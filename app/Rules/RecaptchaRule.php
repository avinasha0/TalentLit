<?php

namespace App\Rules;

use App\Services\RecaptchaService;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\Request;

class RecaptchaRule implements ValidationRule
{
    private RecaptchaService $recaptchaService;
    private Request $request;

    public function __construct(RecaptchaService $recaptchaService, Request $request)
    {
        $this->recaptchaService = $recaptchaService;
        $this->request = $request;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Skip validation if reCAPTCHA is not enabled
        if (!$this->recaptchaService->isEnabled()) {
            \Log::warning('reCAPTCHA validation skipped: Service not enabled');
            return;
        }

        // Skip validation in testing environment
        if (app()->environment('testing')) {
            return;
        }

        // Skip validation for exact localhost matches (but allow localhost subdomains)
        $host = $this->request->getHost();
        $hostWithoutPort = explode(':', $host)[0];
        
        // Only skip for exact localhost matches, not subdomains
        if (in_array($hostWithoutPort, ['localhost', '127.0.0.1', '0.0.0.0', '::1'])) {
            \Log::info('reCAPTCHA validation skipped for localhost development', ['host' => $host]);
            return;
        }
        
        // Skip validation for localhost subdomains in development if configured
        if (config('recaptcha.skip_localhost_in_dev', true) && 
            app()->environment(['local', 'development']) &&
            (str_contains($hostWithoutPort, 'localhost') || str_contains($hostWithoutPort, '127.0.0.1'))) {
            \Log::info('reCAPTCHA validation skipped for localhost subdomain in development', [
                'host' => $host,
                'environment' => app()->environment()
            ]);
            return;
        }
        
        // For localhost subdomains in production, reCAPTCHA will be validated
        // Make sure localhost is registered in Google reCAPTCHA console

        // Handle dev-skip value from component (ONLY in development)
        if ($value === 'dev-skip') {
            // Reject dev-skip in production for security
            if (app()->environment('production')) {
                \Log::error('reCAPTCHA dev-skip value detected in production - rejecting for security', [
                    'host' => $host,
                    'environment' => app()->environment()
                ]);
                $fail('reCAPTCHA verification is required. Please complete the reCAPTCHA verification.');
                return;
            }
            
            // Only allow dev-skip in development environments
            \Log::info('reCAPTCHA validation skipped: dev-skip value detected', [
                'host' => $host,
                'environment' => app()->environment()
            ]);
            return;
        }

        if (empty($value)) {
            $fail('Please complete the reCAPTCHA verification by clicking "I am not a robot".');
            return;
        }

        // Log the validation attempt for debugging
        \Log::info('reCAPTCHA validation attempt', [
            'has_value' => !empty($value),
            'value_length' => strlen($value),
            'ip' => $this->request->ip(),
            'user_agent' => $this->request->userAgent()
        ]);

        // Get the current hostname for subdomain support
        $hostname = $this->request->getHost();
        
        if (!$this->recaptchaService->verify($value, $this->request->ip(), $hostname)) {
            \Log::warning('reCAPTCHA verification failed', [
                'ip' => $this->request->ip(),
                'hostname' => $hostname,
                'user_agent' => $this->request->userAgent(),
                'value_length' => strlen($value)
            ]);
            $fail('reCAPTCHA verification failed. Please try again or refresh the page.');
        }
    }
}
