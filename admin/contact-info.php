<?php
require_once __DIR__ . '/../includes/functions.php';
require_admin();
$pageTitle = 'Contact Information';
$error = '';
$contact = db()->query('SELECT * FROM contacts ORDER BY id DESC LIMIT 1')->fetch();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid CSRF token.';
    } else {
        $phone = trim($_POST['phone'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $addressEn = trim($_POST['address_en'] ?? '');
        $addressFr = trim($_POST['address_fr'] ?? '');
        $facebook = trim($_POST['facebook'] ?? '');
        $twitter = trim($_POST['twitter'] ?? '');
        $instagram = trim($_POST['instagram'] ?? '');
        $linkedin = trim($_POST['linkedin'] ?? '');

        if ($phone === '' || $email === '') {
            $error = 'Phone and email are required.';
        } else {
            if ($contact) {
                $stmt = db()->prepare('UPDATE contacts SET phone = :phone, email = :email, address_en = :address_en, address_fr = :address_fr, facebook = :facebook, twitter = :twitter, instagram = :instagram, linkedin = :linkedin, updated_at = NOW() WHERE id = :id');
                $stmt->execute([':phone' => $phone, ':email' => $email, ':address_en' => $addressEn, ':address_fr' => $addressFr, ':facebook' => $facebook, ':twitter' => $twitter, ':instagram' => $instagram, ':linkedin' => $linkedin, ':id' => $contact['id']]);
            } else {
                $stmt = db()->prepare('INSERT INTO contacts (phone, email, address_en, address_fr, facebook, twitter, instagram, linkedin, created_at, updated_at) VALUES (:phone, :email, :address_en, :address_fr, :facebook, :twitter, :instagram, :linkedin, NOW(), NOW())');
                $stmt->execute([':phone' => $phone, ':email' => $email, ':address_en' => $addressEn, ':address_fr' => $addressFr, ':facebook' => $facebook, ':twitter' => $twitter, ':instagram' => $instagram, ':linkedin' => $linkedin]);
            }
            redirect('contact-info.php');
        }
    }
}
define('APP_INIT_ADMIN', true);
include __DIR__ . '/_header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <h1>Contact Information</h1>
</div>
<?php if ($error): ?>
  <div class="alert alert-danger"><?php echo escape($error); ?></div>
<?php endif; ?>
<div class="card p-4">
  <form method="post">
    <input type="hidden" name="csrf_token" value="<?php echo escape(csrf_token()); ?>">
    <div class="row g-3">
      <div class="col-md-6"><label class="form-label">Phone</label><input class="form-control" name="phone" value="<?php echo escape($contact['phone'] ?? ''); ?>" required></div>
      <div class="col-md-6"><label class="form-label">Email</label><input class="form-control" type="email" name="email" value="<?php echo escape($contact['email'] ?? ''); ?>" required></div>
      <div class="col-md-12"><label class="form-label">Address (EN)</label><textarea class="form-control" name="address_en" rows="2"><?php echo escape($contact['address_en'] ?? ''); ?></textarea></div>
      <div class="col-md-12"><label class="form-label">Address (FR)</label><textarea class="form-control" name="address_fr" rows="2"><?php echo escape($contact['address_fr'] ?? ''); ?></textarea></div>
      <div class="col-md-6"><label class="form-label">Facebook</label><input class="form-control" name="facebook" value="<?php echo escape($contact['facebook'] ?? ''); ?>"></div>
      <div class="col-md-6"><label class="form-label">Twitter</label><input class="form-control" name="twitter" value="<?php echo escape($contact['twitter'] ?? ''); ?>"></div>
      <div class="col-md-6"><label class="form-label">Instagram</label><input class="form-control" name="instagram" value="<?php echo escape($contact['instagram'] ?? ''); ?>"></div>
      <div class="col-md-6"><label class="form-label">LinkedIn</label><input class="form-control" name="linkedin" value="<?php echo escape($contact['linkedin'] ?? ''); ?>"></div>
    </div>
    <button class="btn btn-primary mt-4" type="submit">Save Contact Info</button>
  </form>
</div>
<?php include __DIR__ . '/_footer.php'; ?>