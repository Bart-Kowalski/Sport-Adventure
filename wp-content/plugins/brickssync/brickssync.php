<?php
/**
 * Plugin Name: BricksSync
 * Description: Sync and migrate Bricks Builder settings and templates via Git-friendly JSON files. 
 *              This plugin allows you to:
 *              - Export Bricks Builder settings and templates to JSON files
 *              - Import settings and templates from JSON files
 *              - Store JSON files in your theme, uploads directory, or custom location
 *              - Version control your Bricks Builder configurations
 * Version: 1.1.1
 * Requires at least: 5.8
 * Requires PHP: 7.4
 * Author: Gert Wierbos - BricksSync.com
 * Author URI: https://brickssync.com
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: brickssync
 * Domain Path: /languages
 * 
 * @package BricksSync
 * @since 0.1
 */


/**
 * Main plugin file for BricksSync.
 *
 * This file handles:
 * - Plugin initialization and dependency checks
 * - Loading of required files and dependencies
 * - Setting up constants and configuration
 * - Admin menu and page setup
 * - Form handling and action routing
 * - Storage path management
 *
 * Dependencies:
 * - WordPress 5.8 or higher
 * - PHP 7.4 or higher
 * - Bricks Builder plugin
 * - SureCart SDK (for licensing)
 *
 * @package BricksSync
 * @since 0.1
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// === Constants ===

/**
 * Plugin Version.
 * @since 0.1
 */
define('BRICKSSYNC_VERSION', '1.1.1');

/**
 * The main plugin file path.
 * @since 0.1
 */
define('BRICKSSYNC_PLUGIN_FILE', __FILE__);

/**
 * Absolute path to the plugin directory with trailing slash.
 * @since 0.1
 */
define('BRICKSSYNC_DIR', plugin_dir_path(__FILE__));

/**
 * URL to the plugin directory with trailing slash.
 * @since 0.1
 */
define('BRICKSSYNC_URL', plugin_dir_url(__FILE__));

/**
 * Path to the includes/functions/ directory with trailing slash.
 * @since 0.1
 */
define('BRICKSSYNC_FUNCTIONS_DIR', BRICKSSYNC_DIR . 'includes/functions/');

// === Includes ===

include_once(BRICKSSYNC_FUNCTIONS_DIR . 'debug/debug.php'); // Debug helpers
include_once(BRICKSSYNC_FUNCTIONS_DIR . 'licensing/licensing.php'); // SureCart licensing
include_once(BRICKSSYNC_DIR . 'includes/altForms/altForms.php');
include_once(BRICKSSYNC_FUNCTIONS_DIR . 'cron/cron.php');
include_once(BRICKSSYNC_FUNCTIONS_DIR . 'bricksSettings/bricksSettingsImport.php');
include_once(BRICKSSYNC_FUNCTIONS_DIR . 'bricksSettings/bricksSettingsExport.php');
include_once(BRICKSSYNC_FUNCTIONS_DIR . 'bricksTemplates/filenameUtils.php');
include_once(BRICKSSYNC_FUNCTIONS_DIR . 'bricksTemplates/bricksTemplateImport.php');
include_once(BRICKSSYNC_FUNCTIONS_DIR . 'bricksTemplates/bricksTemplateExport.php');
include_once(BRICKSSYNC_FUNCTIONS_DIR . 'wpCli/wpCli.php'); // WP-CLI commands
include_once(BRICKSSYNC_FUNCTIONS_DIR . 'storage/storage.php'); // Storage helpers
include_once(BRICKSSYNC_FUNCTIONS_DIR . 'admin/formHandler.php');

use Brickssync\Includes\Functions\Debug\brickssync_log;
use BricksSync\Includes\Functions\Licensing\is_license_active;

// === Plugin Activation/Deactivation Hooks ===

/**
 * Hook for plugin deactivation.
 * Runs the cron unscheduling function.
 * @since 0.1
 */
register_deactivation_hook( BRICKSSYNC_PLUGIN_FILE, 'Brickssync\\Includes\\Functions\\Cron\\brickssync_unschedule_cron' );

/**
 * Hook for plugin activation.
 * Runs the cron scheduling function.
 * @since 0.1
 */
register_activation_hook( BRICKSSYNC_PLUGIN_FILE, 'Brickssync\\Includes\\Functions\\Cron\\brickssync_schedule_cron' );

