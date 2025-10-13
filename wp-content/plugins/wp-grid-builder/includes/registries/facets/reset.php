<?php
/**
 * Reset Facet
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
	'name'     => __( 'Reset', 'wp-grid-builder' ),
	'type'     => 'reset',
	'class'    => 'WP_Grid_Builder\Includes\FrontEnd\Facets\Reset',
	'controls' => [
		[
			'type'   => 'fieldset',
			'legend' => __( 'Reset Options', 'wp-grid-builder' ),
			'fields' => [
				'reset_label' => [
					'type'  => 'text',
					'label' => __( 'Button Label', 'wp-grid-builder' ),
				],
				'reset_facet' => [
					'type'        => 'select',
					'label'       => __( 'Reset Facets', 'wp-grid-builder' ),
					'placeholder' => _x( 'All', 'Reset Facets default value', 'wp-grid-builder' ),
					'multiple'    => true,
					'async'       => 'wpgb/v2/objects?object=facets&orderby=name&order=asc&fields=id,name',

				],
			],
		],
	],
];
