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
    $available = ['en', 'fr'];
    if (!empty($_GET['lang']) && in_array($_GET['lang'], $available, true)) {
        setcookie('site_lang', $_GET['lang'], time() + 60 * 60 * 24 * 30, '/', '', false, true);
        return $_GET['lang'];
    }

    if (!empty($_COOKIE['site_lang']) && in_array($_COOKIE['site_lang'], $available, true)) {
        return $_COOKIE['site_lang'];
    }

    return 'en';
}

function get_translation(string $key, string $lang = null): string
{
    $lang = $lang ?: current_language();
    $pdo = db();
    $stmt = $pdo->prepare('SELECT value FROM translations WHERE string_key = :key AND lang = :lang LIMIT 1');
    $stmt->execute([':key' => $key, ':lang' => $lang]);
    $result = $stmt->fetchColumn();
    return $result !== false ? $result : $key;
}

function get_content_value(string $key): ?string
{
    $pdo = db();
    $stmt = $pdo->prepare('SELECT value FROM homepage_content WHERE content_key = :key LIMIT 1');
    $stmt->execute([':key' => $key]);
    return $stmt->fetchColumn() ?: null;
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
        throw new RuntimeException('Image exceeds maximum file size of 4MB.');
    }

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mimeType, ALLOWED_IMAGE_TYPES, true)) {
        throw new RuntimeException('Invalid image file type. Only JPG, PNG, and WEBP are allowed.');
    }

    $extension = match ($mimeType) {
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
        default => throw new RuntimeException('Unsupported image type.'),
    };

    $filename = bin2hex(random_bytes(16)) . '.' . $extension;
    $destinationDir = UPLOAD_BASE_PATH . '/' . trim($targetDir, '/');
    if (!is_dir($destinationDir)) {
        mkdir($destinationDir, 0755, true);
    }

    $destination = $destinationDir . '/' . $filename;

    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        throw new RuntimeException('Failed to save uploaded file.');
    }

    return trim(UPLOAD_BASE_URL, '/') . '/' . trim($targetDir, '/') . '/' . $filename;
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

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mimeType, $allowedMimeTypes, true)) {
        throw new RuntimeException('Invalid file type.');
    }

    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $extension = $extension ? strtolower($extension) : 'dat';
    $filename = bin2hex(random_bytes(16)) . '.' . $extension;
    $destinationDir = UPLOAD_BASE_PATH . '/' . trim($targetDir, '/');
    if (!is_dir($destinationDir)) {
        mkdir($destinationDir, 0755, true);
    }

    $destination = $destinationDir . '/' . $filename;
    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        throw new RuntimeException('Failed to save uploaded file.');
    }

    return trim(UPLOAD_BASE_URL, '/') . '/' . trim($targetDir, '/') . '/' . $filename;
}
