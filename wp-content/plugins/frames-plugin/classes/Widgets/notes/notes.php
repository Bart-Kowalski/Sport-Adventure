<?php
/**
 * Table of Contents Widget.
 *
 * @package Frames_Client
 */

namespace Frames_Client\Widgets\Notes;

use Frames_Client\Helpers\Flag;
use Frames_Client\Widget_Manager;
define( 'FRAMES_NOTES_PRESENCE_SCRIPT', false );


/**
 * Notes element class.
 */
class Notes_Widget extends \Bricks\Element {


	/**
	 * Element properties
	 *
	 * @since  1.0.0
	 * @access public
	 */

	/**
	 * Category.
	 *
	 * @var string
	 */
	public $category = 'Frames';

	/**
	 * Element Prefix.
	 *
	 * @var string
	 */
	public $name = 'fr-notes';


	/**
	 * Icon
	 *
	 * @var string
	 */
	public $icon = 'ti-bookmark';


	/**
	 * Default CSS Selector
	 *
	 * @var string
	 */
	public $css_selector = '.fr-notes';

	/**
	 * Scripts to be enqueued.
	 *
	 * @var array
	 */
	public $scripts = array( 'notes_script' );

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function get_methods() {
	}

	/**
	 * Get widget label.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return string Widget Label.
	 */
	public function get_label() {
		return esc_html__( 'Frames Notes', 'frames' );
	}

	/**
	 * Register widget control groups.
	 *
	 * @since  1.0.0
	 * @access protected
	 */
	public function set_control_groups() {

		// $this->control_groups['frNotesSettings'] = array(
		// 'title' => esc_html__( 'Settings', 'frames' ),
		// 'tab' => 'content',
		// );
	}

	/**
	 * Enqueue Scripts and Styles for the widget
	 *
	 * @since  1.0.0
	 * @access protected
	 */
	public function enqueue_scripts() {
		if ( ! Widget_Manager::is_bricks_builder() ) {
			return;
		}

		$filename = 'notes';
		wp_enqueue_style(
			"frames-{$filename}",
			FRAMES_WIDGETS_URL . "/{$filename}/css/{$filename}.css",
			array(),
			filemtime( FRAMES_WIDGETS_DIR . "/{$filename}/css/{$filename}.css" )
		);

		if ( Flag::is_on( 'FRAMES_NOTES_PRESENCE_SCRIPT' ) ) {
			wp_enqueue_script(
				"frames-{$filename}-",
				FRAMES_WIDGETS_URL . "/{$filename}/js/{$filename}.js",
				array(),
				filemtime( FRAMES_WIDGETS_DIR . "/{$filename}/js/{$filename}.js" ),
				true
			);
		}
	}

	/**
	 * Register widget controls.
	 *
	 * @since  1.0.0
	 * @access protected
	 */
	public function set_controls() {
		$this->controls['_background']['css'][0]['selector'] = '';
		$this->controls['_typography']['css'][0]['selector'] = '';

		$this->controls['hideNotes'] = array(
			'label'   => esc_html__( 'Hide Notes in Builder', 'frames' ),
			'type'    => 'checkbox',
			'inline'  => true,
			'default' => false,
		);

		$this->controls['notesContent'] = array(
			'tab'     => 'content',
			'type'    => 'editor',
			'default' => '<p>' . esc_html__( 'Use this element to leave notes for yourself or your team.', 'frames' ) . '</p>',
		);

	}

	/**
	 * Render widget output.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since  1.0.0
	 * @access protected
	 */
	public function render() {

	}

	/**
	 * Render widget output in Builder.
	 *
	 * @since  1.0.0
	 * @access protected
	 */
	public static function render_builder() {
		?>
		<script type="text/x-template" id="tmpl-bricks-element-fr-notes">
			<div
				:class="['fr-notes']"
				v-bind="settings.hideNotes ? { 'fr-builder-notes': 'hide' } : {}"
				v-html="settings.notesContent"
			>
			</div>
		</script>
		<?php
	}

}
