<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class X_Pro_Accordion extends \Bricks\Element {
	public $category = 'extras';
	public $name     = 'xproaccordion';
	public $icon     = 'ti-layout-list-post';
	public $scripts  = [ 'xProAccordion' ];
	public $nestable = true;

	public function get_label() {
		return esc_html__( 'Pro Accordion', 'bricks' );
	}

	public function set_control_groups() {
    $this->control_groups['items'] = [
			'title' => esc_html__( 'Accordion Items', 'bricks' ),
		];
		$this->control_groups['title'] = [
			'title' => esc_html__( 'Accordion Header', 'bricks' ),
		];

		$this->control_groups['content'] = [
			'title' => esc_html__( 'Accordion Content', 'bricks' ),
		];

   		 $this->control_groups['icon'] = [
			'title' => esc_html__( 'Icons', 'bricks' ),
		];

		$this->control_groups['config'] = [
			'title' => esc_html__( 'Hash / Scroll-to', 'bricks' ),
		];

		$this->control_groups['conditional'] = [
			'title' => esc_html__( 'Conditional accordion', 'bricks' ),
			'required' => ['maybeConditional','=',true]
		];
	}

	public function set_controls() {

    $this->controls['builderHidden'] = [
			'tab'   => 'content',
			'inline' => true,
			'small' => true,
			'label' => esc_html__( 'Collapse in builder', 'bricks' ),
			'type'  => 'checkbox',
		];

		$this->controls['builderHidden_sep'] = [
			'tab'   => 'content',
			'type'  => 'separator',
		  ];
		
    
		$this->controls['_children'] = [
			'type'    => 'repeater',
      		'group'   => 'items',
			'titleProperty' => 'label',
			'items'         => 'children', // NOTE: Undocumented
		];

		$this->controls['expandFirstItem'] = [
			'label' => esc_html__( 'Expand first item', 'bricks' ),
			'type'  => 'checkbox',
		];

    $this->controls['expandAll'] = [
			'label' => esc_html__( 'Expand all', 'bricks' ),
			'type'  => 'checkbox',
		];

    $this->controls['closeSibling'] = [
			'label' => esc_html__( 'Disable closing sibling items', 'bricks' ),
			'type'  => 'checkbox',
		];

		$this->controls['hashLink'] = [
			'label' => esc_html__( 'Open/Scrollto if hashlink in URL', 'bricks' ),
			'type'  => 'checkbox',
			'group' => 'config',
			'description' => esc_html__( 'If URL includes # to accordion item ID on page load, the page will scroll and that accordion item opened', 'bricks' ),
		];

		$this->controls['scrollOffset'] = [
			'tab'         => 'content',
			'label'       => esc_html__( 'Scroll offset (px)', 'bricks' ),
			'type'        => 'number',
			'units'       => false,
			'placeholder' => '0',
			'group' => 'config',
			'required' => ['hashLink', '=', true]
		];

		$this->controls['duration'] = [
			'label'       => esc_html__( 'Transition Duration', 'bricks' ) . ' (ms)',
			'type'        => 'number',
			'placeholder' => 150,
		];

    	$this->controls['itemGap'] = [
			'tab'         => 'content',
			'label'       => esc_html__( 'Gap between items', 'bricks' ),
			'type'        => 'number',
			'units'       => true,
			'css'         => [
				[
					'property' => 'gap',
          			'selector' => ''
				],
			],
			'placeholder' => '0',
		];


		$this->controls['tag'] = [
			'tab'       => 'content',
			'label'     => esc_html__( 'HTML tag', 'bricks' ),
			'type'      => 'select',
			'options'   => [
				'div' => 'div',
				'ul' => 'ul',
			],
			'inline'    => true,
			'clearable' => false,
			'placeholder'   => 'div',
		];

		$this->controls['faqSchema'] = [
			'tab'       => 'content',
			'label'     => esc_html__( 'FAQ schema', 'bricks' ),
			'type'      => 'checkbox',
			'inline'    => true,
		];

		/* conditional */

		$this->controls['maybeConditional'] = [
			'label'       => esc_html__( 'Conditional accordion', 'bricks' ),
			'type'        => 'checkbox',
		];



		$this->controls['navKeys'] = [
			'tab'       => 'content',
			'label'     => esc_html__( 'Skip to next item with up/down keys', 'bricks' ),
			'type'      => 'select',
			'options'   => [
				'enable' => esc_html__( 'Enable', 'bricks' ),
				'disable' => esc_html__( 'Disable', 'bricks' ),
			],
			'inline'    => true,
			'placeholder'   => esc_html__( 'Enable', 'bricks' ),
		];

		
		


		// TITLE

		

		$this->controls['titleMargin'] = [
			'group' => 'title',
			'label' => esc_html__( 'Margin', 'bricks' ),
			'type'  => 'dimensions',
			'css'   => [
				[
					'property' => 'margin',
					'selector' => '.x-accordion_header',
				],
			],
		];

		$this->controls['titlePadding'] = [
			'group' => 'title',
			'label' => esc_html__( 'Padding', 'bricks' ),
			'type'  => 'dimensions',
			'css'   => [
				[
					'property' => 'padding',
					'selector' => '.x-accordion_header',
				],
			],
		];

		$this->controls['titleHeight'] = [
			'group'   => 'title',
			'label'   => esc_html__( 'Min. height', 'bricks' ),
			'type'    => 'number',
			'units'   => true,
			'css'     => [
				[
					'property' => 'min-height',
					'selector' => '.x-accordion_header',
				],
			],
			//'default' => '50px',
		];

		$this->controls['titleBackgroundColor'] = [
			'group' => 'title',
			'label' => esc_html__( 'Background color', 'bricks' ),
			'type'  => 'color',
			'css'   => [
				[
					'property' => 'background-color',
					'selector' => '.x-accordion_header',
				],
			],
		];

		$this->controls['titleBorder'] = [
			'group' => 'title',
			'label' => esc_html__( 'Border', 'bricks' ),
			'type'  => 'border',
			'css'   => [
				[
					'property' => 'border',
					'selector' => '.x-accordion_header',
				],
			],
		];

		$this->controls['titleTypography'] = [
			'group' => 'title',
			'label' => esc_html__( 'Typography', 'bricks' ),
			'type'  => 'typography',
			'css'   => [
				[
					'property' => 'font',
					'selector' => '.x-accordion_header',
				],
			],
		];

		// ACTIVE TITLE

		$this->controls['titleActiveSeparator'] = [
			'group' => 'title',
			'label' => esc_html__( 'Active', 'bricks' ),
			'type'  => 'separator',
		];

		$this->controls['titleActiveBackgroundColor'] = [
			'group' => 'title',
			'label' => esc_html__( 'Background color', 'bricks' ),
			'type'  => 'color',
			'css'   => [
				[
					'property' => 'background-color',
					'selector' => '.x-accordion_header[aria-expanded=true]',
				],
			],
		];

		$this->controls['titleActiveBorder'] = [
			'group' => 'title',
			'label' => esc_html__( 'Border', 'bricks' ),
			'type'  => 'border',
			'css'   => [
				[
					'property' => 'border',
					'selector' => '.x-accordion_header[aria-expanded=true]',
				],
			],
		];

		$this->controls['titleActiveTypography'] = [
			'group' => 'title',
			'label' => esc_html__( 'Typography', 'bricks' ),
			'type'  => 'typography',
			'css'   => [
				[
					'property' => 'font',
					'selector' => '.x-accordion_header[aria-expanded=true]',
				],
			],
		];

		// CONTENT

		$this->controls['contentPadding'] = [
			'group'   => 'content',
			'label'   => esc_html__( 'Padding', 'bricks' ),
			'type'    => 'dimensions',
			'css'     => [
				[
					'property' => 'padding',
					'selector' => '.x-accordion_content-inner',
				],
			],
			'placeholder' => [
				'top'    => 15,
				'right'  => 15,
				'bottom' => 15,
				'left'   => 15,
			],
		];

		$this->controls['contentBackgroundColor'] = [
			'group' => 'content',
			'label' => esc_html__( 'Background color', 'bricks' ),
			'type'  => 'color',
			'css'   => [
				[
					'property' => 'background-color',
					'selector' => '.x-accordion_content',
				],
			],
		];

		$this->controls['contentBorder'] = [
			'group' => 'content',
			'label' => esc_html__( 'Border', 'bricks' ),
			'type'  => 'border',
			'css'   => [
				[
					'property' => 'border',
					'selector' => '.x-accordion_content',
				],
			],
		];

		$this->controls['contentTypography'] = [
			'group' => 'content',
			'label' => esc_html__( 'Typography', 'bricks' ),
			'type'  => 'typography',
			'css'   => [
				[
					'property' => 'font',
					'selector' => '.x-accordion_content',
				],
			],
		];


    /* icon */

    $this->controls['iconTransform'] = [
			'tab'         => 'style',
			'group'       => 'icon',
			'type'        => 'transform',
			'label'       => esc_html__( 'Icon Transform', 'bricks' ),
			'css'         => [
				[
          'selector' => '.x-accordion_icon',
					'property' => 'transform',
				],
			],
			'default' => [
				'rotateX' => '0deg'
			],
			'inline'      => true,
			'small'       => true,
		];

    $this->controls['iconTypography'] = [
			'group' => 'icon',
			'label' => esc_html__( 'Icon Typography', 'bricks' ),
			'type'  => 'typography',
			'css'   => [
				[
					'property' => 'font',
					'selector' => '.x-accordion_icon',
				],
			],
		];

    $this->controls['iconActiveSeparator'] = [
			'group' => 'icon',
			'label' => esc_html__( 'Active', 'bricks' ),
			'type'  => 'separator',
		];

    $this->controls['iconTransformActive'] = [
			'tab'         => 'style',
			'group'       => 'icon',
			'type'        => 'transform',
			'label'       => esc_html__( 'Icon Transform (active)', 'bricks' ),
			'css'         => [
				[
          'selector' => '.x-accordion_header[aria-expanded=true] .x-accordion_icon',
					'property' => 'transform',
				],
			],
      'default' => [
        'rotateX' => '180deg'
      ],
			'inline'      => true,
			'small'       => true,
		];

    $this->controls['iconTypographyActive'] = [
			'group' => 'icon',
			'label' => esc_html__( 'Icon Typography (active)', 'bricks' ),
			'type'  => 'typography',
			'css'   => [
				[
					'property' => 'font',
					'selector' => '.x-accordion_header[aria-expanded=true] .x-accordion_icon',
				],
			],
		];


		

		$this->controls['removeAccordion'] = [
			'tab'       => 'content',
			'label'     => esc_html__( 'Remove accordion behaviour at this breakpoint', 'bricks' ),
			'type'      => 'select',
			'group' => 'conditional',
			'options'   => [
				'true' => esc_html__( 'True', 'bricks' ),
				'false' => esc_html__( 'False', 'bricks' ),
			],
			'placeholder'   => 'false',
			'css'         => [
				[
					'property' => '--x-disable-accordion',
          			'selector' => ''
				],
				[
					'property' => 'display',
          			'selector' => '.x-accordion_content',
					'value' => 'flex',
					'required' => 'true'
				],
				[
					'property' => 'display',
          			'selector' => '.x-accordion_content',
					'value' => 'none',
					'required' => 'false'
				],
				[
					'property' => 'display',
          			'selector' => '.x-accordion_icon',
					'value' => 'none',
					'required' => 'true'
				],
				[
					'property' => 'display',
          			'selector' => '.x-accordion_icon',
					'value' => 'inline-block',
					'required' => 'false'
				],
			],
		];
		

		// Display

		$this->controls['conditionalSep'] = [
			'group' => 'conditional',
			'type'        => 'separator',
		];

		$this->controls['conditional_display'] = [
			'group' => 'conditional',
			'label'     => esc_html__( 'Display', 'bricks' ),
			'type'      => 'select',
			'options'   => [
				'flex'         => 'flex',
				'grid'         => 'grid',
				'block'        => 'block',
				'inline-block' => 'inline-block',
				'inline'       => 'inline',
				'none'         => 'none',
			],
			'add'       => true,
			'inline'    => true,
			'lowercase' => true,
			'css'       => [
				[
					'property' => 'display',
					'selector' => '',
				],
				[
					'selector' => '',
					'property' => 'align-items',
					'value'    => 'initial',
					'required' => 'grid',
				],
			],
		];

		// Display: grid

		$this->controls['conditional_gridGap'] = [
			'group' => 'conditional',
			'label'       => esc_html__( 'Gap', 'bricks' ),
			'type'        => 'number',
			'units'       => true,
			'css'         => [
				[
					'property' => 'grid-gap', 
					'selector' => '',
				],
			],
			'placeholder' => '',
			'required'    => [ 'conditional_display', '=', 'grid' ],
		];

		$this->controls['conditional_gridTemplateColumns'] = [
			'group' => 'conditional',
			'label'          => esc_html__( 'Grid template columns', 'bricks' ),
			'type'           => 'text',
			'tooltip'        => [
				'content'  => 'grid-template-columns',
				'position' => 'top-left',
			],
			'hasDynamicData' => false,
			'css'            => [
				[
					'property' => 'grid-template-columns',
					'selector' => '',
				],
			],
			'placeholder'    => '',
			'required'       => [ 'conditional_display', '=', 'grid' ],
		];

		$this->controls['conditional_gridTemplateRows'] = [
			'group' => 'conditional',
			'label'          => esc_html__( 'Grid template rows', 'bricks' ),
			'type'           => 'text',
			'tooltip'        => [
				'content'  => 'grid-template-rows',
				'position' => 'top-left',
			],
			'hasDynamicData' => false,
			'css'            => [
				[
					'property' => 'grid-template-rows',
					'selector' => '',
				],
			],
			'placeholder'    => '',
			'required'       => [ 'conditional_display', '=', 'grid' ],
		];

		$this->controls['conditional_gridAutoColumns'] = [
			'group' => 'conditional',
			'label'          => esc_html__( 'Grid auto columns', 'bricks' ),
			'type'           => 'text',
			'tooltip'        => [
				'content'  => 'grid-auto-columns',
				'position' => 'top-left',
			],
			'hasDynamicData' => false,
			'css'            => [
				[
					'property' => 'grid-auto-columns',
					'selector' => '',
				],
			],
			'required'       => [ 'conditional_display', '=', 'grid' ],
		];

		$this->controls['conditional_gridAutoRows'] = [
			'group' => 'conditional',
			'label'          => esc_html__( 'Grid auto rows', 'bricks' ),
			'type'           => 'text',
			'tooltip'        => [
				'content'  => 'grid-auto-rows',
				'position' => 'top-left',
			],
			'hasDynamicData' => false,
			'css'            => [
				[
					'property' => 'grid-auto-rows',
					'selector' => '',
				],
			],
			'required'       => [ 'conditional_display', '=', 'grid' ],
		];

		$this->controls['conditional_gridAutoFlow'] = [
			'group' => 'conditional',
			'label'    => esc_html__( 'Grid auto flow', 'bricks' ),
			'type'     => 'select',
			'options'  => [
				'row'    => 'row',
				'column' => 'column',
				'dense'  => 'dense',
			],
			'tooltip'  => [
				'content'  => 'grid-auto-flow',
				'position' => 'top-left',
			],
			'css'      => [
				[
					'property' => 'grid-auto-flow',
					'selector' => '',
				],
			],
			'required' => [ 'conditional_display', '=', 'grid' ],
		];

		$this->controls['conditional_justifyItemsGrid'] = [
			'group' => 'conditional',
			'label'     => esc_html__( 'Justify items', 'bricks' ),
			'tooltip'   => [
				'content'  => 'justify-items',
				'position' => 'top-left',
			],
			'type'      => 'justify-content',
			'direction' => 'row',
			'css'       => [
				[
					'property' => 'justify-items',
				],
			],
			'required'  => [ 'conditional_display', '=', 'grid' ],
		];

		$this->controls['conditional_alignItemsGrid'] = [
			'group' => 'conditional',
			'label'     => esc_html__( 'Align items', 'bricks' ),
			'tooltip'   => [
				'content'  => 'align-items',
				'position' => 'top-left',
			],
			'type'      => 'align-items',
			'direction' => 'row',
			'css'       => [
				[
					'property' => 'align-items',
				],
			],
			'required'  => [ 'conditional_display', '=', 'grid' ],
		];

		$this->controls['conditional_justifyContentGrid'] = [
			'group' => 'conditional',
			'label'     => esc_html__( 'Justify content', 'bricks' ),
			'tooltip'   => [
				'content'  => 'justify-content',
				'position' => 'top-left',
			],
			'type'      => 'justify-content',
			'direction' => 'row',
			'css'       => [
				[
					'property' => 'justify-content',
				],
			],
			'required'  => [ 'conditional_display', '=', 'grid' ],
		];

		$this->controls['conditional_alignContentGrid'] = [
			'group' => 'conditional',
			'label'     => esc_html__( 'Align content', 'bricks' ),
			'tooltip'   => [
				'content'  => 'align-content',
				'position' => 'top-left',
			],
			'type'      => 'align-items',
			'direction' => 'row',
			'css'       => [
				[
					'property' => 'align-content',
				],
			],
			'required'  => [ 'conditional_display', '=', 'grid' ],
		];

		// Display: flex

		// Flex controls
		$this->controls['conditional_flexWrap'] = [
			'group' => 'conditional',
			'label'    => esc_html__( 'Flex wrap', 'bricks' ),
			'tooltip'  => [
				'content'  => 'flex-wrap',
				'position' => 'top-left',
			],
			'type'     => 'select',
			'options'  => [
				'nowrap'       => esc_html__( 'No wrap', 'bricks' ),
				'wrap'         => esc_html__( 'Wrap', 'bricks' ),
				'wrap-reverse' => esc_html__( 'Wrap reverse', 'bricks' ),
			],
			'inline'   => true,
			'css'      => [
				[
					'property' => 'flex-wrap',
				],
			],
			'required' => [ 'conditional_display', '=', 'flex' ],
		];

		$this->controls['conditional_direction'] = [
			'group' => 'conditional',
			'label'    => esc_html__( 'Direction', 'bricks' ),
			'tooltip'  => [
				'content'  => 'flex-direction',
				'position' => 'top-left',
			],
			'type'     => 'direction',
			'css'      => [
				[
					'property' => 'flex-direction',
				],
			],
			'inline'   => true,
			'rerender' => true,
			'required' => [ 'conditional_display', '=', 'flex' ],
		];

		$this->controls['conditional_alignSelf'] = [
			'group' => 'conditional',
			'label'    => esc_html__( 'Align self', 'bricks' ),
			'tooltip'  => [
				'content'  => 'align-self',
				'position' => 'top-left',
			],
			'type'     => 'align-items',
			'css'      => [
				[
					'property'  => 'align-self',
					'important' => true,
				],
				[
					'selector' => '',
					'property' => 'width',
					'value'    => '100%',
					'required' => 'stretch',
				],
			],
			'required' => [ 'conditional_display', '=', 'flex' ],
		];

		$this->controls['conditional_justifyContent'] = [
			'group' => 'conditional',
			'label'    => esc_html__( 'Align main axis', 'bricks' ),
			'tooltip'  => [
				'content'  => 'justify-content',
				'position' => 'top-left',
			],
			'type'     => 'justify-content',
			'css'      => [
				[
					'property' => 'justify-content',
				],
			],
			'required' => [ 'conditional_display', '=', 'flex' ],
		];

		$this->controls['conditional_alignItems'] = [
			'group' => 'conditional',
			'label'    => esc_html__( 'Align cross axis', 'bricks' ),
			'tooltip'  => [
				'content'  => 'align-items',
				'position' => 'top-left',
			],
			'type'     => 'align-items',
			'css'      => [
				[
					'property' => 'align-items',
				],
			],
			'required' => [ 'conditional_display', '=', 'flex' ],
		];

		$this->controls['conditional_columnGap'] = [
			'group' => 'conditional',
			'label'    => esc_html__( 'Column gap', 'bricks' ),
			'type'     => 'number',
			'units'    => true,
			'css'      => [
				[
					'property' => 'column-gap',
				],
			],
			'required' => [ 'conditional_display', '=', 'flex' ],
		];

		$this->controls['conditional_rowGap'] = [
			'group' => 'conditional',
			'label'    => esc_html__( 'Row gap', 'bricks' ),
			'type'     => 'number',
			'units'    => true,
			'css'      => [
				[
					'property' => 'row-gap',
				],
			],
			'required' => [ 'conditional_display', '=', 'flex' ],
		];

		
		$this->controls['conditional_flexGrow'] = [
			'group' => 'conditional',
			'label'       => esc_html__( 'Flex grow', 'bricks' ),
			'type'        => 'number',
			'min'         => 0,
			'tooltip'     => [
				'content'  => 'flex-grow',
				'position' => 'top-left',
			],
			'css'         => [
				[
					'property' => 'flex-grow',
				],
			],
			'placeholder' => 0,
			'required'    => [ 'conditional_display', '=', 'flex' ],
		];

		$this->controls['conditional_flexShrink'] = [
			'group' => 'conditional',
			'label'       => esc_html__( 'Flex shrink', 'bricks' ),
			'type'        => 'number',
			'min'         => 0,
			'tooltip'     => [
				'content'  => 'flex-shrink',
				'position' => 'top-left',
			],
			'css'         => [
				[
					'property' => 'flex-shrink',
				],
			],
			'placeholder' => 1,
			'required'    => [ 'conditional_display', '=', 'flex' ],
		];

		$this->controls['conditional_flexBasis'] = [
			'group' => 'conditional',
			'label'          => esc_html__( 'Flex basis', 'bricks' ),
			'type'           => 'text',
			'tooltip'        => [
				'content'  => 'flex-basis',
				'position' => 'top-left',
			],
			'css'            => [
				[
					'property' => 'flex-basis',
				],
			],
			'inline'         => true,
			'small'          => true,
			'placeholder'    => 'auto',
			'hasDynamicData' => false,
			'required'       => [ 'conditional_display', '=', 'flex' ],
		];

		// Misc
		$this->controls['conditional_order'] = [
			'group' => 'conditional',
			'label'       => esc_html__( 'Order', 'bricks' ),
			'type'        => 'number',
			'min'         => -999,
			'css'         => [
				[
					'property' => 'order',
				],
			],
			'placeholder' => 0,
			'required'    => [ 'conditional_display', '!=',  'none' ],
		];


	}

	public function get_nestable_item() {

		$alternativeStructure = false;

		/* allow for using button structure if prefered */
		$alternativeStructure = apply_filters( 'bricksextras/proaccordion/altdefault', $alternativeStructure );

		if ( !$alternativeStructure ) {
			
			return [
				'name'     => 'block',
				'label'    => esc_html__( 'Accordion Item', 'bricks' ),
				'settings' => [
					'_hidden'         => [
						'_cssClasses' => 'x-accordion_item',
					],
				],
				'children' => [
					[
						'name'     => 'block',
						'label'    => esc_html__( 'Heading tag', 'bricks' ),
						'settings' => [
							'tag' => 'h4',
							'_hidden'         => [
								'_cssClasses' => 'x-accordion_heading-wrapper',
							],
						],
						'children' => [
							[
								'name'     => 'block',
								'label'    => esc_html__( 'Accordion header', 'bricks' ),
								'deletable' => false,
								'settings' => [
									'_alignItems'     => 'center',
									'_direction'      => 'row',
									'_justifyContent' => 'space-between',
									'_flexWrap' => 'nowrap',
									'_hidden'         => [
										'_cssClasses' => 'x-accordion_header',
									],
									'_attributes' => [
										[
											'name' => 'role',
											'value' => 'button',
										],
										[
											'name' => 'tabindex',
											'value' => '0',
										],
									]
								],
								'children' => [
									[
										'name'     => 'text-basic',
										'settings' => [
											'text' => esc_html__( 'Title', 'bricks' ) . ' {item_index}',
											'tag'  => 'span',
											'_hidden'         => [
												'_cssClasses' => 'x-accordion_title',
											],
										],
										
									],
									[
										'name'     => 'icon',
										'label'    => esc_html__( 'Toggle icon', 'bricks' ),
										'settings' => [
											'_hidden' => [
												'_cssClasses' => 'x-accordion_icon',
											],
											'icon'     => [
												'icon'    => 'ion-ios-arrow-down',
												'library' => 'ionicons',
											],
											'iconSize' => '16px',
										],
									],
								],
							],
						],
					],
					[
						'name'     => 'block',
						'label'    => esc_html__( 'Content wrapper', 'bricks' ),
						'deletable' => false,
						'settings' => [
							'_hidden' => [
								'_cssClasses' => 'x-accordion_content',
							],
						],
								'children' => [
									[
									'name'     => 'div',
									'label'    => esc_html__( 'Content', 'bricks' ),
									'settings' => [
										'_hidden' => [
										'_cssClasses' => 'x-accordion_content-inner',
										],
									],
									'children' => [
										[
										'name'     => 'text',
										'settings' => [
											'text' => 'Edit this text or add in new elements. Aenean commodo ligula egget dolor. Aenean massa. Sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quak felis, ultricies nec, pellentesque eu, pretium quid, sem.',
										],
									],
								],
							],
						],  
					],
				],
			];

		} else {

			return [
				'name'     => 'block',
				'label'    => esc_html__( 'Accordion Item', 'bricks' ),
				'settings' => [
					'_hidden'         => [
						'_cssClasses' => 'x-accordion_item',
					],
				],
				'children' => [
							[
								'name'     => 'block',
								'label'    => esc_html__( 'Accordion header', 'bricks' ),
								'deletable' => false,
								'settings' => [
									'_alignItems'     => 'center',
									'_direction'      => 'row',
									'_justifyContent' => 'space-between',
									'_hidden'         => [
										'_cssClasses' => 'x-accordion_header',
									],
									'_attributes' => [
										[
											'name' => 'role',
											'value' => 'button',
										],
										[
											'name' => 'tabindex',
											'value' => '0',
										],
									]
								],

								'children' => [
									[
										'name'     => 'heading',
										'settings' => [
											'text' => esc_html__( 'Title', 'bricks' ) . ' {item_index}',
											'tag'  => 'h4',
											'_hidden'         => [
												'_cssClasses' => 'x-accordion_title',
											],
											/*'_attributes' => [
												[
													'name' => 'role',
													'value' => 'heading',
												]
											]*/
										],
										
									],
									[
										'name'     => 'icon',
										'label'    => esc_html__( 'Toggle icon', 'bricks' ),
										'settings' => [
											'_hidden' => [
												'_cssClasses' => 'x-accordion_icon',
											],
											'icon'     => [
												'icon'    => 'ion-ios-arrow-down',
												'library' => 'ionicons',
											],
											'iconSize' => '1em',
										],
									],
								],
							],
					[
						'name'     => 'block',
						'label'    => esc_html__( 'Content wrapper', 'bricks' ),
						'deletable' => false,
						'settings' => [
							'_hidden' => [
								'_cssClasses' => 'x-accordion_content',
							],
						],
						'children' => [
							[
							'name'     => 'div',
							'label'    => esc_html__( 'Content', 'bricks' ),
							'settings' => [
								'_hidden' => [
								'_cssClasses' => 'x-accordion_content-inner',
								],
							],
							'children' => [
								[
								'name'     => 'text',
								'settings' => [
									'text' => 'Edit this text or add in new elements. Aenean commodo ligula egget dolor. Aenean massa. Sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quak felis, ultricies nec, pellentesque eu, pretium quid, sem.',
								],
								],
							],
							],
						],  
					],
				],
			];
		}
	}

	public function get_nestable_children() {
		$children = [];

		for ( $i = 0; $i < 1; $i++ ) {
			$item = $this->get_nestable_item();

			// Replace {item_index} with $index
			$item       = json_encode( $item );
			$item       = str_replace( '{item_index}', $i + 1, $item );
			$item       = json_decode( $item, true );
			$children[] = $item;
		}

		return $children;
	}

	public function render() {

	$settings = $this->settings;

	$tag = isset( $this->settings['tag'] ) ? \Bricks\Helpers::sanitize_html_tag( $this->settings['tag'], 'div' ) : 'div';

   
    $config = [
      'duration' => isset( $settings['duration'] ) ? intval($settings['duration']) : 150,
      'closeSibling' => isset( $settings['closeSibling'] ),
	  'hashLink' => isset( $settings['hashLink'] ),
	  'scrollOffset' => isset( $settings['scrollOffset'] ) ? intval($settings['scrollOffset']) : 0,
	  'faqSchema' => isset( $settings['faqSchema'] ),
	  'conditional' =>  isset( $settings['maybeConditional'] ),
	  'navKeys' => isset( $settings['navKeys'] ) ? 'enable' === $settings['navKeys'] : true
    ];

    if ( isset( $settings['expandFirstItem'] ) ) {
      $config += [ 'expandFirst' => isset( $settings['expandFirstItem'] ) ];
    }

    if ( isset( $settings['expandAll'] ) ) {
      $config += [ 'expandAll' => isset( $settings['expandAll'] ) ];
    }




	if ( method_exists('\Bricks\Query','is_any_looping') ) {

		$query_id = \Bricks\Query::is_any_looping();

		if ( $query_id ) {

			if ( BricksExtras\Helpers::get_bricks_looping_parent_query_id_by_level(2) ) {
				$loopIndex = \Bricks\Query::get_query_for_element_id( \Bricks\Query::get_query_element_id( BricksExtras\Helpers::get_bricks_looping_parent_query_id_by_level(2) ) )->loop_index . '_' . \Bricks\Query::get_query_for_element_id( \Bricks\Query::get_query_element_id( BricksExtras\Helpers::get_bricks_looping_parent_query_id_by_level(1) ) )->loop_index . '_' . \Bricks\Query::get_loop_index();
				$loopIndexHash = ( \Bricks\Query::get_query_for_element_id( \Bricks\Query::get_query_element_id( BricksExtras\Helpers::get_bricks_looping_parent_query_id_by_level(2) ) )->loop_index + 1 ) . '_' . ( \Bricks\Query::get_query_for_element_id( \Bricks\Query::get_query_element_id( BricksExtras\Helpers::get_bricks_looping_parent_query_id_by_level(1) ) )->loop_index + 1 ) . '_' . ( \Bricks\Query::get_loop_index() + 1 );
			} else {
				if ( BricksExtras\Helpers::get_bricks_looping_parent_query_id_by_level(1) ) {
					$loopIndex = \Bricks\Query::get_query_for_element_id( \Bricks\Query::get_query_element_id( BricksExtras\Helpers::get_bricks_looping_parent_query_id_by_level(1) ) )->loop_index . '_' . \Bricks\Query::get_loop_index();
					$loopIndexHash = ( \Bricks\Query::get_query_for_element_id( \Bricks\Query::get_query_element_id( BricksExtras\Helpers::get_bricks_looping_parent_query_id_by_level(1) ) )->loop_index + 1 ) . '_' . ( \Bricks\Query::get_loop_index() + 1 );
					
				} else {
					$loopIndex = \Bricks\Query::get_loop_index();
					$loopIndexHash = \Bricks\Query::get_loop_index() + 1;
				}
			}			

			$config += [ 'loopIndex' => $loopIndexHash ];

			$this->set_attribute( '_root', 'data-x-id', $this->id . '_' . $loopIndex );
			
		} else {
			$this->set_attribute( '_root', 'data-x-id', $this->id );
		}

	} 




    $this->set_attribute( '_root', 'class', 'x-accordion' );
	$this->set_attribute( '_root', 'data-x-accordion', wp_json_encode($config) );
    
		
	$output = "<" .$tag. " {$this->render_attributes( '_root' )}>";

	// Render children elements (= individual items)
	$output .= \Bricks\Frontend::render_children( $this );

	$output .= "</" .$tag. ">";

	echo $output;

		if ( isset( $settings['faqSchema'] ) ) {

			wp_enqueue_script( 'x-faq-schema', BRICKSEXTRAS_URL . 'components/assets/js/' . \BricksExtras\Helpers::maybeMinifyScripts('faq-schema') . '.js', '', '1.0.0', true );

			add_filter( 'bricks/body/attributes', function( $attributes ) {
					
				$attributes['itemscope'] = '';
				$attributes['itemtype'] = 'https://schema.org/FAQPage';
			  
				return $attributes;

			} );


		}

	}


	public static function render_builder() {  ?>

		<script type="text/x-template" id="tmpl-bricks-element-xproaccordion">
			<component
			class="x-accordion"
			:data-x-expand="settings.expandFirstItem ? 'expandFirst' : ''"
			:data-x-duration="settings.duration"
			:class="settings.builderHidden ? 'x-accordion_builder-collapse' : ''"
			>

			<bricks-element-children :element="element" />
			
			</component>	
		</script>

	<?php }



  public function enqueue_scripts() {
    wp_enqueue_script( 'x-frontend', BRICKSEXTRAS_URL . 'components/assets/js/' . \BricksExtras\Helpers::maybeMinifyScripts('frontend') . '.js', '', '1.0.0', true );
    wp_enqueue_script( 'x-accordion', BRICKSEXTRAS_URL . 'components/assets/js/' . \BricksExtras\Helpers::maybeMinifyScripts('proaccordion') . '.js', ['x-frontend'], '1.1.1', true );

	if (! \BricksExtras\Helpers::elementCSSAdded($this->name) ) {
		wp_enqueue_style( 'x-accordion', BRICKSEXTRAS_URL . 'components/assets/css/proaccordion.css', [], '' );
	}

  }

}
