<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= site_name() ?> - <?= site_tagline() ?></title>
    <meta name="description" content="<?= site_description() ?>">
    
    <!-- Open Graph -->
    <meta property="og:title" content="<?= site_name() ?> - <?= site_tagline() ?>">
    <meta property="og:description" content="<?= site_description() ?>">
    <meta property="og:image" content="<?= site_hero_bg() ?: base_url('favicon.ico') ?>">
    <meta property="og:url" content="<?= current_url() ?>">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="<?= site_name() ?>">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:image" content="<?= site_hero_bg() ?: base_url('favicon.ico') ?>">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #0C7779;
            --primary-dark: #005461;
            --secondary: #2d3748;
            --text: #4a5568;
            --bg-light: #f8fafc;
            --white: #ffffff;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            color: var(--text);
            line-height: 1.6;
            background: var(--white);
            scroll-behavior: smooth;
        }
        
        /* Navigation */
        .navbar {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid #e2e8f0;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            padding: 16px 0;
            transition: all 0.3s ease;
        }
        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .logo {
            font-size: 24px;
            font-weight: 800;
            color: var(--primary-dark);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .logo-img { height: 36px; width: auto; }
        .nav-links { display: flex; gap: 32px; align-items: center; }
        .nav-links a {
            text-decoration: none;
            color: var(--secondary);
            font-weight: 600;
            font-size: 15px;
            transition: color 0.2s;
        }
        .nav-links a:hover { color: var(--primary); }
        .btn-nav {
            background: var(--primary);
            color: white !important;
            padding: 10px 24px;
            border-radius: 99px;
            font-weight: 600;
            transition: all 0.2s;
        }
        .btn-nav:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(12, 119, 121, 0.25);
        }

        /* Hero Section */
        .hero {
            background: linear-gradient(145deg, #f0fdfa 0%, #e6fffa 100%);
            padding: 160px 24px 100px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .hero::before {
            content: '';
            position: absolute;
            top: -50px; left: -50px;
            width: 300px; height: 300px;
            background: rgba(12, 119, 121, 0.05);
            border-radius: 50%;
            filter: blur(50px);
        }
        .hero-content {
            max-width: 800px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }
        .hero-badge {
            display: inline-block;
            background: rgba(12, 119, 121, 0.1);
            color: var(--primary);
            padding: 6px 16px;
            border-radius: 99px;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 24px;
        }
        .hero h1 {
            font-size: 64px;
            font-weight: 800;
            margin-bottom: 24px;
            line-height: 1.1;
            color: #1a202c;
            letter-spacing: -0.02em;
        }
        .hero p {
            font-size: 20px;
            margin-bottom: 40px;
            color: #4a5568;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }
        
        /* Dynamic Hero Styles */
        .hero-with-bg h1, 
        .hero-with-bg p { 
            color: white !important; 
            text-shadow: 0 2px 12px rgba(0,0,0,0.3); 
        }
        .hero-with-bg .hero-badge { 
            background: rgba(255,255,255,0.2) !important; 
            color: white !important; 
            backdrop-filter: blur(4px);
        }
        
        .hero-buttons {
            display: flex;
            gap: 16px;
            justify-content: center;
        }
        
        .hero-stats {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 32px;
            margin-top: 64px;
            padding-top: 32px;
            border-top: 1px solid rgba(0,0,0,0.05);
        }
        .hero-with-bg .hero-stats { border-top-color: rgba(255,255,255,0.2); }
        
        .stat-item { text-align: center; }
        .stat-number {
            font-size: 36px;
            font-weight: 800;
            color: var(--primary);
            line-height: 1;
            margin-bottom: 4px;
        }
        .hero-with-bg .stat-number { color: white !important; text-shadow: 0 2px 10px rgba(0,0,0,0.2); }
        
        .stat-label {
            font-size: 13px;
            color: #718096;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .hero-with-bg .stat-label { color: rgba(255,255,255,0.9) !important; text-shadow: 0 1px 4px rgba(0,0,0,0.2); }
        
        .stat-divider {
            width: 1px;
            height: 40px;
            background: #cbd5e0;
        }
        .hero-with-bg .stat-divider { background: rgba(255,255,255,0.3); }
        .btn {
            padding: 14px 32px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-primary {
            background: var(--primary);
            color: white;
            box-shadow: 0 4px 6px rgba(12, 119, 121, 0.2);
        }
        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(12, 119, 121, 0.3);
        }
        .btn-secondary {
            background: white;
            color: var(--primary);
            border: 1px solid #e2e8f0;
        }
        .btn-secondary:hover {
            border-color: var(--primary);
            background: #f8fafc;
        }

        /* Features Section */
        .section { padding: 80px 24px; }
        .features { background: var(--white); }
        .section-header { text-align: center; margin-bottom: 60px; max-width: 700px; margin-left: auto; margin-right: auto; }
        .section-header h2 {
            font-size: 36px;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 16px;
            letter-spacing: -0.02em;
        }
        .section-header p { font-size: 18px; color: #718096; }
        
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 40px;
            max-width: 1200px;
            margin: 0 auto;
        }
        .feature-card {
            padding: 32px;
            border-radius: 16px;
            background: var(--white);
            border: 1px solid #f0f0f0;
            transition: all 0.3s ease;
        }
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.05);
            border-color: rgba(12, 119, 121, 0.2);
        }
        .feature-icon-wrapper {
            width: 56px; height: 56px;
            background: rgba(12, 119, 121, 0.1);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 24px;
            color: var(--primary);
        }
        .feature-card h3 { font-size: 20px; font-weight: 700; margin-bottom: 12px; color: #1a202c; }
        .feature-card p { color: #718096; line-height: 1.7; }

        /* Posts Section */
        .latest-posts { background: var(--bg-light); }
        .posts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 32px;
            max-width: 1200px;
            margin: 0 auto 48px;
        }
        .post-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.02);
            transition: all 0.3s;
            border: 1px solid #e2e8f0;
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        .post-card:hover { transform: translateY(-4px); box-shadow: 0 12px 24px rgba(0,0,0,0.06); }
        .post-image-wrapper { height: 200px; background: #cbd5e0; overflow: hidden; }
        .post-image { width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s; }
        .post-card:hover .post-image { transform: scale(1.05); }
        .post-content { padding: 24px; flex: 1; display: flex; flex-direction: column; }
        .post-meta { font-size: 13px; color: #718096; margin-bottom: 12px; display: flex; align-items: center; gap: 8px; }
        .post-title { font-size: 20px; font-weight: 700; margin-bottom: 12px; line-height: 1.4; }
        .post-title a { color: #1a202c; text-decoration: none; transition: color 0.2s; }
        .post-title a:hover { color: var(--primary); }
        .post-excerpt { color: #718096; font-size: 15px; margin-bottom: 20px; flex-grow: 1; }
        .read-more { color: var(--primary); font-weight: 600; text-decoration: none; font-size: 14px; display: inline-flex; align-items: center; gap: 4px; }
        
        /* Footer */
        .footer { background: #1a202c; color: #cbd5e0; padding: 80px 24px 32px; }
        .footer-container { max-width: 1200px; margin: 0 auto; display: grid; grid-template-columns: 2fr 1fr 1fr 1fr; gap: 64px; margin-bottom: 64px; }
        .footer-brand h2 { color: white; margin-bottom: 16px; font-size: 24px; }
        .footer-brand p { font-size: 15px; line-height: 1.8; opacity: 0.8; max-width: 300px; }
        .footer-col h4 { color: white; font-size: 16px; font-weight: 700; margin-bottom: 24px; }
        .footer-links { list-style: none; }
        .footer-links li { margin-bottom: 12px; }
        .footer-links a { color: #a0aec0; text-decoration: none; transition: color 0.2s; }
        .footer-links a:hover { color: white; }
        .footer-bottom { border-top: 1px solid #2d3748; padding-top: 32px; text-align: center; font-size: 14px; color: #718096; }

        @media (max-width: 768px) {
            .hero h1 { font-size: 40px; }
            .footer-container { grid-template-columns: 1fr; gap: 40px; }
            
            /* Mobile Nav */
            .menu-toggle { display: block !important; }
            .nav-container { justify-content: space-between; position: relative; }
            .nav-links {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                width: 100%;
                background: white;
                flex-direction: column;
                padding: 24px;
                box-shadow: 0 10px 15px rgba(0,0,0,0.1);
                text-align: center;
                z-index: 2000;
            }
            .nav-links.active { display: flex !important; animation: slideDown 0.3s ease; }
            
            /* Hero Adjustments */
            .hero { padding: 120px 20px 60px; }
            .hero-stats { flex-direction: column; gap: 24px; margin-top: 40px; }
            .hero-stats .stat-divider { width: 40px; height: 1px; }
            .hero-buttons { flex-direction: column; max-width: 100%; }
            .btn { width: 100%; justify-content: center; }
        }
        
        .menu-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 28px;
            color: var(--secondary);
            cursor: pointer;
            padding: 5px;
        }
        
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="nav-container">
            <a href="/" class="logo">
                <?php if (site_logo_type() === 'image' && site_logo()): ?>
                    <img src="<?= site_logo() ?>" alt="<?= site_name() ?>" class="logo-img">
                <?php else: ?>
                    <?= site_name() ?>
                <?php endif; ?>
            </a>
            
            <button class="menu-toggle" aria-label="Toggle Menu">☰</button>
            <div class="nav-links">
                <?php 
                $menuItems = get_menu_items('primary');
                foreach ($menuItems as $item): 
                ?>
                    <a href="<?= get_menu_item_url($item) ?>" target="<?= esc($item['target']) ?>"><?= esc($item['title']) ?></a>
                <?php endforeach; ?>
                
                <?php if (!session()->get('user_id')): ?>
                    <a href="<?= base_url('auth/login') ?>" class="btn-nav">Sign In</a>
                <?php else: ?>
                    <a href="<?= base_url('dashboard') ?>" class="btn-nav">Dashboard</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <?php $heroBg = site_hero_bg(); ?>
    <section class="hero <?= $heroBg ? 'hero-with-bg' : '' ?>" style="<?php if($heroBg): ?>background: linear-gradient(135deg, rgba(12, 119, 121, 0.65), rgba(13, 148, 136, 0.6)), url('<?= $heroBg ?>'); background-size: cover; background-position: center;<?php endif; ?>">
        <div class="hero-content">
            <span class="hero-badge">New Version 2.0 Available</span>
            <h1><?= esc(site_hero_title()) ?></h1>
            <p><?= esc(site_hero_desc()) ?></p>
            <div class="hero-buttons">
                <a href="<?= site_url(site_hero_btn_url()) ?>" class="btn btn-primary"><?= esc(site_hero_btn_text()) ?></a>
                <a href="<?= site_url('about') ?>" class="btn btn-secondary">Learn More</a>
            </div>
            
            <div class="hero-stats">
                <div class="stat-item">
                    <div class="stat-number">10k+</div>
                    <div class="stat-label">Active Users</div>
                </div>
                <div class="stat-divider"></div>
                <div class="stat-item">
                    <div class="stat-number">500+</div>
                    <div class="stat-label">Premium Templates</div>
                </div>
                <div class="stat-divider"></div>
                <div class="stat-item">
                    <div class="stat-number">99%</div>
                    <div class="stat-label">Uptime</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features -->
    <section class="section features">
        <div class="section-header">
            <h2>Everything you need to grow</h2>
            <p>Powerful features to help you manage content, users, and permissions with ease.</p>
        </div>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon-wrapper">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                </div>
                <h3>Secure by Design</h3>
                <p>Enterprise-grade security with role-based access control (RBAC) built right into the core.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon-wrapper">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                </div>
                <h3>Easy Content Management</h3>
                <p>Intuitive editor and content organization tools make publishing a breeze for your team.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon-wrapper">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>
                </div>
                <h3>SEO Optimized</h3>
                <p>Built for performance and search engines with automatic meta tags and structured data.</p>
            </div>
        </div>
    </section>

    <!-- Latest Posts -->
    <section class="section latest-posts">
        <div class="container"> <!-- Use container class for max-width if grid is inside -->
            <div class="section-header">
                <h2>Latest Insights</h2>
                <p>Stay updated with our latest news, tutorials, and articles.</p>
            </div>
            
            <?php if (!empty($latestPosts)): ?>
                <div class="posts-grid">
                    <?php foreach ($latestPosts as $post): ?>
                        <article class="post-card">
                            <div class="post-image-wrapper">
                                <?php if (!empty($post['featured_image'])): ?>
                                    <img src="<?= base_url($post['featured_image']) ?>" alt="<?= esc($post['title']) ?>" class="post-image">
                                <?php else: ?>
                                    <div style="width:100%; height:100%; background: linear-gradient(135deg, #e0f2f1 0%, #b2dfdb 100%); display:flex; align-items:center; justify-content:center;">
                                        <span style="font-size:32px; opacity:0.3;">📝</span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="post-content">
                                <div class="post-meta">
                                    <span><?= date('M d, Y', strtotime($post['published_at'])) ?></span>
                                    <span>•</span>
                                    <span><?= esc($post['author_name'] ?? 'Admin') ?></span>
                                </div>
                                <h3 class="post-title">
                                    <a href="<?= base_url('blog/post/' . $post['slug']) ?>"><?= esc($post['title']) ?></a>
                                </h3>
                                <p class="post-excerpt"><?= esc(substr(strip_tags($post['excerpt'] ?? ''), 0, 100)) ?>...</p>
                                <a href="<?= base_url('blog/post/' . $post['slug']) ?>" class="read-more">Read Article →</a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
                <div style="text-align: center;">
                    <a href="<?= base_url('blog') ?>" class="btn btn-secondary">View All Posts</a>
                </div>
            <?php else: ?>
                <div style="text-align: center; color: #718096; padding: 40px;">
                    <p>No posts available yet.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-brand">
                <h2><?= site_name() ?></h2>
                <p><?= site_tagline() ?: 'Empowering creators with a modern, secure, and flexible content management system.' ?></p>
            </div>
            <div class="footer-col">
                <h4>Product</h4>
                <ul class="footer-links">
                    <li><a href="#">Features</a></li>
                    <li><a href="#">Pricing</a></li>
                    <li><a href="#">Documentation</a></li>
                    <li><a href="#">Changelog</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Resources</h4>
                <ul class="footer-links">
                    <li><a href="#">Community</a></li>
                    <li><a href="#">Help Center</a></li>
                    <li><a href="#">Partners</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Legal</h4>
                <ul class="footer-links">
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Terms of Service</a></li>
                    <li><a href="#">Security</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?= date('Y') ?> <?= site_name() ?>. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
