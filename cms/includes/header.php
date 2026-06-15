<?php
/**
 * Admin Header Include
 * Common header for all admin pages
 */

require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/functions.php';

// Require login for all admin pages
requireLogin();

// Get current admin info
$currentAdmin = getCurrentAdmin();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo escape($pageTitle ?? 'Smartrack CMS'); ?> - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .admin-navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 0 20px;
        }
        
        .admin-navbar .navbar-brand {
            color: white !important;
            font-weight: 600;
            font-size: 18px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .admin-navbar .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            font-size: 14px;
            transition: color 0.3s;
            margin: 0 5px;
        }
        
        .admin-navbar .nav-link:hover {
            color: white !important;
        }
        
        .admin-navbar .nav-link.active {
            color: white !important;
            border-bottom: 2px solid white;
            padding-bottom: 10px !important;
        }
        
        .admin-navbar .btn-logout {
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 13px;
            transition: background-color 0.3s;
        }
        
        .admin-navbar .btn-logout:hover {
            background-color: rgba(255, 255, 255, 0.3);
            color: white;
        }
        
        .admin-container {
            display: flex;
            min-height: calc(100vh - 60px);
        }
        
        .admin-sidebar {
            width: 250px;
            background: white;
            border-right: 1px solid #e0e0e0;
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.05);
        }
        
        .admin-sidebar .nav {
            flex-direction: column;
        }
        
        .admin-sidebar .nav-link {
            color: #333;
            padding: 10px 15px;
            margin-bottom: 5px;
            border-radius: 6px;
            transition: all 0.3s;
            font-size: 14px;
        }
        
        .admin-sidebar .nav-link:hover {
            background-color: #f5f5f5;
            color: #667eea;
        }
        
        .admin-sidebar .nav-link.active {
            background-color: #667eea;
            color: white;
        }
        
        .admin-content {
            flex: 1;
            padding: 30px;
            overflow-y: auto;
        }
        
        .page-header {
            margin-bottom: 30px;
        }
        
        .page-header h1 {
            color: #333;
            font-size: 32px;
            font-weight: 600;
            margin-bottom: 10px;
        }
        
        .page-header p {
            color: #666;
            font-size: 14px;
        }
        
        .dashboard-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .dashboard-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
        }
        
        .dashboard-card-icon {
            width: 50px;
            height: 50px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 15px;
        }
        
        .dashboard-card-value {
            font-size: 28px;
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }
        
        .dashboard-card-label {
            font-size: 13px;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #5568d3 0%, #6a3f91 100%);
        }
        
        .table-responsive {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }
        
        .table {
            margin-bottom: 0;
        }
        
        .table thead {
            background-color: #f8f9fa;
            border-bottom: 2px solid #e0e0e0;
        }
        
        .table th {
            color: #333;
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 15px;
        }
        
        .table td {
            padding: 12px 15px;
            vertical-align: middle;
            border-color: #e0e0e0;
        }
        
        .table tbody tr:hover {
            background-color: #f8f9fa;
        }
        
        .alert {
            border-radius: 6px;
            border: none;
        }
        
        @media (max-width: 768px) {
            .admin-container {
                flex-direction: column;
            }
            
            .admin-sidebar {
                width: 100%;
                border-right: none;
                border-bottom: 1px solid #e0e0e0;
                padding: 15px;
            }
            
            .admin-content {
                padding: 15px;
            }
            
            .page-header h1 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <!-- Admin Navbar -->
    <nav class="navbar navbar-expand-lg admin-navbar">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?php echo getBasePath(); ?>/cms/admin/dashboard.php">
                <i class="fas fa-tachometer-alt"></i> Smartrack CMS
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <span class="nav-link">
                            <i class="fas fa-user-circle"></i> <?php echo escape($currentAdmin['name'] ?? $currentAdmin['email']); ?>
                        </span>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-logout" href="<?php echo getBasePath(); ?>/cms/auth/logout.php">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    <!-- Admin Container -->
    <div class="admin-container">
        <!-- Sidebar -->
        <div class="admin-sidebar">
            <nav class="nav flex-column">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'dashboard.php' ? 'active' : ''; ?>" 
                   href="<?php echo getBasePath(); ?>/cms/admin/dashboard.php">
                    <i class="fas fa-chart-line"></i> Dashboard
                </a>
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'content-manager.php' ? 'active' : ''; ?>" 
                   href="<?php echo getBasePath(); ?>/cms/admin/content-manager.php">
                    <i class="fas fa-file-alt"></i> Content Manager
                </a>
            </nav>
        </div>
        
        <!-- Main Content -->
        <div class="admin-content">
