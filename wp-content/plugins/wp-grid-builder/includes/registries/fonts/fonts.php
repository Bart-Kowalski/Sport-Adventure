<?php
/**
 * Fonts
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

$defaults = [
	[
		'label' => 'Arial, Helvetica',
		'value' => 'Arial, Helvetica, sans-serif',
	],
	[
		'label' => 'Arial Black',
		'value' => "'Arial Black', Gadget, sans-serif",
	],
	[
		'label' => 'Comic Sans MS',
		'value' => "'Comic Sans MS', cursive, sans-serif",
	],
	[
		'label' => 'Courier New',
		'value' => "'Courier New', Courier, monospace",
	],
	[
		'label' => 'Georgia, serif',
		'value' => 'Georgia, serif',
	],
	[
		'label' => 'Impact',
		'value' => 'Impact, Charcoal, sans-serif',
	],
	[
		'label' => 'Lucida Console',
		'value' => "'Lucida Console', Monaco, monospace",
	],
	[
		'label' => 'Lucida Sans Unicode',
		'value' => "'Lucida Sans Unicode', 'Lucida Grande', sans-serif",
	],
	[
		'label' => 'Palatino Linotype',
		'value' => "'Palatino Linotype', 'Book Antiqua', Palatino, serif",
	],
	[
		'label' => 'Tahoma',
		'value' => 'Tahoma, Geneva, sans-serif',
	],
	[
		'label' => 'Times New Roman',
		'value' => "'Times New Roman', Times, serif",
	],
	[
		'label' => 'Trebuchet MS',
		'value' => "'Trebuchet MS', Helvetica, sans-serif",
	],
	[
		'label' => 'Verdana',
		'value' => 'Verdana, Geneva, sans-serif',
	],
];

$google_fonts = Helpers::file_get_contents( 'admin/json/fonts.json' );
$google_fonts = json_decode( $google_fonts, true );

return [
	'default' => [
		'label'   => __( 'Default Fonts', 'wp-grid-builder' ),
		'options' => array_map(
			function( $font ) {

				$font['variants'] = [ 100, 200, 300, 400, 500, 600, 700, 800, 900 ];
				return $font;

			},
			$defaults
		),
	],
	'google'  => [
		'label'   => 'Google Fonts',
		'options' => array_map(
			function( $key, $font ) {

				return [
					'label'    => $key,
					'value'    => "'" . $key . "'",
					'subsets'  => $font['subsets'],
					'variants' => $font['variants'],
				];
			},
			array_keys( (array) $google_fonts ),
			(array) $google_fonts
		),
	],
];
