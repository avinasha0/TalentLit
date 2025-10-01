<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Tenant;
use App\Models\TenantRole;
use Spatie\Permission\Models\Permission;

echo "Fixing tenant-specific roles...\n";

// Get the admin user and tenant
$user = User::where('email', 'admin@acme.com')->first();
$tenant = Tenant::where('slug', 'acme')->first();

if (!$user || !$tenant) {
    echo "ERROR: User or tenant not found!\n";
    exit(1);
}

echo "User: " . $user->name . "\n";
echo "Tenant: " . $tenant->name . " (ID: " . $tenant->id . ")\n";

// Remove all existing roles from user
echo "Removing existing roles...\n";
$user->syncRoles([]);

// Create tenant-specific Owner role
echo "Creating tenant-specific Owner role...\n";
$ownerRole = TenantRole::create([
    'name' => 'Owner',
    'guard_name' => 'web',
    'tenant_id' => $tenant->id,
]);

// Get all permissions and assign to Owner role
$allPermissions = Permission::all();
$ownerRole->syncPermissions($allPermissions);
echo "Created Owner role with " . $allPermissions->count() . " permissions.\n";

// Assign the tenant-specific Owner role to the user
echo "Assigning tenant Owner role to user...\n";
$user->assignRole($ownerRole);

// Ensure user is associated with tenant
if (!$user->belongsToTenant($tenant->id)) {
    echo "Adding user to tenant...\n";
    $user->tenants()->attach($tenant->id);
}

// Final verification
$user->refresh();
echo "\nFinal verification:\n";
echo "User: " . $user->name . "\n";
echo "Tenant: " . $tenant->name . "\n";
echo "Roles: " . $user->roles->pluck('name')->join(', ') . "\n";
echo "Total permissions: " . $user->getAllPermissions()->count() . "\n";

// Test specific permissions
$testPermissions = ['view dashboard', 'create jobs', 'view candidates'];
foreach ($testPermissions as $permission) {
    $hasPermission = $user->can($permission);
    echo "Can $permission: " . ($hasPermission ? 'YES' : 'NO') . "\n";
}

echo "Done!\n";
