<?php
/**
 * Form Handler Functions
 *
 * This file handles form submissions from the BricksSync admin pages.
 * It provides functions for:
 * - Processing form submissions from admin tabs
 * - Handling import/export actions for settings and templates
 * - Validating form submissions with nonce checks
 * - Managing redirects and feedback messages
 * 
 * Supported Actions:
 * - export_settings: Export Bricks Builder settings to JSON
 * - import_settings: Import Bricks Builder settings from JSON
 * - export_templates: Export Bricks templates to JSON
 * - import_templates: Import Bricks templates from JSON
 * 
 * Security Features:
 * - Nonce verification for each form submission
 * - Capability checks via admin page restrictions
 * - Sanitization of form data
 * - Safe redirects with status messages
 *
 * @package BricksSync
 * @since 0.1
 */

namespace Brickssync\Includes\Functions\Admin;

use Brickssync\Includes\Functions\Debug\brickssync_log;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Handle BricksSync form submissions from admin tabs (excluding licensing).
 *
 * This function processes all form submissions from the BricksSync admin interface.
 * It validates the submission, performs the requested action, and redirects with
 * appropriate feedback messages.
 *
 * Supported Actions:
 * - export_settings: Exports Bricks Builder settings to JSON file
 * - import_settings: Imports Bricks Builder settings from JSON file
 * - export_templates: Exports Bricks templates to JSON file
 * - import_templates: Imports Bricks templates from JSON file
 *
 * Each action:
 * 1. Validates the nonce
 * 2. Calls the appropriate function
 * 3. Handles success/error messages
 * 4. Redirects back to the originating tab
 *
 * @since 0.1
 * @hook admin_init
 * @return void
 */
function brickssync_handle_tab_actions() {
    // Only proceed if a brickssync action is submitted
    if ( ! isset( $_POST['brickssync_action'] ) ) {
        return;
    }

    // Only proceed if we are on the BricksSync admin page
    if ( ! isset( $_GET['page'] ) || $_GET['page'] !== 'brickssync-admin' ) {
        return;
    }
    
    // Only handle non-licensing actions here
    $action = sanitize_key( $_POST['brickssync_action'] );
    if ( $action === 'activate_license' || $action === 'deactivate_license' ) {
        return;
    }

    $nonce_action = '';
    $function_to_call = null;
    $success_message = '';
    $error_message = '';
    $redirect_url = remove_query_arg(['action_status', 'message'], admin_url('admin.php?page=brickssync-admin&tab=' . sanitize_key($_GET['tab'] ?? 'templates'))); // Redirect back to current tab
    $import_args = null;
    $export_args = null;

    // Determine function and messages based on the submitted action
    switch ($action) {
        case 'export_settings':
            $nonce_action = 'settings_export_form_nonce';
            $function_to_call = 'brickssync_bricks_settings_export';
            $success_message = __('Settings exported successfully.', 'brickssync');
            $error_message = __('Settings export failed.', 'brickssync');
            break;
        case 'import_settings':
            $nonce_action = 'settings_import_form_nonce';
            $function_to_call = 'brickssync_bricks_settings_import';
            $success_message = __('Settings imported successfully.', 'brickssync');
            $error_message = __('Settings import failed.', 'brickssync');
            break;
        case 'export_templates':
            if (!empty($_POST['template_id'])) {
                $nonce_action = 'single_template_export_' . intval($_POST['template_id']) . '_nonce';
                $function_to_call = 'brickssync_bricks_template_export';
                $success_message = __('Template exported successfully.', 'brickssync');
                $error_message = __('Template export failed.', 'brickssync');
                $export_args = [ 'template_id' => intval($_POST['template_id']) ];
            } else {
                $nonce_action = 'template_export_form_nonce';
                $function_to_call = 'brickssync_bricks_template_export';
                $success_message = __('Templates exported successfully.', 'brickssync');
                $error_message = __('Template export failed.', 'brickssync');
                $export_args = [];
            }
            break;
        case 'import_templates':
            $nonce_action = 'template_import_form_nonce';
            $function_to_call = 'brickssync_bricks_template_import';
            $success_message = __('Templates imported successfully.', 'brickssync');
            $error_message = __('Template import failed.', 'brickssync');
            $import_args = ['overwrite' => true];
            if (!empty($_POST['source_file'])) {
                $import_args['source_file'] = sanitize_file_name($_POST['source_file']);
            }
            break;
        // TODO: Add case for 'save_config' if a config tab form is added
        default:
            // Unknown action, do nothing.
            return;
    }

    // Verify nonce for security
    if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( sanitize_text_field( $_POST['_wpnonce'] ), $nonce_action ) ) {
        wp_die('Security check failed (Nonce verification). Please go back and try again.', 'Nonce Verification Failed', ['response' => 403, 'back_link' => true]);
    }

    // Call the appropriate import/export function
    try {
        // Check if function exists before calling
        if ( ! function_exists($function_to_call) ) {
            throw new Exception("Required function '" . esc_html($function_to_call) . "' does not exist.");
        }
        
        // Call the function with export_args if set
        if (isset($export_args)) {
            $result = call_user_func($function_to_call, $export_args);
        } elseif (isset($import_args)) {
            $result = call_user_func($function_to_call, $import_args);
        } else {
            $result = call_user_func($function_to_call);
        }

        // Use the specific message from the function if it returned one, otherwise use default.
        $message = is_string($result) && !empty(trim($result)) ? $result : $success_message;

        // Add success status and message to redirect URL
        $redirect_url = add_query_arg(['action_status' => 'success', 'message' => urlencode($message)], $redirect_url);

    } catch (Exception $e) {
        // Log error if debug logging is enabled
        brickssync_log('Action ' . $action . ' failed: ' . $e->getMessage(), 'error');
        // Prepare error message for user
        $message = $error_message . ': ' . $e->getMessage();
        // Add error status and message to redirect URL
        $redirect_url = add_query_arg(['action_status' => 'error', 'message' => urlencode($message)], $redirect_url);
    }

    // Perform the redirect
    wp_safe_redirect($redirect_url);
    exit;
} 