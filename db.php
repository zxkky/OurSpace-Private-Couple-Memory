<?php
/**
 * Koneksi database (PDO/MySQL), setara pool koneksi di server.js
 */

function getDb(): PDO
{
    static $pdo = null;

    if ($pdo === null) {
        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
            DB_HOST,
            DB_PORT,
            DB_NAME
        );

        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        } catch (PDOException $e) {
            http_response_code(500);
            if (env('APP_ENV', 'production') === 'development') {
                die('Koneksi database gagal: ' . $e->getMessage());
            }
            die('Terjadi kesalahan pada server. Coba lagi nanti.');
        }
    }

    return $pdo;
}