<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class X_Back_To_Top extends \Bricks\Element {

  // Element properties
  public $category     = 'extras';
	public $name         = 'xbacktotop';
	public $icon         = 'ti-arrow-circle-up';
	public $css_selector = '';
	public $scripts      = ['xBackToTop'];
  public $nestable = true;

  
  public function get_label() {
	return esc_html__( 'Back to Top', 'extras' );
  }

  public function set_control_groups() {

    $this->control_groups['progress'] = [
      'title' => esc_html__( 'Progress', 'extras' ),
      'tab' => 'content',
      'required' => ['type', '=', ['progress']],
  ];

    $this->control_groups['positioning'] = [
        'title' => esc_html__( 'Position', 'extras' ),
        'tab' => 'content',
    ];

    $this->control_groups['inner_layout'] = [
      'title' => esc_html__( 'Inner layout', 'extras' ),
      'tab' => 'content',
     // 'required' => ['content', '=', ['nest']],
    ];

    $this->control_groups['scrolling'] = [
      'title' => esc_html__( 'Scroll behaviour', 'extras' ),
      'tab' => 'content',
    ];

    $this->control_groups['animation'] = [
      'title' => esc_html__( 'Reveal animation', 'extras' ),
      'tab' => 'content',
    ];

  }

  public function set_controls() {


    $this->controls['type'] = [
        'tab'   => 'content',
        'inline' => true,
        'label' => esc_html__( 'Button type', 'bricks' ),
        'type'  => 'select',
        'options' => [
            'progress' => esc_html__( 'Progress circle', 'bricks' ),
            'regular' => esc_html__( 'Regular', 'bricks' ),
        ],
        'clearable' => false,
        'default' => 'progress'
    ];

    $this->controls['content'] = [
        'tab'   => 'content',
        'inline' => true,
        'label' => esc_html__( 'Button content', 'bricks' ),
        'type'  => 'select',
        'options' => [
            'icon' => esc_html__( 'Icon / Text', 'bricks' ),
            'nest' => esc_html__( 'Nest elements', 'bricks' ),
        ],
        'clearable' => false,
        'default' => 'icon',
        'placeholder' => esc_html__( 'Icon / Text', 'bricks' ),
    ];

    $this->controls['buttonText'] = [
      'tab' => 'content',
      'label' => esc_html__( 'Button text', 'bricks' ),
      'type' => 'text',
      'inline' => true,
      'placeholder' => esc_html__( '', 'bricks' ),
      'hasDynamicData' => false,
      'required' => ['content', '=', 'icon'],
   ];

   $this->controls['ariaLabel'] = [
    'tab' => 'content',
    'label' => esc_html__( 'Aria label', 'bricks' ),
    'type' => 'text',
    'inline' => true,
    'default' => esc_html__( 'Back to top' ),
    'hasDynamicData' => false
  ];

    $this->controls['typeSep'] = [
      'tab'   => 'content',
      'type'  => 'seperator',
    ];

    $this->controls['icon'] = [
        'tab'     => 'content',
        'label'   => esc_html__( 'Icon', 'bricks' ),
        'type'    => 'icon',
        'css'     => [
            [
                'selector' => '.x-back-to-top_icon > *', 
            ],
        ],
        'required' => ['content', '=', 'icon'],
        'default' => [
            'library' => 'fontawesomeSolid',
            'icon'    => 'fas fa-chevron-up',
        ],
    ];

    $this->controls['iconTypography'] = [
      'tab'    => 'content',
      'type'   => 'typography',
      'label'  => esc_html__( 'Icon typography', 'bricks' ),
      'css'    => [
        [
          'property' => 'font',
          'selector' => '.x-back-to-top_icon > *',
        ],
      ],
      'required' => ['content', '=', 'icon']
    ];

    


    $this->controls['width'] = [
        'tab' => 'content',
        'label' => esc_html__( 'Button width', 'extras' ),
        'inline'      => true,
        'small'		  => true,
        'type' => 'number',
        'units'    => true,
        'placeholder' => '60px',
        'css' => [
          [
            'selector' => '',  
            'property' => 'width',
          ],
        ],
      ];
      

      $this->controls['height'] = [
        'tab' => 'content',
        'label' => esc_html__( 'Button height', 'extras' ),
        'inline'      => true,
        'small'		  => true,
        'type' => 'number',
        'units'    => true,
        'placeholder' => '60px',
        'required'    => [ 'type', '=', 'regular' ],
        'css' => [
          [
            'selector' => '',  
            'property' => 'height',
          ],
        ],
      ];

      $this->controls['buttonTypography'] = [
        'tab'    => 'content',
        'type'   => 'typography',
        'label'  => esc_html__( 'Button typography', 'bricks' ),
        'css'    => [
          [
            'property' => 'font',
            'selector' => '',
          ],
        ],
      ];

      $this->controls['strokeWidth'] = [
        'group' => 'progress',
        'tab' => 'content',
        'label' => esc_html__( 'Line width', 'extras' ),
        'inline'      => true,
        'small'		  => true,
        'type' => 'number',
        'units'    => true,
        'placeholder' => '4px',
        'css' => [
          [
            'selector' => '.x-back-to-top_progress',  
            'property' => '--x-backtotop-stroke-width',
          ],
        ],
      ];

      $this->controls['backgroundStrokeColor'] = [
        'group' => 'progress',
        'tab' => 'content',
        'label' => esc_html__( 'Circle color', 'extras' ),
        'inline'      => true,
        'small'		  => true,
        'type' => 'color',
        'css' => [
          [
            'selector' => '.x-back-to-top_progress-background',  
            'property' => 'stroke',
          ],
        ],
      ];

      $this->controls['strokeColor'] = [
        'group' => 'progress',
        'tab' => 'content',
        'label' => esc_html__( 'Progress line color', 'extras' ),
        'inline'      => true,
        'small'		  => true,
        'type' => 'color',
        'css' => [
          [
            'selector' => '.x-back-to-top_progress-line',  
            'property' => 'stroke',
          ],
        ],
      ];

      $this->controls['lineCap'] = [
        'group' => 'progress',
        'tab'   => 'content',
        'label' => esc_html__( 'Line cap', 'bricks' ),
        'inline'      => true,
        'type'  => 'select',
        'css' => [
            [
              'selector' => '.x-back-to-top_progress-line',  
              'property' => 'stroke-linecap',
            ],
          ],
        'options' => [
            'round' => esc_html__( 'Round', 'bricks' ),
            'square' => esc_html__( 'Square', 'bricks' ),
        ],
        'placeholder' => esc_html__( 'Round', 'bricks' ),
    ];

      

     


     /* positioning */

     $this->controls['position'] = [
			'tab'     => 'style',
			'group'   => 'positioning',
			'label'   => esc_html__( 'Position', 'bricks' ),
			'type'    => 'select',
			'options' => [
        'fixed' => 'Fixed',
        'absolute' => 'Absolute',
        'sticky' => 'Sticky',
        'static' => 'Static',
        'relative' => 'Relative'
      ],
			'css'     => [
				[
					'property' => 'position',
					'selector' => '',
				],
			],
      //'default' => 'fixed',
			'inline'  => true,
		];

		$this->controls['top'] = [
			'tab'   => 'style',
			'group'   => 'positioning',
			'label' => esc_html__( 'Top', 'bricks' ),
			'type'  => 'number',
			'units' => true,
			'css'   => [
				[
					'property' => 'top',
					'selector' => '',
				],
			],
		];

		$this->controls['right'] = [
			'tab'   => 'style',
			'group'   => 'positioning',
			'label' => esc_html__( 'Right', 'bricks' ),
			'type'  => 'number',
			'units' => true,
			'css'   => [
				[
					'property' => 'right',
					'selector' => '',
				],
			],
      'default' => '40px',
		];

		$this->controls['bottom'] = [
			'tab'   => 'style',
			'group'   => 'positioning',
			'label' => esc_html__( 'Bottom', 'bricks' ),
			'type'  => 'number',
			'units' => true,
			'css'   => [
				[
					'property' => 'bottom',
					'selector' => '',
				],
			],
      'default' => '40px',
		];

		$this->controls['left'] = [
			'tab'   => 'style',
			'group'   => 'positioning',
			'label' => esc_html__( 'Left', 'bricks' ),
			'type'  => 'number',
			'units' => true,
			'css'   => [
				[
					'property' => 'left',
					'selector' => '',
				],
			],
		];

		$this->controls['zIndex'] = [
			'tab'         => 'style',
			'group'   => 'positioning',
			'label'       => esc_html__( 'Z-index', 'bricks' ),
			'type'        => 'number',
			'css'         => [
				[
					'property' => 'z-index',
					'selector' => '',
				],
			],
			'min'         => -999,
			'placeholder' => 10,
		];


    /* scrolling 
    
    only visivle when scrolling up
    visible only after scrolling x pixels.


    
    */

    $this->controls['scrollDistance'] = [
			'tab'         => 'content',
			'type'        => 'number',
      'units'       => true,
			'label' => esc_html__( 'Visible only after scrolling..', 'bricks' ),
			'placeholder' => '100px',
      'group' => 'scrolling',
			'inline'      => true,
      'small' => true,
		];

    $this->controls['scrollUp'] = [
			'tab'   => 'content',
			'inline' => true,
			'group' => 'scrolling',
			'label' => esc_html__( 'Only visible when scrolling up', 'bricks' ),
			'type'  => 'checkbox',
		];

    $this->controls['animationDescription'] = [
      'tab'   => 'content',
      'type'  => 'seperator',
      'group' => 'animation',
      'description' => esc_html__( 'Set the opacity/transform for the button in for the hidden state', 'bricksextras' ),
    ];

    $this->controls['previewHidden'] = [
			'tab'   => 'content',
			'inline' => true,
			'group' => 'animation',
			'label' => esc_html__( 'Preview hidden state in builder', 'bricks' ),
			'type'  => 'checkbox',
		];

    $this->controls['hiddenOpacity'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Opacity', 'extras' ),
			'inline'      => true,
			'small'		  => true,
			'group'	   => 'animation',
			'type' => 'number',
			'min' => 0,
			'max' => 1,
			'step' => '0.1',
      'placeholder' => '0',
			'css' => [
          [
          'selector' => '',
          'property' => 'opacity',
          ],
        ],
		  ];

    $this->controls['hiddenTransform'] = [
			'tab'         => 'style',
			'group'       => 'animation',
			'type'        => 'transform',
			'label'       => esc_html__( 'Transform', 'bricks' ),
			'css'         => [
				[
          'selector' => '',
					'property' => 'transform',
				],
			],
      'default' => [
        'translateY' => '10px'
      ],
			'inline'      => true,
			'small'       => true,
		];


    /* inner layout */

    /* inner layout */

	  $innerContent = '.x-back-to-top_content';

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

    $this->controls['innerBackgroundColor'] = [
			'tab'   => 'content',
			'group' => 'inner_layout',
			'label' => esc_html__( 'Background color', 'bricks' ),
			'type'  => 'color',
			'css'   => [
				[
					'property' => 'background-color',
					'selector' => $innerContent,
				],
			],
		];

		$this->controls['innerBorder'] = [
			'tab'   => 'content',
			'label' => esc_html__( 'Border', 'bricks' ),
			'group' => 'inner_layout',
			'type'  => 'border',
			'css'   => [
				[
					'property' => 'border',
					'selector' => $innerContent,
				],
			],
		];

  }

  // Methods: Frontend-specific
  public function enqueue_scripts() {

    wp_enqueue_script( 'x-backtotop', BRICKSEXTRAS_URL . 'components/assets/js/' . \BricksExtras\Helpers::maybeMinifyScripts('backtotop') . '.js', '', '1.0.3', true );

    if (! \BricksExtras\Helpers::elementCSSAdded($this->name) ) {
      wp_enqueue_style( 'x-backtotop', BRICKSEXTRAS_URL . 'components/assets/css/backtotop.css', [], '' );
    }
  }
  
  public function render() {

    $settings = $this->settings;

    $icon = ! empty( $settings['icon'] ) ? self::render_icon( $settings['icon'] ) : false;
    $ariaLabel = isset( $settings['ariaLabel'] ) ? esc_attr__( $settings['ariaLabel'] ) : false;
    $buttonText = isset( $settings['buttonText'] ) ? esc_html__( $settings['buttonText'] ) : false;

    $scrollDistance = isset( $settings['scrollDistance'] ) ? intval( $settings['scrollDistance'] ) : 100;
    $type = isset( $settings['type'] ) ? esc_attr( $settings['type'] ) : 'progress';
    $content = isset( $settings['content'] ) ? $settings['content'] : 'icon';


    $config = [
      'type' => $type,
      'scrollDistance' => $scrollDistance,
      'scrollUp' => isset( $settings['scrollUp'] )
    ];

    $this->set_attribute( '_root', 'data-x-backtotop', wp_json_encode( $config ) );
    $this->set_attribute( '_root', 'class', 'x-back-to-top' );
    //$this->set_attribute( '_root', 'disabled', '' );

    if ( $ariaLabel ) {
      $this->set_attribute( '_root', 'aria-label', $ariaLabel );
    }


    echo "<button {$this->render_attributes( '_root' )}>";

    if ( 'progress' === $type ) {

      echo '<svg class="x-back-to-top_progress" height="100%" viewBox="0 0 100 100" width="100%">
              <path class="x-back-to-top_progress-background" d="M50,1 a50,50 0 0,1 0,100 a50,50 0 0,1 0,-100"></path>
              <path class="x-back-to-top_progress-line" d="M50,1 a50,50 0 0,1 0,100 a50,50 0 0,1 0,-100"></path>
          </svg>';    
          
    }

    echo "<span class='x-back-to-top_content'>";    

        if ( 'icon' === $content ) {

          echo "<span class='x-back-to-top_icon'>" . $icon . "</span>";  

          if ( $buttonText ) {
              echo "<span class='x-back-to-top_text'>" . $buttonText . "</span>";  
          } 

        } else {

          if ( method_exists('\Bricks\Frontend','render_children') ) {
            echo \Bricks\Frontend::render_children( $this );
          }  

        }

    echo "</span>";

    echo "</button>";

    
  }

  public static function render_builder() { ?>

		<script type="text/x-template" id="tmpl-bricks-element-xbacktotop">

            <component 
                is=button 
                class="x-back-to-top"
                aria-label="back to top"
                :data-x-backtotop="'regular' != settings.type ? 'progress' : 'regular'"
                :class="!settings.previewHidden ? 'x-back-to-top_builder-preview': ''"
            >

            <svg 
              v-show="'regular' !== settings.type"
              class="x-back-to-top_progress"
              :class="'regular' == settings.type ? 'x-back-to-top_progress-invisible' : ''" 
              height="100%" 
              viewBox="0 0 100 100" 
              width="100%"
              >
                <path class="x-back-to-top_progress-background" d="M50,1 a50,50 0 0,1 0,100 a50,50 0 0,1 0,-100"></path>
                <path class="x-back-to-top_progress-line" d="M50,1 a50,50 0 0,1 0,100 a50,50 0 0,1 0,-100" style="transition: stroke-dashoffset 10ms linear 0s; stroke-dasharray: 307.919, 307.919; stroke-dashoffset: 237.098;"></path>
            </svg>

            <span class='x-back-to-top_content'>

              <span v-if="'nest' != settings.content && settings.icon" class='x-back-to-top_icon'>
                  <icon-svg :iconSettings="settings.icon"/>
              </span>
              <contenteditable
                  tag="span"
                  v-if="'nest' != settings.content" 
                  class="x-back-to-top_text"
                  :name="name"
                  controlKey="buttonText"
                  toolbar="style"
                  :settings="settings"
              />

              <bricks-element-children
                v-if="'nest' == settings.content"
                :element="element"
              />
              </span>
            </component>
			
		</script>

	<?php }

}