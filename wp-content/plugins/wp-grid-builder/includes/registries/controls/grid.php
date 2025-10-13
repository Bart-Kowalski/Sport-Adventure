<?php
/**
 * Grid controls
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

$post_formats = Helpers::format_options( get_post_format_strings() );
$post_status  = Helpers::format_options( Helpers::get_post_status() );
$post_types   = Helpers::format_options( Helpers::get_post_types() );
$taxonomies   = Helpers::format_options( Helpers::get_taxonomies_list() );
$user_roles   = Helpers::format_options( Helpers::get_user_roles() );
$image_sizes  = Helpers::format_options( Helpers::get_image_sizes() );
$animations   = apply_filters( 'wp_grid_builder/animations', [] );
$animations   = Helpers::format_options( array_combine( array_keys( $animations ), array_column( $animations, 'name' ) ) );
$loaders      = apply_filters( 'wp_grid_builder/loaders', [] );
$loaders      = Helpers::format_options( array_combine( array_keys( $loaders ), array_column( $loaders, 'name' ) ) );

$query_content = [
	'type'        => 'fieldset',
	'panel'       => 'query',
	'legend'      => __( 'Content', 'wp-grid-builder' ),
	'description' => __( 'Define the type of content to be queried and displayed in the grid.', 'wp-grid-builder' ),
	'fields'      => [
		'source' => [
			'type'    => 'button',
			'options' => [
				[
					'value'  => 'post_type',
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
		'grid'   => [
			'type'   => 'grid',
			'fields' => [
				'posts_per_page' => [
					'type'  => 'number',
					'label' => __( 'Per Page', 'wp-grid-builder' ),
					'info'  => __( '"-1" to show all. "0" corresponds to the default number of posts per page set in WordPress Settings.', 'wp-grid-builder' ),
					'min'   => -1,
					'max'   => 100,
					'step'  => 1,
				],
				'offset'         => [
					'type'  => 'number',
					'label' => __( 'Offset', 'wp-grid-builder' ),
					'info'  => __( 'The "offset" parameter is ignored when the number per page is set to "-1".', 'wp-grid-builder' ),
					'min'   => 0,
					'max'   => 999,
					'step'  => 1,
				],
			],
		],
	],
];

$query_order = [
	'type'   => 'fieldset',
	'panel'  => 'query',
	'legend' => __( 'Order', 'wp-grid-builder' ),
	'fields' => [
		'post_order' => [
			'type'      => 'repeater',
			'addLabel'  => __( 'Add Order', 'wp-grid-builder' ),
			'rowLabel'  => '#%d - {{ orderby || ' . __( 'Post Date (default)', 'wp-grid-builder' ) . ' }} - {{ order || ' . __( 'DESC', 'wp-grid-builder' ) . ' }}',
			'minRows'   => 1,
			'maxRows'   => 10,
			'fields'    => [
				'grid'     => [
					'type'   => 'grid',
					'fields' => [
						'orderby' => [
							'type'         => 'select',
							'label'        => __( 'Order By', 'wp-grid-builder' ),
							'placeholder'  => __( 'Post Date (default)', 'wp-grid-builder' ),
							'isSearchable' => true,
							'isClearable'  => true,
							'options'      => [
								[
									'value' => 'none',
									'label' => __( 'None', 'wp-grid-builder' ),
								],
								[
									'value' => 'ID',
									'label' => __( 'Post ID', 'wp-grid-builder' ),
								],
								[
									'value' => 'parent',
									'label' => __( 'Post parent ID', 'wp-grid-builder' ),
								],
								[
									'value' => 'title',
									'label' => __( 'Post Title', 'wp-grid-builder' ),
								],
								[
									'value' => 'name',
									'label' => __( 'Post Name', 'wp-grid-builder' ),
								],
								[
									'value' => 'author',
									'label' => __( 'Post Author', 'wp-grid-builder' ),
								],
								[
									'value' => 'date',
									'label' => __( 'Post Date', 'wp-grid-builder' ),
								],
								[
									'value' => 'modified',
									'label' => __( 'Post Modified Date', 'wp-grid-builder' ),
								],
								[
									'value' => 'rand',
									'label' => __( 'Random Order', 'wp-grid-builder' ),
								],
								[
									'value' => 'menu_order',
									'label' => __( 'Menu Order', 'wp-grid-builder' ),
								],
								[
									'value' => 'comment_count',
									'label' => __( 'Post Comments Count', 'wp-grid-builder' ),
								],
								[
									'value' => 'post__in',
									'label' => __( 'Included Posts', 'wp-grid-builder' ),
								],
								[
									'value' => 'meta_value',
									'label' => __( 'Custom Field', 'wp-grid-builder' ),
								],
								[
									'value' => 'meta_value_num',
									'label' => __( 'Numeric Custom Field', 'wp-grid-builder' ),
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
				'meta_key' => [
					'type'        => 'text',
					'label'       => __( 'Custom Field', 'wp-grid-builder' ),
					'placeholder' => __( 'Enter a field name', 'wp-grid-builder' ),
					'condition'   => [
						[
							'field'   => 'orderby',
							'compare' => 'CONTAINS',
							'value'   => 'meta_value',
						],
					],
				],
			],
			'condition' => [
				[
					'field'   => 'source',
					'compare' => '===',
					'value'   => 'post_type',
				],
			],
		],
		'term_order' => [
			'type'      => 'grid',
			'fields'    => [
				'term_orderby' => [
					'type'         => 'select',
					'label'        => __( 'Order By', 'wp-grid-builder' ),
					'placeholder'  => __( 'Term Name (default)', 'wp-grid-builder' ),
					'multiple'     => true,
					'isSortable'   => true,
					'isSearchable' => true,
					'options'      => [
						[
							'value' => 'none',
							'label' => __( 'None', 'wp-grid-builder' ),
						],
						[
							'value' => 'term_id',
							'label' => __( 'Term ID', 'wp-grid-builder' ),
						],
						[
							'value' => 'name',
							'label' => __( 'Term Name', 'wp-grid-builder' ),
						],
						[
							'value' => 'slug',
							'label' => __( 'Term Slug', 'wp-grid-builder' ),
						],
						[
							'value' => 'description',
							'label' => __( 'Term Description', 'wp-grid-builder' ),
						],
						[
							'value' => 'parent',
							'label' => __( 'Term Parent', 'wp-grid-builder' ),
						],
						[
							'value' => 'term_order',
							'label' => __( 'Term Order', 'wp-grid-builder' ),
						],
						[
							'value' => 'term_group',
							'label' => __( 'Term Group', 'wp-grid-builder' ),
						],
						[
							'value' => 'count',
							'label' => __( 'Term Posts Count', 'wp-grid-builder' ),
						],
						[
							'value' => 'include',
							'label' => __( 'Included Terms', 'wp-grid-builder' ),
						],
						[
							'value' => 'meta_value',
							'label' => __( 'Custom Field', 'wp-grid-builder' ),
						],
						[
							'value' => 'meta_value_num',
							'label' => __( 'Numeric Custom Field', 'wp-grid-builder' ),
						],
					],
				],
				'order'        => [
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
			'condition' => [
				[
					'field'   => 'source',
					'compare' => '===',
					'value'   => 'term',
				],
			],
		],
		'meta_key'   => [
			'type'        => 'text',
			'label'       => __( 'Custom Field', 'wp-grid-builder' ),
			'placeholder' => __( 'Enter a field name', 'wp-grid-builder' ),
			'condition'   => [
				[
					'field'   => 'source',
					'compare' => '===',
					'value'   => 'term',
				],
				[
					'field'   => 'term_orderby',
					'compare' => 'IN',
					'value'   => [ 'meta_value', 'meta_value_num' ],
				],
			],
		],
		'user_order' => [
			'type'      => 'repeater',
			'addLabel'  => __( 'Add Order', 'wp-grid-builder' ),
			'rowLabel'  => '#%d - {{ orderby || ' . __( 'User Login (default)', 'wp-grid-builder' ) . ' }} - {{ order || ' . __( 'DESC', 'wp-grid-builder' ) . ' }}',
			'minRows'   => 1,
			'maxRows'   => 10,
			'fields'    => [
				'grid'     => [
					'type'   => 'grid',
					'fields' => [
						'orderby' => [
							'type'         => 'select',
							'label'        => __( 'Order By', 'wp-grid-builder' ),
							'placeholder'  => __( 'User Login (default)', 'wp-grid-builder' ),
							'isSearchable' => true,
							'isClearable'  => true,
							'options'      => [
								[
									'value' => 'none',
									'label' => __( 'None', 'wp-grid-builder' ),
								],
								[
									'value' => 'ID',
									'label' => __( 'User ID', 'wp-grid-builder' ),
								],
								[
									'value' => 'display_name',
									'label' => __( 'User Display Name', 'wp-grid-builder' ),
								],
								[
									'value' => 'user_name',
									'label' => __( 'User Name', 'wp-grid-builder' ),
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
								[
									'value' => 'user_registered',
									'label' => __( 'User Registered Date', 'wp-grid-builder' ),
								],
								[
									'value' => 'post_count',
									'label' => __( 'User Posts Count', 'wp-grid-builder' ),
								],
								[
									'value' => 'include',
									'label' => __( 'Included Users', 'wp-grid-builder' ),
								],
								[
									'value' => 'meta_value',
									'label' => __( 'Custom Field', 'wp-grid-builder' ),
								],
								[
									'value' => 'meta_value_num',
									'label' => __( 'Numeric Custom Field', 'wp-grid-builder' ),
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
				'meta_key' => [
					'type'        => 'text',
					'label'       => __( 'Custom Field', 'wp-grid-builder' ),
					'placeholder' => __( 'Enter a field name', 'wp-grid-builder' ),
					'condition'   => [
						[
							'field'   => 'orderby',
							'compare' => 'CONTAINS',
							'value'   => 'meta_value',
						],
					],
				],
			],
			'condition' => [
				[
					'field'   => 'source',
					'compare' => '===',
					'value'   => 'user',
				],
			],
		],
	],
];

$query_posts = [
	'type'      => 'fieldset',
	'panel'     => 'query',
	'legend'    => __( 'Posts', 'wp-grid-builder' ),
	'fields'    => [
		'post_type'           => [
			'type'         => 'select',
			'label'        => __( 'Post Types', 'wp-grid-builder' ),
			'placeholder'  => _x( 'Any', 'Post Types default value', 'wp-grid-builder' ),
			'options'      => $post_types,
			'multiple'     => true,
			'isSearchable' => true,
		],
		'post_status'         => [
			'type'         => 'select',
			'label'        => _x( 'Post Status', 'plural', 'wp-grid-builder' ),
			'placeholder'  => __( 'Default', 'wp-grid-builder' ),
			'info'         => __( 'Default value is "published", but if the user is logged in, "private" is added (according to WordPress).', 'wp-grid-builder' ),
			'options'      => $post_status,
			'multiple'     => true,
			'isSearchable' => true,
		],
		'author__in'          => [
			'type'        => 'select',
			'label'       => __( 'Post Authors', 'wp-grid-builder' ),
			'placeholder' => _x( 'Any', 'Post Authors default value', 'wp-grid-builder' ),
			'async'       => 'wpgb/v2/users',
			'multiple'    => true,
		],
		'post__in'            => [
			'type'        => 'select',
			'label'       => __( 'Include Posts', 'wp-grid-builder' ),
			'placeholder' => _x( 'None', 'Include Posts default value', 'wp-grid-builder' ),
			'async'       => 'wpgb/v2/posts',
			'multiple'    => true,
			'condition'   => [
				[
					'field'   => 'post__not_in',
					'compare' => 'EMPTY',
				],
			],
		],
		'post__not_in'        => [
			'type'        => 'select',
			'label'       => __( 'Exclude Posts', 'wp-grid-builder' ),
			'placeholder' => _x( 'None', 'Exclude Posts default value', 'wp-grid-builder' ),
			'async'       => 'wpgb/v2/posts',
			'multiple'    => true,
			'condition'   => [
				[
					'field'   => 'post__in',
					'compare' => 'EMPTY',
				],
			],
		],
		'post_mime_type'      => [
			'type'        => 'select',
			'label'       => __( 'Mime Types', 'wp-grid-builder' ),
			'placeholder' => _x( 'Any', 'Mime Types default value', 'wp-grid-builder' ),
			'multiple'    => true,
			'options'     => [
				[
					'value' => 'image',
					'label' => __( 'Image', 'wp-grid-builder' ),
				],
				[
					'value' => 'video',
					'label' => __( 'Video', 'wp-grid-builder' ),
				],
				[
					'value' => 'audio',
					'label' => __( 'Audio', 'wp-grid-builder' ),
				],
				[
					'value' => 'text',
					'label' => __( 'Text', 'wp-grid-builder' ),
				],
				[
					'value' => 'application',
					'label' => __( 'Applications', 'wp-grid-builder' ),
				],
			],
			'condition'   => [
				[
					'field'   => 'post_type',
					'compare' => '==',
					'value'   => 'attachment',
				],
				[
					'field'   => 'attachment_ids',
					'compare' => 'EMPTY',
				],
			],
		],
		'attachment_ids'      => [
			'type'      => 'gallery',
			'label'     => __( 'Include Media', 'wp-grid-builder' ),
			'info'      => __( 'Drag & drop media to create a custom order (only works if you have "Media" as unique post type). If no media are added, all media from your library will be queried.', 'wp-grid-builder' ),
			'condition' => [
				[
					'field'   => 'post_type',
					'compare' => 'CONTAINS',
					'value'   => 'attachment',
				],
			],
		],
		'ignore_sticky_posts' => [
			'type'  => 'toggle',
			'label' => __( 'Ignore Sticky Posts', 'wp-grid-builder' ),
		],
	],
	'condition' => [
		[
			'field'   => 'source',
			'compare' => '===',
			'value'   => 'post_type',
		],
	],
];

$query_terms = [
	'type'      => 'fieldset',
	'panel'     => 'query',
	'legend'    => __( 'Terms', 'wp-grid-builder' ),
	'fields'    => [
		'taxonomy'     => [
			'type'         => 'select',
			'label'        => __( 'Taxonomies', 'wp-grid-builder' ),
			'placeholder'  => _x( 'Any', 'Taxonomies default value', 'wp-grid-builder' ),
			'info'         => __( 'Taxonomies, to which results should be limited.', 'wp-grid-builder' ),
			'options'      => $taxonomies,
			'multiple'     => true,
			'isSearchable' => true,
		],
		'term__in'     => [
			'type'        => 'select',
			'label'       => __( 'Include Terms', 'wp-grid-builder' ),
			'placeholder' => _x( 'None', 'Include Terms default value', 'wp-grid-builder' ),
			'async'       => 'wpgb/v2/terms',
			'multiple'    => true,
			'condition'   => [
				[
					'field'   => 'term__not_in',
					'compare' => 'EMPTY',
				],
			],
		],
		'term__not_in' => [
			'type'        => 'select',
			'label'       => __( 'Exclude Terms', 'wp-grid-builder' ),
			'placeholder' => _x( 'None', 'Exclude Terms default value', 'wp-grid-builder' ),
			'async'       => 'wpgb/v2/terms',
			'multiple'    => true,
			'condition'   => [
				[
					'field'   => 'term__in',
					'compare' => 'EMPTY',
				],
			],
		],
		'hide_empty'   => [
			'type'  => 'toggle',
			'label' => __( 'Hide Empty Terms', 'wp-grid-builder' ),
			'help'  => __( 'Whether to hide terms not assigned to any posts.', 'wp-grid-builder' ),
		],
		'childless'    => [
			'type'  => 'toggle',
			'label' => __( 'Childless Terms', 'wp-grid-builder' ),
			'help'  => __( 'Limit results to terms that have no children.', 'wp-grid-builder' ),
		],
	],
	'condition' => [
		[
			'field'   => 'source',
			'compare' => '===',
			'value'   => 'term',
		],
	],
];

$query_users = [
	'type'      => 'fieldset',
	'panel'     => 'query',
	'legend'    => __( 'Users', 'wp-grid-builder' ),
	'fields'    => [
		'role'                => [
			'type'         => 'select',
			'label'        => __( 'Users Roles', 'wp-grid-builder' ),
			'placeholder'  => _x( 'Any', 'Users Roles default value', 'wp-grid-builder' ),
			'info'         => __( 'Roles that users must match. Users must match each selected role.', 'wp-grid-builder' ),
			'options'      => $user_roles,
			'multiple'     => true,
			'isSearchable' => true,
		],
		'role__in'            => [
			'type'         => 'select',
			'label'        => __( 'Include Roles', 'wp-grid-builder' ),
			'placeholder'  => _x( 'None', 'Include Roles default value', 'wp-grid-builder' ),
			'options'      => $user_roles,
			'multiple'     => true,
			'isSearchable' => true,
		],
		'role__not_in'        => [
			'type'         => 'select',
			'label'        => __( 'Exclude Roles', 'wp-grid-builder' ),
			'placeholder'  => _x( 'None', 'Exclude Roles default value', 'wp-grid-builder' ),
			'options'      => $user_roles,
			'multiple'     => true,
			'isSearchable' => true,
		],
		'user__in'            => [
			'type'        => 'select',
			'label'       => __( 'Include Users', 'wp-grid-builder' ),
			'placeholder' => _x( 'None', 'Include Users default value', 'wp-grid-builder' ),
			'async'       => 'wpgb/v2/users',
			'multiple'    => true,
			'condition'   => [
				[
					'field'   => 'user__not_in',
					'compare' => 'EMPTY',
				],
			],
		],
		'user__not_in'        => [
			'type'        => 'select',
			'label'       => __( 'Exclude Users', 'wp-grid-builder' ),
			'placeholder' => _x( 'None', 'Exclude Users default value', 'wp-grid-builder' ),
			'async'       => 'wpgb/v2/users',
			'multiple'    => true,
			'condition'   => [
				[
					'field'   => 'user__in',
					'compare' => 'EMPTY',
				],
			],
		],
		'has_published_posts' => [
			'type'         => 'select',
			'label'        => __( 'Has Published Posts', 'wp-grid-builder' ),
			'placeholder'  => __( 'All post types', 'wp-grid-builder' ),
			'info'         => __( 'Filter results to users who have published posts in the selected post types.', 'wp-grid-builder' ),
			'options'      => $post_types,
			'multiple'     => true,
			'isSearchable' => true,
		],
	],
	'condition' => [
		[
			'field'   => 'source',
			'compare' => '===',
			'value'   => 'user',
		],
	],
];

$query_advanced = [
	'type'          => 'details',
	'panel'         => 'query',
	'summaryOpened' => __( 'Hide advanced settings', 'wp-grid-builder' ),
	'summaryClosed' => __( 'Show advanced settings', 'wp-grid-builder' ),
	'fields'        => [
		'taxonomies'    => [
			'type'        => 'fieldset',
			'legend'      => __( 'Taxonomies', 'wp-grid-builder' ),
			'description' => __( 'Show content associated with certain taxonomies.', 'wp-grid-builder' ),
			'fields'      => [
				'tax_query_clauses' => [
					'type'     => 'clause',
					'rowLabel' => sprintf(
						/* translators: 1: item number, 2: Include or Exclude, 3: list of taxonomy terms. */
						__( '#%1$s - %2$s: %3$s', 'wp-grid-builder' ),
						'%d',
						'{{ operator || ' . __( 'Include', 'wp-grid-builder' ) . ' }}',
						'{{ terms || ' . _x( 'Unset', 'Taxonomy terms default value', 'wp-grid-builder' ) . ' }}'
					),
					'fields'   => [
						'terms'            => [
							'type'        => 'select',
							'label'       => __( 'Taxonomy Terms', 'wp-grid-builder' ),
							'placeholder' => _x( 'Unset', 'Taxonomy terms default value', 'wp-grid-builder' ),
							'async'       => 'wpgb/v2/terms?field=term_taxonomy_id',
							'multiple'    => true,
						],
						'operator'         => [
							'type'    => 'button',
							'label'   => __( 'Operator', 'wp-grid-builder' ),
							'options' => [
								[
									'value' => 'IN',
									'label' => __( 'Include', 'wp-grid-builder' ),
								],
								[
									'value' => 'NOT IN',
									'label' => __( 'Exclude', 'wp-grid-builder' ),
								],
							],
						],
						'include_children' => [
							'type'  => 'toggle',
							'label' => __( 'Child Terms', 'wp-grid-builder' ),
							'help'  => __( 'Include children for hierarchical taxonomies.', 'wp-grid-builder' ),
						],
					],
				],
			],
			'condition'   => [
				[
					'field'   => 'source',
					'compare' => '===',
					'value'   => 'post_type',
				],
			],
		],
		'custom_fields' => [
			'type'        => 'fieldset',
			'legend'      => __( 'Custom Fields', 'wp-grid-builder' ),
			'description' => __( 'Show content associated with certain custom fields.', 'wp-grid-builder' ),
			'fields'      => [
				'meta_query_clauses' => [
					'type'     => 'clause',
					'rowLabel' => sprintf(
						/* translators: 1: item number, 2: key name, 3: compare operator, 4: compare value, 5: value type. */
						__( '#%1$s - If %2$s %3$s %4$s (%5$s)', 'wp-grid-builder' ),
						'%d',
						'{{ key || ' . __( 'Field Key', 'wp-grid-builder' ) . ' }}',
						'{{ compare }}',
						'{{ value }}',
						'{{ type }}'
					),
					'fields'   => [
						'grid' => [
							'type'   => 'grid',
							'fields' => [
								'key'     => [
									'type'  => 'text',
									'label' => __( 'Field Key', 'wp-grid-builder' ),
								],
								'type'    => [
									'type'         => 'select',
									'label'        => __( 'Field Type', 'wp-grid-builder' ),
									'defaultValue' => 'CHAR',
									'isClearable'  => false,
									'options'      => [
										[
											'value' => 'CHAR',
											'label' => __( 'Character', 'wp-grid-builder' ),
										],
										[
											'value' => 'NUMERIC',
											'label' => __( 'Numeric', 'wp-grid-builder' ),
										],
										[
											'value' => 'BINARY',
											'label' => __( 'Binary', 'wp-grid-builder' ),
										],
										[
											'value' => 'DATE',
											'label' => __( 'Date', 'wp-grid-builder' ),
										],
										[
											'value' => 'DATETIME',
											'label' => __( 'Date Time', 'wp-grid-builder' ),
										],
										[
											'value' => 'TIME',
											'label' => __( 'Time', 'wp-grid-builder' ),
										],
										[
											'value' => 'DECIMAL',
											'label' => __( 'Decimal', 'wp-grid-builder' ),
										],
										[
											'value' => 'SIGNED',
											'label' => __( 'Signed', 'wp-grid-builder' ),
										],
										[
											'value' => 'UNSIGNED',
											'label' => __( 'Unsigned', 'wp-grid-builder' ),
										],
									],
								],
								'compare' => [
									'type'         => 'select',
									'label'        => __( 'Compare With', 'wp-grid-builder' ),
									'defaultValue' => '=',
									'isClearable'  => false,
									'options'      => [
										[
											'value' => '=',
											'label' => '=',
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
											'value' => '>=',
											'label' => '>=',
										],
										[
											'value' => '<',
											'label' => '<',
										],
										[
											'value' => '<=',
											'label' => '<=',
										],
										[
											'value' => 'LIKE',
											'label' => __( 'Like', 'wp-grid-builder' ),
										],
										[
											'value' => 'NOT LIKE',
											'label' => __( 'Not Like', 'wp-grid-builder' ),
										],
										[
											'value' => 'IN',
											'label' => __( 'In', 'wp-grid-builder' ),
										],
										[
											'value' => 'NOT IN',
											'label' => __( 'Not In', 'wp-grid-builder' ),
										],
										[
											'value' => 'BETWEEN',
											'label' => __( 'Between', 'wp-grid-builder' ),
										],
										[
											'value' => 'NOT BETWEEN',
											'label' => __( 'Not Between', 'wp-grid-builder' ),
										],
										[
											'value' => 'EXISTS',
											'label' => __( 'Exists', 'wp-grid-builder' ),
										],
										[
											'value' => 'NOT EXISTS',
											'label' => __( 'Not Exists', 'wp-grid-builder' ),
										],
									],
								],
								'value'   => [
									'type'  => 'text',
									'label' => __( 'Field Value', 'wp-grid-builder' ),
								],
							],
						],
					],
				],
			],
		],
	],
];

