<?php
// Database configuration - update these values for your environment.
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'smartrack');
define('DB_USER', 'root');
define('DB_PASS', '');

define('BASE_URL', '');

define('UPLOAD_BASE_PATH', __DIR__ . '/uploads');
define('UPLOAD_BASE_URL', '/uploads');

define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/webp']);
define('MAX_UPLOAD_SIZE', 4 * 1024 * 1024); // 4 MB

// Session settings
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_samesite', 'Lax');
ini_set('session.use_strict_mode', 1);

function site_url(string $path = ''): string
{
    $path = ltrim($path, '/');
    return rtrim(BASE_URL, '/') . '/' . $path;
}
