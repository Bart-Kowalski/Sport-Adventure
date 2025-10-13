<?php
/**
 * Localize admin assets
 *
 * @package   WP Grid Builder
 * @author    Loïc Blascos
 * @copyright 2019-2024 Loïc Blascos
 */

namespace WP_Grid_Builder\Includes\Admin;

use WP_Grid_Builder\Includes\I18n;
use WP_Grid_Builder\Includes\Helpers;
use WP_Grid_Builder\Includes\Settings\Settings;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Localize admin assets
 *
 * @class WP_Grid_Builder\Includes\Admin\Localize
 * @since 2.0.0
 */
final class Localize {


	/**
	 * Constructor
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function __construct() {

		add_action( 'wp_grid_builder/admin/enqueue_script', [ $this, 'localize_script' ] );

	}

	/**
	 * Loacalize app
	 *
	 * @since 2.0.0
	 *
	 * @param string $handle Current app handle (app, post, term, gutenberg).
	 */
	public function localize_script( $handle ) {

		global $wp_version;

		$l10n = [
			'lang'           => I18n::current_lang(),
			'version'        => WPGB_VERSION,
			'WPVersion'      => $wp_version,
			'editorSettings' => $this->localize_editor_settings(),
		];

		if ( 'app' === $handle ) {

			$l10n = array_merge(
				$l10n,
				[
					'facets'          => $this->localize_facets(),
					'addons'          => $this->localize_addons(),
					'preview'         => $this->localize_preview(),
					'plugins'         => array_values( get_option( 'active_plugins', [] ) ),
					'options'         => wpgb_get_options(),
					'license'         => $this->localize_license(),
					'objects'         => $this->localize_objects(),
					'controls'        => $this->localize_controls(),
					'conditions'      => apply_filters( 'wp_grid_builder/conditions', [] ),
					'dynamicTags'     => apply_filters( 'wp_grid_builder/dynamic_tags', [] ),
					'blockCategories' => apply_filters( 'wp_grid_builder/block_categories', [] ),
				]
			);
		}

		wp_localize_script(
			WPGB_SLUG . '-alias',
			'WP_Grid_Builder',
			apply_filters( 'wp_grid_builder/admin/localize_script', $l10n )
		);
	}

	/**
	 * Loacalize editor settings
	 *
	 * @since 2.0.0
	 */
	public function localize_editor_settings() {

		$upload_files = [ 'mediaUpload' => current_user_can( 'upload_files' ) ];

		if ( ! function_exists( 'get_block_editor_settings' ) ) {
			return $upload_files;
		}

		$editor_settings = new \WP_Block_Editor_Context( [ 'name' => 'wp-grid-builder' ] );

		return get_block_editor_settings( $upload_files, $editor_settings );

	}

	/**
	 * Loacalize registered facets
	 *
	 * @since 2.0.0
	 */
	public function localize_facets() {

		return array_map(
			function( $facet ) {
				return array_intersect_key( $facet, array_flip( [ 'name', 'type' ] ) );
			},
			apply_filters( 'wp_grid_builder/facets', [] )
		);
	}

	/**
	 * Loacalize plugin add-ons
	 *
	 * @since 2.0.0
	 */
	public function localize_addons() {

		return wp_parse_args(
			apply_filters( 'wp_grid_builder/addons', [] ),
			current(
				array_intersect_key(
					get_option( WPGB_SLUG . '_plugin_info', [ 'addons' => [] ] ),
					array_flip( [ 'addons' ] )
				)
			)
		);
	}

	/**
	 * Loacalize preview
	 *
	 * @since 2.0.0
	 */
	public function localize_preview() {

		$action = add_query_arg( WPGB_SLUG . '-preview', true, admin_url( 'admin-post.php' ) );
		$action = apply_filters( 'wp_grid_builder/admin/preview_action', $action );

		return [
			'nonce'  => Helpers::current_user_can() ? wp_create_nonce( WPGB_SLUG . '_preview_iframe' ) : '',
			'action' => esc_url_raw( $action ),
		];
	}

	/**
	 * Loacalize app objects
	 *
	 * @since 2.0.0
	 */
	public function localize_objects() {

		global $wpdb;

		return current(
			$wpdb->get_results(
				"SELECT
					( SELECT COUNT(*) FROM {$wpdb->prefix}wpgb_grids ) as grids,
					( SELECT COUNT(*) FROM {$wpdb->prefix}wpgb_cards ) as cards,
					( SELECT COUNT(*) FROM {$wpdb->prefix}wpgb_facets ) as facets,
					( SELECT COUNT(*) FROM {$wpdb->prefix}wpgb_styles ) as styles"
			)
		);
	}

	/**
	 * Loacalize plugin license data
	 *
	 * @since 2.0.0
	 */
	public function localize_license() {

		return array_intersect_key(
			get_option( WPGB_SLUG . '_plugin_info', [] ),
			array_flip( [ 'license_limit', 'license_type', 'site_count', 'is_local', 'expires' ] )
		);
	}

	/**
	 * Loacalize setting fields
	 *
	 * @since 2.0.0
	 */
	public function localize_controls() {

		$settings = new Settings();
		$controls = array_fill_keys( [ 'post', 'card', 'grid', 'facet', 'style', 'options' ], [] );

		foreach ( $controls as $type => $args ) {

			$controls[ $type ] = [
				'controls' => $settings->generate( $type ),
				'defaults' => $settings->defaults( $type ),
			];
		}

		return $controls;

	}
}
