<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class X_Horizontal_Slide_Menu extends \Bricks\Element {

  // Element properties
  public $category     = 'extras';
	public $name         = 'xhorizontalslidemenu';
	public $icon         = 'ti-view-list-alt';
	public $css_selector = '';
	public $scripts      = ['xHorizontalSlideMenu'];

	// Methods: Builder-specific
	public function get_label() {
		return esc_html__( 'Horizontal slide menu', 'extras' );
	}
  public function set_control_groups() {}
  public function set_controls() {

	$nav_menus = [];

	if ( bricks_is_builder() ) {
		foreach ( wp_get_nav_menus() as $menu ) {
			$nav_menus[ $menu->term_id ] = $menu->name;
		}
	}

	$this->controls['menu'] = [
		'tab'         => 'content',
		'label'       => esc_html__( 'Nav Menu', 'bricks' ),
		'type'        => 'select',
		'options'     => $nav_menus,
		'placeholder' => esc_html__( 'Select nav menu', 'bricks' ),
		'description' => sprintf( '<a href="' . admin_url( 'nav-menus.php' ) . '" target="_blank">' . esc_html__( 'Manage my menus in WordPress.', 'bricks' ) . '</a>' ),
	];

	$this->controls['direction'] = [
		'tab' => 'content',
		'label' => esc_html__( 'Slide in from..', 'bricks' ),
		'type' => 'select',
		'options' => [
		  'x-offcanvas_left' => esc_html__( 'Left', 'bricks' ),
		  'x-offcanvas_right' => esc_html__( 'Right', 'bricks' ),
		  'x-offcanvas_top' => esc_html__( 'Top', 'bricks' ),
		  'x-offcanvas_bottom' => esc_html__( 'Bottom', 'bricks' ),
		],
		'inline'      => true,
		'clearable' => false,
		'placeholder' => esc_html__( 'Select', 'bricks' ),
		'default' => 'x-offcanvas_left',
		'rerender'    => false,
	  ];


  }

  // Methods: Frontend-specific
  public function enqueue_scripts() {

	wp_enqueue_style( 'x-horizontal-slide-menu', BRICKSEXTRAS_URL . 'components/assets/css/horizontalslidemenu.css', [], '1.0.0' );
	wp_enqueue_script( 'x-horizontal-slide-menu', BRICKSEXTRAS_URL . 'components/assets/js/horizontalslidemenu.js', '', '1.0.0', true );

  }
  public function render() {

	$menu  = ! empty( $this->settings['menu'] ) ? $this->settings['menu'] : '';

	if ( ! $menu || ! is_nav_menu( $menu ) ) {
		// Use first registered menu
		foreach ( wp_get_nav_menus() as $menu ) {
			$menu = $menu->term_id;
		}

		if ( ! $menu || ! is_nav_menu( $menu ) ) {
			return $this->render_element_placeholder(
				[
					'title' => esc_html__( 'No nav menu found.', 'bricks' ),
				]
			);
		}
	}

	$this->set_attribute( 'x-horizontal-slide-menu_inner', 'class', 'x-horizontal-slide-menu_inner' );

	echo "<nav {$this->render_attributes( '_root' )}>";

	echo "<div {$this->render_attributes( 'x-horizontal-slide-menu_inner' )}>";

		wp_nav_menu( [
			'menu'           => $menu,
			'menu_class'     => 'x-horizonal-slide-menu_list',
			'container'		 => 'false',
		] );

	echo "</div>";

	echo "</nav>";
    
  }


}