<?php
/**
 * Bootstrap aplikasi: setara bagian atas server.js
 * (load env, session, koneksi db, helper, middleware attachUser).
 */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/helper.php';
require_once __DIR__ . '/auth.php';

// Setara express-session (cookie maxAge 30 hari)
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 60 * 60 * 24 * 30, // 30 hari
        'path' => '/',
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

// Path saat ini (dipakai partials/navbar.php untuk highlight menu aktif)
$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';

// Setara middleware attachUser: muat user yang sedang login ke $currentUser
attachCurrentUser();