$layout_layout = [
	'type'        => 'fieldset',
	'panel'       => 'layout',
	'legend'      => __( 'Layout', 'wp-grid-builder' ),
	'description' => __( 'Adjust the grid layout and the arrangement of the cards.', 'wp-grid-builder' ),
	'fields'      => [
		'type'         => [
			'type'    => 'button',
			'options' => [
				[
					'value'  => 'masonry',
					'label'  => __( 'Masonry', 'wp-grid-builder' ),
					'icon'   => 'masonry',
					'inline' => true,
				],
				[
					'value'  => 'metro',
					'label'  => __( 'Metro', 'wp-grid-builder' ),
					'icon'   => 'metro',
					'inline' => true,
				],
				[
					'value'  => 'justified',
					'label'  => __( 'Justified', 'wp-grid-builder' ),
					'icon'   => 'justified',
					'inline' => true,
				],
			],
		],
		'cards_notice' => [
			'type'   => 'notice',
			'status' => 'error',
		],
		'full_width'   => [
			'type'  => 'toggle',
			'label' => __( 'Full Width', 'wp-grid-builder' ),
			'help'  => __( 'Fills the entire browser width.', 'wp-grid-builder' ),
		],
	],
];

$layout_behaviour = [
	'type'      => 'fieldset',
	'panel'     => 'layout',
	'legend'    => __( 'Behaviour', 'wp-grid-builder' ),
	'fields'    => [
		'horizontal_order' => [
			'type'      => 'toggle',
			'label'     => __( 'Horizontal Order', 'wp-grid-builder' ),
			'help'      => __( 'Arrange cards from left to right instead of top to bottom.', 'wp-grid-builder' ),
			'condition' => [
				[
					'field'   => 'type',
					'compare' => '===',
					'value'   => 'masonry',
				],
			],
		],
		'fit_rows'         => [
			'type'      => 'toggle',
			'label'     => __( 'Fit into Rows', 'wp-grid-builder' ),
			'help'      => __( 'Arrange cards into rows. Rows progress vertically.', 'wp-grid-builder' ),
			'condition' => [
				[
					'field'   => 'type',
					'compare' => '===',
					'value'   => 'masonry',
				],
			],
		],
		'equal_columns'    => [
			'type'      => 'toggle',
			'label'     => __( 'Equal Height Columns', 'wp-grid-builder' ),
			'help'      => __( 'Equalize column heights in each row.', 'wp-grid-builder' ),
			'condition' => [
				[
					'field'   => 'type',
					'compare' => '===',
					'value'   => 'masonry',
				],
			],
		],
		'equal_rows'       => [
			'type'      => 'toggle',
			'label'     => __( 'Equal Height Rows', 'wp-grid-builder' ),
			'help'      => __( 'Equalize row heights. This option will crop images.', 'wp-grid-builder' ),
			'condition' => [
				[
					'field'   => 'type',
					'compare' => '===',
					'value'   => 'justified',
				],
			],
		],
		'fill_last_row'    => [
			'type'      => 'toggle',
			'label'     => __( 'Fill Last Row', 'wp-grid-builder' ),
			'help'      => __( 'Force the last row to be completely filled. This option will crop images.', 'wp-grid-builder' ),
			'condition' => [
				[
					'field'   => 'type',
					'compare' => '===',
					'value'   => 'justified',
				],
				[
					'field'   => 'carousel',
					'compare' => '!=',
					'value'   => 1,
				],
			],
		],
		'center_last_row'  => [
			'type'      => 'toggle',
			'label'     => __( 'Center Last Row', 'wp-grid-builder' ),
			'help'      => __( 'Center cards in the last row.', 'wp-grid-builder' ),
			'condition' => [
				[
					'field'   => 'type',
					'compare' => '===',
					'value'   => 'justified',
				],
				[
					'field'   => 'carousel',
					'compare' => '!=',
					'value'   => 1,
				],
			],
		],
	],
	'condition' => [
		[
			'field'   => 'type',
			'compare' => '!==',
			'value'   => 'metro',
		],
	],
];

