<?php

namespace App\Models;

use CodeIgniter\Model;

class PostModel extends Model
{
    protected $table            = 'posts';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'title', 'slug', 'excerpt', 'content', 'category_id', 'author_id',
        'featured_image', 'status', 'meta_title', 'meta_description',
        'meta_keywords', 'published_at'
    ];

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
        'title' => 'required|min_length[3]|max_length[255]',
        'slug' => 'permit_empty|max_length[255]',
        'content' => 'required',
        'author_id' => 'required|integer',
        'status' => 'required|in_list[draft,published]',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['generateSlug', 'setPublishedAt'];
    protected $afterInsert    = [];
    protected $beforeUpdate   = ['generateSlug', 'setPublishedAt'];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = ['deleteRelatedData'];
    protected $afterDelete    = [];

    /**
     * Auto-generate slug from title if not provided
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
        
        if (isset($data['data']['title']) && empty($data['data']['slug'])) {
            $slug = generate_slug($data['data']['title']);
            $data['data']['slug'] = unique_slug($slug, $this->table, $excludeId);
        } elseif (isset($data['data']['slug'])) {
            $data['data']['slug'] = unique_slug($data['data']['slug'], $this->table, $excludeId);
        }
        
        return $data;
    }

    /**
     * Set published_at timestamp when status changes to published
     */
    protected function setPublishedAt(array $data)
    {
        if (isset($data['data']['status']) && $data['data']['status'] === 'published') {
            // Only set if not already set
            if (!isset($data['data']['published_at'])) {
                $data['data']['published_at'] = date('Y-m-d H:i:s');
            }
        }
        
        return $data;
    }

    /**
     * Delete related data before deleting post
     */
    protected function deleteRelatedData(array $data)
    {
        helper('upload');
        
        if (isset($data['id'])) {
            $post = $this->find($data['id']);
            
            if ($post) {
                // Delete featured image
                if (!empty($post['featured_image'])) {
                    delete_image($post['featured_image']);
                }
                
                // Delete post images (will trigger PostImageModel's beforeDelete)
                $imageModel = new PostImageModel();
                $images = $imageModel->where('post_id', $data['id'])->findAll();
                foreach ($images as $image) {
                    $imageModel->delete($image['id']);
                }
            }
        }
        
        return $data;
    }

    /**
     * Get post with category and author
     */
    public function getWithRelations($id)
    {
        return $this->select('posts.*, categories.name as category_name, categories.slug as category_slug, users.username as author_name, users.full_name as author_full_name')
            ->join('categories', 'categories.id = posts.category_id', 'left')
            ->join('users', 'users.id = posts.author_id', 'left')
            ->find($id);
    }

    /**
     * Get all posts with category and author
     */
    public function getAllWithRelations()
    {
        return $this->select('posts.*, categories.name as category_name, users.username as author_name')
            ->join('categories', 'categories.id = posts.category_id', 'left')
            ->join('users', 'users.id = posts.author_id', 'left')
            ->orderBy('posts.created_at', 'DESC')
            ->findAll();
    }

    /**
     * Get published posts
     */
    public function getPublished($limit = null)
    {
        $builder = $this->select('posts.*, categories.name as category_name, categories.slug as category_slug, users.username as author_name')
            ->join('categories', 'categories.id = posts.category_id', 'left')
            ->join('users', 'users.id = posts.author_id', 'left')
            ->where('posts.status', 'published')
            ->orderBy('posts.published_at', 'DESC');
        
        if ($limit !== null) {
            $builder->limit($limit);
        }
        
        return $builder->findAll();
    }

    /**
     * Get post by slug
     */
    public function getBySlug($slug)
    {
        return $this->select('posts.*, categories.name as category_name, categories.slug as category_slug, users.username as author_name, users.full_name as author_full_name')
            ->join('categories', 'categories.id = posts.category_id', 'left')
            ->join('users', 'users.id = posts.author_id', 'left')
            ->where('posts.slug', $slug)
            ->first();
    }

    /**
     * Get posts by category
     */
    public function getByCategory($categoryId, $limit = null)
    {
        $builder = $this->select('posts.*, users.username as author_name')
            ->join('users', 'users.id = posts.author_id', 'left')
            ->where('posts.category_id', $categoryId)
            ->where('posts.status', 'published')
            ->orderBy('posts.published_at', 'DESC');
        
        if ($limit !== null) {
            $builder->limit($limit);
        }
        
        return $builder->findAll();
    }
    /**
     * Get related posts (same category, excluding current)
     */
    public function getRelated($id, $categoryId, $limit = 3)
    {
        // First try to get posts from same category
        $query = $this->select('posts.*, categories.name as category_name, categories.slug as category_slug, users.username as author_name')
            ->join('categories', 'categories.id = posts.category_id', 'left')
            ->join('users', 'users.id = posts.author_id', 'left')
            ->where('posts.id !=', $id)
            ->where('posts.status', 'published');
            
        if ($categoryId) {
            $query->where('posts.category_id', $categoryId);
        }
            
        $results = $query->orderBy('posts.published_at', 'DESC')
            ->limit($limit)
            ->findAll();

        // If not enough related posts, could fallback to recent posts here, but for now we stick to category strictness
        return $results;
    }
}
