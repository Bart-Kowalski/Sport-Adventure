<?php
/**
 * Trigger Widget.
 *
 * @package Frames_Client
 */

namespace Frames_Client\Widgets\Trigger;

use \Frames_Client\Widget_Manager;

/**
 * Trigger class.
 */
class Trigger_Widget extends \Bricks\Element {
	/**
	 * Use predefined element category 'general'.
	 *
	 * @var string
	 */
	public $category = 'Frames';

	/**
	 * Make sure to prefix your elements.
	 *
	 * @var string
	 */
	public $name = 'fr-trigger';

	/**
	 * Themify icon font class.
	 *
	 * @var string
	 */
	public $icon = 'fas fa-bars';

	/**
	 * Default CSS selector.
	 *
	 * @var string
	 */
	public $css_selector = '.trigger-wrapper';

	/**
	 * Scripts to be enqueued.
	 *
	 * @var array
	 */
	public $scripts = array( 'trigger_script' );

	/**
	 * Is nestable.
	 *
	 * @var boolean
	 */
	public $nestable = false;

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function get_methods() {
		 include( 'inc/trigger-functions.php' );
	}

	/**
	 * Get widget label.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget Label.
	 */
	public function get_label() {
		return esc_html__( 'Frames Trigger', 'frames' );
	}

	/**
	 * Register widget controls.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	public function set_controls() {
		/**
		 *  Here you can add your controls for your widget.
		 *  Check this: https://academy.bricksbuilder.io/topic/controls/
		 */

		$this->controls['triggerType'] = array(
			'label' => esc_html__( 'Type of trigger', 'frames' ),
			'type' => 'select',
			'options' => array(
				'burger'  => 'Burger',
				'button'  => 'Button',
			),
			'inline' => true,
			'default' => 'burger',
		);

		// Burger Trigger.

		$this->controls['burgerSeperator'] = array(
			'label'    => esc_html__( 'Burger Trigger', 'bricks' ),
			'type'     => 'separator',
			'required' => array( 'triggerType', '=', 'burger' ),
		);

		$this->controls['animation'] = array(
			'label' => esc_html__( 'Animation', 'frames' ),
			'type' => 'select',
			'options' => array(
				'fr-hamburger--3dx'  => '3DX',
				'fr-hamburger--3dx-r'  => '3DX Reversed',
				'fr-hamburger--3dy'  => '3DY',
				'fr-hamburger--3dy-r'  => '3DY Reversed',
				'fr-hamburger--3dxy'  => '3DXY',
				'fr-hamburger--3dxy-r'  => '3DXY Reversed',
				'fr-hamburger--arrow'  => 'Arrow',
				'fr-hamburger--arrow-r'  => 'Arrow Reversed',
				'fr-hamburger--arrowalt'  => 'Arrow Alternative',
				'fr-hamburger--arrowalt-r'  => 'Arrow Alternative Right',
				'fr-hamburger--arrowturn'  => 'Arrow Turn',
				'fr-hamburger--arrowturn-r'  => 'Arrow Turn Right',
				'fr-hamburger--boring'  => 'Boring',
				'fr-hamburger--collapse'  => 'Collapse',
				'fr-hamburger--collapse-r'  => 'Collapse Reversed',
				'fr-hamburger--elastic'  => 'Elastic',
				'fr-hamburger--elastic-r'  => 'Elastic Reversed',
				'fr-hamburger--emphatic'  => 'Emphatic',
				'fr-hamburger--emphatic-r'  => 'Emphatic Reversed',
				'fr-hamburger--minus'  => 'Minus',
				'fr-hamburger--slider'  => 'Slider',
				'fr-hamburger--slider-r'  => 'Slider Reversed',
				'fr-hamburger--spin'  => 'Spin',
				'fr-hamburger--spring'  => 'Spring',
				'fr-hamburger--spring-r'  => 'Spring Reserved',
				'fr-hamburger--stand'  => 'Stand',
				'fr-hamburger--stand-r'  => 'Stand Reserved',
				'fr-hamburger--squeeze'  => 'Squeeze',
				'fr-hamburger--vortex'  => 'Vortex',
				'fr-hamburger--vortex-r'  => 'Vortex Reserved',
			),
			'inline' => true,
			'default' => 'fr-hamburger--elastic',
			'required' => array( 'triggerType', '=', 'burger' ),
		);

