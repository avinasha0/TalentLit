<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactMail extends Mailable
{
    use Queueable, SerializesModels;

    public $contactData;

    /**
     * Create a new message instance.
     */
    public function __construct($contactData)
    {
        $this->contactData = $contactData;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Contact Form Submission: ' . $this->contactData['subject'],
            replyTo: $this->contactData['email'],
        );
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $name = $this->contactData['name'] ?? '';
        $email = $this->contactData['email'] ?? '';
        $company = $this->contactData['company'] ?? 'Not provided';
        $phone = $this->contactData['phone'] ?? 'Not provided';
        $subject = $this->contactData['subject'] ?? '';
        $message = $this->contactData['message'] ?? '';
        
        $htmlContent = "
        <!DOCTYPE html>
        <html>
        <head>
            <title>New Contact Form Submission</title>
        </head>
        <body>
            <h1>New Contact Form Submission</h1>
            
            <p><strong>Name:</strong> " . htmlspecialchars($name) . "</p>
            <p><strong>Email:</strong> " . htmlspecialchars($email) . "</p>
            <p><strong>Company:</strong> " . htmlspecialchars($company) . "</p>
            <p><strong>Phone:</strong> " . htmlspecialchars($phone) . "</p>
            <p><strong>Subject:</strong> " . htmlspecialchars($subject) . "</p>
            <p><strong>Message:</strong></p>
            <p>" . nl2br(htmlspecialchars($message)) . "</p>
        </body>
        </html>";
        
        return $this->html($htmlContent);
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
