<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class X_Dynamic_Chart extends \Bricks\Element {

  // Element properties
  public $category     = 'extras';
  public $name         = 'xdynamicchart'; 
  public $icon         = 'ti-bar-chart';
  public $css_selector = '';
  public $scripts      = ['xDynamicChart'];
  public $loop_index = 0;

  // Methods: Builder-specific
  public function get_label() {
	return esc_html__( 'Dynamic Chart', 'extras' );
  }
  public function set_control_groups() {

	$this->control_groups['table_content_group'] = [
		'title' => esc_html__( 'Data', 'extras' ),
		'tab' => 'content',
	];

	$this->control_groups['chart_type_group'] = [
		'title' => esc_html__( 'Chart style', 'extras' ),
		'tab' => 'content',
	];

	$this->control_groups['axis_group'] = [
		'title' => esc_html__( 'Axes', 'extras' ),
		'tab' => 'content',
		'required' => ['chart_type', '!=', 'doughnut']
	];

	$this->control_groups['tooltip_group'] = [
		'title' => esc_html__( 'Tooltips', 'extras' ),
		'tab' => 'content',
	];

	$this->control_groups['legend_group'] = [
		'title' => esc_html__( 'Legends', 'extras' ),
		'tab' => 'content',
	];

	$this->control_groups['data_labels_group'] = [
		'title' => esc_html__( 'Data labels', 'extras' ),
		'tab' => 'content',
	];

	$this->control_groups['spacing_group'] = [
		'title' => esc_html__( 'Spacing', 'extras' ),
		'tab' => 'content',
	];
	
	$this->control_groups['dataset_colors_group'] = [
		'title' => esc_html__( 'Dataset colors', 'extras' ),
		'tab' => 'content',
	];

	

  }


  public function set_controls() {

		$this->controls['chartWidth'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Width', 'extras' ),
			'inline' => true,
			'type' => 'number',
			'units'    => true,
			'css' => [
				[
					'selector' => '',  
					'property' => 'width',
				],
			],
			'placeholder' => '800px',
		];

		$this->controls['ariaLabel'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Chart aria-label', 'bricks' ),
			'type' => 'text',
			'placeholder' => esc_html__( 'Chart', 'bricks' ),
			'inline'  => true,
		];

		$this->controls['loop_sep'] = [
			'tab'   => 'content',
			'type'  => 'separator',
			//'label' => esc_html__( 'Dynamic data to populate rows', 'bricks' ),
			//'description' => esc_html__( 'Use query loop if needing to use multiple datasets', 'bricks' ),
		];

		$this->controls = array_replace_recursive( $this->controls, $this->get_loop_builder_controls() );

		$this->controls['queryLoopDatapoints'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Use query loop for..', 'bricks' ),
			'type' => 'select',
			'inline' => true,
			'options' => [
				'dataSets' =>  esc_html__( 'Data sets', 'bricks' ),
				'dataPoints' =>  esc_html__( 'Data points', 'bricks' ),
			],
			'placeholder' => esc_html__( 'Data sets', 'bricks' ),
			'required' => [ 'hasLoop', '!=', false ]
			
		];

		$this->controls['queryLoopDatapoints_Label'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Label', 'bricks' ),
			'group'			=> 'table_content_group',
			'type' => 'text',
			'required' => ['queryLoopDatapoints', '=', 'dataPoints']
		];

		$this->controls['queryLoopDatapoints_Value'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Value', 'bricks' ),
			'group'			=> 'table_content_group',
			'type' => 'text',
			'required' => ['queryLoopDatapoints', '=', 'dataPoints']
		];

		$this->controls['content_items'] = [
			'tab'           => 'content',
			'type'          => 'repeater',
			'group'			=> 'table_content_group',
			'required' => ['queryLoopDatapoints', '!=', 'dataPoints'],
			'label'         => esc_html__( 'Data points', 'bricks' ),
			//'description'   => esc_html__( 'Add the required number of data points along the x-axis, with a label and corresponding value', 'bricks' ),
			'placeholder'   => esc_html__( 'Data point', 'bricks' ),
			'titleProperty' => 'title',
			'fields'        => [
				'title'       => [
					'type'    => 'text',
					'label'   => esc_html__( 'Label', 'bricks' ),
					//'inline'  => true,
				],
				'data'       => [
					'type'    => 'text',
					'label'   => esc_html__( 'Value', 'bricks' ),
					//'inline'  => true,
				],
				'color'       => [
					'type'    => 'color',
					'label'   => esc_html__( 'Data point color', 'bricks' ),
					'description' => esc_html__( '(if using only one dataset)', 'bricks' ),
				],
			],
			'default'     => [
				[
					'title'      => esc_html__( 'January', 'bricks' ),
					'data'      => esc_html__( '20', 'bricks' ),
					'color'	    => [
						'hex' => '#1da69a',
					]
				],
				[
					'title'      => esc_html__( 'February', 'bricks' ),
					'data'      => esc_html__( '70', 'bricks' ),
					'color'	    => [
						'hex' => '#072027',
					]
				],
			]
		];

		$this->controls['xAxisUnits'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Units for values', 'bricks' ),
			'type' => 'text',
			'info' => 'Units to show next to numbers. eg % or years',
			'group'	=> 'table_content_group',
			'required' => ['xAxisDisplay', '!=', 'false'],
			'inline'      => true,
		  ];

		 $this->controls['xAxisUnitPosition'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Units position', 'bricks' ),
			'type' => 'select',
			'options' => [
				'after' => esc_html__( 'After value', 'bricks' ),
				'before' => esc_html__( 'Before value', 'bricks' ),
			  ],
			'group'	=> 'table_content_group',
			'placeholder' => esc_html__( 'After value', 'bricks' ),
			'required' => [
				['xAxisDisplay', '!=', 'false'],
				['xAxisUnits', '!=', '']
			],
			'inline'      => true,
		  ];  

		$this->controls['dataSetColorsStart'] = [
			'tab'   => 'content',
			'type'  => 'separator',
			'group'	=> 'dataset_colors_group',
			'description' => esc_html__( 'For use only if using multiple datasets. Set a colour for each set (either manually or using dynamic data). This will override any other colors set.', 'bricks' ),
		];

		$this->controls['maybeDynamicColors'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Set dataset colors', 'bricks' ),
			'type' => 'select',
			'group'	=> 'dataset_colors_group',
			'options' => [
			  'manually' => esc_html__( 'Manually', 'bricks' ),
			  'dynamic' => esc_html__( 'Use dynamic data', 'bricks' ),
			],
			'inline'      => true,
			'clearable' => false,
			'placeholder' => esc_html__( 'Manually', 'bricks' ),
		  ];


		$this->controls['datasetColor'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Dataset color', 'bricks' ),
			'group'	=> 'dataset_colors_group',
			'type' => 'text',
			'inline' => true,
			'required' => ['maybeDynamicColors', '=', 'dynamic'],
		  ];

		$this->controls['datasetItems'] = [
			'tab'           => 'content',
			'type'          => 'repeater',
			//'checkLoop'   => true,
			'group'			=> 'dataset_colors_group',
			'label'         => esc_html__( 'Data set colours', 'bricks' ),
			//'description'         => esc_html__( 'If using multiple datasets, set the styles individually', 'bricks' ),
			'titleProperty' => 'type',
			'fields'        => [
				'color'       => [
					'type'    => 'color',
					'label'   => esc_html__( 'Color', 'bricks' ),
					//'inline'  => true,
				],
			],
			'required' => ['maybeDynamicColors', '!=', 'dynamic'],
		];

		$this->controls['chartDirection'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Chart direction', 'bricks' ),
			'type' => 'select',
			'group'	=> 'chart_type_group',
			'options' => [
			  'horizontal' => esc_html__( 'Horizontal', 'bricks' ),
			  'vertical' => esc_html__( 'Vertical', 'bricks' ),
			],
			'inline'      => true,
			'clearable' => false,
			'placeholder' => esc_html__( 'Vertical', 'bricks' ),
			'required' => ['chart_type', '!=', 'doughnut'],
		  ];

		
		$this->controls['chart_type'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Chart type', 'bricks' ),
			'type' => 'select',
			'group'	=> 'chart_type_group',
			'options' => [
			  'bar' => esc_html__( 'Bar', 'bricks' ),
			  'line' => esc_html__( 'Line', 'bricks' ),
			  //'bubble' => esc_html__( 'Bubble', 'bricks' ),
			  //'polarArea' => esc_html__( 'Polar Area', 'bricks' ),
			  //'doughnut' => esc_html__( 'Doughnut', 'bricks' ),
			  'doughnut' => esc_html__( 'Pie / 	Doughnut', 'bricks' ),
			  //'radar' => esc_html__( 'Radar', 'bricks' ),
			  //'scatter' => esc_html__( 'Scatter', 'bricks' ),
			  
			],
			'inline'      => true,
			'clearable' => false,
			'placeholder' => esc_html__( 'bar', 'bricks' ),
		  ];


		  $this->controls['tension'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Line tension', 'bricks' ),
			'type' => 'slider',
			'group'	=> 'chart_type_group',
			'units' => false,
			'min' => 0,
			'max' => 1,
			'step' => .01,
			'placeholder' => '0',
			'required' => ['chart_type', '=', 'line'],
		  ];

		  $this->controls['lineColor'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Line color', 'bricks' ),
			//'description' => esc_html__( 'Note - if using multiple datasets, each line color will be overridden by the dataset color', 'bricks' ),
			'type' => 'color',
			'group'	=> 'chart_type_group',
			'required' => ['chart_type', '=', ['line','doughnut']],
			'placeholder' => [
				'hex' => '#44889c',
			  ],
			
		  ];

		  $this->controls['pieBorderWidth'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Line width', 'bricks' ),
			'type' => 'number',
			'group'	=> 'chart_type_group',
			'units' => false,
			'min' => 0,
			'max' => 10,
			'step' => 1,
			'small' => true,
			'placeholder' => esc_html__( '0', 'bricks' ),
			'required' => ['chart_type', '=', ['doughnut']],
		  ];

		  $this->controls['pieCutOut'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Cut out % (doughnut center)', 'bricks' ),
			'type' => 'number',
			'group'	=> 'chart_type_group',
			'units' => false,
			'min' => 0,
			'max' => 100,
			'step' => 1,
			'small' => true,
			'placeholder' => esc_html__( '0', 'bricks' ),
			'required' => ['chart_type', '=', ['doughnut']],
		  ];

		  $this->controls['lineWidth'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Line width', 'bricks' ),
			'type' => 'number',
			'group'	=> 'chart_type_group',
			'units' => false,
			'min' => 0,
			'max' => 10,
			'step' => 1,
			'small' => true,
			'placeholder' => esc_html__( '2', 'bricks' ),
			'required' => ['chart_type', '=', ['line']],
		  ];

		  $this->controls['linePointRadius'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Point size', 'bricks' ),
			'group'	=> 'chart_type_group',
			'type' => 'number',
			'unit' => 'px',
			'inline' => true,
			'small' => true,
			'placeholder' => esc_html__( '3', 'bricks' ),
			'required' => ['chart_type', '=', 'line'],
		  ];

		  $this->controls['linePointBorderWidth'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Point border width', 'bricks' ),
			'group'	=> 'chart_type_group',
			'type' => 'number',
			'unit' => 'px',
			'inline' => true,
			'small' => true,
			'placeholder' => esc_html__( '1', 'bricks' ),
			'required' => ['chart_type', '=', 'line'],
		  ];

		  

		  $this->controls['barBorderRadius'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Border radius', 'bricks' ),
			'group'	=> 'chart_type_group',
			'type' => 'number',
			'unit' => 'px',
			'inline' => true,
			'small' => true,
			'placeholder' => esc_html__( '5', 'bricks' ),
			'required' => ['chart_type', '!=', ['line','doughnut']],
		  ];

		  $this->controls['rotation'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Rotation', 'bricks' ),
			'group'	=> 'chart_type_group',
			'type' => 'number',
			'inline' => true,
			'small' => true,
			'placeholder' => esc_html__( '0', 'bricks' ),
			'required' => ['chart_type', '=', ['doughnut']],
		  ];

		  $this->controls['circumference'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Circumference', 'bricks' ),
			'group'	=> 'chart_type_group',
			'type' => 'number',
			'inline' => true,
			'small' => true,
			'placeholder' => esc_html__( '360', 'bricks' ),
			'required' => ['chart_type', '=', ['doughnut']],
		  ];




		  /* x axis */

		  $this->controls['xAxisStart'] = [
			'tab'   => 'content',
			'group'  => 'axis_group',
			'type'  => 'separator',
			'label' => esc_html__( 'X-axis', 'bricks' ),
		  ];


		  $this->controls['xAxisDisplay'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Display x-axis', 'bricks' ),
			'type' => 'select',
			'group'	=> 'axis_group',
			'options' => [
			  'true' => esc_html__( 'Enable', 'bricks' ),
			  'false' => esc_html__( 'Disable', 'bricks' ),
			],
			'inline'      => true,
			'clearable' => false,
			'placeholder' => esc_html__( 'Enable', 'bricks' ),
		  ];


		  $this->controls['xAxisTitle'] = [
			'tab' => 'content',
			'label' => esc_html__( 'X-axis Title', 'bricks' ),
			'group'	=> 'axis_group',
			'type' => 'text',
			'inline' => true,
			'placeholder' => esc_html__( '', 'bricks' ),
			'required' => ['xAxisDisplay', '!=', 'false'],
		  ];

		  $this->controls['xAxisTitleColor'] = [
			'tab' => 'content',
			'label' => esc_html__( 'X-axis Title color', 'bricks' ),
			'type' => 'color',
			'group'	=> 'axis_group',
			'required' => ['xAxisDisplay', '!=', 'false'],
		  ];

		  $this->controls['xGridColor'] = [
			'tab'   => 'content',
			'group'	=> 'axis_group',
			'label' => esc_html__( 'Grid color', 'bricks' ),
			'type'  => 'color',
			'required' => ['xAxisDisplay', '!=', 'false'],
		  ];

		  $this->controls['xBorderColor'] = [
			'tab'   => 'content',
			'group'	=> 'axis_group',
			'label' => esc_html__( 'Axis Border color', 'bricks' ),
			'type'  => 'color',
			'required' => ['xAxisDisplay', '!=', 'false'],
		  ];

		  $this->controls['xAxisTicksColor'] = [
			'tab' => 'content',
			'label' => esc_html__( 'X-axis labels color', 'bricks' ),
			'type' => 'color',
			'group'	=> 'axis_group',
			'required' => ['xAxisDisplay', '!=', 'false'],
		  ];


		  /* y axis */

		  $this->controls['yAxisStart'] = [
			'tab'   => 'content',
			'group'  => 'axis_group',
			'type'  => 'separator',
			'label' => esc_html__( 'Y-axis', 'bricks' ),
		  ];

		  $this->controls['yAxisDisplay'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Display y-axis', 'bricks' ),
			'type' => 'select',
			'group'	=> 'axis_group',
			'options' => [
			  'true' => esc_html__( 'Enable', 'bricks' ),
			  'false' => esc_html__( 'Disable', 'bricks' ),
			],
			'inline'      => true,
			'clearable' => false,
			'placeholder' => esc_html__( 'Enable', 'bricks' ),
		  ];


		  $this->controls['yAxisTitle'] = [
			'tab' => 'content',
			'label' => esc_html__( 'y-axis Title', 'bricks' ),
			'group'	=> 'axis_group',
			'type' => 'text',
			'inline' => true,
			'placeholder' => esc_html__( '', 'bricks' ),
			'required' => ['yAxisDisplay', '!=', 'false'],
		  ];

		  $this->controls['yAxisTitleColor'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Y-axis Title color', 'bricks' ),
			'type' => 'color',
			'group'	=> 'axis_group',
			'required' => ['yAxisDisplay', '!=', 'false'],
		  ];

		  $this->controls['yAxisType'] = [
			'tab' => 'content',
			'label' => esc_html__( 'y-axis Type', 'bricks' ),
			'type' => 'select',
			'group'	=> 'axis_group',
			'options' => [
			  'linear' => esc_html__( 'Linear', 'bricks' ),
			  'logarithmic' => esc_html__( 'Logarithmic', 'bricks' ),
			],
			'inline'      => true,
			//'clearable' => false,
			'placeholder' => esc_html__( 'Linear', 'bricks' ),
			'required' => ['yAxisDisplay', '!=', 'false'],
		  ];

		  $this->controls['beginAtZero'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Begin At Zero', 'bricks' ),
			'type' => 'select',
			'group'	=> 'axis_group',
			'options' => [
			  'true' => esc_html__( 'Enable', 'bricks' ),
			  'false' => esc_html__( 'Disable', 'bricks' ),
			],
			'inline'      => true,
			'required' => ['yAxisDisplay', '!=', 'false'],
			'placeholder' => esc_html__( 'Enable', 'bricks' ),
		  ];

		  $this->controls['yGridColor'] = [
			'tab'   => 'content',
			'group'	=> 'axis_group',
			'label' => esc_html__( 'Grid color', 'bricks' ),
			'type'  => 'color',
			'required' => ['yAxisDisplay', '!=', 'false'],
		  ];

		  $this->controls['yBorderColor'] = [
			'tab'   => 'content',
			'group'	=> 'axis_group',
			'label' => esc_html__( 'Axis Border color', 'bricks' ),
			'type'  => 'color',
			'required' => ['yAxisDisplay', '!=', 'false'],
		  ];

		  $this->controls['yAxisTicksColor'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Y-axis Values color', 'bricks' ),
			'type' => 'color',
			'group'	=> 'axis_group',
			'required' => ['yAxisDisplay', '!=', 'false'],
		  ];

		  $this->controls['suggestedMin'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Suggested Min', 'bricks' ),
			'group'	=> 'axis_group',
			'type' => 'number',
			'units' => false,
			'inline' => true,
			'small' => true,
			'required' => ['yAxisDisplay', '!=', 'false'],
		  ];

		  $this->controls['suggestedMax'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Suggested Max', 'bricks' ),
			'group'	=> 'axis_group',
			'type' => 'number',
			'units' => false,
			'inline' => true,
			'small' => true,
			'required' => ['yAxisDisplay', '!=', 'false'],
		  ];

		  
		  /*   tooltips */

		  
		  $this->controls['tooltipDisplay'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Tooltip display', 'bricks' ),
			'type' => 'select',
			'group'	=> 'tooltip_group',
			'options' => [
			  'true' => esc_html__( 'Enable', 'bricks' ),
			  'false' => esc_html__( 'Disable', 'bricks' ),
			],
			'inline'      => true,
			'clearable' => false,
			'placeholder' => esc_html__( 'Disable', 'bricks' ),
		  ];

		  $this->controls['events'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Events', 'bricks' ),
			'type' => 'select',
			'group'	=> 'tooltip_group',
			'options' => [
			  'click' => esc_html__( 'Click', 'bricks' ),
			  'hover' => esc_html__( 'Hover & Click', 'bricks' ),
			],
			'inline'      => true,
			'clearable' => false,
			'placeholder' => esc_html__( 'Click', 'bricks' ),
		  ];

		  $this->controls['tooltipColor'] = [
			'tab'   => 'content',
			'group'	=> 'tooltip_group',
			'label' => esc_html__( 'Text color', 'bricks' ),
			'type'  => 'color',
		  ];

		  $this->controls['tooltipBorderColor'] = [
			'tab'   => 'content',
			'group'	=> 'tooltip_group',
			'label' => esc_html__( 'Border color', 'bricks' ),
			'type'  => 'color',
		  ];

		  $this->controls['tooltipBorderWidth'] = [
			'tab'   => 'content',
			'group'	=> 'tooltip_group',
			'label' => esc_html__( 'Border width', 'bricks' ),
			'type'  => 'number',
			'placeholder' => esc_html__( '1', 'bricks' ),
		  ];

		  $this->controls['tooltipCaretSize'] = [
			'tab'   => 'content',
			'group'	=> 'tooltip_group',
			'label' => esc_html__( 'Caret size', 'bricks' ),
			'type'  => 'number',
			'placeholder' => esc_html__( '5', 'bricks' ),
		  ];

		  $this->controls['tooltipBackground'] = [
			'tab'   => 'content',
			'group'	=> 'tooltip_group',
			'label' => esc_html__( 'Background color', 'bricks' ),
			'type'  => 'color',
		  ];
		  


		  /*   legend */

		  
		  $this->controls['legendDisplay'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Display legends', 'bricks' ),
			'type' => 'select',
			'group'	=> 'legend_group',
			'options' => [
			  'true' => esc_html__( 'Enable', 'bricks' ),
			  'false' => esc_html__( 'Disable', 'bricks' ),
			],
			'inline'      => true,
			'clearable' => false,
			'placeholder' => esc_html__( 'Disable', 'bricks' ),
		  ];

		  $this->controls['datasetLabel'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Legend', 'bricks' ),
			'group'	=> 'legend_group',
			'type' => 'text',
			'inline' => true,
			'placeholder' => esc_html__( '', 'bricks' ),
			//'required' => ['legendDisplay', '=', 'true'],
		  ];

		  $this->controls['legendPosition'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Position', 'bricks' ),
			'type' => 'select',
			'group'	=> 'legend_group',
			'options' => [
			  'top' => esc_html__( 'Top', 'bricks' ),
			  'left' => esc_html__( 'Left', 'bricks' ),
			  'bottom' => esc_html__( 'Bottom', 'bricks' ),
			  'right' => esc_html__( 'Right', 'bricks' ),
			  
			],
			'inline'      => true,
			'clearable' => false,
			'placeholder' => esc_html__( 'Top', 'bricks' ),
			//'required' => ['legendDisplay', '=', 'true'],
		  ];

		  $this->controls['legendAlign'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Align', 'bricks' ),
			'type' => 'select',
			'group'	=> 'legend_group',
			'options' => [
			  'start' => esc_html__( 'Start', 'bricks' ),
			  'center' => esc_html__( 'Center', 'bricks' ),
			  'end' => esc_html__( 'End', 'bricks' ),
			  
			],
			'inline'      => true,
			'clearable' => false,
			'placeholder' => esc_html__( 'Center', 'bricks' ),
			//'required' => ['legendDisplay', '=', 'true'],
		  ];

		  $this->controls['legendPadding'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Padding (px)', 'bricks' ),
			'group'	=> 'legend_group',
			'type' => 'number',
			'units' => false,
			'inline' => true,
			'small' => true,
			'placeholder' => esc_html__( '10', 'bricks' ),
			//'required' => ['legendDisplay', '=', 'true'],
		  ];

		  $this->controls['legendBoxWidth'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Box width', 'bricks' ),
			'group'	=> 'legend_group',
			'type' => 'number',
			'units' => false,
			'inline' => true,
			'small' => true,
			'placeholder' => esc_html__( '15', 'bricks' ),
			//'required' => ['legendDisplay', '=', 'true'],
		  ];

		  $this->controls['legendBoxHeight'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Box height', 'bricks' ),
			'group'	=> 'legend_group',
			'type' => 'number',
			'units' => false,
			'inline' => true,
			'small' => true,
			'placeholder' => esc_html__( '12', 'bricks' ),
			//'required' => ['legendDisplay', '=', 'true'],
		  ];


		  /*   data labels */

		  
		  $this->controls['dataLabelsDisplay'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Display data labels', 'bricks' ),
			'type' => 'select',
			'group'	=> 'data_labels_group',
			'options' => [
			  'true' => esc_html__( 'Enable', 'bricks' ),
			  'false' => esc_html__( 'Disable', 'bricks' ),
			],
			'inline'      => true,
			'clearable' => false,
			'placeholder' => esc_html__( 'Disable', 'bricks' ),
		  ];

		  
		 

		  $this->controls['dataLabelsAnchor'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Anchor', 'bricks' ),
			'type' => 'select',
			'group'	=> 'data_labels_group',
			'options' => [
			  'start' => esc_html__( 'Start', 'bricks' ),
			  'center' => esc_html__( 'Center', 'bricks' ),
			  'end' => esc_html__( 'End', 'bricks' ),
			],
			'inline'      => true,
			'placeholder' => esc_html__( 'Center', 'bricks' ),
			'required' => ['dataLabelsDisplay', '=', 'true'],
		  ];

		  $this->controls['dataLabelsAlign'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Align', 'bricks' ),
			'type' => 'select',
			'group'	=> 'data_labels_group',
			'options' => [
			  'start' => esc_html__( 'Start', 'bricks' ),
			  'center' => esc_html__( 'Center', 'bricks' ),
			  'end' => esc_html__( 'End', 'bricks' ),
			],
			'inline'      => true,
			'placeholder' => esc_html__( 'Center', 'bricks' ),
			'required' => ['dataLabelsDisplay', '=', 'true'],
		  ];

		  $this->controls['dataLabelsOffset'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Offset', 'bricks' ),
			'group'	=> 'data_labels_group',
			'type' => 'number',
			'units' => false,
			'inline' => true,
			'small' => true,
			'placeholder' => esc_html__( '4', 'bricks' ),
			'required' => [
				['dataLabelsDisplay', '=', 'true'],
				['dataLabelsAlign', '!=', 'center'],
			],
		  ];

		  $this->controls['dataLabelsPadding'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Padding', 'bricks' ),
			'group'	=> 'data_labels_group',
			'type' => 'number',
			'units' => false,
			'inline' => true,
			'small' => true,
			'placeholder' => esc_html__( '6', 'bricks' ),
			'required' => ['dataLabelsDisplay', '=', 'true'],
		  ];


		  $this->controls['dataLabelsColorSeperator'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Colors', 'bricks' ),
			'group'	=> 'data_labels_group',
			'type' => 'separator',
		  ];

		  $this->controls['dataLabelsColor'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Text color', 'bricks' ),
			'group'	=> 'data_labels_group',
			'type' => 'color',
			'inline' => true,
			'required' => ['dataLabelsDisplay', '=', 'true'],
			'placeholder' => [
				'hex' => '#ffffff',
			],
		  ];

		  $this->controls['dataLabelsBackgroundColor'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Background color', 'bricks' ),
			'info' => esc_html__( 'Leave empty to inherit color', 'bricks' ),
			'group'	=> 'data_labels_group',
			'type' => 'color',
			'inline' => true,
			'required' => ['dataLabelsDisplay', '=', 'true'],
		  ];

		$this->controls['dataLabelsBorderColor'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Border color', 'bricks' ),
			'info' => esc_html__( 'Leave empty to inherit color', 'bricks' ),
			'group'	=> 'data_labels_group',
			'type' => 'color',
			'inline' => true,
			'required' => ['dataLabelsDisplay', '=', 'true'],
		  ];

		  $this->controls['dataLabelsBorderWidth'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Border width', 'bricks' ),
			'group'	=> 'data_labels_group',
			'type' => 'number',
			'inline' => true,
			'small' => true,
			'placeholder' => esc_html__( '0', 'bricks' ),
			'required' => ['dataLabelsDisplay', '=', 'true'],
		  ];

		  $this->controls['dataLabelsRotation'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Rotation', 'bricks' ),
			'group'	=> 'data_labels_group',
			'type' => 'number',
			'inline' => true,
			'small' => true,
			'placeholder' => esc_html__( '0', 'bricks' ),
			'required' => ['dataLabelsDisplay', '=', 'true'],
		  ];

		  $this->controls['dataLabelsBorderRadius'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Border radius', 'bricks' ),
			'group'	=> 'data_labels_group',
			'type' => 'number',
			'inline' => true,
			'small' => true,
			'placeholder' => esc_html__( '0', 'bricks' ),
			'required' => ['dataLabelsDisplay', '=', 'true'],
		  ];

		$this->controls['dataLabelsFontSize'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Font size', 'bricks' ),
			'group'	=> 'data_labels_group',
			'type' => 'number',
			'inline' => true,
			'small' => true,
			'placeholder' => esc_html__( '', 'bricks' ),
			'required' => ['dataLabelsDisplay', '=', 'true'],
		];

		$this->controls['dataLabelsFontWeight'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Font weight', 'bricks' ),
			'group'	=> 'data_labels_group',
			'type' => 'number',
			'inline' => true,
			'placeholder' => esc_html__( 'Normal', 'bricks' ),
			'required' => ['dataLabelsDisplay', '=', 'true'],
		];


		  /*   spacing */

		  $this->controls['spacingSeperator'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Chart padding', 'bricks' ),
			'group'	=> 'spacing_group',
			'type' => 'separator',
		  ];

		  $this->controls['spacingTop'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Top', 'bricks' ),
			'group'	=> 'spacing_group',
			'type' => 'number',
			'units' => false,
			'inline' => true,
			'small' => true,
			'placeholder' => esc_html__( '0', 'bricks' ),
		  ];

		  $this->controls['spacingRight'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Right', 'bricks' ),
			'group'	=> 'spacing_group',
			'type' => 'number',
			'units' => false,
			'inline' => true,
			'small' => true,
			'placeholder' => esc_html__( '0', 'bricks' ),
		  ];

		  $this->controls['spacingBottom'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Bottom', 'bricks' ),
			'group'	=> 'spacing_group',
			'type' => 'number',
			'units' => false,
			'inline' => true,
			'small' => true,
			'placeholder' => esc_html__( '0', 'bricks' ),
		  ];

		  $this->controls['spacingLeft'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Left', 'bricks' ),
			'group'	=> 'spacing_group',
			'type' => 'number',
			'units' => false,
			'inline' => true,
			'small' => true,
			'placeholder' => esc_html__( '0', 'bricks' ),
		  ];

  }

  // Methods: Frontend-specific
  public function enqueue_scripts() {

	wp_enqueue_script( 'x-chart', BRICKSEXTRAS_URL . 'components/assets/js/' . \BricksExtras\Helpers::maybeMinifyScripts('dynamicchart') . '.js', '', '1.0.6', true );

	wp_localize_script(
		'x-chart',
		'xChart',
		[
			'Instances' => [],
			'RTL' => is_rtl() ? 'true' : 'false'
		]
	);

	if (! \BricksExtras\Helpers::elementCSSAdded($this->name) ) {
		wp_enqueue_style( 'x-chart', BRICKSEXTRAS_URL . 'components/assets/css/dynamicchart.css', [], '' );
	}


  }


  public function render() {

		$settings = $this->settings;

		$header_items = ! empty( $settings['header_items'] ) ? $settings['header_items'] : false;
		$content_items = ! empty( $settings['content_items'] ) ? $settings['content_items'] : false;
		$datasetItems = ! empty( $settings['datasetItems'] ) ? $settings['datasetItems'] : false;
		$chartType = isset( $settings['chart_type'] ) ? $settings['chart_type'] : 'bar';
		$loopDataPoints = isset( $settings['queryLoopDatapoints'] ) ? 'dataPoints' === $settings['queryLoopDatapoints'] : false;

		$config = [
			'legendDisplay' => isset( $settings['legendDisplay'] ) ? $settings['legendDisplay'] : 'false',
			'chartType' => $chartType,
			'suggestedMin' => isset( $settings['suggestedMin'] ) ? $settings['suggestedMin'] : null,
			'suggestedMax' => isset( $settings['suggestedMax'] ) ? $settings['suggestedMax'] : null,
			'xGridColor' => isset( $settings['xGridColor'] ) ? $settings['xGridColor'] : '#ccc',
			'yGridColor' => isset( $settings['yGridColor'] ) ? $settings['yGridColor'] : '#ccc',
			'xBorderColor' => isset( $settings['xBorderColor'] ) ? $settings['xBorderColor'] : '#222',
			'yBorderColor' => isset( $settings['yBorderColor'] ) ? $settings['yBorderColor'] : '#222',
			'yAxisType' => isset( $settings['yAxisType'] ) ? $settings['yAxisType'] : 'linear',
			'xAxisTitle' => ! empty( $settings['xAxisTitle'] ) ? esc_attr__( $settings['xAxisTitle'] ) : '',
			'yAxisTitle' => ! empty( $settings['yAxisTitle'] ) ? esc_attr__( $settings['yAxisTitle'] ) : '',
			'beginAtZero' => isset( $settings['beginAtZero'] ) ? $settings['beginAtZero'] : 'true',
			'tension' => isset( $settings['tension'] ) ? floatval($settings['tension']) : 0.1,
			'barBorderRadius' => isset( $settings['barBorderRadius'] ) ? floatval($settings['barBorderRadius']) : 5,
			'lineColor' => isset( $settings['lineColor'] ) ? $settings['lineColor'] :'#222',
			'lineWidth' => isset( $settings['lineWidth'] ) ? floatval($settings['lineWidth']) : 2,
			'linePointRadius' => isset( $settings['linePointRadius'] ) ? floatval($settings['linePointRadius']) : 3,
			'linePointBorderWidth' => isset( $settings['linePointBorderWidth'] ) ? floatval($settings['linePointBorderWidth']) : 1,
			'xAxisTitleColor' => isset( $settings['xAxisTitleColor'] ) ? $settings['xAxisTitleColor'] : '',
			'yAxisTitleColor' => isset( $settings['yAxisTitleColor'] ) ? $settings['yAxisTitleColor'] : '',
			'xAxisTicksColor' => isset( $settings['xAxisTicksColor'] ) ? $settings['xAxisTicksColor'] : '',
			'yAxisTicksColor' => isset( $settings['yAxisTicksColor'] ) ? $settings['yAxisTicksColor'] : '',
			'xAxisUnits' => isset( $settings['xAxisUnits'] ) ? esc_attr__( $settings['xAxisUnits'] ) : '',
			'xAxisUnitPosition' => isset( $settings['xAxisUnitPosition'] ) ? $settings['xAxisUnitPosition'] : 'after',
			'ariaLabel' => isset( $settings['ariaLabel'] ) ? esc_attr__( $settings['ariaLabel'] ) : esc_attr__( 'Chart' ),
			'rotation' => isset( $settings['rotation'] ) ? floatval( $settings['rotation'] ) : 0,
			'circumference' => isset( $settings['circumference'] ) ? floatval( $settings['circumference'] ) : 360,
			'pieBorderColor' => isset( $settings['pieBorderColor'] ) ? $settings['pieBorderColor'] :'#222',
			'pieBorderWidth' => isset( $settings['pieBorderWidth'] ) ? floatval($settings['pieBorderWidth']) : 0,
			'pieCutOut' => isset( $settings['pieCutOut'] ) ? floatval( $settings['pieCutOut'] ) : 0,
			'legendPadding' => isset( $settings['legendPadding'] ) ? floatval( $settings['legendPadding'] ) : 0,
			'chartDirection' => isset( $settings['chartDirection'] ) ? $settings['chartDirection'] : 'vertical',
			'tooltipDisplay' => isset( $settings['tooltipDisplay'] ) ? $settings['tooltipDisplay'] : 'false',
			'events' => isset( $settings['events'] ) ? $settings['events'] : 'click',
			'tooltipColor' => isset( $settings['tooltipColor'] ) ? $settings['tooltipColor'] : '#222',
			'tooltipBackground' => isset( $settings['tooltipBackground'] ) ? $settings['tooltipBackground'] : '#fff',
			'tooltipBorderColor' => isset( $settings['tooltipBorderColor'] ) ? $settings['tooltipBorderColor'] : '#222',
			'tooltipBorderWidth' => isset( $settings['tooltipBorderWidth'] ) ? floatval($settings['tooltipBorderWidth'] ) : 1,
			'tooltipCaretSize' => isset( $settings['tooltipCaretSize'] ) ? floatval($settings['tooltipCaretSize'] ) : 5,
			'loopDataPoints' => $loopDataPoints,
			'spacingTop' => isset( $settings['spacingTop'] ) ? floatval( $settings['spacingTop'] ) : 0,
			'spacingRight' => isset( $settings['spacingRight'] ) ? floatval( $settings['spacingRight'] ) : 0,
			'spacingBottom' => isset( $settings['spacingBottom'] ) ? floatval( $settings['spacingBottom'] ) : 0,
			'spacingLeft' => isset( $settings['spacingLeft'] ) ? floatval( $settings['spacingLeft'] ) : 0,
		];

		if ( isset( $settings['legendPosition'] ) ) {
			$config += [
				'legendPosition' => $settings['legendPosition']
			];
		}

		if ( isset( $settings['legendAlign'] ) ) {
			$config += [
				'legendAlign' => $settings['legendAlign']
			];
		}

		if ( isset( $settings['legendBoxWidth'] ) ) {
			$config += [
				'legendBoxWidth' => floatval( $settings['legendBoxWidth'] )
			];
		}

		if ( isset( $settings['legendBoxHeight'] ) ) {
			$config += [
				'legendBoxHeight' => floatval( $settings['legendBoxHeight'] )
			];
		}

		if ( isset( $settings['dataLabelsDisplay'] ) && 'true' === $settings['dataLabelsDisplay'] ) {
			$config += [
				'dataLabelsDisplay' => true,
				'dataLabelsAlign' => isset( $settings['dataLabelsAlign'] ) ? $settings['dataLabelsAlign'] : 'center',
				'dataLabelsAnchor' => isset( $settings['dataLabelsAnchor'] ) ? $settings['dataLabelsAnchor'] : 'center',
				'dataLabelsOffset' => isset( $settings['dataLabelsOffset'] ) ? floatval( $settings['dataLabelsOffset'] ) : 4,
				'dataLabelsPadding' => isset( $settings['dataLabelsPadding'] ) ? floatval( $settings['dataLabelsPadding'] ) : 6,
				'dataLabelsColor' => isset( $settings['dataLabelsColor'] ) ? $settings['dataLabelsColor'] : '#ffffff',
				'dataLabelsBackgroundColor' => isset( $settings['dataLabelsBackgroundColor'] ) ? $settings['dataLabelsBackgroundColor'] : false,
				'dataLabelsBorderColor' => isset( $settings['dataLabelsBorderColor'] ) ? $settings['dataLabelsBorderColor'] : false,
				'dataLabelsBorderWidth' => isset( $settings['dataLabelsBorderWidth'] ) ? floatval( $settings['dataLabelsBorderWidth'] ) : 0,
				'dataLabelsBorderRadius' => isset( $settings['dataLabelsBorderRadius'] ) ? floatval( $settings['dataLabelsBorderRadius'] ) : 0,
				'dataLabelsFontSize' => isset( $settings['dataLabelsFontSize'] ) ? floatval( $settings['dataLabelsFontSize'] ) : false,
				'dataLabelsFontWeight' => isset( $settings['dataLabelsFontWeight'] ) ? floatval( $settings['dataLabelsFontWeight'] ) : false,
				'dataLabelsRotation' => isset( $settings['dataLabelsRotation'] ) ? floatval( $settings['dataLabelsRotation'] ) : 0,
			];
		}

		if ( 'doughnut' == $chartType ) {
			$xAxisDisplay = 'false';
			$yAxisDisplay = 'false';
		} else {
			$xAxisDisplay = !isset( $settings['xAxisDisplay'] ) ? 'true' : $settings['xAxisDisplay'];
			$yAxisDisplay = !isset( $settings['yAxisDisplay'] ) ? 'true' : $settings['yAxisDisplay'];
		}

		$config += [ 
			'xAxisDisplay' => $xAxisDisplay,
			'yAxisDisplay' => $yAxisDisplay,
		];
		

		$this->set_attribute( 'x-dynamic-chart_table', 'class', 'x-dynamic-chart_table' );
		$this->set_attribute( 'x-dynamic-chart_table', 'style', 
			'display: none;'
		);
		$this->set_attribute( 'x-dynamic-chart_table', 'data-x-chart', wp_json_encode( $config ) );

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
		
		$this->set_attribute( '_root', 'data-x-dynamic-chart', $chartType );

		echo "<div {$this->render_attributes( '_root' )}>";

		echo "<table {$this->render_attributes( 'x-dynamic-chart_table' )}>";

		if ( !$loopDataPoints ) {

		/* table header */
		if ( $content_items ) {			

			echo "<thead><tr>";

			foreach ( $content_items as $index => $content_item ) {

				$header_title = ! empty( $content_item['title'] ) ? esc_html__( $content_item['title'] ) : '';

				echo '<th class="label">' . $header_title . '</th>';

			}

			echo "</tr></thead>";

		}

		/* table content */

		echo "<tbody>";

		// Query Loop
		if ( isset( $settings['hasLoop'] ) ) {
			$query = new \Bricks\Query( [
				'id'       => $this->id,
				'settings' => $settings,
			] );

			if ( $query->count === 1 ) {
				// No results: Empty by default (@since 1.4)
				$no_results_content = $query->get_no_results_content();

				if ( empty( $no_results_content ) ) {
					echo $this->render_element_placeholder( ['title' => esc_html__( 'No results', 'bricks' )] );
				}
			}
		}
		

		// Query Loop
		if ( isset( $settings['hasLoop'] ) ) {
			if (!!$content_items) {
				$content_item = $content_items[0];
				echo wp_kses_post( $query->render( [ $this, 'render_repeater_item' ], compact( 'content_items' ) ) );
			}
			$query->destroy();
			unset( $query );
		} else {

			// just output one row if not using query loop, so user can build single static chart
			?> <tr> <?php	

				$datasetLabels = ! empty( $settings['datasetLabel'] ) ? esc_attr__( $settings['datasetLabel'] ) : '';

				if ( ! empty( $datasetLabels) ) {		
						echo '<td class="legend">';
						echo $datasetLabels;
						echo '</td>';	
				}
			
				if ( $content_items ) {			

					foreach ( $content_items as $index => $content_item ) {

						$style_config = [];

						if ( isset( $content_item['color'] ) ) {

							$style_config += [
								'color' => isset( $content_item['color'] ) ? $content_item['color'] : '',
							];

						}

						$this->set_attribute( "td-$index", 'data-x-chart-style', wp_json_encode( $style_config ) );
				
						echo "<td {$this->render_attributes( "td-$index" )}>";

						$content_datas = ! empty( $content_item['data'] ) ? esc_attr( $content_item['data'] ) : '';

						if ( is_numeric( $content_datas ) ) {
							if ( !is_float( $content_datas) ) {
								$content_datas = number_format( $content_datas, 2, '.', '' );
							}
						}

						echo $content_datas;

						echo '</td>';

					}			

				}
			
			?> </tr> <?php
		}

		echo "</tbody>";  

		} else {

			echo "<thead><tr>";

			// Query Loop
			if ( isset( $settings['hasLoop'] ) ) {
				$query = new \Bricks\Query( [
					'id'       => $this->id,
					'settings' => $settings,
				] );

				if ( $query->count === 1 ) {
					// No results: Empty by default (@since 1.4)
					$no_results_content = $query->get_no_results_content();

					if ( empty( $no_results_content ) ) {
						echo $this->render_element_placeholder( ['title' => esc_html__( 'No results', 'bricks' )] );
					}
				} else {

					echo wp_kses_post( $query->render( [ $this, 'render_repeater_head' ], compact( 'content_items' ) ) );
				
					$query->destroy();
					unset( $query );

				}
			}

			echo "</tr></thead>";

			echo '<tbody><tr>'; 

				echo "<td class=legend ";
				
				echo ">";

				echo !empty( $settings['datasetLabel'] ) ? $settings['datasetLabel'] : '';

				echo '</td>';

				// Query Loop
				if ( isset( $settings['hasLoop'] ) ) {
					$query = new \Bricks\Query( [
						'id'       => $this->id,
						'settings' => $settings,
					] );

					if ( $query->count === 1 ) {
						// No results: Empty by default (@since 1.4)
						$no_results_content = $query->get_no_results_content();

						if ( empty( $no_results_content ) ) {
							echo $this->render_element_placeholder( ['title' => esc_html__( 'No results', 'bricks' )] );
						}
					} else {

						echo wp_kses_post( $query->render( [ $this, 'render_repeater_body' ], compact( 'content_items' ) ) );
					
						$query->destroy();
						unset( $query );

					}
				}
			
			echo '</tr></tbody>';

		}

		/* datasetSettings */
		if ( $datasetItems && isset( $settings['hasLoop'] ) ) {			

			echo "<ul class='x-dynamic-chart_list' style='display: none;'>";

			foreach ( $datasetItems as $index => $datasetItem ) {

				$color = ! empty( $datasetItem['color'] ) ? $datasetItem['color'] : '';

				$dataset_config = [];

				if ( isset( $datasetItem['color'] ) ) {

					$dataset_config += [
						'color' => isset( $datasetItem['color'] ) ? $datasetItem['color'] : '',
					];

				}

				$this->set_attribute( "li-$index", 'data-x-chart-style', wp_json_encode( $dataset_config ) );

				echo "<li {$this->render_attributes( "li-$index" )} ></li>";

			}

			echo "</ul>";

		}

		echo "</table>";
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

		$datasetLabels = ! empty( $settings['datasetLabel'] ) ? $settings['datasetLabel'] : '';
		$datasetColor = ! empty( $settings['datasetColor'] ) ? $settings['datasetColor'] : '';

		echo "<td class=legend ";
		
		if ( isset( $settings['maybeDynamicColors'] ) ) {
			echo 'dynamic' === $settings['maybeDynamicColors'] ? "data-set-color=" . esc_attr( $datasetColor ) . " " : "";
		}
		
		echo ">";
		echo esc_attr( $datasetLabels );
		echo '</td>';	



		if ( $content_items ) {			

			foreach ( $content_items as $index => $content_item ) {

				echo '<td>';

				$content_datas = ! empty( $content_item['data'] ) ? $content_item['data'] : '';

				echo esc_attr( $content_datas );

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

  public function render_repeater_head( $content_items ) {

	$settings = $this->settings;
	$index    = $this->loop_index;

	// Render
	ob_start();

	$queryLoopDatapoints_Label = ! empty( $settings['queryLoopDatapoints_Label'] ) ? esc_html( $settings['queryLoopDatapoints_Label'] ) : '';

	echo '<th class="label" ';

	$datasetColor = ! empty( $settings['datasetColor'] ) ? $settings['datasetColor'] : '';

	if ( isset( $settings['maybeDynamicColors'] ) ) {
		echo 'dynamic' === $settings['maybeDynamicColors'] ? "data-set-color=" . esc_attr( $datasetColor ) . " " : "";
	}
	
	echo '>' . $queryLoopDatapoints_Label . '</th>';

	$html = ob_get_clean();

	$this->loop_index++;

	return $html;

  }

  public function render_repeater_body( $content_items ) {

	$settings = $this->settings;
	$index    = $this->loop_index;

	// Render
	ob_start();

	$queryLoopDatapoints_Label = ! empty( $settings['queryLoopDatapoints_Label'] ) ? $settings['queryLoopDatapoints_Label'] : '';
	$queryLoopDatapoints_Value = ! empty( $settings['queryLoopDatapoints_Value'] ) ? $settings['queryLoopDatapoints_Value'] : '';

	echo "<td ";

	$dataPointColor = isset( $settings['datasetColor'] ) ? $settings['datasetColor'] : '';
	
	if ( isset( $settings['maybeDynamicColors'] ) ) {
		if ( 'dynamic' === $settings['maybeDynamicColors'] ) {
			echo "data-point-color='" . esc_attr( $dataPointColor ) . "' ";
		}
	}
	
	echo ">" . esc_html( $queryLoopDatapoints_Value ) . "</td>";

	$html = ob_get_clean();

	$this->loop_index++;

	return $html;

  }

}