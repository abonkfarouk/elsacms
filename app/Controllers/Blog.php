<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PostModel;
use App\Models\CategoryModel;
use CodeIgniter\HTTP\ResponseInterface;

class Blog extends BaseController
{
    protected $postModel;
    protected $categoryModel;

    public function __construct()
    {
        $this->postModel = new PostModel();
        $this->categoryModel = new CategoryModel();
    }

    /**
     * Blog index - list all published posts
     */
    public function index()
    {
        $posts = $this->postModel->getPublished();
        $categories = $this->categoryModel->getAllWithPostCount();
        $recentPosts = $this->postModel->getPublished(5);

        $data = [
            'title' => 'Blog',
            'posts' => $posts,
            'categories' => $categories,
            'recentPosts' => $recentPosts,
        ];

        return view('blog/index', $data);
    }

    /**
     * View single post by slug
     */
    public function post($slug)
    {
        $post = $this->postModel->getBySlug($slug);
        
        if (!$post || $post['status'] !== 'published') {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $categories = $this->categoryModel->getAllWithPostCount();
        $recentPosts = $this->postModel->getPublished(5);
        $relatedPosts = $this->postModel->getRelated($post['id'], $post['category_id'], 3);

        $data = [
            'title' => $post['title'],
            'post' => $post,
            'categories' => $categories,
            'recentPosts' => $recentPosts,
            'relatedPosts' => $relatedPosts,
            // Open Graph Metadata
            'meta_title' => $post['meta_title'] ?: $post['title'],
            'meta_description' => $post['meta_description'] ?: ($post['excerpt'] ?: substr(strip_tags($post['content']), 0, 160)),
            'meta_image' => $post['featured_image'],
            'meta_type' => 'article',
        ];

        return view('blog/post', $data);
    }

    /**
     * View posts by category
     */
    public function category($slug)
    {
        $category = $this->categoryModel->where('slug', $slug)->first();
        
        if (!$category) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $posts = $this->postModel->getByCategory($category['id']);
        $categories = $this->categoryModel->getAllWithPostCount();
        $recentPosts = $this->postModel->getPublished(5);

        $data = [
            'title' => 'Category: ' . $category['name'],
            'category' => $category,
            'posts' => $posts,
            'categories' => $categories,
            'recentPosts' => $recentPosts,
        ];

        return view('blog/category', $data);
    }
}
