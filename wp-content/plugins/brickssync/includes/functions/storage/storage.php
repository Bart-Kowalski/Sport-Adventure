<?php
/**
 * Storage Helper Functions
 *
 * This file contains functions for handling file storage operations in BricksSync.
 * It provides functionality for:
 * - Managing storage paths and locations
 * - Validating storage directory permissions
 * - Handling file cleanup operations
 * - Providing storage URLs for file access
 *
 * The file includes functions for:
 * - Determining and validating storage paths
 * - Creating and managing storage directories
 * - Cleaning up old files
 * - Retrieving storage URLs
 *
 * @package BricksSync\Includes\Functions\Storage
 * @since 0.1
 */

namespace BricksSync\Includes\Functions\Storage;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Storage Path Status Checker
 * 
 * Determines the configured storage path for JSON files and checks its status.
 * This function is crucial for ensuring the plugin can properly store and access files.
 *
 * Features:
 * - Supports multiple storage locations (child theme, uploads folder, custom path)
 * - Validates directory existence and permissions
 * - Provides detailed status information
 * - Returns comprehensive error messages
 *
 * @since 0.1
 * @return array{
 *     path: string|null,
 *     status: string,
 *     message: string,
 *     is_readable: bool,
 *     is_writable: bool
 * }
 *         - path: The resolved absolute path or null on error
 *         - status: Current status ('ok', 'not_configured', 'not_found', etc.)
 *         - message: User-friendly status description
 *         - is_readable: Directory readability status
 *         - is_writable: Directory writability status
 */
function brickssync_get_storage_path_status(): array {
    $location = get_option('brickssync_json_storage_location');
    $subdir = trim(get_option('brickssync_json_subdir', 'brickssync-json')) ?: 'brickssync-json';
    $path = null;
    $status = 'not_configured';
    $message = __('Storage location is not configured.', 'brickssync');
    $is_readable = false;
    $is_writable = false;

    // Return early if no location option is set.
    if ( ! $location ) {
        return compact('path', 'status', 'message', 'is_readable', 'is_writable');
    }

    // Determine path based on the configured location option.
    switch ($location) {
        case 'child_theme':
            $path = get_stylesheet_directory();
            break;
        case 'uploads_folder':
            $upload_dir = wp_get_upload_dir();
            $path = $upload_dir['basedir'] ?? null;
            break;
        case 'custom_url': // Note: option name is custom_url but stores a path
            $path = get_option('brickssync_custom_storage_location_path');
            break;
    }

    // Handle cases where path could not be determined.
    if ( empty($path) ) {
        $status = 'error';
        $message = sprintf(
            __('Could not determine storage path based on configuration (%s).', 'brickssync'), 
            esc_html($location)
        );
        return compact('path', 'status', 'message', 'is_readable', 'is_writable');
    }

    // Append the subdirectory and ensure trailing slash.
    $path = trailingslashit($path) . trailingslashit($subdir);

    // Check directory status (existence, readability, writability).
    if ( ! is_dir($path) ) {
        $status = 'not_found';
        $message = __('Configured storage path does not exist or is not a directory:', 'brickssync') . ' ' . esc_html($path);
    } elseif ( ! is_readable($path) ) {
        $status = 'not_readable';
        $message = __('Configured storage path is not readable by the web server:', 'brickssync') . ' ' . esc_html($path);
        $is_readable = false; // Cannot check writability if not readable.
        $is_writable = false;
    } else {
        // Path exists and is readable, now check writability.
        $is_readable = true;
        if ( ! wp_is_writable($path) ) {
            $status = 'not_writable';
            $message = __('Configured storage path is not writable by the web server:', 'brickssync') . ' ' . esc_html($path) . ' ' . __('Exporting may fail.', 'brickssync');
            $is_writable = false;
        } else {
            // Path is valid, readable, and writable.
            $status = 'ok';
            $message = __('Storage path configured and accessible:', 'brickssync') . ' ' . esc_html($path);
            $is_writable = true;
        }
    }

    return compact('path', 'status', 'message', 'is_readable', 'is_writable');
}

/**
 * Storage Path Getter
 * 
 * Retrieves the configured storage path and ensures it exists and is writable.
 * This function handles:
 * - Path resolution based on WordPress uploads directory
 * - Directory creation if missing
 * - Permission validation
 * - Error handling for unwritable directories
 *
 * @since 0.1
 * @return string|null The storage path or null if not configured
 * @throws WP_Error If directory cannot be created or is not writable
 */
function brickssync_get_storage_path() {
    $location = get_option('brickssync_json_storage_location');
    $subdir = trim(get_option('brickssync_json_subdir', 'brickssync-json')) ?: 'brickssync-json';
    $path = null;
    switch ($location) {
        case 'child_theme':
            $path = get_stylesheet_directory();
            break;
        case 'uploads_folder':
            $upload_dir = wp_get_upload_dir();
            $path = $upload_dir['basedir'] ?? null;
            break;
        case 'custom_url':
            $path = get_option('brickssync_custom_storage_location_path');
            break;
        default:
            // Fallback: uploads dir
            $upload_dir = wp_get_upload_dir();
            $path = $upload_dir['basedir'] ?? null;
            break;
    }
    if (empty($path)) {
        return null;
    }
    $full_path = trailingslashit($path) . trailingslashit($subdir);
    // Create directory if it doesn't exist
    if (!file_exists($full_path)) {
        wp_mkdir_p($full_path);
    }
    // Automatically create .htaccess to protect JSON directory (Apache only)
    $htaccess_file = trailingslashit($full_path) . '.htaccess';
    if (!file_exists($htaccess_file)) {
        $htaccess_contents = "# Protect BricksSync JSON files from direct web access\nOrder allow,deny\nDeny from all\n\n<IfModule mod_authz_core.c>\n    Require all denied\n</IfModule>\n";
        // Suppress errors in case of permissions issues
        @file_put_contents($htaccess_file, $htaccess_contents);
    }
    // Ensure directory is writable
    if (!is_writable($full_path)) {
        wp_die(
            sprintf(
                /* translators: %s: Directory path */
                __('The directory %s is not writable. Please check your file permissions.', 'brickssync'),
                $full_path
            )
        );
    }
    return $full_path;
}

/**
 * Storage URL Getter
 * 
 * Retrieves the public URL for accessing stored files.
 * This function:
 * - Uses WordPress uploads directory structure
 * - Provides consistent URL formatting
 * - Ensures proper URL construction
 *
 * @since 0.1
 * @return string|null The storage URL or null if not configured
 */
function brickssync_get_storage_url() {
    $upload_dir = wp_upload_dir();
    return $upload_dir['baseurl'] . '/brickssync';
}

/**
 * Storage Cleanup Manager
 * 
 * Manages cleanup of old files in the storage directory.
 * This function:
 * - Removes files older than specified days
 * - Handles file system operations safely
 * - Provides configurable retention period
 *
 * @since 0.1
 * @param int $days Number of days to keep files (default: 7)
 * @return void
 */
function brickssync_cleanup_old_files($days = 7) {
    $storage_path = brickssync_get_storage_path();
    $files = glob($storage_path . '/*');

    if ($files === false) {
        return;
    }

    $now = time();
    foreach ($files as $file) {
        if (is_file($file)) {
            if ($now - filemtime($file) >= 60 * 60 * 24 * $days) {
                unlink($file);
            }
        }
    }
} 