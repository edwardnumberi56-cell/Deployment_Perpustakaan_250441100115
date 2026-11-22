<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Setup folder storage dan cache di /tmp (satu-satunya partisi yang bisa ditulisi di Vercel)
$storagePath = '/tmp/storage';
$cachePath = '/tmp/bootstrap/cache';

foreach ([
    $storagePath . '/framework/views',
    $storagePath . '/framework/cache/data',
    $storagePath . '/framework/sessions',
    $storagePath . '/logs',
    $cachePath,
] as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
}

putenv("APP_STORAGE={$storagePath}");
$_ENV['APP_STORAGE'] = $storagePath;
$_SERVER['APP_STORAGE'] = $storagePath;

putenv("APP_BOOTSTRAP_CACHE_PATH={$cachePath}");
$_ENV['APP_BOOTSTRAP_CACHE_PATH'] = $cachePath;
$_SERVER['APP_BOOTSTRAP_CACHE_PATH'] = $cachePath;

putenv("VIEW_COMPILED_PATH={$storagePath}/framework/views");
$_ENV['VIEW_COMPILED_PATH'] = "{$storagePath}/framework/views";
$_SERVER['VIEW_COMPILED_PATH'] = "{$storagePath}/framework/views";

// Register autoloader
require __DIR__ . '/../vendor/autoload.php';

// Inisialisasi aplikasi
/** @var \Illuminate\Foundation\Application $app */
$app = require_once __DIR__ . '/../bootstrap/app.php';

// Set storage path pada container
$app->useStoragePath($storagePath);

try {
    $request = Request::capture();
    $response = $app->handleRequest($request);
    $response->send();
} catch (\Throwable $e) {
    http_response_code(500);
    echo "<h1>PESAN ASLI ERROR:</h1>";
    echo "<h2 style='color:red;'>" . htmlspecialchars($e->getMessage()) . "</h2>";
    echo "<p><strong>Class:</strong> " . get_class($e) . "</p>";
    echo "<p><strong>File:</strong> " . htmlspecialchars($e->getFile()) . " (Baris: " . $e->getLine() . ")</p>";
    echo "<h3>Stack Trace:</h3>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}
