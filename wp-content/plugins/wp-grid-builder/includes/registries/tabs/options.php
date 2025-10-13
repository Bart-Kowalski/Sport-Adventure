<?php
/**
 * Options tabs
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
		'name'  => 'general',
		'title' => __( 'General', 'wp-grid-builder' ),
	],
	[
		'name'  => 'styles',
		'title' => __( 'Styles', 'wp-grid-builder' ),
	],
	[
		'name'  => 'lightbox',
		'title' => __( 'Lightbox', 'wp-grid-builder' ),
	],
	[
		'name'  => 'advanced',
		'title' => __( 'Advanced', 'wp-grid-builder' ),
	],
];
