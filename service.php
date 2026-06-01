<?php
require_once __DIR__ . '/includes/functions.php';
$pageTitle = 'Service';
$language = current_language();
$slug = trim($_GET['slug'] ?? '');
if ($slug === '') {
    redirect(site_url('index.php'));
}
$stmt = db()->prepare('SELECT * FROM services WHERE slug = :slug LIMIT 1');
$stmt->execute([':slug' => $slug]);
$service = $stmt->fetch();
if (!$service) {
    http_response_code(404);
    echo 'Service not found.';
    exit;
}
$title = $language === 'fr' ? ($service['title_fr'] ?: $service['title_en']) : $service['title_en'];
$summary = $language === 'fr' ? ($service['summary_fr'] ?: $service['summary_en']) : $service['summary_en'];
$content = $language === 'fr' ? ($service['content_fr'] ?: $service['content_en']) : $service['content_en'];
$pageTitle = $title;
define('APP_INIT', true);
include __DIR__ . '/includes/header.php';
?>
<section class="py-5">
  <div class="container">
    <div class="row gy-4">
      <div class="col-lg-6">
        <?php if (!empty($service['image_path'])): ?>
          <img src="<?php echo escape($service['image_path']); ?>" class="img-fluid rounded" alt="<?php echo escape($title); ?>">
        <?php endif; ?>
      </div>
      <div class="col-lg-6">
        <h1><?php echo escape($title); ?></h1>
        <p class="lead"><?php echo escape($summary); ?></p>
        <div><?php echo nl2br(escape($content)); ?></div>
      </div>
    </div>
  </div>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>