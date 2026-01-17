<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PermissionModel;

class PermissionManagement extends BaseController
{
    protected $permissionModel;

    public function __construct()
    {
        $this->permissionModel = new PermissionModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Permission Management',
            'permissions' => $this->permissionModel->findAll()
        ];
        
        return view('permission_management/index', $data);
    }

    public function new()
    {
        $data = [
            'title' => 'Create New Permission'
        ];
        return view('permission_management/edit', $data);
    }

    public function create()
    {
        $rules = [
            'name' => 'required|min_length[2]|is_unique[permissions.name]',
            'description' => 'permit_empty'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description')
        ];

        if ($this->permissionModel->insert($data)) {
            return redirect()->to('permission-management')->with('success', 'Permission created successfully');
        }

        return redirect()->back()->withInput()->with('error', 'Failed to create permission');
    }

    public function edit($id)
    {
        $permission = $this->permissionModel->find($id);
        if (!$permission) {
            return redirect()->to('permission-management')->with('error', 'Permission not found');
        }

        $data = [
            'title' => 'Edit Permission',
            'permission' => $permission
        ];

        return view('permission_management/edit', $data);
    }

    public function update($id)
    {
        $permission = $this->permissionModel->find($id);
        if (!$permission) {
            return redirect()->to('permission-management')->with('error', 'Permission not found');
        }

        $rules = [
            'name' => "required|min_length[2]|is_unique[permissions.name,id,{$id}]",
            'description' => 'permit_empty'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description')
        ];

        if ($this->permissionModel->update($id, $data)) {
            return redirect()->to('permission-management')->with('success', 'Permission updated successfully');
        }

        return redirect()->back()->withInput()->with('error', 'Failed to update permission');
    }

    public function delete($id)
    {
        if ($this->permissionModel->delete($id)) {
            return redirect()->to('permission-management')->with('success', 'Permission deleted successfully');
        }
        return redirect()->to('permission-management')->with('error', 'Failed to delete permission');
    }
}
