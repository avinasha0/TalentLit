<?php

require 'vendor/autoload.php';

$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🧪 Testing Local Environment\n";
echo "============================\n\n";

try {
    // Test 1: Check users
    $users = App\Models\User::all();
    echo "👥 Found {$users->count()} users\n\n";
    
    foreach ($users as $user) {
        echo "User: {$user->name} ({$user->email})\n";
        echo "  Roles: " . $user->roles->count() . "\n";
        echo "  First role: " . ($user->roles->first() ? $user->roles->first()->name : 'No Role') . "\n";
        echo "  Tenants: " . $user->tenants->count() . "\n";
        
        foreach ($user->tenants as $tenant) {
            echo "    - {$tenant->name} ({$tenant->slug})\n";
            $userRoles = $user->roles()->where('roles.tenant_id', $tenant->id)->get();
            echo "      Tenant roles: " . $userRoles->count() . "\n";
            foreach ($userRoles as $role) {
                echo "        - {$role->name}\n";
            }
        }
        echo "\n";
    }
    
    // Test 2: Check tenants
    $tenants = App\Models\Tenant::all();
    echo "🏢 Found {$tenants->count()} tenants\n\n";
    
    foreach ($tenants as $tenant) {
        echo "Tenant: {$tenant->name} ({$tenant->slug})\n";
        $tenantRoles = App\Models\TenantRole::where('tenant_id', $tenant->id)->get();
        echo "  Roles: " . $tenantRoles->count() . "\n";
        foreach ($tenantRoles as $role) {
            echo "    - {$role->name}\n";
        }
        echo "\n";
    }
    
    echo "✅ Local test completed!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
