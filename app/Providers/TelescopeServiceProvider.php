<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

// Check if Telescope is installed before extending
if (class_exists(\Laravel\Telescope\TelescopeApplicationServiceProvider::class)) {
    // Telescope is installed - use the real provider
    class TelescopeServiceProvider extends \Laravel\Telescope\TelescopeApplicationServiceProvider
    {
        /**
         * Register any application services.
         */
        public function register(): void
        {
            // Telescope::night();

            $this->hideSensitiveRequestDetails();

            $isLocal = $this->app->environment('local');

            \Laravel\Telescope\Telescope::filter(function (\Laravel\Telescope\IncomingEntry $entry) use ($isLocal) {
                return $isLocal ||
                       $entry->isReportableException() ||
                       $entry->isFailedRequest() ||
                       $entry->isFailedJob() ||
                       $entry->isScheduledTask() ||
                       $entry->hasMonitoredTag();
            });
        }

        /**
         * Prevent sensitive request details from being logged by Telescope.
         */
        protected function hideSensitiveRequestDetails(): void
        {
            if ($this->app->environment('local')) {
                return;
            }

            \Laravel\Telescope\Telescope::hideRequestParameters(['_token']);

            \Laravel\Telescope\Telescope::hideRequestHeaders([
                'cookie',
                'x-csrf-token',
                'x-xsrf-token',
            ]);
        }

        /**
         * Register the Telescope gate.
         *
         * This gate determines who can access Telescope in non-local environments.
         */
        protected function gate(): void
        {
            \Illuminate\Support\Facades\Gate::define('viewTelescope', function ($user) {
                return in_array($user->email, [
                    //
                ]);
            });
        }
    }
} else {
    // Telescope is not installed - create a dummy provider
    class TelescopeServiceProvider extends ServiceProvider
    {
        public function register(): void
        {
            // Do nothing - Telescope not installed
        }

        public function boot(): void
        {
            // Do nothing - Telescope not installed
        }
    }
}
