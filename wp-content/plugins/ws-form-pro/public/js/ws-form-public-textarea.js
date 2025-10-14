(function($) {

	'use strict';

	// Text area
	$.WS_Form.prototype.form_textarea = function() {

		var ws_this = this;

		// Text Editor
		$('[data-textarea-type="tinymce"]', this.form_canvas_obj).each(function() {

			if(
				(typeof(wp) === 'undefined') ||
				(typeof(wp.editor) === 'undefined') ||
				(typeof(wp.editor.remove) === 'undefined') ||
				(typeof(wp.editor.initialize) === 'undefined')
			) {
				return;
			}

			var id = $(this).attr('id');
			var toolbar = $(this).attr('data-textarea-toolbar');

			switch(toolbar) {

				case 'compact' :

					var wp_editor_params = { 

						tinymce: { 

							toolbar1: ws_form_settings.tinymce_toolbar1_compact,
							toolbar2: ws_form_settings.tinymce_toolbar2_compact,
							toolbar3: ws_form_settings.tinymce_toolbar3_compact,
							toolbar4: ws_form_settings.tinymce_toolbar4_compact,
							plugins: ws_form_settings.tinymce_plugins_compact
						}
					}

					break;

				default :

					var wp_editor_params = { 

						tinymce: { 

							toolbar1: ws_form_settings.tinymce_toolbar1_full,
							toolbar2: ws_form_settings.tinymce_toolbar2_full,
							toolbar3: ws_form_settings.tinymce_toolbar3_full,
							toolbar4: ws_form_settings.tinymce_toolbar4_full,
							plugins: ws_form_settings.tinymce_plugins_full
						}
					}
			}

			// Standard features
			wp_editor_params.tinymce.wpautop = true;
			wp_editor_params.quicktags = true;

			// Check for settings
			var field = ws_this.get_field($(this));
			wp_editor_params.tinymce.paste_as_text = (ws_this.get_object_meta_value(field, 'tinymce_paste_as_text', '') == 'on');
			wp_editor_params.mediaButtons = ws_form_settings.wp_media && (ws_this.get_object_meta_value(field, 'visual_editor_media', '') == 'on');

			wp_editor_params.tinymce.init_instance_callback = function (editor) {

				editor.on('change input', function (e) {

					$('#' + editor.id, ws_this.form_canvas_obj).val(wp.editor.getContent(editor.id)).trigger(e.type);
				});

				var textarea_obj = $('#' + editor.id, ws_this.form_canvas_obj);
				var invalid_feedback_obj = ws_this.get_invalid_feedback_obj(textarea_obj);
				invalid_feedback_obj.before(textarea_obj.detach());
			};

			// Initialize
			wp.editor.remove(id);
			wp.editor.initialize(id, wp_editor_params);

			// Fancybox support
			$(document).on('afterShow.fb', function(e, instance, current) {

				// If fancybox shown contains this form, the initialize any text editors
				if(
					(typeof(current.$slide) !== 'undefined') &&
					$('#' + ws_this.form_obj_id, current.$slide).length
				) {

					// Initialize
					wp.editor.remove(id);
					wp.editor.initialize(id, wp_editor_params);
				}
			});

			// Focus support
			$(this).on('focus', function() {

				var editor = tinyMCE.get($(this).attr('id'));

				if(editor) {

					var container = (editor.iframeElement && editor.iframeElement.closest)
						? editor.iframeElement.closest('[data-type="textarea"]')
						: editor.getContainer();

					if(container) {

						container.scrollIntoView({

							behavior: 'instant',
							block: 'center'
						});
					}

					editor.focus();
				}
			});
		})

		// Remove existing instances of CodeMiror
		$('.CodeMirror', ws_this.form_canvas_obj).each(function() {

			$(this).remove();
		});

		// Add code mirror instances
		$('[data-textarea-type="html"]', this.form_canvas_obj).each(function() {

			if(typeof(wp) === 'undefined') { return; }
			if(typeof(wp.codeEditor) === 'undefined') { return; }

			var id = $(this).attr('id');
			if($(id).length === 0) { return; }

			// Initialize
			wp.codeEditor.initialize(id);

			// Handle keyup events
			$('.CodeMirror', ws_this.form_canvas_obj).each(function() {

				var code_editor = $(this)[0].CodeMirror;
				code_editor.on("keyup", function (cm, event) {

					var code_editor_value = cm.getValue();
					var code_editor_textarea = cm.getTextArea();
					$(code_editor_textarea, ws_this.form_canvas_obj).val(code_editor_value).trigger('keyup');
				});
			});
		});
	}

	// Text editor set value
	$.WS_Form.prototype.textarea_set_value = function(obj, value) {

		obj.filter('textarea').each(function() {

			var textarea_type = (typeof($(this).attr('data-textarea-type')) !== 'undefined') ? $(this).attr('data-textarea-type') : false;

			if(textarea_type !== false) {

				switch(textarea_type) {

					case 'tinymce' :

						if(typeof(wp) === 'undefined') { break; }
						if(typeof(wp.editor) === 'undefined') { break; }
						var editor = tinyMCE.get($(this).attr('id'));
						if(editor) { editor.setContent(value); }
						break;

					case 'html' :

						if(typeof(wp) === 'undefined') { break; }
						if(typeof(wp.CodeMirror) === 'undefined') { break; }
						var editor = $(this).next().get(0).CodeMirror;
						if(editor) { editor.getDoc().setValue(value); }
						break;
				}

			}
		});
	}

})(jQuery);
