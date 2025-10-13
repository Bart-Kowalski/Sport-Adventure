<?php
/**
 * Plugin
 *
 * @package   WP Grid Builder - Bricks
 * @author    Loïc Blascos
 * @copyright 2019-2025 Loïc Blascos
 */

namespace WP_Grid_Builder_Bricks\Includes;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main Instance of the plugin
 *
 * @class WP_Grid_Builder_Bricks\Includes\Plugin
 * @since 1.0.0
 */
final class Plugin {

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {

		add_action( 'after_setup_theme', [ $this, 'init' ] );
		add_filter( 'wp_grid_builder/register', [ $this, 'register' ] );
		add_filter( 'wp_grid_builder/plugin_info', [ $this, 'plugin_info' ], 10, 2 );

	}

	/**
	 * Init instances
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function init() {

		if ( ! defined( 'BRICKS_VERSION' ) || version_compare( BRICKS_VERSION, '1.5.5', '<' ) ) {

			add_action( 'admin_notices', '\WP_Grid_Builder_Bricks\bricks_notice' );
			return;

		}

		new Providers();
		new Builder();
		new Render();
		new Filter();
		new Extend();

	}

	/**
	 * Register add-on
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $addons Holds registered add-ons.
	 * @return array
	 */
	public function register( $addons ) {

		$addons[] = [
			'name'    => 'Bricks',
			'slug'    => WPGB_BRICKS_BASE,
			'option'  => 'wpgb_bricks',
			'version' => WPGB_BRICKS_VERSION,
		];

		return $addons;

	}

	/**
	 * Set plugin info
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array  $info Holds plugin info.
	 * @param string $name Current plugin name.
	 * @return array
	 */
	public function plugin_info( $info, $name ) {

		if ( 'Bricks' !== $name ) {
			return $info;
		}

		$info['icons'] = [
			'1x' => WPGB_BRICKS_URL . 'assets/imgs/icon.png',
			'2x' => WPGB_BRICKS_URL . 'assets/imgs/icon.png',
		];

		if ( ! empty( $info['info'] ) ) {

			$info['info']->banners = [
				'low'  => WPGB_BRICKS_URL . 'assets/imgs/banner.png',
				'high' => WPGB_BRICKS_URL . 'assets/imgs/banner.png',
			];
		}

		return $info;

	}
}
