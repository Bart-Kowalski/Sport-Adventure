<?php

	class WS_Form_JSON extends WS_Form_Core {

		public $id;
		public $label;

		const CREATE_FROM_JSON_MAX_FIELDS = 100;

		public function __construct() {

			$this->id = 0;
			$this->label = __('New Form', 'ws-form');
		}

		// Create or update form from JSON
		public function json_to_form($json) {

			// Attempt to decode output
			$json_decoded = json_decode($json);

			// Check form
			if(
				!is_object($json_decoded) ||
				!isset($json_decoded->label) ||
				!isset($json_decoded->groups) ||
				!is_array($json_decoded->groups) ||
				!isset($json_decoded->groups[0]) ||
				!is_object($json_decoded->groups[0])
			) {
				throw new ErrorException(__('Invalid form data. Please try again.', 'ws-form'));
			}

			// Create list
			$list = array(

				'label' => sanitize_text_field($json_decoded->label)
			);

			// Check count of fields
			$field_count_max = apply_filters('wsf_create_from_json_max_fields', self::CREATE_FROM_JSON_MAX_FIELDS);

			// Create list fields
			$list_fields = array();
			$list_fields_meta_data = array(

				'group_meta_data' => array(),
				'section_meta_data' => array(),
			);
			$sort_index = 0;
			$field_count = 0;

			foreach($json_decoded->groups as $group_index => $group) {

				// Check group
				if(
					!isset($group->label) ||
					!is_string($group->label) ||
					!isset($group->sections) ||
					!is_array($group->sections) ||
					!isset($group->sections[0]) ||
					!is_object($group->sections[0])
				) {
					throw new ErrorException(__('Invalid group data. Please try again.', 'ws-form'));
				}

				$list_fields_meta_data['group_meta_data']['group_' . $group_index]['label'] = sanitize_text_field($group->label);

				foreach($group->sections as $section_index => $section) {

					// Check section
					if(
						!isset($section->label) ||
						!is_string($section->label) ||
						!isset($section->fields) ||
						!is_array($section->fields) ||
						!isset($section->fields[0]) ||
						!is_object($section->fields[0])
					) {
						throw new ErrorException(__('Invalid section data. Please try again.', 'ws-form'));
					}

					if(!isset($list_fields_meta_data['section_meta_data']['group_' . $group_index])) {

						$list_fields_meta_data['section_meta_data']['group_' . $group_index] = array();
					}

					$list_fields_meta_data['section_meta_data']['group_' . $group_index]['section_' . $section_index] = array(

						'label' => sanitize_text_field($section->label)
					);

					foreach($section->fields as $field_index => $field) {

						// Check field
						if(
							!isset($field->label) ||
							!is_string($field->label) ||
							!isset($field->type) ||
							!is_string($field->type)
						) {
							throw new ErrorException(__('Invalid field data. Please try again.', 'ws-form'));
						}

						// Read field data
						$field_label = sanitize_text_field($field->label);
						$field_type = sanitize_text_field($field->type);

						// Check field type
						if(!in_array($field_type, array_keys(self::field_types()))) {

							continue;
						}

						// ID
						$field_id = isset($field->id) ? absint($field->id) : false;
						if(!$field_id) { continue; }

						// Required
						$field_required = isset($field->required) ? $field->required : false;

						// Default value
						$field_default_value = isset($field->default_value) ? sanitize_text_field($field->default_value) : '';

						// Placeholder
						$field_placeholder = isset($field->placeholder) ? sanitize_text_field($field->placeholder) : '';

						// Help
						$field_help = isset($field->help) ? sanitize_text_field($field->help) : '';

						// Step
						$field_step = isset($field->step) ? floatval(sanitize_text_field($field->step)) : '';

						// Field width factor
						$field_width_factor = isset($field->width_factor) ? floatval($field->width_factor) : 1;
						if(
							($field_width_factor < 0) ||
							($field_width_factor >= 1)
						) {
							$field_width_factor = 1;
						}

						$list_fields[] = array(

							'id' => 			$field_id,
							'label' => 			$field_label, 
							'label_field' => 	$field_label, 
							'type' => 			$field_type, 
							'required' => 		$field_required, 
							'default_value' => 	$field_default_value, 
							'pattern' => 		false, 
							'placeholder' => 	$field_placeholder, 
							'input_mask' =>		false,
							'help' => 			$field_help, 
							'visible' =>		true,
							'meta' =>			self::get_meta($field),
							'width_factor' =>	$field_width_factor,
							'group_index' =>	$group_index,
							'section_index' =>	$section_index,
							'sort_index' => 	$field_index
						);

						$field_count++;

						if($field_count > $field_count_max) {

							throw new ErrorException(__('Too many fields returned. Please try again.', 'ws-form'));
						}
					}
				}
			}

			// Create form fields
			$form_fields = array(

				'opt_in_field' => array(

					'type'	=>	'checkbox',
					'label'	=>	__('GDPR', 'ws-form'),
					'meta'	=>	array(

						'data_grid_checkbox' => WS_Form_Common::build_data_grid_meta('data_grid_checkbox', false, false, array(

							array(

								'id'		=> 1,
								'data'		=> array(__('I consent to #blog_name storing my submitted information so they can respond to my inquiry', 'ws-form'))
							)
						))
					)
				),

				'submit' => array(

					'type'			=>	'submit'
				)
			);

			// Create form actions
			$form_actions = array(

				'email',

				'message',

				'database'
			);

			// Create form conditionals
			$form_conditionals = false;

			// Create form meta
			$form_meta = false;

			$ws_form_form = new WS_Form_Form();

			// Create new form
			if($this->id == 0) {

				$ws_form_form->db_create(false);
				$this->id = $ws_form_form->id;
			}

			// Check form created
			if($ws_form_form->id > 0) {

				// Modify form so it matches action list
				WS_Form_Action::update_form($this->id, false, false, false, $list, $list_fields, $list_fields_meta_data, $form_fields, $form_actions, $form_conditionals, $form_meta);

				return $this->id;

			} else {

				return false;
			}
		}

		// Convert action field to WS Form meta key
		public function get_meta($field) {

			$type = WS_Form_Common::get_object_property($field, 'type');

			// Get WS Form meta configurations for action field types
			switch($type) {

				// text_editor
				case 'note' :
				case 'texteditor' :

					$text_editor = sanitize_text_field(WS_Form_Common::get_object_property($field, 'text_editor'));

					if(!empty($text_editor)) {

						return(array('text_editor' => $text_editor));

					} else {

						return false;
					}

				// Build data grids
				case 'select' :
				case 'checkbox' :
				case 'radio' :

					// Get options
					$options = WS_Form_Common::get_object_property($field, 'options');

					// Check options
					if(
						!is_array($options) ||
						(count($options) == 0)
					) {
						$options = array((object) array('value' => 'on', 'text' => $field->label));
					}

					// Get base meta
					$meta_keys = WS_Form_Config::get_meta_keys();
					if(
						!isset($meta_keys['data_grid_' . $type]) ||
						!isset($meta_keys['data_grid_' . $type]['default'])
					) {
						return false;
					}

					$meta = $meta_keys['data_grid_' . $type]['default'];

					// Build new rows
					$rows = array();
					$id = 1;
					foreach($options as $option) {

						if(
							!is_object($option) ||
							!isset($option->value) ||
							!isset($option->text)
						) {
							continue;
						}

						$rows[] = array(

							'id'		=> $id,
							'data'		=> array(

								sanitize_text_field(WS_Form_Common::get_object_property($option, 'value')),
								sanitize_text_field(WS_Form_Common::get_object_property($option, 'text'))
							)
						);

						$id++;
					}

					// Modify meta
					$meta['groups'][0]['rows'] = $rows;

					// Columns
					$meta['columns'] = array(

						array('id' => 0, 'label' => __('Value', 'ws-form')),
						array('id' => 1, 'label' => __('Label', 'ws-form'))
					);

					return(array(

						'data_grid_' . $type => $meta,
						$type . '_field_label' => 1,
						$type . '_field_parse_variable' => 1
					));

				default :

					return false;
			}
		}

		// Field types that can be used to build a form from JSON
		public function field_types() {

			$field_types_allowed = array(

				'checkbox',
				'email',
				'note',
				'number',
				'radio',
				'select',
				'tel',
				'text',
				'textarea',
				'texteditor',
				'url',
				'color',
				'datetime',
				'file',
				'hidden',
				'price',
				'rating',
				'validate',
			);

			// Get field types
			$field_types = WS_Form_Config::get_field_types_flat(false);

			$field_types_allowed_return = array();

			foreach($field_types_allowed as $field_type) {

				if(!isset($field_types[$field_type])) { continue; }

				$field_type_config = $field_types[$field_type];

				$field_types_allowed_return[$field_type] = array(

					'id' => $field_type,
					'label' => esc_html($field_type_config['label']),
					'description' => isset($field_type_config['description']) ? esc_html($field_type_config['description']) : ''
				);
			}

			return $field_types_allowed_return;
		}

		// Get AI prompt that should build a JSON string compatible with this class
		public function get_ai_prompt_form() {

			return "Here is how the JSON string must be formatted.

= Example JSON =
An example format of the JSON object to create is:

" . self::get_form_example_json() . "

DO NOT use the same groups, sections and fields in this example.

= General format =
form->groups[0]->section[0]->fields

All forms specified should have:

- 1 group (Tab)
- 1 section
- 1 or more fields

= Allowed field types =
email = An HTML email input field.
note = Used for adding notes to the form that are only seen in the WS Form layout editor by the administrator.
text = An HTML text input field.
textarea = An HTML textarea field.
number = An HTML number input field.
tel = An HTML tel input field. Used for phone numbers.
url = An HTML url input field. Used for web addresses.
select = An HTML select field.
checkbox = One or more HTML input checkbox fields.
radio = One or more HTML input radio fields.
texteditor = Outputs text to the form. Use this for showing the user interacting with the form useful instructions.
color = An HTML color input.
datetime = An HTML text field that returns a date and/or time.
file = An HTML file input field. Use for uploading files.
hidden = An HTML hidden input field.
price = A field that lets a user enter a price or amount, such a loan amount.
rating = A rating field that lets a user rate something with a number from 0 to 5.

= Field keys =
Each field has the following keys:

id = A unique ID for the field, starting with 1 and increments by 1 for each field added
label = The label of the field. This key is mandatory.
type = The type of the field. This key is mandatory. Available types are: text, textarea, email, hidden, note, number, price, tel, url, datetime, select, checkbox, radio, file, texteditor, rating, color
required = Whether or not the field is required. Set to true if required. Omit if not required.
placeholder = An optional placeholder for the field. Omit if there is no placeholder.
help = Optional help text shown underneath each field. Omit if there is no help text.
width_factor = How wide the field should be on the form, e.g. 0.5 = Half width. Omit if full width.
options = Only used for select, checkbox and radio fields to specify the options. Omit if not a select, checkbox or checkbox field.
text_editor = Enter text to show for a texteditor or note field.
default_value = Only use this if it is appropriate to add a default value to a field.
step = Used for number fields only and sets the step attribute. If blank it defaults to 1. Example value: 0.01 which allows numbers with 2 decimal places. Same as the HTML spec for number fields.

= Field type rules =
Options for select, checkbox and radio field types are stored in the following example format:

'options':[{'value':'option_1','text':'Option 1'},{'value':'option_2','text':'Option 2'}]

In this example, 'value' is the value stored when the form is submitted and 'text' is the label shown to the person completing the form. 

The texteditor field type is used for adding copy to the form such as useful information or instructions. The copy is specified by using the key: text_editor

= Returning the value of a field =
#field(id) returns the value of a field, where id is the number ID of the field you want to reference.

If #field(id) is used within #calc(), for example #calc(#field(123)), it will ALWAYS return a numeric value, NOT a string. Instead of checking against strings like 'wood' or 'aluminum', #field() should return 0, 1, 2, 3, etc., and the conditions should check against those numbers. The value returned by #field() could also just be the literal number required in the value column.

= Calculations using the #calc() variable =
If the form calls for a calculation, use #calc() in the default_value key of a field. Here are some examples:

Add the values of field ID 1 and 2 together:
	#calc(#field(1) + #field(2))

Subtract a values from another:
	#calc(#field(1) - #field(2))

Multiply two values:
	#calc(#field(1) * #field(2))

Divide two values:
	#calc(#field(1) / #field(2))

The #calc() variable gets assessed like a regular JavaScript mathematical expression. Ensure all parameters within #calc() are numeric.

There are other variables that can be used for mathematical functions. Here are some examples:

Absolute: #abs(input)
Ceiling: #ceil(input)
Cosine: #cos(input)
Euler's: #exp(input)
Exponentiation: #pow(base, exponent)
Floor: #floor(input)
Logarithmic: #log(input)
Minimum: #min(50,input)
Maximum: #max(50,input)
Negative: #negative(input)
Positive: #positive(input)
Round: #round(input)
Sine: #sin(input)
Square Root: #sqrt(input)
Tangent: #tan(input)

Here's an example that should not be used on the form itself, but explains how #calc() might be formatted:

#calc(#field(1) * ((#field(2) / 3.5) + #field(3)))

= Rules for calculations =
These strict rules must be adhered to if the form includes calculations:

1. Open and closing brackets in #calc() must be correctly balanced. Do not miss closing brackets.
2. If an input or output relates to a price or currency amount, use field type: price
3. If an input or output relates to a numeric value (not a price) that could have decimals, set the step key to 'any'.
4. #field() used in #calc() will always return a numeric value, never a string. Don't do (#field(194101) == 'triple' ? 40 : 0), instead set the value of the 194101 field to be 40 or 0.
5. There should always be one or more visible outputs, using a number, price or text field.
6. To avoid too many nested brackets in #calc(), break the calculation down using hidden fields.
7. Use hidden fields to break calculations into smaller manageable chunks to make #calc() easier to understand.
= JSON output rules =
The form JSON must adhere to these strict rules:

1. Ensure only the allowed field types are used.
2. Do not format the JSON with new lines, indentation or tabulation. Minify the JSON.
3. Do not include an opt-in or submit button in the field array.
4. Do not add 'Full Name' or 'Your Name' fields. Always have separate first and last name fields.
5. The only allowed width_factor value is 0.5.
6. If there are two fields that are related to one another (e.g. from and to) set the width_factor to 0.5. Only do this if you can place two fields side-by-side.
7. When specifying options for select, checkbox or radio field types, provide a comprehensive and full list of options rather than just a sample.
8. Do not wrap the JSON string in anything else, return only the JSON string.

Very strict rule: Only include the minified JSON object in the output.";
		}

		// Get exampe form - JSON
		public function get_form_example_json() {

			return wp_json_encode(self::get_form_example_array());
		}

		// Get exampe form - Array
		public function get_form_example_array() {

			return array(

				'id' => 1,
				'label' => 'This is the name of the form',

				'groups' => array(

					array(

						'id' => 1,
						'label' => 'This is the name of a tab, e.g. Tab',

						'sections' => array(

							array(

								'id' => 1,
								'label' => 'This is the name of a section, e.g. Section',

								'fields' => array(

									array(

										'id' => 1,
										'label' => 'Instructions',
										'type' => 'texteditor',
										'text_editor' => 'Example instructions for the form.'
									),

									array(

										'id' => 2,
										'label' => 'First Name',
										'type' => 'text',
										'required' => true,
										'width_factor' => 0.5
									),

									array(

										'id' => 3,
										'label' => 'Last Name',
										'type' => 'text',
										'required' => true,
										'width_factor' => 0.5
									),

									array(

										'id' => 4,
										'label' => 'Email',
										'type' => 'email',
										'required' => true
									),

									array(

										'id' => 5,
										'label' => 'Phone',
										'type' => 'phone',
										'required' => false
									),

									array(

										'id' => 6,
										'label' => 'Inquiry',
										'type' => 'textarea',
										'placeholder' => 'How can we help?',
										'help' => 'Example help text.'
									),

									array(

										'id' => 7,
										'label' => 'Preferred contact method',
										'type' => 'radio',
										'options' => array(

											array('value' => 'email', 'text' => 'Email'),
											array('value' => 'phone', 'text' => 'Phone'),
										)
									)
								)
							)
						)
					)
				)
			);
		}
	}
