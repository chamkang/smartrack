<?php
if (!defined('APP_INIT_ADMIN')) { exit; }

$adminUser    = admin_user();
$adminInitial = strtoupper(mb_substr($adminUser['username'] ?? 'A', 0, 1));

// Determine current page for active state
$currentPage = basename($_SERVER['PHP_SELF']);
function nav_active(string $page): string {
    global $currentPage;
    return $currentPage === $page ? ' active' : '';
}

// Breadcrumb label map
$breadcrumbs = [
    'dashboard.php'    => 'Dashboard',
    'homepage.php'     => 'Homepage',
    'services.php'     => 'Services',
    'testimonials.php' => 'Testimonials',
    'contact-info.php' => 'Contact Info',
    'quotes.php'       => 'Quote Requests',
    'messages.php'     => 'Messages',
    'translations.php' => 'Translations',
    'blog.php'         => 'Blog Posts',
    'jobs.php'         => 'Job Postings',
    'applications.php' => 'Applications',
];
$pageLabel = $breadcrumbs[$currentPage] ?? ($pageTitle ?? 'Admin');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo escape($pageTitle ?? 'Admin'); ?> — Smartrack</title>
  <link href="<?php echo escape(site_url('assets/vendor/bootstrap-icons/bootstrap-icons.css')); ?>" rel="stylesheet">
  <link href="<?php echo escape(site_url('assets/vendor/fontawesome-free/css/all.min.css')); ?>" rel="stylesheet">
  <link href="<?php echo escape(site_url('admin/css/admin.css')); ?>" rel="stylesheet">
</head>
<body>

<div class="sidebar-overlay" id="sidebarOverlay"></div>

