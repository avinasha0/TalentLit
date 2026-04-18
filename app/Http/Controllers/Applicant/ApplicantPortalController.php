<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Candidate;
use Illuminate\View\View;

class ApplicantPortalController extends Controller
{
    public function index(): View
    {
        $tenant = tenant();
        if (!$tenant) {
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
            ])
            ->orderByDesc('applied_at')
            ->orderByDesc('created_at')
            ->get();

        return view('applicant.dashboard', [
            'tenant' => $tenant,
            'candidate' => $candidate,
            'applications' => $applications,
        ]);
    }
}
