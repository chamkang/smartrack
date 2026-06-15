<?php
require_once __DIR__ . '/../includes/functions.php';
require_admin();
$pageTitle = 'Testimonials';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid security token.';
    } elseif ($_POST['action'] === 'delete' && !empty($_POST['testimonial_id'])) {
        db()->prepare('DELETE FROM testimonials WHERE id = ?')->execute([(int)$_POST['testimonial_id']]);
        redirect('testimonials.php');
    } elseif ($_POST['action'] === 'save') {
        $authorEn = trim($_POST['author_en'] ?? '');
        $authorFr = trim($_POST['author_fr'] ?? '');
        $roleEn   = trim($_POST['role_en']   ?? '');
        $roleFr   = trim($_POST['role_fr']   ?? '');
        $quoteEn  = trim($_POST['quote_en']  ?? '');
        $quoteFr  = trim($_POST['quote_fr']  ?? '');

        if ($authorEn === '' || $quoteEn === '') {
            $error = 'Author name and English quote are required.';
        } else {
            $imagePath = null;
            try { $imagePath = upload_image('image', 'testimonials'); }
            catch (RuntimeException $e) { $error = $e->getMessage(); }

            if (!$error) {
                db()->prepare('INSERT INTO testimonials (author_en,author_fr,role_en,role_fr,quote_en,quote_fr,image_path,created_at) VALUES (?,?,?,?,?,?,?,CURRENT_TIMESTAMP)')
                   ->execute([$authorEn,$authorFr,$roleEn,$roleFr,$quoteEn,$quoteFr,$imagePath]);
                redirect('testimonials.php?saved=1');
            }
        }
    }
}

$saved = isset($_GET['saved']);
$items = db()->query('SELECT * FROM testimonials ORDER BY created_at DESC')->fetchAll();

define('APP_INIT_ADMIN', true);
include __DIR__ . '/_header.php';
?>

<div class="page-header">
  <div>
    <h1 class="page-title">Testimonials</h1>
    <p class="page-subtitle">Client reviews displayed on your homepage.</p>
  </div>
</div>

<?php if ($error): ?>
  <div class="admin-alert danger"><i class="bi bi-exclamation-triangle-fill admin-alert-icon"></i><?php echo escape($error); ?></div>
<?php endif; ?>
<?php if ($saved): ?>
  <div class="admin-alert success"><i class="bi bi-check-circle-fill admin-alert-icon"></i>Testimonial added successfully.</div>
<?php endif; ?>

