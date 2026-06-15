<?php
require_once __DIR__ . '/../includes/functions.php';
require_admin();
$pageTitle = 'Dashboard';

$pdo = db();

$counts = [
    'services'         => (int) $pdo->query('SELECT COUNT(*) FROM services')->fetchColumn(),
    'testimonials'     => (int) $pdo->query('SELECT COUNT(*) FROM testimonials')->fetchColumn(),
    'quote_requests'   => (int) $pdo->query('SELECT COUNT(*) FROM quote_requests')->fetchColumn(),
    'contact_messages' => (int) $pdo->query('SELECT COUNT(*) FROM contact_messages')->fetchColumn(),
];

$contact = get_contact();
$jobsCount   = (int) $pdo->query('SELECT COUNT(*) FROM job_postings WHERE is_active = 1')->fetchColumn();
$appsNew     = (int) $pdo->query("SELECT COUNT(*) FROM job_applications WHERE status = 'new'")->fetchColumn();
$appsTotal   = (int) $pdo->query('SELECT COUNT(*) FROM job_applications')->fetchColumn();

$recentQuotes = $pdo->query(
    'SELECT name, email, phone, created_at FROM quote_requests ORDER BY created_at DESC LIMIT 5'
)->fetchAll();

$recentMessages = $pdo->query(
    'SELECT name, email, subject, created_at FROM contact_messages ORDER BY created_at DESC LIMIT 5'
)->fetchAll();

define('APP_INIT_ADMIN', true);
include __DIR__ . '/_header.php';
?>

<div class="page-header">
  <div>
    <h1 class="page-title">Dashboard</h1>
    <p class="page-subtitle">Welcome back, <?php echo escape($adminUser['username'] ?? 'Admin'); ?>. Here's what's happening.</p>
  </div>
  <a href="<?php echo escape(site_url('index.php')); ?>" class="btn btn-secondary" target="_blank">
    <i class="bi bi-globe"></i> View Website
  </a>
</div>

<!-- ── Stat cards ── -->
<div class="stat-grid">
  <a href="services.php" class="stat-card red">
    <div class="stat-icon"><i class="bi bi-geo-alt-fill"></i></div>
    <div class="stat-info">
      <div class="stat-value"><?php echo $counts['services']; ?></div>
      <div class="stat-label">Services</div>
    </div>
  </a>
  <a href="testimonials.php" class="stat-card purple">
    <div class="stat-icon"><i class="bi bi-chat-quote-fill"></i></div>
    <div class="stat-info">
      <div class="stat-value"><?php echo $counts['testimonials']; ?></div>
      <div class="stat-label">Testimonials</div>
    </div>
  </a>
  <a href="quotes.php" class="stat-card orange">
    <div class="stat-icon"><i class="bi bi-file-earmark-text-fill"></i></div>
    <div class="stat-info">
      <div class="stat-value"><?php echo $counts['quote_requests']; ?></div>
      <div class="stat-label">Quote Requests</div>
    </div>
  </a>
  <a href="jobs.php" class="stat-card red" style="border-top-color:#8b5cf6;">
    <div class="stat-icon" style="background:rgba(139,92,246,.1);color:#8b5cf6;"><i class="bi bi-briefcase-fill"></i></div>
    <div class="stat-info">
      <div class="stat-value" style="color:#8b5cf6;"><?php echo $jobsCount; ?></div>
      <div class="stat-label">Open Jobs</div>
    </div>
  </a>
  <a href="messages.php" class="stat-card green">
    <div class="stat-icon"><i class="bi bi-envelope-fill"></i></div>
    <div class="stat-info">
      <div class="stat-value"><?php echo $counts['contact_messages']; ?></div>
      <div class="stat-label">Messages</div>
    </div>
  </a>
  <a href="applications.php?status=new" class="stat-card blue">
    <div class="stat-icon"><i class="bi bi-inbox-fill"></i></div>
    <div class="stat-info">
      <div class="stat-value"><?php echo $appsNew; ?></div>
      <div class="stat-label">New CVs</div>
    </div>
  </a>
</div>

<!-- ── Main grid ── -->
<div style="display:grid;grid-template-columns:1fr 1fr;gap:22px;margin-bottom:22px;" class="dashboard-grid">

  <!-- Recent quotes -->
  <div class="admin-card">
    <div class="admin-card-header">
      <div class="admin-card-title">
        <i class="bi bi-file-earmark-text-fill"></i>
        Recent Quote Requests
      </div>
      <a href="quotes.php" class="btn btn-sm btn-secondary">View all</a>
    </div>
    <div class="admin-card-body" style="padding:8px 22px;">
      <?php if (!empty($recentQuotes)): ?>
        <?php foreach ($recentQuotes as $q): ?>
          <div class="recent-item">
            <div class="recent-avatar">
              <?php echo strtoupper(mb_substr($q['name'], 0, 1)); ?>
            </div>
            <div style="min-width:0;">
              <div class="recent-name"><?php echo escape($q['name']); ?></div>
              <div class="recent-meta"><?php echo escape($q['email']); ?></div>
            </div>
            <div class="recent-time"><?php echo date('M j', strtotime($q['created_at'])); ?></div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="empty-state">
          <i class="bi bi-inbox"></i>
          <p>No quote requests yet.</p>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Recent messages -->
  <div class="admin-card">
    <div class="admin-card-header">
      <div class="admin-card-title">
        <i class="bi bi-envelope-fill"></i>
        Recent Messages
      </div>
      <a href="messages.php" class="btn btn-sm btn-secondary">View all</a>
    </div>
    <div class="admin-card-body" style="padding:8px 22px;">
      <?php if (!empty($recentMessages)): ?>
        <?php foreach ($recentMessages as $m): ?>
          <div class="recent-item">
            <div class="recent-avatar">
              <?php echo strtoupper(mb_substr($m['name'], 0, 1)); ?>
            </div>
            <div style="min-width:0;">
              <div class="recent-name"><?php echo escape($m['name']); ?></div>
              <div class="recent-meta"><?php echo escape($m['subject'] ?: $m['email']); ?></div>
            </div>
            <div class="recent-time"><?php echo date('M j', strtotime($m['created_at'])); ?></div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="empty-state">
          <i class="bi bi-inbox"></i>
          <p>No messages yet.</p>
        </div>
      <?php endif; ?>
    </div>
  </div>

