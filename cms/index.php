<?php
/**
 * Smartrack CMS Setup & Index
 * Initialize database and provide quick access to admin panel
 */

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/auth.php';

initSession();

// Check if database needs setup
$dbNeedsSetup = false;
try {
    $stmt = db()->query("SELECT COUNT(*) as count FROM sqlite_master WHERE type='table' AND name='admins'");
    $result = $stmt->fetch();
    $dbNeedsSetup = ($result['count'] ?? 0) === 0;
} catch (Exception $e) {
    $dbNeedsSetup = true;
}

// If logged in, redirect to dashboard
if (isLoggedIn()) {
    header('Location: /smartrack/cms/admin/dashboard.php');
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smartrack CMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .container-welcome {
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 50px;
            max-width: 600px;
            text-align: center;
            animation: slideUp 0.6s ease-out;
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .welcome-icon {
            font-size: 64px;
            color: #667eea;
            margin-bottom: 20px;
        }
        
        .welcome-title {
            font-size: 36px;
            font-weight: 700;
            color: #333;
            margin-bottom: 15px;
        }
        
        .welcome-subtitle {
            font-size: 16px;
            color: #666;
            margin-bottom: 30px;
            line-height: 1.6;
        }
        
        .btn-large {
            padding: 12px 30px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 6px;
            transition: all 0.3s;
            margin: 10px 5px;
        }
        
        .btn-primary-large {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            display: inline-block;
            text-decoration: none;
        }
        
        .btn-primary-large:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
            color: white;
        }
        
        .setup-status {
            background: #f5f5f5;
            border-radius: 8px;
            padding: 20px;
            margin: 30px 0;
            border-left: 4px solid #667eea;
        }
        
        .status-item {
            display: flex;
            align-items: center;
            padding: 8px 0;
            font-size: 14px;
        }
        
        .status-icon {
            margin-right: 10px;
            font-size: 18px;
        }
        
        .status-icon.success {
            color: #4caf50;
        }
        
        .status-icon.warning {
            color: #ff9800;
        }
        
        .features {
            text-align: left;
            margin: 30px 0;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        
        .features h6 {
            color: #667eea;
            font-weight: 600;
            margin-bottom: 15px;
        }
        
        .features ul {
            list-style: none;
            padding: 0;
        }
        
        .features li {
            padding: 8px 0;
            font-size: 14px;
            color: #666;
        }
        
        .features li:before {
            content: "✓ ";
            color: #4caf50;
            font-weight: bold;
            margin-right: 8px;
        }
    </style>
</head>
<body>
    <div class="container-welcome">
        <div class="welcome-icon">
            <i class="fas fa-dashboard"></i>
        </div>
        
        <h1 class="welcome-title">Smartrack CMS</h1>
        <p class="welcome-subtitle">Professional Content Management System for your vehicle tracking website</p>
        
        <!-- Setup Status -->
        <div class="setup-status">
            <div class="status-item">
                <span class="status-icon success"><i class="fas fa-check-circle"></i></span>
                <span><strong>Database:</strong> <?php echo $dbNeedsSetup ? 'Needs Setup' : 'Ready'; ?></span>
            </div>
            
            <?php if ($dbNeedsSetup): ?>
                <p style="color: #ff9800; margin-top: 10px; font-size: 13px;">
                    <i class="fas fa-info-circle"></i> Database needs to be initialized. Click "Setup Database" below.
                </p>
            <?php endif; ?>
        </div>
        
        <!-- Features -->
        <div class="features">
            <h6><i class="fas fa-star"></i> CMS Features</h6>
            <ul>
                <li>Edit website content without coding</li>
                <li>Upload and manage images securely</li>
                <li>Multilingual content support (EN, FR, ES, DE)</li>
                <li>Manage multiple website pages</li>
                <li>Track content updates automatically</li>
            </ul>
        </div>
        
        <!-- Action Buttons -->
        <div>
            <?php if ($dbNeedsSetup): ?>
                <form method="GET" action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>" style="margin-bottom: 15px;">
                    <button type="button" class="btn btn-large btn-primary-large" 
                            onclick="setupDatabase();">
                        <i class="fas fa-database"></i> Setup Database
                    </button>
                </form>
                
                <div class="alert alert-info" role="alert">
                    <small><i class="fas fa-lightbulb"></i> Database will be created automatically. This only needs to be done once.</small>
                </div>
            <?php else: ?>
                <a href="/smartrack/cms/auth/login.php" class="btn btn-large btn-primary-large">
                    <i class="fas fa-sign-in-alt"></i> Go to Admin Login
                </a>
            <?php endif; ?>
        </div>
        
        <!-- Default Credentials Info -->
        <div style="margin-top: 20px; padding: 15px; background: #f0f4ff; border-radius: 6px;">
            <p style="font-size: 12px; color: #666; margin-bottom: 8px;"><strong>Default Admin Credentials:</strong></p>
            <p style="font-size: 13px; color: #333; margin: 0;">
                <i class="fas fa-envelope"></i> <code>admin@smartrack.com</code>
            </p>
            <p style="font-size: 13px; color: #333;">
                <i class="fas fa-lock"></i> <code>Admin123!</code>
            </p>
        </div>
    </div>
    
    <script>
        function setupDatabase() {
            if (confirm('This will initialize the CMS database. Proceed?')) {
                // Create a simple fetch to database setup script
                fetch('/smartrack/cms/database/setup.php')
                    .then(response => {
                        if (response.ok) {
                            alert('Database setup completed! You can now login.');
                            location.reload();
                        } else {
                            alert('Database setup encountered an error. Please check the file permissions.');
                        }
                    })
                    .catch(error => {
                        console.error('Setup error:', error);
                        // Alternative: redirect to setup page
                        window.location.href = '/smartrack/cms/database/setup.php';
                    });
            }
        }
    </script>
</body>
</html>
