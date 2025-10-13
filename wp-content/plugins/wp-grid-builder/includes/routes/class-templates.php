<?php
/**
 * WP REST API Templates routes
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
 * Handle templates routes
 *
 * @class WP_Grid_Builder\Includes\Routes\Templates
 * @since 2.0.0
 */
final class Templates extends Base {

	/**
	 * Register custom routes
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function register_routes() {

		$this->register(
			'templates',
			[
				[
					'methods'  => \WP_REST_Server::READABLE,
					'callback' => [ $this, 'query' ],
					'args'     => [
						'number'  => [
							'type'              => 'integer',
							'default'           => 10,
							'minimum'           => 1,
							'maximum'           => 100,
							'sanitize_callback' => 'absint',
						],
						'include' => [
							'type'              => 'string',
							'default'           => '',
							'sanitize_callback' => 'sanitize_text_field',
						],
						'search'  => [
							'type'              => 'string',
							'default'           => '',
							'sanitize_callback' => 'sanitize_text_field',
						],
					],
				],
			]
		);
	}

	/**
	 * Query templates
	 *
	 * @since 2.0.0
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_REST_Response
	 */
	public function query( $request ) {

		$results = [];
		$params  = $request->get_params();
		$search  = trim( remove_accents( $params['search'] ?: $params['include'] ) );
		$number  = trim( remove_accents( $params['number'] ) );
		$items   = array_keys( apply_filters( 'wp_grid_builder/templates', [] ) );

		foreach ( $items as $item ) {

			$options = [];

			if ( ! $search || stripos( $item, $search ) === false ) {
				continue;
			}

			$results[] = [
				'value' => $item,
				'label' => $item,
			];
		}

		return $this->ensure_response( array_slice( $results, 0, $number ) );

	}
}
