<?php
require_once __DIR__ . '/includes/functions.php';
init_session();

$pageTitle = t('Career - Smartrack', 'Carrières - Smartrack');
$bodyClass = 'services-page';
$lang      = current_language();

// Active job listings
$jobs = db()->query(
    'SELECT * FROM job_postings WHERE is_active = 1 ORDER BY created_at DESC'
)->fetchAll();

$success    = isset($_GET['success']);
$errorCode  = $_GET['error'] ?? '';
$activeJobId = (int)($_GET['jid'] ?? 0);  // re-open modal on error

// Department → icon map
$deptIcons = [
    'Technology'       => 'bi-code-slash',
    'Customer Support' => 'bi-headset',
    'Field'            => 'bi-tools',
    'Sales'            => 'bi-megaphone-fill',
    'Marketing'        => 'bi-graph-up-arrow',
    'Finance'          => 'bi-cash-coin',
    'HR'               => 'bi-people-fill',
    'Management'       => 'bi-briefcase-fill',
    'Operations'       => 'bi-gear-fill',
];

define('APP_INIT', true);
include __DIR__ . '/includes/header.php';
?>

<!-- ─── Page Title ─────────────────────────────────────────── -->
<div class="page-title dark-background"
     style="background-image:url(<?php echo escape(site_url('assets/img/page-title-bg.jpg')); ?>);">
  <div class="container position-relative">
    <h1><?php echo escape(get_translation('career_page_title')); ?></h1>
    <nav class="breadcrumbs">
      <ol>
        <li><a href="<?php echo escape(site_url('index.php')); ?>"><?php echo escape(get_translation('breadcrumb_home')); ?></a></li>
        <li class="current"><?php echo escape(get_translation('breadcrumb_career')); ?></li>
      </ol>
    </nav>
  </div>
</div>

<!-- ─── Intro banner ──────────────────────────────────────── -->
<section class="get-started section">
  <div class="container">
    <div class="row justify-content-between gy-4 align-items-center">
      <div class="col-lg-7" data-aos="fade-right">
        <h3><?php echo escape(get_translation('career_intro_title')); ?></h3>
        <p><?php echo escape(get_translation('career_intro_p1')); ?></p>
        <p><?php echo escape(get_translation('career_intro_p2')); ?></p>
      </div>
      <div class="col-lg-4 text-center" data-aos="fade-left">
        <div style="background:rgba(255,0,0,.06);border:2px dashed rgba(255,0,0,.2);border-radius:16px;padding:36px 24px;">
          <i class="bi bi-people-fill" style="font-size:3rem;color:var(--accent-color);"></i>
          <h4 class="mt-3 mb-1"><?php echo count($jobs); ?> <?php echo count($jobs) !== 1 ? escape(get_translation('career_open_positions')) : escape(t('Open Position','Poste Ouvert')); ?></h4>
          <p class="mb-0" style="color:#666;font-size:.9rem;"><?php echo escape(get_translation('career_based_in')); ?></p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ─── Global alert ──────────────────────────────────────── -->
<?php if ($success): ?>
<div style="background:#d1fae5;border-left:4px solid #10b981;padding:18px 28px;max-width:760px;margin:0 auto 16px;border-radius:8px;">
  <strong><?php echo escape(get_translation('career_success')); ?></strong>
</div>
<?php elseif ($errorCode): ?>
<?php
$msg = match($errorCode) {
    'csrf'        => get_translation('career_err_csrf'),
    'fields'      => get_translation('career_err_fields'),
    'cv_required' => get_translation('career_err_cv'),
    default       => htmlspecialchars(urldecode($errorCode), ENT_QUOTES, 'UTF-8'),
};
?>
<div style="background:#fee2e2;border-left:4px solid #ef4444;padding:18px 28px;max-width:760px;margin:0 auto 16px;border-radius:8px;">
  <strong>⚠ <?php echo $msg; ?></strong>
</div>
<?php endif; ?>

