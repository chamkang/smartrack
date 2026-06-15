<?php
require_once __DIR__ . '/includes/functions.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect(site_url('contact.php'));
}

if (!verify_csrf($_POST['csrf_token'] ?? '')) {
    redirect(site_url('contact.php?error=invalid_csrf'));
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$subject = trim($_POST['subject'] ?? '');
$message = trim($_POST['message'] ?? '');

if ($name === '' || $email === '' || $message === '') {
    redirect(site_url('contact.php?error=missing_fields'));
}

$stmt = db()->prepare('INSERT INTO contact_messages (name, email, subject, message, created_at) VALUES (:name, :email, :subject, :message, CURRENT_TIMESTAMP)');
$stmt->execute([':name' => $name, ':email' => $email, ':subject' => $subject, ':message' => $message]);
redirect(site_url('contact.php?success=message'));
