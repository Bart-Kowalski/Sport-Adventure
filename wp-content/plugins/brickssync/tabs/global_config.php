<?php
/**
 * Admin Tab: Global Configuration
 *
 * Renders the global configuration form for BricksSync multisite.
 */

require_once dirname(__DIR__) . '/includes/functions/network_config.php';
if (!defined('ABSPATH')) exit;

if (is_multisite() && is_network_admin()) {
    $network_config = brickssync_get_network_config();
    $global_form = new AltForms('brickssync_global_config');
    $global_form->section('Global Settings')
        ->input_select('json_storage_location', 'JSON Storage Location', [
            'child_theme' => 'Child Theme',
            'uploads_folder' => 'Uploads Folder',
            'custom_url' => 'Custom Path',
        ], $network_config['global']['json_storage_location'])
        ->input_text('custom_storage_location_path', 'Custom Storage Path', $network_config['global']['custom_storage_location_path'])
        ->input_text('settings_file_name', 'Settings Filename', $network_config['global']['settings_file_name'])
        ->input_text('templates_file_pattern', 'Templates Filename Pattern', $network_config['global']['templates_file_pattern'])
        ->input_text('json_subdir', 'JSON Subdirectory', $network_config['global']['json_subdir'])
        ->input_textarea('excluded_options', 'Excluded Options (one per line)', $network_config['global']['excluded_options'])
        ->input_select('sync_mode', 'Sync Mode', [
            'automatic' => 'Automatic (two-way)',
            'export' => 'Export only',
            'import' => 'Import only',
            'manual' => 'Manual',
        ], $network_config['global']['sync_mode'])
        ->input_checkbox('settings_sync_enabled', 'Enable Settings Sync Automation', !empty($network_config['global']['settings_sync_enabled']) ? '1' : '')
        ->input_checkbox('enabled', 'Enable BricksSync for all sites', !empty($network_config['global']['enabled']) ? '1' : '')
        ->success_message('Global configuration saved.');
    $global_form->on_submit(function($data) use (&$network_config) {
        $network_config['global']['json_storage_location'] = $data['json_storage_location'];
        $network_config['global']['custom_storage_location_path'] = $data['custom_storage_location_path'];
        $network_config['global']['settings_file_name'] = $data['settings_file_name'];
        $network_config['global']['templates_file_pattern'] = $data['templates_file_pattern'];
        $network_config['global']['json_subdir'] = $data['json_subdir'];
        $network_config['global']['excluded_options'] = $data['excluded_options'];
        $network_config['global']['sync_mode'] = $data['sync_mode'];
        $network_config['global']['settings_sync_enabled'] = !empty($data['settings_sync_enabled']);
        $network_config['global']['enabled'] = !empty($data['enabled']);
        brickssync_update_network_config($network_config);
        // JS redirect to avoid headers already sent error and ensure UI updates
        $redirect_url = remove_query_arg('override_site');
        echo '<script>window.location.href = ' . json_encode($redirect_url) . ';</script>';
        exit;
    });
    $global_form->handle();
    // Reload config after save to ensure UI reflects the latest state
    $network_config = brickssync_get_network_config();
    echo '<div class="brickssync-section" style="border:1px solid #e5e5e5;border-radius:8px;padding:28px 32px 28px 32px;margin-bottom:28px;background:#fafbfc;max-width:700px;">';
    echo '<h2 style="margin-top:0;">BricksSync Global Configuration <span style="font-size:0.7em;color:#888;vertical-align:middle;">v' . esc_html(constant('BRICKSSYNC_VERSION')) . '</span></h2>';
    echo '<p style="color:#555;max-width:600px;">Configure the default/global BricksSync settings for all sites in the network. These settings are used unless overridden by a group or site-specific config.</p>';
    echo '<div class="brickssync-section-content">';
    $global_form->render();
    echo '</div>';
    echo '</div>';
}
