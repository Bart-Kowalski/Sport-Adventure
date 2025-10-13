<?php
/**
 * Facet controls
 *
 * @package   WP Grid Builder
 * @author    Loïc Blascos
 * @copyright 2019-2024 Loïc Blascos
 */

use WP_Grid_Builder\Includes\Helpers;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$facets  = apply_filters( 'wp_grid_builder/facets', [] );
$filters = array_filter(
	array_map(
		function( $value, $facet ) {

			if ( ! isset( $facet['type'] ) || 'filter' !== $facet['type'] ) {
				return [];
			}

			return [
				'value' => $value,
				'label' => $facet['name'],
				'icon'  => $facet['icon'] ?? 'funnel',
			];
		},
		array_keys( $facets ),
		$facets
	)
);

$loaders = array_filter(
	array_map(
		function( $value, $facet ) {

			if ( ! isset( $facet['type'] ) || 'load' !== $facet['type'] ) {
				return [];
			}

			return [
				'value' => $value,
				'label' => $facet['name'],
				'icon'  => $facet['icon'] ?? 'funnel',
			];
		},
		array_keys( $facets ),
		$facets
	)
);

$taxonomies = Helpers::get_taxonomies_list();
$taxonomies = array_map(
	function( $value, $label ) {
		return [
			'value' => 'taxonomy/' . $value,
			'label' => $label,
		];
	},
	array_keys( $taxonomies ),
	$taxonomies
);

$general_action = [
	'type'   => 'fieldset',
	'panel'  => 'general',
	'legend' => __( 'Behaviour', 'wp-grid-builder' ),
	'fields' => [
		'type'        => [
			'type'   => 'text',
			'hidden' => true,
		],
		'action'      => [
			'type'         => 'select',
			'label'        => __( 'Facet Action', 'wp-grid-builder' ),
			'isClearable'  => false,
			'defaultValue' => 'filter',
			'options'      => [
				[
					'value' => 'filter',
					'label' => __( 'Filter Content', 'wp-grid-builder' ),
					'icon'  => 'funnel',
				],
				[
					'value' => 'load',
					'label' => __( 'Load Content', 'wp-grid-builder' ),
					'icon'  => 'hourglass',
				],
				[
					'value' => 'sort',
					'label' => __( 'Sort Content', 'wp-grid-builder' ),
					'icon'  => 'arrowDown',
				],
				[
					'value' => 'reset',
					'label' => __( 'Reset Filters', 'wp-grid-builder' ),
					'icon'  => 'update',
				],
				[
					'value' => 'apply',
					'label' => __( 'Apply Filters', 'wp-grid-builder' ),
					'icon'  => 'button',
				],
			],
		],
		'filter_type' => [
			'type'         => 'select',
			'label'        => __( 'Filter Type', 'wp-grid-builder' ),
			'isSearchable' => true,
			'isClearable'  => false,
			'defaultValue' => current( array_keys( $filters ) ),
			'options'      => array_values( $filters ),
			'condition'    => [
				[
					'field'   => 'action',
					'compare' => '===',
					'value'   => 'filter',
				],
			],
		],
		'load_type'   => [
			'type'         => 'select',
			'label'        => __( 'Load Type', 'wp-grid-builder' ),
			'isSearchable' => true,
			'isClearable'  => false,
			'defaultValue' => current( array_keys( $loaders ) ),
			'options'      => array_values( $loaders ),
			'condition'    => [
				[
					'field'   => 'action',
					'compare' => '===',
					'value'   => 'load',
				],
			],
		],
	],
];

