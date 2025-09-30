<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Mail\StageChanged;
use App\Models\Application;
use App\Models\ApplicationEvent;
use App\Models\JobOpening;
use App\Models\JobStage;
use App\Services\NotificationService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class PipelineController extends Controller
{
    use AuthorizesRequests;
    public function index(Request $request, $tenant, JobOpening $job)
    {
        $this->authorize('view', $job);

        // Get job stages ordered by sort_order
        $stages = $job->jobStages()
            ->orderBy('sort_order')
            ->get();

        // Get applications grouped by stage
        $applicationsByStage = $job->applications()
            ->with(['candidate', 'currentStage'])
            ->orderedByStagePosition()
            ->get()
            ->groupBy('current_stage_id');

        // Get recent events for audit trail
        $recentEvents = ApplicationEvent::where('job_id', $job->id)
            ->with(['application.candidate', 'fromStage', 'toStage', 'user'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
            
        \Log::info('Pipeline data loaded', [
            'job_id' => $job->id,
            'stages_count' => $stages->count(),
            'applications_count' => $job->applications()->count(),
            'applications_by_stage' => $applicationsByStage->map(function($apps) {
                return $apps->map(function($app) {
                    return [
                        'id' => $app->id,
                        'candidate_name' => $app->candidate ? $app->candidate->full_name : 'No candidate',
                        'stage_id' => $app->current_stage_id
                    ];
                });
            })
        ]);

        return view('tenant.jobs.pipeline', compact('job', 'stages', 'applicationsByStage', 'recentEvents'));
    }

    public function json(Request $request, $tenant, JobOpening $job)
    {
        $this->authorize('view', $job);

        // Get job stages ordered by sort_order
        $stages = $job->jobStages()
            ->orderBy('sort_order')
            ->get();

        // Get applications grouped by stage
        $applicationsByStage = $job->applications()
            ->with(['candidate', 'currentStage'])
            ->orderedByStagePosition()
            ->get()
            ->groupBy('current_stage_id');

        // Calculate counts for each stage
        $stageCounts = [];
        foreach ($stages as $stage) {
            $stageCounts[$stage->id] = $applicationsByStage->get($stage->id, collect())->count();
        }

        return response()->json([
            'stages' => $stages,
            'applicationsByStage' => $applicationsByStage,
            'stageCounts' => $stageCounts,
        ]);
    }

    public function move(Request $request, $tenant, JobOpening $job)
    {
        // Resolve tenant from slug
        $tenantModel = \App\Models\Tenant::where('slug', $tenant)->firstOrFail();
        
        \Log::info('=== PIPELINE MOVE REQUEST START ===', [
            'job_id' => $job->id,
            'tenant_slug' => $tenant,
            'tenant_id' => $tenantModel->id,
            'request_data' => $request->all(),
            'user_id' => auth()->id(),
            'timestamp' => now()
        ]);

        try {
            $this->authorize('moveApplications', $job);
            \Log::info('Authorization passed');
        } catch (\Exception $e) {
            \Log::error('Authorization failed', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);
            return response()->json([
                'ok' => false,
                'message' => 'Unauthorized: ' . $e->getMessage()
            ], 403);
        }

        try {
            $request->validate([
                'application_id' => 'required|uuid|exists:applications,id',
                'from_stage_id' => 'nullable|uuid|exists:job_stages,id',
                'to_stage_id' => 'required|uuid|exists:job_stages,id',
                'to_position' => 'integer|min:0',
            ]);
            \Log::info('Validation passed');
        } catch (\Exception $e) {
            \Log::error('Validation failed', [
                'error' => $e->getMessage(),
                'validation_errors' => $e->errors() ?? 'No validation errors'
            ]);
            return response()->json([
                'ok' => false,
                'message' => 'Validation failed: ' . $e->getMessage()
            ], 422);
        }

        try {
            $application = Application::where('tenant_id', $tenantModel->id)
                ->where('job_opening_id', $job->id)
                ->findOrFail($request->application_id);
            \Log::info('Application found', [
                'application_id' => $application->id,
                'current_stage_id' => $application->current_stage_id,
                'stage_position' => $application->stage_position
            ]);
        } catch (\Exception $e) {
            \Log::error('Application not found', [
                'error' => $e->getMessage(),
                'application_id' => $request->application_id,
                'job_id' => $job->id,
                'tenant_id' => $tenantModel->id
            ]);
            return response()->json([
                'ok' => false,
                'message' => 'Application not found: ' . $e->getMessage()
            ], 404);
        }

        // Verify the application belongs to the current stage
        if ($application->current_stage_id !== $request->from_stage_id) {
            \Log::error('Stage mismatch', [
                'application_current_stage' => $application->current_stage_id,
                'request_from_stage' => $request->from_stage_id
            ]);
            return response()->json([
                'ok' => false,
                'message' => 'Application is not in the expected stage.'
            ], 400);
        }

        try {
            \Log::info('Starting database transaction');
            DB::transaction(function () use ($request, $application, $job, $tenantModel) {
                $fromStageId = $application->current_stage_id;
                $toStageId = $request->to_stage_id;
                $toPosition = $request->to_position ?? 0;

                \Log::info('=== MOVING APPLICATION ===', [
                    'application_id' => $application->id,
                    'from_stage_id' => $fromStageId,
                    'to_stage_id' => $toStageId,
                    'to_position' => $toPosition
                ]);

                // If moving to the same stage, just reorder
                if ($fromStageId === $toStageId) {
                    \Log::info('Reordering within same stage');
                    $this->reorderWithinStage($application, $toPosition);
                } else {
                    \Log::info('Moving to different stage');
                    $this->moveToDifferentStage($application, $fromStageId, $toStageId, $toPosition);
                }

                \Log::info('Creating audit event');
                // Create audit event
                ApplicationEvent::create([
                    'tenant_id' => $tenantModel->id,
                    'application_id' => $application->id,
                    'job_id' => $job->id,
                    'from_stage_id' => $fromStageId,
                    'to_stage_id' => $toStageId,
                    'user_id' => Auth::id(),
                    'note' => $request->note,
                ]);
                \Log::info('Audit event created successfully');
            });
            \Log::info('Database transaction completed successfully');
        } catch (\Exception $e) {
            \Log::error('=== PIPELINE MOVE FAILED ===', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'application_id' => $application->id ?? 'unknown'
            ]);
            
            return response()->json([
                'ok' => false,
                'message' => 'Failed to move application: ' . $e->getMessage()
            ], 500);
        }

        // Send stage change notification if moving to different stage
        if ($request->from_stage_id !== $request->to_stage_id) {
            $this->sendStageChangeNotification($tenantModel, $job, $application, $request->to_stage_id, $request->note);
        }

        // Get updated counts
        $stageCounts = $this->getStageCounts($job);
        \Log::info('=== PIPELINE MOVE SUCCESS ===', [
            'stage_counts' => $stageCounts,
            'application_id' => $application->id
        ]);

        return response()->json([
            'ok' => true,
            'counts' => $stageCounts,
        ]);
    }

    private function reorderWithinStage(Application $application, int $newPosition)
    {
        \Log::info('=== REORDERING WITHIN STAGE ===', [
            'application_id' => $application->id,
            'stage_id' => $application->current_stage_id,
            'new_position' => $newPosition
        ]);

        $stageId = $application->current_stage_id;
        $jobId = $application->job_opening_id;
        $tenantId = $application->tenant_id;

        // Get all applications in this stage
        $applications = Application::where('tenant_id', $tenantId)
            ->where('job_opening_id', $jobId)
            ->where('current_stage_id', $stageId)
            ->where('id', '!=', $application->id)
            ->orderedByStagePosition()
            ->get();

        \Log::info('Found applications in stage', [
            'count' => $applications->count(),
            'application_ids' => $applications->pluck('id')->toArray()
        ]);

        // Reorder positions
        $position = 0;
        foreach ($applications as $app) {
            if ($position === $newPosition) {
                $position++; // Skip the position for the moved application
            }
            $app->update(['stage_position' => $position]);
            \Log::info('Updated position', [
                'application_id' => $app->id,
                'new_position' => $position
            ]);
            $position++;
        }

        // Set the moved application's position
        $application->update(['stage_position' => $newPosition]);
        \Log::info('Updated moved application position', [
            'application_id' => $application->id,
            'new_position' => $newPosition
        ]);
    }

    private function moveToDifferentStage(Application $application, $fromStageId, $toStageId, int $newPosition)
    {
        \Log::info('=== MOVING TO DIFFERENT STAGE ===', [
            'application_id' => $application->id,
            'from_stage_id' => $fromStageId,
            'to_stage_id' => $toStageId,
            'new_position' => $newPosition,
            'current_position' => $application->stage_position
        ]);

        $jobId = $application->job_opening_id;
        $tenantId = $application->tenant_id;

        // Shift positions in the source stage
        $shiftedFrom = Application::where('tenant_id', $tenantId)
            ->where('job_opening_id', $jobId)
            ->where('current_stage_id', $fromStageId)
            ->where('stage_position', '>', $application->stage_position)
            ->decrement('stage_position');
        \Log::info('Shifted positions in source stage', ['count' => $shiftedFrom]);

        // Shift positions in the target stage
        $shiftedTo = Application::where('tenant_id', $tenantId)
            ->where('job_opening_id', $jobId)
            ->where('current_stage_id', $toStageId)
            ->where('stage_position', '>=', $newPosition)
            ->increment('stage_position');
        \Log::info('Shifted positions in target stage', ['count' => $shiftedTo]);

        // Update the application
        $application->update([
            'current_stage_id' => $toStageId,
            'stage_position' => $newPosition,
        ]);
        \Log::info('Updated application stage and position', [
            'application_id' => $application->id,
            'new_stage_id' => $toStageId,
            'new_position' => $newPosition
        ]);
    }

    private function getStageCounts(JobOpening $job)
    {
        return $job->applications()
            ->selectRaw('current_stage_id, COUNT(*) as count')
            ->groupBy('current_stage_id')
            ->pluck('count', 'current_stage_id')
            ->toArray();
    }

    private function sendStageChangeNotification($tenant, JobOpening $job, Application $application, $toStageId, $note = null): void
    {
        try {
            if (config('mail.default') !== null && $application->candidate && $application->candidate->primary_email) {
                $stage = JobStage::find($toStageId);
                
                if ($stage) {
                    Mail::to($application->candidate->primary_email)
                        ->queue(new StageChanged($tenant, $job, $application->candidate, $application, $stage, $note));
                }
            }
        } catch (\Exception $e) {
            // Log error but don't break the application flow
            \Log::info('Failed to send stage change notification', [
                'tenant_id' => $tenant->id,
                'job_id' => $job->id,
                'application_id' => $application->id,
                'candidate_id' => $application->candidate_id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
