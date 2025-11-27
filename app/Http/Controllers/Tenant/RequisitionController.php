<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\JobRequisition;
use App\Models\Department;
use App\Models\Location;
use App\Models\GlobalDepartment;
use App\Models\GlobalLocation;
use Illuminate\Http\Request;

class RequisitionController extends Controller
{
    /**
     * Display all requisitions
     */
    public function index(Request $request, string $tenant = null)
    {
        $query = JobRequisition::with(['department', 'location', 'globalDepartment', 'globalLocation'])
            ->orderBy('created_at', 'desc');

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('department')) {
            $query->whereHas('department', function ($subQ) use ($request) {
                $subQ->where('name', 'like', '%'.$request->department.'%');
            })->orWhereHas('globalDepartment', function ($subQ) use ($request) {
                $subQ->where('name', 'like', '%'.$request->department.'%');
            });
        }

        if ($request->filled('location')) {
            $query->whereHas('location', function ($subQ) use ($request) {
                $subQ->where('name', 'like', '%'.$request->location.'%');
            })->orWhereHas('globalLocation', function ($subQ) use ($request) {
                $subQ->where('name', 'like', '%'.$request->location.'%');
            });
        }

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', '%'.$keyword.'%')
                    ->orWhere('description', 'like', '%'.$keyword.'%');
            });
        }

        $requisitions = $query->paginate(20);

        // Get filter options
        $departments = Department::orderBy('name')->pluck('name')->unique()->values();
        $globalDepartments = GlobalDepartment::orderBy('name')->pluck('name')->unique()->values();
        $allDepartments = $departments->merge($globalDepartments)->unique()->sort()->values();

        $locations = Location::orderBy('name')->pluck('name')->unique()->values();
        $globalLocations = GlobalLocation::orderBy('name')->pluck('name')->unique()->values();
        $allLocations = $locations->merge($globalLocations)->unique()->sort()->values();

        $statuses = ['pending', 'approved', 'rejected'];

        return view('tenant.requisitions.index', compact('requisitions', 'allDepartments', 'allLocations', 'statuses'));
    }

    /**
     * Show the form for creating a new requisition
     */
    public function create(string $tenant = null)
    {
        $departments = Department::orderBy('name')->get();
        $locations = Location::orderBy('name')->get();
        $globalDepartments = GlobalDepartment::orderBy('name')->get();
        $globalLocations = GlobalLocation::orderBy('name')->get();

        return view('tenant.requisitions.create', compact('departments', 'locations', 'globalDepartments', 'globalLocations'));
    }

    /**
     * Store a newly created requisition
     */
    public function store(Request $request, string $tenant = null)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'headcount' => 'required|integer|min:1',
            'department_id' => 'nullable|exists:departments,id',
            'location_id' => 'nullable|exists:locations,id',
            'global_department_id' => 'nullable|exists:global_departments,id',
            'global_location_id' => 'nullable|exists:global_locations,id',
            'status' => 'nullable|in:pending,approved,rejected',
        ]);

        $validated['status'] = $validated['status'] ?? 'pending';
        $validated['tenant_id'] = tenant_id();

        JobRequisition::create($validated);

        // Use tenantRoute helper which automatically handles both slug and subdomain routes
        $tenantModel = tenant();
        $tenantSlug = $tenantModel ? $tenantModel->slug : $tenant;
        
        return redirect(tenantRoute('tenant.requisitions.index', $tenantSlug))
            ->with('success', 'Requisition created successfully.');
    }

    /**
     * Display pending requisitions
     */
    public function pending(Request $request, string $tenant = null)
    {
        $query = JobRequisition::with(['department', 'location', 'globalDepartment', 'globalLocation'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc');

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', '%'.$keyword.'%')
                    ->orWhere('description', 'like', '%'.$keyword.'%');
            });
        }

        $requisitions = $query->paginate(20);

        return view('tenant.requisitions.pending', compact('requisitions'));
    }

    /**
     * Display approved requisitions
     */
    public function approved(Request $request, string $tenant = null)
    {
        $query = JobRequisition::with(['department', 'location', 'globalDepartment', 'globalLocation'])
            ->where('status', 'approved')
            ->orderBy('created_at', 'desc');

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', '%'.$keyword.'%')
                    ->orWhere('description', 'like', '%'.$keyword.'%');
            });
        }

        $requisitions = $query->paginate(20);

        return view('tenant.requisitions.approved', compact('requisitions'));
    }

    /**
     * Display rejected requisitions
     */
    public function rejected(Request $request, string $tenant = null)
    {
        $query = JobRequisition::with(['department', 'location', 'globalDepartment', 'globalLocation'])
            ->where('status', 'rejected')
            ->orderBy('created_at', 'desc');

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', '%'.$keyword.'%')
                    ->orWhere('description', 'like', '%'.$keyword.'%');
            });
        }

        $requisitions = $query->paginate(20);

        return view('tenant.requisitions.rejected', compact('requisitions'));
    }
}

