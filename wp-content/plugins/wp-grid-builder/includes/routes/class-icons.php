<?php
/**
 * WP REST API Icons route
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
 * Handle icons route
 *
 * @class WP_Grid_Builder\Includes\Routes\Icons
 * @since 2.0.0
 */
final class Icons extends Base {

	/**
	 * Register custom route
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function register_routes() {

		$this->register(
			'icons',
			[
				'methods'  => \WP_REST_Server::READABLE,
				'callback' => [ $this, 'get_icons' ],
			]
		);
	}

	/**
	 * Get icons
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return WP_REST_Response
	 */
	public function get_icons() {

		return $this->ensure_response(
			(array) apply_filters( 'wp_grid_builder/icons', [] )
		);
	}
}
