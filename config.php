<?php
// ── Database (SQLite) ─────────────────────────────────────
// On shared hosting the safest place for the database is OUTSIDE the web root,
// so it can never be downloaded even if .htaccess is ignored. If a database
// exists one level above this folder (e.g. /home/user/smartrack-data/ next to
// public_html), it is used automatically; otherwise we fall back to the copy
// beside the site, which .htaccess denies access to.
if (!defined('DB_PATH')) {
    $externalDb = dirname(__DIR__) . '/smartrack-data/smartrack.db';
    define('DB_PATH', is_file($externalDb) ? $externalDb : __DIR__ . '/smartrack.db');
}

// ── Base URL (auto-detected) ──────────────────────────────
// Calculates the web path from document root to this project folder.
// Works correctly whether the site is at / or /smartrack/ or any subfolder.
if (!defined('BASE_URL')) {
    if (isset($_SERVER['DOCUMENT_ROOT']) && $_SERVER['DOCUMENT_ROOT'] !== '') {
        $docRoot    = rtrim(str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT'])), '/');
        $projectDir = rtrim(str_replace('\\', '/', realpath(__DIR__)), '/');
        $base = str_replace($docRoot, '', $projectDir);
        define('BASE_URL', $base === '' ? '' : $base);
    } else {
        // CLI fallback (setup.php, etc.) — no HTTP requests, URL not needed
        define('BASE_URL', '');
    }
}

// ── Uploads ───────────────────────────────────────────────
define('UPLOAD_BASE_PATH', __DIR__ . '/uploads');
define('UPLOAD_BASE_URL',  rtrim(BASE_URL, '/') . '/uploads');

// ── File upload limits ────────────────────────────────────
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/webp']);
define('MAX_UPLOAD_SIZE', 4 * 1024 * 1024); // 4 MB

// ── Session hardening ─────────────────────────────────────
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_samesite', 'Lax');
ini_set('session.use_strict_mode', 1);

// ── Helpers ───────────────────────────────────────────────
function site_url(string $path = ''): string
{
    $path = ltrim($path, '/');
    return rtrim(BASE_URL, '/') . '/' . $path;
}
