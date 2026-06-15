<?php
if (!defined('APP_INIT')) { exit; }
$language = current_language();
$contact  = get_contact();
$baseUrl  = rtrim(BASE_URL, '/');

// Which page is active?
$currentFile = basename($_SERVER['PHP_SELF'] ?? '');
function nav_is_active(string $page): string {
    global $currentFile;
    return $currentFile === $page ? ' class="active"' : '';
}
?>
<!DOCTYPE html>
<html lang="<?php echo escape($language); ?>">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title><?php echo escape($pageTitle ?? 'Smartrack'); ?></title>
  <meta name="description" content="">
  <meta name="base-url" content="<?php echo escape($baseUrl); ?>">

  <!-- Favicons -->
  <link href="<?php echo escape($baseUrl); ?>/assets/img/st logo.png" rel="icon">
  <link href="<?php echo escape($baseUrl); ?>/assets/img/st logo.png" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="<?php echo escape($baseUrl); ?>/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?php echo escape($baseUrl); ?>/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="<?php echo escape($baseUrl); ?>/assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="<?php echo escape($baseUrl); ?>/assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
  <link href="<?php echo escape($baseUrl); ?>/assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="<?php echo escape($baseUrl); ?>/assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="<?php echo escape($baseUrl); ?>/assets/css/main.css" rel="stylesheet">
</head>

<body class="<?php echo escape($bodyClass ?? 'index-page'); ?>">

  <header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center justify-content-between">

      <a href="<?php echo escape(site_url('index.php')); ?>" class="logo d-flex align-items-center">
        <img src="<?php echo escape($baseUrl); ?>/assets/img/st logo.png" alt="">
        <h1 class="sitename">SMAR<span class="text-danger">TRACK</span></h1>
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="<?php echo escape(site_url('index.php')); ?>"<?php echo nav_is_active('index.php'); ?>><?php echo escape(get_translation('nav_home')); ?></a></li>
          <li><a href="<?php echo escape(site_url('about.php')); ?>"<?php echo nav_is_active('about.php'); ?>><?php echo escape(get_translation('nav_about')); ?></a></li>
          <li class="dropdown">
            <a href="#"><span><?php echo escape(get_translation('nav_services')); ?></span><i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
              <li><a href="<?php echo escape(site_url('SmartFleet.php')); ?>">SmartFleet</a></li>
              <li><a href="<?php echo escape(site_url('SmartSolution.php')); ?>">SmartSolution</a></li>
              <li><a href="<?php echo escape(site_url('devices.php')); ?>"><?php echo escape(get_translation('nav_devices')); ?></a></li>
            </ul>
          </li>
          <li><a href="<?php echo escape(site_url('blog.php')); ?>"<?php echo nav_is_active('blog.php'); ?>><?php echo escape(get_translation('nav_blog')); ?></a></li>
          <li><a href="<?php echo escape(site_url('contact.php')); ?>"<?php echo nav_is_active('contact.php'); ?>><?php echo escape(get_translation('nav_contact')); ?></a></li>
          <li><a href="<?php echo escape(site_url('career.php')); ?>"<?php echo nav_is_active('career.php'); ?>><?php echo escape(get_translation('nav_career')); ?></a></li>
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
