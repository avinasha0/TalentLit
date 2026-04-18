<?php

namespace App\Observers;

use App\Models\Requisition;
use App\Services\CreateDraftJobFromApprovedRequisition;

class RequisitionObserver
{
    public function __construct(
        private CreateDraftJobFromApprovedRequisition $createDraftJobFromApprovedRequisition
    ) {}

    public function saved(Requisition $requisition): void
    {
        if ($requisition->approval_status === 'Approved' && $requisition->status === 'Approved') {
            $this->createDraftJobFromApprovedRequisition->handle($requisition);
        }
    }
}
