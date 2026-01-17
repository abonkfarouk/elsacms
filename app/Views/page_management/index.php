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
        background: #0C7779;
        color: white;
    }
    .btn-primary:hover {
        background: #005461;
    }
    .btn-sm {
        padding: 6px 12px;
        font-size: 13px;
    }
    .btn-secondary {
        background: #6c757d;
        color: white;
    }
    .btn-secondary:hover {
        background: #5a6268;
    }
    .btn-danger {
        background: #dc3545;
        color: white;
    }
    .btn-danger:hover {
        background: #c82333;
    }
    .btn-info {
        background: #17a2b8;
        color: white;
    }
    .btn-info:hover {
        background: #138496;
    }
    .table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }
    .table thead {
        background: #f7fafc;
    }
    .table th {
        padding: 12px;
        text-align: left;
        font-weight: 600;
        color: #2d3748;
        border-bottom: 2px solid #e2e8f0;
    }
    .table td {
        padding: 12px;
        border-bottom: 1px solid #e2e8f0;
    }
    .table tbody tr:hover {
        background: #f7fafc;
    }
    .badge {
        padding: 4px 12px;
        border-radius: 15px;
        font-size: 12px;
        font-weight: 500;
    }
    .badge-success {
        background: #d4f4dd;
        color: #249E94;
    }
    .badge-warning {
        background: #fed7aa;
        color: #ea580c;
    }
    .alert {
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 20px;
    }
    .alert-success {
        background: #d4f4dd;
        color: #249E94;
        border: 1px solid #249E94;
    }
    .alert-error {
        background: #fee;
        color: #c33;
        border: 1px solid #c33;
    }
</style>


<div class="card">
    <div class="card-header">
        <div class="card-title">Page Management</div>
        <a href="<?= base_url('page-management/create') ?>" class="btn btn-primary">Create New Page</a>
    </div>
    <div class="card-body">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-error"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <?php if (!empty($pages)): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Slug</th>
                        <th>Status</th>
                        <th>In Menu</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pages as $page): ?>
                    <tr>
                        <td><?= esc($page['title']) ?></td>
                        <td><code><?= esc($page['slug']) ?></code></td>
                        <td>
                            <span class="badge badge-<?= $page['status'] === 'published' ? 'success' : 'warning' ?>">
                                <?= ucfirst($page['status']) ?>
                            </span>
                        </td>
                        <td><?= $page['show_in_menu'] ? '✓' : '-' ?></td>
                        <td><?= date('M d, Y', strtotime($page['created_at'])) ?></td>
                        <td>
                            <a href="<?= base_url('page-management/edit/' . $page['id']) ?>" class="btn btn-sm btn-secondary">Edit</a>
                            <form action="<?= base_url('page-management/delete/' . $page['id']) ?>" method="post" style="display: inline;" onsubmit="return confirm('Are you sure?')">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                            <?php if ($page['status'] === 'published'): ?>
                                <a href="<?= base_url('page/' . $page['slug']) ?>" class="btn btn-sm btn-info" target="_blank">View</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p style="text-align: center; color: #999; padding: 40px;">No pages yet. Create your first page!</p>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>