$layout_sizing = [
	'type'   => 'fieldset',
	'panel'  => 'layout',
	'legend' => __( 'Sizing', 'wp-grid-builder' ),
	'fields' => [
		'card_sizes' => [
			'type'     => 'repeater',
			'class'    => 'wpgb-table-card-sizes',
			'addLabel' => __( 'Add Size', 'wp-grid-builder' ),
			'rowLabel' => '#%d',
			'minRows'  => 1,
			'maxRows'  => 20,
			'fields'   => [
				'grid'  => [
					'type'    => 'grid',
					'columns' => 3,
					'fields'  => [
						'browser' => [
							'type'   => 'number',
							'label'  => __( 'Browser', 'wp-grid-builder' ),
							'suffix' => 'px',
							'min'    => 1,
							'max'    => 9999,
							'step'   => 1,
						],
						'columns' => [
							'type'        => 'number',
							'label'       => __( 'Columns', 'wp-grid-builder' ),
							'placeholder' => 1,
							'min'         => 1,
							'max'         => 12,
							'step'        => 1,
							'condition'   => [
								[
									'field'   => 'type',
									'compare' => '!==',
									'value'   => 'justified',
								],
							],
						],
						'height'  => [
							'type'        => 'number',
							'label'       => __( 'Row height', 'wp-grid-builder' ),
							'suffix'      => 'px',
							'placeholder' => 120,
							'min'         => 1,
							'max'         => 1000,
							'step'        => 1,
							'condition'   => [
								[
									'field'   => 'type',
									'compare' => '===',
									'value'   => 'justified',
								],
							],
						],
						'gutter'  => [
							'type'        => 'number',
							'label'       => __( 'Spacing', 'wp-grid-builder' ),
							'info'        => __( 'Set "-1" to inherit the previous spacing value.', 'wp-grid-builder' ),
							'placeholder' => 0,
							'suffix'      => 'px',
							'min'         => -1,
							'max'         => 999,
							'step'        => 1,
						],
					],
				],
				'ratio' => [
					'type'      => 'group',
					'label'     => __( 'Aspect ratio', 'wp-grid-builder' ),
					'fields'    => [
						'row' => [
							'type'   => 'row',
							'fields' => [
								'x' => [
									'type'    => 'number',
									'tooltip' => 'X',
									'min'     => 1,
									'max'     => 999,
									'step'    => 1,
								],
								'y' => [
									'type'    => 'number',
									'tooltip' => 'Y',
									'min'     => 1,
									'max'     => 999,
									'step'    => 1,
								],
							],
						],
					],
					'condition' => [
						[
							'field'   => 'type',
							'compare' => '===',
							'value'   => 'metro',
						],
					],
				],
			],
		],
	],
];

