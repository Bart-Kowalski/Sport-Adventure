<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class X_Dynamic_Table extends \Bricks\Element {

  // Element properties
  public $category     = 'extras';
  public $name         = 'xdynamictable'; 
  public $icon         = 'ti-layout-cta-btn-left';
  public $css_selector = '';
  public $scripts      = ['xDynamicTable'];
  public $loop_index = 0;

  // Methods: Builder-specific
  public function get_label() {
	return esc_html__( 'Dynamic Table', 'extras' );
  }
  public function set_control_groups() {

	$this->control_groups['table_content_group'] = [
		'title' => esc_html__( 'Set Columns', 'extras' ),
		'tab' => 'content',
	];

	$this->control_groups['table_content_rows_group'] = [
		'title' => esc_html__( 'Rows / Cells', 'extras' ),
		'tab' => 'content',
		'required' => [
			[ 'maybeDynamic', '=', 'static' ]
		],
	];

	$this->control_groups['header_group'] = [
		'title' => esc_html__( 'Header styles', 'extras' ),
		'tab' => 'content',
	];

	$this->control_groups['rows_group'] = [
		'title' => esc_html__( 'Row/cell Styles', 'extras' ),
		'tab' => 'content',
	];

	$this->control_groups['search_group'] = [
		'title' => esc_html__( 'Search', 'extras' ),
		'tab' => 'content',
	];

	$this->control_groups['pagination_group'] = [
		'title' => esc_html__( 'Table Footer / Pagination', 'extras' ),
		'tab' => 'content',
	];

	$this->control_groups['wrapper_group'] = [
		'title' => esc_html__( 'Body styles', 'extras' ),
		'tab' => 'content',
	];

	$this->control_groups['mobile_group'] = [
		'title' => esc_html__( 'Stack columns', 'extras' ),
		'tab' => 'content',
	];

  }


  public function set_controls() {

		$this->controls['maybeDynamic'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Populate table rows', 'bricks' ),
			'type' => 'select',
			'options' => [
				'dynamic' => esc_html__( 'Dynamic (populate rows with query loop)', 'bricks' ),
				'static' => esc_html__( 'Static (add rows/cells manually)', 'bricks' ),
			],
			'clearable' => false,
			'placeholder' => esc_html__( 'Dynamic (populate rows with query loop)', 'bricks' ),
			//'default' => 'true',
		];

		$this->controls['loop_sep'] = [
			'tab'   => 'content',
			'type'  => 'separator',
			//'label' => esc_html__( 'Dynamic data to populate rows', 'bricks' ),
			'description' => esc_html__( 'Set query to populate rows', 'bricks' ),
			'required' => [
				[ 'maybeDynamic', '!=', 'static' ]
			],
		];

		//$this->controls = array_replace_recursive( $this->controls, $this->get_loop_builder_controls() );

		$this->controls['hasLoop'] = [
			'tab'   => 'content',
			'label' => esc_html__( 'Use query loop', 'bricks' ),
			'type'  => 'checkbox',
			'required' => [
				[ 'maybeDynamic', '!=', 'static' ]
			],
		];

		$this->controls['query'] = [
			'tab'      => 'content',
			'label'    => esc_html__( 'Query', 'bricks' ),
			'type'     => 'query',
			'popup'    => true,
			'inline'   => true,
			'required' => [ 
				[ 'hasLoop', '!=', '' ],
				[ 'maybeDynamic', '!=', 'static' ] 
			],
		];

		$this->controls['column_sep'] = [
			'tab'   => 'content',
			'type'  => 'separator',
			'group'			=> 'table_content_group',
			'label' => esc_html__( 'Add columns', 'bricks' ),
			'description'   => esc_html__( 'Add the required number of columns and then change the column heading and use dynamic tags to populate the cells in each row. {post_title} etc', 'bricks' ),
			'required' => [
				[ 'maybeDynamic', '!=', 'static' ]
			],
		];

		$this->controls['column_sep_static'] = [
			'tab'   => 'content',
			'type'  => 'separator',
			'group'			=> 'table_content_group',
			'label' => esc_html__( 'Add columns', 'bricks' ),
			'description'   => esc_html__( 'Add the required number of columns', 'bricks' ),
			'required' => [
				[ 'maybeDynamic', '=', 'static' ]
			],
		];

		$this->controls['content_items'] = [
			'tab'           => 'content',
			'type'          => 'repeater',
			'group'			=> 'table_content_group',
			'label'         => esc_html__( 'Columns', 'bricks' ),
			//'description'   => esc_html__( 'Add the required number of columns', 'bricks' ),
			'placeholder'   => esc_html__( 'Column', 'bricks' ),
			'titleProperty' => 'title',
			'fields'        => [
				'title'       => [
					'type'    => 'text',
					'label'   => esc_html__( 'Column heading', 'bricks' ),
					//'inline'  => true,
				],
				'data'       => [
					'type'    => 'text',
					'label'   => esc_html__( 'Cell data for this column', 'bricks' ),
				],
				'width'       => [
					'type'    => 'number',
					'label'   => esc_html__( 'Min-width', 'bricks' ),
					'inline' => true,
					'units' => [
						'px' => [
						  'min' => 1,
						  'max' => 1000,
						  'step' => 1,
						],
					],
				],
				'attributes'       => [
					'type'    => 'repeater',
					'label'   => esc_html__( 'Attributes', 'bricks' ),
					'placeholder'   => esc_html__( 'Attribute', 'bricks' ),
					'fields'        => [
						'name'       => [
							'type'    => 'text',
							'label'   => esc_html__( 'Name', 'bricks' ),
							'hasDynamicData' => false,
						],
						'value'       => [
							'type'    => 'text',
							'label'   => esc_html__( 'Value', 'bricks' ),
							'hasDynamicData' => false,
						],
					]
				],
				'data_type' => [
					'inline' => true,
					'small' => true,
					'label' => esc_html__( 'Column data is numbers', 'bricks' ),
					'type'  => 'checkbox',
					'info' => esc_html__( 'Will make column sortable as numbers rather than alphabetically', 'bricks' ),
					'required' => [
						[ 'data_type_date', '!=', true ]
					],
				],
				'data_type_date' => [
					'inline' => true,
					'small' => true,
					'label' => esc_html__( 'Column data is date', 'bricks' ),
					'type'  => 'checkbox',
					'info' => esc_html__( 'Will make column sortable as dates', 'bricks' ),
					'required' => [
						[ 'data_type', '!=', true ]
					],
				],
				'prevent_sortable' => [
					'inline' => true,
					'small' => true,
					'label' => esc_html__( 'Prevent column from being sortable', 'bricks' ),
					'type'  => 'checkbox',
				],
			],
			'default'     => [
				[
					'title'      => esc_html__( 'Column', 'bricks' ),
				],
			],
			'required' => [
				[ 'maybeDynamic', '!=', 'static' ]
			],
		];

		$this->controls['content_items_static'] = [
			'tab'           => 'content',
			'type'          => 'repeater',
			'group'			=> 'table_content_group',
			'label'         => esc_html__( 'Columns', 'bricks' ),
			//'description'   => esc_html__( 'Add the required number of columns', 'bricks' ),
			'placeholder'   => esc_html__( 'Column', 'bricks' ),
			'titleProperty' => 'title',
			'fields'        => [
				'title'       => [
					'type'    => 'text',
					'label'   => esc_html__( 'Column heading', 'bricks' ),
					//'inline'  => true,
				],
				'width'       => [
					'type'    => 'number',
					'label'   => esc_html__( 'Min-width', 'bricks' ),
					'inline' => true,
					'units' => [
						'px' => [
						  'min' => 1,
						  'max' => 1000,
						  'step' => 1,
						],
					],
				],
				'attributes'       => [
					'type'    => 'repeater',
					'label'   => esc_html__( 'Attributes', 'bricks' ),
					'placeholder'   => esc_html__( 'Attribute', 'bricks' ),
					'fields'        => [
						'name'       => [
							'type'    => 'text',
							'label'   => esc_html__( 'Name', 'bricks' ),
							'hasDynamicData' => false,
						],
						'value'       => [
							'type'    => 'text',
							'label'   => esc_html__( 'Value', 'bricks' ),
							'hasDynamicData' => false,
						],
					]
				],
				'data_type' => [
					'inline' => true,
					'small' => true,
					'label' => esc_html__( 'Column data is numbers', 'bricks' ),
					'info' => esc_html__( 'Will ensure column is sortable as numbers', 'bricks' ),
					'type'  => 'checkbox',
					'required' => [
						[ 'data_type_date', '!=', true ]
					],
				],
				'data_type_date' => [
					'inline' => true,
					'small' => true,
					'label' => esc_html__( 'Column data is date', 'bricks' ),
					'type'  => 'checkbox',
					'info' => esc_html__( 'Will make column sortable as dates', 'bricks' ),
					'required' => [
						[ 'data_type', '!=', true ]
					],
				],
				'prevent_sortable' => [
					'inline' => true,
					'small' => true,
					'label' => esc_html__( 'Prevent column from being sortable', 'bricks' ),
					'type'  => 'checkbox',
				],
			],
			'default'     => [
				[
					'title'      => esc_html__( 'Column', 'bricks' ),
				],
			],
			'required' => [
				[ 'maybeDynamic', '=', 'static' ]
			],
		];

		$this->controls['row_sep'] = [
			'tab'   => 'content',
			'type'  => 'separator',
			'group'			=> 'table_content_rows_group',
			'label' => esc_html__( 'Add rows', 'bricks' ),
			'description'   => esc_html__( 'Add the required number of rows, then add cells into each row. Number of rows visible will be depending on the pagination settings.', 'bricks' ),
			'required' => ['maybeDynamic', '=', 'static'],
		  ];

		$this->controls['row_items'] = [
			'tab'           => 'content',
			'type'          => 'repeater',
			'group'			=> 'table_content_rows_group',
			'label'         => esc_html__( 'Rows > Cells', 'bricks' ),
			'placeholder'   => esc_html__( 'Row', 'bricks' ),
			'titleProperty' => 'title',
			'fields'        => [
				'cell'       => [
					'type'    => 'repeater',
					'label'   => esc_html__( 'Cell', 'bricks' ),
					'placeholder'   => esc_html__( 'Cell', 'bricks' ),
					'titleProperty' => 'title',
					'fields'  => [
						'cell_content_text'       => [
							'type'    => 'text',
							'label'   => esc_html__( 'Cell content', 'bricks' ),
							'hasDynamicData' => false,
							'required' => ['cell_content_type', '!=', true],
						],
						'cell_content_editor'       => [
							'type'    => 'editor',
							'label'   => esc_html__( 'Cell content', 'bricks' ),
							'required' => ['cell_content_type', '=', true],
						],
						'cell_content_type' => [
							'type' => 'checkbox',
							'label'   => esc_html__( 'WYSIWYG Editor', 'bricks' ),
						],
					],
				],
			],
			'default'     => [
				[
					'title'      => esc_html__( 'Row', 'bricks' ),
				],
			],
			'required' => ['maybeDynamic', '=', 'static'],
		];

		$this->controls['config_sep'] = [
			'tab'   => 'content',
			'type'  => 'separator',
			'group'			=> 'table_content_group',
		];

		$this->controls['maybe_sortable'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Sortable columns', 'bricks' ),
			'group'			=> 'table_content_group',
			'type' => 'select',
			'options' => [
			  'true' => esc_html__( 'Enable', 'bricks' ),
			  'false' => esc_html__( 'Disable', 'bricks' ),
			],
			'inline'      => true,
			'clearable' => false,
			'placeholder' => esc_html__( 'Disable', 'bricks' ),
			//'default' => 'true',
		  ];

		  $this->controls['resizable'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Resizable columns', 'bricks' ),
			'group'			=> 'table_content_group',
			'type' => 'select',
			'options' => [
			  'true' => esc_html__( 'Enable', 'bricks' ),
			  'false' => esc_html__( 'Disable', 'bricks' ),
			],
			'inline'      => true,
			'clearable' => false,
			'placeholder' => esc_html__( 'Disable', 'bricks' ),
			//'default' => 'true',
		  ];

		  $this->controls['whitespace'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Prevent text wrap', 'bricks' ),
			'group'			=> 'table_content_group',
			'type' => 'select',
			'options' => [
			  'nowrap' => esc_html__( 'Enable', 'bricks' ),
			  'normal' => esc_html__( 'Disable', 'bricks' ),
			],
			'inline'      => true,
			'clearable' => false,
			'placeholder' => esc_html__( 'Enable', 'bricks' ),
			//'default' => 'true',
		  ];

		  $this->controls['cellOverflow'] = [
			'tab' => 'content',
			'group' => 'table_content_group',
			'label' => esc_html__( 'Cell overflow', 'bricks' ),
			'type' => 'select',
			'options' => [ 
			  'auto' => esc_html__( 'Auto', 'bricks' ),
			  'scroll' => esc_html__( 'Scroll', 'bricks' ),
			  'visible' => esc_html__( 'Visible', 'bricks' ),
			  'hidden' => esc_html__( 'Hidden', 'bricks' ),
			  'clip' => esc_html__( 'Clip', 'bricks' ),
			],
			'inline'  => true,
			'css' => [
			  [
				'property' => 'overflow',
				'selector' => 'td.gridjs-td',
			  ]
			],
			'placeholder' => esc_html__( 'Auto', 'bricks' ),
		  ];

		  $this->controls['noRecordsFound'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Text shown if no rows found', 'bricks' ),
			'group'	=> 'table_content_group',
			'type' => 'text',
			'placeholder' => 'No matching records found',
		  ];


		  /* searchable */

		  $this->controls['maybe_searchable'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Searchable', 'bricks' ),
			'type' => 'select',
			'group'			=> 'search_group',
			'options' => [
			  'true' => esc_html__( 'Enable', 'bricks' ),
			  'false' => esc_html__( 'Disable', 'bricks' ),
			],
			'inline'      => true,
			'clearable' => false,
			'placeholder' => esc_html__( 'Disable', 'bricks' ),
		  ];

		  $this->controls['search_text'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Search placeholder', 'bricks' ),
			'group'			=> 'search_group',
			'type' => 'text',
			'placeholder' => 'Search..',
			'required' => ['maybe_searchable', '=', 'true'],
		  ];

		  $this->controls['search_sep'] = [
			'tab'   => 'content',
			'group'	=> 'search_group',
			'type'  => 'separator',
			'label' => esc_html__( 'Style search', 'bricks' ),
			'required' => ['maybe_searchable', '=', 'true'],
		  ];

		  $this->controls['search_input_padding'] = [
			'tab' => 'content',
			'group' => 'search_group',
			'label' => esc_html__( 'Input padding', 'bricks' ),
			'type' => 'dimensions',
			'css' => [
			  [
				'property' => 'padding',
				'selector' => '.gridjs-search-input',
			  ]
			],
			'required' => ['maybe_searchable', '=', 'true'],
		  ];

		  $this->controls['search_input_margin'] = [
			'tab' => 'content',
			'group' => 'search_group',
			'label' => esc_html__( 'Input margin', 'bricks' ),
			'type' => 'dimensions',
			'css' => [
			  [
				'property' => 'margin',
				'selector' => '.gridjs-search-input',
			  ]
			],
			'required' => ['maybe_searchable', '=', 'true'],
		  ];

		  $this->controls['search_width'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Width', 'bricks' ),
			'group' => 'search_group',
			'type' => 'number',
			'min' => 0,
			'max' => 10,
			'step' => '1',
			'units' => 'px',
			'inline' => true,
			'css' => [
				[
				'property' => 'width',
				'selector' => '.gridjs-search',
				],
			],
			'required' => ['maybe_searchable', '=', 'true'],
		  ];

		  $this->controls['search_float'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Float', 'bricks' ),
			'type' => 'select',
			'group'			=> 'search_group',
			'options' => [
			  'left' => esc_html__( 'Left', 'bricks' ),
			  'right' => esc_html__( 'Right', 'bricks' ),
			  'none' => esc_html__( 'None', 'bricks' )
			],
			'css' => [
				[
				'property' => 'float',
				'selector' => '.gridjs-search',
				],
			],
			'placeholder' => esc_html__( 'Left', 'bricks' ),
			'inline'      => true,
			'clearable' => false,
			'required' => ['maybe_searchable', '=', 'true'],
		  ];

		  $this->controls['search_color'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Color', 'bricks' ),
			'group'			=> 'search_group',
			'type' => 'color',
			'inline' => true,
			'css' => [
			  [
				'property' => 'color',
				'selector' => 'input.gridjs-input',
			  ]
			],
			'required' => ['maybe_searchable', '=', 'true'],
		  ];

		  $this->controls['placeholdersearch_color'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Placeholder color', 'bricks' ),
			'group'			=> 'search_group',
			'type' => 'color',
			'inline' => true,
			'css' => [
			  [
				'property' => 'color',
				'selector' => 'input.gridjs-input::placeholder',
			  ]
			],
			'required' => ['maybe_searchable', '=', 'true'],
		  ];

		$this->controls['search_bg'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Background', 'bricks' ),
			'group'			=> 'search_group',
			'type' => 'color',
			'inline' => true,
			'css' => [
			  [
				'property' => 'background-color',
				'selector' => 'input.gridjs-input',
			  ]
			],
			'required' => ['maybe_searchable', '=', 'true'],
		  ];

		  $this->controls['searchBorder'] = [
			'tab'   => 'content',
			'group' => 'search_group',
			'type'  => 'border',
			'label' => esc_html__( 'Border', 'bricks' ),
			'css'   => [
				[
					'property' => 'border',
					'selector' => 'input.gridjs-input'
				],
			],
			'required' => ['maybe_searchable', '=', 'true'],
		];

		$this->controls['searchTypography'] = [
			'tab'    => 'content',
			'group'  => 'search_group',
			'type'   => 'typography',
			'label'  => esc_html__( 'Typography', 'bricks' ),
			'css'    => [
				[
					'property' => 'font',
					'selector' => 'input.gridjs-input'
				],
			],
			'required' => ['maybe_searchable', '=', 'true'],
		];


		  /* pagination */
		  $this->controls['maybe_pagination'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Pagination', 'bricks' ),
			'type' => 'select',
			'group'			=> 'pagination_group',
			'options' => [
			  'true' => esc_html__( 'Enable', 'bricks' ),
			  'false' => esc_html__( 'Disable', 'bricks' ),
			],
			'inline'      => true,
			'clearable' => false,
			'placeholder' => esc_html__( 'Enable', 'bricks' ),
		  ];

		  

		  $this->controls['rows_per_page'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Rows per page', 'bricks' ),
			'group'			=> 'pagination_group',
			'type' => 'number',
			'min' => 0,
			'max' => 100,
			'step' => 1, // Default: 1
			'inline' => true,
			'placeholder' => esc_html__( '5', 'bricksextras' ),
			'required' => ['maybe_pagination', '!=', 'false'],
		  ];

		  $this->controls['buttons_count'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Buttons count', 'bricks' ),
			'group'	=> 'pagination_group',
			'type' => 'number',
			'min' => 0,
			'max' => 100,
			'step' => 1, // Default: 1
			'inline' => true,
			'placeholder' => esc_html__( '2', 'bricksextras' ),
			'required' => ['maybe_pagination', '!=', 'false'],
		  ];

		  
		  $this->controls['maybe_summary'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Summary', 'bricks' ),
			'type' => 'select',
			'group'			=> 'pagination_group',
			'options' => [
			  'true' => esc_html__( 'Enable', 'bricks' ),
			  'false' => esc_html__( 'Disable', 'bricks' ),
			],
			'inline'      => true,
			'clearable' => false,
			'placeholder' => esc_html__( 'Enable', 'bricks' ),
			'required' => ['maybe_pagination', '!=', 'false'],
		  ];

		  $this->controls['pagination_text_sep'] = [
			'tab'   => 'content',
			'group'	=> 'pagination_group',
			'type'  => 'separator',
			'label' => esc_html__( 'Summary text', 'bricks' ),
			'required' => [
				['maybe_pagination', '!=', 'false'],
				['maybe_summary', '!=', 'false'],
			]
		  ];

		  $this->controls['showing_text'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Showing', 'bricks' ),
			'group'			=> 'pagination_group',
			'type' => 'text',
			'inline' => true,
			'placeholder' => 'Showing',
			'required' => [
				['maybe_pagination', '!=', 'false'],
				['maybe_summary', '!=', 'false'],
			]
		  ];

		  $this->controls['to_text'] = [
			'tab' => 'content',
			'label' => esc_html__( 'to', 'bricks' ),
			'group'			=> 'pagination_group',
			'type' => 'text',
			'inline' => true,
			'placeholder' => 'to',
			'required' => [
				['maybe_pagination', '!=', 'false'],
				['maybe_summary', '!=', 'false'],
			]
		  ];

		  $this->controls['of_text'] = [
			'tab' => 'content',
			'label' => esc_html__( 'of', 'bricks' ),
			'group'			=> 'pagination_group',
			'type' => 'text',
			'inline' => true,
			'placeholder' => 'of',
			'required' => [
				['maybe_pagination', '!=', 'false'],
				['maybe_summary', '!=', 'false'],
			]
		  ];

		  $this->controls['results_text'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Results', 'bricks' ),
			'group'			=> 'pagination_group',
			'type' => 'text',
			'inline' => true,
			'placeholder' => 'Results',
			'required' => [
				['maybe_pagination', '!=', 'false'],
				['maybe_summary', '!=', 'false'],
			]
		  ];

		  $this->controls['style_footer_sep'] = [
			'tab'   => 'content',
			'group'	=> 'pagination_group',
			'type'  => 'separator',
			'label' => esc_html__( 'Style footer', 'bricks' ),
			'required' => ['maybe_pagination', '!=', 'false'],
		  ];

		  $this->controls['pagination_typography'] = [
			'tab'    => 'content',
			'group'    => 'pagination_group',
			'type'   => 'typography',
			'label'  => esc_html__( 'Typography', 'bricks' ),
			'css'    => [
				[
					'property' => 'font',
					'selector' => '.gridjs-pagination',
				],
			],
			'required' => ['maybe_pagination', '!=', 'false'],
		];

		$this->controls['footer_color'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Text color', 'bricks' ),
			'group'			=> 'pagination_group',
			'type' => 'color',
			'inline' => true,
			'css' => [
			  [
				'property' => 'color',
				'selector' => '.gridjs-pagination',
			  ]
			],
			'required' => ['maybe_pagination', '!=', 'false'],
		  ];

		$this->controls['footer_bg'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Background', 'bricks' ),
			'group'			=> 'pagination_group',
			'type' => 'color',
			'inline' => true,
			'css' => [
			  [
				'property' => 'background-color',
				'selector' => '.gridjs-footer',
			  ]
			],
			'required' => ['maybe_pagination', '!=', 'false'],
		  ];

		  $this->controls['footer_border'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Border', 'bricks' ),
			'group'			=> 'pagination_group',
			'type' => 'border',
			'inline' => true,
			'css' => [
			  [
				'property' => 'border',
				'selector' => '.gridjs-footer',
			  ]
			],
			'required' => ['maybe_pagination', '!=', 'false'],
		  ];

		  $this->controls['footer_boxshadow'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Box shadow', 'bricks' ),
			'group'			=> 'pagination_group',
			'type' => 'box-shadow',
			'inline' => true,
			'css' => [
			  [
				'property' => 'box-shadow',
				'selector' => '.gridjs-footer',
			  ]
			],
			'required' => ['maybe_pagination', '!=', 'false'],
		  ];

		  $this->controls['footer_padding'] = [
			'tab' => 'content',
			'group'			=> 'pagination_group',
			'label' => esc_html__( 'Padding', 'bricks' ),
			'type' => 'dimensions',
			'css' => [
			  [
				'property' => 'padding',
				'selector' => '.gridjs-footer',
			  ]
			],
			'placeholder' => [
			  'top' => '12px',
			  'right' => '24px',
			  'bottom' => '12px',
			  'left' => '24px',
			],
			'required' => ['maybe_pagination', '!=', 'false'],
		  ];

		  $this->controls['pagination_buttons_sep'] = [
			'tab'   => 'content',
			'group'	=> 'pagination_group',
			'type'  => 'separator',
			'label' => esc_html__( 'Pagination buttons', 'bricks' ),
			'required' => ['maybe_pagination', '!=', 'false'],
		  ];

		  $this->controls['maybe_pagination_buttons'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Prev/Next buttons', 'bricks' ),
			'type' => 'select',
			'group'			=> 'pagination_group',
			'options' => [
			  'true' => esc_html__( 'Enable', 'bricks' ),
			  'false' => esc_html__( 'Disable', 'bricks' ),
			],
			'inline'      => true,
			'clearable' => false,
			'placeholder' => esc_html__( 'Enable', 'bricks' ),
			'required' => ['maybe_pagination', '!=', 'false'],
		  ];


		  $this->controls['previous_text'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Previous text', 'bricks' ),
			'group'			=> 'pagination_group',
			'type' => 'text',
			'inline' => true,
			'placeholder' => 'Previous',
			'required' => ['maybe_pagination', '!=', 'false'],
		  ];
		  

		  $this->controls['next_text'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Next text', 'bricks' ),
			'group'			=> 'pagination_group',
			'type' => 'text',
			'inline' => true,
			'placeholder' => 'Next',
			'required' => ['maybe_pagination', '!=', 'false'],
		  ];

		  $this->controls['pagination_color'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Color', 'bricks' ),
			'group'			=> 'pagination_group',
			'type' => 'color',
			'small' => true,
			'inline' => true,
			'css' => [
			  [
				'property' => 'color',
				'selector' => '.gridjs-pagination .gridjs-pages button',
			  ]
			],
			'required' => ['maybe_pagination', '!=', 'false'],
		  ];

		  $this->controls['pagination_bg'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Background', 'bricks' ),
			'group'			=> 'pagination_group',
			'type' => 'color',
			'small' => true,
			'inline' => true,
			'css' => [
			  [
				'property' => 'background-color',
				'selector' => '.gridjs-pagination .gridjs-pages button',
			  ]
			],
			'required' => ['maybe_pagination', '!=', 'false'],
		  ];

		  $this->controls['pagination_padding'] = [
			'tab' => 'content',
			'group'			=> 'pagination_group',
			'label' => esc_html__( 'Button padding', 'bricks' ),
			'type' => 'dimensions',
			'css' => [
			  [
				'property' => 'padding',
				'selector' => '.gridjs-pagination .gridjs-pages button',
			  ]
			],
			'placeholder' => [
			  'top' => '5px',
			  'right' => '14px',
			  'bottom' => '5px',
			  'left' => '14px',
			],
			'required' => ['maybe_pagination', '!=', 'false'],
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
				'selector' => 'th.gridjs-th',
			  ],
			  [
				'property' => 'background-color',
				'selector' => '.x-dynamic-table_stacked td:before',
			  ]
			],
		  ];

		  $this->controls['header_color'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Header text', 'bricks' ),
			'group'			=> 'header_group',
			'type' => 'color',
			'inline' => true,
			'css' => [
			  [
				'property' => 'color',
				'selector' => 'th.gridjs-th',
			  ],
			  [
				'property' => 'color',
				'selector' => '.x-dynamic-table_stacked td:before',
			  ]
			],
		  ];

		  $this->controls['header_typography'] = [
			'tab'    => 'content',
			'group'    => 'header_group',
			'type'   => 'typography',
			'label'  => esc_html__( 'Typography', 'bricks' ),
			'css'    => [
				[
					'property' => 'font',
					'selector' => 'th.gridjs-th',
				],
				[
					'property' => 'font',
					'selector' => '.x-dynamic-table_stacked td:before',
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
				'selector' => 'th.gridjs-th',
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

		  $this->controls['row_bg'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Background', 'bricks' ),
			'group'			=> 'rows_group',
			'type' => 'color',
			'inline' => true,
			'css' => [
			  [
				'property' => 'background-color',
				'selector' => 'td.gridjs-td',
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
				'selector' => '.gridjs-tr:nth-child(even) td.gridjs-td',
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
				'selector' => 'td.gridjs-td',
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
				'selector' => '.gridjs-tr:nth-child(even) td.gridjs-td',
			  ]
			],
		  ];

		  $this->controls['row_border'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Border color', 'bricks' ),
			'group'			=> 'rows_group',
			'type' => 'color',
			'inline' => true,
			'css' => [
			  [
				'property' => '--x-grid-border-color',
				'selector' => ''
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
					'selector' => 'td.gridjs-td',
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
				'selector' => 'td.gridjs-td',
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
			'label' => esc_html__( 'Row height', 'bricks' ),
			'group'			=> 'rows_group',
			'type' => 'number',
			'units' => true,
			'inline' => true,
			'css' => [
			  [
				'property' => 'height',
				'selector' => 'td.gridjs-td',
			  ]
			],
		  ];


		  /* table wrapper */

		  $this->controls['wrapper_bg'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Background', 'bricks' ),
			'group'			=> 'wrapper_group',
			'type' => 'color',
			'inline' => true,
			'css' => [
			  [
				'property' => 'background-color',
				'selector' => '.gridjs-wrapper',
			  ]
			],
		  ];

		  $this->controls['wrapper_color'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Text color', 'bricks' ),
			'group'			=> 'wrapper_group',
			'type' => 'color',
			'inline' => true,
			'css' => [
			  [
				'property' => 'color',
				'selector' => '.gridjs-wrapper',
			  ]
			],
		  ];

		  $this->controls['wrapper_border'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Border', 'bricks' ),
			'group'	=> 'wrapper_group',
			'type' => 'border',
			'inline' => true,
			'css' => [
			  [
				'property' => 'border',
				'selector' => '.gridjs-wrapper',
			  ]
			],
		  ];

		  $this->controls['wrapper_shadow'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Box shadow', 'bricks' ),
			'group'	=> 'wrapper_group',
			'type' => 'box-shadow',
			'inline' => true,
			'css' => [
			  [
				'property' => 'box-shadow',
				'selector' => '.gridjs-wrapper',
			  ]
			],
		  ];

		  $this->controls['wrapper_typography'] = [
			'tab'    => 'content',
			'group'    => 'wrapper_group',
			'type'   => 'typography',
			'label'  => esc_html__( 'Typography', 'bricks' ),
			'css'    => [
				[
					'property' => 'font',
					'selector' => '.gridjs-wrapper',
				],
			],
		];

		$this->controls['wrapper_padding'] = [
			'tab' => 'content',
			'group'	=> 'wrapper_group',
			'label' => esc_html__( 'Padding', 'bricks' ),
			'type' => 'dimensions',
			'css' => [
			  [
				'property' => 'padding',
				'selector' => '.gridjs-wrapper',
			  ]
			],
		  ];

		  $this->controls['body_height'] = [
			'tab'    => 'content',
			'group'    => 'wrapper_group',
			'type'   => 'number',
			'units'	=> true,
			'label'  => esc_html__( 'Fixed height', 'bricks' ),
			'css'    => [
				[
					'property' => 'height',
					'selector' => '.x-dynamic-table_container:not(.x-dynamic-table_stacked) .gridjs-wrapper',
					'important' => true
				],
			],
		];


		/* mobile version */
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
			'min' => 0,
			'max' => 100,
			'step' => 1, // Default: 1
			'inline' => true,
			'placeholder' => '',
			'units' => true,
			'css' => [
				[
				  'property' => 'margin-bottom',
				  'selector' => '.x-dynamic-table_stacked .gridjs-tr',
				]
			  ],
		  ];

		$this->controls['stackLabelsSep'] = [
			'tab'   => 'content',
			'type'  => 'separator',
			'label' => esc_html__( 'Column labels', 'bricks' ),
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
				  'selector' => '&:not([data-x-table*=stackLabels]) .x-dynamic-table_stacked td:before',
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
				  'selector' => '&:not([data-x-table*=stackLabels]) .x-dynamic-table_stacked td:before',
				]
			  ],
			  'required' => [ 'stackLabels', '!=', 'true' ],
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
				  'selector' => '&:not([data-x-table*=stackLabels]) .x-dynamic-table_stacked td',
				]
			  ],
			  'required' => [ 'stackLabels', '!=', 'true' ],
		  ];

		  $this->controls['stackLabels'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Move cell content onto new line', 'bricks' ),
			'type' => 'select',
			'group'	=> 'mobile_group',
			'options' => [
			  'true' => esc_html__( 'True', 'bricks' ),
			  'false' => esc_html__( 'False', 'bricks' ),
			],
			'inline'      => true,
			'small' => false,
			'placeholder' => esc_html__( 'Disable', 'bricks' ),
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
				  'selector' => '&[data-x-table*=stackLabels] .x-dynamic-table_stacked td span',
				]
			  ],
			  'required' => [ 'stackLabels', '=', 'true' ],
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
				  'selector' => '&[data-x-table*=stackLabels] .x-dynamic-table_stacked td span',
				]
			  ],
			  'required' => [ 'stackLabels', '=', 'true' ],
		  ];

		  

  }

  // Methods: Frontend-specific
  public function enqueue_scripts() {

	wp_enqueue_script( 'x-table', BRICKSEXTRAS_URL . 'components/assets/js/' . \BricksExtras\Helpers::maybeMinifyScripts('dynamictable') . '.js', '', '1.1.0', true );

	wp_localize_script(
		'x-table',
		'xTable',
		[
			'Instances' => [],
			'Columns' => [],
			'Data' => [],
		]
	);

	if (! \BricksExtras\Helpers::elementCSSAdded($this->name) ) {
		wp_enqueue_style( 'x-table', BRICKSEXTRAS_URL . 'components/assets/css/dynamictable.css', [], '' );
	}

  }



  public function render() {

		$settings = $this->settings;

		$header_items = ! empty( $settings['header_items'] ) ? $settings['header_items'] : false;
		$content_items = ! empty( $settings['content_items'] ) ? $settings['content_items'] : false;
		$content_items_static = ! empty( $settings['content_items_static'] ) ? $settings['content_items_static'] : [];
		$row_items = ! empty( $settings['row_items'] ) ? $settings['row_items'] : false;
		$maybeDynamic = isset( $settings['maybeDynamic'] ) ? $settings['maybeDynamic'] : 'dynamic';

		$stackLabels = isset( $settings['stackLabels'] ) ? $settings['stackLabels'] : 'false';

		$config = [
			'sort' => isset( $settings['maybe_sortable'] ) ? $settings['maybe_sortable'] : false,
			'resizable' => isset( $settings['resizable'] ) ? $settings['resizable'] : false,
			'whitespace' => isset( $settings['whitespace'] ) ? $settings['whitespace'] : 'nowrap',
			'search' => isset( $settings['maybe_searchable'] ) ? $settings['maybe_searchable'] : false,
			'placeholder' => isset( $settings['search_text'] ) ? $settings['search_text'] : 'Search..',
			'stack' => isset( $settings['maybeStack'] ) ? $settings['maybeStack'] : 'false',
			'noRecordsFound' => isset( $settings['noRecordsFound'] ) ? esc_attr__( $settings['noRecordsFound'] ) : esc_attr__( 'No matching records found' ),
		];

		if ( !isset( $settings['maybe_pagination'] ) || 'true' === $settings['maybe_pagination'] ) {
			$config += [
				'rowsPerPage' => isset( $settings['rows_per_page']  ) ? intval( $settings['rows_per_page'] ) : 5,
				'previous' => isset( $settings['previous_text'] ) ? esc_attr__( $settings['previous_text'] ) : esc_attr__( 'Previous' ),
				'next' => isset( $settings['next_text'] ) ? esc_attr__( $settings['next_text'] ) : esc_attr__( 'Next' ),
				'showing' => isset( $settings['showing_text'] ) ? esc_attr__( $settings['showing_text'] ) : esc_attr__( 'Showing' ),
				'results' => isset( $settings['results_text'] ) ? esc_attr__( $settings['results_text'] ) : esc_attr__( 'Results' ),
				'paginationButtons' => ! empty( $settings['maybe_pagination_buttons'] ) ? $settings['maybe_pagination_buttons'] : 'true',
				'summary' => isset( $settings['maybe_summary'] ) ? $settings['maybe_summary'] : 'true',
				'to' => isset( $settings['to_text'] ) ? esc_attr__( $settings['to_text'] ) : esc_attr__( 'to' ),
				'of' => isset( $settings['of_text'] ) ? esc_attr__( $settings['of_text'] ) : esc_attr__( 'of' ),
				'buttonsCount' => isset( $settings['buttons_count'] ) ? intval( $settings['buttons_count'] ) : 2,
			];
		}

		if ( 'true' === $stackLabels ) {
			$config += [
				'stackLabels' => $stackLabels
			];
		}

		$this->set_attribute( '_root', 'data-x-table', wp_json_encode( $config ) );

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


		echo "<div {$this->render_attributes( '_root' )}>";

		echo "<table class='x-dynamic-table_table'>";

		if ( 'dynamic' === $maybeDynamic ) {

			/* table header */
			if ( $content_items ) {			

				echo "<thead><tr>";

				foreach ( $content_items as $index => $content_item ) {

					$header_title = ! empty( $content_item['title'] ) ? esc_attr__( bricks_render_dynamic_data( $content_item['title'] ) ) : '';

					$content_widths = ! empty( $content_item['width'] ) ? $content_item['width'] : null;

					$data_type = 'string';
				
					if ( isset( $content_item['data_type'] ) ) {
						$data_type = 'number';
					}

					if ( isset( $content_item['data_type_date'] ) ) {
						$data_type = 'date';
					}

					if ( isset( $content_item['prevent_sortable'] ) ) {
						$data_type = 'prevent_sortable';
					}

					$attributes = ! empty( $content_item['attributes'] ) ? $content_item['attributes'] : false;

					$column_attributes = [];
					$column_attribute = '';

					if ($attributes) {
						foreach ( $attributes as $attribute ) {

							$attribute_name = isset( $attribute['name'] ) ? esc_attr( $attribute['name'] ) : false;
							$attribute_value = isset( $attribute['value'] ) ? esc_attr( $attribute['value'] ) : false;

							if ( !!$attribute_name && !!$attribute_value ) {

								$column_attributes += [
									$attribute_name => $attribute_value
								];

							}
						}
					}

					if (!!$column_attributes) {
						$column_attribute = "data-x-column-attributes='" . wp_json_encode( $column_attributes ) . "'";
					}

					echo !empty( $column_attributes ) ? '<th class="label" data-x-type="' . esc_attr( $data_type ) . '" data-x-column="' . esc_attr( $content_widths ) . '" ' . $column_attribute . '>' . $header_title . '</th>' : '<th class="label" data-x-type="' . esc_attr( $data_type ) . '" data-x-column="' . esc_attr( $content_widths ) . '">' . $header_title . '</th>';

				}

				echo "</tr></thead>";

			}

		} else {

			/* table header */
			if ( $content_items_static ) {			

				echo "<thead><tr>";

				foreach ( $content_items_static as $index => $content_item ) {

					$header_title = ! empty( $content_item['title'] ) ? esc_attr__( bricks_render_dynamic_data( $content_item['title'] ) ) : '';

					$content_widths = ! empty( $content_item['width'] ) ? $content_item['width'] : null;

					$data_type = 'string';
				
					if ( isset( $content_item['data_type'] ) ) {
						$data_type = 'number';
					}

					if ( isset( $content_item['data_type_date'] ) ) {
						$data_type = 'date';
					}

					if ( isset( $content_item['prevent_sortable'] ) ) {
						$data_type = 'prevent_sortable';
					}

					$attributes = ! empty( $content_item['attributes'] ) ? $content_item['attributes'] : false;

					$column_attributes = [];
					$column_attribute = '';

					if ($attributes) {
						foreach ( $attributes as $attribute ) {
							
							$attribute_name = isset( $attribute['name'] ) ? esc_attr( $attribute['name'] ) : false;
							$attribute_value = isset( $attribute['value'] ) ? esc_attr( $attribute['value'] ) : false;

							if ( !!$attribute_name && !!$attribute_value ) {

								$column_attributes += [
									$attribute_name => $attribute_value
								];

							}
						}
					}

					if (!!$column_attributes) {
						$column_attribute = "data-x-column-attributes='" . wp_json_encode( $column_attributes ) . "'";
					}

					echo !empty( $column_attributes ) ? '<th class="label" data-x-type="' . esc_attr( $data_type ) . '" data-x-column="' . esc_attr( $content_widths ) . '" ' . $column_attribute . '>' . $header_title . '</th>' : '<th class="label" data-x-type="' . esc_attr( $data_type ) . '" data-x-column="' . esc_attr( $content_widths ) . '">' . $header_title . '</th>';

				}

				echo "</tr></thead>";

			}

		}

		

		/* table content */

		echo "<tbody>";

		/* dynamic */

		if ( 'dynamic' === $maybeDynamic ) {

			// Query Loop
			if ( isset( $settings['hasLoop'] ) ) {
				$query = new \Bricks\Query( [
					'id'       => $this->id,
					'settings' => $settings,
				] );

				if ( $query->count === 0 ) {
					// No results: Empty by default (@since 1.4)
					$no_results_content = $query->get_no_results_content();

					if ( empty( $no_results_content ) ) {
						echo $this->render_element_placeholder( ['title' => esc_html__( 'No results', 'bricks' )] );
					}
				}

			}
			

			// Query Loop
			if ( isset( $settings['hasLoop'] ) ) {
				$content_item = $content_items[0];

				echo wp_kses_post( $query->render( [ $this, 'render_repeater_item' ], compact( 'content_items' ) ) );
				$query->destroy();
				unset( $query );
			}

		} else { /* static */

			if ( $row_items ) {		

				foreach ( $row_items as $index => $row_item ) {
			
					echo wp_kses_post( $this->render_repeater_item_static( $row_item, $content_items_static ) );
	
				}
	
			}

		}

		echo "</tbody>";  

		echo "</table>";

		echo "<div class='x-dynamic-table_container'></div>";

		echo "</div>";

    
  }

  public function render_repeater_item( $content_items ) {

	$settings = $this->settings;
	$index    = $this->loop_index;

	// Render
	ob_start();
	?>
	<tr>
	<?php	
	
		if ( $content_items ) {			

			foreach ( $content_items as $index => $content_item ) {

				$data_type = 'string';
				
				if ( isset( $content_item['data_type'] ) ) {
					$data_type = 'number';
				}

				if ( isset( $content_item['data_type_date'] ) ) {
					$data_type = 'date';
				}

				if ( isset( $content_item['prevent_sortable'] ) ) {
					$data_type = 'prevent_sortable';
				}

				echo '<td data-x-type="'. $data_type .'">';

				$content_datas = ! empty( $content_item['data'] ) ? $content_item['data'] : '';

				echo bricks_render_dynamic_data( $content_datas );

				echo '</td>';

			}			

		}
	
	?>
  	</tr>
	<?php
	$html = ob_get_clean();

	$this->loop_index++;

	return $html;

  }

  public function render_repeater_item_static( $row_item, $content_items_static ) {

	$settings = $this->settings;

	// Render
	ob_start();
	?>
	<tr>
		<?php	

				$data_types = [];

				/* get column data types */
				if ( $content_items_static ) {			

					foreach ( $content_items_static as $index => $content_item_static ) {

						$data_type = 'string';
				
						if ( isset( $content_item_static['data_type'] ) ) {
							$data_type = 'number';
						}

						if ( isset( $content_item_static['data_type_date'] ) ) {
							$data_type = 'date';
						}

						if ( isset( $content_item_static['prevent_sortable'] ) ) {
							$data_type = 'prevent_sortable';
						}

						$data_types[] = $data_type;

					}

				}

				if ( isset( $row_item['cell'] ) ) {

					foreach ( array_slice( $row_item['cell'], 0, sizeof($content_items_static) ) as $index => $cell ) {

						if ( isset( $cell['cell_content_type'] ) ) {
							$cellContent = ! empty( $cell['cell_content_editor'] ) ? $cell['cell_content_editor'] : false;
						} else {
							$cellContent = ! empty( $cell['cell_content_text'] ) ? $cell['cell_content_text'] : false;
						}

						echo '<td rowspan="2" data-x-type="' . esc_attr( $data_types[$index] ) . '">';
						if ($cellContent) {
							echo wp_kses_post( $cellContent );
						}
						echo '</td>';

					}

					if ( sizeof($row_item['cell']) < sizeof($content_items_static) ) {
						echo str_repeat( '<td data-x-type=""></td>', sizeof($content_items_static) - sizeof($row_item['cell']) );
					}

				} else {
					echo str_repeat( '<td data-x-type=""></td>', sizeof( $content_items_static ) );
				}

	
	?>
  	</tr>
	<?php
	$html = ob_get_clean();

	return $html;

  }

}