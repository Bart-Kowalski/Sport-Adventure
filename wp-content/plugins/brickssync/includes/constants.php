<?php
/**
 * BricksSync Constants
 *
 * Defines plugin-wide constants for configuration, paths, and other settings.
 *
 * @package BricksSync\Includes
 * @since 0.1
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Plugin Version.
 * @since 0.1
 */
define('BRICKSSYNC_VERSION', get_file_data(__FILE__, ['Version' => 'Version'], 'plugin')['Version']);

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

/**
 * Hook name for the daily license check cron event.
 * @since 0.1
 */
if (!defined('BRICKSSYNC_DAILY_LICENSE_CHECK_HOOK')) {
    define('BRICKSSYNC_DAILY_LICENSE_CHECK_HOOK', 'brickssync_daily_license_check');
}