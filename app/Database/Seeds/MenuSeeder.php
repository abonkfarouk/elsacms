<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run()
    {
        // Create default menus
        $menus = [
            [
                'name' => 'Primary Menu',
                'location' => 'primary',
            ],
            [
                'name' => 'Footer Menu',
                'location' => 'footer',
            ],
        ];

        foreach ($menus as $menu) {
            // Check if menu already exists
            $existing = $this->db->table('menus')->where('location', $menu['location'])->get()->getRowArray();
            
            if (!$existing) {
                $this->db->table('menus')->insert($menu);
            }
        }
    }
}
