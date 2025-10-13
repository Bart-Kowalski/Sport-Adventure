<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Advanced_Themer_Darkmode_Toggle_Nestable extends \Bricks\Element {
  // Element properties
  public $category     = 'advanced themer'; // Use predefined element category 'general'
  public $name         = 'brxc-darkmode-toggle-nestable'; // Make sure to prefix your elements
  public $icon         = 'fas fa-toggle-off'; // Themify icon font class
  public $css_selector = ''; // Default CSS selector
  public $scripts      = ['brxc-darkmode']; // Script(s) run when element is rendered on frontend or updated in builder
  public $nestable     = true;

  // Return localised element label
  public function get_label() {
    return esc_html__( 'Darkmode Toggle Nestable', 'bricks' );
  }

  // Enqueue element styles and scripts
  public function enqueue_scripts() {
    wp_enqueue_script( 'brxc-darkmode' );
    wp_enqueue_style( 'brxc-darkmode-toggle-nestable' );
  }

  // Set builder controls
  public function set_controls() {

    $this->controls['toggleGeneralSeperator'] = [
      'tab' => 'content',
      'label' => esc_html__( 'General', 'bricks' ),
      'type'  => 'separator',
    ];
    $this->controls['toggleGeneralPadding'] = [
      'tab' => 'content',
      'label' => esc_html__( 'Toggle Padding', 'bricks' ),
      'type' => 'number',
      'css' => [
        [
          'property' => '--toggle-padding',
        ],
      ],
      'inline' => true,
      'utils' => true,
      'default' => '0px',
    ];
    $this->controls['toggleGeneralSize'] = [
      'tab' => 'content',
      'label' => esc_html__( 'Floating Button Size', 'bricks' ),
      'type' => 'number',
      'css' => [
        [
          'property' => '--toggle-size',
        ],
      ],
      'inline' => true,
      'utils' => true,
      'default' => '33px',
    ];
    $this->controls['togglelightSeperator'] = [
        'tab' => 'content',
        'label' => esc_html__( 'Light View', 'bricks' ),
        'type'  => 'separator',
      ];
      $this->controls['togglelightBackground'] = [
        'tab' => 'content',
        'label' => esc_html__( 'Background Color', 'bricks' ),
        'type' => 'color',
        'css' => [
          [
            'property' => 'background',
            'selector' => '.brxc-darkmode-toggle-nestable__slot',
          ],
        ],
        'inline' => true,
        'small' => true,
        'default' => [
            'rgb' => 'rgba(0, 0, 0, 0)',
        ],
      ];
      $this->controls['togglelightBtnBackground'] = [
        'tab' => 'content',
        'label' => esc_html__( 'Floating Button Background Color', 'bricks' ),
        'type' => 'color',
        'css' => [
          [
            'property' => '--light-floating-bg',
          ],
        ],
        'small' => true,
        'inline' => true,
        'default' => [
            'hex' => '#ffb061',
        ],
      ];
      $this->controls['togglelightOutlineSize'] = [
        'tab' => 'content',
        'label' => esc_html__( 'Floating Button Outline Size', 'bricks' ),
        'type' => 'number',
        'css' => [
          [
            'property' => '--light-border-size',
          ],
        ],
        'inline' => true,
        'utils' => true,
        'default' => '3px',
      ];
      $this->controls['togglelightOutlineColor'] = [
        'tab' => 'content',
        'label' => esc_html__( 'Floating Button Outline Color', 'bricks' ),
        'type' => 'color',
        'css' => [
          [
            'property' => '--light-border-color',
          ],
        ],
        'inline' => true,
        'small' => true,
        'default' => [
            'hex' => '#ffd64f',
        ],
      ];

      $this->controls['toggledarkSeperator'] = [
        'tab' => 'content',
        'label' => esc_html__( 'Dark View', 'bricks' ),
        'type'  => 'separator',
      ];

      $this->controls['toggledarkBackground'] = [
        'tab' => 'content',
        'label' => esc_html__( 'Background Color', 'bricks' ),
        'type' => 'color',
        'css' => [
          [
            'property' => 'background',
            'selector' => '.brxc-darkmode-toggle-nestable__checkbox:checked + label .brxc-darkmode-toggle-nestable__slot',
          ],
        ],
        'inline' => true,
        'small' => true,
        'default' => [
            'hex' => '#353d4e',
        ],
        'placeholder' => '#353d4e',
      ];
      $this->controls['toggledarkBtnBackground'] = [
        'tab' => 'content',
        'label' => esc_html__( 'Floating Button Background Color', 'bricks' ),
        'type' => 'color',
        'css' => [
          [
            'property' => '--dark-floating-bg',
          ],
        ],
        'inline' => true,
        'small' => true,
        'default' => [
            'hex' => '#353d4e',
        ],
      ];

      $this->controls['toggleDarkOutlineSize'] = [
        'tab' => 'content',
        'label' => esc_html__( 'Floating Button Outline Size', 'bricks' ),
        'type' => 'number',
        'css' => [
          [
            'property' => '--dark-border-size',
          ],
        ],
        'inline' => true,
        'utils' => true,
        'default' => '3px',
      ];
      $this->controls['toggleDarkOutlineColor'] = [
        'tab' => 'content',
        'label' => esc_html__( 'Floating Button Outline Color', 'bricks' ),
        'type' => 'color',
        'css' => [
          [
            'property' => '--dark-border-color',
          ],
        ],
        'inline' => true,
        'small' => true,
        'default' => [
            'hex' => '#ffffff',
        ],
      ];

      $this->controls['toggleAriaSeperator'] = [
        'tab' => 'content',
        'label' => esc_html__( 'Accessibility', 'bricks' ),
        'type'  => 'separator',
      ];
      $this->controls['toggleAriaLabel'] = [
        'tab' => 'content',
        'label' => esc_html__( 'Aria-label', 'bricks' ),
        'type' => 'text',
        'units'    => false,
        'inline' => true,
        'default' => 'Toggle Dark Mode',
      ];
      $this->controls['toggleFocusOutlineWidth'] = [
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

      $this->controls['toggleFocusOutlineOffset'] = [
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

      $this->controls['toggleFocusOutlineColor'] = [
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
          '_width' => '5em',
          '_inlineSize' => '5em',
          '_height' => 'var(--toggle-size)',
          '_blockSize' => 'var(--toggle-size)',
          '_display' => 'flex',
          '_direction' => 'row',
          '_alignItems' => 'center',
          '_position' => 'relative',
          '_border' => [
            'radius' => [
              'top' => '25px',
              'right' => '25px',
              'bottom' => '25px',
              'left' => '25px',
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
            'start-start-radius' => '25px',
            'start-end-radius' => '25px',
            'end-start-radius'  => '25px',
            'end-end-radius' => '25px',
          ],
					'_cssClasses' => 'brxc-darkmode-toggle-nestable__slot',

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
            '_transform' => [
              'translateX' => '70%',
            ],
            '_cssTransition' => 'opacity 150ms, transform 500ms cubic-bezier(.26,2,.46,.71)',
            '_cssClasses' => 'brxc-darkmode-toggle-nestable__light-icon-wrapper',
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
                '_cssClasses' => 'brxc-darkmode-toggle-nestable__light-icon',
              ],
            ],
          ],
				],
        [
					'name'     => 'div',
          'label'    => esc_html__( 'Floating Button', 'bricks' ),
					'settings' => [
            '_position' => 'absolute',
            '_height' => 'var(--toggle-final-size)',
            '_blockSize' => 'var(--toggle-final-size)',
            '_border' => [
              'radius' => [
                'top' => '50%',
                'right' => '50%',
                'bottom' => '50%',
                'left' => '50%',
              ],
            ],
          '_borderRadiusLogical' => [
            'start-start-radius' => '50%',
            'start-end-radius' => '50%',
            'end-start-radius'  => '50%',
            'end-end-radius' => '50%',
          ],
            '_aspectRatio' => '1',
            '_cssTransition' => 'background-color 250ms, border-color 250ms, left 500ms cubic-bezier(.26,2,.46,.71)',
            '_cssClasses' => 'brxc-darkmode-toggle-nestable__floating-button',
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
            '_position' => 'absolute',
            '_right' => '0',
            '_insetLogical' => [
              'inline-end' => '0'
            ],
            '_cssTransition' => 'opacity 150ms, transform 500ms cubic-bezier(.26,2.5,.46,.71)',
            '_cssClasses' => 'brxc-darkmode-toggle-nestable__dark-icon-wrapper',
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
                '_display' => 'flex',
                '_direction' => 'row',
                '_justifyContent' => 'center',
                '_alignItems' => 'center',
                '_position' => 'absolute',
                '_transform' => [
                  'translateX' => '-150%',
                ],
                '_cssClasses' => 'brxc-darkmode-toggle-nestable__dark-icon',
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
    $aria_label    = ! empty( $settings['toggleAriaLabel'] ) ? 'aria-label="' . $settings['toggleAriaLabel'] . '"' : '';

    // Add 'class' attribute to element root tag
    $this->set_attribute( '_root', 'data-no-animation', $root_classes );

    // Render element HTML
    $output = '';
    $output .= "<div {$this->render_attributes( '_root' )}>";
    $output .= '<input id="brxcDarkmodeBtn' . $this->element['id'] . '" class="brxc-darkmode-toggle-nestable__checkbox" type="checkbox" ' . $aria_label . ' tabindex="0" aria-labelledby="brxcDarkmodeBtn' . $this->element['id'] . '" style="display:none;"></input>';
    $output .= '<label for="brxcDarkmodeBtn' . $this->element['id'] . '" class="brxc-darkmode-toggle-nestable__label">';
    $output .=  \bricks\Frontend::render_children( $this );
    $output .= '</label>';
    $output .= '<div class="brxc-darkmode-toggle-nestable____status" aria-live="polite"></div>';
    $output .= '</div>';

    echo $output;
  }
}