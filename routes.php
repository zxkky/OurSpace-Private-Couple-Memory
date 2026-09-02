<?php
/**
 * Router sederhana (setara definisi route di server.js).
 * Setiap route dipetakan ke [METHOD, pattern regex, handler].
 */

function dispatch(string $method, string $path): void
{
    global $currentUser;

    $routes = [
        ['GET',  '#^/$#',                          'homeIndex'],
        ['GET',  '#^/login$#',                     'loginShow'],
        ['POST', '#^/login$#',                     'loginSubmit'],
        ['GET',  '#^/register$#',                  'registerShow'],
        ['POST', '#^/register$#',                  'registerSubmit'],
        ['GET',  '#^/logout$#',                    'logoutAction'],

        ['GET',  '#^/dashboard$#',                 'dashboardShow'],

        ['GET',  '#^/albums$#',                    'albumsIndex'],
        ['GET',  '#^/albums/new$#',                'albumNewShow'],
        ['POST', '#^/albums/new$#',                'albumNewSubmit'],
        ['GET',  '#^/albums/(\d+)$#',               'albumShow'],
        ['GET',  '#^/albums/(\d+)/edit$#',          'albumEditShow'],
        ['POST', '#^/albums/(\d+)/edit$#',          'albumEditSubmit'],
        ['POST', '#^/albums/(\d+)/delete$#',        'albumDelete'],
        ['POST', '#^/albums/(\d+)/upload$#',        'albumUpload'],

        ['GET',  '#^/photos/(\d+)$#',               'photoShow'],
        ['POST', '#^/photos/(\d+)/like$#',          'photoLike'],
        ['POST', '#^/photos/(\d+)/edit$#',          'photoEdit'],
        ['POST', '#^/photos/(\d+)/delete$#',        'photoDelete'],
        ['POST', '#^/photos/(\d+)/comment$#',       'commentAdd'],

        ['POST', '#^/comments/(\d+)/edit$#',        'commentEdit'],
        ['POST', '#^/comments/(\d+)/delete$#',      'commentDelete'],

        ['GET',  '#^/timeline$#',                   'timelineShow'],
        ['GET',  '#^/calendar$#',                    'calendarShow'],
    ];

    foreach ($routes as [$routeMethod, $pattern, $handler]) {
        if ($routeMethod !== $method) {
            continue;
        }
        if (preg_match($pattern, $path, $matches)) {
            array_shift($matches);
            call_user_func($handler, ...$matches);
            return;
        }
    }

    render404();
}

/* ---------------------------------------------------------------- */
/* Home / Auth                                                       */
/* ---------------------------------------------------------------- */

function homeIndex(): void
{
    global $currentUser;
    redirectTo($currentUser ? '/dashboard' : '/login');
}

function loginShow(): void
{
    global $currentUser;
    if ($currentUser) {
        redirectTo('/dashboard');
    }
    renderView('login');
}

function loginSubmit(): void
{
    $username = postStr('username');
    $password = postStr('password');

    if ($username === '' || $password === '' || !attemptLogin($username, $password)) {
        flashError('Username atau password salah.');
        redirectTo('/login');
    }

    redirectTo('/dashboard');
}

function registerShow(): void
{
    global $currentUser;
    if ($currentUser) {
        redirectTo('/dashboard');
    }
    renderView('register');
}

function registerSubmit(): void
{
    $displayName = postStr('display_name');
    $username = postStr('username');
    $password = postStr('password', '');

    $err = attemptRegister($displayName, $username, $password);
    if ($err !== null) {
        flashError($err);
        redirectTo('/register');
    }

    redirectTo('/dashboard');
}

function logoutAction(): void
{
    logout();
    redirectTo('/login');
}

/* ---------------------------------------------------------------- */
/* Dashboard                                                          */
/* ---------------------------------------------------------------- */

