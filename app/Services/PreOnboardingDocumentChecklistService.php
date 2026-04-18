<?php

namespace App\Services;

use App\Mail\PreOnboardingDocumentUploadedToHrMail;
use App\Mail\PreOnboardingDocumentsRequestedMail;
use App\Models\Application;
use App\Models\PreOnboardingDocument;
use App\Models\User;
use App\Support\PreOnboardingDocumentCatalog;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class PreOnboardingDocumentChecklistService
{
    /**
     * Create missing checklist rows when the application is eligible; notify candidate when any row is new.
     */
    public function ensureChecklist(Application $application): void
    {
        if (! PreOnboardingDocumentCatalog::eligibleForChecklistSeed($application)) {
            return;
        }

        $application->loadMissing(['candidate', 'jobOpening']);

        $anyNew = false;

        foreach (PreOnboardingDocumentCatalog::definitions() as $def) {
            $doc = PreOnboardingDocument::query()->firstOrCreate(
                [
                    'application_id' => $application->id,
                    'document_key' => $def['key'],
                ],
                [
                    'tenant_id' => $application->tenant_id,
                    'candidate_id' => $application->candidate_id,
                    'title' => $def['title'],
                    'is_required' => $def['required'],
                    'status' => 'pending',
                ]
            );

            if ($doc->wasRecentlyCreated) {
                $anyNew = true;
            }
        }

        if ($anyNew) {
            $candidate = $application->candidate;
            $email = $candidate?->primary_email;
            if ($email) {
                $tenant = \App\Models\Tenant::find($application->tenant_id);
                if ($tenant) {
                    Mail::to($email)->queue(new PreOnboardingDocumentsRequestedMail($tenant, $application->fresh(['jobOpening', 'candidate'])));
                }
            }
        }
    }

    /**
     * @return array{required_total: int, required_done: int, optional_total: int, optional_done: int, all_total: int, all_done: int}
     */
    public function progress(Application $application): array
    {
        $docs = PreOnboardingDocument::query()
            ->where('application_id', $application->id)
            ->get();

        $required = $docs->where('is_required', true);
        $optional = $docs->where('is_required', false);

        $countDone = fn ($collection) => $collection->filter(fn (PreOnboardingDocument $d) => PreOnboardingDocumentCatalog::isCompleteStatus($d->status))->count();

        return [
            'required_total' => $required->count(),
            'required_done' => $countDone($required),
            'optional_total' => $optional->count(),
            'optional_done' => $countDone($optional),
            'all_total' => $docs->count(),
            'all_done' => $countDone($docs),
        ];
    }

    public function notifyHrOfUpload(Application $application, PreOnboardingDocument $document): void
    {
        $application->loadMissing(['candidate', 'jobOpening']);
        $tenant = \App\Models\Tenant::find($application->tenant_id);
        if (! $tenant) {
            return;
        }

        $emails = $this->hrNotificationEmails($application);

        foreach ($emails as $email) {
            Mail::to($email)->queue(new PreOnboardingDocumentUploadedToHrMail($tenant, $application, $document));
        }
    }

    /**
     * @return list<string>
     */
    private function hrNotificationEmails(Application $application): array
    {
        $emails = collect();

        $job = $application->jobOpening;
        if ($job) {
            foreach (['hiring_manager_primary_email', 'hiring_manager_secondary_email'] as $field) {
                $addr = trim((string) ($job->{$field} ?? ''));
                if ($addr !== '' && filter_var($addr, FILTER_VALIDATE_EMAIL)) {
                    $emails->push(strtolower($addr));
                }
            }
        }

        $tenantId = $application->tenant_id;
        $userIds = \Illuminate\Support\Facades\DB::table('tenant_user')
            ->where('tenant_id', $tenantId)
            ->pluck('user_id');

        $users = User::query()->whereIn('id', $userIds)->get();
        foreach ($users as $user) {
            if ($user->hasAnyRole(['Owner', 'Admin', 'Recruiter'], $tenantId) && $user->email) {
                $emails->push(strtolower(trim($user->email)));
            }
        }

        return $emails->unique()->values()->all();
    }

    public function deleteStoredFile(?string $path): void
    {
        if (! $path) {
            return;
        }
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
