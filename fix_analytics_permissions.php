<?php

/**
 * Fix Analytics Permissions Script
 * 
 * This script ensures that the 'view analytics' permission exists
 * and is assigned to the Owner role for all tenants.
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

echo "🔧 Fix Analytics Permissions Script\n";
echo "===================================\n\n";

try {
    // Step 1: Create the 'view analytics' permission if it doesn't exist
    echo "📋 Step 1: Creating 'view analytics' permission...\n";
    $analyticsPermission = Permission::firstOrCreate([
        'name' => 'view analytics',
        'guard_name' => 'web'
    ]);
    echo "   ✅ Permission 'view analytics' exists\n\n";

    // Step 2: Get all tenants
    echo "🏢 Step 2: Processing tenants...\n";
    $tenants = Tenant::all();
    echo "   Found {$tenants->count()} tenants\n\n";

    foreach ($tenants as $tenant) {
        echo "   Processing tenant: {$tenant->name} ({$tenant->slug})\n";
        
        // Step 3: Get or create Owner role for this tenant
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
        }
        echo "     ✅ Owner role exists\n";
        
        // Step 4: Add analytics permission to Owner role
        if (!$ownerRole->hasPermissionTo('view analytics')) {
            $ownerRole->givePermissionTo('view analytics');
            echo "     ✅ Added 'view analytics' permission to Owner role\n";
        } else {
            echo "     ✅ Owner role already has 'view analytics' permission\n";
        }
        
        // Step 5: Get or create Admin role for this tenant
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
        }
        echo "     ✅ Admin role exists\n";
        
        // Step 6: Add analytics permission to Admin role
        if (!$adminRole->hasPermissionTo('view analytics')) {
            $adminRole->givePermissionTo('view analytics');
            echo "     ✅ Added 'view analytics' permission to Admin role\n";
        } else {
            echo "     ✅ Admin role already has 'view analytics' permission\n";
        }
        
        // Step 7: Check users with Owner role
        $ownerUsers = User::whereHas('roles', function ($query) use ($ownerRole) {
            $query->where('id', $ownerRole->id);
        })->get();
        
        echo "     👥 Found {$ownerUsers->count()} users with Owner role\n";
        
        foreach ($ownerUsers as $user) {
            if (!$user->hasPermissionTo('view analytics')) {
                $user->givePermissionTo('view analytics');
                echo "       ✅ Added 'view analytics' permission to user: {$user->name}\n";
            } else {
                echo "       ✅ User {$user->name} already has 'view analytics' permission\n";
            }
        }
        
        echo "     ✅ Completed tenant: {$tenant->name}\n\n";
    }

    // Step 8: Summary
    echo "📊 Step 8: Summary\n";
    echo "==================\n";
    echo "✅ Total tenants processed: " . $tenants->count() . "\n";
    echo "✅ Analytics permission exists: " . (Permission::where('name', 'view analytics')->exists() ? 'Yes' : 'No') . "\n";
    
    // Count users with analytics permission
    $usersWithAnalytics = User::whereHas('permissions', function ($query) {
        $query->where('name', 'view analytics');
    })->count();
    
    echo "✅ Users with analytics permission: {$usersWithAnalytics}\n";
    
    // Count roles with analytics permission
    $rolesWithAnalytics = TenantRole::whereHas('permissions', function ($query) {
        $query->where('name', 'view analytics');
    })->count();
    
    echo "✅ Roles with analytics permission: {$rolesWithAnalytics}\n";
    
    echo "\n🎉 Analytics permissions fix completed!\n";
    echo "✨ Users should now be able to access the analytics dashboard.\n";
    echo "🌐 Test at: https://talentlit.com/test1/dashboard\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}
