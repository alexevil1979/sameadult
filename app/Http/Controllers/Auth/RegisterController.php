<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * Register Controller — AIVidCatalog18
 *
 * Handles user registration for the platform.
 *
 * Strictly fictional AI-generated content — no illegal/prohibited material.
 */
class RegisterController extends Controller
{
    /**
     * Show the registration form.
     *
     * GET /register
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle a registration request.
     *
     * POST /register
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'language' => 'nullable|in:en,ru,es',
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'language' => $validated['language'] ?? 'en',
        ]);

        Auth::login($user);

        return redirect('/')->with('success', __('Welcome to AIVidCatalog18!'));
    }
}
