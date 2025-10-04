<?php

/**
 * Fix User Roles Script
 * 
 * This script specifically fixes user role assignments
 * to ensure they show up correctly in the dashboard.
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
use Spatie\Permission\Models\Permission;

echo "🔧 Fix User Roles Script\n";
echo "========================\n\n";

try {
    // Step 1: Get all users
    echo "👥 Step 1: Checking users and their roles...\n";
    $users = User::all();
    echo "   Found {$users->count()} users\n\n";

    foreach ($users as $user) {
        echo "   User: {$user->name} ({$user->email})\n";
        
        // Check user's tenants
        $userTenants = $user->tenants;
        echo "     Tenants: " . $userTenants->count() . "\n";
        
        foreach ($userTenants as $tenant) {
            echo "       - {$tenant->name} ({$tenant->slug})\n";
            
            // Check user's roles for this tenant
            $userRoles = $user->roles()->where('roles.tenant_id', $tenant->id)->get();
            echo "         Roles: " . $userRoles->count() . "\n";
            
            if ($userRoles->count() === 0) {
                echo "         ❌ No roles found - assigning Owner role...\n";
                
                // Get or create Owner role for this tenant
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
                    
                    // Give Owner role all permissions
                    $ownerRole->syncPermissions(Permission::all());
                    echo "         ✅ Created Owner role for tenant\n";
                }
                
                // Assign role to user
                $user->assignRole($ownerRole);
                echo "         ✅ Assigned Owner role to user\n";
            } else {
                foreach ($userRoles as $role) {
                    echo "         ✅ Role: {$role->name}\n";
                }
            }
        }
        
        // Test the role display logic
        $firstRole = $user->roles->first();
        if ($firstRole) {
            echo "     Dashboard will show: {$firstRole->name}\n";
        } else {
            echo "     Dashboard will show: No Role\n";
        }
        
        echo "\n";
    }

    // Step 2: Test specific user for testtest tenant
    echo "🎯 Step 2: Testing testtest tenant specifically...\n";
    $testtestTenant = Tenant::where('slug', 'testtest')->first();
    
    if ($testtestTenant) {
        echo "   Found testtest tenant: {$testtestTenant->name}\n";
        
        $testtestUsers = User::whereHas('tenants', function ($query) use ($testtestTenant) {
            $query->where('tenants.id', $testtestTenant->id);
        })->get();
        
        echo "   Users in testtest tenant: " . $testtestUsers->count() . "\n";
        
        foreach ($testtestUsers as $user) {
            echo "     User: {$user->name}\n";
            
            $userRoles = $user->roles()->where('roles.tenant_id', $testtestTenant->id)->get();
            echo "     Roles: " . $userRoles->count() . "\n";
            
            if ($userRoles->count() === 0) {
                echo "     ❌ No roles - fixing...\n";
                
                $ownerRole = TenantRole::where('name', 'Owner')
                    ->where('guard_name', 'web')
                    ->where('tenant_id', $testtestTenant->id)
                    ->first();
                    
                if (!$ownerRole) {
                    $ownerRole = TenantRole::create([
                        'name' => 'Owner',
                        'guard_name' => 'web',
                        'tenant_id' => $testtestTenant->id,
                    ]);
                    $ownerRole->syncPermissions(Permission::all());
                }
                
                $user->assignRole($ownerRole);
                echo "     ✅ Fixed!\n";
            }
            
            // Test role display
            $firstRole = $user->roles->first();
            echo "     Dashboard will show: " . ($firstRole ? $firstRole->name : 'No Role') . "\n";
        }
    } else {
        echo "   ❌ testtest tenant not found\n";
    }

    echo "\n🎉 User roles fix completed!\n";
    echo "============================\n";
    echo "✨ Users should now show proper roles in the dashboard.\n";
    echo "🌐 Test at: https://talentlit.com/testtest/dashboard\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}
