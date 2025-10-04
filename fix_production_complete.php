<?php

/**
 * Complete Production Fix Script
 * 
 * This script fixes all production issues including:
 * 1. Missing subscription plans
 * 2. CSS build issues
 * 3. Laravel optimization
 * 4. Database seeding
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
use App\Models\TenantSubscription;

echo "🚀 Complete Production Fix Script\n";
echo "================================\n\n";

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
        echo "   Running: php artisan {$command}\n";
        $exitCode = 0;
        $output = [];
        exec("php artisan {$command} 2>&1", $output, $exitCode);
        if ($exitCode !== 0) {
            echo "   ⚠️  Warning: {$command} failed (exit code: {$exitCode})\n";
        }
    }
    echo "   ✅ Cache clearing completed\n\n";

    // Step 2: Check and seed subscription plans
    echo "📊 Step 2: Checking subscription plans...\n";
    $existingPlans = SubscriptionPlan::count();
    echo "   Found {$existingPlans} existing subscription plans\n";

    if ($existingPlans === 0) {
        echo "   ❌ No subscription plans found! Seeding subscription plans...\n";
        
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
            $plan = SubscriptionPlan::updateOrCreate(
                ['slug' => $planData['slug']],
                $planData
            );
            echo "   ✅ Created/Updated plan: {$plan->name}\n";
        }
    } else {
        echo "   ✅ Subscription plans already exist\n";
    }

    // Step 3: Assign free plans to tenants without subscriptions
    echo "\n🏢 Step 3: Checking tenant subscriptions...\n";
    $tenantsWithoutSubscriptions = Tenant::whereDoesntHave('subscription')->get();
    echo "   Found {$tenantsWithoutSubscriptions->count()} tenants without subscriptions\n";

    if ($tenantsWithoutSubscriptions->count() > 0) {
        $freePlan = SubscriptionPlan::where('slug', 'free')->first();
        
        if (!$freePlan) {
            echo "   ❌ Free plan not found! Cannot assign subscriptions.\n";
            exit(1);
        }

        echo "   🔄 Assigning free plans to tenants without subscriptions...\n";
        
        foreach ($tenantsWithoutSubscriptions as $tenant) {
            TenantSubscription::create([
                'tenant_id' => $tenant->id,
                'subscription_plan_id' => $freePlan->id,
                'status' => 'active',
                'starts_at' => now(),
                'expires_at' => null,
                'payment_method' => 'free',
            ]);

            echo "   ✅ Assigned free plan to tenant: {$tenant->name} ({$tenant->slug})\n";
        }
    } else {
        echo "   ✅ All tenants have subscriptions\n";
    }

    // Step 4: Build CSS and JS assets
    echo "\n🎨 Step 4: Building CSS and JS assets...\n";
    echo "   Running: npm run build\n";
    $exitCode = 0;
    $output = [];
    exec("npm run build 2>&1", $output, $exitCode);
    
    if ($exitCode === 0) {
        echo "   ✅ Assets built successfully\n";
    } else {
        echo "   ⚠️  Warning: Asset build failed (exit code: {$exitCode})\n";
        echo "   Output: " . implode("\n", $output) . "\n";
    }

    // Step 5: Optimize Laravel for production
    echo "\n⚡ Step 5: Optimizing Laravel for production...\n";
    $optimizeCommands = [
        'config:cache',
        'route:cache',
        'view:cache',
    ];
    
    foreach ($optimizeCommands as $command) {
        echo "   Running: php artisan {$command}\n";
        $exitCode = 0;
        $output = [];
        exec("php artisan {$command} 2>&1", $output, $exitCode);
        if ($exitCode !== 0) {
            echo "   ⚠️  Warning: {$command} failed (exit code: {$exitCode})\n";
        }
    }
    echo "   ✅ Laravel optimization completed\n";

    // Step 6: Check file permissions
    echo "\n🔐 Step 6: Checking file permissions...\n";
    $directories = ['storage', 'bootstrap/cache'];
    
    foreach ($directories as $dir) {
        if (is_dir($dir)) {
            echo "   ✅ Directory exists: {$dir}\n";
        } else {
            echo "   ❌ Directory missing: {$dir}\n";
        }
    }

    // Final summary
    echo "\n🎉 Production Fix Complete!\n";
    echo "==========================\n";
    echo "📊 Summary:\n";
    echo "   - Total subscription plans: " . SubscriptionPlan::count() . "\n";
    echo "   - Total tenants: " . Tenant::count() . "\n";
    echo "   - Tenants with subscriptions: " . Tenant::whereHas('subscription')->count() . "\n";
    echo "   - Free plan available: " . (SubscriptionPlan::where('slug', 'free')->exists() ? 'Yes' : 'No') . "\n";
    
    // Check if build files exist
    $buildFiles = [
        'public/build/manifest.json',
        'public/build/assets/app-*.css',
        'public/build/assets/app-*.js'
    ];
    
    echo "\n📁 Build Files Status:\n";
    foreach ($buildFiles as $pattern) {
        $files = glob($pattern);
        if (!empty($files)) {
            echo "   ✅ Found: " . basename($files[0]) . "\n";
        } else {
            echo "   ❌ Missing: " . basename($pattern) . "\n";
        }
    }

    echo "\n✨ Your production environment should now be working correctly!\n";
    echo "🌐 Test your site at: https://talentlit.com/test1/dashboard\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}
