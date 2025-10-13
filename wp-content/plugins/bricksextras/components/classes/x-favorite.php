<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class X_Favorite extends \Bricks\Element {

  // Element properties
  public $category     = 'extras';
	public $name         = 'xfavorite';
	public $icon         = 'ti-heart';
	public $css_selector = '';
	//public $scripts      = ['xFavorite'];
  public $nestable = true;

  
  public function get_label() {
	return esc_html__( 'Favorite Button', 'extras' );
  }
  public function set_control_groups() {

    $this->control_groups['icons'] = [
      'title' => esc_html__( 'Icons', 'bricks' ),
      'tab'   => 'content',
    ];

    $this->control_groups['button'] = [
      'title' => esc_html__( 'Button', 'bricks' ),
      'tab'   => 'content',
    ];

    $this->control_groups['animation'] = [
      'title' => esc_html__( 'Animation', 'bricks' ),
      'tab'   => 'content',
      'required' => ['buttonType','!=','count']
    ];

    $this->control_groups['count'] = [
      'title' => esc_html__( 'Count', 'bricks' ),
      'tab'   => 'content',
      'required' => ['buttonType','=','count']
    ];

    $this->control_groups['tooltip'] = [
      'title' => esc_html__( 'Tooltip', 'bricks' ),
      'tab'   => 'content',
    ];


  }

  public function set_controls() {


    $innerSelector = '.x-favorite';

    $this->controls['_display']['css'][0]['selector'] = $innerSelector;
    $this->controls['_flexDirection']['css'][0]['selector'] = $innerSelector;
    $this->controls['_alignSelf']['css'][0]['selector'] = $innerSelector;
    $this->controls['_justifyContent']['css'][0]['selector'] = $innerSelector;
    $this->controls['_alignItems']['css'][0]['selector'] = $innerSelector;
    $this->controls['_gap']['css'][0]['selector'] = $innerSelector;
    $this->controls['_padding']['css'][0]['selector'] = $innerSelector;

    $this->controls['_border']['css'][0]['selector'] = $innerSelector;
    $this->controls['_boxShadow']['css'][0]['selector'] = $innerSelector;
    $this->controls['_gradient']['css'][0]['selector'] = $innerSelector;
    $this->controls['_transform']['css'][0]['selector'] = $innerSelector;

    $this->controls['access'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Access', 'bricks' ),
			'type' => 'select',
      'inline' => true,
			'options' => [
			  'logged_in' => esc_html__( 'Logged in users', 'bricks' ),
			  'all' => esc_html__( 'All users', 'bricks' ),
			],
      'placeholder' => esc_html__( 'Logged in users', 'bricks' ),
		];

    $this->controls['accessInfo'] = [
			'tab' => 'content',
			'type' => 'info',
      'description' => esc_html__( 'Users selections saves in user meta', 'bricks' ),
      'required' => ['access','!=','all']
		];

    $this->controls['accessInfoAll'] = [
			'tab' => 'content',
			'type' => 'info',
      'description' => esc_html__( 'Users selections saves in user meta, logged out user saved as cookie', 'bricks' ),
      'required' => ['access','=','all']
		];

    $this->controls['accessSep'] = [
			'tab' => 'content',
			'type' => 'separator',
		];


    $this->controls['buttonType'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Behaviour', 'bricks' ),
			'type' => 'select',
      'inline' => true,
			'options' => [
			  'add_remove' => esc_html__( 'Add / Remove button', 'bricks' ),
			  'remove' => esc_html__( 'Clear item', 'bricks' ),
        'clear' => esc_html__( 'Clear all items', 'bricks' ),
        'count' => esc_html__( 'Count', 'bricks' ),
			],
      'placeholder' => esc_html__( 'Add / Remove button', 'bricks' ),
		];

    $listOptions = bricks_is_builder() ? \Bricks\Helpers::get_registered_post_types() : [];

    $listOptions['custom'] = esc_html__( 'Custom (advanced)', 'bricks' );
    
    $this->controls['postType'] = [
			'tab'         => 'content',
      'label'       => esc_html__( 'Post Type', 'bricks' ),
      'type'        => 'select',
      'options'     => $listOptions,
      'clearable'   => true,
      'inline'      => true,
      'searchable'  => true,
      'placeholder' => esc_html__( 'Select post type...', 'bricks' ),
    ];

    $this->controls['newList'] = [
			'tab' => 'content',
			'type' => 'checkbox',
      'label' => esc_html__( 'Custom List', 'extras' ),
		];

    $this->controls['listSlug'] = [
      'tab'   => 'content',
      //'group' => 'button',
      'label' => esc_html__( 'List Indentifier', 'extras' ),
      'info' => esc_html__( 'List that is being saved to', 'extras' ),
      'type'  => 'text',
      'inline' => true,
      //'small' => true,
      'placeholder' => esc_html__(''),
      'required' => ['newList','=',true]
    ];

    $this->controls['listMaximum'] = [
      'tab'   => 'content',
      //'group' => 'button',
      'label' => esc_html__( 'Max. number of posts in list', 'extras' ),
      'type'  => 'number',
      'units' => false,
      'inline' => true,
      'small' => true,
      'placeholder' => esc_html__(''),
      'required' => [
        ['newList','=',true],
        ['buttonType','!=',['remove','clear','count']]
      ]
    ];

    /*
    $this->controls['itemID'] = [
      'tab'   => 'content',
      //'group' => 'button',
      'label' => esc_html__( 'Item ID', 'extras' ),
      'info' => esc_html__( 'The ID being saved to your list', 'extras' ),
      'type'  => 'text',
      'inline' => true,
      'placeholder' => esc_html__('{post_id}'),
      'required' => ['newList','=',true]
    ];
    */
    
    $this->controls['buttonTextSep'] = [
			'tab' => 'content',
			'type' => 'separator',
		];


    $this->controls['addButtonText'] = [
      'tab'   => 'content',
      //'group' => 'button',
      'label' => esc_html__( 'Button text', 'extras' ),
      'type'  => 'text',
      'inline' => true,
      'default' => esc_html__('Add to favorites'),
      'required' => ['buttonType','!=',['remove','clear','count']]
    ];

    $this->controls['removeButtonText'] = [
      'tab'   => 'content',
      //'group' => 'button',
      'label' => esc_html__( 'Button text', 'extras' ),
      'type'  => 'text',
      'inline' => true,
      'default' => esc_html__('Remove'),
      'required' => ['buttonType','=','remove']
    ];

    $this->controls['addedButtonText'] = [
      'tab'   => 'content',
      //'group' => 'button',
      'label' => esc_html__( 'Button text (added)', 'extras' ),
      'type'  => 'text',
      'inline' => true,
      'required' => ['buttonType','!=',['remove','clear','count']]
    ];

    $this->controls['maxReachedButtonText'] = [
      'tab'   => 'content',
      //'group' => 'button',
      'label' => esc_html__( 'Button text (max reached)', 'extras' ),
      'type'  => 'text',
      'inline' => true,
      'default' => esc_html__('Maximum Reached'),
      'required' => [
        ['buttonType','!=',['remove','clear','count']],
        ['listMaximum', '!=', '']
      ]
    ];

    $this->controls['clearButtonText'] = [
      'tab'   => 'content',
      //'group' => 'button',
      'label' => esc_html__( 'Button text', 'extras' ),
      'type'  => 'text',
      'inline' => true,
      'required' => ['buttonType','=','clear'],
      'default' => esc_html__('Clear all'),
    ];

    $this->controls['clearedButtonText'] = [
      'tab'   => 'content',
      //'group' => 'button',
      'label' => esc_html__( 'Button text (cleared)', 'extras' ),
      'type'  => 'text',
      'inline' => true,
      'required' => ['buttonType','=','clear'],
    ];

    $this->controls['countButtonText'] = [
      'tab'   => 'content',
      //'group' => 'button',
      'label' => esc_html__( 'Button text', 'extras' ),
      'type'  => 'text',
      'inline' => true,
      'required' => ['buttonType','=','count']
    ];

    $this->controls['link'] = [
			'label' => esc_html__( 'Link to', 'bricks' ),
			'type'  => 'link',
      'required' => ['buttonType','=','count']
		];

    $this->controls['ariaLabel'] = [
      'tab'   => 'content',
      //'group' => 'button',
      'label' => esc_html__( 'Aria Label', 'extras' ),
      'info' => esc_html__( 'Not needed if adding button text', 'extras' ),
      'type'  => 'text',
      'inline' => true,
      'placeholder' => '',
      'required' => ['buttonType','!=',['remove','clear','count']]
    ];

    $this->controls['removeariaLabel'] = [
      'tab'   => 'content',
      //'group' => 'button',
      'label' => esc_html__( 'Aria Label', 'extras' ),
      'info' => esc_html__( 'Not needed if adding button text', 'extras' ),
      'type'  => 'text',
      'inline' => true,
      'placeholder' => esc_html__( 'Remove item', 'extras' ),
      'required' => ['buttonType','=',['remove']]
    ];

    $this->controls['clearariaLabel'] = [
      'tab'   => 'content',
      //'group' => 'button',
      'label' => esc_html__( 'Aria Label', 'extras' ),
      'info' => esc_html__( 'Not needed if adding button text', 'extras' ),
      'type'  => 'text',
      'inline' => true,
      'placeholder' => esc_html__( 'Clear all', 'extras' ),
      'required' => ['buttonType','=',['clear']]
    ];

    $this->controls['countariaLabel'] = [
      'tab'   => 'content',
      //'group' => 'button',
      'label' => esc_html__( 'Aria Label', 'extras' ),
      'info' => esc_html__( 'Not needed if adding button text', 'extras' ),
      'type'  => 'text',
      'inline' => true,
      'placeholder' => esc_html__( 'Go to favorite', 'extras' ),
      'required' => ['buttonType','=',['count']]
    ];


    /*  icons */

    $this->controls['maybeIcons'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Include Icons', 'bricks' ),
			'type' => 'select',
      'group' => 'icons',
			'options' => [
			  'enable' => esc_html__( 'Enable', 'bricks' ),
			  'disable' => esc_html__( 'Disable', 'bricks' ),
			],
			'inline'      => true,
			'clearable' => false,
			'placeholder' => esc_html__( 'Enable', 'bricks' ),
		];

    $this->controls['addIcon'] = [
      'tab'     => 'content',
      'label'   => esc_html__( 'Icon', 'bricks' ),
      'group' => 'icons',
      'type'    => 'icon',
      'css'      => [
        [
          'selector' => '.x_favorite-removed-icon > *',
        ],
      ],
      'default' => [
        'library' => 'themify',
        'icon' => 'ti-heart',
      ],
      'required' => [
        ['maybeIcons','!=','disable'],
        ['buttonType','!=',['remove','clear']],
      ]
    ];

    $this->controls['removeIcon'] = [
      'tab'     => 'content',
      'label'   => esc_html__( 'Remove Icon', 'bricks' ),
      'group' => 'icons',
      'type'    => 'icon',
      'css'      => [
        [
          'selector' => '.x_favorite-added-icon > *',
        ],
        [
          'selector' => '.x_favorite-clear-icon > *',
        ],
      ],
      'required' => [
        ['maybeIcons','!=','disable'],
        ['buttonType','!=',['count']],
      ]
    ];

    $this->controls['clearedIcon'] = [
      'tab'     => 'content',
      'label'   => esc_html__( 'Cleared Icon', 'bricks' ),
      'group' => 'icons',
      'type'    => 'icon',
      'css'      => [
        [
          'selector' => '.x_favorite-cleared-icon > *',
        ],
      ],
      'required' => [
        ['maybeIcons','!=','disable'],
        ['buttonType','=',['clear']],
      ]
    ];


    $this->controls['iconSize'] = [
      'tab'    => 'content',
      'group'  => 'icons',
      'type'   => 'number',
      'units'   => true,
      'label'  => esc_html__( 'Icon size', 'extras' ),
      'css'    => [
        [
          'property' => 'font-size',
          'selector' => '.x-favorite_icons',
        ],
      ],
      'required' => ['maybeIcons','!=','disable']
    ];

    /*
    $this->controls['iconAnimation'] = [
      'tab'    => 'content',
      'group'  => 'icons',
      'inline' => true,
      'type'   => 'select',
      'label'  => esc_html__( 'Icon animation', 'extras' ),
      'placeholder' => esc_html__( 'Fade', 'bricks' ),
      'options'    => [
          'fade' => esc_html__( 'Fade', 'bricks' ),
          'slideUp' => esc_html__( 'Slide Up', 'bricks' ),
          'slideDown' => esc_html__( 'Slide Down', 'bricks' ),
          'slideLeft' => esc_html__( 'Slide Left', 'bricks' ),
          'slideRight' => esc_html__( 'Slide Right', 'bricks' ),
          'flipX' => esc_html__( 'Flip X', 'bricks' ),
          'flipY' => esc_html__( 'Flip Y', 'bricks' ),
      ],
      'required' => ['maybeIcons','!=','disable']
    ];
    */


    /* count */

    $count = '.x-favorite_count';

    $this->controls['hideIfZeroCount'] = [
      'tab' => 'content',
      'label' => esc_html__( 'Hide if zero', 'bricks' ),
      'type' => 'select',
      'group' => 'count',
      'options' => [
        'enable' => esc_html__( 'Enable', 'bricks' ),
        'disable' => esc_html__( 'Disable', 'bricks' ),
      ],
      'inline'      => true,
      'clearable' => false,
      'placeholder' => esc_html__( 'Disable', 'bricks' ),
    ];

    $this->controls['countBackgroundColor'] = [
      'tab'    => 'content',
      'group'  => 'count',
      'type'   => 'color',
      'label'  => esc_html__( 'Background', 'bricks' ),
      'css'    => [
        [
          'property' => 'background-color',
          'selector' => $count,
        ],
      ],
    ];

    $this->controls['countTypography'] = [
      'tab'    => 'content',
      'group'  => 'count',
      'type'   => 'typography',
      'label'  => esc_html__( 'Typography', 'bricks' ),
      'rerender' => false,
      'css'    => [
        [
          'property' => 'font',
          'selector' => $count,
        ],
      ],
    ];

    $this->controls['countBorder'] = [
      'tab'    => 'content',
      'group'  => 'count',
      'type'   => 'border',
      'rerender' => false,
      'label'  => esc_html__( 'Border', 'bricks' ),
      'css'    => [
        [
          'property' => 'border',
          'selector' => $count,
        ],
      ],
    ];

    $this->controls['countBoxShadow'] = [
      'tab'    => 'content',
      'group'  => 'count',
      'label'  => esc_html__( 'Box Shadow', 'bricks' ),
      'type'   => 'box-shadow',
      'rerender' => false,
      'css'    => [
        [
          'property' => 'box-shadow',
          'selector' => $count,
        ],
      ],
    ];

    $this->controls['countSize'] = [
      'tab'    => 'content',
      'group'  => 'count',
      'type'   => 'number',
      'small'  => true,
      'units'  => true,
      'label'  => esc_html__( 'Size', 'bricks' ),
      'placeholder' => '2em',
      'css'    => [
        [
          'property' => 'height',
          'selector' => $count,
        ],
        [
          'property' => 'width',
          'selector' => $count,
        ],
      ],
    ];

    $this->controls['countTop'] = [
      'tab'    => 'content',
      'group'  => 'count',
      'type'   => 'number',
      'units'  => true,
      'small'  => true,
      'placeholder' => '-1em',
      'label'  => esc_html__( 'Top', 'bricks' ),
      'css'    => [
        [
          'property' => 'top',
          'selector' => $count,
        ],
      ],
    ];

    $this->controls['countRight'] = [
      'tab'    => 'content',
      'group'  => 'count',
      'type'   => 'number',
      'placeholder' => '-1em',
      'units'  => true,
      'small'  => true,
      'label'  => esc_html__( 'Right', 'bricks' ),
      'css'    => [
        [
          'property' => 'right',
          'selector' => $count,
        ],
      ],
    ];

    $this->controls['countBottom'] = [
      'tab'    => 'content',
      'group'  => 'count',
      'type'   => 'number',
      'units'  => true,
      'small'  => true,
      'label'  => esc_html__( 'Bottom', 'bricks' ),
      'css'    => [
        [
          'property' => 'bottom',
          'selector' => $count,
        ],
      ],
    ];

    $this->controls['countLeft'] = [
      'tab'    => 'content',
      'group'  => 'count',
      'type'   => 'number',
      'units'  => true,
      'small'  => true,
      'label'  => esc_html__( 'Left', 'bricks' ),
      'css'    => [
        [
          'property' => 'left',
          'selector' => $count,
        ],
      ],
    ];




    /* tooltip */

    $this->controls['maybeToolip'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Add tooltip', 'bricks' ),
			'type' => 'select',
      'group' => 'tooltip',
			'options' => [
			  'enable' => esc_html__( 'Enable', 'bricks' ),
			  'disable' => esc_html__( 'Disable', 'bricks' ),
			],
			'inline'      => true,
			'clearable' => false,
			'placeholder' => esc_html__( 'Disable', 'bricks' ),
		];

    $this->controls['addTooltipText'] = [
      'tab'   => 'content',
      'group' => 'tooltip',
      'label' => esc_html__( 'Text', 'extras' ),
      'type'  => 'text',
      'inline' => true,
      'placeholder' => esc_html__( 'Add to favorites' ),
      'required' => [
        ['maybeToolip','=','enable'],
        ['buttonType','!=',['remove','clear']],
      ]
    ];

    $this->controls['addedTooltipText'] = [
      'tab'   => 'content',
      'group' => 'tooltip',
      'label' => esc_html__( 'Text (added)', 'extras' ),
      'type'  => 'text',
      'inline' => true,
      'placeholder' => esc_html__( 'Added to favorite' ),
      'required' => [
        ['maybeToolip','=','enable'],
        ['buttonType','!=',['remove','clear','count']],
      ]
    ];

    $this->controls['maxReachedTooltipText'] = [
      'tab'   => 'content',
      'group' => 'tooltip',
      'label' => esc_html__( 'Text (max. reached)', 'extras' ),
      'type'  => 'text',
      'inline' => true,
      'placeholder' => esc_html__( 'Maximum Reached' ),
      'required' => [
        ['maybeToolip','=','enable'],
        ['buttonType','!=',['remove','clear','count']],
        ['listMaximum','!=',''],
      ]
    ];

    $this->controls['removedTooltipText'] = [
      'tab'   => 'content',
      'group' => 'tooltip',
      'label' => esc_html__( 'Text (removed)', 'extras' ),
      'type'  => 'text',
      'inline' => true,
      'placeholder' => esc_html__( 'Removed from favorites' ),
      'description'=> esc_html__( '(touch devices)' ),
      'required' => [
        ['maybeToolip','=','enable'],
        ['buttonType','!=',['remove','clear','count']],
      ]
    ];

    $this->controls['tooltipSep'] = [
      'tab'   => 'content',
      'group'  => 'tooltip',
      'type'  => 'separator'
    ];

    $this->controls['removeTooltipText'] = [
      'tab'   => 'content',
      'group' => 'tooltip',
      'label' => esc_html__( 'Text (remove)', 'extras' ),
      'type'  => 'text',
      'inline' => true,
      'placeholder' => esc_html__( 'Remove' ),
      'required' => [
        ['maybeToolip','=','enable'],
        ['buttonType','=','remove'],
      ]
    ];

   

    $this->controls['clearTooltipText'] = [
      'tab'   => 'content',
      'group' => 'tooltip',
      'label' => esc_html__( 'Text (clear)', 'extras' ),
      'type'  => 'text',
      'inline' => true,
      'placeholder' => esc_html__( 'Clear all' ),
      'required' => [
        ['maybeToolip','=','enable'],
        ['buttonType','=','clear'],
      ]
    ];

    $this->controls['clearedTooltipText'] = [
      'tab'   => 'content',
      'group' => 'tooltip',
      'label' => esc_html__( 'Text (cleared)', 'extras' ),
      'type'  => 'text',
      'inline' => true,
      'placeholder' => esc_html__( 'Cleared' ),
      'required' => [
        ['maybeToolip','=','enable'],
        ['buttonType','=','clear'],
      ]
    ];

    $this->controls['disabledText'] = [
      'tab'   => 'content',
      'group' => 'tooltip',
      'label' => esc_html__( 'Text (disabled)', 'extras' ),
      'type'  => 'text',
      'inline' => true,
      'placeholder' => esc_html__( 'Login to enable' ),
      'required' => [
        ['maybeToolip','=','enable'],
        ['access','!=','all'],
      ]
    ];

    /*
    $this->controls['tooltipShow'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Show on.', 'bricks' ),
			'type' => 'select',
      'group' => 'tooltip', 
			'options' => [
			  'hocus' => esc_html__('Hover / Focus', 'bricks' ), 
				'complete' => esc_html__('On success', 'bricks' ), 
			],
			'inline'      => true,
			'placeholder' => esc_html__( 'Hover / Focus', 'bricks' ), 
			'clearable' => false,
      'required' => ['maybeToolip','=','enable']
		  ];

      */

    $this->controls['placement'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Preferred placement', 'bricks' ),
			'type' => 'select',
      'group' => 'tooltip', 
			'options' => [
			  'top' => esc_html__('Top', 'bricks' ), 
				'right' => esc_html__('Right', 'bricks' ), 
				'bottom' => esc_html__('Bottom', 'bricks' ), 
				'left' => esc_html__('Left', 'bricks' ), 
				'auto' 	=> esc_html__( 'Auto (Side with the most space)', 'bricks' ), 
				'auto-start' => esc_html__( 'Auto Start', 'bricks' ), 
				'auto-end' => esc_html__( 'Auto End', 'bricks' ),
				'top-start' => esc_html__( 'Top Start', 'bricks' ), 
				'top-end' => esc_html__( 'Top End', 'bricks' ),
				'right-start' => esc_html__( 'Right Start', 'bricks' ), 
				'right-end' => esc_html__( 'Right End', 'bricks' ),
				'bottom-start' => esc_html__( 'Bottom Start', 'bricks' ), 
				'bottom-end' => esc_html__( 'Bottom End', 'bricks' ),
				'left-start' => esc_html__( 'Left Start', 'bricks' ), 
				'left-end' => esc_html__( 'Left End', 'bricks' ),
			],
			//'inline'      => true,
			'placeholder' => esc_html__( 'Top', 'bricks' ), 
			'clearable' => false,
      'required' => ['maybeToolip','=','enable']
		  ];


        $this->controls['delay'] = [
          'tab'   => 'content',
          'group' => 'tooltip',
          'label' => esc_html__( 'Delay (ms)', 'extras' ),
          'type'  => 'number',
          'placeholder' => '0',
          'units' => false,
          'small' => true,
          'required' => ['maybeToolip','=','enable']
        ];

      
      $this->controls['offsetSkidding'] = [
        'tab'   => 'content',
        'group' => 'tooltip',
        'label' => esc_html__( 'Offset skidding (px)', 'extras' ),
        'info' => esc_html__( 'Distance along the side of the button', 'extras' ),
        'type'  => 'number',
        'placeholder' => '0',
        'units' => false,
        'small' => true,
        'required' => ['maybeToolip','=','enable']
      ];

      $this->controls['offsetDistance'] = [
        'tab'   => 'content',
        'group' => 'tooltip',
        'label' => esc_html__( 'Offset distance (px)', 'extras' ),
        'info' => esc_html__( 'Distance away from the button', 'extras' ),
        'type'  => 'number',
        'placeholder' => '10',
        'units' => false,
        'small' => true,
        'required' => ['maybeToolip','=','enable']
      ];


      /*  popover style */

      $this->controls['tooltipStyleSep'] = [
        'tab'   => 'content',
        'group'  => 'tooltip',
        'type'  => 'separator',
        'label' => esc_html__( 'Tooltip styling', 'extras' ),
        'required' => ['maybeToolip','=','enable']
      ];

    $popover = '.tippy-content';

    $this->controls['popoverWidth'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Popover width', 'extras' ),
			'inline'      => true,
      'group'  => 'tooltip',
			'small'		=> true,
			'type' => 'number',
			'units'    => true,
			'css' => [
			  [
				'selector' => $popover,  
				'property' => 'width',
			  ],
			],
      'required' => ['maybeToolip','=','enable']
		  ];

     $this->controls['popoverTypography'] = [
       'tab'    => 'content',
       'group'  => 'tooltip',
       'type'   => 'typography',
       'label'  => esc_html__( 'Typography', 'extras' ),
       'rerender' => false,
       'css'    => [
         [
           'property' => 'font',
           'selector' => $popover,
         ],
       ],
       'required' => ['maybeToolip','=','enable']
     ];
 
     $this->controls['popoverBackgroundColor'] = [
       'tab'    => 'content',
       'group'  => 'tooltip',
       'type'   => 'color',
       'label'  => esc_html__( 'Background', 'extras' ),
       'css'    => [
         [
           'property' => '--x-favorite-background',
           'selector' => ''
         ],
       ],
       'required' => ['maybeToolip','=','enable']
     ];
 
     $this->controls['popoverBorder'] = [
       'tab'    => 'content',
       'group'  => 'tooltip',
       'type'   => 'border',
       'rerender' => false,
       'label'  => esc_html__( 'Border', 'extras' ),
       'css'    => [
         [
           'property' => 'border',
           'selector' => $popover,
         ],
       ],
       'required' => ['maybeToolip','=','enable']
     ];
 
     $this->controls['popoverBoxShadow'] = [
       'tab'    => 'content',
       'group'  => 'tooltip',
       'label'  => esc_html__( 'Box Shadow', 'extras' ),
       'type'   => 'box-shadow',
       'rerender' => false,
       'css'    => [
         [
           'property' => 'box-shadow',
           'selector' => $popover,
         ],
       ],
       'required' => ['maybeToolip','=','enable']
     ];


     $popoverCopied = '&:has(.x-favorite[aria-pressed="true"]) .tippy-content';

     $this->controls['popoverCopiedSep'] = [
      'tab'   => 'content',
      'group'  => 'tooltip',
      'type'  => 'separator',
      'label'  => esc_html__( 'Added state', 'extras' ),
      'required' => [
        ['maybeToolip','=','enable'],
        ['buttonType','!=',['remove','clear','count']],
      ]
    ];

     $this->controls['popoverCopiedTypography'] = [
      'tab'    => 'content',
      'group'  => 'tooltip',
      'type'   => 'typography',
      'label'  => esc_html__( 'Typography', 'extras' ),
      'rerender' => false,
      'css'    => [
        [
          'property' => 'font',
          'selector' => $popoverCopied,
        ],
      ],
      'required' => [
        ['maybeToolip','=','enable'],
        ['buttonType','!=',['remove','clear','count']],
      ]
    ];

    $this->controls['popoverCopiedBackgroundColor'] = [
      'tab'    => 'content',
      'group'  => 'tooltip',
      'type'   => 'color',
      'label'  => esc_html__( 'Background', 'extras' ),
      'css'    => [
        [
          'property' => '--x-favorite-background-active',
          'selector' => ''
        ],
      ],
      'required' => [
        ['maybeToolip','=','enable'],
        ['buttonType','!=',['remove','clear','count']],
      ]
    ];

    $this->controls['popoverCopiedBorder'] = [
      'tab'    => 'content',
      'group'  => 'tooltip',
      'type'   => 'border',
      'rerender' => false,
      'label'  => esc_html__( 'Border', 'extras' ),
      'css'    => [
        [
          'property' => 'border',
          'selector' => $popoverCopied
        ],
      ],
      'required' => [
        ['maybeToolip','=','enable'],
        ['buttonType','!=',['remove','clear','count']],
      ]
    ];

    $this->controls['popoverCopiedBoxShadow'] = [
      'tab'    => 'content',
      'group'  => 'tooltip',
      'label'  => esc_html__( 'Box Shadow', 'extras' ),
      'type'   => 'box-shadow',
      'rerender' => false,
      'css'    => [
        [
          'property' => 'box-shadow',
          'selector' => $popoverCopied
        ],
      ],
      'required' => [
        ['maybeToolip','=','enable'],
        ['buttonType','!=',['remove','clear','count']],
      ]
    ];




 
     $this->controls['popover_start'] = [
       'tab'   => 'content',
       'group'  => 'tooltip',
       'type'  => 'separator',
       'required' => ['maybeToolip','=','enable']
     ];
 
     $this->controls['popoverPadding'] = [
       'tab'   => 'content',
       'group' => 'tooltip',
       'label' => esc_html__( 'Padding', 'extras' ),
       'type'  => 'dimensions',
       'css'   => [
         [
           'property' => 'padding',
           'selector' => $popover
         ],
       ],
       'required' => ['maybeToolip','=','enable']
     ];


     /* animation */

     $this->controls['popoverTransitionIn'] = [
      'tab'   => 'content',
      'group' => 'tooltip',
      'label' => esc_html__( 'Transition In (ms)', 'extras' ),
      'css'    => [
        [
          'property' => '--x-favorite-transitionin',
          'selector' => '',
        ],
      ],
      'type'  => 'number',
      'placeholder' => '300',
      'inline' => true,
      'small'  => true,
      'unit' => 'ms',
      'required' => ['maybeToolip','=','enable']
    ];

    $this->controls['popoverTransitionOut'] = [
      'tab'   => 'content',
      'group' => 'tooltip',
      'label' => esc_html__( 'Transition Out (ms)', 'extras' ),
      'css'    => [
        [
          'property' => '--x-favorite-transitionout',
          'selector' => '',
        ],
      ],
      'type'  => 'number',
      'placeholder' => '300',
      'unit' => 'ms',
      'inline' => true,
      'small'  => true,
      'required' => ['maybeToolip','=','enable']
    ];

    $this->controls['popoverTranslateX'] = [
      'tab'   => 'content',
      'group' => 'tooltip',
      'label' => esc_html__( 'TranslateX', 'extras' ),
      'css'    => [
        [
          'property' => '--x-favorite-translatex',
          'selector' => '',
        ],
      ],
      'type'  => 'number',
      'placeholder' => '0',
      'inline' => true,
      'small'  => true,
      'units' => [
        'px' => [
          'min'  => 1,
          'max'  => 1000,
          'step' => 1,
        ],
      ],
      'required' => ['maybeToolip','=','enable']
    ];

    $this->controls['popoverTranslateY'] = [
      'tab'   => 'content',
      'group' => 'tooltip',
      'label' => esc_html__( 'TranslateY', 'extras' ),
      'css'    => [
        [
          'property' => '--x-favorite-translatey',
          'selector' => '',
        ],
      ],
      'type'  => 'number',
      'placeholder' => '10',
      'inline' => true,
      'small'  => true,
      'units' => [
        'px' => [
          'min'  => 1,
          'max'  => 1000,
          'step' => 1,
        ],
      ],
      'required' => ['maybeToolip','=','enable']
    ];


    $this->controls['popoverScale'] = [
      'tab'   => 'content',
      'group' => 'tooltip',
      'label' => esc_html__( 'Scale', 'extras' ),
      'css'    => [
        [
          'property' => '--x-favorite-scale',
          'selector' => '',
        ],
      ],
      'type'  => 'number',
      'placeholder' => '0.95',
      'inline' => true,
      'small'  => true,
      'required' => ['maybeToolip','=','enable']
    ];




      /* button */


    $button = '.x-favorite';

   

    $this->controls['buttonWidth'] = [
      'tab'   => 'content',
      'group' => 'button',
      'label' => esc_html__( 'Button width', 'extras' ),
      'type'  => 'number',
      'units'  => true,
      'css'   => [
        [
          'property' => 'width',
          'selector' => '.x-favorite_inner',
        ],
      ],
    ];

    $this->controls['buttonTypography'] = [
      'tab'    => 'content',
      'group'  => 'button',
      'type'   => 'typography',
      'label'  => esc_html__( 'Typography', 'extras' ),
      'css'    => [
        [
          'property' => 'font',
          'selector' => $button,
        ],
      ],
    ];

    $this->controls['buttonBackgroundColor'] = [
      'tab'    => 'content',
      'group'  => 'button',
      'type'   => 'color',
      'label'  => esc_html__( 'Background', 'extras' ),
      'css'    => [
        [
          'property' => 'background-color',
          'selector' => $button,
        ],
      ],
    ];

    $this->controls['buttonBorder'] = [
      'tab'    => 'content',
      'group'  => 'button',
      'type'   => 'border',
      'label'  => esc_html__( 'Border', 'extras' ),
      'css'    => [
        [
          'property' => 'border',
          'selector' => $button,
        ],
      ],
    ];

    $this->controls['buttonBoxShadow'] = [
      'tab'    => 'content',
      'group'  => 'button',
      'label'  => esc_html__( 'Box Shadow', 'extras' ),
      'type'   => 'box-shadow',
      'css'    => [
        [
          'property' => 'box-shadow',
          'selector' => $button,
        ],
      ],
    ];


    /* added styles */

    $this->controls['buttonCopiedSep'] = [
      'tab'   => 'content',
      'group'  => 'button',
      'type'  => 'separator',
      'label'  => esc_html__( "Button 'added' state", 'extras' ),
      'required' => ['buttonType','!=',['remove','clear','count']]
    ];

    $buttonCopied = '.x-favorite[aria-pressed=true]';

    $this->controls['buttonCopiedTypography'] = [
      'tab'    => 'content',
      'group'  => 'button',
      'type'   => 'typography',
      'label'  => esc_html__( 'Typography', 'extras' ),
      'css'    => [
        [
          'property' => 'font',
          'selector' => $buttonCopied,
        ],
      ],
      'required' => ['buttonType','!=',['remove','clear','count']]
    ];

    $this->controls['buttonCopiedBackgroundColor'] = [
      'tab'    => 'content',
      'group'  => 'button',
      'type'   => 'color',
      'label'  => esc_html__( 'Background', 'extras' ),
      'css'    => [
        [
          'property' => 'background-color',
          'selector' => $buttonCopied,
        ],
      ],
      'required' => ['buttonType','!=',['remove','clear','count']]
    ];

    $this->controls['buttonCopiedBorder'] = [
      'tab'    => 'content',
      'group'  => 'button',
      'type'   => 'border',
      'label'  => esc_html__( 'Border', 'extras' ),
      'css'    => [
        [
          'property' => 'border',
          'selector' => $buttonCopied,
        ],
      ],
      'required' => ['buttonType','!=',['remove','clear','count']]
    ];

    $this->controls['buttonCopiedBoxShadow'] = [
      'tab'    => 'content',
      'group'  => 'button',
      'label'  => esc_html__( 'Box Shadow', 'extras' ),
      'type'   => 'box-shadow',
      'css'    => [
        [
          'property' => 'box-shadow',
          'selector' => $buttonCopied,
        ],
      ],
      'required' => ['buttonType','!=',['remove','clear','count']]
    ];


    /* cleared styles */

    $this->controls['buttonClearedSep'] = [
      'tab'   => 'content',
      'group'  => 'button',
      'type'  => 'separator',
      'label'  => esc_html__( "Button 'cleared' state", 'extras' ),
      'required' => ['buttonType','=',['clear']]
    ];

    $buttonCleared = '.x-favorite_cleared';

    $this->controls['buttonClearedTypography'] = [
      'tab'    => 'content',
      'group'  => 'button',
      'type'   => 'typography',
      'label'  => esc_html__( 'Typography', 'extras' ),
      'css'    => [
        [
          'property' => 'font',
          'selector' => $buttonCleared,
        ],
      ],
      'required' => ['buttonType','=',['clear']]
    ];

    $this->controls['buttonClearedBackgroundColor'] = [
      'tab'    => 'content',
      'group'  => 'button',
      'type'   => 'color',
      'label'  => esc_html__( 'Background', 'extras' ),
      'css'    => [
        [
          'property' => 'background-color',
          'selector' => $buttonCleared,
        ],
      ],
      'required' => ['buttonType','=',['clear']]
    ];

    $this->controls['buttonClearedBorder'] = [
      'tab'    => 'content',
      'group'  => 'button',
      'type'   => 'border',
      'label'  => esc_html__( 'Border', 'extras' ),
      'css'    => [
        [
          'property' => 'border',
          'selector' => $buttonCleared,
        ],
      ],
      'required' => ['buttonType','=',['clear']]
    ];

    $this->controls['buttonClearedBoxShadow'] = [
      'tab'    => 'content',
      'group'  => 'button',
      'label'  => esc_html__( 'Box Shadow', 'extras' ),
      'type'   => 'box-shadow',
      'css'    => [
        [
          'property' => 'box-shadow',
          'selector' => $buttonCleared,
        ],
      ],
      'required' => ['buttonType','=',['clear']]
    ];


    $this->controls['buttonTransition'] = [
      'tab'    => 'content',
      'group'  => 'button',
      'label'  => esc_html__( 'Transition durion', 'extras' ),
      'type'   => 'text',
      'css'    => [
        [
          'property' => '--x-favorite-duration',
          'selector' => $button,
        ],
      ],
      'inline' => true,
      'hasDynamicData' => false,
      'placeholder' => '300ms',
      
    ];


    $this->controls['buttonPaddingSep'] = [
      'tab'   => 'content',
      'group'  => 'button',
      'type'  => 'separator',
    ];

    $this->controls['buttonPadding'] = [
      'tab'   => 'content',
      'group' => 'button',
      'label' => esc_html__( 'Button padding', 'extras' ),
      'type'  => 'dimensions',
      'placeholder' => [
       'top' => '20px',
       'right' => '20px',
       'bottom' => '20px',
       'left' => '20px',
     ],
      'css'   => [
        [
          'property' => 'padding',
          'selector' => $button,
        ],
      ],
    ];

   

    $this->controls['buttonFlexSep'] = [
      'tab'   => 'content',
      'group'  => 'button',
      'type'  => 'separator',
    ];

    $this->controls['buttonDirection'] = [
			'tab'      => 'content',
			'label'    => esc_html__( 'Direction', 'bricks' ),
			'group'		  => 'button',
			'tooltip'  => [
				'content'  => 'flex-direction',
				'position' => 'top-left',
			],
			'type'     => 'direction',
			'css'      => [
				[
					'property' => 'flex-direction',
					'selector' => $button,
				],
			],
			'inline'   => true,
			//'required' => [ '_display', '=', 'flex' ],
		];

		$this->controls['buttonJustifyContent'] = [
			'tab'      => 'content',
			'label'    => esc_html__( 'Align main axis', 'bricks' ),
			'group'		  => 'button',
			'tooltip'  => [
				'content'  => 'justify-content',
				'position' => 'top-left',
			],
			'type'     => 'justify-content',
			'css'      => [
				[
					'property' => 'justify-content',
					'selector' => $button,
				],
			],
			//'required' => [ '_display', '=', 'flex' ],
		];

		$this->controls['buttonAlignItems'] = [
			'tab'      => 'content',
			'label'    => esc_html__( 'Align cross axis', 'bricks' ),
			'group'		  => 'button',
			'tooltip'  => [
				'content'  => 'align-items',
				'position' => 'top-left',
			],
			'type'     => 'align-items',
			'css'      => [
				[
					'property' => 'align-items',
					'selector' => $button,
				],
			],
			//'required' => [ '_display', '=', 'flex' ],
		];

		$this->controls['_columnGap'] = [
			'tab'      => 'content',
			'label'    => esc_html__( 'Column gap', 'bricks' ),
			'group'		  => 'button',
			'type'     => 'number',
			'units'    => true,
			'css'      => [
				[
					'property' => 'column-gap',
					'selector' => $button,
				],
			],
			'info'     => sprintf( __( 'Current browser support: %s (no IE). Use margins for max. browser support.', 'bricks' ), '89%' ),
			//'required' => [ '_display', '=', 'flex' ],
		];

		$this->controls['buttonRowGap'] = [
			'tab'      => 'content',
			'label'    => esc_html__( 'Row gap', 'bricks' ),
			'group'		  => 'button',
			'type'     => 'number',
			'units'    => true,
			'css'      => [
				[
					'property' => 'row-gap',
					'selector' => $button,
				],
			],
			'info'     => sprintf( __( 'Current browser support: %s (no IE). Use margins for max. browser support.', 'bricks' ), '89%' ),
			//'required' => [ '_display', '=', 'flex' ],
		];



    /* loading animation */

    $this->controls['loadingSep'] = [
      'tab'   => 'content',
      'group'  => 'animation',
      'type'  => 'separator',
      'label' => esc_html__( 'Loading animation', 'bricks' ),
    ];

    $this->controls['maybeSpinner'] = [
      'tab'   => 'content',
      'group'  => 'animation',
      'type'  => 'select',
      'label' => esc_html__( 'Spinner', 'bricks' ),
      'options' => [
        'enable' => esc_html__( 'Enable', 'bricks' ),
        'disable' => esc_html__( 'Disable', 'bricks' ),
      ],
      'inline'      => true,
      'placeholder' => esc_html__( 'Enable', 'bricks' ),
    ];

    $this->controls['spinnerPrev'] = [
      'tab'   => 'content',
      'group'  => 'animation',
      'type'  => 'checkbox',
      'label' => esc_html__( 'Preview in builder', 'bricks' ),
      'required' => ['maybeSpinner','!=','disable']
    ];

    $this->controls['spinnerColor'] = [
      'tab'   => 'content',
      'group'  => 'animation',
      'type'  => 'color',
      'label' => esc_html__( 'Spinner color', 'bricks' ),
      'css'   => [
        [
          'property' => 'color',
          'selector' => '&[data-x-spinner] .x-favorite_icons::before',
        ],
        [
          'property' => 'color',
          'selector' => '&[data-x-spinner] .x-favorite_text:only-child::before',
        ],
      ],
      'required' => ['maybeSpinner','!=','disable']
    ];

    $this->controls['spinnerTrackColor'] = [
      'tab'   => 'content',
      'group'  => 'animation',
      'type'  => 'color',
      'label' => esc_html__( 'Track color', 'bricks' ),
      'css'   => [
        [
          'property' => '--x-spinner-track-color',
          'selector' => '',
        ],
      ],
      'required' => ['maybeSpinner','!=','disable']
    ];

    $this->controls['spinnerStrokeWidth'] = [
      'tab'   => 'content',
      'group'  => 'animation',
      'type'  => 'number',
      'placeholder' => '1px',
      'small' => true,
      'units' => true,
      'label' => esc_html__( 'Spinner width', 'bricks' ),
      'css'   => [
        [
          'property' => '--x-spinner-stroke-width',
          'selector' => '',
        ],
      ],
      'required' => ['maybeSpinner','!=','disable']
    ];

    $this->controls['spinnerSize'] = [
      'tab'   => 'content',
      'group'  => 'animation',
      'type'  => 'number',
      'units' => true,
      'small' => true,
      'placeholder' => '0.8em',
      'label' => esc_html__( 'Spinner size', 'bricks' ),
      'css'   => [
        [
          'property' => '--x-spinner-size',
          'selector' => '',
        ],
      ],
      'required' => ['maybeSpinner','!=','disable']
    ];


  }

  
  public function enqueue_scripts() {
    wp_enqueue_script( 'x-favorite', BRICKSEXTRAS_URL . 'components/assets/js/' . \BricksExtras\Helpers::maybeMinifyScripts('favorite') . '.js', '', '3.0.0', true );

    wp_localize_script(
      'x-favorite',
      'xFavoriteObject',
      [
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
        'sec' => wp_create_nonce('favorite_nonce'),
        'user' => is_user_logged_in() ? 'true' : 'false'
      ]
    );

    if ( !BricksExtras\Helpers::maybePreview() ) {

      wp_enqueue_script( 'x-popper', BRICKSEXTRAS_URL . 'components/assets/js/popper.js', '', '1.0.0', true );
      wp_enqueue_script( 'x-favorite-popover', BRICKSEXTRAS_URL . 'components/assets/js/' . \BricksExtras\Helpers::maybeMinifyScripts('favoritepopover') . '.js', '', '2.0.1', true );

      wp_localize_script(
        'x-favorite-popover',
        'xFavoriteTippy',
        [
          'Instances' => [],
        ]
      );

    }

    if (! \BricksExtras\Helpers::elementCSSAdded($this->name) ) {
      wp_enqueue_style( 'x-favorite', BRICKSEXTRAS_URL . 'components/assets/css/favorite.css', [], '' );
    }
  }
  
  public function render() {

   
    $settings = $this->settings;

    $access = isset( $settings['access'] ) ? esc_attr( $settings['access'] ) : 'logged_in';
    $buttonType = isset( $settings['buttonType'] ) ? esc_attr( $settings['buttonType'] ) : 'add_remove';

    
    $maybeIcons = isset( $settings['maybeIcons'] ) ? 'enable' === $settings['maybeIcons'] : true;
    $addIcon = empty( $this->settings['addIcon'] ) ? false : self::render_icon( $this->settings['addIcon'] );
    $clearedIcon = empty( $this->settings['clearedIcon'] ) ? false : self::render_icon( $this->settings['clearedIcon'] );
    $removeIcon = empty( $this->settings['removeIcon'] ) ? false : self::render_icon( $this->settings['removeIcon'] );

    if ( !$addIcon ) {
      $addIcon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M458.4 64.3C400.6 15.7 311.3 23 256 79.3 200.7 23 111.4 15.6 53.6 64.3-21.6 127.6-10.6 230.8 43 285.5l175.4 178.7c10 10.2 23.4 15.9 37.6 15.9 14.3 0 27.6-5.6 37.6-15.8L469 285.6c53.5-54.7 64.7-157.9-10.6-221.3zm-23.6 187.5L259.4 430.5c-2.4 2.4-4.4 2.4-6.8 0L77.2 251.8c-36.5-37.2-43.9-107.6 7.3-150.7 38.9-32.7 98.9-27.8 136.5 10.5l35 35.7 35-35.7c37.8-38.5 97.8-43.2 136.5-10.6 51.1 43.1 43.5 113.9 7.3 150.8z"/></svg>';
    }

    if ( !$removeIcon ) {
      $removeIcon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M462.3 62.6C407.5 15.9 326 24.3 275.7 76.2L256 96.5l-19.7-20.3C186.1 24.3 104.5 15.9 49.7 62.6c-62.8 53.6-66.1 149.8-9.9 207.9l193.5 199.8c12.5 12.9 32.8 12.9 45.3 0l193.5-199.8c56.3-58.1 53-154.3-9.8-207.9z"/></svg>';

      if ( 'remove' === $buttonType || 'clear' === $buttonType ) {
        $removeIcon = '<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 512 512"><path d="M405 136.798L375.202 107 256 226.202 136.798 107 107 136.798 226.202 256 107 375.202 136.798 405 256 285.798 375.202 405 405 375.202 285.798 256z" fill="currentColor"></path></svg>';
      }
    }

    if (!$clearedIcon) {
      $clearedIcon = '<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 512 512"><path d="M405 136.798L375.202 107 256 226.202 136.798 107 107 136.798 226.202 256 107 375.202 136.798 405 256 285.798 375.202 405 405 375.202 285.798 256z" fill="currentColor"></path></svg>';
    }
    

    $addButtonText = isset( $settings['addButtonText'] ) ? esc_attr__( $settings['addButtonText'] ) : false;
    $addedButtonText = isset( $settings['addedButtonText'] ) ? esc_attr__( $settings['addedButtonText'] ) : false;
    $removeButtonText = isset( $settings['removeButtonText'] ) ? esc_attr__( $settings['removeButtonText'] ) : false;
    $maxReachedButtonText = isset( $settings['maxReachedButtonText'] ) ? esc_attr__( $settings['maxReachedButtonText'] ) : false;

    $countButtonText = isset( $settings['countButtonText'] ) ? esc_attr__( $settings['countButtonText'] ) : false;

    $ariaLabel = isset( $settings['ariaLabel'] ) ? esc_attr__( $settings['ariaLabel'] ) : esc_attr__('add item to favorite' );
    $removeariaLabel = isset( $settings['removeariaLabel'] ) ? esc_attr__( $settings['removeariaLabel'] ) : esc_attr__('remove item' );
    $clearariaLabel = isset( $settings['clearariaLabel'] ) ? esc_attr__( $settings['clearariaLabel'] ) : esc_attr__('clear all');
    $countariaLabel = isset( $settings['countariaLabel'] ) ? esc_attr__( $settings['countariaLabel'] ) : esc_attr__('go to favorite');

    $maybeToolip = isset( $settings['maybeToolip'] ) ? 'enable' === $settings['maybeToolip'] : false;

    $iconAnimation = isset( $settings['iconAnimation'] ) ? $settings['iconAnimation'] : 'fade';

    $addedTooltipText = isset( $settings['addedTooltipText'] ) ? esc_attr__( str_replace('"', "'", $this->settings['addedTooltipText']) ) : esc_attr__( 'Added to favorites' );
    $removedTooltipText = isset( $settings['removedTooltipText'] ) ? esc_attr__( str_replace('"', "'", $this->settings['removedTooltipText']) ) : esc_attr__( 'Removed from favorites' );
    $addTooltipText = isset( $settings['addTooltipText'] ) ? esc_attr__( str_replace('"', "'", $this->settings['addTooltipText']) ) : esc_attr__( 'Add to favorites' );
    $disabledText = isset( $settings['disabledText'] ) ? esc_html( str_replace('"', "'", $this->settings['disabledText']) ) : esc_attr__( 'Login to enable ' );
    $removeTooltipText = isset( $settings['removeTooltipText'] ) ? esc_attr__( str_replace('"', "'", $this->settings['removeTooltipText']) ) : esc_attr__( 'Remove' );

    $clearTooltipText = isset( $settings['clearTooltipText'] ) ? esc_attr__( str_replace('"', "'", $this->settings['clearTooltipText']) ) : esc_attr__( 'Clear all' );
    $clearedTooltipText = isset( $settings['clearedTooltipText'] ) ? esc_attr__( str_replace('"', "'", $this->settings['clearedTooltipText']) ) :  esc_attr__('Cleared');

    $clearButtonText = isset( $settings['clearButtonText'] ) ? esc_attr( $settings['clearButtonText'] ) : esc_attr__( 'Clear all' );
    $clearedButtonText = isset( $settings['clearedButtonText'] ) ? esc_attr( $settings['clearedButtonText'] ) : '';

    $tooltipShow = isset( $settings['tooltipShow'] ) ? esc_attr( $settings['tooltipShow'] ) : 'hocus';

    $maybeSpinner = isset( $settings['maybeSpinner'] ) ? 'enable' === $settings['maybeSpinner'] : true;

    $hideIfZeroCount = isset( $settings['hideIfZeroCount'] ) ? 'enable' === $settings['hideIfZeroCount'] : false;

    if ('hocus' === $tooltipShow) {
      $tooltipReveal = 'mouseenter click focus';
    } else {
      $tooltipReveal = 'manual';
    }



    $config = [];

    $postType = isset( $settings['postType'] ) ? esc_attr( $settings['postType'] ) : 'default';
    $listSlug = isset( $settings['newList'] ) && isset( $settings['listSlug'] ) ? $settings['listSlug'] : false;
    $listMaximum = isset( $settings['listMaximum'] ) ? $settings['listMaximum'] : false;

    if (!!$listMaximum) {
      $this->set_attribute( '_root', 'data-x-list-max', intval( $listMaximum ) );

      if ( $buttonType === 'add_remove' && $maxReachedButtonText ) {
        $config += [ 'maxReachedText' => $maxReachedButtonText ];
      }

    }

    if ( 'default' === $postType ) {
      
      if (method_exists('\Bricks\Query','is_any_looping') && \Bricks\Query::is_any_looping() ) {

        if ( \Bricks\Query::is_any_looping() && \Bricks\Query::get_loop_object() ) {
          $postType = get_post_type( \Bricks\Query::get_loop_object() );
        }

      } else {
        $postType = 'post';
      }

    }

    if ( !!$listSlug && isset( $settings['newList'] ) ) { 

      $listSlug = strtolower($listSlug);                     // Convert to lowercase
      $listSlug = preg_replace('/\s+/', '_', $listSlug);      // Replace spaces with underscores
      $suffix = preg_replace('/[^a-z0-9_]/', '', $listSlug);    // Remove characters that are not letters, numbers, or underscores

      $listName = $postType . '__' . $suffix;
      
    } else {
      $listName = $postType;
    }

    $this->set_attribute( '_root', 'data-x-list', esc_attr($listName) );

    $this->set_attribute( '_root', 'data-x-type', esc_attr($buttonType) );

    if ($maybeSpinner) {
      $this->set_attribute( '_root', 'data-x-spinner', 'true' );
    }

    if ($maybeToolip) {
      $config += [
        'placement' => isset( $this->settings['placement'] ) ? esc_attr( $this->settings['placement'] ) : 'top',
        'offsetSkidding' => isset( $this->settings['offsetSkidding'] ) ? intval( $this->settings['offsetSkidding'] ) : 0,
        'offsetDistance' => isset( $this->settings['offsetDistance'] ) ? intval( $this->settings['offsetDistance'] ) : 10,
        'delay' => isset( $this->settings['delay'] ) ? intval( $this->settings['delay'] ) : 0,
        'followCursor' => isset( $this->settings['followCursor'] ) ? $this->settings['followCursor'] : 'false',
        'tooltipAddedText' => $addedTooltipText,
        'removedTooltipText' => $removedTooltipText,
        'removedText' => $addTooltipText,
        'removeTooltipText' => $removeTooltipText,
        'tooltipReveal' =>  $tooltipReveal,
        'clearTooltipText'  => $clearTooltipText,
        'clearedTooltipText'  => $clearedTooltipText,
        'maxReachedTooltipText' => isset( $this->settings['maxReachedTooltipText'] ) ?  esc_attr__( str_replace('"', "'", $this->settings['maxReachedTooltipText']) ) : esc_attr__('Maximum Reached'),
      ];
    }



    if ( $addButtonText && $buttonType === 'add_remove' ) {
      $config += [ 'addText' => $addButtonText ];
    }

    if ( $addedButtonText && $buttonType !== 'remove' ) {
      $config += [ 'addedText' => $addedButtonText ];
    }

    if ('clear' === $buttonType) {
      if ( $clearButtonText ) {
        $config += [ 'clearText' => $clearButtonText ];
      }
      if ( $clearedButtonText ) {
        $config += [ 'clearedText' => $clearedButtonText ];
      }
    }

    if ('count' === $buttonType && $hideIfZeroCount) {
      $config += [ 'hideZero' => 'true' ];
    }

    
    if ( method_exists('\Bricks\Query','is_any_looping') ) {

      $query_id = \Bricks\Query::is_any_looping();

      if ( $query_id ) {
        $config += [ 'isLooping' => \Bricks\Query::get_query_element_id( $query_id ) ];
        $config += [ 'loopIndex' => \Bricks\Query::get_loop_index()  ];

        if ( BricksExtras\Helpers::get_bricks_looping_parent_query_id_by_level(2) ) {
          $loopIndex = \Bricks\Query::get_query_for_element_id( \Bricks\Query::get_query_element_id( BricksExtras\Helpers::get_bricks_looping_parent_query_id_by_level(2) ) )->loop_index . '_' . \Bricks\Query::get_query_for_element_id( \Bricks\Query::get_query_element_id( BricksExtras\Helpers::get_bricks_looping_parent_query_id_by_level(1) ) )->loop_index . '_' . \Bricks\Query::get_loop_index();
        } else {
          if ( BricksExtras\Helpers::get_bricks_looping_parent_query_id_by_level(1) ) {
            $loopIndex = \Bricks\Query::get_query_for_element_id( \Bricks\Query::get_query_element_id( BricksExtras\Helpers::get_bricks_looping_parent_query_id_by_level(1) ) )->loop_index . '_' . \Bricks\Query::get_loop_index();
          } else {
            $loopIndex = \Bricks\Query::get_loop_index();
          }
        }			

        $uniqueID = $this->id . '_' . $loopIndex;
        
      } else {
        $uniqueID = $this->id;
      }

    } 


    if ('logged_in' === $access && !is_user_logged_in() ) {
      $this->set_attribute( 'x-favorite', 'disabled', '' );
      $this->set_attribute( 'x-favorite', 'data-disabled', '' );

      $config += [ 'disabledText' => htmlspecialchars( $disabledText, ENT_QUOTES, 'UTF-8' ) ];
    }

    
    

    $this->set_attribute( '_root', 'data-x-id', esc_attr( $uniqueID ) );
    $this->set_attribute( '_root', 'data-x-favorite', wp_json_encode($config) );

    $tag = 'count' === $buttonType ? 'span' : 'button';

    $link = ! empty( $settings['link'] ) ? $settings['link'] : '';

    if ( $link ) {
      $tag = 'a';
    }

    if ('count' !== $buttonType) {

      $inFavorite = false;

      $postID = intval ( get_the_ID() );

      if ( $postID ) {

        $x_favorite_ids = [];

          $this->set_attribute( '_root', 'data-post-id', intval( get_the_ID() ) );

          if ( !is_user_logged_in() ) {

            if ( isset( $_COOKIE['x_favorite_ids__' . $listName] ) ) {
              $x_favorite_ids = json_decode( stripslashes( $_COOKIE['x_favorite_ids__' . $listName] ), true);

              if (in_array( $postID, $x_favorite_ids ) ) {
                $inFavorite = true;
              }

            }

          } else {

            global $x_favorite_data_global;

            if ( $x_favorite_data_global && is_array( $x_favorite_data_global ) ) {
                if (isset($x_favorite_data_global[$listName]) && in_array($postID, $x_favorite_data_global[$listName])) {
                  $inFavorite = true;
                }
              }

            } 

      }

    } else {

        if ( $link ) {
          $this->set_link_attributes( 'x-favorite', $link );
        }
    }

    if ($maybeToolip) {
      $this->set_attribute( '_root', 'data-x-tooltip', 'true' );
    }

    $this->set_attribute( "x-favorite", 'class', 'x-favorite' );

    if ( 'add_remove' === $buttonType ) { 

      $this->set_attribute( 'x_favorite-added-icon', 'class', 'x_favorite-added-icon' );

      $this->set_attribute( 'x_favorite-icon', 'class', 'x_favorite-removed-icon' );
    
      if ( $inFavorite ) { 
        
        $this->set_attribute( 'x-favorite', 'class', 'x-favorite_added' );
        $this->set_attribute( 'x-favorite', 'aria-pressed', 'true' );
        
      } else {
        $this->set_attribute( 'x-favorite', 'aria-pressed', 'false' );
      }

    } elseif ( 'remove' === $buttonType || 'clear' === $buttonType ) {
      $this->set_attribute( 'x-favorite', 'aria-pressed', 'false' );
    }

    if ( isset( $settings['spinnerPrev'] ) && BricksExtras\Helpers::maybePreview() ) {
      $this->set_attribute( 'x-favorite', 'class', 'x-favorite_busy' );
    }

    if ('clear' === $buttonType) {

      $this->set_attribute( 'x_favorite-added-icon', 'class', 'x_favorite-clear-icon' );
      $this->set_attribute( 'x_favorite-icon', 'class', 'x_favorite-cleared-icon' );

    }

    /* aria labels */
    if ('add_remove' === $buttonType) {
      $this->set_attribute( "x-favorite", 'aria-label', $ariaLabel );
    } elseif ('remove' === $buttonType) {
      $this->set_attribute( "x-favorite", 'aria-label', $removeariaLabel );
    } elseif ('clear' === $buttonType) {
      $this->set_attribute( "x-favorite", 'aria-label', $clearariaLabel );
    } elseif ('count' === $buttonType && $link ) {
      $this->set_attribute( "x-favorite", 'aria-label', $countariaLabel );
    }




    $this->set_attribute( "x-favorite-tooltip-content", 'id', esc_attr( 'x-favorite-tooltip-content_' . $uniqueID ) );

    
    $this->set_attribute( 'x_favorite-icon', 'class', 'x-favorite_icon' );
    $this->set_attribute( 'x_favorite-icon', 'aria-hidden', 'true' );
    
    $this->set_attribute( 'x_favorite-added-icon', 'class', 'x-favorite_icon' );
    $this->set_attribute( 'x_favorite-added-icon', 'aria-hidden', 'true' );

    $this->set_attribute( 'x-favorite_icons', 'class', 'x-favorite_icons' );

    $this->set_attribute( 'x-favorite_count', 'class', 'x-favorite_count' );
    $this->set_attribute( 'x-favorite_count-inner', 'class', 'x-favorite_count-inner' );
  
    $this->set_attribute( "x-favorite-tooltip-content", 'class', [ 'x-favorite-tooltip-content' ] );
    $this->set_attribute( "x-favorite-tooltip-content", 'role', [ 'tooltip' ] );

    $this->set_attribute( "tippy-content", 'class', [ 'tippy-content' ] );
    $this->set_attribute( "tippy-content", 'data-state', [ 'visible' ] );

    $this->set_attribute( "tippy-root", 'data-tippy-root', '' );

    $this->set_attribute( "tippy-box", 'class', 'tippy-box' );
    $this->set_attribute( "tippy-box", 'data-state', 'visible' );
    $this->set_attribute( "tippy-box", 'tabindex', '1' );
    $this->set_attribute( "tippy-box", 'data-theme', 'extras' );
    $this->set_attribute( "tippy-box", 'data-animation', 'extras' );

    $this->set_attribute( "x-favorite_text", 'class', 'x-favorite_text' );

    $this->set_attribute( "x-favorite_text-inner", 'class', 'x-favorite_text-inner' );
    
    

    echo "<div {$this->render_attributes( '_root' )}>";

    echo "<" . $tag . " {$this->render_attributes( 'x-favorite' )}>";


    if ($addButtonText && $buttonType === 'add_remove') {
      if ( $addedButtonText && ( $inFavorite || ( 'remove' === $buttonType || 'clear' === $buttonType ) ) ) { 
        echo "<span {$this->render_attributes( 'x-favorite_text' )}>" . "<span {$this->render_attributes( 'x-favorite_text-inner' )}>" . $addedButtonText . "</span></span>"; 
      } else {
        echo "<span {$this->render_attributes( 'x-favorite_text' )}>" . "<span {$this->render_attributes( 'x-favorite_text-inner' )}>" . $addButtonText . "</span></span>"; 
      }
    }

    if ($removeButtonText && $buttonType === 'remove') {
      echo "<span {$this->render_attributes( 'x-favorite_text' )}>" . "<span {$this->render_attributes( 'x-favorite_text-inner' )}>" . $removeButtonText . "</span></span>"; 
    }

    if ($clearButtonText && $buttonType === 'clear') {
      echo "<span {$this->render_attributes( 'x-favorite_text' )}>" . "<span {$this->render_attributes( 'x-favorite_text-inner' )}>" . $clearButtonText . "</span></span>"; 
    }

    if ($countButtonText && $buttonType === 'count') {
      echo "<span {$this->render_attributes( 'x-favorite_text' )}>" . "<span {$this->render_attributes( 'x-favorite_text-inner' )}>" . $countButtonText . "</span></span>"; 
    }

    echo \Bricks\Frontend::render_children( $this );

    if ($maybeIcons) {
      echo "<span {$this->render_attributes( 'x-favorite_icons' )}>"; 
      if ( 'add_remove' === $buttonType || 'clear' === $buttonType || 'remove' === $buttonType ) {
        echo "<span {$this->render_attributes( 'x_favorite-added-icon' )}> " . $removeIcon . " </span>";
      }
      if ( 'add_remove' === $buttonType || 'count' === $buttonType ) {
        echo "<span {$this->render_attributes( 'x_favorite-icon' )}> " . $addIcon . "  </span>";
      }
      if ( 'clear' === $buttonType ) {
        echo "<span {$this->render_attributes( 'x_favorite-icon' )}> " . $clearedIcon . "  </span>";
      }
      echo "</span>";
    }

    echo "</" . $tag . ">";

    if ( BricksExtras\Helpers::maybePreview() ) {
      echo "<div {$this->render_attributes( 'x-favorite-tooltip-content' )}>";
      echo "<div {$this->render_attributes( 'tippy-root' )}>";
      echo "<div {$this->render_attributes( 'tippy-box' )}>";
      echo "<div {$this->render_attributes( 'tippy-content' )}>";
      echo "<div {$this->render_attributes( 'x-favorite-tooltip-content-inner' )}>" . $addedTooltipText . "</div>";
      echo "</div>";
      echo "</div>";
      echo "</div>";
      echo "</div>";
    }

    if ( 'count' === $buttonType ) {

      if ( isset( $_COOKIE['x_favorite_ids'] ) ) {

        // Get favorite data from cookie
				$postArray = \BricksExtras\Helpers::get_favorite_ids_array( $postType );
        $count = is_array( $postArray ) && is_user_logged_in() ? count( $postArray ) : 0;

      } else {
        $count = 0;
      }

      if ( (0 === $count ) && $hideIfZeroCount) {
        $this->set_attribute( 'x-favorite_count', 'class', 'x-favorite_count-zero' );
      }

      echo "<span {$this->render_attributes( 'x-favorite_count' )}><span {$this->render_attributes( 'x-favorite_count-inner' )}>" . $count . "</span></span>";

    }
    

    echo "</div>";





    if ( $maybeToolip && !BricksExtras\Helpers::maybePreview() ) {

      wp_enqueue_script( 'x-popper', BRICKSEXTRAS_URL . 'components/assets/js/popper.js', '', '1.0.0', true );
      wp_enqueue_script( 'x-favorite-popover', BRICKSEXTRAS_URL . 'components/assets/js/favoritepopover.js', ['x-popper','x-favorite'], '1.0.5', true );

      wp_localize_script(
        'x-favorite-popover',
        'xFavoriteTippy',
        [
          'Instances' => [],
        ]
      );

    }




  }



  

}