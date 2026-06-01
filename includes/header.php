<?php
if (!defined('APP_INIT')) {
    exit;
}
$language = current_language();
?>
<!DOCTYPE html>
<html lang="<?php echo escape($language); ?>">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo escape($pageTitle ?? 'Smartrack'); ?></title>
  <link href="<?php echo escape(site_url('assets/vendor/bootstrap/css/bootstrap.min.css')); ?>" rel="stylesheet">
  <link href="<?php echo escape(site_url('assets/vendor/bootstrap-icons/bootstrap-icons.css')); ?>" rel="stylesheet">
  <link href="<?php echo escape(site_url('assets/vendor/aos/aos.css')); ?>" rel="stylesheet">
  <link href="<?php echo escape(site_url('assets/vendor/glightbox/css/glightbox.min.css')); ?>" rel="stylesheet">
  <link href="<?php echo escape(site_url('assets/vendor/swiper/swiper-bundle.min.css')); ?>" rel="stylesheet">
  <link href="<?php echo escape(site_url('assets/css/main.css')); ?>" rel="stylesheet">
</head>
<body>
<header id="header" class="header d-flex align-items-center fixed-top bg-white shadow-sm">
  <div class="container-fluid container-xl d-flex align-items-center justify-content-between">
    <a href="<?php echo escape(site_url('index.php')); ?>" class="logo d-flex align-items-center">
      <img src="<?php echo escape(site_url('assets/img/st logo.png')); ?>" alt="Smartrack" width="48">
      <h1 class="sitename ms-2">SMAR<span class="text-danger">TRACK</span></h1>
    </a>
    <nav id="navmenu" class="navmenu">
      <ul class="list-unstyled d-flex gap-3 mb-0 align-items-center">
        <li><a href="<?php echo escape(site_url('index.php')); ?>"><?php echo escape(get_translation('n1')); ?></a></li>
        <li><a href="<?php echo escape(site_url('about.php')); ?>"><?php echo escape(get_translation('n2')); ?></a></li>
        <li class="dropdown position-relative">
          <a href="#"><?php echo escape(get_translation('abo')); ?> <i class="bi bi-chevron-down"></i></a>
          <ul class="dropdown-menu position-absolute bg-white border rounded p-2">
            <li><a class="dropdown-item" href="<?php echo escape(site_url('SmartFleet.php')); ?>"><?php echo escape(get_translation('fmanage')); ?></a></li>
            <li><a class="dropdown-item" href="<?php echo escape(site_url('SmartSolution.php')); ?>"><?php echo escape(get_translation('network')); ?></a></li>
            <li><a class="dropdown-item" href="<?php echo escape(site_url('devices.php')); ?>"><?php echo escape(get_translation('n3')); ?></a></li>
          </ul>
        </li>
        <li><a href="<?php echo escape(site_url('contact.php')); ?>"><?php echo escape(get_translation('n4')); ?></a></li>
        <li class="dropdown position-relative">
          <a href="#"><?php echo strtoupper($language); ?> <i class="bi bi-globe"></i></a>
          <ul class="dropdown-menu position-absolute bg-white border rounded p-2">
            <li><a class="dropdown-item" href="?lang=en">EN</a></li>
            <li><a class="dropdown-item" href="?lang=fr">FR</a></li>
          </ul>
        </li>
      </ul>
    </nav>
  </div>
</header>
<main class="mt-5 pt-5">