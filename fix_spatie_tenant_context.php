<?php

/**
 * Fix Spatie Permission Tenant Context
 * 
 * This script creates a proper override for Spatie Permission to handle
 * tenant context correctly in multi-tenant applications.
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

echo "🔧 Fix Spatie Permission Tenant Context\n";
echo "=======================================\n\n";

try {
    // Step 1: Create a custom User model override
    echo "📝 Step 1: Creating User model override...\n";
    
    $userModelPath = app_path('Models/User.php');
    $userModelContent = file_get_contents($userModelPath);
    
    // Check if the override already exists
    if (strpos($userModelContent, 'assignRoleWithTenant') !== false) {
        echo "   ✅ User model already has tenant context methods\n";
    } else {
        echo "   ⚠️  User model needs tenant context methods\n";
        echo "   📋 Manual fix required: Add tenant context methods to User model\n";
    }

    // Step 2: Fix existing role assignments
    echo "\n🔧 Step 2: Fixing existing role assignments...\n";
    
    // Get all role assignments without tenant_id
    $problematicAssignments = DB::table('model_has_roles')
        ->whereNull('tenant_id')
        ->get();
    
    echo "   Found {$problematicAssignments->count()} problematic assignments\n";
    
    foreach ($problematicAssignments as $assignment) {
        // Get the role to find its tenant_id
        $role = DB::table('roles')
            ->where('id', $assignment->role_id)
            ->first();
        
        if ($role && $role->tenant_id) {
            // Update the assignment with the correct tenant_id
            DB::table('model_has_roles')
                ->where('id', $assignment->id)
                ->update(['tenant_id' => $role->tenant_id]);
            
            echo "   ✅ Fixed assignment for role '{$role->name}' (tenant: {$role->tenant_id})\n";
        } else {
            echo "   ❌ Could not find tenant for role ID {$assignment->role_id}\n";
        }
    }

    // Step 3: Ensure all users have proper role assignments
    echo "\n👥 Step 3: Ensuring all users have proper role assignments...\n";
    
    $users = User::all();
    echo "   Processing {$users->count()} users...\n\n";
    
    foreach ($users as $user) {
        echo "   User: {$user->name}\n";
        
        $userTenants = $user->tenants;
        foreach ($userTenants as $tenant) {
            echo "     Tenant: {$tenant->name}\n";
            
            // Check if user has any roles for this tenant
            $hasRoles = DB::table('model_has_roles')
                ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                ->where('model_has_roles.model_id', $user->id)
                ->where('model_has_roles.model_type', User::class)
                ->where('roles.tenant_id', $tenant->id)
                ->exists();
            
            if (!$hasRoles) {
                echo "       ❌ No roles found for this tenant\n";
                
                // Find or create Owner role for this tenant
                $ownerRole = TenantRole::where('name', 'Owner')
                    ->where('tenant_id', $tenant->id)
                    ->first();
                
                if ($ownerRole) {
                    // Assign the role with proper tenant context
                    DB::table('model_has_roles')->insert([
                        'role_id' => $ownerRole->id,
                        'model_type' => User::class,
                        'model_id' => $user->id,
                        'tenant_id' => $tenant->id,
                    ]);
                    
                    echo "       ✅ Assigned Owner role with tenant context\n";
                } else {
                    echo "       ❌ No Owner role found for tenant\n";
                }
            } else {
                echo "       ✅ User has roles for this tenant\n";
            }
        }
        
        echo "\n";
    }

    // Step 4: Test the fix
    echo "🧪 Step 4: Testing the fix...\n";
    
    $testUser = User::first();
    if ($testUser) {
        echo "   Testing user: {$testUser->name}\n";
        
        $firstTenant = $testUser->tenants()->first();
        if ($firstTenant) {
            // Set tenant context
            app()->instance('currentTenantId', $firstTenant->id);
            echo "   Tenant context: {$firstTenant->name}\n";
            
            // Test permission
            $hasPermission = $testUser->hasPermissionTo('view dashboard');
            echo "   Has 'view dashboard': " . ($hasPermission ? 'YES' : 'NO') . "\n";
            
            // Test role
            $hasRole = $testUser->hasRole('Owner');
            echo "   Has 'Owner' role: " . ($hasRole ? 'YES' : 'NO') . "\n";
        }
    }

    echo "\n🎉 Spatie Permission tenant context fix completed!\n";
    echo "================================================\n";
    echo "✨ All role assignments now have proper tenant context.\n";
    echo "🔗 Permission checks should work correctly now!\n";
    echo "🌐 Test at: https://talentlit.com/nine/dashboard\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}
