<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class X_Toggle_Switch extends \Bricks\Element {

  // Element properties
  public $category     = 'extras';
	public $name         = 'xtoggleswitch';
	public $icon         = 'ti-layout-menu';
	public $css_selector = '';
	public $scripts      = ['xToggleSwitch'];
  public $loop_index = 0;

  
  public function get_label() {
	  return esc_html__( 'Toggle Switch', 'extras' );
  }
  public function set_control_groups() {

    $this->control_groups['labels'] = [
			'title' => esc_html__( 'Labels', 'extras' ),
			'tab' => 'content',
		];

    $this->control_groups['labelsLayout'] = [
			'title' => esc_html__( 'Labels layout', 'extras' ),
			'tab' => 'content',
      'required' => ['type', '=', 'multiple']
		];

    $this->control_groups['styling'] = [
			'title' => esc_html__( 'Styling', 'extras' ),
			'tab' => 'content',
		];

    $this->control_groups['switchSlider'] = [
			'title' => esc_html__( 'Control / Slider Styles', 'extras' ),
			'tab' => 'content',
      'required' => ['type', '!=', 'multiple']
		];

  }

  public function set_controls() {

    $this->controls['type'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Toggle type', 'bricks' ),
			'type' => 'select',
			'options' => [
			  'double' => esc_html__( 'Two labels + Switch', 'bricks' ),
			  'multiple' => esc_html__( 'Multiple labels', 'bricks' ),
			],
			'inline'      => true,
			//'small'		  => true,
			'clearable' => false,
			'placeholder' => esc_html__( 'Two labels + Switch', 'bricks' ),
			//'default' => 'double',
		];

    $this->controls['hasLoop'] = [
			'tab'   => 'content',
			'label' => esc_html__( 'Use query loop', 'bricks' ),
			'type'  => 'checkbox',
      'group' => 'labels',
			'required' => ['type', '=', 'multiple']
		];

		$this->controls['query'] = [
			'tab'      => 'content',
			'label'    => esc_html__( 'Query', 'bricks' ),
			'type'     => 'query',
      'group' => 'labels',
			'popup'    => true,
			'inline'   => true,
			'required' => [ 
				[ 'hasLoop', '!=', '' ],
				['type', '=', 'multiple']
			],
		];

    $this->controls['labels'] = [
			'tab'         => 'content',
			'placeholder' => esc_html__( 'Label', 'bricks' ),
			'type'        => 'repeater',
      'group' => 'labels',
			'fields'      => [
				'title'    => [
					'label' => esc_html__( 'Label text', 'bricks' ),
					'type'  => 'text',
          'hasDynamicData' => false,
				],
			],
			'default'     => [
				[
					'title'    => esc_html__( 'Label', 'bricks' ),
				],
				[
					'title'    => esc_html__( 'Label', 'bricks' ) . ' 2',
				],

        [
					'title'    => esc_html__( 'Label', 'bricks' ) . ' 3',
				],
			],
      'required' => [
        ['type', '=', 'multiple'],
        ['hasLoop', '!=', true]
      ]
		];

    $this->controls['labelsQuery'] = [
			'tab'         => 'content',
			'label' => esc_html__( 'Label text', 'bricks' ),
			'type'        => 'repeater',
      'group' => 'labels',
			'type'  => 'text',
			'default'     => [
				[
					'title'    => esc_html__( 'Label', 'bricks' ),
				],
			],
      'required' => [
        ['type', '=', 'multiple'],
        ['hasLoop', '=', true]
      ]
		];


    $this->controls['ariaLabel'] = [
      'tab' => 'content',
      'label' => esc_html__( 'Tablist Aria label', 'bricks' ),
      'type' => 'text',
      'group' => 'labels',
      'placeholder' => 'Switch content',
      'required' => ['type', '=', 'multiple']
    ];
    

		$this->controls['labeldirection'] = [
			'tab'      => 'content',
			'label'    => esc_html__( 'Flex direction', 'bricks' ),
			'group'		  => 'labelsLayout',
			'tooltip'  => [
				'content'  => 'flex-direction',
				'position' => 'top-left',
			],
			'type'     => 'direction',
			'css'      => [
				[
					'property' => 'flex-direction',
					'selector' => '.x-toggle-switch_labels',
				],
			],
			'inline'   => true,
			'rerender' => true,
		];

    $this->controls['labelflexWrap'] = [
			'tab'         => 'content',
			'label'       => esc_html__( 'Flex wrap', 'bricks' ),
			'group'		  => 'labelsLayout',
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
					'selector' => '.x-toggle-switch_labels',
				],
			],
			'placeholder' => esc_html__( 'Wrap', 'bricks' ),
		];

    $this->controls['labeljustifyContent'] = [
			'tab'      => 'content',
			'label'    => esc_html__( 'Align main axis', 'bricks' ),
			'group'		  => 'labelsLayout',
			'tooltip'  => [
				'content'  => 'justify-content',
				'position' => 'top-left',
			],
			'type'     => 'justify-content',
			'css'      => [
				[
					'property' => 'justify-content',
					'selector' => '.x-toggle-switch_labels',
				],
			],
		];

		$this->controls['labelalignItems'] = [
			'tab'      => 'content',
			'label'    => esc_html__( 'Align cross axis', 'bricks' ),
			'group'		  => 'labelsLayout',
			'tooltip'  => [
				'content'  => 'align-items',
				'position' => 'top-left',
			],
			'type'     => 'align-items',
			'css'      => [
				[
					'property' => 'align-items',
					'selector' => '.x-toggle-switch_labels',
				],
			],
		];


    $this->controls['whichSwitcher'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Which content switcher?', 'bricks' ),
			'type' => 'select',
			'options' => [
			  'section' => esc_html__( 'All contents switchers inside this section', 'bricks' ),
			  'selector' => esc_html__( 'One particular content switcher', 'bricks' ),
			],
			//'small'		  => true,
			'clearable' => false,
			'placeholder' => esc_html__( 'All switchers inside this same section', 'bricks' ),
			//'default' => 'double',
		];

    $this->controls['switcherSelector'] = [
      'tab' => 'content',
      'label' => esc_html__( 'Content switcher selector', 'bricks' ),
      'type' => 'text',
      'placeholder' => esc_html__( '.my-content-switcher', 'bricks' ),
      'required' => ['whichSwitcher', '=', 'selector']
    ];






    /* labels */

    $this->controls['disableLabels'] = [
			'label' => esc_html__( 'Disable labels', 'bricks' ),
			'type'  => 'checkbox',
      'group' => 'labels',
      'required' => [
        ['type', '!=', 'multiple']
      ]
		];

    $this->controls['leftLabel'] = [
      'tab' => 'content',
      'label' => esc_html__( 'Left label text', 'bricks' ),
      'type' => 'text',
      'group' => 'labels',
      'placeholder' => 'Monthly',
      'required' => [
        ['type', '!=', 'multiple'],
        ['disableLabels', '!=', true]
      ]
    ];

    $this->controls['rightLabel'] = [
      'tab' => 'content',
      'label' => esc_html__( 'Right label text', 'bricks' ),
      'type' => 'text',
      'group' => 'labels',
      'placeholder' => 'Yearly',
      'required' => [
        ['type', '!=', 'multiple'],
        ['disableLabels', '!=', true]
      ]
    ];

    $this->controls['screenReaderText'] = [
      'tab' => 'content',
      'label' => esc_html__( 'Screen reader label', 'bricks' ),
      'type' => 'text',
      'group' => 'labels',
      'placeholder' => 'Switch pricing',
      'required' => ['type', '!=', 'multiple']
    ];

  


    /* styling*/

    $this->controls['BackgroundColor'] = [
      'tab'   => 'content',
      'group' => 'styling',
      'label' => esc_html__( 'Background color', 'bricks' ),
      'type'  => 'color',
      'css'   => [
        [
          'property' => 'background-color',
          'selector' => ''
        ],
      ],
    ];

    $this->controls['Border'] = [
      'tab'   => 'content',
      'label' => esc_html__( 'Border', 'bricks' ),
      'group' => 'styling',
      'type'  => 'border',
      'css'   => [
        [
          'property' => 'border',
          'selector' => '',
        ],
      ],
    ];

    $this->controls['BoxShadow'] = [
      'tab'   => 'content',
      'label' => esc_html__( 'Box shadow', 'bricks' ),
      'group' => 'styling',
      'type'  => 'box-shadow',
      'css'   => [
        [
          'property' => 'box-shadow',
          'selector' => ''
        ],
      ],
    ];

		$this->controls['Padding'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Padding', 'bricks' ),
			'type' => 'dimensions',
			'group' => 'styling',
			'css' => [
			  [
				'property' => 'padding',
				'selector' => '',
        ],
			],
			'placeholder' => [
			  'top' => '5px',
			  'right' => '5px',
			  'bottom' => '5px',
			  'left' => '5px',
			],
		];


    /* styling */

    $labels = '.x-toggle-switch_labels .x-toggle-switch_label';

    $this->controls['labelSep'] = [
      'tab'   => 'content',
      'group'  => 'styling',
      'type'  => 'separator',
      'label' => esc_html__( 'Labels', 'bricks' ),
    ];


    $this->controls['labelTypography'] = [
			'tab'    => 'content',
			'group'    => 'styling',
			'type'   => 'typography',
			'label'  => esc_html__( 'Typography', 'bricks' ),
			'css'    => [
				[
					'property' => 'font',
					'selector' => $labels,
				],
        [
					'property' => 'font',
					'selector' => ' > .x-toggle-switch_label',
				],
			],
		];

    
    $this->controls['labelsBackgroundColor'] = [
      'tab'   => 'content',
      'group' => 'styling',
      'label' => esc_html__( 'Background color', 'bricks' ),
      'type'  => 'color',
      'css'   => [
        [
          'property' => 'background-color',
          'selector' => $labels
        ],
        [
					'property' => 'background-color',
					'selector' => ' > .x-toggle-switch_label',
				],
      ],
    ];
    
    

    $this->controls['labelsBorder'] = [
      'tab'   => 'content',
      'label' => esc_html__( 'Border', 'bricks' ),
      'group' => 'styling',
      'type'  => 'border',
      'css'   => [
        [
          'property' => 'border',
          'selector' => $labels,
        ],
        [
          'property' => 'border',
          'selector' => '.x-toggle-switch_multiple-slider'
        ],
        [
          'property' => 'font',
          'selector' => ' > .x-toggle-switch_label',
        ],
      ],
    ];

    $this->controls['labelsBoxShadow'] = [
      'tab'   => 'content',
      'label' => esc_html__( 'Box shadow', 'bricks' ),
      'group' => 'styling',
      'type'  => 'box-shadow',
      'css'   => [
        [
          'property' => 'box-shadow',
          'selector' => $labels
        ],
        [
          'property' => 'box-shadow',
          'selector' => ' > .x-toggle-switch_label',
        ],
      ],
    ];

		$this->controls['labelPadding'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Padding', 'bricks' ),
			'type' => 'dimensions',
			'group' => 'styling',
			'css' => [
			  [
				'property' => 'padding',
				'selector' => $labels,
        ],
        [
					'property' => 'padding',
					'selector' => ' > .x-toggle-switch_label',
				],
			],
			'placeholder' => [
			  'top' => '1em',
			  'right' => '1em',
			  'bottom' => '1em',
			  'left' => '1em',
			],
		];

    $this->controls['labelMargin'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Margin', 'bricks' ),
			'type' => 'dimensions',
			'group' => 'styling',
			'css' => [
			  [
				'property' => 'margin',
				'selector' => $labels,
        ],
        [
					'property' => 'margin',
					'selector' => ' > .x-toggle-switch_label',
				],
			],
		];

    $this->controls['labelActiveSep'] = [
      'tab'   => 'content',
      'group'  => 'styling',
      'type'  => 'separator',
      'label' => esc_html__( 'Active label', 'bricks' ),
      'required' => ['type', '=', 'multiple']
    ];

    $this->controls['labelActiveBackgroundColor'] = [
      'tab'   => 'content',
      'group' => 'styling',
      'label' => esc_html__( 'Background color', 'bricks' ),
      'type'  => 'color',
      'css'   => [
        [
          'property' => 'background-color',
          'selector' => '.x-toggle-switch_multiple-slider'
        ],
      ],
      'required' => ['type', '=', 'multiple']
    ];

    $this->controls['labelActiveTypography'] = [
			'tab'    => 'content',
			'group'    => 'styling',
			'type'   => 'typography',
			'label'  => esc_html__( 'Typography', 'bricks' ),
			'css'    => [
				[
					'property' => 'font',
					'selector' => '.x-toggle-switch_label.x-toggle-switch_label-active',
				],
			],
      'required' => ['type', '=', 'multiple']
		];



    /* slider */

    $this->controls['sliderSep'] = [
      'tab'   => 'content',
      'group'  => 'switchSlider',
      'type'  => 'separator',
      'label' => esc_html__( 'Switch slider', 'bricks' ),
      'required' => ['type', '!=', 'multiple']
    ];

    $slider = '.x-toggle-switch_slider';

    $this->controls['sliderWidth'] = [
      'tab' => 'content',
      'label' => esc_html__( 'Slider width', 'bricks' ),
      'inline'      => true,
      'small'		  => true,
      'type' => 'number',
      'group' => 'switchSlider',
      'units'    => true,
      'css' => [
        [
          'selector' => '.x-toggle-switch_switch',  
          'property' => '--x-toggle-slider-width',
        ],
      ],
      //'inlineEditing' => true,
      'placeholder' => '60px',
      'required' => ['type', '!=', 'multiple']
    ];

    $this->controls['sliderHeight'] = [
      'tab' => 'content',
      'label' => esc_html__( 'Slider height', 'bricks' ),
      'inline'      => true,
      'small'		  => true,
      'type' => 'number',
      'group' => 'switchSlider',
      'units'    => true,
      'css' => [
        [
          'selector' => '.x-toggle-switch_slider',  
          'property' => 'height',
        ],
      ],
      //'inlineEditing' => true,
      'placeholder' => '30px',
      'required' => ['type', '!=', 'multiple']
    ];

    

    $this->controls['sliderBackgroundColor'] = [
      'tab'   => 'content',
      'group' => 'switchSlider',
      'label' => esc_html__( 'Background color', 'bricks' ),
      'type'  => 'color',
      'css'   => [
        [
          'property' => 'background-color',
          'selector' => $slider
        ],
      ],
      'required' => ['type', '!=', 'multiple']
    ];

    $this->controls['sliderActiveBackgroundColor'] = [
      'tab'   => 'content',
      'group' => 'switchSlider',
      'label' => esc_html__( 'Background color (active)', 'bricks' ),
      'type'  => 'color',
      'css'   => [
        [
          'property' => 'background-color',
          'selector' => '.x-toggle-switch_toggled ' . $slider
        ],
      ],
      'required' => ['type', '!=', 'multiple']
    ];

    $this->controls['sliderBorder'] = [
      'tab'   => 'content',
      'label' => esc_html__( 'Border', 'bricks' ),
      'group' => 'switchSlider',
      'type'  => 'border',
      'css'   => [
        [
          'property' => 'border',
          'selector' => $slider,
        ],
      ],
      'required' => ['type', '!=', 'multiple'],
    ];

    $this->controls['sliderBoxShadow'] = [
      'tab'   => 'content',
      'label' => esc_html__( 'Box shadow', 'bricks' ),
      'group' => 'switchSlider',
      'type'  => 'box-shadow',
      'css'   => [
        [
          'property' => 'box-shadow',
          'selector' => $slider
        ],
      ],
      'required' => ['type', '!=', 'multiple'],
    ];



    /* control */

    $sliderControl = '.x-toggle-switch_control';

    $this->controls['controlSep'] = [
      'tab'   => 'content',
      'group'  => 'switchSlider',
      'type'  => 'separator',
      'label' => esc_html__( 'Switch control', 'bricks' ),
      'required' => ['type', '!=', 'multiple'],
    ];


    $this->controls['controlSize'] = [
      'tab' => 'content',
      'group' => 'switchSlider',
      'label' => esc_html__( 'Control size', 'bricks' ),
      'inline'      => true,
      'small'		  => true,
      'type' => 'number',
      'units'    => true,
      'css' => [
        [
          'selector' => '.x-toggle-switch_switch',  
          'property' => '--x-toggle-control-size',
        ],
      ],
      'required' => ['type', '!=', 'multiple'],
      'placeholder' => '26px',
      ];

      $this->controls['controlMargin'] = [
        'tab' => 'content',
        'group' => 'switchSlider',
        'label' => esc_html__( 'Distance from edge of slider', 'bricks' ),
        'inline'      => true,
        'small'		  => true,
        'type' => 'number',
        'units'    => true,
        'css' => [
          [
            'selector' => '.x-toggle-switch_switch',  
            'property' => '--x-toggle-slider-padding',
          ],
        ],
        'required' => ['type', '!=', 'multiple'],
        'placeholder' => '4px',
        ];

      


    $this->controls['sliderControlBackgroundColor'] = [
      'tab'   => 'content',
      'group' => 'switchSlider',
      'label' => esc_html__( 'Background color', 'bricks' ),
      'type'  => 'color',
      'css'   => [
        [
          'property' => 'background-color',
          'selector' => $sliderControl
        ],
      ],
      'required' => ['type', '!=', 'multiple']
    ];

    $this->controls['sliderControlActiveBackgroundColor'] = [
      'tab'   => 'content',
      'group' => 'switchSlider',
      'label' => esc_html__( 'Background color (active)', 'bricks' ),
      'type'  => 'color',
      'css'   => [
        [
          'property' => 'background-color',
          'selector' =>  '.x-toggle-switch_toggled ' . $sliderControl
        ],
      ],
      'required' => ['type', '!=', 'multiple']
    ];

    $this->controls['sliderControlBorder'] = [
      'tab'   => 'content',
      'label' => esc_html__( 'Border', 'bricks' ),
      'group' => 'switchSlider',
      'type'  => 'border',
      'css'   => [
        [
          'property' => 'border',
          'selector' => $sliderControl,
        ],
      ],
      'required' => ['type', '!=', 'multiple']
    ];

    $this->controls['sliderControlBoxShadow'] = [
      'tab'   => 'content',
      'label' => esc_html__( 'Box shadow', 'bricks' ),
      'group' => 'switchSlider',
      'type'  => 'box-shadow',
      'css'   => [
        [
          'property' => 'box-shadow',
          'selector' => $sliderControl
        ],
      ],
      'required' => ['type', '!=', 'multiple']
    ];



  }

  // Methods: Frontend-specific
  public function enqueue_scripts() {
    wp_enqueue_script( 'x-toggle-switch', BRICKSEXTRAS_URL . 'components/assets/js/' . \BricksExtras\Helpers::maybeMinifyScripts('toggleswitch') . '.js', '', '1.0.6', true );
    if (! \BricksExtras\Helpers::elementCSSAdded($this->name) ) {
      wp_enqueue_style( 'x-toggle-switch', BRICKSEXTRAS_URL . 'components/assets/css/toggleswitch.css', [], '' );
    }
  }
  
  public function render() {

    $settings = $this->settings;

    $type = isset( $this->settings['type'] ) ? $this->settings['type'] : 'double';
    $leftLabel = isset( $this->settings['leftLabel'] ) ? wp_kses_post( __( $this->settings['leftLabel'] ) ) : esc_attr__( 'Monthly' );
    $rightLabel = isset( $this->settings['rightLabel'] ) ? wp_kses_post( __( $this->settings['rightLabel'] ) ) : esc_attr__( 'Yearly' );
    $screenReaderText = isset( $this->settings['screenReaderText'] ) ? esc_attr__( $this->settings['screenReaderText'] ) : esc_attr__( 'Switch pricing' );
    $ariaLabel = isset( $this->settings['ariaLabel'] ) ? esc_attr__( $this->settings['ariaLabel'] ) : esc_attr__( 'Switch content' );
    $disableLabels = isset( $this->settings['disableLabels'] );

    $whichSwitcher = isset( $this->settings['whichSwitcher'] ) ? esc_attr($this->settings['whichSwitcher']) : 'section';
    $switcherSelector = isset( $this->settings['switcherSelector'] ) ? esc_attr($this->settings['switcherSelector']) : '.my-content-switcher';

    
    $config = [
      'type' =>  $type,
      'contentSwitcher' => 'section' === $whichSwitcher ? 'section' : $switcherSelector
    ];

    $loopIndex = false;
    $identifier = $this->id;

      if ( method_exists('\Bricks\Query','is_any_looping') ) {

        $query_id = \Bricks\Query::is_any_looping();
    
        if ( $query_id ) {

          $config += [ 'isLooping' => \Bricks\Query::get_query_element_id( $query_id ) ];

          
          if ( BricksExtras\Helpers::get_bricks_looping_parent_query_id_by_level(2) ) {
            $loopIndex = \Bricks\Query::get_query_for_element_id( \Bricks\Query::get_query_element_id( BricksExtras\Helpers::get_bricks_looping_parent_query_id_by_level(2) ) )->loop_index . '_' . \Bricks\Query::get_query_for_element_id( \Bricks\Query::get_query_element_id( BricksExtras\Helpers::get_bricks_looping_parent_query_id_by_level(1) ) )->loop_index . '_' . \Bricks\Query::get_loop_index();
          } else {
            if ( BricksExtras\Helpers::get_bricks_looping_parent_query_id_by_level(1) ) {
              $loopIndex = \Bricks\Query::get_query_for_element_id( \Bricks\Query::get_query_element_id( BricksExtras\Helpers::get_bricks_looping_parent_query_id_by_level(1) ) )->loop_index . '_' . \Bricks\Query::get_loop_index();
            } else {
              $loopIndex = \Bricks\Query::get_loop_index();
            }
          }			
    
          $identifier = $this->id . '_' . $loopIndex;
          
        }
    
      } 

    $this->set_attribute( '_root', 'class', 'x-toggle-switch' );
    $this->set_attribute( '_root', 'data-x-switch', wp_json_encode( $config ) );

    $this->set_attribute( 'x-toggle-switch_labels', 'class', 'x-toggle-switch_labels' );

    echo "<div {$this->render_attributes( '_root' )}>";

    if ('double' === $type ) {

     

      if (!$disableLabels) {
        echo '<span class="x-toggle-switch_label">' . $leftLabel . '</span>';
      }

      echo '<div class="x-toggle-switch_switch">
         <span class="x-toggle-switch_slider">
                <span class="x-toggle-switch_control"></span>
              </span>

          <fieldset class="x-screen-reader screen-reader-text">
            <legend class="x-screen-reader">' . esc_attr__( $screenReaderText ) . '</legend>
            <input class="x-toggle-switch_left-input" type="radio" name="x-toggle-switch_' . esc_attr( $identifier ) . '" id="x-toggle-switch_left_' . esc_attr( $identifier ) . '">
            <label class="x-screen-reader" for="x-toggle-switch_left_' .  esc_attr( $identifier ) . '">' .  wp_strip_all_tags( $leftLabel ). '</label>
            <input class="x-toggle-switch_right-input" type="radio" name="x-toggle-switch_' . esc_attr( $identifier ) . '" id="x-toggle-switch_right_' . esc_attr( $identifier ) . '">
            <label class="x-screen-reader" for="x-toggle-switch_right_' . esc_attr( $identifier ) . '">' .  wp_strip_all_tags( $rightLabel ) . '</label>
          </fieldset>
            </div>
            ';

      if (!$disableLabels) {
        echo '<span class="x-toggle-switch_label">' . $rightLabel . '</span>';
      }

    } else {

      if ( empty( $this->settings['labels'] ) ) {
        return $this->render_element_placeholder(
          [
            'title' => esc_html__( 'No labels defined.', 'bricks' ),
          ]
        );
      }

      $this->set_attribute( "x-toggle-switch_labels", 'role', 'tablist' );
      $this->set_attribute( "x-toggle-switch_labels", 'aria-label', $ariaLabel );
        
      echo "<div {$this->render_attributes( 'x-toggle-switch_labels' )}>";

      // Query Loop
			if ( isset( $this->settings['hasLoop'] ) ) {

        $query = new \Bricks\Query( [
					'id'       => $this->id,
					'settings' => $settings,
				] );

				$labelsQuery = $this->settings['labelsQuery'];

				echo $query->render( [ $this, 'render_repeater_item' ], compact( 'labelsQuery' ) );
				$query->destroy();
				unset( $query );

			}
      
      else {

        $this->set_attribute( "x-toggle-switch_label-0", 'class', 'x-toggle-switch_label-active' );
        $this->set_attribute( "x-toggle-switch_label-0", 'aria-selected', 'true' );
        $this->set_attribute( "x-toggle-switch_label-0", 'tabindex', '0' );

          foreach ( $this->settings['labels'] as $index => $label ) {

            $this->set_attribute( "x-toggle-switch_label-$index", 'class', 'x-toggle-switch_label' );
            $this->set_attribute( "x-toggle-switch_label-$index", 'role', 'tab' );
            $this->set_attribute( "x-toggle-switch_label-$index", 'id', 'x-toggle-switch_label_' . $this->id . '_' . $index );

            if (0 !== $index) {
              $this->set_attribute( "x-toggle-switch_label-$index", 'tabindex', '-1' );
              $this->set_attribute( "x-toggle-switch_label-$index", 'aria-selected', 'false' );
            }
            
            if ( ! empty( $label['title'] ) ) {
      
              echo "<button {$this->render_attributes( "x-toggle-switch_label-$index" )}><span>" . wp_kses_post( __( $label['title'] ) ) . "</span></button>";

            }

        }

     }

      echo '<div class="x-toggle-switch_multiple-slider"></div></div>';

    }

    echo '</div>'; 

  }

  public function render_repeater_item( $labelsQuery ) {

    $settings = $this->settings;
    $index    = $this->loop_index;

    $this->set_attribute( "x-toggle-switch_label-0", 'class', 'x-toggle-switch_label-active' );
    $this->set_attribute( "x-toggle-switch_label-0", 'aria-selected', 'true' );
    $this->set_attribute( "x-toggle-switch_label-0", 'tabindex', '0' );

      $this->set_attribute( "x-toggle-switch_label-$index", 'class', 'x-toggle-switch_label' );
      $this->set_attribute( "x-toggle-switch_label-$index", 'role', 'tab' );
      $this->set_attribute( "x-toggle-switch_label-$index", 'id', 'x-toggle-switch_label_' . $this->id . '_' . $index );

      if (0 !== $index) {
        $this->set_attribute( "x-toggle-switch_label-$index", 'tabindex', '-1' );
        $this->set_attribute( "x-toggle-switch_label-$index", 'aria-selected', 'false' );
      }
  
    // Render
    ob_start();
      
      if ( ! empty( $labelsQuery ) ) {

        echo "<button {$this->render_attributes( "x-toggle-switch_label-$index" )}><span>" . wp_kses_post( __( $labelsQuery ) ) . " </span></button>";

      }
    
    $html = ob_get_clean();
  
    $this->loop_index++;
  
    return $html;
  
    }

}