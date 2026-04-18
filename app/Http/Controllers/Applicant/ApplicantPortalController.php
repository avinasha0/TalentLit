<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Candidate;
use App\Services\PreOnboardingDocumentChecklistService;
use App\Support\PreOnboardingDocumentCatalog;
use Illuminate\View\View;

class ApplicantPortalController extends Controller
{
    public function index(): View
    {
        $tenant = tenant();
        if (! $tenant) {
            abort(404);
        }

        $tenant->load('branding');

        $account = auth()->guard('candidate')->user();

        $candidate = Candidate::query()
            ->where('tenant_id', $tenant->id)
            ->where('candidate_account_id', $account->id)
            ->firstOrFail();

        $applications = Application::query()
            ->where('tenant_id', $tenant->id)
            ->where('candidate_id', $candidate->id)
            ->with([
                'jobOpening.department',
                'jobOpening.location',
                'jobOpening.globalLocation',
                'currentStage',
                'stageEvents.toStage',
                'stageEvents.fromStage',
                'interviews',
                'preOnboardingDocuments',
            ])
            ->orderByDesc('applied_at')
            ->orderByDesc('created_at')
            ->get();

        $checklistSvc = app(PreOnboardingDocumentChecklistService::class);
        foreach ($applications as $application) {
            if (PreOnboardingDocumentCatalog::eligibleForChecklistSeed($application)) {
                $checklistSvc->ensureChecklist($application);
            }
        }
        $applications->loadMissing('preOnboardingDocuments');

        return view('applicant.dashboard', [
            'tenant' => $tenant,
            'candidate' => $candidate,
            'applications' => $applications,
        ]);
    }
}
