<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

$tmpStorage = '/tmp/storage';

foreach ([
    $tmpStorage . '/framework/views',
    $tmpStorage . '/framework/cache/data',
    $tmpStorage . '/framework/sessions',
    $tmpStorage . '/logs',
    '/tmp/bootstrap/cache',
] as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
}

putenv("APP_STORAGE={$tmpStorage}");
$_ENV['APP_STORAGE'] = $tmpStorage;
$_SERVER['APP_STORAGE'] = $tmpStorage;

$viewCompiledPath = $tmpStorage . '/framework/views';
putenv("VIEW_COMPILED_PATH={$viewCompiledPath}");
$_ENV['VIEW_COMPILED_PATH'] = $viewCompiledPath;
$_SERVER['VIEW_COMPILED_PATH'] = $viewCompiledPath;

try {
    require __DIR__ . '/../public/index.php';
} catch (\Throwable $e) {
    echo "<h1>Terjadi Exception di Vercel:</h1>";
    echo "<p><strong>Pesan:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p><strong>File:</strong> " . htmlspecialchars($e->getFile()) . " pada baris " . $e->getLine() . "</p>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}