$general_source = [
	'type'      => 'fieldset',
	'panel'     => 'general',
	'legend'    => __( 'Data Source', 'wp-grid-builder' ),
	'hidden'    => true,
	'cloneable' => true,
	'fields'    => [
		'content_type'      => [
			'type'         => 'button',
			'label'        => __( 'Content Type to Filter', 'wp-grid-builder' ),
			'defaultValue' => 'post',
			'options'      => [
				[
					'value'  => 'post',
					'label'  => __( 'Posts', 'wp-grid-builder' ),
					'icon'   => 'page',
					'inline' => true,
				],
				[
					'value'  => 'term',
					'label'  => __( 'Terms', 'wp-grid-builder' ),
					'icon'   => 'tag',
					'inline' => true,
				],
				[
					'value'  => 'user',
					'label'  => __( 'Users', 'wp-grid-builder' ),
					'icon'   => 'user',
					'inline' => true,
				],
			],
		],
		'post_source'       => [
			'name'           => 'source',
			'type'           => 'select',
			'label'          => __( 'Filter Content By', 'wp-grid-builder' ),
			'async'          => [
				'wpgb/v2/metadata?object=registered&field=key',
				'wpgb/v2/metadata?object=post',
			],
			'defaultOptions' => [
				[
					'label'   => __( 'Taxonomies', 'wp-grid-builder' ),
					'options' => $taxonomies,
				],
				[
					'label'   => __( 'Post Fields', 'wp-grid-builder' ),
					'options' => [
						[
							'value' => 'post_field/post_type',
							'label' => __( 'Post Type', 'wp-grid-builder' ),
						],
						[
							'value' => 'post_field/post_date',
							'label' => __( 'Post Date', 'wp-grid-builder' ),
						],
						[
							'value' => 'post_field/post_modified',
							'label' => __( 'Post Modified Date', 'wp-grid-builder' ),
						],
						[
							'value' => 'post_field/post_title',
							'label' => __( 'Post title', 'wp-grid-builder' ),
						],
						[
							'value' => 'post_field/post_author',
							'label' => __( 'Post author', 'wp-grid-builder' ),
						],
					],
				],
			],
			'condition'      => [
				[
					'field'   => 'content_type',
					'compare' => '===',
					'value'   => 'post',
				],
			],
		],
		'term_source'       => [
			'name'           => 'source',
			'type'           => 'select',
			'label'          => __( 'Filter Content By', 'wp-grid-builder' ),
			'async'          => [
				'wpgb/v2/metadata?object=registered&field=key',
				'wpgb/v2/metadata?object=term',
			],
			'defaultOptions' => [
				[
					'label'   => __( 'Term Fields', 'wp-grid-builder' ),
					'options' => [
						[
							'value' => 'term_field/name',
							'label' => __( 'Term Name', 'wp-grid-builder' ),
						],
						[
							'value' => 'term_field/slug',
							'label' => __( 'Term Slug', 'wp-grid-builder' ),
						],
						[
							'value' => 'term_field/taxonomy',
							'label' => __( 'Term Taxonomy', 'wp-grid-builder' ),
						],
						[
							'value' => 'term_field/term_group',
							'label' => __( 'Term Group', 'wp-grid-builder' ),
						],
					],
				],
			],
			'condition'      => [
				[
					'field'   => 'content_type',
					'compare' => '===',
					'value'   => 'term',
				],
			],
		],
		'user_source'       => [
			'name'           => 'source',
			'type'           => 'select',
			'label'          => __( 'Filter Content By', 'wp-grid-builder' ),
			'async'          => [
				'wpgb/v2/metadata?object=registered&field=key',
				'wpgb/v2/metadata?object=user',
			],
			'defaultOptions' => [
				[
					'label'   => __( 'User Fields', 'wp-grid-builder' ),
					'options' => [
						[
							'value' => 'user_field/display_name',
							'label' => __( 'User Display Name', 'wp-grid-builder' ),
						],
						[
							'value' => 'user_field/first_name',
							'label' => __( 'User First Name', 'wp-grid-builder' ),
						],
						[
							'value' => 'user_field/last_name',
							'label' => __( 'User Last Name', 'wp-grid-builder' ),
						],
						[
							'value' => 'user_field/nickname',
							'label' => __( 'User Nickname', 'wp-grid-builder' ),
						],
						[
							'value' => 'user_field/roles',
							'label' => __( 'User Roles', 'wp-grid-builder' ),
						],
					],
				],
			],
			'condition'      => [
				[
					'field'   => 'content_type',
					'compare' => '===',
					'value'   => 'user',
				],
			],
		],
		'post_source_upper' => [
			'name'      => 'meta_key_upper',
			'type'      => 'select',
			'label'     => __( 'Filter Content By (Upper Source)', 'wp-grid-builder' ),
			'info'      => __( 'Define another custom field as upper limit (optional).', 'wp-grid-builder' ),
			'async'     => [
				'wpgb/v2/metadata?object=registered&field=key',
				'wpgb/v2/metadata?object=post',
			],
			'condition' => [
				[
					'field'   => 'content_type',
					'compare' => '===',
					'value'   => 'post',
				],
				[
					'field'   => 'source',
					'compare' => 'NOT EMPTY',
					'value'   => '',
				],
				[
					'field'   => 'source',
					'compare' => 'NOT CONTAINS',
					'value'   => 'taxonomy/',
				],
				[
					'field'   => 'source',
					'compare' => 'NOT CONTAINS',
					'value'   => 'post_field/',
				],
				[
					'field'   => 'source',
					'compare' => 'NOT CONTAINS',
					'value'   => 'term_field/',
				],
				[
					'field'   => 'source',
					'compare' => 'NOT CONTAINS',
					'value'   => 'user_field/',
				],
			],
		],
		'term_source_upper' => [
			'name'      => 'meta_key_upper',
			'type'      => 'select',
			'label'     => __( 'Filter Content By (Upper Source)', 'wp-grid-builder' ),
			'info'      => __( 'Define another custom field as upper limit (optional).', 'wp-grid-builder' ),
			'async'     => [
				'wpgb/v2/metadata?object=registered&field=key',
				'wpgb/v2/metadata?object=term',
			],
			'condition' => [
				[
					'field'   => 'content_type',
					'compare' => '===',
					'value'   => 'term',
				],
				[
					'field'   => 'source',
					'compare' => 'NOT EMPTY',
					'value'   => '',
				],
				[
					'field'   => 'source',
					'compare' => 'NOT CONTAINS',
					'value'   => 'taxonomy/',
				],
				[
					'field'   => 'source',
					'compare' => 'NOT CONTAINS',
					'value'   => 'post_field/',
				],
				[
					'field'   => 'source',
					'compare' => 'NOT CONTAINS',
					'value'   => 'term_field/',
				],
				[
					'field'   => 'source',
					'compare' => 'NOT CONTAINS',
					'value'   => 'user_field/',
				],
			],
		],
		'user_source_upper' => [
			'name'      => 'meta_key_upper',
			'type'      => 'select',
			'label'     => __( 'Filter Content By (Upper Source)', 'wp-grid-builder' ),
			'info'      => __( 'Define another custom field as upper limit (optional).', 'wp-grid-builder' ),
			'async'     => [
				'wpgb/v2/metadata?object=registered&field=key',
				'wpgb/v2/metadata?object=user',
			],
			'condition' => [
				[
					'field'   => 'content_type',
					'compare' => '===',
					'value'   => 'user',
				],
				[
					'field'   => 'source',
					'compare' => 'NOT EMPTY',
					'value'   => '',
				],
				[
					'field'   => 'source',
					'compare' => 'NOT CONTAINS',
					'value'   => 'taxonomy/',
				],
				[
					'field'   => 'source',
					'compare' => 'NOT CONTAINS',
					'value'   => 'post_field/',
				],
				[
					'field'   => 'source',
					'compare' => 'NOT CONTAINS',
					'value'   => 'term_field/',
				],
				[
					'field'   => 'source',
					'compare' => 'NOT CONTAINS',
					'value'   => 'user_field/',
				],
			],
		],
		'advanced'          => [
			'type'          => 'details',
			'summaryOpened' => __( 'Hide advanced settings', 'wp-grid-builder' ),
			'summaryClosed' => __( 'Show advanced settings', 'wp-grid-builder' ),
			'fields'        => [
				'include'         => [
					'type'        => 'select',
					'label'       => __( 'Include Terms', 'wp-grid-builder' ),
					'placeholder' => _x( 'None', 'Include Terms default value', 'wp-grid-builder' ),
					'async'       => 'wpgb/v2/terms',
					'multiple'    => true,
					'condition'   => [
						[
							'field'   => 'content_type',
							'compare' => '==',
							'value'   => 'post',
						],
						[
							'field'   => 'source',
							'compare' => 'CONTAINS',
							'value'   => 'taxonomy/',
						],
						[
							'field'   => 'exclude',
							'compare' => '==',
							'value'   => '',
						],
					],
				],
				'exclude'         => [
					'type'        => 'select',
					'label'       => __( 'Exclude Terms', 'wp-grid-builder' ),
					'placeholder' => _x( 'None', 'Exclude Terms default value', 'wp-grid-builder' ),
					'async'       => 'wpgb/v2/terms',
					'multiple'    => true,
					'condition'   => [
						[
							'field'   => 'content_type',
							'compare' => '==',
							'value'   => 'post',
						],
						[
							'field'   => 'source',
							'compare' => 'CONTAINS',
							'value'   => 'taxonomy/',
						],
						[
							'field'   => 'include',
							'compare' => '==',
							'value'   => '',
						],
					],
				],
				'include_choices' => [
					'type'             => 'select',
					'label'            => __( 'Include Choices', 'wp-grid-builder' ),
					'placeholder'      => _x( 'None', 'Include Choices default value', 'wp-grid-builder' ),
					'noOptionsMessage' => __( 'Type a value and press enter.', 'wp-grid-builder' ),
					'multiple'         => true,
					'isCreatable'      => true,
					'isSearchable'     => true,
					'condition'        => [
						[
							'field'   => 'source',
							'compare' => 'NOT CONTAINS',
							'value'   => 'taxonomy/',
						],
						[
							'field'   => 'exclude_choices',
							'compare' => '==',
							'value'   => '',
						],
					],
				],
				'exclude_choices' => [
					'type'             => 'select',
					'label'            => __( 'Exclude Choices', 'wp-grid-builder' ),
					'placeholder'      => _x( 'None', 'Exclude Choices default value', 'wp-grid-builder' ),
					'noOptionsMessage' => __( 'Type a value and press enter.', 'wp-grid-builder' ),
					'multiple'         => true,
					'isCreatable'      => true,
					'isSearchable'     => true,
					'condition'        => [
						[
							'field'   => 'source',
							'compare' => 'NOT CONTAINS',
							'value'   => 'taxonomy/',
						],
						[
							'field'   => 'include_choices',
							'compare' => '==',
							'value'   => '',
						],
					],
				],
				'depth'           => [
					'type'      => 'number',
					'label'     => __( 'Term Depth', 'wp-grid-builder' ),
					'info'      => __( 'Depth level of terms to be retrieved.', 'wp-grid-builder' ),
					'condition' => [
						[
							'field'   => 'content_type',
							'compare' => '==',
							'value'   => 'post',
						],
						[
							'field'   => 'source',
							'compare' => 'CONTAINS',
							'value'   => 'taxonomy/',
						],
					],
				],
				'grid'            => [
					'type'   => 'grid',
					'fields' => [
						'child_of' => [
							'type'        => 'select',
							'label'       => __( 'Child Of', 'wp-grid-builder' ),
							'placeholder' => _x( 'None', 'Parent Term default value', 'wp-grid-builder' ),
							'info'        => __( 'Parent term to retrieve child terms of.', 'wp-grid-builder' ),
							'async'       => 'wpgb/v2/terms',
							'condition'   => [
								[
									'field'   => 'content_type',
									'compare' => '==',
									'value'   => 'post',
								],
								[
									'field'   => 'source',
									'compare' => 'CONTAINS',
									'value'   => 'taxonomy/',
								],
							],
						],
						'parent'   => [
							'type'        => 'select',
							'label'       => __( 'Parent Term', 'wp-grid-builder' ),
							'placeholder' => _x( 'None', 'Parent Term default value', 'wp-grid-builder' ),
							'info'        => __( 'Parent term to retrieve direct-child terms of.', 'wp-grid-builder' ),
							'async'       => 'wpgb/v2/terms',
							'condition'   => [
								[
									'field'   => 'content_type',
									'compare' => '==',
									'value'   => 'post',
								],
								[
									'field'   => 'source',
									'compare' => 'CONTAINS',
									'value'   => 'taxonomy/',
								],
							],
						],
					],
				],
			],
		],
	],
	'condition' => [
		[
			'field'   => 'action',
			'compare' => '===',
			'value'   => 'filter',
		],
	],
];

