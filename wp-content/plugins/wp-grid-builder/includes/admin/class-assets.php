<?php
/**
 * Enqueue admin assets
 *
 * @package   WP Grid Builder
 * @author    Loïc Blascos
 * @copyright 2019-2024 Loïc Blascos
 */

namespace WP_Grid_Builder\Includes\Admin;

use WP_Grid_Builder\Includes\Helpers;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue admin assets
 *
 * @class WP_Grid_Builder\Includes\Admin\Assets
 * @since 1.0.0
 */
final class Assets {

	/**
	 * Handle prefix
	 *
	 * @since 2.0.0
	 * @var string
	 */
	private $handle_prefix = WPGB_SLUG . '-';

	/**
	 * Script url
	 *
	 * @since 2.0.0
	 * @var string
	 */
	private $script_url = WPGB_URL . 'admin/js/%s.js';

	/**
	 * Style url
	 *
	 * @since 2.0.0
	 * @var string
	 */
	private $style_url = WPGB_URL . 'admin/css/%s.css';

	/**
	 * Internal CSS handles and dependencies
	 *
	 * @since 2.0.0
	 * @var array
	 */
	private $css_assets = [
		'card'       => [],
		'components' => [],
		'editor'     => [ 'components', 'card' ],
		'gutenberg'  => [ 'components' ],
		'app'        => [ 'editor' ],
	];

	/**
	 * Internal JS handles and dependencies
	 *
	 * @since 2.0.0
	 * @var array
	 */
	private $js_assets = [
		'router'     => [ 'alias' ],
		'lodash'     => [],
		'styles'     => [],
		'icons'      => [],
		'codemirror' => [],
		'select'     => [],
		'components' => [ 'alias', 'lodash', 'styles', 'icons', 'select', 'codemirror' ],
		'blocks'     => [ 'components' ],
		'gutenberg'  => [ 'components' ],
		'post'       => [ 'components' ],
		'term'       => [ 'components' ],
		'editor'     => [ 'blocks' ],
		'app'        => [ 'router', 'editor' ],
	];

	/**
	 * Constructor
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function __construct() {

		add_action( 'admin_enqueue_scripts', [ $this, 'register_assets' ], 0 );

	}


	/**
	 * Register assets
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function register_assets() {

		if ( ! Helpers::current_user_can() ) {
			return;
		}

		$this->register_styles();
		$this->register_scripts();
		$this->enqueue_app_assets();
		$this->enqueue_post_assets();
		$this->enqueue_term_assets();

	}

	/**
	 * Register scripts
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function register_styles() {

		$suffix = is_rtl() ? '-rtl' : '';

		foreach ( $this->css_assets as $handle => $deps ) {

			wp_register_style(
				$this->handle_prefix . $handle,
				sprintf( $this->style_url, $handle . $suffix ),
				array_map(
					function( $dep ) {
						return $this->handle_prefix . $dep;
					},
					$deps
				),
				WPGB_VERSION
			);
		}
	}

	/**
	 * Register scripts
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function register_scripts() {

		// Register alias script for dependencies.
		wp_register_script( $this->handle_prefix . 'alias', false, [], WPGB_VERSION, true );

		foreach ( $this->js_assets as $handle => $deps ) {

			wp_register_script(
				$this->handle_prefix . $handle,
				sprintf( $this->script_url, $handle ),
				array_map(
					function( $dep ) {
						return $this->handle_prefix . $dep;
					},
					$deps
				),
				WPGB_VERSION,
				true
			);

			wp_set_script_translations(
				$this->handle_prefix . $handle,
				'wp-grid-builder',
				WPGB_PATH . '/languages'
			);
		}
	}

	/**
	 * Enqueue assets
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param string $js_handle  Handle of JS style.
	 * @param string $css_handle Handle of CSS style.
	 */
	public function enqueue_assets( $js_handle, $css_handle = 'components' ) {

		wp_enqueue_media();
		wp_enqueue_style( 'wp-edit-post' );
		wp_enqueue_editor_block_directory_assets();
		do_action( 'wp_grid_builder/admin/enqueue_script', $js_handle );
		wp_enqueue_script( $this->handle_prefix . $js_handle );
		wp_enqueue_style( $this->handle_prefix . $css_handle );

	}

	/**
	 * Enqueue App assets
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function enqueue_app_assets() {

		global $current_screen;

		if (
			empty( $current_screen->id ) ||
			'toplevel_page_wp-grid-builder' !== $current_screen->id
		) {
			return;
		}

		wp_enqueue_editor_format_library_assets();
		wp_enqueue_style( 'wp-format-library' );
		$this->enqueue_assets( 'app', 'app' );

	}

	/**
	 * Enqueue post edit page assets
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function enqueue_post_assets() {

		global $pagenow;

		if ( 'post-new.php' !== $pagenow && 'post.php' !== $pagenow ) {
			return;
		}

		if ( ! wpgb_get_option( 'post_meta' ) ) {
			return;
		}

		$this->enqueue_assets( 'post' );

	}

	/**
	 * Enqueue term add/edit page assets
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function enqueue_term_assets() {

		global $pagenow;

		if ( 'term.php' !== $pagenow ) {
			return;
		}

		if ( ! wpgb_get_option( 'term_meta' ) ) {
			return;
		}

		$this->enqueue_assets( 'term' );

	}
}
