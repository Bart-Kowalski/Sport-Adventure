<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class X_Breadcrumbs extends \Bricks\Element {

  // Element properties
  public $category     = 'extras';
	public $name         = 'xbreadcrumbs';
	public $icon         = 'ti-angle-double-right';
	public $css_selector = '';

  
  public function get_label() {
	  return esc_html__( 'Site Breadcrumbs', 'extras' );
  }
  public function set_control_groups() {

	$notBricksExtras = [
		'rankmath',
		'navxt',
		'allinone',
		'seopress',
		'yoast',
		'slim'
	];

	$this->control_groups['config'] = [
		'title' => esc_html__( 'Configure output', 'extras' ),
		'tab' => 'content',
		'required' =>  [ 'source', '!=', $notBricksExtras ]
	];

	$this->control_groups['configSlim'] = [
		'title' => esc_html__( 'Configure output', 'extras' ),
		'tab' => 'content',
		'required' =>  [ 'source', '=', 'slim' ]
	];

	$this->control_groups['separator'] = [
		'title' => esc_html__( 'Separator', 'extras' ),
		'tab' => 'content',
		'required' =>  [ 'source', '!=', $notBricksExtras ]
	];

	$this->control_groups['breadcrumbPrefix'] = [
		'title' => esc_html__( 'Breadcrumbs Prefix', 'extras' ),
		'tab' => 'content',
	];

	$this->control_groups['linkStyles'] = [
		'title' => esc_html__( 'Link styles', 'extras' ),
		'tab' => 'content',
	];

	$this->control_groups['currentItem'] = [
		'title' => esc_html__( 'Current item', 'extras' ),
		'tab' => 'content',
		'required' =>  [ 'source', '!=', 'allinone' ]
	];

  }

  public function set_controls() {

	$breadcrumbSource = [
		'extras' => esc_html__( 'BricksExtras', 'bricks' ),
		'rankmath' => esc_html__( 'Rank Math', 'bricks' ),
		'navxt' => esc_html__( 'NavXT', 'bricks' ),
		'allinone' => esc_html__( 'All in One SEO', 'bricks' ),
		'seopress' => esc_html__( 'SEOPress Pro', 'bricks' ),
		'yoast' => esc_html__( 'Yoast', 'bricks' ),
		'slim' => esc_html__( 'Slim SEO', 'bricks' ),
	];

	$notBricksExtras = [
		'rankmath',
		'navxt',
		'allinone',
		'seopress',
		'yoast',
		'slim'
	];

	$this->controls['source'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Breadcrumbs source', 'bricks' ),
			'type' => 'select',
			'options' => $breadcrumbSource,
			'clearable' => false,
			'inline' => true,
			'placeholder' => esc_html__( 'BricksExtras', 'bricks' ),
		];

		$this->controls['overallTypography'] = [
			'tab'    => 'content',
			'type'   => 'typography',
			'label'  => esc_html__( 'Typography', 'extras' ),
			'css'    => [
				[
					'property' => 'font',
					'selector' => '',
				],
			],
		];

		$this->controls['listGap'] = [
			'tab' => 'content',
			'label' => esc_html__( 'List gap', 'bricks' ),
			'inline' => true,
			'type' => 'number',
			'units' => true,
			'placeholder' => '5px',
			'hasDynamicData' => false,
			'css'    => [
				[
					'property' => '--x-breadcrumbs-gap',
					'selector' => '',
				],
			],
		  ];  


		$this->controls['maybeLink'] = [
			'tab'   => 'content',
			'label' => esc_html__( 'Breadcrumb links', 'extras' ),
			'type' => 'select',
			'inline'      => true,
			'options' => [
				'enable' => esc_html__( 'Enable', 'bricks' ),
				'disable' => esc_html__( 'Disable', 'bricks' ),
			],
			'placeholder' => esc_html__( 'Enable', 'bricks' ),
			'required' => 
			[
				'source', '=', [
					'navxt',
				]
			]
		];

		$this->controls['maybeReverse'] = [
			'tab'   => 'content',
			'label' => esc_html__( 'Reverse order', 'extras' ),
			'type' => 'select',
			'inline'      => true,
			'options' => [
				'enable' => esc_html__( 'Enable', 'bricks' ),
				'disable' => esc_html__( 'Disable', 'bricks' ),
			],
			'placeholder' => esc_html__( 'Disable', 'bricks' ),
			'required' => 
			[
				'source', '=', [
					'navxt',
				]
			]
		];

		$this->controls['maybeForce'] = [
			'tab'   => 'content',
			'label' => esc_html__( 'Bypass internal caching', 'extras' ),
			'info' => 'Refer to Breadcrumb NavXT plugin docs for info',
			'type' => 'select',
			'inline'      => true,
			'options' => [
				'enable' => esc_html__( 'Enable', 'bricks' ),
				'disable' => esc_html__( 'Disable', 'bricks' ),
			],
			'placeholder' => esc_html__( 'Disable', 'bricks' ),
			'required' => 
			[
				'source', '=', [
					'navxt',
				]
			]
		];



		$this->controls['breadcrumbPrefix'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Prefix', 'bricks' ),
			'inline' => true,
			'type' => 'text',
			'group' => 'breadcrumbPrefix'
		  ];

		  $this->controls['removePrefix'] = [
			'tab'   => 'content',
			'label' => esc_html__( 'Remove prefix on front page', 'bricks' ),
			'type'  => 'checkbox',
			'inline' => true,
			'placeholder' => esc_html__( 'False', 'bricks' ),
			'group' => 'breadcrumbPrefix',
		];

		  $this->controls['prefixTypography'] = [
			'tab'    => 'content',
			'group'  => 'breadcrumbPrefix',
			'type'   => 'typography',
			'label'  => esc_html__( 'Typography', 'extras' ),
			'css'    => [
				[
					'property' => 'font',
					'selector' => '.x-breadcrumbs_prefix',
				],
			],
		];

		  $this->controls['prefixGap'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Prefix gap', 'bricks' ),
			'inline' => true,
			'type' => 'number',
			'units' => true,
			'placeholder' => '10px',
			'css'    => [
				[
					'property' => 'gap',
					'selector' => '',
				],
			],
			'group'  => 'breadcrumbPrefix',
		  ];  


		  $this->controls['prefixBackgroundColor'] = [
			'tab'    => 'content',
			'group'  => 'breadcrumbPrefix',
			'type'   => 'color',
			'label'  => esc_html__( 'Background', 'extras' ),
			'css'    => [
				[
					'property' => 'background-color',
					'selector' => '.x-breadcrumbs_prefix',
				],
			],
		];
	
		$this->controls['prefixBorder'] = [
			'tab'    => 'content',
			'group'  => 'breadcrumbPrefix',
			'type'   => 'border',
			'label'  => esc_html__( 'Border', 'extras' ),
			'css'    => [
				[
					'property' => 'border',
					'selector' => '.x-breadcrumbs_prefix',
				],
			],
		];
	
		$this->controls['prefixBoxShadow'] = [
			'tab'    => 'content',
			'group'  => 'breadcrumbPrefix',
			'label'  => esc_html__( 'Box Shadow', 'extras' ),
			'type'   => 'box-shadow',
			'css'    => [
				[
					'property' => 'box-shadow',
					'selector' => '.x-breadcrumbs_prefix',
				],
			],
		];
		  


		  $this->controls['maybeHome'] = [
			'tab'   => 'content',
			'label' => esc_html__( 'Include home page link', 'extras' ),
			'type' => 'select',
			'inline'      => true,
			'options' => [
				'enable' => esc_html__( 'Enable', 'bricks' ),
				'disable' => esc_html__( 'Disable', 'bricks' ),
			],
			'placeholder' => esc_html__( 'Enable', 'bricks' ),
			'group' => 'config',
		];

		$this->controls['homeURL'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Home URL', 'bricks' ),
			'inline' => true,
			'type' => 'text',
			'placeholder' => '{site_url}',
			'group' => 'config',
			'required' =>  [
				[ 'maybeHome', '!=', 'disable' ]
			]
		  ];

		  $this->controls['homeLabel'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Home label', 'bricks' ),
			'inline' => true,
			'type' => 'text',
			'placeholder' => 'Home',
			'group' => 'config',
			'required' =>  [
				[ 'maybeHome', '!=', 'disable' ]
			]
		  ];

		$this->controls['homeLinkType'] = [
			'tab'   => 'content',
			'label' => esc_html__( 'Home link type', 'extras' ),
			'type' => 'select',
			'inline'      => true,
			'options' => [
				'text' => esc_html__( 'Text', 'bricks' ),
				'icon' => esc_html__( 'Icon', 'bricks' ),
			],
			'placeholder' => esc_html__( 'Text', 'bricks' ),
			'group' => 'config',
			'small' => true,
			'required' =>  [ 'maybeHome', '!=', 'disable' ]
		];

		$homeIconSelector = '.x-breadcrumbs_list-item .screen-reader-text + *';

		  $this->controls['homeIcon'] = [
			'tab'      => 'content',
			'group' => 'config',
			'label'    => esc_html__( 'Home icon', 'bricks' ),
			'type'     => 'icon',
			'css'      => [
			  [
				'selector' => $homeIconSelector
			  ],
			],
			'default'  => [
			  'library' => 'themify',
			  'icon'    => 'ti-home',
			],
			'required' =>  [
				[ 'maybeHome', '!=', 'disable' ],
				[ 'homeLinkType', '=', 'icon' ]
			]
		  ];

		  $this->controls['homeIconBackgroundColor'] = [
			'tab'    => 'content',
			'group'  => 'config',
			'type'   => 'color',
			'label'  => esc_html__( 'Background', 'extras' ),
			'css'    => [
				[
					'property' => 'background-color',
					'selector' => $homeIconSelector
				],
			],
			'required' =>  [
				[ 'maybeHome', '!=', 'disable' ],
				[ 'homeLinkType', '=', 'icon' ]
			]
		];
	
		$this->controls['homeIconBorder'] = [
			'tab'    => 'content',
			'group'  => 'config',
			'type'   => 'border',
			'label'  => esc_html__( 'Border', 'extras' ),
			'css'    => [
				[
					'property' => 'border',
					'selector' => $homeIconSelector
				],
			],
			'required' =>  [
				[ 'maybeHome', '!=', 'disable' ],
				[ 'homeLinkType', '=', 'icon' ]
			]
		];
	
		$this->controls['homeIconBoxShadow'] = [
			'tab'    => 'content',
			'group'  => 'config',
			'label'  => esc_html__( 'Box Shadow', 'extras' ),
			'type'   => 'box-shadow',
			'css'    => [
				[
					'property' => 'box-shadow',
					'selector' => $homeIconSelector
				],
			],
			'required' =>  [
				[ 'maybeHome', '!=', 'disable' ],
				[ 'homeLinkType', '=', 'icon' ]
			]
		];
	
		$this->controls['homeIconPadding'] = [
			'tab'   => 'content',
			'group'  => 'config',
			'label' => esc_html__( 'Icon padding', 'extras' ),
			'type'  => 'dimensions',
			'css'   => [
				[
					'property' => 'padding',
					'selector' => $homeIconSelector
				],
			],
			'required' =>  [
				[ 'maybeHome', '!=', 'disable' ],
				[ 'homeLinkType', '=', 'icon' ]
			]
		];

		  $this->controls['homeIconTypography'] = [
			'tab'    => 'content',
			'type'   => 'typography',
			'label'  => esc_html__( 'Icon Typography', 'extras' ),
			'css'    => [
				[
					'property' => 'font',
					'selector' => '.x-breadcrumbs_list-item .screen-reader-text + *'
				],
			],
			'group' => 'config',
			'required' =>  [
				[ 'maybeHome', '!=', 'disable' ],
				[ 'homeLinkType', '=', 'icon' ]
			]
		];


		 

		  $this->controls['homeSep'] = [
			'tab'   => 'content',
			'group'  => 'styling',
			'type'  => 'separator',
			'group' => 'config'
		];

		  $this->controls['searchPrefix'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Search prefix', 'bricks' ),
			'inline' => true,
			'type' => 'text',
			'default' => "Search results for",
			'group' => 'config'
		  ];

		  $this->controls['tagPrefix'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Tag prefix', 'bricks' ),
			'inline' => true,
			'type' => 'text',
			'default' => "Posts tagged",
			'group' => 'config'
		  ];


		  $this->controls['authorPrefix'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Author Prefix', 'bricks' ),
			'inline' => true,
			'type' => 'text',
			'default' => "Archives for",
			'group' => 'config',
		  ];


		  $this->controls['error404Prefix'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Error 404', 'bricks' ),
			'inline' => true,
			'type' => 'text',
			'placeholder' => '404 Error: Page not found',
			'group' => 'config'
		  ];

		  $this->controls['errorSep'] = [
			'tab'   => 'content',
			'type'  => 'separator',
			'group' => 'config',
		];

		$this->controls['removeTitle'] = [
			'tab'   => 'content',
			'label' => esc_html__( 'Remove current post/page title', 'extras' ),
			'type' => 'select',
			'inline'      => true,
			'options' => [
				'enable' => esc_html__( 'Enable', 'bricks' ),
				'disable' => esc_html__( 'Disable', 'bricks' ),
			],
			'placeholder' => esc_html__( 'Disable', 'bricks' ),
			'group' => 'config',
		];

		$this->controls['truncateMaxLength'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Truncate title (Max characters)', 'bricks' ),
			'inline' => true,
			'type' => 'number',
			'placeholder' => '100',
			'info' => esc_html__( 'Set the max number of characters to display in post/page titles. After will show ...', 'bricks' ),
			'group' => 'config',
			'required' =>  [ 'removeTitle', '!=', 'enable' ]
		  ];
		

		$this->controls['maybeBlog'] = [
			'tab'   => 'content',
			'label' => esc_html__( 'Include posts page link', 'extras' ),
			'type' => 'select',
			'inline'      => true,
			'options' => [
				'enable' => esc_html__( 'Enable', 'bricks' ),
				'disable' => esc_html__( 'Disable', 'bricks' ),
			],
			'placeholder' => esc_html__( 'Disable', 'bricks' ),
			'group' => 'config',
		];

		  $this->controls['taxNesting'] = [
			'tab'   => 'content',
			'label' => esc_html__( 'Nest taxonomies', 'extras' ),
			'type' => 'select',
			'inline'      => true,
			'options' => [
				'enable' => esc_html__( 'Enable', 'bricks' ),
				'disable' => esc_html__( 'Disable', 'bricks' ),
				],
			'placeholder' => esc_html__( 'Enable', 'bricks' ),
			'group' => 'config',
		];

		$this->controls['maybeLinks'] = [
			'tab'   => 'content',
			'label' => esc_html__( 'Enable links', 'extras' ),
			'type' => 'select',
			'inline'      => true,
			'options' => [
				'enable' => esc_html__( 'Enable', 'bricks' ),
				'disable' => esc_html__( 'Disable', 'bricks' ),
				],
			'placeholder' => esc_html__( 'Enable', 'bricks' ),
			'group' => 'config',
			'required' =>  [ 'source', '!=', $notBricksExtras ]
		];

		$this->controls['maybeSchema'] = [
			'tab'   => 'content',
			'label' => esc_html__( 'Schema markup', 'extras' ),
			'type' => 'select',
			'inline'      => true,
			'options' => [
				'enable' => esc_html__( 'Enable', 'bricks' ),
				'disable' => esc_html__( 'Disable', 'bricks' ),
			],
			'placeholder' => esc_html__( 'Enable', 'bricks' ),
			'group' => 'config',
			'required' =>  [ 'source', '!=', $notBricksExtras ]
		];

		$allCategories = $this->getCategories();

		$this->controls['categorySp'] = [
			'tab'   => 'content',
			'type'  => 'separator',
			'group' => 'config',
		];

		$this->controls['maybePostCategory'] = [
			'tab'   => 'content',
			'label' => esc_html__( 'Include Category', 'extras' ),
			'type' => 'select',
			'inline'      => true,
			'options' => [
				'enable' => esc_html__( 'Enable', 'bricks' ),
				'disable' => esc_html__( 'Disable', 'bricks' ),
			],
			'placeholder' => esc_html__( 'Enable', 'bricks' ),
			'group' => 'config',
			'required' =>  [ 'source', '!=', $notBricksExtras ]
		];

		$this->controls['priorityCategory'] = [
			'tab'   => 'content',
			'label' => esc_html__( 'Priority Category', 'extras' ),
			'type' => 'select',
			'inline'      => true,
			'options' => $allCategories,
			'placeholder' => esc_html__( 'Select..', 'bricks' ),
			'group' => 'config',
			'required' =>  [ 'source', '!=', $notBricksExtras ]
		];

		$this->controls['excludeCategories'] = [
			'tab'   => 'content',
			'label' => esc_html__( 'Exclude Categories', 'extras' ),
			'type'        => 'select',
			'multiple'    => true,
			'inline'      => true,
			'options' => $allCategories,
			'placeholder' => esc_html__( 'Select..', 'bricks' ),
			'group' => 'config',
			'required' =>  [ 'source', '!=', $notBricksExtras ]
		];

		if ( class_exists( 'WooCommerce' ) ) {

			$allProductCategories = $this->getProductCategories();

			$this->controls['wooSep'] = [
				'tab'   => 'content',
				'type'  => 'separator',
				'group' => 'config',
			];

			$this->controls['maybeProductCategory'] = [
				'tab'   => 'content',
				'label' => esc_html__( 'Include Product Category', 'extras' ),
				'type' => 'select',
				'inline'      => true,
				'options' => [
					'enable' => esc_html__( 'Enable', 'bricks' ),
					'disable' => esc_html__( 'Disable', 'bricks' ),
				],
				'placeholder' => esc_html__( 'Disable', 'bricks' ),
				'group' => 'config',
				'required' =>  [ 'source', '!=', $notBricksExtras ]
			];

			$this->controls['excludeProductCategories'] = [
				'tab'   => 'content',
				'label' => esc_html__( 'Exclude Product Categories', 'extras' ),
				'type'        => 'select',
				'multiple'    => true,
				'inline'      => true,
				'options' => $allProductCategories,
				'placeholder' => esc_html__( 'Select..', 'bricks' ),
				'group' => 'config',
				'required' =>  [ 'source', '!=', $notBricksExtras ]
			];

			$this->controls['maybeShop'] = [
				'tab'   => 'content',
				'label' => esc_html__( 'Include Shop Page', 'extras' ),
				'type' => 'select',
				'inline'      => true,
				'options' => [
					'enable' => esc_html__( 'Enable', 'bricks' ),
					'disable' => esc_html__( 'Disable', 'bricks' ),
				],
				'placeholder' => esc_html__( 'Enable', 'bricks' ),
				'group' => 'config',
				'required' =>  [ 'source', '!=', $notBricksExtras ]
			];

		}


		$this->controls['cptSep'] = [
			'tab'   => 'content',
			'type'  => 'separator',
			'label' => esc_html__( 'Custom post types', 'extras' ),
			'group' => 'config',
		];


		$this->controls['maybeCPTarchive'] = [
			'tab'   => 'content',
			'label' => esc_html__( 'Include CPT archive link for taxonomies', 'extras' ),
			'type' => 'select',
			'inline'      => true,
			'options' => [
				'enable' => esc_html__( 'Enable', 'bricks' ),
				'disable' => esc_html__( 'Disable', 'bricks' ),
			],
			'placeholder' => esc_html__( 'Disable', 'bricks' ),
			'group' => 'config',
			'required' =>  [ 'source', '!=', $notBricksExtras ]
		];

		$taxonomies = \Bricks\Setup::$control_options['taxonomies'];
	 	unset( $taxonomies['wp_template_part_area'] );
		unset( $taxonomies['wp_pattern_category'] );

		$this->controls['cpt'] = [
			'tab'           => 'content',
			'type'          => 'repeater',
			'group'			=> 'config',
			'label'         => esc_html__( 'For each CPT single view, which taxonomy to display the terms', 'bricks' ),
			'titleProperty' => 'postType',
			'fields'        => [
				'postType'       => [
					'type' => 'select',
					'options' => \BricksExtras\Helpers::getPostTypes(),
					'label'         => esc_html__( 'Post type', 'bricks' ),
					'placeholder' => esc_html__( 'Select custom post type', 'bricks' ),
					'inline' => true
				],
				'taxonomy'       => [
					'type' => 'select',
					'options' => $taxonomies,
					'label'         => esc_html__( 'Taxonomy', 'bricks' ),
					'inline' => true
					//'placeholder' => esc_html__( 'Select custom post type', 'bricks' ),
				],
			],
			'required' =>  [ 'source', '!=', $notBricksExtras ]
		];

		

		/* slim */

		$this->controls['slimhomeLabel'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Home label', 'bricks' ),
			'inline' => true,
			'type' => 'text',
			'placeholder' => 'Home',
			'group' => 'configSlim'
		  ];

		  $this->controls['slimsearchLabel'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Search label', 'bricks' ),
			'inline' => true,
			'type' => 'text',
			'placeholder' => 'Results for &#8220;%s&#8221;',
			'group' => 'configSlim'
		  ];

		  $this->controls['slimerrorLabel'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Error 404 label', 'bricks' ),
			'inline' => true,
			'type' => 'text',
			'placeholder' => 'Page not found',
			'group' => 'configSlim'
		  ];

		$this->controls['slimdisplayCurrent'] = [
			'tab'   => 'content',
			'label' => esc_html__( 'Display current', 'bricks' ),
			'options' => [
				'true' => esc_html__( 'Enable', 'bricks' ),
				'false' => esc_html__( 'Disable', 'bricks' ),
			],
			'inline' => true,
			'type' => 'select',
			'placeholder' => esc_html__( 'Enable', 'bricks' ),
			'group' => 'configSlim',
		];

		
		$this->controls['slimTaxonomy'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Taxonomy', 'bricks' ),
			'inline' => true,
			'type' => 'text',
			'hasDynamicData' => false,
			'placeholder' => 'category',
			'group' => 'configSlim',
			'info' => 'The taxonomy that you want to output in the breadcrumb trail when on a single post'
		  ];


		$this->controls['slimseparator'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Separator', 'bricks' ),
			'inline' => true,
			'type' => 'text',
			'placeholder' => '»',
			'hasDynamicData' => false,
			'group' => 'configSlim',
		  ];


		
    	$this->controls['separator'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Separator', 'bricks' ),
			'inline' => true,
			'type' => 'text',
			'default' => '»',
			'hasDynamicData' => false,
			'css'    => [
				[
					'property' => '--x-breadcrumb-separator',
					'selector' => 'ol',
					'value' => '"%s"'
				],
			],
			'group' => 'separator',
		  ];

		  $this->controls['separatorTypography'] = [
			'tab'    => 'content',
			'group'  => 'styling',
			'type'   => 'typography',
			'label'  => esc_html__( 'Separator Typography', 'extras' ),
			'css'    => [
				[
					'property' => 'font',
					'selector' => 'li:not(:first-child):before'
				],
			],
			'group' => 'separator'
		];

		  

		  

	
		
	/* link styles */

	$linkSelector = 'a';

	$this->controls['linkTypography'] = [
		'tab'    => 'content',
		'group'  => 'linkStyles',
		'type'   => 'typography',
		'label'  => esc_html__( 'Link Typography', 'extras' ),
		'css'    => [
			[
				'property' => 'font',
				'selector' => $linkSelector
			],
		],
	];

	

	$this->controls['linkBackgroundColor'] = [
		'tab'    => 'content',
		'group'  => 'linkStyles',
		'type'   => 'color',
		'label'  => esc_html__( 'Background', 'extras' ),
		'css'    => [
			[
				'property' => 'background-color',
				'selector' => $linkSelector
			],
		],
	];

	$this->controls['linkBorder'] = [
		'tab'    => 'content',
		'group'  => 'linkStyles',
		'type'   => 'border',
		'label'  => esc_html__( 'Border', 'extras' ),
		'css'    => [
			[
				'property' => 'border',
				'selector' => $linkSelector
			],
		],
	];

	$this->controls['linkBoxShadow'] = [
		'tab'    => 'content',
		'group'  => 'linkStyles',
		'label'  => esc_html__( 'Box Shadow', 'extras' ),
		'type'   => 'box-shadow',
		'css'    => [
			[
				'property' => 'box-shadow',
				'selector' => $linkSelector
			],
		],
	];

	$this->controls['listPadding'] = [
		'tab'   => 'content',
		'group'  => 'linkStyles',
		'label' => esc_html__( 'List item padding', 'extras' ),
		'type'  => 'dimensions',
		'css'   => [
			[
				'property' => 'padding',
				'selector' => $linkSelector,
			],
		],
	];

	$currentListItem = ".x-breadcrumbs_list [aria-current=page] > span, .rank-math-breadcrumb .last:last-child, .breadcrumbs .current-item, .breadcrumb_last, .breadcrumb--last, .breadcrumb-item.active";

	$this->controls['currentListTypography'] = [
		'tab'    => 'content',
		'group'  => 'currentItem',
		'type'   => 'typography',
		'label'  => esc_html__( 'Typography', 'extras' ),
		'css'    => [
			[
				'property' => 'font',
				'selector' => $currentListItem,
			],
		],
	];

	$this->controls['currentBackgroundColor'] = [
		'tab'    => 'content',
		'group'  => 'currentItem',
		'type'   => 'color',
		'label'  => esc_html__( 'Background', 'extras' ),
		'css'    => [
			[
				'property' => 'background-color',
				'selector' => $currentListItem
			],
		],
	];

	$this->controls['currentBorder'] = [
		'tab'    => 'content',
		'group'  => 'currentItem',
		'type'   => 'border',
		'label'  => esc_html__( 'Border', 'extras' ),
		'css'    => [
			[
				'property' => 'border',
				'selector' => $currentListItem
			],
		],
	];

	$this->controls['currentBoxShadow'] = [
		'tab'    => 'content',
		'group'  => 'currentItem',
		'label'  => esc_html__( 'Box Shadow', 'extras' ),
		'type'   => 'box-shadow',
		'css'    => [
			[
				'property' => 'box-shadow',
				'selector' => $currentListItem
			],
		],
	];

  }

  // Methods: Frontend-specific
  public function enqueue_scripts() {
	if (! \BricksExtras\Helpers::elementCSSAdded($this->name) ) {
		wp_enqueue_style( 'x-breadcrumbs', BRICKSEXTRAS_URL . 'components/assets/css/breadcrumbs.css', [], '' );
	  }
  }

  public $args = [];
  
  public function render() {

	$source = isset( $this->settings['source'] ) ? esc_html( $this->settings['source'] ) : 'extras';
	$breadcrumbPrefix = isset( $this->settings['breadcrumbPrefix'] ) ? esc_html( $this->settings['breadcrumbPrefix'] ) : '';

	if ( isset( $this->settings['removePrefix'] ) && is_front_page() ) {
		$breadcrumbPrefix = false;
	}

	$this->set_attribute( '_root', 'data-source', $source );

	if ('rankmath' === $source) {

		echo "<div {$this->render_attributes( '_root' )}>";

			if (function_exists('rank_math_the_breadcrumbs')) {
				if ($breadcrumbPrefix) {
					echo "<span class='x-breadcrumbs_prefix'>" . $breadcrumbPrefix . " </span>";
				}
				rank_math_the_breadcrumbs();
			} else {
				echo $this->render_element_placeholder( [ 
					'title' => esc_html__( "Rank Math breadcrumbs not found, check is active", 'bricksextras' )
					] );
			}

		echo "</div>";

	} 
	
	elseif ('navxt' === $source) {

		$this->set_attribute( 'navxt_list', 'class', 'breadcrumbs' );
		$this->set_attribute( 'navxt_list', 'typeof', 'BreadcrumbList' );
		$this->set_attribute( 'navxt_list', 'vocab', 'https://schema.org/' );

		$maybeLink = isset( $this->settings['maybeLink'] ) ? 'enable' === $this->settings['maybeLink'] : true;
		$maybeReverse = isset( $this->settings['maybeReverse'] ) ? 'enable' === $this->settings['maybeReverse'] : false;
		$maybeForce = isset( $this->settings['maybeForce'] ) ? 'enable' === $this->settings['maybeForce'] : false;

		echo "<div {$this->render_attributes( '_root' )}>";

		 if ( function_exists('bcn_display') ) {
			if ($breadcrumbPrefix) {
				echo "<span class='x-breadcrumbs_prefix'>" . $breadcrumbPrefix . " </span>";
			}

			echo "<div {$this->render_attributes( 'navxt_list' )}>";
				bcn_display($return = false, $maybeLink, $maybeReverse, $maybeForce);
			echo "</div>";
		  } else {
			echo $this->render_element_placeholder( [ 
				'title' => esc_html__( "Breadcrumb NavXT not found, check is active", 'bricksextras' )
				] );
		  }

		  echo "</div>";


	}

	elseif ('slim' === $source) {

		echo "<div {$this->render_attributes( '_root' )}>";

		if ( class_exists( '\SlimSEO\Breadcrumbs' ) ) {

			$slimSeparater = isset( $this->settings['slimseparator'] ) ? esc_html( $this->settings['slimseparator'] ) : '»';
			$slimDisplay_current = isset( $this->settings['slimdisplayCurrent'] ) ? esc_html( $this->settings['slimdisplayCurrent'] ) : 'true';
			$slimHome = isset( $this->settings['slimhomeLabel'] ) ? esc_html( $this->settings['slimhomeLabel'] ) : 'Home';
			$slimSearch = isset( $this->settings['slimsearchLabel'] ) ? esc_html( $this->settings['slimsearchLabel'] ) : 'Results for &#8220;%s&#8221;';
			$slimerrorLabel = isset( $this->settings['slimerrorLabel'] ) ? esc_html( $this->settings['slimerrorLabel'] ) : 'Page not found';
			$slimTaxonomy = isset( $this->settings['slimTaxonomy'] ) ? esc_html( $this->settings['slimTaxonomy'] ) : 'category';


			$slimShortcode = '[slim_seo_breadcrumbs separator="' . $slimSeparater . '" display_current="' . $slimDisplay_current . '" label_home="' . $slimHome . '" label_search="' . $slimSearch . '" label_404="' . $slimerrorLabel . '" taxonomy="' . $slimTaxonomy . '"]';

			if ($breadcrumbPrefix) {
				echo "<span class='x-breadcrumbs_prefix'>" . $breadcrumbPrefix . " </span>";
			}
			
			if ( shortcode_exists('slim_seo_breadcrumbs') ) {
				echo do_shortcode( $slimShortcode );
			}
			
		} else {
			echo $this->render_element_placeholder( [ 
				'title' => esc_html__( "Slim SEO breadcrumbs not found, check is active", 'bricksextras' )
				] );
		}

		echo "</div>";

	}

	elseif ('allinone' === $source) {

		echo "<div {$this->render_attributes( '_root' )}>";

		if( function_exists( 'aioseo_breadcrumbs' ) ) {
			if ($breadcrumbPrefix) {
				echo "<span class='x-breadcrumbs_prefix'>" . $breadcrumbPrefix . " </span>";
			}
			aioseo_breadcrumbs();
		} else {
			echo $this->render_element_placeholder( [ 
				'title' => esc_html__( "All in One SEO not found, check is active", 'bricksextras' )
				] );
		  }

		echo "</div>";  

	}

	else if ('seopress' === $source) {

		echo "<div {$this->render_attributes( '_root' )}>";

		if( function_exists("seopress_display_breadcrumbs") ) {
			if ($breadcrumbPrefix) {
				echo "<span class='x-breadcrumbs_prefix'>" . $breadcrumbPrefix . " </span>";
			}
			seopress_display_breadcrumbs();
		} else {
			echo "";  
		}

		echo "</div>";  

	} 
	
	elseif ( 'yoast' === $source ) {

		echo "<div {$this->render_attributes( '_root' )}>";

		if ( function_exists('yoast_breadcrumb') ) {
			if ($breadcrumbPrefix) {
				echo "<span class='x-breadcrumbs_prefix'>" . $breadcrumbPrefix . " </span>";
			}
			yoast_breadcrumb();
		  } else {
			echo $this->render_element_placeholder( [ 
				'title' => esc_html__( "Yoast SEO not found, check is active", 'bricksextras' )
				] );
		  }

		echo "</div>";  

	}
	
	else {

		$maybeSchema = isset( $this->settings['maybeSchema'] ) ? 'enable' === $this->settings['maybeSchema'] : true;
		$maybeParentTax = isset( $this->settings['taxNesting'] ) ? 'enable' === $this->settings['taxNesting'] : true;
		$maybeHome = isset( $this->settings['maybeHome'] ) ? 'enable' === $this->settings['maybeHome'] : true;
		$maybeBlog = isset( $this->settings['maybeBlog'] ) ? 'enable' === $this->settings['maybeBlog'] : false;

		$maybeProductCategory = isset( $this->settings['maybeProductCategory'] ) ? 'enable' === $this->settings['maybeProductCategory'] : false;
		$maybeShop = isset( $this->settings['maybeShop'] ) ? 'enable' === $this->settings['maybeShop'] : true;

		$maybeCPTarchive = isset( $this->settings['maybeCPTarchive'] ) ? 'enable' === $this->settings['maybeCPTarchive'] : false;

		$maybePostCategory = isset( $this->settings['maybePostCategory'] ) ? 'enable' === $this->settings['maybePostCategory'] : true;

		$maybeLinks = isset( $this->settings['maybeLinks'] ) ? 'disable' !== $this->settings['maybeLinks'] : true;
		
		$archiveFormat = isset( $this->settings['archiveFormat'] ) ? esc_html( $this->settings['archiveFormat'] ) : 'Archives for';

		$homeURL = isset( $this->settings['homeURL'] ) ? esc_html( $this->settings['homeURL'] ) : "{site_url}";
		$homeLinkType = isset( $this->settings['homeLinkType'] ) ? $this->settings['homeLinkType'] : 'text';
		$homeIcon = empty( $this->settings['homeIcon'] ) ? false : self::render_icon( $this->settings['homeIcon'] );
		$homeLabel = isset( $this->settings['homeLabel'] ) ? esc_html( $this->settings['homeLabel'] ) : "Home";

		$searchPrefix = isset( $this->settings['searchPrefix'] ) ? esc_html( $this->settings['searchPrefix'] ) : "";
		$authorPrefix = isset( $this->settings['authorPrefix'] ) ? esc_html( $this->settings['authorPrefix'] ) : "";
		$tagPrefix = isset( $this->settings['tagPrefix'] ) ? esc_html( $this->settings['tagPrefix'] ) : false;
		$error404Prefix = isset( $this->settings['error404Prefix'] ) ? esc_html( $this->settings['error404Prefix'] ) : "404 Error: Page not found";

		$priorityCategory = isset( $this->settings['priorityCategory'] ) ? esc_html( $this->settings['priorityCategory'] ) : false;
		$excludeCategories = isset( $this->settings['excludeCategories'] ) ? $this->settings['excludeCategories'] : [];
		$excludeProductCategories = isset( $this->settings['excludeProductCategories'] ) ? $this->settings['excludeProductCategories'] : [];

		$maxLength = isset( $this->settings['truncateMaxLength'] ) ? $this->settings['truncateMaxLength'] : 100;
		$removeTitle = isset( $this->settings['removeTitle'] ) ? 'enable' === $this->settings['removeTitle'] : false;

		$cpt = ! empty( $this->settings['cpt'] ) ? $this->settings['cpt'] : false;

		$this->set_attribute( '_root', 'aria-label', esc_attr__('breadcrumbs') );

		if ( $maybeSchema ) {
			$this->set_attribute( 'x-breadcrumbs_list', 'itemscope', '' );
			$this->set_attribute( 'x-breadcrumbs_list', 'itemtype', 'http://schema.org/BreadcrumbList' );
		}
		
		$this->set_attribute( 'x-breadcrumbs_list', 'class', 'x-breadcrumbs_list' );

		echo "<nav {$this->render_attributes( '_root' )}>";

		if ($breadcrumbPrefix) {
			echo "<span class='x-breadcrumbs_prefix'>" . $breadcrumbPrefix . " </span>";
		}
			
		echo "<ol {$this->render_attributes( 'x-breadcrumbs_list' )}>";

		if ( is_front_page() || $maybeHome ) {
			$crumbs[] = $this->get_home_crumb($homeLabel,$maybeSchema,$homeURL,$maybeLinks,$homeLinkType,$homeIcon);
		}

		global $wp_query;

			if ( is_home() && !is_front_page() ) {
				$crumbs[] = $this->get_blog_crumb($homeLabel);
			} elseif ( is_search() ) {
				$crumbs[] = $maybeSchema ?  $searchPrefix . " <span itemprop='name'>'"  . esc_html( get_search_query( false ) ) . "'</span>" : $searchPrefix . " '"  . esc_html( get_search_query( false ) ) . "'";
			} elseif ( is_404() ) {
				$crumbs[] = $error404Prefix;
			} elseif ( is_page() && ! is_front_page() ) {
				$crumbs[] = $this->get_page_crumb($maybeSchema,$maybeLinks,$maxLength);
			} elseif ( is_archive() ) {

				if ( is_category() ) {
					$crumbs[] = $this->get_category_crumb($maybeParentTax, $maybeSchema,$maybeLinks);
				} elseif ( is_tag() ) {
					$crumbs[] = $tagPrefix ? single_term_title( $tagPrefix . ' "', false ) . '"' : single_term_title( '', false );
				} elseif ( is_tax() ) {
					$crumbs[] = $this->get_tax_crumb($maybeParentTax, $maybeSchema,$maybeLinks,$maybeCPTarchive);
				} elseif ( is_year() ) {
					$crumbs[] = $this->get_year_crumb();
				} elseif ( is_month() ) {
					$crumbs[] = $this->get_month_crumb($maybeSchema,$maybeLinks);
				} elseif ( is_day() ) {
					$crumbs[] = $this->get_day_crumb($maybeSchema,$maybeLinks);
				} elseif ( is_author() ) {
					$crumbs[] = $authorPrefix . ' ' . esc_html( $wp_query->queried_object->display_name );
				} elseif ( is_post_type_archive() ) {
					$crumbs[] = $this->get_post_type_crumb();
				}

			} elseif ( is_singular() && !is_front_page() ) {
				$crumbs[] = $this->get_single_crumb($maybeParentTax,$maybeSchema,$priorityCategory,$maybeLinks,$maybeBlog, $maybeProductCategory, $maybeShop, $maybePostCategory, $excludeCategories, $excludeProductCategories, $maxLength, $cpt );
			}

			echo $this->render_breadcrumbs($crumbs,$maybeSchema, $removeTitle);

		echo "</ol>";
		
		echo "</nav>";

	}
      
    
  }

	public function get_single_crumb($maybeParentTax,$maybeSchema,$priorityCategory,$maybeLinks,$maybeBlog,$maybeProductCategory, $maybeShop, $maybePostCategory, $excludeCategories, $excludeProductCategories,$maxLength, $cpt) {

		if ( is_attachment() ) {
			$crumb = $this->get_attachment_crumb($maybeSchema,$maybeLinks,$maxLength);
		} elseif ( is_singular( 'post' ) ) {
			$crumb = $this->get_post_crumb($maybeParentTax,$maybeSchema, $priorityCategory, $maybeLinks,$maybeBlog, $maybePostCategory, $excludeCategories);
			$crumb[] = '<span itemprop="name">' . $this->truncate_title( single_post_title( '', false ), $maxLength ) . '</span>';
		} elseif ( is_singular( 'product' ) && class_exists( 'WooCommerce' ) ) {
			$crumb = $this->get_product_crumb($maybeParentTax,$maybeSchema,$maybeLinks,$maybeProductCategory, $maybeShop, $excludeProductCategories);
			$crumb[] = '<span itemprop="name">' . $this->truncate_title( single_post_title( '', false ), $maxLength ) . '</span>';
		} else {
			$crumb = $this->get_cpt_crumb($maybeParentTax,$maybeSchema,$maybeLinks,$cpt);
			$crumb[] = '<span itemprop="name">' . $this->truncate_title( single_post_title( '', false ), $maxLength ) . '</span>';
		}

		return $crumb;

	}

	
	public function get_home_crumb($homeLabel,$maybeSchema,$homeURL,$maybeLinks,$homeLinkType,$homeIcon) {

		if ( 'text' === $homeLinkType ) {
			$crumb = ( is_front_page() ) ? '<span itemprop="name">' . $homeLabel . '</span>' : $this->get_breadcrumb_link( $homeURL, $homeLabel, $maybeSchema, $maybeLinks );
		} else {
			$crumb = ( is_front_page() ) ? '<span itemprop="name"><span class=screen-reader-text>' . $homeLabel . '</span>' . $homeIcon . '</span>' : $this->get_breadcrumb_link( $homeURL, '<span class=screen-reader-text>' . $homeLabel . '</span>' . $homeIcon, $maybeSchema, $maybeLinks );
		}
		
		
		return $crumb;

	}

	
	public function get_blog_crumb($homeLabel) {

		$crumb = get_the_title( get_option('page_for_posts', true) );
		return '<span itemprop="name">' . $crumb . '</span>';

	}

	public function truncate_title($postTitle,$maxLength) {

		$endTitle = strlen($postTitle) > $maxLength ? '...' : '';

		//$postTitleOutput = substr($postTitle, 0, $maxLength) . $endTitle;
		$postTitleOutput = mb_substr( $postTitle, 0, $maxLength, 'UTF-8') . $endTitle;

		return $postTitleOutput;

	}


	public function get_page_crumb($maybeSchema,$maybeLinks,$maxLength) {

		/* woocommerce endpoints */

		if ( ! function_exists( 'is_wc_endpoint_url' ) || ( function_exists( 'is_wc_endpoint_url' ) && !is_wc_endpoint_url() ) ) {

			global $wp_query;

			$crumbs = [];

			$post = $wp_query->get_queried_object();

			if ( $post->post_parent ) {
				if ( FALSE !== get_post_status( $post->post_parent ) ) {
					if ( isset( $post->ancestors ) ) {
						if ( is_array( $post->ancestors ) ) {
							$ancestors = array_values( $post->ancestors );
						} else {
							$ancestors = [ $post->ancestors ];
						}
					} else {
						$ancestors = [ $post->post_parent ];
					}

					foreach ( $ancestors as $ancestor ) {
						array_unshift(
							$crumbs,
							$this->get_breadcrumb_link(
								get_permalink( $ancestor ),
								get_the_title( $ancestor ),
								$maybeSchema,
								$maybeLinks
							)
						);
					}
				}
			} 

			$postTitle = get_the_title();
			
			$crumbs[] = "<span itemprop=name>" . $this->truncate_title($postTitle,$maxLength) . "</span>";
			
			return $crumbs;

		} else {

			$crumbs = [];

			$action         = isset( $_GET['action'] ) ? sanitize_text_field( wp_unslash( $_GET['action'] ) ) : '';
			$endpoint       = is_wc_endpoint_url() ? WC()->query->get_current_endpoint() : '';
			$endpoint_title = $endpoint ? WC()->query->get_endpoint_title( $endpoint, $action ) : '';

			$crumbs[] = $this->get_breadcrumb_link(
				get_permalink(),
				get_the_title(),
				$maybeSchema,
				$maybeLinks
			);
			
			$crumbs[] = "<span itemprop=name>" . $endpoint_title . "</span>";
			
			return $crumbs;

		}

	}

	

	public function get_attachment_crumb($maybeSchema,$maybeLinks,$maxLength) {

		$post = get_post();

		$crumb = '';
		if ( $post->post_parent && $this->args['heirarchial_attachments'] ) {
			
			$attachment_parent = get_post( $post->post_parent );
			$crumb             = $this->get_breadcrumb_link(
				get_permalink( $post->post_parent ),
				$attachment_parent->post_title,
				$maybeSchema,
				$maybeLinks
			);
		}
		$crumb .= $this->truncate_title( single_post_title( '', false ), $maxLength );

		
		return $crumb;

	}


	public function get_post_crumb($maybeParentTax,$maybeSchema, $priorityCategory,$maybeLinks,$maybeBlog, $maybePostCategory, $excludeCategories) {

		$categories = get_the_category();

		$cat_crumb = '';

		$crumbs = [];

		if ( $maybeBlog && get_option('page_for_posts', true) ) {
			$crumbs[] = $this->get_breadcrumb_link(
				get_permalink( get_option( 'page_for_posts' ) ),
				get_the_title( get_option('page_for_posts', true) ),
				$maybeSchema,
				$maybeLinks
			);
		}

		if ( $maybePostCategory ) {

				if ( 1 === count( $categories ) ) {
					if ( !in_array( $categories[0]->cat_ID,  $excludeCategories ) ) {
						$crumbs[] = $this->get_term_parents( $categories[0]->cat_ID, 'category', true, $maybeParentTax, $maybeSchema, $maybeLinks, $excludeCategories );
					}
				}

				if ( count( $categories ) > 1 ) {

					if ( !!$priorityCategory && in_category( $priorityCategory ) ) {
						$crumbs[] = $this->get_term_parents( $priorityCategory, 'category', true, $maybeParentTax, $maybeSchema, $maybeLinks, $excludeCategories );
					} else {
						if ( !in_array( $categories[0]->cat_ID,  $excludeCategories ) ) {
							$crumbs[] = $this->get_term_parents( $categories[1]->cat_ID, 'category', true, $maybeParentTax, $maybeSchema, $maybeLinks, $excludeCategories );
						} else {
							$crumbs[] = $this->get_term_parents( $categories[0]->cat_ID, 'category', true, $maybeParentTax, $maybeSchema, $maybeLinks, $excludeCategories );
						}
					}
				};

		}

		$crumbs = $this->nestedToSingle($crumbs);

		return $crumbs;

	}

	
	public function get_cpt_crumb($maybeParentTax,$maybeSchema,$maybeLinks,$cpt) {

		$post_type        = get_query_var( 'post_type' );
		$post_type_object = get_post_type_object( $post_type );

		if ( null === $post_type_object ) {
			return [];
		}

		$crumbs = [];

		$cpt_archive_link = get_post_type_archive_link( $post_type );
		if ( $cpt_archive_link ) {
			$crumbs[] = $this->get_breadcrumb_link(
				$cpt_archive_link,
				$post_type_object->labels->name,
				$maybeSchema,
				$maybeLinks
			);
		} 


		/* if ancestors */

		$crumbsAncestors = [];
		$taxonomy_term = [];

		global $wp_query;

		$post = $wp_query->get_queried_object();

		if ( $post->post_parent ) {

			if ( FALSE !== get_post_status( $post->post_parent ) ) {
				if ( isset( $post->ancestors ) ) {
					if ( is_array( $post->ancestors ) ) {
						$ancestors = array_values( $post->ancestors );
					} else {
						$ancestors = [ $post->ancestors ];
					}
				} else {
					$ancestors = [ $post->post_parent ];
				}

				foreach ( $ancestors as $ancestor ) {

					array_unshift(
						$crumbsAncestors,
						$this->get_breadcrumb_link(
							get_permalink( $ancestor ),
							get_the_title( $ancestor ),
							$maybeSchema,
							$maybeLinks
						)
					);
				}
			}

			$crumbs = array_merge($crumbs, $crumbsAncestors);
	
		} else {

			if ( !!$cpt && is_array( $cpt ) ) {

				foreach ( $cpt as $cpt ) {

					if ( isset( $cpt['taxonomy'] ) && taxonomy_exists($cpt['taxonomy']) ) {
		
						$taxonomy_terms = get_the_terms( $post->id, $cpt['taxonomy'] );
			
						if ( $taxonomy_terms ) {
							
							if ( 1 === count( $taxonomy_terms ) ) {
								$taxonomy_term[] = $this->get_term_parents( $taxonomy_terms[0]->term_id, $cpt['taxonomy'], true, $maybeParentTax, $maybeSchema, $maybeLinks );
							}
			
							if ( count( $taxonomy_terms ) > 1 ) {
								$taxonomy_term[] = $this->get_term_parents( $taxonomy_terms[0]->term_id, $cpt['taxonomy'], true, $maybeParentTax, $maybeSchema, $maybeLinks );
							}
			
						}
			
					}
				}

			}

			$crumbs = array_merge($crumbs, $taxonomy_term);

			$crumbs = $this->nestedToSingle($crumbs);


		}

		

		return $crumbs;

	}


	public function get_product_crumb($maybeParentTax,$maybeSchema, $maybeLinks, $maybeProductCategory, $maybeShop, $excludeProductCategories) {

		$post_type        = get_query_var( 'post_type' );
		$post_type_object = get_post_type_object( $post_type );

		$crumbs = [];

		$cpt_archive_link = get_post_type_archive_link( $post_type );

		$shop_title = function_exists( 'woocommerce_page_title' ) ? woocommerce_page_title(false) : $post_type_object->labels->name;

		if ( $maybeShop ) {

			if ( $cpt_archive_link ) {
				$crumbs[] = $this->get_breadcrumb_link(
					$cpt_archive_link,
					$shop_title,
					$maybeSchema,
					$maybeLinks
				);
			} else {
				$crumbs[] = $shop_title;
			}

		}


		/* if product category */

		global $product;

		if( is_object( $product ) && !empty( $product ) ){    
			$productid = $product->get_id();
		} else {
			$productid = get_the_ID();
		}

		if ( taxonomy_exists('product_cat') ) {
		
			$product_cats = get_the_terms( $productid, 'product_cat' );

			if ( $maybeProductCategory ) {
				
				if ( 1 === count( $product_cats ) ) {
					if ( !in_array( $product_cats[0]->term_id, $excludeProductCategories ) ) {
						$crumbs[] = $this->get_term_parents( $product_cats[0]->term_id, 'product_cat', true, $maybeParentTax, $maybeSchema, $maybeLinks, $excludeProductCategories );
					}
				}

				if ( count( $product_cats ) > 1 ) {
					if ( !in_array( $product_cats[0]->term_id, $excludeProductCategories ) ) {
						$crumbs[] = $this->get_term_parents( $product_cats[0]->term_id, 'product_cat', true, $maybeParentTax, $maybeSchema, $maybeLinks, $excludeProductCategories );
					} else {
						$crumbs[] = $this->get_term_parents( $product_cats[1]->term_id, 'product_cat', true, $maybeParentTax, $maybeSchema, $maybeLinks, $excludeProductCategories );
					}
				}

			}

		}

		$crumbs = $this->nestedToSingle($crumbs);

		return $crumbs;

	}


	
	public function get_category_crumb($maybeParentTax, $maybeSchema,$maybeLinks) {
		$crumbs[] = $this->get_term_parents( get_query_var( 'cat' ), 'category', false, $maybeParentTax, $maybeSchema, $maybeLinks );
		$crumbs = $this->nestedToSingle($crumbs);
		return $crumbs;
	}

	public function get_tax_crumb($maybeParentTax, $maybeSchema, $maybeLinks, $maybeCPTarchive) {

		global $wp_query;

		$crumbs = [];

		$term  = $wp_query->get_queried_object();
		$taxonomy = get_taxonomy( $term->taxonomy );

		if ( $maybeCPTarchive ) {

			if ( ! empty( $taxonomy->object_type ) && 1 === count( $taxonomy->object_type ) ) {

				$cpt_object = get_post_type_object( $taxonomy->object_type[0] );

				if ( $cpt_object->has_archive ) {

					$archive_label = class_exists( 'WooCommerce' ) && 'product' === $cpt_object->name  ? get_the_title( get_option( 'woocommerce_shop_page_id' ) ) : $cpt_object->label;

					$crumbs[] = $this->get_breadcrumb_link(
						get_post_type_archive_link( $taxonomy->object_type[0] ),
						$archive_label,
						$maybeSchema,
						$maybeLinks
					);

				}
			}

		}

		$crumbs[] = $this->get_term_parents( $term->term_id, $term->taxonomy, false, $maybeParentTax, $maybeSchema, $maybeLinks );
		$crumbs = $this->nestedToSingle($crumbs);

		
		
		return $crumbs;

	}

	public function get_year_crumb() {

		$year = get_query_var( 'm' ) ?: get_query_var( 'year' );
		$crumb = $year;
		
		return $crumb;

	}

	public function get_month_crumb($maybeSchema,$maybeLinks) {

		$year = get_query_var( 'm' ) ? mb_substr( get_query_var( 'm' ), 0, 4 ) : get_query_var( 'year' );

		$crumb  = $this->get_breadcrumb_link(
			get_year_link( $year ),
			$year,
			$maybeSchema,
			$maybeLinks
		);
		$crumb .= single_month_title( ' ', false );
		
		return $crumb;

	}

	
	
	public function get_day_crumb($maybeSchema,$maybeLinks) {

		global $wp_locale;

		$year  = get_query_var( 'm' ) ? mb_substr( get_query_var( 'm' ), 0, 4 ) : get_query_var( 'year' );
		$month = get_query_var( 'm' ) ? mb_substr( get_query_var( 'm' ), 4, 2 ) : get_query_var( 'monthnum' );
		$day   = get_query_var( 'm' ) ? mb_substr( get_query_var( 'm' ), 6, 2 ) : get_query_var( 'day' );

		$crumb  = $this->get_breadcrumb_link(
			get_year_link( $year ),
			$year,
			$maybeSchema,
			$maybeLinks
		);
		$crumb .= $this->get_breadcrumb_link(
			get_month_link( $year, $month ),
			$wp_locale->get_month( $month ),
			$maybeSchema,
			$maybeLinks
		);
		$crumb .= $this->args['labels']['date'] . $day . gmdate( 'S', mktime( 0, 0, 0, 1, $day ) );

		
		return $crumb;

	}

	public function get_post_type_crumb() {

		$crumb = '<span itemprop="name">' . esc_html( post_type_archive_title( '', false ) ) . '</span>';
		return $crumb;

	}

	public function get_term_parents( $parent_id, $taxonomy, $link = false, $maybeParentTax = true, $maybeSchema = true, $maybeLinks = true, array $exclude = [], array $visited = [] ) {

		$parent = get_term( (int) $parent_id, $taxonomy );

		if ( is_wp_error( $parent ) ) {
			return [];
		}

		if ( $maybeParentTax ) {

			if ( $parent->parent && ( $parent->parent !== $parent->term_id ) && ! in_array( $parent->parent, $visited, true ) && ! in_array( $parent->parent, $exclude ) ) {
				$visited[] = $parent->parent;
				$chain[]   = $this->get_term_parents( $parent->parent, $taxonomy, true, $maybeParentTax, $maybeSchema, $maybeLinks, $exclude, $visited );
			}
		}

		if ( $link && ! is_wp_error( get_term_link( get_term( $parent->term_id, $taxonomy ), $taxonomy ) ) ) {

			$chain[] = $this->get_breadcrumb_link(
					get_term_link( get_term( $parent->term_id, $taxonomy ), $taxonomy ),
					$parent->name,
					$maybeSchema,
					$maybeLinks
				);

		} else {
			$chain[] = '<span itemprop="name">' . $parent->name . '</span>';
		}

		return $chain;

	}

	
	public function get_breadcrumb_link( $url, $content, $maybeSchema = true, $maybeLinks = true ) {

		$linkSchema = $maybeSchema ? " itemprop='item'><span itemtype='". $url ."' itemprop='name'" : '';

		if ( $maybeLinks ) {
			$output = "<a href='" . $url . "' " . $linkSchema . "><span>" . $content . "</span></a>";
		} else {
			$output = "<span " . $linkSchema . "><span>" . $content . "</span></span>";
		}

		return $output;

	}


	public function render_breadcrumbs($crumbs,$maybeSchema,$removeTitle) {

		$breadcrumbs_output = '';
	
		$listItemSchema = $maybeSchema ? ' itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem"' : '';
	
		$breadcrumbsFlat = $this->array_flatten($crumbs);

		/* allow users to edit breadcrumbs array */
		$breadcrumbsFlat = apply_filters( 'bricksextras/breadcrumbs/', $breadcrumbsFlat );

		$breadcrumbCount = count($breadcrumbsFlat) - 1;

		
	
		foreach ($breadcrumbsFlat as $key => $breadcrumb) {

		  $breakcrumbActive = ( $key === $breadcrumbCount ) ? " aria-current=page" : "";

		  if ( !$removeTitle || ( $key !== $breadcrumbCount ) || !is_singular() ) {

			$breadcrumbs_output .= '<li class="x-breadcrumbs_list-item"' . $listItemSchema . $breakcrumbActive . '>' . $breadcrumb;
			$breadcrumbs_output .= $maybeSchema ? '<meta itemprop="position" content="' . intVal( $key + 1 ) .'">' : '';
			$breadcrumbs_output .= '</li>';

		  }

		}
	
		return $breadcrumbs_output;
	
	  }


	   /* helpers */

		public function array_flatten($array) {
			$return = array();
			foreach ($array as $key => $value) {
				if (is_array($value)){
					$return = array_merge($return, $this->array_flatten($value));
				} else {
					$return[$key] = $value;
				}
			}

			return $return;
		}

		public function nestedToSingle(array $array) {

			$singleDimArray = [];
		
			foreach ($array as $item) {
		
				if (is_array($item)) {
					$singleDimArray = array_merge($singleDimArray, $this->nestedToSingle($item));
		
				} else {
					$singleDimArray[] = $item;
				}
			}
		
			return $singleDimArray;
		 }


		/**
		 * Retrieve all categories taxonomies
		 * @return array
		 */
		private function getCategories() {

			if ( !bricks_is_builder() ) {
				return;
			}
			
			$out   = [];
			$categories = get_categories( [
				'orderby' => 'name',
				'order'   => 'ASC'
			]);

			if ( ! empty( $categories ) ) {
				
					foreach ( $categories as $category ) {
						$out[$category->term_id] = $category->name;
					}
			}

			return $out;

		}

		/**
		 * Retrieve all categories taxonomies
		 * @return array
		 */
		private function getProductCategories() {
			
			if ( !bricks_is_builder() ) {
				return;
			}
			
			$out   = [];
			$categories = get_categories( [
				'taxonomy' => 'product_cat',
				'orderby' => 'name',
				'order'   => 'ASC'
			]);

			if ( ! empty( $categories ) ) {
				
					foreach ( $categories as $category ) {
						$out[$category->term_id] = $category->name;
					}
			}

			return $out;

		}


		 /**
		 * Retrieve all public taxonomies
		 * @return array
		 */
		private function getTaxonomies() {

			if ( !bricks_is_builder() ) {
				return;
			}
			
			$out   = [];
			$taxonomies = get_taxonomies([ 'public'   => true ], 'names');

			if ( ! empty( $taxonomies ) ) {
				
					foreach ( $taxonomies as $taxonomy ) {
						$out[] = [
							$taxonomy->id => $taxonomy->name
						]; 
					}
			}

			return $out;

		}


}