<?php

namespace App\Mail;

use App\Models\Application;
use App\Models\Candidate;
use App\Models\JobOpening;
use App\Models\Tenant;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewApplication extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Tenant $tenant,
        public JobOpening $job,
        public Candidate $candidate,
        public Application $application
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Application for ' . $this->job->title,
            from: $this->getFromAddress(),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.new-application',
            with: [
                'tenant' => $this->tenant,
                'job' => $this->job,
                'candidate' => $this->candidate,
                'application' => $this->application,
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
