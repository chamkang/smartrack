<?php
require_once __DIR__ . '/../includes/functions.php';
require_admin();
$pageTitle = 'Translations';
$error  = '';
$saved  = false;
$added  = false;

// ── Save edits ────────────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'save') {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid security token.';
    } else {
        $stmt = db()->prepare('INSERT OR REPLACE INTO translations (string_key, lang, value) VALUES (?,?,?)');
        foreach ($_POST['en'] ?? [] as $key => $valEn) {
            $valFr = trim($_POST['fr'][$key] ?? '');
            $valEn = trim($valEn);
            if ($valEn !== '' || $valFr !== '') {
                $stmt->execute([$key, 'en', $valEn]);
                $stmt->execute([$key, 'fr', $valFr]);
            }
        }
        $saved = true;
    }
}

// ── Add new string ────────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'add') {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid security token.';
    } else {
        $newKey = trim($_POST['new_key'] ?? '');
        $newEn  = trim($_POST['new_en']  ?? '');
        $newFr  = trim($_POST['new_fr']  ?? '');
        if ($newKey === '' || $newEn === '') {
            $error = 'Key and English value are required.';
        } else {
            $stmt = db()->prepare('INSERT OR REPLACE INTO translations (string_key, lang, value) VALUES (?,?,?)');
            $stmt->execute([$newKey, 'en', $newEn]);
            $stmt->execute([$newKey, 'fr', $newFr]);
            $added = true;
        }
    }
}

// ── Delete string ─────────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid security token.';
    } else {
        $delKey = trim($_POST['del_key'] ?? '');
        if ($delKey) {
            db()->prepare('DELETE FROM translations WHERE string_key = ?')->execute([$delKey]);
        }
    }
}

// ── Load all strings ──────────────────────────────────────────────────────────
$allEn = db()->query("SELECT string_key, value FROM translations WHERE lang='en' ORDER BY string_key")->fetchAll(PDO::FETCH_KEY_PAIR);
$allFr = db()->query("SELECT string_key, value FROM translations WHERE lang='fr' ORDER BY string_key")->fetchAll(PDO::FETCH_KEY_PAIR);

// Merge keys from both languages
$allKeys = array_unique(array_merge(array_keys($allEn), array_keys($allFr)));
sort($allKeys);

// ── Search filter ─────────────────────────────────────────────────────────────
$search = trim($_GET['q'] ?? '');

// ── Group definitions ─────────────────────────────────────────────────────────
$groups = [
    'nav_'     => ['Navigation',            'bi-compass-fill',       '#3b82f6'],
    'footer_'  => ['Footer',                'bi-layout-text-sidebar','#8b5cf6'],
    'home_'    => ['Homepage',              'bi-house-fill',         '#22c55e'],
    'contact_' => ['Contact Page',          'bi-envelope-fill',      '#06b6d4'],
    'sf_'      => ['SmartFleet Page',       'bi-truck-front-fill',   '#e60000'],
    'ss_'      => ['SmartSolution Page',    'bi-shield-fill-check',  '#3b82f6'],
    'dev_'     => ['Devices Page',          'bi-cpu-fill',           '#f59e0b'],
    'about_'   => ['About Page',            'bi-people-fill',        '#22c55e'],
    'career_'  => ['Career Page',           'bi-briefcase-fill',     '#8b5cf6'],
    'blog_'    => ['Blog & Posts',          'bi-journal-text',       '#06b6d4'],
    'svc_'     => ['Service Detail Page',   'bi-grid-fill',          '#f59e0b'],
    'btn_'     => ['Common Buttons',        'bi-cursor-fill',        '#64748b'],
    'lbl_'     => ['Common Labels',         'bi-tag-fill',           '#64748b'],
    'breadcrumb_' => ['Breadcrumbs',        'bi-chevron-right',      '#94a3b8'],
];

// Assign each key to a group
function get_group(string $key, array $groups): string {
    foreach ($groups as $prefix => $info) {
        if (str_starts_with($key, $prefix)) return $prefix;
    }
    return '__other__';
}

// Build grouped data
$grouped = ['__other__' => ['Legacy / General', 'bi-translate', '#94a3b8', []]];
foreach ($groups as $prefix => $info) {
    $grouped[$prefix] = [$info[0], $info[1], $info[2], []];
}

