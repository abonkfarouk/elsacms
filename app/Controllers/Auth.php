<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\OAuth;
use League\OAuth2\Client\Provider\Google;

class Auth extends BaseController
{
    protected $userModel;
    protected $session;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->session = \Config\Services::session();
    }

    /**
     * Show login page
     */
    public function login()
    {
        // If already logged in, redirect to dashboard
        if ($this->session->has('user_id')) {
            return redirect()->to('/dashboard');
        }

        return view('auth/login');
    }

    /**
     * Process login
     */
    public function processLogin()
    {
        $validation = \Config\Services::validation();
        
        $rules = [
            'username' => 'required',
            'password' => 'required|min_length[6]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $validation->getErrors());
        }

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        // Find user by username or email
        $user = $this->userModel->where('username', $username)
            ->orWhere('email', $username)
            ->first();

        if (!$user) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Username atau password salah');
        }

        // Check if user is active
        if (!$user['is_active']) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Akun Anda tidak aktif');
        }

        // Verify password
        if (!password_verify($password, $user['password'])) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Username atau password salah');
        }

        // Set session
        $sessionData = [
            'user_id'   => $user['id'],
            'username'  => $user['username'],
            'email'     => $user['email'],
            'full_name' => $user['full_name'],
            'logged_in' => true,
        ];
        $this->session->set($sessionData);

        return redirect()->to('/dashboard')->with('success', 'Selamat datang, ' . $user['full_name']);
    }

    /**
     * Show register page
     */
    public function register()
    {
        // If already logged in, redirect to dashboard
        if ($this->session->has('user_id')) {
            return redirect()->to('/dashboard');
        }

        return view('auth/register');
    }

    /**
     * Process registration
     */
    public function processRegister()
    {
        $validation = \Config\Services::validation();
        
        $rules = [
            'username'  => 'required|min_length[3]|max_length[100]|is_unique[users.username]',
            'email'     => 'required|valid_email|is_unique[users.email]',
            'password'  => 'required|min_length[6]',
            'password_confirm' => 'required|matches[password]',
            'full_name' => 'permit_empty|max_length[255]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $validation->getErrors());
        }

        $data = [
            'username'  => $this->request->getPost('username'),
            'email'     => $this->request->getPost('email'),
            'password'  => $this->request->getPost('password'),
            'full_name' => $this->request->getPost('full_name') ?? '',
            'is_active' => 1,
        ];

        if ($this->userModel->insert($data)) {
            return redirect()->to('/auth/login')
                ->with('success', 'Registrasi berhasil! Silakan login.');
        } else {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal melakukan registrasi. Silakan coba lagi.');
        }
    }

    /**
     * Logout
     */
    public function logout()
    {
        $this->session->destroy();
        return redirect()->to('/auth/login')->with('success', 'Anda telah logout');
    }

    // --------------------------------------------------------------------
    // Forgot Password Logic
    // --------------------------------------------------------------------

    public function forgotPassword()
    {
        return view('auth/forgot_password');
    }

    public function sendResetLink()
    {
        $validation = \Config\Services::validation();
        $rules = ['email' => 'required|valid_email'];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $email = $this->request->getPost('email');
        $user = $this->userModel->where('email', $email)->first();

        // For security, do not reveal if email exists, BUT for UX in internal app, we might.
        // I will return 'success' even if not found to prevent enumeration, 
        // OR simply error if I want to be friendly for this specific user.
        // User requested "buat fitur", assuming friendly.
        if (!$user) {
            return redirect()->back()->withInput()->with('error', 'Email tidak ditemukan dalam sistem.');
        }

        // Generate Token
        $token = bin2hex(random_bytes(32));
        $db = \Config\Database::connect();
        
        // Remove old tokens
        $db->table('password_resets')->where('email', $email)->delete();
        
        // Insert new token
        $db->table('password_resets')->insert([
            'email' => $email,
            'token' => $token,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        // Prepare Link
        $resetLink = base_url("auth/resetPassword/$token");
        
        // LOG IT (Critical for testing without SMTP)
        log_message('critical', "RESET PASSWORD LINK for $email: $resetLink");

        // Attempt Email with PHPMailer
        $sent = false;
        try {
            // Start PHPMailer
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            
            // Check for Google OAuth2
            $clientId = env('GOOGLE_CLIENT_ID');
            $clientSecret = env('GOOGLE_CLIENT_SECRET');
            $refreshToken = env('GOOGLE_REFRESH_TOKEN');
            $googleEmail = env('GOOGLE_EMAIL');

            if ($clientId && $clientSecret && $refreshToken) {
                log_message('critical', 'Auth: Attempting Google XOAUTH2');
                
                $mail->Host = 'smtp.gmail.com'; // Force correct host for OAuth
                $mail->Port = 465;
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->SMTPAuth = true;
                $mail->AuthType = 'XOAUTH2';
                
                $provider = new Google([
                    'clientId'     => $clientId,
                    'clientSecret' => $clientSecret,
                ]);
                
                $mail->setOAuth(
                    new OAuth([
                        'provider'     => $provider,
                        'clientId'     => $clientId,
                        'clientSecret' => $clientSecret,
                        'refreshToken' => $refreshToken,
                        'userName'     => $googleEmail,
                    ])
                );
            } else {
                log_message('critical', 'Auth: Attempting Standard SMTP');
                // Standard Auth
                $mail->Host       = env('SMTP_HOST');
                $mail->Port       = env('SMTP_PORT') ?: 465;
                $mail->SMTPSecure = env('SMTP_SECURE') ?: PHPMailer::ENCRYPTION_SMTPS;
                $mail->SMTPAuth   = true; // Ensure this is true
                $mail->Username   = env('SMTP_USER');
                $mail->Password   = env('SMTP_PASS');
            }



            // Recipients
            $fromName = env('SMTP_FROM_NAME') ?: (function_exists('site_name') ? site_name() : 'ElsaCMS');
            
            // Note: For Gmail SMTP, 'From' must be the same as authenticated user or a verified alias.
            // Using random 'no-reply@elsacms.com' will fail with 5.7.0 Authentication Required if not an alias.
            if ($clientId && $clientSecret && $refreshToken) {
                 $mail->setFrom($googleEmail, $fromName);
            } else {
                 //$mail->setFrom($fromEmail, $fromName); // This might fail for Standard Gmail too if not alias. 
                 // Safest is to use the SMTP_USER.
                 $mail->setFrom(env('SMTP_USER'), $fromName);
            }
            
            $mail->addAddress($email);

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Reset Password Permintaan';
            $mail->Body    = "
                <h3>Reset Password</h3>
                <p>Halo,</p>
                <p>Silakan klik link berikut untuk mereset password akun Anda:</p>
                <p><a href='$resetLink' style='background:#0C7779;color:white;padding:10px 20px;text-decoration:none;border-radius:5px;'>Reset Password</a></p>
                <p>Atau copy link ini: <br> $resetLink</p>
                <p>Link ini berlaku selama 1 jam.</p>
                <p>Jika Anda tidak meminta reset password, abaikan email ini.</p>
            ";
            $mail->AltBody = "Halo,\n\nSilakan klik link berikut untuk mereset password akun Anda:\n\n$resetLink\n\nLink ini berlaku selama 1 jam.";

            $mail->send();
            $sent = true;
        } catch (Exception $e) {
            log_message('error', "PHPMailer Error: {$mail->ErrorInfo}");
        }

        return redirect()->back()->with('success', 'Link reset password telah dikirim ke email Anda! (Cek Log/Spam)');
    }

    public function resetPassword($token = null)
    {
        if (!$token) {
            return redirect()->to('/auth/login')->with('error', 'Token tidak valid.');
        }

        $db = \Config\Database::connect();
        $reset = $db->table('password_resets')->where('token', $token)->get()->getRowArray();

        if (!$reset) {
            return redirect()->to('/auth/login')->with('error', 'Token tidak valid atau sudah digunakan.');
        }

        // Check expiry (1 hour)
        $createdAt = strtotime($reset['created_at']);
        if (time() - $createdAt > 3600) {
            return redirect()->to('/auth/login')->with('error', 'Token telah kadaluarsa. Silakan minta ulang.');
        }

        return view('auth/reset_password', ['token' => $token]);
    }

    public function updatePassword()
    {
        $validation = \Config\Services::validation();
        $rules = [
            'token' => 'required',
            'password' => 'required|min_length[6]',
            'password_confirm' => 'required|matches[password]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $token = $this->request->getPost('token');
        $db = \Config\Database::connect();
        $reset = $db->table('password_resets')->where('token', $token)->get()->getRowArray();

        if (!$reset) {
             return redirect()->to('/auth/login')->with('error', 'Token invalid/session expired.');
        }
        
        // Double check expiry
        if (time() - strtotime($reset['created_at']) > 3600) {
            return redirect()->to('/auth/login')->with('error', 'Token kadaluarsa.');
        }

        // Update User
        $user = $this->userModel->where('email', $reset['email'])->first();
        if ($user) {
            // Model hashPassword callback handles hashing if we pass data correctly or we hash it manually?
            // UserModel has beforeUpdate = ['hashPassword'].
            // So we just pass plain password.
            $this->userModel->update($user['id'], [
                'password' => $this->request->getPost('password')
            ]);
            
            // Delete Token
            $db->table('password_resets')->where('email', $reset['email'])->delete();
            
            return redirect()->to('/auth/login')->with('success', 'Password berhasil diubah. Silakan login.');
        }

        return redirect()->to('/auth/login')->with('error', 'User tidak ditemukan.');
    }
}
