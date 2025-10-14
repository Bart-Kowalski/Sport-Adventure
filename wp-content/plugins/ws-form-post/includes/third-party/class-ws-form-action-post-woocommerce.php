<?php

	class WS_Form_Action_Post_WooCommerce extends WS_Form_Action_Post {

		public $woocommerce_file_fields = array();
		public $woocommerce_update_fields = array();
		public $woocommerce_attachments = array();

		public $product_image_gallery_attachment_ids = array();
		public $product_image_gallery_mapped = false;
		public $product_image_product_gallery_field_ids = array();

		// Construct
		public function __construct() {

			// Settings
			add_filter('wsf_action_post_config_meta_keys', array($this, 'hook_config_meta_keys'), 10, 1);
			add_filter('wsf_action_post_action_settings', array($this, 'hook_action_settings'), 10, 1);
			add_filter('wsf_action_post_config_settings_form_admin', array($this, 'hook_config_settings_form_admin'), 10, 1);

			// Form building
			add_filter('wsf_action_post_list_fields', array($this, 'hook_list_fields'), 10, 2);
			add_filter('wsf_action_post_list_fields_meta_data', array($this, 'hook_list_fields_meta_data'), 10, 2);
			add_filter('wsf_action_post_form_actions', array($this, 'hook_form_actions'), 10, 4);
			add_filter('wsf_action_post_form_meta', array($this, 'hook_form_meta'), 10, 3);
			add_filter('wsf_action_post_fields', array($this, 'hook_fields'), 10, 2);

			// Form submitting
			add_filter('wsf_action_post_field_mapping', array($this, 'hook_field_mapping'), 10, 6);
			add_filter('wsf_action_post_meta_input', array($this, 'hook_meta_input'), 10, 5);
			add_filter('wsf_action_post_file_single', array($this, 'hook_file_single'), 10, 1);
			add_action('wsf_action_post_post_meta', array($this, 'hook_post_meta'), 10, 6);

			// Form population
			add_filter('wsf_action_post_get', array($this, 'hook_get'), 10, 3);

			// Logo
			add_filter('wsf_action_post_svg_custom_field_logos', array($this, 'hook_svg_custom_field_logos'), 10, 2);
		}

		// Config meta keys
		public function hook_config_meta_keys($config_meta_keys) {

			// WooCommerce - Fields
			$config_meta_keys['action_post_woocommerce_meta_key'] = array(

				'label'							=>	__('WooCommerce Field', 'ws-form-post'),
				'type'							=>	'select',
				'options'						=>	is_admin() ? WS_Form_WooCommerce::woocommerce_get_fields_all('post', false, false, false, true, false) : array(),
				'options_blank'					=>	__('Select...', 'ws-form-post')
			);

			// WooCommerce - Field mapping
			$config_meta_keys['action_post_field_mapping_woocommerce'] = array(

				'label'						=>	__('WooCommerce Field Mapping', 'ws-form-post'),
				'type'						=>	'repeater',
				'help'						=>	__('Map WS Form fields to WooCommerce fields.', 'ws-form-post'),
				'meta_keys'					=>	array(

					'ws_form_field',
					'action_post_woocommerce_meta_key'
				),
				'meta_keys_unique'			=>	array(

					'action_post_woocommerce_meta_key'
				),
				'condition'					=>	array(

					array(

						'logic'			=>	'!=',
						'meta_key'		=>	'action_post_list_id',
						'meta_value'	=>	''
					)
				)
			);

			// Populate - WooCommerce - Field mapping
			$config_meta_keys['action_post_form_populate_field_mapping_woocommerce'] = array(

				'label'						=>	__('WooCommerce Field Mapping', 'ws-form-post'),
				'type'						=>	'repeater',
				'help'						=>	__('Map WooCommerce field values to WS Form fields.', 'ws-form-post'),
				'meta_keys'					=>	array(

					'action_post_woocommerce_meta_key',
					'ws_form_field'
				),
				'meta_keys_unique'			=>	array(

					'ws_form_field'
				),
				'condition'	=>	array(

					array(

						'logic'			=>	'==',
						'meta_key'		=>	'form_populate_action_id',
						'meta_value'	=>	'post'
					),

					array(

						'logic'			=>	'!=',
						'meta_key'		=>	'form_populate_list_id',
						'meta_value'	=>	''
					),

					array(

						'logic'			=>	'==',
						'meta_key'		=>	'form_populate_enabled',
						'meta_value'	=>	'on'
					)
				)
			);

			// Gallery mapping
			$config_meta_keys['action_post_form_populate_product_gallery_field_id'] = array(

				'label'						=>	__('Gallery Mapping', 'ws-form-post'),
				'type'						=>	'select',
				'options'					=>	'fields',
				'options_blank'				=>	__('Select...', 'ws-form-post'),
				'fields_filter_type'		=>	array('file', 'signature'),
				'help'						=>	__('Select which file field to use for the gallery. Only file fields of type DropzoneJS using the Media Library file handler are compatible with this feature.', 'ws-form-post'),
				'condition'	=>	array(

					array(

						'logic'			=>	'==',
						'meta_key'		=>	'form_populate_action_id',
						'meta_value'	=>	'post'
					),

					array(

						'logic'			=>	'==',
						'meta_key'		=>	'form_populate_list_id',
						'meta_value'	=>	'product'
					),

					array(

						'logic'			=>	'==',
						'meta_key'		=>	'form_populate_enabled',
						'meta_value'	=>	'on'
					)
				)
			);

			return $config_meta_keys;
		}

		// Process action settings
		public function hook_action_settings($settings) {

			array_splice($settings['meta_keys'], 7, 0, 'action_post_field_mapping_woocommerce');

			return $settings;
		}

		// Process form populate
		public function hook_config_settings_form_admin($meta_keys) {

			self::meta_key_inject($meta_keys, 'action_post_form_populate_field_mapping_woocommerce', 'form_populate_tag_mapping');

			$meta_keys[] = 'action_post_form_populate_product_gallery_field_id';

			return $meta_keys;
		}

		// Process list fields
		public function hook_list_fields($list_fields, $list_id) {

			if($list_id !== 'product') { return $list_fields; }

			$fields = WS_Form_WooCommerce::woocommerce_get_fields_all('post', $list_id, false, true, false, false);

			// Process list fields
			$woocommerce_fields_to_list_fields_return = WS_Form_WooCommerce::woocommerce_fields_to_list_fields($fields, $list_fields['group_index'], $list_fields['section_index']);

			// Merge return
			$list_fields['list_fields'] = array_merge_recursive($list_fields['list_fields'], $woocommerce_fields_to_list_fields_return['list_fields']);
			$list_fields['group_index'] = $woocommerce_fields_to_list_fields_return['group_index'];
			$list_fields['section_index'] = $woocommerce_fields_to_list_fields_return['section_index'] + 1;

			return $list_fields;
		}

		// Process list fields meta data
		public function hook_list_fields_meta_data($list_fields_meta_data, $list_id) {

			if($list_id !== 'product') { return $list_fields_meta_data; }

			$fields = WS_Form_WooCommerce::woocommerce_get_fields_all('post', $list_id, false, true, false, false);

			$woocommerce_fields_to_meta_data_return = WS_Form_WooCommerce::woocommerce_fields_to_meta_data($fields, $list_fields_meta_data['group_index'], $list_fields_meta_data['section_index']);

			// Process return
			$list_fields_meta_data['group_meta_data'] = array_merge_recursive($list_fields_meta_data['group_meta_data'], $woocommerce_fields_to_meta_data_return['group_meta_data']);
			$list_fields_meta_data['section_meta_data'] = array_merge_recursive($list_fields_meta_data['section_meta_data'], $woocommerce_fields_to_meta_data_return['section_meta_data']);
			$list_fields_meta_data['group_index'] = $woocommerce_fields_to_meta_data_return['group_index'];
			$list_fields_meta_data['section_index'] = $woocommerce_fields_to_meta_data_return['section_index'] + 1;

			return $list_fields_meta_data;
		}

		// Process form actions
		public function hook_form_actions($form_actions, $form_field_id_lookup_all, $form_field_type_lookup, $list_id) {

			if(!WS_Form_WooCommerce::is_woocommerce_post_type($list_id)) { return $form_actions; }

			$fields = WS_Form_WooCommerce::woocommerce_get_fields_all('post', $list_id, false, true, false, false);

			$woocommerce_fields_to_list_fields_return = WS_Form_WooCommerce::woocommerce_fields_to_list_fields($fields);
			$list_fields = $woocommerce_fields_to_list_fields_return['list_fields'];

			$form_actions['post']['meta']['action_post_field_mapping_woocommerce'] = array();

			foreach($list_fields as $list_field) {

				if(!isset($form_field_id_lookup_all[$list_field['id']])) { continue; }

				$form_actions['post']['meta']['action_post_field_mapping_woocommerce'][] = array(

					'ws_form_field' => $form_field_id_lookup_all[$list_field['id']],
					'action_post_woocommerce_meta_key' => $list_field['id']
				);
			}

			$form_actions['post']['meta']['action_post_gallery_mapping'] = array();

			foreach($form_field_id_lookup_all as $id => $field_id) {

				if(!isset($form_field_type_lookup[$field_id])) { continue; }

				$field_type = $form_field_type_lookup[$field_id];

				switch($field_type) {

					case 'file' :
					case 'signature' :

						if($id == 'product_gallery_field_id') {

							$form_actions['post']['meta']['action_post_gallery_mapping'][] = array(

								'ws_form_field' => $field_id
							);
						}

						break;
				}
			}

			return $form_actions;
		}

		// Process form meta
		public function hook_form_meta($form_meta, $form_field_id_lookup_all, $list_id) {

			if(!WS_Form_WooCommerce::is_woocommerce_post_type($list_id)) { return $form_meta; }

			$fields = WS_Form_WooCommerce::woocommerce_get_fields_all('post', $list_id, false, false, true, false);

			foreach($fields as $field) {

				if(!isset($form_field_id_lookup_all[$field['value']])) { continue; }

				$form_meta['action_post_form_populate_field_mapping_woocommerce'][] = array(

					'action_post_woocommerce_meta_key' => $field['value'],
					'ws_form_field' => $form_field_id_lookup_all[$field['value']]
				);
			}

			if(isset($form_field_id_lookup_all['product_gallery_field_id'])) {

				$form_meta['action_post_form_populate_product_gallery_field_id'] = $form_field_id_lookup_all['product_gallery_field_id'];
			}

			return $form_meta;
		}

		// Process fields
		public function hook_fields($fields, $list_id) {

			if($list_id !== 'product') { return $fields; }

			$fields[] = (object) array('id' => 'product_gallery_field_id', 'name' => __('Gallery', 'ws-form-post'), 'type' => 'file', 'required' => false, 'meta' => array('accept' => 'image/jpeg,image/gif,image/png', 'sub_type' => 'dropzonejs', 'file_handler' => 'attachment', 'multiple_file' => 'on'), 'no_map' => true);

			return $fields;
		}

		// Process post meta
		public function hook_post_meta($form, $submit, $config, $post_id, $list_id, $taxonomy_tags) {

			if($list_id !== 'product') { return true; }

			// Get product_type
			$product_type = 'simple';

			// Check if product type set in tags
			foreach($taxonomy_tags as $taxonomy => $tags) {

				if($taxonomy != 'product_type') { continue; }

				if(count($tags) === 0) { continue; }

				$tags = array_unique($tags);

				foreach($tags as $tag) {

					if(empty($tag)) { continue; }

					$product_type = $tag;

					continue;
				}
			}

			// Check if product type is term ID
			if(is_numeric($product_type)) {

				$product_type_term = get_term($product_type);

				if(!is_wp_error($product_type_term)) {

					$product_type = $product_type_term->name;
				}
			}

			// Get product class name
			$product_classname = WC_Product_Factory::get_product_classname($post_id, $product_type);

			// Get new product
			$new_product = new $product_classname($post_id);

			// Save product
			$new_product->save();

			// Product image gallery
			if($this->product_image_gallery_mapped) {

				update_post_meta($post_id, '_product_image_gallery', implode(',', $this->product_image_gallery_attachment_ids));
			}

			return true;
		}

		// Process meta input
		public function hook_meta_input($meta_input, $form, $submit, $config, $list_id) {

			if($list_id !== 'product') { return $meta_input; }

			// Field mapping
			$field_mapping_woocommerce = parent::get_config($config, 'action_post_field_mapping_woocommerce', array());
			if(!is_array($field_mapping_woocommerce)) { $field_mapping_woocommerce = array(); }

			// Run through each field mapping
			foreach($field_mapping_woocommerce as $field_map_woocommerce) {

				// Get ACF key
				$meta_key = $field_map_woocommerce['action_post_woocommerce_meta_key'];
				if($meta_key == '') { continue; }

				// Get submit value
				$field_id = $field_map_woocommerce['ws_form_field'];
				$meta_value = parent::get_submit_value($submit, WS_FORM_FIELD_PREFIX . $field_id, false, true);
				if($meta_value === false) { continue; }

				// Handle arrays
				if(is_array($meta_value)) { $meta_value = implode(',', $meta_value); }

				// Process meta value
				$meta_value = WS_Form_WooCommerce::woocommerce_ws_form_field_value_to_woocommerce_meta_value($meta_key, $meta_value);

				$meta_input[$meta_key] = $meta_value;
			}

			// Check pricing to create _price meta
			if(isset($meta_input['_regular_price'])) {

				$meta_input['_price'] = $meta_input['_regular_price'];
			}
			if(isset($meta_input['_sale_price'])) {

				if($meta_input['_sale_price'] > $meta_input['_regular_price']) {

					$meta_input['_sale_price'] = '';
				}

				if($meta_input['_sale_price'] != '') {

					$meta_input['_price'] = $meta_input['_sale_price'];
				}
			}

			return $meta_input;
		}

		// Process file object
		public function hook_file_single($file_single) {

			if(empty($file_single) || !is_array($file_single) || !isset($file_single['field_id'])) { return $file_single; }

			$field_id = $file_single['field_id'];

			$attachment_id = isset($file_single['attachment_id']) ? absint($file_single['attachment_id']) : false;

			if($attachment_id !== false) {

				// Add to product gallery
				if(in_array($field_id, $this->product_image_product_gallery_field_ids)) {

					$this->product_image_gallery_attachment_ids[] = $attachment_id;
					$this->product_image_gallery_mapped = true;
				}

			} else {

				// Add to product gallery
				if(in_array($field_id, $this->product_image_product_gallery_field_ids)) {

					// Error
					parent::error_js(sprintf(__("Field ID %u assigned to the WooCommerce product gallery must use the 'Media Library' file handler" , 'ws-form-post'), $field_id));

					// Halt
					return 'halt';
				}
			}

			return $file_single;
		}

		// Process attachment mapping
		public function hook_field_mapping($field_mapping_return, $form, $submit, $config, $deduplication_mapping, $list_id) {

			if($list_id !== 'product') { return $field_mapping_return; }

			// Gallery mapping
			$gallery_mapping = parent::get_config($config, 'action_post_gallery_mapping', array());
			if(!is_array($gallery_mapping)) { $gallery_mapping = array(); }

			// Process gallery mapping
			foreach($gallery_mapping as $gallery_map) {

				// Get field ID
				$field_id = absint($gallery_map['ws_form_field']);
				if($field_id == 0) { continue; }

				// Add to product image gallery field ID array
				$this->product_image_product_gallery_field_ids[] = $field_id;

				// Check to see if this field is attachment mapped, if it isn't, add it
				$field_already_mapped = false;
				foreach($field_mapping_return['attachment_mapping'] as $attachment_map) {

					if($attachment_map['ws_form_field'] == $field_id) {

						$field_already_mapped = true;
						break;
					}
				}
				if(!$field_already_mapped) {

					$field_mapping_return['attachment_mapping'][] = array('ws_form_field' => $field_id);
				}
			}

			return $field_mapping_return;
		}

		// Process get
		public function hook_get($return_array, $form, $post_id) {

			// Get ACF field mappings
			$field_mapping_woocommerce = WS_Form_Common::get_object_meta_value($form, 'action_post_form_populate_field_mapping_woocommerce', '');
			if(is_array($field_mapping_woocommerce)) {

				// Run through each mapping
				foreach($field_mapping_woocommerce as $field_map_woocommerce) {

					// Get WooCommerce meta key
					$meta_key = $field_map_woocommerce->action_post_woocommerce_meta_key;
					if($meta_key == '') { continue; }

					// Get field ID
					$field_id = $field_map_woocommerce->ws_form_field;
					if($field_id == '') { continue; }

					// Get meta value
					$meta_value = get_post_meta($post_id, $meta_key, true);

					// Set field return
					$return_array['fields'][$field_id] = $meta_value;
				}
			}

			// Get gallery field ID
			$product_gallery_field_id = absint(WS_Form_Common::get_object_meta_value($form, 'action_post_form_populate_product_gallery_field_id', ''));
			if($product_gallery_field_id > 0) {

				// Get meta value
				$meta_value = get_post_meta($post_id, '_product_image_gallery', true);

				// Get attachment IDs
				$attachment_ids = explode(',', $meta_value);

				$file_objects = array();

				foreach($attachment_ids as $attachment_id) {

					$attachment_id = absint($attachment_id);
					if(!$attachment_id) { continue; }

					$file_object = WS_Form_File_Handler::get_file_object_from_attachment_id($attachment_id);
					if($file_object === false) { continue; }

					$file_objects[] = $file_object;
				}

				if(count($file_objects) > 0) {

					$return_array['fields'][$product_gallery_field_id] = $file_objects;
				}
			}

			return $return_array;
		}

		// Logo
		public function hook_svg_custom_field_logos($svg_custom_field_logos, $list_id) {

			if(WS_Form_WooCommerce::is_woocommerce_post_type($list_id)) {

				$svg_custom_field_logos[] = '<path fill="#7e58a4" d="M2 5.3h18c1.1 0 2 .9 2 2v6.8c0 1.1-.9 2-2 2h-6.4l.9 2.2-3.9-2.2H2c-1.1 0-2-.9-2-2V7.3c0-1.1.9-2 2-2z"/><path fill="#ffffff" d="M1.1 7.1c.1-.1.4-.2.6-.2.5 0 .7.2.8.6.2 1.9.5 3.5.9 4.8l2-3.7c.1-.4.3-.6.6-.6.4 0 .6.2.7.8.2 1 .5 2.1.9 3.1.2-2.3.6-3.9 1.2-4.9.1-.2.3-.4.6-.4.2 0 .4 0 .6.2.2.1.3.3.3.5 0 .1 0 .3-.1.4-.4.7-.6 1.7-.9 3.2-.3 1.4-.3 2.6-.3 3.4 0 .2 0 .4-.1.6-.1.2-.3.3-.5.3s-.5-.1-.7-.3c-.8-.9-1.5-2.1-2-3.8l-1.3 2.6c-.5 1-1 1.5-1.4 1.6-.2 0-.5-.2-.6-.6-.4-1.3-.9-3.6-1.4-6.9-.1-.3 0-.5.1-.7zM20.4 8.6c-.3-.5-.8-.9-1.4-1-.2 0-.3-.1-.5-.1-.9 0-1.6.4-2.1 1.3-.5.8-.7 1.6-.7 2.5 0 .7.1 1.3.4 1.8.3.5.8.9 1.4 1 .2 0 .3.1.5.1.9 0 1.6-.4 2.1-1.3.5-.8.7-1.6.7-2.5.1-.8-.1-1.4-.4-1.8zm-1.1 2.5c-.1.6-.4 1-.7 1.3-.3.2-.5.3-.7.3-.2 0-.4-.2-.5-.6-.1-.3-.2-.5-.2-.8 0-.2 0-.4.1-.7.1-.4.2-.7.5-1.1.2-.4.6-.6.9-.5.2 0 .4.2.5.6.1.3.2.5.2.8 0 .2-.1.4-.1.7zM14.8 8.6c-.3-.5-.8-.9-1.4-1-.2 0-.3-.1-.5-.1-.9 0-1.6.4-2.1 1.3-.5.8-.7 1.6-.7 2.5 0 .7.1 1.3.4 1.8.3.5.8.9 1.4 1 .2 0 .3.1.5.1.9 0 1.6-.4 2.1-1.3.5-.8.7-1.6.7-2.5 0-.8-.1-1.4-.4-1.8zm-1.1 2.5c-.1.6-.4 1-.7 1.3-.3.2-.5.3-.7.3-.2 0-.4-.2-.5-.6-.1-.3-.2-.5-.2-.8 0-.2 0-.4.1-.7.1-.4.2-.7.5-1.1.2-.4.5-.6.8-.5.2 0 .4.2.5.6.1.3.2.5.2.8v.7z"/>';
			}

			return $svg_custom_field_logos;
		}
	}

	new WS_Form_Action_Post_WooCommerce();