$layout_advanced = [
	'type'          => 'details',
	'panel'         => 'layout',
	'summaryOpened' => __( 'Hide advanced settings', 'wp-grid-builder' ),
	'summaryClosed' => __( 'Show advanced settings', 'wp-grid-builder' ),
	'fields'        => [
		'builder' => [
			'type'        => 'fieldset',
			'legend'      => __( 'Builder', 'wp-grid-builder' ),
			'description' => __( 'Add facets, carousel buttons/dots and styles to the grid layout.', 'wp-grid-builder' ),
			'fields'      => [
				'grid_layout' => [
					'type'   => 'builder',
					'fields' => array_merge(
						[
							'facets'          => [
								'type'           => 'select',
								'label'          => __( 'Facets and Carousel Elements', 'wp-grid-builder' ),
								'placeholder'    => __( 'Type to search', 'wp-grid-builder' ),
								'async'          => 'wpgb/v2/objects?object=facets&orderby=name&order=asc&fields=id,name',
								'multiple'       => true,
								'defaultOptions' => [
									[
										'value' => 'prev-button',
										'label' => __( 'Prev Button (carousel)', 'wp-grid-builder' ),
									],
									[
										'value' => 'next-button',
										'label' => __( 'Next Button (carousel)', 'wp-grid-builder' ),
									],
									[
										'value' => 'page-dots',
										'label' => __( 'Page Dots (carousel)', 'wp-grid-builder' ),
									],
								],
								'condition'      => [
									[
										'field'   => 'carousel',
										'compare' => '==',
										'value'   => 1,
									],
								],
							],
							'facets2'         => [
								'name'        => 'facets',
								'type'        => 'select',
								'label'       => __( 'Facets', 'wp-grid-builder' ),
								'placeholder' => __( 'Type to search', 'wp-grid-builder' ),
								'async'       => 'wpgb/v2/objects?object=facets&orderby=name&order=asc&fields=id,name',
								'multiple'    => true,
								'condition'   => [
									[
										'field'   => 'carousel',
										'compare' => '!=',
										'value'   => 1,
									],
								],
							],
							'padding'         => [
								'type'  => 'padding',
								'label' => _x( 'Padding', 'CSS padding', 'wp-grid-builder' ),
							],
							'margin'          => [
								'type'  => 'margin',
								'label' => _x( 'Margin', 'CSS margin', 'wp-grid-builder' ),
							],
							'background'      => [
								'type'     => 'color',
								'label'    => __( 'Background Color', 'wp-grid-builder' ),
								'gradient' => true,
							],
							'justify-content' => [
								'type'    => 'button',
								'label'   => __( 'Content Alignment', 'wp-grid-builder' ),
								'options' => [
									[
										'value' => 'flex-start',
										'label' => __( 'Left', 'wp-grid-builder' ),
										'icon'  => 'justifyLeft',
									],
									[
										'value' => 'center',
										'label' => __( 'Center', 'wp-grid-builder' ),
										'icon'  => 'justifyCenter',
									],
									[
										'value' => 'flex-end',
										'label' => __( 'Right', 'wp-grid-builder' ),
										'icon'  => 'justifyRight',
									],
									[
										'value' => 'space-between',
										'label' => __( 'Space Between', 'wp-grid-builder' ),
										'icon'  => 'justifySpaceBetween',
									],
								],
							],
						],
						// Fallbacks.
						array_map(
							function() {

								return [
									'type'   => 'number',
									'hidden' => true,
									'units'  => [],
								];
							},
							array_flip( [ 'margin-top', 'margin-right', 'margin-bottom', 'margin-left', 'padding-top', 'padding-right', 'padding-bottom', 'padding-left' ] )
						)
					),
				],
			],
		],
		'tip'     => [
			'type'    => 'tip',
			'content' => __( 'For enhanced flexibility, we strongly recommend utilizing the WordPress editor (Gutenberg) or a dedicated page builder instead of relying solely on the current builder.', 'wp-grid-builder' ),
		],
	],
];

