<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Advanced_Themer_Darkmode_Button extends \Bricks\Element {
  // Element properties
  public $category     = 'advanced themer'; // Use predefined element category 'general'
  public $name         = 'brxc-darkmode-btn'; // Make sure to prefix your elements
  public $icon         = 'fas fa-circle-half-stroke'; // Themify icon font class
  public $css_selector = ''; // Default CSS selector
  public $scripts      = ['brxc-darkmode']; // Script(s) run when element is rendered on frontend or updated in builder

  // Return localised element label
  public function get_label() {
    return esc_html__( 'Darkmode Button (Legacy)', 'bricks' );
  }

  // Set builder control groups
  public function set_control_groups() {
    $this->control_groups['general'] = [ // Unique group identifier (lowercase, no spaces)
      'title' => esc_html__( 'General', 'bricks' ), // Localized control group title
      'tab' => 'content', // Set to either "content" or "style"
    ];

    $this->control_groups['sun'] = [
      'title' => esc_html__( 'Light Mode View', 'bricks' ),
      'tab' => 'content',
    ];

    $this->control_groups['moon'] = [
        'title' => esc_html__( 'Dark Mode View', 'bricks' ),
        'tab' => 'content',
      ];
  }

 
  // Set builder controls
  public function set_controls() {

    $this->controls['toggleWidth'] = [
        'tab' => 'content',
        'group' => 'general',
        'label' => esc_html__( 'Wrapper Size', 'bricks' ),
        'type' => 'number',
        'units'    => true,
        'inline' => true,
        'css' => [
          [
            'property' => '--wrapper-size',
          ]
        ],
        'default' => '2.5em',
        'placeholder' => '2.5em',
      ];

      $this->controls['toggleOutline'] = [
        'tab' => 'content',
        'group' => 'general',
        'label' => esc_html__( 'Button Outline ', 'bricks' ),
        'type' => 'number',
        'units'    => true,
        'inline' => true,
        'css' => [
          [
            'property' => '--button-outline',
          ],
        ],
        'default' => '2px',
        'placeholder' => '2px',
      ];

      $this->controls['toggleOutlineColor'] = [
        'tab' => 'content',
        'group' => 'general',
        'label' => esc_html__( 'Button Outline Color', 'bricks' ),
        'type' => 'color',
        'inline' => true,
        'css' => [
          [
            'property' => '--button-outline-color',
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
            'property' => '--button-border-radius',
            'selector' => '.brxc-toggle-slot',
          ],
          [
            'property' => '--button-border-radius',
          ],
        ],
        'default' => '100px',
        'placeholder' => '100px',
      ];

      

      $this->controls['ToggleBoxShadow'] = [
        'tab' => 'content',
        'group' => 'general',
        'label' => esc_html__( 'Button Box Shadow', 'bricks' ),
        'type' => 'box-shadow',
        'css' => [
          [
            'property' => '--button-box-shadow',
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

      $this->controls['toggleAriaLabel'] = [
        'tab' => 'content',
        'group' => 'general',
        'label' => esc_html__( 'Aria-label', 'bricks' ),
        'type' => 'text',
        'units'    => false,
        'inline' => true,
        'default' => 'Toggle Dark Mode',
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
            'property' => '--light-icon-color',
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
        'inline' => true,
        'css' => [
          [
            'property' => '--light-icon-size',
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
            'selector' => '.brxc-toggle-checkbox ~ label .brxc-toggle-slot',
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
            'property' => '--dark-icon-color',
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
            'property' => '--dark-icon-size',
          ],
        ],
        'default' => '120%',
        'placeholder' => '120%',
      ];

      $this->controls['moonBackground'] = [
        'tab' => 'content',
        'group' => 'moon',
        'label' => esc_html__( 'Background Color', 'bricks' ),
        'type' => 'background',
        'css' => [
          [
            'property' => 'background',
            'selector' => '.brxc-toggle-checkbox:checked ~ label .brxc-toggle-slot',
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
    
  }

  // Enqueue element styles and scripts
  public function enqueue_scripts() {
    wp_enqueue_script( 'brxc-darkmode' );
    wp_enqueue_style( 'brxc-darkmode-btn' );
  }

  // Render element HTML
  public function render() {
    // Set element attributes
    $root_classes[] = 'no-animation';
    $settings = $this->settings;
    $sun_icon     = ! empty( $settings['sunIcon'] ) ? $settings['sunIcon'] : false;
    $moon_icon    = ! empty( $settings['moonIcon'] ) ? $settings['moonIcon'] : false;
    $aria_label    = ! empty( $settings['toggleAriaLabel'] ) ? '"aria-label="' . $settings['toggleAriaLabel'] . '"' : '';

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

    // Add 'class' attribute to element root tag
    $this->set_attribute( '_root', 'class', $root_classes );

    // Render element HTML
    echo "<div {$this->render_attributes( '_root' )}>";
        echo '<input id="brxcDarkmodeBtn-' . $this->element['id']  . '" class="brxc-toggle-checkbox" type="checkbox" ' . $aria_label . ' tab-index="0" style="display:none;"></input>';
        echo '<label for="brxcDarkmodeBtn-' . $this->element['id'] . '">
                <div class="brxc-toggle-slot">
                    <div class="brxc-sun-icon-wrapper">
                        <div class="brxc-sun-icon">' . $sun_icon .'</div>
                    </div>
                    <div class="brxc-moon-icon-wrapper">
                        <div class="brxc-moon-icon">' . $moon_icon .'</div>
                    </div>
                </div>
            </label>
            <div class="darkmode-toggle__status" aria-live="polite"></div>
       </div>';
  }
}