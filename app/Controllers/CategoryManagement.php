<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CategoryModel;
use CodeIgniter\HTTP\ResponseInterface;

class CategoryManagement extends BaseController
{
    protected $categoryModel;
    protected $session;

    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
        $this->session = \Config\Services::session();
    }

    /**
     * List all categories
     */
    public function index()
    {
        $categories = $this->categoryModel->getAllWithPostCount();

        $data = [
            'title' => 'Category Management',
            'categories' => $categories,
        ];

        return view('category_management/index', $data);
    }

    /**
     * Show create category form
     */
    public function create()
    {
        $data = [
            'title' => 'Add Category',
        ];

        return view('category_management/create', $data);
    }

    /**
     * Store new category
     */
    public function store()
    {
        $validation = \Config\Services::validation();
        
        $rules = [
            'name' => 'required|min_length[2]|max_length[100]',
            'slug' => 'permit_empty|max_length[100]',
            'description' => 'permit_empty',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $validation->getErrors());
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'slug' => $this->request->getPost('slug') ?: '',
            'description' => $this->request->getPost('description') ?? '',
        ];

        if ($this->categoryModel->insert($data)) {
            return redirect()->to('/category-management')
                ->with('success', 'Category created successfully');
        } else {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create category. Please try again.');
        }
    }

    /**
     * Show edit category form
     */
    public function edit($id)
    {
        $category = $this->categoryModel->find($id);
        
        if (!$category) {
            return redirect()->to('/category-management')
                ->with('error', 'Category not found');
        }

        $data = [
            'title' => 'Edit Category',
            'category' => $category,
        ];

        return view('category_management/edit', $data);
    }

    /**
     * Update category
     */
    public function update($id)
    {
        $category = $this->categoryModel->find($id);
        
        if (!$category) {
            return redirect()->to('/category-management')
                ->with('error', 'Category not found');
        }

        $validation = \Config\Services::validation();
        
        $rules = [
            'name' => 'required|min_length[2]|max_length[100]',
            'slug' => 'permit_empty|max_length[100]',
            'description' => 'permit_empty',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $validation->getErrors());
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'slug' => $this->request->getPost('slug') ?: '',
            'description' => $this->request->getPost('description') ?? '',
        ];

        if ($this->categoryModel->update($id, $data)) {
            return redirect()->to('/category-management')
                ->with('success', 'Category updated successfully');
        } else {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update category. Please try again.');
        }
    }

    /**
     * Delete category
     */
    public function delete($id)
    {
        $category = $this->categoryModel->find($id);
        
        if (!$category) {
            return redirect()->to('/category-management')
                ->with('error', 'Category not found');
        }

        // Check if category has posts
        $postModel = new \App\Models\PostModel();
        $postsCount = $postModel->where('category_id', $id)->countAllResults();
        
        if ($postsCount > 0) {
            return redirect()->to('/category-management')
                ->with('error', "Cannot delete category. It has {$postsCount} post(s) associated with it.");
        }

        if ($this->categoryModel->delete($id)) {
            return redirect()->to('/category-management')
                ->with('success', 'Category deleted successfully');
        } else {
            return redirect()->to('/category-management')
                ->with('error', 'Failed to delete category. Please try again.');
        }
    }
}
