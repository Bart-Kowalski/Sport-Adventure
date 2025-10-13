<?php
/**
 * Options controls
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

$general_plugin = [
	'type'        => 'fieldset',
	'panel'       => 'general',
	'legend'      => __( 'Plugin', 'wp-grid-builder' ),
	'description' => __( 'Manage features and settings available in back-office.', 'wp-grid-builder' ),
	'fields'      => [
		'uninstall'            => (
			current_user_can( 'manage_options' ) ? [
				'type'  => 'toggle',
				'label' => __( 'Delete Data on Uninstall', 'wp-grid-builder' ),
				'help'  => __( 'Remove all data associated with the plugin.', 'wp-grid-builder' ),
			] :
			[]
		),
		'post_formats_support' => [
			'type'  => 'toggle',
			'label' => __( 'Post Formats Support', 'wp-grid-builder' ),
			'help'  => __( 'Add Post Formats feature to any post type.', 'wp-grid-builder' ),
		],
		'post_meta'            => [
			'type'  => 'toggle',
			'label' => __( 'Display Post Options', 'wp-grid-builder' ),
			'help'  => __( 'Display plugin settings on post editing pages.', 'wp-grid-builder' ),
		],
		'term_meta'            => [
			'type'  => 'toggle',
			'label' => __( 'Display Term Options', 'wp-grid-builder' ),
			'help'  => __( 'Display plugin settings on term editing pages.', 'wp-grid-builder' ),
		],
		'render_blocks'        => [
			'type'  => 'toggle',
			'label' => __( 'Render Blocks in Editor', 'wp-grid-builder' ),
			'help'  => __( 'This option may slow down the loading time in the editor.', 'wp-grid-builder' ),
		],
		'bunny_fonts'          => [
			'type'  => 'toggle',
			'label' => __( 'Bunny Fonts', 'wp-grid-builder' ),
			'help'  => __( 'GDPR-compliant fonts CDN (replace Google Fonts urls).', 'wp-grid-builder' ),
		],
	],
];

$general_facets = [
	'type'   => 'fieldset',
	'panel'  => 'general',
	'legend' => __( 'Facets', 'wp-grid-builder' ),
	'fields' => [
		'endpoint'              => [
			'type'    => 'button',
			'label'   => __( 'Request Endpoint', 'wp-grid-builder' ),
			'help'    => __( 'Using the custom endpoint or REST API will change performance. Filter Custom Content always uses the custom endpoint.', 'wp-grid-builder' ),
			'options' => [
				[
					'value' => '',
					'label' => _x( 'Custom', 'Request Endpoint', 'wp-grid-builder' ),
				],
				[
					'value' => 'rest_api',
					'label' => __( 'Rest API', 'wp-grid-builder' ),
				],
			],
		],
		'filter_custom_content' => [
			'type'  => 'toggle',
			'label' => __( 'Filter Custom Content', 'wp-grid-builder' ),
			'help'  => __( 'Filter archive results and custom WordPress queries.', 'wp-grid-builder' ),
		],
		'history'               => [
			'type'  => 'toggle',
			'label' => __( 'Browser’s History', 'wp-grid-builder' ),
			'help'  => __( 'Add parameters to the url when filtering.', 'wp-grid-builder' ),
		],
		'auto_index'            => [
			'type'  => 'toggle',
			'label' => __( 'Auto Indexing', 'wp-grid-builder' ),
			'help'  => __( 'Index facets when their parameters change.', 'wp-grid-builder' ),
		],
	],
];

$facet_styles = [
	'type'        => 'fieldset',
	'panel'       => 'styles',
	'legend'      => __( 'Facet Style', 'wp-grid-builder' ),
	'description' => __( 'Define a global style for all facets. This global style can be overriden for each facet from facet block, shortcode and widget.', 'wp-grid-builder' ),
	'fields'      => [
		'facet_styles' => [
			'type'        => 'select',
			'label'       => __( 'Global Style', 'wp-grid-builder' ),
			'placeholder' => _x( 'None', 'Facet style', 'wp-grid-builder' ),
			'async'       => 'wpgb/v2/objects?object=styles&orderby=name&order=asc&fields=id,name',
		],
	],
];

$colors_schemes = [
	'type'        => 'fieldset',
	'panel'       => 'styles',
	'legend'      => __( 'Color Schemes', 'wp-grid-builder' ),
	'description' => (
		__( 'Define palettes to manage all your colors in one place.', 'wp-grid-builder' ) . '<br>' .
		__( 'You can use the following color schemes in your cards and grids.', 'wp-grid-builder' )
	),
	'fields'      => [
		[
			'type'   => 'fieldset',
			'panel'  => 'styles',
			'legend' => __( 'Dark Schemes', 'wp-grid-builder' ),
			'fields' => [
				'row' => [
					'type'   => 'row',
					'fields' => [
						'dark_scheme_1' => [
							'type'  => 'color',
							'label' => __( 'Scheme 1', 'wp-grid-builder' ),
						],
						'dark_scheme_2' => [
							'type'  => 'color',
							'label' => __( 'Scheme 2', 'wp-grid-builder' ),
						],
						'dark_scheme_3' => [
							'type'  => 'color',
							'label' => __( 'Scheme 3', 'wp-grid-builder' ),
						],
					],
				],
			],
		],
		[
			'type'   => 'fieldset',
			'panel'  => 'styles',
			'legend' => __( 'Light Schemes', 'wp-grid-builder' ),
			'fields' => [
				'row' => [
					'type'   => 'row',
					'fields' => [
						'light_scheme_1' => [
							'type'  => 'color',
							'label' => __( 'Scheme 1', 'wp-grid-builder' ),
						],
						'light_scheme_2' => [
							'type'  => 'color',
							'label' => __( 'Scheme 2', 'wp-grid-builder' ),
						],
						'light_scheme_3' => [
							'type'  => 'color',
							'label' => __( 'Scheme 3', 'wp-grid-builder' ),
						],
					],
				],
			],
		],
		[
			'type'   => 'fieldset',
			'panel'  => 'styles',
			'legend' => __( 'Accent Scheme', 'wp-grid-builder' ),
			'fields' => [
				'accent_scheme_1' => [
					'type'  => 'color',
					'label' => __( 'Accent Color', 'wp-grid-builder' ),
				],
			],
		],
	],
];

$lightbox_plugin = [
	'type'        => 'fieldset',
	'panel'       => 'lightbox',
	'legend'      => __( 'Lightbox Plugin', 'wp-grid-builder' ),
	'description' => __( 'Define the lightbox plugin to be used to open media in grids.', 'wp-grid-builder' ),
	'fields'      => [
		'grid' => [
			'type'   => 'grid',
			'fields' => [
				'lightbox_plugin'     => [
					'type'        => 'select',
					'label'       => __( 'Open Media with', 'wp-grid-builder' ),
					'placeholder' => _x( 'None', 'Open Media With default value', 'wp-grid-builder' ),
					'options'     => [
						[
							'value' => 'wp_grid_builder',
							'label' => WPGB_NAME,
						],
						[
							'value'    => 'easy_fancybox',
							'label'    => 'Easy FancyBox',
							'disabled' => ! function_exists( 'is_plugin_active' ) || ! is_plugin_active( 'easy-fancybox/easy-fancybox.php' ),
						],
						[
							'value'    => 'modulobox_lite',
							'label'    => 'ModuloBox Lite',
							'disabled' => ! function_exists( 'is_plugin_active' ) || ! is_plugin_active( 'modulobox-lite/modulobox.php' ),
						],
						[
							'value'    => 'modulobox',
							'label'    => 'ModuloBox',
							'disabled' => ! function_exists( 'is_plugin_active' ) || ! is_plugin_active( 'modulobox/modulobox.php' ),
						],
						[
							'value'    => 'foobox',
							'label'    => 'FooBox V2',
							'disabled' => ! ( class_exists( 'FooBox' ) || class_exists( 'fooboxV2' ) ),
						],
					],
				],
				'lightbox_image_size' => [
					'type'        => 'select',
					'label'       => __( 'Image Size', 'wp-grid-builder' ),
					'isClearable' => false,
					'options'     => Helpers::format_options( Helpers::get_image_sizes() ),
				],
			],
		],
	],
];

$lightbox_caption = [
	'type'        => 'fieldset',
	'panel'       => 'lightbox',
	'legend'      => __( 'Caption', 'wp-grid-builder' ),
	'description' => __( 'Define the content displayed in the caption.', 'wp-grid-builder' ),
	'fields'      => [
		'grid' => [
			'type'   => 'grid',
			'fields' => [
				'lightbox_title'       => [
					'type'        => 'select',
					'label'       => __( 'Title', 'wp-grid-builder' ),
					'placeholder' => _x( 'None', 'Caption Title default value', 'wp-grid-builder' ),
					'options'     => [
						[
							'value' => 'title',
							'label' => __( 'Image Title', 'wp-grid-builder' ),
						],
						[
							'value' => 'caption',
							'label' => __( 'Image Caption', 'wp-grid-builder' ),
						],
						[
							'value' => 'alt',
							'label' => __( 'Image Alt Text', 'wp-grid-builder' ),
						],
						[
							'value' => 'description',
							'label' => __( 'Image Description', 'wp-grid-builder' ),
						],
					],
				],
				'lightbox_description' => [
					'type'        => 'select',
					'label'       => __( 'Description', 'wp-grid-builder' ),
					'placeholder' => _x( 'None', 'Caption Description default value', 'wp-grid-builder' ),
					'options'     => [
						[
							'value' => 'title',
							'label' => __( 'Image Title', 'wp-grid-builder' ),
						],
						[
							'value' => 'caption',
							'label' => __( 'Image Caption', 'wp-grid-builder' ),
						],
						[
							'value' => 'alt',
							'label' => __( 'Image Alt Text', 'wp-grid-builder' ),
						],
						[
							'value' => 'description',
							'label' => __( 'Image Description', 'wp-grid-builder' ),
						],
					],
				],
			],
		],
	],
	'condition'   => [
		[
			'field'   => 'lightbox_plugin',
			'compare' => '!==',
			'value'   => '',
		],
	],
];

$lightbox_colors = [
	'type'      => 'fieldset',
	'panel'     => 'lightbox',
	'legend'    => __( 'Colors', 'wp-grid-builder' ),
	'fields'    => [
		'grid'                => [
			'type'   => 'grid',
			'fields' => [
				'lightbox_controls_color' => [
					'type'  => 'color',
					'label' => __( 'Button Color', 'wp-grid-builder' ),
				],
				'lightbox_spinner_color'  => [
					'type'  => 'color',
					'label' => __( 'Spinner Color', 'wp-grid-builder' ),
				],
				'lightbox_title_color'    => [
					'type'  => 'color',
					'label' => __( 'Title Color', 'wp-grid-builder' ),
				],
				'lightbox_desc_color'     => [
					'type'  => 'color',
					'label' => __( 'Description Color', 'wp-grid-builder' ),
				],
			],
		],
		'lightbox_background' => [
			'type'     => 'color',
			'label'    => __( 'Background Color', 'wp-grid-builder' ),
			'help'     => __( 'Background color of the lightbox overlay.', 'wp-grid-builder' ),
			'gradient' => true,
		],
	],
	'condition' => [
		[
			'field'   => 'lightbox_plugin',
			'compare' => '===',
			'value'   => 'wp_grid_builder',
		],
	],
];

$lightbox_advanced = [
	'type'          => 'details',
	'panel'         => 'lightbox',
	'summaryOpened' => __( 'Hide advanced settings', 'wp-grid-builder' ),
	'summaryClosed' => __( 'Show advanced settings', 'wp-grid-builder' ),
	'fields'        => [
		'messages' => [
			'type'        => 'fieldset',
			'legend'      => __( 'Messages', 'wp-grid-builder' ),
			'description' => __( 'Messages used to provide labels to any assistive technologies.', 'wp-grid-builder' ),
			'fields'      => [
				'row'                      => [
					'type'   => 'row',
					'fields' => [
						'lightbox_previous_label' => [
							'type'        => 'text',
							'label'       => __( 'Prev Label', 'wp-grid-builder' ),
							'placeholder' => __( 'Previous slide', 'wp-grid-builder' ),
						],
						'lightbox_next_label'     => [
							'type'        => 'text',
							'label'       => __( 'Next Label', 'wp-grid-builder' ),
							'placeholder' => __( 'Next slide', 'wp-grid-builder' ),
						],
						'lightbox_close_label'    => [
							'type'        => 'text',
							'label'       => __( 'Close Label', 'wp-grid-builder' ),
							'placeholder' => __( 'Close lightbox', 'wp-grid-builder' ),
						],
					],
				],
				'lightbox_error_message'   => [
					'type'        => 'text',
					'label'       => __( 'Error Message', 'wp-grid-builder' ),
					'placeholder' => __( 'Sorry, an error occured while loading the content...', 'wp-grid-builder' ),
				],
				'lightbox_counter_message' => [
					'type'  => 'text',
					'label' => __( 'Counter', 'wp-grid-builder' ),
					'help'  => (
						__( '[index] : Number of the current slide.', 'wp-grid-builder' ) . '<br>' .
						__( '[total] : Number of slides opened in the gallery.', 'wp-grid-builder' )
					),
				],
			],
		],
	],
	'condition'     => [
		[
			'field'   => 'lightbox_plugin',
			'compare' => '===',
			'value'   => 'wp_grid_builder',
		],
	],
];

$advanced_assets = [
	'type'        => 'fieldset',
	'panel'       => 'advanced',
	'legend'      => __( 'Assets', 'wp-grid-builder' ),
	'description' => __( 'Manage assets behaviour.', 'wp-grid-builder' ),
	'fields'      => [
		'delete_plugin_assets' => [
			'type' => 'custom',
		],
		'load_polyfills'       => [
			'type'  => 'toggle',
			'label' => __( 'Load Polyfills', 'wp-grid-builder' ),
			'help'  => __( 'Loads an additional JS script to add support for older browsers.', 'wp-grid-builder' ),
		],
	],
];

$advanced_caching = [
	'type'        => 'fieldset',
	'panel'       => 'advanced',
	'legend'      => __( 'Caching', 'wp-grid-builder' ),
	'description' => __( 'Manage the cache generated by the plugin.', 'wp-grid-builder' ),
	'fields'      => [
		'clear_plugin_cache' => [
			'type' => 'custom',
		],
	],
];

$advanced_image_sizes = [
	'type'        => 'fieldset',
	'panel'       => 'advanced',
	'legend'      => __( 'Image Sizes', 'wp-grid-builder' ),
	'description' => __( 'Set additional image sizes for your WordPress site.', 'wp-grid-builder' ),
	'fields'      => [
		'image_sizes' => [
			'type'     => 'repeater',
			'addLabel' => __( 'Add Size', 'wp-grid-builder' ),
			'rowLabel' => '#%d - {{ width || 0 }}px × {{ height || 0 }}px',
			'maxRows'  => 20,
			'fields'   => [
				'row'  => [
					'type'   => 'row',
					'fields' => [
						'width'  => [
							'type'   => 'number',
							'label'  => __( 'Width', 'wp-grid-builder' ),
							'suffix' => 'px',
						],
						'height' => [
							'type'   => 'number',
							'label'  => __( 'Height', 'wp-grid-builder' ),
							'suffix' => 'px',
						],
					],
				],
				'crop' => [
					'type'  => 'toggle',
					'label' => __( 'Crop Image', 'wp-grid-builder' ),
				],
			],
		],
	],
];

$advanced_tip = [
	'type'    => 'tip',
	'panel'   => 'advanced',
	'content' => sprintf(
		'<strong>' . __( 'Image sizes are only generated when uploaded from the media library.', 'wp-grid-builder' ) . '</strong> ' .
		/* translators: 1: external url, 2: rel external */
		__( 'You can easily regenerate image sizes thanks to <a href="%1$s" rel="%2$s" target="_blank">Regenerate Thumbnails</a> Plugin.', 'wp-grid-builder' ),
		'https://wordpress.org/plugins/regenerate-thumbnails/',
		'external noopener noreferrer'
	),
];

return [
	'general_plugin'       => $general_plugin,
	'general_facets'       => $general_facets,
	'facet_styles'         => $facet_styles,
	'colors_schemes'       => $colors_schemes,
	'lightbox_plugin'      => $lightbox_plugin,
	'lightbox_caption'     => $lightbox_caption,
	'lightbox_colors'      => $lightbox_colors,
	'lightbox_advanced'    => $lightbox_advanced,
	'advanced_assets'      => $advanced_assets,
	'advanced_caching'     => $advanced_caching,
	'advanced_image_sizes' => $advanced_image_sizes,
	'advanced_tip'         => $advanced_tip,
];
