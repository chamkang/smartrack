<?php
require_once __DIR__ . '/../includes/functions.php';
require_admin();
$pageTitle = 'Translations';
$error = '';
$selectedLang = $_GET['lang'] ?? current_language();
$availableLangs = ['en', 'fr'];

if (!in_array($selectedLang, $availableLangs, true)) {
    $selectedLang = 'en';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid CSRF token.';
    } else {
        $updates = $_POST['value'] ?? [];
        $stmt = db()->prepare('UPDATE translations SET value = :value WHERE string_key = :key AND lang = :lang');
        foreach ($updates as $key => $value) {
            $stmt->execute([':value' => trim($value), ':key' => $key, ':lang' => $selectedLang]);
        }
        redirect('translations.php?lang=' . urlencode($selectedLang));
    }
}

$stmt = db()->prepare('SELECT string_key, value FROM translations WHERE lang = :lang ORDER BY string_key ASC');
$stmt->execute([':lang' => $selectedLang]);
$translations = $stmt->fetchAll();
define('APP_INIT_ADMIN', true);
include __DIR__ . '/_header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <h1>Translations</h1>
  <form method="get" class="d-flex gap-2">
    <label class="form-label mb-0 align-self-center">Language</label>
    <select class="form-select" name="lang" onchange="this.form.submit()">
      <?php foreach ($availableLangs as $lang): ?>
        <option value="<?php echo escape($lang); ?>" <?php echo $lang === $selectedLang ? 'selected' : ''; ?>><?php echo escape(strtoupper($lang)); ?></option>
      <?php endforeach; ?>
    </select>
  </form>
</div>
<?php if ($error): ?>
  <div class="alert alert-danger"><?php echo escape($error); ?></div>
<?php endif; ?>
<form method="post">
  <input type="hidden" name="csrf_token" value="<?php echo escape(csrf_token()); ?>">
  <div class="card p-4">
    <div class="row gy-3">
      <?php foreach ($translations as $translation): ?>
        <div class="col-md-6">
          <label class="form-label"><?php echo escape($translation['string_key']); ?></label>
          <textarea class="form-control" name="value[<?php echo escape($translation['string_key']); ?>]" rows="2"><?php echo escape($translation['value']); ?></textarea>
        </div>
      <?php endforeach; ?>
    </div>
    <button class="btn btn-primary mt-4" type="submit">Save Translations</button>
  </div>
</form>
<?php include __DIR__ . '/_footer.php'; ?>