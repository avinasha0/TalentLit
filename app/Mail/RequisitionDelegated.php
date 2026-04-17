<?php

namespace App\Mail;

use App\Models\Requisition;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RequisitionDelegated extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Requisition $requisition,
        public int $delegatorId
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Approval request: [{$this->requisition->id}] - {$this->requisition->job_title}",
            from: $this->getFromAddress(),
        );
    }

    public function content(): Content
    {
        $tenant = $this->requisition->tenant;
        $delegator = User::find($this->delegatorId);
        $approvalLink = url("/{$tenant->slug}/requisitions/{$this->requisition->id}/approval");
        
        return new Content(
            view: 'emails.requisition-delegated',
            with: [
                'requisition' => $this->requisition,
                'tenant' => $tenant,
                'delegator' => $delegator,
                'approvalLink' => $approvalLink,
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