		$this->controls['styleSeperator'] = array(
			'label'    => esc_html__( 'Styles', 'bricks' ),
			'type'     => 'separator',
			'required' => array( 'triggerType', '=', 'burger' ),
		);

		$this->controls['padding'] =
			array(
				'label' => __( 'Padding', 'frames' ),
				'type' => 'spacing',
				'css' => array(
					array(
						'property' => 'padding',
						'selector' => '',
					),
				),
				'required' => array( 'triggerType', '=', 'burger' ),
			);

			$this->controls['triggerSize'] =
			array(
				'label' => __( 'Trigger size', 'frames' ),
				'type' => 'number',
				'min' => 0,
				'max' => 9999,
				'step' => 1,
				'units' => false,
				'inline' => true,
				'css'   => array(
					array(
						'property' => '--fr-hamburger-scale',
						'selector' => '',
					),
				),
				'required' => array( 'triggerType', '=', 'burger' ),
			);

			$this->controls['backgroundColor'] =
			array(
				'label' => __( 'Background Color', 'frames' ),
				'type' => 'color',
				'css'   => array(
					array(
						'property' => 'background-color',
						'selector' => '',
					),
				),
				'required' => array( 'triggerType', '=', 'burger' ),
			);

			$this->controls['burgerBorder'] = array(
				'label' => __( 'Border', 'frames' ),
				'type' => 'border',
				'css' => array(
					array(
						'property' => 'border',
						'selector' => '',
					),
				),
				'required' => array( 'triggerType', '=', 'burger' ),
			);

			$this->controls['lineHeight'] =
			array(
				'label' => __( 'Burger lines height', 'frames' ),
				'type' => 'number',
				'min' => 0,
				'max' => 9999,
				'step' => 1,
				'units' => true,
				'inline' => true,
				'css'   => array(
					array(
						'property' => '--fr-hamburger-line-height',
						'selector' => '',
					),
				),
				'required' => array( 'triggerType', '=', 'burger' ),
			);

			$this->controls['lineColor'] =
			array(

				'label' => __( 'Lines Color', 'frames' ),
				'type' => 'color',
				'css'   => array(
					array(
						'property' => '--fr-hamburger-line-color',
						'selector' => '',
					),
				),
				'required' => array( 'triggerType', '=', 'burger' ),
			);

			$this->controls['activeStyleSeperatr'] = array(
				'label'    => esc_html__( 'Active Styles', 'bricks' ),
				'type'     => 'separator',
				'required' => array( 'triggerType', '=', 'burger' ),
			);

			$this->controls['backgroundColorActive'] =
			array(
				'label' => __( 'Background Color', 'frames' ),
				'type' => 'color',
				'css'   => array(
					array(
						'property' => '--fr-hamburger-background-color-active',
						'selector' => '',
					),
				),
				'required' => array( 'triggerType', '=', 'burger' ),
			);

			$this->controls['burgerBorderActive'] = array(
				'label' => __( 'Border (Active)', 'frames' ),
				'type' => 'border',
				'css' => array(
					array(
						'property' => 'border',
						'selector' => '&.fr-hamburger--active',
					),
				),
				'required' => array( 'triggerType', '=', 'burger' ),
			);

			$this->controls['activeLinesColor'] =
			array(
				'label' => __( 'Active Lines Color', 'frames' ),
				'type' => 'color',
				'css'   => array(
					array(
						'property' => '--fr-hamburger-line-color-active',
						'selector' => '',
					),
				),
				'required' => array( 'triggerType', '=', 'burger' ),
			);

			$this->controls['triggerSizeActive'] =
			array(
				'label' => __( 'Trigger size', 'frames' ),
				'type' => 'number',
				'min' => 0,
				'max' => 9999,
				'step' => 1,
				'units' => false,
				'inline' => true,
				'css'   => array(
					array(
						'property' => '--fr-hamburger-scale-active',
						'selector' => '',
					),
				),
				'required' => array( 'triggerType', '=', 'burger' ),
			);

			$this->controls['addText'] =
			array(
				'label' => __( 'Add Text', 'frames' ),
				'type' => 'checkbox',
				'inline' => true,
				'default' => false,
				'required' => array( 'triggerType', '=', 'burger' ),
			);

			$this->controls['triggerText'] =
			array(
				'label' => __( 'Trigger Text', 'frames' ),
				'type' => 'text',
				'default' => 'Menu',
				'inlineEditing' => true,
				'required' => array(
					array( 'addText', '=', true ),
					array( 'triggerType', '=', 'burger' ),
				),
			);

