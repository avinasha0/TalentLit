<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Candidate;
use App\Models\PreOnboardingDocument;
use App\Services\PreOnboardingDocumentChecklistService;
use App\Support\PreOnboardingDocumentCatalog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ApplicantPreOnboardingDocumentController extends Controller
{
    public function uploadPath(Request $request, string $tenant, Application $application, PreOnboardingDocument $document): RedirectResponse
    {
        return $this->uploadForApplication($request, $application, $document);
    }

    public function uploadSubdomain(Request $request, Application $application, PreOnboardingDocument $document): RedirectResponse
    {
        return $this->uploadForApplication($request, $application, $document);
    }

    private function uploadForApplication(Request $request, Application $application, PreOnboardingDocument $document): RedirectResponse
    {
        $tenantModel = tenant();
        if (! $tenantModel) {
            abort(404);
        }

        $account = auth()->guard('candidate')->user();
        $candidate = Candidate::query()
            ->where('tenant_id', $tenantModel->id)
            ->where('candidate_account_id', $account->id)
            ->firstOrFail();

        abort_unless($application->tenant_id === $tenantModel->id, 403);
        abort_unless($application->candidate_id === $candidate->id, 403);
        abort_unless($document->application_id === $application->id, 403);
        abort_unless($document->tenant_id === $tenantModel->id, 403);
        abort_unless(PreOnboardingDocumentCatalog::eligibleForUpload($application), 403);

        $request->validate([
            'file' => ['required', 'file', 'max:10240', 'mimes:pdf,jpg,jpeg,png,doc,docx'],
        ]);

        $file = $request->file('file');
        $extension = $file->getClientOriginalExtension() ?: 'bin';
        $filename = Str::uuid().'.'.$extension;
        $path = "pre-onboarding-docs/{$tenantModel->id}/{$application->id}/{$filename}";

        $checklist = app(PreOnboardingDocumentChecklistService::class);
        $checklist->deleteStoredFile($document->file_path);

        Storage::disk('public')->put($path, $file->getContent());

        $document->forceFill([
            'file_path' => $path,
            'original_filename' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'uploaded_at' => now(),
            'uploaded_by_candidate_account_id' => $account->id,
            'uploaded_by_user_id' => null,
            'status' => 'uploaded',
            'verified_at' => null,
            'verified_by_user_id' => null,
        ])->save();

        $checklist->notifyHrOfUpload($application->fresh(['candidate', 'jobOpening']), $document->fresh());

        return $this->redirectToDashboard($tenantModel, $application, 'File uploaded successfully.');
    }

    private function redirectToDashboard($tenantModel, Application $application, string $message): RedirectResponse
    {
        if (request()->routeIs('subdomain.*')) {
            return redirect()
                ->route('subdomain.applicant.dashboard')
                ->with('portal_flash', $message)
                ->with('portal_application_id', $application->id);
        }

        return redirect()
            ->route('tenant.applicant.dashboard', ['tenant' => $tenantModel->slug])
            ->with('portal_flash', $message)
            ->with('portal_application_id', $application->id);
    }
}
