<?php
/**
 * Smartrack – Database Setup
 * Run once via browser or CLI: php setup.php
 * Creates all SQLite tables and seeds default data.
 */
require __DIR__ . '/config.php';

header('Content-Type: text/plain; charset=utf-8');

$pdo = new PDO('sqlite:' . DB_PATH);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->exec('PRAGMA foreign_keys = ON;');
$pdo->exec('PRAGMA journal_mode = WAL;');

// ── Schema ────────────────────────────────────────────────────────────────────

$pdo->exec("
    CREATE TABLE IF NOT EXISTS admins (
        id            INTEGER PRIMARY KEY AUTOINCREMENT,
        username      TEXT    UNIQUE NOT NULL,
        password_hash TEXT    NOT NULL,
        email         TEXT    NOT NULL,
        created_at    DATETIME DEFAULT CURRENT_TIMESTAMP
    );

    CREATE TABLE IF NOT EXISTS services (
        id          INTEGER PRIMARY KEY AUTOINCREMENT,
        slug        TEXT    UNIQUE NOT NULL,
        title_en    TEXT    NOT NULL,
        title_fr    TEXT,
        summary_en  TEXT    NOT NULL,
        summary_fr  TEXT,
        content_en  TEXT,
        content_fr  TEXT,
        image_path  TEXT,
        sort_order  INTEGER DEFAULT 0,
        created_at  DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at  DATETIME DEFAULT CURRENT_TIMESTAMP
    );

    CREATE TABLE IF NOT EXISTS gallery (
        id             INTEGER PRIMARY KEY AUTOINCREMENT,
        image_path     TEXT    NOT NULL,
        description_en TEXT,
        description_fr TEXT,
        sort_order     INTEGER DEFAULT 0,
        created_at     DATETIME DEFAULT CURRENT_TIMESTAMP
    );

    CREATE TABLE IF NOT EXISTS testimonials (
        id         INTEGER PRIMARY KEY AUTOINCREMENT,
        author_en  TEXT    NOT NULL,
        author_fr  TEXT,
        role_en    TEXT,
        role_fr    TEXT,
        quote_en   TEXT    NOT NULL,
        quote_fr   TEXT,
        image_path TEXT,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    );

    CREATE TABLE IF NOT EXISTS contacts (
        id         INTEGER PRIMARY KEY AUTOINCREMENT,
        phone      TEXT,
        email      TEXT,
        address_en TEXT,
        address_fr TEXT,
        facebook   TEXT,
        twitter    TEXT,
        instagram  TEXT,
        linkedin   TEXT,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
    );

    CREATE TABLE IF NOT EXISTS quote_requests (
        id         INTEGER PRIMARY KEY AUTOINCREMENT,
        name       TEXT    NOT NULL,
        email      TEXT    NOT NULL,
        phone      TEXT,
        message    TEXT,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    );

    CREATE TABLE IF NOT EXISTS contact_messages (
        id         INTEGER PRIMARY KEY AUTOINCREMENT,
        name       TEXT    NOT NULL,
        email      TEXT    NOT NULL,
        subject    TEXT,
        message    TEXT    NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    );

    CREATE TABLE IF NOT EXISTS translations (
        id         INTEGER PRIMARY KEY AUTOINCREMENT,
        string_key TEXT    NOT NULL,
        lang       TEXT    NOT NULL,
        value      TEXT    NOT NULL,
        UNIQUE(string_key, lang)
    );

    CREATE TABLE IF NOT EXISTS homepage_content (
        id          INTEGER PRIMARY KEY AUTOINCREMENT,
        content_key TEXT    UNIQUE NOT NULL,
        value       TEXT,
        updated_at  DATETIME DEFAULT CURRENT_TIMESTAMP
    );
");
echo "✓ Tables created\n";

// ── Default admin ─────────────────────────────────────────────────────────────

$stmt = $pdo->prepare('SELECT id FROM admins WHERE username = ?');
$stmt->execute(['admin']);
if (!$stmt->fetch()) {
    $hash = password_hash('admin123', PASSWORD_BCRYPT);
    $pdo->prepare('INSERT INTO admins (username, password_hash, email) VALUES (?, ?, ?)')
        ->execute(['admin', $hash, 'admin@smartrack.com']);
    echo "✓ Admin created  username=admin  password=admin123\n";
} else {
    echo "✓ Admin already exists\n";
}

// ── Default contact info ──────────────────────────────────────────────────────

$stmt = $pdo->query('SELECT id FROM contacts LIMIT 1');
if (!$stmt->fetch()) {
    $pdo->exec("
        INSERT INTO contacts (phone, email, address_en, address_fr)
        VALUES ('+237 600 000 000', 'info@smartrackafrica.com',
                'Suite 019, Immeuble Axia Avenue de Gaulle, B.P 13255 Douala-Bonanjo',
                'Suite 019, Immeuble Axia Avenue de Gaulle, B.P 13255 Douala-Bonanjo')
    ");
    echo "✓ Default contact info inserted\n";
}

// ── Sample services ───────────────────────────────────────────────────────────

$stmt = $pdo->query('SELECT COUNT(*) FROM services');
if ($stmt->fetchColumn() == 0) {
    $services = [
        [
            'vehicle-tracking',
            'Vehicle Tracking',       'Suivi de Véhicules',
            'Real-time GPS tracking for your entire fleet.',
            'Suivi GPS en temps réel pour toute votre flotte.',
            'Monitor every vehicle in your fleet with live GPS data, trip history, speed alerts, and geofencing. Reduce theft and improve driver accountability.',
            'Surveillez chaque véhicule de votre flotte avec des données GPS en direct, l\'historique des trajets, des alertes de vitesse et le géofencing.',
            0,
        ],
        [
            'fuel-monitoring',
            'Fuel Monitoring',         'Surveillance Carburant',
            'Cut fuel costs with precise consumption monitoring.',
            'Réduisez les coûts de carburant grâce à une surveillance précise.',
            'Track fuel usage per vehicle, detect anomalies, and get detailed reports to reduce waste and identify fraud.',
            'Suivez la consommation de carburant par véhicule, détectez les anomalies et obtenez des rapports détaillés.',
            1,
        ],
        [
            'security-solutions',
            'Security Solutions',      'Solutions de Sécurité',
            'Protect your assets with advanced security systems.',
            'Protégez vos actifs avec des systèmes de sécurité avancés.',
            'Combine GPS tracking with alarm systems, remote engine immobilisation, and 24/7 monitoring to keep your vehicles safe.',
            'Combinez le suivi GPS avec des systèmes d\'alarme, l\'immobilisation moteur à distance et une surveillance 24h/24.',
            2,
        ],
    ];

    $stmt = $pdo->prepare('
        INSERT INTO services (slug, title_en, title_fr, summary_en, summary_fr, content_en, content_fr, sort_order)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ');
    foreach ($services as $s) {
        $stmt->execute($s);
    }
    echo "✓ Sample services inserted\n";
}

// ── Sample testimonials ───────────────────────────────────────────────────────

$stmt = $pdo->query('SELECT COUNT(*) FROM testimonials');
if ($stmt->fetchColumn() == 0) {
    $testimonials = [
        ['Jean-Pierre Mbeki', 'Jean-Pierre Mbeki', 'Fleet Manager', 'Gestionnaire de Flotte',
         'Smartrack transformed how we manage our 40-vehicle fleet. Real-time tracking saved us thousands every month.',
         'Smartrack a transformé la gestion de notre flotte de 40 véhicules. Le suivi en temps réel nous économise des milliers chaque mois.'],
        ['Marie Fongang', 'Marie Fongang', 'Operations Director', 'Directrice des Opérations',
         'The fuel monitoring feature alone paid for the entire system within 3 months. Excellent ROI.',
         'La fonction de surveillance du carburant à elle seule a rentabilisé le système en 3 mois. Excellent retour sur investissement.'],
        ['Paul Nkeng', 'Paul Nkeng', 'Business Owner', 'Chef d\'Entreprise',
         'Outstanding support team and reliable tracking. I always know where my vehicles are, day or night.',
         'Équipe de support exceptionnelle et suivi fiable. Je sais toujours où sont mes véhicules, jour et nuit.'],
    ];

    $stmt = $pdo->prepare('
        INSERT INTO testimonials (author_en, author_fr, role_en, role_fr, quote_en, quote_fr)
        VALUES (?, ?, ?, ?, ?, ?)
    ');
    foreach ($testimonials as $t) {
        $stmt->execute($t);
    }
    echo "✓ Sample testimonials inserted\n";
}

// ── Homepage content defaults ────────────────────────────────────────────────

$defaults = [
    'hero_title_en'    => 'Smart GPS Tracking for Every Fleet',
    'hero_title_fr'    => 'Suivi GPS Intelligent pour Chaque Flotte',
    'hero_subtitle_en' => 'Real-time vehicle tracking, fuel monitoring, and security solutions tailored for African businesses.',
    'hero_subtitle_fr' => 'Suivi de véhicules en temps réel, surveillance carburant et solutions de sécurité pour les entreprises africaines.',
];

$upsert = $pdo->prepare('
    INSERT OR IGNORE INTO homepage_content (content_key, value) VALUES (?, ?)
');
foreach ($defaults as $key => $value) {
    $upsert->execute([$key, $value]);
}
echo "✓ Homepage content defaults set\n";

// ── Translations ──────────────────────────────────────────────────────────────

$translations = [
    // Navigation
    ['n1',      'en', 'Home'],
    ['n1',      'fr', 'Accueil'],
    ['n2',      'en', 'About'],
    ['n2',      'fr', 'À Propos'],
    ['n3',      'en', 'Devices'],
    ['n3',      'fr', 'Appareils'],
    ['n4',      'en', 'Career'],
    ['n4',      'fr', 'Carrière'],
    ['abo',     'en', 'Products'],
    ['abo',     'fr', 'Produits'],
    ['fmanage', 'en', 'Fleet Management'],
    ['fmanage', 'fr', 'Gestion de Flotte'],
    ['network', 'en', 'Network Solutions'],
    ['network', 'fr', 'Solutions Réseau'],
    // Hero
    ['welcome',     'en', 'Smart GPS Tracking for Every Fleet'],
    ['welcome',     'fr', 'Suivi GPS Intelligent pour Chaque Flotte'],
    ['description', 'en', 'Real-time vehicle tracking, fuel monitoring, and security solutions.'],
    ['description', 'fr', 'Suivi de véhicules en temps réel, surveillance carburant et solutions de sécurité.'],
    ['start',       'en', 'Get Started'],
    ['start',       'fr', 'Commencer'],
    // Services section
    ['adv',    'en', 'Our Advantages'],
    ['adv',    'fr', 'Nos Avantages'],
    ['empower','en', 'We empower businesses with cutting-edge GPS tracking technology.'],
    ['empower','fr', 'Nous dotons les entreprises de technologies de suivi GPS de pointe.'],
    // Gallery
    ['blog', 'en', 'Our Gallery'],
    ['blog', 'fr', 'Notre Galerie'],
    // Testimonials
    ['testimony', 'en', 'What Our Clients Say'],
    ['testimony', 'fr', 'Ce Que Disent Nos Clients'],
    // Contact CTA
    ['request', 'en', 'Request a Quote'],
    ['request', 'fr', 'Demander un Devis'],
    ['form',    'en', 'Fill in the form and we will get back to you shortly.'],
    ['form',    'fr', 'Remplissez le formulaire et nous vous répondrons rapidement.'],
    ['quote',   'en', 'Get a Quote'],
    ['quote',   'fr', 'Obtenir un Devis'],
    // About page
    ['abou', 'en', 'About Us'],
    ['abou', 'fr', 'À Propos de Nous'],
    ['wh',   'en', 'Who We Are'],
    ['wh',   'fr', 'Qui Sommes-Nous'],
    ['lead', 'en', 'Smartrack is a leading provider of GPS tracking and fleet management solutions in Africa.'],
    ['lead', 'fr', 'Smartrack est un leader des solutions de suivi GPS et de gestion de flotte en Afrique.'],
    ['since','en', 'Since Our Launch'],
    ['since','fr', 'Depuis Notre Lancement'],
    ['happy','en', 'Happy Clients'],
    ['happy','fr', 'Clients Satisfaits'],
    ['proj', 'en', 'Projects Completed'],
    ['proj', 'fr', 'Projets Réalisés'],
    ['hours','en', '24/7 Support'],
    ['hours','fr', 'Support 24/7'],
    // Devices page
    ['ft',          'en', 'Fleet Tracking'],
    ['ft',          'fr', 'Suivi de Flotte'],
    ['tor',         'en', 'Real-time GPS tracking for all your vehicles.'],
    ['tor',         'fr', 'Suivi GPS en temps réel pour tous vos véhicules.'],
    ['fuel',        'en', 'Fuel Monitoring'],
    ['fuel',        'fr', 'Surveillance Carburant'],
    ['ourfuel',     'en', 'Monitor fuel consumption and reduce costs.'],
    ['ourfuel',     'fr', 'Surveillez la consommation de carburant et réduisez les coûts.'],
    ['networksys',  'en', 'Comprehensive network security solutions.'],
    ['networksys',  'fr', 'Solutions de sécurité réseau complètes.'],
    ['ourtracking', 'en', 'Our tracking devices provide real-time location data for your assets.'],
    ['ourtracking', 'fr', 'Nos appareils de suivi fournissent des données de localisation en temps réel.'],
    // Footer / misc
    ['terms', 'en', 'Admin'],
    ['terms', 'fr', 'Admin'],
    // Service detail page
    ['learn_more', 'en', 'Learn More'],
    ['learn_more', 'fr', 'En Savoir Plus'],
    ['get_service','en', 'Get This Service'],
    ['get_service','fr', 'Obtenir ce Service'],
    // SmartFleet / SmartSolution pages
    ['sf_title',  'en', 'Smart Fleet Management'],
    ['sf_title',  'fr', 'Gestion de Flotte Intelligente'],
    ['sf_desc',   'en', 'Complete fleet management with real-time GPS tracking, route optimisation, driver behaviour monitoring, and automated reporting.'],
    ['sf_desc',   'fr', 'Gestion complète de flotte avec suivi GPS en temps réel, optimisation des itinéraires, surveillance du comportement des conducteurs et rapports automatisés.'],
    ['ss_title',  'en', 'Smart Network Solutions'],
    ['ss_title',  'fr', 'Solutions Réseau Intelligentes'],
    ['ss_desc',   'en', 'End-to-end network security and connectivity solutions for businesses of all sizes across Africa.'],
    ['ss_desc',   'fr', 'Solutions de sécurité réseau et de connectivité de bout en bout pour les entreprises de toutes tailles en Afrique.'],
];

$stmt = $pdo->prepare('INSERT OR IGNORE INTO translations (string_key, lang, value) VALUES (?, ?, ?)');
foreach ($translations as $row) {
    $stmt->execute($row);
}
echo "✓ Translations seeded (" . count($translations) . " strings)\n";

// ── Done ──────────────────────────────────────────────────────────────────────

echo "\n========================================\n";
echo "Setup complete!\n";
echo "Database: " . DB_PATH . "\n";
echo "----------------------------------------\n";
echo "Admin login\n";
echo "  URL:      /smartrack/admin/login.php\n";
echo "  Username: admin\n";
echo "  Password: admin123\n";
echo "========================================\n";
echo "\nDELETE THIS FILE after setup.\n";