			$this->controls['hamburgerDirection'] =
			array(
				'label' => __( 'Text Position', 'frames' ),
				'type' => 'direction',
				'required' => array(
					array( 'addText', '=', true ),
					array( 'triggerType', '=', 'burger' ),
				),
				'css' => array(
					array(
						'property' => 'flex-direction',
						'selector' => '',
					),
				),
			);

			$this->controls['gap'] =
			array(
				'label' => __( 'Gap', 'frames' ),
				'type' => 'number',
				'min' => 0,
				'max' => 99999,
				'step' => 1,
				'units' => true,
				'inline' => true,
				'required' => array(
					array( 'addText', '=', true ),
					array( 'triggerType', '=', 'burger' ),
				),
				'css'   => array(
					array(
						'property' => 'gap',
						'selector' => '',
					),
				),
			);

			$this->controls['textColor'] = array(
				'label' => __( 'Text Color', 'frames' ),
				'type' => 'color',
				'required' => array(
					array( 'addText', '=', true ),
					array( 'triggerType', '=', 'burger' ),
				),
				'css'   => array(
					array(
						'property' => '--fr-hamburger-text-color',
						'selector' => '',
					),
				),
			);

			$this->controls['textColorActive'] = array(
				'label' => __( 'Text Color Active', 'frames' ),
				'type' => 'color',
				'required' => array(
					array( 'addText', '=', true ),
					array( 'triggerType', '=', 'burger' ),
				),
				'css'   => array(
					array(
						'property' => '--fr-hamburger-text-color-active',
						'selector' => '',
					),
				),
			);

			// button trigger.

			$this->controls['buttonSeperator'] = array(
				'label'    => esc_html__( 'Button Trigger', 'bricks' ),
				'type'     => 'separator',
				'required' => array( 'triggerType', '=', 'button' ),
			);

			$this->controls['useText'] = array(
				'label' => __( 'Use Text', 'frames' ),
				'type' => 'checkbox',
				'inline' => true,
				'default' => true,
				'required' => array( 'triggerType', '=', 'button' ),
			);

			$this->controls['buttonText'] = array(
				'label' => __( 'Button Text', 'frames' ),
				'type' => 'text',
				'default' => 'Open Modal',
				'inline' => true,
				'required' => array(
					array( 'useText', '=', true ),
					array( 'triggerType', '=', 'button' ),
				),
			);

			$this->controls['useActiveText'] = array(
				'label' => __( 'Use Active Text', 'frames' ),
				'type' => 'checkbox',
				'inline' => true,
				'default' => true,
				'required' => array(
					array( 'triggerType', '=', 'button' ),
					array( 'useText', '=', true ),
				)
			);

			$this->controls['buttonActiveText'] = array(
				'label' => __( 'Button Active Text', 'frames' ),
				'type' => 'text',
				'default' => 'Close Modal',
				'inline' => true,
				'required' => array(
					array( 'useActiveText', '=', true ),
					array( 'triggerType', '=', 'button' ),
					array( 'useText', '=', true ),
				),
			);

			$this->controls['useIcon'] = array(
				'label' => __( 'Use Icon', 'frames' ),
				'type' => 'checkbox',
				'inline' => true,
				'default' => false,
				'required' => array( 'triggerType', '=', 'button' ),
			);

			$this->controls['buttonIcon'] = array(
				'label'    => esc_html__( 'Icon', 'frames' ),
				'type'     => 'icon',
				'rerender' => true,
				'default'  => array(
					'library' => 'themify',
					'icon'    => 'ti-layers',
				),
				'css'      => array(
					array(
						'selector' => '.fr-button-trigger__icon > *',
					),
				),
				'required' => array(
					array( 'useIcon', '=', true ),
					array( 'triggerType', '=', 'button' ),
				),
			);

			$this->controls['buttonIconActive'] = array(
				'label'    => esc_html__( 'Icon - Active', 'frames' ),
				'type'     => 'icon',
				'rerender' => true,
				'default'  => array(
					'library' => 'themify',
					'icon'    => 'ti-layers',
				),
				'css'      => array(
					array(
						'selector' => '.fr-button-trigger__icon > *',
					),
				),
				'required' => array(
					array( 'useIcon', '=', true ),
					array( 'triggerType', '=', 'button' ),
				),
			);

