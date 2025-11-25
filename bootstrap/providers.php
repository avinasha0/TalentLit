<?php

$providers = [
    App\Providers\AppServiceProvider::class,
    App\Providers\CurrencyServiceProvider::class,
];

// Only register Telescope in local/development or if package is installed
if (app()->environment(['local', 'development']) || class_exists(\Laravel\Telescope\TelescopeApplicationServiceProvider::class)) {
    $providers[] = App\Providers\TelescopeServiceProvider::class;
}

return $providers;
