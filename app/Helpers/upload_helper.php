<?php

if (!function_exists('upload_image')) {
    /**
     * Upload and validate image file
     * 
     * @param \CodeIgniter\HTTP\Files\UploadedFile $file
     * @param string $path Directory path relative to public/
     * @param array $options Optional settings (maxSize, maxWidth, maxHeight)
     * @return array ['success' => bool, 'path' => string|null, 'error' => string|null]
     */
    function upload_image($file, string $path = 'uploads/images', array $options = []): array
    {
        // Default options
        $maxSize = $options['maxSize'] ?? 2048; // KB
        $maxWidth = $options['maxWidth'] ?? 2000; // pixels
        $maxHeight = $options['maxHeight'] ?? 2000; // pixels
        
        // Validate file
        if (!$file->isValid()) {
            return [
                'success' => false,
                'path' => null,
                'error' => 'File upload failed: ' . $file->getErrorString()
            ];
        }
        
        // Check file type
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($file->getMimeType(), $allowedTypes)) {
            return [
                'success' => false,
                'path' => null,
                'error' => 'Invalid file type. Only JPG, PNG, GIF, and WebP are allowed.'
            ];
        }
        
        // Check file size
        if ($file->getSizeByUnit('kb') > $maxSize) {
            return [
                'success' => false,
                'path' => null,
                'error' => "File size exceeds {$maxSize}KB limit."
            ];
        }
        
        // Create directory if not exists
        $uploadPath = FCPATH . $path;
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }
        
        // Generate unique filename
        $newName = $file->getRandomName();
        
        // Move file
        if ($file->move($uploadPath, $newName)) {
            $filePath = $path . '/' . $newName;
            
            // Resize if needed
            $fullPath = FCPATH . $filePath;
            $imageInfo = getimagesize($fullPath);
            
            if ($imageInfo && ($imageInfo[0] > $maxWidth || $imageInfo[1] > $maxHeight)) {
                resize_image($fullPath, $fullPath, $maxWidth, $maxHeight);
            }
            
            return [
                'success' => true,
                'path' => $filePath,
                'error' => null
            ];
        }
        
        return [
            'success' => false,
            'path' => null,
            'error' => 'Failed to move uploaded file.'
        ];
    }
}

if (!function_exists('resize_image')) {
    /**
     * Resize image maintaining aspect ratio
     * 
     * @param string $source Source file path
     * @param string $destination Destination file path
     * @param int $maxWidth Maximum width
     * @param int $maxHeight Maximum height
     * @return bool
     */
    function resize_image(string $source, string $destination, int $maxWidth, int $maxHeight): bool
    {
        $imageLib = \Config\Services::image();
        
        try {
            $imageLib->withFile($source)
                ->fit($maxWidth, $maxHeight, 'center')
                ->save($destination);
            
            return true;
        } catch (\Exception $e) {
            log_message('error', 'Image resize failed: ' . $e->getMessage());
            return false;
        }
    }
}

if (!function_exists('delete_image')) {
    /**
     * Delete image file
     * 
     * @param string $path File path relative to FCPATH
     * @return bool
     */
    function delete_image(string $path): bool
    {
        if (empty($path)) {
            return false;
        }
        
        $fullPath = FCPATH . $path;
        
        if (file_exists($fullPath)) {
            return unlink($fullPath);
        }
        
        return false;
    }
}
