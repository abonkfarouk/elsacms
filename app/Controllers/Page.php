<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PageModel;
use CodeIgniter\HTTP\ResponseInterface;

class Page extends BaseController
{
    protected $pageModel;

    public function __construct()
    {
        $this->pageModel = new PageModel();
    }

    /**
     * Display page by slug
     */
    public function view($slug)
    {
        $page = $this->pageModel->getBySlug($slug);
        
        if (!$page || $page['status'] !== 'published') {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'title' => $page['meta_title'] ?: $page['title'],
            'page' => $page,
        ];

        return view('page/view', $data);
    }
}
