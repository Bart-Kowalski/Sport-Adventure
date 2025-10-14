(function($) {

	'use strict';

	// Google address
	$.WS_Form.prototype.form_google_address = async function() {

		var ws_this = this;

		// Wait for Google Maps JS API to load
		if(await this.form_google_maps_js_api_await('googleaddress') === false) { return false; }

		// Import libraries
		await this.form_google_maps_js_api_import_libraries('googleaddress');

		// Get Google Address field objects
		var google_address_objects = $('[data-google-address]:not([data-init-google-address])', this.form_canvas_obj);
		var google_address_objects_count = google_address_objects.length;
		if(!google_address_objects_count) { return false;}

		// Run through each autocomplete object
		google_address_objects.each(function() {

			$(this).attr('data-init-google-address', '');

			// Build google_address object
			var google_address = {};

			// Get field ID
			var field_id = ws_this.get_field_id($(this));

			// Field
			var field = ws_this.get_field($(this));

			// Field ID
			google_address.field_id = field_id;

			// ID
			google_address.id = $(this).attr('id');

			// $(this)
			google_address.obj = $(this);

			// Placeholder
			google_address.placeholder = ws_this.get_object_meta_value(field, 'placeholder', '');

			// Field mapping
			google_address.field_mapping = ws_this.get_object_meta_value(field, 'google_address_field_mapping', []);

			// Google map - Field ID
			google_address.map_field_id = ws_this.get_object_meta_value(field, 'google_address_map', '');

			// Google map - Zoom
			google_address.map_zoom = parseInt(ws_this.get_object_meta_value(field, 'google_address_map_zoom', '14'), 10);

			// Google map - Geocode on click
			google_address.map_geolocate_on_click = ws_this.get_object_meta_value(field, 'google_address_map_geolocate_on_click', '');

			// Google map - Geocode set position
			google_address.map_geocode_location_snap = ws_this.get_object_meta_value(field, 'google_address_map_geocode_location_snap', 'on');

			// Geolocate method
			google_address.auto_complete = ws_this.get_object_meta_value(field, 'google_address_auto_complete', '');

			// Geolocate browser config
			google_address.auto_complete_browser_timeout = parseInt(ws_this.get_object_meta_value(field, 'google_address_auto_complete_browser_timeout', '5000'));
			if(isNaN(google_address.auto_complete_browser_timeout)) { google_address.auto_complete_browser_timeout = 5000; }
			google_address.auto_complete_browser_high_accuracy = (ws_this.get_object_meta_value(field, 'google_address_auto_complete_browser_high_accuracy', '') == 'on');

			// Geolocate on load
			google_address.auto_complete_on_load = ws_this.get_object_meta_value(field, 'google_address_auto_complete_on_load', '');

			// Place ID validation
			google_address.place_id_validation = ws_this.get_object_meta_value(field, 'google_address_place_id_validation', 'on');
			var place_id_invalid_message = ws_this.get_object_meta_value(field, 'google_address_place_id_invalid_message', ws_this.language('google_address_place_id_invalid_message'));
			if(place_id_invalid_message == '') { place_id_invalid_message = ws_this.language('google_address_place_id_invalid_message'); }
			google_address.place_id_invalid_message = place_id_invalid_message;

			// Country restrictions
			var restriction_countries = ws_this.get_object_meta_value(field, 'google_address_restriction_country', []);

			google_address.restriction_country = [];

			if(
				(typeof(restriction_countries) === 'object') &&
				restriction_countries.length
			) {

				for(var restriction_country_index in restriction_countries) {

					if(!restriction_countries.hasOwnProperty(restriction_country_index)) { continue; }

					var restriction_country = restriction_countries[restriction_country_index];

					if(restriction_country.country_alpha_2) {

						google_address.restriction_country.push(restriction_country.country_alpha_2);
					}
				}
			}

			// Business restriction
			google_address.restriction_business = ws_this.get_object_meta_value(field, 'google_address_restriction_business', '');

			// Process
			switch($.WS_Form.settings_plugin.google_maps_js_api_version) {

				case '2' :

					ws_this.google_address_process(google_address);
					break;

				default :

					ws_this.google_address_process_legacy(google_address);
			}
		});
	}

	// Google address - Process - Places API (New)
	$.WS_Form.prototype.google_address_process = function(google_address, total_ms_start) {

		var ws_this = this;

		// Build args
		var args = {};

		// Country restriction
		if(
			(typeof(google_address.restriction_country) === 'object') &&
			google_address.restriction_country.length
		) {

			args.includedRegionCodes = google_address.restriction_country;
		}

		// Result type
		switch(google_address.restriction_business) {

			case 'on' : // Legacy
			case 'establishment' :

				args.includedPrimaryTypes = ['establishment'];
				break;

			case 'address' :
			case '(cities)' :
			case '(regions)' :

				args.includedPrimaryTypes = [google_address.restriction_business];
				break;
		}

		// Initiate Place Autocomplete element
		const pac = new google.maps.places.PlaceAutocompleteElement(args);

		// Attempt to customize input
		var pac_input = pac.Fg;
		if(pac_input) {

			// Placeholder
			var placeholder = google_address.obj.attr('placeholder') ? google_address.obj.attr('placeholder') : '';
			if(placeholder == '') { placeholder = ws_this.language('google_address_placeholder'); }
			if(typeof(pac_input.placeholder) == 'string') {

				pac_input.placeholder = placeholder;
			}

			// Style shadow root
			const style = document.createElement('style');
			style.textContent = `.widget-container .input-container input {

	// Base
	-webkit-appearance: none !important;
	color: var(--wsf-field-color) !important;
	font-family: var(--wsf-field-font-family) !important;
	font-size: var(--wsf-field-font-size) !important;
	font-style: var(--wsf-field-font-style) !important;
	font-weight: var(--wsf-field-font-weight) !important;
	letter-spacing: var(--wsf-field-letter-spacing) !important;
	line-height: var(--wsf-field-line-height) !important;
	margin: 0 !important;
	padding: 0 !important;
	text-decoration: var(--wsf-field-text-decoration) !important;
	text-size-adjust: 100% !important;
	text-transform: var(--wsf-field-text-transform) !important;
}

.widget-container .input-container input::placeholder {
	color: var(--wsf-field-color-placeholder) !important;
}

.widget-container .autocomplete-icon,
.widget-container .back-button,
.widget-container .focus-ring,
.widget-container .clear-button {
	display: none !important;
}`;

			pac_input.parentNode.insertBefore(style, pac_input.nextSibling);
		}

		// Place Autocomplete ID
		google_address.id_pac = google_address.id + '-pac';

		// Place Autocomplete obj
		google_address.obj_pac_wrapper = $('#' + google_address.id_pac, ws_this.form_canvas_obj);

		// Add to form
		google_address.obj_pac_wrapper.append(pac);

		// Store Place Autocomplete object
		google_address.obj_pac = $(pac);

		// Sync hidden input field with Google Place Autocomplete input field
		google_address.obj.on('input change', function(e) {

			if(
				(typeof(google_address.obj_pac[0].Fg) == 'object') &&
				(typeof(google_address.obj_pac[0].Fg.value) == 'string')
			) {
				var value = $(this).val();

				var trigger = (value != google_address.obj_pac[0].Fg.value);

				google_address.obj_pac[0].Fg.value = value;

				if(trigger) {

					google_address.obj_pac[0].Fg.dispatchEvent(new Event(e.type, { bubbles: true }));
				}
			}
		});
		google_address.obj_pac.on('input change', function(e) {

			if(
				(typeof(google_address.obj_pac[0].Fg) == 'object') &&
				(typeof(google_address.obj_pac[0].Fg.value) == 'string')
			) {
				var value = google_address.obj_pac[0].Fg.value;

				var trigger = (value != google_address.obj.val());

				google_address.obj.val(value);

				if(trigger) {

					google_address.obj.trigger('change');
				}
			}
		});

		// gmp-select event listener
		google_address.obj_pac.on('gmp-select', async function(e) {

			var place = e.originalEvent.placePrediction.toPlace();

			await place.fetchFields({

				fields: [

					'addressComponents',
					'businessStatus',
					'formattedAddress',
					'nationalPhoneNumber',
					'internationalPhoneNumber',
					'location',
					'displayName',
					'id',
					'plusCode',
					'rating',
					'userRatingCount',
					'googleMapsURI',
					'websiteURI'
				]
			});

			// Convert to legacy format
			place = ws_this.place_legacy(place);

			// Process field mapping
			ws_this.google_address_place_set(google_address, place, false);
		});

		// Events
		this.google_address_events(google_address);
	}

	// Google address - Process - Places API (Legacy)
	$.WS_Form.prototype.google_address_process_legacy = function(google_address, total_ms_start) {

		var ws_this = this;

		// Arguments
		var args = {

			fields: [

				'address_components',
				'business_status',
				'formatted_address',
				'formatted_phone_number',
				'geometry',
				'international_phone_number',
				'name',
				'place_id',
				'plus_code',
				'rating',
				'url',
				'user_ratings_total',
				'vicinity',
				'website'
			]
		};

		// Result type
		switch(google_address.restriction_business) {

			case 'on' : // Legacy
			case 'establishment' :

				args.types = ['establishment'];
				break;

			case 'address' :
			case '(cities)' :
			case '(regions)' :

				args.types = [google_address.restriction_business];
				break;
		}

		// Country restriction
		if(
			(typeof(google_address.restriction_country) === 'object') &&
			google_address.restriction_country.length
		) {

			args.componentRestrictions = { country: google_address.restriction_country };
		}

		// Build autocomplete object
		const autocomplete = new google.maps.places.Autocomplete(google_address.obj[0], args);

		// Place changed listener
		autocomplete.addListener('place_changed', function() {

			// Get place
			var place = this.getPlace();

			// Set place
			ws_this.google_address_place_set(google_address, place, false);
		});

		// Events
		this.google_address_events(google_address);
	}

	// Google address - Events
	$.WS_Form.prototype.google_address_events = function(google_address) {

		var ws_this = this;

		// Auto complete
		if(google_address.auto_complete_on_load) {

			this.google_address_auto_complete(google_address);
		}

		// Validation
		if(google_address.place_id_validation) {

			google_address.obj.on('input', function() {

				ws_this.google_address_validate(google_address);
			});
		}

		// Auto complete event
		google_address.obj.on('wsf-auto-complete', function() {

			ws_this.google_address_auto_complete(google_address);
		});

		// Google map click
		$(document).on('wsf-google-map-click wsf-google-map-dragend', function(e, google_map, position) {

			// Check if correct map
			if(ws_this.google_address_map_field_match(google_address, google_map)) {

				ws_this.google_address_geocode(google_address, position.lat(), position.lng(), true);
			}
		});

		// Google map zoom changed
		$(document).on('wsf-google-map-zoom-changed', function(e, google_map, zoom) {

			// Check if correct map
			if(ws_this.google_address_map_field_match(google_address, google_map)) {

				google_address.map_zoom = zoom;
			}
		});
	}

	// Google address - Map field match
	$.WS_Form.prototype.google_address_map_field_match = function(google_address, google_map) {

		return (

			// If map field ID matches selected map in address field
			(google_map.field_id == google_address.map_field_id) &&

			// And they are in the same repeatable section
			(this.get_section_repeatable_suffix(google_map.obj) == this.get_section_repeatable_suffix(google_address.obj))
		);
	}

	// Google address - Normalize new place to legacy palce
	$.WS_Form.prototype.place_legacy = function(place) {
		var legacy = {};

		if(place.addressComponents) {
			legacy.address_components = place.addressComponents.map(function(c) {
				return {
					long_name: c.longText || '',
					short_name: c.shortText || '',
					types: c.types || []
				};
			});
		}

		if(place.location) {
			legacy.geometry = {
				location: {
					lat: function(){ return place.location.lat(); },
					lng: function(){ return place.location.lng(); }
				},
				viewport: place.viewport || null
			};
		}

		legacy.place_id = place.id || place.placeId || '';
		legacy.formatted_address = place.formattedAddress || '';
		legacy.formatted_phone_number = place.formattedPhoneNumber || '';
		legacy.international_phone_number = place.internationalPhoneNumber || '';
		legacy.name = (place.displayName && place.displayName.text) ? place.displayName.text : (place.displayName || '');
		legacy.plus_code = place.plusCode || null;
		legacy.rating = place.rating || '';
		legacy.url = place.googleMapsUri || '';
		legacy.user_ratings_total = place.userRatingCount || '';
		legacy.vicinity = place.vicinity || '';
		legacy.website = place.websiteUri || '';
		legacy.business_status = place.businessStatus || '';

		return legacy;
	}

	// Google address - Validate
	$.WS_Form.prototype.google_address_validate = function(google_address) {

		var google_address_obj = google_address.obj;

		// If Google Address value changed from valid place ID, reset place ID
		if(
			(typeof(google_address_obj.attr('data-place-value-old')) !== 'undefined') &&
			(google_address_obj.val() !== google_address_obj.attr('data-place-value-old')) &&
			google_address_obj.attr('data-place-id') &&
			!google_address_obj.attr('data-place-id-old')
		) {
			google_address_obj.attr('data-place-id-old', google_address_obj.attr('data-place-id'));
			google_address_obj.removeAttr('data-place-id').trigger('wsf-place-id-reset');
		}

		// If Google Address value changed matched valid place ID, set place ID
		if(
			(typeof(google_address_obj.attr('data-place-value-old')) !== 'undefined') &&
			(google_address_obj.val() === google_address_obj.attr('data-place-value-old')) &&
			!google_address_obj.attr('data-place-id') &&
			google_address_obj.attr('data-place-id-old')
		) {
			google_address_obj.attr('data-place-id', google_address_obj.attr('data-place-id-old'));
			google_address_obj.removeAttr('data-place-id-old').trigger('wsf-place-id-set');
		}

		// Validation
		if(google_address_obj.attr('data-place-id')) {

			// Reset invalid feedback
			this.set_invalid_feedback(google_address_obj, '');

		} else {

			// If a value is present in the Google Address field, set invalid feedback
			if(google_address_obj.val() !== '') {

				// Set invalid feedback
				this.set_invalid_feedback(google_address_obj, google_address.place_id_invalid_message);

			} else {

				// Reset invalid feedback
				this.set_invalid_feedback(google_address_obj, '');
			}
		}
	}

	$.WS_Form.prototype.google_address_place_set = function(google_address, place, map_click) {

		var ws_this = this;

		// Address components
		var components = [];
		components['street_full_short'] = '';
		components['street_full_long'] = '';
		components['street_full_short_rev'] = '';
		components['street_full_long_rev'] = '';
		components['postal_code_full_short'] = '';
		components['postal_code_full_long'] = '';

		// Get section repeatable index
		var section_repeatable_suffix = ws_this.get_section_repeatable_suffix(google_address.obj);

		for(var address_component_index in place.address_components) {

			if(!place.address_components.hasOwnProperty(address_component_index)) { continue; }

			var component = place.address_components[address_component_index];

			for(var component_type_index in component.types) {

				if(!component.types.hasOwnProperty(component_type_index)) { continue; }

				var component_type = component.types[component_type_index];

				switch (component_type) {

					case 'street_number' :

						components['street_number_short'] = component.short_name;
						components['street_number_long'] = component.long_name;
						components['street_full_short'] = component.short_name + ' ' + components['street_full_short'];
						components['street_full_long'] = component.long_name + ' ' + components['street_full_long'];
						components['street_full_short_rev'] += component.short_name;
						components['street_full_long_rev'] += component.long_name;
						break;

					case 'route' :

						components['route_short'] = component.short_name;
						components['route_long'] = component.long_name;
						components['street_full_short'] += component.short_name;
						components['street_full_long'] += component.long_name;
						components['street_full_short_rev'] = component.short_name + ' ' + components['street_full_short_rev'];
						components['street_full_long_rev'] = component.long_name + ' '  + components['street_full_long_rev'];
						break;

					case 'locality' :
					case 'postal_town' :

						components['locality_short'] = component.short_name;
						components['locality_long'] = component.long_name;
						break;

					case 'sublocality' :

						components['sublocality_short'] = component.short_name;
						components['sublocality_long'] = component.long_name;
						break;

					case 'subpremise' :

						components['subpremise_short'] = component.short_name;
						components['subpremise_long'] = component.long_name;
						break;

					case 'neighborhood' :

						components['neighborhood_short'] = component.short_name;
						components['neighborhood_long'] = component.long_name;
						break;

					case 'administrative_area_level_1' :
					
						components['aal1_short'] = component.short_name;
						components['aal1_long'] = component.long_name;
						break;

					case 'administrative_area_level_2' :
					
						components['aal2_short'] = component.short_name;
						components['aal2_long'] = component.long_name;
						break;

					case 'postal_code' :

						components['postal_code_short'] = component.short_name;
						components['postal_code_long'] = component.long_name;
						components['postal_code_full_short'] = component.short_name + components['postal_code_full_short'];
						components['postal_code_full_long'] = component.long_name + components['postal_code_full_long'];
						break;

					case 'postal_code_suffix' :

						components['postal_code_suffix_short'] = component.short_name;
						components['postal_code_suffix_long'] = component.long_name;
						components['postal_code_full_short'] = components['postal_code_full_short'] + '-' + component.short_name;
						components['postal_code_full_long'] = components['postal_code_full_long'] + '-' + component.long_name;
						break;

					case 'country' :

						components['country_short'] = component.short_name;
						components['country_long'] = component.long_name;
						break;
				}
			}
		}

		// Geometry
		if(
			place.geometry &&
			place.geometry.location
		) {
			var location = place.geometry.location;
			components['lat'] = location.lat();
			components['lng'] = location.lng();
			components['lat_lng'] = location.lat() + ',' + location.lng();
		}

		// Plus code
		if(
			place.plus_code &&
			place.plus_code.compound_code &&
			place.plus_code.global_code
		) {
			components['plus_code_compound_code'] = place.plus_code.compound_code;
			components['plus_code_global_code'] = place.plus_code.global_code;
		}

		// String components
		var components_string = [

			'business_status',
			'formatted_address',
			'formatted_phone_number',
			'international_phone_number',
			'name',
			'place_id',
			'rating',
			'url',
			'user_ratings_total',
			'vicinity',
			'website'
		];

		// Clear place ID
		google_address.obj.removeAttr('data-place-id');

		for(var components_string_index in components_string) {

			if(!components_string.hasOwnProperty(components_string_index)) { continue; }

			var component_string = components_string[components_string_index];

			components[component_string] = place[component_string] ? place[component_string] : '';

			// Set place ID
			if(
				(component_string === 'place_id') &&
				components[component_string]
			) {
				// Set place ID
				google_address.obj.attr('data-place-id', components[component_string]).removeAttr('data-place-id-old').trigger('wsf-place-id-set');

				// Place ID validation
				google_address.obj.attr('data-place-value-old', google_address.obj.val());
			}
		}

		// Field mapping
		if(
			(typeof(google_address.field_mapping) === 'object') &&
			google_address.field_mapping.length
		) {

			for(var field_mapping_index in google_address.field_mapping) {

				if(!google_address.field_mapping.hasOwnProperty(field_mapping_index)) { continue; }

				// Get field mapping
				var field_mapping = google_address.field_mapping[field_mapping_index];

				// Get component
				var google_address_component = (typeof(field_mapping.google_address_component) !== 'undefined') ? field_mapping.google_address_component : '';
				if(google_address_component == '') { continue; }
				if(typeof(components[google_address_component]) === 'undefined') { components[google_address_component] = ''; }

				// Get field ID
				var field_id = parseInt((typeof(field_mapping.ws_form_field) !== 'undefined') ? field_mapping.ws_form_field : '', 10);
				if(field_id == 0) { continue; }

				// Field wrapper object
				var obj_wrapper = $('#' + ws_this.form_id_prefix + 'field-wrapper-' + field_id + section_repeatable_suffix, ws_this.form_canvas_obj);

				// Field object
				var obj = $('#' + ws_this.form_id_prefix + 'field-' + field_id + section_repeatable_suffix, ws_this.form_canvas_obj);

				// Get value
				var value = components[google_address_component];

				// Set field value
				ws_this.field_value_set(obj_wrapper, obj, value);

				// Place ID validation
				if(obj[0] === google_address.obj[0]) {

					google_address.obj.attr('data-place-value-old', value);
				}
			}
		}

		// Set Google Map
		if(
			(!map_click || google_address.map_geocode_location_snap) &&
			(google_address.map_field_id != '') &&
			(typeof(ws_this.google_maps[google_address.map_field_id + section_repeatable_suffix]) !== 'undefined') &&
			place.geometry &&
			place.geometry.location
		) {

			// Get Google Map
			var google_map = ws_this.google_maps[google_address.map_field_id + section_repeatable_suffix];

			// Set position
			google_map.marker_set_position(place.geometry.location, place);

			// Set viewport
			if(place.geometry.viewport) {

				google_map.map.fitBounds(place.geometry.viewport);
			}

			// Get Google Map - Zoom
			if(google_address.map_zoom > 0) {

				google_map.map.setZoom(google_address.map_zoom);
			}
		}

		// Validate
		if(google_address.place_id_validation) {

			this.google_address_validate(google_address);
		}
	}

	$.WS_Form.prototype.google_address_auto_complete = function(google_address) {

		var ws_this = this;

		switch(google_address.auto_complete) {

			case 'browser' :

				// Does browser support geolocation?
				if(!navigator.geolocation) { break; }

				// Show loader
				if(typeof(this.form_loader_show) === 'function') { this.form_loader_show('geolocate'); }

				// Get geo location (async)
				navigator.geolocation.getCurrentPosition(

					// Success
					function(position) {

						// Set position
						ws_this.google_address_geocode(google_address, parseFloat(position.coords.latitude), parseFloat(position.coords.longitude), false);

						// Hide loader
						if(typeof(ws_this.form_loader_hide) === 'function') { ws_this.form_loader_hide(); }
					},

					// Error
					function(error) {

						if(error.message) {

							ws_this.error('error_geocoder_google_address_error', error.message, 'google-maps-js-api');
						}				

						// Hide loader
						if(typeof(ws_this.form_loader_hide) === 'function') { ws_this.form_loader_hide(); }
					},

					{
						enableHighAccuracy: google_address.auto_complete_browser_high_accuracy,
						maximumAge: 0,
						timeout: google_address.auto_complete_browser_timeout
					}
				);

				break;

			case 'ip' :

				// Show loader
				if(typeof(this.form_loader_show) === 'function') { this.form_loader_show('geolocate'); }

				// Add to geo stack
				ws_this.form_geo_get_element('lat_lng', '', 'google_address_auto_complete_ip', {google_address: google_address});

				break;
		}
	}

	$.WS_Form.prototype.google_address_auto_complete_ip = function(callback_value, callback_data) {

		// Hide loader
		if(typeof(this.form_loader_hide) === 'function') { this.form_loader_hide(); }

		// Check callback value
		if(
			!callback_value ||
			(typeof(callback_value) !== 'string')
		) {
			return false;
		}

		// Split callback value
		var lat_lng = callback_value.split(',');
		if(lat_lng.length !== 2) { return false; }

		// Geocode
		this.google_address_geocode(callback_data.google_address, parseFloat(lat_lng[0]), parseFloat(lat_lng[1]), false);
	}

	$.WS_Form.prototype.google_address_geocode = function(google_address, lat, lng, map_click) {

		var ws_this = this;

		// Get place by latitude and longitude
		const geocoder = new google.maps.Geocoder();

		// Convert lat lng
		var lat = parseFloat(lat);
		var lng = parseFloat(lng);

		// Check lat lng
		if(
			isNaN(lat) ||
			isNaN(lng)
		) {
			return false;
		}

		const lat_lng = {

			lat: lat,
			lng: lng
		};

		geocoder.geocode({ location: lat_lng }).then((response) => {

			if(response.results[0]) {

				// Get place
				var place = response.results[0];

				// Log
				ws_this.log('log_google_geocode_success', place.place_id, 'google-maps-js-api');

				// Set place
				ws_this.google_address_place_set(google_address, place, map_click);

			} else {

				// No results
				ws_this.error('error_geocoder_google_address_no_results', 'google-maps-js-api');
			}

		}).catch((e) => {

			// Error during geocode
			ws_this.error('error_geocoder_google_address_error', e);
		});
	}

})(jQuery);
