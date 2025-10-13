<?php

namespace BricksExtras;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SettingsPage {
	static $prefix = '';
	static $title = '';
	static $version= '';

	public static function init( $prefix, $title, $version ) {
		self::$prefix = $prefix;
		self::$title = $title; 
		self::$version = $version;
		?>
		
		<h2><?php echo __( 'Enable Elements / Features' ); ?></h2>

		<div class="form-plugin-links" style="display: flex;">
			<form method="post" action="options.php" style="width: 100%;">
				<?php settings_fields( self::$prefix . 'settings' ); ?>
				
					<table class="wp-list-table widefat plugins">
						<thead>
						<tr>
							<td id="cb" class="manage-column column-cb check-column"><label class="screen-reader-text" for="cb-select-all-1">Toggle All</label><input id="cb-select-all-1" type="checkbox"></td><th scope="col" id="name" class="manage-column column-name column-primary"><strong>Toggle All</strong></th><td></td></tr>
						</thead>
						<tbody>
							<?php do_action( self::$prefix . 'form_options' ); ?>
						</tbody>
					</table>
					
					<?php submit_button( null, $type = 'primary large' ); ?>

				
			</form>

			<div class="plugin-links" style="margin-inline-start: 40px; width: 300px;">
				<ul>
					<li style="margin-bottom: 20px;">BricksExtras v<?php echo self::$version; ?><?php if ( true !== BricksExtrasLicense::is_activated_license() ) {
						echo '<span> - Activate license to receive updates</span>';
					} ?></li>
					<li><a style="margin-bottom: 20px; display: flex; align-items: center; line-height: 1;" target="_blank" href="https://bricksextras.com/support/"><svg style="width: 14px; height: 14px; margin-right: 5px;" width="14" height="14" aria-hidden="true" focusable="false" data-prefix="fal" data-icon="life-ring" class="svg-inline--fa fa-life-ring fa-w-16" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M256 8C119.033 8 8 119.033 8 256s111.033 248 248 248 248-111.033 248-248S392.967 8 256 8zm168.766 113.176l-62.885 62.885a128.711 128.711 0 0 0-33.941-33.941l62.885-62.885a217.323 217.323 0 0 1 33.941 33.941zM256 352c-52.935 0-96-43.065-96-96s43.065-96 96-96 96 43.065 96 96-43.065 96-96 96zM363.952 68.853l-66.14 66.14c-26.99-9.325-56.618-9.33-83.624 0l-66.139-66.14c66.716-38.524 149.23-38.499 215.903 0zM121.176 87.234l62.885 62.885a128.711 128.711 0 0 0-33.941 33.941l-62.885-62.885a217.323 217.323 0 0 1 33.941-33.941zm-52.323 60.814l66.139 66.14c-9.325 26.99-9.33 56.618 0 83.624l-66.139 66.14c-38.523-66.715-38.5-149.229 0-215.904zm18.381 242.776l62.885-62.885a128.711 128.711 0 0 0 33.941 33.941l-62.885 62.885a217.366 217.366 0 0 1-33.941-33.941zm60.814 52.323l66.139-66.14c26.99 9.325 56.618 9.33 83.624 0l66.14 66.14c-66.716 38.524-149.23 38.499-215.903 0zm242.776-18.381l-62.885-62.885a128.711 128.711 0 0 0 33.941-33.941l62.885 62.885a217.323 217.323 0 0 1-33.941 33.941zm52.323-60.814l-66.14-66.14c9.325-26.99 9.33-56.618 0-83.624l66.14-66.14c38.523 66.715 38.5 149.229 0 215.904z"></path></svg> Support Portal</a></li>
					<li style=""><a style="display: flex; align-items: center; line-height: 1;" target="_blank" href="https://www.facebook.com/groups/bricksextras/"><svg style="width: 14px; height: 14px; margin-right: 5px;" width="14" height="14" aria-hidden="true" focusable="false" data-prefix="fab" data-icon="facebook-f" class="svg-inline--fa fa-facebook-f fa-w-10" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path fill="currentColor" d="M279.14 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z"></path></svg> Facebook Group</a></li>
					</ul>
			</div>
		</div>

	<?php }
}