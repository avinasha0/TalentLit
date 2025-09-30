<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\JobOpening;
use App\Models\Department;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Gate;

class JobController extends Controller
{
    public function index(Request $request, string $tenant)
    {
        $query = JobOpening::with(['department', 'location', 'jobStages'])
            ->orderBy('created_at', 'desc');

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('department')) {
            $query->whereHas('department', function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->department.'%');
            });
        }

        if ($request->filled('location')) {
            $query->whereHas('location', function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->location.'%');
            });
        }

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', '%'.$keyword.'%')
                    ->orWhere('description', 'like', '%'.$keyword.'%');
            });
        }

        $jobs = $query->paginate(20);

        // Get filter options
        $departments = JobOpening::with('department')
            ->get()
            ->pluck('department.name')
            ->unique()
            ->sort()
            ->values();

        $locations = JobOpening::with('location')
            ->get()
            ->pluck('location.name')
            ->unique()
            ->sort()
            ->values();

        $statuses = ['draft', 'published', 'closed'];

        if ($request->expectsJson()) {
            return response()->json([
                'jobs' => $jobs->map(function ($job) {
                    return [
                        'id' => $job->id,
                        'title' => $job->title,
                        'slug' => $job->slug,
                        'department' => $job->department->name,
                        'location' => $job->location->name,
                        'employment_type' => $job->employment_type,
                        'status' => $job->status,
                        'openings_count' => $job->openings_count,
                        'published_at' => $job->published_at,
                        'created_at' => $job->created_at,
                    ];
                }),
                'pagination' => [
                    'current_page' => $jobs->currentPage(),
                    'last_page' => $jobs->lastPage(),
                    'per_page' => $jobs->perPage(),
                    'total' => $jobs->total(),
                ],
            ]);
        }

        return view('tenant.jobs.index', compact('jobs', 'departments', 'locations', 'statuses'));
    }

    public function create(string $tenant)
    {
        Gate::authorize('create', JobOpening::class);
        
        $departments = Department::orderBy('name')->get();
        $locations = Location::orderBy('name')->get();
        $employmentTypes = ['full_time', 'part_time', 'contract', 'internship'];

        return view('tenant.jobs.create', compact('departments', 'locations', 'employmentTypes'));
    }

    public function store(Request $request, string $tenant)
    {
        Gate::authorize('create', JobOpening::class);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:job_openings,slug,NULL,id,tenant_id,' . tenant_id(),
            'department_id' => 'required|exists:departments,id',
            'location_id' => 'required|exists:locations,id',
            'employment_type' => 'required|in:full_time,part_time,contract,internship',
            'status' => 'required|in:draft,published,closed',
            'openings_count' => 'required|integer|min:1',
            'description' => 'required|string',
        ]);

        // Generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
            
            // Ensure uniqueness within tenant
            $originalSlug = $validated['slug'];
            $counter = 1;
            while (JobOpening::where('slug', $validated['slug'])
                ->where('tenant_id', tenant_id())
                ->exists()) {
                $validated['slug'] = $originalSlug . '-' . $counter;
                $counter++;
            }
        }

        $validated['tenant_id'] = tenant_id();
        
        if ($validated['status'] === 'published') {
            $validated['published_at'] = now();
        }

        $job = JobOpening::create($validated);

        return redirect()
            ->route('tenant.jobs.show', ['tenant' => $tenant, 'job' => $job])
            ->with('success', 'Job created successfully.');
    }

    public function show(string $tenant, JobOpening $job)
    {
        $job->load(['department', 'location', 'jobStages', 'applications.candidate']);

        return view('tenant.jobs.show', compact('job'));
    }

    public function edit(string $tenant, JobOpening $job)
    {
        Gate::authorize('update', $job);
        
        $departments = Department::orderBy('name')->get();
        $locations = Location::orderBy('name')->get();
        $employmentTypes = ['full_time', 'part_time', 'contract', 'internship'];

        return view('tenant.jobs.edit', compact('job', 'departments', 'locations', 'employmentTypes'));
    }

    public function update(Request $request, string $tenant, JobOpening $job)
    {
        Gate::authorize('update', $job);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('job_openings', 'slug')
                    ->ignore($job->id)
                    ->where('tenant_id', tenant_id())
            ],
            'department_id' => 'required|exists:departments,id',
            'location_id' => 'required|exists:locations,id',
            'employment_type' => 'required|in:full_time,part_time,contract,internship',
            'status' => 'required|in:draft,published,closed',
            'openings_count' => 'required|integer|min:1',
            'description' => 'required|string',
        ]);

        // Generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
            
            // Ensure uniqueness within tenant
            $originalSlug = $validated['slug'];
            $counter = 1;
            while (JobOpening::where('slug', $validated['slug'])
                ->where('tenant_id', tenant_id())
                ->where('id', '!=', $job->id)
                ->exists()) {
                $validated['slug'] = $originalSlug . '-' . $counter;
                $counter++;
            }
        }

        // Set published_at if status changed to published
        if ($validated['status'] === 'published' && $job->status !== 'published') {
            $validated['published_at'] = now();
        }

        $job->update($validated);

        return redirect()
            ->route('tenant.jobs.show', ['tenant' => $tenant, 'job' => $job])
            ->with('success', 'Job updated successfully.');
    }

    public function publish(string $tenant, JobOpening $job)
    {
        Gate::authorize('publish', $job);
        
        $job->update([
            'status' => 'published',
            'published_at' => now(),
        ]);

        return redirect()
            ->back()
            ->with('success', 'Job published successfully.');
    }

    public function close(string $tenant, JobOpening $job)
    {
        Gate::authorize('close', $job);
        
        $job->update([
            'status' => 'closed',
        ]);

        return redirect()
            ->back()
            ->with('success', 'Job closed successfully.');
    }

    public function destroy(string $tenant, JobOpening $job)
    {
        Gate::authorize('delete', $job);
        
        $job->delete();

        return redirect()
            ->route('tenant.jobs.index', ['tenant' => $tenant])
            ->with('success', 'Job deleted successfully.');
    }
}
