<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Setup folder storage writable di /tmp
$storageDir = '/tmp/storage';
foreach ([
    $storageDir . '/framework/views',
    $storageDir . '/framework/cache/data',
    $storageDir . '/framework/sessions',
    $storageDir . '/logs',
] as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
}

// Autoloader composer
require __DIR__ . '/../vendor/autoload.php';

// Inisialisasi aplikasi Laravel
$app = require_once __DIR__ . '/../bootstrap/app.php';

// Pastikan storage path menggunakan /tmp/storage
$app->useStoragePath($storageDir);

// Jalankan request
$app->handleRequest(Request::capture());
