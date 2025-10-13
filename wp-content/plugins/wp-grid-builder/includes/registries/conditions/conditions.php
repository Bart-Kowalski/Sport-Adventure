<?php
/**
 * Conditions
 *
 * @package   WP Grid Builder
 * @author    Loïc Blascos
 * @copyright 2019-2024 Loïc Blascos
 */

namespace WP_Grid_Builder\Includes\Registries\Conditions;

use WP_Grid_Builder\Includes\Helpers;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $wp_locale;

$logic_operators = [
	[
		'value' => '==',
		'label' => '==',
	],
	[
		'value' => '!=',
		'label' => '!=',
	],
	[
		'value' => '>',
		'label' => '>',
	],
	[
		'value' => '<',
		'label' => '<',
	],
	[
		'value' => '>=',
		'label' => '>=',
	],
	[
		'value' => '<=',
		'label' => '<=',
	],
];

$is_operators = [
	[
		'value' => '==',
		'label' => __( 'Is', 'wp-grid-builder' ),
	],
	[
		'value' => '!=',
		'label' => __( 'Is not', 'wp-grid-builder' ),
	],
];

$includes_operators = [
	[
		'value' => 'IN',
		'label' => __( 'In', 'wp-grid-builder' ),
	],
	[
		'value' => 'NOT IN',
		'label' => __( 'Not In', 'wp-grid-builder' ),
	],
];

$contains_operators = [
	[
		'value' => 'CONTAINS',
		'label' => __( 'Contains', 'wp-grid-builder' ),
	],
	[
		'value' => 'NOT CONTAINS',
		'label' => __( 'Not Contains', 'wp-grid-builder' ),
	],
];

