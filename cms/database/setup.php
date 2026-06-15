<?php
/**
 * Database Setup Script
 * Creates SQLite database and initializes all required tables
 * Run this script once to set up the CMS database
 */

require_once __DIR__ . '/../config/database.php';

try {
    $pdo = db();
    
    // ============================================
    // Create ADMINS table
    // ============================================
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS admins (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            email TEXT UNIQUE NOT NULL,
            password_hash TEXT NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ");
    echo "✓ Created 'admins' table\n";
    
    // ============================================
    // Create WEBSITE_CONTENT table
    // ============================================
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS website_content (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            page_name TEXT NOT NULL,
            section_name TEXT NOT NULL,
            language_code TEXT DEFAULT 'en',
            title TEXT,
            content TEXT,
            image_path TEXT,
            image_alt TEXT,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            UNIQUE(page_name, section_name, language_code)
        )
    ");
    echo "✓ Created 'website_content' table\n";
    
    // ============================================
    // Check if default admin exists
    // ============================================
    $stmt = $pdo->prepare("SELECT email FROM admins WHERE email = ?");
    $stmt->execute(['admin@smartrack.com']);
    
    if ($stmt->fetch() === false) {
        // Create default admin account
        $defaultPassword = 'Admin123!';
        $passwordHash = password_hash($defaultPassword, PASSWORD_BCRYPT);
        
        $stmt = $pdo->prepare("
            INSERT INTO admins (name, email, password_hash) 
            VALUES (?, ?, ?)
        ");
        $stmt->execute(['Administrator', 'admin@smartrack.com', $passwordHash]);
        
        echo "✓ Created default admin account\n";
        echo "\n========================================\n";
        echo "Default Admin Credentials:\n";
        echo "========================================\n";
        echo "Email: admin@smartrack.com\n";
        echo "Password: Admin123!\n";
        echo "========================================\n";
    } else {
        echo "✓ Default admin account already exists\n";
    }
    
    echo "\n✓ Database setup completed successfully!\n";
    echo "Database file: " . DB_PATH . "\n";
    
} catch (PDOException $e) {
    die("\n✗ Setup Error: " . $e->getMessage() . "\n");
}
?>
