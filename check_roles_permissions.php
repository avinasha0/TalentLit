<?php

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\TenantRole;

echo "Checking tenant roles and permissions...\n";

// Get all tenant roles
$roles = TenantRole::all();
echo "Total roles found: " . $roles->count() . "\n";

foreach ($roles as $role) {
    echo "Role: " . $role->name . " (ID: " . $role->id . ")\n";
    echo "  Permissions: " . json_encode($role->permissions) . "\n";
    echo "  hasPermissionTo(view_jobs): " . ($role->hasPermissionTo('view_jobs') ? 'true' : 'false') . "\n";
    echo "---\n";
}

// Check specific tenant roles
$tenantId = '0199b2b5-5d98-716d-b442-b8de91740516';
$tenantRoles = TenantRole::forTenant($tenantId)->get();
echo "\nRoles for tenant $tenantId:\n";
foreach ($tenantRoles as $role) {
    echo "Role: " . $role->name . "\n";
    echo "  Permissions: " . json_encode($role->permissions) . "\n";
    echo "  hasPermissionTo(view_jobs): " . ($role->hasPermissionTo('view_jobs') ? 'true' : 'false') . "\n";
    echo "---\n";
}