$general_number = [
	'type'      => 'fieldset',
	'panel'     => 'general',
	'legend'    => __( 'Order & Number', 'wp-grid-builder' ),
	'hidden'    => true,
	'cloneable' => true,
	'fields'    => [
		'choice_order' => [
			'type'   => 'grid',
			'fields' => [
				'orderby' => [
					'id'           => 'orderby',
					'type'         => 'select',
					'label'        => __( 'Order By', 'wp-grid-builder' ),
					'defaultValue' => 'count',
					'isClearable'  => false,
					'options'      => [
						[
							'value' => 'count',
							'label' => __( 'Choice Count', 'wp-grid-builder' ),
						],
						[
							'value' => 'facet_name',
							'label' => __( 'Choice Name', 'wp-grid-builder' ),
						],
						[
							'value' => 'facet_value',
							'label' => __( 'Choice Value', 'wp-grid-builder' ),
						],
						[
							'value' => 'facet_order',
							'label' => __( 'Term Order', 'wp-grid-builder' ),
						],
					],
				],
				'order'   => [
					'type'    => 'button',
					'label'   => __( 'Order', 'wp-grid-builder' ),
					'options' => [
						[
							'value' => 'DESC',
							'label' => __( 'Descending', 'wp-grid-builder' ),
							'icon'  => 'arrowDown',
						],
						[
							'value' => 'ASC',
							'label' => __( 'Ascending', 'wp-grid-builder' ),
							'icon'  => 'arrowUp',
						],
					],
				],
			],
		],
		'limit'        => [
			'type'  => 'number',
			'label' => __( 'Number', 'wp-grid-builder' ),
			'info'  => __( 'Maximum number of choices displayed in the filter.', 'wp-grid-builder' ),
			'min'   => 1,
			'max'   => 9999,
		],
		'advanced'     => [
			'type'          => 'details',
			'summaryOpened' => __( 'Hide advanced settings', 'wp-grid-builder' ),
			'summaryClosed' => __( 'Show advanced settings', 'wp-grid-builder' ),
			'fields'        => [
				'display_limit' => [
					'type'  => 'number',
					'label' => __( 'Limit Choices Number', 'wp-grid-builder' ),
					'help'  => __( 'Show a toggle button if the limit is inferior to the number of choices set above.', 'wp-grid-builder' ),
					'min'   => 1,
					'max'   => 9999,
				],
				'toggle_button' => [
					'type'   => 'grid',
					'fields' => [
						'show_more_label' => [
							'type'  => 'text',
							'label' => __( 'Expand Button label', 'wp-grid-builder' ),
							'info'  => __( "Button label used to reveal remaining choices in the list.\n<b>[number]</b> shortcode can be added to display the number of remaining choices.", 'wp-grid-builder' ),
						],
						'show_less_label' => [
							'type'  => 'text',
							'label' => __( 'Collapse Button label', 'wp-grid-builder' ),
							'info'  => __( 'Button label used to collapse the list of choices.', 'wp-grid-builder' ),
						],
					],
				],
			],
		],
	],
];

