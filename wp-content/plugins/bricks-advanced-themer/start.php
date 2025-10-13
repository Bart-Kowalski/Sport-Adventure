<?php

/**
 * Load all application modules.
 *
 * @package Bricks_Advanced_Themer
 */

defined('ABSPATH') || die();

global $brxc_acf_already_exists;

function brxc_init_plugin(){

    include_once __DIR__ . '/const.php';

    if (!class_exists('Advanced_Themer_Bricks\AT__Init')) {

        require_once plugin_dir_path( __FILE__ ) . 'classes/init.php';

        Advanced_Themer_Bricks\AT__Init::init_hooks();
    }
    
}

function brxc_check_license_date($unicode){
    // Unix timestamp of April 1st, 2024
    $april2024Timestamp = 1711922400;

    // Check if the provided timestamp is higher than the April 2024 timestamp
    return intval($unicode) < $april2024Timestamp;
}

// Include ACF PRO only if it's not installed yet

if ( ! function_exists( 'is_plugin_active' ) ) {
	include_once ABSPATH . 'wp-admin/includes/plugin.php';
}

// Check if ACF PRO is active
if ( is_plugin_active( 'advanced-custom-fields-pro/acf.php' ) ) {
	// Abort all bundling, ACF PRO plugin takes priority
	return add_action( 'plugins_loaded', 'brxc_init_plugin' );
}

// Check if another plugin or theme has bundled ACF
if ( defined( 'MY_ACF_PATH' ) ) {
	return add_action( 'plugins_loaded', 'brxc_init_plugin' );;
}

// Define path and URL to the bundled ACF code.
define( 'MY_ACF_PATH', __DIR__ . '/plugins/acf-pro/' );
define( 'MY_ACF_URL', plugin_dir_url( __FILE__ ) . 'plugins/acf-pro/' );

// Include the ACF plugin.
include_once( MY_ACF_PATH . 'acf.php' );

// Customize the URL setting to fix incorrect asset URLs.
add_filter( 'acf/settings/url', function( $url ) {
	return MY_ACF_URL;
} );

// Check if the ACF free plugin is activated
if ( is_plugin_active( 'advanced-custom-fields/acf.php' ) ) {
	// Free plugin activated
	// Show notice
	add_action( 'admin_notices', function () {
		?>
		<div class="updated" style="border-left: 4px solid #ffba00;">
			<p>The free version of ACF cannot be activated alongside Advanced Themer, as ACF is already bundled with AT. It has therefore been deactivated.</p>
		</div>
		<?php
	}, 99 );

	// Disable ACF free plugin
	deactivate_plugins( 'advanced-custom-fields/acf.php' );
}

// Check if ACF free is installed
if ( ! file_exists( WP_PLUGIN_DIR . '/advanced-custom-fields/acf.php' ) ) {
    add_action( 'init', 'Advanced_Themer_Bricks\AT__ACF::remove_acf_menu');
	add_filter( 'acf/settings/show_updates', '__return_false', 100 );
	add_filter( 'acf/settings/remove_wp_meta_box', '__return_false');
}

add_action( 'plugins_loaded', 'brxc_init_plugin' );