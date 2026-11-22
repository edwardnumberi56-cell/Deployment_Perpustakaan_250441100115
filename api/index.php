<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

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

// Tangkap dan render exception menggunakan renderer Symfony bawaan secara langsung (tanpa memanggil 'view')
try {
    $request = Request::capture();
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    
    // Override method renderException pada handler untuk mencetak pesan asli jika ada error
    $handler = $app->make(Illuminate\Contracts\Debug\ExceptionHandler::class);
    
    $response = $kernel->handle($request);
    $response->send();
    $kernel->terminate($request, $response);
} catch (\Throwable $e) {
    echo "<div style='font-family:sans-serif; padding:20px; background:#fff3cd; border:2px solid orange;'>";
    echo "<h1>PESAN ASLI PENYEBAB ERROR:</h1>";
    echo "<h2 style='color:darkred;'>" . htmlspecialchars($e->getMessage()) . "</h2>";
    echo "<p><strong>Class:</strong> " . get_class($e) . "</p>";
    echo "<p><strong>File:</strong> " . htmlspecialchars($e->getFile()) . " (Baris: " . $e->getLine() . ")</p>";
    echo "<h3>Stack Trace:</h3>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    echo "</div>";
}
