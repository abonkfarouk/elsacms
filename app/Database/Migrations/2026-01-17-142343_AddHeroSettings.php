<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddHeroSettings extends Migration
{
    public function up()
    {
        $data = [
            [
                'key' => 'hero_title',
                'value' => 'Manage Your Content With Ease',
                'type' => 'string',
                'group' => 'homepage',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'key' => 'hero_description',
                'value' => 'ElsaCMS provides a powerful platform to create, manage, and publish your content seamlessly.',
                'type' => 'textarea',
                'group' => 'homepage',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'key' => 'hero_bg_image',
                'value' => '',
                'type' => 'string',
                'group' => 'homepage',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'key' => 'hero_btn_text',
                'value' => 'Get Started',
                'type' => 'string',
                'group' => 'homepage',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'key' => 'hero_btn_url',
                'value' => 'auth/register',
                'type' => 'string',
                'group' => 'homepage',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('settings')->insertBatch($data);
    }

    public function down()
    {
        $keys = ['hero_title', 'hero_description', 'hero_bg_image', 'hero_btn_text', 'hero_btn_url'];
        $this->db->table('settings')->whereIn('key', $keys)->delete();
    }
}
