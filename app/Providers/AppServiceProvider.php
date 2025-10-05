<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register the custom permission checker
        $this->app->singleton('App\\Support\\CustomPermissionChecker', function ($app) {
            return new \App\Support\CustomPermissionChecker();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Custom permission directive for our custom role system
        Blade::directive('customCan', function ($expression) {
            return "<?php if (app('App\\Support\\CustomPermissionChecker')->check($expression)): ?>";
        });

        Blade::directive('endcustomCan', function () {
            return "<?php endif; ?>";
        });
    }
}