/**
 * Check dependencies during plugin activation.
 *
 * @since 0.1
 */
function brickssync_activation_check() {
    // Allow network activation even if Bricks is not active, since network admin has no theme.
    if (is_network_admin()) {
        return;
    }
    if (!function_exists('bricks_is_builder') && !class_exists('Bricks\Elements')) {
        deactivate_plugins(plugin_basename(__FILE__));
        wp_die(
            sprintf(
                wp_kses_post(__('BricksSync requires Bricks Builder to be installed and activated. Please <a href="%s">install Bricks Builder</a> first.', 'brickssync')),
                'https://bricksbuilder.io/'
            ),
            'Plugin Dependency Error',
            ['back_link' => true]
        );
    }
}
register_activation_hook(BRICKSSYNC_PLUGIN_FILE, 'brickssync_activation_check');

// === Admin Form Handlers ===

// Hook the correct handler from formHandler.php
add_action('admin_init', 'Brickssync\\Includes\\Functions\\Admin\\brickssync_handle_tab_actions');

// === Auto-export Bricks templates on save ===
require_once __DIR__ . '/includes/functions/bricksTemplates/autoExport.php';

// === Storage Path Helper ===

/**
 * Determines the configured storage path for JSON files and checks its status.
 *
 * Reads the storage location option ('brickssync_json_storage_location') and
 * resolves the corresponding path (child theme, uploads, or custom).
 * Checks if the path exists and is readable/writable.
 *
 * @since 0.1
 * @return array{path: string|null, status: string, message: string, is_readable: bool, is_writable: bool}
 *         - 'path': The resolved absolute path (string) or null on error.
 *         - 'status': 'ok', 'not_configured', 'not_found', 'not_readable', 'not_writable', 'error'.
 *         - 'message': User-friendly message describing the status or error.
 *         - 'is_readable': Boolean indicating readability.
 *         - 'is_writable': Boolean indicating writability.
 */
function brickssync_get_storage_path_status(): array {
    $location = get_option('brickssync_json_storage_location');
    $path = null;
    $status = 'not_configured';
    $message = __('Storage location is not configured.', 'brickssync');
    $is_readable = false;
    $is_writable = false;

    // Return early if no location option is set.
    if ( ! $location ) {
        return compact('path', 'status', 'message', 'is_readable', 'is_writable');
    }

    // Determine path based on the configured location option.
    switch ($location) {
        case 'child_theme':
            $path = get_stylesheet_directory();
            break;
        case 'uploads_folder':
            $upload_dir = wp_get_upload_dir();
            $path = $upload_dir['basedir'] ?? null;
            break;
        case 'custom_url': // Note: option name is custom_url but stores a path
            $path = get_option('brickssync_custom_storage_location_path');
            break;
    }

    // Handle cases where path could not be determined.
    if ( empty($path) ) {
        $status = 'error';
        $message = sprintf(
            __('Could not determine storage path based on configuration (%s).', 'brickssync'), 
            esc_html($location)
        );
        return compact('path', 'status', 'message', 'is_readable', 'is_writable');
    }

    // Ensure path has a trailing slash.
    $path = trailingslashit($path);

    // Check directory status (existence, readability, writability).
    if ( ! is_dir($path) ) {
        $status = 'not_found';
        $message = __('Configured storage path does not exist or is not a directory:', 'brickssync') . ' ' . esc_html($path);
    } elseif ( ! is_readable($path) ) {
        $status = 'not_readable';
        $message = __('Configured storage path is not readable by the web server:', 'brickssync') . ' ' . esc_html($path);
        $is_readable = false; // Cannot check writability if not readable.
        $is_writable = false;
    } else {
        // Path exists and is readable, now check writability.
        $is_readable = true;
        if ( ! wp_is_writable($path) ) {
            $status = 'not_writable';
            $message = __('Configured storage path is not writable by the web server:', 'brickssync') . ' ' . esc_html($path) . ' ' . __('Exporting may fail.', 'brickssync');
            $is_writable = false;
        } else {
            // Path is valid, readable, and writable.
            $status = 'ok';
            $message = __('Storage path configured and accessible:', 'brickssync') . ' ' . esc_html($path);
            $is_writable = true;
        }
    }

    return compact('path', 'status', 'message', 'is_readable', 'is_writable');
}

// === Admin Menu & Page Setup ===

/**
 * Add the BricksSync settings page.
 *
 * Only adds the page if Bricks Builder is active.
 *
 * @since 0.1
 */
