<?php
/**
 * WP REST API Handle batch route
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
 * Handle batch route
 *
 * @class WP_Grid_Builder\Includes\Routes\Batch
 * @since 2.0.0
 */
final class Batch extends Base {

	/**
	 * Register custom route
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function register_routes() {

		$this->register(
			'batch',
			[
				'methods'  => \WP_REST_Server::ALLMETHODS,
				'callback' => [ $this, 'batch' ],
				'args'     => [
					'requests' => [
						'type'     => 'array',
						'required' => true,
					],
				],
			]
		);
	}

	/**
	 * Batch requests
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return array
	 */
	public function batch( $request ) {

		return array_map(
			[ $this, 'request' ],
			$request->get_params()['requests']
		);
	}

	/**
	 * Request
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param array|string $request Holds request(s) to process.
	 * @return WP_REST_Response
	 */
	public function request( $request ) {

		global $wp_rest_server;

		if ( is_string( $request ) ) {
			$request = [ 'path' => $request ];
		}

		// phpcs:disable WordPress.Security
		$request = wp_parse_args(
			$request,
			[
				'path'    => '',
				'method'  => isset( $_SERVER['REQUEST_METHOD'] ) ? strtoupper( wp_unslash( $_SERVER['REQUEST_METHOD'] ) ) : 'POST',
				'body'    => wp_unslash( $_POST ),
				'headers' => wp_unslash( $_SERVER ),
			]
		);
		// phpcs:enable WordPress.Security

		$query_params = [];
		$parsed_url   = wp_parse_url( $request['path'] );
		$rest_request = new \WP_REST_Request( $request['method'], $parsed_url['path'] );

		$rest_request->set_headers( $wp_rest_server->get_headers( $request['headers'] ) );
		$rest_request->set_body_params( $request['body'] );
		$rest_request->set_query_params( $request['body'] );

		if ( ! empty( $parsed_url['query'] ) ) {

			wp_parse_str( $parsed_url['query'], $query_params );
			$rest_request->set_query_params( $query_params );

		}

		$response = $wp_rest_server->dispatch( $rest_request );
		$response = apply_filters( 'rest_post_dispatch', $response, $wp_rest_server, $rest_request );

		return $response->get_data();

	}
}
