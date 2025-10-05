<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\TenantRole;
use App\Models\User;
use App\Rules\RecaptchaRule;
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

        // Store user data temporarily in session instead of creating user immediately
        $request->session()->put('pending_registration', [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'created_at' => now(),
        ]);

        // Store email in session for verification
        $request->session()->put('pending_verification_email', $request->email);

        \Log::info('New user registration - storing data temporarily, redirecting to OTP verification', [
            'user_email' => $request->email
        ]);
        
        return redirect()->route('verification.show')
            ->with('success', 'Registration successful! Please verify your email address.');
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
