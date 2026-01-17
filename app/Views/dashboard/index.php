<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php helper(['rbac', 'setting']); ?>

<style>
    .dashboard-header {
        margin-bottom: 30px;
    }
    .dashboard-header h1 {
        font-size: 28px;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 5px;
    }
    .dashboard-header p {
        color: #718096;
        font-size: 14px;
    }
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }
    .stat-card {
        background: white;
        padding: 25px;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        transition: all 0.3s;
    }
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        border-color: #0C7779;
    }
    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin-bottom: 15px;
    }
    .stat-icon.blue {
        background: #e6f7f5;
        color: #0C7779;
    }
    .stat-icon.green {
        background: #d4f4dd;
        color: #249E94;
    }
    .stat-icon.purple {
        background: #e9d5ff;
        color: #9333ea;
    }
    .stat-icon.orange {
        background: #fed7aa;
        color: #ea580c;
    }
    .stat-value {
        font-size: 32px;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 5px;
    }
    .stat-label {
        color: #718096;
        font-size: 14px;
        font-weight: 500;
    }
    .stat-sublabel {
        color: #a0aec0;
        font-size: 12px;
        margin-top: 5px;
    }
    .quick-actions {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-bottom: 30px;
    }
    .action-card {
        background: white;
        padding: 20px;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        text-decoration: none;
        color: #2d3748;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        gap: 15px;
    }
    .action-card:hover {
        border-color: #0C7779;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(12, 119, 121, 0.15);
    }
    .action-icon {
        font-size: 28px;
    }
    .action-text {
        flex: 1;
    }
    .action-title {
        font-weight: 600;
        font-size: 14px;
        color: #2d3748;
    }
    .action-desc {
        font-size: 12px;
        color: #a0aec0;
        margin-top: 2px;
    }
    .recent-posts {
        background: white;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }
    .recent-posts-header {
        padding: 20px 25px;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .recent-posts-title {
        font-size: 18px;
        font-weight: 600;
        color: #2d3748;
    }
    .view-all-link {
        color: #0C7779;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
    }
    .view-all-link:hover {
        color: #005461;
    }
    .post-item {
        padding: 20px 25px;
        border-bottom: 1px solid #f7fafc;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: background 0.2s;
    }
    .post-item:hover {
        background: #f7fafc;
    }
    .post-item:last-child {
        border-bottom: none;
    }
    .post-info {
        flex: 1;
    }
    .post-title {
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 5px;
    }
    .post-meta {
        font-size: 12px;
        color: #a0aec0;
    }
    .post-status {
        padding: 4px 12px;
        border-radius: 15px;
        font-size: 12px;
        font-weight: 500;
    }
    .status-published {
        background: #d4f4dd;
        color: #249E94;
    }
    .status-draft {
        background: #fed7aa;
        color: #ea580c;
    }
    .empty-state {
        padding: 40px;
        text-align: center;
        color: #a0aec0;
    }
</style>

<div class="dashboard-header">
    <h1>Welcome back, <?= esc($currentUser['name'] ?? 'User') ?>! 👋</h1>
    <p>Here's what's happening with <?= site_name() ?> today</p>
</div>

<!-- Stats Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue">📝</div>
        <div class="stat-value"><?= $stats['total_posts'] ?></div>
        <div class="stat-label">Total Posts</div>
        <div class="stat-sublabel"><?= $stats['published_posts'] ?> published, <?= $stats['draft_posts'] ?> drafts</div>
    </div>

    <div class="stat-card">
        <div class="stat-icon green">📁</div>
        <div class="stat-value"><?= $stats['total_categories'] ?></div>
        <div class="stat-label">Categories</div>
        <div class="stat-sublabel">Organize your content</div>
    </div>

    <div class="stat-card">
        <div class="stat-icon purple">👥</div>
        <div class="stat-value"><?= $stats['total_users'] ?></div>
        <div class="stat-label">Total Users</div>
        <div class="stat-sublabel"><?= $stats['active_users'] ?> active users</div>
    </div>

    <div class="stat-card">
        <div class="stat-icon orange">⚙️</div>
        <div class="stat-value"><?= site_name() ?></div>
        <div class="stat-label">Site Name</div>
        <div class="stat-sublabel"><?= site_tagline() ?></div>
    </div>
</div>

<!-- Quick Actions -->
<?php if (hasPermission('manage_content') || hasPermission('manage_users') || hasPermission('manage_settings')): ?>
<div class="card">
    <div class="card-header">
        <div class="card-title">Quick Actions</div>
    </div>
    <div class="card-body">
        <div class="quick-actions">
            <?php if (hasPermission('manage_content')): ?>
            <a href="<?= base_url('post-management/create') ?>" class="action-card">
                <div class="action-icon">✍️</div>
                <div class="action-text">
                    <div class="action-title">New Post</div>
                    <div class="action-desc">Create a new blog post</div>
                </div>
            </a>
            
            <a href="<?= base_url('category-management/create') ?>" class="action-card">
                <div class="action-icon">📂</div>
                <div class="action-text">
                    <div class="action-title">New Category</div>
                    <div class="action-desc">Add a new category</div>
                </div>
            </a>
            <?php endif; ?>
            
            <?php if (hasPermission('manage_users')): ?>
            <a href="<?= base_url('user-management/create') ?>" class="action-card">
                <div class="action-icon">👤</div>
                <div class="action-text">
                    <div class="action-title">New User</div>
                    <div class="action-desc">Add a new user</div>
                </div>
            </a>
            <?php endif; ?>
            
            <?php if (hasPermission('manage_settings')): ?>
            <a href="<?= base_url('settings') ?>" class="action-card">
                <div class="action-icon">⚙️</div>
                <div class="action-text">
                    <div class="action-title">Settings</div>
                    <div class="action-desc">Customize your site</div>
                </div>
            </a>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Recent Posts -->
<div class="recent-posts">
    <div class="recent-posts-header">
        <div class="recent-posts-title">Recent Posts</div>
        <?php if (hasPermission('manage_content')): ?>
        <a href="<?= base_url('post-management') ?>" class="view-all-link">View All →</a>
        <?php endif; ?>
    </div>
    
    <?php if (!empty($recentPosts)): ?>
        <?php foreach ($recentPosts as $post): ?>
        <div class="post-item">
            <div class="post-info">
                <div class="post-title"><?= esc($post['title']) ?></div>
                <div class="post-meta">
                    Created <?= date('M d, Y', strtotime($post['created_at'])) ?>
                </div>
            </div>
            <span class="post-status status-<?= $post['status'] ?>">
                <?= ucfirst($post['status']) ?>
            </span>
        </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="empty-state">
            <p>No posts yet. Create your first post to get started!</p>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
