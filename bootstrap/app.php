<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'tenant' => \App\Http\Middleware\ResolveTenantFromPath::class,
            'subdomain.tenant' => \App\Http\Middleware\ResolveTenantFromSubdomain::class,
            'subdomain.redirect' => \App\Http\Middleware\RedirectSubdomainWithSlug::class,
            'capture.tenant' => \App\Http\Middleware\CaptureLastTenant::class,
            'custom.permission' => \App\Http\Middleware\CustomPermissionMiddleware::class,
            'subscription.limit' => \App\Http\Middleware\CheckSubscriptionLimits::class,
            'log.route' => \App\Http\Middleware\LogRouteMatching::class,
        ]);
        
        // Add route logging middleware early in the stack
        $middleware->web(append: [
            \App\Http\Middleware\LogRouteMatching::class,
        ]);
        
        // Disable problematic middleware that can cause 500 errors
        $middleware->web(remove: [
            \Illuminate\Http\Middleware\ValidatePathEncoding::class,
        ]);
        
        $middleware->api(remove: [
            \Illuminate\Http\Middleware\ValidatePathEncoding::class,
        ]);
        
        // Also remove from global middleware
        $middleware->removeFromGroup('web', \Illuminate\Http\Middleware\ValidatePathEncoding::class);
        $middleware->removeFromGroup('api', \Illuminate\Http\Middleware\ValidatePathEncoding::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Handle 419 CSRF token expired errors gracefully
        $exceptions->render(function (\Illuminate\Session\TokenMismatchException $e, \Illuminate\Http\Request $request) {
            // For AJAX/JSON requests, return a JSON response
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'message' => 'Your session has expired. Please refresh the page and try again.',
                    'error' => 'Session expired',
                    'code' => 419,
                    'redirect' => route('login')
                ], 419);
            }
            
            // For regular requests, Laravel will automatically use resources/views/errors/419.blade.php
            // But we can customize the response if needed
            return response()->view('errors.419', [], 419);
        });
    })->create();
