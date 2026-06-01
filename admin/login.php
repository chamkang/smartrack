<?php
require_once __DIR__ . '/../includes/functions.php';
init_session();

if (is_logged_in()) {
    redirect(site_url('admin/dashboard.php'));
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid CSRF token.';
    } else {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($username === '' || $password === '') {
            $error = 'Username and password are required.';
        } else {
            $stmt = db()->prepare('SELECT id, username, password_hash, email FROM admins WHERE username = :username LIMIT 1');
            $stmt->execute([':username' => $username]);
            $admin = $stmt->fetch();

            if ($admin && password_verify($password, $admin['password_hash'])) {
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin'] = ['username' => $admin['username'], 'email' => $admin['email']];
                $_SESSION['last_login'] = time();
                redirect(site_url('admin/dashboard.php'));
            }

            $error = 'Invalid credentials.';
        }
    }
}

$pageTitle = 'Admin Login';
define('APP_INIT', true);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo escape($pageTitle); ?></title>
  <link href="<?php echo escape(site_url('assets/vendor/bootstrap/css/bootstrap.min.css')); ?>" rel="stylesheet">
  <style>body{background:#f4f7fc} .auth-box{max-width:420px;margin:80px auto;padding:32px;background:#fff;border-radius:12px;box-shadow:0 0 30px rgba(0,0,0,.08);}</style>
</head>
<body>
<div class="auth-box">
  <h1 class="h4 mb-4">Admin Login</h1>
  <?php if ($error): ?>
      <div class="alert alert-danger"><?php echo escape($error); ?></div>
  <?php endif; ?>
  <form method="post" novalidate>
      <input type="hidden" name="csrf_token" value="<?php echo escape(csrf_token()); ?>">
      <div class="mb-3">
          <label class="form-label">Username</label>
          <input class="form-control" type="text" name="username" required>
      </div>
      <div class="mb-3">
          <label class="form-label">Password</label>
          <input class="form-control" type="password" name="password" required>
      </div>
      <button class="btn btn-primary w-100" type="submit">Sign In</button>
  </form>
</div>
<script src="<?php echo escape(site_url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js')); ?>"></script>
</body>
</html>