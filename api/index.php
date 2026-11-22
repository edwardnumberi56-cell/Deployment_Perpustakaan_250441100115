<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

try {
    // Setup folder storage di /tmp (serverless writable disk)
    $storagePath = '/tmp/storage';
    foreach ([
        $storagePath . '/framework/views',
        $storagePath . '/framework/cache/data',
        $storagePath . '/framework/sessions',
        $storagePath . '/logs',
    ] as $dir) {
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
    }

    putenv("APP_STORAGE={$storagePath}");
    $_ENV['APP_STORAGE'] = $storagePath;
    $_SERVER['APP_STORAGE'] = $storagePath;

    putenv("VIEW_COMPILED_PATH={$storagePath}/framework/views");
    $_ENV['VIEW_COMPILED_PATH'] = "{$storagePath}/framework/views";
    $_SERVER['VIEW_COMPILED_PATH'] = "{$storagePath}/framework/views";

    // Register Composer autoloader
    require __DIR__ . '/../vendor/autoload.php';

    // Inisialisasi aplikasi Laravel
    /** @var \Illuminate\Foundation\Application $app */
    $app = require_once __DIR__ . '/../bootstrap/app.php';

    // Set storage path pada container
    $app->useStoragePath($storagePath);

    // Handle request
    $response = $app->handleRequest(Request::capture());
    $response->send();
} catch (\Throwable $e) {
    http_response_code(500);
    echo "<h1>Terjadi Error di Vercel:</h1>";
    echo "<p><strong>Pesan:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p><strong>File:</strong> " . htmlspecialchars($e->getFile()) . " (Baris: " . $e->getLine() . ")</p>";
    echo "<h3>Stack Trace:</h3>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}
