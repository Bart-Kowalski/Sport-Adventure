<?php
require_once dirname(__DIR__) . '/includes/functions/network_config.php'; // Load config helpers
// Handle reset override POST at the very top before any output
if (
    is_multisite() &&
    is_network_admin() &&
    isset($_POST['reset_override'], $_POST['site_id']) &&
    check_admin_referer('brickssync_reset_override')
) {
    $site_id = intval($_POST['site_id']);
    $network_config = brickssync_get_network_config();
    if (isset($network_config['site_overrides'][$site_id])) {
        unset($network_config['site_overrides'][$site_id]);
        brickssync_update_network_config($network_config);
    }
    // Redirect to the Configuration tab to avoid resubmission and blank state
    echo '<script>window.location.href = ' . json_encode(network_admin_url('settings.php?page=brickssync-network-admin&tab=config')) . ';</script>';
    exit;
}
/**
 * Admin Tab: Configuration (Sites Overview)
 *
 * This file now ONLY renders the 'Sites & Effective Configuration' table and per-site override UI for BricksSync.
 * The Global and Group configuration forms have been moved to their own tabs/files for clarity and maintainability.
 *
 * Maintainers: See global_config.php and group_config.php for those UIs.
 */

require_once dirname(__DIR__) . '/includes/functions/network_config.php'; // Load config helpers
require_once dirname(__DIR__) . '/includes/functions/ui_helpers.php'; // UI helpers for multisite config

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// --- Multisite Network Admin ---
// Handle reset override POST
if (
    is_multisite() &&
    is_network_admin() &&
    isset($_POST['reset_override'], $_POST['site_id']) &&
    check_admin_referer('brickssync_reset_override')
) {
    $site_id = intval($_POST['site_id']);
    $network_config = brickssync_get_network_config();
    if (isset($network_config['site_overrides'][$site_id])) {
        unset($network_config['site_overrides'][$site_id]);
        brickssync_update_network_config($network_config);
    }
    // Redirect to the Configuration tab to avoid resubmission and blank state
    echo '<script>window.location.href = ' . json_encode(network_admin_url('settings.php?page=brickssync-network-admin&tab=config')) . ';</script>';
    exit;
}

