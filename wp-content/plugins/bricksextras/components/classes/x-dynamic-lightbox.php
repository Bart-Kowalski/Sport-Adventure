<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

use BricksExtras\Helpers;

class X_Dynamic_Lightbox extends \Bricks\Element {

  // Element properties
    public $category     = 'extras';
	public $name         = 'xdynamiclightbox';
	public $icon         = 'ti-layout-media-center-alt';
	public $css_selector = '';
	public $nestable = true;
	//public $scripts      = ['xDynamiclightbox'];
	

  // Methods: Builder-specific
  public function get_label() {
	return esc_html__( 'Dynamic Lightbox', 'extras' );
  }
  public function set_control_groups() {

	$this->control_groups['grouping'] = [
		'title' => esc_html__( 'Grouping', 'extras' ),
		'tab' => 'content',
		'required' => ['lightboxContent', '!=', ['calendar']],
	];

	$this->control_groups['config'] = [
		'title' => esc_html__( 'Config', 'extras' ),
		'tab' => 'content',
	];

	$this->control_groups['styling'] = [
		'title' => esc_html__( 'Styling UI', 'extras' ),
		'tab' => 'content',
	];

	$this->control_groups['inner_layout'] = [
		'title' => esc_html__( 'Inner content', 'extras' ),
		'tab' => 'content',
	];

	$this->control_groups['animation'] = [
		'title' => esc_html__( 'Animation', 'extras' ),
		'tab' => 'content',
	];

	$this->control_groups['captions'] = [
		'title' => esc_html__( 'Captions', 'extras' ),
		'tab' => 'content',
		'required' => ['lightboxContent', '!=', ['inline','iframe']],
	];

  }


