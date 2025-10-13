<?php
require_once dirname(__DIR__) . '/includes/functions/network_config.php';
/**
 * Admin Tab: Templates
 *
 * Handles the templates management interface in the BricksSync admin.
 * Provides UI for:
 * - Exporting Bricks templates to JSON files
 * - Importing templates from JSON files
 * - Checking storage path configuration
 * - Displaying storage-related notices
 *
 * Uses AltForms for form handling and relies on BricksSync template import/export functions.
 *
 * @package BricksSync\Admin\Tabs
 * @since 0.1
 */

// Add utility include at the very top before usage
if (!function_exists('brickssync_detect_json_file_type')) {
    include_once __DIR__ . '/../includes/functions/bricksTemplates/bricksTemplateUtils.php';
}

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Multisite subsite: use resolved config from network admin
if (is_multisite() && !is_network_admin()) {
    $eff = brickssync_get_effective_site_config(get_current_blog_id());
    $storage_location = $eff['json_storage_location'] ?? '';
    $sync_mode = $eff['sync_mode'] ?? '';
    $templates_file_pattern = $eff['templates_file_pattern'] ?? 'template-{slug}.json';
    $json_subdir = $eff['json_subdir'] ?? 'brickssync-json';
    require_once dirname(__DIR__) . '/includes/functions/storage/effective_storage.php';
    $site_id = get_current_blog_id();
    $path_status = brickssync_get_effective_storage_path_status($site_id);
    $storage_path = $path_status['path'] ?? '';
    // DO NOT override with uploads path if child_theme is set; always use the path from brickssync_get_effective_storage_path_status
    // $storage_path and $path_status are now always correct for the subsite's effective config
    echo '<div class="notice notice-info"><p>This site uses configuration inherited from the network admin. Templates will be synced according to network settings. Changes cannot be made here.</p></div>';
} else {
    $storage_location = get_option('brickssync_json_storage_location');
    $sync_mode = get_option('brickssync_sync_mode');
    $templates_file_pattern = get_option('brickssync_templates_file_pattern', 'template-{slug}.json');
    $json_subdir = get_option('brickssync_json_subdir', 'brickssync-json');
    $path_status = \Brickssync\Includes\Functions\Storage\brickssync_get_storage_path_status();
    $storage_path = $path_status['path'] ?? '';
}

