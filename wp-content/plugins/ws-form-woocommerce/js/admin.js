(function($){ 

	'use strict';

	$.WS_Form_WooCommerce = function() {

		var ws_this = this;

		// Form assigned
		this.form_assigned();

		// Form add
		this.form_add();

		// Form existing
		this.form_existing();

		// Show panel
		$('[data-wsf-woocommerce-panel-show]').click(function() {

			var panel = $(this).attr('data-wsf-woocommerce-panel-show');
			ws_this.panel_show(panel);
		});
	}

	$.WS_Form_WooCommerce.prototype.form_assigned = function() {

		var ws_this = this;

		// Edit form
		$('[data-wsf-woocommerce-form-edit]').click(function(e) {

			e.preventDefault();

			location.href = ws_form_settings_woocommerce_product.edit_url + ws_this.get_form_id();
		});

		// Remove form
		$('[data-wsf-woocommerce-remove]').click(function(e) {

			// Confirm
			if(!confirm("Are you sure you want to remove this product customization?\n\nNote: The form will not be deleted.")) { return; }

			// Get form ID
			var form_id = ws_this.get_form_id();
			if( !form_id ) { return; }

			// Get product ID
			var product_id = parseInt( $('#post_ID').val() );
			if( !product_id ) { return; }

			// Loader on
			ws_this.panel_show('loader');

			// NONCE
			var params = {};
			params[ws_form_settings_woocommerce_product.wsf_nonce_field_name] = ws_form_settings_woocommerce_product.wsf_nonce;

			// Call REST endpoint
			var ajax_url = ws_form_settings_woocommerce_product.ajax_url + 'action/' + ws_form_settings_woocommerce_product.id + '/product/' + product_id + '/remove/';
			$.ajax({

				beforeSend: function( xhr ) {

					xhr.setRequestHeader( 'X-WP-Nonce', ws_form_settings_woocommerce_product.x_wp_nonce );
				},
				method: 'POST',
				data: params,
				url: ajax_url
			})
			.done( function( response ) {

				// Reload page
				if(
					( typeof(response) !== 'undefined' ) &&
					( typeof(response.error) !== 'undefined' ) &&
					( !response.error)
				) {

					$('#wsf-woocommerce-form-id-select').val('');

					// Loader off
					ws_this.panel_show('form_add');
				}
			})
		});

		// Edit form product settings
		$('[data-wsf-woocommerce-form-edit-woocommerce]').click(function(e) {

			e.preventDefault();

			location.href = ws_form_settings_woocommerce_product.edit_url_woocommerce + ws_this.get_form_id();
		});

		// Settings source
		this.settings_source();
		$('[name="wsf-woocommerce-settings-source"]').click(function() {

			ws_this.settings_source();
		})

		// Initial render
		this.form_assigned_render();
	}

	$.WS_Form_WooCommerce.prototype.settings_source = function() {

		var settings_source = $('[name="wsf-woocommerce-settings-source"]:checked').val();

		switch(settings_source) {

			case '' :

				$('[data-wsf-woocommerce-form-edit-settings]').hide();
				$('#wsf_woocommerce_panel_settings_source_product').show();
				break;

			case 'form' :

				$('[data-wsf-woocommerce-form-edit-settings]').show();
				$('#wsf_woocommerce_panel_settings_source_product').hide();
				break;
		}
	}

	$.WS_Form_WooCommerce.prototype.form_assigned_render = function(meta_data) {

		if(typeof(meta_data) === 'undefined') { var meta_data = false; }

		if(wsf_woocommerce_form_list.length > 0) {

			// Get top form
			var form = wsf_woocommerce_form_list[0];

			var form_id = form.id;
			var form_label = form.label;
			var form_svg = form.svg;
			var show_single_links = ( (wsf_woocommerce_form_list.length == 1) && (wsf_woocommerce_form_list[0]['match'] == 'product') );

			// Check product regular price (Needs to be set so form renders)
			this.regular_price_check();

		} else {

			var form_id = 0;
			var form_label = '';
			var form_svg = '';
			var show_single_links = false;
		}

		// Form ID
		$('#wsf-woocommerce-form-id').val( form_id );

		// Form label
		$('[data-wsf-woocommerce-form-label]').html( form_label );

		// Form SVG
		$('[data-wsf-woocommerce-svg]').html( form_svg );

		// Show remove form link?
		if (show_single_links) {

			$('[wsf_woocommerce_panel_form_assigned_single]').show();

		} else {

			$('[wsf_woocommerce_panel_form_assigned_single]').hide();
		}

		// Check if product is assigned to more that one form
		if(wsf_woocommerce_form_list.length > 1) {

			var tbody = '';

			for(var form_index in wsf_woocommerce_form_list) {

				var form = wsf_woocommerce_form_list[form_index];

				tbody += '<tr' + ((form_index == 0) ? ' class="wsf-woocommerce-form-assigned-primary no-items">' : ' class="no-items"') + '>';
				tbody += '<td>' + form.id + '</td>';
				tbody += '<td><a href="' + ws_form_settings_woocommerce_product.edit_url_woocommerce + form.id + '">' + this.html_encode(form.label) + '</a></td>';
				tbody += '<td>' + this.html_encode(form.match_description) + '</td>';
				tbody += '<td><a href="' + ws_form_settings_woocommerce_product.edit_url_woocommerce + 	form.id + '">' + this.html_encode(form.button_label) + '</a></td>';
				tbody += '</tr>';
			}

			$('#wsf_woocommerce_panel_form_assigned_multiple').show();
			$('#wsf_woocommerce_panel_form_assigned_multiple tbody').html(tbody);

		} else {

			$('#wsf_woocommerce_panel_form_assigned_multiple').hide();
		}

		// Process meta data
		this.meta_keys_set(meta_data);
	}

	$.WS_Form_WooCommerce.prototype.meta_keys_set = function(meta_data) {

		if(
			(typeof(meta_data) === 'undefined') ||
			(meta_data === false)

		) { return false; }

		for(var meta_key_index in ws_form_settings_woocommerce_product.meta_keys) {

			var meta_key_config = ws_form_settings_woocommerce_product.meta_keys[meta_key_index];

			// Checks
			if(typeof(meta_key_config['id']) === 'undefined') { continue; }
			if(typeof(meta_key_config['name']) === 'undefined') { continue; }
			if(typeof(meta_key_config['type']) === 'undefined') { continue; }

			// Get ID
			var meta_id = meta_key_config['id']

			// Get name
			var meta_name = meta_key_config['name'];

			// Get value
			var meta_value = (typeof(meta_data[meta_name]) !== 'undefined') ? meta_data[meta_name] : '';

			// Selector
			var meta_selector = '[name="wsf-woocommerce-' + meta_id + '"]';

			switch(meta_key_config['type']) {

				case 'radio' :

					$(meta_selector + '[value="' + meta_value + '"]').prop('checked', true).trigger('click');
					break;

				case 'checkbox' :

					$(meta_selector).prop('checked', ((meta_value === 'yes') || (meta_value === 'on')));
					break;

				default :

					$(meta_selector).val(meta_value);
			}
		}
	}

	$.WS_Form_WooCommerce.prototype.regular_price_check = function() {

		var price_regular = $('#_regular_price').val();
		if (price_regular == '') {
			$('#_regular_price').val('0');
		}
	}

	$.WS_Form_WooCommerce.prototype.form_existing = function() {

		var ws_this = this;

		// Form selector
		$('#wsf-woocommerce-form-id-select').change(function() {

			// Form ID
			var form_id = parseInt( $('#wsf-woocommerce-form-id-select').val() );
			if( !form_id ) { return; }

			// Get product ID
			var product_id = parseInt( $('#post_ID').val() );
			if( !product_id ) { return; }

			// Loader on
			ws_this.panel_show('loader');

			// NONCE
			var params = {};
			params[ws_form_settings_woocommerce_product.wsf_nonce_field_name] = ws_form_settings_woocommerce_product.wsf_nonce;

			// Call REST endpoint
			var ajax_url = ws_form_settings_woocommerce_product.ajax_url + 'action/' + ws_form_settings_woocommerce_product.id + '/product/' + product_id + '/form/' + form_id + '/';
			$.ajax({

				beforeSend: function( xhr ) {

					xhr.setRequestHeader( 'X-WP-Nonce', ws_form_settings_woocommerce_product.x_wp_nonce );
				},
				method: 'POST',
				data: params,
				url: ajax_url
			})
			.done( function( response ) {

				// Reload page
				if(
					( typeof(response) !== 'undefined' ) &&
					( typeof(response.error) !== 'undefined' ) &&
					( !response.error)
				) {

					// Load form list
					wsf_woocommerce_form_list = response.form_list;

					// Render form assigned
					ws_this.form_assigned_render(response.meta_data);

					// Loader off
					ws_this.panel_show('form_assigned');

					// Reset form selector
					$('#wsf-woocommerce-form-id-select').val('');
				}
			})
		});
	}

	$.WS_Form_WooCommerce.prototype.form_add = function() {

		var ws_this = this;

		// Wizard clicked
		$('#wsf_woocommerce_data [data-action="wsf-add-blank"], #wsf_woocommerce_data [data-action="wsf-add-template"]').click(function(e) {

			e.preventDefault();

			// Loader on
			ws_this.panel_show('loader');

			// Get product ID
			var product_id = parseInt( $('#post_ID').val() );
			if( !product_id ) { return; }

			// Get template ID
			var template_id = $(this).attr( 'data-id' );
			if( !template_id ) { template_id = 'blank'; }

			// NONCE
			var params = {};
			params[ws_form_settings_woocommerce_product.wsf_nonce_field_name] = ws_form_settings_woocommerce_product.wsf_nonce;

			// Call REST endpoint
			var ajax_url = ws_form_settings_woocommerce_product.ajax_url + 'action/' + ws_form_settings_woocommerce_product.id + '/product/' + product_id + '/template/' + template_id + '/';
			$.ajax({

				beforeSend: function( xhr ) {

					xhr.setRequestHeader( 'X-WP-Nonce', ws_form_settings_woocommerce_product.x_wp_nonce );
				},
				method: 'POST',
				data: params,
				url: ajax_url
			})
			.done( function( response ) {

				// Reload page
				if(
					( typeof(response) !== 'undefined' ) &&
					( typeof(response.error) !== 'undefined' ) &&
					( !response.error)
				) {

					// Load form list
					wsf_woocommerce_form_list = response.form_list;

					// Render form assigned
					ws_this.form_assigned_render(response.meta_data);

					// Loader off
					ws_this.panel_show('form_assigned');
				}
			});
		});
	}

	$.WS_Form_WooCommerce.prototype.get_form_id = function() {

		// Get form ID
		return parseInt($('#wsf-woocommerce-form-id').val());
	}

	$.WS_Form_WooCommerce.prototype.panel_show = function(panel) {

		$('.wsf_woocommerce_panel').hide();
		$('#wsf_woocommerce_panel_' + panel).show();
	}

	// HTML encode string
	$.WS_Form_WooCommerce.prototype.html_encode = function(input) {

		if(typeof(input) !== 'string') { return input; }

		var return_html = this.replace_all(input, '&', '&amp;');
		return_html = this.replace_all(return_html, '<', '&lt;');
		return_html = this.replace_all(return_html, '>', '&gt;');
		return_html = this.replace_all(return_html, '"', '&quot;');

		return return_html;
	}

	// Replace all
	$.WS_Form_WooCommerce.prototype.replace_all = function (input, search, replace) {

		if (replace === undefined) {
			return input.toString();
		}
		return input.split(search).join(replace);
	}

	new $.WS_Form_WooCommerce();

})(jQuery);
