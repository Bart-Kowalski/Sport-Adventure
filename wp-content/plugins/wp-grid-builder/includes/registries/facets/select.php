<?php
/**
 * Select Facet
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
	'name'     => __( 'Dropdown', 'wp-grid-builder' ),
	'type'     => 'filter',
	'icon'     => 'dropdown',
	'class'    => 'WP_Grid_Builder\Includes\FrontEnd\Facets\Select',
	'controls' => [
		[
			'type'   => 'clone',
			'fields' => [
				'content_type',
				'source',
				'include',
				'exclude',
				'include_choices',
				'exclude_choices',
				'parent',
				'child_of',
				'depth',
				'orderby',
				'order',
				'limit',
			],
		],
		[
			'type'   => 'fieldset',
			'legend' => __( 'Dropdown', 'wp-grid-builder' ),
			'fields' => [
				'clone'              => [
					'type'   => 'clone',
					'fields' => [
						'logic',
					],
				],
				'select_placeholder' => [
					'type'  => 'text',
					'label' => __( 'Placeholder', 'wp-grid-builder' ),
				],
				'clone2'             => [
					'type'   => 'clone',
					'fields' => [
						'show_empty',
						'show_count',
						'current_terms',
						'children',
						'hierarchical',
						'multiple',
					],
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
