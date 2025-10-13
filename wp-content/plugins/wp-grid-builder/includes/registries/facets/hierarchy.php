<?php
/**
 * Hierarchy Facet
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
	'name'     => __( 'Hierarchy', 'wp-grid-builder' ),
	'type'     => 'filter',
	'icon'     => 'hierarchy',
	'class'    => 'WP_Grid_Builder\Includes\FrontEnd\Facets\Hierarchy',
	'preview'  => array_map(
		function( $index ) {
			return (object) [
				'facet_value'  => 'choice_' . $index,
				'facet_name'   => sprintf(
					/* translators: Choice number */
					__( 'Choice %d', 'wp-grid-builder' ),
					$index + 1
				),
				'facet_slug'   => 'slug',
				'facet_parent' => 0,
				'facet_id'     => $index + 1,
				'count'        => wp_rand( 0, 100 ),
			];
		},
		range( 0, 9 )
	),
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
			'legend' => __( 'Hierarchy', 'wp-grid-builder' ),
			'fields' => [
				'clone' => [
					'type'   => 'clone',
					'fields' => [
						'all_label',
						'show_count',
					],
				],
			],
		],
	],
];
