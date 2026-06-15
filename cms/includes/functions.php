<?php
/**
 * CMS Functions
 * Core functions for managing website content, images, and pages
 */

require_once __DIR__ . '/../config/database.php';

// ============================================
// CONTENT MANAGEMENT FUNCTIONS
// ============================================

/**
 * Create new content
 * 
 * @param string $pageName
 * @param string $sectionName
 * @param string $languageCode
 * @param string|null $title
 * @param string|null $content
 * @param string|null $imagePath
 * @param string|null $imageAlt
 * @return array ['success' => bool, 'message' => string, 'id' => int]
 */
function createContent(
    string $pageName,
    string $sectionName,
    string $languageCode = 'en',
    ?string $title = null,
    ?string $content = null,
    ?string $imagePath = null,
    ?string $imageAlt = null
): array
{
    try {
        $stmt = db()->prepare("
            INSERT INTO website_content 
            (page_name, section_name, language_code, title, content, image_path, image_alt, updated_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP)
        ");
        
        $stmt->execute([
            $pageName,
            $sectionName,
            $languageCode,
            $title,
            $content,
            $imagePath,
            $imageAlt
        ]);
        
        return [
            'success' => true,
            'message' => 'Content created successfully',
            'id' => db()->lastInsertId()
        ];
        
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Error creating content: ' . $e->getMessage()];
    }
}

/**
 * Get content by ID
 * 
 * @param int $id
 * @return array|null
 */
function getContent(int $id): ?array
{
    try {
        $stmt = db()->prepare("
            SELECT * FROM website_content WHERE id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    } catch (PDOException $e) {
        return null;
    }
}

/**
 * Update content
 * 
 * @param int $id
 * @param string|null $title
 * @param string|null $content
 * @param string|null $imagePath
 * @param string|null $imageAlt
 * @return array ['success' => bool, 'message' => string]
 */
function updateContent(
    int $id,
    ?string $title = null,
    ?string $content = null,
    ?string $imagePath = null,
    ?string $imageAlt = null
): array
{
    try {
        // Get current content to preserve unchanged fields
        $current = getContent($id);
        
        if (!$current) {
            return ['success' => false, 'message' => 'Content not found'];
        }
        
        // Use provided values or keep current values
        $title = $title ?? $current['title'];
        $content = $content ?? $current['content'];
        $imagePath = $imagePath ?? $current['image_path'];
        $imageAlt = $imageAlt ?? $current['image_alt'];
        
        $stmt = db()->prepare("
            UPDATE website_content 
            SET title = ?, content = ?, image_path = ?, image_alt = ?, updated_at = CURRENT_TIMESTAMP
            WHERE id = ?
        ");
        
        $stmt->execute([$title, $content, $imagePath, $imageAlt, $id]);
        
        return ['success' => true, 'message' => 'Content updated successfully'];
        
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Error updating content: ' . $e->getMessage()];
    }
}

/**
 * Delete content
 * 
 * @param int $id
 * @return array ['success' => bool, 'message' => string]
 */
function deleteContent(int $id): array
{
    try {
        // Get content to find image path
        $content = getContent($id);
        
        if (!$content) {
            return ['success' => false, 'message' => 'Content not found'];
        }
        
        // Delete associated image if exists
        if (!empty($content['image_path'])) {
            deleteImage($content['image_path']);
        }
        
        $stmt = db()->prepare("DELETE FROM website_content WHERE id = ?");
        $stmt->execute([$id]);
        
        return ['success' => true, 'message' => 'Content deleted successfully'];
        
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Error deleting content: ' . $e->getMessage()];
    }
}

/**
 * Get all content for a specific page
 * 
 * @param string $pageName
 * @param string $languageCode
 * @return array
 */
function getPageContent(string $pageName, string $languageCode = 'en'): array
{
    try {
        $stmt = db()->prepare("
            SELECT * FROM website_content 
            WHERE page_name = ? AND language_code = ?
            ORDER BY section_name
        ");
        $stmt->execute([$pageName, $languageCode]);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        return [];
    }
}

/**
 * Get content by page and section
 * 
 * @param string $pageName
 * @param string $sectionName
 * @param string $languageCode
 * @return array|null
 */
function getContentBySection(string $pageName, string $sectionName, string $languageCode = 'en'): ?array
{
    try {
        $stmt = db()->prepare("
            SELECT * FROM website_content 
            WHERE page_name = ? AND section_name = ? AND language_code = ?
            LIMIT 1
        ");
        $stmt->execute([$pageName, $sectionName, $languageCode]);
        return $stmt->fetch();
    } catch (PDOException $e) {
        return null;
    }
}

// ============================================
// IMAGE UPLOAD FUNCTIONS
// ============================================

/**
 * Upload image with security validation
 * 
 * @param string $fieldName - Form field name
 * @param string $targetDir - Target directory path
 * @return array ['success' => bool, 'message' => string, 'filename' => string|null]
 */
function uploadImage(string $fieldName, string $targetDir = ''): array
{
    // Get upload directory
    if (empty($targetDir)) {
        $basePath = __DIR__ . '/..';
        $targetDir = $basePath . '/uploads/images';
    }
    
    // Check if file was uploaded
    if (empty($_FILES[$fieldName]) || $_FILES[$fieldName]['error'] === UPLOAD_ERR_NO_FILE) {
        return ['success' => false, 'message' => 'No file uploaded'];
    }
    
    $file = $_FILES[$fieldName];
    
    // Check for upload errors
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $errors = [
            UPLOAD_ERR_INI_SIZE => 'File exceeds upload_max_filesize',
            UPLOAD_ERR_FORM_SIZE => 'File exceeds form MAX_FILE_SIZE',
            UPLOAD_ERR_PARTIAL => 'File was partially uploaded',
            UPLOAD_ERR_NO_FILE => 'No file uploaded',
            UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write file',
            UPLOAD_ERR_EXTENSION => 'A PHP extension stopped the upload'
        ];
        
        $message = $errors[$file['error']] ?? 'Unknown upload error';
        return ['success' => false, 'message' => $message];
    }
    
    // Validate file type (MIME type)
    $allowedMimes = ['image/jpeg', 'image/png', 'image/webp'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    
    if (!in_array($mimeType, $allowedMimes)) {
        return ['success' => false, 'message' => 'Only JPG, PNG, and WebP images are allowed'];
    }
    
    // Validate file size (5MB max)
    $maxSize = 5 * 1024 * 1024; // 5MB
    if ($file['size'] > $maxSize) {
        return ['success' => false, 'message' => 'File size exceeds 5MB limit'];
    }
    
    // Create target directory if it doesn't exist
    if (!is_dir($targetDir)) {
        if (!mkdir($targetDir, 0755, true)) {
            return ['success' => false, 'message' => 'Failed to create upload directory'];
        }
    }
    
    // Generate unique filename
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
    $extension = strtolower($extension);
    
    if (!in_array($extension, $allowedExtensions)) {
        return ['success' => false, 'message' => 'Invalid file extension'];
    }
    
    // Create unique filename: timestamp + random hash + extension
    $filename = time() . '_' . bin2hex(random_bytes(8)) . '.' . $extension;
    $targetPath = $targetDir . '/' . $filename;
    
    // Move uploaded file
    if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
        return ['success' => false, 'message' => 'Failed to move uploaded file'];
    }
    
    // Return relative path for database storage
    $relativePath = '/cms/uploads/images/' . $filename;
    
    return [
        'success' => true,
        'message' => 'Image uploaded successfully',
        'filename' => $filename,
        'path' => $relativePath
    ];
}

/**
 * Delete image file
 * 
 * @param string $imagePath - Relative or full path to image
 * @return bool
 */
function deleteImage(string $imagePath): bool
{
    // Convert relative path to full path
    if (strpos($imagePath, '/cms/uploads/images/') === 0) {
        $basePath = __DIR__ . '/../..';
        $fullPath = $basePath . $imagePath;
    } else {
        $fullPath = $imagePath;
    }
    
    // Prevent directory traversal attacks
    $fullPath = realpath($fullPath);
    $uploadDir = realpath(__DIR__ . '/../uploads/images');
    
    if ($fullPath === false || strpos($fullPath, $uploadDir) !== 0) {
        return false;
    }
    
    if (file_exists($fullPath) && is_file($fullPath)) {
        return unlink($fullPath);
    }
    
    return false;
}

// ============================================
// DASHBOARD STATISTICS FUNCTIONS
// ============================================

/**
 * Get total number of pages
 * 
 * @return int
 */
function getTotalPages(): int
{
    try {
        $stmt = db()->query("
            SELECT COUNT(DISTINCT page_name) as count FROM website_content
        ");
        $result = $stmt->fetch();
        return $result['count'] ?? 0;
    } catch (PDOException $e) {
        return 0;
    }
}

/**
 * Get total number of content sections
 * 
 * @return int
 */
function getTotalSections(): int
{
    try {
        $stmt = db()->query("
            SELECT COUNT(*) as count FROM website_content
        ");
        $result = $stmt->fetch();
        return $result['count'] ?? 0;
    } catch (PDOException $e) {
        return 0;
    }
}

/**
 * Get total number of images
 * 
 * @return int
 */
function getTotalImages(): int
{
    try {
        $stmt = db()->query("
            SELECT COUNT(*) as count FROM website_content WHERE image_path IS NOT NULL AND image_path != ''
        ");
        $result = $stmt->fetch();
        return $result['count'] ?? 0;
    } catch (PDOException $e) {
        return 0;
    }
}

/**
 * Get last updated content
 * 
 * @param int $limit
 * @return array
 */
function getLastUpdatedContent(int $limit = 5): array
{
    try {
        $stmt = db()->query("
            SELECT id, page_name, section_name, language_code, title, updated_at 
            FROM website_content 
            ORDER BY updated_at DESC 
            LIMIT $limit
        ");
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        return [];
    }
}
?>
