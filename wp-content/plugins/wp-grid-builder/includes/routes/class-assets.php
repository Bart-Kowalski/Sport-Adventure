<?php
/**
 * WP REST API Plugin assets route
 *
 * @package   WP Grid Builder
 * @author    Loïc Blascos
 * @copyright 2019-2024 Loïc Blascos
 */

namespace WP_Grid_Builder\includes\Routes;

use WP_Grid_Builder\Includes\File;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handle plugin assets route
 *
 * @class WP_Grid_Builder\Includes\Routes\Assets
 * @since 2.0.0
 */
final class Assets extends Base {

	/**
	 * Register custom route
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function register_routes() {

		$this->register(
			'assets',
			[
				'methods'  => \WP_REST_Server::DELETABLE,
				'callback' => [ $this, 'delete' ],
			]
		);
	}

	/**
	 * Delete plugin assets
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return WP_REST_Response
	 */
	public function delete() {

		File::delete( 'grids' );
		File::delete( 'facets' );

		return $this->ensure_response( true, __( 'Stylesheet deleted.', 'wp-grid-builder' ) );

	}
}
