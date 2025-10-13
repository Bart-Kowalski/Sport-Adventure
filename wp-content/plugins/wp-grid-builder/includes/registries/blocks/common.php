<?php
/**
 * Common blocks
 *
 * @package   WP Grid Builder
 * @author    Loïc Blascos
 * @copyright 2019-2024 Loïc Blascos
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$paragraph = [
	'title'           => __( 'Paragraph', 'wp-grid-builder' ),
	'description'     => __( 'Start with the basic building block of all narrative.', 'wp-grid-builder' ),
	'category'        => 'common_blocks',
	'tagName'         => 'p',
	'attributes'      => [
		'content' => [
			'type'    => 'object',
			'default' => [
				'source'      => 'paragraph',
				'idle_scheme' => 'scheme-1',
			],
		],
		'style'   => [
			'type'    => 'object',
			'default' => [
				'idle' => [
					'font-size'    => '1em',
					'line-height'  => '1.4',
					'color_scheme' => 'scheme-1',
				],
			],
		],
	],
	'controls'        => [
		'text' => [
			'type'   => 'code',
			'hidden' => true,
		],
	],
	'render_callback' => 'wpgb_paragraph_block',
];

$raw_content = [
	'title'       => __( 'Custom HTML', 'wp-grid-builder' ),
	'description' => __( 'Add custom HTML code and preview it as you edit.', 'wp-grid-builder' ),
	'category'    => 'common_blocks',
	'tagName'     => 'div',
	'attributes'  => [
		'content' => [
			'type'    => 'object',
			'default' => [
				'source'      => 'raw_content_block',
				'raw_content' => __( 'Add content...', 'wp-grid-builder' ),
				'idle_scheme' => 'scheme-1',
			],
		],
		'style'   => [
			'type'    => 'object',
			'default' => [
				'idle' => [
					'font-size'    => '1em',
					'line-height'  => '1.4',
					'color_scheme' => 'scheme-1',
				],
			],
		],
	],
	'controls'    => [
		'panel' => [
			'type'   => 'panel',
			'fields' => [
				'raw_content' => [
					'type'        => 'code',
					'label'       => __( 'Content', 'wp-grid-builder' ),
					'dynamicData' => 'card',
				],
			],
		],
	],
];

$metadata = [
	'title'       => __( 'Custom Field', 'wp-grid-builder' ),
	'description' => __( 'Displays custom field value.', 'wp-grid-builder' ),
	'category'    => 'common_blocks',
	'tagName'     => 'div',
	'attributes'  => [
		'content' => [
			'type'    => 'object',
			'default' => [
				'source'      => 'metadata',
				'idle_scheme' => 'scheme-1',
			],
		],
		'style'   => [
			'type'    => 'object',
			'default' => [
				'idle' => [
					'font-size'    => '1em',
					'line-height'  => '1.4',
					'color_scheme' => 'scheme-1',
				],
			],
		],
	],
	'controls'    => [
		'panel' => [
			'type'   => 'panel',
			'fields' => [
				'meta_key'                 => [
					'type'        => 'select',
					'label'       => __( 'Custom Field', 'wp-grid-builder' ),
					'placeholder' => __( 'Enter a field name', 'wp-grid-builder' ),
					'async'       => [
						'wpgb/v2/metadata?object=registered',
						'wpgb/v2/metadata?object=post',
						'wpgb/v2/metadata?object=term',
						'wpgb/v2/metadata?object=user',
					],
				],
				'meta_prefix'              => [
					'type'        => 'text',
					'label'       => __( 'Prefix', 'wp-grid-builder' ),
					'whiteSpaces' => true,
					'angleQuotes' => true,
					'condition'   => [
						[
							'field'   => 'source',
							'compare' => '===',
							'value'   => 'metadata',
						],
					],
				],
				'meta_suffix'              => [
					'type'        => 'text',
					'label'       => __( 'Suffix', 'wp-grid-builder' ),
					'whiteSpaces' => true,
					'angleQuotes' => true,
				],
				'meta_type'                => [
					'type'    => 'button',
					'label'   => __( 'Field Type', 'wp-grid-builder' ),
					'options' => [
						[
							'value' => 'text',
							'label' => __( 'Text', 'wp-grid-builder' ),
						],
						[
							'value' => 'number',
							'label' => __( 'Number', 'wp-grid-builder' ),
						],
						[
							'value' => 'date',
							'label' => __( 'Date', 'wp-grid-builder' ),
						],
					],
				],
				'meta_input_date'          => [
					'type'        => 'text',
					'label'       => __( 'Input Date Format', 'wp-grid-builder' ),
					'placeholder' => __( 'Auto (e.g: y-m-d)', 'wp-grid-builder' ),
					'condition'   => [
						[
							'field'   => 'meta_type',
							'compare' => '===',
							'value'   => 'date',
						],
					],
				],
				'meta_output_date'         => [
					'type'        => 'text',
					'label'       => __( 'Output Date Format', 'wp-grid-builder' ),
					'help'        => sprintf(
						// translators: 1: opening anchor tag 2: closing anchor tag.
						__( 'Learn more about %1$sPHP date format%2$s.', 'wp-grid-builder' ),
						'<a href="https://www.php.net/manual/datetime.format.php" target="_blank">',
						'</a>'
					),
					'placeholder' => __( 'Default (e.g: F j, Y)', 'wp-grid-builder' ),
					'condition'   => [
						[
							'field'   => 'meta_type',
							'compare' => '===',
							'value'   => 'date',
						],
					],
				],
				'meta_decimal_places'      => [
					'type'      => 'number',
					'label'     => __( 'Decimal Places', 'wp-grid-builder' ),
					'value'     => 0,
					'condition' => [
						[
							'field'   => 'meta_type',
							'compare' => '===',
							'value'   => 'number',
						],
					],
				],
				'meta_decimal_separator'   => [
					'type'         => 'text',
					'label'        => __( 'Decimal Separator', 'wp-grid-builder' ),
					'defaultValue' => '.',
					'condition'    => [
						[
							'field'   => 'meta_type',
							'compare' => '===',
							'value'   => 'number',
						],
					],
				],
				'meta_thousands_separator' => [
					'type'        => 'text',
					'label'       => __( 'Thousands Separator', 'wp-grid-builder' ),
					'whiteSpaces' => true,
					'condition'   => [
						[
							'field'   => 'meta_type',
							'compare' => '===',
							'value'   => 'number',
						],
					],
				],
			],
		],
	],
];

$svg_icon = [
	'title'       => __( 'SVG Icon', 'wp-grid-builder' ),
	'description' => __( 'Displays an SVG icon.', 'wp-grid-builder' ),
	'category'    => 'common_blocks',
	'tagName'     => 'div',
	'attributes'  => [
		'content' => [
			'type'    => 'object',
			'default' => [
				'source'       => 'svg_icon_block',
				'svg_name'     => 'wpgb/user-interface/link-2',
				'idle_scheme'  => 'scheme-1',
				'stroke-width' => 2,
			],
		],
		'style'   => [
			'type'    => 'object',
			'default' => [
				'idle' => [
					'color_scheme' => 'scheme-1',
					'stroke-width' => 2,
					'width'        => '2em',
					'height'       => '2em',
				],
			],
		],
	],
	'controls'    => [
		'panel' => [
			'type'   => 'panel',
			'fields' => [
				'svg_name'     => [
					'type'  => 'icon',
					'label' => __( 'SVG Icon', 'wp-grid-builder' ),
				],
				'stroke-width' => [
					'type'  => 'range',
					'label' => __( 'Icon Stroke Width', 'wp-grid-builder' ),
					'min'   => 0.1,
					'max'   => 10,
					'step'  => 0.01,
				],
			],
		],
	],
];

$social_share = [
	'title'       => __( 'Social Icon', 'wp-grid-builder' ),
	'description' => __( 'Displays an interactive social icon.', 'wp-grid-builder' ),
	'category'    => 'common_blocks',
	'tagName'     => 'a',
	'attributes'  => [
		'content' => [
			'type'    => 'object',
			'default' => [
				'source'         => 'social_share_block',
				'social_network' => 'facebook',
				'idle_scheme'    => 'scheme-1',
			],
		],
		'style'   => [
			'type'    => 'object',
			'default' => [
				'idle' => [
					'color'                      => '#ffffff',
					'width'                      => '2em',
					'height'                     => '2em',
					'padding-top'                => '0.5em',
					'padding-right'              => '0.5em',
					'padding-bottom'             => '0.5em',
					'padding-left'               => '0.5em',
					'background'                 => '#3b5998',
					'border-top-left-radius'     => '100%',
					'border-top-right-radius'    => '100%',
					'border-bottom-right-radius' => '100%',
					'border-bottom-left-radius'  => '100%',
				],
			],
		],
	],
	'controls'    => [
		'panel' => [
			'type'   => 'panel',
			'fields' => [
				'social_network' => [
					'type'         => 'select',
					'label'        => __( 'Social Network', 'wp-grid-builder' ),
					'isClearable'  => false,
					'isSearchable' => true,
					'defaultValue' => 'facebook',
					'options'      => [
						[
							'value' => 'blogger',
							'label' => 'Blogger',
						],
						[
							'value' => 'buffer',
							'label' => 'Buffer',
						],
						[
							'value' => 'email',
							'label' => __( 'Email', 'wp-grid-builder' ),
						],
						[
							'value' => 'evernote',
							'label' => 'Evernote',
						],
						[
							'value' => 'facebook',
							'label' => 'Facebook',
						],
						[
							'value' => 'linkedin',
							'label' => 'LinkedIn',
						],
						[
							'value' => 'pinterest',
							'label' => 'Pinterest',
						],
						[
							'value' => 'reddit',
							'label' => 'Reddit',
						],
						[
							'value' => 'tumblr',
							'label' => 'Tumblr',
						],
						[
							'value' => 'twitter',
							'label' => __( 'X (formerly twitter)', 'wp-grid-builder' ),
						],
						[
							'value' => 'vkontakte',
							'label' => 'VKontakte',
						],
						[
							'value' => 'whatsapp',
							'label' => 'Whatsapp',
						],
					],
				],
			],
		],
	],
];

$media_button = [
	'title'       => __( 'Media Button', 'wp-grid-builder' ),
	'description' => __( 'Opens the lightbox or play a song/video on click.', 'wp-grid-builder' ),
	'category'    => 'common_blocks',
	'tagName'     => 'div',
	'attributes'  => [
		'content' => [
			'type'    => 'object',
			'default' => [
				'source'        => 'media_button_block',
				'lightbox_icon' => 'wpgb/user-interface/add',
				'play_icon'     => 'wpgb/multimedia/button-play-1',
				'stroke-width'  => 2,
				'idle_scheme'   => 'scheme-1',
			],
		],
		'style'   => [
			'type'    => 'object',
			'default' => [
				'idle' => [
					'color_scheme'   => 'scheme-1',
					'stroke-width'   => 2,
					'width'          => '3em',
					'height'         => '3em',
					'padding-top'    => '0.75em',
					'padding-right'  => '0.75em',
					'padding-bottom' => '0.75em',
					'padding-left'   => '0.75em',
				],
			],
		],
	],
	'controls'    => [
		'panel' => [
			'type'   => 'panel',
			'fields' => [
				'lightbox_icon' => [
					'type'  => 'icon',
					'label' => __( 'Lightbox Icon', 'wp-grid-builder' ),
				],
				'play_icon'     => [
					'type'  => 'icon',
					'label' => __( 'Play Button Icon', 'wp-grid-builder' ),
				],
				'stroke-width'  => [
					'type'  => 'range',
					'label' => __( 'Icon Stroke Width', 'wp-grid-builder' ),
					'min'   => 0.1,
					'max'   => 10,
					'step'  => 0.01,
				],
			],
		],
	],
];

return [
	'paragraph'          => $paragraph,
	'raw_content_block'  => $raw_content,
	'metadata'           => $metadata,
	'svg_icon_block'     => $svg_icon,
	'social_share_block' => $social_share,
	'media_button_block' => $media_button,
];