function dashboardShow(): void
{
    requireAuth();
    $pdo = getDb();

    $start = new DateTime(RELATIONSHIP_START_DATE);
    $today = new DateTime('today');
    $bersama = $start <= $today ? $start->diff($today)->days : 0;

    $nextAnniv = new DateTime($today->format('Y') . '-' . $start->format('m-d'));
    if ($nextAnniv < $today) {
        $nextAnniv->modify('+1 year');
    }
    $countdown = $today->diff($nextAnniv)->days;

    $totalPhotos = (int) $pdo->query("SELECT COUNT(*) FROM photos WHERE media_type = 'photo'")->fetchColumn();
    $totalVideos = (int) $pdo->query("SELECT COUNT(*) FROM photos WHERE media_type = 'video'")->fetchColumn();
    $totalAlbums = (int) $pdo->query('SELECT COUNT(*) FROM albums')->fetchColumn();

    $stmt = $pdo->prepare(
        'SELECT id, filename, media_type FROM photos
         WHERE taken_at IS NOT NULL
           AND MONTH(taken_at) = MONTH(CURDATE()) AND DAY(taken_at) = DAY(CURDATE())
           AND YEAR(taken_at) < YEAR(CURDATE())
         ORDER BY taken_at DESC'
    );
    $stmt->execute();
    $onThisDay = $stmt->fetchAll();

    $recentPhotos = $pdo->query(
        'SELECT id, filename, media_type FROM photos ORDER BY created_at DESC LIMIT 12'
    )->fetchAll();

    renderView('dashboard', compact(
        'bersama', 'countdown', 'totalPhotos', 'totalVideos', 'totalAlbums', 'onThisDay', 'recentPhotos'
    ));
}

/* ---------------------------------------------------------------- */
/* Albums                                                             */
/* ---------------------------------------------------------------- */

function albumsIndex(): void
{
    requireAuth();
    $albums = getDb()->query(
        "SELECT a.*,
                (SELECT filename FROM photos WHERE album_id = a.id ORDER BY created_at DESC LIMIT 1) AS cover,
                (SELECT COUNT(*) FROM photos WHERE album_id = a.id) AS photo_count
         FROM albums a
         ORDER BY a.created_at DESC"
    )->fetchAll();

    renderView('albums', compact('albums'));
}

function albumNewShow(): void
{
    requireAuth();
    renderView('new_album');
}

function albumNewSubmit(): void
{
    requireAuth();
    global $currentUser;

    $name = postStr('name');
    $category = postStr('category', 'Random');
    $validCategories = ['Liburan', 'Anniversary', 'Date', 'Random'];
    if (!in_array($category, $validCategories, true)) {
        $category = 'Random';
    }

    if ($name === '') {
        flashError('Nama album wajib diisi.');
        redirectTo('/albums/new');
    }

    $stmt = getDb()->prepare('INSERT INTO albums (name, category, created_by) VALUES (?, ?, ?)');
    $stmt->execute([$name, $category, $currentUser['id']]);

    redirectTo('/albums/' . getDb()->lastInsertId());
}

function findAlbumOrFail(string $id): array
{
    $stmt = getDb()->prepare('SELECT * FROM albums WHERE id = ?');
    $stmt->execute([$id]);
    $album = $stmt->fetch();
    if (!$album) {
        render404();
        exit;
    }
    return $album;
}

function albumShow(string $id): void
{
    requireAuth();
    global $currentUser;
    $album = findAlbumOrFail($id);

    $stmt = getDb()->prepare(
        "SELECT p.*,
                (SELECT COUNT(*) FROM likes WHERE photo_id = p.id) AS like_count,
                (SELECT COUNT(*) FROM comments WHERE photo_id = p.id) AS comment_count
         FROM photos p WHERE p.album_id = ? ORDER BY p.created_at DESC"
    );
    $stmt->execute([$id]);
    $photos = $stmt->fetchAll();

    renderView('detail_album', compact('album', 'photos'));
}

function albumEditShow(string $id): void
{
    requireAuth();
    $album = findAlbumOrFail($id);
    renderView('edit_album', compact('album'));
}

function albumEditSubmit(string $id): void
{
    requireAuth();
    $album = findAlbumOrFail($id);

    $name = postStr('name');
    $category = postStr('category', 'Random');
    $validCategories = ['Liburan', 'Anniversary', 'Date', 'Random'];
    if (!in_array($category, $validCategories, true)) {
        $category = 'Random';
    }

    if ($name === '') {
        flashError('Nama album wajib diisi.');
        redirectTo("/albums/$id/edit");
    }

    $stmt = getDb()->prepare('UPDATE albums SET name = ?, category = ? WHERE id = ?');
    $stmt->execute([$name, $category, $id]);

    redirectTo("/albums/$id");
}

function albumDelete(string $id): void
{
    requireAuth();
    findAlbumOrFail($id);
    $pdo = getDb();

    $stmt = $pdo->prepare('SELECT filename FROM photos WHERE album_id = ?');
    $stmt->execute([$id]);
    $files = $stmt->fetchAll(PDO::FETCH_COLUMN);

    $stmt = $pdo->prepare('DELETE FROM albums WHERE id = ?');
    $stmt->execute([$id]);

    foreach ($files as $filename) {
        $path = UPLOAD_DIR . '/' . $filename;
        if (is_file($path)) {
            @unlink($path);
        }
    }

    redirectTo('/albums');
}

