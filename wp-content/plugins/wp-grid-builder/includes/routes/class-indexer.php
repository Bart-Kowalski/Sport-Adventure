<?php
/**
 * WP REST API Indexer routes
 *
 * @package   WP Grid Builder
 * @author    Loïc Blascos
 * @copyright 2019-2024 Loïc Blascos
 */

namespace WP_Grid_Builder\includes\Routes;

use WP_Grid_Builder\Includes;
use WP_Grid_Builder\Includes\Database;
use WP_Grid_Builder\Includes\Helpers;
use WP_Grid_Builder\Includes\Scheduler\Queue;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handle indexer routes
 *
 * @class WP_Grid_Builder\Includes\Routes\Indexer
 * @since 2.0.0
 */
final class Indexer extends Base {

	/**
	 * Register custom routes
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function register_routes() {

		$this->register(
			'indexer',
			[
				[
					'methods'  => \WP_REST_Server::CREATABLE,
					'callback' => [ $this, 'index' ],
					'args'     => [
						'include' => [
							'type'              => 'array',
							'required'          => true,
							'sanitize_callback' => 'wp_parse_id_list',
						],
					],
				],
				[
					'methods'  => \WP_REST_Server::DELETABLE,
					'callback' => [ $this, 'delete' ],
					'args'     => [
						'include' => [
							'type'              => 'array',
							'default'           => [],
							'sanitize_callback' => 'wp_parse_id_list',
						],
					],
				],
				[
					'methods'  => \WP_REST_Server::EDITABLE,
					'callback' => [ $this, 'cancel' ],
				],
				[
					'methods'  => \WP_REST_Server::READABLE,
					'callback' => [ $this, 'status' ],
				],
			]
		);
	}

	/**
	 * Index facet(s)
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_REST_Response
	 */
	public function index( $request ) {

		( new Includes\Indexer() )->index_facets( $request->get_param( 'include' ) );

		return $this->ensure_response();

	}

	/**
	 * Delete indexer
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_REST_Response
	 */
	public function delete( $request ) {

		global $wpdb;

		$ids = $request->get_param( 'include' );

		if ( empty( $ids ) ) {

			Helpers::delete_transient();
			$wpdb->query( "TRUNCATE TABLE {$wpdb->prefix}wpgb_index" );

			return $this->ensure_response();

		}

		$facets = (array) Database::query_results(
			[
				'select' => 'id, slug',
				'from'   => 'facets',
				'id'     => $ids,
			]
		);

		foreach ( $facets as $facet ) {
			Helpers::delete_index( $facet['slug'] );
		}

		do_action( 'wp_grid_builder/delete/facets', array_column( $facets, 'id' ) );

		return $this->ensure_response();

	}

	/**
	 * Cancel indexer
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return WP_REST_Response
	 */
	public function cancel() {

		( new Includes\Indexer() )->cancel();

		return $this->ensure_response();

	}

	/**
	 * Get index status
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_REST_Response
	 */
	public function status( $request ) {

		$queue  = array_column( ( new Queue() )->get(), 'key' );
		$facets = count( Helpers::get_indexable_facets( -1 ) );
		$facet  = min( 100, max( 0, (int) get_site_transient( 'wpgb_cron_progress' ) ) );
		$total  = $facets > 0 && count( $queue ) > 0 ? min( 100, max( 0, (int) ( ( $facet / $facets ) + ( 100 / $facets ) * ( $facets - count( $queue ) ) ) ) ) : 0;

		return $this->ensure_response(
			[
				'rows'  => (int) Database::count_items( [ 'from' => 'index' ] ),
				'queue' => $queue,
				'facet' => $facet,
				'total' => $total,
			]
		);
	}
}
