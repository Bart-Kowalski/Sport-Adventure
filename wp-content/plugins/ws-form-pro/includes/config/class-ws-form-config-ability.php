<?php

	class WS_Form_Config_Ability {

		public static $abilities = false;

		// Configuration - Abilities
		public static function get_abilities() {

			$ws_form_json = new WS_Form_JSON();

			// Abilities
			$abilities = [

				// Forms - List
				WS_FORM_ABILITY_API_NAMESPACE . 'forms' => [

					'type' => 'ability',
					'label' => __('List forms', 'ws-form'),
					'description' => __('Returns a list of all of the forms in WS Form.', 'ws-form'),
					'thinking_message' => __('Retrieving forms', 'ws-form'),
					'success_message' => __('Successfully retrieved forms', 'ws-form'),
					'permission_callback' => function() {

						$ws_form_ability = new WS_Form_Ability();
						return $ws_form_ability->permission_callback( 'read_form' );
					},
					'input_schema'  => [

						'type' => 'object',

						'properties' => [

							'published' => [

								'type' => 'boolean',
								'description' => __('Optionally whether to retrieve only published forms. Published forms are those that can be added to pages and posts. Unpublished forms are in a draft state and are still being developed. Defaults to true.', 'ws-form'),
								'default' => true
							],

							'order_by' => [

								'type' => 'string',
								'description' => __('Optionally how to order the forms. Valid values are id or label. Defaults to label.', 'ws-form'),
								'default' => 'label',
								'enum' => ['id', 'label']
							],
						],
					],
					'output_schema' => [

						'type' => 'object',

						'properties' => [

							'id' => [

								'type' => 'number',
								'description' => __('The ID of the form.', 'ws-form')
							],

							'label' => [

								'type' => 'string',
								'description' => __('The label of the form.', 'ws-form')
							],
						],
					],
					'execute_callback' => function( $input ) {

						$ws_form_ability = new WS_Form_Ability();
						return $ws_form_ability->forms( $input );
					}
				],

				// Form - Block
				WS_FORM_ABILITY_API_NAMESPACE . 'form-block' => [

					'type' => 'ability',
					'label' => __('Get form block markup', 'ws-form'),
					'description' => __('Gets the block markup for a form in WS Form by form ID. This should only be used form pages edited by Gutenberg / block editor.', 'ws-form'),
					'thinking_message' => __('Creating shortcode', 'ws-form'),
					'success_message' => __('Shortcode created', 'ws-form'),
					'permission_callback' => function() {

						$ws_form_ability = new WS_Form_Ability();
						return $ws_form_ability->permission_callback( 'read_form' );
					},
					'input_schema'  => [

						'type' => 'object',

						'properties' => [

							'id' => [

								'type' => 'number',
								'description' => __('The form ID to create a shortcode for.', 'ws-form')
							],
						],
					],
					'output_schema' => [

						'type' => 'object',

						'properties' => [

							'markup' => [

								'type' => 'string',
								'description' => __('The block markup for the specified form ID.', 'ws-form')
							],
						],
					],
					'execute_callback' => function( $input ) {

						$ws_form_ability = new WS_Form_Ability();
						return $ws_form_ability->form_block($input);
					}
				],

				// Form - Create - Blank
				WS_FORM_ABILITY_API_NAMESPACE . 'form-create-blank' => [

					'type' => 'ability',
					'label' => __('Create blank form', 'ws-form'),
					'description' => __('Creates a new blank / empty form in the WS Form form plugin for WordPress.', 'ws-form'),
					'thinking_message' => __('Creating a blank form', 'ws-form'),
					'success_message' => __('Blank form created', 'ws-form'),
					'permission_callback' => function() {

						$ws_form_ability = new WS_Form_Ability();
						return $ws_form_ability->permission_callback( [ 'create_form', 'edit_form' ] );
					},
					'input_schema'  => [

						'type' => 'object',

						'properties' => []
					],
					'output_schema' => [

						'type' => 'object',

						'properties' => [

							'id' => [

								'type' => 'number',
								'description' => __('The newly created form ID.', 'ws-form')
							],

							'url_edit' => [

								'type' => 'string',
								'description' => __('The admin URL of the form that was created.', 'ws-form')
							],

							'url_preview' => array(

								'type' => 'string',
								'description' => __('The URL to preview the form that was created.', 'ws-form')
							)
						],
					],
					'execute_callback' => function( $input ) {

						$ws_form_ability = new WS_Form_Ability();
						return $ws_form_ability->form_create_blank($input);
					}
				],

				// Form - Create - JSON
				WS_FORM_ABILITY_API_NAMESPACE . 'form-create-from-json' => [

					'type' => 'ability',
					'label' => __('Create form from a description', 'ws-form'),
					'description' => __('Use this tool when the user asks to create a form based on a description. The tool generates a form from a JSON string, and that JSON must strictly follow the structure defined by the prompt in the form_json input property description.', 'ws-form'),
					'thinking_message' => __('Creating form', 'ws-form'),
					'success_message' => __('Form created', 'ws-form'),
					'permission_callback' => function() {

						$ws_form_ability = new WS_Form_Ability();
						return $ws_form_ability->permission_callback( [ 'create_form', 'edit_form' ] );
					},
					'input_schema'  => [

						'type' => 'object',

						'properties' => [

							'form_json' => [

								'type' => 'string',
								'description' => $ws_form_json->get_ai_prompt_form()
							]
						]
					],
					'output_schema' => [

						'type' => 'object',

						'properties' => [

							'id' => [

								'type' => 'number',
								'description' => __('The newly created form ID.', 'ws-form')
							],

							'url_edit' => array(

								'type' => 'string',
								'description' => __('The URL to edit the form that was created.', 'ws-form')
							),

							'url_preview' => array(

								'type' => 'string',
								'description' => __('The URL to preview the form that was created.', 'ws-form')
							)
						],
					],
					'execute_callback' => function( $input ) {

						$ws_form_ability = new WS_Form_Ability();
						return $ws_form_ability->form_create_from_json($input);
					}
				],

				// Form - Prompt - JSON (Clients not yet compatible)
/*				WS_FORM_ABILITY_API_NAMESPACE . 'form-prompt-json' => [

					'type' => 'prompt',
					'label' => __('Prompt for creating JSON required to create a form', 'ws-form'),
					'description' => __("If the user wants to create a form of a certain description, use this prompt to build the JSON required for the form-create-from-json tool.", 'ws-form'),
					'thinking_message' => __('Creating prompt', 'ws-form'),
					'success_message' => __('Prompt created', 'ws-form'),
					'permission_callback' => function() {

						$ws_form_ability = new WS_Form_Ability();
						return $ws_form_ability->permission_callback( [ 'create_form', 'edit_form' ] );
					},
					'input_schema'  => [

						'type' => 'object',

						'properties' => [

							'description' => [

								'type' => 'string',
								'description' => __('A description of the form to create', 'ws-form')
							]
						]
					],
					'output_schema' => [

						'type' => 'object'
					],
					'execute_callback' => function( $input ) {

						$ws_form_ability = new WS_Form_Ability();
						return $ws_form_ability->form_prompt_json($input);
					}
				],
*/
				// Form - Get
				WS_FORM_ABILITY_API_NAMESPACE . 'form-get' => [

					'type' => 'ability',
					'label' => __('Get form', 'ws-form'),
					'description' => __("Returns the full object of a form by ID.", 'ws-form'),
					'thinking_message' => __('Retrieving form', 'ws-form'),
					'success_message' => __('Form retrieved', 'ws-form'),
					'permission_callback' => function() {

						return $ws_form_ability->permission_callback( 'read_form' );
					},
					'input_schema'  => [

						'type' => 'object',

						'properties' => [

							'id' => [

								'type' => 'number',
								'description' => __('The form ID to retrieve.', 'ws-form')
							]
						]
					],
					'output_schema' => [

						'type' => 'object',

						'properties' => [

							'form_object' => [

								'type' => 'object',
								'description' => __('The full form object.', 'ws-form')
							]
						]
					],
					'execute_callback' => function( $input ) {

						return $ws_form_ability->form_get($input);
					}
				],

				// Form - Publish
				WS_FORM_ABILITY_API_NAMESPACE . 'form-publish' => [

					'type' => 'ability',
					'label' => __('Publish form', 'ws-form'),
					'description' => __('When a form is created in WS Form it initially has a status of draft. Use this to publish a form by ID so that it can be used on a live web page.', 'ws-form'),
					'thinking_message' => __('Publishing form', 'ws-form'),
					'success_message' => __('Form published', 'ws-form'),
					'permission_callback' => function() {

						$ws_form_ability = new WS_Form_Ability();
						return $ws_form_ability->permission_callback( 'edit_form' );
					},
					'input_schema'  => [

						'type' => 'object',

						'properties' => [

							'id' => [

								'type' => 'number',
								'description' => __('The form ID to publish.', 'ws-form')
							],
						],
					],
					'output_schema' => [

						'type' => 'object',

						'properties' => [

							'status' => [

								'type' => 'string',
								'description' => __('The new status of the form.', 'ws-form')
							],
						],
					],
					'execute_callback' => function( $input ) {

						$ws_form_ability = new WS_Form_Ability();
						return $ws_form_ability->form_publish($input);
					}
				],

				// Form - Shortcode
				WS_FORM_ABILITY_API_NAMESPACE . 'form-shortcode' => [

					'type' => 'ability',
					'label' => __('Get form shortcode', 'ws-form'),
					'description' => __('Access the shortcode expression in WS Form for use in WordPress given the form ID.', 'ws-form'),
					'thinking_message' => __('Creating shortcode', 'ws-form'),
					'success_message' => __('Shortcode created', 'ws-form'),
					'permission_callback' => function() {

						$ws_form_ability = new WS_Form_Ability();
						return $ws_form_ability->permission_callback( 'read_form' );
					},
					'input_schema'  => [

						'type' => 'object',

						'properties' => [

							'id' => [

								'type' => 'number',
								'description' => __('The form ID to create a shortcode for.', 'ws-form')
							],
						],
					],
					'output_schema' => [

						'type' => 'object',

						'properties' => [

							'shortcode' => [

								'type' => 'string',
								'description' => __('The shortcode for the specified form ID.', 'ws-form')
							],
						],
					],
					'execute_callback' => function( $input ) {

						$ws_form_ability = new WS_Form_Ability();
						return $ws_form_ability->form_shortcode($input);
					}
				],

				// Form
				WS_FORM_ABILITY_API_NAMESPACE . 'form-stats' => [

					'type' => 'ability',
					'label' => __('Get statistical data about a form by ID', 'ws-form'),
					'description' => __('Returns statistical data about a form by ID including total views, saves, submissions as well as the total number of submission records and how many of those are unread.', 'ws-form'),
					'thinking_message' => __('Retrieving form statistics', 'ws-form'),
					'success_message' => __('Form statistics retrieved', 'ws-form'),
					'permission_callback' => function() {

						$ws_form_ability = new WS_Form_Ability();
						return $ws_form_ability->permission_callback( 'read_form' );
					},
					'input_schema'  => [

						'type' => 'object',

						'properties' => [

							'id' => [

								'type' => 'number',
								'description' => __('The form ID to read.', 'ws-form')
							],
						],
					],
					'output_schema' => [

						'type' => 'object',

						'properties' => [

							'label' => [

								'type' => 'string',
								'description' => __('The form label.', 'ws-form')
							],

							'status' => [

								'type' => 'string',
								'description' => __('The form status. "draft" means the form is still being developed. "publish" means a live and completed version of the form exists.', 'ws-form')
							],

							'count_stat_view' => [

								'type' => 'number',
								'description' => __('The total number of times the form has been viewed publicly. Views by administrators are only included if WS Form > Settings > Basic > Statistics > Include Admin Traffic has been enabled.', 'ws-form')
							],

							'count_stat_save' => [

								'type' => 'number',
								'description' => __('The total number of times someone has viewed the form and clicked "Save" to save their progress.', 'ws-form')
							],

							'count_stat_submit' => [

								'type' => 'number',
								'description' => __('The total number of times someone has submitted the form.', 'ws-form')
							],

							'count_submit' => [

								'type' => 'number',
								'description' => __('The total number of submission records that exist for the form. This can differ from count_stat_submit because submission records can be trashed.', 'ws-form')
							],

							'count_submit_unread' => [

								'type' => 'number',
								'description' => __('The total number of submission records that have not been read yet', 'ws-form')
							]
						],
					],
					'execute_callback' => function( $input ) {

						$ws_form_ability = new WS_Form_Ability();
						return $ws_form_ability->form_stats($input);
					}
				],

				// Form - Delete
				WS_FORM_ABILITY_API_NAMESPACE . 'form-delete' => [

					'type' => 'ability',
					'label' => __('Delete form', 'ws-form'),
					'description' => __('Trash or permanently delete a form by ID.', 'ws-form'),
					'thinking_message' => __('Deleting form', 'ws-form'),
					'success_message' => __('Form deleted', 'ws-form'),
					'permission_callback' => function() {

						$ws_form_ability = new WS_Form_Ability();
						return $ws_form_ability->permission_callback( 'delete_form' );
					},
					'input_schema'  => [

						'type' => 'object',

						'properties' => [

							'id' => [

								'type' => 'number',
								'description' => __('The form ID to move to trash or permanently delete.', 'ws-form')
							],

							'permanent' => [

								'type' => 'boolean',
								'description' => __('If set to true, the form will be permanently deleted. If set to false, the form will be moved to trash and can later be restored.', 'ws-form'),
								'default' => false
							],
						],
					],
					'output_schema' => [

						'type' => 'object',

						'properties' => [

							'id' => [

								'type' => 'number',
								'description' => __('The ID of the form.', 'ws-form')
							],

							'permanent' => [

								'type' => 'boolean',
								'description' => __('Whether the form was permanently deleted.', 'ws-form')
							],

							'message' => [

								'type' => 'string',
								'description' => __('A message describing whether the form was permanently deleted.', 'ws-form')
							],

							'url' => [

								'type' => 'string',
								'description' => __('A suggested URL to redirect to after the form is deleted.', 'ws-form')
							],
						],
					],
					'execute_callback' => function( $input ) {

						$ws_form_ability = new WS_Form_Ability();
						return $ws_form_ability->form_delete($input);
					}
				],

				// Form - Restore
				WS_FORM_ABILITY_API_NAMESPACE . 'form-restore' => [

					'type' => 'ability',
					'label' => __('Restore form', 'ws-form'),
					'description' => __('Restore a form from the trash by ID.', 'ws-form'),
					'thinking_message' => __('Restoring form', 'ws-form'),
					'success_message' => __('Form restored', 'ws-form'),
					'permission_callback' => function() {

						$ws_form_ability = new WS_Form_Ability();
						return $ws_form_ability->permission_callback( 'delete_form' );
					},
					'input_schema'  => [

						'type' => 'object',

						'properties' => [

							'id' => [

								'type' => 'number',
								'description' => __('The form ID to restore.', 'ws-form')
							],
						],
					],
					'output_schema' => [

						'type' => 'object',

						'properties' => [

							'id' => [

								'type' => 'number',
								'description' => __('The ID of the form.', 'ws-form')
							],

							'url' => [

								'type' => 'string',
								'description' => __('A suggested URL to redirect to after the restore completes.', 'ws-form')
							]
						]
					],
					'execute_callback' => function( $input ) {

						$ws_form_ability = new WS_Form_Ability();
						return $ws_form_ability->form_delete($input);
					}
				],

				// Field - Types
/*				WS_FORM_ABILITY_API_NAMESPACE . 'field-types' => [

					'type' => 'ability',
					'label' => __('Get field types', 'ws-form'),
					'description' => __('Get all field types available in WS Form.', 'ws-form'),
					'thinking_message' => __('Retrieving field types', 'ws-form'),
					'success_message' => __('Field types retrieved', 'ws-form'),
					'permission_callback' => function() {

						$ws_form_ability = new WS_Form_Ability();
						return $ws_form_ability->permission_callback( 'create_form' );
					},
					'input_schema'  => [

						'type' => 'object',

						'properties' => []
					],
					'output_schema' => [

						'type' => 'array',

						'properties' => [

							'field_type' => [

								'type' => 'object',

								'properties' => [

									'id' => [

										'type' => 'string',
										'description' => __('The type identifier of the field type. This should be used when specifying a field type back to WS Form.', 'ws-form')
									],

									'label' => [

										'type' => 'string',
										'description' => __('The label of the field type. This is a display name.', 'ws-form')
									],

									'description' => [

										'type' => 'string',
										'description' => __('A description of what the field type can be used for.', 'ws-form')
									],
								],
							],
						],
					],
					'execute_callback' => function( $input ) {

						$ws_form_ability = new WS_Form_Ability();
						return $ws_form_ability->field_types($input);
					}
				]
*/
			];

			// Apply filter
			$abilities = apply_filters('wsf_config_abilities', $abilities);

			return $abilities;
		}

		// Get an ability by ID
		public static function get_ability($id) {

			// Check cache
			if(self::$abilities === false) {

				// Build cache
				self::$abilities = self::get_abilities();
			}

			return isset(self::$abilities[$id]) ? self::$abilities[$id] : false;
		}

		// Get ability input schema
		public static function get_ability_input_schema($id) {

			$ability = self::get_ability($id);

			if($ability === false) { return false; }

			return isset($ability['input_schema']) ? $ability['input_schema'] : false;
		}

		// Get ability output schema
		public static function get_ability_output_schema($id) {

			$ability = self::get_ability($id);

			if($ability === false) { return false; }

			return isset($ability['output_schema']) ? $ability['output_schema'] : false;
		}
	}
