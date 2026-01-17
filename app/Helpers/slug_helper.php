<?php

if (!function_exists('generate_slug')) {
    /**
     * Generate URL-friendly slug from text
     * 
     * @param string $text
     * @return string
     */
    function generate_slug(string $text): string
    {
        // Convert to lowercase
        $slug = strtolower($text);
        
        // Replace spaces with hyphens
        $slug = str_replace(' ', '-', $slug);
        
        // Remove special characters
        $slug = preg_replace('/[^a-z0-9\-]/', '', $slug);
        
        // Remove multiple hyphens
        $slug = preg_replace('/-+/', '-', $slug);
        
        // Trim hyphens from start and end
        $slug = trim($slug, '-');
        
        return $slug;
    }
}

if (!function_exists('unique_slug')) {
    /**
     * Ensure slug is unique by appending number if needed
     * 
     * @param string $slug
     * @param string $table
     * @param int|null $excludeId
     * @return string
     */
    function unique_slug(string $slug, string $table, ?int $excludeId = null): string
    {
        $db = \Config\Database::connect();
        $builder = $db->table($table);
        
        $originalSlug = $slug;
        $counter = 1;
        
        while (true) {
            $builder->where('slug', $slug);
            
            if ($excludeId !== null) {
                $builder->where('id !=', $excludeId);
            }
            
            $exists = $builder->countAllResults() > 0;
            
            if (!$exists) {
                return $slug;
            }
            
            $slug = $originalSlug . '-' . $counter;
            $counter++;
            
            // Reset builder for next iteration
            $builder = $db->table($table);
        }
    }
}
