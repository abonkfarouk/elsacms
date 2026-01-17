<?= $this->extend('blog/layout') ?>

<?= $this->section('content') ?>

<style>
    .page-title {
        font-size: 32px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 10px;
    }
    .category-description {
        color: #7f8c8d;
        margin-bottom: 30px;
        font-size: 16px;
    }
    .post-card {
        margin-bottom: 40px;
        padding-bottom: 40px;
        border-bottom: 1px solid #e0e0e0;
    }
    .post-card:last-child {
        border-bottom: none;
    }
    .post-featured-image {
        width: 100%;
        height: 250px;
        object-fit: cover;
        border-radius: 8px;
        margin-bottom: 20px;
    }
    .post-title {
        font-size: 24px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 10px;
    }
    .post-title a {
        text-decoration: none;
        color: inherit;
        transition: color 0.3s;
    }
    .post-title a:hover {
        color: #0C7779;
    }
    .post-meta {
        display: flex;
        gap: 20px;
        margin-bottom: 15px;
        font-size: 14px;
        color: #7f8c8d;
    }
    .post-excerpt {
        color: #555;
        line-height: 1.8;
        margin-bottom: 15px;
    }
    .read-more {
        display: inline-block;
        color: #0C7779;
        text-decoration: none;
        font-weight: 500;
    }
    .read-more:hover {
        color: #005461;
    }
    .no-posts {
        text-align: center;
        padding: 60px 20px;
        color: #999;
    }
    .back-link {
        display: inline-block;
        margin-bottom: 30px;
        color: #0C7779;
        text-decoration: none;
        font-weight: 500;
    }
    .back-link:hover {
        color: #005461;
    }
</style>

<a href="<?= base_url('blog') ?>" class="back-link">← Back to All Posts</a>

<h1 class="page-title">Category: <?= esc($category['name']) ?></h1>

<?php if (!empty($category['description'])): ?>
    <p class="category-description"><?= esc($category['description']) ?></p>
<?php endif; ?>

<?php if (!empty($posts)): ?>
    <?php foreach ($posts as $post): ?>
        <article class="post-card">
            <?php if (!empty($post['featured_image'])): ?>
                <img src="<?= base_url($post['featured_image']) ?>" alt="<?= esc($post['title']) ?>" class="post-featured-image">
            <?php endif; ?>
            
            <h2 class="post-title">
                <a href="<?= base_url('blog/post/' . $post['slug']) ?>">
                    <?= esc($post['title']) ?>
                </a>
            </h2>
            
            <div class="post-meta">
                <span>📅 <?= date('F d, Y', strtotime($post['published_at'])) ?></span>
                <span>✍️ <?= esc($post['author_name']) ?></span>
            </div>
            
            <?php if (!empty($post['excerpt'])): ?>
                <div class="post-excerpt">
                    <?= esc($post['excerpt']) ?>
                </div>
            <?php endif; ?>
            
            <a href="<?= base_url('blog/post/' . $post['slug']) ?>" class="read-more">
                Read More →
            </a>
        </article>
    <?php endforeach; ?>
<?php else: ?>
    <div class="no-posts">
        <p>No posts in this category yet.</p>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>
