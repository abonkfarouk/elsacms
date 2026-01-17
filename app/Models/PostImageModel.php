<?php

namespace App\Models;

use CodeIgniter\Model;

class PostImageModel extends Model
{
    protected $table            = 'post_images';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['post_id', 'image_path', 'alt_text', 'caption', 'sort_order'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = null;
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'post_id' => 'required|integer',
        'image_path' => 'required|max_length[255]',
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
    protected $beforeDelete   = ['deleteImageFile'];
    protected $afterDelete    = [];

    /**
     * Delete image file before deleting record
     */
    protected function deleteImageFile(array $data)
    {
        helper('upload');
        
        if (isset($data['id'])) {
            $image = $this->find($data['id']);
            if ($image && !empty($image['image_path'])) {
                delete_image($image['image_path']);
            }
        }
        
        return $data;
    }

    /**
     * Get images for a post
     */
    public function getByPostId(int $postId)
    {
        return $this->where('post_id', $postId)
            ->orderBy('sort_order', 'ASC')
            ->findAll();
    }
}
