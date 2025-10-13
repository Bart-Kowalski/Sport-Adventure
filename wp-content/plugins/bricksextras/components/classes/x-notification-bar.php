<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class X_Notification_Bar extends \Bricks\Element {

  // Element properties
  public $category     = 'extras';
	public $name         = 'xnotificationbar';
	public $icon         = 'ti-close';
	public $css_selector = '';
  public $nestable = true;
	//public $scripts      = ['xNotificationBar'];

  
  public function get_label() {
	  return esc_html__( 'Header notification bar', 'extras' );
  }
  public function set_control_groups() {

    $this->control_groups['content'] = [
      'title' => esc_html__( 'notification Content', 'extras' ),
      'tab' => 'content',
    ];

    $this->control_groups['icon'] = [
      'title' => esc_html__( 'Dismiss Button', 'extras' ),
      'tab' => 'content',
    ];

    $this->control_groups['layout'] = [
      'title' => esc_html__( 'Layout / Spacing', 'extras' ),
      'tab' => 'content',
    ];

    $this->control_groups['stickyGroup'] = [
      'title' => esc_html__( 'Sticky on scroll', 'extras' ),
      'tab' => 'content',
  ];

  }

  public function set_controls() {


    $this->controls['notificationBackground'] = [
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

    $this->controls['notificationTypography'] = [
      'tab'    => 'content',
      'type'   => 'typography',
      'label'  => esc_html__( 'Typography', 'bricks' ),
      'css'    => [
        [
          'property' => 'font',
          'selector' => '',
        ],
      ],
    ];
  
    $this->controls['notificationBorder'] = [
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

    $this->controls['slideDuration'] = [
      'tab' => 'content',
      'label' => esc_html__( 'Slide up duration (ms)', 'bricks' ),
      'type' => 'number',
      'inline' => true,
      'small' => true,
      'units' => false,
      'placeholder' => '300',
    ];

  
    $this->controls['builderHidden_sep'] = [
      'tab'   => 'content',
      'type'  => 'separator',
      ];
  
      $this->controls['notification_content'] = [
      'tab' => 'content',
      'group' => 'content',
      'label' => esc_html__( 'Notification content', 'bricks' ),
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
  
  
    $this->controls['notification_wysiwyg'] = [
      'tab' => 'content',
      'group' => 'content',
      'label' => esc_html__( 'Content editor', 'bricks' ),
      'type' => 'editor',
  
      'default' => wp_kses_post( 'Edit me. I am a notification.'),
      'required' => ['notification_content', '!=', ['nestable', 'template']]
      ];


      $this->controls['show_again'] = [
        'tab'         => 'content',
        'label'       => esc_html__( 'Reshow notification', 'bricks' ),
        'type'        => 'select',
        'options'     => [
          'page_load' => esc_html__( 'Show on every visit', 'bricks' ),
          'dismiss' => esc_html__( 'Show again until user clicks dismiss', 'bricks' ),
          'never' => esc_html__( 'Never show again', 'bricks' ),
          'after' => esc_html__( 'Only allow to show again after:', 'bricks' ),
          'evergreen' => esc_html__( "Only show if evergreen countdown hasn't ended", 'bricks' ),
          'manual' => esc_html__( 'Manual (if using with code)', 'bricks' ),
        ],
        //'inline'      => true,
        'placeholder'   => esc_html__( 'Show on every visit', 'bricks' ),
      ];


      $this->controls['typeInfo'] = [
        'tab' => 'content',
        'description' => esc_html__( 'See the docs for details on show/close notifications in your code', 'bricks' ),
        'type' => 'info',
        'required' => ['show_again', '=', 'manual'],
      ];

      $this->controls['dismissInfo'] = [
        'tab' => 'content',
        'description' => esc_html__( 'After user has dismissed, allow to show again after:', 'bricks' ),
        'type' => 'info',
        'required' => ['show_again', '=', 'dismiss'],
      ];
    
      $this->controls['show_again_days'] = [
        'tab' => 'content',
        'label' => esc_html__( 'Days', 'bricks' ),
        'type' => 'number',
        'inline' => true,
        'required' => ['show_again', '=', ['after','dismiss']]
       ];
    
        $this->controls['show_again_hours'] = [
        'tab' => 'content',
        'label' => esc_html__( 'Hours', 'bricks' ),
        'type' => 'number',
        'inline' => true,
        'required' => ['show_again', '=', ['after','dismiss']]
        ];
      

      /* dismiss button */

      $this->controls['dismissText'] = [
        'tab' => 'content',
        'label' => esc_html__( 'Button text', 'bricks' ),
        'group' => 'icon',
        'type' => 'text',
        //'inlineEditing' => true,
        'placeholder' => '',
      ];

      $this->controls['dismiss_icon'] = [
        'tab'      => 'content',
		    'group' => 'icon',
        'label'    => esc_html__( 'Dismiss icon', 'bricks' ),
        'type'     => 'icon',
        'css'      => [
          [
            'selector' => '.x-notification_close svg',
          ],
        ],
        'default'  => [
          'library' => 'themify',
          'icon'    => 'ti-close',
        ],
      ];

      $this->controls['iconSize'] = [
        'tab'      => 'content',
		    'group' => 'icon',
        'label'    => esc_html__( 'Icon size', 'bricks' ),
        'type'     => 'number',
        'units'       => true,
        'css'      => [
          [
            'property' => 'font-size',
            'selector' => '.x-notification_close-icon',
          ],
        ],
      ];

      $icon_selector = '.x-notification_close';

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
          'top' => '5px',
          'right' => '5px',
          'bottom' => '5px',
          'left' => '5px',
        ],
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


  }

  // Methods: Frontend-specific
  public function enqueue_scripts() {

    wp_enqueue_script( 'x-frontend', BRICKSEXTRAS_URL . 'components/assets/js/' . \BricksExtras\Helpers::maybeMinifyScripts('frontend') . '.js', '', '1.0.0', true );
		wp_enqueue_script( 'x-notification-bar', BRICKSEXTRAS_URL . 'components/assets/js/' . \BricksExtras\Helpers::maybeMinifyScripts('notificationbar') . '.js', ['x-frontend'], '1.0.2', true );

    if (! \BricksExtras\Helpers::elementCSSAdded($this->name) ) {
			wp_enqueue_style( 'x-notification-bar', BRICKSEXTRAS_URL . 'components/assets/css/notificationbar.css', [], '' );
		}

	}
  
  public function render() {

    $notification_content = isset( $this->settings['notification_content'] ) ? $this->settings['notification_content'] : 'wysiwyg';
    $dismiss_icon = empty( $this->settings['dismiss_icon'] ) ? false : self::render_icon( $this->settings['dismiss_icon'] );
    $dismiss_text = isset( $this->settings['dismissText'] ) ? esc_html__( $this->settings['dismissText'] ) : false;

    $slideDuration = isset( $this->settings['slideDuration'] ) ? intval( $this->settings['slideDuration'] ) : 300;
    $aria_label = 'dismiss';

    $stickyDisplay = isset( $this->settings['stickyDisplay'] ) ? esc_attr( $this->settings['stickyDisplay'] ) : "always" ;

    $notification_config = [
      'slideDuration' => $slideDuration,
      'show_again' => [
        'type' => isset( $this->settings['show_again'] ) ? $this->settings['show_again'] : 'page_load',
        'options' => [
          'days' => isset( $this->settings['show_again_days'] ) ? $this->settings['show_again_days'] : 0,
          'hours' => isset( $this->settings['show_again_hours'] ) ? $this->settings['show_again_hours'] : 0,
        ],
      ],
    ];

    $this->set_attribute( '_root', 'data-x-notification', wp_json_encode( $notification_config ) );
    $this->set_attribute( '_root', 'data-x-id', $this->id );
    $this->set_attribute( '_root', 'data-x-sticky', $stickyDisplay );
    $this->set_attribute( 'x-notification_close', 'class', 'x-notification_close' );
    $this->set_attribute( 'x-notification_close', 'aria-label', $aria_label );
    $this->set_attribute( 'x-notification_close-text', 'class', 'x-notification_close-text' );
    $this->set_attribute( 'x-notification_close-icon', 'class', 'x-notification_close-icon' );


  
    echo "<div {$this->render_attributes( '_root' )}>";

    if ( 'wysiwyg' === $notification_content ) {
     if ( isset( $this->settings['notification_wysiwyg'] ) ) {
      $content = $this->settings['notification_wysiwyg'];
      $content = $this->render_dynamic_data( $content );
      $content = \Bricks\Helpers::parse_editor_content( $content );
      echo $content;
     }
   } else {
    if ( method_exists('\Bricks\Frontend','render_children') ) {
      echo \Bricks\Frontend::render_children( $this );
    }
   }

   echo "<button {$this->render_attributes( 'x-notification_close' )}>";
   echo $dismiss_text ? "<span {$this->render_attributes( 'x-notification_close-text' )}> " . $dismiss_text . "  </span>" : "";
   echo "<span {$this->render_attributes( 'x-notification_close-icon' )}> " . $dismiss_icon . "  </span>";
           
   echo  "</button>";

    echo "</div>";
    
  }

  public static function render_builder() { ?>

		<script type="text/x-template" id="tmpl-bricks-element-xnotificationbar">

    <component>
      <contenteditable
            tag="div"
            v-if="'nestable' !== settings.notification_content"
            class="x-notification_content"
            :name="name"
            controlKey="notification_wysiwyg"
            toolbar="true"
            :settings="settings"
          />

          <bricks-element-children
            v-else
            :element="element"
          />
          <button 
            class="x-notification_close"
          >

          <contenteditable
            tag="span"
            class="x-notification_close-text"
            :name="name"
            controlKey="dismissText"
            toolbar="style"
            :settings="settings"
          />
            <span class="x-notification_close-icon"><icon-svg :iconSettings="settings.dismiss_icon"/></span>
          </button>
  			</component>
			
		</script>

	<?php }

}