<?php
require_once __DIR__ . '/../includes/functions.php';
require_admin();
$pageTitle = 'Messages';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && verify_csrf($_POST['csrf_token'] ?? '')) {
    if (!empty($_POST['delete_id'])) {
        db()->prepare('DELETE FROM contact_messages WHERE id = ?')->execute([(int)$_POST['delete_id']]);
        redirect('messages.php');
    }
}

$messages = db()->query('SELECT * FROM contact_messages ORDER BY created_at DESC')->fetchAll();

define('APP_INIT_ADMIN', true);
include __DIR__ . '/_header.php';
?>

<div class="page-header">
  <div>
    <h1 class="page-title">Contact Messages</h1>
    <p class="page-subtitle"><?php echo count($messages); ?> message<?php echo count($messages) !== 1 ? 's' : ''; ?> received.</p>
  </div>
</div>

<?php if (empty($messages)): ?>
  <div class="admin-card">
    <div class="empty-state">
      <i class="bi bi-envelope-open"></i>
      <p>No messages yet. They will appear here when clients contact you.</p>
    </div>
  </div>
<?php else: ?>
  <div style="display:flex;flex-direction:column;gap:14px;">
    <?php foreach ($messages as $m): ?>
      <div class="admin-card">
        <div style="display:flex;align-items:flex-start;gap:16px;padding:18px 22px;">
          <div style="width:44px;height:44px;border-radius:10px;background:rgba(59,130,246,.1);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:1rem;color:#3b82f6;flex-shrink:0;">
            <?php echo strtoupper(mb_substr($m['name'], 0, 1)); ?>
          </div>
          <div style="flex:1;min-width:0;">
            <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;margin-bottom:4px;">
              <strong style="font-size:.95rem;"><?php echo escape($m['name']); ?></strong>
              <a href="mailto:<?php echo escape($m['email']); ?>" style="font-size:.82rem;color:var(--text-muted);text-decoration:none;"><?php echo escape($m['email']); ?></a>
              <?php if (!empty($m['subject'])): ?>
                <span class="badge badge-info"><?php echo escape($m['subject']); ?></span>
              <?php endif; ?>
              <span style="font-size:.78rem;color:var(--text-muted);margin-left:auto;"><?php echo date('M j, Y  H:i', strtotime($m['created_at'])); ?></span>
            </div>
            <p style="font-size:.9rem;color:var(--text);margin:0;line-height:1.6;"><?php echo escape($m['message']); ?></p>
          </div>
          <form method="post" onsubmit="return confirm('Delete this message?');" style="flex-shrink:0;margin-left:8px;">
            <input type="hidden" name="csrf_token" value="<?php echo escape(csrf_token()); ?>">
            <input type="hidden" name="delete_id" value="<?php echo $m['id']; ?>">
            <button class="btn btn-sm btn-danger btn-icon" type="submit" title="Delete">
              <i class="bi bi-trash-fill"></i>
            </button>
          </form>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<?php include __DIR__ . '/_footer.php'; ?>
