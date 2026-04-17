<?php

namespace App\Mail;

use App\Models\Requisition;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RequisitionRejected extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Requisition $requisition,
        public string $comments
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Requisition [{$this->requisition->id}] - Rejected",
            from: $this->getFromAddress(),
        );
    }

    public function content(): Content
    {
        $tenant = $this->requisition->tenant;
        $editLink = url("/{$tenant->slug}/requisitions/{$this->requisition->id}/edit");
        
        return new Content(
            view: 'emails.requisition-rejected',
            with: [
                'requisition' => $this->requisition,
                'tenant' => $tenant,
                'comments' => $this->comments,
                'editLink' => $editLink,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }

    private function getFromAddress(): string
    {
        $tenant = $this->requisition->tenant;
        $domain = $tenant->domain ?? config('mail.from.address');
        return "noreply@{$domain}";
    }
}
