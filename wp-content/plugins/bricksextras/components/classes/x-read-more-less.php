<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class X_Read_More_Less extends \Bricks\Element {

  // Element properties
  public $category     = 'extras';
	public $name         = 'xreadmoreless';
	public $icon         = 'ti-layout-cta-center';
	public $css_selector = '';
	public $scripts      = ['xReadMoreLess'];
  public $nestable = true;

  
  public function get_label() {
	  return esc_html__( 'Read More / Less', 'extras' );
  }
  public function set_control_groups() {

    $this->control_groups['content'] = [
      'title' => esc_html__( 'Content', 'extras' ),
      'tab' => 'content',
    ];
  
    $this->control_groups['button'] = [
      'title' => esc_html__( 'Read more button', 'extras' ),
      'tab' => 'content',
    ];

    $this->control_groups['icon'] = [
      'title' => esc_html__( 'Read more icon', 'extras' ),
      'tab' => 'content',
    ];

    $this->control_groups['fadeGradient'] = [
      'title' => esc_html__( 'Fade Gradient', 'extras' ),
      'tab' => 'content',
    ];
  

  }

  public function set_controls() {

    $this->controls['builderVisibility'] = [
			'tab'   => 'content',
			'inline' => true,
			'small' => true,
			'label' => esc_html__( 'Fully expanded inside builder', 'bricks' ),
			'type'  => 'checkbox',
		];


    $this->controls['collapsedHeight'] = [
      'tab' => 'content',
      'label' => esc_html__( 'Collapsed height', 'extras' ),
      'type' => 'number',
      'css' => [
        [
          'selector' => '.x-read-more_content',  
          'property' => 'max-height',
        ],
      ],
      'placeholder' => '200px',
      'units' => 'px'
    ];

    $this->controls['speed'] = [
      'tab' => 'content',
      'label' => esc_html__( 'Animation speed (ms)', 'extras' ),
      'type' => 'number',
      'units' => false,
      'inline' => true,
      'small' => true,
      'placeholder' => '300',
    ];

    $this->controls['heightMargin'] = [
      'tab' => 'content',
      'label' => esc_html__( 'Height Margin (px)', 'bricks' ),
      'type' => 'number',
      'units' => false,
      'inline' => true,
      'small' => true,
      'placeholder' => '20',
    ];

    $this->controls['readMoreContent'] = [
      'tab' => 'content',
      'group' => 'content',
      'label' => esc_html__( 'Content', 'bricks' ),
      'type' => 'select',
      'options' => [
       // 'template' => esc_html__( 'Bricks Template', 'bricks' ),
        'wysiwyg' => esc_html__( 'Editor', 'bricks' ),
        'nestable' => esc_html__( 'Nest elements', 'bricks' ),
      ],
      'inline'      => true,
      'placeholder' => esc_html__( 'Editor', 'bricks' ),
      'clearable' => false,
      'default' => 'wysiwyg',
      ];

    $this->controls['readMoreWysiwyg'] = [
      'tab' => 'content',
      'group' => 'content',
      'label' => esc_html__( 'Content editor', 'bricks' ),
      'type' => 'editor',
      'inlineEditing' => [
        'selector' => '.text-editor', // Mount inline editor to this CSS selector
        'toolbar' => true, // Enable/disable inline editing toolbar
      ],
      'default' => "<p>Edit me, chia DIY typewriter schlitz single-origin coffee subway tile. Next level before they sold out vice truffaut, edison bulb mixtape drinking vinegar hexagon chia tattooed sustainable single-origin coffee biodiesel chambray fashion axe. Etsy trust fund authentic hammock 3 wolf moon sartorial bespoke umami tattooed. Cardigan pabst keffiyeh try-hard cold-pressed four loko food truck sustainable tote bag occupy before they sold out mixtape. Ramps tote bag freegan DIY palo santo YOLO pabst you probably haven't heard of them shabby chic. Aesthetic trust fund ugh, tousled readymade health goth tacos slow-carb tonx. Vinyl gluten-free readymade poke +1 normcore.</p>",
      'required' => ['readMoreContent', '!=', 'nestable']
      ];





      /* read more button */

     $button = '.x-read-more_link';

     $this->controls['moreText'] = [
			'tab' => 'content',
			'group' => 'button',
			'label' => esc_html__( 'More text', 'bricks' ),
			'inline' => true,
			'type' => 'text',
      'default' => 'Read More',
			//'placeholder' => esc_html__( 'Read More', 'bricks' ),
		  ];

      $this->controls['lessText'] = [
        'tab' => 'content',
        'group' => 'button',
        'label' => esc_html__( 'Less text', 'bricks' ),
        'inline' => true,
        'type' => 'text',
        'default' => 'Read Less',
        //'placeholder' => esc_html__( 'Read Less', 'bricks' ),
        ];

        $this->controls['moreAria'] = [
          'tab' => 'content',
          'group' => 'button',
          'label' => esc_html__( 'More Aria-label', 'bricks' ),
          'inline' => true,
          'type' => 'text',
          'default' => 'Read More',
          //'placeholder' => esc_html__( 'Read More', 'bricks' ),
          ];
    
          $this->controls['lessAria'] = [
            'tab' => 'content',
            'group' => 'button',
            'label' => esc_html__( 'Less Aria-label', 'bricks' ),
            'inline' => true,
            'type' => 'text',
            'default' => 'Read Less',
            //'placeholder' => esc_html__( 'Read Less', 'bricks' ),
            ];


        $this->controls['buttonDuration'] = [
          'tab' => 'content',
          'label' => esc_html__( 'Button fade duration (ms)', 'extras' ),
          'type' => 'number',
          'group' => 'button',
          'unit' => 'ms',
          'inline' => true,
          'small' => true,
          'placeholder' => '300',
          'css'    => [
            [
              'property' => 'animation-duration',
              'selector' => $button
            ],
          ],
        ];


     $this->controls['buttonTypography'] = [
       'tab'    => 'content',
       'group'  => 'button',
       'type'   => 'typography',
       'label'  => esc_html__( 'Typography', 'extras' ),
       'css'    => [
         [
           'property' => 'font',
           'selector' => $button
         ],
       ],
     ];
 
     $this->controls['buttonStyle_start'] = [
       'tab'   => 'content',
       'group'  => 'button',
       'type'  => 'separator',
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
           'selector' => $button
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

     $this->controls['buttonPosition'] = [
      'tab'    => 'content',
      'group'  => 'button',
      'label'  => esc_html__( 'Position', 'extras' ),
      'type'   => 'select',
      'inline' => true,
      'info' => 'Absolute will reduce CLS if above fold',
      'options' => [
        'static' => esc_html__( 'Static', 'extras' ),
        'absolute' => esc_html__( 'Absolute', 'extras' ),
       ],
      'css'    => [
        [
          'property' => 'position',
          'selector' => $button,
        ],
      ],
    ];

    $this->controls['buttonBottom'] = [
      'tab'    => 'content',
      'group'  => 'button',
      'label'  => esc_html__( 'Bottom', 'extras' ),
      'type'   => 'number',
      'units'   => true,
      'inline' => true,
      'required' => ['buttonPosition', '=', 'absolute'],
      'placeholder' => '0',
      'css'    => [
        [
          'property' => 'bottom',
          'selector' => $button,
        ],
      ],
    ];
 
     $this->controls['buttonStyle_end'] = [
       'tab'   => 'content',
       'group'  => 'button',
       'type'  => 'separator',
     ];
 
     $this->controls['buttonPadding'] = [
       'tab'   => 'content',
       'group' => 'button',
       'label' => esc_html__( 'Padding', 'extras' ),
       'type'  => 'dimensions',
       'css'   => [
         [
           'property' => 'padding',
           'selector' => $button,
         ],
       ],
       'placeholder' => [
			  'top' => '10px',
			  'right' => '10px',
			  'bottom' => '10px',
			  'left' => '10px',
			],
     ];

     $this->controls['buttonMargin'] = [
      'tab'   => 'content',
      'group' => 'button',
      'label' => esc_html__( 'Margin', 'extras' ),
      'type'  => 'dimensions',
      'required' => ['buttonPosition', '!=', 'absolute'],
      'css'   => [
        [
          'property' => 'margin',
          'selector' => $button,
        ],
      ],
    ];


     $this->controls['buttonAlign'] = [ // Setting key
      'tab'   => 'content',
      'label' => esc_html__( 'Align button', 'bricks' ),
      'type'  => 'align-items',
      'group' => 'button',
      'css'   => [
        [
          'property' => 'align-self',
          'selector' => $button,
        ],
        [
          'required' => 'stretch',
          'property' => 'width',
          'value' => '100%',
          'selector' => $button,
        ],
      ],
       'isHorizontal' => false,
       'exclude' => [
         'space-between',
         'space-around',
         'space-evenly',
       ],
    ];
 

    $this->controls['buttonWidth'] = [
      'tab' => 'content',
      'label' => esc_html__( 'Button width', 'extras' ),
      'type' => 'number',
      'inline' => true,
      'group' => 'button',
      'css' => [
        [
          'selector' => $button,  
          'property' => 'width',
        ],
      ],
      'units' => true
    ];


    /* icon */

    $this->controls['maybeIcon'] = [
			'tab'   => 'content',
			'inline' => true,
      'group' => 'icon',
			'small' => true,
			'label' => esc_html__( 'Display icon', 'bricks' ),
			'type'  => 'checkbox',
		];


    $this->controls['icon'] = [
      'tab'      => 'content',
      'group' => 'icon',
      'label'    => esc_html__( 'Icon', 'bricks' ),
      'type'     => 'icon',
      'group' => 'icon',
      'css'      => [
        [
          'selector' => '.x-read-more_link-icon svg',
        ],
      ],
      'default'  => [
        'library' => 'ionicons',
        'icon'    => 'ion-ios-arrow-down',
      ],
    ];

    $this->controls['iconSize'] = [
      'tab' => 'content',
      'label' => esc_html__( 'Icon size', 'bricks' ),
      'type' => 'number',
      'group'    => 'icon',
      'units' => [
        'px' => [
          'min' => 1,
          'max' => 30,
          'step' => 1,
        ],
        'em' => [
          'min' => 1,
          'max' => 20,
          'step' => 0.1,
        ],
      ],
      'inline' => true,
      'css'    => [
        [
          'property' => 'font-size',
          'selector' => '.x-read-more_link-icon',
        ],
      ],
      ];

      $this->controls['iconRotate'] = [
        'tab' => 'content',
        'label' => esc_html__( 'Icon rotation when expanded (deg)', 'extras' ),
        'type' => 'number',
        'group' => 'icon',
        'unit' => 'deg',
        'inline' => true,
        'small' => true,
        'placeholder' => '180',
        'css'    => [
          [
            'property' => '--x-read-more-rotate',
            'selector' => '.x-read-more_link-icon'
          ],
        ],
      ];

      $this->controls['iconMargin'] = [
        'tab'   => 'content',
        'group'    => 'icon',
        'label' => esc_html__( 'Icon margin', 'extras' ),
        'type'  => 'dimensions',
        'css'   => [
          [
            'property' => 'margin',
            'selector' => '.x-read-more_link-icon',
          ],
        ],
      ];


    /* fade gradient */

    $this->controls['maybeGradient'] = [
			'tab'   => 'content',
			'inline' => true,
			'group' => 'fadeGradient',
			'placeholder' => esc_html__( 'Enable', 'bricks' ),
			'label' => esc_html__( 'Fade gradient', 'bricks' ),
			'type'  => 'select',
			'options' => [
				'true' => esc_html__( 'Enable', 'bricks' ),
				'false' => esc_html__( 'Disable', 'bricks' ),
      ],
		];

    $this->controls['gradientPercentageStart'] = [
      'tab' => 'content',
      'label' => esc_html__( 'Visibility fade start %', 'extras' ),
      'type' => 'number',
      'group' => 'fadeGradient',
      'css'   => [
        [
          'property' => '--x-mask-start',
          'selector' => '.x-read-more_content',
        ],
      ],
      'placeholder' => '40',
      'unit' => '%',
      'required' => ['maybeGradient', '!=', 'false'],
    ];

    $this->controls['gradientPercentageEnd'] = [
      'tab' => 'content',
      'label' => esc_html__( 'Visibility fade end %', 'extras' ),
      'type' => 'number',
      'group' => 'fadeGradient',
      'css'   => [
        [
          'property' => '--x-mask-end',
          'selector' => '.x-read-more_content',
        ],
      ],
      'placeholder' => '90',
      'unit' => '%',
      'required' => ['maybeGradient', '!=', 'false'],
    ];
    
      

  }

  // Methods: Frontend-specific
  public function enqueue_scripts() {

	  wp_enqueue_script( 'x-read-more-less', BRICKSEXTRAS_URL . 'components/assets/js/' . \BricksExtras\Helpers::maybeMinifyScripts('readmoreless') . '.js', '', '1.1.0', true );

    wp_localize_script(
			'x-read-more-less',
			'xReadMore',
			[
				'Instances' => [],
			]
		);

    if (! \BricksExtras\Helpers::elementCSSAdded($this->name) ) {
			wp_enqueue_style( 'x-read-more-less', BRICKSEXTRAS_URL . 'components/assets/css/readmoreless.css', [], '' );
		}
  }
  
  public function render() {

    $icon = empty( $this->settings['icon'] ) ? false : self::render_icon( $this->settings['icon'] );

    $maybeGradient = isset( $this->settings['maybeGradient'] ) ? ( 'true' === $this->settings['maybeGradient'] ) : true;

    $readMoreContent = isset( $this->settings['readMoreContent'] ) ? $this->settings['readMoreContent'] : 'wysiwyg';

    if ( $maybeGradient ) { 
      $this->set_attribute( '_root', 'class', 'x-read-more_fade' );
      $this->set_attribute( '_root', 'data-x-fade', '' );
    }

    $this->set_attribute( 'x-read-more_content', 'class', 'x-read-more_content' );    

    $config = [];

    if ( isset( $this->settings['heightMargin'] ) ) {
      $config += [ 'heightMargin' => $this->settings['heightMargin']];
    }

    if ( isset( $this->settings['speed'] ) ) {
      $config += [ 'speed' => $this->settings['speed']];
    }

    if ( isset( $this->settings['moreText'] ) ) {
      $config += [ 'moreText' => esc_html__( $this->settings['moreText'] )];
    }

    if ( isset( $this->settings['lessText'] ) ) {
      $config += [ 'lessText' => esc_html__( $this->settings['lessText'] )];
    }

    if ( isset( $this->settings['moreAria'] ) ) {
      $config += [ 'moreAria' => esc_html__( $this->settings['moreAria'] )];
    }

    if ( isset( $this->settings['lessAria'] ) ) {
      $config += [ 'lessAria' => esc_html__( $this->settings['lessAria'] )];
    }

    if ( $icon && isset( $this->settings['maybeIcon'] ) ) {
      $config += [ 'icon' => $this->settings['icon']];
    }


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


    $this->set_attribute( '_root', 'data-x-readmore', wp_json_encode( $config ) );
    $this->set_attribute( 'x-read-more_icon', 'class', 'x-read-more_icon' );

    echo "<div {$this->render_attributes( '_root' )}>";
    echo "<div {$this->render_attributes( 'x-read-more_content' )}>";

    if ( 'wysiwyg' === $readMoreContent ) {

      $content = $this->settings['readMoreWysiwyg'];
      $content = $this->render_dynamic_data( $content );
      $content = \Bricks\Helpers::parse_editor_content( $content );
      echo $content;

    } else {
      if ( method_exists('\Bricks\Frontend','render_children') ) {
        echo \Bricks\Frontend::render_children( $this );
      }
    }

     echo "</div>";
    echo "</div>";

    if ( $icon ) {
      echo "<div style='display: none;' {$this->render_attributes( 'x-read-more_icon' )}> " . $icon . "  </div>";
    }

    
    
  }

  public static function render_builder() { ?>

		<script type="text/x-template" id="tmpl-bricks-element-xreadmoreless">

    <component 
      :class="{ 'x-read-more_fade' : 'false' !== settings.maybeGradient }"
			>

				<contenteditable
					tag="div"
          v-if="'nestable' !== settings.readMoreContent"
					class="x-read-more_content"
          :class="{ 'x-read-more_builder-expand' : settings.builderVisibility }"
					:name="name"
					controlKey="readMoreWysiwyg"
					toolbar="true"
					:settings="settings"
				/>

        <div 
					v-else
					class="x-read-more_content"
          :class="{ 'x-read-more_builder-expand' : settings.builderVisibility }"
				> 
        <bricks-element-children
						:element="element"
					/>
				</div>

        <button 
          aria-label="Open" 
          class=x-read-more_link
        >
        <contenteditable
					tag="span"
          class="x-read-more_link-text"
					:name="name"
					controlKey="moreText"
					toolbar="style"
					:settings="settings"
        />
        <span 
          v-if="settings.maybeIcon"
          class="x-read-more_link-icon"
        >
          <icon-svg 
          :iconSettings="settings.icon"
        />
        </span>
        </button>
        
  			</component>
			
		</script>

	<?php }

}