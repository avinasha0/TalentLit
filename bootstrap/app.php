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
        //
    })->create();