  public function set_controls() {

	$this->controls['builderHidden'] = [
		'tab'   => 'content',
		'inline' => true,
		'small' => true,
		'label' => esc_html__( 'Hide in builder', 'bricks' ),
		'type'  => 'checkbox',
	];

	$this->controls['builderHidden_sep'] = [
		'tab'   => 'content',
		'type'  => 'separator',
	];


	$this->controls['lightboxContent'] = [
		'tab'         => 'content',
		'label'       => esc_html__( 'Lightbox Content', 'bricks' ),
		'type'        => 'select',
		'options'     => [
			'inline' => esc_html__( 'Nest elements', 'bricks' ),
			'iframe' => esc_html__( 'iFrame', 'bricks' ),
			'gallery' => esc_html__( 'Gallery', 'bricks' ),
			'manual' => esc_html__( 'Manual (links)', 'bricks' ),
			//'calendar' => esc_html__( 'Calendar', 'bricks' ),
		],
		'inline'      => true,
		'default'   => 'inline',
		'clearable' => false,
	];

	$this->controls['gallerySep'] = [
		'tab'   => 'content',
		'type'  => 'separator',
		'required' => ['lightboxContent', '=', ['gallery']],
		'description' => esc_html__( 'Select images to include in the lightbox, or populate via dynamic data.', 'bricks' ),
	];

	$this->controls['imageGallery'] = [
		'tab'  => 'content',
		'type' => 'image-gallery',
		'required' => ['lightboxContent', '=', ['gallery']],
	];


	$this->controls['linkSelector'] = [
		'tab' => 'content',
		'label' => esc_html__( 'Link selector', 'bricks' ),
		'type' => 'text',
		'tooltip'  => [
			'content'  => 'Make sure the element is actually a link',
			'position' => 'top-left',
		],
		'inline'      => true,
		'placeholder' => '',
		'required' => ['lightboxContent', '=', ['manual','inline']],
		'hasDynamicData' => false,
	];

	$this->controls['sliderSelector'] = [
		'tab' => 'content',
		'label' => esc_html__( 'Slider selector', 'bricks' ),
		'type' => 'text',
		'inline'      => true,
		'placeholder' => '',
		'required' => ['lightboxContent', '=', ['slider']],
		'hasDynamicData' => false,
	];

	$this->controls['contentSource'] = [
		'tab' => 'content',
		'label' => esc_html__( 'iFrame source', 'bricks' ),
		'type' => 'text',
		'inline'      => true,
		'placeholder' => '/',
		'required' => ['lightboxContent', '=', ['iframe']]
	];

	$this->controls['contentSelector'] = [
		'tab' => 'content',
		'label' => esc_html__( 'Iframe selector', 'bricks' ),
		'type' => 'text',
		'inline'      => true,
		'placeholder' => '',
		'description' => esc_html__( 'If iframe source is pages on your site, you can limit the iframe content to one individual selector (leave blank for the whole page)', 'bricksextras' ),
		'required' => ['lightboxContent', '=', ['iframe']]
	];
	
	$templatesList = bricks_is_builder() ? \Bricks\Templates::get_templates_list( [ 'section', 'content' ], get_the_ID() ) : [];
	
	$this->controls['calendarTemplate'] = [
		'tab'         => 'content',
		'inline'      => true,
		'label'       => esc_html__( 'Template', 'bricks' ),
		'type'        => 'select',
		'options'     => $templatesList,
		'searchable'  => true,
		'placeholder' => esc_html__( 'Select template', 'bricks' ),
		'required' => ['lightboxContent', '=', ['calendar']],
		'info' => esc_html__( 'Choose a template for displaying the post content inside a lightbox', 'bricksextras' ),
	];

	$this->controls['backdropsep'] = [
		'tab'   => 'content',
		'type'  => 'separator',
    ];

	/* backdrop */

	$this->controls['backdrop_color'] = [
		'tab' => 'content',
		'label' => esc_html__( 'Backdrop color', 'bricks' ),
		'type' => 'color',
		//'group' => 'backdrop',
		'inline' => true,
		'css' => [
		  [
			'property' => 'background-color',
			'selector' => '.goverlay',
		  ]
		],
	  ];

	  /* style lightbox */
	

	$this->controls['lightboxContentHeight'] = [
		'tab' => 'content',
		'label' => esc_html__( 'Content height', 'extras' ),
		'small'		  => true,
		'inline' => true,
		'type' => 'number',
		'units'    => true,
		/*'css' => [
		  [
			'selector' => '.gslide-inline',  
			'property' => 'height',
			'important' => true,
		  ],
		],*/
		//'required' => ['lightboxContent', '!=', 'manual']
	  ];

	  $this->controls['lightboxContentMaxHeight'] = [
		'tab' => 'content',
		'label' => esc_html__( 'Content max-height', 'extras' ),
		'small'		  => true,
		'inline' => true,
		'type' => 'number',
		'units'    => true,
		'css' => [
		  [
			'selector' => '',  
			'property' => '--x-lightbox-maxheight',
			'important' => true,
		  ],
		],
		'placeholder' => '100vh'
	  ];

	  $this->controls['lightboxContentWidth'] = [
		'tab' => 'content',
		'label' => esc_html__( 'Content width', 'extras' ),
		'description' => esc_html__( '(If video /image content, the height is dynamic)', 'bricksextras' ),
		'small'		  => true,
		'inline' => true,
		'type' => 'number',
		'units'    => true,
		'css' => [
		  [
			'selector' => '.gslide-inline',  
			'property' => 'width',
			'important' => true,
		  ],
		  /*
		  [
			'selector' => '.gcontainer .description-bottom',  
			'property' => 'width',
			'important' => true,
		  ],
		  */
		  [
			'selector' => '.ginner-container',  
			'property' => 'max-width',
			'important' => true,
		  ],
		],
	  ];

	  $this->controls['configVidSep'] = [
		'tab'   => 'content',
		'type'  => 'separator',
    ];

	  $this->controls['autoplayVideos'] = [
		'tab'         => 'content',
		'label'       => esc_html__( 'Autoplay video', 'bricks' ),
		'type'        => 'select',
		'options'     => [
			'true' => esc_html__( 'Enable', 'bricks' ),
			'false' => esc_html__( 'Disable', 'bricks' ),
		],
		'inline'      => true,
		'placeholder' => esc_html__( 'Enable', 'bricks' ),
		'clearable' => false,
		'required' => ['lightboxContent', '=', 'manual']
	];

	$this->controls['videosWidth'] = [
		'tab'      => 'content',
		'label'    => esc_html__( 'Max video width', 'bricks' ),
		'type'     => 'number',
		'placeholder'    => esc_html__( '960px', 'bricks' ),
		'units'    => true,
		'required' => ['lightboxContent', '=', 'manual'],
		'tooltip'     => [
			'content'  => 'If needs to be smaller than set content width',
			'position' => 'top-left',
		],
	];

	$this->controls['videosUIcolor'] = [
		'tab'      => 'content',
		'label'    => esc_html__( 'Video UI Color', 'bricks' ),
		'type'     => 'color',
		'placeholder'    => esc_html__( '', 'bricks' ),
		'required' => ['lightboxContent', '=', 'manual'],
		'css' => [
			[
			  'selector' => '',  
			  'property' => '--plyr-color-main',
			],
		]
	];

	$this->controls['imagesWidth'] = [
		'tab'      => 'content',
		'label'    => esc_html__( 'Max image width', 'bricks' ),
		'type'     => 'number',
		'units'    => true,
		'description'     => '(If using manual link mode for images, or for galleries)',
		'css' => [
			[
			  'selector' => '.gslide-image > img',  
			  'property' => 'width',
			  'important' => true,
			],
			[
				'selector' => '.x_lightbox_dummy_content_gallery',
				'property' => 'width',
			    'important' => true,
			]
		  ],
	];


	/* grouping */

	$this->controls['maybeGrouping'] = [
		'tab'   => 'content',
		'group' => 'grouping',
		'inline' => true,
		'small' => true,
		'label' => esc_html__( 'Grouping', 'bricks' ),
		'type'  => 'checkbox',
	];

	$this->controls['maybeLoop'] = [
		'tab'   => 'content',
		'group' => 'grouping',
		'inline' => true,
		'small' => true,
		'label' => esc_html__( 'Loop slides on end', 'bricks' ),
		'type'  => 'checkbox',
		'required' => ['maybeGrouping', '=', true]
	];

	$this->controls['draggable'] = [
		'tab'   => 'content',
		'group' => 'grouping',
		'inline' => true,
		'small' => true,
		'label' => esc_html__( 'Draggable', 'bricks' ),
		'type'  => 'checkbox',
		'required' => ['maybeGrouping', '=', true]
	];


	$this->controls['slideEffect'] = [
		'tab'         => 'content',
		'label'       => esc_html__( 'Slide Effect', 'bricks' ),
		'type'        => 'select',
		'group' => 'grouping',
		'options'     => [
			'slide' => esc_html__( 'Slide', 'bricks' ),
			'fade' => esc_html__( 'Fade', 'bricks' ),
			//'zoom' => esc_html__( 'Zoom', 'bricks' ),
			'none' => esc_html__( 'None', 'bricks' ),
		],
		'inline'      => true,
		'placeholder' => esc_html__( 'Slide', 'bricks' ),
		'clearable' => false,
		'required' => ['maybeGrouping', '=', true]
	];


	


	  /* config */

	$this->controls['closeOnOutsideClick'] = [
		'tab'         => 'content',
		'label'       => esc_html__( 'Click backdrop to close', 'bricks' ),
		'type'        => 'select',
		'group' => 'config',
		'options'     => [
			'true' => esc_html__( 'Enable', 'bricks' ),
			'false' => esc_html__( 'Disable', 'bricks' ),
		],
		'inline'      => true,
		'placeholder' => esc_html__( 'Enable', 'bricks' ),
		'clearable' => false,
	];

	$this->controls['keyboardNavigation'] = [
		'tab'         => 'content',
		'label'       => esc_html__( 'Keyboard Navigation', 'bricks' ),
		'type'        => 'select',
		'group' => 'config',
		'options'     => [
			'true' => esc_html__( 'Enable', 'bricks' ),
			'false' => esc_html__( 'Disable', 'bricks' ),
		],
		'inline'      => true,
		'placeholder' => esc_html__( 'Enable', 'bricks' ),
		'clearable' => false,
	];

	$this->controls['hashToClose'] = [
		'tab' => 'content',
		'group' => 'config',
		'label' => esc_html__( 'Hash link to close', 'bricks' ),
		'type' => 'select',
		'options' => [
		  'true' => esc_html__( 'Enable', 'bricks' ),
		  'false' => esc_html__( 'Disable', 'bricks' ),
		],
		'inline'      => true,
		'placeholder' => esc_html__( 'Disable', 'bricks' ),
		//'clearable' => false,
	  ];

	  $this->controls['returnFocus'] = [
		'tab' => 'content',
		'group' => 'config',
		'label' => esc_html__( 'Return focus to trigger on close', 'bricks' ),
		'type' => 'select',
		'options' => [
		  'true' => esc_html__( 'Enable', 'bricks' ),
		  'false' => esc_html__( 'Disable', 'bricks' ),
		],
		'inline'      => true,
		'placeholder' => esc_html__( 'Enable', 'bricks' ),
		//'clearable' => false,
	  ];

	  $this->controls['allowID'] = [
		'tab' => 'content',
		'group' => 'config',
		'info' => 'Disable if you only want the class styles to be moved over the lightbox',
		'label' => esc_html__( 'Allow ID styling to be added', 'bricks' ),
		'type' => 'select',
		'options' => [
		  'true' => esc_html__( 'Enable', 'bricks' ),
		  'false' => esc_html__( 'Disable', 'bricks' ),
		],
		'inline'      => true,
		'placeholder' => esc_html__( 'Enable', 'bricks' ),
		//'clearable' => false,
	  ];

	  $this->controls['closeButton'] = [
		'tab'         => 'content',
		'label'       => esc_html__( 'Close button', 'bricks' ),
		'type'        => 'select',
		'group' => 'config',
		'options'     => [
			'true' => esc_html__( 'Enable', 'bricks' ),
			'false' => esc_html__( 'Disable', 'bricks' ),
		],
		'inline'      => true,
		'placeholder' => esc_html__( 'Enable', 'bricks' ),
		'clearable' => false,
	];

	$this->controls['configSep'] = [
		'tab'   => 'content',
		'group' => 'config',
		'type'  => 'sep',
		'description' => esc_html__( 'To add a custom close button inside the lightbox content, add the attribute name "data-x-lightbox-close" to your chosen element.', 'bricksextras' ),
    ];

	/*
	
	*/


	/* inner layout */

	$innerContent = '.x-dynamic-lightbox_content';

	// Display

	  $this->controls['_flexWrap'] = [
		  'tab'         => 'content',
		  'label'       => esc_html__( 'Flex wrap', 'bricks' ),
		  'group'		  => 'inner_layout',
		  'tooltip'     => [
			  'content'  => 'flex-wrap',
			  'position' => 'top-left',
		  ],
		  'type'        => 'select',
		  'options'  => [
			'nowrap'       => esc_html__( 'No wrap', 'bricks' ),
			'wrap'         => esc_html__( 'Wrap', 'bricks' ),
			'wrap-reverse' => esc_html__( 'Wrap reverse', 'bricks' ),
		],
		  'inline'      => true,
		  'css'         => [
			  [
				  'property' => 'flex-wrap',
				  'selector' => $innerContent,
			  ],
		  ],
		  'placeholder' => esc_html__( 'No wrap', 'bricks' ),
		  //'required'    => [ '_display', '=', 'flex' ],
	  ];

	  $this->controls['_direction'] = [
		  'tab'      => 'content',
		  'label'    => esc_html__( 'Direction', 'bricks' ),
		  'group'		  => 'inner_layout',
		  'tooltip'  => [
			  'content'  => 'flex-direction',
			  'position' => 'top-left',
		  ],
		  'type'     => 'direction',
		  'css'      => [
			  [
				  'property' => 'flex-direction',
				  'selector' => $innerContent,
			  ],
		  ],
		  'inline'   => true,
		  'rerender' => true,
		  //'required' => [ '_display', '=', 'flex' ],
	  ];

	  $this->controls['_justifyContent'] = [
		  'tab'      => 'content',
		  'label'    => esc_html__( 'Align main axis', 'bricks' ),
		  'group'		  => 'inner_layout',
		  'tooltip'  => [
			  'content'  => 'justify-content',
			  'position' => 'top-left',
		  ],
		  'type'     => 'justify-content',
		  'css'      => [
			  [
				  'property' => 'justify-content',
				  'selector' => $innerContent,
			  ],
		  ],
		  //'required' => [ '_display', '=', 'flex' ],
	  ];

	  $this->controls['_alignItems'] = [
		  'tab'      => 'content',
		  'label'    => esc_html__( 'Align cross axis', 'bricks' ),
		  'group'		  => 'inner_layout',
		  'tooltip'  => [
			  'content'  => 'align-items',
			  'position' => 'top-left',
		  ],
		  'type'     => 'align-items',
		  'css'      => [
			  [
				  'property' => 'align-items',
				  'selector' => $innerContent,
			  ],
		  ],
		  //'required' => [ '_display', '=', 'flex' ],
	  ];

	  $this->controls['_columnGap'] = [
		  'tab'      => 'content',
		  'label'    => esc_html__( 'Column gap', 'bricks' ),
		  'group'		  => 'inner_layout',
		  'type'     => 'number',
		  'units'    => true,
		  'css'      => [
			  [
				  'property' => 'column-gap',
				  'selector' => $innerContent,
			  ],
		  ],
		  'info'     => sprintf( __( 'Current browser support: %s (no IE). Use margins for max. browser support.', 'bricks' ), '89%' ),
		  //'required' => [ '_display', '=', 'flex' ],
	  ];

	  $this->controls['_rowGap'] = [
		  'tab'      => 'content',
		  'label'    => esc_html__( 'Row gap', 'bricks' ),
		  'group'		  => 'inner_layout',
		  'type'     => 'number',
		  'units'    => true,
		  'css'      => [
			  [
				  'property' => 'row-gap',
				  'selector' => $innerContent,
			  ],
		  ],
		  'info'     => sprintf( __( 'Current browser support: %s (no IE). Use margins for max. browser support.', 'bricks' ), '89%' ),
		  //'required' => [ '_display', '=', 'flex' ],
	  ];

	  $this->controls['content_padding'] = [
		'tab' => 'content',
		'label' => esc_html__( 'Content padding', 'bricks' ),
		'type' => 'dimensions',
		'group' => 'inner_layout',
		'css' => [
		  [
			'property' => 'padding',
			'selector' => '.gslide-inline .ginlined-content',
		  ]
		],
		'placeholder' => [
		  'top' => '40px',
		  'right' => '40px',
		  'bottom' => '40px',
		  'left' => '40px',
		],
	  ];

	  $this->controls['slide_padding'] = [
		'tab' => 'content',
		'label' => esc_html__( 'Slide padding', 'bricks' ),
		'type' => 'dimensions',
		'tooltip'  => [
			'content'  => 'To move the content away from the edges of the viewport',
			'position' => 'top-left',
		],
		'group' => 'inner_layout',
		'css' => [
		  [
			'property' => 'padding',
			'selector' => '.gslide-inner-content',
		  ]
		],
	  ];

	  
	$this->controls['contentBackground'] = [
		'tab'    => 'content',
		'group'  => 'inner_layout',
		'type'   => 'background',
		'label'  => esc_html__( 'Background', 'bricks' ),
		'css'    => [
			[
				'property' => 'background',
				'selector' => '.gslide-inline',
			],
		],
		'required' => ['lightboxContent', '=', ['inline']],
	];

	$this->controls['contentBorder'] = [
		'tab'    => 'content',
		'group'  => 'inner_layout',
		'type'   => 'border',
		'label'  => esc_html__( 'Border', 'bricks' ),
		'css'    => [
			[
				'property' => 'border',
				'selector' => '.ginner-container .gslide-inline',
			],
			[
				'property' => 'border',
				'selector' => '.ginner-container .gslide-external',
			],
		],
	];

	$this->controls['contentOverflow'] = [
		'tab'         => 'content',
		'label'       => esc_html__( 'Overflow', 'bricks' ),
		'type'        => 'select',
		'group' => 'inner_layout',
		'options'     => [
			'hidden' => esc_html__( 'Hidden', 'bricks' ),
			'Auto' => esc_html__( 'Auto', 'bricks' ),
		],
		'css'    => [
			[
				'property' => 'overflow',
				'selector' => '.ginner-container',
			],
		],
		'inline'      => true,
		'placeholder' => esc_html__( 'Auto', 'bricks' ),
	];


	/* styling ui */

	$this->controls['closeButtonSep'] = [
		'tab'   => 'content',
		'group' => 'styling',
		'type'  => 'separator',
		'label' => esc_html__( 'Close button', 'bricks' ),
		'required' => ['closeButton', '!=', 'false']
	  ];

	  $button_selector = '.gcontainer .gclose';

	$this->controls['closeColor'] = [
		'tab'      => 'content',
		'group' => 'styling',
		'label'    => esc_html__( 'Color', 'bricks' ),
		'type'     => 'color',
		'css'      => [
			[
				'property' => 'color',
				'selector' => $button_selector . ' path',
			],
		],
		'required' => ['closeButton', '!=', 'false']
	];

	$this->controls['closeBackgroundColor'] = [
		'tab'   => 'content',
		'group' => 'styling',
		'label' => esc_html__( 'Background color', 'bricks' ),
		'type'  => 'color',
		'css'   => [
			[
				'property' => 'background-color',
				'selector' => $button_selector,
			],
		],
		'required' => ['closeButton', '!=', 'false']
	];

	$this->controls['closeBorder'] = [
		'tab'   => 'content',
		'label' => esc_html__( 'Border', 'bricks' ),
		'group' => 'styling',
		'type'  => 'border',
		'css'   => [
			[
				'property' => 'border',
				'selector' => $button_selector,
			],
		],
		'required' => ['closeButton', '!=', 'false']
	];

	$this->controls['closeBoxShadow'] = [
		'tab'   => 'content',
		'label' => esc_html__( 'Box shadow', 'bricks' ),
		'group' => 'styling',
		'type'  => 'box-shadow',
		'css'   => [
			[
				'property' => 'box-shadow',
				'selector' => $button_selector,
			],
		],
		'required' => ['closeButton', '!=', 'false']
	];


	$this->controls['closeHeight'] = [
		'tab'      => 'content',
		'group'    => 'styling',
		'label'    => esc_html__( 'Button height', 'bricks' ),
		'type'     => 'number',
		'placeholder'    => esc_html__( '35', 'bricks' ),
		'units'    => true,
		'css'      => [
			[
				'property' => 'height',
				'selector' => $button_selector,
			],
		],
		'required' => ['closeButton', '!=', 'false']
	];

	$this->controls['closeWidth'] = [
		'tab'      => 'content',
		'group'    => 'styling',
		'label'    => esc_html__( 'Button width', 'bricks' ),
		'type'     => 'number',
		'placeholder'    => esc_html__( '35', 'bricks' ),
		'units'    => true,
		'css'      => [
			[
				'property' => 'width',
				'selector' => $button_selector,
			],
		],
		'required' => ['closeButton', '!=', 'false']
	];


	$this->controls['closeiconSize'] = [
		'tab'      => 'content',
		'group'    => 'styling',
		'label'    => esc_html__( 'Icon size', 'bricks' ),
		'type'     => 'number',
		'placeholder'    => esc_html__( '18', 'bricks' ),
		'units'    => true,
		'css'      => [
			[
				'property' => 'width',
				'selector' => $button_selector . ' svg',
			],
		],
		'required' => ['closeButton', '!=', 'false']
	];

	  $this->controls['closeMargin'] = [
		'tab' => 'content',
		'group' => 'styling',
		'label' => esc_html__( 'Margin', 'bricks' ),
		'type' => 'dimensions',
		'css' => [
		  [
			'property' => 'margin',
			'selector' => $button_selector,
		  ]
		],
		'placeholder' => [
		  'top' => '20px',
		  'right' => '20px',
		  'bottom' => '20px',
		  'left' => '20px',
		],
		'required' => ['closeButton', '!=', 'false']
	  ];


	  $this->controls['navSep'] = [
		'tab'   => 'content',
		'group' => 'styling',
		'type'  => 'separator',
		'label' => esc_html__( 'Navigation arrows', 'bricks' ),
		'required' => ['maybeGrouping', '=', true]
	  ];

	  $nav_selector = '.gcontainer .gbtn:not(.gclose)';
	  $nav_prev_selector = '.gcontainer .gbtn.gprev';
	  $nav_next_selector = '.gcontainer .gbtn.gnext';

	  $this->controls['navHide'] = [
		'tab'   => 'content',
		'group' => 'styling',
		'label' => esc_html__( 'Hide arrows', 'bricks' ),
		'type'  => 'checkbox',
	]; 

	$this->controls['navColor'] = [
		'tab'      => 'content',
		'group' => 'styling',
		'label'    => esc_html__( 'Color', 'bricks' ),
		'type'     => 'color',
		'css'      => [
			[
				'property' => 'color',
				'selector' => $nav_selector . ' path',
			],
		],
	];

	$this->controls['navBackgroundColor'] = [
		'tab'   => 'content',
		'group' => 'styling',
		'label' => esc_html__( 'Background color', 'bricks' ),
		'type'  => 'color',
		'css'   => [
			[
				'property' => 'background-color',
				'selector' => $nav_selector,
			],
		],
	];

	$this->controls['navBorder'] = [
		'tab'   => 'content',
		'label' => esc_html__( 'Border', 'bricks' ),
		'group' => 'styling',
		'type'  => 'border',
		'css'   => [
			[
				'property' => 'border',
				'selector' => $nav_selector,
			],
		],
	];

	$this->controls['navBoxShadow'] = [
		'tab'   => 'content',
		'label' => esc_html__( 'Box shadow', 'bricks' ),
		'group' => 'styling',
		'type'  => 'box-shadow',
		'css'   => [
			[
				'property' => 'box-shadow',
				'selector' => $nav_selector,
			],
		],
	];


	$this->controls['navHeight'] = [
		'tab'      => 'content',
		'group'    => 'styling',
		'label'    => esc_html__( 'Button height', 'bricks' ),
		'type'     => 'number',
		'placeholder'    => esc_html__( '50', 'bricks' ),
		'units'    => true,
		'css'      => [
			[
				'property' => 'height',
				'selector' => $nav_selector,
			],
		],
	];

	$this->controls['navWidth'] = [
		'tab'      => 'content',
		'group'    => 'styling',
		'label'    => esc_html__( 'Button width', 'bricks' ),
		'type'     => 'number',
		'placeholder'    => esc_html__( '40', 'bricks' ),
		'units'    => true,
		'css'      => [
			[
				'property' => 'width',
				'selector' => $nav_selector,
			],
		],
	];

	$this->controls['naviconSize'] = [
		'tab'      => 'content',
		'group'    => 'styling',
		'label'    => esc_html__( 'Icon size', 'bricks' ),
		'type'     => 'number',
		'placeholder'    => esc_html__( '25', 'bricks' ),
		'units'    => true,
		'css'      => [
			[
				'property' => 'width',
				'selector' => $nav_selector . ' svg',
			],
		],
	];

	  $this->controls['navPrevMargin'] = [
		'tab' => 'content',
		'group' => 'styling',
		'label' => esc_html__( 'Prev Margin', 'bricks' ),
		'type' => 'dimensions',
		'css' => [
		  [
			'property' => 'margin',
			'selector' => $nav_prev_selector,
		  ]
		],
		'placeholder' => [
		  'top' => '0',
		  'right' => '0',
		  'bottom' => '0',
		  'left' => '20px',
		],
	  ];

	  $this->controls['navNextMargin'] = [
		'tab' => 'content',
		'group' => 'styling',
		'label' => esc_html__( 'Next Margin', 'bricks' ),
		'type' => 'dimensions',
		'css' => [
		  [
			'property' => 'margin',
			'selector' => $nav_next_selector,
		  ]
		],
		'placeholder' => [
		  'top' => '0',
		  'right' => '20px',
		  'bottom' => '0',
		  'left' => '0',
		],
	  ];


	  
	  /* captions */

	  $captionsSelector = '.gdesc-inner';

	  

	  $this->controls['maybeCaptions'] = [
		'tab'   => 'content',
		'inline' => true,
		'small' => true,
		'group' => 'captions',
		'label' => esc_html__( 'Enable captions', 'bricks' ),
		'type'  => 'checkbox',
	];

	$this->controls['captionPosition'] = [
		'tab'         => 'content',
		'label'       => esc_html__( 'Caption position', 'bricks' ),
		'group' => 'captions',
		'type'        => 'select',
		'options'     => [
			'captionsBottomViewport' => esc_html__( 'Bottom of viewport', 'bricks' ),
			'captionsUnderContent' => esc_html__( 'Under content', 'bricks' ),
		],
		'inline'      => true,
		'placeholder' => esc_html__( 'Bottom of viewport', 'bricks' ),
	];


	$this->controls['captionsTypography'] = [
		'tab'   => 'content',
		'group' => 'captions',
		'label' => esc_html__( 'Typography', 'bricks' ),
		'type'  => 'typography',
		'css'   => [
			[
				'property' => 'font',
				'selector' => '.gdesc-inner .gslide-desc',
			],
		],
	];

	$this->controls['captionsBackground'] = [
		'tab'   => 'content',
		'group' => 'captions',
		'label' => esc_html__( 'Background', 'bricks' ),
		'type'  => 'color',
		'css'   => [
			[
				'property' => 'background-color',
				'selector' => $captionsSelector,
			],
		],
	];

	  $this->controls['captionsPadding'] = [
		'tab' => 'content',
		'group' => 'captions',
		'label' => esc_html__( 'Padding', 'bricks' ),
		'type' => 'dimensions',
		'css' => [
		  [
			'property' => 'padding',
			'selector' => $captionsSelector,
		  ]
		],
	  ];
	  

	 /* preview animations */ 
	 

	  $this->controls['preview_animation'] = [
		'tab'         => 'content',
		'label'       => esc_html__( 'Preview animation', 'bricks' ),
		'description'       => esc_html__( 'Set the start / end positions to control the movement as the lightbox is revealed/closed', 'bricks' ),
		'group' => 'animation',
		'type'        => 'select',
		'options'     => [
			'x-lightbox_preview-start' => esc_html__( 'Start position', 'bricks' ),
			'x-lightbox_preview-open' => esc_html__( 'Open position', 'bricks' ),
			'x-lightbox_preview-end' => esc_html__( 'End position', 'bricks' ),
		],
		'inline'      => true,
		'placeholder' => esc_html__( 'Open', 'bricks' ),
	];

	$this->controls['start_translate_x'] = [
		'tab' => 'content',
		'label' => esc_html__( 'TranslateX', 'bricks' ),
		'type' => 'number',
		'group' => 'animation',
		'units' => true,
		'inline' => true,
		'css' => [
			[
			  'property' => '--x-lightbox-translatex',
			  'selector' => '.gcontainer',
			]
		  ],
		'placeholder' => esc_html__( '0%', 'bricks' ),
		'required' => ['preview_animation', '=', ['x-lightbox_preview-start']]
	  ];

	  $this->controls['start_translate_y'] = [
		'tab' => 'content',
		'label' => esc_html__( 'TranslateY', 'bricks' ),
		'type' => 'number',
		'group' => 'animation',
		'units' => true,
		'inline' => true,
		'css' => [
			[
			  'property' => '--x-lightbox-translatey',
			  'selector' => '.gcontainer',
			]
		  ],
		'placeholder' => esc_html__( '10%', 'bricks' ),
		'required' => ['preview_animation', '=', ['x-lightbox_preview-start']]
	  ];

	  $this->controls['start_scale'] = [
		'tab' => 'content',
		'label' => esc_html__( 'Scale', 'extras' ),
		'type' => 'number',
		'group' => 'animation',
		'css' => [
		  [
			'selector' => '.gcontainer',  
			'property' => '--x-lightbox-scale',
		  ],
		],
		'inline' => true,
		'units' => false,
		'placeholder' => esc_html__( '1', 'bricks' ),
		'required' => ['preview_animation', '=', ['x-lightbox_preview-start']]
	  ];


	  $this->controls['end_translate_x'] = [
		'tab' => 'content',
		'label' => esc_html__( 'TranslateX', 'bricks' ),
		'type' => 'number',
		'group' => 'animation',
		'units' => true,
		'inline' => true,
		'css' => [
			[
			  'property' => '--x-lightbox-close-translatex',
			  'selector' => '.gcontainer',
			]
		  ],
		'placeholder' => esc_html__( '0%', 'bricks' ),
		'required' => ['preview_animation', '=', ['x-lightbox_preview-end']]
	  ];

	  $this->controls['end_translate_y'] = [
		'tab' => 'content',
		'label' => esc_html__( 'TranslateY', 'bricks' ),
		'type' => 'number',
		'group' => 'animation',
		'units' => true,
		'inline' => true,
		'css' => [
			[
			  'property' => '--x-lightbox-close-translatey',
			  'selector' => '.gcontainer',
			]
		  ],
		'placeholder' => esc_html__( '-5%', 'bricks' ),
		'required' => ['preview_animation', '=', ['x-lightbox_preview-end']]
	  ];

	  $this->controls['end_scale'] = [
		'tab' => 'content',
		'label' => esc_html__( 'Scale', 'extras' ),
		'type' => 'number',
		'group' => 'animation',
		'css' => [
		  [
			'selector' => '.gcontainer',  
			'property' => '--x-lightbox-close-scale',
		  ],
		],
		//'inline' => true,
		'units' => false,
		'min' => 0,
		'max' => 3,
		'step' => '0.1', // Default: 1
		'placeholder' => esc_html__( '1', 'bricks' ),
		'required' => ['preview_animation', '=', ['x-lightbox_preview-end']]
	  ];

	  $this->controls['reduce_motion'] = [
		'tab'   => 'content',
		//'inline' => true,
		'group' => 'animation',
		//'default' => 'false',
		'placeholder' => esc_html__( 'Use animation', 'bricks' ),
		'label' => esc_html__( "If 'Reduce motion' enabled", 'bricks' ),
		'type'  => 'select',
		'options' => [
			'fade' => esc_html__( 'Fade', 'bricks' ),
			'animate' => esc_html__( 'Use animation', 'bricks' ),
			'notransition' => esc_html__( 'No transition', 'bricks' )
		]
	];


	}

