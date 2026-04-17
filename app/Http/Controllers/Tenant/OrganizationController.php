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
        })->get()->unique('id')->values();
        
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
        
        // Group users by original tenant role
        $usersByRole = $users->groupBy(function ($user) {
            return $user->tenant_role ?? 'No Role';
        });

        // Hierarchy: CEO -> Line Manager -> HR Manager -> HR Recruiter
        $roleHierarchy = [
            'CEO' => 1,
            'Line Manager' => 2,
            'HR Manager' => 3,
            'HR Recruiter' => 4,
        ];

        // Map existing role names to hierarchy role names.
        $roleMapping = [
            'Owner' => 'CEO',
            'Admin' => 'Line Manager',
            'DepartmentHead' => 'Line Manager',
            'Hiring Manager' => 'HR Manager',
            'HRManager' => 'HR Manager',
            'Recruiter' => 'HR Recruiter',
            'Interviewer' => 'HR Recruiter',
        ];

        $users->each(function ($user) use ($roleMapping) {
            $user->display_role = $roleMapping[$user->tenant_role ?? ''] ?? ($user->tenant_role ?? '');
        });

        // Helper to create a node payload.
        $makeNode = function ($user, string $role, int $level, ?array $parent = null): array {
            return [
                'user' => $user,
                'role' => $role,
                'level' => $level,
                'parent' => $parent,
                'parentName' => $parent ? $parent['user']->name : null,
                'parentRole' => $parent ? $parent['role'] : null,
                'children' => [],
            ];
        };

        // Build users by normalized hierarchy role.
        $usersByDisplayRole = $users->groupBy(function ($user) {
            return $user->display_role ?? '';
        });

        $ceos = $usersByDisplayRole->get('CEO', collect())->unique('id')->values();
        $lineManagers = $usersByDisplayRole->get('Line Manager', collect())->unique('id')->values();
        $hrManagers = $usersByDisplayRole->get('HR Manager', collect())->unique('id')->values();
        $hrRecruiters = $usersByDisplayRole->get('HR Recruiter', collect())->unique('id')->values();

        // If no CEO exists, use the highest available role as roots and avoid re-attaching that role as children.
        $rootRole = 'CEO';
        $rootUsers = $ceos;
        if ($rootUsers->isEmpty()) {
            if ($lineManagers->isNotEmpty()) {
                $rootRole = 'Line Manager';
                $rootUsers = $lineManagers;
            } elseif ($hrManagers->isNotEmpty()) {
                $rootRole = 'HR Manager';
                $rootUsers = $hrManagers;
            } else {
                $rootRole = 'HR Recruiter';
                $rootUsers = $hrRecruiters;
            }
        }

        $orgTree = [];
        $assignedUserIds = [];
        foreach ($rootUsers as $rootUser) {
            if (in_array($rootUser->id, $assignedUserIds, true)) {
                continue;
            }
            $orgTree[] = $makeNode($rootUser, $rootRole, 1, null);
            $assignedUserIds[] = $rootUser->id;
        }

        // Distribute children so each person appears only once in the tree.
        $attachChildrenRoundRobin = function (array $parentNodes, $childrenUsers, string $childRole, int $level) use ($makeNode, &$assignedUserIds): array {
            if (empty($parentNodes) || $childrenUsers->isEmpty()) {
                return [$parentNodes, []];
            }

            $parentCount = count($parentNodes);
            $index = 0;

            foreach ($childrenUsers as $childUser) {
                if (in_array($childUser->id, $assignedUserIds, true)) {
                    continue;
                }
                $parentIndex = $index % $parentCount;
                $childNode = $makeNode($childUser, $childRole, $level, $parentNodes[$parentIndex]);
                $parentNodes[$parentIndex]['children'][] = $childNode;
                $assignedUserIds[] = $childUser->id;
                $index++;
            }

            $allChildNodes = [];
            foreach ($parentNodes as $parentNode) {
                foreach ($parentNode['children'] as $childNode) {
                    $allChildNodes[] = $childNode;
                }
            }

            return [$parentNodes, $allChildNodes];
        };

        $level2Users = $rootRole === 'Line Manager' ? collect() : $lineManagers;
        $level3Users = $rootRole === 'HR Manager' ? collect() : $hrManagers;
        $level4Users = $rootRole === 'HR Recruiter' ? collect() : $hrRecruiters;

        [$orgTree, $level2Nodes] = $attachChildrenRoundRobin($orgTree, $level2Users, 'Line Manager', 2);
        [$level2Nodes, $level3Nodes] = $attachChildrenRoundRobin($level2Nodes, $level3Users, 'HR Manager', 3);
        [$level3Nodes, $_] = $attachChildrenRoundRobin($level3Nodes, $level4Users, 'HR Recruiter', 4);
        
        return view('tenant.organization.index', compact(
            'tenantModel',
            'departments',
            'locations',
            'users',
            'usersByRole',
            'stats',
            'orgTree'
        ));
    }
}

