<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['username', 'email', 'password', 'full_name', 'is_active', 'activation_token'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'is_active' => 'boolean',
    ];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'username' => 'required|min_length[3]|max_length[100]|is_unique[users.username,id,{id}]',
        'email'    => 'required|valid_email|is_unique[users.email,id,{id}]',
        'password' => 'required|min_length[6]',
        'full_name' => 'permit_empty|max_length[255]',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['hashPassword'];
    protected $afterInsert    = [];
    protected $beforeUpdate   = ['hashPassword'];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Hash password before insert/update
     */
    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        }
        return $data;
    }

    /**
     * Get user with roles
     */
    public function getUserWithRoles($userId)
    {
        $user = $this->find($userId);
        if (!$user) {
            return null;
        }

        $db = \Config\Database::connect();
        $builder = $db->table('user_roles');
        $builder->select('roles.*');
        $builder->join('roles', 'roles.id = user_roles.role_id');
        $builder->where('user_roles.user_id', $userId);
        $roles = $builder->get()->getResultArray();

        $user['roles'] = $roles;
        return $user;
    }

    /**
     * Get user permissions (through roles)
     */
    public function getUserPermissions($userId)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('user_roles');
        $builder->select('permissions.*');
        $builder->join('role_permissions', 'role_permissions.role_id = user_roles.role_id');
        $builder->join('permissions', 'permissions.id = role_permissions.permission_id');
        $builder->where('user_roles.user_id', $userId);
        $builder->distinct();
        return $builder->get()->getResultArray();
    }

    /**
     * Check if user has permission
     */
    public function hasPermission($userId, $permissionName)
    {
        $permissions = $this->getUserPermissions($userId);
        foreach ($permissions as $permission) {
            if ($permission['name'] === $permissionName) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if user has role
     */
    public function hasRole($userId, $roleName)
    {
        $user = $this->getUserWithRoles($userId);
        if (!$user || !isset($user['roles'])) {
            return false;
        }

        foreach ($user['roles'] as $role) {
            if ($role['name'] === $roleName) {
                return true;
            }
        }
        return false;
    }
}
