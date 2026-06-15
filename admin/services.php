<?php
require_once __DIR__ . '/../includes/functions.php';
require_admin();
$pageTitle = 'Services';
$error = '';
$saved = false;
$action = $_POST['action'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid security token.';
    } elseif ($action === 'delete' && !empty($_POST['service_id'])) {
        db()->prepare('DELETE FROM services WHERE id = ?')->execute([(int)$_POST['service_id']]);
        redirect('services.php');
    } elseif ($action === 'save') {
        $serviceId = !empty($_POST['service_id']) ? (int)$_POST['service_id'] : null;
        $slug      = trim($_POST['slug']       ?? '');
        $titleEn   = trim($_POST['title_en']   ?? '');
        $titleFr   = trim($_POST['title_fr']   ?? '');
        $summaryEn = trim($_POST['summary_en'] ?? '');
        $summaryFr = trim($_POST['summary_fr'] ?? '');
        $contentEn = trim($_POST['content_en'] ?? '');
        $contentFr = trim($_POST['content_fr'] ?? '');

        if ($slug === '' || $titleEn === '' || $summaryEn === '') {
            $error = 'Slug, English title, and English summary are required.';
        } else {
            $imagePath = null;
            try { $imagePath = upload_image('image', 'services'); }
            catch (RuntimeException $e) { $error = $e->getMessage(); }

            if (!$error) {
                if ($serviceId) {
                    if ($imagePath) {
                        db()->prepare('UPDATE services SET slug=?,title_en=?,title_fr=?,summary_en=?,summary_fr=?,content_en=?,content_fr=?,image_path=?,updated_at=CURRENT_TIMESTAMP WHERE id=?')
                           ->execute([$slug,$titleEn,$titleFr,$summaryEn,$summaryFr,$contentEn,$contentFr,$imagePath,$serviceId]);
                    } else {
                        db()->prepare('UPDATE services SET slug=?,title_en=?,title_fr=?,summary_en=?,summary_fr=?,content_en=?,content_fr=?,updated_at=CURRENT_TIMESTAMP WHERE id=?')
                           ->execute([$slug,$titleEn,$titleFr,$summaryEn,$summaryFr,$contentEn,$contentFr,$serviceId]);
                    }
                } else {
                    db()->prepare('INSERT INTO services (slug,title_en,title_fr,summary_en,summary_fr,content_en,content_fr,image_path,created_at,updated_at) VALUES (?,?,?,?,?,?,?,?,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP)')
                       ->execute([$slug,$titleEn,$titleFr,$summaryEn,$summaryFr,$contentEn,$contentFr,$imagePath]);
                }
                redirect('services.php?saved=1');
            }
        }
    }
}

$saved = isset($_GET['saved']);
$services = db()->query('SELECT * FROM services ORDER BY sort_order ASC, created_at DESC')->fetchAll();

$editing = null;
if (!empty($_GET['edit'])) {
    $stmt = db()->prepare('SELECT * FROM services WHERE id = ? LIMIT 1');
    $stmt->execute([(int)$_GET['edit']]);
    $editing = $stmt->fetch();
}

define('APP_INIT_ADMIN', true);
include __DIR__ . '/_header.php';
?>

<div class="page-header">
  <div>
    <h1 class="page-title">Services</h1>
    <p class="page-subtitle">Manage the services displayed on your website.</p>
  </div>
</div>

<?php if ($error): ?>
  <div class="admin-alert danger"><i class="bi bi-exclamation-triangle-fill admin-alert-icon"></i><?php echo escape($error); ?></div>
<?php endif; ?>
<?php if ($saved): ?>
  <div class="admin-alert success"><i class="bi bi-check-circle-fill admin-alert-icon"></i>Service saved successfully.</div>
<?php endif; ?>

