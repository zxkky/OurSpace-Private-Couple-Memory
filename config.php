<?php
/**
 * Konfigurasi aplikasi OurSpace.
 * Sesuaikan bagian "Kredensial Database" di bawah dengan environment Anda
 * (bisa juga diisi lewat environment variable dengan nama yang sama).
 */

function env(string $key, $default = null)
{
    $value = getenv($key);
    return $value === false ? $default : $value;
}

// --- Kredensial Database ---
define('DB_HOST', env('DB_HOST', '127.0.0.1'));
define('DB_PORT', env('DB_PORT', '3306'));
define('DB_NAME', env('DB_NAME', 'ourspace'));
define('DB_USER', env('DB_USER', 'root'));
define('DB_PASS', env('DB_PASS', ''));

// --- Pengaturan Aplikasi ---

// Tanggal mulai hubungan (format Y-m-d), dipakai untuk menghitung
// "sudah X hari bersama" & hitung mundur anniversary di dashboard.
define('RELATIONSHIP_START_DATE', env('RELATIONSHIP_START_DATE', '2023-01-01'));

// Direktori penyimpanan file foto/video yang diupload.
define('UPLOAD_DIR', __DIR__ . '/uploads');

// Ukuran maksimal file upload (bytes). 20MB.
define('MAX_UPLOAD_SIZE', 20 * 1024 * 1024);

// Mime type yang diizinkan untuk diupload -> jenis media.
define('ALLOWED_MIME', [
    'image/jpeg'      => 'photo',
    'image/png'       => 'photo',
    'image/gif'       => 'photo',
    'image/webp'      => 'photo',
    'video/mp4'       => 'video',
    'video/webm'      => 'video',
    'video/quicktime' => 'video',
]);
