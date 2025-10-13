<?php

if (!defined('ABSPATH')) {
	exit;
}

class Brf_Walker_Nav_Menu extends Walker_Nav_Menu_Edit
{
	function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0)
	{
		$item_output = '';
		parent::start_el($item_output, $item, $depth, $args, $id);
		$output .= preg_replace(
			'/(?=<div[^>]+class="[^"]*submitbox)/',
			$this->get_fields($item, $depth, $args, $id),
			$item_output
		);
	}

	protected function get_fields($item, $depth, $args = array(), $id = 0)
	{
		ob_start();

		$posts = get_posts([
			'post_type'   => 'bricks_template',
			'post_status' => 'publish',
			'numberposts' => -1
		]);

		$item_id = esc_attr($item->ID);
?>
		<p class="field-bricks-template description description-wide">
			<label for="edit-menu-item-bricks-template">
				<?php _e('Mega Menu – Bricks Template Shortcode'); ?><br />
				<select id="edit-menu-item-bricks-template-<?php echo $item_id; ?>" name="menu-item-bricks-template[<?php echo $item_id; ?>]" style="width: 100%" value="<?php echo esc_attr($item->wpm_megamenu) ?>">
					<option value="none">Select Template</option>
					<?php
					foreach ($posts as $post) {
						$split1 = explode('"', $item->wpm_megamenu);
						$templateID = isset($split1[1]) ? explode("]", $split1[1])[0] : false;
						$selected = intval($templateID) === intval($post->ID) ? 'selected' : '';
						echo "<option value='[bricks_template id=" . '"' . $post->ID . '"' . "]' $selected>" . $post->post_title . " (ID: " . $post->ID . ")</option>";
					}
					?>
				</select>
			</label>
		</p>

<?php

		return ob_get_clean();
	}
}
