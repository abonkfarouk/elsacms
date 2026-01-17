<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSiteLogoTypeSetting extends Migration
{
    public function up()
    {
        $this->db->table('settings')->insert([
            'key'       => 'site_logo_type',
            'value'     => 'text',
            'group'     => 'branding',
            'type'      => 'string',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function down()
    {
        $this->db->table('settings')->where('key', 'site_logo_type')->delete();
    }
}
