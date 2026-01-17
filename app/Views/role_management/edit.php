<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<style>
    .form-label { font-weight: 500; color: #2d3748; margin-bottom: 8px; display: block; }
    .form-control { width: 100%; padding: 10px; border: 1px solid #e2e8f0; border-radius: 6px; margin-bottom: 20px; transition: all 0.3s; }
    .form-control:focus { border-color: #0C7779; outline: none; box-shadow: 0 0 0 3px rgba(12, 119, 121, 0.1); }
    .btn-submit { background: #0C7779; color: white; padding: 10px 24px; border: none; border-radius: 6px; cursor: pointer; font-weight: 500; }
    .btn-submit:hover { background: #005461; }
    .btn-cancel { background: #e2e8f0; color: #4a5568; padding: 10px 24px; border-radius: 6px; text-decoration: none; margin-right: 10px; display: inline-block; }
    .page-header { margin-bottom: 20px; }
    .form-check { display: flex; align-items: start; gap: 10px; margin-bottom: 10px; padding: 8px; border-radius: 6px; transition: background 0.2s; }
    .form-check:hover { background: #f7fafc; }
    .permission-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 10px; }
</style>

<div class="page-header">
    <h2><?= isset($role) ? 'Edit Role' : 'Create New Role' ?></h2>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title">Role Details</div>
    </div>
    <div class="card-body">
        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger" style="margin-bottom: 20px; padding: 15px; background: #fed7d7; color: #c53030; border-radius: 6px;">
                <ul>
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?= isset($role) ? base_url('role-management/update/' . $role['id']) : base_url('role-management/create') ?>" method="post">
            <?= csrf_field() ?>
            
            <label for="name" class="form-label">Role Name</label>
            <input type="text" class="form-control" id="name" name="name" value="<?= old('name', $role['name'] ?? '') ?>" required>
            
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3"><?= old('description', $role['description'] ?? '') ?></textarea>

            <div class="form-label" style="margin-top: 20px;">Permissions</div>
            <div class="card" style="background: #fff; box-shadow: none; border: 1px solid #e2e8f0;">
                <div class="card-body">
                    <div class="permission-grid">
                        <?php foreach ($permissions as $permission): ?>
                            <div class="form-check">
                                <input type="checkbox" name="permissions[]" value="<?= $permission['id'] ?>" id="perm_<?= $permission['id'] ?>"
                                    <?= (isset($rolePermissionIds) && in_array($permission['id'], $rolePermissionIds)) ? 'checked' : '' ?> style="margin-top: 4px;">
                                <label for="perm_<?= $permission['id'] ?>" style="cursor: pointer;">
                                    <div style="font-weight: 600; color: #2d3748;"><?= esc($permission['name']) ?></div>
                                    <div style="font-size: 12px; color: #718096;"><?= esc($permission['description']) ?></div>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div style="margin-top: 30px;">
                <a href="<?= base_url('role-management') ?>" class="btn-cancel">Cancel</a>
                <button type="submit" class="btn-submit">Save Role</button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
