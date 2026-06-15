<?php
require_once __DIR__ . '/includes/functions.php';

$pageTitle = t('Our Devices — Smartrack','Nos Appareils — Smartrack');
$bodyClass = 'devices-page';
$lang      = current_language();

define('APP_INIT', true);
include __DIR__ . '/includes/header.php';

// ── Device catalog data ───────────────────────────────────────────────────────
$devices = [
  [
    'id'         => 'vehicle-tracker',
    'cat'        => 'Tracking',
    'cat_label'  => t('Tracking',    'Suivi'),
    'cat_color'  => '#e60000',
    'icon'       => 'bi-geo-alt-fill',
    'icon_bg'    => '#e60000',
    'name'       => t('Vehicle GPS Tracker',   'Traceur GPS de Véhicule'),
    'tagline'    => t('Always know where every vehicle is — live.', 'Sachez toujours où est chaque véhicule — en direct.'),
    'desc'       => t('A discreet hardwired GPS unit installed behind a panel. It sends live location every 30 seconds, logs every trip, and enables remote engine immobilisation directly from your dashboard.',
                      'Un boîtier GPS câblé installé discrètement derrière un panneau. Il envoie la position en direct toutes les 30 secondes, enregistre chaque trajet et permet la coupure à distance du moteur depuis votre tableau de bord.'),
    'specs'      => [t('4G LTE + GPS','4G LTE + GPS'), t('30 s refresh','Rafraîchissement 30 s'), t('IP67 waterproof','Étanche IP67')],
    'features'   => [
      ['bi-map-fill',         t('Live tracking on map',            'Suivi en direct sur carte')],
      ['bi-clock-history',    t('Full trip history & replay',      'Historique complet des trajets')],
      ['bi-geo-fill',         t('Geofencing with SMS alerts',      'Géozone avec alertes SMS')],
      ['bi-speedometer2',     t('Speed & harsh-driving alerts',    'Alertes vitesse et conduite brusque')],
      ['bi-power',            t('Remote engine immobilisation',    'Coupure moteur à distance')],
    ],
    'badge'      => t('Most Popular', 'Le Plus Populaire'),
    'badge_bg'   => '#e60000',
  ],
  [
    'id'         => 'hd-camera',
    'cat'        => 'Security',
    'cat_label'  => t('Security',    'Sécurité'),
    'cat_color'  => '#3b82f6',
    'icon'       => 'bi-camera-video-fill',
    'icon_bg'    => '#3b82f6',
    'name'       => t('HD Fleet Camera',       'Caméra HD de Flotte'),
    'tagline'    => t('See what your drivers see — and protect them.', 'Voyez ce que voient vos conducteurs — et protégez-les.'),
    'desc'       => t('Dual-lens 1080p camera covers the road ahead and the driver cabin simultaneously. Event-triggered clips upload to the cloud automatically and are tagged with GPS, speed, and timestamp.',
                      'Caméra double objectif 1080p couvrant simultanément la route et la cabine. Les clips déclenchés par événements se téléchargent automatiquement dans le cloud et sont étiquetés avec GPS, vitesse et horodatage.'),
    'specs'      => [t('1080p Full HD','1080p Full HD'), t('140° wide angle','Grand angle 140°'), t('Night vision','Vision nocturne')],
    'features'   => [
      ['bi-camera2',              t('Road & cabin recording',        'Enregistrement route et cabine')],
      ['bi-moon-fill',            t('IR night vision',               'Vision nocturne infrarouge')],
      ['bi-cloud-arrow-up-fill',  t('Auto cloud upload',             'Téléchargement auto cloud')],
      ['bi-play-btn-fill',        t('Live remote viewing',           'Visionnage en direct à distance')],
      ['bi-file-earmark-play',    t('Incident clip download',        "Téléchargement de clips d'incidents")],
    ],
    'badge'      => null,
    'badge_bg'   => null,
  ],
  [
    'id'         => 'smoke-detector',
    'cat'        => 'Safety',
    'cat_label'  => t('Safety',      'Sûreté'),
    'cat_color'  => '#f59e0b',
    'icon'       => 'bi-fire',
    'icon_bg'    => '#f59e0b',
    'name'       => t('Smoke & Heat Detector',  'Détecteur de Fumée et de Chaleur'),
    'tagline'    => t('Detect fire in seconds — before damage is done.', 'Détecter un incendie en secondes — avant que les dégâts ne surviennent.'),
    'desc'       => t('An addressable multi-criteria detector that identifies both smoke and rapid heat rise. It pinpoints the exact triggered zone and fires an instant SMS alert to facility managers, day or night.',
                      'Un détecteur multi-critères adressable qui identifie fumée et montée rapide de chaleur. Il localise précisément la zone déclenchée et envoie une alerte SMS instantanée aux gestionnaires, jour et nuit.'),
    'specs'      => [t('Multi-criteria','Multi-critères'), t('85 dB alarm','Alarme 85 dB'), t('10-year lifespan','Durée de vie 10 ans')],
    'features'   => [
      ['bi-fire',                 t('Smoke & heat detection',        'Détection fumée et chaleur')],
      ['bi-bell-fill',            t('Sounder + strobe alert',        'Alarme sonore + stroboscope')],
      ['bi-phone-fill',           t('Instant SMS notification',      'Notification SMS instantanée')],
      ['bi-check-circle-fill',    t('Monthly self-test routine',     'Autotest mensuel')],
      ['bi-file-earmark-text',    t('Compliance certificate',        'Certificat de conformité')],
    ],
    'badge'      => null,
    'badge_bg'   => null,
  ],
  [
    'id'         => 'driver-id-badge',
    'cat'        => 'Identity',
    'cat_label'  => t('Identity',    'Identité'),
    'cat_color'  => '#22c55e',
    'icon'       => 'bi-person-badge-fill',
    'icon_bg'    => '#22c55e',
    'name'       => t('Driver ID Badge',       'Badge ID Conducteur'),
    'tagline'    => t('Every journey linked to a named driver — automatically.', 'Chaque trajet lié à un conducteur nommé — automatiquement.'),
    'desc'       => t('An RFID card assigned to each driver. Before the engine can start, the driver taps their badge on the reader — linking every trip, speed event, and fuel consumption to a specific individual.',
                      "Une carte RFID assignée à chaque conducteur. Avant que le moteur ne démarre, le conducteur présente son badge sur le lecteur — liant chaque trajet, événement de vitesse et consommation de carburant à un individu spécifique."),
    'specs'      => [t('RFID 125 kHz','RFID 125 kHz'), t('Unique ID per driver','ID unique par conducteur'), t('Tap-to-start','Démarrage par badge')],
    'features'   => [
      ['bi-person-check-fill',    t('Per-driver trip attribution',   'Attribution des trajets par conducteur')],
      ['bi-bar-chart-line-fill',  t('Individual driver scorecards',  'Scorecards individuels des conducteurs')],
      ['bi-lock-fill',            t('Unauthorised start prevention', 'Prévention du démarrage non autorisé')],
      ['bi-people-fill',          t('Easy bulk enrolment',           'Inscription en masse facile')],
      ['bi-shield-fill-check',    t('Tamper alert on removal',       'Alerte en cas de retrait')],
    ],
    'badge'      => null,
    'badge_bg'   => null,
  ],
  [
    'id'         => 'fuel-sensor',
    'cat'        => 'Monitoring',
    'cat_label'  => t('Monitoring',  'Surveillance'),
    'cat_color'  => '#8b5cf6',
    'icon'       => 'bi-fuel-pump-fill',
    'icon_bg'    => '#8b5cf6',
    'name'       => t('Fuel Level Sensor',     'Capteur de Niveau de Carburant'),
    'tagline'    => t('Cut fuel costs by 30% — starting month one.', 'Réduisez les coûts de carburant de 30% — dès le premier mois.'),
    'desc'       => t('A precision capacitive probe that measures tank fuel level to ±1% accuracy. Any sudden unexplained drop triggers an immediate theft alert. Weekly reports show consumption per vehicle, per driver, per route.',
                      'Une sonde capacitive de précision qui mesure le niveau de carburant à ±1% de précision. Toute baisse soudaine inexpliquée déclenche une alerte de vol immédiate. Les rapports hebdomadaires montrent la consommation par véhicule, conducteur et itinéraire.'),
    'specs'      => [t('±1% accuracy','Précision ±1%'), t('0 – 500 L range','Plage 0 – 500 L'), t('Anti-siphon alert','Alerte anti-siphon')],
    'features'   => [
      ['bi-graph-down-arrow',         t('Real-time fuel level graph',   'Graphique en direct du niveau de carburant')],
      ['bi-exclamation-triangle-fill',t('Theft & drain alerts',         'Alertes de vol et de drainage')],
      ['bi-receipt',                  t('Automated weekly reports',     'Rapports hebdomadaires automatisés')],
      ['bi-person-fill',              t('Consumption per driver',       'Consommation par conducteur')],
      ['bi-link-45deg',               t('GPS route correlation',        'Corrélation avec les itinéraires GPS')],
    ],
    'badge'      => t('Best ROI', 'Meilleur ROI'),
    'badge_bg'   => '#8b5cf6',
  ],
  [
    'id'         => 'beacon-no-sim',
    'cat'        => 'Tracking',
    'cat_label'  => t('Tracking',    'Suivi'),
    'cat_color'  => '#06b6d4',
    'icon'       => 'bi-broadcast',
    'icon_bg'    => '#06b6d4',
    'name'       => t('Tracker Beacon',        'Balise Traceur'),
    'tagline'    => t('Track any asset — no SIM, no subscription.', "Suivez n'importe quel actif — sans SIM, sans abonnement."),
    'desc'       => t("A coin-cell BLE beacon that attaches to cargo, equipment, containers, or toolboxes. It broadcasts its identity to nearby gateway devices on your fleet, so location is reported without needing its own SIM card.",
                      "Une balise BLE à pile bouton qui s'attache à la cargaison, équipements, conteneurs ou boîtes à outils. Elle diffuse son identité aux passerelles voisines de votre flotte, permettant la localisation sans SIM."),
    'specs'      => [t('Bluetooth 5.0 BLE','Bluetooth 5.0 BLE'), t('2-year battery','Batterie 2 ans'), t('No SIM required','Sans SIM')],
    'features'   => [
      ['bi-bluetooth',        t('BLE broadcast, no SIM',         'Diffusion BLE, sans SIM')],
      ['bi-battery-full',     t('2-year battery life',           'Batterie 2 ans')],
      ['bi-box-seam-fill',    t("Attach to any asset",           "S'attache à tout actif")],
      ['bi-router-fill',      t('Gateway-synced location',       'Localisation synchronisée via passerelle')],
      ['bi-shield-exclamation',t('Tamper detection alert',       'Alerte de détection de manipulation')],
    ],
    'badge'      => t('No SIM', 'Sans SIM'),
    'badge_bg'   => '#06b6d4',
  ],
  [
    'id'         => 'badge-beacon',
    'cat'        => 'Identity',
    'cat_label'  => t('Identity',    'Identité'),
    'cat_color'  => '#f97316',
    'icon'       => 'bi-broadcast-pin',
    'icon_bg'    => '#f97316',
    'name'       => t('Badge Beacon Tracker',  'Traceur Balise Badge'),
    'tagline'    => t('Know exactly who is where — inside any site.', "Sachez exactement qui est où — à l'intérieur de tout site."),
    'desc'       => t('A wearable clip-on beacon carried by personnel. It combines RFID identity with real-time BLE indoor positioning — so you know which employee entered which zone and when, without a SIM card.',
                      'Une balise portable portée par le personnel. Elle combine identité RFID et positionnement intérieur BLE en temps réel — vous savez quel employé est entré dans quelle zone et quand, sans carte SIM.'),
    'specs'      => [t('BLE + RFID','BLE + RFID'), t('50 m indoor range','Portée intérieure 50 m'), t('Wearable clip','Clip portable')],
    'features'   => [
      ['bi-person-walking',   t('Real-time personnel location',  'Localisation en temps réel du personnel')],
      ['bi-building',         t('Indoor zone mapping',           'Cartographie des zones intérieures')],
      ['bi-shield-fill-check',t('Access log per person',         "Journal d'accès par personne")],
      ['bi-bell-fill',        t('Zone entry/exit alerts',        "Alertes d'entrée/sortie de zone")],
      ['bi-bluetooth',        t('No SIM — gateway linked',       'Sans SIM — lié aux passerelles')],
    ],
    'badge'      => null,
    'badge_bg'   => null,
  ],
];

