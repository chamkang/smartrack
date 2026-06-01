<?php
require_once __DIR__ . '/includes/functions.php';
$pageTitle = get_translation('n4');
$language = current_language();
$contactInfo = db()->query('SELECT * FROM contacts ORDER BY id DESC LIMIT 1')->fetch();
$success = $_GET['success'] ?? '';
$error = $_GET['error'] ?? '';
define('APP_INIT', true);
include __DIR__ . '/includes/header.php';
?>
<section class="py-5">
  <div class="container">
    <div class="row gy-4">
      <div class="col-lg-6">
        <h1><?php echo escape(get_translation('n4')); ?></h1>
        <p><?php echo escape(get_translation('request')); ?></p>
        <?php if ($success === 'quote'): ?>
          <div class="alert alert-success">Your quote request has been saved successfully.</div>
        <?php elseif ($success === 'message'): ?>
          <div class="alert alert-success">Your message has been sent successfully.</div>
        <?php elseif ($error === 'invalid_csrf'): ?>
          <div class="alert alert-danger">Invalid form submission. Please try again.</div>
        <?php elseif ($error === 'missing_fields'): ?>
          <div class="alert alert-danger">Please fill in all required fields.</div>
        <?php endif; ?>
        <div class="mb-4">
          <h5>Contact Information</h5>
          <?php if ($contactInfo): ?>
            <p><strong>Phone:</strong> <?php echo escape($contactInfo['phone']); ?></p>
            <p><strong>Email:</strong> <?php echo escape($contactInfo['email']); ?></p>
            <p><strong>Address:</strong> <?php echo escape($language === 'fr' ? ($contactInfo['address_fr'] ?: $contactInfo['address_en']) : $contactInfo['address_en']); ?></p>
          <?php else: ?>
            <p>Contact details will be updated soon.</p>
          <?php endif; ?>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="card p-4 shadow-sm">
          <h4>Quote Request</h4>
          <form method="post" action="<?php echo escape(site_url('quote-submit.php')); ?>">
            <input type="hidden" name="csrf_token" value="<?php echo escape(csrf_token()); ?>">
            <div class="mb-3"><label class="form-label">Name</label><input class="form-control" name="name" required></div>
            <div class="mb-3"><label class="form-label">Email</label><input class="form-control" type="email" name="email" required></div>
            <div class="mb-3"><label class="form-label">Phone</label><input class="form-control" name="phone" required></div>
            <div class="mb-3"><label class="form-label">Message</label><textarea class="form-control" name="message" rows="5" required></textarea></div>
            <button class="btn btn-primary w-100" type="submit">Send Quote Request</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
<section class="py-5 bg-light">
  <div class="container">
    <div class="row gy-4">
      <div class="col-md-12">
        <h4>General Contact Form</h4>
        <form method="post" action="<?php echo escape(site_url('contact-submit.php')); ?>">
          <input type="hidden" name="csrf_token" value="<?php echo escape(csrf_token()); ?>">
          <div class="row g-3">
            <div class="col-md-6"><input class="form-control" type="text" name="name" placeholder="Name" required></div>
            <div class="col-md-6"><input class="form-control" type="email" name="email" placeholder="Email" required></div>
            <div class="col-md-12"><input class="form-control" type="text" name="subject" placeholder="Subject"></div>
            <div class="col-md-12"><textarea class="form-control" name="message" rows="5" placeholder="Message" required></textarea></div>
          </div>
          <button class="btn btn-secondary mt-3" type="submit">Send Message</button>
        </form>
      </div>
    </div>
  </div>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>