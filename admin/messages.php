<?php
require_once __DIR__ . '/../includes/functions.php';
require_admin();
$pageTitle = 'Contact Messages';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && verify_csrf($_POST['csrf_token'] ?? '')) {
    if (!empty($_POST['delete_id'])) {
        $stmt = db()->prepare('DELETE FROM contact_messages WHERE id = :id');
        $stmt->execute([':id' => (int) $_POST['delete_id']]);
        redirect('messages.php');
    }
}
$messages = db()->query('SELECT * FROM contact_messages ORDER BY created_at DESC')->fetchAll();
define('APP_INIT_ADMIN', true);
include __DIR__ . '/_header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <h1>Contact Messages</h1>
</div>
<div class="card p-4">
  <div class="table-responsive">
    <table class="table table-bordered">
      <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Subject</th><th>Message</th><th>Date</th><th>Actions</th></tr></thead>
      <tbody>
      <?php foreach ($messages as $message): ?>
        <tr>
          <td><?php echo escape($message['id']); ?></td>
          <td><?php echo escape($message['name']); ?></td>
          <td><?php echo escape($message['email']); ?></td>
          <td><?php echo escape($message['subject']); ?></td>
          <td><?php echo escape($message['message']); ?></td>
          <td><?php echo escape($message['created_at']); ?></td>
          <td>
            <form method="post" onsubmit="return confirm('Delete this message?');">
              <input type="hidden" name="csrf_token" value="<?php echo escape(csrf_token()); ?>">
              <input type="hidden" name="delete_id" value="<?php echo escape($message['id']); ?>">
              <button class="btn btn-sm btn-danger">Delete</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<?php include __DIR__ . '/_footer.php'; ?>