  // Methods: Frontend-specific
  public function enqueue_scripts() {

	wp_enqueue_script( 'x-dynamic-lightbox', BRICKSEXTRAS_URL . 'components/assets/js/' . \BricksExtras\Helpers::maybeMinifyScripts('dynamiclightbox') . '.js', '', '1.2.3', true );

	wp_localize_script(
		'x-dynamic-lightbox',
		'xDynamicLightbox',
		[
			'Instances' => [],
			'plyrDir' 	=> BRICKSEXTRAS_URL . 'components/assets/plyr/',
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'bricksextrasURL' => BRICKSEXTRAS_URL,
		]
	);

	if (! \BricksExtras\Helpers::elementCSSAdded($this->name) ) {
		wp_enqueue_style( 'x-dynamic-lightbox', BRICKSEXTRAS_URL . 'components/assets/css/dynamiclightbox.css', [], '' );
	}

 } 

 public function get_normalized_image_settings( $settings ) {
	$items = isset( $settings['imageGallery'] ) ? $settings['imageGallery'] : [];

	$size = ! empty( $items['size'] ) ? $items['size'] : BRICKS_DEFAULT_IMAGE_SIZE;

	// Dynamic Data
	if ( ! empty( $items['useDynamicData'] ) ) {
		$items['images'] = [];

		$images = $this->render_dynamic_data_tag( $items['useDynamicData'], 'image' );

		if ( is_array( $images ) ) {
			foreach ( $images as $image_id ) {
				$items['images'][] = [
					'id'   => $image_id,
					'full' => wp_get_attachment_image_url( $image_id, 'full' ),
					'url'  => wp_get_attachment_image_url( $image_id, $size )
				];
			}
		}
	}

	if ( ! isset( $items['images'] ) ) {
		$images = ! empty( $items ) ? $items : [];

		unset( $items );

		$items['images'] = $images;
	}

	// Get 'size' from first image if not set (previous to 1.4-RC)
	$first_image_size = ! empty( $items['images'][0]['size'] ) ? $items['images'][0]['size'] : false;
	$size             = empty( $items['size'] ) && $first_image_size ? $first_image_size : $size;

	// Calculate new image URL if size is not the same as from the Media Library
	if ( $first_image_size && $first_image_size !== $size ) {
		foreach ( $items['images'] as $key => $image ) {
			$items['images'][ $key ]['size'] = $size;
			$items['images'][ $key ]['url']  = wp_get_attachment_image_url( $image['id'], $size );
		}
	}

	$settings['imageGallery'] = $items;

	$settings['imageGallery']['size'] = $size;

	return $settings;
}

