<?php
/**
 * Post controls
 *
 * @package   WP Grid Builder
 * @author    Loïc Blascos
 * @copyright 2019-2024 Loïc Blascos
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$general_permalink = [
	'type'        => 'fieldset',
	'panel'       => 'general',
	'legend'      => __( 'Permalink', 'wp-grid-builder' ),
	'description' => __( 'Alternative link to default WordPress permalink.', 'wp-grid-builder' ),
	'fields'      => [
		'permalink' => [
			'type' => 'url',
		],
	],
];

$general_media = [
	'type'        => 'fieldset',
	'panel'       => 'general',
	'legend'      => __( 'Media', 'wp-grid-builder' ),
	'description' => __( 'Define a custom media.', 'wp-grid-builder' ),
	'fields'      => [
		'post_format'     => [
			'type'    => 'button',
			'options' => [
				[
					'value' => '',
					'label' => __( 'Default', 'wp-grid-builder' ),
				],
				[
					'value' => 'gallery',
					'label' => __( 'Gallery', 'wp-grid-builder' ),
				],
				[
					'value' => 'video',
					'label' => __( 'Video', 'wp-grid-builder' ),
				],
				[
					'value' => 'audio',
					'label' => __( 'Audio', 'wp-grid-builder' ),
				],
			],
		],
		'attachment_id'   => [
			'type'      => 'image',
			'label'     => __( 'Thumbnail', 'wp-grid-builder' ),
			'help'      => __( 'Alternative image to the featured image.', 'wp-grid-builder' ),
			'condition' => [
				[
					'field'   => 'post_format',
					'compare' => '!==',
					'value'   => 'gallery',
				],
			],
		],
		'gallery_ids'     => [
			'type'      => 'gallery',
			'label'     => __( 'Gallery', 'wp-grid-builder' ),
			'mimeType'  => [ 'image' ],
			'condition' => [
				[
					'field'   => 'post_format',
					'compare' => '===',
					'value'   => 'gallery',
				],
			],
		],
		'mp3_url'         => [
			'type'      => 'file',
			'label'     => __( 'MP3 file', 'wp-grid-builder' ),
			'mimeType'  => [ 'audio/mpeg', 'audio/mp3' ],
			'condition' => [
				[
					'field'   => 'post_format',
					'compare' => '===',
					'value'   => 'audio',
				],
			],
		],
		'ogg_url'         => [
			'type'      => 'file',
			'label'     => __( 'OGG file', 'wp-grid-builder' ),
			'mimeType'  => [ 'audio/ogg' ],
			'condition' => [
				[
					'field'   => 'post_format',
					'compare' => '===',
					'value'   => 'audio',
				],
			],
		],
		'mp4_url'         => [
			'type'      => 'file',
			'label'     => __( 'MP4 file', 'wp-grid-builder' ),
			'mimeType'  => [ 'video/mp4' ],
			'condition' => [
				[
					'field'   => 'post_format',
					'compare' => '===',
					'value'   => 'video',
				],
			],
		],
		'ogv_url'         => [
			'type'      => 'file',
			'label'     => __( 'OGV file', 'wp-grid-builder' ),
			'mimeType'  => [ 'video/ogg' ],
			'condition' => [
				[
					'field'   => 'post_format',
					'compare' => '===',
					'value'   => 'video',
				],
			],
		],
		'webm_url'        => [
			'type'      => 'file',
			'label'     => __( 'WEBM file', 'wp-grid-builder' ),
			'mimeType'  => [ 'video/webm' ],
			'condition' => [
				[
					'field'   => 'post_format',
					'compare' => '===',
					'value'   => 'video',
				],
			],
		],
		'embed_video_url' => [
			'type'      => 'url',
			'label'     => __( 'Embedded URL', 'wp-grid-builder' ),
			'help'      => __( 'Works with Youtube, Vimeo, and Wistia embedded URL.', 'wp-grid-builder' ),
			'condition' => [
				[
					'field'   => 'post_format',
					'compare' => '===',
					'value'   => 'video',
				],
			],
		],
		'video_ratio'     => [
			'type'      => 'button',
			'label'     => __( 'Aspect Ratio', 'wp-grid-builder' ),
			'options'   => [
				[
					'value' => '',
					'label' => __( 'None', 'wp-grid-builder' ),
				],
				[
					'value' => '4:3',
					'label' => '4:3',
				],
				[
					'value' => '16:9',
					'label' => '16:9',
				],
				[
					'value' => '16:10',
					'label' => '16:10',
				],
			],
			'condition' => [
				[
					'field'   => 'post_format',
					'compare' => '===',
					'value'   => 'video',
				],
			],
		],
	],
];

$appearance_sizing = [
	'type'        => 'fieldset',
	'panel'       => 'appearance',
	'legend'      => __( 'Sizing', 'wp-grid-builder' ),
	'description' => __( 'Apply a global size used in all grids.', 'wp-grid-builder' ),
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
];

$appearance_colors = [
	'type'        => 'fieldset',
	'panel'       => 'appearance',
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
];

return [
	'general_permalink' => $general_permalink,
	'general_media'     => $general_media,
	'appearance_sizing' => $appearance_sizing,
	'appearance_colors' => $appearance_colors,
];
