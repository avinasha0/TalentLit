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

        // Debug logging to trace 404s for tenant routes
        if (config('app.debug')) {
            \Log::info('ResolveTenantFromPath invoked', [
                'path' => $request->path(),
                'slug' => $slug,
                'route_name' => optional($request->route())->getName(),
                'route_uri' => optional($request->route())->uri(),
            ]);
        }

        $tenant = Tenant::query()->where('slug', $slug)->first();

        if (! $tenant) {
            if (config('app.debug')) {
                \Log::warning('Tenant not found for slug', ['slug' => $slug, 'path' => $request->path()]);
            }
            // For API, return JSON 404; for web, normal 404.
            if ($request->expectsJson()) {
                abort(response()->json(['message' => 'Tenant not found'], 404));
            }
            throw new NotFoundHttpException('Tenant not found.');
        }

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
