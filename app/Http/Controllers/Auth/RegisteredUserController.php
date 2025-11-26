<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\TenantRole;
use App\Models\User;
use App\Mail\EmailActivationMail;
use App\Rules\RecaptchaRule;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(Request $request): View
    {
        // Store referral code in session if present
        if ($request->has('ref')) {
            $refValue = urldecode($request->get('ref'));
            
            // Try to find tenant by name first (new approach)
            $tenant = Tenant::where('name', $refValue)->first();
            
            if ($tenant) {
                // Find a user from this tenant to track the referral
                // Get the first user from this tenant (preferably owner)
                $referrer = $tenant->users()->first();
                
                if ($referrer) {
                    // Ensure the referrer has a referral code
                    $referralCode = $referrer->referral_code ?? $referrer->generateReferralCode();
                    session(['referral_code' => $referralCode]);
                }
            } else {
                // Fallback to old system: find user by referral code
                $referrer = User::where('referral_code', $refValue)->first();
                if ($referrer) {
                    session(['referral_code' => $refValue]);
                }
            }
        }
        
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 
                'string', 
                'lowercase', 
                'email', 
                'max:255', 
                Rule::unique('users')->where(function ($query) {
                    return $query->whereNotNull('email_verified_at');
                })
            ],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'g-recaptcha-response' => ['required', new RecaptchaRule(app(\App\Services\RecaptchaService::class), $request)],
        ]);

        // Check if user already exists but is unverified
        $user = User::where('email', $request->email)
            ->whereNull('email_verified_at')
            ->first();

        if ($user) {
            // Update existing unverified user
            $user->update([
                'name' => $request->name,
                'password' => Hash::make($request->password),
            ]);
        } else {
            // Create new user account but mark as unverified
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'email_verified_at' => null, // Not verified yet
            ]);
        }

        // Generate activation token
        $activationToken = Str::random(60);
        $user->activation_token = $activationToken;
        $user->save();

        // Generate activation URL
        $activationUrl = route('auth.activate', ['token' => $activationToken]);

        // Send activation email
        try {
            Mail::to($user->email)->send(new EmailActivationMail([
                'name' => $user->name,
                'email' => $user->email,
            ], $activationUrl));

            \Log::info('User registered successfully, activation email sent', [
                'user_id' => $user->id,
                'user_email' => $user->email
            ]);

            $message = $user->wasRecentlyCreated 
                ? '🎉 Registration successful! Please check your email inbox and click the activation link to complete your account setup. If you don\'t see the email, check your spam folder.'
                : '📧 Activation email resent! Please check your email inbox and click the activation link to complete your account setup. If you don\'t see the email, check your spam folder.';
            
            return redirect()->route('login')->with('success', $message);
        } catch (\Exception $e) {
            \Log::error('Failed to send activation email', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);

            // Delete the user if email sending fails and it was newly created
            if ($user->wasRecentlyCreated) {
                $user->delete();
            }

            return back()->with('error', 'Failed to send activation email. Please try again.');
        }
    }
    
    /**
     * Ensure roles exist for a tenant using custom permission system
     */
    private function ensureRolesExistForTenant(Tenant $tenant): void
    {
        // Create custom roles for this tenant if they don't exist
        $this->createCustomRolesForTenant($tenant);
    }
    
    /**
     * Create custom roles for tenant using PermissionService
     */
    private function createCustomRolesForTenant(Tenant $tenant): void
    {
        // Ensure custom tables exist
        if (!DB::getSchemaBuilder()->hasTable('custom_tenant_roles')) {
            DB::statement('
                CREATE TABLE custom_tenant_roles (
                    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    tenant_id VARCHAR(36) NOT NULL,
                    name VARCHAR(255) NOT NULL,
                    permissions JSON,
                    created_at TIMESTAMP NULL,
                    updated_at TIMESTAMP NULL,
                    UNIQUE KEY unique_tenant_role (tenant_id, name),
                    INDEX idx_tenant_id (tenant_id)
                )
            ');
        }
        
        // Use PermissionService to ensure all roles are created with correct permissions
        app(\App\Services\PermissionService::class)->ensureTenantRoles($tenant->id);
    }
}
