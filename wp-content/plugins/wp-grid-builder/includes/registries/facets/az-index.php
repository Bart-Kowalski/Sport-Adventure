<?php
/**
 * A-Z Index Facet
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
	'name'     => __( 'A-Z Index', 'wp-grid-builder' ),
	'type'     => 'filter',
	'icon'     => 'textColor',
	'class'    => 'WP_Grid_Builder\Includes\FrontEnd\Facets\AZ_Index',
	'preview'  => array_map(
		function( $letter ) {
			return (object) [
				'facet_value' => $letter,
				'facet_name'  => strtoupper( $letter ),
				'type'        => 'letter',
				'count'       => wp_rand( 0, 99 ),
			];
		},
		range( 'a', 'z' )
	),
	'controls' => [
		[
			'type'   => 'clone',
			'fields' => [
				'content_type',
				'source',
				'include',
				'exclude',
				'parent',
				'child_of',
				'depth',
				'orderby',
				'order',
			],
		],
		[
			'type'   => 'fieldset',
			'legend' => __( 'A-Z Index', 'wp-grid-builder' ),
			'fields' => [
				'clone1'             => [
					'type'   => 'clone',
					'fields' => [
						'logic',
						'all_label',
					],
				],
				'alphabetical_index' => [
					'type'  => 'text',
					'label' => __( 'Alphabetical Index', 'wp-grid-builder' ),
					'info'  => __( 'Comma separated list of letters used to filter.', 'wp-grid-builder' ),
				],
				'numeric_index'      => [
					'type'  => 'text',
					'label' => __( 'Numeric Index', 'wp-grid-builder' ),
					'info'  => __( 'Comma separated list of numbers used to filter.', 'wp-grid-builder' ),
					'help'  => __( '# (hashtag) can be used as an alias to filter by all numbers.', 'wp-grid-builder' ),
				],
				'clone2'             => [
					'type'   => 'clone',
					'fields' => [
						'show_empty',
						'show_count',
						'multiple',
					],
				],
			],
		],
	],
];
