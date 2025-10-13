<?php
/**
 * Settings
 *
 * @package   WP Grid Builder
 * @author    Loïc Blascos
 * @copyright 2019-2024 Loïc Blascos
 */

namespace WP_Grid_Builder\Includes\Settings;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Setting fields API
 *
 * @class WP_Grid_Builder\Includes\Settings\Settings
 * @since 2.0.0
 */
class Settings extends Controls {

	/**
	 * Get defaults settings values
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param string $id Controls ID.
	 * @return array
	 */
	public function defaults( $id = '' ) {

		return (array) apply_filters( 'wp_grid_builder/defaults/' . $id, [] );

	}

	/**
	 * Generate setting controls for display
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param string $id Controls ID.
	 * @return array
	 */
	public function generate( $id ) {

		$controls = (array) apply_filters( 'wp_grid_builder/controls/' . $id, [] );
		$panel    = (array) apply_filters( 'wp_grid_builder/panels/' . $id, [] );
		$tabs     = (array) apply_filters( 'wp_grid_builder/tabs/' . $id, [] );

		if ( empty( $tabs ) ) {
			return $controls;
		}

		if ( empty( $controls ) ) {
			return [ wp_parse_args( $panel, $tabs ) ];
		}

		return [ wp_parse_args( $panel, $this->build( $tabs, $controls ) ) ];

	}

	/**
	 * Sanitize setting fields
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param string $id       Controls ID.
	 * @param array  $values   Holds values to sanitize.
	 *
	 * @return array
	 */
	public function sanitize( $id = '', $values = [] ) {

		if ( empty( $values ) ) {
			return [];
		}

		$controls = (array) apply_filters( 'wp_grid_builder/controls/' . $id, [] );
		$controls = $this->parse( $controls );

		if ( empty( $controls ) ) {
			return [];
		}

		return $this->prepare( $values, $controls );

	}

	/**
	 * Parse and flatten controls
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param  array $controls Holds controls.
	 * @return array
	 */
	public function parse( $controls ) {

		$parsed = [];

		foreach ( (array) $controls as $name => $control ) {

			// Normalize control.
			$control = $this->normalize( $control, $name );

			// Only parse valid control.
			if ( empty( $control ) ) {

				// Recursively get nested controls.
				if ( is_array( $controls ) ) {
					$parsed = array_merge( $parsed, $this->parse( $controls[ $name ] ) );
				}

				continue;

			}

			// Parse layout controls.
			if ( ! empty( $control['_layout'] ) ) {

				$parsed = array_merge( $parsed, $this->parse( $control['_layout'] ) );
				continue;

			}

			// Only parse control with a valid name as key.
			if ( ! is_string( $control['name'] ) ) {
				continue;
			}

			// Parse nested controls (repeater, clause, group, etc.).
			if ( ! empty( $control['fields'] ) ) {

				$parsed[ $control['name'] ] = array_merge( $control, [ 'fields' => $this->parse( $control['fields'] ) ] );
				continue;

			}

			$parsed[ $control['name'] ] = $control;

		}

		return $parsed;

	}

	/**
	 * Prepare values to be inserted into the database
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param array $values   Holds values to sanitize.
	 * @param array $controls Holds controls to check against.
	 *
	 * @return array
	 */
	public function prepare( $values = [], $controls = [] ) {

		$prepared = [];

		foreach ( (array) $values as $key => $value ) {

			// Recursively prepare nested controls (repeater, clause, group, etc.).
			if ( ! empty( $controls[ $key ]['fields'] ) ) {

				$prepared[ $key ] = $this->prepare( $value, $controls[ $key ]['fields'] );
				continue;

			}

			// Format control value if it exists.
			if ( isset( $controls[ $key ] ) ) {

				$value = $this->format( $value, $controls[ $key ] );

				if ( null !== $value ) {
					$prepared[ $key ] = $value;
				}

				continue;

			}

			// If the control does not exist and does not contain nested controls.
			if ( ! is_array( $value ) ) {
				continue;
			}

			// Recursively prepare nested values.
			$prepared[ sanitize_key( $key ) ] = $this->prepare( $value, $controls );

		}

		return $prepared;

	}
}
