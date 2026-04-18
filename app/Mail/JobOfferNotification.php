<?php

namespace App\Mail;

use App\Models\Application;
use App\Models\Tenant;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class JobOfferNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Tenant $tenant,
        public Application $application,
    ) {
        $this->application->loadMissing(['candidate', 'jobOpening']);
    }

    public function envelope(): Envelope
    {
        $jobTitle = $this->application->jobOpening?->title ?? 'your application';

        return new Envelope(
            subject: 'Job offer — '.$this->tenant->name.' — '.$jobTitle,
        );
    }

    public function content(): Content
    {
        $expiration = now()->addDays(60);
        $tenant = $this->tenant;
        $application = $this->application;

        $urls = $tenant->withOfferSigningRoot(function () use ($tenant, $application, $expiration) {
            if ($tenant->usesEnterpriseSubdomain()) {
                return [
                    'acceptUrl' => URL::temporarySignedRoute('subdomain.offers.accept', $expiration, [
                        'application' => $application->id,
                    ]),
                    'rejectUrl' => URL::temporarySignedRoute('subdomain.offers.reject', $expiration, [
                        'application' => $application->id,
                    ]),
                    'discussionUrl' => URL::temporarySignedRoute('subdomain.offers.discussion.form', $expiration, [
                        'application' => $application->id,
                    ]),
                ];
            }

            return [
                'acceptUrl' => URL::temporarySignedRoute('tenant.offers.accept', $expiration, [
                    'tenant' => $tenant->slug,
                    'application' => $application->id,
                ]),
                'rejectUrl' => URL::temporarySignedRoute('tenant.offers.reject', $expiration, [
                    'tenant' => $tenant->slug,
                    'application' => $application->id,
                ]),
                'discussionUrl' => URL::temporarySignedRoute('tenant.offers.discussion.form', $expiration, [
                    'tenant' => $tenant->slug,
                    'application' => $application->id,
                ]),
            ];
        });

        return new Content(
            view: 'emails.job-offer',
            with: array_merge($urls, [
                'tenant' => $tenant,
                'application' => $application,
                'portalUrl' => $tenant->getApplicantPortalUrl(),
            ]),
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
