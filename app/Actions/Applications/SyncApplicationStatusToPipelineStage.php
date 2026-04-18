<?php

namespace App\Actions\Applications;

use App\Models\Application;
use App\Models\JobStage;
use App\Models\Tenant;
use App\Support\ApplicationStatus;

/**
 * Resolves job pipeline stage + position for an application status (mirrors CandidateController logic).
 */
final class SyncApplicationStatusToPipelineStage
{
    /**
     * @return array<string, mixed> Keys: status, current_stage_id?, stage_position?
     */
    public function attributesForStatus(Application $application, Tenant $tenant, string $normalizedStatus): array
    {
        $normalizedStatus = strtolower($normalizedStatus);

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

        $requestStatusForStageNames = match ($normalizedStatus) {
            'applied' => 'applied',
            'screening' => 'screening',
            'shortlisted' => 'shortlisted',
            'interview' => 'interview',
            'selected' => 'selected',
            'offered' => 'offered',
            'pre_onboarding' => 'pre_onboarding',
            'hired' => 'hired',
            default => $normalizedStatus,
        };

        $stageNames = ApplicationStatus::stageNameCandidates($requestStatusForStageNames);
        $out = ['status' => $normalizedStatus];

        if ($stageNames === []) {
            return $out;
        }

        $jobStages = JobStage::where('tenant_id', $tenant->id)
            ->where('job_opening_id', $application->job_opening_id)
            ->orderBy('sort_order')
            ->get();

        $stage = null;

        foreach ($stageNames as $stageName) {
            $needle = strtolower($stageName);
            $stage = $jobStages->first(function (JobStage $jobStage) use ($needle) {
                return strtolower($jobStage->name) === $needle;
            });
            if ($stage) {
                break;
            }
        }

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

        if (! $stage) {
            $pipelineIndex = array_search($normalizedStatus, ApplicationStatus::PIPELINE, true);
            if ($pipelineIndex !== false && $jobStages->isNotEmpty()) {
                $stage = $jobStages->values()->get(min($pipelineIndex, $jobStages->count() - 1));
            }
        }

        if (! $stage && isset($canonicalStageNames[$normalizedStatus])) {
            $desiredSortOrder = array_search($normalizedStatus, ApplicationStatus::PIPELINE, true);
            $stage = JobStage::create([
                'tenant_id' => $tenant->id,
                'job_opening_id' => $application->job_opening_id,
                'name' => $canonicalStageNames[$normalizedStatus],
                'sort_order' => $desiredSortOrder === false ? ($jobStages->count() + 1) : ($desiredSortOrder + 1),
                'is_terminal' => $normalizedStatus === 'hired',
            ]);
        }

        if ($stage) {
            $out['current_stage_id'] = $stage->id;
            if ($application->current_stage_id !== $stage->id) {
                $nextStagePosition = Application::where('tenant_id', $tenant->id)
                    ->where('job_opening_id', $application->job_opening_id)
                    ->where('current_stage_id', $stage->id)
                    ->max('stage_position');
                $out['stage_position'] = is_null($nextStagePosition) ? 0 : $nextStagePosition + 1;
            }
        }

        return $out;
    }
}
