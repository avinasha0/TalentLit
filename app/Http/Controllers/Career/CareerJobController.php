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
        $branding = $tenant->branding;
        
        $query = JobOpening::with(['department', 'location'])
            ->where('status', 'published')
            ->orderBy('published_at', 'desc');

        // Apply filters
        if ($request->filled('location')) {
            $query->whereHas('location', function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->location.'%');
            });
        }

        if ($request->filled('department')) {
            $query->whereHas('department', function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->department.'%');
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

        // Get filter options
        $departments = JobOpening::where('status', 'published')
            ->with('department')
            ->get()
            ->pluck('department.name')
            ->unique()
            ->sort()
            ->values();

        $locations = JobOpening::where('status', 'published')
            ->with('location')
            ->get()
            ->pluck('location.name')
            ->unique()
            ->sort()
            ->values();

        $employmentTypes = JobOpening::where('status', 'published')
            ->pluck('employment_type')
            ->unique()
            ->sort()
            ->values();

        return view('careers.index', compact('jobs', 'departments', 'locations', 'employmentTypes', 'tenant', 'branding'));
    }

    public function show(string $tenant, JobOpening $job)
    {
        $tenantModel = tenant();
        
        // Ensure job belongs to the current tenant
        if ($job->tenant_id !== $tenantModel->id) {
            abort(404);
        }

        // Ensure job is published
        if ($job->status !== 'published') {
            abort(404);
        }

        $branding = $tenantModel->branding;
        $job->load(['department', 'location', 'jobStages', 'applicationQuestions']);

        return view('careers.show', compact('job', 'tenantModel', 'branding'));
    }
}
