<?php
/**
 * Controls
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
 * Settings controls
 *
 * @class WP_Grid_Builder\Includes\Settings\Controls
 * @since 2.0.0
 */
class Controls {

	use Sanitize;

	/**
	 * Holds registered controls.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	private $controls = [];

	/**
	 * Constructor
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function __construct() {

		add_filter( 'wp_grid_builder/controls', [ $this, 'register' ] );

	}

	/**
	 * Register controls
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param  array $controls Holds registered controls.
	 * @return array
	 */
	public function register( $controls ) {

		return array_merge(
			$controls,
			$this->default_controls(),
			$this->border_control(),
			$this->box_control(),
			$this->box_shadow_control(),
			$this->clause_control(),
			$this->condition_control(),
			$this->text_shadow_control(),
			$this->typography_control()
		);
	}

	/**
	 * Default control definitions
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return array
	 */
	public function default_controls() {

		return [
			'button'   => [ 'sanitize_callback' => [ $this, 'sanitize_choices' ] ],
			'checkbox' => [ 'sanitize_callback' => [ $this, 'sanitize_choices' ] ],
			'code'     => [ 'sanitize_callback' => [ $this, 'sanitize_code' ] ],
			'color'    => [ 'sanitize_callback' => [ $this, 'sanitize_color' ] ],
			'date'     => [ 'sanitize_callback' => [ $this, 'sanitize_date' ] ],
			'email'    => [ 'sanitize_callback' => [ $this, 'sanitize_email' ] ],
			'file'     => [ 'sanitize_callback' => [ $this, 'sanitize_file' ] ],
			'fonts'    => [ 'sanitize_callback' => [ $this, 'sanitize_fonts' ] ],
			'gallery'  => [ 'sanitize_callback' => [ $this, 'sanitize_integers' ] ],
			'icon'     => [ 'sanitize_callback' => [ $this, 'sanitize_text' ] ],
			'image'    => [ 'sanitize_callback' => [ $this, 'sanitize_integer' ] ],
			'input'    => [ 'sanitize_callback' => [ $this, 'sanitize_text' ] ],
			'number'   => [ 'sanitize_callback' => [ $this, 'sanitize_number' ] ],
			'password' => [ 'sanitize_callback' => [ $this, 'sanitize_password' ] ],
			'radio'    => [ 'sanitize_callback' => [ $this, 'sanitize_choices' ] ],
			'range'    => [ 'sanitize_callback' => [ $this, 'sanitize_number' ] ],
			'select'   => [ 'sanitize_callback' => [ $this, 'sanitize_choices' ] ],
			'text'     => [ 'sanitize_callback' => [ $this, 'sanitize_text' ] ],
			'toggle'   => [ 'sanitize_callback' => [ $this, 'sanitize_boolean' ] ],
			'upload'   => [ 'sanitize_callback' => [ $this, 'sanitize_file' ] ],
			'url'      => [ 'sanitize_callback' => [ $this, 'sanitize_url' ] ],
			// Nested controls.
			'builder'  => [],
			'group'    => [],
			'repeater' => [],
		];
	}

	/**
	 * Border control definitions
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return array
	 */
	public function border_control() {

		return [
			'border' => [
				'fields' => array_map(
					function() {

						return [
							'fields' => [
								'color' => [ 'type' => 'color' ],
								'style' => [ 'type' => 'select' ],
								'width' => [
									'type'  => 'number',
									'units' => [],
								],
							],
						];
					},
					array_flip( [ 'bottom', 'left', 'right', 'top' ] )
				),
			],
		];
	}

	/**
	 * Box control definitions
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return array
	 */
	public function box_control() {

		$sides = array_map(
			function() {

				return [
					'type'  => 'number',
					'units' => [],
				];
			},
			array_flip( [ 'bottom', 'left', 'right', 'top' ] )
		);

		return [
			'box'     => [ 'fields' => $sides ],
			'margin'  => [ 'fields' => $sides ],
			'padding' => [ 'fields' => $sides ],
			'radius'  => [ 'fields' => $sides ],
		];
	}

	/**
	 * Box-shadow control definitions
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return array
	 */
	public function box_shadow_control() {

		return [
			'box-shadow' => [
				'fields' => [
					'color'  => [ 'type' => 'color' ],
					'type'   => [ 'type' => 'text' ],
					'blur'   => [
						'type'  => 'number',
						'units' => [],
					],
					'spread' => [
						'type'  => 'number',
						'units' => [],
					],
					'x'      => [
						'type'  => 'number',
						'units' => [],
					],
					'y'      => [
						'type'  => 'number',
						'units' => [],
					],
				],
			],
		];
	}

	/**
	 * Clause control definitions
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return array
	 */
	public function clause_control() {

		return [
			'clause' => [
				'fields' => [
					'relation' => [ 'type' => 'text' ],
				],
			],
		];
	}

