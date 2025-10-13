<?php
/**
 * Term blocks
 *
 * @package   WP Grid Builder
 * @author    Loïc Blascos
 * @copyright 2019-2024 Loïc Blascos
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$the_term_id = [
	'title'       => __( 'Term ID', 'wp-grid-builder' ),
	'description' => __( 'Displays the ID of the term.', 'wp-grid-builder' ),
	'category'    => 'term_blocks',
	'tagName'     => 'div',
	'attributes'  => [
		'content' => [
			'type'    => 'object',
			'default' => [
				'source'      => 'term_field',
				'post_field'  => 'the_term_id',
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

$the_term_slug = [
	'title'       => __( 'Term Slug', 'wp-grid-builder' ),
	'description' => __( 'Displays the slug of the term.', 'wp-grid-builder' ),
	'category'    => 'term_blocks',
	'tagName'     => 'div',
	'attributes'  => [
		'content' => [
			'type'    => 'object',
			'default' => [
				'source'      => 'term_field',
				'term_field'  => 'the_term_slug',
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

$the_term_name = [
	'title'       => __( 'Term Name', 'wp-grid-builder' ),
	'description' => __( 'Displays the name of the term.', 'wp-grid-builder' ),
	'category'    => 'term_blocks',
	'tagName'     => 'h3',
	'attributes'  => [
		'content' => [
			'type'    => 'object',
			'default' => [
				'source'       => 'term_field',
				'term_field'   => 'the_term_name',
				'idle_scheme'  => 'scheme-1',
				'hover_scheme' => 'accent-1',
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
					'margin-top'     => 0,
					'margin-right'   => 0,
					'margin-bottom'  => 0,
					'margin-left'    => 0,
					'padding-left'   => 0,
					'padding-bottom' => 0,
					'padding-right'  => 0,
					'padding-top'    => 0,
				],
				'hover' => [
					'hover_selector' => 'itself',
					'color_scheme'   => 'accent-1',
				],
			],
		],
	],
];

$the_term_taxonomy = [
	'title'       => __( 'Term Taxonomy', 'wp-grid-builder' ),
	'description' => __( 'Displays the taxonomy name of the term.', 'wp-grid-builder' ),
	'category'    => 'term_blocks',
	'tagName'     => 'div',
	'attributes'  => [
		'content' => [
			'type'    => 'object',
			'default' => [
				'source'      => 'term_field',
				'term_field'  => 'the_term_taxonomy',
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

$the_term_parent = [
	'title'       => __( 'Term Parent', 'wp-grid-builder' ),
	'description' => __( 'Displays the parent name of the term.', 'wp-grid-builder' ),
	'category'    => 'term_blocks',
	'tagName'     => 'div',
	'attributes'  => [
		'content' => [
			'type'    => 'object',
			'default' => [
				'source'      => 'term_field',
				'term_field'  => 'the_term_parent',
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

$the_term_description = [
	'title'       => __( 'Term Description', 'wp-grid-builder' ),
	'description' => __( 'Displays the description of the term.', 'wp-grid-builder' ),
	'category'    => 'term_blocks',
	'tagName'     => 'p',
	'attributes'  => [
		'content' => [
			'type'    => 'object',
			'default' => [
				'source'         => 'term_field',
				'term_field'     => 'the_term_description',
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

$the_term_count = [
	'title'       => __( 'Term Post Count', 'wp-grid-builder' ),
	'description' => __( 'Displays the number of matching term posts.', 'wp-grid-builder' ),
	'category'    => 'term_blocks',
	'tagName'     => 'div',
	'attributes'  => [
		'content' => [
			'type'    => 'object',
			'default' => [
				'source'       => 'term_field',
				'term_field'   => 'the_term_count',
				'idle_scheme'  => 'scheme-2',
				'count_format' => 'text',
			],
		],
		'style'   => [
			'type'    => 'object',
			'default' => [
				'idle' => [
					'font-size'    => '0.813em',
					'line-height'  => '1.4',
					'font-weight'  => '400',
					'color_scheme' => 'scheme-2',
					'font-style'   => 'italic',
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
	'the_term_id'          => $the_term_id,
	'the_term_slug'        => $the_term_slug,
	'the_term_name'        => $the_term_name,
	'the_term_taxonomy'    => $the_term_taxonomy,
	'the_term_parent'      => $the_term_parent,
	'the_term_description' => $the_term_description,
	'the_term_count'       => $the_term_count,
];
