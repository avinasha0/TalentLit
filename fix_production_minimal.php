<?php

/**
 * Minimal Production Fix Script
 * 
 * This script fixes only the essential issues without running complex seeders.
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

use App\Models\SubscriptionPlan;
use App\Models\Tenant;
use App\Models\User;
use Spatie\Permission\Models\Permission;

echo "🚀 Minimal Production Fix Script\n";
echo "===============================\n\n";

try {
    // Step 1: Clear caches
    echo "🧹 Step 1: Clearing Laravel caches...\n";
    $commands = ['config:clear', 'cache:clear', 'route:clear', 'view:clear'];
    
    foreach ($commands as $command) {
        try {
            \Illuminate\Support\Facades\Artisan::call($command);
            echo "   ✅ {$command} completed\n";
        } catch (Exception $e) {
            echo "   ⚠️  Warning: {$command} failed\n";
        }
    }

    // Step 2: Ensure subscription plans exist
    echo "\n📊 Step 2: Checking subscription plans...\n";
    $subscriptionPlans = SubscriptionPlan::count();
    echo "   Found {$subscriptionPlans} subscription plans\n";
    
    if ($subscriptionPlans === 0) {
        echo "   ❌ No subscription plans found! Creating basic plans...\n";
        
        // Create only the essential free plan
        SubscriptionPlan::create([
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
        ]);
        echo "   ✅ Created free subscription plan\n";
    } else {
        echo "   ✅ Subscription plans already exist\n";
    }

    // Step 3: Create essential permissions only
    echo "\n📋 Step 3: Creating essential permissions...\n";
    $essentialPermissions = ['view dashboard', 'view analytics'];
    
    foreach ($essentialPermissions as $permission) {
        Permission::firstOrCreate([
            'name' => $permission,
            'guard_name' => 'web'
        ]);
    }
    echo "   ✅ Essential permissions created\n";

    // Step 4: Quick fix for users without permissions and roles
    echo "\n👥 Step 4: Quick user permission and role fix...\n";
    $users = User::all();
    echo "   Found {$users->count()} users\n";
    
    foreach ($users as $user) {
        // Give users the essential permissions directly
        if (!$user->hasPermissionTo('view dashboard')) {
            $user->givePermissionTo('view dashboard');
            echo "   ✅ Added dashboard permission to user: {$user->name}\n";
        }
        
        if (!$user->hasPermissionTo('view analytics')) {
            $user->givePermissionTo('view analytics');
            echo "   ✅ Added analytics permission to user: {$user->name}\n";
        }
        
        if (!$user->hasPermissionTo('manage settings')) {
            $user->givePermissionTo('manage settings');
            echo "   ✅ Added settings permission to user: {$user->name}\n";
        }
        
        // Check if user has any roles for any tenant
        $userRoles = $user->roles()->count();
        if ($userRoles === 0) {
            // User has no roles, give them Owner role for their first tenant
            $firstTenant = $user->tenants()->first();
            if ($firstTenant) {
                // Create Owner role for this tenant if it doesn't exist
                $ownerRole = \App\Models\TenantRole::where('name', 'Owner')
                    ->where('guard_name', 'web')
                    ->where('tenant_id', $firstTenant->id)
                    ->first();
                    
                if (!$ownerRole) {
                    $ownerRole = \App\Models\TenantRole::updateOrCreate(
                        [
                            'name' => 'Owner',
                            'guard_name' => 'web',
                            'tenant_id' => $firstTenant->id,
                        ],
                        [
                            'name' => 'Owner',
                            'guard_name' => 'web',
                            'tenant_id' => $firstTenant->id,
                        ]
                    );
                    // Give Owner role all permissions
                    $ownerRole->syncPermissions(Permission::all());
                }
                
                $user->assignRole($ownerRole);
                echo "   ✅ Assigned Owner role to user: {$user->name} for tenant: {$firstTenant->name}\n";
            }
        }
    }

    // Step 5: Optimize for production
    echo "\n⚡ Step 5: Optimizing for production...\n";
    try {
        \Illuminate\Support\Facades\Artisan::call('config:cache');
        echo "   ✅ Config cached\n";
    } catch (Exception $e) {
        echo "   ⚠️  Config cache failed\n";
    }

    echo "\n🎉 Minimal production fix completed!\n";
    echo "===================================\n";
    echo "✨ Essential issues should now be resolved.\n";
    echo "🌐 Test at: https://talentlit.com/testtest/dashboard\n";
    echo "\n📝 Note: This is a minimal fix. For full functionality,\n";
    echo "   you may need to run the complete seeders later.\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
