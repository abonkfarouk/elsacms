<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// Auth Routes
$routes->group('auth', ['filter' => 'csrf'], function($routes) {
    $routes->get('login', 'Auth::login');
    $routes->post('processLogin', 'Auth::processLogin');
    $routes->get('register', 'Auth::register');
    $routes->post('processRegister', 'Auth::processRegister');
    $routes->get('logout', 'Auth::logout');
});

// Dashboard Routes (Protected by auth filter)
$routes->group('dashboard', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Dashboard::index');
});

// User Management Routes (Protected by auth and manage_users permission)
$routes->group('user-management', ['filter' => ['auth', 'permission:manage_users']], function($routes) {
    $routes->get('/', 'UserManagement::index');
    $routes->get('create', 'UserManagement::create');
    $routes->post('store', 'UserManagement::store');
    $routes->get('edit/(:num)', 'UserManagement::edit/$1');
    $routes->post('update/(:num)', 'UserManagement::update/$1');
    $routes->post('delete/(:num)', 'UserManagement::delete/$1');
});

// Category Management Routes (Protected by auth and manage_content permission)
$routes->group('category-management', ['filter' => ['auth', 'permission:manage_content']], function($routes) {
    $routes->get('/', 'CategoryManagement::index');
    $routes->get('create', 'CategoryManagement::create');
    $routes->post('store', 'CategoryManagement::store');
    $routes->get('edit/(:num)', 'CategoryManagement::edit/$1');
    $routes->post('update/(:num)', 'CategoryManagement::update/$1');
    $routes->post('delete/(:num)', 'CategoryManagement::delete/$1');
});

// Post Management Routes (Protected by auth and manage_content permission)
$routes->group('post-management', ['filter' => ['auth', 'permission:manage_content']], function($routes) {
    $routes->get('/', 'PostManagement::index');
    $routes->get('create', 'PostManagement::create');
    $routes->post('store', 'PostManagement::store');
    $routes->get('edit/(:num)', 'PostManagement::edit/$1');
    $routes->post('update/(:num)', 'PostManagement::update/$1');
    $routes->post('delete/(:num)', 'PostManagement::delete/$1');
    $routes->post('upload-image/(:num)', 'PostManagement::uploadImage/$1');
    $routes->post('delete-image/(:num)', 'PostManagement::deleteImage/$1');
});

// Public Blog Routes
$routes->get('blog', 'Blog::index');
$routes->get('blog/post/(:segment)', 'Blog::post/$1');
$routes->get('blog/category/(:segment)', 'Blog::category/$1');

// Settings Routes (Protected by auth and manage_settings permission)
$routes->group('settings', ['filter' => ['auth', 'permission:manage_settings']], function($routes) {
    $routes->get('/', 'Settings::index');
    $routes->post('update', 'Settings::update');
    $routes->post('upload-logo', 'Settings::uploadLogo');
    $routes->post('upload-favicon', 'Settings::uploadFavicon');
});

// Page Management Routes (Protected by auth and manage_content permission)
$routes->group('page-management', ['filter' => ['auth', 'permission:manage_content']], function($routes) {
    $routes->get('/', 'PageManagement::index');
    $routes->get('create', 'PageManagement::create');
    $routes->post('store', 'PageManagement::store');
    $routes->get('edit/(:num)', 'PageManagement::edit/$1');
    $routes->post('update/(:num)', 'PageManagement::update/$1');
    $routes->post('delete/(:num)', 'PageManagement::delete/$1');
});

// Public Page Viewing
$routes->get('page/(:segment)', 'Page::view/$1');

// Menu Management Routes (Protected by auth and manage_settings permission)
$routes->group('menu-management', ['filter' => ['auth', 'permission:manage_settings']], function($routes) {
    $routes->get('/', 'MenuManagement::index');
    $routes->get('edit/(:num)', 'MenuManagement::edit/$1');
    $routes->post('add-item/(:num)', 'MenuManagement::addItem/$1');
    $routes->post('update-item/(:num)', 'MenuManagement::updateItem/$1');
    $routes->post('delete-item/(:num)', 'MenuManagement::deleteItem/$1');
});

// Role Management Routes (Protected by auth and manage_users permission)
$routes->group('role-management', ['filter' => ['auth', 'permission:manage_users']], function($routes) {
    $routes->get('/', 'RoleManagement::index');
    $routes->get('new', 'RoleManagement::new');
    $routes->post('create', 'RoleManagement::create');
    $routes->get('edit/(:num)', 'RoleManagement::edit/$1');
    $routes->post('update/(:num)', 'RoleManagement::update/$1');
    $routes->get('delete/(:num)', 'RoleManagement::delete/$1');
});

// Permission Management Routes (Protected by auth and manage_users permission)
$routes->group('permission-management', ['filter' => ['auth', 'permission:manage_users']], function($routes) {
    $routes->get('/', 'PermissionManagement::index');
    $routes->get('new', 'PermissionManagement::new');
    $routes->post('create', 'PermissionManagement::create');
    $routes->get('edit/(:num)', 'PermissionManagement::edit/$1');
    $routes->post('update/(:num)', 'PermissionManagement::update/$1');
    $routes->get('delete/(:num)', 'PermissionManagement::delete/$1');
});
