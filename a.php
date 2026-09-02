<?php
/**
 * Entry point aplikasi (setara app.js / server.js bagian listen()).
 * Semua request masuk ke sini lalu di-dispatch ke routes.php.
 */

require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/routes.php';

$path = $currentPath;
if ($path !== '/' && str_ends_with($path, '/')) {
    $path = rtrim($path, '/');
}

dispatch($_SERVER['REQUEST_METHOD'], $path);
