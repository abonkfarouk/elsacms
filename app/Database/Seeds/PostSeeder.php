<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\PostModel;
use App\Models\CategoryModel;

class PostSeeder extends Seeder
{
    public function run()
    {
        $postModel = new PostModel();
        $categoryModel = new CategoryModel();
        
        // Ensure we have categories
        $techCat = $categoryModel->where('slug', 'technology')->first();
        if (!$techCat) {
            $categoryModel->insert([
                'name' => 'Technology', 
                'slug' => 'technology',
                'description' => 'Latest tech news'
            ]);
            $techCat = $categoryModel->where('slug', 'technology')->first();
        }
        
        $posts = [
            [
                'title' => 'Getting Started with ElsaCMS',
                'slug' => 'getting-started-with-elsacms',
                'excerpt' => 'Learn how to set up and configure your new ElsaCMS installation for maximum performance and security.',
                'content' => '<p>ElsaCMS is designed to be lightweight and powerful...</p>',
                'category_id' => $techCat['id'],
                'author_id' => 1, // Assuming admin is ID 1
                'status' => 'published',
                'published_at' => date('Y-m-d H:i:s'),
                'featured_image' => '', // No image for now
            ],
            [
                'title' => 'Why Role-Based Access Control Matters',
                'slug' => 'why-rbac-matters',
                'excerpt' => 'Understanding the importance of restricting access to sensitive areas of your application.',
                'content' => '<p>Security is paramount in modern web applications...</p>',
                'category_id' => $techCat['id'],
                'author_id' => 1,
                'status' => 'published',
                'published_at' => date('Y-m-d H:i:s', strtotime('-1 day')),
                'featured_image' => '',
            ],
            [
                'title' => 'Optimizing SEO for Modern Websites',
                'slug' => 'optimizing-seo',
                'excerpt' => 'Tips and tricks to improve your search engine rankings using built-in tools.',
                'content' => '<p>SEO is not just about keywords...</p>',
                'category_id' => $techCat['id'],
                'author_id' => 1,
                'status' => 'published',
                'published_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
                'featured_image' => '',
            ]
        ];

        foreach ($posts as $post) {
            if (!$postModel->where('slug', $post['slug'])->first()) {
                $postModel->insert($post);
            }
        }
    }
}
