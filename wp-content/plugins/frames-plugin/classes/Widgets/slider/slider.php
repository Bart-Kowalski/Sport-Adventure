<?php
/**
 * Slider Widget.
 *
 * @package Frames_Client
 */

namespace Frames_Client\Widgets\Slider;

use Frames_Client\Helpers\Flag;
use \Frames_Client\Widget_Manager;

use function PHPSTORM_META\type;

// TODO @andre remove flag after test.
define( 'MOUSE_WHEEL_RELEASE', false );

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
	public $name = 'fr-slider';

	/**
	 * Themify icon font class.
	 *
	 * @var string
	 */
	public $icon = 'fas fa-barcode';


	/**
	 * Scripts to be enqueued.
	 *
	 * @var array
	 */
	public $scripts = array( 'slider_script' );

	/**
	 * Is nestable.
	 *
	 * @var boolean
	 */

	public $nestable = true;



	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function get_methods() {
		 include( 'inc/slider-functions.php' );
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
		return esc_html__( 'Frames Slider', 'frames' );
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
		// wp_enqueue_script( 'bricks-splide' ); // TODO: check if this is needed.
		// wp_enqueue_style( 'bricks-splide' ); // TODO: check if this is needed.

		$filename = 'slider';
		// Splide CSS and JS.
		wp_enqueue_style(
			'frames-splide',
			FRAMES_WIDGETS_URL . "/{$filename}/css/splide.min.css",
			array(),
			filemtime( FRAMES_WIDGETS_DIR . "/{$filename}/css/splide.min.css" )
		);
		wp_enqueue_script(
			'frames-splide',
			FRAMES_WIDGETS_URL . "/{$filename}/js/splide.min.js",
			array(),
			filemtime( FRAMES_WIDGETS_DIR . "/{$filename}/js/splide.min.js" ),
			true
		);
		wp_enqueue_script(
			'frames-splide-extension-auto-scroll',
			FRAMES_WIDGETS_URL . "/{$filename}/js/splide-extension-auto-scroll.min.js",
			array( 'frames-splide' ),
			filemtime( FRAMES_WIDGETS_DIR . "/{$filename}/js/splide-extension-auto-scroll.min.js" ),
			true
		);

		// Custom CSS and JS.
		wp_enqueue_style(
			"frames-{$filename}",
			FRAMES_WIDGETS_URL . "/{$filename}/css/{$filename}.css",
			array( 'frames-splide' ),
			filemtime( FRAMES_WIDGETS_DIR . "/{$filename}/css/{$filename}.css" )
		);
		wp_enqueue_script(
			"frames-{$filename}",
			FRAMES_WIDGETS_URL . "/{$filename}/js/{$filename}.js",
			array( 'frames-splide', 'frames-splide-extension-auto-scroll' ),
			filemtime( FRAMES_WIDGETS_DIR . "/{$filename}/js/{$filename}.js" ),
			true
		);
		if ( Flag::is_on( 'MOUSE_WHEEL_RELEASE' ) ) {
			wp_localize_script(
				"frames-{$filename}",
				'frames_slide_obj',
				array(
					'flag_enable_mouse_wheel_release' => Flag::is_on( 'MOUSE_WHEEL_RELEASE' ),
				)
			);
		}
	}

	/**
	 * Register widget control groups.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	public function set_control_groups() {

		$this->control_groups['options'] = array(
			'title'         => esc_html__( 'Options', 'frames' ),
			'tab'           => 'content',
		);

		$this->control_groups['autoplay'] = array(
			'title' => esc_html__( 'Autoplay', 'frames' ),
			'tab' => 'content',
			'required' => array( 'autoplay', '=', true )
		);

		$this->control_groups['autoScroll'] = array(
			'title' => esc_html__( 'Auto Scroll', 'frames' ),
			'tab' => 'content',
			'required' => array( 'autoScroll', '=', true )
		);

		$this->control_groups['slideStyles'] = array(
			'title' => esc_html__( 'Slide Styles', 'frames' ),
			'tab' => 'content',
		);

		$this->control_groups['active'] = array(
			'title' => esc_html__( 'Active Slide Styles', 'frames' ),
			'tab' => 'content',
		);

		$this->control_groups['controls'] = array(
			'title' => esc_html__( 'Controls', 'frames' ),
			'tab' => 'content',

		);

		$this->control_groups['accessibility'] = array(
			'title' => esc_html__( 'Accessibility', 'frames' ),
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

		$this->controls['_height']['css'][0]['selector'] = '.splide__slide';

		$this->controls['layoutSeparator'] = array(
			'group'    => 'options',
			'label'    => esc_html__( 'Layout', 'frames' ),
			'type'     => 'separator',
		);

		$this->controls['perPage'] = array(
			'group'       => 'options',
			'label'       => esc_html__( 'Visible Slide Count', 'frames' ),
			'type'        => 'number',
			'placeholder' => 1,
			'breakpoints' => true,
		);

		$this->controls['perPageLabel'] = array(
			'group'       => 'options',
			'content'     => esc_html__( 'When Loop mode is active, decimal values will automatically be converted to whole numbers.', 'bricks' ),
			'type'        => 'info',
			'required' => array(
				'type',
				'=',
				'loop'
			),
		);

		// required perPage, control 'focus' type 'text' info: write a number or 'center'.

		$this->controls['focus'] = array(
			'group'       => 'options',
			'label'       => esc_html__( 'Which slide to focus', 'frames' ),
			'type'        => 'text',
			'placeholder' => 'center',
			'info'        => esc_html__( 'Write a specif number or word center', 'frames' ),
			'inline'      => true,
			'breakpoints' => true,
			'required' => array(
				array( 'perPage', '!=', '' )
			)
		);

		$this->controls['fixedWidth'] = array(
			'group'       => 'options',
			'label'       => esc_html__( 'Fixed Slide Width', 'frames' ),
			'type'        => 'number',
			'units'       => true,
			'breakpoints' => true,
			'required' => array(
				array( 'type', '!=', 'fade' )
			)
		);

		$this->controls['gap'] = array(
			'group'       => 'options',
			'label'       => esc_html__( 'Gap between slides', 'frames' ),
			'type'        => 'number',
			'units'       => true,
			'placeholder' => 0,
			'breakpoints' => true,
		);

		$this->controls['fixedHeight'] = array(
			'group'       => 'options',
			'label'       => esc_html__( 'Fixed Slide Height', 'frames' ),
			'type'        => 'number',
			'units'       => true,
			'breakpoints' => true,
			'placeholder' => '50vh',
		);

		$this->controls['breakout'] = array(
			'group'    => 'options',
			'label'    => esc_html__( 'Breakout from container', 'frames' ),
			'type'     => 'checkbox',
			'inline'   => true,
		);

		$this->controls['clipToContainer'] = array(
			'group'       => 'options',
			'label'       => esc_html__( 'Clip to container side', 'frames' ),
			'type'        => 'select',
			'options'     => array(
				'inset(-100vw -100vw -100vw 0)' => esc_html__( 'Left', 'frames' ),
				'inset(-100vw 0 -100vw -100vw)'  => esc_html__( 'Right', 'frames' ),
			),
			'inline'      => true,
			'breakpoints' => true,
			'required' => array(
				array( 'breakout', '=', true )
			),
			'css'   => array(
				array(
					'property' => 'clip-path',
					'selector' => '',
				),
			),
		);

		$this->controls['direction'] = array(
			'group'       => 'options',
			'label'       => esc_html__( 'Direction', 'frames' ),
			'type'        => 'select',
			'options'     => array(
				'ltr' => esc_html__( 'LTR', 'frames' ),
				'rtl' => esc_html__( 'RTL', 'frames' ),
				'ttb' => esc_html__( 'Vertical', 'frames' ),
			),
			'inline'      => true,
			'placeholder' => esc_html__( 'LTR', 'frames' ),
			'breakpoints' => true,
		);

		$this->controls['height'] = array(
			'group'       => 'options',
			'info'        => esc_html__( 'You must specify a slide height for vertical slider', 'frames' ),
			'label'       => esc_html__( 'Slider Height', 'frames' ),
			'type'        => 'number',
			'units'       => true,
			'breakpoints' => true,
			'default'     => '50vh',
			'required' => array(
				array( 'type', '!=', 'fade' ),
				array( 'direction', '=', 'ttb' )
			),
		);

		$this->controls['sliderPaddingStart'] = array(
			'group' => 'options',
			'label' => esc_html__( 'Slider Start Padding', 'frames' ),
			'type'  => 'number',
			'units' => true,
			'placeholder' => '0',
			'breakpoints' => true,
			'css' => array(
				array(
					'property' => 'padding-inline-start',
					'selector' => ':not(.splide__track--ttb).fr-slider__track.splide__track',
					'important' => true
				),
				array(
					'property' => 'padding-block-start',
					'selector' => '.fr-slider__track.splide__track.splide__track--ttb',
					'important' => true
				),
			),
			'info' => esc_html__( 'Left padding for LTR/RTL, Top padding for TTB', 'frames' ),
		);

		$this->controls['sliderPaddingEnd'] = array(
			'group' => 'options',
			'label' => esc_html__( 'Slider End Padding', 'frames' ),
			'type'  => 'number',
			'units' => true,
			'placeholder' => '0',
			'breakpoints' => true,
			'css' => array(
				array(
					'property' => 'padding-inline-end',
					'selector' => ':not(.splide__track--ttb).fr-slider__track.splide__track',
					'important' => true
				),
				array(
					'property' => 'padding-block-end',
					'selector' => '.fr-slider__track.splide__track.splide__track--ttb',
					'important' => true
				),
			),
			'info' => esc_html__( 'Right padding for LTR/RTL, Bottom padding for TTB', 'frames' ),
		);

		// Behavior Controls.

		// Behavior Separator.

		$this->controls['behaviorSeparator'] = array(
			'group'    => 'options',
			'label'    => esc_html__( 'Behavior', 'frames' ),
			'type'     => 'separator',
		);

		$this->controls['type'] = array(
			'group'       => 'options',
			'label'       => esc_html__( 'Slider Type', 'frames' ),
			'type'        => 'select',
			'options'     => array(
				'loop'  => esc_html__( 'Loop', 'frames' ),
				'slide' => esc_html__( 'Slide', 'frames' ),
				'fade'  => esc_html__( 'Fade', 'frames' ),
			),
			'inline'      => true,
		);

		$this->controls['start'] = array(
			'group'       => 'options',
			'label'       => esc_html__( 'Start index', 'frames' ),
			'type'        => 'number',
			'placeholder' => 0,
			'info'        => esc_html__( 'Use 0 for the first slide', 'frames' ),
		);

		$this->controls['perMove'] = array(
			'group'       => 'options',
			'label'       => esc_html__( 'No. of slides to move', 'frames' ),
			'type'        => 'number',
			'placeholder' => 1,
			'breakpoints' => true,
		);

		$this->controls['speed'] = array(
			'group'       => 'options',
			'label'       => esc_html__( 'Slide Speed', 'frames' ),
			'type'        => 'number',
			'default' => 300,
			'breakpoints' => true,
			'info'        => esc_html__( 'Slide transition duration (in ms)', 'frames' ),
			'css'           => array(
				array(
					'property' => '--fr-slide-transition-duration',
					'selector' => '',
				),
			),
		);

		// Loop controls.
		$this->controls['rewind'] = array(
			'group'    => 'options',
			'label'    => esc_html__( 'Rewind', 'frames' ),
			'type'     => 'checkbox',
			'inline'   => true,
			'required' => array(
				array( 'type', '!=', array( '', 'loop' ) ),
			),
		);

		$this->controls['rewindByDrag'] = array(
			'group'    => 'options',
			'label'    => esc_html__( 'Rewind by drag', 'frames' ),
			'type'     => 'checkbox',
			'inline'   => true,
			'required' => array(
				array( 'type', '!=', array( '', 'loop' ) ),
				array( 'rewind', '!=', '' )
			),
		);

		$this->controls['rewindSpeed'] = array(
			'group'    => 'options',
			'label'    => esc_html__( 'Speed in ms', 'frames' ),
			'type'     => 'number',
			'inline'   => true,
			'required' => array(
				array( 'type', '!=', array( '', 'loop' ) ),
				array( 'rewind', '!=', '' )
			),
		);

		$this->controls['autoplay'] = array(
			'group'    => 'options',
			'label'    => esc_html__( 'Turn autoplay', 'frames' ),
			'type'     => 'checkbox',
		);

		$this->controls['pauseOnHover'] = array(
			'group'    => 'autoplay',
			'label'    => esc_html__( 'Pause on hover', 'frames' ),
			'type'     => 'checkbox',
			'required' => array( 'autoplay', '=', true ),
			'inline'   => true,
		);

		$this->controls['pauseOnFocus'] = array(
			'group'    => 'autoplay',
			'label'    => esc_html__( 'Pause on focus', 'frames' ),
			'type'     => 'checkbox',
			'inline'   => true,
			'required' => array( 'autoplay', '=', true ),
		);

		$this->controls['interval'] = array(
			'group'       => 'autoplay',
			'label'       => esc_html__( 'Interval in ms', 'frames' ),
			'type'        => 'number',
			'required'    => array( 'autoplay', '=', true ),
			'placeholder' => 3000,
		);

		$this->controls['playPauseButtonSeparator'] = array(
			'group'    => 'autoplay',
			'label'    => esc_html__( 'Play/Pause button', 'frames' ),
			'type'     => 'separator',
			'required' => array( 'autoplay', '=', true ),
		);

		$this->controls['playPauseButton'] = array(
			'group'    => 'autoplay',
			'label'    => esc_html__( 'Play/Pause button', 'frames' ),
			'type'     => 'checkbox',
			'inline'   => true,
			'required' => array( 'autoplay', '=', true ),
		);

		$this->controls['playButtonIcon'] = array(
			'group'    => 'autoplay',
			'label'    => esc_html__( 'Play button', 'frames' ),
			'type'     => 'icon',
			'rerender' => true,
			'default'  => array(
				'library' => 'themify',
				'icon'    => 'ti-control-play',
			),
			'required' => array( 'playPauseButton', '=', true ),
		);

		$this->controls['pauseButtonIcon'] = array(
			'group'    => 'autoplay',
			'label'    => esc_html__( 'Pause button', 'frames' ),
			'type'     => 'icon',
			'rerender' => true,
			'default'  => array(
				'library' => 'themify',
				'icon'    => 'ti-control-pause',
			),
			'required' => array( 'playPauseButton', '=', true ),
		);

		$this->controls['playPauseStylingSeparator'] = array(
			'group'    => 'autoplay',
			'label'    => esc_html__( 'Play/Pause Button Styling', 'frames' ),
			'type'     => 'separator',
			'required' => array( 'playPauseButton', '=', true ),
		);

		$this->controls['playPauseButtonColor'] = array(
			'group'    => 'autoplay',
			'label'    => esc_html__( 'Button background color', 'frames' ),
			'type'     => 'color',
			'css'           => array(
				array(
					'property' => 'background-color',
					'selector' => '.fr-slider__play-pause-icon',
				),
			),
			'required' => array( 'playPauseButton', '=', true ),
		);

		$this->controls['playPauseButtonIconColor'] = array(
			'group'    => 'autoplay',
			'label'    => esc_html__( 'Button icon color', 'frames' ),
			'type'     => 'color',
			'css'           => array(
				array(
					'property' => 'color',
					'selector' => '.fr-slider__play-pause-icon i',
				),
			),
			'required' => array( 'playPauseButton', '=', true ),
		);

		// border type that targets border property for .fr-slider__play-pause-icon.

		$this->controls['playPauseButtonBorder'] = array(
			'group'    => 'autoplay',
			'label'    => esc_html__( 'Button border', 'frames' ),
			'type'     => 'border',
			'css'           => array(
				array(
					'property' => 'border',
					'selector' => '.fr-slider__play-pause-icon',
				),
			),
			'required' => array( 'playPauseButton', '=', true ),
		);

		$this->controls['playPauseButtonSize'] = array(
			'group'    => 'autoplay',
			'label'    => esc_html__( 'Button size', 'frames' ),
			'type'     => 'number',
			'css'           => array(
				array(
					'property' => 'width',
					'selector' => '.fr-slider__play-pause-icon',
				),
			),
			'required' => array( 'playPauseButton', '=', true ),
		);

		$this->controls['playPauseButtonIconSize'] = array(
			'group'    => 'autoplay',
			'label'    => esc_html__( 'Icon size', 'frames' ),
			'type'     => 'number',
			'css'           => array(
				array(
					'property' => 'font-size',
					'selector' => '.fr-slider__play-pause-icon i',
				),
			),
			'required' => array( 'playPauseButton', '=', true ),
		);

		$this->controls['playPauseButtonPositionTop'] = array(
			'group'    => 'autoplay',
			'label'    => esc_html__( 'Top', 'frames' ),
			'type'     => 'number',
			'css'           => array(
				array(
					'property' => 'top',
					'selector' => '.fr-slider__play-pause',
				),
			),
			'required' => array( 'playPauseButton', '=', true ),
		);

		$this->controls['playPauseButtonPositionLeft'] = array(
			'group'    => 'autoplay',
			'label'    => esc_html__( 'Left', 'frames' ),
			'type'     => 'number',
			'css'           => array(
				array(
					'property' => 'left',
					'selector' => '.fr-slider__play-pause',
				),
			),
			'required' => array( 'playPauseButton', '=', true ),
		);

		$this->controls['playPauseButtonPositionRight'] = array(
			'group'    => 'autoplay',
			'label'    => esc_html__( 'Right', 'frames' ),
			'type'     => 'number',
			'css'           => array(
				array(
					'property' => 'right',
					'selector' => '.fr-slider__play-pause',
				),
			),
			'required' => array( 'playPauseButton', '=', true ),
		);

		$this->controls['playPauseButtonPositionBottom'] = array(
			'group'    => 'autoplay',
			'label'    => esc_html__( 'Bottom', 'frames' ),
			'type'     => 'number',
			'css'           => array(
				array(
					'property' => 'bottom',
					'selector' => '.fr-slider__play-pause',
				),
			),
			'required' => array( 'playPauseButton', '=', true ),
		);

		// Progress Bar.

		// progressBar Separator.

		$this->controls['progressBarSeparator'] = array(
			'group'    => 'autoplay',
			'label'    => esc_html__( 'Progress Bar', 'frames' ),
			'type'     => 'separator',
		);

		$this->controls['progressBar'] = array(
			'group'    => 'autoplay',
			'label'    => esc_html__( 'Show progress bar', 'frames' ),
			'type'     => 'checkbox',
		);

		$this->controls['progressBarHeight'] = array(
			'group'    => 'autoplay',
			'label'    => esc_html__( 'Height', 'frames' ),
			'type'     => 'number',
			'css'           => array(
				array(
					'property' => 'height',
					'selector' => '.fr-slider__progress--autoplay .fr-slider__progress-bar',
				),
			),
			'required' => array( 'progressBar', '=', true ),
		);

		$this->controls['progressBarColor'] = array(
			'group'    => 'autoplay',
			'label'    => esc_html__( 'Color', 'frames' ),
			'type'     => 'color',
			'css'           => array(
				array(
					'property' => 'background-color',
					'selector' => '.fr-slider__progress--autoplay .fr-slider__progress-bar',
				),
			),
			'required' => array( 'progressBar', '=', true ),
		);

		// Auto Scroll.

		$this->controls['autoScroll'] = array(
			'group'    => 'options',
			'label'    => esc_html__( 'Turn Automatic Scrolling', 'frames' ),
			'type'     => 'checkbox',
			'info'     => esc_html__( 'Use Loop type slider', 'frames' ),
		);

		$this->controls['autoScrollSpeed'] = array(
			'group'    => 'autoScroll',
			'label'    => esc_html__( 'Speed', 'frames' ),
			'type'     => 'number',
			'info'     => esc_html__(
				'Speed in pixels per frame (1 = 60px/second).
Negative values scroll in opposite direction',
				'frames'
			),
			'inline'   => true,
			'required' => array( 'autoScroll', '=', true ),
		);

		$this->controls['autoScrollAutoStart'] = array(
			'group'    => 'autoScroll',
			'label'    => esc_html__( 'Auto start', 'frames' ),
			'type'     => 'checkbox',
			'inline'   => true,
			'required' => array( 'autoScroll', '=', true ),
		);

		$this->controls['autoScrollRewind'] = array(
			'group'    => 'autoScroll',
			'label'    => esc_html__( 'Rewind', 'frames' ),
			'type'     => 'checkbox',
			'inline'   => true,
			'required' => array( 'autoScroll', '=', true ),
		);

		$this->controls['autoScrollPauseOnHover'] = array(
			'group'    => 'autoScroll',
			'label'    => esc_html__( 'Pause on hover', 'frames' ),
			'type'     => 'checkbox',
			'inline'   => true,
			'required' => array( 'autoScroll', '=', true ),
		);

		$this->controls['autoScrollPauseOnFocus'] = array(
			'group'    => 'autoScroll',
			'label'    => esc_html__( 'Pause on focus', 'frames' ),
			'type'     => 'checkbox',
			'inline'   => true,
			'required' => array( 'autoScroll', '=', true ),
		);

		// Slide drag accessibility controls.
		$this->controls['dragAccessibilitySeparator'] = array(
			'group'    => 'options',
			'label'    => esc_html__( 'Changing slides', 'frames' ),
			'type'     => 'separator',
		);

		$this->controls['wheel'] = array(
			'group' => 'options',
			'label' => esc_html__( 'Use mouse wheel to change slides', 'frames' ),
			'type'     => 'checkbox',
			'inline'   => true,
		);

		if ( Flag::is_on( 'MOUSE_WHEEL_RELEASE' ) ) {
			$this->controls['releaseWheel'] = array(
				'group' => 'options',
				'label' => esc_html__( 'Release mouse wheel', 'frames' ),
				'type'     => 'checkbox',
				'inline'   => true,
				'required' => array( 'wheel', '=', true ),
			);
		}

		$this->controls['drag'] = array(
			'group'       => 'options',
			'label'       => esc_html__( 'Draggable', 'frames' ),
			'type'        => 'select',
			'options'     => array(
				'true'  => esc_html__( 'Enable', 'frames' ),
				'false' => esc_html__( 'Disable', 'frames' ),
				'free' => esc_html__( 'Free', 'frames' ),
			),
			'breakpoints' => true,
			'inline'      => true,
			'placeholder' => esc_html__( 'True', 'frames' ),
		);

		$this->controls['snap'] = array(
			'group'       => 'options',
			'label'    => esc_html__( 'Snap', 'frames' ),
			'type'     => 'checkbox',
			'info'     => esc_html__( 'Whether to snap to nearest slide', 'frames' ),
			'breakpoints' => true,
			'inline'   => true,
			'required'    => array( 'drag', '=', 'free' ),
		);

		// Slide Styles.
		$this->controls['slideOpacity'] = array(
			'group'       => 'slideStyles',
			'label'       => esc_html__( 'Opacity', 'frames' ),
			'type'        => 'number',
			'css'           => array(
				array(
					'property' => 'opacity',
					'selector' => '.splide__slide',
				),
			),
		);

		$this->controls['slideBackgroundColor'] = array(
			'group'       => 'slideStyles',
			'label'       => esc_html__( 'Background Color', 'frames' ),
			'type'        => 'color',
			'css'           => array(
				array(
					'property' => 'background',
					'selector' => '.splide__slide',
					'important' => 'true',
				),
			),
		);

		$this->controls['slideBorder'] = array(
			'group'       => 'slideStyles',
			'label'       => esc_html__( 'Border', 'frames' ),
			'type'        => 'border',
			'css'           => array(
				array(
					'property' => 'border',
					'selector' => '.splide__slide',
					// 'important' => 'true', //.
				),
			),
		);

		$this->controls['slideTypography'] = array(
			'group'       => 'slideStyles',
			'label'       => esc_html__( 'Typography', 'frames' ),
			'type'        => 'typography',
			'css'           => array(
				array(
					'property' => 'font',
					'selector' => '.splide__slide',
					'important' => 'true',
				),
			),
		);

		// Active Slide Controls.

		$this->controls['updateOnMove'] = array(
			'group'    => 'active',
			'tab'         => 'content',
			'label'       => esc_html__( 'Change active state', 'frames' ),
			'type'        => 'select',
			'options'     => array(
				'onMove' => 'On Slide Move',
				'afterMove' => 'After Slide Move',
			),
			'inline'      => true,
			'clearable'   => false,

		);

		$this->controls['activeOpacity'] = array(
			'group'       => 'active',
			'label'       => esc_html__( 'Opacity', 'frames' ),
			'type'        => 'number',
			'css'           => array(
				array(
					'property' => 'opacity',
					'selector' => '.splide__slide.is-active',
				),
			),
		);

		$this->controls['activeBackgroundColor'] = array(
			'group'       => 'active',
			'label'       => esc_html__( 'Background Color', 'frames' ),
			'type'        => 'color',
			'css'           => array(
				array(
					'property' => 'background',
					'selector' => '.splide__slide.is-active',
					'important' => 'true',
				),
			),
		);

		$this->controls['activeBorder'] = array(
			'group'       => 'active',
			'label'       => esc_html__( 'Border', 'frames' ),
			'type'        => 'border',
			'css'           => array(
				array(
					'property' => 'border',
					'selector' => '.splide__slide.is-active',
					// 'important' => 'true', // .
				),
			),
		);

		$this->controls['activeTypography'] = array(
			'group'       => 'active',
			'label'       => esc_html__( 'Typography', 'frames' ),
			'type'        => 'typography',
			'css'           => array(
				array(
					'property' => 'font',
					'selector' => '.splide__slide.is-active',
					'important' => 'true',
				),
			),
		);

		// active translate controls.

		$this->controls['activeTranslate'] = array(
			'group'       => 'active',
			'label'       => esc_html__( 'Transform', 'frames' ),
			'type'        => 'transform',
			'css'           => array(
				array(
					'property' => 'transform',
					'selector' => '.splide__slide.is-active',
					'important' => 'true',
				),
			),
		);

		// Controls.
		$this->controls['syncSeparator'] = array(
			'group'    => 'controls',
			'label'    => esc_html__( 'Sync Sliders Controls', 'frames' ),
			'type'     => 'separator',
		);

		$this->controls['isSync'] = array(
			'group'       => 'controls',
			'label'    => esc_html__( 'Sync with other sliders', 'frames' ),
			'type'     => 'checkbox',
			'info'     => esc_html__( 'Whether to sync with other sliders or with custom slider navigation', 'frames' ),
			'breakpoints' => false,
			'inline'   => true,
		);

		$this->controls['syncId'] = array(
			'group'       => 'controls',
			'label'    => esc_html__( 'Slider Name to Sync Sliders', 'frames' ),
			'type'     => 'text',
			'breakpoints' => false,
			'placeholder' => 'sync-sliders-1',
			'required'    => array( 'isSync', '=', true ),
		);

		$this->controls['isNavigation'] = array(
			'group'       => 'controls',
			'label'    => esc_html__( 'Use slider for navigation', 'frames' ),
			'type'     => 'checkbox',
			'info'     => esc_html__( 'Use this slider as navigation for other sliders with same sync ID', 'frames' ),
			'breakpoints' => false,
			'inline'   => true,
			'default' => false,
			'required'    => array( 'isSync', '=', true ),
		);

		$this->controls['arrowsSeparator'] = array(
			'group'    => 'controls',
			'label'    => esc_html__( 'Arrows', 'frames' ),
			'type'     => 'separator',
		);

		$this->controls['arrows'] = array(
			'group'    => 'controls',
			'label'    => esc_html__( 'Use navigation arrows', 'frames' ),
			'type'     => 'checkbox',
			'inline'   => true,
			'rerender' => true,
			'default'  => 'true',
			'breakpoints' => true,
		);

		$this->controls['arrowSize'] = array(
			'group'       => 'controls',
			'label'       => esc_html__( 'Icon size', 'frames' ),
			'type'        => 'number',
			'units'       => true,
			'css'         => array(
				array(
					'property' => 'font-size',
					'selector' => '.splide__arrows .splide__arrow',
				),
			),
			'default'     => 'var(--space-l)',
			'breakpoints' => true,
			'required'    => array( 'arrows', '!=', '' ),
		);

		$this->controls['arrowHeight'] = array(
			'group'       => 'controls',
			'label'       => esc_html__( 'Height', 'frames' ),
			'type'        => 'number',
			'units'       => true,
			'css'         => array(
				array(
					'property' => 'height',
					'selector' => '.splide__arrow',
				),
			),
			'placeholder' => 'var(--space-l)',
			'required'    => array( 'arrows', '!=', '' ),
		);

		$this->controls['arrowWidth'] = array(
			'group'       => 'controls',
			'label'       => esc_html__( 'Width', 'frames' ),
			'type'        => 'number',
			'units'       => true,
			'css'         => array(
				array(
					'property' => 'width',
					'selector' => '.splide__arrow',
				),
			),
			'placeholder' => 'var(--space-l)',
			'required'    => array( 'arrows', '!=', '' ),
		);

		$this->controls['arrowBackground'] = array(
			'group'    => 'controls',
			'label'    => esc_html__( 'Background', 'frames' ),
			'type'     => 'color',
			'css'      => array(
				array(
					'property' => 'background-color',
					'selector' => '.splide__arrow',
				),
			),
			'required' => array( 'arrows', '!=', '' ),
		);

		$this->controls['arrowBorder'] = array(
			'group'    => 'controls',
			'label'    => esc_html__( 'Border', 'frames' ),
			'type'     => 'border',
			'css'      => array(
				array(
					'property' => 'border',
					'selector' => '.splide__arrow',
				),
			),
			'required' => array( 'arrows', '!=', '' ),
		);

		$this->controls['arrowTypography'] = array(
			'group'    => 'controls',
			'label'    => esc_html__( 'Icon Color', 'frames' ),
			'type'     => 'color',
			'css'      => array(
				array(
					'property' => 'color',
					'selector' => '.splide__arrow',
				),
			),
			'required' => array(
				array( 'arrows', '!=', '' ),
				array( 'prevArrow.icon', '!=', '' ),
				array( 'nextArrow.icon', '!=', '' ),
			),
		);

		// DISABLED.
		$this->controls['disabledSeparator'] = array(
			'group'    => 'controls',
			'label'    => esc_html__( 'Disabled', 'frames' ),
			'type'     => 'separator',
			'required' => array( 'type', '!=', 'next' ),
		);

		$this->controls['disabledInfo'] = array(
			'group'    => 'controls',
			'content'  => esc_html__( ' Require Slide or Fade slider type  ', 'frames' ),
			'type'     => 'info',
			'required' => false,
		);

		$this->controls['disabledBackground'] = array(
			'group'    => 'controls',
			'label'    => esc_html__( 'Background', 'frames' ),
			'type'     => 'color',
			'css'      => array(
				array(
					'property' => 'background-color',
					'selector' => '.splide__arrow[disabled]',
				),
			),
			'required' => array( 'arrows', '!=', '' ),
		);

		$this->controls['disabledColor'] = array(
			'group'    => 'controls',
			'label'    => esc_html__( 'Color', 'frames' ),
			'type'     => 'color',
			'css'      => array(
				array(
					'property' => 'color',
					'selector' => '.splide__arrow[disabled]',
				),
			),
			'required' => array( 'arrows', '!=', '' ),
		);

		$this->controls['disabledBorder'] = array(
			'group'    => 'controls',
			'label'    => esc_html__( 'Border', 'frames' ),
			'type'     => 'border',
			'css'      => array(
				array(
					'property' => 'border',
					'selector' => '.splide__arrow[disabled]',
				),
			),
			'required' => array( 'arrows', '!=', '' ),
		);

		$this->controls['arrowDisabled'] = array(
			'group'    => 'controls',
			'label'    => esc_html__( 'Opacity', 'frames' ),
			'type'     => 'number',
			'css'      => array(
				array(
					'property' => 'opacity',
					'selector' => '.splide__arrow[disabled]',
				),
			),
			'default'  => '0.5',
			'required' => array( 'arrows', '!=', '' ),
		);

		// PREV ARROW.
		$this->controls['prevArrowSeparator'] = array(
			'group'    => 'controls',
			'label'    => esc_html__( 'Prev arrow', 'frames' ),
			'type'     => 'separator',
			'required' => array( 'arrows', '!=', '' ),
		);

		$this->controls['prevArrow'] = array(
			'group'    => 'controls',
			'label'    => esc_html__( 'Prev arrow', 'frames' ),
			'type'     => 'icon',
			'rerender' => true,
			'default'  => array(
				'library' => 'themify',
				'icon'    => 'ti-arrow-left',
			),
			'css'      => array(
				array(
					'selector' => '.splide__arrow--prev > *',
				),
			),
			'required' => array( 'arrows', '!=', '' ),
		);

		$this->controls['prevArrowTop'] = array(
			'group'    => 'controls',
			'label'    => esc_html__( 'Top', 'frames' ),
			'type'     => 'number',
			'units'    => true,
			'css'      => array(
				array(
					'property' => 'inset-block-start',
					'selector' => '.splide__arrow--prev',
				),
			),
			'required' => array( 'arrows', '!=', '' ),
			'default'  => '50%',
		);

		$this->controls['prevArrowRight'] = array(
			'group'    => 'controls',
			'label'    => esc_html__( 'Right', 'frames' ),
			'type'     => 'number',
			'units'    => true,
			'css'      => array(
				array(
					'property' => 'right',
					'selector' => '.splide__arrow--prev',
				),
			),
			'required' => array( 'arrows', '!=', '' ),
		);

		$this->controls['prevArrowBottom'] = array(
			'group'    => 'controls',
			'label'    => esc_html__( 'Bottom', 'frames' ),
			'type'     => 'number',
			'units'    => true,
			'css'      => array(
				array(
					'property' => 'inset-block-end',
					'selector' => '.splide__arrow--prev',
				),
			),
			'required' => array( 'arrows', '!=', '' ),
		);

		$this->controls['prevArrowLeft'] = array(
			'group'    => 'controls',
			'label'    => esc_html__( 'Left', 'frames' ),
			'type'     => 'number',
			'units'    => true,
			'css'      => array(
				array(
					'property' => 'inset-inline-start',
					'selector' => '.splide__arrow--prev',
				),
			),
			'required' => array( 'arrows', '!=', '' ),
			'default'  => 'var(--space-xs)',
		);

		$this->controls['prevArrowTranslate'] = array(
			'group'    => 'controls',
			'label'    => esc_html__( 'Translate x,y', 'frames' ),
			'type'     => 'number',
			'units'    => true,
			'css'      => array(
				array(
					'property' => 'transform:translate',
					'selector' => '.splide__arrow--prev',
				),
			),
			'required' => array( 'arrows', '!=', '' ),
			'default'  => '0,-50%',
		);

		// NEXT ARROW.
		$this->controls['nextArrowSeparator'] = array(
			'group'    => 'controls',
			'label'    => esc_html__( 'Next arrow', 'frames' ),
			'type'     => 'separator',
			'required' => array( 'arrows', '!=', '' ),
		);

		$this->controls['nextArrow'] = array(
			'group'    => 'controls',
			'label'    => esc_html__( 'Next arrow', 'frames' ),
			'type'     => 'icon',
			'rerender' => true,
			'css'      => array(
				array(
					'selector' => '.splide__arrow--next > *',
				),
			),
			'required' => array( 'arrows', '!=', '' ),
			'default'  => array(
				'library' => 'themify',
				'icon'    => 'ti-arrow-right',
			),
		);

		$this->controls['nextArrowTop'] = array(
			'group'    => 'controls',
			'label'    => esc_html__( 'Top', 'frames' ),
			'type'     => 'number',
			'units'    => true,
			'css'      => array(
				array(
					'property' => 'inset-block-start',
					'selector' => '.splide__arrow--next',
				),
			),
			'required' => array( 'arrows', '!=', '' ),
			'default'  => '50%',
		);

		$this->controls['nextArrowRight'] = array(
			'group'    => 'controls',
			'label'    => esc_html__( 'Right', 'frames' ),
			'type'     => 'number',
			'units'    => true,
			'css'      => array(
				array(
					'property' => 'inset-inline-end',
					'selector' => '.splide__arrow--next',
				),
			),
			'required' => array( 'arrows', '!=', '' ),
			'default'  => 'var(--space-xs)',
		);

		$this->controls['nextArrowBottom'] = array(
			'group'    => 'controls',
			'label'    => esc_html__( 'Bottom', 'frames' ),
			'type'     => 'number',
			'units'    => true,
			'css'      => array(
				array(
					'property' => 'inset-block-end',
					'selector' => '.splide__arrow--next',
				),
			),
			'required' => array( 'arrows', '!=', '' ),
		);

		$this->controls['nextArrowLeft'] = array(
			'group'    => 'controls',
			'label'    => esc_html__( 'Left', 'frames' ),
			'type'     => 'number',
			'units'    => true,
			'css'      => array(
				array(
					'property' => 'inset-inline-start',
					'selector' => '.splide__arrow--next',
				),
			),
			'required' => array( 'arrows', '!=', '' ),
		);

		$this->controls['nextArrowTranslate'] = array(
			'group'    => 'controls',
			'label'    => esc_html__( 'Translate x,y', 'frames' ),
			'type'     => 'number',
			'units'    => true,
			'css'      => array(
				array(
					'property' => 'transform:translate',
					'selector' => '.splide__arrow--next',
				),
			),
			'required' => array( 'arrows', '!=', '' ),
			'default'  => '0,-50%',
		);

		// Pagination (dots).
		$this->controls['dotsSeparator'] = array(
			'group'    => 'controls',
			'label'    => esc_html__( 'Pagination Dots', 'frames' ),
			'type'     => 'separator',
			'description' => esc_html__( 'Pagination dots are static in builder preview', 'frames' ),
		);

		$this->controls['paginationNavigationAllert'] = array(
			'group'    => 'controls',
			'content'    => esc_html__( 'If slider is used as navigation for other slider you can not use pagination', 'frames' ),
			'type'     => 'info',
			'required' => array( 'isNavigation', '=', true ),
		);

		$this->controls['pagination'] = array(
			'group'    => 'controls',
			'label'    => esc_html__( 'Show', 'frames' ),
			'type'     => 'checkbox',
			'inline'   => true,
			'rerender' => true,
			'default'  => true,
			'required' => array( 'isNavigation', '=', false ),
		);

		$this->controls['paginationSpacing'] = array(
			'group'       => 'controls',
			'label'       => esc_html__( 'Margin', 'frames' ),
			'type'        => 'spacing',
			'css'         => array(
				array(
					'property' => 'margin',
					'selector' => '.splide__pagination .splide__pagination__page',
				),
			),
			'placeholder' => array(
				'top'    => '.5rem',
				'right'  => '.5rem',
				'bottom' => '.5rem',
				'left'   => '.5rem',
			),
			'required'    => array(
				array( 'pagination', '!=', '' ),
				array( 'isNavigation', '=', false )
			),
		);

		$this->controls['paginationHeight'] = array(
			'group'       => 'controls',
			'label'       => esc_html__( 'Height', 'frames' ),
			'type'        => 'number',
			'units'       => array(
				'px' => array(
					'min' => 1,
					'max' => 100,
				),
			),
			'css'         => array(
				array(
					'property' => 'height',
					'selector' => '.splide__pagination .splide__pagination__page',
				),
			),
			'placeholder' => '10px',
			'required'    => array(
				array( 'pagination', '!=', '' ),
				array( 'isNavigation', '=', false )
			),
		);

		$this->controls['paginationWidth'] = array(
			'group'       => 'controls',
			'label'       => esc_html__( 'Width', 'frames' ),
			'type'        => 'number',
			'units'       => array(
				'px' => array(
					'min' => 1,
					'max' => 100,
				),
			),
			'css'         => array(
				array(
					'property' => 'width',
					'selector' => '.splide__pagination .splide__pagination__page',
				),
			),
			'placeholder' => '10px',
			'required'    => array(
				array( 'pagination', '!=', '' ),
				array( 'isNavigation', '=', false )
			),
		);

		$this->controls['paginationColor'] = array(
			'group'    => 'controls',
			'label'    => esc_html__( 'Color', 'frames' ),
			'type'     => 'color',
			'css'      => array(
				array(
					'property' => 'color',
					'selector' => '.splide__pagination .splide__pagination__page',
				),
				array(
					'property' => 'background-color',
					'selector' => '.splide__pagination .splide__pagination__page',
				),
			),
			'required'    => array(
				array( 'pagination', '!=', '' ),
				array( 'isNavigation', '=', false )
			),
		);

		$this->controls['paginationBorder'] = array(
			'group'    => 'controls',
			'label'    => esc_html__( 'Border', 'frames' ),
			'type'     => 'border',
			'css'      => array(
				array(
					'property' => 'border',
					'selector' => '.splide__pagination .splide__pagination__page',
				),
			),
			'required'    => array(
				array( 'pagination', '!=', '' ),
				array( 'isNavigation', '=', false )
			),
		);

		// ACTIVE.
		$this->controls['paginationActiveSeparator'] = array(
			'group'    => 'controls',
			'label'    => esc_html__( 'Active', 'frames' ),
			'type'     => 'separator',
			'required'    => array(
				array( 'pagination', '!=', '' ),
				array( 'isNavigation', '=', false )
			),
		);

		$this->controls['paginationHeightActive'] = array(
			'group'    => 'controls',
			'label'    => esc_html__( 'Height', 'frames' ),
			'type'     => 'number',
			'units'    => true,
			'css'      => array(
				array(
					'property' => 'height',
					'selector' => '.splide__pagination .splide__pagination__page.is-active',
				),
			),
			'required'    => array(
				array( 'pagination', '!=', '' ),
				array( 'isNavigation', '=', false )
			),
		);

		$this->controls['paginationWidthActive'] = array(
			'group'    => 'controls',
			'label'    => esc_html__( 'Width', 'frames' ),
			'type'     => 'number',
			'units'    => true,
			'css'      => array(
				array(
					'property' => 'width',
					'selector' => '.splide__pagination .splide__pagination__page.is-active',
				),
			),
			'required'    => array(
				array( 'pagination', '!=', '' ),
				array( 'isNavigation', '=', false )
			),
		);

		$this->controls['paginationColorActive'] = array(
			'group'    => 'controls',
			'label'    => esc_html__( 'Color', 'frames' ),
			'type'     => 'color',
			'css'      => array(
				array(
					'property' => 'color',
					'selector' => '.splide__pagination .splide__pagination__page.is-active',
				),
				array(
					'property' => 'background-color',
					'selector' => '.splide__pagination .splide__pagination__page.is-active',
				),
			),
			'required'    => array(
				array( 'pagination', '!=', '' ),
				array( 'isNavigation', '=', false )
			),
		);

		$this->controls['paginationBorderActive'] = array(
			'group'    => 'controls',
			'label'    => esc_html__( 'Border', 'frames' ),
			'type'     => 'border',
			'css'      => array(
				array(
					'property' => 'border',
					'selector' => '.splide__pagination .splide__pagination__page.is-active',
				),
			),
			'required'    => array(
				array( 'pagination', '!=', '' ),
				array( 'isNavigation', '=', false )
			),
		);

		// POSITION.
		$this->controls['paginationPositionSeparator'] = array(
			'group'    => 'controls',
			'label'    => esc_html__( 'Position', 'frames' ),
			'type'     => 'separator',
			'required'    => array(
				array( 'pagination', '!=', '' ),
				array( 'isNavigation', '=', false )
			),
		);

		$this->controls['paginationTop'] = array(
			'group'    => 'controls',
			'label'    => esc_html__( 'Top', 'frames' ),
			'type'     => 'number',
			'units'    => true,
			'css'      => array(
				array(
					'property' => 'top',
					'selector' => '.splide__pagination',
				),
				array(
					'property' => 'bottom',
					'value'    => 'auto',
					'selector' => '.splide__pagination',
				),
			),
			'required'    => array(
				array( 'pagination', '!=', '' ),
				array( 'isNavigation', '=', false )
			),
		);

		$this->controls['paginationRight'] = array(
			'group'    => 'controls',
			'label'    => esc_html__( 'Right', 'frames' ),
			'type'     => 'number',
			'units'    => true,
			'css'      => array(
				array(
					'property' => 'right',
					'selector' => '.splide__pagination',
				),
				array(
					'property' => 'left',
					'value'    => 'auto',
					'selector' => '.splide__pagination',
				),
				array(
					'property' => 'transform',
					'selector' => '.splide__pagination',
					'value'    => 'translateX(0)',
				),
			),
			'required'    => array(
				array( 'pagination', '!=', '' ),
				array( 'isNavigation', '=', false )
			),
		);

		$this->controls['paginationBottom'] = array(
			'group'    => 'controls',
			'label'    => esc_html__( 'Bottom', 'frames' ),
			'type'     => 'number',
			'units'    => true,
			'css'      => array(
				array(
					'property' => 'bottom',
					'selector' => '.splide__pagination',
				),
			),
			'required'    => array(
				array( 'pagination', '!=', '' ),
				array( 'isNavigation', '=', false )
			),
		);

		$this->controls['paginationLeft'] = array(
			'group'    => 'controls',
			'label'    => esc_html__( 'Left', 'frames' ),
			'type'     => 'number',
			'units'    => true,
			'css'      => array(
				array(
					'property' => 'left',
					'selector' => '.splide__pagination',
				),
				array(
					'property' => 'right',
					'value'    => 'auto',
					'selector' => '.splide__pagination',
				),
				array(
					'property' => 'transform',
					'selector' => '.splide__pagination',
					'value'    => 'translateX(0)',
				),
			),
			'required'    => array(
				array( 'pagination', '!=', '' ),
				array( 'isNavigation', '=', false )
			),
		);

		// Tags.
		$this->controls['tagSep'] = array(
			'group'    => 'accessibility',
			'label' => esc_html__( 'HTML tags', 'frames' ),
			'type'     => 'separator',
		);

		$this->controls['sliderTag'] = array(
			'group'    => 'accessibility',
			'tab'         => 'content',
			'label'       => esc_html__( 'Slider Tag', 'frames' ),
			'type'        => 'select',
			'options'     => array(
				'section' => 'Section',
				'div' => 'Div',
				'main' => 'Main',
				'aside' => 'Aside',
				'header' => 'Header',
				'footer' => 'Footer',
				'article' => 'Article',
				'nav' => 'Nav',
				'figure' => 'Figure',
			),
			'inline'      => true,
			'clearable'   => true,
			'default' => 'div',
		);

		$this->controls['listTag'] = array(
			'group'    => 'accessibility',
			'tab'         => 'content',
			'label'       => esc_html__( 'List Tag', 'frames' ),
			'type'        => 'select',
			'options'     => array(
				'ul' => 'Unordered List',
				'ol' => 'Ordered List',
				'dl' => 'Custom',
			),
			'inline'      => true,
			'clearable'   => true,
			'default' => 'ul',
		);

		// accessibility.
		$this->controls['accessibilitySep'] = array(
			'group'    => 'accessibility',
			'label' => esc_html__( 'Arias', 'frames' ),
			'type'     => 'separator',
		);

		$this->controls['ariaNext'] = array(
			'group'    => 'accessibility',
			'label'    => esc_html__( 'Aria-label for next arrow', 'frames' ),
			'type'     => 'text',
			'placeholder' => 'Next slide',
			'required'    => array( 'arrows', '!=', array( 'false' ) ),
		);

		$this->controls['ariaPrev'] = array(
			'group'    => 'accessibility',
			'label'    => esc_html__( 'Aria-label for prev arrow', 'frames' ),
			'type'     => 'text',
			'placeholder' => 'Previous slide',
			'required'    => array( 'arrows', '!=', array( 'false' ) ),
		);

		$this->controls['ariaSlideX'] = array(
			'group'    => 'accessibility',
			'label'    => esc_html__( 'Aria-label for each navigation slide', 'frames' ),
			'type'     => 'text',
			'placeholder' => 'Go to slide %s'
		);

		$this->controls['ariaPageX'] = array(
			'group'    => 'accessibility',
			'label'    => esc_html__( 'Aria-label for pagination', 'frames' ),
			'type'     => 'text',
			'placeholder' => 'Go to page %s',
			'required'    => array( 'pagination', '!=', '' ),
		);

		$this->controls['ariaCarousel'] = array(
			'group'    => 'accessibility',
			'label'    => esc_html__( 'Aria-roledescription for slider', 'frames' ),
			'type'     => 'text',
			'default' => 'Carousel'
		);

		$this->controls['ariaSelect'] = array(
			'group'    => 'accessibility',
			'label'    => esc_html__( 'Aria-label for pagination', 'frames' ),
			'type'     => 'text',
			'placeholder' => 'Select a slide to show',
			'required'    => array( 'pagination', '!=', '' ),
		);

		$this->controls['ariaSlide'] = array(
			'group'    => 'accessibility',
			'label'    => esc_html__( 'Aria-roledescription for each slide', 'frames' ),
			'type'     => 'text',
			'placeholder' => 'Slide'
		);

		$this->controls['ariaSlideLabel'] = array(
			'group'    => 'accessibility',
			'label'    => esc_html__( 'Aria-label for each slide', 'frames' ),
			'type'     => 'text',
			'placeholder' => '%s of %s'
		);

	}





	/**
	 * Get the nestable items for the widget.
	 *
	 * @return array
	 */
	public function get_nestable_item() {
		return array(
			'name'     => 'div',
			// 'name'  => 'block',
			'label'    => esc_html__( 'Slide', 'frames' ) . ' {item_index}',
			'settings' => array(

				/*
				'_alignItems'     => 'center',
				'_direction'      => 'column',
				'_justifyContent' => 'center',
				'_background' => array(
					'color' => array(
						'raw' => 'var(--neutral-ultra-light)',
					),
				),
				'_height' => '40rem',
				*/

				// NOTE: Undocumented (@since 1.5 to apply hard-coded hidden settings).
				'_hidden'         => array(
					'_cssClasses' => 'fr-slide splide__slide',
				),
			),
			'children' => array(
				array(
					'name'     => 'heading',
					'settings' => array(
						'text' => esc_html__( 'Slide', 'frames' ) . ' {item_index}',
					),
				),
			),
		);
	}


	/**
	 * Get the nestable children for the widget.
	 *
	 * @return array
	 */
	public function get_nestable_children() {
		$children = array();

		for ( $i = 0; $i < 3; $i++ ) {
			$item = $this->get_nestable_item();

			// Replace {item_index} with $index.
			$item       = json_encode( $item );
			$item       = str_replace( '{item_index}', $i + 1, $item );
			$item       = json_decode( $item, true );
			$children[] = $item;
		}

		return $children;
	}

	/**
	 * Return numbers of slides
	 *
	 * @return int
	 */
	public function get_slides_count() {
		$slides = $this->get_nestable_children();
		return count( $slides );
	}


	/**
	 * Render arrows (use custom HTML solution as splideJS only accepts SVG path via 'arrowPath')
	 *
	 * @return string
	 */
	public function render_arrows() {
		$prev_arrow = ! empty( $this->settings['prevArrow'] ) ? self::render_icon( $this->settings['prevArrow'] ) : false;
		$next_arrow = ! empty( $this->settings['nextArrow'] ) ? self::render_icon( $this->settings['nextArrow'] ) : false;

		if ( ! $prev_arrow && ! $next_arrow ) {
			return;
		}

		$output = '<div class="splide__arrows fr-slider__arrows">';

		if ( $prev_arrow ) {
			$output .= '<button class="splide__arrow splide__arrow--prev" type="button">' . $prev_arrow . '</button>';
		}

		if ( $next_arrow ) {
			$output .= '<button class="splide__arrow splide__arrow--next" type="button">' . $next_arrow . '</button>';
		}

		$output .= '</div>';

		return $output;
	}

	/**
	 * Render play/pause button
	 *
	 *  @return string
	 */
	public function render_play_pause() {

		$play_button = ! empty( $this->settings['playButtonIcon'] ) ? self::render_icon( $this->settings['playButtonIcon'] ) : false;
		$pause_button = ! empty( $this->settings['pauseButtonIcon'] ) ? self::render_icon( $this->settings['pauseButtonIcon'] ) : false;

		if ( ! $play_button && ! $pause_button ) {
			return;
		}

		$output = '<div class="splide__play-pause fr-slider__play-pause">';
		$output .= '<button class="splide__toggle" type="button">';
		$output .= '<span class="splide__toggle__play">';
		$output .= '<span class="fr-slider__play-pause-icon">' . $play_button . '</span>';
		$output .= '</span>';
		$output .= '<span class="splide__toggle__pause">';
		$output .= '<span class="fr-slider__play-pause-icon">' . $pause_button . '</span>';
		$output .= '</span>';
		$output .= '</button>';
		$output .= '</div>';

		return $output;
	}

	/**
	 * Render progress bar
	 *
	 * @return string
	 */
	public function render_progress_bar() {
		$output = '<div class="splide__progress fr-slider__progress fr-slider__progress--autoplay">';
		$output .= '<div class="splide__progress__bar fr-slider__progress-bar">';
		$output .= '</div>';
		$output .= '</div>';

		return $output;

	}

	/**
	 * Render Static Pagination
	 *
	 * @return string
	 */
	public function render_pagination_in_builder() {

		$direction = ! empty( $this->settings['direction'] ) ? $this->settings['direction'] : ( is_rtl() ? 'rtl' : 'ltr' );

		if ( 'rtl' === $direction ) {
			$direction = 'rtl';
		} else if ( 'ltr' === $direction ) {
			$direction = 'ltr';
		} else {
			$direction = 'ttb';
		}

		$output = '<ul class="splide__pagination splide__pagination--' . $direction . ' fr-slider__pagination--builder">';
		$output .= '<li role="presentation">';
		$output .= '<button class="splide__pagination__page is-active"></button>';
		$output .= '</li>';
		$output .= '<li role="presentation">';
		$output .= '<button class="splide__pagination__page"></button>';
		$output .= '</li>';
		$output .= '</ul>';

		return $output;
	}

	/**
	 * End Render for Custom Navigation
	 *
	 * @param string $control The control name.
	 * @param string $default The default value.
	 * @param array  $splide_options The splide options.
	 * @param array  $settings The settings.
	 * @return void
	 */
	private function ariaControl( $control, $default, &$splide_options, $settings ) {
		$controlValue = substr( $control, 4 );
		$controlValue = lcfirst( $controlValue );

		$splide_options['i18n'] += array(
			$controlValue => ! empty( $settings[ $control ] ) ? $settings[ $control ] : $default
		);
	}

	/**
	 * Check if the value is numeric and add px if it is.
	 *
	 * @param mixed $value The value.
	 * @return void
	 */
	private function numericDefault( $value ) {
		if ( is_numeric( $value ) ) {
			$value = "{$value}px";
		}
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

		// Controls.
		$type = ! empty( $settings['type'] ) ? $settings['type'] : 'loop';

		$gap = ! empty( $settings['gap'] ) ? $settings['gap'] : 0;
		$fixedWidth = ! empty( $settings['fixedWidth'] ) ? $settings['fixedWidth'] : false;
		$fixedHeight = ! empty( $settings['fixedHeight'] ) ? $settings['fixedHeight'] : false;
		$fixedHeight = ! empty( $settings['fixedHeight'] ) ? $settings['fixedHeight'] : false;
		$height = ! empty( $settings['height'] ) ? $settings['height'] : false;
		$updateOnMove = ! empty( $settings['updateOnMove'] ) ? $settings['updateOnMove'] : '';

		$focus = ! empty( $settings['focus'] ) ? $settings['focus'] : 0;
		if ( 'center' === $focus ) {
			$focus = 'center';
		} else {
			$focus = (int) $focus;
		}

		$breakout = isset( $settings['breakout'] );

		if ( $breakout ) {
			$this->set_attribute( '_root', 'data-fr-slider-breakout', 'true' );
		} else {
			$this->set_attribute( '_root', 'data-fr-slider-breakout', 'false' );
		}

		// if ( ! $this->is_frontend ) {} // TODO: check if this is needed.

		$this->numericDefault( $gap );
		$this->numericDefault( $fixedWidth );
		$this->numericDefault( $fixedHeight );
		$this->numericDefault( $height );

		$isSync = isset( $settings['isSync'] );
		$syncId = ! empty( $settings['syncId'] ) ? $settings['syncId'] : '';

		if ( $isSync ) {
			$this->set_attribute( '_root', 'data-fr-slider-sync', 'true' );
			$this->set_attribute( '_root', 'data-fr-slider-sync-id', $syncId );
		} else {
			$this->set_attribute( '_root', 'data-fr-slider-sync', 'false' );
			$this->set_attribute( '_root', 'data-fr-slider-sync-id', '' );
		}

		$isAutoScroll = isset( $settings['autoScroll'] );

		$direction = ! empty( $settings['direction'] ) ? $settings['direction'] : ( is_rtl() ? 'rtl' : 'ltr' );

		// tags.
		$listTag = isset( $settings['listTag'] ) ? esc_html( $settings['listTag'] ) : 'ul';
		$sliderTag = isset( $settings['sliderTag'] ) ? esc_html( $settings['sliderTag'] ) : 'div';

		if ( isset( $settings['drag'] ) ) {
			if ( 'true' === $settings['drag'] ) {
				$drag = true;
			} elseif ( 'false' === $settings['drag'] ) {
				$drag = false;
			} else {
				$drag = 'free';
			}
		} else {
			$drag = true;
		}

		// Arrows.
		$arrows = isset( $settings['arrows'] );

		if ( $arrows ) {
			// Custom arrows set OR use default splide SVG arrows if no custom arrows set.
			$arrows = ( ! empty( $settings['prevArrow'] ) && ! empty( $settings['nextArrow'] ) ) || ( empty( $settings['prevArrow'] ) && empty( $settings['nextArrow'] ) );
		} else {
			$arrows = false;
		}

		$per_page = ! empty( $settings['perPage'] ) ? $settings['perPage'] : 1;

		$splide_options = array(
			'type'         => $type,
			'direction'    => $direction,
			'keyboard'     => ! empty( $settings['keyboard'] ) ? $settings['keyboard'] : 'global', // 'focused', false
			'gap'          => $gap,
			'fixedWidth'   => $fixedWidth,
			'fixedHeight'  => $fixedHeight,
			'start'        => ! empty( $settings['start'] ) ? $settings['start'] : 0,
			'perPage'      => ( 'loop' === $type ) ? floor( $per_page ) : $per_page,
			'perMove'      => ! empty( $settings['perMove'] ) ? $settings['perMove'] : 1,
			'speed'        => ! empty( $settings['speed'] ) ? $settings['speed'] : 400,
			'drag'         => $drag,
			'snap'         => isset( $settings['snap'] ) ? 'true' === $settings['snap'] : false,
			'interval'     => ! empty( $settings['interval'] ) ? $settings['interval'] : 3000,
			'autoplay'     => isset( $settings['autoplay'] ),
			'pauseOnHover' => isset( $settings['pauseOnHover'] ),
			'pauseOnFocus' => isset( $settings['pauseOnFocus'] ),
			'arrows'       => $arrows,
			'pagination'   => isset( $settings['pagination'] ),
			'focus'        => $focus,
			// 'classes' => [
			// 'pagination' => 'fr-slider__pagination',
			// 'page' => 'fr-slider__pagination__page',
			// ],
		);

		if ( 'ttb' === $direction && ! empty( $settings['height'] ) ) {
			$splide_options['height'] = $height;
		}

		if ( 'onMove' === $updateOnMove ) {
			$splide_options['updateOnMove'] = true;
		}

		if ( isset( $settings['rewind'] ) && 'loop' !== $type ) {
			$splide_options['rewind'] = $settings['rewind'];

			if ( ! empty( $settings['rewindSpeed'] ) ) {
				$splide_options['rewindSpeed'] = $settings['rewindSpeed'];
			}

			if ( isset( $settings['rewindByDrag'] ) ) {
				$splide_options['rewindByDrag'] = $settings['rewindByDrag'];
			}
		}

		// if  isset( $settings['isNavigation'] ) === true than
		// $splide_options['isNavigation'] = true and $splide_options['pagination'] = false // TODO: check if this is needed.

		if ( isset( $settings['isNavigation'] ) ) {
			$splide_options['isNavigation'] = true;
			$splide_options['pagination'] = false;
		}

		// Accessibility options.
		if ( ! empty( $settings['ariaCarousel'] ) || ! empty( $settings['ariaPrev'] ) || ! empty( $settings['ariaSlideX'] ) || ! empty( $settings['ariaPageX'] ) || ! empty( $settings['ariaSelect'] ) || ! empty( $settings['ariaSlide'] ) || ! empty( $settings['ariaSlideLabel'] ) ) {
			$splide_options['i18n'] = array();
		}

		if ( isset( $splide_options['i18n'] ) ) {
			$this->ariaControl( 'ariaNext', 'Next slide', $splide_options, $settings );
			$this->ariaControl( 'ariaCarousel', 'Carousel', $splide_options, $settings );
			$this->ariaControl( 'ariaPrev', 'Previous slide', $splide_options, $settings );
			$this->ariaControl( 'ariaSlideX', 'Go to slide %s', $splide_options, $settings );
			$this->ariaControl( 'ariaPageX', 'Go to page %s', $splide_options, $settings );
			$this->ariaControl( 'ariaSelect', 'Select a slide to show', $splide_options, $settings );
			$this->ariaControl( 'ariaSlide', 'Slide', $splide_options, $settings );
			$this->ariaControl( 'ariaSlideLabel', '%s of %s', $splide_options, $settings );
		}

		// Wheel scroll.
		if ( isset( $settings['wheel'] ) ) {
			$splide_options['wheel'] = true;
			$splide_options['wheelSleep'] = 200;
			if ( Flag::is_on( 'MOUSE_WHEEL_RELEASE' ) ) {
				$splide_options['wheelMinThreshold'] = 5;
				if ( isset( $settings['releaseWheel'] ) ) {
					$splide_options['releaseWheel'] = true;
				}
			}
		}

		// Auto scroll.
		if ( $isAutoScroll && $this->is_frontend ) {
			$this->set_attribute( '_root', 'data-fr-slider-auto-scroll', 'true' );
		} else {
			$this->set_attribute( '_root', 'data-fr-slider-auto-scroll', 'false' );
		}

		if ( $isAutoScroll && $this->is_frontend ) {
				$splide_options['autoScroll'] = array(
					'speed'                 => isset( $settings['autoScrollSpeed'] ) ? floatval( $settings['autoScrollSpeed'] ) : 1,
					'pauseOnHover'  => isset( $settings['autoScrollPauseOnHover'] ),
					'pauseOnFocus'  => isset( $settings['autoScrollPauseOnFocus'] ),
					'rewind'                => isset( $settings['autoScrollRewind'] ),
					'autoStart'         => isset( $settings['autoScrollAutoStart'] ),
				);

		}

		// STEP: Add settings per breakpoints to splide options.
		$breakpoints = array();

		foreach ( \Bricks\Breakpoints::$breakpoints as $breakpoint ) {
			foreach ( array_keys( $splide_options ) as $option ) {
				$setting_key      = "$option:{$breakpoint['key']}";
				$breakpoint_width = ! empty( $breakpoint['width'] ) ? $breakpoint['width'] : false;
				$setting_value    = isset( $settings[ $setting_key ] ) ? $settings[ $setting_key ] : false;

				// Spacing requires a unit.
				if ( 'gap' === $option ) {
					// Add default unit.
					if ( is_numeric( $setting_value ) ) {
						$setting_value = "{$setting_value}px";
					}
				}

				if ( $breakpoint_width && false !== $setting_value ) {
					$breakpoints[ $breakpoint_width ][ $option ] = $setting_value;
				}
			}
		}

		if ( count( $breakpoints ) ) {
			$splide_options['breakpoints'] = $breakpoints;
		}

		// Builder: Disable splideJS drag to allow for Bricks DnD.
		if ( ! $this->is_frontend ) {
			$splide_options['autoplay'] = false;
			$splide_options['noDrag']   = '.bricks-draggable-item';
			$splide_options['pagination'] = false;
			$splide_options['drag'] = false;
		}

		if ( is_array( $splide_options ) ) {
			$splide_options = wp_json_encode( $splide_options );
		}

		// TODO: Remove commented out code if it's stable after 1.4.11 .
		// 1.4.9 <
		// $splide_options = preg_replace( '/\s+(?![^()]*\))/', '', $splide_options ); //.

		// 1.4.10
		// $splide_options = preg_replace( '/\s*([\{\}\[\]:;,.])\s*/', '$1 ', $splide_options ); //.
		// $splide_options = preg_replace( '/([:;,.])\s+/', '$1 ', $splide_options ); //.

		// STEP: Render slider.

		$this->set_attribute( '_root', 'class', 'splide' );
		$this->set_attribute( '_root', 'data-splide', trim( $splide_options ) );
		$this->set_attribute( '_root', 'class', 'fr-slider' );
		$this->set_attribute( 'splide__list', 'class', 'splide__list' );
		$this->set_attribute( 'splide__list', 'class', 'fr-slider__list' );
		$this->set_attribute( 'splide__track', 'class', 'splide__track' );
		$this->set_attribute( 'splide__track', 'class', 'fr-slider__track' );

		$output = '<' . $sliderTag . " {$this->render_attributes( '_root' )}>";
		$output .= "<div {$this->render_attributes('splide__track')}>";
		$output .= '<' . $listTag . " {$this->render_attributes('splide__list')}>";
		$output .= \Bricks\Frontend::render_children( $this );
		$output .= '</' . $listTag . '>'; // .splide__list.
		$output .= '</div>'; // .splide__track.
		if ( isset( $settings['arrows'] ) ) {
			$output .= $this->render_arrows();
		}

		if ( isset( $settings['playPauseButton'] ) && isset( $settings['autoplay'] ) ) {
			$output .= $this->render_play_pause();
		}

		if ( ! $this->is_frontend && isset( $settings['pagination'] ) && isset( $settings['isNavigation'] ) !== true ) {
			$output .= $this->render_pagination_in_builder();
		}

		if ( isset( $settings['progressBar'] ) && isset( $settings['autoplay'] ) ) {
			$output .= $this->render_progress_bar();
		}

		$output .= '</' . $sliderTag . '>'; // _root

		echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	}

}
