<?php
/**
 * BricksSync Network Config Utilities
 *
 * Handles global, group, and per-site configuration for multisite.
 * Only loaded/used in multisite.
 */

if (!defined('ABSPATH')) exit;

/**
 * Get the full BricksSync network config array.
 * @return array
 */
function brickssync_get_network_config() {
    $default = [
        'global' => [
            'json_storage_location' => 'child_theme',
            'custom_storage_location_path' => '',
            'settings_file_name' => 'bricks-builder-settings.json',
            'templates_file_pattern' => 'template-{slug}.json',
            'json_subdir' => 'brickssync-json',
            'excluded_options' => "bricks_license_key\nbricks_support_ HASH",
            'sync_mode' => 'automatic',
            'settings_sync_enabled' => true,
            'enabled' => true,
        ],
        'groups' => [], // group_slug => [name, sites[], all config fields]
        'site_overrides' => [], // site_id => [all config fields]
    ];
    return get_site_option('brickssync_network_config', $default);
} 

/**
 * Update the BricksSync network config array.
 * @param array $config
 */
function brickssync_update_network_config($config) {
    update_site_option('brickssync_network_config', $config);
}

/**
 * Get the effective config for a site (site_id).
 * Resolution order: site override > group > global.
 * @param int $site_id
 * @return array
 */
function brickssync_get_effective_site_config($site_id) {
    $config = brickssync_get_network_config();
    // Start with global
    $result = $config['global'];
    // Find group
    $found_group = null;
    foreach ($config['groups'] as $slug => $group) {
        if (in_array($site_id, $group['sites'] ?? [], true)) {
            $found_group = $group;
            break;
        }
    }
    if ($found_group) {
        foreach ($found_group as $k => $v) {
            if ($k !== 'sites' && $k !== 'name') {
                $result[$k] = $v;
            }
        }
    }
    // Per-site override
    if (!empty($config['site_overrides'][$site_id])) {
        foreach ($config['site_overrides'][$site_id] as $k => $v) {
            $result[$k] = $v;
        }
    }
    return $result;
}

/**
 * Get all sites in the network (for admin UI).
 * @return array
 */
function brickssync_get_all_sites() {
    if (!function_exists('get_sites')) return [];
    $sites = get_sites(['number' => 0]);
    $out = [];
    foreach ($sites as $site) {
        $out[$site->blog_id] = $site;
    }
    return $out;
}
