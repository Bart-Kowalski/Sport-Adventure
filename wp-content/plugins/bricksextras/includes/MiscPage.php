<?php

namespace BricksExtras;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class MiscPage {
	static $prefix = '';
	static $title = '';
	static $version= '';

	public static function init( $prefix, $title, $version ) {
		self::$prefix = $prefix;
		self::$title = $title; 
		self::$version = $version;
		?>
		
		<h2><?php echo __( 'Miscellaneous' ); ?></h2>

		<div class="form-plugin-links" style="display: flex;">
			<form method="post" action="options.php" style="width: 100%;">

				<?php settings_fields( self::$prefix . 'misc_options' ); ?>

					<div style="width: 100%; column-count: 2; gap: 2rem;">

						<table class="wp-list-table widefat plugins" style="break-inside: avoid;">
							<thead>
							<tr>
								<td id="cb" class="manage-column column-cb check-column"><label class="screen-reader-text" for="cb-select-all-1">Misc - Toggle All</label><input id="cb-select-all-1" type="checkbox"></td><th scope="col" id="name" class="manage-column column-name column-primary"><strong>Toggle All</strong></th><td></td></tr>
							</thead>
							<tbody>
								<?php do_action( self::$prefix . 'misc_form_options' ); ?>
							</tbody>
						</table>

					</div>
					
					<?php submit_button( null, $type = 'primary large' ); ?>
			</form>

		</div>

	<?php }
}