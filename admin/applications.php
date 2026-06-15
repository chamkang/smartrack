<?php
require_once __DIR__ . '/../includes/functions.php';
require_admin();
$pageTitle = 'Applications';
$filterStatus = $_GET['status'] ?? 'all';

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && verify_csrf($_POST['csrf_token'] ?? '')) {

    if (!empty($_POST['delete_id'])) {
        $row = db()->prepare('SELECT cv_path FROM job_applications WHERE id = ?');
        $row->execute([(int)$_POST['delete_id']]);
        $app = $row->fetch();
        // Delete CV file from disk
        if (!empty($app['cv_path'])) {
            $full = __DIR__ . '/../../' . ltrim($app['cv_path'], '/');
            if (file_exists($full)) @unlink($full);
        }
        db()->prepare('DELETE FROM job_applications WHERE id = ?')
           ->execute([(int)$_POST['delete_id']]);
        redirect('applications.php?status=' . urlencode($filterStatus));
    }

    if (!empty($_POST['set_status']) && !empty($_POST['app_id'])) {
        $allowed = ['new','reviewed','shortlisted','rejected'];
        $status  = in_array($_POST['set_status'], $allowed) ? $_POST['set_status'] : 'new';
        db()->prepare('UPDATE job_applications SET status = ? WHERE id = ?')
           ->execute([$status, (int)$_POST['app_id']]);
        redirect('applications.php?status=' . urlencode($filterStatus));
    }
}

// Fetch applications
$where = $filterStatus !== 'all' ? ' WHERE status = ?' : '';
$stmt  = db()->prepare('SELECT * FROM job_applications' . $where . ' ORDER BY created_at DESC');
$filterStatus !== 'all' ? $stmt->execute([$filterStatus]) : $stmt->execute();
$apps  = $stmt->fetchAll();

