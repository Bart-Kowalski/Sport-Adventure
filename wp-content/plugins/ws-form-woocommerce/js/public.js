(function($){ 

	'use strict';

	$.WS_Form_WooCommerce_Public = function() {

		this.form_validate_do = true;

		var ws_this = this;

		// WS Form - Rendered
	    $(document).on('wsf-rendered', function(e, form, form_id, form_instance_id, form_obj, form_canvas_obj) {

	    	// If this is not a WooCommerce form, return
			var product_id = ws_this.get_product_id(form_obj, false);
			if(product_id === false) { return; }
	    	if(!form_obj.hasClass('cart')) { return; }

			// Add class
			form_obj.addClass('wsf-woocommerce');

	    	// Get WS Form instance
	    	var ws_form = window.wsf_form_instances[form_instance_id];

	    	// Check that ws_form_settings_woocommerce_product
			if(typeof(window.ws_form_settings_woocommerce_product) === 'undefined') {

				window.ws_form_settings_woocommerce_product = {};
			}
			if(typeof(window.ws_form_settings_woocommerce_product[product_id]) === 'undefined') {

				// Get localization data via API
				ws_form.api_call('action/woocommerce/product/' + product_id, 'GET', false, function(response) {

					if(typeof(window.ws_form_settings_woocommerce_product.account) === 'undefined') {

						// Store localization data
						window.ws_form_settings_woocommerce_product = response;

					} else {

						// Merge localization data
						window.ws_form_settings_woocommerce_product[product_id] = response[product_id];
						window.ws_form_settings_woocommerce_product.account.lookup_price = Object.assign(window.ws_form_settings_woocommerce_product.account.lookup_price, response.account.lookup_price);
						window.ws_form_settings_woocommerce_product.account.lookup_price_regular = Object.assign(window.ws_form_settings_woocommerce_product.account.lookup_price_regular, response.account.lookup_price_regular);
						window.ws_form_settings_woocommerce_product.account.lookup_quantity_min = Object.assign(window.ws_form_settings_woocommerce_product.account.lookup_quantity_min, response.account.lookup_quantity_min);
						window.ws_form_settings_woocommerce_product.account.lookup_quantity_max = Object.assign(window.ws_form_settings_woocommerce_product.account.lookup_quantity_max, response.account.lookup_quantity_max);
					}

					// Initialize
					ws_this.initialize(e, form, form_id, form_instance_id, form_obj, form_canvas_obj);
				});

			} else {

				// Initialize
				ws_this.initialize(e, form, form_id, form_instance_id, form_obj, form_canvas_obj);
			}
		});

		// Third Party - WooCommerce Quick View
		this.third_party_wc_quick_view();
	}

	// Initialize
	$.WS_Form_WooCommerce_Public.prototype.initialize = function(e, form, form_id, form_instance_id, form_obj, form_canvas_obj) {

		var ws_this = this;

    	// Get WS Form instance
    	var ws_form = window.wsf_form_instances[form_instance_id];

		// Change WS Form submit event handling
		ws_form.form_ajax = false

		// Add to cart click event tells us if the normal add to cart button was clicked
		$('[name="add-to-cart"]', form_canvas_obj).click(function() { $(this).attr('data-wsf-wc-clicked', ''); })

		// Hide WooCommerce elements
		var product_classes = [];

		// Get product settings
		var id = this.get_product_id(form_obj);
		var settings = this.get_product_settings(form_obj);
		var settings_account = this.get_account_settings();

		// Hide product variation price
		if(settings.product_price_variation_hide) { product_classes.push('wsf-wc-product-price-variation-hide'); }

		// Hide quantity
		if(settings.product_quantity_hide) { product_classes.push('wsf-wc-product-quantity-hide'); }

		// Hide add to cart
		if(settings.product_add_to_cart_hide) { product_classes.push('wsf-wc-product-add-to-cart-hide'); }

		// Apply classes
		if(product_classes.length > 0) { form_obj.closest('.product').addClass(product_classes.join(' ')); }

    	// Form validation
		var variation_obj= $('input[name="variation_id"]', form_obj);
    	var form_valid_wc = (!variation_obj.length || (parseInt(variation_obj.val()) > 0));
    	var form_valid_wsf = ws_form.form_valid;

		// Form validation events - WS Form - Register form validation real time hook
		ws_form.form_validate_real_time_register_hook(function(form_valid, form, form_id, form_instance_id, form_obj, form_canvas_obj) {

 			form_valid_wsf = form_valid;
			ws_this.form_validate(ws_form, form_obj, form_valid_wc && form_valid_wsf);
		});

		// Form validation events - WooCommerce
		form_obj.on('show_variation', function() {

			form_valid_wc = true;
			ws_form.form_validate_real_time_process();
		});
		form_obj.on('hide_variation', function() {

			form_valid_wc = false;
			ws_form.form_validate_real_time_process();
		});

		// Initial validation
		ws_this.form_validate(ws_form, form_obj, form_valid_wc && form_valid_wsf);

		// Get quantity min and max values
		var lookup_quantity_min = parseFloat(settings_account.lookup_quantity_min[id]);
		var lookup_quantity_max = parseFloat(settings_account.lookup_quantity_max[id]);

		// Custom quantity fields
		var quantity_wsf_obj = $('input[data-wsf-wc-quantity]', form_canvas_obj);
		var quantity_woocommerce_obj = $('input[name="quantity"]', form_obj);
		if(quantity_wsf_obj.length) {

			// Set attribute - Min
			if(typeof(quantity_wsf_obj.attr('min')) === 'undefined') { quantity_wsf_obj.attr('min', lookup_quantity_min); };

			// Set attribute - Max
			if((lookup_quantity_max != -1) && (typeof(quantity_wsf_obj.attr('min')) === 'undefined')) { quantity_wsf_obj.attr('min', lookup_quantity_min); };

			// Set attribute - Step
			if(typeof(quantity_wsf_obj.attr('step')) === 'undefined') { quantity_wsf_obj.attr('step', ((typeof(quantity_woocommerce_obj.attr('step')) !== undefined) ? quantity_woocommerce_obj.attr('step') : 1)); };

			// Check default values and determine which quantity to reference
			var quantity_wsf_default = parseInt(quantity_wsf_obj.val());
			var quantity_woocommerce_default = parseInt(quantity_woocommerce_obj.val());
			var quantity_update_wsf = (quantity_woocommerce_default >= quantity_wsf_default);
			var quantity_reference_obj = quantity_update_wsf ? quantity_woocommerce_obj : quantity_wsf_obj;

			// Set initial values
			ws_this.quantity_process(quantity_reference_obj, quantity_update_wsf, quantity_wsf_obj, quantity_woocommerce_obj, lookup_quantity_min, lookup_quantity_max);

			// Run quantity again after 250ms (for back button)
			setTimeout(function() {

				ws_this.quantity_process(quantity_reference_obj, quantity_update_wsf, quantity_wsf_obj, quantity_woocommerce_obj, lookup_quantity_min, lookup_quantity_max);

			}, 250);

			// Custom quantity change event
			quantity_wsf_obj.on('change input paste', function() {

				ws_this.quantity_process($(this), false, quantity_wsf_obj, quantity_woocommerce_obj, lookup_quantity_min, lookup_quantity_max);
			});

			// WooCommerce quantity change event
			quantity_woocommerce_obj.on('change input paste', function() {

				ws_this.quantity_process($(this), true, quantity_wsf_obj, quantity_woocommerce_obj, lookup_quantity_min, lookup_quantity_max);
			});
		}

		// WooCommerce quantity change event
		quantity_woocommerce_obj.on('change input paste', function() {

			ws_this.prices_calculate(ws_form, form_obj, form_instance_id, true, true);
		});

		// Initial prices render
		ws_this.prices_calculate(ws_form, form_obj, form_instance_id, true, true);

		// Run price calculation again (for back button)
		setTimeout(function() {

			ws_this.prices_calculate(ws_form, form_obj, form_instance_id, true, true);

		}, 250);

		// WS Form cart total change
		$('#wsf-' + form_instance_id + '-ecommerce-cart-total', form_canvas_obj).on('change', function() { ws_this.prices_calculate(ws_form, form_obj, form_instance_id, true, false); });

		// Variation change
		if(variation_obj.length) {

			if('MutationObserver' in window) {

				var observer = new MutationObserver(function(mutations) {

					var variation_id_old = false;

					mutations.forEach(function(mutation) {

						var variation_id_new = mutation.target.value;
						if(variation_id_new != variation_id_old) {

 							ws_this.prices_calculate(ws_form, form_obj, form_instance_id, true, false);
							variation_id_old = variation_id_new;
						}
					});
				});
				observer.observe(variation_obj[0], { attributes: true });
	
			} else {
	
				form_obj.on('woocommerce_variation_select_change', function() { ws_this.prices_calculate(ws_form, form_obj, form_instance_id, false, false); });
			}
		}

		// WS Form events
		this.ws_form_events(form_instance_id);

		// WooCommerce Variation Styling
		this.style_woocommerce(form_obj, form_instance_id);

		// Third Party - PluginHive Booking and Appointments
		this.third_party_pluginhive_booking(form_obj, form_instance_id);
	}

	// WS Form events
	$.WS_Form_WooCommerce_Public.prototype.ws_form_events = function(form_instance_id) {

		var ws_this = this;

		var ws_form_instance_id = form_instance_id;

    	// Get WS Form instance
    	var ws_form = window.wsf_form_instances[form_instance_id];

	    // WS Form - Submit before
	    $(document).on('wsf-submit-before', function(e, form, form_id, form_instance_id, form_obj, form_canvas_obj) {

	    	if(form_instance_id !== ws_form_instance_id) { return; }

	    	ws_this.form_validate_do = false;
	    });

	    // WS Form - Submit validate fail
	    $(document).on('wsf-submit-validate-fail', function(e, form, form_id, form_instance_id, form_obj, form_canvas_obj) {

	    	if(form_instance_id !== ws_form_instance_id) { return; }

	    	ws_this.form_validate_do = true;
	    	ws_form.form_validate_real_time_process();
	    });

	    // WS Form - Submit complete
	    $(document).on('wsf-submit-complete', function(e, form, form_id, form_instance_id, form_obj, form_canvas_obj) {

	    	if(form_instance_id !== ws_form_instance_id) { return; }

	    	// Get WS Form instance
	    	var ws_form = window.wsf_form_instances[form_instance_id];

			// Get product settings
			var id = ws_this.get_product_id(form_obj);
			var settings = ws_this.get_product_settings(form_obj);

			// Add add-to-cart hidden field
			if(!$('input[name="add-to-cart"]', form_obj).length && id) {

				ws_form.form_add_hidden_input('add-to-cart', id);
			}

			// Cart item key
			var cart_item_key = (typeof(settings.cart_item_key) !== 'undefined') ? settings.cart_item_key : false;
			if(cart_item_key !== false) { ws_form.form_add_hidden_input('wsf_cart_item_key', cart_item_key); }

			// Cart item nonce
			var nonce_name = (typeof(settings.nonce_name) !== 'undefined') ? settings.nonce_name : false;
			var nonce_value = (typeof(settings.nonce_value) !== 'undefined') ? settings.nonce_value : false;
			if(

				(nonce_name !== false) &&
				(nonce_value !== false)

			) { ws_form.form_add_hidden_input(nonce_name, nonce_value); }

			// Submit the form
			ws_form.form_obj[0].submit();
		});

	    // WS Form - Reset complete
	    $(document).on('wsf-reset-complete wsf-clear-complete', function(e, form, form_id, form_instance_id, form_obj, form_canvas_obj) {

	    	if(form_instance_id !== ws_form_instance_id) { return; }
	    	$('.reset_variations', form_obj).click();
	    });
	}

	// Third Party - WooCommerce - QuickView
	$.WS_Form_WooCommerce_Public.prototype.third_party_wc_quick_view = function() {

		var ws_this = this;
		var ws_instance_id = 0;

		// WooCommerce quick view / Yith quick view
		$(document).on('quick-view-displayed qv_loader_stop', function() {

			if(typeof(wsf_form_init) === 'function') {

				wsf_form_init();

			} else {

				// Legacy initialization

				// Reset instance ID
				ws_instance_id = 0;
				$('.wsf-form').each(function() {

					var instance_id_single = parseInt($(this).attr('data-instance-id'));
					if(instance_id_single > ws_instance_id) { ws_instance_id = instance_id_single; }
				});

				ws_instance_id++;

				// Render each form
				$('.pp_woocommerce_quick_view .wsf-form, #yith-quick-view-content .wsf-form').each(function() {

					// Set ID
					$(this).attr('id', 'ws-form-' + ws_instance_id);

					// Set instance ID
					$(this).attr('data-instance-id', ws_instance_id);

					var id = $(this).attr('id');
					var form_id = $(this).attr('data-id');
					var instance_id = $(this).attr('data-instance-id');

					var ws_form = new $.WS_Form();
					window.wsf_form_instances[instance_id] = ws_form;

					ws_form.render({

						'obj' : 		'#' + id,
						'form_id':		form_id
					});
				});
			}
		});
	}

	// Third Party - PluginHive Booking and Appointments
	$.WS_Form_WooCommerce_Public.prototype.third_party_pluginhive_booking = function(form_obj, form_instance_id) {

		var ws_this = this;

		// Check if extension is active on the page
		if(!$('.phive_booked_price').length) { return; }

		// Look for change events
		$('.booking-info-wraper').on('change', function() {

			// Get product ID
			var id = ws_this.get_product_id(form_obj);
			if(!id) { return; }

			// Check ws_form_settings_woocommerce_product exists
			if(typeof(ws_form_settings_woocommerce_product) === 'undefined') { return false; }
			if(typeof(ws_form_settings_woocommerce_product.account) === 'undefined') { return false; }

			// Get new price
			var phive_booked_price = $('.phive_booked_price').val();

			// Set price lookups
			ws_form_settings_woocommerce_product.account.lookup_price[id] = phive_booked_price;
			ws_form_settings_woocommerce_product.account.lookup_price_regular[id] = phive_booked_price;

			// Get WS Form instance
			var ws_form = window.wsf_form_instances[form_instance_id];

			// Re-render prices
			ws_this.prices_calculate(ws_form, form_obj, form_instance_id, true, false);
		});
	}

	// WooCommerce - Style Variations
    $.WS_Form_WooCommerce_Public.prototype.style_woocommerce = function(form_obj, form_instance_id) {

		// Get product settings
		var settings = this.get_product_settings(form_obj);

		var style_woocommerce = (typeof(settings.style_woocommerce) !== 'undefined') ? settings.style_woocommerce : false;
		if(style_woocommerce) {

	    	// Get WS Form instance
	    	var ws_form = window.wsf_form_instances[form_instance_id];

			// Style label
			var class_field_label_array = ws_form.get_field_value_fallback('select', 'top', 'class_field_label', false);
			if(typeof(class_field_label_array) === 'object') {

				$('.variations td.label label', form_obj).addClass(class_field_label_array.join(' '));
			}

			// Style field
			var class_field_array = ws_form.get_field_value_fallback('select', 'top', 'class_field', false);
			if(typeof(class_field_array) === 'object') {

				$('.variations td.value select', form_obj).addClass(class_field_array.join(' '));
			}
		}
	}

    // Validate form
    $.WS_Form_WooCommerce_Public.prototype.form_validate = function(ws_form, form_obj, form_valid) {

		// Get product settings
		var settings = this.get_product_settings(form_obj);

    	if(!this.form_validate_do || settings.product_form_validate) { return; }

		// WooCommerce add to cart button
		var add_to_cart_obj = $('.single_add_to_cart_button', form_obj);
		add_to_cart_obj.attr('disabled', (form_valid) ? false : '');

		// Button selector
		var button_selector = 'button[type="submit"].wsf-button, input[type="submit"].wsf-button, button[data-ecommerce-payment].wsf-button, [data-post-lock]';

		// WS Form buttons
		if(form_valid) {

			if(typeof(ws_form.get_form_post_lock_button_objs) === 'function') {

				ws_form.form_post_unlock('not-allowed', false, true, button_selector);

			} else {

				ws_form.form_post_unlock('not-allowed', false, true, false);
			}

		} else {

			if(typeof(ws_form.get_form_post_lock_button_objs) === 'function') {

				ws_form.form_post_lock('not-allowed', true, false, button_selector);

			} else {

				ws_form.form_post_lock('not-allowed', true, false, false);
			}
		}
	}

    // Process quantity
    $.WS_Form_WooCommerce_Public.prototype.quantity_process = function(source_obj, update_wsf, quantity_wsf_obj, quantity_woocommerce_obj, lookup_quantity_min, lookup_quantity_max) {

		var quantity = parseInt(source_obj.val());
		if(quantity < lookup_quantity_min) { quantity = lookup_quantity_min; }
		if((lookup_quantity_max != -1) && (quantity > lookup_quantity_max)) { quantity = lookup_quantity_max; }
		if(update_wsf) {
			quantity_wsf_obj.val(quantity);
		} else {
			quantity_woocommerce_obj.val(quantity).trigger('change');
		}
    }

    // Get product settings
    $.WS_Form_WooCommerce_Public.prototype.get_product_settings = function(form_obj) {

		var product_id = this.get_product_id(form_obj, false);
		if(!product_id) { return; }

		if(typeof(ws_form_settings_woocommerce_product) === 'undefined') { return false; }
		if(typeof(ws_form_settings_woocommerce_product[product_id]) === 'undefined') { return false; }

		return ws_form_settings_woocommerce_product[product_id];
    }

    // Get account settings
    $.WS_Form_WooCommerce_Public.prototype.get_account_settings = function() {

		if(typeof(ws_form_settings_woocommerce_product) === 'undefined') { return false; }
		if(typeof(ws_form_settings_woocommerce_product.account) === 'undefined') { return false; }

		return ws_form_settings_woocommerce_product.account;
	}

	// Calculate prices
	$.WS_Form_WooCommerce_Public.prototype.prices_calculate = function(ws_form, form_obj, form_instance_id, immediate, form_calc) {

		var ws_this = this;

		// Get account settings
		var settings_account = this.get_account_settings();

		// Read localized data
		var precision = settings_account.precision;
		var thousand = settings_account.thousand;
		var decimal = settings_account.decimal;
		var format_money_object = {

			symbol: settings_account.symbol,
			decimal: decimal,
			thousand: thousand,
			precision: precision,
			format: settings_account.format
		};

		var fn_prices_calculate = function() {

			// Get product ID
			var id = ws_this.get_product_id(form_obj);
			if(!id) { return; }

			// Calculate amounts
			var ws_form_cart_total_float = (
				( 'undefined' !== typeof( ws_form.ecommerce_cart_price_type ) ) &&
				( 'undefined' !== typeof( ws_form.ecommerce_cart_price_type['total'] ) ) &&
				( 'undefined' !== typeof( ws_form.ecommerce_cart_price_type['total']['float'] ) )
			) ? ws_form.ecommerce_cart_price_type['total']['float'] : 0;

			// Populate WooCommerce price
			var woocommerce_price = settings_account.lookup_price[id];
			var woocommerce_price_float = (woocommerce_price ? parseFloat(woocommerce_price) : 0);
			var woocommerce_price_string = accounting.formatNumber(woocommerce_price_float, precision, thousand, decimal);
			var woocommerce_price_currency = accounting.formatMoney(woocommerce_price_float, format_money_object);
			ws_form.form_ecommerce_price_type_set('woocommerce_price', '[data-ecommerce-cart-price-woocommerce_price]', '#wsf-' + form_instance_id + '-ecommerce-cart-woocommerce_price', woocommerce_price_float, woocommerce_price_string, woocommerce_price_currency);

			// Populate WooCommerce price regular
			var woocommerce_price_regular = settings_account.lookup_price_regular[id];
			var woocommerce_price_regular_float = (woocommerce_price_regular ? parseFloat(woocommerce_price_regular) : 0);
			var woocommerce_price_regular_string = accounting.formatNumber(woocommerce_price_regular_float, precision, thousand, decimal);
			var woocommerce_price_regular_currency = accounting.formatMoney(woocommerce_price_regular_float, format_money_object);
			ws_form.form_ecommerce_price_type_set('woocommerce_price_regular', '[data-ecommerce-cart-price-woocommerce_price_regular]', '#wsf-' + form_instance_id + '-ecommerce-cart-woocommerce_price_regular', woocommerce_price_regular_float, woocommerce_price_regular_string, woocommerce_price_regular_currency);

			// Populate WooCommerce price total
			var woocommerce_price_total_float = woocommerce_price_float + ws_form_cart_total_float;
			var woocommerce_price_total_string = accounting.formatNumber(woocommerce_price_total_float, precision, thousand, decimal);
			var woocommerce_price_total_currency = accounting.formatMoney(woocommerce_price_total_float, format_money_object);
			ws_form.form_ecommerce_price_type_set('woocommerce_price_total', '[data-ecommerce-cart-price-woocommerce_price_total]', '#wsf-' + form_instance_id + '-ecommerce-cart-woocommerce_price_total', woocommerce_price_total_float, woocommerce_price_total_string, woocommerce_price_total_currency);

			// Populate WooCommerce price cart item total
			var quantity_woocommerce_obj = $('input[name="quantity"]', form_obj);
			var woocommerce_quantity = (quantity_woocommerce_obj.length) ? parseFloat(quantity_woocommerce_obj.val()) : 1;
			var woocommerce_price_cart_item_total_float = (woocommerce_quantity * woocommerce_price_total_float);
			var woocommerce_price_cart_item_total_string = accounting.formatNumber(woocommerce_price_cart_item_total_float, precision, thousand, decimal);
			var woocommerce_price_cart_item_total_currency = accounting.formatMoney(woocommerce_price_cart_item_total_float, format_money_object);
			ws_form.form_ecommerce_price_type_set('woocommerce_price_cart_item_total', '[data-ecommerce-cart-price-woocommerce_price_cart_item_total]', '#wsf-' + form_instance_id + '-ecommerce-cart-woocommerce_price_cart_item_total', woocommerce_price_cart_item_total_float, woocommerce_price_cart_item_total_string, woocommerce_price_cart_item_total_currency);

			// Process calculations
			if(form_calc) {

				ws_form.form_calc();
			}
		}

		immediate ? fn_prices_calculate() : setTimeout(fn_prices_calculate, 1000);
	}

	$.WS_Form_WooCommerce_Public.prototype.get_product_id = function(form_obj, check_variant) {

		if(typeof(check_variant) === 'undefined') { var check_variant = true; }

		// Get product ID
		var product_id = false;

		// Product ID - Check for hidden field
		if($('input[name="product_id"]', form_obj).length) { product_id = parseInt($('input[name="product_id"]', form_obj).val()); }

		// Product ID - Check for add to cart button
		if(!product_id && $('button[name="add-to-cart"]', form_obj).length) { product_id = parseInt($('button[name="add-to-cart"]', form_obj).val()); }

		// Product ID - Check for hidden add to cart field
		if(!product_id && $('input[name="add-to-cart"]', form_obj).length) { product_id = parseInt($('input[name="add-to-cart"]', form_obj).val()); }

		// Product ID - Check for localization product ID
		if(
			!product_id &&
			(typeof(ws_form_settings_woocommerce_product) !== 'undefined') &&
			(typeof(ws_form_settings_woocommerce_product.product_id_last) !== 'undefined')
		) {

			product_id = ws_form_settings_woocommerce_product.product_id_last;
		}

		if(!product_id) { return false; }
		if(!check_variant) { return product_id; }

		// Get variation ID
		var variation_id = false;

		// Variation ID - Check for hidden field
		if($('input[name="variation_id"]', form_obj).length) { variation_id = parseInt($('input[name="variation_id"]', form_obj).val()); }

		return variation_id ? variation_id : product_id;
	}

	new $.WS_Form_WooCommerce_Public();

})(jQuery);

