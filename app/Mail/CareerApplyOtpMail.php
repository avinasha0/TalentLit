<?php

namespace App\Mail;

use App\Models\JobOpening;
use App\Models\Tenant;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CareerApplyOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Tenant $tenant,
        public JobOpening $job,
        public string $email,
        public string $otp,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your application verification code — '.$this->tenant->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.career-apply-otp',
            with: [
                'tenant' => $this->tenant,
                'job' => $this->job,
                'email' => $this->email,
                'otp' => $this->otp,
                'expiresMinutes' => \App\Models\CareerApplyEmailOtp::OTP_TTL_MINUTES,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
