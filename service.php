<?php
require_once __DIR__ . '/includes/functions.php';

$lang      = current_language();
$serviceId = intval($_GET['id'] ?? 0);

if ($serviceId === 0) { redirect(site_url('index.php#services')); }

$stmt = db()->prepare('SELECT * FROM services WHERE id = ? LIMIT 1');
$stmt->execute([$serviceId]);
$service = $stmt->fetch();

if (!$service) {
    http_response_code(404);
    $pageTitle = 'Not Found'; $bodyClass = 'service-details-page';
    define('APP_INIT', true);
    include __DIR__ . '/includes/header.php';
    echo '<div class="container py-5"><h1>Service not found.</h1><a href="' . escape(site_url('index.php')) . '">Back to home</a></div>';
    include __DIR__ . '/includes/footer.php';
    exit;
}

$title   = $service['title_'   . $lang] ?: $service['title_en'];
$summary = $service['summary_' . $lang] ?: $service['summary_en'];
$content = $service['content_' . $lang] ?: ($service['content_en'] ?? '');

// ─── Rich per-service data (keyed by slug) ────────────────────────────────────
$serviceDetails = [

  'vehicle-tracking' => [
    'tagline'   => t('Know where every vehicle is — at all times.', 'Sachez où est chaque véhicule — à tout moment.'),
    'hero_icon' => 'bi-geo-alt-fill',
    'hero_color'=> '#e60000',
    'image'     => 'assets/img/GPS Tracking _ Vehicle Tracking System Ireland Landing Page.jpg',
    'features'  => [
      ['bi-geo-alt-fill',       '#e60000', t('Live GPS Positions',         'Positions GPS en Direct'),         t('Refresh every 30 seconds. View the entire fleet on a single map with colour-coded status indicators.',                                          'Actualisées toutes les 30 secondes. Visualisez toute la flotte sur une carte unique avec des indicateurs de statut codés par couleur.')],
      ['bi-clock-history',      '#3b82f6', t('Full Trip History & Replay', 'Historique et Replay de Trajets'), t('Review any journey in detail — start, stop, route taken, stops made, and engine-on/off events.',                                              'Revoyez chaque trajet en détail — départ, arrêt, itinéraire emprunté, haltes et événements moteur allumé/éteint.')],
      ['bi-geo-fill',           '#f59e0b', t('Geofencing Zones',           'Zones de Géorepérage'),            t('Draw unlimited virtual perimeters. Get instant SMS or app push alerts the moment a vehicle enters or leaves.',                                 "Tracez des périmètres virtuels illimités. Recevez des alertes SMS ou push instantanées dès qu'un véhicule entre ou sort d'une zone.")],
      ['bi-speedometer2',       '#8b5cf6', t('Speed & Behaviour Alerts',   'Alertes Vitesse et Comportement'), t('Set speed thresholds per vehicle. Automatic reports flag harsh braking, sharp cornering, and excessive idling.',                               "Définissez des seuils de vitesse par véhicule. Des rapports automatiques signalent les freinages brusques, virages serrés et ralenti excessif.")],
      ['bi-person-badge-fill',  '#22c55e', t('Driver Identification',      'Identification du Conducteur'),    t('Pair each journey with a specific driver via ID card, key fob or PIN — full accountability across your team.',                                 "Associez chaque trajet à un conducteur spécifique via carte d'identité, badge ou PIN — responsabilité totale dans votre équipe.")],
      ['bi-wifi-off',           '#06b6d4', t('Offline Data Storage',       'Stockage Hors Ligne'),             t('Device caches up to 30 days of data locally. Everything syncs automatically when connectivity resumes.',                                      "L'appareil met en cache jusqu'à 30 jours de données en local. Tout se synchronise automatiquement dès que la connectivité est rétablie.")],
    ],
    'stats' => [
      ['30 s',     t('Live Refresh Rate',      'Taux d\'Actualisation')],
      ['99.7%',    t('Platform Uptime',        'Disponibilité Plateforme')],
      ['30%',      t('Avg. Theft Reduction',   'Réduction Vol Moy.')],
      ['≤ 30 min', t('Installation Time',      'Temps d\'Installation')],
    ],
    'benefits' => [
      t('Eliminate ghost mileage and unauthorized personal use.',         'Éliminez les kilomètres fantômes et l\'usage personnel non autorisé.'),
      t('Build watertight documentation for insurance claims.',           'Constituez des preuves solides pour les déclarations d\'assurance.'),
      t('Cut overtime abuse with verified start/end timestamps.',         'Réduisez les abus d\'heures supplémentaires grâce aux horodatages vérifiés.'),
      t('Dispatch the nearest available vehicle — not the default one.',  'Envoyez le véhicule disponible le plus proche — pas celui par défaut.'),
      t('Reduce maintenance costs with accurate odometer data.',          'Réduisez les coûts de maintenance avec des données d\'odomètre précises.'),
    ],
    'steps' => [
      ['01', 'bi-cpu-fill',       t('Device Installed',      'Appareil Installé'),        t('A certified Smartrack technician fits a discreet OBD or hardwired tracker in under 30 minutes — no downtime.',           "Un technicien certifié Smartrack pose un traceur OBD ou câblé discret en moins de 30 minutes — sans interruption d'activité.")],
      ['02', 'bi-cloud-arrow-up', t('Connected to Platform', 'Connecté à la Plateforme'), t('The device registers automatically. You receive your dashboard login credentials within 1 hour.',                         'L\'appareil s\'enregistre automatiquement. Vous recevez vos identifiants de connexion dans l\'heure.')],
      ['03', 'bi-phone-fill',     t('Monitor Anywhere',      'Surveillez Partout'),        t('Log in from any browser or mobile. Alerts land directly in your inbox and on your phone, 24/7.',                          'Connectez-vous depuis n\'importe quel navigateur ou mobile. Les alertes arrivent dans votre boîte mail et sur votre téléphone, 24h/24.')],
    ],
    'faq' => [
      [t('Can drivers tell the device is there?',          'Les conducteurs peuvent-ils voir l\'appareil ?'),          t('The tracker is compact and typically installed behind a panel. You decide whether to disclose it — both transparent and covert deployments are valid.',   'Le traceur est compact et généralement installé derrière un panneau. C\'est vous qui décidez de le divulguer ou non — les déploiements transparents et discrets sont tous deux valides.')],
      [t('What happens if there is no mobile signal?',     'Que se passe-t-il en cas d\'absence de réseau mobile ?'), t('The unit stores all data onboard for up to 30 days and uploads everything the moment a network is available.',                                               "L'appareil stocke toutes les données en local jusqu'à 30 jours et les télécharge dès qu'un réseau est disponible.")],
      [t('Is the data secure?',                            'Les données sont-elles sécurisées ?'),                     t('All telemetry is transmitted over encrypted HTTPS and stored on our hardened servers. Only authorised users in your account can access it.',              'Toutes les données sont transmises via HTTPS chiffré et stockées sur nos serveurs sécurisés. Seuls les utilisateurs autorisés de votre compte peuvent y accéder.')],
      [t('Can I track motorbikes or heavy trucks too?',    'Puis-je suivre des motos ou des poids lourds ?'),          t('Yes. Our device range covers passenger cars, motorbikes, minibuses, trucks, and construction equipment.',                                                  'Oui. Notre gamme couvre les voitures de tourisme, motos, minibus, camions et engins de chantier.')],
    ],
  ],

  'fuel-monitoring' => [
    'tagline'   => t('Cut fuel costs by up to 30% — starting month one.', 'Réduisez les coûts de carburant jusqu\'à 30% — dès le premier mois.'),
    'hero_icon' => 'bi-fuel-pump-fill',
    'hero_color'=> '#f59e0b',
    'image'     => 'assets/img/How IoT technology helps transportation and logistics industry.jpg',
    'features'  => [
      ['bi-fuel-pump-fill',            '#f59e0b', t('Precision Level Sensors',     'Capteurs de Niveau de Précision'),    t('Capacitive fuel-level probes measure tank content with ±1% accuracy — far better than dashboard gauges.',                                       'Des sondes capacitives mesurent le contenu du réservoir avec une précision de ±1% — bien supérieure aux jauges du tableau de bord.')],
      ['bi-graph-down-arrow',          '#e60000', t('Consumption per Trip/Driver', 'Consommation par Trajet/Conducteur'), t('Break down fuel use by vehicle, driver, route, and time period to spot every inefficiency instantly.',                                           "Décomposez l'utilisation du carburant par véhicule, conducteur, route et période pour identifier chaque inefficacité instantanément.")],
      ['bi-exclamation-triangle-fill', '#e60000', t('Theft & Drain Alerts',        'Alertes de Vol et de Siphonnage'),    t('Sudden level drops outside of a refuel event trigger an immediate SMS or push notification.',                                                    'Les baisses soudaines de niveau en dehors d\'un ravitaillement déclenchent une notification SMS ou push immédiate.')],
      ['bi-receipt',                   '#22c55e', t('Automated Fuel Reports',       'Rapports Carburant Automatisés'),    t('Weekly PDF summaries show consumption trends, refuel events, and savings versus the previous period.',                                            'Des résumés PDF hebdomadaires montrent les tendances de consommation, événements de ravitaillement et économies par rapport à la période précédente.')],
      ['bi-bar-chart-line-fill',       '#3b82f6', t('Comparative Analytics',        'Analyses Comparatives'),             t('Benchmark vehicles against each other. Identify which drivers are costing you the most.',                                                         'Comparez les véhicules entre eux. Identifiez quels conducteurs vous coûtent le plus.')],
      ['bi-link-45deg',                '#8b5cf6', t('GPS Integration',              'Intégration GPS'),                   t('Correlate fuel consumption with route length, terrain, and idle time for full context on every litre spent.',                                      'Corrélez la consommation de carburant avec la distance, le terrain et le temps de ralenti pour un contexte complet sur chaque litre dépensé.')],
    ],
    'stats' => [
      ['30%',      t('Avg. Fuel Savings',    'Économies Carburant Moy.')],
      ['±1%',      t('Sensor Accuracy',      'Précision du Capteur')],
      ['< 1 min',  t('Theft Alert Speed',    'Délai Alerte Vol')],
      ['5 000 L',  t('Monitored Daily',      'Surveillés/Jour')],
    ],
    'benefits' => [
      t('Eliminate fuel theft by drivers, attendants, or third parties.',   'Éliminez le vol de carburant par les conducteurs, préposés ou tiers.'),
      t('Reduce idle engine time — a leading cause of wasted fuel.',        "Réduisez le temps de ralenti du moteur — principale cause de gaspillage."),
      t('Optimise routes to lower consumption per kilometre.',              'Optimisez les itinéraires pour réduire la consommation par kilomètre.'),
      t('Produce accurate fuel budgets backed by real data.',               'Produisez des budgets carburant précis basés sur des données réelles.'),
      t("Lower your fleet's carbon footprint with measurable KPIs.",       "Réduisez l'empreinte carbone de votre flotte avec des KPIs mesurables."),
    ],
    'steps' => [
      ['01', 'bi-tools',          t('Sensor Fitted',       'Capteur Installé'),         t('A precision capacitive probe is installed in the fuel tank (or a non-invasive sensor for some models) by our certified team.',   'Une sonde capacitive de précision est installée dans le réservoir (ou un capteur non intrusif pour certains modèles) par notre équipe certifiée.')],
      ['02', 'bi-toggles-fill',   t('Calibrated & Tested', 'Calibré et Testé'),         t('We calibrate to your exact tank geometry and run a fill-drain cycle to verify accuracy before handing over.',                    'Nous calibrons selon la géométrie exacte de votre réservoir et effectuons un cycle de remplissage/vidange pour vérifier la précision avant remise.')],
      ['03', 'bi-graph-up-arrow', t('Savings Begin',       'Les Économies Commencent'), t('Log in to your dashboard and watch real-time fuel levels, daily consumption, and instant theft alerts from day one.',            'Connectez-vous à votre tableau de bord et observez en temps réel les niveaux, la consommation journalière et les alertes de vol dès le premier jour.')],
    ],
    'faq' => [
      [t('Do I need to modify the fuel tank?',                        'Dois-je modifier le réservoir de carburant ?'),                    t('For most vehicles we install a non-intrusive capacitive sensor along the exterior of the tank. A full probe insert is only used when required for accuracy.',  "Pour la plupart des véhicules, nous installons un capteur capacitif non intrusif sur l'extérieur du réservoir. Une sonde complète n'est utilisée que si nécessaire.")],
      [t('What if a driver refuels at an unofficial station?',        'Et si le conducteur fait le plein dans une station non officielle ?'), t('Every refuel event is logged with time, GPS location, and volume added — regardless of where it happens.',                                                    'Chaque événement de ravitaillement est enregistré avec l\'heure, la position GPS et le volume ajouté — peu importe où il se produit.')],
      [t('Can I export data to my ERP or accounting software?',       'Puis-je exporter les données vers mon ERP ou logiciel de comptabilité ?'), t('Yes. Reports are available in CSV and PDF. API access is available on enterprise plans.',                                                                 'Oui. Les rapports sont disponibles en CSV et PDF. L\'accès API est disponible sur les plans entreprise.')],
      [t('How soon will I see savings?',                              'Quand verrai-je des économies ?'),                                  t('Most clients report measurable reductions within the first 30 days simply from drivers knowing they are monitored.',                                           'La plupart des clients signalent des réductions mesurables dans les 30 premiers jours, simplement parce que les conducteurs savent qu\'ils sont surveillés.')],
    ],
  ],

  'security-solutions' => [
    'tagline'   => t('Protect every asset — before, during, and after an incident.', 'Protégez chaque actif — avant, pendant et après un incident.'),
    'hero_icon' => 'bi-shield-fill-check',
    'hero_color'=> '#22c55e',
    'image'     => 'assets/img/biometric.jpg',
    'features'  => [
      ['bi-lock-fill',              '#e60000', t('Remote Engine Immobilisation', 'Immobilisation à Distance'),          t('Cut the engine of any vehicle from your phone the moment it is reported stolen or driven without authorisation.',            "Coupez le moteur de n'importe quel véhicule depuis votre téléphone dès qu'il est signalé volé ou conduit sans autorisation.")],
      ['bi-bell-fill',              '#f59e0b', t('Alarm & Siren System',          'Système d\'Alarme et de Sirène'),     t('Integrated siren and strobe trigger on unauthorised entry, tow attempt, or impact — alerting bystanders immediately.',     'La sirène et le stroboscope intégrés se déclenchent lors d\'une entrée non autorisée, remorquage ou impact — alertant immédiatement les témoins.')],
      ['bi-door-closed-fill',       '#3b82f6', t('Tamper Sensors',                'Capteurs Anti-Intrusion'),            t('Hood, door, and boot sensors log every opening. Any access outside of working hours fires an instant alert.',              'Les capteurs de capot, portière et coffre enregistrent chaque ouverture. Tout accès en dehors des heures de travail déclenche une alerte instantanée.')],
      ['bi-hand-thumbs-up-fill',    '#8b5cf6', t('Driver Panic Button',           'Bouton Panique Conducteur'),          t('One press sends GPS coordinates and a priority alert to your control room — protecting drivers in dangerous situations.', 'Une pression envoie les coordonnées GPS et une alerte prioritaire au centre de contrôle — protégeant les conducteurs en danger.')],
      ['bi-camera-video-fill',      '#06b6d4', t('24/7 Control Room',             'Centre de Contrôle 24h/7j'),          t('Our Cameroon-based monitoring centre responds to every triggered alarm and escalates to local authorities if needed.',      'Notre centre de surveillance au Cameroun répond à chaque alarme et fait appel aux autorités locales si nécessaire.')],
      ['bi-file-earmark-text-fill', '#22c55e', t('Insurance-Grade Reports',       'Rapports Niveau Assurance'),          t('Timestamped, GPS-tagged incident logs accepted by major insurers to support claims and reduce premiums.',                   'Logs d\'incidents horodatés avec GPS, acceptés par les principaux assureurs pour appuyer les déclarations et réduire les primes.')],
    ],
    'stats' => [
      ['< 60 s', t('Immobilisation Time',   'Temps d\'Immobilisation')],
      ['24/7',   t('Monitoring Coverage',   'Surveillance')],
      ['15%',    t('Avg. Insurance Saving', 'Économie Assurance Moy.')],
      ['0',      t('Unresolved Alerts',     'Alertes Non Résolues')],
    ],
    'benefits' => [
      t('Recover stolen vehicles faster with live tracking during theft events.',  'Récupérez les véhicules volés plus rapidement grâce au suivi en direct lors des incidents.'),
      t('Deter theft — visible Smartrack branding is a known deterrent.',          'Dissuadez le vol — le logo Smartrack visible est un dissuasif reconnu.'),
      t('Protect drivers with one-touch emergency escalation.',                    'Protégez les conducteurs avec une escalade d\'urgence en un clic.'),
      t('Reduce insurance premiums with certified monitoring evidence.',           'Réduisez les primes d\'assurance avec des preuves de surveillance certifiée.'),
      t('Full chain-of-custody logs for police and legal proceedings.',            'Journaux complets de traçabilité pour la police et les procédures judiciaires.'),
    ],
    'steps' => [
      ['01', 'bi-shield-shaded', t('Security Audit',    'Audit de Sécurité'),  t('We assess your vehicles, operating environment, and risk profile to recommend the right combination of devices.',             'Nous évaluons vos véhicules, l\'environnement d\'exploitation et le profil de risque pour recommander la bonne combinaison d\'appareils.')],
      ['02', 'bi-tools',         t('Hardware Installed','Matériel Installé'),  t('GPS tracker, immobiliser relay, tamper sensors, siren, and panic button are all wired in during a single visit.',             'GPS, relais d\'immobilisation, capteurs anti-intrusion, sirène et bouton panique sont tous câblés lors d\'une seule visite.')],
      ['03', 'bi-headset',       t('Always Protected',  'Toujours Protégé'),   t('Your fleet is live in our monitoring centre from day one. Every alert triggers a defined escalation procedure.',             'Votre flotte est en direct dans notre centre de surveillance dès le premier jour. Chaque alerte déclenche une procédure d\'escalade définie.')],
    ],
    'faq' => [
      [t('Can the immobiliser be triggered accidentally?',                        'L\'immobilisateur peut-il se déclencher accidentellement ?'),          t('No. Immobilisation requires two-factor confirmation in the dashboard — it cannot be activated while the vehicle is moving above 10 km/h.',           'Non. L\'immobilisation nécessite une confirmation à deux facteurs — elle ne peut pas être activée quand le véhicule roule à plus de 10 km/h.')],
      [t('Does the system work without a mobile network?',                        'Le système fonctionne-t-il sans réseau mobile ?'),                     t('Tamper alerts and sirens activate locally without any network. GPS and remote immobilisation require connectivity, which is restored automatically.','Les alertes anti-intrusion et sirènes s\'activent localement sans réseau. Le GPS et l\'immobilisation à distance nécessitent une connectivité, restaurée automatiquement.')],
      [t('What if a driver is in a dangerous area and presses panic?',            'Et si un conducteur appuie sur panique dans une zone dangereuse ?'),   t('The control room receives the GPS location and a live alert immediately. Protocol includes direct contact with the driver, fleet manager, and if necessary, local emergency services.', 'Le centre de contrôle reçoit la position GPS et une alerte en direct immédiatement. Le protocole inclut le contact direct avec le conducteur, le gestionnaire de flotte et si nécessaire, les services d\'urgence locaux.')],
      [t('Is this compatible with my existing alarm system?',                     'Est-ce compatible avec mon système d\'alarme existant ?'),             t('In most cases yes. Our technicians assess compatibility during the site visit before installation.',                                                    'Dans la plupart des cas, oui. Nos techniciens évaluent la compatibilité lors de la visite de site avant l\'installation.')],
    ],
  ],

  'fleet-management' => [
    'tagline'   => t('Run a leaner, more profitable fleet — with data, not guesswork.', 'Gérez une flotte plus rentable — avec des données, pas des suppositions.'),
    'hero_icon' => 'bi-truck-front-fill',
    'hero_color'=> '#3b82f6',
    'image'     => 'assets/img/GPS Tracking _ Vehicle Tracking System Ireland Landing Page.jpg',
    'features'  => [
      ['bi-truck-front-fill',    '#3b82f6', t('Fleet Overview Dashboard', 'Tableau de Bord Flotte'),           t('See all vehicles, statuses, driver scores, and active alerts on one screen — desktop or mobile.',                                             'Voyez tous les véhicules, statuts, scores conducteurs et alertes actives sur un seul écran — bureau ou mobile.')],
      ['bi-wrench-adjustable',   '#f59e0b', t('Maintenance Scheduling',   'Planification Maintenance'),         t('Set service reminders by mileage or time. Never miss an oil change, tyre rotation, or roadworthy inspection.',                               'Définissez des rappels de service par kilométrage ou par temps. Ne manquez jamais une vidange, rotation de pneus ou contrôle technique.')],
      ['bi-person-lines-fill',   '#22c55e', t('Driver Scorecards',        'Fiches de Score Conducteur'),        t('Weekly per-driver reports score speeding, braking, idling, and seatbelt compliance. Reward good drivers.',                                    'Des rapports hebdomadaires par conducteur évaluent la vitesse, le freinage, le ralenti et le port de ceinture. Récompensez les bons conducteurs.')],
      ['bi-calendar-check-fill', '#8b5cf6', t('Job Dispatch & Routing',   'Dispatch et Routage de Missions'),  t('Assign jobs to the nearest available vehicle, optimise multi-stop routes, and track completion in real time.',                               'Attribuez des missions au véhicule disponible le plus proche, optimisez les itinéraires multi-arrêts et suivez l\'avancement en temps réel.')],
      ['bi-cloud-download-fill', '#06b6d4', t('Automated Reporting',      'Rapports Automatisés'),              t("Schedule daily, weekly, or monthly reports in PDF or CSV — delivered to management's inbox automatically.",                                   'Planifiez des rapports quotidiens, hebdomadaires ou mensuels en PDF ou CSV — livrés automatiquement dans la boîte mail de la direction.')],
      ['bi-bar-chart-fill',      '#e60000', t('Cost Analytics',           'Analyse des Coûts'),                 t('Full breakdown of fuel, mileage, maintenance, and driver costs per vehicle — with month-on-month comparison.',                              'Décomposition complète du carburant, kilométrage, maintenance et coûts conducteur par véhicule — avec comparaison mois par mois.')],
    ],
    'stats' => [
      ['25%',         t('Avg. Cost Reduction',    'Réduction Coûts Moy.')],
      ['40%',         t('Less Admin Time',        'Moins de Temps Admin.')],
      ['98%',         t('On-Time Delivery Rate',  'Livraison à l\'Heure')],
      [t('1 Dashboard','1 Tableau'), t('Full Fleet View', 'Vue Flotte Complète')],
    ],
    'benefits' => [
      t('Replace spreadsheets with a single real-time operations centre.',       'Remplacez les tableurs par un centre d\'opérations en temps réel.'),
      t('Extend vehicle lifespan with proactive maintenance tracking.',           'Prolongez la durée de vie des véhicules avec un suivi de maintenance proactif.'),
      t('Cut payroll disputes with verified working-hour records.',              'Réduisez les litiges de paie avec des relevés d\'heures de travail vérifiés.'),
      t('Identify underutilised vehicles and right-size your fleet.',            'Identifiez les véhicules sous-utilisés et optimisez la taille de votre flotte.'),
      t('Prove SLA compliance to clients with timestamped delivery logs.',       'Prouvez la conformité aux SLA à vos clients avec des journaux de livraison horodatés.'),
    ],
    'steps' => [
      ['01', 'bi-list-check',     t('Fleet Onboarded',   'Flotte Enregistrée'),     t('We import your vehicle list, assign drivers, and configure your dashboard in one setup session — typically under 2 hours.',           'Nous importons votre liste de véhicules, assignons les conducteurs et configurons votre tableau de bord en une session — généralement moins de 2 heures.')],
      ['02', 'bi-sliders2',       t('Rules Configured',  'Règles Configurées'),     t('We set your speed limits, geofences, maintenance intervals, and alert thresholds to match your operating procedures.',                'Nous définissons vos limites de vitesse, géorepérages, intervalles de maintenance et seuils d\'alerte selon vos procédures opérationnelles.')],
      ['03', 'bi-graph-up-arrow', t('Operate Smarter',   'Opérez Plus Intelligemment'), t('Daily operations run through the dashboard. Management gets automated reports. Costs fall within the first month.',                'Les opérations quotidiennes passent par le tableau de bord. La direction reçoit des rapports automatisés. Les coûts baissent dès le premier mois.')],
    ],
    'faq' => [
      [t('How many vehicles can I manage?',                             'Combien de véhicules puis-je gérer ?'),                                        t('The platform scales from 1 to 1 000+ vehicles with no performance degradation. Pricing is per-vehicle per-month.',              'La plateforme s\'adapte de 1 à 1 000+ véhicules sans dégradation. La tarification est par véhicule par mois.')],
      [t('Can I give different access levels to managers and drivers?', 'Puis-je donner différents niveaux d\'accès aux managers et conducteurs ?'),    t('Yes. The platform has role-based access: administrator, fleet manager, dispatcher, and read-only viewer.',                     'Oui. La plateforme dispose d\'un accès basé sur les rôles : administrateur, gestionnaire, répartiteur et lecteur seul.')],
      [t('Does it integrate with our existing systems?',                'S\'intègre-t-il avec nos systèmes existants ?'),                               t('We offer CSV export and API access. Common integrations include SAP, Odoo, and custom ERP/WMS platforms.',                      'Nous proposons l\'export CSV et l\'accès API. Les intégrations courantes incluent SAP, Odoo et les plateformes ERP/WMS personnalisées.')],
      [t('How long before I see ROI?',                                  'Combien de temps avant de voir un ROI ?'),                                     t('Most clients recover the monthly subscription cost within the first 2–3 weeks through fuel and overtime savings alone.',       'La plupart des clients récupèrent le coût d\'abonnement dans les 2-3 premières semaines grâce aux économies de carburant et d\'heures supplémentaires.')],
    ],
  ],

  'video-surveillance' => [
    'tagline'   => t('See everything — inside and outside every vehicle.', 'Voyez tout — à l\'intérieur et à l\'extérieur de chaque véhicule.'),
    'hero_icon' => 'bi-camera-video-fill',
    'hero_color'=> '#06b6d4',
    'image'     => 'assets/img/biometric.jpg',
    'features'  => [
      ['bi-camera-video-fill',          '#06b6d4', t('HD In-Cabin & Road Cameras', 'Caméras HD Cabine et Route'),         t('Front-facing and driver-facing Full HD cameras record continuously, triggered by events or on schedule.',                                      'Des caméras Full HD frontales et orientées conducteur enregistrent en continu, déclenchées par des événements ou selon un planning.')],
      ['bi-cloud-arrow-up-fill',        '#3b82f6', t('Cloud Video Storage',        'Stockage Vidéo Cloud'),               t('Footage is uploaded automatically over 4G and stored securely for 30–90 days depending on your plan.',                                        'Les enregistrements sont téléchargés automatiquement via 4G et stockés de manière sécurisée pendant 30 à 90 jours selon votre forfait.')],
      ['bi-exclamation-octagon-fill',   '#e60000', t('Event-Based Clips',          'Clips Basés sur les Événements'),     t('Hard braking, collision detection, or geofence violation automatically saves and tags the relevant clip.',                                     'Le freinage brusque, la détection de collision ou la violation de géozone sauvegarde et tague automatiquement le clip correspondant.')],
      ['bi-eye-fill',                   '#22c55e', t('Live Remote Viewing',        'Visionnage à Distance en Direct'),    t('Stream any camera live from the dashboard — useful for verifying incidents or checking delivery progress.',                                   "Diffusez n'importe quelle caméra en direct depuis le tableau de bord — utile pour vérifier des incidents ou suivre une livraison.")],
      ['bi-file-earmark-play-fill',     '#f59e0b', t('Evidence Export',            'Export de Preuves'),                  t('Download clips with GPS overlay, speed, and timestamp watermark — court-admissible quality.',                                                 'Téléchargez des clips avec superposition GPS, vitesse et filigrane d\'horodatage — qualité recevable en justice.')],
      ['bi-shield-lock-fill',           '#8b5cf6', t('Privacy Compliant',          'Conforme à la Vie Privée'),           t('Recordings are handled under our data-protection policy; driver notification protocols are configurable.',                                     'Les enregistrements sont traités conformément à notre politique de protection des données ; les protocoles de notification des conducteurs sont configurables.')],
    ],
    'stats' => [
      [t('Full HD',   'Full HD'),        t('Camera Resolution',       'Résolution Caméra')],
      [t('30–90 days','30–90 jours'),    t('Cloud Retention',         'Rétention Cloud')],
      ['< 5 min',                        t('Incident Clip Delivery',  'Livraison Clip Incident')],
      [t('4G Upload', '4G Upload'),      t('Real-Time Sync',          'Sync Temps Réel')],
    ],
    'benefits' => [
      t('Protect drivers from false accident liability with video proof.',     'Protégez les conducteurs de la fausse responsabilité accident avec preuve vidéo.'),
      t('Reduce insurance premiums significantly with camera evidence.',       'Réduisez significativement les primes d\'assurance avec des preuves caméra.'),
      t('Deter cargo theft, hijacking, and passenger misconduct.',             'Dissuadez le vol de marchandises, le carjacking et les comportements inappropriés.'),
      t('Resolve customer complaints instantly with timestamped footage.',     'Résolvez les plaintes clients instantanément avec des enregistrements horodatés.'),
      t('Improve driver behaviour — camera awareness reduces incidents.',      'Améliorez le comportement des conducteurs — la conscience de la caméra réduit les incidents.'),
    ],
    'steps' => [
      ['01', 'bi-camera-fill',    t('Cameras Mounted',      'Caméras Installées'),      t('Front, rear, and cabin cameras are professionally fitted and wired — typically 2 hours per vehicle.',                      'Les caméras frontales, arrières et de cabine sont montées et câblées professionnellement — généralement 2 heures par véhicule.')],
      ['02', 'bi-cloud-upload',   t('Cloud Account Set Up', 'Compte Cloud Configuré'),  t('Your cloud storage, notification rules, and retention period are configured before we leave site.',                        'Votre stockage cloud, règles de notification et période de rétention sont configurés avant notre départ du site.')],
      ['03', 'bi-play-btn-fill',  t('Watch & Act',          'Regardez et Agissez'),     t('Access live streams, review event clips, and download evidence directly from the web portal or mobile app.',              'Accédez aux flux en direct, examinez les clips d\'événements et téléchargez des preuves depuis le portail web ou l\'application mobile.')],
    ],
    'faq' => [
      [t('Does recording pause when the engine is off?',       'L\'enregistrement s\'arrête-t-il quand le moteur est éteint ?'),   t('Cameras support a configurable parking mode — they can record on motion detection for up to 24 hours after engine-off.',               'Les caméras supportent un mode de stationnement configurable — elles peuvent enregistrer sur détection de mouvement jusqu\'à 24 heures après l\'arrêt du moteur.')],
      [t('How much data does video upload use?',               'Quelle quantité de données l\'upload vidéo consomme-t-il ?'),      t('Event-based clips are small (10–60 MB). Full continuous upload to cloud is optional and uses roughly 2–5 GB per day.',                 'Les clips basés sur les événements sont petits (10–60 Mo). L\'upload continu complet vers le cloud est optionnel et utilise environ 2–5 Go par jour.')],
      [t('Can I access footage on my phone?',                  'Puis-je accéder aux enregistrements sur mon téléphone ?'),        t('Yes. The mobile app lets you watch live streams and browse stored clips from anywhere with an internet connection.',                     "Oui. L'application mobile permet de regarder les flux en direct et de parcourir les clips stockés depuis n'importe où.")],
      [t('What if the vehicle enters a tunnel or dead zone?',  'Et si le véhicule entre dans un tunnel ou une zone sans réseau ?'), t('Recording continues locally on the device SD card and syncs to the cloud as soon as connectivity is restored.',                       'L\'enregistrement continue localement sur la carte SD de l\'appareil et se synchronise vers le cloud dès que la connectivité est rétablie.')],
    ],
  ],

  'access-control' => [
    'tagline'   => t('The right people in the right places — and nobody else.', 'Les bonnes personnes aux bons endroits — et personne d\'autre.'),
    'hero_icon' => 'bi-door-open-fill',
    'hero_color'=> '#8b5cf6',
    'image'     => 'assets/img/biometric.jpg',
    'features'  => [
      ['bi-fingerprint',                  '#8b5cf6', t('Biometric Authentication',  'Authentification Biométrique'),      t('Fingerprint, face recognition, and iris scanning for the highest-assurance entry points.',                                              "Empreinte digitale, reconnaissance faciale et scan de l'iris pour les points d'entrée à très haute assurance.")],
      ['bi-credit-card-2-front-fill',     '#3b82f6', t('Smart Card & PIN',           'Carte Intelligente et PIN'),          t('RFID proximity cards and PIN pads for offices, warehouses, and staff-only zones.',                                                     'Cartes de proximité RFID et claviers PIN pour les bureaux, entrepôts et zones réservées au personnel.')],
      ['bi-clock-fill',                   '#f59e0b', t('Time-Based Access Rules',    'Règles d\'Accès Temporelles'),        t('Restrict specific doors to specific staff during specific hours — automatically enforced, no manual management.',                       'Restreignez des portes spécifiques à du personnel spécifique pendant des heures spécifiques — appliqué automatiquement, sans gestion manuelle.')],
      ['bi-list-ul',                      '#22c55e', t('Access Audit Log',           'Journal d\'Audit des Accès'),         t('Every entry and exit is timestamped and logged. Run instant reports on who was where and when.',                                       'Chaque entrée et sortie est horodatée et enregistrée. Générez des rapports instantanés sur qui était où et quand.')],
      ['bi-shield-fill-exclamation',      '#e60000', t('Intrusion Alerts',           'Alertes d\'Intrusion'),               t('Forced-entry or tailgating triggers an immediate alarm and control-room notification.',                                                "Une entrée forcée ou du \"tailgating\" déclenche une alarme immédiate et une notification au centre de contrôle.")],
      ['bi-phone-landscape-fill',         '#06b6d4', t('Mobile Management',          'Gestion Mobile'),                    t('Enrol new staff, revoke credentials, and review logs from the admin mobile app — no on-site visit needed.',                           'Inscrivez de nouveaux employés, révoquez des identifiants et consultez les journaux depuis l\'application mobile d\'administration — sans visite sur site.')],
    ],
    'stats' => [
      ['< 1 s',      t('Biometric Read Time', 'Temps de Lecture Biométrique')],
      [t('Unlimited','Illimité'), t('Users per Site',   'Utilisateurs par Site')],
      ['100%',       t('Audit Coverage',       'Couverture d\'Audit')],
      [t('Mobile','Mobile'), t('Admin Access', 'Accès Admin')],
    ],
    'benefits' => [
      t('Eliminate key duplication and lost-card security risks.',                             'Éliminez les risques de duplication de clé et de perte de badge.'),
      t('Automate time-and-attendance recording tied to door entries.',                       'Automatisez l\'enregistrement des présences lié aux entrées de portes.'),
      t('Restrict sensitive areas (server room, cash office) to authorised staff only.',      'Restreignez les zones sensibles (salle serveur, caisse) au seul personnel autorisé.'),
      t('Generate compliance reports for audits and insurance assessments.',                   'Générez des rapports de conformité pour les audits et évaluations d\'assurance.'),
      t('Remotely revoke access for leavers instantly — no key retrieval needed.',            'Révoquez instantanément l\'accès des départs à distance — sans récupération de clé.'),
    ],
    'steps' => [
      ['01', 'bi-diagram-3-fill', t('Site Survey',      'Étude de Site'),     t('We map all entry points, identify risk levels, and design a layered access policy before any hardware is ordered.',          'Nous cartographions tous les points d\'entrée, identifions les niveaux de risque et concevons une politique d\'accès en couches avant toute commande de matériel.')],
      ['02', 'bi-tools',          t('Hardware Installed','Matériel Installé'), t('Controllers, readers, and electric locks are fitted by our certified team. Existing doors and frames are reused where possible.', 'Contrôleurs, lecteurs et serrures électriques sont posés par notre équipe certifiée. Les portes et cadres existants sont réutilisés si possible.')],
      ['03', 'bi-people-fill',    t('Staff Enrolled',   'Personnel Inscrit'),  t("We enrol your team's biometrics or cards and configure time rules. Full handover training included.",                          'Nous enregistrons les données biométriques ou badges de votre équipe et configurons les règles horaires. Formation complète incluse.')],
    ],
    'faq' => [
      [t('What happens during a power cut?',              'Que se passe-t-il lors d\'une coupure de courant ?'),     t('All controllers have battery backup. Fail-safe (unlocks on power loss) or fail-secure (stays locked) modes are configurable per door.',   'Tous les contrôleurs ont une batterie de secours. Les modes fail-safe (déverrouillage) ou fail-secure (reste verrouillé) sont configurables par porte.')],
      [t('Can I integrate with HR or payroll software?',  'Puis-je m\'intégrer avec un logiciel RH ou de paie ?'),   t('Yes. Access logs export in CSV and our API supports direct integration with common HR platforms.',                                              'Oui. Les journaux d\'accès s\'exportent en CSV et notre API supporte l\'intégration directe avec les plateformes RH courantes.')],
      [t('How do I add or remove staff?',                 'Comment ajouter ou supprimer du personnel ?'),            t('Through the web or mobile admin panel — changes take effect immediately without any on-site intervention.',                                  'Via le panneau d\'administration web ou mobile — les modifications prennent effet immédiatement sans intervention sur site.')],
      [t('Is biometric data stored on the cloud?',        'Les données biométriques sont-elles stockées dans le cloud ?'), t('Templates are stored locally on the controller by default. Cloud backup is available with additional encryption and consent controls.',  'Les gabarits sont stockés localement sur le contrôleur par défaut. La sauvegarde cloud est disponible avec chiffrement supplémentaire et contrôles de consentement.')],
    ],
  ],

  'fire-detection' => [
    'tagline'   => t('Detect, alert, and protect — in seconds.', 'Détecter, alerter et protéger — en quelques secondes.'),
    'hero_icon' => 'bi-fire',
    'hero_color'=> '#e60000',
    'image'     => 'assets/img/biometric.jpg',
    'features'  => [
      ['bi-fire',                    '#e60000', t('Multi-Zone Smoke & Heat',    'Fumée et Chaleur Multi-Zones'),        t('Addressable detectors pinpoint the exact zone and device triggering an alarm — no ambiguity.',                                           'Des détecteurs adressables identifient la zone et l\'appareil exacts à l\'origine d\'une alarme — sans ambiguïté.')],
      ['bi-bell-fill',               '#f59e0b', t('Sounder & Strobe Alerts',    'Alertes Sonores et Stroboscopiques'),  t('High-decibel sounders and visual strobes ensure every occupant is warned — even in noisy environments.',                               'Des avertisseurs sonores à fort décibel et des stroboscopes visuels garantissent que chaque occupant est prévenu — même dans des environnements bruyants.')],
      ['bi-phone-fill',              '#3b82f6', t('Automatic SMS & App Alerts',  'Alertes SMS et App Automatiques'),    t('Facility managers receive real-time push notifications and SMS the moment any detector activates.',                                    'Les responsables reçoivent des notifications push en temps réel et des SMS dès l\'activation d\'un détecteur.')],
      ['bi-shield-fill-check',       '#22c55e', t('Sprinkler Integration',       'Intégration Sprinkler'),              t('Compatible with dry-pipe, wet-pipe, and pre-action sprinkler systems for automatic suppression.',                                       'Compatible avec les systèmes sprinkler à tuyaux secs, mouillés et pré-action pour une suppression automatique.')],
      ['bi-list-check',              '#8b5cf6', t('Monthly Test & Log',          'Test et Journal Mensuel'),            t('Self-test routines log detector health monthly. Faults are flagged before they compromise safety.',                                    'Les routines d\'auto-test enregistrent l\'état de santé des détecteurs chaque mois. Les pannes sont signalées avant de compromettre la sécurité.')],
      ['bi-file-earmark-text-fill',  '#06b6d4', t('Compliance Certificates',     'Certificats de Conformité'),          t('Full documentation for fire safety regulations, building permits, and insurance requirements.',                                          'Documentation complète pour les réglementations de sécurité incendie, permis de construire et exigences d\'assurance.')],
    ],
    'stats' => [
      ['< 10 s',    t('Detection-to-Alert',  'Détection-à-Alerte')],
      ['365/24/7',  t('Monitoring',          'Surveillance')],
      ['99.9%',     t('Detector Reliability','Fiabilité Détecteur')],
      [t('5 yr','5 ans'), t('Device Warranty','Garantie Appareil')],
    ],
    'benefits' => [
      t('Protect lives with sub-10-second alert-to-evacuation time.',              'Protégez des vies avec un temps d\'alerte-à-évacuation inférieur à 10 secondes.'),
      t('Minimise property and inventory damage with early detection.',             'Minimisez les dommages matériels et aux stocks grâce à la détection précoce.'),
      t('Meet legal fire safety requirements for commercial premises.',             'Satisfaites aux exigences légales de sécurité incendie pour les locaux commerciaux.'),
      t('Reduce insurance premiums with certified fire safety documentation.',      'Réduisez les primes d\'assurance avec une documentation de sécurité incendie certifiée.'),
      t('Integrate with access control to automatically unlock exits on alarm.',    'Intégrez avec le contrôle d\'accès pour déverrouiller automatiquement les sorties en cas d\'alarme.'),
    ],
    'steps' => [
      ['01', 'bi-clipboard2-check-fill', t('Risk Assessment',  'Évaluation des Risques'), t('We conduct a full fire-risk assessment of your premises and design a compliant detector and sounder layout.',              'Nous effectuons une évaluation complète des risques d\'incendie de vos locaux et concevons un plan de détecteurs et d\'avertisseurs conforme.')],
      ['02', 'bi-tools',                 t('System Installed',  'Système Installé'),       t('Detectors, sounders, control panel, and wiring are installed with minimal disruption — typically 1–2 days for a standard office.','Détecteurs, avertisseurs, panneau de contrôle et câblage sont installés avec un minimum de perturbations — généralement 1 à 2 jours pour un bureau standard.')],
      ['03', 'bi-shield-check',          t('Certified & Live',  'Certifié et Opérationnel'), t('We commission the system, conduct evacuation drill testing, and issue compliance certificates on the same day.',           'Nous mettons en service le système, effectuons des tests d\'évacuation et délivrons les certificats de conformité le jour même.')],
    ],
    'faq' => [
      [t('How often do detectors need replacing?',                    'Quelle est la fréquence de remplacement des détecteurs ?'),       t('Photoelectric smoke detectors have a 10-year lifespan. Our annual inspection catches any units approaching end-of-life.',              'Les détecteurs de fumée photoélectriques ont une durée de vie de 10 ans. Notre inspection annuelle détecte les appareils approchant de leur fin de vie.')],
      [t('Do false alarms disturb my operations?',                    'Les fausses alarmes perturbent-elles mes opérations ?'),           t('We use intelligent multi-criteria detectors that require confirmation from two sensors before sounding — dramatically reducing false alarms.','Nous utilisons des détecteurs intelligents à critères multiples qui nécessitent la confirmation de deux capteurs avant de sonner — réduisant considérablement les fausses alarmes.')],
      [t('What if the control panel loses power?',                    'Et si le panneau de contrôle perd l\'alimentation ?'),            t('All panels include a minimum 24-hour battery backup that maintains full detection and alarm capability.',                               'Tous les panneaux incluent une batterie de secours d\'au moins 24 heures maintenant la détection et la capacité d\'alarme complète.')],
      [t('Is this suitable for warehouses and industrial sites?',     'Est-ce adapté aux entrepôts et sites industriels ?'),             t('Yes. We carry beam detectors, aspirating systems, and heat detectors rated for dusty, humid, or high-ceiling environments.',           'Oui. Nous disposons de détecteurs à faisceau, systèmes à aspiration et détecteurs de chaleur adaptés aux environnements poussiéreux, humides ou à plafond haut.')],
    ],
  ],

  'network-security' => [
    'tagline'   => t('Secure infrastructure your business can depend on.', 'Une infrastructure sécurisée sur laquelle votre entreprise peut compter.'),
    'hero_icon' => 'bi-wifi',
    'hero_color'=> '#06b6d4',
    'image'     => 'assets/img/biometric.jpg',
    'features'  => [
      ['bi-shield-lock-fill',    '#06b6d4', t('Firewall & UTM',             'Pare-feu et UTM'),                    t('Next-generation firewall with unified threat management blocks malware, ransomware, and intrusion attempts.',                          'Pare-feu de nouvelle génération avec gestion unifiée des menaces bloquant les malwares, ransomwares et tentatives d\'intrusion.')],
      ['bi-wifi',                '#3b82f6', t('Managed WiFi',               'WiFi Géré'),                          t('Enterprise-grade access points with SSID segmentation, guest isolation, and bandwidth control.',                                       'Points d\'accès de niveau entreprise avec segmentation SSID, isolation des invités et contrôle de la bande passante.')],
      ['bi-camera-video-fill',   '#8b5cf6', t('Network Video Surveillance', 'Vidéosurveillance Réseau'),           t('IP CCTV cameras managed centrally — remote live view, motion alerts, and cloud-backed storage.',                                       'Caméras IP CCTV gérées centralement — vue en direct à distance, alertes de mouvement et stockage sauvegardé dans le cloud.')],
      ['bi-diagram-3-fill',      '#22c55e', t('Structured Cabling',         'Câblage Structuré'),                  t('Cat6/Cat6A and fibre installation to TIA-568 standards — clean, labelled, and documented.',                                            'Installation Cat6/Cat6A et fibre aux normes TIA-568 — propre, étiqueté et documenté.')],
      ['bi-bar-chart-fill',      '#f59e0b', t('Network Monitoring (NOC)',   'Surveillance Réseau (NOC)'),          t('Our NOC watches bandwidth, latency, and device health 24/7 — we alert you before users even notice an issue.',                         'Notre NOC surveille la bande passante, la latence et la santé des équipements 24h/7j — nous vous alertons avant même que les utilisateurs ne remarquent un problème.')],
      ['bi-cloud-arrow-up-fill', '#e60000', t('Secure Remote Access (VPN)', 'Accès Distant Sécurisé (VPN)'),     t('Site-to-site and client VPN lets staff work remotely over an encrypted tunnel — no exposed RDP ports.',                               'Le VPN site-à-site et client permet au personnel de travailler à distance via un tunnel chiffré — sans ports RDP exposés.')],
    ],
    'stats' => [
      ['99.9%',     t('Network Uptime SLA',    'SLA Disponibilité Réseau')],
      ['24/7',      t('NOC Monitoring',        'Surveillance NOC')],
      ['< 15 min',  t('Incident Response',     'Réponse aux Incidents')],
      ['0',         t('Unpatched Vulnerabilities', 'Vulnérabilités Non Corrigées')],
    ],
    'benefits' => [
      t('Stop ransomware and data breaches before they reach your servers.',      'Stoppez les ransomwares et violations de données avant qu\'ils n\'atteignent vos serveurs.'),
      t('Reduce IT management burden with fully managed infrastructure.',          'Réduisez la charge de gestion informatique avec une infrastructure entièrement gérée.'),
      t('Scale bandwidth and access points as your team grows.',                   'Augmentez la bande passante et les points d\'accès à mesure que votre équipe grandit.'),
      t('Meet data-protection and audit requirements with documented controls.',   'Satisfaites aux exigences de protection des données et d\'audit avec des contrôles documentés.'),
      t('Ensure business continuity with redundant links and failover routing.',   'Assurez la continuité des activités avec des liens redondants et un routage de basculement.'),
    ],
    'steps' => [
      ['01', 'bi-search',       t('Network Audit',          'Audit Réseau'),           t('We scan your existing infrastructure, identify vulnerabilities, and present a prioritised remediation roadmap.',            'Nous analysons votre infrastructure existante, identifions les vulnérabilités et présentons une feuille de route de remédiation priorisée.')],
      ['02', 'bi-tools',        t('Infrastructure Deployed', 'Infrastructure Déployée'), t('Cabling, switches, access points, firewall, and VPN are installed in a single scheduled maintenance window.',             'Câblage, commutateurs, points d\'accès, pare-feu et VPN sont installés lors d\'une fenêtre de maintenance planifiée unique.')],
      ['03', 'bi-shield-check', t('Monitored & Managed',    'Surveillé et Géré'),      t('Our NOC takes over 24/7 monitoring. You receive a monthly health report and priority helpdesk access.',                    'Notre NOC prend en charge la surveillance 24h/7j. Vous recevez un rapport de santé mensuel et un accès prioritaire au service d\'assistance.')],
    ],
    'faq' => [
      [t('Do I need to replace all my existing hardware?',                    'Dois-je remplacer tout mon matériel existant ?'),                       t('Not necessarily. We assess what can be retained, upgraded, or replaced based on age and security status.',                                          'Pas nécessairement. Nous évaluons ce qui peut être conservé, mis à niveau ou remplacé selon l\'âge et le statut de sécurité.')],
      [t('What is the difference between managed and unmanaged WiFi?',        'Quelle est la différence entre WiFi géré et non géré ?'),               t('Managed WiFi is centrally configured, monitored, and updated by our team — users get reliable, secure connectivity without IT involvement.',       'Le WiFi géré est configuré, surveillé et mis à jour centralement par notre équipe — les utilisateurs bénéficient d\'une connectivité fiable et sécurisée sans intervention informatique.')],
      [t('Can you secure a multi-site network?',                              'Pouvez-vous sécuriser un réseau multi-sites ?'),                        t('Yes. We specialise in multi-site SD-WAN and site-to-site VPN deployments for businesses with branches across Cameroon and the region.',          'Oui. Nous sommes spécialisés dans les déploiements SD-WAN multi-sites et VPN site-à-site pour les entreprises avec des agences au Cameroun et dans la région.')],
      [t('What happens when there is an incident?',                           'Que se passe-t-il lors d\'un incident ?'),                              t('Our NOC alerts you within 15 minutes. For critical incidents our engineers can be on-site in Yaoundé or Douala within 4 hours.',                  'Notre NOC vous alerte dans les 15 minutes. Pour les incidents critiques, nos ingénieurs peuvent être sur site à Yaoundé ou Douala dans les 4 heures.')],
    ],
  ],
];

