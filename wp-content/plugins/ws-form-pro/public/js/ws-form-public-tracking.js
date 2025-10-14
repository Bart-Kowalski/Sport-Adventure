(function($) {

	'use strict';

	// Form tracking
	$.WS_Form.prototype.form_tracking = function() {

		// Get form tracking values
		var tracking_values = this.form_tracking_get_values();

		for(var tracking_value_index in tracking_values) {

			if(!tracking_values.hasOwnProperty(tracking_value_index)) { continue; }

			var tracking_value = tracking_values[tracking_value_index];

			switch(tracking_value.client_source) {

				case 'geo_location' :

					// Does browser support geolocation?
					if(!navigator.geolocation) { break; }

					// Get geo location
					var ws_this = this;

					navigator.geolocation.getCurrentPosition(

						function(position) {

							var tracking_geo_location = position.coords.latitude + ',' + position.coords.longitude;

							// Set hidden value
							ws_this.form_geo_location_process(tracking_geo_location);

							// Debug
							ws_this.log('log_tracking_geo_location', tracking_geo_location, 'tracking');
						},

						function(error) {

							// Set hidden value
							ws_this.form_geo_location_process(error.code);

							// Debug
							ws_this.error('error_tracking_geo_location', ($.WS_Form.debug_rendered ? ws_this.form_geo_location_get_error(error) : ''), 'tracking');
						}
					);

					continue;

				default :

					// Add to form
					if(tracking_value.server_query_var) {

						this.form_add_hidden_input(tracking_value.server_query_var, tracking_value.value);
					}
			}
		}
	}

	// Form tracking - Get values
	$.WS_Form.prototype.form_tracking_get_values = function() {

		var tracking_values = [];

		for(var tracking_id in $.WS_Form.tracking) {

			if(!$.WS_Form.tracking.hasOwnProperty(tracking_id)) { continue; }

			var tracking_config = $.WS_Form.tracking[tracking_id];	

			// Check this tracking method is enabled
			if(!this.get_object_meta_value(this.form, tracking_id, false)) { continue; }

			// Get server query var
			var server_query_var = (typeof(tracking_config.server_query_var) !== 'undefined') ? tracking_config.server_query_var : false;

			// Get client source
			if(typeof(tracking_config.client_source) === 'undefined') { continue; }
			var client_source = tracking_config.client_source;

			switch(client_source) {

				case 'geo_location' :

					var tracking_value = '';
					break;

				default :

					var tracking_value = this.form_tracking_get_value(tracking_config);
					if(tracking_value === false) { continue; }
			}

			tracking_values.push({

				id: tracking_id,
				client_source: client_source,
				server_query_var: server_query_var,
				value: tracking_value
			});
		}

		return tracking_values;
	}

	// Form tracking - Get value
	$.WS_Form.prototype.form_tracking_get_value = function(tracking_config) {

		// Get client source
		if(typeof(tracking_config.client_source) === 'undefined') { return false; }
		var client_source = tracking_config.client_source;

		switch(client_source) {

			case 'query_var' :

				// Get client query var
				if(typeof(tracking_config.client_query_var) === 'undefined') { return false; }
				var client_query_var = tracking_config.client_query_var;

				// Read query var value
				return this.get_query_var(client_query_var);

			case 'referrer' :

				// Get document referrer
				return (typeof(document.referrer) !== 'undefined') ? document.referrer : '';

			case 'href' :

				// Get location HREF
				return (typeof(location.href) !== 'undefined') ? location.href : '';

			case 'hostname' :

				// Get location pathname
				return (typeof(location.hostname) !== 'undefined') ? location.hostname : '';

			case 'pathname' :

				// Get location pathname
				return (typeof(location.pathname) !== 'undefined') ? location.pathname : '';

			case 'query_string' :

				// Get location query string (search)
				return (typeof(location.search) !== 'undefined') ? location.search : '';

			case 'hash' :

				// Get location hash
				return (typeof(location.hash) !== 'undefined') ? location.hash : '';

			case 'os' :

				// Get window.navigator.platform
				if(typeof(window.navigator) === 'undefined') { break; }
				return (typeof(window.navigator.platform) !== 'undefined') ? window.navigator.platform : '';

			case 'agent' :

				// Get window.navigator operating system
				if(typeof(window.navigator) === 'undefined') { break; }
				return (typeof(window.navigator.userAgent) !== 'undefined') ? window.navigator.userAgent : '';
		}

		return false;
	}

	// Form geo location - Process
	$.WS_Form.prototype.form_geo_location_process = function(tracking_geo_location) {

		// Add to form
		this.form_add_hidden_input('wsf_geo_location', tracking_geo_location);
	}

	// Form geo location - Process
	$.WS_Form.prototype.form_geo_location_get_error = function(error) {

		switch(error.code) {

			case error.PERMISSION_DENIED:

				return this.language('debug_tracking_geo_location_permission_denied');

			case error.POSITION_UNAVAILABLE:

				return this.language('debug_tracking_geo_location_position_unavailable');

			case error.TIMEOUT:

				return this.language('debug_tracking_geo_location_timeout');

			default:

				return this.language('debug_tracking_geo_location_default');
		}
	}

})(jQuery);
