<?php
/**
 * Override filter requests of WP Grid Builder
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
 * Filter
 *
 * @class WP_Grid_Builder_Bricks\Includes\Filter
 * @since 1.0.0
 */
final class Filter {

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {

		// We hook after caching add-on to allow caching.
		add_action( 'wp_grid_builder/async/render', [ $this, 'maybe_handle' ], 100 );
		add_action( 'wp_grid_builder/async/refresh', [ $this, 'maybe_handle' ], 100 );
		add_action( 'wp_grid_builder/async/search', [ $this, 'maybe_handle' ], 100 );

	}

	/**
	 * Check if it is a Bricks element
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $atts Template attributes.
	 */
	public function maybe_handle( $atts ) {

		if ( ! isset( $atts['is_template'] ) || 'Bricks' !== $atts['is_template'] ) {
			return;
		}

		$content = ( new Element( $atts ) )->get_content();
		$action  = explode( '/', current_filter() );

		$this->{end( $action )}( $content, $atts );

	}

	/**
	 * Render facets on first load
	 *
	 * @since 1.0.0
	 * @access protected
	 *
	 * @param string $content Element content.
	 * @param array  $atts    Template attributes.
	 */
	protected function render( $content, $atts ) {

		wp_send_json(
			apply_filters(
				'wp_grid_builder/async/render_response',
				[
					'facets' => wpgb_refresh_facets( $atts ),
				],
				$atts
			)
		);
	}

	/**
	 * Refresh facets and content
	 *
	 * @since 1.0.0
	 * @access protected
	 *
	 * @param string $content Element content.
	 * @param array  $atts    Template attributes.
	 */
	protected function refresh( $content, $atts ) {

		wp_send_json(
			apply_filters(
				'wp_grid_builder/async/refresh_response',
				[
					'posts'  => $content,
					'facets' => wpgb_refresh_facets( $atts ),
				],
				$atts
			)
		);
	}

	/**
	 * Search for facet choices
	 *
	 * @since 1.0.0
	 * @access protected
	 *
	 * @param string $content Element content.
	 * @param array  $atts    Template attributes.
	 */
	protected function search( $content, $atts ) {

		wp_send_json(
			apply_filters(
				'wp_grid_builder/async/search_response',
				wpgb_search_facet_choices( $atts ),
				$atts
			)
		);
	}
}
