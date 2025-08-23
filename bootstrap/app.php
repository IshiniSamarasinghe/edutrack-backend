<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\HandleCors;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Global CORS (required for cookies from localhost:3000)
        $middleware->use([ HandleCors::class ]);

        // If Sanctum is present, allow stateful SPA requests on the API group too
        if (class_exists(\Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class)) {
            $middleware->appendToGroup('api', \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class);
        }
        // No need to append EncryptCookies — it's already in the default "web" stack.
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
