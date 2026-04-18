<?php

namespace App\Mail;

use App\Models\Application;
use App\Models\Tenant;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PreOnboardingDocumentsRequestedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Tenant $tenant,
        public Application $application,
    ) {
        $this->application->loadMissing(['candidate', 'jobOpening']);
    }

    public function envelope(): Envelope
    {
        $job = $this->application->jobOpening?->title ?? 'your role';

        return new Envelope(
            subject: 'Documents requested — '.$this->tenant->name.' — '.$job,
        );
    }

    public function content(): Content
    {
        $portalUrl = $this->tenant->usesEnterpriseSubdomain()
            ? route('subdomain.applicant.dashboard')
            : route('tenant.applicant.dashboard', ['tenant' => $this->tenant->slug]);

        return new Content(
            view: 'emails.pre-onboarding.documents-requested',
            with: [
                'portalUrl' => $portalUrl,
            ],
        );
    }
}