<!-- ─── Job listings ──────────────────────────────────────── -->
<section id="open-positions" class="services section light-background">
  <div class="container section-title" data-aos="fade-up">
    <h2><?php echo escape(get_translation('career_open_positions')); ?></h2>
    <p><?php echo escape(get_translation('career_positions_sub')); ?></p>
  </div>

  <div class="container">
    <?php if (empty($jobs)): ?>
      <div class="text-center py-5" data-aos="fade-up">
        <i class="bi bi-briefcase" style="font-size:3.5rem;color:#ccc;"></i>
        <h4 class="mt-3" style="color:#888;"><?php echo escape(get_translation('career_no_positions')); ?></h4>
        <p style="color:#aaa;"><?php echo get_translation('career_no_positions_sub'); ?></p>
      </div>
    <?php else: ?>
      <div class="row gy-4">
        <?php foreach ($jobs as $i => $job): ?>
          <?php
          $title = $lang === 'fr' && $job['title_fr']
                   ? $job['title_fr'] : $job['title_en'];
          $desc  = $lang === 'fr' && $job['description_fr']
                   ? $job['description_fr'] : $job['description_en'] ?? '';
          $icon  = $deptIcons[$job['department']] ?? 'bi-person-workspace';
          $typeColors = [
            'Full-time'  => ['bg'=>'#dcfce7','color'=>'#16a34a'],
            'Part-time'  => ['bg'=>'#fef9c3','color'=>'#ca8a04'],
            'Contract'   => ['bg'=>'#dbeafe','color'=>'#2563eb'],
            'Internship' => ['bg'=>'#f3e8ff','color'=>'#9333ea'],
          ];
          $tc = $typeColors[$job['job_type']] ?? ['bg'=>'#f1f5f9','color'=>'#475569'];
          ?>
          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="<?php echo ($i % 4 + 1) * 100; ?>">
            <div class="service-item position-relative" style="padding-bottom:72px;">
              <!-- Type badge top-right -->
              <span style="position:absolute;top:20px;right:20px;background:<?php echo $tc['bg']; ?>;color:<?php echo $tc['color']; ?>;font-size:.72rem;font-weight:700;padding:4px 12px;border-radius:20px;letter-spacing:.05em;text-transform:uppercase;">
                <?php echo escape($job['job_type']); ?>
              </span>

              <div class="icon"><i class="bi <?php echo escape($icon); ?>"></i></div>
              <h3><?php echo escape($title); ?></h3>

              <div class="d-flex gap-2 flex-wrap mb-2" style="font-size:.8rem;color:#888;">
                <span><i class="bi bi-building me-1"></i><?php echo escape($job['department']); ?></span>
                <span>·</span>
                <span><i class="bi bi-geo-alt me-1"></i><?php echo escape($job['location']); ?></span>
              </div>

              <p style="font-size:.9rem;color:#666;-webkit-line-clamp:3;display:-webkit-box;-webkit-box-orient:vertical;overflow:hidden;">
                <?php echo escape($desc ?: 'Click below to learn more and apply.'); ?>
              </p>

              <button type="button"
                      class="btn-get-started apply-trigger"
                      style="position:absolute;bottom:20px;left:20px;padding:10px 28px;font-size:.9rem;"
                      data-job-id="<?php echo $job['id']; ?>"
                      data-job-title="<?php echo escape($title); ?>">
                <?php echo escape(get_translation('career_apply_now')); ?> <i class="bi bi-arrow-right ms-1"></i>
              </button>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>

<!-- ─── General application ──────────────────────────────── -->
<section id="general-apply" class="section">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="get-started" style="background:#fff;border-radius:16px;padding:48px;box-shadow:0 4px 32px rgba(0,0,0,.08);" data-aos="fade-up">

          <h3 class="mb-1"><?php echo escape(get_translation('career_no_match')); ?></h3>
          <p class="mb-4" style="color:#666;"><?php echo escape(get_translation('career_no_match_sub')); ?></p>

          <form action="<?php echo escape(site_url('apply-submit.php')); ?>"
                method="post" enctype="multipart/form-data"
                class="php-email-form">
            <input type="hidden" name="csrf_token" value="<?php echo escape(csrf_token()); ?>">
            <input type="hidden" name="job_id"    value="">
            <input type="hidden" name="job_title" value="General Application">

            <div class="row gy-3">
              <div class="col-md-6">
                <input type="text"  name="name"  class="form-control" placeholder="<?php echo escape(t('Full Name *','Nom Complet *')); ?>" required>
              </div>
              <div class="col-md-6">
                <input type="email" name="email" class="form-control" placeholder="<?php echo escape(t('Email Address *','Adresse Email *')); ?>" required>
              </div>
              <div class="col-12">
                <input type="text"  name="phone" class="form-control" placeholder="<?php echo escape(t('Phone Number','Numéro de Téléphone')); ?>">
              </div>
              <div class="col-12">
                <textarea name="cover_letter" class="form-control" rows="4"
                          placeholder="<?php echo escape(t("Tell us about yourself and what kind of role you're looking for…","Parlez-nous de vous et du type de poste que vous recherchez…")); ?>"></textarea>
              </div>
              <div class="col-12">
                <label style="font-weight:600;margin-bottom:6px;display:block;">
                  <?php echo escape(get_translation('career_upload_cv')); ?> <span style="color:#e53935;">*</span>
                  <small style="font-weight:400;color:#888;margin-left:8px;"><?php echo escape(get_translation('career_cv_hint')); ?></small>
                </label>
                <input type="file" name="cv" class="form-control" accept=".pdf,.doc,.docx" required>
              </div>
              <div class="col-12 text-center">
                <button type="submit"><?php echo escape(get_translation('career_submit')); ?> <i class="bi bi-send-fill ms-2"></i></button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ─── Application Modal ─────────────────────────────────── -->
