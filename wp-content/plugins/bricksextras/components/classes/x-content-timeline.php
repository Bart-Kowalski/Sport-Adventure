<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class X_Content_Timeline extends \Bricks\Element {

  // Element properties
  public $category     = 'extras';
	public $name         = 'xcontenttimeline';
	public $icon         = 'ti-layout-list-thumb';
	public $css_selector = '';
  public $nestable = true;
  
  public function get_label() {
	return esc_html__( 'Content Timeline', 'extras' );
  }
  public function set_control_groups() {

    $this->control_groups['addItems'] = [
			'title' => esc_html__( 'Add items', 'bricks' ),
		];

    $this->control_groups['layout'] = [
			'title' => esc_html__( 'Layout', 'bricks' ),
		];

    $this->control_groups['markers'] = [
			'title' => esc_html__( 'Markers', 'bricks' ),
		];

    $this->control_groups['content'] = [
			'title' => esc_html__( 'Content area', 'bricks' ),
		];

    $this->control_groups['metaContent'] = [
			'title' => esc_html__( 'Meta content', 'bricks' ),
		];

    $this->control_groups['line'] = [
			'title' => esc_html__( 'Line', 'bricks' ),
		];

    $this->control_groups['scroll'] = [
			'title' => esc_html__( 'Active styles', 'bricks' ),
		];

    $this->control_groups['slider'] = [
			'title' => esc_html__( 'Slider / Horizontal', 'bricks' ),
		];

  }

  public function set_controls() {

    
		$this->controls['_children'] = [
			'type'          => 'repeater',
			'titleProperty' => 'label',
			'items'         => 'children', 
      'group'         => 'addItems'
		];

    $this->controls['listTag'] = [
			'group'    => 'layout',
			'label'       => esc_html__( 'List HTML tag', 'bricks' ),
			'type'        => 'text',
			'hasDynamicData' => false,
			'info' => esc_html__( "If changing the items to li, set this to ul", 'bricks' ),
			'inline'      => true,
      'small'      => true,
			'placeholder' => 'div',
		];


    /* layout */

    $this->controls['contentWidth'] = [
      'tab'      => 'content',
      'group'    => 'layout',
      'label'    => esc_html__( 'Content width', 'bricks' ),
      'type'     => 'number',
      'placeholder'    => esc_html__( '50%', 'bricks' ),
      'units'    => true,
      'css'      => [
        [
          'property' => 'flex-basis',
          'selector' => '.x-content-timeline_content',
        ],
      ],
    ];

    $this->controls['metaWidth'] = [
      'tab'      => 'content',
      'group'    => 'layout',
      'label'    => esc_html__( 'Meta width', 'bricks' ),
      'type'     => 'number',
      'placeholder'    => esc_html__( '50%', 'bricks' ),
      'units'    => true,
      'css'      => [
        [
          'property' => 'flex-basis',
          'selector' => '.x-content-timeline_meta',
        ],
      ],
    ];

    $this->controls['direction'] = [
			'tab'   => 'content',
			'group' => 'layout',
			'label' => esc_html__( 'Flex direction', 'bricks' ),
			'type'  => 'select',
      'inline' => true,
			'options' => [
				'row' => esc_html__( 'Row', 'bricks' ),
				'row-reverse' => esc_html__( 'Row-reverse', 'bricks' ),
			],
      'css' => [
			  [
				'property' => 'flex-direction',
				'selector' => '.x-content-timeline_item',
			  ]
			],
		];

    /* layout */

    $this->controls['metaAlign'] = [
			'tab'   => 'content',
			//'inline' => true,
			'group' => 'layout',
			'label' => esc_html__( 'Meta content align', 'bricks' ),
			'type'  => 'select',
      'inline' => true,
			'options' => [
				'flex-start' => esc_html__( 'Flex start', 'bricks' ),
				'flex-end' => esc_html__( 'Flex end', 'bricks' ),
			],
      'css' => [
			  [
				'property' => 'align-items',
				'selector' => '.x-content-timeline_meta-inner',
			  ]
			],
		];

    $this->controls['contentTextAlign'] = [
			'tab'   => 'content',
			//'inline' => true,
			'group' => 'layout',
			'label' => esc_html__( 'Content text align', 'bricks' ),
			'type'  => 'select',
      'inline' => true,
			'options' => [
				'left' => esc_html__( 'Left', 'bricks' ),
				'right' => esc_html__( 'Right', 'bricks' ),
			],
      'css' => [
			  [
				'property' => 'text-align',
				'selector' => '.x-content-timeline_content-inner',
			  ]
			],
		];

    $this->controls['alternatingSep'] = [
			'tab'   => 'content',
			'group' => 'layout',
			'label' => esc_html__( 'Alternating layout', 'bricks' ),
			'type'     => 'separator',
		];

    $this->controls['directionEven'] = [
			'tab'   => 'content',
			'group' => 'layout',
      'inline' => true,
			'label' => esc_html__( 'Flex direction (even)', 'bricks' ),
			'type'  => 'select',
			'options' => [
				'row' => esc_html__( 'Row', 'bricks' ),
				'row-reverse' => esc_html__( 'Row-reverse', 'bricks' ),
			],
      'css' => [
			  [
				'property' => 'flex-direction',
				'selector' => '.x-content-timeline_item:nth-child(2n)',
			  ]
			],
		];

    $this->controls['metaAlignEven'] = [
			'tab'   => 'content',
			'group' => 'layout',
      'inline' => true,
			'label' => esc_html__( 'Meta content align (even)', 'bricks' ),
			'type'  => 'select',
			'options' => [
				'flex-start' => esc_html__( 'Flex start', 'bricks' ),
				'flex-end' => esc_html__( 'Flex end', 'bricks' ),
			],
      'css' => [
			  [
				'property' => 'align-items',
				'selector' => '.x-content-timeline_item:nth-child(2n) .x-content-timeline_meta-inner',
			  ]
			],
		];

    $this->controls['contentTextAlignEven'] = [
			'tab'   => 'content',
      'inline' => true,
			'group' => 'layout',
			'label' => esc_html__( 'Content text align (even)', 'bricks' ),
			'type'  => 'select',
			'options' => [
				'left' => esc_html__( 'Left', 'bricks' ),
				'right' => esc_html__( 'Right', 'bricks' ),
			],
      'css' => [
			  [
				'property' => 'text-align',
				'selector' => '.x-content-timeline_item:nth-child(2n) .x-content-timeline_content-inner',
			  ]
			],
		];


    /* markers*/

    $this->controls['markerMargin'] = [
      'tab' => 'content',
      'group' => 'markers',
      'label' => esc_html__( 'Margin', 'bricks' ),
      'type' => 'dimensions',
      'css' => [
        [
        'property' => 'margin',
        'selector' => '.x-content-timeline_marker-inner',
        ]
      ],
      ];

      $this->controls['markerHeight'] = [
        'tab'      => 'content',
        'group'    => 'markers',
        'label'    => esc_html__( 'Height', 'bricks' ),
        'type'     => 'number',
        'units'    => true,
        'placeholder' => '40px',
        'css'      => [
          [
            'property' => 'height',
            'selector' => '.x-content-timeline_marker-inner',
          ],
        ],
      ];

      $this->controls['markerWidth'] = [
        'tab'      => 'content',
        'group'    => 'markers',
        'label'    => esc_html__( 'Width', 'bricks' ),
        'type'     => 'number',
        'placeholder' => '40px',
        'units'    => true,
        'css'      => [
          [
            'property' => 'width',
            'selector' => '.x-content-timeline_marker-inner',
          ],
        ],
      ];


      $this->controls['markerBackground'] = [
        'tab' => 'content',
        'group'    => 'markers',
        'label' => esc_html__( 'Background', 'bricks' ),
        'type' => 'color',
        'inline' => true,
        'css' => [
          [
          'property' => '--x-timeline-marker-bg',
          'selector' => '.x-content-timeline_marker-inner',
          ],
        ],
      ];

      $this->controls['markerColor'] = [
        'tab' => 'content',
        'group'    => 'markers',
        'label' => esc_html__( 'Color', 'bricks' ),
        'type' => 'color',
        'inline' => true,
        'css' => [
          [
          'property' => '--x-timeline-marker-color',
          'selector' => '.x-content-timeline_marker-inner',
          ],
        ],
      ];

      $this->controls['markerBorder'] = [
        'tab' => 'content',
        'group'    => 'markers',
        'label' => esc_html__( 'Border', 'bricks' ),
        'type' => 'border',
        'inline' => true,
        'css' => [
          [
          'property' => 'border',
          'selector' => '.x-content-timeline_marker-inner',
          ],
        ],
      ];

      $this->controls['markersActiveSep'] = [
        'tab'   => 'content',
        'group' => 'markers',
        'label' => esc_html__( 'Active styles', 'bricks' ),
        'type'     => 'separator',
      ];

      $this->controls['markerBackgroundActive'] = [
        'tab' => 'content',
        'group'    => 'markers',
        'label' => esc_html__( 'Background (active)', 'bricks' ),
        'type' => 'color',
        'inline' => true,
        'css' => [
          [
          'property' => '--x-timeline-marker-bg-active',
          'selector' => '.x-content-timeline_marker-inner',
          ],
        ],
      ];

      $this->controls['markerColorActive'] = [
        'tab' => 'content',
        'group'    => 'markers',
        'label' => esc_html__( 'Color (active)', 'bricks' ),
        'type' => 'color',
        'inline' => true,
        'css' => [
          [
          'property' => '--x-timeline-marker-color-active',
          'selector' => '.x-content-timeline_marker-inner',
          ],
        ],
      ];

      $this->controls['markerBorderActive'] = [
        'tab' => 'content',
        'group'    => 'markers',
        'label' => esc_html__( 'Border (active)', 'bricks' ),
        'type' => 'border',
        'inline' => true,
        'css' => [
          [
          'property' => 'border',
          'selector' => '.x-content-timeline_active .x-content-timeline_marker-inner',
          ],
        ],
      ];


      /* line */

      $this->controls['lineWidth'] = [
        'tab'      => 'content',
        'group'    => 'line',
        'label'    => esc_html__( 'Line thickness', 'bricks' ),
        'type'     => 'number',
        'placeholder' => '2px',
        'units'    => true,
        'css'      => [
          [
            'property' => 'width',
            'selector' => '.x-content-timeline_marker::before',
          ],
          [
            'property' => 'width',
            'selector' => '.x-content-timeline_line',
          ],
          [
            'property' => '--x-timeline-thickness',
          ],
        ],
      ];


      $this->controls['lineColor'] = [
        'tab' => 'content',
        'group'    => 'line',
        'label' => esc_html__( 'Color', 'bricks' ),
        'type' => 'color',
        'inline' => true,
        'css' => [
          [
          'property' => 'background-color',
          'selector' => '.x-content-timeline_marker::before',
          ],
          [
            'property' => 'background-color',
            'selector' => '.x-content-timeline_line',
          ]
        ],
      ];

      $this->controls['lineColorActive'] = [
        'tab' => 'content',
        'group'    => 'line',
        'label' => esc_html__( 'Color (active)', 'bricks' ),
        'type' => 'color',
        'inline' => true,
        'css' => [
          [
          'property' => 'background-color',
          'selector' => '.x-content-timeline_line-active',
          ]
        ],
      ];


      /* content */

      $contentSelector = '.x-content-timeline_content-inner';

      $this->controls['contentBackground'] = [
        'tab' => 'content',
        'group'    => 'content',
        'label' => esc_html__( 'Background Color', 'bricks' ),
        'type' => 'color',
        'inline' => true,
        'css' => [
          [
          'property' => '--x-timeline-content-bg',
          'selector' => $contentSelector,
          ],
        ],
      ];

      $this->controls['contentColor'] = [
        'tab' => 'content',
        'group'    => 'content',
        'label' => esc_html__( 'Color', 'bricks' ),
        'type' => 'color',
        'inline' => true,
        'css' => [
          [
          'property' => '--x-timeline-content-color',
          'selector' => $contentSelector,
          ],
        ],
      ];

      $this->controls['contentBorder'] = [
        'tab' => 'content',
        'group'    => 'content',
        'label' => esc_html__( 'Border', 'bricks' ),
        'type' => 'border',
        'inline' => true,
        'css' => [
          [
          'property' => 'border',
          'selector' => $contentSelector,
          ],
        ],
      ];

      $this->controls['contentBoxShadow'] = [
        'tab' => 'content',
        'group'    => 'content',
        'label' => esc_html__( 'Box Shadow', 'bricks' ),
        'type' => 'box-shadow',
        'inline' => true,
        'css' => [
          [
          'property' => 'box-shadow',
          'selector' => $contentSelector,
          ],
        ],
      ];

      $this->controls['contentOpacity'] = [
        'tab' => 'content',
        'group'    => 'content',
        'label' => esc_html__( 'Opacity', 'bricks' ),
        'type' => 'number',
        'units' => false,
        'step'	   => 0.1,
        'min'	   => 0,
        'max'	   => 1,
        'inline' => true,
        'css' => [
          [
          'property' => '--x-timeline-content-opacity',
          'selector' => $contentSelector,
          ],
        ],
      ];

      $this->controls['contentPadding'] = [
        'tab' => 'content',
        'label' => esc_html__( 'Padding', 'bricks' ),
        'type' => 'dimensions',
        'group' => 'content',
        'css' => [
          [
          'property' => 'padding',
          'selector' => $contentSelector,
          ]
        ],
        'placeholder' => [
          'top' => '3rem',
          'right' => '3rem',
          'bottom' => '3rem',
          'left' => '3rem',
        ],
      ];

      $this->controls['contentActiveSep'] = [
        'tab'   => 'content',
        'group' => 'content',
        'label' => esc_html__( 'Active styles', 'bricks' ),
        'type'     => 'separator',
      ];

      $this->controls['contentBackgroundActive'] = [
        'tab' => 'content',
        'group'    => 'content',
        'label' => esc_html__( 'Background Color (active)', 'bricks' ),
        'type' => 'color',
        'inline' => true,
        'css' => [
          [
          'property' => '--x-timeline-content-bg-active',
          'selector' => $contentSelector,
          ],
        ],
      ];

      $this->controls['contentColorActive'] = [
        'tab' => 'content',
        'group'    => 'content',
        'label' => esc_html__( 'Color (active)', 'bricks' ),
        'type' => 'color',
        'inline' => true,
        'css' => [
          [
          'property' => '--x-timeline-content-color-active',
          'selector' => $contentSelector,
          ],
        ],
      ];

      $this->controls['contentOpacityActive'] = [
        'tab' => 'content',
        'group'    => 'content',
        'label' => esc_html__( 'Opacity (active)', 'bricks' ),
        'type' => 'number',
        'units' => false,
        'step'	   => 0.1,
        'min'	   => 0,
        'max'	   => 1,
        'inline' => true,
        'css' => [
          [
          'property' => '--x-timeline-content-opacity-active',
          'selector' => $contentSelector,
          ],
        ],
      ];


      /* meta content */

      $metaContentSelector = '.x-content-timeline_meta-inner';

      $this->controls['metacontentBackground'] = [
        'tab' => 'content',
        'group'    => 'metaContent',
        'label' => esc_html__( 'Background Color', 'bricks' ),
        'type' => 'color',
        'inline' => true,
        'css' => [
          [
          'property' => '--x-timeline-meta-bg',
          'selector' => $metaContentSelector,
          ],
        ],
      ];

      $this->controls['metacontentColor'] = [
        'tab' => 'content',
        'group'    => 'metaContent',
        'label' => esc_html__( 'Color', 'bricks' ),
        'type' => 'color',
        'inline' => true,
        'css' => [
          [
          'property' => '--x-timeline-meta-color',
          'selector' => $metaContentSelector,
          ],
        ],
      ];

      $this->controls['metacontentBorder'] = [
        'tab' => 'content',
        'group'    => 'metaContent',
        'label' => esc_html__( 'Border', 'bricks' ),
        'type' => 'border',
        'inline' => true,
        'css' => [
          [
          'property' => 'border',
          'selector' => $metaContentSelector,
          ],
        ],
      ];

      $this->controls['metacontentBoxShadow'] = [
        'tab' => 'content',
        'group'    => 'metaContent',
        'label' => esc_html__( 'Box Shadow', 'bricks' ),
        'type' => 'box-shadow',
        'inline' => true,
        'css' => [
          [
          'property' => 'box-shadow',
          'selector' => $metaContentSelector,
          ],
        ],
      ];

      $this->controls['metacontentOpacity'] = [
        'tab' => 'content',
        'group'    => 'metaContent',
        'label' => esc_html__( 'Opacity', 'bricks' ),
        'type' => 'number',
        'units' => false,
        'step'	   => 0.1,
        'min'	   => 0,
        'max'	   => 1,
        'inline' => true,
        'css' => [
          [
            'property' => '--x-timeline-meta-opacity',
          'selector' => $metaContentSelector,
          ],
        ],
      ];

      $this->controls['metacontentPadding'] = [
        'tab' => 'content',
        'label' => esc_html__( 'Padding', 'bricks' ),
        'type' => 'dimensions',
        'group' => 'metaContent',
        'css' => [
          [
          'property' => 'padding',
          'selector' => $metaContentSelector,
          ]
        ],
      ];

      $this->controls['metaDisplay'] = [
        'tab'   => 'content',
        'inline' => true,
        'group' => 'metaContent',
        'label' => esc_html__( 'Meta display', 'bricks' ),
        'type'  => 'select',
        'options' => [
          'block' => esc_html__( 'Block', 'bricks' ),
          'none' => esc_html__( 'None', 'bricks' ),
        ],
        'css' => [
          [
          'property' => 'display',
          'selector' => '.x-content-timeline_meta',
          ]
        ],
      ];

      $this->controls['metacontentActiveSep'] = [
        'tab'   => 'content',
        'group' => 'metaContent',
        'label' => esc_html__( 'Active styles', 'bricks' ),
        'type'     => 'separator',
      ];

      $this->controls['metacontentBackgroundActive'] = [
        'tab' => 'content',
        'group'    => 'metaContent',
        'label' => esc_html__( 'Background Color (active)', 'bricks' ),
        'type' => 'color',
        'inline' => true,
        'css' => [
          [
            'property' => '--x-timeline-meta-bg-active',
          'selector' => $metaContentSelector,
          ],
        ],
      ];

      $this->controls['metacontentColorActive'] = [
        'tab' => 'content',
        'group'    => 'metaContent',
        'label' => esc_html__( 'Color (active)', 'bricks' ),
        'type' => 'color',
        'inline' => true,
        'css' => [
          [
            'property' => '--x-timeline-meta-color-active',
          'selector' => $metaContentSelector,
          ],
        ],
      ];

      $this->controls['metacontentOpacityActive'] = [
        'tab' => 'content',
        'group'    => 'metaContent',
        'label' => esc_html__( 'Opacity (active)', 'bricks' ),
        'type' => 'number',
        'units' => false,
        'step'	   => 0.1,
        'min'	   => 0,
        'max'	   => 1,
        'inline' => true,
        'css' => [
          [
            'property' => '--x-timeline-meta-opacity-active',
          'selector' => $metaContentSelector,
          ],
        ],
      ];



      /* scroll/active styles */

      $this->controls['scrollEffects'] = [
        'tab' => 'content',
        'label' => esc_html__( 'Active styles', 'bricks' ),
        'type' => 'select',
        'options' => [
          'true' => esc_html__( 'Enable', 'bricks' ),
          'false' => esc_html__( 'Disable', 'bricks' ),
        ],
        'inline'      => true,
        'clearable' => false,
        'placeholder' => esc_html__( 'Disable', 'bricks' ),
        'group'    => 'scroll',
      ];


      /* horizontal */

      $this->controls['horizontal'] = [
        'tab' => 'content',
        'label' => esc_html__( 'Support horizontal in Pro Slider', 'bricks' ),
        'type' => 'select',
        'options' => [
          'true' => esc_html__( 'Enable', 'bricks' ),
          'false' => esc_html__( 'Disable', 'bricks' ),
        ],
        'inline'      => true,
        'clearable' => false,
        'placeholder' => esc_html__( 'Disable', 'bricks' ),
        'group'    => 'slider',
      ];


      $this->controls['horizontalDirection'] = [
        'tab'   => 'content',
        'group' => 'slider',
        'label' => esc_html__( 'Timeline Flex direction', 'bricks' ),
        'description' => esc_html__( 'Change the "Align main axis" on the slides if the markers not aligned', 'bricks' ),
        'type'  => 'select',
        'options' => [
          'column' => esc_html__( 'Column', 'bricks' ),
          'column-reverse' => esc_html__( 'Column-reverse', 'bricks' ),
        ],
        'css' => [
          [
          'property' => 'flex-direction',
          'selector' => '.x-content-timeline_list[data-x-horizontal="true"] .x-content-timeline_item',
          ]
        ],
      ];

  }

  public function get_nestable_item() {
    
		return [
      'name'     => 'div',
			'label'    => esc_html__( 'Timeline Item', 'bricks' ),
      'settings' => [
          //'tag' => 'li',
          '_hidden'         => [
              '_cssClasses' => 'x-content-timeline_item',
          ],
      ],
			'children' => [
            [
              'name'     => 'div',
              'label'    => esc_html__( 'Content (structure)', 'bricks' ),
              'deletable' => false,
              'settings' => [
                  '_hidden'         => [
                      '_cssClasses' => 'x-content-timeline_content',
                  ],
              ],
              'children' => [
                [
                  'name'     => 'div',
                  'label'    => esc_html__( 'Content inner', 'bricks' ),
                  'settings' => [
                      '_hidden'         => [
                        '_cssClasses' => 'x-content-timeline_content-inner',
                      ],
                  ],
                  'children' => [
                    [
                      'name'     => 'heading',
                          'label'    => esc_html__( 'Heading', 'bricks' ),
                          'settings' => [
                            'text' => esc_html__( 'Content here', 'bricks' ),
                        ],
                  
                  ],
                ]
              ]
                
              ]
            ],
            [
              'name'     => 'div',
              'label'    => esc_html__( 'Marker (structure)', 'bricks' ),
              'deletable' => false,
                'settings' => [
                    '_hidden'         => [
                      '_cssClasses' => 'x-content-timeline_marker',
                    ],
                ],
              'children' => [
                [
                  'name'     => 'div',
                  'label'    => esc_html__( 'Marker inner', 'bricks' ),
                  'deletable' => false,
                  'settings' => [
                      '_hidden'         => [
                        '_cssClasses' => 'x-content-timeline_marker-inner',
                      ],
                  ],
                  'children' => [
                    [
                      'name'     => 'icon',
                      'label'    => esc_html__( 'Icon', 'bricks' ),
                      'settings' => [
                            'icon'     => [
                              'icon'    => 'ion-ios-calendar',
                              'library' => 'ionicons',
                            ],
                            '_hidden'         => [
                              '_cssClasses' => 'x-content-timeline_marker-icon',
                            ],
                        ],
                      ],
                  ]
                ]
                ]
              ],
              [
                'name'     => 'div',
                'label'    => esc_html__( 'Meta (structure)', 'bricks' ),
                'deletable' => false,
                'settings' => [
                    '_hidden'         => [
                      '_cssClasses' => 'x-content-timeline_meta',
                    ],
                ],
                'children' => [
                  [
                  'name'     => 'div',
                  'label'    => esc_html__( 'Meta inner', 'bricks' ),
                  'settings' => [
                      '_hidden'         => [
                          '_cssClasses' => 'x-content-timeline_meta-inner',
                      ],
                  ],
                  'children' => [
                    [
                      'name'     => 'text-basic',
                      'label'    => esc_html__( 'Meta text', 'bricks' ),
                        'settings' => [
                          'text' => esc_html__( 'Meta content', 'bricks' ),
                        ],
                    ],
                  ]
                ]
              ]
          ],
          
        ]
		];
	}

	public function get_nestable_children() {
		$children = [];

		for ( $i = 0; $i < 1; $i++ ) {
			$item = $this->get_nestable_item();

			// Replace {item_index} with $index
			$item       = json_encode( $item );
			$item       = str_replace( '{item_index}', $i + 1, $item );
			$item       = json_decode( $item, true );
			$children[] = $item;
		}

		return $children;
	}

  // Methods: Frontend-specific
	public function enqueue_scripts() {
		if (! \BricksExtras\Helpers::elementCSSAdded($this->name) ) {
			wp_enqueue_style( 'x-content-timeline', BRICKSEXTRAS_URL . 'components/assets/css/contenttimeline.css', [], '' );
		}
	}
  
  public function render() {

    $element  = $this->element;

    $horizontal = isset( $this->settings['horizontal'] ) ? esc_attr( $this->settings['horizontal'] ) : 'false';
    $scrollEffects = isset( $this->settings['scrollEffects'] ) ? esc_attr( $this->settings['scrollEffects'] ) : 'false';

    $this->set_attribute( '_root', 'data-x-id', $this->id );
    $this->set_attribute( '_root', 'data-x-horizontal', $horizontal );
    $this->set_attribute( '_root', 'data-x-scroll', $scrollEffects );

    $this->set_attribute( 'x-content-timeline_list', 'data-x-horizontal', $horizontal );
    $this->set_attribute( 'x-content-timeline_line', 'class', 'x-content-timeline_line' );
    $this->set_attribute( 'x-content-timeline_line-active', 'class', 'x-content-timeline_line-active' );
    $this->set_attribute( 'x-content-timeline_list', 'class', 'x-content-timeline_list' );

    $listTag = isset( $this->settings['listTag'] ) ? \Bricks\Helpers::sanitize_html_tag( $this->settings['listTag'], 'div' ) : 'div';

    echo "<div {$this->render_attributes( '_root' )}>";

    echo "<" . $listTag . " {$this->render_attributes( 'x-content-timeline_list' )}>";

      if ( method_exists('\Bricks\Frontend','render_element') ) {

          if ( ! empty( $element['children'] ) && is_array( $element['children'] ) ) {

              foreach ( $element['children'] as $child_id ) {
                  if ( ! array_key_exists( $child_id, \Bricks\Frontend::$elements ) ) {
                      continue;
                  }

                  $child = \Bricks\Frontend::$elements[ $child_id ];

                  echo \Bricks\Frontend::render_element( $child );
              }

          }

      }

    echo "</" . $listTag . ">";

    echo "<div {$this->render_attributes( 'x-content-timeline_line' )}>";
    echo "<div {$this->render_attributes( 'x-content-timeline_line-active' )}>";
    echo "</div>";
    echo "</div>";

    echo "</div>";

    if ('true' === $horizontal) {
      wp_enqueue_script( 'x-content-timeline-horizontal', BRICKSEXTRAS_URL . 'components/assets/js/' . \BricksExtras\Helpers::maybeMinifyScripts('contenttimelinehorizontal') . '.js', '', '1.0.1', true );
    } else {
      wp_enqueue_script( 'x-content-timeline', BRICKSEXTRAS_URL . 'components/assets/js/' . \BricksExtras\Helpers::maybeMinifyScripts('contenttimeline') . '.js', '', '1.0.5', true );
    }
    
  }

  public static function render_builder() { ?>

		<script type="text/x-template" id="tmpl-bricks-element-xcontenttimeline">
			
    <component 
      class="x-content-timeline"
    >
      <div
        class="x-content-timeline_list"
        :data-x-horizontal="settings.horizontal"
        >
          <bricks-element-children  :element="element"  />
      </div>
    </component>    

		</script>

	<?php }

}