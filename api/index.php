<?php
/**
 * Vercel serverless front controller.
 *
 * The vercel-php runtime runs functions from the /api directory, but Smartrack
 * is a classic multi-page PHP app whose entry files live at the project root
 * (index.php, about.php, service.php, admin/*.php, ...). This shim maps the
 * incoming request path to the matching root-level .php file and executes it,
 * so the existing code runs unchanged — and local XAMPP keeps serving the very
 * same files directly (this file is simply ignored there).
 *
 * Static files (assets/, uploads/) are served directly by Vercel via the routes
 * in vercel.json and never reach this router.
 */

$root     = dirname(__DIR__);
$rootReal = realpath($root);

// On Vercel the site lives at the domain root, so pin BASE_URL to '' before
// config.php tries to auto-detect it from DOCUMENT_ROOT (which is wrong here).
if (!defined('BASE_URL')) {
    define('BASE_URL', '');
}

// Resolve the request path to a candidate file under the project root
$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$rel = ltrim(rawurldecode($uri ?? '/'), '/');
if ($rel === '') {
    $rel = 'index.php';
}

$candidate = $root . '/' . $rel;

// "/about" -> "/about.php",  "/admin/" -> "/admin/index.php"
if (is_dir($candidate)) {
    $candidate = rtrim($candidate, '/') . '/index.php';
} elseif (substr($rel, -4) !== '.php' && is_file($candidate . '.php')) {
    $candidate .= '.php';
}

$real = realpath($candidate);

// Validate: file exists, stays inside the project, is a .php page,
// and is not the router itself or a protected internal include.
$ok = $real !== false
    && strpos($real, $rootReal) === 0
    && strtolower(pathinfo($real, PATHINFO_EXTENSION)) === 'php';

if ($ok) {
    $relReal   = str_replace('\\', '/', substr($real, strlen($rootReal) + 1));
    $forbidden = ['api/', 'includes/', 'cms/includes/', 'cms/config/', 'cms/database/'];
    foreach ($forbidden as $f) {
        if (stripos($relReal, $f) === 0) { $ok = false; break; }
    }
}

if (!$ok) {
    http_response_code(404);
    echo 'Page not found.';
    return;
}

// Present the target script as the entry point to the included code
chdir(dirname($real));
$_SERVER['SCRIPT_FILENAME'] = $real;
$_SERVER['SCRIPT_NAME']     = '/' . $relReal;
$_SERVER['PHP_SELF']        = '/' . $relReal;

require $real;