	/**
	 * Condition control definitions
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return array
	 */
	public function condition_control() {

		return [
			'condition' => [
				'fields' => [
					'key'      => [ 'type' => 'text' ],
					'relation' => [ 'type' => 'text' ],
					'field'    => [ 'type' => 'select' ],
					'value'    => [ 'type' => 'select' ],
					'compare'  => [
						'type'    => 'select',
						'options' => [
							[ 'value' => '==' ],
							[ 'value' => '!=' ],
							[ 'value' => '>' ],
							[ 'value' => '<' ],
							[ 'value' => '>=' ],
							[ 'value' => '<=' ],
							[ 'value' => 'IN' ],
							[ 'value' => 'NOT IN' ],
							[ 'value' => 'CONTAINS' ],
							[ 'value' => 'NOT CONTAINS' ],
						],
					],
				],
			],
		];
	}

	/**
	 * Text-shadow control definitions
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return array
	 */
	public function text_shadow_control() {

		return [
			'text-shadow' => [
				'fields' => [
					'color' => [ 'type' => 'color' ],
					'type'  => [ 'type' => 'text' ],
					'blur'  => [
						'type'  => 'number',
						'units' => [],
					],
					'x'     => [
						'type'  => 'number',
						'units' => [],
					],
					'y'     => [
						'type'  => 'number',
						'units' => [],
					],
				],
			],
		];
	}

	/**
	 * Typography control definitions
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return array
	 */
	public function typography_control() {

		return [
			'typography' => [
				'fields' => [
					'font-family'     => [ 'type' => 'text' ],
					'font-weight'     => [ 'type' => 'text' ],
					'font-style'      => [ 'type' => 'text' ],
					'text-decoration' => [ 'type' => 'text' ],
					'text-transform'  => [ 'type' => 'text' ],
					'text-align'      => [ 'type' => 'text' ],
					'font-size'       => [
						'type'  => 'number',
						'units' => [],
					],
					'line-height'     => [
						'type'  => 'number',
						'units' => [],
					],
					'letter-spacing'  => [
						'type'  => 'number',
						'units' => [],
					],
				],
			],
		];
	}

	/**
	 * Build controls panels
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param array $panels   Holds control panels.
	 * @param array $controls Holds controls.
	 * @return array
	 */
	public function build( $panels, $controls ) {

		$panel_controls = [];

		foreach ( $controls as $name => $control ) {

			if ( isset( $control['panel'] ) ) {
				$panel_controls[ $control['panel'] ][ $name ] = $control;
			}
		}

		foreach ( $panels as &$panel ) {

			$panel['type'] = 'tab';

			if ( isset( $panel['name'], $panel_controls[ $panel['name'] ] ) ) {
				$panel['fields'] = $panel_controls[ $panel['name'] ];
			}
		}

		return [
			'type' => 'tab-panel',
			'tabs' => $panels,
		];
	}

	/**
	 * Normalize registered control.
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param  array  $control Holds control arguments.
	 * @param  string $name    Control name.
	 * @return array
	 */
	public function normalize( $control, $name ) {

		if ( ! is_array( $control ) || empty( $control['type'] ) || ! is_scalar( $control['type'] ) ) {
			return [];
		}

		// Remove not allowed key.
		unset( $control['_layout'] );

		if ( empty( $this->controls ) ) {
			$this->controls = apply_filters( 'wp_grid_builder/controls', [] );
		}

		// Define the name from the key if it doesn't exist.
		if ( empty( $control['name'] ) ) {
			$control['name'] = $name;
		}

		$is_registered = isset( $this->controls[ $control['type'] ] );

		// If it is not registered but has subfields.
		if ( ! $is_registered && ! empty( $control['fields'] ) ) {
			return [ '_layout' => $control['fields'] ];
		}

		// If it is not registered and has no subfields.
		if ( ! $is_registered ) {
			return [];
		}

		$registered = $this->controls[ $control['type'] ];

		if ( ! empty( $registered['sanitize_callback'] ) ) {
			$control['sanitize_callback'] = $registered['sanitize_callback'];
		}

		if ( empty( $registered['fields'] ) ) {
			return $control;
		}

		if ( empty( $control['fields'] ) ) {
			$control['fields'] = [];
		}

		$control['fields'] = wp_parse_args( $control['fields'], $registered['fields'] );

		return $control;

	}

	/**
	 * Format registered control value
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param  mixed $value   Value to format.
	 * @param  array $control Holds control arguments.
	 * @return mixed
	 */
	public function format( $value, $control ) {

		if ( empty( $control['sanitize_callback'] ) ) {
			return null;
		}

		$sanitize_callback = $control['sanitize_callback'];

		if ( ! is_callable( $sanitize_callback ) ) {
			return null;
		}

		// At this stage, the callback has already been validated during control normalization (see above).
		return call_user_func( $sanitize_callback, $value, $control );

	}
}
