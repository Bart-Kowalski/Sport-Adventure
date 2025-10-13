<?php
/**
 * Facets
 *
 * @package   WP Grid Builder
 * @author    Loïc Blascos
 * @copyright 2019-2024 Loïc Blascos
 */

namespace WP_Grid_Builder\Includes\Core;

use WP_Grid_Builder\Includes\Indexer;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handle facet core actions
 *
 * @class WP_Grid_Builder\Includes\Core\Facets
 * @since 2.0.0
 */
final class Facets {

	/**
	 * Constructor
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function __construct() {

		add_action( 'wp_grid_builder/delete/facets', [ $this, 'cancel_queue' ] );
		add_action( 'wp_grid_builder/delete/facets', [ $this, 'clear_cache' ] );

	}

	/**
	 * Cancel index queue item
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param array $object_ids Holds object ids to process.
	 */
	public function cancel_queue( $object_ids ) {

		$indexer = new Indexer();

		foreach ( (array) $object_ids as $object_id ) {
			$indexer->queue->cancel_item( $object_id );
		}
	}

	/**
	 * Clear cache
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param integer $object_ids Holds object ids to process.
	 */
	public function clear_cache( $object_ids ) {

		global $wpdb;

		foreach ( (array) $object_ids as $object_id ) {

			$wpdb->query(
				$wpdb->prepare(
					"DELETE FROM {$wpdb->options}
					WHERE (
						option_name LIKE %s
						OR option_name LIKE %s
					)
					AND option_name LIKE %s",
					$wpdb->esc_like( '_transient_wpgb_' ) . '%',
					$wpdb->esc_like( '_site_transient_wpgb_' ) . '%',
					'%' . $wpdb->esc_like( 'F' ) . $object_id . '%'
				)
			);
		}
	}
}
