<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

try {
    // Setup folder storage writable di /tmp (serverless)
    $storagePath = '/tmp/storage';

    foreach ([
        $storagePath . '/framework/views',
        $storagePath . '/framework/cache/data',
        $storagePath . '/framework/sessions',
        $storagePath . '/logs',
    ] as $dir) {
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
    }

    putenv("APP_STORAGE={$storagePath}");
    $_ENV['APP_STORAGE'] = $storagePath;
    $_SERVER['APP_STORAGE'] = $storagePath;

    putenv("VIEW_COMPILED_PATH={$storagePath}/framework/views");
    $_ENV['VIEW_COMPILED_PATH'] = "{$storagePath}/framework/views";
    $_SERVER['VIEW_COMPILED_PATH'] = "{$storagePath}/framework/views";

    // Register autoloader
    require __DIR__ . '/../vendor/autoload.php';

    // Inisialisasi aplikasi
    /** @var \Illuminate\Foundation\Application $app */
    $app = require_once __DIR__ . '/../bootstrap/app.php';

    // Set storage path
    $app->useStoragePath($storagePath);

    // Handle HTTP request
    $request = Request::capture();
    $response = $app->handleRequest($request);
    $response->send();
} catch (\Throwable $e) {
    http_response_code(200); // Sengaja kita beri 200 agar browser TIDAK menampilkan halaman 'This page isn't working'
    echo "<div style='font-family:sans-serif; padding:20px; background:#fff3f3; border:2px solid red;'>";
    echo "<h1 style='color:red;'>Detail Error Terdeteksi:</h1>";
    echo "<p><strong>Pesan:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p><strong>Class:</strong> " . get_class($e) . "</p>";
    echo "<p><strong>File:</strong> " . htmlspecialchars($e->getFile()) . " (Baris: " . $e->getLine() . ")</p>";
    echo "<h3>Stack Trace:</h3>";
    echo "<pre style='background:#f8f9fa; padding:10px; overflow:auto;'>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    echo "</div>";
}
