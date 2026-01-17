<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\MenuModel;
use App\Models\MenuItemModel;
use App\Models\PageModel;
use App\Models\PostModel;
use App\Models\CategoryModel;
use CodeIgniter\HTTP\ResponseInterface;

class MenuManagement extends BaseController
{
    protected $menuModel;
    protected $menuItemModel;
    protected $session;

    public function __construct()
    {
        $this->menuModel = new MenuModel();
        $this->menuItemModel = new MenuItemModel();
        $this->session = \Config\Services::session();
    }

    /**
     * List all menus
     */
    public function index()
    {
        $menus = $this->menuModel->findAll();

        $data = [
            'title' => 'Menu Management',
            'menus' => $menus,
        ];

        return view('menu_management/index', $data);
    }

    /**
     * Edit menu items
     */
    public function edit($id)
    {
        $menu = $this->menuModel->find($id);
        
        if (!$menu) {
            return redirect()->to('/menu-management')
                ->with('error', 'Menu not found');
        }

        $items = $this->menuItemModel->getByMenuId($id);
        
        // Get available pages, posts, categories
        $pageModel = new PageModel();
        $postModel = new PostModel();
        $categoryModel = new CategoryModel();
        
        $pages = $pageModel->where('status', 'published')->findAll();
        $posts = $postModel->where('status', 'published')->findAll();
        $categories = $categoryModel->findAll();

        $data = [
            'title' => 'Edit Menu: ' . $menu['name'],
            'menu' => $menu,
            'items' => $items,
            'pages' => $pages,
            'posts' => $posts,
            'categories' => $categories,
        ];

        return view('menu_management/edit', $data);
    }

    /**
     * Add menu item
     */
    public function addItem($menuId)
    {
        $validation = \Config\Services::validation();
        
        $rules = [
            'title' => 'required|max_length[100]',
            'type' => 'required|in_list[page,post,category,custom,external]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $validation->getErrors());
        }

        $type = $this->request->getPost('type');
        $url = null;
        $typeId = null;

        if ($type === 'custom' || $type === 'external') {
            $url = $this->request->getPost('url');
        } else {
            $typeId = $this->request->getPost('type_id');
        }

        // Get max sort order
        $maxOrder = $this->menuItemModel->where('menu_id', $menuId)
            ->selectMax('sort_order')
            ->first();
        
        $sortOrder = ($maxOrder['sort_order'] ?? 0) + 1;

        $data = [
            'menu_id' => $menuId,
            'title' => $this->request->getPost('title'),
            'type' => $type,
            'type_id' => $typeId,
            'url' => $url,
            'target' => $this->request->getPost('target') ?? '_self',
            'sort_order' => (int)($maxOrder['sort_order'] ?? 0) + 1,
        ];

        if ($this->menuItemModel->insert($data)) {
            return redirect()->to('/menu-management/edit/' . $menuId)
                ->with('success', 'Menu item added successfully');
        } else {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to add menu item');
        }
    }

    /**
     * Update menu item
     */
    public function updateItem($id)
    {
        $item = $this->menuItemModel->find($id);
        
        if (!$item) {
            return redirect()->back()
                ->with('error', 'Menu item not found');
        }

        $validation = \Config\Services::validation();
        
        $rules = [
            'title' => 'required|max_length[100]',
            'sort_order' => 'required|integer',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $validation->getErrors());
        }

        $data = [
            'title' => $this->request->getPost('title'),
            'sort_order' => $this->request->getPost('sort_order'),
            'target' => $this->request->getPost('target') ?? '_self',
        ];

        if ($this->menuItemModel->update($id, $data)) {
            return redirect()->to('/menu-management/edit/' . $item['menu_id'])
                ->with('success', 'Menu item updated successfully');
        } else {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update menu item');
        }
    }

    /**
     * Delete menu item
     */
    public function deleteItem($id)
    {
        $item = $this->menuItemModel->find($id);
        
        if (!$item) {
            return redirect()->back()
                ->with('error', 'Menu item not found');
        }

        $menuId = $item['menu_id'];

        if ($this->menuItemModel->delete($id)) {
            return redirect()->to('/menu-management/edit/' . $menuId)
                ->with('success', 'Menu item deleted successfully');
        } else {
            return redirect()->back()
                ->with('error', 'Failed to delete menu item');
        }
    }
}
