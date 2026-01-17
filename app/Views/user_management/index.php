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
</style>

<div class="page-header">
    <div class="page-title">User Management</div>
    <a href="<?= base_url('user-management/create') ?>" class="btn btn-primary">+ Tambah User</a>
</div>

<?php if (empty($users)): ?>
    <div class="card">
        <div class="empty-state">
            <p>Belum ada user.</p>
            <a href="<?= base_url('user-management/create') ?>" class="btn btn-primary">Tambah user pertama</a>
        </div>
    </div>
<?php else: ?>
    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Nama Lengkap</th>
                    <th>Roles</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= $user['id'] ?></td>
                        <td><strong><?= esc($user['username']) ?></strong></td>
                        <td><?= esc($user['email']) ?></td>
                        <td><?= esc($user['full_name'] ?: '-') ?></td>
                        <td>
                            <?php if (!empty($user['roles'])): ?>
                                <?php foreach ($user['roles'] as $role): ?>
                                    <span class="badge badge-role"><?= esc($role['name']) ?></span>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <span style="color: #999;">-</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($user['is_active']): ?>
                                <span class="badge badge-success">Aktif</span>
                            <?php else: ?>
                                <span class="badge badge-danger">Tidak Aktif</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="<?= base_url('user-management/edit/' . $user['id']) ?>" class="btn btn-edit">Edit</a>
                                <form action="<?= base_url('user-management/delete/' . $user['id']) ?>" method="post" style="display: inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?');">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn btn-delete">Hapus</button>
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
