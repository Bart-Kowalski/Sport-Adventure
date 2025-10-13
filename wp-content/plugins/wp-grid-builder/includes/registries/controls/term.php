<?php
/**
 * Term controls
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
	'color'      => [
		'type'  => 'color',
		'label' => __( 'Color', 'wp-grid-builder' ),
		'help'  => __( 'Color used in cards (Taxonomy terms block)', 'wp-grid-builder' ),
		'width' => 320,
	],
	'background' => [
		'type'  => 'color',
		'label' => __( 'Background', 'wp-grid-builder' ),
		'help'  => __( 'Background used in cards (Taxonomy terms block)', 'wp-grid-builder' ),
		'width' => 320,
	],
];
