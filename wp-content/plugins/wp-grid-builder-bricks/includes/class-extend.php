<?php
/**
 * Extend module controls
 *
 * @package   WP Grid Builder - Bricks
 * @author    Loïc Blascos
 * @copyright 2019-2025 Loïc Blascos
 */

namespace WP_Grid_Builder_Bricks\Includes;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Extend
 *
 * @class WP_Grid_Builder_Bricks\Includes\Extend
 * @since 1.0.0
 */
final class Extend {

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {

		$providers = apply_filters( 'wp_grid_builder_bricks/providers', [] );

		foreach ( $providers as $provider => $class ) {

			add_filter( 'bricks/elements/' . $provider . '/control_groups', [ $this, 'add_control_group' ], 10, 2 );
			add_filter( 'bricks/elements/' . $provider . '/controls', [ $this, 'add_controls' ], 10, 2 );

		}
	}

	/**
	 * Add control groups to providers
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $control_groups Hold control groups.
	 * @return array
	 */
	public function add_control_group( $control_groups ) {

		$control_groups['wp_grid_builder'] = [
			'tab'      => 'content',
			'title'    => esc_html__( 'WP Grid Builder', 'wpgb-bricks' ),
			'required' => [ 'hasLoop', '=', true ],
		];

		return $control_groups;

	}

	/**
	 * Add controls to providers
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $controls Hold controls.
	 * @return array
	 */
	public function add_controls( $controls ) {

		$controls['wpgb_fading'] = [
			'tab'     => 'content',
			'group'   => 'wp_grid_builder',
			'label'   => __( 'Fading Animation', 'wpgb-bricks' ),
			'type'    => 'checkbox',
			'inline'  => true,
			'small'   => true,
			'default' => false,
			'css'     => [
				[
					'selector' => '',
					'property' => 'transition',
					'value'    => 'opacity 0.35s ease',
				],
				[
					'selector' => '&.wpgb-loading',
					'property' => 'opacity',
					'value'    => '0.35',
				],
			],
		];

		return $controls;

	}
}
