<?php

use App\Models\Tenant;

if (! function_exists('currentTenant')) {
    /**
     * Get the current tenant from the request.
     */
    function currentTenant(): ?Tenant
    {
        return app('currentTenant');
    }
}