function brickssync_add_site_admin_page() {
    // Only show menu if Bricks Builder is active
    if (function_exists('bricks_is_builder') || class_exists('Bricks\Elements')) {
        add_submenu_page(
            'bricks',             // Parent slug (Bricks main menu)
            'BricksSync',         // Page title
            'BricksSync',         // Menu title
            'manage_options',     // Capability required
            'brickssync-admin',   // Menu slug
            'brickssync_admin_page' // Callback function to render the page
        );
    }
}

function brickssync_add_network_admin_page() {
    // Only add in network admin
    if (is_network_admin()) {
        add_submenu_page(
            'settings.php',         // Parent slug for network settings
            'BricksSync Network',   // Page title
            'BricksSync',           // Menu title
            'manage_network_options', // Capability required
            'brickssync-network-admin', // Menu slug
            'brickssync_network_admin_page' // Dedicated callback for network admin
        );
    }
}

// Callback for network admin menu page
function brickssync_network_admin_page() {
    echo '<div class="wrap">';
    echo '<h1>' . esc_html__('BricksSync', 'brickssync') . ' <span style="font-size:0.65em;font-weight:normal;color:#888;vertical-align:middle;">v' . esc_html(constant('BRICKSSYNC_VERSION')) . '</span></h1>';
    // Only one tab for now: "License"
    $tabs = array(
        'config'        => __('Configuration', 'brickssync'),
        'global_config' => __('Global config', 'brickssync'),
        'group_config'  => __('Group config', 'brickssync'),
        'licensing'     => __('License', 'brickssync'),
        'debug'         => __('Debug', 'brickssync'),
    );
    // Determine active tab
    $active_tab = isset($_GET['tab']) && array_key_exists($_GET['tab'], $tabs) ? sanitize_key($_GET['tab']) : 'licensing';
    echo '<h2 class="nav-tab-wrapper">';
    foreach ($tabs as $tab_key => $tab_label) {
        $tab_url = network_admin_url('settings.php?page=brickssync-network-admin&tab=' . $tab_key);
        $active_class = ($active_tab == $tab_key) ? 'nav-tab-active' : '';
        echo '<a href="' . esc_url($tab_url) . '" class="nav-tab ' . $active_class . '">' . esc_html($tab_label) . '</a>';
    }
    echo '</h2>';
    // Tab content
    echo '<div class="tab-content" style="margin-top:24px;">';
    $tab_file = BRICKSSYNC_DIR . 'tabs/' . ($active_tab === 'licensing' ? 'network-licensing' : $active_tab) . '.php';
    if (file_exists($tab_file)) {
        include_once($tab_file);
    } else {
        echo '<div class="notice notice-error"><p>' . esc_html__('Tab content file not found:', 'brickssync') . ' ' . esc_html($tab_file) . '</p></div>';
    }
    echo '</div>';
    echo '</div>';
}
// Hook site admin page to admin_menu
add_action('admin_menu', 'brickssync_add_site_admin_page', 20);
// Hook network admin page to network_admin_menu
add_action('network_admin_menu', 'brickssync_add_network_admin_page', 20);

/**
 * Render the BricksSync admin page with tabs.
 *
 * This function acts as the callback for the admin page added in brickssync_add_admin_page().
 * It handles tab navigation and includes the relevant tab content file.
 *
 * @since 0.1
 */
