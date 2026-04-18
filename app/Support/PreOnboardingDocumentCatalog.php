<?php

namespace App\Support;

use App\Models\Application;

final class PreOnboardingDocumentCatalog
{
    /**
     * @return list<array{key: string, title: string, required: bool}>
     */
    public static function definitions(): array
    {
        return [
            ['key' => 'aadhaar_identity', 'title' => 'Aadhaar / Identity Proof', 'required' => true],
            ['key' => 'pan_card', 'title' => 'PAN Card', 'required' => true],
            ['key' => 'address_proof', 'title' => 'Address Proof', 'required' => true],
            ['key' => 'educational_certificates', 'title' => 'Educational Certificates', 'required' => true],
            ['key' => 'experience_documents', 'title' => 'Experience Documents (if applicable)', 'required' => false],
            ['key' => 'payslips', 'title' => 'Payslips', 'required' => true],
            ['key' => 'bank_details', 'title' => 'Bank Details', 'required' => true],
            ['key' => 'photograph', 'title' => 'Photograph', 'required' => true],
            ['key' => 'signed_offer_letter', 'title' => 'Signed Offer Letter', 'required' => true],
            ['key' => 'additional_documents', 'title' => 'Additional documents (optional)', 'required' => false],
        ];
    }

    /**
     * Show checklist UI (Offer Accepted or Pre-Onboarding).
     */
    public static function eligibleForUi(Application $application): bool
    {
        return self::eligibleForUpload($application);
    }

    /**
     * Offer accepted: explicit acceptance or moved to pre-onboarding.
     */
    public static function eligibleForUpload(Application $application): bool
    {
        $st = strtolower((string) $application->status);
        if ($st === 'pre_onboarding') {
            return true;
        }
        if ($st === 'offered' && strtolower((string) ($application->offer_response ?? '')) === 'accepted') {
            return true;
        }

        return false;
    }

    public static function eligibleForChecklistSeed(Application $application): bool
    {
        return self::eligibleForUpload($application);
    }

    public static function isCompleteStatus(string $status): bool
    {
        return in_array(strtolower($status), ['uploaded', 'verified'], true);
    }
}
