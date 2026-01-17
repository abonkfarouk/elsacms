<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\RoleModel;
use CodeIgniter\HTTP\ResponseInterface;

class UserManagement extends BaseController
{
    protected $userModel;
    protected $roleModel;
    protected $session;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->roleModel = new RoleModel();
        $this->session = \Config\Services::session();
    }

    /**
     * List all users
     */
    public function index()
    {
        $users = $this->userModel->findAll();
        
        // Get roles for each user
        foreach ($users as &$user) {
            $user = $this->userModel->getUserWithRoles($user['id']);
        }

        $data = [
            'title' => 'User Management',
            'users' => $users,
        ];

        return view('user_management/index', $data);
    }

    /**
     * Show create user form
     */
    public function create()
    {
        $roles = $this->roleModel->findAll();

        $data = [
            'title' => 'Tambah User',
            'roles' => $roles,
        ];

        return view('user_management/create', $data);
    }

    /**
     * Store new user
     */
    public function store()
    {
        $validation = \Config\Services::validation();
        
        $rules = [
            'username'  => 'required|min_length[3]|max_length[100]|is_unique[users.username]',
            'email'     => 'required|valid_email|is_unique[users.email]',
            'password'  => 'required|min_length[6]',
            'password_confirm' => 'required|matches[password]',
            'full_name' => 'permit_empty|max_length[255]',
            'is_active' => 'permit_empty',
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
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
        ];

        $userId = $this->userModel->insert($data);

        if ($userId) {
            // Assign roles if provided
            $roles = $this->request->getPost('roles');
            if ($roles && is_array($roles)) {
                $this->assignRolesToUser($userId, $roles);
            }

            return redirect()->to('/user-management')
                ->with('success', 'User berhasil ditambahkan');
        } else {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan user. Silakan coba lagi.');
        }
    }

    /**
     * Show edit user form
     */
    public function edit($id)
    {
        $user = $this->userModel->getUserWithRoles($id);
        
        if (!$user) {
            return redirect()->to('/user-management')
                ->with('error', 'User tidak ditemukan');
        }

        $roles = $this->roleModel->findAll();
        
        // Get user's current role IDs
        $userRoleIds = [];
        if (isset($user['roles'])) {
            foreach ($user['roles'] as $role) {
                $userRoleIds[] = $role['id'];
            }
        }

        $data = [
            'title' => 'Edit User',
            'user' => $user,
            'roles' => $roles,
            'userRoleIds' => $userRoleIds,
        ];

        return view('user_management/edit', $data);
    }

    /**
     * Update user
     */
    public function update($id)
    {
        $user = $this->userModel->find($id);
        
        if (!$user) {
            return redirect()->to('/user-management')
                ->with('error', 'User tidak ditemukan');
        }

        $validation = \Config\Services::validation();
        
        $rules = [
            'username'  => "required|min_length[3]|max_length[100]|is_unique[users.username,id,{$id}]",
            'email'     => "required|valid_email|is_unique[users.email,id,{$id}]",
            'password'  => 'permit_empty|min_length[6]',
            'password_confirm' => 'permit_empty|matches[password]',
            'full_name' => 'permit_empty|max_length[255]',
            'is_active' => 'permit_empty',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $validation->getErrors());
        }

        $data = [
            'username'  => $this->request->getPost('username'),
            'email'     => $this->request->getPost('email'),
            'full_name' => $this->request->getPost('full_name') ?? '',
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
        ];

        // Update password only if provided
        if ($this->request->getPost('password')) {
            $data['password'] = $this->request->getPost('password');
        }

        if ($this->userModel->skipValidation(true)->update($id, $data)) {
            // Update roles
            $roles = $this->request->getPost('roles');
            if ($roles !== null) {
                $this->updateUserRoles($id, is_array($roles) ? $roles : []);
            }

            return redirect()->to('/user-management')
                ->with('success', 'User berhasil diupdate');
        } else {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal mengupdate user. Silakan coba lagi.');
        }
    }

    /**
     * Delete user
     */
    public function delete($id)
    {
        // Prevent deleting yourself
        if ($id == $this->session->get('user_id')) {
            return redirect()->to('/user-management')
                ->with('error', 'Anda tidak dapat menghapus akun sendiri');
        }

        $user = $this->userModel->find($id);
        
        if (!$user) {
            return redirect()->to('/user-management')
                ->with('error', 'User tidak ditemukan');
        }

        // Delete user roles first
        $db = \Config\Database::connect();
        $db->table('user_roles')->where('user_id', $id)->delete();

        if ($this->userModel->delete($id)) {
            return redirect()->to('/user-management')
                ->with('success', 'User berhasil dihapus');
        } else {
            return redirect()->to('/user-management')
                ->with('error', 'Gagal menghapus user. Silakan coba lagi.');
        }
    }

    /**
     * Assign roles to user
     */
    protected function assignRolesToUser($userId, array $roleIds)
    {
        $db = \Config\Database::connect();
        
        foreach ($roleIds as $roleId) {
            // Check if role exists
            $role = $this->roleModel->find($roleId);
            if ($role) {
                // Check if already assigned
                $exists = $db->table('user_roles')
                    ->where('user_id', $userId)
                    ->where('role_id', $roleId)
                    ->countAllResults();
                
                if (!$exists) {
                    $db->table('user_roles')->insert([
                        'user_id' => $userId,
                        'role_id' => $roleId,
                    ]);
                }
            }
        }
    }

    /**
     * Update user roles (remove old, add new)
     */
    protected function updateUserRoles($userId, array $roleIds)
    {
        $db = \Config\Database::connect();
        
        // Remove all existing roles
        $db->table('user_roles')->where('user_id', $userId)->delete();
        
        // Add new roles
        foreach ($roleIds as $roleId) {
            $role = $this->roleModel->find($roleId);
            if ($role) {
                $db->table('user_roles')->insert([
                    'user_id' => $userId,
                    'role_id' => $roleId,
                ]);
            }
        }
    }
}
