<?php

namespace BricksExtras;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( class_exists( 'WPGridBuilderStyler' ) ) {
	return;
}

class WPGridBuilderStyler {

		public static function init() {

				add_filter( "bricks/elements/wpgb-facet/control_groups", array( __CLASS__, 'wpgb_facet_group' ) );
                add_filter( "bricks/elements/wpgb-facet/controls", array( __CLASS__, 'wpgb_facet_controls' ) );

                add_filter( 'bricks/theme_styles/control_groups', array( __CLASS__, 'wpgb_theme_facet_group' ) );
		        add_filter( 'bricks/theme_styles/controls', array( __CLASS__, 'wpgb_theme_facet_controls' ) );
                
		}

		public static function wpgb_theme_facet_group( $control_groups ) {

            $control_groups['x_wpgb_facet_theme'] = [
                'title'    => esc_html__( 'WPGB Facets', 'bricksextras' ),
            ];

            return $control_groups;

        }

        public static function wpgb_theme_facet_controls( $controls ) {

            $facetControls = [];

            $accentColorSelectors = [
                'body .flatpickr-day.endRange', 
                'body .flatpickr-day.endRange.inRange', 
                'body .flatpickr-day.endRange.nextMonthDay', 
                'body .flatpickr-day.endRange.prevMonthDay', 
                'body .flatpickr-day.endRange:focus', 
                'body .flatpickr-day.endRange:hover', 
                'body .flatpickr-day.selected', 
                'body .flatpickr-day.selected.inRange', 
                'body .flatpickr-day.selected.nextMonthDay', 
                'body .flatpickr-day.selected.prevMonthDay', 
                'body .flatpickr-day.selected:focus', 
                'body .flatpickr-day.selected:hover', 
                'body .flatpickr-day.startRange', 
                'body .flatpickr-day.startRange.inRange', 
                'body .flatpickr-day.startRange.nextMonthDay', 
                'body .flatpickr-day.startRange.prevMonthDay', 
                'body .flatpickr-day.startRange:focus', 
                'body .flatpickr-day.startRange:hover',
            ];

            $accentColorRules = array_merge( \BricksExtras\Helpers::doCSSrules('background-color', $accentColorSelectors, true), \BricksExtras\Helpers::doCSSrules('border-color', $accentColorSelectors, true) );

            $selectDropDownRule = [
                [
                    'property' => 'background-color',
                    'selector' => '.wpgb-select-dropdown ul li[aria-selected="true"]', 
                 ],
            ];



            $facetControls['x_wpgb_facet_theme'] = [


                'x_wpgb_facet_accent_color' => [
					'group'	=> 'x_wpgb_facet_theme',
                    'label' => esc_html__( 'Accent color', 'bricksextras' ),
					'type'  => 'color',
                    'css'    => array_merge( $accentColorRules, $selectDropDownRule )
                ],




                'x_wpgb_facet_select_dropdown_sep' => [
                    'type'  => 'separator',
                    'group' => 'x_wpgb_facet_theme',
                    'label' => esc_html__( 'Select dropdown'),
                ],
                
                'x_wpgb_facet_select_bg' => [
                    'type'  => 'color',
                    'group' => 'x_wpgb_facet_theme',
                    'label' => esc_html__( 'List background'),
                    'css'    => [
                        [
                            'property' => 'background-color',
                            'selector' => 'body .wpgb-select-dropdown ul li'
                        ],
                    ]
                ],

                'x_wpgb_facet_select_font' => [
                    'type'  => 'typography',
                    'group' => 'x_wpgb_facet_theme',
                    'label' => esc_html__( 'List typography'),
                    'css'    => [
                        [
                            'property' => 'font',
                            'selector' => 'body .wpgb-select-dropdown ul li'
                        ],
                    ]
                ],

                'x_wpgb_facet_select_focused_bg' => [
                    'type'  => 'color',
                    'group' => 'x_wpgb_facet_theme',
                    'label' => esc_html__( 'List background (focused)'),
                    'css'    => [
                        [
                            'property' => 'background-color',
                            'selector' => 'body .wpgb-select-dropdown ul li.wpgb-focused'
                        ],
                    ]
                ],

                'x_wpgb_facet_select_font_focused' => [
                    'type'  => 'typography',
                    'group' => 'x_wpgb_facet_theme',
                    'label' => esc_html__( 'List typography (focused)'),
                    'css'    => [
                        [
                            'property' => 'font',
                            'selector' => 'body .wpgb-select-dropdown ul li.wpgb-focused'
                        ],
                    ]
                ],

                'x_wpgb_facet_select_bg_selected' => [
                    'type'  => 'color',
                    'group' => 'x_wpgb_facet_theme',
                    'label' => esc_html__( 'List background (selected)'),
                    'css'    => [
                        [
                            'property' => 'background-color',
                            'selector' => 'body .wpgb-select-dropdown ul li[aria-selected="true"]'
                        ],
                    ]
                ],

                'x_wpgb_facet_select_font_selected' => [
                    'type'  => 'typography',
                    'group' => 'x_wpgb_facet_theme',
                    'label' => esc_html__( 'List typography (selected)'),
                    'css'    => [
                        [
                            'property' => 'font',
                            'selector' => 'body .wpgb-select-dropdown ul li[aria-selected="true"]'
                        ],
                    ]
                ],

                'x_wpgb_facet_select_padding' => [
                    'type'  => 'dimensions',
                    'group' => 'x_wpgb_facet_theme',
                    'label' => esc_html__( 'Select padding'),
                    'css'    => [
                        [
                            'property' => 'padding',
                            'selector' => 'body .wpgb-select-dropdown ul li'
                        ],
                    ]
                ],


                'x_wpgb_facet_calendar_sep' => [
                    'type'  => 'separator',
                    'group' => 'x_wpgb_facet_theme',
                    'label' => esc_html__( 'Date calendar'),
                ],


                'x_wpgb_facet_calendar_bg' => [
                    'type'  => 'color',
                    'group' => 'x_wpgb_facet_theme',
                    'label' => esc_html__( 'Background color'),
                    'css'    => [
                        [
                            'property' => 'border-bottom-color',
                            'selector' => 'body .flatpickr-calendar.arrowTop:after'
                        ], 
                        [
                            'property' => 'border-top-color',
                            'selector' => 'body .flatpickr-calendar.arrowBottom:after'
                        ],
                        [
                            'property' => 'border-bottom-color',
                            'selector' => 'body .flatpickr-calendar.arrowTop:before'
                        ],
                        [
                            'property' => 'border-top-color',
                            'selector' => 'body .flatpickr-calendar.arrowBottom:before'
                        ],
                        [
                            'property' => 'background-color',
                            'selector' => 'body .flatpickr-calendar'
                        ],
                    ]
                ],

                'x_wpgb_facet_calendar_color' => [
                    'type'  => 'color',
                    'group' => 'x_wpgb_facet_theme',
                    'label' => esc_html__( 'Text color'),
                    'css'    => [
                        [
                            'property' => 'color',
                            'selector' => 'body .flatpickr-calendar'
                        ],
                    ]
                ],

                
                
            ];

            

            return array_merge( $controls, $facetControls );

        }

