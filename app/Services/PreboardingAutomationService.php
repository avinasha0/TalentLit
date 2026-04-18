<?php

namespace App\Services;

use App\Models\Application;
use App\Models\Candidate;
use App\Models\PreboardingItem;
use App\Models\Tenant;
use Illuminate\Support\Carbon;

class PreboardingAutomationService
{
    /**
     * Initialize phase-1 pre-boarding when candidate is hired.
     */
    public function initializeFromHire(Application $application): void
    {
        $application->loadMissing(['candidate', 'jobOpening.department']);

        $candidate = $application->candidate;
        if (! $candidate) {
            return;
        }

        $tenant = Tenant::find($application->tenant_id);
        [$hrContact, $buddy] = $this->resolveContacts($tenant);

        $job = $application->jobOpening;
        $joiningDate = $candidate->joining_date ?? now()->addDays(7);

        $candidate->fill([
            // Force onboarding source so the existing onboarding module lists this hired candidate.
            'source' => 'Onboarding',
            'designation' => $candidate->designation ?: ($job?->title ?? $candidate->designation),
            'department' => $candidate->department ?: ($job?->department?->name ?? $candidate->department),
            'manager' => $candidate->manager ?: ($job?->hiring_manager_primary_name ?? $candidate->manager),
            'joining_date' => $joiningDate,
            'status' => $candidate->status ?: 'Pre-boarding',
            'total_steps' => 10,
            'completed_steps' => max(0, (int) $candidate->completed_steps),
            'hr_contact_name' => $candidate->hr_contact_name ?: ($hrContact['name'] ?? null),
            'hr_contact_email' => $candidate->hr_contact_email ?: ($hrContact['email'] ?? null),
            'buddy_name' => $candidate->buddy_name ?: ($buddy['name'] ?? null),
            'buddy_email' => $candidate->buddy_email ?: ($buddy['email'] ?? null),
            'welcome_kit_status' => $candidate->welcome_kit_status ?: 'dispatched',
            'preboarding_started_at' => $candidate->preboarding_started_at ?: now(),
        ]);
        $candidate->save();

        $this->seedDefaultItems($candidate, $application, Carbon::parse($candidate->joining_date));
        $this->syncProgress($candidate);
    }

    /**
     * When a candidate accepts a job offer: start pre-boarding checklist items (without full hire flow).
     */
    public function initializeFromAcceptedOffer(Application $application): void
    {
        $application->loadMissing(['candidate', 'jobOpening.department']);

        $candidate = $application->candidate;
        if (! $candidate) {
            return;
        }

        $tenant = Tenant::find($application->tenant_id);
        [$hrContact, $buddy] = $this->resolveContacts($tenant);

        $job = $application->jobOpening;
        $joiningDate = $candidate->joining_date ?? now()->addDays(14);

        $candidate->fill([
            'designation' => $candidate->designation ?: ($job?->title ?? $candidate->designation),
            'department' => $candidate->department ?: ($job?->department?->name ?? $candidate->department),
            'manager' => $candidate->manager ?: ($job?->hiring_manager_primary_name ?? $candidate->manager),
            'joining_date' => $joiningDate,
            'status' => $candidate->status ?: 'Pre-boarding',
            'total_steps' => max(1, (int) ($candidate->total_steps ?: 10)),
            'completed_steps' => max(0, (int) $candidate->completed_steps),
            'hr_contact_name' => $candidate->hr_contact_name ?: ($hrContact['name'] ?? null),
            'hr_contact_email' => $candidate->hr_contact_email ?: ($hrContact['email'] ?? null),
            'buddy_name' => $candidate->buddy_name ?: ($buddy['name'] ?? null),
            'buddy_email' => $candidate->buddy_email ?: ($buddy['email'] ?? null),
            'preboarding_started_at' => $candidate->preboarding_started_at ?: now(),
        ]);
        $candidate->save();

        if (! PreboardingItem::where('application_id', $application->id)->exists()) {
            $this->seedDefaultItems($candidate, $application, Carbon::parse($joiningDate));
        }

        $this->syncProgress($candidate->fresh());

        app(PreOnboardingDocumentChecklistService::class)->ensureChecklist($application->fresh());
    }

