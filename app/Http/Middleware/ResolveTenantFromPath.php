<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use App\Support\Tenancy;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ResolveTenantFromPath
{
    public function handle(Request $request, Closure $next)
    {
        $slug = $request->route('tenant'); // <-- first segment param

        // Always log for debugging
        \Log::info('ResolveTenantFromPath.Invoked', [
            'path' => $request->path(),
            'slug' => $slug,
            'method' => $request->method(),
            'host' => $request->getHost(),
            'full_url' => $request->fullUrl(),
            'route_name' => optional($request->route())->getName(),
            'route_uri' => optional($request->route())->uri(),
            'has_file' => $request->hasFile('file'),
        ]);

        $tenant = Tenant::query()->where('slug', $slug)->first();

        if (! $tenant) {
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
            
            \Log::warning('ResolveTenantFromPath.TenantNotFound', [
                'slug' => $slug, 
                'path' => $request->path(),
                'host' => $request->getHost(),
                'available_tenants' => $availableTenants,
            ]);
            
            // For API, return JSON 404; for web, normal 404.
            if ($request->expectsJson()) {
                abort(response()->json(['message' => 'Tenant not found', 'available_tenants' => $availableTenants], 404));
            }
            throw new NotFoundHttpException("Tenant not found for slug: {$slug}. Available tenants: " . json_encode($availableTenants));
        }
        
        \Log::info('ResolveTenantFromPath.Success', [
            'slug' => $slug,
            'tenant_id' => $tenant->id,
            'tenant_slug' => $tenant->slug,
        ]);

        Tenancy::set($tenant);
        View::share('tenant', $tenant); // Make tenant available in all views
        
        // Set current tenant ID for permission context
        app()->instance('currentTenantId', $tenant->id);

        try {
            return $next($request);
        } finally {
            // IMPORTANT: don't leak tenant across requests/tests
            Tenancy::forget();
        }
    }
}