$carousel_carousel = [
	'type'        => 'fieldset',
	'panel'       => 'carousel',
	'legend'      => __( 'Carousel', 'wp-grid-builder' ),
	'description' => __( 'Transform a vertical grid into a carousel (horizontal layout).', 'wp-grid-builder' ),
	'fields'      => [
		'carousel' => [
			'type'  => 'toggle',
			'label' => __( 'Carousel Mode', 'wp-grid-builder' ),
			'help'  => sprintf(
				/* translators: %s: Settings panel url */
				__( 'Carousel buttons/dots can be added in <a href="%s">Layout</a> panel under advanced settings.', 'wp-grid-builder' ),
				'#wpgb-panel=layout'
			),
		],
	],
];

$carousel_behaviour = [
	'type'   => 'fieldset',
	'panel'  => 'carousel',
	'legend' => __( 'Behaviour', 'wp-grid-builder' ),
	'fields' => [
		'grid'    => [
			'type'   => 'grid',
			'fields' => [
				'slide_align'   => [
					'type'    => 'button',
					'label'   => __( 'Slides Alignment', 'wp-grid-builder' ),
					'options' => [
						[
							'value' => 'left',
							'label' => __( 'Left', 'wp-grid-builder' ),
							'icon'  => 'alignLeft',
						],
						[
							'value' => 'center',
							'label' => __( 'Center', 'wp-grid-builder' ),
							'icon'  => 'alignCenter',
						],
						[
							'value' => 'right',
							'label' => __( 'Right', 'wp-grid-builder' ),
							'icon'  => 'alignRight',
						],
					],
				],
				'initial_index' => [
					'type'  => 'number',
					'label' => __( 'Initial Slide', 'wp-grid-builder' ),
					'info'  => __( 'Zero-based index of the initial selected slide in the carousel.', 'wp-grid-builder' ),
					'min'   => 0,
					'max'   => 999,
				],
				'group_cells'   => [
					'type'  => 'number',
					'label' => __( 'Groups Cards', 'wp-grid-builder' ),
					'info'  => __( 'Groups cards together in slides by a number or viewport percent. Flicking, page dots, and previous/next buttons are mapped to group slides.', 'wp-grid-builder' ),
					'units' => [
						'custom' => [
							'min'  => 1,
							'max'  => 100,
							'step' => 1,
						],
						'%'      => [
							'min'  => 1,
							'max'  => 100,
							'step' => 1,
						],
					],
				],
				'rows_number'   => [
					'type'      => 'number',
					'label'     => __( 'Rows Number', 'wp-grid-builder' ),
					'min'       => 1,
					'max'       => 12,
					'step'      => 1,
					'condition' => [
						[
							'field'   => 'type',
							'compare' => '!==',
							'value'   => 'masonry',
						],
					],
				],
				'auto_play'     => [
					'type'   => 'number',
					'label'  => __( 'Auto Play', 'wp-grid-builder' ),
					'info'   => __( 'Auto-playing will pause when mouse is hovered over, and resume when mouse is hovered off.', 'wp-grid-builder' ),
					'suffix' => 'ms',
					'min'    => 0,
					'max'    => 60000,
					'step'   => 100,
				],
			],
		],
		'contain' => [
			'type'  => 'toggle',
			'label' => __( 'Contain Slides', 'wp-grid-builder' ),
			'help'  => __( 'Prevents excessive scrolling at the beginning or end.', 'wp-grid-builder' ),
		],
	],
];

$carousel_physics = [
	'type'   => 'fieldset',
	'panel'  => 'carousel',
	'legend' => __( 'Physics', 'wp-grid-builder' ),
	'fields' => [
		'draggable'   => [
			'type'  => 'toggle',
			'label' => __( 'Draggable', 'wp-grid-builder' ),
			'help'  => __( 'Enables dragging and flicking thanks to a pointer (mouse, fingers, etc.).', 'wp-grid-builder' ),
		],
		'free_scroll' => [
			'type'  => 'toggle',
			'label' => __( 'Free Scroll', 'wp-grid-builder' ),
			'help'  => __( 'Enables content to be freely scrolled and flicked without aligning slides to an end position.', 'wp-grid-builder' ),
		],
		'row'         => [
			'type'   => 'row',
			'fields' => [
				'attraction'    => [
					'type'  => 'number',
					'label' => __( 'Attraction', 'wp-grid-builder' ),
					'info'  => __( 'Attraction attracts the position of the carousel to the selected slide. Higher attraction makes the carousel move faster. Lower makes it move slower.', 'wp-grid-builder' ),
					'min'   => 0.001,
					'max'   => 1.000,
					'step'  => 0.001,
				],
				'friction'      => [
					'type'  => 'number',
					'label' => __( 'Friction', 'wp-grid-builder' ),
					'info'  => __( 'Friction slows the movement of carousel. Higher friction makes the carousel feel stickier and less bouncy. Lower friction makes the carousel feel looser and more wobbly.', 'wp-grid-builder' ),
					'min'   => 0.001,
					'max'   => 1.000,
					'step'  => 0.001,
				],
				'free_friction' => [
					'type'      => 'number',
					'label'     => __( 'Friction (free)', 'wp-grid-builder' ),
					'info'      => __( 'Friction used when free scrolling. When carousel ends are reached, standard friction is used.', 'wp-grid-builder' ),
					'min'       => 0.001,
					'max'       => 1.000,
					'step'      => 0.001,
					'condition' => [
						[
							'field'   => 'free_scroll',
							'compare' => '==',
							'value'   => '1',
						],
					],
				],
			],
		],
	],
];

$carousel_appearance = [
	'type'   => 'fieldset',
	'panel'  => 'carousel',
	'legend' => __( 'Appearance', 'wp-grid-builder' ),
	'fields' => [
		'grid' => [
			'type'   => 'grid',
			'fields' => [
				'prev_next_buttons_color'      => [
					'type'  => 'color',
					'label' => __( 'Buttons Color', 'wp-grid-builder' ),
				],
				'prev_next_buttons_background' => [
					'type'     => 'color',
					'label'    => __( 'Buttons Background', 'wp-grid-builder' ),
					'gradient' => true,
				],
				'page_dots_color'              => [
					'type'     => 'color',
					'label'    => __( 'Dots Color', 'wp-grid-builder' ),
					'gradient' => true,
				],
				'page_dots_selected_color'     => [
					'type'     => 'color',
					'label'    => __( 'Dots Selected Color', 'wp-grid-builder' ),
					'gradient' => true,
				],
				'prev_next_buttons_size'       => [
					'type'  => 'number',
					'label' => __( 'Buttons Size', 'wp-grid-builder' ),
					'units' => [
						'px'  => [
							'min'  => 1,
							'max'  => 100,
							'step' => 1,
						],
						'em'  => [
							'min'  => 1,
							'max'  => 100,
							'step' => 0.001,
						],
						'rem' => [
							'min'  => 1,
							'max'  => 100,
							'step' => 0.001,
						],
					],
				],
			],
		],
	],
];

