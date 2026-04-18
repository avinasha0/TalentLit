<?php

namespace App\Mail;

use App\Models\Application;
use App\Models\PreOnboardingDocument;
use App\Models\Tenant;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PreOnboardingDocumentUploadedToHrMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Tenant $tenant,
        public Application $application,
        public PreOnboardingDocument $document,
    ) {
        $this->application->loadMissing(['candidate', 'jobOpening']);
    }

    public function envelope(): Envelope
    {
        $candidateName = $this->application->candidate?->full_name ?? 'Candidate';

        return new Envelope(
            subject: 'Pre-onboarding document uploaded — '.$candidateName.' — '.$this->document->title,
        );
    }

    public function content(): Content
    {
        $candidateProfileUrl = $this->tenant->usesEnterpriseSubdomain()
            ? route('subdomain.candidates.show', ['candidate' => $this->application->candidate_id])
            : route('tenant.candidates.show', ['tenant' => $this->tenant->slug, 'candidate' => $this->application->candidate_id]);

        return new Content(
            view: 'emails.pre-onboarding.document-uploaded-hr',
            with: [
                'candidateProfileUrl' => $candidateProfileUrl,
            ],
        );
    }
}
