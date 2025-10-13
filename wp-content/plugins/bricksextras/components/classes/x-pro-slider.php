<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class X_Pro_Slider extends \Bricks\Element {
	public $category = 'extras';
	public $name     = 'xproslider';
	public $icon     = 'ti-layout-slider-alt';
	public $scripts  = [ 'xProSlider' ];
	public $nestable = true;

	public function get_label() {
		return esc_html__( 'Pro Slider', 'bricks' );
	}

	public function get_keywords() {
		return [ 'slider', 'testimonials', 'carousel', 'pro' ];
	}

	public function enqueue_scripts() {

		wp_enqueue_script( 'bricks-splide' );
		wp_enqueue_style( 'bricks-splide' );

		wp_enqueue_script( 'x-slider', BRICKSEXTRAS_URL . 'components/assets/js/' . \BricksExtras\Helpers::maybeMinifyScripts('proslider') . '.js', '', '1.1.9', true );

		wp_localize_script(
			'x-slider',
			'xSlider',
			[
				'Instances' => [],
			]
		);

		if (! \BricksExtras\Helpers::elementCSSAdded($this->name) ) {
			wp_enqueue_style( 'x-slider', BRICKSEXTRAS_URL . 'components/assets/css/proslider.css', [], '' );
		}

		
	}

	public function set_control_groups() {

        $this->control_groups['addslides'] = [
			'title' => esc_html__( 'Add slides', 'bricks' ),
			'required' => ['galleryMode', '!=', true],
		];

        $this->control_groups['slide'] = [
			'title' => esc_html__( 'Slides / Layout', 'bricks' ),
		];

		$this->control_groups['options'] = [
			'title' => esc_html__( 'Interaction / Behaviour', 'bricks' ),
		];

		$this->control_groups['dynamicStyles'] = [
			'title' => esc_html__( 'Slide styles', 'bricks' ),
		];

		$this->control_groups['arrows'] = [
			'title' => esc_html__( 'Nav arrows', 'bricks' ),
		];

		$this->control_groups['pagination'] = [
			'title' => esc_html__( 'Pagination dots', 'bricks' ),
		];

		$this->control_groups['autoScroll'] = [
			'title' => esc_html__( 'Auto play / scroll', 'bricks' ),
		];


        $this->control_groups['sync'] = [
			'title' => esc_html__( 'Sync sliders', 'bricks' ),
		];

        $this->control_groups['animations'] = [
			'title' => esc_html__( 'Inner animations', 'bricks' ),
			'required' => ['galleryMode', '!=', true],
		];

		$this->control_groups['accessibility'] = [
			'title' => esc_html__( 'Accessibility', 'bricks' ),
		];

		if ( class_exists( '\Bricks\Popups' ) ) {
			$this->control_groups['conditionalSlider'] = [
				'title' => esc_html__( 'Conditional slider', 'bricks' ),
			];
		}

		$this->control_groups['gallery'] = [
			'title' => esc_html__( 'Gallery', 'bricks' ),
		];
	} 

	public function set_controls() {

		$this->controls['_height']['css'][0]['selector'] = '.splide__slide';
		

		$this->controls['layoutInfo'] = [
			'tab' => 'content',
			'description' => esc_html__( 'Configure the Slider layout / behaviour using the element ID, not a class', 'bricks' ),
			'type' => 'seperator',
		  ];

		$this->controls['_children'] = [
			'type'          => 'repeater',
			'placeholder'   => esc_html__( 'Slide', 'bricks' ),
            'group'         => 'addslides',
			'titleProperty' => 'label',
			'items'         => 'children', // NOTE: Undocumented
		];

		$this->controls['slidesSeparator'] = [
			'group'    => 'slide',
			'label'    => esc_html__( 'Slide widths', 'bricks' ),
			'type'     => 'separator',
			'required' => ['type', '!=', 'fade'],
		];

        $this->controls['perPage'] = [
			'group'       => 'slide',
			'label'       => esc_html__( 'No. of slides to show', 'bricks' ),
			'type'        => 'number',
			'placeholder' => 1,
			'breakpoints' => true,
			'required' => [
				['type', '!=', 'fade'],
				['autoWidth', '!=', true],
			],
            /*'css'   => [
				[
					'property' => '--xitemstoshow',
					'selector' => '& > .x-slider_builder > .splide__track_builder',
				],
			],*/
		];

		

        $this->controls['fixedWidth'] = [
			'group'       => 'slide',
			'label'       => esc_html__( 'or.. fixed slide width', 'bricks' ),
			'tooltip'  => [
				'content'  => 'Will override slides to show setting',
				'position' => 'top-left',
			],
			'type'        => 'number',
			'units'       => true,
			'breakpoints' => true,
			'required' => [
				['type', '!=', 'fade'],
				['autoWidth', '!=', true]
			],
            /*'css'   => [
				[
					'property' => 'width',
					'selector' => '& > .x-slider_builder > .splide__track_builder > .splide__list > .x-slider_slide',
				],
			],*/
			'rerender' => true,
		];

		

		$this->controls['gap'] = [
			'group'       => 'slide',
			'label'       => esc_html__( 'Gap between slides', 'bricks' ),
			'type'        => 'number',
			'units'       => true,
			'placeholder' => '0px',
			'breakpoints' => true,
			'required' => ['type', '!=', 'fade'],
           /* 'css'   => [
				[
					'property' => '--xspacebetween',
					'selector' => '& > .x-slider_builder',
				],
			],*/
			'rerender' => true,
		];

		

		$this->controls['autoWidth'] = [
			'group'    => 'slide',
			'label'    => esc_html__( 'Auto slide width', 'bricks' ),
			'type'     => 'checkbox',
			'inline'   => true,
			'tooltip'  => [
				'content'  => 'Must be setting a slide height for auto width to apply.',
				'position' => 'top-left',
			],
		];

		$this->controls['slidesHeightSeparator'] = [
			'group'    => 'slide',
			'label'    => esc_html__( 'Slide Heights', 'bricks' ),
			'type'     => 'separator',
		];

		$this->controls['fixedHeight'] = [
			'group'       => 'slide',
			'label'       => esc_html__( 'Fixed slide height', 'bricks' ),
			'type'        => 'number',
			'tooltip'  => [
				'content'  => 'Height of slides, needed if using auto width',
				'position' => 'top-left',
			],
			'units'       => true,
			'breakpoints' => true,
           /* 'css'   => [
				[
					'property' => 'height',
					'selector' => '& > .x-slider_builder > .splide__track_builder > .splide__list > .x-slider_slide',
				],
			], */
			'required' => ['autoHeight', '!=', true],
			'rerender' => true,
		];

		

        $this->controls['autoHeight'] = [
			'group'    => 'slide',
			'label'    => esc_html__( 'Auto slide height', 'bricks' ),
			'type'     => 'checkbox',
			'inline'   => true,
			'tooltip'  => [
				'content'  => 'For vertical sliders, will ensure height is auto',
				'position' => 'top-left',
			],
			'rerender' => true,
			/*'css'   => [
				[
					'property' => '--xsliderautoheight',
					'selector' => '& > .x-slider_builder > .splide__track_builder > .splide__list > .x-slider_slide',
					'value' => 'auto'
				],
			],*/
		];

		

		$this->controls['sliderHeightSeparator'] = [
			'group'    => 'slide',
			'type'     => 'separator',
		];

		$this->controls['height'] = [
			'group'       => 'slide',
			'label'       => esc_html__( 'Slider height', 'bricks' ),
			'type'        => 'number',
			'tooltip'  => [
				'content'  => 'Overall height of slider, needed if vertical slider',
				'position' => 'top-left',
			],
			'units'       => true,
			'breakpoints' => true,
            'placeholder' => 'auto',
			'rerender' => true,
            /*'css' => [
                [
                  'property' => 'height',
                  'selector' => '& > .x-slider_builder > .splide__track_builder > .splide__list',
				],
				[
					'property' => 'height',
					'selector' => '& > .splide__track > .splide__list',
				  ]
              ],*/
		];

		

		$this->controls['maybeAdaptiveHeight'] = [
			'group'    => 'slide',
			'label'    => esc_html__( 'Adaptive height', 'bricks' ),
			'type'        => 'checkbox',
			'inline'      => true,
			'tooltip'  => [
				'content'  => 'Will adapt slider height to current slide',
				'position' => 'top-left',
			],
		];

		$this->controls['adaptiveHeight'] = [
			'group'    => 'slide',
			'label'    => esc_html__( 'Adaptive height at this breakpoint', 'bricks' ),
			'type'        => 'select',
			'options'     => [
				'enable' => esc_html__( 'Enable', 'bricks' ),
				'disable'  => esc_html__( 'Disable', 'bricks' ),
			],
			'placeholder' => esc_html__( 'Disable', 'bricks' ),
			'required' => [ 'maybeAdaptiveHeight', '=', true ],
			'inline'      => true,
			'small' => true,
			'tooltip'  => [
				'content'  => 'Will adapt slider height to current slide',
				'position' => 'top-left',
			],
			'rerender' => true,
			'css'   => [
				[
					'property' => '--xadaptiveheight',
					'selector' => '.splide__list',
					'value' => 'var(--xadaptiveheight-%s)'
				],
			],
		];

		$this->controls['adaptiveHeightDuration'] = [
			'group'       => 'slide',
			'label'       => esc_html__( 'Height transiton duration', 'bricks' ),
			'type'        => 'number',
			'units'		 => true,
			'placeholder' => '200ms',
			'required' => [ 'maybeAdaptiveHeight', '=', true ],
			'css'   => [
				[
					'property' => '--xadaptiveheightduration',
					'selector' => '.x-splide__track',
				],
			],
			'rerender' => true,
		];


		// SLIDE

		$this->controls['slidesLayoutSeparator'] = [
			'group'    => 'slide',
			'label'    => esc_html__( 'Slide inner layout & spacing', 'bricks' ),
			'type'     => 'separator',
		];

		$this->controls['slidePadding'] = [
			'group' => 'slide',
			'label' => esc_html__( 'Slide padding', 'bricks' ),
			'type'  => 'spacing',
			'css'   => [
				[
					'property' => 'padding',
					'selector' => '.x-slider_slide',
				],
			],
			'placeholder' => [
				'top'    => '4rem',
				'right'  => '1rem',
				'bottom' => '4rem',
				'left'   => '1rem',
			],
		];

		$this->controls['slideAlignHorizontal'] = [
			'group'   => 'slide',
			'label'   => esc_html__( 'Align horizontal', 'bricks' ),
			'type'    => 'align-items',
			'exclude' => 'stretch',
			'inline'  => true,
			'css'     => [
				[
					'property' => 'align-items',
					'selector' => '.splide__slide',
				],
			],
			'default' => 'center'
		];

		$this->controls['slideAlignVertical'] = [
			'group'   => 'slide',
			'label'   => esc_html__( 'Align vertical', 'bricks' ),
			'type'    => 'justify-content',
			'exclude' => 'space',
			'inline'  => true,
			'css'     => [
				[
					'property' => 'justify-content',
					'selector' => '.splide__slide',
				],
			],
			'default' => 'center'
		];


		$this->controls['directionSeparator'] = [
			'group'    => 'slide',
			'type'     => 'separator',
		];

		$this->controls['perMove'] = [
			'group'       => 'slide',
			'label'       => esc_html__( 'No. of slides to move', 'bricks' ),
			'tooltip'  => [
				'content'  => 'The number of slides to move at once',
				'position' => 'top-left',
			],
			'type'        => 'number',
			'placeholder' => 1,
			'breakpoints' => true,
			'required' => ['type', '!=', 'fade'],
		];

		$this->controls['direction'] = [
			'group'       => 'slide',
			'label'       => esc_html__( 'Direction', 'bricks' ),
			'type'        => 'select',
			'options'     => [
				'ltr' => esc_html__( 'Left to right', 'bricks' ),
				'rtl' => esc_html__( 'Right to left', 'bricks' ),
				'ttb' => esc_html__( 'Vertical', 'bricks' ),
			],
			'inline'      => true,
			'placeholder' => esc_html__( 'Left to right', 'bricks' ),
			'breakpoints' => true,
			/*'css'	      => [
				[
					'property' => '--xslidedirection',
					'selector' => '& > .x-slider_builder',
					'value'    => '%s',
				]
			],*/
			'rerender' => true,
		];


		/* builder */

		$initProSlider = false;

		/* allow for init if prefered */
		$initProSlider = apply_filters( 'bricksextras/proslider/builderinit', $initProSlider );

		if ( !$initProSlider ) {

			$this->controls['perPage']['css'] = [
				[
					'property' => '--xitemstoshow',
					'selector' => '& > .x-slider_builder > .splide__track_builder',
				],
			];

			$this->controls['fixedWidth']['css'] = [
				[
					'property' => 'width',
					'selector' => '& > .x-slider_builder > .splide__track_builder > .splide__list > .x-slider_slide',
				],
			];

			$this->controls['gap']['css'] = [
				[
					'property' => '--xspacebetween',
					'selector' => '& > .x-slider_builder',
				],
			];

			$this->controls['fixedHeight']['css'] = [
				[
					'property' => 'height',
					'selector' => '& > .x-slider_builder > .splide__track_builder > .splide__list > .x-slider_slide',
				],
			];

			$this->controls['autoHeight']['css'] = [
				[
					'property' => '--xsliderautoheight',
					'selector' => '& > .x-slider_builder > .splide__track_builder > .splide__list > .x-slider_slide',
					'value' => 'auto'
				],
			];

			$this->controls['height']['css'] = [
				[
					'property' => 'height',
					'selector' => '& > .x-slider_builder > .splide__track_builder > .splide__list',
				],
				[
					'property' => 'height',
					'selector' => '& > .splide__track > .splide__list',
				]
			];

			$this->controls['direction']['css'] = [
				[
					'property' => '--xslidedirection',
					'selector' => '& > .x-slider_builder',
					'value'    => '%s',
				]
			];

		}


		/*
		$this->controls['trackPadding'] = [
			'group'       => 'slide',
			'label'       => esc_html__( 'Slider track padding', 'bricks' ),
			'type'  => 'number',
			'tooltip'  => [
				'content'  => 'Adds gaps at either end of the slider',
				'position' => 'top-left',
			],
			'units' => true,
            'css'   => [
				[
					'property' => 'padding',
					'selector' => '.splide__track',
				],
			],
		];
		*/

		

		

		
		$this->controls['trackOverflow'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Track overflow', 'bricks' ),
			'group' => 'slide',
			'type' => 'select',
			'hasDynamicData' => false,
			'inline'      => true,
			'small'		  => true,
			'options'     => [
				'unset' => esc_html__( 'Unset', 'bricks' ),
				'hidden'  => esc_html__( 'Hidden', 'bricks' ),
				'visible'  => esc_html__( 'Visible', 'bricks' )
			],
			'info' => esc_html__( "If set to visible, set overflow hidden on the section", 'bricks' ),
			'css'   => [
				[
					'property' => 'overflow',
					'selector' => '& > .splide__track',
				],
				[
					'property' => 'overflow',
					'selector' => '& > .x-slider_builder > .splide__track > .splide__list',
				],
				[
					'property' => 'overflow',
					'selector' => '& > .x-slider_builder > .splide__track',
				],
			],
			
		  ];

		  $this->controls['trackOverflowDirection'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Clip direction', 'bricks' ),
			'placeholder' => esc_html__( 'None', 'bricks' ),
			'group' => 'slide',
			'type' => 'select',
			'hasDynamicData' => false,
			'inline'      => true,
			'small'		  => true,
			'options'     => [
				'left' => esc_html__( 'Left', 'bricks' ),
				'right'  => esc_html__( 'Right', 'bricks' ),
				'none'  => esc_html__( 'None', 'bricks' )
			],
			'css'   => [
				[
					'property' => '--x-slider-overflow',
					'selector' => '.splide__track',
					'value' => 'var(--x-slider-overflow-%s)'
				],
			],
			
		  ];
		
		$this->controls['forceImagesSeparator'] = [
			'group'    => 'slide',
			'type'     => 'separator',
		];

		$this->controls['imageWidth'] = [
			'group'    => 'slide',
			'label'    => esc_html__( 'Force images to be 100% slide height', 'bricks' ),
			'type'     => 'checkbox',
			'inline'   => true,
			'css'   => [
				[
					'property' => 'width',
					'selector' => '.x-slider_slide img',
					'value' => 'auto'
				],
				[
					'property' => 'height',
					'selector' => '.x-slider_slide img',
					'value' => '100%'
				],
				[
					'property' => 'width',
					'selector' => '.x-slider_slide-image',
					'value' => 'auto'
				],
				[
					'property' => 'height',
					'selector' => '.x-slider_slide-image',
					'value' => '100%'
				],
			],
		];

		$this->controls['imageForceWidth'] = [
			'group'    => 'slide',
			'label'    => esc_html__( 'Force images to be 100% slide width', 'bricks' ),
			'type'     => 'checkbox',
			'inline'   => true,
			'css'   => [
				[
					'property' => 'width',
					'selector' => '.x-slider_slide img',
					'value' => '100%'
				],
				[
					'property' => 'width',
					'selector' => '.x-slider_slide-image',
					'value' => '100%'
				],
			],
		];
		

        

		// Fixed settings:

		$this->controls['type'] = [
			'group'       => 'options',
			'label'       => esc_html__( 'Type', 'bricks' ),
			'type'        => 'select',
			'options'     => [
				'loop'  => esc_html__( 'Loop', 'bricks' ),
				'slide' => esc_html__( 'Slide', 'bricks' ),
				'fade'  => esc_html__( 'Fade', 'bricks' ),
			],
			'inline'      => true,
			'placeholder' => esc_html__( 'Slide', 'bricks' ),
			'small' 	  => true
		];

		$this->controls['keyboard'] = [
			'group'       => 'options',
			'label'       => esc_html__( 'Keyboard', 'bricks' ),
			'type'        => 'select',
			'options'     => [
				'false'   => esc_html__( 'Off', 'bricks' ),
				'focused' => esc_html__( 'Focused', 'bricks' ),
				'global'  => esc_html__( 'Global', 'bricks' ),
			],
			'tooltip'  => [
				'content'  => 'Enables shortcuts globally or only on slider focus',
				'position' => 'top-left',
			],
			'inline'      => true,
			'placeholder' => esc_html__( 'Focused', 'bricks' ),
			'breakpoints' => true,
			'small' 	  => true
		];
		

		$this->controls['start'] = [
			'group'       => 'options',
			'label'       => esc_html__( 'Start index', 'bricks' ),
			'type'        => 'number',
			'placeholder' => 0,
		];


		$this->controls['speed'] = [
			'group'       => 'options',
			'label'       => esc_html__( 'Speed (ms)', 'bricks' ),
			'type'        => 'number',
			'placeholder' => 400,
			'breakpoints' => true,
		];

		$this->controls['focus'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Focus', 'bricks' ),
			'group' => 'options',
			'type' => 'text',
			'hasDynamicData' => false,
			'inline'      => true,
			'small'		  => true,
			'breakpoints' => true,
			'tooltip'  => [
				'content'  => 'Which slide to focus if slider has multiple per page',
				'position' => 'top-left',
			],
		  ];

		
		$this->controls['omitEnd'] = [
			'group'    => 'options',
			'label'    => esc_html__( 'Omit end', 'bricks' ),
			'type'     => 'checkbox',
		];

		  $this->controls['trimSpace'] = [
			'group'       => 'options',
			'label'       => esc_html__( 'Trim space', 'bricks' ),
			'tooltip'  => [
				'content'  => 'Trim spaces before/after the carousel',
				'position' => 'top-left',
			],
			'type'        => 'select',
			'options'     => [
				'enable' => esc_html__( 'Enable', 'bricks' ),
				'disable'  => esc_html__( 'Disable', 'bricks' ),
			],
			'inline'      => true,
			'placeholder' => esc_html__( 'Enable', 'bricks' ),
		];


		$this->controls['wheel'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Navigation by mouse wheel', 'bricks' ),
			'group' => 'options',
			'type'     => 'checkbox',
			'inline'   => true,
		]; 

		$this->controls['edgeEffectSep'] = [
			'tab' => 'content',
			'group' => 'options',
			'type'     => 'separator',
			'inline'   => true,
			'required' => [ 'type', '=', 'loop']
		]; 

		$this->controls['edgeEffect'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Add loop fade effect', 'bricks' ),
			'group' => 'options',
			'type'     => 'checkbox',
			'inline'   => true,
			'required' => [ 'type', '=', 'loop']
		]; 

		$this->controls['edgeEffectDistance'] = [
			'group'       => 'options',
			'label'       => esc_html__( 'Distance from edges', 'bricks' ),
			'type'        => 'number',
			'units'       => true,
			'placeholder' => '10%',
			'css'         => [
				[
					'property' => '--x-slider-mask-edge',
					'selector' => '.splide__track',
				],
			],
			'required' => [ 
				['type', '=', 'loop'],
				['edgeEffect', '=', true],
			]
		];

		$this->controls['edgeEffectSlope'] = [
			'group'       => 'options',
			'label'       => esc_html__( 'Gradient slope', 'bricks' ),
			'type'        => 'number',
			'step' => '0.1',
			'min'	=> 1,
			'placeholder' => '2',
			'css'         => [
				[
					'property' => '--x-slider-mask-edge-slope',
					'selector' => '.splide__track',
				],
			],
			'required' => [ 
				['type', '=', 'loop'],
				['edgeEffect', '=', true],
			]
		];

		$this->controls['wheelSleep'] = [
			'group'       => 'options',
			'label'       => esc_html__( 'Wheel sleep', 'bricks' ),
			'type'        => 'number',
			'placeholder' => 700,
			'breakpoints' => true,
			'required' => [ 'wheel', '=', true ],
			'tooltip'  => [
				'content'  => 'The sleep duration (in ms) until accepting next wheel',
				'position' => 'top-left',
			],
		];

		$this->controls['releaseWheel'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Release Wheel', 'bricks' ),
			'group' => 'options',
			'type'     => 'checkbox',
			'required' => [ 'wheel', '=', true ],
			'inline'   => true,
			'tooltip'  => [
				'content'  => 'Release wheel event when reaches the first / last slide',
				'position' => 'top-left',
			],
		  ]; 


		$this->controls['lazySep'] = [
			'group'    => 'options',
			'label'    => esc_html__( 'Lazy loading images', 'bricks' ),
			'type'     => 'separator',
		];

		$this->controls['lazyLoad'] = [
			'group'       => 'options',
			'label'       => esc_html__( 'Lazy load', 'bricks' ),
			'type'        => 'select',
			'options'     => [
				'false'   => esc_html__( 'False', 'bricks' ),
				'nearby' => esc_html__( 'Nearby (recommended)', 'bricks' ),
				'sequential'  => esc_html__( 'Sequential', 'bricks' ),
			],
			'inline'      => true,
			'placeholder' => esc_html__( 'Nearby (recommended)', 'bricks' ),
			'small' 	  => true
		];

		$this->controls['preloadPages'] = [
			'group'       => 'options',
			'label'       => esc_html__( 'Lazy loading preload', 'bricks' ),
			'type'        => 'number',
			'placeholder' => '1',
			'info'  => 'How many pages around the active slide to load',
			'required' => [ 'lazyLoad', '!=', ['false','sequential'] ]
		];

		

		  


		

		$this->controls['interactionSeparator'] = [
			'group'    => 'options',
			'label'    => esc_html__( 'User Interaction', 'bricks' ),
			'type'     => 'separator',
		];

		$this->controls['dragMinThreshold'] = [
			'group'    => 'options',
			'label'    => esc_html__( 'Min Threshold for Drag (px)', 'bricks' ),
			'tooltip'  => [
				'content'  => 'Distance needed to start moving slider by touch.',
				'position' => 'top-left',
			],
			'type'     => 'number',
			'inline'   => true,
			'placeholder' => '10'
		];

		$this->controls['flickPower'] = [
			'group'    => 'options',
			'label'    => esc_html__( 'Flick Power', 'bricks' ),
			'tooltip'  => [
				'content'  => 'The larger this number, the farther the slider runs',
				'position' => 'top-left',
			],
			'type'     => 'number',
			'inline'   => true,
			'placeholder' => '600'
		];

		$this->controls['flickMaxPages'] = [
			'group'    => 'options',
			'tooltip'  => [
				'content'  => 'Limits the number of pages to move by flicking',
				'position' => 'top-left',
			],
			'label'    => esc_html__( 'Max pages that can be flicked', 'bricks' ),
			//'description'    => esc_html__( 'Limits the number of pages to move by the flick action', 'bricks' ),
			'type'     => 'number',
			'inline'   => true,
			'placeholder' => '1'
		];

		$this->controls['hashNavigation'] = [
			'group'    => 'options',
			'label'    => esc_html__( 'URL Hash Navigation', 'bricks' ),
			'type'     => 'checkbox',
			'inline'   => true,
			'tooltip'  => [
				'content'  => 'Make the slider correspond with the URL hash',
				'position' => 'top-left',
			],
		];
		
		
		$this->controls['drag'] = [
			'group'       => 'options',
			'label'       => esc_html__( 'Allow user to drag', 'bricks' ),
			'type'        => 'select',
			'options'     => [
				'true'  => esc_html__( 'True', 'bricks' ),
				'false' => esc_html__( 'False', 'bricks' ),
				'free' => esc_html__( 'Free scrolling', 'bricks' ),
			],
			'breakpoints' => true,
			'inline'      => true,
			'placeholder' => esc_html__( 'True', 'bricks' ),
		];

		$this->controls['easing'] = [
			'group'    => 'options',
			'label'    => esc_html__( 'Easing', 'bricks' ),
			'type'     => 'text',
			'inline'   => true,
			'hasDynamicData' => false,
			'placeholder' => 'cubic-bezier(0.25, 1, 0.5, 1)',
			'tootip' => [
				'content' => 'Timing function for the transition, such as linear, ease or cubic-bezier()',
				'position' => 'top-left',
			],
			'breakpoints' => true,
		];

		$this->controls['snap'] = [
			'group'       => 'options',
			'label'    => esc_html__( 'Snap (to closest slide)', 'bricks' ),
			'type'        => 'select',
			'options'     => [
				'true'  => esc_html__( 'True', 'bricks' ),
				'false' => esc_html__( 'False', 'bricks' ),
			],
			'tooltip'  => [
				'content'  => 'Snap the slides into place after finishes scrolling',
				'position' => 'top-left',
			],
			'breakpoints' => true,
			'inline'   => true,
			'required'    => [ 'drag', '=', 'free'],
		];


		// REWIND

		$this->controls['rewindSeparator'] = [
			'group'    => 'options',
			'label'    => esc_html__( 'Rewind', 'bricks' ),
			'type'     => 'separator',
			'required' => [
				[ 'type', '!=', [ 'loop' ] ],
			],
		];

		$this->controls['rewind'] = [
			'group'    => 'options',
			'label'    => esc_html__( 'Rewind', 'bricks' ),
			'type'     => 'checkbox',
			'inline'   => true,
			'required' => [
				[ 'type', '!=', [ 'loop' ] ],
			],
			'tooltip'  => [
				'content'  => 'Rewind slider back to start when reaches end',
				'position' => 'top-left',
			],
		];

		$this->controls['rewindByDrag'] = [
			'group'    => 'options',
			'label'    => esc_html__( 'Rewind by drag', 'bricks' ),
			'type'     => 'checkbox',
			'inline'   => true,
			'required' => [
				[ 'type', '!=', [ 'loop' ] ],
				[ 'rewind', '!=', '' ]
			],
		];

		$this->controls['rewindSpeed'] = [
			'group'    => 'options',
			'label'    => esc_html__( 'Speed (ms)', 'bricks' ),
			'type'     => 'number',
			'inline'   => true,
			'required' => [
				[ 'type', '!=', [ 'loop' ] ],
				[ 'rewind', '!=', '' ]
			],
		];

		

		// Arrows

		$this->controls['arrows'] = [
			'group'       => 'arrows',
			'label'       => esc_html__( 'Nav arrows', 'bricks' ),
			'type'        => 'select',
			'options'     => [
				'true'  => esc_html__( 'Icons', 'bricks' ),
				'false' => esc_html__( 'None', 'bricks' ),
				//'custom' => esc_html__( 'Custom', 'bricks' ),
			],
			'inline'      => true,
			'placeholder' => esc_html__( 'Icons', 'bricks' ),
			'default' => 'true'

		];

		$this->controls['arrowHeight'] = [
			'group'       => 'arrows',
			'label'       => esc_html__( 'Height', 'bricks' ),
			'type'        => 'number',
			'units'       => true,
			'css'         => [
				[
					'property' => 'height',
					'selector' => '& > .splide__arrows > .splide__arrow',
				],
				[
					'property' => 'height',
					'selector' => '& > .x-slider_builder > .splide__arrows > .splide__arrow',
				],
			],
			'placeholder' => 50,
			'required'    => [ 'arrows', '!=', ['false','custom'] ],
		];

		$this->controls['arrowWidth'] = [
			'group'       => 'arrows',
			'label'       => esc_html__( 'Width', 'bricks' ),
			'type'        => 'number',
			'units'       => true,
			'css'         => [
				[
					'property' => 'width',
					'selector' => '& > .splide__arrows > .splide__arrow',
				],
				[
					'property' => 'width',
					'selector' => '& > .x-slider_builder > .splide__arrows > .splide__arrow',
				],
			],
			'placeholder' => 50,
			'required'    => [ 'arrows', '!=', ['false','custom'] ],
		];

		$this->controls['arrowSize'] = [
			'group'       => 'arrows',
			'label'       => esc_html__( 'Icon size', 'bricks' ),
			'type'        => 'number',
			'units'       => true,
			'css'         => [
				[
					'property' => 'font-size',
					'selector' => '& > .splide__arrows .splide__arrow',
				],
				[
					'property' => 'font-size',
					'selector' => '& > .x-slider_builder > .splide__arrows .splide__arrow',
				],
			],
			//'placeholder' => 50,
			'required'    => [ 'arrows', '!=', ['false','custom'] ],
		];

		$this->controls['arrowBackground'] = [
			'group'    => 'arrows',
			'label'    => esc_html__( 'Background', 'bricks' ),
			'type'     => 'color',
			'css'      => [
				[
					'property' => 'background',
					'selector' => '& > .splide__arrows .splide__arrow',
				],
				[
					'property' => 'background',
					'selector' => '& > .x-slider_builder > .splide__arrows .splide__arrow',
				],
			],
			'required'    => [ 'arrows', '!=', ['false','custom'] ],
		];

		$this->controls['arrowBorder'] = [
			'group'    => 'arrows',
			'label'    => esc_html__( 'Border', 'bricks' ),
			'type'     => 'border',
			'css'      => [
				[
					'property' => 'border',
					'selector' => '& > .splide__arrows .splide__arrow',
				],
				[
					'property' => 'border',
					'selector' => '& > .x-slider_builder > .splide__arrows .splide__arrow',
				],
			],
			'required'    => [ 'arrows', '!=', ['false','custom'] ],
		];

		$this->controls['arrowShadow'] = [
			'tab'    => 'content',
			'group'  => 'arrows',
			'label'  => esc_html__( 'Box Shadow', 'extras' ),
			'type'   => 'box-shadow',
			'css'    => [
				[
					'property' => 'box-shadow',
					'selector' => '& > .splide__arrows .splide__arrow',
				],
				[
					'property' => 'box-shadow',
					'selector' => '& > .x-slider_builder > .splide__arrows .splide__arrow',
				],
			],
			'required'    => [ 'arrows', '!=', ['false','custom'] ],
		];

		$this->controls['arrowTypography'] = [
			'group'    => 'arrows',
			'label'    => esc_html__( 'Typography', 'bricks' ),
			'type'     => 'typography',
			'css'      => [
				[
					'property' => 'font',
					'selector' => '& > .splide__arrows .splide__arrow',
				],
				[
					'property' => 'font',
					'selector' => '& > .x-slider_builder > .splide__arrows .splide__arrow',
				],
			],
			'exclude'  => [
				'font-family',
				'font-weight',
				'font-style',
				'text-align',
				'letter-spacing',
				'line-height',
				'text-decoration',
				'text-transform',
			],
			'required'    => [ 'arrows', '!=', ['false','custom'] ],
		];

		$this->controls['arrowOpacity'] = [
			'group'    => 'arrows',
			'label'    => esc_html__( 'Opacity when disabled', 'bricks' ),
			'type'     => 'number',
			'step'	   => 0.1,
			'min'	   => 0,
			'max'	   => 1,
			'placeholder' => '0.5',
			'css'      => [
				[
					'property' => 'opacity',
					'selector' => '& > .splide__arrows .splide__arrow:disabled',
				],
				[
					'property' => 'opacity',
					'selector' => '& > .x-slider_builder > .splide__arrows .splide__arrow:disabled',
				],
			],
			'required'    => [ 'arrows', '!=', ['false','custom'] ],
		];

		$this->controls['arrowHide'] = [
			'group'    => 'arrows',
			'label'    => esc_html__( 'Hide at this breakpoint', 'bricks' ),
			'type'     => 'select',
			'options'     => [
				'none' => esc_html__( 'True', 'bricks' ),
				'flex'  => esc_html__( 'False', 'bricks' ),
			],
			'css'      => [
				[
					'property' => 'display',
					'selector' => '& > .splide__arrows',
				],
				[
					'property' => 'display',
					'selector' => '& > .x-slider_builder > .splide__arrows',
				],
			],
			'inline'      => true,
			'placeholder' => esc_html__( 'False', 'bricks' ),
			'required'    => [ 'arrows', '!=', ['false','custom'] ],
		];

		

		// PREV ARROW

		$this->controls['prevArrowSeparator'] = [
			'group'    => 'arrows',
			'label'    => esc_html__( 'Prev arrow', 'bricks' ),
			'type'     => 'separator',
			'required'    => [ 'arrows', '!=', ['false','custom'] ],

		];

		$this->controls['prevArrow'] = [
			'group'    => 'arrows',
			'label'    => esc_html__( 'Prev arrow', 'bricks' ),
			'type'     => 'icon',
			'rerender' => true,
			'css'      => [
				[
					'selector' => '& > .x-splide__arrows .splide__arrow--prev > *',
				],
				[
					'selector' => '& > .x-slider_builder > .x-splide__arrows .splide__arrow--prev > *',
				],
			],
			'required' => [ 'arrows', '!=', '' ],
			 /*'default'  => [
				'library' => 'fontawesomeSolid',
				'icon'    => 'fas fa-angle-left',
			  ],*/
			  'required'    => [ 'arrows', '!=', ['false','custom'] ],
		];

		$this->controls['prevArrowTop'] = [
			'group'    => 'arrows',
			'label'    => esc_html__( 'Top', 'bricks' ),
			'type'     => 'number',
			'units'    => true,
			'css'      => [
				[
					'property' => 'top',
					'selector' => '& > .x-splide__arrows .splide__arrow--prev',
				],
				[
					'property' => 'top',
					'selector' => '& > .x-slider_builder > .x-splide__arrows .splide__arrow--prev',
				],
			],
			'required'    => [ 'arrows', '!=', ['false','custom'] ],
		];

		$this->controls['prevArrowRight'] = [
			'group'    => 'arrows',
			'label'    => esc_html__( 'Right', 'bricks' ),
			'type'     => 'number',
			'units'    => true,
			'css'      => [
				[
					'property' => 'right',
					'selector' => '& > .x-splide__arrows .splide__arrow--prev',
				],
				[
					'property' => 'right',
					'selector' => '& > .x-slider_builder > .x-splide__arrows .splide__arrow--prev',
				],
			],
			'required'    => [ 'arrows', '!=', ['false','custom'] ],
		];

		$this->controls['prevArrowBottom'] = [
			'group'    => 'arrows',
			'label'    => esc_html__( 'Bottom', 'bricks' ),
			'type'     => 'number',
			'units'    => true,
			'css'      => [
				[
					'property' => 'bottom',
					'selector' => '& > .x-splide__arrows .splide__arrow--prev',
				],
				[
					'property' => 'bottom',
					'selector' => '& > .x-slider_builder > .x-splide__arrows .splide__arrow--prev',
				],
			],
			'required'    => [ 'arrows', '!=', ['false','custom'] ],
		];

		$this->controls['prevArrowLeft'] = [
			'group'    => 'arrows',
			'label'    => esc_html__( 'Left', 'bricks' ),
			'type'     => 'number',
			'units'    => true,
			'css'      => [
				[
					'property' => 'left',
					'selector' => '& > .x-splide__arrows .splide__arrow--prev',
				],
				[
					'property' => 'left',
					'selector' => '& > .x-slider_builder > .x-splide__arrows .splide__arrow--prev',
				],
			],
			'required'    => [ 'arrows', '!=', ['false','custom'] ],
		];

		$this->controls['prevArrowMargin'] = [
			'group'       => 'arrows',
			'label'       => esc_html__( 'Margin', 'bricks' ),
			'type'        => 'spacing',
			'css'         => [
				[
					'property' => 'margin',
					'selector' => '& > .x-splide__arrows .splide__arrow--prev',
				],
				[
					'property' => 'margin',
					'selector' => '& > .x-slider_builder > .x-splide__arrows .splide__arrow--prev',
				],
			],
			'required'    => [ 'arrows', '!=', ['false','custom'] ],
		];


		// NEXT ARROW

		$this->controls['nextArrowSeparator'] = [
			'group'    => 'arrows',
			'label'    => esc_html__( 'Next arrow', 'bricks' ),
			'type'     => 'separator',
			'required'    => [ 'arrows', '!=', ['false','custom'] ],
		];

		$this->controls['nextArrow'] = [
			'group'    => 'arrows',
			'label'    => esc_html__( 'Next arrow', 'bricks' ),
			'type'     => 'icon',
			'rerender' => true,
			'css'      => [
				[
					'selector' => '& > .x-splide__arrows .splide__arrow--next > *',
				],
				[
					'selector' => '& > .x-slider_builder > .x-splide__arrows .splide__arrow--next > *',
				],
			],
			'required'    => [ 'arrows', '!=', ['false','custom'] ],
			/*'default'  => [
				'library' => 'fontawesomeSolid',
				'icon'    => 'fas fa-angle-right',
			],*/
		];

		$this->controls['nextArrowTop'] = [
			'group'    => 'arrows',
			'label'    => esc_html__( 'Top', 'bricks' ),
			'type'     => 'number',
			'units'    => true,
			'css'      => [
				[
					'property' => 'top',
					'selector' => '& > .x-splide__arrows .splide__arrow--next',
				],
				[
					'property' => 'top',
					'selector' => '& > .x-slider_builder > .x-splide__arrows .splide__arrow--next',
				],
			],
			'required'    => [ 'arrows', '!=', ['false','custom'] ],
		];

		$this->controls['nextArrowRight'] = [
			'group'    => 'arrows',
			'label'    => esc_html__( 'Right', 'bricks' ),
			'type'     => 'number',
			'units'    => true,
			'css'      => [
				[
					'property' => 'right',
					'selector' => '& > .x-splide__arrows .splide__arrow--next',
				],
				[
					'property' => 'right',
					'selector' => '& > .x-slider_builder > .x-splide__arrows .splide__arrow--next',
				],
			],
			'required'    => [ 'arrows', '!=', ['false','custom'] ],
		];

		$this->controls['nextArrowBottom'] = [
			'group'    => 'arrows',
			'label'    => esc_html__( 'Bottom', 'bricks' ),
			'type'     => 'number',
			'units'    => true,
			'css'      => [
				[
					'property' => 'bottom',
					'selector' => '& > .x-splide__arrows .splide__arrow--next',
				],
				[
					'property' => 'bottom',
					'selector' => '& > .x-slider_builder > .x-splide__arrows .splide__arrow--next',
				],
			],
			'required'    => [ 'arrows', '!=', ['false','custom'] ],
		];

		$this->controls['nextArrowLeft'] = [
			'group'    => 'arrows',
			'label'    => esc_html__( 'Left', 'bricks' ),
			'type'     => 'number',
			'units'    => true,
			'css'      => [
				[
					'property' => 'left',
					'selector' => '& > .x-splide__arrows .splide__arrow--next',
				],
				[
					'property' => 'left',
					'selector' => '& > .x-slider_builder > .x-splide__arrows .splide__arrow--next',
				],
			],
			'required'    => [ 'arrows', '!=', ['false','custom'] ],
		];

		$this->controls['nextArrowMargin'] = [
			'group'       => 'arrows',
			'label'       => esc_html__( 'Margin', 'bricks' ),
			'type'        => 'spacing',
			'css'         => [
				[
					'property' => 'margin',
					'selector' => '& > .x-splide__arrows .splide__arrow--next',
				],
				[
					'property' => 'margin',
					'selector' => '& > .x-slider_builder > .x-splide__arrows .splide__arrow--next',
				],
			],
			'required'    => [ 'arrows', '!=', ['false','custom'] ],
		];

		// Pagination (dots)

		$this->controls['pagination'] = [
			'group'    => 'pagination',
			'label'    => esc_html__( 'Enable', 'bricks' ),
			'type'     => 'checkbox',
			'inline'   => true,
			'rerender' => true,
			'default'  => true,
		];

		$this->controls['paginationHeight'] = [
			'group'       => 'pagination',
			'label'       => esc_html__( 'Height', 'bricks' ),
			'type'        => 'number',
			'units'       => true,
			'units'       => [
				'px' => [
					'min' => 1,
					'max' => 100,
				],
			],
			'css'         => [
				[
					'property' => 'height',
					'selector' => '& > .splide__pagination .splide__pagination__page',
				],
				[
					'property' => 'height',
					'selector' => '& > .x-slider_builder > .splide__pagination .splide__pagination__page',
				],
			],
			'placeholder' => '10px',
			'required'    => [ 'pagination', '!=', '' ],
		];

		$this->controls['paginationWidth'] = [
			'group'       => 'pagination',
			'label'       => esc_html__( 'Width', 'bricks' ),
			'type'        => 'number',
			'units'       => true,
			'units'       => [
				'px' => [
					'min' => 1,
					'max' => 100,
				],
			],
			'css'         => [
				[
					'property' => 'width',
					'selector' => '& > .splide__pagination .splide__pagination__page',
				],
				[
					'property' => 'width',
					'selector' => '& > .x-slider_builder > .splide__pagination .splide__pagination__page',
				],
			],
			'placeholder' => '10px',
			'required'    => [ 'pagination', '!=', '' ],
		];

		$this->controls['paginationColor'] = [
			'group'    => 'pagination',
			'label'    => esc_html__( 'Color', 'bricks' ),
			'type'     => 'color',
			'css'      => [
				[
					'property' => 'color',
					'selector' => '& > .splide__pagination .splide__pagination__page',
				],
				[
					'property' => 'background-color',
					'selector' => '& > .splide__pagination .splide__pagination__page',
				],
				[
					'property' => 'color',
					'selector' => '& > .x-slider_builder > .splide__pagination .splide__pagination__page',
				],
				[
					'property' => 'background-color',
					'selector' => '& > .x-slider_builder > .splide__pagination .splide__pagination__page',
				],
			],
			'required' => [ 'pagination', '!=', '' ],
		];

		$this->controls['paginationBorder'] = [
			'group'    => 'pagination',
			'label'    => esc_html__( 'Border', 'bricks' ),
			'type'     => 'border',
			'css'      => [
				[
					'property' => 'border',
					'selector' => '& > .splide__pagination .splide__pagination__page',
				],
				[
					'property' => 'border',
					'selector' => '& > .x-slider_builder > .splide__pagination .splide__pagination__page',
				],
			],
			'required' => [ 'pagination', '!=', '' ],
		];

		$this->controls['paginationSpacing'] = [
			'group'       => 'pagination',
			'label'       => esc_html__( 'Margin', 'bricks' ),
			'type'        => 'spacing',
			'css'         => [
				[
					'property' => 'margin',
					'selector' => '& > .splide__pagination .splide__pagination__page',
				],
				[
					'property' => 'margin',
					'selector' => '& > .x-slider_builder > .splide__pagination .splide__pagination__page',
				],
			],
			'placeholder' => [
				'top'    => '5px',
				'right'  => '5px',
				'bottom' => '5px',
				'left'   => '5px',
			],
			'required'    => [ 'pagination', '!=', '' ],
		];


		$this->controls['paginationHide'] = [
			'group'    => 'pagination',
			'label'    => esc_html__( 'Hide at this breakpoint', 'bricks' ),
			'type'     => 'select',
			'options'     => [
				'none' => esc_html__( 'True', 'bricks' ),
				'flex'  => esc_html__( 'False', 'bricks' ),
			],
			'css'      => [
				[
					'property' => 'display',
					'selector' => '& > .splide__pagination'
				],
				[
					'property' => 'display',
					'selector' => '& > .x-slider_builder > .splide__pagination'
				],
			],
			'required'    => [ 'pagination', '!=', '' ],
			'placeholder' => esc_html__( 'False', 'bricks' ),
		];

		


		// ACTIVE

		$this->controls['paginationActiveSeparator'] = [
			'group'    => 'pagination',
			'label'    => esc_html__( 'Active dots', 'bricks' ),
			'type'     => 'separator',
			'required' => [ 'pagination', '!=', '' ],
		];

		$this->controls['paginationScaleActive'] = [
			'group'    => 'pagination',
			'label'    => esc_html__( 'Scale', 'bricks' ),
			'type'     => 'number',
			'units'    => false,
			'placeholder' => '1',
			'css'      => [
				[
					'property' => 'transform',
					'selector' => '& > .splide__pagination .splide__pagination__page.is-active',
					'value' => 'scale(%s)',
				],
				[
					'property' => 'transform',
					'selector' => '& > .x-slider_builder > .splide__pagination .splide__pagination__page.is-active',
					'value' => 'scale(%s)',
				],
			],
			'required' => [ 'pagination', '!=', '' ],
		];

		$this->controls['paginationColorActive'] = [
			'group'    => 'pagination',
			'label'    => esc_html__( 'Color', 'bricks' ),
			'type'     => 'color',
			'css'      => [
				[
					'property' => 'color',
					'selector' => '& > .splide__pagination .splide__pagination__page.is-active',
				],
				[
					'property' => 'background-color',
					'selector' => '& > .splide__pagination .splide__pagination__page.is-active',
				],
				[
					'property' => 'color',
					'selector' => '& > .x-slider_builder > .splide__pagination .splide__pagination__page.is-active',
				],
				[
					'property' => 'background-color',
					'selector' => '& > .x-slider_builder > .splide__pagination .splide__pagination__page.is-active',
				],
			],
			'required' => [ 'pagination', '!=', '' ],
		];

		$this->controls['paginationBorderActive'] = [
			'group'    => 'pagination',
			'label'    => esc_html__( 'Border', 'bricks' ),
			'type'     => 'border',
			'css'      => [
				[
					'property' => 'border',
					'selector' => '& > .splide__pagination .splide__pagination__page.is-active',
				],
				[
					'property' => 'border',
					'selector' => '& > .x-slider_builder > .splide__pagination .splide__pagination__page.is-active',
				],
			],
			'required' => [ 'pagination', '!=', '' ],
		];

		$this->controls['paginationActiveHeight'] = [
			'group'       => 'pagination',
			'label'       => esc_html__( 'Height', 'bricks' ),
			'type'        => 'number',
			'units'       => true,
			'units'       => [
				'px' => [
					'min' => 1,
					'max' => 100,
				],
			],
			'css'         => [
				[
					'property' => 'height',
					'selector' => '& > .splide__pagination .splide__pagination__page.is-active',
				],
				[
					'property' => 'height',
					'selector' => '& > .x-slider_builder > .splide__pagination .splide__pagination__page.is-active',
				],
			],
			'placeholder' => '10px',
			'required'    => [ 'pagination', '!=', '' ],
		];

		$this->controls['paginationActiveWidth'] = [
			'group'       => 'pagination',
			'label'       => esc_html__( 'Width', 'bricks' ),
			'type'        => 'number',
			'units'       => true,
			'units'       => [
				'px' => [
					'min' => 1,
					'max' => 100,
				],
			],
			'css'         => [
				[
					'property' => 'width',
					'selector' => '& > .splide__pagination .splide__pagination__page.is-active',
				],
				[
					'property' => 'width',
					'selector' => '& > .x-slider_builder > .splide__pagination .splide__pagination__page.is-active',
				],
			],
			'placeholder' => '10px',
			'required'    => [ 'pagination', '!=', '' ],
		];

		// POSITION

		$this->controls['paginationPositionSeparator'] = [
			'group'    => 'pagination',
			'label'    => esc_html__( 'Position', 'bricks' ),
			'type'     => 'separator',
			'required' => [ 'pagination', '!=', '' ],
		];

		$this->controls['paginationTop'] = [
			'group'    => 'pagination',
			'label'    => esc_html__( 'Top', 'bricks' ),
			'type'     => 'number',
			'units'    => true,
			'css'      => [
				[
					'property' => 'top',
					'selector' => '& > .splide__pagination',
				],
				[
					'property' => 'bottom',
					'value'    => 'auto',
					'selector' => '& > .splide__pagination',
				],
				[
					'property' => 'top',
					'selector' => '& > .x-slider_builder > .splide__pagination'
				],
				[
					'property' => 'bottom',
					'value'    => 'auto',
					'selector' => '& > .x-slider_builder > .splide__pagination'
				],
			],
			'required' => [ 'pagination', '!=', '' ],
		];

		$this->controls['paginationRight'] = [
			'group'    => 'pagination',
			'label'    => esc_html__( 'Right', 'bricks' ),
			'type'     => 'number',
			'units'    => true,
			'css'      => [
				[
					'property' => 'right',
					'selector' => '& > .splide__pagination',
				],
				[
					'property' => 'left',
					'value'    => 'auto',
					'selector' => '& > .splide__pagination',
				],
				[
					'property' => 'transform',
					'selector' => '& > .splide__pagination',
					'value'    => 'translateX(0)',
				],
				[
					'property' => 'right',
					'selector' => '& > .x-slider_builder > .splide__pagination'
				],
				[
					'property' => 'left',
					'value'    => 'auto',
					'selector' => '& > .x-slider_builder > .splide__pagination'
				],
				[
					'property' => 'transform',
					'selector' => '& > .x-slider_builder > .splide__pagination',
					'value'    => 'translateX(0)',
				],
			],
			'required' => [ 'pagination', '!=', '' ],
		];

		$this->controls['paginationBottom'] = [
			'group'    => 'pagination',
			'label'    => esc_html__( 'Bottom', 'bricks' ),
			'type'     => 'number',
			'units'    => true,
			'css'      => [
				[
					'property' => 'bottom',
					'selector' => '& > .splide__pagination',
				],
				[
					'property' => 'bottom',
					'selector' => '& > .x-slider_builder > .splide__pagination'
				],
			],
			'required' => [ 'pagination', '!=', '' ],
		];

		$this->controls['paginationLeft'] = [
			'group'    => 'pagination',
			'label'    => esc_html__( 'Left', 'bricks' ),
			'type'     => 'number',
			'units'    => true,
			'css'      => [
				[
					'property' => 'left',
					'selector' => '& > .splide__pagination',
				],
				[
					'property' => 'right',
					'value'    => 'auto',
					'selector' => '& > .splide__pagination',
				],
				[
					'property' => 'transform',
					'selector' => '& > .splide__pagination',
					'value'    => 'translateX(0)',
				],
				[
					'property' => 'left',
					'selector' => '& > .x-slider_builder > .splide__pagination'
				],
				[
					'property' => 'right',
					'value'    => 'auto',
					'selector' => '& > .x-slider_builder > .splide__pagination'
				],
				[
					'property' => 'transform',
					'selector' => '& > .x-slider_builder > .splide__pagination',
					'value'    => 'translateX(0)',
				],
			],
			'required' => [ 'pagination', '!=', '' ],
		];



		
		// SYNCING

		$this->controls['syncingInfo'] = [
			'group'    => 'sync',
			'label' => esc_html__( 'Slider syncing', 'bricks' ),
			
			'type'     => 'separator',
		];

		$this->controls['isNavigation'] = [
			'group'    => 'sync',
			'label'    => esc_html__( 'Enable sync', 'bricks' ),
			'info' => esc_html__( 'Makes slides clickable to navigate another slider.', 'bricks' ),
			'type'     => 'checkbox',
			'inline'   => true,
		];

		$this->controls['syncSelector'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Selector of the other slider(s) to control', 'bricks' ),
			'info' => esc_html__( 'If inside a query loop, use the ID of the other slider', 'bricks' ),
			'group' => 'sync',
			'type' => 'text',
			'placeholder' => '.main-slider',
			'required' => [ 'isNavigation', '=', true ],
			'hasDynamicData' => false,
		  ];

		  $this->controls['independentScrolling'] = [
			'group'    => 'sync',
			'label'    => esc_html__( 'Independent scrolling', 'bricks' ),
			'info' => esc_html__( 'Only control slider by clicking slides', 'bricks' ),
			'type'     => 'checkbox',
			'required' => [ 'isNavigation', '=', true ],
			'inline'   => true,
		];


		  // AUTOPLAY

		$this->controls['autoplayscroll'] = [
			'group'       => 'autoScroll',
			'label'       => esc_html__( 'Enable', 'bricks' ),
			'type'        => 'select',
			'options'     => [
				'autoplay' => esc_html__( 'Auto play (intervals)', 'bricks' ),
				'autoscroll'  => esc_html__( 'Auto scroll (continuous)', 'bricks' ),
				'none'  => esc_html__( 'None', 'bricks' ),
			],
			'inline'      => true,
			'placeholder' => esc_html__( 'None', 'bricks' ),
		];

		$this->controls['interval'] = [
			'group'       => 'autoScroll',
			'label'       => esc_html__( 'Interval (ms)', 'bricks' ),
			'type'        => 'number',
			'required'    => [ 'autoplayscroll', '=', 'autoplay' ],
			'placeholder' => 3000,
		];

		$this->controls['autoplayPaused'] = [
			'group'       => 'autoScroll',
			'label'       => esc_html__( 'Start as paused', 'bricks' ),
			'type'        => 'checkbox',
			'required'    => [ 'autoplayscroll', '=', 'autoplay' ],
			'inline' => true,
		];

		$this->controls['autoScrollSpeed'] = [
			'group'       => 'autoScroll',
			'label'       => esc_html__( 'Speed', 'bricks' ),
			'description'       => esc_html__( '(minus number to reverse direction)', 'bricks' ),
			'type'        => 'number',
			'units'	 	  => false,
			'placeholder' => '1',
			'min'		 => -5,
			'max'		 => 5,
			'step'		  => 0.1,
			'required'    => [ 'autoplayscroll', '=', 'autoscroll' ],
		];

		$this->controls['pauseOnHover'] = [
			'group'    => 'autoScroll',
			'label'    => esc_html__( 'Pause on hover', 'bricks' ),
			'type'     => 'checkbox',
			'inline'   => true,
			'required'    => [ 'autoplayscroll', '=', ['autoplay','autoscroll'] ],
		];

		$this->controls['pauseOnFocus'] = [
			'group'    => 'autoScroll',
			'label'    => esc_html__( 'Pause on focus', 'bricks' ),
			'type'     => 'checkbox',
			'inline'   => true,
			'required'    => [ 'autoplayscroll', '=', ['autoplay','autoscroll'] ],
		];

		$this->controls['intersectionSep'] = [
			'label' => 'Intersection',
			'tab' => 'content',
			'group'    => 'autoScroll',
			'type' => 'separator',
		];

		$this->controls['intersection'] = [
			'group'    => 'autoScroll',
			'label'    => esc_html__( 'Only when slider in view', 'bricks' ),
			'type'     => 'checkbox',
			'inline'   => true,
			'required'    => [ 'autoplayscroll', '=', ['autoplay','autoscroll'] ],
		];

		$this->controls['rootMargin'] = [
			'group'    => 'autoScroll',
			'label'    => esc_html__( 'Root margin', 'bricks' ),
			'type'     => 'number',
			'units'   => true,
			'placeholder' => '0px',
			'inline'   => true,
			'info' => esc_html__( "How far before the slider comes into view", 'bricks' ),
			'required'    => [ 'autoplayscroll', '=', ['autoplay','autoscroll'] ],
		];

		

		

		


		// ANIMATIONS
		
		$this->controls['innerAnimations'] = [
			'group'    => 'animations',
			'label'    => esc_html__( 'Retrigger inner animations', 'bricks' ),
			'type'     => 'checkbox',
			'inline'   => true,
			'required' => [ 'optionsType', '!=', 'custom' ],
		];

		$this->controls['animationTrigger'] = [
			'group'       => 'animations',
			'label'       => esc_html__( 'Animation trigger', 'bricks' ),
			'type'        => 'select',
			'options'     => [
				'is-active' => esc_html__( 'When slide active', 'bricks' ),
				'is-visible'  => esc_html__( 'When slide visible', 'bricks' ),
			],
			//'inline'      => true,
			'placeholder' => esc_html__( 'When slide visible', 'bricks' ),
			'required' => [ 'innerAnimations', '=', true ],
		];

		$this->controls['animateOnce'] = [
			'group'    => 'animations',
			'label'    => esc_html__( 'Only animate once', 'bricks' ),
			'type'     => 'checkbox',
			'inline'   => true,
			'required' => [ 'innerAnimations', '=', true ],
		];

		$this->controls['animationStagger'] = [
			'group'    => 'animations',
			'label'    => esc_html__( 'Auto stagger animation delays', 'bricks' ),
			'type'     => 'checkbox',
			'inline'   => true,
			'required' => [ 'innerAnimations', '=', true ],
		];

		$this->controls['animationDelayFirst'] = [
			'group'    => 'animations',
			'label'    => esc_html__( 'First element delay (ms)', 'bricks' ),
			'type'     => 'number',
			'inline'   => true,
			'required' => [ 'innerAnimations', '=', true ],
		];

		$this->controls['animationDelay'] = [
			'group'    => 'animations',
			'label'    => esc_html__( 'Delay interval (ms)', 'bricks' ),
			'type'     => 'number',
			'inline'   => true,
			'required' => [ 'innerAnimations', '=', true ],
		];


		// dynamic styles

		$this->controls['slideStyleSeparator'] = [
			'group'    => 'dynamicStyles',
			'description'    => esc_html__( 'Default state', 'bricks' ),
			'type'     => 'separator',
		];

		$this->controls['slideOpacity'] = [
			'group'       => 'dynamicStyles',
			'label'       => esc_html__( 'Opacity', 'bricks' ),
			'type'        => 'number',
			'css'      => [
				[
					'property' => 'opacity',
					'selector' => '.x-splide__track > .splide__list .splide__slide',
					'important' => 'true',
				],
			],
		];  

		$this->controls['slideBorder'] = [
			'group'       => 'dynamicStyles',
			'label'       => esc_html__( 'Border', 'bricks' ),
			'type'        => 'border',
			'css'      => [
				[
					'property' => 'border',
					'selector' => '.x-splide__track > .splide__list .splide__slide',
					'important' => 'true',
				],
			],
		];

		$this->controls['slideBackground'] = [
			'group'    => 'dynamicStyles',
			'label'    => esc_html__( 'Background', 'bricks' ),
			'type'     => 'color',
			'css'      => [
				[
					'property' => 'background',
					'selector' => '.x-splide__track > .splide__list .splide__slide',
				],
			],
		];

		$this->controls['slideTypography'] = [
			'tab'    => 'content',
			'group'    => 'dynamicStyles',
			'type'   => 'typography',
			'label'  => esc_html__( 'Typography', 'bricks' ),
			'css'    => [
				[
					'property' => 'font',
					'selector' => '.x-splide__track > .splide__list .splide__slide',
				],
			],
		];

		$this->controls['slideActiveStyleSeparator'] = [
			'group'    => 'dynamicStyles',
			'description'    => esc_html__( 'Is active', 'bricks' ),
			'type'     => 'separator',
		];

		$activeClass = '.is-active';
		$activePreview = '.x-slider_preview-active';
		

		$this->controls['slideOpacityActive'] = [
			'group'       => 'dynamicStyles',
			'label'       => esc_html__( 'Opacity', 'bricks' ),
			'type'        => 'number',
			'css'      => [
				[
					'property' => 'opacity',
					'selector' => '.x-splide__track > .splide__list .splide__slide' . $activeClass,
					'important' => 'true',
				],
			],
		];  

		$this->controls['slideBorderActive'] = [
			'group'       => 'dynamicStyles',
			'label'       => esc_html__( 'Border', 'bricks' ),
			'type'        => 'border',
			'css'      => [
				[
					'property' => 'border',
					'selector' => '.x-splide__track > .splide__list .splide__slide.is-active',
					'important' => 'true',
				],
			],
		];  

		$this->controls['slideBackgroundActive'] = [
			'group'    => 'dynamicStyles',
			'label'    => esc_html__( 'Background', 'bricks' ),
			'type'     => 'color',
			'css'      => [
				[
					'property' => 'background',
					'selector' => '.x-splide__track > .splide__list .splide__slide.is-active',
				],
			],
		];

		$this->controls['slideTypographyActive'] = [
			'tab'    => 'content',
			'group'    => 'dynamicStyles',
			'type'   => 'typography',
			'label'  => esc_html__( 'Typography', 'bricks' ),
			'css'    => [
				[
					'property' => 'font',
					'selector' => '.x-splide__track > .splide__list .splide__slide.is-active',
				],
			],
		];


		// accessibility

		$this->controls['tagsSep'] = [
			'group'    => 'accessibility',
			'label' => esc_html__( 'HTML tags', 'bricks' ),
			'type'     => 'separator',
			'required' => ['galleryMode', '!=', true]
		];

		$this->controls['listTag'] = [
			'group'    => 'accessibility',
			'label'       => esc_html__( 'List HTML tag', 'bricks' ),
			'type'        => 'text',
			'hasDynamicData' => false,
			'info' => esc_html__( "If changing the slides to li, set this to ul", 'bricks' ),
			'inline'      => true,
			'placeholder' => 'div',
			'required' => ['galleryMode', '!=', true]
		];

		$this->controls['ariaLabelSep'] = [
			'group'    => 'accessibility',
			'label' => esc_html__( 'Aria Labels', 'bricks' ),
			'description' => esc_html__( 'Here you can edit the aria-labels for individual parts of the slider (to use more content-based texts if needed)', 'bricks' ),
			'type'     => 'separator',
		];


		$this->controls['i18nNext'] = [
			'group'    => 'accessibility',
			'label'    => esc_html__( 'Aria-label of next arrow', 'bricks' ),
			'type'     => 'text',
			'hasDynamicData' => false,
			'inline'   => true,
			'placeholder' => 'Next slide',
			'required'    => [ 'arrows', '!=', ['false'] ],
		];

		$this->controls['i18nPrev'] = [
			'group'    => 'accessibility',
			'label'    => esc_html__( 'Aria-label of prev arrow', 'bricks' ),
			'type'     => 'text',
			'hasDynamicData' => false,
			'inline'   => true,
			'placeholder' => 'Previous slide',
			'required'    => [ 'arrows', '!=', ['false'] ],
		];

		$this->controls['i18nSlideX'] = [
			'group'    => 'accessibility',
			'label'    => esc_html__( 'Aria-label of each navigation slide', 'bricks' ),
			'type'     => 'text',
			'hasDynamicData' => false,
			'inline'   => true,
			'placeholder' => 'Go to slide %s'
		];

		$this->controls['i18nPageX'] = [
			'group'    => 'accessibility',
			'label'    => esc_html__( 'Aria-label of pagination', 'bricks' ),
			'type'     => 'text',
			'hasDynamicData' => false,
			'inline'   => true,
			'placeholder' => 'Go to page %s',
			'required'    => [ 'pagination', '!=', '' ],
		];

		$this->controls['i18nCarousel'] = [
			'group'    => 'accessibility',
			'label'    => esc_html__( 'Aria-roledescription of slider', 'bricks' ),
			'type'     => 'text',
			'hasDynamicData' => false,
			'inline'   => true,
			'placeholder' => 'Carousel'
		];

		$this->controls['i18nSelect'] = [
			'group'    => 'accessibility',
			'label'    => esc_html__( 'Aria-label of pagination', 'bricks' ),
			'type'     => 'text',
			'hasDynamicData' => false,
			'inline'   => true,
			'placeholder' => 'Select a slide to show',
			'required'    => [ 'pagination', '!=', '' ],
		];

		$this->controls['i18nSlide'] = [
			'group'    => 'accessibility',
			'label'    => esc_html__( 'Aria-roledescription of each slide', 'bricks' ),
			'type'     => 'text',
			'hasDynamicData' => false,
			'inline'   => true,
			'placeholder' => 'Slide'
		];

		$this->controls['i18nSlideLabel'] = [
			'group'    => 'accessibility',
			'label'    => esc_html__( 'Aria-label of each slide', 'bricks' ),
			'type'     => 'text',
			'hasDynamicData' => false,
			'inline'   => true,
			'placeholder' => '%s of %s'
		];

		// conditional slider

		$this->controls['maybeConditionalSlider'] = [
			'group'       => 'conditionalSlider',
			'label'       => esc_html__( 'Disable slider if not enough slides to fill slider viewport', 'bricks' ),
			'tooltip'  => [
				'content'  => "Will remove pagination, arrows and ensure slides aren't draggable",
				'position' => 'top-left',
			],
			'type'        => 'checkbox',
		];

		$this->controls['conditionalSep'] = [
			'group'    => 'conditionalSlider',
			'label' => esc_html__( 'Horizontal layout if slider not active', 'bricks' ),
			'type'     => 'separator',
			'required'    => [ 'maybeConditionalSlider', '!=', false ],
		];

		$conditionalSliderSelector = '&.x-slider.x-no-slider .splide__list';


		$this->controls['conditionaljustifyContent'] = [
			'tab'      => 'content',
			'label'    => esc_html__( 'Align horizontal', 'bricks' ),
			'group'		  => 'conditionalSlider',
			'tooltip'  => [
				'content'  => 'justify-content',
				'position' => 'top-left',
			],
			'type'     => 'align-items',
			'css'      => [
				[
					'property' => 'justify-content',
					'selector' => $conditionalSliderSelector,
				],
			],
			'required'    => [ 'maybeConditionalSlider', '!=', false ],
		];



		// gallery

		$this->controls['galleryInfo'] = [
			'group'    => 'gallery',
			'label' => esc_html__( 'Gallery mode', 'bricks' ),
			'description' => esc_html__( 'The purpose of gallery mode is to allow you to dynamically populate the list of slides, either by using a Pro Slider Gallery element or manually using a code element.', 'bricks' ),
			'type'     => 'separator',
		];


		$this->controls['galleryMode'] = [
			'group'    => 'gallery',
			'label'    => esc_html__( 'Enable', 'bricks' ),
			//'description'    => esc_html__( 'Enable if adding gallery slides (adding the Pro Slider Gallery or using a code element)', 'bricks' ),
			'type'     => 'checkbox',
			'inline'   => true,
		];

		/*

		$this->controls['slidePrevSeparator'] = [
			'group'    => 'dynamicStyles',
			'description' => esc_html__( 'Is previous', 'bricks' ),
			'type'     => 'separator',
		];

		$this->controls['slideNextSeparator'] = [
			'group'    => 'dynamicStyles',
			'description'    => esc_html__( 'Is next', 'bricks' ),
			'type'     => 'separator',
		];

		$this->controls['slideVisibleSeparator'] = [
			'group'    => 'dynamicStyles',
			'description' => esc_html__( 'Is visible', 'bricks' ),
			'type'     => 'separator',
		];

		*/


	}

	public function get_nestable_item() {
		return [
			'name'     => 'block',
			'label'    => esc_html__( 'Slide', 'bricks' ),
            'settings' => [
                
                '_hidden'         => [
                    '_cssClasses' => 'x-slider_slide splide__slide',
                ],
            ],
			'children' => [
				[
					'name'     => 'heading',
					'settings' => [
						'text' => esc_html__( 'Slide', 'bricks' ),
					],
				],
			],
		];
	}

	public function get_nestable_children() {
		$children = [];

		for ( $i = 0; $i < 2; $i++ ) {
			$item = $this->get_nestable_item();

			// Replace {item_index} with $index
			$item       = json_encode( $item );
			$item       = str_replace( '{item_index}', $i + 1, $item );
			$item       = json_decode( $item, true );
			$children[] = $item;
		}

		return $children;
	}

	/**
	 * Render arrows (use custom HTML solution as splideJS only accepts SVG path via 'arrowPath')
	 */
	public function render_arrows() {
		$prev_arrow = ! empty( $this->settings['prevArrow'] ) ? self::render_icon( $this->settings['prevArrow'] ) : false;
		$next_arrow = ! empty( $this->settings['nextArrow'] ) ? self::render_icon( $this->settings['nextArrow'] ) : false;
		$arrows = !isset( $this->settings['arrows'] ) || ( isset( $this->settings['arrows'] ) && 'true' === $this->settings['arrows'] );

		

		$nextAria = !empty( $this->settings['i18nNext'] ) ? esc_attr__( $this->settings['i18nNext'] ) : esc_attr__('Next slide');
		$prevAria = !empty( $this->settings['i18nPrev'] ) ? esc_attr__( $this->settings['i18nPrev'] ) : esc_attr__('Previous slide');

		if ( isset( $this->settings['arrows'] ) && 'false' === $this->settings['arrows'] ) {
			return;
		}

		$output = '<div class="splide__arrows x-splide__arrows">';

		if ( $prev_arrow ) {
			$output .= '<button class="splide__arrow splide__arrow--prev" aria-label="' . $prevAria . '" type="button">' . $prev_arrow . '</button>';
		} else {
			$output .= '<button class="splide__arrow splide__arrow--prev" aria-label="' . $prevAria . '" type="button"><svg class="x-splide__arrow-default" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M41.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l160 160c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L109.3 256 246.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-160 160z"/></svg></button>';
		}

		if ( $next_arrow ) {
			$output .= '<button class="splide__arrow splide__arrow--next" aria-label="' . $nextAria . '" type="button">' . $next_arrow . '</button>';
		} else {
			$output .= '<button class="splide__arrow splide__arrow--next" aria-label="' . $nextAria . '" type="button"><svg class="x-splide__arrow-default" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M278.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-160 160c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L210.7 256 73.4 118.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l160 160z"/></svg></button>';
		}

		$output .= '</div>';

		return $output;
	}


	/**
	 * Render individual slides
	 */
	public function render() {
		$this->set_attribute( '_root', 'class', 'splide' );

		$settings = $this->settings;

		$type = ! empty( $settings['type'] ) ? $settings['type'] : 'slide';

		$autoplayscroll = isset( $settings['autoplayscroll'] ) ? $settings['autoplayscroll'] : false; 

		$rootMargin = ! empty( $settings['rootMargin'] ) ? esc_attr( $settings['rootMargin'] ) : '0px';

		// Spacing requires a unit
		$gap = ! empty( $settings['gap'] ) ? $settings['gap'] : '0px';
		$fixedWidth = ! empty( $settings['fixedWidth'] ) ? $settings['fixedWidth'] : false;
		$fixedHeight =  ! empty( $settings['fixedHeight'] ) ? $settings['fixedHeight'] : false;

		$listTag = isset( $this->settings['listTag'] ) ? \Bricks\Helpers::sanitize_html_tag( $this->settings['listTag'], 'div' ) : 'div';

		// Add default unit
		if ( is_numeric( $gap ) ) {
			$gap = "{$gap}px";
		}
		if ( is_numeric( $fixedWidth ) ) {
			$fixedWidth = "{$fixedWidth}px";
		}
		if ( is_numeric( $fixedHeight ) ) {
			$fixedHeight = "{$fixedHeight}px";
		}

		// Arrows
		$arrows = !isset( $settings['arrows'] ) || ( isset( $settings['arrows'] ) && 'true' === $settings['arrows'] );
		
		if ( isset( $settings['autoplayscroll'] ) ) {
			if ( 'autoplay' === $settings['autoplayscroll'] ) {
				$autoplay = isset( $settings['autoplayPaused'] ) ? 'pause' : true;
			} else {
				$autoplay = false;
			}
		} else {
			$autoplay = false;
		}
		

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

		if ( BricksExtras\Helpers::maybePreview() ) {
			$drag = false;
		}

		$keyboard = ! empty( $settings['keyboard'] ) ? $settings['keyboard'] : 'focused';

		if ('false' === $keyboard) {
			$keyboard = false;
		}

		$media_query = \Bricks\Breakpoints::$is_mobile_first ? 'min' : 'max';

		$splide_options = [
			'rawConfig' => [
				'type'         => $type,
				'direction'    => ! empty( $settings['direction'] ) ? esc_attr( $settings['direction'] ) : ( is_rtl() ? 'rtl' : 'ltr' ),
				'keyboard'     => $keyboard,
				'gap'          => $gap,
				'start'        => ! empty( $settings['start'] ) ? intval( $settings['start'] ) : 0,
				'perPage'      => ! empty( $settings['perPage'] ) && 'fade' !== $type ? $settings['perPage'] : 1,
				'perMove'      => ! empty( $settings['perMove'] ) ? $settings['perMove'] : 1,
				'speed'        => ! empty( $settings['speed'] ) ? intval( $settings['speed'] ) : 400,
				'interval'     => ! empty( $settings['interval'] ) ? $settings['interval'] : 3000,
				'height'	   => ! empty( $settings['height'] ) ? esc_attr( $settings['height'] ) : 'auto',
				'autoHeight'   => isset( $settings['autoHeight'] ),
				'autoWidth'   => isset( $settings['autoWidth'] ),
				'pauseOnHover' => isset( $settings['pauseOnHover'] ),
				'pauseOnFocus' => isset( $settings['pauseOnFocus'] ),
				'arrows'       => $arrows,
				'pagination'   => isset( $settings['pagination'] ),
				'updateOnMove' => true,
				'fixedHeight' => ! empty( $settings['fixedHeight'] ) ? $fixedHeight : false,
				'fixedWidth' => ! empty( $settings['fixedWidth'] ) ? $fixedWidth : false,
				'drag' => $drag,
				'snap' => isset( $settings['snap'] ) ? 'true' === $settings['snap'] : false,
				'easing' => ! empty( $settings['easing'] ) ? $settings['easing'] : 'cubic-bezier(0.25, 1, 0.5, 1)',
				'mediaQuery'   => $media_query,
			],
			'hashNav' => isset( $settings['hashNavigation'] ),
			'conditional' => isset( $settings['maybeConditionalSlider'] )
		];

		if ( isset( $settings['rewind'] ) && $type !== 'loop' ) {

			$splide_options['rawConfig']['rewind'] = $settings['rewind'];

			if ( ! empty( $settings['rewindSpeed'] ) ) {
				$splide_options['rawConfig']['rewindSpeed'] = $settings['rewindSpeed'];
			}

			if ( isset( $settings['rewindByDrag'] ) ) {
				$splide_options['rawConfig']['rewindByDrag'] = $settings['rewindByDrag'];
			}
		}

		if ( isset( $settings['trimSpace'] ) ) {
			$splide_options['rawConfig']['trimSpace'] = 'enable' === $settings['trimSpace'];
		}

		if ( isset( $settings['innerAnimations'] ) ) {
			$splide_options['animationTrigger'] = isset( $settings['animationTrigger'] ) ? $settings['animationTrigger'] : 'is-visible';
			$splide_options['animationStagger'] = isset( $settings['animationStagger'] );
			$splide_options['animationDelay'] =  ! empty( $settings['animationDelay'] ) ? floatval( $settings['animationDelay'] ) : 0;
			$splide_options['animationDelayFirst'] =  ! empty( $settings['animationDelayFirst'] ) ? floatval( $settings['animationDelayFirst'] ) : 0;
			if ( isset( $settings['animateOnce'] ) ) {
				$splide_options['animateOnce'] = isset( $settings['animateOnce'] );
			}
		}

		if ( isset( $settings['dragMinThreshold'] ) ) {
			$splide_options['rawConfig']['dragMinThreshold'] = intval( $settings['dragMinThreshold'] );
		}

		if ( isset( $settings['flickPower'] ) ) {
			$splide_options['rawConfig']['flickPower'] = intval( $settings['flickPower'] );
		}

		if ( isset( $settings['flickMaxPages'] ) ) {
			$splide_options['rawConfig']['flickMaxPages'] = intval( $settings['flickMaxPages'] );
		}

		if (  ! empty( $settings['focus'] ) ) {
			$splide_options['rawConfig']['focus'] = 'center' === $settings['focus'] ? 'center' : intval( $settings['focus'] );
		}

		if (  isset( $settings['omitEnd'] ) ) {
			$splide_options['rawConfig']['omitEnd'] = true;
		}

		if ( isset( $settings['wheel'] ) ) {
			$splide_options['rawConfig']['wheel'] = true;
			$splide_options['rawConfig']['wheelSleep'] = ! empty( $settings['wheelSleep'] ) ? intval( $settings['wheelSleep'] ) : 700;
			$splide_options['rawConfig']['releaseWheel'] = isset( $settings['releaseWheel'] );
		}

		if ( isset( $settings['autoplayscroll'] ) ) {

			if ( 'autoscroll' === $settings['autoplayscroll'] ) {

				$splide_options['rawConfig']['autoScroll'] = [
					'speed' => isset( $settings['autoScrollSpeed'] ) ? floatval( $settings['autoScrollSpeed'] ) : 1,
					'rewind' => isset( $settings['rewind'] ) ? $settings['rewind'] : false,
					'pauseOnHover' => isset( $settings['pauseOnHover'] ),
					'pauseOnFocus' => isset( $settings['pauseOnFocus'] )
				];

			}
		}

		$splide_options['rawConfig']['i18n'] = [
			'carousel' => !empty( $settings['i18nCarousel'] ) ? esc_attr__( $settings['i18nCarousel'] ) : esc_attr__( 'carousel' )
		];

		if ( !empty( $settings['i18nPrev'] ) ) {
			$splide_options['rawConfig']['i18n'] += [ 'prev' => esc_attr__( $settings['i18nPrev'] ) ];
		}

		if ( !empty( $settings['i18nNext'] ) ) {
			$splide_options['rawConfig']['i18n'] += [ 'next' => esc_attr__( $settings['i18nNext'] ) ];
		}

		if ( !empty( $settings['i18nSlideX'] ) ) {
			$splide_options['rawConfig']['i18n'] += [ 'slideX' => esc_attr__( $settings['i18nSlideX'] ) ];
		}

		if ( !empty( $settings['i18nPageX'] ) ) {
			$splide_options['rawConfig']['i18n'] += [ 'pageX' => esc_attr__( $settings['i18nPageX'] ) ];
		}

		if ( !empty( $settings['i18nSelect'] ) ) {
			$splide_options['rawConfig']['i18n'] += [ 'select' => esc_attr__( $settings['i18nSelect'] ) ];
		}

		if ( !empty( $settings['i18nSlide'] ) ) {
			$splide_options['rawConfig']['i18n'] += [ 'slide' => esc_attr__( $settings['i18nSlide'] ) ];
		}

		if ( !empty( $settings['i18nSlideLabel'] ) ) {
			$splide_options['rawConfig']['i18n'] += [ 'slideLabel' => esc_attr__( $settings['i18nSlideLabel'] ) ];
		}

		if ( isset( $settings['autoplayscroll'] ) ) {
			if ( 'autoplay' === $settings['autoplayscroll'] && !isset( $settings['intersection'] ) ) {
				$splide_options['rawConfig']+= [ 'autoplay' => $autoplay,];
			}
		}

		

		if ( isset( $settings['intersection'] ) ) {
		
			if ( isset( $settings['autoplayscroll'] ) ) {

				if ( 'autoscroll' === $settings['autoplayscroll'] ) {
	
					$splide_options['rawConfig'] += ['autoScroll' => 'pause' ];
					$splide_options['rawConfig'] += ['intersection' => [
							'rootMargin' => $rootMargin,
							'inView' => [
								'autoScroll' => true,
							],
							'outView' => [
								'autoScroll' => false,
							],
						]
					];
				}

				if ( 'autoplay' === $settings['autoplayscroll'] ) {
					
					$splide_options['rawConfig']['autoplay'] = 'pause';
					$splide_options['rawConfig'] += ['intersection' => [
							'rootMargin' => $rootMargin,
							'inView' => [
								'autoplay' => true,
							],
							'outView' => [
								'autoplay' => false,
							],
						]
					];
					
				}
			}

		}

		$splide_options['rawConfig']['lazyLoad']  = 'nearby';
		$splide_options['rawConfig']['preloadPages']  = isset( $settings['preloadPages'] ) ? floatval( $settings['preloadPages'] ) : 1;

		if ( isset( $settings['lazyLoad'] ) ) {
			$splide_options['rawConfig']['lazyLoad'] = 'false' === $settings['lazyLoad'] ? false : $settings['lazyLoad'];
		} 

		if ( isset( $settings['edgeEffect'] ) ) {
			$splide_options['edgeEffect'] = 'true';
		}
		

		// STEP: Add settings per breakpoints to splide options
		$breakpoints = [];

		$breakpointOptions = array_keys( $splide_options['rawConfig'] );
		array_unshift( $breakpointOptions, "focus");

		/* remove options that are not supporting breakpoints */

		$valuesToRemove = array(
			'trimSpace',
			'updateOnMove',
			'autoHeight',
			'autoWidth',
			'mediaQuery',
			'lazyLoad',
			'start',
			'interval',
			'pauseOnHover',
			'pauseOnFocus',
			'rewind',
			'rewindByDrag',
			'wheel',
			'wheelSleep',
			'releaseWheel',
			'dragMinThreshold',
			'flickPower',
			'flickMaxPages',
			'hashNavigation',
			'autoplay',
			'preloadPages',
			'i18n'
		);

		foreach ($valuesToRemove as $value) {
			$key = array_search($value, $breakpointOptions);

			if ($key !== false) {
				unset($breakpointOptions[$key]);
			}
		}


		foreach ( \Bricks\Breakpoints::$breakpoints as $breakpoint ) {
			foreach ( $breakpointOptions as $option ) {
				
				$setting_key      = $breakpoint['key'] === 'desktop' ? $option : "$option:{$breakpoint['key']}";
				$breakpoint_width = $breakpoint['width'] ?? false;
				$setting_value    = $settings[ $setting_key ] ?? false;

				// Spacing requires a unit
				if ( $option === 'gap' ) {
					// Add default unit
					if ( is_numeric( $setting_value ) ) {
						$setting_value = "{$setting_value}px";
					}
				}

				if ( $option === 'fixedHeight' ) {
					// Add default unit
					if ( is_numeric( $setting_value ) ) {
						$setting_value = "{$setting_value}px";
					}
				}

				if ( $option === 'fixedWidth' ) {
					// Add default unit
					if ( is_numeric( $setting_value ) ) {
						$setting_value = "{$setting_value}px";
					}
				}

				/* for custom breakpoints if above base breakpoint */

				if ( $option === 'gap' && isset( $breakpoint['base'] ) && $setting_value ) {
					if ( is_numeric( $setting_value ) ) {
						$splide_options['rawConfig']['gap'] = "{$setting_value}px";
					} else {
						$splide_options['rawConfig']['gap'] = $setting_value;
					}
				}

				if ( $option === 'fixedWidth' && isset( $breakpoint['base'] ) && $setting_value ) {
					if ( is_numeric( $setting_value ) ) {
						$splide_options['rawConfig']['fixedWidth'] = "{$setting_value}px";
					} else {
						$splide_options['rawConfig']['fixedWidth'] = $setting_value;
					}
				}

				if ( $option === 'fixedHeight' && isset( $breakpoint['base'] ) && $setting_value ) {
					if ( is_numeric( $setting_value ) ) {
						$splide_options['rawConfig']['fixedHeight'] = "{$setting_value}px";
					} else {
						$splide_options['rawConfig']['fixedHeight'] = $setting_value;
					}
				}

				if ( $option === 'height' && isset( $breakpoint['base'] ) && $setting_value ) {
					if ( is_numeric( $setting_value ) ) {
						$splide_options['rawConfig']['height'] = "{$setting_value}px";
					} else {
						$splide_options['rawConfig']['height'] = $setting_value;
					}
				}

				if ( $option === 'perPage' && isset( $breakpoint['base'] ) && $setting_value ) {
					$splide_options['rawConfig']['perPage'] = intval( $setting_value );
				}

				if ( $option === 'perMove' && isset( $breakpoint['base'] ) && $setting_value ) {
					$splide_options['rawConfig']['perMove'] = intval( $setting_value );
				}

				if ( $option === 'direction' && isset( $breakpoint['base'] ) && $setting_value ) {
					$splide_options['rawConfig']['direction'] = $setting_value;
				}

				if ( $option === 'focus' && isset( $breakpoint['base'] ) && $setting_value ) {
					$splide_options['rawConfig']['focus'] = $setting_value;
				}


				if ( $breakpoint_width && $setting_value !== false ) {
					$breakpoints[ $breakpoint_width ][ $option ] = $setting_value;
				}
			}
		}

		if ( count( $breakpoints ) ) {
			$splide_options['rawConfig']['breakpoints'] = $breakpoints;
		}

		// Custom options (provided as valid JSON string)
		$options_type = ! empty( $settings['optionsType'] ) ? $settings['optionsType'] : 'default';

		if ( $options_type === 'custom' && ! empty( $settings['options'] ) ) {
			$splide_options = trim( stripslashes( $settings['options'] ) );
		}

		$loopIndex = false;

		if ( method_exists('\Bricks\Query','is_any_looping') ) {

			$query_id = \Bricks\Query::is_any_looping();
	
			if ( $query_id ) {
				
				if ( BricksExtras\Helpers::get_bricks_looping_parent_query_id_by_level(2) ) {
					$loopIndex = \Bricks\Query::get_query_for_element_id( \Bricks\Query::get_query_element_id( BricksExtras\Helpers::get_bricks_looping_parent_query_id_by_level(2) ) )->loop_index . '_' . \Bricks\Query::get_query_for_element_id( \Bricks\Query::get_query_element_id( BricksExtras\Helpers::get_bricks_looping_parent_query_id_by_level(1) ) )->loop_index . '_' . \Bricks\Query::get_loop_index();
				} else {
					if ( BricksExtras\Helpers::get_bricks_looping_parent_query_id_by_level(1) ) {
						$loopIndex = \Bricks\Query::get_query_for_element_id( \Bricks\Query::get_query_element_id( BricksExtras\Helpers::get_bricks_looping_parent_query_id_by_level(1) ) )->loop_index . '_' . \Bricks\Query::get_loop_index();
					} else {
						$loopIndex = \Bricks\Query::get_loop_index();
					}
				}			
	
				$this->set_attribute( '_root', 'data-x-id', $this->id . '_' . $loopIndex );
				
			} else {
				$this->set_attribute( '_root', 'data-x-id', $this->id );
			}
	
		} 

		if ( isset( $settings['isNavigation'] ) ) {
			if ( !isset( $settings['independentScrolling'] )  ) {
				$splide_options['rawConfig']['isNavigation'] = true;
			} else {
				$splide_options['isIndepententNav'] = 'true';
			}
			$splide_options['syncSelector'] = isset( $settings['syncSelector'] ) ? $settings['syncSelector'] : '.main-slider';

			if (\Bricks\Query::is_any_looping()) {
				$splide_options['syncSelector'] = isset( $settings['syncSelector'] ) ? $settings['syncSelector'] . '_' . $loopIndex : '.main-slider';
			}
		}

		if ( isset( $settings['maybeAdaptiveHeight'] ) ) {
			$splide_options['adaptiveHeight'] = 'true';
		}
		

		if ( is_array( $splide_options ) ) {
			$splide_options = wp_json_encode( $splide_options );
		}
	
		$this->set_attribute( '_root', 'data-x-slider', $splide_options );
		$this->set_attribute( '_root', 'class', 'x-slider' );
		$this->set_attribute( 'splide__track', 'class', 'splide__track' );
		$this->set_attribute( 'splide__track', 'class', 'x-splide__track' );
		$this->set_attribute( 'splide__list', 'class', 'splide__list' );

		

		$output = "<div {$this->render_attributes( '_root' )}>";

		$output .= $this->render_arrows();

		$output .= "<div {$this->render_attributes( 'splide__track' )}>";

		$output .= !isset( $settings['galleryMode'] ) ? "<" . $listTag ." {$this->render_attributes( 'splide__list' )}>" : "";

		$output .= \Bricks\Frontend::render_children( $this );
 
		$output .= !isset( $settings['galleryMode'] ) ? "</" . $listTag .">" : "";  // .splide__list

		$output .= "</div>"; // .splide__track

		$output .= '</div>'; // .root

		echo $output;

		if ( isset( $settings['intersection'] ) ) {
			wp_enqueue_script( 'x-splide-intersection', BRICKSEXTRAS_URL . 'components/assets/js/splide-intersection.js', '', '0.3.0', true );
		}
	
		if ( 'autoscroll' === $autoplayscroll ) {
			wp_enqueue_script( 'x-splide-autoscroll', BRICKSEXTRAS_URL . 'components/assets/js/' . \BricksExtras\Helpers::maybeMinifyScripts('splide-autoscroll') . '.js', '', '0.5.3', true );
		}

		if ( isset( $settings['hashNavigation'] ) ) {
			wp_enqueue_script( 'x-splide-hashnav', BRICKSEXTRAS_URL . 'components/assets/js/splide-hashnav.js', '', '0.3.0', true );
		}
		

	}

		
		
		public static function render_builder() {  

			$initProSlider = false;

			/* allow for init if prefered */
			$initProSlider = apply_filters( 'bricksextras/proslider/builderinit', $initProSlider );

			if ( !$initProSlider ) {
			
			?>

				<script type="text/x-template" id="tmpl-bricks-element-xproslider">
					<component
						class="splide x-slider"
						:class="[settings.direction, settings.edgeEffect && 'loop' === settings.type ? 'edgeEffect' : '']"
					>

					<div 
						class="x-slider_builder"
					>

						<div v-show="'custom' !== settings.arrows && 'false' !== settings.arrows" class="splide__arrows splide__arrows_builder x-splide__arrows splide__arrows--ltr">
							<button class="splide__arrow splide__arrow--prev" type="button" aria-label="Previous slide" aria-controls="brxe-doiubd-track"  disabled="">
								<icon-svg v-if="settings.prevArrow"
									:iconSettings="settings.prevArrow"
								/>
								<svg v-else class="x-splide__arrow-default" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M41.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l160 160c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L109.3 256 246.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-160 160z"/
								></svg>
							</button>
							<button class="splide__arrow splide__arrow--next" type="button" aria-label="Next slide" aria-controls="brxe-doiubd-track">
								<icon-svg v-if="settings.nextArrow"
									:iconSettings="settings.nextArrow"
								/>
								
								<svg v-else class="x-splide__arrow-default" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M278.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-160 160c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L210.7 256 73.4 118.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l160 160z"/
								></svg>
							</button>
						</div>
							<div 
								class="splide__track splide__track_builder x-splide__track"
								:class="[('fade' === settings.type ? 'splide__track--fade' : ''), (settings.autoWidth ? 'x-slider_autoWidth' : '')]"
							>
							<div v-if="!settings.galleryMode"
								class="splide__list splide__list_builder"
							><bricks-element-children :element="element" />
							</div>

							<bricks-element-children 
								v-else
								:element="element" 
							/>

							</div>

							<!-- Add the pagination element -->
							<ul v-show="settings.pagination" class="splide__pagination splide__pagination_builder" role="tablist" aria-label="Select a slide to show">
								<li role="presentation"><button class="splide__pagination__page is-active" type="button" role="tab" aria-label="Go to slide 1" aria-selected="true"></button></li>
								<li role="presentation"><button class="splide__pagination__page" type="button" role="tab" aria-label="Go to slide 2" tabindex="-1"></button></li>
								<li role="presentation"><button class="splide__pagination__page" type="button" role="tab" aria-label="Go to slide 3" tabindex="-1"></button></li>
							</ul>


						</div>
					</component>	
				</script>

		<?php 
		
		}
	
	}

}