$cards_cards = [
	'type'        => 'fieldset',
	'panel'       => 'cards',
	'legend'      => __( 'Cards', 'wp-grid-builder' ),
	'description' => __( 'Assign cards conditionally to the content queried in the grid. Conditions will be checked in the following order.', 'wp-grid-builder' ),
	'fields'      => [
		'card_types'   => [
			'type'     => 'repeater',
			'addLabel' => __( 'Add Card', 'wp-grid-builder' ),
			'rowLabel' => sprintf(
				/* translators: 1: item number, 2: card name, 3: name of items to assign. */
				__( '#%1$s - Assign %2$s to %3$s', 'wp-grid-builder' ),
				'%d',
				'{{ card || ' . __( 'Default Card', 'wp-grid-builder' ) . ' }}',
				'{{ content || ' . _x( 'Any Content', 'Content Type', 'wp-grid-builder' ) . ' }}'
			),
			'minRows'  => 1,
			'maxRows'  => 10,
			'fields'   => [
				'card'       => [
					'type'        => 'select',
					'label'       => __( 'Card', 'wp-grid-builder' ),
					'placeholder' => __( 'Default Card', 'wp-grid-builder' ),
					'async'       => 'wpgb/v2/objects?object=cards&orderby=name&order=asc&fields=id,name',
				],
				'conditions' => [
					'type'    => 'condition',
					'label'   => __( 'Conditions', 'wp-grid-builder' ),
					'context' => 'card',
				],
			],
		],
		'cards_notice' => [
			'type'   => 'notice',
			'status' => 'error',
		],
	],
];

$cards_sizing = [
	'type'        => 'fieldset',
	'panel'       => 'cards',
	'legend'      => __( 'Sizing', 'wp-grid-builder' ),
	'description' => __( 'Apply a global size to all cards in the grid.', 'wp-grid-builder' ),
	'fields'      => [
		'row' => [
			'type'   => 'row',
			'fields' => [
				'columns' => [
					'type'  => 'number',
					'label' => __( 'Columns Number', 'wp-grid-builder' ),
					'min'   => 1,
					'max'   => 12,
					'step'  => 1,
				],
				'rows'    => [
					'type'  => 'number',
					'label' => __( 'Rows Number', 'wp-grid-builder' ),
					'min'   => 1,
					'max'   => 12,
					'step'  => 1,
				],
			],
		],
	],
	'condition'   => [
		[
			'field'   => 'type',
			'compare' => '!==',
			'value'   => 'justified',
		],
	],
];

$cards_animation = [
	'type'        => 'fieldset',
	'panel'       => 'cards',
	'legend'      => __( 'Animation', 'wp-grid-builder' ),
	'description' => __( 'Add an animation to reveal cards when scrolling or filtering content.', 'wp-grid-builder' ),
	'fields'      => [
		'grid'                  => [
			'type'   => 'grid',
			'fields' => [
				'animation'       => [
					'type'         => 'select',
					'label'        => __( 'Type', 'wp-grid-builder' ),
					'placeholder'  => _x( 'None', 'Animation Type', 'wp-grid-builder' ),
					'options'      => $animations,
					'isSearchable' => true,
				],
				'timing_function' => [
					'type'         => 'select',
					'label'        => __( 'Easing', 'wp-grid-builder' ),
					'defaultValue' => 'ease',
					'isSearchable' => true,
					'isClearable'  => false,
					'options'      => [
						[
							'value' => 'custom',
							'label' => _x( 'Custom', 'CSS transition easing', 'wp-grid-builder' ),
						],
						[
							'value' => 'ease',
							'label' => 'Ease',
						],
						[
							'value' => 'linear',
							'label' => 'Linear',
						],
						[
							'value' => 'ease-in',
							'label' => 'Ease In',
						],
						[
							'value' => 'ease-out',
							'label' => 'Ease Out',
						],
						[
							'value' => 'ease-in-out',
							'label' => 'Ease In Out',
						],
						[
							'value' => 'cubic-bezier(0.550, 0.055, 0.675, 0.190)',
							'label' => 'Ease In Cubic',
						],
						[
							'value' => 'cubic-bezier(0.215, 0.610, 0.355, 1.000)',
							'label' => 'Ease Out Cubic',
						],
						[
							'value' => 'cubic-bezier(0.645, 0.045, 0.355, 1.000)',
							'label' => 'Ease In OutCubic',
						],
						[
							'value' => 'cubic-bezier(0.600, 0.040, 0.980, 0.335)',
							'label' => 'Ease In Circ',
						],
						[
							'value' => 'cubic-bezier(0.075, 0.820, 0.165, 1.000)',
							'label' => 'Ease Out Circ',
						],
						[
							'value' => 'cubic-bezier(0.785, 0.135, 0.150, 0.860)',
							'label' => 'Ease In Out Circ',
						],
						[
							'value' => 'cubic-bezier(0.950, 0.050, 0.795, 0.035)',
							'label' => 'Ease In Expo',
						],
						[
							'value' => 'cubic-bezier(0.190, 1.000, 0.220, 1.000)',
							'label' => 'Ease Out Expo',
						],
						[
							'value' => 'cubic-bezier(1.000, 0.000, 0.000, 1.000)',
							'label' => 'Ease In Out Expo',
						],
						[
							'value' => 'cubic-bezier(0.550, 0.085, 0.680, 0.530)',
							'label' => 'Ease In Quad',
						],
						[
							'value' => 'cubic-bezier(0.250, 0.460, 0.450, 0.940)',
							'label' => 'Ease Out Quad',
						],
						[
							'value' => 'cubic-bezier(0.455, 0.030, 0.515, 0.955)',
							'label' => 'Ease In Out Quad',
						],
						[
							'value' => 'cubic-bezier(0.895, 0.030, 0.685, 0.220)',
							'label' => 'Ease In Quart',
						],
						[
							'value' => 'cubic-bezier(0.165, 0.840, 0.440, 1.000)',
							'label' => 'Ease Out Quart',
						],
						[
							'value' => 'cubic-bezier(0.770, 0.000, 0.175, 1.000)',
							'label' => 'Ease In Out Quart',
						],
						[
							'value' => 'cubic-bezier(0.755, 0.050, 0.855, 0.060)',
							'label' => 'Ease In Quint',
						],
						[
							'value' => 'cubic-bezier(0.230, 1.000, 0.320, 1.000)',
							'label' => 'Ease Out Quint',
						],
						[
							'value' => 'cubic-bezier(0.860, 0.000, 0.070, 1.000)',
							'label' => 'Ease In Out Quint',
						],
						[
							'value' => 'cubic-bezier(0.470, 0.000, 0.745, 0.715)',
							'label' => 'Ease In Sine',
						],
						[
							'value' => 'cubic-bezier(0.390, 0.575, 0.565, 1.000)',
							'label' => 'Ease Out Sine',
						],
						[
							'value' => 'cubic-bezier(0.445, 0.050, 0.550, 0.950)',
							'label' => 'Ease In Out Sine',
						],
						[
							'value' => 'cubic-bezier(0.600, -0.280, 0.735, 0.045)',
							'label' => 'Ease In Back',
						],
						[
							'value' => 'cubic-bezier(0.175,  0.885, 0.320, 1.275)',
							'label' => 'Ease Out Back',
						],
						[
							'value' => 'cubic-bezier(0.680, -0.550, 0.265, 1.550)',
							'label' => 'Ease In Out Back',
						],
					],
				],
			],
		],
		'cubic_bezier_function' => [
			'type'      => 'text',
			'label'     => __( 'Cubic Bezier Function', 'wp-grid-builder' ),
			'condition' => [
				[
					'field'   => 'timing_function',
					'compare' => '===',
					'value'   => 'custom',
				],
			],
		],
		'row'                   => [
			'type'   => 'row',
			'fields' => [
				'transition'       => [
					'type'   => 'number',
					'label'  => __( 'Duration', 'wp-grid-builder' ),
					'suffix' => 'ms',
					'min'    => 0,
					'max'    => 9999,
					'step'   => 1,
				],
				'transition_delay' => [
					'type'   => 'number',
					'label'  => __( 'Delay', 'wp-grid-builder' ),
					'info'   => __( 'Delay, in millisecond, between each card in a grid. It allows to stagger card animations.', 'wp-grid-builder' ),
					'suffix' => 'ms',
					'min'    => 0,
					'max'    => 9999,
					'step'   => 1,
				],
			],
		],
	],
];

$cards_loading = [
	'type'        => 'fieldset',
	'panel'       => 'cards',
	'legend'      => __( 'Loading', 'wp-grid-builder' ),
	'description' => __( 'Define a loading animation to reduce perceived loading speed.', 'wp-grid-builder' ),
	'fields'      => [
		'loader_type' => [
			'type'        => 'select',
			'label'       => __( 'Type', 'wp-grid-builder' ),
			'placeholder' => _x( 'None', 'Loader Type', 'wp-grid-builder' ),
			'options'     => $loaders,
		],
		'grid'        => [
			'type'      => 'grid',
			'fields'    => [
				'loader_color' => [
					'type'  => 'color',
					'label' => __( 'Color', 'wp-grid-builder' ),
					'alpha' => true,
				],
				'loader_size'  => [
					'type'  => 'number',
					'label' => __( 'Size', 'wp-grid-builder' ),
					'min'   => 0.1,
					'max'   => 2,
					'step'  => 0.01,
				],
			],
			'condition' => [
				[
					'field'   => 'loader_type',
					'compare' => 'NOT EMPTY',
				],
			],
		],
	],
];