$general_display = [
	'meta_key'      => [
		'type'   => 'text',
		'hidden' => true,
	],
	'field_type'    => [
		'type'   => 'text',
		'hidden' => true,
	],
	'post_field'    => [
		'type'   => 'text',
		'hidden' => true,
	],
	'term_field'    => [
		'type'   => 'text',
		'hidden' => true,
	],
	'user_field'    => [
		'type'   => 'text',
		'hidden' => true,
	],
	'taxonomy'      => [
		'type'   => 'text',
		'hidden' => true,
	],
	'compare_type'  => [
		'type'         => 'select',
		'panel'        => 'general',
		'hidden'       => true,
		'cloneable'    => true,
		'label'        => __( 'Compare Type', 'wp-grid-builder' ),
		'help'         => __( 'Compares the values of the data source to the range of values selected by the user.', 'wp-grid-builder' ),
		'isClearable'  => false,
		'defaultValue' => 'inside',
		'options'      => [
			[
				'value' => 'inside',
				'label' => __( 'Inside selected range', 'wp-grid-builder' ),
			],
			[
				'value' => 'surround',
				'label' => __( 'Surround selected range', 'wp-grid-builder' ),
			],
			[
				'value' => 'intersect',
				'label' => __( 'Intersect selected range', 'wp-grid-builder' ),
			],
		],
	],
	'show_empty'    => [
		'type'      => 'toggle',
		'panel'     => 'general',
		'hidden'    => true,
		'cloneable' => true,
		'label'     => __( 'Show Empty Choices', 'wp-grid-builder' ),
		'help'      => __( 'Show choices that match no results when filtered.', 'wp-grid-builder' ),
	],
	'show_count'    => [
		'type'      => 'toggle',
		'panel'     => 'general',
		'hidden'    => true,
		'cloneable' => true,
		'label'     => __( 'Show Choice Count', 'wp-grid-builder' ),
		'help'      => __( 'Show the number of results matching each choice.', 'wp-grid-builder' ),
	],
	'children'      => [
		'type'      => 'toggle',
		'panel'     => 'general',
		'hidden'    => true,
		'cloneable' => true,
		'label'     => __( 'Show Children', 'wp-grid-builder' ),
		'help'      => __( 'Show child terms of the selected taxonomy.', 'wp-grid-builder' ),
		'condition' => [
			[
				'field'   => 'content_type',
				'compare' => '==',
				'value'   => 'post',
			],
			[
				'field'   => 'source',
				'compare' => 'CONTAINS',
				'value'   => 'taxonomy/',
			],
		],
	],
	'current_terms' => [
		'type'      => 'toggle',
		'panel'     => 'general',
		'hidden'    => true,
		'cloneable' => true,
		'label'     => __( 'Show Current Terms', 'wp-grid-builder' ),
		'help'      => __( 'Show current archive page terms.', 'wp-grid-builder' ),
		'condition' => [
			[
				'field'   => 'content_type',
				'compare' => '==',
				'value'   => 'post',
			],
			[
				'field'   => 'source',
				'compare' => 'CONTAINS',
				'value'   => 'taxonomy/',
			],
		],
	],
	'logic'         => [
		'type'      => 'button',
		'panel'     => 'general',
		'hidden'    => true,
		'cloneable' => true,
		'label'     => __( 'Logic Between Choices', 'wp-grid-builder' ),
		'options'   => [
			[
				'value' => 'AND',
				'label' => __( 'AND', 'wp-grid-builder' ),
			],
			[
				'value' => 'OR',
				'label' => __( 'OR', 'wp-grid-builder' ),
			],
		],
	],
	'multiple'      => [
		'type'      => 'toggle',
		'panel'     => 'general',
		'hidden'    => true,
		'cloneable' => true,
		'label'     => __( 'Multiple Selection', 'wp-grid-builder' ),
	],
	'hierarchical'  => [
		'type'      => 'toggle',
		'panel'     => 'general',
		'hidden'    => true,
		'cloneable' => true,
		'label'     => __( 'Hierarchical', 'wp-grid-builder' ),
		'help'      => __( 'Display taxonomy terms in hierarchical list.', 'wp-grid-builder' ),
		'condition' => [
			[
				'field'   => 'content_type',
				'compare' => '==',
				'value'   => 'post',
			],
			[
				'field'   => 'source',
				'compare' => 'CONTAINS',
				'value'   => 'taxonomy/',
			],
		],
	],
	'treeview'      => [
		'type'      => 'toggle',
		'panel'     => 'general',
		'hidden'    => true,
		'cloneable' => true,
		'label'     => __( 'Navigation Treeview', 'wp-grid-builder' ),
		'help'      => __( 'Display toggle buttons on parent terms to reveal/hide children.', 'wp-grid-builder' ),
		'condition' => [
			[
				'field'   => 'content_type',
				'compare' => '==',
				'value'   => 'post',
			],
			[
				'field'   => 'source',
				'compare' => 'CONTAINS',
				'value'   => 'taxonomy/',
			],
			[
				'field'   => 'hierarchical',
				'compare' => '==',
				'value'   => 1,
			],
		],
	],
	'all_label'     => [
		'type'      => 'text',
		'panel'     => 'general',
		'hidden'    => true,
		'cloneable' => true,
		'label'     => __( 'All Button Label', 'wp-grid-builder' ),
	],
];

