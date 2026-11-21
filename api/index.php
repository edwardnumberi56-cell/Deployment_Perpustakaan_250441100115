<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

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
$app->handleRequest(Request::capture());
