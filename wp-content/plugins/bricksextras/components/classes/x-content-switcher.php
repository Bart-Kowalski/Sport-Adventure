<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class X_Content_Switcher extends \Bricks\Element {

  // Element properties
  public $category     = 'extras';
	public $name         = 'xcontentswitcher';
	public $icon         = 'ti-loop';
	public $css_selector = '';
  public $nestable = true;
	//public $scripts      = ['xContentSwitcher'];

  
  public function get_label() {
	  return esc_html__( 'Content Switcher', 'extras' );
  }
  public function set_control_groups() {

  }

  public function set_controls() {

    $this->controls['builderHidden'] = [
			'tab'   => 'content',
			'inline' => true,
			'small' => true,
			'label' => esc_html__( 'Show only first child element in builder', 'bricks' ),
			'type'  => 'checkbox',
		];

    $this->controls['startSep'] = [
      'tab'   => 'content',
      'type'  => 'separator',
      'label' => esc_html__( 'Content Blocks', 'bricks' ),
      'description' => esc_html__( 'The child elements placed inside this switcher can be toggled using the toggle switch element', 'bricks' ),
    ];

    $this->controls['childrenBlocks'] = [
			'type'          => 'repeater',
			'titleProperty' => 'label',
			'items'         => 'children', // NOTE: Undocumented
		];

    $this->controls['transitionSep'] = [
      'tab'   => 'content',
      'type'  => 'separator',
    ];

    $this->controls['transitionDuration'] = [
      'tab' => 'content',
      'label' => esc_html__( 'Transition duration', 'extras' ),
      'small'	=> true,
      'inline' => true,
      'type' => 'number',
      'units'    => true,
      'css' => [
        [
        'selector' => '.x-content-switcher_content > *',  
        'property' => 'transition-duration',
        ],
      ],
      'placeholder' => '0ms'
      ];

  }

  public function get_nestable_item() {

		return [
			'name'     => 'block',
			'label'    => esc_html__( 'Content block', 'bricks' ),
			'children' => [
        [
          'name'     => 'heading',
          'settings' => [
            'text' => esc_html__( 'Content', 'bricks' ) . ' {item_index}',
            'tag'  => 'h3',
          ],
        ],
      ],
      'settings' => [
        '_hidden'         => [
          '_cssClasses' => 'x-content-switcher_block',
        ],
      ],
		];
	}


  public function get_nestable_children() {
		$children = [];

		for ( $i = 0; $i < 2; $i++ ) {
			$item = $this->get_nestable_item();

			// Replace {item_index} with $index
			$item       = json_encode( $item );
			$item       = str_replace( '{item_index}', $i + 1, $item );
			$item       = json_decode( $item, true );
			$children[] = $item;
		}

		return $children;
	}

  // Methods: Frontend-specific
	public function enqueue_scripts() {
		if (! \BricksExtras\Helpers::elementCSSAdded($this->name) ) {
			wp_enqueue_style( 'x-content-switcher', BRICKSEXTRAS_URL . 'components/assets/css/contentswitcher.css', [], '' );
		  }
	}
  
  public function render() {


    $this->set_attribute( '_root', 'class', 'x-content-switcher' );
    $this->set_attribute( 'x-content-switcher_content', 'class', 'x-content-switcher_content' );

    $this->set_attribute( 'x-content-switcher_content', 'data-x-switcher', '1' );

    if ( method_exists('\Bricks\Query','is_any_looping') ) {

      $query_id = \Bricks\Query::is_any_looping();

      if ( $query_id ) {
        $this->set_attribute( '_root', 'data-x-id', $this->id . '_' . \Bricks\Query::get_loop_index() );
      } else {
        $this->set_attribute( '_root', 'data-x-id', $this->id );
      }

    } 

    echo "<div {$this->render_attributes( '_root' )}>";

      echo "<div {$this->render_attributes( 'x-content-switcher_content' )}>";
      
      if ( method_exists('\Bricks\Frontend','render_children') ) {
          echo \Bricks\Frontend::render_children( $this );
      }  

      echo "</div>";

		echo "</div>";

  }

  public static function render_builder() { ?>

		<script type="text/x-template" id="tmpl-bricks-element-xcontentswitcher">

      <component 
        class="x-content-switcher"
        >
        <div 
          class="x-content-switcher_content"
          :class="settings.builderHidden ? 'x-content-switcher_hide' : ''"
        >
        <bricks-element-children :element="element" />
      
      </div>
      </component>
			
		</script>

	<?php }

}