if (is_multisite() && is_network_admin()) {
    $network_config = brickssync_get_network_config();
    $sites = brickssync_get_all_sites();
    $groups = $network_config['groups'] ?? [];
    // Table: Show all sites and their effective config
    echo '<div class="brickssync-section" style="border:1px solid #e5e5e5;border-radius:8px;padding:20px 24px 24px 24px;margin-bottom:28px;background:#fafbfc;max-width:1200px;">';
    echo '<h2 style="margin-top:0;">Sites & Effective Configuration</h2>';
    echo '<p style="color:#555;max-width:700px;">Below you can review and override the effective BricksSync configuration for each site. Site-specific overrides take precedence over group/global settings. Use the <b>Override</b> button to customize config for a site, or <b>Reset</b> to remove an override.</p>';
    echo '<div style="overflow-x:auto; width:100%; margin-top:18px;">';
    echo '<div style="font-size:13px;color:#888;margin-bottom:4px;text-align:right;">&#8592; Scroll for more &#8594;</div>';
    echo '<table class="widefat striped" style="min-width:1100px;">'
        .'<thead><tr><th style="width:28px;"></th><th>Site</th><th>Group</th><th>Storage</th><th>Custom Path</th><th>Settings File</th><th>Templates Pattern</th><th>JSON Subdir</th><th>Excluded Options</th><th>Sync Mode</th><th>Automation</th><th>Enabled</th><th>Actions</th></tr></thead><tbody>';
    foreach ($sites as $site_id => $site) {
        $eff = brickssync_get_effective_site_config($site_id);
        // Find group
        $group_name = '-';
        foreach ($groups as $gid => $group) {
            if (in_array($site_id, $group['sites'] ?? [], true)) {
                $group_name = $group['name'];
                break;
            }
        }
        $override = isset($network_config['site_overrides'][$site_id]);
        // Row highlight if overridden
        $row_style = $override ? ' style="background:#fff3f0;"' : '';
        echo '<tr' . $row_style . '>';
        // Add a badge/icon for override status as the first column
        if ($override) {
            echo '<td><span style="display:inline-block;writing-mode:vertical-rl;transform:rotate(180deg);color:#fff;background:#d9534f;border-radius:3px;padding:6px 8px;font-size:10px;letter-spacing:0.5px;line-height:1;" title="Site-specific override is active">Override</span></td>';
        } else {
            echo '<td><span style="display:inline-block;writing-mode:vertical-rl;transform:rotate(180deg);color:#fff;background:#5cb85c;border-radius:3px;padding:6px 8px;font-size:10px;letter-spacing:0.5px;line-height:1;" title="No site-specific override">None</span></td>';
        }
        echo '<td>' . esc_html($site->blogname . ' (' . $site->domain . $site->path . ')') . '</td>';
        // Show group name with icon, strikethrough, and tooltip if override is active
        if ($override && $group_name !== '-') {
            echo '<td><span title="This group is overridden by a site-specific config.">⚠️ <s>' . esc_html($group_name) . '</s></span></td>';
        } else {
            echo '<td>' . esc_html($group_name) . '</td>';
        }
        echo '<td>' . esc_html($eff['json_storage_location']) . '</td>';
        echo '<td>' . esc_html($eff['custom_storage_location_path']) . '</td>';
        echo '<td>' . esc_html($eff['settings_file_name']) . '</td>';
        echo '<td>' . esc_html($eff['templates_file_pattern']) . '</td>';
        echo '<td>' . esc_html($eff['json_subdir']) . '</td>';
        echo '<td><div style="max-width:180px;overflow:auto;white-space:pre;">' . esc_html($eff['excluded_options']) . '</div></td>';
        echo '<td>' . esc_html($eff['sync_mode']) . '</td>';
        echo '<td>' . (!empty($eff['settings_sync_enabled']) ? 'Yes' : 'No') . '</td>';
        echo '<td>' . (!empty($eff['enabled']) ? 'Yes' : 'No') . '</td>';
        echo '<td>';
        // Only show buttons in the action column
        echo '<a href="' . esc_url(add_query_arg('override_site', $site_id)) . '" class="button">Override</a> ';
        if ($override) {
            echo '<form method="post" style="display:inline;">';
            wp_nonce_field('brickssync_reset_override');
            echo '<input type="hidden" name="site_id" value="' . esc_attr($site_id) . '">';
            echo '<input type="submit" name="reset_override" value="Reset" class="button">';
            echo '</form>';
        }
        echo '</td>';
        echo '</tr>';
        // If this site is being edited, show the override form in a new full-width row
        if (isset($_GET['override_site']) && intval($_GET['override_site']) === $site_id) {
            echo '<tr><td colspan="13" style="background:#f9f9f9;padding:0;">'; // colspan is still correct since we removed a column and added one at the start
            // Prefill override form with effective config if no override exists
            if (isset($network_config['site_overrides'][$site_id])) {
                $prefill = $network_config['site_overrides'][$site_id];
            } else {
                $prefill = function_exists('brickssync_get_effective_site_config') ? brickssync_get_effective_site_config($site_id) : [];
            }
            $form = new AltForms('brickssync_override_site_' . $site_id);
            $form->section('Override for ' . esc_html($site->blogname))
                ->input_select('json_storage_location', 'JSON Storage Location', [
                    'child_theme' => 'Child Theme',
                    'uploads_folder' => 'Uploads Folder',
                    'custom_url' => 'Custom Path',
                ], isset($prefill['json_storage_location']) ? $prefill['json_storage_location'] : '')
                ->input_text('custom_storage_location_path', 'Custom Storage Path', isset($prefill['custom_storage_location_path']) ? $prefill['custom_storage_location_path'] : '')
                ->input_text('settings_file_name', 'Settings Filename', isset($prefill['settings_file_name']) ? $prefill['settings_file_name'] : '')
                ->input_text('templates_file_pattern', 'Templates Filename Pattern', isset($prefill['templates_file_pattern']) ? $prefill['templates_file_pattern'] : '')
                ->input_text('json_subdir', 'JSON Subdirectory', isset($prefill['json_subdir']) ? $prefill['json_subdir'] : '')
                ->input_textarea('excluded_options', 'Excluded Options (one per line)', isset($prefill['excluded_options']) ? $prefill['excluded_options'] : '')
                ->input_select('sync_mode', 'Sync Mode', [
                    'automatic' => 'Automatic (two-way)',
                    'export' => 'Export only',
                    'import' => 'Import only',
                    'manual' => 'Manual',
                ], isset($prefill['sync_mode']) ? $prefill['sync_mode'] : '')
                ->input_checkbox('settings_sync_enabled', 'Enable Settings Sync Automation', !empty($prefill['settings_sync_enabled']) ? '1' : '')
                ->input_checkbox('enabled', 'Enable BricksSync for this site', !empty($prefill['enabled']) ? '1' : '')
                ->on_submit(function($data) use (&$network_config, $site_id) {
                    $network_config['site_overrides'][$site_id] = [
                        'json_storage_location' => $data['json_storage_location'],
                        'custom_storage_location_path' => $data['custom_storage_location_path'],
                        'settings_file_name' => $data['settings_file_name'],
                        'templates_file_pattern' => $data['templates_file_pattern'],
                        'json_subdir' => $data['json_subdir'],
                        'excluded_options' => $data['excluded_options'],
                        'sync_mode' => $data['sync_mode'],
                        'settings_sync_enabled' => !empty($data['settings_sync_enabled']),
                        'enabled' => !empty($data['enabled']),
                    ];
                    brickssync_update_network_config($network_config);
                    // JS redirect to avoid headers already sent error
                    $redirect_url = remove_query_arg('override_site');
                    echo '<script>window.location.href = ' . json_encode($redirect_url) . ';</script>';
                    exit;
                })
                ->handle();
            // Reload config after save to ensure UI reflects the latest state
            $network_config = brickssync_get_network_config();
            $form->render();
            echo '<a href="' . esc_url(remove_query_arg('override_site')) . '" class="button">Cancel</a>';
            echo '</td></tr>';
        }
    }
    echo '</tbody></table>';
    echo '</div>'; // close overflow-x div
    echo '</div>'; // close section
    return;
}
// --- Subsite: Show effective config, read-only ---
if (is_multisite() && !is_network_admin()) {
    $site_id = get_current_blog_id();
    $eff = brickssync_get_effective_site_config($site_id);
    echo '<div class="brickssync-section" style="border:1px solid #e5e5e5;border-radius:8px;padding:20px 24px 24px 24px;margin-bottom:28px;background:#fafbfc;max-width:700px;">';
    echo '<h2 style="margin-top:0;">Effective BricksSync Configuration</h2>';
    echo '<p style="color:#555;max-width:600px;">These are the resolved settings for this site, based on network, group, and site-specific configuration. Only the Network Administrator can make changes.</p>';
    echo '<table class="form-table" style="margin-top:16px;">';
    echo '<tr><th>Storage Location</th><td>' . esc_html($eff['json_storage_location'] ?? '') . '</td></tr>';
    echo '<tr><th>Custom Path</th><td>' . esc_html($eff['custom_storage_location_path'] ?? '') . '</td></tr>';
    echo '<tr><th>Settings File Name</th><td>' . esc_html($eff['settings_file_name'] ?? '') . '</td></tr>';
    echo '<tr><th>Templates File Pattern</th><td>' . esc_html($eff['templates_file_pattern'] ?? '') . '</td></tr>';
    echo '<tr><th>JSON Subdirectory</th><td>' . esc_html($eff['json_subdir'] ?? '') . '</td></tr>';
    echo '<tr><th>Excluded Options</th><td><div style="max-width:300px;overflow:auto;white-space:pre;">' . esc_html($eff['excluded_options'] ?? '') . '</div></td></tr>';
    echo '<tr><th>Sync Mode</th><td>' . esc_html($eff['sync_mode'] ?? '') . '</td></tr>';
    echo '<tr><th>Automation Enabled</th><td>' . (!empty($eff['settings_sync_enabled']) ? 'Yes' : 'No') . '</td></tr>';
    echo '<tr><th>Enabled</th><td>' . (!empty($eff['enabled']) ? 'Yes' : 'No') . '</td></tr>';
    echo '</table>';
    echo '<p style="color:#888;font-size:13px;">This configuration is resolved from network/global/group/site settings.<br>Only the Network Administrator can make changes. If you need changes, please contact your network admin.</p>';
    echo '</div>';
    return;
}
// --- Single Site: Use existing config form ---
$config_form = new AltForms('brickssync_config_settings'); // Changed slug slightly for clarity

