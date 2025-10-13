<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class X_Tabs extends \Bricks\Element {

  // Element properties
  public $category     = 'extras';
	public $name         = 'xtabs';
	public $icon         = 'ti-layout-tab';
	public $css_selector = '';
	public $scripts      = ['xTabs'];
	public $nestable = true;

	public function get_label() {
		return esc_html__( 'Pro Tabs', 'bricks' );
	}

	public function get_keywords() {
		return [ 'nestable' ];
	}

  public function enqueue_scripts() {

	if (! \BricksExtras\Helpers::elementCSSAdded($this->name) ) {
		wp_enqueue_style( 'x-tabs', BRICKSEXTRAS_URL . 'components/assets/css/tabs.css', [], '' );
	}
    wp_enqueue_script( 'x-frontend', BRICKSEXTRAS_URL . 'components/assets/js/' . \BricksExtras\Helpers::maybeMinifyScripts('frontend') . '.js', '', '1.0.0', true );
    wp_enqueue_script( 'x-tabs', BRICKSEXTRAS_URL . 'components/assets/js/' . \BricksExtras\Helpers::maybeMinifyScripts('tabs') . '.js', ['x-frontend'], '1.1.1', true );
  }

	public function set_control_groups() {
    
		$this->control_groups['tabsList'] = [
			'title' => esc_html__( 'Tab list', 'bricks' ),
		];

		$this->control_groups['content'] = [
			'title' => esc_html__( 'Tab Content', 'bricks' ),
		];

    	$this->control_groups['behaviour'] = [
			'title' => esc_html__( 'Behaviour', 'bricks' ),
		];

		$this->control_groups['animation'] = [
			'title' => esc_html__( 'Animations', 'bricks' ),
		];

		$this->control_groups['mobileAccordion'] = [
			'title' => esc_html__( 'Mobile Accordion', 'bricks' ),
		];

		$this->control_groups['config'] = [
			'title' => esc_html__( 'Hash / Scroll-to', 'bricks' ),
		];
	}

	public function set_controls() {
		
		/*$this->controls['direction'] = [
			'label'    => esc_html__( 'Direction', 'bricks' ),
			'tooltip'  => [
				'content'  => 'flex-direction',
				'position' => 'top-left',
			],
			'type'     => 'direction',
			'css'      => [
				[
					'selector' => '.x-tabs_list',
					'property' => 'flex-direction',
				],
			],
			'inline'   => true,
			'rerender' => true,
		]; */

		// tabs list

		$this->controls['tabWidth'] = [
			'group'       => 'tabsList',
			'label'       => esc_html__( 'Tab width', 'bricks' ),
			'type'        => 'number',
			'units'       => true,
			'css'         => [
				[
					'selector' => '.x-tabs_tab',
					'property' => 'width',
				],
			],
		];

		$this->controls['tabEqualWidth'] = [
			'group' => 'tabsList',
			'tab'   => 'content',
			//'default' => true,
			'label' => esc_html__( 'Flex grow tabs', 'bricks' ),
			'type'  => 'checkbox',
			'css'         => [
				[
					'selector' => '.x-tabs_tab',
					'property' => 'flex-grow',
					'value' => '1'
				],
			],
		  ];

		$this->controls['tabsGap'] = [
			'group' => 'tabsList',
			'label' => esc_html__( 'Gap', 'bricks' ),
			'type'        => 'number',
			'units'       => true,
			'css'   => [
				[
					'property' => 'gap',
					'selector' => '.x-tabs_list',
				],
			],
		];


		$this->controls['tabWap'] = [
			'group' => 'tabsList',
			'tab'   => 'content',
			'label' => esc_html__( 'Tab Wrap / Scroll', 'bricks' ),
			'inline' => true,
			'type'  => 'select',
			'options'         => [
					'nowrap' => 'No wrap',
					'wrap' => 'wrap',
					'scroll' => 'horizontal scroll'
			],
		  ];

		$this->controls['tabPadding'] = [
			'group'   => 'tabsList',
			'label'   => esc_html__( 'Padding', 'bricks' ),
			'type' => 'dimensions',
			'css'     => [
				[
					'property' => 'padding',
					'selector' => '.x-tabs_tab',
				],
			],
		];

		$this->controls['tabAlign'] = [
			'tab'      => 'style',
			'group'    => 'tabsList',
			'label'    => esc_html__( 'Align tab', 'bricks' ),
			'type'     => 'align-items',
			'css'      => [
				[
					'selector' => '.x-tabs_tab',
					'property' => 'align-items',
				],
			],
		];

		$this->controls['tabsAlign'] = [
			'tab'      => 'style',
			'group'    => 'tabsList',
			'label'    => esc_html__( 'Align tabs list', 'bricks' ),
			'type'     => 'justify-content',
			'css'      => [
				[
					'selector' => '.x-tabs_list',
					'property' => 'justify-content',
				],
			],
		];

		$this->controls['tabStyledSeparator'] = [
			'group'      => 'tabsList',
			'type'       => 'separator',
		];

		$this->controls['tabBackgroundColor'] = [
			'group' => 'tabsList',
			'label' => esc_html__( 'Background', 'bricks' ),
			'type'  => 'color',
			'css'   => [
				[
					'property' => 'background-color',
					'selector' => '.x-tabs_tab',
				],
				[
					'property' => 'background-color',
					'selector' => '.x-tabs_toggle',
				],
				
			],
		];

		$this->controls['tabBorder'] = [
			'group' => 'tabsList',
			'label' => esc_html__( 'Border', 'bricks' ),
			'type'  => 'border',
			'css'   => [
				[
					'property' => 'border',
					'selector' => '.x-tabs_tab',
				],
				[
					'property' => 'border',
					'selector' => '.x-tabs_toggle',
				],
			],
		];

		$this->controls['tabTypography'] = [
			'group' => 'tabsList',
			'label' => esc_html__( 'Typography', 'bricks' ),
			'type'  => 'typography',
			'css'   => [
				[
					'property' => 'font',
					'selector' => '.x-tabs_tab',
				],
				[
					'property' => 'font',
					'selector' => '.x-tabs_toggle',
				],
			],
		];

		/* active tab */

		$this->controls['tabActiveSeparator'] = [
			'group'      => 'tabsList',
			'label'      => esc_html__( 'Active tab', 'bricks' ),
			'type'       => 'separator',
		];

		$this->controls['tabActiveBackgroundColor'] = [
			'group'   => 'tabsList',
			'label'   => esc_html__( 'Background color', 'bricks' ),
			'type'    => 'color',
			'css'     => [
				[
					'property' => 'background-color',
					'selector' => '&:not([data-x-tabs*=animatedTabs]) .x-tabs_tab.x-tabs_tab-selected',
				],
				[
					'property' => 'background-color',
					'selector' => '.x-tabs_toggle[aria-expanded=true]',
				],
				[
					'property' => 'background-color',
					'selector' => '.x-tabs_slider',
				],
				
				
			],
			'default' => [
				'hex' => '#dddedf',
			],
		];

		$this->controls['tabActiveBorder'] = [
			'group' => 'tabsList',
			'label' => esc_html__( 'Border', 'bricks' ),
			'type'  => 'border',
			'css'   => [
				[
					'property' => 'border',
					'selector' => '.x-tabs_tab.x-tabs_tab-selected',
				],
				[
					'property' => 'border',
					'selector' => '.x-tabs_toggle[aria-expanded=true]',
				],
			],
		];

		$this->controls['tabActiveTypography'] = [
			'group' => 'tabsList',
			'label' => esc_html__( 'Typography', 'bricks' ),
			'type'  => 'typography',
			'css'   => [
				[
					'property' => 'font',
					'selector' => '.x-tabs_tab.x-tabs_tab-selected',
				],
				[
					'property' => 'font',
					'selector' => '.x-tabs_toggle[aria-expanded=true]',
				],
			],
		];

		// tab content

		$this->controls['contentPadding'] = [
			'group'   => 'content',
			'label'   => esc_html__( 'Padding', 'bricks' ),
			'type' => 'dimensions',
			'css'     => [
				[
					'property' => 'padding',
					'selector' => '.x-tabs_panel-content',
				],
			],
		];

		$this->controls['contentTypography'] = [
			'group' => 'content',
			'label' => esc_html__( 'Typography', 'bricks' ),
			'type'  => 'typography',
			'css'   => [
				[
					'property' => 'font',
					'selector' => '.x-tabs_panel',
				],
			],
		];

		$this->controls['contentBackgroundColor'] = [
			'group' => 'content',
			'label' => esc_html__( 'Background color', 'bricks' ),
			'type'  => 'color',
			'css'   => [
				[
					'property' => 'background-color',
					'selector' => '&:not([data-x-tabs*=adaptiveHeight]) .x-tabs_panel',
				],
				[
					'property' => 'background-color',
					'selector' => '&[data-x-tabs*=adaptiveHeight].x-tabs-mobile .x-tabs_panel',
				],
				[
					'property' => 'background-color',
					'selector' => '&[data-x-tabs*=adaptiveHeight]:not(.x-tabs-mobile) .x-tabs_content',
				],
				
			],
		];

		$this->controls['contentBorder'] = [
			'group'   => 'content',
			'label'   => esc_html__( 'Border', 'bricks' ),
			'type'    => 'border',
			'css'     => [
				/*[
					'property' => 'border',
					'selector' => '.x-tabs_panel',
				],*/
				[
					'property' => 'border',
					'selector' => '&:not([data-x-tabs*=adaptiveHeight]) .x-tabs_panel',
				],
				[
					'property' => 'border',
					'selector' => '&[data-x-tabs*=adaptiveHeight].x-tabs-mobile .x-tabs_panel',
				],
				[
					'property' => 'border',
					'selector' => '&[data-x-tabs*=adaptiveHeight]:not(.x-tabs-mobile) .x-tabs_content',
				],
			],
		];


		/* behaviour */

		$this->controls['hoverSelect'] = [
			'group' => 'behaviour',
			'tab'   => 'content',
			'rerender' => false,
			'label' => esc_html__( 'Allow tab select on hover', 'bricks' ),
			'type'  => 'checkbox',
		];

		$this->controls['tabUnselect'] = [
			'group' => 'behaviour',
			'tab'   => 'content',
			'rerender' => false,
			'label' => esc_html__( 'Allow tabbing to unselected tabs', 'bricks' ),
			'type'  => 'checkbox',
		];

		

		$this->controls['adaptiveHeight'] = [
			'group' => 'behaviour',
			'tab'   => 'content',
			'label' => esc_html__( 'Adaptive height', 'bricks' ),
			'type'  => 'checkbox',
		];

		$this->controls['adaptiveHeightDuration'] = [
			'group' => 'behaviour',
			'tab'   => 'content',
			'type' => 'number',
			'units' => true,
			'label' => esc_html__( 'Adaptive Height Duration', 'bricks' ),
			'css'   => [
				[
					'property' => '--x-tabs-adaptive-height-duration',
					'selector' => '',
				],
			],
			'placeholder' => '300ms'
			
		];

		$this->controls['tabOrientation'] = [
			'group' => 'behaviour',
			'tab'   => 'content',
			'rerender' => false,
			'inline' => true,
			'label' => esc_html__( 'Tab orientation', 'bricks' ),
			'description' => esc_html__( "'Vertical' will switch the keyboard nav to use Up/Down rather than Left/Right. Use only if you're aligning your tabs vertically", 'bricks' ),
			'type'  => 'select',
			'options' => [
				'horizontal' => esc_html__( 'Horizontal', 'bricks' ),
				'vertical' => esc_html__( 'Vertical', 'bricks' ),
			],
			'placeholder' => esc_html__( 'Horizontal', 'bricks' ),
		];
		

		/* animation */

		

		$this->controls['animateTabSep'] = [
			'group'      => 'animation',
			'label'      => esc_html__( 'Animate Tabs', 'bricks' ),
			'type'       => 'separator',
		];

		$this->controls['animatedTabs'] = [
			'group' => 'animation',
			'tab'   => 'content',
			'rerender' => true,
			'label' => esc_html__( 'Animate active tab', 'bricks' ),
			'type'  => 'checkbox',
		];

		$this->controls['animationSlideDuration'] = [
			'group' => 'animation',
			'tab'   => 'content',
			'type' => 'number',
			'units' => true,
			'label' => esc_html__( 'Animation Duration', 'bricks' ),
			'css'   => [
				[
					'property' => '--x-tabs-slider-duration',
					'selector' => '',
				],
			],
			'placeholder' => '300ms'
		];

		$this->controls['animationSlideBackgroundColor'] = [
			'group' => 'animation',
			'label' => esc_html__( 'Background color', 'bricks' ),
			'type'  => 'color',
			'css'   => [
				[
					'property' => 'background-color',
					'selector' => '.x-tabs_list .x-tabs_slider',
				],
				
			],
		];

		$this->controls['animationSlideBorder'] = [
			'group' => 'animation',
			'label' => esc_html__( 'Border', 'bricks' ),
			'type'  => 'border',
			'css'   => [
				[
					'property' => 'border',
					'selector' => '.x-tabs_list .x-tabs_slider',
				],
			],
		];

		

		$this->controls['animateTabContentSep'] = [
			'group'      => 'animation',
			'label'      => esc_html__( 'Animate Tab Content', 'bricks' ),
			'type'       => 'separator',
			'description' => esc_html__( '(Note: for more control Bricks interactions can be used to add animations to any individual elements inside of the tab content panels)', 'bricks' ),
		];

		$this->controls['animateTabContent'] = [
			'group' => 'animation',
			'tab'   => 'content',
			'label' => esc_html__( 'Tab content animation', 'bricks' ),
			'inline' => true,
			'type'  => 'select',
			'options'  => [
					'fadeinnonne_x' => esc_html__( 'None', 'bricks' ),
					'fadein_x' => esc_html__( 'Fade In', 'bricks' ),
					'fadeinup_x' => esc_html__( 'Fade In Up', 'bricks' ),
					'fadeindown_x' => esc_html__( 'Fade In Down', 'bricks' ),
					'fadeinleft_x' => esc_html__( 'Fade In Left', 'bricks' ),
					'fadeinright_x' => esc_html__( 'Fade In Right', 'bricks' ),
					
			],
			'placeholder' => esc_html__( 'None', 'bricks' ),
		  ];

		  $this->controls['animationDuration'] = [
			'group' => 'animation',
			'tab'   => 'content',
			'rerender' => false,
			'type' => 'number',
			'units' => true,
			'label' => esc_html__( 'Animation Duration', 'bricks' ),
			'css'   => [
				[
					'property' => '--x-tabs-transition-duration',
					'selector' => '',
				],
			],
			'placeholder' => '300ms'
			
		];

		$this->controls['animationDistance'] = [
			'group' => 'animation',
			'tab'   => 'content',
			'type' => 'number',
			'units' => true,
			'label' => esc_html__( 'Animation Distance', 'bricks' ),
			'css'   => [
				[
					'property' => '--x-tabs-transition-distance',
					'selector' => '',
				],
			],
			'placeholder' => '10px'
		];



		/* mobile accordion */

		$breakpointOptions = [];

		foreach ( \Bricks\Breakpoints::$breakpoints as $breakpoint ) {
			$breakpointOptions[$breakpoint['width']] = $breakpoint['label'] . ' ( <= ' . ( intval($breakpoint['width']) ) . 'px )';
		}

		$breakpointOptions['none'] = 'None';

		$thirdBreakpoint = array_keys($breakpointOptions)[2];

		$this->controls['accordionBreakpoint'] = [
			'group' => 'mobileAccordion',
			'type' => 'select',
			'label' => esc_html__( 'Switch to accordion below..', 'bricks' ),
			'options' => $breakpointOptions,
			'default' => $thirdBreakpoint
		];


		/*$this->controls['closeSibling'] = [
			'group' => 'mobileAccordion',
			'label' => esc_html__( 'Disable closing sibling items', 'bricks' ),
			'type'  => 'checkbox',
		];*/

		$this->controls['expandFirstItem'] = [
			'group' => 'mobileAccordion',
			'label' => esc_html__( 'Expand first item', 'bricks' ),
			'type'  => 'checkbox',
		];

		$this->controls['disableBricksAnimations'] = [
			'group' => 'mobileAccordion',
			'label' => esc_html__( 'Disable Bricks animations in content', 'bricks' ),
			'type'  => 'checkbox',
		];

		$this->controls['duration'] = [
			'group' => 'mobileAccordion',
			'label'       => esc_html__( 'Transition Duration', 'bricks' ) . ' (ms)',
			'type'        => 'number',
			'placeholder' => 150,
		];

		$this->controls['accordionItemsGap'] = [
			'group' => 'mobileAccordion',
			'label' => esc_html__( 'Gap', 'bricks' ),
			'type'        => 'number',
			'units'       => true,
			'css'   => [
				[
					'property' => 'gap',
					'selector' => '.x-tabs_content-accordion',
				],
			],
		];

		
		

		$this->controls['accordionToggleSep'] = [
			'group'      => 'mobileAccordion',
			'label'      => esc_html__( 'Accordion Toggle', 'bricks' ),
			'type'       => 'separator',
		];


		$this->controls['accordionToggleBackgroundColor'] = [
			'group' => 'mobileAccordion',
			'label' => esc_html__( 'Background color', 'bricks' ),
			'type'  => 'color',
			'css'   => [
				[
					'property' => 'background-color',
					'selector' => '.x-tabs_content-item .x-tabs_toggle',
				],
				
			],
		];

		$this->controls['accordionToggleBorder'] = [
			'group' => 'mobileAccordion',
			'label' => esc_html__( 'Border', 'bricks' ),
			'type'  => 'border',
			'css'   => [
				[
					'property' => 'border',
					'selector' => '.x-tabs_content-item .x-tabs_toggle',
				],
			],
		];

		$this->controls['accordionToggleTypography'] = [
			'group' => 'mobileAccordion',
			'label' => esc_html__( 'Typography', 'bricks' ),
			'type'  => 'typography',
			'css'   => [
				[
					'property' => 'font',
					'selector' => '.x-tabs_content-item .x-tabs_toggle',
				],
			],
		];

		$this->controls['iconTransformSep'] = [
			'group'      => 'mobileAccordion',
			'type'       => 'separator',
		];

		$this->controls['iconTransform'] = [
			'tab'         => 'style',
			'group'       => 'mobileAccordion',
			'type'        => 'transform',
			'label'       => esc_html__( 'Icon Transform', 'bricks' ),
			'css'         => [
				[
          			'selector' => '.x-tabs_toggle-icon',
					'property' => 'transform',
				],
			],
			'default' => [
				'rotateX' => '0deg'
			],
			'inline'      => true,
			'small'       => true,
		];

		$this->controls['iconTypography'] = [
			'group' => 'mobileAccordion',
			'label' => esc_html__( 'Icon Typography', 'bricks' ),
			'type'  => 'typography',
			'css'   => [
				[
					'property' => 'font',
					'selector' => '.x-tabs_toggle-icon',
				],
			],
		];

		$this->controls['accordionTogglePaddingSep'] = [
			'group'      => 'mobileAccordion',
			'type'       => 'separator',
		];


		$this->controls['accordionTogglePadding'] = [
			'group'   => 'mobileAccordion',
			'label'   => esc_html__( 'Padding', 'bricks' ),
			'type' => 'dimensions',
			'css'     => [
				[
					'property' => 'padding',
					'selector' => '.x-tabs_toggle',
				],
			],
		];

		$this->controls['accordionToggleAlign'] = [
			'tab'      => 'style',
			'group'    => 'mobileAccordion',
			'label'    => esc_html__( 'Align-items', 'bricks' ),
			'type'     => 'align-items',
			'css'      => [
				[
					'selector' => '.x-tabs_toggle',
					'property' => 'align-items',
				],
			],
		];

		$this->controls['accordionToggleJustify'] = [
			'tab'      => 'style',
			'group'    => 'mobileAccordion',
			'label'    => esc_html__( 'Justify-content', 'bricks' ),
			'type'     => 'justify-content',
			'css'      => [
				[
					'selector' => '.x-tabs_toggle',
					'property' => 'justify-content',
				],
			],
		];

		$this->controls['accordionToggleActiveSep'] = [
			'group'      => 'mobileAccordion',
			'label'      => esc_html__( 'Accordion Toggle (active)', 'bricks' ),
			'type'       => 'separator',
		];


		$this->controls['accordionToggleActiveBackgroundColor'] = [
			'group'   => 'mobileAccordion',
			'label'   => esc_html__( 'Background color', 'bricks' ),
			'type'    => 'color',
			'css'     => [
				[
					'property' => 'background-color',
					'selector' => '.x-tabs_content-item .x-tabs_toggle[aria-expanded=true]',
				],
			],
		];

		$this->controls['accordionToggleActiveBorder'] = [
			'group' => 'mobileAccordion',
			'label' => esc_html__( 'Border', 'bricks' ),
			'type'  => 'border',
			'css'   => [
				[
					'property' => 'border',
					'selector' => '.x-tabs_content-item .x-tabs_toggle[aria-expanded=true]',
				],
			],
		];

		$this->controls['accordionToggleActiveTypography'] = [
			'group' => 'mobileAccordion',
			'label' => esc_html__( 'Typography', 'bricks' ),
			'type'  => 'typography',
			'css'   => [
				[
					'property' => 'font',
					'selector' => '.x-tabs_content-item .x-tabs_toggle[aria-expanded=true]',
				],
			],
		];

		$this->controls['iconTransformActiveSep'] = [
			'group'      => 'mobileAccordion',
			'type'       => 'separator',
		];

		$this->controls['iconTransformActive'] = [
			'tab'         => 'style',
			'group'       => 'mobileAccordion',
			'type'        => 'transform',
			'label'       => esc_html__( 'Icon Transform', 'bricks' ),
			'css'         => [
				[
					'selector' => '.x-tabs_toggle[aria-expanded=true] .x-tabs_toggle-icon',
							'property' => 'transform',
						],
					],
			'default' => [
				'rotateX' => '180deg'
			],
			'inline'      => true,
			'small'       => true,
			];

		$this->controls['iconTypographyActive'] = [
			'group' => 'mobileAccordion',
			'label' => esc_html__( 'Icon Typography', 'bricks' ),
			'type'  => 'typography',
			'css'   => [
				[
					'property' => 'font',
					'selector' => '.x-accordion_header[aria-expanded=true] .x-tabs_toggle-icon',
				],
			],
		];


		
		/* hash */

		$this->controls['hashLink'] = [
			'label' => esc_html__( 'Open/Scrollto if hashlink in URL', 'bricks' ),
			'type'  => 'checkbox',
			'group' => 'config',
			'rerender' => false,
			'description' => esc_html__( 'If URL includes # to the "Tab Panel" ID on page load (or a hashlink links to it), the page will scroll and that item opened.', 'bricks' ),
			'required' => ['maybeURLparam', '!=', true]
		];

		$this->controls['scrollOffset'] = [
			'tab'         => 'content',
			'label'       => esc_html__( 'Scroll offset (px)', 'bricks' ),
			'type'        => 'number',
			'units'       => false,
			'placeholder' => '0',
			'group' => 'config',
			'rerender' => false,
			'required' => [
				['hashLink', '=', true],
				['maybeURLparam', '!=', true]
			]
		];

		$this->controls['maybeURLparam'] = [
			'label' => esc_html__( 'Open via URL parameter', 'bricks' ),
			'type'  => 'checkbox',
			'group' => 'config',
			'rerender' => false,
			'description' => esc_html__( "If URL includes ?key=value where value is the tab panel ID, that item will be opened (useful if you wish to control the scroll seperately. Example  ../?tab=faq-2#faq-section to open tab faq-2, and allow Bricks to scroll to the section.", 'bricks' ),
			'required' => ['hashLink', '!=', true]
		];

		$this->controls['URLParamKey'] = [
			'tab'         => 'content',
			'label'       => esc_html__( 'Key', 'bricks' ),
			'type'        => 'text',
			'placeholder' => 'tab',
			'inline' => true,
			'group' => 'config',
			'rerender' => false,
			'required' => [
				['maybeURLparam', '=', true],
				['hashLink', '!=', true]
			]
		];

	}

			
	
	public function get_nestable_children() {
		/**
		 */
		return [
			// Title
			[
				'name'     => 'block',
				'label'    => esc_html__( 'Tabs list', 'bricks' ),
				'deletable' => false,
				'cloneable' => false,	
				'settings' => [
					'_direction' => 'row',
					'_hidden'    => [
						'_cssClasses' => 'x-tabs_list',
					],
					'tag' => 'ul',
					'_attributes' => [
						[
						'name' => 'role',
						'value' => 'tablist',
						]
					]
				],
				'children' => [
					[
						'name'     => 'div',
            			'label'    => 'Tab Item',
						'settings' => [
              				'tag' => 'li',
							'_hidden' => [
								'_cssClasses' => 'x-tabs_tab',
							],
							'_attributes' => [
								[
								'name' => 'role',
								'value' => 'tab',
								],
							]
						],
            		'children' => [
							[
								'label'    => 'Title',
								'name'     => 'text-basic',
								'settings' => [
								'tag' => 'span',
								'text' => esc_html__( 'Item', 'bricks' ) . ' 1',
								],
							],
						],
						
					],
					[
						'name'     => 'div',
            			'label'    => 'Tab Item',
						'settings' => [
              				'tag' => 'li',
							'_hidden' => [
								'_cssClasses' => 'x-tabs_tab',
							],
							'_attributes' => [
								[
								'name' => 'role',
								'value' => 'tab',
								],
							]
						],
            		'children' => [
							[
								'label'    => 'Title',
								'name'     => 'text-basic',
								'settings' => [
								'tag' => 'span',
								'text' => esc_html__( 'Item', 'bricks' ) . ' 2',
								],
							],
						],
						
					],
				],
			],

			// Content
			[
				'name'     => 'block',
				'label'    => esc_html__( 'Tabs Content', 'bricks' ),
				'deletable' => false,
				'cloneable' => false,	
				'settings' => [
					'tag' => 'div',
					'_hidden' => [
						'_cssClasses' => 'x-tabs_content',
					],
				],
        		'children' => [
					
          [
            'name'     => 'block',
			'label'    => esc_html__( 'Tab Content Item', 'bricks' ),
			'settings' => [
				'tag' => 'div',
				'_hidden' => [
					'_cssClasses' => 'x-tabs_content-item',
				],
			],
            'children' => [
              [
                'name'     => 'block',
                'label'    => esc_html__( 'Accordion Toggle', 'bricks' ),
				//'deletable' => false,
				'cloneable' => false,	
                'settings' => [
                  '_hidden' => [
                    '_cssClasses' => 'x-tabs_toggle',
                  ],
                  '_attributes' => [
                    [
                      'name' => 'role',
                      'value' => 'button',
                    ],
					[
						'name' => 'tabindex',
						'value' => '0',
					  ],
                  ]
                ],
                'children' => [
                  [
                    'name'     => 'text-basic',
                    'settings' => [
                      'text' => 'Item 1',
                      'tag'  => 'span',
                    ],
                  ],
                  [
					'name'     => 'icon',
					'label'    => esc_html__( 'Toggle icon', 'bricks' ),
					'settings' => [
						'_hidden' => [
							'_cssClasses' => 'x-tabs_toggle-icon',
						],
						'icon'     => [
							'icon'    => 'ion-ios-arrow-down',
							'library' => 'ionicons',
						],
						'iconSize' => '1em',
					],
				],
                ],
              ],
              [
                'name'     => 'block',
                'label'    => esc_html__( 'Tab Panel', 'bricks' ),
				'deletable' => false,
				'cloneable' => false,	
                'settings' => [
                  '_hidden' => [
                    '_cssClasses' => 'x-tabs_panel',
                  ],
                  '_attributes' => [
                    [
                      'name' => 'role',
                      'value' => 'tabpanel',
                    ],
                  ]
                ],
                'children' => [
						[
							'name'     => 'block',
							'label'    => esc_html__( 'Tab Content', 'bricks' ),
							'deletable' => false,
							'cloneable' => false,	
							'settings' => [
							'_hidden' => [
								'_cssClasses' => 'x-tabs_panel-content',
							],
							],
							'children' => [
							[
								'name'     => 'heading',
								'settings' => [
								'text' => 'Item 1',
								'tag'  => 'h4',
								],
							],
							[
								'name'     => 'text',
								'settings' => [
								'text' => esc_html__( 'Tab content here', 'bricks' ),
								],
							],
							],
						],
					],
				],
            ],
          ],
		  [
            'name'     => 'block',
			'label'    => esc_html__( 'Tab Content Item', 'bricks' ),
			'settings' => [
				'tag' => 'div',
				'_hidden' => [
					'_cssClasses' => 'x-tabs_content-item',
				],
			],
            'children' => [
              [
                'name'     => 'block',
                'label'    => esc_html__( 'Accordion Toggle', 'bricks' ),
				//'deletable' => false,
				'cloneable' => false,	
                'settings' => [
                  '_hidden' => [
                    '_cssClasses' => 'x-tabs_toggle',
                  ],
                  '_attributes' => [
                    [
                      'name' => 'role',
                      'value' => 'button',
                    ],
					[
						'name' => 'tabindex',
						'value' => '0',
					  ],
                  ]
                ],
                'children' => [
                  [
                    'name'     => 'text-basic',
                    'settings' => [
                      'text' => 'Item 2',
                      'tag'  => 'span',
                    ],
                  ],
                  [
					'name'     => 'icon',
					'label'    => esc_html__( 'Toggle icon', 'bricks' ),
					'settings' => [
						'_hidden' => [
							'_cssClasses' => 'x-tabs_toggle-icon',
						],
						'icon'     => [
							'icon'    => 'ion-ios-arrow-down',
							'library' => 'ionicons',
						],
						'iconSize' => '1em',
					],
				],
                ],
              ],
              [
                'name'     => 'block',
                'label'    => esc_html__( 'Tab Panel', 'bricks' ),
				'deletable' => false,
				'cloneable' => false,	
                'settings' => [
                  '_hidden' => [
                    '_cssClasses' => 'x-tabs_panel',
                  ],
                  '_attributes' => [
                    [
                      'name' => 'role',
                      'value' => 'tabpanel',
                    ],
                  ]
                ],
                'children' => [
						[
							'name'     => 'block',
							'label'    => esc_html__( 'Tab Content', 'bricks' ),
							'deletable' => false,
							'cloneable' => false,	
							'settings' => [
							'_hidden' => [
								'_cssClasses' => 'x-tabs_panel-content',
							],
							],
							'children' => [
							[
								'name'     => 'heading',
								'settings' => [
								'text' => 'Item 2',
								'tag'  => 'h4',
								],
							],
							[
								'name'     => 'text',
								'settings' => [
								'text' => esc_html__( 'Tab content here.', 'bricks' ),
								],
							],
							],
						],
					],
				],
            ],
          ],
        ],
      ],
	];
	}

	public function render() {

    $settings = $this->settings;

    $config = [
      'hoverSelect' => isset( $settings['hoverSelect'] ),
	  'accordionBreakpoint' => isset( $settings['accordionBreakpoint'] ) ? $settings['accordionBreakpoint'] : 'none',
	  'closeSibling' => isset( $settings['closeSibling'] ) ? $settings['closeSibling'] : 'none',
	  'duration' => isset( $settings['duration'] ) ? intval ( $settings['duration'] ) : 150,
	  'expandFirstItem' => isset( $settings['expandFirstItem'] ) ? $settings['expandFirstItem'] : false,
	  'tabUnselect' => isset( $settings['tabUnselect'] ) ? $settings['tabUnselect'] : false,
	  'tabOrientation' => isset( $settings['tabOrientation'] ) ? $settings['tabOrientation'] : 'horizontal',
	  'hashLink' => isset( $settings['hashLink'] ) && !isset( $settings['maybeURLparam'] ),
	  'URLparam' => isset( $settings['maybeURLparam'] ) && !isset( $settings['hashLink'] ),
	  'URLParamKey' => isset( $settings['URLParamKey'] ) ? esc_attr( $settings['URLParamKey']) : 'tab',
	  'scrollOffset' => isset( $settings['scrollOffset'] ) ? intval($settings['scrollOffset']) : 0,
	  'animateTabContent' => isset( $settings['animateTabContent'] ) ? $settings['animateTabContent'] : 'fadeinnonne_x'
    ];

	if (isset( $settings['animatedTabs'])) {
		$config['animatedTabs'] = 'true';
	}

	if (isset( $settings['disableBricksAnimations'])) {
		$config['disableBricksAnimations'] = 'true';
	}

	if (isset( $settings['adaptiveHeight'])) {
		$config['adaptiveHeight'] = 'true';
	}
	

	$tabWap = isset( $settings['tabWap'] ) ? $settings['tabWap'] : 'wrap';

    if ( is_array( $config ) ) {
			$tabsConfig = wp_json_encode( $config );
	}

	$this->set_attribute( '_root', 'data-x-tab-wrap', esc_attr( $tabWap ) );
    $this->set_attribute( '_root', 'data-x-tabs', $tabsConfig );

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


	$output = "<div {$this->render_attributes( '_root' )}>";

	$output .= \Bricks\Frontend::render_children( $this );

	$output .= '</div>';

	echo $output;
	}
}