function albumUpload(string $id): void
{
    requireAuth();
    global $currentUser;
    findAlbumOrFail($id);

    if (empty($_FILES['media']) || $_FILES['media']['error'] !== UPLOAD_ERR_OK) {
        flashError('Gagal upload: pilih file foto/video terlebih dahulu.');
        redirectTo("/albums/$id");
    }

    $file = $_FILES['media'];

    if ($file['size'] > MAX_UPLOAD_SIZE) {
        flashError('Ukuran file maksimal 20MB.');
        redirectTo("/albums/$id");
    }

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!isset(ALLOWED_MIME[$mime])) {
        flashError('Tipe file tidak didukung. Gunakan JPG, PNG, GIF, WEBP, MP4, WEBM, atau MOV.');
        redirectTo("/albums/$id");
    }

    $mediaType = ALLOWED_MIME[$mime];
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $ext = $ext !== '' ? '.' . preg_replace('/[^a-zA-Z0-9]/', '', $ext) : '';
    $filename = bin2hex(random_bytes(16)) . $ext;

    if (!is_dir(UPLOAD_DIR)) {
        mkdir(UPLOAD_DIR, 0775, true);
    }

    if (!move_uploaded_file($file['tmp_name'], UPLOAD_DIR . '/' . $filename)) {
        flashError('Gagal menyimpan file ke server.');
        redirectTo("/albums/$id");
    }

    $caption = postStr('caption', '') ?: null;
    $location = postStr('location', '') ?: null;
    $takenAt = postStr('taken_at', '') ?: null;

    $stmt = getDb()->prepare(
        'INSERT INTO photos (album_id, filename, media_type, caption, location, taken_at, uploaded_by)
         VALUES (?, ?, ?, ?, ?, ?, ?)'
    );
    $stmt->execute([$id, $filename, $mediaType, $caption, $location, $takenAt, $currentUser['id']]);

    redirectTo("/albums/$id");
}

/* ---------------------------------------------------------------- */
/* Photos                                                             */
/* ---------------------------------------------------------------- */

function findPhotoOrFail(string $id): array
{
    $stmt = getDb()->prepare(
        'SELECT p.*, a.name AS album_name
         FROM photos p JOIN albums a ON a.id = p.album_id
         WHERE p.id = ?'
    );
    $stmt->execute([$id]);
    $photo = $stmt->fetch();
    if (!$photo) {
        render404();
        exit;
    }
    return $photo;
}

function photoShow(string $id): void
{
    requireAuth();
    global $currentUser;
    $photo = findPhotoOrFail($id);

    $stmt = getDb()->prepare('SELECT COUNT(*) FROM likes WHERE photo_id = ? AND user_id = ?');
    $stmt->execute([$id, $currentUser['id']]);
    $photo['liked_by_me'] = (bool) $stmt->fetchColumn();

    $stmt = getDb()->prepare('SELECT COUNT(*) FROM likes WHERE photo_id = ?');
    $stmt->execute([$id]);
    $photo['like_count'] = (int) $stmt->fetchColumn();

    $stmt = getDb()->prepare(
        'SELECT c.*, u.display_name FROM comments c
         JOIN users u ON u.id = c.user_id
         WHERE c.photo_id = ? ORDER BY c.created_at ASC'
    );
    $stmt->execute([$id]);
    $comments = $stmt->fetchAll();

    renderView('detial_photo', compact('photo', 'comments'));
}

function photoLike(string $id): void
{
    requireAuth();
    global $currentUser;
    findPhotoOrFail($id);
    $pdo = getDb();

    $stmt = $pdo->prepare('SELECT id FROM likes WHERE photo_id = ? AND user_id = ?');
    $stmt->execute([$id, $currentUser['id']]);
    $existing = $stmt->fetch();

    if ($existing) {
        $stmt = $pdo->prepare('DELETE FROM likes WHERE id = ?');
        $stmt->execute([$existing['id']]);
    } else {
        $stmt = $pdo->prepare('INSERT INTO likes (photo_id, user_id) VALUES (?, ?)');
        $stmt->execute([$id, $currentUser['id']]);
    }

    redirectTo("/photos/$id");
}

function photoEdit(string $id): void
{
    requireAuth();
    findPhotoOrFail($id);

    $caption = postStr('caption', '') ?: null;
    $location = postStr('location', '') ?: null;
    $takenAt = postStr('taken_at', '') ?: null;

    $stmt = getDb()->prepare('UPDATE photos SET caption = ?, location = ?, taken_at = ? WHERE id = ?');
    $stmt->execute([$caption, $location, $takenAt, $id]);

    redirectTo("/photos/$id");
}

