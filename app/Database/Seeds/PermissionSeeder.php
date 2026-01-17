<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name' => 'view_dashboard',
                'description' => 'Melihat dashboard',
            ],
            [
                'name' => 'manage_users',
                'description' => 'Mengelola pengguna',
            ],
            [
                'name' => 'manage_roles',
                'description' => 'Mengelola roles',
            ],
            [
                'name' => 'manage_permissions',
                'description' => 'Mengelola permissions',
            ],
            [
                'name' => 'manage_content',
                'description' => 'Mengelola konten',
            ],
            [
                'name' => 'edit_content',
                'description' => 'Mengedit konten',
            ],
            [
                'name' => 'delete_content',
                'description' => 'Menghapus konten',
            ],
            [
                'name' => 'manage_settings',
                'description' => 'Mengelola pengaturan situs',
            ],
        ];

        $this->db->table('permissions')->insertBatch($data);
    }
}
