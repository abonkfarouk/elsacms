<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AdminSettingsPermissionSeeder extends Seeder
{
    public function run()
    {
        // Get admin role ID
        $adminRole = $this->db->table('roles')->where('name', 'admin')->get()->getRowArray();
        
        if (!$adminRole) {
            echo "Admin role not found!\n";
            return;
        }
        
        // Get manage_settings permission ID
        $permission = $this->db->table('permissions')->where('name', 'manage_settings')->get()->getRowArray();
        
        if (!$permission) {
            echo "manage_settings permission not found!\n";
            return;
        }
        
        // Check if already assigned
        $existing = $this->db->table('role_permissions')
            ->where('role_id', $adminRole['id'])
            ->where('permission_id', $permission['id'])
            ->get()
            ->getRowArray();
        
        if ($existing) {
            echo "Permission already assigned to admin role.\n";
            return;
        }
        
        // Assign permission to admin role
        $this->db->table('role_permissions')->insert([
            'role_id' => $adminRole['id'],
            'permission_id' => $permission['id'],
        ]);
        
        echo "Successfully assigned manage_settings permission to admin role!\n";
    }
}
