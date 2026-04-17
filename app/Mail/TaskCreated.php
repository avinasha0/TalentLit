<?php

namespace App\Mail;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TaskCreated extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Task $task
    ) {}

    public function envelope(): Envelope
    {
        $subject = "Task: {$this->task->title}";
        
        return new Envelope(
            subject: $subject,
            from: $this->getFromAddress(),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.task-created',
            with: [
                'task' => $this->task,
                'tenant' => tenant(),
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }

    private function getFromAddress(): string
    {
        $tenant = tenant();
        $domain = $tenant->domain ?? config('mail.from.address');
        return "noreply@{$domain}";
    }
}

