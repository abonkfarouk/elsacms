<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PageModel;
use CodeIgniter\HTTP\ResponseInterface;

class PageManagement extends BaseController
{
    protected $pageModel;
    protected $session;

    public function __construct()
    {
        $this->pageModel = new PageModel();
        $this->session = \Config\Services::session();
        helper(['slug', 'upload']);
    }

    /**
     * List all pages
     */
    public function index()
    {
        $pages = $this->pageModel->orderBy('created_at', 'DESC')->findAll();

        $data = [
            'title' => 'Page Management',
            'pages' => $pages,
        ];

        return view('page_management/index', $data);
    }

    /**
     * Show create page form
     */
    public function create()
    {
        $data = [
            'title' => 'Create Page',
        ];

        return view('page_management/create', $data);
    }

    /**
     * Store new page
     */
    public function store()
    {
        $validation = \Config\Services::validation();
        
        $rules = [
            'title' => 'required|min_length[3]|max_length[255]',
            'slug' => 'permit_empty|max_length[255]',
            'content' => 'required',
            'status' => 'required|in_list[draft,published]',
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
            'status' => $this->request->getPost('status'),
            'meta_title' => $this->request->getPost('meta_title') ?? '',
            'meta_description' => $this->request->getPost('meta_description') ?? '',
            'meta_keywords' => $this->request->getPost('meta_keywords') ?? '',
            'show_in_menu' => $this->request->getPost('show_in_menu') ? true : false,
            'sort_order' => $this->request->getPost('sort_order') ?? 0,
            'template' => $this->request->getPost('template') ?? 'default',
        ];

        // Handle featured image upload
        $featuredImage = $this->request->getFile('featured_image');
        if ($featuredImage && $featuredImage->isValid()) {
            $uploadResult = upload_image($featuredImage, 'uploads/pages');
            if ($uploadResult['success']) {
                $data['featured_image'] = $uploadResult['path'];
            }
        }

        $pageId = $this->pageModel->insert($data);

        if ($pageId) {
            return redirect()->to('/page-management')
                ->with('success', 'Page created successfully');
        } else {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create page. Please try again.');
        }
    }

    /**
     * Show edit page form
     */
    public function edit($id)
    {
        $page = $this->pageModel->find($id);
        
        if (!$page) {
            return redirect()->to('/page-management')
                ->with('error', 'Page not found');
        }

        $data = [
            'title' => 'Edit Page',
            'page' => $page,
        ];

        return view('page_management/edit', $data);
    }

    /**
     * Update page
     */
    public function update($id)
    {
        $page = $this->pageModel->find($id);
        
        if (!$page) {
            return redirect()->to('/page-management')
                ->with('error', 'Page not found');
        }

        $validation = \Config\Services::validation();
        
        $rules = [
            'title' => 'required|min_length[3]|max_length[255]',
            'slug' => 'permit_empty|max_length[255]',
            'content' => 'required',
            'status' => 'required|in_list[draft,published]',
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
            'status' => $this->request->getPost('status'),
            'meta_title' => $this->request->getPost('meta_title') ?? '',
            'meta_description' => $this->request->getPost('meta_description') ?? '',
            'meta_keywords' => $this->request->getPost('meta_keywords') ?? '',
            'show_in_menu' => $this->request->getPost('show_in_menu') ? true : false,
            'sort_order' => $this->request->getPost('sort_order') ?? 0,
            'template' => $this->request->getPost('template') ?? 'default',
        ];

        // Handle featured image upload
        $featuredImage = $this->request->getFile('featured_image');
        if ($featuredImage && $featuredImage->isValid()) {
            // Delete old featured image
            if (!empty($page['featured_image'])) {
                delete_image($page['featured_image']);
            }
            
            $uploadResult = upload_image($featuredImage, 'uploads/pages');
            if ($uploadResult['success']) {
                $data['featured_image'] = $uploadResult['path'];
            }
        }

        if ($this->pageModel->update($id, $data)) {
            return redirect()->to('/page-management')
                ->with('success', 'Page updated successfully');
        } else {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update page. Please try again.');
        }
    }

    /**
     * Delete page
     */
    public function delete($id)
    {
        $page = $this->pageModel->find($id);
        
        if (!$page) {
            return redirect()->to('/page-management')
                ->with('error', 'Page not found');
        }

        // Delete featured image if exists
        if (!empty($page['featured_image'])) {
            delete_image($page['featured_image']);
        }

        if ($this->pageModel->delete($id)) {
            return redirect()->to('/page-management')
                ->with('success', 'Page deleted successfully');
        } else {
            return redirect()->to('/page-management')
                ->with('error', 'Failed to delete page. Please try again.');
        }
    }
}
