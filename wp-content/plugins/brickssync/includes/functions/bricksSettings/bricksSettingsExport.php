<?php
/**
 * Bricks Settings Export Functions
 *
 * This file contains functions for exporting Bricks Builder settings to JSON files.
 * It provides functionality for:
 * - Exporting global Bricks settings
 * - Handling file storage operations
 * - Managing export configurations
 * - Error handling and logging
 *
 * The file includes functions for:
 * - Exporting settings to specified locations
 * - Validating export operations
 * - Logging export activities
 * - Error handling and reporting
 *
 * @package BricksSync\Includes\Functions\BricksSettings
 * @since 0.1
 */


require_once dirname(__DIR__) . '/storage/effective_storage.php';
use function BricksSync\Includes\Functions\Storage\brickssync_get_storage_path;
// Ensure the debug logger is always available for all contexts
require_once __DIR__ . '/../debug/debug.php';
use function BricksSync\Includes\Functions\Debug\brickssync_log;

/**
 * Bricks Settings Exporter
 * 
 * Exports Bricks Builder settings to a JSON file.
 * This function handles:
 * - Retrieving current Bricks settings
 * - Formatting settings for export
 * - Writing to specified file location
 * - Error handling and logging
 *
 * Features:
 * - Supports custom export paths
 * - Validates settings before export
 * - Provides detailed error messages
 * - Logs export operations
 *
 * @since 0.1
 * @param array $args {
 *     Optional. Export configuration arguments.
 *
 *     @type string $target_file  Path to export file. Default uses configured storage path.
 *     @type array  $exclude      Array of settings to exclude from export.
 * }
 * @return string|WP_Error Success message or WP_Error on failure
 */
function brickssync_bricks_settings_export($args = []) {
    // 1. Determine export file path
    if (empty($args['target_file'])) {
        if (is_multisite() && !is_network_admin()) {
            require_once __DIR__ . '/../network_config.php';
            $site_id = get_current_blog_id();
            $eff = brickssync_get_effective_site_config($site_id);
            require_once dirname(__DIR__) . '/storage/effective_storage.php';
            $path_status = brickssync_get_effective_storage_path_status($site_id);
            $storage_dir = $path_status['path'];
            $settings_file_name = $eff['settings_file_name'] ?? 'bricks-builder-settings.json';
            if (empty($storage_dir)) {
                brickssync_log('Storage path not configured (effective config).', 'error');
                return new WP_Error('storage_path_not_configured', __('Storage path not configured.', 'brickssync'));
            }
            $target_file = trailingslashit($storage_dir) . $settings_file_name;
            // Ensure export directory exists
            if (!is_dir($storage_dir)) {
                if (!mkdir($storage_dir, 0775, true) && !is_dir($storage_dir)) {
                    brickssync_log('Export directory does not exist and could not be created: ' . $storage_dir, 'error');
                    return new WP_Error('export_dir_not_created', __('Export directory does not exist and could not be created.', 'brickssync'));
                }
            }
        } else {
            $storage_dir = brickssync_get_storage_path();
            if (empty($storage_dir)) {
                brickssync_log('Storage path not configured.', 'error');
                return new WP_Error('storage_path_not_configured', __('Storage path not configured.', 'brickssync'));
            }
            $target_file = trailingslashit($storage_dir) . 'bricks-builder-settings.json';
        }
    } else {
        $target_file = $args['target_file'];
    }
    // Always force overwrite for settings export
    $force = true;
    $exclude = $args['exclude'] ?? [];

    // 2. Prevent overwrite unless forced
    if (file_exists($target_file) && !$force) {
        brickssync_log('Export aborted: file exists and force not set: ' . $target_file, 'error');
        return new WP_Error('file_exists', __('Export file already exists. Use force to overwrite.', 'brickssync'));
    }

    // 3. Gather all Bricks settings options
    global $wpdb;
    $all_bricks_options = [];
    if (isset($wpdb)) {
        $all_bricks_options = $wpdb->get_col($wpdb->prepare(
            "SELECT option_name FROM {$wpdb->options} WHERE option_name LIKE %s",
            'bricks\_%'
        ));
    }
    // Allow user to exclude options via config
    $excluded_config = get_option('brickssync_excluded_options', '');
    if ($excluded_config) {
        $excluded_lines = array_filter(array_map('trim', explode("\n", $excluded_config)));
        $exclude = array_merge($exclude, $excluded_lines);
    }
    $option_keys = array_diff($all_bricks_options, $exclude);

    $settings = [];
    foreach ($option_keys as $key) {
        $value = get_option($key);
        if ($value !== false) {
            $settings[$key] = $value;
        }
    }
    if (empty($settings)) {
        brickssync_log('No Bricks settings found to export.', 'warning');
        return new WP_Error('no_settings', __('No Bricks settings found to export.', 'brickssync'));
    }

    // 4. Write to JSON file
    $json = json_encode($settings, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    if ($json === false) {
        brickssync_log('Failed to encode settings as JSON.', 'error');
        return new WP_Error('json_encode_failed', __('Failed to encode settings as JSON.', 'brickssync'));
    }
    $result = file_put_contents($target_file, $json);
    if ($result === false) {
        brickssync_log('Failed to write settings file: ' . $target_file, 'error');
        return new WP_Error('write_failed', __('Failed to write settings file.', 'brickssync'));
    }
    brickssync_log('Exported Bricks settings to: ' . $target_file, 'info');
    return sprintf(__('Bricks settings exported to %s.', 'brickssync'), $target_file);
}