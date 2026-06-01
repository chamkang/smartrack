<?php
if (!defined('APP_INIT_ADMIN')) {
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo escape($pageTitle ?? 'Admin'); ?></title>
  <link href="<?php echo escape(site_url('assets/vendor/bootstrap/css/bootstrap.min.css')); ?>" rel="stylesheet">
  <style>body{min-height:100vh;background:#f8f9fa;} .admin-nav a{margin-right:16px;} .form-error{color:#b00020;}</style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="dashboard.php">Smartrack Admin</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNav" aria-controls="adminNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="adminNav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="services.php">Services</a></li>
        <li class="nav-item"><a class="nav-link" href="gallery.php">Gallery</a></li>
        <li class="nav-item"><a class="nav-link" href="testimonials.php">Testimonials</a></li>
        <li class="nav-item"><a class="nav-link" href="homepage.php">Homepage</a></li>
        <li class="nav-item"><a class="nav-link" href="contact-info.php">Contact Info</a></li>
        <li class="nav-item"><a class="nav-link" href="quotes.php">Quote Requests</a></li>
        <li class="nav-item"><a class="nav-link" href="messages.php">Messages</a></li>
        <li class="nav-item"><a class="nav-link" href="translations.php">Translations</a></li>
      </ul>
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
      </ul>
    </div>
  </div>
</nav>
<div class="container py-4">