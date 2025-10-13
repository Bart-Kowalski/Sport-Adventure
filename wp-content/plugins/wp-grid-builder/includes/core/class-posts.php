<?php
/**
 * Posts
 *
 * @package   WP Grid Builder
 * @author    Loïc Blascos
 * @copyright 2019-2024 Loïc Blascos
 */

namespace WP_Grid_Builder\Includes\Core;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handle posts core actions
 *
 * @class WP_Grid_Builder\Includes\Core\Posts
 * @since 2.0.0
 */
final class Posts {

	/**
	 * Constructor
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function __construct() {

		add_action( 'add_attachment', [ $this, 'clear_cache' ] );
		add_action( 'delete_attachment', [ $this, 'clear_cache' ] );
		add_filter( 'wp_grid_builder/all_attachment_ids', [ $this, 'get_attachment' ] );

	}

	/**
	 * Clear attachment ids from cache
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param integer $post_id Attachment ID.
	 */
	public function clear_cache( $post_id ) {

		$post_type = get_post_type( $post_id );

		if ( 'attachment' === $post_type ) {
			wp_cache_delete( 'wpgb_all_attachment_ids' );
		}
	}

	/**
	 * Get attachment ids from cache
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return array
	 */
	public function get_attachment() {

		global $wpdb;

		$attachment_ids = wp_cache_get( 'wpgb_all_attachment_ids' );

		if ( ! is_array( $attachment_ids ) ) {

			$attachment_ids = $wpdb->get_col(
				"SELECT ID FROM {$wpdb->posts}
				WHERE post_type = 'attachment'"
			);

			wp_cache_add( 'wpgb_all_attachment_ids', $attachment_ids );

		}

		return $attachment_ids;

	}
}
