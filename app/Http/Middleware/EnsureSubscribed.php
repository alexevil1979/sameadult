<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Subscription Middleware — AIVidCatalog18
 *
 * Ensures the authenticated user has an active subscription
 * before accessing premium content or features.
 *
 * Strictly fictional AI-generated content — no illegal/prohibited material.
 */
class EnsureSubscribed
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user() || !$request->user()->hasActiveSubscription()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => __('Active subscription required.'),
                    'upgrade_url' => route('plans.index'),
                ], 403);
            }

            return redirect()->route('plans.index')
                ->with('warning', __('You need an active subscription to access premium content.'));
        }

        return $next($request);
    }
}
