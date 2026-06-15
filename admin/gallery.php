<?php
require_once __DIR__ . '/../includes/functions.php';
require_admin();
$pageTitle = 'Gallery';
$error = '';
$saved = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid security token.';
    } elseif ($_POST['action'] === 'delete' && !empty($_POST['gallery_id'])) {
        db()->prepare('DELETE FROM gallery WHERE id = ?')->execute([(int)$_POST['gallery_id']]);
        redirect('gallery.php');
    } elseif ($_POST['action'] === 'save') {
        $descEn = trim($_POST['description_en'] ?? '');
        $descFr = trim($_POST['description_fr'] ?? '');
        try {
            $imagePath = upload_image('image', 'gallery');
        } catch (RuntimeException $e) {
            $error = $e->getMessage();
        }
        if (!$error && $imagePath) {
            db()->prepare('INSERT INTO gallery (image_path,description_en,description_fr,created_at) VALUES (?,?,?,CURRENT_TIMESTAMP)')
               ->execute([$imagePath, $descEn, $descFr]);
            redirect('gallery.php?saved=1');
        } elseif (!$error) {
            $error = 'Please choose an image file to upload.';
        }
    }
}

$saved = isset($_GET['saved']);
$items = db()->query('SELECT * FROM gallery ORDER BY sort_order ASC, created_at DESC')->fetchAll();

define('APP_INIT_ADMIN', true);
include __DIR__ . '/_header.php';
?>

<div class="page-header">
  <div>
    <h1 class="page-title">Gallery</h1>
    <p class="page-subtitle"><?php echo count($items); ?> image<?php echo count($items) !== 1 ? 's' : ''; ?> in the gallery.</p>
  </div>
</div>

<?php if ($error): ?>
  <div class="admin-alert danger"><i class="bi bi-exclamation-triangle-fill admin-alert-icon"></i><?php echo escape($error); ?></div>
<?php endif; ?>
<?php if ($saved): ?>
  <div class="admin-alert success"><i class="bi bi-check-circle-fill admin-alert-icon"></i>Image uploaded successfully.</div>
<?php endif; ?>

<!-- Upload form -->
<div class="admin-card" style="margin-bottom:28px;">
  <div class="admin-card-header">
    <div class="admin-card-title"><i class="bi bi-cloud-upload-fill"></i> Upload New Image</div>
  </div>
  <div class="admin-card-body">
    <form method="post" enctype="multipart/form-data">
      <input type="hidden" name="csrf_token" value="<?php echo escape(csrf_token()); ?>">
      <input type="hidden" name="action" value="save">
      <div style="display:grid;grid-template-columns:1fr 1fr 280px;gap:16px;align-items:end;">
        <div class="form-group" style="margin:0;">
          <label class="form-label">Image <span class="required">*</span></label>
          <input class="form-control" type="file" name="image" accept="image/jpeg,image/png,image/webp" required>
          <p class="form-hint">JPG, PNG or WebP · max 4 MB</p>
        </div>
        <div class="form-group" style="margin:0;">
          <label class="form-label">Caption (EN)</label>
          <input class="form-control" name="description_en" placeholder="Image caption in English">
        </div>
        <div class="form-group" style="margin:0;">
          <label class="form-label">Caption (FR)</label>
          <input class="form-control" name="description_fr" placeholder="Légende en Français">
        </div>
      </div>
      <div style="margin-top:16px;">
        <button class="btn btn-primary" type="submit">
          <i class="bi bi-cloud-upload-fill"></i> Upload Image
        </button>
      </div>
    </form>
  </div>
</div>

<!-- Gallery grid -->
<?php if (empty($items)): ?>
  <div class="admin-card">
    <div class="empty-state">
      <i class="bi bi-images"></i>
      <p>No gallery images yet. Upload your first image above.</p>
    </div>
  </div>
<?php else: ?>
  <div class="gallery-grid">
    <?php foreach ($items as $item): ?>
      <div class="gallery-item">
        <img src="<?php echo escape($item['image_path']); ?>" alt="<?php echo escape($item['description_en'] ?? ''); ?>">
        <div class="gallery-item-footer">
          <span class="gallery-item-desc"><?php echo escape($item['description_en'] ?: '—'); ?></span>
          <form method="post" onsubmit="return confirm('Delete this image?');">
            <input type="hidden" name="csrf_token" value="<?php echo escape(csrf_token()); ?>">
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="gallery_id" value="<?php echo $item['id']; ?>">
            <button class="btn btn-sm btn-danger btn-icon" type="submit" title="Delete">
              <i class="bi bi-trash-fill"></i>
            </button>
          </form>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<style>
@media (max-width: 768px) {
  .admin-card-body > form > div[style*="grid-template-columns:1fr 1fr"] {
    grid-template-columns: 1fr !important;
  }
}
</style>

<?php include __DIR__ . '/_footer.php'; ?>
