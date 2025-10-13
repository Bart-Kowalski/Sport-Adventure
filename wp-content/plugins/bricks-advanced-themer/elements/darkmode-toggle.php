<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Advanced_Themer_Darkmode_Toggle extends \Bricks\Element {
  // Element properties
  public $category     = 'advanced themer'; // Use predefined element category 'general'
  public $name         = 'brxc-darkmode-toggle'; // Make sure to prefix your elements
  public $icon         = 'fas fa-toggle-off'; // Themify icon font class
  public $css_selector = ''; // Default CSS selector
  public $scripts      = ['brxc-darkmode']; // Script(s) run when element is rendered on frontend or updated in builder

  // Return localised element label
  public function get_label() {
    return esc_html__( 'Darkmode Toggle (Legacy)', 'bricks' );
  }

  // Set builder control groups
  public function set_control_groups() {
    $this->control_groups['general'] = [ // Unique group identifier (lowercase, no spaces)
      'title' => esc_html__( 'General', 'bricks' ), // Localized control group title
      'tab' => 'content', // Set to either "content" or "style"
    ];

    $this->control_groups['sun'] = [
      'title' => esc_html__( 'Sun View', 'bricks' ),
      'tab' => 'content',
    ];

    $this->control_groups['moon'] = [
        'title' => esc_html__( 'Moon View', 'bricks' ),
        'tab' => 'content',
      ];
  }
 
  // Set builder controls
  public function set_controls() {

    $this->controls['toggleWidth'] = [
        'tab' => 'content',
        'group' => 'general',
        'label' => esc_html__( 'Wrapper Width', 'bricks' ),
        'type' => 'number',
        'units'    => true,
        'inline' => true,
        'css' => [
          [
            'property' => 'width',
            'selector' => '.brxc-toggle-slot',
          ],
        ],
        'default' => '5em',
        'placeholder' => '5em',
      ];

      $this->controls['toggleSize'] = [
        'tab' => 'content',
        'group' => 'general',
        'label' => esc_html__( 'Toggle Size', 'bricks' ),
        'type' => 'number',
        'units'    => true,
        'inline' => true,
        'css' => [
          [
            'property' => '--toggle-size',
          ],
        ],
        'default' => '35px',
        'placeholder' => '35px',
      ];

      $this->controls['togglePadding'] = [
        'tab' => 'content',
        'group' => 'general',
        'label' => esc_html__( 'Padding', 'bricks' ),
        'type' => 'number',
        'units'    => true,
        'inline' => true,
        'css' => [
          [
            'property' => '--toggle-padding',
          ],
        ],
        'default' => '0px',
        'placeholder' => '0px',
      ];

      $this->controls['toggleOutline'] = [
        'tab' => 'content',
        'group' => 'general',
        'label' => esc_html__( 'Toggle Outline', 'bricks' ),
        'type' => 'number',
        'units'    => true,
        'inline' => true,
        'css' => [
          [
            'property' => 'border-width',
            'selector' => '.brxc-toggle-slot',
          ],
        ],
        'default' => '2px',
        'placeholder' => '2px',
      ];

      $this->controls['toggleOutlineColor'] = [
        'tab' => 'content',
        'group' => 'general',
        'label' => esc_html__( 'Toggle Outline Color', 'bricks' ),
        'type' => 'color',
        'inline' => true,
        'css' => [
          [
            'property' => 'border-color',
            'selector' => '.brxc-toggle-slot',
          ]
        ],
        'default' => [
          'hex' => '#000000',
        ],
        'placeholder' => '#000000',
      ];

      $this->controls['toggleBorderRadius'] = [
        'tab' => 'content',
        'group' => 'general',
        'label' => esc_html__( 'Border Radius', 'bricks' ),
        'type' => 'number',
        'units'    => true,
        'inline' => true,
        'css' => [
          [
            'property' => 'border-radius',
            'selector' => '.brxc-toggle-slot',
          ],
          [
            'property' => 'border-radius',
            'selector' => '.brxc-toggle-button',
          ],
        ],
        'default' => '25px',
        'placeholder' => '25px',
      ];

      

      $this->controls['ToggleBoxShadow'] = [
        'tab' => 'content',
        'group' => 'general',
        'label' => esc_html__( 'Toggle BoxShadow', 'bricks' ),
        'type' => 'box-shadow',
        'css' => [
          [
            'property' => 'box-shadow',
            'selector' => '.brxc-toggle-slot',
          ],
        ],
        'inline' => true,
        'small' => true,
        'default' => [
          'values' => [
            'offsetX' => 0,
            'offsetY' => 0,
            'blur' => 0,
            'spread' => 0,
          ],
          'color' => [
            'rgb' => 'rgba(0, 0, 0, 0)',
          ],
        ],
      ];

      $this->controls['sunIcon'] = [
        'tab'     => 'content',
        'group'   => 'sun',
        'label'   => esc_html__( 'Icon', 'bricks' ),
        'type'    => 'icon',
        'root'    => true, // To target 'svg' root
        'default' => [
          'library' => 'ionicons',
          'icon'    => 'ion-ios-sunny',
        ],
      ];

      $this->controls['sunIconColor'] = [
        'tab' => 'content',
        'group' => 'sun',
        'label' => esc_html__( 'Icon Color', 'bricks' ),
        'type' => 'color',
        'inline' => true,
        'css' => [
          [
            'property' => 'fill',
            'selector' => '.brxc-sun-icon',
          ],
          [
            'property' => 'color',
            'selector' => '.brxc-sun-icon',
          ]
        ],
        'default' => [
          'hex' => '#000000',
        ],
        'placeholder' => '#000000',
      ];

      $this->controls['sunIconSize'] = [
        'tab' => 'content',
        'group' => 'sun',
        'label' => esc_html__( 'Icon Size (Scale in %)', 'bricks' ),
        'type' => 'number',
        'units'    => true,
        'unit' => '%',
        'inline' => true,
        'css' => [
          [
            'property' => 'scale',
            'selector' => '.brxc-sun-icon',
          ],
        ],
        'default' => '100%',
        'placeholder' => '100%',
      ];

      $this->controls['sunBackground'] = [ // Setting key
        'tab' => 'content',
        'group' => 'sun',
        'label' => esc_html__( 'Background Color', 'bricks' ),
        'type' => 'background',
        'css' => [
          [
            'property' => 'background',
            'selector' => '.brxc-toggle-slot',
          ],
        ],
        'inline' => true,
        'small' => true,
        'default' => [
          'color' => [
            'rgb' => 'rgba(0, 0, 0, 0)',
          ],
        ],
      ];

      $this->controls['sunToggleColor'] = [
        'tab' => 'content',
        'group' => 'sun',
        'label' => esc_html__( 'Toggle Color', 'bricks' ),
        'type' => 'color',
        'inline' => true,
        'css' => [
          [
            'property' => 'background-color',
            'selector' => '.brxc-toggle-slot .brxc-toggle-button',
          ]
        ],
        'default' => [
          'hex' => '#ffb061',
        ],
        'placeholder' => '#ffb061',
      ];

      $this->controls['sunBorderColor'] = [
        'tab' => 'content',
        'group' => 'sun',
        'label' => esc_html__( 'Toggle Border Color', 'bricks' ),
        'type' => 'color',
        'inline' => true,
        'css' => [
          [
            'property' => '--sun-border-color',
          ]
        ],
        'default' => [
          'hex' => '#fff261',
        ],
        'placeholder' => '#fff261',
      ];

      $this->controls['sunBorderSize'] = [
        'tab' => 'content',
        'group' => 'sun',
        'label' => esc_html__( 'Toggle Border Size', 'bricks' ),
        'type' => 'number',
        'units'    => true,
        'inline' => true,
        'css' => [
          [
            'property' => '--sun-border-size',
          ],
        ],
        'default' => '3px',
        'placeholder' => '3px',
      ];

      $this->controls['moonIcon'] = [
        'tab'     => 'content',
        'group'   => 'moon',
        'label'   => esc_html__( 'Icon', 'bricks' ),
        'type'    => 'icon',
        'root'    => true, // To target 'svg' root
        'default' => [
          'library' => 'ionicons',
          'icon'    => 'ion-ios-moon',
        ],
      ];

      $this->controls['moonIconColor'] = [
        'tab' => 'content',
        'group' => 'moon',
        'label' => esc_html__( 'Icon Color', 'bricks' ),
        'type' => 'color',
        'inline' => true,
        'css' => [
          [
            'property' => 'fill',
            'selector' => '.brxc-moon-icon',
          ],
          [
            'property' => 'color',
            'selector' => '.brxc-moon-icon',
          ]
        ],
        'default' => [
          'hex' => '#ffffff',
        ],
        'placeholder' => '#ffffff',
      ];

      $this->controls['moonIconSize'] = [
        'tab' => 'content',
        'group' => 'moon',
        'label' => esc_html__( 'Icon Size (Scale in %)', 'bricks' ),
        'type' => 'number',
        'units'    => true,
        'unit' => '%',
        'step' => '1',
        'inline' => true,
        'css' => [
          [
            'property' => 'scale',
            'selector' => '.brxc-moon-icon',
          ],
        ],
        'default' => '85%',
        'placeholder' => '85%',
      ];

      $this->controls['moonBackground'] = [ // Setting key
        'tab' => 'content',
        'group' => 'moon',
        'label' => esc_html__( 'Background Color', 'bricks' ),
        'type' => 'background',
        'css' => [
          [
            'property' => 'background',
            'selector' => '.brxc-toggle-checkbox:checked ~ .brxc-toggle-slot',
          ],
        ],
        'inline' => true,
        'small' => true,
        'default' => [
          'color' => [
            'hex' => '#353d4e',
          ],
        ],
        'placeholder' => '#353d4e',
      ];

      $this->controls['moonToggleColor'] = [
        'tab' => 'content',
        'group' => 'moon',
        'label' => esc_html__( 'Toggle Color', 'bricks' ),
        'type' => 'color',
        'inline' => true,
        'css' => [
          [
            'property' => 'background-color',
            'selector' => '.brxc-toggle-checkbox:checked ~ .brxc-toggle-slot .brxc-toggle-button',
          ]
        ],
        'default' => [
          'hex' => '#485367',
        ],
        'placeholder' => '#485367',
      ];

      $this->controls['moonBorderColor'] = [
        'tab' => 'content',
        'group' => 'moon',
        'label' => esc_html__( 'Toggle Border Color', 'bricks' ),
        'type' => 'color',
        'inline' => true,
        'css' => [
          [
            'property' => '--moon-border-color',
          ]
        ],
        'default' => [
          'hex' => '#ffffff',
        ],
        'placeholder' => '#ffffff',
      ];

      $this->controls['moonBorderSize'] = [
        'tab' => 'content',
        'group' => 'moon',
        'label' => esc_html__( 'Toggle Border Size', 'bricks' ),
        'type' => 'number',
        'units'    => true,
        'inline' => true,
        'css' => [
          [
            'property' => '--moon-border-size',
          ],
        ],
        'default' => '3px',
        'placeholder' => '3px',
      ];
    
  }

  // Enqueue element styles and scripts
  public function enqueue_scripts() {
    wp_enqueue_script( 'brxc-darkmode' );
    wp_enqueue_style( 'brxc-darkmode-toggle' );
  }

  // Render element HTML
  public function render() {
    // Set element attributes
    $root_classes[] = 'no-animation';
    $settings = $this->settings;
    $sun_icon     = ! empty( $settings['sunIcon'] ) ? $settings['sunIcon'] : false;
    $moon_icon    = ! empty( $settings['moonIcon'] ) ? $settings['moonIcon'] : false;

    if ( ! $moon_icon ) {
			return $this->render_element_placeholder(
				[
					'title' => esc_html__( 'No Moon icon selected.', 'bricks' ),
				]
			);
    }

    if ( ! $sun_icon ) {
			return $this->render_element_placeholder(
				[
					'title' => esc_html__( 'No Sun icon selected.', 'bricks' ),
				]
			);
    }

    $moon_icon = self::render_icon( $moon_icon, '' );
    $sun_icon = self::render_icon( $sun_icon, '' );

    $this->set_attribute( '_root', 'class', $root_classes );

    echo "<div {$this->render_attributes( '_root' )}>";
        echo '<label for="brxcDarkmodeToggle-' . $this->element['id'] . '">
            <input id="brxcDarkmodeToggle-' . $this->element['id'] . '" class="brxc-toggle-checkbox" type="checkbox" style="display:none;"></input>
            <div class="brxc-toggle-slot">
                <div class="brxc-sun-icon-wrapper">
                    <div class="brxc-sun-icon">' . $sun_icon .'</div>
                </div>
                <div class="brxc-toggle-button"></div>
                <div class="brxc-moon-icon-wrapper">
                    <div class="brxc-moon-icon">' . $moon_icon .'</div>
                </div>
            </div>
        </label>
    </div>';
  }
}