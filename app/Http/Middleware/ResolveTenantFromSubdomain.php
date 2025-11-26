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
        
        // Always log for debugging
        \Log::info('ResolveTenantFromSubdomain.Invoked', [
            'host' => $host,
            'app_url' => $appUrl,
            'app_domain' => $appDomain,
            'extracted_subdomain' => $subdomain,
            'path' => $request->path(),
            'method' => $request->method(),
            'full_url' => $request->fullUrl(),
        ]);
        
        // If no subdomain found, pass through (let path-based middleware handle it)
        if (!$subdomain) {
            return $next($request);
        }
        
        // Find tenant by subdomain
        $tenant = Tenant::query()
            ->where('subdomain', $subdomain)
            ->where('subdomain_enabled', true)
            ->first();
        
        $matchMethod = 'subdomain_field';
        
        // In local development, also try matching by slug if subdomain not configured
        if (!$tenant && app()->environment('local', 'testing')) {
            \Log::info('ResolveTenantFromSubdomain: Trying slug match in local dev', [
                'subdomain' => $subdomain,
            ]);
            
            $tenant = Tenant::query()
                ->where('slug', $subdomain)
                ->first();
            
            if ($tenant) {
                $matchMethod = 'slug';
                \Log::info('ResolveTenantFromSubdomain.Matched', [
                    'match_method' => $matchMethod,
                    'subdomain' => $subdomain,
                    'tenant_slug' => $tenant->slug,
                    'tenant_id' => $tenant->id,
                    'tenant_subdomain' => $tenant->subdomain,
                    'subdomain_enabled' => $tenant->subdomain_enabled,
                ]);
            }
        }
        
        if (!$tenant) {
            // Log available tenants for debugging
            $availableTenants = Tenant::query()
                ->select('id', 'slug', 'subdomain', 'subdomain_enabled')
                ->get()
                ->map(fn($t) => [
                    'slug' => $t->slug,
                    'subdomain' => $t->subdomain,
                    'subdomain_enabled' => $t->subdomain_enabled,
                ])
                ->toArray();
            
            \Log::warning('ResolveTenantFromSubdomain.TenantNotFound', [
                'subdomain' => $subdomain,
                'host' => $host,
                'app_domain' => $appDomain,
                'environment' => app()->environment(),
                'available_tenants' => $availableTenants,
                'path' => $request->path(),
            ]);
            
            throw new NotFoundHttpException("Tenant not found for subdomain: {$subdomain}. Available tenants: " . json_encode($availableTenants));
        }
        
        \Log::info('ResolveTenantFromSubdomain.Success', [
            'match_method' => $matchMethod,
            'subdomain' => $subdomain,
            'tenant_id' => $tenant->id,
            'tenant_slug' => $tenant->slug,
        ]);
        
        // Verify tenant has Enterprise plan (skip in local development)
        if (!app()->environment('local', 'testing')) {
            $subscription = $tenant->activeSubscription;
            if (!$subscription || !$subscription->plan || $subscription->plan->slug !== 'enterprise') {
                throw new NotFoundHttpException('Subdomain access is only available for Enterprise plans.');
            }
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
        
        \Log::debug('ResolveTenantFromSubdomain.ExtractSubdomain', [
            'host' => $host,
            'app_domain' => $appDomain,
        ]);
        
        // If host is same as app domain, no subdomain
        if ($host === $appDomain) {
            \Log::debug('ResolveTenantFromSubdomain: Host matches app domain, no subdomain', [
                'host' => $host,
            ]);
            return null;
        }
        
        // Check if host ends with app domain
        if (str_ends_with($host, '.' . $appDomain)) {
            $subdomain = str_replace('.' . $appDomain, '', $host);
            \Log::debug('ResolveTenantFromSubdomain: Extracted subdomain from host', [
                'subdomain' => $subdomain,
                'host' => $host,
            ]);
            return $subdomain ?: null;
        }
        
        // For localhost development - handle both localhost and 127.0.0.1
        $isLocalDomain = in_array($appDomain, ['localhost', '127.0.0.1']) || 
                        str_contains($appDomain, '127.0.0.1') ||
                        str_contains($appDomain, 'localhost');
        
        if ($isLocalDomain) {
            $parts = explode('.', $host);
            if (count($parts) > 1 && $parts[0] !== 'www' && $parts[0] !== '127') {
                $subdomain = $parts[0];
                \Log::debug('ResolveTenantFromSubdomain: Extracted subdomain for local dev', [
                    'subdomain' => $subdomain,
                    'host' => $host,
                    'parts' => $parts,
                ]);
                return $subdomain;
            }
        }
        
        \Log::debug('ResolveTenantFromSubdomain: No subdomain extracted', [
            'host' => $host,
            'app_domain' => $appDomain,
        ]);
        
        return null;
    }
}
