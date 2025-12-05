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
        
        return view('tenant.organization.index', compact(
            'tenantModel',
            'departments',
            'locations',
            'users',
            'usersByRole',
            'stats',
            'tenant'
        ));
    }
}

