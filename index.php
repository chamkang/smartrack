<?php
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Smartrack — GPS Fleet Tracking & Security Solutions';
$bodyClass = 'index-page';
$lang      = current_language();

$heroImage     = get_content_value('hero_image');
$heroVideo     = get_content_value('hero_video');
$heroTitle     = get_content_value('hero_title_'    . $lang) ?: get_translation('welcome');
$heroSubtitle  = get_content_value('hero_subtitle_' . $lang) ?: get_translation('description');
$dbServices    = db()->query('SELECT * FROM services ORDER BY sort_order ASC, created_at ASC')->fetchAll();
$dbTestimonials= db()->query('SELECT * FROM testimonials ORDER BY created_at DESC')->fetchAll();
$dbBlogPosts   = db()->query('SELECT * FROM blog_posts WHERE is_published=1 ORDER BY published_at DESC LIMIT 3')->fetchAll();
$contact       = get_contact();

// ── Promo carousel slides (editable via Admin → Homepage Content) ──────────────
// Each field falls back to the default below if no admin value has been saved.
$promoThemes = [
    1 => ['grad1'=>'#e60000','grad2'=>'#c40000','btn'=>'#e60000','icon'=>'bi-lightning-fill','alt'=>'Limited Time Offer'],
    2 => ['grad1'=>'#2563eb','grad2'=>'#1d4ed8','btn'=>'#2563eb','icon'=>'bi-fingerprint','alt'=>'Advanced Security'],
];
$promoDefaults = [
    1 => [
        'image'       => 'assets/img/truckr.jpg',
        'eyebrow_en'  => '🎯 Early Bird Special',                                            'eyebrow_fr' => '🎯 Offre Lève-Tôt',
        'title_en'    => '40% OFF',                                                          'title_fr'   => '40% DE RÉDUCTION',
        'subtitle_en' => 'First 3 months of GPS Tracking<br>Limited to next <strong>50 clients</strong>',
        'subtitle_fr' => 'Les 3 premiers mois de suivi GPS<br>Limité aux <strong>50 prochains clients</strong>',
        'note_en'     => 'Valid only this month • Free consultation included',               'note_fr'    => 'Valable ce mois uniquement • Consultation gratuite incluse',
        'btn_en'      => 'Claim Your Discount',                                              'btn_fr'     => 'Profitez de la Réduction',
        'link'        => 'contact.php',
    ],
    2 => [
        'image'       => 'assets/img/lock.jpg',
        'eyebrow_en'  => '✨ Now Available',                                                 'eyebrow_fr' => '✨ Maintenant Disponible',
        'title_en'    => 'Advanced Biometric<br>Authentication',                            'title_fr'   => 'Authentification<br>Biométrique Avancée',
        'subtitle_en' => 'Fingerprint, face ID & PIN for maximum security',                 'subtitle_fr'=> 'Empreinte, reconnaissance faciale et PIN pour une sécurité maximale',
        'note_en'     => '',                                                                'note_fr'    => '',
        'btn_en'      => 'Learn More',                                                      'btn_fr'     => 'En Savoir Plus',
        'link'        => 'contact.php',
    ],
];
$promoSlides = [];
foreach ([1, 2] as $n) {
    if (get_content_value("promo{$n}_enabled") === '0') continue; // explicitly hidden by admin
    $d    = $promoDefaults[$n];
    $img  = get_content_value("promo{$n}_image");
    $link = get_content_value("promo{$n}_link");
    $promoSlides[] = [
        'theme'    => $promoThemes[$n],
        'image'    => ($img  !== null && $img  !== '') ? $img  : site_url($d['image']),
        'link'     => ($link !== null && $link !== '') ? $link : site_url($d['link']),
        'eyebrow'  => get_content_value("promo{$n}_eyebrow_{$lang}")  ?: $d["eyebrow_{$lang}"],
        'title'    => get_content_value("promo{$n}_title_{$lang}")    ?: $d["title_{$lang}"],
        'subtitle' => get_content_value("promo{$n}_subtitle_{$lang}") ?: $d["subtitle_{$lang}"],
        'note'     => get_content_value("promo{$n}_note_{$lang}")     ?? $d["note_{$lang}"],
        'btn'      => get_content_value("promo{$n}_btn_{$lang}")      ?: $d["btn_{$lang}"],
    ];
}

// Icon map by service slug
$serviceIcons = [
    'vehicle-tracking'  => 'bi-geo-alt-fill',
    'fuel-monitoring'   => 'bi-fuel-pump-fill',
    'security-solutions'=> 'bi-shield-fill-check',
    'fleet-management'  => 'bi-truck-front-fill',
    'fire-detection'    => 'bi-fire',
    'network-security'  => 'bi-wifi',
    'video-surveillance'=> 'bi-camera-video-fill',
    'access-control'    => 'bi-door-open-fill',
];

define('APP_INIT', true);
include __DIR__ . '/includes/header.php';
?>

<!-- ═══════════════════════════════════════════════════════════
     § 1  HERO