if ($storage_location && $sync_mode) {
    /**
     * Check storage path status and set form states
     *
     * Validates the configured storage path and:
     * - Displays appropriate notices for path issues
     * - Disables export/import forms based on path permissions
     * - Sets form submission states
     */
    // Use the correct path status for multisite subsites
    if (is_multisite() && !is_network_admin()) {
        $site_id = get_current_blog_id();
        require_once dirname(__DIR__) . '/includes/functions/storage/effective_storage.php';
        $path_status = brickssync_get_effective_storage_path_status($site_id);
    } else {
        $path_status = brickssync_get_storage_path_status();
    }
    $export_disabled = false;
    $import_disabled = false;

    // Display notice if storage path has issues
    if ($path_status['status'] !== 'ok') {
        $notice_type = ($path_status['status'] === 'not_writable') ? 'warning' : 'error';
        echo '<div class="notice notice-' . $notice_type . ' inline"><p><strong>Configuration Issue:</strong> ' . esc_html($path_status['message']) . '</p></div>';
        // Disable export and import if path is not writable or readable
        if ($path_status['status'] !== 'not_writable') {
             $export_disabled = true;
             $import_disabled = true;
        }
        if ( ! $path_status['is_writable']) {
            $export_disabled = true;
        }
         if ( ! $path_status['is_readable']) {
            $import_disabled = true;
        }
    }

    // Display template synchronization UI
    echo '<h2 style="margin-top:0;">Bricks Templates Synchronization</h2>';
    echo '<p style="max-width:700px; color:#555;">Use this screen to export or import Bricks templates as JSON files for versioning, migration, or backup. You can manage all templates at once or handle them individually. For global settings, use the Settings tab.</p>';

    // Display bulk actions section
    echo '<div class="brickssync-section" style="border:1px solid #e5e5e5; border-radius:8px; padding:20px; margin-bottom:28px; background:#fafbfc;">';
    echo '<h3>Bulk Actions</h3>';
    echo '<p style="margin-bottom:8px;">Export or import <strong>all</strong> Bricks templates in one action. Recommended for full site migrations or backups.</p>';
    // All file operations below must use the effective config if on multisite subsite.
    // (No direct file operations are present in this snippet, but ensure future logic uses $path_status and $templates_file_pattern from effective config.)
    $template_export_form = new AltForms('template_export_form');
    $template_export_form
        ->input_hidden('brickssync_action', 'export_templates') 
        ->submit_label('Export All Templates');
    $template_export_form->render();
    $template_import_form = new AltForms('template_import_form');
    $template_import_form
        ->input_hidden('brickssync_action', 'import_templates') 
        ->submit_label('Import All Templates'); 
    $template_import_form->render();
    echo '</div>';

    // Display single template actions section
    echo '<div class="brickssync-section" style="border:1px solid #e5e5e5; border-radius:8px; padding:20px; margin-bottom:28px; background:#fff;">';
    echo '<h3>Single Template Actions</h3>';
    echo '<p style="margin-bottom:8px;">Export or import templates one by one. Useful for selective migrations or testing.</p>';
    // --- Single Template Export List ---
    $templates = get_posts([
        'post_type'   => 'bricks_template',
        'post_status' => 'any',
        'numberposts' => -1,
        'orderby'     => 'title',
        'order'       => 'ASC',
    ]);
    if ($templates) {
        echo '<h4 style="margin-top:14px;">Export Single Template</h4>';
        echo '<table class="widefat striped" style="max-width: 800px; margin-bottom:20px;">
            <thead><tr><th>Title</th><th>Type</th><th>Status</th><th>Export</th></tr></thead><tbody>';
        foreach ($templates as $template) {
            echo '<tr>';
            echo '<td>' . esc_html($template->post_title) . '</td>';
            echo '<td>' . esc_html($template->post_type) . '</td>';
            echo '<td>' . esc_html($template->post_status) . '</td>';
            echo '<td>';
            $single_export_form = new AltForms('single_template_export_' . $template->ID);
            $single_export_form
                ->input_hidden('brickssync_action', 'export_templates')
                ->input_hidden('template_id', $template->ID)
                ->submit_label('Export');
            $single_export_form->render();
            echo '</td>';
            echo '</tr>';
        }
        echo '</tbody></table>';
    }
    // --- Single Template Import List ---
    // Use $storage_path as previously set (from resolved config or path_status)
    $json_files = [];
    $template_json_files = [];
    if ($path_status['status'] === 'ok' && $storage_path && is_dir($storage_path)) {
        $json_files = glob(trailingslashit($storage_path) . '*.json');
        // Only include files detected as 'template' type
        foreach ($json_files as $file) {
            if (function_exists('brickssync_detect_json_file_type')) {
                $type = brickssync_detect_json_file_type($file);
                if ($type === 'template') {
                    $template_json_files[] = $file;
                }
            } else {
                // Fallback: show all files if utility not available
                $template_json_files[] = $file;
            }
        }
    }
    if ($template_json_files) {
        echo '<h4 style="margin-top:14px;">Import Single Template File</h4>';
        echo '<table class="widefat striped" style="max-width: 800px; margin-bottom:20px;">
            <thead><tr><th>File Name</th><th>Import</th></tr></thead><tbody>';
        foreach ($template_json_files as $file) {
            $basename = basename($file);
            echo '<tr>';
            echo '<td>' . esc_html($basename) . '</td>';
            echo '<td>';
            $single_import_form = new AltForms('template_import_form');
            $single_import_form
                ->input_hidden('brickssync_action', 'import_templates')
                ->input_hidden('source_file', $basename)
                ->submit_label('Import');
            $single_import_form->render();
            echo '</td>';
            echo '</tr>';
        }
        echo '</tbody></table>';
    }
    echo '</div>';
    // Add JS confirmation for import and button disabling
    ?>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            const importForm = document.querySelector('form input[name="brickssync_action"][value="import_templates"]');
            if (importForm) {
                const submitButton = importForm.closest('form').querySelector('button[type="submit"], input[type="submit"]');
                if (submitButton) {
                    submitButton.addEventListener('click', function(event) {
                        if (!confirm('Are you sure you want to import templates? This might overwrite existing templates if names conflict.')) {
                            event.preventDefault();
                        }
                    });
                    <?php if ($import_disabled): ?>
                    submitButton.disabled = true;
                    <?php endif; ?>
                }
            }
            <?php if ($export_disabled): ?>
            const exportSubmit = document.querySelector('form input[name="brickssync_action"][value="export_templates"]')?.closest('form').querySelector('button[type="submit"], input[type="submit"]');
            if(exportSubmit) exportSubmit.disabled = true;
            <?php endif; ?>
        });
    </script>
    <?php
} else {
    $config_url = admin_url('admin.php?page=brickssync-admin&tab=config');
    echo wp_kses_post(
        sprintf(
            '<p>Please <a href="%s">configure the storage location and sync mode</a> first before using the template sync features.</p>',
            esc_url($config_url)
        )
    );
}