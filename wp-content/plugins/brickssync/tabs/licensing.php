<?php
/**
 * Admin Tab: Licensing
 *
 * Handles the display of the licensing status, activation form, and deactivation button using the AltForms library.
 * Provides UI for:
 * - Viewing current license status
 * - Activating license
 * - Deactivating license
 *
 * Uses AltForms for form handling and SureCart SDK for licensing logic.
 *
 * @package BricksSync\Admin\Tabs
 * @since 0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// --- Pre-computation and Checks ---

// Ensure SureCart Client global is available (initialized in main plugin file).
if ( ! isset( $GLOBALS['brickssync_surecart_client'] ) || ! is_object( $GLOBALS['brickssync_surecart_client'] ) ) {
    echo '<div class="notice notice-error"><p>' . esc_html__( 'BricksSync Error: SureCart Licensing Client not initialized. Licensing features unavailable.', 'brickssync' ) . '</p></div>';
    return; // Stop rendering this tab if client is missing.
}

$client = $GLOBALS['brickssync_surecart_client']; // Get client from global

use BricksSync\Includes\Functions\Licensing\{
    initialize_surecart_licensing,
    is_license_active,
    mask_license_key
};

// Check license status using helper function from main plugin file.
$is_active = \BricksSync\Includes\Functions\Licensing\is_license_active();

// Initialize variables for license details.
$license_info = null;
// Use fallback-aware helper for license key
$license_data = \BricksSync\Includes\Functions\Licensing\brickssync_get_license_data_with_network_fallback();
$license_key = $license_data['key'];
$notice_html = ''; // Stores HTML for notices displayed at the top.

// Attempt to retrieve license details using the global client.
try {
    $license_info = $client->license();
    // Use fallback-aware helper for license key
    $license_data = \BricksSync\Includes\Functions\Licensing\brickssync_get_license_data_with_network_fallback();
    $license_key = $license_data['key'];
} catch (\Exception $e) {
    // Log error and prepare a notice for the user.
    error_log("Brickssync Licensing Tab Error: " . $e->getMessage());
    $notice_html .= '<div class="notice notice-error is-dismissible"><p>' . sprintf(
        esc_html__( 'Error retrieving license details: %s', 'brickssync' ), 
        esc_html($e->getMessage())
    ) . '</p></div>';
}

// --- Display Notices --- 
// Output any generated notices before the form.
echo $notice_html;

// --- Build Form using AltForms --- 
// Use different form slugs for activation and deactivation to simplify nonce handling in the main plugin file.
$form_slug = $is_active ? 'brickssync_license_deactivate_form' : 'brickssync_license_activate_form';
$form = new AltForms($form_slug);

// Define the submission logic within an on_submit callback
$form->on_submit(function( $submitted_data ) use ( $form_slug, $client ) {
    $message = '';
    $status = 'error';
    $redirect_url = admin_url('admin.php?page=brickssync-admin&tab=licensing');

    if ($form_slug === 'brickssync_license_activate_form') {
        // --- Activate Logic --- 
        $license_key = sanitize_text_field( $submitted_data['brickssync_license_key'] ?? '' );
        if ( empty($license_key) ) {
            $message = __('Please enter a license key.', 'brickssync');
        } else {
            try {
                \Brickssync\Includes\Functions\Debug\brickssync_log('Starting license activation process...', 'debug');
                // First validate the license key
                \Brickssync\Includes\Functions\Debug\brickssync_log('Retrieving license information...', 'debug');
                $license = $client->license()->retrieve($license_key);
                if (is_wp_error($license)) {
                    throw new \Exception($license->get_error_message());
                }
                \Brickssync\Includes\Functions\Debug\brickssync_log('License retrieved successfully. License ID: ' . $license->id, 'debug');
                // Create activation
                \Brickssync\Includes\Functions\Debug\brickssync_log('Creating activation...', 'debug');
                $activation_result = $client->license()->activate($license_key);
                if (is_wp_error($activation_result)) {
                    throw new \Exception($activation_result->get_error_message());
                }
                // Retrieve activation ID from settings after activation
                $activation_id = $client->settings()->activation_id;
                \Brickssync\Includes\Functions\Debug\brickssync_log('Activation created. Activation ID: ' . $activation_id, 'debug');
                // Store license key and activation ID
                $client->settings()->license_key = $license_key;
                $client->settings()->activation_id = $activation_id;
                // Persist to options on single-site only
                if ( ! is_multisite() ) {
                    update_option('brickssync_license_key', $license_key);
                    update_option('brickssync_activation_id', $activation_id);
                }
                \Brickssync\Includes\Functions\Debug\brickssync_log('Stored license key and activation ID in settings', 'debug');
                // Validate release
                $activation_check = $client->license()->is_active();
                \Brickssync\Includes\Functions\Debug\brickssync_log('Activation check result: ' . print_r($activation_check, true), 'debug');
                // If we got here, all steps were successful
                $status = 'success';
                $message = __('License activated successfully.', 'brickssync');
                \Brickssync\Includes\Functions\Debug\brickssync_log('License activation completed successfully', 'debug');
                // Add JavaScript redirect
                echo '<script>window.location.href = "' . esc_js(admin_url('admin.php?page=brickssync-admin&tab=licensing&action_status=success&message=' . urlencode($message))) . '";</script>';
            } catch (\Exception $e) {
                $message = __('License activation failed:', 'brickssync') . ' ' . $e->getMessage();
                \Brickssync\Includes\Functions\Debug\brickssync_log('License Activation failed: ' . $e->getMessage(), 'error');
            }
        }
    } elseif ($form_slug === 'brickssync_license_deactivate_form') {
        // --- Deactivate Logic ---
        try {
            $result = $client->license()->deactivate();
            if (is_wp_error($result)) {
                throw new \Exception($result->get_error_message());
            }
            $client->settings()->license_key = '';
            $client->settings()->activation_id = '';
            // Remove from options on single-site only
            if ( ! is_multisite() ) {
                update_option('brickssync_license_key', '');
                update_option('brickssync_activation_id', '');
            }
            $status = 'success';
            $message = __('License deactivated successfully.', 'brickssync');
            \Brickssync\Includes\Functions\Debug\brickssync_log('License deactivated successfully', 'debug');
        } catch (\Exception $e) {
            $message = __('License deactivation failed:', 'brickssync') . ' ' . $e->getMessage();
            \Brickssync\Includes\Functions\Debug\brickssync_log('License Deactivation failed: ' . $e->getMessage(), 'error');
        }
    }
    // A better approach might be to modify AltForms or use a different pattern, but this works for now.
    if ( ! empty( $message ) ) {
        set_transient('brickssync_licensing_notice', ['status' => $status, 'message' => $message], 30);
    }
});

// --- Multisite Subsite Handling ---
if (is_multisite() && !is_network_admin()) {
    echo '<div class="wrap">';
    echo '<h2>' . esc_html__('BricksSync License Status', 'brickssync') . '</h2>';
    if ($is_active) {
        if (isset($license_info->expires_at)) {
            $expiry_date = empty($license_info->expires_at) ? __('Never', 'brickssync') : date_i18n(get_option('date_format'), strtotime($license_info->expires_at));
            echo '<p><strong>' . esc_html__('Expires:', 'brickssync') . '</strong> ' . esc_html($expiry_date) . '</p>';
        }
        if (isset($license_info->activation_count)) {
            echo '<p><strong>' . esc_html__('Activations:', 'brickssync') . '</strong> ' . intval($license_info->activation_count) . '</p>';
        }
        echo '<p style="margin-top:2em;"><span style="color:green;font-weight:bold;">' . esc_html__('License is active for this network.', 'brickssync') . '</span></p>';
    } else {
        echo '<p><span style="color:red;font-weight:bold;">' . esc_html__('No active license found for this network.', 'brickssync') . '</span></p>';
    }
    echo '<div style="margin-top:2em;padding:1em;background:#f8f8f8;border-left:4px solid #2271b1;max-width:600px;">
        <strong>' . esc_html__('Network Managed Licensing', 'brickssync') . '</strong><br>
        ' . esc_html__('This site is part of a WordPress multisite network. BricksSync licensing is managed centrally by the Network Administrator. To activate or deactivate your license, please visit the Network Admin → Settings → BricksSync page.', 'brickssync') . '
    </div>';
    echo '</div>';
    // Stop further processing so no forms are shown
    return;
}

// --- Populate Form Based on License Status ---
if ( $is_active && $license_info ) {
    // === License is Active: Display Details and Deactivate Button ===
    $form->content('<h3>' . __('License Status: Active', 'brickssync') . '</h3>');
    // Display masked license key.
    $form->content('<p><strong>' . __('License Key:', 'brickssync') . '</strong> ' . esc_html(\BricksSync\Includes\Functions\Licensing\mask_license_key($license_key)) . '</p>');
    if (isset($license_info->expires_at)) {
        $expiry_date = empty($license_info->expires_at) ? __('Never', 'brickssync') : date_i18n(get_option('date_format'), strtotime($license_info->expires_at));
        $form->content('<p><strong>' . __('Expires:', 'brickssync') . '</strong> ' . esc_html($expiry_date) . '</p>');
    }
    if (isset($license_info->max_sites)) {
        $max_sites_display = ($license_info->max_sites == 9999 || $license_info->max_sites === null) ? __('Unlimited', 'brickssync') : esc_html($license_info->current_sites ?? '?') . ' / ' . esc_html($license_info->max_sites);
        $form->content('<p><strong>' . __('Activations:', 'brickssync') . '</strong> ' . $max_sites_display . '</p>');
    }
    $form->submit_label(__('Deactivate License', 'brickssync'));
    // Note: Styling the button (e.g., red color) requires custom CSS targeting the AltForms output.
    // Example CSS: #brickssync_license_deactivate_form_submit .button-primary { background-color: #dc3232; border-color: #dc3232; }
} else {
    // === License is Inactive: Display Activation Form ===
    $form->content('<h3>' . __('Activate Your License', 'brickssync') . '</h3>');
    $form->content('<p>' . __('Enter your license key below to activate BricksSync and unlock all features.', 'brickssync') . '</p>');
    $form->input_text('brickssync_license_key', __('License Key', 'brickssync'), '')
         ->content('<p class="description">' . __('Enter the license key you received after purchase.', 'brickssync') . '</p>');
    $form->submit_label(__('Activate License', 'brickssync'));
    if ( ! empty( $license_key ) && ! $is_active ) {
         $form->content('<p class="description" style="color: #dc3232;">' . __('Activation may have failed previously. Please verify your key and try again.', 'brickssync') . '</p>');
    }
    if ( defined( 'BRICKSSYNC_DISABLE_LICENSING' ) && BRICKSSYNC_DISABLE_LICENSING ) {
        $form->content('<div class="notice notice-info inline"><p>' . __('Licensing checks are currently disabled via the BRICKSSYNC_DISABLE_LICENSING constant in your wp-config.php.', 'brickssync') . '</p></div>');
    } 
}

// --- Handle Submission --- 
$form->handle();

// --- Check for Redirect/Notice from our callback ---
$notice = get_transient('brickssync_licensing_notice');
if ($notice) {
    delete_transient('brickssync_licensing_notice');
    $redirect_url = admin_url('admin.php?page=brickssync-admin&tab=licensing');
    $redirect_url = add_query_arg(['action_status' => $notice['status'], 'message' => urlencode($notice['message'])], $redirect_url);
    if (!headers_sent()) {
        wp_safe_redirect($redirect_url);
        exit;
    } else {
        $notice_class = ($notice['status'] === 'success') ? 'notice-success' : 'notice-error';
        echo '<div class="notice '. $notice_class .' is-dismissible"><p>' . esc_html( $notice['message'] ) . '</p></div>';
    }
}

// --- Render Form --- 
echo '<h2 style="margin-top:0;">BricksSync License Management</h2>';
echo '<p style="max-width:700px; color:#555;">Manage your BricksSync license. Activate your license to unlock all features, or deactivate to unlink this site. Your license details and activation status are shown below.</p>';
echo '<div class="brickssync-section" style="border:1px solid #e5e5e5; border-radius:8px; padding:20px; margin-bottom:28px; background:#fafbfc;">';
echo '<h3>License Status &amp; Actions</h3>';
echo $form->render();
echo '</div>';
?>
