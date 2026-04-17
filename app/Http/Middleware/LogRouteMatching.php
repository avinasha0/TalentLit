<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogRouteMatching
{
    /**
     * Handle an incoming request and log route matching details.
     */
    public function handle(Request $request, Closure $next)
    {
        $startTime = microtime(true);
        
        $logData = [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'host' => $request->getHost(),
            'path' => $request->path(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'route_name' => null,
            'route_uri' => null,
            'route_domain' => null,
            'middleware' => [],
            'tenant_resolved' => false,
            'tenant_id' => null,
            'tenant_slug' => null,
            'is_subdomain' => false,
        ];
        
        try {
            $response = $next($request);
            
            // After route matching, capture route info
            $route = $request->route();
            if ($route) {
                $logData['route_name'] = $route->getName();
                $logData['route_uri'] = $route->uri();
                $logData['route_domain'] = $route->getDomain();
                $logData['middleware'] = $route->gatherMiddleware();
            }
            
            // Check if tenant is resolved
            $tenant = tenant();
            if ($tenant) {
                $logData['tenant_resolved'] = true;
                $logData['tenant_id'] = $tenant->id;
                $logData['tenant_slug'] = $tenant->slug;
                $logData['is_subdomain'] = str_starts_with($logData['route_name'] ?? '', 'subdomain.');
            }
            
            $logData['response_status'] = $response->getStatusCode();
            $logData['duration_ms'] = round((microtime(true) - $startTime) * 1000, 2);
            
            // Log with appropriate level
            if ($logData['response_status'] >= 400) {
                Log::warning('Route.Match', $logData);
            } else {
                Log::info('Route.Match', $logData);
            }
            
            return $response;
        } catch (\Exception $e) {
            $logData['error'] = $e->getMessage();
            $logData['error_class'] = get_class($e);
            $logData['duration_ms'] = round((microtime(true) - $startTime) * 1000, 2);
            
            Log::error('Route.Match.Error', $logData);
            
            throw $e;
        }
    }
}

