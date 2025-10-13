<?php
/**
 * WP REST API Objects routes
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
 * Handle objects routes
 *
 * @class WP_Grid_Builder\Includes\Routes\Objects
 * @since 2.0.0
 */
final class Objects extends Base {

	/**
	 * Register custom routes
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function register_routes() {

		$this->register(
			'objects',
			[
				[
					'methods'  => \WP_REST_Server::READABLE,
					'callback' => [ $this, 'query' ],
					'args'     => [
						'object'  => [
							'type'     => 'string',
							'required' => true,
							'enum'     => [ 'grids', 'cards', 'facets', 'styles' ],
						],
						'fields'  => [
							'type'    => 'array',
							'default' => [ '*' ],
							'items'   => [
								'type' => 'string',
								'enum' => [ '*', 'id', 'slug', 'name', 'date', 'modified_date', 'source', 'type' ],
							],
						],
						'number'  => [
							'type'              => 'integer',
							'default'           => 10,
							'minimum'           => 1,
							'maximum'           => 100,
							'sanitize_callback' => 'absint',
						],
						'paged'   => [
							'type'              => 'integer',
							'default'           => 1,
							'minimum'           => 1,
							'sanitize_callback' => 'absint',
						],
						'orderby' => [
							'type'    => 'string',
							'default' => 'date',
							'enum'    => [ 'id', 'name', 'date', 'modified_date', 'source', 'type' ],
						],
						'order'   => [
							'type'    => 'string',
							'default' => 'desc',
							'enum'    => [ 'desc', 'asc' ],
						],
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
					],
				],
				[
					'methods'  => \WP_REST_Server::CREATABLE,
					'callback' => [ $this, 'duplicate' ],
					'args'     => [
						'object'  => [
							'type'     => 'string',
							'required' => true,
							'enum'     => [ 'grids', 'cards', 'facets', 'styles' ],
						],
						'include' => [
							'type'              => 'array',
							'default'           => [],
							'sanitize_callback' => 'wp_parse_id_list',
						],
					],
				],
				[
					'methods'  => \WP_REST_Server::DELETABLE,
					'callback' => [ $this, 'delete' ],
					'args'     => [
						'object'  => [
							'type'     => 'string',
							'required' => true,
							'enum'     => [ 'grids', 'cards', 'facets', 'styles' ],
						],
						'include' => [
							'type'              => 'array',
							'default'           => [],
							'sanitize_callback' => 'wp_parse_id_list',
						],
					],
				],
			]
		);
	}

	/**
	 * Query objects
	 *
	 * @since 2.0.0
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_REST_Response
	 */
	public function query( $request ) {

		$params = $request->get_params();
		$query  = [
			'select'  => implode( ',', $params['fields'] ),
			'from'    => $params['object'],
			'limit'   => count( $params['include'] ) ?: $params['number'],
			'paged'   => $params['paged'],
			'orderby' => $params['orderby'] . ' ' . $params['order'],
			'id'      => $params['include'],
			's'       => trim( remove_accents( $params['search'] ) ),
		];

		try {

			if ( isset( $params['count'] ) ) {
				$items = (int) Database::count_items( $query );
			} else {
				$items = (array) Database::query_results( $query );
			}
		} catch ( \Exception $error ) {
			return $this->ensure_response( [], $error->getMessage(), 'error' );
		}

		return $this->ensure_response( $items );

	}

	/**
	 * Duplicate objects
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_REST_Response
	 */
	public function duplicate( $request ) {

		$params  = $request->get_params();
		$object  = $params['object'];
		$include = $params['include'];

		do_action( 'wp_grid_builder/before_duplicate/' . $object, $include );

		try {
			$include = Database::duplicate_row( $object, $include );
		} catch ( \Exception $error ) {
			return $this->ensure_response( false, $error->getMessage(), 'error' );
		}

		do_action( 'wp_grid_builder/duplicate/' . $object, $include );

		return $this->ensure_response(
			$include,
			sprintf(
				/* translators: %d: number of duplicated items */
				_n( '%d item duplicated.', '%d items duplicated.', max( count( $include ), 1 ), 'wp-grid-builder' ),
				count( $include )
			)
		);
	}

	/**
	 * Delete objects
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_REST_Response
	 */
	public function delete( $request ) {

		$params  = $request->get_params();
		$object  = $params['object'];
		$include = $params['include'];

		do_action( 'wp_grid_builder/before_delete/' . $object, $include );

		try {
			$include = Database::delete_row( $object, $include );
		} catch ( \Exception $error ) {
			return $this->ensure_response( false, $error->getMessage(), 'error' );
		}

		do_action( 'wp_grid_builder/delete/' . $object, $include );

		return $this->ensure_response(
			$include,
			sprintf(
				/* translators: %d: number of deleted items */
				_n( '%d item deleted.', '%d items deleted.', max( count( $include ), 1 ), 'wp-grid-builder' ),
				count( $include )
			)
		);
	}
}
