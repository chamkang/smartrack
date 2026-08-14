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
// The web path from the document root to this folder: '' when the site is the
// whole domain (shared hosting), '/smartrack' when it sits in a subfolder
// (local XAMPP).
//
// Set BASE_URL_OVERRIDE below if a host ever detects it wrongly:
//   define('BASE_URL', '');          // site is the whole domain
if (!defined('BASE_URL')) {
    $base = '';

    $docRootRaw = $_SERVER['DOCUMENT_ROOT'] ?? '';
    if ($docRootRaw !== '') {
        $docRoot    = realpath($docRootRaw);
        $projectDir = realpath(__DIR__);

        if ($docRoot !== false && $projectDir !== false) {
            $docRoot    = rtrim(str_replace('\\', '/', $docRoot), '/');
            $projectDir = rtrim(str_replace('\\', '/', $projectDir), '/');

            // Only trust the result when the project really sits inside the
            // document root. On cPanel these can resolve to different paths
            // (symlinks), and a blind str_replace would leave the full server
            // path — e.g. "/home/user/public_html" — in every asset URL.
            if ($projectDir === $docRoot) {
                $base = '';
            } elseif (str_starts_with($projectDir . '/', $docRoot . '/')) {
                $base = substr($projectDir, strlen($docRoot));
            }
        }
    }

    // A real base path is a short URL fragment. Anything containing a filesystem
    // marker means detection failed, so fall back to the domain root.
    if ($base !== '' && (preg_match('#(^|/)(home|var|usr|srv|Users)(/|$)#', $base) || str_contains($base, ':'))) {
        $base = '';
    }

    define('BASE_URL', $base);
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
