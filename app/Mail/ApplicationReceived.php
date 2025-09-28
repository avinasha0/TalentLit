<?php

namespace App\Mail;

use App\Models\Application;
use App\Models\Candidate;
use App\Models\JobOpening;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ApplicationReceived extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Candidate $candidate,
        public JobOpening $job,
        public Application $application
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Application Received - '.$this->job->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.application-received',
            with: [
                'candidate' => $this->candidate,
                'job' => $this->job,
                'application' => $this->application,
                'tenant' => tenant(),
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
