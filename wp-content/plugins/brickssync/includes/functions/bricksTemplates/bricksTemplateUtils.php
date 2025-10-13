<?php
/**
 * Bricks Template Utilities
 *
 * Utility functions to detect and classify Bricks template, settings, and other JSON files
 * by their contents (not just filename).
 *
 * @package BricksSync\Includes\Functions\BricksTemplates
 * @since 0.1
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Detects the type of Bricks JSON file based on its contents.
 * Returns 'template', 'settings', or 'unknown'.
 *
 * @param string $filepath Absolute path to the JSON file.
 * @return string Type: 'template', 'settings', or 'unknown'.
 */
function brickssync_detect_json_file_type($filepath) {
    if (!file_exists($filepath)) return 'unknown';
    $json = json_decode(file_get_contents($filepath), true);
    if (!is_array($json)) return 'unknown';

    // If any settings keys are present at the top level, classify as settings (even if other keys exist)
    $settings_keys = [
        'bricks_global_settings',
        'bricks_theme_styles',
        'bricks_code_signatures_admin_notice',
        'bricks_https_notice_dismissed',
    ];
    foreach ($settings_keys as $key) {
        if (array_key_exists($key, $json)) {
            return 'settings';
        }
    }
    // Only if not settings, then check for template structure
    if (
        (isset($json['post_type']) && $json['post_type'] === 'bricks_template') ||
        (isset($json['id']) && isset($json['content']))
    ) {
        return 'template';
    }
    if (isset($json['global_settings']) || isset($json['settings'])) {
        return 'settings';
    }
    return 'unknown';
}