return [
	'post'         => [
		'label'   => __( 'Post Data', 'wp-grid-builder' ),
		'options' => [
			[
				'context' => [ 'card' ],
				'value'   => 'post_id',
				'label'   => __( 'Post ID', 'wp-grid-builder' ),
				'fields'  => [
					'compare' => [
						'options' => $logic_operators,
					],
					'value'   => [
						'type'        => 'number',
						'min'         => 0,
						'max'         => 9999999999999999,
						'step'        => 1,
						'placeholder' => 0,
					],
				],
			],
			[
				'context' => [ 'card' ],
				'value'   => 'post_parent',
				'label'   => __( 'Post Parent', 'wp-grid-builder' ),
				'fields'  => [
					'compare' => [
						'options' => $logic_operators,
					],
					'value'   => [
						'type'        => 'number',
						'min'         => 0,
						'max'         => 9999999999999999,
						'step'        => 1,
						'placeholder' => 0,
					],
				],
			],
			[
				'context' => [ 'card' ],
				'value'   => 'post_name',
				'label'   => __( 'Post Name', 'wp-grid-builder' ),
				'fields'  => [
					'compare' => [
						'options' => array_merge(
							$is_operators,
							$contains_operators
						),
					],
				],
			],
			[
				'context' => [ 'card' ],
				'value'   => 'post_title',
				'label'   => __( 'Post Title', 'wp-grid-builder' ),
				'fields'  => [
					'compare' => [
						'options' => array_merge(
							$is_operators,
							$contains_operators
						),
					],
				],
			],
			[
				'context' => [ 'card' ],
				'value'   => 'post_author',
				'label'   => __( 'Post Author', 'wp-grid-builder' ),
				'fields'  => [
					'compare' => [
						'options' => $is_operators,
					],
					'value'   => [
						'type'  => 'select',
						'async' => 'wpgb/v2/users',
					],
				],
			],
			[
				'context' => [ 'card' ],
				'value'   => 'post_permalink',
				'label'   => __( 'Post Permalink', 'wp-grid-builder' ),
				'fields'  => [
					'compare' => [
						'options' => array_merge(
							$is_operators,
							$contains_operators
						),
					],
				],
			],
			[
				'context' => [ 'card' ],
				'value'   => 'post_date',
				'label'   => __( 'Post Date', 'wp-grid-builder' ),
				'fields'  => [
					'compare' => [
						'options' => $logic_operators,
					],
					'value'   => [
						'type' => 'date',
					],
				],
			],
			[
				'context' => [ 'card' ],
				'value'   => 'post_modified_date',
				'label'   => __( 'Post Modified Date', 'wp-grid-builder' ),
				'fields'  => [
					'compare' => [
						'options' => $logic_operators,
					],
					'value'   => [
						'type' => 'date',
					],
				],
			],
			[
				'context' => [ 'card' ],
				'value'   => 'post_content',
				'label'   => __( 'Post Content', 'wp-grid-builder' ),
				'fields'  => [
					'compare' => [
						'options' => array_merge(
							$is_operators,
							$contains_operators
						),
					],
				],
			],
			[
				'context' => [ 'card' ],
				'value'   => 'post_excerpt',
				'label'   => __( 'Post Excerpt', 'wp-grid-builder' ),
				'fields'  => [
					'compare' => [
						'options' => array_merge(
							$is_operators,
							$contains_operators
						),
					],
				],
			],
			[
				'context' => [ 'card' ],
				'value'   => 'post_term',
				'label'   => __( 'Post Term', 'wp-grid-builder' ),
				'fields'  => [
					'compare' => [
						'options' => $includes_operators,
					],
					'value'   => [
						'type'     => 'select',
						'async'    => 'wpgb/v2/terms',
						'multiple' => true,
					],
				],
			],
			[
				'context' => [ 'card' ],
				'value'   => 'post_status',
				'label'   => __( 'Post Status', 'wp-grid-builder' ),
				'fields'  => [
					'compare' => [
						'options' => $includes_operators,
					],
					'value'   => [
						'type'         => 'select',
						'options'      => array_slice( Helpers::format_options( Helpers::get_post_status() ), 1 ),
						'multiple'     => true,
						'isSearchable' => true,
					],
				],
			],
			[
				'context' => [ 'card' ],
				'value'   => 'post_type',
				'label'   => __( 'Post Type', 'wp-grid-builder' ),
				'fields'  => [
					'compare' => [
						'options' => $includes_operators,
					],
					'value'   => [
						'type'         => 'select',
						'options'      => Helpers::format_options( Helpers::get_post_types() ),
						'multiple'     => true,
						'isSearchable' => true,
					],
				],
			],
			[
				'context' => [ 'card' ],
				'value'   => 'post_format',
				'label'   => __( 'Post Format', 'wp-grid-builder' ),
				'fields'  => [
					'compare' => [
						'options' => $includes_operators,
					],
					'value'   => [
						'type'         => 'select',
						'options'      => Helpers::format_options( get_post_format_strings() ),
						'multiple'     => true,
						'isSearchable' => true,
					],
				],
			],
			[
				'context' => [ 'card' ],
				'value'   => 'post_thumbnail',
				'label'   => __( 'Post Thumbnail', 'wp-grid-builder' ),
				'fields'  => [
					'compare' => [
						'options' => $is_operators,
					],
					'value'   => [
						'type'    => 'select',
						'options' => [
							[
								'value' => 1,
								'label' => __( 'Set', 'wp-grid-builder' ),
							],
							[
								'value' => 0,
								'label' => __( 'Not Set', 'wp-grid-builder' ),
							],
						],
					],
				],
			],
			[
				'context' => [ 'card' ],
				'value'   => 'post_comments_number',
				'label'   => __( 'Post Comments Count', 'wp-grid-builder' ),
				'fields'  => [
					'compare' => [
						'options' => $logic_operators,
					],
					'value'   => [
						'type'        => 'number',
						'min'         => 0,
						'max'         => 9999999999999999,
						'step'        => 1,
						'placeholder' => 0,
					],
				],
			],
			[
				'context' => [ 'card' ],
				'value'   => 'post_metadata',
				'label'   => __( 'Post Custom Field', 'wp-grid-builder' ),
				'fields'  => [
					'field'   => [
						'type'        => 'select',
						'label'       => __( 'Field', 'wp-grid-builder' ),
						'placeholder' => __( 'Select a field', 'wp-grid-builder' ),
						'async'       => [
							'wpgb/v2/metadata?object=registered',
							'wpgb/v2/metadata?object=post',
							'wpgb/v2/metadata?object=term',
							'wpgb/v2/metadata?object=user',
						],
					],
					'compare' => [
						'options' => array_merge(
							$logic_operators,
							$contains_operators
						),
					],
				],
			],
		],
	],
	'term'         => [
		'label'   => __( 'Term Data', 'wp-grid-builder' ),
		'options' => [
			[
				'context' => [ 'card' ],
				'value'   => 'term_id',
				'label'   => __( 'Term ID', 'wp-grid-builder' ),
				'fields'  => [
					'compare' => [
						'options' => $logic_operators,
					],
					'value'   => [
						'type'        => 'number',
						'min'         => 0,
						'max'         => 9999999999999999,
						'step'        => 1,
						'placeholder' => 0,
					],
				],
			],
			[
				'context' => [ 'card' ],
				'value'   => 'term_slug',
				'label'   => __( 'Term Slug', 'wp-grid-builder' ),
				'fields'  => [
					'compare' => [
						'options' => array_merge(
							$is_operators,
							$contains_operators
						),
					],
				],
			],
			[
				'context' => [ 'card' ],
				'value'   => 'term_name',
				'label'   => __( 'Term Name', 'wp-grid-builder' ),
				'fields'  => [
					'compare' => [
						'options' => array_merge(
							$is_operators,
							$contains_operators
						),
					],
				],
			],
			[
				'context' => [ 'card' ],
				'value'   => 'term_taxonomy',
				'label'   => __( 'Term Taxonomy', 'wp-grid-builder' ),
				'fields'  => [
					'compare' => [
						'options' => $includes_operators,
					],
					'value'   => [
						'type'         => 'select',
						'options'      => Helpers::format_options( Helpers::get_taxonomies_list() ),
						'multiple'     => true,
						'isSearchable' => true,
					],
				],
			],
			[
				'context' => [ 'card' ],
				'value'   => 'term_parent',
				'label'   => __( 'Term Parent', 'wp-grid-builder' ),
				'fields'  => [
					'compare' => [
						'options' => $logic_operators,
					],
					'value'   => [
						'type'        => 'number',
						'min'         => 0,
						'max'         => 9999999999999999,
						'step'        => 1,
						'placeholder' => 0,
					],
				],
			],
			[
				'context' => [ 'card' ],
				'value'   => 'term_description',
				'label'   => __( 'Term Description', 'wp-grid-builder' ),
				'fields'  => [
					'compare' => [
						'options' => array_merge(
							$is_operators,
							$contains_operators
						),
					],
				],
			],
			[
				'context' => [ 'card' ],
				'value'   => 'term_count',
				'label'   => __( 'Term Posts Count', 'wp-grid-builder' ),
				'fields'  => [
					'compare' => [
						'options' => $logic_operators,
					],
					'value'   => [
						'type'        => 'number',
						'min'         => 0,
						'max'         => 9999999999999999,
						'step'        => 1,
						'placeholder' => 0,
					],
				],
			],
			[
				'context' => [ 'card' ],
				'value'   => 'term_metadata',
				'label'   => __( 'Term Custom Field', 'wp-grid-builder' ),
				'fields'  => [
					'field'   => [
						'type'        => 'select',
						'label'       => __( 'Field', 'wp-grid-builder' ),
						'placeholder' => __( 'Select a field', 'wp-grid-builder' ),
						'async'       => [
							'wpgb/v2/metadata?object=registered',
							'wpgb/v2/metadata?object=post',
							'wpgb/v2/metadata?object=term',
							'wpgb/v2/metadata?object=user',
						],
					],
					'compare' => [
						'options' => array_merge(
							$logic_operators,
							$contains_operators
						),
					],
				],
			],
		],
	],
	'user'         => [
		'label'   => __( 'User Data', 'wp-grid-builder' ),
		'options' => [
			[
				'context' => [ 'card' ],
				'value'   => 'user_id',
				'label'   => __( 'User ID', 'wp-grid-builder' ),
				'fields'  => [
					'compare' => [
						'options' => $logic_operators,
					],
					'value'   => [
						'type'        => 'number',
						'min'         => 0,
						'max'         => 9999999999999999,
						'step'        => 1,
						'placeholder' => 0,
					],
				],
			],
			[
				'context' => [ 'card' ],
				'value'   => 'user_login',
				'label'   => __( 'Username', 'wp-grid-builder' ),
				'fields'  => [
					'compare' => [
						'options' => array_merge(
							$is_operators,
							$contains_operators
						),
					],
				],
			],
			[
				'context' => [ 'card' ],
				'value'   => 'user_display_name',
				'label'   => __( 'User Display Name', 'wp-grid-builder' ),
				'fields'  => [
					'compare' => [
						'options' => array_merge(
							$is_operators,
							$contains_operators
						),
					],
				],
			],
			[
				'context' => [ 'card' ],
				'value'   => 'user_first_name',
				'label'   => __( 'User First Name', 'wp-grid-builder' ),
				'fields'  => [
					'compare' => [
						'options' => array_merge(
							$is_operators,
							$contains_operators
						),
					],
				],
			],
			[
				'context' => [ 'card' ],
				'value'   => 'user_last_name',
				'label'   => __( 'User Last Name', 'wp-grid-builder' ),
				'fields'  => [
					'compare' => [
						'options' => array_merge(
							$is_operators,
							$contains_operators
						),
					],
				],
			],
			[
				'context' => [ 'card' ],
				'value'   => 'user_nickname',
				'label'   => __( 'User Nickname', 'wp-grid-builder' ),
				'fields'  => [
					'compare' => [
						'options' => array_merge(
							$is_operators,
							$contains_operators
						),
					],
				],
			],
			[
				'context' => [ 'card' ],
				'value'   => 'user_email',
				'label'   => __( 'User Email', 'wp-grid-builder' ),
				'fields'  => [
					'compare' => [
						'options' => array_merge(
							$is_operators,
							$contains_operators
						),
					],
				],
			],
			[
				'context' => [ 'card' ],
				'value'   => 'user_url',
				'label'   => __( 'User Website', 'wp-grid-builder' ),
				'fields'  => [
					'compare' => [
						'options' => array_merge(
							$is_operators,
							$contains_operators
						),
					],
				],
			],
			[
				'context' => [ 'card' ],
				'value'   => 'user_roles',
				'label'   => __( 'User Roles', 'wp-grid-builder' ),
				'fields'  => [
					'compare' => [
						'options' => $includes_operators,
					],
					'value'   => [
						'type'         => 'select',
						'options'      => Helpers::format_options( Helpers::get_user_roles() ),
						'multiple'     => true,
						'isSearchable' => true,
					],
				],
			],
			[
				'context' => [ 'card' ],
				'value'   => 'user_description',
				'label'   => __( 'User Description', 'wp-grid-builder' ),
				'fields'  => [
					'compare' => [
						'options' => array_merge(
							$is_operators,
							$contains_operators
						),
					],
				],
			],
			[
				'context' => [ 'card' ],
				'value'   => 'user_post_count',
				'label'   => __( 'User Posts Count', 'wp-grid-builder' ),
				'fields'  => [
					'compare' => [
						'options' => $logic_operators,
					],
					'value'   => [
						'type'        => 'number',
						'min'         => 0,
						'max'         => 9999999999999999,
						'step'        => 1,
						'placeholder' => 0,
					],
				],
			],
			[
				'context' => [ 'card' ],
				'value'   => 'user_metadata',
				'label'   => __( 'User Custom Field', 'wp-grid-builder' ),
				'fields'  => [
					'field'   => [
						'type'        => 'select',
						'label'       => __( 'Field', 'wp-grid-builder' ),
						'placeholder' => __( 'Select a field', 'wp-grid-builder' ),
						'async'       => [
							'wpgb/v2/metadata?object=registered',
							'wpgb/v2/metadata?object=post',
							'wpgb/v2/metadata?object=term',
							'wpgb/v2/metadata?object=user',
						],
					],
					'compare' => [
						'options' => array_merge(
							$logic_operators,
							$contains_operators
						),
					],
				],
			],
		],
	],
	'current_user' => [
		'label'   => __( 'Current User', 'wp-grid-builder' ),
		'options' => [
			[
				'context' => [ 'card', 'facet' ],
				'value'   => 'current_user_id',
				'label'   => __( 'Current User ID', 'wp-grid-builder' ),
				'fields'  => [
					'compare' => [
						'options' => $logic_operators,
					],
					'value'   => [
						'type'        => 'number',
						'min'         => 0,
						'max'         => 9999999999999999,
						'step'        => 1,
						'placeholder' => 0,
					],
				],
			],
			[
				'context' => [ 'card', 'facet' ],
				'value'   => 'current_user_logged_in',
				'label'   => __( 'Current User Login', 'wp-grid-builder' ),
				'fields'  => [
					'compare' => [
						'options' => $is_operators,
					],
					'value'   => [
						'type'    => 'select',
						'options' => [
							[
								'value' => 1,
								'label' => __( 'Logged In', 'wp-grid-builder' ),
							],
							[
								'value' => 0,
								'label' => __( 'Logged Out', 'wp-grid-builder' ),
							],
						],
					],
				],
			],
			[
				'context' => [ 'card', 'facet' ],
				'value'   => 'current_user_registered',
				'label'   => __( 'Current User Registered', 'wp-grid-builder' ),
				'fields'  => [
					'compare' => [
						'options' => $logic_operators,
					],
					'value'   => [
						'type' => 'date',
					],
				],
			],
			[
				'context' => [ 'card', 'facet' ],
				'value'   => 'current_user_login',
				'label'   => __( 'Current Username', 'wp-grid-builder' ),
				'fields'  => [
					'compare' => [
						'options' => array_merge(
							$is_operators,
							$contains_operators
						),
					],
				],
			],
			[
				'context' => [ 'card', 'facet' ],
				'value'   => 'current_user_display_name',
				'label'   => __( 'Current User Display Name', 'wp-grid-builder' ),
				'fields'  => [
					'compare' => [
						'options' => array_merge(
							$is_operators,
							$contains_operators
						),
					],
				],
			],
			[
				'context' => [ 'card', 'facet' ],
				'value'   => 'current_user_first_name',
				'label'   => __( 'Current User First Name', 'wp-grid-builder' ),
				'fields'  => [
					'compare' => [
						'options' => array_merge(
							$is_operators,
							$contains_operators
						),
					],
				],
			],
			[
				'context' => [ 'card', 'facet' ],
				'value'   => 'current_user_last_name',
				'label'   => __( 'Current User Last Name', 'wp-grid-builder' ),
				'fields'  => [
					'compare' => [
						'options' => array_merge(
							$is_operators,
							$contains_operators
						),
					],
				],
			],
			[
				'context' => [ 'card', 'facet' ],
				'value'   => 'current_user_email',
				'label'   => __( 'Current User Email', 'wp-grid-builder' ),
				'fields'  => [
					'compare' => [
						'options' => array_merge(
							$is_operators,
							$contains_operators
						),
					],
				],
			],
			[
				'context' => [ 'card', 'facet' ],
				'value'   => 'current_user_url',
				'label'   => __( 'Current User Website', 'wp-grid-builder' ),
				'fields'  => [
					'compare' => [
						'options' => array_merge(
							$is_operators,
							$contains_operators
						),
					],
				],
			],
			[
				'context' => [ 'card', 'facet' ],
				'value'   => 'current_user_roles',
				'label'   => __( 'Current User Roles', 'wp-grid-builder' ),
				'fields'  => [
					'compare' => [
						'options' => $includes_operators,
					],
					'value'   => [
						'type'         => 'select',
						'options'      => Helpers::format_options( Helpers::get_user_roles() ),
						'multiple'     => true,
						'isSearchable' => true,
					],
				],
			],
		],
	],
	'grid'         => [
		'label'   => __( 'Content', 'wp-grid-builder' ),
		'options' => [
			[
				'context' => [ 'card', 'facet' ],
				'value'   => 'content_id',
				'label'   => __( 'Content / Grid', 'wp-grid-builder' ),
				'fields'  => [
					'compare' => [
						'options' => $includes_operators,
					],
					'value'   => [
						'type'        => 'select',
						'multiple'    => true,
						'isCreatable' => true,
						'async'       => [
							'wpgb/v2/objects?object=grids&fields=id,name',
							'/wpgb/v2/templates',
						],
					],
				],
			],
			[
				'context' => [ 'card', 'facet' ],
				'value'   => 'content_state',
				'label'   => __( 'Content / Grid State', 'wp-grid-builder' ),
				'fields'  => [
					'compare' => [
						'options' => $is_operators,
					],
					'value'   => [
						'type'    => 'select',
						'options' => [
							[
								'value' => 1,
								'label' => __( 'Filtered', 'wp-grid-builder' ),
							],
							[
								'value' => 0,
								'label' => __( 'Not Filtered', 'wp-grid-builder' ),
							],
						],
					],
				],
			],
		],
	],
	'facet'        => [
		'label'   => __( 'Facet', 'wp-grid-builder' ),
		'options' => [
			[
				'context' => [ 'card', 'facet' ],
				'value'   => 'facet_state',
				'label'   => __( 'Facet State', 'wp-grid-builder' ),
				'fields'  => [
					'compare' => [
						'options' => $is_operators,
					],
					'field'   => [
						'type'        => 'select',
						'label'       => __( 'facet', 'wp-grid-builder' ),
						'placeholder' => __( 'Select a field', 'wp-grid-builder' ),
						'async'       => 'wpgb/v2/objects?object=facets&fields=id,name',
					],
					'value'   => [
						'type'    => 'select',
						'options' => [
							[
								'value' => 1,
								'label' => __( 'Selected', 'wp-grid-builder' ),
							],
							[
								'value' => 0,
								'label' => __( 'Not Selected', 'wp-grid-builder' ),
							],
						],
					],
				],
			],
			[
				'context' => [ 'card', 'facet' ],
				'value'   => 'facet_value',
				'label'   => __( 'Facet Value', 'wp-grid-builder' ),
				'fields'  => [
					'compare' => [
						'options' => array_merge(
							$includes_operators,
							$contains_operators
						),
					],
					'field'   => [
						'type'        => 'select',
						'label'       => __( 'facet', 'wp-grid-builder' ),
						'placeholder' => __( 'Select a field', 'wp-grid-builder' ),
						'async'       => 'wpgb/v2/objects?object=facets&fields=id,name',
					],
					'value'   => [
						'type'             => 'select',
						'noOptionsMessage' => __( 'Type a value and press enter.', 'wp-grid-builder' ),
						'multiple'         => true,
						'isCreatable'      => true,
						'isSearchable'     => true,
					],
				],
			],
			[
				'context' => [ 'card', 'facet' ],
				'value'   => 'facet_result_count',
				'label'   => __( 'Facet Result Count', 'wp-grid-builder' ),
				'fields'  => [
					'compare' => [
						'options' => $logic_operators,
					],
					'value'   => [
						'type'        => 'number',
						'min'         => 0,
						'max'         => 9999999999999999,
						'step'        => 1,
						'placeholder' => 0,
					],
				],
			],
		],
	],
	'date'         => [
		'label'   => __( 'Date & Time', 'wp-grid-builder' ),
		'options' => [
			[
				'context' => [ 'card', 'facet' ],
				'value'   => 'date_weekday',
				'label'   => __( 'Weekday', 'wp-grid-builder' ),
				'fields'  => [
					'compare' => [
						'options' => $logic_operators,
					],
					'value'   => [
						'type'    => 'select',
						'options' => Helpers::format_options( array_values( $wp_locale->weekday ) ),
					],
				],
			],
			[
				'context' => [ 'card', 'facet' ],
				'value'   => 'date_date',
				'label'   => __( 'Date', 'wp-grid-builder' ),
				'fields'  => [
					'compare' => [
						'options' => $logic_operators,
					],
					'value'   => [
						'type' => 'date',
					],
				],
			],
			[
				'context' => [ 'card', 'facet' ],
				'value'   => 'date_time',
				'label'   => __( 'Time', 'wp-grid-builder' ),
				'fields'  => [
					'compare' => [
						'options' => $logic_operators,
					],
					'value'   => [
						'type'   => 'date',
						'format' => 'time',
					],
				],
			],
			[
				'context' => [ 'card', 'facet' ],
				'value'   => 'date_datetime',
				'label'   => __( 'Datetime', 'wp-grid-builder' ),
				'fields'  => [
					'compare' => [
						'options' => $logic_operators,
					],
					'value'   => [
						'type'   => 'date',
						'format' => 'datetime',
					],
				],
			],
		],
	],
	'url'          => [
		'label'   => __( 'URL', 'wp-grid-builder' ),
		'options' => [
			[
				'context' => [ 'card', 'facet' ],
				'value'   => 'url_current',
				'label'   => __( 'Current URL', 'wp-grid-builder' ),
				'fields'  => [
					'compare' => [
						'options' => array_merge(
							$is_operators,
							$contains_operators
						),
					],
				],
			],
			[
				'context' => [ 'card', 'facet' ],
				'value'   => 'url_referer',
				'label'   => __( 'Referer URL', 'wp-grid-builder' ),
				'fields'  => [
					'compare' => [
						'options' => array_merge(
							$is_operators,
							$contains_operators
						),
					],
				],
			],
		],
	],
	'device'       => [
		'label'   => __( 'Device', 'wp-grid-builder' ),
		'options' => [
			[
				'context' => [ 'card', 'facet' ],
				'value'   => 'device_browser',
				'label'   => __( 'Browser Type', 'wp-grid-builder' ),
				'fields'  => [
					'compare' => [
						'options' => $includes_operators,
					],
					'value'   => [
						'type'     => 'select',
						'multiple' => true,
						'options'  => [
							[
								'value' => 'chrome',
								'label' => 'Chrome',
							],
							[
								'value' => 'firefox',
								'label' => 'Firefox',
							],
							[
								'value' => 'safari',
								'label' => 'Safari',
							],
							[
								'value' => 'opera',
								'label' => 'Opera',
							],
							[
								'value' => 'edge',
								'label' => 'Edge',
							],
							[
								'value' => 'msie',
								'label' => 'Internet Explorer',
							],
						],
					],
				],
			],
			[
				'context' => [ 'card', 'facet' ],
				'value'   => 'device_os',
				'label'   => __( 'Operating System', 'wp-grid-builder' ),
				'fields'  => [
					'compare' => [
						'options' => $includes_operators,
					],
					'value'   => [
						'type'         => 'select',
						'multiple'     => true,
						'isSearchable' => true,
						'options'      => [
							[
								'value' => 'win',
								'label' => 'Windows',
							],
							[
								'value' => 'mac',
								'label' => 'macOS',
							],
							[
								'value' => 'linux',
								'label' => 'Linux',
							],
							[
								'value' => 'ubuntu',
								'label' => 'Ubuntu',
							],
							[
								'value' => 'iphone',
								'label' => 'iPhone',
							],
							[
								'value' => 'ipad',
								'label' => 'iPad',
							],
							[
								'value' => 'android',
								'label' => 'Android',
							],
							[
								'value' => 'blackberry',
								'label' => 'Blackberry',
							],
							[
								'value' => 'webos',
								'label' => 'Mobile (webOS)',
							],
						],
					],
				],
			],
		],
	],
];
