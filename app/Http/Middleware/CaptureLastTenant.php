<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CaptureLastTenant
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Only capture tenant slug for guest users
        if (!auth()->check()) {
            $tenantSlug = $request->route('tenant');
            if ($tenantSlug) {
                session(['last_tenant_slug' => $tenantSlug]);
            }
        }

        return $next($request);
    }
}
