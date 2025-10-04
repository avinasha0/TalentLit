<?php

require 'vendor/autoload.php';

$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔍 Debugging Permission System\n";
echo "==============================\n\n";

try {
    // Test 1: Check if user is authenticated
    echo "🔐 Step 1: Authentication Check\n";
    if (auth()->check()) {
        $user = auth()->user();
        echo "   ✅ User is authenticated: {$user->name}\n";
    } else {
        echo "   ❌ User is NOT authenticated\n";
        echo "   This is likely the root cause of the 403 error!\n";
        exit;
    }

    // Test 2: Check user permissions
    echo "\n📋 Step 2: Permission Check\n";
    $hasDashboardPermission = $user->hasPermissionTo('view dashboard');
    echo "   Has 'view dashboard' permission: " . ($hasDashboardPermission ? 'YES' : 'NO') . "\n";
    
    if (!$hasDashboardPermission) {
        echo "   ❌ This is the cause of the 403 error!\n";
        
        // Check what permissions the user has
        $userPermissions = $user->permissions->pluck('name')->toArray();
        echo "   User permissions: " . (empty($userPermissions) ? 'NONE' : implode(', ', $userPermissions)) . "\n";
        
        // Check user roles
        $userRoles = $user->roles->pluck('name')->toArray();
        echo "   User roles: " . (empty($userRoles) ? 'NONE' : implode(', ', $userRoles)) . "\n";
        
        // Check if permissions exist in database
        $permissionExists = \Spatie\Permission\Models\Permission::where('name', 'view dashboard')->exists();
        echo "   'view dashboard' permission exists in DB: " . ($permissionExists ? 'YES' : 'NO') . "\n";
    }

    // Test 3: Check tenant context
    echo "\n🏢 Step 3: Tenant Context Check\n";
    $currentTenant = app('currentTenantId');
    echo "   Current tenant ID: " . ($currentTenant ? $currentTenant : 'NOT SET') . "\n";
    
    // Test 4: Check if user belongs to tenant
    echo "\n👥 Step 4: User-Tenant Relationship\n";
    $userTenants = $user->tenants;
    echo "   User belongs to {$userTenants->count()} tenants:\n";
    foreach ($userTenants as $tenant) {
        echo "     - {$tenant->name} ({$tenant->slug})\n";
        
        // Check user roles for this specific tenant
        $tenantRoles = $user->roles()->where('roles.tenant_id', $tenant->id)->get();
        echo "       Roles: " . $tenantRoles->count() . "\n";
        foreach ($tenantRoles as $role) {
            echo "         - {$role->name}\n";
            $rolePermissions = $role->permissions->pluck('name')->toArray();
            echo "           Permissions: " . (empty($rolePermissions) ? 'NONE' : implode(', ', $rolePermissions)) . "\n";
        }
    }

    // Test 5: Check Spatie Permission configuration
    echo "\n⚙️ Step 5: Spatie Permission Configuration\n";
    $guardName = config('permission.defaults.guard');
    echo "   Default guard: {$guardName}\n";
    
    $tablePrefix = config('permission.table_names.roles');
    echo "   Roles table: {$tablePrefix}\n";
    
    $permissionTable = config('permission.table_names.permissions');
    echo "   Permissions table: {$permissionTable}\n";

    echo "\n🎯 Root Cause Analysis\n";
    echo "=====================\n";
    
    if (!auth()->check()) {
        echo "❌ ROOT CAUSE: User is not authenticated\n";
        echo "   Solution: User needs to log in first\n";
    } elseif (!$hasDashboardPermission) {
        echo "❌ ROOT CAUSE: User doesn't have 'view dashboard' permission\n";
        echo "   Solution: Assign 'view dashboard' permission to user\n";
    } else {
        echo "✅ User should have access to dashboard\n";
    }

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
