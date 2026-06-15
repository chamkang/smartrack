<?php
/**
 * Unified contact form handler
 * Routes to quote_requests or contact_messages based on form type
 */
require_once __DIR__ . '/includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect(site_url('contact.php'));
}

if (!verify_csrf($_POST['csrf_token'] ?? '')) {
    redirect(site_url('contact.php?error=csrf'));
}

$type    = $_POST['type']    ?? 'message';   // 'quote' or 'message'
$name    = trim($_POST['name']    ?? '');
$email   = trim($_POST['email']   ?? '');
$phone   = trim($_POST['phone']   ?? '');
$subject = trim($_POST['subject'] ?? '');
$message = trim($_POST['message'] ?? '');

// Validate required fields
if ($name === '' || $email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || $message === '') {
    redirect(site_url('contact.php?error=fields&type=' . urlencode($type)));
}

if ($type === 'quote') {
    db()->prepare('
        INSERT INTO quote_requests (name, email, phone, message, created_at)
        VALUES (?, ?, ?, ?, CURRENT_TIMESTAMP)
    ')->execute([$name, $email, $phone, $message]);
} else {
    db()->prepare('
        INSERT INTO contact_messages (name, email, subject, message, created_at)
        VALUES (?, ?, ?, ?, CURRENT_TIMESTAMP)
    ')->execute([$name, $email, $subject, $message]);
}

redirect(site_url('contact.php?success=1&type=' . urlencode($type)));
