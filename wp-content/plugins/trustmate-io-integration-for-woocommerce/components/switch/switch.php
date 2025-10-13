<?php
function trustmate_render_switch($text, $id)
{
?>
  <div class="toggle-switch" >
      <input class="toggle-switch" type="checkbox" id="<?php echo esc_attr($id) ?>" name="<?php echo esc_attr($id) ?>" value="1" <?php checked(true, get_option($id)) ?>/>
      <span><?php echo esc_html($text) ?></span>
  </div>
<?php
}
?>