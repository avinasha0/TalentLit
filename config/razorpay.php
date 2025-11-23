<?php

return [
    /*
    |--------------------------------------------------------------------------
    | RazorPay Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration for RazorPay payment gateway.
    | You can get these credentials from your RazorPay dashboard.
    |
    */

    'key_id' => env('RAZORPAY_KEY_ID') ?: env('RAZORPAY_KEY'),
    'key_secret' => env('RAZORPAY_KEY_SECRET') ?: env('RAZORPAY_SECRET'),
    'webhook_secret' => env('RAZORPAY_WEBHOOK_SECRET'),
    
    /*
    |--------------------------------------------------------------------------
    | Pro Plan Configuration
    |--------------------------------------------------------------------------
    |
    | This determines whether the Pro plan should use waitlist or payment mode.
    | Set to 'waitlist' to enable waitlist functionality
    | Set to 'active' to enable payment processing
    |
    */
    
    'pro_plan_mode' => env('PRO_PLAN_MODE', 'waitlist'),
    
    /*
    |--------------------------------------------------------------------------
    | Currency Configuration
    |--------------------------------------------------------------------------
    |
    | Default currency for payments
    |
    */
    
    'currency' => 'INR',
    
    /*
    |--------------------------------------------------------------------------
    | Payment Settings
    |--------------------------------------------------------------------------
    |
    | Additional payment configuration
    |
    */
    
    'payment_timeout' => 15, // minutes
    'retry_attempts' => 3,
    
    /*
    |--------------------------------------------------------------------------
    | Webhook Configuration
    |--------------------------------------------------------------------------
    |
    | Webhook settings for payment notifications
    |
    */
    
    'webhook_url' => env('APP_URL') . '/webhook/razorpay',
    'webhook_events' => [
        'payment.captured',
        'payment.failed',
        'order.paid',
        'invoice.paid', // Recurring subscription payment - sent when subscription invoice is paid
        'invoice.partially_paid', // Subscription payment partially completed
        'invoice.expired', // Subscription invoice expired (payment failed or not completed)
    ],
];
