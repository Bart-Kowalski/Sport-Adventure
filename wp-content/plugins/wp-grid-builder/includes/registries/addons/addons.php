<?php
/**
 * Addons
 *
 * @package   WP Grid Builder
 * @author    Loïc Blascos
 * @copyright 2019-2024 Loïc Blascos
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

return [
	'bricks'         => [
		'name'    => 'Bricks',
		'slug'    => 'wp-grid-builder-bricks/wp-grid-builder-bricks.php',
		'content' => __( 'Easily integrate WP Grid Builder with Bricks plugin.', 'wp-grid-builder' ),
		'icon'    => WPGB_URL . 'admin/svg/bricks.svg',
	],
	'pods'           => [
		'name'    => 'Pods',
		'slug'    => 'wp-grid-builder-pods/wp-grid-builder-pods.php',
		'content' => __( 'Easily integrate WP Grid Builder with Pods plugin.', 'wp-grid-builder' ),
		'icon'    => WPGB_URL . 'admin/svg/pods.svg',
	],
	'beaver-builder' => [
		'name'    => 'Beaver Builder',
		'slug'    => 'wp-grid-builder-beaver-builder/wp-grid-builder-beaver-builder.php',
		'content' => __( 'Easily integrate WP Grid Builder with Beaver Builder plugin.', 'wp-grid-builder' ),
		'icon'    => WPGB_URL . 'admin/svg/beaver-builder.svg',
	],
	'meta-box'       => [
		'name'    => 'Meta Box',
		'slug'    => 'wp-grid-builder-meta-box/wp-grid-builder-meta-box.php',
		'content' => __( 'Easily integrate WP Grid Builder with Meta Box plugin.', 'wp-grid-builder' ),
		'icon'    => WPGB_URL . 'admin/svg/meta-box.svg',
	],
	'oxygen'         => [
		'name'    => 'Oxygen',
		'slug'    => 'wp-grid-builder-oxygen/wp-grid-builder-oxygen.php',
		'content' => __( 'Integrate WP Grid Builder with Oxygen plugin.', 'wp-grid-builder' ),
		'icon'    => WPGB_URL . 'admin/svg/oxygen.svg',
	],
	'elementor'      => [
		'name'    => 'Elementor',
		'slug'    => 'wp-grid-builder-elementor/wp-grid-builder-elementor.php',
		'content' => __( 'Integrate WP Grid Builder with Elementor plugin.', 'wp-grid-builder' ),
		'icon'    => WPGB_URL . 'admin/svg/elementor.svg',
	],
	'multilingual'   => [
		'name'    => 'Multilingual',
		'slug'    => 'wp-grid-builder-multilingual/wp-grid-builder-multilingual.php',
		'content' => __( 'Easily integrate WP Grid Builder with Polylang and WPML plugins.', 'wp-grid-builder' ),
		'icon'    => WPGB_URL . 'admin/svg/multilingual.svg',
	],
	'caching'        => [
		'name'    => 'Caching',
		'slug'    => 'wp-grid-builder-caching/wp-grid-builder-caching.php',
		'content' => __( 'Speed up loading time when filtering grids by caching content and facets.', 'wp-grid-builder' ),
		'icon'    => WPGB_URL . 'admin/svg/caching.svg',
	],
	'map-facet'      => [
		'name'    => 'Map Facet',
		'slug'    => 'wp-grid-builder-map-facet/wp-grid-builder-map-facet.php',
		'content' => __( 'Add maps from Google Maps, Mapbox or Leaflet to display markers and to filter.', 'wp-grid-builder' ),
		'icon'    => WPGB_URL . 'admin/svg/map-facet.svg',
	],
	'learndash'      => [
		'name'    => 'LearnDash',
		'slug'    => 'wp-grid-builder-learndash/wp-grid-builder-learndash.php',
		'content' => __( 'Add new blocks to the card builder to display courses information.', 'wp-grid-builder' ),
		'icon'    => WPGB_URL . 'admin/svg/learndash.svg',
	],
];