			// styling separator.

			$this->controls['burgerDirection'] =
			array(
				'label' => __( 'Text Positioning', 'frames' ),
				'type' => 'direction',
				'required' => array(
					array( 'useText', '=', true ),
					array( 'useIcon', '=', true ),
					array( 'triggerType', '=', 'button' ),
				),
				'css' => array(
					array(
						'property' => 'flex-direction',
						'selector' => '',
					),
				),
			);

			$this->controls['buttonStyleSeperator'] = array(
				'label'    => esc_html__( 'Button Styles', 'bricks' ),
				'type'     => 'separator',
				'required' => array(
					array( 'triggerType', '=', 'button' ),
					array( 'useText', '=', true ),
				)
			);

			$this->controls['buttonPadding'] = array(
				'label' => __( 'Padding', 'frames' ),
				'type' => 'spacing',
				'css' => array(
					array(
						'property' => 'padding',
						'selector' => '',
					),
				),
				'required' => array(
					array( 'triggerType', '=', 'button' ),
					array( 'useText', '=', true ),
				)
			);

			// background color.

			$this->controls['buttonBackgroundColor'] = array(
				'label' => __( 'Background Color', 'frames' ),
				'type' => 'color',
				'css'   => array(
					array(
						'property' => 'background-color',
						'selector' => '',
					),
				),
				'required' => array(
					array( 'triggerType', '=', 'button' ),
					array( 'useText', '=', true ),
				)
			);

			// border.

			$this->controls['buttonBorder'] = array(
				'label' => __( 'Border', 'frames' ),
				'type' => 'border',
				'css' => array(
					array(
						'property' => 'border',
						'selector' => '',
					),
				),
				'required' => array(
					array( 'triggerType', '=', 'button' ),
					array( 'useText', '=', true ),
				)
			);

			// color.

			$this->controls['buttonTypography'] = array(
				'label' => __( 'Typography', 'frames' ),
				'type' => 'typography',
				'css'   => array(
					array(
						'property' => 'typography',
						'selector' => '',
					),
				),
				'required' => array(
					array( 'triggerType', '=', 'button' ),
					array( 'useText', '=', true ),
				)
			);

			// box-shadow.

			$this->controls['buttonBoxShadow'] = array(
				'label' => __( 'Box Shadow', 'frames' ),
				'type' => 'box-shadow',
				'css' => array(
					array(
						'property' => 'box-shadow',
						'selector' => '',
					),
				),
				'required' => array(
					array( 'triggerType', '=', 'button' ),
					array( 'useText', '=', true ),
				)
			);

			// separator Button Active Styles.

			$this->controls['buttonActiveStyleSeperator'] = array(
				'label'    => esc_html__( 'Button Active Styles', 'bricks' ),
				'type'     => 'separator',
				'required' => array(
					array( 'triggerType', '=', 'button' ),
					array( 'useText', '=', true ),
				)
			);

			// background color.

			$this->controls['buttonBackgroundColorActive'] = array(
				'label' => __( 'Background Color', 'frames' ),
				'type' => 'color',
				'css'   => array(
					array(
						'property' => '--fr-button-trigger-background-color-active',
						'selector' => '',
					),
				),
				'required' => array(
					array( 'triggerType', '=', 'button' ),
					array( 'useText', '=', true ),
				)
			);

			// color.

			$this->controls['buttonTypographyActive'] = array(
				'label' => __( 'Typography', 'frames' ),
				'type' => 'color',
				'css'   => array(
					array(
						'property' => '--fr-button-trigger-font-color-active',
						'selector' => '',
					),
				),
				'required' => array(
					array( 'triggerType', '=', 'button' ),
					array( 'useText', '=', true ),
				)
			);

			// separator Icon Styles.

			$this->controls['buttonIconStyleSeperator'] = array(
				'label'    => esc_html__( 'Icon Styles', 'bricks' ),
				'type'     => 'separator',
				'required' => array(
					array( 'triggerType', '=', 'button' ),
					array( 'useIcon', '=', true ),
				)
			);

			$this->controls['buttonIconSize'] = array(
				'label' => __( 'Icon Size', 'frames' ),
				'type' => 'number',
				'units' => true,
				'css'   => array(
					array(
						'property' => 'font-size',
						'selector' => '.fr-button-trigger__icon',
					),
				),
				'required' => array(
					array( 'triggerType', '=', 'button' ),
					array( 'useIcon', '=', true ),
				)
			);

