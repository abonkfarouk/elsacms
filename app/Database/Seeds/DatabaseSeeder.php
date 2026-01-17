<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Seed Roles
        $this->call('RoleSeeder');
        
        // Seed Permissions
        $this->call('PermissionSeeder');
        
        // Seed Categories
        $this->call('CategorySeeder');
        
        // Create Admin User
        $userModel = new \App\Models\UserModel();
        $adminData = [
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => 'admin123', // Will be hashed automatically
            'full_name' => 'Administrator',
            'is_active' => 1,
        ];
        
        $userId = $userModel->insert($adminData);
        
        // Assign admin role to admin user
        $db = \Config\Database::connect();
        $role = $db->table('roles')->where('name', 'admin')->get()->getRowArray();
        
        if ($role && $userId) {
            $db->table('user_roles')->insert([
                'user_id' => $userId,
                'role_id' => $role['id'],
            ]);
            
            // Assign all permissions to admin role
            $permissions = $db->table('permissions')->get()->getResultArray();
            foreach ($permissions as $permission) {
                $db->table('role_permissions')->insert([
                    'role_id' => $role['id'],
                    'permission_id' => $permission['id'],
                ]);
            }
        }
        
        echo "Database seeded successfully!\n";
        echo "Admin user created:\n";
        echo "Username: admin\n";
        echo "Password: admin123\n";
    }
}
