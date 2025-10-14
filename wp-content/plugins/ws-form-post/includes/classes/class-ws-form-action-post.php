<?php

	class WS_Form_Action_Post extends WS_Form_Action {

		public $id = 'post';
		public $pro_required = true;
		public $label;
		public $label_action;
		public $events;
		public $multiple = true;
		public $configured = false;
		public $priority = 25;
		public $can_repost = true;
		public $form_add = false;

		// Add new features
		public $add_new_reload = false;

		// Licensing
		private $licensing;

		// Config
		public $method;
		public $list_id = false;
		public $status;

		public $field_mapping;
		public $clear_hidden_meta_values;
		public $meta_mapping_custom;

		public $meta_mapping;
		public $tag_mapping;
		public $terms;
		public $attachment_mapping;
		public $gallery_mapping;
		public $featured_image;
		public $deduplication_mapping;
		public $author;
		public $author_restrict;
		public $post_parent;
		public $comment_status;
		public $ping_status;
		public $date;
		public $password;
		public $expose;
		public $update_previous_post;
		public $page_template;
		public $post_id;

		public $form_populate_post_id;
		public $form_populate_meta_mapping;

		// Constants
		const WS_FORM_LICENSE_ITEM_ID = 1642;
		const WS_FORM_LICENSE_NAME = 'Post Management add-on for WS Form PRO';
		const WS_FORM_LICENSE_VERSION = WS_FORM_POST_VERSION;
		const WS_FORM_LICENSE_AUTHOR = 'WS Form';
		const DEFAULT_POST_STATUS = 'publish';
		const DEFAULT_POST_TYPE = 'post';
		const DEFAULT_COMMENT_STATUS = 'open';
		const DEFAULT_PING_STATUS = 'open';

		public function __construct() {

			// Events
			$this->events = array('submit');

			// Register config filters
			add_filter('wsf_config_options', array($this, 'config_options'), 10, 1);
			add_filter('wsf_config_meta_keys', array($this, 'config_meta_keys'), 11, 2);
			add_filter('wsf_config_settings_form_admin', array($this, 'config_settings_form_admin'), 20, 1);
			add_filter('plugin_action_links_' . WS_FORM_POST_PLUGIN_BASENAME, array($this, 'plugin_action_links'), 10, 1);
			add_action('rest_api_init', array($this, 'rest_api_init'));
			add_filter('wsf_submit_clear_meta_filter_keys', array($this, 'submit_clear_meta_filter_keys'), 10, 1);

			// Licensing
			$this->licensing = new WS_Form_Licensing(

				self::WS_FORM_LICENSE_ITEM_ID,
				'post',
				self::WS_FORM_LICENSE_NAME,
				self::WS_FORM_LICENSE_VERSION,
				self::WS_FORM_LICENSE_AUTHOR,
				WS_FORM_POST_PLUGIN_ROOT_FILE
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
			$this->label = __('Post Management', 'ws-form-post');

			// Set label for actions pull down
			$this->label_action = __('Post Management', 'ws-form-post');

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
			array_unshift($links, sprintf('<a href="%s">%s</a>', WS_Form_Common::get_admin_url('ws-form-settings', false, 'tab=action_post'), __('Settings', 'ws-form-post')));

			return $links;
		}

		// Settings - Static
		public function settings_static($value, $field) {

			switch ($field) {

				case 'action_post_license_version' :

					$value = self::WS_FORM_LICENSE_VERSION;
					break;

				case 'action_post_license_status' :

					$value = $this->licensing->license_status();
					break;
			}

			return $value;
		}

		// Settings - Button
		public function settings_button($value, $field, $button) {

			switch($button) {

				case 'license_action_post' :

					$license_activated = WS_Form_Common::option_get('action_post_license_activated', false);
					if($license_activated) {

						$value = '<input class="wsf-button" type="button" data-action="wsf-mode-submit" data-mode="deactivate" value="' . __('Deactivate', 'ws-form-post') . '" />';

					} else {

						$value = '<input class="wsf-button" type="button" data-action="wsf-mode-submit" data-mode="activate" value="' . __('Activate', 'ws-form-post') . '" />';
					}

					break;
			}
			return $value;
		}

		// Settings - Update fields
		public function settings_update_fields($field, $value) {

			switch ($field) {

				case 'action_post_license_key' :

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

			global $wpdb;

			// Check action is configured properly
			if(!self::check_configured()) { return false; }

			// Load configuration
			$this->list_id = false;
			self::load_config($config);

			// Clear hidden meta values?
			$submit_parse = clone $submit;
			if($this->clear_hidden_meta_values) { $submit_parse->clear_hidden_meta_values(); }

			// Check post ID
			if($this->post_id != '') {

				$this->post_id = WS_Form_Common::parse_variables_process($this->post_id, $form, $submit_parse, 'text/plain');
			}

			// Check list ID is configured properly
			if(!self::check_list_id()) { return false; }

			// Get post type name
			$post_type = get_post_type_object($this->list_id);
			if(empty($post_type)) { return array(); }

			// Check to see if post ID saved in submit for this post type
			$meta_key_post_id = sprintf('wsf_action_post_post_id_%s', $this->list_id);
			if(
				$this->update_previous_post &&
				isset($submit->meta) &&
				isset($submit->meta[$meta_key_post_id])
			) {

				$this->post_id = absint($submit->meta[$meta_key_post_id]);
				if($this->post_id === 0) { $this->post_id = false; }
			}

			// Dedupe mapping (Only process if we are creating a new post)
			$deduplication_mapping = array();
			if(absint($this->post_id) == 0) {

				foreach($this->deduplication_mapping as $deduplication_map) {

					$deduplication_mapping[] = $deduplication_map['ws_form_field'];
				}
			}

			// Process field mapping
			$api_fields = array();
			$meta_input = array();

			foreach($this->field_mapping as $field_map) {

				// Get API field
				$api_field = $field_map['action_post_list_fields'];

				// Get submit value
				$field_id = $field_map['ws_form_field'];
				$submit_value = parent::get_submit_value($submit_parse, WS_FORM_FIELD_PREFIX . $field_id, false, true);
				if($submit_value === false) { continue; }

				// Post cannot accept arrays
				if(is_array($submit_value)) { $submit_value = implode(', ', $submit_value); }

				// Check for duplicate (Only process if we are creating a new post)
				if((absint($this->post_id) == 0) && in_array($field_id, $deduplication_mapping)) {

					$sql = sprintf("SELECT ID FROM %s WHERE post_type = '%s' AND NOT(post_status = 'trash') AND %s = '%s' LIMIT 1", $wpdb->posts, esc_sql($this->list_id), esc_sql($api_field), esc_sql($submit_value));
					$post_id = $wpdb->get_var($sql);
					if(!is_null($post_id)) {

						// Error
						self::error_js(sprintf(__('Duplicate %s submitted.' , 'ws-form-post'), strtolower($post_type->labels->singular_name)));

						// Halt
						return 'halt';
					}
				}

				// Save field
				$api_fields[$api_field] = $submit_value;
			}

			// Process field mapping filters
			$field_mapping_return = array(

				'attachment_mapping' => $this->attachment_mapping
			);
			$field_mapping_return = apply_filters('wsf_action_post_field_mapping', $field_mapping_return, $form, $submit_parse, $config, $deduplication_mapping, $this->list_id);
			if(!is_array($field_mapping_return)) { return $field_mapping_return; }	// Handles halt
			$this->attachment_mapping = $field_mapping_return['attachment_mapping'];

			// Process field meta mapping
			foreach($this->meta_mapping as $meta_map) {

				$field_id = $meta_map['ws_form_field'];
				$meta_key = $meta_map['action_post_meta_key'];

				// Parse meta key
				$meta_key = WS_Form_Common::parse_variables_process($meta_key, $form, $submit_parse, 'text/plain');

				// Check meta key
				if(empty($meta_key)) { continue; }

				// Get submit value
				$meta_value = parent::get_submit_value($submit_parse, WS_FORM_FIELD_PREFIX . $field_id, false, true);

				// Check meta value
				if($meta_value === false) { continue; }

				// Handle arrays
				if(is_array($meta_value)) { $meta_value = implode(',', $meta_value); }

				// Check for duplicate
				if(in_array($field_id, $deduplication_mapping)) {

					$sql = sprintf('SELECT ID FROM %1$s RIGHT JOIN %2$s ON %1$s.post_id = %2$s.ID WHERE %1$s.meta_key = \'%3$s\' AND %1$s.meta_value = \'%4$s\' AND %2$s.post_type = \'%5$s\' AND NOT(%2$s.post_status = \'trash\') LIMIT 1', $wpdb->postmeta, $wpdb->posts, esc_sql($meta_key), esc_sql($meta_value), esc_sql($this->list_id));
					$post_id = $wpdb->get_var($sql);
					if(!is_null($post_id)) {

						// Error
						self::error_js(sprintf(__('Duplicate %s submitted.' , 'ws-form-post'), strtolower($post_type->labels->singular_name)));

						// Halt
						return 'halt';
					}
				}

				$meta_input[$meta_key] = $meta_value;
			}

			// Process custom meta mapping
			foreach($this->meta_mapping_custom as $meta_map) {

				$meta_key = $meta_map['action_post_meta_key'];
				if(empty($meta_key)) { continue; }

				$meta_value = $meta_map['action_post_meta_value'];

				// Parse meta value
				$meta_value = WS_Form_Common::parse_variables_process($meta_value, $form, $submit_parse, 'text/plain');

				// If meta value is serialized, unserialize it
				if(is_serialized($meta_value)) {

					$meta_value_old = $meta_value;
					$meta_value = @unserialize($meta_value);

					if($meta_value === false) {

						// Error
						self::error_js(sprintf(__('Invalid serialized data: %s', 'ws-form-post'), $meta_value_old));

						// Halt
						return 'halt';
					}
				}

				$meta_input[$meta_key] = $meta_value;
			}

			// Process meta input filters
			$meta_input = apply_filters('wsf_action_post_meta_input', $meta_input, $form, $submit_parse, $config, $this->list_id);

			// Build post
			$postarr = array();

			// Author
			$post_author = isset($this->author) ? $this->author : '';
			if($post_author != '') { $postarr['post_author'] = $this->author; } 

			// Status
			if(isset($this->status) && ($this->status != '')) {

				$postarr['post_status'] = $this->status;
			}

			// Type
			$postarr['post_type'] = (isset($this->list_id) && ($this->list_id != '')) ? $this->list_id : self::DEFAULT_POST_TYPE;

			// Comment status
			if(isset($this->comment_status) && ($this->comment_status != '')) {

				$postarr['comment_status'] = $this->comment_status;
			}

			// Ping status
			if(isset($this->ping_status) && ($this->ping_status != '')) {

				$postarr['ping_status'] = $this->ping_status;
			}

			// Page template
			$page_template = (($this->list_id == 'page') && isset($this->page_template)) ? $this->page_template : '';
			if($page_template != '') { $postarr['page_template'] = $this->page_template; } 

			// Password
			if(isset($this->password) && ($this->password != '') && ($this->status != 'private')) {

				$postarr['post_password'] = WS_Form_Common::parse_variables_process($this->password, $form, $submit_parse, 'text/plain');
			}

			// Post title
			$post_title = isset($api_fields['post_title']) ? $api_fields['post_title'] : false;
			if($post_title !== false) { $postarr['post_title'] = $post_title; }

			// Post slug
			$post_name = isset($api_fields['post_name']) ? $api_fields['post_name'] : false;
			if($post_name !== false) { $postarr['post_name'] = sanitize_title($post_name); }

			// Post content
			$post_content = isset($api_fields['post_content']) ? $api_fields['post_content'] : false;
			if($post_content !== false) { $postarr['post_content'] = $post_content; }

			// Post excerpt
			$post_excerpt = isset($api_fields['post_excerpt']) ? $api_fields['post_excerpt'] : false;
			if($post_excerpt !== false) { $postarr['post_excerpt'] = $post_excerpt; }

			// Menu order
			$menu_order = isset($api_fields['menu_order']) ? $api_fields['menu_order'] : false;
			if($menu_order !== false) { $postarr['menu_order'] = absint($menu_order); }

			// Post parent
			if($this->post_parent != '') {

				$this->post_parent = absint(WS_Form_Common::parse_variables_process($this->post_parent, $form, $submit_parse, 'text/plain'));
				$postarr['post_parent'] = $this->post_parent;
			}

			// Meta input
			if(count($meta_input) > 0) { $postarr['meta_input'] = $meta_input; }

			// Post date
			if(!empty($this->date)) {

				// Parse date
				$this->date = WS_Form_Common::parse_variables_process($this->date, $form, $submit_parse, 'text/plain');

				// Convert to time
				$post_date_time = strtotime($this->date);

				// If time was converted
				if($post_date_time !== false) {

					// Set post date
					$postarr['post_date'] = date('Y-m-d H:i:s', $post_date_time);
				}
			}

			// Post ID
			$this->post_id = absint($this->post_id);
			if($this->post_id > 0) {

				if(get_post_type($this->post_id) !== $this->list_id) {

					// Error
					self::error_js(__('Invalid post type.', 'ws-form-post'));

					// Halt
					return 'halt';
				}

				$postarr['ID'] = $this->post_id;

				$post_method = __('updated', 'ws-form-post');

			} else {

				$post_method = __('added', 'ws-form-post');
			}

			if(isset($postarr['ID'])) {

				// Get post before update (used by hooks later on)
				$post_before = get_post($postarr['ID']);

				// Check for author restriction
				if($this->author_restrict) {

					if(is_null($post_before)) {

						// Error
						self::error_js(__('Invalid post ID', 'ws-form-post'));

						// Halt
						return 'halt';
					}
					$post_author_id = absint($post_before->post_author);

					if($post_author_id !== get_current_user_id()) {

						// Error
						self::error_js(__('Insufficient permissions to update', 'ws-form-post'));

						// Halt
						return 'halt';
					}
				}

				// WordPress update post
				$post_id = wp_update_post(wp_slash($postarr), true);

			} else {

				// WordPress insert post
				$post_id = wp_insert_post(wp_slash($postarr), true);
			}

			// Error management
			if(is_wp_error($post_id)) {

				// Error
				self::wp_error_process($post_id);

				// Halt
				return 'halt';
			}

			// Save post ID to submission
			$submit->post_id = $post_id;

			// Process tags
			$taxonomy_tags = array();
			foreach($this->tag_mapping as $tag_map) {

				// Get taxonomy
				$taxonomy = $tag_map['action_post_tag_category_id'];
				if(!isset($taxonomy_tags[$taxonomy])) { $taxonomy_tags[$taxonomy] = array(); }
				$taxonomy_obj = get_taxonomy($taxonomy);
				if (!$taxonomy_obj) { continue; }

				// Get field ID
				$field_id = $tag_map['ws_form_field'];
				if($field_id == '') { continue; }

				// Read submit meta
				$tags = parent::get_submit_value($submit_parse, WS_FORM_FIELD_PREFIX . $field_id, false);
				if($tags !== false) {

					// Turn into array if it isn't already
					if(!is_array($tags)) { $tags = (($tags == '') ? array() : explode(',', $tags)); }

					// Convert tags to integers
					foreach($tags as $index => $tag) {

						if(is_numeric($tag)) { $tags[$index] = absint($tag); }
					}

					$taxonomy_tags[$taxonomy] = array_merge($taxonomy_tags[$taxonomy], $tags);
				}
			}

			// Terms
			foreach($this->terms as $term) {

				// Get term ID
				$term_id = absint($term['action_post_term']);
				if($term_id === 0) { continue; }

				// Get term
				$term = get_term($term_id);

				if(is_wp_error($term)) {

					// Error
					self::error_js($term->get_error_message());

					// Halt
					return 'halt';
				}

				// Get taxonomy
				$taxonomy = $term->taxonomy;
				if(!isset($taxonomy_tags[$taxonomy])) { $taxonomy_tags[$taxonomy] = array(); }

				// Add to array
				$taxonomy_tags[$taxonomy][] = $term_id;
			}

			// Assign terms to post
			foreach($taxonomy_tags as $taxonomy => $tags) {

				if(count($tags) === 0) { continue; }

				$tags = array_unique($tags);

				// Set object terms
				$term_taxonomy_ids = wp_set_object_terms($post_id, $tags, $taxonomy);

				if(is_wp_error($term_taxonomy_ids)) {

					// Error
					self::error_js($term_taxonomy_ids->get_error_message());

					// Halt
					return 'halt';
				}
			}

			// Check for a featured image
			$featured_image_field_id = (absint($this->featured_image) == 0) ? false : absint($this->featured_image);

			// Add featured image field ID to attachment map
			$featured_image_field_id_mapped = false;

			if($featured_image_field_id !== false) {

				foreach($this->attachment_mapping as $attachment_map) {

					$field_id = $attachment_map['ws_form_field'];
					if($field_id == $featured_image_field_id) { $featured_image_field_id_mapped = true; }
				}
				if(!$featured_image_field_id_mapped) {

					$this->attachment_mapping[] = array('ws_form_field' => $featured_image_field_id);
					$featured_image_field_id_mapped = true;
				}
			}

			// Process attachment mapping
			$files = array();
			$featured_image_found = false;
			foreach($this->attachment_mapping as $attachment_map) {

				$field_id = $attachment_map['ws_form_field'];

				// Get submit value
				$get_submit_value_repeatable_return = parent::get_submit_value_repeatable($submit_parse, WS_FORM_FIELD_PREFIX . $field_id, array(), true);

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
					if(!is_array($file_objects)) { continue; }

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

						// set_post_thumbnail?
						$set_post_thumbnail = ($featured_image_field_id !== false) ? ($field_id == $featured_image_field_id) : false;
						if($set_post_thumbnail) { $featured_image_found = true; }

						// Get file path
						$file_url = WS_Form_File_Handler_WS_Form::$file_handlers[$handler]->get_url($file_object);

						if($handler === 'attachment') {

							if(!isset($file_object['attachment_id'])) { continue; }
							$attachment_id = absint($file_object['attachment_id']);
							if(!$attachment_id) { continue; }

							// Build file array
							$file_single = array(

								'name'					=>	$file_object['name'],
								'attachment_id'			=>	$attachment_id,
								'set_post_thumbnail'	=>	$set_post_thumbnail,
								'field_id'				=>	$field_id,
								'repeatable'			=>	$repeatable,
								'repeater_index'		=>	$repeater_index,
								'file_url'				=>	$file_url
							);

							// Process file single filter
							$file_single = apply_filters('wsf_action_post_file_single', $file_single);

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
								'set_post_thumbnail'	=>	$set_post_thumbnail,
								'field_id'				=>	$field_id,
								'repeatable'			=>	$repeatable,
								'repeater_index'		=>	$repeater_index,
								'file_url'				=>	$file_url
							);

							// Process file single filter
							$file_single = apply_filters('wsf_action_post_file_single', $file_single);
							if($file_single === 'halt') { return 'halt'; }
						}

						// Add to files array
						$files[] = $file_single;

						// Reset set_post_thumbnail
						if($set_post_thumbnail) { $featured_image_field_id = false; }
					}
				}
			}

			// Removed featured image if it was mapped but no image uploaded
			if(
				$featured_image_field_id_mapped &&
				!$featured_image_found
			) {
				delete_post_meta($post_id, '_thumbnail_id');
			}

			// Process files
			if(count($files) > 0) {

				foreach($files as $file) {

					if(isset($file['attachment_id'])) {

						$attachment_id = $file['attachment_id'];

						// Assign attachment to this post
						wp_update_post(

							array(

								'ID' => $attachment_id, 
								'post_parent' => $post_id
							)
						);

					} else {

						// Need to require these files
						if(!function_exists('media_handle_upload')) {

							require_once(ABSPATH . "wp-admin" . '/includes/image.php');
							require_once(ABSPATH . "wp-admin" . '/includes/file.php');
							require_once(ABSPATH . "wp-admin" . '/includes/media.php');
						}

						$attachment_id = media_handle_sideload($file, $post_id);

						// Error management
						if(is_wp_error($attachment_id)) {

							self::wp_error_process($attachment_id);
							return 'halt';
						}
					}

					// set_post_thumbnail
					if($file['set_post_thumbnail']) {

						set_post_thumbnail($post_id, $attachment_id);
					}

					// Do wsf_action_post_file action
					do_action('wsf_action_post_file', $file, $attachment_id);
				}

				// Do wsf_action_post_attachments action
				do_action('wsf_action_post_attachments', $this->list_id);
			}

			// Do wsf_action_post_post_meta action
			do_action('wsf_action_post_post_meta', $form, $submit_parse, $config, $post_id, $this->list_id, $taxonomy_tags);

			// Expose?
			if($this->expose) {

				global $post;
				$post = get_post($post_id);
				setup_postdata($post);
				$GLOBALS['ws_form_post_root'] = $post;
			}

			// Save post ID to submission meta data
			$submit->meta[$meta_key_post_id] = $post_id;

			// Only run if not attachments
			if($this->list_id != 'attachment') {

				// Clean post cache
				clean_post_cache($post_id);

				// Get post
				$post = get_post($post_id);

				// Update
				$update = isset($postarr['ID']);
				if($update) {

					// Run update actions
					do_action(sprintf('edit_post_%s', $this->list_id), $post_id, $post);
					do_action('edit_post', $post_id, $post);

					//  Re-read post
					$post_after = get_post($post_id);

					do_action('post_updated', $post_id, $post_after, $post_before);
				}

				// Run save actions
				do_action(sprintf('save_post_%s', $this->list_id), $post_id, $post, $update);
				do_action('save_post', $post_id, $post, $update);
				do_action('wp_insert_post', $post_id, $post, $update);
			}

			// Check for trash
			if(
				isset($postarr['status']) &&
				($postarr['status'] == 'trash')
			) {
				if(!wp_trash_post($post_id)) {

					// Error
					self::error_js(__('Trash failed', 'ws-form-post'));

					// Halt
					return 'halt';
				}
			}

			// Success
			parent::success(sprintf(__('Successfully %s %s (ID: %u)' , 'ws-form-post'), $post_method, $post_type->labels->singular_name, $post_id));

			return true;
		}

		// Retain this key in submit meta
		public function submit_clear_meta_filter_keys($keys) {

			$keys[] = 'wsf_action_post_post_id';

			return $keys;
		}

		// Create hash for post
		public function get_post_hash($post) {

			return wp_hash(sprintf('%u-%s-%s-%s', $post->ID, $post->post_type, $post->post_modified, $post->guid));
		}

		// Check for duplication
		public function duplication_repeatable_return($get_submit_value_repeatable_return, $field_id, $meta_key, $list_id, $deduplication_mapping) {

			global $wpdb;

			foreach($get_submit_value_repeatable_return['value'] as $meta_value) {

				// Deduplication
				if(in_array($field_id, $deduplication_mapping)) {

					$meta_value_dedupe = is_array($meta_value) ? serialize($meta_value) : $meta_value;

					$sql = sprintf('SELECT ID FROM %1$s RIGHT JOIN %2$s ON %1$s.post_id = %2$s.ID WHERE %1$s.meta_key = \'%3$s\' AND %1$s.meta_value = \'%4$s\' AND %2$s.post_type = \'%5$s\' AND NOT(%2$s.post_status = \'trash\') LIMIT 1', $wpdb->postmeta, $wpdb->posts, esc_sql($meta_key), esc_sql($meta_value_dedupe), esc_sql($list_id));
					$post_id = $wpdb->get_var($sql);
					if(!is_null($post_id)) {

						// Get post type object
						$post_type = get_post_type_object($list_id);

						// Error
						self::error_js(sprintf(__('Duplicate %s submitted.' , 'ws-form-post'), strtolower($post_type->labels->singular_name)));

						// Halt
						return false;
					}
				}
			}

			return true;
		}

		// Get post data
		public function get($form, $user) {

			// Check action is configured properly
			if(!self::check_configured()) { return false; }

			// Check list ID is set
			if(!self::check_list_id()) { return false; }

			// Read form populate data - Post ID
			$post_id = WS_Form_Common::get_object_meta_value($form, 'action_post_form_populate_post_id', '');
			if($post_id == '') { $post_id = '#post_id'; }
			$post_id = WS_Form_Common::parse_variables_process($post_id, $form);
			$post_id = absint($post_id);
			if($post_id == 0) { return false; }

			// Check post type of get
			if(get_post_type($post_id) != $this->list_id) { return false; }

			// Get the post
			$post = get_post($post_id);
			if(is_null($post)) { return false; }

			// Check for author restriction
			$author_restrict = WS_Form_Common::get_object_meta_value($form, 'action_post_form_populate_author_restrict', '');
			if($author_restrict) {

				$post_author_id = absint($post->post_author);

				if($post_author_id !== get_current_user_id()) {

					return false;
				}
			}

			// Build return array
			$return_array = array(

				'fields' => array(),
				'section_repeatable' => array(),
				'fields_repeatable' => array(),
				'tags' => array()
			);

			// Get post data
			$post_data = array(

				'post_title'	=>	$post->post_title,
				'post_name'		=>	$post->post_name,
				'post_content'	=>	$post->post_content,
				'post_excerpt'	=>	$post->post_excerpt,
			);

			// Field mapping
			$field_mapping = WS_Form_Common::get_object_meta_value($form, 'form_populate_field_mapping', '');

			if(is_array($field_mapping)) {

				foreach($field_mapping as $field_map) {

					// Get meta key
					$meta_key = $field_map->form_populate_list_fields;

					// Get field ID
					$field_id = $field_map->ws_form_field;

					// Get meta value
					$meta_value = isset($post_data[$meta_key]) ? $post_data[$meta_key] : '';

					$return_array['fields'][$field_id] = $meta_value;
				}
			}

			// Run wsf_action_post_get filter
			$return_array = apply_filters('wsf_action_post_get', $return_array, $form, $post_id, $this->list_id);

			// Meta key mapping
			$meta_mapping = WS_Form_Common::get_object_meta_value($form, 'action_post_form_populate_meta_mapping', '');
			if(is_array($meta_mapping)) {

				foreach($meta_mapping as $meta_map) {

					$meta_key = $meta_map->action_post_meta_key;
					$field_id = $meta_map->ws_form_field;
					$meta_value = get_post_meta($post_id, $meta_key, true);
					$return_array['fields'][$field_id] = $meta_value;
				}
			}

			// Run through each taxonomy
			$taxonomies = get_object_taxonomies($this->list_id, 'objects');
			foreach ($taxonomies as $taxonomy) {

				$terms = wp_get_post_terms($post_id, $taxonomy->name);

				foreach($terms as $term) {

					$return_array['tags'][$term->term_id] = true;
				}
			}

			// Featured image
			$featured_image_field_id = absint(WS_Form_Common::get_object_meta_value($form, 'action_post_form_populate_featured_image', ''));
			if($featured_image_field_id > 0) {

				$post_thumbnail_id = get_post_thumbnail_id($post);

				if($post_thumbnail_id) {

					$return_array['fields'][$featured_image_field_id] = array(WS_Form_File_Handler::get_file_object_from_attachment_id($post_thumbnail_id));
				}
			}

			return $return_array;
		}

		// Get lists
		public function get_lists($fetch = false) {

			// Check action is configured properly
			if(!self::check_configured()) { return false; }

			$lists = array();

			$post_types_exclude = array('attachment');
			$post_types = get_post_types(array('show_in_menu' => true), 'objects', 'or');

			foreach($post_types as $post_type) {

				$post_type_name = $post_type->name;

				if(in_array($post_type_name, $post_types_exclude)) { continue; }

				$post_count_object = wp_count_posts($post_type_name);
				$record_count = (isset($post_count_object->publish)) ? $post_count_object->publish : 0;

				$lists[] = array(

					'id' => 			$post_type_name, 
					'label' => 			$post_type->labels->singular_name, 
					'field_count' => 	false,
					'record_count' => 	$record_count
				);
			}

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

			// Read post type
			$post_type = get_post_type_object($this->list_id);
			if(empty($post_type)) { return array(); }

			// Set label
			$label = $post_type->labels->singular_name;

			// Build list
			$list = array(

				'label' => $label
			);

			return $list;
		}

		// Get list fields
		public function get_list_fields($fetch = false, $process_integrations = true) {

			// Check action is configured properly
			if(!self::check_configured()) { return false; }

			// Load configuration
			self::load_config();

			// Build list fields
			$list_fields = array();

			// Check if Visual Editor can be selected
			global $wp_version;
			$post_content_meta = (version_compare($wp_version, '4.8', '>=')) ? array('input_type_textarea' => 'tinymce') : false;

			// Post fields
			$fields = array();
			$sort_index = 1;

			// Title
			if(post_type_supports($this->list_id, 'title')) {

				$fields[] = (object) array('id' => 'post_title', 'name' => __('Title', 'ws-form-post'), 'type' => 'text', 'required' => true, 'meta' => false);
			}

			// Slug
			$fields[] = (object) array('id' => 'post_name', 'name' => __('Slug', 'ws-form-post'), 'type' => 'text', 'required' => false, 'meta' => false, 'help_text' => __('Leave blank to use sanitized post title.', 'ws-form-post'));

			// Editor
			if(post_type_supports($this->list_id, 'editor')) {

				$fields[] = (object) array('id' => 'post_content', 'name' => __('Content', 'ws-form-post'), 'type' => 'textarea', 'required' => false, 'meta' => $post_content_meta);
			}

			// Excerpt
			if(post_type_supports($this->list_id, 'excerpt')) {

				$fields[] = (object) array('id' => 'post_excerpt', 'name' => __('Excerpt', 'ws-form-post'), 'type' => 'textarea', 'required' => false, 'meta' => false);
			}

			// Thumbnail
			if(post_type_supports($this->list_id, 'thumbnail')) {

				$fields[] = (object) array('id' => 'featured_image', 'name' => __('Featured Image', 'ws-form-post'), 'type' => 'file', 'required' => false, 'meta' => array('accept' => 'image/jpeg,image/gif,image/png', 'sub_type' => 'dropzonejs', 'file_handler' => 'attachment'), 'no_map' => true);
			}

			// Menu Order
			$fields[] = (object) array('id' => 'menu_order', 'name' => __('Menu Order', 'ws-form-post'), 'type' => 'text', 'required' => false, 'meta' => false, 'no_add' => true);

			// Process fields hook
			$fields = apply_filters('wsf_action_post_fields', $fields, $this->list_id);

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
					'no_map' =>			parent::get_object_value($field, 'no_map'),
					'no_add' =>			parent::get_object_value($field, 'no_add')
				);
			}

			// Build list field hook state
			$list_fields = array(

				'list_fields' => $list_fields,
				'group_index' => 0,
				'section_index' => 1	// Set to 1 so it follows the built in fields
			);

			// Process list fields hook
			if($process_integrations) {

				$list_fields = apply_filters('wsf_action_post_list_fields', $list_fields, $this->list_id);
			}

			return $list_fields['list_fields'];
		}

		// Get list fields meta data (Returns group and section data such as label and whether or not a section is repeatable)
		public function get_list_fields_meta_data() {

			$list_fields_meta_data = array(

				'group_meta_data' => array(),
				'section_meta_data' => array(),
				'group_index' => 0,
				'section_index' => 1	// Set to 1 so it follows the built in fields
			);

			// Process wsf_action_post_list_fields_meta_data hook
			return apply_filters('wsf_action_post_list_fields_meta_data', $list_fields_meta_data, $this->list_id);
		}

		// Get form fields
		public function get_fields() {

			$form_fields = array(

				'submit' => array(

					'type'			=>	'submit',
					'label'			=>	__('Submit', 'ws-form-post')
				)
			);

			return $form_fields;
		}

		// Get form actions
		public function get_actions($form_field_id_lookup_all, $form_field_type_lookup) {

			// Get post type name
			$post_type = get_post_type_object($this->list_id);
			if(empty($post_type)) { return array(); }

			// Set label
			$label = $post_type->labels->singular_name;

			$form_actions = array(

				'post' => array(

					'meta'	=> array(

						'action_post_list_id'			=>	$this->list_id,
						'action_post_field_mapping'		=>	'field_mapping',
						'action_post_tag_mapping'		=>	'tag_mapping'
					)
				),

				'message' => array(

					'meta'	=> array(

						'action_message_message'	=> sprintf(__('Successfully added %s.', 'ws-form-post'), strtolower($label))
					)
				)
			);

			if(post_type_supports($this->list_id, 'thumbnail')) {

				$form_actions['post']['meta']['action_post_featured_image'] = '#featured_image';
			}

			// Process wsf_action_post_form_actions filter
			$form_actions = apply_filters('wsf_action_post_form_actions', $form_actions, $form_field_id_lookup_all, $form_field_type_lookup, $this->list_id);

			// Look for file or image fields that we can map as attachments
			$form_actions['post']['meta']['action_post_attachment_mapping'] = array();

			foreach($form_field_id_lookup_all as $id => $field_id) {

				if(!isset($form_field_type_lookup[$field_id])) { continue; }

				$field_type = $form_field_type_lookup[$field_id];

				switch($field_type) {

					case 'file' :
					case 'signature' :

						if(
							($id != 'featured_image') &&
							($id != 'product_gallery_field_id')
						) {

							$form_actions['post']['meta']['action_post_attachment_mapping'][] = array(

								'ws_form_field' => $field_id
							);
						}

						break;
				}
			}

			return $form_actions;
		}

		// Get form meta
		public function get_meta($form_field_id_lookup_all) {

			// Process wsf_action_post_form_meta filter
			$form_meta = apply_filters('wsf_action_post_form_meta', array(), $form_field_id_lookup_all, $this->list_id);

			if(isset($form_field_id_lookup_all['featured_image'])) {

				$form_meta['action_post_form_populate_featured_image'] = $form_field_id_lookup_all['featured_image'];
			}

			return $form_meta;
		}

		// Get tag categories
		public function get_tag_categories($fetch = false) {

			// Check action is configured properly
			if(!self::check_configured()) { return false; }

			// Check list ID is set
			if(!self::check_list_id()) { return false; }

			$tag_categories = WS_Form_Common::option_get('action_post_tag_categories_' . $this->list_id);

			if($fetch || ($tag_categories === false)) {

				$tag_categories = array();

				// Taxonomy
				$taxonomies = get_object_taxonomies($this->list_id, 'objects');

				$sort_index = 1; 
				foreach($taxonomies as $taxonomy) {

					switch($taxonomy->name) {

						case 'product_type' :

							$type = 'select';
							break;

						default :

						$type = 'checkbox';
					}

					$tag_categories[] = array(

						'id'			=> $taxonomy->name,
						'label'			=> $taxonomy->labels->singular_name, 
						'sort_index'	=> $sort_index,
						'type'			=> $type,
						'data_source'	=> array(

							'id'	=> 'term',
							'meta'	=>	array(

								'data_source_term_filter_taxonomies' => array(

									array('data_source_term_taxonomies' => $taxonomy->name)
								),

								'data_source_term_groups' => ''
							)
						)
					);

					$sort_index++;
				}

				// Store to options
				WS_Form_Common::option_set('action_post_tag_categories_' . $this->list_id, $tag_categories);
			}

			return $tag_categories;
		}

		// Get tags
		public function get_tags($tag_category_id = false, $fetch = false) {

			// Check action is configured properly
			if(!self::check_configured()) { return false; }

			// Check list ID is set
			if(!self::check_list_id()) { return false; }

			// Check tag category ID is set
			if($tag_category_id === false) { self::error(__('Tag category ID is not set', 'ws-form-post')); }

			$tags = WS_Form_Common::option_get('action_post_tag_categories_' . $this->list_id . '_tags_' . $tag_category_id);

			if($fetch || ($tags === false)) {

				$tags = array();

				// Check if taxonomy is hierachical
				$tag_category_hierachical = is_taxonomy_hierarchical($tag_category_id);

				// Get terms
				$api_tags = get_terms(array('taxonomy' => $tag_category_id, 'hide_empty' => false));

				$sort_index = 1;

				foreach($api_tags as $tag) {

					$tags[] = array(

						'id' => 			$tag->term_id,
//							'id' => 			($tag_category_hierachical ? $tag->term_id : $tag->name),
						'label' => 			$tag->name,
						'record_count' =>	$tag->count,
						'sort_index' =>		$sort_index
					);

					$sort_index++;
				}

				// Store to options
				WS_Form_Common::option_set('action_post_tag_categories_' . $this->list_id . '_tags_' . $tag_category_id, $tags);
			}

			return $tags;
		}

		// Perform form field ID lookup
		public function form_field_id_lookup($input, $form_field_id_lookup) {

			return (isset($form_field_id_lookup[$input])) ? $form_field_id_lookup[$input] : $input;
		}

		// Get settings
		public function get_action_settings() {

			$settings = array(

				'meta_keys'		=> array(

					'action_post_list_id',
					'action_post_post_id',
					'action_post_status',
					'action_post_author',
					'action_post_author_restrict',
					'action_post_page_template',
					'action_post_field_mapping',
					'action_post_clear_hidden_meta_values',
					'action_post_meta_mapping',
					'action_post_meta_mapping_custom',
					'action_post_attachment_mapping',
					'action_post_gallery_mapping',
					'action_post_featured_image',
					'action_post_deduplication_mapping',
					'action_post_tag_mapping',
					'action_post_terms',
					'action_post_post_parent',
					'action_post_comment_status',
					'action_post_ping_status',
					'action_post_date',
					'action_post_password',
					'action_post_update_previous_post',
					'action_post_expose'
				)
			);

			// Process wsf_action_post_action_settings filter
			$settings = apply_filters('wsf_action_post_action_settings', $settings);

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
			$settings = apply_filters('wsf_action_post_settings', $settings);

			return $settings;
		}

		// Check action is configured properly
		public function check_configured() {

			if(!$this->configured) { self::error(__('Action not configured', 'ws-form-post') . ' (' . $this->label . ''); }

			return $this->configured;
		}

		// Check list ID is set
		public function check_list_id() {

			if($this->list_id === false) { self::error(__('Post type is not set', 'ws-form-post')); }

			return ($this->list_id !== false);
		}

		// Meta keys for this action
		public function config_meta_keys($meta_keys = array(), $form_id = 0) {

			// Build config_meta_keys
			$config_meta_keys = array(

				// Post Type
				'action_post_list_id'	=> array(

					'label'							=>	__('Post Type', 'ws-form-post'),
					'type'							=>	'select',
					'help'							=>	__('Which post type do you want to add or update?', 'ws-form-post'),
					'options'						=>	'action_api_populate',
					'options_blank'					=>	__('Select...', 'ws-form-post'),
					'options_action_id_meta_key'	=>	'action_id',
					'options_action_api_populate'	=>	'lists',
					'default'						=>	'post'
				),

				// Post ID
				'action_post_post_id' => array(

					'label'			=>	__('Post ID (For Updates)', 'ws-form-post'),
					'type'			=>	'text',
					'default'		=>	'',
					'select_list'	=>	true,
					'help'			=>	__('If blank, a new post will be added. To update an existing post, enter a post ID or variable. <a href="https://wsform.com/knowledgebase/setting-which-post-id-to-update-in-the-post-management-action/" target="_blank">Learn more</a>', 'ws-form-post'),
					'condition'					=>	array(

						array(

							'logic'			=>	'!=',
							'meta_key'		=>	'action_post_list_id',
							'meta_value'	=>	''
						)
					)
				),

				// Clear hidden meta values
				'action_post_clear_hidden_meta_values'	=> array(

					'label'						=>	__('Clear Hidden Fields', 'ws-form-post'),
					'type'						=>	'checkbox',
					'help'						=>	__('Enabling this will clear fields that were hidden when the form was submitted.', 'ws-form-post'),
					'default'					=>	'on',
					'condition'					=>	array(

						array(

							'logic'			=>	'!=',
							'meta_key'		=>	'action_post_list_id',
							'meta_value'	=>	''
						)
					)
				),

				// Field mapping
				'action_post_field_mapping'	=> array(

					'label'						=>	__('Field Mapping', 'ws-form-post'),
					'type'						=>	'repeater',
					'help'						=>	__('Map WS Form fields to post fields.', 'ws-form-post'),
					'meta_keys'					=>	array(

						'ws_form_field',
						'action_post_list_fields'
					),
					'meta_keys_unique'			=>	array(

						'action_post_list_fields'
					),
					'auto_map'					=>	true,
					'condition'					=>	array(

						array(

							'logic'			=>	'!=',
							'meta_key'		=>	'action_post_list_id',
							'meta_value'	=>	''
						)
					)
				),

				// Field meta mapping
				'action_post_meta_mapping'	=> array(

					'label'						=>	__('Field Meta Mapping', 'ws-form-post'),
					'type'						=>	'repeater',
					'help'						=>	__('Map WS Form fields to post meta keys.', 'ws-form-post'),
					'meta_keys'					=>	array(

						'ws_form_field',
						'action_post_meta_key'
					),
					'condition'					=>	array(

						array(

							'logic'			=>	'!=',
							'meta_key'		=>	'action_post_list_id',
							'meta_value'	=>	''
						)
					)
				),

				// Custom meta mapping
				'action_post_meta_mapping_custom'	=> array(

					'label'						=>	__('Custom Meta Mapping', 'ws-form-post'),
					'type'						=>	'repeater',
					'help'						=>	__('Map custom values to meta keys.', 'ws-form-post'),
					'meta_keys'					=>	array(

						'action_post_meta_key',
						'action_post_meta_value'
					),
					'condition'					=>	array(

						array(

							'logic'			=>	'!=',
							'meta_key'		=>	'action_post_list_id',
							'meta_value'	=>	''
						)
					)
				),

				// Meta key
				'action_post_meta_key'	=> array(

					'label'						=>	__('Meta Key', 'ws-form-post'),
					'type'						=>	'text'
				),

				// Meta value
				'action_post_meta_value'	=> array(

					'label'						=>	__('Meta Value', 'ws-form-post'),
					'type'						=>	'text'
				),

				// Term mapping
				'action_post_tag_mapping'	=> array(

					'label'						=>	__('Term Mapping', 'ws-form-post'),
					'type'						=>	'repeater',
					'help'						=>	__('Map WS Form fields to post terms.', 'ws-form-post'),
					'meta_keys'					=>	array(

						'ws_form_field',
						'action_post_tag_category_id'
					),
					'condition'					=>	array(

						array(

							'logic'			=>	'!=',
							'meta_key'		=>	'action_post_list_id',
							'meta_value'	=>	''
						)
					)
				),

				// Taxonomy
				'action_post_tag_category_id' => array(

					'label'							=>	__('Taxonomy', 'ws-form-post'),
					'type'							=>	'select',
					'options'						=>	array(),
				),

				// Custom Term Mapping
				'action_post_terms' => array(

					'label'						=>	__('Terms', 'ws-form-post'),
					'type'						=>	'repeater',
					'help'						=>	__('Assign terms to this post.', 'ws-form-post'),
					'meta_keys'					=>	array(

						'action_post_term'
					),
					'condition'					=>	array(

						array(

							'logic'			=>	'!=',
							'meta_key'		=>	'action_post_list_id',
							'meta_value'	=>	''
						)
					)
				),

				// Terms
				'action_post_term' => array(

					'label'						=>	__('Term', 'ws-form-post'),
					'type'						=>	'select',
					'select2'					=>	true,
					'select_ajax_method_search' => 'action_post_term_search',
					'select_ajax_method_cache'  => 'action_post_term_cache',
					'select_ajax_placeholder'   => __('Search terms...', 'ws-form-post')
				),

				// Attachment mapping
				'action_post_attachment_mapping'	=> array(

					'label'						=>	__('Attachment Mapping', 'ws-form-post'),
					'type'						=>	'repeater',
					'help'						=>	__('Map WS Form file and signature fields to post attachments.', 'ws-form-post'),
					'meta_keys'					=>	array(

						'ws_form_field_file'
					),
					'meta_keys_unique'			=>	array(

						'ws_form_field_file'
					),
					'condition'					=>	array(

						array(

							'logic'			=>	'!=',
							'meta_key'		=>	'action_post_list_id',
							'meta_value'	=>	''
						)
					)
				),

				// Gallery mapping
				'action_post_gallery_mapping'	=> array(

					'label'						=>	__('Gallery Mapping', 'ws-form-post'),
					'type'						=>	'repeater',
					'help'						=>	__('Map WS Form file and signature fields to the product gallery.', 'ws-form-post'),
					'meta_keys'					=>	array(

						'ws_form_field_file'
					),
					'meta_keys_unique'			=>	array(

						'ws_form_field_file'
					),
					'condition'					=>	array(

						array(

							'logic'			=>	'==',
							'meta_key'		=>	'action_post_list_id',
							'meta_value'	=>	'product'
						)
					)
				),

				// Featured image
				'action_post_featured_image'	=> array(

					'label'							=>	__('Featured Image', 'ws-form-post'),
					'type'							=>	'select',
					'options'						=>	'fields',
					'options_blank'					=>	__('Select...', 'ws-form-post'),
					'fields_filter_type'			=>	array('file', 'signature'),
					'help'							=>	__('Select which file field to use for the featured image.', 'ws-form-post'),
					'condition'					=>	array(

						array(

							'logic'			=>	'!=',
							'meta_key'		=>	'action_post_list_id',
							'meta_value'	=>	''
						)
					)
				),

				// Deduplication
				'action_post_deduplication_mapping'	=> array(

					'label'						=>	__('Deduplicate by Field', 'ws-form-post'),
					'type'						=>	'repeater',
					'help'						=>	__('Select unique WS Form fields.', 'ws-form-post'),
					'meta_keys'					=>	array(

						'ws_form_field'
					),
					'meta_keys_unique'			=>	array(
						'ws_form_field'
					),
					'condition'					=>	array(

						array(

							'logic'			=>	'!=',
							'meta_key'		=>	'action_post_list_id',
							'meta_value'	=>	''
						)
					)
				),

				// List fields
				'action_post_list_fields'	=> array(

					'label'							=>	__('Post Field', 'ws-form-post'),
					'type'							=>	'select',
					'options'						=>	'action_api_populate',
					'options_blank'					=>	__('Select...', 'ws-form-post'),
					'options_action_id'				=>	'post',
					'options_list_id_meta_key'		=>	'action_post_list_id',
					'options_action_api_populate'	=>	'list_fields'
				),

				// Status
				'action_post_status'	=> array(

					'label'						=>	__('Status', 'ws-form-post'),
					'type'						=>	'select',
					'help'						=>	__('Select the status you want to assign to this post.', 'ws-form-post'),
					'options'					=>	array(),
					'default'					=>	self::DEFAULT_POST_STATUS,
					'condition'					=>	array(

						array(

							'logic'			=>	'!=',
							'meta_key'		=>	'action_post_list_id',
							'meta_value'	=>	''
						)
					)
				),

				// Author
				'action_post_author'	=> array(

					'label'						=>	__('Author', 'ws-form-post'),
					'type'						=>	'select',
					'help'						=>	__('Author of post.', 'ws-form-post'),
					'options'					=>	array(),
					'default'					=>	'',
					'condition'					=>	array(

						array(

							'logic'			=>	'!=',
							'meta_key'		=>	'action_post_list_id',
							'meta_value'	=>	''
						)
					)
				),

				// Author - Restrict
				'action_post_author_restrict'	=> array(

					'label'						=>	__('Restrict Updates to Author', 'ws-form-post'),
					'type'						=>	'checkbox',
					'help'						=>	__('Only allow posts to be updated by the original author.', 'ws-form-post'),
					'default'					=>	'',
					'condition'					=>	array(

						array(

							'logic'			=>	'!=',
							'meta_key'		=>	'action_post_list_id',
							'meta_value'	=>	''
						)
					)
				),

				// Post Parent
				'action_post_post_parent' => array(

					'label'			=>	__('Post Parent ID', 'ws-form-post'),
					'type'			=>	'text',
					'default'		=>	'',
					'select_list'	=>	true,
					'help'			=>	__('Set this to parent post ID this child post belongs to. Leave blank for none.', 'ws-form-post'),
					'condition'					=>	array(

						array(

							'logic'			=>	'!=',
							'meta_key'		=>	'action_post_list_id',
							'meta_value'	=>	''
						)
					)
				),

				// Comment Status
				'action_post_comment_status'	=> array(

					'label'						=>	__('Comment Status', 'ws-form-post'),
					'type'						=>	'select',
					'help'						=>	__('Whether the post can accept comments.', 'ws-form-post'),
					'options'					=>	array(

						array('value' => '', 'text' => 'Default (New = Default comment status, Existing = Inherit)'),
						array('value' => 'closed', 'text' => 'Closed'),
						array('value' => 'open', 'text' => 'Open')
					),
					'default'					=>	get_option('default_comment_status', self::DEFAULT_COMMENT_STATUS),
					'condition'					=>	array(

						array(

							'logic'			=>	'!=',
							'meta_key'		=>	'action_post_list_id',
							'meta_value'	=>	''
						)
					)
				),

				// Ping Status
				'action_post_ping_status'	=> array(

					'label'						=>	__('Ping Status', 'ws-form-post'),
					'type'						=>	'select',
					'help'						=>	__('Whether the post can accept pings.', 'ws-form-post'),
					'options'					=>	array(

						array('value' => '', 'text' => 'Default (New = Default ping status, Existing = Inherit)'),
						array('value' => 'closed', 'text' => 'Closed'),
						array('value' => 'open', 'text' => 'Open')
					),
					'default'					=>	get_option('default_ping_status', self::DEFAULT_PING_STATUS),
					'condition'					=>	array(

						array(

							'logic'			=>	'!=',
							'meta_key'		=>	'action_post_list_id',
							'meta_value'	=>	''
						)
					)
				),

				// Page Template
				'action_post_page_template'	=> array(

					'label'						=>	__('Page Template', 'ws-form-post'),
					'type'						=>	'select',
					'help'						=>	__('Page template to assign page to.', 'ws-form-post'),
					'options'					=>	array(),
					'default'					=>	'default',
					'condition'					=>	array(

						array(

							'logic'			=>	'==',
							'meta_key'		=>	'action_post_list_id',
							'meta_value'	=>	'page'
						)
					)
				),

				// Date
				'action_post_date'	=> array(

					'label'						=>	__('Date / Time', 'ws-form-post'),
					'type'						=>	'date',
					'help'						=>	__('The date / time of the post. Leave blank for current date / time.', 'ws-form-post'),
					'default'					=>	'',
					'placeholder'				=>	'Y-m-d H:i:s',
					'condition'					=>	array(

						array(

							'logic'			=>	'!=',
							'meta_key'		=>	'action_post_list_id',
							'meta_value'	=>	''
						)
					)
				),

				// Password
				'action_post_password'	=> array(

					'label'						=>	__('Password', 'ws-form-post'),
					'type'						=>	'text',
					'help'						=>	__('The password to access the post. Leave blank for none. Note: WordPress does not encrypt post passwords.', 'ws-form-post'),
					'default'					=>	'',
					'condition'					=>	array(

						array(

							'logic'			=>	'!=',
							'meta_key'		=>	'action_post_list_id',
							'meta_value'	=>	''
						),

						array(

							'logic'			=>	'!=',
							'meta_key'		=>	'action_post_status',
							'meta_value'	=>	'private'
						)
					)
				),

				// Update previous post
				'action_post_update_previous_post'	=> array(

					'label'						=>	__('Update Previous Post', 'ws-form-post'),
					'type'						=>	'checkbox',
					'help'						=>	__('If this action is run after a previous post management action, update the post created first.', 'ws-form-post'),
					'default'					=>	'on',
					'condition'					=>	array(

						array(

							'logic'			=>	'!=',
							'meta_key'		=>	'action_post_list_id',
							'meta_value'	=>	''
						)
					)
				),

				// Expose
				'action_post_expose'	=> array(

					'label'						=>	__('Expose Post to Other Actions', 'ws-form-post'),
					'type'						=>	'checkbox',
					'help'						=>	__('If checked the newly created post data will be available in #post variables.', 'ws-form-post'),
					'default'					=>	'on',
					'condition'					=>	array(

						array(

							'logic'			=>	'!=',
							'meta_key'		=>	'action_post_list_id',
							'meta_value'	=>	''
						)
					)
				),

				// Auto Populate

				// Post ID
				'action_post_form_populate_post_id' => array(

					'label'			=>	__('Post ID', 'ws-form-post'),
					'type'			=>	'text',
					'default'		=>	'',
					'placeholder'	=>	'#post_id',
					'help'			=>	__('If blank, WS Form will use the current post ID. You can also manually enter a post ID or use a variable such as #query_var("post_id").'),
					'select_list'	=>	true,
					'condition'		=>	array(

						array(

							'logic'			=>	'==',
							'meta_key'		=>	'form_populate_action_id',
							'meta_value'	=>	'post'
						),

						array(

							'logic'			=>	'==',
							'meta_key'		=>	'form_populate_enabled',
							'meta_value'	=>	'on'
						)
					)
				),

				// Field meta mapping
				'action_post_form_populate_meta_mapping'	=> array(

					'label'						=>	__('Meta Mapping', 'ws-form-post'),
					'type'						=>	'repeater',
					'help'						=>	__('Map post meta key values to WS Form fields.', 'ws-form-post'),
					'meta_keys'					=>	array(

						'action_post_meta_key',
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
				),

				// Form populate - Author - Restrict
				'action_post_form_populate_author_restrict'	=> array(

					'label'						=>	__('Restrict Populate to Author', 'ws-form-post'),
					'type'						=>	'checkbox',
					'help'						=>	__('Only allow population of the form from posts authored by the logged in user.', 'ws-form-post'),
					'default'					=>	'',
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
				),

				// Featured image
				'action_post_form_populate_featured_image'	=> array(

					'label'							=>	__('Featured Image', 'ws-form-post'),
					'type'							=>	'select',
					'options'						=>	'fields',
					'options_blank'					=>	__('Select...', 'ws-form-post'),
					'fields_filter_type'			=>	array('file', 'signature'),
					'help'							=>	__('Select which file field to use for the featured image. Only file fields of type DropzoneJS using the Media Library file handler are compatible with this feature.', 'ws-form-post'),
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
				)
			);

			// Populate options if in admin
			if(is_admin()) {

				// Add WordPress post stati
				foreach(get_post_stati(array('internal' => false), 'objects') as $post_status_name => $post_status) {

					$config_meta_keys['action_post_status']['options'][] = array(

						'value' => esc_attr($post_status_name),
						'text' => sprintf(

							'%s (%s)', 
							esc_html($post_status->label),
							esc_html($post_status_name)
						)
					);
				}

				// Add trashed status
				$config_meta_keys['action_post_status']['options'][] = array('value' => 'trash', 'text' => __('Trash (trash)', 'ws-form'));

				// Sort stati
				$status_text = array_column($config_meta_keys['action_post_status']['options'], 'text');
				array_multisort($status_text, SORT_ASC, $config_meta_keys['action_post_status']['options']);

				// Add default post status
				array_unshift($config_meta_keys['action_post_status']['options'], array('value' => '', 'text' => __('Default (New = Draft, Existing = Inherit)', 'ws-form-post')));

				// Add authors
				global $wp_version;
				if(WS_Form_Common::version_compare($wp_version, '5.9') >= 0) {

					$get_users_args = array('capability__in' => array('publish_pages', 'publish_posts'));

				} else {

					$get_users_args = array('who' => 'authors');
				}
				$authors = get_users($get_users_args);

				$config_meta_keys['action_post_author']['options'][] = array('value' => '', 'text' => 'Current User');
				foreach($authors as $author) {
					$config_meta_keys['action_post_author']['options'][] = array('value' => $author->ID, 'text' => $author->display_name);
				}

				// Add page templates
				$templates = wp_get_theme()->get_page_templates();
				$config_meta_keys['action_post_page_template']['options'][] = array('value' => 'default', 'text' => __('Default Template'));
				foreach($templates as $template_file => $template_name) {
					$config_meta_keys['action_post_page_template']['options'][] = array('value' => $template_file, 'text' => $template_name);
				}

				// Add taxonomies
				$taxonomies = get_taxonomies(array(), 'objects'); 
				foreach ($taxonomies as $taxonomy) {
					$config_meta_keys['action_post_tag_category_id']['options'][] = array('value' => $taxonomy->name, 'text' => sprintf('%s (%s)', $taxonomy->labels->singular_name, $taxonomy->name));
				}

				// Sort taxonomies
				$status_text = array_column($config_meta_keys['action_post_tag_category_id']['options'], 'text');
				array_multisort($status_text, SORT_ASC, $config_meta_keys['action_post_tag_category_id']['options']);
			}

			// Apply filter
			$config_meta_keys = apply_filters('wsf_action_post_config_meta_keys', $config_meta_keys);

			// Merge
			$meta_keys = array_merge($meta_keys, $config_meta_keys);

			return $meta_keys;
		}

		// Plug-in options for this action
		public function config_options($options) {

			$options['action_post'] = array(

				'label'		=>	$this->label,
				'fields'	=>	array(

					'action_post_license_version'	=>	array(

						'label'		=>	__('Add-on Version', 'ws-form-post'),
						'type'		=>	'static'
					),

					'action_post_license_key'	=>	array(

						'label'		=>	__('Add-on License Key', 'ws-form-post'),
						'type'		=>	'text',
						'help'		=>	__('Enter your Post Management add-on for WS Form PRO license key here.', 'ws-form-post'),
						'button'	=>	'license_action_post',
						'action'	=>	'post'
					),

					'action_post_license_status'	=>	array(

						'label'		=>	__('Add-on License Status', 'ws-form-post'),
						'type'		=>	'static'
					),
				)
			);

			return $options;
		}

		public function config_settings_form_admin($config_settings_form_admin) {

			if(!isset($config_settings_form_admin['sidebars']['form']['meta']['fieldsets']['action'])) { return $config_settings_form_admin; }

			$meta_keys = $config_settings_form_admin['sidebars']['form']['meta']['fieldsets']['action']['fieldsets'][0]['meta_keys'];

			// Add post ID
			self::meta_key_inject($meta_keys, 'action_post_form_populate_post_id', 'form_populate_field_mapping');

			// Add author restrict
			self::meta_key_inject($meta_keys, 'action_post_form_populate_author_restrict', 'form_populate_field_mapping');

			// Process wsf_action_post_config_settings_form_admin filter
			$meta_keys = apply_filters('wsf_action_post_config_settings_form_admin', $meta_keys);

			// Add meta key mapping
			self::meta_key_inject($meta_keys, 'action_post_form_populate_meta_mapping', 'form_populate_tag_mapping');

			// Add featured image mapping
			$meta_keys[] = 'action_post_form_populate_featured_image';

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
		public function wp_error_process($post) {

			$error_messages = $post->get_error_messages();
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

			if($this->list_id === false) { $this->list_id = parent::get_config($config, 'action_post_list_id'); }
			$this->post_id = parent::get_config($config, 'action_post_post_id', '');
			$this->clear_hidden_meta_values = 	parent::get_config($config, 'action_post_clear_hidden_meta_values', 'on');
			$this->status = parent::get_config($config, 'action_post_status', self::DEFAULT_POST_STATUS);
			$this->author = parent::get_config($config, 'action_post_author', '');
			$this->author_restrict = parent::get_config($config, 'action_post_author_restrict', '');
			$this->post_parent = parent::get_config($config, 'action_post_post_parent', '');
			$this->comment_status = parent::get_config($config, 'action_post_comment_status', get_option('default_comment_status', self::DEFAULT_COMMENT_STATUS));
			$this->ping_status = parent::get_config($config, 'action_post_ping_status', get_option('default_ping_status', self::DEFAULT_PING_STATUS));
			$this->date = parent::get_config($config, 'action_post_date', '');
			$this->password = parent::get_config($config, 'action_post_password', '');
			$this->expose = parent::get_config($config, 'action_post_expose', 'on');
			$this->update_previous_post = parent::get_config($config, 'action_post_update_previous_post', 'on');
			$this->page_template = parent::get_config($config, 'action_post_page_template', '');
			$this->featured_image = parent::get_config($config, 'action_post_featured_image');

			// Field mapping
			$this->field_mapping = parent::get_config($config, 'action_post_field_mapping', array());
			if(!is_array($this->field_mapping)) { $this->field_mapping = array(); }

			// Field meta mapping
			$this->meta_mapping = parent::get_config($config, 'action_post_meta_mapping', array());
			if(!is_array($this->meta_mapping)) { $this->meta_mapping = array(); }

			// Custom meta mapping
			$this->meta_mapping_custom = parent::get_config($config, 'action_post_meta_mapping_custom', array());
			if(!is_array($this->meta_mapping_custom)) { $this->meta_mapping_custom = array(); }

			// Tag mapping
			$this->tag_mapping = parent::get_config($config, 'action_post_tag_mapping', array());
			if(!is_array($this->tag_mapping)) { $this->tag_mapping = array(); }

			// Terms
			$this->terms = parent::get_config($config, 'action_post_terms', array());
			if(!is_array($this->terms)) { $this->terms = array(); }

			// Deduplication mapping
			$this->deduplication_mapping = parent::get_config($config, 'action_post_deduplication_mapping', array());
			if(!is_array($this->deduplication_mapping)) { $this->deduplication_mapping = array(); }

			// Attachment mapping
			$this->attachment_mapping = parent::get_config($config, 'action_post_attachment_mapping', array());
			if(!is_array($this->attachment_mapping)) { $this->attachment_mapping = array(); }
		}

		// Term search
		public function term_search($parameters) {

			global $wpdb;

			$term = WS_Form_Common::get_query_var_nonce('term', '', $parameters);
			$type = WS_Form_Common::get_query_var_nonce('_type', '', $parameters);

			$taxonomy_lookups = self::get_taxonomy_lookup();

			$results = array();

			$terms = $wpdb->get_results(sprintf('SELECT DISTINCT t.term_id, t.name, tt.taxonomy FROM %1$sterms AS t LEFT JOIN %1$sterm_taxonomy AS tt ON t.term_id = tt.term_id WHERE ((t.name LIKE \'%2$s%%\') OR (t.slug LIKE \'%2$s%%\')) ORDER BY t.name ASC', esc_sql($wpdb->prefix), esc_sql($term)));
			foreach ($terms as $term) {

				if(!isset($taxonomy_lookups[$term->taxonomy])) { continue; }
				$taxonomy_label = $taxonomy_lookups[$term->taxonomy];

				$results[] = array('id' => $term->term_id, 'text' => sprintf('%s: %s (ID: %u)', $taxonomy_label, $term->name, $term->term_id));
			}

			return array('results' => $results);
		}

		// Term cache
		public function term_cache($parameters) {

			$return_array = array();

			$taxonomy_lookups = self::get_taxonomy_lookup();

			$term_ids = WS_Form_Common::get_query_var_nonce('ids', '', $parameters);
			foreach ($term_ids as $term_id) {

				$term_id = absint($term_id);

				$term = get_term($term_id);
				if(is_wp_error($term)) {

					continue;
				}

				$taxonomy_label = $taxonomy_lookups[$term->taxonomy];

				$return_array[$term_id] = sprintf('%s: %s (ID: %u)', $taxonomy_label, $term->name, $term->term_id);
			}

			return $return_array;
		}

		// Taxonomy lookups
		public function get_taxonomy_lookup() {

			// Get taxonomies
			$taxonomy_lookup = array();
			$taxonomies = get_taxonomies(array(), 'object');
			foreach($taxonomies as $id => $taxonomy) {

				$taxonomy_lookup[$id] = $taxonomy->labels->singular_name;
			}

			return $taxonomy_lookup;
		}

		// Load config at plugin level
		public function load_config_plugin() {

			$this->configured = true;
			return $this->configured;
		}

		// Build REST API endpoints
		public function rest_api_init() {

			// API routes - get_* (Use cache)
			register_rest_route(WS_FORM_RESTFUL_NAMESPACE, '/action/post/lists/', array('methods' => 'GET', 'callback' => array($this, 'api_get_lists'), 'permission_callback' => function () { return WS_Form_Common::can_user('create_form'); }));
			register_rest_route(WS_FORM_RESTFUL_NAMESPACE, '/action/post/list/(?P<list_id>[a-zA-Z0-9_-]+)/', array('methods' => 'GET', 'callback' => array($this, 'api_get_list'), 'permission_callback' => function () { return WS_Form_Common::can_user('create_form'); }));
			register_rest_route(WS_FORM_RESTFUL_NAMESPACE, '/action/post/list/(?P<list_id>[a-zA-Z0-9_-]+)/fields/', array('methods' => 'GET', 'callback' => array($this, 'api_get_list_fields'), 'permission_callback' => function () { return WS_Form_Common::can_user('create_form'); }));

			// API routes - fetch_* (Pull from API and update cache)
			register_rest_route(WS_FORM_RESTFUL_NAMESPACE, '/action/post/lists/fetch/', array('methods' => 'GET', 'callback' => array($this, 'api_fetch_lists'), 'permission_callback' => function () { return WS_Form_Common::can_user('create_form'); }));
			register_rest_route(WS_FORM_RESTFUL_NAMESPACE, '/action/post/list/(?P<list_id>[a-zA-Z0-9_-]+)/fetch/', array('methods' => 'GET', 'callback' => array($this, 'api_fetch_list'), 'permission_callback' => function () { return WS_Form_Common::can_user('create_form'); }));
			register_rest_route(WS_FORM_RESTFUL_NAMESPACE, '/action/post/list/(?P<list_id>[a-zA-Z0-9_-]+)/fields/fetch/', array('methods' => 'GET', 'callback' => array($this, 'api_fetch_list_fields'), 'permission_callback' => function () { return WS_Form_Common::can_user('create_form'); }));

			// Select2 - Term
			register_rest_route(WS_FORM_RESTFUL_NAMESPACE, '/select2/action_post_term_search/', array( 'methods' => 'GET', 'callback' => array($this, 'api_term_search'), 'permission_callback' => function () { return WS_Form_Common::can_user('edit_form'); }));

			register_rest_route(WS_FORM_RESTFUL_NAMESPACE, '/select2/action_post_term_cache/', array( 'methods' => 'POST', 'callback' => array($this, 'api_term_cache'), 'permission_callback' => function () { return WS_Form_Common::can_user('edit_form'); }));
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
			$list_fields = self::get_list_fields(false, false);

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
			$list_fields = self::get_list_fields(true, false);

			// Process response
			self::api_response($list_fields);
		}

		// API endpoint - Search terms
		public function api_term_search( $parameters ) {

			return self::term_search( $parameters );
		}

		// API endpoint - Cache terms
		public function api_term_cache( $parameters ) {

			return self::term_cache( $parameters );
		}

		// SVG Logo - Color (Used for the 'Add Form' page)
		public function get_svg_logo_color($list_id = false) {

			// Template SVG: 140 x 180
			$svg_logo = '<g transform="translate(45.000000, 62.000000)"><path fill="#0077a1" d="M25 1.5a23.3 23.3 0 0 1 16.62 6.89 23.4 23.4 0 0 1 5.04 7.47 23.45 23.45 0 0 1-2.17 22.29 23.91 23.91 0 0 1-6.35 6.35 23.45 23.45 0 0 1-26.28 0 23.91 23.91 0 0 1-6.35-6.35A23.56 23.56 0 0 1 15.86 3.34 23.4 23.4 0 0 1 25 1.5M25 0a25 25 0 1 0 0 50 25 25 0 0 0 0-50z"/><path fill="#0077a1" d="M4.17 25c0 8.25 4.79 15.37 11.74 18.75L5.97 16.52A20.69 20.69 0 0 0 4.17 25zm34.89-1.05a11 11 0 0 0-1.72-5.75c-1.06-1.72-2.05-3.17-2.05-4.89 0-1.91 1.45-3.7 3.5-3.7l.27.02a20.8 20.8 0 0 0-31.47 3.93l1.34.03c2.18 0 5.55-.26 5.55-.26 1.12-.07 1.26 1.58.13 1.72 0 0-1.13.13-2.38.2l7.59 22.57 4.56-13.67-3.25-8.89c-1.12-.07-2.19-.2-2.19-.2-1.12-.07-.99-1.78.13-1.72 0 0 3.44.26 5.49.26 2.18 0 5.55-.26 5.55-.26 1.12-.07 1.26 1.58.13 1.72 0 0-1.13.13-2.38.2l7.53 22.39 2.15-6.81c.96-3 1.52-5.11 1.52-6.89zm-13.69 2.87l-6.25 18.16a20.81 20.81 0 0 0 12.81-.33 1.32 1.32 0 0 1-.15-.29l-6.41-17.54zM43.28 15c.09.66.14 1.38.14 2.14 0 2.11-.4 4.49-1.58 7.46L35.48 43a20.8 20.8 0 0 0 7.8-28z"/></g>';

			// Process SVG custom field logos hook
			$svg_custom_field_logos = apply_filters('wsf_action_post_svg_custom_field_logos', array(), $list_id);

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
