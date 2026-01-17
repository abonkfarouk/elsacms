<?php

if (!function_exists('render_menu')) {
    /**
     * Render menu HTML by location
     * 
     * @param string $location Menu location (primary, footer)
     * @param string $class CSS class for menu container
     * @return string HTML output
     */
    function render_menu(string $location, string $class = 'nav')
    {
        $menuModel = new \App\Models\MenuModel();
        $menuItemModel = new \App\Models\MenuItemModel();
        
        $menu = $menuModel->getByLocation($location);
        
        if (!$menu) {
            return '';
        }
        
        $items = $menuItemModel->getHierarchical($menu['id']);
        
        if (empty($items)) {
            return '';
        }
        
        return render_menu_items($items, $class);
    }
}

if (!function_exists('render_menu_items')) {
    /**
     * Recursively render menu items
     * 
     * @param array $items Menu items
     * @param string $class CSS class
     * @param int $depth Current depth
     * @return string HTML output
     */
    function render_menu_items(array $items, string $class = 'nav', int $depth = 0)
    {
        if (empty($items)) {
            return '';
        }
        
        $html = $depth === 0 ? "<nav class=\"{$class}\">" : '<ul>';
        
        foreach ($items as $item) {
            $url = get_menu_item_url($item);
            $target = $item['target'] ?? '_self';
            
            $html .= '<a href="' . esc($url) . '" target="' . esc($target) . '">';
            $html .= esc($item['title']);
            $html .= '</a>';
            
            // Render children if exists
            if (!empty($item['children'])) {
                $html .= render_menu_items($item['children'], $class, $depth + 1);
            }
        }
        
        $html .= $depth === 0 ? '</nav>' : '</ul>';
        
        return $html;
    }
}

if (!function_exists('get_menu_item_url')) {
    /**
     * Get URL for menu item based on type
     * 
     * @param array $item Menu item
     * @return string URL
     */
    function get_menu_item_url(array $item)
    {
        switch ($item['type']) {
            case 'page':
                if ($item['type_id']) {
                    $pageModel = new \App\Models\PageModel();
                    $page = $pageModel->find($item['type_id']);
                    return $page ? base_url('page/' . $page['slug']) : '#';
                }
                break;
            
            case 'post':
                if ($item['type_id']) {
                    $postModel = new \App\Models\PostModel();
                    $post = $postModel->find($item['type_id']);
                    return $post ? base_url('blog/post/' . $post['slug']) : '#';
                }
                break;
            
            case 'category':
                if ($item['type_id']) {
                    $categoryModel = new \App\Models\CategoryModel();
                    $category = $categoryModel->find($item['type_id']);
                    return $category ? base_url('blog/category/' . $category['slug']) : '#';
                }
                break;
            
            case 'custom':
            case 'external':
                return $item['url'] ?? '#';
        }
        
        return $item['url'] ?? '#';
    }
}

if (!function_exists('get_menu_items')) {
    /**
     * Get menu items array by location
     * 
     * @param string $location Menu location
     * @return array Menu items
     */
    function get_menu_items(string $location)
    {
        $menuModel = new \App\Models\MenuModel();
        $menuItemModel = new \App\Models\MenuItemModel();
        
        $menu = $menuModel->getByLocation($location);
        
        if (!$menu) {
            return [];
        }
        
        return $menuItemModel->getHierarchical($menu['id']);
    }
}
