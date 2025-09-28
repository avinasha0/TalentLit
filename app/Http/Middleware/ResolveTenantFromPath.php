<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResolveTenantFromPath
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $tenantSlug = $request->segment(1);

        if (! $tenantSlug) {
            return $next($request);
        }

        $tenant = Tenant::where('slug', $tenantSlug)->first();

        if (! $tenant) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Tenant not found'], 404);
            }

            abort(404);
        }

        $request->attributes->set('tenant', $tenant);
        app()->instance('currentTenant', $tenant);

        return $next($request);
    }
}
