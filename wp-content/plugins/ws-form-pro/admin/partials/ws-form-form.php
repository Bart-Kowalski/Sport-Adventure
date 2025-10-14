<?php

	// Form - Admin Page

	// Loader
	WS_Form_Common::loader();
?>
<div id="wsf-wrapper" class="<?php WS_Form_Common::wrapper_classes(); ?>">

<!-- Header -->
<div class="wsf-header">
<h1><?php esc_html_e('Forms', 'ws-form'); ?></h1>
<?php

	if(WS_Form_Common::can_user('create_form')) {
?>
<a class="wsf-button wsf-button-small wsf-button-information" href="<?php WS_Form_Common::echo_esc_url(WS_Form_Common::get_admin_url('ws-form-add')); ?>" title="<?php esc_attr_e('Add New', 'ws-form'); ?>"><?php WS_Form_Common::render_icon_16_svg('plus'); ?> <?php esc_html_e('Add New', 'ws-form'); ?></a>
<?php
	}

	if(WS_Form_Common::can_user('import_form')) {
?>
<button class="wsf-button wsf-button-small" data-action-button="wsf-form-upload"><?php WS_Form_Common::render_icon_16_svg('upload'); ?> <?php esc_html_e('Import', 'ws-form'); ?></button>
<?php
	}
?>
</div>
<hr class="wp-header-end">
<!-- /Header -->
<?php

	// Review nag
	WS_Form_Common::review();

	// Import
	if(WS_Form_Common::can_user('import_form')) {
?>
<input type="file" id="wsf-object-upload-file" class="wsf-file-upload" accept=".json" aria-hidden aria-label="<?php esc_attr_e('File upload', 'ws-form'); ?>" />
<?php
	}
?>
<!-- Form Table -->
<form id="wsf-form-list-table" method="post">
<?php

	// Prepare
	$this->ws_form_wp_list_table_form_obj->prepare_items();

	// Search
	$this->ws_form_wp_list_table_form_obj->search_box('Search', 'search');

	// Views
	$this->ws_form_wp_list_table_form_obj->views();
?>
<input type="hidden" name="_wpnonce" value="<?php WS_Form_Common::echo_esc_attr(wp_create_nonce('wp_rest')); ?>">
<?php wp_nonce_field(WS_FORM_POST_NONCE_ACTION_NAME, WS_FORM_POST_NONCE_FIELD_NAME); ?>
<input type="hidden" name="page" value="ws-form">
<?php

	// Display
	$this->ws_form_wp_list_table_form_obj->display();
?>
</form>
<!-- /Form Table -->

<!-- Form Actions -->
<form action="<?php WS_Form_Common::echo_esc_attr(WS_Form_Common::get_admin_url()); ?>" id="wsf-action-do" method="post">
<input type="hidden" name="_wpnonce" value="<?php WS_Form_Common::echo_esc_attr(wp_create_nonce('wp_rest')); ?>">
<?php wp_nonce_field(WS_FORM_POST_NONCE_ACTION_NAME, WS_FORM_POST_NONCE_FIELD_NAME); ?>
<input type="hidden" name="page" value="ws-form">
<input type="hidden" id="wsf-action" name="action" value="">
<input type="hidden" id="wsf-id" name="id" value="">
<input type="hidden" name="paged" value="<?php WS_Form_Common::echo_esc_attr(WS_Form_Common::get_query_var_nonce('paged', '', false, false, true, 'POST')); ?>">
<input type="hidden" name="ws-form-status" value="<?php WS_Form_Common::echo_esc_attr(WS_Form_Common::get_query_var_nonce('ws-form-status', '', false, false, true, 'POST')); ?>">
</form>
<!-- /Form Actions -->

<script>

	(function($) {

		'use strict';

		// On load
		$(function() {

			// Initialize WS Form
			var wsf_obj = new $.WS_Form();

			// Partial initialization
			wsf_obj.init_partial();

			// Initialize tooltips
			wsf_obj.tooltips();

			// Initialize form table
			wsf_obj.wp_list_table_form();

			$('#wsf-form-table h1').html('<?php esc_html_e('Drop file to import', 'ws-form'); ?>');
		});

	})(jQuery);

</script>
</div>
