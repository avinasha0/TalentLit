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

if (! function_exists('hasCustomPermission')) {
    /**
     * Check if current user has a custom permission for the current tenant
     * Uses PermissionService for centralized permission checking
     */
    function hasCustomPermission(string $permission, $tenant = null): bool
    {
        $user = auth()->user();
        if (!$user) {
            return false;
        }

        $tenant = $tenant ?? tenant();
        if (!$tenant) {
            return false;
        }

        $tenantId = is_object($tenant) ? $tenant->id : $tenant;
        
        // Use PermissionService (don't log UI checks to avoid spam, but log important ones)
        return app(\App\Services\PermissionService::class)->userHasPermission($user->id, $tenantId, $permission, false);
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
            // Get current tenant and subdomain for comparison
            $tenant = tenant();
            $request = request();
            $host = explode(':', $request->getHost())[0];
            $appDomain = explode(':', parse_url(config('app.url'), PHP_URL_HOST) ?? $host)[0];
            $subdomain = str_replace('.' . $appDomain, '', $host);
            
            // Determine subdomain route name
            $subdomainName = $name;
            if (str_starts_with($name, 'tenant.')) {
                // Convert tenant.* route names to subdomain.*
                $subdomainName = str_replace('tenant.', 'subdomain.', $name);
            } elseif (!str_starts_with($name, 'subdomain.')) {
                // For routes like careers.index, subscription.show, etc., try subdomain version
                // Check if subdomain route exists by trying to get route list
                $subdomainName = 'subdomain.' . $name;
            }
            
            // Remove tenant slug from parameters if present
            // On subdomain, tenant is identified by subdomain, so tenant slug parameter is not needed
            if (is_array($parameters)) {
                // Remove 'tenant' key if present
                unset($parameters['tenant']);
                // If first element is a string and not a UUID/numeric, it's likely the tenant slug - remove it
                if (isset($parameters[0]) && is_string($parameters[0])) {
                    $firstParam = $parameters[0];
                    // Check if it's a UUID (keep it) or numeric (keep it) or tenant slug (remove it)
                    $isUuid = preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $firstParam);
                    $isNumeric = is_numeric($firstParam);
                    // If it's not UUID and not numeric, it's likely tenant slug - remove it
                    if (!$isUuid && !$isNumeric) {
                        array_shift($parameters);
                    }
                }
                // Re-index array if needed
                if (array_values($parameters) !== $parameters) {
                    $parameters = array_values($parameters);
                }
            } elseif (is_string($parameters)) {
                // If single parameter is a string, check if it's UUID (keep) or slug (remove)
                $isUuid = preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $parameters);
                if (!$isUuid && !is_numeric($parameters)) {
                    // Not a UUID and not numeric, likely tenant slug - remove it
                    $parameters = [];
                } else {
                    // UUID or numeric - keep it as array for route generation
                    $parameters = [$parameters];
                }
            } elseif (is_numeric($parameters)) {
                // Keep numeric parameters (like job IDs)
                $parameters = [$parameters];
            }
            
            // Generate subdomain route - domain-bound routes need the subdomain parameter
            // Ensure parameters is an array and add subdomain parameter from current request
            if (!is_array($parameters)) {
                $parameters = $parameters ? [$parameters] : [];
            }
            $parameters['subdomain'] = $subdomain;
            
            // Generate subdomain route - don't fallback when on subdomain
            // If subdomain route doesn't exist, let it throw (routes should have subdomain equivalents)
            return route($subdomainName, $parameters, $absolute);
        }
        
        // Use path-based routing
        return route($name, $parameters, $absolute);
    }
}
