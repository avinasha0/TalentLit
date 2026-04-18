<?php

namespace App\Actions\Candidates;

use App\Models\Application;
use App\Services\ApplicantPortalProvisioner\ApplicantPortalProvisioningResult;

readonly class ApplySubmissionResult
{
    public function __construct(
        public Application $application,
        public ApplicantPortalProvisioningResult $applicantPortal,
    ) {}
}
