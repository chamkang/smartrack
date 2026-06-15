<?php
/**
 * CMS Integration for Main Website
 * Bridges the main website with the CMS database
 * Include this file in your website pages to use CMS content
 */

// Load CMS functions
require_once __DIR__ . '/cms/config/database.php';
require_once __DIR__ . '/cms/includes/functions.php';

/**
 * Get content from CMS for display on website
 * 
 * @param string $page Page name
 * @param string $section Section name
 * @param string $language Language code (default: 'en')
 * @return array|null
 */
function getCMSContent($page, $section, $language = 'en') {
    return getContentBySection($page, $section, $language);
}

/**
 * Get page content by ID from CMS
 * 
 * @param int $id Content ID
 * @return array|null
 */
function getCMSContentById($id) {
    return getContent($id);
}

/**
 * Get all content sections for a page
 * 
 * @param string $page Page name
 * @param string $language Language code (default: 'en')
 * @return array
 */
function getPageCMSContent($page, $language = 'en') {
    return getPageContent($page, $language);
}

/**
 * Display content title with fallback
 * 
 * @param array|null $content Content array from CMS
 * @param string $fallback Fallback text if no content
 * @return string
 */
function displayCMSTitle($content, $fallback = '') {
    if (!$content || empty($content['title'])) {
        return $fallback;
    }
    return escape($content['title']);
}

/**
 * Display content text with fallback
 * 
 * @param array|null $content Content array from CMS
 * @param string $fallback Fallback text if no content
 * @return string
 */
function displayCMSContent($content, $fallback = '') {
    if (!$content || empty($content['content'])) {
        return $fallback;
    }
    return escape($content['content']);
}

/**
 * Display content image with fallback
 * 
 * @param array|null $content Content array from CMS
 * @param string $fallback Fallback image URL
 * @return string|null
 */
function displayCMSImage($content, $fallback = null) {
    if (!$content || empty($content['image_path'])) {
        return $fallback;
    }
    return escape($content['image_path']);
}

/**
 * Get image alt text
 * 
 * @param array|null $content Content array from CMS
 * @param string $fallback Fallback alt text
 * @return string
 */
function getCMSImageAlt($content, $fallback = 'Image') {
    if (!$content || empty($content['image_alt'])) {
        return $fallback;
    }
    return escape($content['image_alt']);
}

/**
 * Check if content exists
 * 
 * @param array|null $content Content array
 * @return bool
 */
function hasCMSContent($content) {
    return !empty($content) && !empty($content['id']);
}

/**
 * Get content with fallback behavior
 * 
 * @param string $page Page name
 * @param string $section Section name
 * @param string $language Language code
 * @param array $fallback Fallback array with keys: title, content, image_path, image_alt
 * @return array
 */
function getCMSContentWithFallback($page, $section, $language = 'en', $fallback = []) {
    $content = getContentBySection($page, $section, $language);
    
    if (!$content) {
        $content = array_merge([
            'id' => null,
            'page_name' => $page,
            'section_name' => $section,
            'language_code' => $language,
            'title' => '',
            'content' => '',
            'image_path' => null,
            'image_alt' => '',
            'updated_at' => null
        ], $fallback);
    }
    
    return $content;
}

/**
 * Get dashboard statistics for website admin
 * 
 * @return array
 */
function getCMSStats() {
    return [
        'total_pages' => getTotalPages(),
        'total_sections' => getTotalSections(),
        'total_images' => getTotalImages(),
        'last_updated' => getLastUpdatedContent(1)
    ];
}

/**
 * Initialize CMS for website (check database)
 * 
 * @return bool
 */
function initializeCMS() {
    try {
        $stmt = db()->query("SELECT COUNT(*) as count FROM website_content LIMIT 1");
        return true;
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Check if CMS content exists for page
 * 
 * @param string $page Page name
 * @param string $language Language code
 * @return bool
 */
function cmsPageExists($page, $language = 'en') {
    $content = getPageContent($page, $language);
    return !empty($content);
}
?>
