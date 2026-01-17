<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name' => 'Technology',
                'slug' => 'technology',
                'description' => 'Articles about technology, programming, and software development',
            ],
            [
                'name' => 'Business',
                'slug' => 'business',
                'description' => 'Business news, tips, and strategies',
            ],
            [
                'name' => 'Lifestyle',
                'slug' => 'lifestyle',
                'description' => 'Lifestyle, health, and wellness articles',
            ],
            [
                'name' => 'Travel',
                'slug' => 'travel',
                'description' => 'Travel guides and destination reviews',
            ],
            [
                'name' => 'Food',
                'slug' => 'food',
                'description' => 'Recipes, restaurant reviews, and culinary tips',
            ],
        ];

        $this->db->table('categories')->insertBatch($data);
    }
}