			// icon color.

			$this->controls['buttonIconColor'] = array(
				'label' => __( 'Icon Color', 'frames' ),
				'type' => 'color',
				'css'   => array(
					array(
						'property' => 'color',
						'selector' => '.fr-button-trigger__icon',
					),
					array(
						'property' => 'fill',
						'selector' => '.fr-button-trigger__icon',
					),
				),
				'required' => array(
					array( 'triggerType', '=', 'button' ),
					array( 'useIcon', '=', true ),
				)
			);

			// icon wrapper size.

			$this->controls['buttonIconWrapperSize'] = array(
				'label' => __( 'Icon Wrapper Size', 'frames' ),
				'type' => 'number',
				'units' => true,
				'css'   => array(
					array(
						'property' => 'width',
						'selector' => '.fr-button-trigger__icon-wrapper',
					),
					array(
						'property' => 'height',
						'selector' => '.fr-button-trigger__icon-wrapper',
					),
				),
				'required' => array(
					array( 'triggerType', '=', 'button' ),
					array( 'useIcon', '=', true ),
				)
			);

			// icon wrapper background color.

			$this->controls['buttonIconWrapperBackgroundColor'] = array(
				'label' => __( 'Icon Wrapper Background Color', 'frames' ),
				'type' => 'color',
				'css'   => array(
					array(
						'property' => 'background-color',
						'selector' => '.fr-button-trigger__icon-wrapper',
					),
				),
				'required' => array(
					array( 'triggerType', '=', 'button' ),
					array( 'useIcon', '=', true ),
				)
			);

			// icon wrapper border.

			$this->controls['buttonIconWrapperBorder'] = array(
				'label' => __( 'Icon Wrapper Border', 'frames' ),
				'type' => 'border',
				'css' => array(
					array(
						'property' => 'border',
						'selector' => '.#brxe-{{ID}} .fr-button-trigger__icon-wrapper',
					),
				),
				'required' => array(
					array( 'triggerType', '=', 'button' ),
					array( 'useIcon', '=', true ),
				)
			);

			// Separator Icon Active Styles.

			$this->controls['buttonIconActiveStyleSeperator'] = array(
				'label'    => esc_html__( 'Icon Active Styles', 'bricks' ),
				'type'     => 'separator',
				'required' => array(
					array( 'triggerType', '=', 'button' ),
					array( 'useIcon', '=', true ),
				)
			);

			// icon color.

			$this->controls['buttonIconColorActive'] = array(
				'label' => __( 'Icon Color', 'frames' ),
				'type' => 'color',
				'css'   => array(
					array(
						'property' => '--fr-button-trigger-icon-color-active',
						'selector' => '',
					),
				),
				'required' => array(
					array( 'triggerType', '=', 'button' ),
					array( 'useIcon', '=', true ),
				)
			);

			// icon wrapper background color.

			$this->controls['buttonIconWrapperBackgroundColorActive'] = array(
				'label' => __( 'Icon Wrapper Background Color', 'frames' ),
				'type' => 'color',
				'css'   => array(
					array(
						'property' => '--fr-button-trigger-icon-wrapper-background-color-active',
						'selector' => '',
					),
				),
				'required' => array(
					array( 'triggerType', '=', 'button' ),
					array( 'useIcon', '=', true ),
				)
			);

			// icon wrapper border (active).

			$this->controls['buttonIconWrapperBorderActive'] = array(
				'label' => __( 'Icon Wrapper Border (Active)', 'frames' ),
				'type' => 'border',
				'css' => array(
					array(
						'property' => 'border',
						'selector' => '#brxe-{{ID}}.fr-button-trigger--active .fr-button-trigger__icon-wrapper',
					),
				),
				'required' => array(
					array( 'triggerType', '=', 'button' ),
					array( 'useIcon', '=', true ),
				)
			);

			// accessibility seperator.

			$this->controls['accessibilitySeperator'] = array(
				'label'    => esc_html__( 'Accessibility', 'bricks' ),
				'type'     => 'separator',
			);

