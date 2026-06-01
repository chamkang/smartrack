<?php
require_once __DIR__ . '/includes/functions.php';
$pageTitle = 'Smartrack';
$language = current_language();
$heroImage = get_content_value('hero_image') ?: 'assets/img/hero-fallback.jpg';
$heroVideo = get_content_value('hero_video');
$services = db()->query('SELECT * FROM services ORDER BY created_at DESC LIMIT 3')->fetchAll();
$gallery = db()->query('SELECT * FROM gallery ORDER BY created_at DESC LIMIT 6')->fetchAll();
$testimonials = db()->query('SELECT * FROM testimonials ORDER BY created_at DESC LIMIT 3')->fetchAll();
$contactInfo = db()->query('SELECT * FROM contacts ORDER BY id DESC LIMIT 1')->fetch();
define('APP_INIT', true);
include __DIR__ . '/includes/header.php';
?>
<section id="hero" class="hero section py-5" style="position: relative; overflow: hidden;">
  <?php if ($heroVideo): ?>
    <video src="<?php echo escape($heroVideo); ?>" autoplay muted loop playsinline class="w-100 h-100 position-absolute top-0 start-0" style="object-fit:cover;"></video>
  <?php else: ?>
    <img src="<?php echo escape($heroImage); ?>" class="w-100 h-100 position-absolute top-0 start-0" style="object-fit:cover;" alt="Hero">
  <?php endif; ?>
  <div class="container position-relative" style="z-index: 2;">
    <div class="row justify-content-center text-center text-white py-5">
      <div class="col-lg-8">
        <h1><?php echo escape(get_translation('welcome')); ?></h1>
        <p class="lead"><?php echo escape(get_translation('description')); ?></p>
        <a href="<?php echo escape(site_url('contact.php')); ?>" class="btn btn-danger btn-lg"><?php echo escape(get_translation('start')); ?></a>
      </div>
    </div>
  </div>
</section>
<section id="services" class="py-5">
  <div class="container">
    <div class="text-center mb-4">
      <h2><?php echo escape(get_translation('adv')); ?></h2>
      <p><?php echo escape(get_translation('empower')); ?></p>
    </div>
    <div class="row gy-4">
      <?php foreach ($services as $service): ?>
        <div class="col-md-4">
          <div class="card h-100 shadow-sm">
            <?php if (!empty($service['image_path'])): ?>
              <img src="<?php echo escape($service['image_path']); ?>" class="card-img-top" alt="<?php echo escape($service['title_en']); ?>">
            <?php endif; ?>
            <div class="card-body">
              <h5 class="card-title"><?php echo escape($language === 'fr' ? ($service['title_fr'] ?: $service['title_en']) : $service['title_en']); ?></h5>
              <p class="card-text"><?php echo escape($language === 'fr' ? ($service['summary_fr'] ?: $service['summary_en']) : $service['summary_en']); ?></p>
              <a href="<?php echo escape(site_url('service.php?slug=' . urlencode($service['slug']))); ?>" class="btn btn-outline-primary">View Service</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<section id="gallery" class="py-5 bg-light">
  <div class="container">
    <div class="text-center mb-4"><h2><?php echo escape(get_translation('blog')); ?></h2></div>
    <div class="row g-3">
      <?php foreach ($gallery as $item): ?>
        <div class="col-md-4">
          <div class="card">
            <img src="<?php echo escape($item['image_path']); ?>" class="card-img-top" alt="Gallery">
            <div class="card-body"><p class="card-text"><?php echo escape($language === 'fr' ? ($item['description_fr'] ?: $item['description_en']) : $item['description_en']); ?></p></div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<section id="testimonials" class="py-5">
  <div class="container">
    <div class="text-center mb-4"><h2><?php echo escape(get_translation('testimony')); ?></h2></div>
    <div class="row gy-4">
      <?php foreach ($testimonials as $item): ?>
        <div class="col-md-4">
          <div class="card p-4 h-100 shadow-sm">
            <p>“<?php echo escape($language === 'fr' ? ($item['quote_fr'] ?: $item['quote_en']) : $item['quote_en']); ?>”</p>
            <p class="mb-1"><strong><?php echo escape($language === 'fr' ? ($item['author_fr'] ?: $item['author_en']) : $item['author_en']); ?></strong></p>
            <small><?php echo escape($language === 'fr' ? ($item['role_fr'] ?: $item['role_en']) : $item['role_en']); ?></small>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<section id="contact-preview" class="py-5 bg-dark text-white">
  <div class="container text-center">
    <h2><?php echo escape(get_translation('request')); ?></h2>
    <p><?php echo escape(get_translation('form')); ?></p>
    <a href="<?php echo escape(site_url('contact.php')); ?>" class="btn btn-outline-light"><?php echo escape(get_translation('quote')); ?></a>
  </div>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>