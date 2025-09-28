<?php

use App\Support\Tenancy;

if (! function_exists('tenant')) {
    function tenant()
    {
        return Tenancy::get();
    }
}
if (! function_exists('tenant_id')) {
    function tenant_id()
    {
        return Tenancy::id();
    }
}
