<?php

namespace App\Mail;

use App\Models\Tenant;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ApplicantPortalCredentials extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Tenant $tenant,
        public string $loginEmail,
        public string $plainPassword,
        public bool $isReturningApplicant = false,
    ) {}

    public function envelope(): Envelope
    {
        $subject = $this->isReturningApplicant
            ? 'New applicant portal password — '.$this->tenant->name
            : 'Your '.$this->tenant->name.' applicant portal login';

        return new Envelope(
            subject: $subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.applicant-portal-credentials',
            with: [
                'tenant' => $this->tenant,
                'loginEmail' => $this->loginEmail,
                'plainPassword' => $this->plainPassword,
                'loginUrl' => $this->tenant->getCandidateLoginUrl(),
                'portalUrl' => $this->tenant->getApplicantPortalUrl(),
                'isReturningApplicant' => $this->isReturningApplicant,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
