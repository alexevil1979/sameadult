<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

/**
 * CSRF Token Verification — AIVidCatalog18
 *
 * Webhook endpoints are excluded from CSRF verification
 * because NOWPayments sends server-to-server POST requests.
 */
class VerifyCsrfToken extends Middleware
{
    /**
     * URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'webhook/*',
    ];
}
