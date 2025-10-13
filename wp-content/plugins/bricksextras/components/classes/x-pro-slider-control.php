<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class X_Pro_Slider_Control extends \Bricks\Element {

  // Element properties
   public $category     = 'extras';
	public $name         = 'xproslidercontrol';
	public $icon         = 'ti-signal';
	public $nestable = true;

  
  public function get_label() {
	  return esc_html__( 'Pro Slider Control', 'extras' );
  }
  public function set_control_groups() {

    $this->control_groups['counterCurrent'] = [
			'title' => esc_html__( 'Current slide no.', 'bricks' ),
      		'required' => [ 'controlType', '=', 'counter' ],
		];

    $this->control_groups['counterSeperator'] = [
			'title' => esc_html__( 'Seperator.', 'bricks' ),
      		'required' => [ 'controlType', '=', 'counter' ],
		];

    $this->control_groups['counterTotal'] = [
			'title' => esc_html__( 'Total slides no.', 'bricks' ),
      		'required' => [ 'controlType', '=', 'counter' ],
		];

		$this->control_groups['playButtonIconGroup'] = [
			'title' => esc_html__( 'Icons', 'bricks' ),
      		'required' => [ 'controlType', '=', 'playPause' ],
		];

		$this->control_groups['playButtonGroup'] = [
			'title' => esc_html__( 'Button styles', 'bricks' ),
      		'required' => [ 'controlType', '=', 'playPause' ],
		];

		$this->control_groups['progressBarGroup'] = [
			'title' => esc_html__( 'Progress Bar', 'bricks' ),
      		'required' => [ 'controlType', '!=', ['navArrow','playPause', 'counter','autoplayProgress', 'slideContent'] ],
		];

		$this->control_groups['navArrowGroup'] = [
			'title' => esc_html__( 'Navigation Button', 'bricks' ),
      		'required' => [ 'controlType', '=', 'navArrow' ],
		];

		$this->control_groups['autoplayProgressGroup'] = [
			'title' => esc_html__( 'Autoplay Progress', 'bricks' ),
      		'required' => [ 'controlType', '=', 'autoplayProgress' ],
		];

		$this->control_groups['slideContentGroup'] = [
			'title' => esc_html__( 'Slide Content', 'bricks' ),
      		'required' => [ 'controlType', '=', 'slideContent' ],
		];

  }

  public function set_controls() {

	$this->controls['intro'] = [
		'tab'         => 'content',
		'description'    => esc_html__( 'This element should be placed outside of the Pro Slider', 'bricks' ),
		'type'     => 'separator',
	];

	$this->controls['slider'] = [
		'tab'   => 'content',
		'label' => esc_html__( 'Slider to control..', 'bricks' ),
		'type'  => 'select',
		'options' => [
			'section' => esc_html__( 'Auto find slider within same section', 'bricks' ),
			'selector' => esc_html__( 'Control specific slider', 'bricks' ),
		],
		'placeholder' => esc_html__( 'Auto find slider within same section', 'bricks' ),
	];

    $this->controls['sliderSelector'] = [
			'tab'         => 'content',
			'type'        => 'text',
			'label' => esc_html__( 'Slider selector', 'bricks' ),
			'hasDynamicData' => false,
			'inline'      => true,
			'required' => [ 'slider', '=', 'selector' ],
		];

    $this->controls['controlType'] = [
			'tab'   => 'content',
			//'inline' => true,
			'placeholder' => esc_html__( 'Progress Bar', 'bricks' ),
			'label' => esc_html__( 'Control type', 'bricks' ),
			'type'  => 'select',
			'options' => [
				'progressBar' => esc_html__( 'Progress Bar', 'bricks' ),
        		'counter' => esc_html__( 'Counter', 'bricks' ),
				'navArrow' => esc_html__( 'Navigation Button', 'bricks' ),
        		'playPause' => esc_html__( 'Autoplay Play/Pause', 'bricks' ),
				'autoplayProgress' => esc_html__( 'Autoplay Progress', 'bricks' ),
				'slideContent' => esc_html__( 'Slide Content', 'bricks' ),
			]
		];

	 $this->controls['slideToGetContent'] = [
			'tab'   => 'content',
			'inline' => true,
			'placeholder' => esc_html__( 'Active slide', 'bricks' ),
			'label' => esc_html__( 'Slide', 'bricks' ),
			'type'  => 'select',
			'options' => [
				'active' => esc_html__( 'Active slide', 'bricks' ),
        		'previous' => esc_html__( 'Previous slide', 'bricks' ),
				'next' => esc_html__( 'Next slide', 'bricks' ),
			],
			'required' => [ 'controlType', '=', 'slideContent' ],
		];



	/* content */
	
	$this->controls['contentTag'] = [
		'label'    => esc_html__( 'Tag', 'bricks' ),
		'type'     => 'text',
		'hasDynamicData' => false,
		'inline'      => true,
		'small' => true,
		'placeholder' => 'div',
		'required' => [ 'controlType', '=', 'slideContent' ],
	];



    /* play button */ 

    $this->controls['playIcon'] = [
			'label'    => esc_html__( 'Play icon', 'bricks' ),
			'type'     => 'icon',
			'group'   => 'playButtonIconGroup',
			'rerender' => true,
			'css'      => [
				[
					'selector' => '.x-splide__toggle__play > *',
				],
			],
			/*
			'default'  => [
				'library' => 'ionicons',
				'icon'    => 'ion-ios-play',
			  ],
			  */
			  'required' => [ 'controlType', '=', 'playPause' ],
		];

    $this->controls['pauseIcon'] = [
			'label'    => esc_html__( 'Pause icon', 'bricks' ),
			'type'     => 'icon',
			'group'   => 'playButtonIconGroup',
			'rerender' => true,
			'css'      => [
				[
					'selector' => '.x-splide__toggle__pause > *',
				],
			],
			/*
			'default'  => [
				'library' => 'ionicons',
				'icon'    => 'ion-ios-pause',
			  ],
			  */
			  'required' => [ 'controlType', '=', 'playPause' ],
		];

    $this->controls['playAriaLabel'] = [
			'tab'         => 'content',
			'group'   => 'playButtonIconGroup',
			'type'        => 'text',
			'label' => esc_html__( 'Aria label (play)', 'bricks' ),
			'hasDynamicData' => false,
			'inline'      => true,
     	 'required' => [ 'controlType', '=', 'playPause' ],
		];

    $this->controls['pauseAriaLabel'] = [
			'tab'         => 'content',
			'type'        => 'text',
			'label' => esc_html__( 'Aria label (pause)', 'bricks' ),
			'hasDynamicData' => false,
			'group'   => 'playButtonIconGroup',
			'inline'      => true,
      		'required' => [ 'controlType', '=', 'playPause' ],
		];


    $this->controls['buttonSep'] = [
			'label'    => esc_html__( 'Button styles', 'bricks' ),
			'type'     => 'separator',
			'group'   => 'playButtonGroup',
			'required' => [ 'controlType', '=', 'playPause' ],
    ];



    $this->controls['buttonTypography'] = [
			'label'    => esc_html__( 'Typography', 'bricks' ),
			'type'     => 'typography',
			'group'   => 'playButtonGroup',
			'css'      => [
				[
					'property' => 'font',
					'selector' => '.x-splide__toggle',
				],
			],
			'required' => [ 'controlType', '=', 'playPause' ],
		];

		$this->controls['buttonBorder'] = [
			'label'    => esc_html__( 'Border', 'bricks' ),
			'group'   => 'playButtonGroup',
			'type'     => 'border',
			'css'      => [
				[
					'property' => 'border',
					'selector' => '.x-splide__toggle',
				],
			],
			'required' => [ 'controlType', '=', 'playPause' ],
		];

    $this->controls['buttonBackground'] = [
			'label'    => esc_html__( 'Background', 'bricks' ),
			'type'     => 'background',
			'group'   => 'playButtonGroup',
			'css'      => [
				[
					'property' => 'background',
					'selector' => '.x-splide__toggle',
				],
			],
			'required' => [ 'controlType', '=', 'playPause' ],
		];

    $this->controls['buttonShadow'] = [
			'tab'    => 'content',
			'label'  => esc_html__( 'Box Shadow', 'extras' ),
			'type'   => 'box-shadow',
			'group'   => 'playButtonGroup',	
			'css'    => [
				[
					'property' => 'box-shadow',
					'selector' => '.x-splide__toggle',
				],
			],
			'required' => [ 'controlType', '=', 'playPause' ],
		];

    $this->controls['buttonMargin'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Margin', 'bricks' ),
			'group'   => 'playButtonGroup',
			'type' => 'dimensions',
			'css' => [
			  [
				'property' => 'margin',
				'selector' => '.x-splide__toggle',
			  ]
			],
			'required' => [ 'controlType', '=', 'playPause' ],
		  ];

      $this->controls['buttonPadding'] = [
        'tab' => 'content',
        'label' => esc_html__( 'Padding', 'bricks' ),
		'group'   => 'playButtonGroup',
        'type' => 'dimensions',
        'css' => [
          [
          'property' => 'padding',
          'selector' => '.x-splide__toggle',
          ]
        ],
        'required' => [ 'controlType', '=', 'playPause' ],
      ];



	


    /* counter */

    $this->controls['counterCurrentSeparator'] = [
      'group'   => 'counterCurrent',
			'label'    => esc_html__( 'Current slide no.', 'bricks' ),
			'type'     => 'separator',
    ];


    $this->controls['indexCurrentText'] = [
			'tab'         => 'content',
      		'group'   => 'counterCurrent',
			'type'        => 'text',
			'label' => esc_html__( 'Prefix', 'bricks' ),
			'hasDynamicData' => false,
			'inline'      => true,
		];

    $this->controls['indexCurrentTextSuffix'] = [
			'tab'         => 'content',
      'group'   => 'counterCurrent',
			'type'        => 'text',
			'label' => esc_html__( 'Suffix', 'bricks' ),
			'hasDynamicData' => false,
			'inline'      => true,
		];

    $this->controls['indexTypography'] = [
			'label'    => esc_html__( 'Typography', 'bricks' ),
			'type'     => 'typography',
      'group'   => 'counterCurrent',
			'css'      => [
				[
					'property' => 'font',
					'selector' => '.x-slider_counter-index',
				],
			],
		];

		$this->controls['indexBorder'] = [
			'label'    => esc_html__( 'Border', 'bricks' ),
      'group'   => 'counterCurrent',
			'type'     => 'border',
			'css'      => [
				[
					'property' => 'border',
					'selector' => '.x-slider_counter-index',
				],
			],
		];

    $this->controls['indexBackground'] = [
			'label'    => esc_html__( 'Background', 'bricks' ),
			'type'     => 'background',
      'group'   => 'counterCurrent',
			'css'      => [
				[
					'property' => 'background',
					'selector' => '.x-slider_counter-index',
				],
			],
		];

    $this->controls['indexShadow'] = [
			'tab'    => 'content',
			'label'  => esc_html__( 'Box Shadow', 'extras' ),
			'type'   => 'box-shadow',
      'group'   => 'counterCurrent',
			'css'    => [
				[
					'property' => 'box-shadow',
					'selector' => '.x-slider_counter-index',
				],
			],
		];

    $this->controls['indexMargin'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Margin (of number)', 'bricks' ),
      'group'   => 'counterCurrent',
			'type' => 'dimensions',
			'css' => [
			  [
				'property' => 'margin',
				'selector' => '.x-slider_counter-index-number',
			  ]
			],
      'placeholder' => [
			  'top' => '0',
			  'right' => '0',
			  'bottom' => '0',
			  'left' => '5px',
			],
		  ];

      $this->controls['indexPadding'] = [
        'tab' => 'content',
        'label' => esc_html__( 'Padding', 'bricks' ),
        'group'   => 'counterCurrent',
        'type' => 'dimensions',
        'css' => [
          [
          'property' => 'padding',
          'selector' => '.x-slider_counter-index',
          ]
        ],
      ];





    $this->controls['counterSeparator'] = [
			'label'    => esc_html__( 'Seperator', 'bricks' ),
      		'group'   => 'counterSeperator',
			'type'     => 'separator',
    ];


    $this->controls['slideSeperatorText'] = [
			'tab'         => 'content',
      'group'   => 'counterSeperator',
			'type'        => 'text',
			'label' => esc_html__( 'Seperator', 'bricks' ),
			'hasDynamicData' => false,
			'placeholder' => 'of',
			'default' => 'of',
			'inline'      => true,
		];

    $this->controls['seperatorTypography'] = [
			'label'    => esc_html__( 'Typography', 'bricks' ),
			'type'     => 'typography',
      		'group'   => 'counterSeperator',
			'css'      => [
				[
					'property' => 'font',
					'selector' => '.x-slider_counter-seperator',
				],
			],
		];

    $this->controls['seperatorMargin'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Margin', 'bricks' ),
			'type' => 'dimensions',
      		'group'   => 'counterSeperator',
			'css' => [
			  [
				'property' => 'margin',
				'selector' => '.x-slider_counter-seperator',
			  ]
			],
			'placeholder' => [
			  'top' => '0',
			  'right' => '5px',
			  'bottom' => '0',
			  'left' => '5px',
			],
		  ];


    $this->controls['counterTotalSeparator'] = [
			'label'    => esc_html__( 'Total slides no.', 'bricks' ),
     		 'group'   => 'counterTotal',
			'type'     => 'separator',
			'required' => [ 'controlType', '=', 'counter' ],
    ];


    $this->controls['slideTotalText'] = [
			'tab'         => 'content',
      		'group'   => 'counterTotal',
			'type'        => 'text',
			'label' => esc_html__( 'Prefix', 'bricks' ),
			'hasDynamicData' => false,
			'inline'      => true,
		];

    $this->controls['slideTotalTextSuffix'] = [
			'tab'         => 'content',
			'type'        => 'text',
      		'group'   => 'counterTotal',
			'label' => esc_html__( 'Suffix', 'bricks' ),
			'hasDynamicData' => false,
			'inline'      => true,
		];

    $this->controls['totalTypography'] = [
			'label'    => esc_html__( 'Typography', 'bricks' ),
			'type'     => 'typography',
      		'group'   => 'counterTotal',
			'css'      => [
				[
					'property' => 'font',
					'selector' => '.x-slider_counter-total',
				],
			],
		];

		$this->controls['totalBorder'] = [
			'label'    => esc_html__( 'Border', 'bricks' ),
			'type'     => 'border',
      		'group'   => 'counterTotal',
			'css'      => [
				[
					'property' => 'border',
					'selector' => '.x-slider_counter-total',
				],
			],
		];

    $this->controls['totalBackground'] = [
			'label'    => esc_html__( 'Background', 'bricks' ),
			'type'     => 'background',
      		'group'   => 'counterTotal',
			'css'      => [
				[
					'property' => 'background',
					'selector' => '.x-slider_counter-total',
				],
			],
		];

    $this->controls['totalShadow'] = [
			'tab'    => 'content',
			'label'  => esc_html__( 'Box Shadow', 'extras' ),
			'type'   => 'box-shadow',
      		'group'   => 'counterTotal',
			'css'    => [
				[
					'property' => 'box-shadow',
					'selector' => '.x-slider_counter-total',
				],
			],
		];

    $this->controls['totalMargin'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Margin (of number)', 'bricks' ),
			'type' => 'dimensions',
      		'group'   => 'counterTotal',
			'css' => [
			  [
				'property' => 'margin',
				'selector' => '.x-slider_counter-total-number',
			  ]
			],
      	'placeholder' => [
			  'top' => '0',
			  'right' => '5px',
			  'bottom' => '0',
			  'left' => '0',
			],
		  ];

      $this->controls['totalPadding'] = [
        'tab' => 'content',
        'label' => esc_html__( 'Padding', 'bricks' ),
        'type' => 'dimensions',
        'group'   => 'counterTotal',
        'css' => [
          [
          'property' => 'padding',
          'selector' => '.x-slider_counter-total',
          ]
        ],
      ];


    // Progress bar 

    $this->controls['progressbarSeparator'] = [
			'label'    => esc_html__( 'Progress bar', 'bricks' ),
			'type'     => 'separator',
			'group'	   => 'progressBarGroup',
			'required' => [ 'controlType', '!=', [
				'counter',
				'playPause',
				'navArrow'
				]
			],
    ];

	$this->controls['progressBarClickable'] = [
		'label'    => esc_html__( 'Make progress bar interactive', 'bricks' ),
		'type'     => 'checkbox',
		'group'	   => 'progressBarGroup',
		'inline'   => true,
		'default'  => true,
		'required' => [ 'controlType', '!=', [
			'counter',
			'playPause',
			'navArrow'
			]
		],
	];

	$this->controls['progressBarAriaLabel'] = [
		'label'    => esc_html__( 'Aria label', 'bricks' ),
		'type'     => 'text',
		'group'	   => 'progressBarGroup',
		'inline'   => true,
		'default'  => true,
		'required' => [ 'controlType', '!=', [
			'counter',
			'playPause',
			'navArrow'
			]
		],
		'placeholder' => esc_attr__( 'Slider progress' )
	];


    $this->controls['progressDirection'] = [
			'label'       => esc_html__( 'Direction', 'bricks' ),
			'type'        => 'select',
			'group'	   => 'progressBarGroup',
			'options'     => [
				'ltr' => esc_html__( 'Left to right', 'bricks' ),
				'rtl' => esc_html__( 'Right to left', 'bricks' ),
			],
			'inline'      => true,
			'placeholder' => esc_html__( 'Left to right', 'bricks' ),
			'required' => [ 'controlType', '!=', [
				'counter',
				'playPause',
				'navArrow'
				]
			],
		];

		$this->controls['progressBarTransition'] = [
			'label'       => esc_html__( 'Transition duration', 'bricks' ),
			'type'        => 'number',
			'group'	   => 'progressBarGroup',
			'placeholder' => '400ms',
			'required' => [ 'controlType', '!=', [
				'counter',
				'playPause',
				'navArrow'
				]
			],
            'css'   => [
				[
					'property' => 'transition-duration',
					'selector' => '.x-slider_progress-bar',
				],
			],
		];

		$this->controls['progressBarBackground'] = [
			'label'    => esc_html__( 'Background', 'bricks' ),
			'type'     => 'background',
			'group'	   => 'progressBarGroup',
			'css'      => [
				[
					'property' => 'background',
					'selector' => '.x-slider_progress',
				],
			],
			'required' => [ 'controlType', '!=', [
				'counter',
				'playPause',
				'navArrow'
				]
			],
		];

		$this->controls['progressBarActiveBackground'] = [
			'label'    => esc_html__( 'Background (active)', 'bricks' ),
			'type'     => 'background',
			'group'	   => 'progressBarGroup',
			'css'      => [
				[
					'property' => 'background',
					'selector' => '.x-slider_progress-bar',
				],
			],
			'required' => [ 'controlType', '!=', [
				'counter',
				'playPause',
				'navArrow'
				]
			],
		];

		$this->controls['progressBarBorder'] = [
			'label'    => esc_html__( 'Border', 'bricks' ),
			'type'     => 'border',
			'group'	   => 'progressBarGroup',
			'css'      => [
				[
					'property' => 'border',
					'selector' => '.x-slider_progress',
				],
			],
			'required' => [ 'controlType', '!=', [
				'counter',
				'playPause',
				'navArrow'
				]
			],
		];

		$this->controls['progressBarThickness'] = [
			'label'    => esc_html__( 'Thickness', 'bricks' ),
			'type'     => 'number',
			'group'	   => 'progressBarGroup',
			'units'    => true,
			'placeholder' => '4px',
			'css'      => [
				[
					'property' => '--xsliderprogress-thickness',
					'selector' => '.x-slider_progress',
				],
			],
			'required' => [ 'controlType', '!=', [
				'counter',
				'playPause',
				'navArrow'
				]
			],
		];

		$this->controls['progressPositioning'] = [
			'label'       => esc_html__( 'Position', 'bricks' ),
			'type'        => 'select',
			'group'	   => 'progressBarGroup',
			'options'     => [
				'static' => esc_html__( 'Static', 'bricks' ),
				'absolute'  => esc_html__( 'Absolute', 'bricks' ),
			],
			'inline'      => true,
			'placeholder' => esc_html__( 'Relative', 'bricks' ),
			'required' => [ 'controlType', '!=', [
				'counter',
				'playPause',
				'navArrow'
				]
			],
			'css'      => [
				[
					'property' => 'position',
					'selector' => '.x-slider_progress',
				],
			],
		]; 

		$this->controls['progressTop'] = [
			'label'    => esc_html__( 'Top', 'bricks' ),
			'type'     => 'number',
			'group'	   => 'progressBarGroup',
			'units'    => true,
			'css'      => [
				[
					'property' => 'top',
					'selector' => '.x-slider_progress',
				],
			],
			'required' => [ 'controlType', '!=', [
				'counter',
				'playPause',
				'navArrow'
				]
			],
		];

		$this->controls['progressRight'] = [
			'label'    => esc_html__( 'Right', 'bricks' ),
			'type'     => 'number',
			'group'	   => 'progressBarGroup',
			'units'    => true,
			'placeholder' => '0',
			'css'      => [
				[
					'property' => 'right',
					'selector' => '.x-slider_progress',
				],
			],
			'required' => [ 'controlType', '!=', [
				'counter',
				'playPause',
				'navArrow'
				]
			],
		];

		$this->controls['progressBottom'] = [
			'label'    => esc_html__( 'Bottom', 'bricks' ),
			'type'     => 'number',
			'group'	   => 'progressBarGroup',
			'units'    => true,
			'placeholder' => '0',
			'css'      => [
				[
					'property' => 'bottom',
					'selector' => '.x-slider_progress',
				],
			],
			'required' => [ 'controlType', '!=', [
				'counter',
				'playPause',
				'navArrow'
				]
			],
		];

		$this->controls['progressLeft'] = [
			'label'    => esc_html__( 'Left', 'bricks' ),
			'type'     => 'number',
			'group'	   => 'progressBarGroup',
			'units'    => true,
			'placeholder' => '0',
			'css'      => [
				[
					'property' => 'left',
					'selector' => '.x-slider_progress',
				],
			],
			'required' => [ 'controlType', '!=', [
				'counter',
				'playPause',
				'navArrow'
				]
			],
		];

		$this->controls['progressMargin'] = [
			'label'       => esc_html__( 'Margin', 'bricks' ),
			'type'        => 'spacing',
			'group'	   => 'progressBarGroup',
			'css'         => [
				[
					'property' => 'margin',
					'selector' => '.x-slider_progress',
				],
			],
			'required' => [ 'controlType', '!=', [
				'counter',
				'playPause',
				'navArrow'
				]
			],
		];


		/* navigation buttons */

		 $this->controls['navSeparator'] = [
			'label'    => esc_html__( 'Navigation button', 'bricks' ),
			'type'     => 'separator',
			'group'	   => 'navArrowGroup',
			'required'    => [ 
				['controlType', '=', 'navArrow']
			],
    	];

		$this->controls['navType'] = [
			'tab'   => 'content',
			'inline' => true,
			'label' => esc_html__( 'Navigation type', 'bricks' ),
			'type'  => 'select',
			'group'	   => 'navArrowGroup',
			'options' => [
				'prev' => esc_html__( 'Previous', 'bricks' ),
				'next' => esc_html__( 'Next', 'bricks' ),
			],
			'placeholder' => esc_html__( 'Previous', 'bricks' ),
			'required'    => [ 
				['controlType', '=', 'navArrow']
			],
		];

		$this->controls['buttonType'] = [
			'tab'   => 'content',
			'inline' => true,
			'label' => esc_html__( 'Button content', 'bricks' ),
			'type'  => 'select',
			'group'	   => 'navArrowGroup',
			'options' => [
				'icon' => esc_html__( 'Icon', 'bricks' ),
				'nest' => esc_html__( 'Nest elements', 'bricks' ),
			],
			'placeholder' => esc_html__( 'Icon', 'bricks' ),
			'required'    => [ 
				['controlType', '=', 'navArrow']
			],
		];

		$this->controls['prevIcon'] = [
			'label'    => esc_html__( 'Icon', 'bricks' ),
			'type'     => 'icon',
			'group'	   => 'navArrowGroup',
			'rerender' => true,
			'css'      => [
				[
					'selector' => '.x-slider-control_nav--prev .x-slider-control_nav-arrow > *',
				],
			],
			/*
			'default'  => [
				'library' => 'fontawesomeSolid',
				'icon'    => 'fas fa-angle-left',
			],
			*/
			'required'    => [ 
				['navType', '!=', 'next'],
				['controlType', '=', 'navArrow'],
				['buttonType', '!=', 'nest']
			],
		];

		

		$this->controls['nextIcon'] = [
			'label'    => esc_html__( 'Icon', 'bricks' ),
			'type'     => 'icon',
			'group'	   => 'navArrowGroup',
			'css'      => [
				[
					'selector' => '.x-slider-control_nav--next .x-slider-control_nav-arrow > *',
				],
			],
			/*
			'default'  => [
				'library' => 'fontawesomeSolid',
				'icon'    => 'fas fa-angle-right',
			],
			*/
			'required'    => [ 
				['navType', '=', 'next'],
				['controlType', '=', 'navArrow'],
				['buttonType', '!=', 'nest']
			],
		];

		$this->controls['prevAriaLabel'] = [
			'tab'         => 'content',
			'group'	   => 'navArrowGroup',
			'type'        => 'text',
			'label' => esc_html__( 'Aria label', 'bricks' ),
			'hasDynamicData' => false,
			'inline'      => true,
			'required'    => [ 
				['navType', '!=', 'next'],
				['controlType', '=', 'navArrow']
			],
			'placeholder' => 'Previous Slide'
		];

		$this->controls['nextAriaLabel'] = [
			'tab'         => 'content',
			'type'        => 'text',
			'group'	   => 'navArrowGroup',
			'label' => esc_html__( 'Aria label', 'bricks' ),
			'hasDynamicData' => false,
			'inline'      => true,
			'required'    => [ 
				['navType', '=', 'next'],
				['controlType', '=', 'navArrow']
			],
			'placeholder' => 'Next Slide'
		];

		$navButton = '.x-slider-control_nav';

		$this->controls['navbuttonSep'] = [
			'label'    => esc_html__( 'Button styles', 'bricks' ),
			'type'     => 'separator',
			'group'	   => 'navArrowGroup',
			'required'    => [ 
				['controlType', '=', 'navArrow'],
			],
    	];



    	$this->controls['navbuttonTypography'] = [
			'label'    => esc_html__( 'Typography', 'bricks' ),
			'type'     => 'typography',
			'group'	   => 'navArrowGroup',
			'css'      => [
				[
					'property' => 'font',
					'selector' => $navButton
				],
			],
		];

		$this->controls['navbuttonBorder'] = [
			'label'    => esc_html__( 'Border', 'bricks' ),
			'type'     => 'border',
			'group'	   => 'navArrowGroup',
			'css'      => [
				[
					'property' => 'border',
					'selector' => '.x-slider-control_nav'
				],
			],
		];

    	$this->controls['navbuttonBackground'] = [
			'label'    => esc_html__( 'Background', 'bricks' ),
			'type'     => 'background',
			'group'	   => 'navArrowGroup',
			'css'      => [
				[
					'property' => 'background',
					'selector' => '.x-slider-control_nav'
				],
			],
		];

    	$this->controls['navbuttonShadow'] = [
			'tab'    => 'content',
			'label'  => esc_html__( 'Box Shadow', 'extras' ),
			'type'   => 'box-shadow',
			'group'	   => 'navArrowGroup',
			'css'    => [
				[
					'property' => 'box-shadow',
					'selector' => $navButton
				],
			],
		];

		$this->controls['navButtonWidth'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Width', 'extras' ),
			'inline'      => true,
			'group'	   => 'navArrowGroup',
			'small'		=> true,
			'type' => 'number',
			'units'    => true,
			'css' => [
			  [
				'selector' => $navButton,
				'property' => 'width',
			  ],
			],
			'placeholder' => '',
		  ];

		  $this->controls['navButtonHeight'] = [
			'tab' => 'content',
			'group'	   => 'navArrowGroup',
			'label' => esc_html__( 'Height', 'extras' ),
			'inline'      => true,
			'small'		  => true,
			'type' => 'number',
			'units'    => true,
			'css' => [
			  [
				'selector' => $navButton,
				'property' => 'height',
			  ],
			],
			'placeholder' => '',
		  ];

		  $this->controls['navButtonOpacity'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Opacity', 'extras' ),
			'inline'      => true,
			'small'		  => true,
			'group'	   => 'navArrowGroup',
			'type' => 'number',
			'min' => 0,
			'max' => 1,
			'placeholder' => '1',
			'step' => '0.1',
			'css' => [
			  [
				'selector' => $navButton,
				'property' => 'opacity',
			  ],
			],
		  ];

		  $this->controls['navButtonOpacityDisabled'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Opacity (disabled)', 'extras' ),
			'inline'      => true,
			'small'		  => true,
			'group'	   => 'navArrowGroup',
			'type' => 'number',
			'min' => 0,
			'max' => 1,
			'step' => '0.1',
			'placeholder' => '0.35',
			'css' => [
			  [
				'selector' => $navButton . '[disabled]',
				'property' => 'opacity',
			  ],
			  [
				'selector' => $navButton . ':disabled',
				'property' => 'opacity',
			  ],
			],
			
		  ];

      $this->controls['navbuttonPadding'] = [
        'tab' => 'content',
        'label' => esc_html__( 'Padding', 'bricks' ),
        'group'	   => 'navArrowGroup',
        'type' => 'dimensions',
        'css' => [
          [
          'property' => 'padding',
          'selector' => $navButton
          ]
        ],
		'placeholder' => [
			'top' => '15px',
			'right' => '20px',
			'bottom' => '15px',
			'left' => '20px',
		  ],
      ];


	  /* autoplay progress 
	  */
	  $this->controls['autoplayProgressType'] = [
		'tab'   => 'content',
		'label' => esc_html__( 'Progress type', 'bricks' ),
		'placeholder' => esc_html__( 'Progress Bar', 'bricks' ),
		'type'  => 'select',
		'inline' => true,
		'group' => 'autoplayProgressGroup',
		'options' => [
			'progressBar' => esc_html__( 'Progress Bar', 'bricks' ),
			'circle' => esc_html__( 'Circle', 'bricks' ),
		]
	];

	/* autoplay circle */

	$this->controls['autoplayCircleWidth'] = [
		'tab'         => 'content',
		'label'    => esc_html__( 'Circle size', 'bricks' ),
		'type'     => 'number',
		'units'	   => true,
		'group' => 'autoplayProgressGroup',
		'placeholder' => '100px',
		'css' => [
			[
			  'selector' => '.x-slider-control_autoplay_container',  
			  'property' => 'width',
			],
			[
				'selector' => '.x-slider-control_autoplay_container',  
				'property' => 'height',
			],
		  ],
		'required' => [ 'autoplayProgressType', '=', ['circle']]
	 ];

	$this->controls['autoplayprogreslineSep'] = [
		'tab'         => 'content',
		'label'    => esc_html__( 'Progress line', 'bricks' ),
		'type'     => 'separator',
		'group' => 'autoplayProgressGroup',
		'required' => [ 'autoplayProgressType', '=', ['circle']]
	];

	$this->controls['autoplayprogressLineWidth'] = [
        'tab' => 'content',
        'label' => esc_html__( 'Line width', 'extras' ),
        'inline'      => true,
        'small'		  => true,
        'type' => 'number',
        'units'    => true,
        'placeholder' => '10',
        'css' => [
          [
            'selector' => '.x-slider-control_autoplay_circle circle',  
            'property' => '--x-autoplay-stroke-width',
          ],
        ],
		'group' => 'autoplayProgressGroup',
		'required' => [ 'autoplayProgressType', '=', ['circle']]
      ];

      $this->controls['autoplayprogressLineColor'] = [
        'tab' => 'content',
        'label' => esc_html__( 'Circle color', 'extras' ),
        'inline'      => true,
        'small'		  => true,
        'type' => 'color',
        'css' => [
          [
            'selector' => '.x-slider-control_autoplay_circle circle',  
            'property' => 'stroke',
          ],
        ],
		'group' => 'autoplayProgressGroup',
		'required' => [ 'autoplayProgressType', '=', ['circle']]
      ];

	  $this->controls['autoplayprogresBgSep'] = [
		'tab'         => 'content',
		'label'    => esc_html__( 'Circle', 'bricks' ),
		'type'     => 'separator',
		'group' => 'autoplayProgressGroup',
		'required' => [ 'autoplayProgressType', '=', ['circle']]
	];

	  $this->controls['autoplayprogressBgWidth'] = [
        'group' => 'progress',
        'tab' => 'content',
        'label' => esc_html__( 'Line width', 'extras' ),
        'inline'      => true,
        'small'		  => true,
        'type' => 'number',
        'units'    => true,
        'placeholder' => '10',
        'css' => [
          [
            'selector' => '.x-slider-control_autoplay_circle_bg circle',  
            'property' => '--x-autoplay-stroke-width',
          ],
        ],
		'group' => 'autoplayProgressGroup',
		'required' => [ 'autoplayProgressType', '=', ['circle']]
      ];

      $this->controls['autoplayprogressBgColor'] = [
        'group' => 'progress',
        'tab' => 'content',
        'label' => esc_html__( 'Circle color', 'extras' ),
        'inline'      => true,
        'small'		  => true,
        'type' => 'color',
        'css' => [
          [
            'selector' => '.x-slider-control_autoplay_circle_bg circle',  
            'property' => 'stroke',
          ],
        ],
		'group' => 'autoplayProgressGroup',
		'required' => [ 'autoplayProgressType', '=', ['circle']]
      ];

	  /* autoplay progress */

	  $this->controls['autoplayprogressBarBackground'] = [
		'label'    => esc_html__( 'Background', 'bricks' ),
		'type'     => 'color',
		'group'	   => 'autoplayProgressGroup',
		'css'      => [
			[
				'property' => 'background-color',
				'selector' => '.x-slider-control_autoplay_progress',
			],
		],
		'required' => [ 'autoplayProgressType', '!=', ['circle']]
	];

	$this->controls['autoplayprogressBarActiveBackground'] = [
		'label'    => esc_html__( 'Background (active)', 'bricks' ),
		'type'     => 'color',
		'group'	   => 'autoplayProgressGroup',
		'css'      => [
			[
				'property' => 'background-color',
				'selector' => '.x-slider-control_autoplay_progress-bar',
			],
		],
		'required' => [ 'autoplayProgressType', '!=', ['circle']]
	];

	$this->controls['autoplayprogressBarBorder'] = [
		'label'    => esc_html__( 'Border', 'bricks' ),
		'type'     => 'border',
		'group'	   => 'autoplayProgressGroup',
		'css'      => [
			[
				'property' => 'border',
				'selector' => '.x-slider-control_autoplay_progress',
			],
		],
		'required' => [ 'autoplayProgressType', '!=', ['circle']]
	];

	$this->controls['autoplayprogressBarThickness'] = [
		'label'    => esc_html__( 'Thickness', 'bricks' ),
		'type'     => 'number',
		'group'	   => 'autoplayProgressGroup',
		'units'    => true,
		'placeholder' => '4px',
		'css'      => [
			[
				'property' => 'height',
				'selector' => '.x-slider-control_autoplay_progress-bar',
			],
		],
		'required' => [ 'autoplayProgressType', '!=', ['circle']]
	];

	

	$this->controls['autoplayprogressDirection'] = [
		'label'       => esc_html__( 'Direction', 'bricks' ),
		'type'        => 'select',
		'group'	   => 'autoplayProgressGroup',
		'options'     => [
			'left' => esc_html__( 'Left to right', 'bricks' ),
			'right' => esc_html__( 'Right to left', 'bricks' ),
		],
		'inline'      => true,
		'placeholder' => esc_html__( 'Left to right', 'bricks' ),
		'required' => [ 'autoplayProgressType', '!=', ['circle']],
		'css'      => [
			[
				'property' => 'transform-origin',
				'selector' => '.x-slider-control_autoplay_progress-bar',
			],
		],
	];

      
	  /* slide content 
	  */

	  $this->controls['slideContentType'] = [
		'label'       => esc_html__( 'Content type', 'bricks' ),
		'type'        => 'select',
		'group'	   => 'slideContentGroup',
		'inline' => true,
		'options'     => [
			'caption' => esc_html__( 'Pro Slider Gallery Caption', 'bricks' ),
			'attribute' => esc_html__( 'Slide Data Attribute', 'bricks' ),
			'custom' => esc_html__( 'Custom element', 'bricks' ),
		],
		'placeholder' => esc_html__( 'Pro Slider Gallery Caption', 'bricks' ),
	];

	  $this->controls['slideContentSelector'] = [
		'tab'   => 'content',
		'label' => esc_html__( 'Element selector', 'bricks' ),
		'placeholder' => esc_html__( '.some-element', 'bricks' ),
		'type'  => 'text',
		'hasDynamicData' => false,
		'inline' => true,
		'group' => 'slideContentGroup',
		'required' => [ 'slideContentType', '=', 'custom' ],
	];

	$this->controls['slideAttribute'] = [
		'tab'   => 'content',
		'label' => esc_html__( 'Slide custom attribute', 'bricks' ),
		'placeholder' => esc_html__( 'data-x-content', 'bricks' ),
		'type'  => 'text',
		'hasDynamicData' => false,
		'inline' => true,
		'group' => 'slideContentGroup',
		'required' => [ 'slideContentType', '=', 'attribute' ],
	];


  }

  // Methods: Frontend-specific
  public function enqueue_scripts() {
	if (! \BricksExtras\Helpers::elementCSSAdded($this->name) ) {
		wp_enqueue_style( 'x-pro-slider-control', BRICKSEXTRAS_URL . 'components/assets/css/proslidercontrol.css', [], '' );
	}
  }
  
  public function render() {

    $settings = $this->settings;

    $controlType = isset( $settings['controlType'] ) ? $settings['controlType'] : 'progressBar';
	$slider = isset( $settings['slider'] ) ? $settings['slider'] : 'section';
    $sliderSelector = isset( $settings['sliderSelector'] ) ? esc_attr( $settings['sliderSelector'] ) : '';
    $slideSeperatorText = isset( $settings['slideSeperatorText'] ) ? esc_html__( $settings['slideSeperatorText'] ) : 'of';

    $slideTotalText = isset( $settings['slideTotalText'] ) ? esc_html__( $settings['slideTotalText'] ) : false;
    $slideTotalTextSuffix = isset( $settings['slideTotalTextSuffix'] ) ? esc_html__( $settings['slideTotalTextSuffix'] ) : false;
    
    $indexCurrentText = isset( $settings['indexCurrentText'] ) ? esc_html__( $settings['indexCurrentText'] ) : false;
    $indexCurrentTextSuffix = isset( $settings['indexCurrentTextSuffix'] ) ? esc_html__( $settings['indexCurrentTextSuffix'] ) : false;

    $progressDirection = isset( $settings['progressDirection'] ) ? $settings['progressDirection'] : 'ltr';

	$autoplayProgressType = isset( $settings['autoplayProgressType'] ) ? $settings['autoplayProgressType'] : 'progressBar';

	$slideContentSelector = isset( $settings['slideContentSelector'] ) ? $settings['slideContentSelector'] : '.some-element';
	$slideContentType = isset( $settings['slideContentType'] ) ? $settings['slideContentType'] : 'caption';
	$slideAttribute = isset( $settings['slideAttribute'] ) ? $settings['slideAttribute'] : 'data-x-content';
	$slideToGetContent = isset( $this->settings['slideToGetContent'] ) ? $this->settings['slideToGetContent'] : 'active';

	if ( 'section' === $slider ) {
		$sliderSelector = false;
	}

    $config = [
		'slider' => $sliderSelector,
      	'type' => $controlType
    ];

    if ( 'progressBar' === $controlType && isset( $settings['progressBarClickable'] ) ) {
		$config['progressBarClickable'] = true;
		$config['direction'] = $progressDirection;
	}

    if ( 'playPause' === $controlType ) {
      $config['playAriaLabel'] = isset( $this->settings['playAriaLabel'] ) ? esc_attr__( $this->settings['playAriaLabel'] ) : esc_attr__( 'Play autoplay' );
      $config['pauseAriaLabel'] = isset( $this->settings['pauseAriaLabel'] ) ? esc_attr__( $this->settings['pauseAriaLabel'] ) : esc_attr__( 'Pause autoplay' );
    }

	if ('autoplayProgress' === $controlType ) {
		$config['autoplay-progress'] = $autoplayProgressType;
	}

	if ('slideContent' === $controlType ) {
		$config['slideContentType'] = $slideContentType;
		$config['slideContentSelector'] = $slideContentSelector;
		$config['slideAttribute'] = $slideAttribute;
		$config['currentSlide'] = $slideToGetContent;
	}

	if ( method_exists('\Bricks\Query','is_any_looping') ) {

		$query_id = \Bricks\Query::is_any_looping();

		if ( $query_id ) {
			$config += [ 'isLooping' => \Bricks\Query::get_query_element_id( $query_id ) ];
		}

	} 

    $this->set_attribute( '_root', 'data-x-slider-control', wp_json_encode( $config ) );
    $this->set_attribute( '_root', 'class', 'x-slider-control' );

	$contentTag = isset( $this->settings['contentTag'] ) ? esc_attr( $this->settings['contentTag'] ) : 'div';

	echo 'slideContent' === $controlType ? "<" . $contentTag . " {$this->render_attributes( '_root' )} >" : "<div {$this->render_attributes( '_root' )} >";

    if ('progressBar' === $controlType ) {

	  $this->set_attribute( 'x-slider_progress', 'class', 'x-slider_progress' );
      $this->set_attribute( 'x-slider_progress-bar', 'class', 'x-slider_progress-bar' );

	  $this->set_attribute( 'x-slider_progress', 'aria-valuemin', '1' ); 
    

        
	if ( isset( $settings['progressBarClickable'] ) ) {

		$this->set_attribute( 'x-slider_progress', 'role', 'slider' );
        $this->set_attribute( 'x-slider_progress', 'tabindex', '0' );
		
     } else {
		$this->set_attribute( 'x-slider_progress', 'role', 'progressbar' );
	 }

	 $progressBarAriaLabel = isset( $this->settings['progressBarAriaLabel'] ) ? esc_attr__( $this->settings['progressBarAriaLabel'] ) : esc_attr__( 'Slider progress' );
	 $this->set_attribute( 'x-slider_progress', 'aria-label', $progressBarAriaLabel );
      
      
      echo "<div {$this->render_attributes( 'x-slider_progress' )}>";
      echo "<div {$this->render_attributes( 'x-slider_progress-bar' )}>";
      echo "</div>";
      echo "</div>";

    } elseif ('counter' === $controlType ) {

      $this->set_attribute( 'x-slider_counter', 'class', 'x-slider_counter' );
      $this->set_attribute( 'x-slider_counter-seperator', 'class', 'x-slider_counter-seperator' );

      $this->set_attribute( 'x-slider_counter-total', 'class', 'x-slider_counter-total' );
      $this->set_attribute( 'x-slider_counter-total-prefix', 'class', 'x-slider_counter-total-prefix' );
      $this->set_attribute( 'x-slider_counter-total-suffix', 'class', 'x-slider_counter-total-suffix' );

      $this->set_attribute( 'x-slider_counter-index', 'class', 'x-slider_counter-index' );
      $this->set_attribute( 'x-slider_counter-index-prefix', 'class', 'x-slider_counter-index-prefix' );
      $this->set_attribute( 'x-slider_counter-index-suffix', 'class', 'x-slider_counter-index-suffix' );

      echo "<div {$this->render_attributes( 'x-slider_counter' )}>";
        echo "<div {$this->render_attributes( 'x-slider_counter-index' )}>";
        echo $indexCurrentText ? "<div {$this->render_attributes( 'x-slider_counter-index-prefix' )}>" . $indexCurrentText . "</div>" : '';
        echo "<div class='x-slider_counter-index-number'></div>";
        echo $indexCurrentTextSuffix ? "<div {$this->render_attributes( 'x-slider_counter-index-suffix' )}>" . $indexCurrentTextSuffix . "</div>" : '';
      echo" </div>";

      echo "<div {$this->render_attributes( 'x-slider_counter-seperator' )}> " . $slideSeperatorText . " </div>";

      echo "<div {$this->render_attributes( 'x-slider_counter-total' )}>";
        echo $slideTotalText ? "<div {$this->render_attributes( 'x-slider_counter-total-prefix' )}>" . $slideTotalText . "</div>" : '';
        echo "<div class='x-slider_counter-total-number'></div>";
        echo $slideTotalTextSuffix ? "<div {$this->render_attributes( 'x-slider_counter-total-suffix' )}>" . $slideTotalTextSuffix . "</div>" : '';
        echo "</div>";
      echo "</div>";
      
    } elseif ('playPause' === $controlType ) {

      echo $this->render_button();

    } elseif ('navArrow' === $controlType ) {

		echo $this->render_nav();

	} elseif ('autoplayProgress' === $controlType ) {

		echo $this->autoplayProgress();

	} elseif ('slideContent' === $controlType ) {
		

		echo "<span class=x-slider-control_content></span>";

	}


	echo 'slideContent' === $controlType ? "</" . $contentTag . ">" : "</div>";

    
  }

  	/**
	 * Render button
	 */
	public function autoplayProgress() {

		$output = '';

		$autoplayProgressType = isset( $this->settings['autoplayProgressType'] ) ? $this->settings['autoplayProgressType'] : 'progressBar';

		if ( 'circle' === $autoplayProgressType ) {

			$output .= '<div class=x-slider-control_autoplay_container>';

			$output .= '<svg class="x-slider-control_autoplay_circle_bg" viewBox="0 0 500 500" xmlns="http://www.w3.org/2000/svg"><circle cx="250" cy="250" r="245" style="opacity: 1; stroke-dashoffset: 0; stroke-dasharray: 1571.8px, 1581.8px;"></circle></svg>';
			$output .= '<svg class="x-slider-control_autoplay_circle" viewBox="0 0 500 500" xmlns="http://www.w3.org/2000/svg"><circle cx="250" cy="250" r="245" style="opacity: 1; stroke-dashoffset: 874.617; stroke-dasharray: 1571.8px, 1581.8px;"></circle></svg>';
			
			$output .= '<div class=x-slider-control_autoplay_inner>';
			$output .= \Bricks\Frontend::render_children( $this );
			$output .= '</div>';
			$output .= '</div>';

		} else {
			$output .= '<div class="x-slider-control_autoplay_progress" role="presentation">';
			$output .= '<div class="x-slider-control_autoplay_progress-bar"></div>';
			$output .= '</div>';
		}

		return $output;

	}

  	/**
	 * Render button
	 */
	public function render_nav() {

	  $prevIcon = ! empty( $this->settings['prevIcon'] ) ? self::render_icon( $this->settings['prevIcon'] ) : false;
	  $nextIcon = ! empty( $this->settings['nextIcon'] ) ? self::render_icon( $this->settings['nextIcon'] ) : false;


	  $navType = isset( $this->settings['navType'] ) ? esc_attr($this->settings['navType']) : 'prev';
	  $arrow = 'next' !== $navType ? $prevIcon : $nextIcon;
	  $buttonType = isset( $this->settings['buttonType'] ) ? $this->settings['buttonType'] : 'icon';

	  $prevAriaLabel = isset( $this->settings['prevAriaLabel'] ) ? esc_attr__( $this->settings['prevAriaLabel'] ) : esc_attr__( 'Previous Slide');
	  $nextAriaLabel = isset( $this->settings['nextAriaLabel'] ) ? esc_attr__( $this->settings['nextAriaLabel'] ) : esc_attr__( 'Next Slide' );

	  $navAriaLabel = 'next' !== $navType ? $prevAriaLabel : $nextAriaLabel;

      $this->set_attribute( 'x-slider-control_nav', 'class', ['x-slider-control_nav','x-slider-control_nav--' . $navType] );
	  $this->set_attribute( 'x-slider-control_nav-arrow', 'class', ['x-slider-control_nav-arrow'] );
      $this->set_attribute( 'x-slider-control_nav', 'type', 'button' );
	  $this->set_attribute( 'x-slider-control_nav', 'disabled', '' ); /* disabled by default */
      $this->set_attribute( 'x-slider-control_nav', 'aria-label', $navAriaLabel );

      $output = "<button {$this->render_attributes( 'x-slider-control_nav' )}>";

      if ( 'next' !== $navType && 'icon' === $buttonType) {
        $output .= $prevIcon ? "<span {$this->render_attributes( 'x-slider-control_nav-arrow' )}>" . $prevIcon . "</span>" : "<span {$this->render_attributes( 'x-slider-control_nav-arrow' )}><svg class='x-slider-control_nav-arrow-default' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 320 512'><path d='M41.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l160 160c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L109.3 256 246.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-160 160z'/></svg></span>";
      }

	  if ( 'next' === $navType && 'icon' === $buttonType) {
        $output .= $nextIcon ? "<span {$this->render_attributes( 'x-slider-control_nav-arrow' )}>" . $nextIcon . "</span>" : "<span {$this->render_attributes( 'x-slider-control_nav-arrow' )}><svg class='x-slider-control_nav-arrow-default' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 320 512'><path d='M278.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-160 160c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L210.7 256 73.4 118.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l160 160z'/></svg></span>";
      }

	  if ( 'nest' === $buttonType) {
		$output .= \Bricks\Frontend::render_children( $this );
	  }

      $output .=  "</button>";

	  return $output;

	}

	/**
	 * Render play button
	 */
	public function render_button() {

		$playIcon = ! empty( $this->settings['playIcon'] ) ? self::render_icon( $this->settings['playIcon'] ) : false;
		$pauseIcon = ! empty( $this->settings['pauseIcon'] ) ? self::render_icon( $this->settings['pauseIcon'] ) : false;
		

      $this->set_attribute( 'x-splide__toggle', 'class', ['x-splide__toggle','splide__toggle'] );
      $this->set_attribute( 'x-splide__toggle', 'type', 'button' );
      $this->set_attribute( 'x-splide__toggle__play', 'class', ['x-splide__toggle__play','splide__toggle__play'] );
      $this->set_attribute( 'x-splide__toggle__pause', 'class', ['x-splide__toggle__pause','splide__toggle__pause'] );

      $output = "<button {$this->render_attributes( 'x-splide__toggle' )}>";

      $output .= $playIcon ? "<span {$this->render_attributes( 'x-splide__toggle__play' )}>" . $playIcon . "</span>" : "<span {$this->render_attributes( 'x-splide__toggle__play' )}><svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 512 512'><path d='M128 104.3v303.4c0 6.4 6.5 10.4 11.7 7.2l240.5-151.7c5.1-3.2 5.1-11.1 0-14.3L139.7 97.2c-5.2-3.3-11.7.7-11.7 7.1z' fill='currentColor'/></svg></span>";
      $output .= $pauseIcon ? "<span {$this->render_attributes( 'x-splide__toggle__pause' )}>" . $pauseIcon . "</span>" : "<span {$this->render_attributes( 'x-splide__toggle__pause' )}><svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 512 512'><path d='M199.9 416h-63.8c-4.5 0-8.1-3.6-8.1-8V104c0-4.4 3.6-8 8.1-8h63.8c4.5 0 8.1 3.6 8.1 8v304c0 4.4-3.6 8-8.1 8z' fill='currentColor'/><path d='M375.9 416h-63.8c-4.5 0-8.1-3.6-8.1-8V104c0-4.4 3.6-8 8.1-8h63.8c4.5 0 8.1 3.6 8.1 8v304c0 4.4-3.6 8-8.1 8z' fill='currentColor'/></svg></span>";

      $output .=  "</button>";

	  return $output;
	}

  public static function render_builder() { 
	
	$initProSlider = false;

	/* allow for init if prefered */
	$initProSlider = apply_filters( 'bricksextras/proslider/builderinit', $initProSlider );

	if ( !$initProSlider ) {
	
	?>
			

		<script type="text/x-template" id="tmpl-bricks-element-xproslidercontrol">
			
    		<component
				class="x-slider-control x-slider-control_builder"
				:class="'autoplayProgess' !== settings.controlType ? 'autoplayProgress' : ''" 
				:data-x-slider-control="'counter' !== settings.controlType && 'playPause' !== settings.controlType && 'navArrow' !== settings.controlType && 'autoplayProgress' !== settings.controlType && 'slideContent' !== settings.controlType ? 'progressBarClickable' : ''"
				:data-x-autoplay-progress="'autoplayProgress' === settings.controlType ? 'circle' === settings.autoplayProgressType ? 'autoplayProgressCircle' : 'autoplayProgressBar' : ''"
				>

          <div v-if="'counter' !== settings.controlType && 'playPause' !== settings.controlType && 'navArrow' !== settings.controlType && 'autoplayProgress' !== settings.controlType && 'slideContent' !== settings.controlType" class="x-slider_progress">
            <div class="x-slider_progress-bar" :class="settings.progressDirection"></div>
          </div>

          <div v-if="'counter' === settings.controlType" class="x-slider_counter">
            <div class="x-slider_counter-index">
            
            <contenteditable
              tag="div"
              class="x-slider_counter-index-prefix"
              :name="name"
              placeholder= ""
              controlKey="indexCurrentText"
              toolbar="style align"
              :settings="settings"
            />
            <div class="x-slider_counter-index-number">X</div>
            <contenteditable
              tag="div"
              class="x-slider_counter-index-suffix"
              :name="name"
              placeholder= ""
              controlKey="indexCurrentTextSuffix"
              toolbar="style align"
              :settings="settings"
            />
            </div>
            <contenteditable
              v-if="settings.slideSeperatorText"
              tag="div"
              class="x-slider_counter-seperator"
              :name="name"
              placeholder= ""
              controlKey="slideSeperatorText"
              toolbar="style align"
              :settings="settings"
            />
            <div v-else class="x-slider_counter-seperator">of</div>
            <div class="x-slider_counter-total">
              <contenteditable
                tag="div"
                class="x-slider_counter-total-prefix"
                :name="name"
                placeholder= ""
                controlKey="slideTotalText"
                toolbar="style align"
                :settings="settings"
              />
              <div class="x-slider_counter-total-number">Y</div>
              <contenteditable
                tag="div"
                class="x-slider_counter-total-suffix"
                :name="name"
                placeholder= ""
                controlKey="slideTotalTextSuffix"
                toolbar="style align"
                :settings="settings"
              />
            </div>
          </div>

          <button 
            v-if="'playPause' === settings.controlType" 
            class="x-splide__toggle splide__toggle"
            @click="is_active = !is_active"
            :class="is_active ? 'is-active' : ''" 
          >
          <span class="x-splide__toggle__play splide__toggle__play">
          <icon-svg v-if="settings.playIcon"
			:iconSettings="settings.playIcon"
			/>
			<svg v-else xmlns='http://www.w3.org/2000/svg' viewBox='0 0 512 512'><path d='M128 104.3v303.4c0 6.4 6.5 10.4 11.7 7.2l240.5-151.7c5.1-3.2 5.1-11.1 0-14.3L139.7 97.2c-5.2-3.3-11.7.7-11.7 7.1z'/></svg>
          </span>
          <span class="x-splide__toggle__pause splide__toggle__pause">
          <icon-svg v-if="settings.pauseIcon"
				:iconSettings="settings.pauseIcon"
			/>
			<svg v-else xmlns='http://www.w3.org/2000/svg' viewBox='0 0 512 512'><path d='M199.9 416h-63.8c-4.5 0-8.1-3.6-8.1-8V104c0-4.4 3.6-8 8.1-8h63.8c4.5 0 8.1 3.6 8.1 8v304c0 4.4-3.6 8-8.1 8z' fill='currentColor'/><path d='M375.9 416h-63.8c-4.5 0-8.1-3.6-8.1-8V104c0-4.4 3.6-8 8.1-8h63.8c4.5 0 8.1 3.6 8.1 8v304c0 4.4-3.6 8-8.1 8z'/></svg>
          </span>
          </button>  

		  <button 
            v-if="'navArrow' === settings.controlType" 
            class="x-slider-control_nav"
			:class="'next' === settings.navType ? 'x-slider-control_nav--next' : 'x-slider-control_nav--prev'" 
          >
          <span v-if="'nest' !== settings.buttonType" class="x-slider-control_nav-arrow">
          	<icon-svg 
				v-if="'next' !== settings.navType && settings.prevIcon" 
				:iconSettings="settings.prevIcon" /
			>
			<svg class="x-slider-control_nav-arrow-default" v-else-if="'next' !== settings.navType && !settings.prevIcon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M41.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l160 160c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L109.3 256 246.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-160 160z"/></svg>
			<icon-svg 
				v-else-if="'next' === settings.navType && settings.nextIcon" 
				:iconSettings="settings.nextIcon" />
			<svg class="x-slider-control_nav-arrow-default" v-else-if="'next' === settings.navType && !settings.nestIcon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M278.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-160 160c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L210.7 256 73.4 118.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l160 160z"/></svg>
          </span>

		  <bricks-element-children v-if="'nest' === settings.buttonType" :element="element" />
          </button>  

		  <div 
		  	v-if="'autoplayProgress' === settings.controlType && 'circle' === settings.autoplayProgressType"
            class="x-slider-control_autoplay_container"
          >
			<svg v-if="'autoplayProgress' === settings.controlType && 'circle' === settings.autoplayProgressType" class="x-slider-control_autoplay_circle_bg" viewBox="0 0 500 500" xmlns="http://www.w3.org/2000/svg"><circle cx="250" cy="250" r="245" style="opacity: 1; stroke-dashoffset: 0; stroke-dasharray: 1571.8px, 1581.8px;"></circle></svg>
			<svg v-if="'autoplayProgress' === settings.controlType && 'circle' === settings.autoplayProgressType" class="x-slider-control_autoplay_circle" viewBox="0 0 500 500" xmlns="http://www.w3.org/2000/svg"><circle cx="250" cy="250" r="245" style="opacity: 1; stroke-dashoffset: 1174.617!important; stroke-dasharray: 1571.8px, 1581.8px;"></circle></svg> 
			<div v-if="'autoplayProgress' === settings.controlType && 'circle' === settings.autoplayProgressType" class=x-slider-control_autoplay_inner>
			<bricks-element-children v-if="'autoplayProgress' === settings.controlType && 'circle' === settings.autoplayProgressType" :element="element" />
			</div>
          </div> 
		  
		  <div 
		  	v-if="'autoplayProgress' === settings.controlType && 'circle' !== settings.autoplayProgressType"
            class="x-slider-control_autoplay_progress"
          >
		  <div 
		    v-if="'autoplayProgress' === settings.controlType && 'circle' !== settings.autoplayProgressType"
			class="x-slider-control_autoplay_progress-bar"
			style="transform: scaleX(0.3);"
		  >
		  </div>
		  </div>

		  <div 
		  	v-if="'slideContent' === settings.controlType"
			class="x-slider-control_content"
          > Content here
			</div>

   		 </component>      

		</script>

	<?php } 
	
	}

}