<?php
/**
 * Modal Widget.
 *
 * @package Frames_Client
 */

namespace Frames_Client\Widgets\Modal;

use Frames_Client\Helpers\Flag;
use \Frames_Client\Widget_Manager;
// TODO @Hakira-Shymuy remove flag after feature finished and tested.
define( 'FRAMES_ENABLE_MODAL_EXIT_INTENT', false );
define( 'FRAMES_MODAL_SLIDER_COMPATIBILITY', false );

/**
 * Modal class.
 */
class Modal_Widget extends \Bricks\Element {

	/**
	 * Use predefined element category 'general'.
	 *
	 * @var string
	 */
	public $category = 'Frames';

	/**
	 * I Might have create waaay to little fo a specific name... It might collide with live projects... (need to discuss I want to change it to fr-modal)
	 *
	 * @var string
	 */
	public $name = 'fr-modal';

	/**
	 * Themify icon font class.
	 *
	 * @var string
	 */
	public $icon = 'fas fa-copy';

	/**
	 * Default CSS selector.
	 *
	 * @var string
	 */
	public $css_selector = '.modal-wrapper';

	/**
	 * Scripts to be enqueued.
	 *
	 * @var array
	 */
	public $scripts = array( 'modal_script' );

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
	// public function get_methods()
	// {
	// include('inc/modal-functions.php');
	// } // TODO: check if this is needed.


	/**
	 * Get widget label.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget Label.
	 */
	public function get_label() {
		return esc_html__( 'Frames Modal', 'frames' );
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
			'title' => esc_html__( 'General Settings', 'frames' ),
			'tab'   => 'content',
		);

		// $this->control_groups['triggers'] = array(
		// 'title' => esc_html__( 'Action Triggers', 'frames' ),
		// 'tab'       => 'content',
		// 'required'   => array(
		// 'triggerType',
		// '=',
		// true,
		// ),
		// );

		// $this->control_groups['overlay'] = array(
		// 'title' => esc_html__( 'Overlay', 'frames' ),
		// 'tab'   => 'content',
		// );

		$this->control_groups['positioning'] = array(
			'title' => esc_html__( 'Modal Positioning', 'frames' ),
			'tab'   => 'content',
		);

		$this->control_groups['body'] = array(
			'title' => esc_html__( 'Modal Body Style', 'frames' ),
			'tab'   => 'content',
		);

		$this->control_groups['opening'] = array(
			'title' => esc_html__( 'Opening Options', 'frames' ),
			'tab'   => 'content',
		);

