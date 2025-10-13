<?php
/**
 * Normalize
 *
 * @package   WP Grid Builder
 * @author    Loïc Blascos
 * @copyright 2019-2024 Loïc Blascos
 */

namespace WP_Grid_Builder\Includes\FrontEnd;

use WP_Grid_Builder\Includes\I18n;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Normalize grid settings
 *
 * @class WP_Grid_Builder\FrontEnd\Normalize
 * @since 1.0.0
 */
final class Normalize implements Interfaces\Normalize_Interface {

	/**
	 * Holds settings properties
	 *
	 * @since 1.0.0
	 * @access protected
	 *
	 * @var WP_Grid_Builder\FrontEnd\Settings instance
	 */
	protected $settings;

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param object Settings $settings Settings class instance.
	 */
	public function __construct( Settings $settings ) {

		$this->settings = $settings;

	}

	/**
	 * Parse settings
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function parse() {

		$this->settings->query();
		$this->default_settings();
		$this->default_thumb();
		$this->card_sizes();
		$this->animations();
		$this->lazy_load();
		$this->set_lang();

	}

	/**
	 * Normalize grid default settings
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function default_settings() {

		// Get default grid settings.
		$defaults = apply_filters( 'wp_grid_builder/defaults/grid', [] );

		$this->settings->properties = (object) array_merge(
			wp_parse_args( $this->settings->properties, $defaults ),
			wpgb_get_options()
		);
	}

	/**
	 * Get default thumbnail
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function default_thumb() {

		if ( empty( $this->settings->default_thumbnail ) ) {
			return;
		}

		$id    = $this->settings->default_thumbnail;
		$size  = wp_is_mobile() ? $this->settings->thumbnail_size_mobile : $this->settings->thumbnail_size;
		$thumb = wp_get_attachment_image_src( $id, $size, false );
		$full  = wp_get_attachment_image_src( $id, 'full' );

		$this->settings->default_thumbnail = [
			'title'       => '',
			'caption'     => '',
			'description' => '',
			'mime_type'   => get_post_mime_type( $id ),
			'alt'         => get_post_meta( $id, '_wp_attachment_image_alt', true ),
			'sizes'       => [
				'thumbnail' => [
					'url'    => isset( $thumb[0] ) ? $thumb[0] : null,
					'width'  => isset( $thumb[1] ) ? $thumb[1] : null,
					'height' => isset( $thumb[2] ) ? $thumb[2] : null,
				],
				'full'      => [
					'url'    => isset( $full[0] ) ? $full[0] : null,
					'width'  => isset( $full[1] ) ? $full[1] : null,
					'height' => isset( $full[2] ) ? $full[2] : null,
				],
			],
		];
	}

	/**
	 * Normalize card sizes
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function card_sizes() {

		$sizes  = [];
		$gutter = 0;

		foreach ( (array) $this->settings->card_sizes as $index => $size ) {

			if ( 0 === $index && empty( $size['browser'] ) ) {
				$size['browser'] = 9999;
			}

			if ( empty( $size['browser'] ) ) {
				continue;
			}

			$sizes[ (int) $size['browser'] ] = [
				'columns' => (int) ( $size['columns'] ?? 1 ) ?: 1,
				'height'  => (int) ( $size['height'] ?? 120 ) ?: 120,
				'gutter'  => (int) ( $size['gutter'] ?? 0 ) ?: 0,
				'ratio'   => (string) number_format(
					max( 1, (int) ( $size['ratio']['x'] ?? 4 ) ) /
					max( 1, (int) ( $size['ratio']['y'] ?? 3 ) ),
					5
				),
			];
		}

		krsort( $sizes );

		// Assign previous getter recursively.
		foreach ( $sizes as $browser => &$size ) {

			if ( $size['gutter'] > -1 ) {
				$gutter = $size['gutter'];
			}

			$size['gutter'] = $gutter;

		}

		$this->settings->card_sizes = $sizes;

	}

	/**
	 * Normalize animation and transition
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function animations() {

		$animation = $this->settings->animation;

		if ( empty( $animation ) ) {
			return;
		}

		$animations = apply_filters( 'wp_grid_builder/animations', [] );

		if ( empty( $animations[ $animation ] ) ) {
			return;
		}

		$animation = $animations[ $animation ];

		if ( ! isset( $animation['visible'], $animation['hidden'] ) ) {
			return;
		}

		$hidden  = '';
		$visible = '';

		if ( 'custom' === $this->settings->timing_function ) {
			$this->settings->timing_function = $this->settings->cubic_bezier_function;
		}

		$animation['visible']['transition'] = absint( $this->settings->transition ) . 'ms ' . $this->settings->timing_function;
		$animation['visible']['transition-property'] = 'transform, opacity;';

		foreach ( $animation['hidden'] as $prop => $val ) {
			$hidden .= sanitize_key( $prop ) . ':' . esc_attr( $val ) . ';';
		}

		foreach ( $animation['visible'] as $prop => $val ) {
			$visible .= sanitize_key( $prop ) . ':' . esc_attr( $val ) . ';';
		}

		$this->settings->reveal = 1;
		$this->settings->animation = [
			'hidden'  => $hidden,
			'visible' => $visible,
		];
	}

	/**
	 * Check lazy load compatibility
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function lazy_load() {

		// Remove lazy load if AMP page (with AMP for WordPress plugin).
		if ( function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() ) {
			$this->settings->lazy_load = false;
		}
	}

	/**
	 * Set lang parameter to properly query
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function set_lang() {

		if ( empty( $this->settings->lang ) ) {
			$this->settings->lang = I18n::current_lang();
		}
	}
}
