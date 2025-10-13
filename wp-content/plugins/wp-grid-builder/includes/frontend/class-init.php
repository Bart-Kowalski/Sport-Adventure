<?php
/**
 * Init
 *
 * @package   WP Grid Builder
 * @author    Loïc Blascos
 * @copyright 2019-2024 Loïc Blascos
 */

namespace WP_Grid_Builder\Includes\FrontEnd;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Frontend init class
 *
 * @class WP_Grid_Builder\FrontEnd\Init
 * @since 1.0.0
 */
final class Init extends Async {

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {

		parent::__construct();
		$this->includes();
		$this->hooks();

	}

	/**
	 * Register plugin functions
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function includes() {

		require_once WPGB_PATH . 'includes/frontend/blocks/base.php';
		require_once WPGB_PATH . 'includes/frontend/blocks/post.php';
		require_once WPGB_PATH . 'includes/frontend/blocks/user.php';
		require_once WPGB_PATH . 'includes/frontend/blocks/term.php';
		require_once WPGB_PATH . 'includes/frontend/blocks/design.php';
		require_once WPGB_PATH . 'includes/frontend/blocks/product.php';
		require_once WPGB_PATH . 'includes/frontend/blocks/common.php';
		require_once WPGB_PATH . 'includes/frontend/functions/grids.php';
		require_once WPGB_PATH . 'includes/frontend/functions/assets.php';
		require_once WPGB_PATH . 'includes/frontend/functions/facets.php';
		require_once WPGB_PATH . 'includes/frontend/functions/styles.php';
		require_once WPGB_PATH . 'includes/frontend/functions/layout.php';
		require_once WPGB_PATH . 'includes/frontend/functions/helpers.php';
		require_once WPGB_PATH . 'includes/frontend/functions/sources.php';
		require_once WPGB_PATH . 'includes/frontend/functions/templates.php';

	}

	/**
	 * Init hooks
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function hooks() {

		add_action( 'wp_grid_builder/facet/render', [ $this, 'register_facet_assets' ] );
		add_action( 'wp_grid_builder/grid/render', [ $this, 'register_grid_assets' ] );
		add_filter( 'wp_grid_builder/async/refresh_response', [ $this, 'result_counts' ] );

	}

	/**
	 * Register facet script & style
	 *
	 * @since 1.2.1
	 * @access public
	 */
	public function register_facet_assets() {

		wpgb_register_style( WPGB_SLUG . '-facets' );
		wpgb_register_script( WPGB_SLUG . '-facets' );

		if ( wpgb_get_option( 'load_polyfills' ) ) {
			wpgb_register_script( WPGB_SLUG . '-polyfills' );
		}
	}

	/**
	 * Register grid scripts & style
	 *
	 * @since 1.2.1
	 * @access public
	 */
	public function register_grid_assets() {

		wpgb_register_style( WPGB_SLUG . '-style' );
		wpgb_register_script( WPGB_SLUG . '-layout' );

		if ( wpgb_get_option( 'load_polyfills' ) ) {
			wpgb_register_script( WPGB_SLUG . '-polyfills' );
		}

		if ( 'wp_grid_builder' === wpgb_get_option( 'lightbox_plugin' ) ) {
			wpgb_register_script( WPGB_SLUG . '-lightbox' );
		}
	}

	/**
	 * Return number of results in response
	 *
	 * @since 1.2.1
	 * @access public
	 *
	 * @param array $response Holds refresh response.
	 * @return array
	 */
	public function result_counts( $response ) {

		$response['total'] = wpgb_get_found_objects();

		return $response;

	}

	/**
	 * Render facets on first load
	 *
	 * @since 1.2.1
	 * @access public
	 *
	 * @param array $atts Grid/Template attributes.
	 * @return array
	 */
	public function render( $atts ) {

		wpgb_query_content( $atts );

		return apply_filters(
			'wp_grid_builder/async/render_response',
			[
				'facets' => wpgb_refresh_facets( $atts ),
			],
			$atts
		);
	}

	/**
	 * Refresh facets and content
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $atts Grid/Template attributes.
	 * @return array
	 */
	public function refresh( $atts ) {

		return apply_filters(
			'wp_grid_builder/async/refresh_response',
			[
				'posts'  => wpgb_refresh_content( $atts ),
				'facets' => wpgb_refresh_facets( $atts ),
			],
			$atts
		);
	}

	/**
	 * Search facet choices
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $atts Grid/Template attributes.
	 * @return array
	 */
	public function search( $atts ) {

		wpgb_query_content( $atts );

		return apply_filters(
			'wp_grid_builder/async/search_response',
			wpgb_search_facet_choices( $atts ),
			$atts
		);
	}
}
