<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class X_Fluent_Form extends \Bricks\Element {

  // Element properties
  public $category     = 'extras';
	public $name         = 'xfluentform';
	public $icon         = 'ti-layout-media-overlay-alt-2';
	public $css_selector = '';
	//public $scripts      = ['xFluentForm'];
  
  public function get_label() {
	  return esc_html__( 'Fluent Form', 'extras' );
  }


  public function set_control_groups() {

    $this->control_groups['spacing_group'] = [
			'title' => esc_html__( 'Form spacing', 'extras' ),
			'tab'   => 'content',
		];

		$this->control_groups['wrapper_group'] = [
			'title' => esc_html__( 'Form Wrapper', 'extras' ),
			'tab'   => 'content',
		];	

    $this->control_groups['inputs_group'] = [
			'title' => esc_html__( 'Inputs / Labels', 'extras' ),
			'tab'   => 'content',
		];

    $this->control_groups['submit_group'] = [
			'title' => esc_html__( 'Submit', 'extras' ),
			'tab'   => 'content',
		];

		$this->control_groups['checkbox_group'] = [
			'title' => esc_html__( 'Checkbox / Radio', 'extras' ),
			'tab'   => 'content',
		];

		$this->control_groups['gdpr_group'] = [
			'title' => esc_html__( 'GDPR / T&Cs', 'extras' ),
			'tab'   => 'content',
		];

    $this->control_groups['checkable_group'] = [
			'title' => esc_html__( 'Checkable Grid', 'extras' ),
			'tab'   => 'content',
		];

    $this->control_groups['upload_group'] = [
			'title' => esc_html__( 'File Upload Button', 'extras' ),
			'tab'   => 'content',
		];

    $this->control_groups['progress_group'] = [
			'title' => esc_html__( 'Progress Bar', 'extras' ),
			'tab'   => 'content',
		];

	$this->control_groups['progress_steps_group'] = [
		'title' => esc_html__( 'Progress Steps', 'extras' ),
		'tab'   => 'content',
	];	

    $this->control_groups['step_group'] = [
			'title' => esc_html__( 'Step Button', 'extras' ),
			'tab'   => 'content',
		];

    $this->control_groups['netpromoter_group'] = [
			'title' => esc_html__( 'Net Promotor Score', 'extras' ),
			'tab'   => 'content',
		];

	$this->control_groups['repeater_group'] = [
		'title' => esc_html__( 'Repeater', 'extras' ),
		'tab'   => 'content',
	];	

    $this->control_groups['success_group'] = [
			'title' => esc_html__( 'Success / Validation Errors', 'extras' ),
			'tab'   => 'content',
		];

    


  }

  public function set_controls() {

     /**
     * Form ID
     */
    
    $dropdown_options = [];
    
    if (function_exists('wpFluent') ) {
    
        $fluentForms = wpFluent()->table( 'fluentform_forms' )
        ->select( ['id', 'title'] )
        ->orderBy( 'id', 'DESC' )
        ->get();
        
        foreach ( $fluentForms as $fluentform ) {
              $dropdown_options[$fluentform->id] = str_replace('\'', '', str_replace(' ', '&#8205; ', $fluentform->title));
        }
        
    } else {
        $dropdown_options['noforms'] = esc_attr('No fluent forms');
    }

    $this->controls['formSource'] = [
			'tab'         => 'content',
			'label'       => esc_html__( 'Add form ID dynamically', 'bricks' ),
			'type'        => 'checkbox',
			'inline'      => true,
		];

    $this->controls['fluentFormSelect'] = [
			'tab'         => 'content',
			'label'       => esc_html__( 'Select form', 'bricks' ),
			'type'        => 'select',
			'options'     => $dropdown_options,
			'placeholder' => esc_html__( 'Select form', 'bricks' ),
			'default' => '1',
			'required' => ['formSource', '!=', true],
		];

    $this->controls['fluentFormID'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Form ID', 'bricks' ),
			'type' => 'text',
     		 'required' => ['formSource', '=', true],
		];

    /* selectors */

    $group = '.fluentform .ff-el-group:not(form > *:last-child)';
    $column_cell = '.fluentform .ff-t-cell';

    
    /* spacing */

    $this->controls['groupMargin'] = [
			'tab'   => 'content',
			'group' => 'spacing_group',
			'label' => esc_html__( 'Vertical gap', 'extras' ),
			'type' => 'number',
			'placeholder' => '20',
			'units'    => true,
			'css'   => [
				[
					'property' => 'margin-bottom',
					'selector' => $group,
				],
			],
		];

    $this->controls['cellColumnGap'] = [
			'tab'   => 'content',
			'group' => 'spacing_group',
			'label' => esc_html__( 'Column gap', 'extras' ),
			'type' => 'number',
			'placeholder' => '15',
			'units'    => true,
			'css'   => [
				[
					'property' => '--x-ff-gap',
					'selector' => '.frm-fluent-form .ff-t-container',
				],
			],
		];


	$this->controls['wrapperBackgroundColor'] = [
		'tab'    => 'content',
		'group'  => 'wrapper_group',
		'type'   => 'color',
		'label'  => esc_html__( 'Background', 'extras' ),
		'css'    => [
			[
				'property' => 'background-color',
				'selector' => 'form',
			],
		],
	];

	$this->controls['wrapperBorder'] = [
		'tab'    => 'content',
		'group'  => 'wrapper_group',
		'type'   => 'border',
		'label'  => esc_html__( 'Border', 'extras' ),
		'css'    => [
			[
				'property' => 'border',
				'selector' => 'form',
			],
		],
	];

	$this->controls['wrapperBoxShadow'] = [
		'tab'    => 'content',
		'group'  => 'wrapper_group',
		'label'  => esc_html__( 'Box Shadow', 'extras' ),
		'type'   => 'box-shadow',
		'css'    => [
			[
				'property' => 'box-shadow',
				'selector' => 'form',
			],
		],
	];

	$this->controls['wrapperPadding'] = [
		'tab'   => 'content',
		'group' => 'wrapper_group',
		'label' => esc_html__( 'Padding', 'extras' ),
		'type'  => 'dimensions',
		'css'   => [
			[
				'property' => 'padding',
				'selector' => 'form',
			],
		],
	];


    /* inputs / labels */

    $this->controls['inputTypography_start'] = [
      'tab'   => 'content',
      'group'  => 'inputs_group',
      'type'  => 'separator',
      'description' => esc_html__( 'Typography', 'extras' ),
    ];

    $this->controls['inputTypography'] = [
			'tab'    => 'content',
			'group'  => 'inputs_group',
			'type'   => 'typography',
			'label'  => esc_html__( 'Field text', 'extras' ),
			'css'    => [
				[
					'property' => 'font',
					'selector' => '.fluentform .ff-el-group input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file])',
				],
				[
					'property' => 'font',
					'selector' => '.fluentform .ff-el-group textarea',
				],
				[
					'property' => 'font',
					'selector' => '.fluentform .ff-el-group select',
				],
			],
		];

    $this->controls['placeholderTypography'] = [
			'tab'    => 'content',
			'group'  => 'inputs_group',
			'type'   => 'typography',
			'label'  => esc_html__( 'Placeholder', 'extras' ),
			'css'    => [
				[
					'property' => 'font',
					'selector' => '.fluentform .ff-el-group input::-webkit-input-placeholder',
				],
				[
					'property' => 'font',
					'selector' => '.fluentform .ff-el-group textarea::-webkit-input-placeholder',
				],
			],
		];

    $this->controls['labelsTypography'] = [
			'tab'    => 'content',
			'group'  => 'inputs_group',
			'type'   => 'typography',
			'label'  => esc_html__( 'Labels', 'extras' ),
			'css'    => [
				[
					'property' => 'font',
					'selector' => '.fluentform .ff-el-input--label label',
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
					'selector' => '.fluentform .ff-el-input--label.ff-el-is-required.asterisk-right label:after',
				],
			],
		];

		$this->controls['inputStyle_start'] = [
      'tab'   => 'content',
      'group'  => 'inputs_group',
      'type'  => 'separator',
    ];

		

		$this->controls['inputBackgroundColor'] = [
			'tab'    => 'content',
			'group'  => 'inputs_group',
			'type'   => 'color',
			'label'  => esc_html__( 'Background', 'extras' ),
			'css'    => [
				[
					'property' => 'background-color',
					'selector' => '.fluentform .ff-el-group input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file])',
				],
				[
					'property' => 'background-color',
					'selector' => '.fluentform .ff-el-group textarea',
				],
				[
					'property' => 'background-color',
					'selector' => '.fluentform .ff-el-group select',
				],
			],
		];

		$this->controls['inputBorder'] = [
			'tab'    => 'content',
			'group'  => 'inputs_group',
			'type'   => 'border',
			'label'  => esc_html__( 'Border', 'extras' ),
			'css'    => [
				[
					'property' => 'border',
					'selector' => '.fluentform .ff-el-group input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file])',
				],
				[
					'property' => 'border',
					'selector' => '.fluentform .ff-el-group textarea',
				],
				[
					'property' => 'border',
					'selector' => '.fluentform .ff-el-group select',
				],
			],
		];

		$this->controls['inputBoxShadow'] = [
			'tab'    => 'content',
			'group'  => 'inputs_group',
			'label'  => esc_html__( 'Box Shadow', 'extras' ),
			'type'   => 'box-shadow',
			'css'    => [
				[
					'property' => 'box-shadow',
					'selector' => '.fluentform .ff-el-group input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file])',
				],
				[
					'property' => 'box-shadow',
					'selector' => '.fluentform .ff-el-group textarea',
				],
				[
					'property' => 'box-shadow',
					'selector' => '.fluentform .ff-el-group select',
				],
			],
		];

    $this->controls['inputStyle_end'] = [
      'tab'   => 'content',
      'group'  => 'inputs_group',
      'type'  => 'separator',
    ];

		$this->controls['inputPadding'] = [
			'tab'   => 'content',
			'group' => 'inputs_group',
			'label' => esc_html__( 'Padding', 'extras' ),
			'type'  => 'dimensions',
			'css'   => [
				[
					'property' => 'padding',
					'selector' => '.fluentform .ff-el-group input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file])',
				],
				[
					'property' => 'padding',
					'selector' => '.fluentform .ff-el-group textarea',
				],
				[
					'property' => 'padding',
					'selector' => '.fluentform .ff-el-group select',
				],
			],
		];
    



     /* submit */

     $submit = '.fluentform .ff-btn-submit';

    $this->controls['submitTypography'] = [
			'tab'    => 'content',
			'group'  => 'submit_group',
			'type'   => 'typography',
			'label'  => esc_html__( 'Typography', 'extras' ),
			'css'    => [
				[
					'property' => 'font',
					'selector' => $submit
				],
			],
		];

		$this->controls['submitStyle_start'] = [
			'tab'   => 'content',
			'group'  => 'submit_group',
			'type'  => 'separator',
		];
		

		$this->controls['submitBackgroundColor'] = [
			'tab'    => 'content',
			'group'  => 'submit_group',
			'type'   => 'color',
			'label'  => esc_html__( 'Background', 'extras' ),
			'css'    => [
				[
					'property' => 'background-color',
					'selector' => $submit,
				],
				[
					'property' => 'background-color',
					'selector' => '.fluentform form .ff-btn-submit:not(.ff_btn_no_style)',
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
					'selector' => $submit
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
					'selector' => $submit,
				],
			],
		];

    $this->controls['submitStyle_end'] = [
      'tab'   => 'content',
      'group'  => 'submit_group',
      'type'  => 'separator',
    ];

		$this->controls['submitPadding'] = [
			'tab'   => 'content',
			'group' => 'submit_group',
			'label' => esc_html__( 'Padding', 'extras' ),
			'type'  => 'dimensions',
			'css'   => [
				[
					'property' => 'padding',
					'selector' => $submit,
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
					'selector' => $submit,
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
					'selector' => '.fluentform .ff_submit_btn_wrapper',
				],
			],
			'inline'      => true,
			'placeholder' => 'left',
		);

	$this->controls['submitHoverOpacity'] = [
		'tab'   => 'content',
		'group' => 'submit_group',
		'label' => esc_html__( 'Hover/focus opacity', 'extras' ),
		'type' => 'number',
		'css'   => [
			[
				'property' => 'opacity',
				'selector' => '.fluentform.ff-default .ff-btn-submit:focus',
			],
			[
				'property' => 'opacity',
				'selector' => '.fluentform.ff-default .ff-btn-submit:hover',
			],
		],
		'placeholder' => '0.8',
	];

    /* checkbox */


    $checkbox_input = '&[data-x-fluent-form*=smartUI] input[type="checkbox"]';
    $radio_input = '&[data-x-fluent-form*=smartUI] input[type="radio"]';
	$checkbox_selected_input = '&[data-x-fluent-form*=smartUI] .ff_item_selected input[type="checkbox"]';
    $radio_selected_input = '&[data-x-fluent-form*=smartUI] .ff_item_selected input[type="radio"]';
    $checkbox_checked_input = '&[data-x-fluent-form*=smartUI] .ff-el-group .ff_item_selected input[type=checkbox]::after';
    $radio_checked_input = '&[data-x-fluent-form*=smartUI] .ff-el-group .ff_item_selected input[type=radio]::after';


	

	$this->controls['radioCheckboxTypography'] = [
		'tab'    => 'content',
		'group'  => 'checkbox_group',
		'type'   => 'typography',
		'label'  => esc_html__( 'Labels Typography', 'extras' ),
		'css'    => [
			[
				'property' => 'font',
				'selector' => '.ff-el-form-check-label',
			],
		],
	];

    $this->controls['smartUI'] = [
			'tab'   => 'content',
			'group' => 'checkbox_group',
			'label' => esc_html__( 'Custom checkbox styles', 'extras' ),
			'type'  => 'checkbox',
		];

		$this->controls['radioCheckboxSize'] = [
			'tab'      => 'content',
			'group'    => 'checkbox_group',
			'label'    => esc_html__( 'Size', 'extras' ),
			'type' => 'number',
			'placeholder' => '15px',
			'units'    => true,
			'css'      => [
				[
					'property'  => 'width',
					'selector'  => $checkbox_input,
					'important' => 'true',
				],
				[
					'property'  => 'width',
					'selector'  => $radio_input,
					'important' => 'true',
				],
				[
					'property' => 'height',
					'selector' => $checkbox_input,
				],
				[
					'property' => 'height',
					'selector' => $radio_input,
				],
			],
      		//'required' => [ 'smartUI', '!=', false ],
		];

		$this->controls['radioCheckboxPadding'] = [
			'tab'   => 'content',
			'group' => 'checkbox_group',
			'label' => esc_html__( 'Inner padding', 'extras' ),
			'type' => 'number',
			'units'    => true,
			'placeholder' => '3px',
			'info'    => esc_html__( 'More padding to make inner dot smaller', 'extras' ),
			'css'   => [
				[
					'property' => 'padding',
					'selector' => '&[data-x-fluent-form*=smartUI] .ff-el-group input[type=radio]',
				],
				[
					'property' => 'padding',
					'selector' => '&[data-x-fluent-form*=smartUI] .ff-el-group input[type=checkbox]',
				],
			],
		];

		$this->controls['radioCheckboxColor'] = [
			'tab'      => 'content',
			'group'    => 'checkbox_group',
			'label'    => esc_html__( 'Input background', 'extras' ),
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
      //'required' => [ 'smartUI', '!=', false ],
		];

		$this->controls['radioCheckboxColorChecked'] = [
			'tab'      => 'content',
			'group'    => 'checkbox_group',
			'label'    => esc_html__( 'Selected Color (Inner dot)', 'extras' ),
			'info'    => esc_html__( 'Color inside checkbox/radio when selected', 'extras' ),
			'type'     => 'color',
			'css'      => [
				[
					'property' => 'background',
					'selector' => $checkbox_checked_input
				],
				[
					'property' => 'background',
					'selector' => $radio_checked_input
				],
			],
      //'required' => [ 'smartUI', '!=', false ],
		];

		$this->controls['radioCheckboxColorSelected'] = [
			'tab'      => 'content',
			'group'    => 'checkbox_group',
			'label'    => esc_html__( 'Input background (selected)', 'extras' ),
			'type'     => 'color',
			'css'      => [
				[
					'property' => 'background',
					'selector' => $checkbox_selected_input,
				],
				[
					'property' => 'background',
					'selector' => $radio_selected_input,
				],
			],
      //'required' => [ 'smartUI', '!=', false ],
		];

		

		$this->controls['radioCheckboxColorSep'] = [
			'tab'   => 'content',
			'group'  => 'checkbox_group',
			'type'  => 'separator',
			'description'  => 'For changing border radius..',
			//'required' => [ 'smartUI', '!=', false ],
		  ];



		$this->controls['checkboxBorder'] = [
			'tab'      => 'content',
			'group'    => 'checkbox_group',
			'label'    => esc_html__( 'Checkbox Border', 'extras' ),
			'type'     => 'border',
			'inline'   => true,
			'small'    => true,
			'css'      => [
				[
					'property' => 'border',
					'selector' => $checkbox_input,
				],
				[
					'property' => 'border',
					'selector' => $checkbox_input . '::after',
				],
				
			],
      //'required' => [ 'smartUI', '!=', false ],
		];

		$this->controls['radioBorder'] = [
			'tab'      => 'content',
			'group'    => 'checkbox_group',
			'label'    => esc_html__( 'Radio Border', 'extras' ),
			'type'     => 'border',
			'inline'   => true,
			'small'    => true,
			'css'      => [
				[
					'property' => 'border',
					'selector' => $radio_input,
				],
			],
      //'required' => [ 'smartUI', '!=', false ],
		];

		$this->controls['checkboxBorderSelected'] = [
			'tab'      => 'content',
			'group'    => 'checkbox_group',
			'label'    => esc_html__( 'Checkbox Border (inner dot)', 'extras' ),
			'type'     => 'border',
			'info'    => esc_html__( 'Dot inside checkbox/radio input', 'extras' ),
			'inline'   => true,
			'small'    => true,
			'css'      => [
				[
					'property' => 'border',
					'selector' => $checkbox_checked_input,
				],
			],
      //'required' => [ 'smartUI', '!=', false ],
		];

		$this->controls['radioBorderSelected'] = [
			'tab'      => 'content',
			'group'    => 'checkbox_group',
			'label'    => esc_html__( 'Radio Border (inner dot)', 'extras' ),
			'info'    => esc_html__( 'Dot inside checkbox/radio input', 'extras' ),
			'type'     => 'border',
			'inline'   => true,
			'small'    => true,
			'css'      => [
				[
					'property' => 'border',
					'selector' => $radio_checked_input,
				],
			],
      //'required' => [ 'smartUI', '!=', false ],
		];


		$this->controls['checkboxBorderInputSelected'] = [
			'tab'      => 'content',
			'group'    => 'checkbox_group',
			'label'    => esc_html__( 'Checkbox Border (selected)', 'extras' ),
			'type'     => 'border',
			'inline'   => true,
			'small'    => true,
			'css'      => [
				[
					'property' => 'border',
					'selector' => $checkbox_selected_input,
				],
			],
      //'required' => [ 'smartUI', '!=', false ],
		];

		$this->controls['radioBorderInputSelected'] = [
			'tab'      => 'content',
			'group'    => 'checkbox_group',
			'label'    => esc_html__( 'Radio Border (selected)', 'extras' ),
			'type'     => 'border',
			'inline'   => true,
			'small'    => true,
			'css'      => [
				[
					'property' => 'border',
					'selector' => $radio_selected_input,
				],
			],
      //'required' => [ 'smartUI', '!=', false ],
		];


		

		

		$this->controls['checkbox_end'] = [
		'tab'   => 'content',
		'group'  => 'checkbox_group',
		'type'  => 'separator',
		//'required' => [ 'smartUI', '!=', false ],
		];

		$this->controls['checkboxMargin'] = [
			'tab'   => 'content',
			'group' => 'checkbox_group',
			'label' => esc_html__( 'Checkbox / Radio Margin', 'extras' ),
			'type'  => 'dimensions',
			'css'   => [
				[
					'property' => 'margin',
					'selector' => $checkbox_input,
				],
        		[
					'property' => 'margin',
					'selector' => $radio_input,
				],
			],
			'placeholder' => [
				'right' => '5px',
			  ],
		];

		$this->controls['checkboxItemsMargin'] = [
			'tab'   => 'content',
			'group' => 'checkbox_group',
			'label' => esc_html__( 'Items Margin', 'extras' ),
			'type'  => 'dimensions',
			'css'   => [
				[
					'property' => 'margin',
					'selector' => '.ff-default .ff-el-form-check label.ff-el-form-check-label',
				],
        		[
					'property' => 'margin',
					'selector' => '.ff-el-form-check label.ff-el-form-check-label',
				],
			],
			'placeholder' => [
				'bottom' => '7px',
			],
		];


    /* gdpr */

    $gdpr_selector = ".fluentform .ff-el-tc label";

    $this->controls['gdprTypography'] = [
			'tab'    => 'content',
			'group'  => 'gdpr_group',
			'type'   => 'typography',
			'label'  => esc_html__( 'Typography', 'extras' ),
			'css'    => [
				[
					'property' => 'font',
					'selector' => $gdpr_selector,
				],
			],
		];

    $this->controls['gdprLinkTypography'] = [
			'tab'    => 'content',
			'group'  => 'gdpr_group',
			'type'   => 'typography',
			'label'  => esc_html__( 'Link Typography', 'extras' ),
			'css'    => [
				[
					'property' => 'font',
					'selector' => $gdpr_selector . ' a',
				],
			],
		];

		$this->controls['gdpr_start'] = [
      'tab'   => 'content',
      'group'  => 'gdpr_group',
      'type'  => 'separator',
    ];

		$this->controls['gdprBackgroundColor'] = [
			'tab'    => 'content',
			'group'  => 'gdpr_group',
			'type'   => 'color',
			'label'  => esc_html__( 'Background', 'extras' ),
			'css'    => [
				[
					'property' => 'background-color',
					'selector' => $gdpr_selector,
				],
			],
		];

		$this->controls['gdprBorder'] = [
			'tab'    => 'content',
			'group'  => 'gdpr_group',
			'type'   => 'border',
			'label'  => esc_html__( 'Border', 'extras' ),
			'css'    => [
				[
					'property' => 'border',
					'selector' => $gdpr_selector,
				],
			],
		];

		$this->controls['gdprBoxShadow'] = [
			'tab'    => 'content',
			'group'  => 'gdpr_group',
			'label'  => esc_html__( 'Box Shadow', 'extras' ),
			'type'   => 'box-shadow',
			'css'    => [
				[
					'property' => 'box-shadow',
					'selector' => $gdpr_selector,
				],
			],
		];

    $this->controls['gdpr_end'] = [
      'tab'   => 'content',
      'group'  => 'gdpr_group',
      'type'  => 'separator',
    ];

		$this->controls['gdprPadding'] = [
			'tab'   => 'content',
			'group' => 'gdpr_group',
			'label' => esc_html__( 'Padding', 'extras' ),
			'type'  => 'dimensions',
			'css'   => [
				[
					'property' => 'padding',
					'selector' => $gdpr_selector,
				],
			],
		];


    /* checkable grid */

    $checkable_grid_selector = '.fluentform .ff-checkable-grids';

    $this->controls['gridTypography'] = [
			'tab'    => 'content',
			'group'  => 'checkable_group',
			'type'   => 'typography',
			'label'  => esc_html__( 'Typography', 'extras' ),
			'css'    => [
				[
					'property' => 'font',
					'selector' => $checkable_grid_selector,
				],
			],
		];

    $this->controls['tableHeadBackground'] = [
			'tab'    => 'content',
			'group'  => 'checkable_group',
			'type'   => 'color',
			'label'  => esc_html__( 'Table Head Background', 'extras' ),
			'css'    => [
				[
					'property' => 'background-color',
					'selector' => '.fluentform .ff-checkable-grids thead>tr>th',
				],
			],
		];

    $this->controls['tableHeadTypography'] = [
			'tab'    => 'content',
			'group'  => 'checkable_group',
			'type'   => 'typography',
			'label'  => esc_html__( 'Table Head Typography', 'extras' ),
			'css'    => [
				[
					'property' => 'font',
					'selector' => '.fluentform .ff-checkable-grids thead>tr>th',
				],
			],
		];

    $this->controls['tableAltBackground'] = [
			'tab'    => 'content',
			'group'  => 'checkable_group',
			'type'   => 'color',
			'label'  => esc_html__( 'Alt Background', 'extras' ),
			'css'    => [
				[
					'property' => 'background-color',
					'selector' => '.fluentform .ff-checkable-grids tbody>tr:nth-child(2n)>td',
				],
			],
		];

    $this->controls['tableAltBackground'] = [
			'tab'    => 'content',
			'group'  => 'checkable_group',
			'type'   => 'color',
			'label'  => esc_html__( 'Alt Text color', 'extras' ),
			'css'    => [
				[
					'property' => 'color',
					'selector' => '.fluentform .ff-checkable-grids tbody>tr:nth-child(2n)>td',
				],
			],
		];

    $this->controls['tableBorder'] = [
			'tab'    => 'content',
			'group'  => 'checkable_group',
			'type'   => 'border',
			'label'  => esc_html__( 'Border', 'extras' ),
			'css'    => [
				[
					'property' => 'border',
					'selector' => $checkable_grid_selector,
				],
			],
		];




    $this->controls['checkable_start'] = [
      'tab'   => 'content',
      'group'  => 'checkable_group',
      'type'  => 'separator',
    ];

		$this->controls['gridPadding'] = [
			'tab'   => 'content',
			'group' => 'checkable_group',
			'label' => esc_html__( 'Cell padding', 'extras' ),
			'type'  => 'dimensions',
			'css'   => [
				[
					'property' => 'padding',
					'selector' => '.fluentform .ff-checkable-grids tbody>tr>td',
				],
			],
		];


    /* uploads */

    $upload_btn = '.fluentform .ff_upload_btn.ff-btn';

    $this->controls['uploadTypography'] = [
			'tab'    => 'content',
			'group'  => 'upload_group',
			'type'   => 'typography',
			'label'  => esc_html__( 'Typography', 'extras' ),
			'css'    => [
				[
					'property' => 'font',
					'selector' => $upload_btn,
				],
			],
		];

    $this->controls['uploadBackgroundColor'] = [
			'tab'    => 'content',
			'group'  => 'upload_group',
			'type'   => 'color',
			'label'  => esc_html__( 'Background', 'extras' ),
			'css'    => [
				[
					'property' => 'background-color',
					'selector' => $upload_btn,
				],
			],
		];

		$this->controls['uploadBorder'] = [
			'tab'    => 'content',
			'group'  => 'upload_group',
			'type'   => 'border',
			'label'  => esc_html__( 'Border', 'extras' ),
			'css'    => [
				[
					'property' => 'border',
					'selector' => $upload_btn,
				],
			],
		];

		$this->controls['uploadBoxShadow'] = [
			'tab'    => 'content',
			'group'  => 'upload_group',
			'label'  => esc_html__( 'Box Shadow', 'extras' ),
			'type'   => 'box-shadow',
			'css'    => [
				[
					'property' => 'box-shadow',
					'selector' => $upload_btn,
				],
			],
		];

    $this->controls['upload_start'] = [
      'tab'   => 'content',
      'group'  => 'upload_group',
      'type'  => 'separator',
    ];

		$this->controls['uploadPadding'] = [
			'tab'   => 'content',
			'group' => 'upload_group',
			'label' => esc_html__( 'Button padding', 'extras' ),
			'type'  => 'dimensions',
			'css'   => [
				[
					'property' => 'padding',
					'selector' => $upload_btn
				],
			],
		];

    /* progess bar*/

    $progress_bar = '.fluentform .ff-el-progress';

    $this->controls['progressBackgroundColor'] = [
			'tab'    => 'content',
			'group'  => 'progress_group',
			'type'   => 'color',
			'label'  => esc_html__( 'Bar Color', 'extras' ),
			'css'    => [
				[
					'property' => 'background-color',
					'selector' => $progress_bar,
				],
				[
					'property' => 'background-color',
					'selector' => '.fluentform .ff-step-titles li:after'
				]
			],
		];

    $this->controls['progressActiveBackgroundColor'] = [
			'tab'    => 'content',
			'group'  => 'progress_group',
			'type'   => 'color',
			'label'  => esc_html__( 'Bar Color (active)', 'extras' ),
			'css'    => [
				[
					'property' => 'background-color',
					'selector' => '.fluentform .ff-el-progress-bar',
				],
				[
					'property' => 'background-color',
					'selector' => '.fluentform .ff-step-titles li.ff_active:after, .fluentform .ff-step-titles li.ff_completed:after'
				]
			],
		];

    $this->controls['progressHeight'] = [
			'tab'   => 'content',
			'group' => 'progress_group',
			'label' => esc_html__( 'Bar height', 'extras' ),
			'type' => 'number',
			'units'    => true,
			'css'   => [
				[
					'property' => 'height',
					'selector' => $progress_bar,
				],
				[
					'property' => 'height',
					'selector' => '.fluentform .ff-step-titles li:after'
				]
			],
		];


    $this->controls['progressTypography'] = [
			'tab'    => 'content',
			'group'  => 'progress_group',
			'type'   => 'typography',
			'label'  => esc_html__( 'Bar Typography', 'extras' ),
			'css'    => [
				[
					'property' => 'font',
					'selector' => $progress_bar,
				],
			],
		];

    $this->controls['progressStatusTypography'] = [
			'tab'    => 'content',
			'group'  => 'progress_group',
			'type'   => 'typography',
			'label'  => esc_html__( 'Progress Status Typography', 'extras' ),
			'css'    => [
				[
					'property' => 'font',
					'selector' => '.fluentform .ff-el-progress-status',
				],
			],
		];


	/* progess steps */

    $step_titles_selector = '.fluentform .ff-step-titles span';
	$step_titles_active_selector = '.fluentform .ff-step-titles li.ff_active span, .fluentform .ff-step-titles li.ff_completed span';
	$step_bar_selector = '.fluentform .ff-step-titles li.ff_active:before';
	$step_counter_selector = '.fluentform .ff-step-titles li:before';

	$this->controls['progressTop'] = [
		'tab'   => 'content',
		'group' => 'progress_steps_group',
		'label' => esc_html__( 'Bar top position', 'extras' ),
		'type' => 'number',
		'units'    => true,
		'css'   => [
			[
				'property' => 'top',
				'selector' => '.fluentform .ff-step-titles li:after'
			]
		],
	];

	$this->controls['progressStepsCountersSep'] = [
		'tab'   => 'content',
		'group'  => 'progress_steps_group',
		'type'  => 'separator',
		'label'  => esc_html__( 'Counters', 'extras' ),
	  ];

	$this->controls['progressStepsActiveBackgroundColor'] = [
		'tab'    => 'content',
		'group'  => 'progress_steps_group',
		'type'   => 'color',
		'label'  => esc_html__( 'Background (active)', 'extras' ),
		'css'    => [
			[
				'property' => 'background-color',
				'selector' => $step_bar_selector,
			],
		],
	];

	$this->controls['progressStepsBackgroundColor'] = [
		'tab'    => 'content',
		'group'  => 'progress_steps_group',
		'type'   => 'color',
		'label'  => esc_html__( 'Background (in-active)', 'extras' ),
		'css'    => [
			[
				'property' => 'background-color',
				'selector' => $step_counter_selector,
			],
		],
	];

	$this->controls['progressStepsActiveColor'] = [
		'tab'    => 'content',
		'group'  => 'progress_steps_group',
		'type'   => 'color',
		'label'  => esc_html__( 'Color (active)', 'extras' ),
		'css'    => [
			[
				'property' => 'color',
				'selector' => $step_bar_selector,
			],
		],
	];

	$this->controls['progressStepsColor'] = [
		'tab'    => 'content',
		'group'  => 'progress_steps_group',
		'type'   => 'color',
		'label'  => esc_html__( 'Color (in-active)', 'extras' ),
		'css'    => [
			[
				'property' => 'color',
				'selector' => $step_counter_selector,
			],
		],
	];

	$this->controls['progressStepsActiveBorder'] = [
		'tab'    => 'content',
		'group'  => 'progress_steps_group',
		'type'   => 'border',
		'label'  => esc_html__( 'Border (active)', 'extras' ),
		'css'    => [
			[
				'property' => 'border',
				'selector' => $step_bar_selector,
			],
		],
	];

	$this->controls['progressStepsBorderColor'] = [
		'tab'    => 'content',
		'group'  => 'progress_steps_group',
		'type'   => 'border',
		'label'  => esc_html__( 'Border (in-active)', 'extras' ),
		'css'    => [
			[
				'property' => 'border',
				'selector' => $step_counter_selector,
			],
		],
	];

	$this->controls['progressStepsTypography'] = [
		'tab'    => 'content',
		'group'  => 'progress_steps_group',
		'type'   => 'typography',
		'label'  => esc_html__( 'Typography', 'extras' ),
		'css'    => [
			[
				'property' => 'font',
				'selector' => $step_counter_selector,
			],
		],
	];

	$this->controls['progressStepsTitlesSep'] = [
		'tab'   => 'content',
		'group'  => 'progress_steps_group',
		'type'  => 'separator',
		'label'  => esc_html__( 'Titles', 'extras' ),
	  ];

	  $this->controls['progressStepsTitlesActiveColor'] = [
		'tab'    => 'content',
		'group'  => 'progress_steps_group',
		'type'   => 'color',
		'label'  => esc_html__( 'Color (active)', 'extras' ),
		'css'    => [
			[
				'property' => 'color',
				'selector' => $step_titles_active_selector,
			],
		],
	];

	$this->controls['progressStepsTitlesColor'] = [
		'tab'    => 'content',
		'group'  => 'progress_steps_group',
		'type'   => 'color',
		'label'  => esc_html__( 'Color (in-active)', 'extras' ),
		'css'    => [
			[
				'property' => 'color',
				'selector' => $step_titles_selector,
			],
		],
	];

	$this->controls['progressStepsTitlesTypography'] = [
		'tab'    => 'content',
		'group'  => 'progress_steps_group',
		'type'   => 'typography',
		'label'  => esc_html__( 'Typography', 'extras' ),
		'css'    => [
			[
				'property' => 'font',
				'selector' => $step_titles_selector,
			],
		],
	];

    /* step button */

    $step_btn = '.fluentform .ff-btn-secondary';

    $this->controls['stepTypography'] = [
			'tab'    => 'content',
			'group'  => 'step_group',
			'type'   => 'typography',
			'label'  => esc_html__( 'Typography', 'extras' ),
			'css'    => [
				[
					'property' => 'font',
					'selector' => $step_btn,
				],
			],
		];

    $this->controls['stepBackgroundColor'] = [
			'tab'    => 'content',
			'group'  => 'step_group',
			'type'   => 'color',
			'label'  => esc_html__( 'Background', 'extras' ),
			'css'    => [
				[
					'property' => 'background-color',
					'selector' => $step_btn,
				],
			],
		];

		$this->controls['stepBorder'] = [
			'tab'    => 'content',
			'group'  => 'step_group',
			'type'   => 'border',
			'label'  => esc_html__( 'Border', 'extras' ),
			'css'    => [
				[
					'property' => 'border',
					'selector' => $step_btn,
				],
			],
		];

		$this->controls['stepBoxShadow'] = [
			'tab'    => 'content',
			'group'  => 'step_group',
			'label'  => esc_html__( 'Box Shadow', 'extras' ),
			'type'   => 'box-shadow',
			'css'    => [
				[
					'property' => 'box-shadow',
					'selector' => $step_btn,
				],
			],
		];

    $this->controls['step_start'] = [
      'tab'   => 'content',
      'group'  => 'step_group',
      'type'  => 'separator',
    ];

		$this->controls['stepPadding'] = [
			'tab'   => 'content',
			'group' => 'step_group',
			'label' => esc_html__( 'Button padding', 'extras' ),
			'type'  => 'dimensions',
			'css'   => [
				[
					'property' => 'padding',
					'selector' => $step_btn
				],
			],
		];




    /* net promoter group */

    $net_promoter_label = '.fluentform .ff_net_table tbody tr td label';

    $this->controls['netTypography'] = [
			'tab'    => 'content',
			'group'  => 'netpromoter_group',
			'type'   => 'typography',
			'label'  => esc_html__( 'Typography', 'extras' ),
			'css'    => [
				[
					'property' => 'font',
					'selector' => $net_promoter_label,
				],
			],
		];

    $this->controls['checkedInitialBackground'] = [
			'tab'    => 'content',
			'group'  => 'netpromoter_group',
			'type'   => 'color',
			'label'  => esc_html__( 'Background', 'extras' ),
			'css'    => [
				[
					'property' => 'background-color',
					'selector' => '.fluentform .ff_net_table tbody tr td'
				],
			],
		];

    $this->controls['checkedBorderColor'] = [
			'tab'    => 'content',
			'group'  => 'netpromoter_group',
			'type'   => 'color',
			'label'  => esc_html__( 'Border color', 'extras' ),
			'css'    => [
				[
					'property' => 'border-color',
					'selector' => '.fluentform .ff_net_table tbody tr td'
				],
        [
					'property' => 'border-color',
					'selector' => '.ff_net_table tbody tr td:first-of-type'
				],
			],
		];

    $this->controls['checkedHoverBorder'] = [
			'tab'    => 'content',
			'group'  => 'netpromoter_group',
			'type'   => 'color',
			'label'  => esc_html__( 'Hover Border Color', 'extras' ),
			'css'    => [
				[
					'property' => 'border-color',
					'selector' => '.fluentform .ff_net_table tbody tr td label:hover:after'
				],
			],
		];
    

    $this->controls['checkedBackground'] = [
			'tab'    => 'content',
			'group'  => 'netpromoter_group',
			'type'   => 'color',
			'label'  => esc_html__( 'Checked background', 'extras' ),
			'css'    => [
				[
					'property' => 'background-color',
					'selector' => '.fluentform .ff_net_table tbody tr td input[type=radio]:checked+label'
				],
			],
		];

    $this->controls['checkedColor'] = [
			'tab'    => 'content',
			'group'  => 'netpromoter_group',
			'type'   => 'color',
			'label'  => esc_html__( 'Checked color', 'extras' ),
			'css'    => [
				[
					'property' => 'color',
					'selector' => '.fluentform .ff_net_table tbody tr td input[type=radio]:checked+label'
				],
			],
		];


    /* repeater */

	$button_icon = '.repeat_btn .ff-el-repeat-buttons-list svg';

	$this->controls['repeaterIconSize'] = [
		'tab'      => 'content',
		'group'    => 'repeater_group',
		'label'    => esc_html__( 'Icon size', 'extras' ),
		'type' => 'number',
		'units'    => true,
		'css'      => [
			[
				'property'  => 'font-size',
				'selector'  => $button_icon,
			],
		],
	];

	$this->controls['repeaterIconColor'] = [
		'tab'    => 'content',
		'group'  => 'repeater_group',
		'type'   => 'color',
		'label'  => esc_html__( 'Color', 'extras' ),
		'css'    => [
			[
				'property' => 'fill',
				'selector' => $button_icon,
			],
		],
	];



    /* messages */

    $success_msg = '.fluentform .ff-message-success';
    $error_msg = '.fluentform .text-danger';

    $this->controls['success_start'] = [
      'tab'   => 'content',
      'group'  => 'success_group',
      'type'  => 'separator',
      'label' => esc_html__( 'Success message', 'extras' ),
    ];

    $this->controls['successTypography'] = [
			'tab'    => 'content',
			'group'  => 'success_group',
			'type'   => 'typography',
			'label'  => esc_html__( 'Typography', 'extras' ),
			'css'    => [
				[
					'property' => 'font',
					'selector' => $success_msg,
				],
			],
		];

    $this->controls['successBackgroundColor'] = [
			'tab'    => 'content',
			'group'  => 'success_group',
			'type'   => 'color',
			'label'  => esc_html__( 'Background', 'extras' ),
			'css'    => [
				[
					'property' => 'background-color',
					'selector' => $success_msg,
				],
			],
		];

		$this->controls['successBorder'] = [
			'tab'    => 'content',
			'group'  => 'success_group',
			'type'   => 'border',
			'label'  => esc_html__( 'Border', 'extras' ),
			'css'    => [
				[
					'property' => 'border',
					'selector' => $success_msg,
				],
			],
		];

		$this->controls['successBoxShadow'] = [
			'tab'    => 'content',
			'group'  => 'success_group',
			'label'  => esc_html__( 'Box Shadow', 'extras' ),
			'type'   => 'box-shadow',
			'css'    => [
				[
					'property' => 'box-shadow',
					'selector' => $success_msg,
				],
			],
		];

    $this->controls['success_end'] = [
      'tab'   => 'content',
      'group'  => 'success_group',
      'type'  => 'separator',
    ];

		$this->controls['successPadding'] = [
			'tab'   => 'content',
			'group' => 'success_group',
			'label' => esc_html__( 'Padding', 'extras' ),
			'type'  => 'dimensions',
			'css'   => [
				[
					'property' => 'padding',
					'selector' => $success_msg
				],
			],
		];

    $this->controls['error_start'] = [
      'tab'   => 'content',
      'group'  => 'success_group',
      'type'  => 'separator',
      'label' => esc_html__( 'Validation error text', 'extras' ),
    ];

    $this->controls['errorTypography'] = [
			'tab'    => 'content',
			'group'  => 'success_group',
			'type'   => 'typography',
			'label'  => esc_html__( 'Typography', 'extras' ),
			'css'    => [
				[
					'property' => 'font',
					'selector' => $error_msg,
				],
			],
		];

    $this->controls['errorMargin'] = [
			'tab'   => 'content',
			'group' => 'success_group',
			'label' => esc_html__( 'Margin', 'extras' ),
			'type'  => 'dimensions',
			'css'   => [
				[
					'property' => 'margin',
					'selector' => $error_msg
				],
			],
		];


  }

  // Methods: Frontend-specific
  public function enqueue_scripts() {
    	wp_enqueue_style('fluentform-public-default');
		wp_enqueue_style('fluent-form-styles');
		wp_enqueue_script('fluent-form-submission');

		if (! \BricksExtras\Helpers::elementCSSAdded($this->name) ) {
			wp_enqueue_style( 'x-fluent-form', BRICKSEXTRAS_URL . 'components/assets/css/fluentform.css', [], '' );
		  }
  }
  
  public function render() {

    if ( !function_exists( 'wpFluentForm' ) ) {
			return $this->render_element_placeholder( [ 
        'title' => esc_html__( "Fluent Forms isn't active.", 'bricksextras' )
        ] );
		}

    $formSource = isset( $this->settings['formSource'] ) ? true : false;

    $config = [];

    if ( isset( $this->settings['smartUI'] ) ) {
			$config += [ 'smartUI' => true ];
		}

    $this->set_attribute( '_root', 'data-x-fluent-form', wp_json_encode( $config ) );

    echo "<div {$this->render_attributes( '_root' )}>";

	$fluentFormID = isset( $this->settings['fluentFormID'] ) ? $this->settings['fluentFormID'] : false;

	$formID = '';

	if ( !$formSource ) {
		$formID = $this->settings['fluentFormSelect'];
	} else {
		if ( !!$fluentFormID ) {
			$formID = strstr( $fluentFormID, '{') ? $this->render_dynamic_data_tag( $fluentFormID, 'text' ) : $fluentFormID;
		}
	}

	echo do_shortcode( '[fluentform id="' . esc_attr( $formID ) . '" ]' );

    echo "</div>";
    
  }

}