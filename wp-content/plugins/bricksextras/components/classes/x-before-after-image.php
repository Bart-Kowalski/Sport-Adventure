<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class X_Before_After_Image extends \Bricks\Element {

  // Element properties
  public $category     = 'extras';
	public $name         = 'xbeforeafterimage';
	public $icon         = 'ti-split-v-alt';
	public $css_selector = '';
	public $nestable = true;

  
  public function get_label() {
	  return esc_html__( 'Before / After Image', 'extras' );
  }
  public function set_control_groups() {

    $this->control_groups['control'] = [
			'title' => esc_html__( 'Slider control', 'extras' ),
			'tab' => 'content',
		];

    $this->control_groups['icons'] = [
			'title' => esc_html__( 'Slider icons', 'extras' ),
			'tab' => 'content',
		];

    $this->control_groups['labels'] = [
			'title' => esc_html__( 'Labels', 'extras' ),
			'tab' => 'content',
		];

    $this->control_groups['imageStyles'] = [
			'title' => esc_html__( 'Image styling', 'extras' ),
			'tab' => 'content',
		];

    $this->control_groups['userInteraction'] = [
			'title' => esc_html__( 'User Interaction', 'extras' ),
			'tab' => 'content',
		];

  }

  public function set_controls() {

    

    $this->controls['start'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Start position (0-100)', 'extras' ),
			'inline'      => true,
			'small'		  => true,
			'type' => 'number',
			'css' => [
			  [
				'selector' => '',  
				'property' => '--x-start-position',
			  ],
			],
      'placeholder' => esc_html__( '50', 'bricks' ),
		];

    $this->controls['direction'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Direction', 'bricks' ),
			'type' => 'select',
			'options' => [
			  'vertical' => esc_html__( 'Vertical', 'bricks' ),
			  'horizontal' => esc_html__( 'Horizontal', 'bricks' ),
			],
			'inline'      => true,
			'clearable' => false,
			'placeholder' => esc_html__( 'Horizontal', 'bricks' ),
		];

    $this->controls['sliderHeight'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Height', 'extras' ),
			'inline'      => true,
			'small'		  => true,
      'tooltip'  => [
        'content'  => "Only set if needing to force the height",
        'position' => 'top-left',
      ],
			'type' => 'number',
			'units'    => true,
			'css' => [
			  [
				'selector' => '',  
				'property' => 'height',
			  ],
			],
		];

    $this->controls['aspectRatio'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Aspect ratio', 'extras' ),
			'inline'      => true,
			'small'		  => true,
			'type' => 'number',
      'tooltip'  => [
        'content'  => "If setting aspect-ratio don't set a height",
        'position' => 'top-left',
      ],
			'units'    => true,
			'css' => [
			  [
				'selector' => '',  
				'property' => 'aspect-ratio',
			  ],
			],
      'placeholder' => '',
		];

    /* slide control */

    $this->controls['controlAriaLabel'] = [
			'tab' => 'content',
			'group' => 'control',
			'label' => esc_html__( 'Control input aria-label', 'bricks' ),
			//'inline' => true,
			'type' => 'text',
			'placeholder' => esc_html__( 'Percentage of image visible', 'bricks' ),
		  ];

    $this->controls['lineSep'] = [
			'group'    => 'control',
			'label'    => esc_html__( 'Line', 'bricks' ),
			'type'     => 'separator',
		];

    $this->controls['lineThickness'] = [
			'tab' => 'content',
      'group' => 'control',
			'label' => esc_html__( 'Line thickness', 'bricks' ),
			'inline'      => true,
			'small'		  => true,
			'type' => 'number',
      'units' => true,
			'css' => [
			  [
				'selector' => '.x-before-after_slider-line',  
				'property' => '--x-before-after-line',
			  ],
			],
		];

    $this->controls['lineBackground'] = [
			'tab' => 'content',
      'group' => 'control',
			'label' => esc_html__( 'Line color', 'bricks' ),
			'inline'      => true,
			'small'		  => true,
			'type' => 'color',
			'css' => [
			  [
				'selector' => '.x-before-after_slider-line',  
				'property' => 'background-color',
			  ],
			],
		];

    $this->controls['iconSep'] = [
			'group'    => 'icons',
			'label'    => esc_html__( 'Icons', 'bricks' ),
			'type'     => 'separator',
		];

    $this->controls['iconLeft'] = [
			'tab'     => 'content',
      'group' => 'icons',
			'label'   => esc_html__( 'Icon left', 'bricks' ),
			'type'    => 'icon',
      'default'  => [
        'library' => 'themify',
        'icon'    => 'ti-angle-left',
      ],
      'clearable' => false,
		];

    $this->controls['iconRight'] = [
			'tab'     => 'content',
      'group' => 'icons',
			'label'   => esc_html__( 'Icon right', 'bricks' ),
			'type'    => 'icon',
      'default'  => [
        'library' => 'themify',
        'icon'    => 'ti-angle-right',
      ],
		];


    $this->controls['iconSize'] = [
      'tab'      => 'content',
      'group' => 'icons',
      'label'    => esc_html__( 'Icon size', 'bricks' ),
      'type'     => 'number',
      'units'       => true,
      'css'      => [
        [
          'property' => 'font-size',
          'selector' => '.x-before-after_slider-button-icon',
        ],
      ],
      'placeholder' => esc_html__( '14px', 'bricks' ),
    ];

    $this->controls['iconColor'] = [
      'tab'   => 'content',
      'group' => 'icons',
      'label' => esc_html__( 'Icon color', 'bricks' ),
      'type'  => 'color',
      'css'   => [
        [
          'property' => 'color',
          'selector' => '.x-before-after_slider-button-icon',  
        ],
      ],
    ];

    $this->controls['iconColorActive'] = [
      'tab'   => 'content',
      'group' => 'icons',
      'label' => esc_html__( 'Icon color (focus)', 'bricks' ),
      'type'  => 'color',
      'css'   => [
        [
          'property' => 'color',
          'selector' => 'input.x-before-after_slider:focus-visible ~ .x-before-after_slider-button .x-before-after_slider-button-icon',  
        ],
      ],
    ];

    $this->controls['iconMargin'] = [
			'tab'   => 'content',
			'group' => 'icons',
			'label' => esc_html__( 'Margin', 'bricks' ),
			'type'  => 'dimensions',
			'css'   => [
				[
					'property' => 'padding',
					'selector' => '.x-before-after_slider-button-icon > *',  
				],
			],
		];

    $this->controls['controlSep'] = [
			'group'    => 'control',
			'label'    => esc_html__( 'Slider control', 'bricks' ),
			'type'     => 'separator',
		];

    $this->controls['controlThickness'] = [
			'tab' => 'content',
      'group' => 'control',
			'label' => esc_html__( 'Slide control thickness', 'bricks' ),
			'inline'      => true,
			'small'		  => true,
			'type' => 'number',
      'units' => true,
			'css' => [
			  [
				'selector' => '',  
				'property' => '--x-before-after-controlwidth',
			  ],
			],
      'placeholder' => esc_html__( '40px', 'bricks' ),
		];

    $this->controls['controlLength'] = [
			'tab' => 'content',
      'group' => 'control',
			'label' => esc_html__( 'Slide control length', 'bricks' ),
			'inline'      => true,
			'small'		  => true,
			'type' => 'number',
      'units' => true,
			'css' => [
			  [
				'selector' => '',  
				'property' => '--x-before-after-controllength',
			  ],
			],
      'placeholder' => esc_html__( '60px', 'bricks' ),
		];

    $this->controls['controlBackgroundColor'] = [
      'tab'   => 'content',
      'group' => 'control',
      'label' => esc_html__( 'Background color', 'bricks' ),
      'type'  => 'color',
      'css'   => [
        [
          'property' => 'background-color',
          'selector' => '.x-before-after_slider-button-icon',  
        ],
      ],
    ];

    $this->controls['controlBackgroundColorActive'] = [
      'tab'   => 'content',
      'group' => 'control',
      'label' => esc_html__( 'Background color (focus)', 'bricks' ),
      'type'  => 'color',
      'css'   => [
        [
          'property' => 'background-color',
          'selector' => 'input.x-before-after_slider:focus-visible ~ .x-before-after_slider-button .x-before-after_slider-button-icon',  
        ],
      ],
    ];

    $this->controls['controlBackgroundColorBlur'] = [
      'tab'   => 'content',
      'group' => 'control',
      'label' => esc_html__( 'Background blur', 'bricks' ),
      'type'  => 'number',
      'inline' => true,
      'units' => true,
      'placeholder' => esc_html__( '0px', 'bricks' ),
      'css'   => [
        [
          'property' => '--x-before-after-blur',
          'selector' => '.x-before-after_slider-button',  
        ],
      ],
    ];

    

    $this->controls['controlBorder'] = [
      'tab'   => 'content',
      'label' => esc_html__( 'Border', 'bricks' ),
      'group' => 'control',
      'type'  => 'border',
      'css'   => [
        [
          'property' => 'border',
          'selector' => '.x-before-after_slider-button-icon',  
        ],
      ],
    ];

    $this->controls['controlBorderFocus'] = [
      'tab'   => 'content',
      'label' => esc_html__( 'Border (focus)', 'bricks' ),
      'group' => 'control',
      'type'  => 'border',
      'css'   => [
        [
          'property' => 'border',
          'selector' => 'input.x-before-after_slider:focus-visible ~ .x-before-after_slider-button .x-before-after_slider-button-icon',  
        ],
      ],
    ];

    $this->controls['controlBoxShadow'] = [
      'tab'   => 'content',
      'label' => esc_html__( 'Box shadow', 'bricks' ),
      'group' => 'control',
      'type'  => 'box-shadow',
      'css'   => [
        [
          'property' => 'box-shadow',
          'selector' => '.x-before-after_slider-button-icon',  
        ],
      ],
    ];

    


    /* image styles */

    $this->controls['imagestyleSep'] = [
      'tab'   => 'content',
      'type'  => 'separator',
      'group'  => 'imageStyles',
      'description' => esc_html__( 'For full control over styling (and to change the images) go to the individual image elements inside the structure panel', 'bricks' ),
    ];

    $this->controls['beforeFilter'] = [
			'tab'    => 'content',
			'group'  => 'imageStyles',
			'label'  => esc_html__( 'Before filters', 'bricksable' ),
			'type'   => 'filters',
			'inline' => true,
			'css'    => [
        [
					'property' => 'filter',
					'selector' => '.x-before-after-image_block:first-of-type img',
        ],
      ]
    ];

    $this->controls['afterFilter'] = [
			'tab'    => 'content',
			'group'  => 'imageStyles',
			'label'  => esc_html__( 'After filters', 'bricksable' ),
			'type'   => 'filters',
			'inline' => true,
			'css'    => [
        [
					'property' => 'filter',
					'selector' => '.x-before-after-image_block:nth-of-type(2) img',
        ],
      ]
    ];


    /* labels */

    $this->controls['maybeLabels'] = [
      'tab' => 'content',
      'label' => esc_html__( 'Display labels', 'bricks' ),
      'type'  => 'checkbox',
      'group' => 'labels',
    ];

    $this->controls['beforeText'] = [
			'tab' => 'content',
			'group' => 'labels',
			'label' => esc_html__( 'Before text', 'bricks' ),
			'inline' => true,
			'type' => 'text',
      'default' => esc_html__( 'Before' ),
			//'placeholder' => esc_html__( 'Before', 'bricks' ),
			'required' => ['maybeLabels', '=', true],
		  ];

      $this->controls['afterText'] = [
        'tab' => 'content',
        'group' => 'labels',
        'label' => esc_html__( 'After text', 'bricks' ),
        'inline' => true,
        'type' => 'text',
        'default' => esc_html__( 'After' ),
        //'placeholder' => esc_html__( 'After', 'bricks' ),
        'required' => ['maybeLabels', '=', true],
        ];


        $this->controls['labelTypography'] = [
          'tab'    => 'content',
          'group'  => 'labels',
          'type'   => 'typography',
          'label'  => esc_html__( 'Typography', 'extras' ),
          'required' => ['maybeLabels', '=', true],
          'css'    => [
            [
              'property' => 'font',
              'selector' => '.x-before-after_label',  
            ],
          ],
        ];


    $this->controls['labelBackgroundColor'] = [
      'tab'   => 'content',
      'group' => 'labels',
      'label' => esc_html__( 'Background color', 'bricks' ),
      'required' => ['maybeLabels', '=', true],
      'type'  => 'color',
      'css'   => [
        [
          'property' => 'background-color',
          'selector' => '.x-before-after_label',  
        ],
      ],
    ];

    $this->controls['labelBorder'] = [
      'tab'   => 'content',
      'label' => esc_html__( 'Border', 'bricks' ),
      'required' => ['maybeLabels', '=', true],
      'group' => 'labels',
      'type'  => 'border',
      'css'   => [
        [
          'property' => 'border',
          'selector' => '.x-before-after_label',  
        ],
      ],
    ];

    $this->controls['labelBoxShadow'] = [
      'tab'   => 'content',
      'label' => esc_html__( 'Box shadow', 'bricks' ),
      'group' => 'labels',
      'type'  => 'box-shadow',
      'css'   => [
        [
          'property' => 'box-shadow',
          'selector' => '.x-before-after_label',  
        ],
      ],
      'required' => ['maybeLabels', '=', true],
    ];


    $this->controls['labelPadding'] = [
			'tab'   => 'content',
			'group' => 'labels',
			'label' => esc_html__( 'Padding', 'bricks' ),
			'type'  => 'dimensions',
			'css'   => [
				[
					'property' => 'padding',
					'selector' => '.x-before-after_label',  
				],
			],
      'required' => ['maybeLabels', '=', true],
		];


    $this->controls['labelBeforePositionSep'] = [
			'group'    => 'labels',
			'label'    => esc_html__( 'Before label position', 'bricks' ),
			'type'     => 'separator',
			'required' => ['maybeLabels', '=', true],
		];

		$this->controls['labelTop'] = [
			'group'    => 'labels',
			'label'    => esc_html__( 'Top', 'bricks' ),
			'type'     => 'number',
			'units'    => true,
			'css'      => [
				[
					'property' => 'top',
					'selector' => '.x-before-after_before-label'
				],
			],
			'required' => ['maybeLabels', '=', true],
		];

    $this->controls['labelLeft'] = [
			'group'    => 'labels',
			'label'    => esc_html__( 'Left', 'bricks' ),
			'type'     => 'number',
			'units'    => true,
			'css'      => [
				[
					'property' => 'left',
					'selector' => '.x-before-after_before-label'
				],
			],
      'default'    => '20px',
			'required' => ['maybeLabels', '=', true],
		];

    $this->controls['labelRight'] = [
			'group'    => 'labels',
			'label'    => esc_html__( 'Right', 'bricks' ),
			'type'     => 'number',
			'units'    => true,
			'css'      => [
				[
					'property' => 'right',
					'selector' => '.x-before-after_before-label'
				],
			],
			'required' => ['maybeLabels', '=', true],
		];

    $this->controls['labelBottom'] = [
			'group'    => 'labels',
			'label'    => esc_html__( 'Bottom', 'bricks' ),
			'type'     => 'number',
			'units'    => true,
			'css'      => [
				[
					'property' => 'bottom',
					'selector' => '.x-before-after_before-label'
				],
			],
      'default'    => '20px',
			'required' => ['maybeLabels', '=', true],
		];


    $this->controls['labelAfterPositionSep'] = [
			'group'    => 'labels',
			'label'    => esc_html__( 'After label position', 'bricks' ),
			'type'     => 'separator',
			'required' => ['maybeLabels', '=', true],
		];

		$this->controls['labelAfterTop'] = [
			'group'    => 'labels',
			'label'    => esc_html__( 'Top', 'bricks' ),
			'type'     => 'number',
			'units'    => true,
			'css'      => [
				[
					'property' => 'top',
					'selector' => '.x-before-after_after-label'
				],
			],
      'default'    => '20px',
			'required' => ['maybeLabels', '=', true],
		];

    $this->controls['labelAfterLeft'] = [
			'group'    => 'labels',
			'label'    => esc_html__( 'Left', 'bricks' ),
			'type'     => 'number',
			'units'    => true,
			'css'      => [
				[
					'property' => 'left',
					'selector' => '.x-before-after_after-label'
				],
			],
			'required' => ['maybeLabels', '=', true],
		];

    $this->controls['labelAfterRight'] = [
			'group'    => 'labels',
			'label'    => esc_html__( 'Right', 'bricks' ),
			'type'     => 'number',
			'units'    => true,
			'css'      => [
				[
					'property' => 'right',
					'selector' => '.x-before-after_after-label'
				],
			],
      'default'    => '20px',
			'required' => ['maybeLabels', '=', true],
		];

    $this->controls['labelAfterBottom'] = [
			'group'    => 'labels',
			'label'    => esc_html__( 'Bottom', 'bricks' ),
			'type'     => 'number',
			'units'    => true,
			'css'      => [
				[
					'property' => 'bottom',
					'selector' => '.x-before-after_after-label'
				],
			],
			'required' => ['maybeLabels', '=', true],
		];


    /* user interaction */

    $this->controls['maybeMouseMove'] = [
      'tab' => 'content',
      'label' => esc_html__( 'Allow cursor move to control slider', 'bricks' ),
      'type'  => 'checkbox',
      'group' => 'userInteraction',
    ];

    $this->controls['touchSep'] = [
      'tab'   => 'content',
      'type'  => 'separator',
      'group'  => 'userInteraction',
      'description' => esc_html__( 'The touchable size of the control on touch devices for dragging', 'bricks' ),
    ];

    $this->controls['touchHeight'] = [
			'tab' => 'content',
      'group' => 'userInteraction',
			'label' => esc_html__( 'Touch height', 'bricks' ),
			'inline'      => true,
			'small'		  => true,
			'type' => 'number',
      'units' => true,
			'css' => [
			  [
				'selector' => '',  
				'property' => '--x-before-after-touchheight',
			  ],
			],
      'placeholder' => esc_html__( '60px', 'bricks' ),
		];

    $this->controls['touchWidth'] = [
			'tab' => 'content',
      'group' => 'userInteraction',
			'label' => esc_html__( 'Touch width', 'bricks' ),
			'inline'      => true,
			'small'		  => true,
			'type' => 'number',
      'units' => true,
			'css' => [
			  [
				'selector' => '',  
				'property' => '--x-before-after-touchwidth',
			  ],
			],
      'placeholder' => esc_html__( '60px', 'bricks' ),
		];
    

  }

  // Methods: Frontend-specific
  public function enqueue_scripts() {
    wp_enqueue_script( 'x-before-after-image', BRICKSEXTRAS_URL . 'components/assets/js/' . \BricksExtras\Helpers::maybeMinifyScripts('beforeafterimage') . '.js', '', '1.0.1', true );
    if (! \BricksExtras\Helpers::elementCSSAdded($this->name) ) {
      wp_enqueue_style( 'x-before-after-image', BRICKSEXTRAS_URL . 'components/assets/css/beforeafterimage.css', [], '' );
    }
  
  }
  
  public function render() {

     // Icon
    $iconLeft = empty( $this->settings['iconLeft'] ) ? false : self::render_icon( $this->settings['iconLeft'] );
    $iconRight = empty( $this->settings['iconRight'] ) ? false : self::render_icon( $this->settings['iconRight'] );

    $direction = isset( $this->settings['direction'] ) ? esc_attr( $this->settings['direction'] ) : 'horizontal';

    $maybeLabels = isset( $this->settings['maybeLabels'] ) ? $this->settings['maybeLabels'] : false;

    $beforeText = isset( $this->settings['beforeText'] ) ?  esc_attr__( $this->settings['beforeText'] ) :  esc_attr__( 'Before' );
    $afterText = isset( $this->settings['afterText'] ) ? esc_attr__( $this->settings['afterText'] ) :  esc_attr__( 'After' );

    $controlAriaLabel = isset( $this->settings['controlAriaLabel'] ) ? esc_attr__( $this->settings['controlAriaLabel'] ) : esc_attr__( 'Percentage of image visible' );

    $config = [
      'direction' => $direction,
      'maybeMouseMove' => isset( $this->settings['maybeMouseMove'] ) ? $this->settings['maybeMouseMove'] : false
    ];

    $this->set_attribute( '_root', 'class', 'x-before-after' );
    $this->set_attribute( 'x-before-after_container', 'class', 'x-before-after_container' );

    $this->set_attribute( 'x-before-after_slider', 'class', 'x-before-after_slider' );
    $this->set_attribute( 'x-before-after_slider', 'type', 'range' );
    $this->set_attribute( 'x-before-after_slider', 'min', '00' );
    $this->set_attribute( 'x-before-after_slider', 'max', '100' );
    $this->set_attribute( 'x-before-after_slider', 'value', '50' );
    $this->set_attribute( 'x-before-after_slider', 'aria-label', $controlAriaLabel );
    $this->set_attribute( 'x-before-after_slider', 'orient', $direction );

    $this->set_attribute( 'x-before-after_slider-line-before', 'class', ['x-before-after_slider-line','x-before-after_slider-line-before'] );
    $this->set_attribute( 'x-before-after_slider-line-before', 'aria-hidden', 'true' );

    $this->set_attribute( 'x-before-after_slider-line-after', 'class', ['x-before-after_slider-line','x-before-after_slider-line-after'] );
    $this->set_attribute( 'x-before-after_slider-line-after', 'aria-hidden', 'true' );

    $this->set_attribute( 'x-before-after_slider-button', 'class', 'x-before-after_slider-button' );
    $this->set_attribute( 'x-before-after_slider-button', 'aria-hidden', 'true' );

    $this->set_attribute( 'x-before-after_before-label', 'class', ['x-before-after_before-label','x-before-after_label'] );
    $this->set_attribute( 'x-before-after_after-label', 'class', ['x-before-after_after-label','x-before-after_label'] );

    $this->set_attribute( 'x-before-after_slider-button-icon', 'class', 'x-before-after_slider-button-icon' );

    $this->set_attribute( '_root', 'data-x-before-after', wp_json_encode( $config ) );

    echo "<div {$this->render_attributes( '_root' )}>";
      echo "<div {$this->render_attributes( 'x-before-after_container' )}>";
        echo \Bricks\Frontend::render_children( $this );
      echo "</div>";

      echo "<input {$this->render_attributes( 'x-before-after_slider' )}/>";
        echo "<div {$this->render_attributes( 'x-before-after_slider-line-before' )}></div>";
        echo "<div {$this->render_attributes( 'x-before-after_slider-button' )}>";
          echo "<div {$this->render_attributes( 'x-before-after_slider-button-icon' )}> $iconLeft $iconRight </div>";
        echo "</div>";
        echo "<div {$this->render_attributes( 'x-before-after_slider-line-after' )}></div>";

        if ( $maybeLabels ) {
          echo "<div {$this->render_attributes( 'x-before-after_before-label' )}>";
          echo $beforeText;
          echo "</div>";

          echo "<div {$this->render_attributes( 'x-before-after_after-label' )}>";
          echo $afterText;
          echo "</div>";
        }

      echo "</div>";
    
  }

  public function get_nestable_item() {
    
		return [
      'name'     => 'block',
			'label'    => esc_html__( 'Block', 'bricks' ),
      'deletable' => false,
      'settings' => [
          // NOTE: Undocumented (@since 1.5 to apply hard-coded hidden settings)
          '_hidden'         => [
              '_cssClasses' => 'x-before-after-image_block',
          ],
      ],
			'children' => [
				[
					'name'     => 'image',
          'label'    => esc_html__( 'Image', 'bricks' ),
              'settings' => [
                'image' => [
                  'url'  => 'https://images.unsplash.com/photo-1499578124509-1611b77778c8?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=2676&q=80',
                ],
                'caption' => 'none',
                  '_hidden'         => [
                      '_cssClasses' => 'x-before-after-image_image',
                  ],
              ],
          ],
        ]
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

  

  public static function render_builder() { ?>

		<script type="text/x-template" id="tmpl-bricks-element-xbeforeafterimage">

    <component class="x-before-after" :data-x-before-after="settings.direction">
        <div class="x-before-after_container">
          <bricks-element-children  :element="element"  />
        </div>
        <!-- step="10" -->
        <input
          type="range"
          min="0"
          max="100"
          value="50"
          aria-label="Percentage of before photo shown"
          class="x-before-after_slider"
        />
        <div class="x-before-after_slider-line x-before-after_slider-line-before" aria-hidden="true"></div>
        <div class="x-before-after_slider-button" aria-hidden="true">
          <div class="x-before-after_slider-button-icon">
            <icon-svg v-if="settings.iconLeft" :iconSettings="settings.iconLeft"/>
            <icon-svg v-if="settings.iconRight" :iconSettings="settings.iconRight"/>
          </div>
        </div>
        <div class="x-before-after_slider-line x-before-after_slider-line-after" aria-hidden="true"></div>
        <contenteditable
						tag="div"
						class="x-before-after_label x-before-after_before-label"
						:name="name"
						controlKey="beforeText"
						v-show="settings.maybeLabels"
						toolbar="style align"
						:settings="settings"
					/>
          <contenteditable
						tag="div"
						class="x-before-after_label x-before-after_after-label"
						:name="name"
						controlKey="afterText"
						v-show="settings.maybeLabels"
						toolbar="style align"
						:settings="settings"
					/>
    </component>
		</script>

	<?php }

}