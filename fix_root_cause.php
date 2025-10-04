<?php

/**
 * Fix Root Cause - Missing Dashboard Permission
 * 
 * This script fixes the actual root cause of the 403 error:
 * Users don't have the 'view dashboard' permission.
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

use App\Models\User;
use Spatie\Permission\Models\Permission;

echo "🎯 Fix Root Cause - Dashboard Permission\n";
echo "========================================\n\n";

try {
    // Step 1: Ensure 'view dashboard' permission exists
    echo "📋 Step 1: Creating 'view dashboard' permission...\n";
    $dashboardPermission = Permission::firstOrCreate([
        'name' => 'view dashboard',
        'guard_name' => 'web'
    ]);
    echo "   ✅ 'view dashboard' permission exists\n";

    // Step 2: Get all users and give them the permission
    echo "\n👥 Step 2: Assigning dashboard permission to all users...\n";
    $users = User::all();
    echo "   Found {$users->count()} users\n\n";
    
    foreach ($users as $user) {
        echo "   User: {$user->name} ({$user->email})\n";
        
        if (!$user->hasPermissionTo('view dashboard')) {
            $user->givePermissionTo('view dashboard');
            echo "     ✅ Added 'view dashboard' permission\n";
        } else {
            echo "     ✅ Already has 'view dashboard' permission\n";
        }
        
        // Test the permission
        $hasPermission = $user->hasPermissionTo('view dashboard');
        echo "     Permission check: " . ($hasPermission ? 'PASS' : 'FAIL') . "\n\n";
    }

    // Step 3: Test a specific user
    echo "🧪 Step 3: Testing permission for specific user...\n";
    $testUser = User::first();
    if ($testUser) {
        echo "   Testing user: {$testUser->name}\n";
        echo "   Has 'view dashboard': " . ($testUser->hasPermissionTo('view dashboard') ? 'YES' : 'NO') . "\n";
        echo "   Can access dashboard: " . ($testUser->can('view dashboard') ? 'YES' : 'NO') . "\n";
    }

    echo "\n🎉 Root cause fix completed!\n";
    echo "============================\n";
    echo "✨ All users now have 'view dashboard' permission.\n";
    echo "🌐 Test at: https://talentlit.com/nine/dashboard\n";
    echo "   The 403 error should now be resolved!\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}
