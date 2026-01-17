<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;

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
}
