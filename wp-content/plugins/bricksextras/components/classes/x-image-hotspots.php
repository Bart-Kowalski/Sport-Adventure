<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class X_Image_Hotspots extends \Bricks\Element {

  // Element properties
  public $category     = 'extras';
	public $name         = 'ximagehotspots';
	public $icon         = 'ti-image';
	public $css_selector = '';
	public $scripts      = ['xImageHotspots'];
  public $loop_index   = 0;

  
  public function get_label() {
	  return esc_html__( 'Image hotspots', 'extras' );
  }
  public function set_control_groups() {

    $this->control_groups['image'] = [
			'title' => esc_html__( 'Image', 'bricks' ),
			'tab'   => 'content',
		];

    $this->control_groups['markers'] = [
			'title' => esc_html__( 'Marker style', 'bricks' ),
			'tab'   => 'content',
		];

		$this->control_groups['content'] = [
			'title' => esc_html__( 'Popover style', 'bricks' ),
			'tab'   => 'content',
		];

    $this->control_groups['pulses'] = [
			'title' => esc_html__( 'Pulses', 'bricks' ),
			'tab'   => 'content',
		];

    $this->control_groups['behaviour'] = [
			'title' => esc_html__( 'Behaviour', 'bricks' ),
			'tab'   => 'content',
		];

    $this->control_groups['animation'] = [
			'title' => esc_html__( 'Animation', 'bricks' ),
			'tab'   => 'content',
		];

  }

  public function set_controls() {

    $this->controls = array_replace_recursive( $this->controls, $this->get_loop_builder_controls() );
    
    $this->controls['markers'] = [
			'tab'         => 'content',
			'placeholder' => esc_html__( 'Markers', 'bricks' ),
      'title'    => esc_html__( 'Add Markers', 'bricks' ),
			'type'        => 'repeater',
      'titleProperty' => 'title',
			'checkLoop'   => true,
			'fields'      => [
				'title'    => [
					'label' => esc_html__( 'Marker text', 'bricks' ),
					'type'  => 'text',
				],
				'position_x' => [
					'label'   => esc_html__( 'Position X', 'bricks' ),
          'type'    => 'text',
          'placeholder'   => esc_html__( '0%', 'bricks' ),
				],
        'position_y' => [
					'label'   => esc_html__( 'Position Y', 'bricks' ),
          'type'    => 'text',
          'placeholder'   => esc_html__( '0%', 'bricks' ),
				],
        'markerType'  => [
					'label' => esc_html__( 'Marker action ( popover, link or both )', 'bricks' ),
					'type'  => 'text',
          'options' => [
            'popover' => esc_html__( 'popover', 'bricks' ),
            'link' => esc_html__( 'link', 'bricks' )
          ],
          'placeholder' => esc_html__( 'popover', 'bricks' )
				],
        'link'  => [
					'label' => esc_html__( 'Link', 'bricks' ),
					'type'  => 'link',
				],
				'content'  => [
					'label' => esc_html__( 'Popover content', 'bricks' ),
					'type'  => 'editor',
				],
        'aria_label' => [
					'label' => esc_html__( 'Aria label', 'bricks' ),
					'type'  => 'text',
				],
        'markerImage' => [
					'label' => esc_html__( 'Marker Image', 'bricks' ),
          'description' => esc_html__( '( Will replace marker icon )', 'bricks' ),
					'type'  => 'image',
				],
        'markerImageAltText' => [
					'label' => esc_html__( 'Alt text', 'bricks' ),
					'type'  => 'text',
				],
        'custom_attributes' => [
					'label' => esc_html__( 'Custom attributes', 'bricks' ),
					'type'  => 'repeater',
          'placeholder' => esc_html__( 'Attribute', 'bricks' ),
          'fields'      => [
            'name'    => [
              'label' => esc_html__( 'Name', 'bricks' ),
              'type'  => 'text',
            ],
            'value'    => [
              'label' => esc_html__( 'Value', 'bricks' ),
              'type'  => 'text',
            ],
          ]
				],
			],
			'default'     => [
				[
					'title'    => esc_html__( 'Marker', 'bricks' ),
					'content'  => esc_html__( 'Popover content here ..', 'bricks' ),
          'position_x' => '30%',
          'position_y' => '10%',
          'aria_label' => esc_attr__('Toggle popover')
				],
        [
					'title'    => esc_html__( 'Marker', 'bricks' ),
					'content'  => esc_html__( 'Popover content here ..', 'bricks' ),
          'position_x' => '60%',
          'position_y' => '30%',
          'aria_label' => esc_attr__('Toggle popover')
				],
			],
		];

    $this->controls['image'] = [
      'tab' => 'content',
      'label' => esc_html__( 'Image', 'bricks' ),
      'type' => 'image',
      'group' => 'image',
    ];

    $this->controls['altText'] = [
			'tab'      => 'content',
			'label'    => esc_html__( 'Custom alt text', 'bricks' ),
			'type'     => 'text',
			'inline'   => true,
			'rerender' => false,
			'required' => [ 'image', '!=', '' ],
      'group' => 'image',
		];


    /* markers */


    $marker = '.x-marker_marker';
    $marker_inner = '.x-marker_marker-inner'; 

    $this->controls['markerIconSep'] = [
      'tab'      => 'content',
      'group'    => 'markers',
      'label'    => esc_html__( 'Icon', 'bricks' ),
      'type'     => 'separator',
    ];

    $this->controls['icon'] = [
			'tab'     => 'content',
      'group' => 'markers',
			'label'   => esc_html__( 'Marker icon', 'bricks' ),
			'type'    => 'icon',
			'default' => [
				'icon'    => 'ion-ios-pin',
				'library' => 'ionicons',
			],
      'css'      => [
        [
          'property' => 'font-size',
          'selector' => $marker_inner . ' svg'
        ],
      ],
		];

    $this->controls['iconSize'] = [
      'tab'      => 'content',
      'group'    => 'markers',
      'label'    => esc_html__( 'Icon size', 'bricks' ),
      'type'     => 'number',
      'units'    => true,
      'css'      => [
        [
          'property' => 'font-size',
          'selector' => $marker_inner . '> *:not(.x-marker_marker-title)'
        ],
      ],
    ];

    $this->controls['markerImageSep'] = [
      'tab'      => 'content',
      'group'    => 'markers',
      'label'    => esc_html__( 'Marker Image', 'bricks' ),
      'type'     => 'separator',
    ];

    $this->controls['imageWidth'] = [
      'tab'      => 'content',
      'group'    => 'markers',
      'label'    => esc_html__( 'Image width', 'bricks' ),
      'type'     => 'number',
      'units'    => true,
      'css'      => [
        [
          'property' => 'width',
          'selector' => $marker_inner . ' .x-hotspots_marker-image'
        ],
      ],
    ];

    $this->controls['imageHeight'] = [
      'tab'      => 'content',
      'group'    => 'markers',
      'label'    => esc_html__( 'Image height', 'bricks' ),
      'type'     => 'number',
      'units'    => true,
      'css'      => [
        [
          'property' => 'height',
          'selector' => $marker_inner . ' .x-hotspots_marker-image'
        ],
      ],
    ];

    $this->controls['imageObjectFit'] = [
      'tab'      => 'content',
      'group'    => 'markers',
      'label'    => esc_html__( 'Object Fit', 'bricks' ),
      'type'     => 'select',
      'inline' => true,
      'options'     => [
				'fill'       => esc_html__( 'Fill', 'bricks' ),
				'contain'    => esc_html__( 'Contain', 'bricks' ),
				'cover'      => esc_html__( 'Cover', 'bricks' ),
				'none'       => esc_html__( 'None', 'bricks' ),
				'scale-down' => esc_html__( 'Scale down', 'bricks' ),
				'fill'       => esc_html__( 'Fill', 'bricks' ),
			],
      'css'      => [
        [
          'property' => 'object-fit',
          'selector' => $marker_inner . ' .x-hotspots_marker-image'
        ],
      ],
    ];

    $this->controls['imageBorder'] = [
      'tab'      => 'content',
      'group'    => 'markers',
      'label'    => esc_html__( 'Image border', 'bricks' ),
      'type'     => 'border',
      'css'      => [
        [
          'property' => 'border',
          'selector' => $marker_inner . ' .x-hotspots_marker-image'
        ],
      ],
    ];

    

     
 
     $this->controls['marker_start'] = [
       'tab'   => 'content',
       'group'  => 'markers',
       'label'  => esc_html__( 'Marker button', 'extras' ),
       'type'  => 'separator',
     ];

     $this->controls['markerTypography'] = [
      'tab'    => 'content',
      'group'  => 'markers',
      'type'   => 'typography',
      'label'  => esc_html__( 'Typography', 'extras' ),
      'css'    => [
        [
          'property' => 'font',
          'selector' => $marker_inner,
        ],
      ],
    ];

    $this->controls['markerBackgroundColor'] = [
      'tab'    => 'content',
      'group'  => 'markers',
      'type'   => 'color',
      'label'  => esc_html__( 'Background', 'extras' ),
      'css'    => [
        [
          'property' => 'background-color',
          'selector' => $marker_inner,
        ],
      ],
    ];

    $this->controls['markerBorder'] = [
      'tab'    => 'content',
      'group'  => 'markers',
      'type'   => 'border',
      'label'  => esc_html__( 'Border', 'extras' ),
      'css'    => [
        [
          'property' => 'border',
          'selector' => $marker,
        ],
      ],
    ];

    $this->controls['markerBoxShadow'] = [
      'tab'    => 'content',
      'group'  => 'markers',
      'label'  => esc_html__( 'Box Shadow', 'extras' ),
      'type'   => 'box-shadow',
      'css'    => [
        [
          'property' => 'box-shadow',
          'selector' => $marker_inner,
        ],
      ],
    ];
 
     $this->controls['markerPadding'] = [
       'tab'   => 'content',
       'group' => 'markers',
       'label' => esc_html__( 'Button padding', 'extras' ),
       'type'  => 'dimensions',
       'placeholder' => [
			  'top' => '20px',
			  'right' => '20px',
			  'bottom' => '20px',
			  'left' => '20px',
			],
       'css'   => [
         [
           'property' => 'padding',
           'selector' => '.x-marker_marker-inner'
         ],
       ],
     ];


     $this->controls['markerWidth'] = [
      'tab'      => 'content',
      'group'    => 'markers',
      'label'    => esc_html__( 'Button width', 'bricks' ),
      'type'     => 'number',
      'units'    => true,
      'css'      => [
        [
          'property' => 'width',
          'selector' => $marker_inner
        ],
      ],
    ];

    $this->controls['markerHeight'] = [
      'tab'      => 'content',
      'group'    => 'markers',
      'label'    => esc_html__( 'Button height', 'bricks' ),
      'type'     => 'number',
      'units'    => true,
      'css'      => [
        [
          'property' => 'height',
          'selector' => $marker_inner
        ],
      ],
    ];

    $this->controls['markerButtonDirection'] = [
			'tab'      => 'content',
			'label'    => esc_html__( 'Direction', 'bricks' ),
			'group'		  => 'markers',
			'tooltip'  => [
				'content'  => 'flex-direction',
				'position' => 'top-left',
			],
			'type'     => 'direction',
			'css'      => [
				[
					'property' => 'flex-direction',
					'selector' => $marker_inner,
				],
			],
			'inline'   => true,
			'rerender' => true,
			//'required' => [ '_display', '=', 'flex' ],
		];

		$this->controls['markerButtonjustifyContent'] = [
			'tab'      => 'content',
			'label'    => esc_html__( 'Align main axis', 'bricks' ),
			'group'		  => 'markers',
			'tooltip'  => [
				'content'  => 'justify-content',
				'position' => 'top-left',
			],
			'type'     => 'justify-content',
			'css'      => [
				[
					'property' => 'justify-content',
					'selector' => $marker_inner,
				],
			],
			//'required' => [ '_display', '=', 'flex' ],
		];

		$this->controls['markerButtonalignItems'] = [
			'tab'      => 'content',
			'label'    => esc_html__( 'Align cross axis', 'bricks' ),
			'group'		  => 'markers',
			'tooltip'  => [
				'content'  => 'align-items',
				'position' => 'top-left',
			],
			'type'     => 'align-items',
			'css'      => [
				[
					'property' => 'align-items',
					'selector' => $marker_inner,
				],
			],
			//'required' => [ '_display', '=', 'flex' ],
		];

    $this->controls['markerButtoncolumnGap'] = [
			'tab'      => 'content',
			'label'    => esc_html__( 'Gap', 'bricks' ),
			'group'		  => 'markers',
			'type'     => 'number',
			'units'    => true,
			'css'      => [
				[
					'property' => 'gap',
					'selector' => $marker_inner,
				],
			],
		];



      /* popover content */


    $popover = '.x-marker_popover .tippy-content';

    $this->controls['popoverWidth'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Popover width', 'extras' ),
			'inline'      => true,
      'group'  => 'content',
			'small'		=> true,
			'type' => 'number',
			'units'    => true,
			'css' => [
			  [
				'selector' => $popover,  
				'property' => 'width',
			  ],
			],
		  ];

     $this->controls['popoverTypography'] = [
       'tab'    => 'content',
       'group'  => 'content',
       'type'   => 'typography',
       'label'  => esc_html__( 'Typography', 'extras' ),
       'rerender' => false,
       'css'    => [
         [
           'property' => 'font',
           'selector' => $popover,
         ],
       ],
     ];
 
     $this->controls['popoverBackgroundColor'] = [
       'tab'    => 'content',
       'group'  => 'content',
       'type'   => 'color',
       'label'  => esc_html__( 'Background', 'extras' ),
       'css'    => [
         [
           'property' => 'background-color',
           'selector' => $popover,
         ],
       ],
     ];

     $this->controls['popoverArrowColor'] = [
      'tab'    => 'content',
      'group'  => 'content',
      'type'   => 'color',
      'label'  => esc_html__( 'Arrow color', 'extras' ),
      'css'    => [
        [
          'property' => 'color',
          'selector' => '.x-marker .tippy-arrow',
        ],
      ],
    ];
 
     $this->controls['popoverBorder'] = [
       'tab'    => 'content',
       'group'  => 'content',
       'type'   => 'border',
       'rerender' => false,
       'label'  => esc_html__( 'Border', 'extras' ),
       'css'    => [
         [
           'property' => 'border',
           'selector' => $popover,
         ],
       ],
     ];
 
     $this->controls['popoverBoxShadow'] = [
       'tab'    => 'content',
       'group'  => 'content',
       'label'  => esc_html__( 'Box Shadow', 'extras' ),
       'type'   => 'box-shadow',
       'rerender' => false,
       'css'    => [
         [
           'property' => 'box-shadow',
           'selector' => $popover,
         ],
       ],
     ];
 
     $this->controls['popover_start'] = [
       'tab'   => 'content',
       'group'  => 'content',
       'type'  => 'separator',
     ];
 
     $this->controls['popoverPadding'] = [
       'tab'   => 'content',
       'group' => 'content',
       'label' => esc_html__( 'Padding', 'extras' ),
       'type'  => 'dimensions',
       'css'   => [
         [
           'property' => 'padding',
           'selector' => $popover
         ],
       ],
     ];


     /* behaviour */

     $this->controls['placement'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Preferred placement', 'bricks' ),
			'type' => 'select',
      'group' => 'behaviour', 
			'options' => [
			  'top' => esc_html__('Top', 'bricks' ), 
				'right' => esc_html__('Right', 'bricks' ), 
				'bottom' => esc_html__('Bottom', 'bricks' ), 
				'left' => esc_html__('Left', 'bricks' ), 
				'auto' 	=> esc_html__( 'Auto (Side with the most space)', 'bricks' ), 
				'auto-start' => esc_html__( 'Auto Start', 'bricks' ), 
				'auto-end' => esc_html__( 'Auto End', 'bricks' ),
				'top-start' => esc_html__( 'Top Start', 'bricks' ), 
				'top-end' => esc_html__( 'Top End', 'bricks' ),
				'right-start' => esc_html__( 'Right Start', 'bricks' ), 
				'right-end' => esc_html__( 'Right End', 'bricks' ),
				'bottom-start' => esc_html__( 'Bottom Start', 'bricks' ), 
				'bottom-end' => esc_html__( 'Bottom End', 'bricks' ),
				'left-start' => esc_html__( 'Left Start', 'bricks' ), 
				'left-end' => esc_html__( 'Left End', 'bricks' ),
			],
			//'inline'      => true,
			'placeholder' => esc_html__( 'Auto (Where enough space)', 'bricks' ), 
			'clearable' => false,
		  ];


      $this->controls['interaction'] = [
        'tab' => 'content',
        'label' => esc_html__( 'User interaction to open', 'bricks' ),
        'type' => 'select',
        'group' => 'behaviour', 
        'options' => [
          'mouseenter focus' => 'mouseenter & focus', 
          'click' => 'click (default)',
          'mouseenter click' => 'mouseenter & click',
        ],
        'inline'      => true,
        'placeholder' => 'click (default)',
        'clearable' => false,
        ];

      
      $this->controls['offsetSkidding'] = [
        'tab'   => 'content',
        'group' => 'behaviour',
        'label' => esc_html__( 'Offset skidding (px)', 'extras' ),
        'info' => esc_html__( 'Distance along the side of the marker', 'extras' ),
        'type'  => 'number',
        'placeholder' => '0',
        'units' => false,
        'small' => true
      ];

      $this->controls['offsetDistance'] = [
        'tab'   => 'content',
        'group' => 'behaviour',
        'label' => esc_html__( 'Offset distance (px)', 'extras' ),
        'info' => esc_html__( 'Distance away from the marker', 'extras' ),
        'type'  => 'number',
        'placeholder' => '10',
        'units' => false,
        'small' => true
      ];

      $this->controls['moveTransition'] = [
        'tab'   => 'content',
        'group' => 'behaviour',
        'label' => esc_html__( 'Move transition duration (ms)', 'extras' ),
        'type'  => 'number',
        'placeholder' => '200',
        'units' => false,
        'small' => true
      ];

      $this->controls['moveFocus'] = [
        'tab' => 'content',
        'label' => esc_html__( 'Move focus to popover content on tab', 'bricks' ),
        'type' => 'select',
        'group' => 'behaviour', 
        'options' => [
          'enable' => esc_html__( 'Enable', 'bricks' ),
          'disable' => esc_html__( 'Disable', 'bricks' ),
        ],
        'placeholder' => esc_html__( 'Enable', 'bricks' ),
        'inline'      => true,
        ];


      /* pulses */

      $this->controls['pulseType'] = [
        'tab' => 'content',
        'label' => esc_html__( 'Pulses', 'bricks' ),
        'type' => 'select',
        'group' => 'pulses',
        'options' => [
          'single' => esc_html__( 'Single', 'bricks' ),
          'double' => esc_html__( 'Double', 'bricks' ),
          'none' => esc_html__( 'None', 'bricks' ),
        ],
        'inline'      => true,
        'placeholder' => esc_html__( 'Double', 'bricks' ),
        'clearable' => false,
        ];


        $this->controls['pauseonHover'] = [
          'tab' => 'content',
          'label' => esc_html__( 'Pause on hover', 'bricks' ),
          'type' => 'select',
          'group' => 'pulses',
          'options' => [
            'none' => esc_html__( 'Enable', 'bricks' ),
            'display' => esc_html__( 'Disable', 'bricks' ),
          ],
          'inline'      => true,
          'placeholder' => esc_html__( 'Enable', 'bricks' ),
          'clearable' => false,
          'css'    => [
            [
              'property' => '--x-pulse-pause',
              'selector' => '',
            ],
          ],
        ];

        $this->controls['pulseSize'] = [
          'tab'   => 'content',
          'group' => 'pulses',
          'label' => esc_html__( 'Pulse scale', 'extras' ),
          'css'    => [
            [
              'property' => '--x-pulse-size',
              'selector' => '',
            ],
          ],
          'type'  => 'number',
          'placeholder' => '1.2',
          'min'  => 0,
					'max'  => 0.1,
					'step' => 3,
          'units' => false,
          'inline' => true
        ];

        $this->controls['pulseDuration'] = [
          'tab'   => 'content',
          'group' => 'pulses',
          'label' => esc_html__( 'Pulse duration (ms)', 'extras' ),
          'css'    => [
            [
              'property' => '--x-pulse-duration',
              'selector' => '',
            ],
          ],
          'type'  => 'number',
          'placeholder' => '2000',
          'inline' => true,
          'hasDynamicData' => false,
          'unit' => 'ms',
        ];

        $this->controls['pulseColor'] = [
          'tab'    => 'content',
          'group' => 'pulses',
          'type'   => 'color',
          'label'  => esc_html__( 'Pulse color', 'extras' ),
          'placeholder'=> 'rgba(0,0,0,0.4)',
          'css'    => [
            [
              'property' => '--x-pulse-color',
              'selector' => '',
            ],
          ],
        ];



        /* animation */

        $this->controls['popoverTransitionIn'] = [
          'tab'   => 'content',
          'group' => 'animation',
          'label' => esc_html__( 'Transition In (ms)', 'extras' ),
          'css'    => [
            [
              'property' => '--x-popover-transitionin',
              'selector' => '',
            ],
          ],
          'type'  => 'number',
          'placeholder' => '300',
          'inline' => true,
          'unit' => 'ms',
          'hasDynamicData' => false,
        ];

        $this->controls['popoverTransitionOut'] = [
          'tab'   => 'content',
          'group' => 'animation',
          'label' => esc_html__( 'Transition Out (ms)', 'extras' ),
          'css'    => [
            [
              'property' => '--x-popover-transitionout',
              'selector' => '',
            ],
          ],
          'type'  => 'number',
          'placeholder' => '300',
          'unit' => 'ms',
          'inline' => true,
          'hasDynamicData' => false,
        ];

        $this->controls['popoverTranslateX'] = [
          'tab'   => 'content',
          'group' => 'animation',
          'label' => esc_html__( 'TranslateX', 'extras' ),
          'css'    => [
            [
              'property' => '--x-popover-translatex',
              'selector' => '',
            ],
          ],
          'type'  => 'number',
          'placeholder' => '0',
          'inline' => true,
          'hasDynamicData' => false,
          'units' => [
            'px' => [
              'min'  => 1,
              'max'  => 1000,
              'step' => 1,
            ],
          ],
        ];

        $this->controls['popoverTranslateY'] = [
          'tab'   => 'content',
          'group' => 'animation',
          'label' => esc_html__( 'TranslateY', 'extras' ),
          'css'    => [
            [
              'property' => '--x-popover-translatey',
              'selector' => '',
            ],
          ],
          'type'  => 'number',
          'placeholder' => '10',
          'inline' => true,
          'hasDynamicData' => false,
          'units' => [
            'px' => [
              'min'  => 1,
              'max'  => 1000,
              'step' => 1,
            ],
          ],
        ];


        $this->controls['popoverScale'] = [
          'tab'   => 'content',
          'group' => 'animation',
          'label' => esc_html__( 'Scale', 'extras' ),
          'css'    => [
            [
              'property' => '--x-popover-scale',
              'selector' => '',
            ],
          ],
          'type'  => 'number',
          'placeholder' => '0.95',
          'inline' => true,
          'hasDynamicData' => false,
        ];

        
    

  }

  // Methods: Frontend-specific
  public function enqueue_scripts() {
    wp_enqueue_script( 'x-popper', BRICKSEXTRAS_URL . 'components/assets/js/popper.js', '', '1.0.0', true );
		wp_enqueue_script( 'x-hotspots', BRICKSEXTRAS_URL . 'components/assets/js/' . \BricksExtras\Helpers::maybeMinifyScripts('imagehotspots') . '.js', ['x-popper'], '1.0.5', true );

    wp_localize_script(
			'x-hotspots',
			'xHotspots',
			[
				'Instances' => [],
			]
		);

    if (! \BricksExtras\Helpers::elementCSSAdded($this->name) ) {
			wp_enqueue_style( 'x-image-hotspots', BRICKSEXTRAS_URL . 'components/assets/css/imagehotspots.css', [], '' );
		}
  }

  public function get_normalized_image_settings( $settings, $settingName ) {
		if ( empty( $settings[$settingName] ) ) {
			return [
				'id'   => 0,
				'url'  => false,
				'size' => BRICKS_DEFAULT_IMAGE_SIZE,
			];
		}

		$image = $settings[$settingName];

    // Size
		$image['size'] = empty( $image['size'] ) ? BRICKS_DEFAULT_IMAGE_SIZE : $settings[$settingName]['size'];

		// Image ID or URL from dynamic data
		if ( ! empty( $image['useDynamicData'] ) ) {

			$images = $this->render_dynamic_data_tag( $image['useDynamicData'], 'image', [ 'size' => $image['size'] ] );

			if ( ! empty( $images[0] ) ) {
				if ( is_numeric( $images[0] ) ) {
					$image['id'] = $images[0];
				} else {
					$image['url'] = $images[0];
				}
			}
		}

		$image['id'] = empty( $image['id'] ) ? 0 : $image['id'];

		// If External URL, $image['url'] is already set
		if ( ! isset( $image['url'] ) ) {
			$image['url'] = ! empty( $image['id'] ) ? wp_get_attachment_image_url( $image['id'], $image['size'] ) : false;
		}

		return $image;

	}
  
  public function render() {

    // Icon
		if ( isset( $this->settings['icon'] ) ) {
			$icon = self::render_icon( $this->settings['icon'], [ 'x-marker_marker-icon' ] );
		} else {
			$icon = false;
		}

    $image      = $this->get_normalized_image_settings( $this->settings, 'image' );
		$image_id   = $image['id'];
		$image_url  = $image['url'];
		$image_size = $image['size'];

    $altText = isset( $this->settings['altText'] ) ? esc_attr__( $this->settings['altText'] ) : '';

    $image_placeholder_url = \Bricks\Builder::get_template_placeholder_image();

    $markers = empty( $this->settings['markers'] ) ? false : $this->settings['markers'];

    $config = [
      'placement' => isset( $this->settings['placement'] ) ? $this->settings['placement'] : 'auto',
      'offsetSkidding' => isset( $this->settings['offsetSkidding'] ) ? intval( $this->settings['offsetSkidding'] ) : 0,
      'offsetDistance' => isset( $this->settings['offsetDistance'] ) ? intval( $this->settings['offsetDistance'] ) : 10,
      'moveTransition' => isset( $this->settings['moveTransition'] ) ? intval( $this->settings['moveTransition'] ) : 200,
      'interaction' => isset( $this->settings['interaction'] ) ? $this->settings['interaction'] : 'click',
      'pulseType' => isset( $this->settings['pulseType'] ) ? $this->settings['pulseType'] : 'double',
      'moveFocus' =>  isset( $this->settings['moveFocus'] ) ? 'enable' === $this->settings['moveFocus'] : true,
    ];

    $this->set_attribute( '_root', 'data-x-hotspots', wp_json_encode( $config ) );

    $output = "<div {$this->render_attributes( '_root' )}>";

    if ( isset( $this->settings['image'] ) ) {

      if ( ! empty( $this->settings['altText'] ) ) {
        $imageAttr = [
          'class' => 'x-hotspots_image',
          'alt' => esc_attr__( $this->settings['altText'] )
        ]; 
      } else {
        $imageAttr = ['class' => 'x-hotspots_image'];
      }

      $image = wp_kses_post( wp_get_attachment_image( 
        $image_id, 
        $image_size, 
        false,
        $imageAttr
      ) );

      if ( '' !== $image ) {
        $output .= $image;
      } else {
        if ( '' !== $image_url ) {
          $output .= '<img src="'. $image_url  .'" alt="' . $altText . '">';
        }
      }

    } else {
      $output .= '<img src="'. $image_placeholder_url  .'">';
    }

		// Query Loop
		if ( isset( $this->settings['hasLoop'] ) ) {

			$query = new \Bricks\Query(
				[
					'id'       => $this->id,
					'settings' => $this->settings,
				]
			);

			$marker = $markers[0];

			$output .= $query->render( [ $this, 'render_repeater_item' ], compact( 'marker', 'icon' ) );

			$query->destroy();
			unset( $query );

		} else {
			foreach ( $markers as $index => $marker ) {
				$output .= self::render_repeater_item( $marker, $icon );
			}
		}

		$output .= '</div>';

		echo $output;

    
  }


  public function render_repeater_item( $marker, $icon ) {

		$settings = $this->settings;
		$index    = $this->loop_index;
		$output   = '';

    $this->set_attribute( "x-marker-$index", 'class', [ 'x-marker' ] );
    $this->set_attribute( "x-marker_marker-$index", 'class', [ 'x-marker_marker' ] );
    $this->set_attribute( "x-marker_marker-inner-$index", 'class', [ 'x-marker_marker-inner' ] );

    $this->set_attribute( "x-marker-$index", 'data-x-id', $this->id . '_' . $index );

    $this->set_attribute( "x-marker_marker-$index", 'style', [ 
      'left: ' . $marker['position_x'] . ';',
      'top: ' . $marker['position_y'] . ';',
    ] );

    $markerTypeSetting = isset( $marker['markerType'] ) ? $marker['markerType'] : 'popover';

    $markerType = strstr( $markerTypeSetting, '{') ? $this->render_dynamic_data_tag( $markerTypeSetting, 'text' ) : $markerTypeSetting;

    $markerIsLink = 'popover' !== $markerType;

    $this->set_attribute( "x-marker_popover-$index", 'class', [ 'x-marker_popover' ] );
    $this->set_attribute( "x-marker_popover-content-$index", 'class', [ 'x-marker_popover-content' ] );
    $this->set_attribute( "x-marker_marker-title-$index", 'class', [ 'x-marker_marker-title' ] );

    $markerTag = $markerIsLink ? 'a' : 'button';

    $link = ! empty( $marker['link'] ) ? $marker['link'] : '';

    if (!$markerIsLink) {

      $this->set_attribute( "x-marker_marker-$index", 'role', [ 
        'button',
      ] );

      $this->set_attribute( "x-marker_marker-$index", 'class', 'x-marker_marker-trigger' );

      $this->set_attribute( "x-marker_marker-$index", 'aria-label', [ 
        esc_attr__( $marker['aria_label'] ),
      ] );
  
      $this->set_attribute( "x-marker_marker-$index", 'tabindex', [ 
        '0',
      ] );

    } else {
      $this->set_link_attributes( "x-marker_marker-$index", $link );

      if ( 'both' === $markerType ) {
        $this->set_attribute( "x-marker_marker-$index", 'class', 'x-marker_marker-trigger' );
      }
    }

    $custom_attributes = isset( $marker['custom_attributes'] ) ? $marker['custom_attributes'] : [];

    if (count($custom_attributes) !== 0) {
      
      foreach ($custom_attributes as $attribute) {

        $attributeName = isset( $attribute['name'] ) ? esc_attr( $attribute['name'] ) : '';
        $attributeValue = isset( $attribute['value'] ) ? esc_attr( $attribute['value'] ) : '';

        $this->set_attribute( "x-marker_marker-$index", $attributeName, $attributeValue);

      }

    }

    $maybeMarkerImage = false;
    $image_output = '';

    $markerImageAltText = isset( $marker['markerImageAltText'] ) ? esc_attr__( $marker['markerImageAltText'] ) : '';

    if ( isset( $marker['markerImage'] ) ) {

      if ( ! empty( $marker['markerImageAltText'] ) ) {
        $marker_imageAttr = [
          'class' => 'x-hotspots_marker-image',
          'alt' => $markerImageAltText
        ]; 
      } else {
        $marker_imageAttr = ['class' => 'x-hotspots_marker-image'];
      }

      $marker_image      = $this->get_normalized_image_settings( $marker, 'markerImage' );
      $marker_image_id   = $marker_image['id'];
      $marker_image_url  = $marker_image['url'];
      $marker_image_size = $marker_image['size'];

      if ($marker_image_id) {

        $maybeMarkerImage = true;

        $marker_image_output = wp_kses_post( wp_get_attachment_image( 
          $marker_image_id, 
          $marker_image_size, 
          false,
          $marker_imageAttr
        ) );

        $image_output .= $marker_image_output;

      } else {
          if ( '' !== $marker_image_url ) {

            $maybeMarkerImage = true;

            $image_output .= '<img class="x-hotspots_marker-image" src="'. $marker_image_url  .'" alt="' . $markerImageAltText . '">';
          }
      }

    }

    

		$output .= "<div {$this->render_attributes( "x-marker-$index" )}>";

			$output .= "<" . $markerTag . " {$this->render_attributes( "x-marker_marker-$index" )}><span {$this->render_attributes( "x-marker_marker-inner-$index" )}>";

      if ( !!$maybeMarkerImage ) {
        $output .= $image_output;
      } else {
        if ( $icon ) {
          $output .= $icon;
        }
      }

      if ( ! empty( $marker['title'] ) ) {
				$output .= "<span {$this->render_attributes( "x-marker_marker-title-$index" )}>" . esc_html__( $marker['title'] ) . "</span>";
      }

			$output .= "</span></" . $markerTag . ">";

		if ( isset( $marker['content'] ) ) {
		
			$content = $this->render_dynamic_data( $marker['content'] );
			$output .= 'link' !== $markerType ? "<div {$this->render_attributes( "x-marker_popover-$index" )}><div {$this->render_attributes( "x-marker_popover-content-$index" )}>" . apply_filters( 'the_content', $content ) . "</div></div>" : "";
		}

		$output .= '</div>';

		$this->loop_index++;

		return $output;
	}

}