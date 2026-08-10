<?php
require_once __DIR__ . '/db.php';

function init_session(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function escape(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function redirect(string $url): void
{
    header('Location: ' . $url);
    exit;
}

function csrf_token(): string
{
    init_session();

    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(24));
    }

    return $_SESSION['csrf_token'];
}

function verify_csrf(string $token): bool
{
    init_session();
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

function is_logged_in(): bool
{
    init_session();
    return !empty($_SESSION['admin_id']);
}

function require_admin(): void
{
    if (!is_logged_in()) {
        redirect(site_url('admin/login.php'));
    }
}

function admin_user(): array
{
    init_session();
    return $_SESSION['admin'] ?? [];
}

function current_language(): string
{
    // Resolve once per request and cache — this function is called many times
    // during rendering (every t() / get_translation() call), but the cookie
    // must only be set once, before any output is sent.
    static $resolved = null;
    if ($resolved !== null) {
        return $resolved;
    }

    $available = ['en', 'fr'];
    if (!empty($_GET['lang']) && in_array($_GET['lang'], $available, true)) {
        $resolved = $_GET['lang'];
        // Only attempt to set the cookie if headers have not yet been sent,
        // otherwise it triggers a "headers already sent" warning.
        if (!headers_sent()) {
            setcookie('site_lang', $resolved, time() + 60 * 60 * 24 * 30, '/', '', false, true);
        }
        $_COOKIE['site_lang'] = $resolved; // reflect choice for the rest of this request
        return $resolved;
    }

    if (!empty($_COOKIE['site_lang']) && in_array($_COOKIE['site_lang'], $available, true)) {
        $resolved = $_COOKIE['site_lang'];
        return $resolved;
    }

    $resolved = 'en';
    return $resolved;
}

/**
 * Return English or French string based on current language.
 * Use for inline static content: <?php echo t('Hello', 'Bonjour'); ?>
 */
function t(string $en, string $fr = ''): string {
    return current_language() === 'fr' && $fr !== '' ? $fr : $en;
}

/**
 * Bulk-load all translations for a language into a cached array.
 * Call once per page: $tr = load_translations();
 * Then use: $tr['key'] ?? 'fallback'
 */
function load_translations(?string $lang = null): array {
    $lang = $lang ?: current_language();
    static $cache = [];
    if (!isset($cache[$lang])) {
        $stmt = db()->prepare('SELECT string_key, value FROM translations WHERE lang = ?');
        $stmt->execute([$lang]);
        $cache[$lang] = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    }
    return $cache[$lang];
}

function get_translation(string $key, ?string $lang = null): string
{
    $lang = $lang ?: current_language();
    $tr   = load_translations($lang);
    return $tr[$key] ?? $key;
}

/**
 * Bulk-load every homepage_content row once per request.
 * The homepage alone reads ~20 keys (hero + promo carousel); without this each
 * one was a separate query.
 */
function load_content_values(bool $refresh = false): array
{
    static $cache = null;
    if ($cache === null || $refresh) {
        $cache = db()->query('SELECT content_key, value FROM homepage_content')
                     ->fetchAll(PDO::FETCH_KEY_PAIR);
    }
    return $cache;
}

function get_content_value(string $key): ?string
{
    $all = load_content_values();
    return array_key_exists($key, $all) ? $all[$key] : null;
}

function set_content_value(string $key, string $value): void
{
    if (db_driver() === 'pgsql') {
        db()->prepare('
            INSERT INTO homepage_content (content_key, value, updated_at)
            VALUES (?, ?, CURRENT_TIMESTAMP)
            ON CONFLICT (content_key) DO UPDATE
              SET value = EXCLUDED.value, updated_at = CURRENT_TIMESTAMP
        ')->execute([$key, $value]);
    } else {
        db()->prepare('
            INSERT OR REPLACE INTO homepage_content (content_key, value, updated_at)
            VALUES (?, ?, CURRENT_TIMESTAMP)
        ')->execute([$key, $value]);
    }
    load_content_values(true); // refresh cache so admin saves show immediately
}

function get_contact(): array
{
    $row = db()->query('SELECT * FROM contacts ORDER BY id DESC LIMIT 1')->fetch();
    return $row ?: [];
}

function upload_image(string $fieldName, string $targetDir): ?string
{
    if (empty($_FILES[$fieldName]) || $_FILES[$fieldName]['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }

    $file = $_FILES[$fieldName];

    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Upload failed with code ' . $file['error']);
    }

    if ($file['size'] > MAX_UPLOAD_SIZE) {
        throw new RuntimeException('Image exceeds maximum file size of 4 MB.');
    }

    $finfo    = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mimeType, ALLOWED_IMAGE_TYPES, true)) {
        throw new RuntimeException('Invalid image type. Only JPG, PNG, and WebP are allowed.');
    }

    $extension = match ($mimeType) {
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp',
        default      => throw new RuntimeException('Unsupported image type.'),
    };

    $filename       = bin2hex(random_bytes(16)) . '.' . $extension;
    $destinationDir = UPLOAD_BASE_PATH . '/' . trim($targetDir, '/');

    if (!is_dir($destinationDir)) {
        mkdir($destinationDir, 0755, true);
    }

    $destination = $destinationDir . '/' . $filename;

    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        throw new RuntimeException('Failed to save uploaded file.');
    }

    return '/' . trim(UPLOAD_BASE_URL, '/') . '/' . trim($targetDir, '/') . '/' . $filename;
}

function upload_cv(string $fieldName): ?array
{
    if (empty($_FILES[$fieldName]) || $_FILES[$fieldName]['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }

    $file = $_FILES[$fieldName];

    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Upload failed with error code ' . $file['error']);
    }

    $maxSize = 5 * 1024 * 1024; // 5 MB
    if ($file['size'] > $maxSize) {
        throw new RuntimeException('CV file exceeds the 5 MB size limit.');
    }

    $finfo    = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    $allowed = [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    ];

    if (!in_array($mimeType, $allowed, true)) {
        throw new RuntimeException('Only PDF, DOC, and DOCX files are accepted for CVs.');
    }

    // SECURITY: derive the extension from the verified MIME type, never from the
    // user-supplied filename — otherwise "cv.php" with DOC-looking bytes would be
    // saved as an executable .php file.
    $ext = match ($mimeType) {
        'application/pdf'  => 'pdf',
        'application/msword' => 'doc',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
        default => throw new RuntimeException('Unsupported CV file type.'),
    };
    $filename = time() . '_' . bin2hex(random_bytes(8)) . '.' . $ext;

    $dir = UPLOAD_BASE_PATH . '/cvs';
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }

    $destination = $dir . '/' . $filename;
    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        throw new RuntimeException('Failed to save the uploaded CV.');
    }

    return [
        'path'          => '/' . trim(UPLOAD_BASE_URL, '/') . '/cvs/' . $filename,
        'original_name' => $file['name'],
    ];
}

