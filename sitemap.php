<?php
/**
 * Dynamic XML sitemap — includes every public page plus all published blog
 * posts and services, with EN/FR alternates so Google indexes both languages.
 * Reachable at /sitemap.php (and /sitemap.xml via the .htaccess rewrite).
 */
require_once __DIR__ . '/includes/functions.php';

$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$root   = $scheme . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . rtrim(BASE_URL, '/');

$urls = [
    ['index.php',         '1.0', 'weekly'],
    ['about.php',         '0.8', 'monthly'],
    ['SmartFleet.php',    '0.9', 'monthly'],
    ['SmartSolution.php', '0.9', 'monthly'],
    ['devices.php',       '0.8', 'monthly'],
    ['blog.php',          '0.7', 'weekly'],
    ['contact.php',       '0.7', 'monthly'],
    ['career.php',        '0.6', 'weekly'],
];

// Services
try {
    foreach (db()->query('SELECT id FROM services ORDER BY id')->fetchAll(PDO::FETCH_COLUMN) as $id) {
        $urls[] = ['service.php?id=' . $id, '0.8', 'monthly'];
    }
} catch (Throwable $e) { /* table may be empty */ }

// Published blog posts (with last-modified date)
$posts = [];
try {
    $posts = db()->query(
        'SELECT id, COALESCE(updated_at, published_at) AS mod FROM blog_posts
         WHERE is_published = 1 ORDER BY published_at DESC'
    )->fetchAll();
} catch (Throwable $e) { /* ignore */ }

header('Content-Type: application/xml; charset=utf-8');
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:xhtml="http://www.w3.org/1999/xhtml">
<?php
$emit = function (string $path, string $priority, string $freq, ?string $lastmod = null) use ($root) {
    $loc = $root . '/' . $path;
    $sep = str_contains($path, '?') ? '&amp;' : '?';
    echo "  <url>\n";
    echo "    <loc>" . htmlspecialchars($loc, ENT_XML1) . "</loc>\n";
    if ($lastmod) {
        $ts = strtotime($lastmod);
        if ($ts) echo "    <lastmod>" . date('Y-m-d', $ts) . "</lastmod>\n";
    }
    echo "    <changefreq>$freq</changefreq>\n";
    echo "    <priority>$priority</priority>\n";
    echo '    <xhtml:link rel="alternate" hreflang="en" href="' . htmlspecialchars($loc . $sep . 'lang=en', ENT_XML1) . "\"/>\n";
    echo '    <xhtml:link rel="alternate" hreflang="fr" href="' . htmlspecialchars($loc . $sep . 'lang=fr', ENT_XML1) . "\"/>\n";
    echo "  </url>\n";
};

foreach ($urls as [$p, $pri, $fq]) { $emit($p, $pri, $fq); }
foreach ($posts as $post)          { $emit('blog-post.php?id=' . $post['id'], '0.6', 'monthly', $post['mod'] ?? null); }
?>
</urlset>
