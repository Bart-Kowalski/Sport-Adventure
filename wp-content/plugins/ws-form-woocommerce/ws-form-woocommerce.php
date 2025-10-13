<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Plugin Name:       WooCommerce WS Form PRO Product Add-Ons
 * Plugin URI:        https://wsform.com/knowledgebase/woocommerce/
 * Description:       WooCommerce extension for WS Form PRO
 * Version:           1.1.82
 * Requires at least: 5.2
 * Requires PHP:      5.6
 * Author:            WS Form
 * Author URI:        https://wsform.com/
 * Text Domain:       ws-form-woocommerce
 *
 * Woo: 4875731:d89f100dccd14884727f3e69e02fb628
 * WC requires at least: 3.0.0
 * WC tested up to: 8.2
 *
 * Copyright: © 2023 WS Form
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

define( 'WS_FORM_WOOCOMMERCE_PLUGIN_DIR_URL', plugin_dir_url(__FILE__) );
define( 'WS_FORM_WOOCOMMERCE_PLUGIN_DIR_PATH', plugin_dir_path(__FILE__) );
define( 'WS_FORM_WOOCOMMERCE_PLUGIN_BASENAME', plugin_basename(__FILE__) );
define( 'WS_FORM_WOOCOMMERCE_VERSION', '1.1.82' );

// Declare that we are compatible with custom order tables (HPOS)
add_action( 'before_woocommerce_init', function() {
	if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
	}
} );

class WS_Form_Add_On_WooCommerce {

	const WS_FORM_PRO_ID          = 'ws-form-pro/ws-form.php';
	const WS_FORM_PRO_VERSION_MIN = '1.9.0';
	const WOOCOMMERCE_VERSION_MIN = '3.0.0';

	private $form_config_array = array();

	public function __construct() {

		// Load plugin.php
		if ( ! function_exists( 'is_plugin_active' ) ) {

			include_once ABSPATH . 'wp-admin/includes/plugin.php' ;
		}

		// Admin init
		add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ), 20 );
	}

	public function plugins_loaded() {

		if ( self::is_dependency_ok() ) {

			new WS_Form_Action_WooCommerce();

		} else {

			self::dependency_error();

			if ( isset( $_GET['activate'] ) ) { // phpcs:ignore

				unset( $_GET['activate'] ); // phpcs:ignore
			}
		}
	}

	public function activate() {

		if ( ! self::is_dependency_ok() ) {

			self::dependency_error();
		}
	}

	// Check dependencies
	public function is_dependency_ok() {

		if ( ! defined( 'WS_FORM_VERSION' ) ) {

			return false;
		}

		return(

			is_plugin_active( self::WS_FORM_PRO_ID ) &&
			( version_compare( WS_FORM_VERSION, self::WS_FORM_PRO_VERSION_MIN ) >= 0 ) &&
			defined( 'WC_VERSION' ) &&
			( version_compare( WC_VERSION, self::WOOCOMMERCE_VERSION_MIN ) >= 0 )
		);
	}

	// Add error notice action - Pro
	public function dependency_error() {

		// Show error notification
		add_action( 'after_plugin_row_' . plugin_basename( __FILE__ ), array( $this, 'dependency_error_notification' ), 10, 2 );
	}

	// Dependency error - Notification
	public function dependency_error_notification( $file, $plugin ) {

		// Checks
		if ( ! current_user_can( 'update_plugins' ) ) {

			return;
		}
		if ( plugin_basename(__FILE__) != $file ) {

			return;
		}

		// Build notice
		printf( '<tr class="plugin-update-tr"><td colspan="3" class="plugin-update colspanchange"><div class="update-message notice inline notice-error notice-alt"><p>%s</p></div></td></tr>',

			/* translators: %1$s: WS Form PRO product link, %2$s: WS Form PRO minimum version, %3$s: Minimum WooCommerce version */
			sprintf( esc_html__('This add-on requires %1$s (version %2$s or later) and WooCommerce (version %3$s or later) to be installed and activated.', 'ws-form-woocommerce' ),

				'<a href="https://wsform.com?utm_source=ws_form_pro&utm_medium=plugins" target="_blank">WS Form PRO</a>',
				esc_html( self::WS_FORM_PRO_VERSION_MIN ),
				esc_html( self::WOOCOMMERCE_VERSION_MIN )
			)
		);
	}
}

$wsf_add_on_woocommerce = new WS_Form_Add_On_WooCommerce();

register_activation_hook( __FILE__, array( $wsf_add_on_woocommerce, 'activate' ) );

add_action( 'wsf_plugins_loaded', function () {

	include 'includes/classes/class-ws-form-action-woocommerce.php';
} );
