<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class PermissionFilter implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return RequestInterface|ResponseInterface|string|void
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = \Config\Services::session();
        
        // First check if user is logged in
        if (!$session->has('user_id') || !$session->get('logged_in')) {
            return redirect()->to('/auth/login')
                ->with('error', 'Silakan login terlebih dahulu');
        }

        // Check if permission arguments are provided
        if (empty($arguments)) {
            return;
        }

        $userId = $session->get('user_id');
        $userModel = new \App\Models\UserModel();

        // Check each required permission
        foreach ($arguments as $permission) {
            if (!$userModel->hasPermission($userId, $permission)) {
                return redirect()->to('/dashboard')
                    ->with('error', 'Anda tidak memiliki izin untuk mengakses halaman ini');
            }
        }
    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return ResponseInterface|void
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
