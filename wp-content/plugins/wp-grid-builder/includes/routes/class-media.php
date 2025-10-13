<?php
/**
 * WP REST Media route
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
 * Handle media route
 *
 * @class WP_Grid_Builder\Includes\Routes\Media
 * @since 2.0.0
 */
final class Media extends Base {

	/**
	 * Register custom route
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function register_routes() {

		$this->register(
			'media',
			[
				'methods'  => \WP_REST_Server::READABLE,
				'callback' => [ $this, 'query' ],
				'args'     => [
					'include' => [
						'type'              => 'array',
						'default'           => [],
						'sanitize_callback' => 'wp_parse_id_list',
					],
				],
			]
		);
	}

	/**
	 * Get media by ids
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_REST_Response
	 */
	public function query( $request ) {

		$params = $request->get_params();
		$query  = new \WP_Query(
			[
				'post_type'      => 'attachment',
				'post_status'    => 'any',
				'posts_per_page' => count( $params['include'] ),
				'post__in'       => $params['include'],
				'orderby'        => 'post__in',
				'no_found_rows'  => true,
			]
		);

		$media = array_map( 'wp_prepare_attachment_for_js', $query->posts );
		$media = array_filter( $media );

		return $this->ensure_response( $media );

	}
}
