<?php
/**
 * WP REST Grid route
 *
 * @package   WP Grid Builder
 * @author    Loïc Blascos
 * @copyright 2019-2024 Loïc Blascos
 */

namespace WP_Grid_Builder\includes\Routes;

use WP_Grid_Builder\Includes\Database;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handle grid route
 *
 * @class WP_Grid_Builder\Includes\Routes\Grid
 * @since 2.0.0
 */
final class Grid extends Base {

	/**
	 * Register custom route
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function register_routes() {

		$this->register(
			'grid',
			[
				'methods'  => \WP_REST_Server::READABLE,
				'callback' => [ $this, 'check' ],
				'args'     => [
					'include' => [
						'type'              => 'array',
						'required'          => true,
						'sanitize_callback' => 'wp_parse_id_list',
					],
					'type'    => [
						'type'     => 'string',
						'required' => true,
						'enum'     => [ 'masonry', 'justified', 'metro' ],
					],
				],
			]
		);
	}

	/**
	 * Check grid cards
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_REST_Response
	 */
	public function check( $request ) {

		$type    = $request->get_param( 'type' );
		$include = $request->get_param( 'include' );

		if ( empty( $include ) ) {
			return $this->ensure_response( [] );
		}

		$cards = Database::query_results(
			[
				'select' => 'type, name',
				'from'   => 'cards',
				'id'     => $include,
			]
		);

		$cards = array_map(
			function( $card ) use ( $type ) {

				if ( 'masonry' === $card['type'] && 'masonry' !== $type ) {
					return $card['name'];
				}

				return '';

			},
			$cards
		);

		return $this->ensure_response( array_values( array_filter( $cards ) ) );

	}
}
