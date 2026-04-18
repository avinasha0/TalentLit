<?php

namespace App\Actions\Applications;

use App\Models\Application;
use App\Models\Tenant;
use App\Services\PreboardingAutomationService;
use Illuminate\Support\Facades\DB;

final class RecordOfferResponse
{
    /**
     * @return array{ok: bool, error?: string}
     */
    public function handle(Tenant $tenant, Application $application, string $action, ?string $discussionMessage = null): array
    {
        if ($application->tenant_id !== $tenant->id) {
            return ['ok' => false, 'error' => 'invalid'];
        }

        if (strtolower((string) $application->status) !== 'offered') {
            return ['ok' => false, 'error' => 'not_offered'];
        }

        if ($application->offer_responded_at) {
            return ['ok' => false, 'error' => 'already_responded'];
        }

        $action = strtolower($action);

        if ($action === 'accept') {
            DB::transaction(function () use ($tenant, $application) {
                $sync = app(SyncApplicationStatusToPipelineStage::class);
                $payload = array_merge([
                    'offer_response' => 'accepted',
                    'offer_responded_at' => now(),
                    'offer_discussion_message' => null,
                ], $sync->attributesForStatus($application, $tenant, 'pre_onboarding'));
                $application->forceFill($payload)->save();

                app(PreboardingAutomationService::class)->initializeFromAcceptedOffer($application->fresh());
            });

            return ['ok' => true];
        }

        if ($action === 'reject') {
            $application->forceFill([
                'offer_response' => 'rejected',
                'offer_responded_at' => now(),
                'offer_discussion_message' => null,
            ])->save();

            return ['ok' => true];
        }

        if ($action === 'discussion') {
            $message = trim((string) $discussionMessage);
            if ($message === '') {
                return ['ok' => false, 'error' => 'message_required'];
            }

            $application->forceFill([
                'offer_response' => 'discussion',
                'offer_responded_at' => now(),
                'offer_discussion_message' => $message,
            ])->save();

            return ['ok' => true];
        }

        return ['ok' => false, 'error' => 'invalid_action'];
    }
}
