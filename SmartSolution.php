<?php
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'SmartSolution — Integrated Security Platform';
$bodyClass = 'smartsolution-page';
$lang      = current_language();

define('APP_INIT', true);
include __DIR__ . '/includes/header.php';
?>

<style>
/* ══ SmartSolution Page ══════════════════════════════ */

/* Hero */
.ss-hero {
  min-height:100vh;display:flex;align-items:center;
  background:linear-gradient(135deg,#030b14 0%,#050f1c 60%,#0a0612 100%);
  position:relative;overflow:hidden;
}
.ss-hero-orbs { position:absolute;inset:0;pointer-events:none; }
.ss-orb {
  position:absolute;border-radius:50%;filter:blur(80px);
  animation:orb-drift 10s ease-in-out infinite;
}
@keyframes orb-drift{0%,100%{transform:translateY(0) scale(1)}50%{transform:translateY(30px) scale(1.05)}}

/* Solution nav cards */
.ss-nav-card {
  padding:20px 16px;border-radius:16px;text-align:center;cursor:pointer;
  border:1.5px solid #e9ecef;background:#fff;
  transition:all .28s;text-decoration:none;display:block;
}
.ss-nav-card:hover {
  transform:translateY(-5px) perspective(800px) rotateX(3deg);
  box-shadow:0 12px 40px rgba(0,0,0,.1);
}
.ss-nav-card .ss-nav-icon {
  width:54px;height:54px;border-radius:14px;
  display:flex;align-items:center;justify-content:center;
  font-size:1.4rem;margin:0 auto 12px;transition:transform .4s ease;
}
.ss-nav-card:hover .ss-nav-icon { transform:rotateY(360deg); }
.ss-nav-card h6 { font-size:.8rem;font-weight:800;color:#1a202c;margin:0;letter-spacing:.01em; }

/* Feature section */
.ss-solution { padding:96px 0;position:relative; }
.ss-solution:nth-child(even) { background:#f8f9fb; }

/* Bullet row */
.ss-bullet {
  display:flex;align-items:flex-start;gap:14px;
  padding:15px 16px;border-radius:11px;margin-bottom:12px;
  background:#f8f9fb;border:1px solid #f0f0f0;
  transition:all .3s;
}
.ss-solution:nth-child(even) .ss-bullet { background:#fff; }
.ss-bullet:hover { transform:translateX(4px);border-color:rgba(0,0,0,.12);box-shadow:0 4px 16px rgba(0,0,0,.06); }
.ss-bullet-icon {
  width:38px;height:38px;border-radius:10px;flex-shrink:0;
  display:flex;align-items:center;justify-content:center;font-size:1rem;
}
.ss-bullet h6 { font-size:.875rem;font-weight:700;color:#1a202c;margin:0 0 2px; }
.ss-bullet p  { font-size:.82rem;color:#64748b;margin:0;line-height:1.5; }

/* Number badge */
.ss-num {
  width:32px;height:32px;border-radius:50%;display:inline-flex;
  align-items:center;justify-content:center;
  font-size:.75rem;font-weight:900;color:#fff;flex-shrink:0;margin-right:4px;
}

/* Screen wrapper */
.ss-img-wrap {
  border-radius:20px;overflow:hidden;
  box-shadow:0 20px 70px rgba(0,0,0,.14);
  transition:transform .4s ease;
  position:relative;
}
.ss-img-wrap:hover { transform:perspective(1000px) rotateY(3deg) rotateX(-2deg) scale(1.02); }
.ss-img-wrap img { width:100%;display:block;height:400px;object-fit:cover;object-position:center; }

/* Stats dark bar */
.ss-stat-bar {
  border-radius:14px;overflow:hidden;
  background:linear-gradient(135deg,#0b0e1a,#111622);
  margin-top:24px;
}
.ss-stat-cell {
  padding:22px 16px;text-align:center;
  border-right:1px solid rgba(255,255,255,.07);
}
.ss-stat-cell:last-child { border-right:none; }
.ss-stat-v { font-size:1.7rem;font-weight:900;line-height:1;display:block;letter-spacing:-.03em; }
.ss-stat-l { font-size:.68rem;font-weight:700;color:rgba(255,255,255,.4);text-transform:uppercase;
  letter-spacing:.08em;margin-top:4px; }

/* Section badge */
.ss-badge {
  display:inline-flex;align-items:center;gap:8px;
  border-radius:30px;padding:5px 16px;margin-bottom:16px;border:1px solid;
}
.ss-badge span { font-size:.72rem;font-weight:700;letter-spacing:.07em;text-transform:uppercase; }
</style>

<!-- ══════════════════════════════════════════
     HERO
══════════════════════════════════════════ -->
<section class="ss-hero">
  <div class="ss-hero-orbs">
    <div class="ss-orb" style="width:500px;height:500px;background:#3b82f620;top:-100px;right:-50px;animation-delay:0s;"></div>
    <div class="ss-orb" style="width:400px;height:400px;background:#8b5cf615;bottom:-80px;left:-80px;animation-delay:3s;"></div>
    <div class="ss-orb" style="width:300px;height:300px;background:#e6000010;top:40%;left:40%;animation-delay:6s;"></div>
  </div>

  <div class="container position-relative" style="z-index:2;padding-top:120px;padding-bottom:80px;">
    <div class="row align-items-center gy-5">

      <!-- Left -->
      <div class="col-lg-6" data-aos="fade-right">
        <div style="display:inline-flex;align-items:center;gap:8px;
                    background:rgba(59,130,246,.15);border:1px solid rgba(59,130,246,.3);
                    border-radius:30px;padding:5px 16px;margin-bottom:22px;">
          <span style="width:7px;height:7px;background:#3b82f6;border-radius:50%;
                        box-shadow:0 0 0 3px rgba(59,130,246,.3);animation:ss-pulse 2s infinite;"></span>
          <span style="font-size:.78rem;font-weight:700;color:rgba(255,255,255,.8);letter-spacing:.07em;text-transform:uppercase;">
            <?php echo escape(get_translation('ss_platform_badge')); ?>
          </span>
        </div>
        <style>@keyframes ss-pulse{0%,100%{box-shadow:0 0 0 3px rgba(59,130,246,.3)}50%{box-shadow:0 0 0 8px rgba(59,130,246,.06)}}</style>

        <h1 style="font-size:clamp(2.8rem,5.5vw,4.2rem);font-weight:900;color:#fff;
                   line-height:1.05;letter-spacing:-.04em;margin-bottom:20px;">
          Smart<span style="color:#3b82f6;">Solution</span>
        </h1>
        <p style="font-size:1.1rem;color:rgba(255,255,255,.6);line-height:1.8;
                  max-width:520px;margin-bottom:36px;">
          <?php echo escape(get_translation('ss_hero_sub')); ?>
        </p>

        <div style="display:flex;flex-wrap:wrap;gap:12px;margin-bottom:40px;">
          <a href="<?php echo escape(site_url('contact.php')); ?>"
             style="display:inline-flex;align-items:center;gap:10px;background:#3b82f6;color:#fff;
                    font-weight:700;font-size:.95rem;padding:14px 32px;border-radius:50px;
                    text-decoration:none;box-shadow:0 6px 24px rgba(59,130,246,.4);transition:all .25s;"
             onmouseover="this.style.background='#2563eb';this.style.transform='translateY(-2px)'"
             onmouseout="this.style.background='#3b82f6';this.style.transform='none'">
            <i class="bi bi-shield-fill-check"></i> <?php echo escape(get_translation('ss_security_btn')); ?>
          </a>
          <a href="#video-surveillance"
             style="display:inline-flex;align-items:center;gap:10px;
                    background:rgba(255,255,255,.07);border:1.5px solid rgba(255,255,255,.18);
                    color:#fff;font-weight:600;font-size:.95rem;padding:14px 32px;
                    border-radius:50px;text-decoration:none;transition:all .25s;"
             onmouseover="this.style.background='rgba(255,255,255,.13)'"
             onmouseout="this.style.background='rgba(255,255,255,.07)'">
            <i class="bi bi-arrow-down-circle" style="color:#3b82f6;"></i> <?php echo escape(get_translation('ss_explore_btn')); ?>
          </a>
        </div>

        <div style="display:flex;flex-wrap:wrap;gap:18px;">
          <?php foreach ([t('Video Surveillance','Vidéosurveillance'),t('Access Control','Contrôle d\'Accès'),t('Fire Detection','Détection Incendie'),t('Network Security','Sécurité Réseau'),t('Time Management','Gestion du Temps')] as $t): ?>
            <div style="display:flex;align-items:center;gap:7px;font-size:.8rem;color:rgba(255,255,255,.45);">
              <i class="bi bi-check-circle-fill" style="color:#3b82f6;font-size:.7rem;"></i><?php echo $t; ?>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Right: solution icons grid -->
      <div class="col-lg-6 d-none d-lg-block" data-aos="fade-left" data-aos-delay="150">
        <div class="row g-3">
          <?php
          $heroCards = [
            ['bi-camera-video-fill','#3b82f6',t('Video Surveillance','Vidéosurveillance'),t('HD cameras, cloud storage, live view','Caméras HD, stockage cloud, vue en direct')],
            ['bi-door-open-fill',   '#8b5cf6',t('Access Control','Contrôle d\'Accès'),     t('Biometric + RFID entry management','Gestion d\'entrée biométrique + RFID')],
            ['bi-fire',             '#e60000',t('Fire Detection','Détection Incendie'),    t('Multi-zone addressable detectors','Détecteurs adressables multi-zones')],
            ['bi-wifi',             '#06b6d4',t('Network Security','Sécurité Réseau'),      t('Firewall, VPN, managed WiFi','Pare-feu, VPN, WiFi géré')],
            ['bi-fingerprint',      '#22c55e',t('Time Management','Gestion du Temps'),      t('Biometric attendance & payroll','Présence biométrique et paie')],
          ];
          foreach ($heroCards as $i => [$ic,$col,$t,$sub]):
          ?>
            <div class="col-<?php echo $i === 4 ? '12' : '6'; ?>">
              <div style="background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);
                          border-radius:16px;padding:20px;display:flex;align-items:center;gap:14px;
                          backdrop-filter:blur(12px);transition:all .3s;"
                   onmouseover="this.style.background='rgba(255,255,255,.07)';this.style.transform='translateY(-3px)'"
                   onmouseout="this.style.background='rgba(255,255,255,.04)';this.style.transform='none'">
                <div style="width:46px;height:46px;border-radius:12px;flex-shrink:0;
                             background:<?php echo $col; ?>20;
                             display:flex;align-items:center;justify-content:center;
                             font-size:1.3rem;color:<?php echo $col; ?>;">
                  <i class="bi <?php echo $ic; ?>"></i>
                </div>
                <div>
                  <div style="font-size:.875rem;font-weight:700;color:#fff;"><?php echo $t; ?></div>
                  <div style="font-size:.75rem;color:rgba(255,255,255,.4);margin-top:2px;"><?php echo $sub; ?></div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
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
          <li class="current">SmartSolution</li>
        </ol>
      </nav>
    </div>
  </div>
</section>

<!-- ══════════════════════════════════════════
     SOLUTION NAVIGATION CARDS
══════════════════════════════════════════ -->
<section style="background:#fff;padding:60px 0 20px;">
  <div class="container">
    <p style="text-align:center;font-size:.78rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#aaa;margin-bottom:20px;">
      <?php echo escape(get_translation('ss_five_systems')); ?>
    </p>
    <div class="row g-3" data-aos="fade-up">
      <?php
      $navCards = [
        ['#video-surveillance','bi-camera-video-fill','#3b82f6',t('Video','Vidéo'),t('Surveillance','Surveillance')],
        ['#access-control',    'bi-door-open-fill',   '#8b5cf6',t('Access','Contrôle'),t('Control','d\'Accès')],
        ['#fire-detection',    'bi-fire',             '#e60000',t('Fire','Détection'),t('Detection','Incendie')],
        ['#network-security',  'bi-wifi',             '#06b6d4',t('Network','Sécurité'),t('Security','Réseau')],
        ['#time-management',   'bi-fingerprint',      '#22c55e',t('Time','Gestion'),t('Management','du Temps')],
      ];
      foreach ($navCards as [$href,$ic,$col,$t1,$t2]):
      ?>
        <div class="col">
          <a href="<?php echo $href; ?>" class="ss-nav-card" style="border-color:<?php echo $col; ?>25;">
            <div class="ss-nav-icon" style="background:<?php echo $col; ?>12;color:<?php echo $col; ?>;">
              <i class="bi <?php echo $ic; ?>"></i>
            </div>
            <h6><?php echo $t1; ?><br><?php echo $t2; ?></h6>
          </a>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ══════════════════════════════════════════
     SOLUTION 1 — VIDEO SURVEILLANCE
══════════════════════════════════════════ -->
<section id="video-surveillance" class="ss-solution">
  <div class="container">
    <div class="row align-items-center gy-5">
      <div class="col-lg-6" data-aos="fade-right">
        <div class="ss-badge" style="background:rgba(59,130,246,.08);border-color:rgba(59,130,246,.2);">
          <i class="bi bi-camera-video-fill" style="color:#3b82f6;"></i>
          <span style="color:#3b82f6;"><?php echo escape(get_translation('ss_vid_badge')); ?></span>
        </div>
        <h2 style="font-size:clamp(1.8rem,3vw,2.5rem);font-weight:900;color:#1a202c;letter-spacing:-.03em;line-height:1.2;margin-bottom:14px;">
          <?php echo get_translation('ss_vid_title'); ?>
        </h2>
        <p style="color:#64748b;font-size:.975rem;line-height:1.8;margin-bottom:28px;">
          <?php echo escape(get_translation('ss_vid_sub')); ?>
        </p>
        <?php
        $vidFeatures = [
          ['bi-camera2',            '#3b82f6',t('HD Road & Cabin Cameras','Caméras HD Route et Cabine'),t('Full HD dual-lens cameras covering entry, exit, corridors, and parking.',                'Caméras Full HD à double objectif couvrant entrée, sortie, couloirs et parking.')],
          ['bi-moon-fill',          '#8b5cf6',t('Night Vision','Vision Nocturne'),                      t('Infrared night vision up to 30 m ensures 24-hour coverage with no blind spots.',         'La vision nocturne infrarouge jusqu\'à 30 m assure une couverture 24h/24 sans angle mort.')],
          ['bi-cloud-arrow-up-fill','#22c55e',t('Auto Cloud Upload','Téléversement Cloud Auto'),         t('Motion and event-triggered clips upload immediately — never lost if the camera is stolen.','Les clips déclenchés par mouvement ou événement sont téléversés immédiatement — jamais perdus si la caméra est volée.')],
          ['bi-play-btn-fill',      '#f59e0b',t('Live Remote Viewing','Visionnage à Distance en Direct'),t('Stream any camera live via the web portal or mobile app from anywhere in the world.',     'Diffusez n\'importe quelle caméra en direct via le portail web ou l\'application mobile, partout dans le monde.')],
          ['bi-shield-lock-fill',   '#e60000',t('Evidence Export','Export de Preuves'),                 t('Download clips with GPS, speed, and timestamp watermarks — court-admissible quality.',    'Téléchargez des clips avec filigranes GPS, vitesse et horodatage — qualité recevable en justice.')],
          ['bi-person-fill',        '#06b6d4',t('AI Motion Detection','Détection de Mouvement IA'),     t('Person and vehicle detection reduces false alarms and focuses alerts on real threats.',   'La détection de personnes et de véhicules réduit les fausses alarmes et concentre les alertes sur les menaces réelles.')],
        ];
        foreach ($vidFeatures as [$ic,$col,$t,$d]):
        ?>
          <div class="ss-bullet">
            <div class="ss-bullet-icon" style="background:<?php echo $col; ?>15;color:<?php echo $col; ?>;">
              <i class="bi <?php echo $ic; ?>"></i>
            </div>
            <div><h6><?php echo $t; ?></h6><p><?php echo $d; ?></p></div>
          </div>
        <?php endforeach; ?>
      </div>
      <div class="col-lg-6" data-aos="fade-left" data-aos-delay="100">
        <div class="ss-img-wrap">
          <img src="<?php echo escape(site_url('assets/img/Home Security Cameras Installation Orange County.jpg')); ?>" alt="Video Surveillance">
          <div style="position:absolute;bottom:20px;left:20px;right:20px;
                      background:rgba(0,0,0,.7);backdrop-filter:blur(10px);
                      border-radius:12px;padding:14px 18px;
                      display:flex;align-items:center;gap:12px;">
            <span style="width:9px;height:9px;border-radius:50%;background:#22c55e;flex-shrink:0;
                          box-shadow:0 0 0 3px rgba(34,197,94,.3);animation:ss-pulse 2s infinite;"></span>
            <span style="font-size:.8rem;color:#fff;font-weight:600;"><?php echo escape(t('Live feed active — 8 cameras online','Flux en direct actif — 8 caméras en ligne')); ?></span>
          </div>
        </div>
        <div class="ss-stat-bar">
          <div class="row g-0">
            <?php foreach ([['1080p',t('Resolution','Résolution')],['30–90d',t('Cloud Retention','Rétention Cloud')],['< 5 min',t('Clip Delivery','Livraison Clip')]] as [$v,$l]): ?>
              <div class="col-4 ss-stat-cell">
                <span class="ss-stat-v" style="color:#3b82f6;"><?php echo $v; ?></span>
                <div class="ss-stat-l"><?php echo $l; ?></div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ══════════════════════════════════════════
     SOLUTION 2 — ACCESS CONTROL
══════════════════════════════════════════ -->
<section id="access-control" class="ss-solution">
  <div class="container">
    <div class="row align-items-center gy-5 flex-lg-row-reverse">
      <div class="col-lg-6" data-aos="fade-left">
        <div class="ss-badge" style="background:rgba(139,92,246,.08);border-color:rgba(139,92,246,.2);">
          <i class="bi bi-door-open-fill" style="color:#8b5cf6;"></i>
          <span style="color:#8b5cf6;"><?php echo escape(get_translation('ss_acc_badge')); ?></span>
        </div>
        <h2 style="font-size:clamp(1.8rem,3vw,2.5rem);font-weight:900;color:#1a202c;letter-spacing:-.03em;line-height:1.2;margin-bottom:14px;">
          <?php echo get_translation('ss_acc_title'); ?>
        </h2>
        <p style="color:#64748b;font-size:.975rem;line-height:1.8;margin-bottom:28px;">
          <?php echo escape(get_translation('ss_acc_sub')); ?>
        </p>
        <?php
        $accFeatures = [
          ['bi-fingerprint',        '#8b5cf6',t('Biometric Readers','Lecteurs Biométriques'),t('Fingerprint and face recognition for the highest-security entry points.',                 'Empreinte digitale et reconnaissance faciale pour les points d\'entrée à très haute sécurité.')],
          ['bi-credit-card-2-front','#3b82f6',t('Smart Card & PIN','Carte Intelligente et PIN'),t('RFID proximity cards and PIN pads for offices, warehouses, and staff zones.',           'Cartes de proximité RFID et claviers PIN pour bureaux, entrepôts et zones du personnel.')],
          ['bi-clock-fill',         '#f59e0b',t('Time-Based Rules','Règles Temporelles'),    t('Restrict doors by staff member, day of week, and time — enforced automatically.',         'Restreignez les portes par employé, jour de la semaine et heure — appliqué automatiquement.')],
          ['bi-list-ul',            '#22c55e',t('Full Audit Log','Journal d\'Audit Complet'),t('Every entry and exit timestamped and stored — run instant who-was-where reports.',        'Chaque entrée et sortie horodatée et stockée — générez des rapports instantanés sur qui était où.')],
          ['bi-shield-fill-exclamation','#e60000',t('Intrusion Alerts','Alertes d\'Intrusion'),t('Forced entry or tailgating triggers immediate alarm and control-room notification.',    'Une entrée forcée ou un talonnage déclenche une alarme immédiate et une notification au centre de contrôle.')],
          ['bi-phone-landscape-fill','#06b6d4',t('Mobile Management','Gestion Mobile'),       t('Enrol staff, revoke access, and review logs from the admin mobile app — remotely.',       'Inscrivez le personnel, révoquez les accès et consultez les journaux depuis l\'app mobile admin — à distance.')],
        ];
        foreach ($accFeatures as [$ic,$col,$t,$d]):
        ?>
          <div class="ss-bullet">
            <div class="ss-bullet-icon" style="background:<?php echo $col; ?>15;color:<?php echo $col; ?>;">
              <i class="bi <?php echo $ic; ?>"></i>
            </div>
            <div><h6><?php echo $t; ?></h6><p><?php echo $d; ?></p></div>
          </div>
        <?php endforeach; ?>
      </div>
      <div class="col-lg-6" data-aos="fade-right" data-aos-delay="100">
        <div class="ss-img-wrap">
          <img src="<?php echo escape(site_url('assets/img/biometric.jpg')); ?>" alt="Access Control">
        </div>
        <div class="ss-stat-bar">
          <div class="row g-0">
            <?php foreach ([['< 1 s',t('Read Time','Temps de Lecture')],['100%',t('Audit Coverage','Couverture Audit')],[t('Mobile','Mobile'),t('Admin Access','Accès Admin')]] as [$v,$l]): ?>
              <div class="col-4 ss-stat-cell">
                <span class="ss-stat-v" style="color:#8b5cf6;"><?php echo $v; ?></span>
                <div class="ss-stat-l"><?php echo $l; ?></div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ══════════════════════════════════════════
     SOLUTION 3 — FIRE DETECTION
══════════════════════════════════════════ -->
<section id="fire-detection" class="ss-solution">
  <div class="container">
    <div class="row align-items-center gy-5">
      <div class="col-lg-6" data-aos="fade-right">
        <div class="ss-badge" style="background:rgba(230,0,0,.08);border-color:rgba(230,0,0,.2);">
          <i class="bi bi-fire" style="color:#e60000;"></i>
          <span style="color:#e60000;"><?php echo escape(get_translation('ss_fire_badge')); ?></span>
        </div>
        <h2 style="font-size:clamp(1.8rem,3vw,2.5rem);font-weight:900;color:#1a202c;letter-spacing:-.03em;line-height:1.2;margin-bottom:14px;">
          <?php echo get_translation('ss_fire_title'); ?>
        </h2>
        <p style="color:#64748b;font-size:.975rem;line-height:1.8;margin-bottom:28px;">
          <?php echo escape(get_translation('ss_fire_sub')); ?>
        </p>
        <?php
        $fireFeatures = [
          ['bi-fire',               '#e60000',t('Smoke & Heat Detection','Détection Fumée et Chaleur'),t('Multi-criteria sensors detect both smoke and rapid heat rise simultaneously.',           'Des capteurs multicritères détectent simultanément la fumée et la hausse rapide de température.')],
          ['bi-bell-fill',          '#f59e0b',t('Sounder & Strobe','Avertisseur et Stroboscope'),     t('85 dB sounders and visual strobes — audible even in noisy industrial environments.',       'Avertisseurs 85 dB et stroboscopes visuels — audibles même dans des environnements industriels bruyants.')],
          ['bi-phone-fill',         '#3b82f6',t('Instant SMS Alert','Alerte SMS Instantanée'),       t('Facility managers receive push notification and SMS within seconds of detection.',         'Les responsables reçoivent une notification push et un SMS en quelques secondes après détection.')],
          ['bi-shield-fill-check',  '#22c55e',t('Sprinkler Integration','Intégration Sprinkler'),     t('Compatible with wet-pipe, dry-pipe, and pre-action suppression systems.',                  'Compatible avec les systèmes d\'extinction à tuyaux mouillés, secs et pré-action.')],
          ['bi-list-check',         '#8b5cf6',t('Monthly Self-Test','Auto-Test Mensuel'),             t('Detectors run self-diagnostics monthly. Faults are flagged before they become a risk.',    'Les détecteurs effectuent un auto-diagnostic mensuel. Les pannes sont signalées avant de devenir un risque.')],
          ['bi-file-earmark-text',  '#06b6d4',t('Compliance Certs','Certificats de Conformité'),      t('Full documentation for fire safety regulations, insurance, and building permits.',         'Documentation complète pour les réglementations de sécurité incendie, l\'assurance et les permis de construire.')],
        ];
        foreach ($fireFeatures as [$ic,$col,$t,$d]):
        ?>
          <div class="ss-bullet">
            <div class="ss-bullet-icon" style="background:<?php echo $col; ?>15;color:<?php echo $col; ?>;">
              <i class="bi <?php echo $ic; ?>"></i>
            </div>
            <div><h6><?php echo $t; ?></h6><p><?php echo $d; ?></p></div>
          </div>
        <?php endforeach; ?>
      </div>
      <div class="col-lg-6" data-aos="fade-left" data-aos-delay="100">
        <div class="ss-img-wrap">
          <img src="<?php echo escape(site_url('assets/img/Mains Smoke alarm can significantly reduce the….jpg')); ?>" alt="Fire Detection">
        </div>
        <div class="ss-stat-bar">
          <div class="row g-0">
            <?php foreach ([['< 10 s',t('Detection Alert','Alerte Détection')],['365/24/7',t('Monitoring','Surveillance')],['5 yr',t('Warranty','Garantie')]] as [$v,$l]): ?>
              <div class="col-4 ss-stat-cell">
                <span class="ss-stat-v" style="color:#e60000;"><?php echo $v; ?></span>
                <div class="ss-stat-l"><?php echo $l; ?></div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ══════════════════════════════════════════
     SOLUTION 4 — NETWORK SECURITY
══════════════════════════════════════════ -->
<section id="network-security" class="ss-solution">
  <div class="container">
    <div class="row align-items-center gy-5 flex-lg-row-reverse">
      <div class="col-lg-6" data-aos="fade-left">
        <div class="ss-badge" style="background:rgba(6,182,212,.08);border-color:rgba(6,182,212,.2);">
          <i class="bi bi-wifi" style="color:#06b6d4;"></i>
          <span style="color:#06b6d4;"><?php echo escape(get_translation('ss_net_badge')); ?></span>
        </div>
        <h2 style="font-size:clamp(1.8rem,3vw,2.5rem);font-weight:900;color:#1a202c;letter-spacing:-.03em;line-height:1.2;margin-bottom:14px;">
          <?php echo get_translation('ss_net_title'); ?>
        </h2>
        <p style="color:#64748b;font-size:.975rem;line-height:1.8;margin-bottom:28px;">
          <?php echo escape(get_translation('ss_net_sub')); ?>
        </p>
        <?php
        $netFeatures = [
          ['bi-shield-lock-fill',   '#06b6d4',t('Next-Gen Firewall & UTM','Pare-feu Nouvelle Génération et UTM'),t('Blocks malware, ransomware, and intrusion attempts in real time.',                'Bloque les malwares, ransomwares et tentatives d\'intrusion en temps réel.')],
          ['bi-wifi',               '#3b82f6',t('Managed Enterprise WiFi','WiFi Entreprise Géré'),  t('SSID segmentation, guest isolation, and bandwidth control — centrally managed.',           'Segmentation SSID, isolation des invités et contrôle de bande passante — géré centralement.')],
          ['bi-diagram-3-fill',     '#22c55e',t('Structured Cabling','Câblage Structuré'),         t('Cat6/Cat6A and fibre to TIA-568 standards — clean, labelled, documented.',                 'Cat6/Cat6A et fibre aux normes TIA-568 — propre, étiqueté, documenté.')],
          ['bi-bar-chart-fill',     '#f59e0b',t('24/7 NOC Monitoring','Surveillance NOC 24h/7j'),  t('Our network operations centre watches bandwidth, latency, and device health around the clock.','Notre centre d\'opérations réseau surveille la bande passante, la latence et la santé des équipements en continu.')],
          ['bi-cloud-arrow-up-fill','#8b5cf6',t('Secure VPN Access','Accès VPN Sécurisé'),          t('Site-to-site and client VPN — encrypted remote work with no exposed ports.',               'VPN site-à-site et client — télétravail chiffré sans ports exposés.')],
          ['bi-tools',              '#e60000',t('Managed IT Services','Services Informatiques Gérés'),t('Monthly subscription covers monitoring, patching, and helpdesk. No surprise bills.',     'L\'abonnement mensuel couvre la surveillance, les correctifs et l\'assistance. Aucune facture surprise.')],
        ];
        foreach ($netFeatures as [$ic,$col,$t,$d]):
        ?>
          <div class="ss-bullet">
            <div class="ss-bullet-icon" style="background:<?php echo $col; ?>15;color:<?php echo $col; ?>;">
              <i class="bi <?php echo $ic; ?>"></i>
            </div>
            <div><h6><?php echo $t; ?></h6><p><?php echo $d; ?></p></div>
          </div>
        <?php endforeach; ?>
      </div>
      <div class="col-lg-6" data-aos="fade-right" data-aos-delay="100">
        <div class="ss-img-wrap">
          <img src="<?php echo escape(site_url('assets/img/Data Security.jpg')); ?>" alt="Network Security">
        </div>
        <div class="ss-stat-bar">
          <div class="row g-0">
            <?php foreach ([['99.9%',t('Uptime SLA','Disponibilité SLA')],['< 15 min',t('Response','Réponse')],['0',t('Unpatched CVEs','CVE Non Corrigées')]] as [$v,$l]): ?>
              <div class="col-4 ss-stat-cell">
                <span class="ss-stat-v" style="color:#06b6d4;"><?php echo $v; ?></span>
                <div class="ss-stat-l"><?php echo $l; ?></div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ══════════════════════════════════════════
     SOLUTION 5 — TIME MANAGEMENT
══════════════════════════════════════════ -->
<section id="time-management" class="ss-solution">
  <div class="container">
    <div class="row align-items-center gy-5">
      <div class="col-lg-6" data-aos="fade-right">
        <div class="ss-badge" style="background:rgba(34,197,94,.08);border-color:rgba(34,197,94,.2);">
          <i class="bi bi-fingerprint" style="color:#22c55e;"></i>
          <span style="color:#22c55e;"><?php echo escape(get_translation('ss_time_badge')); ?></span>
        </div>
        <h2 style="font-size:clamp(1.8rem,3vw,2.5rem);font-weight:900;color:#1a202c;letter-spacing:-.03em;line-height:1.2;margin-bottom:14px;">
          <?php echo get_translation('ss_time_title'); ?>
        </h2>
        <p style="color:#64748b;font-size:.975rem;line-height:1.8;margin-bottom:28px;">
          <?php echo escape(get_translation('ss_time_sub')); ?>
        </p>
        <?php
        $timeFeatures = [
          ['bi-fingerprint',        '#22c55e',t('Biometric Clocking','Pointage Biométrique'),     t('Fingerprint and face recognition — no cards, no PINs, no proxy clocking.',                 'Empreinte digitale et reconnaissance faciale — sans cartes, sans PIN, sans pointage par procuration.')],
          ['bi-clock-fill',         '#3b82f6',t('Real-Time Attendance','Présence en Temps Réel'),  t('Live dashboard shows who is on-site at any moment — updated on every clock event.',        'Le tableau de bord en direct montre qui est sur site à tout moment — mis à jour à chaque pointage.')],
          ['bi-calendar-check-fill','#f59e0b',t('Shift Scheduling','Planification des Quarts'),     t('Build shift rosters, set overtime rules, and flag late arrivals automatically.',           'Créez des plannings, définissez les règles d\'heures supplémentaires et signalez automatiquement les retards.')],
          ['bi-file-earmark-excel', '#8b5cf6',t('Payroll Export','Export Paie'),                    t('Month-end reports export in CSV — compatible with all major payroll platforms.',           'Les rapports de fin de mois s\'exportent en CSV — compatibles avec toutes les grandes plateformes de paie.')],
          ['bi-geo-fill',           '#e60000',t('Multi-Site Support','Support Multi-Sites'),        t('Manage staff across multiple locations from a single admin console.',                      'Gérez le personnel sur plusieurs sites depuis une seule console d\'administration.')],
          ['bi-graph-up-arrow',     '#06b6d4',t('Productivity Reporting','Rapports de Productivité'),t('Identify patterns — late arrivals, absenteeism, overtime trends — at a glance.',           'Identifiez les tendances — retards, absentéisme, heures supplémentaires — en un coup d\'œil.')],
        ];
        foreach ($timeFeatures as [$ic,$col,$t,$d]):
        ?>
          <div class="ss-bullet">
            <div class="ss-bullet-icon" style="background:<?php echo $col; ?>15;color:<?php echo $col; ?>;">
              <i class="bi <?php echo $ic; ?>"></i>
            </div>
            <div><h6><?php echo $t; ?></h6><p><?php echo $d; ?></p></div>
          </div>
        <?php endforeach; ?>
      </div>
      <div class="col-lg-6" data-aos="fade-left" data-aos-delay="100">
        <div class="ss-img-wrap">
          <img src="<?php echo escape(site_url('assets/img/fingerprint clocking in machine.jpg')); ?>" alt="Time Management">
        </div>
        <div class="ss-stat-bar">
          <div class="row g-0">
            <?php foreach ([['< 1 s',t('Clock-in Speed','Vitesse de Pointage')],['0',t('Manual Errors','Erreurs Manuelles')],[t('Multi-site','Multi-sites'),t('One Console','Une Console')]] as [$v,$l]): ?>
              <div class="col-4 ss-stat-cell">
                <span class="ss-stat-v" style="color:#22c55e;"><?php echo $v; ?></span>
                <div class="ss-stat-l"><?php echo $l; ?></div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ══════════════════════════════════════════
     INTEGRATION STRIP
══════════════════════════════════════════ -->
<section style="background:linear-gradient(135deg,#0b0e1a,#111622);padding:80px 0;">
  <div class="container">
    <div class="row align-items-center gy-4">
      <div class="col-lg-6" data-aos="fade-right">
        <h2 style="font-size:clamp(1.6rem,3vw,2.2rem);font-weight:900;color:#fff;letter-spacing:-.03em;margin-bottom:14px;">
          <?php echo get_translation('ss_integration_title'); ?>
        </h2>
        <p style="color:rgba(255,255,255,.55);font-size:.975rem;line-height:1.8;margin-bottom:28px;">
          <?php echo escape(get_translation('ss_integration_sub')); ?>
        </p>
        <a href="<?php echo escape(site_url('contact.php')); ?>"
           style="display:inline-flex;align-items:center;gap:10px;background:#3b82f6;color:#fff;
                  font-weight:700;font-size:.9rem;padding:13px 28px;border-radius:50px;
                  text-decoration:none;box-shadow:0 4px 18px rgba(59,130,246,.4);transition:.25s;"
           onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='none'">
          <i class="bi bi-shield-fill-check"></i> <?php echo escape(get_translation('ss_request_audit')); ?>
        </a>
      </div>
      <div class="col-lg-6" data-aos="fade-left" data-aos-delay="100">
        <div class="row g-3">
          <?php
          $integrations = [
            ['bi-camera-video-fill','#3b82f6',t('Camera clips linked to access events','Clips caméra liés aux événements d\'accès')],
            ['bi-fire',            '#e60000',t('Fire alarm auto-unlocks exit doors','L\'alarme incendie déverrouille les portes de sortie')],
            ['bi-fingerprint',     '#22c55e',t('Biometric clocking feeds payroll','Le pointage biométrique alimente la paie')],
            ['bi-shield-lock-fill','#06b6d4',t('Network logs tied to access records','Journaux réseau liés aux registres d\'accès')],
            ['bi-bell-fill',       '#f59e0b',t('One alert engine for all five systems','Un moteur d\'alerte pour les cinq systèmes')],
            ['bi-grid-1x2-fill',   '#8b5cf6',t('Single dashboard — no app-switching','Un seul tableau de bord — sans changement d\'app')],
          ];
          foreach ($integrations as [$ic,$col,$text]):
          ?>
            <div class="col-md-6">
              <div style="display:flex;align-items:center;gap:12px;background:rgba(255,255,255,.04);
                          border:1px solid rgba(255,255,255,.07);border-radius:12px;padding:14px 16px;">
                <i class="bi <?php echo $ic; ?>" style="color:<?php echo $col; ?>;font-size:1.2rem;flex-shrink:0;"></i>
                <span style="font-size:.83rem;color:rgba(255,255,255,.7);font-weight:600;"><?php echo $text; ?></span>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ══════════════════════════════════════════
     CTA
══════════════════════════════════════════ -->
<section style="background:linear-gradient(135deg,#1d4ed8,#1e3a8a);padding:88px 0;
                position:relative;overflow:hidden;">
  <div style="position:absolute;top:-80px;right:-80px;width:320px;height:320px;border-radius:50%;background:rgba(255,255,255,.04);"></div>
  <div class="container text-center position-relative" data-aos="fade-up">
    <i class="bi bi-shield-fill-check" style="font-size:2.8rem;color:rgba(255,255,255,.25);margin-bottom:16px;display:block;"></i>
    <h2 style="font-size:clamp(1.8rem,3.5vw,2.6rem);font-weight:900;color:#fff;margin-bottom:14px;letter-spacing:-.03em;">
      <?php echo get_translation('ss_cta_title'); ?>
    </h2>
    <p style="color:rgba(255,255,255,.7);font-size:1rem;max-width:500px;margin:0 auto 36px;line-height:1.8;">
      <?php echo escape(get_translation('ss_cta_sub')); ?>
    </p>
    <div style="display:flex;justify-content:center;flex-wrap:wrap;gap:14px;">
      <a href="<?php echo escape(site_url('contact.php')); ?>"
         style="display:inline-flex;align-items:center;gap:10px;background:#fff;color:#1d4ed8;
                font-weight:800;font-size:1rem;padding:16px 40px;border-radius:50px;
                text-decoration:none;box-shadow:0 6px 24px rgba(0,0,0,.2);transition:.3s;"
         onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform='none'">
        <i class="bi bi-calendar-check-fill"></i> <?php echo escape(get_translation('ss_book_survey')); ?>
      </a>
      <a href="<?php echo escape(site_url('SmartFleet.php')); ?>"
         style="display:inline-flex;align-items:center;gap:10px;
                background:rgba(255,255,255,.1);color:#fff;font-weight:700;font-size:.95rem;
                padding:16px 36px;border-radius:50px;text-decoration:none;
                border:1.5px solid rgba(255,255,255,.25);transition:.25s;"
         onmouseover="this.style.background='rgba(255,255,255,.18)'"
         onmouseout="this.style.background='rgba(255,255,255,.1)'">
        <i class="bi bi-truck-front-fill"></i> <?php echo escape(get_translation('ss_explore_fleet')); ?>
      </a>
    </div>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
