<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class X_Header_Row extends \Bricks\Element {

  // Element properties
  public $category     = 'extras';
	public $name         = 'xheaderrow';
	public $icon         = 'ti-line-double';
	public $css_selector = '';
	//public $scripts      = ['xHeaderRow'];
  public $nestable = true;

  
  public function get_label() {
	  return esc_html__( 'Header Row', 'extras' );
  }
  public function set_control_groups() {

    $this->control_groups['stickyGroup'] = [
        'title' => esc_html__( 'Sticky on scroll', 'extras' ),
        'tab' => 'content',
    ];

    $this->control_groups['overlayGroup'] = [
        'title' => esc_html__( 'Overlay', 'extras' ),
        'tab' => 'content',
    ];

  }

  public function set_controls() {

    $this->controls['height'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Height', 'extras' ),
			'inline'      => true,
			'small'		  => true,
			'type' => 'number',
			'units'    => true,
			'css' => [
			  [
				'property' => 'height',
			  ],
			],
		  ];


      $this->controls['headerBackground'] = [
        'tab'    => 'content',
        'type'   => 'background',
        'label'  => esc_html__( 'Background', 'extras' ),
        'css'    => [
          [
            'property' => 'background-color',
            'selector' => '',
          ],
        ],
      ];

      $this->controls['headerTypography'] = [
        'tab'    => 'content',
        'type'   => 'color',
        'label'  => esc_html__( 'Typography', 'extras' ),
        'css'    => [
          [
            'property' => 'font',
            'selector' => '',
          ],
        ],
      ];

      $this->controls['headerBoxShadow'] = [
        'tab'    => 'content',
        'label'  => esc_html__( 'Box Shadow', 'extras' ),
        'type'   => 'box-shadow',
        'css'    => [
            [
                'property' => 'box-shadow',
              ],
        ],
    ];

      $this->controls['maybeFullWidth'] = [
        'tab'   => 'content',
        'label' => esc_html__( 'Full width content', 'bricks' ),
        'type'  => 'checkbox',
        'css'    => [
            [
              'property' => 'width',
              'selector' => '.brxe-container',
              'value' => '100%'
            ],
          ],
      ];

      /* Overlay header */

      $this->controls['overlaySep'] = array(
        'group' => 'overlayGroup',
        'type'  => 'separator',
        'label' => esc_html__( 'Overlay Header', 'bricks' ),
        'description' => esc_html__( 'Change how this header row behaves if inside an overlay header.', 'bricks' ),
      );

      $this->controls['overlayDisplay'] = [
        'tab' => 'content',
        'label' => esc_html__( 'Overlay display', 'bricks' ),
        'type' => 'select',
        'options' => [
          'hide' => esc_html__( 'Hide in overlay header', 'bricks' ),
          'show' => esc_html__( 'Only show in overlay header', 'bricks' ),
          'always' => esc_html__( 'Always show', 'bricks' ),
        ],
        'inline'      => true,
        
        'placeholder' => esc_html__( 'Always show', 'bricks' ),
        'group'		  => 'overlayGroup',
        'info' => esc_html__( 'Conditionally show/hide the header row if the header is overlay', 'bricks' ),
      ];


      $this->controls['overlayBackground'] = [
        'tab'    => 'content',
        'type'   => 'color',
        'label'  => esc_html__( 'Overlay background', 'extras' ),
        'css'    => [
            [
                'property' => '--x-overlay-header-background',
            ],
        ],
        'group'		  => 'overlayGroup',
        'info' => esc_html__( 'If wishing for the header to have a background color when the header is overlay', 'bricks' ),
    ];


      /* Sticky header */

      $this->controls['stickyDisplay'] = [
        'tab' => 'content',
        'label' => esc_html__( 'Sticky display', 'bricks' ),
        'type' => 'select',
        'options' => [
          'hide' => esc_html__( 'Hide in sticky header', 'bricks' ),
          'show' => esc_html__( 'Only show in sticky header', 'bricks' ),
          'always' => esc_html__( 'Always show', 'bricks' ),
        ],
        'group'		  => 'stickyGroup',
        'inline'      => true,
        'placeholder' => esc_html__( 'Always show', 'bricks' ),
      ];

      $this->controls['stickySep'] = array(
        'group' => 'stickyGroup',
        'type'  => 'separator',
        'label' => esc_html__( 'Styles when sticky', 'bricks' ),
      );

      $this->controls['stickyPreview'] = [
        'group' => 'stickyGroup',
        'tab'   => 'content',
        'inline' => true,
        'small' => true,
        'label' => esc_html__( 'Preview sticky styles', 'bricks' ),
        'type'  => 'checkbox',
    ];


      $this->controls['stickyHeight'] = [
        'tab' => 'content',
        'label' => esc_html__( 'Height', 'extras' ),
        'inline'      => true,
        'small'		  => true,
        'type' => 'number',
        'units'    => true,
        'css' => [
          [
            'selector' => '&[data-x-sticky-active*=true]',
            'property' => 'height',
            'important' => true
          ],
          [
            'property' => '--x-sticky-header-height',
            'important' => true
          ],
        ],
        'group'		  => 'stickyGroup',
      ];

        $this->controls['stickyBackground'] = [
            'tab'    => 'content',
            'type'   => 'color',
            'label'  => esc_html__( 'Background', 'extras' ),
            'css'    => [
                [
                    'selector' => '&[data-x-sticky-active*=true]',
                    'property' => 'background',
                    'important' => true
                  ],
            ],
            'group'		  => 'stickyGroup',
        ];

        $this->controls['stickyShadow'] = [
            'tab'    => 'content',
            'label'  => esc_html__( 'Box Shadow', 'extras' ),
            'type'   => 'box-shadow',
            'css'    => [
                    [
                          'property' => 'box-shadow',
                          'selector' => '&[data-x-sticky-active*=true]',
                          'important' => true
                        ],
            ],
            'group'		  => 'stickyGroup',
		  ];

        $this->controls['stickyTypography'] = [
            'tab'    => 'content',
            'type'   => 'typography',
            'label'  => esc_html__( 'Typography', 'extras' ),
            'css'    => [
                [
                    'property' => 'font',
                    'selector' => '&[data-x-sticky-active*=true]',
                    'important' => true
                  ],
            ],
            'group'		  => 'stickyGroup',
        ];

     

      
  }

  // Methods: Frontend-specific
  public function enqueue_scripts() {
    if (! \BricksExtras\Helpers::elementCSSAdded($this->name) ) {
			wp_enqueue_style( 'x-header-row', BRICKSEXTRAS_URL . 'components/assets/css/headerrow.css', [], '' );
		  }
  }


  public function get_nestable_item() {

    return [
        'name'     => 'container',
        'label'    => esc_html__( 'Header wrap', 'bricks' ),
        'settings' => [
            'tag'  => 'div',
            '_alignItems'     => 'center',
            '_direction'      => 'row',
            '_justifyContent' => 'space-between',
            '_hidden'         => [
                '_cssClasses' => 'x-header_wrap',
            ],
        ],
    ];
  }

  public function get_nestable_children() {
    $children = [];

    for ( $i = 0; $i < 1; $i++ ) {
        $item = $this->get_nestable_item();

        // Replace {item_index} with $index
        $item       = json_encode( $item );
        $item       = str_replace( '{item_index}', $i + 1, $item );
        $item       = json_decode( $item, true );
        $children[] = $item;
    }

    return $children;
}
  
  public function render() {

    $element  = $this->element;

    $config = [];

    if ( isset( $this->settings['stickyDisplay'] ) ) {
      $config += [ 'stickyDisplay' => $this->settings['stickyDisplay'] ];
    }

    $overlayDisplay = isset( $this->settings['overlayDisplay'] ) ? esc_attr( $this->settings['overlayDisplay'] ) : "always" ;
    $stickyDisplay = isset( $this->settings['stickyDisplay'] ) ? esc_attr( $this->settings['stickyDisplay'] ) : "always" ;

    $this->set_attribute( '_root', 'data-x-overlay', $overlayDisplay );
    $this->set_attribute( '_root', 'data-x-sticky', $stickyDisplay );

    $this->set_attribute( 'x-header-wrap', 'class', 'x-header-wrap' );
    

    echo "<div {$this->render_attributes( '_root' )}>";

      if ( method_exists('\Bricks\Frontend','render_element') ) {

        if ( ! empty( $element['children'] ) && is_array( $element['children'] ) ) {

            foreach ( $element['children'] as $child_id ) {
                if ( ! array_key_exists( $child_id, \Bricks\Frontend::$elements ) ) {
                    continue;
                }

                $child = \Bricks\Frontend::$elements[ $child_id ];

                echo \Bricks\Frontend::render_element( $child );
            }

        }

    }

    echo "</div>";
    
  }

  
  public static function render_builder() { ?>

		<script type="text/x-template" id="tmpl-bricks-element-xheaderrow">

            <component
                :data-x-overlay="settings.overlayDisplay ? settings.overlayDisplay : 'always'"
                :data-x-sticky-active="settings.stickyPreview ? 'true' : 'false'"
            >
                <bricks-element-children :element="element" />

            </component>

		</script>

	<?php }

}