// Fall back to a generic entry for any slug not explicitly listed
$slug   = $service['slug'] ?? '';
$detail = $serviceDetails[$slug] ?? null;

$pageTitle = $title . ' - Smartrack';
$bodyClass = 'service-details-page';

$others = db()->prepare('SELECT * FROM services WHERE id != ? ORDER BY sort_order ASC, created_at ASC LIMIT 6');
$others->execute([$serviceId]);
$otherServices = $others->fetchAll();

define('APP_INIT', true);
include __DIR__ . '/includes/header.php';
?>

<!-- Page Title -->
<div class="page-title dark-background"
     style="background-image:url(<?php echo escape(site_url('assets/img/page-title-bg.jpg')); ?>);">
  <div class="container position-relative">
    <h1><?php echo escape($title); ?></h1>
    <?php if ($detail): ?>
      <p style="color:rgba(255,255,255,.75);font-size:1rem;margin-top:8px;"><?php echo escape($detail['tagline']); ?></p>
    <?php endif; ?>
    <nav class="breadcrumbs">
      <ol>
        <li><a href="<?php echo escape(site_url('index.php')); ?>"><?php echo escape(get_translation('breadcrumb_home')); ?></a></li>
        <li><a href="<?php echo escape(site_url('devices.php')); ?>"><?php echo escape(get_translation('breadcrumb_services')); ?></a></li>
        <li class="current"><?php echo escape($title); ?></li>
      </ol>
    </nav>
  </div>
