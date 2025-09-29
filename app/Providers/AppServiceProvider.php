<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register layout components
        $this->loadViewComponentsAs('', [
            'app-layout' => \App\View\Components\AppLayout::class,
            'guest-layout' => \App\View\Components\GuestLayout::class,
        ]);
    }
}
