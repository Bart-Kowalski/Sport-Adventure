<?php
/**
 * Switch Widget.
 *
 * @package Frames_Client
 */

namespace Frames_Client\Widgets\FramesSwitch;

use \Frames_Client\Widget_Manager;

/**
 * Switch class.
 */
class Fr_Switch_Widget extends \Bricks\Element {

	/**
	 * Widget category.
	 *
	 * @var string
	 */
	public $category = 'Frames';

	/**
	 * Widget name.
	 *
	 * @var string
	 */
	public $name = 'fr-switch';

	/**
	 * Widget icon.
	 *
	 * @var string
	 */
	public $icon = 'fas fa-toggle-on';

	/**
	 * Widget scripts.
	 *
	 * @var string
	 */
	public $scripts = array( 'switch_script' );

	/**
	 * Is widget nestable?
	 *
	 * @var string
	 */
	public $nestable = false;

	/**
	 * Get the widget label.
	 *
	 * @return string
	 */
	public function get_label() {
		return esc_html__( 'Frames Switch', 'frames' );
	}

	/**
	 * Enqueue Scripts and Styles for the widget
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	public function enqueue_scripts() {
		$filename = 'switch';
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
	 * Register widget control groups.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	public function set_control_groups() {

		/**
		 *  Here you can add your control groups and assign them to different tabs.
		 *  Check this: https://academy.bricksbuilder.io/article/create-your-own-elements/
		 */

		$this->control_groups['settings'] = array(
			'title' => esc_html__( 'Settings', 'frames' ),
			'tab' => 'content',
		);

		$this->control_groups['labelStyling'] = array(
			'title' => esc_html__( 'Labels Styling', 'frames' ),
			'tab' => 'content',
		);

		$this->control_groups['sliderStyling'] = array(
			'title' => esc_html__( 'Switch Styling', 'frames' ),
			'tab' => 'content',
		);

		$this->control_groups['indicatorStyling'] = array(
			'title' => esc_html__( 'Switch Indicator', 'frames' ),
			'tab' => 'content',
		);

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

		 // type info with 'description' set to 'create a block element with 2 children and give it an unique class'.

		$this->controls['switcherContentInfo'] =
			array(
				'type' => 'info',
				'content' => 'Create a block element with 2 children and give it an unique class f.e .fr-switcher',
				'required' => array( 'switcherSelector', '=', '' )
			);

			$this->controls['switcherSelectorInfo'] =
			array(
				'type' => 'separator',
				'description' => esc_html__( 'Enter the selector of the element that wraps the content you want to switch.', 'frames' ),
				'label' => ' ',
			);

			// info type with content Enter the selector of the element that wraps the content you want to switch.

			$this->controls['switcherSelector'] =
			array(
				'label' => __( 'Content Wrapper Selector', 'frames' ),
				'type' => 'text',
				'inlineEditing' => true,
			);

			$this->controls['defaultActive'] = array(
				'group' => 'settings',
				'label' => esc_html__( 'Activated by default', 'frames' ),
				'type' => 'checkbox',
				'default' => false,
			);

			$this->controls['firstLabel'] =
			array(
				'group' => 'settings',
				'label' => __( 'First Label', 'frames' ),
				'type' => 'text',
				'default' => 'Option 1',
				'inlineEditing' => true,
			);

			$this->controls['secondLabel'] =
			array(
				'group' => 'settings',
				'label' => __( 'Second Label', 'frames' ),
				'type' => 'text',
				'default' => 'Option 2',
				'inlineEditing' => true,
			);

			$this->controls['ariaLabel'] =
			array(
				'group' => 'settings',
				'label' => __( 'Accessible Description', 'frames' ),
				'type' => 'text',
				'default' => 'Switch content',
				'inlineEditing' => true,
			);

			$this->controls['labelTypography'] =
			array(
				'group' => 'labelStyling',
				'label' => __( 'Label Typography', 'frames' ),
				'type' => 'typography',
				'css' => array(
					array(
						'property' => 'typography',
						'selector' => '.fr-switch__label',
					),
				),
				'inline' => true,
			);

			// for color.

			$this->controls['labelColor'] =
			array(
				'group' => 'labelStyling',
				'label' => __( 'Label Color', 'frames' ),
				'type' => 'color',
				'css' => array(
					array(
						'property' => 'color',
						'selector' => '.fr-switch__label',
					),
				),
				'inline' => true,
			);

