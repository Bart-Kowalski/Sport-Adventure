<?php

	class WS_Form_Action_User extends WS_Form_Action {

		public $id = 'user';
		public $pro_required = true;
		public $label;
		public $label_action;
		public $events;
		public $multiple = true;
		public $configured = false;
		public $priority = 25;
		public $can_repost = true;
		public $form_add = false;
		public $get_require_list_id = false;
		public $get_require_field_mapping = false;

		// Add new features
		public $add_new_reload = false;

		// Licensing
		private $licensing;

		// Config
		public $method;
		public $list_id = false;
		public $secure_cookie = false;
		public $rich_editing = false;
		public $syntax_highlighting = false;
		public $comment_shortcuts = false;
		public $show_admin_bar_front = false;
		public $admin_color;
		public $role;
		public $password_create;
		public $password_length;
		public $password_special_characters;

		public $field_mapping;
		public $meta_mapping_custom;

		public $field_mapping_acf;
		public $field_mapping_meta_box;
		public $field_mapping_pods;
		public $field_mapping_toolset;

		public $meta_mapping;

		// WooCommerce
		public $woocommerce_activated;

		// ACF
		public $acf_activated;
		public $acf_validation;

		// Metabox
		public $meta_box_activated;

		// Pods
		public $pods_activated;

		// Toolset
		public $toolset_activated;

		// JetEngine
		public $jetengine_activated;

		// Constants
		const WS_FORM_PASSWORD_LENGTH_DEFAULT = 12;
		const WS_FORM_LICENSE_ITEM_ID = 650;
		const WS_FORM_LICENSE_NAME = 'User Management add-on for WS Form PRO';
		const WS_FORM_LICENSE_VERSION = WS_FORM_USER_VERSION;
		const WS_FORM_LICENSE_AUTHOR = 'WS Form';
		const WS_FORM_ADMIN_COLOR_DEFAULT = 'fresh';

		public function __construct() {

			// Set label
			$this->label = __('User Management', 'ws-form-user');

			// Set label for actions pull down
			$this->label_action = __('User Management', 'ws-form-user');

			// Events
			$this->events = array('submit');

			// WooCommerce
			$this->woocommerce_activated = class_exists('WS_Form_WooCommerce');

			// ACF
			$this->acf_activated = class_exists('WS_Form_ACF');

			// Meta Box
			$this->meta_box_activated = class_exists('WS_Form_Meta_Box');

			// Pods
			$this->pods_activated = class_exists('WS_Form_Pods');

			// Toolset
			$this->toolset_activated = class_exists('WS_Form_Toolset');

			// JetEngine
			$this->jetengine_activated = class_exists('WS_Form_JetEngine');

			// Admin color
			$this->admin_color = self::WS_FORM_ADMIN_COLOR_DEFAULT;

			// Filter and action hooks
			add_filter('wsf_config_options', array($this, 'config_options'), 10, 1);
			add_filter('wsf_config_meta_keys', array($this, 'config_meta_keys'), 10, 2);
			add_filter('wsf_config_settings_form_admin', array($this, 'config_settings_form_admin'), 20, 1);
			add_filter('wsf_settings_static', array($this, 'settings_static'), 10, 2);
			add_filter('wsf_settings_button', array($this, 'settings_button'), 10, 3);
			add_filter('wsf_settings_update_fields', array($this, 'settings_update_fields'), 10, 2);
			add_filter('plugin_action_links_' . WS_FORM_USER_PLUGIN_BASENAME, array($this, 'plugin_action_links'), 10, 1);
			add_action('rest_api_init', array($this, 'rest_api_init'), 10, 0);

			// Licensing
			$this->licensing = new WS_Form_Licensing(

				self::WS_FORM_LICENSE_ITEM_ID,
				$this->id,
				self::WS_FORM_LICENSE_NAME,
				self::WS_FORM_LICENSE_VERSION,
				self::WS_FORM_LICENSE_AUTHOR,
				WS_FORM_USER_PLUGIN_ROOT_FILE
			);
			$this->licensing->transient_check();
			add_action('admin_init', array($this->licensing, 'updater'));

			// Register action
			parent::register($this);

			// Load plugin level configuration
			self::load_config_plugin();
		}

		// Get license item ID
		public function get_license_item_id() {

			return self::WS_FORM_LICENSE_ITEM_ID;
		}

		// Plugin action link
		public function plugin_action_links($links) {

			// Settings
			array_unshift($links, sprintf('<a href="%s">%s</a>', WS_Form_Common::get_admin_url('ws-form-settings', false, 'tab=action_' . $this->id), __('Settings', 'ws-form-user')));

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

						$value = '<input class="wsf-button" type="button" data-action="wsf-mode-submit" data-mode="deactivate" value="' . __('Deactivate', 'ws-form-user') . '" />';

					} else {

						$value = '<input class="wsf-button" type="button" data-action="wsf-mode-submit" data-mode="activate" value="' . __('Activate', 'ws-form-user') . '" />';
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

		// Post to API
		public function post($form, &$submit, $config) {

			// Check action is configured properly
			if(!self::check_configured()) { return false; }

			// Load configuration
			$this->list_id = false;
			self::load_config($config);

			// Check list ID is configured properly
			if(!self::check_list_id()) { return false; }

			// Process field mapping
			$api_fields = array();
			$meta_keys = array();

			foreach($this->field_mapping as $field_map) {

				// Get API field
				$api_field = $field_map['action_' . $this->id . '_list_fields'];

				// Get submit value
				$field_id = $field_map['ws_form_field'];
				$submit_value = parent::get_submit_value($submit, WS_FORM_FIELD_PREFIX . $field_id, false, true);
				if($submit_value === false) { continue; }

				// Convert arrays
				if(is_array($submit_value)) { $submit_value = implode(',', $submit_value); }

				// Set value
				$api_fields[$api_field] = $submit_value;
			}

			$attachment_mapping = array();

			// Process ACF
			$acf_file_fields = array();
			$acf_update_fields = array();

			if($this->acf_activated) {

				// Get first option value so we can use that to set the value
				$fields = WS_Form_Common::get_fields_from_form($form);

				// Get field types
	 			$field_types = WS_Form_Config::get_field_types_flat();

				// Field validation
				if($this->acf_validation) {

					$acf_field_validation_error = false;
				}

				// Run through each field mapping
				foreach($this->field_mapping_acf as $field_map_acf) {

					// Get ACF key
					$acf_key = $field_map_acf['action_' . $this->id . '_acf_key'];

					// Get submit value
					$field_id = $field_map_acf['ws_form_field'];
					$field_name = WS_FORM_FIELD_PREFIX . $field_id;
					$get_submit_value_repeatable_return = parent::get_submit_value_repeatable($submit, $field_name, array(), true);

					if(
						!is_array($get_submit_value_repeatable_return) ||
						!is_array($get_submit_value_repeatable_return['value']) ||
						!isset($get_submit_value_repeatable_return['value'][0])
					) { continue; }

					// Get ACF field type
					$acf_field_type = WS_Form_ACF::acf_get_field_type($acf_key);
					if($acf_field_type === false) { continue; }

					// ACF field type processing
					$acf_field_is_file = in_array($acf_field_type, WS_Form_ACF::acf_get_field_types_file());
					if($acf_field_is_file) {

						// Check to see if this field is attachment mapped, if it isn't, add it
						$field_already_mapped = false;
						foreach($attachment_mapping as $attachment_map) {

							if($attachment_map['ws_form_field'] == $field_id) {

								$field_already_mapped = true;
								break;
							}
						}
						if(!$field_already_mapped) {

							$attachment_mapping[] = array('ws_form_field' => $field_id);
						}

						// Remember which ACF key this field needs to be mapped to
						$acf_file_fields[$field_id] = $acf_key;
					}

					// Get parent ACF field type
					$acf_data = WS_Form_ACF::acf_get_parent_data($acf_key);
					$acf_parent_field_type = isset($acf_data['type']) ? $acf_data['type'] : false;
					$acf_parent_field_acf_key = isset($acf_data['acf_key']) ? $acf_data['acf_key'] : false;

					// Check if parent is repeatable
					switch($acf_parent_field_type) {

						case 'repeater' :

							$repeatable_index = $get_submit_value_repeatable_return['repeatable_index'];

							if(!isset($acf_update_fields[$acf_parent_field_acf_key])) {

								$acf_update_fields[$acf_parent_field_acf_key] = array();
							}

							// Add each value
							foreach($get_submit_value_repeatable_return['value'] as $repeater_index => $meta_value) {

								if(!isset($acf_update_fields[$acf_parent_field_acf_key][$repeater_index])) {

									$acf_update_fields[$acf_parent_field_acf_key][$repeater_index] = array();
								}								

								// Convert empty arrays to empty strings
								if(is_array($meta_value) && (count($meta_value) == 0)) { $meta_value = ''; }

								// Process meta value
								$meta_value = WS_Form_ACF::acf_ws_form_field_value_to_acf_meta_value($meta_value, $acf_field_type, $field_id, $fields, $field_types, $acf_key);

								// ACF field validation
								if($this->acf_validation) {

									$section_repeatable_index = isset($repeatable_index[$repeater_index]) ? $repeatable_index[$repeater_index] : 0;
									$valid = WS_Form_ACF::acf_validate_value($submit, $field_id, $section_repeatable_index, $meta_value, $acf_key, sprintf('acf[%s]', $acf_key));
									if($valid !== true) { $acf_field_validation_error = true; }
								}

								// If this is a file and no file submitted, then remove file by setting value to null
								if($acf_field_is_file) {

									if(empty($meta_value)) {

										$acf_update_fields[$acf_parent_field_acf_key][$repeater_index][$acf_key] = null;
									}

								} else {

									// Add to fields to update
									$acf_update_fields[$acf_parent_field_acf_key][$repeater_index][$acf_key] = $meta_value;
								}
							}

							break;

						case 'group' :

							// Get meta value
							$meta_value = $get_submit_value_repeatable_return['value'][0];

							// Convert empty arrays to empty strings
							if(is_array($meta_value) && (count($meta_value) == 0)) { $meta_value = ''; }

							// Process meta value
							$meta_value = WS_Form_ACF::acf_ws_form_field_value_to_acf_meta_value($meta_value, $acf_field_type, $field_id, $fields, $field_types, $acf_key);

							// ACF field validation
							if($this->acf_validation) {

								$valid = WS_Form_ACF::acf_validate_value($submit, $field_id, 0, $meta_value, $acf_key, sprintf('acf[%s]', $acf_key));
								if($valid !== true) { $acf_field_validation_error = true; }
							}

							if(!isset($acf_update_fields[$acf_parent_field_acf_key])) {

								$acf_update_fields[$acf_parent_field_acf_key] = array();
							}

							// If this is a file and no file submitted, then remove file by setting value to null
							if($acf_field_is_file) {

								if(empty($meta_value)) {

									$acf_update_fields[$acf_parent_field_acf_key][$acf_key] = null;
								}

							} else {

								// Add to fields to update
								$acf_update_fields[$acf_parent_field_acf_key][$acf_key] = $meta_value;
							}

							break;

						default :

							// Get meta value
							$meta_value = $get_submit_value_repeatable_return['value'][0];

							// Convert empty arrays to empty strings
							if(is_array($meta_value) && (count($meta_value) == 0)) { $meta_value = ''; }

							// Process meta value
							$meta_value = WS_Form_ACF::acf_ws_form_field_value_to_acf_meta_value($meta_value, $acf_field_type, $field_id, $fields, $field_types, $acf_key);

							// ACF field validation
							if($this->acf_validation) {

								$valid = WS_Form_ACF::acf_validate_value($submit, $field_id, 0, $meta_value, $acf_key, sprintf('acf[%s]', $acf_key));
								if($valid !== true) { $acf_field_validation_error = true; }
							}

							// If this is a file and no file submitted, then remove file by setting value to null
							if($acf_field_is_file) {

								if(empty($meta_value)) {

									$acf_update_fields[$acf_key] = null;
								}

							} else {

								// Add to fields to update
								$acf_update_fields[$acf_key] = $meta_value;
							}
					}
				}

				// Check for ACF field validation errors
				if($this->acf_validation && $acf_field_validation_error) { return 'halt'; }
			}

			// Process Meta Box
			$meta_box_file_fields = array();
			$meta_box_update_fields = array();

			if($this->meta_box_activated) {

				// Field validation
				$meta_box_field_validation_error = false;

				// Run through each field mapping
				foreach($this->field_mapping_meta_box as $field_map_meta_box) {

					// Get Meta Box key
					$meta_box_field_id = $field_map_meta_box['action_' . $this->id . '_meta_box_field_id'];

					// Get submit value
					$field_id = $field_map_meta_box['ws_form_field'];
					$field_name = WS_FORM_FIELD_PREFIX . $field_id;
					$get_submit_value_repeatable_return = parent::get_submit_value_repeatable($submit, $field_name, array(), true);

					if(
						!is_array($get_submit_value_repeatable_return) ||
						!is_array($get_submit_value_repeatable_return['value']) ||
						!isset($get_submit_value_repeatable_return['value'][0])
					) { continue; }

					// Get Meta Box field type
					$meta_box_field_type = WS_Form_Meta_Box::meta_box_get_field_type($meta_box_field_id);
					if($meta_box_field_type === false) { continue; }

					// Field type data formatting
					switch($meta_box_field_type) {

						// Single image is not stored as an array
						case 'single_image' :

							$meta_value_file_empty = 0;
							break;

						default :

							$meta_value_file_empty = array();
					}

					// Meta Box field type processing
					$meta_box_field_is_file = in_array($meta_box_field_type, WS_Form_Meta_Box::meta_box_get_field_types_file());
					if($meta_box_field_is_file) {

						// Check to see if this field is attachment mapped, if it isn't, add it
						$field_already_mapped = false;
						foreach($attachment_mapping as $attachment_map) {

							if($attachment_map['ws_form_field'] == $field_id) {

								$field_already_mapped = true;
								break;
							}
						}
						if(!$field_already_mapped) {

							$attachment_mapping[] = array('ws_form_field' => $field_id);
						}

						// Remember which Meta Box key this field needs to be mapped to
						$meta_box_file_fields[$field_id] = $meta_box_field_id;
					}

					// Get parent Meta Box field type
					$meta_box_parent_data = WS_Form_Meta_Box::meta_box_get_parent_data($meta_box_field_id);
					$meta_box_parent_field_type = isset($meta_box_parent_data['type']) ? $meta_box_parent_data['type'] : false;
					$meta_box_parent_repeater = isset($meta_box_parent_data['repeater']) ? $meta_box_parent_data['repeater'] : false;

					// Check if parent is repeatable
					switch($meta_box_parent_field_type) {

						case 'key_value' :
						case 'group' :

							$meta_box_parent_field_id = isset($meta_box_parent_data['field_id']) ? $meta_box_parent_data['field_id'] : false;

							if(!isset($meta_box_update_fields[$meta_box_parent_field_id])) {

								$meta_box_update_fields[$meta_box_parent_field_id] = array();
							}

							$repeatable_index = $get_submit_value_repeatable_return['repeatable_index'];

							// Add each value
							foreach($get_submit_value_repeatable_return['value'] as $repeater_index => $meta_value) {

								// Convert empty arrays to empty strings
								if(is_array($meta_value) && (count($meta_value) == 0)) { $meta_value = ''; }

								// Process meta value
								$meta_value = WS_Form_Meta_Box::meta_box_ws_form_field_value_to_meta_box_meta_value($meta_value, $meta_box_field_type, $meta_box_field_id);

								// Key value index changes
								if($meta_box_parent_field_id . '_key' === $meta_box_field_id) { $meta_box_field_id = 0; }
								if($meta_box_parent_field_id . '_value' === $meta_box_field_id) { $meta_box_field_id = 1; }

								// Add to fields to update
								if($meta_box_parent_repeater) {

									// As repeater (clone enabled)
									if(!isset($meta_box_update_fields[$meta_box_parent_field_id][$repeater_index])) {

										$meta_box_update_fields[$meta_box_parent_field_id][$repeater_index] = array();
									}

									// If this is a file and no file submitted, then remove file by setting value to 0
									if($meta_box_field_is_file) {

										if(empty($meta_value)) {

											$meta_box_update_fields[$meta_box_parent_field_id][$repeater_index][$meta_box_field_id] = $meta_value_file_empty;
										}

									} else {

										// Add to fields to update
										$meta_box_update_fields[$meta_box_parent_field_id][$repeater_index][$meta_box_field_id] = $meta_value;
									}

								} else {

									// As regular row (clone disabled)
									if(!isset($meta_box_update_fields[$meta_box_parent_field_id][$meta_box_field_id])) {

										$meta_box_update_fields[$meta_box_parent_field_id][$meta_box_field_id] = array();
									}

									// If this is a file and no file submitted, then remove file by setting value to 0
									if($meta_box_field_is_file) {

										if(empty($meta_value)) {

											$meta_box_update_fields[$meta_box_parent_field_id][$meta_box_field_id] = $meta_value_file_empty;
										}

									} else {

										// Add to fields to update
										$meta_box_update_fields[$meta_box_parent_field_id][$meta_box_field_id] = $meta_value;
									}
								}
							}

							break;

						default :

							// Get meta value
							$meta_value = $get_submit_value_repeatable_return['value'][0];

							// Convert empty arrays to empty strings
							if(is_array($meta_value) && (count($meta_value) == 0)) { $meta_value = ''; }

							// Process meta value
							$meta_value = WS_Form_Meta_Box::meta_box_ws_form_field_value_to_meta_box_meta_value($meta_value, $meta_box_field_type, $meta_box_field_id);

							// If this is a file and no file submitted, then remove file by setting value to 0
							if($meta_box_field_is_file) {

								if(empty($meta_value)) {

									$meta_box_update_fields[$meta_box_field_id] = $meta_value_file_empty;
								}

							} else {

								// Add to fields to update
								$meta_box_update_fields[$meta_box_field_id] = $meta_value;
							}
					}
				}

				// Check for Meta Box field validation errors
				if($meta_box_field_validation_error) { return 'halt'; }
			}

			// Process Pods
			$pods_file_fields = array();
			$pods_update_fields = array();

			if($this->pods_activated) {

				// Build pods ID to name lookup
				$pods_id_to_name_lookup = WS_Form_Pods::pods_get_id_to_name_lookup('user');

				// Run through each field mapping
				foreach($this->field_mapping_pods as $field_map_pods) {

					// Get Pods field ID
					$pods_field_id = $field_map_pods['action_' . $this->id . '_pods_field_id'];

					// Get Pods field name
					if(!isset($pods_id_to_name_lookup[$pods_field_id])) { continue; }
					$pods_field_name = $pods_id_to_name_lookup[$pods_field_id];

					// Get submit value
					$field_id = $field_map_pods['ws_form_field'];
					$field_name = WS_FORM_FIELD_PREFIX . $field_id;
					$get_submit_value_repeatable_return = parent::get_submit_value_repeatable($submit, $field_name, array(), true);

					if(
						!is_array($get_submit_value_repeatable_return) ||
						!is_array($get_submit_value_repeatable_return['value']) ||
						!isset($get_submit_value_repeatable_return['value'][0])
					) { continue; }

					// Get Pods field type
					$pods_field_type = WS_Form_Pods::pods_get_field_type($pods_field_id);
					if($pods_field_type === false) { continue; }

					// Pods field type processing
					$pods_field_is_file = in_array($pods_field_type, WS_Form_Pods::pods_get_field_types_file());
					if($pods_field_is_file) {

						// Check to see if this field is attachment mapped, if it isn't, add it
						$field_already_mapped = false;
						foreach($attachment_mapping as $attachment_map) {

							if($attachment_map['ws_form_field'] == $field_id) {

								$field_already_mapped = true;
								break;
							}
						}
						if(!$field_already_mapped) {

							$attachment_mapping[] = array('ws_form_field' => $field_id);
						}

						// Remember which Pods key this field needs to be mapped to
						$pods_file_fields[$field_id] = $pods_field_id;
					}

					// Get meta value
					$meta_value = $get_submit_value_repeatable_return['value'][0];

					// Convert empty arrays to empty strings
					if(is_array($meta_value) && (count($meta_value) == 0)) { $meta_value = ''; }

					// Process meta value
					$meta_value = WS_Form_Pods::pods_ws_form_field_value_to_pods_meta_value($meta_value, $pods_field_type, $pods_field_id);

					// If this is a file and no file submitted, then remove file by setting value to 0
					if($pods_field_is_file) {

						if(empty($meta_value)) {

							$pods_update_fields[$pods_field_name] = 0;
						}

					} else {

						// Add to fields to update
						$pods_update_fields[$pods_field_name] = $meta_value;
					}
				}
			}

			// Process Toolset
			$toolset_file_fields = array();
			$toolset_update_fields = array();
			$toolset_field_type_lookup = array();

			if($this->toolset_activated) {

				// Run through each field mapping
				foreach($this->field_mapping_toolset as $field_map_toolset) {

					// Get Toolset field slug
					$toolset_field_slug = $field_map_toolset['action_' . $this->id . '_toolset_field_slug'];

					// Get submit value
					$field_id = $field_map_toolset['ws_form_field'];
					$field_name = WS_FORM_FIELD_PREFIX . $field_id;
					$get_submit_value_repeatable_return = parent::get_submit_value_repeatable($submit, $field_name, array(), true);

					if(
						!is_array($get_submit_value_repeatable_return) ||
						!is_array($get_submit_value_repeatable_return['value']) ||
						!isset($get_submit_value_repeatable_return['value'][0])
					) { continue; }

					// Get Toolset field type
					$toolset_field_type = WS_Form_Toolset::toolset_get_field_type($toolset_field_slug, 'wpcf-usermeta');
					if($toolset_field_type === false) { continue; }

					// Add to fields type lookup
					$toolset_field_type_lookup[$toolset_field_slug] = $toolset_field_type;

					// Toolset field type processing
					$toolset_field_is_file = in_array($toolset_field_type, WS_Form_Toolset::toolset_get_field_types_file());
					if($toolset_field_is_file) {

						// Check to see if this field is attachment mapped, if it isn't, add it
						$field_already_mapped = false;
						foreach($attachment_mapping as $attachment_map) {

							if($attachment_map['ws_form_field'] == $field_id) {

								$field_already_mapped = true;
								break;
							}
						}
						if(!$field_already_mapped) {

							$attachment_mapping[] = array('ws_form_field' => $field_id);
						}

						// Remember which Toolset slug this field needs to be mapped to
						$toolset_file_fields[$field_id] = $toolset_field_slug;
					}

					// Get meta value
					$meta_value = $get_submit_value_repeatable_return['value'][0];

					// Convert empty arrays to empty strings
					if(is_array($meta_value) && (count($meta_value) == 0)) { $meta_value = ''; }

					// Process meta value
					$meta_value = WS_Form_Toolset::toolset_ws_form_field_value_to_toolset_meta_value($meta_value, $toolset_field_type, $toolset_field_slug, 'wpcf-usermeta');

					// If this is a file and no file submitted, then remove file by setting value to 0
					if($toolset_field_is_file) {

						if(empty($meta_value)) {

							$toolset_update_fields[$toolset_field_slug] = 0;
						}

					} else {

						// Add to fields to update
						$toolset_update_fields[$toolset_field_slug] = $meta_value;
					}
				}
			}

			// Process JetEngine
			$jetengine_file_fields = array();
			$jetengine_update_fields = array();

			if($this->jetengine_activated) {

				// Get first option value so we can use that to set the value
				$fields = WS_Form_Common::get_fields_from_form($form);

				// Get field types
	 			$field_types = WS_Form_Config::get_field_types_flat();

				// Run through each field mapping
				foreach($this->field_mapping_jetengine as $field_map_jetengine) {

					// Get JetEngine field name
					$jetengine_field_name = $field_map_jetengine['action_' . $this->id . '_jetengine_field_name'];

					// Get submit value
					$field_id = $field_map_jetengine['ws_form_field'];
					$field_name = WS_FORM_FIELD_PREFIX . $field_id;
					$get_submit_value_repeatable_return = parent::get_submit_value_repeatable($submit, $field_name, array(), true);

					if(
						!is_array($get_submit_value_repeatable_return) ||
						!is_array($get_submit_value_repeatable_return['value']) ||
						!isset($get_submit_value_repeatable_return['value'][0])
					) { continue; }

					// Get JetEngine field type
					$jetengine_field_type = WS_Form_JetEngine::jetengine_get_field_type($jetengine_field_name, 'user');
					if($jetengine_field_type === false) { continue; }

					// Get parent JetEngine field type
					$jetengine_data = WS_Form_JetEngine::jetengine_get_parent_data($jetengine_field_name, 'user');
					$jetengine_parent_field_type = isset($jetengine_data['type']) ? $jetengine_data['type'] : false;
					$jetengine_parent_field_jetengine_field_name = isset($jetengine_data['name']) ? $jetengine_data['name'] : false;

					// JetEngine field type processing
					$jetengine_field_is_file = in_array($jetengine_field_type, WS_Form_JetEngine::jetengine_get_field_types_file());
					if($jetengine_field_is_file) {

						// Check to see if this field is attachment mapped, if it isn't, add it
						$field_already_mapped = false;
						foreach($attachment_mapping as $attachment_map) {

							if($attachment_map['ws_form_field'] == $field_id) {

								$field_already_mapped = true;
								break;
							}
						}
						if(!$field_already_mapped) {

							$attachment_mapping[] = array('ws_form_field' => $field_id);
						}

						// Remember which JetEngine key this field needs to be mapped to
						$jetengine_file_fields[$field_id] = array(

							'jetengine_field_name' => $jetengine_field_name,
							'jetengine_parent_field_type' => $jetengine_parent_field_type,
							'jetengine_parent_field_jetengine_field_name' => $jetengine_parent_field_jetengine_field_name
						);
					}

					// Check if parent is repeatable
					switch($jetengine_parent_field_type) {

						case 'repeater' :

							$repeatable_index = $get_submit_value_repeatable_return['repeatable_index'];

							if(!isset($jetengine_update_fields[$jetengine_parent_field_jetengine_field_name])) {

								$jetengine_update_fields[$jetengine_parent_field_jetengine_field_name] = array();
							}

							// Add each value
							foreach($get_submit_value_repeatable_return['value'] as $repeater_index => $meta_value) {

								$item_name = sprintf('item-%u', $repeater_index);

								if(!isset($jetengine_update_fields[$jetengine_parent_field_jetengine_field_name][$item_name])) {

									$jetengine_update_fields[$jetengine_parent_field_jetengine_field_name][$item_name] = array();
								}								

								// Convert empty arrays to empty strings
								if(is_array($meta_value) && (count($meta_value) == 0)) { $meta_value = ''; }

								// Process meta value
								$meta_value = WS_Form_JetEngine::jetengine_ws_form_field_value_to_jetengine_meta_value($meta_value, $jetengine_field_type, $jetengine_field_name, $field_id, $fields, $field_types, 'user');

								// If this is a file and no file submitted, then remove file by setting value to null
								if($jetengine_field_is_file) {

									if(empty($meta_value)) {

										$jetengine_update_fields[$jetengine_parent_field_jetengine_field_name][$item_name][$jetengine_field_name] = null;
									}

								} else {

									// Add to fields to update
									$jetengine_update_fields[$jetengine_parent_field_jetengine_field_name][$item_name][$jetengine_field_name] = $meta_value;
								}
							}

							break;

						default :

							// Get meta value
							$meta_value = $get_submit_value_repeatable_return['value'][0];

							// Convert empty arrays to empty strings
							if(is_array($meta_value) && (count($meta_value) == 0)) { $meta_value = ''; }

							// Process meta value
							$meta_value = WS_Form_JetEngine::jetengine_ws_form_field_value_to_jetengine_meta_value($meta_value, $jetengine_field_type, $jetengine_field_name, $field_id, $fields, $field_types, 'user');

							// If this is a file and no file submitted, then remove file by setting value to null
							if($jetengine_field_is_file) {

								if(empty($meta_value)) {

									$jetengine_update_fields[$jetengine_field_name] = null;
								}

							} else {

								// Add to fields to update
								$jetengine_update_fields[$jetengine_field_name] = $meta_value;
							}
					}
				}
			}

			// Process WooCommerce
			if($this->woocommerce_activated) {

				$woocommerce_fields = WS_Form_WooCommerce::woocommerce_get_fields_all('user', false, false, true, false, false);

				foreach($woocommerce_fields as $meta_key => $woocommerce_field) {

					if(isset($api_fields[$meta_key])) {

						$meta_keys[$meta_key] = $api_fields[$meta_key];
					}
				}
			}

			// Process meta mapping
			foreach($this->meta_mapping as $meta_map) {

				$field_id = $meta_map['ws_form_field'];
				$meta_key = $meta_map['action_' . $this->id . '_meta_key'];

				// Get submit value
				$meta_value = parent::get_submit_value($submit, WS_FORM_FIELD_PREFIX . $field_id, false, true);
				if($meta_value === false) { continue; }

				// Convert arrays
				if(is_array($meta_value)) { $meta_value = implode(',', $meta_value); }

				$meta_keys[$meta_key] = $meta_value;
			}

			// Process custom meta mapping
			foreach($this->meta_mapping_custom as $meta_map) {

				$meta_key = $meta_map['action_' . $this->id . '_meta_key'];
				if(empty($meta_key)) { continue; }

				$meta_value = $meta_map['action_' . $this->id . '_meta_value'];

				$meta_keys[$meta_key] = WS_Form_Common::parse_variables_process($meta_value, $form, $submit, 'text/plain');
			}

			// Remember me
			$remember_me = isset($api_fields['remember_me']) ? !empty($api_fields['remember_me']) : false;

			switch($this->list_id) {

				case 'register' :

					// Password confirmation check
					if(!isset($api_fields['password'])) { $api_fields['password'] = ''; };
					if(!isset($api_fields['password_confirm'])) { $api_fields['password_confirm'] = ''; };

					if($api_fields['password'] == '') {

						if($this->password_create) {

							// Get password length
							$password_length = intval($this->password_length);
							if($password_length == 0) { $password_length = self::WS_FORM_PASSWORD_LENGTH_DEFAULT; }

							// Get special character
							$password_special_characters = ($this->password_special_characters == 'on');

							// Create password
							$api_fields['password'] = $api_fields['password_confirm'] = wp_generate_password($password_length, $password_special_characters);

						} else {

							// Error, no password specified
							self::error_js('Password not specified'); return 'halt';
						}

					}

					// Check passwords match
					if($api_fields['password'] != $api_fields['password_confirm']) { self::error_js('Passwords do not match'); return 'halt'; }

					// Build credentials
					$userdata = array();
					if(isset($api_fields['email'])) { $userdata['user_email'] = $api_fields['email']; }
					if(isset($api_fields['username'])) { $userdata['user_login'] = $api_fields['username']; }
					if(empty($userdata['user_login'])) { $userdata['user_login'] = $api_fields['email']; }
					if(isset($api_fields['password'])) { $userdata['user_pass'] = $api_fields['password']; }
					if(isset($api_fields['website'])) { $userdata['user_url'] = $api_fields['website']; }
					if(isset($api_fields['first_name'])) { $userdata['first_name'] = $api_fields['first_name']; }
					if(isset($api_fields['last_name'])) { $userdata['last_name'] = $api_fields['last_name']; }
					if(isset($api_fields['nickname'])) { $userdata['nickname'] = $api_fields['nickname']; }
					if(isset($api_fields['description'])) { $userdata['description'] = $api_fields['description']; }
					if(isset($api_fields['display_name'])) { $userdata['display_name'] = $api_fields['display_name']; }
					$userdata['role'] = (isset($this->role) && ($this->role != '')) ? $this->role : get_option('default_role');

					// Build meta keys
					$meta_keys['rich_editing'] = isset($this->rich_editing) ? (($this->rich_editing != '') ? 'false' : 'true') : 'true';
					$meta_keys['syntax_highlighting'] = isset($this->syntax_highlighting) ? (($this->syntax_highlighting != '') ? 'true' : 'false') : 'false';
					$meta_keys['comment_shortcuts'] = isset($this->comment_shortcuts) ? (($this->comment_shortcuts != '') ? 'true' : 'false') : 'false';
					$meta_keys['show_admin_bar_front'] = isset($this->show_admin_bar_front) ? (($this->show_admin_bar_front != '') ? 'true' : 'false') : 'true';
					$meta_keys['admin_color'] = isset($this->admin_color) ? $this->admin_color : 'fresh';

					// Add slashes
					$userdata = wp_slash($userdata);

					// WordPress insert user
					$user_id = wp_insert_user($userdata);

					// Error management
					if(is_wp_error($user_id)) {

						self::wp_error_process($user_id);
						return 'halt';
					}

					// Update user meta
					if(count($meta_keys) > 0) { self::update_user_meta_do($user_id, $meta_keys); }

					// Save user ID to submission
					$submit->user_id = $user_id;

					// Expose user data
					$user = get_userdata($user_id);
					$GLOBALS['ws_form_user'] = $user;

					// Post processing
					self::post_process($attachment_mapping, $submit, $acf_update_fields, $acf_file_fields, $meta_box_update_fields, $meta_box_file_fields, $pods_update_fields, $pods_file_fields, $toolset_update_fields, $toolset_field_type_lookup, $toolset_file_fields, $jetengine_update_fields, $jetengine_file_fields, $user_id);

					// Send user notification
					switch($this->send_user_notification) {

						case 'admin' :
						case 'both' :
						case 'user' :

							do_action('edit_user_created_user', $user_id, $this->send_user_notification);
							break;
					}

					// Do action
					do_action('wsf_action_' . $this->id . '_register', $form, $submit, $config);

					// Success
					parent::success(sprintf(__('User registration successful! User: %s' , 'ws-form-user'), $userdata['user_login']));

					return true;

				case 'update' :

					// Get user
					if(!function_exists('wp_get_current_user')) {

						include_once(ABSPATH . 'wp-includes/pluggable.php');
					}
					$current_user = wp_get_current_user();

					// Check user
					if($current_user->ID == 0) { self::error_js('User not logged in'); return 'halt';  }

					// Password confirmation check
					if(!isset($api_fields['password'])) { $api_fields['password'] = ''; };

					// Build credentials
					$userdata = array();
					$userdata['ID'] = $current_user->ID;
					if(isset($api_fields['first_name'])) { $userdata['first_name'] = $api_fields['first_name']; }
					if(isset($api_fields['last_name'])) { $userdata['last_name'] = $api_fields['last_name']; }
					if(isset($api_fields['nickname'])) { $userdata['nickname'] = $api_fields['nickname']; }
					if(isset($api_fields['display_name'])) { $userdata['display_name'] = $api_fields['display_name']; }
					if(isset($api_fields['email'])) { $userdata['user_email'] = $api_fields['email']; }
					if(isset($api_fields['website'])) { $userdata['user_url'] = $api_fields['website']; }
					if(isset($api_fields['description'])) { $userdata['description'] = $api_fields['description']; }

					if(isset($api_fields['rich_editing'])) { $meta_keys['rich_editing'] =  ($api_fields['rich_editing'] ? 'false' : 'true'); }
					if(isset($api_fields['syntax_highlighting'])) { $meta_keys['syntax_highlighting'] =  ($api_fields['syntax_highlighting'] ? 'false' : 'true'); }
					if(isset($api_fields['comment_shortcuts'])) { $meta_keys['comment_shortcuts'] =  ($api_fields['comment_shortcuts'] ? 'true' : 'false'); }
					if(isset($api_fields['show_admin_bar_front'])) { $meta_keys['show_admin_bar_front'] =  ($api_fields['show_admin_bar_front'] ? 'true' : 'false'); }
					if(isset($api_fields['admin_color'])) { $meta_keys['admin_color'] = $api_fields['admin_color']; }
					if(empty($meta_keys['admin_color'])) { $meta_keys['admin_color'] = self::WS_FORM_ADMIN_COLOR_DEFAULT; }

					// Password change?
					if(isset($api_fields['password']) && ($api_fields['password'] != '')) { $userdata['user_pass'] = $api_fields['password']; }

					// Add slashes
					$userdata = wp_slash($userdata);

					// WordPress update user
					$user_id = wp_update_user($userdata);

					// Error management
					if(is_wp_error($user_id)) {

						self::wp_error_process($user_id);
						return 'halt';
					}

					// Save user ID to submission
					$submit->user_id = $user_id;

					// Update user meta
					if(count($meta_keys) > 0) { self::update_user_meta_do($user_id, $meta_keys); }

					// Post processing
					self::post_process($attachment_mapping, $submit, $acf_update_fields, $acf_file_fields, $meta_box_update_fields, $meta_box_file_fields, $pods_update_fields, $pods_file_fields, $toolset_update_fields, $toolset_field_type_lookup, $toolset_file_fields, $jetengine_update_fields, $jetengine_file_fields, $user_id);

					// Do action
					do_action('wsf_action_' . $this->id . '_update', $form, $submit, $config);

					// Success
					parent::success(__('User update successful!', 'ws-form-user'));

					return true;

				case 'signon' :

					// Build credentials
					$creds = array();
					$creds['user_login'] = isset($api_fields['username']) ? $api_fields['username'] : '';
					$creds['user_password'] = isset($api_fields['password']) ? $api_fields['password'] : '';
					$creds['remember'] = $remember_me;

					// WordPress sign on
					$user = wp_signon($creds, $this->secure_cookie);

					// Error management
					if(is_wp_error($user)) {

						foreach($user->errors as $error_id => $error) {

							switch($error_id) {

								case 'empty_username' :

									$error_message = __('Empty username', 'ws-form-user');
									break;

								case 'invalid_username' :

									$error_message = __('Invalid username', 'ws-form-user');
									break;

								case 'empty_password' :

									$error_message = __('Empty password', 'ws-form-user');
									break;

								case 'incorrect_password' :

									$error_message = __('Incorrect password', 'ws-form-user');
									break;

								case 'invalid_email' :

									$error_message = __('Invalid email address', 'ws-form-user');
									break;

								default :

									$error_message = sprintf(

										/* translators: %s = Error ID */
										__('Unknown error: %s', 'ws-form-user'),
										$error_id
									);
							}

							// Apply filters
							$error_message = apply_filters(

								'wsf_action_' . $this->id . '_signon_error',

								$error_message,

								$error_id, 

								$form,

								$submit,

								$config
							);

							// Show the message
							self::error_js($error_message);
						}

						return 'halt';
					}

					// Set current user
					wp_set_current_user($user->ID);

					// Save user ID to submission
					$submit->user_id = $user->ID;

					// Do action
					do_action('wsf_action_' . $this->id . '_signon', $form, $submit, $config);

					// Success
					parent::success(__('User sign on successful!', 'ws-form-user'));

					return true;

				case 'lostpassword' :

					// Build credentials
					$user_login = isset($api_fields['username']) ? $api_fields['username'] : '';

					// Get user account
					if(($user = get_user_by('login', $user_login)) === false) {

						$user = get_user_by('email', $user_login);
					}

					// User not found
					if($user === false) { self::error_js(__('The username or email address specified cannot not be found.', 'ws-form-user')); return 'halt'; }

					// Set lost password key
					$user->lost_password_key = ($get_password_reset_key_return = get_password_reset_key($user));

					// Expose user data
					$GLOBALS['ws_form_user'] = $user;

					// Error management
					if(is_wp_error($get_password_reset_key_return)) {

						foreach($get_password_reset_key_return->errors as $error_id => $error) {

							switch($error_id) {

								case 'no_password_reset' :

									$error_message = __('Password reset is not allowed for this user.', 'ws-form-user');
									break;

								case 'no_password_key_update' :

									$error_message = __('Could not save password reset key to database.', 'ws-form-user');
									break;

								default :

									$error_message = sprintf(

										/* translators: %s = Error ID */
										__('Unknown error: %s', 'ws-form-user'),
										$error_id
									);
							}

							// Apply filter
							$error_message = apply_filters(

								'wsf_action_' . $this->id . '_lostpassword_error',

								$error_message,

								$error_id, 

								$form,

								$submit,

								$config
							);

							// Show the message
							self::error_js($error_message);
						}

						return 'halt';
					}

					// Save user ID to submission
					$submit->user_id = $user->ID;

					// Do action
					do_action('wsf_action_' . $this->id . '_lostpassword', $form, $submit, $config);

					// Success
					parent::success(__('User password key update successful!', 'ws-form-user'));

					return true;

				case 'resetpassword' :

					// Read login
					$rp_login = isset($api_fields['rp_login']) ? $api_fields['rp_login'] : '';
					if(empty($rp_login)) { self::error_js(__('Login not specified', 'ws-form-user')); return 'halt'; }

					// Read key
					$rp_key = isset($api_fields['rp_key']) ? $api_fields['rp_key'] : '';
					if(empty($rp_key)) { self::error_js(__('Key not specified', 'ws-form-user')); return 'halt'; }

					// Read password
					$pass1 = isset($api_fields['pass1']) ? $api_fields['pass1'] : '';
					if(empty($pass1)) { self::error_js(__('Password not specified', 'ws-form-user')); return 'halt'; }

					// Check rp_key
					$user = check_password_reset_key($rp_key, $rp_login);
					if(!$user || is_wp_error($user)) { self::error_js(__('Invalid password reset request.', 'ws-form-user')); return 'halt'; }

					// Reset password
					reset_password($user, $pass1);

					// Do action
					do_action('wsf_action_' . $this->id . '_resetpassword', $form, $submit, $config);

					// Success
					parent::success(__('User password reset successful!', 'ws-form-user'));

					return true;

				case 'logout' :

					// Logout
					wp_logout();

					// Do action
					do_action('wsf_action_' . $this->id . '_logout', $form, $submit, $config);

					// Success
					parent::success(__('User successfully logged out!', 'ws-form-user'));

					return true;
			}
		}

		// Attachment mapping processing
		public function post_process($attachment_mapping, $submit, $acf_update_fields, $acf_file_fields, $meta_box_update_fields, $meta_box_file_fields, $pods_update_fields, $pods_file_fields, $toolset_update_fields, $toolset_field_type_lookup, $toolset_file_fields, $jetengine_update_fields, $jetengine_file_fields, $user_id) {

			// Process attachment mapping
			$files = array();
			foreach($attachment_mapping as $attachment_map) {

				$field_id = $attachment_map['ws_form_field'];

				// Get submit value
				$get_submit_value_repeatable_return = parent::get_submit_value_repeatable($submit, WS_FORM_FIELD_PREFIX . $field_id, array(), true);

				if(
					!is_array($get_submit_value_repeatable_return) ||
					!is_array($get_submit_value_repeatable_return['value']) ||
					!isset($get_submit_value_repeatable_return['value'][0])
				) { continue; }

				// Repeatable?
				$repeatable = isset($get_submit_value_repeatable_return['repeatable']) ? $get_submit_value_repeatable_return['repeatable'] : false;

				// Add each value
				foreach($get_submit_value_repeatable_return['value'] as $repeater_index => $meta_value) {

					$file_objects = $get_submit_value_repeatable_return['value'][$repeater_index];
					if(!is_array($meta_value)) { continue; }

					foreach($file_objects as $file_object) {

						// Check submit file_object data
						if(
							!isset($file_object['name']) ||
							!isset($file_object['type']) ||
							!isset($file_object['size']) ||
							!isset($file_object['path'])

						) { continue; }

						// Get handler
						$handler = isset($file_object['handler']) ? $file_object['handler'] : 'wsform';
						if(!isset(WS_Form_File_Handler_WS_Form::$file_handlers[$handler])) { continue; }

						// Get file path
						$file_url = WS_Form_File_Handler_WS_Form::$file_handlers[$handler]->get_url($file_object);

						if($handler === 'attachment') {

							if(!isset($file_object['attachment_id'])) { continue; }
							$attachment_id = intval($file_object['attachment_id']);
							if(!$attachment_id) { continue; }

							// Build file array
							$file_single = array(

								'attachment_id'			=>	$attachment_id,
								'field_id'				=>	$field_id,
								'repeatable'			=>	$repeatable,
								'repeater_index'		=>	$repeater_index,
								'file_url'				=>	$file_url
							);
							$files[] = $file_single;

						} else {

							// Get temporary file
							$tmp_name = WS_Form_File_Handler_WS_Form::$file_handlers[$handler]->copy_to_temp_file($file_object);
							if($tmp_name === false) { continue;}

							// Build file array
							$file_single = array(

								'name'					=>	$file_object['name'],
								'type'					=>	$file_object['type'],
								'tmp_name'				=>	$tmp_name,
								'error'					=>	0,
								'size'					=>	$file_object['size'],
								'field_id'				=>	$field_id,
								'repeatable'			=>	$repeatable,
								'repeater_index'		=>	$repeater_index,
								'file_url'				=>	$file_url
							);
							$files[] = $file_single;
						}
					}
				}
			}

			// Process files
			if(count($files) > 0) {

				// ACF assignment
				if($this->acf_activated) {

					$acf_attachments = array();
				}

				// Meta Box assignment
				if($this->meta_box_activated) {

					$meta_box_attachments = array();
				}

				// Pods assignment
				if($this->pods_activated) {

					$pods_attachments = array();
				}

				// Toolset assignment
				if($this->toolset_activated) {

					$toolset_attachments = array();
				}

				// JetEngine assignment
				if($this->jetengine_activated) {

					$jetengine_attachments = array();
				}

				foreach($files as $file) {

					if(isset($file['attachment_id'])) {

						$attachment_id = $file['attachment_id'];

					} else {

						// Need to require these files
						if(!function_exists('media_handle_upload')) {

							require_once(ABSPATH . "wp-admin" . '/includes/image.php');
							require_once(ABSPATH . "wp-admin" . '/includes/file.php');
							require_once(ABSPATH . "wp-admin" . '/includes/media.php');
						}

						$attachment_id = media_handle_sideload($file);

						// Error management
						if(is_wp_error($attachment_id)) {

							self::wp_error_process($attachment_id);
							return 'halt';
						}
					}

					// ACF assignment
					if($this->acf_activated) {

						$field_id = $file['field_id'];
						$repeatable = $file['repeatable'];
						$repeater_index = $file['repeater_index'];

						if(isset($acf_file_fields[$field_id])) {

							// Get ACF key
							$acf_key = $acf_file_fields[$field_id];

							// Get ACF attachments key (Used to group attachment ID's together)
							$acf_attachments_key = $acf_key . ($repeatable ? '_' . $repeater_index : '');

							if(!isset($acf_attachments[$acf_attachments_key])) {

								$acf_attachments[$acf_attachments_key] = array(

									'acf_key' => $acf_key,
									'meta_value_array' => array(),
									'repeatable' => $repeatable,
									'repeater_index' => $repeater_index
								);
							}

							$acf_attachments[$acf_attachments_key]['meta_value_array'][] = $attachment_id;
						}
					}

					// Meta Box assignment
					if($this->meta_box_activated) {

						$field_id = $file['field_id'];
						$repeatable = $file['repeatable'];
						$repeater_index = $file['repeater_index'];

						if(isset($meta_box_file_fields[$field_id])) {

							// Get Meta Box field ID
							$meta_box_field_id = $meta_box_file_fields[$field_id];

							// Get parent Meta Box field type
							$meta_box_parent_data = WS_Form_Meta_Box::meta_box_get_parent_data($meta_box_field_id);
							$meta_box_parent_field_id = isset($meta_box_parent_data['field_id']) ? $meta_box_parent_data['field_id'] : false;
							$meta_box_parent_repeater = isset($meta_box_parent_data['repeater']) ? $meta_box_parent_data['repeater'] : false;

							// Get ACF attachments key (Used to group attachment ID's together)
							$meta_box_attachments_key = $meta_box_field_id . ($repeatable ? '_' . $repeater_index : '');

							if(!isset($meta_box_attachments[$meta_box_attachments_key])) {

								$meta_box_attachments[$meta_box_attachments_key] = array(

									'parent_field_id' => $meta_box_parent_field_id,
									'parent_repeater' => $meta_box_parent_repeater,
									'field_id' => $meta_box_field_id,
									'meta_value_array' => array(),
									'repeatable' => $repeatable,
									'repeater_index' => $repeater_index
								);
							}

							$meta_box_attachments[$meta_box_attachments_key]['meta_value_array'][] = strval($attachment_id);
						}
					}

					// Pods assignment
					if($this->pods_activated) {

						// Build pods ID to name lookup
						$pods_id_to_name_lookup = WS_Form_Pods::pods_get_id_to_name_lookup('user');

						$field_id = $file['field_id'];

						if(isset($pods_file_fields[$field_id])) {

							// Get Pods field ID
							$pods_field_id = $pods_file_fields[$field_id];

							// Get Pods field name
							if(!isset($pods_id_to_name_lookup[$pods_field_id])) { continue; }
							$pods_field_name = $pods_id_to_name_lookup[$pods_field_id];

							if(!isset($pods_attachments[$pods_field_id])) {

								$pods_attachments[$pods_field_id] = array(

									'field_id' => $pods_field_id,
									'meta_value_array' => array()
								);
							}

							$pods_attachments[$pods_field_id]['meta_value_array'][] = $attachment_id;
						}
					}

					// Toolset assignment
					if($this->toolset_activated) {

						$field_id = $file['field_id'];

						if(isset($toolset_file_fields[$field_id])) {

							// Get Toolset field ID
							$toolset_field_slug = $toolset_file_fields[$field_id];

							if(!isset($toolset_attachments[$toolset_field_slug])) {

								$toolset_attachments[$toolset_field_slug] = array(

									'field_id' => $toolset_field_slug,
									'meta_value_array' => array()
								);
							}

							$toolset_attachments[$toolset_field_slug]['meta_value_array'][] = $file['file_url'];
						}
					}

					// JetEngine assignment
					if($this->jetengine_activated) {

						$field_id = $file['field_id'];
						$repeatable = $file['repeatable'];
						$repeater_index = $file['repeater_index'];

						if(isset($jetengine_file_fields[$field_id])) {

							// Get JetEngine file field
							$jetengine_file_field = $jetengine_file_fields[$field_id];

							$jetengine_field_name = $jetengine_file_field['jetengine_field_name'];
							$jetengine_parent_field_type = $jetengine_file_field['jetengine_parent_field_type'];
							$jetengine_parent_field_jetengine_field_name = $jetengine_file_field['jetengine_parent_field_jetengine_field_name'];

							// Get JetEngine attachments name (Used to group attachment ID's together)
							$jetengine_attachments_name = $jetengine_field_name . ($repeatable ? '_' . $repeater_index : '');

							if(!isset($jetengine_attachments[$jetengine_attachments_name])) {

								$jetengine_attachments[$jetengine_attachments_name] = array(

									'jetengine_field_name' => $jetengine_field_name,
									'jetengine_parent_field_type' => $jetengine_parent_field_type,
									'jetengine_parent_field_jetengine_field_name' => $jetengine_parent_field_jetengine_field_name,
									'meta_value_array' => array(),
									'repeatable' => $repeatable,
									'repeater_index' => $repeater_index
								);
							}

							$jetengine_field_settings = WS_Form_JetEngine::jetengine_get_field_settings($jetengine_field_name, 'user');

							$value_format = isset($jetengine_field_settings['value_format']) ? $jetengine_field_settings['value_format'] : 'id';

							switch($value_format) {

								case 'id' :

									$jetengine_attachments[$jetengine_attachments_name]['meta_value_array'][] = $attachment_id;
									break;

								case 'url' :

									$jetengine_attachments[$jetengine_attachments_name]['meta_value_array'][] = $file['file_url'];
									break;

								case 'both' :

									$jetengine_attachments[$jetengine_attachments_name]['meta_value_array'][] = array(

										'id' => $attachment_id,
										'url' => $file['file_url']
									);
									break;
							}
						}
					}
				}

				// ACF assignment
				if($this->acf_activated && (count($acf_attachments) > 0)) {

					foreach($acf_attachments as $acf_attachment) {

						$acf_key = $acf_attachment['acf_key'];
						$meta_value_array = $acf_attachment['meta_value_array'];
						$repeatable = $acf_attachment['repeatable'];
						$repeater_index = $acf_attachment['repeater_index'];

						// Get parent ACF field type
						$acf_data = WS_Form_ACF::acf_get_parent_data($acf_key);
						$acf_parent_field_type = isset($acf_data['type']) ? $acf_data['type'] : false;
						$acf_parent_field_acf_key = isset($acf_data['acf_key']) ? $acf_data['acf_key'] : false;

						$meta_value = (count($meta_value_array) == 1) ? $meta_value_array[0] : $meta_value_array;

						// Add to fields to update
						if($repeatable) {

							if(!isset($acf_update_fields[$acf_parent_field_acf_key])) {

								$acf_update_fields[$acf_parent_field_acf_key] = array();
							}

							if(!isset($acf_update_fields[$acf_parent_field_acf_key][$repeater_index])) {

								$acf_update_fields[$acf_parent_field_acf_key][$repeater_index] = array();
							}

							$acf_update_fields[$acf_parent_field_acf_key][$repeater_index][$acf_key] = $meta_value;


						} else {

							$acf_update_fields[$acf_key] = $meta_value;
						}
					}
				}

				// Meta box assignment
				if($this->meta_box_activated && (count($meta_box_attachments) > 0)) {

					foreach($meta_box_attachments as $meta_box_attachment) {

						$meta_box_parent_field_id = $meta_box_attachment['parent_field_id'];
						$meta_box_parent_repeater = $meta_box_attachment['parent_repeater'];
						$meta_box_field_id = $meta_box_attachment['field_id'];
						$meta_value_array = $meta_box_attachment['meta_value_array'];
						$repeatable = $meta_box_attachment['repeatable'];
						$repeater_index = $meta_box_attachment['repeater_index'];

						// Get Meta Box field type
						$meta_box_field_type = WS_Form_Meta_Box::meta_box_get_field_type($meta_box_field_id);
						if($meta_box_field_type === false) { continue; }

						// Field type data formatting
						switch($meta_box_field_type) {

							// Single image is not stored as an array
							case 'single_image' :

								$meta_value = $meta_value_array[0];
								break;

							default :

								$meta_value = $meta_value_array;
						}

						// Add to fields to update
						if($meta_box_parent_field_id !== false) {

							if(!isset($meta_box_update_fields[$meta_box_parent_field_id])) {

								$meta_box_update_fields[$meta_box_parent_field_id] = array();
							}

							if($meta_box_parent_repeater) {

								if(!isset($meta_box_update_fields[$meta_box_parent_field_id][$repeater_index])) {

									$meta_box_update_fields[$meta_box_parent_field_id][$repeater_index] = array();
								}

								$meta_box_update_fields[$meta_box_parent_field_id][$repeater_index][$meta_box_field_id] = $meta_value;

							} else {

								if(!isset($meta_box_update_fields[$meta_box_parent_field_id])) {

									$meta_box_update_fields[$meta_box_parent_field_id] = array();
								}

								$meta_box_update_fields[$meta_box_parent_field_id][$meta_box_field_id] = $meta_value;
							}

						} else {

							$meta_box_update_fields[$meta_box_field_id] = $meta_value;
						}
					}
				}

				// Pods assignment
				if($this->pods_activated && (count($pods_attachments) > 0)) {

					// Build pods ID to name lookup
					$pods_id_to_name_lookup = WS_Form_Pods::pods_get_id_to_name_lookup('user');

					foreach($pods_attachments as $pods_attachment) {

						// Get Pods field ID
						$pods_field_id = $pods_attachment['field_id'];

						// Get Pods field name
						if(!isset($pods_id_to_name_lookup[$pods_field_id])) { continue; }
						$pods_field_name = $pods_id_to_name_lookup[$pods_field_id];

						$meta_value_array = $pods_attachment['meta_value_array'];

						$meta_value = (count($meta_value_array) == 1) ? $meta_value_array[0] : $meta_value_array;

						$pods_update_fields[$pods_field_name] = $meta_value;
					}
				}

				// Toolset assignment
				if($this->toolset_activated && (count($toolset_attachments) > 0)) {

					foreach($toolset_attachments as $toolset_attachment) {

						// Get Toolset field ID
						$toolset_field_slug = $toolset_attachment['field_id'];

						$meta_value_array = $toolset_attachment['meta_value_array'];

						$meta_value = (count($meta_value_array) == 1) ? $meta_value_array[0] : $meta_value_array;

						$toolset_update_fields[$toolset_field_slug] = $meta_value;
					}
				}

				// JetEngine assignment
				if($this->jetengine_activated && (count($jetengine_attachments) > 0)) {

					foreach($jetengine_attachments as $jetengine_attachment) {

						$jetengine_field_name = $jetengine_attachment['jetengine_field_name'];
						$jetengine_parent_field_jetengine_field_name = $jetengine_attachment['jetengine_parent_field_jetengine_field_name'];
						$jetengine_parent_field_type = $jetengine_attachment['jetengine_parent_field_type'];
						$meta_value_array = $jetengine_attachment['meta_value_array'];
						$repeatable = $jetengine_attachment['repeatable'];
						$repeater_index = $jetengine_attachment['repeater_index'];

						// Get parent JetEngine field type
						$jetengine_data = WS_Form_JetEngine::jetengine_get_parent_data($jetengine_field_name, 'user');
						$jetengine_parent_field_type = isset($jetengine_data['type']) ? $jetengine_data['type'] : false;
						$jetengine_parent_field_jetengine_field_name = isset($jetengine_data['name']) ? $jetengine_data['name'] : false;

						// Format according to field type and value_format
						$jetengine_field_settings = WS_Form_JetEngine::jetengine_get_field_settings($jetengine_field_name, 'user');

						$jetengine_field_type = isset($jetengine_field_settings['type']) ? $jetengine_field_settings['type'] : 'media';
						$jetengine_field_value_format = isset($jetengine_field_settings['value_format']) ? $jetengine_field_settings['value_format'] : 'id';

						// Determine meta value
						$meta_value = array();
						
						switch($jetengine_field_value_format) {

							case 'id' :
							case 'url' :

								$meta_value = implode(',', $meta_value_array);
								break;

							case 'both' :

								switch($jetengine_field_type) {
									
									case 'media' :
										
										$meta_value = isset($meta_value_array[0]) ? $meta_value_array[0] : array();
										break;
										
									case 'gallery' :
										
										$meta_value = $meta_value_array;
										break;
								}
								break;
						}

						// Add to fields to update
						switch($jetengine_parent_field_type) {

							case 'repeater' :

								$item_name = sprintf('item-%u', $repeater_index);

								if(!isset($jetengine_update_fields[$jetengine_parent_field_jetengine_field_name])) {

									$jetengine_update_fields[$jetengine_parent_field_jetengine_field_name] = array();
								}

								if(!isset($jetengine_update_fields[$jetengine_parent_field_jetengine_field_name][$item_name])) {

									$jetengine_update_fields[$jetengine_parent_field_jetengine_field_name][$item_name] = array();
								}

								$jetengine_update_fields[$jetengine_parent_field_jetengine_field_name][$item_name][$jetengine_field_name] = $meta_value;

								break;

							default :

								$jetengine_update_fields[$jetengine_field_name] = $meta_value;
						}
					}
				}
			}

			// Process ACF fields
			if($this->acf_activated) {

				// Add slashes
				$acf_update_fields = wp_slash($acf_update_fields);

				// Update fields
				foreach($acf_update_fields as $acf_key => $meta_value) {

					update_field($acf_key, $meta_value, sprintf('user_%u', $user_id));
				}
			}

			// Process Meta Box fields
			if($this->meta_box_activated) {

				// Add slashes
				$meta_box_update_fields = wp_slash($meta_box_update_fields);

				// Update fields
				foreach($meta_box_update_fields as $meta_box_field_id => $meta_value) {

					rwmb_set_meta($user_id, $meta_box_field_id, $meta_value, ['object_type' => 'user']);
				}
			}

			// Process Pods fields
			if($this->pods_activated) {

				// Update fields
				$pods = pods('user', $user_id);
				$pods->save($pods_update_fields);
			}

			// Process Toolset fields
			if($this->toolset_activated) {

				// Add slashes
				$toolset_update_fields = wp_slash($toolset_update_fields);

				WS_Form_Toolset::toolset_update_meta($user_id, $toolset_update_fields, $toolset_field_type_lookup, 'wpcf-usermeta');
			}

			// Process JetEngine fields
			if($this->jetengine_activated) {

				// Add slashes
				$jetengine_update_fields = wp_slash($jetengine_update_fields);

				// Update fields
				foreach($jetengine_update_fields as $meta_key => $meta_value) {

					update_user_meta($user_id, $meta_key, $meta_value);
				}

				// Run through each mapping
				foreach($this->jetengine_relations as $jetengine_relation) {

					// Get JetEngine relation ID
					$jetengine_relation_id = $jetengine_relation['action_' . $this->id . '_jetengine_relation_id'];
					if(empty($jetengine_relation_id)) { continue; }

					// Get relation instance
					$relation_instance = jet_engine()->relations->get_active_relations($jetengine_relation_id);
					if($relation_instance === false) { continue; }

					// Get JetEngine relation context
					$jetengine_relation_context = $jetengine_relation['action_' . $this->id . '_jetengine_relation_context'];
					if(empty($jetengine_relation_context)) { continue; }
					if(!in_array($jetengine_relation_context, array('child', 'parent'))) { continue; }

					// Set context
					$relation_instance->set_update_context($jetengine_relation_context);

					// Get JetEngine relation field ID
					$field_id = $jetengine_relation['ws_form_field'];
					$submit_value = parent::get_submit_value($submit, WS_FORM_FIELD_PREFIX . $field_id, false, true);
					if($submit_value === false) { continue; }

					// Get JetEngine relation replace
					$jetengine_relation_replace = ($jetengine_relation['action_' . $this->id . '_jetengine_relation_replace'] == 'on');

					// Convert to array
					if(!is_array($submit_value)) { $submit_value = implode(',', $submit_value); }

					// Process according to context
					switch($jetengine_relation_context) {

						case 'parent' :

							// Use user ID as the item ID
							$child_id = $user_id;

							// Delete existing relation
							if($jetengine_relation_replace) {

								$relation_instance->delete_rows(false, $child_id);
							}

							// The submitted values are the parent ID
							foreach($submit_value as $parent_id) {

								$parent_id = absint($parent_id);
								if($parent_id === 0) { continue; }

								// Update relation
								$relation_instance->update($parent_id, $child_id);
							}

							break;

						case 'child' :

							// Use user ID as the parent ID
							$parent_id = $user_id;

							// Delete existing relations
							if($jetengine_relation_replace) {

								$relation_instance->delete_rows($parent_id);
							}

							// The submitted values are the child ID
							foreach($submit_value as $child_id) {

								$child_id = absint($child_id);
								if($child_id === 0) { continue; }

								$relation_instance->update($parent_id, $child_id);
							}

							break;
					}
				}
			}
		}

		// Update user meta
		public function update_user_meta_do($user_id, $meta_keys) {

			// Add slashes
			$meta_keys = wp_slash($meta_keys);

			foreach($meta_keys as $meta_key => $meta_value) {

				update_user_meta($user_id, $meta_key, $meta_value);
			}
		}

		// Get user data
		public function get($form = false, $current_user = false) {

			// Check action is configured properly
			if(!self::check_configured()) { return false; }

			if(!$current_user) { return false; }

			$current_user_id = $current_user->ID;

			$user_data = array(

				'user_id' 					=>	$current_user_id,
				'user_login' 				=>	($current_user_id > 0) ? $current_user->user_login : '',
				'user_nicename' 			=>	($current_user_id > 0) ? $current_user->user_nicename : '',
				'user_email' 				=>	($current_user_id > 0) ? $current_user->user_email : '',
				'user_display_name'			=>	($current_user_id > 0) ? $current_user->display_name : '',
				'user_url' 					=>	($current_user_id > 0) ? $current_user->user_url : '',
				'user_registered' 			=>	($current_user_id > 0) ? $current_user->user_registered : '',
				'user_first_name'			=>	($current_user_id > 0) ? get_user_meta($current_user_id, 'first_name', true) : '',
				'user_last_name'			=>	($current_user_id > 0) ? get_user_meta($current_user_id, 'last_name', true) : '',
				'user_description'			=>	($current_user_id > 0) ? get_user_meta($current_user_id, 'description', true) : '',
				'user_nickname' 			=>	($current_user_id > 0) ? get_user_meta($current_user_id, 'nickname', true) : '',
				'user_rich_editing'			=>	($current_user_id > 0) ? get_user_meta($current_user_id, 'rich_editing', true) : '',
				'user_syntax_highlighting'	=>	($current_user_id > 0) ? get_user_meta($current_user_id, 'syntax_highlighting', true) : '',
				'user_comment_shortcuts'	=>	($current_user_id > 0) ? get_user_meta($current_user_id, 'comment_shortcuts', true) : '',
				'user_show_admin_bar_front'	=>	($current_user_id > 0) ? get_user_meta($current_user_id, 'show_admin_bar_front', true) : '',
				'user_admin_color' 			=>	($current_user_id > 0) ? get_user_meta($current_user_id, 'admin_color', true) : self::WS_FORM_ADMIN_COLOR_DEFAULT
			);

			// Checkbox formatting
			$user_data['user_rich_editing'] = ($user_data['user_rich_editing'] == 'true') ? '' : 'on';
			$user_data['user_syntax_highlighting'] = ($user_data['user_syntax_highlighting'] == 'true') ? '' : 'on';
			$user_data['user_comment_shortcuts'] = ($user_data['user_comment_shortcuts'] == 'true') ? 'on' : '';
			$user_data['user_show_admin_bar_front'] = ($user_data['user_show_admin_bar_front'] == 'true') ? 'on' : '';

			$fields_return = array();

			// Process WooCommerce
			if($this->woocommerce_activated) {

				$woocommerce_fields = WS_Form_WooCommerce::woocommerce_get_fields_all('user', false, false, true, false, false);
			}

			// Field mapping
			$field_mapping = WS_Form_Common::get_object_meta_value($form, 'action_' . $this->id . '_form_populate_field_mapping', '');
			if(is_array($field_mapping) && ($current_user_id > 0)) {

				foreach($field_mapping as $field_map) {

					$meta_key = $field_map->{'action_' . $this->id . '_form_populate_field'};
					$field_id = $field_map->ws_form_field;

					$meta_value = false;

					if(
						$this->woocommerce_activated &&
						isset($woocommerce_fields[$meta_key])
					) {

						$meta_value = get_user_meta($current_user_id, $meta_key, false);
					}

					if($meta_value === false) {

						$meta_value = isset($user_data[$meta_key]) ? $user_data[$meta_key] : '';
					}

					// User meta data is already HTML encoded. Population of data is HTML encoded, so strip HTML encoding here to avoid doubling up of encoding.
					if(is_string($meta_value)) {

						$meta_value = html_entity_decode($meta_value);
					}

					$fields_return[$field_id] = $meta_value;
				}
			}
			$fields_repeatable_return = array();
			$section_repeatable_return = array();

			// ACF field mapping
			if($this->acf_activated) {

				// Get first option value so we can use that to set the value
				$fields = WS_Form_Common::get_fields_from_form($form);

				// Get field types
	 			$field_types = WS_Form_Config::get_field_types_flat();

				// Get ACF field mappings
				$field_mapping_acf = WS_Form_Common::get_object_meta_value($form, 'action_' . $this->id . '_form_populate_field_mapping_acf', '');
				if(is_array($field_mapping_acf)) {

					// Get ACF field values for current user
					$acf_field_data = WS_Form_ACF::acf_get_field_data('user_' . $current_user_id);

					// Run through each mapping
					foreach($field_mapping_acf as $field_map_acf) {

						// Get ACF field key
						$acf_key = $field_map_acf->{'action_' . $this->id . '_acf_key'};

						// Get field ID
						$field_id = $field_map_acf->ws_form_field;

						// Get meta value
						if(!isset($acf_field_data[$acf_key])) { continue; }

						// Read ACF field data
						$acf_field = $acf_field_data[$acf_key];
						$acf_field_repeater = $acf_field['repeater'];
						$acf_field_values = $acf_field['values'];

						// Get ACF field type
						$acf_field_type = WS_Form_ACF::acf_get_field_type($acf_key);
						if($acf_field_type === false) { continue; }

						// Process acf_field_values
						$acf_field_values = WS_Form_ACF::acf_acf_meta_value_to_ws_form_field_value($acf_field_values, $acf_field_type, $acf_field_repeater, $field_id, $fields, $field_types);

						// Set value
						if($acf_field_repeater) {

							// Build section_repeatable_return
							if(
								isset($fields[$field_id]) &&
								isset($fields[$field_id]->section_repeatable) &&
								$fields[$field_id]->section_repeatable &&
								isset($fields[$field_id]->section_id) &&
								is_array($acf_field_values)
							) {

								$section_id = $fields[$field_id]->section_id;
								$section_count = (isset($section_repeatable_return['section_' . $section_id]) && isset($section_repeatable_return['section_' . $section_id]['index'])) ? count($section_repeatable_return['section_' . $section_id]['index']) : 1;
								if(count($acf_field_values) > $section_count) { $section_count = count($acf_field_values); }
								$section_repeatable_return['section_' . $section_id] = array('index' => range(1, $section_count));
							}

							// Build fields_repeatable_return
							$fields_repeatable_return[$field_id] = $acf_field_values;

						} else {

							// Build fields_return
							$fields_return[$field_id] = $acf_field_values;
						}
					}
				}
			}

			// Meta Box field mapping
			if($this->meta_box_activated) {

				// Get first option value so we can use that to set the value
				$fields = WS_Form_Common::get_fields_from_form($form);

				// Get field types
	 			$field_types = WS_Form_Config::get_field_types_flat();

				// Get Meta Box field mappings
				$field_mapping_meta_box = WS_Form_Common::get_object_meta_value($form, 'action_' . $this->id . '_form_populate_field_mapping_meta_box', '');
				if(is_array($field_mapping_meta_box)) {

					// Get Meta Box field values for current user
					$meta_box_field_data = WS_Form_Meta_Box::meta_box_get_field_data('user', false, $current_user_id);

					// Run through each mapping
					foreach($field_mapping_meta_box as $field_map_meta_box) {

						// Get Meta Box field key
						$meta_box_field_id = $field_map_meta_box->{'action_' . $this->id . '_meta_box_field_id'};

						// Get field ID
						$field_id = $field_map_meta_box->ws_form_field;

						// Get meta value
						if(!isset($meta_box_field_data[$meta_box_field_id])) { continue; }

						// Read Meta Box field data
						$meta_box_field = $meta_box_field_data[$meta_box_field_id];
						$meta_box_field_repeater = $meta_box_field['repeater'];
						$meta_box_field_values = $meta_box_field['values'];

						// Get Meta Box field type
						$meta_box_field_type = WS_Form_Meta_Box::meta_box_get_field_type($meta_box_field_id);
						if($meta_box_field_type === false) { continue; }

						// Process meta_box_field_values
						$meta_box_field_values = WS_Form_Meta_Box::meta_box_meta_box_meta_value_to_ws_form_field_value($meta_box_field_values, $meta_box_field_type, $meta_box_field_repeater, $field_id, $fields, $field_types);

						// Set value
						if($meta_box_field_repeater) {

							// Build section_repeatable_return
							if(
								isset($fields[$field_id]) &&
								isset($fields[$field_id]->section_repeatable) &&
								$fields[$field_id]->section_repeatable &&
								isset($fields[$field_id]->section_id) &&
								is_array($meta_box_field_values)
							) {

								$section_id = $fields[$field_id]->section_id;
								$section_count = (isset($section_repeatable_return['section_' . $section_id]) && isset($section_repeatable_return['section_' . $section_id]['index'])) ? count($section_repeatable_return['section_' . $section_id]['index']) : 1;
								if(count($meta_box_field_values) > $section_count) { $section_count = count($meta_box_field_values); }
								$section_repeatable_return['section_' . $section_id] = array('index' => range(1, $section_count));
							}

							// Build fields_repeatable_return
							$fields_repeatable_return[$field_id] = $meta_box_field_values;

						} else {

							// Build fields_return
							$fields_return[$field_id] = $meta_box_field_values;
						}
					}
				}
			}

			// Pods field mapping
			if($this->pods_activated) {

				// Get first option value so we can use that to set the value
				$fields = WS_Form_Common::get_fields_from_form($form);

				// Get field types
	 			$field_types = WS_Form_Config::get_field_types_flat();

				// Get Pods field mappings
				$field_mapping_pods = WS_Form_Common::get_object_meta_value($form, 'action_' . $this->id . '_form_populate_field_mapping_pods', '');
				if(is_array($field_mapping_pods)) {

					// Get Pods field values for current user
					$pods_field_data = WS_Form_Pods::pods_get_field_data('user', false, $current_user_id);

					// Run through each mapping
					foreach($field_mapping_pods as $field_map_pods) {

						// Get Pods field key
						$pods_field_id = $field_map_pods->{'action_' . $this->id . '_pods_field_id'};

						// Get field ID
						$field_id = $field_map_pods->ws_form_field;

						// Get meta value
						if(!isset($pods_field_data[$pods_field_id])) { continue; }

						// Read Pods field data
						$pods_field = $pods_field_data[$pods_field_id];
						$pods_field_values = $pods_field['values'];

						// Get Pods field type
						$pods_field_type = WS_Form_Pods::pods_get_field_type($pods_field_id);
						if($pods_field_type === false) { continue; }

						// Process pods_field_values
						$pods_field_values = WS_Form_Pods::pods_pods_meta_value_to_ws_form_field_value($pods_field_values, $pods_field_type, $field_id, $fields, $field_types);

						// Set value
						$fields_return[$field_id] = $pods_field_values;
					}
				}
			}

			// Toolset field mapping
			if($this->toolset_activated) {

				// Get first option value so we can use that to set the value
				$fields = WS_Form_Common::get_fields_from_form($form);

				// Get field types
	 			$field_types = WS_Form_Config::get_field_types_flat();

				// Get Toolset field mappings
				$field_mapping_toolset = WS_Form_Common::get_object_meta_value($form, 'action_' . $this->id . '_form_populate_field_mapping_toolset', '');
				if(is_array($field_mapping_toolset)) {

					// Get Toolset field values for current user
					$toolset_field_data = WS_Form_Toolset::toolset_get_field_data(array('domain' => Toolset_Element_Domain::USERS), $current_user_id);

					// Run through each mapping
					foreach($field_mapping_toolset as $field_map_toolset) {

						// Get Toolset field slug
						$toolset_field_slug = $field_map_toolset->{'action_' . $this->id . '_toolset_field_slug'};

						// Get field ID
						$field_id = $field_map_toolset->ws_form_field;

						// Get meta value
						if(!isset($toolset_field_data[$toolset_field_slug])) { continue; }

						// Read Toolset field data
						$toolset_field = $toolset_field_data[$toolset_field_slug];
						$toolset_field_values = $toolset_field['values'];

						// Get Toolset field type
						$toolset_field_type = WS_Form_Toolset::toolset_get_field_type($toolset_field_slug, 'wpcf-usermeta');
						if($toolset_field_type === false) { continue; }

						// Process toolset_field_values
						$toolset_field_values = WS_Form_Toolset::toolset_toolset_meta_value_to_ws_form_field_value($toolset_field_values, $toolset_field_type, $field_id, $fields, $field_types, $current_user_id, $toolset_field_slug);

						// Build fields_return
						$fields_return[$field_id] = $toolset_field_values;
					}
				}
			}

			// JetEngine field mapping
			if($this->jetengine_activated) {

				// Get first option value so we can use that to set the value
				$fields = WS_Form_Common::get_fields_from_form($form);

				// Get field types
	 			$field_types = WS_Form_Config::get_field_types_flat();

				// Get JetEngine field mappings
				$field_mapping_jetengine = WS_Form_Common::get_object_meta_value($form, 'action_' . $this->id . '_form_populate_field_mapping_jetengine', '');
				if(is_array($field_mapping_jetengine)) {

					// Get JetEngine field values for current user
					$jetengine_field_data = WS_Form_JetEngine::jetengine_get_field_data('user', null, $current_user_id);

					// Run through each mapping
					foreach($field_mapping_jetengine as $field_map_jetengine) {

						// Get JetEngine field name
						$jetengine_field_name = $field_map_jetengine->{'action_' . $this->id . '_jetengine_field_name'};

						// Get field ID
						$field_id = $field_map_jetengine->ws_form_field;

						// Get meta value
						if(!isset($jetengine_field_data[$jetengine_field_name])) { continue; }

						// Read JetEngine field data
						$jetengine_field = $jetengine_field_data[$jetengine_field_name];
						$jetengine_field_repeater = $jetengine_field['repeater'];
						$jetengine_field_values = $jetengine_field['values'];

						// Get JetEngine field type
						$jetengine_field_type = WS_Form_JetEngine::jetengine_get_field_type($jetengine_field_name, 'user');
						if($jetengine_field_type === false) { continue; }

						// Process jetengine_field_values
						$jetengine_field_values = WS_Form_JetEngine::jetengine_jetengine_meta_value_to_ws_form_field_value($jetengine_field_values, $jetengine_field_type, $jetengine_field_repeater, $jetengine_field_name, $field_id, $fields, $field_types, 'user');

						// Set value
						if($jetengine_field_repeater) {

							// Build section_repeatable_return
							if(
								isset($fields[$field_id]) &&
								isset($fields[$field_id]->section_repeatable) &&
								$fields[$field_id]->section_repeatable &&
								isset($fields[$field_id]->section_id) &&
								is_array($jetengine_field_values)
							) {

								$section_id = $fields[$field_id]->section_id;
								$section_count = (isset($section_repeatable_return['section_' . $section_id]) && isset($section_repeatable_return['section_' . $section_id]['index'])) ? count($section_repeatable_return['section_' . $section_id]['index']) : 1;
								if(count($jetengine_field_values) > $section_count) { $section_count = count($jetengine_field_values); }
								$section_repeatable_return['section_' . $section_id] = array('index' => range(1, $section_count));
							}

							// Build fields_repeatable_return
							$fields_repeatable_return[$field_id] = $jetengine_field_values;

						} else {

							// Build fields_return
							$fields_return[$field_id] = $jetengine_field_values;
						}
					}
				}

				// Get JetEngine Relation mappings
				$jetengine_relations_populate = WS_Form_Common::get_object_meta_value($form, 'action_' . $this->id . '_jetengine_relations_populate', '');
				if(is_array($jetengine_relations_populate)) {

					// Run through each mapping
					foreach($jetengine_relations_populate as $jetengine_relation) {

						// Get JetEngine relation ID
						$jetengine_relation_id = $jetengine_relation->{'action_' . $this->id . '_jetengine_relation_id'};
						if(empty($jetengine_relation_id)) { continue; }

						// Get relation instance
						$relation_instance = jet_engine()->relations->get_active_relations($jetengine_relation_id);
						if($relation_instance === false) { continue; }

						// Get JetEngine relation context
						$jetengine_relation_context = $jetengine_relation->{'action_' . $this->id . '_jetengine_relation_context'};
						if(empty($jetengine_relation_context)) { continue; }
						if(!in_array($jetengine_relation_context, array('child', 'parent'))) { continue; }

						// Get JetEngine relation field ID
						$field_id = $jetengine_relation->{'ws_form_field'};

						// Get relation value
						switch($jetengine_relation_context) {

							case 'parent' :

								$jetengine_field_values = $relation_instance->get_parents($current_user_id, 'ids');
								break;

							case 'child' :

								$jetengine_field_values = $relation_instance->get_children($current_user_id, 'ids');
								break;
						}

						if(count($jetengine_field_values) == 0) { continue; }

						$fields_return[$field_id] = $jetengine_field_values;
					}
				}
			}

			// Meta key mapping
			$meta_mapping = WS_Form_Common::get_object_meta_value($form, 'action_' . $this->id . '_form_populate_meta_mapping', '');
			if(is_array($meta_mapping) && ($current_user_id > 0)) {

				foreach($meta_mapping as $meta_map) {

					$meta_key = $meta_map->{'action_' . $this->id . '_meta_key'};
					$field_id = $meta_map->ws_form_field;
					$meta_value = get_user_meta($current_user_id, $meta_key, true);
					$fields_return[$field_id] = $meta_value;
				}
			}

			$return_array = array('fields' => $fields_return, 'section_repeatable' => $section_repeatable_return, 'fields_repeatable' => $fields_repeatable_return, 'tags' => array());

			return $return_array;
		}

		// Get lists
		public function get_lists($fetch = false) {

			// Check action is configured properly
			if(!self::check_configured()) { return false; }

			$total_users = count_users()['total_users'];

			$lists = array(

				array(

					'id' => 			'register', 
					'label' => 			__('Register', 'ws-form-user'), 
					'field_count' => 	false,
					'record_count' => 	$total_users
				),

				array(

					'id' => 			'update', 
					'label' => 			__('Edit Profile', 'ws-form-user'), 
					'field_count' => 	false,
					'record_count' => 	$total_users
				),

				array(

					'id' => 			'signon', 
					'label' => 			__('Log In', 'ws-form-user'), 
					'field_count' => 	false,
					'record_count' => 	$total_users
				),

				array(

					'id' => 			'lostpassword', 
					'label' => 			__('Forgot Password', 'ws-form-user'), 
					'field_count' => 	false,
					'record_count' => 	$total_users
				),

				array(

					'id' => 			'resetpassword', 
					'label' => 			__('Reset Password', 'ws-form-user'), 
					'field_count' => 	false,
					'record_count' => 	$total_users
				),

				array(

					'id' => 			'logout', 
					'label' => 			__('Log Out', 'ws-form-user'), 
					'field_count' => 	false,
					'record_count' => 	$total_users
				)
			);

			return $lists;
		}

		// Get list
		public function get_list($fetch = false) {

			// Check action is configured properly
			if(!self::check_configured()) { return false; }

			// Check list ID is set
			if(!self::check_list_id()) { return false; }

			// Load configuration
			self::load_config();

			// Set label
			$label = '';
			switch($this->list_id) {

				case 'register' : 		$label = __('Register', 'ws-form-user'); break;
				case 'update' : 		$label = __('Edit Profile', 'ws-form-user'); break;
				case 'signon' : 		$label = __('Log In', 'ws-form-user'); break;
				case 'lostpassword' : 	$label = __('Forgot Password', 'ws-form-user'); break;
				case 'resetpassword' : 	$label = __('Reset Password', 'ws-form-user'); break;
				case 'logout' : 		$label = __('Log Out', 'ws-form-user'); break;
			}

			// Build list
			$list = array(

				'label' => $label
			);

			return $list;
		}

		// Get list fields
		public function get_list_fields($fetch = false, $woocommerce = true, $acf = true, $meta_box = true, $pods = true, $toolset = true, $jetengine = true) {

			// Check action is configured properly
			if(!self::check_configured()) { return false; }

			// Load configuration
			self::load_config();

			// List fields array
			$list_fields = array();

			// User fields
			switch($this->list_id) {

				case 'register' :

					$fields = array(

						(object) array('id' => 'username', 'name' => __('Username', 'ws-form-user'), 'type' => 'text', 'required' => true, 'meta' => false),
						(object) array('id' => 'email', 'name' => __('Email', 'ws-form-user'), 'type' => 'email', 'required' => true, 'meta' => false),
						(object) array('id' => 'first_name', 'name' => __('First Name', 'ws-form-user'), 'type' => 'text', 'required' => false, 'meta' => false),
						(object) array('id' => 'last_name', 'name' => __('Last Name', 'ws-form-user'), 'type' => 'text', 'required' => false, 'meta' => false),
						(object) array('id' => 'nickname', 'name' => __('Nickname', 'ws-form-user'), 'type' => 'text', 'required' => false, 'meta' => array()),
						(object) array('id' => 'display_name', 'name' => __('Display Name', 'ws-form-user'), 'type' => 'text', 'required' => false, 'meta' => array()),
						(object) array('id' => 'website', 'name' => __('Website', 'ws-form-user'), 'type' => 'url', 'required' => false, 'meta' => false),
						(object) array('id' => 'description', 'name' => __('Biographical Info', 'ws-form-user'), 'type' => 'textarea', 'required' => false, 'meta' => array()),
						(object) array('id' => 'password', 'name' => __('Password', 'ws-form-user'), 'type' => 'password', 'required' => true, 'meta' => false),
						(object) array('id' => 'password_confirm', 'name' => __('Password Confirmation', 'ws-form-user'), 'type' => 'password', 'required' => true, 'meta' => false)
					);

					break;

				case 'update' :

					$fields = array(

						(object) array('id' => 'email', 'name' => __('Email', 'ws-form-user'), 'type' => 'email', 'required' => true, 'meta' => array()),
						(object) array('id' => 'first_name', 'name' => __('First Name', 'ws-form-user'), 'type' => 'text', 'required' => true, 'meta' => array()),
						(object) array('id' => 'last_name', 'name' => __('Last Name', 'ws-form-user'), 'type' => 'text', 'required' => true, 'meta' => array()),
						(object) array('id' => 'nickname', 'name' => __('Nickname', 'ws-form-user'), 'type' => 'text', 'required' => false, 'meta' => array()),
						(object) array('id' => 'display_name', 'name' => __('Display Name', 'ws-form-user'), 'type' => 'text', 'required' => false, 'meta' => array()),
						(object) array('id' => 'website', 'name' => __('Website', 'ws-form-user'), 'type' => 'url', 'required' => false, 'meta' => array()),
						(object) array('id' => 'description', 'name' => __('Biographical Info', 'ws-form-user'), 'type' => 'textarea', 'required' => false, 'meta' => array()),
						(object) array('id' => 'password', 'name' => __('New Password', 'ws-form-user'), 'type' => 'password', 'required' => false, 'meta' => array('help' => __('Enter a new password (optional)'))),
						(object) array(

							'id' 			=> 'rich_editing', 
							'name' 			=> __('Visual Editor', 'ws-form-user'), 
							'type' 			=> 'checkbox', 
							'required' 		=> false, 
							'sort_index' 	=> 8, 
							'meta' 			=> array(

								'data_grid_checkbox' => WS_Form_Common::build_data_grid_meta('data_grid_checkbox', false, array(

										array('id' => 0, 'label' => __('Label', 'ws-form')),
										array('id' => 1, 'label' => __('Value', 'ws-form'))

									), array(array(

									'id'		=> 1,
									'default'	=> '',
									'required'	=> '',
									'disabled'	=> '',
									'hidden'	=> '',
									'data'		=> array(__('Disable the visual editor when writing', 'ws-form-user'), 'on')
								))),

								'checkbox_field_value' => 1
							)
						),
						(object) array(

							'id' 			=> 'syntax_highlighting', 
							'name' 			=> __('Syntax Highlighting', 'ws-form-user'), 
							'type' 			=> 'checkbox', 
							'required' 		=> false, 
							'sort_index' 	=> 9, 
							'meta' 			=> array(

								'data_grid_checkbox' => WS_Form_Common::build_data_grid_meta('data_grid_checkbox', false, array(

										array('id' => 0, 'label' => __('Label', 'ws-form')),
										array('id' => 1, 'label' => __('Value', 'ws-form'))

									), array(array(

									'id'		=> 1,
									'default'	=> '',
									'required'	=> '',
									'disabled'	=> '',
									'hidden'	=> '',
									'data'		=> array(__('Disable syntax highlighting when editing code', 'ws-form-user'), 'on')
								))),

								'checkbox_field_value' => 1
							)
						),
						(object) array(

							'id' 			=> 'comment_shortcuts', 
							'name' 			=> __('Keyboard Shortcuts', 'ws-form-user'), 
							'type' 			=> 'checkbox', 
							'required' 		=> false, 
							'sort_index' 	=> 10, 
							'meta' 			=> array(

								'data_grid_checkbox' => WS_Form_Common::build_data_grid_meta('data_grid_checkbox', false, array(

										array('id' => 0, 'label' => __('Label', 'ws-form')),
										array('id' => 1, 'label' => __('Value', 'ws-form'))

									), array(array(

									'id'		=> 1,
									'default'	=> '',
									'required'	=> '',
									'disabled'	=> '',
									'hidden'	=> '',
									'data'		=> array(__('Enable keyboard shortcuts for comment moderation', 'ws-form-user'), 'on')
								))),

								'checkbox_field_value' => 1
							)
						),
						(object) array(

							'id' 			=> 'show_admin_bar_front', 
							'name' 			=> __('Toolbar', 'ws-form-user'), 
							'type' 			=> 'checkbox', 
							'required' 		=> false, 
							'sort_index' 	=> 11, 
							'meta' 			=> array(

								'data_grid_checkbox' => WS_Form_Common::build_data_grid_meta('data_grid_checkbox', false, array(

										array('id' => 0, 'label' => __('Label', 'ws-form')),
										array('id' => 1, 'label' => __('Value', 'ws-form'))

									), array(array(

									'id'		=> 1,
									'default'	=> '',
									'required'	=> '',
									'disabled'	=> '',
									'hidden'	=> '',
									'data'		=> array(__('Show Toolbar when viewing site', 'ws-form-user'), 'on')
								))),

								'checkbox_field_value' => 1
							)
						),
						(object) array(

							'id' 			=> 'admin_color', 
							'name' 			=> __('Admin Color Scheme', 'ws-form-user'), 
							'type' 			=> 'radio', 
							'required' 		=> false, 
							'meta' 			=> array(

								'data_grid_radio' => WS_Form_Common::build_data_grid_meta('data_grid_radio', false, array(

										array('id' => 0, 'label' => __('Label', 'ws-form')),
										array('id' => 1, 'label' => __('Value', 'ws-form'))

									), array(

										array(

											'id'		=> 1,
											'default'	=> 'on',
											'required'	=> '',
											'disabled'	=> '',
											'hidden'	=> '',
											'data'		=> array(__('Default', 'ws-form-user'), 'fresh')
										),
										array(

											'id'		=> 2,
											'default'	=> '',
											'required'	=> '',
											'disabled'	=> '',
											'hidden'	=> '',
											'data'		=> array(__('Light', 'ws-form-user'), 'light')
										),
										array(

											'id'		=> 3,
											'default'	=> '',
											'required'	=> '',
											'disabled'	=> '',
											'hidden'	=> '',
											'data'		=> array(__('Blue', 'ws-form-user'), 'blue')
										),
										array(

											'id'		=> 4,
											'default'	=> '',
											'required'	=> '',
											'disabled'	=> '',
											'hidden'	=> '',
											'data'		=> array(__('Coffee', 'ws-form-user'), 'coffee')
										),
										array(

											'id'		=> 5,
											'default'	=> '',
											'required'	=> '',
											'disabled'	=> '',
											'hidden'	=> '',
											'data'		=> array(__('Ectoplasm', 'ws-form-user'), 'ectoplasm')
										),
										array(

											'id'		=> 6,
											'default'	=> '',
											'required'	=> '',
											'disabled'	=> '',
											'hidden'	=> '',
											'data'		=> array(__('Midnight', 'ws-form-user'), 'midnight')
										),
										array(

											'id'		=> 7,
											'default'	=> '',
											'required'	=> '',
											'disabled'	=> '',
											'hidden'	=> '',
											'data'		=> array(__('Ocean', 'ws-form-user'), 'ocean')
										),
										array(

											'id'		=> 8,
											'default'	=> '',
											'required'	=> '',
											'disabled'	=> '',
											'hidden'	=> '',
											'data'		=> array(__('Sunrise', 'ws-form-user'), 'sunrise')
										)
									)
								),

								'radio_field_value' => 1
							)
						)
					);

					break;

				case 'signon' :

					$fields = array(

						(object) array('id' => 'username', 'name' => __('Username or Email Address', 'ws-form-user'), 'type' => 'text', 'required' => true, 'meta' => false),
						(object) array('id' => 'password', 'name' => __('Password', 'ws-form-user'), 'type' => 'password', 'required' => true, 'meta' => false, 'meta' => array('password_strength_meter' => '')),
						(object) array(

							'id' 			=> 'remember_me', 
							'name' 			=> __('Remember Me', 'ws-form-user'), 
							'type' 			=> 'checkbox', 
							'required' 		=> false, 
							'meta' 			=> array(

								'data_grid_checkbox' => WS_Form_Common::build_data_grid_meta('data_grid_checkbox', false, false, array(array(

									'id'		=> 1,
									'default'	=> '',
									'required'	=> '',
									'disabled'	=> '',
									'hidden'	=> '',
									'data'		=> array(__('Remember Me', 'ws-form-user'))
								)))
							)
						),
					);

					break;

				case 'lostpassword' :

					$fields = array(

						(object) array(

							'id' 			=> 'help_text', 
							'name' 			=> __('Help Text', 'ws-form-user'), 
							'type' 			=> 'texteditor', 
							'required' 		=> false,
							'meta' 			=> array(

								'text_editor' => '<p>' . __('Please enter your username or email address. You will receive a link to create a new password via email.', 'ws-form-user') . '</p>'
							),
							'no_map'		=> true
						),
						(object) array('id' => 'username', 'name' => __('Username or Email Address', 'ws-form-user'), 'type' => 'text', 'required' => true, 'sort_index' => 2),
					);

					break;

				case 'resetpassword' :

					$fields = array(

						(object) array(

							'id' 			=> 'help_text', 
							'name' 			=> __('Help Text', 'ws-form-user'), 
							'type' 			=> 'texteditor', 
							'required' 		=> false,
							'meta' 			=> array(

								'text_editor' => '<p>' . __('Enter your new password below.', 'ws-form-user') . '</p>'
							)
						),

						(object) array(

							'id' => 'pass1',
							'name' => __('New Password', 'ws-form-user'),
							'type' => 'password',
							'required' => true
						),

						(object) array(

							'id' => 'pass2',
							'name' => __('New Password (Confirmation)', 'ws-form-user'),
							'type' => 'password',
							'required' => true
						),

						(object) array(

							'id' => 'rp_login',
							'name' => __('Login', 'ws-form-user'),
							'type' => 'hidden',
							'required' => true,
							'meta'			=>	array(

								'default_value'	=>	'#query_var("login")'
							)
						),

						(object) array(

							'id' => 'rp_key',
							'name' => __('Reset Password Key', 'ws-form-user'),
							'type' => 'hidden',
							'required' => true,
							'meta'			=>	array(

								'default_value'	=>	'#query_var("key")'
							)
						),
					);

					break;

				case 'logout' :

					$fields = array();

					break;
			}

			// Process fields
			$sort_index = 1;
			$section_index = 0;
			foreach($fields as $field) {

				$type = parent::get_object_value($field, 'type');
				$action_type = parent::get_object_value($field, 'action_type');

				$list_fields[] = array(

					'id' => 			parent::get_object_value($field, 'id'),
					'label' => 			parent::get_object_value($field, 'name'), 
					'label_field' => 	parent::get_object_value($field, 'name'), 
					'type' => 			$type,
					'action_type' =>	$type,
					'required' => 		parent::get_object_value($field, 'required'), 
					'default_value' => 	parent::get_object_value($field, 'default_value'),
					'pattern' => 		'',
					'placeholder' => 	'',
					'help' => 			parent::get_object_value($field, 'help_text'), 
					'sort_index' => 	$sort_index++,
					'section_index' =>	0,
					'visible' =>		true,
					'meta' => 			parent::get_object_value($field, 'meta'),
					'no_map' =>			parent::get_object_value($field, 'no_map')
				);
			}

			switch($this->list_id) {

				case 'register' :
				case 'update' :

					$group_index = 0;
					$section_index = 1;	// Set to 1 so it follows the built in fields

					// Get WooCommerce fields
					if($this->woocommerce_activated && $woocommerce) {

						$fields = WS_Form_WooCommerce::woocommerce_get_fields_all('user', false, false, true, false, false);

						$woocommerce_fields_to_list_fields_return = WS_Form_WooCommerce::woocommerce_fields_to_list_fields($fields, $group_index, $section_index, $sort_index);
						$list_fields = array_merge($list_fields, $woocommerce_fields_to_list_fields_return['list_fields']);

						// Read group and section index (Fed to next integration)
						$group_index = $woocommerce_fields_to_list_fields_return['group_index'];
						$section_index = $woocommerce_fields_to_list_fields_return['section_index'] + 1;
					}

					// Get ACF fields
					if($this->acf_activated && $acf) {

						// Get ACF fields
						$filter = array(

							'user_id' => (($this->list_id == 'register') ? 'new' : get_current_user_id())
						);
						switch($this->list_id) {

							case 'register' :

								$filter['user_form'] = 'add';
								break;

							case 'update' :

								$filter['user_form'] = 'edit';
								break;
						}

						$fields = WS_Form_ACF::acf_get_fields_all($filter, false, true, false, false);

						$acf_fields_to_list_fields_return = WS_Form_ACF::acf_fields_to_list_fields($fields, $group_index, $section_index, $sort_index);
						$list_fields = array_merge($list_fields, $acf_fields_to_list_fields_return['list_fields']);

						// Read group and section index (Fed to next integration)
						$group_index = $acf_fields_to_list_fields_return['group_index'];
						$section_index = $acf_fields_to_list_fields_return['section_index'] + 1;
					}

					// Get Metabox fields
					if($this->meta_box_activated && $meta_box) {

						$fields = WS_Form_Meta_Box::meta_box_get_fields_all('user', false, false, true, false, false);

						$meta_box_fields_to_list_fields_return = WS_Form_Meta_Box::meta_box_fields_to_list_fields($fields, $group_index, $section_index, $sort_index);
						$list_fields = array_merge($list_fields, $meta_box_fields_to_list_fields_return['list_fields']);

						// Read group and section index (Fed to next integration)
						$group_index = $meta_box_fields_to_list_fields_return['group_index'];
						$section_index = $meta_box_fields_to_list_fields_return['section_index'] + 1;
					}

					// Get Pods fields
					if($this->pods_activated && $pods) {

						$fields = WS_Form_Pods::pods_get_fields_all('user', false, false, true, false, false);

						$pods_fields_to_list_fields_return = WS_Form_Pods::pods_fields_to_list_fields($fields, $group_index, $section_index, $sort_index);
						$list_fields = array_merge_recursive($list_fields, $pods_fields_to_list_fields_return['list_fields']);

						// Read group and section index (Fed to next integration)
						$group_index = $pods_fields_to_list_fields_return['group_index'];
						$section_index = $pods_fields_to_list_fields_return['section_index'] + 1;
					}

					// Get Toolset fields
					if($this->toolset_activated && $toolset) {

						$fields = WS_Form_Toolset::toolset_get_fields_all(array('domain' => Toolset_Element_Domain::USERS), false, true, false, false);

						$toolset_fields_to_list_fields_return = WS_Form_Toolset::toolset_fields_to_list_fields($fields, $group_index, $section_index, $sort_index);
						$list_fields = array_merge_recursive($list_fields, $toolset_fields_to_list_fields_return['list_fields']);

						// Read group and section index (Fed to next integration)
						$group_index = $toolset_fields_to_list_fields_return['group_index'];
						$section_index = $toolset_fields_to_list_fields_return['section_index'] + 1;
					}

					// Get JetEngine fields
					if($this->jetengine_activated && $jetengine) {

						$fields = WS_Form_JetEngine::jetengine_get_fields_all('user', null, false, true, false, false);

						$jetengine_fields_to_list_fields_return = WS_Form_JetEngine::jetengine_fields_to_list_fields($fields, $group_index, $section_index, $sort_index);
						$list_fields = array_merge_recursive($list_fields, $jetengine_fields_to_list_fields_return['list_fields']);

						// Read group and section index (Fed to next integration)
						$group_index = $jetengine_fields_to_list_fields_return['group_index'];
						$section_index = $jetengine_fields_to_list_fields_return['section_index'] + 1;
					}

					break;
			}

			return $list_fields;
		}

		// Get list fields meta data (Returns group and section data such as label and whether or not a section is repeatable)
		public function get_list_fields_meta_data() {

			$group_meta_data = array();
			$section_meta_data = array();

			$group_index = 0;
			$section_index = 1;	// Set to 1 so it follows the built in fields

			// Get WooCommerce fields
			if($this->woocommerce_activated) {

				$fields = WS_Form_WooCommerce::woocommerce_get_fields_all('user', false, false, true, false, false);

				$woocommerce_fields_to_meta_data_return = WS_Form_WooCommerce::woocommerce_fields_to_meta_data($fields, $group_index, $section_index);
				$group_meta_data = array_merge($group_meta_data, $woocommerce_fields_to_meta_data_return['group_meta_data']);
				$section_meta_data = array_merge($section_meta_data, $woocommerce_fields_to_meta_data_return['section_meta_data']);

				// Read group and section index (Fed to next integration)
				$group_index = $woocommerce_fields_to_meta_data_return['group_index'];
				$section_index = $woocommerce_fields_to_meta_data_return['section_index'] + 1;
			}

			// Get ACF fields
			if($this->acf_activated) {

				// Get ACF fields
				$filter = array(

					'user_id' => (($this->list_id == 'register') ? 'new' : get_current_user_id())
				);
				switch($this->list_id) {

					case 'register' :

						$filter['user_form'] = 'add';
						break;

					case 'update' :

						$filter['user_form'] = 'edit';
						break;
				}

				$fields = WS_Form_ACF::acf_get_fields_all($filter, false, true, false, false);

				$acf_fields_to_meta_data_return = WS_Form_ACF::acf_fields_to_meta_data($fields, $group_index, $section_index);
				$group_meta_data = array_merge($group_meta_data, $acf_fields_to_meta_data_return['group_meta_data']);
				$section_meta_data = array_merge($section_meta_data, $acf_fields_to_meta_data_return['section_meta_data']);

				// Read group and section index (Fed to next integration)
				$group_index = $acf_fields_to_meta_data_return['group_index'];
				$section_index = $acf_fields_to_meta_data_return['section_index'] + 1;
			}

			// Get Meta Box fields
			if($this->meta_box_activated) {

				$fields = WS_Form_Meta_Box::meta_box_get_fields_all('user', false, false, true, false, false);

				$meta_box_fields_to_meta_data_return = WS_Form_Meta_Box::meta_box_fields_to_meta_data($fields, $group_index, $section_index);
				$group_meta_data = array_merge($group_meta_data, $meta_box_fields_to_meta_data_return['group_meta_data']);
				$section_meta_data = array_merge($section_meta_data, $meta_box_fields_to_meta_data_return['section_meta_data']);

				// Read group and section index (Fed to next integration)
				$group_index = $meta_box_fields_to_meta_data_return['group_index'];
				$section_index = $meta_box_fields_to_meta_data_return['section_index'] + 1;
			}

			// Get Pods fields
			if($this->pods_activated) {

				$fields = WS_Form_Pods::pods_get_fields_all('user', false, false, true, false, false);

				$pods_fields_to_meta_data_return = WS_Form_Pods::pods_fields_to_meta_data($fields, $group_index, $section_index);
				$group_meta_data = array_merge_recursive($group_meta_data, $pods_fields_to_meta_data_return['group_meta_data']);
				$section_meta_data = array_merge_recursive($section_meta_data, $pods_fields_to_meta_data_return['section_meta_data']);

				// Read group and section index (Fed to next integration)
				$group_index = $pods_fields_to_meta_data_return['group_index'];
				$section_index = $pods_fields_to_meta_data_return['section_index'] + 1;
			}

			// Get Toolset fields
			if($this->toolset_activated) {

				$fields = WS_Form_Toolset::toolset_get_fields_all(array('domain' => Toolset_Element_Domain::USERS), false, true, false, false);

				$toolset_fields_to_meta_data_return = WS_Form_Toolset::toolset_fields_to_meta_data($fields, $group_index, $section_index);
				$group_meta_data = array_merge_recursive($group_meta_data, $toolset_fields_to_meta_data_return['group_meta_data']);
				$section_meta_data = array_merge_recursive($section_meta_data, $toolset_fields_to_meta_data_return['section_meta_data']);

				// Read group and section index (Fed to next integration)
				$group_index = $toolset_fields_to_meta_data_return['group_index'];
				$section_index = $toolset_fields_to_meta_data_return['section_index'] + 1;
			}

			// Get JetEngine fields
			if($this->jetengine_activated) {

				$fields = WS_Form_JetEngine::jetengine_get_fields_all('user', null, false, true, false, false);

				$jetengine_fields_to_meta_data_return = WS_Form_JetEngine::jetengine_fields_to_meta_data($fields, $group_index, $section_index);
				$group_meta_data = array_merge_recursive($group_meta_data, $jetengine_fields_to_meta_data_return['group_meta_data']);
				$section_meta_data = array_merge_recursive($section_meta_data, $jetengine_fields_to_meta_data_return['section_meta_data']);

				// Read group and section index (Fed to next integration)
				$group_index = $jetengine_fields_to_meta_data_return['group_index'];
				$section_index = $jetengine_fields_to_meta_data_return['section_index'] + 1;
			}

			return array('group_meta_data' => $group_meta_data, 'section_meta_data' => $section_meta_data);
		}

		// Get form fields
		public function get_fields() {

			switch($this->list_id) {

				case 'register' :

					$form_fields = array(

						'submit' => array(

							'type'			=>	'submit',
							'label'			=>	__('Register', 'ws-form-user')
						)
					);

					break;

				case 'update' :

					$form_fields = array(

						'submit' => array(

							'type'			=>	'submit',
							'label'			=>	__('Update Profile', 'ws-form-user')
						)
					);

					break;

				case 'signon' :

					$form_fields = array(

						'submit' => array(

							'type'			=>	'submit',
							'label'			=>	__('Log In', 'ws-form-user')
						)
					);

					break;

				case 'lostpassword' :

					$form_fields = array(

						'submit' => array(

							'type'			=>	'submit',
							'label'			=>	__('Get New Password', 'ws-form-user')
						)
					);

					break;

				case 'resetpassword' :

					$form_fields = array(

						'submit' => array(

							'type'			=>	'submit',
							'label'			=>	__('Reset Password', 'ws-form-user'),
						)
					);

					break;

				case 'logout' :

					$form_fields = array(

						'submit' => array(

							'type'			=>	'submit',
							'label'			=>	__('Log Out', 'ws-form-user'),
						)
					);

					break;
			}

			return $form_fields;
		}

		// Get form actions
		public function get_actions($form_field_id_lookup_all) {

			switch($this->list_id) {

				case 'register' :

					$form_actions = array(

						$this->id => array(

							'meta'	=> array(

								'action_' . $this->id . '_list_id'			=>	$this->list_id,
								'action_' . $this->id . '_field_mapping'	=>	'field_mapping'
							)
						),

						'message' => array(

							'meta'	=> array(

								'action_message_message'	=> __('Thank you for registering.', 'ws-form-user')
							)
						)
					);

					break;

				case 'update' :

					$form_actions = array(

						$this->id => array(

							'meta'	=> array(

								'action_' . $this->id . '_list_id'			=>	$this->list_id,
								'action_' . $this->id . '_field_mapping'	=>	'field_mapping'
							)
						),

						'message' => array(

							'meta'	=> array(

								'action_message_message'	=> __('Your profile has been successfully updated.', 'ws-form-user')
							)
						)
					);

					break;

				case 'signon' :

					$form_actions = array(

						$this->id => array(

							'meta'	=> array(

								'action_' . $this->id . '_list_id'			=>	$this->list_id,
								'action_' . $this->id . '_field_mapping'	=>	'field_mapping'
							)
						),

						'message' => array(

							'meta'	=> array(

								'action_message_message'	=> __('You were successfully logged in.', 'ws-form-user'),
								'action_message_duration'	=> '2000'
							)
						),

						'redirect' => array(

							'meta'	=> array(

								'url'	=> '/'
							)
						)
					);

					break;

				case 'lostpassword' :

					$message_textarea = __("Someone has requested a password reset for the following account:\n\nSite Name: #blog_name\n\nUsername: #user_login\n\nIf this was a mistake, just ignore this email and nothing will happen.\n\nTo reset your password, visit the following address:\n\n#user_lost_password_url", 'ws-form-user');
					$message_text_editor = '<p>' . implode("</p><p>", explode("\n\n", $message_textarea)) . '</p>';
					$message_html_editor = '<p>' . implode("</p>\n\n<p>", explode("\n\n", $message_textarea)) . '</p>';

					$form_actions = array(

						$this->id => array(

							'meta'	=> array(

								'action_' . $this->id . '_list_id'			=>	$this->list_id,
								'action_' . $this->id . '_field_mapping'	=>	'field_mapping'
							)
						),

						'email' => array(

							'meta'	=> array(

								'action_email_to'			=> array(

									array(

										'action_email_email' 	=> '#user_email',
										'action_email_name' 	=> '#blog_name'
									)
								),

								'action_email_subject'		=> __('Password Reset', 'ws-form-user'),

								'action_email_message_textarea'		=>	$message_textarea,
								'action_email_message_text_editor'	=>	$message_text_editor,
								'action_email_message_html_editor'	=>	$message_html_editor
							)
						),

						'message' => array(

							'meta'	=> array(

								'action_message_message'	=> __('Check your email for the confirmation link.', 'ws-form-user')
							)
						)
					);

					break;

				case 'resetpassword' :

					$form_actions = array(

						$this->id => array(

							'meta'	=> array(

								'action_' . $this->id . '_list_id'			=>	$this->list_id,
								'action_' . $this->id . '_field_mapping'	=>	'field_mapping'
							)
						),

						'message' => array(

							'meta'	=> array(

								'action_message_message'	=> __('Your password was successfully reset.', 'ws-form-user')
							)
						)
					);

					break;

				case 'logout' :

					$form_actions = array(

						$this->id => array(

							'meta'	=> array(

								'action_' . $this->id . '_list_id'			=>	$this->list_id
							)
						),

						'message' => array(

							'meta'	=> array(

								'action_message_message'	=> __('You were successfully logged out.', 'ws-form-user'),
								'action_message_duration'	=> '2000'
							)
						),

						'redirect' => array(

							'meta'	=> array(

								'url'	=> '/'
							)
						)
					);

					break;
			}

			switch($this->list_id) {

				case 'register' :
				case 'update' :

					if($this->acf_activated) {

						// Get ACF fields
						$filter = array(

							'user_id' => (($this->list_id == 'register') ? 'new' : get_current_user_id())
						);
						switch($this->list_id) {

							case 'register' :

								$filter['user_form'] = 'add';
								break;

							case 'update' :

								$filter['user_form'] = 'edit';
								break;
						}

						$fields = WS_Form_ACF::acf_get_fields_all($filter, false, true, false);

						$acf_fields_to_list_fields_return = WS_Form_ACF::acf_fields_to_list_fields($fields);
						$list_fields = $acf_fields_to_list_fields_return['list_fields'];

						$form_actions[$this->id]['meta']['action_' . $this->id . '_field_mapping_acf'] = array();

						foreach($list_fields as $list_field) {

							if(
								!isset($form_field_id_lookup_all[$list_field['id']]) ||
								!WS_Form_ACF::acf_field_mappable($list_field['action_type'])
							) {
								continue;
							}

							$form_actions[$this->id]['meta']['action_' . $this->id . '_field_mapping_acf'][] = array(

								'ws_form_field' => $form_field_id_lookup_all[$list_field['id']],
								'action_' . $this->id . '_acf_key' => $list_field['id']
							);
						}
					}

					if($this->meta_box_activated) {

						$fields = WS_Form_Meta_Box::meta_box_get_fields_all('user', false, false, true, false, false);

						$meta_box_fields_to_list_fields_return = WS_Form_Meta_Box::meta_box_fields_to_list_fields($fields);
						$list_fields = $meta_box_fields_to_list_fields_return['list_fields'];

						$form_actions[$this->id]['meta']['action_' . $this->id . '_field_mapping_meta_box'] = array();

						foreach($list_fields as $list_field) {

							if(
								!isset($form_field_id_lookup_all[$list_field['id']]) ||
								!WS_Form_Meta_Box::meta_box_field_mappable($list_field['action_type'])
							) {
								continue;
							}

							$form_actions[$this->id]['meta']['action_' . $this->id . '_field_mapping_meta_box'][] = array(

								'ws_form_field' => $form_field_id_lookup_all[$list_field['id']],
								'action_' . $this->id . '_meta_box_field_id' => $list_field['id']
							);
						}
					}

					if($this->pods_activated) {

						$fields = WS_Form_Pods::pods_get_fields_all('user', false, false, true, false, false);

						$pods_fields_to_list_fields_return = WS_Form_Pods::pods_fields_to_list_fields($fields);
						$list_fields = $pods_fields_to_list_fields_return['list_fields'];

						$form_actions[$this->id]['meta']['action_' . $this->id . '_field_mapping_pods'] = array();

						foreach($list_fields as $list_field) {

							if(
								!isset($form_field_id_lookup_all[$list_field['id']]) ||
								!WS_Form_Pods::pods_field_mappable($list_field['action_type'])
							) {
								continue;
							}

							$form_actions[$this->id]['meta']['action_' . $this->id . '_field_mapping_pods'][] = array(

								'ws_form_field' => $form_field_id_lookup_all[$list_field['id']],
								'action_' . $this->id . '_pods_field_id' => $list_field['id']
							);
						}
					}

					if($this->toolset_activated) {

						$fields = WS_Form_Toolset::toolset_get_fields_all(array('domain' => Toolset_Element_Domain::USERS), false, true, false, false);

						$toolset_fields_to_list_fields_return = WS_Form_Toolset::toolset_fields_to_list_fields($fields);
						$list_fields = $toolset_fields_to_list_fields_return['list_fields'];

						$form_actions[$this->id]['meta']['action_' . $this->id . '_field_mapping_toolset'] = array();

						foreach($list_fields as $list_field) {

							if(
								!isset($form_field_id_lookup_all[$list_field['id']]) ||
								!WS_Form_Toolset::toolset_field_mappable($list_field['action_type'])
							) {
								continue;
							}

							$form_actions[$this->id]['meta']['action_' . $this->id . '_field_mapping_toolset'][] = array(

								'ws_form_field' => $form_field_id_lookup_all[$list_field['id']],
								'action_' . $this->id . '_toolset_field_slug' => $list_field['id']
							);
						}
					}

					if($this->jetengine_activated) {

						$fields = WS_Form_JetEngine::jetengine_get_fields_all('user', null, false, true, false, false);

						$jetengine_fields_to_list_fields_return = WS_Form_JetEngine::jetengine_fields_to_list_fields($fields);
						$list_fields = $jetengine_fields_to_list_fields_return['list_fields'];

						$form_actions[$this->id]['meta']['action_' . $this->id . '_field_mapping_jetengine'] = array();

						foreach($list_fields as $list_field) {

							if(
								!isset($form_field_id_lookup_all[$list_field['id']]) ||
								!WS_Form_JetEngine::jetengine_field_mappable($list_field['action_type'])
							) {
								continue;
							}

							$form_actions[$this->id]['meta']['action_' . $this->id . '_field_mapping_jetengine'][] = array(

								'ws_form_field' => $form_field_id_lookup_all[$list_field['id']],
								'action_' . $this->id . '_jetengine_field_name' => $list_field['id']
							);
						}
					}

					break;
			}

			return $form_actions;
		}

		// Get conditionals
		public function get_conditionals() {

			switch($this->list_id) {

				case 'register' :

					$form_conditionals = array(

						array(

							'label'			=>	__('Check passwords match', 'ws-form-user'),

							'conditional'	=> array(

								'if'	=>	array(

									array(

										'conditions'	=>	array(

											array(

												'id' => 1,
												'object' => 'field',
												'object_id' => 'password',
												'object_row_id' => false,
												'logic' => 'field_match_not',
												'value' => 'password_confirm',
												'case_sensitive' => true,
												'logic_previous' => '||'
											)
										),

										'logic_previous' => '||'
									)
								),

								'then'	=> array(

									array(

										'id' => 1,
										'object' => 'field',
										'object_id' => 'password_confirm',
										'object_row_id' => false,
										'action' => 'set_custom_validity',
										'value' => __('Passwords do not match', 'ws-form-user')
									)
								),

								'else'	=> array(

									array(

										'id' => 1,
										'object' => 'field',
										'object_id' => 'password_confirm',
										'object_row_id' => false,
										'action' => 'set_custom_validity',
										'value' => ''
									)
								)
							)
						)
					);

					break;

				case 'resetpassword' :

					$form_conditionals = array(

						array(

							'label'			=>	__('Check passwords match', 'ws-form-user'),

							'conditional'	=> array(

								'if'	=>	array(

									array(

										'conditions'	=>	array(

											array(

												'id' => 1,
												'object' => 'field',
												'object_id' => 'pass1',
												'object_row_id' => false,
												'logic' => 'field_match_not',
												'value' => 'pass2',
												'case_sensitive' => true,
												'logic_previous' => '||'
											)
										),

										'logic_previous' => '||'
									)
								),

								'then'	=> array(

									array(

										'id' => 1,
										'object' => 'field',
										'object_id' => 'pass2',
										'object_row_id' => false,
										'action' => 'set_custom_validity',
										'value' => __('Passwords do not match', 'ws-form-user')
									)
								),

								'else'	=> array(

									array(

										'id' => 1,
										'object' => 'field',
										'object_id' => 'pass2',
										'object_row_id' => false,
										'action' => 'set_custom_validity',
										'value' => ''
									)
								)
							)
						)
					);

					break;

				default :

					$form_conditionals = array();
			}

			return $form_conditionals;
		}

		// Get form meta
		public function get_meta($form_field_id_lookup_all) {

			$form_meta = array('submit_reload' => '');

			switch($this->list_id) {

				case 'update' :

					$form_meta['form_populate_enabled'] = 'on';

					$form_meta['action_' . $this->id . '_form_populate_field_mapping'] = array(

						array('action_user_form_populate_field' => 'user_first_name', 'ws_form_field' => self::form_field_id_lookup('first_name', $form_field_id_lookup_all)),
						array('action_user_form_populate_field' => 'user_last_name', 'ws_form_field' => self::form_field_id_lookup('last_name', $form_field_id_lookup_all)),
						array('action_user_form_populate_field' => 'user_nickname', 'ws_form_field' => self::form_field_id_lookup('nickname', $form_field_id_lookup_all)),
						array('action_user_form_populate_field' => 'user_display_name', 'ws_form_field' => self::form_field_id_lookup('display_name', $form_field_id_lookup_all)),
						array('action_user_form_populate_field' => 'user_email', 'ws_form_field' => self::form_field_id_lookup('email', $form_field_id_lookup_all)),
						array('action_user_form_populate_field' => 'user_url', 'ws_form_field' => self::form_field_id_lookup('website', $form_field_id_lookup_all)),
						array('action_user_form_populate_field' => 'user_description', 'ws_form_field' => self::form_field_id_lookup('description', $form_field_id_lookup_all)),
						array('action_user_form_populate_field' => 'user_rich_editing', 'ws_form_field' => self::form_field_id_lookup('rich_editing', $form_field_id_lookup_all)),
						array('action_user_form_populate_field' => 'user_syntax_highlighting', 'ws_form_field' => self::form_field_id_lookup('syntax_highlighting', $form_field_id_lookup_all)),
						array('action_user_form_populate_field' => 'user_comment_shortcuts', 'ws_form_field' => self::form_field_id_lookup('comment_shortcuts', $form_field_id_lookup_all)),
						array('action_user_form_populate_field' => 'user_show_admin_bar_front', 'ws_form_field' => self::form_field_id_lookup('show_admin_bar_front', $form_field_id_lookup_all)),
						array('action_user_form_populate_field' => 'user_admin_color', 'ws_form_field' => self::form_field_id_lookup('admin_color', $form_field_id_lookup_all))
					);

					if($this->woocommerce_activated) {

						$woocommerce_fields = WS_Form_WooCommerce::woocommerce_get_fields_all('user', false, false, false, true, false);

						foreach($woocommerce_fields as $meta_key => $woocommerce_field) {

							$form_meta['action_' . $this->id . '_form_populate_field_mapping'][] = array('action_user_form_populate_field' => $meta_key, 'ws_form_field' => self::form_field_id_lookup($meta_key, $form_field_id_lookup_all));
						}
					}

					if($this->acf_activated) {

						// Get ACF fields
						$filter = array(

							'user_id' => (($this->list_id == 'register') ? 'new' : get_current_user_id())
						);
						switch($this->list_id) {

							case 'register' :

								$filter['user_form'] = 'add';
								break;

							case 'update' :

								$filter['user_form'] = 'edit';
								break;
						}

						$fields = WS_Form_ACF::acf_get_fields_all($filter, false, false, true, false);

						foreach($fields as $field) {

							if(!isset($form_field_id_lookup_all[$field['value']])) { continue; }

							$form_meta['action_' . $this->id . '_form_populate_field_mapping_acf'][] = array(

								'action_' . $this->id . '_acf_key' => $field['value'],
								'ws_form_field' => $form_field_id_lookup_all[$field['value']]
							);
						}
					}

					if($this->meta_box_activated) {

						$fields = WS_Form_Meta_Box::meta_box_get_fields_all('user', false, false, false, true, false);

						foreach($fields as $field) {

							if(!isset($form_field_id_lookup_all[$field['value']])) { continue; }

							$form_meta['action_' . $this->id . '_form_populate_field_mapping_meta_box'][] = array(

								'action_' . $this->id . '_meta_box_field_id' => $field['value'],
								'ws_form_field' => $form_field_id_lookup_all[$field['value']]
							);
						}
					}

					if($this->pods_activated) {

						$fields = WS_Form_Pods::pods_get_fields_all('user', false, false, false, true, false);

						foreach($fields as $field) {

							if(!isset($form_field_id_lookup_all[$field['value']])) { continue; }

							$form_meta['action_' . $this->id . '_form_populate_field_mapping_pods'][] = array(

								'action_' . $this->id . '_pods_field_id' => $field['value'],
								'ws_form_field' => $form_field_id_lookup_all[$field['value']]
							);
						}
					}

					if($this->toolset_activated) {

						$fields = WS_Form_Toolset::toolset_get_fields_all(array('domain' => Toolset_Element_Domain::USERS), false, false, true, false);

						foreach($fields as $field) {

							if(!isset($form_field_id_lookup_all[$field['value']])) { continue; }

							$form_meta['action_' . $this->id . '_form_populate_field_mapping_toolset'][] = array(

								'action_' . $this->id . '_toolset_field_slug' => $field['value'],
								'ws_form_field' => $form_field_id_lookup_all[$field['value']]
							);
						}
					}

					if($this->jetengine_activated) {

						$fields = WS_Form_JetEngine::jetengine_get_fields_all('user', null, false, false, true, false);

						foreach($fields as $field) {

							if(!isset($form_field_id_lookup_all[$field['value']])) { continue; }

							$form_meta['action_' . $this->id . '_form_populate_field_mapping_jetengine'][] = array(

								'action_' . $this->id . '_jetengine_field_name' => $field['value'],
								'ws_form_field' => $form_field_id_lookup_all[$field['value']]
							);
						}
					}

					break;
			}

			return $form_meta;
		}

		// Perform form field ID lookup
		public function form_field_id_lookup($input, $form_field_id_lookup) {

			return (isset($form_field_id_lookup[$input])) ? $form_field_id_lookup[$input] : $input;
		}

		// Get settings
		public function get_action_settings() {

			$settings = array(

				'meta_keys'		=> array(

					'action_' . $this->id . '_list_id',
					'action_' . $this->id . '_field_mapping',
					'action_' . $this->id . '_meta_mapping',
					'action_' . $this->id . '_meta_mapping_custom',
					'action_' . $this->id . '_secure_cookie',
					'action_' . $this->id . '_rich_editing',
					'action_' . $this->id . '_syntax_highlighting',
					'action_' . $this->id . '_comment_shortcuts',
					'action_' . $this->id . '_show_admin_bar_front',
					'action_' . $this->id . '_password_create',
					'action_' . $this->id . '_password_length',
					'action_' . $this->id . '_password_special_characters',
					'action_' . $this->id . '_send_user_notification',
					'action_' . $this->id . '_admin_color',
					'action_' . $this->id . '_role'
				)
			);

			// JetEngine
			if($this->jetengine_activated) {

				array_splice($settings['meta_keys'], 2, 0, 'action_' . $this->id . '_jetengine_relations');
				array_splice($settings['meta_keys'], 2, 0, 'action_' . $this->id . '_field_mapping_jetengine');
			}

			// Toolset
			if($this->toolset_activated) {

				array_splice($settings['meta_keys'], 2, 0, 'action_' . $this->id . '_field_mapping_toolset');
			}

			// Pods
			if($this->pods_activated) {

				array_splice($settings['meta_keys'], 2, 0, 'action_' . $this->id . '_field_mapping_pods');
			}

			// Meta Box
			if($this->meta_box_activated) {

				array_splice($settings['meta_keys'], 2, 0, 'action_' . $this->id . '_field_mapping_meta_box');
			}

			// ACF
			if($this->acf_activated) {

				array_splice($settings['meta_keys'], 2, 0, 'action_' . $this->id . '_acf_validation');
				array_splice($settings['meta_keys'], 2, 0, 'action_' . $this->id . '_field_mapping_acf');
			}

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

			if(!$this->configured) { return self::error(__('Action not configured', 'ws-form-user') . ' (' . $this->label . ''); }

			return $this->configured;
		}

		// Check list ID is set
		public function check_list_id() {

			if($this->list_id === false) { return self::error(__('List ID is not set', 'ws-form-user')); }

			return ($this->list_id !== false);
		}

		// Meta keys for this action
		public function config_meta_keys($meta_keys = array(), $form_id = 0) {

			// Build config_meta_keys
			$config_meta_keys = array(

				// List ID
				'action_' . $this->id . '_list_id'	=> array(

					'label'							=>	__('Method', 'ws-form-user'),
					'type'							=>	'select',
					'help'							=>	__('Which user method do you want to run?', 'ws-form-user'),
					'options'						=>	'action_api_populate',
					'options_blank'					=>	__('Select...', 'ws-form-user'),
					'options_action_id_meta_key'	=>	'action_id',
					'options_action_api_populate'	=>	'lists',
					'default'						=>	'signon'
				),

				// Secure cookies
				'action_' . $this->id . '_secure_cookie'	=> array(

					'label'						=>	__('Secure Cookies?', 'ws-form-user'),
					'type'						=>	'checkbox',
					'default'					=>	is_ssl() ? 'on' : '',
					'condition'					=>	array(

						array(

							'logic'			=>	'==',
							'meta_key'		=>	'action_' . $this->id . '_list_id',
							'meta_value'	=>	'login'
						)
					)
				),

				// Send user notification
				'action_' . $this->id . '_send_user_notification'	=> array(

					'label'						=>	__('Send Email Notification', 'ws-form'),
					'type'						=>	'select',
					'default'					=>	'admin',
					'options'					=>	array(

						array('value' => '', 'text' => __('None', 'ws-form')),
						array('value' => 'admin', 'text' => __('Administrator', 'ws-form')),
						array('value' => 'user', 'text' => __('User', 'ws-form')),
						array('value' => 'both', 'text' => __('User and Administrator', 'ws-form'))
					),
					'condition'					=>	array(

						array(

							'logic'			=>	'==',
							'meta_key'		=>	'action_' . $this->id . '_list_id',
							'meta_value'	=>	'register'
						)
					)
				),

				// Create password
				'action_' . $this->id . '_password_create'	=> array(

					'label'						=>	__('Create Password (If blank)', 'ws-form'),
					'type'						=>	'checkbox',
					'default'					=>	'',
					'condition'					=>	array(

						array(

							'logic'			=>	'==',
							'meta_key'		=>	'action_' . $this->id . '_list_id',
							'meta_value'	=>	'register'
						)
					)
				),

				// Create password - Length
				'action_' . $this->id . '_password_length'	=> array(

					'label'						=>	__('Password Length', 'ws-form'),
					'type'						=>	'number',
					'default'					=>	self::WS_FORM_PASSWORD_LENGTH_DEFAULT,
					'condition'					=>	array(

						array(

							'logic'			=>	'==',
							'meta_key'		=>	'action_' . $this->id . '_list_id',
							'meta_value'	=>	'register'
						),

						array(

							'logic_previous'	=>	'&&',
							'logic'				=>	'==',
							'meta_key'			=>	'action_' . $this->id . '_password_create',
							'meta_value'		=>	'on'
						)
					)					
				),

				// Create password - Special characters
				'action_' . $this->id . '_password_special_characters'	=> array(

					'label'						=>	__('Use Special Characters', 'ws-form'),
					'type'						=>	'checkbox',
					'default'					=>	'',
					'condition'					=>	array(

						array(

							'logic'			=>	'==',
							'meta_key'		=>	'action_' . $this->id . '_list_id',
							'meta_value'	=>	'register'
						),

						array(

							'logic_previous'	=>	'&&',
							'logic'				=>	'==',
							'meta_key'			=>	'action_' . $this->id . '_password_create',
							'meta_value'		=>	'on'
						)
					)					
				),

				// Disable the visual editor when writing
				'action_' . $this->id . '_rich_editing'	=> array(

					'label'						=>	__('Disable the Visual Editor When Writing', 'ws-form'),
					'type'						=>	'checkbox',
					'default'					=>	'',
					'condition'					=>	array(

						array(

							'logic'			=>	'==',
							'meta_key'		=>	'action_' . $this->id . '_list_id',
							'meta_value'	=>	'register'
						)
					)
				),

				// Disable syntax highlighting when editing code
				'action_' . $this->id . '_syntax_highlighting'	=> array(

					'label'						=>	__('Disable Syntax Highlighting When Editing Code', 'ws-form'),
					'type'						=>	'checkbox',
					'default'					=>	'',
					'condition'					=>	array(

						array(

							'logic'			=>	'==',
							'meta_key'		=>	'action_' . $this->id . '_list_id',
							'meta_value'	=>	'register'
						)
					)
				),

				// Enable keyboard shortcuts for comment moderation.
				'action_' . $this->id . '_comment_shortcuts'	=> array(

					'label'						=>	__('Enable Keyboard Shortcuts for Comment Moderation', 'ws-form'),
					'type'						=>	'checkbox',
					'default'					=>	'',
					'condition'					=>	array(

						array(

							'logic'			=>	'==',
							'meta_key'		=>	'action_' . $this->id . '_list_id',
							'meta_value'	=>	'register'
						)
					)
				),

				// Show toolbar when viewing site
				'action_' . $this->id . '_show_admin_bar_front'	=> array(

					'label'						=>	__('Show Toolbar When Viewing Site', 'ws-form'),
					'type'						=>	'checkbox',
					'default'					=>	'',
					'condition'					=>	array(

						array(

							'logic'			=>	'==',
							'meta_key'		=>	'action_' . $this->id . '_list_id',
							'meta_value'	=>	'register'
						)
					)
				),

				// Admin Color Scheme
				'action_' . $this->id . '_admin_color'	=> array(

					'label'						=>	__('Admin Color Scheme', 'ws-form-user'),
					'type'						=>	'select',
					'options'					=>	array(

						array('value' => 'fresh', 'text' => __('Default', 'ws-form-user')),
						array('value' => 'light', 'text' => __('Light', 'ws-form-user')),
						array('value' => 'blue', 'text' => __('Blue', 'ws-form-user')),
						array('value' => 'coffee', 'text' => __('Coffee', 'ws-form-user')),
						array('value' => 'ectoplasm', 'text' => __('Ectoplasm', 'ws-form-user')),
						array('value' => 'midnight', 'text' => __('Midnight', 'ws-form-user')),
						array('value' => 'ocean', 'text' => __('Ocean', 'ws-form-user')),
						array('value' => 'sunrise', 'text' => __('Sunrise', 'ws-form-user'))
					),
					'default'					=>	self::WS_FORM_ADMIN_COLOR_DEFAULT,
					'condition'					=>	array(

						array(

							'logic'			=>	'==',
							'meta_key'		=>	'action_' . $this->id . '_list_id',
							'meta_value'	=>	'register'
						)
					)
				),

				// Field mapping
				'action_' . $this->id . '_field_mapping'	=> array(

					'label'						=>	__('Field Mapping', 'ws-form-user'),
					'type'						=>	'repeater',
					'help'						=>	__('Map WS Form fields to user fields.', 'ws-form-user'),
					'meta_keys'					=>	array(

						'ws_form_field',
						'action_' . $this->id . '_list_fields'
					),
					'meta_keys_unique'			=>	array(

						'action_' . $this->id . '_list_fields'
					),
					'auto_map'					=>	true,
					'condition'					=>	array(

						array(

							'logic'			=>	'!=',
							'meta_key'		=>	'action_' . $this->id . '_list_id',
							'meta_value'	=>	''
						),

						array(

							'logic_previous'	=>	'&&',
							'logic'				=>	'!=',
							'meta_key'			=>	'action_' . $this->id . '_list_id',
							'meta_value'		=>	'logout'
						)
					)
				),

				// Meta mapping
				'action_' . $this->id . '_meta_mapping'	=> array(

					'label'						=>	__('Meta Mapping', 'ws-form-user'),
					'type'						=>	'repeater',
					'help'						=>	__('Map WS Form fields to user meta fields.', 'ws-form-user'),
					'meta_keys'					=>	array(

						'ws_form_field',
						'action_' . $this->id . '_meta_key'
					),
					'condition'					=>	array(

						array(

							'logic'				=>	'==',
							'meta_key'			=>	'action_' . $this->id . '_list_id',
							'meta_value'		=>	'register'
						),

						array(
							'logic_previous'	=>	'||',
							'logic'				=>	'==',
							'meta_key'			=>	'action_' . $this->id . '_list_id',
							'meta_value'		=>	'update'
						)
					)
				),

				// Custom meta mapping
				'action_' . $this->id . '_meta_mapping_custom'	=> array(

					'label'						=>	__('Custom Meta Mapping', 'ws-form-user'),
					'type'						=>	'repeater',
					'help'						=>	__('Map custom values to meta keys.', 'ws-form-user'),
					'meta_keys'					=>	array(

						'action_' . $this->id . '_meta_key',
						'action_' . $this->id . '_meta_value'
					),
					'condition'					=>	array(

						array(

							'logic'				=>	'==',
							'meta_key'			=>	'action_' . $this->id . '_list_id',
							'meta_value'		=>	'register'
						),

						array(
							'logic_previous'	=>	'||',
							'logic'				=>	'==',
							'meta_key'			=>	'action_' . $this->id . '_list_id',
							'meta_value'		=>	'update'
						)
					)
				),

				// Meta field
				'action_' . $this->id . '_meta_key'	=> array(

					'label'						=>	__('Meta Field', 'ws-form-user'),
					'type'						=>	'text'
				),

				// Meta value
				'action_' . $this->id . '_meta_value'	=> array(

					'label'						=>	__('Meta Value', 'ws-form-user'),
					'type'						=>	'text'
				),

				// List fields
				'action_' . $this->id . '_list_fields'	=> array(

					'label'							=>	__('User Field', 'ws-form-user'),
					'type'							=>	'select',
					'options'						=>	'action_api_populate',
					'options_blank'					=>	__('Select...', 'ws-form-user'),
					'options_action_id'				=>	$this->id,
					'options_list_id_meta_key'		=>	'action_' . $this->id . '_list_id',
					'options_action_api_populate'	=>	'list_fields'
				),

				// Role
				'action_' . $this->id . '_role'	=> array(

					'label'						=>	__('Role', 'ws-form-user'),
					'type'						=>	'select',
					'help'						=>	__('Role user will be assigned to.', 'ws-form-user'),
					'options'					=>	array(),
					'default'					=>	get_option('default_role'),
					'condition'					=>	array(

						array(

							'logic'			=>	'==',
							'meta_key'		=>	'action_' . $this->id . '_list_id',
							'meta_value'	=>	'register'
						)
					)
				),

				// Form auto-populate

				// Field mapping
				'action_' . $this->id . '_form_populate_field_mapping'	=> array(

					'label'						=>	__('Field Mapping', 'ws-form'),
					'type'						=>	'repeater',
					'help'						=>	__('Map user fields to WS Form fields', 'ws-form'),
					'meta_keys'					=>	array(

						'action_' . $this->id . '_form_populate_field',
						'ws_form_field_edit'
					),
					'meta_keys_unique'			=>	array(

						'ws_form_field_edit'
					),
					'condition'					=>	array(

						array(

							'logic'				=>	'==',
							'meta_key'			=>	'form_populate_enabled',
							'meta_value'		=>	'on'
						),

						array(

							'logic'				=>	'==',
							'meta_key'			=>	'form_populate_action_id',
							'meta_value'		=>	'user',
							'logic_previous'	=>	'&&'
						)
					)
				),

				// User fields
				'action_' . $this->id . '_form_populate_field'	=> array(

					'label'							=>	__('User Field', 'ws-form-user'),
					'type'							=>	'select',
					'options'						=>	array(

						array('value' => 'user_id', 'text' => __('ID', 'ws-form-user')),
						array('value' => 'user_login', 'text' => __('Username', 'ws-form-user')),
						array('value' => 'user_first_name', 'text' => __('First Name', 'ws-form-user')),
						array('value' => 'user_last_name', 'text' => __('Last Name', 'ws-form-user')),
						array('value' => 'user_display_name', 'text' => __('Display Name', 'ws-form-user')),
						array('value' => 'user_nicename', 'text' => __('Nice Name', 'ws-form-user')),
						array('value' => 'user_nickname', 'text' => __('Nickname', 'ws-form-user')),
						array('value' => 'user_email', 'text' => __('Email', 'ws-form-user')),
						array('value' => 'user_url', 'text' => __('Website', 'ws-form-user')),
						array('value' => 'user_registered', 'text' => __('Registered Date', 'ws-form-user')),
						array('value' => 'user_description', 'text' => __('Biographical Info', 'ws-form-user')),
						array('value' => 'user_rich_editing', 'text' => __('Visual Editor', 'ws-form-user')),
						array('value' => 'user_syntax_highlighting', 'text' => __('Syntax Highlighting', 'ws-form-user')),
						array('value' => 'user_comment_shortcuts', 'text' => __('Keyboard Shortcuts', 'ws-form-user')),
						array('value' => 'user_show_admin_bar_front', 'text' => __('Toolbar', 'ws-form-user')),
						array('value' => 'user_admin_color', 'text' => __('Admin Color Scheme', 'ws-form-user')),
					),
					'options_blank'					=>	__('Select...', 'ws-form-user')
				),

				// Meta mapping
				'action_' . $this->id . '_form_populate_meta_mapping'	=> array(

					'label'						=>	__('Meta Mapping', 'ws-form-user'),
					'type'						=>	'repeater',
					'help'						=>	__('Map user meta key values to WS Form fields.', 'ws-form-user'),
					'meta_keys'					=>	array(

						'action_' . $this->id . '_meta_key',
						'ws_form_field'
					),
					'meta_keys_unique'			=>	array(

						'ws_form_field'
					),
					'condition'	=>	array(

						array(

							'logic'			=>	'==',
							'meta_key'		=>	'form_populate_action_id',
							'meta_value'	=>	'user'
						),

						array(

							'logic'			=>	'==',
							'meta_key'		=>	'form_populate_enabled',
							'meta_value'	=>	'on'
						)
					)
				)
			);

			// Do not show list selector for user (Auto populate)
			$meta_keys['form_populate_list_id']['condition'][] = array(

				'logic'				=>	'!=',
				'meta_key'			=>	'form_populate_action_id',
				'meta_value'		=>	'user',
				'logic_previous'	=>	'&&'
			);

			// Do not show regular field mapping selector for user (Auto populate)
			$meta_keys['form_populate_field_mapping']['condition'][] = array(

				'logic'				=>	'!=',
				'meta_key'			=>	'form_populate_action_id',
				'meta_value'		=>	'user',
				'logic_previous'	=>	'&&'
			);

			// Add user roles
			$all_roles = wp_roles()->roles;
			$user = wp_get_current_user();
			if($user) {

				$next_level = sprintf('level_%u', (intval($user->user_level) + 1));
				foreach ($all_roles as $name => $role) {
					if(!isset($role['capabilities'][$next_level])) {
						$config_meta_keys['action_' . $this->id . '_role']['options'][] = array('value' => $name, 'text' => $role['name']);
					}
				}
			}

			// WooCommerce
			if($this->woocommerce_activated) {

				$woocommerce_fields = is_admin() ? WS_Form_WooCommerce::woocommerce_get_fields_all('user', false, false, true, false, false) : array();

				foreach($woocommerce_fields as $meta_key => $woocommerce_field) {

					$config_meta_keys['action_' . $this->id . '_form_populate_field']['options'][] = array('value' => $meta_key, 'text' => $woocommerce_field['label']);
				}
			}

			// ACF
			if($this->acf_activated) {
			
				// ACF - Fields
				$config_meta_keys['action_' . $this->id . '_acf_key'] = array(

					'label'							=>	__('ACF Field', 'ws-form-user'),
					'type'							=>	'select',
					'options'						=>	is_admin() ? WS_Form_ACF::acf_get_fields_all(false, false, false, true) : array(),
					'options_blank'					=>	__('Select...', 'ws-form-user')
				);

				// ACF - Field mapping
				$config_meta_keys['action_' . $this->id . '_field_mapping_acf'] = array(

					'label'						=>	__('ACF Field Mapping', 'ws-form-user'),
					'type'						=>	'repeater',
					'help'						=>	__('Map WS Form fields to ACF fields.', 'ws-form-user'),
					'meta_keys'					=>	array(

						'ws_form_field',
						'action_' . $this->id . '_acf_key'
					),
					'meta_keys_unique'			=>	array(

						'action_' . $this->id . '_acf_key'
					),
					'condition'					=>	array(

						array(

							'logic'			=>	'!=',
							'meta_key'		=>	'action_' . $this->id . '_list_id',
							'meta_value'	=>	''
						)
					)
				);

				// ACF - Validation 
				$config_meta_keys['action_' . $this->id . '_acf_validation'] = array(

					'label'						=>	__('Process ACF Validation', 'ws-form'),
					'type'						=>	'checkbox',
					'help'						=>	__('Enabling this will process ACF validation filters when the form is submitted.', 'ws-form'),
					'default'					=>	'on',
					'condition'					=>	array(

						array(

							'logic'			=>	'!=',
							'meta_key'		=>	'action_' . $this->id . '_list_id',
							'meta_value'	=>	''
						)
					)
				);

				// Populate - ACF - Field mapping
				$config_meta_keys['action_' . $this->id . '_form_populate_field_mapping_acf'] = array(

					'label'						=>	__('ACF Field Mapping', 'ws-form-user'),
					'type'						=>	'repeater',
					'help'						=>	__('Map ACF field values to WS Form fields.', 'ws-form-user'),
					'meta_keys'					=>	array(

						'action_' . $this->id . '_acf_key',
						'ws_form_field'
					),
					'meta_keys_unique'			=>	array(

						'ws_form_field'
					),
					'condition'	=>	array(

						array(

							'logic'			=>	'==',
							'meta_key'		=>	'form_populate_action_id',
							'meta_value'	=>	'user'
						),

						array(

							'logic'			=>	'==',
							'meta_key'		=>	'form_populate_enabled',
							'meta_value'	=>	'on'
						)
					)
				);
			}

			// Meta Box
			if($this->meta_box_activated) {

				// Meta Box - Fields
				$config_meta_keys['action_' . $this->id . '_meta_box_field_id'] = array(

					'label'							=>	__('Meta Box Field', 'ws-form-user'),
					'type'							=>	'select',
					'options'						=>	is_admin() ? WS_Form_Meta_Box::meta_box_get_fields_all('user', false, false, false, true, false) : array(),
					'options_blank'					=>	__('Select...', 'ws-form-user')
				);

				// Meta Box - Field mapping
				$config_meta_keys['action_' . $this->id . '_field_mapping_meta_box'] = array(

					'label'						=>	__('Meta Box Field Mapping', 'ws-form-user'),
					'type'						=>	'repeater',
					'help'						=>	__('Map WS Form fields to Meta Box fields.', 'ws-form-user'),
					'meta_keys'					=>	array(

						'ws_form_field',
						'action_' . $this->id . '_meta_box_field_id'
					),
					'meta_keys_unique'			=>	array(

						'action_' . $this->id . '_meta_box_field_id'
					),
					'condition'					=>	array(

						array(

							'logic'			=>	'!=',
							'meta_key'		=>	'action_' . $this->id . '_list_id',
							'meta_value'	=>	''
						)
					)
				);

				// Populate - Meta Box - Field mapping
				$config_meta_keys['action_' . $this->id . '_form_populate_field_mapping_meta_box'] = array(

					'label'						=>	__('Meta Box Field Mapping', 'ws-form-user'),
					'type'						=>	'repeater',
					'help'						=>	__('Map Meta Box field values to WS Form fields.', 'ws-form-user'),
					'meta_keys'					=>	array(

						'action_' . $this->id . '_meta_box_field_id',
						'ws_form_field'
					),
					'meta_keys_unique'			=>	array(

						'ws_form_field'
					),
					'condition'	=>	array(

						array(

							'logic'			=>	'==',
							'meta_key'		=>	'form_populate_action_id',
							'meta_value'	=>	'user'
						),

						array(

							'logic'			=>	'==',
							'meta_key'		=>	'form_populate_enabled',
							'meta_value'	=>	'on'
						)
					)
				);
			}

			// Pods
			if($this->pods_activated) {

				// Pods - Fields
				$config_meta_keys['action_' . $this->id . '_pods_field_id'] = array(

					'label'							=>	__('Pods Field', 'ws-form-user'),
					'type'							=>	'select',
					'options'						=>	is_admin() ? WS_Form_Pods::pods_get_fields_all('user', false, false, false, true, false) : array(),
					'options_blank'					=>	__('Select...', 'ws-form-user')
				);

				// Pods - Field mapping
				$config_meta_keys['action_' . $this->id . '_field_mapping_pods'] = array(

					'label'						=>	__('Pods Field Mapping', 'ws-form-user'),
					'type'						=>	'repeater',
					'help'						=>	__('Map WS Form fields to Pods fields.', 'ws-form-user'),
					'meta_keys'					=>	array(

						'ws_form_field',
						'action_' . $this->id . '_pods_field_id'
					),
					'meta_keys_unique'			=>	array(

						'action_' . $this->id . '_pods_field_id'
					),
					'condition'					=>	array(

						array(

							'logic'			=>	'!=',
							'meta_key'		=>	'action_' . $this->id . '_list_id',
							'meta_value'	=>	''
						)
					)
				);

				// Populate - Pods - Field mapping
				$config_meta_keys['action_' . $this->id . '_form_populate_field_mapping_pods'] = array(

					'label'						=>	__('Pods Field Mapping', 'ws-form-user'),
					'type'						=>	'repeater',
					'help'						=>	__('Map Pods field values to WS Form fields.', 'ws-form-user'),
					'meta_keys'					=>	array(

						'action_' . $this->id . '_pods_field_id',
						'ws_form_field'
					),
					'meta_keys_unique'			=>	array(

						'ws_form_field'
					),
					'condition'	=>	array(

						array(

							'logic'			=>	'==',
							'meta_key'		=>	'form_populate_action_id',
							'meta_value'	=>	'user'
						),

						array(

							'logic'			=>	'==',
							'meta_key'		=>	'form_populate_enabled',
							'meta_value'	=>	'on'
						)
					)
				);
			}

			// Toolset
			if($this->toolset_activated) {
			
				// Toolset - Fields
				$config_meta_keys['action_' . $this->id . '_toolset_field_slug'] = array(

					'label'							=>	__('Toolset Field', 'ws-form-user'),
					'type'							=>	'select',
					'options'						=>	is_admin() ? WS_Form_Toolset::toolset_get_fields_all(array('domain' => Toolset_Element_Domain::USERS), false, false, true, false) : array(),
					'options_blank'					=>	__('Select...', 'ws-form-user')
				);

				// Toolset - Field mapping
				$config_meta_keys['action_' . $this->id . '_field_mapping_toolset'] = array(

					'label'						=>	__('Toolset Field Mapping', 'ws-form-user'),
					'type'						=>	'repeater',
					'help'						=>	__('Map WS Form fields to Toolset fields.', 'ws-form-user'),
					'meta_keys'					=>	array(

						'ws_form_field',
						'action_' . $this->id . '_toolset_field_slug'
					),
					'meta_keys_unique'			=>	array(

						'action_' . $this->id . '_toolset_field_slug'
					),
					'condition'					=>	array(

						array(

							'logic'			=>	'!=',
							'meta_key'		=>	'action_' . $this->id . '_list_id',
							'meta_value'	=>	''
						)
					)
				);

				// Populate - Toolset - Field mapping
				$config_meta_keys['action_' . $this->id . '_form_populate_field_mapping_toolset'] = array(

					'label'						=>	__('Toolset Field Mapping', 'ws-form-user'),
					'type'						=>	'repeater',
					'help'						=>	__('Map Toolset field values to WS Form fields.', 'ws-form-user'),
					'meta_keys'					=>	array(

						'action_' . $this->id . '_toolset_field_slug',
						'ws_form_field'
					),
					'meta_keys_unique'			=>	array(

						'ws_form_field'
					),
					'condition'	=>	array(

						array(

							'logic'			=>	'==',
							'meta_key'		=>	'form_populate_action_id',
							'meta_value'	=>	'user'
						),

						array(

							'logic'			=>	'==',
							'meta_key'		=>	'form_populate_enabled',
							'meta_value'	=>	'on'
						)
					)
				);
			}

			// JetEngine
			if($this->jetengine_activated) {
			
				// JetEngine - Field mapping
				$config_meta_keys['action_' . $this->id . '_field_mapping_jetengine'] = array(

					'label'						=>	__('JetEngine Field Mapping', 'ws-form-user'),
					'type'						=>	'repeater',
					'help'						=>	__('Map WS Form fields to JetEngine fields.', 'ws-form-user'),
					'meta_keys'					=>	array(

						'ws_form_field',
						'action_' . $this->id . '_jetengine_field_name'
					),
					'meta_keys_unique'			=>	array(

						'action_' . $this->id . '_jetengine_field_name'
					),
					'condition'					=>	array(

						array(

							'logic'			=>	'!=',
							'meta_key'		=>	'action_' . $this->id . '_list_id',
							'meta_value'	=>	''
						)
					)
				);

				// Populate - JetEngine - Field mapping
				$config_meta_keys['action_' . $this->id . '_form_populate_field_mapping_jetengine'] = array(

					'label'						=>	__('JetEngine Field Mapping', 'ws-form-user'),
					'type'						=>	'repeater',
					'help'						=>	__('Map JetEngine field values to WS Form fields.', 'ws-form-user'),
					'meta_keys'					=>	array(

						'action_' . $this->id . '_jetengine_field_name',
						'ws_form_field'
					),
					'meta_keys_unique'			=>	array(

						'ws_form_field'
					),
					'condition'	=>	array(

						array(

							'logic'			=>	'==',
							'meta_key'		=>	'form_populate_action_id',
							'meta_value'	=>	'user'
						),

						array(

							'logic'			=>	'==',
							'meta_key'		=>	'form_populate_enabled',
							'meta_value'	=>	'on'
						)
					)
				);

				// JetEngine - Relations
				$config_meta_keys['action_' . $this->id . '_jetengine_relations'] = array(

					'label'						=>	__('JetEngine Relations', 'ws-form-user'),
					'type'						=>	'repeater',
					'help'						=>	__('Set a JetEngine relation parent or child value to a field value.', 'ws-form-user'),
					'meta_keys'					=>	array(

						'ws_form_field',
						'action_' . $this->id . '_jetengine_relation_id',
						'action_' . $this->id . '_jetengine_relation_context',
						'action_' . $this->id . '_jetengine_relation_replace',
					),
					'meta_keys_unique'			=>	array(

						'action_' . $this->id . '_jetengine_field_name'
					),
					'condition'					=>	array(

						array(

							'logic'			=>	'!=',
							'meta_key'		=>	'action_' . $this->id . '_list_id',
							'meta_value'	=>	''
						)
					)
				);

				// JetEngine - Relations
				$config_meta_keys['action_' . $this->id . '_jetengine_relations_populate'] = array(

					'label'						=>	__('JetEngine Relations', 'ws-form-user'),
					'type'						=>	'repeater',
					'help'						=>	__('Set a field to the value of a JetEngine relation parent or child value.', 'ws-form-user'),
					'meta_keys'					=>	array(

						'action_' . $this->id . '_jetengine_relation_id',
						'action_' . $this->id . '_jetengine_relation_context',
						'ws_form_field'
					),
					'meta_keys_unique'			=>	array(

						'action_' . $this->id . '_jetengine_field_name'
					),
					'condition'	=>	array(

						array(

							'logic'			=>	'==',
							'meta_key'		=>	'form_populate_action_id',
							'meta_value'	=>	'user'
						),

						array(

							'logic'			=>	'==',
							'meta_key'		=>	'form_populate_enabled',
							'meta_value'	=>	'on'
						)
					)
				);

				// JetEngine - Relations - ID
				$config_meta_keys['action_' . $this->id . '_jetengine_relation_id'] = array(

					'label'							=>	__('Relation', 'ws-form-user'),
					'type'							=>	'select',
					'options'						=>	array()
				);

				// JetEngine - Relations - Context
				$config_meta_keys['action_' . $this->id . '_jetengine_relation_context'] = array(

					'label'							=>	__('Context', 'ws-form-user'),
					'type'							=>	'select',
					'options'						=>	array(

						array('value' => 'parent', 'text' => __('Parent', 'ws-form-user')),
						array('value' => 'child', 'text' => __('Child', 'ws-form-user'))
					),
					'default'						=>	'child'
				);

				// JetEngine - Relations - Replace
				$config_meta_keys['action_' . $this->id . '_jetengine_relation_replace'] = array(

					'label'						=>	__('Replace', 'ws-form-user'),
					'type'						=>	'select',
					'options'					=>	array(

						array('value' => '', 'text' => __('No', 'ws-form-user')),
						array('value' => 'on', 'text' => __('Yes', 'ws-form-user'))
					),
					'default'					=>	'on'
				);

				// JetEngine - Fields
				$config_meta_keys['action_' . $this->id . '_jetengine_field_name'] = array(

					'label'							=>	__('JetEngine Field', 'ws-form-user'),
					'type'							=>	'select',
					'options'						=>	is_admin() ? WS_Form_JetEngine::jetengine_get_fields_all('user', null, false, false, true, false) : array(),
					'options_blank'					=>	__('Select...', 'ws-form-user')
				);

				// JetEngine - Populate relations
				if(is_admin()) {

					$relations = jet_engine()->relations->get_active_relations();

					if(is_array($relations)) {

						foreach($relations as $rel_id => $rel) {

							if(
								jet_engine()->relations->types_helper->object_is($rel->get_args('parent_object'), 'mix', 'users') ||
								jet_engine()->relations->types_helper->object_is($rel->get_args('child_object'), 'mix', 'users')
							) {
								$config_meta_keys['action_' . $this->id . '_jetengine_relation_id']['options'][] = array(

									'value' => $rel_id,
									'text' => $rel->get_relation_name()
								);
							}
						}
					}
				}
			}

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

						'label'		=>	__('Add-on Version', 'ws-form-user'),
						'type'		=>	'static'
					),

					'action_' . $this->id . '_license_key'	=>	array(

						'label'		=>	__('Add-on License Key', 'ws-form-user'),
						'type'		=>	'text',
						'help'		=>	__('Enter your User Management add-on for WS Form PRO license key here.', 'ws-form-user'),
						'button'	=>	'license_action_' . $this->id,
						'action'	=>	$this->id
					),

					'action_' . $this->id . '_license_status'	=>	array(

						'label'		=>	__('Add-on License Status', 'ws-form-user'),
						'type'		=>	'static'
					),
				)
			);

			return $options;
		}

		public function config_settings_form_admin($config_settings_form_admin) {

			if(!isset($config_settings_form_admin['sidebars']['form']['meta']['fieldsets']['action'])) { return $config_settings_form_admin; }

			$meta_keys = $config_settings_form_admin['sidebars']['form']['meta']['fieldsets']['action']['fieldsets'][0]['meta_keys'];

			// Add user field mapping
			self::meta_key_inject($meta_keys, 'action_' . $this->id . '_form_populate_field_mapping', 'form_populate_field_mapping');

			// Add ACF field mapping
			if($this->acf_activated) {

				self::meta_key_inject($meta_keys, 'action_' . $this->id . '_form_populate_field_mapping_acf', 'form_populate_tag_mapping');
			}

			// Add Meta Box field mapping
			if($this->meta_box_activated) {

				self::meta_key_inject($meta_keys, 'action_' . $this->id . '_form_populate_field_mapping_meta_box', 'form_populate_tag_mapping');
			}

			// Add Pods field mapping
			if($this->pods_activated) {

				self::meta_key_inject($meta_keys, 'action_' . $this->id . '_form_populate_field_mapping_pods', 'form_populate_tag_mapping');
			}

			// Add Toolset field mapping
			if($this->toolset_activated) {

				self::meta_key_inject($meta_keys, 'action_' . $this->id . '_form_populate_field_mapping_toolset', 'form_populate_tag_mapping');
			}

			// Add JetEngine field mapping
			if($this->jetengine_activated) {

				self::meta_key_inject($meta_keys, 'action_' . $this->id . '_form_populate_field_mapping_jetengine', 'form_populate_tag_mapping');
				self::meta_key_inject($meta_keys, 'action_' . $this->id . '_jetengine_relations_populate', 'form_populate_tag_mapping');
			}

			// Add user meta key mapping
			self::meta_key_inject($meta_keys, 'action_' . $this->id . '_form_populate_meta_mapping', 'form_populate_tag_mapping');

			$config_settings_form_admin['sidebars']['form']['meta']['fieldsets']['action']['fieldsets'][0]['meta_keys'] = $meta_keys;

			return $config_settings_form_admin;
		}

		// Inject a meta key
		public function meta_key_inject(&$meta_keys, $insert_this, $insert_before = false) {

			$key = ($insert_before !== false) ? array_search($insert_before, $meta_keys) : false;

			if($key !== false) {

				$meta_keys = 

					array_merge(

						array_values(array_slice($meta_keys, 0, $key, true)),
						array($insert_this),
						array_values(array_slice($meta_keys, $key, count($meta_keys) - 1, true))
					);

			} else {

				$meta_keys = array_merge(array_values($meta_keys), array($insert_this));
			}
		}

		// Process wp_error_process
		public function wp_error_process($user) {

			$error_messages = $user->get_error_messages();
			self::error_js($error_messages);
		}

		// Error
		public function error_js($error_messages) {

			if(!is_array($error_messages)) { $error_messages = array($error_messages); }

			foreach($error_messages as $error_message) {

				// Show the message
				parent::error($error_message);
			}
		}

		// Load config for this action
		public function load_config($config = array()) {

			if($this->list_id === false) { $this->list_id = parent::get_config($config, 'action_' . $this->id . '_list_id'); }
			$this->secure_cookie = parent::get_config($config, 'action_' . $this->id . '_secure_cookie', '');
			$this->show_admin_bar_front = parent::get_config($config, 'action_' . $this->id . '_show_admin_bar_front', '');
			$this->rich_editing = parent::get_config($config, 'action_' . $this->id . '_rich_editing', '');
			$this->syntax_highlighting = parent::get_config($config, 'action_' . $this->id . '_syntax_highlighting', '');
			$this->comment_shortcuts = parent::get_config($config, 'action_' . $this->id . '_comment_shortcuts', '');
			$this->admin_color = parent::get_config($config, 'action_' . $this->id . '_admin_color', '');

			// Field mapping
			$this->field_mapping = parent::get_config($config, 'action_' . $this->id . '_field_mapping', array());
			if(!is_array($this->field_mapping)) { $this->field_mapping = array(); }

			// Field mapping - ACF
			if($this->acf_activated) {

				$this->field_mapping_acf = parent::get_config($config, 'action_' . $this->id . '_field_mapping_acf', array());
				if(!is_array($this->field_mapping_acf)) { $this->field_mapping_acf = array(); }
				$this->acf_validation = parent::get_config($config, 'action_' . $this->id . '_acf_validation', 'on');
			}

			// Field mapping - Meta Box
			if($this->meta_box_activated) {

				$this->field_mapping_meta_box = parent::get_config($config, 'action_' . $this->id . '_field_mapping_meta_box', array());
				if(!is_array($this->field_mapping_meta_box)) { $this->field_mapping_meta_box = array(); }
			}

			// Field mapping - Pods
			if($this->pods_activated) {

				$this->field_mapping_pods = parent::get_config($config, 'action_' . $this->id . '_field_mapping_pods', array());
				if(!is_array($this->field_mapping_pods)) { $this->field_mapping_pods = array(); }
			}

			// Field mapping - Toolset
			if($this->toolset_activated) {

				$this->field_mapping_toolset = parent::get_config($config, 'action_' . $this->id . '_field_mapping_toolset', array());
				if(!is_array($this->field_mapping_toolset)) { $this->field_mapping_toolset = array(); }
			}

			// Field mapping - JetEngine
			if($this->jetengine_activated) {

				$this->field_mapping_jetengine = parent::get_config($config, 'action_' . $this->id . '_field_mapping_jetengine', array());
				if(!is_array($this->field_mapping_jetengine)) { $this->field_mapping_jetengine = array(); }
				$this->jetengine_relations = parent::get_config($config, 'action_' . $this->id . '_jetengine_relations', array());
				if(!is_array($this->jetengine_relations)) { $this->jetengine_relations = array(); }
			}

			// Meta mapping
			$this->meta_mapping = parent::get_config($config, 'action_' . $this->id . '_meta_mapping', array());
			if(!is_array($this->meta_mapping)) { $this->meta_mapping = array(); }

			// Custom meta mapping
			$this->meta_mapping_custom = parent::get_config($config, 'action_' . $this->id . '_meta_mapping_custom', array());
			if(!is_array($this->meta_mapping_custom)) { $this->meta_mapping_custom = array(); }

			$this->role = parent::get_config($config, 'action_' . $this->id . '_role', get_option('default_role'));
			$this->send_user_notification = parent::get_config($config, 'action_' . $this->id . '_send_user_notification', 'admin');
			$this->password_create = parent::get_config($config, 'action_' . $this->id . '_password_create', '');
			$this->password_length = parent::get_config($config, 'action_' . $this->id . '_password_length', self::WS_FORM_PASSWORD_LENGTH_DEFAULT);
			$this->password_special_characters = parent::get_config($config, 'action_' . $this->id . '_password_special_characters', '');
			$this->role = parent::get_config($config, 'action_' . $this->id . '_role', get_option('default_role'));
			$this->role = parent::get_config($config, 'action_' . $this->id . '_role', get_option('default_role'));
		}

		// Load config at plugin level
		public function load_config_plugin() {

			$this->configured = true;
			return $this->configured;
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
			$list_fields = self::get_list_fields(false, true, false, false, false, false, false);

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
			$list_fields = self::get_list_fields(true, true, false, false, false, false, false);

			// Process response
			self::api_response($list_fields);
		}

		// SVG Logo - Color (Used for the 'Add Form' page)
		public function get_svg_logo_color($list_id = false) {

			// Template SVG: 140 x 180
			$svg_logo = '<g transform="translate(45.000000, 62.000000)"><path fill="#0077a1" d="M25 1.5a23.3 23.3 0 0 1 16.62 6.89 23.4 23.4 0 0 1 5.04 7.47 23.45 23.45 0 0 1-2.17 22.29 23.91 23.91 0 0 1-6.35 6.35 23.45 23.45 0 0 1-26.28 0 23.91 23.91 0 0 1-6.35-6.35A23.56 23.56 0 0 1 15.86 3.34 23.4 23.4 0 0 1 25 1.5M25 0a25 25 0 1 0 0 50 25 25 0 0 0 0-50z"/><path fill="#0077a1" d="M4.17 25c0 8.25 4.79 15.37 11.74 18.75L5.97 16.52A20.69 20.69 0 0 0 4.17 25zm34.89-1.05a11 11 0 0 0-1.72-5.75c-1.06-1.72-2.05-3.17-2.05-4.89 0-1.91 1.45-3.7 3.5-3.7l.27.02a20.8 20.8 0 0 0-31.47 3.93l1.34.03c2.18 0 5.55-.26 5.55-.26 1.12-.07 1.26 1.58.13 1.72 0 0-1.13.13-2.38.2l7.59 22.57 4.56-13.67-3.25-8.89c-1.12-.07-2.19-.2-2.19-.2-1.12-.07-.99-1.78.13-1.72 0 0 3.44.26 5.49.26 2.18 0 5.55-.26 5.55-.26 1.12-.07 1.26 1.58.13 1.72 0 0-1.13.13-2.38.2l7.53 22.39 2.15-6.81c.96-3 1.52-5.11 1.52-6.89zm-13.69 2.87l-6.25 18.16a20.81 20.81 0 0 0 12.81-.33 1.32 1.32 0 0 1-.15-.29l-6.41-17.54zM43.28 15c.09.66.14 1.38.14 2.14 0 2.11-.4 4.49-1.58 7.46L35.48 43a20.8 20.8 0 0 0 7.8-28z"/></g>';

			if(!in_array($list_id, array('register', 'update'))) { return $svg_logo; }

			$svg_custom_field_logos = array();

			// WooCommerce
			if($this->woocommerce_activated) {

				$has_fields = WS_Form_WooCommerce::woocommerce_get_fields_all('user', false, false, true, false, true);

				if($has_fields) {

					$svg_custom_field_logos[] = '<path fill="#7e58a4" d="M2 5.3h18c1.1 0 2 .9 2 2v6.8c0 1.1-.9 2-2 2h-6.4l.9 2.2-3.9-2.2H2c-1.1 0-2-.9-2-2V7.3c0-1.1.9-2 2-2z"/><path fill="#ffffff" d="M1.1 7.1c.1-.1.4-.2.6-.2.5 0 .7.2.8.6.2 1.9.5 3.5.9 4.8l2-3.7c.1-.4.3-.6.6-.6.4 0 .6.2.7.8.2 1 .5 2.1.9 3.1.2-2.3.6-3.9 1.2-4.9.1-.2.3-.4.6-.4.2 0 .4 0 .6.2.2.1.3.3.3.5 0 .1 0 .3-.1.4-.4.7-.6 1.7-.9 3.2-.3 1.4-.3 2.6-.3 3.4 0 .2 0 .4-.1.6-.1.2-.3.3-.5.3s-.5-.1-.7-.3c-.8-.9-1.5-2.1-2-3.8l-1.3 2.6c-.5 1-1 1.5-1.4 1.6-.2 0-.5-.2-.6-.6-.4-1.3-.9-3.6-1.4-6.9-.1-.3 0-.5.1-.7zM20.4 8.6c-.3-.5-.8-.9-1.4-1-.2 0-.3-.1-.5-.1-.9 0-1.6.4-2.1 1.3-.5.8-.7 1.6-.7 2.5 0 .7.1 1.3.4 1.8.3.5.8.9 1.4 1 .2 0 .3.1.5.1.9 0 1.6-.4 2.1-1.3.5-.8.7-1.6.7-2.5.1-.8-.1-1.4-.4-1.8zm-1.1 2.5c-.1.6-.4 1-.7 1.3-.3.2-.5.3-.7.3-.2 0-.4-.2-.5-.6-.1-.3-.2-.5-.2-.8 0-.2 0-.4.1-.7.1-.4.2-.7.5-1.1.2-.4.6-.6.9-.5.2 0 .4.2.5.6.1.3.2.5.2.8 0 .2-.1.4-.1.7zM14.8 8.6c-.3-.5-.8-.9-1.4-1-.2 0-.3-.1-.5-.1-.9 0-1.6.4-2.1 1.3-.5.8-.7 1.6-.7 2.5 0 .7.1 1.3.4 1.8.3.5.8.9 1.4 1 .2 0 .3.1.5.1.9 0 1.6-.4 2.1-1.3.5-.8.7-1.6.7-2.5 0-.8-.1-1.4-.4-1.8zm-1.1 2.5c-.1.6-.4 1-.7 1.3-.3.2-.5.3-.7.3-.2 0-.4-.2-.5-.6-.1-.3-.2-.5-.2-.8 0-.2 0-.4.1-.7.1-.4.2-.7.5-1.1.2-.4.5-.6.8-.5.2 0 .4.2.5.6.1.3.2.5.2.8v.7z"/>';
				}
			}

			// ACF
			if($this->acf_activated) {

				// Get ACF fields
				$filter = array(

					'user_id' => (($list_id == 'register') ? 'new' : get_current_user_id())
				);
				switch($this->list_id) {

					case 'register' :

						$filter['user_form'] = 'add';
						break;

					case 'update' :

						$filter['user_form'] = 'edit';
						break;
				}

				$has_fields = WS_Form_ACF::acf_get_fields_all($filter, false, true, false, true);

				if($has_fields) {

					$svg_custom_field_logos[] = '<path fill="#47bda1" d="M22 .1v21.7c0 .2 0 .2-.2.2H.3c-.2 0-.3 0-.3-.2V.1h22zM10 14.2c.2 0 .3.1.5.1.6.2 1.2.2 1.8 0 .7-.2 1.3-.6 1.8-1.1v1c0 .2.1.2.2.2h1.3c.2 0 .2 0 .2-.2v-2.1c0-.2 0-.2.2-.2h2.3c.1 0 .2 0 .2-.2v-1.2c0-.2 0-.2-.2-.2h-2.2c-.2 0-.3 0-.3-.2v-.8c0-.2.1-.3.3-.3h2.4c.1 0 .2 0 .2-.2V7.5c0-.1 0-.2-.2-.2h-4.2c-.1 0-.2.1-.2.2v1.1l-.1-.1c-.8-.9-1.8-1.2-3-1.1-1.2.2-2 .8-2.6 1.8-.1.2-.2.4-.2.5-.1 0-.1-.1-.1-.2-.3-.6-.6-1.2-.8-1.8-.1-.2-.2-.3-.3-.3h-.7c-.2 0-.3.1-.4.2-.8 2.2-1.7 4.4-2.6 6.6 0 .1-.1.1 0 .2h1.6c.1 0 .1-.1.2-.1.1-.2.2-.5.3-.7 0-.1.1-.2.2-.2h2c.1 0 .2 0 .2.2.2.2.3.4.3.6 0 .1.1.2.2.2h1.5c.3 0 .3 0 .2-.2z"/><path d="M22 .1H.1c0-.1.1 0 .2 0h21.5c0-.1.1-.1.2 0z" fill="#aaa9aa"/><path fill="#f3f3f4" d="M10 14.2c-.6-1.4-1.2-2.8-1.7-4.2 0-.1-.1-.2-.1-.2.1-.1.1-.3.2-.4.2.1.2.4.3.6.6 1.3 1.1 2.6 1.6 4 0 .1.1.2.1.3-.1 0-.2-.1-.4-.1zM14 13.3c.4-.5.7-1 .8-1.6 0-.1.1-.2.2-.2s.1.1.1.2c-.2.7-.5 1.3-1.1 1.8.1 0 0-.1 0-.2zM14.1 8.4c.3 0 .4.3.5.5.3.4.5.8.6 1.3 0 .1.1.2-.1.2-.1 0-.2 0-.3-.2-.1-.6-.4-1.1-.8-1.5 0-.1.1-.2.1-.3z"/><path fill="#47bda1" d="M14.1 11v.5c-.1.1-.3.1-.5 0-.2 0-.2.1-.3.2-.4.8-1.3 1.3-2.2 1.1-.9-.2-1.5-1-1.5-1.9 0-.9.7-1.7 1.6-1.9.9-.2 1.8.3 2.1 1.1.1.2.1.2.3.2.2 0 .4-.1.5 0 0 .2-.1.5 0 .7-.1 0 0 0 0 0zM6.6 10.2l.6 1.5c0 .1 0 .1-.1.1h-.9c-.1 0-.1 0-.1-.1.2-.5.3-1 .5-1.5z"/>';
				}
			}

			// Meta Box
			if($this->meta_box_activated) {

				$has_fields = WS_Form_Meta_Box::meta_box_get_fields_all('user', false, false, true, false, true);

				if($has_fields) {

					$svg_custom_field_logos[] = '<path d="M1.9 0h18.2c1 0 1.9.9 1.9 1.9v18.2c0 1.1-.9 1.9-1.9 1.9H1.9c-1 0-1.9-.9-1.9-1.9V1.9C0 .9.9 0 1.9 0z" fill="#010101"/><path d="M14.3 13.6l.2-4.5-2.7 7.6h-1.4L7.6 9.2l.2 4.5v1.6l1.1.2v1.2H4.6v-1.2l1.1-.2V7.9l-1.1-.2V6.5H8.4L11 14l2.6-7.5h3.8v1.2l-1.1.2v7.3l1.1.2v1.2h-4.2v-1.2l1.1-.2v-1.6z" fill="#fff"/>';
				}
			}

			// Pods
			if($this->pods_activated) {

				$has_fields = WS_Form_Pods::pods_get_fields_all('user', false, false, true, false, true);

				if($has_fields) {

					$svg_custom_field_logos[] = '<path fill="#95BF3D" d="M0 22V0h22v22H0zm2.5-11c0 4.7 3.8 8.5 8.3 8.6 4.8.1 8.7-3.7 8.8-8.3.1-4.8-3.8-8.8-8.5-8.8-4.8 0-8.6 3.8-8.6 8.5z"/><path fill="#95BF3D" d="M3 11c0-4.4 3.6-8 8-8s8 3.6 8 8.1c0 4.4-3.6 8-8.1 8C6.6 19.1 3 15.4 3 11zm6.7 1.5c.2-.2.3-.2.5-.2 1.7 0 3.3-.6 4.8-1.5 1-.7 1.9-1.5 2.8-2.3.4-.4.3-1-.3-1.1-1.3-.5-2.2-1.4-2.7-2.7-.1-.3-.3-.4-.5-.5-1.4-.6-2.8-.8-4.3-.6C6.4 4 3.6 7 3.5 10.5c-.2 4.4 3.3 7.9 7.3 8 3.2 0 5.5-1.4 7-4.1.1-.2.1-.4.1-.6-.4-1.3-.3-2.6.4-3.8.2-.4.2-.7 0-1.1-2.4 2.2-5.1 3.8-8.6 3.6z"/><path fill="#95BF3D" d="M13.5 5.7c1.9.1 2.9 1.5 3.3 3 .1.2-.1.2-.2.2-1.5-.1-3.1-1.5-3.5-3 0-.3.2-.2.4-.2zM17 10.3c.6 1.8.4 3.5-.8 5.1-.8-1.3-.7-3.7.8-5.1zM15.4 12.2c0 1.4-.4 2.6-1.5 3.4-.1.1-.2.2-.3.1-.1-.1-.1-.2-.1-.3-.1-1.4.2-2.7 1.3-3.7.1-.1.3-.3.5-.3.1.1 0 .3.1.5v.3zM15.1 10.3c-1.5-.1-2.8-1.4-3.1-2.8-.1-.3.1-.2.2-.2.7.1 1.3.4 1.8.9.6.5 1 1.2 1.1 2.1zM13.4 12.5c0 1.4-.4 2.6-1.7 3.4-.1.1-.2.2-.3.1-.1-.1-.1-.2-.1-.3.1-1.2.4-2.3 1.6-3 .1-.1.2-.1.3-.2h.2zM10.8 8.5c1.2.1 2.4 1.3 2.6 2.5 0 .2 0 .2-.2.2-1.2-.2-2.4-1.3-2.6-2.5-.1-.2 0-.2.2-.2zM11.8 11.9c-1.2-.2-2-.9-2.4-2-.1-.3-.1-.5.3-.4 1.1.2 1.9 1.1 2.1 2.4zM9.4 15.4c.1-1.1.9-2.1 1.8-2.4.1 0 .3-.1.4 0 .1.1 0 .2 0 .3-.3 1.1-.8 1.9-1.9 2.3-.3.1-.4 0-.3-.2zM10.1 12c-1.1-.1-2-.9-2.3-1.9-.1-.4-.1-.4.2-.4 1.1.2 1.9 1.1 2.1 2.3zM8.5 12.6c-.8.7-1.7.7-2.6.1-.3-.2-.2-.3 0-.4.9-.5 1.8-.4 2.6.3zM6.3 9.9c.9.1 1.7.9 1.9 1.8 0 .2 0 .2-.2.2-.8 0-1.9-1.1-1.9-1.8 0-.3.1-.3.2-.2zM10 12.8c-.4 1-1.2 1.5-2.2 1.4-.3 0-.3-.1-.2-.4.6-.7 1.4-1.1 2.4-1zM4.3 10.2c.9-.1 1.6.3 2 1.1.1.3.1.3-.2.3-.8-.1-1.5-.6-1.8-1.4z"/>';
				}
			}

			// Toolset
			if($this->toolset_activated) {

				$has_fields = WS_Form_Toolset::toolset_get_fields_all(array('domain' => Toolset_Element_Domain::USERS), false, true, false, true);

				if($has_fields) {

					$svg_custom_field_logos[] = '<path fill="#EC793D" d="M1 5.1h2.4V2.9c0-.5.4-.9 1-.9H9c.5 0 1 .3 1 .9V5h2V2.9c0-.5.4-.9 1-.9h4.6c.5 0 1 .3 1 .9V5H21c.6 0 1 .3 1 .9v13.2c0 .4-.4.8-1 .8H1c-.5 0-1-.3-1-.9V6c.1-.5.5-.9 1-.9z"/><path fill="#ffffff" d="M16.2 7.3v-3h-1.5v3.1H7.5V4.3H5.9v3.1H2.5v10.2h17V7.3h-3.3z"/>';
				}
			}

			// JetEngine
			if($this->jetengine_activated) {

				$has_fields = WS_Form_JetEngine::jetengine_get_fields_all('user', null, false, true, false, true);

				if($has_fields) {

					$svg_custom_field_logos[] = '<path fill="#9D64ED" d="M1.7 0h18.6c.9 0 1.7.8 1.7 1.7v18.6c0 .9-.8 1.7-1.7 1.7H1.7C.8 22 0 21.2 0 20.3V1.7C.1.7.8 0 1.7 0z"/><path fill="#FFFFFF" d="M19.5 4.6c.3 0 .4.3.2.5L18.2 7c-.2.2-.5.1-.5-.2V6c0-.1 0-.2-.1-.3l-.6-.5c-.2-.2-.1-.6.2-.6h2.3zM7.4 9.5c0 1.8-1.5 3.3-3.3 3.3-.5 0-.8-.4-.8-.8 0-.5.4-.8.8-.8.9 0 1.7-.7 1.7-1.6V7.1c0-.5.4-.8.8-.8.5 0 .8.4.8.8v2.4zm9 0c0 .9.7 1.6 1.7 1.6.5 0 .8.4.8.8s-.4.8-.8.8c-1.8 0-3.3-1.5-3.3-3.3V6.9c0-.5.4-.8.8-.8s.8.4.8.8v.7h.7c.5 0 .8.4.8.8 0 .5-.4.8-.8.8l-.7.3zm-2.2-1.1c-.3-.7-.7-1.3-1.4-1.7-1.6-.9-3.6-.4-4.5 1.2s-.4 3.6 1.2 4.5c1.2.7 2.6.6 3.6-.2.2-.1.4-.4.4-.7 0-.5-.4-.8-.8-.8-.2 0-.4.1-.6.2-.5.3-1.2.4-1.7.1l3.3-1.5c.2-.1.4-.2.5-.4.1-.2.1-.5 0-.7zM12 8.1c.1.1.2.1.3.2L9.6 9.6c0-.3.1-.6.2-.9.4-.8 1.4-1 2.2-.6zm.3 5.9v-.2h-.2v.2h.2c-.1 0-.1 0 0 0zm-.2.4v2h.2v-2c-.1-.1-.1-.1-.2 0 0-.1 0-.1 0 0zm-5.2.4c.1.1.2.3.2.4H5.5c0-.2.1-.3.2-.4.2-.2.3-.2.6-.2.2 0 .4 0 .6.2zM7 16c0-.1-.1-.1 0 0h-.2c-.1.1-.1.1-.2.1h-.3c-.2 0-.4-.1-.6-.2-.1-.1-.2-.3-.2-.4h1.8v-.1c0-.3-.1-.5-.3-.7-.2-.2-.4-.3-.7-.3-.3 0-.5.1-.7.3-.2.2-.3.4-.3.7 0 .3.1.5.3.7.2.2.4.3.7.3.3 0 .5-.1.7-.2V16zm.6-1.6s-.1 0 0 0v2h.2v-1.1c0-.2.1-.4.2-.5s.3-.2.5-.2.4.1.5.2.2.3.2.5v1.1h.2v-1.1c0-.3-.1-.5-.3-.7-.2-.2-.4-.3-.7-.3-.3 0-.5.1-.7.3l-.1-.2c.1-.1.1-.1 0 0 0-.1 0-.1 0 0zm3.1.2c-.2 0-.4.1-.6.2-.2.2-.2.3-.2.6 0 .2.1.4.2.6s.3.2.6.2c.2 0 .4-.1.6-.2.2-.2.2-.3.2-.6 0-.2-.1-.4-.2-.6-.2-.2-.4-.2-.6-.2zm.7 1.4c-.2.2-.5.4-.8.4s-.5-.1-.7-.3c-.2-.2-.3-.4-.3-.7 0-.3.1-.5.3-.7.2-.2.4-.3.7-.3.3 0 .5.1.7.3.2.2.3.4.3.7v1c0 .2-.1.4-.2.5-.2.2-.5.3-.8.3s-.6-.1-.8-.3c-.1-.1-.1-.2-.2-.2v-.2h.2c0 .1.1.1.2.2s.2.1.3.2c.1 0 .2.1.3.1.2 0 .4-.1.6-.3.1-.1.2-.3.2-.4V16zm1.3-1.6c.1-.1.1-.1 0 0l.2-.1V14.6c.2-.2.5-.3.7-.3.3 0 .5.1.7.3.2.2.3.4.3.7v1.1h-.2v-1.1c0-.2-.1-.4-.2-.5s-.3-.2-.5-.2-.4.1-.5.2-.2.3-.2.5v1.1h-.2v-2h-.1zm3.7.4c.1.1.2.3.2.4H15c0-.2.1-.3.2-.4.2-.2.3-.2.6-.2s.4 0 .6.2zm.1 1.2c-.1-.1-.1-.1 0 0h-.2c-.1.1-.1.1-.2.1h-.3c-.2 0-.4-.1-.6-.2-.1-.1-.2-.3-.2-.4h1.8v-.1c0-.3-.1-.5-.3-.7-.2-.2-.4-.3-.7-.3-.3 0-.5.1-.7.3-.2.2-.3.4-.3.7 0 .3.1.5.3.7.2.2.4.3.7.3.3 0 .5-.1.7-.2V16z"/>';
				}
			}

			$svg_custom_field_logos_count = count($svg_custom_field_logos);

			if($svg_custom_field_logos_count === 0) { return $svg_logo; }

			// Add custom field logos
			$svg_custom_field_logos_spacing = 5;
			$svg_custom_field_logos_width = 22;
			$svg_custom_field_logos_width_total = (($svg_custom_field_logos_width * $svg_custom_field_logos_count) + ($svg_custom_field_logos_spacing * ($svg_custom_field_logos_count - 1)));
			$svg_custom_field_logos_x = 70 - ($svg_custom_field_logos_width_total / 2);
			$svg_custom_field_logos_y = 126;

			foreach($svg_custom_field_logos as $svg_custom_field_logos_index => $svg_custom_field_logo) {

				$svg_custom_field_logos_x_offset = $svg_custom_field_logos_index * ($svg_custom_field_logos_width + $svg_custom_field_logos_spacing);

				$g_translate_x = $svg_custom_field_logos_x + $svg_custom_field_logos_x_offset;
				$g_translate_y = $svg_custom_field_logos_y;

				$svg_logo .= sprintf('<g transform="translate(%.6f, %.6f)">%s</g>', $g_translate_x, $g_translate_y, $svg_custom_field_logo);
			}

			return $svg_logo;
		}
	}
