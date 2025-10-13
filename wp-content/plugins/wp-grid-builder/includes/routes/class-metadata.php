<?php
/**
 * WP REST API Metadata routes
 *
 * @package   WP Grid Builder
 * @author    Loïc Blascos
 * @copyright 2019-2024 Loïc Blascos
 */

namespace WP_Grid_Builder\includes\Routes;

use WP_Grid_Builder\Includes\Settings\Settings;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handle metadata routes
 *
 * @class WP_Grid_Builder\Includes\Routes\Metadata
 * @since 2.0.0
 */
final class Metadata extends Base {

	/**
	 * Register custom routes
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function register_routes() {

		$this->register(
			'metadata',
			[
				[
					'methods'             => \WP_REST_Server::EDITABLE,
					'callback'            => [ $this, 'update' ],
					'permission_callback' => [ $this, 'permission_callback' ],
					'args'                => [
						'object' => [
							'type'     => 'string',
							'required' => true,
							'enum'     => [ 'post', 'term', 'user', 'comment' ],
						],
						'value'  => [
							'type'     => 'mixed',
							'required' => true,
						],
						'key'    => [
							'type'     => 'string',
							'required' => true,
						],
						'id'     => [
							'type'              => 'integer',
							'required'          => true,
							'sanitize_callback' => 'absint',
						],
					],
				],
				[
					'methods'  => \WP_REST_Server::READABLE,
					'callback' => [ $this, 'query' ],
					'args'     => [
						'object'  => [
							'type'    => 'string',
							'default' => 'post',
							'enum'    => [ 'post', 'term', 'user', 'comment', 'registered' ],
						],
						'number'  => [
							'type'              => 'integer',
							'default'           => 20,
							'minimum'           => 1,
							'sanitize_callback' => 'absint',
						],
						'include' => [
							'type'              => 'array',
							'default'           => [],
							'sanitize_callback' => function( $list ) {

								if ( ! is_array( $list ) ) {
									return preg_split( '/[,]+/', $list, -1, PREG_SPLIT_NO_EMPTY );
								}

								$list = array_filter( $list, 'is_scalar' );

								return $list;

							},
						],
						'search'  => [
							'type'              => 'string',
							'default'           => '',
							'sanitize_callback' => 'sanitize_text_field',
						],
						'field'   => [
							'type'    => 'string',
							'default' => 'name',
							'enum'    => [ 'key', 'name' ],
						],
						'lang'    => [
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
	 * Handle REST API permission callback
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_Error|boolean
	 */
	public function permission_callback( $request ) {

		if ( ! current_user_can( "edit_{$request->get_param( 'object' )}", $request->get_param( 'id' ) ) ) {

			return new \WP_Error(
				'rest_forbidden',
				/* translators: %s: Object type. */
				__( 'Sorry, you are not allowed to edit this item.', 'wp-grid-builder' ),
				[ 'status' => $this->authorization_status_code() ]
			);
		}

		return true;

	}

	/**
	 * Update WordPress metadata
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_REST_Response
	 */
	public function update( $request ) {

		$object_type  = $request->get_param( 'object' );
		$meta_key     = $request->get_param( 'key' );
		$object_id    = $request->get_param( 'id' );

		// Merge all settings to keep term metadata (colors).
		$old_metadata = get_metadata( $object_type, $object_id, $meta_key, true );
		$new_metadata = ( new Settings() )->sanitize( 'post', $request->get_param( 'value' ) );
		$new_metadata = apply_filters( 'wp_grid_builder/settings/save_fields', $new_metadata, $object_type, $object_id );
		$new_metadata = wp_parse_args( $new_metadata, $old_metadata );

		update_metadata( $object_type, $object_id, wp_slash( $meta_key ), wp_slash( $new_metadata ) );

		return $this->ensure_response( true, __( 'Item saved.', 'wp-grid-builder' ) );

	}

	/**
	 * Get WordPress metadata
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_REST_Response
	 */
	public function query( $request ) {

		$params = $request->get_params();

		switch ( $params['object'] ) {
			case 'registered':
				$metadata = $this->get_meta( 'registered', $params );
				break;
			case 'user':
				$metadata = $this->get_meta( 'user', $params );
				break;
			case 'term':
				$metadata = $this->get_meta( 'term', $params );
				break;
			default:
				$metadata = $this->get_meta( 'post', $params );
		}

		return $this->ensure_response( array_filter( (array) $metadata ) );

	}

