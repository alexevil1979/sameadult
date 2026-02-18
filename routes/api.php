<?php

/**
 * API Routes — AIVidCatalog18
 *
 * RESTful API endpoints for mobile apps and third-party consumers.
 * Authentication: Laravel Sanctum (Bearer token).
 *
 * Base URL: /api/v1/
 *
 * Strictly fictional AI-generated content — no illegal/prohibited material.
 *
 * Rate limiting: 60 requests/minute for guests, 120 for authenticated users.
 */

use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\VideoApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// =========================================================================
// API v1 Routes
// =========================================================================

// Health check
Route::get('/ping', function () {
    return response()->json([
        'status'  => 'ok',
        'service' => 'AIVidCatalog18 API',
        'version' => '1.0.0',
    ]);
});

// =====================================================================
// Authentication (Public)
// =====================================================================
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthApiController::class, 'register']);
    Route::post('/login', [AuthApiController::class, 'login']);
});

// =====================================================================
// Authenticated Routes (Sanctum)
// =====================================================================
Route::middleware('auth:sanctum')->group(function () {

    // Auth management
    Route::post('/auth/logout', [AuthApiController::class, 'logout']);
    Route::get('/auth/user', [AuthApiController::class, 'user']);

    // Video access (signed URL for streaming)
    Route::get('/videos/{video}/access', [VideoApiController::class, 'access']);
});

// =====================================================================
// Public Video Catalog (No auth required, optional auth for access info)
// =====================================================================
Route::get('/videos', [VideoApiController::class, 'index']);
Route::get('/videos/{video}', [VideoApiController::class, 'show']);