════════════════════════════════════════════════════════════ -->
<section id="hero" class="hero section dark-background" style="position:relative;overflow:hidden;min-height:100vh;display:flex;align-items:center;">

  <!-- Background media -->
  <?php if ($heroVideo): ?>
    <video src="<?php echo escape(site_url($heroVideo)); ?>" autoplay muted loop playsinline
      style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;z-index:0;"></video>
  <?php elseif ($heroImage): ?>
    <img src="<?php echo escape($heroImage); ?>"
      style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;z-index:0;" alt="">
  <?php else: ?>
    <!-- CSS gradient fallback — always looks great -->
    <div style="position:absolute;inset:0;z-index:0;
      background:linear-gradient(135deg,#03060d 0%,#0d1117 40%,#1a0505 100%);"></div>
    <div style="position:absolute;inset:0;z-index:0;
      background-image:radial-gradient(ellipse 90% 60% at 15% 40%,rgba(230,0,0,.18) 0%,transparent 60%),
                       radial-gradient(ellipse 70% 80% at 90% 80%,rgba(230,0,0,.08) 0%,transparent 55%);"></div>
    <!-- Subtle dot grid -->
    <div style="position:absolute;inset:0;z-index:0;
      background-image:radial-gradient(rgba(255,255,255,.06) 1px,transparent 1px);
      background-size:32px 32px;"></div>
  <?php endif; ?>

  <!-- Dark overlay for text legibility -->
  <div style="position:absolute;inset:0;z-index:1;background:rgba(3,6,13,.65);"></div>

  <!-- Content -->
  <div class="container position-relative" style="z-index:2;padding-top:110px;padding-bottom:60px;">
    <div class="row align-items-center gy-5">

      <!-- Left: headline + CTAs -->
      <div class="col-lg-7" data-aos="fade-right" data-aos-delay="100">

        <!-- Industry badge -->
        <div style="display:inline-flex;align-items:center;gap:8px;
                    background:rgba(230,0,0,.15);border:1px solid rgba(230,0,0,.35);
                    border-radius:30px;padding:6px 18px;margin-bottom:24px;">
          <span style="width:7px;height:7px;border-radius:50%;background:#e60000;
                        box-shadow:0 0 0 3px rgba(230,0,0,.3);animation:pulse-dot 2s infinite;"></span>
          <span style="color:rgba(255,255,255,.85);font-size:.8rem;font-weight:600;letter-spacing:.06em;text-transform:uppercase;">
            <?php echo escape(t('GPS Fleet Management · Central Africa', 'Gestion de Flotte GPS · Afrique Centrale')); ?>
          </span>
        </div>

        <h1 style="font-size:clamp(2.2rem,5vw,3.8rem);font-weight:900;color:#fff;
                   line-height:1.1;letter-spacing:-.03em;margin-bottom:22px;">
          <span id="welcome"><?php echo escape($heroTitle); ?></span>
        </h1>

        <p style="font-size:1.1rem;color:rgba(255,255,255,.7);line-height:1.75;
                  max-width:560px;margin-bottom:36px;">
          <span id="description"><?php echo escape($heroSubtitle); ?></span>
        </p>

        <!-- CTAs -->
        <div style="display:flex;flex-wrap:wrap;gap:14px;margin-bottom:44px;">
          <a href="<?php echo escape(site_url('contact.php')); ?>"
             style="display:inline-flex;align-items:center;gap:10px;
                    background:#e60000;color:#fff;font-weight:700;font-size:.975rem;
                    padding:14px 32px;border-radius:50px;text-decoration:none;
                    box-shadow:0 6px 24px rgba(230,0,0,.4);
                    transition:all .25s;letter-spacing:.01em;"
             onmouseover="this.style.background='#c40000';this.style.transform='translateY(-2px)'"
             onmouseout="this.style.background='#e60000';this.style.transform='translateY(0)'">
            <i class="bi bi-file-earmark-text-fill"></i>
            <span id="start"><?php echo escape(get_translation('start')); ?></span>
          </a>
          <a href="#how-it-works"
             style="display:inline-flex;align-items:center;gap:10px;
                    background:rgba(255,255,255,.08);border:1.5px solid rgba(255,255,255,.25);
                    color:#fff;font-weight:600;font-size:.975rem;
                    padding:14px 32px;border-radius:50px;text-decoration:none;
                    backdrop-filter:blur(8px);transition:all .25s;"
             onmouseover="this.style.background='rgba(255,255,255,.15)'"
             onmouseout="this.style.background='rgba(255,255,255,.08)'">
            <i class="bi bi-play-circle-fill" style="color:#e60000;"></i>
            <?php echo escape(t('How It Works', 'Comment Ça Fonctionne')); ?>
          </a>
        </div>

        <!-- Trust badges -->
        <div style="display:flex;flex-wrap:wrap;gap:20px;">
          <?php
          $badges = [
            t('232+ Clients Served','232+ Clients Servis'),
            t('Real-Time Tracking','Suivi en Temps Réel'),
            t('24/7 Local Support','Support Local 24h/7j'),
            t('Made for Africa','Conçu pour l\'Afrique'),
          ];
          foreach ($badges as $b):
          ?>
            <div style="display:flex;align-items:center;gap:7px;font-size:.82rem;color:rgba(255,255,255,.6);">
              <i class="bi bi-check-circle-fill" style="color:#e60000;font-size:.75rem;"></i>
              <?php echo $b; ?>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Right: floating KPI card -->
      <div class="col-lg-5 d-none d-lg-flex justify-content-center" data-aos="fade-left" data-aos-delay="200">
        <div class="tilt-3d" style="background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.1);
                    border-radius:20px;padding:36px;backdrop-filter:blur(16px);
                    width:100%;max-width:380px;">

          <!-- Live indicator -->
          <div style="display:flex;align-items:center;gap:8px;margin-bottom:24px;">
            <span style="width:8px;height:8px;border-radius:50%;background:#22c55e;
                          box-shadow:0 0 0 4px rgba(34,197,94,.25);animation:pulse-dot 2s infinite;"></span>
            <span style="font-size:.78rem;color:rgba(255,255,255,.5);font-weight:600;letter-spacing:.06em;text-transform:uppercase;">
              <?php echo escape(t('Live Dashboard Preview', 'Aperçu du Tableau de Bord en Direct')); ?>
            </span>
          </div>

          <!-- Mini stat rows -->
          <?php
          $kpis = [
            ['bi-geo-alt-fill',      '#e60000', t('Vehicles Online','Véhicules en Ligne'),     '238 / 238'],
            ['bi-fuel-pump-fill',    '#f59e0b', t('Avg. Fuel Saved','Carburant Économisé'),    '18.4 L / day'],
            ['bi-shield-fill-check', '#22c55e', t('Security Alerts','Alertes Sécurité'),       t('0 active','0 actif')],
            ['bi-speedometer2',      '#3b82f6', t('Fleet Avg. Speed','Vitesse Moy. Flotte'),   '47 km/h'],
          ];
          foreach ($kpis as [$icon,$color,$label,$value]):
          ?>
            <div style="display:flex;align-items:center;gap:14px;padding:12px 0;
                        border-bottom:1px solid rgba(255,255,255,.06);">
              <div style="width:38px;height:38px;border-radius:10px;flex-shrink:0;
                           display:flex;align-items:center;justify-content:center;
                           background:<?php echo $color; ?>22;">
                <i class="bi <?php echo $icon; ?>" style="color:<?php echo $color; ?>;font-size:1.1rem;"></i>
              </div>
              <div style="flex:1;">
                <div style="font-size:.72rem;color:rgba(255,255,255,.4);text-transform:uppercase;letter-spacing:.06em;"><?php echo $label; ?></div>
                <div style="font-size:.95rem;font-weight:700;color:#fff;"><?php echo $value; ?></div>
              </div>
            </div>
          <?php endforeach; ?>

          <a href="<?php echo escape(site_url('contact.php')); ?>"
             style="display:block;text-align:center;margin-top:20px;
                    background:#e60000;color:#fff;font-weight:700;font-size:.875rem;
                    padding:12px;border-radius:10px;text-decoration:none;
                    transition:background .2s;"
             onmouseover="this.style.background='#c40000'" onmouseout="this.style.background='#e60000'">
            <?php echo escape(t('Get This for Your Fleet →', 'Obtenez Ceci pour Votre Flotte →')); ?>
          </a>
        </div>
      </div>

    </div>
  </div>

  <!-- Scroll hint -->
  <div style="position:absolute;bottom:28px;left:50%;transform:translateX(-50%);z-index:3;
              display:flex;flex-direction:column;align-items:center;gap:6px;
              color:rgba(255,255,255,.35);font-size:.72rem;letter-spacing:.08em;text-transform:uppercase;">
    <?php echo escape(t('Scroll', 'Défiler')); ?>
    <i class="bi bi-chevron-double-down" style="animation:bounce-down 1.8s ease-in-out infinite;font-size:.9rem;"></i>
  </div>

</section>

<style>
@keyframes pulse-dot  { 0%,100%{box-shadow:0 0 0 3px rgba(230,0,0,.3)} 50%{box-shadow:0 0 0 7px rgba(230,0,0,.08)} }
@keyframes bounce-down{ 0%,100%{transform:translateY(0)} 50%{transform:translateY(5px)} }
@keyframes parallax-drift { 0%{transform:translateY(0px)} 50%{transform:translateY(20px)} 100%{transform:translateY(0px)} }
@keyframes float-in-up { from{opacity:0;transform:translateY(30px)} to{opacity:1;transform:translateY(0)} }

/* ── Stats strip ── */
.idx-stats { background:#fff;border-bottom:1px solid #f0f0f0; }
.idx-stats .stat-block { padding:36px 24px;text-align:center;border-right:1px solid #f0f0f0;transition:background .2s; }
.idx-stats .stat-block:last-child { border-right:none; }
.idx-stats .stat-block:hover { background:#fafafa; }
.idx-stat-num  { font-size:2.6rem;font-weight:900;color:#e60000;letter-spacing:-.04em;line-height:1;display:block; }
.idx-stat-lbl  { font-size:.78rem;font-weight:700;color:#888;text-transform:uppercase;letter-spacing:.08em;margin-top:4px; }

/* ── 3D Flip Service Cards ── */
.idx-flip-wrap {
  perspective:1200px;
  height:300px;
}
.idx-flip-card {
  width:100%;height:100%;position:relative;
  transform-style:preserve-3d;
  transition:transform .7s cubic-bezier(.4,0,.2,1);
  cursor:pointer;
}
.idx-flip-wrap:hover .idx-flip-card { transform:rotateY(180deg); }
.idx-flip-front,.idx-flip-back {
  position:absolute;inset:0;
  border-radius:16px;padding:30px;
  backface-visibility:hidden;
  -webkit-backface-visibility:hidden;
  display:flex;flex-direction:column;
}
.idx-flip-front {
  background:#fff;
  border:1px solid #f0f0f0;
  box-shadow:0 2px 24px rgba(0,0,0,.06);
}
.idx-flip-back {
  background:linear-gradient(145deg,#e60000 0%,#9b0000 100%);
  color:#fff;
  transform:rotateY(180deg);
  justify-content:center;
}
.idx-flip-back::before {
  content:'';position:absolute;top:-30px;right:-30px;
  width:120px;height:120px;border-radius:50%;
  background:rgba(255,255,255,.06);
}
.idx-flip-back::after {
  content:'';position:absolute;bottom:-20px;left:-20px;
  width:80px;height:80px;border-radius:50%;
  background:rgba(255,255,255,.04);
}
.idx-service-icon {
  width:56px;height:56px;border-radius:14px;
  display:flex;align-items:center;justify-content:center;
  font-size:1.5rem;margin-bottom:16px;
  background:rgba(230,0,0,.08);color:#e60000;
  transition:background .25s;flex-shrink:0;
}
.idx-flip-front h3 { font-size:1rem;font-weight:700;margin-bottom:8px;color:#1a202c; }
.idx-flip-front p  { font-size:.85rem;color:#64748b;line-height:1.65;flex:1; }
.idx-flip-hint {
  font-size:.72rem;color:#bbb;margin-top:auto;padding-top:10px;
  display:flex;align-items:center;gap:6px;
}
.idx-flip-back .flip-icon {
  width:52px;height:52px;border-radius:12px;
  background:rgba(255,255,255,.18);color:#fff;
  display:flex;align-items:center;justify-content:center;
  font-size:1.4rem;margin-bottom:14px;position:relative;z-index:1;
}
.idx-flip-back h3 { font-size:1rem;font-weight:700;color:#fff;margin-bottom:10px;position:relative;z-index:1; }
.idx-flip-back p  { font-size:.82rem;color:rgba(255,255,255,.82);line-height:1.65;margin-bottom:16px;position:relative;z-index:1; }
.idx-flip-back-link {
  display:inline-flex;align-items:center;gap:7px;
  background:rgba(255,255,255,.18);color:#fff;
  font-size:.8rem;font-weight:700;
  padding:9px 20px;border-radius:30px;text-decoration:none;
  border:1px solid rgba(255,255,255,.3);
  transition:background .2s;position:relative;z-index:1;
  width:fit-content;
}
.idx-flip-back-link:hover { background:rgba(255,255,255,.3);color:#fff; }

/* ── 3D Tilt (JS-driven) ── */
.tilt-3d {
  transform-style:preserve-3d;
  transition:transform .12s ease;
  will-change:transform;
}
.tilt-3d.tilt-returning { transition:transform .5s ease !important; }

/* ── 3D Dashboard Showcase ── */
.idx-showcase {
  background:linear-gradient(135deg,#080b18 0%,#0f1627 100%);
  overflow:hidden;position:relative;
}
.idx-showcase::before {
  content:'';position:absolute;inset:0;
  background:radial-gradient(ellipse 80% 60% at 70% 50%,rgba(230,0,0,.07),transparent 60%),
             radial-gradient(ellipse 50% 70% at 10% 80%,rgba(37,99,235,.07),transparent 55%);
}
.device-perspective-wrap {
  perspective:1400px;
  perspective-origin:50% 50%;
}
.device-screen-3d {
  background:linear-gradient(160deg,#111827,#0d1117);
  border-radius:18px;
  border:1.5px solid rgba(255,255,255,.12);
  box-shadow:0 50px 120px rgba(0,0,0,.8),
             0 0 0 1px rgba(255,255,255,.04),
             inset 0 1px 0 rgba(255,255,255,.07);
  transform:rotateY(-18deg) rotateX(6deg);
  transform-style:preserve-3d;
  transition:transform .6s cubic-bezier(.4,0,.2,1);
  padding:20px;
  position:relative;
  overflow:hidden;
}
.device-perspective-wrap:hover .device-screen-3d {
  transform:rotateY(-6deg) rotateX(2deg);
}
.device-screen-3d::before {
  content:'';position:absolute;top:0;left:0;right:0;height:1px;
  background:linear-gradient(90deg,transparent,rgba(255,255,255,.15),transparent);
}
/* Dashboard UI inside screen */
.dash-topbar {
  display:flex;align-items:center;justify-content:space-between;
  padding:10px 14px;
  background:rgba(255,255,255,.04);
  border-radius:10px;margin-bottom:14px;
}
.dash-dot { width:8px;height:8px;border-radius:50%;margin-right:5px;display:inline-block; }
.dash-row { display:flex;gap:10px;margin-bottom:10px; }
.dash-kpi {
  flex:1;background:rgba(255,255,255,.05);border-radius:10px;padding:12px 14px;
  border:1px solid rgba(255,255,255,.06);
}
.dash-kpi-val { font-size:1.2rem;font-weight:800;color:#fff;line-height:1; }
.dash-kpi-lbl { font-size:.62rem;color:rgba(255,255,255,.4);text-transform:uppercase;letter-spacing:.07em;margin-top:4px; }
.dash-map {
  background:rgba(255,255,255,.03);border-radius:10px;padding:14px;
  border:1px solid rgba(255,255,255,.06);margin-bottom:10px;
  min-height:80px;position:relative;overflow:hidden;
}
.dash-map-dot {
  position:absolute;width:8px;height:8px;border-radius:50%;
  background:#e60000;box-shadow:0 0 0 3px rgba(230,0,0,.25);
  animation:map-ping 2.5s ease-in-out infinite;
}
@keyframes map-ping {
  0%,100%{box-shadow:0 0 0 3px rgba(230,0,0,.25)}
  50%{box-shadow:0 0 0 8px rgba(230,0,0,.08)}
}
/* Floating chips that pop out of screen */
.dash-chip {
  position:absolute;
  background:rgba(255,255,255,.96);
  border-radius:12px;padding:10px 16px;
  box-shadow:0 12px 40px rgba(0,0,0,.35);
  font-size:.75rem;font-weight:700;
  display:flex;align-items:center;gap:8px;
  white-space:nowrap;
  transform:translateZ(0);
}
.dash-chip-1 { top:-20px;right:-30px;animation:chip-float1 5s ease-in-out infinite; }
.dash-chip-2 { bottom:20px;left:-40px;animation:chip-float2 6s ease-in-out infinite 1s; }
.dash-chip-3 { top:40%;right:-50px;animation:chip-float1 7s ease-in-out infinite .5s; }
@keyframes chip-float1 { 0%,100%{transform:translateY(0) translateZ(20px)} 50%{transform:translateY(-10px) translateZ(24px)} }
@keyframes chip-float2 { 0%,100%{transform:translateY(0) translateZ(20px)} 50%{transform:translateY(8px) translateZ(16px)} }

/* ── 3D Step hover ── */
.idx-step-wrap {
  perspective:800px;
}
.idx-step-inner {
  background:#fff;border-radius:16px;padding:36px 24px;text-align:center;
  border:1px solid #f0f0f0;box-shadow:0 2px 16px rgba(0,0,0,.04);
  transform-style:preserve-3d;
  transition:transform .4s ease,box-shadow .4s ease;
}
.idx-step-inner:hover {
  transform:perspective(800px) rotateX(-5deg) translateY(-6px);
  box-shadow:0 20px 60px rgba(0,0,0,.1);
}
.idx-step-num {
  width:64px;height:64px;border-radius:50%;
  background:#e60000;color:#fff;font-size:1.5rem;font-weight:900;
  display:flex;align-items:center;justify-content:center;
  margin:0 auto 20px;position:relative;z-index:1;
  transition:transform .3s ease;
}
.idx-step-inner:hover .idx-step-num {
  transform:rotateY(360deg) scale(1.1);
  box-shadow:0 8px 24px rgba(230,0,0,.4);
}
.idx-step-num::after {
  content:'';position:absolute;top:50%;left:100%;
  width:calc(100% + 48px);height:2px;
  border-top:2px dashed rgba(230,0,0,.25);
  transform:translateY(-50%);z-index:0;
}
.idx-step-wrap:last-child .idx-step-num::after { display:none; }
@media(max-width:767px){ .idx-step-num::after { display:none; } }
.idx-step-icon { font-size:2rem;color:#e60000;margin-bottom:12px; }
.idx-step-inner h4 { font-size:1rem;font-weight:700;color:#1a202c;margin-bottom:8px; }
.idx-step-inner p  { font-size:.875rem;color:#64748b;line-height:1.6; }

/* ── Why Smartrack (dark) ── */
.idx-why {
  background:linear-gradient(135deg,#0b0e1a 0%,#111622 100%);
  color:#fff;
  position:relative;
  overflow:hidden;
  background-attachment:fixed;
  background-position:center;
}
.idx-why::before {
  content:'';position:absolute;top:0;left:0;right:0;bottom:0;
  background-image:radial-gradient(circle at 30% 60%,rgba(230,0,0,.05) 0%,transparent 50%),
                   radial-gradient(circle at 70% 40%,rgba(37,99,235,.05) 0%,transparent 50%);
  animation:parallax-drift 20s ease-in-out infinite;
}
.idx-feature-pill {
  display:flex;align-items:center;gap:14px;padding:14px 18px;
  background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.07);
  border-radius:12px;transition:all .3s;
  margin-bottom:12px;
  position:relative;
  z-index:2;
  animation:float-in-up .6s ease-out;
}
.idx-feature-pill:hover { background:rgba(230,0,0,.08);border-color:rgba(230,0,0,.25); }
.idx-feature-icon { width:36px;height:36px;border-radius:9px;flex-shrink:0;
  background:rgba(230,0,0,.15);display:flex;align-items:center;justify-content:center;
  color:#e60000;font-size:1rem; }
.idx-feature-text h5 { font-size:.9rem;font-weight:700;color:#fff;margin:0 0 2px; }
.idx-feature-text p  { font-size:.8rem;color:rgba(255,255,255,.5);margin:0;line-height:1.5; }

/* ── How it works ── */
.idx-step {
  text-align:center;padding:36px 24px;position:relative;
}
.idx-step-num {
  width:64px;height:64px;border-radius:50%;
  background:#e60000;color:#fff;font-size:1.5rem;font-weight:900;
  display:flex;align-items:center;justify-content:center;
  margin:0 auto 20px;position:relative;z-index:1;
}
.idx-step-num::after {
  content:'';position:absolute;top:50%;left:100%;
  width:calc(100% + 48px);height:2px;
  border-top:2px dashed rgba(230,0,0,.25);
  transform:translateY(-50%);z-index:0;
}
/* Remove connector from last step */
.idx-step:last-child .idx-step-num::after { display:none; }
@media(max-width:767px){ .idx-step-num::after { display:none; } }

.idx-step-icon { font-size:2rem;color:#e60000;margin-bottom:12px; }
.idx-step h4   { font-size:1rem;font-weight:700;color:#1a202c;margin-bottom:8px; }
.idx-step p    { font-size:.875rem;color:#64748b;line-height:1.6; }

/* ── CTA banner ── */
.idx-cta-banner {
  background:linear-gradient(135deg,#c40000 0%,#8b0000 100%);
  position:relative;overflow:hidden;
}
.idx-cta-banner::before {
  content:'';position:absolute;inset:0;
  background-image:radial-gradient(rgba(255,255,255,.06) 1px,transparent 1px);
  background-size:28px 28px;
}
</style>

<!-- ═══════════════════════════════════════════════════════════
     § 2  PROMO CAROUSEL BANNER  (slides editable via Admin → Homepage)
════════════════════════════════════════════════════════════ -->
<?php if (!empty($promoSlides)): $multi = count($promoSlides) > 1; ?>
<section class="idx-promo-banner section" style="padding:0;overflow:hidden;background:#fff;">
  <div class="swiper init-swiper" style="width:100%;height:500px;" data-aos="fade-up">
    <script type="application/json" class="swiper-config">
      {"loop":<?php echo $multi ? 'true' : 'false'; ?>,"speed":800,"autoplay":{"delay":6000,"disableOnInteraction":false},"pagination":{"el":".swiper-pagination","type":"bullets","clickable":true},"navigation":{"nextEl":".swiper-button-next","prevEl":".swiper-button-prev"},"effect":"fade","fadeEffect":{"crossFade":true}}
    </script>
    <div class="swiper-wrapper">
      <?php foreach ($promoSlides as $slide): $th = $slide['theme']; ?>
      <div class="swiper-slide" style="display:flex;align-items:stretch;">
        <div style="width:50%;height:100%;overflow:hidden;position:relative;background:#1a1a1a;">
          <img src="<?php echo escape($slide['image']); ?>" alt="<?php echo escape($th['alt']); ?>"
               style="width:100%;height:100%;object-fit:cover;object-position:center;animation:img-zoom 8s ease-out infinite;">
        </div>
        <div style="width:50%;height:100%;background:linear-gradient(135deg,<?php echo $th['grad1']; ?> 0%,<?php echo $th['grad2']; ?> 100%);display:flex;flex-direction:column;align-items:center;justify-content:center;padding:40px;color:#fff;text-align:center;position:relative;overflow:hidden;">
          <div style="position:absolute;top:-50px;right:-50px;width:200px;height:200px;background:rgba(255,255,255,.06);border-radius:50%;animation:float 6s ease-in-out infinite;"></div>
          <div style="position:relative;z-index:1;">
            <div style="font-size:.85rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:rgba(255,255,255,.8);margin-bottom:12px;"><?php echo escape($slide['eyebrow']); ?></div>
            <h2 style="font-size:clamp(2.4rem,4vw,3.4rem);font-weight:900;margin:0 0 16px;line-height:1.05;text-shadow:0 4px 12px rgba(0,0,0,.2);"><?php echo $slide['title']; ?></h2>
            <p style="font-size:1.05rem;margin:0 0 24px;line-height:1.6;color:rgba(255,255,255,.9);"><?php echo $slide['subtitle']; ?></p>
            <a href="<?php echo escape($slide['link']); ?>" style="display:inline-flex;align-items:center;gap:10px;background:#fff;color:<?php echo $th['btn']; ?>;font-weight:800;font-size:1rem;padding:15px 38px;border-radius:50px;text-decoration:none;box-shadow:0 8px 24px rgba(0,0,0,.25);transition:all .3s;cursor:pointer;" onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 12px 36px rgba(0,0,0,.35)'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 8px 24px rgba(0,0,0,.25)'">
              <i class="bi <?php echo escape($th['icon']); ?>"></i> <?php echo escape($slide['btn']); ?>
            </a>
            <?php if (trim($slide['note']) !== ''): ?>
              <p style="font-size:.8rem;color:rgba(255,255,255,.6);margin-top:14px;"><?php echo escape($slide['note']); ?></p>
            <?php endif; ?>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php if ($multi): ?>
    <div class="swiper-pagination" style="bottom:20px;"></div>
    <div class="swiper-button-prev" style="color:#fff;background:rgba(230,0,0,.7);width:50px;height:50px;border-radius:50%;top:50%;transform:translateY(-50%);left:20px;"></div>
    <div class="swiper-button-next" style="color:#fff;background:rgba(230,0,0,.7);width:50px;height:50px;border-radius:50%;top:50%;transform:translateY(-50%);right:20px;"></div>
    <?php endif; ?>
  </div>
  <style>
    @keyframes img-zoom { from{transform:scale(1)} to{transform:scale(1.05)} }
    @keyframes float { 0%,100%{transform:translateY(0)} 50%{transform:translateY(20px)} }
  </style>
</section>
<?php endif; ?>

<!-- ═══════════════════════════════════════════════════════════
     § 2.5  STATS STRIP (with parallax effect)
════════════════════════════════════════════════════════════ -->
<div class="idx-stats" style="background:linear-gradient(180deg,#fff 0%,#f9f9f9 100%);position:relative;background-attachment:fixed;background-position:center;">
  <div class="container-fluid px-0">
    <div class="row g-0">
      <?php
      $stats = [
        ['232', t('Happy Clients','Clients Satisfaits'),          'bi-emoji-smile',       '#2563eb'],
        ['521', t('Projects Delivered','Projets Réalisés'),       'bi-journal-check',     '#e60000'],
        ['1463',t('Support Hours','Heures de Support'),           'bi-headset',           '#16a34a'],
        ['100', t('% Uptime SLA','% Disponibilité SLA'),          'bi-shield-fill-check', '#f59e0b'],
      ];
      foreach ($stats as [$num, $label, $icon, $col]):
      ?>
        <div class="col-6 col-lg-3">
          <div class="idx-stats stat-block">
            <i class="bi <?php echo $icon; ?>" style="font-size:1.6rem;color:<?php echo $col; ?>;margin-bottom:8px;display:block;"></i>
            <span class="idx-stat-num purecounter"
                  data-purecounter-start="0"
                  data-purecounter-end="<?php echo $num; ?>"
                  data-purecounter-duration="1"><?php echo $num; ?></span>
            <div class="idx-stat-lbl"><?php echo $label; ?></div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<!-- ═══════════════════════════════════════════════════════════
     § 3  SERVICES (with parallax background)
════════════════════════════════════════════════════════════ -->
<section id="services" class="section" style="background:#f8f9fb;position:relative;background-attachment:fixed;" data-aos="fade-up">
  <div class="container" style="position:relative;z-index:2;">
    <div class="section-title" data-aos="fade-up">
      <h2><?php echo escape(t('Our Services','Nos Services')); ?></h2>
      <p><span id="form"><?php echo escape(get_translation('form')); ?></span></p>
    </div>
    <div class="row gy-4">
      <?php
      // If no DB services, use defaults
      if (empty($dbServices)) {
        $dbServices = [
          ['id'=>0,'slug'=>'vehicle-tracking',   'title_en'=>'Fleet Tracking',      'title_fr'=>'Suivi de Flotte',
           'summary_en'=>'Monitor your entire fleet in real-time with live location, speed alerts and geofencing.',
           'summary_fr'=>'Surveillez votre flotte en temps réel avec la localisation en direct, les alertes de vitesse et le géorepérage.'],
          ['id'=>0,'slug'=>'fuel-monitoring',    'title_en'=>'Fuel Monitoring',     'title_fr'=>'Surveillance Carburant',
           'summary_en'=>'Cut fuel costs by up to 30% with precise consumption monitoring and anomaly detection.',
           'summary_fr'=>'Réduisez les coûts de carburant jusqu\'à 30% grâce à une surveillance précise.'],
          ['id'=>0,'slug'=>'security-solutions', 'title_en'=>'Security Solutions',  'title_fr'=>'Solutions Sécurité',
           'summary_en'=>'Protect assets with alarm systems, remote engine immobilisation and 24/7 monitoring.',
           'summary_fr'=>'Protégez vos actifs avec des systèmes d\'alarme et une surveillance 24h/24.'],
        ];
      }
      foreach ($dbServices as $i => $s):
        $title   = ($lang==='fr' && $s['title_fr'])   ? $s['title_fr']   : $s['title_en'];
        $summary = ($lang==='fr' && $s['summary_fr'])  ? $s['summary_fr'] : $s['summary_en'];
        $icon    = $serviceIcons[$s['slug']] ?? 'bi-geo-alt-fill';
      ?>
        <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="<?php echo ($i%3+1)*100; ?>">
          <div class="idx-flip-wrap">
            <div class="idx-flip-card">
              <!-- FRONT -->
              <div class="idx-flip-front">
                <div class="idx-service-icon"><i class="bi <?php echo escape($icon); ?>"></i></div>
                <h3><?php echo escape($title); ?></h3>
                <p><?php echo escape($summary); ?></p>
                <div class="idx-flip-hint">
                  <i class="bi bi-arrow-repeat"></i> <?php echo escape(t('Hover to explore','Survolez pour explorer')); ?>
                </div>
              </div>
              <!-- BACK -->
              <div class="idx-flip-back">
                <div class="flip-icon"><i class="bi <?php echo escape($icon); ?>"></i></div>
                <h3><?php echo escape($title); ?></h3>
                <p><?php echo escape($summary); ?></p>
                <?php if ($s['id']): ?>
                  <a href="<?php echo escape(site_url('service.php?id='.$s['id'])); ?>" class="idx-flip-back-link">
                    <?php echo escape(t('Full Details','Voir Détails')); ?> <i class="bi bi-arrow-right"></i>
                  </a>
                <?php else: ?>
                  <a href="<?php echo escape(site_url('contact.php')); ?>" class="idx-flip-back-link">
                    <?php echo escape(t('Get a Quote','Obtenir un Devis')); ?> <i class="bi bi-arrow-right"></i>
                  </a>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════════════════════════
     § 4  WHY SMARTRACK  (dark contrast section)
════════════════════════════════════════════════════════════ -->
<section class="idx-why section">
  <div class="container" style="position:relative;z-index:2;">
    <div class="row gy-5 align-items-center">

      <div class="col-lg-5" data-aos="fade-right">
        <div style="display:inline-block;background:rgba(230,0,0,.15);border:1px solid rgba(230,0,0,.3);
                    border-radius:30px;padding:4px 16px;margin-bottom:16px;">
          <span style="font-size:.72rem;font-weight:700;color:#ff6666;letter-spacing:.08em;text-transform:uppercase;">
            <?php echo escape(t('Why Choose Smartrack', 'Pourquoi Choisir Smartrack')); ?>
          </span>
        </div>
        <h2 style="font-size:clamp(1.8rem,3.5vw,2.6rem);font-weight:800;color:#fff;
                   line-height:1.2;letter-spacing:-.03em;margin-bottom:16px;">
          <span id="adv"><?php echo escape(get_translation('adv')); ?></span>
        </h2>
        <p style="color:rgba(255,255,255,.55);font-size:.975rem;line-height:1.75;margin-bottom:32px;">
          <span id="empower"><?php echo escape(get_translation('empower')); ?></span>
        </p>
        <a href="<?php echo escape(site_url('about.php')); ?>"
           style="display:inline-flex;align-items:center;gap:8px;color:#e60000;font-weight:700;font-size:.9rem;text-decoration:none;transition:gap .2s;"
           onmouseover="this.style.gap='14px'" onmouseout="this.style.gap='8px'">
          <?php echo escape(t('Our Story','Notre Histoire')); ?> <i class="bi bi-arrow-right"></i>
        </a>
      </div>

      <div class="col-lg-7" data-aos="fade-left" data-aos-delay="100">
        <div class="row g-3">
          <?php
          $features = [
            ['bi-geo-alt-fill',      '#e60000', t('Real-Time GPS Tracking','Suivi GPS en Temps Réel'),   t('Live location updates every 30 seconds for your entire fleet, accessible from any device.', "Mises à jour en direct toutes les 30 secondes pour toute votre flotte, accessibles depuis n'importe quel appareil.")],
            ['bi-fuel-pump-fill',    '#f59e0b', t('Fuel Monitoring','Surveillance Carburant'),           t('Detect theft, measure consumption and reduce waste with automated fuel reports.', "Détectez le vol, mesurez la consommation et réduisez le gaspillage avec des rapports automatisés.")],
            ['bi-person-badge-fill', '#22c55e', t('Driver Behaviour','Comportement Conducteur'),         t('Score each driver on speed, braking and idling. Safer drivers mean lower costs.', "Notez chaque conducteur sur la vitesse, le freinage et le ralenti. Des conducteurs plus sûrs = moins de coûts.")],
            ['bi-geo-fill',          '#3b82f6', t('Geofencing Alerts','Alertes de Géozone'),             t('Define zones and get instant SMS or app alerts when vehicles enter or leave.', "Définissez des zones et recevez des alertes SMS instantanées quand des véhicules entrent ou sortent.")],
            ['bi-lock-fill',         '#8b5cf6', t('Remote Immobilisation','Immobilisation à Distance'),  t('Cut the engine remotely the moment a vehicle is reported stolen.', "Coupez le moteur à distance dès qu'un véhicule est signalé volé.")],
            ['bi-headset',           '#06b6d4', t('24/7 Local Support','Support Local 24h/7j'),          t('Our Cameroon-based team is reachable around the clock — in French and English.', "Notre équipe basée au Cameroun est joignable 24h/24 — en français et en anglais.")],
          ];
          foreach ($features as [$icon,$color,$title,$desc]):
          ?>
            <div class="col-md-6">
              <div class="idx-feature-pill">
                <div class="idx-feature-icon" style="background:<?php echo $color; ?>22;color:<?php echo $color; ?>;">
                  <i class="bi <?php echo $icon; ?>"></i>
                </div>
                <div class="idx-feature-text">
                  <h5><?php echo $title; ?></h5>
                  <p><?php echo $desc; ?></p>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════════════════════════
     § 5  HOW IT WORKS
════════════════════════════════════════════════════════════ -->
<section id="how-it-works" class="section" style="background:#fff;">
  <div class="container">
    <div class="section-title" data-aos="fade-up">
      <h2><?php echo escape(t('How It Works','Comment Ça Fonctionne')); ?></h2>
      <p><?php echo escape(t('Up and running in three simple steps — no technical expertise required', "Opérationnel en trois étapes simples — aucune expertise technique requise")); ?></p>
    </div>
    <div class="row g-4 position-relative" data-aos="fade-up" data-aos-delay="100">
      <?php
      $steps = [
        ['bi-cpu-fill',       '01', t('Install the Tracker','Installer le Traceur'),    t('Our certified technician installs a discreet GPS device in each vehicle. The process takes less than 30 minutes per vehicle with zero downtime.', "Notre technicien certifié installe un boîtier GPS discret dans chaque véhicule en moins de 30 minutes, sans interruption d'activité.")],
        ['bi-phone-fill',     '02', t('Monitor in Real Time','Surveiller en Temps Réel'),t('Log in to your Smartrack web or mobile dashboard from anywhere. See every vehicle, driver score, fuel level and alert — live.', "Connectez-vous à votre tableau de bord Smartrack depuis n'importe où. Voir chaque véhicule, score conducteur, niveau de carburant et alerte — en direct.")],
        ['bi-graph-up-arrow', '03', t('Optimise & Save','Optimiser & Économiser'),       t('Receive automated weekly reports. Identify waste, reward safe drivers, and cut costs. Most clients see ROI within the first 90 days.', "Recevez des rapports hebdomadaires automatisés. Identifiez les gaspillages et réduisez les coûts. La plupart des clients voient un ROI dans les 90 premiers jours.")],
      ];
      foreach ($steps as $i => [$icon,$num,$title,$desc]):
      ?>
        <div class="col-lg-4 col-md-12 idx-step-wrap" data-aos="fade-up" data-aos-delay="<?php echo ($i+1)*100; ?>">
          <div class="idx-step-inner">
            <div class="idx-step-num"><?php echo $num; ?></div>
            <i class="bi <?php echo $icon; ?> idx-step-icon"></i>
            <h4><?php echo $title; ?></h4>
            <p><?php echo $desc; ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    <div class="text-center mt-4" data-aos="fade-up">
      <a href="<?php echo escape(site_url('contact.php')); ?>" class="btn-get-started"
         style="display:inline-flex;align-items:center;gap:10px;">
        <i class="bi bi-calendar-check-fill"></i>
        <?php echo escape(t('Book a Free Installation Demo', 'Réserver une Démo d\'Installation Gratuite')); ?>
      </a>
    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════════════════════════
     § 5.3  3D LIVE DASHBOARD SHOWCASE
════════════════════════════════════════════════════════════ -->
<section class="idx-showcase section">
  <div class="container" style="position:relative;z-index:2;">
    <div class="row align-items-center gy-5">

      <!-- Left: copy -->
      <div class="col-lg-5" data-aos="fade-right">
        <div style="display:inline-block;background:rgba(230,0,0,.15);border:1px solid rgba(230,0,0,.3);
                    border-radius:30px;padding:4px 16px;margin-bottom:18px;">
          <span style="font-size:.72rem;font-weight:700;color:#ff6b6b;letter-spacing:.08em;text-transform:uppercase;">
            <?php echo escape(t('Live Dashboard', 'Tableau de Bord en Direct')); ?>
          </span>
        </div>
        <h2 style="font-size:clamp(1.9rem,3.5vw,2.8rem);font-weight:800;color:#fff;
                   line-height:1.2;letter-spacing:-.03em;margin-bottom:16px;">
          <?php echo escape(t('Total fleet control in one view', 'Contrôle total de la flotte en une seule vue')); ?>
        </h2>
        <p style="color:rgba(255,255,255,.55);line-height:1.8;margin-bottom:28px;font-size:.975rem;">
          <?php echo escape(t('Your Smartrack dashboard gives you instant visibility across every vehicle, driver, and zone — on any device, anywhere in the world.', "Votre tableau de bord Smartrack vous donne une visibilité instantanée sur chaque véhicule, conducteur et zone — sur n'importe quel appareil, partout dans le monde.")); ?>
        </p>
        <div class="d-flex flex-column gap-3 mb-32" style="margin-bottom:32px;">
          <?php
          $usp = [
            ['bi-map-fill',       '#e60000', t('Live vehicle positions refreshed every 30 s',       'Positions des véhicules actualisées toutes les 30 s')],
            ['bi-bell-fill',      '#f59e0b', t('Instant push alerts for speed & zone violations',   "Alertes push instantanées pour vitesse et violations de zone")],
            ['bi-bar-chart-fill', '#22c55e', t('Automated weekly savings & ROI reports',             'Rapports hebdomadaires automatisés sur les économies et le ROI')],
          ];
          foreach ($usp as [$ic,$col,$txt]):
          ?>
            <div style="display:flex;align-items:center;gap:14px;">
              <div style="width:38px;height:38px;border-radius:10px;background:<?php echo $col; ?>22;
                          flex-shrink:0;display:flex;align-items:center;justify-content:center;">
                <i class="bi <?php echo $ic; ?>" style="color:<?php echo $col; ?>;font-size:1rem;"></i>
              </div>
              <span style="color:rgba(255,255,255,.75);font-size:.9rem;"><?php echo $txt; ?></span>
            </div>
          <?php endforeach; ?>
        </div>
        <a href="<?php echo escape(site_url('contact.php')); ?>"
           style="display:inline-flex;align-items:center;gap:10px;
                  background:#e60000;color:#fff;font-weight:700;font-size:.9rem;
                  padding:13px 30px;border-radius:50px;text-decoration:none;
                  box-shadow:0 6px 24px rgba(230,0,0,.35);transition:all .25s;"
           onmouseover="this.style.background='#c40000';this.style.transform='translateY(-2px)'"
           onmouseout="this.style.background='#e60000';this.style.transform='translateY(0)'">
          <i class="bi bi-display-fill"></i> <?php echo escape(t('Request a Live Demo', 'Demander une Démo en Direct')); ?>
        </a>
      </div>

      <!-- Right: 3D screen mockup -->
      <div class="col-lg-7 d-none d-lg-block" data-aos="fade-left" data-aos-delay="150">
        <div class="device-perspective-wrap" style="position:relative;padding:60px 40px 60px 20px;">

          <!-- 3D screen -->
          <div class="device-screen-3d tilt-3d">
            <!-- Topbar -->
            <div class="dash-topbar">
              <div style="display:flex;align-items:center;gap:6px;">
                <span class="dash-dot" style="background:#e60000;"></span>
                <span class="dash-dot" style="background:#f59e0b;"></span>
                <span class="dash-dot" style="background:#22c55e;"></span>
              </div>
              <span style="font-size:.7rem;color:rgba(255,255,255,.4);font-weight:600;letter-spacing:.08em;">
                SMARTRACK DASHBOARD
              </span>
              <span style="display:flex;align-items:center;gap:5px;font-size:.65rem;color:#22c55e;font-weight:700;">
                <span style="width:6px;height:6px;border-radius:50%;background:#22c55e;
                             box-shadow:0 0 0 3px rgba(34,197,94,.25);"></span>
                LIVE
              </span>
            </div>

            <!-- KPI row -->
            <div class="dash-row">
              <?php
              $dkpis = [
                ['238', t('Vehicles','Véhicules'),     '#e60000'],
                ['97%', t('Uptime','Disponibilité'),   '#22c55e'],
                ['18L', t('Fuel Saved','Carburant'),   '#f59e0b'],
                ['0',   t('Alerts','Alertes'),         '#3b82f6'],
              ];
              foreach ($dkpis as [$v,$l,$c]):
              ?>
              <div class="dash-kpi">
                <div class="dash-kpi-val" style="color:<?php echo $c; ?>;"><?php echo $v; ?></div>
                <div class="dash-kpi-lbl"><?php echo $l; ?></div>
              </div>
              <?php endforeach; ?>
            </div>

            <!-- Map area -->
            <div class="dash-map">
              <span style="font-size:.6rem;color:rgba(255,255,255,.3);font-weight:700;
                           letter-spacing:.1em;text-transform:uppercase;"><?php echo escape(t('Fleet Map — Cameroon', 'Carte de Flotte — Cameroun')); ?></span>
              <!-- Grid lines -->
              <div style="position:absolute;inset:0;
                background-image:linear-gradient(rgba(255,255,255,.03) 1px,transparent 1px),
                                 linear-gradient(90deg,rgba(255,255,255,.03) 1px,transparent 1px);
                background-size:20px 20px;border-radius:10px;"></div>
              <!-- Vehicle dots -->
              <div class="dash-map-dot" style="top:35%;left:30%;"></div>
              <div class="dash-map-dot" style="top:55%;left:55%;animation-delay:.6s;"></div>
              <div class="dash-map-dot" style="top:25%;left:65%;animation-delay:1.2s;background:#22c55e;box-shadow:0 0 0 3px rgba(34,197,94,.25);"></div>
              <div class="dash-map-dot" style="top:70%;left:20%;animation-delay:1.8s;background:#f59e0b;box-shadow:0 0 0 3px rgba(245,158,11,.25);"></div>
              <div class="dash-map-dot" style="top:45%;left:75%;animation-delay:2.4s;"></div>
              <!-- Route lines (SVG) -->
              <svg style="position:absolute;inset:0;width:100%;height:100%;opacity:.2;" xmlns="http://www.w3.org/2000/svg">
                <polyline points="30%,35% 55%,55% 75%,45%" style="fill:none;stroke:#e60000;stroke-width:1;stroke-dasharray:4,4;"/>
                <polyline points="65%,25% 55%,55% 20%,70%" style="fill:none;stroke:#22c55e;stroke-width:1;stroke-dasharray:4,4;"/>
              </svg>
            </div>

            <!-- Bottom chart bar -->
            <div style="background:rgba(255,255,255,.03);border-radius:10px;padding:12px 14px;
                        border:1px solid rgba(255,255,255,.06);">
              <div style="font-size:.62rem;color:rgba(255,255,255,.35);text-transform:uppercase;
                          letter-spacing:.08em;margin-bottom:8px;"><?php echo escape(t("Today's Distance (km)", 'Distance du Jour (km)')); ?></div>
              <div style="display:flex;align-items:flex-end;gap:5px;height:28px;">
                <?php
                $bars = [40,65,50,80,55,90,70,85,60,75,95,88];
                foreach ($bars as $h):
                ?>
                <div style="flex:1;background:linear-gradient(to top,#e60000,#ff4444);
                            border-radius:3px 3px 0 0;
                            height:<?php echo $h; ?>%;opacity:.7;transition:opacity .2s;"
                     onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='.7'"></div>
                <?php endforeach; ?>
              </div>
            </div>
          </div>

          <!-- Floating notification chips -->
          <div class="dash-chip dash-chip-1" style="color:#16a34a;">
            <i class="bi bi-check-circle-fill" style="color:#22c55e;font-size:1rem;"></i>
            <?php echo escape(t('Vehicle ST-042 arrived', 'Véhicule ST-042 arrivé')); ?>
          </div>
          <div class="dash-chip dash-chip-2" style="color:#e60000;">
            <i class="bi bi-fuel-pump-fill" style="color:#f59e0b;font-size:1rem;"></i>
            <?php echo escape(t('Fuel saved: 18.4 L today', 'Carburant économisé : 18,4 L aujourd\'hui')); ?>
          </div>
          <div class="dash-chip dash-chip-3" style="color:#1d4ed8;">
            <i class="bi bi-shield-fill-check" style="color:#3b82f6;font-size:1rem;"></i>
            <?php echo escape(t('0 active alerts', '0 alerte active')); ?>
          </div>

        </div>
      </div>

    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════════════════════════
     § 5.5  QUICK FACTS GRID (3D cards)
════════════════════════════════════════════════════════════ -->
<section class="section" style="background:linear-gradient(135deg,#f8f9fb 0%,#f0f0f0 100%);position:relative;overflow:hidden;">
  <div class="container" style="position:relative;z-index:2;">
    <div class="section-title" data-aos="fade-up">
      <h2><?php echo escape(t('Why Smartrack Stands Out', 'Pourquoi Smartrack Se Démarque')); ?></h2>
      <p><?php echo escape(t('Trusted by 232+ businesses across Africa for reliability and innovation', 'La confiance de plus de 232 entreprises en Afrique pour sa fiabilité et son innovation')); ?></p>
    </div>
    <div class="row g-4">
      <?php
      $facts = [
        ['bi-lightning-bolt-fill', '#e60000', t('Zero Setup Hassle',    'Installation Sans Tracas'),   t('Start tracking in under 2 hours with our plug-and-play GPS devices',                    "Commencez à suivre en moins de 2 heures avec nos appareils GPS plug-and-play")],
        ['bi-graph-up-arrow',      '#f59e0b', t('Immediate ROI',        'Retour sur Investissement'),  t('70% of clients see cost savings within 90 days of deployment',                          "70% des clients voient des économies dans les 90 jours suivant le déploiement")],
        ['bi-shield-fill-check',   '#22c55e', t('Bank-Grade Security',  'Sécurité Niveau Bancaire'),   t('Enterprise-level encryption with SOC 2 compliance',                                    'Chiffrement de niveau entreprise avec conformité SOC 2')],
        ['bi-building',            '#3b82f6', t("Africa's Best Support", 'Meilleur Support en Afrique'),t("24/7 English & French support from Cameroon — we understand your market",               "Support 24h/7j en anglais et français depuis le Cameroun — nous comprenons votre marché")],
      ];
      foreach ($facts as $i => [$icon,$color,$title,$desc]):
      ?>
        <div class="col-lg-6" data-aos="fade-up" data-aos-delay="<?php echo ($i+1)*100; ?>">
          <div style="background:#fff;border-radius:16px;padding:32px;border:1px solid #e9ecef;
                      box-shadow:0 2px 20px rgba(0,0,0,.04);
                      position:relative;overflow:hidden;
                      transition:all .35s;
                      cursor:pointer;"
               class="tilt-3d">
            <div style="position:absolute;top:0;left:0;right:0;bottom:0;background:linear-gradient(135deg,<?php echo $color; ?>08 0%,transparent 100%);"></div>
            <div style="position:relative;z-index:1;">
              <div style="width:64px;height:64px;border-radius:14px;background:<?php echo $color; ?>15;
                          display:flex;align-items:center;justify-content:center;margin-bottom:16px;">
                <i class="bi <?php echo $icon; ?>" style="font-size:1.8rem;color:<?php echo $color; ?>;"></i>
              </div>
              <h4 style="font-size:1.1rem;font-weight:700;color:#1a202c;margin-bottom:8px;"><?php echo $title; ?></h4>
              <p style="font-size:.9rem;color:#64748b;line-height:1.6;margin:0;"><?php echo $desc; ?></p>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════════════════════════
     § 6  FEATURES TAB
════════════════════════════════════════════════════════════ -->
<section id="features" class="features section" style="background:#f8f9fb;">
  <div class="container">

    <div class="section-title" data-aos="fade-up">
      <h2><?php echo escape(t('Platform Features', 'Fonctionnalités de la Plateforme')); ?></h2>
      <p><?php echo escape(t('Everything your fleet needs, in one powerful dashboard', 'Tout ce dont votre flotte a besoin, dans un seul tableau de bord puissant')); ?></p>
    </div>

    <ul class="nav nav-tabs row g-2 d-flex" data-aos="fade-up" data-aos-delay="100" role="tablist">
      <li class="nav-item col-3" role="presentation">
        <a class="nav-link active show" data-bs-toggle="tab" data-bs-target="#features-tab-1" aria-selected="true" role="tab">
          <h4><span id="time"><?php echo escape(get_translation('time')); ?></span></h4>
        </a>
      </li>
      <li class="nav-item col-3" role="presentation">
        <a class="nav-link" data-bs-toggle="tab" data-bs-target="#features-tab-2" aria-selected="false" tabindex="-1" role="tab">
          <h4><span id="driver"><?php echo escape(get_translation('driver')); ?></span></h4>
        </a>
      </li>
      <li class="nav-item col-3" role="presentation">
        <a class="nav-link" data-bs-toggle="tab" data-bs-target="#features-tab-3" aria-selected="false" tabindex="-1" role="tab">
          <h4><span id="advan"><?php echo escape(get_translation('advan')); ?></span></h4>
        </a>
      </li>
      <li class="nav-item col-3" role="presentation">
        <a class="nav-link" data-bs-toggle="tab" data-bs-target="#features-tab-4" aria-selected="false" tabindex="-1" role="tab">
          <h4><span id="custom"><?php echo escape(get_translation('custom')); ?></span></h4>
        </a>
      </li>
    </ul>

    <div class="tab-content" data-aos="fade-up" data-aos-delay="200">
      <div class="tab-pane fade active show" id="features-tab-1" role="tabpanel">
        <div class="row align-items-center">
          <div class="col-lg-6 order-2 order-lg-1 mt-3 mt-lg-0">
            <h3><span id="sgps"><?php echo escape(get_translation('sgps')); ?></span></h3>
            <p class="fst-italic"><span id="gtrack"><?php echo escape(get_translation('gtrack')); ?></span></p>
            <ul>
              <li><i class="bi bi-check2-all"></i> <span id="livel"><?php echo escape(get_translation('livel')); ?></span></li>
              <li><i class="bi bi-check2-all"></i> <span id="efi"><?php echo escape(get_translation('efi')); ?></span></li>
              <li><i class="bi bi-check2-all"></i> <span id="ins"><?php echo escape(get_translation('ins')); ?></span></li>
            </ul>
          </div>
          <div class="col-lg-6 order-1 order-lg-2 text-center">
            <img src="<?php echo escape(site_url('assets/img/GPS Tracking _ Vehicle Tracking System Ireland Landing Page.jpg')); ?>" alt="" class="img-fluid" style="border-radius:12px;box-shadow:0 8px 40px rgba(0,0,0,.12);">
          </div>
        </div>
      </div>
      <div class="tab-pane fade" id="features-tab-2" role="tabpanel">
        <div class="row align-items-center">
          <div class="col-lg-6 order-2 order-lg-1 mt-3 mt-lg-0">
            <h3><span id="dbe"><?php echo escape(get_translation('dbe')); ?></span></h3>
            <p class="fst-italic"><span id="with"><?php echo escape(get_translation('with')); ?></span></p>
            <ul>
              <li><i class="bi bi-check2-all"></i> <span id="safety"><?php echo escape(get_translation('safety')); ?></span></li>
              <li><i class="bi bi-check2-all"></i> <span id="fuel"><?php echo escape(get_translation('fuel')); ?></span></li>
              <li><i class="bi bi-check2-all"></i> <span id="perf"><?php echo escape(get_translation('perf')); ?></span></li>
            </ul>
          </div>
          <div class="col-lg-6 order-1 order-lg-2 text-center">
            <img src="<?php echo escape(site_url('assets/img/How IoT technology helps transportation and logistics industry.jpg')); ?>" alt="" class="img-fluid" style="border-radius:12px;box-shadow:0 8px 40px rgba(0,0,0,.12);">
          </div>
        </div>
      </div>
      <div class="tab-pane fade" id="features-tab-3" role="tabpanel">
        <div class="row align-items-center">
          <div class="col-lg-6 order-2 order-lg-1 mt-3 mt-lg-0">
            <h3><span id="advanced"><?php echo escape(get_translation('advanced')); ?></span></h3>
            <ul>
              <li><i class="bi bi-check2-all"></i> <span id="scam"><?php echo escape(get_translation('scam')); ?></span></li>
              <li><i class="bi bi-check2-all"></i> <span id="control"><?php echo escape(get_translation('control')); ?></span></li>
              <li><i class="bi bi-check2-all"></i> <span id="alerts"><?php echo escape(get_translation('alerts')); ?></span></li>
            </ul>
            <p class="fst-italic"><span id="inter"><?php echo escape(get_translation('inter')); ?></span></p>
          </div>
          <div class="col-lg-6 order-1 order-lg-2 text-center">
            <img src="<?php echo escape(site_url('assets/img/biometric.jpg')); ?>" alt="" class="img-fluid" style="border-radius:12px;box-shadow:0 8px 40px rgba(0,0,0,.12);">
          </div>
        </div>
      </div>
      <div class="tab-pane fade" id="features-tab-4" role="tabpanel">
        <div class="row align-items-center">
          <div class="col-lg-6 order-2 order-lg-1 mt-3 mt-lg-0">
            <h3><span id="customreport"><?php echo escape(get_translation('customreport')); ?></span></h3>
            <p class="fst-italic"><span id="reporttools"><?php echo escape(get_translation('reporttools')); ?></span></p>
            <ul>
              <li><i class="bi bi-check2-all"></i> <span id="tailor"><?php echo escape(get_translation('tailor')); ?></span></li>
              <li><i class="bi bi-check2-all"></i> <span id="decision"><?php echo escape(get_translation('decision')); ?></span></li>
              <li><i class="bi bi-check2-all"></i> <span id="automated"><?php echo escape(get_translation('automated')); ?></span></li>
            </ul>
          </div>
          <div class="col-lg-6 order-1 order-lg-2 text-center">
            <img src="<?php echo escape(site_url('assets/img/gear.jpg')); ?>" alt="" class="img-fluid" style="border-radius:12px;box-shadow:0 8px 40px rgba(0,0,0,.12);">
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════════════════════════
     § 7  TESTIMONIALS
════════════════════════════════════════════════════════════ -->
<section id="testimonials" class="testimonials section light-background">
  <div class="container section-title" data-aos="fade-up">
    <h2><span id="testimony"><?php echo escape(get_translation('testimony')); ?></span></h2>
    <p><span id="here"><?php echo escape(get_translation('here')); ?></span></p>
  </div>
  <div class="container" data-aos="fade-up" data-aos-delay="100">
    <div class="swiper init-swiper">
      <script type="application/json" class="swiper-config">
        {"loop":true,"speed":600,"autoplay":{"delay":5000},"slidesPerView":"auto","pagination":{"el":".swiper-pagination","type":"bullets","clickable":true},"breakpoints":{"320":{"slidesPerView":1,"spaceBetween":40},"1200":{"slidesPerView":2,"spaceBetween":20}}}
      </script>
      <div class="swiper-wrapper">
        <?php if (!empty($dbTestimonials)): ?>
          <?php foreach ($dbTestimonials as $t): ?>
            <div class="swiper-slide">
              <div class="testimonial-wrap">
                <div class="testimonial-item">
                  <?php if (!empty($t['image_path'])): ?>
                    <img src="<?php echo escape($t['image_path']); ?>" class="testimonial-img" alt="">
                  <?php else: ?>
                    <div class="testimonial-img d-flex align-items-center justify-content-center rounded-circle"
                         style="width:90px;height:90px;font-size:2rem;background:#e60000;color:#fff;font-weight:700;">
                      <?php echo strtoupper(mb_substr($t['author_en'],0,1)); ?>
                    </div>
                  <?php endif; ?>
                  <h3><?php echo escape($t['author_'.$lang] ?: $t['author_en']); ?></h3>
                  <h4><?php echo escape($t['role_'.$lang]   ?: ($t['role_en'] ?? '')); ?></h4>
                  <div class="stars">
                    <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                  </div>
                  <p>
                    <i class="bi bi-quote quote-icon-left"></i>
                    <span><?php echo escape($t['quote_'.$lang] ?: $t['quote_en']); ?></span>
                    <i class="bi bi-quote quote-icon-right"></i>
                  </p>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
      <div class="swiper-pagination"></div>
    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════════════════════════
     § 8  CTA BANNER  (replaces misplaced quote form)
════════════════════════════════════════════════════════════ -->
<section class="idx-cta-banner section">
  <div class="container position-relative" style="z-index:1;">
    <div class="row align-items-center gy-4" data-aos="fade-up">
      <div class="col-lg-8">
        <h2 style="font-size:clamp(1.6rem,3vw,2.4rem);font-weight:800;color:#fff;margin-bottom:10px;letter-spacing:-.02em;">
          <?php echo escape(t('Ready to take control of your fleet?', 'Prêt à prendre le contrôle de votre flotte ?')); ?>
        </h2>
        <p style="color:rgba(255,255,255,.75);font-size:1rem;margin:0;">
          <?php echo escape(t('Get a free consultation and custom quote — no commitment, no hidden fees.', 'Obtenez une consultation gratuite et un devis personnalisé — sans engagement, sans frais cachés.')); ?>
          <?php if (!empty($contact['phone'])): ?>
            <?php echo escape(t('Or call us directly:', 'Ou appelez-nous directement :')); ?>
            <a href="tel:<?php echo escape($contact['phone']); ?>"
              style="color:#fff;font-weight:700;"><?php echo escape($contact['phone']); ?></a>
          <?php endif; ?>
        </p>
      </div>
      <div class="col-lg-4 text-lg-end">
        <a href="<?php echo escape(site_url('contact.php')); ?>"
           style="display:inline-flex;align-items:center;gap:10px;
                  background:#fff;color:#c40000;font-weight:800;font-size:1rem;
                  padding:15px 36px;border-radius:50px;text-decoration:none;
                  box-shadow:0 4px 20px rgba(0,0,0,.2);
                  transition:all .25s;"
           onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 8px 32px rgba(0,0,0,.3)'"
           onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 4px 20px rgba(0,0,0,.2)'">
          <i class="bi bi-arrow-right-circle-fill"></i>
          <?php echo escape(t('Get a Free Quote', 'Obtenir un Devis Gratuit')); ?>
        </a>
      </div>
    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════════════════════════
     § 9  RECENT BLOG POSTS
════════════════════════════════════════════════════════════ -->
<?php if (!empty($dbBlogPosts)): ?>
<section id="recent-blog-posts" class="recent-blog-posts section" style="background:#f8f9fb;">
  <div class="container section-title" data-aos="fade-up">
    <h2><span id="blog"><?php echo escape(get_translation('blog')); ?></span></h2>
    <p><?php echo escape(t('Insights on fleet management, GPS technology and security for African businesses', 'Analyses sur la gestion de flotte, la technologie GPS et la sécurité pour les entreprises africaines')); ?></p>
  </div>
  <div class="container">
    <div class="row gy-5">
      <?php foreach ($dbBlogPosts as $i => $bp):
        $bTitle   = ($lang==='fr' && $bp['title_fr'])   ? $bp['title_fr']   : $bp['title_en'];
        $bExcerpt = ($lang==='fr' && $bp['excerpt_fr']) ? $bp['excerpt_fr'] : $bp['excerpt_en'];
        $bImg     = $bp['image_path']
                    ? site_url($bp['image_path'])
                    : site_url('assets/img/blog/blog-'.($i+1).'.jpg');
      ?>
        <div class="col-xl-4 col-md-6" data-aos="fade-up" data-aos-delay="<?php echo ($i+1)*100; ?>">
          <div class="post-item position-relative h-100">
            <div class="post-img position-relative overflow-hidden">
              <img src="<?php echo escape($bImg); ?>" class="img-fluid" alt="<?php echo escape($bTitle); ?>">
              <span class="post-date"><?php echo date('M j', strtotime($bp['published_at'])); ?></span>
            </div>
            <div class="post-content d-flex flex-column">
              <span style="display:inline-block;background:rgba(230,0,0,.08);color:#e60000;font-size:.7rem;
                            font-weight:700;padding:3px 12px;border-radius:20px;margin-bottom:8px;
                            letter-spacing:.05em;text-transform:uppercase;width:fit-content;">
                <?php echo escape($bp['category']); ?>
              </span>
              <h3 class="post-title"><?php echo escape($bTitle); ?></h3>
              <?php if ($bExcerpt): ?>
                <p style="font-size:.84rem;color:#64748b;line-height:1.6;margin-bottom:10px;
                           display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                  <?php echo escape($bExcerpt); ?>
                </p>
              <?php endif; ?>
              <div class="meta d-flex align-items-center">
                <div class="d-flex align-items-center">
                  <i class="bi bi-person"></i>
                  <span class="ps-2"><?php echo escape($bp['author']); ?></span>
                </div>
                <span class="px-3 text-black-50">/</span>
                <div class="d-flex align-items-center">
                  <i class="bi bi-folder2"></i>
                  <span class="ps-2"><?php echo escape($bp['category']); ?></span>
                </div>
              </div>
              <hr>
              <a href="<?php echo escape(site_url('blog-post.php?id='.$bp['id'])); ?>" class="readmore stretched-link">
                <span><?php echo escape(t('Read More', 'Lire la Suite')); ?></span><i class="bi bi-arrow-right"></i>
              </a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    <div class="text-center mt-5" data-aos="fade-up">
      <a href="<?php echo escape(site_url('blog.php')); ?>" class="btn-get-started"
         style="display:inline-flex;align-items:center;gap:10px;">
        <i class="bi bi-journal-richtext"></i>
        <?php echo escape(t('View All Posts', 'Voir Tous les Articles')); ?>
      </a>
    </div>
  </div>
</section>
<?php endif; ?>

<script>
/* ── Mouse-tilt for .tilt-3d elements ── */
(function() {
  var MAX_TILT = 14;
  document.querySelectorAll('.tilt-3d').forEach(function(el) {
    el.addEventListener('mouseenter', function() {
      el.classList.remove('tilt-returning');
    });
    el.addEventListener('mousemove', function(e) {
      var r  = el.getBoundingClientRect();
      var xPct = (e.clientX - r.left)  / r.width  - 0.5;
      var yPct = (e.clientY - r.top)   / r.height - 0.5;
      var rotX = -yPct * MAX_TILT;
      var rotY =  xPct * MAX_TILT;
      el.style.transform = 'perspective(1000px) rotateX(' + rotX + 'deg) rotateY(' + rotY + 'deg) translateZ(8px)';
    });
    el.addEventListener('mouseleave', function() {
      el.classList.add('tilt-returning');
      el.style.transform = 'perspective(1000px) rotateX(0deg) rotateY(0deg) translateZ(0)';
    });
  });
  /* Keep device-screen-3d's own base transform while also applying tilt */
  var screen3d = document.querySelector('.device-screen-3d.tilt-3d');
  if (screen3d) {
    screen3d.addEventListener('mousemove', function(e) {
      var r  = screen3d.getBoundingClientRect();
      var xPct = (e.clientX - r.left) / r.width  - 0.5;
      var yPct = (e.clientY - r.top)  / r.height - 0.5;
      var rX = -yPct * 8 + 4;
      var rY =  xPct * 8 - 14;
      screen3d.style.transform = 'rotateY(' + rY + 'deg) rotateX(' + rX + 'deg)';
    });
    screen3d.addEventListener('mouseleave', function() {
      screen3d.style.transform = 'rotateY(-18deg) rotateX(6deg)';
    });
  }
})();
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
