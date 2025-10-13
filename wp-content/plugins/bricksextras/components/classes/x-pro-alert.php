<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class X_Pro_Alert extends \Bricks\Element {

  // Element properties
  public $category     = 'extras';
	public $name         = 'xproalert';
	public $icon         = 'ti-layout-cta-right';
	public $css_selector = '';
	public $scripts      = ['xProAlert'];
  public $nestable = true;
  
  public function get_label() {
	return esc_html__( 'Pro Alert', 'extras' );
  }
  public function set_control_groups() {

    $this->control_groups['content'] = [
      'title' => esc_html__( 'Alert Content', 'extras' ),
      'tab' => 'content',
    ];

    $this->control_groups['position'] = [
      'title' => esc_html__( 'Positioning', 'extras' ),
      'tab' => 'content',
    ];

    $this->control_groups['icon'] = [
      'title' => esc_html__( 'Dismiss Icon', 'extras' ),
      'tab' => 'content',
    ];

    $this->control_groups['layout'] = [
      'title' => esc_html__( 'Layout / Spacing', 'extras' ),
      'tab' => 'content',
    ];

  }

  public function set_controls() {


    
    $this->controls['builderHidden'] = [
      'tab'   => 'content',
      'inline' => true,
      'small' => true,
      //'default' => true,
      'label' => esc_html__( 'Hide in builder', 'bricks' ),
      'type'  => 'checkbox',
    ];

    $this->controls['width'] = [
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
    ];

    $this->controls['alertBackground'] = [
      'tab'    => 'content',
      'type'   => 'background',
      'label'  => esc_html__( 'Background', 'bricks' ),
      'css'    => [
        [
          'property' => 'background',
          'selector' => '',
        ],
      ],
    ];
  
    $this->controls['alertBorder'] = [
      'tab'   => 'content',
      'type'  => 'border',
      'label' => esc_html__( 'Border', 'bricks' ),
      'css'   => [
        [
          'property' => 'border',
          'selector' => '',
        ],
      ],
    ];

    $this->controls['alert_position'] = [
      'tab' => 'content',
      'label' => esc_html__( 'Alert positioning', 'bricks' ),
      'type' => 'select',
      'group' => 'position',
      'options' => [
        'relative' => esc_html__( 'Inline content', 'bricks' ),
        'fixed' => esc_html__( 'Fixed to viewport', 'bricks' ),
      ],
      'css' => [
        [
        'property' => 'position',
        'selector' => '',
        ]
      ],
      'inline'      => true,
      'placeholder' => esc_html__( 'Inline', 'bricks' ),
      'clearable' => false,
      ];

      $this->controls['alertTop'] = [
        'tab' => 'content',
        'label' => esc_html__( 'Top', 'bricks' ),
        'type' => 'number',
        'group' => 'position',
        'units' => true,
        'inline' => true,
        'css' => [
          [
            'property' => 'top',
            'selector' => '&[data-viewport]'
          ],
        ],
        'required' => ['alert_position', '=', ['fixed']]
      ];

      $this->controls['alertRight'] = [
        'tab' => 'content',
        'label' => esc_html__( 'Right', 'bricks' ),
        'type' => 'number',
        'group' => 'position',
        'units' => true,
        'inline' => true,
        'css' => [
          [
            'property' => 'right',
            'selector' => '&[data-viewport]'
          ],
        ],
        'default' => 30,
        'required' => ['alert_position', '=', ['fixed']]
      ];

      $this->controls['alertBottom'] = [
        'tab' => 'content',
        'label' => esc_html__( 'Bottom', 'bricks' ),
        'type' => 'number',
        'group' => 'position',
        'units' => true,
        'inline' => true,
        'css' => [
          [
            'property' => 'bottom',
            'selector' => '&[data-viewport]'
          ],
        ],
        'default' => 30,
        'required' => ['alert_position', '=', ['fixed']]
      ];

      $this->controls['alertLeft'] = [
        'tab' => 'content',
        'label' => esc_html__( 'Left', 'bricks' ),
        'type' => 'number',
        'group' => 'position',
        'units' => true,
        'inline' => true,
        'css' => [
          [
            'property' => 'left',
            'selector' => '&[data-viewport]'
          ],
        ],
        'required' => ['alert_position', '=', ['fixed']]
      ];

      $this->controls['alertZindex'] = [
        'tab' => 'content',
        'label' => esc_html__( 'Z-index', 'bricks' ),
        'type' => 'number',
        'group' => 'position',
        'units' => false,
        'inline' => true,
        'css' => [
          [
            'property' => 'z-index',
            'selector' => ''
          ],
        ],
        'required' => ['alert_position', '=', ['fixed']]
      ];




  
    $this->controls['builderHidden_sep'] = [
      'tab'   => 'content',
      'type'  => 'separator',
      ];
  
      $this->controls['alert_content'] = [
      'tab' => 'content',
      'group' => 'content',
      'label' => esc_html__( 'Alert content', 'bricks' ),
      'type' => 'select',
      'options' => [
        'wysiwyg' => esc_html__( 'Editor', 'bricks' ),
        'nestable' => esc_html__( 'Nest elements', 'bricks' ),
      ],
      'inline'      => true,
      'placeholder' => esc_html__( 'Editor', 'bricks' ),
      'clearable' => false,
      'default' => 'wysiwyg',
      ];

  
  
    $this->controls['alert_wysiwyg'] = [
      'tab' => 'content',
      'group' => 'content',
      'label' => esc_html__( 'Content editor', 'bricks' ),
      'type' => 'editor',
      'inlineEditing' => [
        'selector' => '.text-editor', // Mount inline editor to this CSS selector
        'toolbar' => true, // Enable/disable inline editing toolbar
      ],
      'default' => 'Edit me. I am an alert.</p>',
      'required' => ['alert_content', '!=', ['nestable']]
      ];


      $this->controls['show_again'] = [
        'tab'         => 'content',
        'label'       => esc_html__( 'Reshow alert', 'bricks' ),
        'type'        => 'select',
        'options'     => [
          'page_load' => esc_html__( 'Show on every visit', 'bricks' ),
          'dismiss' => esc_html__( 'Show again until user clicks dismiss', 'bricks' ),
          'never' => esc_html__( 'Never show again', 'bricks' ),
          'after' => esc_html__( 'Only allow to show after:', 'bricks' ),
          'manual' => esc_html__( 'Manual (if using with code)', 'bricks' ),
        ],
        //'inline'      => true,
        'placeholder'   => esc_html__( 'Show again on next visit', 'bricks' )
      ];

      $this->controls['typeInfo'] = [
        'tab' => 'content',
        'description' => esc_html__( 'See the docs for details on show/close alerts in your code', 'bricks' ),
        'type' => 'info',
        'required' => ['show_again', '=', 'manual'],
      ];
    
      $this->controls['show_again_days'] = [
        'tab' => 'content',
        'label' => esc_html__( 'Days', 'bricks' ),
        'type' => 'number',
        'inline' => true,
        'required' => ['show_again', '=', ['after']]
        ];
    
        $this->controls['show_again_hours'] = [
        'tab' => 'content',
        'label' => esc_html__( 'Hours', 'bricks' ),
        'type' => 'number',
        'inline' => true,
        'required' => ['show_again', '=', ['after']]
        ];
      

      /* dismiss icon */

      $this->controls['dismiss_icon'] = [
        'tab'      => 'content',
		    'group' => 'icon',
        'label'    => esc_html__( 'Dismiss icon', 'bricks' ),
        'type'     => 'icon',
        'css'      => [
          [
            'selector' => '.x-alert_close-icon svg',
          ],
        ],
        'default'  => [
          'library' => 'themify',
          'icon'    => 'ti-close',
        ],
      ];

      $this->controls['aria_label'] = [
        'tab' => 'content',
        'label' => esc_html__( 'Aria label', 'bricks' ),
        'type' => 'text',
        'group' => 'icon',
        'placeholder' => esc_attr__( 'Dismiss', 'bricks' ),
        ];
    

      $icon_selector = '.x-alert_close';

      $this->controls['iconTypography'] = [
        'tab'    => 'content',
        'group'  => 'icon',
        'type'   => 'typography',
        'label'  => esc_html__( 'Typography', 'bricks' ),
        'css'    => [
          [
            'property' => 'font',
            'selector' => $icon_selector,
          ],
        ],
      ];

  
      $this->controls['iconBackgroundColor'] = [
        'tab'   => 'content',
        'group' => 'icon',
        'label' => esc_html__( 'Background color', 'bricks' ),
        'type'  => 'color',
        'css'   => [
          [
            'property' => 'background-color',
            'selector' => $icon_selector,
          ],
        ],
      ];
  
      $this->controls['iconBorder'] = [
        'tab'   => 'content',
        'label' => esc_html__( 'Border', 'bricks' ),
        'group' => 'icon',
        'type'  => 'border',
        'css'   => [
          [
            'property' => 'border',
            'selector' => $icon_selector,
          ],
        ],
      ];
  
      $this->controls['iconBoxShadow'] = [
        'tab'   => 'content',
        'label' => esc_html__( 'Box shadow', 'bricks' ),
        'group' => 'icon',
        'type'  => 'box-shadow',
        'css'   => [
          [
            'property' => 'box-shadow',
            'selector' => $icon_selector,
          ],
        ],
      ];
  
  
      $this->controls['button_padding'] = [
        'tab' => 'content',
        'group' => 'icon',
        'label' => esc_html__( 'Padding', 'bricks' ),
        'type' => 'dimensions',
        'css' => [
          [
          'property' => 'padding',
          'selector' => $icon_selector,
          ]
        ],
        'placeholder' => [
          'top' => '10px',
          'right' => '10px',
          'bottom' => '10px',
          'left' => '10px',
        ],
        ];
  
        $this->controls['button_margin'] = [
        'tab' => 'content',
        'group' => 'icon',
        'label' => esc_html__( 'Margin', 'bricks' ),
        'type' => 'dimensions',
        'css' => [
          [
          'property' => 'margin',
          'selector' => $icon_selector,
          ]
        ],
        'placeholder' => [
          'top' => '10px',
          'right' => '10px',
          'bottom' => '10px',
          'left' => '10px',
        ],
        ];



      /* layout */

      $this->controls['padding'] = [
        'tab' => 'content',
        'label' => esc_html__( 'Padding', 'bricks' ),
        'type' => 'dimensions',
        'group' => 'layout',
        'css' => [
          [
          'property' => 'padding',
          'selector' => '',
          ]
        ],
        'placeholder' => [
          'top' => '30px',
          'right' => '30px',
          'bottom' => '30px',
          'left' => '30px',
        
        ],
      ];


  }

  // Methods: Frontend-specific
  public function enqueue_scripts() {

		wp_enqueue_script( 'x-pro-alert', BRICKSEXTRAS_URL . 'components/assets/js/' . \BricksExtras\Helpers::maybeMinifyScripts('proalert') . '.js', '', '1.0.1', true );

    if (! \BricksExtras\Helpers::elementCSSAdded($this->name) ) {
			wp_enqueue_style( 'x-pro-alert', BRICKSEXTRAS_URL . 'components/assets/css/proalert.css', [], '' );
		}

	}
  
  public function render() {

    $alert_content = isset( $this->settings['alert_content'] ) ? $this->settings['alert_content'] : 'wysiwyg';
    $alert_position = isset( $this->settings['alert_position'] ) ? $this->settings['alert_position'] : 'relative';
    $dismiss_icon = empty( $this->settings['dismiss_icon'] ) ? false : self::render_icon( $this->settings['dismiss_icon'] );
    $aria_label = isset( $this->settings['aria_label'] ) ? esc_attr__($this->settings['aria_label'] ): 'Dismiss';

    $alert_config = [];

    $alert_config['show_again'] = [
      'type' => isset( $this->settings['show_again'] ) ? $this->settings['show_again'] : '',
      'options' => [
        'days' => isset( $this->settings['show_again_days'] ) ? intval( $this->settings['show_again_days'] ) : 0,
        'hours' => isset( $this->settings['show_again_hours'] ) ? intval( $this->settings['show_again_hours'] ) : 0,
      ],
    ];
  

    $this->set_attribute( '_root', 'data-x-alert', wp_json_encode( $alert_config ) );

    if ('fixed' === $alert_position ) {
      $this->set_attribute( '_root', 'data-viewport', '' );
    }

    $this->set_attribute( 'x-alert_close', 'class', 'x-alert_close' );
    $this->set_attribute( 'x-alert_close', 'aria-label', $aria_label );
    $this->set_attribute( 'x-alert_close-icon', 'class', 'x-alert_close-icon' );

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


    echo "<div {$this->render_attributes( '_root' )}>";

    if ( 'wysiwyg' === $alert_content ) {
     if ( isset( $this->settings['alert_wysiwyg'] ) ) {
          $content = $this->settings['alert_wysiwyg'];
          $content = $this->render_dynamic_data( $content );
          $content = \Bricks\Helpers::parse_editor_content( $content );
          echo $content;
     }
   } else {
    if ( method_exists('\Bricks\Frontend','render_children') ) {
      echo \Bricks\Frontend::render_children( $this );
    }
   }

    echo "<button {$this->render_attributes( 'x-alert_close' )}><span {$this->render_attributes( 'x-alert_close-icon' )}> " . $dismiss_icon . "  </span></button>";

    echo "</div>";
    
  }

  public static function render_builder() { ?>

		<script type="text/x-template" id="tmpl-bricks-element-xproalert">

    <component 
				v-show="!settings.builderHidden"
        :data-viewport="'fixed' === settings.alert_position ? true : null"
        data-x-alert
			>

      <contenteditable
            tag="div"
            v-if="'nestable' !== settings.alert_content"
            class="x-alert_content"
            :name="name"
            controlKey="alert_wysiwyg"
            toolbar="true"
            :settings="settings"
          />
          <div 
            v-else
            class="x-alert_content"
          >
          <bricks-element-children
						:element="element"
					/>
          </div>
        
          <button 
            class="x-alert_close"
          >
            <span class="x-alert_close-icon"><icon-svg :iconSettings="settings.dismiss_icon"/></span>
          </button>
  			</component>
			
		</script>

	<?php }

}