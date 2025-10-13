<?php
/**
 * SureCart Licensing Configuration and Functions
 *
 * This file handles all SureCart licensing related functionality for BricksSync.
 * It provides functions for:
 * - Initializing the SureCart licensing client
 * - Checking license status and validation
 * - Managing license activation and deactivation
 * - Running daily license validation checks
 * - Masking license keys for secure display
 *
 * Features:
 * - Automatic daily license validation via WP Cron
 * - Secure license key storage and display
 * - Configurable licensing checks (can be disabled for development)
 * - Detailed debug logging for license operations
 *
 * Requirements:
 * - SureCart SDK must be present in includes/external/SureCart-WordPress-SDK/
 * - BRICKSSYNC_SURECART_PUBLIC_TOKEN must be defined
 * - WordPress admin access for license management
 * - WP Cron for automated validation
 *
 * @package BricksSync
 * @since 0.1
 */

namespace BricksSync\Includes\Functions\Licensing;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Include debug functions
include_once(BRICKSSYNC_FUNCTIONS_DIR . 'debug/debug.php');

use function BricksSync\Includes\Functions\Debug\brickssync_log;

/**
 * SureCart Public API Token.
 * This token identifies the BricksSync product for licensing checks.
 * Used by the SureCart SDK to authenticate API requests.
 *
 * @since 0.1
 * @var string
 */
define('BRICKSSYNC_SURECART_PUBLIC_TOKEN', 'pt_wymUa1zcxHXM3AbkALstBnin');

/**
 * Optional: Disable licensing checks.
 * Define in wp-config.php: define('BRICKSSYNC_DISABLE_LICENSING', true);
 * This is useful for development or testing environments.
 *
 * @since 0.1
 * @var bool
 */
// define('BRICKSSYNC_DISABLE_LICENSING', true);

/**
 * Include the SureCart SDK Licensing Client library.
 * Checks if the SDK is already loaded, and if not, attempts to load it
 * from the expected location in the plugin directory.
 *
 * The SDK provides:
 * - License activation/deactivation
 * - License status checks
 * - Remote validation
 * - Error handling
 *
 * @since 0.1
 * @return void
 * @throws Exception If SDK file is missing and in admin context
 */
if ( ! class_exists( 'SureCart\\Licensing\\Client' ) ) {
    $sdk_path = BRICKSSYNC_DIR . 'includes/external/SureCart-WordPress-SDK/src/Client.php';
    if ( file_exists( $sdk_path ) ) {
        require_once $sdk_path;
    } else {
        // Show an admin notice if the SDK file is missing.
        if (is_admin()) {
             add_action('admin_notices', function() use ($sdk_path) {
                 echo '<div class="notice notice-error"><p><strong>BricksSync Error:</strong> SureCart SDK file not found at: ' . esc_html($sdk_path) . '. Licensing will not work.</p></div>';
             });
        }
    }
}

/**
 * Initialize SureCart Licensing Client manually.
 *
 * Instantiates the client and stores it in a global variable.
 * This runs on the 'init' hook.
 *
 * @since 0.1
 */
function initialize_surecart_licensing() {
    // Ensure SureCart\Licensing\Client class exists (it should be included earlier)
    if ( ! class_exists( 'SureCart\\Licensing\\Client' ) ) {
        if (is_admin()) { // Show notice if class is missing despite include attempt
             add_action('admin_notices', function() {
                 echo '<div class="notice notice-error"><p><strong>BricksSync Error:</strong> SureCart SDK Client class not found. Licensing will not work.</p></div>';
             });
        }
        return; 
    }
    
    // Get plugin data for client initialization
    if ( ! function_exists( 'get_plugin_data' ) ) {
        require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    }
    $plugin_data = get_plugin_data( BRICKSSYNC_PLUGIN_FILE );
    $plugin_name = $plugin_data['Name'] ?? 'BricksSync';

    // Store client in global variable for access elsewhere.
    // Using the main plugin file constant BRICKSSYNC_PLUGIN_FILE.
    $GLOBALS['brickssync_surecart_client'] = new \SureCart\Licensing\Client( 
        $plugin_name, 
        BRICKSSYNC_SURECART_PUBLIC_TOKEN, // Assumes token is correctly defined above
        BRICKSSYNC_PLUGIN_FILE 
    );
    
    // Optional: Set text domain for SDK strings if needed
    // $GLOBALS['brickssync_surecart_client']->set_textdomain( 'brickssync' );
    
    // We handle our own UI, so don't let the SDK add its settings page.
    // $GLOBALS['brickssync_surecart_client']->settings()->add_page(...);
}

// Hook into 'init' with proper namespace
add_action('init', __NAMESPACE__ . '\\initialize_surecart_licensing');

/**
 * Helper function to check if the BricksSync license is active.
 *
 * Checks the BRICKSSYNC_DISABLE_LICENSING constant first.
 *
 * @since 0.1
 * @return bool True if license is active or disabled via constant, false otherwise.
 */
