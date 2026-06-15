<?php
require_once __DIR__ . '/../includes/functions.php';
require_admin();
$pageTitle = 'Contact Info';
$error = '';
$saved = false;

$contact = get_contact();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid security token.';
    } else {
        $phone     = trim($_POST['phone']      ?? '');
        $email     = trim($_POST['email']      ?? '');
        $addrEn    = trim($_POST['address_en'] ?? '');
        $addrFr    = trim($_POST['address_fr'] ?? '');
        $facebook  = trim($_POST['facebook']   ?? '');
        $twitter   = trim($_POST['twitter']    ?? '');
        $instagram = trim($_POST['instagram']  ?? '');
        $linkedin  = trim($_POST['linkedin']   ?? '');

        if ($phone === '' || $email === '') {
            $error = 'Phone number and email are required.';
        } else {
            if ($contact) {
                db()->prepare('UPDATE contacts SET phone=?,email=?,address_en=?,address_fr=?,facebook=?,twitter=?,instagram=?,linkedin=?,updated_at=CURRENT_TIMESTAMP WHERE id=?')
                   ->execute([$phone,$email,$addrEn,$addrFr,$facebook,$twitter,$instagram,$linkedin,$contact['id']]);
            } else {
                db()->prepare('INSERT INTO contacts (phone,email,address_en,address_fr,facebook,twitter,instagram,linkedin) VALUES (?,?,?,?,?,?,?,?)')
                   ->execute([$phone,$email,$addrEn,$addrFr,$facebook,$twitter,$instagram,$linkedin]);
            }
            $contact = get_contact();
            $saved = true;
        }
    }
}

define('APP_INIT_ADMIN', true);
include __DIR__ . '/_header.php';
?>

<div class="page-header">
  <div>
    <h1 class="page-title">Contact Information</h1>
    <p class="page-subtitle">This info appears on the website contact page and footer.</p>
  </div>
</div>

<?php if ($error): ?>
  <div class="admin-alert danger"><i class="bi bi-exclamation-triangle-fill admin-alert-icon"></i><?php echo escape($error); ?></div>
<?php endif; ?>
<?php if ($saved): ?>
  <div class="admin-alert success"><i class="bi bi-check-circle-fill admin-alert-icon"></i>Contact information saved successfully.</div>
<?php endif; ?>

