<?php

namespace App\Services;

use App\Models\Tenant;

class CurrencyService
{
    /**
     * Currency configurations
     */
    const CURRENCIES = [
        'INR' => [
            'symbol' => '₹',
            'code' => 'INR',
            'name' => 'Indian Rupee',
            'countries' => ['India', 'IN'],
            'decimal_places' => 0, // INR typically doesn't use decimal places
            'thousands_separator' => ',',
            'decimal_separator' => '.',
        ],
        'USD' => [
            'symbol' => '$',
            'code' => 'USD',
            'name' => 'US Dollar',
            'countries' => ['United States', 'US', 'USA'],
            'decimal_places' => 2,
            'thousands_separator' => ',',
            'decimal_separator' => '.',
        ],
    ];

    /**
     * Default currency
     */
    const DEFAULT_CURRENCY = 'INR';

    /**
     * Get currency for a tenant (always INR for India)
     */
    public static function getCurrencyForTenant(Tenant $tenant): string
    {
        return 'INR';
    }

    /**
     * Detect currency from location string (always INR for India)
     */
    public static function detectCurrencyFromLocation(?string $location): string
    {
        return 'INR';
    }

    /**
     * Get currency configuration
     */
    public static function getCurrencyConfig(string $currency): array
    {
        return self::CURRENCIES[$currency] ?? self::CURRENCIES[self::DEFAULT_CURRENCY];
    }

    /**
     * Format amount with currency symbol
     */
    public static function formatAmount(float $amount, string $currency = null): string
    {
        if (!$currency) {
            $currency = self::getCurrentCurrency();
        }

        $config = self::getCurrencyConfig($currency);
        
        // Format the number
        $formattedAmount = number_format(
            $amount,
            $config['decimal_places'],
            $config['decimal_separator'],
            $config['thousands_separator']
        );

        // Add currency symbol
        return $config['symbol'] . $formattedAmount;
    }

    /**
     * Format amount range (e.g., ₹50,000 - ₹80,000)
     */
    public static function formatAmountRange(float $minAmount, float $maxAmount, string $currency = null): string
    {
        if (!$currency) {
            $currency = self::getCurrentCurrency();
        }

        $config = self::getCurrencyConfig($currency);
        
        $minFormatted = number_format(
            $minAmount,
            $config['decimal_places'],
            $config['decimal_separator'],
            $config['thousands_separator']
        );

        $maxFormatted = number_format(
            $maxAmount,
            $config['decimal_places'],
            $config['decimal_separator'],
            $config['thousands_separator']
        );

        return $config['symbol'] . $minFormatted . ' - ' . $config['symbol'] . $maxFormatted;
    }

    /**
     * Get current currency for the active tenant
     */
    public static function getCurrentCurrency(): string
    {
        $tenant = self::getCurrentTenant();
        return $tenant ? self::getCurrencyForTenant($tenant) : self::DEFAULT_CURRENCY;
    }

    /**
     * Get current tenant from Tenancy context
     */
    private static function getCurrentTenant(): ?Tenant
    {
        return \App\Support\Tenancy::get();
    }

    /**
     * Get currency symbol
     */
    public static function getCurrencySymbol(string $currency = null): string
    {
        if (!$currency) {
            $currency = self::getCurrentCurrency();
        }

        $config = self::getCurrencyConfig($currency);
        return $config['symbol'];
    }

    /**
     * Get currency code
     */
    public static function getCurrencyCode(string $currency = null): string
    {
        if (!$currency) {
            $currency = self::getCurrentCurrency();
        }

        $config = self::getCurrencyConfig($currency);
        return $config['code'];
    }

    /**
     * Get all available currencies
     */
    public static function getAvailableCurrencies(): array
    {
        return self::CURRENCIES;
    }

    /**
     * Convert amount from one currency to another (basic conversion)
     * Note: This is a simplified version. In production, you'd want to use a real exchange rate API
     */
    public static function convertAmount(float $amount, string $fromCurrency, string $toCurrency): float
    {
        if ($fromCurrency === $toCurrency) {
            return $amount;
        }

        // Basic conversion rates (you should use a real exchange rate API)
        $exchangeRates = [
            'INR' => [
                'USD' => 0.012, // 1 INR = 0.012 USD (approximate)
            ],
            'USD' => [
                'INR' => 83.0, // 1 USD = 83 INR (approximate)
            ],
        ];

        $rate = $exchangeRates[$fromCurrency][$toCurrency] ?? 1;
        return $amount * $rate;
    }

    /**
     * Format subscription plan price with proper currency conversion
     */
    public static function formatSubscriptionPrice(float $price, string $planCurrency = 'USD', string $displayCurrency = null): string
    {
        if (!$displayCurrency) {
            $displayCurrency = self::getCurrentCurrency();
        }

        // Convert price if needed
        $convertedPrice = self::convertAmount($price, $planCurrency, $displayCurrency);
        
        // Format with appropriate currency
        return self::formatAmount($convertedPrice, $displayCurrency);
    }

    /**
     * Parse amount from formatted string
     */
    public static function parseAmount(string $formattedAmount, string $currency = null): float
    {
        if (!$currency) {
            $currency = self::getCurrentCurrency();
        }

        $config = self::getCurrencyConfig($currency);
        
        // Remove currency symbol and thousands separators
        $cleanAmount = str_replace([$config['symbol'], $config['thousands_separator']], '', $formattedAmount);
        
        // Replace decimal separator with dot if needed
        if ($config['decimal_separator'] !== '.') {
            $cleanAmount = str_replace($config['decimal_separator'], '.', $cleanAmount);
        }

        return (float) $cleanAmount;
    }
}
