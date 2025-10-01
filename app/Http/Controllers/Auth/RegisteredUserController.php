<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\TenantRole;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
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
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'email_verified_at' => null, // Ensure email is not verified initially
        ]);

        // Store email in session for verification
        $request->session()->put('pending_verification_email', $request->email);

        // Generate and send OTP
        $otpRecord = \App\Models\EmailVerificationOtp::generateForEmail($request->email);
        
        try {
            \Mail::to($request->email)->send(new \App\Mail\EmailVerificationOtpMail($otpRecord->otp, $request->email));
            
            \Log::info('New user registration - OTP sent for verification', [
                'user_id' => $user->id,
                'user_email' => $user->email
            ]);
            
            return redirect()->route('verification.show')
                ->with('success', 'Registration successful! Please check your email for the verification code.');
        } catch (\Exception $e) {
            \Log::error('Failed to send OTP email during registration: ' . $e->getMessage());
            
            // If email sending fails, delete the user and show error
            $user->delete();
            
            return back()->with('error', 'Registration failed. Please try again.');
        }
    }
    
    /**
     * Ensure roles exist for a tenant
     */
    private function ensureRolesExistForTenant(Tenant $tenant): void
    {
        // Create Owner role if it doesn't exist
        $ownerRole = TenantRole::firstOrCreate([
            'name' => 'Owner',
            'guard_name' => 'web',
            'tenant_id' => $tenant->id,
        ]);
        
        // Create basic permissions if they don't exist
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
    }
}
