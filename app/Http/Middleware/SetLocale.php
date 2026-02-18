<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

/**
 * Locale Middleware — AIVidCatalog18
 *
 * Sets the application locale based on:
 * 1. URL query parameter (?lang=ru)
 * 2. Session preference
 * 3. Authenticated user's language setting
 * 4. Browser Accept-Language header
 * 5. Default fallback (en)
 *
 * Supports: en, ru, es
 */
class SetLocale
{
    /**
     * Supported locales.
     */
    protected array $supported = ['en', 'ru', 'es'];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = null;

        // 1. Check URL query parameter
        if ($request->has('lang') && in_array($request->query('lang'), $this->supported)) {
            $locale = $request->query('lang');
            session(['locale' => $locale]);
        }

        // 2. Check session
        if (!$locale && session()->has('locale') && in_array(session('locale'), $this->supported)) {
            $locale = session('locale');
        }

        // 3. Check authenticated user preference
        if (!$locale && $request->user() && in_array($request->user()->language, $this->supported)) {
            $locale = $request->user()->language;
        }

        // 4. Check browser Accept-Language
        if (!$locale) {
            $browserLocale = substr($request->server('HTTP_ACCEPT_LANGUAGE', 'en'), 0, 2);
            if (in_array($browserLocale, $this->supported)) {
                $locale = $browserLocale;
            }
        }

        // 5. Apply locale (fallback to config default)
        App::setLocale($locale ?? config('app.locale', 'en'));

        return $next($request);
    }
}