  public function render() {

	$gallerySettings = $this->get_normalized_image_settings( $this->settings );
	$images   = ! empty( $gallerySettings['imageGallery']['images'] ) ? $gallerySettings['imageGallery']['images'] : false;
	$size     = ! empty( $gallerySettings['imageGallery']['size'] ) ? $gallerySettings['imageGallery']['size'] : BRICKS_DEFAULT_IMAGE_SIZE;
	$lightboxContentWidth = isset( $this->settings['lightboxContentWidth'] ) ? esc_attr( $this->settings['lightboxContentWidth'] ) : false;
	$lightboxContentHeight = isset( $this->settings['lightboxContentHeight'] ) ? esc_attr( $this->settings['lightboxContentHeight'] ) : false;
	$lightboxContent = isset( $this->settings['lightboxContent'] ) ? $this->settings['lightboxContent'] : 'inline';

	$maybeCaptions = isset( $this->settings['maybeCaptions'] );

	$allowID = isset( $this->settings['allowID'] ) ? $this->settings['allowID'] : 'true';

	/* allow to be changed globally via filter */
	$allowID = apply_filters( 'bricksextras/dynamiclightbox/allow_id_styling', $allowID );

	 $lightbox_config = [
		'contentType' => $lightboxContent,
		'identifier' => $this->id,
		'grouping' => isset( $this->settings['maybeGrouping'] ) ? 'true' : 'false',
		'linkSelector' => isset( $this->settings['linkSelector'] ) ? esc_attr( $this->settings['linkSelector'] ) : 'false',
		'sliderSelector' => isset( $this->settings['sliderSelector'] ) ? esc_attr( $this->settings['sliderSelector'] ) : 'false',
		'contentSource' => isset( $this->settings['contentSource'] ) ? $this->settings['contentSource'] : 'false',
		'contentSelector' => isset( $this->settings['contentSelector'] ) ? esc_attr( $this->settings['contentSelector'] ) : 'false',
		'keyboardNavigation' => !isset( $this->settings['keyboardNavigation'] ) ? true : 'false' !== $this->settings['keyboardNavigation'],
		'hashToClose' => isset( $this->settings['hashToClose'] ) ? $this->settings['hashToClose'] : 'false',
		'returnFocus' => isset( $this->settings['returnFocus'] ) ? $this->settings['returnFocus'] : 'true',
		'captionPosition' => isset( $this->settings['captionPosition'] ) ? $this->settings['captionPosition'] : 'captionsBottomViewport',
		'allowID' => $allowID,
		'rawConfig' => [
			'loop' => isset( $this->settings['maybeLoop'] ) ? true : false,
			'draggable' => isset( $this->settings['draggable'] ) ? true : false,
			'slideEffect' => isset( $this->settings['slideEffect'] ) ? $this->settings['slideEffect'] : 'slide',
			'closeButton' => !isset( $this->settings['closeButton'] ) ? true : 'false' !== $this->settings['closeButton'],
			'closeOnOutsideClick' => !isset( $this->settings['closeOnOutsideClick'] ) ? true : 'false' !== $this->settings['closeOnOutsideClick']
		]
	 ];

	 if ( isset( $this->settings['reduce_motion'] ) ) {
		$lightbox_config += [ 'reduceMotion' => $this->settings['reduce_motion']];
	}

	if ( isset( $this->settings['lightboxContentHeight'] ) ) {
		$lightbox_config += [ 'height' => $lightboxContentHeight];
	} 

	if ( isset( $this->settings['lightboxContentWidth'] ) ) {
		$lightbox_config += [ 'width' => $lightboxContentWidth];
	}

	if ( isset( $this->settings['autoplayVideos'] ) ) {
		$lightbox_config['rawConfig'] += [ 'autoplayVideos' => 'false' !== $this->settings['autoplayVideos'] ];
	}

	if ( isset( $this->settings['videosWidth'] ) ) {
		$lightbox_config['rawConfig'] += [ 'videosWidth' => $this->settings['videosWidth'] ];
	}

	$galleryImageURLs = [];
	$image_caption = '';
	$image_src = '';

	if ( $images ) {

		foreach ( $images as $index => $item ) {
			
			// Get image url
			if ( isset( $item['id'] ) ) {
				$image_src = wp_get_attachment_image_src( $item['id'], $size );
				$image_caption = wp_get_attachment_caption( $item['id'] );
			} elseif ( isset( $item['url'] ) ) {
				$image_src = [ $item['url'], 800, 600 ];
			}

			$galleryImages[] = [$image_src,$image_caption];

		}

		$lightbox_config += ['galleryImages' => $galleryImages];
	}

	$count = 0;

	if ( method_exists('\Bricks\Query','is_any_looping') ) {

		$query_id = \Bricks\Query::is_any_looping();

		if ( $query_id ) {
			$lightbox_config += [ 'isLooping' => \Bricks\Query::get_query_element_id( $query_id ) ];

			if ( BricksExtras\Helpers::get_bricks_looping_parent_query_id_by_level(2) ) {
				$loopIndex = \Bricks\Query::get_query_for_element_id( \Bricks\Query::get_query_element_id( BricksExtras\Helpers::get_bricks_looping_parent_query_id_by_level(2) ) )->loop_index . '_' . \Bricks\Query::get_query_for_element_id( \Bricks\Query::get_query_element_id( BricksExtras\Helpers::get_bricks_looping_parent_query_id_by_level(1) ) )->loop_index . '_' . \Bricks\Query::get_loop_index();
			} else {
				if ( BricksExtras\Helpers::get_bricks_looping_parent_query_id_by_level(1) ) {
					$loopIndex = \Bricks\Query::get_query_for_element_id( \Bricks\Query::get_query_element_id( BricksExtras\Helpers::get_bricks_looping_parent_query_id_by_level(1) ) )->loop_index . '_' . \Bricks\Query::get_loop_index();
				} else {
					$loopIndex = \Bricks\Query::get_loop_index();
				}
			}			

			$lightbox_config += [ 'loopIndex' => $loopIndex ];

			$this->set_attribute( '_root', 'data-x-id', $this->id . '_' . $loopIndex );
			$lightboxContentIdentifier = "x-dynamic-lightbox_content-" . $this->id . "-" . $loopIndex;

			$count = $loopIndex;
			
		} else {
			$this->set_attribute( '_root', 'data-x-id', $this->id );
			$lightboxContentIdentifier = "x-dynamic-lightbox_content-" . $this->id . "-0";
		}

	} 

	$this->set_attribute( '_root', 'data-x-lightbox', wp_json_encode( $lightbox_config ) );

	if ($maybeCaptions) {
		$this->set_attribute( '_root', 'class', 'x-dynamic-lightbox_captions' );
	}

	 $contentSource = get_permalink();

	 if ( 'inline' === $lightboxContent ) {
		$contentSource = "#" . $lightboxContentIdentifier;
	 } elseif( 'iframe' === $lightboxContent ) {
		$contentSource = isset( $this->settings['contentSource'] ) ? esc_attr( $this->settings['contentSource'] ) : '/';
	 }

	 if ( isset( $this->settings['navHide'] ) ) {
		$this->set_attribute( '_root', 'class', 'x-dynamic-lightbox_nav-hide' );
	 }

	 $lightboxContentWidth = isset( $this->settings['lightboxContentWidth'] ) ? $lightboxContentWidth : '600px';

	 echo "<div {$this->render_attributes( '_root' )}>";

	 
	 if ( 'iframe' === $lightboxContent ) {

		echo "<a data-type='external' href=" . $contentSource . " class='x-dynamic-lightbox_link x-dynamic-lightbox_link-" . $this->id . " x-dynamic-lightbox_link-" . $this->id . '-' . $count . "' ";

		$lightboxContentWidth = isset( $this->settings['lightboxContentWidth'] ) ? $lightboxContentWidth : '600px';

		echo "data-width='" . $lightboxContentWidth . "' ";
		echo isset( $this->settings['lightboxContentHeight'] ) ? "data-height='" . $lightboxContentHeight . "' " : "";

		
		echo ">";

			if ( method_exists('\Bricks\Frontend','render_children') ) {
				echo \Bricks\Frontend::render_children( $this );
			} 

		echo "</a>";

	}

	if ( 'calendar' === $lightboxContent ) {

		$template_id = ! empty( $this->settings['calendarTemplate'] ) ? intval( $this->settings['calendarTemplate'] ) : 0;

		echo "<a data-x-template-id='" . $template_id . "' href='#" . esc_attr( $lightboxContentIdentifier ) . "' class='x-dynamic-lightbox_link x-dynamic-lightbox_link-" . $this->id . " x-dynamic-lightbox_link-" . $this->id . '-' . $count . "' ";
		echo "data-width='" . $lightboxContentWidth . "' ";
		echo isset( $this->settings['lightboxContentHeight'] ) ? "data-height='" . $lightboxContentHeight . "' " : "";

		
		echo ">";

		echo "</a>";

		echo "<div class='x-dynamic-lightbox_calendar-content' id='" . esc_attr( $lightboxContentIdentifier )  . "'>";

		echo "</div>";

	}

	if ( 'gallery' === $lightboxContent ) {

		echo "<button tabindex=0 class='x-dynamic-lightbox_link x-dynamic-lightbox_link-" . $this->id . " x-dynamic-lightbox_link-" . $this->id . '-' . $count . "' ";
		echo "data-width='" . $lightboxContentWidth . "' ";
		echo isset( $this->settings['lightboxContentHeight'] ) ? "data-height='" . $lightboxContentHeight . "' " : "";

		
		echo ">";

			if ( method_exists('\Bricks\Frontend','render_children') ) {
				echo \Bricks\Frontend::render_children( $this );
			} 

		echo "</button>";

	}

	if ('inline' === $lightboxContent ) {

		echo "<div class='x-dynamic-lightbox_content' tabindex='0' id='" . esc_attr( $lightboxContentIdentifier ) . "' >";
									
		if ( method_exists('\Bricks\Frontend','render_children') ) {
			echo \Bricks\Frontend::render_children( $this );
		}  

		echo "</div>";

	}

	echo "</div>";
    
  }


