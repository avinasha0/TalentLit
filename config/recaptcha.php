<?php

return [
    /*
    |--------------------------------------------------------------------------
    | reCAPTCHA Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your reCAPTCHA settings. You can get your site key
    | and secret key from the Google reCAPTCHA admin console.
    |
    */

    'site_key' => env('RECAPTCHA_SITE_KEY'),
    'secret_key' => env('RECAPTCHA_SECRET_KEY'),

    /*
    |--------------------------------------------------------------------------
    | reCAPTCHA Version
    |--------------------------------------------------------------------------
    |
    | Specify which version of reCAPTCHA to use:
    | - v2: Checkbox reCAPTCHA
    | - v3: Invisible reCAPTCHA with score
    |
    */

    'version' => env('RECAPTCHA_VERSION', 'v2'),

    /*
    |--------------------------------------------------------------------------
    | reCAPTCHA v3 Score Threshold
    |--------------------------------------------------------------------------
    |
    | For reCAPTCHA v3, specify the minimum score required to pass verification.
    | Score ranges from 0.0 (likely bot) to 1.0 (likely human).
    |
    */

    'score_threshold' => env('RECAPTCHA_SCORE_THRESHOLD', 0.5),

    /*
    |--------------------------------------------------------------------------
    | reCAPTCHA v3 Action
    |--------------------------------------------------------------------------
    |
    | For reCAPTCHA v3, specify the action name to verify against.
    |
    */

    'action' => env('RECAPTCHA_ACTION', 'submit'),

    /*
    |--------------------------------------------------------------------------
    | Allowed Domains (for subdomain support)
    |--------------------------------------------------------------------------
    |
    | List of domains that are allowed for reCAPTCHA verification.
    | This is useful when you have multiple subdomains. You can specify
    | the base domain here, and all subdomains will be accepted.
    |
    | Example: ['example.com', 'talentlit.com']
    | This will allow: example.com, subdomain.example.com, etc.
    |
    | Leave empty to accept any domain (not recommended for production).
    |
    */

    'allowed_domains' => env('RECAPTCHA_ALLOWED_DOMAINS') 
        ? explode(',', env('RECAPTCHA_ALLOWED_DOMAINS')) 
        : [],

    /*
    |--------------------------------------------------------------------------
    | Auto-accept Subdomains
    |--------------------------------------------------------------------------
    |
    | When set to true, any subdomain of registered domains will be accepted
    | automatically. This allows you to register only the base domain in
    | Google reCAPTCHA console (e.g., "example.com") and all subdomains
    | (e.g., "tenant1.example.com", "tenant2.example.com") will work.
    |
    | Set to false to require exact domain matches.
    |
    */

    'auto_accept_subdomains' => env('RECAPTCHA_AUTO_ACCEPT_SUBDOMAINS', true),

    /*
    |--------------------------------------------------------------------------
    | Skip reCAPTCHA for Localhost in Development
    |--------------------------------------------------------------------------
    |
    | When set to true, reCAPTCHA validation will be skipped for localhost
    | subdomains in local/development environment. This is useful when localhost
    | is not registered in Google reCAPTCHA console.
    |
    | Set to false to require reCAPTCHA even on localhost (requires localhost
    | to be registered in Google console).
    |
    */

    'skip_localhost_in_dev' => env('RECAPTCHA_SKIP_LOCALHOST_IN_DEV', true),
];
