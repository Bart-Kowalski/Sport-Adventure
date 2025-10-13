<?php
/**
 * Frames Client Main file.
 *
 * @package Frames_Client
 */

/**
 * Plugin Name:       Frames
 * Plugin URI:        https://getframes.io/
 * Description:       A real time wireframing tool, design-ready development system, and accessible component library that empowers you to build beautiful custom websites in half the time with zero limits on your creativity.
 * Version:           1.5.4
 * Requires at least: 5.9
 * Requires PHP:      7.3
 * Author:            Kevin Geary, Matteo Greco
 * Author URI:        https://getframes.io/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://getframes.io/
 * Text Domain:       frames
 * Domain Path:       /languages
 */

defined( 'ABSPATH' ) || die( 'No script kiddies please!' );

/**
 * Define plugin directories and urls.
 */
define( 'FRAMES_PLUGIN_FILE', __FILE__ );
define( 'FRAMES_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'FRAMES_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'FRAMES_ASSETS_URL', plugin_dir_url( __FILE__ ) . 'assets' );
define( 'FRAMES_ASSETS_DIR', plugin_dir_path( __FILE__ ) . 'assets' );
define( 'FRAMES_CLASSES_URL', plugin_dir_url( __FILE__ ) . 'classes' );
define( 'FRAMES_CLASSES_DIR', plugin_dir_path( __FILE__ ) . 'classes' );
define( 'FRAMES_WIDGETS_URL', plugin_dir_url( __FILE__ ) . 'classes/Widgets' );
define( 'FRAMES_WIDGETS_DIR', plugin_dir_path( __FILE__ ) . 'classes/Widgets' );

/**
 * Load the plugin.
 */
require_once FRAMES_PLUGIN_DIR . '/classes/Autoloader.php';
\Frames_Client\Autoloader::register();
\Frames_Client\Plugin::get_instance()->init();
