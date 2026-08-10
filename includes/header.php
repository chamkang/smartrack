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

/* ── SEO ──────────────────────────────────────────────────────────────────────
   Pages may set $metaDescription, $metaKeywords, $ogImage and $noIndex before
   including this file. Sensible bilingual defaults are used otherwise. */
$seoDesc = $metaDescription ?? t(
    'Smartrack Africa provides GPS vehicle tracking, fuel monitoring and security solutions for fleets in Cameroon and Central Africa. Real-time tracking, 24/7 local support.',
    "Smartrack Africa fournit le suivi GPS de véhicules, la surveillance de carburant et des solutions de sécurité pour les flottes au Cameroun et en Afrique centrale. Suivi en temps réel, support local 24h/7j."
);
$seoDesc  = trim(preg_replace('/\s+/', ' ', $seoDesc));
if (function_exists('mb_strimwidth')) { $seoDesc = mb_strimwidth($seoDesc, 0, 300, '…'); }

$seoKeywords = $metaKeywords ?? t(
    'GPS tracking Cameroon, fleet management Douala, vehicle tracking Africa, fuel monitoring, geofencing, vehicle security, Smartrack Africa',
    "suivi GPS Cameroun, gestion de flotte Douala, traçage véhicule Afrique, surveillance carburant, géorepérage, sécurité véhicule, Smartrack Africa"
);

// Absolute site root (needed for canonical, hreflang, Open Graph)
$scheme   = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host     = $_SERVER['HTTP_HOST'] ?? 'localhost';
$siteRoot = $scheme . '://' . $host . $baseUrl;

// Canonical = current page without the lang parameter (lang is handled by hreflang)
$qs = $_GET;
unset($qs['lang']);
$canonical = $siteRoot . '/' . ltrim($currentFile, '/') . ($qs ? '?' . http_build_query($qs) : '');
$altEn = $canonical . ($qs ? '&' : '?') . 'lang=en';
$altFr = $canonical . ($qs ? '&' : '?') . 'lang=fr';

$ogImg = isset($ogImage)
    ? (preg_match('#^https?://#', $ogImage) ? $ogImage : $siteRoot . '/' . ltrim($ogImage, '/'))
    : $siteRoot . '/assets/img/st logo.png';
?>
<!DOCTYPE html>
<html lang="<?php echo escape($language); ?>">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title><?php echo escape($pageTitle ?? 'Smartrack Africa'); ?></title>
  <meta name="description" content="<?php echo escape($seoDesc); ?>">
  <meta name="keywords" content="<?php echo escape($seoKeywords); ?>">
  <meta name="author" content="Smartrack Africa">
  <meta name="robots" content="<?php echo !empty($noIndex) ? 'noindex,nofollow' : 'index,follow,max-image-preview:large'; ?>">
  <meta name="base-url" content="<?php echo escape($baseUrl); ?>">
  <meta name="theme-color" content="#e60000">

  <!-- Canonical + language alternates -->
  <link rel="canonical" href="<?php echo escape($canonical); ?>">
  <link rel="alternate" hreflang="en" href="<?php echo escape($altEn); ?>">
  <link rel="alternate" hreflang="fr" href="<?php echo escape($altFr); ?>">
  <link rel="alternate" hreflang="x-default" href="<?php echo escape($canonical); ?>">

  <!-- Open Graph (Facebook, WhatsApp, LinkedIn) -->
  <meta property="og:type" content="website">
  <meta property="og:site_name" content="Smartrack Africa">
  <meta property="og:locale" content="<?php echo $language === 'fr' ? 'fr_FR' : 'en_US'; ?>">
  <meta property="og:title" content="<?php echo escape($pageTitle ?? 'Smartrack Africa'); ?>">
  <meta property="og:description" content="<?php echo escape($seoDesc); ?>">
  <meta property="og:url" content="<?php echo escape($canonical); ?>">
  <meta property="og:image" content="<?php echo escape($ogImg); ?>">

  <!-- Twitter/X card -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="<?php echo escape($pageTitle ?? 'Smartrack Africa'); ?>">
  <meta name="twitter:description" content="<?php echo escape($seoDesc); ?>">
  <meta name="twitter:image" content="<?php echo escape($ogImg); ?>">

  <!-- Structured data: local business + site search -->
  <script type="application/ld+json">
  <?php
  $addr = !empty($contact['address_' . $language]) ? $contact['address_' . $language] : ($contact['address_en'] ?? 'Douala, Cameroon');
  echo json_encode([
      '@context' => 'https://schema.org',
      '@type'    => 'LocalBusiness',
      'name'     => 'Smartrack Africa',
      'description' => $seoDesc,
      'url'      => $siteRoot . '/',
      'logo'     => $siteRoot . '/assets/img/st logo.png',
      'image'    => $ogImg,
      'telephone'=> $contact['phone'] ?? '+237 691 415 588',
      'email'    => $contact['email'] ?? 'info@smartrackafrica.com',
      'foundingDate' => '2006',
      'address'  => [
          '@type' => 'PostalAddress',
          'streetAddress'   => $addr,
          'addressLocality' => 'Douala',
          'addressCountry'  => 'CM',
      ],
      'areaServed' => ['@type' => 'Country', 'name' => 'Cameroon'],
      'sameAs' => array_values(array_filter([
          $contact['facebook'] ?? null, $contact['twitter'] ?? null,
          $contact['instagram'] ?? null, $contact['linkedin'] ?? null,
      ])),
  ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
  ?>
  </script>

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
        <h1 class="sitename">SMAR<span class="logo-t-split">T</span><span class="text-danger">RACK</span></h1>
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="<?php echo escape(site_url('index.php')); ?>"<?php echo nav_is_active('index.php'); ?>><?php echo escape(get_translation('nav_home')); ?></a></li>
          <li class="dropdown">
            <a href="<?php echo escape(site_url('about.php')); ?>"<?php echo nav_is_active('about.php'); ?>><span><?php echo escape(t('About Us','À Propos')); ?></span><i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
              <li><a href="<?php echo escape(site_url('about.php')); ?>"><?php echo escape(t('Our Story','Notre Histoire')); ?></a></li>
              <li><a href="<?php echo escape(site_url('about.php#our-partners')); ?>"><?php echo escape(t('Our Partners','Nos Partenaires')); ?></a></li>
              <li><a href="<?php echo escape(site_url('about.php#our-references')); ?>"><?php echo escape(t('Our References','Nos Références')); ?></a></li>
            </ul>
          </li>
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