<div style="display:grid;grid-template-columns:400px 1fr;gap:24px;align-items:start;">

  <!-- Form -->
  <div class="admin-card" style="position:sticky;top:calc(var(--topbar-h) + 20px);">
    <div class="admin-card-header">
      <div class="admin-card-title"><i class="bi bi-chat-quote-fill"></i> Add Testimonial</div>
    </div>
    <div class="admin-card-body">
      <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?php echo escape(csrf_token()); ?>">
        <input type="hidden" name="action" value="save">

        <div class="lang-tabs">
          <button type="button" class="lang-tab active" data-lang="en">🇬🇧 English</button>
          <button type="button" class="lang-tab" data-lang="fr">🇫🇷 French</button>
        </div>

        <div id="fields-en">
          <div class="form-group">
            <label class="form-label">Author Name (EN) <span class="required">*</span></label>
            <input class="form-control" name="author_en" placeholder="Jean-Pierre Mbeki" required>
          </div>
          <div class="form-group">
            <label class="form-label">Role / Position (EN)</label>
            <input class="form-control" name="role_en" placeholder="Fleet Manager, Acme Corp">
          </div>
          <div class="form-group">
            <label class="form-label">Quote (EN) <span class="required">*</span></label>
            <textarea class="form-control" name="quote_en" rows="4" placeholder="What did the client say?" required></textarea>
          </div>
        </div>

        <div id="fields-fr" style="display:none;">
          <div class="form-group">
            <label class="form-label">Author Name (FR)</label>
            <input class="form-control" name="author_fr" placeholder="Jean-Pierre Mbeki">
          </div>
          <div class="form-group">
            <label class="form-label">Role / Position (FR)</label>
            <input class="form-control" name="role_fr" placeholder="Gestionnaire de Flotte">
          </div>
          <div class="form-group">
            <label class="form-label">Quote (FR)</label>
            <textarea class="form-control" name="quote_fr" rows="4" placeholder="Qu'a dit le client ?"></textarea>
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">Author Photo <span style="font-weight:400;color:var(--text-muted);">(optional)</span></label>
          <input class="form-control" type="file" name="image" accept="image/jpeg,image/png,image/webp">
          <p class="form-hint">JPG, PNG or WebP · max 4 MB</p>
        </div>

        <button class="btn btn-primary" style="width:100%;" type="submit">
          <i class="bi bi-plus-circle-fill"></i> Add Testimonial
        </button>
      </form>
    </div>
  </div>

  <!-- List -->
  <div style="display:flex;flex-direction:column;gap:14px;">
    <?php if (empty($items)): ?>
      <div class="admin-card">
        <div class="empty-state">
          <i class="bi bi-chat-quote"></i>
          <p>No testimonials yet. Add your first client review.</p>
        </div>
      </div>
    <?php else: ?>
      <?php foreach ($items as $t): ?>
        <div class="admin-card">
          <div style="display:flex;align-items:flex-start;gap:16px;padding:18px 22px;">
            <?php if (!empty($t['image_path'])): ?>
              <img src="<?php echo escape($t['image_path']); ?>" style="width:52px;height:52px;border-radius:50%;object-fit:cover;flex-shrink:0;" alt="">
            <?php else: ?>
              <div style="width:52px;height:52px;border-radius:50%;background:rgba(229,57,53,.12);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:1.2rem;color:var(--accent);flex-shrink:0;">
                <?php echo strtoupper(mb_substr($t['author_en'], 0, 1)); ?>
              </div>
            <?php endif; ?>
            <div style="flex:1;min-width:0;">
              <div style="font-weight:700;"><?php echo escape($t['author_en']); ?></div>
              <?php if (!empty($t['role_en'])): ?>
                <div style="font-size:.82rem;color:var(--text-muted);margin-bottom:8px;"><?php echo escape($t['role_en']); ?></div>
              <?php endif; ?>
              <div style="font-size:.9rem;color:var(--text);font-style:italic;">&ldquo;<?php echo escape(substr($t['quote_en'], 0, 180)); ?><?php echo strlen($t['quote_en']) > 180 ? '…' : ''; ?>&rdquo;</div>
            </div>
            <form method="post" onsubmit="return confirm('Delete this testimonial?');" style="flex-shrink:0;">
              <input type="hidden" name="csrf_token" value="<?php echo escape(csrf_token()); ?>">
              <input type="hidden" name="action" value="delete">
              <input type="hidden" name="testimonial_id" value="<?php echo $t['id']; ?>">
              <button class="btn btn-sm btn-danger btn-icon" type="submit" title="Delete">
                <i class="bi bi-trash-fill"></i>
              </button>
            </form>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>

</div>

<script>
  document.querySelectorAll('.lang-tab').forEach(btn => {
    btn.addEventListener('click', function() {
      document.querySelectorAll('.lang-tab').forEach(b => b.classList.remove('active'));
      this.classList.add('active');
      const lang = this.dataset.lang;
      document.getElementById('fields-en').style.display = lang === 'en' ? '' : 'none';
      document.getElementById('fields-fr').style.display = lang === 'fr' ? '' : 'none';
    });
  });
</script>

<style>
@media (max-width: 900px) {
  .admin-content > div[style*="grid-template-columns:400px"] {
    grid-template-columns: 1fr !important;
  }
}
</style>

<?php include __DIR__ . '/_footer.php'; ?>
