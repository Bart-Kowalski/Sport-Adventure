<?php
/**
 * Colors
 *
 * @package   WP Grid Builder
 * @author    Loïc Blascos
 * @copyright 2019-2024 Loïc Blascos
 */

namespace WP_Grid_Builder\Includes\FrontEnd;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Generate global color schemes
 *
 * @class WP_Grid_Builder\Includes\FrontEnd\Colors
 * @since 1.1.5
 */
final class Colors {

	/**
	 * Generate CSS variables for colors
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return string
	 */
	public function generate() {

		$variables = (
			$this->schemes() .
			$this->lightbox()
		);

		if ( empty( $variables ) ) {
			return '';
		}

		return ':root{' . $variables . '}';

	}

	/**
	 * Generate CSS schemes
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return string
	 */
	public function generate_schemes() {

		$variables = $this->schemes();

		if ( empty( $variables ) ) {
			return '';
		}

		return ':root{' . $variables . '}';

	}

	/**
	 * Generate lightbox
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return string
	 */
	public function generate_lightbox() {

		$variables = $this->lightbox();

		if ( empty( $variables ) ) {
			return '';
		}

		return ':root{' . $variables . '}';

	}

	/**
	 * Generate global color schemes
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function schemes() {

		$variables = [
			'accent-scheme-1',
			'dark-scheme-1',
			'dark-scheme-2',
			'dark-scheme-3',
			'light-scheme-1',
			'light-scheme-2',
			'light-scheme-3',
		];

		$variables = array_reduce(
			$variables,
			function( $carry, $variable ) {

				$value = wpgb_get_option( str_replace( '-', '_', $variable ) );

				if ( empty( $value ) ) {
					return $carry;
				}

				return $carry . '--wpgb-' . $variable . ':' . esc_attr( $value ) . ';';

			},
			''
		);

		return $variables;

	}

	/**
	 * Generate lightbox colors
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function lightbox() {

		if ( 'wp_grid_builder' !== wpgb_get_option( 'lightbox_plugin' ) ) {
			return '';
		}

		$options = [
			'--wpgb-lightbox-background' => 'lightbox_background',
			'--wpgb-lightbox-controls'   => 'lightbox_controls_color',
			'--wpgb-lightbox-spinner'    => 'lightbox_spinner_color',
			'--wpgb-lightbox-title'      => 'lightbox_title_color',
			'--wpgb-lightbox-desc'       => 'lightbox_desc_color',
		];

		array_walk(
			$options,
			function( $option, $variable ) use ( &$variables ) {

				$value = wpgb_get_option( $option );

				if ( empty( $value ) ) {
					return;
				}

				$variables .= $variable . ':' . esc_attr( $value ) . ';';
			}
		);

		return $variables;

	}
}
