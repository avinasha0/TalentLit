<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Location;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrganizationController extends Controller
{
    public function index(Request $request, string $tenant = null)
    {
        // Get the tenant model from the middleware
        $tenantModel = tenant();
        
        // If tenant is null, it means we're using subdomain routing
        // The tenant is already set by the middleware
        if (!$tenantModel) {
            abort(500, 'Tenant not resolved. Please check your subdomain configuration.');
        }
        
        // Get all departments for this tenant
        $departments = Department::where('tenant_id', $tenantModel->id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
        
        // Get all locations for this tenant
        $locations = Location::where('tenant_id', $tenantModel->id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
        
        // Get all users/team members for this tenant
        $users = User::whereHas('tenants', function ($query) use ($tenantModel) {
            $query->where('tenant_id', $tenantModel->id);
        })->get();
        
        // Check if created_by column exists (once for both tables)
        $jobOpeningsColumns = collect(DB::select('SHOW COLUMNS FROM job_openings'))->pluck('Field');
        $requisitionsColumns = collect(DB::select('SHOW COLUMNS FROM job_requisitions'))->pluck('Field');
        $jobOpeningsHasCreatedBy = $jobOpeningsColumns->contains('created_by');
        $requisitionsHasCreatedBy = $requisitionsColumns->contains('created_by');
        
        // Load tenant-specific roles for each user
        $users->each(function ($user) use ($tenantModel, $jobOpeningsHasCreatedBy, $requisitionsHasCreatedBy) {
            $user->tenant_role = DB::table('custom_user_roles')
                ->where('user_id', $user->id)
                ->where('tenant_id', $tenantModel->id)
                ->value('role_name');
            
            // Count job openings (if created_by field exists)
            if ($jobOpeningsHasCreatedBy) {
                $user->job_openings_count = DB::table('job_openings')
                    ->where('tenant_id', $tenantModel->id)
                    ->where('created_by', $user->id)
                    ->count();
            } else {
                $user->job_openings_count = 0;
            }
            
            // Count requisitions (if created_by field exists)
            if ($requisitionsHasCreatedBy) {
                $user->requisitions_count = DB::table('job_requisitions')
                    ->where('tenant_id', $tenantModel->id)
                    ->where('created_by', $user->id)
                    ->count();
            } else {
                $user->requisitions_count = 0;
            }
        });
        
        // Get statistics
        $stats = [
            'total_departments' => $departments->count(),
            'total_locations' => $locations->count(),
            'total_team_members' => $users->count(),
            'total_job_openings' => DB::table('job_openings')
                ->where('tenant_id', $tenantModel->id)
                ->count(),
            'total_requisitions' => DB::table('job_requisitions')
                ->where('tenant_id', $tenantModel->id)
                ->count(),
        ];
        
        // Group users by role
        $usersByRole = $users->groupBy(function ($user) {
            return $user->tenant_role ?? 'No Role';
        });
        
        // Build hierarchical tree structure
        // Hierarchy: CEO -> Line Manager -> HR Manager -> HR Recruiter
        $roleHierarchy = [
            'CEO' => 1,
            'Line Manager' => 2,
            'HR Manager' => 3,
            'HR Recruiter' => 4,
        ];
        
        // Map old roles to new roles for backward compatibility
        $roleMapping = [
            'Owner' => 'CEO',
            'DepartmentHead' => 'Line Manager',
            'HRManager' => 'HR Manager',
            'Recruiter' => 'HR Recruiter',
        ];
        
        // Normalize user roles to new structure
        $users->each(function ($user) use ($roleMapping) {
            $currentRole = $user->tenant_role ?? '';
            if (isset($roleMapping[$currentRole])) {
                $user->display_role = $roleMapping[$currentRole];
            } else {
                $user->display_role = $currentRole;
            }
        });
        
        // Helper function to build tree with parent info
        $buildNode = function($user, $role, $level, $parent = null) use (&$buildNode, $users, $roleHierarchy, $roleMapping) {
            $node = [
                'user' => $user,
                'role' => $role,
                'level' => $level,
                'parent' => $parent,
                'parentName' => $parent ? $parent['user']->name : null,
                'parentRole' => $parent ? $parent['role'] : null,
                'children' => []
            ];
            
            // Determine next level role
            $nextRole = null;
            if ($role === 'CEO' || $role === 'Owner') {
                $nextRole = 'Line Manager';
            } elseif ($role === 'Line Manager' || $role === 'DepartmentHead') {
                $nextRole = 'HR Manager';
            } elseif ($role === 'HR Manager' || $role === 'HRManager') {
                $nextRole = 'HR Recruiter';
            }
            
            // Get children for this node - check both new and old role names
            if ($nextRole) {
                $children = $users->filter(function ($u) use ($nextRole, $roleMapping) {
                    $userRole = $u->tenant_role ?? '';
                    $displayRole = $u->display_role ?? $userRole;
                    
                    // Check if user matches next role (either new name or mapped old name)
                    return $displayRole === $nextRole || 
                           (isset($roleMapping[$userRole]) && $roleMapping[$userRole] === $nextRole);
                });
                
                foreach ($children as $child) {
                    $childDisplayRole = $child->display_role ?? ($child->tenant_role ?? '');
                    $node['children'][] = $buildNode($child, $childDisplayRole, $level + 1, $node);
                }
            }
            
            return $node;
        };
        
        // Build tree structure
        $orgTree = [];
        
        // Get CEO/Owner users (top level)
        $ceos = $users->filter(function ($user) {
            $role = $user->tenant_role ?? '';
            $displayRole = $user->display_role ?? $role;
            return $displayRole === 'CEO' || $role === 'Owner';
        });
        
        if ($ceos->count() > 0) {
            foreach ($ceos as $ceo) {
                $ceoDisplayRole = $ceo->display_role ?? ($ceo->tenant_role ?? '');
                $orgTree[] = $buildNode($ceo, $ceoDisplayRole === 'Owner' ? 'CEO' : $ceoDisplayRole, 1, null);
            }
        }
        
        // If no CEO/Owner found, show other roles at top level
        if (empty($orgTree)) {
            // Try to show any available roles in hierarchy order
            foreach ($roleHierarchy as $roleName => $level) {
                $roleUsers = $users->filter(function ($user) use ($roleName, $roleMapping) {
                    $userRole = $user->tenant_role ?? '';
                    $displayRole = $user->display_role ?? $userRole;
                    return $displayRole === $roleName || 
                           (isset($roleMapping[$userRole]) && $roleMapping[$userRole] === $roleName);
                });
                
                if ($roleUsers->count() > 0) {
                    foreach ($roleUsers as $user) {
                        $userDisplayRole = $user->display_role ?? ($user->tenant_role ?? '');
                        $orgTree[] = $buildNode($user, $userDisplayRole, $level, null);
                    }
                    break; // Show only the highest level role found
                }
            }
        }
        
        return view('tenant.organization.index', compact(
            'tenantModel',
            'departments',
            'locations',
            'users',
            'usersByRole',
            'stats',
            'orgTree',
            'tenant'
        ));
    }
}

