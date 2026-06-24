<?php

use App\Http\Middleware\AdminMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__ . '/../routes/api.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        if (isset($_ENV['VERCEL']) || getenv('VERCEL')) {
            $middleware->trustProxies(at: '*');
        }
        $middleware->alias([
            'admin' => AdminMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
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
