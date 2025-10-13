<?php
/**
 * Facet functions
 *
 * @package   WP Grid Builder
 * @author    Loïc Blascos
 * @copyright 2019-2024 Loïc Blascos
 */

use WP_Grid_Builder\Includes\File;
use WP_Grid_Builder\Includes\Database;
use WP_Grid_Builder\Includes\Container;
use WP_Grid_Builder\Includes\FrontEnd\Colors;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Set facet style
 *
 * @since 2.0.0
 *
 * @param array $args Holds facet arguments.
 * @return array
 */
function wpgb_set_facet_style( $args = [] ) {

	// Do not apply global facet style in facet preview.
	if ( isset( $args['grid'] ) && 'wpgb_facet_preview' === $args['grid'] && empty( $args['is_gutenberg'] ) ) {
		return $args;
	}

	if ( empty( $args['style'] ) ) {
		$args['style'] = wpgb_get_option( 'facet_styles' );
	}

	if ( empty( $args['style'] ) ) {
		return $args;
	}

	$container = Container::instance( 'Container/Styles' );

	if ( ! is_array( $container->prop( 'ids' ) ) ) {
		$container->add( 'ids', [] );
	}

	$id  = (int) $args['style'];
	$ids = $container->prop( 'ids' );

	if ( in_array( $id, $ids, true ) ) {
		return $args;
	}

	$container->add( 'ids', array_merge( $ids, (array) $id ) );

	return $args;

}
add_filter( 'wp_grid_builder/facet/render_args', 'wpgb_set_facet_style', PHP_INT_MAX - 10 );

/**
 * Check if there are available styles
 *
 * @since 2.0.0
 *
 * @return boolean
 */
function wpgb_has_facet_style() {

	return count( wpgb_get_facet_style_ids() ) > 0;

}

/**
 * Get facet style IDs
 *
 * @since 2.0.0
 *
 * @return array
 */
function wpgb_get_facet_style_ids() {

	if ( ! Container::has( 'Container/Styles' ) ) {
		return [];
	}

	$container = Container::instance( 'Container/Styles' );

	if ( ! is_array( $container->prop( 'ids' ) ) ) {
		return [];
	}

	$styles = $container->prop( 'styles' );

	if ( is_array( $styles ) ) {
		return array_column( $styles, 'id' );
	}

	$ids = array_filter( array_unique( $container->prop( 'ids' ) ) );

	if ( empty( $ids ) ) {

		$container->add( 'styles', [] );
		return $ids;

	}

	$styles = Database::query_results(
		[
			'select'  => 'id, css, settings',
			'from'    => 'styles',
			'orderby' => 'id',
			'id'      => $ids,
		]
	);

	$styles = array_map(
		function( $style ) {

			$style['settings'] = json_decode( $style['settings'], true );
			return $style;

		},
		$styles
	);

	$container->add( 'styles', $styles );

	return array_column( $styles, 'id' );

}

/**
 * Get facet fonts
 *
 * @since 2.0.0
 *
 * @return array
 */
function wpgb_get_facet_fonts() {

	if ( ! wpgb_has_facet_style() ) {
		return [];
	}

	$fonts    = [];
	$container = Container::instance( 'Container/Styles' );

	foreach ( $container->prop( 'styles' ) as $style ) {

		if ( empty( $style['settings']['fonts']['google'] ) ) {
			continue;
		}

		$fonts[] = $style['settings']['fonts']['google'];

	}

	return $fonts;

}

/**
 * Get facet style CSS
 *
 * @since 2.0.0
 *
 * @param boolean $css_variables whether or not to generate CSS variables.
 * @return string
 */
function wpgb_get_facet_style_css( $css_variables = true ) {

	$styles  = '';

	if ( $css_variables ) {
		$styles .= ( new Colors() )->generate_schemes();
	}

	if ( ! wpgb_has_facet_style() ) {
		return wp_strip_all_tags( $styles );
	}

	$container = Container::instance( 'Container/Styles' );

	foreach ( $container->prop( 'styles' ) as $style ) {

		$content = File::get_contents( 'styles', $style['id'] . '.css' );

		if ( empty( $content ) ) {
			$content = str_replace( '.wpgb-style-preview', '.wpgb-style-' . $style['id'], $style['css'] );
		}

		$styles .= $content;

	}

	return wp_strip_all_tags( $styles );

}

/**
 * Generate facet stylesheet
 *
 * @since 2.0.0 Add facet styles
 * @since 1.4.0
 *
 * @return boolean
 */
function wpgb_generate_facet_style_stylesheet() {

	$stylesheet = 'styles.css';

	if ( wpgb_has_facet_style() ) {
		$stylesheet = 'S' . implode( 'S', wpgb_get_facet_style_ids() ) . '.css';
	}

	if ( File::get_url( 'facets', $stylesheet ) ) {
		return true;
	}

	$css = wpgb_get_facet_style_css();

	if ( empty( $css ) ) {
		return false;
	}

	return File::put_contents( 'facets', $stylesheet, $css );

}

/**
 * Generate and get facet stylesheet
 *
 * @since 2.0.0 Add facet styles
 * @since 1.4.0
 *
 * @return array
 */
