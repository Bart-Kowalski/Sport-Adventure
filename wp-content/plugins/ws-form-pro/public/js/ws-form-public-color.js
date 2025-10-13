(function($) {

	'use strict';

	// Form - Color
	$.WS_Form.prototype.form_color = function() {

		// Do not load color picker if no color fields found
		if(!$('[data-type="color"] input', this.form_canvas_obj).length) { return false; }

		// Do not use color picker
		if($.WS_Form.settings_plugin.ui_color == 'off') { return false; }

		if(

			// Use color picker
			($.WS_Form.settings_plugin.ui_color == 'on') ||

			// If browser does not support native color picked, use minicolors
			(
				($.WS_Form.settings_plugin.ui_color == 'native') &&
				!this.native_color
			)
		) {

			this.form_color_process();
		}
	}

	// Form - Color - Process
	$.WS_Form.prototype.form_color_process = function() {

		var ws_this = this;

		// Check to see if minicolors loaded
		if(typeof(jQuery().minicolors) !== 'undefined') {

			$('[data-type="color"] input', this.form_canvas_obj).each(function() {

				if(
					// Use color picker
					($.WS_Form.settings_plugin.ui_color == 'on') ||

					// If browser does not support native color picker, use minicolors
					(
						($.WS_Form.settings_plugin.ui_color == 'native') &&
						!ws_this.native_color
					)

				) {
					// Custom invalid feedback text
					var invalid_feedback_obj = ws_this.get_invalid_feedback_obj($(this));

					// Get framework specific args
					var args = (typeof(ws_this.framework.minicolors_args) !== 'undefined') ? ws_this.framework.minicolors_args : {};

					// Apply minicolors
					$(this).minicolors(args);

					// Move invalid feedback div
					invalid_feedback_obj.insertAfter($(this));
				}
			});
		}
	}

})(jQuery);
