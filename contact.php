<?php
require_once __DIR__ . '/includes/functions.php';
init_session();

$pageTitle = 'Contact - Smartrack';
$bodyClass = 'contact-page';
$lang      = current_language();
$contact   = get_contact();
$success   = isset($_GET['success']);
$errorCode = $_GET['error'] ?? '';
$prevType  = $_GET['type']  ?? 'quote';   // restore active tab after error
?>
<!DOCTYPE html>
<html lang="<?php echo escape($lang); ?>">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title><?php echo escape($pageTitle); ?></title>
  <meta name="base-url" content="<?php echo escape(rtrim(BASE_URL,'/')); ?>">

  <link href="<?php echo escape(site_url('assets/img/st logo.png')); ?>" rel="icon">
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&family=Open+Sans:wght@300;400;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link href="<?php echo escape(site_url('assets/vendor/bootstrap/css/bootstrap.min.css')); ?>" rel="stylesheet">
  <link href="<?php echo escape(site_url('assets/vendor/bootstrap-icons/bootstrap-icons.css')); ?>" rel="stylesheet">
  <link href="<?php echo escape(site_url('assets/vendor/aos/aos.css')); ?>" rel="stylesheet">
  <link href="<?php echo escape(site_url('assets/vendor/fontawesome-free/css/all.min.css')); ?>" rel="stylesheet">
  <link href="<?php echo escape(site_url('assets/css/main.css')); ?>" rel="stylesheet">

  <style>
  /* ── Contact page custom styles ────────────────────────── */
  :root {
    --red: #e60000;
    --red-dark: #b30000;
    --red-light: rgba(230,0,0,.08);
    --dark: #0b0e1a;
    --dark2: #14192e;
    --t: .25s cubic-bezier(.4,0,.2,1);
  }

  /* Info bar */
  .cinfo-bar { background:#f8f9fa; border-bottom:1px solid #eee; padding:36px 0; }
  .cinfo-card {
    display:flex; align-items:center; gap:18px;
    padding:22px 26px; background:#fff;
    border-radius:14px; box-shadow:0 2px 16px rgba(0,0,0,.07);
    transition:box-shadow var(--t), transform var(--t);
    height:100%;
  }
  .cinfo-card:hover { box-shadow:0 6px 28px rgba(0,0,0,.12); transform:translateY(-3px); }
  .cinfo-icon {
    width:54px; height:54px; border-radius:14px; flex-shrink:0;
    display:flex; align-items:center; justify-content:center;
    font-size:1.4rem;
  }
  .cinfo-icon.red    { background:rgba(230,0,0,.1); color:var(--red); }
  .cinfo-icon.green  { background:rgba(22,163,74,.1); color:#16a34a; }
  .cinfo-icon.blue   { background:rgba(37,99,235,.1); color:#2563eb; }
  .cinfo-label { font-size:.72rem; text-transform:uppercase; letter-spacing:.08em; font-weight:700; color:#999; }
  .cinfo-value { font-size:.975rem; font-weight:600; color:#1a202c; margin-top:2px; }

  /* Main contact section */
  .contact-main { padding:80px 0; }

  /* Left info panel */
  .contact-panel {
    background:linear-gradient(145deg, var(--dark) 0%, var(--dark2) 100%);
    border-radius:20px; padding:44px 40px;
    color:#fff; position:relative; overflow:hidden; height:100%;
  }
  .contact-panel::before {
    content:''; position:absolute; inset:0;
    background:radial-gradient(ellipse 80% 60% at 20% 30%, rgba(230,0,0,.18) 0%, transparent 60%),
                radial-gradient(ellipse 60% 80% at 85% 85%, rgba(230,0,0,.08) 0%, transparent 60%);
  }
  .contact-panel-grid {
    position:absolute; inset:0;
    background-image:linear-gradient(rgba(255,255,255,.03) 1px, transparent 1px),
                     linear-gradient(90deg, rgba(255,255,255,.03) 1px, transparent 1px);
    background-size:36px 36px;
  }
  .contact-panel-content { position:relative; z-index:1; }

  .panel-heading { font-size:1.75rem; font-weight:800; letter-spacing:-.03em; margin-bottom:8px; }
  .panel-sub { color:rgba(255,255,255,.55); font-size:.9rem; line-height:1.7; margin-bottom:36px; }

  .panel-info-row {
    display:flex; align-items:flex-start; gap:14px;
    padding:14px 0; border-bottom:1px solid rgba(255,255,255,.07);
  }
  .panel-info-row:last-of-type { border-bottom:none; }
  .panel-info-icon {
    width:38px; height:38px; border-radius:10px;
    background:rgba(255,255,255,.07); border:1px solid rgba(255,255,255,.1);
    display:flex; align-items:center; justify-content:center;
    font-size:.95rem; color:var(--red); flex-shrink:0; margin-top:2px;
  }
  .panel-info-label { font-size:.72rem; text-transform:uppercase; letter-spacing:.08em; color:rgba(255,255,255,.4); font-weight:700; }
  .panel-info-value { font-size:.9rem; color:rgba(255,255,255,.85); margin-top:2px; font-weight:500; line-height:1.5; }
  .panel-info-value a { color:rgba(255,255,255,.85); text-decoration:none; }
  .panel-info-value a:hover { color:#fff; }

  .panel-socials { display:flex; gap:10px; margin-top:28px; }
  .panel-social-btn {
    width:40px; height:40px; border-radius:10px;
    background:rgba(255,255,255,.07); border:1px solid rgba(255,255,255,.1);
    display:flex; align-items:center; justify-content:center;
    color:rgba(255,255,255,.6); text-decoration:none; font-size:1rem;
    transition:background var(--t), color var(--t), border-color var(--t);
  }
  .panel-social-btn:hover { background:var(--red); border-color:var(--red); color:#fff; }

  .panel-badges { margin-top:32px; display:flex; flex-direction:column; gap:10px; }
  .panel-badge {
    display:flex; align-items:center; gap:10px;
    font-size:.82rem; color:rgba(255,255,255,.65);
  }
  .panel-badge i { color:var(--red); font-size:.9rem; }

  /* GPS ping decoration */
  .gps-ping {
    position:absolute; bottom:32px; right:32px; z-index:1;
    width:12px; height:12px;
  }
  .gps-ping-dot {
    width:12px; height:12px; border-radius:50%;
    background:var(--red); box-shadow:0 0 10px 3px rgba(230,0,0,.4);
  }
  .gps-ping-ring {
    position:absolute; top:50%; left:50%;
    transform:translate(-50%,-50%);
    border-radius:50%; border:1.5px solid var(--red);
    animation:gps-expand 2.6s ease-out infinite;
  }
  .gps-ping-ring:nth-child(2) { animation-delay:.65s; border-color:rgba(230,0,0,.5); }
  .gps-ping-ring:nth-child(3) { animation-delay:1.3s;  border-color:rgba(230,0,0,.25); }
  @keyframes gps-expand {
    0%   { width:12px; height:12px; opacity:.9; }
    100% { width:80px; height:80px; opacity:0;  }
  }

  /* Form card */
  .contact-form-card {
    background:#fff; border-radius:20px;
    padding:44px 40px; box-shadow:0 4px 40px rgba(0,0,0,.10);
    height:100%;
  }

  .form-card-title { font-size:1.5rem; font-weight:800; color:#1a202c; letter-spacing:-.02em; margin-bottom:6px; }
  .form-card-sub   { font-size:.875rem; color:#64748b; margin-bottom:28px; }

  /* Type toggle */
  .type-toggle { display:flex; background:#f1f5f9; border-radius:12px; padding:5px; gap:4px; margin-bottom:28px; }
  .type-btn {
    flex:1; display:flex; align-items:center; justify-content:center; gap:8px;
    padding:11px 20px; border:none; border-radius:9px; cursor:pointer;
    font-family:inherit; font-size:.875rem; font-weight:600;
    color:#64748b; background:transparent;
    transition:all var(--t);
  }
  .type-btn.active {
    background:#fff; color:var(--red);
    box-shadow:0 2px 12px rgba(0,0,0,.10);
  }
  .type-btn i { font-size:1rem; }

  /* Form fields */
  .cf-group { margin-bottom:18px; position:relative; }
  .cf-label {
    display:block; font-size:.72rem; font-weight:700; text-transform:uppercase;
    letter-spacing:.08em; color:#64748b; margin-bottom:7px;
  }
  .cf-label .req { color:var(--red); margin-left:3px; }
  .cf-input-wrap { position:relative; }
  .cf-input-icon {
    position:absolute; left:14px; top:50%; transform:translateY(-50%);
    color:#94a3b8; font-size:.95rem; pointer-events:none;
    transition:color var(--t);
  }
  .cf-textarea-icon { top:14px; transform:none; }
  .cf-control {
    width:100%; padding:12px 14px 12px 42px;
    border:1.5px solid #e2e8f0; border-radius:10px;
    font-family:inherit; font-size:.9rem; color:#1a202c;
    background:#fafafa; outline:none;
    transition:border-color var(--t), background var(--t), box-shadow var(--t);
    line-height:1.5;
  }
  .cf-control:focus {
    border-color:var(--red); background:#fff;
    box-shadow:0 0 0 4px rgba(230,0,0,.10);
  }
  .cf-control:focus + .cf-input-icon,
  .cf-input-wrap:has(.cf-control:focus) .cf-input-icon { color:var(--red); }
  textarea.cf-control { resize:vertical; min-height:130px; padding-top:14px; }

  .cf-row { display:grid; grid-template-columns:1fr 1fr; gap:14px; }
  @media(max-width:540px){ .cf-row { grid-template-columns:1fr; } }

  /* Submit btn */
  .cf-submit {
    width:100%; padding:14px 24px; margin-top:8px;
    border:none; border-radius:10px; cursor:pointer;
    font-family:inherit; font-size:1rem; font-weight:700; color:#fff;
    background:linear-gradient(135deg, var(--red) 0%, var(--red-dark) 100%);
    background-size:200%;
    display:flex; align-items:center; justify-content:center; gap:10px;
    position:relative; overflow:hidden;
    transition:box-shadow var(--t), transform var(--t);
    box-shadow:0 4px 18px rgba(230,0,0,.35);
  }
  .cf-submit::before {
    content:''; position:absolute; top:-50%; left:-75%; width:50%; height:200%;
    background:linear-gradient(105deg, transparent 20%, rgba(255,255,255,.18) 50%, transparent 80%);
    animation:cf-shimmer 3s ease-in-out infinite;
  }
  @keyframes cf-shimmer { 0%{left:-75%} 60%,100%{left:125%} }
  .cf-submit:hover { box-shadow:0 8px 28px rgba(230,0,0,.45); transform:translateY(-1px); }
  .cf-submit:active { transform:translateY(0); }

  /* Alert */
  .cf-alert {
    border-radius:10px; padding:14px 18px; font-size:.9rem; font-weight:500;
    display:flex; align-items:center; gap:12px; margin-bottom:20px;
  }
  .cf-alert.success { background:rgba(22,163,74,.08); border:1px solid rgba(22,163,74,.3); color:#15803d; }
  .cf-alert.error   { background:rgba(230,0,0,.07); border:1px solid rgba(230,0,0,.25); color:#b91c1c; }

  /* Responsive */
  @media(max-width:991px){
    .contact-panel { margin-bottom:28px; }
    .contact-form-card { padding:32px 24px; }
  }
  @media(max-width:767px){
    .contact-main { padding:48px 0; }
    .cinfo-bar { padding:24px 0; }
  }
  </style>
</head>

<body class="contact-page">

<?php
// Include nav/header manually (we need full control of head above)
// but still honour APP_INIT guard in header.php
define('APP_INIT', true);

// Output just the nav — pull from header.php minus the DOCTYPE/head
$language = current_language();
$baseUrl  = rtrim(BASE_URL, '/');

$currentFile = 'contact.php';
function nav_is_active_c(string $page): string {
    return $page === 'contact.php' ? ' class="active"' : '';
}
?>

<header id="header" class="header d-flex align-items-center fixed-top">
  <div class="container-fluid container-xl position-relative d-flex align-items-center justify-content-between">
    <a href="<?php echo escape(site_url('index.php')); ?>" class="logo d-flex align-items-center">
      <img src="<?php echo escape($baseUrl); ?>/assets/img/st logo.png" alt="">
      <h1 class="sitename">SMAR<span class="text-danger">TRACK</span></h1>
    </a>
    <nav id="navmenu" class="navmenu">
      <ul>
        <li><a href="<?php echo escape(site_url('index.php')); ?>"><span id="n1"><?php echo escape(get_translation('n1')); ?></span></a></li>
        <li><a href="<?php echo escape(site_url('about.php')); ?>"><span id="n2"><?php echo escape(get_translation('n2')); ?></span></a></li>
        <li class="dropdown">
          <a href="#"><span>Services</span><i class="bi bi-chevron-down toggle-dropdown"></i></a>
          <ul>
            <li><a href="<?php echo escape(site_url('SmartFleet.php')); ?>">SmartFleet</a></li>
            <li><a href="<?php echo escape(site_url('SmartSolution.php')); ?>">SmartSolution</a></li>
            <li><a href="<?php echo escape(site_url('devices.php')); ?>"><span id="n3"><?php echo escape(get_translation('n3')); ?></span></a></li>
          </ul>
        </li>
        <li><a href="<?php echo escape(site_url('blog.php')); ?>">Blog</a></li>
        <li><a href="<?php echo escape(site_url('contact.php')); ?>" class="active">Contact</a></li>
        <li><a href="<?php echo escape(site_url('career.php')); ?>"><span id="n4"><?php echo escape(get_translation('n4')); ?></span></a></li>
        <li class="dropdown">
          <a href="#"><span id="langu"><?php echo strtoupper($language); ?></span><i class="bi bi-globe toggle-dropdown"></i></a>
          <ul>
            <li><a href="?lang=en" id="lang-en">EN</a></li>
            <li><a href="?lang=fr" id="lang-fr">FR</a></li>
          </ul>
        </li>
      </ul>
      <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
    </nav>
  </div>
</header>

<main class="main">

<!-- ── Page Title ─────────────────────────────────────────── -->
<div class="page-title dark-background"
     style="background-image:url(<?php echo escape(site_url('assets/img/page-title-bg.jpg')); ?>);">
  <div class="container position-relative">
    <h1><?php echo escape(get_translation('nav_contact')); ?></h1>
    <nav class="breadcrumbs">
      <ol>
        <li><a href="<?php echo escape(site_url('index.php')); ?>"><?php echo escape(get_translation('breadcrumb_home')); ?></a></li>
        <li class="current"><?php echo escape(get_translation('nav_contact')); ?></li>
      </ol>
    </nav>
  </div>
</div>

<!-- ── Info bar ────────────────────────────────────────────── -->
<div class="cinfo-bar">
  <div class="container">
    <div class="row g-4">
      <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
        <div class="cinfo-card">
          <div class="cinfo-icon red"><i class="bi bi-geo-alt-fill"></i></div>
          <div>
            <div class="cinfo-label"><?php echo escape(get_translation('contact_our_office')); ?></div>
            <div class="cinfo-value"><?php echo escape(!empty($contact['address_' . $lang]) ? $contact['address_' . $lang] : ($contact['address_en'] ?? 'Suite 019, Immeuble Axia Avenue de Gaulle, B.P 13255 Douala-Bonanjo')); ?></div>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
        <div class="cinfo-card">
          <div class="cinfo-icon green"><i class="bi bi-telephone-fill"></i></div>
          <div>
            <div class="cinfo-label"><?php echo escape(get_translation('contact_call_us')); ?></div>
            <div class="cinfo-value">
              <a href="tel:<?php echo escape($contact['phone'] ?? ''); ?>" style="color:inherit;text-decoration:none;">
                <?php echo escape($contact['phone'] ?? '+237 691 415 588'); ?>
              </a>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
        <div class="cinfo-card">
          <div class="cinfo-icon blue"><i class="bi bi-envelope-fill"></i></div>
          <div>
            <div class="cinfo-label"><?php echo escape(get_translation('contact_email_us')); ?></div>
            <div class="cinfo-value">
              <a href="mailto:<?php echo escape($contact['email'] ?? ''); ?>" style="color:inherit;text-decoration:none;">
                <?php echo escape($contact['email'] ?? 'info@smartrackafrica.com'); ?>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- ── Main contact section ───────────────────────────────── -->
<section class="contact-main">
  <div class="container">
    <div class="row g-5 align-items-stretch">

      <!-- Left: info panel -->
      <div class="col-lg-5" data-aos="fade-right">
        <div class="contact-panel">
          <div class="contact-panel-grid"></div>
          <div class="contact-panel-content">

            <h2 class="panel-heading"><?php echo escape(get_translation('contact_lets_talk')); ?></h2>
            <p class="panel-sub"><?php echo escape(get_translation('contact_lets_talk_sub')); ?></p>

            <!-- Contact details -->
            <div class="panel-info-row">
              <div class="panel-info-icon" style="background:rgba(34,197,94,.12);border-color:rgba(34,197,94,.2);color:#22c55e;">
                <i class="bi bi-headset"></i>
              </div>
              <div>
                <div class="panel-info-label"><?php echo escape(get_translation('contact_sales')); ?></div>
                <div class="panel-info-value">
                  <a href="tel:+237691415588">+237 691 415 588</a>
                </div>
              </div>
            </div>

            <div class="panel-info-row">
              <div class="panel-info-icon" style="background:rgba(59,130,246,.12);border-color:rgba(59,130,246,.2);color:#3b82f6;">
                <i class="bi bi-wrench-adjustable-circle-fill"></i>
              </div>
              <div>
                <div class="panel-info-label"><?php echo escape(get_translation('contact_tech_dir')); ?></div>
                <div class="panel-info-value">
                  <a href="tel:+237699902466">+237 699 902 466</a>
                </div>
              </div>
            </div>

            <div class="panel-info-row">
              <div class="panel-info-icon" style="background:rgba(245,158,11,.12);border-color:rgba(245,158,11,.2);color:#f59e0b;">
                <i class="bi bi-boxes"></i>
              </div>
              <div>
                <div class="panel-info-label"><?php echo escape(get_translation('contact_inv_mgr')); ?></div>
                <div class="panel-info-value">
                  <a href="tel:+237698800298">+237 698 800 298</a>
                </div>
              </div>
            </div>

            <?php if (!empty($contact['email'])): ?>
            <div class="panel-info-row">
              <div class="panel-info-icon"><i class="bi bi-envelope-fill"></i></div>
              <div>
                <div class="panel-info-label">Email</div>
                <div class="panel-info-value"><a href="mailto:<?php echo escape($contact['email']); ?>"><?php echo escape($contact['email']); ?></a></div>
              </div>
            </div>
            <?php endif; ?>

            <?php
            $addr = !empty($contact['address_' . $lang]) ? $contact['address_' . $lang] : ($contact['address_en'] ?? '');
            if ($addr):
            ?>
            <div class="panel-info-row">
              <div class="panel-info-icon"><i class="bi bi-geo-alt-fill"></i></div>
              <div>
                <div class="panel-info-label"><?php echo escape(t('Address', 'Adresse')); ?></div>
                <div class="panel-info-value"><?php echo escape($addr); ?></div>
              </div>
            </div>
            <?php endif; ?>

            <!-- Social links -->
            <div class="panel-socials">
              <?php
              $socials = [
                'facebook'  => ['bi-facebook',  'Facebook'],
                'twitter'   => ['bi-twitter-x', 'Twitter'],
                'instagram' => ['bi-instagram', 'Instagram'],
                'linkedin'  => ['bi-linkedin',  'LinkedIn'],
              ];
              foreach ($socials as $key => [$icon, $label]):
                if (empty($contact[$key])) continue;
              ?>
                <a href="<?php echo escape($contact[$key]); ?>" target="_blank" rel="noopener"
                   class="panel-social-btn" title="<?php echo $label; ?>">
                  <i class="bi <?php echo $icon; ?>"></i>
                </a>
              <?php endforeach; ?>
            </div>

            <!-- Trust badges -->
            <div class="panel-badges">
              <div class="panel-badge"><i class="bi bi-check-circle-fill"></i> <?php echo escape(get_translation('contact_respond')); ?></div>
              <div class="panel-badge"><i class="bi bi-check-circle-fill"></i> <?php echo escape(get_translation('contact_consult')); ?></div>
              <div class="panel-badge"><i class="bi bi-check-circle-fill"></i> <?php echo escape(get_translation('contact_no_commit')); ?></div>
            </div>

          </div>

          <!-- GPS ping decoration -->
          <div class="gps-ping">
            <div class="gps-ping-dot"></div>
            <div class="gps-ping-ring"></div>
            <div class="gps-ping-ring"></div>
            <div class="gps-ping-ring"></div>
          </div>
        </div>
      </div>

      <!-- Right: unified form -->
      <div class="col-lg-7" data-aos="fade-left">
        <div class="contact-form-card">

          <h3 class="form-card-title"><?php echo escape(get_translation('contact_help_title')); ?></h3>
          <p class="form-card-sub"><?php echo escape(get_translation('contact_fill_form')); ?></p>

          <!-- Success / error alerts -->
          <?php if ($success): ?>
            <div class="cf-alert success">
              <i class="bi bi-check-circle-fill" style="font-size:1.2rem;flex-shrink:0;"></i>
              <div>
                <strong><?php echo escape(get_translation('contact_msg_sent')); ?></strong>
                <?php if (($_GET['type'] ?? '') === 'quote'): ?>
                  <?php echo escape(get_translation('contact_success_quote')); ?>
                <?php else: ?>
                  <?php echo escape(get_translation('contact_success_msg')); ?>
                <?php endif; ?>
              </div>
            </div>
          <?php elseif ($errorCode === 'fields'): ?>
            <div class="cf-alert error">
              <i class="bi bi-exclamation-triangle-fill" style="font-size:1.2rem;flex-shrink:0;"></i>
              <div><?php echo escape(get_translation('contact_error_fields')); ?></div>
            </div>
          <?php elseif ($errorCode === 'csrf'): ?>
            <div class="cf-alert error">
              <i class="bi bi-shield-exclamation" style="font-size:1.2rem;flex-shrink:0;"></i>
              <div><?php echo t('<strong>Security error.</strong> Please refresh the page and try again.', '<strong>Erreur de sécurité.</strong> Veuillez actualiser la page et réessayer.'); ?></div>
            </div>
          <?php endif; ?>

          <form action="<?php echo escape(site_url('contact-unified.php')); ?>"
                method="post" id="contactForm" novalidate>
            <input type="hidden" name="csrf_token" value="<?php echo escape(csrf_token()); ?>">
            <input type="hidden" name="type" id="formType" value="<?php echo escape($prevType); ?>">

            <!-- Type toggle -->
            <div class="type-toggle" role="group" aria-label="Enquiry type">
              <button type="button" class="type-btn <?php echo $prevType !== 'message' ? 'active' : ''; ?>"
                      id="btnQuote" onclick="setType('quote')">
                <i class="bi bi-file-earmark-text-fill"></i>
                <?php echo escape(get_translation('contact_tab_quote')); ?>
              </button>
              <button type="button" class="type-btn <?php echo $prevType === 'message' ? 'active' : ''; ?>"
                      id="btnMessage" onclick="setType('message')">
                <i class="bi bi-chat-dots-fill"></i>
                <?php echo escape(get_translation('contact_tab_msg')); ?>
              </button>
            </div>

            <!-- Name + Email -->
            <div class="cf-row">
              <div class="cf-group">
                <label class="cf-label"><?php echo escape(get_translation('contact_name')); ?> <span class="req">*</span></label>
                <div class="cf-input-wrap">
                  <input type="text" name="name" class="cf-control"
                         placeholder="Jean-Pierre Mbeki" required
                         value="<?php echo escape($_GET['name'] ?? ''); ?>">
                  <i class="bi bi-person-fill cf-input-icon"></i>
                </div>
              </div>
              <div class="cf-group">
                <label class="cf-label"><?php echo escape(get_translation('contact_email_field')); ?> <span class="req">*</span></label>
                <div class="cf-input-wrap">
                  <input type="email" name="email" class="cf-control"
                         placeholder="you@example.com" required
                         value="<?php echo escape($_GET['email'] ?? ''); ?>">
                  <i class="bi bi-envelope-fill cf-input-icon"></i>
                </div>
              </div>
            </div>

            <!-- Phone (always visible, required for quote) -->
            <div class="cf-group">
              <label class="cf-label" id="phoneLabel">
                <?php echo escape(get_translation('contact_phone_field')); ?> <span class="req" id="phoneReq">*</span>
              </label>
              <div class="cf-input-wrap">
                <input type="tel" name="phone" class="cf-control" id="phoneField"
                       placeholder="+237 6xx xxx xxx"
                       value="<?php echo escape($_GET['phone'] ?? ''); ?>">
                <i class="bi bi-telephone-fill cf-input-icon"></i>
              </div>
            </div>

            <!-- Subject (message only) -->
            <div class="cf-group" id="subjectGroup" style="display:none;">
              <label class="cf-label"><?php echo escape(get_translation('contact_subject')); ?></label>
              <div class="cf-input-wrap">
                <input type="text" name="subject" class="cf-control"
                       placeholder="<?php echo escape(t('What is your enquiry about?', 'Quel est l\'objet de votre demande ?')); ?>"
                       value="<?php echo escape($_GET['subject'] ?? ''); ?>">
                <i class="bi bi-tag-fill cf-input-icon"></i>
              </div>
            </div>

            <!-- Context label (quote only) -->
            <div class="cf-group" id="serviceGroup">
              <label class="cf-label"><?php echo escape(get_translation('contact_svc_interest')); ?></label>
              <div class="cf-input-wrap">
                <select name="service_interest" class="cf-control" style="padding-left:42px;cursor:pointer;">
                  <option value=""><?php echo escape(t('— Select a service (optional) —', '— Sélectionnez un service (optionnel) —')); ?></option>
                  <option value="Fleet Tracking"><?php echo escape(t('Fleet Tracking', 'Suivi de Flotte')); ?></option>
                  <option value="Fuel Monitoring"><?php echo escape(t('Fuel Monitoring', 'Surveillance Carburant')); ?></option>
                  <option value="Security Solutions"><?php echo escape(t('Security Solutions', 'Solutions de Sécurité')); ?></option>
                  <option value="Fire Detection"><?php echo escape(t('Fire Detection', 'Détection Incendie')); ?></option>
                  <option value="Video Surveillance"><?php echo escape(t('Video Surveillance', 'Vidéosurveillance')); ?></option>
                  <option value="Network Security"><?php echo escape(t('Network Security', 'Sécurité Réseau')); ?></option>
                  <option value="Access Control"><?php echo escape(t('Access Control', 'Contrôle d\'Accès')); ?></option>
                  <option value="Other"><?php echo escape(t('Other', 'Autre')); ?></option>
                </select>
                <i class="bi bi-grid-fill cf-input-icon"></i>
              </div>
            </div>

            <!-- Message -->
            <div class="cf-group">
              <label class="cf-label"><?php echo escape(get_translation('contact_your_msg')); ?> <span class="req">*</span></label>
              <div class="cf-input-wrap">
                <textarea name="message" class="cf-control" id="msgField"
                          placeholder="<?php echo escape(t("Tell us about your fleet size, current challenges, or what you'd like to know…", 'Parlez-nous de la taille de votre flotte, de vos défis actuels ou de ce que vous souhaitez savoir…')); ?>"
                          required rows="5"><?php echo escape($_GET['message'] ?? ''); ?></textarea>
                <i class="bi bi-chat-text-fill cf-input-icon cf-textarea-icon"></i>
              </div>
            </div>

            <button type="submit" class="cf-submit" id="submitBtn">
              <i class="bi bi-send-fill"></i>
              <span id="submitLabel"><?php echo escape(get_translation('contact_submit_quote')); ?></span>
            </button>

          </form>
        </div>
      </div>

    </div>
  </div>
</section>

</main>

<?php
// Render footer
$contact = get_contact(); // already set above, re-use
include __DIR__ . '/includes/footer.php';
?>

<script>
const labels = {
  quote: {
    submitLabel:    '<?php echo addslashes(get_translation('contact_submit_quote')); ?>',
    msgPlaceholder: '<?php echo addslashes(t('Tell us about your fleet size, current challenges, or what you\'d like to know…','Parlez-nous de la taille de votre flotte, de vos défis actuels ou de ce que vous souhaitez savoir…')); ?>',
  },
  message: {
    submitLabel:    '<?php echo addslashes(get_translation('contact_submit_msg')); ?>',
    msgPlaceholder: '<?php echo addslashes(t('Write your question or message here…','Écrivez votre question ou votre message ici…')); ?>',
  }
};

function setType(type) {
  document.getElementById('formType').value = type;

  // Toggle buttons
  document.getElementById('btnQuote').classList.toggle('active',   type === 'quote');
  document.getElementById('btnMessage').classList.toggle('active', type === 'message');

  // Phone: required for quote, optional for message
  const phoneField = document.getElementById('phoneField');
  const phoneReq   = document.getElementById('phoneReq');
  phoneField.required = (type === 'quote');
  phoneReq.style.display = (type === 'quote') ? '' : 'none';

  // Subject: only for message
  document.getElementById('subjectGroup').style.display  = (type === 'message') ? '' : 'none';
  // Service: only for quote
  document.getElementById('serviceGroup').style.display  = (type === 'quote')   ? '' : 'none';

  // Labels
  document.getElementById('submitLabel').textContent = labels[type].submitLabel;
  document.getElementById('msgField').placeholder    = labels[type].msgPlaceholder;
}

// Init on load
setType(document.getElementById('formType').value || 'quote');

// Button loading state
document.getElementById('contactForm').addEventListener('submit', function() {
  const btn = document.getElementById('submitBtn');
  btn.disabled = true;
  btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span><?php echo addslashes(t('Sending…','Envoi…')); ?>';
});
</script>

</body>
</html>
