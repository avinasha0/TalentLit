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

if (! function_exists('currentTenantId')) {
    function currentTenantId()
    {
        return app('currentTenantId');
    }
}

if (! function_exists('userRole')) {
    function userRole($user = null)
    {
        $user = $user ?? auth()->user();
        return $user ? $user->roles->first() : null;
    }
}

if (! function_exists('userRoleName')) {
    function userRoleName($user = null)
    {
        $role = userRole($user);
        return $role ? $role->name : 'No Role';
    }
}

if (! function_exists('hasPermission')) {
    function hasPermission($permission, $user = null)
    {
        $user = $user ?? auth()->user();
        return $user ? $user->can($permission) : false;
    }
}

// Currency helper functions
if (! function_exists('currency')) {
    function currency($amount, $currency = null)
    {
        return \App\Services\CurrencyService::formatAmount($amount, $currency);
    }
}

if (! function_exists('currencySymbol')) {
    function currencySymbol($currency = null)
    {
        return \App\Services\CurrencyService::getCurrencySymbol($currency);
    }
}

if (! function_exists('currencyCode')) {
    function currencyCode($currency = null)
    {
        return \App\Services\CurrencyService::getCurrencyCode($currency);
    }
}

if (! function_exists('currencyRange')) {
    function currencyRange($minAmount, $maxAmount, $currency = null)
    {
        return \App\Services\CurrencyService::formatAmountRange($minAmount, $maxAmount, $currency);
    }
}

if (! function_exists('currentCurrency')) {
    function currentCurrency()
    {
        return \App\Services\CurrencyService::getCurrentCurrency();
    }
}

if (! function_exists('subscriptionPrice')) {
    function subscriptionPrice($price, $planCurrency = 'USD', $displayCurrency = null)
    {
        return \App\Services\CurrencyService::formatSubscriptionPrice($price, $planCurrency, $displayCurrency);
    }
}
