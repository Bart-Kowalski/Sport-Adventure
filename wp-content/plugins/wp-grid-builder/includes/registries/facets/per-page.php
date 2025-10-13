<?php
/**
 * Per Page Facet
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
	'name'     => __( 'Per Page', 'wp-grid-builder' ),
	'type'     => 'load',
	'icon'     => 'dropdown',
	'class'    => 'WP_Grid_Builder\Includes\FrontEnd\Facets\Per_Page',
	'controls' => [
		[
			'type'   => 'fieldset',
			'legend' => __( 'Per Page', 'wp-grid-builder' ),
			'fields' => [
				'per_page_options' => [
					'type'        => 'text',
					'label'       => __( 'Per Page Options', 'wp-grid-builder' ),
					'placeholder' => __( 'e.g.: 10, 25, 50, 100', 'wp-grid-builder' ),
					'help'        => __( 'Enter a comma-separated list of choices.', 'wp-grid-builder' ),
				],
			],
		],
		[
			'type'   => 'clone',
			'fields' => [
				'combobox',
			],
		],
	],
];
