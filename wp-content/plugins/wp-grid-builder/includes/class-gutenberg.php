<?php
/**
 * Gutenberg
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
 * Add Gutenberg blocks
 *
 * @class WP_Grid_Builder\Includes\Gutenberg
 * @since 1.0.0
 */
class Gutenberg {

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {

		add_action( 'init', [ $this, 'register_block' ] );
		add_filter( 'block_categories_all', [ $this, 'block_category' ] );
		add_action( 'enqueue_block_assets', [ $this, 'editor_assets' ] );
		add_action( 'enqueue_block_editor_assets', [ $this, 'editor_assets' ] );

	}

	/**
	 * Whether it is the block editor or not
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param array $attributes Holds block attributes.
	 * @return boolean
	 */
	public function is_block_editor( $attributes ) {

		// Make sure we are rendering Gutenberg block.
		if ( empty( $attributes['is_gutenberg'] ) ) {
			return false;
		}

		// Make sure we are editing in Gutenberg (ServerSideRender component).
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( empty( $_GET['context'] ) || 'edit' !== $_GET['context'] ) {
			return false;
		}

		return true;

	}

	/**
	 * Register blocks
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function register_block() {

		register_block_type(
			'wp-grid-builder/grid',
			[
				'editor_script'   => WPGB_SLUG . '-gutenberg',
				'render_callback' => [ $this, 'render_grid' ],
				'attributes'      => [
					'is_main_query' => [
						'type'    => 'boolean',
						'default' => false,
					],
					'is_gutenberg'  => [
						'type'    => 'boolean',
						'default' => is_admin(),
					],
					'className'     => [
						'type'    => 'string',
						'default' => '',
					],
					'align'         => [
						'type'    => 'string',
						'default' => 'none',
					],
					'id'            => [
						'type'    => 'number',
						'default' => '',
					],
				],
			]
		);

		register_block_type(
			'wp-grid-builder/template',
			[
				'editor_script'   => WPGB_SLUG . '-gutenberg',
				'render_callback' => [ $this, 'render_template' ],
				'attributes'      => [
					'className' => [
						'type'    => 'string',
						'default' => '',
					],
					'align'     => [
						'type'    => 'string',
						'default' => 'none',
					],
					'id'        => [
						'type'    => 'string',
						'default' => '',
					],
				],
			]
		);

		register_block_type(
			'wp-grid-builder/facet',
			[
				'editor_script'   => WPGB_SLUG . '-gutenberg',
				'render_callback' => [ $this, 'render_facet' ],
				'attributes'      => [
					'className'    => [
						'type'    => 'string',
						'default' => '',
					],
					'is_gutenberg' => [
						'type'    => 'boolean',
						'default' => is_admin(),
					],
					'align'        => [
						'type'    => 'string',
						'default' => 'none',
					],
					'grid'         => [
						'type'    => [ 'string', 'number' ],
						'default' => '',
					],
					'style'        => [
						'type'    => 'number',
						'default' => '',
					],
					'id'           => [
						'type'    => 'number',
						'default' => '',
					],
				],
			]
		);
	}

	/**
	 * Add custom category for blocks
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $categories Holds Gutenberg categories.
	 * @return array
	 */
	public function block_category( $categories ) {

		return array_merge(
			$categories,
			[
				[
					'slug'  => 'wp_grid_builder',
					'title' => WPGB_NAME,
				],
			]
		);
	}

	/**
	 * Enqueue Gutenberg block assets
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function editor_assets() {

		if ( ! is_admin() ) {
			return;
		}

		wp_enqueue_script( WPGB_SLUG . '-gutenberg' );
		wp_enqueue_style( WPGB_SLUG . '-gutenberg' );
		$this->localize_script();

	}

	/**
	 * Localize script
	 *
	 * @since 1.6.8 mainQuery argument for FSE editor.
	 * @since 1.0.0
	 */
	public function localize_script() {

		global $wp_scripts;

		$data = $wp_scripts->get_data( WPGB_SLUG . '-gutenberg', 'data' );

		if ( ! empty( $data ) ) {
			return;
		}

		$data = array_merge(
			apply_filters( 'wp_grid_builder/frontend/localize_script', [] ),
			[
				'frontStyles'    => wpgb_get_styles(),
				'frontScripts'   => wpgb_get_scripts(),
				'renderBlocks'   => (bool) wpgb_get_option( 'render_blocks' ),
				'hasTemplates'   => count( apply_filters( 'wp_grid_builder/templates', [] ) ) > 0,
				'blockProviders' => apply_filters( 'wp_grid_builder/block/providers', [] ),
				'hasLightbox'    => true,
				'hasFacets'      => true,
				'hasGrids'       => true,
				'history'        => false,
				'shadowGrids'    => [],
				'mainQuery'      => $this->get_main_query_args(),
			]
		);

		wp_localize_script( WPGB_SLUG . '-gutenberg', WPGB_SLUG . '_settings', $data );

	}

	/**
	 * Get default main query argument
	 *
	 * @since 1.6.8
	 * @return array
	 */
	public function get_main_query_args() {

		return [
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'posts_per_page' => get_option( 'posts_per_page', 10 ),
		];
	}

	/**
	 * Setup main query arguments in grid for FSE editor
	 *
	 * @since 1.6.8
	 *
	 * @param array $attributes Holds block attributes.
	 * @return array
	 */
	public function set_grid_attributes( $attributes ) {

		if ( empty( $attributes['is_gutenberg'] ) || empty( $attributes['is_main_query'] ) ) {
			return $attributes;
		}

		unset( $attributes['is_main_query'] );
		return array_merge( $this->get_main_query_args(), $attributes );

	}

	/**
	 * Render grid block
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $attributes Holds block attributes.
	 * @return string
	 */
	public function render_grid( $attributes ) {

		$attributes = $this->set_grid_attributes( $attributes );

		ob_start();
		wpgb_render_grid( $attributes );
		$this->enqueue_styles( $attributes );
		return ob_get_clean();

	}

	/**
	 * Render template block
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $attributes Holds block attributes.
	 * @return string
	 */
	public function render_template( $attributes ) {

		ob_start();
		wpgb_render_template( $attributes );
		return ob_get_clean();

	}

	/**
	 * Render grid block
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $attributes Holds block attributes.
	 * @return string
	 */
	public function render_facet( $attributes ) {

		ob_start();
		wpgb_render_facet( $attributes );
		$this->inline_styles( $attributes );
		return ob_get_clean();

	}

	/**
	 * Enqueue block styles in Gutenberg editor
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $attributes Holds block attributes.
	 */
	public function enqueue_styles( $attributes ) {

		// Make sure we are rendering in the editor.
		if ( ! $this->is_block_editor( $attributes ) ) {
			return;
		}

		// Enqueue plugin styles.
		wpgb_enqueue_styles();

		// Add inline styles generated by a grid only.
		$styles = wp_styles();
		$styles->print_inline_style( WPGB_SLUG . '-style' );
		$styles->do_item( WPGB_SLUG . '-grids' );
		$styles->do_item( WPGB_SLUG . '-fonts' );

	}

	/**
	 * Inline facet styles in Gutenberg editor
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param array $attributes Holds block attributes.
	 */
	public function inline_styles( $attributes ) {

		// Make sure we are rendering in the editor.
		if ( ! $this->is_block_editor( $attributes ) ) {
			return;
		}

		wpgb_print_facet_style();
		wpgb_print_facet_fonts();

	}
}
