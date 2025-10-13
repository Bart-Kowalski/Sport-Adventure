<?php
/**
 * Options defaults
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
	// General.
	'uninstall'                => 0,
	'post_formats_support'     => 0,
	'post_meta'                => 0,
	'term_meta'                => 1,
	'render_blocks'            => 0,
	'bunny_fonts'              => 0,
	'endpoint'                 => '',
	'filter_custom_content'    => 0,
	'history'                  => 1,
	'auto_index'               => 1,
	// Colors.
	'dark_scheme_1'            => '#262626',
	'dark_scheme_2'            => '#565656',
	'dark_scheme_3'            => '#767676',
	'light_scheme_1'           => '#ffffff',
	'light_scheme_2'           => '#f6f6f6',
	'light_scheme_3'           => '#f5f5f5',
	'accent_scheme_1'          => '#0069ff',
	// Lightbox.
	'lightbox_plugin'          => 'wp_grid_builder',
	'lightbox_image_size'      => 'full',
	'lightbox_title'           => 'title',
	'lightbox_description'     => 'caption',
	'lightbox_controls_color'  => '#ffffff',
	'lightbox_spinner_color'   => '#ffffff',
	'lightbox_title_color'     => '#ffffff',
	'lightbox_desc_color'      => '#bbbbbb',
	'lightbox_background'      => 'linear-gradient(180deg, rgba(30,30,30,0.45) 0%, rgba(30,30,30,0.9) 100%)',
	'lightbox_previous_label'  => '',
	'lightbox_next_label'      => '',
	'lightbox_close_label'     => '',
	'lightbox_error_message'   => '',
	'lightbox_counter_message' => '[index] / [total]',
	// Advanced.
	'load_polyfills'           => 1,
	'image_sizes'              => [],
];
