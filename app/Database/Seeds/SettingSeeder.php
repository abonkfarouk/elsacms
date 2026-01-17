<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run()
    {
        $settings = [
            // General Settings
            [
                'key'   => 'site_name',
                'value' => 'ElsaCMS',
                'type'  => 'text',
                'group' => 'general',
            ],
            [
                'key'   => 'site_tagline',
                'value' => 'Modern Content Management System',
                'type'  => 'text',
                'group' => 'general',
            ],
            [
                'key'   => 'site_description',
                'value' => 'ElsaCMS adalah Content Management System modern dengan fitur Role-Based Access Control, SEO optimization, dan user management yang komprehensif.',
                'type'  => 'textarea',
                'group' => 'general',
            ],
            
            // Branding Settings
            [
                'key'   => 'site_logo',
                'value' => '',
                'type'  => 'image',
                'group' => 'branding',
            ],
            [
                'key'   => 'site_favicon',
                'value' => '',
                'type'  => 'image',
                'group' => 'branding',
            ],
            
            // Contact Settings
            [
                'key'   => 'contact_email',
                'value' => 'info@elsacms.com',
                'type'  => 'email',
                'group' => 'contact',
            ],
            [
                'key'   => 'contact_phone',
                'value' => '+62 123 4567 890',
                'type'  => 'text',
                'group' => 'contact',
            ],
            [
                'key'   => 'contact_address',
                'value' => 'Jakarta, Indonesia',
                'type'  => 'textarea',
                'group' => 'contact',
            ],
            
            // Social Media Settings
            [
                'key'   => 'social_facebook',
                'value' => '',
                'type'  => 'url',
                'group' => 'social',
            ],
            [
                'key'   => 'social_twitter',
                'value' => '',
                'type'  => 'url',
                'group' => 'social',
            ],
            [
                'key'   => 'social_instagram',
                'value' => '',
                'type'  => 'url',
                'group' => 'social',
            ],
            [
                'key'   => 'social_linkedin',
                'value' => '',
                'type'  => 'url',
                'group' => 'social',
            ],
        ];

        foreach ($settings as $setting) {
            $setting['created_at'] = date('Y-m-d H:i:s');
            $setting['updated_at'] = date('Y-m-d H:i:s');
            $this->db->table('settings')->insert($setting);
        }
    }
}
