(function($) {

	'use strict';

	// Form SSN
	$.WS_Form.prototype.form_ssn = function() {

		var ws_this = this;

		$('[data-wsf-ssn]:not([data-init-wsf-ssn])', this.form_canvas_obj).each(function() {

			// Initial processing
			var hidden_obj = ws_this.form_ssn_get_hidden_obj($(this));
			ws_this.form_ssn_update(hidden_obj.val().replace(/\D/g, ''), $(this), hidden_obj);

			// On input
			$(this).on('input', function(e) {

				// Get hidden object
				var hidden_obj = ws_this.form_ssn_get_hidden_obj($(this));

				// Get full SSN from hidden object
				var ssn_full = hidden_obj.val().replace(/\D/g, '');

				// Get raw value from input
				var ssn = $(this).val().replace(/\D/g, '');

				// Get key
				var key = e.originalEvent.inputType;

				if(key === 'deleteContentBackward') {

					// Backspace
					ssn_full = ssn_full.slice(0, -1);

				} else {

					// Get new character
					var char_new = $(this).val().slice(-1);

					// Add new character to full SSN if it is numeric
					if(
						/^\d+$/.test(char_new) &&
						(ssn_full.length < 9) &&
						char_new
					) {
						ssn_full += char_new;
					}
				}

				// Update SSN
				ws_this.form_ssn_update(ssn_full, $(this), hidden_obj);
			});

			// On paste, replace full SSN
			$(this).on('paste', function(e) {

				e.preventDefault();

				// Get hidden object
				var hidden_obj = ws_this.form_ssn_get_hidden_obj($(this));

				// Get pasted data
				var pasted_string = (e.originalEvent.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '').slice(0, 9);
				
				// Update the masked input field
				ws_this.form_ssn_update(pasted_string, $(this), hidden_obj);
			});

			// Set field as initialized
			$(this).attr('data-init-wsf-ssn', '');
		});
	}

	$.WS_Form.prototype.form_ssn_update = function(ssn_full, input_obj, hidden_obj) {

		// Get field ID
		var field_id = this.get_field_id(input_obj);

		// Get field config
		var field = this.field_data_cache[field_id];

		// Build SSN output
		switch(this.get_object_meta_value(field, 'ssn_mask', '###-##-9999')) {

			case 'full' :

				var ssn_input = this.form_ssn_format_dashed_mask_full(ssn_full);
				break;

			case 'partial' :

				var ssn_input = this.form_ssn_format_dashed_mask_partial(ssn_full);
				break;

			case 'partial_show_as_type' :

				var ssn_input = this.form_ssn_format_dashed_mask_partial_show_as_type(ssn_full);
				break;

			default :

				var ssn_input = this.form_ssn_format_dashed(ssn_full);
		}

		// Set input object
		input_obj.val(ssn_input);

		// Build SSN output
		if(this.get_object_meta_value(field, 'ssn_format', 'dashed') == 'dashed') {

			var ssn_output = this.form_ssn_format_dashed(ssn_full);

			var ssn_regex = /^(?!219-09-9999|078-05-1120)(?!666|000|9\d{2})\d{3}-(?!00)\d{2}-(?!0{4})\d{4}$/;

		} else {

			var ssn_output = ssn_full;

			var ssn_regex = /^(?!219099999|078051120)(?!666|000|9\d{2})\d{3}(?!00)\d{2}(?!0{4})\d{4}$/;
		}

		// Set hidden object
		hidden_obj.val(ssn_output);

		// Validate
		var validated = ssn_regex.test(ssn_output);

		if(validated) {

			this.set_invalid_feedback(input_obj, '');

		} else {

			this.set_invalid_feedback(input_obj, false);
		}
	}

	$.WS_Form.prototype.form_ssn_format_dashed = function(ssn) {

		var ssn_output = '';

		if (ssn.length === 1) {
			ssn_output = ssn;
		} else if (ssn.length === 2) {
			ssn_output = ssn[0] + ssn[1];
		} else if (ssn.length === 3) {
			ssn_output = ssn[0] + ssn[1] + ssn[2];
		} else if (ssn.length === 4) {
			ssn_output = ssn[0] + ssn[1] + ssn[2] + '-' + ssn[3];
		} else if (ssn.length === 5) {
			ssn_output = ssn[0] + ssn[1] + ssn[2] + '-' + ssn[3] + ssn[4];
		} else if (ssn.length === 6) {
			ssn_output = ssn[0] + ssn[1] + ssn[2] + '-' + ssn[3] + ssn[4] + '-' + ssn[5];
		} else if (ssn.length === 7) {
			ssn_output = ssn[0] + ssn[1] + ssn[2] + '-' + ssn[3] + ssn[4] + '-' + ssn[5] + ssn[6];
		} else if (ssn.length === 8) {
			ssn_output = ssn[0] + ssn[1] + ssn[2] + '-' + ssn[3] + ssn[4] + '-' + ssn[5] + ssn[6] + ssn[7];
		} else if (ssn.length === 9) {
			ssn_output = ssn[0] + ssn[1] + ssn[2] + '-' + ssn[3] + ssn[4] + '-' + ssn[5] + ssn[6] + ssn[7] + ssn[8];
		}

		return ssn_output;
	}

	$.WS_Form.prototype.form_ssn_format_dashed_mask_partial_show_as_type = function(ssn) {

		var ssn_output = '';

		if (ssn.length === 1) {
			ssn_output = ssn;
		} else if (ssn.length === 2) {
			ssn_output = '*' + ssn[1];
		} else if (ssn.length === 3) {
			ssn_output = '**' + ssn[2];
		} else if (ssn.length === 4) {
			ssn_output = '***-' + ssn[3];
		} else if (ssn.length === 5) {
			ssn_output = '***-*' + ssn[4];
		} else if (ssn.length === 6) {
			ssn_output = '***-**-' + ssn[5];
		} else if (ssn.length === 7) {
			ssn_output = '***-**-' + ssn[5] + ssn[6];
		} else if (ssn.length === 8) {
			ssn_output = '***-**-' + ssn[5] + ssn[6] + ssn[7];
		} else if (ssn.length === 9) {
			ssn_output = '***-**-' + ssn[5] + ssn[6] + ssn[7] + ssn[8];
		}

		return ssn_output;
	}

	$.WS_Form.prototype.form_ssn_format_dashed_mask_partial = function(ssn) {

		var ssn_output = '';

		if (ssn.length === 1) {
			ssn_output = '*';
		} else if (ssn.length === 2) {
			ssn_output = '**';
		} else if (ssn.length === 3) {
			ssn_output = '***';
		} else if (ssn.length === 4) {
			ssn_output = '***-*';
		} else if (ssn.length === 5) {
			ssn_output = '***-**';
		} else if (ssn.length === 6) {
			ssn_output = '***-**-' + ssn[5];
		} else if (ssn.length === 7) {
			ssn_output = '***-**-' + ssn[5] + ssn[6];
		} else if (ssn.length === 8) {
			ssn_output = '***-**-' + ssn[5] + ssn[6] + ssn[7];
		} else if (ssn.length === 9) {
			ssn_output = '***-**-' + ssn[5] + ssn[6] + ssn[7] + ssn[8];
		}

		return ssn_output;
	}

	$.WS_Form.prototype.form_ssn_format_dashed_mask_full = function(ssn) {

		var ssn_output = '';

		if (ssn.length === 1) {
			ssn_output = '*';
		} else if (ssn.length === 2) {
			ssn_output = '**';
		} else if (ssn.length === 3) {
			ssn_output = '***';
		} else if (ssn.length === 4) {
			ssn_output = '***-*';
		} else if (ssn.length === 5) {
			ssn_output = '***-**';
		} else if (ssn.length === 6) {
			ssn_output = '***-**-*';
		} else if (ssn.length === 7) {
			ssn_output = '***-**-**';
		} else if (ssn.length === 8) {
			ssn_output = '***-**-***';
		} else if (ssn.length === 9) {
			ssn_output = '***-**-****';
		}

		return ssn_output;
	}

	$.WS_Form.prototype.form_ssn_get_hidden_obj = function(input_obj) {

		return $('~ input[data-wsf-ssn-hidden]', input_obj);
	}


})(jQuery);
