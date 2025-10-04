<?php

/**
 * Fix User Permissions Script
 * 
 * This script ensures that users have the proper permissions
 * to access tenant dashboards and other features.
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

echo "🔧 Fix User Permissions Script\n";
echo "==============================\n\n";

try {
    // Step 1: Ensure all required permissions exist
    echo "📋 Step 1: Creating required permissions...\n";
    $requiredPermissions = [
        'view dashboard',
        'view jobs',
        'create jobs',
        'edit jobs',
        'delete jobs',
        'publish jobs',
        'close jobs',
        'manage stages',
        'view stages',
        'create stages',
        'edit stages',
        'delete stages',
        'reorder stages',
        'view candidates',
        'create candidates',
        'edit candidates',
        'delete candidates',
        'move candidates',
        'import candidates',
        'view analytics',
        'view interviews',
        'create interviews',
        'edit interviews',
        'delete interviews',
        'manage users',
        'manage settings',
        'manage email templates',
    ];

    foreach ($requiredPermissions as $permission) {
        Permission::firstOrCreate([
            'name' => $permission,
            'guard_name' => 'web'
        ]);
    }
    echo "   ✅ All required permissions exist\n\n";

    // Step 2: Process all tenants
    echo "🏢 Step 2: Processing tenants...\n";
    $tenants = Tenant::all();
    echo "   Found {$tenants->count()} tenants\n\n";

    foreach ($tenants as $tenant) {
        echo "   Processing tenant: {$tenant->name} ({$tenant->slug})\n";
        
        // Step 3: Get or create roles for this tenant
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
            echo "     ✅ Created Owner role\n";
        } else {
            echo "     ✅ Owner role exists\n";
        }

        $adminRole = TenantRole::where('name', 'Admin')
            ->where('guard_name', 'web')
            ->where('tenant_id', $tenant->id)
            ->first();
            
        if (!$adminRole) {
            $adminRole = TenantRole::create([
                'name' => 'Admin',
                'guard_name' => 'web',
                'tenant_id' => $tenant->id,
            ]);
            echo "     ✅ Created Admin role\n";
        } else {
            echo "     ✅ Admin role exists\n";
        }

        // Step 4: Assign all permissions to Owner role
        $ownerRole->syncPermissions(Permission::all());
        echo "     ✅ Assigned all permissions to Owner role\n";

        // Step 5: Assign limited permissions to Admin role
        $adminPermissions = [
            'view dashboard', 'view jobs', 'create jobs', 'edit jobs', 'delete jobs', 'publish jobs', 'close jobs',
            'manage stages', 'view stages', 'create stages', 'edit stages', 'delete stages', 'reorder stages',
            'view candidates', 'create candidates', 'edit candidates', 'delete candidates', 'move candidates', 'import candidates',
            'view analytics', 'view interviews', 'create interviews', 'edit interviews', 'delete interviews',
            'manage email templates',
        ];
        $adminRole->syncPermissions($adminPermissions);
        echo "     ✅ Assigned limited permissions to Admin role\n";

        // Step 6: Get users for this tenant
        $tenantUsers = User::whereHas('tenants', function ($query) use ($tenant) {
            $query->where('tenants.id', $tenant->id);
        })->get();

        echo "     👥 Found {$tenantUsers->count()} users for this tenant\n";

        foreach ($tenantUsers as $user) {
            // Check if user has any roles for this tenant
            $userRoles = $user->roles()->where('tenant_id', $tenant->id)->get();
            
            if ($userRoles->count() === 0) {
                // User has no roles, assign Owner role
                $user->assignRole($ownerRole);
                echo "       ✅ Assigned Owner role to user: {$user->name}\n";
            } else {
                // User has roles, ensure they have the right permissions
                $hasDashboardPermission = false;
                foreach ($userRoles as $role) {
                    if ($role->hasPermissionTo('view dashboard')) {
                        $hasDashboardPermission = true;
                        break;
                    }
                }
                
                if (!$hasDashboardPermission) {
                    // Add dashboard permission to user's first role
                    $firstRole = $userRoles->first();
                    if (!$firstRole->hasPermissionTo('view dashboard')) {
                        $firstRole->givePermissionTo('view dashboard');
                        echo "       ✅ Added dashboard permission to role: {$firstRole->name} for user: {$user->name}\n";
                    }
                } else {
                    echo "       ✅ User {$user->name} already has dashboard permission\n";
                }
            }
        }
        
        echo "     ✅ Completed tenant: {$tenant->name}\n\n";
    }

    // Step 7: Summary
    echo "📊 Step 7: Summary\n";
    echo "==================\n";
    echo "✅ Total tenants processed: " . $tenants->count() . "\n";
    echo "✅ Total permissions: " . Permission::count() . "\n";
    
    // Count users with dashboard permission
    $usersWithDashboard = User::whereHas('permissions', function ($query) {
        $query->where('name', 'view dashboard');
    })->orWhereHas('roles.permissions', function ($query) {
        $query->where('name', 'view dashboard');
    })->count();
    
    echo "✅ Users with dashboard permission: {$usersWithDashboard}\n";
    
    echo "\n🎉 User permissions fix completed!\n";
    echo "✨ Users should now be able to access tenant dashboards.\n";
    echo "🌐 Test at: https://talentlit.com/testtest/dashboard\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}
