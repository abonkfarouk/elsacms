<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title . ' - ' : '' ?><?= site_name() ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background-color: #f5f7fa;
            color: #333;
        }
        .layout-container {
            display: flex;
            min-height: 100vh;
        }
        /* Sidebar */
        .sidebar {
            width: 260px;
            background: linear-gradient(180deg, #2c3e50 0%, #34495e 100%);
            color: white;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }
        .sidebar-header {
            padding: 25px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(0, 0, 0, 0.2);
        }
        .sidebar-header h1 {
            font-size: 22px;
            font-weight: 600;
            margin-bottom: 5px;
        }
        .sidebar-header p {
            font-size: 12px;
            opacity: 0.8;
        }
        .sidebar-menu {
            padding: 20px 0;
        }
        .menu-item {
            display: block;
            padding: 12px 20px;
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }
        .menu-item:hover {
            background: rgba(255, 255, 255, 0.1);
            border-left-color: #667eea;
            color: white;
        }
        .menu-item.active {
            background: rgba(102, 126, 234, 0.2);
            border-left-color: #667eea;
            color: white;
        }
        .menu-item i {
            width: 20px;
            margin-right: 10px;
            display: inline-block;
        }
        .menu-section {
            padding: 15px 20px 5px;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.6;
            font-weight: 600;
        }
        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: 260px;
            min-height: 100vh;
        }
        .topbar {
            background: white;
            padding: 15px 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .topbar-title {
            font-size: 20px;
            font-weight: 600;
            color: #2c3e50;
        }
        .topbar-user {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .user-info {
            text-align: right;
        }
        .user-name {
            font-weight: 500;
            font-size: 14px;
            color: #2c3e50;
        }
        .user-role {
            font-size: 12px;
            color: #7f8c8d;
        }
        .btn-logout {
            background: #e74c3c;
            color: white;
            padding: 8px 16px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 13px;
            transition: background 0.3s;
        }
        .btn-logout:hover {
            background: #c0392b;
        }
        .content-area {
            padding: 30px;
        }
        /* Cards */
        .card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            transition: box-shadow 0.3s;
        }
        .card:hover {
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }
        .card-title {
            font-size: 18px;
            font-weight: 600;
            color: #2c3e50;
        }
        .card-body {
            color: #555;
        }
        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 25px;
        }
        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            position: relative;
            overflow: hidden;
        }
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .stat-value {
            font-size: 32px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 5px;
        }
        .stat-label {
            font-size: 14px;
            color: #7f8c8d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .stat-icon {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 48px;
            opacity: 0.1;
        }
        /* Badges */
        .badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            margin-right: 8px;
            margin-bottom: 8px;
        }
        .badge-role {
            background-color: #e3f2fd;
            color: #1976d2;
        }
        .badge-permission {
            background-color: #f3e5f5;
            color: #7b1fa2;
        }
        .badge-success {
            background-color: #d4edda;
            color: #155724;
        }
        .badge-danger {
            background-color: #f8d7da;
            color: #721c24;
        }
        /* Alerts */
        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border-color: #28a745;
        }
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border-color: #dc3545;
        }
        .alert-info {
            background-color: #d1ecf1;
            color: #0c5460;
            border-color: #17a2b8;
        }
        /* Quick Actions */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }
        .action-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-decoration: none;
            transition: transform 0.3s, box-shadow 0.3s;
            display: block;
        }
        .action-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
            color: white;
        }
        .action-card h3 {
            font-size: 16px;
            margin-bottom: 8px;
        }
        .action-card p {
            font-size: 12px;
            opacity: 0.9;
        }
        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s;
            }
            .sidebar.open {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
            }
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <?php helper('rbac'); ?>
    <div class="layout-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <h1><?= site_name() ?></h1>
                <p><?= site_tagline() ?></p>
            </div>
            <nav class="sidebar-menu">
                <div class="menu-section">Main Menu</div>
                <a href="<?= base_url('dashboard') ?>" class="menu-item <?= uri_string() == 'dashboard' ? 'active' : '' ?>">
                    <i>📊</i> Dashboard
                </a>
                
                <?php if (hasPermission('manage_users')): ?>
                <div class="menu-section">Management</div>
                <a href="<?= base_url('user-management') ?>" class="menu-item <?= strpos(uri_string(), 'user-management') !== false ? 'active' : '' ?>">
                    <i>👥</i> User Management
                </a>
                <?php endif; ?>
                
                <?php if (hasPermission('manage_users')): ?>
                <a href="<?= base_url('role-management') ?>" class="menu-item <?= strpos(uri_string(), 'role-management') !== false ? 'active' : '' ?>">
                    <i>🔐</i> Role Management
                </a>
                <?php endif; ?>
                
                <?php if (hasPermission('manage_users')): ?>
                <a href="<?= base_url('permission-management') ?>" class="menu-item <?= strpos(uri_string(), 'permission-management') !== false ? 'active' : '' ?>">
                    <i>🔑</i> Permission Management
                </a>
                <?php endif; ?>
                
                <?php if (hasPermission('manage_content')): ?>
                <div class="menu-section">Content</div>
                <a href="<?= base_url('category-management') ?>" class="menu-item <?= strpos(uri_string(), 'category-management') !== false ? 'active' : '' ?>">
                    <i>📁</i> Categories
                </a>
                <a href="<?= base_url('post-management') ?>" class="menu-item <?= strpos(uri_string(), 'post-management') !== false ? 'active' : '' ?>">
                    <i>📝</i> Posts
                </a>
                <a href="<?= base_url('page-management') ?>" class="menu-item <?= strpos(uri_string(), 'page-management') !== false ? 'active' : '' ?>">
                    <i>📄</i> Pages
                </a>
                <?php endif; ?>
                
                <?php if (hasPermission('manage_settings')): ?>
                <div class="menu-section">System</div>
                <a href="<?= base_url('settings') ?>" class="menu-item <?= strpos(uri_string(), 'settings') !== false ? 'active' : '' ?>">
                    <i>⚙️</i> Settings
                </a>
                <a href="<?= base_url('menu-management') ?>" class="menu-item <?= strpos(uri_string(), 'menu-management') !== false ? 'active' : '' ?>">
                    <i>🔗</i> Menus
                </a>
                <?php endif; ?>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Topbar -->
            <div class="topbar">
                <div class="topbar-title"><?= isset($title) ? $title : 'Dashboard' ?></div>
                <div class="topbar-user">
                    <div class="user-info">
                        <div class="user-name"><?= esc(session()->get('full_name') ?: session()->get('username')) ?></div>
                        <div class="user-role">
                            <?php 
                            $user = getCurrentUser();
                            if ($user && !empty($user['roles'])) {
                                echo esc($user['roles'][0]['name']);
                            }
                            ?>
                        </div>
                    </div>
                    <a href="<?= base_url('auth/logout') ?>" class="btn-logout">Logout</a>
                </div>
            </div>

            <!-- Content Area -->
            <div class="content-area">
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success">
                        <?= session()->getFlashdata('success') ?>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-error">
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($this->sections['content'])): ?>
                    <?= $this->renderSection('content') ?>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>
</html>
