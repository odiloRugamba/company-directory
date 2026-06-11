<?php

use App\Http\Middleware\EnsureProfileComplete;
use App\Http\Middleware\EnsureRole;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        ]);

        $middleware->alias([
            'profile.complete' => EnsureProfileComplete::class,
            'role' => EnsureRole::class,
        ]);

        $middleware->throttleApi('60,1', 'public');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->respond(function (Response $response, \Throwable $e, Request $request) {
            if ($request->is('api/*') && in_array($response->getStatusCode(), [403, 404, 500])) {
                return response()->json([
                    'data' => null,
                    'message' => $e->getMessage() ?: 'An error occurred.',
                ], $response->getStatusCode());
            }
            return $response;
        });
    })->create();
