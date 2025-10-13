<?php
/**
 * Admin Tab: Group Configuration
 *
 * Renders the group management UI for BricksSync multisite.
 */

require_once dirname(__DIR__) . '/includes/functions/network_config.php';
require_once dirname(__DIR__) . '/includes/functions/ui_helpers.php';
if (!defined('ABSPATH')) exit;

if (is_multisite() && is_network_admin()) {
    $network_config = brickssync_get_network_config();
    $sites = brickssync_get_all_sites();
    $groups = $network_config['groups'];
    // Add/Edit Group Form
    $editing_group = null;
    if (isset($_GET['edit_group'])) {
        $slug = sanitize_text_field($_GET['edit_group']);
        $editing_group = $groups[$slug] ?? null;
    }
    $group_form = new AltForms('brickssync_group_form');
    $group_form->section($editing_group ? 'Edit Group' : 'Add Group')
        ->input_text('name', 'Group Name', $editing_group['name'] ?? '')
        ->input_select('json_storage_location', 'JSON Storage Location', [
            'child_theme' => 'Child Theme',
            'uploads_folder' => 'Uploads Folder',
            'custom_url' => 'Custom Path',
        ], $editing_group['json_storage_location'] ?? $network_config['global']['json_storage_location'])
        ->input_text('custom_storage_location_path', 'Custom Storage Path', $editing_group['custom_storage_location_path'] ?? $network_config['global']['custom_storage_location_path'])
        ->input_text('settings_file_name', 'Settings Filename', $editing_group['settings_file_name'] ?? $network_config['global']['settings_file_name'])
        ->input_text('templates_file_pattern', 'Templates Filename Pattern', $editing_group['templates_file_pattern'] ?? $network_config['global']['templates_file_pattern'])
        ->input_text('json_subdir', 'JSON Subdirectory', $editing_group['json_subdir'] ?? $network_config['global']['json_subdir'])
        ->input_textarea('excluded_options', 'Excluded Options (one per line)', $editing_group['excluded_options'] ?? $network_config['global']['excluded_options'])
        ->input_select('sync_mode', 'Sync Mode', [
            'automatic' => 'Automatic (two-way)',
            'export' => 'Export only',
            'import' => 'Import only',
            'manual' => 'Manual',
        ], $editing_group['sync_mode'] ?? $network_config['global']['sync_mode'])
        ->input_checkbox('settings_sync_enabled', 'Enable Settings Sync Automation', !empty($editing_group['settings_sync_enabled']) ? '1' : '')
        ->input_checkbox('enabled', 'Enable BricksSync for this group', !empty($editing_group['enabled']) ? '1' : '')
        ->content('<label>Assign Sites to Group:</label>' . brickssync_render_sites_multiselect('sites', $editing_group['sites'] ?? []));
    $group_form->on_submit(function($data) use (&$network_config) {
        $slug = brickssync_group_slug($data['name']);
        // Manually fetch sites from $_POST because AltForms content() fields are not included in $data
        $sites = isset($_POST['sites']) ? array_map('intval', (array)$_POST['sites']) : [];
        $network_config['groups'][$slug] = [
            'name' => $data['name'],
            'json_storage_location' => $data['json_storage_location'],
            'custom_storage_location_path' => $data['custom_storage_location_path'],
            'settings_file_name' => $data['settings_file_name'],
            'templates_file_pattern' => $data['templates_file_pattern'],
            'json_subdir' => $data['json_subdir'],
            'excluded_options' => $data['excluded_options'],
            'sync_mode' => $data['sync_mode'],
            'settings_sync_enabled' => !empty($data['settings_sync_enabled']),
            'enabled' => !empty($data['enabled']),
            'sites' => $sites,
        ];
        brickssync_update_network_config($network_config);
        // JS redirect to avoid double-submit and ensure UI updates
        $redirect_url = remove_query_arg(['edit_group']);
        echo '<script>window.location.href = ' . json_encode($redirect_url) . ';</script>';
        exit;
    });
    $group_form->handle();
    // Delete Group
    if (isset($_POST['delete_group']) && isset($_POST['group_id'])) {
        check_admin_referer('brickssync_delete_group');
        unset($network_config['groups'][$_POST['group_id']]);
        brickssync_update_network_config($network_config);
        // JS redirect to avoid double-delete and update UI
        $redirect_url = remove_query_arg(['edit_group']);
        echo '<script>window.location.href = ' . json_encode($redirect_url) . ';</script>';
        exit;
    }
    echo '<div class="brickssync-section" style="border:1px solid #e5e5e5;border-radius:8px;padding:28px 32px 28px 32px;margin-bottom:28px;background:#fafbfc;max-width:900px;">';
    echo '<h2 style="margin-top:0;">Groups</h2>';
    echo '<p style="color:#555;max-width:650px;">Groups allow you to apply shared BricksSync settings to multiple sites. Sites inherit group settings unless overridden. Edit or add groups below, and assign sites to each group.</p>';
    echo '<div class="brickssync-section" style="border:1px solid #e5e5e5;border-radius:8px;padding:24px 28px 24px 28px;margin-bottom:28px;background:#fafbfc;max-width:900px;">';
    echo '<h3 style="margin-top:0;">Add/Edit Group</h3>';
    $group_form->render();
    echo '</div>';
    if (!empty($groups)) {
        echo '<div class="brickssync-section" style="border:1px solid #e5e5e5;border-radius:8px;padding:24px 28px 24px 28px;margin-bottom:28px;background:#fafbfc;max-width:1000px;">';
        echo '<h3 style="margin-top:0;">Existing Groups</h3>';
        echo '<table class="widefat striped" style="margin-top:12px;"><thead><tr><th>ID</th><th>Name</th><th>Path</th><th>Sync Mode</th><th>Enabled</th><th>Sites</th><th>Actions</th></tr></thead><tbody>';
        foreach ($groups as $gid => $group) {
            echo '<tr>';
            echo '<td>' . esc_html($gid) . '</td>';
            echo '<td>' . esc_html($group['name']) . '</td>';
            echo '<td>' . (isset($group['path']) ? esc_html($group['path']) : '-') . '</td>';
            echo '<td>' . esc_html($group['sync_mode']) . '</td>';
            echo '<td>' . (!empty($group['enabled']) ? 'Yes' : 'No') . '</td>';
            // Show assigned sites as name + url, one per line
            $site_lines = [];
            foreach (($group['sites'] ?? []) as $sid) {
                if (isset($sites[$sid])) {
                    $site_lines[] = esc_html($sites[$sid]->blogname . ' (' . $sites[$sid]->domain . $sites[$sid]->path . ')');
                } else {
                    $site_lines[] = esc_html($sid);
                }
            }
            echo '<td><div style="white-space:pre-line;">' . implode("\n", $site_lines) . '</div></td>';
            echo '<td>';
            // Edit button
            echo '<a href="' . esc_url(add_query_arg('edit_group', $gid)) . '" class="button">Edit</a> ';
            // Delete form
            echo '<form method="post" style="display:inline;">';
            wp_nonce_field('brickssync_delete_group');
            echo '<input type="hidden" name="group_id" value="' . esc_attr($gid) . '">';
            echo '<input type="submit" name="delete_group" value="Delete" class="button">';
            echo '</form>';
            echo '</td>';
            echo '</tr>';
        }
        echo '</tbody></table>';
    }
}
