<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\Resume;
use App\Http\Requests\StoreCandidateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CandidateController extends Controller
{
    public function index(Request $request, string $tenant, $job = null)
    {
        $query = Candidate::with(['tags', 'applications.jobOpening']);
        
        // Filter by job if job parameter is provided
        if ($job) {
            $query->whereHas('applications', function ($q) use ($job) {
                $q->where('job_opening_id', $job);
            });
        }

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', '%'.$search.'%')
                    ->orWhere('last_name', 'like', '%'.$search.'%')
                    ->orWhere('primary_email', 'like', '%'.$search.'%');
            });
        }

        if ($request->filled('source')) {
            $query->where('source', $request->source);
        }

        if ($request->filled('tag')) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->tag.'%');
            });
        }

        $candidates = $query->orderBy('created_at', 'desc')->paginate(20);

        // Get filter options
        $sources = Candidate::pluck('source')
            ->filter()
            ->unique()
            ->sort()
            ->values();

        $tags = \App\Models\Tag::pluck('name')
            ->unique()
            ->sort()
            ->values();

        if ($request->expectsJson()) {
            return response()->json([
                'candidates' => $candidates->map(function ($candidate) {
                    return [
                        'id' => $candidate->id,
                        'name' => $candidate->full_name,
                        'email' => $candidate->primary_email,
                        'phone' => $candidate->primary_phone,
                        'source' => $candidate->source,
                        'tags' => $candidate->tags->map(function ($tag) {
                            return [
                                'name' => $tag->name,
                                'color' => $tag->color,
                            ];
                        }),
                        'applications_count' => $candidate->applications->count(),
                        'created_at' => $candidate->created_at,
                    ];
                }),
                'pagination' => [
                    'current_page' => $candidates->currentPage(),
                    'last_page' => $candidates->lastPage(),
                    'per_page' => $candidates->perPage(),
                    'total' => $candidates->total(),
                ],
            ]);
        }

        $tenant = tenant();
        
        // Get subscription limit information
        $currentPlan = $tenant->currentPlan();
        $currentCandidateCount = $tenant->candidates()->count();
        $maxCandidates = $currentPlan ? $currentPlan->max_candidates : 0;
        $canAddCandidates = $maxCandidates === -1 || $currentCandidateCount < $maxCandidates;

        return view('tenant.candidates.index', compact('candidates', 'sources', 'tags', 'tenant', 'currentCandidateCount', 'maxCandidates', 'canAddCandidates'));
    }

    public function show(string $tenant, Candidate $candidate)
    {
        // Ensure candidate belongs to current tenant
        if ($candidate->tenant_id !== tenant_id()) {
            abort(404, 'Candidate not found in this tenant');
        }

        // Load relationships efficiently
        $candidate->load([
            'tags',
            'notes.user',
            'resumes',
            'interviews' => function ($query) {
                $query->with([
                    'job:id,title',
                    'panelists:id,name'
                ])->orderBy('scheduled_at', 'desc');
            },
            'applications' => function ($query) {
                $query->with([
                    'jobOpening:id,title,department_id',
                    'jobOpening.department:id,name',
                    'currentStage:id,name'
                ]);
            }
        ]);

        $tenant = tenant();
        return view('tenant.candidates.show', compact('candidate', 'tenant'));
    }

    public function edit(string $tenant, Candidate $candidate)
    {
        // Ensure candidate belongs to current tenant
        if ($candidate->tenant_id !== tenant_id()) {
            abort(404, 'Candidate not found in this tenant');
        }

        $tenant = tenant();
        return view('tenant.candidates.edit', compact('candidate', 'tenant'));
    }

    public function update(StoreCandidateRequest $request, string $tenant, Candidate $candidate)
    {
        // Ensure candidate belongs to current tenant
        if ($candidate->tenant_id !== tenant_id()) {
            abort(404, 'Candidate not found in this tenant');
        }

        $candidate->update($request->validated());

        return redirect()
            ->route('tenant.candidates.show', ['tenant' => $tenant, 'candidate' => $candidate])
            ->with('success', 'Candidate updated successfully.');
    }

    public function storeResume(Request $request, string $tenant, Candidate $candidate)
    {
        // Ensure candidate belongs to current tenant
        if ($candidate->tenant_id !== tenant_id()) {
            abort(404, 'Candidate not found in this tenant');
        }

        $request->validate([
            'resume_file' => 'required|file|mimes:pdf,doc,docx|max:10240', // 10MB max
        ]);

        $file = $request->file('resume_file');
        $extension = $file->getClientOriginalExtension();
        $filename = Str::uuid() . '.' . $extension;
        $path = "resumes/{$candidate->tenant_id}/{$filename}";

        // Store file
        Storage::disk('public')->put($path, file_get_contents($file));

        // Create resume record
        $resume = Resume::create([
            'tenant_id' => $candidate->tenant_id,
            'candidate_id' => $candidate->id,
            'disk' => 'public',
            'path' => $path,
            'filename' => $file->getClientOriginalName(),
            'mime' => $file->getMimeType(),
            'size' => $file->getSize(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Resume uploaded successfully.',
            'resume' => $resume
        ]);
    }

    public function destroyResume(string $tenant, Candidate $candidate, Resume $resume)
    {
        // Ensure candidate and resume belong to current tenant
        if ($candidate->tenant_id !== tenant_id() || $resume->tenant_id !== tenant_id()) {
            abort(404, 'Resource not found in this tenant');
        }

        // Ensure resume belongs to candidate
        if ($resume->candidate_id !== $candidate->id) {
            abort(404, 'Resume not found for this candidate');
        }

        // Delete file from storage
        if (Storage::disk($resume->disk)->exists($resume->path)) {
            Storage::disk($resume->disk)->delete($resume->path);
        }

        // Delete resume record
        $resume->delete();

        return response()->json([
            'success' => true,
            'message' => 'Resume deleted successfully.'
        ]);
    }
}
