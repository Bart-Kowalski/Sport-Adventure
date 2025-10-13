<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class X_Copy_To_Clipboard extends \Bricks\Element {

  // Element properties
  public $category     = 'extras';
	public $name         = 'xcopytoclipboard';
	public $icon         = 'ti-files';
	public $css_selector = '';
	public $scripts      = ['xCopyToClipBoard','xCopyToClipboardPopover'];
  public $nestable = true;

  
  public function get_label() {
	return esc_html__( 'Copy to Clipboard', 'extras' );
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

    $this->control_groups['tooltip'] = [
      'title' => esc_html__( 'Tooltip', 'bricks' ),
      'tab'   => 'content',
    ];

    $this->control_groups['behaviour'] = [
      'title' => esc_html__( 'Behaviour', 'bricks' ),
      'tab'   => 'content',
    ];

  }

  public function set_controls() {


    $innerSelector = '.x-copy-to-clipboard';

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


    $this->controls['copyFrom'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Text to copy', 'bricks' ),
			'type' => 'select',
			'options' => [
			  'selector' => esc_html__( 'Choose Selector', 'bricks' ),
			  'dynamic_data' => esc_html__( 'From Dynamic data', 'bricks' ),
			],
			'inline'      => true,
			'clearable' => false,
			'placeholder' => esc_html__( 'Selector', 'bricks' ),
		];

    $this->controls['copySelector'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Copy selector', 'bricks' ),
      'info' => esc_html__( 'ID or class of element containing the text', 'bricks' ),
			'type' => 'text',
			'inline'      => true,
			'clearable' => false,
      'required' => ['copyFrom','!=','dynamic_data']
		];

    $this->controls['copytext'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Copy text', 'bricks' ),
			'type' => 'textarea',
			'clearable' => false,
      'required' => ['copyFrom','=','dynamic_data']
		];

    $this->controls['stripTags'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Strip HTML tags', 'bricks' ),
			'type' => 'select',
      'inline' => true,
			'options' => [
			  'enable' => esc_html__( 'Enable', 'bricks' ),
			  'disable' => esc_html__( 'Disable', 'bricks' ),
			],
      'required' => ['copyFrom','=','dynamic_data'],
      'placeholder' => esc_html__( 'Enable', 'bricks' ),
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

    $this->controls['copyIcon'] = [
      'tab'     => 'content',
      'label'   => esc_html__( 'Copy Icon', 'bricks' ),
      'group' => 'icons',
      'type'    => 'icon',
      'css'      => [
        [
          'selector' => '.x_copy-icon',
        ],
      ],
      'default' => [
        'library' => 'themify',
        'icon' => 'ti-files',
      ],
      'required' => ['maybeIcons','!=','disable']
    ];

    $this->controls['afterPressed'] = [
			'tab' => 'content',
			'label' => esc_html__( 'On copy completed..', 'bricks' ),
			'type' => 'select',
      'group' => 'icons',
			'options' => [
			  'check' => esc_html__( 'Show animated check', 'bricks' ),
			  'icon' => esc_html__( 'Show custom icon', 'bricks' ),
			],
			'inline'      => true,
			'clearable' => false,
			'placeholder' => esc_html__( 'Animated check', 'bricks' ),
      'required' => ['maybeIcons','!=','disable']
		];

    $this->controls['copiedIcon'] = [
      'tab'     => 'content',
      'label'   => esc_html__( 'Copied Icon', 'bricks' ),
      'group' => 'icons',
      'type'    => 'icon',
      'css'      => [
        [
          'selector' => '.x_copied-icon',
        ],
      ],
      'default' => [
        'library' => 'themify',
        'icon' => 'ti-check',
      ],
      'required' => [
        ['afterPressed','=','icon'],
        ['maybeIcons','!=','disable']
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
          'selector' => '.x-copy-to-clipboard_icons',
        ],
      ],
      'required' => ['maybeIcons','!=','disable']
    ];

    

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

    $this->controls['animatedIconSeparation'] = [
      'tab'    => 'content',
      'group'  => 'icons',
      'type'   => 'separator',
      'required' => [
        ['maybeIcons','!=','disable'],
        ['afterPressed','!=','icon']
      ]
    ];

    $this->controls['animatedIconSize'] = [
      'tab'    => 'content',
      'group'  => 'icons',
      'type'   => 'number',
      'units'   => true,
      'small'  => true,
      'label'  => esc_html__( 'Animated check size', 'extras' ),
      'css'    => [
        [
          'property' => 'width',
          'selector' => '.x-copy-to-clipboard_animated-icon',
        ],
      ],
      'placeholder' => '1em',
      'required' => [
        ['maybeIcons','!=','disable'],
        ['afterPressed','!=','icon']
      ]
    ];

    $this->controls['animatedIconStrokeWidth'] = [
      'tab'    => 'content',
      'group'  => 'icons',
      'type'   => 'number',
      'units'   => false,
      'small'  => true,
      'label'  => esc_html__( 'Animated check line width', 'extras' ),
      'css'    => [
        [
          'property' => 'stroke-width',
          'selector' => '.x-copy-to-clipboard_animated-icon',
        ],
      ],
      'required' => [
        ['maybeIcons','!=','disable'],
        ['afterPressed','!=','icon']
      ]
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



    $this->controls['copyTooltipText'] = [
      'tab'   => 'content',
      'group' => 'tooltip',
      'label' => esc_html__( 'Tooltip text', 'extras' ),
      'type'  => 'text',
      'inline' => true,
      'placeholder' => 'Copy',
      'required' => ['maybeToolip','=','enable']
    ];

    $this->controls['copiedTooltipText'] = [
      'tab'   => 'content',
      'group' => 'tooltip',
      'label' => esc_html__( 'Tooltip text (copied)', 'extras' ),
      'type'  => 'text',
      'inline' => true,
      'placeholder' => 'Copied',
      'required' => ['maybeToolip','=','enable']
    ];

    $this->controls['tooltipShow'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Show on.', 'bricks' ),
			'type' => 'select',
      'group' => 'tooltip', 
			'options' => [
			  'hocus' => esc_html__('Hover / Focus', 'bricks' ), 
				'complete' => esc_html__('When copy complete', 'bricks' ), 
			],
			'inline'      => true,
			'placeholder' => esc_html__( 'Hover / Focus', 'bricks' ), 
			'clearable' => false,
      'required' => ['maybeToolip','=','enable']
		  ];

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
           'property' => '--x-copytoclipboard-background',
           'selector' => '.x-copy-to-clipboard + [data-tippy-root]'
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


     $popoverCopied = '[aria-pressed=true] + [data-tippy-root] .tippy-content';

     $this->controls['popoverCopiedSep'] = [
      'tab'   => 'content',
      'group'  => 'tooltip',
      'type'  => 'separator',
      'label'  => esc_html__( 'Copied state', 'extras' ),
      'required' => ['maybeToolip','=','enable']
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
      'required' => ['maybeToolip','=','enable']
    ];

    $this->controls['popoverCopiedBackgroundColor'] = [
      'tab'    => 'content',
      'group'  => 'tooltip',
      'type'   => 'color',
      'label'  => esc_html__( 'Background', 'extras' ),
      'css'    => [
        [
          'property' => '--x-copytoclipboard-background',
          'selector' => '.x-copy-to-clipboard[aria-pressed=true] + [data-tippy-root]'
        ],
      ],
      'required' => ['maybeToolip','=','enable']
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
      'required' => ['maybeToolip','=','enable']
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
      'required' => ['maybeToolip','=','enable']
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
          'property' => '--x-copytoclipboard-transitionin',
          'selector' => '',
        ],
      ],
      'type'  => 'number',
      'placeholder' => '300',
      'inline' => true,
      'unit' => 'ms',
      'required' => ['maybeToolip','=','enable']
    ];

    $this->controls['popoverTransitionOut'] = [
      'tab'   => 'content',
      'group' => 'tooltip',
      'label' => esc_html__( 'Transition Out (ms)', 'extras' ),
      'css'    => [
        [
          'property' => '--x-copytoclipboard-transitionout',
          'selector' => '',
        ],
      ],
      'type'  => 'number',
      'placeholder' => '300',
      'unit' => 'ms',
      'inline' => true,
      'required' => ['maybeToolip','=','enable']
    ];

    $this->controls['popoverTranslateX'] = [
      'tab'   => 'content',
      'group' => 'tooltip',
      'label' => esc_html__( 'TranslateX', 'extras' ),
      'css'    => [
        [
          'property' => '--x-copytoclipboard-translatex',
          'selector' => '',
        ],
      ],
      'type'  => 'number',
      'placeholder' => '0',
      'inline' => true,
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
          'property' => '--x-copytoclipboard-translatey',
          'selector' => '',
        ],
      ],
      'type'  => 'number',
      'placeholder' => '10',
      'inline' => true,
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
          'property' => '--x-copytoclipboard-scale',
          'selector' => '',
        ],
      ],
      'type'  => 'number',
      'placeholder' => '0.95',
      'inline' => true,
      'required' => ['maybeToolip','=','enable']
    ];




      /* button */


    $button = '.x-copy-to-clipboard';

    $this->controls['copyButtonText'] = [
      'tab'   => 'content',
      'group' => 'button',
      'label' => esc_html__( 'Button text', 'extras' ),
      'type'  => 'text',
      'inline' => true,
      'default' => esc_html__('Copy'),
    ];

    $this->controls['copiedButtonText'] = [
      'tab'   => 'content',
      'group' => 'button',
      'label' => esc_html__( 'Button text (copied)', 'extras' ),
      'type'  => 'text',
      'inline' => true,
    ];

    $this->controls['ariaLabel'] = [
      'tab'   => 'content',
      'group' => 'button',
      'label' => esc_html__( 'Aria Label', 'extras' ),
      'info' => esc_html__( 'Not needed if adding button text', 'extras' ),
      'type'  => 'text',
      'inline' => true,
      'placeholder' => '',
    ];

    $this->controls['buttonWidth'] = [
      'tab'   => 'content',
      'group' => 'button',
      'label' => esc_html__( 'Button width', 'extras' ),
      'type'  => 'number',
      'units'  => true,
      'css'   => [
        [
          'property' => 'width',
          'selector' => $button,
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


    /* copied styles */

    $this->controls['buttonCopiedSep'] = [
      'tab'   => 'content',
      'group'  => 'button',
      'type'  => 'separator',
      'label'  => esc_html__( "Button 'copied' state", 'extras' ),
    ];

    $buttonCopied = '.x-copy-to-clipboard[aria-pressed=true]';

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
    ];

    $this->controls['buttonTransition'] = [
      'tab'    => 'content',
      'group'  => 'button',
      'label'  => esc_html__( 'Transition durion', 'extras' ),
      'type'   => 'text',
      'css'    => [
        [
          'property' => '--x-copytoclipboard-duation',
          'selector' => $button,
        ],
      ],
      'inline' => true,
      'hasDynamicData' => false,
      'placeholder' => '300ms'
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



    /* behaviour */

    $this->controls['hideEmpty'] = [
      'tab'    => 'content',
      'group'  => 'behaviour',
      'inline' => true,
      'type'   => 'select',
      'options' => [
			  'enable' => esc_html__( 'Enable', 'bricks' ),
			  'disable' => esc_html__( 'Disable', 'bricks' ),
			],
      'label'  => esc_html__( 'Hide if no text found to copy', 'extras' ),
      'placeholder' => esc_html__( 'Disable', 'bricks' ),
    ];

    $this->controls['buttonDelay'] = [
      'tab'    => 'content',
      'group'  => 'behaviour',
      'type'   => 'number',
      'label'  => esc_html__( 'Button state delay (ms)', 'extras' ),
      'info'  => esc_html__( 'How long to stay in copied state', 'extras' ),
      'placeholder' => '3000'
    ];

    $this->controls['selectText'] = [
      'tab'    => 'content',
      'group'  => 'behaviour',
      'inline' => true,
      'type'   => 'select',
      'options' => [
			  'enable' => esc_html__( 'Enable', 'bricks' ),
			  'disable' => esc_html__( 'Disable', 'bricks' ),
			],
      'label'  => esc_html__( 'Select text after copying', 'extras' ),
      'placeholder' => esc_html__( 'Disable', 'bricks' ),
    ];

  }

  
  public function enqueue_scripts() {
    wp_enqueue_script( 'x-copy-to-clipboard', BRICKSEXTRAS_URL . 'components/assets/js/' . \BricksExtras\Helpers::maybeMinifyScripts('copytoclipboard') . '.js', '', '1.0.2' );

    if ( BricksExtras\Helpers::maybePreview() ) {

      wp_enqueue_script( 'x-popper', BRICKSEXTRAS_URL . 'components/assets/js/popper.js', '', '1.0.0', true );
      wp_enqueue_script( 'x-copy-to-clipboard-popover', BRICKSEXTRAS_URL . 'components/assets/js/' . \BricksExtras\Helpers::maybeMinifyScripts('copytoclipboardpopover') . '.js', ['x-popper'], '1.0.5', true );

      wp_localize_script(
        'x-copy-to-clipboard-popover',
        'xTippy',
        [
          'Instances' => [],
        ]
      );

    }

    if (! \BricksExtras\Helpers::elementCSSAdded($this->name) ) {
      wp_enqueue_style( 'x--copy-to-clipboard', BRICKSEXTRAS_URL . 'components/assets/css/copytoclipboard.css', [], '' );
    }
  }
  
  public function render() {

    $tag = 'button';
    $settings = $this->settings;

    
    $maybeIcons = isset( $settings['maybeIcons'] ) ? 'enable' === $settings['maybeIcons'] : true;
    $copyIcon = empty( $this->settings['copyIcon'] ) ? false : self::render_icon( $this->settings['copyIcon'] );
    $copiedIcon = empty( $this->settings['copiedIcon'] ) ? false : self::render_icon( $this->settings['copiedIcon'] );
    $afterPressed = isset( $settings['afterPressed'] ) ? esc_attr( $settings['afterPressed'] ) : 'check';

    $copyButtonText = isset( $settings['copyButtonText'] ) ? esc_attr__( $settings['copyButtonText'] ) : false;
    $copiedButtonText = isset( $settings['copiedButtonText'] ) ? esc_attr__( $settings['copiedButtonText'] ) : false;
    $ariaLabel = isset( $settings['ariaLabel'] ) ? esc_attr__( $settings['ariaLabel'] ) : false;

    $maybeToolip = isset( $settings['maybeToolip'] ) ? 'enable' === $settings['maybeToolip'] : false;
    $selectText = isset( $settings['selectText'] ) ? 'enable' === $settings['selectText'] : false;

    $hideEmpty = isset( $settings['hideEmpty'] ) ? 'enable' === $settings['hideEmpty'] : false;

    $copySelector = isset( $settings['copySelector'] ) ? esc_attr( $settings['copySelector'] ) : '';

    $outputIcon = 'check' === $afterPressed ? "<svg width='16' height='16' viewBox='0 0 16 16' fill='none' stroke='currentColor' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round' class='x-copy-to-clipboard_animated-icon'><path d='M13.25 4.75L6 12L2.75 8.75'></path></svg>" : $copiedIcon;

    $copyFrom = isset( $settings['copyFrom'] ) ? esc_attr( $settings['copyFrom'] ) : 'selector';

    $iconAnimation = isset( $settings['iconAnimation'] ) ? esc_attr( $settings['iconAnimation'] ) : 'fade';

    $copyTooltipText = isset( $settings['copyTooltipText'] ) ? esc_attr( $settings['copyTooltipText'] ) : 'Copy';
    $copiedTooltipText = isset( $settings['copiedTooltipText'] ) ? esc_attr( $settings['copiedTooltipText'] ) : 'Copied';

    $copytextSetting = isset( $settings['copytext'] ) ? esc_html__( $settings['copytext'] ) : '';

    $tooltipShow = isset( $settings['tooltipShow'] ) ? esc_attr( $settings['tooltipShow'] ) : 'hocus';

    if ('hocus' === $tooltipShow) {
      $tooltipReveal = 'mouseenter click focus';
    } else {
      $tooltipReveal = 'manual';
    }

    $config = [];

    $copyText = $this->render_dynamic_data( $copytextSetting, 'text' );
    $stripTags = isset( $settings['stripTags'] ) ? 'enable' === $settings['stripTags'] : true;

    $copyTextStripped = wp_strip_all_tags($copyText);

    if ( 'dynamic_data' === $copyFrom ) {
      if ($stripTags) {
        $this->set_attribute( '_root', 'data-x-copy-text', esc_attr( $copyTextStripped ) );
      } else {
        $this->set_attribute( '_root', 'data-x-copy-text', esc_attr( $settings['copytext'] ) );
      }
      
    } else {
      $config += [ 'copySelector' => $copySelector ];
      $config += [ 'selectText' => $selectText ];
    }


    if ($maybeToolip) {
      $config += [
        'placement' => isset( $this->settings['placement'] ) ? $this->settings['placement'] : 'top',
        'offsetSkidding' => isset( $this->settings['offsetSkidding'] ) ? intval( $this->settings['offsetSkidding'] ) : 0,
        'offsetDistance' => isset( $this->settings['offsetDistance'] ) ? intval( $this->settings['offsetDistance'] ) : 10,
        'delay' => isset( $this->settings['delay'] ) ? intval( $this->settings['delay'] ) : 0,
        'followCursor' => isset( $this->settings['followCursor'] ) ? $this->settings['followCursor'] : 'false',
        'copyText' => $copyTooltipText,
        'copiedText' => $copiedTooltipText,
        'tooltipReveal' =>  $tooltipReveal
      ];
    }
    
    if ( isset( $settings['buttonDelay'] ) ) {
      $config += [
        'buttonDelay' => intval( $this->settings['buttonDelay'] ),
      ];
    }

    if ($hideEmpty) {
      $config += [ 'hideEmpty' => 'true' ];
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

    

    $this->set_attribute( '_root', 'data-x-id', $uniqueID );
    $this->set_attribute( '_root', 'data-x-copy-to-clipboard', wp_json_encode($config) );

    if ($maybeToolip) {
      $this->set_attribute( '_root', 'data-x-tooltip', 'true' );
    }

    $this->set_attribute( "x-copy-to-clipboard", 'class', 'x-copy-to-clipboard' );
    if (!!$ariaLabel && !$copyButtonText) {
      $this->set_attribute( "x-copy-to-clipboard", 'aria-label', $ariaLabel );
    }
    $this->set_attribute( "x-copy-tooltip-content", 'id', 'x-copy-tooltip-content_' . $uniqueID );

    $this->set_attribute( 'x-copy-to-clipboard', 'aria-pressed', 'false' );

    $this->set_attribute( 'x_copy-icon', 'class', 'x_copy-icon' );
    $this->set_attribute( 'x_copy-icon', 'class', 'x-copy-to-clipboard_icon' );
    $this->set_attribute( 'x_copy-icon', 'aria-hidden', 'true' );
    $this->set_attribute( 'x_copied-icon', 'class', 'x_copied-icon' );
    $this->set_attribute( 'x_copied-icon', 'class', 'x-copy-to-clipboard_icon' );
    $this->set_attribute( 'x_copied-icon', 'aria-hidden', 'true' );

    $this->set_attribute( 'x-copy-to-clipboard_icons', 'class', 'x-copy-to-clipboard_icons' );

  
    $this->set_attribute( "x-copy-tooltip-content", 'class', [ 'x-copy-tooltip-content' ] );
    $this->set_attribute( "x-copy-tooltip-content", 'role', [ 'tooltip' ] );

    $this->set_attribute( "tippy-content", 'class', [ 'tippy-content' ] );
    $this->set_attribute( "tippy-content", 'data-state', [ 'visible' ] );

    $this->set_attribute( "tippy-root", 'data-tippy-root', '' );

    $this->set_attribute( "tippy-box", 'class', 'tippy-box' );
    $this->set_attribute( "tippy-box", 'data-state', 'visible' );
    $this->set_attribute( "tippy-box", 'tabindex', '1' );
    $this->set_attribute( "tippy-box", 'data-theme', 'extras' );
    $this->set_attribute( "tippy-box", 'data-animation', 'extras' );

    $this->set_attribute( 'x-copy-to-clipboard', 'data-x-animation', $iconAnimation );

    $this->set_attribute( "x-copy-to-clipboard_text", 'class', 'x-copy-to-clipboard_text' );
    if ($copiedButtonText) {
      $this->set_attribute( "x-copy-to-clipboard_text", 'data-x-copied', $copiedButtonText );
    }
    
    
    

    echo "<div {$this->render_attributes( '_root' )}>";

    echo "<button {$this->render_attributes( 'x-copy-to-clipboard' )}>";


    if ($copyButtonText) {
      echo "<span {$this->render_attributes( 'x-copy-to-clipboard_text' )}>" . $copyButtonText . "</span>"; 
    }

    echo \Bricks\Frontend::render_children( $this );

    if ($maybeIcons) {
      echo "<span {$this->render_attributes( 'x-copy-to-clipboard_icons' )}>"; 
      echo "<span {$this->render_attributes( 'x_copied-icon' )}> " . $outputIcon . " </span>";
      echo "<span {$this->render_attributes( 'x_copy-icon' )}> " . $copyIcon . "  </span>";
      echo "</span>";
    }

    echo "</button>";

    if ( BricksExtras\Helpers::maybePreview() ) {
      echo "<div {$this->render_attributes( 'x-copy-tooltip-content' )}>";
      echo "<div {$this->render_attributes( 'tippy-root' )}>";
      echo "<div {$this->render_attributes( 'tippy-box' )}>";
      echo "<div {$this->render_attributes( 'tippy-content' )}>";
      echo "<div {$this->render_attributes( 'x-copy-tooltip-content-inner' )}>" . $copyTooltipText . "</div>";
      echo "</div>";
      echo "</div>";
      echo "</div>";
      echo "</div>";
    }

    

    echo "</div>";





    if ( $maybeToolip && !BricksExtras\Helpers::maybePreview() ) {

      wp_enqueue_script( 'x-popper', BRICKSEXTRAS_URL . 'components/assets/js/popper.js', '', '1.0.0', true );
      wp_enqueue_script( 'x-copy-to-clipboard-popover', BRICKSEXTRAS_URL . 'components/assets/js/' . \BricksExtras\Helpers::maybeMinifyScripts('copytoclipboardpopover') . '.js', ['x-popper'], '1.0.4', true );

      wp_localize_script(
        'x-copy-to-clipboard-popover',
        'xTippy',
        [
          'Instances' => [],
        ]
      );

    }




  }



  

}