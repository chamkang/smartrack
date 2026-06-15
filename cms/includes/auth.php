<?php
/**
 * Authentication Functions
 * Handles login, logout, session management, and CSRF protection
 */

require_once __DIR__ . '/../config/database.php';

/**
 * Initialize session if not already started
 */
function initSession(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        // Security headers for session - MUST be before session_start()
        session_set_cookie_params([
            'httponly' => true,
            'secure' => isset($_SERVER['HTTPS']),
            'samesite' => 'Lax'
        ]);
        session_start();
    }
}

/**
 * Generate or retrieve CSRF token
 * 
 * @return string
 */
function getCsrfToken(): string
{
    initSession();
    
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    
    return $_SESSION['csrf_token'];
}

/**
 * Verify CSRF token
 * 
 * @param string $token
 * @return bool
 */
function verifyCsrfToken(string $token): bool
{
    initSession();
    
    if (empty($_SESSION['csrf_token'])) {
        return false;
    }
    
    return hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Check if user is logged in
 * 
 * @return bool
 */
function isLoggedIn(): bool
{
    initSession();
    return !empty($_SESSION['admin_id']) && !empty($_SESSION['admin_email']);
}

/**
 * Get current logged-in admin info
 * 
 * @return array|null
 */
function getCurrentAdmin(): ?array
{
    initSession();
    
    if (!isLoggedIn()) {
        return null;
    }
    
    return [
        'id' => $_SESSION['admin_id'],
        'email' => $_SESSION['admin_email'],
        'name' => $_SESSION['admin_name'] ?? 'Admin'
    ];
}

/**
 * Login admin with email and password
 * 
 * @param string $email
 * @param string $password
 * @return array ['success' => bool, 'message' => string]
 */
function loginAdmin(string $email, string $password): array
{
    initSession();
    
    $email = trim($email);
    $password = trim($password);
    
    if (empty($email) || empty($password)) {
        return ['success' => false, 'message' => 'Email and password are required'];
    }
    
    try {
        $stmt = db()->prepare("SELECT id, name, email, password_hash FROM admins WHERE email = ?");
        $stmt->execute([$email]);
        $admin = $stmt->fetch();
        
        if (!$admin) {
            return ['success' => false, 'message' => 'Invalid email or password'];
        }
        
        if (!password_verify($password, $admin['password_hash'])) {
            return ['success' => false, 'message' => 'Invalid email or password'];
        }
        
        // Set session variables
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_email'] = $admin['email'];
        $_SESSION['admin_name'] = $admin['name'];
        $_SESSION['login_time'] = time();
        
        return ['success' => true, 'message' => 'Login successful'];
        
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Database error: ' . $e->getMessage()];
    }
}

/**
 * Logout admin
 */
function logoutAdmin(): void
{
    initSession();
    
    // Destroy session variables
    $_SESSION = [];
    
    // Destroy session cookie
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }
    
    // Destroy session
    session_destroy();
}

/**
 * Require login - redirect if not logged in
 */
function requireLogin(): void
{
    if (!isLoggedIn()) {
        header('Location: ' . getBasePath() . '/cms/auth/login.php');
        exit;
    }
}

/**
 * Get base path for redirects
 * 
 * @return string
 */
function getBasePath(): string
{
    $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
    $parts = explode('/', trim($scriptName, '/'));
    
    // Find 'smartrack' in the path and return everything up to it
    foreach ($parts as $index => $part) {
        if ($part === 'smartrack') {
            return '/' . implode('/', array_slice($parts, 0, $index + 1));
        }
    }
    
    return '';
}

/**
 * Escape output for HTML
 * 
 * @param mixed $value
 * @return string
 */
function escape($value): string
{
    if ($value === null) {
        return '';
    }
    
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

/**
 * Redirect to URL
 * 
 * @param string $url
 * @return void
 */
function redirect(string $url): void
{
    header('Location: ' . $url);
    exit;
}
?>
