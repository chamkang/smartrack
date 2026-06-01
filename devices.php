<?php
require_once __DIR__ . '/includes/functions.php';
$pageTitle = get_translation('n3');
$language = current_language();
define('APP_INIT', true);
include __DIR__ . '/includes/header.php';
?>
<section class="py-5">
  <div class="container">
    <h1><?php echo escape(get_translation('n3')); ?></h1>
    <p><?php echo escape(get_translation('ourtracking')); ?></p>
    <div class="row g-4 mt-4">
      <div class="col-md-4"><div class="card p-4"><h5><?php echo escape(get_translation('ft')); ?></h5><p><?php echo escape(get_translation('tor')); ?></p></div></div>
      <div class="col-md-4"><div class="card p-4"><h5><?php echo escape(get_translation('fuel')); ?></h5><p><?php echo escape(get_translation('ourfuel')); ?></p></div></div>
      <div class="col-md-4"><div class="card p-4"><h5><?php echo escape(get_translation('network')); ?></h5><p><?php echo escape(get_translation('networksys')); ?></p></div></div>
    </div>
  </div>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>