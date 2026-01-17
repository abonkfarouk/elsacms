<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

<style>
    .tabs {
        display: flex;
        gap: 10px;
        margin-bottom: 30px;
        border-bottom: 2px solid #e2e8f0;
    }
    .tab {
        padding: 12px 24px;
        background: transparent;
        border: none;
        cursor: pointer;
        font-weight: 500;
        color: #718096;
        transition: all 0.3s;
        border-bottom: 3px solid transparent;
        margin-bottom: -2px;
    }
    .tab.active {
        color: #0C7779;
        border-bottom-color: #0C7779;
    }
    .tab-content {
        display: none;
    }
    .tab-content.active {
        display: block;
    }
    .form-group {
        margin-bottom: 25px;
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
    .form-group input[type="url"],
    .form-group textarea {
        width: 100%;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 14px;
    }
    .form-group textarea {
        min-height: 100px;
        resize: vertical;
    }
    .form-note {
        font-size: 12px;
        color: #7f8c8d;
        margin-top: 5px;
    }
    .btn {
        padding: 12px 30px;
        border-radius: 5px;
        border: none;
        cursor: pointer;
        font-weight: 500;
        transition: all 0.3s;
    }
    .btn-primary {
        background: #0C7779;
        color: white;
    }
    .btn-primary:hover {
        background: #005461;
    }
    .image-upload-section {
        border: 2px dashed #ddd;
        border-radius: 8px;
        padding: 20px;
        text-align: center;
    }
    .current-image {
        max-width: 200px;
        margin: 15px 0;
        border-radius: 5px;
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
        <div class="card-title">Site Settings</div>
    </div>
    <div class="card-body">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-error"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <!-- Tabs -->
        <div class="tabs">
            <button class="tab active" onclick="switchTab('general')">General</button>
            <button class="tab" onclick="switchTab('branding')">Branding</button>
            <button class="tab" onclick="switchTab('homepage')">Homepage</button>
            <button class="tab" onclick="switchTab('contact')">Contact</button>
            <button class="tab" onclick="switchTab('social')">Social Media</button>
        </div>

        <form action="<?= base_url('settings/update') ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <!-- Homepage Tab -->
            <div id="homepage-tab" class="tab-content">
                <div class="form-group">
                    <label for="hero_title">Hero Title</label>
                    <input type="text" id="hero_title" name="hero_title" value="<?= esc($settings['homepage']['hero_title']['value'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="hero_description">Hero Description</label>
                    <textarea id="hero_description" name="hero_description"><?= esc($settings['homepage']['hero_description']['value'] ?? '') ?></textarea>
                </div>
                <div class="form-group">
                    <label for="hero_bg_image">Hero Background Image</label>
                    <?php if (!empty($settings['homepage']['hero_bg_image']['value'])): ?>
                        <div style="margin-bottom: 10px;">
                            <img src="<?= base_url($settings['homepage']['hero_bg_image']['value']) ?>" alt="Hero Background" style="max-height: 150px; border-radius: 4px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                            <div><small class="text-muted">Current background image</small></div>
                        </div>
                    <?php endif; ?>
                    <label class="file-upload">
                        <span class="file-cta">
                        <span class="file-icon">🖼️</span>
                        <span class="file-label">Choose File...</span>
                    </span>
                    <span class="file-name" id="hero-file-name">No file selected</span>
                </label>
                <input type="file" id="hero_bg_image" name="hero_bg_image" accept="image/*" class="file-input" onchange="document.getElementById('hero-file-name').textContent = this.files[0].name">
            </div>
            <small class="text-muted">Recommended size: 1920x1080px. Server Limit: <strong><?= ini_get('upload_max_filesize') ?></strong>.</small>
        </div>
                <div class="form-group">
                    <label for="hero_btn_text">Hero Button Text</label>
                    <input type="text" id="hero_btn_text" name="hero_btn_text" value="<?= esc($settings['homepage']['hero_btn_text']['value'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="hero_btn_url">Hero Button URL</label>
                    <input type="text" id="hero_btn_url" name="hero_btn_url" value="<?= esc($settings['homepage']['hero_btn_url']['value'] ?? '') ?>" placeholder="e.g. auth/register">
                </div>
            </div>

            <!-- General Tab -->
            <div id="general-tab" class="tab-content active">
                <?php if (isset($settings['general'])): ?>
                    <?php foreach ($settings['general'] as $setting): ?>
                        <div class="form-group">
                            <label for="<?= $setting['key'] ?>"><?= ucwords(str_replace('_', ' ', str_replace('site_', '', $setting['key']))) ?></label>
                            <?php if ($setting['type'] === 'textarea'): ?>
                                <textarea id="<?= $setting['key'] ?>" name="<?= $setting['key'] ?>"><?= esc($setting['value']) ?></textarea>
                            <?php else: ?>
                                <input type="<?= $setting['type'] ?>" id="<?= $setting['key'] ?>" name="<?= $setting['key'] ?>" value="<?= esc($setting['value']) ?>">
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Branding Tab -->
            <div id="branding-tab" class="tab-content">
                <div class="form-group">
                    <label>Logo Display Mode</label>
                    <div style="margin-top: 5px;">
                        <label style="margin-right: 15px;">
                            <input type="radio" name="site_logo_type" value="text" <?= (!isset($settings['branding']['site_logo_type']) || $settings['branding']['site_logo_type']['value'] === 'text') ? 'checked' : '' ?>> 
                            Text Only
                        </label>
                        <label>
                            <input type="radio" name="site_logo_type" value="image" <?= (isset($settings['branding']['site_logo_type']) && $settings['branding']['site_logo_type']['value'] === 'image') ? 'checked' : '' ?>> 
                            Image Logo
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label>Logo Image</label>
                    <div class="image-upload-section">
                        <?php if (!empty($settings['branding']['site_logo']['value'])): ?>
                            <img src="<?= base_url($settings['branding']['site_logo']['value']) ?>" alt="Current Logo" class="current-image">
                        <?php endif; ?>
                        <div style="margin-top: 10px;">
                            <input type="file" name="logo" accept="image/*">
                        </div>
                        <div class="form-note">Recommended: 500x500px, Max 2MB. Leave empty to keep current.</div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Favicon</label>
                    <div class="image-upload-section">
                        <?php if (!empty($settings['branding']['site_favicon']['value'])): ?>
                            <img src="<?= base_url($settings['branding']['site_favicon']['value']) ?>" alt="Current Favicon" class="current-image">
                        <?php endif; ?>
                        <div style="margin-top: 10px;">
                            <input type="file" name="favicon" accept="image/*">
                        </div>
                        <div class="form-note">Recommended: 64x64px, Max 512KB. Leave empty to keep current.</div>
                    </div>
                </div>
            </div>

            <!-- Contact Tab -->
            <div id="contact-tab" class="tab-content">
                <?php if (isset($settings['contact'])): ?>
                    <?php foreach ($settings['contact'] as $setting): ?>
                        <div class="form-group">
                            <label for="<?= $setting['key'] ?>"><?= ucwords(str_replace('_', ' ', str_replace('contact_', '', $setting['key']))) ?></label>
                            <?php if ($setting['type'] === 'textarea'): ?>
                                <textarea id="<?= $setting['key'] ?>" name="<?= $setting['key'] ?>"><?= esc($setting['value']) ?></textarea>
                            <?php else: ?>
                                <input type="<?= $setting['type'] ?>" id="<?= $setting['key'] ?>" name="<?= $setting['key'] ?>" value="<?= esc($setting['value']) ?>">
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Social Media Tab -->
            <div id="social-tab" class="tab-content">
                <?php if (isset($settings['social'])): ?>
                    <?php foreach ($settings['social'] as $setting): ?>
                        <div class="form-group">
                            <label for="<?= $setting['key'] ?>"><?= ucwords(str_replace('_', ' ', str_replace('social_', '', $setting['key']))) ?> URL</label>
                            <input type="url" id="<?= $setting['key'] ?>" name="<?= $setting['key'] ?>" value="<?= esc($setting['value']) ?>" placeholder="https://...">
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div style="margin-top: 30px;">
                <button type="submit" class="btn btn-primary">Save Settings</button>
            </div>
        </form>
    </div>
</div>

<script>
function switchTab(tabName) {
    // Hide all tabs
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.remove('active');
    });
    document.querySelectorAll('.tab').forEach(tab => {
        tab.classList.remove('active');
    });
    
    // Show selected tab
    document.getElementById(tabName + '-tab').classList.add('active');
    event.target.classList.add('active');
}
</script>

<?= $this->endSection() ?>
