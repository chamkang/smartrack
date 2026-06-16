<?php
require_once __DIR__ . '/../config.php';

/**
 * Which database engine is active this request:
 *   'pgsql'  → Supabase / Postgres (production, e.g. Vercel) — when DB env vars are set
 *   'sqlite' → local development (default, no env vars) — unchanged behaviour
 *
 * Used by query code that must differ between the two dialects
 * (e.g. "INSERT OR REPLACE" in SQLite vs "INSERT ... ON CONFLICT" in Postgres).
 */
function db_driver(): string
{
    static $driver = null;
    if ($driver !== null) {
        return $driver;
    }
    $driver = (getenv('DATABASE_URL') || getenv('DB_HOST')) ? 'pgsql' : 'sqlite';
    return $driver;
}

function db(): PDO
{
    static $pdo;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];

    if (db_driver() === 'pgsql') {
        // ── Postgres / Supabase ───────────────────────────────────────────────
        // Accept either a single DATABASE_URL (the libpq URI Supabase shows) or
        // discrete DB_HOST / DB_PORT / DB_NAME / DB_USER / DB_PASSWORD vars.
        $url = getenv('DATABASE_URL');
        if ($url) {
            $p    = parse_url($url) ?: [];
            $host = $p['host'] ?? '';
            $port = $p['port'] ?? 6543;
            $name = isset($p['path']) ? ltrim($p['path'], '/') : 'postgres';
            $user = isset($p['user']) ? rawurldecode($p['user']) : '';
            $pass = isset($p['pass']) ? rawurldecode($p['pass']) : '';
        } else {
            $host = getenv('DB_HOST') ?: '';
            $port = getenv('DB_PORT') ?: '6543';
            $name = getenv('DB_NAME') ?: 'postgres';
            $user = getenv('DB_USER') ?: '';
            $pass = getenv('DB_PASSWORD') ?: '';
        }

        // Supabase requires SSL. Emulated prepares keep us compatible with the
        // transaction pooler (port 6543), which doesn't support server-side
        // prepared statements.
        $options[PDO::ATTR_EMULATE_PREPARES] = true;

        $dsn = sprintf('pgsql:host=%s;port=%s;dbname=%s;sslmode=require', $host, $port, $name);
        $pdo = new PDO($dsn, $user, $pass, $options);
    } else {
        // ── SQLite (local development) ────────────────────────────────────────
        $pdo = new PDO('sqlite:' . DB_PATH, null, null, $options);
        $pdo->exec('PRAGMA foreign_keys = ON;');
        $pdo->exec('PRAGMA journal_mode = WAL;');
    }

    return $pdo;
}
