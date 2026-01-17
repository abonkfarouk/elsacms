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
    .form-text { font-size: 13px; color: #718096; margin-top: -15px; margin-bottom: 20px; }
</style>

<div class="page-header">
    <h2><?= isset($permission) ? 'Edit Permission' : 'Create New Permission' ?></h2>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title">Permission Details</div>
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

        <form action="<?= isset($permission) ? base_url('permission-management/update/' . $permission['id']) : base_url('permission-management/create') ?>" method="post">
            <?= csrf_field() ?>
            
            <label for="name" class="form-label">Permission Name (Key)</label>
            <input type="text" class="form-control" id="name" name="name" value="<?= old('name', $permission['name'] ?? '') ?>" placeholder="e.g. manage_users" required>
            <div class="form-text">Unique key used in code to check permission.</div>
            
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3"><?= old('description', $permission['description'] ?? '') ?></textarea>

            <div style="margin-top: 30px;">
                <a href="<?= base_url('permission-management') ?>" class="btn-cancel">Cancel</a>
                <button type="submit" class="btn-submit">Save Permission</button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
