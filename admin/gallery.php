<?php
require_once __DIR__ . '/../includes/functions.php';
require_admin();
$pageTitle = 'Manage Gallery';
$error = '';
$action = $_POST['action'] ?? '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid CSRF token.';
    } elseif ($action === 'delete' && !empty($_POST['gallery_id'])) {
        $stmt = db()->prepare('DELETE FROM gallery WHERE id = :id');
        $stmt->execute([':id' => (int) $_POST['gallery_id']]);
        redirect('gallery.php');
    } elseif ($action === 'save') {
        $descriptionEn = trim($_POST['description_en'] ?? '');
        $descriptionFr = trim($_POST['description_fr'] ?? '');
        $imagePath = null;

        try {
            $imagePath = upload_image('image', 'gallery');
        } catch (RuntimeException $uploadError) {
            $error = $uploadError->getMessage();
        }

        if (!$error && $imagePath) {
            $stmt = db()->prepare('INSERT INTO gallery (image_path, description_en, description_fr, created_at) VALUES (:image_path, :description_en, :description_fr, NOW())');
            $stmt->execute([':image_path' => $imagePath, ':description_en' => $descriptionEn, ':description_fr' => $descriptionFr]);
            redirect('gallery.php');
        }
    }
}
$galleryItems = db()->query('SELECT * FROM gallery ORDER BY created_at DESC')->fetchAll();
define('APP_INIT_ADMIN', true);
include __DIR__ . '/_header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <h1>Gallery</h1>
</div>
<?php if ($error): ?>
  <div class="alert alert-danger"><?php echo escape($error); ?></div>
<?php endif; ?>
<div class="row gy-4">
  <div class="col-lg-6">
    <div class="card p-4">
      <h5>Upload Image</h5>
      <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?php echo escape(csrf_token()); ?>">
        <input type="hidden" name="action" value="save">
        <div class="mb-3"><label class="form-label">Image</label><input class="form-control" type="file" name="image" accept="image/jpeg,image/png,image/webp" required></div>
        <div class="mb-3"><label class="form-label">Description (EN)</label><textarea class="form-control" name="description_en" rows="2"></textarea></div>
        <div class="mb-3"><label class="form-label">Description (FR)</label><textarea class="form-control" name="description_fr" rows="2"></textarea></div>
        <button class="btn btn-primary w-100" type="submit">Upload</button>
      </form>
    </div>
  </div>
  <div class="col-lg-6">
    <div class="card p-4">
      <h5>Gallery Items</h5>
      <div class="table-responsive">
        <table class="table table-striped">
          <thead><tr><th>ID</th><th>Image</th><th>Description EN</th><th>Actions</th></tr></thead>
          <tbody>
          <?php foreach ($galleryItems as $item): ?>
            <tr>
              <td><?php echo escape($item['id']); ?></td>
              <td><img src="<?php echo escape($item['image_path']); ?>" width="120" alt="Gallery"></td>
              <td><?php echo escape($item['description_en']); ?></td>
              <td>
                <form method="post" class="d-inline" onsubmit="return confirm('Delete this gallery item?');">
                  <input type="hidden" name="csrf_token" value="<?php echo escape(csrf_token()); ?>">
                  <input type="hidden" name="action" value="delete">
                  <input type="hidden" name="gallery_id" value="<?php echo escape($item['id']); ?>">
                  <button class="btn btn-sm btn-danger" type="submit">Delete</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<?php include __DIR__ . '/_footer.php'; ?>