/**
 * Custom Path Validation
 *
 * Validates the custom storage path when selected.
 * Checks:
 * - Path is not empty when custom location is selected
 * - Path is valid and writable (TODO: implement)
 *
 * @param string $value The submitted path value
 * @param array $submitted_data The complete form submission data
 * @return string|null Error message if validation fails, null if valid
 */
$validate_custom_path = function($value, $submitted_data) {
    // Only validate if 'custom_url' is selected
    if (isset($submitted_data['brickssync_json_storage_location']) && $submitted_data['brickssync_json_storage_location'] === 'custom_url') {
        if (empty(trim($value))) {
            return 'Custom Storage Path cannot be empty when "Use custom settings" is selected.';
        }
        // TODO: Add more robust validation: check if path is valid, writable, etc.
        /*
        if (!is_dir($value) || !wp_is_writable($value)) {
             return 'Custom Storage Path is not a valid or writable directory.';
        }
        */
    }
    return null; // No error
};

// Get upload dir info safely
$upload_dir_info = wp_get_upload_dir();
$upload_dir_path = $upload_dir_info['basedir'] ?? '[Error retrieving uploads path]';

/**
 * Form Configuration
 *
 * Sets up the form structure with three main sections:
 * 1. Storage Settings
 *    - Child theme location (default)
 *    - Uploads folder location
 *    - Custom path option
 * 2. File Naming & Options
 *    - Settings filename
 *    - Template filename pattern
 *    - Excluded options list
 *    - JSON subdirectory
 * 3. Automation Settings
 *    - Automatic mode (two-way sync)
 *    - Export only mode
 *    - Import only mode
 *    - Manual mode
 */
