<?php
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'SmartFleet — GPS Fleet Management Platform';
$bodyClass = 'smartfleet-page';
$lang      = current_language();

define('APP_INIT', true);
include __DIR__ . '/includes/header.php';
?>

<style>
/* ══ SmartFleet Page ══════════════════════════════ */

/* Hero */
.sf-hero {
  min-height:100vh;display:flex;align-items:center;
  background:linear-gradient(135deg,#03060d 0%,#080f1a 55%,#140505 100%);
  position:relative;overflow:hidden;
}
.sf-hero-grid {
  position:absolute;inset:0;
  background-image:
    linear-gradient(rgba(230,0,0,.05) 1px,transparent 1px),
    linear-gradient(90deg,rgba(230,0,0,.05) 1px,transparent 1px);
  background-size:50px 50px;
  animation:grid-shift 20s linear infinite;
}
@keyframes grid-shift { from{background-position:0 0} to{background-position:50px 50px} }
.sf-hero-glow {
  position:absolute;width:600px;height:600px;border-radius:50%;
  background:radial-gradient(circle,rgba(230,0,0,.12) 0%,transparent 70%);
  top:-100px;right:-100px;animation:glow-pulse 4s ease-in-out infinite;
}
@keyframes glow-pulse { 0%,100%{opacity:.6;transform:scale(1)} 50%{opacity:1;transform:scale(1.1)} }

/* Module nav pills */
.sf-module-nav {
  display:flex;justify-content:center;flex-wrap:wrap;gap:12px;
  margin-bottom:56px;
}
.sf-module-pill {
  display:inline-flex;align-items:center;gap:9px;
  padding:12px 28px;border-radius:50px;font-size:.875rem;font-weight:700;
  text-decoration:none;border:1.5px solid #e9ecef;color:#374151;
  background:#fff;transition:all .25s;letter-spacing:.02em;
}
.sf-module-pill:hover,.sf-module-pill.active {
  background:#e60000;color:#fff;border-color:#e60000;
  box-shadow:0 6px 20px rgba(230,0,0,.3);transform:translateY(-2px);
}

/* Feature row */
.sf-feature-row { padding:96px 0;border-bottom:1px solid #f0f0f0; }
.sf-feature-row:last-of-type { border-bottom:none; }

/* Feature bullet card */
.sf-bullet {
  display:flex;align-items:flex-start;gap:14px;margin-bottom:16px;
  padding:16px 18px;border-radius:12px;
  background:#f8f9fb;border:1px solid #f0f0f0;
  transition:all .3s;
}
.sf-bullet:hover {
  background:#fff5f5;border-color:rgba(230,0,0,.2);
  transform:translateX(4px);
}
.sf-bullet-icon {
  width:40px;height:40px;border-radius:10px;flex-shrink:0;
  display:flex;align-items:center;justify-content:center;font-size:1.1rem;
}
.sf-bullet h6 { font-size:.9rem;font-weight:700;color:#1a202c;margin:0 0 3px; }
.sf-bullet p  { font-size:.82rem;color:#64748b;margin:0;line-height:1.5; }

/* Stat chip */
.sf-stat-chip {
  text-align:center;padding:28px 20px;
}
.sf-stat-val { font-size:2.4rem;font-weight:900;line-height:1;display:block;letter-spacing:-.04em; }
.sf-stat-lbl { font-size:.72rem;font-weight:700;color:rgba(255,255,255,.5);
  text-transform:uppercase;letter-spacing:.08em;margin-top:6px; }

/* Screenshot frame */
.sf-screen-wrap {
  position:relative;border-radius:16px;overflow:hidden;
  box-shadow:0 24px 80px rgba(0,0,0,.18);
  transition:transform .4s ease;
}
.sf-screen-wrap:hover { transform:perspective(1000px) rotateY(-3deg) rotateX(2deg) scale(1.02); }
.sf-screen-wrap::before {
  content:'';position:absolute;top:0;left:0;right:0;height:34px;
  background:linear-gradient(90deg,#e60000 0%,#ff4444 100%);z-index:1;
}
.sf-screen-wrap::after {
  content:'⬤  ⬤  ⬤';position:absolute;top:8px;left:14px;
  color:rgba(255,255,255,.6);font-size:.55rem;letter-spacing:6px;z-index:2;
}
.sf-screen-wrap img {
  display:block;width:100%;margin-top:34px;
  height:340px;object-fit:cover;object-position:center;
}

/* Section badge */
.sf-section-badge {
  display:inline-flex;align-items:center;gap:8px;
  background:rgba(230,0,0,.08);border:1px solid rgba(230,0,0,.18);
  border-radius:30px;padding:5px 16px;margin-bottom:16px;
}
.sf-section-badge span { font-size:.72rem;font-weight:700;color:#e60000;letter-spacing:.07em;text-transform:uppercase; }

/* ROI strip */
.sf-roi { background:linear-gradient(135deg,#0b0e1a,#111622);color:#fff;position:relative;overflow:hidden; }
.sf-roi::before {
  content:'';position:absolute;inset:0;
  background-image:radial-gradient(rgba(255,255,255,.03) 1px,transparent 1px);
  background-size:30px 30px;
}

/* ══ Camera System ══════════════════════════ */
.cam-grid {
  display:grid;grid-template-columns:1fr 1fr;gap:12px;
}
.cam-feed {
  background:#0d1117;border:1px solid rgba(34,197,94,.22);
  border-radius:10px;overflow:hidden;font-family:'Courier New',monospace;
  position:relative;transition:border-color .3s;
}
.cam-feed:hover { border-color:rgba(34,197,94,.55); }
.cam-header {
  background:#111620;padding:7px 12px;display:flex;align-items:center;
  justify-content:space-between;border-bottom:1px solid rgba(34,197,94,.15);
}
.cam-ch  { font-size:.65rem;font-weight:700;color:#22c55e;letter-spacing:.1em; }
.cam-status-live { font-size:.58rem;color:rgba(255,255,255,.35);display:flex;align-items:center;gap:5px; }
.cam-body {
  height:160px;
  background:linear-gradient(160deg,#0a0e14 0%,#111820 100%);
  display:flex;align-items:center;justify-content:center;
  position:relative;overflow:hidden;
}
.cam-body-night { background:linear-gradient(160deg,#08090d 0%,#0d1015 100%) !important; }
.cam-icon-wrap  { font-size:2.4rem;color:rgba(34,197,94,.18);z-index:1; }

/* Scan line */
.cam-scanline {
  position:absolute;top:-2px;left:0;right:0;height:2px;
  background:linear-gradient(90deg,transparent 0%,rgba(34,197,94,.45) 50%,transparent 100%);
  animation:scan-move 3s linear infinite;z-index:2;pointer-events:none;
}
@keyframes scan-move { 0%{top:-2px} 100%{top:162px} }

/* Face detection rectangle */
.cam-detect-box {
  position:absolute;width:66px;height:78px;
  border:2px solid #22c55e;border-radius:4px;
  top:28px;right:28px;z-index:3;
  box-shadow:0 0 8px rgba(34,197,94,.35);
  animation:detect-pulse 2s ease-in-out infinite;
}
@keyframes detect-pulse {
  0%,100%{box-shadow:0 0 6px rgba(34,197,94,.25)}
  50%    {box-shadow:0 0 16px rgba(34,197,94,.6)}
}
/* Corner marks on detect box */
.cam-detect-box::before,.cam-detect-box::after {
  content:'';position:absolute;width:10px;height:10px;border-color:#22c55e;border-style:solid;
}
.cam-detect-box::before { top:-2px;left:-2px;border-width:3px 0 0 3px; }
.cam-detect-box::after  { bottom:-2px;right:-2px;border-width:0 3px 3px 0; }

/* Road lines overlay */
.cam-road-lines {
  position:absolute;bottom:0;left:50%;transform:translateX(-50%);
  width:70%;height:100%;
  background:linear-gradient(to bottom,transparent 0%,rgba(255,255,255,.04) 50%,rgba(255,255,255,.09) 100%);
  clip-path:polygon(28% 0%,72% 0%,100% 100%,0% 100%);
  z-index:2;
}
.cam-road-dash {
  position:absolute;bottom:10px;left:50%;transform:translateX(-50%);
  width:3px;height:60%;z-index:3;
  background:repeating-linear-gradient(to bottom,rgba(255,220,0,.25) 0px,rgba(255,220,0,.25) 12px,transparent 12px,transparent 22px);
}

/* Bottom label badge */
.cam-label-badge {
  position:absolute;bottom:8px;left:8px;z-index:4;
  background:rgba(0,0,0,.72);border:1px solid rgba(255,255,255,.09);
  border-radius:4px;padding:3px 9px;font-size:.57rem;color:rgba(255,255,255,.55);
  display:flex;align-items:center;gap:5px;
}
/* Footer bar */
.cam-footer {
  background:#090c12;padding:6px 12px;
  display:flex;justify-content:space-between;align-items:center;
  border-top:1px solid rgba(34,197,94,.1);
}
.cam-ts    { font-size:.52rem;color:rgba(255,255,255,.28); }
.cam-coord { font-size:.52rem;color:rgba(34,197,94,.5); }
.cam-spd   { font-size:.58rem;font-weight:700;padding:2px 8px;border-radius:3px;
             background:rgba(34,197,94,.18);color:#22c55e; }
.cam-ok    { font-size:.58rem;color:#22c55e;display:flex;align-items:center;gap:4px; }

/* Alert overlay ribbon */
.cam-alert-ribbon {
  position:absolute;top:34px;left:0;right:0;z-index:5;
  background:rgba(220,30,30,.88);color:#fff;
  font-size:.6rem;font-weight:700;letter-spacing:.04em;
  padding:4px 12px;display:flex;align-items:center;gap:7px;
  animation:alert-blink 2s ease-in-out infinite;
}
@keyframes alert-blink { 0%,100%{opacity:1} 50%{opacity:.55} }

/* Phone-use overlay dot */
.cam-phone-badge {
  position:absolute;top:10px;left:10px;z-index:5;
  background:rgba(251,146,60,.9);color:#fff;
  font-size:.55rem;font-weight:700;border-radius:4px;padding:3px 8px;
  display:flex;align-items:center;gap:4px;
}

/* Feature cards on dark bg */
.cam-feat-card {
  background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);
  border-radius:14px;padding:20px 22px;margin-bottom:18px;transition:all .3s;
}
.cam-feat-card:hover {
  background:rgba(255,255,255,.07);border-color:rgba(255,255,255,.15);
  transform:translateX(4px);
}
.cam-tag {
  display:inline-flex;align-items:center;gap:5px;
  font-size:.68rem;font-weight:600;
  border-radius:20px;padding:3px 10px;margin:2px 2px 0 0;
}
</style>

<!-- ══════════════════════════════════════════
     HERO
══════════════════════════════════════════ -->
<section class="sf-hero">
  <div class="sf-hero-grid"></div>
  <div class="sf-hero-glow"></div>

  <div class="container position-relative" style="z-index:2;padding-top:120px;padding-bottom:80px;">
    <div class="row align-items-center gy-5">

      <!-- Left -->
      <div class="col-lg-6" data-aos="fade-right">
        <div style="display:inline-flex;align-items:center;gap:8px;
                    background:rgba(230,0,0,.15);border:1px solid rgba(230,0,0,.3);
                    border-radius:30px;padding:5px 16px;margin-bottom:22px;">
          <span style="width:7px;height:7px;background:#e60000;border-radius:50%;
                        box-shadow:0 0 0 3px rgba(230,0,0,.3);animation:pd 2s infinite;"></span>
          <span style="font-size:.78rem;font-weight:700;color:rgba(255,255,255,.8);letter-spacing:.07em;text-transform:uppercase;">
            <?php echo escape(get_translation('sf_platform_badge')); ?>
          </span>
        </div>
        <style>@keyframes pd{0%,100%{box-shadow:0 0 0 3px rgba(230,0,0,.3)}50%{box-shadow:0 0 0 7px rgba(230,0,0,.08)}}</style>

        <h1 style="font-size:clamp(2.8rem,5.5vw,4.2rem);font-weight:900;color:#fff;
                   line-height:1.05;letter-spacing:-.04em;margin-bottom:20px;">
          Smart<span style="color:#e60000;">Fleet</span>
        </h1>
        <p style="font-size:1.1rem;color:rgba(255,255,255,.65);line-height:1.8;
                  max-width:520px;margin-bottom:36px;">
          <?php echo escape(get_translation('sf_hero_sub')); ?>
        </p>

        <div style="display:flex;flex-wrap:wrap;gap:12px;margin-bottom:40px;">
          <a href="<?php echo escape(site_url('contact.php')); ?>"
             style="display:inline-flex;align-items:center;gap:10px;background:#e60000;color:#fff;
                    font-weight:700;font-size:.95rem;padding:14px 32px;border-radius:50px;
                    text-decoration:none;box-shadow:0 6px 24px rgba(230,0,0,.4);transition:all .25s;"
             onmouseover="this.style.background='#c40000';this.style.transform='translateY(-2px)'"
             onmouseout="this.style.background='#e60000';this.style.transform='none'">
            <i class="bi bi-calendar-check-fill"></i> <?php echo escape(get_translation('sf_book_demo')); ?>
          </a>
          <a href="#gps-tracking"
             style="display:inline-flex;align-items:center;gap:10px;
                    background:rgba(255,255,255,.07);border:1.5px solid rgba(255,255,255,.2);
                    color:#fff;font-weight:600;font-size:.95rem;padding:14px 32px;
                    border-radius:50px;text-decoration:none;transition:all .25s;"
             onmouseover="this.style.background='rgba(255,255,255,.15)'"
             onmouseout="this.style.background='rgba(255,255,255,.07)'">
            <i class="bi bi-arrow-down-circle" style="color:#e60000;"></i> <?php echo escape(get_translation('sf_explore_features')); ?>
          </a>
        </div>

        <!-- Trust row -->
        <div style="display:flex;flex-wrap:wrap;gap:20px;">
          <?php foreach ([t('GPS Tracking','Suivi GPS'),t('Fuel Monitoring','Surveillance Carburant'),t('Driver Scorecards','Fiches Conducteur'),t('Fleet Analytics','Analyses de Flotte')] as $t): ?>
            <div style="display:flex;align-items:center;gap:7px;font-size:.8rem;color:rgba(255,255,255,.5);">
              <i class="bi bi-check-circle-fill" style="color:#e60000;font-size:.7rem;"></i><?php echo $t; ?>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Right: floating stats card -->
      <div class="col-lg-6 d-none d-lg-block" data-aos="fade-left" data-aos-delay="150">
        <div style="background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.09);
                    border-radius:20px;padding:36px;backdrop-filter:blur(20px);">
          <div style="display:flex;align-items:center;gap:8px;margin-bottom:24px;">
            <span style="width:8px;height:8px;border-radius:50%;background:#22c55e;
                          box-shadow:0 0 0 4px rgba(34,197,94,.2);animation:pd 2s infinite;"></span>
            <span style="font-size:.75rem;color:rgba(255,255,255,.45);font-weight:600;letter-spacing:.07em;text-transform:uppercase;">
              <?php echo escape(t('Live Platform Preview', 'Aperçu de la Plateforme en Direct')); ?>
            </span>
          </div>
          <?php
          $kpis = [
            ['bi-geo-alt-fill','#e60000',          t('Vehicles Tracked','Véhicules Suivis'),  t('238 active','238 actifs')],
            ['bi-fuel-pump-fill','#f59e0b',        t('Fuel Saved Today','Carburant Économisé'),t('412 litres','412 litres')],
            ['bi-person-badge-fill','#22c55e',     t('Drivers On Route','Conducteurs en Route'),'184 / 238'],
            ['bi-exclamation-triangle-fill','#3b82f6',t('Active Alerts','Alertes Actives'),     t('2 speed','2 vitesse')],
            ['bi-graph-up-arrow','#8b5cf6',        t('Monthly Saving','Économie Mensuelle'),    'CFA 1.4M'],
          ];
          foreach ($kpis as [$ic,$col,$lbl,$val]):
          ?>
            <div style="display:flex;align-items:center;gap:14px;padding:11px 0;
                        border-bottom:1px solid rgba(255,255,255,.05);">
              <div style="width:36px;height:36px;border-radius:9px;flex-shrink:0;
                           background:<?php echo $col; ?>22;
                           display:flex;align-items:center;justify-content:center;">
                <i class="bi <?php echo $ic; ?>" style="color:<?php echo $col; ?>;font-size:1rem;"></i>
              </div>
              <div style="flex:1;">
                <div style="font-size:.7rem;color:rgba(255,255,255,.38);text-transform:uppercase;letter-spacing:.06em;"><?php echo $lbl; ?></div>
                <div style="font-size:.9rem;font-weight:700;color:#fff;"><?php echo $val; ?></div>
              </div>
            </div>
          <?php endforeach; ?>
          <a href="<?php echo escape(site_url('contact.php')); ?>"
             style="display:block;text-align:center;margin-top:20px;background:#e60000;
                    color:#fff;font-weight:700;font-size:.85rem;padding:12px;
                    border-radius:10px;text-decoration:none;transition:.2s;"
             onmouseover="this.style.background='#c40000'" onmouseout="this.style.background='#e60000'">
            <?php echo escape(t('Get This Dashboard for Your Fleet →', 'Obtenez ce Tableau de Bord pour Votre Flotte →')); ?>
          </a>
        </div>
      </div>

    </div>
  </div>

  <!-- Breadcrumb -->
  <div style="position:absolute;bottom:28px;left:0;right:0;z-index:2;">
    <div class="container">
      <nav class="breadcrumbs" style="justify-content:flex-start;">
        <ol>
          <li><a href="<?php echo escape(site_url('index.php')); ?>"><?php echo escape(get_translation('breadcrumb_home')); ?></a></li>
          <li class="current">SmartFleet</li>
        </ol>
      </nav>
    </div>
  </div>
</section>

<!-- ══════════════════════════════════════════
     MODULE OVERVIEW PILLS
══════════════════════════════════════════ -->
<section style="background:#fff;padding:56px 0 16px;">
  <div class="container">
    <p style="text-align:center;font-size:.78rem;font-weight:700;letter-spacing:.1em;
              text-transform:uppercase;color:#aaa;margin-bottom:20px;"><?php echo escape(get_translation('sf_modules_sub')); ?></p>
    <div class="sf-module-nav" data-aos="fade-up">
      <a href="#gps-tracking" class="sf-module-pill">
        <i class="bi bi-geo-alt-fill" style="color:#e60000;"></i> <?php echo escape(get_translation('sf_mod_gps')); ?>
      </a>
      <a href="#fuel-management" class="sf-module-pill">
        <i class="bi bi-fuel-pump-fill" style="color:#f59e0b;"></i> <?php echo escape(get_translation('sf_mod_fuel')); ?>
      </a>
      <a href="#fleet-management" class="sf-module-pill">
        <i class="bi bi-truck-front-fill" style="color:#3b82f6;"></i> <?php echo escape(get_translation('sf_mod_fleet')); ?>
      </a>
      <a href="#camera-system" class="sf-module-pill">
        <i class="bi bi-camera-video-fill" style="color:#22c55e;"></i> <?php echo escape(get_translation('sf_mod_camera')); ?>
      </a>
    </div>
  </div>
</section>

<!-- ══════════════════════════════════════════
     MODULE 1 — GPS TRACKING
══════════════════════════════════════════ -->
<section id="gps-tracking" class="sf-feature-row" style="background:#fff;">
  <div class="container">
    <div class="row align-items-center gy-5">

      <div class="col-lg-6" data-aos="fade-right">
        <div class="sf-section-badge">
          <i class="bi bi-geo-alt-fill" style="color:#e60000;"></i>
          <span><?php echo escape(get_translation('sf_gps_badge')); ?></span>
        </div>
        <h2 style="font-size:clamp(1.8rem,3vw,2.5rem);font-weight:900;color:#1a202c;
                   letter-spacing:-.03em;line-height:1.2;margin-bottom:14px;">
          <?php echo get_translation('sf_gps_title'); ?>
        </h2>
        <p style="color:#64748b;font-size:.975rem;line-height:1.8;margin-bottom:28px;">
          <?php echo escape(get_translation('sf_gps_sub')); ?>
        </p>

        <?php
        $gpsFeatures = [
          ['bi-geo-alt-fill','#e60000', t('Real-Time Positioning','Positionnement en Temps Réel'), t('Live location on an interactive map, updated every 30 seconds via 4G LTE.',                 'Localisation en direct sur une carte interactive, mise à jour toutes les 30 secondes via 4G LTE.')],
          ['bi-clock-history','#3b82f6',t('Trip History & Replay','Historique et Replay de Trajets'), t('Replay any journey in full — route, stops, speed, engine events.',                          'Revoyez chaque trajet en entier — itinéraire, arrêts, vitesse, événements moteur.')],
          ['bi-geo-fill','#f59e0b',     t('Geofencing Alerts','Alertes de Géorepérage'),            t('Draw custom zones. Get instant SMS when a vehicle enters or exits.',                        'Tracez des zones personnalisées. Recevez un SMS instantané quand un véhicule entre ou sort.')],
          ['bi-speedometer2','#22c55e', t('Speed & Harsh Driving','Vitesse et Conduite Brusque'),   t('Automatic alerts and driver scoring for speeding, hard braking, cornering.',                'Alertes automatiques et notation des conducteurs pour la vitesse, le freinage brusque et les virages.')],
          ['bi-power','#8b5cf6',        t('Remote Immobilisation','Immobilisation à Distance'),     t('Cut the engine from any browser or mobile app in under 60 seconds.',                        'Coupez le moteur depuis un navigateur ou une application mobile en moins de 60 secondes.')],
          ['bi-wifi-off','#06b6d4',     t('Offline Caching','Mise en Cache Hors Ligne'),            t('30-day onboard storage — all data syncs when signal returns.',                              'Stockage embarqué de 30 jours — toutes les données se synchronisent au retour du signal.')],
        ];
        foreach ($gpsFeatures as [$ic,$col,$t,$d]):
        ?>
          <div class="sf-bullet">
            <div class="sf-bullet-icon" style="background:<?php echo $col; ?>15;color:<?php echo $col; ?>;">
              <i class="bi <?php echo $ic; ?>"></i>
            </div>
            <div><h6><?php echo $t; ?></h6><p><?php echo $d; ?></p></div>
          </div>
        <?php endforeach; ?>
      </div>

      <div class="col-lg-6" data-aos="fade-left" data-aos-delay="100">
        <div class="sf-screen-wrap">
          <img src="<?php echo escape(site_url('assets/img/GPS Tracking _ Vehicle Tracking System Ireland Landing Page.jpg')); ?>"
               alt="GPS Tracking Dashboard">
        </div>
        <!-- Stats row -->
        <div class="row g-0 mt-4" style="background:linear-gradient(135deg,#0b0e1a,#111622);border-radius:14px;overflow:hidden;">
          <?php foreach ([['30 s',t('Refresh Rate','Actualisation')],['99.7%',t('Uptime SLA','Disponibilité SLA')],['< 60 s',t('Immobilise Time','Temps Immobilisation')]] as [$v,$l]): ?>
            <div class="col-4 sf-stat-chip" style="border-right:1px solid rgba(255,255,255,.07);">
              <span class="sf-stat-val" style="color:#e60000;"><?php echo $v; ?></span>
              <div class="sf-stat-lbl"><?php echo $l; ?></div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- ══════════════════════════════════════════
     MODULE 2 — FUEL MANAGEMENT
══════════════════════════════════════════ -->
<section id="fuel-management" class="sf-feature-row" style="background:#f8f9fb;">
  <div class="container">
    <div class="row align-items-center gy-5 flex-lg-row-reverse">

      <div class="col-lg-6" data-aos="fade-left">
        <div class="sf-section-badge">
          <i class="bi bi-fuel-pump-fill" style="color:#f59e0b;"></i>
          <span style="color:#f59e0b;"><?php echo escape(get_translation('sf_fuel_badge')); ?></span>
        </div>
        <h2 style="font-size:clamp(1.8rem,3vw,2.5rem);font-weight:900;color:#1a202c;
                   letter-spacing:-.03em;line-height:1.2;margin-bottom:14px;">
          <?php echo get_translation('sf_fuel_title'); ?>
        </h2>
        <p style="color:#64748b;font-size:.975rem;line-height:1.8;margin-bottom:28px;">
          <?php echo escape(get_translation('sf_fuel_sub')); ?>
        </p>

        <?php
        $fuelFeatures = [
          ['bi-fuel-pump-fill',   '#f59e0b',         t('Precision Level Sensors','Capteurs de Niveau de Précision'), t('±1% accuracy capacitive probe. Far more reliable than dashboard gauges.',                  'Sonde capacitive de précision ±1%. Bien plus fiable que les jauges du tableau de bord.')],
          ['bi-graph-down-arrow', '#e60000',         t('Consumption Analytics','Analyse de Consommation'),           t('Break down fuel use by vehicle, driver, route, and time of day.',                          'Décomposez la consommation par véhicule, conducteur, itinéraire et heure de la journée.')],
          ['bi-exclamation-triangle-fill','#e60000', t('Theft & Drain Alerts','Alertes de Vol et Siphonnage'),       t('Unexplained level drops trigger immediate SMS — theft caught in under 1 minute.',          'Les baisses de niveau inexpliquées déclenchent un SMS immédiat — vol détecté en moins d\'une minute.')],
          ['bi-receipt',          '#22c55e',         t('Automated Fuel Reports','Rapports Carburant Automatisés'),   t('Weekly PDF reports with trends, refuels, and period-on-period comparison.',                'Rapports PDF hebdomadaires avec tendances, ravitaillements et comparaison période sur période.')],
          ['bi-bar-chart-line-fill','#3b82f6',       t('Driver Comparison','Comparaison des Conducteurs'),           t('Rank drivers by fuel efficiency. Reward the best, coach the rest.',                        'Classez les conducteurs par efficacité énergétique. Récompensez les meilleurs, formez les autres.')],
          ['bi-link-45deg',       '#8b5cf6',         t('GPS Route Correlation','Corrélation Itinéraire GPS'),        t('See how road type, terrain, and idle time affect consumption per trip.',                   'Voyez comment le type de route, le terrain et le ralenti affectent la consommation par trajet.')],
        ];
        foreach ($fuelFeatures as [$ic,$col,$t,$d]):
        ?>
          <div class="sf-bullet">
            <div class="sf-bullet-icon" style="background:<?php echo $col; ?>15;color:<?php echo $col; ?>;">
              <i class="bi <?php echo $ic; ?>"></i>
            </div>
            <div><h6><?php echo $t; ?></h6><p><?php echo $d; ?></p></div>
          </div>
        <?php endforeach; ?>
      </div>

      <div class="col-lg-6" data-aos="fade-right" data-aos-delay="100">
        <div class="sf-screen-wrap">
          <img src="<?php echo escape(site_url('assets/img/How IoT technology helps transportation and logistics industry.jpg')); ?>"
               alt="Fuel Management">
        </div>
        <div class="row g-0 mt-4" style="background:linear-gradient(135deg,#0b0e1a,#111622);border-radius:14px;overflow:hidden;">
          <?php foreach ([['30%',t('Avg. Fuel Saving','Économie Carburant Moy.')],['±1%',t('Sensor Accuracy','Précision Capteur')],['< 1 min',t('Theft Alert','Alerte Vol')]] as [$v,$l]): ?>
            <div class="col-4 sf-stat-chip" style="border-right:1px solid rgba(255,255,255,.07);">
              <span class="sf-stat-val" style="color:#f59e0b;"><?php echo $v; ?></span>
              <div class="sf-stat-lbl"><?php echo $l; ?></div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- ══════════════════════════════════════════
     MODULE 3 — FLEET MANAGEMENT
══════════════════════════════════════════ -->
<section id="fleet-management" class="sf-feature-row" style="background:#fff;">
  <div class="container">
    <div class="row align-items-center gy-5">

      <div class="col-lg-6" data-aos="fade-right">
        <div class="sf-section-badge">
          <i class="bi bi-truck-front-fill" style="color:#3b82f6;"></i>
          <span style="color:#3b82f6;"><?php echo escape(get_translation('sf_fleet_badge')); ?></span>
        </div>
        <h2 style="font-size:clamp(1.8rem,3vw,2.5rem);font-weight:900;color:#1a202c;
                   letter-spacing:-.03em;line-height:1.2;margin-bottom:14px;">
          <?php echo get_translation('sf_fleet_title'); ?>
        </h2>
        <p style="color:#64748b;font-size:.975rem;line-height:1.8;margin-bottom:28px;">
          <?php echo escape(get_translation('sf_fleet_sub')); ?>
        </p>

        <?php
        $fleetFeatures = [
          ['bi-grid-1x2-fill',       '#3b82f6', t('Fleet Overview Dashboard','Tableau de Bord Flotte'), t('One screen. All vehicles, drivers, alerts, and KPIs — desktop or mobile.',              'Un seul écran. Tous les véhicules, conducteurs, alertes et KPIs — bureau ou mobile.')],
          ['bi-wrench-adjustable',   '#f59e0b', t('Maintenance Scheduling','Planification Maintenance'),t('Service reminders by mileage or calendar. Never miss an inspection again.',             'Rappels d\'entretien par kilométrage ou calendrier. Ne manquez plus jamais une inspection.')],
          ['bi-person-lines-fill',   '#22c55e', t('Driver Scorecards','Fiches de Score Conducteur'),   t('Weekly per-driver reports on speeding, idling, seatbelt, and efficiency.',              'Rapports hebdomadaires par conducteur sur la vitesse, le ralenti, la ceinture et l\'efficacité.')],
          ['bi-calendar-check-fill', '#8b5cf6', t('Job Dispatch & Routing','Dispatch et Routage'),     t('Assign jobs to nearest vehicle. Track completion status in real time.',                 'Attribuez les missions au véhicule le plus proche. Suivez l\'avancement en temps réel.')],
          ['bi-cloud-download-fill', '#06b6d4', t('Automated Reporting','Rapports Automatisés'),       t("Scheduled reports delivered to management's inbox — PDF or CSV.",                       'Rapports planifiés livrés dans la boîte mail de la direction — PDF ou CSV.')],
          ['bi-bar-chart-fill',      '#e60000', t('Cost Analytics','Analyse des Coûts'),               t('Per-vehicle cost breakdown: fuel, maintenance, mileage, driver hours.',                 'Ventilation des coûts par véhicule : carburant, maintenance, kilométrage, heures conducteur.')],
        ];
        foreach ($fleetFeatures as [$ic,$col,$t,$d]):
        ?>
          <div class="sf-bullet">
            <div class="sf-bullet-icon" style="background:<?php echo $col; ?>15;color:<?php echo $col; ?>;">
              <i class="bi <?php echo $ic; ?>"></i>
            </div>
            <div><h6><?php echo $t; ?></h6><p><?php echo $d; ?></p></div>
          </div>
        <?php endforeach; ?>
      </div>

      <div class="col-lg-6" data-aos="fade-left" data-aos-delay="100">
        <div class="sf-screen-wrap">
          <img src="<?php echo escape(site_url('assets/img/blue.jpg')); ?>"
               alt="Fleet Management Dashboard">
        </div>
        <div class="row g-0 mt-4" style="background:linear-gradient(135deg,#0b0e1a,#111622);border-radius:14px;overflow:hidden;">
          <?php foreach ([['25%',t('Cost Reduction','Réduction Coûts')],['40%',t('Less Admin','Moins d\'Admin.')],['90 days',t('ROI Achieved','ROI Atteint')]] as [$v,$l]): ?>
            <div class="col-4 sf-stat-chip" style="border-right:1px solid rgba(255,255,255,.07);">
              <span class="sf-stat-val" style="color:#3b82f6;"><?php echo $v; ?></span>
              <div class="sf-stat-lbl"><?php echo $l; ?></div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- ══════════════════════════════════════════
     MODULE 4 — SMART CAMERA SYSTEM
══════════════════════════════════════════ -->
<section id="camera-system" style="background:#080b12;padding:96px 0;">
  <div class="container">

    <!-- Section header -->
    <div class="row justify-content-center mb-5" data-aos="fade-up">
      <div class="col-lg-8 text-center">
        <div class="sf-section-badge" style="background:rgba(34,197,94,.1);border-color:rgba(34,197,94,.22);">
          <i class="bi bi-camera-video-fill" style="color:#22c55e;"></i>
          <span style="color:#22c55e;"><?php echo escape(get_translation('sf_cam_badge')); ?></span>
        </div>
        <h2 style="font-size:clamp(1.8rem,3vw,2.5rem);font-weight:900;color:#fff;
                   letter-spacing:-.03em;line-height:1.2;margin:16px 0 14px;">
          <?php echo get_translation('sf_cam_title'); ?>
        </h2>
        <p style="color:rgba(255,255,255,.5);font-size:.97rem;line-height:1.85;max-width:580px;margin:0 auto;">
          <?php echo escape(get_translation('sf_cam_sub')); ?>
        </p>
      </div>
    </div>

    <div class="row align-items-start gy-5">

      <!-- ── Camera feed mockup grid ──────────────────────── -->
      <div class="col-lg-7" data-aos="fade-right">

        <!-- Top bar mimicking a DVR header -->
        <div style="background:#0d1117;border:1px solid rgba(34,197,94,.2);border-bottom:none;
                    border-radius:12px 12px 0 0;padding:10px 16px;
                    display:flex;align-items:center;justify-content:space-between;">
          <div style="display:flex;align-items:center;gap:10px;">
            <i class="bi bi-camera-video-fill" style="color:#22c55e;font-size:.9rem;"></i>
            <span style="font-size:.7rem;font-weight:700;color:#22c55e;letter-spacing:.08em;font-family:monospace;">SMARTRACK DVR · 4CH LIVE VIEW</span>
          </div>
          <div style="display:flex;align-items:center;gap:6px;">
            <span style="width:7px;height:7px;border-radius:50%;background:#22c55e;
                          box-shadow:0 0 0 3px rgba(34,197,94,.25);animation:pd 2s infinite;"></span>
            <span style="font-size:.62rem;color:rgba(255,255,255,.35);font-family:monospace;">REC</span>
          </div>
        </div>

        <div class="cam-grid" style="border:1px solid rgba(34,197,94,.2);border-radius:0 0 12px 12px;overflow:hidden;">

          <!-- CH01 — Driver DMS -->
          <div class="cam-feed">
            <div class="cam-header">
              <span class="cam-ch">CH01 · <?php echo escape(t('DRIVER','CONDUCTEUR')); ?></span>
              <span class="cam-status-live"><i class="bi bi-circle-fill" style="color:#22c55e;font-size:.38rem;"></i> LIVE</span>
            </div>
            <div class="cam-body">
              <div class="cam-scanline"></div>
              <div class="cam-icon-wrap"><i class="bi bi-person-fill"></i></div>
              <!-- Face detection box -->
              <div class="cam-detect-box"></div>
              <!-- Eye tracking dots -->
              <div style="position:absolute;top:44px;right:47px;z-index:4;display:flex;gap:8px;">
                <div style="width:5px;height:3px;background:#22c55e;border-radius:3px;opacity:.7;animation:blink-l 4s ease-in-out infinite;"></div>
                <div style="width:5px;height:3px;background:#22c55e;border-radius:3px;opacity:.7;animation:blink-r 4s ease-in-out 0.5s infinite;"></div>
              </div>
              <div class="cam-label-badge"><i class="bi bi-cpu-fill" style="color:#22c55e;"></i> <?php echo escape(t('AI Face Track','Suivi Facial IA')); ?></div>
            </div>
            <div class="cam-alert-ribbon"><i class="bi bi-exclamation-triangle-fill"></i> <?php echo escape(t('FATIGUE DETECTED — ALERT SENT','FATIGUE DÉTECTÉE — ALERTE ENVOYÉE')); ?></div>
            <div class="cam-footer">
              <span class="cam-ts">2025-06-09 07:12:59 UTC+01</span>
              <span class="cam-coord">N:3.7914 E:11.4859</span>
            </div>
          </div>

          <!-- CH02 — Front road -->
          <div class="cam-feed">
            <div class="cam-header">
              <span class="cam-ch">CH02 · <?php echo escape(t('FRONT','AVANT')); ?></span>
              <span class="cam-status-live"><i class="bi bi-circle-fill" style="color:#22c55e;font-size:.38rem;"></i> LIVE</span>
            </div>
            <div class="cam-body">
              <div class="cam-scanline"></div>
              <!-- Road perspective lines -->
              <div class="cam-road-lines"></div>
              <div class="cam-road-dash"></div>
              <div class="cam-icon-wrap" style="color:rgba(59,130,246,.2);"><i class="bi bi-signpost-2-fill"></i></div>
              <!-- lane markers -->
              <div style="position:absolute;bottom:25px;left:22%;width:2px;height:30px;
                           background:rgba(255,255,255,.18);z-index:3;transform:skewX(-15deg);"></div>
              <div style="position:absolute;bottom:25px;right:22%;width:2px;height:30px;
                           background:rgba(255,255,255,.18);z-index:3;transform:skewX(15deg);"></div>
              <div class="cam-label-badge"><i class="bi bi-arrow-up-circle-fill" style="color:#3b82f6;"></i> <?php echo escape(t('Road View','Vue Route')); ?></div>
            </div>
            <div class="cam-footer">
              <span class="cam-ts">2025-06-09 07:12:59 UTC+01</span>
              <span class="cam-spd">046 KM/H</span>
            </div>
          </div>

          <!-- CH03 — Rear -->
          <div class="cam-feed">
            <div class="cam-header">
              <span class="cam-ch">CH03 · <?php echo escape(t('REAR','ARRIÈRE')); ?></span>
              <span class="cam-status-live"><i class="bi bi-circle-fill" style="color:#22c55e;font-size:.38rem;"></i> LIVE</span>
            </div>
            <div class="cam-body cam-body-night">
              <div class="cam-scanline"></div>
              <div class="cam-icon-wrap" style="color:rgba(251,146,60,.2);"><i class="bi bi-arrow-down-circle-fill"></i></div>
              <!-- Night-vision tint -->
              <div style="position:absolute;inset:0;background:rgba(0,20,0,.25);z-index:1;"></div>
              <!-- IR indicator -->
              <div style="position:absolute;top:8px;right:8px;z-index:5;
                           display:flex;align-items:center;gap:4px;
                           background:rgba(0,0,0,.6);border-radius:4px;padding:2px 7px;">
                <span style="width:5px;height:5px;border-radius:50%;background:#8b5cf6;opacity:.8;"></span>
                <span style="font-size:.52rem;color:rgba(255,255,255,.4);font-family:monospace;">IR ON</span>
              </div>
              <div class="cam-label-badge"><i class="bi bi-arrow-down-circle" style="color:#f59e0b;"></i> <?php echo escape(t('Rear View · Night','Vue Arrière · Nuit')); ?></div>
            </div>
            <div class="cam-footer">
              <span class="cam-ts">2025-06-09 21:49:50 UTC+01</span>
              <span class="cam-coord">N:4.0091 E:12.9906</span>
            </div>
          </div>

          <!-- CH04 — Cargo -->
          <div class="cam-feed">
            <div class="cam-header">
              <span class="cam-ch">CH04 · <?php echo escape(t('CARGO','CARGO')); ?></span>
              <span class="cam-status-live"><i class="bi bi-circle-fill" style="color:#22c55e;font-size:.38rem;"></i> LIVE</span>
            </div>
            <div class="cam-body">
              <div class="cam-scanline"></div>
              <div class="cam-icon-wrap" style="color:rgba(139,92,246,.2);"><i class="bi bi-box-fill"></i></div>
              <!-- Cargo area lines -->
              <div style="position:absolute;inset:20px;border:1px dashed rgba(139,92,246,.18);border-radius:4px;z-index:2;"></div>
              <div style="position:absolute;bottom:30px;left:50%;transform:translateX(-50%);
                           width:55%;height:30%;background:rgba(139,92,246,.07);border:1px solid rgba(139,92,246,.15);
                           border-radius:4px;z-index:3;display:flex;align-items:center;justify-content:center;">
                <span style="font-size:.52rem;color:rgba(139,92,246,.5);font-family:monospace;"><?php echo escape(t('LOAD ZONE','ZONE DE CHARGE')); ?></span>
              </div>
              <div class="cam-label-badge"><i class="bi bi-shield-fill-check" style="color:#8b5cf6;"></i> <?php echo escape(t('Cargo Monitor','Surveillance Cargo')); ?></div>
            </div>
            <div class="cam-footer">
              <span class="cam-ts">2025-06-09 07:12:59 UTC+01</span>
              <span class="cam-ok"><i class="bi bi-check-circle-fill"></i> <?php echo escape(t('Cargo Secure','Cargo Sécurisé')); ?></span>
            </div>
          </div>

        </div><!-- /cam-grid -->

        <style>
        @keyframes blink-l { 0%,45%,55%,100%{height:3px;opacity:.7} 50%{height:1px;opacity:.2} }
        @keyframes blink-r { 0%,45%,55%,100%{height:3px;opacity:.7} 50%{height:1px;opacity:.2} }
        </style>

        <!-- IMEI / encryption strip -->
        <div style="margin-top:12px;background:#0a0d14;border:1px solid rgba(34,197,94,.12);
                    border-radius:8px;padding:9px 16px;
                    display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;">
          <span style="font-family:monospace;font-size:.65rem;color:rgba(255,255,255,.22);">
            IMEI: 865478070000783 &nbsp;·&nbsp; 4G LTE Connected
          </span>
          <span style="font-size:.65rem;color:#22c55e;font-weight:700;display:flex;align-items:center;gap:5px;">
            <i class="bi bi-shield-lock-fill"></i> <?php echo escape(t('All channels AES-256 encrypted','Tous les canaux chiffrés AES-256')); ?>
          </span>
        </div>

        <!-- Alert stats -->
        <div class="row g-3 mt-2">
          <?php foreach ([
            ['bi-bell-fill','#e60000','< 3 sec',t('Alert Delivery Time','Délai de Livraison d\'Alerte')],
            ['bi-eye-fill','#22c55e','24 / 7',t('Live Monitoring','Surveillance en Direct')],
            ['bi-cloud-upload-fill','#3b82f6',t('Cloud','Cloud'),t('30-Day Storage','Stockage 30 Jours')],
          ] as [$ic,$col,$v,$l]): ?>
            <div class="col-4">
              <div style="background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.07);
                           border-radius:10px;padding:14px;text-align:center;">
                <i class="bi <?php echo $ic; ?>" style="color:<?php echo $col; ?>;font-size:1.1rem;display:block;margin-bottom:6px;"></i>
                <div style="font-size:1.1rem;font-weight:900;color:#fff;letter-spacing:-.02em;"><?php echo $v; ?></div>
                <div style="font-size:.62rem;color:rgba(255,255,255,.35);text-transform:uppercase;letter-spacing:.07em;margin-top:3px;"><?php echo $l; ?></div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>

      </div><!-- /col camera grid -->

      <!-- ── Feature descriptions ────────────────────────── -->
      <div class="col-lg-5" data-aos="fade-left" data-aos-delay="120">

        <?php
        $camFeatures = [
          [
            'bi-person-bounding-box','#22c55e',
            t('Driver Monitoring System (DMS)','Système de Surveillance du Conducteur (DMS)'),
            t('An AI camera pointed at the driver analyses eye movement, head position, and behaviour in real time. The moment fatigue, distraction, phone use, or yawning is detected, the system triggers an audible cabin alarm and sends an immediate SMS alert to the fleet manager — all within 3 seconds.',
              "Une caméra IA pointée sur le conducteur analyse les mouvements oculaires, la position de la tête et le comportement en temps réel. Dès qu'une fatigue, distraction, utilisation du téléphone ou bâillement est détecté, le système déclenche une alarme sonore en cabine et envoie une alerte SMS immédiate au gestionnaire de flotte — le tout en moins de 3 secondes."),
            '#22c55e',
            [t('Fatigue & drowsiness detection','Détection de fatigue et somnolence'),t('Distraction & eyes-off-road alert','Alerte distraction et yeux hors route'),t('Phone use while driving','Utilisation du téléphone au volant'),t('Seatbelt compliance monitoring','Surveillance du port de la ceinture')],
          ],
          [
            'bi-camera-fill','#3b82f6',
            t('Front-Facing Road Camera','Caméra Route Frontale'),
            t('A wide-angle HD camera captures everything ahead of the truck, GPS-stamped every second. Footage is invaluable for insurance claims, accident investigation, and driver coaching sessions.',
              "Une caméra HD grand-angle capture tout ce qui se trouve devant le camion, horodatée GPS chaque seconde. Les enregistrements sont précieux pour les déclarations d'assurance, les enquêtes d'accident et les sessions de coaching des conducteurs."),
            '#3b82f6',
            [t('GPS-stamped dashcam footage','Enregistrements dashcam horodatés GPS'),t('Lane departure evidence','Preuve de sortie de voie'),t('Hazard & incident recording','Enregistrement des dangers et incidents'),t('Collision liability protection','Protection responsabilité collision')],
          ],
          [
            'bi-arrow-down-circle-fill','#f59e0b',
            t('Rear-View Camera','Caméra de Recul'),
            t('Night-vision IR camera covers the full blind spot behind the truck during road travel and reversing. Detects tailgaters, records reversing accidents, and monitors loading bay activity.',
              "Une caméra IR à vision nocturne couvre tout l'angle mort derrière le camion en circulation et en marche arrière. Détecte les talonneurs, enregistre les accidents de recul et surveille l'activité du quai de chargement."),
            '#f59e0b',
            [t('Night-vision IR capability','Vision nocturne infrarouge'),t('Reversing assistance & safety','Aide et sécurité au recul'),t('Tailgating detection','Détection de talonnage'),t('Loading bay oversight','Surveillance du quai de chargement')],
          ],
          [
            'bi-box-seam-fill','#8b5cf6',
            t('Cargo Bay Monitor','Surveillance de la Soute'),
            t('Mounted inside the cargo compartment, this camera lets dispatchers remotely verify goods are loaded correctly, detect tampering or theft in transit, and confirm the condition of goods at delivery — from the dashboard, anywhere.',
              "Montée dans le compartiment de chargement, cette caméra permet aux répartiteurs de vérifier à distance que les marchandises sont correctement chargées, de détecter une altération ou un vol en transit, et de confirmer l'état des biens à la livraison — depuis le tableau de bord, partout."),
            '#8b5cf6',
            [t('Theft & tampering alerts','Alertes de vol et d\'altération'),t('Load integrity verification','Vérification de l\'intégrité du chargement'),t('Delivery condition evidence','Preuve de l\'état à la livraison'),t('Remote live cargo view','Vue cargo en direct à distance')],
          ],
        ];
        foreach ($camFeatures as [$ic,$col,$title,$desc,$tagCol,$tags]):
        ?>
          <div class="cam-feat-card">
            <div style="display:flex;align-items:flex-start;gap:13px;margin-bottom:10px;">
              <div style="width:42px;height:42px;border-radius:10px;flex-shrink:0;
                           background:<?php echo $col; ?>20;color:<?php echo $col; ?>;
                           display:flex;align-items:center;justify-content:center;font-size:1.1rem;">
                <i class="bi <?php echo $ic; ?>"></i>
              </div>
              <h6 style="color:#fff;font-size:.92rem;font-weight:700;margin:0;line-height:1.35;padding-top:2px;">
                <?php echo $title; ?>
              </h6>
            </div>
            <p style="font-size:.83rem;color:rgba(255,255,255,.48);margin:0 0 12px;line-height:1.7;">
              <?php echo $desc; ?>
            </p>
            <div>
              <?php foreach ($tags as $tag): ?>
                <span class="cam-tag" style="color:<?php echo $col; ?>;background:<?php echo $col; ?>15;border:1px solid <?php echo $col; ?>28;">
                  <i class="bi bi-check2"></i> <?php echo $tag; ?>
                </span>
              <?php endforeach; ?>
            </div>
          </div>
        <?php endforeach; ?>

        <!-- Alert notification card -->
        <div style="background:rgba(34,197,94,.07);border:1px solid rgba(34,197,94,.2);
                    border-radius:12px;padding:18px 20px;margin-top:4px;">
          <div style="display:flex;align-items:flex-start;gap:12px;">
            <div style="width:38px;height:38px;border-radius:9px;flex-shrink:0;
                         background:rgba(34,197,94,.18);color:#22c55e;
                         display:flex;align-items:center;justify-content:center;font-size:1rem;">
              <i class="bi bi-bell-fill"></i>
            </div>
            <div>
              <div style="font-size:.88rem;font-weight:700;color:#fff;margin-bottom:5px;">
                <?php echo escape(t('Instant multi-channel alerts','Alertes multicanal instantanées')); ?>
              </div>
              <div style="font-size:.78rem;color:rgba(255,255,255,.42);line-height:1.65;">
                <?php echo t('Every safety event triggers a <strong style="color:rgba(255,255,255,.7);">cabin beep</strong> for the driver, a <strong style="color:rgba(255,255,255,.7);">dashboard notification</strong> and <strong style="color:rgba(255,255,255,.7);">SMS to the fleet manager</strong> — all within 3 seconds of detection.',
                  'Chaque événement de sécurité déclenche un <strong style="color:rgba(255,255,255,.7);">bip en cabine</strong> pour le conducteur, une <strong style="color:rgba(255,255,255,.7);">notification au tableau de bord</strong> et un <strong style="color:rgba(255,255,255,.7);">SMS au gestionnaire de flotte</strong> — le tout en moins de 3 secondes après détection.'); ?>
              </div>
            </div>
          </div>
        </div>

      </div><!-- /col features -->

    </div><!-- /row -->
  </div>
</section>

<!-- ══════════════════════════════════════════
     ROI NUMBERS STRIP
══════════════════════════════════════════ -->
<section class="sf-roi section">
  <div class="container position-relative" style="z-index:1;">
    <div class="row text-center" data-aos="fade-up">
      <div class="col-12 mb-5">
        <h2 style="font-size:clamp(1.8rem,3vw,2.4rem);font-weight:900;color:#fff;letter-spacing:-.03em;">
          <?php echo escape(get_translation('sf_roi_title')); ?>
        </h2>
        <p style="color:rgba(255,255,255,.5);font-size:.95rem;">
          <?php echo escape(get_translation('sf_roi_sub')); ?>
        </p>
      </div>
    </div>
    <div class="row g-0" data-aos="fade-up" data-aos-delay="100">
      <?php
      $roi = [
        ['30%', t('Fuel cost reduction','Réduction coût carburant'),        '#e60000',  'bi-fuel-pump-fill'],
        ['25%', t('Overall fleet cost cut','Baisse coût total flotte'),      '#f59e0b',  'bi-graph-down-arrow'],
        ['40%', t('Drop in fleet admin time','Baisse du temps admin.'),      '#22c55e',  'bi-clock-fill'],
        ['70%', t('Clients hit ROI in 90 days','Clients ROI en 90 jours'),   '#3b82f6',  'bi-graph-up-arrow'],
        ['98%', t('Platform uptime SLA','Disponibilité plateforme SLA'),     '#8b5cf6',  'bi-shield-fill-check'],
        ['24/7',t('Local support coverage','Couverture support local'),      '#06b6d4',  'bi-headset'],
      ];
      foreach ($roi as [$v,$l,$c,$ic]):
      ?>
        <div class="col-6 col-md-4 col-lg-2">
          <div style="text-align:center;padding:32px 12px;border-right:1px solid rgba(255,255,255,.06);
                      transition:background .2s;"
               onmouseover="this.style.background='rgba(255,255,255,.03)'"
               onmouseout="this.style.background='transparent'">
            <i class="bi <?php echo $ic; ?>" style="font-size:1.4rem;color:<?php echo $c; ?>;margin-bottom:10px;display:block;"></i>
            <div style="font-size:2rem;font-weight:900;color:<?php echo $c; ?>;line-height:1;letter-spacing:-.04em;">
              <?php echo $v; ?>
            </div>
            <div style="font-size:.72rem;font-weight:700;color:rgba(255,255,255,.4);text-transform:uppercase;
                        letter-spacing:.07em;margin-top:6px;"><?php echo $l; ?></div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ══════════════════════════════════════════
     HOW TO GET STARTED — 4 STEPS
══════════════════════════════════════════ -->
<section style="background:#fff;padding:96px 0;">
  <div class="container">
    <div class="section-title" data-aos="fade-up">
      <h2><?php echo escape(get_translation('sf_steps_title')); ?></h2>
      <p><?php echo escape(get_translation('sf_steps_sub')); ?></p>
    </div>
    <div class="row g-4 mt-2" data-aos="fade-up" data-aos-delay="100">
      <?php
      $steps = [
        ['01','bi-telephone-fill',    '#e60000',t('Free Consultation','Consultation Gratuite'),  t('We assess your fleet size, routes, and pain points. You get a custom proposal within 24 hours.',                  'Nous évaluons la taille de votre flotte, vos itinéraires et vos difficultés. Vous recevez une proposition personnalisée sous 24 heures.')],
        ['02','bi-tools',             '#f59e0b',t('Device Installation','Installation des Appareils'), t('Certified technicians fit trackers and sensors. 30 minutes per vehicle, zero downtime.',                       'Des techniciens certifiés posent traceurs et capteurs. 30 minutes par véhicule, sans interruption.')],
        ['03','bi-sliders2-vertical', '#3b82f6',t('Platform Setup','Configuration Plateforme'),    t('Your dashboard is configured with your vehicles, drivers, geofences, and alert rules before handover.',          'Votre tableau de bord est configuré avec vos véhicules, conducteurs, géozones et règles d\'alerte avant remise.')],
        ['04','bi-graph-up-arrow',    '#22c55e',t('Watch Costs Fall','Voyez les Coûts Chuter'),    t('Go live immediately. Most clients see measurable savings within the first week.',                                'Démarrez immédiatement. La plupart des clients constatent des économies mesurables dès la première semaine.')],
      ];
      foreach ($steps as [$num,$ic,$col,$t,$d]):
      ?>
        <div class="col-lg-3 col-md-6">
          <div style="text-align:center;padding:32px 20px;border-radius:18px;height:100%;
                      background:#f8f9fb;border:1px solid #eef0f2;transition:all .35s;"
               onmouseover="this.style.background='#fff';this.style.transform='translateY(-6px) perspective(900px) rotateX(3deg)';this.style.boxShadow='0 16px 50px rgba(0,0,0,.08)'"
               onmouseout="this.style.background='#f8f9fb';this.style.transform='none';this.style.boxShadow='none'">
            <div style="width:60px;height:60px;border-radius:50%;background:<?php echo $col; ?>;
                        color:#fff;font-size:1.2rem;font-weight:900;
                        display:flex;align-items:center;justify-content:center;
                        margin:0 auto 16px;box-shadow:0 6px 20px <?php echo $col; ?>44;
                        transition:transform .4s ease;"
                 onmouseover="this.style.transform='rotateY(360deg)'"
                 onmouseout="this.style.transform='none'">
              <?php echo $num; ?>
            </div>
            <i class="bi <?php echo $ic; ?>" style="font-size:1.8rem;color:<?php echo $col; ?>;margin-bottom:12px;display:block;"></i>
            <h5 style="font-size:.95rem;font-weight:800;color:#1a202c;margin-bottom:8px;"><?php echo $t; ?></h5>
            <p style="font-size:.855rem;color:#64748b;line-height:1.65;margin:0;"><?php echo $d; ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ══════════════════════════════════════════
     CTA
══════════════════════════════════════════ -->
<section style="background:linear-gradient(135deg,#c40000,#8b0000);padding:88px 0;
                position:relative;overflow:hidden;">
  <div style="position:absolute;top:-80px;right:-80px;width:320px;height:320px;border-radius:50%;background:rgba(255,255,255,.04);"></div>
  <div class="container text-center position-relative" data-aos="fade-up">
    <h2 style="font-size:clamp(1.8rem,3.5vw,2.6rem);font-weight:900;color:#fff;
               margin-bottom:14px;letter-spacing:-.03em;">
      <?php echo escape(get_translation('sf_cta_title')); ?>
    </h2>
    <p style="color:rgba(255,255,255,.75);font-size:1rem;max-width:520px;margin:0 auto 36px;line-height:1.8;">
      <?php echo escape(get_translation('sf_cta_sub')); ?>
    </p>
    <div style="display:flex;justify-content:center;flex-wrap:wrap;gap:14px;">
      <a href="<?php echo escape(site_url('contact.php')); ?>"
         style="display:inline-flex;align-items:center;gap:10px;background:#fff;color:#c40000;
                font-weight:800;font-size:1rem;padding:16px 40px;border-radius:50px;
                text-decoration:none;box-shadow:0 6px 24px rgba(0,0,0,.2);transition:all .3s;"
         onmouseover="this.style.transform='translateY(-3px)'"
         onmouseout="this.style.transform='none'">
        <i class="bi bi-calendar-check-fill"></i> <?php echo escape(get_translation('sf_book_demo')); ?>
      </a>
      <a href="<?php echo escape(site_url('devices.php')); ?>"
         style="display:inline-flex;align-items:center;gap:10px;
                background:rgba(255,255,255,.12);color:#fff;font-weight:700;font-size:.95rem;
                padding:16px 36px;border-radius:50px;text-decoration:none;
                border:1.5px solid rgba(255,255,255,.3);transition:all .25s;"
         onmouseover="this.style.background='rgba(255,255,255,.2)'"
         onmouseout="this.style.background='rgba(255,255,255,.12)'">
        <i class="bi bi-cpu-fill"></i> <?php echo escape(get_translation('sf_see_devices')); ?>
      </a>
    </div>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
