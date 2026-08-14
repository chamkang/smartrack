<?php
/**
 * Admin account — change password.
 *
 * The password you type here is hashed with bcrypt and stored; the plain text
 * is never written to disk, logged, or kept in the session.
 */
require_once __DIR__ . '/../includes/functions.php';
require_admin();

$pageTitle = 'My Account';
$error = '';
$saved = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        $error = 'Security token expired. Please refresh and try again.';
    } else {
        $current = $_POST['current_password'] ?? '';
        $new     = $_POST['new_password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';

        $stmt = db()->prepare('SELECT id, password_hash FROM admins WHERE id = ? LIMIT 1');
        $stmt->execute([$_SESSION['admin_id']]);
        $admin = $stmt->fetch();

        if (!$admin || !password_verify($current, $admin['password_hash'])) {
            $error = 'Your current password is not correct.';
        } elseif (strlen($new) < 10) {
            $error = 'The new password must be at least 10 characters long.';
        } elseif (!preg_match('/[A-Za-z]/', $new) || !preg_match('/\d/', $new)) {
            $error = 'Use a mix of letters and numbers.';
        } elseif ($new !== $confirm) {
            $error = 'The two new passwords do not match.';
        } elseif (password_verify($new, $admin['password_hash'])) {
            $error = 'That is already your current password.';
        } else {
            db()->prepare('UPDATE admins SET password_hash = ? WHERE id = ?')
                ->execute([password_hash($new, PASSWORD_BCRYPT), $admin['id']]);
            session_regenerate_id(true);   // invalidate any other session
            $saved = true;
        }
    }
}

// Is the well-known default still in use?
$stmt = db()->prepare('SELECT username, password_hash FROM admins WHERE id = ? LIMIT 1');
$stmt->execute([$_SESSION['admin_id']]);
$me = $stmt->fetch() ?: ['username' => '', 'password_hash' => ''];
$usingDefault = $me['password_hash'] && password_verify('admin123', $me['password_hash']);

define('APP_INIT_ADMIN', true);
include __DIR__ . '/_header.php';
?>
<div class="page-header">
  <div>
    <h1 class="page-title">My Account</h1>
    <p class="page-subtitle">Signed in as <strong><?php echo escape($me['username']); ?></strong></p>
  </div>
</div>

<?php if ($usingDefault): ?>
  <div class="admin-alert danger">
    <i class="bi bi-shield-exclamation admin-alert-icon"></i>
    <div>
      <strong>You are still using the default password.</strong><br>
      It ships with the project and is publicly known, so anyone could sign in.
      Please change it now — especially before the site goes live.
    </div>
  </div>
<?php endif; ?>

<?php if ($error): ?>
  <div class="admin-alert danger"><i class="bi bi-exclamation-triangle-fill admin-alert-icon"></i><?php echo escape($error); ?></div>
<?php elseif ($saved): ?>
  <div class="admin-alert success"><i class="bi bi-check-circle-fill admin-alert-icon"></i>Password changed. Use the new one next time you sign in.</div>
<?php endif; ?>

<div class="row">
  <div class="col-lg-7">
    <div class="admin-card" style="padding:28px;">
      <h5 style="font-size:1rem;font-weight:700;margin-bottom:18px;">Change password</h5>
      <form method="post" autocomplete="off">
        <input type="hidden" name="csrf_token" value="<?php echo escape(csrf_token()); ?>">

        <div class="mb-3">
          <label class="form-label">Current password</label>
          <input class="form-control" type="password" name="current_password" required autocomplete="current-password">
        </div>
        <div class="mb-3">
          <label class="form-label">New password</label>
          <input class="form-control" type="password" name="new_password" required minlength="10" autocomplete="new-password">
          <small class="text-muted">At least 10 characters, using both letters and numbers.</small>
        </div>
        <div class="mb-4">
          <label class="form-label">Confirm new password</label>
          <input class="form-control" type="password" name="confirm_password" required minlength="10" autocomplete="new-password">
        </div>

        <button class="btn btn-primary px-4" type="submit">
          <i class="bi bi-key-fill"></i> Update password
        </button>
      </form>
    </div>
  </div>

  <div class="col-lg-5">
    <div class="card p-3 bg-light">
      <h6>Before going live</h6>
      <ul class="mb-0 small">
        <li>Change this password from the default.</li>
        <li>Delete <code>setup.php</code> from the server — it can recreate the default admin account.</li>
        <li>Keep <code>smartrack.db</code> out of the public folder if your host allows it.</li>
        <li>Use HTTPS so your password is not sent in the clear.</li>
      </ul>
    </div>
  </div>
</div>

<?php include __DIR__ . '/_footer.php'; ?>
