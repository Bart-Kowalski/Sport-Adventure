<?php
/**
 * BricksSync UI Helpers for Multisite Config
 *
 * Provides helper functions for generating multi-select site lists, group slug creation, etc.
 */
if (!defined('ABSPATH')) exit;

/**
 * Render a multi-select or checkbox list of all sites.
 * @param string $name The field name.
 * @param array $selected_ids Array of selected site IDs.
 * @return string HTML
 */
function brickssync_render_sites_multiselect($name, $selected_ids = []) {
    $sites = brickssync_get_all_sites();
    $html = '<select name="' . esc_attr($name) . '[]" multiple size="' . min(10, count($sites)) . '" style="width:100%;">';
    foreach ($sites as $site_id => $site) {
        $selected = in_array($site_id, $selected_ids) ? 'selected' : '';
        $label = esc_html($site->blogname . ' (' . $site->domain . $site->path . ')');
        $html .= '<option value="' . esc_attr($site_id) . '" ' . $selected . '>' . $label . '</option>';
    }
    $html .= '</select>';
    return $html;
}

/**
 * Generate a slug from a group name.
 * @param string $name
 * @return string
 */
function brickssync_group_slug($name) {
    $slug = strtolower(trim(preg_replace('/[^a-zA-Z0-9]+/', '-', $name), '-'));
    return $slug ?: uniqid('group_');
}
