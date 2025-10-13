<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class X_Interactive_Cursor extends \Bricks\Element {

  // Element properties
  public $category     = 'extras';
	public $name         = 'xinteractivecursor';
	public $icon         = 'ti-control-record';
	public $css_selector = '';

  
  public function get_label() {
	return esc_html__( 'Interactive Cursor', 'extras' );
  }
  public function set_control_groups() {

  }

  public function set_controls() {

    $this->controls['builderHidden'] = [
        'tab'   => 'content',
        'inline' => true,
        'small' => true,
        'label' => esc_html__( 'Hide in builder', 'bricks' ),
        'type'  => 'checkbox',
    ];

    $this->controls['builderHidden_sep'] = [
        'tab'   => 'content',
        'type'  => 'separator',
    ];

    $this->controls['hoverSelectors'] = [
        'tab' => 'content',
        'label' => esc_html__( 'Hover selectors', 'bricks' ),
        'info' => esc_html__( 'Include all selectors where the cursor should turn into the hover state', 'bricks' ),
        'type' => 'text',
        'default' => 'a, button, input, textarea, .splide__slide, .vds-button',
		'hasDynamicData' => false,
      ];

      $this->controls['trailDelay'] = [
        'tab'      => 'content',
        'label'    => esc_html__( 'Trail speed (0-1)', 'bricks' ),
        'info' => esc_html__( 'How fast the trail catches up. 1 = instantly, 0 = insanely slow', 'bricks' ),
        'type'     => 'number',
        'units'    => false,
        'min'      => 0,
        'max'      => 1,
        'step'      => .025,
        'placeholder' => .2,
    ];

      $this->controls['configSep'] = [
        'tab'   => 'content',
        'type'  => 'separator',
    ];  

    

    $this->controls['builderPreview'] = [
        'tab' => 'content',
        'label' => esc_html__( 'Preview cursor state', 'bricks' ),
        'type' => 'select',
        'description' => esc_html__( 'Preview each state in the builder to change the styles for each.', 'bricks' ),
        'options' => [
          'default' => esc_html__( 'Default', 'bricks' ),
          'trail-grow' => esc_html__( 'Hover state', 'bricks' ),
          'text-visible' => esc_html__( 'Showing text', 'bricks' ),
          'mousedown' => esc_html__( 'Mousedown', 'bricks' ),
        ],
        'clearable' => false,
        'default' => 'default'
    ];

   

    /* default state */

    $this->controls['ballScale'] = [
        'tab'      => 'content',
        'label'    => esc_html__( 'Ball scale', 'bricks' ),
        'type'     => 'number',
        'units'    => false,
        'min'      => 0,
        'max'      => 2,
        'step'      => 0.05,
        'placeholder' => 0.7,
        'css'      => [
            [
                'property' => '--x-cursor-text-start',
                'selector' => '',
            ],
        ],
        'required' => ['builderPreview', '=', 'default']
    ];

    $this->controls['trailScale'] = [
        'tab'      => 'content',
        'label'    => esc_html__( 'Trail scale', 'bricks' ),
        'type'     => 'number',
        'units'    => false,
        'min'      => 0,
        'max'      => 2,
        'step'      => 0.05,
        'placeholder' => 1,
        'css'      => [
            [
                'property' => '--x-cursor-trail-start',
                'selector' => '',
            ],
        ],
        'required' => ['builderPreview', '=', 'default']
    ];

    /* hover state */

    $this->controls['hoverStateSep'] = [
      'tab'   => 'content',
      'type'  => 'separator',
      'label' => esc_html__( 'Hover state', 'bricks' ),
      'description' => esc_html__( 'When cursor passes over any of the chosen hover selectors (above).', 'bricks' ),
      'required' => ['builderPreview', '=', 'trail-grow']
    ];  

    $this->controls['ballHoverScale'] = [
        'tab'      => 'content',
        'label'    => esc_html__( 'Ball scale', 'bricks' ),
        'type'     => 'number',
        'units'    => false,
        'min'      => 0,
        'step'      => 0.025,
        'placeholder' => 0,
        'css'      => [
            [
                'property' => '--x-cursor-text-end',
                'selector' => '',
            ],
        ],
        'required' => ['builderPreview', '=', 'trail-grow']
    ];

    $this->controls['trailHoverScale'] = [
        'tab'      => 'content',
        'label'    => esc_html__( 'Trail scale', 'bricks' ),
        'type'     => 'number',
        'units'    => false,
        'min'      => 0,
        'step'      => 0.025,
        'placeholder' => 3,
        'css'      => [
            [
                'property' => '--x-cursor-trail-scale',
                'selector' => '',
            ],
        ],
        'required' => ['builderPreview', '=', 'trail-grow']
    ];

    /* text state */

    $this->controls['textVisibleSep'] = [
      'tab'   => 'content',
      'type'  => 'separator',
      'label' => esc_html__( 'Showing text', 'bricks' ),
      'description' => esc_html__( 'When hovering over elements with data-x-hover value.', 'bricks' ),
      'required' => ['builderPreview', '=', 'text-visible']
    ];  

    $this->controls['ballTextScale'] = [
        'tab'      => 'content',
        'label'    => esc_html__( 'Ball scale', 'bricks' ),
        'type'     => 'number',
        'units'    => false,
        'min'      => 0,
        'step'      => 0.025,
        'placeholder' => 10,
        'css'      => [
            [
                'property' => '--x-cursor-text-scale',
                'selector' => '',
            ],
        ],
        'required' => ['builderPreview', '=', 'text-visible']
    ];

    $this->controls['ballTypography'] = [
        'tab'    => 'content',
        'type'   => 'typography',
        'label'  => esc_html__( 'Typography', 'bricks' ),
        'css'    => [
          [
            'property' => 'font',
            'selector' => '',
          ],
        ],
        'required' => ['builderPreview', '=', 'text-visible']
      ];

     /* mousedown state */

     $this->controls['mouseDownSep'] = [
      'tab'   => 'content',
      'type'  => 'separator',
      'label' => esc_html__( 'Mousedown', 'bricks' ),
      'description' => esc_html__( 'On mouse down, before fully clicking.', 'bricks' ),
      'required' => ['builderPreview', '=', 'mousedown']
    ];  

     $this->controls['ballMousedownScale'] = [
        'tab'      => 'content',
        'label'    => esc_html__( 'Ball scale', 'bricks' ),
        'type'     => 'number',
        'units'    => false,
        'min'      => 0,
        'step'      => 0.025,
        'placeholder' => 0.3,
        'css'      => [
            [
                'property' => '--x-cursor-text-down',
                'selector' => '',
            ],
        ],
        'required' => ['builderPreview', '=', 'mousedown']
    ];

    $this->controls['trailMousedownScale'] = [
        'tab'      => 'content',
        'label'    => esc_html__( 'Trail scale', 'bricks' ),
        'type'     => 'number',
        'units'    => false,
        'min'      => 0,
        'step'      => 0.025,
        'placeholder' => 0.8,
        'css'      => [
            [
                'property' => '--x-cursor-trail-down',
                'selector' => '',
            ],
        ],
        'required' => ['builderPreview', '=', 'mousedown']
    ];


    $this->controls['stylesSep'] = [
        'tab'   => 'content',
        'type'  => 'separator',
     ];

     $this->controls['ballColor'] = [
        'tab'    => 'content',
        'type'   => 'color',
        'label'  => esc_html__( 'Ball color', 'extras' ),
        'css'    => [
          [
            'property' => '--x-cursor-ball-color',
            'selector' => '',
          ],
        ],
      ];

      $this->controls['ballRadius'] = [
        'tab'    => 'content',
        'type'   => 'number',
        'units'  => true,
        'label'  => esc_html__( 'Ball border radius', 'extras' ),
        'css'    => [
          [
            'property' => 'border-radius',
            'selector' => '.x-cursor_ball::after',
          ],
        ],
        'placeholder' => '50%',
      ];

      $this->controls['trailColor'] = [
        'tab'    => 'content',
        'type'   => 'color',
        'label'  => esc_html__( 'Trail color', 'extras' ),
        'css'    => [
          [
            'property' => '--x-cursor-trail-color',
            'selector' => '',
          ],
        ],
      ];

      

      $this->controls['trailRadius'] = [
        'tab'    => 'content',
        'type'   => 'number',
        'units'  => true,
        'label'  => esc_html__( 'Trail border radius', 'extras' ),
        'css'    => [
          [
            'property' => 'border-radius',
            'selector' => '.x-cursor_trail',
          ],
        ],
        'placeholder' => '50%',
      ];

      $this->controls['waitSep'] = [
        'tab'   => 'content',
        'type'  => 'separator',
    ];  

      $this->controls['wait'] = [
        'tab'      => 'content',
        'label'    => esc_html__( 'Wait before adding to page (ms)', 'bricks' ),
        'type'     => 'number',
        'units'    => false,
        'min'      => 0,
        'max'      => 1000,
        'step'      => 1,
        'placeholder' => 100,
    ];

      

  }

  public function enqueue_scripts() {
    wp_enqueue_script( 'x-cursor', BRICKSEXTRAS_URL . 'components/assets/js/' . \BricksExtras\Helpers::maybeMinifyScripts('cursor') . '.js', '', '1.0.6', true );

    if (! \BricksExtras\Helpers::elementCSSAdded($this->name) ) {
			wp_enqueue_style( 'x-cursor', BRICKSEXTRAS_URL . 'components/assets/css/interactivecursor.css', [], '' );
		}
  }
  
  public function render() {

    $hoverSelectors = isset( $this->settings['hoverSelectors'] ) ? $this->settings['hoverSelectors'] : 'a, button, input';
    $trailDelay = isset( $this->settings['trailDelay'] ) ? $this->settings['trailDelay'] : .2;
    $wait = isset( $this->settings['wait'] ) ? $this->settings['wait'] : 100;

    $config = [
        'hoverSelectors' => $hoverSelectors,
        'trailDelay'     => $trailDelay,
        'wait'     => $wait
    ];

    $this->set_attribute( '_root', 'data-x-cursor', wp_json_encode( $config ) );
    $this->set_attribute( '_root', 'aria-hidden', 'true');
    $this->set_attribute( '_root', 'class', ['x-interactive-cursor'] );
    $this->set_attribute( 'x-cursor_ball', 'class', ['x-cursor_ball','x-cursor_inner'] );
    $this->set_attribute( 'x-cursor_trail', 'class', ['x-cursor_trail','x-cursor_inner'] );
    $this->set_attribute( 'x-cursor_text', 'class', ['x-cursor_text'] );

    echo "<div {$this->render_attributes( '_root' )}>";
    echo "<div {$this->render_attributes( 'x-cursor_ball' )}>";
    echo "<span {$this->render_attributes( 'x-cursor_text' )}></span>";
    echo "</div>";
    echo "<div {$this->render_attributes( 'x-cursor_trail' )}></div>";
    echo "</div>";
        
  }

  public static function render_builder() { ?>

		<script type="text/x-template" id="tmpl-bricks-element-xinteractivecursor">

        <component 
            v-if="!settings.builderHidden"
            class="brxe-xinteractivecursor x-interactive-cursor x-cursor_ready" 
            :class="'x-cursor_' + settings.builderPreview"
            data-x-cursor=""
        >
        
            <div class="x-cursor_ball x-cursor_inner">
                <span class="x-cursor_text">Hover text</span>
            </div>
            <div class="x-cursor_trail x-cursor_inner"></div>
    
        </component>
			
		</script>

	<?php }

}