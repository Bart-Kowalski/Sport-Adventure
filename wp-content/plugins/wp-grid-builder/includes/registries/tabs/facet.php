<?php
/**
 * Facet tabs
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
		'name'  => 'conditions',
		'title' => __( 'Conditions', 'wp-grid-builder' ),
	],
	[
		'name'  => 'advanced',
		'title' => __( 'Advanced', 'wp-grid-builder' ),
	],
];
