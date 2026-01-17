<?php

if (!function_exists('hasPermission')) {
    /**
     * Check if current user has a specific permission
     * 
     * @param string $permissionName
     * @return bool
     */
    function hasPermission($permissionName)
    {
        $session = \Config\Services::session();
        
        if (!$session->has('user_id')) {
            return false;
        }

        $userModel = new \App\Models\UserModel();
        return $userModel->hasPermission($session->get('user_id'), $permissionName);
    }
}

if (!function_exists('hasRole')) {
    /**
     * Check if current user has a specific role
     * 
     * @param string $roleName
     * @return bool
     */
    function hasRole($roleName)
    {
        $session = \Config\Services::session();
        
        if (!$session->has('user_id')) {
            return false;
        }

        $userModel = new \App\Models\UserModel();
        return $userModel->hasRole($session->get('user_id'), $roleName);
    }
}

if (!function_exists('hasAnyRole')) {
    /**
     * Check if current user has any of the specified roles
     * 
     * @param array $roleNames
     * @return bool
     */
    function hasAnyRole(array $roleNames)
    {
        foreach ($roleNames as $roleName) {
            if (hasRole($roleName)) {
                return true;
            }
        }
        return false;
    }
}

if (!function_exists('hasAnyPermission')) {
    /**
     * Check if current user has any of the specified permissions
     * 
     * @param array $permissionNames
     * @return bool
     */
    function hasAnyPermission(array $permissionNames)
    {
        foreach ($permissionNames as $permissionName) {
            if (hasPermission($permissionName)) {
                return true;
            }
        }
        return false;
    }
}

if (!function_exists('isLoggedIn')) {
    /**
     * Check if user is logged in
     * 
     * @return bool
     */
    function isLoggedIn()
    {
        $session = \Config\Services::session();
        return $session->has('user_id') && $session->get('logged_in') === true;
    }
}

if (!function_exists('getCurrentUser')) {
    /**
     * Get current logged in user data
     * 
     * @return array|null
     */
    function getCurrentUser()
    {
        $session = \Config\Services::session();
        
        if (!$session->has('user_id')) {
            return null;
        }

        $userModel = new \App\Models\UserModel();
        return $userModel->getUserWithRoles($session->get('user_id'));
    }
}
