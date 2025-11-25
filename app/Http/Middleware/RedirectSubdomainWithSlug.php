<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RedirectSubdomainWithSlug
{
    /**
     * Handle an incoming request.
     * Redirects subdomain URLs with tenant slug to clean subdomain URLs.
     * Example: leadformhub.talentlit.com/leadformhub/dashboard -> leadformhub.talentlit.com/dashboard
     */
    public function handle(Request $request, Closure $next)
    {
        $host = $request->getHost();
        $appUrl = config('app.url');
        $appDomain = parse_url($appUrl, PHP_URL_HOST) ?? $host;
        
        // Remove port if present
        $host = explode(':', $host)[0];
        $appDomain = explode(':', $appDomain)[0];
        
        // Check if we're on a subdomain
        $isSubdomain = $host !== $appDomain && str_ends_with($host, '.' . $appDomain);
        
        if ($isSubdomain) {
            $subdomain = str_replace('.' . $appDomain, '', $host);
            $path = trim($request->path(), '/');
            
            // Check if path starts with the subdomain name (tenant slug)
            if ($path && (str_starts_with($path, $subdomain . '/') || $path === $subdomain)) {
                // Remove the subdomain/tenant slug from the path
                if ($path === $subdomain) {
                    $cleanPath = '';
                } else {
                    $cleanPath = substr($path, strlen($subdomain) + 1);
                }
                
                // Build clean URL
                $scheme = $request->getScheme();
                $port = $request->getPort();
                $cleanUrl = $scheme . '://' . $host;
                if ($port && !in_array($port, [80, 443])) {
                    $cleanUrl .= ':' . $port;
                }
                if ($cleanPath) {
                    $cleanUrl .= '/' . $cleanPath;
                }
                
                // Add query string if present
                if ($request->getQueryString()) {
                    $cleanUrl .= '?' . $request->getQueryString();
                }
                
                // Redirect to clean URL
                return redirect($cleanUrl, 301);
            }
        }
        
        return $next($request);
    }
}

