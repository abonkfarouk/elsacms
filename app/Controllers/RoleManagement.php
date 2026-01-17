<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\RoleModel;
use App\Models\PermissionModel;

class RoleManagement extends BaseController
{
    protected $roleModel;
    protected $permissionModel;

    public function __construct()
    {
        $this->roleModel = new RoleModel();
        $this->permissionModel = new PermissionModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Role Management',
            'roles' => $this->roleModel->findAll()
        ];
        
        return view('role_management/index', $data);
    }

    public function new()
    {
        $data = [
            'title' => 'Create New Role',
            'permissions' => $this->permissionModel->findAll()
        ];
        return view('role_management/edit', $data);
    }

    public function create()
    {
        $rules = [
            'name' => 'required|min_length[2]|is_unique[roles.name]',
            'description' => 'permit_empty'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $roleData = [
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description')
        ];

        $roleId = $this->roleModel->insert($roleData);
        
        if ($roleId) {
            // Save permissions
            $permissions = $this->request->getPost('permissions') ?? [];
            $this->roleModel->setPermissions($roleId, $permissions);
            
            return redirect()->to('role-management')->with('success', 'Role created successfully');
        }

        return redirect()->back()->withInput()->with('error', 'Failed to create role');
    }

    public function edit($id)
    {
        $role = $this->roleModel->find($id);
        if (!$role) {
            return redirect()->to('role-management')->with('error', 'Role not found');
        }

        $rolePermissions = $this->roleModel->getPermissions($id);
        $rolePermissionIds = array_column($rolePermissions, 'id');

        $data = [
            'title' => 'Edit Role',
            'role' => $role,
            'permissions' => $this->permissionModel->findAll(),
            'rolePermissionIds' => $rolePermissionIds
        ];

        return view('role_management/edit', $data);
    }

    public function update($id)
    {
        $role = $this->roleModel->find($id);
        if (!$role) {
            return redirect()->to('role-management')->with('error', 'Role not found');
        }

        $rules = [
            'name' => "required|min_length[2]|is_unique[roles.name,id,{$id}]",
            'description' => 'permit_empty'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $roleData = [
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description')
        ];

        if ($this->roleModel->update($id, $roleData)) {
            // Save permissions
            $permissions = $this->request->getPost('permissions') ?? [];
            $this->roleModel->setPermissions($id, $permissions);
            
            return redirect()->to('role-management')->with('success', 'Role updated successfully');
        }

        return redirect()->back()->withInput()->with('error', 'Failed to update role');
    }

    public function delete($id)
    {
        if ($this->roleModel->delete($id)) {
            return redirect()->to('role-management')->with('success', 'Role deleted successfully');
        }
        return redirect()->to('role-management')->with('error', 'Failed to delete role');
    }
}
