<?php

/**
 * Simple Final Production Fix
 * 
 * This script just assigns existing roles to users
 * without trying to create new roles.
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

echo "🚀 Simple Final Production Fix\n";
echo "==============================\n\n";

try {
    // Step 1: Clear caches
    echo "🧹 Step 1: Clearing caches...\n";
    $commands = ['config:clear', 'cache:clear', 'route:clear', 'view:clear'];
    
    foreach ($commands as $command) {
        try {
            \Illuminate\Support\Facades\Artisan::call($command);
            echo "   ✅ {$command}\n";
        } catch (Exception $e) {
            echo "   ⚠️  {$command} failed\n";
        }
    }

    // Step 2: Fix user roles by assigning existing roles
    echo "\n👥 Step 2: Fixing user roles...\n";
    $users = User::all();
    echo "   Found {$users->count()} users\n\n";
    
    foreach ($users as $user) {
        echo "   User: {$user->name}\n";
        
        // Check if user has any roles
        $userRoles = $user->roles()->count();
        echo "     Current roles: {$userRoles}\n";
        
        if ($userRoles === 0) {
            // User has no roles, find their first tenant and assign Owner role
            $firstTenant = $user->tenants()->first();
            
            if ($firstTenant) {
                echo "     Tenant: {$firstTenant->name} ({$firstTenant->slug})\n";
                
                // Find existing Owner role for this tenant
                $ownerRole = TenantRole::where('name', 'Owner')
                    ->where('guard_name', 'web')
                    ->where('tenant_id', $firstTenant->id)
                    ->first();
                
                if ($ownerRole) {
                    // Assign existing role
                    $user->assignRole($ownerRole);
                    echo "     ✅ Assigned existing Owner role\n";
                } else {
                    echo "     ❌ No Owner role found for tenant\n";
                }
            } else {
                echo "     ❌ No tenants found for user\n";
            }
        } else {
            echo "     ✅ User already has roles\n";
        }
        
        // Test what dashboard will show
        $firstRole = $user->roles->first();
        echo "     Dashboard will show: " . ($firstRole ? $firstRole->name : 'No Role') . "\n\n";
    }

    // Step 3: Test specific testtest tenant
    echo "🎯 Step 3: Testing testtest tenant...\n";
    $testtestTenant = Tenant::where('slug', 'testtest')->first();
    
    if ($testtestTenant) {
        echo "   Found testtest tenant: {$testtestTenant->name}\n";
        
        $testtestUsers = User::whereHas('tenants', function ($query) use ($testtestTenant) {
            $query->where('tenants.id', $testtestTenant->id);
        })->get();
        
        echo "   Users in testtest: " . $testtestUsers->count() . "\n";
        
        foreach ($testtestUsers as $user) {
            echo "     User: {$user->name}\n";
            
            $userRoles = $user->roles()->where('roles.tenant_id', $testtestTenant->id)->get();
            echo "     Roles: " . $userRoles->count() . "\n";
            
            if ($userRoles->count() === 0) {
                $ownerRole = TenantRole::where('name', 'Owner')
                    ->where('guard_name', 'web')
                    ->where('tenant_id', $testtestTenant->id)
                    ->first();
                
                if ($ownerRole) {
                    $user->assignRole($ownerRole);
                    echo "     ✅ Assigned Owner role\n";
                } else {
                    echo "     ❌ No Owner role found\n";
                }
            }
            
            $firstRole = $user->roles->first();
            echo "     Dashboard will show: " . ($firstRole ? $firstRole->name : 'No Role') . "\n";
        }
    } else {
        echo "   ❌ testtest tenant not found\n";
    }

    echo "\n🎉 Simple fix completed!\n";
    echo "========================\n";
    echo "✨ This should fix the 'No Role' issue in the dashboard.\n";
    echo "🌐 Test at: https://talentlit.com/testtest/dashboard\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}
