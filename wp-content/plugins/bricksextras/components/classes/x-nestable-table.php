<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class X_Nestable_Table extends \Bricks\Element {

  // Element properties
  public $category     = 'extras';
	public $name         = 'xnestabletable';
	public $icon         = 'ti-layout-cta-btn-left';
	public $css_selector = '';
	public $scripts      = ['xNestableTable'];
  public $nestable 		= true;

  
  public function get_label() {
	return esc_html__( 'Nestable Table', 'extras' );
  }
  public function set_control_groups() {

    $this->control_groups['header_group'] = [
      'title' => esc_html__( 'Header styles', 'extras' ),
      'tab' => 'content',
    ];
  
    $this->control_groups['rows_group'] = [
      'title' => esc_html__( 'Row/cell Styles', 'extras' ),
      'tab' => 'content',
    ];

	$this->control_groups['sticky_header_group'] = [
		'title' => esc_html__( 'Sticky Header', 'extras' ),
		'tab' => 'content',
	  ];
  
    $this->control_groups['mobile_group'] = [
      'title' => esc_html__( 'Stack vertically', 'extras' ),
      'tab' => 'content',
    ];

  }

  public function set_controls() {

		$this->controls['table_layout'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Table layout', 'bricks' ),
			'type' => 'select',
			'inline' => true,
			'options' => [
				'auto' => esc_html__( 'Auto', 'bricks' ),
				'fixed' => esc_html__( 'Fixed', 'bricks' ),
			],
			'css' => [
				[
					'property' => 'table-layout',
				],
			],
		];


		/* header */

		$this->controls['header_bg'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Header background', 'bricks' ),
			'group'			=> 'header_group',
			'type' => 'color',
			'inline' => true,
			'css' => [
			  [
				'property' => 'background-color',
				'selector' => 'th',
			  ],
			  [
				'property' => 'background-color',
				'selector' => '&.x-nestable-table_stacked td:before',
			  ]
			],
		  ];

		  $this->controls['header_typography'] = [
			'tab'    => 'content',
			'group'    => 'header_group',
			'type'   => 'typography',
			'label'  => esc_html__( 'Header Typography', 'bricks' ),
			'css'    => [
				[
					'property' => 'font',
					'selector' => 'th',
				],
				[
					'property' => 'font',
					'selector' => '&.x-nestable-table_stacked td:before',
				]
			],
		];

		$this->controls['header_padding'] = [
			'tab' => 'content',
			'group'			=> 'header_group',
			'label' => esc_html__( 'Padding', 'bricks' ),
			'type' => 'dimensions',
			'css' => [
			  [
				'property' => 'padding',
				'selector' => 'th',
			  ]
			],
			'placeholder' => [
				'top' => '14px',
				'right' => '24px',
				'bottom' => '14px',
				'left' => '24px',
			  ],
		  ];



		  /* table rows */

		  $this->controls['cell_borders'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Cell border color', 'bricks' ),
			'group'			=> 'rows_group',
			'type' => 'color',
			'inline' => true,
			'css' => [
			  [
				'property' => '--x-table-border-color',
				'selector' => '',
			  ],
			  
			],
		  ];

		  $this->controls['row_bg'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Background', 'bricks' ),
			'group'			=> 'rows_group',
			'type' => 'color',
			'inline' => true,
			'css' => [
			  [
				'property' => 'background-color',
				'selector' => 'td',
			  ]
			],
		  ];

		  $this->controls['row_bg_even'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Background (even)', 'bricks' ),
			'group'			=> 'rows_group',
			'type' => 'color',
			'inline' => true,
			'css' => [
			  [
				'property' => 'background-color',
				'selector' => 'tr:nth-child(even) td',
			  ]
			],
		  ];

		  $this->controls['row_color'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Text color', 'bricks' ),
			'group'			=> 'rows_group',
			'type' => 'color',
			'inline' => true,
			'css' => [
			  [
				'property' => 'color',
				'selector' => 'td',
			  ]
			],
		  ];

		  $this->controls['row_color_even'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Text color (even)', 'bricks' ),
			'group'			=> 'rows_group',
			'type' => 'color',
			'inline' => true,
			'css' => [
			  [
				'property' => 'color',
				'selector' => '.tr:nth-child(even) td',
			  ]
			],
		  ];


		  $this->controls['row_typography'] = [
			'tab'    => 'content',
			'group'    => 'rows_group',
			'type'   => 'typography',
			'label'  => esc_html__( 'Typography', 'bricks' ),
			'css'    => [
				[
					'property' => 'font',
					'selector' => 'td',
				],
			],
		];

		$this->controls['row_padding'] = [
			'tab' => 'content',
			'group'	=> 'rows_group',
			'label' => esc_html__( 'Padding', 'bricks' ),
			'type' => 'dimensions',
			'css' => [
			  [
				'property' => 'padding',
				'selector' => 'td',
			  ]
			],
			'placeholder' => [
				'top' => '14px',
				'right' => '24px',
				'bottom' => '14px',
				'left' => '24px',
			  ],
		  ];

		  $this->controls['row_min_height'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Fixed row height', 'bricks' ),
			'group'			=> 'rows_group',
			'type' => 'number',
			'units' => true,
			'inline' => true,
			'css' => [
			  [
				'property' => 'height',
				'selector' => 'td',
			  ]
			],
		  ];

		  /* sticky header */

		  $this->controls['sticky_sep'] = [
			'tab'     => 'style',
			'group'   => 'sticky_header_group',
			'label'   => esc_html__( 'Sticky table header', 'bricks' ),
			'type'    => 'separator',
			'description' => esc_html__( "Note that sticky positioning can't be used inside containers using the overflow property", 'bricks' ),
		  ];

		  $this->controls['position'] = [
			'tab'     => 'style',
			'group'   => 'sticky_header_group',
			'label'   => esc_html__( 'Header Position', 'bricks' ),
			'type'    => 'select',
			'options' => [
				'sticky' => 'Sticky',
				'static' => 'Static',
			],
			'css'     => [
					[
						'property' => 'position',
						'selector' => 'thead:not(.x-nestable-table_stacked thead)',
					],
				],
				'inline'  => true,
			];

			$this->controls['top'] = [
				'tab'     => 'style',
				'group'   => 'sticky_header_group',
				'label'   => esc_html__( 'Top', 'bricks' ),
				'type'    => 'number',
				'placeholder' => '0',
				'units'	 => true,
				'css'     => [
						[
							'property' => 'top',
							'selector' => 'thead:not(.x-nestable-table_stacked thead)',
						],
				],
				'inline'  => true,
				'required' => [ 'position', '=', 'sticky' ],
			];


    	/* stacked */
		$breakpointOptions = [];

		foreach ( \Bricks\Breakpoints::$breakpoints as $breakpoint ) {
			$breakpointOptions[$breakpoint['width']] = $breakpoint['label'] . ' (<= ' . $breakpoint['width'] . 'px )';
		}

		$breakpointOptions['false'] = 'None';

		$this->controls['maybeStack'] = [
			'tab' => 'content',
			'group'	=> 'mobile_group',
			'label' => esc_html__( 'Stack columns vertically at breakpoint', 'bricks' ),
			'type' => 'select',
			'options' => $breakpointOptions,
			'placeholder' => 'None',
		];

		$this->controls['stackRowGap'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Row gap', 'bricks' ),
			'group'	=> 'mobile_group',
			'type' => 'number',
			'inline' => true,
			'placeholder' => '1em',
			'units' => true,
			'css' => [
				[
				  'property' => 'gap',
				  'selector' => '&.x-nestable-table_stacked tbody',
				]
			  ],
		  ];

		  $this->controls['stackLabels'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Cell content on new line', 'bricks' ),
			'type' => 'select',
			'group'	=> 'mobile_group',
			'options' => [
			  'true' => esc_html__( 'True', 'bricks' ),
			  'false' => esc_html__( 'False', 'bricks' ),
			],
			'inline'      => true,
			'small' => false,
			'placeholder' => esc_html__( 'False', 'bricks' ),
		  ];

		$this->controls['stackLabelsSep'] = [
			'tab'   => 'content',
			'type'  => 'separator',
			'label' => esc_html__( 'Column labels', 'bricks' ),
			'description' => esc_html__( '(typography inherited from header styles)', 'bricks' ),
			'group'	=> 'mobile_group',
		];

		$this->controls['stackWidth'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Width', 'bricks' ),
			'group'	=> 'mobile_group',
			'type' => 'number',
			'min' => 0,
			'max' => 100,
			'step' => 1, // Default: 1
			'inline' => true,
			'units' => true,
			'css' => [
				[
				  'property' => 'width',
				  'selector' => '&:not([data-x-nestable-table*=stackLabels]).x-nestable-table_stacked td:before',
				]
			  ],
			  'required' => [ 'stackLabels', '!=', 'true' ],
		  ];

		$this->controls['stackMinWidth'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Min-width', 'bricks' ),
			'group'	=> 'mobile_group',
			'type' => 'number',
			'min' => 0,
			'max' => 1000,
			'step' => 1, // Default: 1
			'inline' => true,
			'placeholder' => '150px',
			'units' => true,
			'css' => [
				[
				  'property' => 'min-width',
				  'selector' => '&:not([data-x-nestable-table*=stackLabels]).x-nestable-table_stacked td:before',
				]
			  ],
			  'required' => [ 'stackLabels', '!=', 'true' ],
		  ];

		  

		  

		  $this->controls['alignTitle'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Label align', 'bricks' ),
			'group'	=> 'mobile_group',
			'inline' => true,
			'placeholder' => esc_html__( 'Center', 'bricks' ),
			'type' => 'select',
			'options' => [
				'flex-start' => esc_html__( 'Flex start', 'bricks' ),
				'center' => esc_html__( 'Center', 'bricks' ),
				'flex-end' => esc_html__( 'Flex end', 'bricks' ),
			],
			'css' => [
				[
				  'property' => 'align-items',
				  'selector' => '&.x-nestable-table_stacked td:before',
				]
			  ],
			  'required' => [ 'stackLabels', '!=', 'true' ],
		  ];

		  $this->controls['labelPadding'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Label padding', 'bricks' ),
			'group'	=> 'mobile_group',
			'type' => 'dimensions',
			'units' => true,
			'css' => [
				[
				  'property' => 'padding',
				  'selector' => '&.x-nestable-table_stacked td:before',
				]
			  ],
		  ];

		  $this->controls['cellsSep'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Cells', 'bricks' ),
			'group'	=> 'mobile_group',
			'type' => 'separator',
		  ];

		  $this->controls['stackPadding'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Cell inline padding', 'bricks' ),
			'info' => esc_html__( 'Padding behind the label', 'bricks' ),
			'group'	=> 'mobile_group',
			'type' => 'number',
			'min' => 0,
			'max' => 1000,
			'inline' => true,
			'placeholder' => '150px',
			'units' => true,
			'css' => [
				[
				  'property' => 'padding-inline-start',
				  'selector' => '&:not([data-x-nestable-table*=stackLabels]).x-nestable-table_stacked td',
				]
			  ],
			  'required' => [ 'stackLabels', '!=', 'true' ],
		  ];

		  $this->controls['stackCellTypography'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Cell typography', 'bricks' ),
			'group'	=> 'mobile_group',
			'type' => 'typography',
			'units' => true,
			'css' => [
				[
				  'property' => 'font',
				  'selector' => '&.x-nestable-table_stacked td',
				]
			  ],
			  //'required' => [ 'stackLabels', '=', 'true' ],
		  ];

		  $this->controls['cellAlign'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Cell align', 'bricks' ),
			'group'	=> 'mobile_group',
			'type' => 'text-align',
			'units' => true,
			'exclude' => [
				'justify',
			],
			'css' => [
				[
				  'property' => 'text-align',
				  'selector' => '&.x-nestable-table_stacked td',
				],
				[
					'property' => 'align-items',
					'selector' => '&.x-nestable-table_stacked td',
					'value'    => 'flex-start',
					'required' => 'left',
				],
				[
					'property' => 'align-items',
					'selector' => '&.x-nestable-table_stacked td',
					'value'    => 'center',
					'required' => 'center',
				],
				[
					'property' => 'align-items',
					'selector' => '&.x-nestable-table_stacked td',
					'value'    => 'flex-end',
					'required' => 'right',
				],
			  ],
		  ];
		  


		  $this->controls['stackCellPadding'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Cell content padding', 'bricks' ),
			'group'	=> 'mobile_group',
			'type' => 'dimensions',
			'units' => true,
			'css' => [
				[
				  'property' => 'padding',
				  'selector' => '&[data-x-nestable-table*=stackLabels].x-nestable-table_stacked td > span',
				]
			  ],
			  'required' => [ 'stackLabels', '=', 'true' ],
		  ];

  }

  // Methods: Frontend-specific
  public function enqueue_scripts() {

    wp_enqueue_script( 'x-nestable-table', BRICKSEXTRAS_URL . 'components/assets/js/' . \BricksExtras\Helpers::maybeMinifyScripts('nestabletable') . '.js', '', '1.0.4', true );

    if (! \BricksExtras\Helpers::elementCSSAdded($this->name) ) {
      wp_enqueue_style( 'x-nestable-table', BRICKSEXTRAS_URL . 'components/assets/css/nestabletable.css', [], '' );
    }

  }
  
  public function render() {

    $settings = $this->settings;

	$stackLabels = isset( $settings['stackLabels'] ) ? $settings['stackLabels'] : 'false';

    $config = [
		'stack' => isset( $settings['maybeStack'] ) ? $settings['maybeStack'] : 'false',
	];

	if ( 'true' === $stackLabels ) {
		$config += [
			'stackLabels' => $stackLabels
		];
	}


    $this->set_attribute( '_root', 'data-x-nestable-table', wp_json_encode( $config ) );

    $tag = !BricksExtras\Helpers::maybePreview() ? 'table' : 'div';

    
    echo "<" . $tag . " {$this->render_attributes( '_root' )}>";
			echo \Bricks\Frontend::render_children( $this );
    echo "</" . $tag . ">";
    
  }

  


  public function get_nestable_children() {
		return [
      		[
			'name' => 'div',
			'label' => esc_html__( 'Head', 'bricks' ),
			'deletable' => false,
			'cloneable' => false,	
			'settings' => [
				'tag' => 'custom',
				'customTag' => 'thead'
			],
			'children' => [
          [
          'name' => 'div',
          'label' => esc_html__( 'Row', 'bricks' ),
		  'deletable' => false,
			'cloneable' => false,
          'settings' => [
            'tag' => 'custom',
            'customTag' => 'tr'
          ],
          'children' => [
            [
              'name' => 'div',
              'label' => esc_html__( 'Cell', 'bricks' ),
              'settings' => [
                'tag' => 'custom',
                'customTag' => 'th',
              ],
              'children' => [
                [
                  'name' => 'text-basic',
                  'settings' => [
                    'text' => esc_html__( 'Post Title', 'bricks' ),
                    'tag' 	=> 'span'
                  ]
                ]
              ]
            ],
            [
              'name' => 'div',
              'label' => esc_html__( 'Cell', 'bricks' ),
              'settings' => [
                'tag' => 'custom',
                'customTag' => 'th',
              ],
              'children' => [
                [
                  'name' => 'text-basic',
                  'settings' => [
                    'text' => esc_html__( 'Post Date', 'bricks' ),
                    'tag' 	=> 'span'
                  ]
                ]
              ]
            ],
          ],
          ],
        ],
      ],
      [
				'name' => 'div',
				'label' => esc_html__( 'Body', 'bricks' ),
				'deletable' => false,
				'cloneable' => false,
				'settings' => [
					'tag' => 'custom',
					'customTag' => 'tbody'
				],
				'children' => [
          [
          'name' => 'div',
          'label' => esc_html__( 'Row', 'bricks' ),
          'settings' => [
            'tag' => 'custom',
            'customTag' => 'tr'
          ],
          'children' => [
            [
              'name' => 'div',
              'label' => esc_html__( 'Cell', 'bricks' ),
              'settings' => [
                'tag' => 'custom',
                'customTag' => 'td',
              ],
              'children' => [
                [
                  'name' => 'text-basic',
                  'settings' => [
                    'text' => esc_html__( '{post_title}', 'bricks' ),
                    'tag' 	=> 'span'
                  ]
                ]
              ]
            ],
            [
              'name' => 'div',
              'label' => esc_html__( 'Cell', 'bricks' ),
              'settings' => [
                'tag' => 'custom',
                'customTag' => 'td',
              ],
              'children' => [
                [
                  'name' => 'text-basic',
                  'settings' => [
                    'text' => esc_html__( '{post_date}', 'bricks' ),
                    'tag' 	=> 'span'
                  ]
                ]
              ]
            ],
          ]
        ],
        ]
      ]
		];
	}

  

}