			$this->controls['labelPadding'] =
			array(
				'group' => 'labelStyling',
				'label' => __( 'Padding', 'frames' ),
				'type' => 'dimensions',
				'css' => array(
					array(
						'property' => 'padding',
						'selector' => '.fr-switch__label',
					),
				),
				'default' => array(
					'top' => '',
					'right' => 'var(--space-xs)',
					'bottom' => '',
					'left' => 'var(--space-xs)',
				),
			);

			$this->controls['labelMargin'] =
			array(
				'group' => 'labelStyling',
				'label' => __( 'Margin', 'frames' ),
				'type' => 'dimensions',
				'css' => array(
					array(
						'property' => 'margin',
						'selector' => '.fr-switch__label',
					),
				),
				'default' => array(
					'top' => '',
					'right' => '',
					'bottom' => '',
					'left' => '',
				),
			);

			// Slider Controls.

			$this->controls['sliderWidth'] =
			array(
				'group' => 'sliderStyling',
				'label' => __( 'Width', 'frames' ),
				'type' => 'number',
				'min' => 0,
				'max' => 9999,
				'step' => 1,
				'units' => true,
				'inline' => true,
				'default' => '3.4em',
				'css'   => array(
					array(
						'property' => 'width',
						'selector' => '.fr-switch__slider',
					),
				),
			);

			$this->controls['sliderHeight'] =
			array(
				'group' => 'sliderStyling',
				'label' => __( 'Height', 'frames' ),
				'type' => 'number',
				'min' => 0,
				'max' => 9999,
				'step' => 1,
				'units' => true,
				'inline' => true,
				'default' => '1.9em',
				'css'   => array(
					array(
						'property' => 'height',
						'selector' => '.fr-switch__slider',
					),
				),
			);

			$this->controls['sliderBackgroundColor'] =
			array(
				'group' => 'sliderStyling',
				'label' => __( 'Background Color', 'frames' ),
				'type' => 'color',
				'default' => array(
					'raw' => 'var(--neutral-ultra-light)',
				),
				'css'   => array(
					array(
						'property' => 'background-color',
						'selector' => '.fr-switch__slider',
					),
				),
			);

			$this->controls['sliderBorder'] =
			array(
				'group' => 'sliderStyling',
				'label' => __( 'Border', 'frames' ),
				'type' => 'border',
				'default' => '',
				'inlineEditing' => true,
				'css'   => array(
					array(
						'property' => 'border',
						'selector' => '.fr-switch__slider',
					),
				),
			);

			// Slider Indicator Controls.

			$this->controls['sliderIndicatorHeight'] =
			array(
				'group' => 'indicatorStyling',
				'label' => __( 'Height', 'frames' ),
				'type' => 'number',
				'min' => 0,
				'max' => 9999,
				'units' => true,
				'step' => 1,
				'inline' => true,
				'default' => '1.5em',
				'css'   => array(
					array(
						'property' => '--fr-switch-indicatorHeight',
						'selector' => '',
					),
				),
			);

			$this->controls['sliderIndicatorWidth'] =
			array(
				'group' => 'indicatorStyling',
				'label' => __( 'Width', 'frames' ),
				'type' => 'number',
				'min' => 0,
				'max' => 10,
				'units' => true,
				'step' => 1,
				'inline' => true,
				'default' => '1.5em',
				'css'   => array(
					array(
						'property' => '--fr-switch-indicatorWidth',
						'selector' => '',
					),
				),
			);

			$this->controls['sliderIndicatorPadding'] =
			array(
				'group' => 'indicatorStyling',
				'label' => __( 'Space from edge', 'frames' ),
				'type' => 'number',
				'min' => 0,
				'max' => 10,
				'units' => true,
				'step' => 1,
				'inline' => true,
				'default' => '.2em',
				'css'   => array(
					array(
						'property' => '--fr-switch-indicatorPadding',
						'selector' => '',
					),
				),
			);

			$this->controls['disabledIndicatorColor'] =
			array(
				'group' => 'indicatorStyling',
				'label' => __( 'Disabled Color', 'frames' ),
				'type' => 'color',
				'default' => array(
					'raw' => 'var(--neutral-light)',
				),
				'css'   => array(
					array(
						'property' => '--fr-switch-disabledColor',
						'selector' => '',
					),
				),
			);

