<?php

	// WPML
	// https://wpml.org/documentation/support/string-package-translation/

	class WS_Form_WPML {

		public function __construct() {

			// Register filters
			add_filter('wsf_translate_plugins', array($this, 'plugins', 10, 1));

			// Register action hooks
			add_action('wsf_translate', array($this, 'translate'), 10, 4);
			add_action('wsf_translate_start', array($this, 'start'), 10, 2);
			add_action('wsf_translate_register', array($this, 'register'), 10, 6);
			add_action('wsf_translate_finish', array($this, 'finish'), 10, 2);
			add_action('wsf_translate_delete_unused', array($this, 'delete_unused'), 10, 2);
			add_action('wsf_translate_unregister_all', array($this, 'unregister_all'), 10, 1);
		}

		public function plugins($plugins) {

			$plugins[] = array(

				'id' => 'wpml',
				'label' => __('WPML', 'ws-form')
			);

			return $plugins;
		}

		public function translate($string_value, $string_name, $form_id, $form_label) {

			// Translate string
			// https://wpml.org/wpml-hook/wpml_translate_string/
			return apply_filters(

				'wpml_translate_string',
				$string_value,										// String value
				$string_name,										// String name
				self::get_package_object($form_id, $form_label)		// Package
			);
		}

		public function start($form_id, $form_label) {

			// Start string package registration
			// https://wpml.org/wpml-hook/wpml_start_string_package_registration/
			do_action(

				'wpml_start_string_package_registration',
				self::get_package_object($form_id, $form_label)		// Package
			);
		}

		public function register($string_value, $string_name, $label, $type, $form_id, $form_label) {

			// Register string
			// https://wpml.org/wpml-hook/wpml_register_string/
			do_action(

				'wpml_register_string',
				$string_value,										// String value
				$string_name,										// String name
				self::get_package_object($form_id, $form_label),	// Package
				$label,												// String title
				self::ws_form_type_convert($type)					// String type: LINE, AREA or VISUAL
			);
		}

		public function finish($form_id, $form_label) {

			// Delete unused package strings
			// https://wpml.org/wpml-hook/wpml_delete_unused_package_strings/
			do_action(

				'wpml_delete_unused_package_strings',
				self::get_package_object($form_id, $form_label)		// Package
			);
		}

		public function unregister_all($form_id) {

			// Delete package
			// https://wpml.org/wpml-hook/wpml_delete_package/
			do_action(

				'wpml_delete_package',
				self::get_package_name($form_id),					// Package name
				self::get_package_kind()							// Package kind
			);
		}

		public function ws_form_type_convert($type) {

			// Convert type
			switch($type) {

				case 'text' : return 'LINE';
				case 'text_editor' : return 'AREA';
				default : return 'LINE';
			}
		}

		// Get package - One package per form
		public function get_package_object($form_id, $form_label = '') {

			// Set package object
			return (object) array(

				'kind' => self::get_package_kind(),
				'kind_slug' => self::get_package_kind_slug(),
				'name' => self::get_package_name($form_id),
				'title' => self::get_package_title($form_label),
				'edit_link' => esc_url(WS_Form_Common::get_admin_url('ws-form-edit', $form_id)),
				'view_link' => esc_url(WS_Form_Common::get_preview_url($form_id))
			);
		}

		// Get package kind
		public function get_package_kind() {

			return __('WS Form', 'ws-form');
		}

		// Get package kind slug
		public function get_package_kind_slug() {

			return 'wsf-form';
		}

		// Get package name
		public function get_package_name($form_id) {

			return $form_id;
		}

		// Get package title
		public function get_package_title($form_label = '') {

			return $form_label . time();
		}
	}

	new WS_Form_WPML();
