<?php
require_once __DIR__ . '/includes/functions.php';

$lang = current_language();
$id   = (int)($_GET['id'] ?? 0);

if (!$id) { redirect(site_url('blog.php')); }

$stmt = db()->prepare('SELECT * FROM blog_posts WHERE id = ? AND is_published = 1 LIMIT 1');
$stmt->execute([$id]);
$post = $stmt->fetch();

if (!$post) {
    http_response_code(404);
    $pageTitle = 'Not Found';
    $bodyClass = 'blog-page';
    define('APP_INIT', true);
    include __DIR__ . '/includes/header.php';
    echo '<div class="container py-5 text-center"><h2>' . escape(get_translation('blogpost_not_found')) . '</h2>
          <a href="' . escape(site_url('blog.php')) . '" class="btn-get-started">' . escape(get_translation('blogpost_back')) . '</a></div>';
    include __DIR__ . '/includes/footer.php';
    exit;
}

// Pick language
$title   = ($lang === 'fr' && $post['title_fr'])   ? $post['title_fr']   : $post['title_en'];
$excerpt = ($lang === 'fr' && $post['excerpt_fr'])  ? $post['excerpt_fr'] : $post['excerpt_en'];
$content = ($lang === 'fr' && $post['content_fr'])  ? $post['content_fr'] : $post['content_en'];

// Format content: treat blank lines as paragraph breaks
$paragraphs = array_filter(array_map('trim', explode("\n\n", $content)));

// Image source
$imgSrc = $post['image_path'] ? site_url($post['image_path']) : site_url('assets/img/blog/blog-1.jpg');

// Related posts (same category, excluding this one)
$rel = db()->prepare(
    'SELECT id, title_en, title_fr, image_path, published_at, category
     FROM blog_posts WHERE is_published = 1 AND category = ? AND id != ?
     ORDER BY published_at DESC LIMIT 3'
);
$rel->execute([$post['category'], $id]);
$related = $rel->fetchAll();

// Recent posts for sidebar
$recent = db()->query(
    'SELECT id, title_en, title_fr, image_path, published_at
     FROM blog_posts WHERE is_published = 1
     ORDER BY published_at DESC LIMIT 5'
)->fetchAll();

$pageTitle = $title . ' - Smartrack Blog';
$bodyClass = 'blog-details-page';

define('APP_INIT', true);
include __DIR__ . '/includes/header.php';
?>

<!-- Page Title -->
<div class="page-title dark-background"
     style="background-image:url(<?php echo escape(site_url('assets/img/page-title-bg.jpg')); ?>);">
  <div class="container position-relative">
    <h1><?php echo escape($title); ?></h1>
    <nav class="breadcrumbs">
      <ol>
        <li><a href="<?php echo escape(site_url('index.php')); ?>"><?php echo escape(get_translation('breadcrumb_home')); ?></a></li>
        <li><a href="<?php echo escape(site_url('blog.php')); ?>"><?php echo escape(get_translation('breadcrumb_blog')); ?></a></li>
        <li class="current"><?php echo escape(mb_substr($title, 0, 50)) . (mb_strlen($title) > 50 ? '…' : ''); ?></li>
      </ol>
    </nav>
  </div>
</div>

