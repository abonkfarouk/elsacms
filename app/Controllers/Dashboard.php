<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\PostModel;
use App\Models\CategoryModel;
use CodeIgniter\HTTP\ResponseInterface;

class Dashboard extends BaseController
{
    public function index()
    {
        helper(['rbac', 'setting']);
        
        $userModel = new UserModel();
        $postModel = new PostModel();
        $categoryModel = new CategoryModel();
        
        // Get statistics
        $stats = [
            'total_users' => $userModel->countAll(),
            'active_users' => $userModel->where('is_active', 1)->countAllResults(false),
            'total_posts' => $postModel->countAll(),
            'published_posts' => $postModel->where('status', 'published')->countAllResults(false),
            'draft_posts' => $postModel->where('status', 'draft')->countAllResults(false),
            'total_categories' => $categoryModel->countAll(),
        ];
        
        // Get recent posts
        $recentPosts = $postModel->orderBy('created_at', 'DESC')->limit(5)->find();
        
        // Get current user info
        $currentUser = $userModel->find(session()->get('user_id'));
        
        $data = [
            'title' => 'Dashboard',
            'stats' => $stats,
            'recentPosts' => $recentPosts,
            'currentUser' => $currentUser,
        ];
        
        return view('dashboard/index', $data);
    }
}
