<?php
require_once __DIR__ . '/../includes/functions.php';
require_admin();
$pageTitle = 'Blog';
$error  = '';
$saved  = false;
$action = $_POST['action'] ?? '';

$categories = ['Fleet Management','GPS Technology','Technology','Security','General'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid security token.';

    } elseif ($action === 'delete' && !empty($_POST['post_id'])) {
        // Delete image file if it's an upload
        $r = db()->prepare('SELECT image_path FROM blog_posts WHERE id = ?');
        $r->execute([(int)$_POST['post_id']]);
        $row = $r->fetch();
        if ($row && $row['image_path'] && str_starts_with($row['image_path'], 'uploads/')) {
            $full = __DIR__ . '/../../' . $row['image_path'];
            if (file_exists($full)) @unlink($full);
        }
        db()->prepare('DELETE FROM blog_posts WHERE id = ?')
           ->execute([(int)$_POST['post_id']]);
        redirect('blog.php');

    } elseif ($action === 'toggle' && !empty($_POST['post_id'])) {
        $id  = (int)$_POST['post_id'];
        $cur = db()->prepare('SELECT is_published FROM blog_posts WHERE id = ?');
        $cur->execute([$id]);
        $row = $cur->fetch();
        db()->prepare('UPDATE blog_posts SET is_published = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?')
           ->execute([1 - (int)($row['is_published'] ?? 1), $id]);
        redirect('blog.php');

    } elseif ($action === 'save') {
        $id        = !empty($_POST['post_id']) ? (int)$_POST['post_id'] : null;
        $titleEn   = trim($_POST['title_en']   ?? '');
        $titleFr   = trim($_POST['title_fr']   ?? '');
        $slug      = trim($_POST['slug']        ?? '');
        $excerptEn = trim($_POST['excerpt_en']  ?? '');
        $excerptFr = trim($_POST['excerpt_fr']  ?? '');
        $contentEn = trim($_POST['content_en']  ?? '');
        $contentFr = trim($_POST['content_fr']  ?? '');
        $author    = trim($_POST['author']       ?? 'Smartrack Team');
        $category  = trim($_POST['category']     ?? 'General');
        $pubDate   = trim($_POST['published_at'] ?? date('Y-m-d H:i:s'));
        $published = isset($_POST['is_published']) ? 1 : 0;

        // Slug fallback
        if ($slug === '') {
            $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $titleEn));
            $slug = trim($slug, '-');
        }

        if ($titleEn === '') {
            $error = 'English title is required.';
        } else {
            // Image upload
            $imagePath = null;
            try {
                $up = upload_image('image', 'blog');
                if ($up) $imagePath = ltrim($up, '/');
            } catch (RuntimeException $e) {
                $error = $e->getMessage();
            }

            if (!$error) {
                if ($id) {
                    $sets = 'title_en=?,title_fr=?,slug=?,excerpt_en=?,excerpt_fr=?,content_en=?,
                              content_fr=?,author=?,category=?,is_published=?,published_at=?,
                              updated_at=CURRENT_TIMESTAMP';
                    $vals = [$titleEn,$titleFr,$slug,$excerptEn,$excerptFr,$contentEn,$contentFr,
                             $author,$category,$published,$pubDate];
                    if ($imagePath) {
                        $sets .= ',image_path=?';
                        $vals[] = $imagePath;
                    }
                    $vals[] = $id;
                    db()->prepare("UPDATE blog_posts SET $sets WHERE id=?")->execute($vals);
                } else {
                    db()->prepare('
                        INSERT INTO blog_posts
                            (title_en,title_fr,slug,excerpt_en,excerpt_fr,content_en,content_fr,
                             image_path,author,category,is_published,published_at)
                        VALUES (?,?,?,?,?,?,?,?,?,?,?,?)
                    ')->execute([
                        $titleEn,$titleFr,$slug,$excerptEn,$excerptFr,$contentEn,$contentFr,
                        $imagePath,'Smartrack Team',$category,$published,$pubDate
                    ]);
                }
                redirect('blog.php?saved=1');
            }
        }
    }
}

$saved   = isset($_GET['saved']);
$posts   = db()->query('SELECT * FROM blog_posts ORDER BY published_at DESC')->fetchAll();
$editing = null;
if (!empty($_GET['edit'])) {
    $s = db()->prepare('SELECT * FROM blog_posts WHERE id = ? LIMIT 1');
    $s->execute([(int)$_GET['edit']]);
    $editing = $s->fetch();
}

