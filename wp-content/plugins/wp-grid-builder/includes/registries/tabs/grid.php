<?php
/**
 * Grid tabs
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
	[
		'name'  => 'query',
		'title' => __( 'Query', 'wp-grid-builder' ),
	],
	[
		'name'  => 'layout',
		'title' => __( 'Layout', 'wp-grid-builder' ),
	],
	[
		'name'  => 'carousel',
		'title' => __( 'Carousel', 'wp-grid-builder' ),
	],
	[
		'name'  => 'cards',
		'title' => __( 'Cards', 'wp-grid-builder' ),
	],
	[
		'name'  => 'media',
		'title' => __( 'Media', 'wp-grid-builder' ),
	],
	[
		'name'  => 'advanced',
		'title' => __( 'Advanced', 'wp-grid-builder' ),
	],
];
