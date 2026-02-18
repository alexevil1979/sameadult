<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 * Auth API Controller — AIVidCatalog18
 *
 * Handles API authentication via Laravel Sanctum tokens.
 * Used by mobile apps for login, register, and token management.
 *
 * Strictly fictional AI-generated content — no illegal/prohibited material.
 *
 * @group Authentication
 *
 * Endpoints:
 *   POST /api/auth/register   — Register new user
 *   POST /api/auth/login      — Login and get token
 *   POST /api/auth/logout     — Revoke current token
 *   GET  /api/auth/user       — Get authenticated user info
 */
class AuthApiController extends Controller
{
    /**
     * Register a new user and return an API token.
     *
     * POST /api/auth/register
     *
     * @bodyParam name string required
     * @bodyParam email string required
     * @bodyParam password string required
     * @bodyParam password_confirmation string required
     * @bodyParam language string optional (en|ru|es)
     */
    public function register(Request $request): JsonResponse
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

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'message' => 'Registration successful.',
            'user'    => $this->formatUser($user),
            'token'   => $token,
        ], 201);
    }

    /**
     * Login and receive an API token.
     *
     * POST /api/auth/login
     *
     * @bodyParam email string required
     * @bodyParam password string required
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => [__('The provided credentials are incorrect.')],
            ]);
        }

        // Revoke all previous tokens for security
        $user->tokens()->delete();

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful.',
            'user'    => $this->formatUser($user),
            'token'   => $token,
        ]);
    }

    /**
     * Logout — revoke the current API token.
     *
     * POST /api/auth/logout
     * Requires: Bearer token
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully.']);
    }

    /**
     * Get the authenticated user's profile.
     *
     * GET /api/auth/user
     * Requires: Bearer token
     */
    public function user(Request $request): JsonResponse
    {
        return response()->json([
            'data' => $this->formatUser($request->user()),
        ]);
    }

    /**
     * Format user data for API response.
     */
    protected function formatUser(User $user): array
    {
        return [
            'id'                    => $user->id,
            'name'                  => $user->name,
            'email'                 => $user->email,
            'language'              => $user->language,
            'is_admin'              => $user->is_admin,
            'has_active_subscription' => $user->hasActiveSubscription(),
            'subscription_end'      => $user->subscription_end?->toISOString(),
            'created_at'            => $user->created_at->toISOString(),
        ];
    }
}
