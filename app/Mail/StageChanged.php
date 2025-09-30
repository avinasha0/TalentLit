<?php

namespace App\Mail;

use App\Models\Application;
use App\Models\Candidate;
use App\Models\JobOpening;
use App\Models\JobStage;
use App\Models\Tenant;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StageChanged extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Tenant $tenant,
        public JobOpening $job,
        public Candidate $candidate,
        public Application $application,
        public JobStage $stage,
        public ?string $message = null
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Application Update - ' . $this->job->title,
            from: $this->getFromAddress(),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.stage-changed',
            with: [
                'tenant' => $this->tenant,
                'job' => $this->job,
                'candidate' => $this->candidate,
                'application' => $this->application,
                'stage' => $this->stage,
                'message' => $this->message,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }

    private function getFromAddress(): string
    {
        $domain = $this->tenant->domain ?? config('mail.from.address');
        return "noreply@{$domain}";
    }
}
