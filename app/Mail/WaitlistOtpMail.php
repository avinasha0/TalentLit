<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WaitlistOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public $otp;
    public $email;

    /**
     * Create a new message instance.
     */
    public function __construct(string $otp, string $email)
    {
        $this->otp = $otp;
        $this->email = $email;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Verify Your Email - TalentLit Waitlist')
            ->view('emails.waitlist-otp')
            ->with([
                'otp' => $this->otp,
                'email' => $this->email,
            ]);
    }
}