define('APP_INIT_ADMIN', true);
include __DIR__ . '/_header.php';
?>

<div class="page-header">
  <div>
    <h1 class="page-title">Blog Posts</h1>
    <p class="page-subtitle"><?php echo count($posts); ?> post<?php echo count($posts) !== 1 ? 's' : ''; ?> — manage articles shown on the blog page.</p>
  </div>
  <a href="<?php echo escape(site_url('blog.php')); ?>" target="_blank" class="btn btn-secondary">
    <i class="bi bi-eye"></i> View Blog
  </a>
</div>

<?php if ($error): ?>
  <div class="admin-alert danger"><i class="bi bi-exclamation-triangle-fill admin-alert-icon"></i><?php echo escape($error); ?></div>
<?php endif; ?>
<?php if ($saved): ?>
  <div class="admin-alert success"><i class="bi bi-check-circle-fill admin-alert-icon"></i>Blog post saved successfully.</div>
<?php endif; ?>

<div style="display:grid;grid-template-columns:460px 1fr;gap:24px;align-items:start;">

  <!-- ── Form ──────────────────────────────────────────────── -->
  <div class="admin-card" style="position:sticky;top:calc(var(--topbar-h) + 20px);">
    <div class="admin-card-header">
      <div class="admin-card-title">
        <i class="bi bi-<?php echo $editing ? 'pencil-fill' : 'plus-circle-fill'; ?>"></i>
        <?php echo $editing ? 'Edit Post' : 'New Post'; ?>
      </div>
      <?php if ($editing): ?>
        <a href="blog.php" class="btn btn-sm btn-secondary">Cancel</a>
      <?php endif; ?>
    </div>
    <div class="admin-card-body">
      <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?php echo escape(csrf_token()); ?>">
        <input type="hidden" name="action"   value="save">
        <?php if ($editing): ?>
          <input type="hidden" name="post_id" value="<?php echo $editing['id']; ?>">
        <?php endif; ?>

        <!-- Language tabs -->
        <div class="lang-tabs mb-4">
          <button type="button" class="lang-tab active" data-lang="en">🇬🇧 English</button>
          <button type="button" class="lang-tab" data-lang="fr">🇫🇷 French</button>
        </div>

        <!-- EN -->
        <div id="fields-en">
          <div class="form-group">
            <label class="form-label">Title (EN) <span class="required">*</span></label>
            <input class="form-control" name="title_en" id="titleEn"
                   value="<?php echo escape($editing['title_en'] ?? ''); ?>"
                   placeholder="Post title in English" required>
          </div>
          <div class="form-group">
            <label class="form-label">Excerpt (EN) <span style="font-weight:400;color:var(--text-muted);">— shown on listing cards</span></label>
            <textarea class="form-control" name="excerpt_en" rows="2"
                      placeholder="One or two sentences summarising the post."><?php echo escape($editing['excerpt_en'] ?? ''); ?></textarea>
          </div>
          <div class="form-group">
            <label class="form-label">Full Content (EN)</label>
            <textarea class="form-control" name="content_en" rows="8"
                      placeholder="Write the full article. Separate paragraphs with a blank line. ALL CAPS lines become sub-headings."><?php echo escape($editing['content_en'] ?? ''); ?></textarea>
            <p class="form-hint">Tip: Write section headings in ALL CAPS on their own line — they display as styled headings.</p>
          </div>
        </div>

        <!-- FR -->
        <div id="fields-fr" style="display:none;">
          <div class="form-group">
            <label class="form-label">Title (FR)</label>
            <input class="form-control" name="title_fr"
                   value="<?php echo escape($editing['title_fr'] ?? ''); ?>"
                   placeholder="Titre en français">
          </div>
          <div class="form-group">
            <label class="form-label">Excerpt (FR)</label>
            <textarea class="form-control" name="excerpt_fr" rows="2"><?php echo escape($editing['excerpt_fr'] ?? ''); ?></textarea>
          </div>
          <div class="form-group">
            <label class="form-label">Full Content (FR)</label>
            <textarea class="form-control" name="content_fr" rows="8"><?php echo escape($editing['content_fr'] ?? ''); ?></textarea>
          </div>
        </div>

        <!-- Slug -->
        <div class="form-group">
          <label class="form-label">Slug (URL)</label>
          <div style="display:flex;gap:8px;">
            <input class="form-control" name="slug" id="slugField"
                   value="<?php echo escape($editing['slug'] ?? ''); ?>"
                   placeholder="auto-generated-from-title"
                   style="font-family:monospace;font-size:.85rem;">
            <button type="button" id="genSlug" class="btn btn-secondary" style="white-space:nowrap;">
              <i class="bi bi-arrow-clockwise"></i>
            </button>
          </div>
          <p class="form-hint">Used in the URL: /blog-post.php?id=… (slug is informational only)</p>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Category</label>
            <select class="form-control" name="category">
              <?php foreach ($categories as $cat): ?>
                <option value="<?php echo escape($cat); ?>"
                  <?php echo ($editing['category'] ?? 'General') === $cat ? 'selected' : ''; ?>>
                  <?php echo escape($cat); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">Author</label>
            <input class="form-control" name="author"
                   value="<?php echo escape($editing['author'] ?? 'Smartrack Team'); ?>">
          </div>
        </div>

        <!-- Featured image -->
        <div class="form-group">
          <label class="form-label">Featured Image</label>
          <input class="form-control" type="file" name="image" accept="image/jpeg,image/png,image/webp">
          <p class="form-hint">JPG, PNG or WebP · max 4 MB</p>
          <?php if (!empty($editing['image_path'])): ?>
            <div class="img-preview-wrap mt-2">
              <img src="<?php echo escape(site_url($editing['image_path'])); ?>" alt="">
            </div>
          <?php endif; ?>
        </div>

        <!-- Publish date -->
        <div class="form-group">
          <label class="form-label">Publish Date</label>
          <input class="form-control" type="datetime-local" name="published_at"
                 value="<?php echo escape(date('Y-m-d\TH:i', strtotime($editing['published_at'] ?? 'now'))); ?>">
        </div>

        <!-- Published toggle -->
        <div class="form-group" style="display:flex;align-items:center;gap:12px;padding:14px;
                                        background:var(--content-bg);border-radius:8px;">
          <input type="checkbox" name="is_published" id="isPub"
                 style="width:18px;height:18px;accent-color:var(--accent);"
                 <?php echo (!isset($editing) || $editing['is_published']) ? 'checked' : ''; ?>>
          <label for="isPub" style="margin:0;font-weight:600;cursor:pointer;">
            Publish post
            <span style="font-weight:400;color:var(--text-muted);font-size:.82rem;display:block;">
              Visible to readers on the blog page
            </span>
          </label>
        </div>

        <button class="btn btn-primary" style="width:100%;margin-top:4px;" type="submit">
          <i class="bi bi-check-lg"></i>
          <?php echo $editing ? 'Update Post' : 'Publish Post'; ?>
        </button>
      </form>
    </div>
  </div>

  <!-- ── Post list ──────────────────────────────────────────── -->
  <div style="display:flex;flex-direction:column;gap:14px;">
    <?php if (empty($posts)): ?>
      <div class="admin-card">
        <div class="empty-state">
          <i class="bi bi-journal-text"></i>
          <p>No posts yet. Write your first article using the form.</p>
        </div>
      </div>
    <?php else: ?>
      <?php foreach ($posts as $p): ?>
        <div class="admin-card">
          <div style="display:flex;align-items:flex-start;gap:16px;padding:18px 22px;">

            <!-- Thumbnail -->
            <?php if (!empty($p['image_path'])): ?>
              <img src="<?php echo escape(site_url($p['image_path'])); ?>"
                   style="width:90px;height:68px;object-fit:cover;border-radius:8px;flex-shrink:0;" alt="">
            <?php else: ?>
              <div style="width:90px;height:68px;border-radius:8px;background:rgba(229,57,53,.08);
                           display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="bi bi-journal-text" style="color:var(--accent);font-size:1.8rem;"></i>
              </div>
            <?php endif; ?>

            <!-- Info -->
            <div style="flex:1;min-width:0;">
              <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;margin-bottom:4px;">
                <!-- Live/Draft indicator -->
                <span style="width:8px;height:8px;border-radius:50%;flex-shrink:0;
                              background:<?php echo $p['is_published'] ? '#22c55e' : '#94a3b8'; ?>;
                              box-shadow:<?php echo $p['is_published'] ? '0 0 0 3px rgba(34,197,94,.2)' : 'none'; ?>;">
                </span>
                <strong style="font-size:.95rem;"><?php echo escape($p['title_en']); ?></strong>
              </div>
              <div style="display:flex;gap:10px;font-size:.8rem;color:var(--text-muted);flex-wrap:wrap;">
                <span style="background:rgba(229,57,53,.08);color:var(--accent);
                              font-size:.7rem;font-weight:700;padding:2px 10px;border-radius:20px;">
                  <?php echo escape($p['category']); ?>
                </span>
                <span><i class="bi bi-person me-1"></i><?php echo escape($p['author']); ?></span>
                <span><i class="bi bi-calendar3 me-1"></i><?php echo date('M j, Y', strtotime($p['published_at'])); ?></span>
                <span style="color:<?php echo $p['is_published'] ? '#16a34a' : '#94a3b8'; ?>;font-weight:600;">
                  <?php echo $p['is_published'] ? 'Published' : 'Draft'; ?>
                </span>
              </div>
              <?php if (!empty($p['excerpt_en'])): ?>
                <p style="font-size:.82rem;color:var(--text-muted);margin-top:6px;margin-bottom:0;
                           display:-webkit-box;-webkit-line-clamp:1;-webkit-box-orient:vertical;overflow:hidden;">
                  <?php echo escape($p['excerpt_en']); ?>
                </p>
              <?php endif; ?>
            </div>

            <!-- Actions -->
            <div style="display:flex;gap:8px;flex-shrink:0;align-items:center;">
              <a href="<?php echo escape(site_url('blog-post.php?id=' . $p['id'])); ?>"
                 target="_blank" class="btn btn-sm btn-secondary" title="Preview">
                <i class="bi bi-eye"></i>
              </a>
              <!-- Toggle -->
              <form method="post" style="margin:0;">
                <input type="hidden" name="csrf_token" value="<?php echo escape(csrf_token()); ?>">
                <input type="hidden" name="action"  value="toggle">
                <input type="hidden" name="post_id" value="<?php echo $p['id']; ?>">
                <button class="btn btn-sm btn-secondary" type="submit"
                        title="<?php echo $p['is_published'] ? 'Unpublish' : 'Publish'; ?>">
                  <i class="bi bi-<?php echo $p['is_published'] ? 'eye-slash' : 'eye'; ?>"></i>
                </button>
              </form>
              <a href="blog.php?edit=<?php echo $p['id']; ?>" class="btn btn-sm btn-secondary">
                <i class="bi bi-pencil-fill"></i>
              </a>
              <form method="post" onsubmit="return confirm('Delete this post?');" style="margin:0;">
                <input type="hidden" name="csrf_token" value="<?php echo escape(csrf_token()); ?>">
                <input type="hidden" name="action"  value="delete">
                <input type="hidden" name="post_id" value="<?php echo $p['id']; ?>">
                <button class="btn btn-sm btn-danger btn-icon" type="submit">
                  <i class="bi bi-trash-fill"></i>
                </button>
              </form>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>

