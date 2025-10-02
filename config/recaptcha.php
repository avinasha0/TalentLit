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
];