</div>

<style>
/* ── Service page styles ── */
.svc-feature-card {
  background:#fff;border-radius:14px;padding:28px 24px;
  border:1px solid #f0f0f0;
  box-shadow:0 2px 16px rgba(0,0,0,.05);
  height:100%;transition:all .3s;
}
.svc-feature-card:hover {
  transform:translateY(-5px) perspective(900px) rotateX(2deg);
  box-shadow:0 12px 40px rgba(0,0,0,.1);
}
.svc-feature-icon {
  width:52px;height:52px;border-radius:12px;
  display:flex;align-items:center;justify-content:center;
  font-size:1.4rem;margin-bottom:14px;
}
.svc-feature-card h5 { font-size:.95rem;font-weight:700;color:#1a202c;margin-bottom:8px; }
.svc-feature-card p  { font-size:.855rem;color:#64748b;line-height:1.65;margin:0; }

.svc-stat-box {
  text-align:center;padding:28px 16px;
  border-right:1px solid rgba(255,255,255,.08);
}
.svc-stat-box:last-child { border-right:none; }
.svc-stat-val { font-size:2.2rem;font-weight:900;color:#e60000;line-height:1;display:block; }
.svc-stat-lbl { font-size:.72rem;font-weight:700;color:rgba(255,255,255,.5);text-transform:uppercase;letter-spacing:.08em;margin-top:6px; }

.svc-step-num {
  width:56px;height:56px;border-radius:50%;
  background:#e60000;color:#fff;
  display:flex;align-items:center;justify-content:center;
  font-size:1.2rem;font-weight:900;flex-shrink:0;
  transition:transform .35s ease,box-shadow .35s ease;
}
.svc-step:hover .svc-step-num {
  transform:rotateY(360deg) scale(1.1);
  box-shadow:0 6px 20px rgba(230,0,0,.4);
}

/* ── Accordion FAQ ── */
.svc-accordion .accordion-button {
  font-weight:600;font-size:.92rem;color:#1a202c;background:#fff;
}
.svc-accordion .accordion-button:not(.collapsed) {
  background:#fff9f9;color:#e60000;box-shadow:none;
}
.svc-accordion .accordion-button::after {
  filter:hue-rotate(200deg);
}
.svc-accordion .accordion-item {
  border:1px solid #f0f0f0;border-radius:10px !important;
  margin-bottom:8px;overflow:hidden;
}
.svc-accordion .accordion-body {
  font-size:.875rem;color:#64748b;line-height:1.7;background:#fafafa;
}

/* ── Sidebar ── */
.svc-sidebar-link {
  display:flex;align-items:center;gap:10px;padding:12px 16px;
  border-radius:10px;text-decoration:none;color:#374151;
  font-size:.88rem;font-weight:600;transition:all .2s;
  margin-bottom:4px;
}
.svc-sidebar-link:hover, .svc-sidebar-link.active {
  background:rgba(230,0,0,.07);color:#e60000;
}
.svc-sidebar-link i { color:#e60000;font-size:1.1rem;flex-shrink:0; }
</style>

<!-- ── Main Layout ── -->
<section id="service-details" class="service-details section" style="background:#f8f9fb;">
  <div class="container">
    <div class="row gy-5">

      <!-- SIDEBAR -->
      <div class="col-lg-3" data-aos="fade-up" data-aos-delay="100">

        <!-- Other services -->
        <?php if (!empty($otherServices)): ?>
        <div style="background:#fff;border-radius:16px;padding:28px;box-shadow:0 2px 20px rgba(0,0,0,.06);margin-bottom:24px;">
          <h5 style="font-size:.88rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;
                     color:#888;margin-bottom:16px;padding-bottom:10px;border-bottom:1px solid #f0f0f0;">
            <?php echo escape(get_translation('svc_other_services')); ?>
          </h5>
          <?php foreach ($otherServices as $s):
            $sTitle = $s['title_' . $lang] ?: $s['title_en'];
            $sSlug  = $s['slug'] ?? '';
            $sIcon  = [
              'vehicle-tracking'  =>'bi-geo-alt-fill',
              'fuel-monitoring'   =>'bi-fuel-pump-fill',
              'security-solutions'=>'bi-shield-fill-check',
              'fleet-management'  =>'bi-truck-front-fill',
              'fire-detection'    =>'bi-fire',
              'network-security'  =>'bi-wifi',
              'video-surveillance'=>'bi-camera-video-fill',
              'access-control'    =>'bi-door-open-fill',
            ][$sSlug] ?? 'bi-arrow-right-circle';
          ?>
            <a href="<?php echo escape(site_url('service.php?id=' . $s['id'])); ?>"
               class="svc-sidebar-link <?php echo $s['id'] == $serviceId ? 'active' : ''; ?>">
              <i class="bi <?php echo $sIcon; ?>"></i>
              <?php echo escape($sTitle); ?>
            </a>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- CTA card -->
        <div style="background:linear-gradient(135deg,#e60000,#9b0000);border-radius:16px;padding:28px;color:#fff;text-align:center;">
          <i class="bi bi-headset" style="font-size:2.5rem;opacity:.8;"></i>
          <h5 class="mt-3 mb-2" style="font-size:1rem;font-weight:700;"><?php echo escape(get_translation('svc_talk_expert')); ?></h5>
          <p style="font-size:.82rem;opacity:.8;margin-bottom:20px;line-height:1.6;">
            <?php echo escape(get_translation('svc_sidebar_quote')); ?>
          </p>
          <a href="<?php echo escape(site_url('contact.php')); ?>"
             style="background:#fff;color:#e60000;font-weight:700;font-size:.82rem;
                    padding:10px 22px;border-radius:30px;text-decoration:none;display:inline-block;
                    transition:all .2s;"
             onmouseover="this.style.transform='translateY(-2px)'"
             onmouseout="this.style.transform='translateY(0)'">
            <?php echo escape(get_translation('svc_free_consult')); ?>
          </a>
        </div>

      </div><!-- /sidebar -->

      <!-- MAIN CONTENT -->
      <div class="col-lg-9" data-aos="fade-up" data-aos-delay="200">

        <!-- Hero image + intro -->
        <?php
        $heroImg = !empty($service['image_path'])
            ? escape($service['image_path'])
            : escape(site_url(($detail['image'] ?? 'assets/img/page-title-bg.jpg')));
        ?>
        <div style="border-radius:18px;overflow:hidden;margin-bottom:36px;
                    box-shadow:0 8px 40px rgba(0,0,0,.1);">
          <img src="<?php echo $heroImg; ?>" alt="<?php echo escape($title); ?>"
               style="width:100%;height:320px;object-fit:cover;">
        </div>

        <!-- Intro text -->
        <div style="margin-bottom:40px;">
          <span style="display:inline-block;background:rgba(230,0,0,.08);color:#e60000;
                       font-size:.72rem;font-weight:700;padding:4px 14px;border-radius:20px;
                       letter-spacing:.06em;text-transform:uppercase;margin-bottom:14px;">
            <?php echo escape($title); ?>
          </span>
          <?php if ($detail): ?>
            <h2 style="font-size:1.9rem;font-weight:800;color:#1a202c;margin-bottom:14px;line-height:1.25;">
              <?php echo escape($detail['tagline']); ?>
            </h2>
          <?php endif; ?>
          <p style="font-size:1rem;color:#475569;line-height:1.8;margin-bottom:12px;">
            <?php echo escape($summary); ?>
          </p>
          <?php if ($content): ?>
            <p style="font-size:.95rem;color:#64748b;line-height:1.8;"><?php echo escape($content); ?></p>
          <?php endif; ?>
        </div>

        <?php if ($detail): ?>

        <!-- ── Key Features Grid ── -->
        <div style="margin-bottom:50px;">
          <h3 style="font-size:1.3rem;font-weight:800;color:#1a202c;margin-bottom:6px;">
            <?php echo escape(get_translation('svc_whats_included')); ?>
          </h3>
          <p style="color:#64748b;font-size:.9rem;margin-bottom:24px;">
            <?php echo escape(get_translation('svc_whats_included_sub')); ?>
          </p>
          <div class="row g-3">
            <?php foreach ($detail['features'] as $i => $feat):
              [$fIcon,$fColor,$fTitle,$fDesc] = $feat;
            ?>
              <div class="col-md-6" data-aos="fade-up" data-aos-delay="<?php echo ($i%3)*80; ?>">
                <div class="svc-feature-card">
                  <div class="svc-feature-icon" style="background:<?php echo $fColor; ?>15;color:<?php echo $fColor; ?>;">
                    <i class="bi <?php echo $fIcon; ?>"></i>
                  </div>
                  <h5><?php echo $fTitle; ?></h5>
                  <p><?php echo $fDesc; ?></p>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- ── Stats strip (dark) ── -->
        <div style="background:linear-gradient(135deg,#0b0e1a,#111622);border-radius:18px;
                    margin-bottom:50px;overflow:hidden;" data-aos="fade-up">
          <div class="row g-0">
            <?php foreach ($detail['stats'] as [$val,$lbl]): ?>
              <div class="col-6 col-md-3">
                <div class="svc-stat-box">
                  <span class="svc-stat-val"><?php echo $val; ?></span>
                  <div class="svc-stat-lbl"><?php echo $lbl; ?></div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- ── Benefits ── -->
        <div style="background:#fff;border-radius:16px;padding:36px;
                    box-shadow:0 2px 20px rgba(0,0,0,.05);margin-bottom:50px;" data-aos="fade-up">
          <h3 style="font-size:1.2rem;font-weight:800;color:#1a202c;margin-bottom:20px;">
            <?php echo escape(get_translation('svc_benefits_title')); ?>
          </h3>
          <div class="row gy-2">
            <?php foreach ($detail['benefits'] as $benefit): ?>
              <div class="col-md-6">
                <div style="display:flex;align-items:flex-start;gap:12px;">
                  <div style="width:28px;height:28px;border-radius:50%;background:rgba(230,0,0,.08);
                              flex-shrink:0;display:flex;align-items:center;justify-content:center;margin-top:2px;">
                    <i class="bi bi-check2" style="color:#e60000;font-size:.9rem;font-weight:900;"></i>
                  </div>
                  <span style="font-size:.9rem;color:#374151;line-height:1.6;"><?php echo escape($benefit); ?></span>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- ── How We Deploy It ── -->
        <div style="margin-bottom:50px;" data-aos="fade-up">
          <h3 style="font-size:1.2rem;font-weight:800;color:#1a202c;margin-bottom:6px;"><?php echo escape(get_translation('svc_deploy_title')); ?></h3>
          <p style="color:#64748b;font-size:.9rem;margin-bottom:28px;"><?php echo escape(get_translation('svc_deploy_sub')); ?></p>
          <div class="row g-4">
            <?php foreach ($detail['steps'] as [$num,$icon,$stitle,$sdesc]): ?>
              <div class="col-md-4">
                <div class="svc-step" style="text-align:center;padding:28px 16px;">
                  <div class="svc-step-num" style="margin:0 auto 16px;">
                    <?php echo $num; ?>
                  </div>
                  <i class="bi <?php echo $icon; ?>" style="font-size:1.8rem;color:#e60000;margin-bottom:10px;display:block;"></i>
                  <h5 style="font-size:.95rem;font-weight:700;color:#1a202c;margin-bottom:8px;"><?php echo $stitle; ?></h5>
                  <p style="font-size:.855rem;color:#64748b;line-height:1.65;margin:0;"><?php echo $sdesc; ?></p>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- ── FAQ ── -->
        <div style="margin-bottom:50px;" data-aos="fade-up">
          <h3 style="font-size:1.2rem;font-weight:800;color:#1a202c;margin-bottom:6px;"><?php echo escape(get_translation('svc_faq_title')); ?></h3>
          <p style="color:#64748b;font-size:.9rem;margin-bottom:24px;"><?php echo escape(get_translation('svc_faq_sub')); ?></p>
          <div class="accordion svc-accordion" id="svcFaq">
            <?php foreach ($detail['faq'] as $fi => [$question,$answer]): ?>
              <div class="accordion-item">
                <h2 class="accordion-header">
                  <button class="accordion-button <?php echo $fi > 0 ? 'collapsed' : ''; ?>"
                          type="button" data-bs-toggle="collapse"
                          data-bs-target="#faq-<?php echo $fi; ?>">
                    <?php echo escape($question); ?>
                  </button>
                </h2>
                <div id="faq-<?php echo $fi; ?>"
                     class="accordion-collapse collapse <?php echo $fi === 0 ? 'show' : ''; ?>"
                     data-bs-parent="#svcFaq">
                  <div class="accordion-body">
                    <?php echo escape($answer); ?>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>

        <?php endif; // $detail ?>

        <!-- ── Final CTA ── -->
        <div style="background:linear-gradient(135deg,#c40000,#8b0000);border-radius:18px;
                    padding:44px;text-align:center;position:relative;overflow:hidden;" data-aos="fade-up">
          <div style="position:absolute;top:-30px;right:-30px;width:160px;height:160px;
                      border-radius:50%;background:rgba(255,255,255,.05);"></div>
          <div style="position:relative;z-index:1;">
            <h3 style="font-size:1.5rem;font-weight:800;color:#fff;margin-bottom:12px;">
              <?php echo escape(get_translation('svc_ready_title')); ?> <?php echo escape($title); ?>?
            </h3>
            <p style="color:rgba(255,255,255,.8);font-size:.95rem;margin-bottom:28px;max-width:480px;margin-left:auto;margin-right:auto;">
              <?php echo escape(get_translation('svc_cta_sub')); ?>
            </p>
            <div style="display:flex;justify-content:center;flex-wrap:wrap;gap:12px;">
              <a href="<?php echo escape(site_url('contact.php')); ?>"
                 style="display:inline-flex;align-items:center;gap:10px;
                        background:#fff;color:#c40000;font-weight:800;font-size:.95rem;
                        padding:14px 32px;border-radius:50px;text-decoration:none;
                        box-shadow:0 4px 20px rgba(0,0,0,.2);transition:all .25s;"
                 onmouseover="this.style.transform='translateY(-2px)'"
                 onmouseout="this.style.transform='translateY(0)'">
                <i class="bi bi-file-earmark-text-fill"></i> <?php echo escape(get_translation('svc_get_quote')); ?>
              </a>
              <a href="<?php echo escape(site_url('index.php#services')); ?>"
                 style="display:inline-flex;align-items:center;gap:10px;
                        background:rgba(255,255,255,.12);color:#fff;font-weight:700;font-size:.95rem;
                        padding:14px 32px;border-radius:50px;text-decoration:none;
                        border:1.5px solid rgba(255,255,255,.3);transition:all .25s;"
                 onmouseover="this.style.background='rgba(255,255,255,.2)'"
                 onmouseout="this.style.background='rgba(255,255,255,.12)'">
                <i class="bi bi-grid-1x2-fill"></i> <?php echo escape(get_translation('svc_all_services')); ?>
              </a>
            </div>
          </div>
        </div>

      </div><!-- /main content -->
    </div><!-- /row -->
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
