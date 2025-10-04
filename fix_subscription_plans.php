<?php

/**
 * Fix Subscription Plans in Production
 * 
 * This script ensures that subscription plans are properly seeded in production
 * to fix the "Free subscription plan not found" error.
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

echo "🔧 Fixing Subscription Plans in Production...\n\n";

try {
    // Check if subscription plans exist
    $existingPlans = SubscriptionPlan::count();
    echo "📊 Found {$existingPlans} existing subscription plans\n";

    if ($existingPlans === 0) {
        echo "❌ No subscription plans found! Seeding subscription plans...\n";
        
        // Seed subscription plans
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
                'price' => -1, // Special value to indicate "Contact for Pricing"
                'currency' => 'INR',
                'billing_cycle' => 'monthly',
                'is_active' => true,
                'is_popular' => false,
                'max_users' => -1, // Unlimited
                'max_job_openings' => -1, // Unlimited
                'max_candidates' => -1, // Unlimited
                'max_applications_per_month' => -1, // Unlimited
                'max_interviews_per_month' => -1, // Unlimited
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
            echo "✅ Created/Updated plan: {$plan->name}\n";
        }
    } else {
        echo "✅ Subscription plans already exist\n";
    }

    // Check for tenants without subscriptions
    $tenantsWithoutSubscriptions = Tenant::whereDoesntHave('subscription')->get();
    echo "\n📋 Found {$tenantsWithoutSubscriptions->count()} tenants without subscriptions\n";

    if ($tenantsWithoutSubscriptions->count() > 0) {
        $freePlan = SubscriptionPlan::where('slug', 'free')->first();
        
        if (!$freePlan) {
            echo "❌ Free plan not found! Cannot assign subscriptions.\n";
            exit(1);
        }

        echo "🔄 Assigning free plans to tenants without subscriptions...\n";
        
        foreach ($tenantsWithoutSubscriptions as $tenant) {
            TenantSubscription::create([
                'tenant_id' => $tenant->id,
                'subscription_plan_id' => $freePlan->id,
                'status' => 'active',
                'starts_at' => now(),
                'expires_at' => null, // Free plans don't expire
                'payment_method' => 'free',
            ]);

            echo "✅ Assigned free plan to tenant: {$tenant->name} ({$tenant->slug})\n";
        }
    }

    echo "\n🎉 Subscription plans fix completed successfully!\n";
    echo "📊 Summary:\n";
    echo "   - Total subscription plans: " . SubscriptionPlan::count() . "\n";
    echo "   - Total tenants: " . Tenant::count() . "\n";
    echo "   - Tenants with subscriptions: " . Tenant::whereHas('subscription')->count() . "\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}
