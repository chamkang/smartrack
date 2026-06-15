<?php
require_once __DIR__ . '/includes/functions.php';
init_session();

$pageTitle = t('About Us — Smartrack Africa', 'À Propos — Smartrack Africa');
$bodyClass = 'about-page';
$lang      = current_language();

define('APP_INIT', true);
include __DIR__ . '/includes/header.php';
?>

<!-- ══ Page Title ══════════════════════════════════════════════════════════ -->
<div class="page-title dark-background"
     style="background-image:url(<?php echo escape(site_url('assets/img/page-title-bg.jpg')); ?>);">
  <div class="container position-relative">
    <h1><?php echo escape(t('About Us','À Propos')); ?></h1>
    <p style="color:rgba(255,255,255,.75);margin-top:8px;font-size:1rem;">
      <?php echo escape(t("The team and story behind Africa's GPS & security platform.", "L'équipe et l'histoire de la plateforme GPS & sécurité d'Afrique.")); ?>
    </p>
    <nav class="breadcrumbs">
      <ol>
        <li><a href="<?php echo escape(site_url('index.php')); ?>"><?php echo escape(get_translation('breadcrumb_home')); ?></a></li>
        <li class="current"><?php echo escape(get_translation('breadcrumb_about')); ?></li>
      </ol>
    </nav>
  </div>
</div>

<style>
/* ── General ── */
.abt-section-tag {
  display:inline-block;background:rgba(230,0,0,.08);color:#e60000;
  font-size:.7rem;font-weight:700;padding:4px 14px;border-radius:20px;
  letter-spacing:.07em;text-transform:uppercase;margin-bottom:14px;
}

