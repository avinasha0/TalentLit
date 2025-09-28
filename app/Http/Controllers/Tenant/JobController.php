<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\JobOpening;
use Illuminate\Http\Request;

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

    public function show(string $tenant, JobOpening $job)
    {
        $job->load(['department', 'location', 'jobStages', 'applications.candidate']);

        return view('tenant.jobs.show', compact('job'));
    }
}
