<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class X_Header_Search extends \Bricks\Element {

  public $category     = 'extras';
  public $name         = 'xheadersearch';
  public $icon         = 'ti-search';
  public $css_selector = '';
  public $nestable = true;
  //public $scripts      = ['xHeaderSearch'];

  public function get_label() {
    return esc_html__( 'Header Search', 'extras' );
  }

  // Set builder control groups
  public function set_control_groups() {

    $this->control_groups['form'] = [
			'title' => esc_html__( 'Form styles', 'extras' ),
			'tab' => 'content',
		];

    $this->control_groups['icons'] = [
			'title' => esc_html__( 'Open/Close Toggles', 'extras' ),
			'tab' => 'content',
		];

    $this->control_groups['liveSearch'] = [
			'title' => esc_html__( 'Live search', 'extras' ),
			'tab' => 'content',
      'required' => ['layout','=',['header_overlay','below_header']]
		];

  }

  // Set builder controls
  public function set_controls() {


    $this->controls['builderSearchOpen'] = [
      'tab'   => 'content',
      'inline' => true,
      'small' => true,
      //'default' => true,
      'label' => esc_html__( 'Reveal form in builder', 'bricks' ),
      'type'  => 'checkbox',
    ];

    $this->controls['layout'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Search layout', 'bricks' ),
			'type' => 'select',
			'options' => [
			  'header_overlay' => esc_html__( 'Header overlay', 'bricks' ),
			  'below_header' => esc_html__( 'Below header', 'bricks' ),
			  'full_screen' => esc_html__( 'Full screen', 'bricks' ),
			  'expand' => esc_html__( 'Expand', 'bricks' ),
			],
			'inline'      => true,
			'clearable' => false,
			'placeholder' => esc_html__( 'Select', 'bricks' ),
			'default' => 'header_overlay',
		  ];

      $this->controls['expandWidth'] = [
        'tab' => 'content',
        'label' => esc_html__( 'Expand width', 'extras' ),
        'type' => 'number',
        'units' => 'px',
        'inline' => true,
        'css' => [
          [
            'selector' => '.x-search-form',  
            'property' => '--x-headersearch-expand-width',
            ],
        ],
        'placeholder' => esc_html__( '260px', 'bricks' ),
        'required' => ['layout', '=', 'expand'],
      ];

      $this->controls['belowAnimation'] = [
        'tab' => 'content',
        'label' => esc_html__( 'Reveal style', 'bricks' ),
        'type' => 'select',
        'options' => [
          'slide' => esc_html__( 'Slide', 'bricks' ),
          'fade' => esc_html__( 'Fade' ),
        ],
        'inline'      => true,
        //'clearable' => false,
        'placeholder' => esc_html__( 'Slide', 'bricks' ),
        'required' => [
          ['layout', '=', 'below_header'],
          ['maybeLiveSearch', '!=', true]
          ]
        ];


      $this->controls['belowHeaderHeight'] = [
        'tab' => 'content',
        'label' => esc_html__( 'Below header form height', 'extras' ),
        'type' => 'number',
        'units'    => true,
        'css' => [
          [
          'selector' => '[data-type=below_header][aria-expanded=true] + .x-search-form',  
          'property' => '--x-header-slide-height',
          ],
          [
            'selector' => '[data-type=below_header][data-reveal=fade] + .x-search-form',  
            'property' => '--x-header-slide-height',
          ],
          [
            'selector' => '&[data-nest=true] [data-type=below_header] + .x-search-form [data-search-width] > .brxe-filter-search',
            'property' => 'height',
          ]
        ],
        'placeholder' => '80px',
        'required' => [
          ['layout', '=', 'below_header'],
          //['maybeLiveSearch', '!=', true]
          ]
        ];

        $this->controls['transitionDuration'] = [
          'tab' => 'content',
          'label' => esc_html__( 'Transition duration', 'bricks' ),
          'inline'      => true,
          'small'		  => true,
          'type' => 'number',
          'units'    => true,
          'css' => [
            [
              'selector' => '.x-search-form',  
              'property' => '--x-header-transiton',
            ],
          ],
          //'inlineEditing' => true,
          'placeholder' => '300ms',
          ];

        

        $this->controls['searchWidth'] = [
          'tab' => 'content',
          'label' => esc_html__( 'Search max width', 'bricks' ),
          'type' => 'select',
          'options' => [
            'contentWidth' => esc_html__( 'Content width', 'bricks' ),
            'fullWidth' => esc_html__( 'Full width', 'bricks' ),
            'customWidth' => esc_html__( 'Custom width', 'bricks' )
          ],
          'inline'      => true,
          'clearable' => false,
          'placeholder' => esc_html__( 'Content width', 'bricks' ),
          ];



          $this->controls['searchWidthCustom'] = [
            'tab' => 'content',
            'label' => esc_html__( 'Custom search width', 'extras' ),
            'type' => 'number',
            'units' => 'px',
            'inline' => true,
            'css' => [
              [
                'selector' => '.brxe-container[data-search-width=customWidth]',  
                'property' => 'width',
                ],
            ],
            'placeholder' => esc_html__( '1140px', 'bricks' ),
            'required' => ['searchWidth', '=', 'customWidth'],
          ];

           $this->controls['autoComplete'] = [
            'tab' => 'content',
            'label' => esc_html__( 'Auto complete', 'extras' ),
            'type' => 'select',
            'inline' => true,
            'options' => [
              'on' => esc_html__( 'True', 'bricks' ),
              'off' => esc_html__( 'False', 'bricks' ),
            ],
            'placeholder' => esc_html__( 'False', 'bricks' ),
            'required' => ['maybeLiveSearch', '!=', true],
          ];


        /*


        $postTypes = \BricksExtras\Helpers::getPostTypes();
        $postTypesBuiltin = \BricksExtras\Helpers::getPostTypes( true );


        $this->controls['postTypeSearch'] = [
          'tab'           => 'content',
          'type' => 'select',
          'multiple'    => true,
          'inline' => true,
          'options' => array_merge($postTypes, $postTypesBuiltin),
          'label'         => esc_html__( 'Limit search query to specific post types', 'bricks' ),
          'required' => ['maybeLiveSearch', '!=', true],
        ];

        */

        $this->controls['placeholder'] = [
          'tab' => 'content',
          'label' => esc_html__( 'Placeholder text', 'bricks' ),
          'type' => 'text',
          'placeholder' => esc_attr__( 'Search...', 'bricks' ),
          'required' => ['maybeLiveSearch', '!=', true],
        ];



        /* form styles */

        $form = '.x-search-form';
    
        $this->controls['formStart'] = [
          'tab'   => 'content',
          'group'  => 'form',
          'type'  => 'separator',
          'label'  => esc_html__( 'Form', 'extras' ),
        ];
    
        $this->controls['formBackgroundColor'] = [
          'tab'    => 'content',
          'group'  => 'form',
          'type'   => 'color',
          'label'  => esc_html__( 'Background', 'extras' ),
          'css'    => [
            [
              'property' => 'background-color',
              'selector' => $form,
            ],
          ],
        ];
    
        $this->controls['formBorder'] = [
          'tab'    => 'content',
          'group'  => 'form',
          'type'   => 'border',
          'label'  => esc_html__( 'Border', 'extras' ),
          'css'    => [
            [
              'property' => 'border',
              'selector' => $form,
            ],
          ],
        ];
    
        $this->controls['formBoxShadow'] = [
          'tab'    => 'content',
          'group'  => 'form',
          'label'  => esc_html__( 'Box Shadow', 'extras' ),
          'type'   => 'box-shadow',
          'css'    => [
            [
              'property' => 'box-shadow',
              'selector' => $form,
            ],
          ],
        ];

        $this->controls['formPadding'] = [
          'tab'   => 'content',
          'group' => 'form',
          'label' => esc_html__( 'Padding', 'extras' ),
          'type'  => 'dimensions',
          'placeholder' => [
            'top' => '0',
            'right' => '0',
            'bottom' => '0',
            'left' => '0',
          ],
          'css'   => [
            [
              'property' => 'padding',
              'selector' => $form,
            ],
          ],
        ];




        $this->controls['formInput'] = [
          'tab'   => 'content',
          'group'  => 'form',
          'type'  => 'separator',
          'label'  => esc_html__( 'Input', 'extras' ),
        ];

       

        $this->controls['inputTypography'] = [
          'tab'    => 'content',
          'group'  => 'form',
          'type'   => 'typography',
          'label'  => esc_html__( 'Typography', 'extras' ),
          'css'    => [
            [
              'property' => 'font',
              'selector' => $form . ' input[type=search]',
            ],
            [
              'property' => 'font',
              'selector' => $form . ' .brxe-filter-search',
            ],

            [
              'property' => 'font',
              'selector' => $form . ' .x-header-search_toggle-close',
            ],


          ],
        ];

        $this->controls['inputTypographyPlaceholder'] = [
          'tab'    => 'content',
          'group'  => 'form',
          'type'   => 'typography',
          'label'  => esc_html__( 'Placeholder typography', 'extras' ),
          'css'    => [
            [
              'property' => 'font',
              'selector' => $form . ' input[type=search]::placeholder',
            ],
            [
              'property' => 'font',
              'selector' => $form . ' input[type=search]::-webkit-placeholder',
            ],
          ],
        ];

        $this->controls['inputBackgroundColor'] = [
          'tab'    => 'content',
          'group'  => 'form',
          'type'   => 'color',
          'label'  => esc_html__( 'Input Background', 'extras' ),
          'css'    => [
            [
              'property' => 'background-color',
              'selector' => $form . ' input[type=search]',
            ],
          ],
        ];

        $this->controls['autoCompleteInputBackgroundColor'] = [
          'tab'    => 'content',
          'group'  => 'form',
          'type'   => 'color',
          'label'  => esc_html__( 'Input Background (Autocomplete)', 'extras' ),
          'css'    => [
            [
              'property' => '--x-header-search-autocomplete',
              'selector' => $form,
            ],
          ],
        ];

        
    
        $this->controls['inputBorder'] = [
          'tab'    => 'content',
          'group'  => 'form',
          'type'   => 'border',
          'label'  => esc_html__( 'Border', 'extras' ),
          'css'    => [
            [
              'property' => 'border',
              'selector' => $form . ' input[type=search]',
            ],
          ],
        ];
    
        $this->controls['inputBoxShadow'] = [
          'tab'    => 'content',
          'group'  => 'form',
          'label'  => esc_html__( 'Box Shadow', 'extras' ),
          'type'   => 'box-shadow',
          'css'    => [
            [
              'property' => 'box-shadow',
              'selector' => $form . ' input[type=search]',
            ],
          ],
        ];

        $this->controls['inputPadding'] = [
          'tab'   => 'content',
          'group' => 'form',
          'label' => esc_html__( 'Padding', 'extras' ),
          'type'  => 'dimensions',
          'placeholder' => [
            'top' => '0',
            'right' => '0',
            'bottom' => '0',
            'left' => '0',
          ],
          'css'   => [
            [
              'property' => 'padding',
              'selector' => $form . ' input[type=search]',
            ],
          ],
        ];




        /* icons */

        $searchButton = 'button.x-header-search_toggle-open';
        $closeButton = '.x-search-form button.x-header-search_toggle-close';

        $this->controls['searchIconStart'] = [
          'tab'   => 'content',
          'group'  => 'icons',
          'type'  => 'separator',
          'label'  => esc_html__( 'Open search icon', 'extras' ),
        ];

      $this->controls['search_icon'] = [
        'tab'      => 'content',
        'group' => 'icons',
        'label'    => esc_html__( 'Choose icon', 'bricks' ),
        'type'     => 'icon',
        'css'      => [
          [
            'selector' => '.x-header-search_toggle-open svg',
          ],
        ],
        'default'  => [
          'library' => 'themify',
          'icon'    => 'ti-search',
        ],
      ];

      $this->controls['iconSize'] = [
        'tab'      => 'content',
        'group'    => 'icons',
        'label'    => esc_html__( 'Icon size', 'bricks' ),
        'type'     => 'number',
        'units'    => true,
        'css'      => [
          [
            'property' => 'font-size',
            'selector' => $searchButton . ' > *:not(.x-header-search_toggle-open-text)'
          ],
        ],
      ];

      $this->controls['aria_label'] = [
        'tab' => 'content',
        'label' => esc_html__( 'Aria label', 'bricks' ),
        'type' => 'text',
        'group' => 'icons',
        'inline' => true,
        'placeholder' => esc_attr__( 'Open search', 'bricks' ),
        ];

        $this->controls['buttonText'] = [
          'tab' => 'content',
          'label' => esc_html__( 'Button text', 'bricks' ),
          'type' => 'text',
          'group' => 'icons',
          'inline' => true,
          'placeholder' => esc_attr__( '', 'bricks' ),
          ];


          $this->controls['buttonTypography'] = [
            'tab' => 'content',
            'label' => esc_html__( 'Button typography', 'bricks' ),
            'type' => 'typography',
            'group' => 'icons',
            'css'      => [
              [
                'property' => 'font',
                'selector' => '.x-header-search_toggle-open-text'
              ],
            ],
          ];
    

        
    
        $this->controls['iconColor'] = [
          'tab'      => 'content',
          'group' => 'icons',
          'label'    => esc_html__( 'Color', 'bricks' ),
          'type'     => 'color',
          'css'      => [
            [
              'property' => 'color',
              'selector' => $searchButton
            ],
          ],
        ];
    
        $this->controls['iconBackgroundColor'] = [
          'tab'   => 'content',
          'group' => 'icons',
          'label' => esc_html__( 'Background color', 'bricks' ),
          'type'  => 'color',
          'css'   => [
            [
              'property' => 'background-color',
              'selector' => $searchButton
            ],
          ],
        ];
    
        $this->controls['iconBorder'] = [
          'tab'   => 'content',
          'label' => esc_html__( 'Border', 'bricks' ),
          'group' => 'icons',
          'type'  => 'border',
          'css'   => [
            [
              'property' => 'border',
              'selector' => $searchButton,
            ],
          ],
        ];
    
        $this->controls['iconBoxShadow'] = [
          'tab'   => 'content',
          'label' => esc_html__( 'Box shadow', 'bricks' ),
          'group' => 'icons',
          'type'  => 'box-shadow',
          'css'   => [
            [
              'property' => 'box-shadow',
              'selector' => $searchButton
            ],
          ],
        ];
    
    
        $this->controls['button_padding'] = [
          'tab' => 'content',
          'group' => 'icons',
          'label' => esc_html__( 'Padding', 'bricks' ),
          'type' => 'dimensions',
          'css' => [
            [
            'property' => 'padding',
            'selector' => $searchButton
            ]
          ],
          'placeholder' => [
            'top' => '10px',
            'right' => '10px',
            'bottom' => '10px',
            'left' => '10px',
          ],
          ];
    
          $this->controls['button_margin'] = [
          'tab' => 'content',
          'group' => 'icons',
          'label' => esc_html__( 'Margin', 'bricks' ),
          'type' => 'dimensions',
          'css' => [
            [
            'property' => 'margin',
            'selector' => $searchButton
            ]
          ],
        ];


        $this->controls['markerButtonDirection'] = [
          'tab'      => 'content',
          'label'    => esc_html__( 'Direction', 'bricks' ),
          'group'		  => 'icons',
          'tooltip'  => [
            'content'  => 'flex-direction',
            'position' => 'top-left',
          ],
          'type'     => 'direction',
          'css'      => [
            [
              'property' => 'flex-direction',
              'selector' => $searchButton,
            ],
          ],
          'inline'   => true,
          'rerender' => true,
          //'required' => [ '_display', '=', 'flex' ],
        ];
    
        $this->controls['markerButtonjustifyContent'] = [
          'tab'      => 'content',
          'label'    => esc_html__( 'Align main axis', 'bricks' ),
          'group'		  => 'icons',
          'tooltip'  => [
            'content'  => 'justify-content',
            'position' => 'top-left',
          ],
          'type'     => 'justify-content',
          'css'      => [
            [
              'property' => 'justify-content',
              'selector' => $searchButton,
            ],
          ],
          //'required' => [ '_display', '=', 'flex' ],
        ];
    
        $this->controls['markerButtonalignItems'] = [
          'tab'      => 'content',
          'label'    => esc_html__( 'Align cross axis', 'bricks' ),
          'group'		  => 'icons',
          'tooltip'  => [
            'content'  => 'align-items',
            'position' => 'top-left',
          ],
          'type'     => 'align-items',
          'css'      => [
            [
              'property' => 'align-items',
              'selector' => $searchButton,
            ],
          ],
          //'required' => [ '_display', '=', 'flex' ],
        ];
    
        $this->controls['markerButtoncolumnGap'] = [
          'tab'      => 'content',
          'label'    => esc_html__( 'Gap', 'bricks' ),
          'group'		  => 'icons',
          'type'     => 'number',
          'units'    => true,
          'css'      => [
            [
              'property' => 'gap',
              'selector' => $searchButton,
            ],
          ],
        ];



      $this->controls['closeIconStart'] = [
        'tab'   => 'content',
        'group'  => 'icons',
        'type'  => 'separator',
        'label'  => esc_html__( 'Close search icon', 'extras' ),
      ];

      $this->controls['maybe_remove_close'] = [
        'tab' => 'content',
        'label' => esc_html__( 'Remove close icon', 'bricks' ),
        'type'  => 'checkbox',
        'group' => 'icons',
      ];



      $this->controls['close_icon'] = [
        'tab'      => 'content',
        'group' => 'icons',
        'label'    => esc_html__( 'Choose icon', 'bricks' ),
        'type'     => 'icon',
        'css'      => [
          [
            'selector' => '.x-header-search_toggle-close svg',
          ],
        ],
        'default'  => [
          'library' => 'themify',
          'icon'    => 'ti-close',
        ],
        'required' => ['maybe_remove_close', '!=', true]
      ];



      $this->controls['close_aria_label'] = [
        'tab' => 'content',
        'label' => esc_html__( 'Aria label', 'bricks' ),
        'type' => 'text',
        'group' => 'icons',
        'inline' => true,
        'placeholder' => esc_attr__( 'Close search', 'bricks' ),
        'required' => ['maybe_remove_close', '!=', true]
        ];
    

        $this->controls['closeIconSize'] = [
          'tab'      => 'content',
          'group'    => 'icons',
          'label'    => esc_html__( 'Icon size', 'bricks' ),
          'type'     => 'number',
          'units'    => true,
          'css'      => [
            [
              'property' => 'font-size',
              'selector' => $closeButton
            ],
          ],
          'required' => ['maybe_remove_close', '!=', true]
        ];
    
        $this->controls['closeIconColor'] = [
          'tab'      => 'content',
          'group' => 'icons',
          'label'    => esc_html__( 'Color', 'bricks' ),
          'type'     => 'color',
          'css'      => [
            [
              'property' => 'color',
              'selector' => $closeButton
            ],
          ],
          'required' => ['maybe_remove_close', '!=', true]
        ];
    
        $this->controls['closeIconBackgroundColor'] = [
          'tab'   => 'content',
          'group' => 'icons',
          'label' => esc_html__( 'Background color', 'bricks' ),
          'type'  => 'color',
          'css'   => [
            [
              'property' => 'background-color',
              'selector' => $closeButton
            ],
          ],
          'required' => ['maybe_remove_close', '!=', true]
        ];
    
        $this->controls['closeIconBorder'] = [
          'tab'   => 'content',
          'label' => esc_html__( 'Border', 'bricks' ),
          'group' => 'icons',
          'type'  => 'border',
          'css'   => [
            [
              'property' => 'border',
              'selector' => $closeButton,
            ],
          ],
          'required' => ['maybe_remove_close', '!=', true]
        ];
    
        $this->controls['closeIconBoxShadow'] = [
          'tab'   => 'content',
          'label' => esc_html__( 'Box shadow', 'bricks' ),
          'group' => 'icons',
          'type'  => 'box-shadow',
          'css'   => [
            [
              'property' => 'box-shadow',
              'selector' => $closeButton
            ],
          ],
          'required' => ['maybe_remove_close', '!=', true]
        ];
    
    
        $this->controls['closeButtonPadding'] = [
          'tab' => 'content',
          'group' => 'icons',
          'label' => esc_html__( 'Padding', 'bricks' ),
          'type' => 'dimensions',
          'css' => [
            [
            'property' => 'padding',
            'selector' => $closeButton
            ]
          ],
          'placeholder' => [
            'top' => '10px',
            'right' => '10px',
            'bottom' => '10px',
            'left' => '10px',
          ],
          'required' => ['maybe_remove_close', '!=', true]
        ];
    
          $this->controls['closeButtonMargin'] = [
          'tab' => 'content',
          'group' => 'icons',
          'label' => esc_html__( 'Margin', 'bricks' ),
          'type' => 'dimensions',
          'css' => [
            [
            'property' => 'margin',
            'selector' => $closeButton
            ]
          ],
          'required' => ['maybe_remove_close', '!=', true]
        ];



        $this->controls['maybeLiveSearch'] = [
          'tab'   => 'content',
          'inline' => true,
          'small' => true,
          'group' => 'liveSearch',
          'label' => esc_html__( 'Nest live search', 'bricks' ),
          'info' => esc_html__( "Allows Filter - Search element to be nested", 'bricks' ),
          'type'  => 'checkbox',
        ];

        $this->controls['maybeEnterKeyRedirect'] = [
          'tab'   => 'content',
          'inline' => true,
          'small' => true,
          'group' => 'liveSearch',
          'label' => esc_html__( 'Redirect to search results page on Enter', 'bricks' ),
          'type'  => 'checkbox',
          'required' => ['maybeLiveSearch', '=', true]
        ];



        
    
  }

  // Methods: Frontend-specific
	public function enqueue_scripts() {
		wp_enqueue_script( 'x-header-search', BRICKSEXTRAS_URL . 'components/assets/js/' . \BricksExtras\Helpers::maybeMinifyScripts('headersearch') . '.js', '', '1.0.4', true );

    wp_localize_script(
			'x-header-search',
			'xSearch',
			[
				'searchURL' => esc_url( get_site_url() ) . '/',
			]
		);

    if (! \BricksExtras\Helpers::elementCSSAdded($this->name) ) {
			wp_enqueue_style( 'x-header-search', BRICKSEXTRAS_URL . 'components/assets/css/headersearch.css', [], '' );
		  }
	}


  /** 
   * Render element HTML on frontend
   */
  public function render() {

  
    $layout = isset( $this->settings['layout'] ) ? esc_attr( $this->settings['layout'] ) : '';
    $reveal = isset( $this->settings['belowAnimation'] ) ? esc_attr( $this->settings['belowAnimation'] ) : 'slide';

    $autoComplete = isset( $this->settings['autoComplete'] ) ? esc_attr( $this->settings['autoComplete'] ) : 'off';

    $postTypeSearch = ! empty( $this->settings['postTypeSearch'] ) ? $this->settings['postTypeSearch'] : false;

    $this->set_attribute( 'x-header-search_toggle-open', 'class', 'x-header-search_toggle-open' );
    $this->set_attribute( 'x-header-search_toggle-open', 'data-type', $layout );
    $this->set_attribute( 'x-header-search_toggle-open', 'data-reveal', $reveal );

    if ( !isset( $this->settings['buttonText'] ) ) {
      $this->set_attribute( 'x-header-search_toggle-open', 'aria-label', isset( $this->settings['aria_label'] ) ? esc_attr__( $this->settings['aria_label'] ) : esc_attr__( 'Open Search' ) );
    }
    
    $this->set_attribute( 'x-header-search_toggle-open', 'aria-controls', 'x-header-search_form-' . $this->id );
    $this->set_attribute( 'x-header-search_toggle-open', 'aria-expanded', 'false' );
    $this->set_attribute( 'x-header-search_toggle-open', 'aria-haspopup', 'dialog' );

    $this->set_attribute( 'x-header-search_toggle-close', 'class', 'x-header-search_toggle-close' );

    $this->set_attribute( 'x-header-search_toggle-close', 'aria-label', isset( $this->settings['close_aria_label'] ) ? esc_attr__( $this->settings['close_aria_label'] ) : esc_attr__( 'Close Search' ) );
    $this->set_attribute( 'x-header-search_toggle-close', 'aria-controls', 'x-header-search_form-' . $this->id ); 
    $this->set_attribute( 'x-header-search_toggle-close', 'aria-expanded', 'false' );

    $this->set_attribute( 'x-header-search_toggle-text', 'class', 'x-header-search_toggle-text' );

    if ( isset( $this->settings['maybeLiveSearch'] ) ) {
      $this->set_attribute( '_root', 'data-nest', 'true' );

      if ( isset( $this->settings['maybeEnterKeyRedirect'] ) ) {
        $this->set_attribute( '_root', 'data-search-redirect', 'true' );
      }
    }


    $this->set_attribute( '_root', 'data-type', $layout );

    echo "<div {$this->render_attributes( '_root' )}>";

    $search_icon = empty( $this->settings['search_icon'] ) ? false : self::render_icon( $this->settings['search_icon'] );
    $close_icon = empty( $this->settings['close_icon'] ) ? false : self::render_icon( $this->settings['close_icon'] );
    $placeholder = isset( $this->settings['placeholder'] ) ? esc_attr__( $this->settings['placeholder'] ) : esc_attr__( 'Search...' );

    $searchWidth = isset( $this->settings['searchWidth'] ) ? esc_attr( $this->settings['searchWidth'] ) : 'contentWidth';

    $buttonText = isset( $this->settings['buttonText'] ) ? '<span class="x-header-search_toggle-open-text"> ' . esc_attr__( $this->settings['buttonText'] ) . '</span>' : '';

    $config = [];
   

    if ( $search_icon ) {

      echo "<button {$this->render_attributes( 'x-header-search_toggle-open' )}>";
      echo $buttonText;
      echo $search_icon;
			echo '</button>';

		}

    $postTypeInputs = '';

    /*

    if ( !!$postTypeSearch && is_array( $postTypeSearch ) ) {

      $config['postTypes'] = $postTypeSearch;

      $name = count($postTypeSearch) > 1 ? 'post_type[]' : 'post_type';

      foreach ( $postTypeSearch as $postType ) {

        $postTypeInputs .= '<input type="hidden" name="' . $name . '" value="' . esc_attr( $postType ) . '" />';
      }

    }
      
    */

    $html = '<form role="search" autocomplete="' . $autoComplete . '" method="get" class="x-search-form" id="x-header-search_form-' . $this->id . '" action="' . esc_url( home_url( '/' ) ) . '">
              <div data-search-width="' . $searchWidth . '" class="brxe-container">
                <label>
                  <span class="screen-reader-text">' . $placeholder . '</span>
                  <input type="search" placeholder="' . $placeholder . '" value="' . get_search_query() . '" name="s"> 
                  </label>
                  ' . $postTypeInputs . '
                  <input type="submit" class="search-submit" value="Search">';

                if ( $close_icon && !isset($this->settings['maybe_remove_close'] ) ) {

                  $html .=  "<button {$this->render_attributes( 'x-header-search_toggle-close' )}>";
                  $html .=  $close_icon;
                  $html .=  '</button>';
            
                }

    $html .=  '</div>
            </form>';

    if ( ( 'below_header' === $layout || 'header_overlay' === $layout ) && isset( $this->settings['maybeLiveSearch'] ) ) {

      $this->set_attribute( 'x-search-form', 'class', 'x-search-form' );
      $this->set_attribute( 'x-search-form', 'id', 'x-header-search_form-' . $this->id );
      $this->set_attribute( 'x-search-form', 'role', 'search' );
      $this->set_attribute( 'x-search-form', 'data-x-search', wp_json_encode($config) );

      $this->set_attribute( 'x-search-form_container', 'class', 'brxe-container' );
      $this->set_attribute( 'x-search-form_container', 'data-search-width', $searchWidth );

      $output = "<div {$this->render_attributes( 'x-search-form' )}>";
      $output .= "<div {$this->render_attributes( 'x-search-form_container' )}>";

      if ( method_exists('\Bricks\Frontend','render_children') ) {
        $output .= \Bricks\Frontend::render_children( $this );
      }  
      

      if ( $close_icon && !isset($this->settings['maybe_remove_close'] ) ) {

        $output .=  "<button {$this->render_attributes( 'x-header-search_toggle-close' )}>";
        $output .=  $close_icon;
        $output .=  '</button>';

      }

      $output .= '</div></div>';  

    } else {
      $output = apply_filters( 'get_search_form', $html );
    }

    echo $output;

    echo "</div>";

  }
    
  

  /**
   * Render element HTML in builder (optional)
   */
  public static function render_builder() { ?>

		<script type="text/x-template" id="tmpl-bricks-element-xheadersearch">
			<component
				class="brxe-xheadersearch"
        :data-type="settings.layout"
        :data-nest="settings.maybeLiveSearch"
			>
				
      <button 
        aria-label="Open search" 
        class="x-header-search_toggle-open"
        
        :data-type="settings.layout"
        :data-reveal="null != settings.belowAnimation ? settings.belowAnimation : 'slide'"
        :aria-expanded="settings.builderSearchOpen ? 'true' : 'false'"
      >

      <contenteditable
        v-if="settings.buttonText"
        tag="span"
        class="x-header-search_toggle-open-text"
        :name="name"
        controlKey="buttonText"
        toolbar="style"
        :settings="settings"
      />


      <icon-svg 
        :iconSettings="settings.search_icon"
      />
      </button>

      <form v-if="!settings.maybeLiveSearch" role="search" method="get" class="x-search-form" action="/">
               <div 
               :data-search-width="settings.searchWidth ? settings.searchWidth : 'contentWidth'" 
                class="brxe-container"
                >
                <label>
                  <span class="screen-reader-text">Search ...</span>
                  <input 
                    type="search" 
                    :placeholder="null != settings.placeholder ? settings.placeholder : 'Search...'"
                    value="" 
                    name="s"> 
                  </label>
                  

                  <button 
                      v-if="!settings.maybe_remove_close"
                      aria-label="close search" 
                      class="x-header-search_toggle-close"
                      :data-type="settings.layout"
                      :aria-expanded="settings.builderSearchOpen ? 'true' : 'false'"
                    >

                    <icon-svg 
                      :iconSettings="settings.close_icon"
                    />
                    </button>
              
                 <input type="submit" class="search-submit" value="Search">
                 </div>
      </form>

      <div v-else class="x-search-form">  
      <div 
        :data-search-width="settings.searchWidth ? settings.searchWidth : 'contentWidth'" 
        class="brxe-container"
        >

        <bricks-element-children
          :element="element"
        />

        <button 
            v-if="!settings.maybe_remove_close"
            aria-label="close search" 
            class="x-header-search_toggle-close"
            :data-type="settings.layout"
            :aria-expanded="settings.builderSearchOpen ? 'true' : 'false'"
          >

          <icon-svg 
            :iconSettings="settings.close_icon"
          />
          </button>

          </div>

      </div>

     
			</component>	
		</script>

	<?php }


}