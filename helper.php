<?php
/**
 * Fungsi bantu umum: escaping, format tanggal/angka Indonesia,
 * render partial/view, redirect, dan flash message.
 */

function h($value): string
{
    return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES, 'UTF-8');
}

function formatNumberId($n): string
{
    return number_format((float) $n, 0, ',', '.');
}

function formatDateId($date): string
{
    if (empty($date)) {
        return '';
    }
    $ts = is_numeric($date) ? (int) $date : strtotime((string) $date);
    if ($ts === false) {
        return '';
    }
    $bulan = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
    ];
    return (int) date('j', $ts) . ' ' . $bulan[(int) date('n', $ts)] . ' ' . date('Y', $ts);
}

/**
 * Render sebuah partial (partials/<name>.php). $currentUser dan
 * $currentPath otomatis tersedia di dalam partial.
 */
function renderPartial(string $name, array $vars = []): void
{
    global $currentUser, $currentPath;
    extract($vars);
    $file = __DIR__ . '/partials/' . $name . '.php';
    if (file_exists($file)) {
        require $file;
    }
}

/**
 * Render sebuah view (view/<name>.php) lengkap dengan layout HTML-nya.
 * $currentUser, $currentPath, dan $error (flash) otomatis tersedia.
 */
function renderView(string $name, array $vars = []): void
{
    global $currentUser, $currentPath;
    if (!array_key_exists('error', $vars)) {
        $vars['error'] = popError();
    }
    extract($vars);
    $file = __DIR__ . '/view/' . $name . '.php';
    if (!file_exists($file)) {
        http_response_code(404);
        require __DIR__ . '/view/404.php';
        return;
    }
    require $file;
}

function render404(): void
{
    http_response_code(404);
    global $currentUser, $currentPath;
    require __DIR__ . '/view/404.php';
}

function redirectTo(string $path): void
{
    header('Location: ' . $path);
    exit;
}

function flashError(string $message): void
{
    $_SESSION['flash_error'] = $message;
}

function popError(): ?string
{
    if (!empty($_SESSION['flash_error'])) {
        $e = $_SESSION['flash_error'];
        unset($_SESSION['flash_error']);
        return $e;
    }
    return null;
}

/** Ambil nilai POST dengan trim, default string kosong. */
function postStr(string $key, string $default = ''): string
{
    return isset($_POST[$key]) ? trim((string) $_POST[$key]) : $default;
}