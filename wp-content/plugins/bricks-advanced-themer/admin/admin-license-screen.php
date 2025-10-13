<?php
namespace Bricks;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

?>
<div class="panel" id="panel-license-tools">
	<div class="panel-body clearfix">
		<div id="License" class="of-tabs">
			<form method="post" action="options.php">

				<?php settings_fields('BRXC_license'); ?>


				<div id="licence-form" class="BRXC_admin_card">

					<div class="BRXC_admin_header">
						<h2 class=" my-small">Welcome to Advanced Themer for Bricks</h2>
						<h4>To get you started, please <b>activate your license first</b></h4>
					</div><!-- End of BRXC_admin_header -->

					<div class="BRXC_admin_body bg-neutral-400 rounded-md p-medium my-medium max-w-narrow ">

						<div class="flex gap-medium mb-small">

							<label class="description" for="BRXC_license_key"><?php _e('Enter your license key'); ?></label>

							<?php if ($status !== false && $status == 'valid') { ?>
								<div class="licence-status">
									| Status: <span class="bg-[#06bf50] text-white px-xsmall rounded-sm"><?php _e('active'); ?></span>
								</div>
							<?php } else { ?>

							<?php } ?>
						</div>

						<div class="flex gap-medium  [&>*]:!p-0  [&>*]:!m-0">
							<input id="BRXC_license_key" name="BRXC_license_key" type="password" class="regular-text" value="<?php esc_attr_e($license); ?>" />

							<?php if ($status !== false && $status == 'valid') { ?>
								<?php wp_nonce_field('BRXC_nonce', 'BRXC_nonce'); ?>

								<input type="submit" class="button-primary" class="BRXC_license_deactivate" name="BRXC_license_deactivate" value="<?php _e('Deactivate License'); ?>" />
							<?php } else { ?>
								<?php wp_nonce_field('BRXC_nonce', 'BRXC_nonce'); ?>
								<?php submit_button('Activate License', 'primary', 'BRXC_license_activate', true, array()); ?>
							<?php } ?>

						</div>

					</div><!-- End of BRXC_admin_body -->

				</div><!-- End of BRXC_admin_card -->
			</form>

		</div> <!-- End of Licence -->
	</div>
</div>