/* ── Story section ── */
.abt-story-img {
  border-radius:20px;overflow:hidden;position:relative;
  box-shadow:0 24px 80px rgba(0,0,0,.14);
}
.abt-story-img img { width:100%;height:460px;object-fit:cover;display:block; }
.abt-story-img .abt-badge {
  position:absolute;bottom:28px;left:28px;
  background:rgba(3,6,13,.85);backdrop-filter:blur(12px);
  border:1px solid rgba(255,255,255,.1);
  border-radius:14px;padding:16px 22px;color:#fff;
}
.abt-story-img .abt-badge span { font-size:2rem;font-weight:900;color:#e60000;line-height:1; }
.abt-story-img .abt-badge p   { font-size:.72rem;color:rgba(255,255,255,.55);margin:4px 0 0;
  text-transform:uppercase;letter-spacing:.07em; }

/* ── Values cards ── */
.abt-value-card {
  background:#fff;border-radius:16px;padding:32px 28px;
  border:1px solid #f0f0f0;height:100%;
  box-shadow:0 2px 20px rgba(0,0,0,.04);
  transition:all .3s;
  position:relative;overflow:hidden;
}
.abt-value-card::before {
  content:'';position:absolute;top:0;left:0;right:0;height:3px;
  background:var(--vcolor,#e60000);transform:scaleX(0);transform-origin:left;
  transition:transform .3s;
}
.abt-value-card:hover { transform:translateY(-6px);box-shadow:0 16px 48px rgba(0,0,0,.1); }
.abt-value-card:hover::before { transform:scaleX(1); }
.abt-value-icon {
  width:56px;height:56px;border-radius:14px;
  display:flex;align-items:center;justify-content:center;
  font-size:1.5rem;margin-bottom:18px;
}
.abt-value-card h4 { font-size:1rem;font-weight:800;color:#1a202c;margin-bottom:8px; }
.abt-value-card p  { font-size:.875rem;color:#64748b;line-height:1.75;margin:0; }

/* ── Timeline ── */
.abt-timeline { position:relative;padding-left:36px; }
.abt-timeline::before {
  content:'';position:absolute;left:10px;top:0;bottom:0;
  width:2px;background:linear-gradient(180deg,#e60000,rgba(230,0,0,.1));
}
.abt-timeline-item { position:relative;margin-bottom:36px; }
.abt-timeline-item:last-child { margin-bottom:0; }
.abt-timeline-dot {
  position:absolute;left:-30px;top:4px;
  width:20px;height:20px;border-radius:50%;
  background:#e60000;border:3px solid #fff;
  box-shadow:0 0 0 3px rgba(230,0,0,.2);
  transition:transform .3s;
}
.abt-timeline-item:hover .abt-timeline-dot { transform:scale(1.3); }
.abt-timeline-year {
  font-size:.72rem;font-weight:800;color:#e60000;
  text-transform:uppercase;letter-spacing:.1em;margin-bottom:4px;
}
.abt-timeline-item h5 { font-size:.95rem;font-weight:700;color:#1a202c;margin-bottom:4px; }
.abt-timeline-item p  { font-size:.855rem;color:#64748b;line-height:1.6;margin:0; }

/* ── Founder ── */
.abt-founder-photo {
  border-radius:20px;overflow:hidden;
  border:3px dashed rgba(230,0,0,.3);
  background:linear-gradient(135deg,#f8f9fb,#fff);
  display:flex;flex-direction:column;align-items:center;justify-content:center;
  min-height:440px;position:relative;
  transition:border-color .3s;
}
.abt-founder-photo:hover { border-color:rgba(230,0,0,.6); }
.abt-founder-photo .placeholder-ring {
  width:160px;height:160px;border-radius:50%;
  background:rgba(230,0,0,.06);border:2px dashed rgba(230,0,0,.25);
  display:flex;align-items:center;justify-content:center;
  margin-bottom:20px;
}
.abt-founder-photo .placeholder-ring i { font-size:3.5rem;color:rgba(230,0,0,.25); }
.abt-founder-photo .placeholder-text {
  font-size:.8rem;color:#aaa;font-weight:600;letter-spacing:.04em;
  text-align:center;line-height:1.6;
}
.abt-founder-quote {
  border-left:4px solid #e60000;padding:16px 22px;
  background:rgba(230,0,0,.04);border-radius:0 10px 10px 0;
  margin:20px 0;font-style:italic;font-size:.975rem;
  color:#374151;line-height:1.75;
}

/* ── Team cards ── */
.abt-team-card {
  background:#fff;border-radius:18px;overflow:hidden;
  box-shadow:0 2px 20px rgba(0,0,0,.06);
  transition:all .35s;text-align:center;
}
.abt-team-card:hover {
  transform:translateY(-8px) perspective(1000px) rotateX(2deg);
  box-shadow:0 20px 60px rgba(0,0,0,.12);
}
.abt-team-photo {
  height:240px;background:linear-gradient(135deg,#f1f5f9,#e2e8f0);
  display:flex;align-items:center;justify-content:center;
  position:relative;overflow:hidden;
}
.abt-team-photo img {
  width:100%;height:100%;object-fit:cover;object-position:top;display:block;
}
.abt-team-photo .ph-icon {
  font-size:4rem;color:#cbd5e1;
}
.abt-team-photo .abt-social-overlay {
  position:absolute;inset:0;background:rgba(230,0,0,.85);
  display:flex;align-items:center;justify-content:center;gap:14px;
  opacity:0;transition:opacity .3s;
}
.abt-team-card:hover .abt-social-overlay { opacity:1; }
.abt-social-overlay a {
  width:38px;height:38px;border-radius:50%;background:rgba(255,255,255,.15);
  display:flex;align-items:center;justify-content:center;
  color:#fff;font-size:1rem;text-decoration:none;
  transition:background .2s;
}
.abt-social-overlay a:hover { background:rgba(255,255,255,.3); }
.abt-team-body { padding:22px; }
.abt-team-body h4 { font-size:1rem;font-weight:800;color:#1a202c;margin-bottom:4px; }
.abt-team-body span { font-size:.78rem;font-weight:700;color:#e60000;
  text-transform:uppercase;letter-spacing:.06em; }
.abt-team-body p { font-size:.84rem;color:#64748b;line-height:1.6;margin:10px 0 0; }

/* ── Stats ── */
.abt-stat-card {
  text-align:center;padding:36px 20px;
  background:#fff;border-radius:16px;
  box-shadow:0 2px 20px rgba(0,0,0,.05);
  border-bottom:4px solid transparent;
  transition:all .3s;
}
.abt-stat-card:hover { transform:translateY(-5px);box-shadow:0 12px 40px rgba(0,0,0,.1); }
.abt-stat-val {
  font-size:2.8rem;font-weight:900;line-height:1;
  display:block;margin-bottom:6px;
}
.abt-stat-lbl { font-size:.75rem;font-weight:700;color:#888;
  text-transform:uppercase;letter-spacing:.08em; }
</style>

<!-- ══ § 1  OUR STORY ══════════════════════════════════════════════════════ -->
<section class="section" style="background:#fff;padding-top:80px;">
  <div class="container">
    <div class="row align-items-center gy-5">

      <!-- Image col -->
      <div class="col-lg-5" data-aos="fade-right">
        <div class="abt-story-img">
          <img src="<?php echo escape(site_url('assets/img/implantation-SMARTRACK-AFRICA-1.png')); ?>"
               alt="Smartrack Africa">
          <div class="abt-badge">
            <span>2018</span>
            <p><?php echo escape(get_translation('about_founded')); ?></p>
          </div>
        </div>
      </div>

      <!-- Text col -->
      <div class="col-lg-6 offset-lg-1" data-aos="fade-left" data-aos-delay="100">
        <div class="abt-section-tag"><?php echo escape(get_translation('about_our_story')); ?></div>
        <h2 style="font-size:clamp(1.8rem,3.5vw,2.6rem);font-weight:900;color:#1a202c;
                   line-height:1.2;letter-spacing:-.03em;margin-bottom:18px;">
          <?php echo t('Built for Africa.<br>Designed to last.', "Conçu pour l'Afrique.<br>Fait pour durer."); ?>
        </h2>
        <p style="font-size:1rem;color:#475569;line-height:1.85;margin-bottom:18px;">
          <?php echo escape(get_translation('about_hero_p1')); ?>
        </p>
        <p style="font-size:1rem;color:#475569;line-height:1.85;margin-bottom:28px;">
          <?php echo escape(get_translation('about_hero_p2')); ?>
        </p>

        <!-- Mission / Vision pills -->
        <div class="row g-3">
          <div class="col-sm-6">
            <div style="background:#fff9f9;border:1px solid rgba(230,0,0,.12);border-radius:12px;padding:18px;">
              <div style="font-size:.7rem;font-weight:800;color:#e60000;text-transform:uppercase;
                          letter-spacing:.08em;margin-bottom:6px;"><?php echo escape(get_translation('about_mission_lbl')); ?></div>
              <p style="font-size:.875rem;color:#374151;line-height:1.6;margin:0;">
                <?php echo escape(get_translation('about_mission_desc')); ?>
              </p>
            </div>
          </div>
          <div class="col-sm-6">
            <div style="background:#f0fdf4;border:1px solid rgba(34,197,94,.15);border-radius:12px;padding:18px;">
              <div style="font-size:.7rem;font-weight:800;color:#16a34a;text-transform:uppercase;
                          letter-spacing:.08em;margin-bottom:6px;"><?php echo escape(get_translation('about_vision_lbl')); ?></div>
              <p style="font-size:.875rem;color:#374151;line-height:1.6;margin:0;">
                <?php echo escape(get_translation('about_vision_desc')); ?>
              </p>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- ══ § 2  STATS ═══════════════════════════════════════════════════════════ -->
<section class="section" style="background:#f8f9fb;">
  <div class="container">
    <div class="row g-4">
      <?php
      $abtStats = [
        ['232', 'Clients Served',     'bi-emoji-smile-fill',    '#e60000'],
        ['521', 'Projects Delivered', 'bi-journal-check',       '#3b82f6'],
        ['1463','Support Hours',      'bi-headset',             '#22c55e'],
        ['19',  'Team Members',       'bi-people-fill',         '#f59e0b'],
      ];
      foreach ($abtStats as $i => [$val,$lbl,$icon,$col]):
      ?>
        <div class="col-6 col-lg-3" data-aos="fade-up" data-aos-delay="<?php echo $i*80; ?>">
          <div class="abt-stat-card" style="border-bottom-color:<?php echo $col; ?>;">
            <i class="bi <?php echo $icon; ?>" style="font-size:2rem;color:<?php echo $col; ?>;
               margin-bottom:12px;display:block;"></i>
            <span class="abt-stat-val purecounter"
                  style="color:<?php echo $col; ?>;"
                  data-purecounter-start="0"
                  data-purecounter-end="<?php echo $val; ?>"
                  data-purecounter-duration="1"><?php echo $val; ?></span>
            <div class="abt-stat-lbl"><?php echo $lbl; ?></div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ══ § 3  VALUES ══════════════════════════════════════════════════════════ -->
<section class="section" style="background:#fff;">
  <div class="container">
    <div class="section-title" data-aos="fade-up">
      <h2><?php echo escape(get_translation('about_values_title')); ?></h2>
      <p><?php echo escape(get_translation('about_values_sub')); ?></p>
    </div>
    <div class="row g-4">
      <?php
      $values = [
        ['bi-geo-alt-fill',      '#e60000',
          t('Local Expertise',    'Expertise Locale'),
          t("We are based in Douala. Our engineers, support staff, and technicians know Cameroon's roads, infrastructure, and business environment intimately.",
            "Nous sommes basés à Douala. Nos ingénieurs, notre équipe de support et nos techniciens connaissent intimement les routes, l'infrastructure et l'environnement commercial du Cameroun.")],
        ['bi-shield-fill-check', '#22c55e',
          t('Reliability First',  'La Fiabilité Avant Tout'),
          t("Every device we install is built to withstand heat, humidity, and rough roads. We don't deploy until we are confident it will perform.",
            "Chaque appareil que nous installons est conçu pour résister à la chaleur, l'humidité et les routes difficiles. Nous ne déployons pas tant que nous ne sommes pas confiants en ses performances.")],
        ['bi-translate',         '#3b82f6',
          t('Bilingual by Design','Bilingue par Conception'),
          t('Smartrack operates fully in both French and English. Every client, every report, every support call — we meet you in your language.',
            'Smartrack fonctionne entièrement en français et en anglais. Chaque client, chaque rapport, chaque appel de support — nous vous rejoignons dans votre langue.')],
        ['bi-graph-up-arrow',    '#f59e0b',
          t('Measurable ROI',    'ROI Mesurable'),
          t('We tie our success to yours. Most clients see measurable fuel and cost savings within the first 30 days of deployment.',
            'Nous lions notre succès au vôtre. La plupart des clients constatent des économies mesurables sur le carburant et les coûts dans les 30 premiers jours.')],
      ];
      foreach ($values as $i => [$icon,$col,$title,$desc]):
      ?>
        <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="<?php echo $i*80; ?>">
          <div class="abt-value-card" style="--vcolor:<?php echo $col; ?>;">
            <div class="abt-value-icon"
                 style="background:<?php echo $col; ?>12;color:<?php echo $col; ?>;">
              <i class="bi <?php echo $icon; ?>"></i>
            </div>
            <h4><?php echo $title; ?></h4>
            <p><?php echo $desc; ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ══ § 4  FOUNDER ═════════════════════════════════════════════════════════ -->
<section class="section" style="background:linear-gradient(135deg,#f8f9fb 0%,#fff 100%);">
  <div class="container">
    <div class="section-title" data-aos="fade-up">
      <h2><?php echo escape(get_translation('about_founder_title')); ?></h2>
      <p><?php echo escape(get_translation('about_founder_sub')); ?></p>
    </div>

    <div class="row align-items-center gy-5">

      <!-- Photo placeholder -->
      <div class="col-lg-4" data-aos="fade-right">
        <div class="abt-founder-photo">
          <div class="placeholder-ring">
            <i class="bi bi-person-fill"></i>
          </div>
          <div class="placeholder-text">
            <strong style="color:#bbb;"><?php echo escape(get_translation('about_founder_photo')); ?></strong><br>
            <?php echo escape(get_translation('about_founder_upload')); ?>
          </div>
          <!-- Replace the placeholder block above with an <img> tag once you have the photo -->
          <!-- <img src="assets/img/founder.jpg" alt="Founder" style="width:100%;height:100%;object-fit:cover;"> -->
        </div>
      </div>

      <!-- Bio col -->
      <div class="col-lg-7 offset-lg-1" data-aos="fade-left" data-aos-delay="100">
        <div class="abt-section-tag"><?php echo escape(get_translation('about_founder_ceo_tag')); ?></div>
        <h2 style="font-size:clamp(1.6rem,3vw,2.2rem);font-weight:900;color:#1a202c;
                   margin-bottom:4px;letter-spacing:-.02em;">
          <?php echo escape(t('[Founder Name]','[Nom du Fondateur]')); ?>
        </h2>
        <p style="font-size:.82rem;font-weight:700;color:#e60000;text-transform:uppercase;
                  letter-spacing:.08em;margin-bottom:20px;">
          <?php echo escape(get_translation('about_founder_ceo_lbl')); ?>
        </p>

        <div class="abt-founder-quote">
          <?php echo escape(t(
            '"Africa does not need cheaper versions of Western technology — it needs solutions built from the ground up for its roads, its climate, and its people. That is exactly what Smartrack is."',
            '"L\'Afrique n\'a pas besoin de versions moins chères de la technologie occidentale — elle a besoin de solutions conçues de zéro pour ses routes, son climat et ses populations. C\'est exactement ce qu\'est Smartrack."'
          )); ?>
        </div>

        <p style="font-size:.975rem;color:#475569;line-height:1.85;margin-bottom:16px;">
          <?php echo escape(t(
            "With a background in telecommunications and embedded systems engineering, [Founder Name] identified a critical gap in Central Africa's fleet management landscape — businesses were either going without tracking entirely, or paying for imported solutions that lacked local support and broke down in the field.",
            "Fort d'une formation en télécommunications et en systèmes embarqués, [Nom du Fondateur] a identifié un manque critique dans le paysage de la gestion de flotte en Afrique centrale — les entreprises se passaient totalement de suivi, ou payaient pour des solutions importées sans support local qui tombaient en panne sur le terrain."
          )); ?>
        </p>
        <p style="font-size:.975rem;color:#475569;line-height:1.85;margin-bottom:28px;">
          <?php echo escape(t(
            "He founded Smartrack Africa in Douala with a commitment to building hardware that performs in Africa's conditions and software that any business owner — whether in Bonanjo or Bafoussam — could understand and use from day one.",
            "Il a fondé Smartrack Africa à Douala avec l'engagement de développer du matériel performant dans les conditions africaines et un logiciel que tout chef d'entreprise — à Bonanjo ou à Bafoussam — puisse comprendre et utiliser dès le premier jour."
          )); ?>
        </p>

        <!-- Credentials -->
        <div class="row g-3">
          <?php
          $credentials = [
            ['bi-mortarboard-fill','#3b82f6', t('Engineering Background','Formation en Ingénierie'),  t('Telecommunications & embedded systems','Télécommunications & systèmes embarqués')],
            ['bi-geo-alt-fill',    '#e60000', t('Based in Douala','Basé à Douala'),                   t('Operating across Central Africa','Opérant dans toute l\'Afrique Centrale')],
            ['bi-award-fill',      '#f59e0b', t('7+ Years in Tech','7+ Ans dans la Tech'),            t('Fleet & security technology specialist','Spécialiste des technologies de flotte & sécurité')],
          ];
          foreach ($credentials as [$ic,$col,$ct,$cs]):
          ?>
            <div class="col-sm-4">
              <div style="display:flex;align-items:flex-start;gap:10px;">
                <div style="width:36px;height:36px;border-radius:10px;flex-shrink:0;
                            background:<?php echo $col; ?>12;color:<?php echo $col; ?>;
                            display:flex;align-items:center;justify-content:center;font-size:1rem;">
                  <i class="bi <?php echo $ic; ?>"></i>
                </div>
                <div>
                  <div style="font-size:.78rem;font-weight:700;color:#1a202c;"><?php echo $ct; ?></div>
                  <div style="font-size:.72rem;color:#94a3b8;"><?php echo $cs; ?></div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- ══ § 5  JOURNEY / TIMELINE ══════════════════════════════════════════════ -->
<section class="section" style="background:linear-gradient(135deg,#0b0e1a 0%,#111622 100%);">
  <div class="container">
    <div class="row gy-5 align-items-center">

      <div class="col-lg-4" data-aos="fade-right">
        <div class="abt-section-tag" style="background:rgba(230,0,0,.2);color:#ff6666;">
          <?php echo escape(get_translation('about_journey_tag')); ?>
        </div>
        <h2 style="font-size:clamp(1.6rem,3vw,2.4rem);font-weight:900;color:#fff;
                   line-height:1.2;letter-spacing:-.03em;margin-bottom:16px;">
          <?php echo escape(get_translation('about_journey_title')); ?>
        </h2>
        <p style="color:rgba(255,255,255,.55);font-size:.95rem;line-height:1.8;">
          <?php echo escape(get_translation('about_journey_sub')); ?>
        </p>
      </div>

      <div class="col-lg-7 offset-lg-1" data-aos="fade-left" data-aos-delay="100">
        <div class="abt-timeline">
          <?php
          $milestones = [
            ['2018',
              t('Company Founded',          'Fondation de l\'Entreprise'),
              t('Smartrack Africa was incorporated in Douala with a founding team of 3 engineers and a mission to bring reliable fleet tracking to Cameroon.',
                "Smartrack Africa a été créée à Douala avec une équipe fondatrice de 3 ingénieurs et la mission d'apporter un suivi de flotte fiable au Cameroun.")],
            ['2019',
              t('First 50 Clients',          'Premiers 50 Clients'),
              t('Rapid adoption among logistics companies and NGOs in the Douala-Yaoundé corridor. First dedicated support centre opened.',
                "Adoption rapide parmi les sociétés de logistique et les ONG dans le corridor Douala-Yaoundé. Ouverture du premier centre de support dédié.")],
            ['2020',
              t('Fuel Monitoring Launched',  'Lancement du Suivi Carburant'),
              t('Expanded the platform to include precision fuel-level sensors — immediately reducing fuel theft for 30+ clients within the first quarter.',
                "Extension de la plateforme avec des capteurs de niveau de carburant de précision — réduction immédiate du vol de carburant pour 30+ clients dès le premier trimestre.")],
            ['2021',
              t('Security Division Added',   'Division Sécurité Ajoutée'),
              t('Launched SmartSolution: access control, video surveillance, fire detection and network security for commercial premises.',
                'Lancement de SmartSolution : contrôle d\'accès, vidéosurveillance, détection d\'incendie et sécurité réseau pour les locaux commerciaux.')],
            ['2023',
              t('Bilingual Platform',        'Plateforme Bilingue'),
              t('Full French/English dashboard and mobile app launched. Expanded operations to Bafoussam and Yaoundé with local technician teams.',
                "Tableau de bord et application mobile entièrement en français/anglais lancés. Extension des opérations à Bafoussam et Yaoundé avec des équipes de techniciens locaux.")],
            ['2024',
              t('SmartBeacon Range',         'Gamme SmartBeacon'),
              t('Introduced the SIM-free BLE beacon range for asset tracking — no subscription required. Deployed across 40+ warehouses and construction sites.',
                "Introduction de la gamme de balises BLE sans SIM pour le suivi des actifs — sans abonnement requis. Déployée dans 40+ entrepôts et chantiers de construction.")],
          ];
          foreach ($milestones as $i => [$year,$title,$desc]):
          ?>
            <div class="abt-timeline-item" data-aos="fade-up" data-aos-delay="<?php echo $i*60; ?>">
              <div class="abt-timeline-dot"></div>
              <div class="abt-timeline-year"><?php echo $year; ?></div>
              <h5><?php echo $title; ?></h5>
              <p><?php echo $desc; ?></p>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- ══ § 6  TEAM ════════════════════════════════════════════════════════════ -->
<section class="section" style="background:#f8f9fb;">
  <div class="container">
    <div class="section-title" data-aos="fade-up">
      <h2><?php echo escape(get_translation('about_team_title')); ?></h2>
      <p><?php echo escape(get_translation('about_team_sub')); ?></p>
    </div>

    <div class="row g-4">
      <?php
      $team = [
        ['', t('Chief Executive Officer',      'Directeur Général'),
             t("Leading Smartrack's vision and strategic growth across Central Africa.", "Pilote la vision et la croissance stratégique de Smartrack en Afrique centrale.")],
        ['', t('Chief Technology Officer',     'Directeur Technique'),
             t('Building and scaling the tracking platform and hardware ecosystem.', "Développe et fait évoluer la plateforme de suivi et l'écosystème matériel.")],
        ['', t('Head of Field Operations',     'Responsable des Opérations Terrain'),
             t('Managing all device installations, site surveys, and technical teams.', "Gère toutes les installations d'appareils, les inspections de sites et les équipes techniques.")],
        ['', t('Product & UX Lead',            'Responsable Produit & UX'),
             t('Designing the dashboard and mobile experience our clients rely on daily.', "Conçoit le tableau de bord et l'expérience mobile sur lesquels nos clients comptent chaque jour.")],
        ['', t('Sales & Partnerships Manager', 'Responsable Ventes & Partenariats'),
             t('Growing our client base and building partnerships across the region.', "Développe notre base de clients et construit des partenariats dans toute la région.")],
        ['', t('Customer Success Lead',        'Responsable Succès Client'),
             t('Ensuring every client extracts full value from their Smartrack deployment.', "S'assure que chaque client tire pleinement parti de son déploiement Smartrack.")],
      ];
      foreach ($team as $i => [$photo, $role, $desc]):
      ?>
        <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="<?php echo ($i%3)*100; ?>">
          <div class="abt-team-card">

            <div class="abt-team-photo">
              <?php if ($photo): ?>
                <img src="<?php echo escape(site_url($photo)); ?>" alt="<?php echo escape($role); ?>">
              <?php else: ?>
                <i class="bi bi-person-fill ph-icon"></i>
              <?php endif; ?>

              <!-- Social overlay on hover -->
              <div class="abt-social-overlay">
                <a href="#" title="LinkedIn"><i class="bi bi-linkedin"></i></a>
                <a href="#" title="Twitter"><i class="bi bi-twitter-x"></i></a>
                <a href="#" title="Email"><i class="bi bi-envelope-fill"></i></a>
              </div>
            </div>

            <div class="abt-team-body">
              <h4><?php echo escape(get_translation('about_team_member')); ?></h4>
              <span><?php echo escape($role); ?></span>
              <p><?php echo escape($desc); ?></p>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <p class="text-center mt-5" style="color:#94a3b8;font-size:.875rem;" data-aos="fade-up">
      <i class="bi bi-camera-fill me-2" style="color:#e60000;"></i>
      <?php echo escape(get_translation('about_photos_note')); ?>
    </p>
  </div>
</section>

<!-- ══ § 7  WHY AFRICA ══════════════════════════════════════════════════════ -->
<section class="section" style="background:#fff;">
  <div class="container">
    <div class="row align-items-center gy-5">
      <div class="col-lg-5" data-aos="fade-right">
        <div class="abt-section-tag"><?php echo escape(get_translation('about_focus_tag')); ?></div>
        <h2 style="font-size:clamp(1.6rem,3vw,2.4rem);font-weight:900;color:#1a202c;
                   line-height:1.2;letter-spacing:-.03em;margin-bottom:16px;">
          <?php echo escape(get_translation('about_focus_title')); ?>
        </h2>
        <p style="font-size:1rem;color:#475569;line-height:1.85;margin-bottom:16px;">
          <?php echo escape(get_translation('about_focus_p1')); ?>
        </p>
        <p style="font-size:1rem;color:#475569;line-height:1.85;">
          <?php echo escape(get_translation('about_focus_p2')); ?>
        </p>
      </div>
      <div class="col-lg-6 offset-lg-1" data-aos="fade-left" data-aos-delay="100">
        <div class="row g-3">
          <?php
          $afritags = [
            ['bi-wifi-off',          '#e60000',
              t('Offline-resilient devices',   'Appareils résistants hors-ligne'),
              t('Data stored locally when there is no signal and synced automatically.', "Données stockées localement en l'absence de signal et synchronisées automatiquement.")],
            ['bi-thermometer-high',  '#f59e0b',
              t('Built for tropical climates', 'Conçu pour les climats tropicaux'),
              t('Hardware rated for high heat and humidity — tested in field conditions.', 'Matériel résistant à la chaleur et à l\'humidité élevées — testé sur le terrain.')],
            ['bi-translate',         '#3b82f6',
              t('French & English support',    'Support Français & Anglais'),
              t('Bilingual team, bilingual dashboard, bilingual documentation.', 'Équipe bilingue, tableau de bord bilingue, documentation bilingue.')],
            ['bi-tools',             '#22c55e',
              t('Local technician network',    'Réseau de Techniciens Locaux'),
              t('Certified installers based in Douala, Yaoundé, and Bafoussam.', 'Installateurs certifiés basés à Douala, Yaoundé et Bafoussam.')],
            ['bi-currency-exchange', '#8b5cf6',
              t('Africa-aligned pricing',      'Tarification Adaptée à l\'Afrique'),
              t('Subscription tiers designed for African SME budgets — not European enterprise.', 'Formules conçues pour les budgets des PME africaines — pas pour les grandes entreprises européennes.')],
            ['bi-headset',           '#06b6d4',
              t('24/7 local helpdesk',         'Assistance Locale 24h/7j'),
              t('You speak to someone in Cameroon, not an overseas call centre.', 'Vous parlez à quelqu\'un au Cameroun, pas à un centre d\'appels à l\'étranger.')],
          ];
          foreach ($afritags as [$icon,$col,$atitle,$adesc]):
          ?>
            <div class="col-sm-6">
              <div style="display:flex;align-items:flex-start;gap:12px;padding:14px;
                          background:#f8f9fb;border-radius:12px;border:1px solid #f0f0f0;
                          transition:all .25s;"
                   onmouseover="this.style.background='#fff';this.style.boxShadow='0 4px 20px rgba(0,0,0,.07)'"
                   onmouseout="this.style.background='#f8f9fb';this.style.boxShadow='none'">
                <div style="width:36px;height:36px;border-radius:9px;flex-shrink:0;
                            background:<?php echo $col; ?>12;color:<?php echo $col; ?>;
                            display:flex;align-items:center;justify-content:center;font-size:1rem;">
                  <i class="bi <?php echo $icon; ?>"></i>
                </div>
                <div>
                  <div style="font-size:.82rem;font-weight:700;color:#1a202c;margin-bottom:2px;"><?php echo $atitle; ?></div>
                  <div style="font-size:.78rem;color:#64748b;line-height:1.5;"><?php echo $adesc; ?></div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ══ § 8  CTA ══════════════════════════════════════════════════════════════ -->
<section style="background:linear-gradient(135deg,#c40000,#8b0000);padding:80px 0;
                position:relative;overflow:hidden;">
  <div style="position:absolute;top:-60px;right:-60px;width:300px;height:300px;
              border-radius:50%;background:rgba(255,255,255,.04);"></div>
  <div class="container text-center position-relative" data-aos="fade-up">
    <h2 style="font-size:clamp(1.6rem,3vw,2.4rem);font-weight:900;color:#fff;
               margin-bottom:12px;letter-spacing:-.02em;">
      <?php echo escape(get_translation('about_cta_title')); ?>
    </h2>
    <p style="color:rgba(255,255,255,.75);font-size:1rem;max-width:500px;
              margin:0 auto 32px;line-height:1.8;">
      <?php echo escape(get_translation('about_cta_sub')); ?>
    </p>
    <div style="display:flex;justify-content:center;flex-wrap:wrap;gap:14px;">
      <a href="<?php echo escape(site_url('contact.php')); ?>"
         style="display:inline-flex;align-items:center;gap:10px;background:#fff;color:#c40000;
                font-weight:800;font-size:1rem;padding:16px 40px;border-radius:50px;
                text-decoration:none;box-shadow:0 6px 24px rgba(0,0,0,.2);transition:all .25s;"
         onmouseover="this.style.transform='translateY(-3px)'"
         onmouseout="this.style.transform='translateY(0)'">
        <i class="bi bi-file-earmark-text-fill"></i> <?php echo escape(get_translation('about_get_quote')); ?>
      </a>
      <a href="<?php echo escape(site_url('devices.php')); ?>"
         style="display:inline-flex;align-items:center;gap:10px;
                background:rgba(255,255,255,.12);color:#fff;font-weight:700;font-size:.95rem;
                padding:16px 36px;border-radius:50px;text-decoration:none;
                border:1.5px solid rgba(255,255,255,.3);transition:all .25s;"
         onmouseover="this.style.background='rgba(255,255,255,.2)'"
         onmouseout="this.style.background='rgba(255,255,255,.12)'">
        <i class="bi bi-cpu-fill"></i> <?php echo escape(get_translation('about_view_devices')); ?>
      </a>
    </div>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
