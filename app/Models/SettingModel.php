<?php

namespace App\Models;

use CodeIgniter\Model;

class SettingModel extends Model
{
    protected $table            = 'settings';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['key', 'value', 'type', 'group'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'key'   => 'required|max_length[100]',
        'type'  => 'required|in_list[text,textarea,email,url,image]',
        'group' => 'required|in_list[general,branding,contact,social]',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Get a single setting value by key
     */
    public function getSetting(string $key, $default = null)
    {
        $setting = $this->where('key', $key)->first();
        return $setting ? $setting['value'] : $default;
    }

    /**
     * Get all settings in a group
     */
    public function getSettingsByGroup(string $group): array
    {
        return $this->where('group', $group)->findAll();
    }

    /**
     * Update a setting value
     */
    public function updateSetting(string $key, $value): bool
    {
        $setting = $this->where('key', $key)->first();
        
        if ($setting) {
            // Skip validation since we're only updating the value field
            $this->skipValidation = true;
            $result = $this->update($setting['id'], ['value' => $value]);
            $this->skipValidation = false;
            return $result;
        }
        
        return false;
    }

    /**
     * Get all settings as key-value array
     */
    public function getAllSettings(): array
    {
        $settings = $this->findAll();
        $result = [];
        
        foreach ($settings as $setting) {
            $result[$setting['key']] = $setting['value'];
        }
        
        return $result;
    }

    /**
     * Get all settings grouped by group
     */
    public function getAllSettingsGrouped(): array
    {
        $settings = $this->findAll();
        $result = [];
        
        foreach ($settings as $setting) {
            if (!isset($result[$setting['group']])) {
                $result[$setting['group']] = [];
            }
            $result[$setting['group']][$setting['key']] = $setting;
        }
        
        return $result;
    }
}
