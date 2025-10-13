<?php
namespace Advanced_Themer_Bricks;
if (!defined('ABSPATH')) { die();
}
$i++;
?>
<input type="checkbox" id="<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>Reset<?php echo esc_attr($i);?>" class="brxc-no-reset">
<label for="<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>Reset<?php echo esc_attr($i);?>" class="brxc-no-reset">Don't reset me</label>