$config_form
    // ->input_hidden('test', '12345') // Removed unused test field
    ->section('Storage Settings') // Section title for clarity
    ->start_radio_group('JSON Storage location')
        ->input_radio('brickssync_json_storage_location', 'Save in active child theme ('.wp_get_theme()->get('Name').')', 'child_theme')
        ->content('<p class="description">Path: '.esc_html(get_stylesheet_directory()).'</p><p class="description">Recommended default. Stores JSON files in your child theme folder, keeping them version controlled with your theme.</p><br/>')

        ->input_radio('brickssync_json_storage_location', 'Save in uploads folder', 'uploads_folder')
        ->content('<p class="description">Path: '.esc_html($upload_dir_path).'</p><p class="description">Stores JSON files in the WordPress uploads directory. Useful if you don\'t use a child theme or want to separate data from theme files.</p><br/>')
        
        ->input_radio('brickssync_json_storage_location', 'Use custom settings', 'custom_url', [
            'toggles' => ['brickssync_custom_storage_location_path']
        ])
        ->input_text('brickssync_custom_storage_location_path', 'Custom Storage path', '', ['validation_callback' => $validate_custom_path])
        ->content('<p class="description"><span style="color:#888888;">Example path: '.esc_html(get_stylesheet_directory()).'/brickssync-json</span></p>')
    ->end_radio_group()

    ->content_full_width('<hr/>') // Visual separation

    ->section('File Naming & Options') // Section title
    ->input_text('brickssync_settings_file_name', 'Bricks Builder settings filename', 'bricks-builder-settings.json')
    ->content('<p class="description">Filename for the exported Bricks global settings.</p>')
    ->input_text('brickssync_templates_file_pattern', 'Templates filename pattern','template-{slug}.json')
    ->content('<p class="description">Pattern for template filenames. Use <code>{slug}</code> or <code>{id}</code> as placeholders.</p>')
    ->input_text('brickssync_json_subdir', 'JSON subdirectory', 'brickssync-json')
    ->content('<p class="description">Subdirectory inside the storage location for all BricksSync JSON files. Default is <code>brickssync-json</code>.</p>')
    ->input_textarea('brickssync_excluded_options', 'Excluded options (one per line)', "bricks_license_key\nbricks_support_ HASH")
    ->content('<p class="description">List WordPress option keys (one per line) to exclude from the settings export (e.g., license keys).</p>')

    ->content_full_width('<hr/>')

    ->section('Automation Settings') // Section title
    ->start_radio_group('Sync mode')
        ->input_radio('brickssync_sync_mode', '<strong>Automatic mode</strong> (two-way sync)', 'automatic')
        ->content('<p class="description">Periodically checks for changes in both the database and JSON files, syncing them automatically. (Runs via WP-Cron)</p>')

        ->input_radio('brickssync_sync_mode', '<strong>Export only mode</strong> (database → files)', 'export')
        ->content('<p class="description">Periodically exports changes from the database to JSON files automatically. (Runs via WP-Cron)</p>')

        ->input_radio('brickssync_sync_mode', '<strong>Import only mode</strong> (files → database)', 'import')
        ->content('<p class="description">Periodically imports changes from JSON files into the database automatically. (Runs via WP-Cron)</p>')

        ->input_radio('brickssync_sync_mode', '<strong>Manual mode</strong> (no automation)', 'manual')
        ->content('<p class="description">Disables automatic syncing. Use the manual import/export buttons on the other tabs.</p>')
    ->end_radio_group()

    ->input_checkbox('brickssync_settings_sync_enabled', 'Enable settings sync automation', 1, [
        'checked' => get_option('brickssync_settings_sync_enabled', '1') === '1',
        'description' => 'If checked, Bricks settings will be synchronized according to the selected sync mode. If unchecked, settings will only be synced manually. <br><strong>Note:</strong> This only affects Bricks <em>settings</em> (global options). Template sync is always controlled by the main sync mode.'
    ])
    ->content('<p class="description" style="margin-top:-12px; color:#666;">Disabling this option is useful if you want to automate template sync but prefer to manage global Bricks settings manually (for example, to avoid overwriting environment-specific settings).</p>')
    ->content_full_width('<hr/>')
    ->handle()
    ->success_message('Configuration saved successfully.'); // Custom success message for this form

// --- New UI Wrapper ---
echo '<h2 style="margin-top:0;">BricksSync Plugin Configuration</h2>';
echo '<p style="max-width:700px; color:#555;">Configure storage location, file naming, and automation options for BricksSync. These settings control how and where your data is saved and synchronized.</p>';
echo '<div class="brickssync-section" style="border:1px solid #e5e5e5; border-radius:8px; padding:20px; margin-bottom:28px; background:#fafbfc;">';
echo '<h3>Configuration Settings</h3>';
$config_form->render();
echo '</div>';