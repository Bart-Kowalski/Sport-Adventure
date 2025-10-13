<?php
/**
 * Plugin Name:       HappyFiles Pro
 * Plugin URI:        https://happyfiles.io
 * Description:       Organize your WordPress data (files, post types, plugins).
 * Version:           1.8.3
 * Author:            Codeer
 * Author URI:        https://codeer.io
 * Text Domain:       happyfiles
 * Domain Path:       /languages
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! defined( 'HAPPYFILES_VERSION' ) ) {
	define( 'HAPPYFILES_VERSION', '1.8.3' );
	define( 'HAPPYFILES_FILE', __FILE__ );
	define( 'HAPPYFILES_PATH', plugin_dir_path( __FILE__ ) );
	define( 'HAPPYFILES_URL', plugin_dir_url( __FILE__ ) );
	define( 'HAPPYFILES_BASENAME', plugin_basename( __FILE__ ) );
	define( 'HAPPYFILES_ASSETS_URL', plugin_dir_url( __FILE__ ) . 'assets' );
	define( 'HAPPYFILES_ASSETS_PATH', plugin_dir_path( __FILE__ ) . 'assets' );
	define( 'HAPPYFILES_TAXONOMY', 'happyfiles_category' );
	define( 'HAPPYFILES_POSITION', 'happyfiles_position' );
	define( 'HAPPYFILES_FOLDER_COLORS', 'happyfiles_folder_colors' );
	define( 'HAPPYFILES_SETTINGS_GROUP', 'happyfiles_settings' );
	define( 'HAPPYFILES_TEMP_DIR', 'happyfiles-temp' );
}

require_once HAPPYFILES_PATH . 'includes/init.php';

// To avoid fatal error on plugin activation when HappyFiles free version is still active
if ( file_exists( HAPPYFILES_PATH . 'includes/pro.php' ) ) {
	require_once HAPPYFILES_PATH . 'includes/pro.php';
}