			$this->controls['enabledIndicatorColor'] =
			array(
				'group' => 'indicatorStyling',
				'label' => __( 'Enabled Color', 'frames' ),
				'type' => 'color',
				'default' => array(
					'raw' => 'var(--neutral-dark)',
				),
				'css'   => array(
					array(
						'property' => '--fr-switch-enabledColor',
						'selector' => '',
					),
				),
			);

			$this->controls['indicatorBorder'] =
			array(
				'group' => 'indicatorStyling',
				'label' => __( 'Border', 'frames' ),
				'type' => 'border',
				'default' => '',
				'inlineEditing' => true,
				'css'   => array(
					array(
						'property' => 'border',
						'selector' => '.fr-switch__slider-indicator',
					),
				),
			);

			$this->controls['indicatorTransition'] =
			array(
				'group' => 'indicatorStyling',
				'label' => __( 'Transition', 'frames' ),
				'type' => 'text',
				'default' => 'all .3s ease',
				'inlineEditing' => true,
				'css'   => array(
					array(
						'property' => '--fr-switch-indicatorTransition',
						'selector' => '',
					),
				),
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

		// $defaultActive = ! empty( $settings['defaultActive'] );
		// $defaultActive = (int) $defaultActive;
		// $firstItemOpened = isset( $settings['firstItemOpened'] );
		$defaultActive  = isset( $settings['defaultActive'] );
		$contentSelector = ! empty( $settings['switcherSelector'] ) ? $settings['switcherSelector'] : '';
		$firstLabel = ! empty( $settings['firstLabel'] ) ? $settings['firstLabel'] : '';
		$secondLabel = ! empty( $settings['secondLabel'] ) ? $settings['secondLabel'] : '';
		$ariaLabel = ! empty( $settings['ariaLabel'] ) ? $settings['ariaLabel'] : $this->controls['ariaLabel']['default'];

		// color mode.
		$moonIcon = '<svg class="fr-switch--moon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M11.3807 2.01904C9.91573 3.38786 9 5.33708 9 7.50018C9 11.6423 12.3579 15.0002 16.5 15.0002C18.6631 15.0002 20.6123 14.0844 21.9811 12.6195C21.6613 17.8539 17.3149 22.0002 12 22.0002C6.47715 22.0002 2 17.523 2 12.0002C2 6.68532 6.14629 2.33888 11.3807 2.01904Z"></path></svg>';
		$sunIcon = '<svg class="fr-switch--sun" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 18C8.68629 18 6 15.3137 6 12C6 8.68629 8.68629 6 12 6C15.3137 6 18 8.68629 18 12C18 15.3137 15.3137 18 12 18ZM11 1H13V4H11V1ZM11 20H13V23H11V20ZM3.51472 4.92893L4.92893 3.51472L7.05025 5.63604L5.63604 7.05025L3.51472 4.92893ZM16.9497 18.364L18.364 16.9497L20.4853 19.0711L19.0711 20.4853L16.9497 18.364ZM19.0711 3.51472L20.4853 4.92893L18.364 7.05025L16.9497 5.63604L19.0711 3.51472ZM5.63604 16.9497L7.05025 18.364L4.92893 20.4853L3.51472 19.0711L5.63604 16.9497ZM23 11V13H20V11H23ZM4 11V13H1V11H4Z"></path></svg>';

		$options = array(
			'defaultActive' => $defaultActive,
			'contentSelector' => $contentSelector,
		);

		if ( is_array( $options ) ) {
			$options = wp_json_encode( $options );
		}

		$options = str_replace( array( "\r", "\n", ' ' ), '', $options );

		$this->set_attribute( '_root', 'class', 'fr-switch' );
		$this->set_attribute( '_root', 'aria-label', $ariaLabel );
		$this->set_attribute( '_root', 'type', 'button' );
		$this->set_attribute( '_root', 'data-fr-switch-options', trim( $options ) );

		$output = "<button {$this->render_attributes('_root')}>";

		if ( ! empty( $firstLabel ) ) {
			$output .= '<span class="fr-switch__label fr-switch__label--first">' . $firstLabel . '</span>';
		}

		$output .= '<span class="fr-switch__slider">';
		$output .= '<span class="fr-switch__slider-indicator"></span>';
		$output .= '</span>';

		if ( ! empty( $secondLabel ) ) {
			$output .= '<span class="fr-switch__label fr-switch__label--second">' . $secondLabel . '</span>';
		}

		$output .= '</button>';

		echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

}
