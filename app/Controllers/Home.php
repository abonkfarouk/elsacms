<?php

namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;

class Home extends BaseController
{
    public function index()
    {
        // Fetch latest posts for homepage
        $postModel = new \App\Models\PostModel();
        $latestPosts = $postModel->getPublished(6); // Get 6 latest posts
        
        $data = [
            'latestPosts' => $latestPosts,
        ];
        
        return view('home/index', $data);
    }
}
