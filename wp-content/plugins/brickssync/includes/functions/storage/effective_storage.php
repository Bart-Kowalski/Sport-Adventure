<?php
/**
 * BricksSync Effective Storage Path Helper
 *
 * Returns the correct storage path status for the current site, using the resolved network config on multisite subsites.
 *
 * @return array Same structure as brickssync_get_storage_path_status()
 */
function brickssync_get_effective_storage_path_status($site_id = null) {
    if (function_exists('is_multisite') && is_multisite() && !is_network_admin()) {
        if (!$site_id) $site_id = get_current_blog_id();
        if (!function_exists('brickssync_get_effective_site_config')) {
            require_once dirname(__DIR__, 2) . '/functions/network_config.php';
        }
        $eff = brickssync_get_effective_site_config($site_id);
        $location = $eff['json_storage_location'] ?? '';
        $subdir = trim($eff['json_subdir'] ?? 'brickssync-json') ?: 'brickssync-json';
        $path = null;
        error_log('BricksSync DEBUG: [site_id=' . $site_id . '] effective config: ' . print_r($eff, true));
        error_log('BricksSync DEBUG: [site_id=' . $site_id . '] storage location: ' . $location);
        switch ($location) {
            case 'child_theme':
                // Ensure correct theme directory for subsite
                if (function_exists('switch_to_blog')) switch_to_blog($site_id);
                $path = function_exists('get_stylesheet_directory') ? get_stylesheet_directory() : '';
                if (function_exists('restore_current_blog')) restore_current_blog();
                break;
            case 'uploads_folder':
                $upload_dir = wp_get_upload_dir();
                $path = $upload_dir['basedir'] ?? null;
                break;
            case 'custom_url':
                $path = $eff['custom_storage_location_path'] ?? '';
                break;
        }
        if (empty($path)) {
            return [
                'path' => null,
                'status' => 'error',
                'message' => __('Could not determine storage path from effective config.', 'brickssync'),
                'is_readable' => false,
                'is_writable' => false,
            ];
        }
        $path = trailingslashit($path) . trailingslashit($subdir);
        error_log('BricksSync DEBUG: [site_id=' . $site_id . '] final resolved path: ' . $path);
        $created = false;
        if (!is_dir($path)) {
            if (@mkdir($path, 0775, true) && is_dir($path)) {
                $created = true;
            }
        }
        $is_readable = is_readable($path);
        $is_writable = wp_is_writable($path);
        $status = ($is_readable && $is_writable) ? 'ok' : (!$is_readable ? 'not_readable' : 'not_writable');
        if ($status === 'ok') {
            $message = __('Storage path configured and accessible:', 'brickssync') . ' ' . esc_html($path);
            if ($created) {
                $message .= ' (directory was missing and has been created)';
            }
        } else {
            $message = __('Configured storage path has issues:', 'brickssync') . ' ' . esc_html($path);
            if (!is_dir($path)) {
                $message .= ' (directory does not exist and could not be created)';
            }
        }
        return compact('path', 'status', 'message', 'is_readable', 'is_writable');
    } else {
        if (!function_exists('BricksSync\\Includes\\Functions\\Storage\\brickssync_get_storage_path_status')) {
            require_once __DIR__ . '/storage.php';
        }
        return \BricksSync\Includes\Functions\Storage\brickssync_get_storage_path_status();
    }
}
