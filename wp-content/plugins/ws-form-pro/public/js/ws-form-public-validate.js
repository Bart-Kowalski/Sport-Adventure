(function($) {

	'use strict';

	// Form - Validate Field
	$.WS_Form.prototype.form_validate_field = function(event) {

		var ws_this = this;

		// Set validation fields
		$('[data-type="validate"]', this.form_canvas_obj).each(function() {

			// Get field ID
			var field_id = $(this).attr('data-id');

			// Get field
			var field = ws_this.field_data_cache[field_id];

			// Get inner DOM element
			var validate_obj = $('[data-wsf-validate]', $(this));

			// Check for real-time updates
			if(
				(event == 'real_time') &&
				!ws_this.get_object_meta_value(field, 'validate_real_time', '')
			) {
				return;
			}

			// Determine scope
			switch(ws_this.get_object_meta_value(field, 'validate_scope', 'form')) {

				case 'section' :

					var validate_scope = ws_this.get_section($(this));
					break;

				case 'group' :

					var validate_scope = ws_this.get_group($(this));
					break;

				default :

					var validate_scope = ws_this.form_canvas_obj;
			}

			// Check scope
			if(!validate_scope) { validate_scope = ws_this.form_canvas_obj; }

			// Process fields in order they appear on form
			var objs_invalid = [];

			ws_this.get_field_elements(validate_scope).each(function() {

				if(ws_this.is_invalid($(this))) {

					objs_invalid.push($(this));
				}
			});

			if(objs_invalid.length) {

				// Get mask
				var validate_mask = ws_this.get_object_meta_value(field, 'validate_mask', "<h2>#validate_prefix</h2>\n\n#validate_list");
				if(validate_mask == '') { validate_mask = '<h2>#validate_prefix</h2>\n\n#validate_list'; }

				// Get message
				var validate_message = ws_this.get_object_meta_value(field, 'validate_message', '');

				// Get validate list
				var validate_list = '';

				var field_name_touched = {};

				for(var objs_invalid_index in objs_invalid) {

					if(!objs_invalid.hasOwnProperty(objs_invalid_index)) { continue; }

					var obj_error = objs_invalid[objs_invalid_index];

					var obj_field = ws_this.get_field(obj_error);
					if(!obj_field) { continue; }

					// Avoid duplicates for radio fields
					var field_name = obj_error.attr('name');
					if(field_name_touched[field_name]) { continue; }
					field_name_touched[field_name] = true;

					// Build list item
					validate_list += '<li>';

					// Should links be added?
					var li_href = ws_this.get_object_meta_value(field, 'validate_li_href', 'on') ? ((typeof(obj_error.attr('id')) !== 'undefined') ? ('#' + obj_error.attr('id')) : false) : false;

					if(li_href) {

						validate_list += '<a href="' + ws_this.esc_attr(li_href) + '">';
					}

					// Add field label
					validate_list += ws_this.esc_html(obj_field.label);

					// Should invalid feedback be shown?
					if(ws_this.get_object_meta_value(field, 'validate_li_invalid_feedback', 'on')) {

						var invalid_feedback_obj = ws_this.get_invalid_feedback_obj(obj_error);

						if(invalid_feedback_obj) {

							validate_list += ': <span class="wsf-validate-invalid-feedback">' + ws_this.esc_html(invalid_feedback_obj.html()) + '</span>';
						}
					}

					if(li_href) {

						validate_list += '</a>';
					}

					validate_list += '</li>';
				}

				if(validate_list) {

					validate_list = '<ul class="wsf-validate-list">' + validate_list + '</ul>';
				}

				// Parse mask
				var validate_mask_values = {

					'validate_message' : validate_message,
					'validate_list' : validate_list
				};

				// Build HTML
				var validate_html = ws_this.mask_parse(validate_mask, validate_mask_values);

				// Set HTML
				validate_obj.html(validate_html).parent().removeClass('wsf-validate-hidden');

				// Focus events
				$('ul.wsf-validate-list li a[href]', validate_obj).on('mousedown', function(e) {

					// Prevent default
					e.preventDefault();

					// Get href
					var li_href = $(this).attr('href');

					// Focus
					$(li_href).focus().trigger('focus');
				});

			} else {

				// Clear HTML
				validate_obj.html('').parent().addClass('wsf-validate-hidden');
			}
		});
	}

})(jQuery);
