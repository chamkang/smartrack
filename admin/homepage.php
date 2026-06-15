<?php
require_once __DIR__ . '/../includes/functions.php';
require_admin();
$pageTitle = 'Homepage Content';
$error = '';
$saved = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid CSRF token.';
    } else {
        // Text fields  (blank = keep current / fall back to default)
        $textFields = [
            'hero_title_en', 'hero_title_fr',
            'hero_subtitle_en', 'hero_subtitle_fr',
            // Promo carousel — slide 1
            'promo1_eyebrow_en', 'promo1_eyebrow_fr',
            'promo1_title_en',   'promo1_title_fr',
            'promo1_subtitle_en','promo1_subtitle_fr',
            'promo1_note_en',    'promo1_note_fr',
            'promo1_btn_en',     'promo1_btn_fr',
            'promo1_link',
            // Promo carousel — slide 2
            'promo2_eyebrow_en', 'promo2_eyebrow_fr',
            'promo2_title_en',   'promo2_title_fr',
            'promo2_subtitle_en','promo2_subtitle_fr',
            'promo2_note_en',    'promo2_note_fr',
            'promo2_btn_en',     'promo2_btn_fr',
            'promo2_link',
        ];
        foreach ($textFields as $field) {
            $val = trim($_POST[$field] ?? '');
            if ($val !== '') {
                set_content_value($field, $val);
            }
        }

        // Promo slide visibility toggles — always saved so unchecking persists
        set_content_value('promo1_enabled', isset($_POST['promo1_enabled']) ? '1' : '0');
        set_content_value('promo2_enabled', isset($_POST['promo2_enabled']) ? '1' : '0');

        // Promo slide images
        foreach (['promo1_image', 'promo2_image'] as $imgField) {
            if (!$error) {
                try {
                    $up = upload_image($imgField, 'home');
                    if ($up) {
                        set_content_value($imgField, $up);
                    }
                } catch (RuntimeException $e) {
                    $error = $e->getMessage();
                }
            }
        }

        // Hero image upload
        try {
            $newImage = upload_image('hero_image', 'home');
            if ($newImage) {
                set_content_value('hero_image', $newImage);
            }
        } catch (RuntimeException $e) {
            $error = $e->getMessage();
        }

        // Hero video upload
        if (!$error) {
            try {
                $newVideo = upload_media('hero_video', 'home', ['video/mp4', 'video/webm'], 20 * 1024 * 1024);
                if ($newVideo) {
                    set_content_value('hero_video', $newVideo);
                }
            } catch (RuntimeException $e) {
                $error = $e->getMessage();
            }
        }

        if (!$error) {
            $saved = true;
        }
    }
}

// Load current values
$heroTitleEn    = get_content_value('hero_title_en')    ?? '';
$heroTitleFr    = get_content_value('hero_title_fr')    ?? '';
$heroSubtitleEn = get_content_value('hero_subtitle_en') ?? '';
$heroSubtitleFr = get_content_value('hero_subtitle_fr') ?? '';
$heroImage      = get_content_value('hero_image');
$heroVideo      = get_content_value('hero_video');

// Promo carousel — defaults mirror the fallbacks used in index.php so the admin
// sees the live content even before anything has been saved.
$promoFallback = [
    1 => [
        'eyebrow_en'=>'🎯 Early Bird Special','eyebrow_fr'=>'🎯 Offre Lève-Tôt',
        'title_en'=>'40% OFF','title_fr'=>'40% DE RÉDUCTION',
        'subtitle_en'=>'First 3 months of GPS Tracking<br>Limited to next <strong>50 clients</strong>',
        'subtitle_fr'=>'Les 3 premiers mois de suivi GPS<br>Limité aux <strong>50 prochains clients</strong>',
        'note_en'=>'Valid only this month • Free consultation included','note_fr'=>'Valable ce mois uniquement • Consultation gratuite incluse',
        'btn_en'=>'Claim Your Discount','btn_fr'=>'Profitez de la Réduction','link'=>'contact.php',
    ],
    2 => [
        'eyebrow_en'=>'✨ Now Available','eyebrow_fr'=>'✨ Maintenant Disponible',
        'title_en'=>'Advanced Biometric<br>Authentication','title_fr'=>'Authentification<br>Biométrique Avancée',
        'subtitle_en'=>'Fingerprint, face ID & PIN for maximum security','subtitle_fr'=>'Empreinte, reconnaissance faciale et PIN pour une sécurité maximale',
        'note_en'=>'','note_fr'=>'',
        'btn_en'=>'Learn More','btn_fr'=>'En Savoir Plus','link'=>'contact.php',
    ],
];
// Resolve display value: saved value, else fallback default
$promoVal = function (int $n, string $key) use ($promoFallback) {
    $v = get_content_value("promo{$n}_{$key}");
    return ($v !== null && $v !== '') ? $v : ($promoFallback[$n][$key] ?? '');
};
$promoEnabled = function (int $n) {
    return get_content_value("promo{$n}_enabled") !== '0'; // default ON
};
$promoImage = function (int $n) {
    return get_content_value("promo{$n}_image");
};

