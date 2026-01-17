<?php

namespace App\Models;

use CodeIgniter\Model;

class PageModel extends Model
{
    protected $table            = 'pages';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'title', 'slug', 'content', 'excerpt', 'featured_image', 'status',
        'meta_title', 'meta_description', 'meta_keywords', 'sort_order',
        'show_in_menu', 'template'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'show_in_menu' => 'boolean',
        'sort_order'   => 'integer',
    ];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'title'  => 'required|min_length[3]|max_length[255]',
        'slug'   => 'permit_empty|max_length[255]',
        'content' => 'required',
        'status' => 'required|in_list[draft,published]',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['generateSlug'];
    protected $afterInsert    = [];
    protected $beforeUpdate   = ['generateSlug'];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Auto-generate slug from title if not provided
     */
    protected function generateSlug(array $data)
    {
        helper('slug');
        
        if (isset($data['data']['title']) && empty($data['data']['slug'])) {
            $slug = generate_slug($data['data']['title']);
            
            // Extract ID for update operations
            $excludeId = null;
            if (isset($data['id'])) {
                $excludeId = is_array($data['id']) ? $data['id'][0] : $data['id'];
            }
            
            $data['data']['slug'] = unique_slug($slug, $this->table, $excludeId);
        } elseif (isset($data['data']['slug'])) {
            // Extract ID for update operations
            $excludeId = null;
            if (isset($data['id'])) {
                $excludeId = is_array($data['id']) ? $data['id'][0] : $data['id'];
            }
            
            $data['data']['slug'] = unique_slug($data['data']['slug'], $this->table, $excludeId);
        }
        
        return $data;
    }

    /**
     * Get all published pages
     */
    public function getPublished()
    {
        return $this->where('status', 'published')->findAll();
    }

    /**
     * Get page by slug
     */
    public function getBySlug(string $slug)
    {
        return $this->where('slug', $slug)->first();
    }

    /**
     * Get pages marked for menu display
     */
    public function getMenuPages()
    {
        return $this->where('show_in_menu', true)
                    ->where('status', 'published')
                    ->orderBy('sort_order', 'ASC')
                    ->findAll();
    }
}
