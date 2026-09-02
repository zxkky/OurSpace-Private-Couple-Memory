<?php
/**
 * Autentikasi berbasis session. OurSpace hanya untuk 2 akun (user & pasangan).
 */

/** Muat user yang sedang login (jika ada) ke variabel global $currentUser. */
function attachCurrentUser(): void
{
    global $currentUser;
    $currentUser = null;

    if (empty($_SESSION['user_id'])) {
        return;
    }

    $stmt = getDb()->prepare('SELECT id, username, display_name FROM users WHERE id = ?');
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();

    if ($user) {
        $currentUser = $user;
    } else {
        // Session mengacu ke user yang sudah tidak ada
        unset($_SESSION['user_id']);
    }
}

/** Middleware: redirect ke /login jika belum login. */
function requireAuth(): void
{
    global $currentUser;
    if (!$currentUser) {
        redirectTo('/login');
    }
}

/** Cek username/password, buat session jika cocok. */
function attemptLogin(string $username, string $password): bool
{
    $stmt = getDb()->prepare('SELECT * FROM users WHERE username = ?');
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = (int) $user['id'];
        return true;
    }

    return false;
}

/**
 * Daftarkan akun baru. Dibatasi maksimal 2 akun total.
 * Mengembalikan pesan error, atau null jika berhasil.
 */
function attemptRegister(string $displayName, string $username, string $password): ?string
{
    if ($displayName === '' || $username === '') {
        return 'Nama panggilan dan username wajib diisi.';
    }
    if (strlen($password) < 6) {
        return 'Password minimal 6 karakter.';
    }

    $pdo = getDb();

    $count = (int) $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
    if ($count >= 2) {
        return 'OurSpace hanya untuk 2 akun (kamu & pasangan). Silakan masuk dengan akun yang sudah ada.';
    }

    $stmt = $pdo->prepare('SELECT id FROM users WHERE username = ?');
    $stmt->execute([$username]);
    if ($stmt->fetch()) {
        return 'Username sudah digunakan.';
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare(
        'INSERT INTO users (username, password_hash, display_name) VALUES (?, ?, ?)'
    );
    $stmt->execute([$username, $hash, $displayName]);

    session_regenerate_id(true);
    $_SESSION['user_id'] = (int) $pdo->lastInsertId();

    return null;
}

function logout(): void
{
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie('PHPSESSID', '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    }
    session_destroy();
}