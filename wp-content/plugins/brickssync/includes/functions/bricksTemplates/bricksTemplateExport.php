<?php
/**
 * Bricks Templates Export Functions
 *
 * This file contains functions for exporting Bricks Builder templates to JSON files.
 * It provides functionality for:
 * - Exporting individual or multiple templates
 * - Handling file storage operations
 * - Managing export configurations
 * - Error handling and logging
 *
 * The file includes functions for:
 * - Exporting templates to specified locations
 * - Validating export operations
 * - Logging export activities
 * - Error handling and reporting
 *
 * @package BricksSync\Includes\Functions\BricksTemplates
 * @since 0.1
 */

use BricksSync\Includes\Functions\Debug\brickssync_log;
use function BricksSync\Includes\Functions\BricksTemplates\brickssync_sanitize_filename;
require_once dirname(__DIR__) . '/storage/effective_storage.php';
use function BricksSync\Includes\Functions\Storage\brickssync_get_storage_path;

/**
 * Bricks Templates Exporter
 * 
 * Exports Bricks Builder templates to JSON files.
 * This function handles:
 * - Retrieving template data from database
 * - Formatting templates for export
 * - Writing to specified file location
 * - Error handling and logging
 *
 * Features:
 * - Supports single or bulk template export
 * - Validates templates before export
 * - Provides detailed error messages
 * - Logs export operations
 *
 * @since 0.1
 * @param array $args {
 *     Optional. Export configuration arguments.
 *
 *     @type int|array $template_id  Template ID or array of IDs to export. Default exports all templates.
 *     @type string    $target_dir   Directory to export to. Default uses configured storage path.
 *     @type bool      $force        Whether to force export even if files exist. Default false.
 *     @type array     $exclude      Array of template fields to exclude from export.
 * }
 * @return string|WP_Error Success message or WP_Error on failure
 */
function brickssync_bricks_template_export($args = []) {
    if (is_multisite() && !is_network_admin()) {
        require_once __DIR__ . '/../network_config.php';
        $site_id = get_current_blog_id();
        $eff = brickssync_get_effective_site_config($site_id);
        $file_pattern = $eff['templates_file_pattern'] ?? 'template-{slug}.json';
    }
    if (empty($file_pattern)) {
        $file_pattern = get_option('brickssync_templates_file_pattern', 'template-{slug}.json');
    }
    // Use effective storage path for multisite subsites
    if (function_exists('is_multisite') && is_multisite() && !is_network_admin()) {
        require_once dirname(__DIR__) . '/storage/effective_storage.php';
        $site_id = get_current_blog_id();
        $path_status = brickssync_get_effective_storage_path_status($site_id);
        $storage_dir = isset($args['target_dir']) && $args['target_dir'] ? $args['target_dir'] : ($path_status['path'] ?? '');
        \BricksSync\Includes\Functions\Debug\brickssync_log('Export using brickssync_get_effective_storage_path_status() returned: ' . $storage_dir, 'info');
    } else {
        $storage_dir = isset($args['target_dir']) && $args['target_dir'] ? $args['target_dir'] : brickssync_get_storage_path();
        \BricksSync\Includes\Functions\Debug\brickssync_log('Export using brickssync_get_storage_path() returned: ' . $storage_dir, 'info');
    }

    // Get templates to export
    if (isset($args['template_id']) && !empty($args['template_id'])) {
        // Only export the specified template(s)
        $template_ids = is_array($args['template_id']) ? $args['template_id'] : [$args['template_id']];
    } else {
        // Export all templates
        $template_ids = get_posts([
            'post_type' => 'bricks_template',
            'post_status' => 'any',
            'fields' => 'ids',
            'numberposts' => -1,
        ]);
    }

    $exported = 0;
    $errors = [];
    foreach ($template_ids as $template_id) {
        $post = get_post($template_id);
        if (!$post) continue;
        $slug = $post->post_name;
        $id = $post->ID;
        $title = $post->post_title;
        $safe_title = \BricksSync\Includes\Functions\BricksTemplates\brickssync_sanitize_filename($title);

        // Skip export if slug is empty
        if (empty($slug)) {
            \BricksSync\Includes\Functions\Debug\brickssync_log("Skipping export for template ID $id: slug is empty.", 'warning');
            continue;
        }

        // Replace placeholders
        $filename = str_replace(
            ['{slug}', '{id}', '{title}'],
            [$slug, $id, $safe_title],
            $file_pattern
        );

        $full_path = trailingslashit($storage_dir) . $filename;

        // Ensure the export directory exists
        if (!is_dir($storage_dir)) {
            if (!mkdir($storage_dir, 0775, true) && !is_dir($storage_dir)) {
                $errors[] = "Export directory does not exist and could not be created: $storage_dir";
                \BricksSync\Includes\Functions\Debug\brickssync_log("Export directory does not exist and could not be created: $storage_dir", 'error');
                continue;
            }
        }

        // Gather ALL post data
        $export_data = get_object_vars($post);
        // Export all meta as strings (serialize if not string)
        $raw_meta = get_post_meta($id);
        $meta = [];
        foreach ($raw_meta as $key => $values) {
            $val = $values[0];
            $meta[$key] = is_string($val) ? $val : serialize($val);
        }
        $export_data['meta'] = $meta;

        // Allow exclusion of certain meta/fields
        if (!empty($args['exclude']) && is_array($args['exclude'])) {
            foreach ($args['exclude'] as $exclude_key) {
                unset($export_data[$exclude_key]);
                unset($export_data['meta'][$exclude_key]);
            }
        }

        // Write JSON file
        $json = json_encode($export_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        if ($json === false) {
            $errors[] = "Failed to encode template ID $id as JSON.";
            \BricksSync\Includes\Functions\Debug\brickssync_log("Failed to encode template ID $id as JSON.", 'error');
            continue;
        }
        $result = file_put_contents($full_path, $json);
        if ($result === false) {
            $errors[] = "Failed to write file: $full_path";
            \BricksSync\Includes\Functions\Debug\brickssync_log("Failed to write file: $full_path", 'error');
            continue;
        }
        \BricksSync\Includes\Functions\Debug\brickssync_log("Exported template ID $id as $full_path", 'info');
        $exported++;
    }

    $msg = "$exported template(s) exported to $storage_dir.";
    if (!empty($errors)) {
        $msg .= " Errors: " . implode(' | ', $errors);
    }
    return $msg;
}