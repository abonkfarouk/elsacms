<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;
use App\Libraries\Mailer;

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

        // Generate Activation Token
        $activationToken = bin2hex(random_bytes(32));

        $data = [
            'username'  => $this->request->getPost('username'),
            'email'     => $this->request->getPost('email'),
            'password'  => $this->request->getPost('password'),
            'full_name' => $this->request->getPost('full_name') ?? '',
            'is_active' => 0, // Inactive by default
            'activation_token' => $activationToken
        ];

        if ($this->userModel->insert($data)) {
            // Send Verification Email
            $verifyLink = base_url("auth/verify/$activationToken");
            $mailer = new Mailer();
            $subject = 'Verifikasi Akun ElsaCMS';
            $message = "
                <h3>Verifikasi Akun</h3>
                <p>Halo " . esc($data['full_name']) . ",</p>
                <p>Terima kasih telah mendaftar. Silakan klik link di bawah ini untuk mengaktifkan akun Anda:</p>
                <p><a href='$verifyLink' style='background:#0C7779;color:white;padding:10px 20px;text-decoration:none;border-radius:5px;'>Verifikasi Email</a></p>
                <p>Atau copy link ini: <br> $verifyLink</p>
            ";

            if ($mailer->send($data['email'], $subject, $message)) {
                return redirect()->to('/auth/login')
                    ->with('success', 'Registrasi berhasil! Silakan cek email untuk verifikasi akun Anda.');
            } else {
                return redirect()->to('/auth/login')
                    ->with('warning', 'Registrasi berhasil, namun gagal mengirim email verifikasi. Silakan hubungi admin.');
            }
        } else {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal melakukan registrasi. Silakan coba lagi.');
        }
    }

    /**
     * Verify Email
     */
    public function verifyEmail($token = null)
    {
        if (!$token) {
            return redirect()->to('/auth/login')->with('error', 'Token tidak valid.');
        }

        $user = $this->userModel->where('activation_token', $token)->first();

        if (!$user) {
            return redirect()->to('/auth/login')->with('error', 'Token verifikasi tidak valid atau akun sudah aktif.');
        }

        $this->userModel->update($user['id'], [
            'is_active' => 1,
            'activation_token' => null
        ]);

        return redirect()->to('/auth/login')->with('success', 'Akun berhasil diverifikasi! Silakan login.');
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

        // Send Email
        $mailer = new Mailer();
        $sent = $mailer->send($email, 'Reset Password Permintaan', "
            <h3>Reset Password</h3>
            <p>Halo,</p>
            <p>Silakan klik link berikut untuk mereset password akun Anda:</p>
            <p><a href='$resetLink' style='background:#0C7779;color:white;padding:10px 20px;text-decoration:none;border-radius:5px;'>Reset Password</a></p>
            <p>Link berlaku 1 jam.</p>
        ");

        if (!$sent) {
            log_message('error', "Failed to send reset link to $email");
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
