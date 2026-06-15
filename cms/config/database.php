<?php
/**
 * SQLite Database Configuration
 * Handles PDO connection to SQLite database for Smartrack CMS
 */

// Define database file path
define('DB_PATH', __DIR__ . '/../../smartrack.db');

/**
 * Get PDO connection to SQLite database
 * Creates database file if it doesn't exist
 * 
 * @return PDO
 * @throws PDOException
 */
function getDatabase(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    try {
        $pdo = new PDO('sqlite:' . DB_PATH);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        
        // Enable foreign keys
        $pdo->exec('PRAGMA foreign_keys = ON;');
        
        return $pdo;
    } catch (PDOException $e) {
        die('Database Connection Error: ' . $e->getMessage());
    }
}

/**
 * Get database instance (alias for getDatabase)
 * 
 * @return PDO
 */
function db(): PDO
{
    return getDatabase();
}
?>
