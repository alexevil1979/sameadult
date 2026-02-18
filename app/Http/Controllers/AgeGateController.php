<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

/**
 * Age Gate Controller — AIVidCatalog18
 *
 * Handles age verification flow. All visitors must confirm they are 18+
 * before accessing any content on the platform.
 *
 * Strictly fictional AI-generated content — no illegal/prohibited material.
 */
class AgeGateController extends Controller
{
    /**
     * Show the age verification page.
     *
     * GET /age-gate
     */
    public function show()
    {
        // If already verified, redirect to home
        if (request()->cookie('age_verified')) {
            return redirect('/');
        }

        return view('age-gate');
    }

    /**
     * Process the age verification form.
     *
     * POST /age-gate/verify
     *
     * Validates:
     * - User checked the "I confirm I am 18+" checkbox
     * - Optional: date of birth confirms 18+
     *
     * Sets a cookie valid for 1 year.
     */
    public function verify(Request $request)
    {
        $request->validate([
            'confirm_age' => 'required|accepted',
            'birth_date'  => 'nullable|date|before_or_equal:' . now()->subYears(18)->format('Y-m-d'),
        ], [
            'confirm_age.required' => __('You must confirm that you are 18 or older.'),
            'confirm_age.accepted' => __('You must confirm that you are 18 or older.'),
            'birth_date.before_or_equal' => __('You must be at least 18 years old to access this site.'),
        ]);

        // Set age verification cookie (1 year = 525600 minutes)
        $cookie = Cookie::make('age_verified', '1', 525600, '/', null, false, true);

        $intendedUrl = $request->input('intended_url', '/');

        return redirect($intendedUrl)->withCookie($cookie);
    }
}