<div class="admin-shell">

  <!-- ══════════════ SIDEBAR ══════════════ -->
  <aside class="sidebar" id="sidebar">

    <a href="<?php echo escape(site_url('admin/dashboard.php')); ?>" class="sidebar-logo">
      <img src="<?php echo escape(site_url('assets/img/st logo.png')); ?>" alt="Smartrack" onerror="this.style.display='none'">
      <div class="sidebar-logo-text">SMAR<span>TRACK</span></div>
    </a>

    <nav class="sidebar-nav" style="overflow-y:auto;max-height:calc(100vh - 180px);padding-right:8px;">
      <!-- Custom scrollbar styling -->
      <style>
        .sidebar-nav::-webkit-scrollbar { width:6px; }
        .sidebar-nav::-webkit-scrollbar-track { background:transparent; }
        .sidebar-nav::-webkit-scrollbar-thumb { background:rgba(255,255,255,.2);border-radius:3px; }
        .sidebar-nav::-webkit-scrollbar-thumb:hover { background:rgba(255,255,255,.4); }
      </style>

      <div class="sidebar-section-label">Overview</div>

      <div class="sidebar-item">
        <a href="dashboard.php" class="sidebar-link<?php echo nav_active('dashboard.php'); ?>">
          <span class="sidebar-icon"><i class="bi bi-grid-1x2-fill"></i></span>
          Dashboard
        </a>
      </div>

      <div class="sidebar-section-label">Content</div>

      <div class="sidebar-item">
        <a href="homepage.php" class="sidebar-link<?php echo nav_active('homepage.php'); ?>">
          <span class="sidebar-icon"><i class="bi bi-house-fill"></i></span>
          Homepage
        </a>
      </div>

      <div class="sidebar-item">
        <a href="services.php" class="sidebar-link<?php echo nav_active('services.php'); ?>">
          <span class="sidebar-icon"><i class="bi bi-geo-alt-fill"></i></span>
          Services
        </a>
      </div>

      <div class="sidebar-item">
        <a href="testimonials.php" class="sidebar-link<?php echo nav_active('testimonials.php'); ?>">
          <span class="sidebar-icon"><i class="bi bi-chat-quote-fill"></i></span>
          Testimonials
        </a>
      </div>

      <div class="sidebar-item">
        <a href="translations.php" class="sidebar-link<?php echo nav_active('translations.php'); ?>">
          <span class="sidebar-icon"><i class="bi bi-translate"></i></span>
          Translations
        </a>
      </div>

      <div class="sidebar-section-label">Business</div>

      <div class="sidebar-item">
        <a href="contact-info.php" class="sidebar-link<?php echo nav_active('contact-info.php'); ?>">
          <span class="sidebar-icon"><i class="bi bi-telephone-fill"></i></span>
          Contact Info
        </a>
      </div>

      <?php
      $quoteCount = (int) db()->query('SELECT COUNT(*) FROM quote_requests')->fetchColumn();
      $msgCount   = (int) db()->query('SELECT COUNT(*) FROM contact_messages')->fetchColumn();
      ?>

      <div class="sidebar-item">
        <a href="quotes.php" class="sidebar-link<?php echo nav_active('quotes.php'); ?>">
          <span class="sidebar-icon"><i class="bi bi-file-earmark-text-fill"></i></span>
          Quote Requests
          <?php if ($quoteCount > 0): ?>
            <span class="sidebar-badge"><?php echo $quoteCount; ?></span>
          <?php endif; ?>
        </a>
      </div>

      <div class="sidebar-item">
        <a href="messages.php" class="sidebar-link<?php echo nav_active('messages.php'); ?>">
          <span class="sidebar-icon"><i class="bi bi-envelope-fill"></i></span>
          Messages
          <?php if ($msgCount > 0): ?>
            <span class="sidebar-badge"><?php echo $msgCount; ?></span>
          <?php endif; ?>
        </a>
      </div>

      <div class="sidebar-section-label">Content</div>

      <div class="sidebar-item">
        <a href="blog.php" class="sidebar-link<?php echo nav_active('blog.php'); ?>">
          <span class="sidebar-icon"><i class="bi bi-journal-richtext"></i></span>
          Blog Posts
        </a>
      </div>

      <div class="sidebar-section-label">Careers</div>

      <?php
      $appNew = (int) db()->query("SELECT COUNT(*) FROM job_applications WHERE status = 'new'")->fetchColumn();
      $jobCount = (int) db()->query("SELECT COUNT(*) FROM job_postings WHERE is_active = 1")->fetchColumn();
      ?>

      <div class="sidebar-item">
        <a href="jobs.php" class="sidebar-link<?php echo nav_active('jobs.php'); ?>">
          <span class="sidebar-icon"><i class="bi bi-briefcase-fill"></i></span>
          Job Postings
          <?php if ($jobCount > 0): ?>
            <span class="sidebar-badge" style="background:var(--sidebar-hover);color:rgba(255,255,255,.6);"><?php echo $jobCount; ?></span>
          <?php endif; ?>
        </a>
      </div>

      <div class="sidebar-item">
        <a href="applications.php" class="sidebar-link<?php echo nav_active('applications.php'); ?>">
          <span class="sidebar-icon"><i class="bi bi-inbox-fill"></i></span>
          Applications
          <?php if ($appNew > 0): ?>
            <span class="sidebar-badge"><?php echo $appNew; ?></span>
          <?php endif; ?>
        </a>
      </div>

      <div class="sidebar-section-label">Site</div>

      <div class="sidebar-item">
        <a href="<?php echo escape(site_url('index.php')); ?>" class="sidebar-link" target="_blank">
          <span class="sidebar-icon"><i class="bi bi-box-arrow-up-right"></i></span>
          View Website
        </a>
      </div>

    </nav>

    <!-- User block -->
    <div class="sidebar-user">
      <div class="sidebar-user-avatar"><?php echo escape($adminInitial); ?></div>
      <div class="sidebar-user-info">
        <div class="sidebar-user-name"><?php echo escape($adminUser['username'] ?? 'Admin'); ?></div>
        <div class="sidebar-user-role">Administrator</div>
      </div>
      <a href="logout.php" class="sidebar-logout" title="Logout">
        <i class="bi bi-box-arrow-right"></i>
      </a>
    </div>

  </aside>

  <!-- ══════════════ MAIN ══════════════ -->
  <div class="admin-main">

    <!-- Top bar -->
    <header class="topbar">
      <button class="topbar-hamburger" id="hamburger" aria-label="Toggle menu">
        <i class="bi bi-list" style="font-size:1.4rem;"></i>
      </button>

      <div class="topbar-breadcrumb">
        <a href="dashboard.php" class="crumb">Admin</a>
        <span class="crumb-sep"><i class="bi bi-chevron-right"></i></span>
        <span class="crumb-current"><?php echo escape($pageLabel); ?></span>
      </div>

      <div class="topbar-right">
        <a href="<?php echo escape(site_url('index.php')); ?>" class="topbar-btn" target="_blank" title="View website">
          <i class="bi bi-globe"></i>
        </a>
        <a href="logout.php" class="topbar-btn" title="Logout">
          <i class="bi bi-box-arrow-right"></i>
        </a>
        <a href="dashboard.php" class="topbar-user" style="text-decoration:none;">
          <div class="topbar-user-avatar"><?php echo escape($adminInitial); ?></div>
          <span class="topbar-user-name"><?php echo escape($adminUser['username'] ?? 'Admin'); ?></span>
        </a>
      </div>
    </header>

    <!-- Content -->
    <main class="admin-content">