$cards_advanced = [
	'type'          => 'details',
	'panel'         => 'cards',
	'summaryOpened' => __( 'Hide advanced settings', 'wp-grid-builder' ),
	'summaryClosed' => __( 'Show advanced settings', 'wp-grid-builder' ),
	'fields'        => [
		'colors' => [
			'type'        => 'fieldset',
			'legend'      => __( 'Colors', 'wp-grid-builder' ),
			'description' => __( 'Override main card colors set from the card editor.', 'wp-grid-builder' ),
			'fields'      => [
				'grid' => [
					'type'   => 'grid',
					'fields' => [
						'content_background'   => [
							'type'     => 'color',
							'label'    => __( 'Content Background', 'wp-grid-builder' ),
							'gradient' => true,
						],
						'content_color_scheme' => [
							'type'           => 'button',
							'label'          => __( 'Content Color Scheme', 'wp-grid-builder' ),
							'isDeselectable' => true,
							'options'        => [
								[
									'value' => 'light',
									'label' => __( 'Light', 'wp-grid-builder' ),
								],
								[
									'value' => 'dark',
									'label' => __( 'Dark', 'wp-grid-builder' ),
								],
							],
						],
						'overlay_background'   => [
							'type'     => 'color',
							'label'    => __( 'Overlay Background', 'wp-grid-builder' ),
							'gradient' => true,
						],
						'overlay_color_scheme' => [
							'type'           => 'button',
							'label'          => __( 'Overlay Color Scheme', 'wp-grid-builder' ),
							'isDeselectable' => true,
							'options'        => [
								[
									'value' => 'light',
									'label' => __( 'Light', 'wp-grid-builder' ),
								],
								[
									'value' => 'dark',
									'label' => __( 'Dark', 'wp-grid-builder' ),
								],
							],
						],
					],
				],
			],
		],
	],
];

$media_formats = [
	'type'        => 'fieldset',
	'panel'       => 'media',
	'legend'      => __( 'Formats', 'wp-grid-builder' ),
	'description' => __( 'If no formats are selected, only images will be displayed in cards.', 'wp-grid-builder' ),
	'fields'      => [
		'post_formats' => [
			'type'     => 'button',
			'multiple' => true,
			'options'  => [
				[
					'value'  => 'gallery',
					'label'  => __( 'Gallery', 'wp-grid-builder' ),
					'icon'   => 'gallery',
					'inline' => true,
				],
				[
					'value'  => 'video',
					'label'  => __( 'Video', 'wp-grid-builder' ),
					'icon'   => 'video',
					'inline' => true,
				],
				[
					'value'  => 'audio',
					'label'  => __( 'Audio', 'wp-grid-builder' ),
					'icon'   => 'audio',
					'inline' => true,
				],
			],
		],
	],
];

$media_behaviour = [
	'type'   => 'fieldset',
	'panel'  => 'media',
	'legend' => __( 'Behaviour', 'wp-grid-builder' ),
	'fields' => [
		'first_media'           => [
			'type'      => 'toggle',
			'label'     => __( 'First Media Content', 'wp-grid-builder' ),
			'help'      => __( 'Fetch first media in post content if missing.', 'wp-grid-builder' ),
			'condition' => [
				[
					'field'   => 'source',
					'compare' => '===',
					'value'   => 'post_type',
				],
			],
		],
		'gallery_slideshow'     => [
			'type'      => 'toggle',
			'label'     => __( 'Gallery Slideshow', 'wp-grid-builder' ),
			'help'      => __( 'Enable slideshow for gallery post format.', 'wp-grid-builder' ),
			'condition' => [
				[
					'field'   => 'post_formats',
					'compare' => 'CONTAINS',
					'value'   => 'gallery',
				],
			],
		],
		'product_image_hover'   => [
			'type'      => 'toggle',
			'label'     => __( 'Product Image Hover', 'wp-grid-builder' ),
			'help'      => __( 'Reveal first gallery image on hover (WooCommerce).', 'wp-grid-builder' ),
			'condition' => [
				[
					'field'   => 'post_type',
					'compare' => 'CONTAINS',
					'value'   => 'product',
				],
			],
		],
		'embedded_video_poster' => [
			'type'      => 'toggle',
			'label'     => __( 'Embedded Video Posters', 'wp-grid-builder' ),
			'help'      => __( 'Automatically fetches embedded video posters.', 'wp-grid-builder' ),
			'condition' => [
				[
					'field'   => 'post_formats',
					'compare' => 'CONTAINS',
					'value'   => 'video',
				],
			],
		],
		'video_lightbox'        => [
			'type'      => 'toggle',
			'label'     => __( 'Open Videos in Lightbox', 'wp-grid-builder' ),
			'help'      => __( 'When disabled, videos will be played in cards.', 'wp-grid-builder' ),
			'condition' => [
				[
					'field'   => 'post_formats',
					'compare' => 'CONTAINS',
					'value'   => 'video',
				],
			],
		],
	],
];

$media_thumbnail = [
	'type'        => 'fieldset',
	'panel'       => 'media',
	'legend'      => __( 'Thumbnails', 'wp-grid-builder' ),
	'description' => sprintf(
		/* translators: %s: Settings panel url */
		__( 'Additional image sizes can be defined in the <a href="%s">plugin settings</a>.', 'wp-grid-builder' ),
		'#wpgb-modal=settings&wpgb-tab=advanced'
	),
	'fields'      => [
		'grid'              => [
			'type'   => 'grid',
			'fields' => [
				'thumbnail_size'        => [
					'type'         => 'select',
					'label'        => __( 'Desktop Size', 'wp-grid-builder' ),
					'isClearable'  => false,
					'isSearchable' => true,
					'options'      => $image_sizes,
				],
				'thumbnail_size_mobile' => [
					'type'         => 'select',
					'label'        => __( 'Mobile Size', 'wp-grid-builder' ),
					'isClearable'  => false,
					'isSearchable' => true,
					'options'      => $image_sizes,
				],
			],
		],
		'thumbnail_ratio'   => [
			'type'   => 'group',
			'fields' => [
				'row' => [
					'type'   => 'row',
					'label'  => __( 'Aspect Ratio', 'wp-grid-builder' ),
					'info'   => __( 'Set the same aspect ratio for all thumbnails.', 'wp-grid-builder' ),
					'fields' => [
						'x' => [
							'type'    => 'number',
							'tooltip' => 'X',
							'min'     => 1,
							'max'     => 999,
							'step'    => 1,
						],
						'y' => [
							'type'    => 'number',
							'tooltip' => 'Y',
							'min'     => 1,
							'max'     => 999,
							'step'    => 1,
						],
					],
				],
			],
		],
		'default_thumbnail' => [
			'type'  => 'image',
			'label' => __( 'Default Thumbnail', 'wp-grid-builder' ),
			'info'  => __( 'Add a default thumbnail in cards if missing.', 'wp-grid-builder' ),
		],
	],
	'condition'   => [
		[
			'field'   => 'source',
			'compare' => 'IN',
			'value'   => [ 'post_type', 'term', 'user' ],
		],
	],
];

