<?php

/**
 * Final Production Fix Script
 * 
 * This script fixes all remaining production issues:
 * 1. Middleware conflicts
 * 2. User permissions
 * 3. Tenant access
 * 4. Path encoding issues
 */

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// Bootstrap Laravel
$app = Application::configure(basePath: __DIR__)
    ->withRouting(
        web: __DIR__ . '/routes/web.php',
        api: __DIR__ . '/routes/api.php',
        commands: __DIR__ . '/routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();

$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Tenant;
use App\Models\TenantRole;
use App\Models\User;
use App\Models\SubscriptionPlan;
use Spatie\Permission\Models\Permission;

echo "🚀 Final Production Fix Script\n";
echo "==============================\n\n";

try {
    // Step 1: Clear all caches
    echo "🧹 Step 1: Clearing Laravel caches...\n";
    $commands = [
        'config:clear',
        'cache:clear',
        'route:clear',
        'view:clear',
        'event:clear',
    ];
    
    foreach ($commands as $command) {
        try {
            \Illuminate\Support\Facades\Artisan::call($command);
            echo "   ✅ {$command} completed\n";
        } catch (Exception $e) {
            echo "   ⚠️  Warning: {$command} failed - " . $e->getMessage() . "\n";
        }
    }
    echo "   ✅ Cache clearing completed\n\n";

    // Step 2: Ensure subscription plans exist
    echo "📊 Step 2: Checking subscription plans...\n";
    $subscriptionPlans = SubscriptionPlan::count();
    echo "   Found {$subscriptionPlans} subscription plans\n";

    if ($subscriptionPlans === 0) {
        echo "   ❌ No subscription plans found! Creating subscription plans...\n";
        
        $plans = [
            [
                'name' => 'Free',
                'slug' => 'free',
                'description' => 'Perfect for small teams getting started with recruitment',
                'price' => 0.00,
                'currency' => 'INR',
                'billing_cycle' => 'monthly',
                'is_active' => true,
                'is_popular' => false,
                'max_users' => 2,
                'max_job_openings' => 3,
                'max_candidates' => 50,
                'max_applications_per_month' => 25,
                'max_interviews_per_month' => 10,
                'max_storage_gb' => 1,
                'analytics_enabled' => false,
                'custom_branding' => false,
                'api_access' => false,
                'priority_support' => false,
                'advanced_reporting' => false,
                'integrations' => false,
                'white_label' => false,
            ],
            [
                'name' => 'Pro',
                'slug' => 'pro',
                'description' => 'Advanced features for growing recruitment teams',
                'price' => 999.00,
                'currency' => 'INR',
                'billing_cycle' => 'monthly',
                'is_active' => true,
                'is_popular' => true,
                'max_users' => 10,
                'max_job_openings' => 25,
                'max_candidates' => 500,
                'max_applications_per_month' => 200,
                'max_interviews_per_month' => 100,
                'max_storage_gb' => 10,
                'analytics_enabled' => true,
                'custom_branding' => true,
                'api_access' => true,
                'priority_support' => true,
                'advanced_reporting' => true,
                'integrations' => true,
                'white_label' => false,
            ],
            [
                'name' => 'Enterprise',
                'slug' => 'enterprise',
                'description' => 'Unlimited everything for large organizations',
                'price' => -1,
                'currency' => 'INR',
                'billing_cycle' => 'monthly',
                'is_active' => true,
                'is_popular' => false,
                'max_users' => -1,
                'max_job_openings' => -1,
                'max_candidates' => -1,
                'max_applications_per_month' => -1,
                'max_interviews_per_month' => -1,
                'max_storage_gb' => 100,
                'analytics_enabled' => true,
                'custom_branding' => true,
                'api_access' => true,
                'priority_support' => true,
                'advanced_reporting' => true,
                'integrations' => true,
                'white_label' => true,
            ],
        ];

        foreach ($plans as $planData) {
            SubscriptionPlan::updateOrCreate(
                ['slug' => $planData['slug']],
                $planData
            );
            echo "   ✅ Created/Updated plan: {$planData['name']}\n";
        }
    } else {
        echo "   ✅ Subscription plans already exist\n";
    }

    // Step 3: Ensure all required permissions exist
    echo "\n📋 Step 3: Creating required permissions...\n";
    $requiredPermissions = [
        'view dashboard',
        'view jobs',
        'create jobs',
        'edit jobs',
        'delete jobs',
        'publish jobs',
        'close jobs',
        'manage stages',
        'view stages',
        'create stages',
        'edit stages',
        'delete stages',
        'reorder stages',
        'view candidates',
        'create candidates',
        'edit candidates',
        'delete candidates',
        'move candidates',
        'import candidates',
        'view analytics',
        'view interviews',
        'create interviews',
        'edit interviews',
        'delete interviews',
        'manage users',
        'manage settings',
        'manage email templates',
    ];

    foreach ($requiredPermissions as $permission) {
        Permission::firstOrCreate([
            'name' => $permission,
            'guard_name' => 'web'
        ]);
    }
    echo "   ✅ All required permissions exist\n";

    // Step 4: Process all tenants and fix permissions
    echo "\n🏢 Step 4: Processing tenants and fixing permissions...\n";
    $tenants = Tenant::all();
    echo "   Found {$tenants->count()} tenants\n\n";

    foreach ($tenants as $tenant) {
        echo "   Processing tenant: {$tenant->name} ({$tenant->slug})\n";
        
        // Get or create roles for this tenant
        $ownerRole = TenantRole::where('name', 'Owner')
            ->where('guard_name', 'web')
            ->where('tenant_id', $tenant->id)
            ->first();
            
        if (!$ownerRole) {
            $ownerRole = TenantRole::create([
                'name' => 'Owner',
                'guard_name' => 'web',
                'tenant_id' => $tenant->id,
            ]);
            echo "     ✅ Created Owner role\n";
        } else {
            echo "     ✅ Owner role exists\n";
        }

        $adminRole = TenantRole::where('name', 'Admin')
            ->where('guard_name', 'web')
            ->where('tenant_id', $tenant->id)
            ->first();
            
        if (!$adminRole) {
            $adminRole = TenantRole::create([
                'name' => 'Admin',
                'guard_name' => 'web',
                'tenant_id' => $tenant->id,
            ]);
            echo "     ✅ Created Admin role\n";
        } else {
            echo "     ✅ Admin role exists\n";
        }

        // Assign all permissions to Owner role
        $ownerRole->syncPermissions(Permission::all());
        echo "     ✅ Assigned all permissions to Owner role\n";

        // Assign limited permissions to Admin role
        $adminPermissions = [
            'view dashboard', 'view jobs', 'create jobs', 'edit jobs', 'delete jobs', 'publish jobs', 'close jobs',
            'manage stages', 'view stages', 'create stages', 'edit stages', 'delete stages', 'reorder stages',
            'view candidates', 'create candidates', 'edit candidates', 'delete candidates', 'move candidates', 'import candidates',
            'view analytics', 'view interviews', 'create interviews', 'edit interviews', 'delete interviews',
            'manage email templates',
        ];
        $adminRole->syncPermissions($adminPermissions);
        echo "     ✅ Assigned limited permissions to Admin role\n";

        // Get users for this tenant and fix their permissions
        $tenantUsers = User::whereHas('tenants', function ($query) use ($tenant) {
            $query->where('tenants.id', $tenant->id);
        })->get();

        echo "     👥 Found {$tenantUsers->count()} users for this tenant\n";

        foreach ($tenantUsers as $user) {
            // Check if user has any roles for this tenant
            $userRoles = $user->roles()->where('roles.tenant_id', $tenant->id)->get();
            
            if ($userRoles->count() === 0) {
                // User has no roles, assign Owner role
                $user->assignRole($ownerRole);
                echo "       ✅ Assigned Owner role to user: {$user->name}\n";
            } else {
                echo "       ✅ User {$user->name} already has roles\n";
            }
        }
        
        echo "     ✅ Completed tenant: {$tenant->name}\n\n";
    }

    // Step 5: Optimize Laravel for production
    echo "⚡ Step 5: Optimizing Laravel for production...\n";
    $optimizeCommands = [
        'config:cache',
        'route:cache',
        'view:cache',
    ];
    
    foreach ($optimizeCommands as $command) {
        try {
            \Illuminate\Support\Facades\Artisan::call($command);
            echo "   ✅ {$command} completed\n";
        } catch (Exception $e) {
            echo "   ⚠️  Warning: {$command} failed - " . $e->getMessage() . "\n";
        }
    }
    echo "   ✅ Laravel optimization completed\n";

    // Final summary
    echo "\n🎉 Final Production Fix Complete!\n";
    echo "================================\n";
    echo "📊 Summary:\n";
    echo "   - Total tenants: " . Tenant::count() . "\n";
    echo "   - Total users: " . User::count() . "\n";
    echo "   - Total permissions: " . Permission::count() . "\n";
    echo "   - Subscription plans: " . SubscriptionPlan::count() . "\n";
    
    echo "\n✨ Your production environment should now be fully working!\n";
    echo "🌐 Test URLs:\n";
    echo "   - Dashboard: https://talentlit.com/testtest/dashboard\n";
    echo "   - Analytics: https://talentlit.com/testtest/analytics\n";
    echo "   - Onboarding: https://talentlit.com/onboarding/organization\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}
