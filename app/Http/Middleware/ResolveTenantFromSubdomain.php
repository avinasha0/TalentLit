<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use App\Support\Tenancy;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ResolveTenantFromSubdomain
{
    public function handle(Request $request, Closure $next)
    {
        $host = $request->getHost();
        $appUrl = config('app.url');
        $appDomain = parse_url($appUrl, PHP_URL_HOST) ?? $host;
        
        // Extract subdomain from host
        $subdomain = $this->extractSubdomain($host, $appDomain);
        
        // If no subdomain found, pass through (let path-based middleware handle it)
        if (!$subdomain) {
            return $next($request);
        }
        
        // Find tenant by subdomain
        $tenant = Tenant::query()
            ->where('subdomain', $subdomain)
            ->where('subdomain_enabled', true)
            ->first();
        
        if (!$tenant) {
            // Subdomain not found or not enabled - return 404
            throw new NotFoundHttpException('Tenant not found.');
        }
        
        // Verify tenant has Enterprise plan
        $subscription = $tenant->activeSubscription;
        if (!$subscription || !$subscription->plan || $subscription->plan->slug !== 'enterprise') {
            throw new NotFoundHttpException('Subdomain access is only available for Enterprise plans.');
        }
        
        // Set tenant context (same as ResolveTenantFromPath)
        Tenancy::set($tenant);
        View::share('tenant', $tenant);
        app()->instance('currentTenantId', $tenant->id);
        
        try {
            return $next($request);
        } finally {
            Tenancy::forget();
        }
    }
    
    private function extractSubdomain(string $host, string $appDomain): ?string
    {
        // Remove port if present
        $host = explode(':', $host)[0];
        $appDomain = explode(':', $appDomain)[0];
        
        // If host is same as app domain, no subdomain
        if ($host === $appDomain) {
            return null;
        }
        
        // Check if host ends with app domain
        if (str_ends_with($host, '.' . $appDomain)) {
            $subdomain = str_replace('.' . $appDomain, '', $host);
            return $subdomain ?: null;
        }
        
        // For localhost development
        if ($appDomain === 'localhost' || str_contains($appDomain, '127.0.0.1')) {
            $parts = explode('.', $host);
            if (count($parts) > 1 && $parts[0] !== 'www') {
                return $parts[0];
            }
        }
        
        return null;
    }
}
