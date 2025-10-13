<?php
/**
 * Helper functions
 *
 * @package   WP Grid Builder
 * @author    Loïc Blascos
 * @copyright 2019-2024 Loïc Blascos
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use WP_Grid_Builder\Includes\Settings\Settings;
use WP_Grid_Builder\Includes\Container;

/**
 * Get plugin options
 *
 * @since 1.4.0
 *
 * @return array
 */
function wpgb_get_options() {

	static $options;

	if ( ! empty( $options ) && is_array( $options ) ) {
		return $options;
	}

	$defaults = apply_filters( 'wp_grid_builder/defaults/options', [] );
	$options  = get_option( WPGB_SLUG . '_global_settings' );
	$options  = wp_parse_args( $options, $defaults );

	return $options;

}

/**
 * Get plugin option
 *
 * @since 1.4.0
 *
 * @param string $option  Name of option to retrieve.
 * @param mixed  $default Default value to return if the option does not exist.
 * @return mixed
 */
function wpgb_get_option( $option = '', $default = false ) {

	if ( empty( $option ) ) {
		return false;
	}

	$options = wpgb_get_options();

	if ( ! isset( $options[ $option ] ) ) {
		return $default;
	}

	return $options[ $option ];

}

/**
 * Retrieve grid settings
 *
 * @since 1.0.0
 *
 * @param string $key Settings key.
 * @return mixed
 */
function wpgb_get_grid_settings( $key = '' ) {

	if ( ! Container::has( 'Container/Grid' ) ) {
		return false;
	}

	$settings = Container::instance( 'Container/Grid' )->get( 'Settings' );

	if ( ! empty( $key ) ) {

		if ( isset( $settings->$key ) ) {
			return $settings->$key;
		}

		return false;

	}

	return $settings->properties;

}

/**
 * Check is we are in overview mode
 *
 * @since 1.0.0
 *
 * @return mixed
 */
function wpgb_is_overview() {

	$settings = wpgb_get_grid_settings();

	if ( isset( $settings->is_overview ) ) {
		return $settings->is_overview;
	}

	return false;

}

/**
 * Check is we are in preview mode
 *
 * @since 1.0.0
 *
 * @return mixed
 */
function wpgb_is_preview() {

	$settings = wpgb_get_grid_settings();

	if ( isset( $settings->is_preview ) ) {
		return $settings->is_preview;
	}

	return false;

}

/**
 * Check if we are in Gutenberg edit page
 *
 * @since 1.0.0
 *
 * @return mixed
 */
function wpgb_is_gutenberg() {

	if ( ! function_exists( 'get_current_screen' ) ) {
		return false;
	}

	$screen = get_current_screen();

	if ( is_null( $screen ) || ! method_exists( $screen, 'is_block_editor' ) ) {
		return false;
	}

	return $screen->is_block_editor();

}

/**
 * Determines whether the current request is a plugin Rest request.
 *
 * @since 2.0.4
 *
 * @return boolean
 */
function wpgb_is_rest_api() {

	static $wpgb_is_rest_api;

	if ( $wpgb_is_rest_api ) {
		return $wpgb_is_rest_api;
	}

	// phpcs:disable WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
	if ( empty( $_SERVER['REQUEST_URI'] ) ) {
		return false;
	}

	$rest_url    = 'wpgb/v2/filter';
	$request_uri = urldecode( wp_unslash( $_SERVER['REQUEST_URI'] ) );
	$is_rest_api = false !== strpos( $request_uri, $rest_url );

	if ( ! $is_rest_api || empty( $_GET['action'] ) ) {
		return false;
	}

	$wpgb_is_rest_api  = true;
	$_GET['wpgb-ajax'] = wp_unslash( $_GET['action'] );

	return true;
	// phpcs:enable WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

}

/**
 * Determines whether the current request is a plugin Ajax request.
 *
 * @since 1.4.2
 *
 * @return boolean
 */
function wpgb_doing_ajax() {

	if ( wpgb_is_rest_api() ) {
		return true;
	}

	// phpcs:ignore WordPress.Security.NonceVerification.Recommended
	return wp_doing_ajax() && ! empty( $_GET['wpgb-ajax'] );

}

/**
 * Determines whether the current request is rendering facets.
 *
 * @since 1.4.2
 *
 * @return boolean
 */
function wpgb_is_rendering_facets() {

	// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated, WordPress.Security.NonceVerification.Recommended
	return wpgb_doing_ajax() && 'render' === $_GET['wpgb-ajax'];

}

/**
 * Determines whether the current request is refreshing facets.
 *
 * @since 1.4.2
 *
 * @return boolean
 */
function wpgb_is_refreshing_facets() {

	// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated, WordPress.Security.NonceVerification.Recommended
	return wpgb_doing_ajax() && 'refresh' === $_GET['wpgb-ajax'];

}

/**
 * Determines whether the current request is searching for facet choices.
 *
 * @since 1.4.2
 *
 * @return boolean
 */
function wpgb_is_searching_facet_choices() {

	// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated, WordPress.Security.NonceVerification.Recommended
	return wpgb_doing_ajax() && 'search' === $_GET['wpgb-ajax'];

}

/**
 * Determines if we are filtering a shadow grid.
 *
 * @since 1.4.2
 *
 * @return boolean
 */
function wpgb_is_shadow_grid() {

	// phpcs:ignore WordPress.Security.NonceVerification.Recommended
	if ( ! wpgb_doing_ajax() || ! isset( $_REQUEST[ WPGB_SLUG ] ) ) {
		return false;
	}

	// phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
	$request = wp_unslash( $_REQUEST[ WPGB_SLUG ] );
	$request = json_decode( $request, true );

	return wpgb_doing_ajax() && ! empty( $request['is_shadow'] );

}

/**
 * Retrieve/Display SVG icon
 *
 * @since 1.0.0
 *
 * @param string  $icon    Holds icon name.
 * @param boolean $echo    Echo ro return icon.
 * @param boolean $svg_use Display icon as svg use.
 * @return string
 */
function wpgb_svg_icon( $icon = '', $echo = true, $svg_use = true ) {

	global $is_IE, $is_edge;

	if ( empty( $icon ) ) {
		return '';
	}

	$icons = apply_filters( 'wp_grid_builder/icons', [] );

	if ( empty( $icons[ $icon ] ) ) {
		return '';
	}

	if ( ! empty( $icons[ $icon ]['url'] ) && apply_filters( 'wp_grid_builder/svg_use', $svg_use && ! $is_IE && ! $is_edge ) ) {

		$output = '<svg><use xlink:href="' . esc_url( $icons[ $icon ]['url'] ) . '"/></svg>';

		if ( ! $echo ) {
			return $output;
		}

		echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		return '';

	}

	if ( ! empty( $icons[ $icon ]['icon'] ) ) {
		$icon = trim( $icons[ $icon ]['icon'] );
	} elseif ( ! empty( $icons[ $icon ]['path'] ) ) {

		$path = wp_normalize_path( $icons[ $icon ]['path'] );

		if ( ! file_exists( $path ) ) {
			return '';
		}

		ob_start();
		include $path;
		$icon = preg_replace( '#\s(id)="[^"]+"#', '', ob_get_clean() );

	} else {
		return '';
	}

	if ( $echo ) {
		echo $icon; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	} else {
		return $icon;
	}
}
