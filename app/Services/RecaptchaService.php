<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RecaptchaService
{
    private string $secretKey;
    private string $siteKey;

    public function __construct()
    {
        $this->secretKey = config('recaptcha.secret_key');
        $this->siteKey = config('recaptcha.site_key');
    }

    /**
     * Verify reCAPTCHA response
     */
    public function verify(string $response, string $remoteIp = null): bool
    {
        if (empty($response)) {
            Log::warning('reCAPTCHA verification failed: Empty response');
            return false;
        }

        if (empty($this->secretKey)) {
            Log::error('reCAPTCHA verification failed: Secret key not configured');
            return false;
        }

        try {
            $verifyResponse = Http::timeout(10)->asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => $this->secretKey,
                'response' => $response,
                'remoteip' => $remoteIp,
            ]);

            if (!$verifyResponse->successful()) {
                Log::error('reCAPTCHA API request failed', [
                    'status' => $verifyResponse->status(),
                    'body' => $verifyResponse->body()
                ]);
                return false;
            }

            $result = $verifyResponse->json();

            Log::info('reCAPTCHA verification result', [
                'success' => $result['success'] ?? false,
                'score' => $result['score'] ?? null,
                'action' => $result['action'] ?? null,
                'challenge_ts' => $result['challenge_ts'] ?? null,
                'hostname' => $result['hostname'] ?? null,
                'error_codes' => $result['error-codes'] ?? null,
                'response_length' => strlen($response),
                'remote_ip' => $remoteIp
            ]);

            $success = $result['success'] ?? false;
            
            if (!$success && isset($result['error-codes'])) {
                Log::warning('reCAPTCHA verification failed with error codes', [
                    'error_codes' => $result['error-codes'],
                    'response_length' => strlen($response),
                    'remote_ip' => $remoteIp
                ]);
            }

            return $success;
        } catch (\Exception $e) {
            Log::error('reCAPTCHA verification exception', [
                'error' => $e->getMessage(),
                'response_length' => strlen($response),
                'remote_ip' => $remoteIp,
                'trace' => $e->getTraceAsString()
            ]);

            return false;
        }
    }

    /**
     * Get site key for frontend
     */
    public function getSiteKey(): string
    {
        return $this->siteKey;
    }

    /**
     * Check if reCAPTCHA is enabled
     */
    public function isEnabled(): bool
    {
        return !empty($this->secretKey) && !empty($this->siteKey);
    }
}
