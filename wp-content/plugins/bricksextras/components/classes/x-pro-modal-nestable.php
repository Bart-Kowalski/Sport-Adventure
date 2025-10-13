<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class X_Pro_Modal_Nestable extends \Bricks\Element {

  // Element properties
  public $category     = 'extras';
  public $name         = 'xpromodalnestable';
  public $icon         = 'ti-layout-media-overlay';
  public $css_selector = '.x-modal_content';
  public $nestable = true;

  // Methods: Builder-specific
  public function get_label() {
	return esc_html__( 'Modal', 'extras' );
  }
  public function set_control_groups() {

	$this->control_groups['trigger'] = [
		'title' => esc_html__( 'Trigger / Reshow', 'extras' ),
		'tab' => 'content',
	];

	$this->control_groups['positioning'] = [
		'title' => esc_html__( 'Size / positioning', 'extras' ),
		'tab' => 'content',
	];

	$this->control_groups['inner_layout'] = [
		'title' => esc_html__( 'Inner layout', 'extras' ),
		'tab' => 'content',
	];

	$this->control_groups['close_button'] = [
		'title' => esc_html__( 'Close button', 'extras' ),
		'tab' => 'content',
		'required' => ['maybe_remove_close', '!=', true]
	];

	$this->control_groups['interaction'] = [
		'title' => esc_html__( 'Interaction / Accessibility', 'extras' ),
		'tab' => 'content',
	];

	$this->control_groups['animation'] = [
		'title' => esc_html__( 'Animation', 'extras' ),
		'tab' => 'content',
	];

  }
  public function set_controls() {

	$this->controls['builderHidden'] = [
		'tab'   => 'content',
		'inline' => true,
		'small' => true,
		//'default' => true,
		'label' => esc_html__( 'Hide in builder', 'bricks' ),
		'type'  => 'checkbox',
	];

	$this->controls['builderHidden_sep'] = [
		'tab'   => 'content',
		'type'  => 'separator',
	  ];

	 

	  /* backdrop */

	 $this->controls['backdrop_color'] = [
		'tab' => 'content',
		'label' => esc_html__( 'Backdrop color', 'bricks' ),
		'type' => 'color',
		//'group' => 'backdrop',
		'inline' => true,
		'css' => [
		  [
			'property' => 'background-color',
			'selector' => '.x-modal_backdrop',
		  ]
		],
	  ];

	  $this->controls['disableScroll'] = [
		'tab'   => 'content',
		'inline' => true,
		'small' => true,
		//'default' => true,
		'label' => esc_html__( 'Disable scroll when open', 'bricks' ),
		'type'  => 'checkbox',
	];


	  $this->controls['maybe_remove_close'] = [
		'tab' => 'content',
		'label' => esc_html__( 'Remove default close button', 'bricks' ),
		'type'  => 'checkbox',
		//'group' => 'close_button',
		'description' => esc_html__( '(To add a custom close button, add the attribute data-x-modal-close to your button inside the modal', 'bricks' ),
	  ];

	 


	$this->controls['triggers'] = [
		'tab' => 'content',
		'group' => 'trigger',
		'label' => esc_html__( 'Triggers to show modal', 'bricks' ),
		'type' => 'repeater',
		'titleProperty' => 'label',
		'placeholder' => esc_html__( 'Trigger', 'bricks' ),
		'fields' => [
		  'label'      => [
			'type'   => 'text',
			'label'  => esc_html__( 'Name', 'bricks' ),
			'placeholder' => esc_html__( 'Add friendly name for trigger', 'bricks' ),
			],
		  'type' => [
			'label' => esc_html__( 'Trigger type', 'bricks' ),
			'type' => 'select',
			'options' => [
				'pageLoad' => esc_html__( 'On page load (after x milliseconds)', 'bricks' ),
				'pageLoadURLParameter' => esc_html__( 'On page load (Incl. URL parameter)', 'bricks' ),
				'scroll' => esc_html__( 'On scroll number of px', 'bricks' ),
				'scrolledToElement' => esc_html__( 'On scroll to element', 'bricks' ),
				'exitIntent' => esc_html__( 'On page exit intent', 'bricks' ),
				'pageViews' => esc_html__( 'After number of page views', 'bricks' ),
				'timeInactive' => esc_html__( 'After time inactive', 'bricks' ),
				'elementClick' => esc_html__( 'On click element', 'bricks' ),
				'elementHover' => esc_html__( 'On hover element', 'bricks' ),
			]
		  ],
		  'selector' => [
			'label' => esc_html__( 'Element selector', 'bricks' ),
			'type' => 'text',
			'hasDynamicData' => false,
			'placeholder' => esc_html__( '.your-class', 'bricks' ),
			'required' => ['type', '=', [
				'scrolledToElement',
				'elementClick',
				'elementHover'
			 ]
			],
		  ],
		  'aria_controls' => [
			'tab'   => 'content',
			'small' => true,
			'label' => esc_html__( "Auto-add aria attributes to trigger", 'bricks' ),
			'type'  => 'checkbox',
			'required' => ['type', '=', [
				'elementClick',
			 ]
			],
		  ],

		  'delay' => [
			'label' => esc_html__( 'Delay (ms)', 'bricks' ),
			'type' => 'number',
			'required' => ['type', '=', [
				'pageLoad',
				'pageLoadURLParameter',
				'elementClick',
				'elementHover',
				'scrolledToElement'
			 ]
			],
		  ],
		  'query_includes' => [
			'label' => esc_html__( 'URL parameter includes', 'bricks' ),
			'type' => 'text',
			'placeholder' => esc_html__( 'referrer=facebook', 'bricks' ),
			'required' => ['type', '=', [
				'pageLoadURLParameter',
			 ]
			],
		  ],
		  'views' => [
			'label' => esc_html__( 'Views', 'bricks' ),
			'type' => 'number',
			'required' => ['type', '=', [
				'pageViews',
			 ]
			],
		  ],
		  'inactive_time' => [
			'label' => esc_html__( 'Inactive time (s)', 'bricks' ),
			'type' => 'number',
			'units' => false,
			'required' => ['type', '=', [
				'timeInactive',
			 ]
			],
		  ],
		  'amount' => [
			'label' => esc_html__( 'Scroll amount (px)', 'bricks' ),
			'type' => 'number',
			'unit' => 'px',
			'required' => ['type', '=', [
				'scroll',
			 ]
			],
		  ],
		],
	  ];


	  $this->controls['trigger_sep'] = [
		'tab'   => 'content',
		'type'  => 'separator',
		'group' => 'trigger',
		//'label' => esc_html__( 'Style button', 'bricks' ),
		];  
	
		$this->controls['show_again'] = [
			'tab'         => 'content',
			'label'       => esc_html__( 'Reshow modal', 'bricks' ),
			'type'        => 'select',
			'group' => 'trigger',
			'options'     => [
				'page_load' => esc_html__( 'Show again on next visit', 'bricks' ),
				'never' => esc_html__( 'Never show again', 'bricks' ),
				'after' => esc_html__( 'Only allow to show after:', 'bricks' ),
				'evergreen' => esc_html__( "Only show if evergreen countdown hasn't ended", 'bricks' ),
			],
			//'inline'      => true,
			'placeholder'   => esc_html__( 'Show again on next visit', 'bricks' )
		];
	
		$this->controls['show_again_days'] = [
			'tab' => 'content',
			'group' => 'trigger',
			'label' => esc_html__( 'Days', 'bricks' ),
			'type' => 'number',
			'inline' => true,
			'required' => ['show_again', '=', ['after']]
		  ];
	
		  $this->controls['show_again_hours'] = [
			'tab' => 'content',
			'group' => 'trigger',
			'label' => esc_html__( 'Hours', 'bricks' ),
			'type' => 'number',
			'inline' => true,
			'required' => ['show_again', '=', ['after']]
		  ];

		  $this->controls['show_once'] = [
			'tab'   => 'content',
			'group' => 'trigger',
			'small' => true,
			//'default' => true,
			'label' => esc_html__( 'Only show once per page visit', 'bricks' ),
			'type'  => 'checkbox',
			'required' => ['show_once_session', '!=', true]
		];
	
		$this->controls['show_once_session'] = [
			'tab'   => 'content',
			'group' => 'trigger',
			'small' => true,
			//'default' => true,
			'label' => esc_html__( 'Only show once per session', 'bricks' ),
			'type'  => 'checkbox',
			'required' => ['show_once', '!=', true]
		];

	


	/* Positioning */

	$this->controls['modal_width'] = [
		'tab' => 'content',
		'label' => esc_html__( 'Content width', 'extras' ),
		'small'	=> true,
		'inline' => true,
		'type' => 'number',
		'group' => 'positioning',
		'units'    => true,
		'css' => [
		  [
			'selector' => '.x-modal_container',  
			'property' => 'width',
		  ],
		],
		'placeholder' => '600px'
	  ];

	$this->controls['vertical_align'] = [ 
		'tab'   => 'content',
		'label' => esc_html__( 'Vertical position', 'bricks' ),
		'placeholder' => esc_html__( 'Center', 'bricks' ),
		'group' => 'positioning',
		'type'  => 'select',
		'options' => [
			'top' => esc_html__( 'Top', 'bricks' ),
			'middle' => esc_html__( 'Center', 'bricks' ),
			'bottom' => esc_html__( 'Bottom', 'bricks' ),
		  ],
		  'css'   => [
			[
			  'property' => 'vertical-align',
			  'selector' => '.x-modal_container',
			],
		  ],
		  'inline' => true,
	];

	$this->controls['horizontal_align'] = [ // Setting key
		'tab'   => 'content',
		'label' => esc_html__( 'Horizontal position', 'bricks' ),
		'placeholder' => esc_html__( 'Center', 'bricks' ),
		'group' => 'positioning',
		'type'  => 'select',
		'css'   => [
		  [
			'property' => 'text-align',
			'selector' => '.x-modal_backdrop',
		  ],
		],
		'options' => [
			'right' => esc_html__( 'Right', 'bricks' ),
			'center' => esc_html__( 'Center', 'bricks' ),
			'left' => esc_html__( 'Left', 'bricks' ),
		  ],
		  'inline' => true,
	];

	$this->controls['content_padding'] = [
		'tab' => 'content',
		'label' => esc_html__( 'Content padding', 'bricks' ),
		'type' => 'dimensions',
		'group' => 'positioning',
		'css' => [
		  [
			'property' => 'padding',
			'selector' => '.x-modal_content',
		  ]
		],
		'placeholder' => [
		  'top' => '30px',
		  'right' => '30px',
		  'bottom' => '30px',
		  'left' => '30px',
		],
	  ];

	$this->controls['padding'] = [
		'tab' => 'content',
		'label' => esc_html__( 'Space from edge of viewport', 'bricks' ),
		'type' => 'dimensions',
		'group' => 'positioning',
		'css' => [
		  [
			'property' => 'padding',
			'selector' => '.x-modal_backdrop',
		  ]
		],
		'placeholder' => [
		  'top' => '20px',
		  'right' => '20px',
		  'bottom' => '20px',
		  'left' => '20px',
		],
	  ];



	  /* inner layout */

	  $innerContent = '.x-modal_content';

	  // Display

		$this->controls['_flexWrap'] = [
			'tab'         => 'content',
			'label'       => esc_html__( 'Flex wrap', 'bricks' ),
			'group'		  => 'inner_layout',
			'tooltip'     => [
				'content'  => 'flex-wrap',
				'position' => 'top-left',
			],
			'type'        => 'select',
			'options'  => [
				'nowrap'       => esc_html__( 'No wrap', 'bricks' ),
				'wrap'         => esc_html__( 'Wrap', 'bricks' ),
				'wrap-reverse' => esc_html__( 'Wrap reverse', 'bricks' ),
			],
			'inline'      => true,
			'css'         => [
				[
					'property' => 'flex-wrap',
					'selector' => $innerContent,
				],
			],
			'placeholder' => esc_html__( 'No wrap', 'bricks' ),
			//'required'    => [ '_display', '=', 'flex' ],
		];

		$this->controls['_direction'] = [
			'tab'      => 'content',
			'label'    => esc_html__( 'Direction', 'bricks' ),
			'group'		  => 'inner_layout',
			'tooltip'  => [
				'content'  => 'flex-direction',
				'position' => 'top-left',
			],
			'type'     => 'direction',
			'css'      => [
				[
					'property' => 'flex-direction',
					'selector' => $innerContent,
				],
			],
			'inline'   => true,
			'rerender' => true,
			//'required' => [ '_display', '=', 'flex' ],
		];

		$this->controls['_justifyContent'] = [
			'tab'      => 'content',
			'label'    => esc_html__( 'Align main axis', 'bricks' ),
			'group'		  => 'inner_layout',
			'tooltip'  => [
				'content'  => 'justify-content',
				'position' => 'top-left',
			],
			'type'     => 'justify-content',
			'css'      => [
				[
					'property' => 'justify-content',
					'selector' => $innerContent,
				],
			],
			//'required' => [ '_display', '=', 'flex' ],
		];

		$this->controls['_alignItems'] = [
			'tab'      => 'content',
			'label'    => esc_html__( 'Align cross axis', 'bricks' ),
			'group'		  => 'inner_layout',
			'tooltip'  => [
				'content'  => 'align-items',
				'position' => 'top-left',
			],
			'type'     => 'align-items',
			'css'      => [
				[
					'property' => 'align-items',
					'selector' => $innerContent,
				],
			],
			//'required' => [ '_display', '=', 'flex' ],
		];

		$this->controls['_columnGap'] = [
			'tab'      => 'content',
			'label'    => esc_html__( 'Column gap', 'bricks' ),
			'group'		  => 'inner_layout',
			'type'     => 'number',
			'units'    => true,
			'css'      => [
				[
					'property' => 'column-gap',
					'selector' => $innerContent,
				],
			],
			'info'     => sprintf( __( 'Current browser support: %s (no IE). Use margins for max. browser support.', 'bricks' ), '89%' ),
			//'required' => [ '_display', '=', 'flex' ],
		];

		$this->controls['_rowGap'] = [
			'tab'      => 'content',
			'label'    => esc_html__( 'Row gap', 'bricks' ),
			'group'		  => 'inner_layout',
			'type'     => 'number',
			'units'    => true,
			'css'      => [
				[
					'property' => 'row-gap',
					'selector' => $innerContent,
				],
			],
			'info'     => sprintf( __( 'Current browser support: %s (no IE). Use margins for max. browser support.', 'bricks' ), '89%' ),
			//'required' => [ '_display', '=', 'flex' ],
		];


	  /* close button */

	  $button_selector = '.x-modal_close';

	

	  $this->controls['close_icon'] = [
        'tab'      => 'content',
		'group' => 'close_button',
        'label'    => esc_html__( 'Close icon', 'bricks' ),
        'type'     => 'icon',
        'css'      => [
          [
            'selector' => '.x-modal_close svg',
          ],
        ],
        'default'  => [
          'library' => 'themify',
          'icon'    => 'ti-close',
        ],
		//'required' => ['maybe_remove_close', '!=', true]
      ];

	  $this->controls['button_text'] = [
		'tab' => 'content',
		'label' => esc_html__( 'Close text', 'bricks' ),
		'type' => 'text',
		'group' => 'close_button',
		'placeholder' => '',
		//'required' => ['maybe_remove_close', '!=', true]
	  ];

	  $this->controls['aria_label'] = [
		'tab' => 'content',
		'label' => esc_html__( 'Aria label', 'bricks' ),
		'type' => 'text',
		'group' => 'close_button',
		'placeholder' => esc_html__( 'Close modal', 'bricks' ),
		//'required' => ['maybe_remove_close', '!=', true]
	  ];


	  $this->controls['button_sep'] = [
		'tab'   => 'content',
		'group' => 'close_button',
		'type'  => 'separator',
		'label' => esc_html__( 'Style button', 'bricks' ),
		//'required' => ['maybe_remove_close', '!=', true]
	  ];

		$this->controls['buttonTypography'] = [
			'tab'    => 'content',
			'group'    => 'close_button',
			'type'   => 'typography',
			'label'  => esc_html__( 'Typography', 'bricks' ),
			'css'    => [
				[
					'property' => 'font',
					'selector' => $button_selector,
				],
			],
			//'required' => ['maybe_remove_close', '!=', true]
		];

		$this->controls['iconSize'] = [
			'tab'      => 'content',
			'group'    => 'close_button',
			'label'    => esc_html__( 'Icon size', 'bricks' ),
			'type'     => 'number',
			'units'    => true,
			'css'      => [
				[
					'property' => 'font-size',
					'selector' => '.x-modal_close-icon',
				],
			],
			//'required' => ['maybe_remove_close', '!=', true]
		];

		$this->controls['iconColor'] = [
			'tab'      => 'content',
			'group' => 'close_button',
			'label'    => esc_html__( 'Color', 'bricks' ),
			'type'     => 'color',
			'css'      => [
				[
					'property' => 'color',
					'selector' => $button_selector,
				],
			],
			//'required' => ['maybe_remove_close', '!=', true]
		];

		$this->controls['iconBackgroundColor'] = [
			'tab'   => 'content',
			'group' => 'close_button',
			'label' => esc_html__( 'Background color', 'bricks' ),
			'type'  => 'color',
			'css'   => [
				[
					'property' => 'background-color',
					'selector' => $button_selector,
				],
			],
			//'required' => ['maybe_remove_close', '!=', true]
		];

		$this->controls['iconBorder'] = [
			'tab'   => 'content',
			'label' => esc_html__( 'Border', 'bricks' ),
			'group' => 'close_button',
			'type'  => 'border',
			'css'   => [
				[
					'property' => 'border',
					'selector' => $button_selector,
				],
			],
			//'required' => ['maybe_remove_close', '!=', true]
		];

		$this->controls['iconBoxShadow'] = [
			'tab'   => 'content',
			'label' => esc_html__( 'Box shadow', 'bricks' ),
			'group' => 'close_button',
			'type'  => 'box-shadow',
			'css'   => [
				[
					'property' => 'box-shadow',
					'selector' => $button_selector,
				],
			],
			//'required' => ['maybe_remove_close', '!=', true]
		];


		$this->controls['button_padding'] = [
			'tab' => 'content',
			'group' => 'close_button',
			'label' => esc_html__( 'Padding', 'bricks' ),
			'type' => 'dimensions',
			'css' => [
			  [
				'property' => 'padding',
				'selector' => $button_selector,
			  ]
			],
			'placeholder' => [
			  'top' => '10px',
			  'right' => '10px',
			  'bottom' => '10px',
			  'left' => '10px',
			],
		  ];

		  $this->controls['button_margin'] = [
			'tab' => 'content',
			'group' => 'close_button',
			'label' => esc_html__( 'Margin', 'bricks' ),
			'type' => 'dimensions',
			'css' => [
			  [
				'property' => 'margin',
				'selector' => $button_selector,
			  ]
			],
			'placeholder' => [
			  'top' => '10px',
			  'right' => '10px',
			  'bottom' => '10px',
			  'left' => '10px',
			],
			//'required' => ['maybe_remove_close', '!=', true]
		  ];






	  /* preview animations */ 

	  $this->controls['transitionDuration'] = [
		'tab' => 'content',
		'label' => esc_html__( 'Transition duration', 'bricks' ),
		'type' => 'text',
		'group' => 'animation',
		'inline'      => true,
		//'small'		  => true,
		'hasDynamicData' => false,
		'css' => [
			[
			  'selector' => '',  
			  'property' => '--x-modal-transition',
			],
		  ],
		//'inlineEditing' => true,
		'placeholder' => '200ms',
	  ];

	  $this->controls['preview_animation'] = [
		'tab'         => 'content',
		'label'       => esc_html__( 'Preview animation', 'bricks' ),
		'description'       => esc_html__( 'Set the start / end positions to control the movement as the modal is revealed/closed', 'bricks' ),
		'group' => 'animation',
		'type'        => 'select',
		'options'     => [
			'x-modal_preview-start' => esc_html__( 'Start position', 'bricks' ),
			'x-modal_preview-open' => esc_html__( 'Open position', 'bricks' ),
			'x-modal_preview-end' => esc_html__( 'End position', 'bricks' ),
		],
		'inline'      => true,
		'placeholder' => esc_html__( 'Open', 'bricks' ),
	];

	$this->controls['start_translate_x'] = [
		'tab' => 'content',
		'label' => esc_html__( 'TranslateX', 'bricks' ),
		'type' => 'number',
		'group' => 'animation',
		'units' => true,
		'inline' => true,
		'css' => [
			[
			  'property' => '--x-modal-translatex',
			  'selector' => '.x-modal_backdrop',
			]
		  ],
		'placeholder' => esc_html__( '0%', 'bricks' ),
		'required' => ['preview_animation', '=', ['x-modal_preview-start']]
	  ];

	  $this->controls['start_translate_y'] = [
		'tab' => 'content',
		'label' => esc_html__( 'TranslateY', 'bricks' ),
		'type' => 'number',
		'group' => 'animation',
		'units' => true,
		'inline' => true,
		'css' => [
			[
			  'property' => '--x-modal-translatey',
			  'selector' => '.x-modal_backdrop',
			]
		  ],
		'placeholder' => esc_html__( '10%', 'bricks' ),
		'required' => ['preview_animation', '=', ['x-modal_preview-start']]
	  ];

	  $this->controls['start_scale'] = [
		'tab' => 'content',
		'label' => esc_html__( 'Scale', 'extras' ),
		'type' => 'number',
		'group' => 'animation',
		'css' => [
		  [
			'selector' => '.x-modal_container',  
			'property' => '--x-modal-scale',
		  ],
		],
		'inline' => true,
		'units' => false,
		'placeholder' => esc_html__( '1', 'bricks' ),
		'required' => ['preview_animation', '=', ['x-modal_preview-start']]
	  ];


	  $this->controls['end_translate_x'] = [
		'tab' => 'content',
		'label' => esc_html__( 'TranslateX', 'bricks' ),
		'type' => 'number',
		'group' => 'animation',
		'units' => true,
		'inline' => true,
		'css' => [
			[
			  'property' => '--x-modal-close-translatex',
			  'selector' => '.x-modal_backdrop',
			]
		  ],
		'placeholder' => esc_html__( '0%', 'bricks' ),
		'required' => ['preview_animation', '=', ['x-modal_preview-end']]
	  ];

	  $this->controls['end_translate_y'] = [
		'tab' => 'content',
		'label' => esc_html__( 'TranslateY', 'bricks' ),
		'type' => 'number',
		'group' => 'animation',
		'units' => true,
		'inline' => true,
		'css' => [
			[
			  'property' => '--x-modal-close-translatey',
			  'selector' => '.x-modal_backdrop',
			]
		  ],
		'placeholder' => esc_html__( '-5%', 'bricks' ),
		'required' => ['preview_animation', '=', ['x-modal_preview-end']]
	  ];

	  $this->controls['end_scale'] = [
		'tab' => 'content',
		'label' => esc_html__( 'Scale', 'extras' ),
		'type' => 'number',
		'group' => 'animation',
		'css' => [
		  [
			'selector' => '.x-modal_container',  
			'property' => '--x-modal-close-scale',
		  ],
		],
		//'inline' => true,
		'units' => false,
		'min' => 0,
		'max' => 3,
		'step' => '0.1', // Default: 1
		'placeholder' => esc_html__( '1', 'bricks' ),
		'required' => ['preview_animation', '=', ['x-modal_preview-end']]
	  ];

	  $this->controls['reduce_motion'] = [
		'tab'   => 'content',
		//'inline' => true,
		'group' => 'animation',
		//'default' => 'false',
		'placeholder' => esc_html__( 'Use animation', 'bricks' ),
		'label' => esc_html__( "If 'Reduce motion' enabled", 'bricks' ),
		'type'  => 'select',
		'options' => [
			'fade' => esc_html__( 'Fade', 'bricks' ),
			'animate' => esc_html__( 'Use animation', 'bricks' ),
			'notransition' => esc_html__( 'No transition', 'bricks' )
		]
	];


	  $this->controls['clickBackdropClose'] = [
		'tab' => 'content',
		'group' => 'interaction',
		'label' => esc_html__( 'Click backdrop to close', 'bricks' ),
		'type' => 'select',
		'options' => [
		  'auto' => esc_html__( 'Enable', 'bricks' ),
		  'none' => esc_html__( 'Disable', 'bricks' ),
		],
		'css' => [
			[
			  'property' => 'pointer-events',
			  'selector' => '.x-modal_backdrop',
			]
		  ],
		'inline'      => true,
		'placeholder' => esc_html__( 'Enable', 'bricks' ),
		//'clearable' => false,
	  ];

	  $this->controls['escToClose'] = [
		'tab' => 'content',
		'group' => 'interaction',
		'label' => esc_html__( 'Press Esc to close', 'bricks' ),
		'type' => 'select',
		'options' => [
		  'true' => esc_html__( 'Enable', 'bricks' ),
		  'false' => esc_html__( 'Disable', 'bricks' ),
		],
		'inline'      => true,
		'placeholder' => esc_html__( 'Enable', 'bricks' ),
		//'clearable' => false,
	  ];

	  $this->controls['disableFocus'] = [
		'tab' => 'content',
		'group' => 'interaction',
		'label' => esc_html__( 'Auto focus on first focusable element', 'bricks' ),
		'type' => 'select',
		'options' => [
		  'false' => esc_html__( 'Enable', 'bricks' ),
		  'true' => esc_html__( 'Disable', 'bricks' ),
		],
		'inline'      => true,
		'placeholder' => esc_html__( 'Enable', 'bricks' ),
		//'clearable' => false,
	  ];

	  $this->controls['hashToClose'] = [
		'tab' => 'content',
		'group' => 'interaction',
		'label' => esc_html__( 'Hash link to close', 'bricks' ),
		'type' => 'select',
		'options' => [
		  'true' => esc_html__( 'Enable', 'bricks' ),
		  'false' => esc_html__( 'Disable', 'bricks' ),
		],
		'inline'      => true,
		'placeholder' => esc_html__( 'Enable', 'bricks' ),
		//'clearable' => false,
	  ];

	  $this->controls['maybeCustomAriaLabel'] = [
		'tab' => 'content',
		'label' => esc_html__( 'Modal aria label', 'bricks' ),
		'type'  => 'select',
		'group' => 'interaction',
		'placeholder' => esc_html__( 'Auto - Labelled by first heading in modal', 'bricks' ),
		'options' => [
			'default' => esc_html__( 'Auto - Labelled by first heading in modal', 'bricks' ),
			'custom' => esc_html__( 'Custom label', 'bricks' ),
		],
	  ];

	  $this->controls['customAriaLabel'] = [
		'tab' => 'content',
		'group' => 'interaction',
		'label' => esc_html__( 'Aria label', 'bricks' ),
		'type'  => 'text',
		'required' => ['maybeCustomAriaLabel', '=', ['custom']],
		'placeholder' => esc_html__( 'Modal', 'bricks' ),
	  ];

	  $this->controls['role'] = [
		'tab' => 'content',
		'group' => 'interaction',
		'label' => esc_html__( 'Role', 'bricks' ),
		'type' => 'select',
		'options' => [
		  'dialog' => esc_html( 'dialog', 'bricks' ),
		  'alertdialog' => esc_html( 'alertdialog', 'bricks' ),
		],
		'inline'      => true,
		'placeholder' => esc_html( 'dialog', 'bricks' ),
		//'clearable' => false,
	  ];

  }

  // Methods: Frontend-specific
	public function enqueue_scripts() {

		wp_enqueue_script( 'x-pro-modal', BRICKSEXTRAS_URL . 'components/assets/js/' . \BricksExtras\Helpers::maybeMinifyScripts('promodal') . '.js', '', '1.1.6', true );

		if (! \BricksExtras\Helpers::elementCSSAdded($this->name) ) {
			wp_enqueue_style( 'x-pro-modal', BRICKSEXTRAS_URL . 'components/assets/css/promodal.css', [], '' );
		}

	}


  public function render() {

	$triggers_values = isset( $this->settings['triggers'] ) ? $this->settings['triggers'] : '';

	$button_text = ! empty( $this->settings['button_text'] ) ? '<span class="x-modal_close-text">' . esc_html__( $this->settings['button_text'] ) . '</span>' : '';
	$aria_label = isset( $this->settings['aria_label'] ) ? esc_attr__($this->settings['aria_label'] ): 'close modal';

	$maybeCustomAriaLabel = isset( $this->settings['maybeCustomAriaLabel'] ) ? 'custom' === $this->settings['maybeCustomAriaLabel'] : false;
	$customAriaLabel = isset( $this->settings['customAriaLabel'] ) ? esc_attr__( $this->settings['customAriaLabel'] ) : 'Modal';
	$role = isset( $this->settings['role'] ) ? esc_attr( $this->settings['role'] ) : 'dialog';

	$triggers = [];

    if ( is_array( $triggers_values ) ) {
		foreach ( $triggers_values as $trigger_config ) {
        
		if ( isset( $trigger_config['type'] ) ) {
		
			switch ( $trigger_config['type'] ) {
				case 'pageLoad':
					$triggers[] = [
						'type'    => 'pageLoad',
						'options' => [
							'delay' => isset( $trigger_config['delay'] ) ? $trigger_config['delay'] : 0,
						],
					];
					break;

				case 'pageLoadURLParameter':
					$triggers[] = [
						'type'    => 'pageLoadQuery',
						'options' => [
							'params' => isset( $trigger_config['query_includes'] ) ? $trigger_config['query_includes'] : null,
							'delay' => isset( $trigger_config['delay'] ) ? $trigger_config['delay'] : 0,
						],
					];
					break;	

				case 'scroll':
					$triggers[] = [
						'type'    => 'pageScroll',
						'options' => [
							'scrollAmount' => isset( $trigger_config['amount'] ) ? $trigger_config['amount'] : 0,
						],
					];
					break;

				case 'exitIntent':
					$triggers[] = [
						'type' => 'exitIntent',
						'options' => [],
					];
					break;

				case 'pageViews':
					$triggers[] = [
						'type'    => 'page_views',
						'options' => [
							'views' => isset( $trigger_config['views'] ) ? $trigger_config['views'] : 1,
						],
					];
					break;	

				case 'elementClick':
					$triggers[] = [
						'type'    => 'element_click',
						'options' => [
							'selector' => isset( $trigger_config['selector'] ) ? $trigger_config['selector'] : null,
							'ariaControls' => isset( $trigger_config['aria_controls'] ) ? $trigger_config['aria_controls'] : false,
							'delay' => isset( $trigger_config['delay'] ) ? $trigger_config['delay'] : 0,
						],
					];
					break;

				case 'elementHover':
					$triggers[] = [
						'type'    => 'element_hover',
						'options' => [
							'selector' => isset( $trigger_config['selector'] ) ? $trigger_config['selector'] : null,
							'delay' => isset( $trigger_config['delay'] ) ? $trigger_config['delay'] : 0,
						],
					];
					break;

				case 'timeInactive':
					$triggers[] = [
						'type'    => 'time_inactive',
						'options' => [
							'time' => isset( $trigger_config['inactive_time'] ) ? $trigger_config['inactive_time'] : 0,
						],
					];
					break;
				case 'scrolledToElement':
					$triggers[] = [
						'type'    => 'scrolled_to_element',
						'options' => [
							'selector' => isset( $trigger_config['selector'] ) ? $trigger_config['selector'] : null,
							'delay' => isset( $trigger_config['delay'] ) ? $trigger_config['delay'] : 0,
						],
					];
					break;

				default:
					# code...
					break;
			}

		}


      }
    } 

	$maybe_trap_focus = isset( $this->settings['maybe_trap_focus'] ) ? $this->settings['maybe_trap_focus'] : 'true';

	$disableFocus = isset( $this->settings['disableFocus'] ) ? $this->settings['disableFocus'] : 'false';

	$modal_config = [
		'rawConfig'      => [
			'openClass' => 'x-modal_open',
			'closeTrigger' => 'data-x-modal-close',
			'awaitOpenAnimation' => true,
			'awaitCloseAnimation' => true,
			'disableScroll' => isset( $this->settings['disableScroll'] ),
			'disableFocus' => 'false' !== $disableFocus,
		],
		'triggers' =>  $triggers,
		'show_once' => isset( $this->settings['show_once'] ) ? $this->settings['show_once'] : false,
		'show_once_session' => isset( $this->settings['show_once_session'] ) ? $this->settings['show_once_session'] : false,
		'show_again' => [
			'type' => isset( $this->settings['show_again'] ) ? $this->settings['show_again'] : '',
			'options' => [
				'days' => isset( $this->settings['show_again_days'] ) ? $this->settings['show_again_days'] : 0,
				'hours' => isset( $this->settings['show_again_hours'] ) ? $this->settings['show_again_hours'] : 0,
			],
		],
		'escToClose' => isset( $this->settings['escToClose'] ) ? $this->settings['escToClose'] : 'true',
		'hashToClose' => isset( $this->settings['hashToClose'] ) ? $this->settings['hashToClose'] : 'true',
	];

	if ( isset( $this->settings['reduce_motion'] ) ) {
		$modal_config += [ 'reduceMotion' => $this->settings['reduce_motion']];
	}

	if ( $maybeCustomAriaLabel ) {
		$modal_config += [ 'customAriaLabel' => $customAriaLabel ];
	}

	/* get element ID of the most deep looped element */
	if ( method_exists('\Bricks\Query','is_any_looping') ) {

		$query_id = \Bricks\Query::is_any_looping();

		if ( $query_id ) {
			$modal_config += [ 'isLooping' => \Bricks\Query::get_query_element_id( $query_id ) ];
			$modal_config += [ 'loopIndex' => \Bricks\Query::get_loop_index()  ];

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

	$close_icon = empty( $this->settings['close_icon'] ) ? false : self::render_icon( $this->settings['close_icon'] );

	$preventScroll = isset( $this->settings['disableScroll'] );

	if ( $preventScroll ) {
		$this->set_attribute( '_root', 'data-lenis-prevent', '' );
	}

	$this->set_attribute( '_root', 'data-x-modal', wp_json_encode( $modal_config ) );
	$this->set_attribute( '_root', 'class', 'x-modal' );
	$this->set_attribute( 'x-modal_backdrop', 'class', 'x-modal_backdrop' );
	$this->set_attribute( 'x-modal_backdrop', 'tabindex', '-1' );

	$clickBackdropClose = isset( $this->settings['clickBackdropClose'] ) ? $this->settings['clickBackdropClose'] : 'auto';

	if ( 'none' !== $clickBackdropClose ) {
		$this->set_attribute( 'x-modal_backdrop', 'data-x-modal-close', '' );
	}

	$this->set_attribute( 'x-modal_container', 'class', 'x-modal_container' );
	$this->set_attribute( 'x-modal_container', 'aria-modal', 'true' );
	$this->set_attribute( 'x-modal_container', 'role', $role );

	$this->set_attribute( 'x-modal_content', 'class', 'x-modal_content' );

	$this->set_attribute( 'x-modal_close', 'class', 'x-modal_close' );
	$this->set_attribute( 'x-modal_close', 'aria-label', $aria_label );
	$this->set_attribute( 'x-modal_close', 'data-x-modal-close', '' );

	$this->set_attribute( 'x-modal_close-icon', 'class', 'x-modal_close-icon' );



    echo "<div {$this->render_attributes( '_root' )}>";
		echo "<div {$this->render_attributes( 'x-modal_backdrop' )}>";
			echo "<div {$this->render_attributes( 'x-modal_container' )}>";
				 echo "<div {$this->render_attributes( 'x-modal_content' )}>";

				if ( method_exists('\Bricks\Frontend','render_children') ) {
						echo \Bricks\Frontend::render_children( $this );
				}

					echo isset ( $this->settings['maybe_remove_close'] ) ? '' : "<button {$this->render_attributes( 'x-modal_close' )}>" . $button_text . "<span {$this->render_attributes( 'x-modal_close-icon' )}> " . $close_icon . "  </span></button>";

				 echo "</div>";
			echo "</div>";
		echo "</div>";	
	echo "</div>";

  }

  public static function render_builder() { ?>

		<script type="text/x-template" id="tmpl-bricks-element-xpromodalnestable">
			
			<component 
				v-show="!settings.builderHidden"
				class="x-modal"
				:class="settings.preview_animation"
			>
			<div 
					class="x-modal_backdrop"> 
				<div 
					class="x-modal_container" 
					role="dialog" 
					aria-modal="true"
				>
				<div 
					class="x-modal_content" 
				> 
				<bricks-element-children
						:element="element"
					/>
  				</div>
			
				<button 
					class="x-modal_close"
					v-if="!settings.maybe_remove_close"
				>
					<contenteditable
						tag="span"
						class="x-modal_close-text"
						:name="name"
						controlKey="button_text"
						v-if="settings.button_text"
						toolbar="style align"
						:settings="settings"
					/>
					
					<span class="x-modal_close-icon"><icon-svg :iconSettings="settings.close_icon"/></span></button>
				</div>
			</div>	
  			</component>

		</script>

	<?php }

}