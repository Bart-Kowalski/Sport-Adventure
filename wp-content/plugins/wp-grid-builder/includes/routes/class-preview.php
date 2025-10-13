<?php
/**
 * WP REST API Preview facet route
 *
 * @package   WP Grid Builder
 * @author    Loïc Blascos
 * @copyright 2019-2024 Loïc Blascos
 */

namespace WP_Grid_Builder\includes\Routes;

use WP_Grid_Builder\Includes\Helpers;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handle Preview route
 *
 * @class WP_Grid_Builder\Includes\Routes\Preview
 * @since 2.0.0
 */
final class Preview extends Base {

	/**
	 * Register custom route
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function register_routes() {

		$this->register(
			'preview',
			[
				'methods'  => \WP_REST_Server::READABLE,
				'callback' => [ $this, 'render' ],
			]
		);
	}

	/**
	 * Render facet styles
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_REST_Response
	 */
	public function render( $request ) {

		ob_start();
		Helpers::get_template( 'layout/icons', '', true );
		wpgb_render_facet(
			[
				'id'      => 1,
				'grid'    => 'wpgb_facet_preview',
				'class'   => 'wpgb-style-preview',
				'preview' => true,
			]
		);

		wp_enqueue_script( WPGB_SLUG );
		wpgb_enqueue_styles();
		wpgb_enqueue_scripts();
		$this->enqueue_assets();

		return $this->ensure_response( ob_get_clean() );

	}

	/**
	 * Enqueue all scripts/styles from WP Grid Builder
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function enqueue_assets() {

		global $wp_styles, $wp_scripts;

		if ( isset( $wp_styles->queue ) ) {

			foreach ( $wp_styles->queue as $handle ) {

				if ( WPGB_SLUG === substr( $handle, 0, strlen( WPGB_SLUG ) ) ) {
					wp_styles()->do_item( $handle );
				}
			}
		}

		if ( isset( $wp_scripts->queue ) ) {

			foreach ( $wp_scripts->queue as $handle ) {

				if ( WPGB_SLUG === substr( $handle, 0, strlen( WPGB_SLUG ) ) ) {
					wp_scripts()->do_item( $handle );
				}
			}
		}
	}
}
