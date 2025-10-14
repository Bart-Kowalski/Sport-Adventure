<?php

	class WS_Form_Ability {

		public $input = false; 

		// Register abilities
		public function register() {

			// Get abilities
			$abilities = WS_Form_Config::get_abilities();

			// Register abilities
			foreach($abilities as $ability_name => $ability) {

				wp_register_ability(

					// Ability
					$ability_name,

					// Args
					array(

						'label'               => $ability['label'],
						'description'         => $ability['description'],
						'input_schema'        => $ability['input_schema'],
						'output_schema'       => $ability['output_schema'],
						'execute_callback'    => $ability['execute_callback'],
						'permission_callback' => $ability['permission_callback']
					)
				);
			}
		}

		// Forms
		public function forms($input) {

			try {

				// Init
				self::init($input, WS_FORM_ABILITY_API_NAMESPACE . 'forms');

				// Initiate instance of Form class
				$ws_form_form = new WS_Form_Form();

				// Return data
				return $ws_form_form->get_all_key_value(self::input_get('published'), self::input_get('order_by'), false);

			} catch(Exception $e) {

				/* translators: %s: Error message */
				return self::error($e, __('Error retrieving forms: %s', 'ws-form'));
			}
		}

		// Form - Shortcode
		public function form_block($input) {

			try {

				// Init
				self::init($input, WS_FORM_ABILITY_API_NAMESPACE . 'form-block');

				// Get form ID
				$form_id = self::input_get('id');

				// Check form ID
				if($form_id == 0) {

					throw new Exception(esc_html__('Invalid form ID.', 'ws-form'));
				}

				// Get block markup
				$block = array(

					'blockName' => 'wsf-block/form-add',
					'attrs'     => array( 'form_id' => $form_id ),
					'innerBlocks' => array(),
					'innerHTML' => '',
				);

				// Return data
				return [

					'markup' => serialize_block( $block )
				];

			} catch(Exception $e) {

				/* translators: %s: Error message */
				return self::error($e, __('Error generating block: %s', 'ws-form'));
			}
		}

		// Form - Create - Blank
		public function form_create_blank($input) {

			try {

				// Init
				self::init($input, WS_FORM_ABILITY_API_NAMESPACE . 'form-create-blank');

				// Create instance
				$ws_form_form = new WS_Form_Form();

				// Create a new form
				$ws_form_form->db_create();

				// Get the form ID
				$form_id = $ws_form_form->id;

				// Return data
				return array(

					'id' => $form_id,
					'url_edit' => WS_Form_Common::get_admin_url('ws-form-edit', $form_id),
					'url_preview' => WS_Form_Common::get_preview_url($form_id),
				);

			} catch(Exception $e) {

				/* translators: %s: Error message */
				return self::error($e, __('Error creating blank form: %s', 'ws-form'));
			}
		}

		// Form - Create - JSON (Clients not yet compatible)
/*		public function form_prompt_json($input) {

			try {

				// Init
				self::init($input, WS_FORM_ABILITY_API_NAMESPACE . 'form-prompt-json');

				// Get description
				$description = self::input_get('description');

				// Return data
				return [

					'messages' => [

						[
							'role'    => 'user',
							'content' => [
								'type' => 'text',
								'text' => sprintf(

									"%s:\n\n%s\n\n%s:\n\n%s",
									esc_html(__('Generate WS Form JSON based on the following requirements', 'ws-form')),
									WS_Form_Config_Ability::get_form_json_prompt(),
									esc_html(__('The description of the form to create is as follows', 'ws-form')),
									esc_html($description)
								)
							],
						],
					],
				];

			} catch(Exception $e) {
*/
				/* translators: %s: Error message */
/*				return self::error($e, __('Error creating prompt from JSON: %s', 'ws-form'));
			}
		}
*/
		// Form - Create - JSON
		public function form_create_from_json($input) {

			try {

				// Init
				self::init($input, WS_FORM_ABILITY_API_NAMESPACE . 'form-create-from-json');

				// Get JSON
				$form_json = self::input_get('form_json');

				// Load WS_Form_JSON class
				require_once WS_FORM_PLUGIN_DIR_PATH . 'includes/core/class-ws-form-json.php';

				// Create instance
				$ws_form_json = new WS_Form_JSON();

				// Create a new form
				$form_id = $ws_form_json->json_to_form($form_json);

				// Return data
				return [

					'id' => $form_id,
					'url_edit' => WS_Form_Common::get_admin_url('ws-form-edit', $form_id),
					'url_preview' => WS_Form_Common::get_preview_url($form_id)
				];

			} catch(Exception $e) {

				/* translators: %s: Error message */
				return self::error($e, __('Error creating form from JSON: %s', 'ws-form'));
			}
		}

		// Form - Delete
		public function form_delete($input) {

			try {

				// Init
				self::init($input, WS_FORM_ABILITY_API_NAMESPACE . 'form-delete');

				// Get form ID
				$form_id = self::input_get('id');

				// Get permanent (wsf_ability_form_delete_permanent filter hook must be true)
				$permanent = self::input_get('permanent');

				if($permanent && !apply_filters('wsf_ability_form_delete_permanent', false)) {

					throw new Exception(esc_html__('Permanent form deletion is not enabled.', 'ws-form'));
				}

				// Create instance
				$ws_form_form = new WS_Form_Form();

				// Set form ID
				$ws_form_form->id = $form_id;

				// Trash or permanently delete the form
				$ws_form_form->db_delete($permanent);

				// Return data
				return [

					'id' => $form_id,
					'permanent' => $permanent,
					'message' => esc_html($permanent ? __('Form permanently deleted', 'ws-form') : __('Form trashed', 'ws-form')),
					'url' => $permanent ? WS_Form_Common::get_admin_url('ws-form') : WS_Form_Common::get_admin_url('ws-form', false, '&ws-form-status=trash')
				];

			} catch(Exception $e) {

				/* translators: %s: Error message */
				return self::error($e, __('Error deleting form: %s', 'ws-form'));
			}
		}

		// Form - Restore
		public function form_restore($input) {

			try {

				// Init
				self::init($input, WS_FORM_ABILITY_API_NAMESPACE . 'form-restore');

				// Get form ID
				$form_id = self::input_get('id');

				// Create instance
				$ws_form_form = new WS_Form_Form();

				// Set form ID
				$ws_form_form->id = $form_id;

				// Trash or permanently delete the form
				$ws_form_form->db_restore();

				// Return data
				return [

					'id' => $form_id,
					'url' => WS_Form_Common::get_admin_url('ws-form-edit', $form_id)
				];

			} catch(Exception $e) {

				/* translators: %s: Error message */
				return self::error($e, __('Error restoring form: %s', 'ws-form'));
			}
		}

		// Form - Get
		public function form_get($input) {

			try {

				// Init
				self::init($input, WS_FORM_ABILITY_API_NAMESPACE . 'form-get');

				// Get form ID
				$form_id = self::input_get('id');

				// Create instance
				$ws_form_form = new WS_Form_Form();

				// Set form ID
				$ws_form_form->id = $form_id;

				// Read the form
				$form_object = $ws_form_form->db_read(true, true);

				// Return data
				return [

					'form_object' => $form_object
				];

			} catch(Exception $e) {

				/* translators: %s: Error message */
				return self::error($e, __('Error retrieving form: %s', 'ws-form'));
			}
		}

		// Form - Publish
		public function form_publish($input) {

			try {

				// Init
				self::init($input, WS_FORM_ABILITY_API_NAMESPACE . 'form-publish');

				// Get form ID
				$form_id = self::input_get('id');

				// Check form ID
				if($form_id == 0) {

					throw new Exception(esc_html__('Invalid form ID.', 'ws-form'));
				}

				// Create instance
				$ws_form_form = new WS_Form_Form();

				// Set form ID
				$ws_form_form->id = $form_id;

				// Publish the form
				$ws_form_form->db_publish();

				// Return data
				return [

					'status' => 'publish'
				];

			} catch(Exception $e) {

				/* translators: %s: Error message */
				return self::error($e, __('Error publishing form: %s', 'ws-form'));
			}
		}

		// Form - Shortcode
		public function form_shortcode($input) {

			try {

				// Init
				self::init($input, WS_FORM_ABILITY_API_NAMESPACE . 'form-shortcode');

				// Get form ID
				$form_id = self::input_get('id');

				// Check form ID
				if($form_id == 0) {

					throw new Exception(esc_html__('Invalid form ID.', 'ws-form'));
				}

				// Return data
				return [

					'shortcode' => WS_Form_Common::shortcode($form_id)
				];

			} catch(Exception $e) {

				/* translators: %s: Error message */
				return self::error($e, __('Error generating shortcode: %s', 'ws-form'));
			}
		}

		// Form - Statistics
		public function form_stats($input) {

			try {

				// Init
				self::init($input, WS_FORM_ABILITY_API_NAMESPACE . 'form-stats');

				// Get form ID
				$form_id = self::input_get('id');

				// Create instance
				$ws_form_form = new WS_Form_Form();

				// Set form ID
				$ws_form_form->id = $form_id;

				// Read the form
				$form_object = $ws_form_form->db_read();

				// Return data
				return [

					'label' => esc_html($form_object->label),
					'status' => esc_html($form_object->status),
					'count_stat_view' => esc_html($form_object->count_stat_view),
					'count_stat_save' => esc_html($form_object->count_stat_save),
					'count_stat_submit' => esc_html($form_object->count_stat_submit),
					'count_submit' => esc_html($form_object->count_submit),
					'count_submit_unread' => esc_html($form_object->count_submit_unread),
				];

			} catch(Exception $e) {

				/* translators: %s: Error message */
				return self::error($e, __('Error retrieving form statistics: %s', 'ws-form'));
			}
		}

		// Form - Update
		public function form_update($input) {

			try {

				// Init
				self::init($input, WS_FORM_ABILITY_API_NAMESPACE . 'form-update');

				// Get form object
				$form_object = self::input_get('form_object');

				// Check form object
				if(
					!is_object($form_object) ||
					!isset($form_object->id) ||
					!isset($form_object->label) ||
					!isset($form_object->groups)
				) {
					throw new Exception(esc_html__('Invalid form object.', 'ws-form'));
				}

				// Get form ID
				$form_id = absint($form_object->id);
				if($form_id === 0) {

					throw new Exception(esc_html__('Invalid form ID.', 'ws-form'));
				}

				// Create instance
				$ws_form_form = new WS_Form_Form();

				// Set form ID
				$ws_form_form->id = $form_id;

				// Update the form from the form object
				$ws_form_form->db_update_from_object($form_object);

				// Read the form
				$form_object = $ws_form_form->db_read(true, true);

				// Return data
				return [

					'form_object' => $form_object
				];

			} catch(Exception $e) {

				/* translators: %s: Error message */
				return self::error($e, __('Error updating form: %s', 'ws-form'));
			}
		}

		// Field - Types
/*		public function field_types($input) {

			try {

				// Init
				self::init($input, WS_FORM_ABILITY_API_NAMESPACE . 'field-types');

				// Get field types we can build a form dynamically from
				$ws_form_json = new WS_Form_JSON();

				// Return data
				return array_values($ws_form_json->field_types());

			} catch(Exception $e) {
*/
				/* translators: %s: Error message */
/*				return self::error($e, __('Error generating shortcode: %s', 'ws-form'));
			}
		}
*/
		// Init
		public function init($input, $ability_name) {

			// Parse input
			self::input_parse($input, $ability_name);
		}

		// Permission callback
		public function permission_callback($caps) {

		    if ( empty( $caps ) ) {
		        return false;
		    }

		    $user = wp_get_current_user();

		    if ( ! $user || 0 === $user->ID ) {

		        return false;
		    }

		    if( is_string($caps) ) {

		    	return $user->has_cap( $caps );
		    }

		    if( is_array($caps) ) {

			    foreach ( $caps as $cap ) {

			        if ( ! $user->has_cap( $cap ) ) {

			            return false;
			        }
			    }

			    return true;
			}

		    return false;
		}

		// Input parse
		public function input_parse($input, $ability_name) {

			// Reset input
			$this->input = array();

			// Check if input is WP_REST_Request
			if(is_a($input, 'WP_REST_Request')) {

				$input = $input->get_json_params();
			}

			// Check input are an array
			if(!is_array($input)) { $input = array(); }

			// Ensure all input match input schema

			// Get ability input schema
			$input_schema = WS_Form_Config_Ability::get_ability_input_schema($ability_name);

			// Check input schema is valid
			if(!is_array($input_schema)) {

				throw new Exception(

					sprintf(

						/* translators: %s: Ability name */
						esc_html__('Input schema for ability %s not found.', 'ws-form'),
						esc_html($ability_name)
					)
				);
			}

			// Check if input schema is empty
			if(!is_array($input_schema) || empty($input_schema)) {

				return array();
			}

			// Check type is object
			if(
				!isset($input_schema['type']) ||
				($input_schema['type'] != 'object')
			) {
				throw new Exception(

					sprintf(

						/* translators: %s: Ability name */
						esc_html__('Invalid input schema type for ability %s. Expects object.', 'ws-form'),
						esc_html($ability_name)
					)
				);
			}

			// Check properties exist
			if(
				!isset($input_schema['properties']) ||
				!is_array($input_schema['properties'])
			) {
				throw new Exception(

					sprintf(

						/* translators: %s: Ability name */
						esc_html__('No input schema properties for ability %s.', 'ws-form'),
						esc_html($ability_name)
					)
				);
			}

			// Process properties
			foreach($input_schema['properties'] as $property_name => $property) {

				// Check property name (Required)
				if(empty($property_name)) {

					throw new Exception(

						sprintf(

							/* translators: %s: Ability name */
							esc_html__('Invalid input schema property name for ability %s.', 'ws-form'),
							esc_html($ability_name)
						)
					);
				}

				// Get property type
				$type = isset($property['type']) ? strtolower($property['type']) : 'string';

				// Check property type (Required)
				if(
					!in_array($type, array(

						// MCP
						'number',
						'boolean',
						'string',
						'array',
						'object',
						'null',

						// WordPress abilities API
						'integer'
					))
				) {
					throw new Exception(

						sprintf(

							/* translators: %1$s: Ability name, %2$s: Property name */
							esc_html__('Invalid input schema property type for ability %1$s (Property: %2$s).', 'ws-form'),
							esc_html($ability_name),
							esc_html($property_name)
						)
					);
				}

				// Get input value
				$input_value = isset($input[$property_name]) ? $input[$property_name] : '';

				// Get required
				$required = isset($property['required']) ? WS_Form_Common::is_true($property['required']) : null;

				// Check required
				if(
					empty($input_value) &&
					$required
				) {
					throw new Exception(

						sprintf(

							/* translators: %1$s: Ability ID, %2$s: Property name */
							esc_html__('Required input schema property for ability %1$s missing (Property: %2$s).', 'ws-form'),
							esc_html($ability_name),
							esc_html($property_name)
						)
					);
				}

				// Get default value
				$default_value = isset($property['default']) ? $property['default'] : false;

				// Enumeration
				if(isset($property['enum'])) {

					if(!is_array($property['enum'])) {

						throw new Exception(

							sprintf(

								/* translators: %1$s: Property name, %2$s: Ability ID */
								esc_html__('Input schema property %1$s has invalid enum for ability %2$s. Expected array.', 'ws-form'),
								esc_html($property_name),
								esc_html($ability_name)
							)
						);
					}

					if(!in_array($input_value, $property['enum'])) {

						$input_value = null;
					}
				}

				// Process according to type
				// Attempt to convert to correct type in case AI provides a different type
				switch($type) {

					case 'number' :
					case 'integer' :

						$input_value = intval($input_value);
						break;

					case 'boolean' :

						$input_value = WS_Form_Common::is_true($input_value);
						break;

					case 'string' :

						$input_value = sanitize_text_field($input_value);
						break;

					case 'array' :

						$input_value = WS_Form_Common::to_array($input_value);
						break;

					case 'object' :

						$input_value = WS_Form_Common::to_object($input_value);
						break;

					case 'null' :

						$input_value = null;
						break;
				}

				// If no value passed and string then set to default value
				if(
					($input_value === '') &&
					!is_null($default_value)
				) {
					$input_value = $default_value;
				}

				// Sanitize and set input
				$this->input[$property_name] = $input_value;
			}

			return $this->input;
		}

		// Get input
		public function input_get($property_name) {

			// Check input have been parsed
			if($this->input === false) {

				throw new Exception(esc_html__('Inputs not parsed.', 'ws-form'));				
			}

			if(!isset($this->input[$property_name])) {

				throw new Exception(

					sprintf(

						/* translators: %s: Property name */
						esc_html__('Property %s does not exist in input schema.', 'ws-form'),
						esc_html($property_name)
					)
				);				
			}

			return $this->input[$property_name];
		}

		// Error handling
		public function error($e, $message) {

			return [

				'error' => array(

					'code' => 'ws_form_error',
					'message' => sprintf(

						esc_html($message),
						esc_html($e->getMessage())
					),
					'data' => $this->input
				)
			];
		}
	}
