<?php

namespace App\Models;

use CodeIgniter\Model;

class MenuItemModel extends Model
{
    protected $table            = 'menu_items';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'menu_id', 'parent_id', 'title', 'url', 'type', 'type_id', 'target', 'sort_order'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'menu_id' => 'integer',
        'parent_id' => '?integer',
        'type_id' => '?integer',
        'sort_order' => '?integer',
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
        'menu_id' => 'required|integer',
        'title' => 'required|max_length[100]',
        'type' => 'required|in_list[page,post,category,custom,external]',
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
     * Get all items for a menu
     */
    public function getByMenuId(int $menuId)
    {
        return $this->where('menu_id', $menuId)
                    ->orderBy('sort_order', 'ASC')
                    ->findAll();
    }

    /**
     * Get items in hierarchical structure
     */
    public function getHierarchical(int $menuId)
    {
        $items = $this->getByMenuId($menuId);
        return $this->buildTree($items);
    }

    /**
     * Build tree structure from flat array
     */
    private function buildTree(array $items, $parentId = null)
    {
        $branch = [];
        
        foreach ($items as $item) {
            if ($item['parent_id'] == $parentId) {
                $children = $this->buildTree($items, $item['id']);
                if ($children) {
                    $item['children'] = $children;
                }
                $branch[] = $item;
            }
        }
        
        return $branch;
    }

    /**
     * Update order of multiple items
     */
    public function updateOrder(array $items)
    {
        $db = \Config\Database::connect();
        $db->transStart();
        
        foreach ($items as $index => $item) {
            $this->update($item['id'], [
                'sort_order' => $index,
                'parent_id' => $item['parent_id'] ?? null,
            ]);
        }
        
        $db->transComplete();
        return $db->transStatus();
    }
}
