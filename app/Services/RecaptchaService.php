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
    public function verify(string $response, string $remoteIp = null, string $requestHostname = null): bool
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

            $success = $result['success'] ?? false;
            $hostname = $result['hostname'] ?? null;
            $errorCodes = $result['error-codes'] ?? [];

            Log::info('reCAPTCHA verification result', [
                'success' => $success,
                'score' => $result['score'] ?? null,
                'action' => $result['action'] ?? null,
                'challenge_ts' => $result['challenge_ts'] ?? null,
                'hostname' => $hostname,
                'request_hostname' => $requestHostname,
                'error_codes' => $errorCodes,
                'response_length' => strlen($response),
                'remote_ip' => $remoteIp
            ]);

            // Check for hostname mismatch errors (common with subdomains)
            if (!$success && in_array('invalid-input-response', $errorCodes)) {
                Log::warning('reCAPTCHA invalid input response - possible token reuse or expired token', [
                    'hostname' => $hostname,
                    'request_hostname' => $requestHostname,
                    'error_codes' => $errorCodes
                ]);
            }

            // Check for hostname-related errors
            if (!$success && (in_array('invalid-input-secret', $errorCodes) || in_array('bad-request', $errorCodes))) {
                Log::error('reCAPTCHA configuration error - check secret key and domain settings', [
                    'hostname' => $hostname,
                    'request_hostname' => $requestHostname,
                    'error_codes' => $errorCodes
                ]);
            }

            // Check for domain not registered error (common with localhost)
            if (!$success && in_array('invalid-input-response', $errorCodes)) {
                // Check if this is a localhost domain issue
                if ($requestHostname && (str_contains($requestHostname, 'localhost') || str_contains($requestHostname, '127.0.0.1'))) {
                    Log::warning('reCAPTCHA domain not registered - localhost needs to be added to Google console', [
                        'hostname' => $hostname,
                        'request_hostname' => $requestHostname,
                        'error_codes' => $errorCodes,
                        'note' => 'Add "localhost" to Google reCAPTCHA console domains, or set RECAPTCHA_SKIP_LOCALHOST_IN_DEV=true'
                    ]);
                    
                    // In development, if skip_localhost_in_dev is enabled, return true to allow
                    if (config('recaptcha.skip_localhost_in_dev', true) && app()->environment(['local', 'development'])) {
                        Log::info('reCAPTCHA validation bypassed for localhost in development mode');
                        return true;
                    }
                }
            }

            // If verification was successful, check hostname matching (for subdomain support)
            if ($success) {
                // If no hostname returned, accept the verification (some configurations don't return hostname)
                if (!$hostname) {
                    return true;
                }

                // If no request hostname provided, accept the verification
                if (!$requestHostname) {
                    return true;
                }

                // Check if hostnames match (supports subdomains)
                if ($this->isValidHostname($hostname, $requestHostname)) {
                    return true;
                }

                // Even if hostnames don't match exactly, if base domains match, accept it
                // This allows subdomains to work when only base domain is registered in Google console
                $hostnameBase = $this->getBaseDomain($hostname);
                $requestBase = $this->getBaseDomain($requestHostname);
                
                if ($hostnameBase && $requestBase && $hostnameBase === $requestBase) {
                    Log::info('reCAPTCHA accepted: base domain match for subdomain', [
                        'verified_hostname' => $hostname,
                        'request_hostname' => $requestHostname,
                        'base_domain' => $hostnameBase
                    ]);
                    return true;
                }
                
                // Special handling for localhost subdomains
                // Google may return "localhost" but request is "tenant1.localhost" - accept it
                if (($hostnameBase === 'localhost' || $requestBase === 'localhost') && 
                    (str_contains($hostname, 'localhost') || str_contains($requestHostname, 'localhost'))) {
                    Log::info('reCAPTCHA accepted: localhost subdomain match', [
                        'verified_hostname' => $hostname,
                        'request_hostname' => $requestHostname
                    ]);
                    return true;
                }

                // Check against configured allowed domains
                $allowedDomains = config('recaptcha.allowed_domains', []);
                if (!empty($allowedDomains)) {
                    foreach ($allowedDomains as $allowedDomain) {
                        $allowedBase = $this->getBaseDomain($allowedDomain);
                        if ($requestBase && $allowedBase && $requestBase === $allowedBase) {
                            Log::info('reCAPTCHA accepted: matches allowed domain', [
                                'verified_hostname' => $hostname,
                                'request_hostname' => $requestHostname,
                                'allowed_domain' => $allowedDomain
                            ]);
                            return true;
                        }
                    }
                }

                // If verification succeeded but hostname doesn't match, log warning but accept it
                // This is important for subdomain support - Google may return base domain
                // even when subdomain is used, and verification still succeeds
                Log::warning('reCAPTCHA hostname mismatch but verification succeeded', [
                    'verified_hostname' => $hostname,
                    'request_hostname' => $requestHostname,
                    'note' => 'Accepting verification - Google may return base domain for subdomains'
                ]);
                return true;
            }

            return $success;
        } catch (\Exception $e) {
            Log::error('reCAPTCHA verification exception', [
                'error' => $e->getMessage(),
                'response_length' => strlen($response),
                'remote_ip' => $remoteIp,
                'request_hostname' => $requestHostname,
                'trace' => $e->getTraceAsString()
            ]);

            return false;
        }
    }

    /**
     * Check if hostname is valid (supports subdomains)
     */
    private function isValidHostname(string $verifiedHostname, string $requestHostname): bool
    {
        // Exact match
        if ($verifiedHostname === $requestHostname) {
            return true;
        }

        // Remove port if present
        $verifiedHostname = explode(':', $verifiedHostname)[0];
        $requestHostname = explode(':', $requestHostname)[0];

        // Special handling for localhost subdomains
        $isLocalhostVerified = str_contains($verifiedHostname, 'localhost') || $verifiedHostname === '127.0.0.1';
        $isLocalhostRequest = str_contains($requestHostname, 'localhost') || $requestHostname === '127.0.0.1';
        
        if ($isLocalhostVerified && $isLocalhostRequest) {
            // Both are localhost variants - accept them
            return true;
        }

        // Check if request hostname is a subdomain of verified hostname
        if (str_ends_with($requestHostname, '.' . $verifiedHostname)) {
            return true;
        }

        // Check if verified hostname is a subdomain of request hostname
        if (str_ends_with($verifiedHostname, '.' . $requestHostname)) {
            return true;
        }

        // Extract base domain from both
        $verifiedBase = $this->getBaseDomain($verifiedHostname);
        $requestBase = $this->getBaseDomain($requestHostname);

        // If base domains match, allow (for subdomain support)
        if ($verifiedBase && $requestBase && $verifiedBase === $requestBase) {
            return true;
        }

        return false;
    }

    /**
     * Extract base domain from hostname
     */
    private function getBaseDomain(string $hostname): ?string
    {
        // Remove port if present
        $hostname = explode(':', $hostname)[0];
        
        // Special handling for localhost subdomains
        if (str_ends_with($hostname, '.localhost') || $hostname === 'localhost') {
            return 'localhost';
        }
        
        // Special handling for 127.0.0.1 subdomains
        if (str_contains($hostname, '127.0.0.1')) {
            return '127.0.0.1';
        }
        
        $parts = explode('.', $hostname);
        if (count($parts) >= 2) {
            // Return last two parts (e.g., example.com from subdomain.example.com)
            return implode('.', array_slice($parts, -2));
        }
        return $hostname;
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