function upload_media(string $fieldName, string $targetDir, array $allowedMimeTypes, int $maxSize): ?string
{
    if (empty($_FILES[$fieldName]) || $_FILES[$fieldName]['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }

    $file = $_FILES[$fieldName];

    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Upload failed with code ' . $file['error']);
    }

    if ($file['size'] > $maxSize) {
        throw new RuntimeException('File exceeds maximum allowed size.');
    }

    $finfo    = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mimeType, $allowedMimeTypes, true)) {
        throw new RuntimeException('Invalid file type.');
    }

    // SECURITY: map the verified MIME type to a safe extension. Never trust the
    // extension in the uploaded file's name — it could be ".php".
    $mimeExt = [
        'image/jpeg' => 'jpg',  'image/png'  => 'png',   'image/webp' => 'webp',
        'image/gif'  => 'gif',  'video/mp4'  => 'mp4',   'video/webm' => 'webm',
        'application/pdf' => 'pdf',
    ];
    if (!isset($mimeExt[$mimeType])) {
        throw new RuntimeException('Unsupported file type.');
    }
    $extension      = $mimeExt[$mimeType];
    $filename       = bin2hex(random_bytes(16)) . '.' . $extension;
    $destinationDir = UPLOAD_BASE_PATH . '/' . trim($targetDir, '/');

    if (!is_dir($destinationDir)) {
        mkdir($destinationDir, 0755, true);
    }

    $destination = $destinationDir . '/' . $filename;

    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        throw new RuntimeException('Failed to save uploaded file.');
    }

    return '/' . trim(UPLOAD_BASE_URL, '/') . '/' . trim($targetDir, '/') . '/' . $filename;
}
