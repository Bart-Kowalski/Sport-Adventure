<?php

	/**
	 * @link              https://wsform.com/knowledgebase/klaviyo/
	 * @since             1.0.0
	 * @package           WS_Form_Klaviyo
	 *
	 * @wordpress-plugin
	 * Plugin Name:       WS Form PRO - Klaviyo
	 * Plugin URI:        https://wsform.com/knowledgebase/klaviyo/
	 * Description:       Klaviyo add-on for WS Form PRO
	 * Version:           2.0.5
	 * Requires at least: 5.2
	 * Requires PHP:      5.6
	 * License:           GPLv3 or later
	 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 	 * Author:            WS Form
  	 * Author URI:        https://wsform.com/
	 * Text Domain:       ws-form-klaviyo-v3
	 */

	Class WS_Form_Add_On_Klaviyo_V3 {

		const WS_FORM_PRO_ID 			= 'ws-form-pro/ws-form.php';
		const WS_FORM_PRO_VERSION_MIN 	= '1.9.198';

		function __construct() {

			// Load plugin.php
			if(!function_exists('is_plugin_active')) {

				include_once(ABSPATH . 'wp-admin/includes/plugin.php');
			}

			// Admin init
			add_action('plugins_loaded', array($this, 'plugins_loaded'), 20);
		}

		function plugins_loaded() {

			if(self::is_dependency_ok()) {

				new WS_Form_Action_Klaviyo_V3();

			} else {

				self::dependency_error();

				if(isset($_GET['activate'])) { unset($_GET['activate']); }
			}
		}

		function activate() {

			if (!self::is_dependency_ok()) {

				self::dependency_error();
			}
		}

		// Check dependencies
		function is_dependency_ok() {

			if(!defined('WS_FORM_VERSION')) { return false; }

			return(

				is_plugin_active(self::WS_FORM_PRO_ID) &&
				(version_compare(WS_FORM_VERSION, self::WS_FORM_PRO_VERSION_MIN) >= 0)
			);
		}

		// Add error notice action - Pro
		function dependency_error() {

			// Show error notification
			add_action('after_plugin_row_' . plugin_basename(__FILE__), array($this, 'dependency_error_notification'), 10, 2);
		}

		// Dependency error - Notification
		function dependency_error_notification($file, $plugin) {

			// Checks
			if(!current_user_can('update_plugins')) { return; }
			if($file != plugin_basename(__FILE__)) { return; }

			// Build notice
			$dependency_notice = sprintf('<tr class="plugin-update-tr"><td colspan="3" class="plugin-update colspanchange"><div class="update-message notice inline notice-error notice-alt"><p>%s</p></div></td></tr>', sprintf(__('This add-on requires %s (version %s or later) to be installed and activated.', 'ws-form-klaviyo-v3'), '<a href="https://wsform.com?utm_source=ws_form_pro&utm_medium=plugins" target="_blank">WS Form PRO</a>', self::WS_FORM_PRO_VERSION_MIN));

			// Show notice
			echo $dependency_notice;
		}
	}

	$wsf_add_on_klaviyo = new WS_Form_Add_On_Klaviyo_V3();	

	register_activation_hook(__FILE__, array($wsf_add_on_klaviyo, 'activate'));

	// This gets fired by WS Form when it is ready to register add-ons
	add_action('wsf_plugins_loaded', function() {

		class WS_Form_Action_Klaviyo_V3 extends WS_Form_Action {

			public $id = 'klaviyov2';
			public $pro_required = true;
			public $label;
			public $label_action;
			public $events;
			public $multiple = true;
			public $configured = false;
			public $priority = 50;
			public $can_repost = true;
			public $form_add = false;

			// Licensing
			private $licensing;

			// Config
			private $api_endpoint = false;
			private $api_key_private;
			public $list_id = false;
			public $source = '#form_label';
			public $opt_in_field;
			public $sms_consent_field;
			public $field_mapping;
			public $custom_mapping = false;

			// Constants
			const EMAIL_MERGE_FIELD = 'email';
			const PHONE_MERGE_FIELD = 'phone_number';
			const RECORDS_PER_AGE = 10;
			const WS_FORM_LICENSE_ITEM_ID = 5352;
			const WS_FORM_LICENSE_NAME = 'Klaviyo add-on for WS Form PRO';
			const WS_FORM_LICENSE_VERSION = '2.0.5';
			const WS_FORM_LICENSE_AUTHOR = 'WS Form';
			const API_REVISION = '2025-01-15';

			public function __construct() {

				// Events
				$this->events = array('submit');

				// Register config filters
				add_filter('wsf_config_options', array($this, 'config_options'), 10, 1);
				add_filter('wsf_config_meta_keys', array($this, 'config_meta_keys'), 10, 2);
				add_filter('plugin_action_links_' . plugin_basename(__FILE__), array($this, 'plugin_action_links'), 10, 1);
				add_filter('rest_api_init', array($this, 'rest_api_init'), 10, 2);

				// Add nag action
				add_action('wsf_nag', array($this, 'nag'));

				// Licensing
				$this->licensing = new WS_Form_Licensing(

					self::WS_FORM_LICENSE_ITEM_ID,
					$this->id,
					self::WS_FORM_LICENSE_NAME,
					self::WS_FORM_LICENSE_VERSION,
					self::WS_FORM_LICENSE_AUTHOR,
					__FILE__
				);
				$this->licensing->transient_check();
				add_action('admin_init', array($this->licensing, 'updater'));
				add_filter('wsf_settings_static', array($this, 'settings_static'), 10, 2);
				add_filter('wsf_settings_button', array($this, 'settings_button'), 10, 3);
				add_filter('wsf_settings_update_fields', array($this, 'settings_update_fields'), 10, 2);

				// Load plugin level configuration
				self::load_config_plugin();

				// Register init action
				add_action('init', array($this, 'init'));
			}

			public function init() {

				// Set label
				$this->label = __('Klaviyo', 'ws-form-klaviyo-v3');

				// Set label for actions pull down
				$this->label_action = __('Add to Klaviyo', 'ws-form-klaviyo-v3');

				// Register action
				parent::register($this);
			}

			// Get license item ID
			public function get_license_item_id() {

				return self::WS_FORM_LICENSE_ITEM_ID;
			}

			// Plugin action link
			public function plugin_action_links($links) {

				// Settings
				array_unshift($links, sprintf('<a href="%s">%s</a>', WS_Form_Common::get_admin_url('ws-form-settings', false, 'tab=action_' . $this->id), __('Settings', 'ws-form-klaviyo-v3')));

				return $links;
			}

			// Settings - Static
			public function settings_static($value, $field) {

				switch ($field) {

					case 'action_' . $this->id . '_license_version' :

						$value = self::WS_FORM_LICENSE_VERSION;
						break;

					case 'action_' . $this->id . '_license_status' :

						$value = $this->licensing->license_status();
						break;
				}

				return $value;
			}

			// Settings - Button
			public function settings_button($value, $field, $button) {

				switch($button) {

					case 'license_action_' . $this->id :

						$license_activated = WS_Form_Common::option_get('action_' . $this->id . '_license_activated', false);
						if($license_activated) {

							$value = '<input class="wsf-button" type="button" data-action="wsf-mode-submit" data-mode="deactivate" value="' . __('Deactivate', 'ws-form-klaviyo-v3') . '" />';

						} else {

							$value = '<input class="wsf-button" type="button" data-action="wsf-mode-submit" data-mode="activate" value="' . __('Activate', 'ws-form-klaviyo-v3') . '" />';
						}

						break;
				}
				
				return $value;
			}

			// Settings - Update fields
			public function settings_update_fields($field, $value) {

				switch ($field) {

					case 'action_' . $this->id . '_license_key' :

						$mode = WS_Form_Common::get_query_var('action_mode');

						switch($mode) {

							case 'activate' :

								$this->licensing->activate($value);
								break;

							case 'deactivate' :

								$this->licensing->deactivate($value);
								break;
						}

					break;
				}
			}

			// Nag
			public function nag() {

				// Load plugin level configuration
				self::load_config_plugin();

				if(!$this->configured) {

					WS_Form_Common::admin_message_push(sprintf(__('To complete the %s setup for %s, please enter your API key <a href="%s">here</a>.', 'ws-form-klaviyo-v3'), $this->label, WS_FORM_NAME_PRESENTABLE, WS_Form_Common::get_admin_url('ws-form-settings', -1, 'tab=action_' . $this->id)), 'notice-warning', false);
				}
			}

			// Post to API
			public function post($form, &$submit, $config) {

				// Check action is configured properly
				if(!self::check_configured()) { return false; }

				// Load configuration
				self::load_config($config);

				// Data
				$data_profile = array(

					'data' => array(

						'type' => 'profile',
						'attributes' => array()
					)
				);

				$data_profile_to_list = array(

					'data' => array(

						array(

							'type' => 'profile'
						)
					)
				);

				$data_profile_subscription = array(

					'data' => array(

						'type' => 'profile-subscription-bulk-create-job',
						'attributes' => array(

							'profiles' => array(

								'data' => array(

									array(

										'type' => 'profile',
										'attributes' => array(
										)
									)
								)
							)
						)
					)
				);

				$consent_required = false;
				$consent_count = 0;

				// Get opt in value (False if field not submitted)
				$opt_in_field_value = parent::get_submit_value($submit, WS_FORM_FIELD_PREFIX . $this->opt_in_field, false);

				if(($this->opt_in_field !== false) && ($this->opt_in_field !== '') && ($opt_in_field_value !== false)) {

					$consent_required = true;

					if(!empty($opt_in_field_value)) {

						$data_profile_subscription['data']['attributes']['profiles']['data'][0]['attributes']['subscriptions']['email'] = array(
							'marketing' => array(
								'consent' => 'SUBSCRIBED'
							)
						);

						$consent_count++;
					}
				}

				// Get SMS consent value (False if field not submitted)
				$sms_consent_field_value = parent::get_submit_value($submit, WS_FORM_FIELD_PREFIX . $this->sms_consent_field, false);

				if(($this->sms_consent_field !== false) && ($this->sms_consent_field !== '') && ($sms_consent_field_value !== false)) {

					$consent_required = true;

					if(!empty($sms_consent_field_value)) {

						$data_profile_subscription['data']['attributes']['profiles']['data'][0]['attributes']['subscriptions']['sms'] = array(
							'marketing' => array(
								'consent' => 'SUBSCRIBED'
							)
						);

						$consent_count++;
					}
				}

				// End user did not provide consent, exit gracefully
				if($consent_required && ($consent_count === 0)) {

					self::success(__('User did not provide consent, no data pushed to action', 'ws-form-klaviyo-v3'));
					return true;
				}

				// Add source
				$data_profile_subscription['data']['attributes']['custom_source'] = WS_Form_Common::parse_variables_process($this->source, $form, $submit, 'text/plain');

				// Check list ID is configured properly
				if(!self::check_list_id()) { return false; }

				// Process field mapping
				$email = false;
				$phone_number = false;

				foreach($this->field_mapping as $field_map) {

					$field_id = $field_map['ws_form_field'];

					$api_field = $field_map['action_' . $this->id . '_list_fields'];

					// Legacy support
					$api_field = str_replace('$', '', $api_field);

					// Get submit value
					$submit_value = parent::get_submit_value($submit, WS_FORM_FIELD_PREFIX . $field_id, false);

					if($submit_value === false) { continue; }

					// Klaviyo cannot accept arrays
					if(is_array($submit_value)) { $submit_value = implode(',', $submit_value); }

					switch($api_field) {

						// Email
						case self::EMAIL_MERGE_FIELD :

							// Save email address
							$email = $submit_value;
							$data_profile['data']['attributes'][$api_field] = $submit_value;
							break;

						// Phone
						case self::PHONE_MERGE_FIELD :

							// Save phone address
							$phone_number = $submit_value;
							$data_profile['data']['attributes'][$api_field] = $submit_value;
							break;

						// Location
						case 'address1' :
						case 'address2' :
						case 'city' :
						case 'country' :
						case 'region' :
						case 'zip' :
						case 'timezone' :

							if(!isset($data_profile['data']['attributes']['location'])) {

								$data_profile['data']['attributes']['location'] = array();
							}

							$data_profile['data']['attributes']['location'][$api_field] = $submit_value;
							break;

						default : 

							$data_profile['data']['attributes'][$api_field] = $submit_value;
					}
				}

				// Properties
				foreach($this->custom_mapping as $custom_map) {

					// Get key
					$custom_key = $custom_map['action_' . $this->id . '_custom_mapping_key'];
					$custom_key = WS_Form_Common::parse_variables_process($custom_key, $form, $submit, 'text/plain');
					if($custom_key == '') { continue; }

					// Get value
					$custom_value = $custom_map['action_' . $this->id . '_custom_mapping_value'];
					$custom_value = WS_Form_Common::parse_variables_process($custom_value, $form, $submit, 'text/plain');

					// Map key
					if(!isset($data_profile['data']['attributes']['properties'])) {

						$data_profile['data']['attributes']['properties'] = array();
					}

					$data_profile['data']['attributes']['properties'][$custom_key] = $custom_value;
				}

				// Check email address is configured
				if(
					($email === false) &&
					($phone_number === false)

				) { return self::error(__('Email address or phone number not mapped', 'ws-form-klaviyo-v3')); }

				// Check user email address is valid
				if(($email !== false) && !filter_var($email, FILTER_VALIDATE_EMAIL)) { return self::error(sprintf(__('Invalid user email address: %s', 'ws-form-klaviyo-v3'), $email)); }

				// Process email
				if($email !== false) {

					$data_profile_subscription['data']['attributes']['profiles']['data'][0]['attributes']['email'] = $email;
				}

				// Process phone number
				if($phone_number !== false) {

					$data_profile_subscription['data']['attributes']['profiles']['data'][0]['attributes']['phone_number'] = $phone_number;
				}

				// Create / update profile
				$api_response = parent::api_call($this->api_endpoint, 'api/profile-import/', 'POST', json_encode($data_profile), self::get_api_headers());

				// Check status code
				if(
					($api_response['http_code'] != 200) &&	// Updated
					($api_response['http_code'] != 201)		// Added
				) {
					return self::error(self::get_api_error_detail($api_response['response']));
				}

				// Process list data
				$api_response_decoded = json_decode($api_response['response']);
				if(
					is_null($api_response_decoded) ||
					!is_object($api_response_decoded) ||
					!property_exists($api_response_decoded, 'data') ||
					!is_object($api_response_decoded->data)
				) {
					return self::error(__('Invalid API response data', 'ws-form-klaviyo-v3'));
				}

				// Get profile ID
				$profile_id = $api_response_decoded->data->id;

				// Success
				self::success(sprintf(__('Successfully added profile %s (%s)', 'ws-form-klaviyo-v3'), $email, $profile_id));

				// Add profile to list
				$data_profile_to_list['data'][0]['id'] = $profile_id;

				// Subscribe profile
				$api_response = parent::api_call($this->api_endpoint, sprintf('api/lists/%s/relationships/profiles/', $this->list_id), 'POST', json_encode($data_profile_to_list), self::get_api_headers());

				// Check status code
				if($api_response['http_code'] != 204) { return self::error(self::get_api_error_detail($api_response['response'])); }

				// Success
				self::success(sprintf(__('Successfully added profile %s (%s) to list %s', 'ws-form-klaviyo-v3'), $email, $profile_id, $this->list_id));

				// Add profile ID to profile subscription
				$data_profile_subscription['data']['attributes']['profiles']['data'][0]['id'] = $profile_id;

				// Subscribe profile
				$api_response = parent::api_call($this->api_endpoint, 'api/profile-subscription-bulk-create-jobs/', 'POST', json_encode($data_profile_subscription), self::get_api_headers());

				// Check status code
				if($api_response['http_code'] != 202) { return self::error(self::get_api_error_detail($api_response['response'])); }

				// Success
				self::success(sprintf(__('Successfully subscribed profile %s (%s)', 'ws-form-klaviyo-v3'), $email, $profile_id));
			}

			// Get from API
			public function get($form, $user) {

				// Check action is configured properly
				if(!self::check_configured()) { return false; }

				// Check list ID is set
				if(!self::check_list_id()) { return false; }

				// Get field types
				$list_fields = self::get_list_fields();
				$list_field_type = array();
				foreach($list_fields as $list_field) {

					$list_field_type[$list_field['id']] = $list_field['type'];
				}

				// Get user email address
				$user_email = $user->user_email;

				// Check user email address is valid
				if(!filter_var($user_email, FILTER_VALIDATE_EMAIL)) { return self::error(__('Invalid user email address', 'ws-form-klaviyo-v3')); }

				// Check list membership
				$params = array(

					'filter' => sprintf('equals(email,"%s")', $user_email),
					'page[size]' => 1
				);

				$api_response = parent::api_call($this->api_endpoint, 'api/profiles/', 'GET', $params, self::get_api_headers());

				// Check status code
				if($api_response['http_code'] != 200) { return self::error(self::get_api_error_detail($api_response['response'])); }

				// Process list data
				$api_response_decoded = json_decode($api_response['response']);
				if(
					is_null($api_response_decoded) ||
					!is_object($api_response_decoded) ||
					!property_exists($api_response_decoded, 'data') ||
					!is_array($api_response_decoded->data)
				) {
					return self::error(__('Invalid API response data', 'ws-form-klaviyo-v3'));
				}

				// Check for results
				if(count($api_response_decoded->data) == 0) { return self::error(__('Profile data not found', 'ws-form-klaviyo-v3')); }

				// Get attributes
				if(empty($api_response_decoded->data[0]->attributes)) { return self::error(__('Profile data not found', 'ws-form-klaviyo-v3')); }

				$attributes = $api_response_decoded->data[0]->attributes;

				// Process fields
				$fields_return = array(

					'email' => parent::get_object_value($attributes, 'email', ''),
					'phone_number' => parent::get_object_value($attributes, 'phone_number', ''),
					'first_name' => parent::get_object_value($attributes, 'first_name', ''),
					'last_name' => parent::get_object_value($attributes, 'last_name', ''),
					'organization' => parent::get_object_value($attributes, 'organization', ''),
					'title' => parent::get_object_value($attributes, 'title', ''),
					'address1' => parent::get_object_value($attributes->location, 'address1', ''),
					'address2' => parent::get_object_value($attributes->location, 'address2', ''),
					'city' => parent::get_object_value($attributes->location, 'city', ''),
					'region' => parent::get_object_value($attributes->location, 'region', ''),
					'zip' => parent::get_object_value($attributes->location, 'zip', ''),
					'country' => parent::get_object_value($attributes->location, 'country', ''),
					'timezone' => parent::get_object_value($attributes->location, 'timezone', ''),
				);

				$return_array = array('fields' => $fields_return, 'tags' => false);

				return $return_array;
			}

			// Get lists
			public function get_lists($fetch = false) {

				// Check action is configured properly
				if(!self::check_configured()) { return false; }

				// Check to see if lists are cached
				$lists = WS_Form_Common::option_get('action_' . $this->id . '_lists');

				// Retried if fetch is requested or lists are not cached
				if($fetch || ($lists === false)) {

					$lists = array();

					// Load configuration
					self::load_config();

					// Next URL
					$next_url = $this->api_endpoint . 'api/lists';

					while(!empty($next_url)) {

						// Get lists
						$api_response = parent::api_call($next_url, '', 'GET', false, self::get_api_headers());

						// Check status code
						if($api_response['http_code'] != 200) { return self::error(self::get_api_error_detail($api_response['response'])); }

						// Process list data
						$api_response_decoded = json_decode($api_response['response']);
						if(
							is_null($api_response_decoded) ||
							!is_object($api_response_decoded) ||
							!property_exists($api_response_decoded, 'data') ||
							!is_array($api_response_decoded->data)
						) {
							return self::error(__('Invalid API response data', 'ws-form-klaviyo-v3'));
						}

						foreach($api_response_decoded->data as $list) {

							$lists[] = array(

								'id' => 			parent::get_object_value($list, 'id'),
								'label' => 			parent::get_object_value($list->attributes, 'name'),
								'field_count' => 	false,
								'record_count' => 	false
							);
						}

						// Get next URL
						if(
							property_exists($api_response_decoded, 'links') &&
							is_object($api_response_decoded->links) &&
							property_exists($api_response_decoded->links, 'next')
						) {

							$next_url = $api_response_decoded->links->next;

						} else {

							$next_url = false;
						}
					}

					// Store to options
					WS_Form_Common::option_set('action_' . $this->id . '_lists', $lists);
				}

				return $lists;
			}

			// Get list
			public function get_list($fetch = false) {

				// Check action is configured properly
				if(!self::check_configured()) { return false; }

				// Check list ID is set
				if(!self::check_list_id()) { return false; }

				$list = WS_Form_Common::option_get('action_' . $this->id . '_list_' . $this->list_id);

				if($fetch || ($list === false)) {

					// Load configuration
					self::load_config();

					// Get list
					$api_response = parent::api_call($this->api_endpoint, 'api/lists/' . $this->list_id, 'GET', false, self::get_api_headers());

					// Check status code
					if($api_response['http_code'] != 200) { return self::error(self::get_api_error_detail($api_response['response'])); }

					// Process list data
					$api_response_list = json_decode($api_response['response']);
					if(
						is_null($api_response_list) ||
						!is_object($api_response_list) ||
						!property_exists($api_response_list, 'data') ||
						!is_object($api_response_list->data)
					) {
						return self::error(__('Invalid API response data', 'ws-form-klaviyo-v3'));
					}

					// Build list
					$list = array(

						'label' => parent::get_object_value($api_response_list->data->attributes, 'name')
					);

					// Store to options
					WS_Form_Common::option_set('action_' . $this->id . '_list_' . $this->list_id, $list);
				}

				return $list;
			}

			// Get list fields
			public function get_list_fields($fetch = false) {

				// Check action is configured properly
				if(!self::check_configured()) { return false; }

				$list_fields = WS_Form_Common::option_get('action_' . $this->id . '_list_fields_' . $this->list_id);

				if($fetch || ($list_fields === false)) {

					$list_fields = array();

					// Load configuration
					self::load_config();

					// Initialize
					$api_list_fields = array();

					// Default Klaviyo fields
					$klaviyo_fields = array(

						array('id' => self::EMAIL_MERGE_FIELD, 'type' => 'email', 'label' => __('Email', 'ws-form-klaviyo-v3'), 'required' => true),
						array('id' => self::PHONE_MERGE_FIELD, 'type' => 'tel', 'label' => __('Phone Number', 'ws-form-klaviyo-v3')),
						array('id' => 'first_name', 'type' => 'text', 'label' => __('First Name', 'ws-form-klaviyo-v3')),
						array('id' => 'last_name', 'type' => 'text', 'label' => __('Last Name', 'ws-form-klaviyo-v3')),
						array('id' => 'address1', 'type' => 'text', 'label' => __('Address Line 1', 'ws-form-klaviyo-v3')),
						array('id' => 'address2', 'type' => 'text', 'label' => __('Address Line 2', 'ws-form-klaviyo-v3')),
						array('id' => 'organization', 'type' => 'text', 'label' => __('Organization', 'ws-form-klaviyo-v3')),
						array('id' => 'title', 'type' => 'text', 'label' => __('Title', 'ws-form-klaviyo-v3')),
						array('id' => 'city', 'type' => 'text', 'label' => __('City', 'ws-form-klaviyo-v3')),
						array('id' => 'region', 'type' => 'text', 'label' => __('Region', 'ws-form-klaviyo-v3')),
						array('id' => 'zip', 'type' => 'text', 'label' => __('Zip', 'ws-form-klaviyo-v3')),
						array('id' => 'country', 'type' => 'text', 'label' => __('Country', 'ws-form-klaviyo-v3')),
						array('id' => 'timezone', 'type' => 'text', 'label' => __('Timezone', 'ws-form-klaviyo-v3'), 'no_add' => true),
					);

					$sort_index_offset = 0;
					foreach($klaviyo_fields as $klaviyo_field) {

						// Add built in klaviyo fields not returned by API
						$list_fields[] = array(

							'id' => 			$klaviyo_field['id'],
							'label' => 			$klaviyo_field['label'], 
							'label_field' => 	$klaviyo_field['label'], 
							'type' => 			$klaviyo_field['type'],
							'action_type' =>	$klaviyo_field['type'],
							'required' => 		isset($klaviyo_field['required']) ? $klaviyo_field['required'] : false, 
							'default_value' => 	'',
							'pattern' => 		'',
							'placeholder' => 	'',
							'help' => 			'', 
							'sort_index' => 	$sort_index_offset,
							'visible' =>		true,
							'meta' => 			false,
							'no_add' =>			isset($klaviyo_field['no_add']) ? $klaviyo_field['no_add'] : false
						);

						$sort_index_offset++;
					}

					// Store to options
					WS_Form_Common::option_set('action_' . $this->id . '_list_fields_' . $this->list_id, $list_fields);
				}

				return $list_fields;
			}

			// Get form fields
			public function get_fields() {

				$form_fields = array(

					'opt_in_field' => array(

						'type'	=>	'checkbox',
						'label'	=>	__('Email Opt-In Field', 'ws-form'),
						'meta'	=>	array(

							'data_grid_checkbox' => WS_Form_Common::build_data_grid_meta('data_grid_checkbox', false, false, array(

								array(

									'id'		=> 1,
									'default'	=> '',
									'required'	=> '',
									'disabled'	=> '',
									'hidden'	=> '',
									'data'		=> array(__('I would like to opt-in and receive emails from #blog_name', 'ws-form'))
								)
							))
						)
					),

					'sms_consent_field' => array(

						'type'	=>	'checkbox',
						'label'	=>	__('SMS Opt-In Field', 'ws-form'),
						'meta'	=>	array(

							'data_grid_checkbox' => WS_Form_Common::build_data_grid_meta('data_grid_checkbox', false, false, array(

								array(

									'id'		=> 1,
									'default'	=> '',
									'required'	=> '',
									'disabled'	=> '',
									'hidden'	=> '',
									'data'		=> array(__('I would like to opt-in and receive SMS messages from #blog_name', 'ws-form'))
								)
							))
						)
					),

					'submit' => array(

						'type'			=>	'submit'
					)
				);

				return $form_fields;
			}

			// Get form actions
			public function get_actions() {

				$form_actions = array(

					$this->id => array(

						'meta'	=> array(

							'action_' . $this->id . '_list_id'				=>	$this->list_id,
							'action_' . $this->id . '_source'				=>	$this->source,
							'action_' . $this->id . '_field_mapping'		=>	'field_mapping',
							'action_' . $this->id . '_custom_mapping'		=>	'custom_mapping',
							'action_' . $this->id . '_opt_in_field'			=>	'opt_in_field',
							'action_' . $this->id . '_sms_consent_field'	=>	'sms_consent_field'
						)
					),

					'message',

					'database'
				);

				return $form_actions;
			}

			// Get API error detail
			public function get_api_error_detail($api_response) {

				$api_response_decoded = json_decode($api_response);

				if(
					is_null($api_response_decoded) ||
					!property_exists($api_response_decoded, 'errors') ||
					!is_array($api_response_decoded->errors)
				) {
					return __('Unknown API error for action: ' . $this->label, 'ws-form-klaviyo-v3');
				}

				$error_detail_array = array();

				foreach($api_response_decoded->errors as $error) {

					if(
						!property_exists($error, 'title') ||
						!property_exists($error, 'detail')
					) {
						continue;
					}

					$error_detail_array[] = sprintf('%s (%s)', $error->title, $error->detail);
				}

				return $error_detail_array;
			}

			// Get settings
			public function get_action_settings() {

				$settings = array(

					'meta_keys'		=> array(

						'action_' . $this->id . '_list_id',
						'action_' . $this->id . '_source',
						'action_' . $this->id . '_opt_in_field',
						'action_' . $this->id . '_sms_consent_field',
						'action_' . $this->id . '_field_mapping',
						'action_' . $this->id . '_custom_mapping'
					)
				);

				// Wrap settings so they will work with sidebar_html function in admin.js
				$settings = parent::get_settings_wrapper($settings);

				// Add labels
				$settings->label = $this->label;
				$settings->label_action = $this->label_action;

				// Add multiple
				$settings->multiple = $this->multiple;

				// Add events
				$settings->events = $this->events;

				// Add can_repost
				$settings->can_repost = $this->can_repost;

				// Apply filter
				$settings = apply_filters('wsf_action_' . $this->id . '_settings', $settings);

				return $settings;
			}

			// Check action is configured properly
			public function check_configured() {

				if(!$this->configured) { return self::error(__('Action not configured', 'ws-form-klaviyo-v3') . ' (' . $this->label . ''); }

				return $this->configured;
			}

			// Check list ID is set
			public function check_list_id() {

				if($this->list_id === false) { return self::error(__('List ID is not set', 'ws-form-klaviyo-v3')); }

				return ($this->list_id !== false);
			}

			// Meta keys for this action
			public function config_meta_keys($meta_keys = array(), $form_id = 0) {

				// Build config_meta_keys
				$config_meta_keys = array(

					// List ID
					'action_' . $this->id . '_list_id'	=> array(

						'label'						=>	__('Klaviyo List', 'ws-form-klaviyo-v3'),
						'type'						=>	'select',
						'help'						=>	__('Select the Klaviyo list to associate this form with.', 'ws-form-klaviyo-v3'),
						'options'						=>	'action_api_populate',
						'options_blank'					=>	__('Select...', 'ws-form-klaviyo-v3'),
						'options_action_id_meta_key'	=>	'action_id',
						'options_action_api_populate'	=>	'lists',
						'reload'					=>	array(

							'action_id'			=>	$this->id,
							'method'			=>	'lists_fetch'
						),
					),

					// Opt-In field
					'action_' . $this->id . '_opt_in_field'	=> array(

						'label'							=>	__('Opt-In Field', 'ws-form-klaviyo-v3'),
						'type'							=>	'select',
						'options'						=>	'fields',
						'options_blank'					=>	__('Select...', 'ws-form-klaviyo-v3'),
						'fields_filter_type'			=>	array('select', 'checkbox', 'radio'),
						'help'							=>	__('Checkbox recommended.', 'ws-form-klaviyo-v3')
					),

					// SMS consent field
					'action_' . $this->id . '_sms_consent_field'	=> array(

						'label'							=>	__('SMS Consent Field', 'ws-form-klaviyo-v3'),
						'type'							=>	'select',
						'options'						=>	'fields',
						'options_blank'					=>	__('Select...', 'ws-form-klaviyo-v3'),
						'fields_filter_type'			=>	array('select', 'checkbox', 'radio'),
						'help'							=>	__('Checkbox recommended.', 'ws-form-klaviyo-v3')
					),

					// Source
					'action_' . $this->id . '_source'	=> array(

						'label'						=>	__('Source', 'ws-form'),
						'type'						=>	'text',
						'default'					=>	'#form_label',
						'help'						=>	__('Form signup source value.', 'ws-form-klaviyo-v3'),
						'condition'					=>	array(

							array(

								'logic'			=>	'!=',
								'meta_key'		=>	'action_' . $this->id . '_list_id',
								'meta_value'	=>	''
							)
						),
						'select_list'				=>	true				
					),

					// Field mapping
					'action_' . $this->id . '_field_mapping'	=> array(

						'label'						=>	__('Field Mapping', 'ws-form-klaviyo-v3'),
						'type'						=>	'repeater',
						'help'						=>	__('Map WS Form fields to Klaviyo fields.', 'ws-form-klaviyo-v3'),
						'meta_keys'					=>	array(

							'ws_form_field',
							'action_' . $this->id . '_list_fields'
						),
						'meta_keys_unique'			=>	array(
							'action_' . $this->id . '_list_fields'
						),
						'reload'					=>	array(

							'action_id'			=>	$this->id,
							'method'			=>	'list_fields_fetch',
							'list_id_meta_key'	=>	'action_' . $this->id . '_list_id'
						),
						'auto_map'					=>	true,
						'condition'					=>	array(

							array(

								'logic'			=>	'!=',
								'meta_key'		=>	'action_' . $this->id . '_list_id',
								'meta_value'	=>	''
							)
						)
					),

					// List fields
					'action_' . $this->id . '_list_fields'	=> array(

						'label'							=>	__('Klaviyo Field', 'ws-form-klaviyo-v3'),
						'type'							=>	'select',
						'options'						=>	'action_api_populate',
						'options_blank'					=>	__('Select...', 'ws-form-klaviyo-v3'),
						'options_action_id'				=>	$this->id,
						'options_list_id_meta_key'		=>	'action_' . $this->id . '_list_id',
						'options_action_api_populate'	=>	'list_fields'
					),

					// Custom field mapping
					'action_' . $this->id . '_custom_mapping'	=> array(

						'label'						=>	__('Custom Properties', 'ws-form-klaviyo-v3'),
						'type'						=>	'repeater',
						'help'						=>	__('Map custom key value properties.', 'ws-form-klaviyo-v3'),
						'meta_keys'					=>	array(

							'action_' . $this->id . '_custom_mapping_key',
							'action_' . $this->id . '_custom_mapping_value'
						),
						'condition'					=>	array(

							array(

								'logic'			=>	'!=',
								'meta_key'		=>	'action_' . $this->id . '_list_id',
								'meta_value'	=>	''
							)
						)
					),

					// Custom field mapping - Key
					'action_' . $this->id . '_custom_mapping_key'	=> array(

						'label'						=>	__('Key', 'ws-form-klaviyo-v3'),
						'type'						=>	'text'
					),

					// Custom field mapping - Value
					'action_' . $this->id . '_custom_mapping_value'	=> array(

						'label'						=>	__('Value', 'ws-form-klaviyo-v3'),
						'type'						=>	'text',
						'placeholder'				=>	__('e.g. #field(123)', 'ws-form-klaviyo-v3')
					)
				);

				// Merge
				$meta_keys = array_merge($meta_keys, $config_meta_keys);

				return $meta_keys;
			}

			// Plug-in options for this action
			public function config_options($options) {

				$options['action_' . $this->id] = array(

					'label'		=>	$this->label,
					'fields'	=>	array(

						'action_' . $this->id . '_license_version'	=>	array(

							'label'		=>	__('Add-on Version', 'ws-form-klaviyo-v3'),
							'type'		=>	'static'
						),

						'action_' . $this->id . '_license_key'	=>	array(

							'label'		=>	__('Add-on License Key', 'ws-form-klaviyo-v3'),
							'type'		=>	'text',
							'help'		=>	__('Enter your klaviyo add-on for WS Form PRO license key here.', 'ws-form-klaviyo-v3'),
							'button'	=>	'license_action_' . $this->id,
							'action'	=>	$this->id
						),

						'action_' . $this->id . '_license_status'	=>	array(

							'label'		=>	__('Add-on License Status', 'ws-form-klaviyo-v3'),
							'type'		=>	'static'
						),

						'action_' . $this->id . '_api_key_private'	=>	array(

							'label'		=>	__('Private API Key', 'ws-form-klaviyo-v3'),
							'type'		=>	'text',
							'default'	=>	'',
							'help'		=>	__('For more information about the Klaviyo API, <a href="https://help.klaviyo.com/hc/en-us/articles/360028203871-How-do-I-find-my-API-keys-" target="_blank">click here</a>.', 'ws-form-klaviyo-v3')
						),
					)
				);

				return $options;
			}

			// Load config for this action
			public function load_config($config = array()) {

				if($this->list_id === false) { $this->list_id = parent::get_config($config, 'action_' . $this->id . '_list_id'); }
				$this->source = parent::get_config($config, 'action_' . $this->id . '_source', '#form_label');
				$this->opt_in_field = parent::get_config($config, 'action_' . $this->id . '_opt_in_field');
				$this->sms_consent_field = parent::get_config($config, 'action_' . $this->id . '_sms_consent_field');
				$this->field_mapping = parent::get_config($config, 'action_' . $this->id . '_field_mapping', array());
				if(!is_array($this->field_mapping)) { $this->field_mapping = array(); }
				$this->custom_mapping = parent::get_config($config, 'action_' . $this->id . '_custom_mapping', array());
				if(!is_array($this->custom_mapping)) { $this->custom_mapping = array(); }
			}

			// Load config at plugin level
			public function load_config_plugin() {

				$this->configured = false;

				$this->api_key_private = WS_Form_Common::option_get('action_' . $this->id . '_api_key_private', '');

				// Check API key
				if($this->api_key_private == '') { return $this->configured; }

				$this->api_endpoint = 'https://a.klaviyo.com/';

				$this->configured = true;

				return $this->configured;
			}

			// Get API headers
			public function get_api_headers() {

				return array(

					'Authorization' => 'Klaviyo-API-Key ' . $this->api_key_private,
					'Revision' => self::API_REVISION
				);
			}

			// Build REST API endpoints
			public function rest_api_init() {

				// API routes - get_* (Use cache)
				register_rest_route(WS_FORM_RESTFUL_NAMESPACE, '/action/' . $this->id . '/lists/', array('methods' => 'GET', 'callback' => array($this, 'api_get_lists'), 'permission_callback' => function () { return WS_Form_Common::can_user('create_form'); }));
				register_rest_route(WS_FORM_RESTFUL_NAMESPACE, '/action/' . $this->id . '/list/(?P<list_id>[a-zA-Z0-9]+)/', array('methods' => 'GET', 'callback' => array($this, 'api_get_list'), 'permission_callback' => function () { return WS_Form_Common::can_user('create_form'); }));
				register_rest_route(WS_FORM_RESTFUL_NAMESPACE, '/action/' . $this->id . '/list/(?P<list_id>[a-zA-Z0-9]+)/fields/', array('methods' => 'GET', 'callback' => array($this, 'api_get_list_fields'), 'permission_callback' => function () { return WS_Form_Common::can_user('create_form'); }));

				// API routes - fetch_* (Pull from API and update cache)
				register_rest_route(WS_FORM_RESTFUL_NAMESPACE, '/action/' . $this->id . '/lists/fetch/', array('methods' => 'GET', 'callback' => array($this, 'api_fetch_lists'), 'permission_callback' => function () { return WS_Form_Common::can_user('create_form'); }));
				register_rest_route(WS_FORM_RESTFUL_NAMESPACE, '/action/' . $this->id . '/list/(?P<list_id>[a-zA-Z0-9]+)/fetch/', array('methods' => 'GET', 'callback' => array($this, 'api_fetch_list'), 'permission_callback' => function () { return WS_Form_Common::can_user('create_form'); }));
				register_rest_route(WS_FORM_RESTFUL_NAMESPACE, '/action/' . $this->id . '/list/(?P<list_id>[a-zA-Z0-9]+)/fields/fetch/', array('methods' => 'GET', 'callback' => array($this, 'api_fetch_list_fields'), 'permission_callback' => function () { return WS_Form_Common::can_user('create_form'); }));
			}

			// API endpoint - Lists
			public function api_get_lists() {

				// Get lists
				$lists = self::get_lists();

				// Process response
				self::api_response($lists);
			}

			// API endpoint - List
			public function api_get_list($parameters) {

				// Get lists
				$this->list_id = WS_Form_Common::get_query_var('list_id', false, $parameters);
				$list = self::get_list();

				// Process response
				self::api_response($list);
			}

			// API endpoint - List fields
			public function api_get_list_fields($parameters) {

				// Get lists
				$this->list_id = WS_Form_Common::get_query_var('list_id', false, $parameters);
				$list_fields = self::get_list_fields();

				// Process response
				self::api_response($list_fields);
			}

			// API endpoint - Lists with fetch
			public function api_fetch_lists() {

				// Get lists
				$lists = self::get_lists(true);

				// Process response
				self::api_response($lists);
			}

			// API endpoint - List with fetch
			public function api_fetch_list($parameters) {

				// Get lists
				$this->list_id = WS_Form_Common::get_query_var('list_id', false, $parameters);
				$list = self::get_list(true);

				// Process response
				self::api_response($list);
			}

			// API endpoint - List fields with fetch
			public function api_fetch_list_fields($parameters) {

				// Get lists
				$this->list_id = WS_Form_Common::get_query_var('list_id', false, $parameters);
				$list_fields = self::get_list_fields(true);

				// Process response
				self::api_response($list_fields);
			}

			// SVG Logo - Color (Used for the 'Add Form' page)
			public function get_svg_logo_color($list_id = false) {

				$svg_logo = '<g transform="translate(39.000000, 67.000000)"><path d="M67.8,45.4H0V0h67.8L53.6,22.7L67.8,45.4L67.8,45.4z"></path></g>';

				return $svg_logo;
			}
		}
	});