$combobox = [
	'type'        => 'fieldset',
	'panel'       => 'general',
	'legend'      => __( 'Combobox', 'wp-grid-builder' ),
	'description' => __( 'Replace the browser dropdown by a JavaScript based combobox including a search field.', 'wp-grid-builder' ),
	'hidden'      => true,
	'cloneable'   => true,
	'fields'      => [
		'combobox'      => [
			'type'  => 'toggle',
			'label' => __( 'Combobox', 'wp-grid-builder' ),
		],
		'clearable'     => [
			'type'      => 'toggle',
			'label'     => __( 'Clearable List', 'wp-grid-builder' ),
			'condition' => [
				[
					'field'   => 'combobox',
					'compare' => '==',
					'value'   => 1,
				],
			],
		],
		'searchable'    => [
			'type'      => 'toggle',
			'label'     => __( 'Searchable List', 'wp-grid-builder' ),
			'condition' => [
				[
					'field'   => 'combobox',
					'compare' => '==',
					'value'   => 1,
				],
			],
		],
		'async'         => [
			'type'      => 'toggle',
			'label'     => __( 'Asynchronous Search', 'wp-grid-builder' ),
			'condition' => [
				[
					'field'   => 'action',
					'compare' => '===',
					'value'   => 'filter',
				],
				[
					'field'   => 'combobox',
					'compare' => '==',
					'value'   => 1,
				],
				[
					'field'   => 'searchable',
					'compare' => '==',
					'value'   => 1,
				],
			],
		],
		'no_results'    => [
			'type'        => 'text',
			'label'       => __( 'No Results Message', 'wp-grid-builder' ),
			'placeholder' => __( 'No Results Found.', 'wp-grid-builder' ),
			'condition'   => [
				[
					'field'   => 'combobox',
					'compare' => '==',
					'value'   => 1,
				],
			],
		],
		'loading'       => [
			'type'        => 'text',
			'label'       => __( 'Loading Message', 'wp-grid-builder' ),
			'placeholder' => __( 'Loading...', 'wp-grid-builder' ),
			'condition'   => [
				[
					'field'   => 'action',
					'compare' => '===',
					'value'   => 'filter',
				],
				[
					'field'   => 'combobox',
					'compare' => '==',
					'value'   => 1,
				],
				[
					'field'   => 'searchable',
					'compare' => '==',
					'value'   => 1,
				],
				[
					'field'   => 'async',
					'compare' => '==',
					'value'   => 1,
				],
			],
		],
		'search'        => [
			'type'        => 'text',
			'label'       => __( 'Search Message', 'wp-grid-builder' ),
			'placeholder' => __( 'Please enter 1 or more characters.', 'wp-grid-builder' ),
			'condition'   => [
				[
					'field'   => 'action',
					'compare' => '===',
					'value'   => 'filter',
				],
				[
					'field'   => 'combobox',
					'compare' => '==',
					'value'   => 1,
				],
				[
					'field'   => 'searchable',
					'compare' => '==',
					'value'   => 1,
				],
				[
					'field'   => 'async',
					'compare' => '==',
					'value'   => 1,
				],
			],
		],
		'combobox-info' => [
			'type'      => 'tip',
			'warning'   => true,
			'content'   => __( 'Asynchronous search cannot maintain hierarchical listing. Because a search may not match parents and children in one query, this is not possible to maintain a hierarchical listing.', 'wp-grid-builder' ),
			'condition' => [
				[
					'field'   => 'action',
					'compare' => '===',
					'value'   => 'filter',
				],
				[
					'field'   => 'content_type',
					'compare' => '==',
					'value'   => 'post',
				],
				[
					'field'   => 'source',
					'compare' => 'CONTAINS',
					'value'   => 'taxonomy/',
				],
				[
					'field'   => 'hierarchical',
					'compare' => '==',
					'value'   => 1,
				],
				[
					'field'   => 'combobox',
					'compare' => '==',
					'value'   => 1,
				],
				[
					'field'   => 'searchable',
					'compare' => '==',
					'value'   => 1,
				],
				[
					'field'   => 'async',
					'compare' => '==',
					'value'   => 1,
				],
			],
		],
	],
];

