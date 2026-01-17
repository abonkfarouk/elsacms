<?php

/**
 * Setting Helper
 * 
 * Provides global functions to access site settings
 */

if (!function_exists('get_setting')) {
    /**
     * Get a setting value by key
     * 
     * @param string $key Setting key
     * @param mixed $default Default value if setting not found
     * @return mixed
     */
    function get_setting(string $key, $default = null)
    {
        static $settings = null;
        
        // Load settings once and cache
        if ($settings === null) {
            $settingModel = new \App\Models\SettingModel();
            $settings = $settingModel->getAllSettings();
        }
        
        return $settings[$key] ?? $default;
    }
}

if (!function_exists('site_name')) {
    /**
     * Get site name
     * 
     * @return string
     */
    function site_name(): string
    {
        return get_setting('site_name', 'ElsaCMS');
    }
}

if (!function_exists('site_tagline')) {
    /**
     * Get site tagline
     * 
     * @return string
     */
    function site_tagline(): string
    {
        return get_setting('site_tagline', 'Modern Content Management System');
    }
}

if (!function_exists('site_description')) {
    /**
     * Get site description
     * 
     * @return string
     */
    function site_description(): string
    {
        return get_setting('site_description', '');
    }
}

if (!function_exists('site_logo')) {
    /**
     * Get site logo URL
     * 
     * @return string
     */
    function site_logo(): string
    {
        $logo = get_setting('site_logo', '');
        return $logo ? base_url($logo) : '';
    }
}

if (!function_exists('site_logo_type')) {
    /**
     * Get site logo type (text/image)
     * 
     * @return string
     */
    function site_logo_type(): string
    {
        return get_setting('site_logo_type', 'text');
    }
}

if (!function_exists('site_favicon')) {
    /**
     * Get site favicon URL
     * 
     * @return string
     */
    function site_favicon(): string
    {
        $favicon = get_setting('site_favicon', '');
        return $favicon ? base_url($favicon) : '';
    }
}

if (!function_exists('site_hero_bg')) {
    function site_hero_bg(): string
    {
        $bg = get_setting('hero_bg_image', '');
        return $bg ? base_url($bg) : '';
    }
}

if (!function_exists('site_hero_title')) {
    function site_hero_title(): string
    {
        return get_setting('hero_title', 'Manage Your Content With Ease');
    }
}

if (!function_exists('site_hero_desc')) {
    function site_hero_desc(): string
    {
        return get_setting('hero_description', 'ElsaCMS provides a powerful platform to create, manage, and publish your content seamlessly.');
    }
}

if (!function_exists('site_hero_btn_text')) {
    function site_hero_btn_text(): string
    {
        return get_setting('hero_btn_text', 'Get Started');
    }
}

if (!function_exists('site_hero_btn_url')) {
    function site_hero_btn_url(): string
    {
        return get_setting('hero_btn_url', 'auth/register');
    }
}

if (!function_exists('contact_email')) {
    /**
     * Get contact email
     * 
     * @return string
     */
    function contact_email(): string
    {
        return get_setting('contact_email', '');
    }
}

if (!function_exists('contact_phone')) {
    /**
     * Get contact phone
     * 
     * @return string
     */
    function contact_phone(): string
    {
        return get_setting('contact_phone', '');
    }
}

if (!function_exists('contact_address')) {
    /**
     * Get contact address
     * 
     * @return string
     */
    function contact_address(): string
    {
        return get_setting('contact_address', '');
    }
}
