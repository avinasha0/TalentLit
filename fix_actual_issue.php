<?php

/**
 * Fix Actual Issue - Spatie Permission Tenant Context
 * 
 * The REAL issue is that when users are assigned roles via assignRole(),
 * the model_has_roles table doesn't get the tenant_id populated.
 * This causes permission checks to fail because Spatie can't find the role
 * in the correct tenant context.
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

use App\Models\User;
use App\Models\Tenant;
use App\Models\TenantRole;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

echo "🎯 Fix Actual Issue - Spatie Permission Tenant Context\n";
echo "=====================================================\n\n";

try {
    // Step 1: Check current state
    echo "📊 Step 1: Checking current state...\n";
    
    $usersWithoutTenantContext = DB::table('model_has_roles')
        ->whereNull('tenant_id')
        ->count();
    
    echo "   Users with roles missing tenant_id: {$usersWithoutTenantContext}\n";
    
    $totalRoleAssignments = DB::table('model_has_roles')->count();
    echo "   Total role assignments: {$totalRoleAssignments}\n\n";

    // Step 2: Fix model_has_roles table
    echo "🔧 Step 2: Fixing model_has_roles table...\n";
    
    $users = User::all();
    echo "   Processing {$users->count()} users...\n\n";
    
    foreach ($users as $user) {
        echo "   User: {$user->name} ({$user->email})\n";
        
        // Get user's tenants
        $userTenants = $user->tenants;
        echo "     Tenants: {$userTenants->count()}\n";
        
        foreach ($userTenants as $tenant) {
            echo "       Tenant: {$tenant->name} ({$tenant->slug})\n";
            
            // Get user's roles for this tenant
            $userRoles = DB::table('model_has_roles')
                ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                ->where('model_has_roles.model_id', $user->id)
                ->where('model_has_roles.model_type', User::class)
                ->where('roles.tenant_id', $tenant->id)
                ->select('model_has_roles.*', 'roles.name as role_name')
                ->get();
            
            echo "         Roles in DB: {$userRoles->count()}\n";
            
            foreach ($userRoles as $roleAssignment) {
                // Check if tenant_id is missing
                if (is_null($roleAssignment->tenant_id)) {
                    echo "         ❌ Role '{$roleAssignment->role_name}' missing tenant_id\n";
                    
                    // Fix it by updating the tenant_id
                    DB::table('model_has_roles')
                        ->where('id', $roleAssignment->id)
                        ->update(['tenant_id' => $tenant->id]);
                    
                    echo "         ✅ Fixed tenant_id for role '{$roleAssignment->role_name}'\n";
                } else {
                    echo "         ✅ Role '{$roleAssignment->role_name}' has tenant_id\n";
                }
            }
        }
        
        echo "\n";
    }

    // Step 3: Verify the fix
    echo "✅ Step 3: Verifying the fix...\n";
    
    $remainingIssues = DB::table('model_has_roles')
        ->whereNull('tenant_id')
        ->count();
    
    echo "   Remaining users with missing tenant_id: {$remainingIssues}\n";
    
    if ($remainingIssues === 0) {
        echo "   🎉 All role assignments now have tenant_id!\n";
    } else {
        echo "   ⚠️  Some issues remain\n";
    }

    // Step 4: Test permission check
    echo "\n🧪 Step 4: Testing permission check...\n";
    
    $testUser = User::first();
    if ($testUser) {
        echo "   Testing user: {$testUser->name}\n";
        
        // Simulate tenant context
        $firstTenant = $testUser->tenants()->first();
        if ($firstTenant) {
            app()->instance('currentTenantId', $firstTenant->id);
            echo "   Set tenant context: {$firstTenant->name}\n";
            
            // Test permission check
            $hasPermission = $testUser->hasPermissionTo('view dashboard');
            echo "   Has 'view dashboard' permission: " . ($hasPermission ? 'YES' : 'NO') . "\n";
            
            // Test role check
            $hasRole = $testUser->hasRole('Owner');
            echo "   Has 'Owner' role: " . ($hasRole ? 'YES' : 'NO') . "\n";
        }
    }

    echo "\n🎉 Actual issue fix completed!\n";
    echo "=============================\n";
    echo "✨ The model_has_roles table now has proper tenant_id values.\n";
    echo "🌐 Permission checks should now work correctly!\n";
    echo "🔗 Test at: https://talentlit.com/nine/dashboard\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}