  public static function render_builder() { ?>

		<script type="text/x-template" id="tmpl-bricks-element-xdynamiclightbox">
			
		<component 
			class="x-dynamic-lightbox"
			
			:class="[settings.captionPosition ? settings.captionPosition : 'captionsBottomViewport', settings.preview_animation]"
		>
			<bricks-element-children
				v-if="'iframe' === settings.lightboxContent || 'gallery' === settings.lightboxContent"
				:element="element"
			/>
			<div v-show="!settings.builderHidden" class="glightbox-container glightbox-clean" tabindex="-1" role="dialog" aria-hidden="false">
				<div class="gloader visible" style="display: none;"></div>
				<div class="goverlay"></div>
				<div class="gcontainer">
					<div id="glightbox-slider" class="gslider">
						<div class="gslide current loaded">
							<div class="gslide-inner-content">
								<div v-if="'slider' !== settings.lightboxContent" class="ginner-container desc-bottom">
									<div 
									v-if="'iframe' !== settings.lightboxContent && 'manual' !== settings.lightboxContent && 'gallery' !== settings.lightboxContent"
										class="gslide-inline"
										:style="{ height: settings.lightboxContentHeight }"
									>
										<div id="inline-example" class="ginlined-content">
											<div class="x-dynamic-lightbox_content">
												<bricks-element-children
													:element="element"
												/>
											</div>
										</div>
									</div>
									<div v-else class="gslide-media gslide-external">
										<div 
											v-if="'manual' !== settings.lightboxContent && 'gallery' !== settings.lightboxContent" 
											class="x_lightbox_dummy_content"
											:style="{ height: settings.lightboxContentHeight }"
										> iFrame content will appear here on the front end.	
										</div>
										<div 
											v-else-if="'gallery' === settings.lightboxContent" 
											class="x_lightbox_dummy_content x_lightbox_dummy_content_gallery"
										> Images from gallery will appear here on the front end. (Enable grouping for multiple images).	
										</div>
										<div 
											v-else 
											class="x_lightbox_dummy_content"
										> Content from link href will appear here on the front end.	Images/Videos or URLs for iFrames
										</div>	
									</div>

									<div 
										v-if="settings.maybeCaptions && ( 'manual' === settings.lightboxContent || 'gallery' === settings.lightboxContent )"
										class="gslide-description description-bottom">
										<div class="gdesc-inner">
											<div class="gslide-desc">Caption will be here</div>
										</div>
									</div>

								</div>

							</div>
						</div>
						<button v-if="'false' !== settings.closeButton" class="gclose gbtn" aria-label="Close" data-taborder="3"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" xml:space="preserve"><g><g><path d="M505.943,6.058c-8.077-8.077-21.172-8.077-29.249,0L6.058,476.693c-8.077,8.077-8.077,21.172,0,29.249C10.096,509.982,15.39,512,20.683,512c5.293,0,10.586-2.019,14.625-6.059L505.943,35.306C514.019,27.23,514.019,14.135,505.943,6.058z"></path></g></g><g><g><path d="M505.942,476.694L35.306,6.059c-8.076-8.077-21.172-8.077-29.248,0c-8.077,8.076-8.077,21.171,0,29.248l470.636,470.636c4.038,4.039,9.332,6.058,14.625,6.058c5.293,0,10.587-2.019,14.624-6.057C514.018,497.866,514.018,484.771,505.942,476.694z"></path></g></g></svg></button>
						<button v-if="settings.maybeGrouping && 'calendar' !== settings.lightboxContent && !settings.navHide" class="gprev gbtn" aria-label="Previous" data-taborder="2"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 477.175 477.175" xml:space="preserve"><g><path d="M145.188,238.575l215.5-215.5c5.3-5.3,5.3-13.8,0-19.1s-13.8-5.3-19.1,0l-225.1,225.1c-5.3,5.3-5.3,13.8,0,19.1l225.1,225c2.6,2.6,6.1,4,9.5,4s6.9-1.3,9.5-4c5.3-5.3,5.3-13.8,0-19.1L145.188,238.575z"></path></g></svg></button>
						<button v-if="settings.maybeGrouping && 'calendar' !== settings.lightboxContent && !settings.navHide" class="gnext gbtn" aria-label="Next" data-taborder="1"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 477.175 477.175" xml:space="preserve"> <g><path d="M360.731,229.075l-225.1-225.1c-5.3-5.3-13.8-5.3-19.1,0s-5.3,13.8,0,19.1l215.5,215.5l-215.5,215.5c-5.3,5.3-5.3,13.8,0,19.1c2.6,2.6,6.1,4,9.5,4c3.4,0,6.9-1.3,9.5-4l225.1-225.1C365.931,242.875,365.931,234.275,360.731,229.075z"></path></g></svg></button>
					</div>
				</div>
			</div>
		</component>		

		</script>

	<?php }

}