<div style="display:grid;grid-template-columns:1fr 380px;gap:24px;align-items:start;">

  <form method="post">
    <input type="hidden" name="csrf_token" value="<?php echo escape(csrf_token()); ?>">

    <div class="admin-card" style="margin-bottom:20px;">
      <div class="admin-card-header">
        <div class="admin-card-title"><i class="bi bi-telephone-fill"></i> Main Contact Details</div>
      </div>
      <div class="admin-card-body">
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Phone <span class="required">*</span></label>
            <input class="form-control" name="phone" value="<?php echo escape($contact['phone'] ?? ''); ?>" placeholder="+237 600 000 000" required>
          </div>
          <div class="form-group">
            <label class="form-label">Email <span class="required">*</span></label>
            <input class="form-control" type="email" name="email" value="<?php echo escape($contact['email'] ?? ''); ?>" placeholder="info@smartrackafrica.com" required>
          </div>
        </div>
        <div class="form-group">
          <label class="form-label">Office Address (English)</label>
          <textarea class="form-control" name="address_en" rows="2" placeholder="Douala, Cameroon"><?php echo escape($contact['address_en'] ?? ''); ?></textarea>
        </div>
        <div class="form-group">
          <label class="form-label">Office Address (French)</label>
          <textarea class="form-control" name="address_fr" rows="2" placeholder="Douala, Cameroun"><?php echo escape($contact['address_fr'] ?? ''); ?></textarea>
        </div>
      </div>
    </div>

    <div class="admin-card" style="margin-bottom:20px;">
      <div class="admin-card-header">
        <div class="admin-card-title"><i class="bi bi-share-fill"></i> Social Media Links</div>
      </div>
      <div class="admin-card-body">
        <div class="form-row">
          <div class="form-group">
            <label class="form-label"><i class="bi bi-facebook" style="color:#1877f2;"></i> Facebook</label>
            <input class="form-control" name="facebook" value="<?php echo escape($contact['facebook'] ?? ''); ?>" placeholder="https://facebook.com/smartrack">
          </div>
          <div class="form-group">
            <label class="form-label"><i class="bi bi-twitter-x"></i> Twitter / X</label>
            <input class="form-control" name="twitter" value="<?php echo escape($contact['twitter'] ?? ''); ?>" placeholder="https://x.com/smartrack">
          </div>
          <div class="form-group">
            <label class="form-label"><i class="bi bi-instagram" style="color:#e1306c;"></i> Instagram</label>
            <input class="form-control" name="instagram" value="<?php echo escape($contact['instagram'] ?? ''); ?>" placeholder="https://instagram.com/smartrack">
          </div>
          <div class="form-group">
            <label class="form-label"><i class="bi bi-linkedin" style="color:#0077b5;"></i> LinkedIn</label>
            <input class="form-control" name="linkedin" value="<?php echo escape($contact['linkedin'] ?? ''); ?>" placeholder="https://linkedin.com/company/smartrack">
          </div>
        </div>
      </div>
    </div>

    <button class="btn btn-primary" type="submit">
      <i class="bi bi-check-lg"></i> Save Contact Info
    </button>
  </form>

  <!-- Preview panel -->
  <div class="admin-card" style="position:sticky;top:calc(var(--topbar-h) + 20px);">
    <div class="admin-card-header">
      <div class="admin-card-title"><i class="bi bi-eye-fill"></i> Current Info</div>
    </div>
    <div class="admin-card-body">
      <?php if ($contact): ?>
        <?php if (!empty($contact['phone'])): ?>
          <div class="info-row">
            <i class="bi bi-telephone-fill"></i>
            <span class="info-label">Phone</span>
            <span class="info-value"><?php echo escape($contact['phone']); ?></span>
          </div>
        <?php endif; ?>
        <?php if (!empty($contact['email'])): ?>
          <div class="info-row">
            <i class="bi bi-envelope-fill"></i>
            <span class="info-label">Email</span>
            <span class="info-value"><?php echo escape($contact['email']); ?></span>
          </div>
        <?php endif; ?>
        <?php if (!empty($contact['address_en'])): ?>
          <div class="info-row">
            <i class="bi bi-geo-alt-fill"></i>
            <span class="info-label">Address</span>
            <span class="info-value"><?php echo escape($contact['address_en']); ?></span>
          </div>
        <?php endif; ?>
        <?php
        $socials = ['facebook'=>'#1877f2','twitter'=>'#000','instagram'=>'#e1306c','linkedin'=>'#0077b5'];
        foreach ($socials as $key => $color):
          if (empty($contact[$key])) continue;
        ?>
          <div class="info-row">
            <i class="bi bi-<?php echo $key === 'twitter' ? 'twitter-x' : $key; ?>" style="color:<?php echo $color; ?>"></i>
            <a href="<?php echo escape($contact[$key]); ?>" target="_blank" style="color:var(--text);font-size:.85rem;text-overflow:ellipsis;overflow:hidden;white-space:nowrap;">
              <?php echo escape($contact[$key]); ?>
            </a>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="empty-state" style="padding:24px 16px;">
          <i class="bi bi-telephone-x"></i>
          <p>No contact info saved yet.</p>
        </div>
      <?php endif; ?>
    </div>
  </div>

</div>

<style>
@media (max-width: 900px) {
  .admin-content > div[style*="grid-template-columns:1fr 380px"] {
    grid-template-columns: 1fr !important;
  }
}
</style>

<?php include __DIR__ . '/_footer.php'; ?>
