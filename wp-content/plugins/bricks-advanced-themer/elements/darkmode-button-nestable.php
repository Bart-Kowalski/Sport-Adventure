<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Advanced_Themer_Darkmode_Button_Nestable extends \Bricks\Element {
  // Element properties
  public $category     = 'advanced themer'; // Use predefined element category 'general'
  public $name         = 'brxc-darkmode-btn-nestable'; // Make sure to prefix your elements
  public $icon         = 'fas fa-circle-half-stroke'; // Themify icon font class
  public $css_selector = ''; // Default CSS selector
  public $scripts      = ['brxc-darkmode']; // Script(s) run when element is rendered on frontend or updated in builder
  public $nestable     = true;

  // Return localised element label
  public function get_label() {
    return esc_html__( 'Darkmode Button Nestable', 'bricks' );
  }


    // Enqueue element styles and scripts
    public function enqueue_scripts() {
      wp_enqueue_script( 'brxc-darkmode' );
      wp_enqueue_style( 'brxc-darkmode-btn-nestable' );
    }
 
  // Set builder controls
  public function set_controls() {
      if (class_exists('\Automatic_CSS\API') ) {
        $this->controls['btnCompatibilityACSS'] = [
          'tab'      => 'content',
          'label'    => esc_html__( 'Compatibility with ACSS' ),
          'type'  => 'checkbox',
          'fullAccess' => true,
        ];
      }

      $this->controls['btnLightSeperator'] = [
        'tab' => 'content',
        'label' => esc_html__( 'Light View', 'bricks' ),
        'type'  => 'separator',
      ];
      $this->controls['btnLightBackground'] = [ // Setting key
        'tab' => 'content',
        'label' => esc_html__( 'Background Color', 'bricks' ),
        'type' => 'color',
        'css' => [
          [
            'property' => 'background',
            'selector' => '.brxc-darkmode-btn-nestable__slot',
          ],
        ],
        'inline' => true,
        'small' => true,
        'default' => [
            'rgb' => 'rgba(0, 0, 0, 0)',
        ],
      ];

      $this->controls['btnDarkSeperator'] = [
        'tab' => 'content',
        'label' => esc_html__( 'Dark View', 'bricks' ),
        'type'  => 'separator',
      ];

      $this->controls['btnDarkBackground'] = [
        'tab' => 'content',
        'label' => esc_html__( 'Background Color', 'bricks' ),
        'type' => 'color',
        'css' => [
          [
            'property' => 'background',
            'selector' => '.brxc-darkmode-btn-nestable__checkbox:checked + label .brxc-darkmode-btn-nestable__slot',
          ],
        ],
        'inline' => true,
        'small' => true,
        'default' => [
            'hex' => '#353d4e',
        ],
        'placeholder' => '#353d4e',
      ];
      $this->controls['btnAriaSeperator'] = [
        'tab' => 'content',
        'label' => esc_html__( 'Accessibility', 'bricks' ),
        'type'  => 'separator',
      ];
      $this->controls['btnAriaLabel'] = [
        'tab' => 'content',
        'label' => esc_html__( 'Aria-label', 'bricks' ),
        'type' => 'text',
        'units'    => false,
        'inline' => true,
        'default' => 'Toggle Dark Mode',
      ];
      $this->controls['btnFocusOutlineWidth'] = [
        'tab' => 'content',
        'label' => esc_html__( 'Focus Outline Width', 'bricks' ),
        'type' => 'number',
        'css' => [
          [
            'property' => '--focus-outline-width',
            'selector' => '',
          ],
        ],
        'units' => true,
        'inline' => true,
        'default' => '2px',
      ];

      $this->controls['btnFocusOutlineOffset'] = [
        'tab' => 'content',
        'label' => esc_html__( 'Focus Outline Offset', 'bricks' ),
        'type' => 'number',
        'css' => [
          [
            'property' => '--focus-outline-offset',
            'selector' => '',
          ],
        ],
        'units' => true,
        'inline' => true,
        'default' => '2px',
      ];

      $this->controls['btnFocusOutlineColor'] = [
        'tab' => 'content',
        'label' => esc_html__( 'Focus Outline  Color', 'bricks' ),
        'type' => 'color',
        'css' => [
          [
            'property' => '--focus-outline-color',
            'selector' => '',
          ],
        ],
        'inline' => true,
        'default' => [
            'hex' => '#cccccc',
        ],
      ];
    
  }


  public function get_nestable_item() {

		return [
			'name'     => 'div',
			'label'    => esc_html__( 'Container', 'bricks' ),
      'settings' => [
          '_cssTransition' => 'background-color 250ms',
          '_cursor' => 'pointer',
          '_display' => 'flex',
          '_direction' => 'row',
          '_justifyContent' => 'center',
          '_alignItems' => 'center',
          '_height' => '2.5em',
          '_blockSize' => '2.5em',
          '_width' => '2.5em',
          '_inlineSize' => '2.5em',
          '_border' => [
            'radius' => [
              'top' => '100px',
              'right' => '100px',
              'bottom' => '100px',
              'left' => '100px',
            ],
            'width' => [
              'top' => '2',
              'right' => '2',
              'bottom' => '2',
              'left' => '2',
            ],
            'color' => [
              'hex' => '#353d4e',
            ],
            'style' => 'solid',
          ],
          '_borderWidthLogical' => [
            'block-start-width' => '2',
            'inline-end-width' => '2',
            'block-end-width'  => '2',
            'inline-start-width' => '2',
          ],
          '_borderStyle' => 'solid',
          '_borderColor' => [
            'hex' => '#353d4e',
          ],
          '_borderRadiusLogical' => [
            'start-start-radius' => '100px',
            'start-end-radius' => '100px',
            'end-start-radius'  => '100px',
            'end-end-radius' => '100px',
          ],
					'_cssClasses' => 'brxc-darkmode-btn-nestable__slot',

				],
			'children' => [
				[
					'name'     => 'div',
          'label'    => esc_html__( 'Wrapper Light', 'bricks' ),
					'settings' => [
            '_display' => 'flex',
            '_direction' => 'row',
            '_justifyContent' => 'center',
            '_alignItems' => 'center',
            '_cssClasses' => 'brxc-darkmode-btn-nestable__light-icon-wrapper',
					],
          'children' => [
            [
              'name'     => 'icon',
              'label'    => esc_html__( 'Icon Light', 'bricks' ),
              'settings' => [
                'icon' => [
                  'library' => 'ionicons',
                  'icon'    => 'ion-ios-sunny',
                ],
                'iconColor' => [
                  'hex' => '#000000',
                ],
                'iconSize' => '120%',
                '_cssClasses' => 'brxc-darkmode-btn-nestable__light-icon',
              ],
            ],
          ],
				],
        [
					'name'     => 'div',
          'label'    => esc_html__( 'Wrapper Dark', 'bricks' ),
					'settings' => [
            '_display' => 'flex',
            '_direction' => 'row',
            '_justifyContent' => 'center',
            '_alignItems' => 'center',
            '_cssClasses' => 'brxc-darkmode-btn-nestable__dark-icon-wrapper',
					],
          
          'children' => [
            [
              'name'     => 'icon',
              'label'    => esc_html__( 'Icon Dark', 'bricks' ),
              'settings' => [
                'icon' => [
                  'library' => 'ionicons',
                  'icon'    => 'ion-ios-moon',
                ],
                'iconColor' => [
                  'hex' => '#ffffff'
                ],
                'iconSize' => '100%',
                '_cssClasses' => 'brxc-darkmode-btn-nestable__dark-icon',
              ],
            ],
          ],
				],
			],
		];
	}

  public function get_nestable_children() {
		$children = [];

		for ( $i = 0; $i < 1; $i++ ) {
			$item = $this->get_nestable_item();
			$children[] = $item;
		}

		return $children;
	}

  // Render element HTML
  public function render() {
    // Set element attributes
    $root_classes[] = 'true';
    $settings = $this->settings;
    $aria_label    = ! empty( $settings['btnAriaLabel'] ) ? 'aria-label="' . $settings['btnAriaLabel'] . '"' : '';

    // Add 'class' attribute to element root tag
    $this->set_attribute( '_root', 'data-no-animation', $root_classes );

    // Render element HTML
    $output = '';
    $output .= "<div {$this->render_attributes( '_root' )}>";
    $output .= '<input id="brxcDarkmodeBtn' . $this->element['id'] . '" class="brxc-darkmode-btn-nestable__checkbox" type="checkbox" ' . $aria_label . ' tabindex="0" aria-labelledby="brxcDarkmodeBtn' . $this->element['id'] . '" style="display:none;"></input>';
    $output .= '<label for="brxcDarkmodeBtn' . $this->element['id'] . '" class="brxc-darkmode-btn-nestable__label">';
    $output .=  \bricks\Frontend::render_children( $this );
    $output .= '</label>';
    $output .= '<div class="brxc-darkmode-btn-nestable____status" aria-live="polite"></div>';
    $output .= '</div>';

    echo $output;
  }

}