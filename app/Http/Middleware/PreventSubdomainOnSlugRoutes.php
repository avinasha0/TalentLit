<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PreventSubdomainOnSlugRoutes
{
    /**
     * Prevent slug-based routes from matching on subdomain requests.
     * This ensures subdomain routes are matched first.
     */
    public function handle(Request $request, Closure $next)
    {
        $host = $request->getHost();
        $appUrl = config('app.url');
        $appDomain = parse_url($appUrl, PHP_URL_HOST) ?? 'localhost';
        
        // Remove port if present
        $host = explode(':', $host)[0];
        $appDomain = explode(':', $appDomain)[0];
        
        // Check if we're on a subdomain
        $isSubdomain = false;
        
        // Check if host ends with app domain (subdomain pattern)
        if ($host !== $appDomain && str_ends_with($host, '.' . $appDomain)) {
            $isSubdomain = true;
        }
        
        // For localhost development, check if host has dots and first part is not www
        if (!$isSubdomain && ($appDomain === 'localhost' || str_contains($appDomain, '127.0.0.1'))) {
            $parts = explode('.', $host);
            if (count($parts) > 1 && $parts[0] !== 'www' && $parts[0] !== '127') {
                $isSubdomain = true;
            }
        }
        
        // If we're on a subdomain, check if this is a slug-based route
        // Slug-based routes have {tenant} in the path, subdomain routes don't
        if ($isSubdomain) {
            $route = $request->route();
            if ($route) {
                $routeName = $route->getName();
                $routeUri = $route->uri();
                
                // If route name starts with 'tenant.' (slug-based) and not 'subdomain.'
                // This means a slug route matched on a subdomain - block it
                if ($routeName && str_starts_with($routeName, 'tenant.') && !str_starts_with($routeName, 'subdomain.')) {
                    \Log::warning('PreventSubdomainOnSlugRoutes: Blocked slug route on subdomain', [
                        'host' => $host,
                        'route_name' => $routeName,
                        'route_uri' => $routeUri,
                        'path' => $request->path(),
                    ]);
                    
                    throw new NotFoundHttpException('Route not found. Use subdomain routes for subdomain access.');
                }
            }
        }
        
        return $next($request);
    }
}

