<?php

/**
 * Hostinger-Compatible Production Fix Script
 * 
 * This script fixes production issues without using exec() function
 * which is disabled on Hostinger shared hosting.
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
use Illuminate\Support\Facades\Artisan;

echo "🚀 Hostinger Production Fix Script\n";
echo "==================================\n\n";

try {
    // Step 1: Clear all caches using Artisan facade
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
        try {
            Artisan::call($command);
            echo "   ✅ {$command} completed\n";
        } catch (Exception $e) {
            echo "   ⚠️  Warning: {$command} failed - " . $e->getMessage() . "\n";
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

    // Step 4: Optimize Laravel for production (without exec)
    echo "\n⚡ Step 4: Optimizing Laravel for production...\n";
    $optimizeCommands = [
        'config:cache',
        'route:cache',
        'view:cache',
    ];
    
    foreach ($optimizeCommands as $command) {
        echo "   Running: php artisan {$command}\n";
        try {
            Artisan::call($command);
            echo "   ✅ {$command} completed\n";
        } catch (Exception $e) {
            echo "   ⚠️  Warning: {$command} failed - " . $e->getMessage() . "\n";
        }
    }
    echo "   ✅ Laravel optimization completed\n";

    // Step 5: Check file permissions and directories
    echo "\n🔐 Step 5: Checking file permissions...\n";
    $directories = ['storage', 'bootstrap/cache'];
    
    foreach ($directories as $dir) {
        if (is_dir($dir)) {
            echo "   ✅ Directory exists: {$dir}\n";
            if (is_writable($dir)) {
                echo "   ✅ Directory is writable: {$dir}\n";
            } else {
                echo "   ⚠️  Directory not writable: {$dir}\n";
            }
        } else {
            echo "   ❌ Directory missing: {$dir}\n";
        }
    }

    // Step 6: Check if build files exist
    echo "\n📁 Step 6: Checking build files...\n";
    $buildFiles = [
        'public/build/manifest.json',
    ];
    
    foreach ($buildFiles as $file) {
        if (file_exists($file)) {
            echo "   ✅ Found: " . basename($file) . "\n";
        } else {
            echo "   ❌ Missing: " . basename($file) . "\n";
        }
    }

    // Check for CSS and JS files
    $cssFiles = glob('public/build/assets/*.css');
    $jsFiles = glob('public/build/assets/*.js');
    
    if (!empty($cssFiles)) {
        echo "   ✅ Found CSS files: " . count($cssFiles) . " files\n";
    } else {
        echo "   ❌ No CSS files found in public/build/assets/\n";
    }
    
    if (!empty($jsFiles)) {
        echo "   ✅ Found JS files: " . count($jsFiles) . " files\n";
    } else {
        echo "   ❌ No JS files found in public/build/assets/\n";
    }

    // Final summary
    echo "\n🎉 Hostinger Production Fix Complete!\n";
    echo "=====================================\n";
    echo "📊 Summary:\n";
    echo "   - Total subscription plans: " . SubscriptionPlan::count() . "\n";
    echo "   - Total tenants: " . Tenant::count() . "\n";
    echo "   - Tenants with subscriptions: " . Tenant::whereHas('subscription')->count() . "\n";
    echo "   - Free plan available: " . (SubscriptionPlan::where('slug', 'free')->exists() ? 'Yes' : 'No') . "\n";
    
    echo "\n✨ Your production environment should now be working correctly!\n";
    echo "🌐 Test your site at: https://talentlit.com/test1/dashboard\n";
    
    echo "\n📝 Note: If CSS/JS files are missing, you may need to:\n";
    echo "   1. Build assets locally and upload them\n";
    echo "   2. Or contact Hostinger support to enable Node.js\n";
    echo "   3. Or use a CDN for your assets\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}
