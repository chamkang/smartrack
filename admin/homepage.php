<?php
require_once __DIR__ . '/../includes/functions.php';
require_admin();
$pageTitle = 'Homepage Content';
$error = '';
$heroImage = get_content_value('hero_image');
$heroVideo = get_content_value('hero_video');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid CSRF token.';
    } else {
        try {
            $newImage = upload_image('hero_image', 'home');
        } catch (RuntimeException $uploadError) {
            $error = $uploadError->getMessage();
        }

        try {
            $newVideo = upload_media('hero_video', 'home', ['video/mp4', 'video/webm'], 20 * 1024 * 1024);
        } catch (RuntimeException $uploadError) {
            if (!$error) {
                $error = $uploadError->getMessage();
            }
        }

        if (!$error) {
            $pdo = db();
            if ($newImage) {
                $stmt = $pdo->prepare('INSERT INTO homepage_content (content_key, value) VALUES (:key, :value) ON DUPLICATE KEY UPDATE value = VALUES(value)');
                $stmt->execute([':key' => 'hero_image', ':value' => $newImage]);
            }
            if ($newVideo) {
                $stmt = $pdo->prepare('INSERT INTO homepage_content (content_key, value) VALUES (:key, :value) ON DUPLICATE KEY UPDATE value = VALUES(value)');
                $stmt->execute([':key' => 'hero_video', ':value' => $newVideo]);
            }
            redirect('homepage.php');
        }
    }
}

define('APP_INIT_ADMIN', true);
include __DIR__ . '/_header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <h1>Homepage Content</h1>
</div>
<?php if ($error): ?>
  <div class="alert alert-danger"><?php echo escape($error); ?></div>
<?php endif; ?>
<div class="card p-4">
  <form method="post" enctype="multipart/form-data">
    <input type="hidden" name="csrf_token" value="<?php echo escape(csrf_token()); ?>">
    <div class="mb-3">
      <label class="form-label">Hero Image</label>
      <input class="form-control" type="file" name="hero_image" accept="image/jpeg,image/png,image/webp">
      <?php if ($heroImage): ?>
        <div class="mt-2"><img src="<?php echo escape($heroImage); ?>" alt="Hero Image" class="img-fluid rounded" style="max-width: 100%;"></div>
      <?php endif; ?>
    </div>
    <div class="mb-3">
      <label class="form-label">Hero Video</label>
      <input class="form-control" type="file" name="hero_video" accept="video/mp4,video/webm">
      <?php if ($heroVideo): ?>
        <div class="mt-2"><a href="<?php echo escape($heroVideo); ?>" target="_blank">Current hero video</a></div>
      <?php endif; ?>
    </div>
    <button class="btn btn-primary" type="submit">Save Homepage Content</button>
  </form>
</div>
<?php include __DIR__ . '/_footer.php'; ?>