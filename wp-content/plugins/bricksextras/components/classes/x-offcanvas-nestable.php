<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class X_Offcanvas_Nestable extends \Bricks\Element {

	// Element properties
	public $category     = 'extras';
	public $name         = 'xoffcanvasnestable';
	public $icon         = 'ti-layout-sidebar-left';
	public $css_selector = '.x-offcanvas_inner';
	public $nestable = true;
	//public $scripts      = ['xOffCanvas']; /* Please prefix all your scripts. E.g.: prefixElementTest */

	// Methods: Builder-specific
	public function get_label() {
		return esc_html__( 'Pro OffCanvas', 'extras' );
	}

	public function get_keywords() {
		return [ 'menu' ];
	}

	// Set builder control groups
	public function set_control_groups() {

		$this->controls['text'] = [ // Unique group identifier (lowercase, no spaces)
			'title' => esc_html__( 'Text', 'extras' ), // Localized control group title
			'tab' => 'content', // Set to either "content" or "style"
		];

		$this->control_groups['layout'] = [
			'title' => esc_html__( 'Layout / Spacing', 'extras' ),
			'tab' => 'content',
		];

		$this->control_groups['config'] = [
			'title' => esc_html__( 'Config', 'extras' ),
			'tab' => 'content',
		];

		$this->control_groups['backdrop'] = [
			'title' => esc_html__( 'Backdrop', 'extras' ),
			'tab' => 'content',
		];

		$this->control_groups['accessibility'] = [
			'title' => esc_html__( 'Accessibility', 'extras' ),
			'tab' => 'content',
		];
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

		$this->controls['clickTrigger'] = [
			'tab'         => 'content',
			'type'        => 'text',
			'label' => esc_html__( 'Click trigger', 'bricks' ),
			'placeholder' => esc_html__( '.your-class-here', 'bricks' ),
			'hasDynamicData' => false,
			'inline'      => true,
			'default'	=> '.brxe-xburgertrigger'
		];

		  $this->controls['offcanvas_color'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Background color', 'bricks' ),
			'type' => 'color',
			'inline' => true,
			'css' => [
			  [
				'property' => 'background-color',
				'selector' => '.x-offcanvas_inner',
			  ]
			],
		  ];


		  $this->controls['style_sep'] = [
			'tab'   => 'content',
			'type'  => 'separator',
		  ];

		    
		  $this->controls['direction'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Position', 'bricks' ),
			'type' => 'select',
			'options' => [
			  'x-offcanvas_left' => esc_html__( 'Left', 'bricks' ),
			  'x-offcanvas_right' => esc_html__( 'Right', 'bricks' ),
			  'x-offcanvas_top' => esc_html__( 'Top', 'bricks' ),
			  'x-offcanvas_bottom' => esc_html__( 'Bottom', 'bricks' ),
			],
			'inline'      => true,
			//'small'		  => true,
			'clearable' => false,
			'placeholder' => esc_html__( 'Select', 'bricks' ),
			'default' => 'x-offcanvas_left',
		  ];

		  $this->controls['transitionDuration'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Transition duration', 'bricks' ),
			'inline'      => true,
			'type' => 'number',
			'units'    => true,
			'small'		=> true,
			'css' => [
				[
				  'selector' => '',  
				  'property' => '--x-offcanvas-duration',
				],
			  ],
			//'inlineEditing' => true,
			'placeholder' => '300ms',
		  ];

		  $this->controls['transitionType'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Transition type', 'bricks' ),
			'type' => 'select',
			'options' => [
			  'slide' => esc_html__( 'Slide', 'bricks' ),
			  'fade' => esc_html__( 'Fade', 'bricks' ),
			],
			'inline'      => true,
			'clearable' => false,
			'placeholder' => esc_html__( 'Slide', 'bricks' ),
		  ];

		  $this->controls['offcanvas_width'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Width', 'extras' ),
			'inline'      => true,
			'small'		=> true,
			'type' => 'number',
			'units'    => true,
			'css' => [
			  [
				'selector' => '.x-offcanvas_inner',  
				'property' => 'width',
			  ],
			],
			'placeholder' => '300px',
			//'default' => '300px',
			'required' => ['direction', '=', ['x-offcanvas_left', 'x-offcanvas_right']],
		  ];

		  $this->controls['offcanvas_height'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Height', 'extras' ),
			'inline'      => true,
			'small'		  => true,
			'type' => 'number',
			'units'    => true,
			'css' => [
			  [
				'selector' => '.x-offcanvas_inner',  
				'property' => 'height',
			  ],
			],
			'placeholder' => '100%',
			'required' => ['direction', '=', ['x-offcanvas_top', 'x-offcanvas_bottom']],
		  ];

		  
		  /* config */

		  $this->controls['maybe_hash_close'] = [
			'tab'   => 'content',
			'inline' => true,
			'group' => 'config',
			//'default' => 'false',
			'placeholder' => esc_html__( 'Enable', 'bricks' ),
			'label' => esc_html__( 'Clicking hashlink will close', 'bricks' ),
			'type'  => 'select',
			'options' => [
				'true' => esc_html__( 'Enable', 'bricks' ),
				'false' => esc_html__( 'Disable', 'bricks' ),
			]
		];

		$this->controls['esc_to_close'] = [
			'tab'   => 'content',
			'inline' => true,
			'group' => 'config',
			//'default' => 'false',
			'placeholder' => esc_html__( 'Enable', 'bricks' ),
			'label' => esc_html__( 'Esc to close', 'bricks' ),
			'type'  => 'select',
			'options' => [
				'true' => esc_html__( 'Enable', 'bricks' ),
				'false' => esc_html__( 'Disable', 'bricks' ),
			]
		];

		$this->controls['move_focus'] = [
			'tab'   => 'content',
			'inline' => true,
			'group' => 'config',
			//'default' => 'false',
			'placeholder' => esc_html__( 'Offcanvas inner', 'bricks' ),
			'label' => esc_html__( 'Move focus to offcanvas', 'bricks' ),
			'type'  => 'select',
			'options' => [
				'content' => esc_html__( 'Offcanvas inner', 'bricks' ),
				'selector' => esc_html__( 'Selector inside offcanvas', 'bricks' ),
				'disable' => esc_html__( 'Disable', 'bricks' ),
			]
		];

		$this->controls['trapFocus'] = [
			'tab'   => 'content',
			'inline' => true,
			'group' => 'config',
			'placeholder' => esc_html__( 'Enable', 'bricks' ),
			'label' => esc_html__( 'Trap focus', 'bricks' ),
			'type'  => 'select',
			'options' => [
				'true' => esc_html__( 'Enable', 'bricks' ),
				'false' => esc_html__( 'Disable', 'bricks' ),
			]
		];

		$this->controls['returnFocus'] = [
			'tab'   => 'content',
			'inline' => true,
			'group' => 'config',
			'placeholder' => esc_html__( 'Enable', 'bricks' ),
			'label' => esc_html__( 'Return focus to trigger on close', 'bricks' ),
			'type'  => 'select',
			'options' => [
				'true' => esc_html__( 'Enable', 'bricks' ),
				'false' => esc_html__( 'Disable', 'bricks' ),
			]
		];

		$this->controls['focus_selector'] = [
			'tab' => 'content',
			'group' => 'config',
			'label' => esc_html__( 'Focus selector', 'bricks' ),
			//'inline' => true,
			'type' => 'text',
			//'default' => esc_html__( '.other-offcanvas', 'bricks' ),
			'placeholder' => esc_html__( 'a', 'bricks' ),
			'required' => ['move_focus', '=', 'selector'],
			'hasDynamicData' => false
		  ];

		  $this->controls['preventScroll'] = [
			'tab'   => 'content',
			'inline' => true,
			'group' => 'config',
			//'default' => 'false',
			'placeholder' => esc_html__( 'Disable', 'bricks' ),
			'label' => esc_html__( 'Prevent scroll when open', 'bricks' ),
			'type'  => 'select',
			'options' => [
				'true' => esc_html__( 'Enable', 'bricks' ),
				'false' => esc_html__( 'Disable', 'bricks' ),
			]
		];

		  $this->controls['sync_burger_triggers'] = [
			'tab'   => 'content',
			'inline' => true,
			'group' => 'config',
			//'default' => 'false',
			'placeholder' => esc_html__( 'Disable', 'bricks' ),
			'label' => esc_html__( 'Sync burger triggers', 'bricks' ),
			'type'  => 'select',
			'options' => [
				'true' => esc_html__( 'Enable', 'bricks' ),
				'false' => esc_html__( 'Disable', 'bricks' ),
			]
		];

		
		$this->controls['force_second_close'] = [
			'tab'   => 'content',
			'inline' => true,
			'group' => 'config',
			//'default' => 'false',
			'placeholder' => esc_html__( 'Disable', 'bricks' ),
			'label' => esc_html__( 'Force second offcanvas to close', 'bricks' ),
			'type'  => 'select',
			'options' => [
				'true' => esc_html__( 'Enable', 'bricks' ),
				'false' => esc_html__( 'Disable', 'bricks' ),
			]
		];

		$this->controls['second_offcanvas_selector'] = [
			'tab' => 'content',
			'group' => 'config',
			'label' => esc_html__( 'Offcanvas selector', 'bricks' ),
			//'inline' => true,
			'type' => 'text',
			//'default' => esc_html__( '.other-offcanvas', 'bricks' ),
			'placeholder' => esc_html__( '.other-offcanvas', 'bricks' ),
			'required' => ['force_second_close', '=', 'true'],
		  ];

		/* backdrop */

		$this->controls['disable_backdrop'] = [
			'tab'   => 'content',
			'inline' => true,
			'small' => true,
			'group' => 'backdrop',
			'label' => esc_html__( 'Disable backdrop', 'bricks' ),
			'type'  => 'checkbox',

		];

		$this->controls['backdrop_to_close'] = [
			'tab'   => 'content',
			'inline' => true,
			'group' => 'backdrop',
			//'default' => 'true',
			'placeholder' => esc_html__( 'Enable', 'bricks' ),
			'label' => esc_html__( 'Click backdrop to close', 'bricks' ),
			'type'  => 'select',
			'options' => [
				'true' => esc_html__( 'Enable', 'bricks' ),
				'false' => esc_html__( 'Disable', 'bricks' ),
			],
			'required' => ['disable_backdrop', '!=', true],
		];

		$this->controls['backdrop_color'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Backdrop color', 'bricks' ),
			'type' => 'color',
			'group' => 'backdrop',
			'inline' => true,
			'css' => [
			  [
				'property' => 'background-color',
				'selector' => '.x-offcanvas_backdrop',
			  ]
			],
			'required' => ['disable_backdrop', '!=', true],
		  ];

		  $this->controls['backdrop_zindex'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Z-index', 'bricks' ),
			'type' => 'number',
			'group' => 'backdrop',
			'min' => 0,
			'max' => 1000,
			'step' => 1, // Default: 1
			'inline' => true,
			//'default' => 10,
			'css' => [
				[
				'property' => 'z-index',
				'selector' => '.x-offcanvas_backdrop',
				],
			],
			'placeholder' => esc_html__( '10', 'bricksextras' ),
			'required' => ['disable_backdrop', '!=', true],
		  ];



		  /* Accessibility */


		  $this->controls['aria_label'] = [
			'tab' => 'content',
			'group' => 'accessibility',
			'label' => esc_html__( 'Aria label', 'bricks' ),
			'inline' => true,
			'type' => 'text',
			//'default' => esc_html__( 'offcanvas content', 'bricks' ),
			'placeholder' => esc_html__( 'Offcanvas', 'bricks' )
		  ];

		 

		$this->controls['auto_aria_control'] = [
			'tab'   => 'content',
			'inline' => true,
			'group' => 'accessibility',
			//'default' => 'false',
			'placeholder' => esc_html__( 'Enable', 'bricks' ),
			'label' => esc_html__( 'Add aria-controls to trigger', 'bricks' ),
			'type'  => 'select',
			'options' => [
				'true' => esc_html__( 'Enable', 'bricks' ),
				'false' => esc_html__( 'Disable', 'bricks' ),
			]
		];

		$this->controls['reduce_motion'] = [
			'tab'   => 'content',
			'inline' => true,
			'group' => 'accessibility',
			//'default' => 'false',
			'placeholder' => esc_html__( 'Slide', 'bricks' ),
			'label' => esc_html__( "If 'Reduce motion' enabled", 'bricks' ),
			'type'  => 'select',
			'options' => [
				'fade' => esc_html__( 'Fade', 'bricks' ),
				'slide' => esc_html__( 'Slide', 'bricks' ),
				'notransition' => esc_html__( 'No transition', 'bricks' )
			]
		];

		$this->controls['tag'] = [
			'tab'       => 'content',
			'label'     => esc_html__( 'Inner content tag', 'bricks' ),
			'type'        => 'text',
			'hasDynamicData' => false,
			'group' => 'accessibility',
			'inline'    => true,
			'clearable' => false,
			'placeholder'   => 'div',
		];

		$this->controls['role'] = [
			'tab'       => 'content',
			'label'     => esc_html__( 'Inner content role', 'bricks' ),
			'type'        => 'text',
			'hasDynamicData' => false,
			'group' => 'accessibility',
			'inline'    => true,
			'clearable' => false,
			'placeholder'   => 'dialog',
		];

		

		  $this->controls['justifyContent'] = [
			'tab'   => 'content',
			'label' => esc_html__( 'Justify content', 'bricks' ),
			'group' => 'layout',
			'type'  => 'justify-content',
			'css'   => [
			  [
				'property' => 'justify-content',
				'selector' => '.x-offcanvas_inner',
			  ],
			],
		     'isHorizontal' => false,
			// 'exclude' => [
			  // 'flex-start',
			  // 'center',
			  // 'flex-end',
			  // 'space-between',
			  // 'space-around',
			  // 'space-evenly',
			// ],
		  ];

		  $this->controls['alignItems'] = [ // Setting key
			'tab'   => 'content',
			'label' => esc_html__( 'Align items', 'bricks' ),
			'group' => 'layout',
			'type'  => 'align-items',
			'css'   => [
			  [
				'property' => 'align-items',
				'selector' => '.x-offcanvas_inner',
			  ],
			],
		    'isHorizontal' => false,
			// 'exclude' => [
			  // 'flex-start',
			  // 'center',
			  // 'flex-end',
			  // 'space-between',
			  // 'space-around',
			  // 'space-evenly',
			// ],
		  ];

		  $this->controls['content_padding'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Content padding', 'bricks' ),
			'type' => 'dimensions',
			'group' => 'layout',
			'css' => [
			  [
				'property' => 'padding',
				'selector' => '.x-offcanvas_inner',
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

		wp_enqueue_script( 'x-offcanvas', BRICKSEXTRAS_URL . 'components/assets/js/' . \BricksExtras\Helpers::maybeMinifyScripts('offcanvas') . '.js', '', '1.1.1', true );
		wp_enqueue_script( 'x-offcanvas-inert', BRICKSEXTRAS_URL . 'components/assets/js/' . \BricksExtras\Helpers::maybeMinifyScripts('inert') . '.js', '', '1.0.0', true );

		wp_localize_script(
			'x-offcanvas',
			'xProOffCanvas',
			[
				'Instances' => [],
			]
		);

		if (! \BricksExtras\Helpers::elementCSSAdded($this->name) ) {
			wp_enqueue_style( 'x-offcanvas', BRICKSEXTRAS_URL . 'components/assets/css/offcanvas.css', [], '' );
		}

	}

	
	public function render() {

		$direction = isset( $this->settings['direction'] ) ? esc_attr( $this->settings['direction'] ) : 'x-offcanvas_left';
		$aria_label = isset( $this->settings['aria_label'] ) ? esc_attr__( $this->settings['aria_label'] ) : 'Offcanvas';
		$role = isset( $this->settings['role'] ) ? esc_attr( $this->settings['role'] ) : 'dialog';
		$move_focus = isset( $this->settings['move_focus'] ) ? $this->settings['move_focus'] : 'content';
		$focus_selector = isset( $this->settings['focus_selector'] ) ? esc_attr( $this->settings['focus_selector'] ) : 'a';
		$second_offcanvas_selector = isset( $this->settings['second_offcanvas_selector'] ) ? esc_attr( $this->settings['second_offcanvas_selector'] ) : '.other-offcanvas';

		$maybe_hash_close = isset( $this->settings['maybe_hash_close'] ) ? $this->settings['maybe_hash_close'] : 'true';

		$transitionType = isset( $this->settings['transitionType'] ) ? esc_attr( $this->settings['transitionType'] ) : 'slide';

		$offcanvas_config = [
			'clickTrigger' => isset( $this->settings['clickTrigger'] ) ? esc_attr( $this->settings['clickTrigger'] ) : '',
			'preventScroll' => isset( $this->settings['preventScroll'] ) ? $this->settings['preventScroll'] : false,
			'returnFocus' =>  isset( $this->settings['returnFocus'] ) ? 'enable' === $this->settings['returnFocus'] : true,
		];

		if ( isset( $this->settings['esc_to_close'] ) ) {
			$offcanvas_config += [ 'escClose' => $this->settings['esc_to_close'] ];
		}

		if ( isset( $this->settings['trapFocus'] ) ) {
			$offcanvas_config += [ 'trapFocus' => $this->settings['trapFocus'] ];
		}

		if ( isset( $this->settings['backdrop_to_close'] ) ) {
			$offcanvas_config += [ 'backdropClose' => $this->settings['backdrop_to_close'] ];
		}

		if ( isset( $this->settings['auto_aria_control'] ) ) {
			$offcanvas_config += [ 'autoAriaControl' => $this->settings['auto_aria_control'] ];
		}

		if ( isset( $this->settings['force_second_close'] ) && 'true' === $this->settings['force_second_close'] ) {
			$offcanvas_config += [ 'secondClose' => $second_offcanvas_selector ];
		}
		
		if ( isset( $this->settings['sync_burger_triggers'] ) && 'true' === $this->settings['sync_burger_triggers'] ) {
			$offcanvas_config += [ 'syncBurgers' => $this->settings['sync_burger_triggers'] ];
		}

		if ( isset( $this->settings['reduce_motion'] ) ) {
			$offcanvas_config += [ 'reduceMotion' => $this->settings['reduce_motion']];
		}

		if ( 'selector' === $move_focus ) {
			$offcanvas_config += [ 'focus' => $focus_selector ];
		} else if ( 'disable' === $move_focus ) {
			$offcanvas_config += [ 'focus' => 'false' ];
		}

		if ( 'false' === $maybe_hash_close ) {
			$offcanvas_config += [ 'disableHashlink' => 'true' ];
		}

		$template_id = ! empty( $this->settings['offcanvas_template'] ) ? intval( $this->settings['offcanvas_template'] ) : false;

		$preventScroll = isset( $this->settings['preventScroll'] ) ? $this->settings['preventScroll'] : false;

		if ( $preventScroll ) {
			$this->set_attribute( 'x-offcanvas_inner', 'data-lenis-prevent', '' );
		}
		
		
		$this->set_attribute( '_root', 'class', 'x-offcanvas' );
		$this->set_attribute( 'x-offcanvas_inner', 'class', 'x-offcanvas_inner ' . $direction );
		$this->set_attribute( 'x-offcanvas_inner', 'aria-hidden', 'false' );
		$this->set_attribute( 'x-offcanvas_inner', 'aria-label', $aria_label );
		$this->set_attribute( 'x-offcanvas_inner', 'role', $role );
		$this->set_attribute( 'x-offcanvas_inner', 'inert', '' );
		$this->set_attribute( 'x-offcanvas_inner', 'tabindex', '0' );
		$this->set_attribute( 'x-offcanvas_inner', 'data-type', $transitionType );
		
		$this->set_attribute( 'x-offcanvas_backdrop', 'class', 'x-offcanvas_backdrop' );

		/* get element ID of the most deep looped element */
		if ( method_exists('\Bricks\Query','is_any_looping') ) {

			$query_id = \Bricks\Query::is_any_looping();

			if ( $query_id ) {

				$offcanvas_config += [ 'isLooping' => \Bricks\Query::get_query_element_id( $query_id ) ];
				$offcanvas_config += [ 'loopIndex' => \Bricks\Query::get_loop_index()  ];

				if ( BricksExtras\Helpers::get_bricks_looping_parent_query_id_by_level(2) ) {
					$loopIndex = \Bricks\Query::get_query_for_element_id( \Bricks\Query::get_query_element_id( BricksExtras\Helpers::get_bricks_looping_parent_query_id_by_level(2) ) )->loop_index . '_' . \Bricks\Query::get_query_for_element_id( \Bricks\Query::get_query_element_id( BricksExtras\Helpers::get_bricks_looping_parent_query_id_by_level(1) ) )->loop_index . '_' . \Bricks\Query::get_loop_index();
				} else {
					if ( BricksExtras\Helpers::get_bricks_looping_parent_query_id_by_level(1) ) {
						$loopIndex = \Bricks\Query::get_query_for_element_id( \Bricks\Query::get_query_element_id( BricksExtras\Helpers::get_bricks_looping_parent_query_id_by_level(1) ) )->loop_index . '_' . \Bricks\Query::get_loop_index();
					} else {
						$loopIndex = \Bricks\Query::get_loop_index();
					}
				}			

				$this->set_attribute( 'x-offcanvas_inner', 'id', "x-offcanvas_inner-{$this->id}" . '_' . $loopIndex );
				$this->set_attribute( '_root', 'data-x-id', $this->id . '_' . $loopIndex );
				
			} else {
				$this->set_attribute( 'x-offcanvas_inner', 'id', "x-offcanvas_inner-{$this->id}" );
				$this->set_attribute( '_root', 'data-x-id', $this->id );
			}

		} 

		$this->set_attribute( '_root', 'data-x-offcanvas', wp_json_encode( $offcanvas_config ) );

		$tag = isset( $this->settings['tag'] ) ? \Bricks\Helpers::sanitize_html_tag( $this->settings['tag'], 'div' ) : 'div';
		
		echo "<div {$this->render_attributes( '_root' )}>";

		echo "<" . $tag . " {$this->render_attributes( 'x-offcanvas_inner' )}>";
		
		if ( method_exists('\Bricks\Frontend','render_children') ) {
				echo \Bricks\Frontend::render_children( $this );
		}  

		echo "</" . $tag . ">";

		echo isset( $this->settings['disable_backdrop'] ) ? "" : "<div {$this->render_attributes( 'x-offcanvas_backdrop' )}></div>";

		echo "</div>";
		
		

	}

	
	public static function render_builder() { ?>

		<script type="text/x-template" id="tmpl-bricks-element-xoffcanvasnestable">
			<component
				v-if="!settings.builderHidden"
				class="x-offcanvas x-offcanvas-nestable"
			>
				<div 
					class="x-offcanvas_backdrop"
					v-if="!settings.disable_backdrop"
				></div>
				<div 
					class="x-offcanvas_inner"
					:class="settings.direction"
				>
				<bricks-element-children
						:element="element"
					/>
				
				</div>
			</component>	
		</script>

	<?php }

}