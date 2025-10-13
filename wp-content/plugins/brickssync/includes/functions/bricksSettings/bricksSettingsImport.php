<?php
/**
 * Bricks Settings Import Functions
 *
 * This file contains functions for importing Bricks Builder settings from JSON files.
 * It provides functionality for:
 * - Importing global Bricks settings
 * - Validating imported settings
 * - Managing import configurations
 * - Error handling and logging
 *
 * The file includes functions for:
 * - Importing settings from specified locations
 * - Validating import operations
 * - Logging import activities
 * - Error handling and reporting
 *
 * @package BricksSync\Includes\Functions\BricksSettings
 * @since 0.1
 */

use BricksSync\Includes\Functions\Debug\brickssync_log;

use function BricksSync\Includes\Functions\Storage\brickssync_get_storage_path;
// Ensure the debug logger is always available for all contexts
require_once __DIR__ . '/../debug/debug.php';
use function BricksSync\Includes\Functions\Debug\brickssync_log;

/**
 * Bricks Settings Importer
 * 
 * Imports Bricks Builder settings from a JSON file.
 * This function handles:
 * - Reading settings from source file
 * - Validating imported settings
 * - Applying settings to Bricks
 * - Error handling and logging
 *
 * Features:
 * - Supports custom import paths
 * - Validates settings before import
 * - Provides detailed error messages
 * - Logs import operations
 *
 * @since 0.1
 * @param array $args {
 *     Optional. Import configuration arguments.
 *
 *     @type string $source_file  Path to import file. Default uses configured storage path.
 *     @type bool   $force        Whether to force import even if settings exist. Default false.
 *     @type array  $exclude      Array of settings to exclude from import.
 * }
 * @return string|WP_Error Success message or WP_Error on failure
 */
function brickssync_bricks_settings_import($args = []) {
    // 1. Determine import file path
    // Use effective storage path for multisite subsites
    if (function_exists('is_multisite') && is_multisite() && !is_network_admin()) {
        require_once __DIR__ . '/../network_config.php';
        require_once dirname(__DIR__) . '/storage/effective_storage.php';
        $site_id = get_current_blog_id();
        $eff = brickssync_get_effective_site_config($site_id);
        $path_status = brickssync_get_effective_storage_path_status($site_id);
        $storage_dir = $path_status['path'];
        $settings_filename = $eff['settings_file_name'] ?? 'bricks-builder-settings.json';
        if (!$storage_dir) {
            brickssync_log('Storage directory not found or not configured: ' . print_r($storage_dir, true), 'error');
            return new WP_Error('storage_not_found', __('Storage directory not found.', 'brickssync'));
        }
        $source_file = $args['source_file'] ?? trailingslashit($storage_dir) . $settings_filename;
    } else {
        $storage_dir = brickssync_get_storage_path();
        if (!$storage_dir) {
            brickssync_log('Storage directory not found or not configured: ' . print_r($storage_dir, true), 'error');
            return new WP_Error('storage_not_found', __('Storage directory not found.', 'brickssync'));
        }
        $settings_filename = get_option('brickssync_settings_file_name', 'bricks-builder-settings.json');
        $source_file = $args['source_file'] ?? trailingslashit($storage_dir) . $settings_filename;
    }
    // Ensure directory exists
    if (!is_dir($storage_dir)) {
        brickssync_log('Storage directory does not exist: ' . $storage_dir, 'error');
        return new WP_Error('storage_dir_missing', __('Storage directory does not exist.', 'brickssync'));
    }
    $exclude = $args['exclude'] ?? [];

    // 2. Check file exists and is readable
    if (!file_exists($source_file) || !is_readable($source_file)) {
        brickssync_log('Settings import file not found or not readable: ' . $source_file, 'error');
        return new WP_Error('file_not_found', __('Settings import file not found or not readable.', 'brickssync'));
    }

    // 3. Read and decode JSON
    $json = file_get_contents($source_file);
    $settings = json_decode($json, true);
    if (!is_array($settings)) {
        brickssync_log('Invalid or corrupt JSON in settings file: ' . $source_file, 'error');
        return new WP_Error('json_decode_failed', __('Invalid or corrupt JSON in settings file.', 'brickssync'));
    }

    // 4. Exclude any configured options
    $excluded_config = get_option('brickssync_excluded_options', '');
    if ($excluded_config) {
        $excluded_lines = array_filter(array_map('trim', explode("\n", $excluded_config)));
        $exclude = array_merge($exclude, $excluded_lines);
    }
    foreach ($exclude as $ex) {
        unset($settings[$ex]);
    }

    // 5. Import settings into WordPress
    $imported = 0;
    $unchanged = 0;
    $errors = [];
    foreach ($settings as $key => $value) {
        // Only import options that start with bricks_
        if (strpos($key, 'bricks_') !== 0) {
            continue;
        }
        $current = get_option($key, null);
        brickssync_log(
            "Importing $key: current=" . var_export($current, true) .
            " new=" . var_export($value, true),
            'debug'
        );
        $result = update_option($key, $value);
        if ($result) {
            $imported++;
            brickssync_log('Imported Bricks setting: ' . $key, 'info');
        } else {
            if ($current === $value) {
                $unchanged++;
                brickssync_log('Skipped import for ' . $key . ': value unchanged.', 'warning');
            } else {
                $errors[] = $key;
                brickssync_log('Failed to import Bricks setting: ' . $key, 'error');
            }
        }
    }
    $msg = sprintf(__('Bricks settings imported from %s. %d option(s) imported.', 'brickssync'), $source_file, $imported);
    if ($unchanged > 0) {
        $msg .= ' ' . sprintf(_n('%d option was unchanged and skipped.', '%d options were unchanged and skipped.', $unchanged, 'brickssync'), $unchanged);
    }
    if (!empty($errors)) {
        return new WP_Error('import_partial', __('Some settings failed to import: ', 'brickssync') . implode(', ', $errors) . ' | ' . $msg);
    }
    return $msg;
}