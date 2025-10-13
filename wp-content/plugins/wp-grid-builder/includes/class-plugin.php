<?php
/**
 * Initialize plugin
 *
 * @package   WP Grid Builder
 * @author    Loïc Blascos
 * @copyright 2019-2024 Loïc Blascos
 */

namespace WP_Grid_Builder\Includes;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main Inctance of the plugin
 *
 * @class WP_Grid_Builder\Includes\Plugin
 * @since 2.0.0
 */
final class Plugin {

	use Singleton;

	/**
	 * Constructor
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function __construct() {

		add_action( 'plugins_loaded', [ $this, 'init' ], 0 );
		add_action( 'init', [ $this, 'load_textdomain' ], 0 );
		add_action( 'admin_init', [ $this, 'check_tables' ], 0 );
		add_action( 'wp_initialize_site', [ $this, 'insert_site' ] );
		add_action( 'wpmu_drop_tables', [ $this, 'delete_site' ] );
		add_action( 'upgrader_process_complete', [ $this, 'update' ], 10, 2 );

		register_activation_hook( WPGB_FILE, [ $this, 'activation' ] );
		register_deactivation_hook( WPGB_FILE, [ $this, 'deactivation' ] );

	}

	/**
	 * Init instances
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function init() {

		do_action( 'wp_grid_builder/loaded' );

		$this->register();
		$this->includes();
		$this->core();
		$this->admin();
		$this->integrations();
		$this->frontend();
		$this->rest_api();

		do_action( 'wp_grid_builder/init' );

	}

	/**
	 * Load textdomain
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function load_textdomain() {

		load_plugin_textdomain(
			'wp-grid-builder',
			false,
			basename( dirname( WPGB_FILE ) ) . '/languages'
		);
	}

	/**
	 * Register plugin/add-ons licenses
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function register() {

		if ( ! is_admin() ) {
			return;
		}

		array_map(
			function( $plugin ) {

				new Updater(
					new License( $plugin )
				);
			},
			apply_filters( 'wp_grid_builder/register', [ [] ] )
		);
	}

	/**
	 * Core ressources
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function core() {

		new Core\Cards();
		new Core\Grids();
		new Core\Posts();
		new Core\Facets();
		new Core\Styles();

	}

	/**
	 * Admin ressources
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function admin() {

		if ( ! is_admin() ) {
			return;
		}

		new Admin\Menu();
		new Admin\Assets();
		new Admin\Preview();
		new Admin\MetaBox();
		new Admin\Localize();

	}

	/**
	 * Frontend ressource
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function frontend() {

		new FrontEnd\Init();
		new FrontEnd\Localize();
		new FrontEnd\REST_API();
		new FrontEnd\Intercept();

		FrontEnd\Filter::get_instance();
		FrontEnd\Styles::get_instance();
		FrontEnd\Scripts::get_instance();

	}

	/**
	 * Includes ressources
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function includes() {

		new I18n();
		new Extend();
		new Indexer();
		new Registry();
		new Gutenberg();
		new Conditions();
		new Dynamic_Data();

	}

	/**
	 * Register Rest API Routes
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function rest_api() {

		new Routes\Item();
		new Routes\Grid();
		new Routes\Addon();
		new Routes\Batch();
		new Routes\Cache();
		new Routes\Fonts();
		new Routes\Icons();
		new Routes\Media();
		new Routes\Posts();
		new Routes\Terms();
		new Routes\Users();
		new Routes\Assets();
		new Routes\Blocks();
		new Routes\Import();
		new Routes\Plugin();
		new Routes\Preview();
		new Routes\Objects();
		new Routes\Indexer();
		new Routes\Options();
		new Routes\Metadata();
		new Routes\Templates();

	}

	/**
	 * Integrations ressources
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function integrations() {

		new Integrations\ACF\ACF();
		new Integrations\EDD\EDD();
		new Integrations\Jetpack\Jetpack();
		new Integrations\SearchWP\SearchWP();
		new Integrations\WP_Rocket\WP_Rocket();
		new Integrations\Relevanssi\Relevanssi();
		new Integrations\WooCommerce\WooCommerce();
		new Integrations\Blocks\Core_Query();
		new Integrations\Blocks\Core_Latest_Posts();
		new Integrations\Blocks\WC_Product_Collection();
		new Integrations\Blocks\Kadence_Posts();
		new Integrations\Blocks\Kadence_Query();
		new Integrations\Blocks\Generateblocks_Query();

	}

	/**
	 * Create custom tables if needed
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function check_tables() {

		if ( Database::is_up_to_date() ) {
			return;
		}

		$network_wide = is_plugin_active_for_network( WPGB_BASE );

		Database::create_tables( $network_wide );
		Helpers::delete_transient();

	}

	/**
	 * Create custom tables and delete transients on plugin update
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param array $upgrader_object Holds upgrader arguments.
	 * @param array $options Holds plugin options.
	 */
	public function update( $upgrader_object, $options ) {

		if ( 'update' !== $options['action'] || 'plugin' !== $options['type'] ) {
			return;
		}

		if ( empty( $options['plugins'] ) ) {
			return;
		}

		foreach ( $options['plugins'] as $plugin ) {

			if ( WPGB_BASE === $plugin ) {

				$network_wide = is_plugin_active_for_network( WPGB_BASE );

				Database::create_tables( $network_wide, true );
				Helpers::delete_transient();
				do_action( 'wp_grid_builder/updated' );
				break;

			}
		}
	}

	/**
	 * Create custom tables and delete transients on plugin activation
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param boolean $network_wide Whether to enable the plugin for all sites in the network.
	 */
	public function activation( $network_wide ) {

		Database::create_tables( $network_wide, true );
		Helpers::delete_transient();
		do_action( 'wp_grid_builder/activated' );

	}

	/**
	 * Delete transients on plugin deactivation
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function deactivation() {

		Helpers::delete_transient();
		wp_clear_scheduled_hook( 'wpgb_cron' );
		do_action( 'wp_grid_builder/deactivated' );

	}

	/**
	 * Create custom tables whenever a new site is created (multisite)
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param WP_Site|integer $new_site New site object | New site id.
	 */
	public function insert_site( $new_site ) {

		if ( ! is_plugin_active_for_network( WPGB_BASE ) ) {
			return;
		}

		switch_to_blog( $new_site->id );
		Database::create_tables( true, true );
		restore_current_blog();

	}

	/**
	 * Delete custom tables whenever a site is delete (multisite)
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param array $tables New site object.
	 */
	public function delete_site( $tables ) {

		global $wpdb;

		return array_merge(
			[
				"{$wpdb->prefix}wpgb_grids",
				"{$wpdb->prefix}wpgb_cards",
				"{$wpdb->prefix}wpgb_index",
				"{$wpdb->prefix}wpgb_facets",
			],
			$tables
		);
	}
}