// Get license key and activation id, with network fallback
function brickssync_get_license_data_with_network_fallback() {
    $key = get_option('brickssync_license_key', '');
    $activation_id = get_option('brickssync_activation_id', '');
    if (empty($key) || empty($activation_id)) {
        // Fallback to network (multisite) options
        $key = get_site_option('brickssync_license_key', '');
        $activation_id = get_site_option('brickssync_activation_id', '');
    }
    return [
        'key' => $key,
        'activation_id' => $activation_id,
    ];
}

function is_license_active(): bool {
    // Allow disabling license check via constant
    if ( defined( 'BRICKSSYNC_DISABLE_LICENSING' ) && BRICKSSYNC_DISABLE_LICENSING ) {
        return true;
    }

    // Check if SureCart client is initialized
    if ( ! isset( $GLOBALS['brickssync_surecart_client'] ) || ! is_object( $GLOBALS['brickssync_surecart_client'] ) ) {
        return false;
    }

    try {
        $client = $GLOBALS['brickssync_surecart_client'];

        // Use fallback-aware helper to get license key and activation ID
        $license_data = brickssync_get_license_data_with_network_fallback();
        $license_key = $license_data['key'];
        $activation_id = $license_data['activation_id'];

        // Make sure client settings reflect the license being checked
        $client->settings()->license_key = $license_key;
        $client->settings()->activation_id = $activation_id;

        if (empty($license_key) || empty($activation_id)) {
            brickssync_log('License check: No license key or activation ID stored.', 'debug');
            return false;
        }

        // Check the activation status directly
        $activation = $client->activation()->get($activation_id);
        if (is_wp_error($activation)) {
            brickssync_log('License check: Activation check failed: ' . $activation->get_error_message(), 'debug');
            return false;
        }

        // If we have a valid activation object, the license is active
        if (!empty($activation->id)) {
            brickssync_log('License check: Valid activation found.', 'debug');
            return true;
        }

        brickssync_log('License check: No valid activation found.', 'debug');
        return false;
    } catch (\Exception $e) {
        brickssync_log('License check exception: ' . $e->getMessage(), 'debug');
        return false;
    }
}

/**
 * Masks a license key, showing only the first and last few characters.
 *
 * @since 0.1
 * @param string $key The license key.
 * @param int    $visible_chars Optional. The number of characters to show at the start and end. Default 4.
 * @param string $mask_char Optional. The character to use for masking. Default '*'.
 * @return string The masked license key.
 */
function mask_license_key(?string $key, int $visible_chars = 4, string $mask_char = '*'): string {
    // Return empty string if key is null or empty
    if ( empty($key) ) {
        return '';
    }
    
    $key_len = strlen($key);
    // If key is too short to mask meaningfully, return it fully masked.
    if ($key_len <= ($visible_chars * 2)) {
        return str_repeat($mask_char, $key_len);
    }
    // Return the masked key (e.g., abcd****wxyz)
    return substr($key, 0, $visible_chars) . str_repeat($mask_char, $key_len - ($visible_chars * 2)) . substr($key, -$visible_chars);
}

/**
 * Daily cron job task to validate the license status with SureCart.
 *
 * This function is executed by the WP Cron task scheduled via BRICKSSYNC_DAILY_LICENSE_CHECK_HOOK.
 * It attempts to perform a remote license check using the SureCart SDK.
 *
 * @since 0.1
 */
function daily_license_validation_cron() {
    // Do nothing if SureCart Client global is not available.
    if ( ! isset( $GLOBALS['brickssync_surecart_client'] ) || ! is_object( $GLOBALS['brickssync_surecart_client'] ) ) {
        brickssync_log('Daily license check skipped: Client global not initialized.', 'debug');
        return;
    }
    
    brickssync_log('Running daily license validation check...', 'debug');

    $client = $GLOBALS['brickssync_surecart_client']; // Use global client

    try {
        // Do nothing if no license key is stored (plugin likely not activated).
        $stored_key = $client->settings()->license_key; // Use client instance to get key
        if ( empty($stored_key) ) {
            brickssync_log('Daily license check skipped: No license key stored.', 'debug');
            return;
        }
        
        // Attempt remote validation using is_active(true).
        $is_currently_active = $client->license()->is_active( true ); // Pass true to force remote check.
        
        // Rely on the SDK to update the stored license status implicitly.
        
        brickssync_log('Daily license validation check completed. Remote status active: ' . ($is_currently_active ? 'Yes' : 'No'), 'debug');

    } catch (\Exception $e) {
        // Log error if remote validation fails.
        brickssync_log('Daily license validation failed: ' . $e->getMessage(), 'error');
        // Optional: Implement error handling, e.g., maybe update local status to inactive?
    }
}

/**
 * Hook name for the daily license check cron event.
 * @since 0.1
 */
if (!defined('BRICKSSYNC_DAILY_LICENSE_CHECK_HOOK')) {
    define('BRICKSSYNC_DAILY_LICENSE_CHECK_HOOK', 'brickssync_daily_license_check');
}

// Schedule the daily license validation cron job action with proper namespace
add_action(BRICKSSYNC_DAILY_LICENSE_CHECK_HOOK, __NAMESPACE__ . '\\daily_license_validation_cron'); 