$media_advanced = [
	'type'          => 'details',
	'panel'         => 'media',
	'summaryOpened' => __( 'Hide advanced settings', 'wp-grid-builder' ),
	'summaryClosed' => __( 'Show advanced settings', 'wp-grid-builder' ),
	'fields'        => [
		'loading' => [
			'type'        => 'fieldset',
			'legend'      => __( 'Loading', 'wp-grid-builder' ),
			'description' => __( 'Defer image loading to reduce initial page load time.', 'wp-grid-builder' ),
			'fields'      => [
				'lazy_load'               => [
					'type'  => 'toggle',
					'label' => __( 'Lazy Load Images', 'wp-grid-builder' ),
					'help'  => __( 'Defer the loading of images until there are visible in the viewport.', 'wp-grid-builder' ),
				],
				'lazy_load_spinner'       => [
					'type'      => 'toggle',
					'label'     => __( 'Loading Spinner', 'wp-grid-builder' ),
					'help'      => __( 'Show a loading animation until the image is loaded.', 'wp-grid-builder' ),
					'condition' => [
						[
							'field'   => 'lazy_load',
							'compare' => '==',
							'value'   => 1,
						],
					],
				],
				'lazy_load_blurred_image' => [
					'type'      => 'toggle',
					'label'     => __( 'Blurred Image', 'wp-grid-builder' ),
					'help'      => __( 'Display a blurred image during loading. This option generates and uploads additional tiny image sizes on your WordPress site.', 'wp-grid-builder' ),
					'condition' => [
						[
							'field'   => 'lazy_load',
							'compare' => '==',
							'value'   => 1,
						],
					],
				],
				'grid'                    => [
					'type'      => 'grid',
					'fields'    => [
						'lazy_load_background'    => [
							'type'      => 'color',
							'label'     => __( 'Background Color', 'wp-grid-builder' ),
							'condition' => [
								[
									'field'   => 'lazy_load',
									'compare' => '==',
									'value'   => 1,
								],
							],
						],
						'lazy_load_spinner_color' => [
							'type'      => 'color',
							'label'     => __( 'Spinner Color', 'wp-grid-builder' ),
							'condition' => [
								[
									'field'   => 'lazy_load',
									'compare' => '==',
									'value'   => 1,
								],
								[
									'field'   => 'lazy_load_spinner',
									'compare' => '==',
									'value'   => 1,
								],
							],
						],
					],
					'condition' => [
						'relation' => 'OR',
						[
							'field'   => 'lazy_load',
							'compare' => '==',
							'value'   => 1,
						],
						[
							'field'   => 'lazy_load_spinner',
							'compare' => '==',
							'value'   => 1,
						],
					],
				],
			],
		],
	],
];

$advanced_messages = [
	'type'   => 'fieldset',
	'panel'  => 'advanced',
	'legend' => __( 'Messages', 'wp-grid-builder' ),
	'fields' => [
		'grid' => [
			'type'   => 'grid',
			'fields' => [
				'no_posts_msg'   => [
					'type'        => 'text',
					'label'       => __( 'No Content', 'wp-grid-builder' ),
					'placeholder' => __( 'Sorry, no content found.', 'wp-grid-builder' ),
				],
				'no_results_msg' => [
					'type'        => 'text',
					'label'       => __( 'No Results', 'wp-grid-builder' ),
					'placeholder' => __( 'Sorry, no results match your search criteria.', 'wp-grid-builder' ),
				],
			],
		],
	],
];

$advanced_custom_css = [
	'type'   => 'fieldset',
	'panel'  => 'advanced',
	'legend' => __( 'Custom CSS', 'wp-grid-builder' ),
	'fields' => [
		'grid'       => [
			'type'   => 'grid',
			'fields' => [
				'class'  => [
					'type'        => 'text',
					'label'       => __( 'Custom CSS Class', 'wp-grid-builder' ),
					'placeholder' => __( 'Enter a class name', 'wp-grid-builder' ),
				],
				'css_id' => [
					'type'        => 'text',
					'label'       => __( 'Generated CSS Class', 'wp-grid-builder' ),
					'placeholder' => __( 'Please save the grid to generate the CSS class.', 'wp-grid-builder' ),
					'disabled'    => true,
				],
			],
		],
		'custom_css' => [
			'type'  => 'code',
			'mode'  => 'css',
			'label' => __( 'Enter your CSS code:', 'wp-grid-builder' ),
		],
	],
];

$advanced_custom_js = [
	'type'   => 'fieldset',
	'panel'  => 'advanced',
	'legend' => __( 'Custom JavaScript', 'wp-grid-builder' ),
	'fields' => [
		'not-allowed' => [
			'type'    => 'notice',
			'status'  => 'error',
			'content' => (
				'<strong>' . __( 'You are not allowed to add/edit JavaScript code.', 'wp-grid-builder' ) . '</strong><br><br>' .
				__( 'Only user with <code>edit_plugins</code> capability and <code>DISALLOW_FILE_EDIT</code> constant set to <code>false</code> can add/edit JavaScript code.', 'wp-grid-builder' ) . ' ' .
				__( 'This behaviour is the same as the WordPress plugin editor.', 'wp-grid-builder' )
			),
		],
	],
];

if ( current_user_can( 'edit_plugins' ) ) {

	$advanced_custom_js = [
		'type'   => 'fieldset',
		'panel'  => 'advanced',
		'legend' => __( 'Custom JavaScript', 'wp-grid-builder' ),
		'fields' => [
			'custom_js' => [
				'type'  => 'code',
				'mode'  => 'javascript',
				'label' => __( 'Enter your JS code:', 'wp-grid-builder' ),
			],
		],
	];
}

$advanced_tip = [
	'type'    => 'tip',
	'panel'   => 'advanced',
	'content' => (
		'<strong>' . __( 'Customization is not covered by the scope of the support included with the plugin.', 'wp-grid-builder' ) . '</strong><br>' .
		sprintf(
			/* translators: 1: external url, 2: rel external */
			__( 'If you are looking for customization service, we recommend <a href="%1$s" rel="%2$s" target="_blank">Codeable.io</a>.', 'wp-grid-builder' ),
			'https://codeable.io/?ref=paT8V',
			'external noopener noreferrer'
		)
	),
];

$hidden = [
	'type'   => 'fieldset',
	'panel'  => 'query',
	'hidden' => true,
	'fields' => [
		'id'   => [
			'type' => 'number',
			'min'  => 0,
			'step' => 1,
		],
		'name' => [
			'type' => 'text',
		],
		'slug' => [
			'type' => 'text',
		],
	],
];

$fallback = [
	'type'   => 'fieldset',
	'panel'  => 'query',
	'hidden' => true,
	'fields' => [
		'order'               => [
			'type' => 'select',
		],
		'orderby'             => [
			'type'     => 'select',
			'multiple' => true,
		],
		'meta_key'            => [
			'type' => 'text',
		],
		'tax_query'           => [
			'type'     => 'select',
			'multiple' => true,
		],
		'tax_query_operator'  => [
			'type' => 'select',
		],
		'tax_query_relation'  => [
			'type' => 'select',
		],
		'tax_query_children'  => [
			'type' => 'select',
		],
		'meta_query'          => [
			'type'   => 'repeater',
			'fields' => [
				'key'      => [ 'type' => 'text' ],
				'type'     => [ 'type' => 'select' ],
				'value'    => [ 'type' => 'text' ],
				'compare'  => [ 'type' => 'select' ],
				'relation' => [ 'type' => 'select' ],
			],
		],
		'thumbnail_aspect'    => [
			'type' => 'toggle',
		],
		'override_card_sizes' => [
			'type' => 'toggle',
		],
		'loader'              => [
			'type' => 'toggle',
		],
		'layout'              => [
			'type' => 'select',
		],
		'cards'               => [
			'type'   => 'repeater',
			'fields' => array_merge(
				[
					'default' => [
						'type'  => 'select',
						'async' => true,
					],
				],
				array_map(
					function() {
						return [
							'type'  => 'select',
							'async' => true,
						];
					},
					array_column( $post_types, 'label', 'value' )
				),
				array_map(
					function() {
						return [
							'type'  => 'select',
							'async' => true,
						];
					},
					array_column( $post_formats, 'label', 'value' )
				)
			),
		],
	],
];

return [
	'query_content'       => $query_content,
	'query_order'         => $query_order,
	'query_posts'         => $query_posts,
	'query_terms'         => $query_terms,
	'query_users'         => $query_users,
	'query_advanced'      => $query_advanced,
	'layout_layout'       => $layout_layout,
	'layout_behaviour'    => $layout_behaviour,
	'layout_sizing'       => $layout_sizing,
	'layout_advanced'     => $layout_advanced,
	'carousel_carousel'   => $carousel_carousel,
	'carousel_behaviour'  => $carousel_behaviour,
	'carousel_physics'    => $carousel_physics,
	'carousel_appearance' => $carousel_appearance,
	'cards_cards'         => $cards_cards,
	'cards_sizing'        => $cards_sizing,
	'cards_animation'     => $cards_animation,
	'cards_loading'       => $cards_loading,
	'cards_advanced'      => $cards_advanced,
	'media_formats'       => $media_formats,
	'media_behaviour'     => $media_behaviour,
	'media_thumbnail'     => $media_thumbnail,
	'media_advanced'      => $media_advanced,
	'advanced_messages'   => $advanced_messages,
	'advanced_custom_css' => $advanced_custom_css,
	'advanced_custom_js'  => $advanced_custom_js,
	'advanced_tip'        => $advanced_tip,
	'hidden'              => $hidden,
	'fallback'            => $fallback,
];
