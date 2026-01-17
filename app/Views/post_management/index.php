<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<style>
    .btn {
        padding: 10px 20px;
        border-radius: 5px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.3s;
        border: none;
        cursor: pointer;
        display: inline-block;
    }
    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    }
    .btn-edit {
        background: #28a745;
        color: white;
        padding: 6px 12px;
        font-size: 12px;
    }
    .btn-edit:hover {
        background: #218838;
    }
    .btn-delete {
        background: #dc3545;
        color: white;
        padding: 6px 12px;
        font-size: 12px;
    }
    .btn-delete:hover {
        background: #c82333;
    }
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }
    .page-title {
        font-size: 24px;
        font-weight: 600;
        color: #2c3e50;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }
    thead {
        background-color: #f8f9fa;
    }
    th, td {
        padding: 15px;
        text-align: left;
        border-bottom: 1px solid #dee2e6;
    }
    th {
        font-weight: 600;
        color: #495057;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    td {
        color: #333;
        font-size: 14px;
    }
    tbody tr:hover {
        background-color: #f8f9fa;
    }
    .action-buttons {
        display: flex;
        gap: 8px;
    }
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #999;
    }
    .empty-state p {
        font-size: 16px;
        margin-bottom: 10px;
    }
    .status-badge {
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 500;
    }
    .status-draft {
        background: #fff3cd;
        color: #856404;
    }
    .status-published {
        background: #d4edda;
        color: #155724;
    }
    .post-title {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 5px;
    }
    .post-meta {
        font-size: 12px;
        color: #7f8c8d;
    }
</style>

<div class="page-header">
    <div class="page-title">Post Management</div>
    <a href="<?= base_url('post-management/create') ?>" class="btn btn-primary">+ Create Post</a>
</div>

<?php if (empty($posts)): ?>
    <div class="card">
        <div class="empty-state">
            <p>No posts yet.</p>
            <a href="<?= base_url('post-management/create') ?>" class="btn btn-primary">Create first post</a>
        </div>
    </div>
<?php else: ?>
    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Author</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($posts as $post): ?>
                    <tr>
                        <td>
                            <div class="post-title"><?= esc($post['title']) ?></div>
                            <div class="post-meta"><?= esc($post['slug']) ?></div>
                        </td>
                        <td><?= esc($post['category_name'] ?? 'Uncategorized') ?></td>
                        <td><?= esc($post['author_name']) ?></td>
                        <td>
                            <span class="status-badge status-<?= $post['status'] ?>">
                                <?= ucfirst($post['status']) ?>
                            </span>
                        </td>
                        <td><?= date('M d, Y', strtotime($post['created_at'])) ?></td>
                        <td>
                            <div class="action-buttons">
                                <a href="<?= base_url('post-management/edit/' . $post['id']) ?>" class="btn btn-edit">Edit</a>
                                <form action="<?= base_url('post-management/delete/' . $post['id']) ?>" method="post" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this post?');">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-delete">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>
