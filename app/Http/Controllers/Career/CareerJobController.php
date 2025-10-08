<?php

namespace App\Http\Controllers\Career;

use App\Http\Controllers\Controller;
use App\Models\JobOpening;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CareerJobController extends Controller
{
    public function index(Request $request)
    {
        $tenant = tenant();
        
        // Check if careers page is enabled
        if (!$tenant->careers_enabled) {
            $branding = $tenant->branding;
            return view('careers.disabled', compact('tenant', 'branding'));
        }
        
        $branding = $tenant->branding;
        
        $query = JobOpening::with(['department', 'globalDepartment', 'location', 'globalLocation', 'city'])
            ->where('status', 'published')
            ->orderBy('published_at', 'desc');

        // Apply filters
        if ($request->filled('location')) {
            $query->where(function ($q) use ($request) {
                $q->whereHas('globalLocation', function ($subQ) use ($request) {
                    $subQ->where('name', 'like', '%'.$request->location.'%');
                })->orWhereHas('city', function ($subQ) use ($request) {
                    $subQ->where('name', 'like', '%'.$request->location.'%');
                });
            });
        }

        if ($request->filled('department')) {
            $query->where(function ($q) use ($request) {
                $q->whereHas('department', function ($subQ) use ($request) {
                    $subQ->where('name', 'like', '%'.$request->department.'%');
                })->orWhereHas('globalDepartment', function ($subQ) use ($request) {
                    $subQ->where('name', 'like', '%'.$request->department.'%');
                });
            });
        }

        if ($request->filled('employment_type')) {
            $query->where('employment_type', $request->employment_type);
        }

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', '%'.$keyword.'%')
                    ->orWhere('description', 'like', '%'.$keyword.'%');
            });
        }

        $jobs = $query->paginate(12);

        // Get filter options - combine both tenant and global departments/locations
        $tenantDepartments = JobOpening::where('status', 'published')
            ->with('department')
            ->get()
            ->pluck('department.name')
            ->filter()
            ->unique()
            ->values();

        $globalDepartments = JobOpening::where('status', 'published')
            ->with('globalDepartment')
            ->get()
            ->pluck('globalDepartment.name')
            ->filter()
            ->unique()
            ->values();

        $departments = $tenantDepartments->merge($globalDepartments)->unique()->sort()->values();

        $globalLocations = JobOpening::where('status', 'published')
            ->with('globalLocation')
            ->get()
            ->pluck('globalLocation.name')
            ->filter()
            ->unique()
            ->values();

        $cities = JobOpening::where('status', 'published')
            ->with('city')
            ->get()
            ->pluck('city.formatted_location')
            ->filter()
            ->unique()
            ->values();

        $locations = $globalLocations->merge($cities)->unique()->sort()->values();

        $employmentTypes = JobOpening::where('status', 'published')
            ->pluck('employment_type')
            ->unique()
            ->sort()
            ->values();

        return view('careers.index', compact('jobs', 'departments', 'locations', 'employmentTypes', 'tenant', 'branding'));
    }

    public function show(string $tenant, string $job)
    {
        $tenantModel = tenant();
        
        // Check if careers page is enabled
        if (!$tenantModel->careers_enabled) {
            $branding = $tenantModel->branding;
            return view('careers.disabled', ['tenant' => $tenantModel, 'branding' => $branding]);
        }
        
        // Find job by slug instead of relying on route model binding
        $jobModel = JobOpening::where('slug', $job)
            ->where('tenant_id', $tenantModel->id)
            ->where('status', 'published')
            ->first();

        if (!$jobModel) {
            abort(404);
        }

        $branding = $tenantModel->branding;
        $jobModel->load(['department', 'globalDepartment', 'location', 'globalLocation', 'city', 'jobStages', 'applicationQuestions']);

        return view('careers.show', ['job' => $jobModel, 'tenantModel' => $tenantModel, 'branding' => $branding]);
    }
}
