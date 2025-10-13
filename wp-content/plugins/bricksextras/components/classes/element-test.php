<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class X_Class extends \Bricks\Element {

  // Element properties
  public $category     = 'extras';
	public $name         = 'xtableofcontents';
	public $icon         = 'ti-view-list-alt';
	public $css_selector = '';
	public $scripts      = ['xTableOfContents'];

  
  public function get_label() {
	return esc_html__( '', 'extras' );
  }
  public function set_control_groups() {

  }

  public function set_controls() {

  }

  // Methods: Frontend-specific
  public function enqueue_scripts() {

  }
  
  public function render() {

    
  }

  public static function render_builder() { ?>

		<script type="text/x-template" id="tmpl-bricks-element-xtableofcontents">
			
		</script>

	<?php }

}