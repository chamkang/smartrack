<?php
require_once __DIR__ . '/../includes/functions.php';
require_admin();
$pageTitle = 'Quote Requests';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && verify_csrf($_POST['csrf_token'] ?? '')) {
    if (!empty($_POST['delete_id'])) {
        db()->prepare('DELETE FROM quote_requests WHERE id = ?')->execute([(int)$_POST['delete_id']]);
        redirect('quotes.php');
    }
}

$quotes = db()->query('SELECT * FROM quote_requests ORDER BY created_at DESC')->fetchAll();

define('APP_INIT_ADMIN', true);
include __DIR__ . '/_header.php';
?>

<div class="page-header">
  <div>
    <h1 class="page-title">Quote Requests</h1>
    <p class="page-subtitle"><?php echo count($quotes); ?> request<?php echo count($quotes) !== 1 ? 's' : ''; ?> received.</p>
  </div>
</div>

<?php if (empty($quotes)): ?>
  <div class="admin-card">
    <div class="empty-state">
      <i class="bi bi-file-earmark-text"></i>
      <p>No quote requests yet. They will appear here when clients submit the form.</p>
    </div>
  </div>
<?php else: ?>
  <div class="admin-table-wrap">
    <table class="admin-table">
      <thead>
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>Email</th>
          <th>Phone</th>
          <th>Message</th>
          <th>Date</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($quotes as $q): ?>
          <tr>
            <td class="td-muted"><?php echo escape($q['id']); ?></td>
            <td>
              <div style="display:flex;align-items:center;gap:10px;">
                <div style="width:34px;height:34px;border-radius:8px;background:rgba(229,57,53,.1);display:flex;align-items:center;justify-content:center;font-weight:700;color:var(--accent);font-size:.9rem;flex-shrink:0;">
                  <?php echo strtoupper(mb_substr($q['name'], 0, 1)); ?>
                </div>
                <strong><?php echo escape($q['name']); ?></strong>
              </div>
            </td>
            <td><a href="mailto:<?php echo escape($q['email']); ?>" style="color:inherit;"><?php echo escape($q['email']); ?></a></td>
            <td><?php echo escape($q['phone'] ?: '—'); ?></td>
            <td style="max-width:260px;">
              <span style="display:block;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:240px;" title="<?php echo escape($q['message']); ?>">
                <?php echo escape($q['message']); ?>
              </span>
            </td>
            <td class="td-muted" style="white-space:nowrap;"><?php echo date('M j, Y', strtotime($q['created_at'])); ?></td>
            <td>
              <form method="post" onsubmit="return confirm('Delete this quote request?');">
                <input type="hidden" name="csrf_token" value="<?php echo escape(csrf_token()); ?>">
                <input type="hidden" name="delete_id" value="<?php echo $q['id']; ?>">
                <button class="btn btn-sm btn-danger btn-icon" type="submit" title="Delete">
                  <i class="bi bi-trash-fill"></i>
                </button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php endif; ?>

<?php include __DIR__ . '/_footer.php'; ?>