// Category list for filter tabs — data-filter stays English for JS; label is translated
$cats = [
  ['All',       t('All','Tous')],
  ['Tracking',  t('Tracking','Suivi')],
  ['Security',  t('Security','Sécurité')],
  ['Safety',    t('Safety','Sûreté')],
  ['Monitoring',t('Monitoring','Surveillance')],
  ['Identity',  t('Identity','Identité')],
];
?>

<!-- Page Title -->
<div class="page-title dark-background"
     style="background-image:url(<?php echo escape(site_url('assets/img/page-title-bg.jpg')); ?>);">
  <div class="container position-relative">
    <h1><?php echo escape(get_translation('dev_page_title')); ?></h1>
    <p style="color:rgba(255,255,255,.75);margin-top:8px;">
      <?php echo escape(get_translation('dev_page_subtitle')); ?>
    </p>
    <nav class="breadcrumbs">
      <ol>
        <li><a href="<?php echo escape(site_url('index.php')); ?>"><?php echo escape(get_translation('breadcrumb_home')); ?></a></li>
        <li class="current"><?php echo escape(get_translation('breadcrumb_devices')); ?></li>
      </ol>
    </nav>
  </div>
</div>

<style>
/* ── Filter tabs ── */
.dev-filter-bar { display:flex;flex-wrap:wrap;gap:10px;justify-content:center;margin-bottom:48px; }
.dev-filter-btn {
  padding:8px 22px;border-radius:50px;font-size:.82rem;font-weight:700;
  letter-spacing:.04em;text-transform:uppercase;cursor:pointer;border:none;
  background:#f1f1f1;color:#555;transition:all .25s;
}
.dev-filter-btn:hover  { background:#e60000;color:#fff; }
.dev-filter-btn.active { background:#e60000;color:#fff;box-shadow:0 4px 16px rgba(230,0,0,.3); }

/* ── Device card ── */
.dev-card {
  background:#fff;border-radius:20px;overflow:hidden;height:100%;
  border:1px solid #f0f0f0;
  box-shadow:0 2px 20px rgba(0,0,0,.05);
  display:flex;flex-direction:column;
  transition:transform .35s ease, box-shadow .35s ease;
  transform-style:preserve-3d;
  perspective:1200px;
}
.dev-card:hover {
  transform:translateY(-8px) rotateX(2deg);
  box-shadow:0 20px 60px rgba(0,0,0,.12);
}
.dev-card-top {
  padding:32px 28px 20px;
  position:relative;
  overflow:hidden;
}
.dev-card-top::after {
  content:'';position:absolute;top:0;left:0;right:0;height:4px;
}
.dev-card-icon {
  width:68px;height:68px;border-radius:18px;
  display:flex;align-items:center;justify-content:center;
  font-size:1.8rem;margin-bottom:18px;
  transition:transform .4s ease;
  flex-shrink:0;
}
.dev-card:hover .dev-card-icon { transform:rotateY(360deg) scale(1.1); }

.dev-card-badge {
  position:absolute;top:18px;right:18px;
  font-size:.68rem;font-weight:800;letter-spacing:.07em;text-transform:uppercase;
  padding:4px 12px;border-radius:20px;color:#fff;
}
.dev-cat-pill {
  display:inline-block;font-size:.68rem;font-weight:700;letter-spacing:.06em;
  text-transform:uppercase;padding:3px 12px;border-radius:20px;margin-bottom:10px;
}
.dev-card h3 {
  font-size:1.1rem;font-weight:800;color:#1a202c;margin-bottom:6px;line-height:1.3;
}
.dev-card-tagline {
  font-size:.82rem;color:#64748b;font-style:italic;margin-bottom:12px;line-height:1.5;
}
.dev-card p {
  font-size:.875rem;color:#475569;line-height:1.75;margin-bottom:0;
}

/* ── Specs pills ── */
.dev-specs {
  display:flex;flex-wrap:wrap;gap:6px;padding:0 28px 16px;
}
.dev-spec-pill {
  font-size:.7rem;font-weight:700;padding:4px 12px;border-radius:20px;
  background:#f1f5f9;color:#475569;letter-spacing:.03em;
}

/* ── Features list ── */
.dev-features {
  padding:0 28px 16px;flex:1;
}
.dev-features li {
  display:flex;align-items:center;gap:9px;
  font-size:.84rem;color:#374151;padding:5px 0;
  border-bottom:1px solid #f8f9fb;
}
.dev-features li:last-child { border-bottom:none; }
.dev-features li i { font-size:.95rem;flex-shrink:0; }

/* ── Card footer ── */
.dev-card-foot {
  padding:18px 28px 26px;
  display:flex;gap:10px;margin-top:auto;
}
.dev-btn-primary {
  flex:1;display:inline-flex;align-items:center;justify-content:center;gap:8px;
  background:#e60000;color:#fff;font-weight:700;font-size:.82rem;
  padding:11px 16px;border-radius:10px;text-decoration:none;
  transition:all .25s;border:none;cursor:pointer;
}
.dev-btn-primary:hover { background:#c40000;transform:translateY(-1px); }
.dev-btn-secondary {
  display:inline-flex;align-items:center;justify-content:center;gap:8px;
  background:#f8f9fb;color:#374151;font-weight:700;font-size:.82rem;
  padding:11px 16px;border-radius:10px;text-decoration:none;
  transition:all .25s;border:1px solid #e9ecef;cursor:pointer;
}
.dev-btn-secondary:hover { background:#e9ecef; }

/* ── Ecosystem section ── */
.dev-ecosystem { background:linear-gradient(135deg,#0b0e1a 0%,#111622 100%);color:#fff; }
.eco-node {
  text-align:center;padding:28px 16px;
  transition:transform .3s;cursor:default;
}
.eco-node:hover { transform:translateY(-6px); }
.eco-icon-wrap {
  width:72px;height:72px;border-radius:50%;
  display:flex;align-items:center;justify-content:center;
  font-size:1.6rem;margin:0 auto 14px;
  position:relative;
}
.eco-icon-wrap::after {
  content:'';position:absolute;inset:-6px;border-radius:50%;
  border:2px dashed rgba(255,255,255,.15);
  animation:spin-slow 12s linear infinite;
}
@keyframes spin-slow { to { transform:rotate(360deg); } }

/* ── Specs table ── */
.dev-specs-table th { background:#f8f9fb;font-size:.78rem;font-weight:700;
  text-transform:uppercase;letter-spacing:.06em;color:#888;padding:14px 16px; }
.dev-specs-table td { font-size:.875rem;color:#374151;padding:14px 16px;vertical-align:middle; }
.dev-specs-table tr:hover td { background:#fff9f9; }

/* ── Hidden cards (filter) ── */
.dev-col { transition:opacity .3s, transform .3s; }
.dev-col.hidden { display:none; }
</style>

<!-- ══════════════════════════════════════════
     HERO INTRO STRIP
══════════════════════════════════════════ -->
<section style="background:#fff;padding:60px 0 20px;">
  <div class="container">
    <div class="row align-items-center gy-4">
      <div class="col-lg-7" data-aos="fade-right">
        <span style="display:inline-block;background:rgba(230,0,0,.08);color:#e60000;
                     font-size:.72rem;font-weight:700;padding:4px 14px;border-radius:20px;
                     letter-spacing:.06em;text-transform:uppercase;margin-bottom:14px;">
          <?php echo escape(get_translation('dev_badge')); ?>
        </span>
        <h2 style="font-size:clamp(1.8rem,3.5vw,2.6rem);font-weight:900;color:#1a202c;
                   line-height:1.2;letter-spacing:-.03em;margin-bottom:14px;">
          <?php echo escape(get_translation('dev_hero_title')); ?>
        </h2>
        <p style="font-size:1rem;color:#64748b;line-height:1.8;max-width:520px;">
          <?php echo escape(get_translation('dev_hero_desc')); ?>
        </p>
      </div>
      <div class="col-lg-5" data-aos="fade-left" data-aos-delay="100">
        <div class="row g-3">
          <?php
          $summary_stats = [
            ['7',     get_translation('dev_stat_devices'),   'bi-cpu-fill',          '#e60000'],
            ['1',     get_translation('dev_stat_dashboard'), 'bi-grid-1x2-fill',     '#3b82f6'],
            ['24/7',  get_translation('dev_stat_monitoring'),'bi-shield-fill-check', '#22c55e'],
            ['< 2 h', get_translation('dev_stat_install'),  'bi-tools',             '#f59e0b'],
          ];
          foreach ($summary_stats as [$v,$l,$ic,$c]):
          ?>
            <div class="col-6">
              <div style="background:#f8f9fb;border-radius:14px;padding:20px;text-align:center;
                          border:1px solid #eef0f2;transition:all .25s;"
                   onmouseover="this.style.transform='translateY(-4px) perspective(800px) rotateX(3deg)';this.style.boxShadow='0 8px 30px rgba(0,0,0,.08)'"
                   onmouseout="this.style.transform='none';this.style.boxShadow='none'">
                <i class="bi <?php echo $ic; ?>" style="font-size:1.4rem;color:<?php echo $c; ?>;margin-bottom:6px;display:block;"></i>
                <div style="font-size:1.6rem;font-weight:900;color:#1a202c;line-height:1;"><?php echo $v; ?></div>
                <div style="font-size:.72rem;font-weight:700;color:#888;text-transform:uppercase;
                            letter-spacing:.06em;margin-top:4px;"><?php echo escape($l); ?></div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ══════════════════════════════════════════
     DEVICE CATALOG
══════════════════════════════════════════ -->
<section style="background:#f8f9fb;padding:60px 0;">
  <div class="container">

    <!-- Filter bar -->
    <div class="dev-filter-bar" data-aos="fade-up">
      <?php foreach ($cats as [$filterVal, $filterLabel]): ?>
        <button class="dev-filter-btn <?php echo $filterVal === 'All' ? 'active' : ''; ?>"
                data-filter="<?php echo $filterVal; ?>">
          <?php echo escape($filterLabel); ?>
        </button>
      <?php endforeach; ?>
    </div>

    <!-- Device grid -->
    <div class="row g-4" id="deviceGrid">
      <?php foreach ($devices as $i => $d): ?>
        <div class="col-lg-4 col-md-6 dev-col"
             data-cat="<?php echo escape($d['cat']); ?>"
             data-aos="fade-up"
             data-aos-delay="<?php echo ($i % 3) * 100; ?>">

          <div class="dev-card" style="--accent:<?php echo $d['icon_bg']; ?>">
            <div class="dev-card-top">
              <!-- Accent top bar -->
              <div style="position:absolute;top:0;left:0;right:0;height:4px;
                          background:<?php echo $d['icon_bg']; ?>;"></div>

              <!-- Badge -->
              <?php if ($d['badge']): ?>
                <div class="dev-card-badge" style="background:<?php echo $d['badge_bg']; ?>;">
                  <?php echo escape($d['badge']); ?>
                </div>
              <?php endif; ?>

              <!-- Icon -->
              <div class="dev-card-icon"
                   style="background:<?php echo $d['icon_bg']; ?>18;color:<?php echo $d['icon_bg']; ?>;">
                <i class="bi <?php echo $d['icon']; ?>"></i>
              </div>

              <!-- Category pill -->
              <div class="dev-cat-pill"
                   style="background:<?php echo $d['cat_color']; ?>12;color:<?php echo $d['cat_color']; ?>;">
                <?php echo escape($d['cat_label']); ?>
              </div>

              <!-- Name & tagline -->
              <h3><?php echo escape($d['name']); ?></h3>
              <p class="dev-card-tagline"><?php echo escape($d['tagline']); ?></p>

              <!-- Description -->
              <p><?php echo escape($d['desc']); ?></p>
            </div>

            <!-- Specs pills -->
            <div class="dev-specs">
              <?php foreach ($d['specs'] as $spec): ?>
                <span class="dev-spec-pill"><?php echo escape($spec); ?></span>
              <?php endforeach; ?>
            </div>

            <!-- Features -->
            <ul class="dev-features list-unstyled">
              <?php foreach ($d['features'] as [$ficon, $flabel]): ?>
                <li>
                  <i class="bi <?php echo $ficon; ?>" style="color:<?php echo $d['icon_bg']; ?>;"></i>
                  <?php echo escape($flabel); ?>
                </li>
              <?php endforeach; ?>
            </ul>

            <!-- Footer buttons -->
            <div class="dev-card-foot">
              <a href="<?php echo escape(site_url('contact.php')); ?>" class="dev-btn-primary">
                <i class="bi bi-file-earmark-text-fill"></i> <?php echo escape(get_translation('dev_get_quote')); ?>
              </a>
              <a href="<?php echo escape(site_url('contact.php')); ?>" class="dev-btn-secondary"
                 title="<?php echo escape(t('Ask a question','Poser une question')); ?>">
                <i class="bi bi-chat-dots-fill"></i>
              </a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

  </div>
</section>

<!-- ══════════════════════════════════════════
     ECOSYSTEM — HOW THEY WORK TOGETHER
══════════════════════════════════════════ -->
<section class="dev-ecosystem section">
  <div class="container">
    <div class="section-title" data-aos="fade-up" style="color:#fff;">
      <h2 style="color:#fff;"><?php echo escape(get_translation('dev_eco_title')); ?></h2>
      <p style="color:rgba(255,255,255,.6);">
        <?php echo escape(get_translation('dev_eco_sub')); ?>
      </p>
    </div>

    <div class="row g-0 justify-content-center" data-aos="fade-up" data-aos-delay="100">
      <?php
      $eco = [
        ['bi-geo-alt-fill',      '#e60000', t('Vehicle Tracker','Traceur de Véhicule'),     t('Live location + trip log','Position en direct + journal de trajet')],
        ['bi-camera-video-fill', '#3b82f6', t('Camera','Caméra'),                           t('Video evidence on demand','Vidéo sur demande')],
        ['bi-fuel-pump-fill',    '#8b5cf6', t('Fuel Sensor','Capteur Carburant'),            t('Consumption & theft alerts','Consommation & alertes de vol')],
        ['bi-person-badge-fill', '#22c55e', t('Driver ID Badge','Badge ID Conducteur'),      t('Every trip attributed','Chaque trajet attribué')],
        ['bi-broadcast',         '#06b6d4', t('Beacon Tracker','Balise Traceur'),            t('Asset location, no SIM','Localisation actifs, sans SIM')],
        ['bi-fire',              '#f59e0b', t('Smoke Detector','Détecteur de Fumée'),        t('Premises protection','Protection des locaux')],
        ['bi-broadcast-pin',     '#f97316', t('Badge Beacon','Balise Badge'),                t('Indoor personnel tracking','Suivi personnel intérieur')],
      ];
      foreach ($eco as $i => [$eIcon,$eCol,$eName,$eSub]):
      ?>
        <div class="col-6 col-md-4 col-lg" data-aos="fade-up" data-aos-delay="<?php echo $i*60; ?>">
          <div class="eco-node">
            <div class="eco-icon-wrap" style="background:<?php echo $eCol; ?>18;">
              <i class="bi <?php echo $eIcon; ?>" style="color:<?php echo $eCol; ?>;position:relative;z-index:1;"></i>
            </div>
            <div style="font-size:.88rem;font-weight:700;color:#fff;margin-bottom:4px;"><?php echo escape($eName); ?></div>
            <div style="font-size:.75rem;color:rgba(255,255,255,.45);"><?php echo escape($eSub); ?></div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- Central dashboard callout -->
    <div class="text-center mt-5" data-aos="fade-up" data-aos-delay="200">
      <div style="display:inline-flex;align-items:center;gap:16px;
                  background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1);
                  border-radius:50px;padding:16px 36px;">
        <i class="bi bi-arrow-down-circle-fill" style="font-size:1.6rem;color:#e60000;animation:bounce-down 1.8s ease-in-out infinite;"></i>
        <span style="font-size:.95rem;font-weight:700;color:#fff;"><?php echo escape(get_translation('dev_eco_callout')); ?></span>
        <i class="bi bi-arrow-down-circle-fill" style="font-size:1.6rem;color:#e60000;animation:bounce-down 1.8s ease-in-out infinite;"></i>
      </div>
      <style>@keyframes bounce-down{0%,100%{transform:translateY(0)}50%{transform:translateY(6px)}}</style>
    </div>
  </div>
</section>

<!-- ══════════════════════════════════════════
     SPECS COMPARISON TABLE
══════════════════════════════════════════ -->
<section style="background:#fff;padding:80px 0;">
  <div class="container">
    <div class="section-title" data-aos="fade-up">
      <h2><?php echo escape(get_translation('dev_specs_title')); ?></h2>
      <p><?php echo escape(get_translation('dev_specs_sub')); ?></p>
    </div>
    <div class="table-responsive" data-aos="fade-up" data-aos-delay="100">
      <table class="table dev-specs-table"
             style="border-radius:16px;overflow:hidden;box-shadow:0 2px 20px rgba(0,0,0,.06);">
        <thead>
          <tr>
            <th><?php echo escape(get_translation('dev_table_device')); ?></th>
            <th><?php echo escape(get_translation('dev_table_category')); ?></th>
            <th><?php echo escape(get_translation('dev_table_connectivity')); ?></th>
            <th><?php echo escape(get_translation('dev_table_keyspec')); ?></th>
            <th><?php echo escape(get_translation('dev_table_sim')); ?></th>
            <th><?php echo escape(get_translation('dev_table_install')); ?></th>
          </tr>
        </thead>
        <tbody>
          <?php
          $tableRows = [
            [t('Vehicle GPS Tracker','Traceur GPS de Véhicule'),    t('Tracking','Suivi'),        '4G LTE + GPS',  t('30 s live refresh','Rafraîchissement en direct 30 s'),  t('Yes','Oui'), t('Hardwired / OBD','Câblé / OBD')],
            [t('HD Fleet Camera','Caméra HD de Flotte'),             t('Security','Sécurité'),     '4G LTE + WiFi', t('1080p Full HD','1080p Full HD'),                          t('Yes','Oui'), t('Cabin mount','Montage cabine')],
            [t('Smoke & Heat Detector','Détecteur Fumée et Chaleur'),t('Safety','Sûreté'),        'RS-485 / LoRa', t('Multi-criteria','Multi-critères'),                         t('No','Non'),  t('Ceiling mounted','Plafonnier')],
            [t('Driver ID Badge','Badge ID Conducteur'),             t('Identity','Identité'),     'RFID 125 kHz',  t('Tap-to-start','Démarrage par badge'),                     t('No','Non'),  t('Reader + card','Lecteur + carte')],
            [t('Fuel Level Sensor','Capteur Niveau Carburant'),      t('Monitoring','Surveillance'),'RS-232 / CAN',  t('±1% accuracy','Précision ±1%'),                          t('No','Non'),  t('Tank probe','Sonde de réservoir')],
            [t('Tracker Beacon','Balise Traceur'),                   t('Tracking','Suivi'),        'BLE 5.0',       t('2-year battery','Batterie 2 ans'),                        t('No','Non'),  t('Clip / magnet','Clip / aimant')],
            [t('Badge Beacon Tracker','Traceur Balise Badge'),       t('Identity','Identité'),     'BLE + RFID',    t('50 m indoor range','Portée intérieure 50 m'),             t('No','Non'),  t('Wearable clip','Clip portable')],
          ];
          $catColors = [
            'Tracking'   =>'#e60000','Suivi'       =>'#e60000',
            'Security'   =>'#3b82f6','Sécurité'    =>'#3b82f6',
            'Safety'     =>'#f59e0b','Sûreté'      =>'#f59e0b',
            'Identity'   =>'#22c55e','Identité'    =>'#22c55e',
            'Monitoring' =>'#8b5cf6','Surveillance'=>'#8b5cf6',
          ];
          foreach ($tableRows as $r):
            $rc = $catColors[$r[1]] ?? '#888';
          ?>
            <tr>
              <td style="font-weight:700;color:#1a202c;"><?php echo escape($r[0]); ?></td>
              <td>
                <span style="background:<?php echo $rc; ?>12;color:<?php echo $rc; ?>;
                              font-size:.72rem;font-weight:700;padding:3px 10px;
                              border-radius:20px;letter-spacing:.05em;text-transform:uppercase;">
                  <?php echo escape($r[1]); ?>
                </span>
              </td>
              <td style="color:#64748b;"><?php echo escape($r[2]); ?></td>
              <td style="color:#374151;font-weight:600;"><?php echo escape($r[3]); ?></td>
              <td>
                <?php if ($r[4] === 'Yes' || $r[4] === 'Oui'): ?>
                  <i class="bi bi-check-circle-fill" style="color:#22c55e;"></i>
                <?php else: ?>
                  <i class="bi bi-x-circle-fill" style="color:#cbd5e1;"></i>
                <?php endif; ?>
              </td>
              <td style="color:#64748b;font-size:.875rem;"><?php echo escape($r[5]); ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</section>

<!-- ══════════════════════════════════════════
     BOTTOM CTA
══════════════════════════════════════════ -->
<section style="background:linear-gradient(135deg,#c40000,#8b0000);padding:80px 0;
                position:relative;overflow:hidden;">
  <div style="position:absolute;top:-60px;right:-60px;width:280px;height:280px;
              border-radius:50%;background:rgba(255,255,255,.04);"></div>
  <div style="position:absolute;bottom:-40px;left:-40px;width:200px;height:200px;
              border-radius:50%;background:rgba(255,255,255,.04);"></div>
  <div class="container text-center position-relative" data-aos="fade-up">
    <i class="bi bi-cpu-fill" style="font-size:2.8rem;color:rgba(255,255,255,.3);margin-bottom:16px;display:block;"></i>
    <h2 style="font-size:clamp(1.6rem,3vw,2.4rem);font-weight:900;color:#fff;
               margin-bottom:12px;letter-spacing:-.02em;">
      <?php echo escape(get_translation('dev_cta_title')); ?>
    </h2>
    <p style="color:rgba(255,255,255,.75);font-size:1rem;max-width:500px;
              margin:0 auto 32px;line-height:1.8;">
      <?php echo escape(get_translation('dev_cta_sub')); ?>
    </p>
    <div style="display:flex;justify-content:center;flex-wrap:wrap;gap:14px;">
      <a href="<?php echo escape(site_url('contact.php')); ?>"
         style="display:inline-flex;align-items:center;gap:10px;background:#fff;color:#c40000;
                font-weight:800;font-size:1rem;padding:16px 40px;border-radius:50px;
                text-decoration:none;box-shadow:0 6px 24px rgba(0,0,0,.2);transition:all .25s;"
         onmouseover="this.style.transform='translateY(-3px)'"
         onmouseout="this.style.transform='translateY(0)'">
        <i class="bi bi-calendar-check-fill"></i> <?php echo escape(get_translation('dev_book_consult')); ?>
      </a>
      <a href="<?php echo escape(site_url('SmartFleet.php')); ?>"
         style="display:inline-flex;align-items:center;gap:10px;
                background:rgba(255,255,255,.12);color:#fff;font-weight:700;font-size:.95rem;
                padding:16px 36px;border-radius:50px;text-decoration:none;
                border:1.5px solid rgba(255,255,255,.3);transition:all .25s;"
         onmouseover="this.style.background='rgba(255,255,255,.2)'"
         onmouseout="this.style.background='rgba(255,255,255,.12)'">
        <i class="bi bi-grid-1x2-fill"></i> <?php echo escape(get_translation('dev_view_solutions')); ?>
      </a>
    </div>
  </div>
</section>

<!-- ── Category filter JS ── -->
<script>
(function () {
  const btns  = document.querySelectorAll('.dev-filter-btn');
  const cols  = document.querySelectorAll('.dev-col');

  btns.forEach(btn => {
    btn.addEventListener('click', () => {
      btns.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      const filter = btn.dataset.filter;
      cols.forEach(col => {
        if (filter === 'All' || col.dataset.cat === filter) {
          col.classList.remove('hidden');
        } else {
          col.classList.add('hidden');
        }
      });
    });
  });
})();
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
