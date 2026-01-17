<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Quill Editor CSS -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

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
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 14px;
        transition: border-color 0.3s;
    }
    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #667eea;
    }
    .form-group textarea {
        min-height: 200px;
        resize: vertical;
        font-family: inherit;
    }
    .form-actions {
        display: flex;
        gap: 10px;
        margin-top: 30px;
    }
    .form-note {
        font-size: 12px;
        color: #7f8c8d;
        margin-top: 5px;
    }
    .section-title {
        font-size: 18px;
        font-weight: 600;
        color: #2c3e50;
        margin: 30px 0 20px 0;
        padding-bottom: 10px;
        border-bottom: 2px solid #f0f0f0;
    }
    .section-title:first-child {
        margin-top: 0;
    }
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }
    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }
    }
    .file-input-wrapper {
        position: relative;
    }
    .file-input-wrapper input[type="file"] {
        width: 100%;
        padding: 12px;
        border: 2px dashed #ddd;
        border-radius: 5px;
        cursor: pointer;
    }
    .file-input-wrapper input[type="file"]:hover {
        border-color: #667eea;
    }
</style>

<div class="card">
    <div class="card-header">
        <div class="card-title">Create New Post</div>
    </div>
    <div class="card-body">
        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-error">
                <ul style="list-style: none; padding: 0; margin: 0;">
                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('post-management/store') ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>
            
            <div class="section-title">Basic Information</div>
            
            <div class="form-group">
                <label for="title">Title *</label>
                <input type="text" id="title" name="title" value="<?= old('title') ?>" required autofocus>
            </div>

            <div class="form-group">
                <label for="slug">Slug</label>
                <input type="text" id="slug" name="slug" value="<?= old('slug') ?>">
                <div class="form-note">Leave empty to auto-generate from title. Use lowercase letters, numbers, and hyphens only.</div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="category_id">Category</label>
                    <select id="category_id" name="category_id">
                        <option value="">-- Select Category --</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category['id'] ?>" <?= old('category_id') == $category['id'] ? 'selected' : '' ?>>
                                <?= esc($category['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="status">Status *</label>
                    <select id="status" name="status" required>
                        <option value="draft" <?= old('status') == 'draft' ? 'selected' : '' ?>>Draft</option>
                        <option value="published" <?= old('status') == 'published' ? 'selected' : '' ?>>Published</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="excerpt">Excerpt</label>
                <textarea id="excerpt" name="excerpt" style="min-height: 100px;"><?= old('excerpt') ?></textarea>
                <div class="form-note">Short summary of the post (optional)</div>
            </div>

            <div class="form-group">
                <label for="content">Content *</label>
                <div id="quill-editor" style="min-height: 300px; background: white;"></div>
                <textarea id="content" name="content" style="display: none;"><?= old('content') ?></textarea>
            </div>

            <div class="section-title">Featured Image</div>

            <div class="form-group">
                <label for="featured_image">Featured Image</label>
                <div class="file-input-wrapper">
                    <input type="file" id="featured_image" name="featured_image" accept="image/*">
                </div>
                <div class="form-note">Recommended size: 1200x630px. Max file size: 2MB. Formats: JPG, PNG, GIF, WebP</div>
            </div>

            <div class="section-title">SEO Settings</div>

            <div class="form-group">
                <label for="meta_title">Meta Title</label>
                <input type="text" id="meta_title" name="meta_title" value="<?= old('meta_title') ?>" maxlength="60">
                <div class="form-note">Recommended: 50-60 characters. Leave empty to use post title.</div>
            </div>

            <div class="form-group">
                <label for="meta_description">Meta Description</label>
                <textarea id="meta_description" name="meta_description" style="min-height: 80px;" maxlength="160"><?= old('meta_description') ?></textarea>
                <div class="form-note">Recommended: 150-160 characters. Brief description for search engines.</div>
            </div>

            <div class="form-group">
                <label for="meta_keywords">Meta Keywords</label>
                <input type="text" id="meta_keywords" name="meta_keywords" value="<?= old('meta_keywords') ?>">
                <div class="form-note">Comma-separated keywords (e.g., technology, programming, web development)</div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Create Post</button>
                <a href="<?= base_url('post-management') ?>" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<!-- Quill Editor JS -->
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script>
    // Initialize Quill editor
    var quill = new Quill('#quill-editor', {
        theme: 'snow',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                [{ 'color': [] }, { 'background': [] }],
                [{ 'align': [] }],
                ['link', 'image'],
                ['clean']
            ]
        },
        placeholder: 'Write your post content here...'
    });

    // Sync Quill content to hidden textarea on form submit
    var form = document.querySelector('form');
    form.onsubmit = function() {
        var content = document.querySelector('#content');
        content.value = quill.root.innerHTML;
    };

    // Load old content if exists
    var oldContent = document.querySelector('#content').value;
    if (oldContent) {
        quill.root.innerHTML = oldContent;
    }
</script>

<?= $this->endSection() ?>
