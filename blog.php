<?php
require_once __DIR__ . '/includes/functions.php';

$pageTitle = t('Blog - Smartrack', 'Blog - Smartrack');
$bodyClass = 'blog-page';
$lang      = current_language();

// Pagination
$perPage  = 9;
$page     = max(1, (int)($_GET['page'] ?? 1));
$offset   = ($page - 1) * $perPage;

// Category filter
$catFilter = trim($_GET['cat'] ?? '');

// Count total
if ($catFilter) {
    $totalStmt = db()->prepare(
        'SELECT COUNT(*) FROM blog_posts WHERE is_published = 1 AND category = ?'
    );
    $totalStmt->execute([$catFilter]);
} else {
    $totalStmt = db()->query('SELECT COUNT(*) FROM blog_posts WHERE is_published = 1');
}
$total     = (int) $totalStmt->fetchColumn();
$totalPages = max(1, (int) ceil($total / $perPage));
$page       = min($page, $totalPages);

// Fetch posts
if ($catFilter) {
    $stmt = db()->prepare(
        'SELECT * FROM blog_posts WHERE is_published = 1 AND category = ?
         ORDER BY published_at DESC LIMIT ? OFFSET ?'
    );
    $stmt->execute([$catFilter, $perPage, $offset]);
} else {
    $stmt = db()->prepare(
        'SELECT * FROM blog_posts WHERE is_published = 1
         ORDER BY published_at DESC LIMIT ? OFFSET ?'
    );
    $stmt->execute([$perPage, $offset]);
}
$posts = $stmt->fetchAll();

// All categories for filter
$cats = db()->query(
    'SELECT DISTINCT category FROM blog_posts WHERE is_published = 1 ORDER BY category'
)->fetchAll(PDO::FETCH_COLUMN);

define('APP_INIT', true);
include __DIR__ . '/includes/header.php';
?>

<!-- Page Title -->
<div class="page-title dark-background"
     style="background-image:url(<?php echo escape(site_url('assets/img/page-title-bg.jpg')); ?>);">
  <div class="container position-relative">
    <h1><?php echo escape(get_translation('blog_page_title')); ?></h1>
    <nav class="breadcrumbs">
      <ol>
        <li><a href="<?php echo escape(site_url('index.php')); ?>"><?php echo escape(get_translation('breadcrumb_home')); ?></a></li>
        <li class="current"><?php echo escape(get_translation('breadcrumb_blog')); ?></li>
      </ol>
    </nav>
  </div>
</div>

