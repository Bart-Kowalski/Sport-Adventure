<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class X_Table_Of_Contents extends \Bricks\Element {

  // Element properties
  public $category     = 'extras';
	public $name         = 'xtableofcontents';
	public $icon         = 'ti-layout-list-post';
	public $css_selector = '';
	//public $scripts      = ['xTableOfContents'];

  // Methods: Builder-specific
  public function get_label() {
	return esc_html__( 'Table of Contents', 'extras' );
  }

  public function get_keywords() {
	return [ 'toc' ];
  }

  public function set_control_groups() {

	$this->control_groups['tableHeading'] = [
		'title' => esc_html__( 'Table Heading', 'extras' ),
		'tab' => 'content',
	];

	$this->control_groups['listItems'] = [
		'title' => esc_html__( 'List Items', 'extras' ),
		'tab' => 'content',
	];

	$this->control_groups['counterGroup'] = [
		'title' => esc_html__( 'Counters', 'extras' ),
		'tab' => 'content',
		'required' => ['listType', '=', 'counter']
	];

	$this->control_groups['borderGroup'] = [
		'title' => esc_html__( 'Border style', 'extras' ),
		'tab' => 'content',
		'required' => ['listType', '=', 'border']
	];


	$this->control_groups['behaviour'] = [
		'title' => esc_html__( 'Content', 'extras' ),
		'tab' => 'content',
	];

	$this->control_groups['conditionalDisplay'] = [
		'title' => esc_html__( 'Conditional display', 'extras' ),
		'tab' => 'content',
	];

  }


  public function set_controls() {

	$this->controls['contentSelector'] = [
		'tab'         => 'content',
		'type'        => 'text',
		'label' => esc_html__( 'Content selector*', 'bricks' ),
		'description' => esc_html__( 'Use the class or ID of the container around your content', 'bricks' ),									
		'placeholder' => '.your-class-here',
		'hasDynamicData' => false,
		'inline'      => true,
	];

	$this->controls['contentSelectorSep'] = [
		'tab'   => 'content',
		'type'  => 'separator',
	];

	$this->controls['headingSelectors'] = [
		'tab'         => 'content',
		'type'        => 'select',
		'multiple'    => true,
		'inline' => true,
		'options'   => [
			'h1' => 'h1',
			'h2' => 'h2',
			'h3' => 'h3',
			'h4' => 'h4',
			'h5' => 'h5',
			'h6' => 'h6',
		],
		'placeholder'   => ['h2','h3','h4','h5'],
		'label' => esc_html__( 'Headings to include', 'bricks' ),	
	];

	  $this->controls['ignoreSelector'] = [
		'tab' => 'content',
		'label' => esc_html__( 'Headings to ignore', 'bricks' ),
		'type'        => 'text',
		'inline' => true,
		'placeholder'   => '.ignore-class',
		'hasDynamicData' => false,
		//'description'=> esc_html__( 'Ignore headings matching this selector', 'bricks' ),
	  ];

	  $this->controls['tag'] = [
		'tab'       => 'content',
		'label'     => esc_html__( 'Wrapper tag', 'bricks' ),
		'type'      => 'select',
		//'group'    => 'listItems',
		'options'   => [
			'div' => 'div',
			'nav' => 'nav',
			'aside' => 'aside',
		],
		'inline'    => true,
		'clearable' => false,
		'placeholder'   => 'nav',
		'default'   => 'nav',
	];

	$this->controls['collapseDepth'] = [
		'tab' => 'content',
		'label' => esc_html__( 'Collapse depth', 'bricks' ),
		'type' => 'number',
		'min' => 0,
		'max' => 5,
		'step' => 1, 
		'units'=> false,
		'small' => true,
		'inline' => true,
		'placeholder' => '0',
		'default' => 0,
		'info'=> esc_html__( '0 = No items collapsed, all visible', 'bricks' ),
	  ];


	  $this->controls['listType'] = [
		'tab' => 'content',
		'label' => esc_html__( 'List type', 'bricks' ),
		'type' => 'select',
		'options' => [
		  //'icon' => esc_html__( 'Icon', 'bricks' ),
		  'counter' => esc_html__( 'Counter', 'bricks' ),
		  'border' => esc_html__( 'Border', 'bricks' ),
		  'text' => esc_html__( 'Text', 'bricks' ),
		],
		'inline'      => true,
		//'small'		  => true,
		'clearable' => false,
		'placeholder' => esc_html__( 'Text', 'bricks' ),
	  ];

	  $this->controls['conditionalDisplay'] = [
		'tab' => 'content',
		'label' => esc_html__( 'Conditional display', 'bricks' ),
		'type' => 'select',
		'group' => 'conditionalDisplay',
		'options' => [
		  'enable' => esc_html__( 'Enable', 'bricks' ),
		  'disable' => esc_html__( 'Disable', 'bricks' ),
		],
		'inline'      => true,
		'clearable' => false,
		'placeholder' => esc_html__( 'Disable', 'bricks' ),
	  ];

	  $this->controls['conditionalDisplayValue'] = [
		'tab' => 'content',
		'group' => 'conditionalDisplay',
		'label' => esc_html__( 'Minimum headings found to display', 'bricks' ),
		'type' => 'number',
		'units' => false,
		'inline'      => true,
		'small' => true,
		'required' => ['conditionalDisplay', '=', 'enable'],
		'placeholder' => '1'
	  ];

	$this->controls['breakpointSep'] = [
		'tab'   => 'content',
		'type'  => 'separator',
	];

	$this->controls['closePageLoad'] = [
		'tab'   => 'content',
		'inline' => true,
		'small' => true,
		'label' => esc_html__( 'Always closed on page load', 'bricks' ),
		'type'  => 'checkbox',
	];

	$this->controls['closeBreakpoint'] = [
		'tab'       => 'content',
		'label'     => esc_html__( 'Close at this breakpoint', 'bricks' ),
		'type'      => 'select',
		'options'   => [
			'true' => esc_html__( 'True', 'bricks' ),
			'false' => esc_html__( 'False', 'bricks' ),
		],
		'breakpoints' => true,
		'inline' => true,
		'small' => true,
		'placeholder' => esc_html__( 'False', 'bricks' ),
		'required' => ['closePageLoad', '!=', true],
		'css' => [
			[
				'selector' => '.x-toc_body',  
				'property' => '--x-toc-close',
				'value'    => 'var(--x-toc-close-%s)'
			  ],
		],
	];




	/* list items */

	$this->controls['subitemsIdent'] = [
		'tab' => 'content',
		'label' => esc_html__( 'Sub items indent', 'extras' ),
		'type' => 'number',
		'small'		=> true,
		'group'    => 'listItems',
		'css' => [
		  [
			'selector' => '.x-toc_list .x-toc_list',  
			'property' => 'padding-inline-start',
		  ],
		],
		'units' => true,
		'placeholder' => '10'
	  ];

	  $this->controls['columnCount'] = [
		'tab' => 'content',
		'label' => esc_html__( 'Column count', 'bricks' ),
		'type' => 'number',
		'group'    => 'listItems',
		'step' => '1',
		'inline' => true,
		'placeholder' => '0',
		'css'    => [
			[
				'property' => 'column-count',
				'selector' => '&:not([data-x-toc*=border]) .x-toc_body > .x-toc_list',
			],
		],
		'required' => ['listType', '!=', 'border']
	  ];

	  $this->controls['columnGap'] = [
		'tab' => 'content',
		'label' => esc_html__( 'Column gap', 'bricks' ),
		'type' => 'number',
		'group'    => 'listItems',
		'units' => [
			'px' => [
			  'min' => 1,
			  'max' => 30,
			  'step' => 1,
			],
			'em' => [
			  'min' => 1,
			  'max' => 20,
			  'step' => 0.1,
			],
		],
		'inline' => true,
		'placeholder' => '0',
		'css'    => [
			[
				'property' => 'column-gap',
				'selector' => '&:not([data-x-toc*=border]) .x-toc_body > .x-toc_list',
			],
		],
		'required' => ['listType', '!=', 'border']
	  ];

	  $this->controls['listTypographysept'] = [
		'tab'   => 'content',
		'group'  => 'listItems',
		'type'  => 'separator',
	  ];


	  $this->controls['itemTypography'] = [
		'tab'    => 'content',
		'group'  => 'listItems',
		'type'   => 'typography',
		'label'  => esc_html__( 'Typography', 'bricks' ),
		'css'    => [
			[
				'property' => 'font',
				'selector' => '.x-toc_link',
			],
		],
	 ];

	 $this->controls['itemTypographyActive'] = [
		'tab'    => 'content',
		'group'  => 'listItems',
		'type'   => 'typography',
		'label'  => esc_html__( 'Typography (active)', 'bricks' ),
		'css'    => [
			[
				'property' => 'font',
				'selector' => '.x-toc_link.x-toc_active-link',
			],
		],
	 ];

	 $this->controls['itemTypographyH2'] = [
		'tab'    => 'content',
		'group'  => 'listItems',
		'type'   => 'typography',
		'label'  => esc_html__( 'Typography (h2 items)', 'bricks' ),
		'css'    => [
			[
				'property' => 'font',
				'selector' => '.x-toc_link.node-name--H2',
			],
		],
	 ];
	 $this->controls['itemTypographyH3'] = [
		'tab'    => 'content',
		'group'  => 'listItems',
		'type'   => 'typography',
		'label'  => esc_html__( 'Typography (h3 items)', 'bricks' ),
		'css'    => [
			[
				'property' => 'font',
				'selector' => '.x-toc_link.node-name--H3',
			],
		],
	 ];
	 $this->controls['itemTypographyH4'] = [
		'tab'    => 'content',
		'group'  => 'listItems',
		'type'   => 'typography',
		'label'  => esc_html__( 'Typography (h4 items)', 'bricks' ),
		'css'    => [
			[
				'property' => 'font',
				'selector' => '.x-toc_link.node-name--H4',
			],
		],
	 ];
	 $this->controls['itemTypographyH5'] = [
		'tab'    => 'content',
		'group'  => 'listItems',
		'type'   => 'typography',
		'label'  => esc_html__( 'Typography (h5 items)', 'bricks' ),
		'css'    => [
			[
				'property' => 'font',
				'selector' => '.x-toc_link.node-name--H5',
			],
		],
	 ];
	 $this->controls['itemTypographyH6'] = [
		'tab'    => 'content',
		'group'  => 'listItems',
		'type'   => 'typography',
		'label'  => esc_html__( 'Typography (h6 items)', 'bricks' ),
		'css'    => [
			[
				'property' => 'font',
				'selector' => '.x-toc_link.node-name--H6',
			],
		],
	 ];

	 $this->controls['linkBackground'] = [
		'tab'    => 'content',
		'group'  => 'listItems',
		'type'   => 'color',
		'label'  => esc_html__( 'Link background', 'bricks' ),
		'css'    => [
			[
				'property' => 'background-color',
				'selector' => '.x-toc_link'
			],
		],
	];

	$this->controls['linkBackgroundActive'] = [
		'tab'    => 'content',
		'group'  => 'listItems',
		'type'   => 'color',
		'label'  => esc_html__( 'Link background (active)', 'bricks' ),
		'css'    => [
			[
				'property' => 'background-color',
				'selector' => '.x-toc_link.x-toc_active-link'
			],
		],
	];

	 $this->controls['listsep'] = [
		'tab'   => 'content',
		'group'  => 'listItems',
		'type'  => 'separator',
	  ];

	  
  
	  $this->controls['linkPadding'] = [
			'tab'   => 'content',
			'group' => 'listItems',
			'label' => esc_html__( 'Link padding', 'extras' ),
			'type'  => 'dimensions',
			'css'   => [
				[
					'property' => 'padding',
					'selector' => '.x-toc_link',
				],
			],
		];

		$this->controls['linkMargin'] = [
			'tab'   => 'content',
			'group' => 'listItems',
			'label' => esc_html__( 'Link margin', 'extras' ),
			'type'  => 'dimensions',
			'css'   => [
				[
					'property' => 'margin',
					'selector' => '.x-toc_link',
				],
			],
		]; 
		
		$this->controls['tableBodyPadding'] = [
			'tab'   => 'content',
			'group' => 'listItems',
			'label' => esc_html__( 'Table body padding', 'extras' ),
			'type'  => 'dimensions',
			'css'   => [
				[
					'property' => 'padding',
					'selector' => '.x-toc_body > .x-toc_list',
				],
			],
		];



	  /* icon counter border */


	  $this->controls['nestCounters'] = [
		'tab'   => 'content',
		'inline' => true,
		'group'  => 'counterGroup',
		'small' => true,
		'label' => esc_html__( 'Prevent nested counters', 'bricks' ),
		'type'  => 'checkbox',
	];

	$list_style_type_options = [
		"decimal" => "decimal (1, 2, 3)",
		"decimal-leading-zero" => "decimal-leading-zero (01, 02, 03)",
		"lower-roman" => "lower-roman (i ii iii)",
		"upper-roman" => "upper-roman (I II III)",
		"lower-alpha" => "lower-alpha (a b c)",
		"upper-alpha" => "upper-alpha (A B C)",
		"disc" => "disc",
		"circle" => "circle",
		"none",
	];

	 $this->controls['listStyleType'] = [
		'tab' => 'content',
		'label' => esc_html__( 'List style type', 'bricks' ),
		'group' => 'counterGroup',
		'type' => 'select',
		'options' => $list_style_type_options,
		'inline'      => true,
		//'small'		  => true,
		'clearable' => false,
		'placeholder' => esc_html__( 'decimal', 'bricks' ),
		'css'    => [
			[
				'property' => '--x-toc-type',
				'selector' => '.x-toc_link',
			],
		],
	  ];

	  $this->controls['listStyleTypeTwo'] = [
		'tab' => 'content',
		'label' => esc_html__( 'List style type', 'bricks' ),
		'group' => 'counterGroup',
		'type' => 'select',
		'options' => $list_style_type_options,
		'inline'      => true,
		//'small'		  => true,
		'clearable' => false,
		'placeholder' => esc_html__( 'decimal', 'bricks' ),
		'css'    => [
			[
				'property' => '--x-toc-typetwo',
				'selector' => '.x-toc_link',
			],
		],
	  ];
	  $this->controls['listStyleTypeThree'] = [
		'tab' => 'content',
		'label' => esc_html__( 'List style type', 'bricks' ),
		'group' => 'counterGroup',
		'type' => 'select',
		'options' => $list_style_type_options,
		'inline'      => true,
		//'small'		  => true,
		'clearable' => false,
		'placeholder' => esc_html__( 'decimal', 'bricks' ),
		'css'    => [
			[
				'property' => '--x-toc-typethree',
				'selector' => '.x-toc_body',
			],
		],
	  ];

	  $this->controls['listStyleTypeFour'] = [
		'tab' => 'content',
		'label' => esc_html__( 'List style type', 'bricks' ),
		'group' => 'counterGroup',
		'type' => 'select',
		'options' => $list_style_type_options,
		'inline'      => true,
		//'small'		  => true,
		'clearable' => false,
		'placeholder' => esc_html__( 'decimal', 'bricks' ),
		'css'    => [
			[
				'property' => '--x-toc-typefour',
				'selector' => '.x-toc_body',
			],
		],
	  ];

	  $this->controls['counterSeperator'] = [
		'tab' => 'content',
		'label' => esc_html__( 'Counter seperator', 'bricks' ),
		'group'    => 'counterGroup',
		'type' => 'text',
		'inline' => true,
		'placeholder' => '.',
		'css'    => [
			[
				'property' => '--x-toc-seperator',
				'selector' => '.x-toc_body',
			],
		],
		'hasDynamicData' => false,
	  ];

	  $this->controls['counterSuffix'] = [
		'tab' => 'content',
		'label' => esc_html__( 'Counter suffix', 'bricks' ),
		'group'    => 'counterGroup',
		'type' => 'text',
		'inline' => true,
		'placeholder' => '',
		'css'    => [
			[
				'property' => '--x-toc-suffix',
				'selector' => '.x-toc_body',
			],
		],
		'hasDynamicData' => false,
	  ];


	  $this->controls['counterTypograpphy'] = [
		'tab'    => 'content',
		'group'  => 'counterGroup',
		'type'   => 'typography',
		'label'  => esc_html__( 'Counter typography', 'bricks' ),
		'css'    => [
			[
				'property' => 'font',
				'selector' => '&[data-x-toc*=counter] .x-toc_link::before',
			],
		],
	 ];


	  $this->controls['listBorder'] = [
		'tab'    => 'content',
		'group'  => 'borderGroup',
		'type'   => 'color',
		'label'  => esc_html__( 'Border', 'bricks' ),
		'css'    => [
			[
				'property' => 'background-color',
				'selector' => '&[data-x-toc*=border] .x-toc_link::before'
			],
		],
	];

	$this->controls['listBorderActive'] = [
		'tab'    => 'content',
		'group'  => 'borderGroup',
		'type'   => 'color',
		'label'  => esc_html__( 'Active Border', 'bricks' ),
		'css'    => [
			[
				'property' => 'background-color',
				'selector' => '&[data-x-toc*=border] .x-toc_link.x-toc_active-link::before'
			],
		],
	];

	$this->controls['borderWidth'] = [
		'tab' => 'content',
		'label' => esc_html__( 'Border width', 'bricks' ),
		'type' => 'number',
		'group'    => 'borderGroup',
		'units' => [
			'px' => [
			  'min' => 1,
			  'max' => 30,
			  'step' => 1,
			],
			'em' => [
			  'min' => 1,
			  'max' => 20,
			  'step' => 0.1,
			],
		],
		'inline' => true,
		'placeholder' => '2px',
		'css'    => [
			[
				'property' => 'width',
				'selector' => '&[data-x-toc*=border] .x-toc_link::before',
			],
		],
	  ];


	/* table header */

	$header_selector = '.x-toc_header';

	$this->controls['maybe_remove_header'] = [
		'tab' => 'content',
		'label' => esc_html__( 'Remove heading', 'bricks' ),
		'type'  => 'checkbox',
		'group' => 'tableHeading',
	  ];

	  $this->controls['headerText'] = [
		'tab' => 'content',
		'label' => esc_html__( 'Header text', 'bricks' ),
		'group'    => 'tableHeading',
		'type' => 'text',
		'inline' => true,
		'hasDynamicData' => false,
		'placeholder' => 'Table of Contents',
		'default' => 'Table of Contents',
		'required' => ['maybe_remove_header', '!=', true]
	  ];

	  $this->controls['headerAriaLabel'] = [
		'tab' => 'content',
		'label' => esc_html__( 'Aria label', 'bricks' ),
		'group'    => 'tableHeading',
		'type' => 'text',
		'inline' => true,
		'hasDynamicData' => false,
		'placeholder' => 'Table of Contents',
		'default' => 'Table of Contents',
		'required' => ['maybe_remove_header', '!=', true]
	  ];

	  $this->controls['header_icon'] = [
        'tab'      => 'content',
        'group' => 'tableHeading',
        'label'    => esc_html__( 'Icon', 'bricks' ),
        'type'     => 'icon',
        'css'      => [
          [
            'selector' => '&.icon-svg', // NOTE: Undocumented: & = no space (add to element root)
          ],
        ],
        'default'  => [
          'library' => 'themify',
          'icon'    => 'ti-angle-down',
        ],
		'required' => ['maybe_remove_header', '!=', true]
      ];

	  $this->controls['headerIconSize'] = [
		'tab' => 'content',
		'label' => esc_html__( 'Icon size', 'bricks' ),
		'type' => 'number',
		'group'    => 'tableHeading',
		'units' => [
			'px' => [
			  'min' => 1,
			  'max' => 30,
			  'step' => 1,
			],
			'em' => [
			  'min' => 1,
			  'max' => 20,
			  'step' => 0.1,
			],
		],
		'inline' => true,
		'css'    => [
			[
				'property' => 'font-size',
				'selector' => '.x-toc_header-icon',
			],
		],
		'required' => ['maybe_remove_header', '!=', true]
	  ];

	  $this->controls['slideDuration'] = [
		'tab' => 'content',
		'label' => esc_html__( 'Slide duration (ms)', 'bricks' ),
		'group'  => 'tableHeading',
		'type' => 'number',
		'min' => 0,
		'max' => 1000,
		'inline' => true,
		'placeholder' => esc_html__( '300', 'bricks' ),
		'required' => ['maybe_remove_header', '!=', true]
	  ];

	  $this->controls['headerBackground'] = [
		'tab'    => 'content',
		'group'  => 'tableHeading',
		'type'   => 'background',
		'label'  => esc_html__( 'Background', 'bricks' ),
		'css'    => [
			[
				'property' => 'background',
				'selector' => $header_selector,
			],
		],
		'required' => ['maybe_remove_header', '!=', true]
	];

	$this->controls['headerTypography'] = [
		'tab'    => 'content',
		'group'  => 'tableHeading',
		'type'   => 'typography',
		'label'  => esc_html__( 'Typography', 'bricks' ),
		'css'    => [
			[
				'property' => 'font',
				'selector' => $header_selector,
			],
		],
		'required' => ['maybe_remove_header', '!=', true]
	];

	$this->controls['headerBorder'] = [
		'tab'   => 'content',
		'group' => 'tableHeading',
		'type'  => 'border',
		'label' => esc_html__( 'Border', 'bricks' ),
		'css'   => [
			[
				'property' => 'border',
				'selector' => $header_selector,
			],
		],
		'required' => ['maybe_remove_header', '!=', true]
	];

	$this->controls['headingsep'] = [
		'tab'   => 'content',
		'group'  => 'tableHeading',
		'type'  => 'separator',
		'required' => ['maybe_remove_header', '!=', true]
	  ];
  
	  $this->controls['headingPadding'] = [
			'tab'   => 'content',
			'group' => 'tableHeading',
			'label' => esc_html__( 'Padding', 'extras' ),
			'type'  => 'dimensions',
			'css'   => [
				[
					'property' => 'padding',
					'selector' => $header_selector,
				],
			],
			'required' => ['maybe_remove_header', '!=', true]
		];

		$this->controls['smoothScroll'] = [
			'tab' => 'content',
			'group'    => 'behaviour',
			'label' => esc_html__( 'Smooth scroll', 'bricks' ),
			'description' => esc_html__( 'Disable if prefer to rely on Bricks smooth scroll or another solution', 'bricks' ),
			'type' => 'select',
			'options' => [
			  'true' => esc_html__( 'Enable', 'bricks' ),
			  'false' => esc_html__( 'Disable', 'bricks' ),
			],
			
			'placeholder' => esc_html__( 'Enable', 'bricks' ),
			'clearable' => false,
		];	

	  $this->controls['smoothScrollOffset'] = [
		'tab' => 'content',
		'label' => esc_html__( 'Smooth Scroll Offset (px)', 'bricks' ),
		'type' => 'number',
		'group'    => 'behaviour',
		'min' => 0,
		'max' => 1000,
		'step' => '1',
		'inline' => true,
		'placeholder' => '0',
		'required' => ['smoothScroll', '!=', 'false']
	  ];

		$this->controls['autoID'] = [
			'tab'      => 'content',
			'group'    => 'behaviour',
			'label'    => esc_html__( 'Automatically add unique IDs to headings', 'bricksextras' ),
			'type'     => 'checkbox'
		];

		$this->controls['autoIDText'] = [
			'tab' => 'content',
			'group'    => 'behaviour',
			'label' => esc_html__( 'Heading IDs', 'bricks' ),
			'type' => 'select',
			'options' => [
			  'prefix' => esc_html__( 'Prefixed', 'bricks' ),
			  'text' => esc_html__( 'Use heading text', 'bricks' ),
			],
			'inline' => true,
			'placeholder' => esc_html__( 'Prefixed', 'bricks' ),
			'clearable' => false,
			'required' => ['autoID', '=', true]
		];	

		$this->controls['idPrefix'] = [
			'tab' => 'content',
			'label' => esc_html__( 'ID prefix', 'bricks' ),
			'group'    => 'behaviour',
			'type' => 'text',
			'inline' => true,
			'hasDynamicData' => false,
			'placeholder' => 'heading-',
			'required' => [
				['autoID', '=', true],
				['autoIDText', '!=', 'text']
			],
		  ];

	

  }

  // Methods: Frontend-specific
  public function enqueue_scripts() {
	wp_enqueue_script( 'x-frontend', BRICKSEXTRAS_URL . 'components/assets/js/' . \BricksExtras\Helpers::maybeMinifyScripts('frontend') . '.js', '', '1.0.1', true );
	wp_enqueue_script( 'x-toc', BRICKSEXTRAS_URL . 'components/assets/js/' . \BricksExtras\Helpers::maybeMinifyScripts('tableofcontents') . '.js', ['x-frontend'], '1.0.8', true );

	if (! \BricksExtras\Helpers::elementCSSAdded($this->name) ) {
		wp_enqueue_style( 'x-toc', BRICKSEXTRAS_URL . 'components/assets/css/tableofcontents.css', [], '' );
	}
  }

  public function render() {

		$tag = isset( $this->settings['tag'] ) ? \Bricks\Helpers::sanitize_html_tag( $this->settings['tag'], 'nav' ) : 'nav';

		$aria_label = isset( $this->settings['headerAriaLabel'] ) ? esc_attr__( $this->settings['headerAriaLabel'] ) : 'Table of Contents';

		$header_icon = empty( $this->settings['header_icon'] ) ? false : self::render_icon( $this->settings['header_icon'] );
		$headerText = isset( $this->settings['headerText'] ) ? esc_html( $this->settings['headerText'] ) : 'Table of Contents';
		$closePageLoad = isset( $this->settings['closePageLoad'] );

		$toc_config = [
			'contentSelector' => isset( $this->settings['contentSelector'] ) ? esc_attr( $this->settings['contentSelector'] ) : '',
			'headingSelectors' => isset( $this->settings['headingSelectors'] ) ? $this->settings['headingSelectors'] : 'h2,h3,h4,h5',
			'autoID' => isset( $this->settings['autoID'] ) ? true : false,
			'idPrefix' => isset( $this->settings['idPrefix'] ) ? esc_attr($this->settings['idPrefix']) : 'heading',
			'autoIDText' => isset( $this->settings['autoIDText'] ) ? esc_attr( $this->settings['autoIDText'] ) : 'prefix',
			'scrollSmoothOffset' => isset( $this->settings['smoothScrollOffset'] ) ? 0 - intval( $this->settings['smoothScrollOffset'] ) : 0,
			'listType' => isset( $this->settings['listType'] ) ? $this->settings['listType'] : 'counter',
			'slideDuration' => isset( $this->settings['slideDuration'] ) ? $this->settings['slideDuration'] : 300,
			'ignoreSelector' => isset( $this->settings['ignoreSelector'] ) ? esc_attr( $this->settings['ignoreSelector'] ) : '',
			'smoothScroll' => isset( $this->settings['smoothScroll'] ) ? $this->settings['smoothScroll'] : 'true',
			'collapseDepth' => isset( $this->settings['collapseDepth'] ) ? intval($this->settings['collapseDepth']) : 0,
			'conditionalDisplay' => isset( $this->settings['conditionalDisplay'] ) ? $this->settings['conditionalDisplay'] : 'disable',
			'conditionalDisplayValue' => isset( $this->settings['conditionalDisplayValue'] ) ? intval( $this->settings['conditionalDisplayValue'] ) : 1,
			'hidden' => [
				'closeBreakpoint' => ! empty( $this->settings['closeBreakpoint'] ) ? $this->settings['closeBreakpoint'] : 'false'
			]
		];

		if ( isset( $this->settings['nestCounters']  ) ) {
			$toc_config += [ 'nestCounters' => 'true' ];
		}

		$breakpoints = [];

		foreach ( \Bricks\Breakpoints::$breakpoints as $breakpoint ) {
			foreach ( array_keys( $toc_config['hidden'] ) as $option ) {
				$setting_key      = "$option:{$breakpoint['key']}";
				$breakpoint_width = ! empty( $breakpoint['width'] ) ? $breakpoint['width'] : false;
				$setting_value    = isset( $this->settings[ $setting_key ] ) ? $this->settings[ $setting_key ] : false;

				if ( $breakpoint_width && $setting_value !== false ) {
					$breakpoints[ $breakpoint_width ][ $option ] = $setting_value;
				}
			}
		}

		if ( count( $breakpoints ) ) {
			$toc_config['hidden']['breakpoints'] = $breakpoints;
		}



		$this->set_attribute( 'x-toc_header', 'class', 'x-toc_header' );
		$this->set_attribute( 'x-toc_header', 'role', 'button' );
		$this->set_attribute( 'x-toc_header', 'tabindex', '0' );
		$this->set_attribute( 'x-toc_header', 'aria-label', $aria_label );
		$this->set_attribute( 'x-toc_header', 'aria-controls', 'x-toc_' . $this->id );

		if ( $closePageLoad ) {
			$this->set_attribute( 'x-toc_header', 'aria-expanded', 'false' );
			$this->set_attribute( 'x-toc_body', 'style', 'display:none' );
		} else {
			$this->set_attribute( 'x-toc_header', 'aria-expanded', 'true' );
		}

		$this->set_attribute( 'x-toc_header-text', 'class', 'x-toc_header-text' );

		$this->set_attribute( 'x-toc_body', 'class', 'x-toc_body' );
		$this->set_attribute( 'x-toc_body', 'aria-hidden', 'false' );
		$this->set_attribute( 'x-toc_body', 'id', 'x-toc_' . $this->id );

		$this->set_attribute( 'x-toc_header-icon', 'class', 'x-toc_header-icon');

		$this->set_attribute( '_root', 'data-x-toc', wp_json_encode( $toc_config ) );
		
		echo "<" . $tag . " {$this->render_attributes( '_root' )}>";
		
		if ( !isset ( $this->settings['maybe_remove_header'] ) ) {

			echo  "<div {$this->render_attributes( 'x-toc_header' )}><div {$this->render_attributes( 'x-toc_header-text' )}>" . $headerText . "</div>";
			echo $header_icon ? "<div {$this->render_attributes( 'x-toc_header-icon' )}>" . $header_icon  . "</div>" : "";
			echo "</div>";

		}

		echo "<div {$this->render_attributes( 'x-toc_body' )}></div>";
		
		echo "</" . $tag . ">";
    
  }

  public static function render_builder() { ?>

		<script type="text/x-template" id="tmpl-bricks-element-xtableofcontents">
			<component
					class=brxe-xtableofcontents
					:is="tag"
					:data-x-toc="(settings.nestCounters ? 'nestCounters' : '') + settings.listType"
			>

			<div 
				class="x-toc_header" 
				role=button 
				:aria-expanded="!settings.closePageLoad ? 'true' : 'false'"
				v-show="!settings.maybe_remove_header"
			>
				<contenteditable
					tag="div"
					class="x-toc_header-text"
					:name="name"
					controlKey="headerText"
					toolbar="style"
					:settings="settings"
					placeholder="Table of Contents"
				/>
				<div class="x-toc_header-icon">
				<icon-svg 
					:iconSettings="settings.header_icon"
				/>
  				</div>
  			</div>
			<div 
				class="x-toc_body x-toc_body_builder"
				v-show="!settings.closePageLoad"
			>
						<ol class="x-toc_list ">
							<li v-show="5 > settings.collapseDepth || !settings.collapseDepth" class="x-toc_list-item">
								<a href="#one" class="x-toc_link x-toc_active-link node-name--H2">Primary item (h2)</a>
								<ol class="x-toc_list ">
									<li v-show="4 > settings.collapseDepth || !settings.collapseDepth" class="x-toc_list-item">
										<a href="#two" class="x-toc_link node-name--H3 ">Sub item (h3)</a>
									</li>
								</ol>
							</li>
							<li v-show="5 > settings.collapseDepth || !settings.collapseDepth" class="x-toc_list-item"><a href="#four" class="x-toc_link node-name--H2 ">Primary item (h2)</a>
								<ol class="x-toc_list ">
									<li v-show="4 > settings.collapseDepth || !settings.collapseDepth" class="x-toc_list-item"><a href="#five" class="x-toc_link node-name--H3 ">Sub item (h3)</a></li>
									<li v-show="4 > settings.collapseDepth || !settings.collapseDepth" class="x-toc_list-item"><a href="#six" class="x-toc_link node-name--H3 ">Sub item (h3)</a>
										<ol class="x-toc_list ">
											<li v-show="3 > settings.collapseDepth || !settings.collapseDepth" class="x-toc_list-item">
												<a href="#two" class="x-toc_link node-name--H4 ">Sub item (h4)</a>
												<ol class="x-toc_list ">
													<li v-show="2 > settings.collapseDepth || !settings.collapseDepth" class="x-toc_list-item">
														<a href="#two" class="x-toc_link node-name--H5 ">Sub item (h5)</a>
														<ol class="x-toc_list ">
															<li v-show="1 > settings.collapseDepth || !settings.collapseDepth" class="x-toc_list-item">
																<a href="#two" class="x-toc_link node-name--H5 ">Sub item (h6)</a>
															</li>
														</ol>
													</li>
												</ol>
											</li>
											<li v-show="3 > settings.collapseDepth || !settings.collapseDepth" class="x-toc_list-item">
												<a href="#two" class="x-toc_link node-name--H4 ">Sub item (h4)</a>
												<ol class="x-toc_list ">
													<li v-show="2 > settings.collapseDepth || !settings.collapseDepth" class="x-toc_list-item">
														<a href="#two" class="x-toc_link node-name--H5 ">Sub item (h5)</a>
														<ol class="x-toc_list ">
															<li v-show="1 > settings.collapseDepth || !settings.collapseDepth" class="x-toc_list-item">
																<a href="#two" class="x-toc_link node-name--H5 ">Sub item (h6)</a>
															</li>
														</ol>
													</li>
												</ol>
											</li>
										</ol>
									</li>
								</ol>
							</li>
							<li v-show="5 > settings.collapseDepth || !settings.collapseDepth" class="x-toc_list-item">
								<a href="#one" class="x-toc_link node-name--H2">Primary item (h2)</a>
								<ol class="x-toc_list ">
									<li v-show="4 > settings.collapseDepth || !settings.collapseDepth" class="x-toc_list-item">
										<a href="#two" class="x-toc_link node-name--H3 ">Sub item (h3)</a>
									</li>
								</ol>
							</li>
						</ol>
  			</div>
			</component>	
		</script>

	<?php }

}