<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\PermissionService;

class CustomPermissionMiddleware
{
    protected $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }

    public function handle(Request $request, Closure $next, string $permission)
    {
        // Convert permission from 'view dashboard' to 'view_dashboard'
        $permission = str_replace(' ', '_', $permission);
        
        // Get current user
        $user = auth()->user();
        
        if (!$user) {
            abort(403, 'Unauthorized');
        }

        // Get tenant from route parameter, tenant() helper (for subdomain routes), app instance, or session
        $tenant = null;
        
        // First, try tenant() helper (subdomain routing - set by ResolveTenantFromSubdomain)
        // This should be the primary method for subdomain routes
        if (function_exists('tenant')) {
            $tenant = tenant();
        }
        
        // Second, try Tenancy support class directly (same as tenant() helper but more explicit)
        if (!$tenant && class_exists(\App\Support\Tenancy::class)) {
            $tenant = \App\Support\Tenancy::get();
        }
        
        // Third, try app instance (set by ResolveTenantFromSubdomain middleware)
        if (!$tenant && app()->bound('currentTenantId')) {
            $tenantId = app('currentTenantId');
            if ($tenantId) {
                $tenant = \App\Models\Tenant::find($tenantId);
            }
        }
        
        // Fourth, try resolving from subdomain directly (fallback for subdomain routes)
        if (!$tenant) {
            $host = $request->getHost();
            $appUrl = config('app.url');
            $appDomain = parse_url($appUrl, PHP_URL_HOST) ?? $host;
            $appDomain = explode(':', $appDomain)[0];
            $host = explode(':', $host)[0];
            
            // Check if we're on a subdomain
            if ($host !== $appDomain) {
                // Extract subdomain
                $subdomain = null;
                if (str_ends_with($host, '.' . $appDomain)) {
                    $subdomain = str_replace('.' . $appDomain, '', $host);
                } elseif (in_array($appDomain, ['localhost', '127.0.0.1']) || str_contains($appDomain, 'localhost')) {
                    $parts = explode('.', $host);
                    if (count($parts) > 1 && $parts[0] !== 'www' && $parts[0] !== '127') {
                        $subdomain = $parts[0];
                    }
                }
                
                if ($subdomain) {
                    $tenant = \App\Models\Tenant::where('subdomain', $subdomain)
                        ->where('subdomain_enabled', true)
                        ->first();
                }
            }
        }
        
        // Fifth, try route parameter (path-based routing)
        if (!$tenant && $request->route('tenant')) {
            $tenant = \App\Models\Tenant::where('slug', $request->route('tenant'))->first();
        }
        
        // Sixth, try session fallback
        if (!$tenant && session('current_tenant_id')) {
            $tenant = \App\Models\Tenant::find(session('current_tenant_id'));
        }
        
        if (!$tenant) {
            \Log::warning('CustomPermissionMiddleware.TenantNotFound', [
                'user_id' => $user->id,
                'permission' => $permission,
                'route' => $request->route()?->getName(),
                'host' => $request->getHost(),
                'path' => $request->path(),
                'tenant_helper' => function_exists('tenant') ? (tenant() ? 'exists' : 'null') : 'not_exists',
                'app_instance' => app()->bound('currentTenantId') ? app('currentTenantId') : 'not_bound',
                'session_tenant' => session('current_tenant_id'),
            ]);
            abort(403, 'Tenant not found');
        }

        // Check if user has the required permission for this tenant
        // PermissionService will log the check automatically
        $hasPermission = $this->permissionService->userHasPermission($user->id, $tenant->id, $permission, true);
        
        if (!$hasPermission) {
            // Log detailed information for debugging
            $userRole = $this->permissionService->getUserRole($user->id, $tenant->id);
            \Log::error('CustomPermissionMiddleware.PermissionDenied', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'tenant_id' => $tenant->id,
                'tenant_name' => $tenant->name,
                'tenant_slug' => $tenant->slug,
                'tenant_subdomain' => $tenant->subdomain,
                'permission' => $permission,
                'user_role' => $userRole,
                'route' => $request->route()?->getName(),
                'host' => $request->getHost(),
                'path' => $request->path(),
                'user_belongs_to_tenant' => $user->belongsToTenant($tenant->id),
            ]);
            
            abort(403, 'You don\'t have permission to perform this action. Contact an Owner.');
        }

        return $next($request);
    }
}
