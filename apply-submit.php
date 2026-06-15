<?php
require_once __DIR__ . '/includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect(site_url('career.php'));
}

if (!verify_csrf($_POST['csrf_token'] ?? '')) {
    redirect(site_url('career.php?error=csrf'));
}

$jobId     = !empty($_POST['job_id']) ? (int)$_POST['job_id'] : null;
$jobTitle  = trim($_POST['job_title']     ?? '');
$name      = trim($_POST['name']          ?? '');
$email     = trim($_POST['email']         ?? '');
$phone     = trim($_POST['phone']         ?? '');
$letter    = trim($_POST['cover_letter']  ?? '');

if ($name === '' || $email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    redirect(site_url('career.php?error=fields' . ($jobId ? '&jid=' . $jobId : '')));
}

// CV upload (required)
$cv = null;
try {
    $cv = upload_cv('cv');
} catch (RuntimeException $e) {
    redirect(site_url('career.php?error=' . urlencode($e->getMessage()) . ($jobId ? '&jid=' . $jobId : '')));
}

if (!$cv) {
    redirect(site_url('career.php?error=cv_required' . ($jobId ? '&jid=' . $jobId : '')));
}

db()->prepare('
    INSERT INTO job_applications
        (job_id, job_title, name, email, phone, cover_letter, cv_path, cv_original_name, status, created_at)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, \'new\', CURRENT_TIMESTAMP)
')->execute([
    $jobId,
    $jobTitle ?: 'General Application',
    $name,
    $email,
    $phone,
    $letter,
    $cv['path'],
    $cv['original_name'],
]);

redirect(site_url('career.php?success=1' . ($jobId ? '&jid=' . $jobId : '')));
