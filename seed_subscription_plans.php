<?php

/**
 * Quick Subscription Plans Seeder
 * 
 * This script quickly seeds subscription plans without running the full Laravel application.
 * Use this if you need to quickly fix the subscription plan issue.
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

echo "🌱 Quick Subscription Plans Seeder\n";
echo "==================================\n\n";

try {
    // Check if plans already exist
    $existingPlans = SubscriptionPlan::count();
    echo "📊 Found {$existingPlans} existing subscription plans\n";

    if ($existingPlans > 0) {
        echo "✅ Subscription plans already exist. No action needed.\n";
        echo "\nExisting plans:\n";
        foreach (SubscriptionPlan::all() as $plan) {
            echo "   - {$plan->name} ({$plan->slug}) - {$plan->currency} {$plan->price}\n";
        }
        exit(0);
    }

    // Create subscription plans
    echo "🔄 Creating subscription plans...\n";
    
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
            'price' => 997.00,
            'actual_price' => 3999.00,
            'discount_price' => 997.00,
            'currency' => 'INR',
            'billing_cycle' => 'monthly',
            'is_active' => true,
            'is_popular' => true,
            'max_users' => 4,
            'max_job_openings' => 10,
            'max_candidates' => 200,
            'max_applications_per_month' => 80,
            'max_interviews_per_month' => 40,
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
            'name' => 'Pro Yearly',
            'slug' => 'pro-yearly',
            'description' => 'Advanced features for growing recruitment teams with enhanced limits',
            'price' => 9997.00,
            'actual_price' => 47974.00,
            'discount_price' => 9997.00,
            'currency' => 'INR',
            'billing_cycle' => 'yearly',
            'is_active' => true,
            'is_popular' => false,
            'max_users' => 6,
            'max_job_openings' => 15,
            'max_candidates' => 300,
            'max_applications_per_month' => 125,
            'max_interviews_per_month' => 60,
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
        $plan = SubscriptionPlan::create($planData);
        echo "✅ Created plan: {$plan->name} ({$plan->slug})\n";
    }

    echo "\n🎉 Successfully created " . count($plans) . " subscription plans!\n";
    echo "✨ The 'Free subscription plan not found' error should now be resolved.\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}
