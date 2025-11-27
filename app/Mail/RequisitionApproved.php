<?php

namespace App\Mail;

use App\Models\Requisition;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RequisitionApproved extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Requisition $requisition,
        public ?User $approver = null
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Requisition Approved: [{$this->requisition->id}] - {$this->requisition->job_title}",
            from: $this->getFromAddress(),
        );
    }

    public function content(): Content
    {
        $tenant = $this->requisition->tenant;
        $viewLink = url("/{$tenant->slug}/requisitions/{$this->requisition->id}");
        
        return new Content(
            view: 'emails.requisition-approved',
            with: [
                'requisition' => $this->requisition,
                'tenant' => $tenant,
                'approver' => $this->approver,
                'viewLink' => $viewLink,
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
