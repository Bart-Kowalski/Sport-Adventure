<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class X_Lottie extends \Bricks\Element {

  // Element properties
  public $category     = 'extras';
	public $name         = 'xlottie';
	public $icon         = 'ti-image';
	public $css_selector = '';
	public $scripts      = ['xLottie'];

  
  public function get_label() {
	return esc_html__( 'Lottie', 'bricks' );
  }
  public function set_control_groups() {

  }

  public function set_controls() {

    $this->controls['lottieURL'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Lottie URL', 'bricks' ),
			//'inline' => true,
			'type' => 'text',
			'placeholder' => esc_url( 'https://assets8.lottiefiles.com/packages/lf20_8qDRX7nBln.json', 'bricks' )
		];

    $this->controls['width'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Width', 'bricks' ),
			'inline'      => true,
			'small'		  => true,
			'type' => 'number',
			'units'    => true,
			'css' => [
			  [
				'selector' => '',  
				'property' => 'width',
			  ],
			],
			'placeholder' => '300px',
		  ];


      $this->controls['frameStart'] = [
        'tab' => 'content',
        'inline'      => true,
        'label' => esc_html__( 'Start frame', 'bricks' ),
        'type' => 'number',
        'units' => false,
        'placeholder' => esc_html__( '0', 'bricks' )
      ];
  
      $this->controls['frameEnd'] = [
        'tab' => 'content',
        'inline'      => true,
        'label' => esc_html__( 'End frame', 'bricks' ),
        'type' => 'number',
        'units' => false,
        'placeholder' => esc_html__( '60', 'bricks' )
      ];

      $this->controls['speed'] = [
        'tab' => 'content',
        'inline'      => true,
        'label' => esc_html__( 'Speed', 'bricks' ),
        'type' => 'number',
        'units' => false,
        'placeholder' => esc_html__( '1', 'bricks' ),
        'required' => ['trigger', '!=', ['scroll','scrollSelector','cursor']],
      ];


      $this->controls['interactivitySep'] = [
        'tab'   => 'content',
        'type'  => 'separator',
        'label'  => esc_html__( 'Interactivity', 'bricks' ),
      ];

      
		$this->controls['trigger'] = [
			'tab'         => 'content',
			'label'       => esc_html__( 'Trigger', 'bricks' ),
			'type'        => 'select',
      //'inline'      => true,
			'options'     => array(
				'viewport' => esc_html__( 'When visible in viewport', 'bricks' ),
        'scroll'   => esc_html__( 'Scroll (relative to lottie)', 'bricks' ),
        'scrollSelector'   => esc_html__( 'Scroll (relative to another element)', 'bricks' ),
				'click'    => esc_html__( 'Click (on lottie)', 'bricks' ),
        'clickSelector'    => esc_html__( 'Click (on another element)', 'bricks' ),
				'hover'    => esc_html__( 'Hover (over Lottie)', 'bricks' ),
        'hoverSelector'    => esc_html__( 'Hover (over another element)', 'bricks' ),
				'cursor'     => esc_html__( 'Cursor position', 'bricks' ),
			),
			'default'     => 'click',
			'placeholder' => esc_html__( 'None', 'bricks' ),
    ];

    $this->controls['clickElementSelector'] = [
			'tab' => 'content',
      'inline'      => true,
			'label' => esc_html__( 'Element selector', 'bricks' ),
			'type' => 'text',
			'placeholder' => esc_html__( '.element-class', 'bricks' ),
      'required' => ['trigger', '=', 'clickSelector'],
		];

    $this->controls['reverseClick'] = [
			'tab'   => 'content',
			'inline' => true,
			'small' => true,
			'label' => esc_html__( 'Reverse on second click', 'bricks' ),
			'type'  => 'checkbox',
      'required' => ['trigger', '=', ['click','clickSelector']],
		];

    $this->controls['scrollElementSelector'] = [
			'tab' => 'content',
      'inline'      => true,
			'label' => esc_html__( 'Element selector', 'bricks' ),
			'type' => 'text',
			'placeholder' => esc_html__( '.container-class', 'bricks' ),
      'required' => ['trigger', '=', 'scrollSelector'],
		];

    $this->controls['hoverSelector'] = [
			'tab' => 'content',
      'inline'      => true,
			'label' => esc_html__( 'Element selector', 'bricks' ),
			'type' => 'text',
			'placeholder' => esc_html__( '.container-class', 'bricks' ),
      'required' => ['trigger', '=', 'hoverSelector'],
		];

    $this->controls['hoverReverse'] = [
			'tab'   => 'content',
			'inline' => true,
			'small' => true,
			'label' => esc_html__( 'Reverse on mouseout', 'bricks' ),
			'type'  => 'checkbox',
      'required' => ['trigger', '=', ['hover','hoverSelector']],
		];

    $this->controls['offsetTop'] = [
      'tab' => 'content',
      'inline'      => true,
      'label' => esc_html__( 'Offset top (%)', 'bricks' ),
      'description' => esc_html__( 'Distance from top of viewport for animation to end', 'bricks' ),
      'type' => 'number',
      'units' => false,
      'placeholder' => esc_html__( '0', 'bricks' ),
      'required' => ['trigger', '=', ['scroll','scrollSelector']],
    ];

    $this->controls['offsetBottom'] = [
      'tab' => 'content',
      'inline'      => true,
      'label' => esc_html__( 'Offset bottom (%)', 'bricks' ),
      'description' => esc_html__( 'Distance from bottom of viewport for animation to start', 'bricks' ),
      'type' => 'number',
      'units' => false,
      'placeholder' => esc_html__( '0', 'bricks' ),
      'required' => ['trigger', '=', ['scroll','scrollSelector']],
    ];


    $this->controls['autoPlay'] = [
			'tab'   => 'content',
			'inline' => true,
			'small' => true,
			'label' => esc_html__( 'Autoplay', 'bricks' ),
			'type'  => 'checkbox',
      'required' => ['trigger', '=', ['none','viewport']],
		];

    $this->controls['loop'] = [
			'tab'   => 'content',
			'inline' => true,
			'small' => true,
			'label' => esc_html__( 'Loop', 'bricks' ),
			'type'  => 'checkbox',
      'required' => ['trigger', '=', ['none','viewport']],
		];
  

    $this->controls['perfSep'] = [
      'tab'   => 'content',
      'type'  => 'separator',
      'label'  => esc_html__( 'Performance', 'bricks' ),
    ];

    $this->controls['lottieVersion'] = [
			'tab'         => 'content',
			'label'       => esc_html__( 'Lottie version', 'bricks' ),
			'type'        => 'select',
      'inline'      => true,
			'options'     => array(
				'light' => esc_html__( 'Light', 'bricks' ),
        'full'   => esc_html__( 'Full', 'bricks' ),
			),
			'default'     => 'light',
			'placeholder' => esc_html__( 'Light', 'bricks' ),
      'description' => esc_html__( "Lottie light is ~39% lighter version of Lottie that doesn't support expressions or effects. Use light unless your chosen animation needs those supported", 'bricks' ),
    ];

  }

  
   public function enqueue_scripts() {
   
    if (! \BricksExtras\Helpers::elementCSSAdded($this->name) ) {
			wp_enqueue_style( 'x-lottie', BRICKSEXTRAS_URL . 'components/assets/css/lottie.css', [], '' );
		}
    
  }
  
  public function render() {

    $trigger = isset( $this->settings['trigger'] ) ? $this->settings['trigger'] : 'none';
    $lottieURL = isset( $this->settings['lottieURL'] ) ? esc_url( $this->render_dynamic_data( $this->settings['lottieURL'] ) ) : 'https://assets8.lottiefiles.com/packages/lf20_8qDRX7nBln.json';
    $lottieVersion = isset( $this->settings['lottieVersion'] ) ? $this->settings['lottieVersion'] : 'light';

    $lottieConfig = [
      'url' => $lottieURL,
      'trigger' => $trigger,
      'start' => isset( $this->settings['frameStart'] ) ? intval( $this->settings['frameStart'] ) : 0,
      'end' => isset( $this->settings['frameEnd'] ) ? intval( $this->settings['frameEnd'] ) : 60,
      'speed' => isset( $this->settings['speed'] ) ? floatval( $this->settings['speed'] ) : 1,
    ];

    if ( 'scroll' === $trigger || 'scrollSelector' === $trigger || 'viewport' === $trigger  ) {
			$lottieConfig += [ 
        'offsetB' => isset( $this->settings['offsetBottom'] ) ? intval($this->settings['offsetBottom']) : 0,
        'offsetT' => isset( $this->settings['offsetTop'] ) ? intval($this->settings['offsetTop']) : 0,
        'scrollSelector' => isset( $this->settings['scrollElementSelector'] ) ? esc_attr( $this->settings['scrollElementSelector'] ) : '',
      ];
		}

    if ( 'none' === $trigger || 'viewport' === $trigger ) {
			$lottieConfig += [ 
        'autoPlay' => isset( $this->settings['autoPlay'] ) ? $this->settings['autoPlay'] : false,
        'loop' => isset( $this->settings['loop'] ) ? $this->settings['loop'] : false,
      ];
		}

    if ( 'click' === $trigger || 'clickSelector' === $trigger ) {
			$lottieConfig += [ 
        'rev' => isset( $this->settings['reverseClick'] ),
        'clickSelector' => isset( $this->settings['clickElementSelector'] ) ? esc_attr( $this->settings['clickElementSelector'] ) : '.element-class',
      ];
		}

    if ( 'hoverSelector' === $trigger ) {
			$lottieConfig += [ 
        'hoverReverse' => isset( $this->settings['hoverReverse'] ),
        'hoverSelector' => isset( $this->settings['hoverSelector'] ) ? esc_attr( $this->settings['hoverSelector'] ) : '.container-class',
      ];
		}

    if ( 'hover' === $trigger ) {
			$lottieConfig += [ 
        'hoverReverse' => isset( $this->settings['hoverReverse'] ),
      ];
		}

    $this->set_attribute( '_root', 'data-x-lottie', wp_json_encode( $lottieConfig ) );


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


    echo "<div {$this->render_attributes( '_root' )}></div>";

    if ('light' === $lottieVersion) {
      wp_enqueue_script( 'x-lottie-light', BRICKSEXTRAS_URL . 'components/assets/js/lottielight.min.js', '', '5.9.6', true );
    } else {
      wp_enqueue_script( 'x-lottie', BRICKSEXTRAS_URL . 'components/assets/js/lottie.min.js', '', '5.9.6', true );
    }

    

    if ( 'none' !== $trigger && 'viewport' !== $trigger && 'hoverSelector' != $trigger ) {
      wp_enqueue_script( 'x-lottie-interactivity', BRICKSEXTRAS_URL . 'components/assets/js/lottieinteractivity.min.js', '', '1.6.1', true );
    }

    wp_enqueue_script( 'x-lottie-init', BRICKSEXTRAS_URL . 'components/assets/js/' . \BricksExtras\Helpers::maybeMinifyScripts('lotties') . '.js', '', '1.0.5', true );

    wp_localize_script(
			'x-lottie-init',
			'xLottieAnimation',
			[
				'Instances' => [],
			]
		);


  }


}