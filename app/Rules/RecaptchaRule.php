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

        if (!$this->recaptchaService->verify($value, $this->request->ip())) {
            \Log::warning('reCAPTCHA verification failed', [
                'ip' => $this->request->ip(),
                'user_agent' => $this->request->userAgent(),
                'value_length' => strlen($value)
            ]);
            $fail('reCAPTCHA verification failed. Please try again or refresh the page.');
        }
    }
}
