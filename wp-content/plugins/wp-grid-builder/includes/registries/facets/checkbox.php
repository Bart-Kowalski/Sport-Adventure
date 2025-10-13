<?php
/**
 * Checkbox Facet
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
	'name'     => __( 'Checkboxes', 'wp-grid-builder' ),
	'type'     => 'filter',
	'icon'     => 'checkbox',
	'class'    => 'WP_Grid_Builder\Includes\FrontEnd\Facets\Checkbox',
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
				'display_limit',
				'show_more_label',
				'show_less_label',
			],
		],
		[
			'type'   => 'fieldset',
			'legend' => __( 'Checkbox', 'wp-grid-builder' ),
			'fields' => [
				'clone' => [
					'type'   => 'clone',
					'fields' => [
						'logic',
						'show_empty',
						'show_count',
						'current_terms',
						'children',
						'hierarchical',
						'treeview',
					],
				],
			],
		],
	],
];
