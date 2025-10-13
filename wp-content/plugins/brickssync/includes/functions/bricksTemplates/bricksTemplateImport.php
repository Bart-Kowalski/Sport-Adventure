<?php
/**
 * Bricks Templates Import Functions
 *
 * This file contains functions for importing Bricks Builder templates from JSON files.
 * It provides functionality for:
 * - Importing individual or multiple templates
 * - Validating imported templates
 * - Managing import configurations
 * - Error handling and logging
 *
 * The file includes functions for:
 * - Importing templates from specified locations
 * - Validating import operations
 * - Logging import activities
 * - Error handling and reporting
 *
 * @package BricksSync\Includes\Functions\BricksTemplates
 * @since 0.1
 */

use function Brickssync\Includes\Functions\Debug\brickssync_log;
use function BricksSync\Includes\Functions\Storage\brickssync_get_storage_path;

require_once dirname(__DIR__, 2) . '/functions/storage/storage.php';

require_once __DIR__ . '/importRecord.php';

/**
 * Bricks Templates Importer
 * 
 * Imports Bricks Builder templates from JSON files.
 * This function handles:
 * - Reading template data from source files
 * - Validating imported templates
 * - Creating/updating templates in Bricks
 * - Error handling and logging
 *
 * Features:
 * - Supports single or bulk template import
 * - Validates templates before import
 * - Provides detailed error messages
 * - Logs import operations
 *
 * @since 0.1
 * @param array $args {
 *     Optional. Import configuration arguments.
 *
 *     @type string $source_dir  Path to import directory. Default uses configured storage path.
 *     @type bool   $overwrite   Whether to overwrite existing templates. Default false.
 *     @type array  $exclude     Array of templates to exclude from import.
 *     @type int    $template_id ID of a single template to import.
 *     @type string $source_file Filename of a single template to import.
 * }
 * @return string|WP_Error Success message or WP_Error on failure
 */
