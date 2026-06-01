<?php
require_once __DIR__ . '/includes/functions.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect(site_url('index.php'));
}

if (!verify_csrf($_POST['csrf_token'] ?? '')) {
    redirect(site_url('contact.php?error=invalid_csrf'));
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$message = trim($_POST['message'] ?? '');

if ($name === '' || $email === '' || $phone === '' || $message === '') {
    redirect(site_url('contact.php?error=missing_fields'));
}

$stmt = db()->prepare('INSERT INTO quote_requests (name, email, phone, message, created_at) VALUES (:name, :email, :phone, :message, NOW())');
$stmt->execute([':name' => $name, ':email' => $email, ':phone' => $phone, ':message' => $message]);
redirect(site_url('contact.php?success=quote'));
