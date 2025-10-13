<?php
/**
 * Radio Facet
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
	'name'     => __( 'Radio Buttons', 'wp-grid-builder' ),
	'type'     => 'filter',
	'icon'     => 'radio',
	'class'    => 'WP_Grid_Builder\Includes\FrontEnd\Facets\Radio',
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
			'legend' => __( 'Radio Buttons', 'wp-grid-builder' ),
			'fields' => [
				'clone' => [
					'type'   => 'clone',
					'fields' => [
						'all_label',
						'show_empty',
						'show_count',
						'current_terms',
						'children',
					],
				],
			],
		],
	],
];
