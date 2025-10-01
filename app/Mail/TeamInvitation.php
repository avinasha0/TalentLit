<?php

namespace App\Mail;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TeamInvitation extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Tenant $tenant,
        public User $user,
        public string $roleName,
        public ?string $invitationToken = null
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'You\'ve been invited to join ' . $this->tenant->name,
            from: new Address($this->getFromAddress(), $this->getFromName()),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.team-invitation',
            with: [
                'tenant' => $this->tenant,
                'user' => $this->user,
                'roleName' => $this->roleName,
                'loginUrl' => $this->getLoginUrl(),
                'invitationToken' => $this->invitationToken,
                'invitationUrl' => $this->getInvitationUrl(),
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }

    private function getFromAddress(): string
    {
        // Use a default from address if tenant domain is not available
        $fromAddress = config('mail.from.address', 'noreply@talentlit.com');
        
        // If tenant has a domain, use it, but ensure it's properly formatted
        if ($this->tenant->domain && filter_var($this->tenant->domain, FILTER_VALIDATE_DOMAIN)) {
            $fromAddress = "noreply@{$this->tenant->domain}";
        }
        
        return $fromAddress;
    }

    private function getFromName(): string
    {
        return config('mail.from.name', $this->tenant->name . ' Team');
    }

    private function getLoginUrl(): string
    {
        return url('/login');
    }

    private function getInvitationUrl(): string
    {
        if ($this->invitationToken) {
            return url("/invitation/{$this->invitationToken}");
        }
        return $this->getLoginUrl();
    }
}
