<?php

/**
 * Web Routes — AIVidCatalog18
 *
 * All web routes are wrapped in the 'set.locale' middleware for i18n.
 * Adult content routes additionally require 'age.verified' middleware.
 *
 * Strictly fictional AI-generated content — no illegal/prohibited material.
 */

use App\Http\Controllers\AgeGateController;
use App\Http\Controllers\Admin\VideoController as AdminVideoController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

// =========================================================================
// Age Gate — Must be accessible WITHOUT age verification
// =========================================================================
Route::get('/age-gate', [AgeGateController::class, 'show'])->name('age-gate.show');
Route::post('/age-gate/verify', [AgeGateController::class, 'verify'])->name('age-gate.verify');

// =========================================================================
// Language Switcher — Accessible without auth
// =========================================================================
Route::get('/locale/{locale}', [LocaleController::class, 'switch'])->name('locale.switch');

// =========================================================================
// Webhook — CSRF exempt, no age gate, no auth
// =========================================================================
Route::post('/webhook/nowpayments', [WebhookController::class, 'nowpayments'])
    ->name('webhook.nowpayments')
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

// =========================================================================
// Public Routes — Require age verification
// =========================================================================
Route::middleware(['set.locale', 'age.verified'])->group(function () {

    // Home page — Landing page with hero, features, pricing
    Route::get('/', [\App\Http\Controllers\LandingController::class, 'index'])->name('home');

    // =====================================================================
    // Authentication Routes (Guest only)
    // =====================================================================
    Route::middleware('guest')->group(function () {
        Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [LoginController::class, 'login']);
        Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
        Route::post('/register', [RegisterController::class, 'register']);
    });

    Route::post('/logout', [LoginController::class, 'logout'])
        ->middleware('auth')
        ->name('logout');

    // =====================================================================
    // Public Video Catalog
    // =====================================================================
    Route::get('/videos', [VideoController::class, 'index'])->name('videos.index');
    Route::get('/videos/{video}', [VideoController::class, 'show'])->name('videos.show');

    // Video streaming — requires auth for premium content
    Route::get('/videos/{video}/stream', [VideoController::class, 'stream'])
        ->middleware('auth')
        ->name('videos.stream');

    // =====================================================================
    // Subscription Plans
    // =====================================================================
    Route::get('/plans', [PlanController::class, 'index'])->name('plans.index');

    Route::middleware('auth')->group(function () {
        Route::post('/plans/{plan}/buy', [PlanController::class, 'buy'])->name('plans.buy');
    });

    // =====================================================================
    // Payment Callbacks
    // =====================================================================
    Route::get('/payments/success', [PlanController::class, 'success'])->name('payments.success');
    Route::get('/payments/cancel', [PlanController::class, 'cancel'])->name('payments.cancel');

    // =====================================================================
    // Admin Panel — Requires auth + admin middleware
    // =====================================================================
    Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

        // Admin Dashboard
        Route::get('/', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        // Video Management CRUD
        Route::resource('videos', AdminVideoController::class);
        Route::post('videos/{video}/approve', [AdminVideoController::class, 'approve'])
            ->name('videos.approve');
    });
});