</div>

<!-- ── Bottom row ── -->
<div style="display:grid;grid-template-columns:1fr 1fr;gap:22px;">

  <!-- Contact info summary -->
  <div class="admin-card">
    <div class="admin-card-header">
      <div class="admin-card-title">
        <i class="bi bi-telephone-fill"></i>
        Contact Information
      </div>
      <a href="contact-info.php" class="btn btn-sm btn-secondary">Edit</a>
    </div>
    <div class="admin-card-body">
      <?php if ($contact): ?>
        <?php if (!empty($contact['phone'])): ?>
          <div class="info-row">
            <i class="bi bi-telephone-fill"></i>
            <span class="info-label">Phone</span>
            <span class="info-value"><?php echo escape($contact['phone']); ?></span>
          </div>
        <?php endif; ?>
        <?php if (!empty($contact['email'])): ?>
          <div class="info-row">
            <i class="bi bi-envelope-fill"></i>
            <span class="info-label">Email</span>
            <span class="info-value"><?php echo escape($contact['email']); ?></span>
          </div>
        <?php endif; ?>
        <?php if (!empty($contact['address_en'])): ?>
          <div class="info-row">
            <i class="bi bi-geo-alt-fill"></i>
            <span class="info-label">Address</span>
            <span class="info-value"><?php echo escape($contact['address_en']); ?></span>
          </div>
        <?php endif; ?>
      <?php else: ?>
        <div class="empty-state" style="padding:30px 20px;">
          <i class="bi bi-telephone-x"></i>
          <p>No contact info set.</p>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Recent applications -->
  <?php
  $recentApps = $pdo->query(
      "SELECT name, email, job_title, status, created_at FROM job_applications ORDER BY created_at DESC LIMIT 5"
  )->fetchAll();
  $appStatusColors = ['new'=>'#1d4ed8','reviewed'=>'#ca8a04','shortlisted'=>'#15803d','rejected'=>'#b91c1c'];
  ?>
  <div class="admin-card">
    <div class="admin-card-header">
      <div class="admin-card-title">
        <i class="bi bi-inbox-fill"></i>
        Recent Applications
      </div>
      <a href="applications.php" class="btn btn-sm btn-secondary">View all</a>
    </div>
    <div class="admin-card-body" style="padding:8px 22px;">
      <?php if (!empty($recentApps)): ?>
        <?php foreach ($recentApps as $a): $sc = $appStatusColors[$a['status']] ?? '#475569'; ?>
          <div class="recent-item">
            <div class="recent-avatar"><?php echo strtoupper(mb_substr($a['name'],0,1)); ?></div>
            <div style="min-width:0;">
              <div class="recent-name"><?php echo escape($a['name']); ?></div>
              <div class="recent-meta"><?php echo escape($a['job_title'] ?: 'General'); ?></div>
            </div>
            <span style="margin-left:auto;background:rgba(0,0,0,.05);color:<?php echo $sc; ?>;font-size:.68rem;font-weight:700;padding:3px 9px;border-radius:20px;text-transform:uppercase;letter-spacing:.05em;white-space:nowrap;">
              <?php echo escape($a['status']); ?>
            </span>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="empty-state" style="padding:30px 20px;">
          <i class="bi bi-inbox"></i><p>No applications yet.</p>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Quick actions -->
  <div class="admin-card">
    <div class="admin-card-header">
      <div class="admin-card-title">
        <i class="bi bi-lightning-fill"></i>
        Quick Actions
      </div>
    </div>
    <div class="admin-card-body">
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
        <a href="services.php" class="btn btn-secondary" style="justify-content:flex-start;">
          <i class="bi bi-plus-circle-fill" style="color:var(--accent);"></i> Add Service
        </a>
        <a href="testimonials.php" class="btn btn-secondary" style="justify-content:flex-start;">
          <i class="bi bi-chat-quote" style="color:#8b5cf6;"></i> Add Review
        </a>
        <a href="translations.php" class="btn btn-secondary" style="justify-content:flex-start;">
          <i class="bi bi-translate" style="color:#22c55e;"></i> Translations
        </a>
        <a href="homepage.php" class="btn btn-secondary" style="justify-content:flex-start;">
          <i class="bi bi-pencil-fill" style="color:#f59e0b;"></i> Edit Hero
        </a>
        <a href="contact-info.php" class="btn btn-secondary" style="justify-content:flex-start;">
          <i class="bi bi-telephone" style="color:#0891b2;"></i> Contact Info
        </a>
        <a href="jobs.php" class="btn btn-secondary" style="justify-content:flex-start;">
          <i class="bi bi-briefcase-fill" style="color:#8b5cf6;"></i> Post Job
        </a>
        <a href="applications.php" class="btn btn-secondary" style="justify-content:flex-start;">
          <i class="bi bi-inbox-fill" style="color:#e53935;"></i> View CVs
        </a>
      </div>
    </div>
  </div>

</div>

<style>
@media (max-width: 768px) {
  .dashboard-grid,
  .dashboard-grid + div { grid-template-columns: 1fr !important; }
}
</style>

<?php include __DIR__ . '/_footer.php'; ?>
