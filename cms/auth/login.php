<?php
/**
 * Admin Login Page
 * Handles admin authentication
 */

require_once __DIR__ . '/../includes/auth.php';

// Initialize session
initSession();

// If already logged in, redirect to dashboard
if (isLoggedIn()) {
    redirect(getBasePath() . '/cms/admin/dashboard.php');
}

$error = '';
$success = '';

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CSRF token
    if (empty($_POST['csrf_token']) || !verifyCsrfToken($_POST['csrf_token'])) {
        $error = 'Security token expired. Please try again.';
    } else {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        
        // Attempt login
        $result = loginAdmin($email, $password);
        
        if ($result['success']) {
            redirect(getBasePath() . '/cms/admin/dashboard.php');
        } else {
            $error = $result['message'];
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smartrack CMS - Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .login-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 420px;
            padding: 40px;
            animation: slideUp 0.5s ease-out;
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .login-header h1 {
            color: #333;
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 8px;
        }
        
        .login-header p {
            color: #666;
            font-size: 14px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
            font-size: 14px;
        }
        
        .form-group input {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.3s;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #667eea;
        }
        
        .login-button {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            margin-top: 10px;
        }
        
        .login-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }
        
        .login-button:active {
            transform: translateY(0);
        }
        
        .alert {
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        
        .alert-danger {
            background-color: #fee;
            color: #c33;
            border: 1px solid #fcc;
        }
        
        .alert-success {
            background-color: #efe;
            color: #3c3;
            border: 1px solid #cfc;
        }
        
        .login-footer {
            text-align: center;
            margin-top: 20px;
            color: #999;
            font-size: 12px;
        }
        
        .credentials-info {
            background: #f5f5f5;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            border-left: 4px solid #667eea;
        }
        
        .credentials-info h6 {
            color: #667eea;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 12px;
        }
        
        .credentials-info p {
            margin: 4px 0;
            color: #666;
            font-size: 13px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1>Smartrack CMS</h1>
            <p>Admin Portal</p>
        </div>
        
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger">
                <?php echo escape($error); ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($success)): ?>
            <div class="alert alert-success">
                <?php echo escape($success); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <input type="hidden" name="csrf_token" value="<?php echo escape(getCsrfToken()); ?>">
            
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" required 
                       placeholder="admin@smartrack.com" autocomplete="email">
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required 
                       placeholder="Enter your password" autocomplete="current-password">
            </div>
            
            <button type="submit" class="login-button">Sign In</button>
        </form>
        
        <div class="credentials-info">
            <h6>📋 DEFAULT CREDENTIALS</h6>
            <p><strong>Email:</strong> admin@smartrack.com</p>
            <p><strong>Password:</strong> Admin123!</p>
        </div>
        
        <div class="login-footer">
            <p>Smartrack Vehicle Tracking System</p>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
