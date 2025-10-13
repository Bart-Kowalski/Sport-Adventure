<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class X_Burger_Trigger extends \Bricks\Element {

  // Element properties
  	public $category     = 'extras';
	public $name         = 'xburgertrigger';
	public $icon         = 'ti-menu';
	public $css_selector = '';

	// Methods: Builder-specific
	public function get_label() {
		return esc_html__( 'Burger Trigger', 'extras' );
	}

	public function set_control_groups() {
		 $css_added = false;
	}

	public function set_controls() {

		$animations_array = [
			'x-hamburger--3dx' => esc_html__( '3dx', 'bricks' ),
			'x-hamburger--3dy' => esc_html__( '3dy', 'bricks' ),
			'x-hamburger--arrow' => esc_html__( 'arrow', 'bricks' ),
			'x-hamburger--arrowalt' => esc_html__( 'arrow-alt', 'bricks' ),
			'x-hamburger--arrowturn' => esc_html__( 'arrowturn', 'bricks' ),
			'x-hamburger--boring' => esc_html__( 'boring', 'bricks' ),
			'x-hamburger--collapse' => esc_html__( 'collapse', 'bricks' ),
			'x-hamburger--elastic' => esc_html__( 'elastic', 'bricks' ),
			'x-hamburger--minus' => esc_html__( 'minus', 'bricks' ),
			'x-hamburger--slider' => esc_html__( 'slider', 'bricks' ),
			'x-hamburger--squeeze' => esc_html__( 'squeeze', 'bricks' ),
			'x-hamburger--vortex' => esc_html__( 'vortex', 'bricks' ),
			'x-hamburger--none' => esc_html__( 'none', 'bricks' ),
		];


		$this->controls['burger_animation'] = [
			'tab'         => 'content',
			'label'       => esc_html__( 'Animation', 'bricks' ),
			'type'        => 'select',
			'options'     => $animations_array,
			'inline'      => true,
			'placeholder'   => esc_html__( 'Slider', 'bricks' ),
			'default'     => 'x-hamburger--slider'
		];

		$this->controls['burger_scale'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Burger scale', 'bricks' ),
			'type' => 'number',
			'min' => 0,
			'max' => 1,
			'step' => '0.01', // Default: 1
			'inline' => true,
			'css' => [
				[
				'property' => '--x-burger-size',
				'selector' => '.x-hamburger-box',
				],
			],
			'placeholder' => esc_html__( '0.8', 'bricksextras' ),
		  ];

		  $this->controls['burger_line_height'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Burger line height', 'bricks' ),
			'type' => 'number',
			'min' => 0,
			'max' => 10,
			'step' => '1', // Default: 1
			'units' => true,
			'inline' => true,
			'css' => [
				[
				'property' => '--x-burger-line-height',
				'selector' => '.x-hamburger-box',
				],
			],
			'placeholder' => esc_html__( '4px', 'bricksextras' ),
		  ];

		  $this->controls['burger_line_radius'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Burger line border radius', 'bricks' ),
			'type' => 'number',
			'min' => 0,
			'max' => 10,
			'step' => '1', // Default: 1
			'units' => true,
			'inline' => true,
			'css' => [
				[
				'property' => '--x-burger-line-radius',
				'selector' => '.x-hamburger-box',
				],
			],
			'placeholder' => esc_html__( '4px', 'bricksextras' ),
		  ];

		  $this->controls['burger_line_color'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Burger line color', 'bricks' ),
			'type' => 'color',
			'inline' => true,
			'css' => [
			  [
				'property' => '--x-burger-line-color',
				'selector' => '.x-hamburger-box',
			  ]
			],
			'placeholder' => [
			  'hex' => '#111',
			],
		  ];


		  $this->controls['button_bg'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Background color', 'bricks' ),
			'type' => 'color',
			'inline' => true,
			'css' => [
			  [
				'property' => 'background-color',
				'selector' => '',
			  ]
			],
		  ];

		  $this->controls['burger_padding'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Button padding', 'bricks' ),
			'type' => 'dimensions',
			'css' => [
			  [
				'property' => 'padding',
				'selector' => '',
			  ]
			],
			'placeholder' => [
			  'top' => '10px',
			  'right' => '10px',
			  'bottom' => '10px',
			  'left' => '10px',
			],
		  ];

		  $this->controls['activeSep'] = [
			'tab'   => 'content',
			'type'  => 'separator',
			'label' => esc_html__( 'Active styles', 'bricks' ),
		];

		$this->controls['button_bg_active'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Background Color', 'bricks' ),
			'type' => 'color',
			'inline' => true,
			'css' => [
			  [
				'property' => 'background-color',
				'selector' => '',
			  ]
			],
		  ];

		  $this->controls['burger_line_color_active'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Burger line color', 'bricks' ),
			'type' => 'color',
			'inline' => true,
			'css' => [
			  [
				'property' => '--x-burger-line-color-active',
				'selector' => '.x-hamburger-box',
			  ]
			],
		  ];

		  $this->controls['textSep'] = [
			'tab'   => 'content',
			'type'  => 'separator',
		];

		$this->controls['aria_label'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Aria label', 'bricks' ),
			'type' => 'text',
			'placeholder' => esc_attr__('Open menu'),
		  ];

		  $this->controls['buttonText'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Button text', 'bricks' ),
			'type' => 'text',
		  ];  

		  $this->controls['text_padding'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Text padding', 'bricks' ),
			'type' => 'dimensions',
			'css' => [
			  [
				'property' => 'padding',
				'selector' => '.x-hamburger-text',
			  ]
			],
			'required' => ['buttonText', '!=', ''],
		  ];

		  

		  $this->controls['flexDirection'] = [
			'tab'      => 'content',
			'label'    => esc_html__( 'Direction (burger/text)', 'bricks' ),
			'tooltip'  => [
				'content'  => 'flex-direction',
				'position' => 'top-left',
			],
			'type'     => 'direction',
			'css'      => [
				[
					'property' => 'flex-direction',
					'selector' => '',
				],
			],
			'required' => ['buttonText', '!=', ''],
		];

		$this->controls['buttonTextDisplay'] = [
			'tab'      => 'content',
			'label'    => esc_html__( 'Button text display', 'bricks' ),
			'type'     => 'select',
			'inline'	=> true,
			'options'  => [
				'block' => 'Block',
				'none' => 'None',
			],
			'css'      => [
				[
					'property' => 'display',
					'selector' => '.x-hamburger-text',
				],
			],
			'required' => ['buttonText', '!=', ''],
		];

	}

	

	// Methods: Frontend-specific
	public function enqueue_scripts() {
		wp_enqueue_script( 'x-burger', BRICKSEXTRAS_URL . 'components/assets/js/' . \BricksExtras\Helpers::maybeMinifyScripts('burgertrigger') . '.js', '', '1.0.1', true );
		if (! \BricksExtras\Helpers::elementCSSAdded($this->name) ) {
			wp_enqueue_style( 'x-burger', BRICKSEXTRAS_URL . 'components/assets/css/burgertrigger.css', [], '' );
		  }
	}

	public function render() {

		$animation_class = isset( $this->settings['burger_animation'] ) ? esc_attr( $this->settings['burger_animation'] ) : '';
		$aria_label = isset( $this->settings['aria_label'] ) ? esc_attr__($this->settings['aria_label']) : esc_attr__( 'open menu' );
		$buttonText = isset( $this->settings['buttonText'] ) ? esc_attr__($this->settings['buttonText']) : '';

		if ('' === $buttonText) {
			$this->set_attribute( '_root', 'aria-label', $aria_label );
		}
		
		$this->set_attribute( 'x-hamburger-box', 'class', 'x-hamburger-box ' . $animation_class );
		$this->set_attribute( 'x-hamburger-inner', 'class', 'x-hamburger-inner' );
		$this->set_attribute( 'x-hamburger-text', 'class', 'x-hamburger-text' );

		$output = "<button {$this->render_attributes( '_root' )}>";

		$output .= "<span {$this->render_attributes( 'x-hamburger-box' )}>";
		$output .= "<span {$this->render_attributes( 'x-hamburger-inner' )}></span>";
		$output .= "</span>";
		$output .= $buttonText ? "<span {$this->render_attributes( 'x-hamburger-text' )}>" . $buttonText . "</span>" : "";
		$output .= "</button>";

		echo $output;

	}

  	public static function render_builder() { ?>

		<script type="text/x-template" id="tmpl-bricks-element-xburgertrigger">

			<component
					is="button"
					@click="is_active = !is_active"
			>
				<span class="x-hamburger-box"
					:class="settings.burger_animation + ' ' + (is_active ? 'is-active' : '')"  
				>
					<span class="x-hamburger-inner"></span>
				</span>
				<contenteditable
					v-if="settings.buttonText"
					tag="span"
					class="x-hamburger-text"
					:name="name"
					controlKey="buttonText"
					toolbar="style"
					:settings="settings"
				/>	

			</component>

		</script>

	<?php }

}