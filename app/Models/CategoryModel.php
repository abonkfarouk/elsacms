<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $table            = 'categories';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['name', 'slug', 'description'];

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
        'name' => 'required|min_length[2]|max_length[100]',
        'slug' => 'permit_empty|max_length[100]',
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
     * Auto-generate slug from name if not provided
     */
    protected function generateSlug(array $data)
    {
        helper('slug');
        
        // Extract ID for update operations
        $excludeId = null;
        if (isset($data['id'])) {
            // For updates, id is passed as array with single element
            $excludeId = is_array($data['id']) ? $data['id'][0] : $data['id'];
        }
        
        if (isset($data['data']['name']) && empty($data['data']['slug'])) {
            $slug = generate_slug($data['data']['name']);
            $data['data']['slug'] = unique_slug($slug, $this->table, $excludeId);
        } elseif (isset($data['data']['slug'])) {
            $data['data']['slug'] = unique_slug($data['data']['slug'], $this->table, $excludeId);
        }
        
        return $data;
    }

    /**
     * Get category with post count
     */
    public function getWithPostCount($id = null)
    {
        $builder = $this->db->table($this->table);
        $builder->select('categories.*, COUNT(posts.id) as post_count');
        $builder->join('posts', 'posts.category_id = categories.id', 'left');
        $builder->groupBy('categories.id');
        
        if ($id !== null) {
            $builder->where('categories.id', $id);
            return $builder->get()->getRowArray();
        }
        
        return $builder->get()->getResultArray();
    }

    /**
     * Get all categories with post counts
     */
    public function getAllWithPostCount()
    {
        return $this->getWithPostCount();
    }
}
