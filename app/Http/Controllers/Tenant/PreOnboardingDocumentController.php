<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Candidate;
use App\Models\PreOnboardingDocument;
use App\Services\PreOnboardingDocumentChecklistService;
use App\Support\PreOnboardingDocumentCatalog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PreOnboardingDocumentController extends Controller
{
    public function downloadPath(Request $request, string $tenant, Candidate $candidate, Application $application, PreOnboardingDocument $document): StreamedResponse|Response
    {
        return $this->downloadInternal($candidate, $application, $document);
    }

    public function downloadSubdomain(Request $request, Candidate $candidate, Application $application, PreOnboardingDocument $document): StreamedResponse|Response
    {
        return $this->downloadInternal($candidate, $application, $document);
    }

    private function downloadInternal(Candidate $candidate, Application $application, PreOnboardingDocument $document): StreamedResponse|Response
    {
        $this->assertStaffApplication($candidate, $application);
        $this->assertDocument($application, $document);
        abort_unless($document->hasFile(), 404);

        return Storage::disk('public')->download(
            $document->file_path,
            $document->original_filename ?: basename($document->file_path)
        );
    }

    public function staffUploadPath(Request $request, string $tenant, Candidate $candidate, Application $application, PreOnboardingDocument $document): RedirectResponse
    {
        return $this->staffUploadInternal($request, $candidate, $application, $document);
    }

    public function staffUploadSubdomain(Request $request, Candidate $candidate, Application $application, PreOnboardingDocument $document): RedirectResponse
    {
        return $this->staffUploadInternal($request, $candidate, $application, $document);
    }

    private function staffUploadInternal(Request $request, Candidate $candidate, Application $application, PreOnboardingDocument $document): RedirectResponse
    {
        $this->assertStaffApplication($candidate, $application);
        $this->assertDocument($application, $document);
        abort_unless(PreOnboardingDocumentCatalog::eligibleForUpload($application), 403);

        $request->validate([
            'file' => ['required', 'file', 'max:10240', 'mimes:pdf,jpg,jpeg,png,doc,docx'],
        ]);

        $tenantModel = tenant();
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
            'uploaded_by_user_id' => auth()->id(),
            'uploaded_by_candidate_account_id' => null,
            'status' => 'uploaded',
            'verified_at' => null,
            'verified_by_user_id' => null,
        ])->save();

        $checklist->notifyHrOfUpload($application->fresh(['candidate', 'jobOpening']), $document->fresh());

        return $this->redirectToCandidateShow($tenantModel, $candidate, 'Document uploaded for '.$document->title.'.');
    }

    public function verifyPath(Request $request, string $tenant, Candidate $candidate, Application $application, PreOnboardingDocument $document): RedirectResponse
    {
        return $this->verifyInternal($candidate, $application, $document);
    }

    public function verifySubdomain(Request $request, Candidate $candidate, Application $application, PreOnboardingDocument $document): RedirectResponse
    {
        return $this->verifyInternal($candidate, $application, $document);
    }

    private function verifyInternal(Candidate $candidate, Application $application, PreOnboardingDocument $document): RedirectResponse
    {
        $this->assertStaffApplication($candidate, $application);
        $this->assertDocument($application, $document);
        abort_unless(auth()->user()?->isHrAdmin((string) tenant_id()) ?? false, 403);
        abort_unless($document->hasFile(), 422);
        abort_unless(strtolower((string) $document->status) === 'uploaded', 422);

        $document->forceFill([
            'status' => 'verified',
            'verified_at' => now(),
            'verified_by_user_id' => auth()->id(),
        ])->save();

        $tenantModel = tenant();

        return $this->redirectToCandidateShow($tenantModel, $candidate, 'Document marked verified: '.$document->title.'.');
    }

    private function redirectToCandidateShow($tenantModel, Candidate $candidate, string $message): RedirectResponse
    {
        if (request()->routeIs('subdomain.*')) {
            return redirect()
                ->route('subdomain.candidates.show', ['candidate' => $candidate->id])
                ->with('success', $message);
        }

        return redirect()
            ->route('tenant.candidates.show', ['tenant' => $tenantModel->slug, 'candidate' => $candidate->id])
            ->with('success', $message);
    }

    private function assertStaffApplication(Candidate $candidate, Application $application): void
    {
        $tenantModel = tenant();
        if (! $tenantModel) {
            abort(404);
        }
        abort_unless($candidate->tenant_id === $tenantModel->id, 404);
        abort_unless($application->tenant_id === $tenantModel->id, 404);
        abort_unless($application->candidate_id === $candidate->id, 404);
    }

    private function assertDocument(Application $application, PreOnboardingDocument $document): void
    {
        abort_unless($document->application_id === $application->id, 404);
    }
}
