<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class X_Ws_Forms extends \Bricks\Element {

  // Element properties
 	public $category     = 'extras';
	public $name         = 'xwsforms';
	public $icon         = 'ti-layout-media-overlay-alt-2';
	public $css_selector = '';

	public function __construct($element = null) {

		if( bricks_is_builder() ) {
			$this->scripts = ['wsf_form_init'];
		}

		parent::__construct($element);
	}

  
	public function get_label() {
		return esc_html__( 'WS Forms', 'extras' );
	}

	public function set_control_groups() {

		$this->control_groups['general_group'] = [
			'title' => esc_html__( 'Form wide colors', 'extras' ),
			'tab'   => 'content',
		];

    	$this->control_groups['inputs_group'] = [
			'title' => esc_html__( 'Inputs / Labels / Help text', 'extras' ),
			'tab'   => 'content',
		];

		$this->control_groups['prefix_group'] = [
			'title' => esc_html__( 'Prefix / Suffix', 'extras' ),
			'tab'   => 'content',
		];

		$this->control_groups['submit_group'] = [
			'title' => esc_html__( 'Buttons', 'extras' ),
			'tab'   => 'content',
		];

		$this->control_groups['checkbox_group'] = [
			'title' => esc_html__( 'Checkbox / Radio', 'extras' ),
			'tab'   => 'content',
		];

		$this->control_groups['messages_group'] = [
			'title' => esc_html__( 'Messages', 'extras' ),
			'tab'   => 'content',
		];

		$this->control_groups['phone_group'] = [
			'title' => esc_html__( 'Phone number', 'extras' ),
			'tab'   => 'content',
		];

		$this->control_groups['repeatable_group'] = [
			'title' => esc_html__( 'Repeater Buttons/Icons', 'extras' ),
			'tab'   => 'content',
		];

		$this->control_groups['range_group'] = [
			'title' => esc_html__( 'Range Slider', 'extras' ),
			'tab'   => 'content',
		];

		$this->control_groups['meter_group'] = [
			'title' => esc_html__( 'Meter / Progress Bar', 'extras' ),
			'tab'   => 'content',
		];

		$this->control_groups['tab_group'] = [
			'title' => esc_html__( 'Tabs', 'extras' ),
			'tab'   => 'content',
		];

		$this->control_groups['tab_content_group'] = [
			'title' => esc_html__( 'Tabs Content', 'extras' ),
			'tab'   => 'content',
		];

		$this->control_groups['file_upload_group'] = [
			'title' => esc_html__( 'File Upload', 'extras' ),
			'tab'   => 'content',
		];

		$this->control_groups['password_group'] = [
			'title' => esc_html__( 'Password', 'extras' ),
			'tab'   => 'content',
		];

		$this->control_groups['legal_group'] = [
			'title' => esc_html__( 'Legal', 'extras' ),
			'tab'   => 'content',
		];

		$this->control_groups['star_rating_group'] = [
			'title' => esc_html__( 'Star Rating', 'extras' ),
			'tab'   => 'content',
		];

	}

	public function set_controls() {


		// Remove default '_typography'
		unset( $this->controls['_typography'] );

		$this->controls['typography'] = [
			'tab'   => 'style',
			'group' => '_typography',
			'label' => esc_html__( 'Typography', 'bricks' ),
			'type'  => 'typography',
			'css'   => [
				[
					'property' => 'font',
					'selector' => '.wsf-form'
				],
			],
		];

		$this->controls['typographyh1'] = [
			'tab'   => 'style',
			'group' => '_typography',
			'label' => esc_html__( 'Typography', 'bricks' ) . ' (h1)',
			'type'  => 'typography',
			'css'   => [
				[
					'property' => 'font',
					'selector' => '.wsf-form h1'
				],
			],
		];

		$this->controls['typographyh2'] = [
			'tab'   => 'style',
			'group' => '_typography',
			'label' => esc_html__( 'Typography', 'bricks' ) . ' (h2)',
			'type'  => 'typography',
			'css'   => [
				[
					'property' => 'font',
					'selector' => '.wsf-form h2'
				],
			],
		];

		$this->controls['typographyh3'] = [
			'tab'   => 'style',
			'group' => '_typography',
			'label' => esc_html__( 'Typography', 'bricks' ) . ' (h3)',
			'type'  => 'typography',
			'css'   => [
				[
					'property' => 'font',
					'selector' => '.wsf-form h3'
				],
			],
		];

		$this->controls['typographyh4'] = [
			'tab'   => 'style',
			'group' => '_typography',
			'label' => esc_html__( 'Typography', 'bricks' ) . ' (h4)',
			'type'  => 'typography',
			'css'   => [
				[
					'property' => 'font',
					'selector' => '.wsf-form h4'
				],
			],
		];

		$this->controls['typographyh5'] = [
			'tab'   => 'style',
			'group' => '_typography',
			'label' => esc_html__( 'Typography', 'bricks' ) . ' (h5)',
			'type'  => 'typography',
			'css'   => [
				[
					'property' => 'font',
					'selector' => '.wsf-form h5'
				],
			],
		];

		$this->controls['typographyh6'] = [
			'tab'   => 'style',
			'group' => '_typography',
			'label' => esc_html__( 'Typography', 'bricks' ) . ' (h6)',
			'type'  => 'typography',
			'css'   => [
				[
					'property' => 'font',
					'selector' => '.wsf-form h6'
				],
			],
		];


		$formOptions = [];

		if ( method_exists('WS_Form_Common','get_forms_array') ) {
			$formOptions = WS_Form_Common::get_forms_array(false);
		}

		$this->controls['formSource'] = [
			'tab'         => 'content',
			'label'       => esc_html__( 'Add form ID dynamically', 'bricks' ),
			'type'        => 'checkbox',
			'inline'      => true,
		];

		$this->controls['formSelect'] = [
			'tab'         => 'content',
			'label'       => esc_html__( 'Select form', 'bricks' ),
			'type'        => 'select',
			'options' => $formOptions,
			'placeholder' => esc_html__( 'Select form', 'bricks' ),
			'clearable' => false,
			'required' => ['formSource', '!=', true],
		];

		$this->controls['formID'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Form ID', 'bricks' ),
			'type' => 'text',
			'required' => ['formSource', '=', true],
		];

		$inputsArray = [
			'input[type="date"].wsf-field',
			'input[type="datetime-local"].wsf-field',
			'input[type="month"].wsf-field',
			'input[type="password"].wsf-field', 
			'input[type="search"].wsf-field',
			'input[type="time"].wsf-field',
			'input[type="week"].wsf-field',
			'input[type="email"].wsf-field', 
			'input[type="number"].wsf-field', 
			'input[type="tel"].wsf-field',
			'input[type="text"].wsf-field', 
			'input[type="text"].wsf-field:not(.minicolors > input)', 
			'input[type="url"].wsf-field',
			'select.wsf-field',
			'select.wsf-field:not([multiple]):not([size])',
			'textarea.wsf-field',
			'[data-wsf-legal].wsf-field'
		];

		$placeholderSelectors = [
			'input[type=date].wsf-field::placeholder',
			'input[type=datetime-local].wsf-field::placeholder',
			'input[type=file].wsf-field::placeholder',
			'input[type=month].wsf-field::placeholder',
			'input[type=password].wsf-field::placeholder',
			'input[type=search].wsf-field::placeholder',
			'input[type=time].wsf-field::placeholder',
			'input[type=week].wsf-field::placeholder',
			'input[type=email].wsf-field::placeholder',
			'input[type=number].wsf-field::placeholder',
			'input[type=tel].wsf-field::placeholder',
			'input[type=text].wsf-field::placeholder',
			'input[type=url].wsf-field::placeholder',
			'select.wsf-field::placeholder',
			'textarea.wsf-field::placeholder'
		];

		$defaultArray = [
			'.wsf-form',
			'.wsf-form ul.wsf-group-tabs > li.wsf-tab-active > a',
			'.wsf-form ul.wsf-group-tabs > li > a',
			'ul.wsf-group-tabs.wsf-steps > li.wsf-tab-active ~ li > a:not(.wsf-tab-disabled):before',
			'.wsf-input-group-prepend, .wsf-input-group-append',
			'input[type=radio].wsf-field + label.wsf-label', 
			'input[type=checkbox].wsf-field + label.wsf-label', 
			'.xdsoft_datetimepicker .xdsoft_label', 
			'.xdsoft_datetimepicker .xdsoft_calendar td',
			'.xdsoft_datetimepicker .xdsoft_calendar th', 
			'.wsf-select2-dropdown .select2-results .select2-results__option',
			'select.wsf-field ~ .select2-container .select2-search--inline .select2-search__field', 
			'select.wsf-field ~ .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover',
			'select.wsf-field ~ .select2-container .select2-selection--single .select2-selection__rendered',
			'select.wsf-field ~ .select2-container .select2-selection--multiple .select2-selection__rendered',
			'select.wsf-field ~ .select2-container .select2-selection--single .select2-selection__placeholder',
			'select.wsf-field ~ .select2-container .select2-selection--multiple .select2-selection__placeholder',
			'select.wsf-field ~ .select2-container .select2-selection--single .select2-selection__placeholder',
			'select.wsf-field ~ .select2-container .select2-selection--multiple .select2-selection__placeholder',
			'.wsf-select2-dropdown .select2-search--dropdown .select2-search__field',
			'.wsf-select2-dropdown .select2-results .select2-results__option',
			'.wsf-help',
			'button.wsf-button',
			'.wsf-field-wrapper[data-type=range] input[type=range].wsf-field + datalist option', 
			'.wsf-field-wrapper[data-type=price_range] input[type=range].wsf-field + datalist option'
		];

		$inputs = '';

		$this->controls['generalColorSep'] = [
			'tab'   => 'content',
			'group'  => 'general_group',
			'label' => esc_html__( 'Form wide colors', 'extras' ),
			'description' => esc_html__( 'Used in multiple places across the form, will override all WS Form global styles', 'extras' ),
			'type'  => 'separator',
		];


	   

	  
		$defaultColorCSS = \BricksExtras\Helpers::doCSSrules('color', $defaultArray, true);
		$defaultColorCSS = array_merge( $defaultColorCSS, \BricksExtras\Helpers::doCSSrules('color', $inputsArray, true) );

		$extraDefaultStyles = [
			[
				'property' => 'border-color',
				'selector' => '.xdsoft_datetimepicker .xdsoft_calendar th', 
			 ],
		];

		$defaultColorCSS = array_merge( $defaultColorCSS, $extraDefaultStyles );

		$this->controls['generaldefaultColor'] = [
			'tab'    => 'content',
			'group'  => 'general_group',
			'type'   => 'color',
			'units' => true,
			'label'  => esc_html__( 'Default color', 'extras' ),
			'description'  => esc_html__( 'Labels and field values', 'extras' ),
			'css' => $defaultColorCSS
		];

		

		$invertedColorCSS = [
			[
			   'property' => 'color',
			   'selector' => '.wsf-form ul.wsf-group-tabs.wsf-steps > li > a:before', 
			],
			[
			   'property' => 'background-color',
			   'selector' => '.wsf-form ul.wsf-group-tabs.wsf-steps > li.wsf-tab-active > a:before', 
			],
			[
			   'property' => 'background-color',
			   'selector' => '.wsf-form ul.wsf-group-tabs.wsf-steps > li.wsf-tab-active ~ li > a:before', 
			],
			[
			   'property' => 'color',
			   'selector' => '[data-wsf-tooltip]:after', 
			],
			[
			   'property' => 'color',
			   'selector' => 'input[type=checkbox].wsf-field.wsf-button:checked + label.wsf-label,
			   input[type=radio].wsf-field.wsf-button:checked + label.wsf-label', 
			],
			[
			   'property' => 'color',
			   'selector' => '.xdsoft_datetimepicker .xdsoft_label > .xdsoft_select > div > .xdsoft_option.xdsoft_current', 
			],
			[
			   'property' => 'color',
			   'selector' => '.xdsoft_datetimepicker .xdsoft_label > .xdsoft_select > div > .xdsoft_option:hover'
			],
			[
			   'property' => 'color',
			   'selector' => '.xdsoft_datetimepicker .xdsoft_calendar td.xdsoft_current',
			],
			[
			   'property' => 'color',
			   'selector' => '.xdsoft_datetimepicker .xdsoft_timepicker .xdsoft_time_box>div>div.xdsoft_current',
			],
			[
			   'property' => 'color',
			   'selector' => '.xdsoft_datetimepicker .xdsoft_calendar td:hover',
			],
			[
			   'property' => 'color',
			   'selector' => '.xdsoft_datetimepicker .xdsoft_timepicker .xdsoft_time_box>div>div:hover',
			],
			[
			   'property' => 'color',
			   'selector' => 'button.wsf-button.wsf-button-primary',
			],
			[
			   'property' => 'color',
			   'selector' => 'button.wsf-button.wsf-button-secondary',
			],
			[
			   'property' => 'color',
			   'selector' => 'button.wsf-button.wsf-button-success',
			],
			[
			   'property' => 'color',
			   'selector' => 'button.wsf-button.wsf-button-danger',
			],
			[
			   'property' => 'color',
			   'selector' => 'button.wsf-button.wsf-button-inverted.wsf-button-primary:hover',
			],
			[
			   'property' => 'color',
			   'selector' => 'button.wsf-button.wsf-button-inverted.wsf-button-primary:focus',
			],
			[
			   'property' => 'color',
			   'selector' => 'button.wsf-button.wsf-button-inverted.wsf-button-success:hover',
			],
			[
			   'property' => 'color',
			   'selector' => 'button.wsf-button.wsf-button-inverted.wsf-button-success:focus',
			],
			[
			   'property' => 'color',
			   'selector' => 'button.wsf-button.wsf-button-inverted.wsf-button-information:hover',
			],
			[
			   'property' => 'color',
			   'selector' => 'button.wsf-button.wsf-button-inverted.wsf-button-information:focus',
			],
			[
			   'property' => 'color',
			   'selector' => 'button.wsf-button.wsf-button-inverted.wsf-button-warning:hover',
			],
			[
			   'property' => 'color',
			   'selector' => 'button.wsf-button.wsf-button-inverted.wsf-button-warning:focus',
			],
			[
			   'property' => 'color',
			   'selector' => 'button.wsf-button.wsf-button-inverted.wsf-button-danger:hover',
			],
			[
				'property' => 'background-color',
				'selector' => 'input[type=radio].wsf-field + label.wsf-label:before',
			],
			[
				'property' => 'background-color',
				'selector' => 'input[type=checkbox].wsf-field + label.wsf-label:before',
			],
			[
				'property' => 'background-color',
				'selector' => '.xdsoft_datetimepicker',
			],
			[
				'property' => 'background-color',
				'selector' => '.xdsoft_datetimepicker .xdsoft_label',
			],

			[
				'property' => 'color',
				'selector' => '.xdsoft_datetimepicker .xdsoft_calendar td.xdsoft_current',
			],
			[
				'property' => 'color',
				'selector' => '.xdsoft_datetimepicker .xdsoft_timepicker .xdsoft_time_box>div>div.xdsoft_current',
				'important' => true
			],
			[
				'property' => 'background-color',
				'selector' => '.wsf-groups input[type=radio].wsf-field:checked + label.wsf-label:after',
			],
			[
				'property' => 'background-color',
				'selector' => '.wsf-groups input[type=checkbox].wsf-field:checked + label.wsf-label:after',
			],
			[
				'property' => 'background-color',
				'selector' => 'select.wsf-field ~ .select2-container .select2-selection--single',
			],
			[
				'property' => 'background-color',
				'selector' => 'select.wsf-field ~ .select2-container .select2-selection--multiple',
			]
		];

		$invertedColorCSS = array_merge( $invertedColorCSS, \BricksExtras\Helpers::doCSSrules('background-color', $inputsArray, true) );

		$this->controls['generaldefaultInvertedColor'] = [
			'tab'    => 'content',
			'group'  => 'general_group',
			'type'   => 'color',
			'units' => true,
			'label'  => esc_html__( 'Inverted color', 'extras' ),
			'description'  => esc_html__( 'Field backgrounds and button text', 'extras' ),
			'css'  =>  $invertedColorCSS
		];

		$lightColorCSS = [
			[
				'property' => 'color',
				'selector' => '.wsf-form ul.wsf-group-tabs > li > a.wsf-tab-disabled'
			],
			[
				'property' => 'color',
				'selector' => '.wsf-form ul.wsf-group-tabs.wsf-steps > li > a.wsf-tab-disabled:before'
			],
			[
				'property' => 'color',
				'selector' => '.wsf-form ul.wsf-group-tabs.wsf-steps > li.wsf-tab-active ~ li > a.wsf-tab-disabled:before'
			],
			[
				'property' => 'color',
				'selector' => 'input[type=text].wsf-field ~ .dropzone .dz-message'
			],
			[
				'property' => 'color',
				'selector' => 'select.wsf-field ~ .select2-container .select2-search--inline .select2-search__field::placeholder'
			],
			
		];

		$lightColors = array_merge( $lightColorCSS, \BricksExtras\Helpers::doCSSrules('color', $placeholderSelectors, true) );

		

		$this->controls['generalLightColor'] = [
			'tab'    => 'content',
			'group'  => 'general_group',
			'type'   => 'color',
			'units' => true,
			'label'  => esc_html__( 'Light Color', 'extras' ),
			'description'  => esc_html__( 'Placeholders, help text, and disabled field values.', 'extras' ),
			'css'    => $lightColors
		];

		$lighterColorCSS = [
			[
				'property' => 'border-color',
				'selector' => 'button.wsf-button'
			],
			[
				'property' => 'background-color',
				'selector' => 'button.wsf-button'
			],
			[
				'property' => 'border-color',
				'selector' => 'button.wsf-button:disabled'
			],
			[
				'property' => 'background-color',
				'selector' => 'button.wsf-button:disabled'
			],
			[
				'property' => 'background-color',
				'selector' => 'ul.wsf-group-tabs.wsf-steps > li.wsf-tab-active ~ li > a:after'
			],
			[
				'property' => 'border-color',
				'selector' => 'ul.wsf-group-tabs.wsf-steps > li.wsf-tab-active ~ li > a:before'
			],
			[
				'property' => 'border-color',
				'selector' => 'input[type=text].wsf-field ~ .dropzone'
			],
			
			
		];

		$lighterColorCSS = array_merge( $lighterColorCSS, \BricksExtras\Helpers::doCSSrules('border-color', $inputsArray, true) );

		$this->controls['generalLighterColor'] = [
			'tab'    => 'content',
			'group'  => 'general_group',
			'type'   => 'color',
			'units' => true,
			'label'  => esc_html__( 'Lighter Color', 'extras' ),
			'description'  => esc_html__( 'Field borders and buttons.', 'extras' ),
			'css'    => $lighterColorCSS
		];

		$lightestColorCSS = [
			[
				'property' => 'background-color',
				'selector' => 'input[type=file].wsf-field::file-selector-button'
			],
			[
				'property' => 'background-color',
				'selector' => 'input[type=file].wsf-field::-webkit-file-upload-button'
			],
			[
				'property' => 'background-color',
				'selector' => 'input[type=text].wsf-field ~ .dropzone .wsf-progress'
			],
			[
				'property' => 'background-color',
				'selector' => 'input[type=text].wsf-field:disabled ~ .dropzone'
			],
			[
				'property' => 'background-color',
				'selector' => 'input[type=text].wsf-field:disabled ~ canvas'
			],
			[
				'property' => 'background-color',
				'selector' => 'meter.wsf-meter::-webkit-meter-bar'
			],
			[
				'property' => 'background-color',
				'selector' => 'progress.wsf-progress[value]'
			],
			[
				'property' => 'background-color',
				'selector' => 'progress.wsf-progress[value]::-webkit-progress-bar'
			],
			[
				'property' => 'background-color',
				'selector' => '.wsf-alert'
			],
			[
				'property' => 'background-color',
				'selector' => '.wsf-input-group-prepend',
			],
			[
				'property' => 'background-color',
				'selector' => '.wsf-input-group-append'
			],
			[
				'property' => 'background-color',
				'selector' => 'input[type=date].wsf-field:disabled'
			],
			[
				'property' => 'background-color',
				'selector' => 'input[type=datetime-local].wsf-field:disabled'
			],
			[
				'property' => 'background-color',
				'selector' => 'input[type=file].wsf-field:disabled'
			],
			[
				'property' => 'background-color',
				'selector' => 'input[type=month].wsf-field:disabled'
			],
			[
				'property' => 'background-color',
				'selector' => 'input[type=password].wsf-field:disabled'
			],
			[
				'property' => 'background-color',
				'selector' => 'input[type=search].wsf-field:disabled'
			],
			[
				'property' => 'background-color',
				'selector' => 'input[type=time].wsf-field:disabled'
			],
			[
				'property' => 'background-color',
				'selector' => 'input[type=week].wsf-field:disabled'
			],
			[
				'property' => 'background-color',
				'selector' => 'input[type=email].wsf-field:disabled'
			],
			[
				'property' => 'background-color',
				'selector' => 'input[type=number].wsf-field:disabled'
			],
			[
				'property' => 'background-color',
				'selector' => 'input[type=tel].wsf-field:disabled'
			],
			[
				'property' => 'background-color',
				'selector' => 'input[type=text].wsf-field:disabled'
			],
			[
				'property' => 'background-color',
				'selector' => 'input[type=url].wsf-field:disabled'
			],
			[
				'property' => 'background-color',
				'selector' => 'input[type=tel].wsf-field:disabled'
			],
			[
				'property' => 'background-color',
				'selector' => 'select.wsf-field:disabled'
			],
			[
				'property' => 'background-color',
				'selector' => 'textarea.wsf-field:disabled'
			],
			[
				'property' => 'background-color',
				'selector' => 'select.wsf-field ~ .select2-container--default.select2-container--disabled .select2-selection--single,select.wsf-field ~ .select2-container--default.select2-container--disabled .select2-selection--multiple'
			],
			[
				'property' => 'background-color',
				'selector' => 'select.wsf-field ~ .select2-container--default .select2-selection--multiple .select2-selection__choice'
			],
			[
				'property' => 'background-color',
				'selector' => '.wsf-select2-dropdown .select2-results .select2-results__option[aria-selected=true]'
			],
			[
				'property' => 'background-color',
				'selector' => '.wsf-select2-dropdown .select2-results .select2-results__option[aria-selected=true]'
			],
			[
				'property' => 'background-color',
				'selector' => 'input[type=checkbox].wsf-field:disabled + label.wsf-label:before'
			],
			[
				'property' => 'background-color',
				'selector' => 'input[type=radio].wsf-field:disabled + label.wsf-label:before'
			],
			[
				'property' => 'background-color',
				'selector' => '.xdsoft_datetimepicker .xdsoft_calendar td,body .xdsoft_datetimepicker .xdsoft_calendar th'
			],
			[
				'property' => '--wsf-color-upper-track',
				'selector' => 'input[type=range].wsf-field'
			],
			[
				'property' => 'background-color',
				'selector' => 'input[type=range].wsf-field::-webkit-slider-runnable-track'
			],
			[
				'property' => 'background-color',
				'selector' => 'input[type=range].wsf-field::-moz-range-track'
			],
			[
				'property' => 'background-color',
				'selector' => 'input[type=range].wsf-field::-ms-track'
			],
			[
				'property' => 'background-color',
				'selector' => 'input[type=range].wsf-field::-ms-fill-lower,input[type=range].wsf-field::-ms-fill-upper'
			],
			[
				'property' => 'background-color',
				'selector' => '.xdsoft_datetimepicker .xdsoft_calendar th'
			]

		];
		
	

		$this->controls['generalLightestColor'] = [
			'tab'    => 'content',
			'group'  => 'general_group',
			'type'   => 'color',
			'units' => true,
			'label'  => esc_html__( 'Lightest Color', 'extras' ),
			'description'  => esc_html__( 'Range slider backgrounds, progress bar backgrounds, and disabled field backgrounds.', 'extras' ),
			'css'    => $lightestColorCSS
		];

		$this->controls['generalPrimaryColor'] = [
			'tab'    => 'content',
			'group'  => 'general_group',
			'type'   => 'color',
			'units' => true,
			'label'  => esc_html__( 'Primary Color', 'extras' ),
			'description'  => esc_html__( 'Checkboxes, radios, range sliders, progress bars, and submit buttons.', 'extras' ),
			'css'    => [
				 [
					'property' => 'border-color',
					'selector' => '.wsf-form ul.wsf-group-tabs.wsf-steps > li > a:before', 
				 ],
				 [
					'property' => 'color',
					'selector' => '.wsf-form ul.wsf-group-tabs.wsf-steps > li.wsf-tab-active > a:before', 
				 ],
				 [
					'property' => 'border-color',
					'selector' => '.wsf-form ul.wsf-group-tabs.wsf-steps > li.wsf-tab-active > a:before', 
				 ],
				 [
					'property' => 'background-color',
					'selector' => 'button.wsf-button.wsf-button-primary', 
				 ],
				 [
					'property' => 'border-color',
					'selector' => 'button.wsf-button.wsf-button-primary', 
				 ],
				 [
					'property' => 'background-color',
					'selector' => 'input[type=radio].wsf-field:checked + label.wsf-label:before', 
				 ],
				 [
					'property' => 'border-color',
					'selector' => 'input[type=radio].wsf-field:checked + label.wsf-label:before', 
				 ],
				 [
					'property' => 'border-color',
					'selector' => 'input[type=radio].wsf-field:focus + label.wsf-label:before', 
				 ],
				 [
					'property' => 'background-color',
					'selector' => 'input[type=checkbox].wsf-field:checked + label.wsf-label:before', 
				 ],
				 [
					'property' => 'border-color',
					'selector' => 'input[type=checkbox].wsf-field:checked + label.wsf-label:before', 
				 ],
				 [
					'property' => 'border-color',
					'selector' => 'input[type=checkbox].wsf-field:focus + label.wsf-label:before', 
				 ],
				 [
					'property' => 'border-color',
					'selector' => 'input[type=date].wsf-field:focus', 
				 ],
				 [
					'property' => 'border-color',
					'selector' => 'input[type=datetime-local].wsf-field:focus', 
				 ],
				 [
					'property' => 'border-color',
					'selector' => 'input[type=file].wsf-field:focus', 
				],
				 [
					'property' => 'border-color',
					'selector' => 'input[type=month].wsf-field:focus', 
				],
				 [
					'property' => 'border-color',
					'selector' => 'input[type=password].wsf-field:focus', 
				],
				 [
					'property' => 'border-color',
					'selector' => 'input[type=search].wsf-field:focus', 
				],
				 [
					'property' => 'border-color',
					'selector' => 'input[type=time].wsf-field:focus', 
				],
				 [
					'property' => 'border-color',
					'selector' => 'input[type=week].wsf-field:focus', 
				],
				 [
					'property' => 'border-color',
					'selector' => 'input[type=email].wsf-field:focus', 
				],
				 [
					'property' => 'border-color',
					'selector' => 'input[type=number].wsf-field:focus', 
				],
				 [
					'property' => 'border-color',
					'selector' => 'input[type=tel].wsf-field:focus', 
				],
				 [
					'property' => 'border-color',
					'selector' => 'input[type=text].wsf-field:focus', 
				],
				 [
					'property' => 'border-color',
					'selector' => 'input[type=url].wsf-field:focus', 
				],
				 [
					'property' => 'border-color',
					'selector' => 'select.wsf-field:focus', 
				 ],
				 [
					'property' => 'border-color',
					'selector' => 'textarea.wsf-field:focus', 
				 ],
				 [
					'property' => 'background-color',
					'selector' => '.xdsoft_datetimepicker .xdsoft_calendar td:hover', 
					'important' => true
				 ],
				 [
					'property' => 'background-color',
					'selector' => '.xdsoft_datetimepicker .xdsoft_timepicker .xdsoft_time_box>div>div:hover', 
					'important' => true
				 ],
				 [
					'property' => 'background-color',
					'selector' => '.xdsoft_datetimepicker .xdsoft_label > .xdsoft_select > div > .xdsoft_option.xdsoft_current', 
					'important' => true
				 ],
				 [
					'property' => 'background-color',
					'selector' => '.xdsoft_datetimepicker .xdsoft_label > .xdsoft_select > div > .xdsoft_option:hover',
					'important' => true
				 ],

				 [
					'property' => 'background-color',
					'selector' => '.xdsoft_datetimepicker .xdsoft_calendar td.xdsoft_current',
					'important' => true
				 ],
				 [
					'property' => 'background-color',
					'selector' => '.xdsoft_datetimepicker .xdsoft_timepicker .xdsoft_time_box>div>div.xdsoft_current',
					'important' => true
				 ],
				 [
					'property' => 'background-color',
					'selector' => 'input[type=range].wsf-field[data-fill-lower-track]::-ms-fill-lower'
				],
				[
					'property' => 'background-color',
					'selector' => 'input[type=range].wsf-field::-webkit-slider-thumb'
				],
				[
					'property' => 'background-color',
					'selector' => 'input[type=range].wsf-field::-moz-range-thumb'
				],
				[
					'property' => 'background-color',
					'selector' => 'input[type=range].wsf-field::-ms-thumb'
				],
				 [
					'property' => 'border-color',
					'selector' => '.wsf-form ul.wsf-group-tabs.wsf-steps > li > a:not(.wsf-tab-disabled):focus:before',
				 ],
				 [
					'property' => 'border-color',
					'selector' => 'select.wsf-field ~ .select2-container--default.select2-container--focus:not(.select2-container--disabled) .select2-selection--single',
				 ],
				 [
					'property' => 'border-color',
					'selector' => 'select.wsf-field ~ .select2-container--default.select2-container--focus:not(.select2-container--disabled) .select2-selection--multiple',
				 ],
				 [
					'property' => 'border-color',
					'selector' => '.wsf-form ul.wsf-group-tabs.wsf-steps > li > a:before',
				 ],
				 [
					'property' => 'background-color',
					'selector' => '.wsf-form ul.wsf-group-tabs.wsf-steps > li > a:after',
				 ],
				 [
					'property' => 'background-color',
					'selector' => '.wsf-form ul.wsf-group-tabs.wsf-steps > li > a:before',
				 ],
				 [
					'property' => 'background-color',
					'selector' => 'input[type=range].wsf-field[data-fill-lower-track]::-ms-fill-lower'
				],
				[
					'property' => 'background-color',
					'selector' => 'input[type=range].wsf-field::-webkit-slider-thumb'
				],
				[
					'property' => 'background-color',
					'selector' => 'input[type=range].wsf-field::-moz-range-thumb'
				],
				[
					'property' => 'background-color',
					'selector' => 'input[type=range].wsf-field::-ms-thumb'
				],
				[
					'property' => '--wsf-color-lower-track',
					'selector' => 'input[type=range].wsf-field'
				],
				[
					'property' => 'background-color',
					'selector' => 'progress.wsf-progress[value]::-webkit-progress-value'
				],
				[
					'property' => 'background-color',
					'selector' => 'progress.wsf-progress[value]::-moz-progress-bar'
				],
				[
					'property' => 'background-color',
					'selector' => 'progress.wsf-progress[value]::-ms-fill'
				],
				 
				
			]
		];

		$this->controls['generalSecondaryColor'] = [
			'tab'    => 'content',
			'group'  => 'general_group',
			'type'   => 'color',
			'units' => true,
			'label'  => esc_html__( 'Secondary Color', 'extras' ),
			'description'  => esc_html__( 'Secondary elements such as a reset button.', 'extras' ),
			'css'    => [
				 [
					'property' => 'border-color',
					'selector' => 'input[type=date].wsf-field', 
				 ],
				 [
					'property' => 'border-color',
					'selector' => 'button.wsf-button.wsf-button-secondary', 
				 ],
				 [
					'property' => 'background-color',
					'selector' => 'button.wsf-button.wsf-button-secondary', 
				 ],
				 [
					'property' => 'border-color',
					'selector' => 'button.wsf-button.wsf-button-secondary:disabled', 
				 ],
				 [
					'property' => 'background-color',
					'selector' => 'button.wsf-button.wsf-button-secondary:disabled', 
				 ],
				 [
					'property' => 'border-color',
					'selector' => 'button.wsf-button.wsf-button-inverted.wsf-button-secondary', 
				 ],
				 [
					'property' => 'color',
					'selector' => 'button.wsf-button.wsf-button-inverted.wsf-button-secondary', 
				 ],

				 [
					'property' => 'border-color',
					'selector' => 'button.wsf-button.wsf-button-inverted.wsf-button-secondary:disabled', 
				 ],
				 [
					'property' => 'color',
					'selector' => 'button.wsf-button.wsf-button-inverted.wsf-button-secondary:disabled', 
				 ],
				 [
					'property' => 'background-color',
					'selector' => 'button.wsf-button.wsf-button-inverted.wsf-button-secondary:focus', 
				 ],
				 [
					'property' => 'border-color',
					'selector' => 'button.wsf-button.wsf-button-inverted.wsf-button-secondary:focus', 
				 ],
				 [
					'property' => 'color',
					'selector' => 'wsf-text-secondary collator_asort', 
				 ],

				 

				 
			]
		];

		$this->controls['generalSuccessColor'] = [
			'tab'    => 'content',
			'group'  => 'general_group',
			'type'   => 'color',
			'units' => true,
			'label'  => esc_html__( 'Success Color', 'extras' ),
			'description'  => esc_html__( 'Completed progress bars, save buttons, and success messages.', 'extras' ),
			'css'    => [
				 [
					'property' => 'border-color',
					'selector' => '.wsf-form ul.wsf-group-tabs.wsf-steps.wsf-steps-success > li > a:before', 
				 ],
				 [
					'property' => 'background-color',
					'selector' => '.wsf-form ul.wsf-group-tabs.wsf-steps.wsf-steps-success > li > a:before', 
				 ],
				 [
					'property' => 'background-color',
					'selector' => '.wsf-form ul.wsf-group-tabs.wsf-steps.wsf-steps-success > li > a:after', 
				 ],
				[
					'property' => 'border-color',
					'selector' => '.wsf-form ul.wsf-group-tabs.wsf-steps.wsf-steps-success > li > a:not(.wsf-tab-disabled):focus:before', 
				],
				[
					'property' => 'color',
					'selector' => '.wsf-form ul.wsf-group-tabs.wsf-steps.wsf-steps-success > li.wsf-tab-active > a:before', 
				],
				[
					'property' => 'color',
					'selector' => '.wsf-form ul.wsf-group-tabs.wsf-steps.wsf-steps-success > li.wsf-tab-active > a:before', 
				],
				[
					'property' => 'background-color',
					'selector' => 'input[type=text].wsf-field ~ .dropzone .wsf-progress.wsf-progress-success .wsf-upload', 
				],
				[
					'property' => 'background-color',
					'selector' => 'progress.wsf-progress.wsf-progress-success[value]::-webkit-progress-value', 
				],
				[
					'property' => 'background-color',
					'selector' => 'progress.wsf-progress.wsf-progress-success[value]::-moz-progress-bar', 
				],
				[
					'property' => 'background-color',
					'selector' => 'progress.wsf-progress.wsf-progress-success[value]::-ms-fill', 
				],
				[
					'property' => 'background-color',
					'selector' => 'meter.wsf-meter::-webkit-meter-optimum-value', 
				],
				[
					'property' => 'background-color',
					'selector' => 'meter.wsf-meter:-moz-meter-optimum::-moz-meter-bar', 
				],
				[
					'property' => 'background-color',
					'selector' => 'button.wsf-button.wsf-button-success', 
				 ],
				[
					'property' => 'border-color',
					'selector' => 'button.wsf-button.wsf-button-success', 
				],

				[
					'property' => 'background-color',
					'selector' => 'button.wsf-button.wsf-button-success:disabled', 
				 ],
				[
					'property' => 'border-color',
					'selector' => 'button.wsf-button.wsf-button-success:disabled', 
				],

				[
					'property' => 'border-color',
					'selector' => 'button.wsf-button.wsf-button-inverted.wsf-button-success:disabled', 
				 ],
				[
					'property' => 'color',
					'selector' => 'button.wsf-button.wsf-button-inverted.wsf-button-success:disabled', 
				],
				[
					'property' => 'color',
					'selector' => '.wsf-text-success', 
				],
				[
					'property' => 'background-color',
					'selector' => '.wsf-alert.wsf-alert-success', 
				],
				[
					'property' => 'border-color',
					'selector' => '.wsf-alert.wsf-alert-success', 
				],
				[
					'property' => 'background-color',
					'selector' => 'button.wsf-button.wsf-button-success', 
				],
				
				
				
			]
		];

		$this->controls['generalInformationBg'] = [ 
			'tab'    => 'content',
			'group'  => 'general_group',
			'type'   => 'color',
			'units' => true,
			'label'  => esc_html__( 'Information', 'extras' ),
			'description'  => esc_html__( 'Information messages.', 'extras' ),
			'css'    => [
				 [
					'property' => 'background-color',
					'selector' => '.wsf-alert.wsf-alert-information', 
				 ],
				 [
					'property' => 'border-color',
					'selector' => '.wsf-alert.wsf-alert-information', 
				 ],
				 [
					'property' => 'background-color',
					'selector' => 'button.wsf-button.wsf-button-information', 
				 ],
				 [
					'property' => 'border-color',
					'selector' => 'button.wsf-button.wsf-button-information', 
				 ],

				 
			]
		];

		$this->controls['generalWarningColor'] = [
			'tab'    => 'content',
			'group'  => 'general_group',
			'type'   => 'color',
			'units' => true,
			'label'  => esc_html__( 'Warning Color', 'extras' ),
			'description'  => esc_html__( 'Warning messages', 'extras' ),
			'css'    => [
				 [
					'property' => 'border-color',
					'selector' => 'input[type=date].wsf-field', 
				 ],
				 [
					'property' => 'background-color',
					'selector' => 'button.wsf-button.wsf-button-warning', 
				 ],
				 [
					'property' => 'border-color',
					'selector' => 'button.wsf-button.wsf-button-warning', 
				 ],
				 [
					'property' => 'background-color',
					'selector' => '.wsf-alert.wsf-alert-warning', 
				 ],
				 [
					'property' => 'border-color',
					'selector' => '.wsf-alert.wsf-alert-warning', 
				 ],
			]
		];

		$this->controls['generalDangerColor'] = [
			'tab'    => 'content',
			'group'  => 'general_group',
			'type'   => 'color',
			'units' => true,
			'label'  => esc_html__( 'Danger Color', 'extras' ),
			'description'  => esc_html__( 'Required field labels, invalid field borders, invalid feedback, remove repeatable section buttons, and danger messages.', 'extras' ),
			'css'    => [
				 [
					'property' => 'border-color',
					'selector' => 'input[type=date].wsf-field', 
				 ],
				 [
					'property' => 'color',
					'selector' => '.wsf-invalid-feedback', 
				 ],
				 [
					'property' => 'background-color',
					'selector' => 'button.wsf-button.wsf-button-danger', 
				 ],
				 [
					'property' => 'border-color',
					'selector' => 'button.wsf-button.wsf-button-danger', 
				 ],
				 [
					'property' => 'background-color',
					'selector' => '.wsf-alert.wsf-alert-danger', 
				 ],
				 [
					'property' => 'border-color',
					'selector' => '.wsf-alert.wsf-alert-danger', 
				 ],
				 
			]
		];

		$this->controls['generalBorderColor'] = [
			'tab'    => 'content',
			'group'  => 'general_group',
			'type'   => 'color',
			'units' => true,
			'label'  => esc_html__( 'Border color', 'extras' ),
			'css'    => [
				[
					'property' => 'border-color',
					'selector' => 'input[type=date].wsf-field, 
								   input[type=datetime-local].wsf-field, 
								   input[type=file].wsf-field, 
								   input[type=month].wsf-field, 
								   input[type=password].wsf-field, 
								   input[type=search].wsf-field, 
								   input[type=time].wsf-field, 
								   input[type=week].wsf-field, 
								   input[type=email].wsf-field, 
								   input[type=number].wsf-field, 
								   input[type=tel].wsf-field, 
								   input[type=text].wsf-field, 
								   input[type=url].wsf-field, 
								   select.wsf-field, textarea.wsf-field'
				],
				[
					'property' => 'border-top-color',
					'selector' => '.wsf-form ul.wsf-group-tabs > li.wsf-tab-active > a'
				],
				[
					'property' => 'border-left-color',
					'selector' => '.wsf-form ul.wsf-group-tabs > li.wsf-tab-active > a'
				],
				[
					'property' => 'border-right-color',
					'selector' => '.wsf-form ul.wsf-group-tabs > li.wsf-tab-active > a'
				],
				[
					'property' => 'border-bottom-color',
					'selector' => '.wsf-form ul.wsf-group-tabs'
				],

				

			],
		];

		$this->controls['focusBoxShadow'] = [
			'tab'    => 'content',
			'group'  => 'general_group',
			'type'   => 'color',
			'info' => esc_html__( 'The box-shadow color that is added to every input when focused', 'extras' ),
			'label'  => esc_html__( 'Focus shadow color', 'extras' ),
			'css'    => [
				[
					'property' => '--xwsf-focus-shadow',
					'selector' => '.wsf-form'
				]
			]
		];

		$this->controls['spacing_sep'] = [
			'tab'   => 'content',
			'group'  => 'spacing_group',
			'type'  => 'separator',
		];

		


		

		/* inputs / labels */


		/* spacing */

		$this->controls['gridGutter'] = [
			'tab'   => 'content',
			'group' => 'inputs_group',
			'label' => esc_html__( 'Space between form elements', 'extras' ),
			'type' => 'number',
			'placeholder' => '20',
			'units'    => true,
			'css'   => [
				[
					'property' => '--xwsf-grid-gutter',
					'selector' => '.wsf-form',
				],
				[
					'property' => 'margin-bottom',
					'selector' => '.wsf-form .wsf-field-wrapper',
				],
				
			],
		];
	  
		 
		$this->controls['labelsSep'] = [
			'tab'   => 'content',
			'group'  => 'inputs_group',
			'label' => esc_html__( 'Labels', 'extras' ),
			'type'  => 'separator',
		  ];
	  

  
	 	 $this->controls['labelsTypography'] = [
			  'tab'    => 'content',
			  'group'  => 'inputs_group',
			  'type'   => 'typography',
			  'label'  => esc_html__( 'Typography', 'extras' ),
			  'css'      => [
				[
					'property' => 'font',
					'selector' => '.wsf-form label',
				],
				[
					'property' => 'font',
					'selector' => '.wsf-form label.wsf-label',
				],
			],
		  ];

		  $this->controls['asteriskColor'] = [
			'tab'      => 'content',
			'group'  => 'inputs_group',
			'label'    => esc_html__( 'Asterisk Color', 'extras' ),
			'type'     => 'color',
			'css'      => [
				[
					'property' => 'color',
					'selector' => '.wsf-required-wrapper .wsf-text-danger',
				],
			],
		];

		  $this->controls['labelMargin'] = [
			'tab'   => 'content',
			'group' => 'inputs_group',
			'label' => esc_html__( 'Margin', 'extras' ),
			'type'  => 'dimensions',
			'css'   => [
				[
					'property' => 'margin',
					'selector' => '.wsf-form label:not(input[type=checkbox].wsf-field + label.wsf-label)',
				],
				[
					'property' => 'margin',
					'selector' => '.wsf-form label.wsf-label:not(input[type=checkbox].wsf-field + label.wsf-label)',
				],
			],
		 ];
		

			$this->controls['inputStyle_start'] = [
				'tab'   => 'content',
				'group'  => 'inputs_group',
				'label' => esc_html__( 'Inputs', 'extras' ),
				'type'  => 'separator',
		  	];
	  
			  $this->controls['inputTypography'] = [
				'tab'    => 'content',
				'group'  => 'inputs_group',
				'type'   => 'typography',
				'label'  => esc_html__( 'Field typography', 'extras' ),
				'css'    =>  \BricksExtras\Helpers::doCSSrules('font', $inputsArray)
			];

			
	
			$this->controls['placeholderTypography'] = [
				'tab'    => 'content',
				'group'  => 'inputs_group',
				'type'   => 'typography',
				'label'  => esc_html__( 'Placeholder typography', 'extras' ),
				'css'    => \BricksExtras\Helpers::doCSSrules('font', $placeholderSelectors)
			];

			
	  
			  $this->controls['inputBackgroundColor'] = [
				  'tab'    => 'content',
				  'group'  => 'inputs_group',
				  'type'   => 'color',
				  'label'  => esc_html__( 'Background', 'extras' ),
				  'css'    => \BricksExtras\Helpers::doCSSrules('background-color', $inputsArray)
			  ];
	  
			  $this->controls['inputBorder'] = [
				  'tab'    => 'content',
				  'group'  => 'inputs_group',
				  'type'   => 'border',
				  'label'  => esc_html__( 'Border', 'extras' ),
				  'css'    => \BricksExtras\Helpers::doCSSrules('border', $inputsArray)
			  ];
	  
			  $this->controls['inputBoxShadow'] = [
				  'tab'    => 'content',
				  'group'  => 'inputs_group',
				  'label'  => esc_html__( 'Box Shadow', 'extras' ),
				  'type'   => 'box-shadow',
				  'css'    =>  \BricksExtras\Helpers::doCSSrules('box-shadow', $inputsArray)
			  ];
	  
			  $this->controls['inputPadding'] = [
				  'tab'   => 'content',
				  'group' => 'inputs_group',
				  'label' => esc_html__( 'Input Padding', 'extras' ),
				  'type'  => 'dimensions',
				  'css'   =>  \BricksExtras\Helpers::doCSSrules('padding', array_diff($inputsArray, ['input[type="text"].wsf-field', 'select.wsf-field:not([multiple]):not([size])', 'select.wsf-field']) )
			  ];

			  $this->controls['selectPaddingSep'] = [
				'tab'   => 'content',
				'group'  => 'inputs_group',
				'type'  => 'separator',
			  ];

			  $this->controls['selectPadding'] = [
				'tab'   => 'content',
				'group' => 'inputs_group',
				'label' => esc_html__( 'Select padding', 'extras' ),
				'type'  => 'dimensions',
				'css'   => [
					[
						'property' => 'padding',
						'selector' => 'select.wsf-field',
					],
				],
			];

			/* phone inputs */

			$phoneInput = '.wsf-form input[type="tel"].wsf-field.wsf-field';

			$this->controls['phoneSep'] = [
				'tab'   => 'content',
				'group'  => 'phone_group',
				'label' => esc_html__( 'Phone input', 'extras' ),
				'type'  => 'separator',
			  ];


			  $this->controls['phoneinputTypography'] = [
				'tab'    => 'content',
				'group'  => 'phone_group',
				'type'   => 'typography',
				'label'  => esc_html__( 'Typography', 'extras' ),
				'css'    =>  [
					[
						'property' => 'font',
						'selector' => $phoneInput,
					],
				],
			];

			
	
			$this->controls['phoneplaceholderTypography'] = [
				'tab'    => 'content',
				'group'  => 'phone_group',
				'type'   => 'typography',
				'label'  => esc_html__( 'Placeholder typography', 'extras' ),
				'css'    =>  [
					[
						'property' => 'font',
						'selector' => $phoneInput . '::placeholder',
					],
				],
			];

			
	  
			  $this->controls['phoneinputBackgroundColor'] = [
				  'tab'    => 'content',
				  'group'  => 'phone_group',
				  'type'   => 'color',
				  'label'  => esc_html__( 'Background', 'extras' ),
				  'css'    => [
					[
						'property' => 'background-color',
						'selector' => $phoneInput,
					],
				],
			  ];
	  
			  $this->controls['phoneinputBorder'] = [
				  'tab'    => 'content',
				  'group'  => 'phone_group',
				  'type'   => 'border',
				  'label'  => esc_html__( 'Border', 'extras' ),
				  'css'    => [
					[
						'property' => 'border',
						'selector' => $phoneInput,
					],
				],
			  ];
	  
			  $this->controls['phoneinputBoxShadow'] = [
				  'tab'    => 'content',
				  'group'  => 'phone_group',
				  'label'  => esc_html__( 'Box Shadow', 'extras' ),
				  'type'   => 'box-shadow',
				  'css'    => [
					[
						'property' => 'box-shadow',
						'selector' => $phoneInput,
					],
				],
			  ];
	  
			 


			/* international inputs */

			$this->controls['international'] = [
				'tab'   => 'content',
				'group'  => 'phone_group',
				'label' => esc_html__( 'International phone Inputs', 'extras' ),
				'type'  => 'separator',
			];

			$this->controls['phoneinputPadding'] = [
				'tab'   => 'content',
				'group' => 'phone_group',
				'label' => esc_html__( 'Input Padding', 'extras' ),
				'type'  => 'dimensions',
				 'css'    => [
				  [
					  'property' => 'padding',
					  'selector' => '.wsf-form .iti__flag-container + input[type="tel"].wsf-field.wsf-field',
				  ],
			  ],
			];

			$this->controls['selectedFlagBg'] = [
				'tab'    => 'content',
				'group'  => 'phone_group',
				'type'   => 'color',
				'label'  => esc_html__( 'Selected Flag Background', 'extras' ),
				'css'    => [
				  [
					  'property' => 'background-color',
					  'selector' => '.iti__selected-flag',
				  ],
			  ],
			];

			$this->controls['selectedFlagPadding'] = [
				'tab'    => 'content',
				'group'  => 'phone_group',
				'type'   => 'dimensions',
				'label'  => esc_html__( 'Selected Flag padding', 'extras' ),
				'css'    => [
				  [
					  'property' => 'padding',
					  'selector' => '.iti__selected-flag',
				  ],
			  ],
			];

			$this->controls['dropdownArrowColor'] = [
				'tab'    => 'content',
				'group'  => 'phone_group',
				'type'   => 'color',
				'label'  => esc_html__( 'Dropdown arrow color', 'extras' ),
				'css'    => [
				  [
					  'property' => 'color',
					  'selector' => '.iti__arrow',
				  ],
				  [
					'property' => 'color',
					'selector' => '.iti__arrow:not(.iti__arrow--up)',
				],
			  ],
			];

			$this->controls['internationalDropdownSep'] = [
				'tab'   => 'content',
				'group'  => 'phone_group',
				'label' => esc_html__( 'International dropdown', 'extras' ),
				'type'  => 'separator',
			];

			$this->controls['countryListBackground'] = [
				'tab'    => 'content',
				'group'  => 'phone_group',
				'type'   => 'color',
				'label'  => esc_html__( 'Country list background', 'extras' ),
				'css'    => [
				  [
					  'property' => 'background-color',
					  'selector' => '.iti__country-list',
				  ],
			  ],
			];

			$this->controls['countryListtypography'] = [
				'tab'    => 'content',
				'group'  => 'phone_group',
				'type'   => 'typography',
				'label'  => esc_html__( 'Country typography', 'extras' ),
				'css'    => [
				  [
					  'property' => 'font',
					  'selector' => '.iti__country-name',
				  ],
			  ],
			];

			$this->controls['countryDialCodetypography'] = [
				'tab'    => 'content',
				'group'  => 'phone_group',
				'type'   => 'typography',
				'label'  => esc_html__( 'Country dial code typography', 'extras' ),
				'css'    => [
				  [
					  'property' => 'font',
					  'selector' => '.iti__dial-code',
				  ],
			  ],
			];

			$this->controls['countryPadding'] = [
				'tab'   => 'content',
				'group' => 'phone_group',
				'label' => esc_html__( 'Country padding', 'extras' ),
				'type'  => 'dimensions',
				'css'   => [
					[
						'property' => 'padding',
						'selector' => '.iti__country',
					],
				],
			 ];

			

			$this->controls['countryHighlightBackground'] = [
				'tab'    => 'content',
				'group'  => 'phone_group',
				'type'   => 'color',
				'label'  => esc_html__( 'Country highlight background', 'extras' ),
				'css'    => [
				  [
					  'property' => 'background-color',
					  'selector' => '.iti__country.iti__highlight',
				  ],
			  ],
			];

			$this->controls['countryHighlightColor'] = [
				'tab'    => 'content',
				'group'  => 'phone_group',
				'type'   => 'color',
				'label'  => esc_html__( 'Country highlight color', 'extras' ),
				'css'    => [
				  [
					  'property' => 'color',
					  'selector' => '.iti__country.iti__highlight .iti__country-name',
				  ],
			  ],
			];

			$this->controls['countryDialCodeHighlightColor'] = [
				'tab'    => 'content',
				'group'  => 'phone_group',
				'type'   => 'typography',
				'label'  => esc_html__( 'Country dial code highlight color', 'extras' ),
				'css'    => [
				  [
					  'property' => 'font',
					  'selector' => '.iti__country.iti__highlight .iti__dial-code',
				  ],
			  ],
			];

			


			$this->controls['helpSep'] = [
				'tab'   => 'content',
				'group'  => 'inputs_group',
				'label' => esc_html__( 'Help text', 'extras' ),
				'type'  => 'separator',
			  ];
		  
	
	  
			  $this->controls['helpTypography'] = [
				  'tab'    => 'content',
				  'group'  => 'inputs_group',
				  'type'   => 'typography',
				  'label'  => esc_html__( 'Typography', 'extras' ),
				  'css'    =>  [
					[
						'property' => 'font',
						'selector' => '.wsf-form .wsf-help'
				    ]
				  ]
			  ];

			  $this->controls['helpTypographyBg'] = [
				'tab'    => 'content',
				'group'  => 'inputs_group',
				'type'   => 'color',
				'label'  => esc_html__( 'Background', 'extras' ),
				'css'    =>  [
				  [
					  'property' => 'background-color',
					  'selector' => '.wsf-form .wsf-help', 
				  ]
				]
			];

			$this->controls['helpTypographyBorder'] = [
				'tab'    => 'content',
				'group'  => 'inputs_group',
				'type'   => 'border',
				'label'  => esc_html__( 'Border', 'extras' ),
				'css'    =>  [
				  [
					  'property' => 'background-color',
					  'selector' => '.wsf-form .wsf-help',
				  ]
				]
			];

			$this->controls['helpPadding'] = [
				'tab'   => 'content',
				'group' => 'inputs_group',
				'label' => esc_html__( 'Padding', 'extras' ),
				'type'  => 'dimensions',
				'css'   => [
					[
						'property' => 'padding',
						'selector' => '.wsf-form .wsf-help',
					],
					
				],
			 ];
	
			  $this->controls['helpMargin'] = [
				'tab'   => 'content',
				'group' => 'inputs_group',
				'label' => esc_html__( 'Margin', 'extras' ),
				'type'  => 'dimensions',
				'css'   => [
					[
						'property' => 'margin',
						'selector' => '.wsf-form .wsf-help',
					],
				],
			 ];

			 $this->controls['invalidSep'] = [
				'tab'   => 'content',
				'group'  => 'inputs_group',
				'label' => esc_html__( 'Invalid feedback text', 'extras' ),
				'type'  => 'separator',
			  ];

			  $this->controls['invalidTypography'] = [
				'tab'    => 'content',
				'group'  => 'inputs_group',
				'type'   => 'typography',
				'label'  => esc_html__( 'Typography', 'extras' ),
				'css'    =>  [
				  [
					  'property' => 'font',
					  'selector' => '.wsf-form .wsf-invalid-feedback'
				  ]
				]
			];

			$this->controls['invalidTypographyBg'] = [
				'tab'    => 'content',
				'group'  => 'inputs_group',
				'type'   => 'color',
				'label'  => esc_html__( 'Background', 'extras' ),
				'css'    =>  [
				  [
					  'property' => 'background-color',
					  'selector' => '.wsf-form .wsf-invalid-feedback'
				  ]
				]
			];

			$this->controls['invalidTypographyBorder'] = [
				'tab'    => 'content',
				'group'  => 'inputs_group',
				'type'   => 'border',
				'label'  => esc_html__( 'Border', 'extras' ),
				'css'    =>  [
				  [
					  'property' => 'background-color',
					  'selector' => '.wsf-form .wsf-invalid-feedback'
				  ]
				]
			];

			$this->controls['invalidPadding'] = [
				'tab'   => 'content',
				'group' => 'inputs_group',
				'label' => esc_html__( 'Padding', 'extras' ),
				'type'  => 'dimensions',
				'css'   => [
					[
						'property' => 'padding',
						'selector' => '.wsf-form .wsf-invalid-feedback'
					],
					[
					  'property' => 'padding',
					  'selector' => '.wsf-form input[type=checkbox].wsf-field + label.wsf-label + .wsf-invalid-feedback'
				  ],
					
				],
			 ];
  
			$this->controls['invalidMargin'] = [
			  'tab'   => 'content',
			  'group' => 'inputs_group',
			  'label' => esc_html__( 'Margin', 'extras' ),
			  'type'  => 'dimensions',
			  'css'   => [
				  [
					  'property' => 'margin',
					  'selector' => '.wsf-form .wsf-invalid-feedback'
				  ],
				  [
					'property' => 'margin',
					'selector' => '.wsf-form input[type=checkbox].wsf-field + label.wsf-label + .wsf-invalid-feedback'
				],
				  
			  ],
		   ];


			 /* prefix */

			 $this->controls['prefixSep'] = [
				'tab'   => 'content',
				'group'  => 'prefix_group',
				'label' => esc_html__( 'Prefix', 'extras' ),
				'type'  => 'separator',
			  ];
		  
			  $this->controls['prefixBackground'] = [
				'tab'    => 'content',
				'group'  => 'prefix_group',
				'type'   => 'color',
				'label'  => esc_html__( 'Background', 'extras' ),
				'css'    =>  [
				  [
					  'property' => 'background-color',
					  'selector' => '.wsf-form .wsf-input-group-prepend'
				  ]
				]
			];

			$this->controls['prefixBorder'] = [
				'tab'    => 'content',
				'group'  => 'prefix_group',
				'type'   => 'border',
				'label'  => esc_html__( 'Border', 'extras' ),
				'css'    =>  [
				  [
					  'property' => 'border',
					  'selector' => '.wsf-form .wsf-input-group-prepend'
				  ]
				]
			 ];

			 $this->controls['prefixInputBorder'] = [
				'tab'    => 'content',
				'group'  => 'prefix_group',
				'type'   => 'border',
				'label'  => esc_html__( 'Input Border when has prefix', 'extras' ),
				'css'    =>  [
				  [
					  'property' => 'border',
					  'selector' => '.wsf-form .wsf-input-group-has-prepend input[type].wsf-field'
				  ]
				]
			 ];
	  
			  $this->controls['prefixTypography'] = [
				  'tab'    => 'content',
				  'group'  => 'prefix_group',
				  'type'   => 'typography',
				  'label'  => esc_html__( 'Typography', 'extras' ),
				  'css'    =>  [
					[
						'property' => 'font',
						'selector' => '.wsf-form .wsf-input-group-prepend'
				    ]
				  ]
			  ];

			  $this->controls['prefixPadding'] = [
				'tab'   => 'content',
				'group' => 'prefix_group',
				'label' => esc_html__( 'Padding', 'extras' ),
				'type'  => 'dimensions',
				'css'   => [
					[
						'property' => 'padding',
						'selector' => '.wsf-form .wsf-input-group-prepend'
					],
				],
			 ];


			 /* suffix */

			 $this->controls['suffixSep'] = [
				'tab'   => 'content',
				'group'  => 'prefix_group',
				'label' => esc_html__( 'suffix', 'extras' ),
				'type'  => 'separator',
			  ];
		  
			  $this->controls['suffixBackground'] = [
				'tab'    => 'content',
				'group'  => 'prefix_group',
				'type'   => 'color',
				'label'  => esc_html__( 'Background', 'extras' ),
				'css'    =>  [
				  [
					  'property' => 'background-color',
					  'selector' => '.wsf-form .wsf-input-group-append'
				  ]
				]
			];

			$this->controls['suffixBorder'] = [
				'tab'    => 'content',
				'group'  => 'prefix_group',
				'type'   => 'border',
				'label'  => esc_html__( 'Border', 'extras' ),
				'css'    =>  [
				  [
					  'property' => 'border',
					  'selector' => '.wsf-form .wsf-input-group-append'
				  ]
				]
			 ];

			 $this->controls['suffixInputBorder'] = [
				'tab'    => 'content',
				'group'  => 'prefix_group',
				'type'   => 'border',
				'label'  => esc_html__( 'Input Border when has suffix', 'extras' ),
				'css'    =>  [
				  [
					  'property' => 'border',
					  'selector' => '.wsf-form .wsf-input-group-has-append input[type].wsf-field'
				  ]
				]
			 ];
	  
			  $this->controls['suffixTypography'] = [
				  'tab'    => 'content',
				  'group'  => 'prefix_group',
				  'type'   => 'typography',
				  'label'  => esc_html__( 'Typography', 'extras' ),
				  'css'    =>  [
					[
						'property' => 'font',
						'selector' => '.wsf-form .wsf-input-group-append'
				    ]
				  ]
			  ];

			  $this->controls['suffixPadding'] = [
				'tab'   => 'content',
				'group' => 'prefix_group',
				'label' => esc_html__( 'Padding', 'extras' ),
				'type'  => 'dimensions',
				'css'   => [
					[
						'property' => 'padding',
						'selector' => '.wsf-form .wsf-input-group-append'
					],
				],
			 ];

			 



			  /* submit */

			  $mainButton = '.wsf-form button.wsf-button';
		  
				$this->controls['submitPadding'] = [
					'tab'   => 'content',
					'group' => 'submit_group',
					'label' => esc_html__( 'Padding', 'extras' ),
					'type'  => 'dimensions',
					'css'   => [
						[
							'property' => 'padding',
							'selector' => $mainButton,
						],
					],
				];
		  
		  
			  $this->controls['submitWidth'] = [
					  'tab'   => 'content',
					  'group' => 'submit_group',
					  'label' => esc_html__( 'Width', 'extras' ),
					  'type' => 'number',
					  'units'    => true,
					  'css'   => [
						  [
							  'property' => 'width',
							  'selector' => $mainButton,
						  ],
					  ],
				  ];
		  
		  
			  $this->controls['inputTextAlign'] = array(
				'tab'         => 'content',
					  'group' => 'submit_group',
					  'label'       => esc_html__( 'Button align', 'extras' ),
					  'type'        => 'text-align',
					  'css'         => [
						  [
							  'property' => 'text-align',
							  'selector' => $mainButton
						  ],
					  ],
					  'inline'      => true,
					  'placeholder' => 'left',
				);

			 	 $this->controls['buttonTypography'] = [
					  'tab'    => 'content',
					  'group'  => 'submit_group',
					  'type'   => 'typography',
					  'label'  => esc_html__( 'Typography', 'extras' ),
					  'css'    => [
						  [
							  'property' => 'font',
							  'selector' => $mainButton
						  ],
					  ],
				  ];
		  
				  $this->controls['submitStyle_start'] = [
						'tab'   => 'content',
						'group'  => 'submit_group',
						'type'  => 'separator',
						'label'  => esc_html__( 'Submit Button', 'extras' ),
					];

			  	$submitButton = '.wsf-form button.wsf-button.wsf-button-primary';
				  
				  $this->controls['submitTypography'] = [
					'tab'    => 'content',
					'group'  => 'submit_group',
					'type'   => 'typography',
					'label'  => esc_html__( 'Typography', 'extras' ),
					'css'    => [
						[
							'property' => 'font',
							'selector' => $submitButton
						],
					],
				];
		  
				  $this->controls['submitBackgroundColor'] = [
					  'tab'    => 'content',
					  'group'  => 'submit_group',
					  'type'   => 'color',
					  'label'  => esc_html__( 'Background', 'extras' ),
					  'css'    => [
						  [
							  'property' => 'background-color',
							  'selector' => $submitButton,
						  ],
					  ],
				  ];
		  
				  $this->controls['submitBorder'] = [
					  'tab'    => 'content',
					  'group'  => 'submit_group',
					  'type'   => 'border',
					  'label'  => esc_html__( 'Border', 'extras' ),
					  'css'    => [
						  [
							  'property' => 'border',
							  'selector' => $submitButton
						  ],
					  ],
				  ];
		  
				  $this->controls['submitBoxShadow'] = [
					  'tab'    => 'content',
					  'group'  => 'submit_group',
					  'label'  => esc_html__( 'Box Shadow', 'extras' ),
					  'type'   => 'box-shadow',
					  'css'    => [
						  [
							  'property' => 'box-shadow',
							  'selector' => $submitButton,
						  ],
					  ],
				  ];

				  $this->controls['saveStyle_start'] = [
					'tab'   => 'content',
					'group'  => 'submit_group',
					'type'  => 'separator',
					'label'  => esc_html__( 'Save Button', 'extras' ),
				];

				  $saveButton = '.wsf-form button.wsf-button[data-action="wsf-save"]';
		  
				  $this->controls['saveTypography'] = [
					'tab'    => 'content',
					'group'  => 'submit_group',
					'type'   => 'typography',
					'label'  => esc_html__( 'Typography', 'extras' ),
					'css'    => [
						[
							'property' => 'font',
							'selector' => $saveButton
						],
					],
				];
		  
				  $this->controls['saveBackgroundColor'] = [
					  'tab'    => 'content',
					  'group'  => 'submit_group',
					  'type'   => 'color',
					  'label'  => esc_html__( 'Background', 'extras' ),
					  'css'    => [
						  [
							  'property' => 'background-color',
							  'selector' => $saveButton,
						  ],
					  ],
				  ];
		  
				  $this->controls['saveBorder'] = [
					  'tab'    => 'content',
					  'group'  => 'submit_group',
					  'type'   => 'border',
					  'label'  => esc_html__( 'Border', 'extras' ),
					  'css'    => [
						  [
							  'property' => 'border',
							  'selector' => $saveButton
						  ],
					  ],
				  ];
		  
				  $this->controls['saveBoxShadow'] = [
					  'tab'    => 'content',
					  'group'  => 'submit_group',
					  'label'  => esc_html__( 'Box Shadow', 'extras' ),
					  'type'   => 'box-shadow',
					  'css'    => [
						  [
							  'property' => 'box-shadow',
							  'selector' => $saveButton,
						  ],
					  ],
				  ];

				  $this->controls['resetStyle_start'] = [
					'tab'   => 'content',
					'group'  => 'submit_group',
					'type'  => 'separator',
					'label'  => esc_html__( 'Reset Button', 'extras' ),
				];

				  $resetButton = '.wsf-form button.wsf-button[data-action="wsf-reset"]';
		  
				  $this->controls['resetTypography'] = [
					'tab'    => 'content',
					'group'  => 'submit_group',
					'type'   => 'typography',
					'label'  => esc_html__( 'Typography', 'extras' ),
					'css'    => [
						[
							'property' => 'font',
							'selector' => $resetButton
						],
					],
				];
		  
				  $this->controls['resetBackgroundColor'] = [
					  'tab'    => 'content',
					  'group'  => 'submit_group',
					  'type'   => 'color',
					  'label'  => esc_html__( 'Background', 'extras' ),
					  'css'    => [
						  [
							  'property' => 'background-color',
							  'selector' => $resetButton,
						  ],
					  ],
				  ];
		  
				  $this->controls['resetBorder'] = [
					  'tab'    => 'content',
					  'group'  => 'submit_group',
					  'type'   => 'border',
					  'label'  => esc_html__( 'Border', 'extras' ),
					  'css'    => [
						  [
							  'property' => 'border',
							  'selector' => $resetButton
						  ],
					  ],
				  ];
		  
				  $this->controls['resetBoxShadow'] = [
					  'tab'    => 'content',
					  'group'  => 'submit_group',
					  'label'  => esc_html__( 'Box Shadow', 'extras' ),
					  'type'   => 'box-shadow',
					  'css'    => [
						  [
							  'property' => 'box-shadow',
							  'selector' => $resetButton,
						  ],
					  ],
				  ];

				  $this->controls['clearStyle_start'] = [
					'tab'   => 'content',
					'group'  => 'submit_group',
					'type'  => 'separator',
					'label'  => esc_html__( 'clear Button', 'extras' ),
				];

				  $clearButton = '.wsf-form button.wsf-button[data-action="wsf-clear"]';
		  
				  $this->controls['clearTypography'] = [
					'tab'    => 'content',
					'group'  => 'submit_group',
					'type'   => 'typography',
					'label'  => esc_html__( 'Typography', 'extras' ),
					'css'    => [
						[
							'property' => 'font',
							'selector' => $clearButton
						],
					],
				];
		  
				  $this->controls['clearBackgroundColor'] = [
					  'tab'    => 'content',
					  'group'  => 'submit_group',
					  'type'   => 'color',
					  'label'  => esc_html__( 'Background', 'extras' ),
					  'css'    => [
						  [
							  'property' => 'background-color',
							  'selector' => $clearButton,
						  ],
					  ],
				  ];
		  
				  $this->controls['clearBorder'] = [
					  'tab'    => 'content',
					  'group'  => 'submit_group',
					  'type'   => 'border',
					  'label'  => esc_html__( 'Border', 'extras' ),
					  'css'    => [
						  [
							  'property' => 'border',
							  'selector' => $clearButton
						  ],
					  ],
				  ];
		  
				  $this->controls['clearBoxShadow'] = [
					  'tab'    => 'content',
					  'group'  => 'submit_group',
					  'label'  => esc_html__( 'Box Shadow', 'extras' ),
					  'type'   => 'box-shadow',
					  'css'    => [
						  [
							  'property' => 'box-shadow',
							  'selector' => $clearButton,
						  ],
					  ],
				  ];

				  $this->controls['prevStyle_start'] = [
					'tab'   => 'content',
					'group'  => 'submit_group',
					'type'  => 'separator',
					'label'  => esc_html__( 'Previous Button', 'extras' ),
				];

				  $previousButton = '.wsf-form button.wsf-button[data-action="wsf-tab_previous"]';
		  
				  $this->controls['prevTypography'] = [
					'tab'    => 'content',
					'group'  => 'submit_group',
					'type'   => 'typography',
					'label'  => esc_html__( 'Typography', 'extras' ),
					'css'    => [
						[
							'property' => 'font',
							'selector' => $previousButton
						],
					],
				];
		  
				  $this->controls['prevBackgroundColor'] = [
					  'tab'    => 'content',
					  'group'  => 'submit_group',
					  'type'   => 'color',
					  'label'  => esc_html__( 'Background', 'extras' ),
					  'css'    => [
						  [
							  'property' => 'background-color',
							  'selector' => $previousButton,
						  ],
					  ],
				  ];
		  
				  $this->controls['prevBorder'] = [
					  'tab'    => 'content',
					  'group'  => 'submit_group',
					  'type'   => 'border',
					  'label'  => esc_html__( 'Border', 'extras' ),
					  'css'    => [
						  [
							  'property' => 'border',
							  'selector' => $previousButton
						  ],
					  ],
				  ];
		  
				  $this->controls['prevBoxShadow'] = [
					  'tab'    => 'content',
					  'group'  => 'submit_group',
					  'label'  => esc_html__( 'Box Shadow', 'extras' ),
					  'type'   => 'box-shadow',
					  'css'    => [
						  [
							  'property' => 'box-shadow',
							  'selector' => $previousButton,
						  ],
					  ],
				  ];


				  $this->controls['nextStyle_start'] = [
					'tab'   => 'content',
					'group'  => 'submit_group',
					'type'  => 'separator',
					'label'  => esc_html__( 'Next Button', 'extras' ),
				];

				  $nextButton = '.wsf-form button.wsf-button[data-action="wsf-tab_next"]';
		  
				  $this->controls['nextTypography'] = [
					'tab'    => 'content',
					'group'  => 'submit_group',
					'type'   => 'typography',
					'label'  => esc_html__( 'Typography', 'extras' ),
					'css'    => [
						[
							'property' => 'font',
							'selector' => $nextButton
						],
					],
				];
		  
				  $this->controls['nextBackgroundColor'] = [
					  'tab'    => 'content',
					  'group'  => 'submit_group',
					  'type'   => 'color',
					  'label'  => esc_html__( 'Background', 'extras' ),
					  'css'    => [
						  [
							  'property' => 'background-color',
							  'selector' => $nextButton,
						  ],
					  ],
				  ];
		  
				  $this->controls['nextBorder'] = [
					  'tab'    => 'content',
					  'group'  => 'submit_group',
					  'type'   => 'border',
					  'label'  => esc_html__( 'Border', 'extras' ),
					  'css'    => [
						  [
							  'property' => 'border',
							  'selector' => $nextButton
						  ],
					  ],
				  ];
		  
				  $this->controls['nextBoxShadow'] = [
					  'tab'    => 'content',
					  'group'  => 'submit_group',
					  'label'  => esc_html__( 'Box Shadow', 'extras' ),
					  'type'   => 'box-shadow',
					  'css'    => [
						  [
							  'property' => 'box-shadow',
							  'selector' => $nextButton,
						  ],
					  ],
				  ];

				  $this->controls['moveUpStyle_start'] = [
					'tab'   => 'content',
					'group'  => 'submit_group',
					'type'  => 'separator',
					'label'  => esc_html__( 'Move Up Button', 'extras' ),
				];

				  $moveUpButton = '.wsf-form button.wsf-button[data-action="wsf-section-move-up-button"]';
		  
				  $this->controls['moveUpTypography'] = [
					'tab'    => 'content',
					'group'  => 'submit_group',
					'type'   => 'typography',
					'label'  => esc_html__( 'Typography', 'extras' ),
					'css'    => [
						[
							'property' => 'font',
							'selector' => $moveUpButton
						],
					],
				];
		  
				  $this->controls['moveUpBackgroundColor'] = [
					  'tab'    => 'content',
					  'group'  => 'submit_group',
					  'type'   => 'color',
					  'label'  => esc_html__( 'Background', 'extras' ),
					  'css'    => [
						  [
							  'property' => 'background-color',
							  'selector' => $moveUpButton,
						  ],
					  ],
				  ];
		  
				  $this->controls['moveUpBorder'] = [
					  'tab'    => 'content',
					  'group'  => 'submit_group',
					  'type'   => 'border',
					  'label'  => esc_html__( 'Border', 'extras' ),
					  'css'    => [
						  [
							  'property' => 'border',
							  'selector' => $moveUpButton
						  ],
					  ],
				  ];
		  
				  $this->controls['moveUpBoxShadow'] = [
					  'tab'    => 'content',
					  'group'  => 'submit_group',
					  'label'  => esc_html__( 'Box Shadow', 'extras' ),
					  'type'   => 'box-shadow',
					  'css'    => [
						  [
							  'property' => 'box-shadow',
							  'selector' => $moveUpButton,
						  ],
					  ],
				  ];

				  $this->controls['moveDownStyle_start'] = [
					'tab'   => 'content',
					'group'  => 'submit_group',
					'type'  => 'separator',
					'label'  => esc_html__( 'Move Down Button', 'extras' ),
				];

				  $moveDownButton = '.wsf-form button.wsf-button[data-action="wsf-section-move-down-button"]';
		  
				  $this->controls['moveDownTypography'] = [
					'tab'    => 'content',
					'group'  => 'submit_group',
					'type'   => 'typography',
					'label'  => esc_html__( 'Typography', 'extras' ),
					'css'    => [
						[
							'property' => 'font',
							'selector' => $moveDownButton
						],
					],
				];
		  
				  $this->controls['moveDownBackgroundColor'] = [
					  'tab'    => 'content',
					  'group'  => 'submit_group',
					  'type'   => 'color',
					  'label'  => esc_html__( 'Background', 'extras' ),
					  'css'    => [
						  [
							  'property' => 'background-color',
							  'selector' => $moveDownButton,
						  ],
					  ],
				  ];
		  
				  $this->controls['moveDownBorder'] = [
					  'tab'    => 'content',
					  'group'  => 'submit_group',
					  'type'   => 'border',
					  'label'  => esc_html__( 'Border', 'extras' ),
					  'css'    => [
						  [
							  'property' => 'border',
							  'selector' => $moveDownButton
						  ],
					  ],
				  ];
		  
				  $this->controls['moveDownBoxShadow'] = [
					  'tab'    => 'content',
					  'group'  => 'submit_group',
					  'label'  => esc_html__( 'Box Shadow', 'extras' ),
					  'type'   => 'box-shadow',
					  'css'    => [
						  [
							  'property' => 'box-shadow',
							  'selector' => $moveDownButton,
						  ],
					  ],
				  ];



				  


			/* checkbox */


			$checkbox_input = '.wsf-form input[type=checkbox].wsf-field + label.wsf-label:before';
			$radio_input = '.wsf-form input[type=radio].wsf-field + label.wsf-label:before';
			
			$checkbox_input_after = '.wsf-form input[type=checkbox].wsf-field + label.wsf-label:after';
			$radio_input_after = '.wsf-form input[type=radio].wsf-field + label.wsf-label:after';

			$checkbox_input_checked = '.wsf-form input[type=checkbox].wsf-field:checked + label.wsf-label:before';
			$radio_input_checked = '.wsf-form input[type=radio].wsf-field:checked + label.wsf-label:before';

			
			$this->controls['radioCheckBoxLabel'] = [
				'tab'    => 'content',
				'group'  => 'checkbox_group',
				'label'  => esc_html__( 'Label typography', 'extras' ),
				'type'   => 'typography',
				'css'    => [
					[
						'property' => 'font',
						'selector' => '.wsf-form input[type=checkbox].wsf-field + label.wsf-label'
					],
					[
						'property' => 'font',
						'selector' => '.wsf-form input[type=radio].wsf-field + label.wsf-label'
					],
				],
			];

			

			

				$this->controls['radioCheckboxSize'] = [
					'tab'      => 'content',
					'group'    => 'checkbox_group',
					'label'    => esc_html__( 'Size', 'extras' ),
					'type' => 'number',
					'units'    => true,
					'css'      => [
						[
							'property'  => 'width',
							'selector'  => $checkbox_input,
						],
						[
							'property' => 'width',
							'selector' => $radio_input,
						],
						[
							'property'  => 'width',
							'selector'  => $checkbox_input_after,
						],
						[
							'property' => 'width',
							'selector' => $radio_input_after,
						],
						[
							'property'  => 'height',
							'selector'  => $checkbox_input,
						],
						[
							'property' => 'height',
							'selector' => $radio_input,
						],
						[
							'property'  => 'height',
							'selector'  => $checkbox_input_after,
						],
						[
							'property' => 'height',
							'selector' => $radio_input_after,
						],
						[
							'property' => '--xwsf-checkbox-size',
							'selector' => '.wsf-form',
						]
						
					],
				];

				

				$this->controls['radioCheckboxMargin'] = [
					'tab'   => 'content',
					'group' => 'checkbox_group',
					'label' => esc_html__( 'Vertical spacing', 'extras' ),
					'type'  => 'number',
					'units' => true,
					'css'   => [
						[
							'property'  => '-webkit-margin-end',
							'selector'  => '.wsf-form .wsf-inline',
						],
						[
							'property'  => 'margin-end',
							'selector'  => '.wsf-form .wsf-inline',
						],
						[
							'property'  => 'margin-bottom',
							'selector'  => '.wsf-form input[type=checkbox].wsf-field + label.wsf-label',
						],
						[
							'property'  => 'margin-bottom',
							'selector'  => '.wsf-form input[type=radio].wsf-field + label.wsf-label',
						],
						
					],
				];

				$this->controls['radioCheckboxSpacing'] = [
					'tab'   => 'content',
					'group' => 'checkbox_group',
					'label' => esc_html__( 'Horizontal spacing', 'extras' ),
					'type'  => 'number',
					'units' => true,
					'css'   => [
						[
							'property'  => '--xwsf-checkbox-spacing',
							'selector'  => '.wsf-form',
						],
					],
				];

				$this->controls['radioCheckboxColor'] = [
					'tab'      => 'content',
					'group'    => 'checkbox_group',
					'label'    => esc_html__( 'Color', 'extras' ),
					'type'     => 'color',
					'css'      => [
						[
							'property' => 'background',
							'selector' => $checkbox_input,
						],
						[
							'property' => 'background',
							'selector' => $radio_input,
						],
					],
				];

				$this->controls['radioCheckboxBgColorChecked'] = [
					'tab'      => 'content',
					'group'    => 'checkbox_group',
					'label'    => esc_html__( 'Checked Color', 'extras' ),
					'type'     => 'color',
					'css'      => [
						[
							'property' => 'background-color',
							'selector' => $checkbox_input_checked
						],
						[
							'property' => 'background-color',
							'selector' => $radio_input_checked
						],
						[
							'property' => 'border-color',
							'selector' => $checkbox_input_checked
						],
						[
							'property' => 'border-color',
							'selector' => $radio_input_checked
						],
					],
				];

				$this->controls['radioCheckboxColorChecked'] = [
					'tab'      => 'content',
					'group'    => 'checkbox_group',
					'label'    => esc_html__( 'Checked Inner Color', 'extras' ),
					'type'     => 'color',
					'css'      => [
						[
							'property' => 'background-color',
							'selector' => '.wsf-form .wsf-groups input[type=radio].wsf-field:checked + label.wsf-label:after'
						],
						[
							'property' => 'background-color',
							'selector' => '.wsf-form .wsf-groups input[type=checkbox].wsf-field:checked + label.wsf-label:after'
						],
					],
				];

				$this->controls['radioCheckboxAlignItems'] = [
					'tab'      => 'content',
					'label'    => esc_html__( 'Align main axis', 'bricks' ),
					'group'		  => 'checkbox_group',
					'exclude' => [ 'stretch' ],
					'type'     => 'align-items',
					'css'      => [
						[
							'property' => 'align-items',
							'selector' => '.wsf-form input[type=radio].wsf-field + label.wsf-label',
						],
						[
							'property' => 'align-items',
							'selector' => '.wsf-form input[type=checkbox].wsf-field + label.wsf-label',
						],
					],
					//'required' => [ '_display', '=', 'flex' ],
				];


				

		/* range slider */


		$this->controls['rangeSliderUpperBg'] = [
			'tab'      => 'content',
			'group'    => 'range_group',
			'label'    => esc_html__( 'Slider Color (upper track)', 'extras' ),
			'type'     => 'color',
			'css'      => [
				[
					'property' => '--wsf-color-upper-track',
					'selector' => '.wsf-form input[type=range].wsf-field'
				],
				[
					'property' => 'background-color',
					'selector' => '.wsf-form input[type=range].wsf-field::-webkit-slider-runnable-track'
				],
				[
					'property' => 'background-color',
					'selector' => '.wsf-form input[type=range].wsf-field::-moz-range-track'
				],
				[
					'property' => 'background-color',
					'selector' => '.wsf-form input[type=range].wsf-field::-ms-track'
				],
				[
					'property' => 'background-color',
					'selector' => '.wsf-form input[type=range].wsf-field::-ms-fill-lower,input[type=range].wsf-field::-ms-fill-upper'
				],
			],
		];

		$this->controls['rangeSliderBg'] = [
			'tab'      => 'content',
			'group'    => 'range_group',
			'label'    => esc_html__( 'Slider Color (lower track)', 'extras' ),
			'type'     => 'color',
			'css'      => [
				[
					'property' => 'background-color',
					'selector' => 'input[type=range].wsf-field[data-fill-lower-track]::-ms-fill-lower'
				],
				[
					'property' => '--wsf-color-lower-track',
					'selector' => '.wsf-form input[type=range].wsf-field'
				],
			],
		];


		$this->controls['rangeSliderControlColor'] = [
			'tab'      => 'content',
			'group'    => 'range_group',
			'label'    => esc_html__( 'Control Color', 'extras' ),
			'type'     => 'color',
			'css'      => [
				[
					'property' => 'background-color',
					'selector' => '.wsf-form input[type=range].wsf-field[data-fill-lower-track]::-ms-fill-lower'
				],
				[
					'property' => 'background-color',
					'selector' => '.wsf-form input[type=range].wsf-field::-webkit-slider-thumb'
				],
				[
					'property' => 'background-color',
					'selector' => '.wsf-form input[type=range].wsf-field::-moz-range-thumb'
				],
				[
					'property' => 'background-color',
					'selector' => '.wsf-form input[type=range].wsf-field::-ms-thumb'
				]
			],
		];

		$this->controls['tickTypography'] = [
			'tab'   => 'content',
			'group' => 'range_group',
			'label' => esc_html__( 'Tick Mark Typography', 'extras' ),
			'type'  => 'typography',
			'css'   => [
				[
					'property' => 'font',
					'selector' => ".wsf-form .wsf-field-wrapper[data-type='range'] input[type=range].wsf-field + datalist option",
				],
				[
					'property' => 'font',
					'selector' => ".wsf-field-wrapper[data-type='price_range'] input[type=range].wsf-field + datalist option",
				],
			],
		];


		/* Meter */

		$this->controls['meterSep'] = [
			'tab'   => 'content',
			'group' => 'meter_group',
			'label' => esc_html__( 'Meter', 'extras' ),
			'type'  => 'separator',
		];


		
		$this->controls['meterBackground'] = [
			'tab'   => 'content',
			'group' => 'meter_group',
			'label' => esc_html__( 'Background', 'extras' ),
			'type'  => 'color',
			'css'   => [
				[
					'property' => 'background-color',
					'selector' => '.wsf-form meter.wsf-meter::-webkit-meter-bar',
				],
			],
		];

		$this->controls['meterActiveBackground'] = [
			'tab'   => 'content',
			'group' => 'meter_group',
			'label' => esc_html__( 'Active background', 'extras' ),
			'type'  => 'color',
			'css'   => [
				[
					'property' => 'background-color',
					'selector' => '.wsf-form meter.wsf-meter::-webkit-meter-optimum-value', 
				],
				[
					'property' => 'background-color',
					'selector' => '.wsf-form meter.wsf-meter:-moz-meter-optimum::-moz-meter-bar', 
				],
			],
		];

		$this->controls['progressSep'] = [
			'tab'   => 'content',
			'group' => 'meter_group',
			'label' => esc_html__( 'Progress Bar', 'extras' ),
			'type'  => 'separator',
		];


		$this->controls['progressBackground'] = [
			'tab'   => 'content',
			'group' => 'meter_group',
			'label' => esc_html__( 'Background', 'extras' ),
			'type'  => 'color',
			'css'   => [
				[
					'property' => 'background-color',
					'selector' => '.wsf-form progress.wsf-progress[value]',
				],
				[
					'property' => 'background-color',
					'selector' => '.wsf-form progress.wsf-progress[value]::-webkit-progress-bar',
				],

				
			],
		];

		$this->controls['progessActiveBackground'] = [
			'tab'   => 'content',
			'group' => 'meter_group',
			'label' => esc_html__( 'Active background', 'extras' ),
			'type'  => 'color',
			'css'   => [
				[
					'property' => 'background-color',
					'selector' => '.wsf-form progress.wsf-progress[value]::-webkit-progress-value'
				],
				[
					'property' => 'background-color',
					'selector' => '.wsf-form progress.wsf-progress[value]::-moz-progress-bar'
				],
				[
					'property' => 'background-color',
					'selector' => '.wsf-form progress.wsf-progress[value]::-ms-fill'
				],
				
			],
		];

		

		/* tabs */

		$this->controls['tabTypography'] = [
			'tab'   => 'content',
			'group' => 'tab_group',
			'label' => esc_html__( 'Typography', 'extras' ),
			'type'  => 'typography',
			'css'   => [
				[
					'property' => 'font',
					'selector' => '.wsf-form ul.wsf-group-tabs > li > a',
				],
			],
		];

		$this->controls['tabActiveTypography'] = [
			'tab'   => 'content',
			'group' => 'tab_group',
			'label' => esc_html__( 'Typography (active)', 'extras' ),
			'type'  => 'typography',
			'css'   => [
				[
					'property' => 'font',
					'selector' => '.wsf-form ul.wsf-group-tabs > li.wsf-tab-active > a',
				],
			],
		];

		$this->controls['tab_sep'] = [
			'tab'   => 'content',
			'group'  => 'tab_group',
			'label' => esc_html__( 'Tabs (standard)', 'extras' ),
			'type'  => 'separator',
		];


		$this->controls['tabBorder'] = [
			'tab'   => 'content',
			'group' => 'tab_group',
			'label' => esc_html__( 'Border color', 'extras' ),
			'type'  => 'color',
			'css'   => [
				[
					'property' => 'border-top-color',
					'selector' => '.wsf-form ul.wsf-group-tabs > li.wsf-tab-active > a',
				],
				[
					'property' => 'border-left-color',
					'selector' => '.wsf-form ul.wsf-group-tabs > li.wsf-tab-active > a',
				],
				[
					'property' => 'border-right-color',
					'selector' => '.wsf-form ul.wsf-group-tabs > li.wsf-tab-active > a',
				],
				[
					'property' => 'border-color',
					'selector' => '.wsf-form ul.wsf-group-tabs',
				],
			],
		];

		$this->controls['tabBackground'] = [
			'tab'   => 'content',
			'group' => 'tab_group',
			'label' => esc_html__( 'Background', 'extras' ),
			'type'  => 'color',
			'css'   => [
				[
					'property' => 'background-color',
					'selector' => '.wsf-form ul.wsf-group-tabs > li > a',
				],
			],
		];

		$this->controls['tabActiveBackground'] = [
			'tab'   => 'content',
			'group' => 'tab_group',
			'label' => esc_html__( 'Background (active)', 'extras' ),
			'type'  => 'color',
			'css'   => [
				[
					'property' => 'background-color',
					'selector' => '.wsf-form ul.wsf-group-tabs > li.wsf-tab-active > a',
				],
			],
		];

		$this->controls['tabWidth'] = [
			'tab'   => 'content',
			'group' => 'tab_group',
			'label' => esc_html__( 'Tab width', 'extras' ),
			'type'  => 'number',
			'units'  => true,
			'css'   => [
				[
					'property' => 'width',
					'selector' => '.wsf-form ul.wsf-group-tabs > li',
				],
			],
		];
		
		$this->controls['tabPadding'] = [
			'tab'   => 'content',
			'group' => 'tab_group',
			'label' => esc_html__( 'Tab padding', 'extras' ),
			'type'  => 'dimensions',
			'css'   => [
				[
					'property' => 'padding',
					'selector' => '.wsf-form ul.wsf-group-tabs > li > a',
				],
			],
		];

		$this->controls['tabsMargin'] = [
			'tab'   => 'content',
			'group' => 'tab_group',
			'label' => esc_html__( 'Tabs margin', 'extras' ),
			'type'  => 'dimensions',
			'css'   => [
				[
					'property' => 'margin',
					'selector' => '.wsf-form ul.wsf-group-tabs',
				],
			],
		];

		

		


		$this->controls['tabStepsSep'] = [
			'tab'   => 'content',
			'group'  => 'tab_group',
			'label' => esc_html__( 'Tabs (Steps style)', 'extras' ),
			'type'  => 'separator',
		];

		$this->controls['tabStepsSize'] = [
			'tab'   => 'content',
			'group' => 'tab_group',
			'label' => esc_html__( 'Steps size', 'extras' ),
			'type'  => 'number',
			'units'  => true,
			'css'   => [
				[
					'property' => '--x-wsf-step-size',
					'selector' => '.wsf-form',
				],
			],
		];

		$this->controls['tabStepsTypography'] = [
			'tab'   => 'content',
			'group' => 'tab_group',
			'label' => esc_html__( 'Steps Typography', 'extras' ),
			'type'  => 'typography',
			'css'   => [
				[
					'property' => 'font',
					'selector' => '.wsf-form ul.wsf-group-tabs.wsf-steps.wsf-tabs > li > a:before',
				],
			],
		];

		$this->controls['tabStepsLabelTypography'] = [
			'tab'   => 'content',
			'group' => 'tab_group',
			'label' => esc_html__( 'Steps Label Typography', 'extras' ),
			'type'  => 'typography',
			'css'   => [
				[
					'property' => 'font',
					'selector' => '.wsf-form ul.wsf-group-tabs.wsf-steps.wsf-tabs > li > a span',
				],
			],
		];

		$this->controls['tabStepsBorder'] = [
			'tab'   => 'content',
			'group' => 'tab_group',
			'label' => esc_html__( 'Tabs Color', 'extras' ),
			'type'  => 'color',
			'css'   => [
				[
					'property' => 'border-color',
					'selector' => '.wsf-form ul.wsf-group-tabs.wsf-steps.wsf-tabs > li.wsf-tab-active ~ li > a:before',
				],
				[
					'property' => 'background-color',
					'selector' => '.wsf-form ul.wsf-group-tabs.wsf-steps > li.wsf-tab-active ~ li > a:after',
				],
				[
					'property' => 'color',
					'selector' => '.wsf-form ul.wsf-group-tabs.wsf-steps.wsf-tabs > li.wsf-tab-active ~ li > a:before',
				],

				
			],
		];

		$this->controls['tabStepsActiveBorder'] = [
			'tab'   => 'content',
			'group' => 'tab_group',
			'label' => esc_html__( 'Tabs Active color', 'extras' ),
			'type'  => 'color',
			'css'   => [
				[
					'property' => 'border-color',
					'selector' => '.wsf-form ul.wsf-group-tabs.wsf-steps.wsf-tabs > li.wsf-tab-active > a:before',
				],
				[
					'property' => 'color',
					'selector' => '.wsf-form ul.wsf-group-tabs.wsf-steps.wsf-tabs > li.wsf-tab-active > a:before',
				],
				[
					'property' => 'background-color',
					'selector' => '.wsf-form ul.wsf-group-tabs.wsf-steps.wsf-tabs > li > a:after',
				],
				[
					'property' => 'background-color',
					'selector' => '.wsf-form ul.wsf-group-tabs.wsf-steps > li > a:before',
				],
				[
					'property' => 'border-color',
					'selector' => '.wsf-form ul.wsf-group-tabs.wsf-steps > li > a:before',
				],
				
				
			],
		];

		

		
		/* tab content */


		$this->controls['tabContentBackground'] = [
			'tab'      => 'content',
			'group'    => 'tab_content_group',
			'label'    => esc_html__( 'Background', 'extras' ),
			'type'     => 'background',
			'css'      => [
				[
					'property' => 'background',
					'selector' => '.wsf-form .wsf-group'
				],
			],
		];

		$this->controls['tabContentBorder'] = [
			'tab'      => 'content',
			'group'    => 'tab_content_group',
			'label'    => esc_html__( 'Border', 'extras' ),
			'type'     => 'border',
			'css'      => [
				[
					'property' => 'border',
					'selector' => '.wsf-form .wsf-group'
				],
			],
		];
		
		$this->controls['tabContentPadding'] = [
			'tab'   => 'content',
			'group' => 'tab_content_group',
			'label' => esc_html__( 'Padding', 'extras' ),
			'type'  => 'dimensions',
			'css'   => [
				[
					'property' => 'padding',
					'selector' => '.wsf-form .wsf-group',
				],
			],
		];


		/* file upload file_upload_group */

		$this->controls['fileUploadColor'] = [
			'tab'      => 'content',
			'group'    => 'file_upload_group',
			'label'    => esc_html__( 'Typography', 'extras' ),
			'type'     => 'typography',
			'css'      => [
				[
					'property' => 'font',
					'selector' => '.wsf-form input[type=text].wsf-field ~ .dropzone .dz-message'
				],
			],
		];

		$this->controls['fileUploadBorder'] = [
			'tab'      => 'content',
			'group'    => 'file_upload_group',
			'label'    => esc_html__( 'Border', 'extras' ),
			'type'     => 'border',
			'css'      => [
				[
					'property' => 'border',
					'selector' => '.wsf-form input[type=text].wsf-field ~ .dropzone'
				],
			],
		];

		$this->controls['fileUploadBackground'] = [
			'tab'      => 'content',
			'group'    => 'file_upload_group',
			'label'    => esc_html__( 'Background color', 'extras' ),
			'type'     => 'color',
			'css'      => [
				[
					'property' => 'background-color',
					'selector' => '.wsf-form input[type=text].wsf-field ~ .dropzone'
				],
			],
		];

		$this->controls['fileUploadPadding'] = [
			'tab'      => 'content',
			'group'    => 'file_upload_group',
			'label'    => esc_html__( 'Padding', 'extras' ),
			'type'     => 'dimensions',
			'css'      => [
				[
					'property' => 'padding',
					'selector' => '.wsf-form input[type=text].wsf-field ~ .dropzone'
				],
			],
		];

		

		



		/* messages */

		$this->controls['successMessageSep'] = [
			'tab'      => 'content',
			'group'    => 'messages_group',
			'label'    => esc_html__( 'Success Message', 'extras' ),
			'type'     => 'separator',
		];

		$this->controls['successMessageBackground'] = [
			'tab'      => 'content',
			'group'    => 'messages_group',
			'label'    => esc_html__( 'Background', 'extras' ),
			'type'     => 'color',
			'css'      => [
				[
					'property' => 'background-color',
					'selector' => 'div.wsf-alert.wsf-alert-success',
				],
			],
		];

		$this->controls['successMessageTypography'] = [
			'tab'      => 'content',
			'group'    => 'messages_group',
			'label'    => esc_html__( 'Typography', 'extras' ),
			'type'     => 'typography',
			'css'      => [
				[
					'property' => 'font',
					'selector' => 'div.wsf-alert.wsf-alert-success',
				],
			],
		];

		$this->controls['successMessageBorder'] = [
			'tab'      => 'content',
			'group'    => 'messages_group',
			'label'    => esc_html__( 'Border', 'extras' ),
			'type'     => 'border',
			'css'      => [
				[
					'property' => 'border',
					'selector' => 'div.wsf-alert.wsf-alert-success',
				],
			],
		];

		$this->controls['successMessageShadow'] = [
			'tab'      => 'content',
			'group'    => 'messages_group',
			'label'    => esc_html__( 'Box Shadow', 'extras' ),
			'type'     => 'box-shadow',
			'css'      => [
				[
					'property' => 'box-shadow',
					'selector' => 'div.wsf-alert.wsf-alert-success',
				],
			],
		];


		$this->controls['informationMessageSep'] = [
			'tab'      => 'content',
			'group'    => 'messages_group',
			'label'    => esc_html__( 'information Message', 'extras' ),
			'type'     => 'separator',
		];

		$this->controls['informationMessageBackground'] = [
			'tab'      => 'content',
			'group'    => 'messages_group',
			'label'    => esc_html__( 'Background', 'extras' ),
			'type'     => 'color',
			'css'      => [
				[
					'property' => 'background-color',
					'selector' => '.wsf-form .wsf-alert.wsf-alert-information',
				],
			],
		];

		$this->controls['informationMessageTypography'] = [
			'tab'      => 'content',
			'group'    => 'messages_group',
			'label'    => esc_html__( 'Typography', 'extras' ),
			'type'     => 'typography',
			'css'      => [
				[
					'property' => 'font',
					'selector' => '.wsf-form .wsf-alert.wsf-alert-information',
				],
			],
		];

		$this->controls['informationMessageBorder'] = [
			'tab'      => 'content',
			'group'    => 'messages_group',
			'label'    => esc_html__( 'Border', 'extras' ),
			'type'     => 'border',
			'css'      => [
				[
					'property' => 'border',
					'selector' => '.wsf-form .wsf-alert.wsf-alert-information',
				],
			],
		];

		$this->controls['informationMessageShadow'] = [
			'tab'      => 'content',
			'group'    => 'messages_group',
			'label'    => esc_html__( 'Box Shadow', 'extras' ),
			'type'     => 'box-shadow',
			'css'      => [
				[
					'property' => 'box-shadow',
					'selector' => '.wsf-form .wsf-alert.wsf-alert-information',
				],
			],
		];

		$this->controls['warningMessageSep'] = [
			'tab'      => 'content',
			'group'    => 'messages_group',
			'label'    => esc_html__( 'warning Message', 'extras' ),
			'type'     => 'separator',
		];

		$this->controls['warningMessageBackground'] = [
			'tab'      => 'content',
			'group'    => 'messages_group',
			'label'    => esc_html__( 'Background', 'extras' ),
			'type'     => 'color',
			'css'      => [
				[
					'property' => 'background-color',
					'selector' => '.wsf-form .wsf-alert.wsf-alert-warning',
				],
			],
		];

		$this->controls['warningMessageTypography'] = [
			'tab'      => 'content',
			'group'    => 'messages_group',
			'label'    => esc_html__( 'Typography', 'extras' ),
			'type'     => 'typography',
			'css'      => [
				[
					'property' => 'font',
					'selector' => '.wsf-form .wsf-alert.wsf-alert-warning',
				],
			],
		];

		$this->controls['warningMessageBorder'] = [
			'tab'      => 'content',
			'group'    => 'messages_group',
			'label'    => esc_html__( 'Border', 'extras' ),
			'type'     => 'border',
			'css'      => [
				[
					'property' => 'border',
					'selector' => '.wsf-form .wsf-alert.wsf-alert-warning',
				],
			],
		];

		$this->controls['warningMessageShadow'] = [
			'tab'      => 'content',
			'group'    => 'messages_group',
			'label'    => esc_html__( 'Box Shadow', 'extras' ),
			'type'     => 'box-shadow',
			'css'      => [
				[
					'property' => 'box-shadow',
					'selector' => '.wsf-form .wsf-alert.wsf-alert-warning',
				],
			],
		];


		$this->controls['dangerMessageSep'] = [
			'tab'      => 'content',
			'group'    => 'messages_group',
			'label'    => esc_html__( 'danger Message', 'extras' ),
			'type'     => 'separator',
		];

		$this->controls['dangerMessageBackground'] = [
			'tab'      => 'content',
			'group'    => 'messages_group',
			'label'    => esc_html__( 'Background', 'extras' ),
			'type'     => 'color',
			'css'      => [
				[
					'property' => 'background-color',
					'selector' => '.wsf-form .wsf-alert.wsf-alert-danger',
				],
			],
		];

		$this->controls['dangerMessageTypography'] = [
			'tab'      => 'content',
			'group'    => 'messages_group',
			'label'    => esc_html__( 'Typography', 'extras' ),
			'type'     => 'typography',
			'css'      => [
				[
					'property' => 'font',
					'selector' => '.wsf-form .wsf-alert.wsf-alert-danger',
				],
			],
		];

		$this->controls['dangerMessageBorder'] = [
			'tab'      => 'content',
			'group'    => 'messages_group',
			'label'    => esc_html__( 'Border', 'extras' ),
			'type'     => 'border',
			'css'      => [
				[
					'property' => 'border',
					'selector' => '.wsf-form .wsf-alert.wsf-alert-danger',
				],
			],
		];

		$this->controls['dangerMessageShadow'] = [
			'tab'      => 'content',
			'group'    => 'messages_group',
			'label'    => esc_html__( 'Box Shadow', 'extras' ),
			'type'     => 'box-shadow',
			'css'      => [
				[
					'property' => 'box-shadow',
					'selector' => '.wsf-form .wsf-alert.wsf-alert-danger',
				],
			],
		];



		/* repeatable repeatable_group */

		$this->controls['addStyle_start'] = [
			'tab'   => 'content',
			'group'  => 'repeatable_group',
			'type'  => 'separator',
			'label'  => esc_html__( 'Add Button', 'extras' ),
		];

		  $addButton = '.wsf-form button.wsf-button[data-action="wsf-section-add-button"]';
  
		  $this->controls['addTypography'] = [
			'tab'    => 'content',
			'group'  => 'repeatable_group',
			'type'   => 'typography',
			'label'  => esc_html__( 'Typography', 'extras' ),
			'css'    => [
				[
					'property' => 'font',
					'selector' => $addButton
				],
			],
		];
  
		  $this->controls['addBackgroundColor'] = [
			  'tab'    => 'content',
			  'group'  => 'repeatable_group',
			  'type'   => 'color',
			  'label'  => esc_html__( 'Background', 'extras' ),
			  'css'    => [
				  [
					  'property' => 'background-color',
					  'selector' => $addButton,
				  ],
			  ],
		  ];
  
		  $this->controls['addBorder'] = [
			  'tab'    => 'content',
			  'group'  => 'repeatable_group',
			  'type'   => 'border',
			  'label'  => esc_html__( 'Border', 'extras' ),
			  'css'    => [
				  [
					  'property' => 'border',
					  'selector' => $addButton
				  ],
			  ],
		  ];
  
		  $this->controls['addBoxShadow'] = [
			  'tab'    => 'content',
			  'group'  => 'repeatable_group',
			  'label'  => esc_html__( 'Box Shadow', 'extras' ),
			  'type'   => 'box-shadow',
			  'css'    => [
				  [
					  'property' => 'box-shadow',
					  'selector' => $addButton,
				  ],
			  ],
		  ];

		  $this->controls['removeStyle_start'] = [
			'tab'   => 'content',
			'group'  => 'repeatable_group',
			'type'  => 'separator',
			'label'  => esc_html__( 'remove Button', 'extras' ),
		];

		  $removeButton = '.wsf-form button.wsf-button[data-action="wsf-section-delete-button"]';
  
		  $this->controls['removeTypography'] = [
			'tab'    => 'content',
			'group'  => 'repeatable_group',
			'type'   => 'typography',
			'label'  => esc_html__( 'Typography', 'extras' ),
			'css'    => [
				[
					'property' => 'font',
					'selector' => $removeButton
				],
			],
		];
  
		  $this->controls['removeBackgroundColor'] = [
			  'tab'    => 'content',
			  'group'  => 'repeatable_group',
			  'type'   => 'color',
			  'label'  => esc_html__( 'Background', 'extras' ),
			  'css'    => [
				  [
					  'property' => 'background-color',
					  'selector' => $removeButton,
				  ],
			  ],
		  ];
  
		  $this->controls['removeBorder'] = [
			  'tab'    => 'content',
			  'group'  => 'repeatable_group',
			  'type'   => 'border',
			  'label'  => esc_html__( 'Border', 'extras' ),
			  'css'    => [
				  [
					  'property' => 'border',
					  'selector' => $removeButton
				  ],
			  ],
		  ];
  
		  $this->controls['removeBoxShadow'] = [
			  'tab'    => 'content',
			  'group'  => 'repeatable_group',
			  'label'  => esc_html__( 'Box Shadow', 'extras' ),
			  'type'   => 'box-shadow',
			  'css'    => [
				  [
					  'property' => 'box-shadow',
					  'selector' => $removeButton,
				  ],
			  ],
		  ];


		  $this->controls['iconsStyle_start'] = [
			'tab'   => 'content',
			'group'  => 'repeatable_group',
			'type'  => 'separator',
			'label'  => esc_html__( 'Repeatable Section Icons', 'extras' ),
		];

		$this->controls['repeatableIconSize'] = [
			'tab'    => 'content',
			'group'  => 'repeatable_group',
			'label'  => esc_html__( 'Icons size', 'extras' ),
			'type'   => 'number',
			'units'   => true,
			'placeholder' => '24px',
			'css'    => [
				[
					'property' => 'height',
					'selector' => '.wsf-form .wsf-section-icon',
				],
				[
					'property' => 'width',
					'selector' => '.wsf-form .wsf-section-icon',
				],
			],
		];

		$this->controls['repeatableIconGap'] = [
			'tab'    => 'content',
			'group'  => 'repeatable_group',
			'label'  => esc_html__( 'Gap', 'extras' ),
			'type'   => 'number',
			'units'   => true,
			'placeholder' => '5px',
			'css'    => [
				[
					'property' => ' -webkit-margin-end',
					'selector' => '.wsf-form .wsf-section-icons > a',
					'important' => true
				],
				[
					'property' => 'margin-inline-end',
					'selector' => '.wsf-form .wsf-section-icons > a',
					'important' => true
				],
			],
		];

		/*

		$this->controls['repeatableAddIconColor'] = [
			'tab'    => 'content',
			'group'  => 'repeatable_group',
			'label'  => esc_html__( 'Add icon color', 'extras' ),
			'type'   => 'color',
			'css'    => [
				[
					'property' => 'fill',
					'selector' => '.wsf-form .wsf-section-add .wsf-section-icon path',
				],
			],
		];

		$this->controls['repeatableDeleteIconColor'] = [
			'tab'    => 'content',
			'group'  => 'repeatable_group',
			'label'  => esc_html__( 'Delete icon color', 'extras' ),
			'type'   => 'color',
			'css'    => [
				[
					'property' => 'fill',
					'selector' => '.wsf-form .wsf-section-delete .wsf-section-icon path',
				],
			],
		];

		$this->controls['repeatableMoveUpIconColor'] = [
			'tab'    => 'content',
			'group'  => 'repeatable_group',
			'label'  => esc_html__( 'Move Up icon color', 'extras' ),
			'type'   => 'color',
			'css'    => [
				[
					'property' => 'fill',
					'selector' => '.wsf-form .wsf-section-move-up .wsf-section-icon path',
				],
			],
		];

		$this->controls['repeatableMoveDownIconColor'] = [
			'tab'    => 'content',
			'group'  => 'repeatable_group',
			'label'  => esc_html__( 'Move Down icon color', 'extras' ),
			'type'   => 'color',
			'css'    => [
				[
					'property' => 'fill',
					'selector' => '.wsf-form .wsf-section-move-down .wsf-section-icon path',
				],
			],
		];

		$this->controls['repeatableDragIconColor'] = [
			'tab'    => 'content',
			'group'  => 'repeatable_group',
			'label'  => esc_html__( 'Drag icon color', 'extras' ),
			'type'   => 'color',
			'css'    => [
				[
					'property' => 'fill',
					'selector' => '.wsf-form .wsf-section-drag .wsf-section-icon path',
				],
			],
		];

		$this->controls['repeatableClearIconColor'] = [
			'tab'    => 'content',
			'group'  => 'repeatable_group',
			'label'  => esc_html__( 'Clear icon color', 'extras' ),
			'type'   => 'color',
			'css'    => [
				[
					'property' => 'fill',
					'selector' => '.wsf-form .wsf-section-clear .wsf-section-icon path',
				],
			],
		];

		$this->controls['repeatableResetIconColor'] = [
			'tab'    => 'content',
			'group'  => 'repeatable_group',
			'label'  => esc_html__( 'Reset icon color', 'extras' ),
			'type'   => 'color',
			'css'    => [
				[
					'property' => 'fill',
					'selector' => '.wsf-form .wsf-section-reset .wsf-section-icon path',
				],
			],
		];

		*/

		/* password  */

		$this->controls['passwordWrapperBackground'] = [
			'tab'      => 'content',
			'group'    => 'password_group',
			'label'    => esc_html__( 'Icon wrapper background', 'extras' ),
			'type'     => 'color',
			'css'      => [
				[
					'property' => 'background-color',
					'selector' => '.wsf-form .wsf-field-wrapper[data-type=password] .wsf-input-group-append',
				],
			],
		];

		$this->controls['passwordWrapperPadding'] = [
			'tab'      => 'content',
			'group'    => 'password_group',
			'label'    => esc_html__( 'Icon wrapper padding', 'extras' ),
			'type'     => 'dimensions',
			'css'      => [
				[
					'property' => 'padding',
					'selector' => '.wsf-form .wsf-field-wrapper[data-type=password] .wsf-input-group-append',
				],
			],
		];

		$this->controls['passwordGeneratePasswordSep'] = [
			'tab'      => 'content',
			'group'    => 'password_group',
			'label'    => esc_html__( 'Generate Password Icon', 'extras' ),
			'type'     => 'separator',
		];

		$this->controls['passwordGeneratePasswordIconColor'] = [
			'tab'      => 'content',
			'group'    => 'password_group',
			'label'    => esc_html__( 'Icon color', 'extras' ),
			'type'     => 'color',
			'css'      => [
				[
					'property' => 'fill',
					'selector' => '.wsf-form .wsf-field-wrapper[data-type=password] [data-wsf-password-generate] svg',
				],
			],
		];

		$this->controls['passwordGeneratePasswordIconSize'] = [
			'tab'      => 'content',
			'group'    => 'password_group',
			'label'    => esc_html__( 'Icon size', 'extras' ),
			'type'     => 'number',
			'units'     => true,
			'css'      => [
				[
					'property' => 'height',
					'selector' => '.wsf-form .wsf-field-wrapper[data-type=password] [data-wsf-password-generate] svg',
				],
				[
					'property' => 'width',
					'selector' => '.wsf-form .wsf-field-wrapper[data-type=password] [data-wsf-password-generate] svg',
				],
			],
		];

		$this->controls['passwordVisibilityPasswordSep'] = [
			'tab'      => 'content',
			'group'    => 'password_group',
			'label'    => esc_html__( 'Password Visibilty Icon', 'extras' ),
			'type'     => 'separator',
		];

		$this->controls['passwordVisibilityPasswordIconColor'] = [
			'tab'      => 'content',
			'group'    => 'password_group',
			'label'    => esc_html__( 'Icon color', 'extras' ),
			'type'     => 'color',
			'css'      => [
				[
					'property' => 'fill',
					'selector' => '.wsf-form .wsf-field-wrapper[data-type=password] [data-wsf-password-visibility-toggle] svg',
				],
			],
		];

		$this->controls['passwordVisibilityPasswordIconSize'] = [
			'tab'      => 'content',
			'group'    => 'password_group',
			'label'    => esc_html__( 'Icon size', 'extras' ),
			'type'     => 'number',
			'units'     => true,
			'css'      => [
				[
					'property' => 'height',
					'selector' => '.wsf-form .wsf-field-wrapper[data-type=password] [data-wsf-password-visibility-toggle] svg',
				],
				[
					'property' => 'width',
					'selector' => '.wsf-form .wsf-field-wrapper[data-type=password] [data-wsf-password-visibility-toggle] svg',
				],
			],
		];



		/* legal  */

		$this->controls['legalTypography'] = [
			'tab'      => 'content',
			'group'    => 'legal_group',
			'label'    => esc_html__( 'Typography', 'extras' ),
			'type'     => 'typography',
			'css'      => [
				[
					'property' => 'font',
					'selector' => '.wsf-form [data-wsf-legal].wsf-field',
				],
			],
		];

		$this->controls['legalBackground'] = [
			'tab'      => 'content',
			'group'    => 'legal_group',
			'label'    => esc_html__( 'Background', 'extras' ),
			'type'     => 'background',
			'css'      => [
				[
					'property' => 'background',
					'selector' => '.wsf-form [data-wsf-legal].wsf-field',
				],
			],
		];

		$this->controls['legalBorder'] = [
			'tab'      => 'content',
			'group'    => 'legal_group',
			'label'    => esc_html__( 'Border', 'extras' ),
			'type'     => 'border',
			'css'      => [
				[
					'property' => 'border',
					'selector' => '.wsf-form [data-wsf-legal].wsf-field',
				],
			],
		];

		$this->controls['legalPadding'] = [
			'tab'      => 'content',
			'group'    => 'legal_group',
			'label'    => esc_html__( 'Padding', 'extras' ),
			'type'     => 'dimensions',
			'css'      => [
				[
					'property' => 'padding',
					'selector' => '.wsf-form [data-wsf-legal].wsf-field',
				],
			],
		];



		/* star rating */

		$this->controls['starColor'] = [
			'tab'      => 'content',
			'group'    => 'star_rating_group',
			'label'    => esc_html__( 'Color', 'extras' ),
			'type'     => 'color',
			'css'      => [
				[
					'property' => 'fill',
					'selector' => 'ul.wsf-rating li:not(.wsf-rating-selected) path',
					'important' => true
				],
			],
		];

		$this->controls['starSelectedColor'] = [
			'tab'      => 'content',
			'group'    => 'star_rating_group',
			'label'    => esc_html__( 'Selected Color', 'extras' ),
			'type'     => 'color',
			'css'      => [
				[
					'property' => 'fill',
					'selector' => 'ul.wsf-rating li.wsf-rating-selected path',
					'important' => true
				],
			],
		];

		$this->controls['starGap'] = [
			'tab'      => 'content',
			'group'    => 'star_rating_group',
			'label'    => esc_html__( 'Gap', 'extras' ),
			'type'     => 'number',
			'units'     => true,
			'css'      => [
				[
					'property' => 'gap',
					'selector' => 'ul.wsf-rating',
				],
			],
		];

		$this->controls['starSize'] = [
			'tab'      => 'content',
			'group'    => 'star_rating_group',
			'label'    => esc_html__( 'Star size', 'extras' ),
			'placeholder' => '24px',
			'type'     => 'number',
			'units'     => true,
			'css'      => [
				[
					'property' => 'height',
					'selector' => 'ul.wsf-rating svg',
				],
				[
					'property' => 'width',
					'selector' => 'ul.wsf-rating svg',
				],
			],
		];


  }

  public function enqueue_scripts() {
    if (! \BricksExtras\Helpers::elementCSSAdded($this->name) ) {
      wp_enqueue_style( 'x-ws-forms', BRICKSEXTRAS_URL . 'components/assets/css/wsforms.css', [], '' );
    }
  }

  
  public function render() {

			if ( !defined( 'WS_FORM_NAME' ) ) {
				return $this->render_element_placeholder( [ 
				'title' => esc_html__( "WS Form isn't active.", 'bricksextras' )
				] );
			}

			$settings = $this->settings;

			if ( !isset( $settings['formSource'] ) ) {
				$formID = isset( $settings['formSelect'] ) ? $settings['formSelect'] : 0;
			} else {
				$formID = strstr( $settings['formID'], '{') ? $this->render_dynamic_data_tag( $settings['formID'], 'text' ) : $settings['formID'];
			}

			if ( method_exists('\Bricks\Query','is_any_looping') ) {

				$query_id = \Bricks\Query::is_any_looping();
		
				if ( $query_id ) {
					
					if ( BricksExtras\Helpers::get_bricks_looping_parent_query_id_by_level(2) ) {
						$loopIndex = \Bricks\Query::get_query_for_element_id( \Bricks\Query::get_query_element_id( BricksExtras\Helpers::get_bricks_looping_parent_query_id_by_level(2) ) )->loop_index . '_' . \Bricks\Query::get_query_for_element_id( \Bricks\Query::get_query_element_id( BricksExtras\Helpers::get_bricks_looping_parent_query_id_by_level(1) ) )->loop_index . '_' . \Bricks\Query::get_loop_index();
					} else {
						if ( BricksExtras\Helpers::get_bricks_looping_parent_query_id_by_level(1) ) {
							$loopIndex = \Bricks\Query::get_query_for_element_id( \Bricks\Query::get_query_element_id( BricksExtras\Helpers::get_bricks_looping_parent_query_id_by_level(1) ) )->loop_index . '_' . \Bricks\Query::get_loop_index();
						} else {
							$loopIndex = \Bricks\Query::get_loop_index();
						}
					}			
		
					$this->set_attribute( '_root', 'data-x-wsf', $this->id . '_' . $loopIndex );
					
				} else {
					$this->set_attribute( '_root', 'data-x-wsf', $this->id );
				}
		
			} 

			if( $formID > 0 ) {

				echo "<div {$this->render_attributes('_root')}>";

					$bricks_iframe = (
						(function_exists('bricks_is_builder_preview') && bricks_is_builder_preview()) ||
						(function_exists('bricks_is_builder_iframe') && bricks_is_builder_iframe()) ||
						(function_exists('bricks_is_builder_call') && bricks_is_builder_call())
					);

					$visualBuilder = ( $bricks_iframe || bricks_is_ajax_call() ) ? ' visual_builder="true"' : '';

					$formShortcode = '[ws_form id="' .  esc_attr( $formID ) . '" element_id="' . esc_attr( $formID ) . $visualBuilder . ']';

					echo do_shortcode($formShortcode);

				echo '</div>';

		    } else {

				echo $this->render_element_placeholder([	

					'icon-class'	=> $this->icon,
					'title'			=> esc_html__('No form selected', 'ws-form'),
					'description'	=> esc_html__('Please select a form from the element controls.', 'ws-form'),

					// Legacy attribute
					'text'			=> esc_html__('No form selected', 'ws-form')
				]);

			}

  }

}
