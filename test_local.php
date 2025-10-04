<?php

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
use App\Models\Tenant;

echo "Testing Localhost Permissions\n";
echo "============================\n\n";

try {
    $user = User::first();
    if (!$user) {
        echo "❌ No users found\n";
        exit(1);
    }
    
    echo "User: {$user->name} ({$user->email})\n";
    echo "Has 'view dashboard' permission: " . ($user->hasPermissionTo('view dashboard') ? 'YES' : 'NO') . "\n";
    echo "Has 'Owner' role: " . ($user->hasRole('Owner') ? 'YES' : 'NO') . "\n";
    
    echo "\nRoles:\n";
    foreach ($user->roles as $role) {
        echo "  - {$role->name} (tenant: {$role->tenant_id})\n";
    }
    
    echo "\nPermissions:\n";
    foreach ($user->getAllPermissions() as $permission) {
        echo "  - {$permission->name}\n";
    }
    
    echo "\nTenants:\n";
    foreach ($user->tenants as $tenant) {
        echo "  - {$tenant->name} ({$tenant->slug})\n";
    }
    
    // Test with tenant context
    $firstTenant = $user->tenants()->first();
    if ($firstTenant) {
        echo "\nTesting with tenant context: {$firstTenant->name}\n";
        app()->instance('currentTenantId', $firstTenant->id);
        
        echo "Has 'view dashboard' with tenant context: " . ($user->hasPermissionTo('view dashboard') ? 'YES' : 'NO') . "\n";
        echo "Has 'Owner' role with tenant context: " . ($user->hasRole('Owner') ? 'YES' : 'NO') . "\n";
    }

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}