// Summary counts
$counts = db()->query("
    SELECT status, COUNT(*) as n FROM job_applications GROUP BY status
")->fetchAll(PDO::FETCH_KEY_PAIR);
$total = array_sum($counts);

$statusColors = [
    'new'         => ['bg'=>'#dbeafe','c'=>'#1d4ed8','label'=>'New'],
    'reviewed'    => ['bg'=>'#fef9c3','c'=>'#ca8a04','label'=>'Reviewed'],
    'shortlisted' => ['bg'=>'#dcfce7','c'=>'#15803d','label'=>'Shortlisted'],
    'rejected'    => ['bg'=>'#fee2e2','c'=>'#b91c1c','label'=>'Rejected'],
];

define('APP_INIT_ADMIN', true);
include __DIR__ . '/_header.php';
?>

<div class="page-header">
  <div>
    <h1 class="page-title">Applications</h1>
    <p class="page-subtitle"><?php echo $total; ?> application<?php echo $total !== 1 ? 's' : ''; ?> received in total.</p>
  </div>
  <a href="jobs.php" class="btn btn-secondary">
    <i class="bi bi-briefcase-fill"></i> Manage Jobs
  </a>
</div>

<!-- Status summary cards -->
<div class="stat-grid" style="margin-bottom:28px;">
  <a href="applications.php?status=all" class="stat-card <?php echo $filterStatus==='all'?'blue':''; ?>" style="border-top-color:<?php echo $filterStatus==='all'?'var(--accent)':'#e2e8f0'; ?>">
    <div class="stat-icon" style="background:rgba(100,116,139,.1);color:#475569;"><i class="bi bi-inbox-fill"></i></div>
    <div class="stat-info">
      <div class="stat-value" style="color:#475569;"><?php echo $total; ?></div>
      <div class="stat-label">All</div>
    </div>
  </a>
  <?php foreach ($statusColors as $key => $sc): ?>
    <a href="applications.php?status=<?php echo $key; ?>"
       class="stat-card"
       style="border-top-color:<?php echo $sc['c']; ?>;">
      <div class="stat-icon" style="background:<?php echo $sc['bg']; ?>;color:<?php echo $sc['c']; ?>;">
        <i class="bi bi-<?php echo ['new'=>'envelope-fill','reviewed'=>'eye-fill','shortlisted'=>'star-fill','rejected'=>'x-circle-fill'][$key]; ?>"></i>
      </div>
      <div class="stat-info">
        <div class="stat-value" style="color:<?php echo $sc['c']; ?>;"><?php echo $counts[$key] ?? 0; ?></div>
        <div class="stat-label"><?php echo $sc['label']; ?></div>
      </div>
    </a>
  <?php endforeach; ?>
</div>

<!-- Applications list -->
<?php if (empty($apps)): ?>
  <div class="admin-card">
    <div class="empty-state">
      <i class="bi bi-inbox"></i>
      <p>No applications <?php echo $filterStatus !== 'all' ? 'with status "' . escape($filterStatus) . '"' : 'yet'; ?>.</p>
    </div>
  </div>
<?php else: ?>
  <div style="display:flex;flex-direction:column;gap:14px;">
    <?php foreach ($apps as $app):
      $sc = $statusColors[$app['status']] ?? $statusColors['new'];
    ?>
      <div class="admin-card">
        <div style="display:flex;align-items:flex-start;gap:18px;padding:18px 22px;">

          <!-- Avatar -->
          <div style="width:48px;height:48px;border-radius:12px;background:rgba(229,57,53,.1);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:1.1rem;color:var(--accent);flex-shrink:0;">
            <?php echo strtoupper(mb_substr($app['name'], 0, 1)); ?>
          </div>

          <!-- Main info -->
          <div style="flex:1;min-width:0;">
            <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;margin-bottom:4px;">
              <strong style="font-size:.95rem;"><?php echo escape($app['name']); ?></strong>
              <!-- Status badge -->
              <span style="background:<?php echo $sc['bg']; ?>;color:<?php echo $sc['c']; ?>;font-size:.7rem;font-weight:700;padding:3px 10px;border-radius:20px;letter-spacing:.05em;text-transform:uppercase;">
                <?php echo $sc['label']; ?>
              </span>
              <span style="font-size:.78rem;color:var(--text-muted);margin-left:auto;">
                <?php echo date('M j, Y  H:i', strtotime($app['created_at'])); ?>
              </span>
            </div>

            <div style="display:flex;gap:14px;font-size:.82rem;color:var(--text-muted);flex-wrap:wrap;margin-bottom:8px;">
              <a href="mailto:<?php echo escape($app['email']); ?>" style="color:inherit;">
                <i class="bi bi-envelope me-1"></i><?php echo escape($app['email']); ?>
              </a>
              <?php if (!empty($app['phone'])): ?>
                <span><i class="bi bi-telephone me-1"></i><?php echo escape($app['phone']); ?></span>
              <?php endif; ?>
              <span style="background:rgba(229,57,53,.08);color:var(--accent);padding:2px 10px;border-radius:12px;font-weight:600;">
                <i class="bi bi-briefcase me-1"></i><?php echo escape($app['job_title'] ?: 'General Application'); ?>
              </span>
            </div>

            <?php if (!empty($app['cover_letter'])): ?>
              <details style="font-size:.85rem;color:var(--text-muted);">
                <summary style="cursor:pointer;color:var(--text);font-weight:600;">Cover Letter</summary>
                <p style="margin-top:8px;line-height:1.7;"><?php echo escape($app['cover_letter']); ?></p>
              </details>
            <?php endif; ?>
          </div>

          <!-- Right actions -->
          <div style="display:flex;flex-direction:column;gap:8px;flex-shrink:0;align-items:flex-end;">
            <!-- Download CV -->
            <?php if (!empty($app['cv_path'])): ?>
              <a href="<?php echo escape($app['cv_path']); ?>" download="<?php echo escape($app['cv_original_name'] ?: 'cv'); ?>"
                 class="btn btn-sm btn-secondary" title="Download CV">
                <i class="bi bi-file-earmark-arrow-down-fill"></i>
                <?php echo escape($app['cv_original_name'] ?: 'Download CV'); ?>
              </a>
            <?php else: ?>
              <span style="font-size:.78rem;color:var(--text-muted);">No CV uploaded</span>
            <?php endif; ?>

            <!-- Status change -->
            <form method="post" style="margin:0;">
              <input type="hidden" name="csrf_token" value="<?php echo escape(csrf_token()); ?>">
              <input type="hidden" name="app_id" value="<?php echo $app['id']; ?>">
              <div style="display:flex;gap:6px;align-items:center;">
                <select name="set_status" class="form-control" style="padding:5px 10px;font-size:.8rem;width:auto;">
                  <?php foreach ($statusColors as $key => $s): ?>
                    <option value="<?php echo $key; ?>" <?php echo $app['status'] === $key ? 'selected' : ''; ?>>
                      <?php echo $s['label']; ?>
                    </option>
                  <?php endforeach; ?>
                </select>
                <button class="btn btn-sm btn-secondary" type="submit" title="Save status">
                  <i class="bi bi-check2"></i>
                </button>
              </div>
            </form>

            <!-- Delete -->
            <form method="post" onsubmit="return confirm('Delete this application and its CV?');" style="margin:0;">
              <input type="hidden" name="csrf_token" value="<?php echo escape(csrf_token()); ?>">
              <input type="hidden" name="delete_id" value="<?php echo $app['id']; ?>">
              <button class="btn btn-sm btn-danger btn-icon" type="submit" title="Delete">
                <i class="bi bi-trash-fill"></i>
              </button>
            </form>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<?php include __DIR__ . '/_footer.php'; ?>
