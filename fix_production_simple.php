<?php

/**
 * Simple Production Fix Script
 * 
 * This script uses Laravel's built-in seeders to fix production issues
 * without complex database operations that can cause conflicts.
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

echo "🚀 Simple Production Fix Script\n";
echo "==============================\n\n";

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

    // Step 2: Run the role permission seeder
    echo "\n📋 Step 2: Running role permission seeder...\n";
    try {
        \Illuminate\Support\Facades\Artisan::call('db:seed', ['--class' => 'RolePermissionSeeder']);
        echo "   ✅ Role permission seeder completed\n";
    } catch (Exception $e) {
        echo "   ⚠️  Warning: Role permission seeder failed - " . $e->getMessage() . "\n";
    }

    // Step 3: Run the assign free plans seeder
    echo "\n🏢 Step 3: Assigning free plans to tenants...\n";
    try {
        \Illuminate\Support\Facades\Artisan::call('db:seed', ['--class' => 'AssignFreePlansSeeder']);
        echo "   ✅ Free plans assigned successfully\n";
    } catch (Exception $e) {
        echo "   ⚠️  Warning: Assign free plans seeder failed - " . $e->getMessage() . "\n";
    }

    // Step 4: Optimize for production
    echo "\n⚡ Step 4: Optimizing for production...\n";
    $optimizeCommands = ['config:cache', 'route:cache', 'view:cache'];
    
    foreach ($optimizeCommands as $command) {
        try {
            \Illuminate\Support\Facades\Artisan::call($command);
            echo "   ✅ {$command} completed\n";
        } catch (Exception $e) {
            echo "   ⚠️  Warning: {$command} failed\n";
        }
    }

    echo "\n🎉 Simple production fix completed!\n";
    echo "==================================\n";
    echo "✨ Your production environment should now be working.\n";
    echo "🌐 Test at: https://talentlit.com/testtest/dashboard\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