define('APP_INIT_ADMIN', true);
include __DIR__ . '/_header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <h1>Homepage Content</h1>
</div>
<?php if ($error): ?>
  <div class="alert alert-danger"><?php echo escape($error); ?></div>
<?php elseif ($saved): ?>
  <div class="alert alert-success">Homepage content saved successfully.</div>
<?php endif; ?>

<div class="row gy-4">
  <div class="col-lg-8">
    <form method="post" enctype="multipart/form-data">
      <input type="hidden" name="csrf_token" value="<?php echo escape(csrf_token()); ?>">

      <div class="card p-4 mb-4">
        <h5 class="mb-3">Hero Text</h5>
        <div class="mb-3">
          <label class="form-label">Hero Title (English)</label>
          <input class="form-control" name="hero_title_en" value="<?php echo escape($heroTitleEn); ?>" placeholder="e.g. Smart GPS Tracking for Every Fleet">
        </div>
        <div class="mb-3">
          <label class="form-label">Hero Title (French)</label>
          <input class="form-control" name="hero_title_fr" value="<?php echo escape($heroTitleFr); ?>" placeholder="e.g. Suivi GPS Intelligent pour Chaque Flotte">
        </div>
        <div class="mb-3">
          <label class="form-label">Hero Subtitle (English)</label>
          <textarea class="form-control" name="hero_subtitle_en" rows="3"><?php echo escape($heroSubtitleEn); ?></textarea>
        </div>
        <div class="mb-3">
          <label class="form-label">Hero Subtitle (French)</label>
          <textarea class="form-control" name="hero_subtitle_fr" rows="3"><?php echo escape($heroSubtitleFr); ?></textarea>
        </div>
      </div>

      <div class="card p-4 mb-4">
        <h5 class="mb-3">Hero Media</h5>
        <div class="mb-3">
          <label class="form-label">Hero Background Image</label>
          <input class="form-control" type="file" name="hero_image" accept="image/jpeg,image/png,image/webp">
          <small class="text-muted">JPG, PNG or WebP · max 4 MB</small>
          <?php if ($heroImage): ?>
            <div class="mt-2">
              <img src="<?php echo escape($heroImage); ?>" alt="Hero" class="img-fluid rounded" style="max-height:200px;">
            </div>
          <?php endif; ?>
        </div>
        <div class="mb-3">
          <label class="form-label">Hero Background Video <span class="text-muted">(optional – overrides image)</span></label>
          <input class="form-control" type="file" name="hero_video" accept="video/mp4,video/webm">
          <small class="text-muted">MP4 or WebM · max 20 MB</small>
          <?php if ($heroVideo): ?>
            <div class="mt-2">
              <a href="<?php echo escape($heroVideo); ?>" target="_blank" class="btn btn-sm btn-outline-secondary">View current video</a>
            </div>
          <?php endif; ?>
        </div>
      </div>

      <?php
      $promoMeta = [
          1 => ['name' => 'Slide 1', 'theme' => 'Red — promotion / offer'],
          2 => ['name' => 'Slide 2', 'theme' => 'Blue — feature announcement'],
      ];
      foreach ([1, 2] as $n):
      ?>
      <div class="card p-4 mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h5 class="mb-0">Promo Carousel · <?php echo $promoMeta[$n]['name']; ?></h5>
          <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" role="switch"
                   id="promo<?php echo $n; ?>_enabled" name="promo<?php echo $n; ?>_enabled"
                   <?php echo $promoEnabled($n) ? 'checked' : ''; ?>>
            <label class="form-check-label" for="promo<?php echo $n; ?>_enabled">Show this slide</label>
          </div>
        </div>
        <p class="text-muted small mb-3"><i class="bi bi-palette"></i> Theme: <?php echo $promoMeta[$n]['theme']; ?> (fixed)</p>

        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label">Eyebrow / tag (English)</label>
            <input class="form-control" name="promo<?php echo $n; ?>_eyebrow_en" value="<?php echo escape($promoVal($n,'eyebrow_en')); ?>">
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Eyebrow / tag (French)</label>
            <input class="form-control" name="promo<?php echo $n; ?>_eyebrow_fr" value="<?php echo escape($promoVal($n,'eyebrow_fr')); ?>">
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Headline (English)</label>
            <input class="form-control" name="promo<?php echo $n; ?>_title_en" value="<?php echo escape($promoVal($n,'title_en')); ?>">
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Headline (French)</label>
            <input class="form-control" name="promo<?php echo $n; ?>_title_fr" value="<?php echo escape($promoVal($n,'title_fr')); ?>">
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Sub-text (English)</label>
            <textarea class="form-control" rows="2" name="promo<?php echo $n; ?>_subtitle_en"><?php echo escape($promoVal($n,'subtitle_en')); ?></textarea>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Sub-text (French)</label>
            <textarea class="form-control" rows="2" name="promo<?php echo $n; ?>_subtitle_fr"><?php echo escape($promoVal($n,'subtitle_fr')); ?></textarea>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Button label (English)</label>
            <input class="form-control" name="promo<?php echo $n; ?>_btn_en" value="<?php echo escape($promoVal($n,'btn_en')); ?>">
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Button label (French)</label>
            <input class="form-control" name="promo<?php echo $n; ?>_btn_fr" value="<?php echo escape($promoVal($n,'btn_fr')); ?>">
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Small note under button (English) <span class="text-muted">— optional</span></label>
            <input class="form-control" name="promo<?php echo $n; ?>_note_en" value="<?php echo escape($promoVal($n,'note_en')); ?>">
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Small note under button (French) <span class="text-muted">— optional</span></label>
            <input class="form-control" name="promo<?php echo $n; ?>_note_fr" value="<?php echo escape($promoVal($n,'note_fr')); ?>">
          </div>
          <div class="col-12 mb-3">
            <label class="form-label">Button link</label>
            <input class="form-control" name="promo<?php echo $n; ?>_link" value="<?php echo escape($promoVal($n,'link')); ?>" placeholder="e.g. contact.php or https://…">
          </div>
          <div class="col-12">
            <label class="form-label">Slide image</label>
            <input class="form-control" type="file" name="promo<?php echo $n; ?>_image" accept="image/jpeg,image/png,image/webp">
            <small class="text-muted">JPG, PNG or WebP · max 4 MB · leave blank to keep current</small>
            <?php $pImg = $promoImage($n); if ($pImg): ?>
              <div class="mt-2"><img src="<?php echo escape($pImg); ?>" alt="" class="img-fluid rounded" style="max-height:140px;"></div>
            <?php else: ?>
              <div class="mt-2"><img src="<?php echo escape(site_url($n === 1 ? 'assets/img/truckr.jpg' : 'assets/img/lock.jpg')); ?>" alt="" class="img-fluid rounded" style="max-height:140px;opacity:.85;"><div class="small text-muted mt-1">Current default image</div></div>
            <?php endif; ?>
          </div>
        </div>
      </div>
      <?php endforeach; ?>

      <button class="btn btn-primary px-4" type="submit">Save Homepage Content</button>
    </form>
  </div>

  <div class="col-lg-4">
    <div class="card p-3 bg-light">
      <h6>Quick Tips</h6>
      <ul class="mb-0 small">
        <li>Leave a field blank to keep the current value.</li>
        <li>Upload a new image to replace the existing one.</li>
        <li>If both image and video are set, the video is used on the hero.</li>
        <li>The <strong>Promo Carousel</strong> below the hero has two slides — edit text/images, toggle "Show this slide" off to hide one, or off on both to remove the banner entirely.</li>
        <li>Headline &amp; sub-text accept simple HTML like <code>&lt;br&gt;</code> for line breaks.</li>
        <li>Manage service cards via the <a href="services.php">Services</a> page.</li>
        <li>Manage translations via the <a href="translations.php">Translations</a> page.</li>
      </ul>
    </div>
  </div>
</div>
<?php include __DIR__ . '/_footer.php'; ?>
