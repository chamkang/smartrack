<?php
/**
 * Content Manager
 * Create, edit, and delete website content
 */

$pageTitle = 'Content Manager';

require_once __DIR__ . '/../includes/header.php';

$error = '';
$success = '';
$editContent = null;
$allContent = [];

// Get all content
try {
    $stmt = db()->query("SELECT * FROM website_content ORDER BY page_name, section_name, language_code");
    $allContent = $stmt->fetchAll();
} catch (PDOException $e) {
    $error = 'Error fetching content: ' . $e->getMessage();
}

// Handle delete action
if (isset($_GET['delete']) && !empty($_GET['delete'])) {
    $contentId = (int)$_GET['delete'];
    
    if (isset($_POST['confirm_delete']) && isset($_POST['csrf_token'])) {
        if (verifyCsrfToken($_POST['csrf_token'])) {
            $result = deleteContent($contentId);
            if ($result['success']) {
                $success = $result['message'];
                // Redirect to content manager to refresh the list
                header('Location: ' . getBasePath() . '/cms/admin/content-manager.php');
                exit;
            } else {
                $error = $result['message'];
            }
        }
    } else if (!isset($_POST['confirm_delete'])) {
        // Show delete confirmation
        $editContent = getContent($contentId);
    }
}

// Handle edit action
if (isset($_GET['edit']) && !empty($_GET['edit'])) {
    $contentId = (int)$_GET['edit'];
    $editContent = getContent($contentId);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CSRF token
    if (empty($_POST['csrf_token']) || !verifyCsrfToken($_POST['csrf_token'])) {
        $error = 'Security token expired. Please try again.';
    } else {
        $pageName = trim($_POST['page_name'] ?? '');
        $sectionName = trim($_POST['section_name'] ?? '');
        $languageCode = trim($_POST['language_code'] ?? 'en');
        $title = trim($_POST['title'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $imageAlt = trim($_POST['image_alt'] ?? '');
        
        // Validate required fields
        if (empty($pageName) || empty($sectionName)) {
            $error = 'Page name and section name are required.';
        } else {
            // Handle image upload
            $imagePath = null;
            
            if (!empty($_FILES['image']['name'])) {
                $uploadResult = uploadImage('image');
                if (!$uploadResult['success']) {
                    $error = $uploadResult['message'];
                } else {
                    $imagePath = $uploadResult['path'];
                }
            }
            
            // Create or update content
            if (!$error) {
                if (!empty($_POST['content_id'])) {
                    // Update existing content
                    $contentId = (int)$_POST['content_id'];
                    $result = updateContent($contentId, $title ?: null, $content ?: null, $imagePath, $imageAlt);
                } else {
                    // Create new content
                    $result = createContent($pageName, $sectionName, $languageCode, $title ?: null, $content ?: null, $imagePath, $imageAlt);
                }
                
                if ($result['success']) {
                    $success = $result['message'];
                    // Refresh page
                    header('Location: ' . getBasePath() . '/cms/admin/content-manager.php');
                    exit;
                } else {
                    $error = $result['message'];
                }
            }
        }
    }
}

// Refresh content list after any action
try {
    $stmt = db()->query("SELECT * FROM website_content ORDER BY page_name, section_name, language_code");
    $allContent = $stmt->fetchAll();
} catch (PDOException $e) {
    // Error already displayed
}

?>

<div class="page-header">
    <h1><i class="fas fa-file-alt"></i> Content Manager</h1>
    <p>Manage website content sections, images, and multilingual content.</p>
</div>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle"></i> <?php echo escape($error); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (!empty($success)): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle"></i> <?php echo escape($success); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Delete Confirmation Modal -->
<?php if (isset($_GET['delete']) && $editContent && !isset($_POST['confirm_delete'])): ?>
    <div class="alert alert-warning">
        <h5><i class="fas fa-exclamation-triangle"></i> Confirm Delete</h5>
        <p>Are you sure you want to delete this content?</p>
        <p><strong><?php echo escape($editContent['page_name']); ?> - <?php echo escape($editContent['section_name']); ?></strong></p>
        
        <form method="POST" class="d-flex gap-2">
            <input type="hidden" name="csrf_token" value="<?php echo escape(getCsrfToken()); ?>">
            <button type="submit" name="confirm_delete" value="1" class="btn btn-danger">
                <i class="fas fa-trash"></i> Delete
            </button>
            <a href="<?php echo getBasePath(); ?>/cms/admin/content-manager.php" class="btn btn-secondary">
                <i class="fas fa-times"></i> Cancel
            </a>
        </form>
    </div>
<?php endif; ?>

<div class="row">
    <!-- Content Form -->
    <div class="col-lg-6">
        <div class="dashboard-card">
            <h5 class="mb-4">
                <i class="fas fa-<?php echo !empty($_GET['edit']) ? 'edit' : 'plus'; ?>"></i> 
                <?php echo !empty($_GET['edit']) ? 'Edit Content' : 'Create New Content'; ?>
            </h5>
            
            <form method="POST" enctype="multipart/form-data" novalidate>
                <input type="hidden" name="csrf_token" value="<?php echo escape(getCsrfToken()); ?>">
                
                <?php if (!empty($_GET['edit'])): ?>
                    <input type="hidden" name="content_id" value="<?php echo escape($_GET['edit']); ?>">
                <?php endif; ?>
                
                <!-- Page Name -->
                <div class="mb-3">
                    <label class="form-label"><strong>Page Name</strong></label>
                    <input type="text" class="form-control" name="page_name" 
                           value="<?php echo escape($editContent['page_name'] ?? ''); ?>"
                           placeholder="e.g., index, about, services" required
                           <?php echo !empty($_GET['edit']) ? 'readonly' : ''; ?>>
                    <small class="text-muted">Cannot be changed after creation</small>
                </div>
                
                <!-- Section Name -->
                <div class="mb-3">
                    <label class="form-label"><strong>Section Name</strong></label>
                    <input type="text" class="form-control" name="section_name" 
                           value="<?php echo escape($editContent['section_name'] ?? ''); ?>"
                           placeholder="e.g., hero, features, testimonials" required
                           <?php echo !empty($_GET['edit']) ? 'readonly' : ''; ?>>
                    <small class="text-muted">Cannot be changed after creation</small>
                </div>
                
                <!-- Language Code -->
                <div class="mb-3">
                    <label class="form-label"><strong>Language</strong></label>
                    <select class="form-control" name="language_code">
                        <option value="en" <?php echo (($editContent['language_code'] ?? 'en') === 'en') ? 'selected' : ''; ?>>English</option>
                        <option value="fr" <?php echo (($editContent['language_code'] ?? '') === 'fr') ? 'selected' : ''; ?>>French</option>
                        <option value="es" <?php echo (($editContent['language_code'] ?? '') === 'es') ? 'selected' : ''; ?>>Spanish</option>
                        <option value="de" <?php echo (($editContent['language_code'] ?? '') === 'de') ? 'selected' : ''; ?>>German</option>
                    </select>
                </div>
                
                <!-- Title -->
                <div class="mb-3">
                    <label class="form-label"><strong>Title</strong></label>
                    <input type="text" class="form-control" name="title" 
                           value="<?php echo escape($editContent['title'] ?? ''); ?>"
                           placeholder="Section title">
                </div>
                
                <!-- Content -->
                <div class="mb-3">
                    <label class="form-label"><strong>Content</strong></label>
                    <textarea class="form-control" name="content" rows="6" 
                              placeholder="Enter your content here..."><?php echo escape($editContent['content'] ?? ''); ?></textarea>
                </div>
                
                <!-- Image Upload -->
                <div class="mb-3">
                    <label class="form-label"><strong>Image</strong></label>
                    
                    <!-- Show current image if editing -->
                    <?php if (!empty($editContent['image_path'])): ?>
                        <div class="mb-3">
                            <p class="text-muted">Current Image:</p>
                            <img src="<?php echo escape($editContent['image_path']); ?>" 
                                 alt="<?php echo escape($editContent['image_alt'] ?? 'Content Image'); ?>"
                                 style="max-width: 100%; height: auto; border-radius: 6px; max-height: 200px;">
                        </div>
                    <?php endif; ?>
                    
                    <input type="file" class="form-control" name="image" accept="image/jpeg,image/png,image/webp">
                    <small class="text-muted">Accepted: JPG, PNG, WebP (Max 5MB)</small>
                </div>
                
                <!-- Image Alt Text -->
                <div class="mb-3">
                    <label class="form-label"><strong>Image Alt Text</strong></label>
                    <input type="text" class="form-control" name="image_alt" 
                           value="<?php echo escape($editContent['image_alt'] ?? ''); ?>"
                           placeholder="Describe the image for accessibility">
                </div>
                
                <!-- Form Actions -->
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> 
                        <?php echo !empty($_GET['edit']) ? 'Update' : 'Create'; ?>
                    </button>
                    
                    <?php if (!empty($_GET['edit'])): ?>
                        <a href="<?php echo getBasePath(); ?>/cms/admin/content-manager.php" 
                           class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Content List -->
    <div class="col-lg-6">
        <div class="dashboard-card">
            <h5 class="mb-4">
                <i class="fas fa-list"></i> All Content (<?php echo count($allContent); ?>)
            </h5>
            
            <?php if (empty($allContent)): ?>
                <div class="alert alert-info mb-0">
                    <i class="fas fa-info-circle"></i> No content created yet. Create your first content section using the form on the left.
                </div>
            <?php else: ?>
                <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Page</th>
                                <th>Section</th>
                                <th>Lang</th>
                                <th>Updated</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($allContent as $item): ?>
                                <tr>
                                    <td>
                                        <strong><?php echo escape($item['page_name']); ?></strong>
                                    </td>
                                    <td>
                                        <small><?php echo escape($item['section_name']); ?></small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info"><?php echo escape($item['language_code']); ?></span>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            <?php 
                                            $date = strtotime($item['updated_at']);
                                            echo date('M d H:i', $date);
                                            ?>
                                        </small>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="?edit=<?php echo $item['id']; ?>" 
                                               class="btn btn-primary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="?delete=<?php echo $item['id']; ?>" 
                                               class="btn btn-danger" title="Delete"
                                               onclick="return confirm('Delete this content?');">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
