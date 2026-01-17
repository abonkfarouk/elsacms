<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PostModel;
use App\Models\CategoryModel;
use App\Models\PostImageModel;
use CodeIgniter\HTTP\ResponseInterface;

class PostManagement extends BaseController
{
    protected $postModel;
    protected $categoryModel;
    protected $imageModel;
    protected $session;

    public function __construct()
    {
        $this->postModel = new PostModel();
        $this->categoryModel = new CategoryModel();
        $this->imageModel = new PostImageModel();
        $this->session = \Config\Services::session();
        helper(['slug', 'upload']);
    }

    /**
     * List all posts
     */
    public function index()
    {
        $posts = $this->postModel->getAllWithRelations();

        $data = [
            'title' => 'Post Management',
            'posts' => $posts,
        ];

        return view('post_management/index', $data);
    }

    /**
     * Show create post form
     */
    public function create()
    {
        $categories = $this->categoryModel->findAll();

        $data = [
            'title' => 'Create Post',
            'categories' => $categories,
        ];

        return view('post_management/create', $data);
    }

    /**
     * Store new post
     */
    public function store()
    {
        $validation = \Config\Services::validation();
        
        $rules = [
            'title' => 'required|min_length[3]|max_length[255]',
            'slug' => 'permit_empty|max_length[255]',
            'content' => 'required',
            'status' => 'required|in_list[draft,published]',
            'category_id' => 'permit_empty|integer',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $validation->getErrors());
        }

        $data = [
            'title' => $this->request->getPost('title'),
            'slug' => $this->request->getPost('slug') ?: '',
            'excerpt' => $this->request->getPost('excerpt') ?? '',
            'content' => $this->request->getPost('content'),
            'category_id' => $this->request->getPost('category_id') ?: null,
            'author_id' => $this->session->get('user_id'),
            'status' => $this->request->getPost('status'),
            'meta_title' => $this->request->getPost('meta_title') ?? '',
            'meta_description' => $this->request->getPost('meta_description') ?? '',
            'meta_keywords' => $this->request->getPost('meta_keywords') ?? '',
        ];

        // Handle featured image upload
        $featuredImage = $this->request->getFile('featured_image');
        if ($featuredImage && $featuredImage->isValid()) {
            $uploadResult = upload_image($featuredImage, 'uploads/posts');
            if ($uploadResult['success']) {
                $data['featured_image'] = $uploadResult['path'];
            }
        }

        $postId = $this->postModel->insert($data);

        if ($postId) {
            return redirect()->to('/post-management')
                ->with('success', 'Post created successfully');
        } else {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create post. Please try again.');
        }
    }

    /**
     * Show edit post form
     */
    public function edit($id)
    {
        $post = $this->postModel->find($id);
        
        if (!$post) {
            return redirect()->to('/post-management')
                ->with('error', 'Post not found');
        }

        $categories = $this->categoryModel->findAll();
        $images = $this->imageModel->getByPostId($id);

        $data = [
            'title' => 'Edit Post',
            'post' => $post,
            'categories' => $categories,
            'images' => $images,
        ];

        return view('post_management/edit', $data);
    }

    /**
     * Update post
     */
    public function update($id)
    {
        $post = $this->postModel->find($id);
        
        if (!$post) {
            return redirect()->to('/post-management')
                ->with('error', 'Post not found');
        }

        $validation = \Config\Services::validation();
        
        $rules = [
            'title' => 'required|min_length[3]|max_length[255]',
            'slug' => 'permit_empty|max_length[255]',
            'content' => 'required',
            'status' => 'required|in_list[draft,published]',
            'category_id' => 'permit_empty|integer',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $validation->getErrors());
        }

        $data = [
            'title' => $this->request->getPost('title'),
            'slug' => $this->request->getPost('slug') ?: '',
            'excerpt' => $this->request->getPost('excerpt') ?? '',
            'content' => $this->request->getPost('content'),
            'category_id' => $this->request->getPost('category_id') ?: null,
            'status' => $this->request->getPost('status'),
            'meta_title' => $this->request->getPost('meta_title') ?? '',
            'meta_description' => $this->request->getPost('meta_description') ?? '',
            'meta_keywords' => $this->request->getPost('meta_keywords') ?? '',
        ];

        // Handle featured image upload
        $featuredImage = $this->request->getFile('featured_image');
        if ($featuredImage && $featuredImage->isValid()) {
            // Delete old featured image
            if (!empty($post['featured_image'])) {
                delete_image($post['featured_image']);
            }
            
            $uploadResult = upload_image($featuredImage, 'uploads/posts');
            if ($uploadResult['success']) {
                $data['featured_image'] = $uploadResult['path'];
            }
        }

        if ($this->postModel->update($id, $data)) {
            return redirect()->to('/post-management')
                ->with('success', 'Post updated successfully');
        } else {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update post. Please try again.');
        }
    }

    /**
     * Delete post
     */
    public function delete($id)
    {
        $post = $this->postModel->find($id);
        
        if (!$post) {
            return redirect()->to('/post-management')
                ->with('error', 'Post not found');
        }

        if ($this->postModel->delete($id)) {
            return redirect()->to('/post-management')
                ->with('success', 'Post deleted successfully');
        } else {
            return redirect()->to('/post-management')
                ->with('error', 'Failed to delete post. Please try again.');
        }
    }

    /**
     * Upload additional image (AJAX)
     */
    public function uploadImage($postId)
    {
        $post = $this->postModel->find($postId);
        
        if (!$post) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Post not found'
            ]);
        }

        $image = $this->request->getFile('image');
        if (!$image || !$image->isValid()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid image file'
            ]);
        }

        $uploadResult = upload_image($image, 'uploads/posts/gallery');
        
        if ($uploadResult['success']) {
            // Get max sort order
            $maxOrder = $this->imageModel->where('post_id', $postId)
                ->selectMax('sort_order')
                ->first();
            
            $sortOrder = ($maxOrder['sort_order'] ?? 0) + 1;
            
            $imageData = [
                'post_id' => $postId,
                'image_path' => $uploadResult['path'],
                'alt_text' => $this->request->getPost('alt_text') ?? '',
                'caption' => $this->request->getPost('caption') ?? '',
                'sort_order' => $sortOrder,
            ];
            
            $imageId = $this->imageModel->insert($imageData);
            
            if ($imageId) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Image uploaded successfully',
                    'image' => array_merge($imageData, ['id' => $imageId])
                ]);
            }
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => $uploadResult['error'] ?? 'Failed to upload image'
        ]);
    }

    /**
     * Delete image (AJAX)
     */
    public function deleteImage($imageId)
    {
        $image = $this->imageModel->find($imageId);
        
        if (!$image) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Image not found'
            ]);
        }

        if ($this->imageModel->delete($imageId)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Image deleted successfully'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Failed to delete image'
        ]);
    }
}
