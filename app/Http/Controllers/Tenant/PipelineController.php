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
            ->with(['candidate', 'currentStage', 'interviews' => function($query) {
                $query->orderBy('scheduled_at', 'desc');
            }])
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
            ->with(['candidate', 'currentStage', 'interviews' => function($query) {
                $query->orderBy('scheduled_at', 'desc');
            }])
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

        try {
            $this->authorize('moveApplications', $job);
        } catch (\Exception $e) {
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
        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Validation failed: ' . $e->getMessage()
            ], 422);
        }

        try {
            $application = Application::where('tenant_id', $tenantModel->id)
                ->where('job_opening_id', $job->id)
                ->findOrFail($request->application_id);
        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Application not found: ' . $e->getMessage()
            ], 404);
        }

        // Verify the application belongs to the current stage
        if ($application->current_stage_id !== $request->from_stage_id) {
            return response()->json([
                'ok' => false,
                'message' => 'Application is not in the expected stage.'
            ], 400);
        }

        $toStageId = $request->to_stage_id;

        try {
            DB::transaction(function () use ($request, $application, $job, $tenantModel) {
                $fromStageId = $application->current_stage_id;
                $toStageId = $request->to_stage_id;
                $toPosition = $request->to_position ?? 0;

                // If moving to the same stage, just reorder
                if ($fromStageId === $toStageId) {
                    $this->reorderWithinStage($application, $toPosition);
                } else {
                    $this->moveToDifferentStage($application, $fromStageId, $toStageId, $toPosition);
                    
                    // Update interview status based on stage movement
                    $this->updateInterviewStatusForStageChange($application, $fromStageId, $toStageId);
                }

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
            });
        } catch (\Exception $e) {
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

        return response()->json([
            'ok' => true,
            'counts' => $stageCounts,
            'toStageId' => $toStageId,
            'candidateName' => $application->candidate->full_name
        ]);
    }

    private function reorderWithinStage(Application $application, int $newPosition)
    {

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

        $jobId = $application->job_opening_id;
        $tenantId = $application->tenant_id;

        // Shift positions in the source stage
        Application::where('tenant_id', $tenantId)
            ->where('job_opening_id', $jobId)
            ->where('current_stage_id', $fromStageId)
            ->where('stage_position', '>', $application->stage_position)
            ->decrement('stage_position');

        // Shift positions in the target stage
        Application::where('tenant_id', $tenantId)
            ->where('job_opening_id', $jobId)
            ->where('current_stage_id', $toStageId)
            ->where('stage_position', '>=', $newPosition)
            ->increment('stage_position');

        // Update the application
        $application->update([
            'current_stage_id' => $toStageId,
            'stage_position' => $newPosition,
        ]);
    }

    private function updateInterviewStatusForStageChange(Application $application, $fromStageId, $toStageId)
    {
        // Get stage names to determine if we're moving to/from interview stages
        $fromStage = \App\Models\JobStage::find($fromStageId);
        $toStage = \App\Models\JobStage::find($toStageId);
        
        if (!$fromStage || !$toStage) {
            return;
        }
        
        $fromStageName = strtolower($fromStage->name);
        $toStageName = strtolower($toStage->name);
        
        // Check if moving from interview stage to non-interview stage
        if (str_contains($fromStageName, 'interview') && !str_contains($toStageName, 'interview')) {
            // Get all interviews for this application
            $interviews = \App\Models\Interview::where('application_id', $application->id)
                ->where('status', 'scheduled')
                ->get();
            
            foreach ($interviews as $interview) {
                $oldStatus = $interview->status;
                $newStatus = 'canceled'; // Default
                
                // Update interview status based on the new stage
                if (str_contains($toStageName, 'hired')) {
                    $newStatus = 'completed';
                } elseif (str_contains($toStageName, 'rejected')) {
                    $newStatus = 'canceled';
                } elseif (str_contains($toStageName, 'withdrawn')) {
                    $newStatus = 'canceled';
                } else {
                    // For other stages like 'screen', 'sourced', etc.
                    $newStatus = 'canceled';
                }
                
                if ($oldStatus !== $newStatus) {
                    $updateData = ['status' => $newStatus];
                    
                    // Set cancellation reason if cancelling due to stage change
                    if ($newStatus === 'canceled') {
                        $updateData['cancellation_reason'] = 'stage_change';
                    }
                    
                    $interview->update($updateData);
                    
                    // Log the interview status change
                    \Log::info('Interview status updated due to stage change', [
                        'interview_id' => $interview->id,
                        'application_id' => $application->id,
                        'from_stage' => $fromStageName,
                        'to_stage' => $toStageName,
                        'old_status' => $oldStatus,
                        'new_status' => $newStatus,
                        'cancellation_reason' => $newStatus === 'canceled' ? 'stage_change' : null
                    ]);
                }
            }
        }
        
        // Check if moving from non-interview stage to interview stage
        if (!str_contains($fromStageName, 'interview') && str_contains($toStageName, 'interview')) {
            // Get all interviews for this application that are cancelled
            $interviews = \App\Models\Interview::where('application_id', $application->id)
                ->where('status', 'canceled')
                ->get();
            
            foreach ($interviews as $interview) {
                // Reschedule cancelled interviews if they're in the future
                if ($interview->scheduled_at > now()) {
                    $oldStatus = $interview->status;
                    $interview->update(['status' => 'scheduled']);
                    
                    // Log the interview status change
                    \Log::info('Interview status updated due to stage change', [
                        'interview_id' => $interview->id,
            'application_id' => $application->id,
                        'from_stage' => $fromStageName,
                        'to_stage' => $toStageName,
                        'old_status' => $oldStatus,
                        'new_status' => 'scheduled'
                    ]);
                }
            }
        }
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
