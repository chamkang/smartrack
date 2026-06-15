<?php
require_once __DIR__ . '/../includes/functions.php';
require_admin();
$pageTitle = 'Job Postings';
$error  = '';
$saved  = false;
$action = $_POST['action'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid security token.';
    } elseif ($action === 'delete' && !empty($_POST['job_id'])) {
        db()->prepare('DELETE FROM job_postings WHERE id = ?')
           ->execute([(int)$_POST['job_id']]);
        redirect('jobs.php');

    } elseif ($action === 'toggle' && !empty($_POST['job_id'])) {
        $id  = (int)$_POST['job_id'];
        $cur = db()->prepare('SELECT is_active FROM job_postings WHERE id = ?');
        $cur->execute([$id]);
        $row = $cur->fetch();
        db()->prepare('UPDATE job_postings SET is_active = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?')
           ->execute([$row ? (1 - (int)$row['is_active']) : 1, $id]);
        redirect('jobs.php');

    } elseif ($action === 'save') {
        $id      = !empty($_POST['job_id']) ? (int)$_POST['job_id'] : null;
        $titleEn = trim($_POST['title_en']        ?? '');
        $titleFr = trim($_POST['title_fr']        ?? '');
        $dept    = trim($_POST['department']      ?? 'General');
        $loc     = trim($_POST['location']        ?? 'Douala, Cameroon');
        $type    = trim($_POST['job_type']        ?? 'Full-time');
        $descEn  = trim($_POST['description_en']  ?? '');
        $descFr  = trim($_POST['description_fr']  ?? '');
        $reqEn   = trim($_POST['requirements_en'] ?? '');
        $reqFr   = trim($_POST['requirements_fr'] ?? '');
        $active  = isset($_POST['is_active']) ? 1 : 0;

        if ($titleEn === '') {
            $error = 'English job title is required.';
        } else {
            if ($id) {
                db()->prepare('
                    UPDATE job_postings
                    SET title_en=?,title_fr=?,department=?,location=?,job_type=?,
                        description_en=?,description_fr=?,requirements_en=?,requirements_fr=?,
                        is_active=?,updated_at=CURRENT_TIMESTAMP
                    WHERE id=?
                ')->execute([$titleEn,$titleFr,$dept,$loc,$type,$descEn,$descFr,$reqEn,$reqFr,$active,$id]);
            } else {
                db()->prepare('
                    INSERT INTO job_postings
                        (title_en,title_fr,department,location,job_type,
                         description_en,description_fr,requirements_en,requirements_fr,is_active)
                    VALUES (?,?,?,?,?,?,?,?,?,?)
                ')->execute([$titleEn,$titleFr,$dept,$loc,$type,$descEn,$descFr,$reqEn,$reqFr,$active]);
            }
            redirect('jobs.php?saved=1');
        }
    }
}

$saved   = isset($_GET['saved']);
$jobs    = db()->query('SELECT * FROM job_postings ORDER BY created_at DESC')->fetchAll();
$editing = null;
if (!empty($_GET['edit'])) {
    $s = db()->prepare('SELECT * FROM job_postings WHERE id = ? LIMIT 1');
    $s->execute([(int)$_GET['edit']]);
    $editing = $s->fetch();
}

$departments = ['General','Technology','Customer Support','Field','Sales','Marketing','Finance','HR','Management','Operations'];
$types       = ['Full-time','Part-time','Contract','Internship'];

define('APP_INIT_ADMIN', true);
include __DIR__ . '/_header.php';
?>

<div class="page-header">
  <div>
    <h1 class="page-title">Job Postings</h1>
    <p class="page-subtitle">Create and manage open positions shown on the career page.</p>
  </div>
  <a href="applications.php" class="btn btn-secondary">
    <i class="bi bi-inbox-fill"></i> View Applications
  </a>
</div>

<?php if ($error): ?>
  <div class="admin-alert danger"><i class="bi bi-exclamation-triangle-fill admin-alert-icon"></i><?php echo escape($error); ?></div>
<?php endif; ?>
<?php if ($saved): ?>
  <div class="admin-alert success"><i class="bi bi-check-circle-fill admin-alert-icon"></i>Job posting saved successfully.</div>
<?php endif; ?>

<div style="display:grid;grid-template-columns:440px 1fr;gap:24px;align-items:start;">

  <!-- ── Form ──────────────────────────────────────────── -->
  <div class="admin-card" style="position:sticky;top:calc(var(--topbar-h) + 20px);">
    <div class="admin-card-header">
      <div class="admin-card-title">
        <i class="bi bi-<?php echo $editing ? 'pencil-fill' : 'plus-circle-fill'; ?>"></i>
        <?php echo $editing ? 'Edit Job' : 'Post a Job'; ?>
      </div>
      <?php if ($editing): ?>
        <a href="jobs.php" class="btn btn-sm btn-secondary">Cancel</a>
      <?php endif; ?>
    </div>
    <div class="admin-card-body">
      <form method="post">
        <input type="hidden" name="csrf_token" value="<?php echo escape(csrf_token()); ?>">
        <input type="hidden" name="action" value="save">
        <?php if ($editing): ?>
          <input type="hidden" name="job_id" value="<?php echo $editing['id']; ?>">
        <?php endif; ?>

        <!-- Language tabs -->
        <div class="lang-tabs mb-4">
          <button type="button" class="lang-tab active" data-lang="en">🇬🇧 English</button>
          <button type="button" class="lang-tab" data-lang="fr">🇫🇷 French</button>
        </div>

        <!-- EN fields -->
        <div id="fields-en">
          <div class="form-group">
            <label class="form-label">Job Title (EN) <span class="required">*</span></label>
            <input class="form-control" name="title_en" value="<?php echo escape($editing['title_en'] ?? ''); ?>" placeholder="e.g. Software Engineer" required>
          </div>
          <div class="form-group">
            <label class="form-label">Description (EN)</label>
            <textarea class="form-control" name="description_en" rows="4" placeholder="Role overview, responsibilities…"><?php echo escape($editing['description_en'] ?? ''); ?></textarea>
          </div>
          <div class="form-group">
            <label class="form-label">Requirements (EN)</label>
            <textarea class="form-control" name="requirements_en" rows="3" placeholder="Skills, experience, qualifications…"><?php echo escape($editing['requirements_en'] ?? ''); ?></textarea>
          </div>
        </div>

        <!-- FR fields -->
        <div id="fields-fr" style="display:none;">
          <div class="form-group">
            <label class="form-label">Job Title (FR)</label>
            <input class="form-control" name="title_fr" value="<?php echo escape($editing['title_fr'] ?? ''); ?>" placeholder="ex. Ingénieur Logiciel">
          </div>
          <div class="form-group">
            <label class="form-label">Description (FR)</label>
            <textarea class="form-control" name="description_fr" rows="4"><?php echo escape($editing['description_fr'] ?? ''); ?></textarea>
          </div>
          <div class="form-group">
            <label class="form-label">Requirements (FR)</label>
            <textarea class="form-control" name="requirements_fr" rows="3"><?php echo escape($editing['requirements_fr'] ?? ''); ?></textarea>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Department</label>
            <select class="form-control" name="department">
              <?php foreach ($departments as $d): ?>
                <option value="<?php echo escape($d); ?>" <?php echo ($editing['department'] ?? 'General') === $d ? 'selected' : ''; ?>>
                  <?php echo escape($d); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">Job Type</label>
            <select class="form-control" name="job_type">
              <?php foreach ($types as $t): ?>
                <option value="<?php echo escape($t); ?>" <?php echo ($editing['job_type'] ?? 'Full-time') === $t ? 'selected' : ''; ?>>
                  <?php echo escape($t); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">Location</label>
          <input class="form-control" name="location" value="<?php echo escape($editing['location'] ?? 'Douala, Cameroon'); ?>">
        </div>

        <div class="form-group" style="display:flex;align-items:center;gap:12px;padding:14px;background:var(--content-bg);border-radius:8px;">
          <input type="checkbox" name="is_active" id="isActive" style="width:18px;height:18px;accent-color:var(--accent);"
                 <?php echo (!isset($editing) || $editing['is_active']) ? 'checked' : ''; ?>>
          <label for="isActive" style="margin:0;font-weight:600;cursor:pointer;">
            Publish immediately
            <span style="font-weight:400;color:var(--text-muted);font-size:.82rem;display:block;">Visible to applicants on the career page</span>
          </label>
        </div>

        <button class="btn btn-primary" style="width:100%;margin-top:4px;" type="submit">
          <i class="bi bi-check-lg"></i>
          <?php echo $editing ? 'Update Job Posting' : 'Publish Job Posting'; ?>
        </button>
      </form>
    </div>
  </div>

  <!-- ── Listings ───────────────────────────────────────── -->
  <div style="display:flex;flex-direction:column;gap:14px;">
    <?php if (empty($jobs)): ?>
      <div class="admin-card">
        <div class="empty-state">
          <i class="bi bi-briefcase"></i>
          <p>No jobs posted yet. Use the form to create your first posting.</p>
        </div>
      </div>
    <?php else: ?>
      <?php foreach ($jobs as $j): ?>
        <div class="admin-card">
          <div style="display:flex;align-items:center;gap:16px;padding:18px 22px;">
            <!-- Status indicator -->
            <div style="width:10px;height:10px;border-radius:50%;background:<?php echo $j['is_active'] ? '#22c55e' : '#e2e8f0'; ?>;flex-shrink:0;box-shadow:<?php echo $j['is_active'] ? '0 0 0 3px rgba(34,197,94,.2)' : 'none'; ?>;"></div>

            <div style="flex:1;min-width:0;">
              <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
                <strong style="font-size:.95rem;"><?php echo escape($j['title_en']); ?></strong>
                <?php if (!empty($j['title_fr'])): ?>
                  <span style="font-size:.78rem;color:var(--text-muted);">/ <?php echo escape($j['title_fr']); ?></span>
                <?php endif; ?>
              </div>
              <div style="display:flex;gap:10px;margin-top:4px;font-size:.8rem;color:var(--text-muted);flex-wrap:wrap;">
                <span><i class="bi bi-building me-1"></i><?php echo escape($j['department']); ?></span>
                <span><i class="bi bi-clock me-1"></i><?php echo escape($j['job_type']); ?></span>
                <span><i class="bi bi-geo-alt me-1"></i><?php echo escape($j['location']); ?></span>
                <span><i class="bi bi-calendar me-1"></i><?php echo date('M j, Y', strtotime($j['created_at'])); ?></span>
              </div>
            </div>

            <!-- Actions -->
            <div style="display:flex;gap:8px;align-items:center;flex-shrink:0;">
              <!-- Toggle active -->
              <form method="post" style="margin:0;">
                <input type="hidden" name="csrf_token" value="<?php echo escape(csrf_token()); ?>">
                <input type="hidden" name="action"  value="toggle">
                <input type="hidden" name="job_id"  value="<?php echo $j['id']; ?>">
                <button class="btn btn-sm btn-secondary" type="submit" title="<?php echo $j['is_active'] ? 'Unpublish' : 'Publish'; ?>">
                  <i class="bi bi-<?php echo $j['is_active'] ? 'eye-slash' : 'eye'; ?>"></i>
                  <?php echo $j['is_active'] ? 'Unpublish' : 'Publish'; ?>
                </button>
              </form>
              <a href="jobs.php?edit=<?php echo $j['id']; ?>" class="btn btn-sm btn-secondary">
                <i class="bi bi-pencil-fill"></i> Edit
              </a>
              <form method="post" onsubmit="return confirm('Delete this job posting?');" style="margin:0;">
                <input type="hidden" name="csrf_token" value="<?php echo escape(csrf_token()); ?>">
                <input type="hidden" name="action"  value="delete">
                <input type="hidden" name="job_id"  value="<?php echo $j['id']; ?>">
                <button class="btn btn-sm btn-danger" type="submit"><i class="bi bi-trash-fill"></i></button>
              </form>
            </div>
          </div>

          <?php if (!empty($j['description_en'])): ?>
            <div style="padding:0 22px 16px 52px;font-size:.84rem;color:var(--text-muted);border-top:1px solid var(--border);">
              <div style="padding-top:12px;"><?php echo escape(mb_substr($j['description_en'], 0, 200)) . (mb_strlen($j['description_en']) > 200 ? '…' : ''); ?></div>
            </div>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>

</div>

<script>
  document.querySelectorAll('.lang-tab').forEach(btn => {
    btn.addEventListener('click', function() {
      document.querySelectorAll('.lang-tab').forEach(b => b.classList.remove('active'));
      this.classList.add('active');
      const lang = this.dataset.lang;
      document.getElementById('fields-en').style.display = lang === 'en' ? '' : 'none';
      document.getElementById('fields-fr').style.display = lang === 'fr' ? '' : 'none';
    });
  });
</script>

<style>
@media(max-width:900px){
  .admin-content>div[style*="grid-template-columns:440px"]{grid-template-columns:1fr!important;}
}
</style>

<?php include __DIR__ . '/_footer.php'; ?>