<!-- Blog Details -->
<section class="blog-details section">
  <div class="container" data-aos="fade-up">
    <div class="row g-5">

      <!-- ── Main article ── -->
      <div class="col-lg-8">
        <article class="article">

          <!-- Featured image -->
          <div class="mb-4" style="width:100%;height:420px;border-radius:12px;overflow:hidden;">
            <img src="<?php echo escape($imgSrc); ?>" alt="<?php echo escape($title); ?>"
                 style="width:100%;height:100%;object-fit:cover;object-position:center;display:block;">
          </div>

          <!-- Meta row -->
          <div class="meta-top d-flex flex-wrap align-items-center gap-3 mb-3"
               style="font-size:.85rem;color:#888;">
            <span style="background:rgba(255,0,0,.08);color:var(--accent-color);font-size:.72rem;font-weight:700;
                         padding:4px 14px;border-radius:20px;letter-spacing:.05em;text-transform:uppercase;">
              <?php echo escape($post['category']); ?>
            </span>
            <span><i class="bi bi-person me-1"></i><?php echo escape($post['author']); ?></span>
            <span><i class="bi bi-calendar3 me-1"></i><?php echo date('F j, Y', strtotime($post['published_at'])); ?></span>
          </div>

          <!-- Title -->
          <h2 class="title" style="font-size:1.8rem;font-weight:700;margin-bottom:12px;">
            <?php echo escape($title); ?>
          </h2>

          <!-- Excerpt / lead -->
          <?php if ($excerpt): ?>
            <p class="lead" style="font-size:1.05rem;color:#555;font-style:italic;
                                    border-left:4px solid var(--accent-color);
                                    padding:12px 20px;background:#fff9f9;
                                    border-radius:0 8px 8px 0;margin-bottom:28px;">
              <?php echo escape($excerpt); ?>
            </p>
          <?php endif; ?>

          <!-- Content -->
          <div class="content" style="line-height:1.85;color:#444;font-size:.975rem;">
            <?php foreach ($paragraphs as $para):
              // Detect section headings (ALL CAPS lines)
              if (preg_match('/^[A-Z][A-Z0-9 \'\-:\/]+$/', trim($para)) && strlen($para) < 80):
            ?>
              <h4 style="margin:32px 0 12px;font-size:1.05rem;font-weight:700;
                          color:var(--heading-color);text-transform:uppercase;
                          letter-spacing:.08em;border-bottom:2px solid var(--accent-color);
                          padding-bottom:6px;display:inline-block;">
                <?php echo escape($para); ?>
              </h4>
            <?php else: ?>
              <p><?php echo escape($para); ?></p>
            <?php endif; ?>
            <?php endforeach; ?>
          </div>

          <!-- Tags / share -->
          <div class="meta-bottom d-flex align-items-center justify-content-between flex-wrap gap-3 mt-5 pt-4"
               style="border-top:1px solid #e9ecef;">
            <div>
              <i class="bi bi-tag me-2" style="color:var(--accent-color);"></i>
              <span class="fw-semibold me-2"><?php echo escape(get_translation('blogpost_category')); ?></span>
              <a href="<?php echo escape(site_url('blog.php?cat=' . urlencode($post['category']))); ?>"
                 style="color:var(--accent-color);font-weight:600;">
                <?php echo escape($post['category']); ?>
              </a>
            </div>
            <div class="d-flex gap-2">
              <span class="fw-semibold me-2" style="font-size:.85rem;color:#888;"><?php echo escape(get_translation('blogpost_share')); ?></span>
              <a href="https://twitter.com/intent/tweet?text=<?php echo urlencode($title); ?>&url=<?php echo urlencode(site_url('blog-post.php?id=' . $id)); ?>"
                 target="_blank" style="color:#1da1f2;font-size:1.2rem;"><i class="bi bi-twitter-x"></i></a>
              <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(site_url('blog-post.php?id=' . $id)); ?>"
                 target="_blank" style="color:#1877f2;font-size:1.2rem;"><i class="bi bi-facebook"></i></a>
              <a href="https://wa.me/?text=<?php echo urlencode($title . ' ' . site_url('blog-post.php?id=' . $id)); ?>"
                 target="_blank" style="color:#25d366;font-size:1.2rem;"><i class="bi bi-whatsapp"></i></a>
            </div>
          </div>

        </article>

        <!-- Related posts -->
        <?php if (!empty($related)): ?>
        <div class="mt-5">
          <h4 style="font-size:1.1rem;font-weight:700;margin-bottom:24px;padding-bottom:12px;border-bottom:2px solid var(--accent-color);display:inline-block;">
            <?php echo escape(get_translation('blogpost_related')); ?>
          </h4>
          <div class="row gy-4">
            <?php foreach ($related as $r):
              $rt = ($lang === 'fr' && $r['title_fr']) ? $r['title_fr'] : $r['title_en'];
              $ri = $r['image_path'] ? site_url($r['image_path']) : site_url('assets/img/blog/blog-1.jpg');
            ?>
              <div class="col-md-4">
                <div class="post-item position-relative h-100">
                  <div class="post-img position-relative overflow-hidden"
                       style="height:180px;display:block;">
                    <img src="<?php echo escape($ri); ?>"
                         alt="<?php echo escape($rt); ?>"
                         style="width:100%;height:100%;object-fit:cover;object-position:center;display:block;">
                    <span class="post-date"><?php echo date('M j', strtotime($r['published_at'])); ?></span>
                  </div>
                  <div class="post-content d-flex flex-column" style="padding:14px;">
                    <h3 class="post-title" style="font-size:.9rem;"><?php echo escape($rt); ?></h3>
                    <hr>
                    <a href="<?php echo escape(site_url('blog-post.php?id=' . $r['id'])); ?>"
                       class="readmore stretched-link" style="font-size:.82rem;">
                      <span><?php echo escape(get_translation('blog_read_more')); ?></span><i class="bi bi-arrow-right"></i>
                    </a>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>

        <!-- Back link -->
        <div class="mt-5">
          <a href="<?php echo escape(site_url('blog.php')); ?>" class="btn-get-started"
             style="display:inline-flex;align-items:center;gap:8px;">
            <i class="bi bi-arrow-left"></i> <?php echo escape(get_translation('blogpost_back')); ?>
          </a>
        </div>

      </div><!-- /col-lg-8 -->

      <!-- ── Sidebar ── -->
      <div class="col-lg-4">

        <!-- Recent posts widget -->
        <div class="sidebar-widget" style="background:#fff;border-radius:12px;padding:28px;
                                            box-shadow:0 2px 20px rgba(0,0,0,.07);margin-bottom:28px;">
          <h5 style="font-size:1rem;font-weight:700;margin-bottom:20px;
                     padding-bottom:12px;border-bottom:2px solid var(--accent-color);">
            <?php echo escape(get_translation('blogpost_recent')); ?>
          </h5>
          <?php foreach ($recent as $r):
            $rt = ($lang === 'fr' && $r['title_fr']) ? $r['title_fr'] : $r['title_en'];
            $ri = $r['image_path'] ? site_url($r['image_path']) : site_url('assets/img/blog/blog-1.jpg');
          ?>
            <div class="d-flex gap-3 mb-3 <?php echo $r['id'] === $id ? 'opacity-50' : ''; ?>">
              <img src="<?php echo escape($ri); ?>" alt=""
                   style="width:64px;height:52px;object-fit:cover;border-radius:8px;flex-shrink:0;">
              <div>
                <a href="<?php echo escape(site_url('blog-post.php?id=' . $r['id'])); ?>"
                   style="font-size:.85rem;font-weight:600;color:var(--heading-color);text-decoration:none;
                           display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;line-height:1.4;">
                  <?php echo escape($rt); ?>
                </a>
                <div style="font-size:.75rem;color:#aaa;margin-top:3px;">
                  <?php echo date('M j, Y', strtotime($r['published_at'])); ?>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>

        <!-- Categories widget -->
        <div class="sidebar-widget" style="background:#fff;border-radius:12px;padding:28px;
                                            box-shadow:0 2px 20px rgba(0,0,0,.07);margin-bottom:28px;">
          <h5 style="font-size:1rem;font-weight:700;margin-bottom:20px;
                     padding-bottom:12px;border-bottom:2px solid var(--accent-color);">
            <?php echo escape(get_translation('blogpost_categories')); ?>
          </h5>
          <?php
          $catCounts = db()->query(
              'SELECT category, COUNT(*) as n FROM blog_posts WHERE is_published = 1 GROUP BY category ORDER BY n DESC'
          )->fetchAll();
          foreach ($catCounts as $cc):
          ?>
            <a href="<?php echo escape(site_url('blog.php?cat=' . urlencode($cc['category']))); ?>"
               class="d-flex justify-content-between align-items-center py-2"
               style="text-decoration:none;color:var(--default-color);border-bottom:1px solid #f1f1f1;font-size:.9rem;transition:.2s;"
               onmouseover="this.style.color='var(--accent-color)'" onmouseout="this.style.color='var(--default-color)'">
              <span><i class="bi bi-chevron-right me-2" style="color:var(--accent-color);font-size:.75rem;"></i>
                <?php echo escape($cc['category']); ?>
              </span>
              <span style="background:rgba(255,0,0,.08);color:var(--accent-color);
                            font-size:.72rem;font-weight:700;padding:2px 10px;border-radius:20px;">
                <?php echo $cc['n']; ?>
              </span>
            </a>
          <?php endforeach; ?>
        </div>

        <!-- CTA widget -->
        <div style="background:var(--accent-color);color:#fff;border-radius:12px;padding:28px;text-align:center;">
          <i class="bi bi-geo-alt-fill" style="font-size:2.5rem;opacity:.8;"></i>
          <h5 class="mt-3 mb-2"><?php echo escape(get_translation('blogpost_cta_title')); ?></h5>
          <p style="font-size:.875rem;opacity:.85;margin-bottom:20px;">
            <?php echo escape(get_translation('blogpost_cta_sub')); ?>
          </p>
          <a href="<?php echo escape(site_url('contact.php')); ?>"
             style="background:#fff;color:var(--accent-color);font-weight:700;padding:10px 24px;
                     border-radius:30px;text-decoration:none;font-size:.875rem;display:inline-block;">
            <?php echo escape(get_translation('blogpost_contact_us')); ?>
          </a>
        </div>

      </div><!-- /sidebar -->

    </div>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
