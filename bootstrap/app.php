<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (\Illuminate\Foundation\Configuration\Middleware $middleware) {
    $middleware->alias([
        'role' => \App\Http\Middleware\RoleMiddleware::class,
    ]);
})
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->reportable(function (\Throwable $e) {
            // Ensure we always log a short, searchable line in serverless logs
            // (Vercel sometimes shows only a stack fragment; this captures the real message).
            $vercelId = null;
            $method = null;
            $url = null;

            try {
                $request = request();
                $vercelId = $request->headers->get('x-vercel-id');
                $method = $request->method();
                $url = $request->fullUrl();
            } catch (\Throwable $ignored) {
                // no request available (CLI, early boot, etc.)
            }

            logger()->error('Unhandled exception', [
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'vercel_id' => $vercelId,
                'method' => $method,
                'url' => $url,
            ]);
        });
    })->create();
