<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Setup folder storage dan cache di /tmp (serverless writable directory)
$storagePath = '/tmp/storage';
$cacheDir    = '/tmp/storage/cache';

foreach ([
    $storagePath . '/framework/views',
    $storagePath . '/framework/cache/data',
    $storagePath . '/framework/sessions',
    $storagePath . '/logs',
    $cacheDir,
] as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
}

// 1. Arahkan semua cache internal Laravel ke /tmp
putenv("APP_STORAGE={$storagePath}");
$_ENV['APP_STORAGE'] = $storagePath;
$_SERVER['APP_STORAGE'] = $storagePath;

putenv("APP_SERVICES_CACHE={$cacheDir}/services.php");
$_ENV['APP_SERVICES_CACHE'] = "{$cacheDir}/services.php";
$_SERVER['APP_SERVICES_CACHE'] = "{$cacheDir}/services.php";

putenv("APP_PACKAGES_CACHE={$cacheDir}/packages.php");
$_ENV['APP_PACKAGES_CACHE'] = "{$cacheDir}/packages.php";
$_SERVER['APP_PACKAGES_CACHE'] = "{$cacheDir}/packages.php";

putenv("APP_CONFIG_CACHE={$cacheDir}/config.php");
$_ENV['APP_CONFIG_CACHE'] = "{$cacheDir}/config.php";
$_SERVER['APP_CONFIG_CACHE'] = "{$cacheDir}/config.php";

putenv("APP_ROUTES_CACHE={$cacheDir}/routes.php");
$_ENV['APP_ROUTES_CACHE'] = "{$cacheDir}/routes.php";
$_SERVER['APP_ROUTES_CACHE'] = "{$cacheDir}/routes.php";

putenv("APP_EVENTS_CACHE={$cacheDir}/events.php");
$_ENV['APP_EVENTS_CACHE'] = "{$cacheDir}/events.php";
$_SERVER['APP_EVENTS_CACHE'] = "{$cacheDir}/events.php";

putenv("VIEW_COMPILED_PATH={$storagePath}/framework/views");
$_ENV['VIEW_COMPILED_PATH'] = "{$storagePath}/framework/views";
$_SERVER['VIEW_COMPILED_PATH'] = "{$storagePath}/framework/views";

// 2. Register autoloader Composer
require __DIR__ . '/../vendor/autoload.php';

// 3. Inisialisasi aplikasi Laravel
/** @var \Illuminate\Foundation\Application $app */
$app = require_once __DIR__ . '/../bootstrap/app.php';

// 4. Set storage path
$app->useStoragePath($storagePath);

// 5. Tangani HTTP request sesuai standar Laravel 11
$app->handleRequest(Request::capture());
