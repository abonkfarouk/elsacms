<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($page['meta_title'] ?: $page['title']) ?> - <?= site_name() ?></title>
    <meta name="description" content="<?= esc($page['meta_description'] ?: $page['excerpt']) ?>">
    <?php if (!empty($page['meta_keywords'])): ?>
    <meta name="keywords" content="<?= esc($page['meta_keywords']) ?>">
    <?php endif; ?>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #333;
            background: #f8f9fa;
        }
        .header {
            background: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px 0;
        }
        .header-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 0 30px;
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
        .nav a {
            color: #4a5568;
            text-decoration: none;
            margin-left: 30px;
            font-weight: 500;
        }
        .nav a:hover {
            color: #0C7779;
        }
        .container {
            max-width: 900px;
            margin: 40px auto;
            padding: 0 30px;
        }
        .page-header {
            background: white;
            padding: 50px;
            border-radius: 12px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        .page-title {
            font-size: 42px;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 20px;
            line-height: 1.2;
        }
        .page-excerpt {
            font-size: 18px;
            color: #718096;
            line-height: 1.6;
        }
        .featured-image {
            width: 100%;
            height: 400px;
            object-fit: cover;
            border-radius: 12px;
            margin-bottom: 30px;
        }
        .page-content {
            background: white;
            padding: 50px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        .page-content h1, .page-content h2, .page-content h3 {
            color: #2d3748;
            margin-top: 30px;
            margin-bottom: 15px;
        }
        .page-content p {
            margin-bottom: 20px;
        }
        .page-content ul, .page-content ol {
            margin-bottom: 20px;
            padding-left: 30px;
        }
        .page-content a {
            color: #0C7779;
            text-decoration: none;
        }
        .page-content a:hover {
            text-decoration: underline;
        }
        .footer {
            background: #2d3748;
            color: white;
            padding: 40px 30px;
            margin-top: 60px;
            text-align: center;
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-container">
            <a href="<?= base_url('/') ?>" class="logo"><?= site_name() ?></a>
            <nav class="nav">
                <a href="<?= base_url('/') ?>">Home</a>
                <a href="<?= base_url('blog') ?>">Blog</a>
            </nav>
        </div>
    </header>

    <div class="container">
        <div class="page-header">
            <h1 class="page-title"><?= esc($page['title']) ?></h1>
            <?php if (!empty($page['excerpt'])): ?>
                <p class="page-excerpt"><?= esc($page['excerpt']) ?></p>
            <?php endif; ?>
        </div>

        <?php if (!empty($page['featured_image'])): ?>
            <img src="<?= base_url($page['featured_image']) ?>" alt="<?= esc($page['title']) ?>" class="featured-image">
        <?php endif; ?>

        <div class="page-content">
            <?= $page['content'] ?>
        </div>
    </div>

    <footer class="footer">
        <p>&copy; <?= date('Y') ?> <?= site_name() ?>. All rights reserved.</p>
    </footer>
</body>
</html>
