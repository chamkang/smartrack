<?php
/**
 * Admin Dashboard
 * Overview of CMS statistics and recent activity
 */

$pageTitle = 'Dashboard';

require_once __DIR__ . '/../includes/header.php';

// Get dashboard statistics
$totalPages = getTotalPages();
$totalSections = getTotalSections();
$totalImages = getTotalImages();
$lastUpdated = getLastUpdatedContent(5);

?>

<div class="page-header">
    <h1><i class="fas fa-chart-line"></i> Dashboard</h1>
    <p>Welcome back, <?php echo escape($currentAdmin['name']); ?>! Here's an overview of your CMS.</p>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-6 col-lg-3">
        <div class="dashboard-card">
            <div class="dashboard-card-icon" style="background-color: #e3f2fd;">
                <i class="fas fa-file-alt" style="color: #2196F3;"></i>
            </div>
            <div class="dashboard-card-value"><?php echo $totalPages; ?></div>
            <div class="dashboard-card-label">Total Pages</div>
        </div>
    </div>
    
    <div class="col-md-6 col-lg-3">
        <div class="dashboard-card">
            <div class="dashboard-card-icon" style="background-color: #f3e5f5;">
                <i class="fas fa-puzzle-piece" style="color: #9c27b0;"></i>
            </div>
            <div class="dashboard-card-value"><?php echo $totalSections; ?></div>
            <div class="dashboard-card-label">Content Sections</div>
        </div>
    </div>
    
    <div class="col-md-6 col-lg-3">
        <div class="dashboard-card">
            <div class="dashboard-card-icon" style="background-color: #e8f5e9;">
                <i class="fas fa-image" style="color: #4caf50;"></i>
            </div>
            <div class="dashboard-card-value"><?php echo $totalImages; ?></div>
            <div class="dashboard-card-label">Uploaded Images</div>
        </div>
    </div>
    
    <div class="col-md-6 col-lg-3">
        <div class="dashboard-card">
            <div class="dashboard-card-icon" style="background-color: #fff3e0;">
                <i class="fas fa-clock" style="color: #ff9800;"></i>
            </div>
            <div class="dashboard-card-value"><?php echo !empty($lastUpdated) ? 'Yes' : 'No'; ?></div>
            <div class="dashboard-card-label">Recent Updates</div>
        </div>
    </div>
</div>

<!-- Last Updated Content -->
<div class="row">
    <div class="col-12">
        <div class="dashboard-card">
            <h5 class="mb-4">
                <i class="fas fa-history"></i> Last Updated Content
            </h5>
            
            <?php if (!empty($lastUpdated)): ?>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Page</th>
                                <th>Section</th>
                                <th>Language</th>
                                <th>Title</th>
                                <th>Updated</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($lastUpdated as $content): ?>
                                <tr>
                                    <td><?php echo escape($content['page_name']); ?></td>
                                    <td><?php echo escape($content['section_name']); ?></td>
                                    <td><?php echo escape($content['language_code']); ?></td>
                                    <td><?php echo escape($content['title'] ?? '-'); ?></td>
                                    <td>
                                        <small class="text-muted">
                                            <?php 
                                            $date = strtotime($content['updated_at']);
                                            echo date('M d, Y H:i', $date);
                                            ?>
                                        </small>
                                    </td>
                                    <td>
                                        <a href="<?php echo getBasePath(); ?>/cms/admin/content-manager.php?edit=<?php echo $content['id']; ?>" 
                                           class="btn btn-sm btn-primary">
                                            Edit
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-info mb-0">
                    <i class="fas fa-info-circle"></i> No content created yet. 
                    <a href="<?php echo getBasePath(); ?>/cms/admin/content-manager.php">Create your first content section</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mt-4">
    <div class="col-12">
        <div class="dashboard-card">
            <h5 class="mb-3">
                <i class="fas fa-bolt"></i> Quick Actions
            </h5>
            <div class="d-flex gap-2">
                <a href="<?php echo getBasePath(); ?>/cms/admin/content-manager.php?action=create" 
                   class="btn btn-primary">
                    <i class="fas fa-plus"></i> Create Content
                </a>
                <a href="<?php echo getBasePath(); ?>/cms/admin/content-manager.php" 
                   class="btn btn-outline-primary">
                    <i class="fas fa-list"></i> View All Content
                </a>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
