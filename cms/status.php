<?php
/**
 * CMS System Test/Demo Page
 * Tests database connectivity and displays system status
 */

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

initSession();

// Test database connection
$dbStatus = 'Unknown';
$tables = [];

try {
    $pdo = db();
    
    // Get list of tables
    $stmt = $pdo->query("SELECT name FROM sqlite_master WHERE type='table'");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    $dbStatus = count($tables) > 0 ? '✓ Connected' : '⚠ Empty';
} catch (Exception $e) {
    $dbStatus = '✗ Error: ' . $e->getMessage();
}

// Get statistics
$adminCount = 0;
$contentCount = 0;
$imageCount = 0;

try {
    $stmt = db()->query("SELECT COUNT(*) as count FROM admins");
    $adminCount = $stmt->fetch()['count'] ?? 0;
    
    $stmt = db()->query("SELECT COUNT(*) as count FROM website_content");
    $contentCount = $stmt->fetch()['count'] ?? 0;
    
    $stmt = db()->query("SELECT COUNT(*) as count FROM website_content WHERE image_path IS NOT NULL AND image_path != ''");
    $imageCount = $stmt->fetch()['count'] ?? 0;
} catch (Exception $e) {
    // Tables not ready yet
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smartrack CMS - System Status</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 30px 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .container-test {
            max-width: 900px;
            background: white;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }
        
        .test-header {
            margin-bottom: 30px;
            border-bottom: 2px solid #e0e0e0;
            padding-bottom: 20px;
        }
        
        .test-header h1 {
            color: #333;
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .test-section {
            margin-bottom: 30px;
        }
        
        .test-section h3 {
            color: #667eea;
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 15px;
            border-left: 4px solid #667eea;
            padding-left: 12px;
        }
        
        .status-card {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .status-label {
            font-weight: 500;
            color: #333;
        }
        
        .status-value {
            font-size: 18px;
            font-weight: 600;
            color: #667eea;
        }
        
        .status-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
        }
        
        .status-success {
            background-color: #d4edda;
            color: #155724;
        }
        
        .status-warning {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .status-error {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .code-block {
            background: #2d2d2d;
            color: #f8f8f2;
            padding: 15px;
            border-radius: 6px;
            overflow-x: auto;
            font-family: 'Courier New', monospace;
            font-size: 12px;
            margin-top: 10px;
        }
        
        .action-buttons {
            display: flex;
            gap: 10px;
            margin-top: 20px;
            flex-wrap: wrap;
        }
        
        .action-buttons a,
        .action-buttons button {
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
        }
        
        .btn-primary-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
        }
        
        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
            color: white;
        }
        
        .btn-secondary-custom {
            background: #e0e0e0;
            color: #333;
            border: none;
        }
        
        .btn-secondary-custom:hover {
            background: #d0d0d0;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="container-test">
            <!-- Header -->
            <div class="test-header">
                <h1><i class="fas fa-heartbeat"></i> Smartrack CMS - System Status</h1>
                <p class="text-muted">Database connectivity and setup verification</p>
            </div>
            
            <!-- Database Status -->
            <div class="test-section">
                <h3><i class="fas fa-database"></i> Database Status</h3>
                
                <div class="status-card">
                    <span class="status-label">Connection:</span>
                    <span class="status-badge <?php echo strpos($dbStatus, 'Connected') !== false ? 'status-success' : (strpos($dbStatus, 'Empty') !== false ? 'status-warning' : 'status-error'); ?>">
                        <?php echo escape($dbStatus); ?>
                    </span>
                </div>
                
                <div class="status-card">
                    <span class="status-label">Tables Created:</span>
                    <span class="status-value"><?php echo count($tables); ?></span>
                </div>
                
                <?php if (!empty($tables)): ?>
                    <div style="margin-top: 10px; padding: 10px; background: #f0f4ff; border-radius: 6px;">
                        <strong style="color: #667eea;">Tables:</strong>
                        <ul style="margin: 8px 0 0 20px; font-size: 14px;">
                            <?php foreach ($tables as $table): ?>
                                <li><?php echo escape($table); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Statistics -->
            <div class="test-section">
                <h3><i class="fas fa-chart-bar"></i> CMS Statistics</h3>
                
                <div class="row">
                    <div class="col-md-6 col-lg-4">
                        <div class="status-card">
                            <div>
                                <span class="status-label">Admin Accounts:</span>
                                <div class="status-value"><?php echo $adminCount; ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="status-card">
                            <div>
                                <span class="status-label">Content Sections:</span>
                                <div class="status-value"><?php echo $contentCount; ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="status-card">
                            <div>
                                <span class="status-label">Uploaded Images:</span>
                                <div class="status-value"><?php echo $imageCount; ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Default Credentials -->
            <div class="test-section">
                <h3><i class="fas fa-key"></i> Default Credentials</h3>
                
                <div class="status-card">
                    <div>
                        <span class="status-label">Email:</span>
                        <div style="color: #333; font-family: monospace; margin-top: 5px;">
                            <code>admin@smartrack.com</code>
                        </div>
                    </div>
                </div>
                
                <div class="status-card">
                    <div>
                        <span class="status-label">Password:</span>
                        <div style="color: #333; font-family: monospace; margin-top: 5px;">
                            <code>Admin123!</code>
                        </div>
                    </div>
                </div>
                
                <div style="background: #ffeaa7; padding: 12px; border-radius: 6px; margin-top: 15px; border-left: 4px solid #fdcb6e;">
                    <strong style="color: #d63031;">⚠️ Important:</strong> Change the default password immediately after login!
                </div>
            </div>
            
            <!-- Database File -->
            <div class="test-section">
                <h3><i class="fas fa-folder"></i> System Information</h3>
                
                <div class="status-card">
                    <span class="status-label">Database File:</span>
                    <code style="color: #667eea; word-break: break-all;">smartrack.db</code>
                </div>
                
                <div class="status-card">
                    <span class="status-label">Upload Directory:</span>
                    <code style="color: #667eea;">/cms/uploads/images/</code>
                </div>
                
                <div class="status-card">
                    <span class="status-label">PHP Version:</span>
                    <span style="color: #333; font-weight: 600;"><?php echo phpversion(); ?></span>
                </div>
            </div>
            
            <!-- Quick Links -->
            <div class="test-section">
                <h3><i class="fas fa-link"></i> Quick Links</h3>
                
                <div class="action-buttons">
                    <a href="<?php echo getBasePath(); ?>/cms/auth/login.php" class="btn-primary-custom">
                        <i class="fas fa-sign-in-alt"></i> Admin Login
                    </a>
                    <a href="<?php echo getBasePath(); ?>/cms/admin/dashboard.php" class="btn-secondary-custom">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                    <a href="<?php echo getBasePath(); ?>/cms/admin/content-manager.php" class="btn-secondary-custom">
                        <i class="fas fa-file-alt"></i> Content Manager
                    </a>
                    <a href="<?php echo getBasePath(); ?>/cms/README.md" class="btn-secondary-custom" target="_blank">
                        <i class="fas fa-book"></i> Documentation
                    </a>
                </div>
            </div>
            
            <!-- Setup Instructions -->
            <div class="alert alert-info mt-4">
                <h5 class="alert-heading"><i class="fas fa-info-circle"></i> First Time Setup?</h5>
                <ol style="margin-bottom: 0;">
                    <li>Database is already set up with default admin account</li>
                    <li>Navigate to <strong>Admin Login</strong> above</li>
                    <li>Use the default credentials provided</li>
                    <li>Change your password immediately</li>
                    <li>Start managing content in the <strong>Content Manager</strong></li>
                </ol>
            </div>
        </div>
    </div>
</body>
</html>
