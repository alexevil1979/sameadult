<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Admin Middleware — AIVidCatalog18
 *
 * Restricts access to admin panel routes. Only users with is_admin=true
 * can access admin functionality (video CRUD, user management, etc.).
 *
 * Strictly fictional AI-generated content — no illegal/prohibited material.
 */
class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user() || !$request->user()->is_admin) {
            abort(403, __('Unauthorized. Admin access required.'));
        }

        return $next($request);
    }
}