		public static function wpgb_facet_group( $control_groups ) {
				
                $control_groups['x_facet_title'] = [
						'tab'      => 'content', 
						'title'    => esc_html__( 'Titles', 'bricksextras' ),
				];

                $control_groups['x_facet_inputs'] = [
                    'tab'      => 'content',
                    'title'    => esc_html__( 'Inputs', 'bricksextras' ),
                ];

                $control_groups['x_facet_labels'] = [
                    'tab'      => 'content',
                    'title'    => esc_html__( 'Labels', 'bricksextras' ),
                ];


                $control_groups['x_facet_checkbox'] = [
                    'tab'      => 'content',
                    'title'    => esc_html__( 'Checkbox / Radio', 'bricksextras' ),
                ];

                $control_groups['x_facet_buttons'] = [
                    'tab'      => 'content',
                    'title'    => esc_html__( 'Buttons', 'bricksextras' ),
                ];

                $control_groups['x_facet_selections'] = [
                    'tab'      => 'content',
                    'title'    => esc_html__( 'Selections', 'bricksextras' ),
                ];

                $control_groups['x_facet_dropdown'] = [
                    'tab'      => 'content',
                    'title'    => esc_html__( 'Dropdown', 'bricksextras' ),
                ];

                $control_groups['x_facet_loadmore'] = [
                    'tab'      => 'content',
                    'title'    => esc_html__( 'Load More', 'bricksextras' ),
                ];

                $control_groups['x_facet_slider'] = [
                    'tab'      => 'content',
                    'title'    => esc_html__( 'Range Slider', 'bricksextras' ),
                ];

                $control_groups['x_facet_pagination'] = [
                    'tab'      => 'content',
                    'title'    => esc_html__( 'Pagination', 'bricksextras' ),
                ];

                $control_groups['x_facet_reset'] = [
                    'tab'      => 'content',
                    'title'    => esc_html__( 'Reset', 'bricksextras' ),
                ];

                $control_groups['x_facet_search'] = [
                    'tab'      => 'content',
                    'title'    => esc_html__( 'Search', 'bricksextras' ),
                ];

                $control_groups['x_facet_number'] = [
                    'tab'      => 'content',
                    'title'    => esc_html__( 'Number', 'bricksextras' ),
                ];

                $control_groups['x_facet_date'] = [
                    'tab'      => 'content',
                    'title'    => esc_html__( 'Date', 'bricksextras' ),
                ];

                $control_groups['x_facet_star_rating'] = [
                    'tab'      => 'content',
                    'title'    => esc_html__( 'Star Rating', 'bricksextras' ),
                ];

                $control_groups['x_facet_az_index'] = [
                    'tab'      => 'content',
                    'title'    => esc_html__( 'A-Z index', 'bricksextras' ),
                ];


				return $control_groups;
		}

		
		public static function wpgb_facet_controls( $controls ) {

                $controls['color_sep'] = [
                    'tab'   => 'content',
                    'type'  => 'separator',
                    'label' => esc_html__( 'General colors', 'bricksextras' ),
                ];

				$controls['x_accent_color'] = [
					'tab'   => 'content',
					//'group'	=> 'x_wpgb_facet',
                    'label' => esc_html__( 'Accent color', 'bricksextras' ),
					'type'  => 'color',
                    'css'   => [
                        [
                            'property' => 'background-color',
                            'selector' => '.wpgb-facet .wpgb-button[aria-pressed="true"]',
                        ],
                        [
                            'property' => 'background-color',
                            'selector' => '.wpgb-facet .wpgb-range-facet .wpgb-range-slider .wpgb-range-progress',
                        ],
                        [
                            'property' => 'background-color',
                            'selector' => '.wpgb-facet .wpgb-checkbox-facet .wpgb-checkbox[aria-pressed="true"] .wpgb-checkbox-control',
                        ],
                        [
                            'property' => 'background-color',
                            'selector' => '.wpgb-facet .wpgb-radio-facet .wpgb-radio-control:after',
                        ],
                        [
                            'property' => 'background-color',
                            'selector' => '.wpgb-facet .wpgb-load-more,.wpgb-facet .wpgb-apply,.wpgb-facet .wpgb-number-submit',
                        ],
                        [
                            'property' => 'background-color',
                            'selector' => '.wpgb-facet .wpgb-reset',
                        ],
                        [
                            'property' => 'border-color',
                            'selector' => '.wpgb-facet .wpgb-button[aria-pressed="true"]',
                        ],
                        [
                            'property' => 'border-color',
                            'selector' => '.wpgb-facet .wpgb-color-facet .wpgb-color[aria-pressed="true"] .wpgb-color-control',
                        ],
                        [
                            'property' => 'border-color',
                            'selector' => '.wpgb-facet .wpgb-radio-facet .wpgb-radio[aria-pressed="true"] .wpgb-radio-control',
                        ],
                        [
                            'property' => 'border-color',
                            'selector' => '.wpgb-facet .wpgb-range-facet .wpgb-range-slider .wpgb-range-thumb',
                        ],
                        [
                            'property' => 'border-color',
                            'selector' => '.wpgb-facet .wpgb-color-facet .wpgb-color[aria-pressed="true"] .wpgb-color-control,.wpgb-facet .wpgb-radio-facet .wpgb-radio[aria-pressed="true"] .wpgb-radio-control',
                        ],
                        [
                            'property' => 'border-color',
                            'selector' => '.wpgb-facet .wpgb-checkbox-facet .wpgb-checkbox[aria-pressed="true"] .wpgb-checkbox-control',
                        ],
                        [
                            'property' => 'color',
                            'selector' => '.wpgb-facet .wpgb-pagination li a[aria-current]',
                        ],
                    ],
                    
			    ];

                $controls['x_facet_general_border-color'] = [
                    'tab'    => 'content',
                    'type'   => 'color',
                    'label'  => esc_html__( 'Border colors', 'bricks' ),
                    'css'    => [
                        [
                            'property' => 'border-color',
                            'selector' => '.wpgb-facet select.wpgb-select'
                        ],
                        [
                            'property' => 'color',
                            'selector' => '.wpgb-facet .wpgb-select-controls'
                        ],
                        [
                            'property' => 'border-color',
                            'selector' => '.wpgb-facet .wpgb-search-facet input[type="search"]'
                        ],
                        [
                            'property' => 'border-color',
                            'selector' => '.wpgb-facet .wpgb-autocomplete-facet input[type="search"]'
                        ],
                        [
                            'property' => 'border-color',
                            'selector' => '.wpgb-facet .wpgb-date-facet label input[type="text"]'
                        ],
                        [
                            'property' => 'border-color',
                            'selector' => '.wpgb-facet ul .wpgb-button'
                        ],
                        [
                            'property' => 'border-color',
                            'selector' => '.wpgb-facet .wpgb-select'
                        ],
                        [
                            'property' => 'border-color',
                            'selector' => '.wpgb-facet .wpgb-input'
                        ],
                        [
                            'property' => 'border-color',
                            'selector' => '.wpgb-facet ul .wpgb-button'
                        ],
                        [
                            'property' => 'border-color',
                            'selector' => '.wpgb-facet .wpgb-button:not(button)'
                        ],
                        [
                            'property' => 'border-color',
                            'selector' => '.wpgb-facet .wpgb-radio .wpgb-radio-control'
                        ],
                        [
                            'property' => 'border-color',
                            'selector' => '.wpgb-facet .wpgb-checkbox .wpgb-checkbox-control'
                        ],
                        [
                            'property' => 'border-color',
                            'selector' => '.wpgb-facet .wpgb-checkbox .wpgb-checkbox-control:before'
                        ],
                    ],
                ];

                $controls['x_facet_general_borderhover-color'] = [
                    'tab'    => 'content',
                    'type'   => 'color',
                    'label'  => esc_html__( 'Border colors (hover)', 'bricks' ),
                    'css'    => [
                        [
                            'property' => 'border-color',
                            'selector' => '.wpgb-facet .wpgb-input:hover'
                        ],
                        [
                            'property' => 'color',
                            'selector' => 'wpgb-facet .wpgb-select:hover + .wpgb-select-controls'
                        ],
                        [
                            'property' => 'border-color',
                            'selector' => '.wpgb-facet .wpgb-select:hover'
                        ],
                        [
                            'property' => 'border-color',
                            'selector' => '.wpgb-facet .wpgb-button:hover:not(button):not([aria-pressed="true"]):not([tabindex="-1"])'
                        ],
                        [
                            'property' => 'border-color',
                            'selector' => '.wpgb-facet .wpgb-radio:hover:not([tabindex="-1"]) .wpgb-radio-control'
                        ],
                        [
                            'property' => 'border-color',
                            'selector' => '.wpgb-facet .wpgb-radio:hover:not([tabindex="-1"]) .wpgb-radio-control'
                        ],
                        [
                            'property' => 'border-color',
                            'selector' => '.wpgb-facet .wpgb-checkbox:hover:not([tabindex="-1"]) .wpgb-checkbox-control'
                        ],
                    ],
                ];

                $focusOutlineSelectors = [
                    '.wpgb-facet .wpgb-button:focus:not(button)', 
                    '.wpgb-facet .wpgb-rating:focus', 
                    '.wpgb-facet .wpgb-hierarchy:focus',
                    '.wpgb-facet .wpgb-toggle-hidden:focus', 
                    '.wpgb-facet .wpgb-range-clear:focus', 
                    '.wpgb-facet .wpgb-range-thumb:focus', 
                    '.wpgb-facet .wpgb-pagination li a:focus', 
                    '.wpgb-facet .wpgb-radio:focus .wpgb-radio-control', 
                    '.wpgb-facet .wpgb-checkbox:focus .wpgb-checkbox-control', 
                    '.wpgb-facet .wpgb-color:focus .wpgb-color-control', 
                    '.wpgb-facet .wpgb-az-index:focus .wpgb-az-index-label'
                ];

                $controls['x_facet_general_outline-color'] = [
                    'tab'    => 'content',
                    'type'   => 'color',
                    'label'  => esc_html__( 'Focus outline color', 'bricks' ),
                    'css'    =>  \BricksExtras\Helpers::doCSSrules('outline-color', $focusOutlineSelectors, true)
                ];


                



                /* titles */

                $titleSelector = '.wpgb-facet .wpgb-facet-title';

                $controls['x_facet_title_typography'] = [
                    'tab'    => 'content',
                    'group' => 'x_facet_title',
                    'type'   => 'typography',
                    'label'  => esc_html__( 'Typography', 'bricks' ),
                    'css'    => [
                        [
                            'property' => 'font',
                            'selector' => $titleSelector,
                        ],
                    ],
                ];

                $controls['x_facet_title_margin'] = [
                    'group' => 'title',
                    'label' => esc_html__( 'Margin', 'bricks' ),
                    'group' => 'x_facet_title',
                    'type'  => 'dimensions',
                    'css'   => [
                        [
                            'property' => 'margin',
                            'selector' => $titleSelector,
                        ],
                    ],
                ];


                /* inputs */

                $controls['inputSep'] = [
                    'tab'   => 'content',
                    'type'  => 'separator',
                    'label' => esc_html__( 'General input styles', 'bricks' ),
                    'group' => 'x_facet_inputs',
                ];

                $controls['x_facet_input_typography'] = [
                    'tab'    => 'content',
                    'group' => 'x_facet_inputs',
                    'type'   => 'typography',
                    'label'  => esc_html__( 'Typography', 'bricks' ),
                    'css'    => [
                        [
                            'property' => 'font',
                            'selector' => '.wpgb-facet select.wpgb-select'
                        ],
                        [
                            'property' => 'font',
                            'selector' => '.wpgb-facet .wpgb-search-facet input[type="search"]'
                        ],
                        [
                            'property' => 'font',
                            'selector' => '.wpgb-facet .wpgb-autocomplete-facet input[type="search"]'
                        ],
                        [
                            'property' => 'font',
                            'selector' => '.wpgb-facet .wpgb-date-facet label input[type="text"]'
                        ],
                        [
                            'property' => 'font',
                            'selector' => '.wpgb-facet select.wpgb-select'
                        ],
                        [
                            'property' => 'font',
                            'selector' => '.wpgb-facet select.wpgb-select+.wpgb-select-controls'
                        ],
                        [
                            'property' => 'font',
                            'selector' => '.wpgb-facet .wpgb-number-facet .wpgb-number'
                        ],

                        
                    ],
                ];

                $controls['x_facet_input_typography_placeholder'] = [
                    'tab'    => 'content',
                    'group' => 'x_facet_inputs',
                    'type'   => 'typography',
                    'label'  => esc_html__( 'Typography (placeholder)', 'bricks' ),
                    'css'    => [
                        [
                            'property' => 'font',
                            'selector' => '.wpgb-facet select.wpgb-select::-webkit-input-placeholder'
                        ],
                        [
                            'property' => 'font',
                            'selector' => '.wpgb-facet .wpgb-search-facet input[type="search"]::-webkit-input-placeholder'
                        ],
                        [
                            'property' => 'font',
                            'selector' => '.wpgb-facet .wpgb-autocomplete-facet input[type="search"]::-webkit-input-placeholder'
                        ],
                        [
                            'property' => 'font',
                            'selector' => '.wpgb-facet .wpgb-date-facet label input[type="text"]::-webkit-input-placeholder'
                        ],
                        [
                            'property' => 'font',
                            'selector' => '.wpgb-facet .wpgb-number-facet .wpgb-number::-webkit-input-placeholder'
                        ],
                        [
                            'property' => 'font',
                            'selector' => '.wpgb-facet select.wpgb-select::placeholder'
                        ],
                        [
                            'property' => 'font',
                            'selector' => '.wpgb-facet .wpgb-search-facet input[type="search"]::placeholder'
                        ],
                        [
                            'property' => 'font',
                            'selector' => '.wpgb-facet .wpgb-autocomplete-facet input[type="search"]::placeholder'
                        ],
                        [
                            'property' => 'font',
                            'selector' => '.wpgb-facet .wpgb-date-facet label input[type="text"]::placeholder'
                        ],
                        [
                            'property' => 'font',
                            'selector' => '.wpgb-facet .wpgb-number-facet .wpgb-number::placeholder'
                        ],
                        [
                            'property' => 'font',
                            'selector' => '.wpgb-select-placeholder input[type="text"]::placeholder'
                        ],
                        
                    ],
                ];

                $controls['x_facet_input_bg'] = [
                    'tab'    => 'content',
                    'group' => 'x_facet_inputs',
                    'type'   => 'color',
                    'label'  => esc_html__( 'Background Color', 'bricks' ),
                    'css'    => [
                        [
                            'property' => 'background-color',
                            'selector' => '.wpgb-facet select.wpgb-select'
                        ],
                        [
                            'property' => 'background-color',
                            'selector' => '.wpgb-facet .wpgb-search-facet input[type="search"]'
                        ],
                        [
                            'property' => 'background-color',
                            'selector' => '.wpgb-facet .wpgb-autocomplete-facet input[type="search"]'
                        ],
                        [
                            'property' => 'background-color',
                            'selector' => '.wpgb-facet .wpgb-date-facet label input[type="text"]'
                        ],
                        [
                            'property' => 'background-color',
                            'selector' => '.wpgb-facet .wpgb-number-facet .wpgb-number'
                        ],
                        
                    ],
                ];

                $controls['x_facet_input_border'] = [
                    'tab'    => 'content',
                    'group' => 'x_facet_inputs',
                    'type'   => 'border',
                    'label'  => esc_html__( 'Border', 'bricks' ),
                    'css'    => [
                        [
                            'property' => 'border',
                            'selector' => '.wpgb-facet select.wpgb-select'
                        ],
                        [
                            'property' => 'border',
                            'selector' => '.wpgb-facet .wpgb-search-facet input[type="search"]'
                        ],
                        [
                            'property' => 'border',
                            'selector' => '.wpgb-facet .wpgb-autocomplete-facet input[type="search"]'
                        ],
                        [
                            'property' => 'border',
                            'selector' => '.wpgb-facet .wpgb-date-facet label input[type="text"]'
                        ],
                        [
                            'property' => 'border',
                            'selector' => '.wpgb-facet .wpgb-number-facet .wpgb-number'
                        ],
                        [
                            'property' => 'border',
                            'selector' => '.wpgb-facet .wpgb-select'
                        ],
                        
                    ],
                ];

                $controls['x_facet_general_icon-color'] = [
                    'tab'    => 'content',
                    'type'   => 'color',
                    'group' => 'x_facet_inputs',
                    'label'  => esc_html__( 'Icon color', 'bricks' ),
                    'css'    => [
                        [
                            'property' => 'color',
                            'selector' => '.wpgb-facet .wpgb-input-icon'
                        ],
                    ],

                ];

                $controls['x_facet_general_icon-size'] = [
                    'tab'    => 'content',
                    'type'   => 'number',
                    'units' => true,
                    'group' => 'x_facet_inputs',
                    'label'  => esc_html__( 'Icon size', 'bricks' ),
                    'css'    => [
                        [
                            'property' => 'width',
                            'selector' => '.wpgb-facet .wpgb-input-icon'
                        ],
                        [
                            'property' => 'height',
                            'selector' => '.wpgb-facet .wpgb-input-icon'
                        ],
                        [
                            'property' => 'top',
                            'selector' => '.wpgb-facet .wpgb-input-icon',
                            'value' => 'calc( 50% - %s/2 )'
                        ],
                        
                    ],
                    'placeholder' => '16px'
                ];

                $controls['x_facet_general_icon-margin'] = [
                    'tab'    => 'content',
                    'group' => 'x_facet_inputs',
                    'label'  => esc_html__( 'Icon margin', 'bricks' ),
                    'type'  => 'dimensions',
                    'css'   => [
                        [
                            'property' => 'margin',
                            'selector' => '.wpgb-facet .wpgb-input-icon'
                        ],
                    ],
                ];

                

                $controls['x_facet_input_height'] = [
                    'group' => 'title',
                    'label' => esc_html__( 'Height', 'bricks' ),
                    'group' => 'x_facet_inputs',
                    'type'  => 'number',
                    'units' => true,
                    'css'   => [
                        [
                            'property' => 'height',
                            'selector' => '.wpgb-facet select.wpgb-select'
                        ],
                        [
                            'property' => 'min-height',
                            'selector' => '.wpgb-facet select.wpgb-select'
                        ],
                        [
                            'property' => 'min-height',
                            'selector' => '.wpgb-facet .wpgb-select'
                        ],
                        [
                            'property' => 'height',
                            'selector' => '.wpgb-facet .wpgb-search-facet input[type="search"]'
                        ],
                        [
                            'property' => 'min-height',
                            'selector' => '.wpgb-facet .wpgb-search-facet input[type="search"]'
                        ],
                        [
                            'property' => 'height',
                            'selector' => '.wpgb-facet .wpgb-autocomplete-facet input[type="search"]'
                        ],
                        [
                            'property' => 'min-height',
                            'selector' => '.wpgb-facet .wpgb-autocomplete-facet input[type="search"]'
                        ],
                        [
                            'property' => 'height',
                            'selector' => '.wpgb-facet .wpgb-date-facet label input[type="text"]'
                        ],
                        [
                            'property' => 'min-height',
                            'selector' => '.wpgb-facet .wpgb-date-facet label input[type="text"]'
                        ],

                        [
                            'property' => 'height',
                            'selector' => '.wpgb-facet .wpgb-number-facet .wpgb-number'
                        ],
                        [
                            'property' => 'min-height',
                            'selector' => '.wpgb-facet .wpgb-number-facet .wpgb-number'
                        ],

                        

                        
                    ],
                ];


                /* labels */

                $controls['x_facet_labels_typography'] = [
                    'tab'    => 'content',
                    'group' => 'x_facet_labels',
                    'type'   => 'typography',
                    'label'  => esc_html__( 'Typography', 'bricks' ),
                    'css'    => [
                        [
                            'property' => 'font',
                            'selector' => '.wpgb-facet .wpgb-radio-label'
                        ],
                        [
                            'property' => 'font',
                            'selector' => '.wpgb-facet .wpgb-button-label'
                        ],
                        [
                            'property' => 'font',
                            'selector' => '.wpgb-facet .wpgb-rating-label'
                        ],
                        [
                            'property' => 'font',
                            'selector' => '.wpgb-facet .wpgb-az-index-label'
                        ],
                        [
                            'property' => 'font',
                            'selector' => '.wpgb-facet .wpgb-checkbox-label'
                        ],
                        [
                            'property' => 'font',
                            'selector' => '.wpgb-facet .wpgb-hierarchy-label'
                        ],
                    ],
                ];


                /* checkbox / radio */

                $controls['x_facet_checkbox_size'] = [
                    'label' => esc_html__( 'Checkbox Size', 'bricks' ),
                    'group' => 'x_facet_checkbox',
                    'type'  => 'number',
                    'units' => true,
                    'placeholder' => '20px',
                    'css'   => [
                        [
                            'property' => 'height',
                            'selector' => '.wpgb-facet .wpgb-checkbox-facet .wpgb-checkbox-control'
                        ],
                        [
                            'property' => 'width',
                            'selector' => '.wpgb-facet .wpgb-checkbox-facet .wpgb-checkbox-control'
                        ],
                    ],
                ];

                $controls['x_facet_radio_size'] = [
                    'label' => esc_html__( 'Radio Size', 'bricks' ),
                    'group' => 'x_facet_checkbox',
                    'type'  => 'number',
                    'units' => true,
                    'placeholder' => '20px',
                    'css'   => [
                        [
                            'property' => 'height',
                            'selector' => '.wpgb-facet .wpgb-radio-facet .wpgb-radio-control'
                        ],
                        [
                            'property' => 'width',
                            'selector' => '.wpgb-facet .wpgb-radio-facet .wpgb-radio-control'
                        ],
                    ],
                ];

                $controls['x_facet_vertical-gap'] = [
                    'label' => esc_html__( 'Gap', 'bricks' ),
                    'group' => 'x_facet_checkbox',
                    'type'  => 'number',
                    'units' => true,
                    'css'   => [
                        [
                            'property' => 'gap',
                            'selector' => '.wpgb-facet .wpgb-radio-facet .wpgb-hierarchical-list'
                        ],
                        [
                            'property' => 'gap',
                            'selector' => '.wpgb-facet .wpgb-checkbox-facet .wpgb-hierarchical-list'
                        ],
                    ],
                ];

                $controls['x_facet_list_direction'] = [
                    'tab'      => 'content',
                    'label'    => esc_html__( 'List direction', 'bricks' ),
                    'group'		  => 'x_facet_checkbox',
                    'tooltip'  => [
                        'content'  => 'flex-direction',
                        'position' => 'top-left',
                    ],
                    'type'     => 'direction',
                    'css'      => [
                        [
                            'property' => 'flex-direction',
                            'selector' => '.wpgb-facet .wpgb-radio-facet .wpgb-hierarchical-list'
                        ],
                        [
                            'property' => 'flex-direction',
                            'selector' => '.wpgb-facet .wpgb-checkbox-facet .wpgb-hierarchical-list'
                        ],
                    ],
                    'inline'   => true,
                ];

                $controls['x_facet_list_wrap'] = [
                    'tab'         => 'content',
                    'label'       => esc_html__( 'Flex wrap', 'bricks' ),
                    'group'		  => 'x_facet_checkbox',
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
                            'selector' => '.wpgb-facet .wpgb-radio-facet .wpgb-hierarchical-list'
                        ],
                        [
                            'property' => 'flex-wrap',
                            'selector' => '.wpgb-facet .wpgb-checkbox-facet .wpgb-hierarchical-list'
                        ],
                    ],
                    'placeholder' => esc_html__( 'Wrap', 'bricks' ),
                ];

                

                $controls['checkbox_label_start'] = [
                    'tab'   => 'content',
                    'type'  => 'separator',
                    'group' => 'x_facet_checkbox',
                ];

                $controls['x_facet_checkbox_labels_typography'] = [
                    'tab'    => 'content',
                    'group' => 'x_facet_checkbox',
                    'type'   => 'typography',
                    'label'  => esc_html__( 'Label typography', 'bricks' ),
                    'css'    => [
                        [
                            'property' => 'font',
                            'selector' => '.wpgb-facet .wpgb-radio-facet .wpgb-radio-label'
                        ],
                        [
                            'property' => 'font',
                            'selector' => '.wpgb-facet .wpgb-checkbox-facet .wpgb-checkbox-label'
                        ],
                    ],
                ];

                $controls['x_facet-horizontal-gap'] = [
                    'label' => esc_html__( 'Horizontal gap', 'bricks' ),
                    'info'  => esc_html__( 'Gap between label and checkbox', 'bricks' ),
                    'group' => 'x_facet_checkbox',
                    'type'  => 'number',
                    'units' => true,
                    'css'   => [
                        [
                            'property' => 'padding-left',
                            'selector' => '.wpgb-facet .wpgb-radio-label'
                        ],
                        [
                            'property' => 'padding-left',
                            'selector' => '.wpgb-facet .wpgb-checkbox-label'
                        ],
                    ],
                ];

                $controls['checkbox_label_end'] = [
                    'tab'   => 'content',
                    'type'  => 'separator',
                    'group' => 'x_facet_checkbox',
                ];

                $controls['x_facet_checkbox_bg'] = [
                    'label' => esc_html__( 'Background color', 'bricks' ),
                    'group' => 'x_facet_checkbox',
                    'type'  => 'color',
                    'css'   => [
                        [
                            'property' => 'background-color',
                            'selector' => '.wpgb-facet .wpgb-checkbox .wpgb-checkbox-control',
                        ],
                        [
                            'property' => 'background-color',
                            'selector' => '.wpgb-facet .wpgb-radio .wpgb-radio-control',
                        ],
                    ],
                ];

                $controls['x_facet_checkbox_border'] = [
                    'label' => esc_html__( 'Border', 'bricks' ),
                    'group' => 'x_facet_checkbox',
                    'type'  => 'border',
                    'css'   => [
                        [
                            'property' => 'border',
                            'selector' => '.wpgb-facet .wpgb-checkbox .wpgb-checkbox-control',
                        ],
                        [
                            'property' => 'border',
                            'selector' => '.wpgb-facet .wpgb-radio .wpgb-radio-control',
                        ],
                    ],
                ];

                

                $controls['x_facet_checkbox_bg_selected'] = [
                    'label' => esc_html__( 'Background color (selected)', 'bricks' ),
                    'group' => 'x_facet_checkbox',
                    'type'  => 'color',
                    'css'   => [
                        [
                            'property' => 'background-color',
                            'selector' => '.wpgb-facet .wpgb-checkbox-facet .wpgb-checkbox[aria-pressed="true"] .wpgb-checkbox-control',
                        ],
                        [
                            'property' => 'background-color',
                            'selector' => '.wpgb-facet .wpgb-radio-facet .wpgb-radio[aria-pressed="true"] .wpgb-radio-control',
                        ],
                    ],
                ];

                

                $controls['x_facet_checkbox_border_selected'] = [
                    'label' => esc_html__( 'Border (selected)', 'bricks' ),
                    'group' => 'x_facet_checkbox',
                    'type'  => 'border',
                    'css'   => [
                        [
                            'property' => 'border',
                            'selector' => '.wpgb-facet .wpgb-checkbox-facet .wpgb-checkbox[aria-pressed="true"] .wpgb-checkbox-control',
                        ],
                        [
                            'property' => 'border',
                            'selector' => '.wpgb-facet .wpgb-radio-facet .wpgb-radio[aria-pressed="true"] .wpgb-radio-control',
                        ],
                    ],
                ];

                $controls['x_facet_checkbox_inner_selected'] = [
                    'label' => esc_html__( 'Inner color (selected)', 'bricks' ),
                    'group' => 'x_facet_checkbox',
                    'type'  => 'color',
                    'css'   => [
                        [
                            'property' => 'border-color',
                            'selector' => '.wpgb-facet .wpgb-checkbox-facet .wpgb-checkbox[aria-pressed="true"] .wpgb-checkbox-control:after',
                        ],
                        [
                            'property' => 'background-color',
                            'selector' => '.wpgb-facet .wpgb-radio-facet .wpgb-radio-control:after',
                        ],

                        
                    ],
                ];

               
                

               // loadmore

               $loadmoreSelector = '.wpgb-facet .wpgb-load-more.wpgb-button';
               
               $controls['x_facet_loadmore_border'] = [
                    'group' => 'title',
                    'label' => esc_html__( 'Border', 'bricks' ),
                    'group' => 'x_facet_loadmore',
                    'type'  => 'border',
                    'css'   => [
                        [
                            'property' => 'border',
                            'selector' => $loadmoreSelector,
                        ],
                    ],
                ];

                $controls['x_facet_loadmore_bg'] = [
                    'group' => 'title',
                    'label' => esc_html__( 'Background color', 'bricks' ),
                    'group' => 'x_facet_loadmore',
                    'type'  => 'color',
                    'css'   => [
                        [
                            'property' => 'background-color',
                            'selector' => $loadmoreSelector,
                        ],
                    ],
                ];

                $controls['x_facet_loadmore_font'] = [
                    'group' => 'title',
                    'label' => esc_html__( 'Typography', 'bricks' ),
                    'group' => 'x_facet_loadmore',
                    'type'  => 'typography',
                    'css'   => [
                        [
                            'property' => 'font',
                            'selector' => $loadmoreSelector,
                        ],
                    ],
                ];

                $controls['x_facet_loadmore_padding'] = [
                    'tab'   => 'content',
                    'group' => 'x_facet_loadmore',
                    'label' => esc_html__( 'Padding', 'extras' ),
                    'type'  => 'dimensions',
                    'css'   => [
                        [
                            'property' => 'padding',
                            'selector' => $loadmoreSelector,
                        ],
                    ],
                ];

                $controls['x_facet_loadmore_width'] = [
                    'tab'    => 'content',
                    'type'   => 'number',
                    'units' => true,
                    'group' => 'x_facet_loadmore',
                    'label'  => esc_html__( 'Button width', 'bricks' ),
                    'css'    => [
                        [
                            'property' => 'width',
                            'selector' => $loadmoreSelector
                        ],
                    ],
                ];


                /* slider */
                $sliderSelector = ".wpgb-facet .wpgb-range-facet .wpgb-range-slider";
                $thumbSelector = ".wpgb-facet .wpgb-range-facet .wpgb-range-slider .wpgb-range-thumb";

                $controls['slider_sep'] = [
                    'tab'   => 'content',
                    'type'  => 'separator',
                    'label' => esc_html__( 'Slider', 'bricks' ),
                    'group' => 'x_facet_slider',
                ];

                $controls['x_facet_slider_height'] = [
                    'group' => 'title',
                    'label' => esc_html__( 'Height', 'bricks' ),
                    'group' => 'x_facet_slider',
                    'type'  => 'number',
                    'units' => true,
                    'css'   => [
                        [
                            'property' => 'height',
                            'selector' => $sliderSelector,
                        ],
                    ],
                    'placeholder' => '6px'
                ];

                $controls['x_facet_slider_border'] = [
                    'group' => 'title',
                    'label' => esc_html__( 'Border', 'bricks' ),
                    'group' => 'x_facet_slider',
                    'type'  => 'border',
                    'css'   => [
                        [
                            'property' => 'border',
                            'selector' => $sliderSelector,
                        ],
                    ],
                ];

                $controls['x_facet_slider_bg'] = [
                    'group' => 'title',
                    'label' => esc_html__( 'Background color', 'bricks' ),
                    'group' => 'x_facet_slider',
                    'type'  => 'color',
                    'css'   => [
                        [
                            'property' => 'background-color',
                            'selector' => $sliderSelector,
                        ],
                    ],
                ];

                $controls['x_facet_slider_progress_bg'] = [
                    'group' => 'title',
                    'label' => esc_html__( 'Progress Background color', 'bricks' ),
                    'group' => 'x_facet_slider',
                    'type'  => 'color',
                    'css'   => [
                        [
                            'property' => 'background-color',
                            'selector' => '.wpgb-facet .wpgb-range-facet .wpgb-range-slider .wpgb-range-progress'
                        ],
                    ],
                ];


                $controls['slider_thumb_sep'] = [
                    'tab'   => 'content',
                    'type'  => 'separator',
                    'label' => esc_html__( 'Slider Thumb', 'bricks' ),
                    'group' => 'x_facet_slider',
                ];

                $controls['x_facet_slider_control_height'] = [
                    'group' => 'title',
                    'label' => esc_html__( 'Size', 'bricks' ),
                    'group' => 'x_facet_slider',
                    'type'  => 'number',
                    'units' => true,
                    'placeholder' => '16px',
                    'css'   => [
                        [
                            'property' => 'height',
                            'selector' => $thumbSelector
                        ],
                        [
                            'property' => 'width',
                            'selector' => $thumbSelector
                        ],
                    ],
                ];


                $controls['x_facet_slider_control_bg'] = [
                    'tab'    => 'content',
                    'group'  => 'x_facet_slider',
                    'type'   => 'color',
                    'label'  => esc_html__( 'Background', 'extras' ),
                    'css'    => [
                        [
                            'property' => 'background-color',
                            'selector' => $thumbSelector,
                        ],
                    ],
                ];
            
                $controls['x_facet_slider_control_border'] = [
                    'tab'    => 'content',
                    'group'  => 'x_facet_slider',
                    'type'   => 'border',
                    'label'  => esc_html__( 'Border', 'extras' ),
                    'css'    => [
                        [
                            'property' => 'border',
                            'selector' => $thumbSelector,
                        ],
                    ],
                ];
                
                $controls['range_value_sep'] = [
                    'tab'   => 'content',
                    'type'  => 'separator',
                    'group' => 'x_facet_slider',
                ];
                
                $controls['x_facet_range_typography'] = [
                    'tab'    => 'content',
                    'group' => 'x_facet_slider',
                    'type'   => 'typography',
                    'label'  => esc_html__( 'Range Value Typography', 'bricks' ),
                    'css'    => [
                        [
                            'property' => 'font',
                            'selector' => '.wpgb-facet .wpgb-range-facet .wpgb-range-values'
                        ],
                    ],
                ];

                $controls['range_clear_sep'] = [
                    'tab'   => 'content',
                    'type'  => 'separator',
                    'label' => esc_html__( 'Range Clear Button', 'bricks' ),
                    'group' => 'x_facet_slider',
                ];
                
                $rangeClearButton = '.wpgb-facet .wpgb-range-facet .wpgb-range-clear';

                $controls['x_facet_range_clear_button_typography'] = [
                    'tab'    => 'content',
                    'group' => 'x_facet_slider',
                    'type'   => 'typography',
                    'label'  => esc_html__( 'Typography', 'bricks' ),
                    'css'    => [
                        [
                            'property' => 'font',
                            'selector' => $rangeClearButton,
                        ],
                    ],
                ];

                $controls['x_facet_range_clear_button_bg'] = [
                    'tab'    => 'content',
                    'group'  => 'x_facet_slider',
                    'type'   => 'color',
                    'label'  => esc_html__( 'Background', 'extras' ),
                    'css'    => [
                        [
                            'property' => 'background-color',
                            'selector' => $rangeClearButton,
                        ],
                    ],
                ];
            
                $controls['x_facet_range_clear_button_border'] = [
                    'tab'    => 'content',
                    'group'  => 'x_facet_slider',
                    'type'   => 'border',
                    'label'  => esc_html__( 'Border', 'extras' ),
                    'css'    => [
                        [
                            'property' => 'border',
                            'selector' => $rangeClearButton,
                        ],
                    ],
                ];
            
                $controls['x_facet_range_clear_button_boxshadow'] = [
                    'tab'    => 'content',
                    'group'  => 'x_facet_slider',
                    'label'  => esc_html__( 'Box Shadow', 'extras' ),
                    'type'   => 'box-shadow',
                    'css'    => [
                        [
                            'property' => 'box-shadow',
                            'selector' => $rangeClearButton,
                        ],
                    ],
                ];
            
                $controls['x_facet_range_clear_button_padding'] = [
                    'tab'   => 'content',
                    'group' => 'x_facet_slider',
                    'label' => esc_html__( 'Padding', 'extras' ),
                    'type'  => 'dimensions',
                    'css'   => [
                        [
                            'property' => 'padding',
                            'selector' => $rangeClearButton,
                        ],
                    ],
                ];



                /* buttons */

                $buttonSelector = '.wpgb-facet .wpgb-button';

                $controls['x_facet_button_typography'] = [
                    'tab'    => 'content',
                    'group' => 'x_facet_buttons',
                    'type'   => 'typography',
                    'label'  => esc_html__( 'Typography', 'bricks' ),
                    'css'    => [
                        [
                            'property' => 'font',
                            'selector' => '.wpgb-facet .wpgb-button-facet .wpgb-button'
                        ],
                        [
                            'property' => 'font',
                            'selector' => '.wpgb-facet button.wpgb-button'
                        ],
                        [
                            'property' => 'font',
                            'selector' => '.wpgb-facet .wpgb-button-label'
                        ],
                        [
                            'property' => 'font',
                            'selector' => '.wpgb-facet .wpgb-button'
                        ],
                        
                    ],
                ];

                $controls['x_facet_button_bg'] = [
                    'tab'    => 'content',
                    'group'  => 'x_facet_buttons',
                    'type'   => 'color',
                    'label'  => esc_html__( 'Background', 'extras' ),
                    'css'    => [
                        [
                            'property' => 'background-color',
                            'selector' => '.wpgb-facet .wpgb-button-facet .wpgb-button'
                        ],
                        [
                            'property' => 'background-color',
                            'selector' => '.wpgb-facet button.wpgb-button'
                        ],
                        [
                            'property' => 'background-color',
                            'selector' => '.wpgb-facet .wpgb-button'
                        ],
                    ],
                ];

                
                
            
                $controls['x_facet_button_border'] = [
                    'tab'    => 'content',
                    'group'  => 'x_facet_buttons',
                    'type'   => 'border',
                    'label'  => esc_html__( 'Border', 'extras' ),
                    'css'    => [
                        [
                            'property' => 'border',
                            'selector' => '.wpgb-facet .wpgb-button-facet .wpgb-button'
                        ],
                        [
                            'property' => 'border',
                            'selector' => '.wpgb-facet button.wpgb-button'
                        ],
                        [
                            'property' => 'border',
                            'selector' => '.wpgb-facet .wpgb-button'
                        ],
                    ],
                ];

                
                $controls['x_facet_button_boxshadow'] = [
                    'tab'    => 'content',
                    'group'  => 'x_facet_buttons',
                    'label'  => esc_html__( 'Box Shadow', 'extras' ),
                    'type'   => 'box-shadow',
                    'css'    => [
                        [
                            'property' => 'box-shadow',
                            'selector' => '.wpgb-facet .wpgb-button-facet .wpgb-button'
                        ],
                        [
                            'property' => 'box-shadow',
                            'selector' => '.wpgb-facet button.wpgb-button'
                        ],
                        [
                            'property' => 'box-shadow',
                            'selector' => '.wpgb-facet .wpgb-button'
                        ],
                    ],
                ];
            
                $controls['x_facet_button_padding'] = [
                    'tab'   => 'content',
                    'group' => 'x_facet_buttons',
                    'label' => esc_html__( 'Padding', 'extras' ),
                    'type'  => 'dimensions',
                    'css'   => [
                        [
                            'property' => 'padding',
                            'selector' => '.wpgb-facet .wpgb-button-facet .wpgb-button'
                        ],
                        [
                            'property' => 'padding',
                            'selector' => '.wpgb-facet button.wpgb-button'
                        ],
                        [
                            'property' => 'padding',
                            'selector' => '.wpgb-facet .wpgb-button'
                        ],
                    ],
                ];


                $controls['button_selected_sep'] = [
                    'tab'   => 'content',
                    'type'  => 'separator',
                    'label' => esc_html__( 'Selected', 'bricks' ),
                    'group' => 'x_facet_buttons',
                ];

                $controls['x_facet_button_typography_selected'] = [
                    'tab'    => 'content',
                    'group' => 'x_facet_buttons',
                    'type'   => 'typography',
                    'label'  => esc_html__( 'Typography', 'bricks' ),
                    'css'    => [
                        [
                            'property' => 'font',
                            'selector' => '.wpgb-facet .wpgb-button-facet .wpgb-button[aria-pressed="true"]'
                        ],
                    ],
                ];

                $controls['x_facet_button_bg_selected'] = [
                    'tab'    => 'content',
                    'group'  => 'x_facet_buttons',
                    'type'   => 'color',
                    'label'  => esc_html__( 'Background', 'extras' ),
                    'css'    => [
                        [
                            'property' => 'background-color',
                            'selector' => '.wpgb-facet .wpgb-button-facet .wpgb-button[aria-pressed="true"]',
                        ],
                    ],
                ];


                $controls['x_facet_button_border_selected'] = [
                    'tab'    => 'content',
                    'group'  => 'x_facet_buttons',
                    'type'   => 'border',
                    'label'  => esc_html__( 'Border', 'extras' ),
                    'css'    => [
                        [
                            'property' => 'border',
                            'selector' => '.wpgb-facet .wpgb-button-facet .wpgb-button[aria-pressed="true"]'
                        ],
                    ],
                ];


                /* selections */

                $selectionsButtonSelector = '.wpgb-facet .wpgb-selection-facet .wpgb-button';

                $controls['x_facet_selection_buttons_sep'] = [
                    'tab'   => 'content',
                    'type'  => 'separator',
                    'label' => esc_html__( 'Selection buttons', 'bricks' ),
                    'group' => 'x_facet_selections',
                ];

                $controls['x_facet_selections_typography'] = [
                    'tab'    => 'content',
                    'group' => 'x_facet_selections',
                    'type'   => 'typography',
                    'label'  => esc_html__( 'Typography', 'bricks' ),
                    'css'    => [
                        [
                            'property' => 'font',
                            'selector' => $selectionsButtonSelector
                        ],
                        [
                            'property' => 'font',
                            'selector' => $selectionsButtonSelector . ' .wpgb-button-label'
                        ],
                    ],
                ];

                $controls['x_facet_button_selectionsbg'] = [
                    'tab'    => 'content',
                    'group'  => 'x_facet_selections',
                    'type'   => 'color',
                    'label'  => esc_html__( 'Background', 'extras' ),
                    'css'    => [
                        [
                            'property' => 'background-color',
                            'selector' => $selectionsButtonSelector
                        ],
                    ],
                ];
            
                $controls['x_facet_button_selectionsborder'] = [
                    'tab'    => 'content',
                    'group'  => 'x_facet_selections',
                    'type'   => 'border',
                    'label'  => esc_html__( 'Border', 'extras' ),
                    'css'    => [
                        [
                            'property' => 'border',
                            'selector' => $selectionsButtonSelector
                        ],
                    ],
                ];

                
                $controls['x_facet_button_selectionsboxshadow'] = [
                    'tab'    => 'content',
                    'group'  => 'x_facet_selections',
                    'label'  => esc_html__( 'Box Shadow', 'extras' ),
                    'type'   => 'box-shadow',
                    'css'    => [
                        [
                            'property' => 'box-shadow',
                            'selector' => $selectionsButtonSelector
                        ],
                    ],
                ];
            
                $controls['x_facet_button_selectionspadding'] = [
                    'tab'   => 'content',
                    'group' => 'x_facet_selections',
                    'label' => esc_html__( 'Padding', 'extras' ),
                    'type'  => 'dimensions',
                    'css'   => [
                        [
                            'property' => 'padding',
                            'selector' => $selectionsButtonSelector
                        ],
                    ],
                ];

            

                /* dropdown */

                $controls['x_facet_dropdown_height'] = [
                    'tab'   => 'content',
                    'group' => 'x_facet_dropdown',
                    'label' => esc_html__( 'Input height', 'extras' ),
                    'type'  => 'number',
                    'css'   => [
                        [
                            'property' => 'height',
                            'selector' => '.wpgb-facet .wpgb-select-facet .wpgb-select'
                        ],
                        [
                            'property' => 'min-height',
                            'selector' => '.wpgb-facet .wpgb-select-facet select.wpgb-select'
                        ],
                    ],
                ];

                $controls['x_facet_dropdown_padding'] = [
                    'tab'   => 'content',
                    'group' => 'x_facet_dropdown',
                    'label' => esc_html__( 'Input padding', 'extras' ),
                    'type'  => 'dimensions',
                    'css'   => [
                        [
                            'property' => 'padding',
                            'selector' => '.wpgb-facet select.wpgb-select'
                        ],
                        [
                            'property' => 'padding',
                            'selector' => '.wpgb-facet .wpgb-select-placeholder'
                        ],
                        
                    ],
                ];

                $controls['x_facet_dropdown_icon_color'] = [
                    'tab'   => 'content',
                    'group' => 'x_facet_dropdown',
                    'label' => esc_html__( 'Icon color', 'extras' ),
                    'type'  => 'color',
                    'css'   => [
                        [
                            'property' => 'color',
                            'selector' => '.wpgb-facet .wpgb-select-facet .wpgb-select button'
                        ],
                    ],
                ];

                $controls['x_facet_dropdown_sepearator_color'] = [
                    'tab'   => 'content',
                    'group' => 'x_facet_dropdown',
                    'label' => esc_html__( 'Sepearator color', 'extras' ),
                    'type'  => 'color',
                    'css'   => [
                        [
                            'property' => 'background-color',
                            'selector' => '.wpgb-select .wpgb-select-controls .wpgb-select-separator'
                        ],
                    ],
                ];

                $controls['x_facet_dropdown_border'] = [
                    'tab'    => 'content',
                    'group'  => 'x_facet_dropdown',
                    'type'   => 'border',
                    'label'  => esc_html__( 'Border', 'extras' ),
                    'css'    => [
                        [
                            'property' => 'border',
                            'selector' => '.wpgb-facet div.wpgb-select'
                        ],
                        [
                            'property' => 'border',
                            'selector' => '.wpgb-facet select.wpgb-select'
                        ],
                    ],
                ];


                $controls['x_facet_dropdown_bg'] = [
                    'group' => 'title',
                    'label' => esc_html__( 'Background color', 'bricks' ),
                    'group' => 'x_facet_dropdown',
                    'type'  => 'color',
                    'css'   => [
                        [
                            'property' => 'background-color',
                            'selector' => '.wpgb-facet div.wpgb-select'
                        ],
                        [
                            'property' => 'background-color',
                            'selector' => '.wpgb-facet select.wpgb-select'
                        ],
                    ],
                ];

                $controls['x_facet_dropdown_font'] = [
                    'group' => 'title',
                    'label' => esc_html__( 'Typography', 'bricks' ),
                    'group' => 'x_facet_dropdown',
                    'type'  => 'typography',
                    'css'   => [
                        [
                            'property' => 'font',
                            'selector' => '.wpgb-facet div.wpgb-select'
                        ],
                        [
                            'property' => 'font',
                            'selector' => '.wpgb-facet select.wpgb-select'
                        ],
                        [
                            'property' => 'font',
                            'selector' => '.wpgb-facet .wpgb-select-placeholder'
                        ],
                    ],
                ];

                


                $controls['x_facet_dropdown_selection_sep'] = [
                    'tab'   => 'content',
                    'type'  => 'separator',
                    'label' => esc_html__( 'Multiple Selection values', 'bricks' ),
                    'group' => 'x_facet_dropdown',
                ];

                $dropdownSelectedValues = '.wpgb-select-placeholder .wpgb-select-values>div.wpgb-select-value';

                $controls['x_facet_dropdown_selected_bg'] = [
                    'group' => 'title',
                    'label' => esc_html__( 'Background color', 'bricks' ),
                    'group' => 'x_facet_dropdown',
                    'type'  => 'color',
                    'css'   => [
                        [
                            'property' => 'background-color',
                            'selector' => $dropdownSelectedValues
                        ],
                    ],
                ];

                $controls['x_facet_dropdown_selected_border'] = [
                    'tab'    => 'content',
                    'group'  => 'x_facet_dropdown',
                    'type'   => 'border',
                    'label'  => esc_html__( 'Border', 'extras' ),
                    'css'    => [
                        [
                            'property' => 'border',
                            'selector' => $dropdownSelectedValues
                        ],
                    ],
                ];
            
                $controls['x_facet_dropdown_selected_boxshadow'] = [
                    'tab'    => 'content',
                    'group'  => 'x_facet_dropdown',
                    'label'  => esc_html__( 'Box Shadow', 'extras' ),
                    'type'   => 'box-shadow',
                    'css'    => [
                        [
                            'property' => 'box-shadow',
                            'selector' => $dropdownSelectedValues
                        ],
                    ],
                ];

                $controls['x_facet_dropdown_selected_font'] = [
                    'tab'    => 'content',
                    'group' => 'x_facet_dropdown',
                    'type'   => 'typography',
                    'label'  => esc_html__( 'Typography', 'bricks' ),
                    'css'    => [
                        [
                            'property' => 'font',
                            'selector' => $dropdownSelectedValues
                        ],
                    ],
                ];

                $controls['x_facet_dropdown_selected_padding'] = [
                    'tab'   => 'content',
                    'group' => 'x_facet_dropdown',
                    'label' => esc_html__( 'Padding', 'extras' ),
                    'type'  => 'dimensions',
                    'css'   => [
                        [
                            'property' => 'padding',
                            'selector' => $dropdownSelectedValues
                        ],
                    ],
                ];

                $controls['x_facet_dropdown_selected_margin'] = [
                    'tab'   => 'content',
                    'group' => 'x_facet_dropdown',
                    'label' => esc_html__( 'Margin', 'extras' ),
                    'type'  => 'dimensions',
                    'css'   => [
                        [
                            'property' => 'margin',
                            'selector' => $dropdownSelectedValues
                        ],
                    ],
                ];


                $controls['x_facet_dropdown_selection_remove_sep'] = [
                    'tab'   => 'content',
                    'type'  => 'separator',
                    'label' => esc_html__( 'Multiple Selection remove icon', 'bricks' ),
                    'group' => 'x_facet_dropdown',
                ];

                $dropdownSelectedRemoveButton = '.wpgb-select-placeholder .wpgb-select-values>div.wpgb-select-value button.wpgb-select-remove';

                $controls['x_facet_dropdown_selected_remove_bg'] = [
                    'group' => 'title',
                    'label' => esc_html__( 'Background color', 'bricks' ),
                    'group' => 'x_facet_dropdown',
                    'type'  => 'color',
                    'css'   => [
                        [
                            'property' => 'background-color',
                            'selector' => $dropdownSelectedRemoveButton
                        ],
                    ],
                ];

                $controls['x_facet_dropdown_selected_remove_border'] = [
                    'tab'    => 'content',
                    'group'  => 'x_facet_dropdown',
                    'type'   => 'border',
                    'label'  => esc_html__( 'Border', 'extras' ),
                    'css'    => [
                        [
                            'property' => 'border',
                            'selector' => $dropdownSelectedRemoveButton
                        ],
                    ],
                ];
            
                $controls['x_facet_dropdown_selected_remove_boxshadow'] = [
                    'tab'    => 'content',
                    'group'  => 'x_facet_dropdown',
                    'label'  => esc_html__( 'Box Shadow', 'extras' ),
                    'type'   => 'box-shadow',
                    'css'    => [
                        [
                            'property' => 'box-shadow',
                            'selector' => $dropdownSelectedRemoveButton
                        ],
                    ],
                ];

                $controls['x_facet_dropdown_selected_remove_font'] = [
                    'tab'    => 'content',
                    'group' => 'x_facet_dropdown',
                    'type'   => 'typography',
                    'label'  => esc_html__( 'Typography', 'bricks' ),
                    'css'    => [
                        [
                            'property' => 'font',
                            'selector' => $dropdownSelectedRemoveButton
                        ],
                    ],
                ];

                $controls['x_facet_dropdown_selected_remove_padding'] = [
                    'tab'   => 'content',
                    'group' => 'x_facet_dropdown',
                    'label' => esc_html__( 'Padding', 'extras' ),
                    'type'  => 'dimensions',
                    'css'   => [
                        [
                            'property' => 'padding',
                            'selector' => $dropdownSelectedRemoveButton
                        ],
                    ],
                ];

                $controls['x_facet_dropdown_selected_remove_margin'] = [
                    'tab'   => 'content',
                    'group' => 'x_facet_dropdown',
                    'label' => esc_html__( 'Margin', 'extras' ),
                    'type'  => 'dimensions',
                    'css'   => [
                        [
                            'property' => 'margin',
                            'selector' => $dropdownSelectedRemoveButton
                        ],
                    ],
                ];

                

                

                /* pagination */

                $controls['pagination_link_sep'] = [
                    'tab'   => 'content',
                    'type'  => 'separator',
                    'label' => esc_html__( 'Pagination links', 'bricks' ),
                    'group' => 'x_facet_pagination',
                ];

                $paginationLinkSelector = '.wpgb-facet .wpgb-pagination li a';

                $controls['x_facet_pagination_typography'] = [
                    'tab'    => 'content',
                    'group' => 'x_facet_pagination',
                    'type'   => 'typography',
                    'label'  => esc_html__( 'Typography', 'bricks' ),
                    'css'    => [
                        [
                            'property' => 'font',
                            'selector' => $paginationLinkSelector
                        ],
                    ],
                ];


                $controls['x_facet_pagination_bg'] = [
                    'tab'    => 'content',
                    'group'  => 'x_facet_pagination',
                    'type'   => 'color',
                    'label'  => esc_html__( 'Background color', 'extras' ),
                    'css'    => [
                        [
                            'property' => 'background-color',
                            'selector' => $paginationLinkSelector
                        ],
                    ],
                ];

                
            
                $controls['x_facet_pagination_border'] = [
                    'tab'    => 'content',
                    'group'  => 'x_facet_pagination',
                    'type'   => 'border',
                    'label'  => esc_html__( 'Border', 'extras' ),
                    'css'    => [
                        [
                            'property' => 'border',
                            'selector' => $paginationLinkSelector
                        ],
                    ],
                ];
            
                $controls['x_facet_pagination_boxshadow'] = [
                    'tab'    => 'content',
                    'group'  => 'x_facet_pagination',
                    'label'  => esc_html__( 'Box Shadow', 'extras' ),
                    'type'   => 'box-shadow',
                    'css'    => [
                        [
                            'property' => 'box-shadow',
                            'selector' => $paginationLinkSelector
                        ],
                    ],
                ];
            
                $controls['x_facet_pagination_padding'] = [
                    'tab'   => 'content',
                    'group' => 'x_facet_pagination',
                    'label' => esc_html__( 'Padding', 'extras' ),
                    'type'  => 'dimensions',
                    'css'   => [
                        [
                            'property' => 'padding',
                            'selector' => $paginationLinkSelector
                        ],
                    ],
                ];

                $controls['x_facet_pagination_gap'] = [
                    'tab'   => 'content',
                    'group' => 'x_facet_pagination',
                    'label' => esc_html__( 'Gap', 'extras' ),
                    'type'  => 'number',
                    'units' => true,
                    'css'   => [
                        [
                            'property' => 'gap',
                            'selector' => '.wpgb-pagination'
                        ],
                    ],
                ];

               


                $controls['pagination_link_current_sep'] = [
                    'tab'   => 'content',
                    'type'  => 'separator',
                    'label' => esc_html__( 'Pagination links (current)', 'bricks' ),
                    'group' => 'x_facet_pagination',
                ];


                $controls['x_facet_pagination_current_typography'] = [
                    'tab'    => 'content',
                    'group' => 'x_facet_pagination',
                    'type'   => 'typography',
                    'label'  => esc_html__( 'Typography', 'bricks' ),
                    'css'    => [
                        [
                            'property' => 'font',
                            'selector' => $paginationLinkSelector . '[aria-current]'
                        ],
                    ],
                ];


                $controls['x_facet_pagination_current_bg'] = [
                    'tab'    => 'content',
                    'group'  => 'x_facet_pagination',
                    'type'   => 'color',
                    'label'  => esc_html__( 'Background color', 'extras' ),
                    'css'    => [
                        [
                            'property' => 'background-color',
                            'selector' => $paginationLinkSelector . '[aria-current]'
                        ],
                    ],
                ];

                
            
                $controls['x_facet_pagination_current_border'] = [
                    'tab'    => 'content',
                    'group'  => 'x_facet_pagination',
                    'type'   => 'border',
                    'label'  => esc_html__( 'Border', 'extras' ),
                    'css'    => [
                        [
                            'property' => 'border',
                            'selector' => $paginationLinkSelector . '[aria-current]'
                        ],
                    ],
                ];
            
                $controls['x_facet_pagination_current_boxshadow'] = [
                    'tab'    => 'content',
                    'group'  => 'x_facet_pagination',
                    'label'  => esc_html__( 'Box Shadow', 'extras' ),
                    'type'   => 'box-shadow',
                    'css'    => [
                        [
                            'property' => 'box-shadow',
                            'selector' => $paginationLinkSelector . '[aria-current]'
                        ],
                    ],
                ];




                /* reset */

                $resetSelector = '.wpgb-facet .wpgb-button.wpgb-reset';

                $controls['x_facet_reset_typography'] = [
                    'tab'    => 'content',
                    'group' => 'x_facet_reset',
                    'type'   => 'typography',
                    'label'  => esc_html__( 'Typography', 'bricks' ),
                    'css'    => [
                        [
                            'property' => 'font',
                            'selector' => $resetSelector
                        ],
                    ],
                ];

                $controls['x_facet_reset_bg'] = [
                    'tab'    => 'content',
                    'group'  => 'x_facet_reset',
                    'type'   => 'color',
                    'label'  => esc_html__( 'Background', 'extras' ),
                    'css'    => [
                        [
                            'property' => 'background-color',
                            'selector' => $resetSelector
                        ],
                    ],
                ];

            
                $controls['x_facet_reset_border'] = [
                    'tab'    => 'content',
                    'group'  => 'x_facet_reset',
                    'type'   => 'border',
                    'label'  => esc_html__( 'Border', 'extras' ),
                    'css'    => [
                        [
                            'property' => 'border',
                            'selector' => $resetSelector
                        ],
                    ],
                ];

                
                $controls['x_facet_reset_boxshadow'] = [
                    'tab'    => 'content',
                    'group'  => 'x_facet_reset',
                    'label'  => esc_html__( 'Box Shadow', 'extras' ),
                    'type'   => 'box-shadow',
                    'css'    => [
                        [
                            'property' => 'box-shadow',
                            'selector' => $resetSelector
                        ],
                    ],
                ];

                $controls['x_facet_reset-button-width'] = [
                    'tab'    => 'content',
                    'type'   => 'number',
                    'units' => true,
                    'group' => 'x_facet_reset',
                    'label'  => esc_html__( 'Button width', 'bricks' ),
                    'css'    => [
                        [
                            'property' => 'width',
                            'selector' => $resetSelector
                        ],
                    ],
                ];
            
                $controls['x_facet_reset_padding'] = [
                    'tab'   => 'content',
                    'group' => 'x_facet_reset',
                    'label' => esc_html__( 'Padding', 'extras' ),
                    'type'  => 'dimensions',
                    'css'   => [
                        [
                            'property' => 'padding',
                            'selector' => $resetSelector
                        ],
                    ],
                ];


                 /* search */

                 $controls['x_facet_search_padding'] = [
                    'tab'   => 'content',
                    'group' => 'x_facet_search',
                    'label' => esc_html__( 'Input Padding', 'extras' ),
                    'type'  => 'dimensions',
                    'css'   => [
                        [
                            'property' => 'padding',
                            'selector' => '.wpgb-facet .wpgb-autocomplete-facet input[type="search"]'
                        ],
                        [
                            'property' => 'padding',
                            'selector' => '.wpgb-facet .wpgb-search-facet input[type="search"]'
                        ],
                       
                    ],
                ];

                /* number */

                $numberInputSelector = '.wpgb-facet .wpgb-number-facet .wpgb-number';

                $controls['x_facet_number_sep'] = [
                    'tab'    => 'content',
                    'group'  => 'x_facet_number',
                    'type'   => 'separator',
                    'label'  => esc_html__( 'Input styles', 'extras' ),
                ];

                $controls['x_facet_number_bg'] = [
                    'tab'    => 'content',
                    'group'  => 'x_facet_number',
                    'type'   => 'color',
                    'label'  => esc_html__( 'Background', 'extras' ),
                    'css'    => [
                        [
                            'property' => 'background-color',
                            'selector' => $numberInputSelector
                        ],
                    ],
                ];

            
                $controls['x_facet_number_border'] = [
                    'tab'    => 'content',
                    'group'  => 'x_facet_number',
                    'type'   => 'border',
                    'label'  => esc_html__( 'Border', 'extras' ),
                    'css'    => [
                        [
                            'property' => 'border',
                            'selector' => $numberInputSelector
                        ],
                    ],
                ];

                
                $controls['x_facet_number_boxshadow'] = [
                    'tab'    => 'content',
                    'group'  => 'x_facet_number',
                    'label'  => esc_html__( 'Box Shadow', 'extras' ),
                    'type'   => 'box-shadow',
                    'css'    => [
                        [
                            'property' => 'box-shadow',
                            'selector' => $numberInputSelector
                        ],
                    ],
                ];
            

                $controls['x_facet_number_padding'] = [
                    'tab'   => 'content',
                    'group' => 'x_facet_number',
                    'label' => esc_html__( 'Input Padding', 'extras' ),
                    'type'  => 'dimensions',
                    'css'   => [
                        [
                            'property' => 'padding',
                            'selector' => $numberInputSelector
                        ],
                    ],
                ];

                $controls['x_facet_number_submit_sep'] = [
                    'tab'    => 'content',
                    'group'  => 'x_facet_number',
                    'type'   => 'separator',
                    'label'  => esc_html__( 'Submit Button', 'extras' ),
                ];

                $numberSubmitSelector = '.wpgb-facet button.wpgb-button.wpgb-number-submit';


                $controls['x_facet_number_submit_border'] = [
                    'group' => 'title',
                    'label' => esc_html__( 'Border', 'bricks' ),
                    'group' => 'x_facet_number',
                    'type'  => 'border',
                    'css'   => [
                        [
                            'property' => 'border',
                            'selector' => $numberSubmitSelector,
                        ],
                    ],
                ];


                $controls['x_facet_number_submit_bg'] = [
                    'group' => 'title',
                    'label' => esc_html__( 'Background color', 'bricks' ),
                    'group' => 'x_facet_number',
                    'type'  => 'color',
                    'css'   => [
                        [
                            'property' => 'background-color',
                            'selector' => $numberSubmitSelector,
                        ],
                    ],
                ];

                $controls['x_facet_number_submit_font'] = [
                    'group' => 'title',
                    'label' => esc_html__( 'Typography', 'bricks' ),
                    'group' => 'x_facet_number',
                    'type'  => 'typography',
                    'css'   => [
                        [
                            'property' => 'font',
                            'selector' => $numberSubmitSelector,
                        ],
                    ],
                ];

                $controls['x_facet_number_submit_padding'] = [
                    'tab'   => 'content',
                    'group' => 'x_facet_number',
                    'label' => esc_html__( 'Padding', 'extras' ),
                    'type'  => 'dimensions',
                    'css'   => [
                        [
                            'property' => 'padding',
                            'selector' => $numberSubmitSelector,
                        ],
                    ],
                ];

                $controls['x_facet_number_submit_width'] = [
                    'tab'    => 'content',
                    'type'   => 'number',
                    'units' => true,
                    'group' => 'x_facet_number',
                    'label'  => esc_html__( 'Button width', 'bricks' ),
                    'css'    => [
                        [
                            'property' => 'width',
                            'selector' => $numberSubmitSelector
                        ],
                    ],
                ];




                /* date */

                $controls['x_facet_date_padding'] = [
                    'tab'   => 'content',
                    'group' => 'x_facet_date',
                    'label' => esc_html__( 'Input Padding', 'extras' ),
                    'type'  => 'dimensions',
                    'css'   => [
                        [
                            'property' => 'padding',
                            'selector' => '.wpgb-facet .wpgb-date-facet label input[type="text"].wpgb-input'
                        ],
                    ],
                ];


                /* star rating */

                $controls['x_facet_star_color'] = [
                    'tab'    => 'content',
                    'group'  => 'x_facet_star_rating',
                    'type'   => 'color',
                    'label'  => esc_html__( 'Star Color', 'extras' ),
                    'css'    => [
                        [
                            'property' => 'color',
                            'selector' => '.wpgb-facet .wpgb-rating-facet .wpgb-rating-control .wpgb-rating-svg'
                        ],
                    ],
                ];

                $controls['x_facet_star_color_active'] = [
                    'tab'    => 'content',
                    'group'  => 'x_facet_star_rating',
                    'type'   => 'color',
                    'label'  => esc_html__( 'Star Color (active)', 'extras' ),
                    'css'    => [
                        [
                            'property' => 'color',
                            'selector' => '.wpgb-facet .wpgb-rating-facet .wpgb-rating[aria-pressed="true"] .wpgb-rating-svg'
                        ],
                        [
                            'property' => 'color',
                            'selector' => '.wpgb-facet .wpgb-rating-facet .wpgb-rating:hover:not([tabindex="-1"]) .wpgb-rating-svg'
                        ],
                    ],
                ];


                 /* a-z index */

                 $indexButtonSelector = '.wpgb-facet .wpgb-az-index-facet .wpgb-az-index-label';

                 $controls['x_facet_index_sep'] = [
                    'tab'    => 'content',
                    'group'  => 'x_facet_az_index',
                    'type'   => 'separator',
                    'label'  => esc_html__( 'Button styles', 'extras' ),
                ];

                 $controls['x_facet_index_typography'] = [
                    'tab'   => 'content',
                    'group' => 'x_facet_az_index',
                    'label' => esc_html__( 'Typography', 'extras' ),
                    'type'  => 'typography',
                    'css'   => [
                        [
                            'property' => 'font',
                            'selector' => $indexButtonSelector
                        ],
                    ],
                    'placeholder' => '0.4'
                ];

                $controls['x_facet_index_bg'] = [
                    'tab'    => 'content',
                    'group'  => 'x_facet_az_index',
                    'type'   => 'color',
                    'label'  => esc_html__( 'Background', 'extras' ),
                    'css'    => [
                        [
                            'property' => 'background-color',
                            'selector' => $indexButtonSelector
                        ],
                    ],
                ];

            
                $controls['x_facet_index_border'] = [
                    'tab'    => 'content',
                    'group'  => 'x_facet_az_index',
                    'type'   => 'border',
                    'label'  => esc_html__( 'Border', 'extras' ),
                    'css'    => [
                        [
                            'property' => 'border',
                            'selector' => $indexButtonSelector
                        ],
                    ],
                ];

                
                $controls['x_facet_index_boxshadow'] = [
                    'tab'    => 'content',
                    'group'  => 'x_facet_az_index',
                    'label'  => esc_html__( 'Box Shadow', 'extras' ),
                    'type'   => 'box-shadow',
                    'css'    => [
                        [
                            'property' => 'box-shadow',
                            'selector' => $indexButtonSelector
                        ],
                    ],
                ];
            
                $controls['x_facet_index_padding'] = [
                    'tab'   => 'content',
                    'group' => 'x_facet_az_index',
                    'label' => esc_html__( 'Padding', 'extras' ),
                    'type'  => 'dimensions',
                    'css'   => [
                        [
                            'property' => 'padding',
                            'selector' => $indexButtonSelector
                        ],
                    ],
                ];

                 $controls['x_facet_index_disabled_opacity'] = [
                    'tab'   => 'content',
                    'group' => 'x_facet_az_index',
                    'label' => esc_html__( 'Disabled opacity', 'extras' ),
                    'type'  => 'number',
                    'css'   => [
                        [
                            'property' => 'opacity',
                            'selector' => '.wpgb-facet .wpgb-az-index-facet [class^="wpgb"][role="button"][tabindex="-1"]'
                        ],
                    ],
                    'placeholder' => '0.4'
                ];

                
				

			return $controls;
		}
		


		
		

}