<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\EmailVerificationOtpMail;
use App\Models\EmailVerificationOtp;
use App\Models\SubscriptionPlan;
use App\Models\Tenant;
use App\Models\TenantRole;
use App\Models\TenantSubscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;

class EmailVerificationController extends Controller
{
    /**
     * Show the email verification form
     */
    public function show(Request $request)
    {
        $email = $request->session()->get('pending_verification_email');
        
        if (!$email) {
            return redirect()->route('register')->with('error', 'No pending verification found.');
        }

        return view('auth.verify-email', compact('email'));
    }

    /**
     * Send OTP to email
     */
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $email = $request->email;
        $user = User::where('email', $email)->first();

        if (!$user || $user->email_verified_at) {
            return redirect()->route('register')->with('error', 'Invalid email or already verified.');
        }

        // Generate OTP
        $otpRecord = EmailVerificationOtp::generateForEmail($email);

        // Send email
        try {
            Mail::to($email)->send(new EmailVerificationOtpMail($otpRecord->otp, $email));
            
            $request->session()->put('pending_verification_email', $email);
            
            return back()->with('success', 'Verification code sent to your email address.');
        } catch (\Exception $e) {
            \Log::error('Failed to send OTP email: ' . $e->getMessage());
            return back()->with('error', 'Failed to send verification code. Please try again.');
        }
    }

    /**
     * Verify OTP and complete registration
     */
    public function verify(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|string|size:6'
        ]);

        $email = $request->email;
        $otp = $request->otp;

        // Verify OTP
        if (!EmailVerificationOtp::verify($email, $otp)) {
            return back()->with('error', 'Invalid or expired verification code.');
        }

        // Get user and mark email as verified
        $user = User::where('email', $email)->first();
        if (!$user) {
            return redirect()->route('register')->with('error', 'User not found.');
        }

        $user->update(['email_verified_at' => now()]);

        // Clear session
        $request->session()->forget('pending_verification_email');

        // Log the user in
        Auth::login($user);

        // Create tenant and assign free subscription
        $this->createTenantWithFreeSubscription($user);

        return redirect()->route('onboarding.organization')
            ->with('success', 'Email verified successfully! Welcome to HireHub.');
    }

    /**
     * Resend OTP
     */
    public function resend(Request $request)
    {
        $email = $request->session()->get('pending_verification_email');
        
        if (!$email) {
            return redirect()->route('register')->with('error', 'No pending verification found.');
        }

        return $this->sendOtp($request->merge(['email' => $email]));
    }

    /**
     * Create tenant and assign free subscription for new user
     */
    private function createTenantWithFreeSubscription(User $user)
    {
        // Get the free subscription plan
        $freePlan = SubscriptionPlan::where('slug', 'free')->first();
        
        if (!$freePlan) {
            \Log::error('Free subscription plan not found');
            return;
        }

        // Create a temporary tenant for the user (will be updated during onboarding)
        $tenant = Tenant::create([
            'name' => $user->name . "'s Organization",
            'slug' => 'temp-' . $user->id . '-' . time(),
            'website' => null,
            'location' => null,
            'company_size' => null,
        ]);

        // Add user to tenant
        $user->tenants()->attach($tenant->id);

        // Create roles for this tenant
        $this->createRolesForTenant($tenant);

        // Assign Owner role to the user
        $ownerRole = TenantRole::where('tenant_id', $tenant->id)->where('name', 'Owner')->first();
        if ($ownerRole) {
            $user->assignRole($ownerRole);
        }

        // Assign free subscription
        TenantSubscription::create([
            'tenant_id' => $tenant->id,
            'subscription_plan_id' => $freePlan->id,
            'status' => 'active',
            'starts_at' => now(),
            'expires_at' => null, // Free plans don't expire
            'payment_method' => 'free',
        ]);

        // Set session data
        session(['current_tenant_id' => $tenant->id]);
        session(['last_tenant_slug' => $tenant->slug]);

        \Log::info('Created tenant with free subscription for user', [
            'user_id' => $user->id,
            'tenant_id' => $tenant->id,
            'subscription_plan' => 'free'
        ]);
    }

    /**
     * Create roles and permissions for a tenant
     */
    private function createRolesForTenant(Tenant $tenant): void
    {
        // Create Owner role
        $ownerRole = TenantRole::firstOrCreate([
            'name' => 'Owner',
            'guard_name' => 'web',
            'tenant_id' => $tenant->id,
        ]);

        // Create Admin role
        $adminRole = TenantRole::firstOrCreate([
            'name' => 'Admin',
            'guard_name' => 'web',
            'tenant_id' => $tenant->id,
        ]);

        // Create Recruiter role
        $recruiterRole = TenantRole::firstOrCreate([
            'name' => 'Recruiter',
            'guard_name' => 'web',
            'tenant_id' => $tenant->id,
        ]);

        // Create Hiring Manager role
        $hiringManagerRole = TenantRole::firstOrCreate([
            'name' => 'Hiring Manager',
            'guard_name' => 'web',
            'tenant_id' => $tenant->id,
        ]);

        // Create basic permissions
        $permissions = [
            'view jobs', 'create jobs', 'edit jobs', 'delete jobs', 'publish jobs', 'close jobs',
            'manage stages', 'view stages', 'create stages', 'edit stages', 'delete stages', 'reorder stages',
            'view candidates', 'create candidates', 'edit candidates', 'delete candidates', 'move candidates', 'import candidates',
            'view dashboard', 'manage users', 'manage settings'
        ];

        foreach ($permissions as $permission) {
            \Spatie\Permission\Models\Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
        }

        // Assign all permissions to Owner role
        $ownerRole->syncPermissions(\Spatie\Permission\Models\Permission::all());

        // Assign limited permissions to other roles
        $adminRole->syncPermissions([
            'view jobs', 'create jobs', 'edit jobs', 'delete jobs', 'publish jobs', 'close jobs',
            'manage stages', 'view stages', 'create stages', 'edit stages', 'delete stages', 'reorder stages',
            'view candidates', 'create candidates', 'edit candidates', 'delete candidates', 'move candidates', 'import candidates',
            'view dashboard'
        ]);

        $recruiterRole->syncPermissions([
            'view jobs', 'create jobs', 'edit jobs', 'publish jobs', 'close jobs',
            'view stages', 'reorder stages',
            'view candidates', 'create candidates', 'edit candidates', 'move candidates',
            'view dashboard'
        ]);

        $hiringManagerRole->syncPermissions([
            'view jobs', 'view stages', 'view candidates', 'view dashboard'
        ]);
    }
}
