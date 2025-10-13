<?php
/**
 * Slider Controls Widget.
 *
 * @package Frames_Client
 */

namespace Frames_Client\Widgets\Slider_Controls;

use Frames_Client\Widget_Manager;

/**
 * Slider class.
 */
class Slider_Widget extends \Bricks\Element {

	/**
	 * Use predefined element category 'general'.
	 *
	 * @var string
	 */
	public $category = 'Frames';

	/**
	 * Widget name.
	 *
	 * @var string
	 */
	public $name = 'fr-slider-controls';

	/**
	 * Themify icon font class.
	 *
	 * @var string
	 */
	public $icon = 'fas fa-barcode';

	/**
	 * Default CSS selector.
	 *
	 * @var string
	 */
	public $css_selector = '.fr-slider-controls';

	/**
	 * Scripts to be enqueued.
	 *
	 * @var array
	 */
	public $scripts = array( 'fr_slider_controls_script' );

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
		 include( 'inc/slider-controls-functions.php' );
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
		return esc_html__( 'Frames Slider Controls', 'frames' );
	}

	/**
	 * Register widget control groups.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	public function set_control_groups() {

		$this->control_groups['arrows'] = array(
			'title' => esc_html__( 'Arrows', 'frames' ),
			'tab' => 'content',
			'required' => array( 'type', '!=', 'progress' ),

		);

		$this->control_groups['progress'] = array(
			'title' => esc_html__( 'Progress Bar', 'frames' ),
			'tab' => 'content',
			'required' => array( 'type', '=', 'progress' ),
		);

		$this->control_groups['pagination'] = array(
			'title' => esc_html__( 'Pagination Dots', 'frames' ),
			'tab' => 'content',
			'required' => array( 'type', '=', 'pagination' ),
		);

	}

	/**
	 * Register widget controls.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	public function set_controls() {

		$this->controls['syncId'] = array(
			'label'    => esc_html__( 'Slider Name', 'frames' ),
			'type'     => 'text',
			'breakpoints' => false,
			'placeholder' => 'sync-sliders-1',
			'inline'   => true,
		);

		$this->controls['type'] = array(
			'label'    => esc_html__( 'Use', 'frames' ),
			'type'     => 'select',
			'options'  => array(
				'nextPrev' => esc_html__( 'Previous and Next Button', 'frames' ),
				'prev' => esc_html__( 'Previous Button', 'frames' ),
				'next' => esc_html__( 'Next Button', 'frames' ),
				'progress' => esc_html__( 'Progress Bar', 'frames' ),
			),
			'inline'   => true,
			'default'  => 'nextPrev',
		);

		// Progress Bar.

		// separator type with description "progress bar is static in builder preview".
		$this->controls['progressBarSeparator'] = array(
			'type' => 'separator',
			'group' => 'progress',
			// 'label' => esc_html__( 'Progress Bar', 'frames' ),
			'description' => esc_html__( 'Progress bar is static in builder preview', 'frames' ),
		);

		$this->controls['progressBgColor'] = array(
			'group'    => 'progress',
			'label'    => esc_html__( 'Color', 'frames' ),
			'type'     => 'color',
			'css'      => array(
				array(
					'property' => '--fr-slider-progress-bar-color',
					'selector' => '',
				),
			),

		);
		$this->controls['progressBgColorActive'] = array(
			'group'    => 'progress',
			'label'    => esc_html__( 'Active Color', 'frames' ),
			'type'     => 'color',
			'css'      => array(
				array(
					'property' => '--fr-slider-progress-bar-progress-color',
					'selector' => '',
				),
			),
		);

		$this->controls['progressHeight'] = array(
			'group'       => 'progress',
			'label'       => esc_html__( 'Height', 'frames' ),
			'type'        => 'number',
			'units'       => true,
			'css'         => array(
				array(
					'property' => '--fr-slider-progress-bar-height',
					'selector' => '',
				)
			),
			'default'     => '.5rem',
			'breakpoints' => true,
		);

		$this->controls['progressWidth'] = array(
			'group'       => 'progress',
			'label'       => esc_html__( 'Width', 'frames' ),
			'type'        => 'number',
			'units'       => true,
			'css'         => array(
				array(
					'property' => 'width',
					'selector' => '',
				),
			),
			'breakpoints' => true,
		);

		$this->controls['progressBorder'] = array(
			'group'    => 'progress',
			'label'    => esc_html__( 'Border', 'frames' ),
			'type'     => 'border',
			'css'      => array(
				array(
					'property' => 'border',
					'selector' => '.fr-slider__progress',
				),
			),
		);

		// Arrows.
		$this->controls['arrows'] = array(
			'group'    => 'arrows',
			'label'    => esc_html__( 'Use navigation arrows', 'frames' ),
			'type'     => 'checkbox',
			'inline'   => true,
			'rerender' => true,
			'default'  => 'true',
			'breakpoints' => true,
		);

		$this->controls['arrowSize'] = array(
			'group'       => 'arrows',
			'label'       => esc_html__( 'Icon size', 'frames' ),
			'type'        => 'number',
			'units'       => true,
			'css'         => array(
				array(
					'property' => 'font-size',
					'selector' => '.fr-slider__custom-arrows .fr-slider__custom-arrow',
				),
			),
			'default'     => 'var(--space-l)',
			'breakpoints' => true,

		);

		$this->controls['arrowHeight'] = array(
			'group'       => 'arrows',
			'label'       => esc_html__( 'Height', 'frames' ),
			'type'        => 'number',
			'units'       => true,
			'css'         => array(
				array(
					'property' => 'height',
					'selector' => '.fr-slider__custom-arrow',
				),
			),
			'placeholder' => 'var(--space-l)',
			'required'    => array( 'arrows', '!=', '' ),
		);

		$this->controls['arrowWidth'] = array(
			'group'       => 'arrows',
			'label'       => esc_html__( 'Width', 'frames' ),
			'type'        => 'number',
			'units'       => true,
			'css'         => array(
				array(
					'property' => 'width',
					'selector' => '.fr-slider__custom-arrow',
				),
			),
			'placeholder' => 'var(--space-l)',
			'required'    => array( 'arrows', '!=', '' ),
		);

		$this->controls['arrowSize'] = array(
			'group'       => 'arrows',
			'label'       => esc_html__( 'Icon size', 'bricks' ),
			'type'        => 'number',
			'units'       => true,
			'css'         => array(
				array(
					'property' => 'font-size',
					'selector' => '.fr-slider__custom-arrow > *',
				),
			),

		);

		$this->controls['arrowBackground'] = array(
			'group'    => 'arrows',
			'label'    => esc_html__( 'Background', 'frames' ),
			'type'     => 'color',
			'css'      => array(
				array(
					'property' => 'background-color',
					'selector' => '.fr-slider__custom-arrow',
				),
			),
			'required' => array( 'arrows', '!=', '' ),
		);

		$this->controls['arrowBorder'] = array(
			'group'    => 'arrows',
			'label'    => esc_html__( 'Border', 'frames' ),
			'type'     => 'border',
			'css'      => array(
				array(
					'property' => 'border',
					'selector' => '.fr-slider__custom-arrow',
				),
			),
			'required' => array( 'arrows', '!=', '' ),
		);

		$this->controls['arrowTypography'] = array(
			'group'    => 'arrows',
			'label'    => esc_html__( 'Icons Color', 'frames' ),
			'type'     => 'color',
			'css'      => array(
				array(
					'property' => 'color',
					'selector' => '.fr-slider__custom-arrow',
				),
			),
			'default'  => 'var(--neutral-ultra-dark)',
			'required' => array( 'arrows', '!=', '' ),
		);

		$this->controls['arrowsFlexDirection'] =
		array(
			'label' => __( 'Arrows Positioning', 'frames' ),
			'type' => 'direction',
			'group'    => 'arrows',
			'css' => array(
				array(
					'property' => 'flex-direction',
					'selector' => '.fr-slider__custom-arrows',
				),
			),
			'required' => array( 'type', '=', 'nextPrev' ),
		);

		$this->controls['arrowsGap'] = array(
			'group'       => 'arrows',
			'label'       => esc_html__( 'Gap', 'frames' ),
			'type'        => 'number',
			'units'       => true,
			'css'         => array(
				array(
					'property' => 'gap',
					'selector' => '.fr-slider__custom-arrows',
				),
			),
			'default'     => 'var(--space-l)',
			'breakpoints' => true,
			'required' => array( 'type', '=', 'nextPrev' ),

		);

		// DISABLED.
		$this->controls['disabledSeparator'] = array(
			'group'    => 'arrows',
			'label'    => esc_html__( 'Disabled', 'frames' ),
			'type'     => 'separator',
			'required' => array( 'type', '!=', 'next' ),
		);

		$this->controls['disabledInfo'] = array(
			'group'    => 'arrows',
			'content'  => esc_html__( ' Require Slide or Fade slider type ', 'frames' ),
			'type'     => 'info',
			'required' => false,
		);

		$this->controls['disabledBackground'] = array(
			'group'    => 'arrows',
			'label'    => esc_html__( 'Background', 'frames' ),
			'type'     => 'color',
			'css'      => array(
				array(
					'property' => 'background-color',
					'selector' => '.fr-slider__custom-arrow[disabled]',
				),
			),
			'required' => array( 'arrows', '!=', '' ),
		);

		$this->controls['disabledColor'] = array(
			'group'    => 'arrows',
			'label'    => esc_html__( 'Color', 'frames' ),
			'type'     => 'color',
			'css'      => array(
				array(
					'property' => 'color',
					'selector' => '.fr-slider__custom-arrow[disabled]',
				),
			),
			'required' => array( 'arrows', '!=', '' ),
		);

		$this->controls['disabledBorder'] = array(
			'group'    => 'arrows',
			'label'    => esc_html__( 'Border', 'frames' ),
			'type'     => 'border',
			'css'      => array(
				array(
					'property' => 'border',
					'selector' => '.fr-slider__custom-arrow[disabled]',
				),
			),
			'required' => array( 'arrows', '!=', '' ),
		);

		$this->controls['arrowDisabled'] = array(
			'group'    => 'arrows',
			'label'    => esc_html__( 'Opacity', 'frames' ),
			'type'     => 'number',
			'css'      => array(
				array(
					'property' => 'opacity',
					'selector' => '.fr-slider__custom-arrow[disabled]',
				),
			),
			'default'  => '1',
			'required' => array( 'arrows', '!=', '' ),
		);

		// PREV ARROW.
		$this->controls['prevArrowSeparator'] = array(
			'group'    => 'arrows',
			'label'    => esc_html__( 'Prev arrow', 'frames' ),
			'type'     => 'separator',
			'required' => array( 'type', '!=', 'next' ),
		);

		$this->controls['prevArrow'] = array(
			'group'    => 'arrows',
			'label'    => esc_html__( 'Prev arrow', 'frames' ),
			'type'     => 'icon',
			'rerender' => true,
			'default'  => array(
				'library' => 'themify',
				'icon'    => 'ti-arrow-left',
			),
			'css'      => array(
				array(
					'selector' => '.fr-slider__custom-arrow--prev > *',
				),
			),
			'required' => array( 'type', '!=', 'next' ),
		);

		// text type control for previous arrow aria label with default "Previous slide" value.

		$this->controls['prevArrowAriaLabel'] = array(
			'group'    => 'arrows',
			'label'    => esc_html__( 'Previous arrow aria label', 'frames' ),
			'type'     => 'text',
			'default'  => esc_html__( 'Previous slide', 'frames' ),
			'required' => array( 'type', '!=', 'next' ),
		);

		// NEXT ARROW.

		$this->controls['nextArrowSeparator'] = array(
			'group'    => 'arrows',
			'label'    => esc_html__( 'Next arrow', 'frames' ),
			'type'     => 'separator',
			'required' => array( 'type', '!=', 'prev' ),
		);

		$this->controls['nextArrow'] = array(
			'group'    => 'arrows',
			'label'    => esc_html__( 'Next arrow', 'frames' ),
			'type'     => 'icon',
			'rerender' => true,
			'css'      => array(
				array(
					'selector' => '.fr-slider__custom-arrow--next > *',
				),
			),
			'required' => array( 'type', '!=', 'prev' ),
			'default'  => array(
				'library' => 'themify',
				'icon'    => 'ti-arrow-right',
			),
		);

		// text type control for next arrow aria label with default "Next slide" value.

		$this->controls['nextArrowAriaLabel'] = array(
			'group'    => 'arrows',
			'label'    => esc_html__( 'Next arrow aria label', 'frames' ),
			'type'     => 'text',
			'default'  => esc_html__( 'Next slide', 'frames' ),
			'required' => array( 'type', '!=', 'prev' ),
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
		// wp_enqueue_script( 'bricks-splide' );
		// wp_enqueue_style( 'bricks-splide' ); // TODO: check if needed.

		// Custom CSS and JS.
		$filename = 'slider-controls';
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
	 * Output aria label.
	 *
	 * @param string $aria_label Aria label.
	 * @return string
	 */
	private static function outputAriaLabel( $aria_label ) {
		return 'aria-label="' . $aria_label . '"';
	}

	/**
	 * Render for Custom Navigation.
	 *
	 * @param string $type Type of navigation.
	 * @return string
	 */
	public function render_custom_navigation( $type ) {
		$prev_arrow = ! empty( $this->settings['prevArrow'] ) ? self::render_icon( $this->settings['prevArrow'] ) : false;
		$next_arrow = ! empty( $this->settings['nextArrow'] ) ? self::render_icon( $this->settings['nextArrow'] ) : false;
		$previous_aria_label = ! empty( $this->settings['prevArrowAriaLabel'] ) ? $this->settings['prevArrowAriaLabel'] : 'Previous slide';
		$next_aria_label = ! empty( $this->settings['nextArrowAriaLabel'] ) ? $this->settings['nextArrowAriaLabel'] : 'Next slide';

		if ( ! $prev_arrow && ! $next_arrow ) {
			return;
		}

		if ( 'nextPrev' === $type ) {
			$output = '<div class="fr-slider__custom-arrows">';

			if ( $prev_arrow ) {
				$output .= '<button class="fr-slider__custom-arrow--prev fr-slider__custom-arrow" type="button"' . self::outputAriaLabel( $previous_aria_label ) . '>' . $prev_arrow . '</button>';
			}
			if ( $next_arrow ) {
				$output .= '<button class="fr-slider__custom-arrow--next fr-slider__custom-arrow" type="button"' . self::outputAriaLabel( $next_aria_label ) . '>' . $next_arrow . '</button>';
			}

			$output .= '</div>';

		} else if ( 'next' === $type ) {

			if ( $next_arrow ) {
				$output = '<button class="fr-slider__custom-arrow--next fr-slider__custom-arrow" type="button"' . self::outputAriaLabel( $next_aria_label ) . '>' . $next_arrow . '</button>';
			}
		} else if ( 'prev' === $type ) {

			if ( $prev_arrow ) {
				$output = '<button class="fr-slider__custom-arrow--prev fr-slider__custom-arrow" type="button"' . self::outputAriaLabel( $previous_aria_label ) . '>' . $prev_arrow . '</button>';
			}
		} else if ( 'progress' === $type ) {
			$output = '<div class="fr-slider__progress">';
			$output .= '<div class="fr-slider__progress-bar"></div>';
			$output .= '<div class="fr-slider__progress-buttons"></div>';
			$output .= '</div>';
		}

		return $output;
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

		$navSyncId = ! empty( $settings['syncId'] ) ? $settings['syncId'] : '';
		$type = ! empty( $settings['type'] ) ? $settings['type'] : 'nextPrev';

		$isNextPrevious = 'nextPrev' === $type;
		// $isPagination = $navType === 'pagination'; // TODO: check if needed.
		$isNext = 'next' === $type;
		$isPrev = 'prev' === $type;
		$isProgress = 'progress' === $type;

		$this->set_attribute( '_root', 'data-fr-slider-nav-sync-id', $navSyncId );
		$this->set_attribute( '_root', 'data-fr-slider-nav-type', $type );
		$this->set_attribute( '_root', 'class', 'fr-slider-custom-navigation' );

		$output = "<div {$this->render_attributes( '_root' )}>";

		// Previous and Next Buttons.
		if ( $isNextPrevious ) {
			$output .= $this->render_custom_navigation( 'nextPrev' );
		}

		if ( $isProgress ) {
			$output .= $this->render_custom_navigation( 'progress' );
		}

		// Next Button.
		if ( $isNext ) {
			$output .= $this->render_custom_navigation( 'next' );
		}

		// Previous Button.
		if ( $isPrev ) {
			$output .= $this->render_custom_navigation( 'prev' );
		}

		$output .= '</div>';
		echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	}


}
