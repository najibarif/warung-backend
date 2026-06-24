<?php

use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\SecurityHeadersMiddleware;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        if (isset($_ENV['VERCEL']) || getenv('VERCEL')) {
            $middleware->trustProxies(at: '*');
        }
        $middleware->api(prepend: [
            SecurityHeadersMiddleware::class,
        ]);
        $middleware->alias([
            'admin' => AdminMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (NotFoundHttpException|MethodNotAllowedHttpException $e, Request $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json(['message' => 'Not Found'], 404);
            }
        });
        $exceptions->render(function (\Throwable $e, Request $request) {
            if (($request->expectsJson() || $request->is('api/*')) && !$e instanceof AuthenticationException && !$e instanceof ModelNotFoundException) {
                return response()->json([
                    'message' => 'Internal Server Error'
                ], 500);
            }
        });
    })->create();

// Pindahkan direktori storage ke /tmp karena Vercel read-only
if (isset($_ENV['VERCEL']) || getenv('VERCEL')) {
    $app->useStoragePath('/tmp/storage');
    if (!is_dir('/tmp/storage/framework/views')) {
        @mkdir('/tmp/storage/framework/views', 0777, true);
        @mkdir('/tmp/storage/framework/cache/data', 0777, true);
        @mkdir('/tmp/storage/logs', 0777, true);
    }
}

return $app;
