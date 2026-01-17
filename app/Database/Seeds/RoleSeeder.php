<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name' => 'admin',
                'description' => 'Administrator dengan akses penuh',
            ],
            [
                'name' => 'editor',
                'description' => 'Editor yang dapat mengelola konten',
            ],
            [
                'name' => 'user',
                'description' => 'User biasa',
            ],
        ];

        $this->db->table('roles')->insertBatch($data);
    }
}
