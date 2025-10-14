<?php

class WS_Form_Block_Form_Add {

	public function __construct() {

		add_action('init', array($this, 'init'));
	}

	public function init() {

		// Register scripts - WordPress will auto-enqueue these when the block editor loads
		// because they're specified in block.json's "editorScript" array
		wp_register_script(

			'ws-form-block-editor',
			plugins_url('block.js', __FILE__),
			array('wp-blocks', 'wp-element', 'wp-components', 'wp-block-editor', 'wp-api-fetch', 'wp-server-side-render'),
			filemtime(__DIR__ . '/block.js'),
			true
		);
		
		wp_register_script(

			'ws-form-block-editor-iframe',
			plugins_url('block-editor-iframe.js', __FILE__),
			array('wp-dom-ready'),
			filemtime(__DIR__ . '/block-editor-iframe.js'),
			true
		);
		
		// Localize scripts with data they need
		wp_localize_script('ws-form-block-editor', 'ws_form_block_form_add', array(

			// Block settings
			'category' => WS_FORM_NAME,
			'keywords' => array(WS_FORM_NAME_PRESENTABLE, __('form', 'ws-form')),
			'label' => WS_FORM_NAME_PRESENTABLE,
			'name' => 'wsf-block/form-add',
			'nonce' => wp_create_nonce('wp_rest'),

			// Translations
			'text_add_form_button' => __('Add New', 'ws-form'),

			/* translators: %s: Presentable name (e.g. WS Form PRO) */
			'text_description' => sprintf(__('Add a form to your web page using %s.', 'ws-form'), WS_FORM_NAME_PRESENTABLE),
			'text_edit_form_button' => __('Edit', 'ws-form'),
			'text_form_not_selected' => __('Select a form in the sidebar.', 'ws-form'),
			'text_help_form_element_id' => __('Optional custom element ID for the form', 'ws-form'),
			'text_label_form_id' => __('Select Form', 'ws-form'),
			'text_label_form_element_id' => __('Form Element ID (optional)', 'ws-form'),
			'text_panel_title' => __('WS Form Settings' ,'ws-form'),
			'text_option_placeholder' => __('Select...', 'ws-form'),
			'text_preview_alt' => __('WS Form Preview', 'ws-form'),
			'text_loading' => __('Loading...', 'ws-form'),
			'text_styler_button' => __('Style', 'ws-form'),
			'text_styler_section' => __('Form Styling', 'ws-form'),

			// URLs
			'url_add' => esc_url(WS_Form_Common::get_admin_url('ws-form-add')),
			'url_admin' => esc_url(admin_url('admin.php')),
			'url_block' => plugins_url('', __FILE__),
			'url_site' => esc_url(home_url('/')),
		));

		wp_localize_script(

			'ws-form-block-editor-iframe',
			'ws_form_block_form_add_iframe',
			array(

				'is_block_editor' => true
			)
		);

		// Register block - WordPress will automatically enqueue the editorScript files
		// specified in block.json when the block editor loads
		register_block_type(

			__DIR__,
			array(

				'render_callback' => array($this, 'render')
			)
		);
	}

	public function render($attributes) {

		// Do not render if form ID is not set
		if(!isset($attributes['form_id'])) { return ''; }

		// Get form ID
		$form_id = absint($attributes['form_id']);

		// Do not render if form ID = 0
		if($form_id == 0) { return ''; }

		// Get form element ID
		$form_element_id = isset($attributes['form_element_id']) ? $attributes['form_element_id'] : '';
		if($form_element_id != '') { $form_element_id = sprintf(' element_id="%s"', esc_attr($form_element_id)); }

		// Get className
		$form_class_name = isset($attributes['className']) ? $attributes['className'] : '';
		if($form_class_name != '') { $form_class_name = sprintf(' class="%s"', esc_attr($form_class_name)); }

		return do_shortcode(sprintf('[%s id="%u"%s%s]', WS_FORM_SHORTCODE, $form_id, $form_element_id, $form_class_name));
	}
}

new WS_Form_Block_Form_Add();