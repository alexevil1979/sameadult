<?php

/**
 * AIVidCatalog18 — Application Bootstrap
 *
 * Configures routing (web + API), middleware aliases,
 * and exception handling for the platform.
 * Strictly fictional AI-generated content — no illegal/prohibited material.
 */

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Register custom middleware aliases
        $middleware->alias([
            'age.verified' => \App\Http\Middleware\AgeVerification::class,
            'admin'        => \App\Http\Middleware\AdminMiddleware::class,
            'subscribed'   => \App\Http\Middleware\EnsureSubscribed::class,
            'set.locale'   => \App\Http\Middleware\SetLocale::class,
        ]);

        // Sanctum stateful API middleware for SPA authentication
        $middleware->statefulApi();
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