function photoDelete(string $id): void
{
    requireAuth();
    $photo = findPhotoOrFail($id);
    $albumId = $photo['album_id'];

    $stmt = getDb()->prepare('DELETE FROM photos WHERE id = ?');
    $stmt->execute([$id]);

    $path = UPLOAD_DIR . '/' . $photo['filename'];
    if (is_file($path)) {
        @unlink($path);
    }

    redirectTo("/albums/$albumId");
}

/* ---------------------------------------------------------------- */
/* Comments                                                           */
/* ---------------------------------------------------------------- */

function commentAdd(string $photoId): void
{
    requireAuth();
    global $currentUser;
    findPhotoOrFail($photoId);

    $comment = postStr('comment');
    if ($comment !== '') {
        $stmt = getDb()->prepare(
            'INSERT INTO comments (photo_id, user_id, comment) VALUES (?, ?, ?)'
        );
        $stmt->execute([$photoId, $currentUser['id'], $comment]);
    }

    redirectTo("/photos/$photoId");
}

function findCommentOrFail(string $id): array
{
    $stmt = getDb()->prepare('SELECT * FROM comments WHERE id = ?');
    $stmt->execute([$id]);
    $comment = $stmt->fetch();
    if (!$comment) {
        render404();
        exit;
    }
    return $comment;
}

function commentEdit(string $id): void
{
    requireAuth();
    global $currentUser;
    $comment = findCommentOrFail($id);

    if ((int) $comment['user_id'] !== (int) $currentUser['id']) {
        redirectTo('/photos/' . $comment['photo_id']);
    }

    $text = postStr('comment');
    if ($text !== '') {
        $stmt = getDb()->prepare('UPDATE comments SET comment = ? WHERE id = ?');
        $stmt->execute([$text, $id]);
    }

    redirectTo('/photos/' . $comment['photo_id']);
}

function commentDelete(string $id): void
{
    requireAuth();
    global $currentUser;
    $comment = findCommentOrFail($id);
    $photoId = $comment['photo_id'];

    if ((int) $comment['user_id'] === (int) $currentUser['id']) {
        $stmt = getDb()->prepare('DELETE FROM comments WHERE id = ?');
        $stmt->execute([$id]);
    }

    redirectTo("/photos/$photoId");
}

/* ---------------------------------------------------------------- */
/* Timeline & Calendar                                                */
/* ---------------------------------------------------------------- */

function timelineShow(): void
{
    requireAuth();

    $rows = getDb()->query(
        "SELECT id, filename, media_type, caption,
                COALESCE(taken_at, DATE(created_at)) AS effective_date
         FROM photos
         ORDER BY effective_date DESC, created_at DESC"
    )->fetchAll();

    $bulan = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
    ];

    $groups = [];
    foreach ($rows as $row) {
        $ts = strtotime($row['effective_date']);
        $label = $bulan[(int) date('n', $ts)] . ' ' . date('Y', $ts);
        $groups[$label][] = $row;
    }

    renderView('timeline', compact('groups'));
}

function calendarShow(): void
{
    requireAuth();

    $monthNames = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
    ];

    $month = isset($_GET['month']) ? max(1, min(12, (int) $_GET['month'])) : (int) date('n');
    $year = isset($_GET['year']) ? (int) $_GET['year'] : (int) date('Y');

    $currentYear = (int) date('Y');
    $yearOptions = range($currentYear - 5, $currentYear + 1);

    $daysInMonth = (int) date('t', mktime(0, 0, 0, $month, 1, $year));
    $startOffset = (int) date('w', mktime(0, 0, 0, $month, 1, $year)); // 0 = Minggu

    $stmt = getDb()->prepare(
        'SELECT id, filename, media_type, caption, taken_at
         FROM photos
         WHERE taken_at IS NOT NULL AND YEAR(taken_at) = ? AND MONTH(taken_at) = ?
         ORDER BY taken_at ASC, created_at ASC'
    );
    $stmt->execute([$year, $month]);
    $rows = $stmt->fetchAll();

    $photosByDay = [];
    foreach ($rows as $row) {
        $day = (int) date('j', strtotime($row['taken_at']));
        $photosByDay[$day][] = $row;
    }
    ksort($photosByDay);

    renderView('calendar', compact(
        'monthNames', 'month', 'year', 'yearOptions', 'daysInMonth', 'startOffset', 'photosByDay'
    ));
}