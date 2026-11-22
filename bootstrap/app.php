<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->statefulApi();

        // Daftarkan Alias Middleware Spatie Laravel Permission
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Cetak exception awal secara langsung tanpa memanggil Blade view
        $exceptions->render(function (\Throwable $e, Request $request) {
            return response(
                "<div style='font-family:Arial,sans-serif; padding:25px; background:#fff0f0; border:2px solid #dc3545;'>" .
                "<h1 style='color:#dc3545; margin-top:0;'>PENYEBAB UTAMA ERROR:</h1>" .
                "<p style='font-size:18px;'><strong>Pesan Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>" .
                "<p><strong>Class:</strong> " . get_class($e) . "</p>" .
                "<p><strong>Lokasi:</strong> " . htmlspecialchars($e->getFile()) . " pada baris <strong>" . $e->getLine() . "</strong></p>" .
                "<h3>Stack Trace:</h3>" .
                "<pre style='background:#f8f9fa; padding:15px; border:1px solid #ccc; overflow:auto; max-height:400px;'>" . htmlspecialchars($e->getTraceAsString()) . "</pre>" .
                "</div>",
                200
            );
        });
    })->create();

// Arahkan storage path ke /tmp/storage jika di serverless
if (isset($_ENV['VERCEL']) || isset($_SERVER['VERCEL']) || is_dir('/tmp')) {
    $app->useStoragePath('/tmp/storage');
}

return $app;
