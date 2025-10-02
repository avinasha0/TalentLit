<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class CurrencyServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Register currency blade directives
        Blade::directive('currency', function ($expression) {
            return "<?php echo currency($expression); ?>";
        });

        Blade::directive('currencySymbol', function ($expression = null) {
            if ($expression) {
                return "<?php echo currencySymbol($expression); ?>";
            }
            return "<?php echo currencySymbol(); ?>";
        });

        Blade::directive('currencyCode', function ($expression = null) {
            if ($expression) {
                return "<?php echo currencyCode($expression); ?>";
            }
            return "<?php echo currencyCode(); ?>";
        });

        Blade::directive('currencyRange', function ($expression) {
            return "<?php echo currencyRange($expression); ?>";
        });

        Blade::directive('currentCurrency', function () {
            return "<?php echo currentCurrency(); ?>";
        });

        Blade::directive('subscriptionPrice', function ($expression) {
            return "<?php echo subscriptionPrice($expression); ?>";
        });
    }
}
