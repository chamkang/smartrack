<?php
require_once __DIR__ . '/../includes/functions.php';
require_admin();
$pageTitle = 'Manage Services';
$error = '';
$action = $_POST['action'] ?? '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid CSRF token.';
    } elseif ($action === 'delete' && !empty($_POST['service_id'])) {
        $stmt = db()->prepare('DELETE FROM services WHERE id = :id');
        $stmt->execute([':id' => (int) $_POST['service_id']]);
        redirect('services.php');
    } elseif ($action === 'save') {
        $serviceId = !empty($_POST['service_id']) ? (int) $_POST['service_id'] : null;
        $slug = trim($_POST['slug'] ?? '');
        $titleEn = trim($_POST['title_en'] ?? '');
        $titleFr = trim($_POST['title_fr'] ?? '');
        $summaryEn = trim($_POST['summary_en'] ?? '');
        $summaryFr = trim($_POST['summary_fr'] ?? '');
        $contentEn = trim($_POST['content_en'] ?? '');
        $contentFr = trim($_POST['content_fr'] ?? '');

        if ($slug === '' || $titleEn === '' || $summaryEn === '') {
            $error = 'Service slug, English title, and English summary are required.';
        } else {
            try {
                $imagePath = upload_image('image', 'services');
            } catch (RuntimeException $uploadError) {
                $error = $uploadError->getMessage();
            }

            if (!$error) {
                if ($serviceId) {
                    if ($imagePath) {
                        $stmt = db()->prepare('UPDATE services SET slug = :slug, title_en = :title_en, title_fr = :title_fr, summary_en = :summary_en, summary_fr = :summary_fr, content_en = :content_en, content_fr = :content_fr, image_path = :image_path, updated_at = NOW() WHERE id = :id');
                        $stmt->execute([':slug' => $slug, ':title_en' => $titleEn, ':title_fr' => $titleFr, ':summary_en' => $summaryEn, ':summary_fr' => $summaryFr, ':content_en' => $contentEn, ':content_fr' => $contentFr, ':image_path' => $imagePath, ':id' => $serviceId]);
                    } else {
                        $stmt = db()->prepare('UPDATE services SET slug = :slug, title_en = :title_en, title_fr = :title_fr, summary_en = :summary_en, summary_fr = :summary_fr, content_en = :content_en, content_fr = :content_fr, updated_at = NOW() WHERE id = :id');
                        $stmt->execute([':slug' => $slug, ':title_en' => $titleEn, ':title_fr' => $titleFr, ':summary_en' => $summaryEn, ':summary_fr' => $summaryFr, ':content_en' => $contentEn, ':content_fr' => $contentFr, ':id' => $serviceId]);
                    }
                } else {
                    $stmt = db()->prepare('INSERT INTO services (slug, title_en, title_fr, summary_en, summary_fr, content_en, content_fr, image_path, created_at, updated_at) VALUES (:slug, :title_en, :title_fr, :summary_en, :summary_fr, :content_en, :content_fr, :image_path, NOW(), NOW())');
                    $stmt->execute([':slug' => $slug, ':title_en' => $titleEn, ':title_fr' => $titleFr, ':summary_en' => $summaryEn, ':summary_fr' => $summaryFr, ':content_en' => $contentEn, ':content_fr' => $contentFr, ':image_path' => $imagePath]);
                }
                redirect('services.php');
            }
        }
    }
}

$services = db()->query('SELECT * FROM services ORDER BY created_at DESC')->fetchAll();
$editingService = null;
if (!empty($_GET['edit'])) {
    $stmt = db()->prepare('SELECT * FROM services WHERE id = :id LIMIT 1');
    $stmt->execute([':id' => (int) $_GET['edit']]);
    $editingService = $stmt->fetch();
}
define('APP_INIT_ADMIN', true);
include __DIR__ . '/_header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <h1>Services</h1>
</div>
<?php if ($error): ?>
  <div class="alert alert-danger"><?php echo escape($error); ?></div>
<?php endif; ?>
<div class="row gy-4">
  <div class="col-lg-6">
    <div class="card p-4">
      <h5><?php echo $editingService ? 'Edit Service' : 'Add Service'; ?></h5>
      <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?php echo escape(csrf_token()); ?>">
        <input type="hidden" name="action" value="save">
        <?php if ($editingService): ?>
          <input type="hidden" name="service_id" value="<?php echo escape($editingService['id']); ?>">
        <?php endif; ?>
        <div class="mb-3"><label class="form-label">Slug</label><input class="form-control" name="slug" value="<?php echo escape($editingService['slug'] ?? ''); ?>" required></div>
        <div class="mb-3"><label class="form-label">Title (EN)</label><input class="form-control" name="title_en" value="<?php echo escape($editingService['title_en'] ?? ''); ?>" required></div>
        <div class="mb-3"><label class="form-label">Title (FR)</label><input class="form-control" name="title_fr" value="<?php echo escape($editingService['title_fr'] ?? ''); ?>"></div>
        <div class="mb-3"><label class="form-label">Summary (EN)</label><textarea class="form-control" name="summary_en" rows="2" required><?php echo escape($editingService['summary_en'] ?? ''); ?></textarea></div>
        <div class="mb-3"><label class="form-label">Summary (FR)</label><textarea class="form-control" name="summary_fr" rows="2"><?php echo escape($editingService['summary_fr'] ?? ''); ?></textarea></div>
        <div class="mb-3"><label class="form-label">Content (EN)</label><textarea class="form-control" name="content_en" rows="4"><?php echo escape($editingService['content_en'] ?? ''); ?></textarea></div>
        <div class="mb-3"><label class="form-label">Content (FR)</label><textarea class="form-control" name="content_fr" rows="4"><?php echo escape($editingService['content_fr'] ?? ''); ?></textarea></div>
        <div class="mb-3"><label class="form-label">Image</label><input class="form-control" type="file" name="image" accept="image/jpeg,image/png,image/webp"></div>
        <?php if (!empty($editingService['image_path'])): ?>
          <div class="mb-3"><img src="<?php echo escape($editingService['image_path']); ?>" class="img-fluid rounded" alt="Service image"></div>
        <?php endif; ?>
        <button class="btn btn-primary w-100" type="submit"><?php echo $editingService ? 'Update Service' : 'Create Service'; ?></button>
      </form>
    </div>
  </div>
  <div class="col-lg-6">
    <div class="card p-4">
      <h5>Existing Services</h5>
      <div class="table-responsive">
        <table class="table table-striped">
          <thead><tr><th>ID</th><th>Slug</th><th>EN Title</th><th>Actions</th></tr></thead>
          <tbody>
          <?php foreach ($services as $service): ?>
            <tr>
              <td><?php echo escape($service['id']); ?></td>
              <td><?php echo escape($service['slug']); ?></td>
              <td><?php echo escape($service['title_en']); ?></td>
              <td>
                <a class="btn btn-sm btn-secondary" href="services.php?edit=<?php echo escape($service['id']); ?>">Edit</a>
                <form method="post" class="d-inline" onsubmit="return confirm('Delete this service?');">
                  <input type="hidden" name="csrf_token" value="<?php echo escape(csrf_token()); ?>">
                  <input type="hidden" name="action" value="delete">
                  <input type="hidden" name="service_id" value="<?php echo escape($service['id']); ?>">
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