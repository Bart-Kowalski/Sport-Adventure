<?php
/**
 * Search Facet
 *
 * @package   WP Grid Builder
 * @author    Loïc Blascos
 * @copyright 2019-2024 Loïc Blascos
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$engines = apply_filters(
	'wp_grid_builder/facet/search_engines',
	[
		'wordpress'  => 'WordPress',
		'relevanssi' => 'Relevanssi',
		'searchwp'   => 'SearchWP',
	]
);

return [
	'name'     => __( 'Search Field', 'wp-grid-builder' ),
	'type'     => 'filter',
	'icon'     => 'search',
	'class'    => 'WP_Grid_Builder\Includes\FrontEnd\Facets\Search',
	'controls' => [
		[
			'type'   => 'clone',
			'fields' => [
				'content_type',
			],
		],
		[
			'type'   => 'fieldset',
			'legend' => __( 'Search Field', 'wp-grid-builder' ),
			'fields' => [
				'search_placeholder'  => [
					'type'  => 'text',
					'label' => __( 'Search Placeholder', 'wp-grid-builder' ),
				],
				'grid_1'              => [
					'type'   => 'grid',
					'fields' => [
						'search_engine' => [
							'type'         => 'select',
							'label'        => __( 'Search Engine', 'wp-grid-builder' ),
							'defaultValue' => 'wordpress',
							'isClearable'  => false,
							'options'      => array_map(
								function( $value, $label ) {

									return [
										'value'    => $value,
										'label'    => $label,
										'disabled' => (
											! function_exists( 'relevanssi_search' ) && 'relevanssi' === $value ||
											! class_exists( 'SWP_Query' ) && 'searchwp' === $value
										),
									];
								},
								array_keys( $engines ),
								$engines
							),
						],
						'search_number' => [
							'type'  => 'number',
							'label' => __( 'Search Number', 'wp-grid-builder' ),
							'info'  => __( 'Number of results to search. The number of results can significantly impact performance. Generally, a large amount of results is not necessary and may confuse users which will search again to narrow down results.', 'wp-grid-builder' ),
							'min'   => -1,
							'max'   => 9999,
						],
					],
				],
				'search_post_columns' => [
					'type'        => 'select',
					'label'       => __( 'Search Fields', 'wp-grid-builder' ),
					'placeholder' => _x( 'Default', 'Search Columns', 'wp-grid-builder' ),
					'multiple'    => true,
					'options'     => [
						[
							'value' => 'post_title',
							'label' => __( 'Post Title', 'wp-grid-builder' ),
						],
						[
							'value' => 'post_content',
							'label' => __( 'Post Content', 'wp-grid-builder' ),
						],
						[
							'value' => 'post_excerpt',
							'label' => __( 'Post Excerpt', 'wp-grid-builder' ),
						],
					],
					'condition'   => [
						'relation' => 'AND',
						[
							'relation' => 'OR',
							[
								'field'   => 'content_type',
								'compare' => '==',
								'value'   => '',
							],
							[
								'field'   => 'content_type',
								'compare' => '==',
								'value'   => 'post',
							],
						],
						[
							'relation' => 'OR',
							[
								'field'   => 'search_engine',
								'compare' => '==',
								'value'   => '',
							],
							[
								'field'   => 'search_engine',
								'compare' => '==',
								'value'   => 'wordpress',
							],
						],
					],
				],
				'search_user_columns' => [
					'type'        => 'select',
					'label'       => __( 'Search Fields', 'wp-grid-builder' ),
					'placeholder' => _x( 'Default', 'Search Columns', 'wp-grid-builder' ),
					'multiple'    => true,
					'options'     => [
						[
							'value' => 'ID',
							'label' => __( 'User ID', 'wp-grid-builder' ),
						],
						[
							'value' => 'display_name',
							'label' => __( 'User Display Name', 'wp-grid-builder' ),
						],
						[
							'value' => 'user_login',
							'label' => __( 'User Login', 'wp-grid-builder' ),
						],
						[
							'value' => 'user_nicename',
							'label' => __( 'User Nicename', 'wp-grid-builder' ),
						],
						[
							'value' => 'user_email',
							'label' => __( 'User Email', 'wp-grid-builder' ),
						],
						[
							'value' => 'user_url',
							'label' => __( 'User Website', 'wp-grid-builder' ),
						],
					],
					'condition'   => [
						'relation' => 'AND',
						[
							'field'   => 'content_type',
							'compare' => '==',
							'value'   => 'user',
						],
						[
							'relation' => 'OR',
							[
								'field'   => 'search_engine',
								'compare' => '==',
								'value'   => '',
							],
							[
								'field'   => 'search_engine',
								'compare' => '==',
								'value'   => 'wordpress',
							],
						],
					],
				],
				'grid_2'              => [
					'type'   => 'grid',
					'fields' => [
						'search_debounce'   => [
							'type'        => 'number',
							'label'       => __( 'Debounce', 'wp-grid-builder' ),
							'info'        => __( 'Number of milliseconds for which the search is delayed.', 'wp-grid-builder' ),
							'suffix'      => 'ms',
							'min'         => 0,
							'max'         => 9999,
							'placeholder' => 350,

						],
						'search_min_length' => [
							'type'        => 'number',
							'label'       => __( 'Min Characters', 'wp-grid-builder' ),
							'info'        => __( 'Minimum number of characters to trigger a search.', 'wp-grid-builder' ),
							'min'         => 1,
							'max'         => 999,
							'placeholder' => 1,
						],
					],
				],
				'search_relevancy'    => [
					'type'  => 'toggle',
					'label' => __( 'Search Relevancy', 'wp-grid-builder' ),
					'help'  => __( 'Keep search order relevance. Can be useful when using a plugin like SearchWP with weighting attributes.', 'wp-grid-builder' ),
				],
				'instant_search'      => [
					'type'  => 'toggle',
					'label' => __( 'Instant Search', 'wp-grid-builder' ),
					'help'  => __( 'Update results and facets while typing.', 'wp-grid-builder' ),
				],
			],
		],
	],
];
