<?php

namespace App\Services;

use App\Mail\JobOfferNotification;
use App\Models\Application;
use App\Models\Tenant;
use Illuminate\Support\Facades\Mail;

class JobOfferNotificationService
{
    public function sendOfferEmail(Application $application): void
    {
        $application->loadMissing(['candidate', 'jobOpening']);

        if (config('mail.default') === null) {
            return;
        }

        $email = $application->candidate?->primary_email;
        if (! $email) {
            return;
        }

        $application->forceFill([
            'offer_response' => null,
            'offer_responded_at' => null,
            'offer_discussion_message' => null,
        ])->save();

        $tenant = Tenant::find($application->tenant_id);
        if (! $tenant) {
            return;
        }

        try {
            Mail::to($email)->queue(new JobOfferNotification($tenant, $application));
        } catch (\Throwable $e) {
            \Log::warning('Failed to queue job offer notification', [
                'application_id' => $application->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
