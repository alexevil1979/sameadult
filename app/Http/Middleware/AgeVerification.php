<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Age Verification Middleware — AIVidCatalog18
 *
 * Ensures visitors have confirmed they are 18+ before accessing
 * any content on the platform. Uses a cookie-based verification.
 *
 * Strictly fictional AI-generated content — no illegal/prohibited material.
 */
class AgeVerification
{
    /**
     * Routes that should be excluded from age verification.
     */
    protected array $except = [
        'age-gate',
        'age-gate/verify',
        'api/*',
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip age verification for excluded routes
        foreach ($this->except as $pattern) {
            if ($request->is($pattern)) {
                return $next($request);
            }
        }

        // Check if the user has already verified their age (cookie lasts 1 year)
        if (!$request->cookie('age_verified')) {
            return redirect()->route('age-gate.show')
                ->with('intended_url', $request->url());
        }

        return $next($request);
    }
}
