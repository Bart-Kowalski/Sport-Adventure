<?php
/**
 * Autocomplete Facet
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
	'name'     => __( 'Auto-Complete', 'wp-grid-builder' ),
	'type'     => 'filter',
	'icon'     => 'autocomplete',
	'class'    => 'WP_Grid_Builder\Includes\FrontEnd\Facets\Autocomplete',
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
			'legend' => __( 'Auto-Complete', 'wp-grid-builder' ),
			'fields' => [
				'acplt_placeholder' => [
					'type'  => 'text',
					'label' => __( 'Search Placeholder', 'wp-grid-builder' ),
				],
				'grid'              => [
					'type'   => 'grid',
					'fields' => [
						'acplt_debounce'   => [
							'type'        => 'number',
							'label'       => __( 'Debounce', 'wp-grid-builder' ),
							'info'        => __( 'Number of milliseconds for which the search is delayed.', 'wp-grid-builder' ),
							'suffix'      => 'ms',
							'min'         => 0,
							'max'         => 9999,
							'placeholder' => 350,

						],
						'acplt_min_length' => [
							'type'        => 'number',
							'label'       => __( 'Min Characters', 'wp-grid-builder' ),
							'info'        => __( 'Minimum number of characters to trigger a search.', 'wp-grid-builder' ),
							'min'         => 1,
							'max'         => 999,
							'placeholder' => 1,
						],
						'acplt_relevance'  => [
							'type'  => 'toggle',
							'label' => __( 'Sort by Relevance', 'wp-grid-builder' ),
							'help'  => __( 'Sort suggested choices by relevance.', 'wp-grid-builder' ),
						],
						'acplt_auto_focus' => [
							'type'  => 'toggle',
							'label' => __( 'Auto Focus', 'wp-grid-builder' ),
							'help'  => __( 'Automatically focus first suggestion in the list.', 'wp-grid-builder' ),
						],
						'acplt_highlight'  => [
							'type'  => 'toggle',
							'label' => __( 'Highlight', 'wp-grid-builder' ),
							'help'  => __( 'Highlight matched string in suggestion.', 'wp-grid-builder' ),
						],
						'acplt_match_all'  => [
							'type'      => 'toggle',
							'label'     => __( 'Highlight All Matches', 'wp-grid-builder' ),
							'help'      => __( 'Highlights all matching strings in suggestion.', 'wp-grid-builder' ),
							'condition' => [
								[
									'field'   => 'acplt_highlight',
									'compare' => '==',
									'value'   => 1,
								],
							],
						],
						'clone'            => [
							'type'   => 'clone',
							'fields' => [
								'show_empty',
								'show_count',
								'current_terms',
								'children',
							],
						],
					],
				],
			],
		],
	],
];
