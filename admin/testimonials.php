<?php
require_once __DIR__ . '/../includes/functions.php';
require_admin();
$pageTitle = 'Manage Testimonials';
$error = '';
$action = $_POST['action'] ?? '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid CSRF token.';
    } elseif ($action === 'delete' && !empty($_POST['testimonial_id'])) {
        $stmt = db()->prepare('DELETE FROM testimonials WHERE id = :id');
        $stmt->execute([':id' => (int) $_POST['testimonial_id']]);
        redirect('testimonials.php');
    } elseif ($action === 'save') {
        $authorEn = trim($_POST['author_en'] ?? '');
        $authorFr = trim($_POST['author_fr'] ?? '');
        $roleEn = trim($_POST['role_en'] ?? '');
        $roleFr = trim($_POST['role_fr'] ?? '');
        $quoteEn = trim($_POST['quote_en'] ?? '');
        $quoteFr = trim($_POST['quote_fr'] ?? '');

        if ($authorEn === '' || $quoteEn === '') {
            $error = 'Author and quote in English are required.';
        }

        $imagePath = null;
        if (empty($error)) {
            try {
                $imagePath = upload_image('image', 'testimonials');
            } catch (RuntimeException $uploadError) {
                $error = $uploadError->getMessage();
            }
        }

        if (!$error) {
            $stmt = db()->prepare('INSERT INTO testimonials (author_en, author_fr, role_en, role_fr, quote_en, quote_fr, image_path, created_at) VALUES (:author_en, :author_fr, :role_en, :role_fr, :quote_en, :quote_fr, :image_path, NOW())');
            $stmt->execute([':author_en' => $authorEn, ':author_fr' => $authorFr, ':role_en' => $roleEn, ':role_fr' => $roleFr, ':quote_en' => $quoteEn, ':quote_fr' => $quoteFr, ':image_path' => $imagePath]);
            redirect('testimonials.php');
        }
    }
}
$items = db()->query('SELECT * FROM testimonials ORDER BY created_at DESC')->fetchAll();
define('APP_INIT_ADMIN', true);
include __DIR__ . '/_header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <h1>Testimonials</h1>
</div>
<?php if ($error): ?>
  <div class="alert alert-danger"><?php echo escape($error); ?></div>
<?php endif; ?>
<div class="row gy-4">
  <div class="col-lg-6">
    <div class="card p-4">
      <h5>Add Testimonial</h5>
      <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?php echo escape(csrf_token()); ?>">
        <input type="hidden" name="action" value="save">
        <div class="mb-3"><label class="form-label">Author (EN)</label><input class="form-control" name="author_en" required></div>
        <div class="mb-3"><label class="form-label">Author (FR)</label><input class="form-control" name="author_fr"></div>
        <div class="mb-3"><label class="form-label">Role (EN)</label><input class="form-control" name="role_en"></div>
        <div class="mb-3"><label class="form-label">Role (FR)</label><input class="form-control" name="role_fr"></div>
        <div class="mb-3"><label class="form-label">Quote (EN)</label><textarea class="form-control" name="quote_en" rows="3" required></textarea></div>
        <div class="mb-3"><label class="form-label">Quote (FR)</label><textarea class="form-control" name="quote_fr" rows="3"></textarea></div>
        <div class="mb-3"><label class="form-label">Photo</label><input class="form-control" type="file" name="image" accept="image/jpeg,image/png,image/webp"></div>
        <button class="btn btn-primary w-100" type="submit">Add Testimonial</button>
      </form>
    </div>
  </div>
  <div class="col-lg-6">
    <div class="card p-4">
      <h5>Existing Testimonials</h5>
      <div class="table-responsive">
        <table class="table table-striped">
          <thead><tr><th>ID</th><th>Author</th><th>Quote</th><th>Actions</th></tr></thead>
          <tbody>
          <?php foreach ($items as $item): ?>
            <tr>
              <td><?php echo escape($item['id']); ?></td>
              <td><?php echo escape($item['author_en']); ?></td>
              <td><?php echo escape(substr($item['quote_en'], 0, 80)); ?></td>
              <td>
                <form method="post" class="d-inline" onsubmit="return confirm('Delete this testimonial?');">
                  <input type="hidden" name="csrf_token" value="<?php echo escape(csrf_token()); ?>">
                  <input type="hidden" name="action" value="delete">
                  <input type="hidden" name="testimonial_id" value="<?php echo escape($item['id']); ?>">
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