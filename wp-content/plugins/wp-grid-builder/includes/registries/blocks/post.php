<?php
/**
 * Post blocks
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

$the_id = [
	'title'       => __( 'Post ID', 'wp-grid-builder' ),
	'description' => __( 'Displays the ID of the post.', 'wp-grid-builder' ),
	'category'    => 'post_blocks',
	'tagName'     => 'div',
	'attributes'  => [
		'content' => [
			'type'    => 'object',
			'default' => [
				'source'      => 'post_field',
				'post_field'  => 'the_id',
				'idle_scheme' => 'scheme-2',
			],
		],
		'style'   => [
			'type'    => 'object',
			'default' => [
				'idle' => [
					'font-size'    => '1em',
					'line-height'  => '1',
					'font-weight'  => '600',
					'color_scheme' => 'scheme-2',
				],
			],
		],
	],
];

$the_name = [
	'title'       => __( 'Post Name', 'wp-grid-builder' ),
	'description' => __( 'Displays the name of the post.', 'wp-grid-builder' ),
	'category'    => 'post_blocks',
	'tagName'     => 'div',
	'attributes'  => [
		'content' => [
			'type'    => 'object',
			'default' => [
				'source'       => 'post_field',
				'post_field'   => 'the_name',
				'idle_scheme'  => 'scheme-1',
				'hover_scheme' => 'accent-1',
			],
		],
		'action'  => [
			'type'    => 'object',
			'default' => [
				'action_type' => 'link',
			],
		],
		'style'   => [
			'type'    => 'object',
			'default' => [
				'idle'  => [
					'font-size'    => '1.625em',
					'line-height'  => '1.4',
					'font-weight'  => '700',
					'color_scheme' => 'scheme-1',
				],
				'hover' => [
					'hover_selector' => 'itself',
					'color_scheme'   => 'accent-1',
				],
			],
		],
	],
];

$the_title = [
	'title'       => __( 'Post Title', 'wp-grid-builder' ),
	'description' => __( 'Displays the title of the post.', 'wp-grid-builder' ),
	'category'    => 'post_blocks',
	'tagName'     => 'h3',
	'attributes'  => [
		'content' => [
			'type'    => 'object',
			'default' => [
				'source'       => 'post_field',
				'post_field'   => 'the_title',
				'idle_scheme'  => 'scheme-1',
				'hover_scheme' => 'accent-1',
			],
		],
		'action'  => [
			'type'    => 'object',
			'default' => [
				'action_type' => 'link',
			],
		],
		'style'   => [
			'type'    => 'object',
			'default' => [
				'idle'  => [
					'font-size'      => '1.625em',
					'line-height'    => '1.4',
					'font-weight'    => '700',
					'color_scheme'   => 'scheme-1',
					'padding-top'    => 0,
					'padding-bottom' => 0,
					'padding-right'  => 0,
					'padding-left'   => 0,
					'margin-left'    => 0,
					'margin-bottom'  => 0,
					'margin-right'   => 0,
					'margin-top'     => 0,
				],
				'hover' => [
					'hover_selector' => 'itself',
					'color_scheme'   => 'accent-1',
				],
			],
		],
	],
];

$the_content = [
	'title'       => __( 'Post Content', 'wp-grid-builder' ),
	'description' => __( 'Displays the contents of the post.', 'wp-grid-builder' ),
	'category'    => 'post_blocks',
	'tagName'     => 'div',
	'attributes'  => [
		'content' => [
			'type'    => 'object',
			'default' => [
				'source'      => 'post_field',
				'post_field'  => 'the_content',
				'idle_scheme' => 'scheme-2',
			],
		],
		'style'   => [
			'type'    => 'object',
			'default' => [
				'idle' => [
					'font-size'    => '1.125em',
					'line-height'  => '1.6',
					'font-weight'  => '300',
					'color_scheme' => 'scheme-2',
				],
			],
		],
	],
];

$the_excerpt = [
	'title'       => __( 'Post Excerpt', 'wp-grid-builder' ),
	'description' => __( 'Displays the excerpt of the post.', 'wp-grid-builder' ),
	'category'    => 'post_blocks',
	'tagName'     => 'p',
	'attributes'  => [
		'content' => [
			'type'    => 'object',
			'default' => [
				'source'         => 'post_field',
				'post_field'     => 'the_excerpt',
				'idle_scheme'    => 'scheme-2',
				'excerpt_length' => 35,
				'excerpt_suffix' => '...',
			],
		],
		'style'   => [
			'type'    => 'object',
			'default' => [
				'idle' => [
					'font-size'    => '1.125em',
					'line-height'  => '1.6',
					'font-weight'  => '300',
					'color_scheme' => 'scheme-2',
				],
			],
		],
	],
	'controls'    => [
		'panel' => [
			'type'   => 'panel',
			'fields' => [
				'excerpt_suffix' => [
					'type'  => 'text',
					'label' => __( 'Excerpt Suffix', 'wp-grid-builder' ),
				],
				'excerpt_length' => [
					'type'  => 'range',
					'label' => __( 'Excerpt Length', 'wp-grid-builder' ),
					'help'  => __( 'Set the length to -1 to display the full excerpt.', 'wp-grid-builder' ),
					'min'   => -1,
					'max'   => 999,
					'step'  => 1,
				],
			],
		],
	],
];

$the_post_type = [
	'title'       => __( 'Post Type', 'wp-grid-builder' ),
	'description' => __( 'Displays the name of the post type.', 'wp-grid-builder' ),
	'category'    => 'post_blocks',
	'tagName'     => 'div',
	'attributes'  => [
		'content' => [
			'type'    => 'object',
			'default' => [
				'source'      => 'post_field',
				'post_field'  => 'the_post_type',
				'idle_scheme' => 'scheme-1',
			],
		],
		'style'   => [
			'type'    => 'object',
			'default' => [
				'idle' => [
					'font-size'    => '1em',
					'line-height'  => '1.4',
					'font-weight'  => '600',
					'color_scheme' => 'scheme-1',
				],
			],
		],
	],
];

$the_post_format = [
	'title'       => __( 'Post Format', 'wp-grid-builder' ),
	'description' => __( 'Displays the format of the post.', 'wp-grid-builder' ),
	'category'    => 'post_blocks',
	'tagName'     => 'div',
	'attributes'  => [
		'content' => [
			'type'    => 'object',
			'default' => [
				'source'      => 'post_field',
				'post_field'  => 'the_post_format',
				'idle_scheme' => 'scheme-1',
			],
		],
		'style'   => [
			'type'    => 'object',
			'default' => [
				'idle' => [
					'font-size'    => '1em',
					'line-height'  => '1.4',
					'font-weight'  => '600',
					'color_scheme' => 'scheme-1',
				],
			],
		],
	],
];

$the_post_status = [
	'title'       => __( 'Post Status', 'wp-grid-builder' ),
	'description' => __( 'Displays the status of the post.', 'wp-grid-builder' ),
	'category'    => 'post_blocks',
	'tagName'     => 'div',
	'attributes'  => [
		'content' => [
			'type'    => 'object',
			'default' => [
				'source'      => 'post_field',
				'post_field'  => 'the_post_status',
				'idle_scheme' => 'scheme-1',
			],
		],
		'style'   => [
			'type'    => 'object',
			'default' => [
				'idle' => [
					'font-size'    => '1em',
					'line-height'  => '1.4',
					'font-weight'  => '600',
					'color_scheme' => 'scheme-1',
				],
			],
		],
	],
];

$the_date = [
	'title'       => __( 'Post Date', 'wp-grid-builder' ),
	'description' => __( 'Displays the published date of the post.', 'wp-grid-builder' ),
	'category'    => 'post_blocks',
	'tagName'     => 'time',
	'attributes'  => [
		'content' => [
			'type'    => 'object',
			'default' => [
				'source'      => 'post_field',
				'post_field'  => 'the_date',
				'idle_scheme' => 'scheme-1',
				'format'      => 'j F Y',
			],
		],
		'style'   => [
			'type'    => 'object',
			'default' => [
				'idle' => [
					'font-size'    => '1em',
					'line-height'  => '1.4',
					'font-weight'  => '600',
					'color_scheme' => 'scheme-1',
				],
			],
		],
	],
	'controls'    => [
		'panel' => [
			'type'   => 'panel',
			'fields' => [
				'date_format' => [
					'type'  => 'text',
					'label' => __( 'Date Format', 'wp-grid-builder' ),
					'help'  => sprintf(
						// translators: 1: opening anchor tag 2: closing anchor tag.
						__( 'Enter any %1$sPHP date format%2$s. Enter "ago" to display human readable format.', 'wp-grid-builder' ),
						'<a href="https://www.php.net/manual/datetime.format.php" target="_blank">',
						'</a>'
					),
				],
			],
		],
	],
];

$the_modified_date = [
	'title'       => __( 'Post Modified Date', 'wp-grid-builder' ),
	'description' => __( 'Displays the modified date of the post.', 'wp-grid-builder' ),
	'category'    => 'post_blocks',
	'tagName'     => 'time',
	'attributes'  => [
		'content' => [
			'type'    => 'object',
			'default' => [
				'source'      => 'post_field',
				'post_field'  => 'the_modified_date',
				'idle_scheme' => 'scheme-1',
				'format'      => 'j F Y',
			],
		],
		'style'   => [
			'type'    => 'object',
			'default' => [
				'idle' => [
					'font-size'    => '1em',
					'line-height'  => '1.4',
					'font-weight'  => '600',
					'color_scheme' => 'scheme-1',
				],
			],
		],
	],
	'controls'    => [
		'panel' => [
			'type'   => 'panel',
			'fields' => [
				'date_format' => [
					'type'  => 'text',
					'label' => __( 'Date Format', 'wp-grid-builder' ),
					'help'  => sprintf(
						// translators: 1: opening anchor tag 2: closing anchor tag.
						__( 'Enter any %1$sPHP date format%2$s. Enter "ago" to display human readable format.', 'wp-grid-builder' ),
						'<a href="https://www.php.net/manual/datetime.format.php" target="_blank">',
						'</a>'
					),
				],
			],
		],
	],
];

$the_terms = [
	'title'       => __( 'Post Terms', 'wp-grid-builder' ),
	'description' => __( 'Displays the taxonomy terms assigned to the post.', 'wp-grid-builder' ),
	'category'    => 'post_blocks',
	'tagName'     => 'div',
	'attributes'  => [
		'content' => [
			'type'    => 'object',
			'default' => [
				'source'       => 'post_field',
				'post_field'   => 'the_terms',
				'idle_scheme'  => 'scheme-3',
				'hover_scheme' => 'accent-1',
				'term_glue'    => ', ',
			],
		],
		'style'   => [
			'type'    => 'object',
			'default' => [
				'idle'  => [
					'font-size'    => '0.8125em',
					'line-height'  => '1.4',
					'font-weight'  => '400',
					'color_scheme' => 'scheme-3',
				],
				'hover' => [
					'color_scheme'   => 'accent-1',
					'hover_selector' => 'itself',
				],
			],
		],
	],
	'controls'    => [
		'panel1' => [
			'type'   => 'panel',
			'fields' => [
				'number'    => [
					'type'  => 'number',
					'label' => __( 'Number', 'wp-grid-builder' ),
					'help'  => __( '"0" or empty to show all term.', 'wp-grid-builder' ),
					'min'   => 0,
					'max'   => 100,
					'step'  => 1,
				],
				'orderby'   => [
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
				'meta_key'  => [
					'type'        => 'text',
					'label'       => __( 'Custom Field', 'wp-grid-builder' ),
					'placeholder' => __( 'Enter a field name', 'wp-grid-builder' ),
					'condition'   => [
						[
							'field'   => 'orderby',
							'compare' => 'IN',
							'value'   => [ 'meta_value', 'meta_value_num' ],
						],
					],
				],
				'order'     => [
					'type'    => 'button',
					'label'   => __( 'Order', 'wp-grid-builder' ),
					'options' => [
						[
							'value' => 'ASC',
							'label' => __( 'ASC', 'wp-grid-builder' ),
						],
						[
							'value' => 'DESC',
							'label' => __( 'DESC', 'wp-grid-builder' ),
						],
					],
				],
				'taxonomy'  => [
					'type'         => 'select',
					'label'        => __( 'Taxonomies', 'wp-grid-builder' ),
					'placeholder'  => esc_html_x( 'Any', 'Include Taxonomies default value', 'wp-grid-builder' ),
					'options'      => Helpers::format_options( Helpers::get_taxonomies_list() ),
					'multiple'     => true,
					'isSearchable' => true,
				],
				'include'   => [
					'type'        => 'select',
					'label'       => __( 'Include Terms', 'wp-grid-builder' ),
					'placeholder' => _x( 'None', 'Include Terms default value', 'wp-grid-builder' ),
					'async'       => 'wpgb/v2/terms',
					'multiple'    => true,
					'condition'   => [
						[
							'field'   => 'exclude',
							'compare' => 'EMPTY',
						],
					],
				],
				'exclude'   => [
					'type'        => 'select',
					'label'       => __( 'Exclude Terms', 'wp-grid-builder' ),
					'placeholder' => _x( 'None', 'Exclude Terms default value', 'wp-grid-builder' ),
					'async'       => 'wpgb/v2/terms',
					'multiple'    => true,
					'condition'   => [
						[
							'field'   => 'include',
							'compare' => 'EMPTY',
						],
					],
				],
				'childless' => [
					'type'  => 'toggle',
					'label' => __( 'Childless Terms', 'wp-grid-builder' ),
				],
			],
		],
		'panel2' => [
			'type'   => 'panel',
			'fields' => [
				'term_glue'    => [
					'type'        => 'text',
					'label'       => __( 'Terms Separator', 'wp-grid-builder' ),
					'whiteSpaces' => true,
					'angleQuotes' => true,
				],
				'term_spacing' => [
					'type'  => 'box',
					'label' => __( 'Terms Spacing', 'wp-grid-builder' ),
					'units' => [
						'custom' => [
							'min'  => 0,
							'max'  => 100,
							'step' => 0.01,
						],
						'px'     => [
							'min'  => 0,
							'max'  => 999,
							'step' => 1,
						],
						'em'     => [
							'min'  => 0,
							'max'  => 100,
							'step' => 0.001,
						],
						'rem'    => [
							'min'  => 0,
							'max'  => 100,
							'step' => 0.001,
						],
						'%'      => [
							'min'  => 0,
							'max'  => 100,
							'step' => 0.001,
						],
					],
				],
				'term_link'    => [
					'type'  => 'toggle',
					'label' => __( 'Link to archive page', 'wp-grid-builder' ),
				],
				'term_color'   => [
					'type'  => 'toggle',
					'label' => __( 'Use the colors defined in the admin edit term pages', 'wp-grid-builder' ),
				],
			],
		],
	],
];

$the_author = [
	'title'       => __( 'Post Author', 'wp-grid-builder' ),
	'description' => __( 'Displays the author name of the post.', 'wp-grid-builder' ),
	'category'    => 'post_blocks',
	'tagName'     => 'div',
	'attributes'  => [
		'content' => [
			'type'    => 'object',
			'default' => [
				'source'       => 'post_field',
				'post_field'   => 'the_author',
				'idle_scheme'  => 'scheme-1',
				'hover_scheme' => 'accent-1',
			],
		],
		'action'  => [
			'type'    => 'object',
			'default' => [
				'action_type' => 'link',
				'link_url'    => 'author_page',
			],
		],
		'style'   => [
			'type'    => 'object',
			'default' => [
				'idle'  => [
					'font-size'    => '1em',
					'line-height'  => '1.4',
					'color_scheme' => 'scheme-1',
				],
				'hover' => [
					'color_scheme'   => 'accent-1',
					'hover_selector' => 'itself',
				],
			],
		],
	],
	'controls'    => [
		'panel' => [
			'type'   => 'panel',
			'fields' => [
				'author_prefix' => [
					'type'        => 'text',
					'label'       => __( 'Author Prefix', 'wp-grid-builder' ),
					'whiteSpaces' => true,
					'angleQuotes' => true,
				],
			],
		],
	],
];

$the_avatar = [
	'title'       => __( 'Post Avatar', 'wp-grid-builder' ),
	'description' => __( 'Displays the author avatar of the post.', 'wp-grid-builder' ),
	'category'    => 'post_blocks',
	'tagName'     => 'div',
	'attributes'  => [
		'content' => [
			'type'    => 'object',
			'default' => [
				'source'      => 'post_field',
				'post_field'  => 'the_avatar',
				'idle_scheme' => 'scheme-1',
			],
		],
		'action'  => [
			'type'    => 'object',
			'default' => [
				'action_type' => 'link',
				'link_url'    => 'author_page',
			],
		],
		'style'   => [
			'type'    => 'object',
			'default' => [
				'idle' => [
					'font-size'                  => '1em',
					'line-height'                => '1.4',
					'font-weight'                => '400',
					'width'                      => '48px',
					'height'                     => '48px',
					'border-top-left-radius'     => '100%',
					'border-top-right-radius'    => '100%',
					'border-bottom-right-radius' => '100%',
					'border-bottom-left-radius'  => '100%',
					'color_scheme'               => 'scheme-1',
				],
			],
		],
	],
];

$comments_number = [
	'title'       => __( 'Comments Count', 'wp-grid-builder' ),
	'description' => __( 'Displays the number of comments of the post.', 'wp-grid-builder' ),
	'category'    => 'post_blocks',
	'tagName'     => 'div',
	'attributes'  => [
		'content' => [
			'type'    => 'object',
			'default' => [
				'source'       => 'post_field',
				'post_field'   => 'comments_number',
				'idle_scheme'  => 'scheme-2',
				'hover_scheme' => 'scheme-1',
				'count_format' => 'text',
			],
		],
		'action'  => [
			'type'    => 'object',
			'default' => [
				'action_type' => 'link',
			],
		],
		'style'   => [
			'type'    => 'object',
			'default' => [
				'idle'  => [
					'font-size'    => '0.8125em',
					'line-height'  => '1.4',
					'font-weight'  => '400',
					'color_scheme' => 'scheme-2',
					'font-style'   => 'italic',
				],
				'hover' => [
					'hover_selector' => 'itself',
					'color_scheme'   => 'scheme-1',
				],
			],
		],
	],
	'controls'    => [
		'panel' => [
			'type'   => 'panel',
			'fields' => [
				'count_format' => [
					'type'    => 'button',
					'label'   => __( 'Format Type', 'wp-grid-builder' ),
					'options' => [
						[
							'value' => 'text',
							'label' => __( 'Text', 'wp-grid-builder' ),
						],
						[
							'value' => 'number',
							'label' => __( 'Number', 'wp-grid-builder' ),
						],
					],
				],
			],
		],
	],
];

return [
	'the_id'            => $the_id,
	'the_name'          => $the_name,
	'the_title'         => $the_title,
	'the_content'       => $the_content,
	'the_excerpt'       => $the_excerpt,
	'the_post_type'     => $the_post_type,
	'the_post_format'   => $the_post_format,
	'the_post_status'   => $the_post_status,
	'the_date'          => $the_date,
	'the_modified_date' => $the_modified_date,
	'the_terms'         => $the_terms,
	'the_author'        => $the_author,
	'the_avatar'        => $the_avatar,
	'comments_number'   => $comments_number,
];
