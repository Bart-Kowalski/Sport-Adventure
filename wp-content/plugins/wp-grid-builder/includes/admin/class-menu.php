<?php
/**
 * Menu
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
 * Handle admin Menu
 *
 * @class WP_Grid_Builder\Includes\Admin\Menu
 * @since 1.0.0
 */
class Menu {

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {

		add_action( 'admin_menu', [ $this, 'main_menu' ] );
		add_action( 'admin_menu', [ $this, 'sub_menus' ] );
		add_filter( 'submenu_file', [ $this, 'highlight_submenu' ], 10, 2 );
		add_action( 'admin_head', [ $this, 'remove_notices' ] );
		add_filter( 'plugin_action_links_' . WPGB_BASE, [ $this, 'plugin_action_links' ], 10, 4 );

	}

	/**
	 * Add admin menu and submenu items in Dashboard.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function main_menu() {

		add_menu_page(
			'wp-grid-builder',
			WPGB_NAME,
			Helpers::get_user_capability(),
			'wp-grid-builder',
			null,
			$this->menu_icon()
		);
	}

	/**
	 * Add admin menu and submenu items in Dashboard.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function sub_menus() {

		$capability = Helpers::get_user_capability();
		$submenus   = [
			'dashboard' => esc_html__( 'Dashboard', 'wp-grid-builder' ),
			'grids'     => esc_html__( 'All Grids', 'wp-grid-builder' ),
			'cards'     => esc_html__( 'All Cards', 'wp-grid-builder' ),
			'facets'    => esc_html__( 'All Facets', 'wp-grid-builder' ),
			'styles'    => esc_html__( 'All Styles', 'wp-grid-builder' ),
		];

		foreach ( $submenus as $slug => $name ) {

			$menu_slug = 'wp-grid-builder';

			if ( 'dashboard' !== $slug ) {
				$menu_slug .= '&menu=' . $slug;
			}

			add_submenu_page(
				'wp-grid-builder',
				WPGB_NAME,
				$name,
				$capability,
				$menu_slug,
				[ $this, 'load_page' ]
			);
		}
	}

	/**
	 * Highlights parent of current submenu.
	 *
	 * @since 1.2.2
	 * @access public
	 *
	 * @param string $submenu_file The submenu file.
	 * @param string $parent_file  The submenu item's parent file.
	 * @return string
	 */
	public function highlight_submenu( $submenu_file, $parent_file ) {

		global $menu;

		if ( 'wp-grid-builder' !== $parent_file ) {
			return $submenu_file;
		}

		// phpcs:disable WordPress.Security.NonceVerification.Recommended
		if ( ! empty( $_GET['menu'] ) ) {
			$submenu_file = 'wp-grid-builder&menu=' . sanitize_key( wp_unslash( $_GET['menu'] ) );
		}
		// phpcs:enable WordPress.Security.NonceVerification.Recommended

		return $submenu_file;

	}

	/**
	 * Add menu icon as data uri to prevent additional HTTP requests (CSS+Font) in admin dashboard
	 * Base64-encoded SVG using a data URI, still allow to preserve the color scheme
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function menu_icon() {

		$icon = sprintf(
			'<svg xmlns="http://www.w3.org/2000/svg" x="0" y="0" viewBox="0 0 1000 1000">
				<path fill="currentColor" d="M838.071 0H161.928C72.496 0 0 72.495 0 161.928v676.143C0 927.504 72.496 1000 161.928 1000H838.07c89.433 0 161.929-72.496 161.929-161.929V161.928C999.999 72.495 927.503 0 838.071 0zM721.246 801.412c0 11.589-6.238 17.827-17.832 17.833H297.056c-11.588 0-17.833-6.244-17.833-17.833V696.188c0-11.594 6.246-17.833 17.833-17.833h406.358c11.588 0 17.832 6.239 17.832 17.833v105.224zM500.283 621.389c-126.023 0-226.131-92.06-226.131-213.619 0-121.554 99.824-213.347 225.848-213.347v127.544c-50.051 0-83.732 39.325-83.732 85.802s33.959 85.809 84.016 85.809c49.155 0 83.12-39.332 83.12-85.809l-.006-1.032h142.116l.006 1.032c-.001 121.561-100.11 213.62-225.237 213.62zm225.61-270.771H554.764V179.49h157.493c7.526.003 13.629 6.102 13.636 13.63v157.498z"/>
			</svg>'
		);

		// phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode
		return 'data:image/svg+xml;base64,' . base64_encode( $icon );

	}

	/**
	 * Remove all admin notices in plugin pages
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function remove_notices() {

		global $current_screen;

		if (
			empty( $current_screen->id ) ||
			'toplevel_page_wp-grid-builder' !== $current_screen->id
		) {
			return;
		}

		remove_all_actions( 'admin_notices' );
		remove_all_actions( 'all_admin_notices' );

	}


	/**
	 * Output page content
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function load_page() {

		do_action( 'wp_grid_builder/admin/load_page' );
		printf(
			'<div id="wpgb-admin-app">
				<div id="wpgb-admin-header"></div>
			</div>'
		);
	}

	/**
	 * Add edit/doc links on plugin list page
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param  array $links Array action links.
	 * @return array Array action links
	 */
	public function plugin_action_links( $links ) {

		if ( ! Helpers::current_user_can() ) {
			return $links;
		}

		$settings_url = add_query_arg(
			[
				'page' => 'wp-grid-builder',
			],
			admin_url( 'admin.php' )
		);

		// Add custom action links.
		return array_merge(
			[
				'<a href="' . esc_url( $settings_url ) . '" aria-label="' . esc_attr__( 'Go to plugin settings', 'wp-grid-builder' ) . '">' . esc_html__( 'Settings', 'wp-grid-builder' ) . '</a>',
				'<a href="https://docs.wpgridbuilder.com" target="_blank" rel="external noopener noreferrer" aria-label="' . esc_attr__( 'Go to plugin documentation', 'wp-grid-builder' ) . '">' . esc_html__( 'Docs', 'wp-grid-builder' ) . '</a>',
				'<a href="https://wpgridbuilder.com/account/support/" target="_blank" rel="external noopener noreferrer" aria-label="' . esc_attr__( 'Open a ticket', 'wp-grid-builder' ) . '">' . esc_html__( 'Support', 'wp-grid-builder' ) . '</a>',
			],
			$links
		);
	}
}