$values_format = [
	'type'      => 'fieldset',
	'panel'     => 'general',
	'legend'    => __( 'Values Format', 'wp-grid-builder' ),
	'hidden'    => true,
	'cloneable' => true,
	'fields'    => [
		'grid' => [
			'type'   => 'grid',
			'fields' => [
				'prefix'              => [
					'type'        => 'text',
					'label'       => __( 'Value Prefix', 'wp-grid-builder' ),
					'whiteSpaces' => true,
					'angleQuotes' => true,
				],
				'suffix'              => [
					'type'        => 'text',
					'label'       => __( 'Value Suffix', 'wp-grid-builder' ),
					'whiteSpaces' => true,
					'angleQuotes' => true,
				],
				'step'                => [
					'type'  => 'number',
					'label' => __( 'Step Interval', 'wp-grid-builder' ),
					'min'   => 0.0001,
					'max'   => 999999,
					'step'  => 0.0001,
				],
				'thousands_separator' => [
					'type'        => 'text',
					'label'       => __( 'Thousands Separator', 'wp-grid-builder' ),
					'whiteSpaces' => true,
				],
				'decimal_separator'   => [
					'type'        => 'text',
					'label'       => __( 'Decimal Separator', 'wp-grid-builder' ),
					'whiteSpaces' => true,
				],
				'decimal_places'      => [
					'type'  => 'number',
					'label' => __( 'Decimal Places', 'wp-grid-builder' ),
				],
			],
		],
	],
];

