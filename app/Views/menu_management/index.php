<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<style>
    .menu-list {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }
    .menu-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 20px;
        transition: all 0.3s;
    }
    .menu-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        border-color: #0C7779;
    }
    .menu-name {
        font-size: 18px;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 10px;
    }
    .menu-location {
        font-size: 14px;
        color: #718096;
        margin-bottom: 15px;
    }
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
</style>

<div class="card">
    <div class="card-header">
        <div class="card-title">Menu Management</div>
    </div>
    <div class="card-body">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-error"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <div class="menu-list">
            <?php foreach ($menus as $menu): ?>
            <div class="menu-card">
                <div class="menu-name"><?= esc($menu['name']) ?></div>
                <div class="menu-location">Location: <code><?= esc($menu['location']) ?></code></div>
                <a href="<?= base_url('menu-management/edit/' . $menu['id']) ?>" class="btn btn-primary">Edit Menu Items</a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
