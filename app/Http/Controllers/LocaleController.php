<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * Locale Controller — AIVidCatalog18
 *
 * Handles language switching for the web interface.
 * Supports: en, ru, es
 */
class LocaleController extends Controller
{
    /**
     * Switch the application locale.
     *
     * GET /locale/{locale}
     */
    public function switch(string $locale)
    {
        $supported = ['en', 'ru', 'es'];

        if (!in_array($locale, $supported)) {
            abort(400, 'Unsupported locale.');
        }

        session(['locale' => $locale]);

        // Update user preference if authenticated
        if (auth()->check()) {
            auth()->user()->update(['language' => $locale]);
        }

        return back()->with('success', __('Language changed successfully.'));
    }
}
