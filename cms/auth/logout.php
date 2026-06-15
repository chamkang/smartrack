<?php
/**
 * Admin Logout
 * Handles admin session termination
 */

require_once __DIR__ . '/../includes/auth.php';

// Logout the admin
logoutAdmin();

// Redirect to login page
redirect(getBasePath() . '/cms/auth/login.php?logged_out=1');
?>
