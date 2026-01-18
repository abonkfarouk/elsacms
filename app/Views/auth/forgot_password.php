<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - <?= site_name() ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif; background: linear-gradient(135deg, #f0fdfa 0%, #e6f7f5 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; }
        .login-container { background: white; border-radius: 10px; box-shadow: 0 10px 40px rgba(0, 0, 0, 0.05); padding: 40px; width: 100%; max-width: 400px; border-top: 5px solid #0C7779; }
        .login-header { text-align: center; margin-bottom: 30px; }
        .login-header h2 { font-size: 24px; margin-bottom: 10px; color: #2d3748; }
        .login-header p { color: #718096; font-size: 14px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; color: #4a5568; font-weight: 500; font-size: 14px; }
        .form-group input { width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 14px; transition: all 0.3s; }
        .form-group input:focus { outline: none; border-color: #0C7779; box-shadow: 0 0 0 3px rgba(12, 119, 121, 0.1); }
        .btn { width: 100%; padding: 12px; background: #0C7779; color: white; border: none; border-radius: 6px; font-size: 16px; font-weight: 600; cursor: pointer; transition: all 0.2s; }
        .btn:hover { background: #005461; transform: translateY(-1px); }
        .alert { padding: 12px; border-radius: 6px; margin-bottom: 20px; font-size: 14px; }
        .alert-error { background-color: #fff5f5; color: #c53030; border: 1px solid #feb2b2; }
        .alert-success { background-color: #f0fff4; color: #276749; border: 1px solid #9ae6b4; }
        .back-link { text-align: center; margin-top: 20px; font-size: 14px; }
        .back-link a { color: #0C7779; text-decoration: none; font-weight: 600; }
        .back-link a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h2>Lupa Password</h2>
            <p>Masukkan email Anda untuk mereset password</p>
        </div>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-error"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-error">
                <ul style="list-style:none;padding:0;margin:0;">
                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('auth/sendResetLink') ?>" method="post">
            <?= csrf_field() ?>
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" value="<?= old('email') ?>" required autofocus>
            </div>
            <button type="submit" class="btn">Kirim Link Reset</button>
        </form>

        <div class="back-link">
            Ingat password? <a href="<?= base_url('auth/login') ?>">Login di sini</a>
            <br><br>
            <a href="<?= base_url('/') ?>" style="color: #718096; font-weight: 400;">← Kembali ke Beranda</a>
        </div>
    </div>
</body>
</html>
