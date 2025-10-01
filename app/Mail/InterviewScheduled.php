<?php

namespace App\Mail;

use App\Models\Interview;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InterviewScheduled extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public Interview $interview
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Interview Scheduled - ' . ($this->interview->job ? $this->interview->job->title : 'General Interview'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.interview-scheduled',
            with: [
                'interview' => $this->interview,
                'candidate' => $this->interview->candidate,
                'job' => $this->interview->job,
                'panelists' => $this->interview->panelists,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}