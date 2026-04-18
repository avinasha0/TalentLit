<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCandidateRequest;
use App\Models\Application;
use App\Models\Candidate;
use App\Models\JobStage;
use App\Models\Resume;
use App\Services\JobOfferNotificationService;
use App\Services\PreboardingAutomationService;
use App\Services\PreOnboardingDocumentChecklistService;
use App\Support\ApplicationStatus;
use App\Support\PreOnboardingDocumentCatalog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CandidateController extends Controller
{
    public function index(Request $request, string $tenant, $job = null)
    {
        // Get tenant context
        $tenantModel = tenant();
        if (! $tenantModel) {
            abort(404, 'Tenant not found');
        }

        $query = Candidate::where('tenant_id', $tenantModel->id)
            ->with(['tags', 'applications' => function ($q) {
                $q->with('jobOpening')
                    ->orderBy('applied_at', 'desc');
            }]);

        // Filter by job if job parameter is provided
        if ($job) {
            $jobId = (int) $job; // Ensure job parameter is cast to integer

            // Verify the job exists and belongs to the tenant
            $jobExists = \App\Models\JobOpening::where('id', $jobId)
                ->where('tenant_id', $tenantModel->id)
                ->exists();

            if (! $jobExists) {
                abort(404, 'Job not found');
            }

            $query->whereHas('applications', function ($q) use ($jobId) {
                $q->where('job_opening_id', $jobId);
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

        if ($request->filled('status')) {
            $query->whereHas('applications', function ($q) use ($request) {
                $q->where('status', $request->status);
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

        // Get available statuses (filters + legacy slugs)
        $statuses = ApplicationStatus::filterSlugs();

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

        // Get subscription limit information
        $currentPlan = $tenantModel->currentPlan();
        $currentCandidateCount = $tenantModel->candidates()->count();
        $maxCandidates = $currentPlan ? $currentPlan->max_candidates : 0;
        $canAddCandidates = $maxCandidates === -1 || $currentCandidateCount < $maxCandidates;

        return view('tenant.candidates.index', compact('candidates', 'sources', 'tags', 'statuses', 'tenantModel', 'currentCandidateCount', 'maxCandidates', 'canAddCandidates'));
    }

    public function show(string $tenant, Candidate $candidate)
    {
        // Get tenant context
        $tenantModel = tenant();
        if (! $tenantModel) {
            abort(404, 'Tenant not found');
        }

        // Ensure candidate belongs to current tenant
        if ($candidate->tenant_id !== $tenantModel->id) {
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
                    'panelists:id,name',
                ])->orderBy('scheduled_at', 'desc');
            },
            'applications' => function ($query) {
                $query->with([
                    'jobOpening:id,title,department_id',
                    'jobOpening.department:id,name',
                    'currentStage:id,name',
                    'preOnboardingDocuments',
                ]);
            },
        ]);

        $checklistSvc = app(PreOnboardingDocumentChecklistService::class);
        foreach ($candidate->applications as $application) {
            if (PreOnboardingDocumentCatalog::eligibleForChecklistSeed($application)) {
                $checklistSvc->ensureChecklist($application);
            }
        }
        $candidate->loadMissing(['applications.preOnboardingDocuments']);

        return view('tenant.candidates.show', compact('candidate', 'tenantModel'));
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
        $filename = Str::uuid().'.'.$extension;
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
            'resume' => $resume,
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
            'message' => 'Resume deleted successfully.',
        ]);
    }

    public function updateApplicationStatus(Request $request, string $tenant, Candidate $candidate, Application $application)
    {
        // Ensure candidate and application belong to current tenant
        $tenantModel = tenant();
        if (! $tenantModel) {
            abort(404, 'Tenant not found');
        }

        if ($candidate->tenant_id !== $tenantModel->id || $application->tenant_id !== $tenantModel->id) {
            abort(404, 'Resource not found in this tenant');
        }

        // Ensure application belongs to candidate
        if ($application->candidate_id !== $candidate->id) {
            abort(404, 'Application not found for this candidate');
        }

        $request->validate([
            'status' => ['required', Rule::in(ApplicationStatus::allowed())],
        ]);

        $previousStatus = strtolower((string) $application->status);

        $updateData = [
            'status' => $request->status,
        ];

        $normalizedStatus = match (strtolower($request->status)) {
            'active' => 'applied',
            'called' => 'screening',
            'interviewed' => 'interview',
            default => strtolower($request->status),
        };

        $canonicalStageNames = [
            'applied' => 'Applied',
            'screening' => 'Screening',
            'shortlisted' => 'Shortlisted',
            'interview' => 'Interview',
            'selected' => 'Selected',
            'offered' => 'Offered',
            'pre_onboarding' => 'Pre-Onboarding',
            'hired' => 'Hired',
        ];

        $stageNames = ApplicationStatus::stageNameCandidates($request->status);
        if ($stageNames !== []) {
            $jobStages = JobStage::where('tenant_id', $tenantModel->id)
                ->where('job_opening_id', $application->job_opening_id)
                ->orderBy('sort_order')
                ->get();

            $stage = null;

            // 1) Exact / case-insensitive stage name match
            foreach ($stageNames as $stageName) {
                $needle = strtolower($stageName);
                $stage = $jobStages->first(function (JobStage $jobStage) use ($needle) {
                    return strtolower($jobStage->name) === $needle;
                });
                if ($stage) {
                    break;
                }
            }

            // 2) Partial match for naming variants (e.g. "Screen" vs "Phone Screen")
            if (! $stage) {
                foreach ($stageNames as $stageName) {
                    $needle = strtolower($stageName);
                    $stage = $jobStages->first(function (JobStage $jobStage) use ($needle) {
                        return str_contains(strtolower($jobStage->name), $needle)
                            || str_contains($needle, strtolower($jobStage->name));
                    });
                    if ($stage) {
                        break;
                    }
                }
            }

            // 3) Final fallback: map pipeline status by sort order
            if (! $stage) {
                $pipelineIndex = array_search($normalizedStatus, ApplicationStatus::PIPELINE, true);
                if ($pipelineIndex !== false && $jobStages->isNotEmpty()) {
                    $stage = $jobStages->values()->get(min($pipelineIndex, $jobStages->count() - 1));
                }
            }

            // 4) Ensure canonical stage exists for this status and use it.
            if (! $stage && isset($canonicalStageNames[$normalizedStatus])) {
                $desiredSortOrder = array_search($normalizedStatus, ApplicationStatus::PIPELINE, true);
                $stage = JobStage::create([
                    'tenant_id' => $tenantModel->id,
                    'job_opening_id' => $application->job_opening_id,
                    'name' => $canonicalStageNames[$normalizedStatus],
                    'sort_order' => $desiredSortOrder === false ? ($jobStages->count() + 1) : ($desiredSortOrder + 1),
                    'is_terminal' => $normalizedStatus === 'hired',
                ]);
            }

            if ($stage) {
                $updateData['current_stage_id'] = $stage->id;
                if ($application->current_stage_id !== $stage->id) {
                    $nextStagePosition = Application::where('tenant_id', $tenantModel->id)
                        ->where('job_opening_id', $application->job_opening_id)
                        ->where('current_stage_id', $stage->id)
                        ->max('stage_position');
                    $updateData['stage_position'] = is_null($nextStagePosition) ? 0 : $nextStagePosition + 1;
                }
            }
        }

        $application->update($updateData);

        $newStatus = strtolower((string) $application->status);
        if ($newStatus === 'offered' && $previousStatus !== 'offered') {
            app(JobOfferNotificationService::class)->sendOfferEmail($application->fresh());
        }

        if ($request->status === 'hired') {
            app(PreboardingAutomationService::class)->initializeFromHire($application);
        }

        if ($newStatus === 'pre_onboarding' && $previousStatus !== 'pre_onboarding') {
            app(PreOnboardingDocumentChecklistService::class)->ensureChecklist($application->fresh());
        }

        // Reload the relationship to get the updated stage name
        $application->load('currentStage');

        return response()->json([
            'success' => true,
            'message' => 'Application status updated successfully.',
            'status' => $application->status,
            'current_stage' => $application->currentStage ? $application->currentStage->name : null,
        ]);
    }
}