</div><!-- /grid -->

<script>
  // Language tab switcher
  document.querySelectorAll('.lang-tab').forEach(btn => {
    btn.addEventListener('click', function() {
      document.querySelectorAll('.lang-tab').forEach(b => b.classList.remove('active'));
      this.classList.add('active');
      const lang = this.dataset.lang;
      document.getElementById('fields-en').style.display = lang === 'en' ? '' : 'none';
      document.getElementById('fields-fr').style.display = lang === 'fr' ? '' : 'none';
    });
  });

  // Auto-generate slug from EN title
  function makeSlug(str) {
    return str.toLowerCase()
      .normalize('NFD').replace(/[̀-ͯ]/g, '')
      .replace(/[^a-z0-9\s-]/g, '')
      .trim().replace(/\s+/g, '-').replace(/-+/g, '-');
  }
  document.getElementById('genSlug').addEventListener('click', () => {
    const title = document.getElementById('titleEn').value;
    document.getElementById('slugField').value = makeSlug(title);
  });
  document.getElementById('titleEn').addEventListener('blur', () => {
    if (!document.getElementById('slugField').value) {
      document.getElementById('slugField').value = makeSlug(document.getElementById('titleEn').value);
    }
  });
</script>

<style>
@media(max-width:960px){
  .admin-content>div[style*="grid-template-columns:460px"]{grid-template-columns:1fr!important;}
}
</style>

<?php include __DIR__ . '/_footer.php'; ?>