$conditions = [
	'type'        => 'fieldset',
	'panel'       => 'conditions',
	'legend'      => __( 'Conditional Actions', 'wp-grid-builder' ),
	'description' => __( 'Conditionally reveal, hide or disable the facet.', 'wp-grid-builder' ),
	'fields'      => [
		'actions' => [
			'type'     => 'repeater',
			'addLabel' => __( 'Add Action', 'wp-grid-builder' ),
			'maxRows'  => 10,
			'fields'   => [
				'action'     => [
					'type'    => 'select',
					'label'   => __( 'Action', 'wp-grid-builder' ),
					'options' => [
						[
							'value' => 'hide',
							'label' => __( 'Hide Facet', 'wp-grid-builder' ),
						],
						[
							'value' => 'reveal',
							'label' => __( 'Reveal Facet', 'wp-grid-builder' ),
						],
						[
							'value' => 'disable',
							'label' => __( 'Disable Facet', 'wp-grid-builder' ),
						],
						[
							'value' => 'enable',
							'label' => __( 'Enable Facet', 'wp-grid-builder' ),
						],
					],
				],
				'conditions' => [
					'type'    => 'condition',
					'label'   => __( 'Conditions', 'wp-grid-builder' ),
					'context' => 'facet',
				],
			],
		],
	],
];

