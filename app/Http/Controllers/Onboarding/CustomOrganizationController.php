<?php

namespace App\Http\Controllers\Onboarding;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use App\Models\Tenant;
use App\Models\TenantSubscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CustomOrganizationController extends Controller
{
    public function create()
    {
        try {
            // Check if user is authenticated
            if (!auth()->check()) {
                return redirect()->route('login');
            }

            // Check if user already has tenants
            $user = auth()->user();
            if ($user->tenants()->count() > 0) {
                // Check if this is a new registration (user just verified email)
                $isNewRegistration = session()->has('just_verified_email');
                
                if (!$isNewRegistration) {
                    // User already has a tenant and this is not a new registration, redirect to dashboard
                    $firstTenant = $user->tenants()->first();
                    return redirect()->route('tenant.dashboard', $firstTenant->slug);
                }
            }

            return view('onboarding.organization');
        } catch (\Exception $e) {
            Log::error('Error in custom organization create method', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->view('errors.500', [
                'message' => 'An error occurred while loading the organization creation page. Please try again.'
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            // Log the request data for debugging
            Log::info('Custom Organization form submission', [
                'request_data' => $request->all(),
                'user_agent' => $request->userAgent(),
                'is_mobile' => $this->isMobileDevice($request->userAgent()),
                'ip' => $request->ip()
            ]);

            // Custom validation without relying on built-in validation
            $errors = $this->validateOrganizationData($request);
            if (!empty($errors)) {
                return back()->withErrors($errors)->withInput();
            }

            // Start database transaction
            DB::beginTransaction();

            try {
                // Check if free subscription plan exists
                $freePlan = SubscriptionPlan::where('slug', 'free')->first();
                if (!$freePlan) {
                    Log::error('Free subscription plan not found during organization creation');
                    throw new \Exception('System configuration error. Please contact support.');
                }

                // Create the tenant
                $tenant = Tenant::create([
                    'name' => $request->name,
                    'slug' => $request->slug,
                    'website' => $request->website,
                    'location' => $request->location,
                    'company_size' => $request->company_size,
                ]);

                // Add current user to tenant
                $user = auth()->user();
                $user->tenants()->attach($tenant->id);

                // Create custom roles and permissions for this tenant
                $this->createCustomRolesForTenant($tenant);

                // Assign Owner role to the user using custom method
                $this->assignCustomOwnerRole($user, $tenant);

                // Assign free subscription to the new tenant
                $this->assignFreeSubscription($tenant);

                // Set session data
                session(['current_tenant_id' => $tenant->id]);
                session(['last_tenant_slug' => $tenant->slug]);
                
                // Clear the just_verified_email flag since onboarding is starting
                session()->forget('just_verified_email');

                // Commit transaction
                DB::commit();

                Log::info('Custom Organization created successfully, redirecting to dashboard', [
                    'tenant_slug' => $tenant->slug,
                    'redirect_url' => route('tenant.dashboard', $tenant->slug),
                    'user_id' => $user->id,
                ]);

                return redirect()->route('tenant.dashboard', $tenant->slug)
                    ->with('success', 'Organization created successfully! Welcome to TalentLit.');

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (\Exception $e) {
            Log::error('Error in custom organization store method', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            
            return back()->withErrors(['error' => 'An error occurred while creating your organization. Please try again.'])->withInput();
        }
    }

    private function validateOrganizationData(Request $request): array
    {
        $errors = [];

        // Validate name
        if (empty($request->name)) {
            $errors['name'] = 'Organization name is required.';
        } elseif (strlen($request->name) > 255) {
            $errors['name'] = 'Organization name must not exceed 255 characters.';
        }

        // Validate slug
        if (empty($request->slug)) {
            $errors['slug'] = 'Organization URL is required.';
        } elseif (strlen($request->slug) > 255) {
            $errors['slug'] = 'Organization URL must not exceed 255 characters.';
        } elseif (!preg_match('/^[a-z0-9-]+$/', $request->slug)) {
            $errors['slug'] = 'Organization URL can only contain lowercase letters, numbers, and hyphens.';
        } else {
            // Check if slug already exists
            if (Tenant::where('slug', $request->slug)->exists()) {
                $errors['slug'] = 'This organization URL is already taken.';
            }
        }

        // Validate website (optional)
        if (!empty($request->website)) {
            if (!filter_var($request->website, FILTER_VALIDATE_URL)) {
                $errors['website'] = 'Please enter a valid website URL.';
            } elseif (strlen($request->website) > 255) {
                $errors['website'] = 'Website URL must not exceed 255 characters.';
            }
        }

        // Validate location (optional)
        if (!empty($request->location) && strlen($request->location) > 255) {
            $errors['location'] = 'Location must not exceed 255 characters.';
        }

        // Validate company size (optional)
        if (!empty($request->company_size) && strlen($request->company_size) > 255) {
            $errors['company_size'] = 'Company size must not exceed 255 characters.';
        }

        return $errors;
    }

    private function createCustomRolesForTenant(Tenant $tenant): void
    {
        // Create custom roles table if it doesn't exist
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
                'view_interviews', 'create_interviews', 'edit_interviews', 'delete_interviews'
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
                'view_stages', 'reorder_stages',
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
                'view_dashboard', 'view_jobs', 'view_stages', 'view_candidates',
                'view_interviews', 'create_interviews', 'edit_interviews', 'delete_interviews'
            ]),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        Log::info('Custom roles created for tenant', [
            'tenant_id' => $tenant->id,
            'tenant_slug' => $tenant->slug
        ]);
    }

    private function assignCustomOwnerRole(User $user, Tenant $tenant): void
    {
        // Create custom user roles table if it doesn't exist
        if (!DB::getSchemaBuilder()->hasTable('custom_user_roles')) {
            DB::statement('
                CREATE TABLE custom_user_roles (
                    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    user_id BIGINT UNSIGNED NOT NULL,
                    tenant_id VARCHAR(36) NOT NULL,
                    role_name VARCHAR(255) NOT NULL,
                    created_at TIMESTAMP NULL,
                    updated_at TIMESTAMP NULL,
                    UNIQUE KEY unique_user_tenant_role (user_id, tenant_id, role_name),
                    INDEX idx_user_id (user_id),
                    INDEX idx_tenant_id (tenant_id)
                )
            ');
        }

        // Assign Owner role to user
        DB::table('custom_user_roles')->insertOrIgnore([
            'user_id' => $user->id,
            'tenant_id' => $tenant->id,
            'role_name' => 'Owner',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        Log::info('Custom Owner role assigned to user', [
            'user_id' => $user->id,
            'tenant_id' => $tenant->id,
            'tenant_slug' => $tenant->slug
        ]);
    }

    private function assignFreeSubscription(Tenant $tenant): void
    {
        // Get the free subscription plan
        $freePlan = SubscriptionPlan::where('slug', 'free')->first();
        
        if (!$freePlan) {
            Log::error('Free subscription plan not found when creating tenant', [
                'tenant_id' => $tenant->id,
                'tenant_slug' => $tenant->slug
            ]);
            return;
        }

        // Check if tenant already has a subscription
        $existingSubscription = TenantSubscription::where('tenant_id', $tenant->id)->first();
        if ($existingSubscription) {
            Log::info('Tenant already has a subscription, skipping free plan assignment', [
                'tenant_id' => $tenant->id,
                'existing_plan' => $existingSubscription->plan->name
            ]);
            return;
        }

        // Create free subscription
        TenantSubscription::create([
            'tenant_id' => $tenant->id,
            'subscription_plan_id' => $freePlan->id,
            'status' => 'active',
            'starts_at' => now(),
            'expires_at' => null, // Free plans don't expire
            'payment_method' => 'free',
        ]);

        Log::info('Assigned free subscription to new tenant', [
            'tenant_id' => $tenant->id,
            'tenant_slug' => $tenant->slug,
            'subscription_plan' => 'free'
        ]);
    }

    private function isMobileDevice(string $userAgent): bool
    {
        $mobileKeywords = [
            'Mobile', 'Android', 'iPhone', 'iPad', 'iPod', 'BlackBerry', 
            'Windows Phone', 'Opera Mini', 'IEMobile', 'Mobile Safari'
        ];
        
        foreach ($mobileKeywords as $keyword) {
            if (stripos($userAgent, $keyword) !== false) {
                return true;
            }
        }
        
        return false;
    }
}
