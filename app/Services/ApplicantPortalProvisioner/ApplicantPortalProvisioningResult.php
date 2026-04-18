<?php

namespace App\Services\ApplicantPortalProvisioner;

readonly class ApplicantPortalProvisioningResult
{
    public function __construct(
        public bool $credentialsEmailQueued,
        public bool $canAccessApplicantPortal,
    ) {}
}