$advanced = [
	'type'   => 'fieldset',
	'panel'  => 'advanced',
	'legend' => __( 'Naming', 'wp-grid-builder' ),
	'fields' => [
		'name'   => [
			'type'   => 'text',
			'hidden' => true,
		],
		'grid'   => [
			'type'   => 'grid',
			'fields' => [
				'slug'  => [
					'type'        => 'text',
					'label'       => __( 'Facet Slug', 'wp-grid-builder' ),
					'help'        => __( 'Slug is used as parameter in the url to filter content.', 'wp-grid-builder' ),
					'placeholder' => __( 'Enter a facet slug', 'wp-grid-builder' ),
				],
				'title' => [
					'type'        => 'text',
					'label'       => __( 'Facet Title', 'wp-grid-builder' ),
					'help'        => __( 'Title is optional and it is displayed above the facet.', 'wp-grid-builder' ),
					'placeholder' => __( 'Enter a facet title', 'wp-grid-builder' ),

				],
			],
		],
		'css_id' => [
			'type'        => 'text',
			'label'       => __( 'Generated CSS Class', 'wp-grid-builder' ),
			'help'        => __( 'Useful to target a particular facet with CSS or JS.', 'wp-grid-builder' ),
			'placeholder' => __( 'Please save the facet to generate the CSS class.', 'wp-grid-builder' ),
			'disabled'    => true,
		],
	],
];

return array_merge(
	[
		'general_action' => $general_action,
		'general_source' => $general_source,
		'general_number' => $general_number,
		'combobox'       => $combobox,
		'values_format'  => $values_format,
		'general_facets' => [
			'panel'  => 'general',
			'fields' => array_filter(
				array_map(
					function( $block ) {

						if ( empty( $block['controls'] ) ) {
							return [];
						}

						unset(
							$block['name'],
							$block['class']
						);

						return $block;

					},
					$facets
				)
			),
		],
		'conditions'     => $conditions,
		'advanced'       => $advanced,
	],
	$general_display
);