<!-- Blog Section -->
<section class="blog-posts section">
  <div class="container">

    <!-- Category filter pills -->
    <?php if (!empty($cats)): ?>
    <div class="d-flex flex-wrap gap-2 mb-5" data-aos="fade-up">
      <a href="<?php echo escape(site_url('blog.php')); ?>"
         class="btn <?php echo $catFilter === '' ? 'btn-danger' : 'btn-outline-secondary'; ?>"
         style="border-radius:30px;padding:6px 20px;font-size:.85rem;font-weight:600;">
        <?php echo escape(get_translation('blog_filter_all')); ?>
      </a>
      <?php foreach ($cats as $cat): ?>
        <a href="<?php echo escape(site_url('blog.php?cat=' . urlencode($cat))); ?>"
           class="btn <?php echo $catFilter === $cat ? 'btn-danger' : 'btn-outline-secondary'; ?>"
           style="border-radius:30px;padding:6px 20px;font-size:.85rem;font-weight:600;">
          <?php echo escape($cat); ?>
        </a>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php if (empty($posts)): ?>
      <div class="text-center py-5">
        <i class="bi bi-journal-x" style="font-size:3rem;color:#ccc;"></i>
        <p class="mt-3" style="color:#888;"><?php echo escape(get_translation('blog_no_posts')); ?><?php echo $catFilter ? escape(get_translation('blog_in_category')) : ''; ?>.</p>
      </div>
    <?php else: ?>
    <div class="row gy-5">
      <?php foreach ($posts as $i => $post):
        $title   = ($lang === 'fr' && $post['title_fr'])   ? $post['title_fr']   : $post['title_en'];
        $excerpt = ($lang === 'fr' && $post['excerpt_fr'])  ? $post['excerpt_fr'] : $post['excerpt_en'];
        $imgSrc  = $post['image_path']
                   ? (str_starts_with($post['image_path'], 'upload')
                      ? site_url($post['image_path'])
                      : site_url($post['image_path']))
                   : site_url('assets/img/blog/blog-1.jpg');
      ?>
      <div class="col-xl-4 col-md-6" data-aos="fade-up" data-aos-delay="<?php echo ($i % 3 + 1) * 100; ?>">
        <div class="post-item position-relative h-100">

          <div class="post-img position-relative overflow-hidden">
            <img src="<?php echo escape($imgSrc); ?>" class="img-fluid" alt="<?php echo escape($title); ?>">
            <span class="post-date"><?php echo date('M j', strtotime($post['published_at'])); ?></span>
          </div>

          <div class="post-content d-flex flex-column">
            <!-- Category badge -->
            <span style="display:inline-block;background:rgba(255,0,0,.08);color:var(--accent-color);font-size:.72rem;font-weight:700;padding:3px 12px;border-radius:20px;margin-bottom:8px;letter-spacing:.05em;text-transform:uppercase;width:fit-content;">
              <?php echo escape($post['category']); ?>
            </span>

            <h3 class="post-title"><?php echo escape($title); ?></h3>

            <?php if ($excerpt): ?>
              <p style="font-size:.875rem;color:#666;line-height:1.6;margin-bottom:12px;
                         display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                <?php echo escape($excerpt); ?>
              </p>
            <?php endif; ?>

            <div class="meta d-flex align-items-center">
              <div class="d-flex align-items-center">
                <i class="bi bi-person"></i>
                <span class="ps-2"><?php echo escape($post['author']); ?></span>
              </div>
              <span class="px-3 text-black-50">/</span>
              <div class="d-flex align-items-center">
                <i class="bi bi-folder2"></i>
                <span class="ps-2"><?php echo escape($post['category']); ?></span>
              </div>
            </div>

            <hr>
            <a href="<?php echo escape(site_url('blog-post.php?id=' . $post['id'])); ?>"
               class="readmore stretched-link">
              <span><?php echo escape(get_translation('blog_read_more')); ?></span>
              <i class="bi bi-arrow-right"></i>
            </a>
          </div>

        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
    <div class="d-flex justify-content-center mt-5 gap-2" data-aos="fade-up">
      <?php if ($page > 1): ?>
        <a href="?page=<?php echo $page - 1; ?><?php echo $catFilter ? '&cat=' . urlencode($catFilter) : ''; ?>"
           class="btn btn-outline-secondary" style="border-radius:30px;padding:6px 20px;">
          <?php echo escape(get_translation('blog_prev')); ?>
        </a>
      <?php endif; ?>

      <?php for ($p = 1; $p <= $totalPages; $p++): ?>
        <a href="?page=<?php echo $p; ?><?php echo $catFilter ? '&cat=' . urlencode($catFilter) : ''; ?>"
           class="btn <?php echo $p === $page ? 'btn-danger' : 'btn-outline-secondary'; ?>"
           style="border-radius:30px;padding:6px 16px;">
          <?php echo $p; ?>
        </a>
      <?php endfor; ?>

      <?php if ($page < $totalPages): ?>
        <a href="?page=<?php echo $page + 1; ?><?php echo $catFilter ? '&cat=' . urlencode($catFilter) : ''; ?>"
           class="btn btn-outline-secondary" style="border-radius:30px;padding:6px 20px;">
          <?php echo escape(get_translation('blog_next')); ?>
        </a>
      <?php endif; ?>
    </div>
    <?php endif; ?>

    <?php endif; ?>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
