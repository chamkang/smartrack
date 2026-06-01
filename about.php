<?php
require_once __DIR__ . '/includes/functions.php';
$pageTitle = get_translation('abou');
$language = current_language();
define('APP_INIT', true);
include __DIR__ . '/includes/header.php';
?>
<section class="py-5">
  <div class="container">
    <div class="row gy-4 align-items-center">
      <div class="col-lg-6">
        <h1><?php echo escape(get_translation('wh')); ?></h1>
        <p><?php echo escape(get_translation('lead')); ?></p>
      </div>
      <div class="col-lg-6">
        <img src="<?php echo escape(site_url('assets/img/about.jpg')); ?>" class="img-fluid rounded" alt="About Smartrack">
      </div>
    </div>
  </div>
</section>
<section class="py-5 bg-light">
  <div class="container">
    <h2><?php echo escape(get_translation('since')); ?></h2>
    <div class="row gy-4">
      <div class="col-md-4"><div class="card p-4 text-center"><h3>38K+</h3><p><?php echo escape(get_translation('happy')); ?></p></div></div>
      <div class="col-md-4"><div class="card p-4 text-center"><h3>166</h3><p><?php echo escape(get_translation('proj')); ?></p></div></div>
      <div class="col-md-4"><div class="card p-4 text-center"><h3>24/7</h3><p><?php echo escape(get_translation('hours')); ?></p></div></div>
    </div>
  </div>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>