    public function syncProgress(Candidate $candidate): void
    {
        $items = PreboardingItem::where('tenant_id', $candidate->tenant_id)
            ->where('candidate_id', $candidate->id)
            ->get();

        if ($items->isEmpty()) {
            return;
        }

        $completed = $items->whereIn('status', ['completed', 'waived'])->count();
        $total = $items->count();

        $candidate->completed_steps = $completed;
        $candidate->total_steps = max($total, 1);
        $candidate->status = $this->deriveCandidateStatus($items);
        $candidate->save();
    }

    private function seedDefaultItems(Candidate $candidate, Application $application, Carbon $joiningDate): void
    {
        $templates = [
            // Document track (e-sign)
            ['document.offer_letter', 'document', 'Offer Letter E-signature', true, 1, -6],
            ['document.nda', 'document', 'NDA E-signature', true, 2, -5],
            ['document.policy_ack', 'document', 'Policy Acknowledgement E-signature', true, 3, -4],
            // IT track
            ['it.email_account', 'it', 'Corporate Email Provisioning', false, 1, -5],
            ['it.laptop', 'it', 'Laptop and Accessories Provisioning', false, 2, -4],
            ['it.system_access', 'it', 'System Access Requests', false, 3, -3],
            // Benefits track
            ['benefits.plan_selection', 'benefits', 'Benefits Plan Selection', false, 1, -3],
            ['benefits.nominee_details', 'benefits', 'Nominee and Dependents Submission', false, 2, -2],
            // Checklist gate
            ['checklist.pre_day1_gate', 'checklist', 'Pre-Day-1 Checklist Gate', false, 99, -1],
        ];

        foreach ($templates as [$key, $track, $title, $requiresEsign, $sortOrder, $daysBeforeJoin]) {
            PreboardingItem::firstOrCreate(
                [
                    'candidate_id' => $candidate->id,
                    'item_key' => $key,
                ],
                [
                    'tenant_id' => $candidate->tenant_id,
                    'application_id' => $application->id,
                    'track' => $track,
                    'title' => $title,
                    'status' => 'pending',
                    'requires_esign' => $requiresEsign,
                    'esign_status' => $requiresEsign ? 'sent' : null,
                    'assigned_to_name' => $this->resolveAssignedName($candidate, $track),
                    'assigned_to_email' => $this->resolveAssignedEmail($candidate, $track),
                    'due_date' => $joiningDate->copy()->addDays($daysBeforeJoin)->toDateString(),
                    'meta' => [
                        'trigger' => $application->status === 'hired' ? 'candidate_marked_hired' : 'offer_accepted',
                        'parallel_track' => in_array($track, ['document', 'it', 'benefits'], true),
                    ],
                    'sort_order' => $sortOrder,
                ]
            );
        }
    }

    private function deriveCandidateStatus($items): string
    {
        $gate = $items->firstWhere('item_key', 'checklist.pre_day1_gate');
        $documentPending = $items->where('track', 'document')->whereNotIn('status', ['completed', 'waived'])->count() > 0;
        $itPending = $items->where('track', 'it')->whereNotIn('status', ['completed', 'waived'])->count() > 0;
        $benefitsPending = $items->where('track', 'benefits')->whereNotIn('status', ['completed', 'waived'])->count() > 0;

        if ($gate && in_array($gate->status, ['completed', 'waived'], true)) {
            return 'Day 1 Ready';
        }

        if ($documentPending) {
            return 'Pending Docs';
        }

        if ($itPending) {
            return 'IT Pending';
        }

        if ($benefitsPending) {
            return 'Benefits Pending';
        }

        return 'Pre-boarding';
    }

    private function resolveAssignedName(Candidate $candidate, string $track): ?string
    {
        return match ($track) {
            'document', 'benefits', 'checklist' => $candidate->hr_contact_name,
            'it' => $candidate->manager,
            default => null,
        };
    }

    private function resolveAssignedEmail(Candidate $candidate, string $track): ?string
    {
        return match ($track) {
            'document', 'benefits', 'checklist' => $candidate->hr_contact_email,
            'it' => null,
            default => null,
        };
    }

    private function resolveContacts(?Tenant $tenant): array
    {
        if (! $tenant) {
            return [null, null];
        }

        $users = $tenant->users()
            ->select('users.name', 'users.email')
            ->orderBy('users.name')
            ->limit(2)
            ->get()
            ->values();

        $first = $users->get(0);
        $second = $users->get(1) ?? $first;

        return [
            $first ? ['name' => $first->name, 'email' => $first->email] : null,
            $second ? ['name' => $second->name, 'email' => $second->email] : null,
        ];
    }
}