		$this->control_groups['closeIcon'] = array(
			'title' => esc_html__( 'Closing Options', 'frames' ),
			'tab'   => 'content',
		);
	}

	/**
	 * Get and Map breakpoints from Bricks
	 *
	 * @return array
	 */
	public function get_dynamic_breakpoints() {
		$breakpoints = array();
		foreach ( \Bricks\Breakpoints::$breakpoints as $breakpoint ) {
			$breakpoints[ $breakpoint['key'] ] = $breakpoint['label'];
		}
		return $breakpoints;
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

		$this->controls['hideModal'] = array(
			'label'   => __( 'Hide Modal in Builder', 'frames' ),
			'group'    => 'settings',
			'type'    => 'checkbox',
			'inline'  => true,
			'default' => false,
		);

		$this->controls['isScroll'] = array(
			'group'   => 'settings',
			'label'   => __( 'Make Modal Scrollable', 'frames' ),
			'type'    => 'checkbox',
			'inline'  => true,
			'default' => false,
		);

		$this->controls['isScrollBar'] = array(
			'group'    => 'settings',
			'label'    => __( 'Hide Scrollbar', 'frames' ),
			'type'     => 'checkbox',
			'inline'   => true,
			'default'  => false,
			'required' => array(
				'isScroll',
				'=',
				true,
			),
		);

		$this->controls['videoIsAutoplay'] = array(
			'group'   => 'settings',
			'label'   => __( 'Autoplay Video/Audio on Open', 'frames' ),
			'type'    => 'checkbox',
			'inline'  => true,
			'default' => false,
		);

		$this->controls['bodyAllowScroll'] = array(
			'group'     => 'settings',
			'label'   => __( 'Make Website Scrollable', 'frames' ),
			'type'    => 'checkbox',
			'inline'  => true,
			'default' => false,
		);

		// Positioning Controls.
		 $this->controls['positionFor'] = array(
			 'tab'        => 'content',
			 'group'      => 'positioning',
			 'label'      => __( 'Place in relation to: ', 'frames' ),
			 'type'       => 'select',
			 'options'    => array(
				 'page'    => 'Page',
				 'trigger' => 'Trigger',
			 ),
			 'inline'     => true,
			 'multiple'   => false,
			 'searchable' => false,
			 'clearable'  => false,
			 'default'    => 'page',
		 );

		 $this->controls['positionRelatedToTrigger'] = array(
			 'tab'        => 'content',
			 'group'      => 'positioning',
			 'label'      => __( 'Vertical Position ', 'frames' ),
			 'type'       => 'select',
			 'options'    => array(
				 'top'    => 'Top',
				 'bottom' => 'Bottom',
			 ),
			 'info'       => __( 'If you choose top but there is no place for it, it will automatically act like bottom and vice versa', 'frames' ),
			 'inline'     => true,
			 'multiple'   => false,
			 'searchable' => false,
			 'default'    => 'bottom',
			 'required'   => array(
				 'positionFor',
				 '=',
				 'trigger',
			 ),
		 );

		 $this->controls['placeFromTriggers'] = array(
			 'tab'        => 'content',
			 'group'      => 'positioning',
			 'label'      => __( 'Inline Position ', 'frames' ),
			 'type'       => 'select',
			 'options'    => array(
				 'left'      => 'Left side',
				 'right'     => 'Right side',
				 'center'    => 'Center',
				 'full'      => 'Full page width',
				 'container' => 'Full container width',
			 ),
			 'info'       => __( 'If you choose left but there is no place for it, it will automatically act like right and vice versa', 'frames' ),
			 'inline'     => true,
			 'multiple'   => false,
			 'searchable' => false,
			 'default'    => 'bottom',
			 'required'   => array(
				 'positionFor',
				 '=',
				 'trigger',
			 ),
		 );

		 $this->controls['yOffsetFromTrigger'] = array(
			 'group'    => 'positioning',
			 'label'    => __( 'Vertical Offset', 'frames' ),
			 'type'     => 'number',
			 'min'      => 0,
			 'max'      => 999,
			 'units'    => true,
			 'step'     => 1,
			 'inline'   => true,
			 'required' => array(
				 'positionFor',
				 '=',
				 'trigger',
			 ),
		 );

		 $this->controls['xOffsetFromTrigger'] = array(
			 'group' => 'positioning',
			 'label' => __( 'Horizontal Offset', 'frames' ),
			 'type' => 'number',
			 'min' => 0,
			 'max' => 999,
			 'units' => true,
			 'step' => 1,
			 'inline' => true,
			 'required' => array(
				 'positionFor',
				 '=',
				 'trigger',
			 ),
		 );

		 $this->controls['edgeToEdge'] = array(
			 'group' => 'positioning',
			 'label' => __( 'Align Edge to Edge', 'frames' ),
			 'type'  => 'checkbox',
			 'inline'    => true,
			 'default'   => false,
			 'required'  => array(
				 'positionFor',
				 '=',
				 'trigger',
			 ),
		 );

		 $this->controls['centerModalOnBreakpoint'] = array(
			 'group'     => 'positioning',
			 'label'     => esc_html__( 'Center Modal On Breakpoint', 'frames' ),
			 'type'      => 'checkbox',
			 'inline'    => true,
			 'default'   => false,
			 'required'  => array(
				 'positionFor',
				 '=',
				 'trigger',
			 ),
		 );

		 $this->controls['fromTriggerCenterOnBreakpoint'] = array(
			 'tab'       => 'content',
			 'group'     => 'positioning',
			 'label'     => esc_html__( 'Center on', 'frames' ),
			 'type'      => 'select',
			 'options'   => $this->get_dynamic_breakpoints(),
			 'inline'    => true,
			 'default'   => 'mobile_portrait',
			 'required'  => array(
				 'centerModalOnBreakpoint',
				 '=',
				 true,
			 ),
		 );

		 $this->controls['verticalPosition'] = array(
			 'group'    => 'positioning',
			 'label'    => __( 'Horizontal', 'frames' ),
			 'type'     => 'justify-content',
			 'css'      => array(
				 array(
					 'property' => 'justify-content',
					 'selector' => '',
				 ),
			 ),
			 // https://academy.bricksbuilder.io/article/justify-content-control/
			 // HELP: according to the docs, this should work, but it doesn't.
				// 'exclude' => array(
				// 'space-between',
				// 'space-around',
				// 'space-evenly',
				// ), // TODO: check if this is still needed.
			 'exclude'  => 'space',
			 // TODO: I found this workaround poking in the Bricks base.php file. It's not what the docs say.
			 'required' => array(
				 'positionFor',
				 '=',
				 'page',
			 ),
		 );

		 $this->controls['horizontalPosition'] = array(
			 'group'    => 'positioning',
			 'label'    => __( 'Vertical', 'frames' ),
			 // TODO: @wojtekpiskorz - let's add a comment to explain why we're using the word "vertical" here.
			 'type'     => 'align-items',
			 'css'      => array(
				 array(
					 'property' => 'align-items',
					 'selector' => '',
				 ),
			 ),
			 // 'exclude' => array(
			 // 'space-between',
			 // 'space-around',
			 // 'space-evenly',
			 // ), // TODO: check if this is still needed.
			  'exclude'  => 'stretch',
			 // TODO: I found this workaround poking in the Bricks base.php file. It's not what the docs say.
			 'required' => array(
				 'positionFor',
				 '=',
				 'page',
			 ),
		 );

		 $this->controls['modalBodyOffsetVertical'] = array(
			 'group'    => 'positioning',
			 'label'    => __( 'Vertical offset', 'frames' ),
			 'type'     => 'number',
			 'min'      => 0,
			 'max'      => 999,
			 'units'    => true,
			 'step'     => 1,
			 'css'      => array(
				 array(
					 'selector' => '',
					 'property' => '--fr-modal-body-offset-vertical',
				 // 'important' => true,
				 ),
			 ),
			 'inline'   => true,
			 'default'  => '50px',
			 'required' => array(
				 'positionFor',
				 '=',
				 'page',
			 ),
		 );

		 $this->controls['modalBodyOffsetHorizontal'] = array(
			 'group'    => 'positioning',
			 'label'    => __( 'Horizontal offset', 'frames' ),
			 'type'     => 'number',
			 'min'      => 0,
			 'max'      => 999,
			 'units'    => true,
			 'step'     => 1,
			 'css'      => array(
				 array(
					 'selector' => '',
					 'property' => '--fr-modal-body-offset-horizontal',
				 // 'important' => true,
				 ),
			 ),
			 'inline'   => true,
			 'default'  => '50px',
			 'required' => array(
				 'positionFor',
				 '=',
				 'page',
			 ),
		 );

		 // $this->controls['stylingInfo'] = array(
		 // 'group'    => 'body',
		 // 'content'  => esc_html__( 'Use the Style tab to customize the Modal\'s body container', 'frames' ),
		 // 'type'     => 'info',
		 // 'required' => false,
		 // );

		 $this->controls['scrollMaxHeight'] = array(
			 'group'       => 'body',
			 'label'       => __( 'Max Height', 'frames' ),
			 'type'        => 'number',
			 'min'         => 0,
			 'max'         => 99999,
			 'step'        => 1,
			 'units'       => false,
			 'inline'      => true,
			 'placeholder' => '90vh',
			 'css'         => array(
				 array(
					 'property'  => 'max-height',
					 'selector'  => '.fr-modal__body',
					 'important' => true,
				 ),
			 ),
			 'required'    => array(
				 'isScroll',
				 '=',
				 true,
			 ),
		 );

		 $this->controls['overlayColor'] = array(
			 'group'   => 'settings',
			 'label'   => __( 'Background Overlay Color', 'frames' ),
			 'type'    => 'color',
			 'default' => array(
				 'raw' => 'var(--neutral-trans-40)',
			 ),
			 'css'     => array(
				 array(
					 'property' => 'background-color',
					 'selector' => '.fr-modal__overlay',
				 ),
			 ),
		 );

		 $this->controls['width'] = array(
			 'group'   => 'body',
			 'label'   => __( 'Width', 'frames' ),
			 'type'    => 'number',
			 'min'     => 0,
			 'max'     => 99999,
			 'step'    => 1,
			 'units'   => false,
			 'inline'  => true,
			 'default' => 'var(--width-l)',
			 'css'     => array(
				 array(
					 'property' => 'width',
					 'selector' => '.fr-modal__body',
				 ),
			 ),
		 );

		 $this->controls['backgroundColor'] = array(
			 'group'   => 'body',
			 'label'   => __( 'Background Color', 'frames' ),
			 'type'    => 'color',
			 'default' => array(
				 'rgb' => 'var(--white)',
			 ),
			 'css'     => array(
				 array(
					 'property' => 'background-color',
					 'selector' => '.fr-modal__body',
				 ),
			 ),
		 );

		 $this->controls['padding'] = array(
			 'group'   => 'body',
			 'label'   => __( 'Padding', 'frames' ),
			 'type'    => 'spacing',
			 'css'     => array(
				 array(
					 'property' => 'padding',
					 'selector' => '.fr-modal__body',
				 ),
			 ),
			 'default' => array(
				 'top'    => 'var(--space-m)',
				 'right'  => 'var(--space-m)',
				 'bottom' => 'var(--space-m)',
				 'left'   => 'var(--space-m)',
			 ),
		 );

		 // Opening Options.

		 // type of trigger, selector by default.

		 $this->controls['triggerType'] = array(
			 'group'      => 'opening',
			 'label'      => esc_html__( 'Trigger Type: ', 'frames' ),
			 'type'       => 'select',
			 'options'    => array(
				 'selector' => 'Selector',
				 'action'    => 'Action',
			 ),
			 'inline'    => true,
			 'multiple'   => false,
			 'searchable' => false,
			 'clearable'  => false,
			 'default'    => 'selector',
		 );

		 $this->controls['triggerSelector'] = array(
			 'group'         => 'opening',
			 'label'         => __( 'Trigger Selector', 'frames' ),
			 'type'          => 'text',
			 'default'       => '.fr-trigger',
			 'inline'        => true,
			 'inlineEditing' => true,
			 'required'  => array(
				 'triggerType',
				 '=',
				 'selector',
			 )
		 );

		 $this->controls['sameClose'] = array(
			 'group'         => 'opening',
			 'label'         => __( 'Use Same Trigger to Close', 'frames' ),
			 'type'    => 'checkbox',
			 'inline'  => true,
			 'default' => false,
			 'required'   => array(
				 'triggerType',
				 '=',
				 'selector',
			 ),
		 );

		 /**
		  * Repeater type of field
		  * Action types
		  */
		 $this->controls['userActionType'] = array(
			 'group'             => 'opening',
			 'label'             => esc_html__( 'Action Triggers ', 'frames' ),
			 'type'              => 'repeater',
			 'titleProperty' => 'label',
			 'placeholder'   => esc_html__( 'Trigger', 'frames' ),
			 'required'   => array(
				 'triggerType',
				 '=',
				 'action',
			 ),

			 // Fields Array > holds all the fields for each repeater item.
			 'fields'            => array(

				 // Label for each repeater item.
				 'label'         => array(
					 'type'      => 'text',
					 'label'     => esc_html__( 'My Action Name', 'frames' ),
					 'placeholder'   => esc_html__( 'Trigger Friendly Name', 'frames' ),
				 ),

				 // Dropdown with the Actions for Trigger.
				 'type'  => array(
					 'label'     => esc_html__( 'Trigger type', 'frames' ),
					 'type'      => 'select',
					 'options'   => array(
						 'mouseLeaveViewport'    => esc_html__( 'Mouse Leave Viewport', 'frames' ),
						 'onPageLoad'            => esc_html__( 'On Page Load', 'frames' ),
						 'onHover'               => esc_html__( 'On Hover', 'frames' ),
						 'onInactivity'  => esc_html__( 'Inactivity', 'frames' ),
					 ),
					 'default'   => 'mouseLeaveViewport',
				 ),

				 // Extra fields for certain Trigger Types, Conditions apply.

				 // On Page Load.
				 'afterPageLoad'     => array(
					 'label'                 => esc_html__( 'After Page Load (s)', 'frames' ),
					 'type'                  => 'number',
					 'min'     => 0,
					 'max'     => 999,
					 'units'   => false,
					 'step'    => 1,
					 'inline'  => true,
					 'default' => '5',
					 'required'      => array(
						 'type',
						 '=',
						 'onPageLoad'
					 )
				 ),

				 // On Hover.
				 'hoverSelector'     => array(
					 'label'                 => esc_html__( 'Hover Selector', 'frames' ),
					 'type'          => 'text',
					 'default'       => '',
					 'inline' => false,
					 'required'      => array(
						 'type',
						 '=',
						 'onHover'
					 )
				 ),

				 // On Inactivity.
				 'mouseInactivity'   => array(
					 'label' => esc_html__( 'Mouse Inactivity', 'frames' ),
					 'type'  => 'checkbox',
					 'inline'    => true,
					 'default'   => false,
					 'required'  => array(
						 'type',
						 '=',
						 'onInactivity'
					 ),
				 ),

				 // On Inactivity.
				 'keyboardInactivity'    => array(
					 'label' => esc_html__( 'Keyboard Inactivity', 'frames' ),
					 'type'  => 'checkbox',
					 'inline'    => true,
					 'default'   => false,
					 'required' => array(
						 'type',
						 '=',
						 'onInactivity'
					 ),
				 ),

				 // On Inactivity.
				 'scrollInactivity'  => array(
					 'label' => esc_html__( 'Scroll Inactivity', 'frames' ),
					 'type'  => 'checkbox',
					 'inline'    => true,
					 'default'   => false,
					 'required'  => array(
						 'type',
						 '=',
						 'onInactivity'
					 ),
				 ),

				 // On Inactivity.
				 'inactivityTime'    => array(
					 'label' => esc_html__( 'Inactivity Time (Seconds)', 'frames' ),
					 'type'  => 'number',
					 'min'   => 0,
					 'max'   => 999,
					 'units' => false,
					 'steps' => 1,
					 'inline'    => true,
					 'default'   => '60',
					 'required'  => array(
						 'type',
						 '=',
						 'onInactivity'
					 ),
				 ),

				 // Next field.
			 ),
		 );

		 if ( Flag::is_on( 'FRAMES_ENABLE_MODAL_EXIT_INTENT' ) ) {
			 $this->controls['userActionType'] = array(
				 'group'             => 'opening',
				 'label'             => esc_html__( 'Action Triggers ', 'frames' ),
				 'type'              => 'repeater',
				 'titleProperty' => 'label',
				 'placeholder'   => esc_html__( 'Trigger', 'frames' ),
				 'required'   => array(
					 'triggerType',
					 '=',
					 'action',
				 ),

				 // Fields Array > holds all the fields for each repeater item.
				 'fields'            => array(

					 // Label for each repeater item.
					 'label'         => array(
						 'type'      => 'text',
						 'label'     => esc_html__( 'My Action Name', 'frames' ),
						 'placeholder'   => esc_html__( 'Trigger Friendly Name', 'frames' ),
					 ),

					 // Dropdown with the Actions for Trigger.
					 'type'  => array(
						 'label'     => esc_html__( 'Trigger type', 'frames' ),
						 'type'      => 'select',
						 'options'   => array(
							 'mouseLeaveViewport'    => esc_html__( 'Mouse Leave Viewport', 'frames' ),
							 'onPageLoad'            => esc_html__( 'On Page Load', 'frames' ),
							 'onInactivity'  => esc_html__( 'Inactivity', 'frames' ),
							 'onScrollToEl'  => esc_html__( 'On Scroll to Element', 'frames' ),
						 ),
						 'default'   => 'mouseLeaveViewport',
					 ),

					 // Extra fields for certain Trigger Types, Conditions apply.

					 // On Page Load.
					 'afterPageLoad'     => array(
						 'label'                 => esc_html__( 'After Page Load (s)', 'frames' ),
						 'type'                  => 'number',
						 'min'     => 0,
						 'max'     => 999,
						 'units'   => false,
						 'step'    => 1,
						 'inline'  => true,
						 'default' => '5',
						 'required'      => array(
							 'type',
							 '=',
							 'onPageLoad'
						 )
					 ),

					 // On Inactivity.
					 'mouseInactivity'   => array(
						 'label' => esc_html__( 'Mouse Inactivity', 'frames' ),
						 'type'  => 'checkbox',
						 'inline'    => true,
						 'default'   => false,
						 'required'  => array(
							 'type',
							 '=',
							 'onInactivity'
						 ),
					 ),

					 // On Inactivity.
					 'keyboardInactivity'    => array(
						 'label' => esc_html__( 'Keyboard Inactivity', 'frames' ),
						 'type'  => 'checkbox',
						 'inline'    => true,
						 'default'   => false,
						 'required' => array(
							 'type',
							 '=',
							 'onInactivity'
						 ),
					 ),

					 // On Inactivity.
					 'scrollInactivity'  => array(
						 'label' => esc_html__( 'Scroll Inactivity', 'frames' ),
						 'type'  => 'checkbox',
						 'inline'    => true,
						 'default'   => false,
						 'required'  => array(
							 'type',
							 '=',
							 'onInactivity'
						 ),
					 ),

					 // On Inactivity.
					 'inactivityTime'    => array(
						 'label' => esc_html__( 'Inactivity Time (Seconds)', 'frames' ),
						 'type'  => 'number',
						 'min'   => 0,
						 'max'   => 999,
						 'units' => false,
						 'steps' => 1,
						 'inline'    => true,
						 'default'   => '60',
						 'required'  => array(
							 'type',
							 '=',
							 'onInactivity'
						 ),
					 ),

					 // TODO @Hakira-Shymuy before release put handleOnScrollToEl under Flag Feature, and exclude from release.
					 // On Scroll To Element Selector .
					 'scrollSelector'    => array(
						 'label' => esc_html__( 'Selector', 'frames' ),
						 'type'  => 'text',
						 'default'   => '',
						 'inlineEditing' => true,
						 'required'  => array(
							 'type',
							 '=',
							 'onScrollToEl'
						 ),
					 ),

					 // On Scroll To Element Threshold .
					 'selectorThreshold' => array(
						 'label' => esc_html__( 'Selector Threshold (%)', 'frames' ),
						 'type'  => 'number',
						 'min'   => 0,
						 'max'   => 100,
						 'units' => false,
						 'step'  => 1,
						 'default'   => '50',
						 'inlineEditing' => true,
						 'required'  => array(
							 'type',
							 '=',
							 'onScrollToEl'
						 ),
					 ),
				 ),
			 );
		 }

		 // Repeat modal visibility Options.
		 $this->controls['repeatActions'] = array(
			 'group'      => 'opening',
			 'label'      => esc_html__( 'Repeat Action(s): ', 'frames' ),
			 'type'       => 'select',
			 'options'    => array(
				 'perPageVisit'    => 'Per page visit',
				 'neverShowAgain' => 'Never show again',
				 'alwaysShow'     => 'Always show',
			 ),
			 'inline'     => false,
			 'multiple'   => false,
			 'searchable' => false,
			 'clearable'  => false,
			 'default'    => 'perPageVisit',
			 'required'   => array(
				 'triggerType',
				 '=',
				 'action',
			 ),
		 );

		 $this->controls['fadeTime'] = array(
			 'group'   => 'opening',
			 'label'   => __( 'Fade-In Duration', 'frames' ),
			 'type'    => 'number',
			 'min'     => 0,
			 'max'     => 999,
			 'units'   => false,
			 'step'    => 1,
			 'inline'  => true,
			 'default' => '300',
		 );

		 // Closing Options.

		 $this->controls['isCloseButton'] = array(
			 'group'      => 'closeIcon',
			 'label'      => esc_html__( 'Close Option: ', 'frames' ),
			 'type'       => 'select',
			 'options'    => array(
				 'selector' => 'Selector',
				 'icon'    => 'Icon',
			 ),
			 'inline'    => true,
			 'multiple'   => false,
			 'searchable' => false,
			 'clearable'  => false,
			 'default'    => 'icon',
		 );

		 $this->controls['closeSelector'] = array(
			 'group'         => 'closeIcon',
			 'label'         => __( 'Close Selector', 'frames' ),
			 'type'          => 'text',
			 'default'       => '',
			 'inline'        => true,
			 'inlineEditing' => true,
			 'required'      => array(
				 'isCloseButton',
				 '=',
				 'selector',
			 ),
		 );

			$this->controls['iconPlacement'] = array(
				'tab'        => 'content',
				'group'      => 'closeIcon',
				'label'      => __( 'Icon is: ', 'frames' ),
				'type'       => 'select',
				'options'    => array(
					'outside' => 'Outside',
					'inside'  => 'Inside',
				),
				'inline'     => true,
				'multiple'   => false,
				'searchable' => false,
				'clearable'  => false,
				'default'    => 'outside',
				'required'   => array(
					'isCloseButton',
					'=',
					'icon',
				),
			);

			$this->controls['icon'] = array(
				'group'    => 'closeIcon',
				'label'    => __( 'Icon', 'frames' ),
				'type'     => 'icon',
				'default'  => array(
					'library' => 'themify',
					'icon'    => 'ti-close',
				),
				'required' => array(
					'isCloseButton',
					'=',
					'icon',
				),
			);

			$this->controls['iconColor'] = array(
				'group'    => 'closeIcon',
				'label'    => __( 'Color', 'frames' ),
				'type'     => 'color',
				'default'  => array(
					'rgb' => 'var(--neutral-ultra-dark)',
				),
				'css'      => array(
					array(
						'property' => 'color',
						'selector' => '.fr-modal__close-icon',
					),
				),
				'required' => array(
					'isCloseButton',
					'=',
					'icon',
				),
			);

			$this->controls['iconBackgroundColor'] = array(
				'group'    => 'closeIcon',
				'label'    => __( 'Background Color', 'frames' ),
				'type'     => 'color',
				'default'  => array(
					'rgb' => 'var(--white)',
				),
				'css'      => array(
					array(
						'property' => 'background-color',
						'selector' => '.fr-modal__close-icon-wrapper',
					),
				),
				'required' => array(
					'isCloseButton',
					'=',
					'icon',
				),
			);

			$this->controls['iconSize'] = array(
				'group'    => 'closeIcon',
				'label'    => __( 'Icon Size', 'frames' ),
				'type'     => 'number',
				'min'      => 0,
				'max'      => 99999,
				'step'     => 1,
				'units'    => false,
				'inline'   => true,
				'default'  => 'var(--text-m)',
				'css'      => array(
					array(
						'property' => 'font-size',
						'selector' => '.fr-modal__close-icon',
					),
				),
				'required' => array(
					'isCloseButton',
					'=',
					'icon',
				),
			);

			$this->controls['iconBackgroundSize'] = array(
				'group'    => 'closeIcon',
				'label'    => __( 'Icon Background Size', 'frames' ),
				'type'     => 'number',
				'min'      => 0,
				'max'      => 99999,
				'step'     => 1,
				'units'    => false,
				'inline'   => true,
				'default'  => 'var(--space-l)',
				'css'      => array(
					array(
						'property' => 'width',
						'selector' => '.fr-modal__close-icon',
					),
				),
				'required' => array(
					'isCloseButton',
					'=',
					'icon',
				),
			);

			$this->controls['iconBorder'] = array(
				'group'         => 'closeIcon',
				'label'         => __( 'Border', 'frames' ),
				'type'          => 'border',
				'default'       => '',
				'inlineEditing' => true,
				'css'           => array(
					array(
						'property' => 'border',
						'selector' => '.fr-modal__close-icon-wrapper',
					),
				),
				'required'      => array(
					'isCloseButton',
					'=',
					'icon',
				),
			);

			$this->controls['iconPosTop'] = array(
				'group'    => 'closeIcon',
				'label'    => __( 'Top', 'frames' ),
				'type'     => 'number',
				'min'      => 0,
				'max'      => 99999,
				'step'     => 1,
				'units'    => false,
				'inline'   => true,
				'default'  => 'var(--space-m)',
				'css'      => array(
					array(
						'property' => 'top',
						'selector' => '.fr-modal__close-icon-wrapper',
					),
				),
				'required' => array(
					'isCloseButton',
					'=',
					'icon',
				),
			);

			$this->controls['iconPosRight'] = array(
				'group'    => 'closeIcon',
				'label'    => __( 'Right', 'frames' ),
				'type'     => 'number',
				'min'      => 0,
				'max'      => 99999,
				'step'     => 1,
				'units'    => false,
				'inline'   => true,
				'default'  => 'var(--space-m)',
				'css'      => array(
					array(
						'property' => 'right',
						'selector' => '.fr-modal__close-icon-wrapper',
					),
				),
				'required' => array(
					'isCloseButton',
					'=',
					'icon',
				),
			);

			$this->controls['iconPosBottom'] = array(
				'group'    => 'closeIcon',
				'label'    => __( 'Bottom', 'frames' ),
				'type'     => 'number',
				'min'      => 0,
				'max'      => 99999,
				'step'     => 1,
				'units'    => false,
				'inline'   => true,
				'default'  => 'auto',
				'css'      => array(
					array(
						'property' => 'bottom',
						'selector' => '.fr-modal__close-icon-wrapper',
					),
				),
				'required' => array(
					'isCloseButton',
					'=',
					'icon',
				),
			);

			$this->controls['iconPosLeft'] = array(
				'group'    => 'closeIcon',
				'label'    => __( 'Left', 'frames' ),
				'type'     => 'number',
				'min'      => 0,
				'max'      => 99999,
				'step'     => 1,
				'units'    => false,
				'inline'   => true,
				'default'  => 'auto',
				'css'      => array(
					array(
						'property' => 'left',
						'selector' => '.fr-modal__close-icon-wrapper',
					),
				),
				'required' => array(
					'isCloseButton',
					'=',
					'icon',
				),
			);

			$this->controls['closeIconAriaLabel'] = array(
				'group'    => 'closeIcon',
				'label'    => __( 'Aria Label', 'frames' ),
				'type'     => 'text',
				'inline'   => true,
				'default'  => 'Close Modal',
				'required' => array(
					'isCloseButton',
					'=',
					'icon',
				),
			);

			$this->controls['fadeOutTime'] = array(
				'group'   => 'closeIcon',
				'label'   => __( 'Fade-Out Duration', 'frames' ),
				'type'    => 'number',
				'min'     => 0,
				'max'     => 999,
				'units'   => false,
				'step'    => 1,
				'inline'  => true,
				'default' => '300',
			);

			// click anywhere to close input
			// TODO: @andrea → inverted behavior.
			$this->controls['disableCloseOutsideClick'] = array(
				'group'     => 'closeIcon',
				'label'   => __( 'Disable Click Outside to Close', 'frames' ),
				'type'    => 'checkbox',
				'inline'  => true,
				'default' => false,
			);
	}

	/**
	 * Convert boolean to string
	 *
	 * @param bool $bool Boolean value.
	 * @return string
	 */
	public function toString( $bool ) {
		return $bool ? 'true' : 'false';
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

		$filename = 'modal';
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
		// TODO @Hakira-Shymuy remove flag after feature finished and tested.
		if ( Flag::is_on( 'FRAMES_ENABLE_MODAL_EXIT_INTENT' ) ) {
			wp_localize_script(
				"frames-{$filename}",
				'frames_modal_obj',
				array(
					'flag_enable_modal_exit_intent' => Flag::is_on( 'FRAMES_ENABLE_MODAL_EXIT_INTENT' ),
				)
			);
		}

		// TODO flag for Modal Slider Compatibility.
		if ( Flag::is_on( 'FRAMES_MODAL_SLIDER_COMPATIBILITY' ) ) {
			wp_localize_script(
				"frames-{$filename}",
				'frames_modal_slider_obj',
				array(
					'flag_modal_slider_compatibility' => Flag::is_on( 'FRAMES_MODAL_SLIDER_COMPATIBILITY' ),
				)
			);
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
		// $this->get_methods();

		// starts an empty array.
		$triggerConfigurations = array();

		if ( Flag::is_on( 'FRAMES_ENABLE_MODAL_EXIT_INTENT' ) ) {
			if ( isset( $settings['triggerType'] ) && 'action' === $settings['triggerType'] && ! empty( $settings['userActionType'] ) ) {

				// if the conditions are met we proceed to loop the actions set on bricks.
				foreach ( $settings['userActionType'] as $action ) {

					$action_type = isset( $action['type'] ) ? $action['type'] : '';
					// we map each action on the triggerConfig.
					$triggerConfig = array(
						'type'  => $action_type,
					);

					$options = array();
					switch ( $action_type ) {
						case 'mouseLeaveViewport':
							// other settings like i have on placeholder if needed.
							break;
						case 'onPageLoad':
							if ( ! empty( $action['afterPageLoad'] ) ) {
								$options['afterPageLoad'] = $action['afterPageLoad'];
							}
							break;
						case 'onInactivity':
							if ( isset( $action['mouseInactivity'] ) ) {
								$options['mouseInactivity'] = $action['mouseInactivity'];
							}
							if ( isset( $action['keyboardInactivity'] ) ) {
								$options['keyboardInactivity'] = $action['keyboardInactivity'];
							}
							if ( isset( $action['scrollInactivity'] ) ) {
								$options['scrollInactivity'] = $action['scrollInactivity'];
							}
							if ( isset( $action['inactivityTime'] ) ) {
								$options['inactivityTime'] = $action['inactivityTime'];
							}
							break;
						case 'onScrollToEl':
							if ( Flag::is_on( 'FRAMES_ENABLE_MODAL_EXIT_INTENT' ) ) {
								if ( ! empty( $action['scrollSelector'] ) ) {
									$options['scrollSelector'] = $action['scrollSelector'];
								}
								if ( ! empty( $action['selectorThreshold'] ) ) {
									$options['selectorThreshold'] = $action['selectorThreshold'];
								}
							}
							break;
						case 'onHover':
							if ( ! empty( $action['hoverSelector'] ) ) {
								$options['hoverSelector'] = $action['hoverSelector'];
							}
							break;
					}

					if ( ! empty( $options ) ) {
						$triggerConfig['options'] = $options;
					}

					$triggerConfigurations[] = $triggerConfig;
				}
			}
		}
		if ( isset( $settings['triggerType'] ) && 'action' === $settings['triggerType'] && ! empty( $settings['userActionType'] ) ) {

			// if the conditions are met we proceed to loop the actions set on bricks.
			foreach ( $settings['userActionType'] as $action ) {

				$action_type = isset( $action['type'] ) ? $action['type'] : '';
				// we map each action on the triggerConfig.
				$triggerConfig = array(
					'type'  => $action_type,
				);

				$options = array();
				switch ( $action_type ) {
					case 'mouseLeaveViewport':
						// other settings like i have on placeholder if needed.
						break;
					case 'onPageLoad':
						if ( ! empty( $action['afterPageLoad'] ) ) {
							$options['afterPageLoad'] = $action['afterPageLoad'];
						}
						break;
					case 'onInactivity':
						if ( isset( $action['mouseInactivity'] ) ) {
							$options['mouseInactivity'] = $action['mouseInactivity'];
						}
						if ( isset( $action['keyboardInactivity'] ) ) {
							$options['keyboardInactivity'] = $action['keyboardInactivity'];
						}
						if ( isset( $action['scrollInactivity'] ) ) {
							$options['scrollInactivity'] = $action['scrollInactivity'];
						}
						if ( isset( $action['inactivityTime'] ) ) {
							$options['inactivityTime'] = $action['inactivityTime'];
						}
						break;
					case 'onHover':
						if ( ! empty( $action['hoverSelector'] ) ) {
							$options['hoverSelector'] = $action['hoverSelector'];
						}
						break;
				}

				if ( ! empty( $options ) ) {
					$triggerConfig['options'] = $options;
				}

				$triggerConfigurations[] = $triggerConfig;
			}
		} else {
			$triggerSelector = ! empty( $settings['triggerSelector'] ) ? $settings['triggerSelector'] : '.fr-trigger';
		}

		$closeSelector   = ! empty( $settings['closeSelector'] ) ? $settings['closeSelector'] : '.fr-modal__close';
		$fadeTime        = ! empty( $settings['fadeTime'] ) ? $settings['fadeTime'] : 300;
		$fadeOutTime     = ! empty( $settings['fadeOutTime'] ) ? $settings['fadeOutTime'] : $fadeTime;

		$videoIsAutoplay        = isset( $settings['videoIsAutoplay'] );
		$isScroll               = isset( $settings['isScroll'] );
		$isScrollBar            = isset( $settings['isScrollBar'] );
		$hideModal              = isset( $settings['hideModal'] );
		$disableCloseOutsideClick = isset( $settings['disableCloseOutsideClick'] );
		$bodyAllowScroll        = isset( $settings['bodyAllowScroll'] );
		$isCloseButton          = isset( $settings['isCloseButton'] );
		$iconPlacement          = ! empty( $settings['iconPlacement'] ) ? $settings['iconPlacement'] : 'outside';
		$icon                   = ! empty( $this->settings['icon'] ) ? self::render_icon( $this->settings['icon'] ) : false;
		$sameClose          = isset( $settings['sameClose'] );

		// Positioning Controls.
		$positionFor              = ! empty( $settings['positionFor'] ) ? $settings['positionFor'] : 'page';
		$positionRelatedToTrigger = ! empty( $settings['positionRelatedToTrigger'] ) ? $settings['positionRelatedToTrigger'] : 'bottom';
		$placeFromTriggers        = ! empty( $settings['placeFromTriggers'] ) ? $settings['placeFromTriggers'] : 'center';
		$yOffsetFromTrigger        = ! empty( $settings['yOffsetFromTrigger'] ) ? $settings['yOffsetFromTrigger'] : '0';
		$xOffsetFromTrigger        = ! empty( $settings['xOffsetFromTrigger'] ) ? $settings['xOffsetFromTrigger'] : '0';
		$edgeToEdge                 = isset( $settings['edgeToEdge'] );
		$closeIconAriaLabel        = ! empty( $settings['closeIconAriaLabel'] ) ? $settings['closeIconAriaLabel'] : 'Close Modal';

		$centerOnBreakpoint     = ! empty( $settings['centerModalOnBreakpoint'] ) && $settings['centerModalOnBreakpoint'];
		if ( $centerOnBreakpoint ) {
			$isFromTriggerCenterOnBreakpoint = isset( $settings['fromTriggerCenterOnBreakpoint'] ) ? $settings['fromTriggerCenterOnBreakpoint'] : false;

			if ( $isFromTriggerCenterOnBreakpoint ) {
				$centerOptions = array();

				foreach ( \Bricks\Breakpoints::$breakpoints as $breakpoint ) {
					if ( $breakpoint['key'] == $isFromTriggerCenterOnBreakpoint ) {
						$centerOptions['centerOnBreakpoint'] = $breakpoint['width'];
						break;
					}
				}
			}
		}

		$queryID = null;

		// Query.
		if ( method_exists( '\Bricks\Query', 'is_any_looping' ) ) {
			$query = \Bricks\Query::is_any_looping();

			if ( $query ) {
				$this->set_attribute( '_root', 'data-fr-modal-inside-query', 'true' );
			} else {
				$this->set_attribute( '_root', 'data-fr-modal-inside-query', 'false' );
			}

			$count = 0;

			if ( class_exists( '\Bricks\Query' ) ) {
				$queryObj = \Bricks\Query::get_query_object();
				if ( $queryObj ) {
					$queryID = $queryObj->element_id;
					$queryID = 'brxe-' . $queryID;

					$this->set_attribute( '_root', 'data-fr-modal-query-id', $queryID );
				}

				if ( $queryObj ) {
					$count = ( intval( $queryObj::get_loop_index() ) );
					$count++;
					// add 1 to count
					// change count to string.
				}
			}
		}//end if

		if ( $count ) {
			// $dynamicID = $this->id . '-' . $count;
			$dynamicID = 'fr-' . $this->id . '-' . $count;

			/*
			 * 2023-03-13 - MG
			 * Bricks has already declared $this->attributes['_root']['id'] as a string
			 * and the call to $this->set_attribute() is trying to set it as an array.
			 * This causes a Fatal error [] operator not supported for strings.
			 */
			// $this->set_attribute( '_root', 'id', $dynamicID ); // TODO: find a way to set the ID attribute that respects encapsulation.
			$this->attributes['_root']['id'] = $dynamicID;
		}

		$this->set_attribute( '_root', 'class', 'fr-modal' );
		$this->set_attribute( '_root', 'class', 'fr-modal--hide' );
		$this->set_attribute( '_root', 'aria-hidden', 'true' );

		// set the attributes for the types of triggers.
		if ( isset( $settings['triggerType'] ) && ! empty( $settings['userActionType'] ) ) {
			$triggerConfigJson = wp_json_encode( $triggerConfigurations );
			$triggerConfigJson = preg_replace( '/\s+(?![^()]*\))/', '', $triggerConfigJson );

			$this->set_attribute( '_root', 'data-fr-modal-trigger-config', esc_attr( $triggerConfigJson ) );

		} elseif ( ! empty( $settings['triggerSelector'] ) ) {
			$this->set_attribute( '_root', 'data-fr-modal-trigger', $triggerSelector );
		}

		// $this->set_attribute( '_root', 'data-fr-modal-trigger', $triggerSelector ); // disable previous feature from 1.4.3.

		if ( isset( $settings['triggerType'] ) && 'action' === $settings['triggerType']  /*&& ! isset( $settings['triggerSelector'] )*/ ) {
			$repeatActionsConfig = array(
				'repeatActions' => ! empty( $settings['repeatActions'] ) ? $settings['repeatActions'] : 'perPageVisit',
			);
			$repeatActionsJson = wp_json_encode( $repeatActionsConfig );
			$repeatActionsJson = preg_replace( '/\s+(?![^()]*\))/', '', $repeatActionsJson );
			$this->set_attribute( '_root', 'data-fr-modal-repeat-actions', esc_attr( $repeatActionsJson ) );
		}

		$this->set_attribute( '_root', 'data-fr-modal-close', $closeSelector );
		$this->set_attribute( '_root', 'data-fr-modal-fade-time', $fadeTime );
		$this->set_attribute( '_root', 'data-fr-modal-fade-out-time', $fadeOutTime );
		$this->set_attribute( '_root', 'data-fr-modal-video-autoplay', $this->toString( $videoIsAutoplay ) );
		$this->set_attribute( '_root', 'data-fr-modal-scroll', $this->toString( $isScroll ) );
		$this->set_attribute( '_root', 'data-fr-modal-scrollbar', $this->toString( $isScrollBar ) );
		$this->set_attribute( '_root', 'data-fr-modal-disable-close-outside', $this->toString( $disableCloseOutsideClick ) );
		$this->set_attribute( '_root', 'data-fr-modal-allow-scroll', $this->toString( $bodyAllowScroll ) );
		$this->set_attribute( '_root', 'data-fr-modal-same-close-trigger', $this->toString( $sameClose ) );

		if ( isset( $positionFor ) && 'trigger' === $positionFor ) {
			$this->set_attribute( '_root', 'data-fr-modal-position-for', $positionFor );
			$this->set_attribute( '_root', 'data-fr-modal-position-related-to-trigger', $positionRelatedToTrigger );
			$this->set_attribute( '_root', 'data-fr-modal-place-from-triggers', $placeFromTriggers );
			$this->set_attribute( '_root', 'data-fr-modal-trigger-yOffset', $yOffsetFromTrigger );
			$this->set_attribute( '_root', 'data-fr-modal-trigger-xOffset', $xOffsetFromTrigger );

			if ( $edgeToEdge ) {
				$this->set_attribute( '_root', 'data-fr-modal-trigger-edge', 'true' );
			}

			// Json encode the Array and set the attribute.
			if ( ! empty( $centerOptions ) ) {
				$centerOptionsJson = wp_json_encode( $centerOptions );
				$this->set_attribute( '_root', 'data-fr-modal-center-option', $centerOptionsJson );
			}
		}

		if ( $hideModal ) {
			$this->set_attribute( '_root', 'fr-builder', 'hide' );
		}

		// Icon settings.
		if ( ! $icon ) {
			return $this->render_element_placeholder(
				array(
					'title' => esc_html__( 'No icon selected.', 'bricks' ),
				)
			);
		}

		// OUTPUT.
		$output = '<div ' . $this->render_attributes( '_root' ) . '>';

		// $output  = "<div {$this->render_attributes( '_root' )}>";
		$output .= '<div class="fr-modal__overlay"></div> <!-- .fr-modal__overlay -->';

		// Icon outside.
		if ( $isCloseButton ) {
			if ( 'outside' === $iconPlacement ) {
				$output .= '<div class="fr-modal__close-icon-wrapper">';
				$output .= '<button aria-label="' . $closeIconAriaLabel . '" class="fr-modal__close-icon" tabindex="0">';
				if ( $icon ) {
					$output .= $icon;
				}

				$output .= '</button> <!-- .fr-modal__close-icon -->';
				$output .= '</div> <!-- .fr-modal__close-icon-wrapper -->';
			}
		}

		$output .= '<div class="fr-modal__body modal-wrapper">';
		$output .= \Bricks\Frontend::render_children( $this ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		// Icon Inside.
		if ( $isCloseButton ) {
			if ( 'inside' === $iconPlacement ) {
				$output .= '<div class="fr-modal__close-icon-wrapper">';
				$output .= '<button aria-label="' . $closeIconAriaLabel . '" class="fr-modal__close-icon" tabindex="0">';
				if ( $icon ) {
					$output .= $icon;
				}

				$output .= '</button> <!-- .fr-modal__close-icon -->';
				$output .= '</div> <!-- .fr-modal__close-icon-wrapper -->';
			}
		}

		$output .= '</div> <!-- .fr-modal__body -->';
		$output .= '</div> <!-- .fr-modal -->';

		echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		// log queryID.
	}
}