	/**
	 * Get metadata
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param string $object Metadata object.
	 * @param array  $params Holds requested parameters.
	 * @return array
	 */
	public function get_meta( $object, $params ) {

		if ( 'registered' === $object ) {
			return $this->registered_meta( $params );
		}

		if ( ! empty( $params['include'] ) ) {
			return $this->query_meta( $object, $params );
		}

		return $this->search_meta( $object, $params );

	}

	/**
	 * Search in registered fields
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param array $params Holds requested parameters.
	 * @return array
	 */
	public function registered_meta( $params ) {

		$results = [];
		$fields  = apply_filters( 'wp_grid_builder/custom_fields', [], $params['field'] );
		$search  = trim( remove_accents( $params['search'] ) );

		foreach ( $fields as $type => $args ) {

			$options = [];

			foreach ( $args as $key => $name ) {

				if (
					// get registered field.
					(
						$params['include'] &&
						in_array( $key, $params['include'], true )
					) ||
					// Search registered field.
					(
						$search && (
							stripos( $key, $search ) !== false ||
							stripos( $name, $search ) !== false ||
							stripos( $type, $search ) !== false
						)
					)
				) {

					$options[] = [
						'value' => $key,
						'label' => str_replace( '&rsaquo;', '>', $name ),
					];
				}
			}

			$results[] = [
				'label'   => $type,
				'options' => $options,
			];
		}

		return $results;

	}

	/**
	 * Search object metadata
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param string $object Metadata object.
	 * @param array  $params Holds requested parameters.
	 * @return array
	 */
	public function search_meta( $object, $params ) {

		global $wpdb;

		$wpdb->hide_errors();

		$exclude = $this->exclude();
		$holders = rtrim( str_repeat( '%s,', count( $exclude ) + 2 ), ',' );
		$search  = trim( remove_accents( $params['search'] ) );

		$results = $wpdb->get_results(
			// phpcs:disable WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$wpdb->prepare(
				"SELECT DISTINCT meta_key as value, meta_key as label
				FROM {$wpdb->{$object . 'meta'}}
				WHERE meta_key LIKE %s
				AND meta_key NOT LIKE %s
				AND meta_key NOT IN ($holders)
				ORDER BY CHAR_LENGTH(meta_key) ASC, meta_key ASC
				LIMIT %d",
				array_merge(
					[ '%' . $wpdb->esc_like( $search ) . '%' ],
					[ '%_oembed_%' ],
					array_merge(
						$exclude,
						[
							'_edit_last',
							'_edit_lock',
						]
					),
					(array) $params['number']
				)
			)
			// phpcs:enable WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		);

		if ( $wpdb->last_error ) {
			return [];
		}

		return $results;

	}

	/**
	 * Query object metadata
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param string $object Metadata object.
	 * @param array  $params Holds requested parameters.
	 * @return array
	 */
	public function query_meta( $object, $params ) {

		global $wpdb;

		$wpdb->hide_errors();

		$exclude  = $this->exclude();
		$holders1 = rtrim( str_repeat( '%s,', count( $params['include'] ) ), ',' );
		$holders2 = rtrim( str_repeat( '%s,', count( $exclude ) + 2 ), ',' );

		$results = $wpdb->get_results(
			// phpcs:disable WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$wpdb->prepare(
				"SELECT DISTINCT meta_key as value, meta_key as label
				FROM {$wpdb->{$object . 'meta'}}
				WHERE meta_key IN ($holders1)
				AND meta_key NOT IN ($holders2)
				LIMIT %d",
				array_merge(
					$params['include'],
					array_merge(
						$exclude,
						[
							'_edit_last',
							'_edit_lock',
						]
					),
					(array) count( $params['include'] )
				)
			)
			// phpcs:enable WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		);

		if ( $wpdb->last_error ) {
			return [];
		}

		return $results;

	}

	/**
	 * Get metadata keys to exclude from search
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return array
	 */
	public function exclude() {

		$exclude = apply_filters( 'wp_grid_builder/custom_fields', [] );

		if ( empty( $exclude ) ) {
			return [];
		}

		$exclude = array_values( $exclude );

		if ( ! is_array( $exclude ) || empty( $exclude ) ) {
			return [];
		}

		return array_filter(
			array_keys( array_merge( ...$exclude ) ),
			function( $item ) {
				return (
					'acf/' !== substr( $item, 0, 4 ) &&
					'meta-box/' !== substr( $item, 0, 9 )
				);
			}
		);
	}
}