function brickssync_admin_page() {
	
    // Define the available tabs
    // TODO: Potentially make tabs filterable
    $tabs = array(
        "templates" => __("Templates", 'brickssync'),
        "settings" => __("Bricks Builder settings", 'brickssync'),
        "config" => __("Configuration settings", 'brickssync'),
        "licensing" => __("Licensing", 'brickssync'),
        "debug" => __("Debug & Logging", 'brickssync'),
    );

    // Determine the active tab, default to 'licensing'
	$active_tab = isset( $_GET['tab'] ) ? sanitize_key( $_GET['tab'] ) : 'templates';
    
    // Ensure the active tab is valid, fallback to 'licensing' if not.
    if ( ! array_key_exists($active_tab, $tabs) ) {
        $active_tab = 'templates';
    }
    
    // Check license status to potentially restrict access to non-licensing tabs.
    $show_content = true;
    if ( $active_tab !== 'licensing' ) { 
        if (!\BricksSync\Includes\Functions\Licensing\is_license_active()) {
            $show_content = false;
        }
    }

	?>

	<div class="wrap">
		<h1><?php echo esc_html(get_admin_page_title()); ?> <span style="font-size:10px;">version <?php echo esc_html(BRICKSSYNC_VERSION); ?></span></h1>
        
        <!-- Tab Navigation -->
		<h2 class="nav-tab-wrapper">
		<?php foreach ($tabs as $tab_key => $tab_value) {
            $tab_url = admin_url('admin.php?page=brickssync-admin&tab=' . $tab_key);
            $active_class = ($active_tab == $tab_key) ? 'nav-tab-active' : '';
            // Only render the link if the tab should be shown (e.g., hide others if license inactive?)
            // Currently shows all tabs, content is restricted below.
            echo '<a href="' . esc_url($tab_url) . '" class="nav-tab ' . $active_class . '">' . esc_html($tab_value) . '</a>';
        } ?>
		</h2>
        
        <?php 
        // Display feedback notices from form submissions (success/error messages)
        if ( isset( $_GET['action_status'] ) ) {
            $status = sanitize_key($_GET['action_status']); // 'success' or 'error'
            $message = isset($_GET['message']) ? sanitize_text_field(urldecode($_GET['message'])) : '';
            $notice_class = ($status === 'success') ? 'notice-success' : 'notice-error';
            
            // Provide default messages if none was passed in the URL
            if ( empty(trim($message)) ) {
                $message = ($status === 'success') ? __('Operation completed successfully.', 'brickssync') : __('Operation failed. Please check logs.', 'brickssync');
            }
            echo '<div class="notice '. $notice_class .' is-dismissible"><p>' . esc_html( $message ) . '</p></div>';
        }
        ?>
        
        <?php 
        // Display tab content or license activation prompt
        if ( $show_content ) :
             // Include the active tab content file from the 'tabs/' directory.
            ?>
            <div class="tab-content">
                <br/>
                <?php 
                $tab_file = BRICKSSYNC_DIR . 'tabs/' . $active_tab . '.php';
                if (file_exists($tab_file)) {
                    include_once($tab_file);
                } else {
                    echo '<div class="notice notice-error"><p>' . esc_html__( 'Error: Tab content file not found:', 'brickssync' ) . ' ' . esc_html($tab_file) . '</p></div>';
                }
                ?>
            </div>
            <?php
        else: // License is inactive, show activation prompt
            ?>
            <div class="tab-content">
                <br/>
                <div class="notice notice-warning inline">
                    <p><?php 
                        // Prompt user to activate license, linking to the licensing tab.
                        printf(
                            wp_kses_post(__( 'Please <a href="%s">activate your license</a> to use this feature.', 'brickssync')), 
                            esc_url(admin_url('admin.php?page=brickssync-admin&tab=licensing')) 
                        );
                    ?></p>
                </div>
            </div>
            <?php
        endif; 
        ?>
    </div><!-- /.wrap -->

    <?php
}

/**
 * Display admin notice if Bricks Builder is not active.
 *
 * @since 0.1
 */
function brickssync_check_bricks_builder() {
    // Don't show notice in network admin (no theme context)
    if (is_network_admin()) {
        return;
    }
    if (!function_exists('bricks_is_builder') && !class_exists('Bricks\Elements')) {
        ?>
        <div class="notice notice-error">
            <p><?php 
                printf(
                    wp_kses_post(__('BricksSync requires Bricks Builder to be installed and activated. Please <a href="%s">install Bricks Builder</a> to use BricksSync.', 'brickssync')),
                    'https://bricksbuilder.io/'
                );
            ?></p>
        </div>
        <?php
    }
}
add_action('admin_notices', 'brickssync_check_bricks_builder');

// Enqueue admin scripts
add_action('admin_enqueue_scripts', function($hook) {
    if (strpos($hook, 'brickssync-admin') !== false) {
        wp_enqueue_script('jquery');
    }
});

// Add AJAX handler for debug log
add_action('wp_ajax_brickssync_get_debug_log', function() {
    check_ajax_referer('brickssync_get_debug_log', '_ajax_nonce');
    
    $wp_debug_log = WP_CONTENT_DIR . '/debug.log';
    if (file_exists($wp_debug_log)) {
        wp_send_json_success(file_get_contents($wp_debug_log));
    } else {
        wp_send_json_error('Debug log file not found');
    }
});
