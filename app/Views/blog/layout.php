<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? esc($title) . ' - ' : '' ?><?= site_name() ?></title>
    
    <!-- Open Graph / Social Media -->
    <?php
    $ogTitle = isset($meta_title) ? $meta_title : (isset($title) ? $title . ' - ' . site_name() : site_name());
    $ogDesc = isset($meta_description) ? $meta_description : site_description();
    $ogImage = !empty($meta_image) ? base_url($meta_image) : (function_exists('site_hero_bg') && site_hero_bg() ? site_hero_bg() : base_url('favicon.ico'));
    $ogType = isset($meta_type) ? $meta_type : 'website';
    $ogUrl = current_url();
    ?>
    <meta property="og:title" content="<?= esc($ogTitle) ?>">
    <meta property="og:description" content="<?= esc($ogDesc) ?>">
    <meta property="og:image" content="<?= esc($ogImage) ?>">
    <meta property="og:url" content="<?= esc($ogUrl) ?>">
    <meta property="og:type" content="<?= esc($ogType) ?>">
    <meta property="og:site_name" content="<?= esc(site_name()) ?>">
    
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= esc($ogTitle) ?>">
    <meta name="twitter:description" content="<?= esc($ogDesc) ?>">
    <meta name="twitter:image" content="<?= esc($ogImage) ?>">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            line-height: 1.6;
            color: #333;
            background: #f8f9fa;
        }
        /* Header */
        .header {
            background: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .header-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .logo {
            font-size: 24px;
            font-weight: 700;
            color: #005461;
            text-decoration: none;
        }
        .nav-menu {
            display: flex;
            gap: 30px;
            align-items: center;
        }
        .nav-menu a {
            text-decoration: none;
            color: #333;
            font-weight: 500;
            transition: color 0.3s;
        }
        .nav-menu a:hover {
            color: #0C7779;
        }
        /* Main Container */
        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 30px;
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 40px;
        }
        /* Content Area */
        .content {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        /* Sidebar */
        .sidebar {
            display: flex;
            flex-direction: column;
            gap: 30px;
        }
        .widget {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        .widget-title {
            font-size: 18px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f0f0f0;
        }
        .widget ul {
            list-style: none;
        }
        .widget ul li {
            margin-bottom: 12px;
        }
        .widget ul li a {
            text-decoration: none;
            color: #555;
            transition: color 0.3s;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .widget ul li a:hover {
            color: #0C7779;
        }
        .category-count {
            background: #e6f7f5;
            color: #0C7779;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
        }
        /* Footer */
        .footer {
            background: #2c3e50;
            color: white;
            padding: 40px 30px;
            margin-top: 60px;
            text-align: center;
        }
        /* Responsive */
        /* Responsive */
        @media (max-width: 768px) {
            .container {
                grid-template-columns: 1fr;
            }
            .sidebar {
                order: 2;
            }
            .header-container {
                justify-content: space-between;
                position: relative;
            }
            .menu-toggle { display: block; }
            .nav-menu {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                width: 100%;
                background: white;
                flex-direction: column;
                padding: 15px;
                box-shadow: 0 4px 10px rgba(0,0,0,0.1);
                text-align: center;
                gap: 15px;
                z-index: 2000;
            }
            .nav-menu.active { display: flex !important; }
        }
        
        .menu-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #333;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-container">
            <a href="<?= base_url('/') ?>" class="logo">
                <?php if (site_logo_type() === 'image' && site_logo()): ?>
                    <img src="<?= site_logo() ?>" alt="<?= site_name() ?>" style="height: 40px; width: auto;">
                <?php else: ?>
                    <?= site_name() ?>
                <?php endif; ?>
            </a>
            <button class="menu-toggle">☰</button>
            <nav class="nav-menu">
                <?php 
                $menuItems = get_menu_items('primary');
                foreach ($menuItems as $item): 
                ?>
                    <a href="<?= get_menu_item_url($item) ?>" target="<?= esc($item['target']) ?>"><?= esc($item['title']) ?></a>
                <?php endforeach; ?>
            </nav>
        </div>
    </header>

    <!-- Main Container -->
    <div class="container">
        <!-- Content Area (2/3) -->
        <main class="content">
            <?= $this->renderSection('content') ?>
        </main>

        <!-- Sidebar (1/3) -->
        <aside class="sidebar">
            <!-- Categories Widget -->
            <div class="widget">
                <h3 class="widget-title">Categories</h3>
                <ul>
                    <?php if (!empty($categories)): ?>
                        <?php foreach ($categories as $category): ?>
                            <li>
                                <a href="<?= base_url('blog/category/' . $category['slug']) ?>">
                                    <span><?= esc($category['name']) ?></span>
                                    <span class="category-count"><?= $category['post_count'] ?? 0 ?></span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li style="color: #999;">No categories yet</li>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- Recent Posts Widget -->
            <div class="widget">
                <h3 class="widget-title">Recent Posts</h3>
                <ul>
                    <?php if (!empty($recentPosts)): ?>
                        <?php foreach ($recentPosts as $recentPost): ?>
                            <li>
                                <a href="<?= base_url('blog/post/' . $recentPost['slug']) ?>">
                                    <?= esc($recentPost['title']) ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li style="color: #999;">No posts yet</li>
                    <?php endif; ?>
                </ul>
            </div>
        </aside>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; <?= date('Y') ?> ElsaCMS. All rights reserved.</p>
    </footer>

    <script>
        document.querySelector('.menu-toggle').addEventListener('click', function() {
            document.querySelector('.nav-menu').classList.toggle('active');
        });
    </script>
</body>
</html>
