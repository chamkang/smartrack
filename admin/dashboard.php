<?php
require_once __DIR__ . '/../includes/functions.php';
require_admin();
$pageTitle = 'Admin Dashboard';
$counts = [];
$pdo = db();
$tables = ['services', 'gallery', 'testimonials', 'quote_requests', 'contact_messages'];
foreach ($tables as $table) {
    $stmt = $pdo->query("SELECT COUNT(*) FROM {$table}");
    $counts[$table] = $stmt->fetchColumn();
}
$contact = $pdo->query('SELECT phone, email FROM contacts ORDER BY id DESC LIMIT 1')->fetch();

define('APP_INIT', true);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo escape($pageTitle); ?></title>
  <link href="<?php echo escape(site_url('assets/vendor/bootstrap/css/bootstrap.min.css')); ?>" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Smartrack Admin</a>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link active" href="dashboard.php">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="services.php">Services</a></li>
        <li class="nav-item"><a class="nav-link" href="gallery.php">Gallery</a></li>
        <li class="nav-item"><a class="nav-link" href="testimonials.php">Testimonials</a></li>
        <li class="nav-item"><a class="nav-link" href="contact-info.php">Contact Info</a></li>
        <li class="nav-item"><a class="nav-link" href="quotes.php">Quotes</a></li>
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
  <h1 class="mb-4">Admin Dashboard</h1>
  <div class="row g-4">
    <div class="col-md-4"><div class="card p-3"><h5>Services</h5><p class="display-6"><?php echo escape($counts['services']); ?></p></div></div>
    <div class="col-md-4"><div class="card p-3"><h5>Gallery Items</h5><p class="display-6"><?php echo escape($counts['gallery']); ?></p></div></div>
    <div class="col-md-4"><div class="card p-3"><h5>Testimonials</h5><p class="display-6"><?php echo escape($counts['testimonials']); ?></p></div></div>
    <div class="col-md-4"><div class="card p-3"><h5>Quote Requests</h5><p class="display-6"><?php echo escape($counts['quote_requests']); ?></p></div></div>
    <div class="col-md-4"><div class="card p-3"><h5>Contact Messages</h5><p class="display-6"><?php echo escape($counts['contact_messages']); ?></p></div></div>
  </div>
  <?php if ($contact): ?>
  <div class="mt-4">
    <h5>Contact Info</h5>
    <p><strong>Phone:</strong> <?php echo escape($contact['phone']); ?></p>
    <p><strong>Email:</strong> <?php echo escape($contact['email']); ?></p>
  </div>
  <?php endif; ?>
</div>
<script src="<?php echo escape(site_url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js')); ?>"></script>
</body>
</html>