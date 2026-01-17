<?= $this->extend('blog/layout') ?>

<?= $this->section('content') ?>

<style>
    .post-header {
        margin-bottom: 30px;
    }
    .post-title {
        font-size: 36px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 15px;
        line-height: 1.3;
    }
    .post-meta {
        display: flex;
        gap: 20px;
        margin-bottom: 20px;
        font-size: 14px;
        color: #7f8c8d;
        flex-wrap: wrap;
    }
    .post-meta span {
        display: flex;
        align-items: center;
        gap: 5px;
    }
    .post-category {
        background: #0C7779;
        color: white;
        padding: 4px 12px;
        border-radius: 15px;
        font-size: 12px;
        font-weight: 500;
        text-decoration: none;
    }
    .post-featured-image {
        width: 100%;
        height: 400px;
        object-fit: cover;
        border-radius: 8px;
        margin-bottom: 30px;
    }
    .post-content {
        color: #333;
        line-height: 1.8;
        font-size: 16px;
    }
    .post-content p {
        margin-bottom: 20px;
    }
    .post-content h2 {
        margin-top: 30px;
        margin-bottom: 15px;
        color: #2c3e50;
    }
    .post-content h3 {
        margin-top: 25px;
        margin-bottom: 12px;
        color: #2c3e50;
    }
    .back-link {
        display: inline-block;
        margin-top: 40px;
        color: #0C7779;
        text-decoration: none;
        font-weight: 500;
    }
    .back-link:hover {
        color: #005461;
    }
    
    @media (max-width: 768px) {
        .post-featured-image {
            height: 250px;
        }
        .post-title {
            font-size: 28px;
        }
    }
    
    /* Related Posts */
    .related-section { margin-top: 60px; padding-top: 40px; border-top: 1px solid #e0e0e0; }
    .related-title { font-size: 24px; font-weight: 700; color: #2c3e50; margin-bottom: 25px; }
    .related-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px; }
    .related-card { background: white; border: 1px solid #eee; border-radius: 8px; overflow: hidden; transition: transform 0.2s; }
    .related-card:hover { transform: translateY(-3px); box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
    .related-img { width: 100%; height: 150px; object-fit: cover; background: #f8f9fa; }
    .related-content { padding: 15px; }
    .related-date { font-size: 12px; color: #7f8c8d; margin-bottom: 5px; display: block; }
    .related-link { font-size: 16px; font-weight: 600; color: #2c3e50; text-decoration: none; line-height: 1.4; display: block; }
    .related-link:hover { color: #0C7779; }
</style>

<article>
    <div class="post-header">
        <h1 class="post-title"><?= esc($post['title']) ?></h1>
        
        <div class="post-meta">
            <?php if (!empty($post['category_name'])): ?>
                <span>
                    <a href="<?= base_url('blog/category/' . $post['category_slug']) ?>" class="post-category">
                        <?= esc($post['category_name']) ?>
                    </a>
                </span>
            <?php endif; ?>
            <span>📅 <?= date('F d, Y', strtotime($post['published_at'])) ?></span>
            <span>✍️ <?= esc($post['author_name']) ?></span>
        </div>
    </div>
    
    <?php if (!empty($post['featured_image'])): ?>
        <img src="<?= base_url($post['featured_image']) ?>" alt="<?= esc($post['title']) ?>" class="post-featured-image">
    <?php endif; ?>
    
    <div class="post-content">
        <?= $post['content'] ?>
    </div>
    
    <?php if (!empty($relatedPosts)): ?>
    <div class="related-section">
        <h3 class="related-title">Related Posts</h3>
        <div class="related-grid">
            <?php foreach ($relatedPosts as $rPost): ?>
                <div class="related-card">
                    <a href="<?= base_url('blog/post/' . $rPost['slug']) ?>" style="text-decoration:none;">
                        <?php if(!empty($rPost['featured_image'])): ?>
                            <img src="<?= base_url($rPost['featured_image']) ?>" alt="<?= esc($rPost['title']) ?>" class="related-img">
                        <?php else: ?>
                            <div class="related-img" style="display:flex;align-items:center;justify-content:center;color:#ccc;font-size:24px;">📝</div>
                        <?php endif; ?>
                    </a>
                    <div class="related-content">
                        <span class="related-date"><?= date('M d, Y', strtotime($rPost['published_at'])) ?></span>
                        <a href="<?= base_url('blog/post/' . $rPost['slug']) ?>" class="related-link">
                            <?= esc($rPost['title']) ?>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
    
    <a href="<?= base_url('blog') ?>" class="back-link">← Back to Blog</a>
</article>

<?= $this->endSection() ?>
