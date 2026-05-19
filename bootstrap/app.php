<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
    web: __DIR__.'/../routes/web.php',
    api: __DIR__.'/../routes/api.php',
    commands: __DIR__.'/../routes/console.php',
)

    ->withMiddleware(function (Middleware $middleware) {

    // GLOBAL middleware (работает для всех запросов)
    $middleware->append(\App\Http\Middleware\CorsMiddleware::class);

    // API middleware (если нужно)
    $middleware->api(prepend: [
        \App\Http\Middleware\CorsMiddleware::class,
    ]);
})

    ->withExceptions(function (Exceptions $exceptions): void {
        // Return JSON for API errors
        $exceptions->render(function (Throwable $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => $e->getMessage(),
                    'status' => 'error'
                ], $e instanceof \Illuminate\Http\Exceptions\HttpResponseException ? 
                   $e->getResponse()->getStatusCode() : 500);
            }
        });
    })->create();
