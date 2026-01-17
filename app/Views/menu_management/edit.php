<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<style>
    .menu-editor-page .menu-editor {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
        margin-top: 20px;
    }
    @media (max-width: 968px) {
        .menu-editor-page .menu-editor {
            grid-template-columns: 1fr;
        }
    }
    .menu-editor-page .menu-editor > .card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }
    .menu-editor-page .menu-editor > .card > .card-header {
        padding: 15px 20px;
        border-bottom: 1px solid #e2e8f0;
        background: #f7fafc;
    }
    .menu-editor-page .menu-editor > .card > .card-header .card-title {
        font-size: 16px;
        font-weight: 600;
        color: #2d3748;
    }
    .menu-editor-page .menu-editor > .card > .card-body {
        padding: 20px;
    }
    .menu-editor-page .form-group {
        margin-bottom: 15px;
    }
    .menu-editor-page .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: 500;
        color: #2d3748;
        font-size: 14px;
    }
    .menu-editor-page .form-group input, 
    .menu-editor-page .form-group select {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 14px;
    }
    .menu-editor-page .form-group input:focus, 
    .menu-editor-page .form-group select:focus {
        outline: none;
        border-color: #0C7779;
    }
    .menu-editor-page .btn {
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
    .menu-editor-page .btn-primary {
        background: #0C7779;
        color: white;
    }
    .menu-editor-page .btn-primary:hover {
        background: #005461;
    }
    .menu-editor-page .btn-sm {
        padding: 6px 12px;
        font-size: 13px;
    }
    .menu-editor-page .btn-danger {
        background: #dc3545;
        color: white;
    }
    .menu-editor-page .btn-danger:hover {
        background: #c82333;
    }
    .menu-editor-page .btn-secondary {
        background: #6c757d;
        color: white;
    }
    .menu-editor-page .btn-secondary:hover {
        background: #5a6268;
    }
    .menu-editor-page .alert {
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 20px;
    }
    .menu-editor-page .alert-success {
        background: #d4f4dd;
        color: #249E94;
        border: 1px solid #249E94;
    }
    .menu-editor-page .alert-error {
        background: #fee;
        color: #c33;
        border: 1px solid #c33;
    }
    .menu-editor-page .menu-items-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .menu-editor-page .menu-item {
        background: #f7fafc;
        border: 1px solid #e2e8f0;
        border-radius: 5px;
        padding: 15px;
        margin-bottom: 10px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .menu-editor-page .menu-item-info {
        flex: 1;
    }
    .menu-editor-page .menu-item-title {
        font-weight: 600;
        color: #2d3748;
        font-size: 14px;
    }
    .menu-editor-page .menu-item-meta {
        font-size: 12px;
        color: #718096;
        margin-top: 5px;
    }
    .menu-editor-page .menu-item-actions {
        display: flex;
        gap: 5px;
    }
    .menu-editor-page #type-specific {
        margin-top: 10px;
    }
</style>

<div class="menu-editor-page">
<div class="card">
    <div class="card-header">
        <div class="card-title">Edit Menu: <?= esc($menu['name']) ?></div>
        <a href="<?= base_url('menu-management') ?>" class="btn btn-secondary">Back to Menus</a>
    </div>
    <div class="card-body">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-error"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <div class="menu-editor">
            <!-- Add Menu Item Form -->
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Add Menu Item</div>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('menu-management/add-item/' . $menu['id']) ?>" method="post">
                        <?= csrf_field() ?>
                        
                        <div class="form-group">
                            <label for="type">Type *</label>
                            <select id="type" name="type" required onchange="updateTypeFields()">
                                <option value="">-- Select Type --</option>
                                <option value="page">Page</option>
                                <option value="post">Post</option>
                                <option value="category">Category</option>
                                <option value="custom">Custom Link</option>
                                <option value="external">External Link</option>
                            </select>
                        </div>

                        <div id="type-specific"></div>

                        <div class="form-group">
                            <label for="title">Title *</label>
                            <input type="text" id="title" name="title" required>
                        </div>

                        <div class="form-group">
                            <label for="target">Open in</label>
                            <select id="target" name="target">
                                <option value="_self">Same Window</option>
                                <option value="_blank">New Window</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Add Menu Item</button>
                    </form>
                </div>
            </div>

            <!-- Current Menu Items -->
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Current Menu Items</div>
                </div>
                <div class="card-body">
                    <?php if (!empty($items)): ?>
                        <ul class="menu-items-list">
                            <?php foreach ($items as $item): ?>
                            <li class="menu-item">
                                <div class="menu-item-info">
                                    <div class="menu-item-title"><?= esc($item['title']) ?></div>
                                    <div class="menu-item-meta">
                                        Type: <?= ucfirst($item['type']) ?> | 
                                        Order: <?= $item['sort_order'] ?> |
                                        Target: <?= $item['target'] ?>
                                    </div>
                                </div>
                                <div class="menu-item-actions">
                                    <form action="<?= base_url('menu-management/delete-item/' . $item['id']) ?>" method="post" style="display: inline;" onsubmit="return confirm('Are you sure?')">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </div>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p style="text-align: center; color: #999; padding: 40px;">No menu items yet. Add your first item!</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const pages = <?= json_encode($pages) ?>;
const posts = <?= json_encode($posts) ?>;
const categories = <?= json_encode($categories) ?>;

function updateTypeFields() {
    const type = document.getElementById('type').value;
    const container = document.getElementById('type-specific');
    const titleInput = document.getElementById('title');
    
    container.innerHTML = '';
    
    if (type === 'page') {
        container.innerHTML = `
            <div class="form-group">
                <label for="type_id">Select Page *</label>
                <select id="type_id" name="type_id" required onchange="updateTitle()">
                    <option value="">-- Select Page --</option>
                    ${pages.map(p => `<option value="${p.id}">${p.title}</option>`).join('')}
                </select>
            </div>
        `;
    } else if (type === 'post') {
        container.innerHTML = `
            <div class="form-group">
                <label for="type_id">Select Post *</label>
                <select id="type_id" name="type_id" required onchange="updateTitle()">
                    <option value="">-- Select Post --</option>
                    ${posts.map(p => `<option value="${p.id}">${p.title}</option>`).join('')}
                </select>
            </div>
        `;
    } else if (type === 'category') {
        container.innerHTML = `
            <div class="form-group">
                <label for="type_id">Select Category *</label>
                <select id="type_id" name="type_id" required onchange="updateTitle()">
                    <option value="">-- Select Category --</option>
                    ${categories.map(c => `<option value="${c.id}">${c.name}</option>`).join('')}
                </select>
            </div>
        `;
    } else if (type === 'custom' || type === 'external') {
        container.innerHTML = `
            <div class="form-group">
                <label for="url">URL *</label>
                <input type="text" id="url" name="url" placeholder="https://example.com" required>
            </div>
        `;
    }
}

function updateTitle() {
    const type = document.getElementById('type').value;
    const typeId = document.getElementById('type_id')?.value;
    const titleInput = document.getElementById('title');
    
    if (!typeId) return;
    
    let selectedItem;
    if (type === 'page') {
        selectedItem = pages.find(p => p.id == typeId);
    } else if (type === 'post') {
        selectedItem = posts.find(p => p.id == typeId);
    } else if (type === 'category') {
        selectedItem = categories.find(c => c.id == typeId);
    }
    
    if (selectedItem) {
        titleInput.value = selectedItem.title || selectedItem.name;
    }
}
</script>

</div><!-- /.menu-editor-page -->

<?= $this->endSection() ?>
