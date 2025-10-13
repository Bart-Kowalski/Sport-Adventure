<?php
/**
 * Bricks Builder
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
 * Builder
 *
 * @class WP_Grid_Builder_Bricks\Includes\Builder
 * @since 1.0.0
 */
final class Builder {

	use Helpers;

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {

		add_action( 'init', [ $this, 'register_elements' ], 11 );
		add_action( 'wp_footer', [ $this, 'dequeue_assets' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'builder_assets' ] );
		add_filter( 'wp_grid_builder/frontend/enqueue_scripts', [ $this, 'unset_assets' ] );
		add_filter( 'wp_grid_builder/frontend/enqueue_styles', [ $this, 'unset_assets' ] );
		add_filter( 'wp_grid_builder/facet/choices', [ $this, 'add_choices' ], 10, 2 );
		add_filter( 'wp_grid_builder_caching/bypass', [ $this, 'bypass_cache' ] );
		add_filter( 'wp_grid_builder_map/has_facet', [ $this, 'has_facet' ] );

	}

	/**
	 * Register builder elements
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function register_elements() {

		\Bricks\Elements::register_element( WPGB_BRICKS_PATH . 'includes/elements/class-grid.php' );
		\Bricks\Elements::register_element( WPGB_BRICKS_PATH . 'includes/elements/class-facet.php' );

	}

	/**
	 * Dequeue WP Grid Builder assets in editor
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function dequeue_assets() {

		if ( ! bricks_is_builder() ) {
			return;
		}

		foreach ( wpgb_get_styles() as $args ) {
			! empty( $args['handle'] ) && wp_dequeue_style( $args['handle'] );
		}

		foreach ( wpgb_get_scripts() as $args ) {
			! empty( $args['handle'] ) && wp_dequeue_script( $args['handle'] );
		}
	}

	/**
	 * Unset WP Grid Builder assets in preview mode to prevent conflict with grid shortcodes
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $assets Holds assets to enqueue.
	 * @return array
	 */
	public function unset_assets( $assets ) {

		if ( bricks_is_builder() ) {
			$assets = [];
		}

		return $assets;

	}

	/**
	 * Enqueue builder assets
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function builder_assets() {

		if ( ! bricks_is_builder_iframe() ) {
			return;
		}

		wp_enqueue_style(
			'wpgb-bricks-builder',
			WPGB_BRICKS_URL . 'assets/css/builder.css',
			[],
			WPGB_BRICKS_VERSION
		);

		wp_enqueue_script(
			'wpgb-bricks-builder',
			WPGB_BRICKS_URL . 'assets/js/builder.js',
			[],
			WPGB_BRICKS_VERSION,
			true
		);

		$this->localize_script();

	}

	/**
	 * Localize preview script
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function localize_script() {

		$post_types = get_post_types( [ 'public' => true ] );
		unset( $post_types['attachment'] );

		$data = array_merge(
			apply_filters( 'wp_grid_builder/frontend/localize_script', [] ),
			[
				'adminUrl'       => admin_url( 'admin.php' ),
				'frontStyles'    => wpgb_get_styles(),
				'frontScripts'   => wpgb_get_scripts(),
				'renderBlocks'   => (bool) wpgb_get_option( 'render_blocks' ),
				'templates'      => array_keys( apply_filters( 'wp_grid_builder/templates', [] ) ),
				'providers'      => array_keys( apply_filters( 'wp_grid_builder_bricks/providers', [] ) ),
				'shadowGrids'    => [],
				'history'        => false,
				'hasGrids'       => true,
				'hasFacets'      => true,
				'hasLightbox'    => true,
				'loadingContent' => esc_html__( 'Please wait, loading content...', 'wpgb-bricks' ),
				'noFacetContent' => esc_html__( 'No content found in facet.', 'wpgb-bricks' ),
				'mainQuery'      => [
					'post_type'   => array_keys( $post_types ),
					'post_status' => 'publish',
				],
			]
		);

		wp_localize_script( 'wpgb-bricks-builder', 'wpgb_settings', $data );

	}

	/**
	 * Add default choices in selection facet
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $choices Holds facet choices.
	 * @param array $facet   Holds facet settings.
	 * @return array
	 */
	public function add_choices( $choices, $facet ) {

		if ( ! $this->is_builder() ) {
			return $choices;
		}

		if ( empty( $facet['type'] ) || 'selection' !== $facet['type'] ) {
			return $choices;
		}

		return [
			(object) [
				'facet_value' => 'choice_1',
				'facet_name'  => 'Choice 1',
				'facet_slug'  => '_wpgb_bb_selection',
			],
			(object) [
				'facet_value' => 'choice_2',
				'facet_name'  => 'Choice 2',
				'facet_slug'  => '_wpgb_bb_selection',
			],
			(object) [
				'facet_value' => 'choice_3',
				'facet_name'  => 'Choice 3',
				'facet_slug'  => '_wpgb_bb_selection',
			],
		];
	}

	/**
	 * Bypass cache when editing facets in editor
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param boolean $bypass Whether to bypass the cache.
	 * @return boolean
	 */
	public function bypass_cache( $bypass ) {

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( $this->is_builder() ) {
			$bypass = true;
		}

		return $bypass;

	}

	/**
	 * Localize map facet assets in editor
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param boolean $has_facet Whether there is a map facet or not.
	 * @return boolean
	 */
	public function has_facet( $has_facet ) {

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( bricks_is_builder() ) {
			$has_facet = true;
		}

		return $has_facet;

	}
}
