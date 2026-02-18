<?php

/**
 * NOWPayments Crypto Gateway Configuration
 *
 * Used for subscription payments on AIVidCatalog18.
 * Strictly fictional AI-generated content — no illegal/prohibited material.
 */

return [
    'api_key' => env('NOWPAYMENTS_API_KEY', ''),
    'ipn_secret' => env('NOWPAYMENTS_IPN_SECRET', ''),
    'sandbox' => env('NOWPAYMENTS_SANDBOX', true),

    // Base URL switches between sandbox and production
    'base_url' => env('NOWPAYMENTS_SANDBOX', true)
        ? 'https://api-sandbox.nowpayments.io/v1'
        : 'https://api.nowpayments.io/v1',
];