<div class="modal fade" id="applyModal" tabindex="-1" aria-labelledby="applyModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content" style="border-radius:16px;border:none;overflow:hidden;">

      <div class="modal-header" style="background:var(--accent-color);color:#fff;border:none;padding:20px 28px;">
        <div>
          <h5 class="modal-title mb-0" id="applyModalLabel"><?php echo escape(get_translation('career_apply_modal_title')); ?> <strong id="modalJobDisplay"><?php echo escape(get_translation('career_this_position')); ?></strong></h5>
          <small style="opacity:.8;"><?php echo escape(get_translation('career_modal_required')); ?></small>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body" style="padding:32px 36px;">
        <form action="<?php echo escape(site_url('apply-submit.php')); ?>"
              method="post" enctype="multipart/form-data"
              id="modalApplyForm">
          <input type="hidden" name="csrf_token" value="<?php echo escape(csrf_token()); ?>">
          <input type="hidden" name="job_id"     id="modalJobId">
          <input type="hidden" name="job_title"  id="modalJobTitle">

          <div class="row gy-3">
            <div class="col-md-6">
              <label class="form-label fw-semibold"><?php echo escape(t('Full Name *','Nom Complet *')); ?></label>
              <input type="text"  name="name"  class="form-control" placeholder="Jean-Pierre Mbeki" required>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold"><?php echo escape(t('Email Address *','Adresse Email *')); ?></label>
              <input type="email" name="email" class="form-control" placeholder="you@example.com" required>
            </div>
            <div class="col-12">
              <label class="form-label fw-semibold"><?php echo escape(t('Phone Number','Numéro de Téléphone')); ?></label>
              <input type="text"  name="phone" class="form-control" placeholder="+237 6xx xxx xxx">
            </div>
            <div class="col-12">
              <label class="form-label fw-semibold"><?php echo escape(get_translation('career_modal_cover')); ?></label>
              <textarea name="cover_letter" class="form-control" rows="5"
                        placeholder="<?php echo escape(t("Tell us why you're a great fit for this role…","Dites-nous pourquoi vous êtes fait pour ce poste…")); ?>"></textarea>
            </div>
            <div class="col-12">
              <label class="form-label fw-semibold">
                <?php echo escape(t('Upload CV *','Télécharger CV *')); ?>
                <small class="text-muted fw-normal ms-2"><?php echo escape(get_translation('career_cv_hint')); ?></small>
              </label>
              <input type="file" name="cv" class="form-control" accept=".pdf,.doc,.docx" required>
            </div>
          </div>
        </form>
      </div>

      <div class="modal-footer" style="border:none;padding:16px 36px 28px;gap:12px;">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                style="padding:10px 24px;border-radius:8px;"><?php echo escape(get_translation('career_modal_cancel')); ?></button>
        <button type="submit" form="modalApplyForm"
                style="background:var(--accent-color);color:#fff;border:none;padding:10px 32px;border-radius:8px;font-weight:600;font-size:.95rem;cursor:pointer;">
          <?php echo escape(get_translation('career_submit')); ?> <i class="bi bi-send-fill ms-2"></i>
        </button>
      </div>

    </div>
  </div>
</div>

<script>
// Populate and open the modal when any "Apply Now" card button is clicked
document.querySelectorAll('.apply-trigger').forEach(btn => {
  btn.addEventListener('click', () => {
    const title = btn.dataset.jobTitle;
    const id    = btn.dataset.jobId;
    document.getElementById('modalJobDisplay').textContent = title;
    document.getElementById('modalJobId').value    = id;
    document.getElementById('modalJobTitle').value = title;
    // Reset the form inputs
    document.getElementById('modalApplyForm').reset();
    document.getElementById('modalJobId').value    = id;
    document.getElementById('modalJobTitle').value = title;
    new bootstrap.Modal(document.getElementById('applyModal')).show();
  });
});

<?php if ($activeJobId && $errorCode): ?>
// Re-open the modal automatically after a validation error
window.addEventListener('load', () => {
  const jobId = <?php echo $activeJobId; ?>;
  const btn = document.querySelector('[data-job-id="' + jobId + '"]');
  if (btn) btn.click();
});
<?php endif; ?>
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
