<?php
/**
 * WP REST API Blocks route
 *
 * @package   WP Grid Builder
 * @author    Loïc Blascos
 * @copyright 2019-2024 Loïc Blascos
 */

namespace WP_Grid_Builder\includes\Routes;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handle blocks route
 *
 * @class WP_Grid_Builder\Includes\Routes\Blocks
 * @since 2.0.0
 */
final class Blocks extends Base {

	/**
	 * Register custom route
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function register_routes() {

		$this->register(
			'blocks',
			[
				'methods'  => \WP_REST_Server::READABLE,
				'callback' => [ $this, 'get_blocks' ],
			]
		);
	}

	/**
	 * Get editor blocks
	 *
	 * @since 2.0.0
	 *
	 * @return WP_REST_Response
	 */
	public function get_blocks() {

		return $this->ensure_response(
			array_map(
				function( $block ) {

					unset( $block['controls'] );
					return $block;

				},
				apply_filters( 'wp_grid_builder/blocks', [] )
			)
		);
	}
}
