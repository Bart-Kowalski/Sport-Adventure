<?php
/**
 * Metabox
 *
 * @package   WP Grid Builder
 * @author    Loïc Blascos
 * @copyright 2019-2024 Loïc Blascos
 */

namespace WP_Grid_Builder\Includes\Admin;

use WP_Grid_Builder\Includes\Helpers;
use WP_Grid_Builder\Includes\Settings\Settings;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handle metabox
 *
 * @class WP_Grid_Builder\Includes\Admin\MetaBox
 * @since 2.0.0
 */
final class MetaBox {

	/**
	 * Field nonce.
	 *
	 * @since 2.0.0
	 * @var string
	 */
	private $nonce = 'wpgb_fields_nonce';

	/**
	 * Save acton name.
	 *
	 * @since 2.0.0
	 * @var string
	 */
	private $action = 'wpgb_fields';

	/**
	 * Metadata key.
	 *
	 * @since 2.0.0
	 * @var string
	 */
	private $meta_key = '_wpgb';

	/**
	 * Constructor
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function __construct() {

		add_action( 'add_meta_boxes', [ $this, 'post_metabox' ] );
		add_action( 'current_screen', [ $this, 'term_metabox' ] );
		add_action( 'save_post', [ $this, 'save_post' ], 10, 3 );
		add_action( 'edit_attachment', [ $this, 'save_post' ], 10 );
		add_action( 'created_term', [ $this, 'save_term' ], 10, 3 );
		add_action( 'edited_term', [ $this, 'save_term' ], 10, 3 );
		add_filter( 'wp_grid_builder/admin/localize_script', [ $this, 'localize' ] );

	}

	/**
	 * Register post metabox
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function post_metabox() {

		if ( ! Helpers::current_user_can() ) {
			return;
		}

		if ( ! wpgb_get_option( 'post_meta' ) ) {
			return;
		}

		$post_types = get_post_types( [ 'public' => true ] );
		unset( $post_types['attachment'] );

		add_meta_box(
			WPGB_SLUG,
			WPGB_NAME,
			[ $this, 'render_metabox' ],
			$post_types,
			'normal',
			'default'
		);
	}

	/**
	 * Register term metabox
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function term_metabox() {

		global $pagenow;

		if ( 'term.php' !== $pagenow ) {
			return;
		}

		if ( ! Helpers::current_user_can() ) {
			return;
		}

		if ( ! wpgb_get_option( 'term_meta' ) ) {
			return;
		}

		add_action( get_current_screen()->taxonomy . '_edit_form', [ $this, 'render_metabox' ], 10, 2 );

	}

	/**
	 * Render metabox
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function render_metabox() {

		echo '<div id="wpgb-admin-app"></div>';

		wp_nonce_field( $this->action, $this->nonce, false );

	}

	/**
	 * Save post metadata
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param int     $post_id Post ID.
	 * @param WP_Post $post    Post object.
	 * @param bool    $update  Whether this is an existing post being updated or not.
	 */
	public function save_post( $post_id, $post = null, $update = false ) {

		// If post revision.
		if (
			is_int( wp_is_post_revision( $post_id ) ) ||
			is_null( $update )
		) {
			return;
		}

		// If post autosave.
		if (
			( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) ||
			is_int( wp_is_post_autosave( $post_id ) )
		) {
			return;
		}

		// Check user capability.
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		$this->save_metadata( 'post', $post_id );

	}

	/**
	 * Save term metadata
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param int    $term_id  Term ID.
	 * @param int    $tt_id    Term taxonomy ID.
	 * @param string $taxonomy Taxonomy slug.
	 */
	public function save_term( $term_id, $tt_id, $taxonomy ) {

		$taxonomy   = get_taxonomy( $taxonomy );
		$capability = $taxonomy->cap->manage_terms;

		// Check user capability.
		if ( ! current_user_can( $capability, $term_id ) ) {
			return;
		}

		$this->save_metadata( 'term', $term_id );

	}

	/**
	 * Save metabox
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param string  $object_type Metadata object type.
	 * @param integer $object_id   Object ID to update.
	 */
	public function save_metadata( $object_type, $object_id ) {

		$data = wp_unslash( $_POST );

		if ( empty( $data[ $this->meta_key ] ) ) {
			return;
		}

		if ( empty( $data[ $this->nonce ] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $data[ $this->nonce ], $this->action ) ) {
			return;
		}

		// Merge all old meatadata to retain all values (colors).
		$old_metadata = get_metadata( $object_type, $object_id, $this->meta_key, true );
		$new_metadata = json_decode( $data[ $this->meta_key ], true );
		$new_metadata = ( new Settings() )->sanitize( $object_type, $new_metadata );
		$new_metadata = apply_filters( 'wp_grid_builder/settings/save_fields', $new_metadata, $object_type, $object_id );
		$new_metadata = wp_parse_args( $new_metadata, $old_metadata );

		update_metadata( $object_type, $object_id, wp_slash( $this->meta_key ), wp_slash( $new_metadata ) );

	}

	/**
	 * Localize metadata and controls
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param array $data Holds data to localize.
	 * @return array
	 */
	public function localize( $data ) {

		global $post;

		if ( isset( $data['controls'] ) ) {
			return $data;
		}

		$settings = new Settings();

		// phpcs:disable WordPress.Security.NonceVerification.Recommended
		if ( ! empty( $_REQUEST['tag_ID'] ) ) {

			$data['metadata'] = (array) get_term_meta( (int) $_REQUEST['tag_ID'], '_' . WPGB_SLUG, true );
			$data['controls'] = [
				'controls' => $settings->generate( 'term' ),
				'defaults' => $settings->defaults( 'term' ),
			];
		} elseif ( ! empty( $post->ID ) ) {

			$data['metadata'] = (array) get_post_meta( $post->ID, '_' . WPGB_SLUG, true );
			$data['controls'] = [
				'controls' => $settings->generate( 'post' ),
				'defaults' => $settings->defaults( 'post' ),
			];
		}
		// phpcs:enable WordPress.Security.NonceVerification.Recommended

		return $data;

	}
}
