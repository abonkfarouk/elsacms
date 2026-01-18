<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?= site_name() ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #f0fdfa 0%, #e6f7f5 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .login-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.05);
            padding: 40px;
            width: 100%;
            max-width: 400px;
            border-top: 5px solid #0C7779;
        }
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo-img {
            max-height: 50px;
            width: auto;
            margin-bottom: 10px;
        }
        .login-header h1 {
            color: #2d3748;
            font-size: 28px;
            margin-bottom: 10px;
            font-weight: 700;
        }
        .login-header a {
            text-decoration: none;
            color: inherit;
        }
        .login-header p {
            color: #718096;
            font-size: 14px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #4a5568;
            font-weight: 500;
            font-size: 14px;
        }
        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            font-size: 14px;
            transition: all 0.3s;
        }
        .form-group input:focus {
            outline: none;
            border-color: #0C7779;
            box-shadow: 0 0 0 3px rgba(12, 119, 121, 0.1);
        }
        .btn {
            width: 100%;
            padding: 12px;
            background: #0C7779;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn:hover {
            background: #005461;
            transform: translateY(-1px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .btn:active {
            transform: translateY(0);
        }
        .alert {
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        .alert-error {
            background-color: #fff5f5;
            color: #c53030;
            border: 1px solid #feb2b2;
        }
        .alert-success {
            background-color: #f0fff4;
            color: #276749;
            border: 1px solid #9ae6b4;
        }
        .register-link {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #718096;
        }
        .register-link a {
            color: #0C7779;
            text-decoration: none;
            font-weight: 600;
        }
        .register-link a:hover {
            text-decoration: underline;
        }
        .error-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .error-list li {
            color: #c33;
            font-size: 13px;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1 style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; margin-bottom: 10px;">ElsaCMS</h1>
            <h2 style="font-size: 24px; margin-bottom: 10px;">Login</h2>
            <p>Masuk ke akun Anda</p>
        </div>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-error">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-error">
                <ul class="error-list">
                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('auth/processLogin') ?>" method="post">
            <?= csrf_field() ?>
            
            <div class="form-group">
                <label for="username">Username atau Email</label>
                <input type="text" id="username" name="username" value="<?= old('username') ?>" required autofocus>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <div style="text-align: right; margin-bottom: 20px; margin-top: -15px;">
                <a href="<?= base_url('auth/forgot') ?>" style="color: #0C7779; font-size: 13px; text-decoration: none; font-weight: 500;">Lupa Password?</a>
            </div>

            <button type="submit" class="btn">Login</button>
        </form>

        <div class="register-link">
            Belum punya akun? <a href="<?= base_url('auth/register') ?>">Daftar di sini</a>
        </div>
        <div style="text-align: center; margin-top: 20px;">
            <a href="<?= base_url('/') ?>" style="color: #667eea; text-decoration: none; font-size: 14px;">← Kembali ke Beranda</a>
        </div>
    </div>
</body>
</html>
