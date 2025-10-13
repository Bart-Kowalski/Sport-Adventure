<?php
require_once dirname(__DIR__) . '/includes/functions/network_config.php';
/**
 * Admin Tab: Settings
 *
 * Handles the Bricks Builder settings management interface in the BricksSync admin.
 * Provides UI for:
 * - Exporting Bricks global settings to JSON files
 * - Importing settings from JSON files
 * - Validating storage path and file accessibility
 * - Displaying configuration notices and warnings
 *
 * Uses AltForms for form handling and relies on BricksSync settings import/export functions.
 *
 * @package BricksSync\Admin\Tabs
 * @since 0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Multisite subsite: use resolved config from network admin
if (is_multisite() && !is_network_admin()) {
    $eff = brickssync_get_effective_site_config(get_current_blog_id());
    $storage_location = $eff['json_storage_location'] ?? '';
    $sync_mode = $eff['sync_mode'] ?? '';
    $settings_file_name = $eff['settings_file_name'] ?? 'bricks-builder-settings.json';
    require_once dirname(__DIR__) . '/includes/functions/storage/effective_storage.php';
    $site_id = get_current_blog_id();
    $path_status = brickssync_get_effective_storage_path_status($site_id);
    echo '<div class="notice notice-info"><p>This site uses configuration inherited from the network admin. Settings cannot be changed here.</p></div>';
} else {
    $storage_location = get_option('brickssync_json_storage_location');
    $sync_mode = get_option('brickssync_sync_mode');
    $settings_file_name = get_option('brickssync_settings_file_name', 'bricks-builder-settings.json');
    $path_status = brickssync_get_storage_path_status();
}

if ($storage_location && $sync_mode) {
    // Display header and intro text
    echo '<h2 style="margin-top:0;">Bricks Global Settings Synchronization</h2>';
    echo '<p style="max-width:700px; color:#555;">Export or import your Bricks global settings as a JSON file. Useful for migrating settings between sites or keeping environment-specific configurations in sync.</p>';

    // Info: Components and Global CSS included
    echo '<div class="notice notice-info" style="margin-bottom:16px;padding:10px 15px;background:#f8fafc;border-left:4px solid #0073aa;">
        <strong>Note:</strong> When exporting Bricks settings, all global options—including <strong>Bricks Components</strong> and <strong>Global CSS</strong>—are included in the export file. You do not need to export these separately.
    </div>';

    // Create container for actions
    echo '<div class="brickssync-section" style="border:1px solid #e5e5e5; border-radius:8px; padding:20px; margin-bottom:28px; background:#fafbfc;">';
    echo '<h3>Actions</h3>';
    echo '<p style="margin-bottom:8px;">Export or import all Bricks <strong>settings</strong> in one action. This does not affect templates.</p>';

    /**
     * Storage Path Validation
     *
     * Checks the configured storage path and:
     * - Validates path existence and permissions
     * - Displays appropriate notices for issues
     * - Disables forms based on path status
     * - Checks for specific settings file existence
     */
    $export_disabled = false;
    $import_disabled = false;

    // Check if path status is not OK
    if ($path_status['status'] !== 'ok') {
        // Display error/warning based on status
        $notice_type = ($path_status['status'] === 'not_writable') ? 'warning' : 'error';
        echo '<div class="notice notice-' . $notice_type . ' inline"><p><strong>Configuration Issue:</strong> ' . esc_html($path_status['message']) . '</p></div>';
        
        // Determine if buttons should be disabled
        if ($path_status['status'] !== 'not_writable') {
             $export_disabled = true;
             $import_disabled = true;
        }
        if ( ! $path_status['is_writable']) {
            $export_disabled = true;
        }
         if ( ! $path_status['is_readable']) {
            $import_disabled = true;
            // Also check if the specific settings file exists for import
            $settings_filename = $settings_file_name; // Use effective config file name on subsites
            $settings_filepath = $path_status['path'] . $settings_filename;
            if ($path_status['path'] && !is_file($settings_filepath)) {
                 echo '<div class="notice notice-error inline"><p><strong>Import Issue:</strong> Configured settings file not found: ' . esc_html($settings_filepath) . '</p></div>';
                 $import_disabled = true;
            }
        }
    }

    // TODO: Add checks to ensure the settings file is accessible/writable/readable based on context.

    /**
     * Export Form
     *
     * Creates a form for exporting Bricks global settings to a JSON file.
     * The form submits to the settings export handler.
     */
    // All file operations below must use the effective config if on multisite subsite.
    // (No direct file operations are present in this snippet, but ensure future logic uses $path_status and $settings_file_name from effective config.)
    $settings_export_form = new AltForms('settings_export_form');
    $settings_export_form
        ->input_hidden('brickssync_action', 'export_settings')
        ->submit_label('Export Settings');
    $settings_export_form->render();

    /**
     * Import Form
     *
     * Creates a form for importing Bricks global settings from a JSON file.
     * The form submits to the settings import handler.
     */
    $settings_import_form = new AltForms('settings_import_form');
    $settings_import_form
        ->input_hidden('brickssync_action', 'import_settings')
        ->submit_label('Import Settings');
    $settings_import_form->render();
    echo '</div>';

    // Add JS confirmation for import
    ?>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            const importForm = document.querySelector('form input[name="brickssync_action"][value="import_settings"]');
            if (importForm) {
                const submitButton = importForm.closest('form').querySelector('button[type="submit"], input[type="submit"]');
                if (submitButton) {
                    submitButton.addEventListener('click', function(event) {
                        if (!confirm('Are you sure you want to import settings? This will overwrite existing Bricks settings.')) {
                            event.preventDefault();
                        }
                    });
                }
            }
            <?php if ($export_disabled): ?>
            const exportSubmit = document.querySelector('form input[name="brickssync_action"][value="export_settings"]')?.closest('form').querySelector('button[type="submit"], input[type="submit"]');
            if(exportSubmit) exportSubmit.disabled = true;
            <?php endif; ?>
            <?php if ($import_disabled): ?>
            const importSubmit = document.querySelector('form input[name="brickssync_action"][value="import_settings"]')?.closest('form').querySelector('button[type="submit"], input[type="submit"]');
            if(importSubmit) importSubmit.disabled = true;
            <?php endif; ?>
        });
    </script>
    <?php
} else {
    // Display configuration notice
    $config_url = admin_url('admin.php?page=brickssync-admin&tab=config');
    echo wp_kses_post(
        sprintf(
            '<p>Please <a href="%s">configure the storage location and sync mode</a> first before using the settings sync features.</p>',
            esc_url($config_url)
        )
    );
}