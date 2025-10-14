(function($) {

	'use strict';

	// Processes consent field types
	$.WS_Form.prototype.form_consent = function() {

		// TrustedForm
		this.form_consent_trustedform();
	}

	// TrustedForm
	$.WS_Form.prototype.form_consent_trustedform = function() {

		var ws_this = this;

		var trustedform_objs = $('[data-consent-trustedform]', this.form_canvas_obj);

		trustedform_objs.each(function() {

			var trustedform_url_params = {

				form_selector: '#' + ws_this.form_obj.attr('id'),
				l: new Date().getTime() + Math.random()
			};

			// Get field
			var field = ws_this.get_field($(this));

			// Field ID - Certificate URL
			var trustedform_field_id_cert_url = parseInt(ws_this.get_object_meta_value(field, 'trustedform_field_id_cert_url', 0), 10);
			trustedform_url_params.field = ((trustedform_field_id_cert_url > 0) ? ('field_' + trustedform_field_id_cert_url) : 'xxTrustedFormCertUrl');

			// Field ID - Ping URL
			var trustedform_field_id_ping_url = parseInt(ws_this.get_object_meta_value(field, 'trustedform_field_id_ping_url', 0), 10);
			trustedform_url_params.ping_field = ((trustedform_field_id_cert_url > 0) ? ('field_' + trustedform_field_id_ping_url) : 'xxTrustedFormPingUrl');

			// Field ID - Token
			var trustedform_field_id_token = parseInt(ws_this.get_object_meta_value(field, 'trustedform_field_id_token', 0), 10);
			trustedform_url_params.token_field = ((trustedform_field_id_token > 0) ? ('field_' + trustedform_field_id_token) : 'xxTrustedFormToken');

			// Sandbox
			var trustedform_sandbox = (ws_this.get_object_meta_value(field, 'trustedform_sandbox', '') == 'on');
			if(trustedform_sandbox) {

				trustedform_url_params.sandbox = 'true';
			}

			// Invert field sensitivity
			var trustedform_invert_field_sensitivity = (ws_this.get_object_meta_value(field, 'trustedform_invert_field_sensitivity', '') == 'on');
			if(trustedform_invert_field_sensitivity) {

				trustedform_url_params.invert_field_sensitivity = 'true';
			}

			// Identifier
			var trustedform_identifier = ws_this.get_object_meta_value(field, 'trustedform_identifier', '');
			if(
				(typeof(trustedform_identifier) === 'string') &&
				(trustedform_identifier.length <= 128) &&
				(trustedform_identifier != '')
			) {
				trustedform_url_params.identifier = trustedform_identifier;
			}

			// Build query string
			var query_string = new URLSearchParams(trustedform_url_params).toString();

			// Log
			ws_this.log('log_trustedform_init', ws_this.esc_html(query_string));

			// Build TrustedForm script object
			var trustedform_obj = document.createElement('script');
			trustedform_obj.type = 'text/javascript';
			trustedform_obj.async = true;
			trustedform_obj.src = ("https:" == document.location.protocol ? 'https' : 'http') + '://api.trustedform.com/trustedform.js?' + query_string;

			// Insert script after form
			ws_this.form_obj[0].insertAdjacentElement("afterend", trustedform_obj);

			// Bypass debug population
			if(trustedform_field_id_cert_url > 0) { $('[name="field_' + trustedform_field_id_cert_url + '"]', ws_this.form_obj).attr('data-debug-populate-bypass', ''); }
			if(trustedform_field_id_ping_url > 0) { $('[name="field_' + trustedform_field_id_ping_url + '"]', ws_this.form_obj).attr('data-debug-populate-bypass', ''); }
			if(trustedform_field_id_token > 0) { $('[name="field_' + trustedform_field_id_token + '"]', ws_this.form_obj).attr('data-debug-populate-bypass', ''); }
		});
	}

})(jQuery);
