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
            'g-recaptcha-response' => ['required', new RecaptchaRule(app(\App\Services\RecaptchaService::class), $request)],
        ]);

        // Create user account but mark as unverified
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'email_verified_at' => null, // Not verified yet
        ]);

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

            return redirect()->route('login')
                ->with('success', '🎉 Registration successful! Please check your email inbox and click the activation link to complete your account setup. If you don\'t see the email, check your spam folder.');
        } catch (\Exception $e) {
            \Log::error('Failed to send activation email', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);

            // Delete the user if email sending fails
            $user->delete();

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
     * Create custom roles for tenant
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
        
        // Create Owner role
        DB::table('custom_tenant_roles')->insertOrIgnore([
            'tenant_id' => $tenant->id,
            'name' => 'Owner',
            'permissions' => json_encode([
                'view_dashboard', 'view_jobs', 'create_jobs', 'edit_jobs', 'delete_jobs', 'publish_jobs', 'close_jobs',
                'manage_stages', 'view_stages', 'create_stages', 'edit_stages', 'delete_stages', 'reorder_stages',
                'view_candidates', 'create_candidates', 'edit_candidates', 'delete_candidates', 'move_candidates', 'import_candidates',
                'view_interviews', 'create_interviews', 'edit_interviews', 'delete_interviews',
                'view_analytics', 'manage_users', 'manage_settings', 'manage_email_templates'
            ]),
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        // Create Admin role
        DB::table('custom_tenant_roles')->insertOrIgnore([
            'tenant_id' => $tenant->id,
            'name' => 'Admin',
            'permissions' => json_encode([
                'view_dashboard', 'view_jobs', 'create_jobs', 'edit_jobs', 'delete_jobs', 'publish_jobs', 'close_jobs',
                'manage_stages', 'view_stages', 'create_stages', 'edit_stages', 'delete_stages', 'reorder_stages',
                'view_candidates', 'create_candidates', 'edit_candidates', 'delete_candidates', 'move_candidates', 'import_candidates',
                'view_interviews', 'create_interviews', 'edit_interviews', 'delete_interviews',
                'view_analytics', 'manage_settings'
            ]),
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        // Create Recruiter role
        DB::table('custom_tenant_roles')->insertOrIgnore([
            'tenant_id' => $tenant->id,
            'name' => 'Recruiter',
            'permissions' => json_encode([
                'view_dashboard', 'view_jobs', 'create_jobs', 'edit_jobs', 'publish_jobs', 'close_jobs',
                'view_candidates', 'create_candidates', 'edit_candidates', 'move_candidates',
                'view_interviews', 'create_interviews', 'edit_interviews', 'delete_interviews'
            ]),
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        // Create Hiring Manager role
        DB::table('custom_tenant_roles')->insertOrIgnore([
            'tenant_id' => $tenant->id,
            'name' => 'Hiring Manager',
            'permissions' => json_encode([
                'view_dashboard', 'view_jobs', 'view_candidates', 'create_candidates', 'edit_candidates', 'move_candidates',
                'view_interviews', 'create_interviews', 'edit_interviews', 'delete_interviews'
            ]),
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
