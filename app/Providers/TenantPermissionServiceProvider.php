<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Spatie\Permission\PermissionRegistrar;
use Spatie\Permission\Contracts\Permission as PermissionContract;
use Spatie\Permission\Contracts\Role as RoleContract;

class TenantPermissionServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(PermissionContract::class, \Spatie\Permission\Models\Permission::class);
        $this->app->bind(RoleContract::class, \App\Models\TenantRole::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Set the current tenant context for permission checks
        $this->app->make(PermissionRegistrar::class)->setPermissionsTeamId(
            function () {
                return app()->bound('currentTenantId') ? app('currentTenantId') : null;
            }
        );
    }
}