function wpgb_get_facet_style_stylesheet() {

	if ( ! wpgb_generate_facet_style_stylesheet() ) {
		return [];
	}

	$style_ids = wpgb_get_facet_style_ids();

	if ( empty( $style_ids ) ) {
		$stylesheet = 'styles.css';
	} else {
		$stylesheet = 'S' . implode( 'S', $style_ids ) . '.css';
	}

	return [
		'handle'  => WPGB_SLUG . '-styles',
		'source'  => esc_url_raw( File::get_url( 'facets', $stylesheet ) ),
		'version' => filemtime( File::get_path( 'facets', $stylesheet ) ),
	];
}

/**
 * Get facet fonts URL.
 *
 * @since 2.0.0
 *
 * @return array
 */
function wpgb_get_facet_fonts_url() {

	$google = wpgb_get_facet_fonts();

	if ( empty( $google ) ) {
		return [];
	}

	// Merge all font families.
	$families = call_user_func_array( 'array_merge_recursive', $google );
	$families = array_map( 'array_unique', $families );

	if ( empty( $families ) ) {
		return [];
	}

	array_walk(
		$families,
		function( $val, $key ) use ( &$query ) {
			$query[] = $key . ':' . implode( ',', $val );
		}
	);

	$query = implode( '|', $query );

	return add_query_arg(
		[ 'family' => rawurlencode( $query ) ],
		! empty( wpgb_get_option( 'bunny_fonts' ) ) ? 'https://fonts.bunny.net/css' : 'https://fonts.googleapis.com/css'
	);
}

/**
 * Get facet fonts stylesheet.
 *
 * @since 2.0.0
 *
 * @return array
 */
function wpgb_get_facet_fonts_stylesheet() {

	$url = wpgb_get_facet_fonts_url();

	if ( empty( $url ) ) {
		return [];
	}

	return [
		'handle'  => WPGB_SLUG . '-fonts',
		'source'  => esc_url_raw( $url ),
		'version' => null,
	];
}

/**
 * Print facet fonts
 *
 * @since 2.0.0
 */
function wpgb_print_facet_fonts() {

	$url = wpgb_get_facet_fonts_url();

	if ( empty( $url ) ) {
		return;
	}

	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped, WordPress.WP.EnqueuedResources.NonEnqueuedStylesheet
	printf( '<link rel="stylesheet" href="%s" media="all">', esc_url_raw( $url ) );

}

/**
 * Print facet style
 *
 * @since 2.0.0
 */
function wpgb_print_facet_style() {

	$css = wpgb_get_facet_style_css();

	if ( empty( $css ) ) {
		return;
	}

	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	printf( '<style>%s</style>', wpgb_get_facet_style_css() );

}

/**
 * Register facet stylsheet in block editor
 *
 * @since 2.0.0 Add facet styles
 * @since 1.4.0
 *
 * @param array $styles Holds registered stylsheets.
 * @return array
 */
function wpgb_editor_register_facet_style( $styles ) {

	if ( wpgb_get_option( 'render_blocks' ) && wpgb_is_gutenberg() ) {
		$styles[] = wpgb_get_facet_style_stylesheet();
	}

	return $styles;

}
add_filter( 'wp_grid_builder/frontend/register_styles', 'wpgb_editor_register_facet_style' );

/**
 * Register facet stylsheet on frontend
 *
 * @since 2.0.0 Add facet style and fonts.
 * @since 1.4.0
 *
 * @param array $styles Holds registered stylsheets.
 * @return array
 */
function wpgb_register_facet_style( $styles ) {

	$handles = array_column( $styles, 'handle' );

	if ( ! in_array( WPGB_SLUG . '-style', $handles, true ) ) {

		$styles[] = wpgb_get_facet_style_stylesheet();
		$styles[] = wpgb_get_facet_fonts_stylesheet();

	}

	return $styles;

}
add_filter( 'wp_grid_builder/frontend/register_styles', 'wpgb_register_facet_style' );

/**
 * Inline facet CSS if stylesheet missing (fallback)
 *
 * @since 2.0.0
 *
 * @param string $styles Holds inline styles.
 * @param array  $handles Holds enqueued stylesheet handles.
 * @return string
 */
function wpgb_inline_facet_style( $styles, $handles ) {

	if ( ! isset( $handles[ WPGB_SLUG . '-style' ] ) && ! isset( $handles[ WPGB_SLUG . '-styles' ] ) ) {
		$styles .= wpgb_get_facet_style_css();
	}

	return $styles;

}
add_filter( 'wp_grid_builder/frontend/add_inline_style', 'wpgb_inline_facet_style', 10, 2 );

/**
 * Dequeue facets stylesheet if main stylesheet is already registered
 *
 * @since 1.4.0
 *
 * @param array $handles Holds handles to enqueue.
 * @return array
 */
function wpgb_dequeue_facet_style( $handles ) {

	if ( isset( $handles[ WPGB_SLUG . '-facets' ], $handles[ WPGB_SLUG . '-style' ] ) ) {
		unset( $handles[ WPGB_SLUG . '-facets' ] );
	}

	return $handles;

}
add_filter( 'wp_grid_builder/frontend/enqueue_styles', 'wpgb_dequeue_facet_style' );