function brickssync_bricks_template_import($args = []) {
    // 1. Resolve source dir to match export logic (no extra subdir)
    // Use effective storage path for multisite subsites
    if (function_exists('is_multisite') && is_multisite() && !is_network_admin()) {
        require_once dirname(__DIR__, 2) . '/functions/storage/effective_storage.php';
        $site_id = get_current_blog_id();
        $path_status = brickssync_get_effective_storage_path_status($site_id);
        $source_dir = $path_status['path'] ?? '';
        brickssync_log('Import using brickssync_get_effective_storage_path_status() returned: ' . $source_dir, 'info');
    } else {
        $source_dir = brickssync_get_storage_path();
        brickssync_log('Import using brickssync_get_storage_path() returned: ' . $source_dir, 'info');
    }
    if (!$source_dir || !is_dir($source_dir)) {
        // Try fallback to child theme directory + subdir if storage path is still empty
        $child_theme_dir = function_exists('get_stylesheet_directory') ? get_stylesheet_directory() : null;
        $subdir = get_option('brickssync_json_subdir', 'brickssync-json');
        if ($child_theme_dir) {
            $fallback_dir = trailingslashit($child_theme_dir) . trailingslashit($subdir);
            if (is_dir($fallback_dir)) {
                $source_dir = $fallback_dir;
                brickssync_log('Import fallback: using child theme + subdir: ' . $source_dir, 'info');
            }
        }
    }
    brickssync_log('Resolved source_dir: ' . $source_dir, 'info');
    if (!$source_dir || !is_dir($source_dir)) {
        brickssync_log('Source directory not found: ' . $source_dir, 'error');
        return new WP_Error('import_failed', 'Source directory not found.');
    }
    $overwrite = $args['overwrite'] ?? false;
    $exclude = $args['exclude'] ?? [];
    $single_file = $args['source_file'] ?? null;
    $single_template = $args['template_id'] ?? null;
    $imported = 0;
    $errors = [];

    // 2. Get import record
    $import_record = brickssync_get_template_import_record();
    brickssync_log('Import record loaded: ' . print_r($import_record, true), 'debug');

    // 3. Gather files to import (all .json except settings file)
    $settings_filename = get_option('brickssync_settings_file_name', 'bricks-builder-settings.json');
    $files = array_filter(glob(trailingslashit($source_dir) . '*.json'), function($f) use ($settings_filename) {
        return basename($f) !== $settings_filename && is_file($f);
    });
    brickssync_log('Found files: ' . print_r($files, true), 'info');
    if ($single_file) {
        $files = array_filter($files, function($f) use ($single_file) {
            return basename($f) === $single_file;
        });
    } elseif ($single_template) {
        $files = array_filter($files, function($f) use ($single_template) {
            return strpos(basename($f), (string)$single_template) !== false;
        });
    }
    if (!empty($exclude)) {
        $files = array_filter($files, function($f) use ($exclude) {
            foreach ($exclude as $ex) {
                if (strpos(basename($f), (string)$ex) !== false) return false;
            }
            return true;
        });
        brickssync_log('Filtered for exclude: ' . print_r($exclude, true) . ' => ' . print_r($files, true), 'info');
    }

    foreach ($files as $file) {
        $basename = basename($file);
        $mtime = filemtime($file);
        $hash = md5_file($file);
        $record = $import_record[$basename] ?? null;
        $changed = !$record || $record['mtime'] !== $mtime || $record['hash'] !== $hash;
        brickssync_log("Processing $basename | mtime: $mtime | hash: $hash | changed: " . ($changed ? 'yes' : 'no') . ", overwrite: " . ($overwrite ? 'yes' : 'no'), 'debug');
        if (!$changed && !$overwrite) {
            brickssync_log("Skipping $basename (not changed, not forced)", 'info');
            continue;
        }
        // 4. Read and decode JSON
        $json = file_get_contents($file);
        $data = json_decode($json, true);
        if (!$data) {
            $errors[] = "Invalid or missing data in $basename";
            brickssync_log("Invalid or missing data in $basename", 'error');
            continue;
        }
        // 5. Import logic (create/update post by old ID mapping, then filename, then slug)
        $old_id = $data['ID'] ?? null;
        $post_title = $data['post_title'] ?? pathinfo($basename, PATHINFO_FILENAME);
        $post_content = $data['post_content'] ?? '';
        $post_status = $data['post_status'] ?? 'publish';
        $post_type = $data['post_type'] ?? 'bricks_template';
        // 1. Try to match by import record old_id mapping
        $matched_id = null;
        foreach ($import_record as $rec) {
            if (isset($rec['old_id']) && $old_id && $rec['old_id'] == $old_id && !empty($rec['new_id']) && get_post_status($rec['new_id'])) {
                $matched_id = $rec['new_id'];
                break;
            }
        }
        // 2. Fallback: match by _brickssync_import_file (legacy)
        if (!$matched_id) {
            $existing = get_posts([
                'post_type' => $post_type,
                'meta_key' => '_brickssync_import_file',
                'meta_value' => $basename,
                'posts_per_page' => 1,
                'post_status' => 'any',
                'fields' => 'ids',
            ]);
            if ($existing) $matched_id = $existing[0];
        }
        // 3. Fallback: match by slug
        if (!$matched_id) {
            $slug = $data['post_name'] ?? pathinfo($basename, PATHINFO_FILENAME);
            $by_slug = get_posts([
                'post_type' => $post_type,
                'name' => $slug,
                'posts_per_page' => 1,
                'post_status' => 'any',
                'fields' => 'ids',
            ]);
            if ($by_slug) $matched_id = $by_slug[0];
        }
        $postarr = [
            'post_title' => $post_title,
            'post_content' => $post_content,
            'post_status' => $post_status,
            'post_type' => $post_type,
        ];
        if ($matched_id) {
            $postarr['ID'] = $matched_id;
            $post_id = wp_update_post($postarr);
            brickssync_log("Updated post ID $post_id for $basename (matched by mapping/filename/slug)", 'info');
        } else {
            $post_id = wp_insert_post($postarr);
            brickssync_log("Inserted new post ID $post_id for $basename", 'info');
        }
        if (is_wp_error($post_id)) {
            $errors[] = "Failed to import $basename: " . $post_id->get_error_message();
            brickssync_log("Failed to import $basename: " . $post_id->get_error_message(), 'error');
            continue;
        }
        // Always update the meta for filename
        update_post_meta($post_id, '_brickssync_import_file', $basename);
        // Import all meta keys from JSON (smart: unserialize if serialized string, else store as-is or let WP serialize)
        foreach ($data['meta'] as $meta_key => $meta_value) {
            if (is_string($meta_value) && is_serialized($meta_value)) {
                $unserialized = @unserialize($meta_value);
                brickssync_log("Unserializing meta $meta_key before import.", 'debug');
                update_post_meta($post_id, $meta_key, $unserialized);
            } else {
                brickssync_log("Importing meta $meta_key as-is (type: ".gettype($meta_value).")", 'debug');
                update_post_meta($post_id, $meta_key, $meta_value);
            }
        }
        brickssync_log("Imported meta for post ID $post_id from $basename: " . print_r(array_keys($data['meta']), true), 'debug');
        // Update import record with old_id <-> new_id mapping
        $import_record[$basename] = [
            'old_id' => $old_id,
            'new_id' => $post_id,
            'mtime' => $mtime,
            'hash' => $hash,
            'last_imported' => time(),
        ];
        brickssync_update_template_import_record($import_record);
        brickssync_update_template_import_record_entry($basename, $mtime, $hash);
        $imported++;
    }
    $msg = "$imported template(s) imported from $source_dir.";
    if (!empty($errors)) {
        $msg .= " Errors: " . implode(' | ', $errors);
        brickssync_log('Import completed with errors: ' . print_r($errors, true), 'error');
    } else {
        brickssync_log('Import completed successfully. Imported: ' . $imported, 'info');
    }
    return $msg;
}