<div style="display:grid;grid-template-columns:420px 1fr;gap:24px;align-items:start;">

  <!-- ── Form ── -->
  <div class="admin-card" style="position:sticky;top:calc(var(--topbar-h) + 20px);">
    <div class="admin-card-header">
      <div class="admin-card-title">
        <i class="bi bi-<?php echo $editing ? 'pencil-fill' : 'plus-circle-fill'; ?>"></i>
        <?php echo $editing ? 'Edit Service' : 'Add Service'; ?>
      </div>
      <?php if ($editing): ?>
        <a href="services.php" class="btn btn-sm btn-secondary">Cancel</a>
      <?php endif; ?>
    </div>
    <div class="admin-card-body">
      <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?php echo escape(csrf_token()); ?>">
        <input type="hidden" name="action" value="save">
        <?php if ($editing): ?>
          <input type="hidden" name="service_id" value="<?php echo escape($editing['id']); ?>">
        <?php endif; ?>

        <div class="form-group">
          <label class="form-label">Slug <span class="required">*</span></label>
          <input class="form-control" name="slug" value="<?php echo escape($editing['slug'] ?? ''); ?>" placeholder="e.g. vehicle-tracking" required>
          <p class="form-hint">URL-friendly identifier, lowercase with hyphens.</p>
        </div>

        <div class="lang-tabs" id="langTabs">
          <button type="button" class="lang-tab active" data-lang="en">🇬🇧 English</button>
          <button type="button" class="lang-tab" data-lang="fr">🇫🇷 French</button>
        </div>

        <!-- English fields -->
        <div id="fields-en">
          <div class="form-group">
            <label class="form-label">Title (EN) <span class="required">*</span></label>
            <input class="form-control" name="title_en" value="<?php echo escape($editing['title_en'] ?? ''); ?>" required>
          </div>
          <div class="form-group">
            <label class="form-label">Summary (EN) <span class="required">*</span></label>
            <textarea class="form-control" name="summary_en" rows="2" required><?php echo escape($editing['summary_en'] ?? ''); ?></textarea>
          </div>
          <div class="form-group">
            <label class="form-label">Full Content (EN)</label>
            <textarea class="form-control" name="content_en" rows="4"><?php echo escape($editing['content_en'] ?? ''); ?></textarea>
          </div>
        </div>

        <!-- French fields -->
        <div id="fields-fr" style="display:none;">
          <div class="form-group">
            <label class="form-label">Title (FR)</label>
            <input class="form-control" name="title_fr" value="<?php echo escape($editing['title_fr'] ?? ''); ?>">
          </div>
          <div class="form-group">
            <label class="form-label">Summary (FR)</label>
            <textarea class="form-control" name="summary_fr" rows="2"><?php echo escape($editing['summary_fr'] ?? ''); ?></textarea>
          </div>
          <div class="form-group">
            <label class="form-label">Full Content (FR)</label>
            <textarea class="form-control" name="content_fr" rows="4"><?php echo escape($editing['content_fr'] ?? ''); ?></textarea>
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">Service Image</label>
          <input class="form-control" type="file" name="image" accept="image/jpeg,image/png,image/webp">
          <p class="form-hint">JPG, PNG or WebP · max 4 MB</p>
          <?php if (!empty($editing['image_path'])): ?>
            <div class="img-preview-wrap mt-2">
              <img src="<?php echo escape($editing['image_path']); ?>" alt="Current">
            </div>
          <?php endif; ?>
        </div>

        <button class="btn btn-primary" style="width:100%;" type="submit">
          <i class="bi bi-check-lg"></i>
          <?php echo $editing ? 'Update Service' : 'Create Service'; ?>
        </button>
      </form>
    </div>
  </div>

  <!-- ── List ── -->
  <div>
    <?php if (empty($services)): ?>
      <div class="admin-card">
        <div class="empty-state">
          <i class="bi bi-geo-alt"></i>
          <p>No services yet. Add your first service using the form.</p>
        </div>
      </div>
    <?php else: ?>
      <div style="display:flex;flex-direction:column;gap:14px;">
        <?php foreach ($services as $s): ?>
          <div class="admin-card" style="overflow:visible;">
            <div style="display:flex;align-items:center;gap:16px;padding:18px 22px;">
              <?php if (!empty($s['image_path'])): ?>
                <img src="<?php echo escape($s['image_path']); ?>" class="table-thumb" style="width:72px;height:56px;" alt="">
              <?php else: ?>
                <div style="width:72px;height:56px;border-radius:8px;background:rgba(229,57,53,.1);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                  <i class="bi bi-geo-alt-fill" style="color:var(--accent);font-size:1.5rem;"></i>
                </div>
              <?php endif; ?>
              <div style="flex:1;min-width:0;">
                <div style="font-weight:700;font-size:.95rem;"><?php echo escape($s['title_en']); ?></div>
                <?php if (!empty($s['title_fr'])): ?>
                  <div style="font-size:.8rem;color:var(--text-muted);"><?php echo escape($s['title_fr']); ?></div>
                <?php endif; ?>
                <div style="margin-top:4px;">
                  <span class="badge badge-neutral"><?php echo escape($s['slug']); ?></span>
                </div>
              </div>
              <div style="display:flex;gap:8px;flex-shrink:0;">
                <a href="services.php?edit=<?php echo $s['id']; ?>" class="btn btn-sm btn-secondary">
                  <i class="bi bi-pencil-fill"></i> Edit
                </a>
                <form method="post" onsubmit="return confirm('Delete this service?');">
                  <input type="hidden" name="csrf_token" value="<?php echo escape(csrf_token()); ?>">
                  <input type="hidden" name="action" value="delete">
                  <input type="hidden" name="service_id" value="<?php echo $s['id']; ?>">
                  <button class="btn btn-sm btn-danger" type="submit">
                    <i class="bi bi-trash-fill"></i> Delete
                  </button>
                </form>
              </div>
            </div>
            <?php if (!empty($s['summary_en'])): ?>
              <div style="padding:0 22px 16px;color:var(--text-muted);font-size:.85rem;border-top:1px solid var(--border);">
                <div style="padding-top:12px;"><?php echo escape($s['summary_en']); ?></div>
              </div>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>

</div>

<script>
  // Language tab switcher
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
  .admin-content > div[style*="grid-template-columns:420px"] {
    grid-template-columns: 1fr !important;
  }
}
</style>

<?php include __DIR__ . '/_footer.php'; ?>