foreach ($allKeys as $key) {
    // Apply search filter
    if ($search !== '') {
        $enVal = $allEn[$key] ?? '';
        $frVal = $allFr[$key] ?? '';
        if (stripos($key,$search) === false && stripos($enVal,$search) === false && stripos($frVal,$search) === false) {
            continue;
        }
    }
    $prefix = get_group($key, $groups);
    $grouped[$prefix][3][] = $key;
}

// Count missing FR
$missingFr = 0;
foreach ($allKeys as $key) {
    if (empty(trim($allFr[$key] ?? ''))) $missingFr++;
}

define('APP_INIT_ADMIN', true);
include __DIR__ . '/_header.php';
?>

<style>
.tr-group-header {
    display:flex;align-items:center;gap:10px;padding:14px 20px;
    background:#f8f9fb;border:1px solid #e9ecef;border-radius:10px;
    margin-bottom:2px;cursor:pointer;user-select:none;
    transition:background .2s;
}
.tr-group-header:hover { background:#f1f5f9; }
.tr-group-icon { width:32px;height:32px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:.9rem;flex-shrink:0; }
.tr-group-title { font-weight:700;font-size:.9rem;color:#1a202c;flex:1; }
.tr-group-count { font-size:.75rem;background:#e9ecef;color:#64748b;padding:2px 8px;border-radius:20px;font-weight:600; }
.tr-group-missing { font-size:.75rem;background:#fef2f2;color:#ef4444;padding:2px 8px;border-radius:20px;font-weight:700; }
.tr-group-body { display:none;margin-bottom:16px; }
.tr-group-body.open { display:block; }
.tr-row {
    display:grid;grid-template-columns:220px 1fr 1fr 38px;
    gap:10px;align-items:start;
    padding:10px 12px;border-bottom:1px solid #f0f0f0;
}
.tr-row:last-child { border-bottom:none; }
.tr-row:hover { background:#fafafa; }
.tr-key { font-family:monospace;font-size:.72rem;color:#64748b;word-break:break-all;padding-top:6px; }
.tr-key .tr-missing-badge { display:inline-block;background:#fef2f2;color:#ef4444;font-size:.6rem;font-weight:700;padding:1px 6px;border-radius:3px;margin-top:3px; }
.tr-field { display:flex;flex-direction:column;gap:3px; }
.tr-lang-tag { font-size:.6rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;margin-bottom:2px; }
.tr-lang-tag.en { color:#3b82f6; }
.tr-lang-tag.fr { color:#e60000; }
.tr-textarea {
    width:100%;border:1.5px solid #e2e8f0;border-radius:7px;
    padding:7px 10px;font-size:.82rem;font-family:inherit;
    resize:vertical;min-height:38px;line-height:1.5;
    transition:border-color .2s;
}
.tr-textarea:focus { border-color:#3b82f6;outline:none;box-shadow:0 0 0 3px rgba(59,130,246,.1); }
.tr-textarea.fr-empty { border-color:#fca5a5;background:#fff7f7; }
.tr-delete-btn { padding:0;border:none;background:none;color:#cbd5e1;cursor:pointer;font-size:1rem;transition:color .2s;align-self:start;margin-top:8px; }
.tr-delete-btn:hover { color:#ef4444; }
.tr-search { max-width:320px; }
.add-string-card { background:linear-gradient(135deg,#f8f9fb,#fff);border:2px dashed #e2e8f0;border-radius:14px;padding:24px; }
.add-string-card:hover { border-color:#3b82f6; }
@media(max-width:768px) {
    .tr-row { grid-template-columns:1fr;gap:6px; }
    .tr-key { font-size:.7rem; }
}
</style>

<div class="page-header">
  <div>
    <h1 class="page-title">Translations</h1>
    <p class="page-subtitle">
      Manage all public-facing text in English and French.
      <?php if ($missingFr > 0): ?>
        <span style="color:#ef4444;font-weight:700;"><?php echo $missingFr; ?> string<?php echo $missingFr > 1 ? 's' : ''; ?> missing French translation.</span>
      <?php else: ?>
        <span style="color:#22c55e;font-weight:700;">✓ All strings have French translations.</span>
      <?php endif; ?>
    </p>
  </div>
  <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
    <form method="get" style="display:flex;gap:6px;">
      <input type="text" name="q" value="<?php echo escape($search); ?>"
             class="form-control tr-search" placeholder="Search key or text…"
             style="height:38px;font-size:.85rem;">
      <button class="btn btn-secondary" type="submit" style="height:38px;padding:0 14px;">
        <i class="bi bi-search"></i>
      </button>
      <?php if ($search): ?><a href="translations.php" class="btn btn-secondary" style="height:38px;padding:0 14px;"><i class="bi bi-x"></i></a><?php endif; ?>
    </form>
    <button class="btn btn-primary" form="mainForm" type="submit" style="height:38px;">
      <i class="bi bi-check-lg"></i> Save All Changes
    </button>
  </div>
</div>

<?php if ($error): ?>
  <div class="admin-alert danger"><i class="bi bi-exclamation-triangle-fill admin-alert-icon"></i><?php echo escape($error); ?></div>
<?php endif; ?>
<?php if ($saved): ?>
  <div class="admin-alert success"><i class="bi bi-check-circle-fill admin-alert-icon"></i>All translations saved successfully.</div>
<?php endif; ?>
<?php if ($added): ?>
  <div class="admin-alert success"><i class="bi bi-plus-circle-fill admin-alert-icon"></i>New translation string added.</div>
<?php endif; ?>

<!-- ── Add New String ──────────────────────────────────────────────────────── -->
<div class="add-string-card" style="margin-bottom:20px;">
  <div style="font-weight:700;font-size:.9rem;color:#1a202c;margin-bottom:14px;">
    <i class="bi bi-plus-circle-fill" style="color:#3b82f6;margin-right:6px;"></i> Add New Translation String
  </div>
  <form method="post">
    <input type="hidden" name="csrf_token" value="<?php echo escape(csrf_token()); ?>">
    <input type="hidden" name="action" value="add">
    <div style="display:grid;grid-template-columns:200px 1fr 1fr auto;gap:10px;align-items:end;">
      <div>
        <label class="form-label" style="font-size:.72rem;">Key (e.g. nav_home)</label>
        <input class="form-control" name="new_key" placeholder="prefix_name" style="font-family:monospace;font-size:.82rem;" required>
      </div>
      <div>
        <label class="form-label" style="font-size:.72rem;color:#3b82f6;">🇬🇧 English value</label>
        <input class="form-control" name="new_en" placeholder="English text" style="font-size:.82rem;" required>
      </div>
      <div>
        <label class="form-label" style="font-size:.72rem;color:#e60000;">🇫🇷 French value</label>
        <input class="form-control" name="new_fr" placeholder="Texte en français" style="font-size:.82rem;">
      </div>
      <div>
        <button class="btn btn-primary" type="submit"><i class="bi bi-plus-lg"></i> Add</button>
      </div>
    </div>
    <div style="margin-top:8px;font-size:.75rem;color:#94a3b8;">
      Use prefixes: <code>nav_</code> <code>footer_</code> <code>home_</code> <code>contact_</code> <code>sf_</code> <code>ss_</code> <code>dev_</code> <code>about_</code> <code>career_</code> <code>blog_</code> <code>svc_</code> <code>btn_</code>
    </div>
  </form>
</div>

<!-- ── Main translation form ──────────────────────────────────────────────── -->
<form method="post" id="mainForm">
  <input type="hidden" name="csrf_token" value="<?php echo escape(csrf_token()); ?>">
  <input type="hidden" name="action" value="save">

  <?php foreach ($grouped as $prefix => [$gLabel, $gIcon, $gColor, $keys]): ?>
    <?php if (empty($keys)) continue; ?>
    <?php
    $groupMissing = 0;
    foreach ($keys as $k) {
        if (empty(trim($allFr[$k] ?? ''))) $groupMissing++;
    }
    $isOpen = ($search !== '' || $prefix === '__other__' || $prefix === 'nav_' || $groupMissing > 0) ? 'open' : '';
    ?>
    <div style="margin-bottom:8px;">
      <div class="tr-group-header" onclick="toggleGroup(this)">
        <div class="tr-group-icon" style="background:<?php echo $gColor; ?>18;color:<?php echo $gColor; ?>;">
          <i class="bi <?php echo $gIcon; ?>"></i>
        </div>
        <span class="tr-group-title"><?php echo escape($gLabel); ?></span>
        <span class="tr-group-count"><?php echo count($keys); ?> string<?php echo count($keys) > 1 ? 's' : ''; ?></span>
        <?php if ($groupMissing > 0): ?>
          <span class="tr-group-missing"><?php echo $groupMissing; ?> missing FR</span>
        <?php endif; ?>
        <i class="bi bi-chevron-down" style="color:#94a3b8;font-size:.8rem;transition:transform .2s;"></i>
      </div>
      <div class="tr-group-body <?php echo $isOpen; ?>">
        <div class="admin-card" style="margin:0;border-radius:0 0 10px 10px;border-top:none;">
          <!-- Column headers -->
          <div style="display:grid;grid-template-columns:220px 1fr 1fr 38px;gap:10px;
                      padding:8px 12px;background:#f8f9fb;border-bottom:1px solid #e9ecef;
                      font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#94a3b8;">
            <span>Key</span>
            <span style="color:#3b82f6;">🇬🇧 English</span>
            <span style="color:#e60000;">🇫🇷 French</span>
            <span></span>
          </div>
          <?php foreach ($keys as $key):
            $enVal = $allEn[$key] ?? '';
            $frVal = $allFr[$key] ?? '';
            $missingThis = trim($frVal) === '';
            $rows = min(4, max(1, substr_count($enVal, "\n") + ceil(strlen($enVal) / 60)));
          ?>
            <div class="tr-row">
              <div class="tr-key">
                <?php echo escape($key); ?>
                <?php if ($missingThis): ?>
                  <div class="tr-missing-badge">FR missing</div>
                <?php endif; ?>
              </div>
              <div class="tr-field">
                <span class="tr-lang-tag en">EN</span>
                <textarea class="tr-textarea"
                          name="en[<?php echo escape($key); ?>]"
                          rows="<?php echo $rows; ?>"><?php echo escape($enVal); ?></textarea>
              </div>
              <div class="tr-field">
                <span class="tr-lang-tag fr">FR</span>
                <textarea class="tr-textarea <?php echo $missingThis ? 'fr-empty' : ''; ?>"
                          name="fr[<?php echo escape($key); ?>]"
                          rows="<?php echo $rows; ?>"
                          placeholder="Traduction française…"><?php echo escape($frVal); ?></textarea>
              </div>
              <div>
                <form method="post" style="display:inline;" onsubmit="return confirm('Delete key \'<?php echo escape($key); ?>\'? This cannot be undone.');">
                  <input type="hidden" name="csrf_token" value="<?php echo escape(csrf_token()); ?>">
                  <input type="hidden" name="action" value="delete">
                  <input type="hidden" name="del_key" value="<?php echo escape($key); ?>">
                  <button class="tr-delete-btn" type="submit" title="Delete this string">
                    <i class="bi bi-trash3"></i>
                  </button>
                </form>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  <?php endforeach; ?>

  <div style="margin-top:20px;display:flex;justify-content:flex-end;gap:10px;">
    <button class="btn btn-primary btn-lg" type="submit">
      <i class="bi bi-check-lg"></i> Save All Changes
    </button>
  </div>
</form>

<script>
function toggleGroup(header) {
  const body = header.nextElementSibling;
  const icon = header.querySelector('.bi-chevron-down, .bi-chevron-up');
  body.classList.toggle('open');
  if (icon) icon.className = body.classList.contains('open')
    ? icon.className.replace('bi-chevron-down','bi-chevron-up')
    : icon.className.replace('bi-chevron-up','bi-chevron-down');
}

// Auto-expand groups that contain search matches
<?php if ($search): ?>
document.querySelectorAll('.tr-group-body').forEach(b => b.classList.add('open'));
document.querySelectorAll('.tr-group-header .bi-chevron-down').forEach(i => i.className = i.className.replace('bi-chevron-down','bi-chevron-up'));
<?php endif; ?>

// Highlight missing FR fields
document.querySelectorAll('.tr-textarea.fr-empty').forEach(el => {
  el.addEventListener('input', () => {
    if (el.value.trim()) el.classList.remove('fr-empty');
  });
});
</script>

<?php include __DIR__ . '/_footer.php'; ?>
