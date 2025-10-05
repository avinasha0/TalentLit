<?php

namespace App\Http\Controllers\Onboarding;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use App\Models\Tenant;
use App\Models\TenantRole;
use App\Models\TenantSubscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class OrganizationController extends Controller
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
            \Log::error('Error in onboarding organization create method', [
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
            \Log::info('Organization form submission', [
                'request_data' => $request->all(),
                'user_agent' => $request->userAgent(),
                'is_mobile' => $request->isMobile(),
                'ip' => $request->ip()
            ]);

            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'slug' => ['required', 'string', 'max:255', 'unique:tenants,slug', 'regex:/^[a-z0-9-]+$/'],
                'website' => ['nullable', 'url', 'max:255'],
                'location' => ['nullable', 'string', 'max:255'],
                'company_size' => ['nullable', 'string', 'max:255'],
            ]);

            // Check if free subscription plan exists
            $freePlan = SubscriptionPlan::where('slug', 'free')->first();
            if (!$freePlan) {
                \Log::error('Free subscription plan not found during organization creation');
                return back()->withErrors(['error' => 'System configuration error. Please contact support.'])->withInput();
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

            // Create roles and permissions for this tenant
            $this->createRolesForTenant($tenant);

            // Assign Owner role to the user
            $ownerRole = TenantRole::where('tenant_id', $tenant->id)->where('name', 'Owner')->first();
            if ($ownerRole) {
                $user->assignRole($ownerRole);
            }

            // Assign free subscription to the new tenant
            $this->assignFreeSubscription($tenant);

            // Set session data
            session(['current_tenant_id' => $tenant->id]);
            session(['last_tenant_slug' => $tenant->slug]);
            
            // Clear the just_verified_email flag since onboarding is starting
            session()->forget('just_verified_email');

            \Log::info('Organization created successfully, redirecting to setup', [
                'tenant_slug' => $tenant->slug,
                'redirect_url' => route('onboarding.setup', $tenant->slug)
            ]);

            return redirect()->route('onboarding.setup', $tenant->slug);
        } catch (\Exception $e) {
            \Log::error('Error in onboarding organization store method', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            
            return back()->withErrors(['error' => 'An error occurred while creating your organization. Please try again.'])->withInput();
        }
    }

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
            'view dashboard', 'view analytics', 'manage users', 'manage settings', 'manage email templates'
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

    /**
     * Assign free subscription to the new tenant
     */
    private function assignFreeSubscription(Tenant $tenant): void
    {
        // Get the free subscription plan
        $freePlan = SubscriptionPlan::where('slug', 'free')->first();
        
        if (!$freePlan) {
            \Log::error('Free subscription plan not found when creating tenant', [
                'tenant_id' => $tenant->id,
                'tenant_slug' => $tenant->slug
            ]);
            return;
        }

        // Check if tenant already has a subscription
        $existingSubscription = TenantSubscription::where('tenant_id', $tenant->id)->first();
        if ($existingSubscription) {
            \Log::info('Tenant already has a subscription, skipping free plan assignment', [
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

        \Log::info('Assigned free subscription to new tenant', [
            'tenant_id' => $tenant->id,
            'tenant_slug' => $tenant->slug,
            'subscription_plan' => 'free'
        ]);
    }
}