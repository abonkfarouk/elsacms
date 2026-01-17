<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<style>
    .btn-action {
        text-decoration: none;
        padding: 5px 10px;
        border-radius: 4px;
        font-size: 14px;
        display: inline-block;
        margin-right: 5px;
    }
    .btn-edit { background: #ed8936; color: white; }
    .btn-delete { background: #f56565; color: white; }
    .btn-primary { background: #0C7779; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; }
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
</style>

<div class="page-header">
    <h2>Permission Management</h2>
    <a href="<?= base_url('permission-management/new') ?>" class="btn-primary">
        + Create New Permission
    </a>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success" style="margin-bottom: 20px; padding: 15px; background: #c6f6d5; color: #2f855a; border-radius: 6px;">
        <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger" style="margin-bottom: 20px; padding: 15px; background: #fed7d7; color: #c53030; border-radius: 6px;">
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <div class="card-title">Permissions List</div>
    </div>
    <div class="card-body">
        <table class="table" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="text-align: left; background: #f7fafc; border-bottom: 2px solid #edf2f7;">
                    <th style="padding: 12px;">ID</th>
                    <th style="padding: 12px;">Name</th>
                    <th style="padding: 12px;">Description</th>
                    <th style="padding: 12px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($permissions as $permission): ?>
                    <tr style="border-bottom: 1px solid #edf2f7;">
                        <td style="padding: 12px;"><?= esc($permission['id']) ?></td>
                        <td style="padding: 12px;"><code><?= esc($permission['name']) ?></code></td>
                        <td style="padding: 12px; color: #718096;"><?= esc($permission['description']) ?></td>
                        <td style="padding: 12px;">
                            <a href="<?= base_url('permission-management/edit/' . $permission['id']) ?>" class="btn-action btn-edit">
                                ✏️ Edit
                            </a>
                            <a href="<?= base_url('permission-management/delete/' . $permission['id']) ?>" class="btn-action btn-delete" onclick="return confirm('Are you sure you want to delete this permission?');">
                                🗑️ Delete
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
