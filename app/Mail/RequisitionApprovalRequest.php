<?php

namespace App\Mail;

use App\Models\Requisition;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RequisitionApprovalRequest extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Requisition $requisition,
        public bool $isFinanceStage = false,
    ) {}

    public function envelope(): Envelope
    {
        $prefix = $this->isFinanceStage ? 'Finance approval required' : 'Approval request';

        return new Envelope(
            subject: "{$prefix}: [{$this->requisition->id}] - {$this->requisition->job_title}",
            from: $this->getFromAddress(),
        );
    }

    public function content(): Content
    {
        $tenant = $this->requisition->tenant;
        $approvalLink = url("/{$tenant->slug}/requisitions/{$this->requisition->id}/approval");
        
        return new Content(
            view: 'emails.requisition-approval-request',
            with: [
                'requisition' => $this->requisition,
                'tenant' => $tenant,
                'approvalLink' => $approvalLink,
                'isFinanceStage' => $this->isFinanceStage,
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