			$this->controls['ariaLabel'] =
			array(
				'label' => __( 'Aria Label', 'frames' ),
				'type' => 'text',
				'default' => 'Toggle Menu',
				'inlineEditing' => true,
			);
	}


	/**
	 * Enqueue Scripts and Styles for the widget
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	public function enqueue_scripts() {
		if ( ! Widget_Manager::is_bricks_frontend() ) {
			return;
		}
		$filename = 'trigger';
		wp_enqueue_style(
			"frames-{$filename}",
			FRAMES_WIDGETS_URL . "/{$filename}/css/{$filename}.css",
			array(),
			filemtime( FRAMES_WIDGETS_DIR . "/{$filename}/css/{$filename}.css" )
		);
		wp_enqueue_script(
			"frames-{$filename}",
			FRAMES_WIDGETS_URL . "/{$filename}/js/{$filename}.js",
			array(),
			filemtime( FRAMES_WIDGETS_DIR . "/{$filename}/js/{$filename}.js" ),
			true
		);
	}



	/**
	 * Render widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	public function render() {

		$settings = $this->settings;

		$type = ! empty( $settings['triggerType'] ) ? $settings['triggerType'] : 'burger';

		// burger settings.
		$animationType  = ! empty( $settings['animation'] ) ? $settings['animation'] : 'fr-hamburger--elastic';
		$ariaLabel      = ! empty( $settings['ariaLabel'] ) ? $settings['ariaLabel'] : 'open menu';
		$triggerText    = ! empty( $settings['triggerText'] ) ? $settings['triggerText'] : 'Menu';
		$addText = ! empty( $settings['addText'] ) ? $settings['addText'] : 0;

		// button settings.

		$useIcon = ! empty( $settings['useIcon'] ) ? $settings['useIcon'] : 0;
		$useText = ! empty( $settings['useText'] ) ? $settings['useText'] : 0;
		$buttonText = ! empty( $settings['buttonText'] ) ? $settings['buttonText'] : 'Open Modal';
		// $prev_arrow = ! empty( $this->settings['prevArrow'] ) ? self::render_icon( $this->settings['prevArrow'] ) : false;
		$buttonIcon = ! empty( $settings['buttonIcon'] ) ? self::render_icon( $settings['buttonIcon'] ) : false;
		$buttonIconActive = ! empty( $settings['buttonIconActive'] ) ? self::render_icon( $settings['buttonIconActive'] ) : false;

		// active settings.
		$useActiveText = ! empty( $settings['useActiveText'] ) ? $settings['useActiveText'] : 0;
		$buttonActiveText = ! empty( $settings['buttonActiveText'] ) ? $settings['buttonActiveText'] : 'Close Modal';

		// options.

		$trigger_options = array(
			'buttonText' => $buttonText,
			'buttonActiveText' => $buttonActiveText,
			'useActiveText' => $useActiveText,
		);

		if ( is_array( $trigger_options ) ) {
				$trigger_options = wp_json_encode( $trigger_options );
		}

		$trigger_options = str_replace( array( "\r", "\n" ), '', $trigger_options );

		$this->set_attribute( '_root', 'aria-label', $ariaLabel );
		$this->set_attribute( '_root', 'aria-controls', 'navigation' );
		$this->set_attribute( '_root', 'aria-expanded', 'false' );

		if ( 'burger' === $type ) {
			$this->set_attribute( '_root', 'class', $animationType );
			$this->set_attribute( '_root', 'class', 'fr-hamburger' );
			$output = "<button {$this->render_attributes( '_root' )}>";
			$output .= "<span class='fr-hamburger-box'>";
			$output .= "<span class='fr-hamburger-inner'></span>";
			$output .= '</span>';
			if ( $addText ) {
				$output .= "<span class='fr-hamburger-text'>{$triggerText}</span>";
			}
		}

		if ( 'button' === $type ) {
			$this->set_attribute( '_root', 'data-fr-trigger-options', trim( $trigger_options ) );
			$this->set_attribute( '_root', 'class', 'fr-button-trigger' );
			$output = "<button {$this->render_attributes( '_root' )}>";

			if ( $useIcon ) {
				$output .= "<span class='fr-button-trigger__icon-wrapper'>";
				$output .= "<span class='fr-button-trigger__icon fr-button-trigger__icon--default'>{$buttonIcon}</span>";
				$output .= "<span class='fr-button-trigger__icon fr-button-trigger__icon--active'>{$buttonIconActive}</span>";
				$output .= '</span>';
			}

			if ( $useText ) {
				$output .= "<span class='fr-button-trigger__text'>{$buttonText}</span>";
			}
		}

		$output .= '</button>';

		echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	}
}
