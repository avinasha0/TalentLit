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

if (! function_exists('isSubdomainAccess')) {
    /**
     * Check if current request is via subdomain
     */
    function isSubdomainAccess()
    {
        $request = request();
        $host = $request->getHost();
        $appUrl = config('app.url');
        $appDomain = parse_url($appUrl, PHP_URL_HOST) ?? $host;
        
        // Remove port if present
        $host = explode(':', $host)[0];
        $appDomain = explode(':', $appDomain)[0];
        
        // If host is same as app domain, not subdomain
        if ($host === $appDomain) {
            return false;
        }
        
        // Check if host ends with app domain (subdomain)
        return str_ends_with($host, '.' . $appDomain);
    }
}

if (! function_exists('tenantRoute')) {
    /**
     * Generate tenant route - uses subdomain routes when on subdomain, otherwise path-based routes
     */
    function tenantRoute($name, $parameters = [], $absolute = true)
    {
        // If on subdomain, use subdomain route names
        if (isSubdomainAccess()) {
            // Convert tenant.* route names to subdomain.*
            $subdomainName = str_replace('tenant.', 'subdomain.', $name);
            
            // Remove tenant slug from parameters if present
            if (is_array($parameters)) {
                unset($parameters['tenant']);
            } elseif (is_string($parameters) || is_numeric($parameters)) {
                // If single parameter is tenant slug, remove it
                $parameters = [];
            }
            
            // Try to generate subdomain route
            try {
                return route($subdomainName, $parameters, $absolute);
            } catch (\Exception $e) {
                // Fallback to original route if subdomain route doesn't exist
                return route($name, $parameters, $absolute);
            }
        }
        
        // Use path-based routing
        return route($name, $parameters, $absolute);
    }
}
