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
        padding: 12px 30px;
        font-size: 16px;
    }
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    }
    .btn-secondary {
        background: #6c757d;
        color: white;
    }
    .btn-secondary:hover {
        background: #5a6268;
    }
    .form-group {
        margin-bottom: 20px;
    }
    .form-group label {
        display: block;
        margin-bottom: 8px;
        color: #2c3e50;
        font-weight: 500;
        font-size: 14px;
    }
    .form-group input[type="text"],
    .form-group input[type="email"],
    .form-group input[type="password"],
    .form-group input[type="email"] {
        width: 100%;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 14px;
        transition: border-color 0.3s;
    }
    .form-group input:focus {
        outline: none;
        border-color: #667eea;
    }
    .form-group input[type="checkbox"] {
        width: 18px;
        height: 18px;
        margin-right: 8px;
        cursor: pointer;
    }
    .checkbox-group {
        display: flex;
        align-items: center;
    }
    .checkbox-group label {
        margin-bottom: 0;
        cursor: pointer;
    }
    .roles-group {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-top: 10px;
    }
    .role-checkbox {
        display: flex;
        align-items: center;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 5px;
        transition: background-color 0.3s;
    }
    .role-checkbox:hover {
        background-color: #f8f9fa;
    }
    .role-checkbox input[type="checkbox"] {
        margin-right: 10px;
    }
    .form-actions {
        display: flex;
        gap: 10px;
        margin-top: 30px;
    }
</style>

<div class="card">
    <div class="card-header">
        <div class="card-title">Tambah User Baru</div>
    </div>
    <div class="card-body">
        <form action="<?= base_url('user-management/store') ?>" method="post">
            <?= csrf_field() ?>
            
            <div class="form-group">
                <label for="username">Username *</label>
                <input type="text" id="username" name="username" value="<?= old('username') ?>" required autofocus>
            </div>

            <div class="form-group">
                <label for="email">Email *</label>
                <input type="email" id="email" name="email" value="<?= old('email') ?>" required>
            </div>

            <div class="form-group">
                <label for="full_name">Nama Lengkap</label>
                <input type="text" id="full_name" name="full_name" value="<?= old('full_name') ?>">
            </div>

            <div class="form-group">
                <label for="password">Password *</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="form-group">
                <label for="password_confirm">Konfirmasi Password *</label>
                <input type="password" id="password_confirm" name="password_confirm" required>
            </div>

            <div class="form-group">
                <div class="checkbox-group">
                    <input type="checkbox" id="is_active" name="is_active" value="1" <?= old('is_active') ? 'checked' : 'checked' ?>>
                    <label for="is_active">Aktif</label>
                </div>
            </div>

            <div class="form-group">
                <label>Roles</label>
                <div class="roles-group">
                    <?php foreach ($roles as $role): ?>
                        <div class="role-checkbox">
                            <input type="checkbox" id="role_<?= $role['id'] ?>" name="roles[]" value="<?= $role['id'] ?>">
                            <label for="role_<?= $role['id'] ?>"><?= esc($role['name']) ?> - <?= esc($role['description']) ?></label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="<?= base_url('user-management') ?>" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
