<?php

/**
 * Test Onboarding Organization Page
 * 
 * This script tests the onboarding organization functionality
 * to identify what's causing the 500 error.
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

echo "🧪 Testing Onboarding Organization Page\n";
echo "=====================================\n\n";

try {
    // Test 1: Check if subscription plans exist
    echo "📊 Test 1: Checking subscription plans...\n";
    $subscriptionPlans = SubscriptionPlan::count();
    echo "   Found {$subscriptionPlans} subscription plans\n";
    
    if ($subscriptionPlans === 0) {
        echo "   ❌ No subscription plans found - this could cause the 500 error\n";
    } else {
        echo "   ✅ Subscription plans exist\n";
    }

    // Test 2: Check if free plan exists
    echo "\n🆓 Test 2: Checking free subscription plan...\n";
    $freePlan = SubscriptionPlan::where('slug', 'free')->first();
    if ($freePlan) {
        echo "   ✅ Free plan exists: {$freePlan->name}\n";
    } else {
        echo "   ❌ Free plan not found - this will cause tenant creation to fail\n";
    }

    // Test 3: Check tenants
    echo "\n🏢 Test 3: Checking tenants...\n";
    $tenants = Tenant::count();
    echo "   Found {$tenants} tenants\n";

    // Test 4: Check users
    echo "\n👥 Test 4: Checking users...\n";
    $users = User::count();
    echo "   Found {$users} users\n";

    // Test 5: Check if we can create a test tenant (without saving)
    echo "\n🧪 Test 5: Testing tenant creation logic...\n";
    try {
        $testTenant = new Tenant([
            'name' => 'Test Organization',
            'slug' => 'test-org-' . time(),
            'website' => 'https://test.com',
            'location' => 'Test City',
            'company_size' => '1-10',
        ]);
        
        // Validate the model
        $testTenant->validate();
        echo "   ✅ Tenant model validation passed\n";
        
        // Test if we can save (but don't actually save)
        echo "   ✅ Tenant creation logic should work\n";
        
    } catch (Exception $e) {
        echo "   ❌ Tenant creation failed: " . $e->getMessage() . "\n";
    }

    // Test 6: Check permissions
    echo "\n🔐 Test 6: Checking permissions...\n";
    $permissions = \Spatie\Permission\Models\Permission::count();
    echo "   Found {$permissions} permissions\n";
    
    $analyticsPermission = \Spatie\Permission\Models\Permission::where('name', 'view analytics')->first();
    if ($analyticsPermission) {
        echo "   ✅ Analytics permission exists\n";
    } else {
        echo "   ❌ Analytics permission not found\n";
    }

    // Test 7: Check if the view file exists
    echo "\n📄 Test 7: Checking view file...\n";
    $viewPath = resource_path('views/onboarding/organization.blade.php');
    if (file_exists($viewPath)) {
        echo "   ✅ View file exists: {$viewPath}\n";
    } else {
        echo "   ❌ View file not found: {$viewPath}\n";
    }

    // Test 8: Check if the layout file exists
    echo "\n🎨 Test 8: Checking layout file...\n";
    $layoutPath = resource_path('views/components/onboarding-layout.blade.php');
    if (file_exists($layoutPath)) {
        echo "   ✅ Layout file exists: {$layoutPath}\n";
    } else {
        echo "   ❌ Layout file not found: {$layoutPath}\n";
    }

    echo "\n🎉 Test completed!\n";
    echo "==================\n";
    echo "If all tests pass, the 500 error might be due to:\n";
    echo "1. Missing authentication\n";
    echo "2. Database connection issues\n";
    echo "3. Missing environment variables\n";
    echo "4. File permission issues\n";
    echo "5. PHP errors in the controller\n";

} catch (Exception $e) {
    echo "❌ Error during testing: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}
