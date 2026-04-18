<?php

namespace App\Services;

use App\Models\Department;
use App\Models\JobOpening;
use App\Models\Location;
use App\Models\Requisition;
use App\Models\Tenant;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CreateDraftJobFromApprovedRequisition
{
    /**
     * Temporary / contract roles on requisitions map to job employment_type `contract`
     * (distinct from fixed-term "Contract" type stored the same way in UI).
     */
    private const CONTRACT_TYPE_TO_EMPLOYMENT = [
        'Full-time' => 'full_time',
        'Part-time' => 'part_time',
        'Contract' => 'contract',
        'Intern' => 'intern',
        'Temporary' => 'contract',
    ];

    public function handle(Requisition $requisition): ?JobOpening
    {
        if ($requisition->approval_status !== 'Approved' || $requisition->status !== 'Approved') {
            return null;
        }

        $existing = JobOpening::withoutGlobalScope('tenant')
            ->where('staffing_requisition_id', $requisition->id)
            ->first();
        if ($existing) {
            return $existing;
        }

        $tenant = Tenant::query()->find($requisition->tenant_id);
        if (!$tenant) {
            Log::warning('CreateDraftJobFromApprovedRequisition: tenant not found', [
                'requisition_id' => $requisition->id,
                'tenant_id' => $requisition->tenant_id,
            ]);

            return null;
        }

        $tenant->load('activeSubscription.plan');
        $currentPlan = $tenant->currentPlan();
        $currentJobCount = $tenant->jobOpenings()->count();
        $maxJobs = $currentPlan ? $currentPlan->max_job_openings : 0;
        $canAddJobs = $maxJobs === -1 || $currentJobCount < $maxJobs;
        if (!$canAddJobs) {
            Log::warning('CreateDraftJobFromApprovedRequisition: tenant at job limit, skipping auto-create', [
                'requisition_id' => $requisition->id,
                'tenant_id' => $tenant->id,
                'current_job_count' => $currentJobCount,
                'max_job_openings' => $maxJobs,
            ]);

            return null;
        }

        $department = $this->resolveDepartment($requisition);
        if (!$department) {
            Log::warning('CreateDraftJobFromApprovedRequisition: no tenant department match for requisition department name', [
                'requisition_id' => $requisition->id,
                'department' => $requisition->department,
            ]);

            return null;
        }

        $locationId = $this->resolveLocationId($requisition);

        $employmentType = self::CONTRACT_TYPE_TO_EMPLOYMENT[$requisition->contract_type] ?? 'full_time';

        $slug = $this->makeUniqueSlug($requisition->job_title, $requisition->tenant_id);
        $description = $this->buildDescription($requisition);
        $openingsCount = max(1, (int) $requisition->headcount);

        $payload = [
            'tenant_id' => $requisition->tenant_id,
            'requisition_id' => null,
            'staffing_requisition_id' => $requisition->id,
            'title' => $requisition->job_title,
            'slug' => $slug,
            'department_id' => $department->id,
            'location_id' => $locationId,
            'employment_type' => $employmentType,
            'status' => 'draft',
            'openings_count' => $openingsCount,
            'description' => $description,
            'published_at' => null,
        ];

        try {
            return JobOpening::create($payload);
        } catch (QueryException $e) {
            if ($this->isDuplicateStaffingRequisition($e)) {
                return JobOpening::withoutGlobalScope('tenant')
                    ->where('staffing_requisition_id', $requisition->id)
                    ->first();
            }
            throw $e;
        }
    }

    private function resolveDepartment(Requisition $requisition): ?Department
    {
        $name = trim((string) ($requisition->department ?? ''));
        if ($name === '') {
            return null;
        }

        $tenantId = $requisition->tenant_id;

        $exact = Department::withoutGlobalScope('tenant')
            ->where('tenant_id', $tenantId)
            ->where('name', $name)
            ->first();
        if ($exact) {
            return $exact;
        }

        $normalized = mb_strtolower($name);

        return Department::withoutGlobalScope('tenant')
            ->where('tenant_id', $tenantId)
            ->whereRaw('LOWER(TRIM(name)) = ?', [$normalized])
            ->first();
    }

    private function resolveLocationId(Requisition $requisition): ?string
    {
        $name = trim((string) ($requisition->location ?? ''));
        if ($name === '') {
            return null;
        }

        $tenantId = $requisition->tenant_id;

        $exact = Location::withoutGlobalScope('tenant')
            ->where('tenant_id', $tenantId)
            ->where('name', $name)
            ->first();
        if ($exact) {
            return $exact->id;
        }

        $normalized = mb_strtolower($name);
        $loc = Location::withoutGlobalScope('tenant')
            ->where('tenant_id', $tenantId)
            ->whereRaw('LOWER(TRIM(name)) = ?', [$normalized])
            ->first();

        return $loc?->id;
    }

    private function isDuplicateStaffingRequisition(QueryException $e): bool
    {
        $code = $e->errorInfo[1] ?? null;
        // MySQL duplicate = 1062; SQLite unique constraint = 19
        if ($code === 1062 || $code === 19) {
            return true;
        }

        return str_contains(strtolower($e->getMessage()), 'duplicate')
            || str_contains(strtolower($e->getMessage()), 'unique constraint failed');
    }

    private function makeUniqueSlug(string $title, string $tenantId): string
    {
        $baseSlug = Str::slug($title) ?: 'job';
        $slug = $baseSlug;
        $counter = 1;
        while (JobOpening::withoutGlobalScope('tenant')
            ->where('tenant_id', $tenantId)
            ->where('slug', $slug)
            ->exists()) {
            $slug = $baseSlug.'-'.$counter;
            $counter++;
        }

        return $slug;
    }

    private function buildDescription(Requisition $requisition): string
    {
        $parts = [];

        $parts[] = 'Justification'."\n".$requisition->justification;

        $skillsText = $this->formatSkills($requisition->skills);
        if ($skillsText !== '') {
            $parts[] = 'Skills / requirements'."\n".$skillsText;
        }

        if ($requisition->additional_notes) {
            $parts[] = 'Additional notes'."\n".$requisition->additional_notes;
        }

        $meta = [];
        $meta[] = 'Budget: '.$requisition->budget_min.' – '.$requisition->budget_max;
        $meta[] = 'Experience (years): '.$requisition->experience_min
            .($requisition->experience_max !== null ? ' – '.$requisition->experience_max : '');
        $meta[] = 'Priority: '.$requisition->priority;
        $meta[] = 'Department (requisition): '.$requisition->department;
        if ($requisition->location) {
            $meta[] = 'Location (requisition): '.$requisition->location;
        }
        $parts[] = 'Details'."\n".implode("\n", $meta);

        return implode("\n\n", $parts);
    }

    private function formatSkills(?string $skills): string
    {
        if ($skills === null || $skills === '') {
            return '';
        }
        $trimmed = trim($skills);
        if ($trimmed !== '' && ($trimmed[0] === '[' || $trimmed[0] === '{')) {
            $decoded = json_decode($skills, true);
            if (is_array($decoded)) {
                return implode(', ', array_map('strval', $decoded));
            }
        }

        return $trimmed;
    }
}
