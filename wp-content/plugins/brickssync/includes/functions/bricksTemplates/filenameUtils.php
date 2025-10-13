<?php
/**
 * Filename Utilities for BricksSync
 *
 * Provides functions to sanitize and build filenames for template export/import.
 *
 * @package BricksSync\Includes\Functions\BricksTemplates
 * @since 0.1
 */

namespace BricksSync\Includes\Functions\BricksTemplates;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Sanitize a string for use as a filename.
 *
 * Replaces spaces and unsafe characters with hyphens, removes non-ASCII,
 * and trims to a reasonable length.
 *
 * @param string $title
 * @return string
 */
function brickssync_sanitize_filename($title) {
    $sanitized = sanitize_title($title); // Uses WP's slug sanitizer
    $sanitized = substr($sanitized, 0, 80); // Limit length
    return $sanitized;
}
