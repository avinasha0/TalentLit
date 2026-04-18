<?php

namespace App\Support;

/**
 * Application (per job) status values shown in the candidate UI and filters.
 */
final class ApplicationStatus
{
    /** Primary hiring pipeline order */
    public const PIPELINE = [
        'applied',
        'screening',
        'shortlisted',
        'interview',
        'selected',
        'offered',
        'pre_onboarding',
        'hired',
    ];

    public const OTHER = ['hold', 'rejected', 'withdrawn'];

    /** Older records may still use these slugs */
    public const LEGACY = ['active', 'called', 'interviewed'];

    /**
     * @return list<string>
     */
    public static function allowed(): array
    {
        return array_values(array_unique(array_merge(
            self::PIPELINE,
            self::OTHER,
            self::LEGACY,
        )));
    }

    /**
     * @return array<string, string>
     */
    public static function labels(): array
    {
        return [
            'applied' => 'Applied',
            'active' => 'Applied',
            'screening' => 'Screening',
            'called' => 'Screening (legacy)',
            'shortlisted' => 'Shortlisted',
            'interview' => 'Interview',
            'interviewed' => 'Interview (legacy)',
            'selected' => 'Selected',
            'offered' => 'Offered',
            'pre_onboarding' => 'Pre-Onboarding',
            'hired' => 'Joined (Hired)',
            'hold' => 'Hold',
            'rejected' => 'Rejected',
            'withdrawn' => 'Withdrawn',
        ];
    }

    public static function label(string $status): string
    {
        $key = strtolower($status);

        return self::labels()[$key] ?? ucfirst(str_replace('_', ' ', $key));
    }

    /**
     * Job stage names to try in order when syncing current_stage_id.
     *
     * @return list<string>
     */
    public static function stageNameCandidates(string $status): array
    {
        return match (strtolower($status)) {
            'applied', 'active' => ['Applied'],
            'screening', 'called' => ['Screening', 'Screen', 'Phone Screen'],
            'shortlisted' => ['Shortlisted', 'Screen', 'Phone Screen'],
            'interview', 'interviewed' => ['Interview'],
            'selected' => ['Selected', 'Final Review', 'Interview'],
            'offered' => ['Offered', 'Offer'],
            'pre_onboarding' => ['Pre-Onboarding', 'Preboarding', 'Offer'],
            'hired' => ['Hired', 'Joined'],
            default => [],
        };
    }

    /**
     * Status values for the candidates index filter dropdown.
     * Excludes legacy slugs superseded by pipeline values (still valid on applications).
     *
     * @return list<string>
     */
    public static function filterSlugs(): array
    {
        return array_values(array_unique(array_merge(
            self::PIPELINE,
            self::OTHER,
            ['active'],
        )));
    }

    /**
     * Application statuses counted as "in pipeline" (not terminal / on hold).
     *
     * @return list<string>
     */
    public static function openPipelineStatuses(): array
    {
        return array_values(array_diff(
            array_merge(self::PIPELINE, self::LEGACY),
            ['hired', 'rejected', 'withdrawn', 'hold'],
        ));
    }
}
