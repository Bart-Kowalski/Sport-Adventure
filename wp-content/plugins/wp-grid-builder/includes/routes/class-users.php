<?php
/**
 * WP REST API Users route
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
 * Handle users route
 *
 * @class WP_Grid_Builder\Includes\Routes\Users
 * @since 2.0.0
 */
final class Users extends Base {

	/**
	 * Register custom route
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function register_routes() {

		$this->register(
			'users',
			[
				'methods'  => \WP_REST_Server::READABLE,
				'callback' => [ $this, 'get_users' ],
				'args'     => [
					'include' => [
						'type'              => 'array',
						'default'           => [],
						'sanitize_callback' => 'wp_parse_id_list',
					],
					'search'  => [
						'type'              => 'string',
						'default'           => '',
						'sanitize_callback' => 'sanitize_text_field',
					],
					'lang'    => [
						'type'              => 'string',
						'default'           => '',
						'sanitize_callback' => 'sanitize_text_field',
					],
				],
			]
		);
	}

	/**
	 * Get WordPress users by ids
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_REST_Response
	 */
	public function get_users( $request ) {

		$params = $request->get_params();

		return $this->ensure_response(
			$this->query(
				[
					'lang'           => $params['lang'],
					'fields'         => [ 'ID', 'display_name' ],
					'number'         => count( $params['include'] ) ?: 20,
					'include'        => $params['include'],
					'search'         => '*' . trim( $params['search'] ) . '*',
					'search_columns' => [ 'display_name' ],
					'orderby'        => 'display_name',
					'order'          => 'ASC',
					'count_total'    => false,
				]
			)
		);
	}

	/**
	 * Query users
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param array $query_args Holds WP_User_Query arguments.
	 * @return array
	 */
	public function query( $query_args ) {

		$users = [];
		$query = new \WP_User_Query( $query_args );

		foreach ( $query->get_results() as $user ) {

			$users[] = [
				'value' => $user->ID,
				'label' => sprintf(
					/* translators: %s: option name, %d: option count */
					__( '%1$s (#%2$d)', 'wp-grid-builder' ),
					html_entity_decode( wp_strip_all_tags( $user->display_name ), ENT_QUOTES, get_bloginfo( 'charset' ) ),
					$user->ID
				),
			];
		}

		return $users;

	}
}
