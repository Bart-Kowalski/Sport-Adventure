<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class X_Popover extends \Bricks\Element {

  // Element properties
  public $category     = 'extras';
    public $name         = 'xpopover';
    public $icon         = 'ti-comment-alt';
    public $css_selector = '';
    public $scripts      = ['xPopover'];
    public $nestable = true;

  
  public function get_label() {
	return esc_html__( 'Popover / Tooltips', 'extras' );
  }
  public function set_control_groups() {

    $this->control_groups['button'] = [
      'title' => esc_html__( 'Button', 'bricks' ),
      'tab'   => 'content',
  ];

    $this->control_groups['content'] = [
        'title' => esc_html__( 'Popover content', 'bricks' ),
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

    $this->control_groups['advanced'] = [
      'title' => esc_html__( 'Tooltips', 'bricks' ),
      'tab'   => 'content',
  ];

  }

  // Methods: Frontend-specific
  public function enqueue_scripts() {
    wp_enqueue_script( 'x-popper', BRICKSEXTRAS_URL . 'components/assets/js/popper.js', '', '1.0.1', true );
	  wp_enqueue_script( 'x-popover', BRICKSEXTRAS_URL . 'components/assets/js/' . \BricksExtras\Helpers::maybeMinifyScripts('popover') . '.js', ['x-popper'], '1.1.1', true );

    wp_localize_script(
			'x-popover',
			'xTippy',
			[
				'Instances' => [],
			]
		);

    if (! \BricksExtras\Helpers::elementCSSAdded($this->name) ) {
			wp_enqueue_style( 'x-popover', BRICKSEXTRAS_URL . 'components/assets/css/popover.css', [], '' );
		}
  }

  public function set_controls() {

    $this->controls['ariaLabel'] = [
      'tab' => 'content',
      'label' => esc_html__( 'Aria label', 'bricks' ),
      'group' => 'button',
      'type' => 'text',
      'inline' => true,
      'default' => 'More information',
      'hasDynamicData' => false
    ];

    $this->controls['buttonText'] = [
      'tab' => 'content',
      'label' => esc_html__( 'Button text', 'bricks' ),
      'group' => 'button',
      'type' => 'text',
      'inline' => true,
      'hasDynamicData' => false
    ];

    $this->controls['icon'] = [
        'tab'     => 'content',
        'label'   => esc_html__( 'Icon', 'bricks' ),
        'group' => 'button',
        'type'    => 'icon',
        'css'      => [
          [
            'selector' => '.x-popover_button-icon svg',
          ],
        ],
        'default' => [
            'icon'    => 'fas fa-circle-info',
            'library' => 'fontawesomeSolid',
        ],
    ];

    $this->controls['iconTypography'] = [
      'tab'      => 'content',
      'label'    => esc_html__( 'Icon typography', 'bricks' ),
      'type'     => 'typography',
      'group' => 'button',
      'units'    => true,
      'css'      => [
          [
          'property' => 'font',
          'selector' => '.x-popover_button-icon'
          ],
      ],
  ];

    $button = '.x-popover_button';

    $this->controls['buttonTypography'] = [
      'tab'    => 'content',
      'group'  => 'button',
      'type'   => 'typography',
      'label'  => esc_html__( 'Typography', 'extras' ),
      'css'    => [
        [
          'property' => 'font',
          'selector' => $button,
        ],
      ],
    ];

    $this->controls['buttonBackgroundColor'] = [
      'tab'    => 'content',
      'group'  => 'button',
      'type'   => 'color',
      'label'  => esc_html__( 'Background', 'extras' ),
      'css'    => [
        [
          'property' => 'background-color',
          'selector' => $button,
        ],
      ],
    ];

    $this->controls['buttonBorder'] = [
      'tab'    => 'content',
      'group'  => 'button',
      'type'   => 'border',
      'label'  => esc_html__( 'Border', 'extras' ),
      'css'    => [
        [
          'property' => 'border',
          'selector' => $button,
        ],
      ],
    ];

    $this->controls['buttonBoxShadow'] = [
      'tab'    => 'content',
      'group'  => 'button',
      'label'  => esc_html__( 'Box Shadow', 'extras' ),
      'type'   => 'box-shadow',
      'css'    => [
        [
          'property' => 'box-shadow',
          'selector' => $button,
        ],
      ],
    ];

    $this->controls['buttonPaddingSep'] = [
      'tab'   => 'content',
      'group'  => 'button',
      'type'  => 'separator',
    ];

    $this->controls['buttonPadding'] = [
      'tab'   => 'content',
      'group' => 'button',
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
          'selector' => $button,
        ],
      ],
    ];

    $this->controls['buttonFlexSep'] = [
      'tab'   => 'content',
      'group'  => 'button',
      'type'  => 'separator',
    ];

    $this->controls['buttonDirection'] = [
			'tab'      => 'content',
			'label'    => esc_html__( 'Direction', 'bricks' ),
			'group'		  => 'button',
			'tooltip'  => [
				'content'  => 'flex-direction',
				'position' => 'top-left',
			],
			'type'     => 'direction',
			'css'      => [
				[
					'property' => 'flex-direction',
					'selector' => $button,
				],
			],
			'inline'   => true,
			'rerender' => true,
			//'required' => [ '_display', '=', 'flex' ],
		];

		$this->controls['buttonJustifyContent'] = [
			'tab'      => 'content',
			'label'    => esc_html__( 'Align main axis', 'bricks' ),
			'group'		  => 'button',
			'tooltip'  => [
				'content'  => 'justify-content',
				'position' => 'top-left',
			],
			'type'     => 'justify-content',
			'css'      => [
				[
					'property' => 'justify-content',
					'selector' => $button,
				],
			],
			//'required' => [ '_display', '=', 'flex' ],
		];

		$this->controls['buttonAlignItems'] = [
			'tab'      => 'content',
			'label'    => esc_html__( 'Align cross axis', 'bricks' ),
			'group'		  => 'button',
			'tooltip'  => [
				'content'  => 'align-items',
				'position' => 'top-left',
			],
			'type'     => 'align-items',
			'css'      => [
				[
					'property' => 'align-items',
					'selector' => $button,
				],
			],
			//'required' => [ '_display', '=', 'flex' ],
		];

		$this->controls['_columnGap'] = [
			'tab'      => 'content',
			'label'    => esc_html__( 'Column gap', 'bricks' ),
			'group'		  => 'button',
			'type'     => 'number',
			'units'    => true,
			'css'      => [
				[
					'property' => 'column-gap',
					'selector' => $button,
				],
			],
			'info'     => sprintf( __( 'Current browser support: %s (no IE). Use margins for max. browser support.', 'bricks' ), '89%' ),
			//'required' => [ '_display', '=', 'flex' ],
		];

		$this->controls['buttonRowGap'] = [
			'tab'      => 'content',
			'label'    => esc_html__( 'Row gap', 'bricks' ),
			'group'		  => 'button',
			'type'     => 'number',
			'units'    => true,
			'css'      => [
				[
					'property' => 'row-gap',
					'selector' => $button,
				],
			],
			'info'     => sprintf( __( 'Current browser support: %s (no IE). Use margins for max. browser support.', 'bricks' ), '89%' ),
			//'required' => [ '_display', '=', 'flex' ],
		];


    /*  popover style */

    $popover = '.x-popover_content .tippy-content';

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
           'property' => '--x-popover-background',
           'selector' => '',
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

        $this->controls['delay'] = [
          'tab'   => 'content',
          'group' => 'behaviour',
          'label' => esc_html__( 'Delay (ms)', 'extras' ),
          'type'  => 'number',
          'placeholder' => '0',
          'units' => false,
          'small' => true
        ];

      
      $this->controls['offsetSkidding'] = [
        'tab'   => 'content',
        'group' => 'behaviour',
        'label' => esc_html__( 'Offset skidding (px)', 'extras' ),
        'info' => esc_html__( 'Distance along the side of the button', 'extras' ),
        'type'  => 'number',
        'placeholder' => '0',
        'units' => false,
        'small' => true
      ];

      $this->controls['offsetDistance'] = [
        'tab'   => 'content',
        'group' => 'behaviour',
        'label' => esc_html__( 'Offset distance (px)', 'extras' ),
        'info' => esc_html__( 'Distance away from the button', 'extras' ),
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
        'placeholder' => '50',
        'units' => false,
        'small' => true
      ];

      $this->controls['appendBody'] = [
        'tab'   => 'content',
        'group' => 'behaviour',
        'label' => esc_html__( 'Append to body', 'extras' ),
        'type'  => 'checkbox',
      ];




      /* advanced */

      $this->controls['advancedSep'] = [
        'tab'   => 'content',
        'group'  => 'advanced',
        'type'  => 'separator',
        'description' => esc_html__( 'Popover can be used to add tooltips to any elements. The tooltip content will either be the content you nest inside this popover, or you can add text directly to the elements in style > tooltip settings', 'extras' ),
      ];

      $this->controls['maybeTooltip'] = [
        'tab'   => 'content',
        'inline' => true,
        'small' => true,
        'group'  => 'advanced',
        'label' => esc_html__( 'Use as tooltip', 'bricks' ),
        'type'  => 'checkbox',
      ];

      $this->controls['elementSelector'] = [
        'tab'         => 'content',
        'type'        => 'text',
        'group'  => 'advanced',
        'label' => esc_html__( 'Element selector', 'bricks' ),
        'placeholder' => esc_attr( '.your-class-here', 'bricks' ),
        'hasDynamicData' => false,
        'info' => esc_html__( 'The selector of the element(s) the user should interact with to reveal the tooltip', 'bricks' ),
        'inline'      => true,
        'required' => [ 'maybeTooltip', '=', true ],
      ];

      $this->controls['maybeDynamicContent'] = [
        'tab'   => 'content',
        'inline' => true,
        'small' => true,
        'group'  => 'advanced',
        'info' => esc_html__( "If wishing to dynamically add content from each elements tooltip settings. Disable if nesting elements to display instead.", 'bricks' ),
        'label' => esc_html__( 'Dynamic tooltip text', 'bricks' ),
        'type'  => 'checkbox',
        'required' => [ 'maybeTooltip', '=', true ],
      ];

      $this->controls['multipleTriggers'] = [
        'tab'   => 'content',
        'inline' => true,
        'small' => true,
        'group'  => 'advanced',
        'label' => esc_html__( 'Multiple triggers for same tooltip', 'bricks' ),
        'type'  => 'checkbox',
        'required' => [ 'maybeTooltip', '=', true ],
      ];

      $this->controls['followCursor'] = [
        'tab'   => 'content',
        'group' => 'advanced',
        'label' => esc_html__( 'Follow cursor', 'extras' ),
        'type' => 'select',
        'options' => [
          'false' => 'False', 
          'true' => 'Follow both X and Y axes',
          'horizontal' => 'Follow on X axis',
          'vertical' => 'Follow on Y axis',
          'initial' => 'Follow until it shows',
        ],
        'placeholder' => 'False',
        'inline' => true,
        'info' => esc_html__( 'Only for if not using multiple triggers', 'bricks' ),
        'required' => [ 
          ['maybeTooltip', '=', true],
          ['multipleTriggers', '=', false]  
        ],
      ];

      $this->controls['hidebutton'] = [
        'tab'   => 'content',
        'inline' => true,
        'small' => true,
        'group'  => 'advanced',
        'label' => esc_html__( 'Hide button in builder', 'bricks' ),
        //'info' => esc_html__( "The button isn't visible on the front if the popover is being used as a tooltip.", 'bricks' ),
        'type'  => 'checkbox',
        'required' => [ 'maybeTooltip', '=', true ],
      ];


  }

  
  
  public function render() {

    $icon = empty( $this->settings['icon'] ) ? false : self::render_icon( $this->settings['icon'] );
    $ariaLabel = isset( $this->settings['ariaLabel'] ) ? esc_attr__( $this->settings['ariaLabel'] ) : false;
    $buttonText = isset( $this->settings['buttonText'] ) ? esc_html__( $this->settings['buttonText'] ) : false;

    $config = [
      'placement' => isset( $this->settings['placement'] ) ? $this->settings['placement'] : 'auto',
      'offsetSkidding' => isset( $this->settings['offsetSkidding'] ) ? intval( $this->settings['offsetSkidding'] ) : 0,
      'offsetDistance' => isset( $this->settings['offsetDistance'] ) ? intval( $this->settings['offsetDistance'] ) : 10,
      'moveTransition' => isset( $this->settings['moveTransition'] ) ? intval( $this->settings['moveTransition'] ) : 50,
      'interaction' => isset( $this->settings['interaction'] ) ? $this->settings['interaction'] : 'click',
      'delay' => isset( $this->settings['delay'] ) ? intval( $this->settings['delay'] ) : 0,
      'followCursor' => isset( $this->settings['followCursor'] ) ? $this->settings['followCursor'] : 'false',
      'appendBody' => isset( $this->settings['appendBody'] )
    ];

    if ( isset( $this->settings['maybeTooltip'] ) ) {
      $config += [ 
        'elementSelector' => isset( $this->settings['elementSelector'] ) ? esc_attr( $this->settings['elementSelector'] ) : '.your-class-here',
        'dynamicContent' => isset( $this->settings['maybeDynamicContent'] ),
        'multipleTriggers' => isset( $this->settings['multipleTriggers'] )
      ]; 
    }

    if ( isset( $this->settings['hidebutton'] ) && isset( $this->settings['maybeTooltip'] ) ) {
      $config += [ 
        'hide' => true
      ];
    }

     /* get element ID of the most deep looped element */
     if ( method_exists('\Bricks\Query','is_any_looping') ) {

      $query_id = \Bricks\Query::is_any_looping();

      if ( $query_id ) {
        $config += [ 'isLooping' => \Bricks\Query::get_query_element_id( $query_id ) ];
        $config += [ 'loopIndex' => \Bricks\Query::get_loop_index()  ];

        if ( BricksExtras\Helpers::get_bricks_looping_parent_query_id_by_level(2) ) {
          $loopIndex = \Bricks\Query::get_query_for_element_id( \Bricks\Query::get_query_element_id( BricksExtras\Helpers::get_bricks_looping_parent_query_id_by_level(2) ) )->loop_index . '_' . \Bricks\Query::get_query_for_element_id( \Bricks\Query::get_query_element_id( BricksExtras\Helpers::get_bricks_looping_parent_query_id_by_level(1) ) )->loop_index . '_' . \Bricks\Query::get_loop_index();
        } else {
          if ( BricksExtras\Helpers::get_bricks_looping_parent_query_id_by_level(1) ) {
            $loopIndex = \Bricks\Query::get_query_for_element_id( \Bricks\Query::get_query_element_id( BricksExtras\Helpers::get_bricks_looping_parent_query_id_by_level(1) ) )->loop_index . '_' . \Bricks\Query::get_loop_index();
          } else {
            $loopIndex = \Bricks\Query::get_loop_index();
          }
        }			

        $uniqueID = $this->id . '_' . $loopIndex;
        
      } else {
        $uniqueID = $this->id;
      }

    } 

    $this->set_attribute( "_root", 'class', [ 'x-popover' ] );
    $this->set_attribute( "_root", 'data-x-popover', wp_json_encode($config) );    

    $this->set_attribute( "x-popover_button", 'class', [ 'x-popover_button' ] );
    
    
    $this->set_attribute( "x-popover_button-icon", 'class', [ 'x-popover_button-icon' ] );

    if ( $ariaLabel && !$buttonText ) {
      $this->set_attribute( "x-popover_button", 'aria-label', $ariaLabel );
    }

    $this->set_attribute( "x-popover_content", 'class', [ 'x-popover_content' ] );
    $this->set_attribute( "x-popover_content", 'role', [ 'tooltip' ] );
    

    $this->set_attribute( "tippy-content", 'class', [ 'tippy-content' ] );
    $this->set_attribute( "tippy-content", 'data-state', [ 'visible' ] );

    $this->set_attribute( "tippy-root", 'data-tippy-root', '' );

    $this->set_attribute( "tippy-box", 'class', 'tippy-box' );
    $this->set_attribute( "tippy-box", 'data-state', 'visible' );
    $this->set_attribute( "tippy-box", 'tabindex', '1' );
    $this->set_attribute( "tippy-box", 'data-theme', 'extras' );
    $this->set_attribute( "tippy-box", 'data-animation', 'extras' );
    
    $this->set_attribute( "x-popover_content-inner", 'class', [ 'x-popover_content-inner' ] );
    

    $this->set_attribute( "x-popover_button", 'aria-describedby', 'x-popover_content_' . $uniqueID );
    $this->set_attribute( "x-popover_content", 'id', 'x-popover_content_' . $uniqueID );
    $this->set_attribute( "_root", 'data-x-id', $uniqueID );

    echo "<div {$this->render_attributes( '_root' )}>";

    /* only output button on front end if not being used as tooltip */
    if ( ( isset( $_SERVER['HTTP_REFERER'] ) && strstr( $_SERVER['HTTP_REFERER'], 'brickspreview' ) || !isset( $this->settings['maybeTooltip'] ) ) ) {

      echo "<button {$this->render_attributes( 'x-popover_button' )}>";
      echo "<span {$this->render_attributes( 'x-popover_button-icon' )}> " . $icon . "  </span>";

      if ( $buttonText ) {
        echo "<span {$this->render_attributes( 'x-popover_button-text' )}> " . $buttonText . "  </span>";
      }
      echo "</button>";

    }

    echo "<div {$this->render_attributes( 'x-popover_content' )}>";

    if ( BricksExtras\Helpers::maybePreview() ) {
      echo "<div {$this->render_attributes( 'tippy-root' )}>";
      echo "<div {$this->render_attributes( 'tippy-box' )}>";
      echo "<div {$this->render_attributes( 'tippy-content' )}>";
    }
    echo "<div {$this->render_attributes( 'x-popover_content-inner' )}>";
    
    if ( method_exists('\Bricks\Frontend','render_children') ) {
        echo \Bricks\Frontend::render_children( $this );
    } 

    echo "</div>";
    if ( BricksExtras\Helpers::maybePreview() ) {
      echo "</div>";
      echo "</div>";
      echo "</div>";
    }
    echo "</div>";

    echo "</div>";
    
  }


  public function get_nestable_item() {
        return [
            'name'     => 'text',
            'label'    => esc_html__( 'Popover content', 'bricks' ),
            'settings' => [
                'text' => esc_html__( 'Popover content', 'bricks' ),
            ],
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

}