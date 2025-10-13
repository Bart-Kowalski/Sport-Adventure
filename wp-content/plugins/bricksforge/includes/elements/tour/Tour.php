<?php

namespace Bricks;

if (!defined('ABSPATH'))
    exit;

class BrfTour extends \Bricks\Element
{

    public $category = 'bricksforge';
    public $name = 'brf-tour';
    public $icon = 'ti-layout-cta-right';
    public $scripts = [];
    public $nestable = false;

    public function get_label()
    {
        return esc_html__("Tour Guide", 'bricksforge');
    }

    public function enqueue_scripts()
    {
        wp_enqueue_script('bricksforge-shepherd');
        wp_enqueue_script('bricksforge-elements');
        wp_enqueue_style('bricksforge-shepherd');
    }

    public function set_control_groups()
    {
        $this->control_groups['general'] = [
            'title' => esc_html__('General', 'bricksforge'),
            'tab'  => 'content',
        ];
        $this->control_groups['steps'] = [
            'title' => esc_html__('Steps', 'bricksforge'),
            'tab'  => 'content',
        ];
        $this->control_groups['style_step'] = [
            'title' => esc_html__('Style: Step', 'bricksforge'),
            'tab'  => 'content',
        ];
        $this->control_groups['style_buttons'] = [
            'title' => esc_html__('Style: Buttons', 'bricksforge'),
            'tab'  => 'content',
        ];
        $this->control_groups['style_overlay'] = [
            'title' => esc_html__('Style: Overlay', 'bricksforge'),
            'tab'  => 'content',
        ];
    }

    public function set_controls()
    {
        $this->controls['activate'] = [
            'tab'     => 'content',
            'label' => esc_html__('Activate the tour', 'bricksforge'),
            'type'    => 'checkbox',
            'default' => 'true'
        ];

        $this->controls['hide_builder'] = [
            'tab'     => 'content',
            'label' => esc_html__('Hide in builder', 'bricksforge'),
            'type'    => 'checkbox',
        ];

        // General: Scroll To Element
        $this->controls['scrollTo'] = [
            'tab'     => 'content',
            'group' => 'general',
            'label' => esc_html__('Scroll To Element', 'bricksforge'),
            'type'    => 'checkbox',
            'default' => 'true'
        ];

        // General: useModalOverlay
        $this->controls['useModalOverlay'] = [
            'tab'     => 'content',
            'group' => 'general',
            'label' => esc_html__('Use Modal Overlay', 'bricksforge'),
            'type'    => 'checkbox',
            'default' => 'true'
        ];

        // Trigger (On Page Load, On Click)
        $this->controls['trigger'] = [
            'tab'     => 'content',
            'group' => 'general',
            'label' => esc_html__('Trigger', 'bricksforge'),
            'type'    => 'select',
            'options' => [
                'load' => esc_html__('On Page Load', 'bricksforge'),
                'click' => esc_html__('On Click', 'bricksforge'),
            ],
            'default' => 'load'
        ];

        // If Page Load, we add "Once" as option
        $this->controls['triggerOnce'] = [
            'tab'     => 'content',
            'group' => 'general',
            'label' => esc_html__('Trigger Once', 'bricksforge'),
            'type'    => 'checkbox',
            'required' => [["trigger", "=", "load"]]
        ];

        // If click, we need a selector
        $this->controls['triggerSelector'] = [
            'tab'     => 'content',
            'group' => 'general',
            'label' => esc_html__('Trigger Selector', 'bricksforge'),
            'type'    => 'text',
            'placeholder' => '.your-selector',
            'required' => [["trigger", "=", "click"]]
        ];

        // Steps (Repeater)
        $this->controls['steps'] = [
            'tab'     => 'content',
            'group' => 'steps',
            'label' => esc_html__('Steps', 'bricksforge'),
            'type'    => 'repeater',
            'titleProperty' => 'stepId',
            'fields'  => [
                'stepId' => [
                    'label' => esc_html__('ID (Optional)', 'bricksforge'),
                    'type'  => 'text',
                    'value' => Helpers::generate_random_id(false)
                ],
                'title' => [
                    'label' => esc_html__('Title', 'bricksforge'),
                    'type'  => 'text',
                ],
                'text' => [
                    'label' => esc_html__('Text', 'bricksforge'),
                    'type'  => 'editor',
                ],
                'element' => [
                    'label' => esc_html__('Element Selector', 'bricksforge'),
                    'type'  => 'text',
                    'placeholder' => '.my-element'
                ],
                'position' => [
                    'label' => esc_html__('Position', 'bricksforge'),
                    'type'  => 'select',
                    'options' => [
                        'top' => esc_html__('Top', 'bricksforge'),
                        'top-start' => esc_html__('Top Start', 'bricksforge'),
                        'top-end' => esc_html__('Top End', 'bricksforge'),
                        'bottom' => esc_html__('Bottom', 'bricksforge'),
                        'bottom-start' => esc_html__('Bottom Start', 'bricksforge'),
                        'bottom-end' => esc_html__('Bottom End', 'bricksforge'),
                        'right' => esc_html__('Right', 'bricksforge'),
                        'right-start' => esc_html__('Right Start', 'bricksforge'),
                        'right-end' => esc_html__('Right End', 'bricksforge'),
                        'left' => esc_html__('Left', 'bricksforge'),
                        'left-start' => esc_html__('Left Start', 'bricksforge'),
                        'left-end' => esc_html__('Left End', 'bricksforge'),
                    ],
                ],
                'showCloseIcon' => [
                    'label' => esc_html__('Show Close Icon', 'bricksforge'),
                    'type'  => 'checkbox',
                ],
                'overlayPadding' => [
                    'label' => esc_html__('Overlay Padding', 'bricksforge'),
                    'type'  => 'number',
                    'default' => '0'
                ],

                // Buttons (Repeater)
                'buttons' => [
                    'label' => esc_html__('Buttons', 'bricksforge'),
                    'type'  => 'repeater',
                    'titleProperty' => 'action',
                    'fields'  => [
                        'action' => [
                            'label' => esc_html__('Action', 'bricksforge'),
                            'type'  => 'select',
                            'options' => [
                                'next' => esc_html__('Next', 'bricksforge'),
                                'back' => esc_html__('Back', 'bricksforge'),
                                'complete' => esc_html__('Complete', 'bricksforge'),
                                'cancel' => esc_html__('Cancel', 'bricksforge'),
                            ],
                        ],
                        'text' => [
                            'label' => esc_html__('Text', 'bricksforge'),
                            'type'  => 'text',
                        ],
                    ],
                    'default' => [
                        [
                            'action' => 'back',
                            'text' => esc_html__('Back', 'bricksforge')
                        ],
                        [
                            'action' => 'next',
                            'text' => esc_html__('Next', 'bricksforge')
                        ],
                    ]
                ],
            ],
            'default' => [
                [
                    'stepId' => '',
                    'title' => esc_html__('Step 1', 'bricksforge'),
                    'text' => esc_html__('Describe your step!', 'bricksforge'),
                    'element' => '.your-selector',
                    'position' => 'top',
                    'buttons' => [
                        [
                            'action' => 'back',
                            'text' => esc_html__('Back', 'bricksforge')
                        ],
                        [
                            'action' => 'next',
                            'text' => esc_html__('Next', 'bricksforge')
                        ],
                    ]
                ]
            ]
        ];

        // Style: Step
        $this->controls['step_background'] = [
            'tab'     => 'content',
            'group' => 'style_step',
            'label' => esc_html__('Background Color', 'bricksforge'),
            'type'    => 'color',
            'default' => '#fff',
            'css' => [
                [
                    'property' => 'background',
                    'selector' => '.shepherd-theme-bricksforge',
                ],
                [
                    'property' => 'background',
                    'selector' => '.shepherd-theme-bricksforge .shepherd-arrow:before',
                ],
                [
                    'property' => 'background',
                    'selector' => '.brf-tour-holder',
                ]
            ]
        ];
        $this->controls['step_padding'] = [
            'tab'     => 'content',
            'group' => 'style_step',
            'label' => esc_html__('Padding', 'bricksforge'),
            'type'    => 'dimensions',
            'default' => [
                'top' => '15px',
                'right' => '15px',
                'bottom' => '15px',
                'left' => '15px'
            ],
            'css' => [
                [
                    'property' => 'padding',
                    'selector' => '.shepherd-theme-bricksforge',
                ],
                [
                    'property' => 'padding',
                    'selector' => '.brf-tour-holder',
                ]
            ]
        ];

        $this->controls['step_border'] = [
            'tab'     => 'content',
            'group' => 'style_step',
            'label' => esc_html__('Border', 'bricksforge'),
            'type'    => 'border',
            'css' => [
                [
                    'property' => 'border',
                    'selector' => '.shepherd-theme-bricksforge',
                ],
                [
                    'property' => 'border',
                    'selector' => '.brf-tour-holder',
                ]
            ]
        ];
        $this->controls['step_typography'] = [
            'tab'     => 'content',
            'group' => 'style_step',
            'label' => esc_html__('Text Typography', 'bricksforge'),
            'type'    => 'typography',
            'css' => [
                [
                    'property' => 'typography',
                    'selector' => '.shepherd-theme-bricksforge .shepherd-text',
                ],
                [
                    'property' => 'typography',
                    'selector' => '.brf-tour-holder .brf-tour-holder__text',
                ]
            ]
        ];
        $this->controls['title_typography'] = [
            'tab'     => 'content',
            'group' => 'style_step',
            'label' => esc_html__('Title Typography', 'bricksforge'),
            'type'    => 'typography',
            'css' => [
                [
                    'property' => 'typography',
                    'selector' => '.shepherd-theme-bricksforge .shepherd-title',
                ],
                [
                    'property' => 'typography',
                    'selector' => '.brf-tour-holder .brf-tour-holder__title',
                ]
            ]
        ];

        // Style: Buttons
        $this->controls['button_background'] = [
            'tab'     => 'content',
            'group' => 'style_buttons',
            'label' => esc_html__('Background Color', 'bricksforge'),
            'type'    => 'color',
            'default' => '#222',
            'css' => [
                [
                    'property' => 'background',
                    'selector' => '.shepherd-theme-bricksforge .shepherd-footer button',
                ],
                [
                    'property' => 'background',
                    'selector' => '.brf-tour-holder .brf-tour-holder__buttons',
                ]
            ]
        ];

        $this->controls['button_background-hover'] = [
            'tab'     => 'content',
            'group' => 'style_buttons',
            'label' => esc_html__('Background Color (Hover)', 'bricksforge'),
            'type'    => 'color',
            'default' => '#555',
            'css' => [
                [
                    'property' => 'background',
                    'selector' => '.shepherd-theme-bricksforge .shepherd-footer button:hover',
                ],
                [
                    'property' => 'background',
                    'selector' => '.brf-tour-holder .brf-tour-holder__buttons-hover',
                ]
            ]
        ];

        $this->controls['button_typography'] = [
            'tab'     => 'content',
            'group' => 'style_buttons',
            'label' => esc_html__('Typography', 'bricksforge'),
            'type'    => 'typography',
            'default' => [
                'color' => [
                    'hex' => '#FFFFFF',
                ]
            ],
            'css' => [
                [
                    'property' => 'typography',
                    'selector' => '.shepherd-theme-bricksforge .shepherd-footer button',
                ],
                [
                    'property' => 'typography',
                    'selector' => '.brf-tour-holder .brf-tour-holder__buttons',
                ]
            ]
        ];

        $this->controls['button_typography-hover'] = [
            'tab'     => 'content',
            'group' => 'style_buttons',
            'label' => esc_html__('Typography (Hover)', 'bricksforge'),
            'type'    => 'typography',
            'default' => [
                'color' => [
                    'hex' => '#FFFFFF',
                ]
            ],
            'css' => [
                [
                    'property' => 'typography',
                    'selector' => '.shepherd-theme-bricksforge .shepherd-footer button:hover',
                ],
                [
                    'property' => 'typography',
                    'selector' => '.brf-tour-holder .brf-tour-holder__buttons-hover',
                ]
            ]
        ];

        $this->controls['button_padding'] = [
            'tab'     => 'content',
            'group' => 'style_buttons',
            'label' => esc_html__('Padding', 'bricksforge'),
            'type'    => 'dimensions',
            'default' => [
                'top' => '10px',
                'right' => '20px',
                'bottom' => '10px',
                'left' => '20px'
            ],
            'css' => [
                [
                    'property' => 'padding',
                    'selector' => '.shepherd-theme-bricksforge .shepherd-footer button',
                ],
                [
                    'property' => 'padding',
                    'selector' => '.brf-tour-holder .brf-tour-holder__buttons',
                ]
            ]
        ];

        $this->controls['button_margin'] = [
            'tab'     => 'content',
            'group' => 'style_buttons',
            'label' => esc_html__('Margin', 'bricksforge'),
            'type'    => 'dimensions',
            'default' => [
                'top' => '0px',
                'right' => '5px',
                'bottom' => '0px',
                'left' => '0px'
            ],
            'css' => [
                [
                    'property' => 'margin',
                    'selector' => '.shepherd-theme-bricksforge .shepherd-footer button',
                ],
                [
                    'property' => 'margin',
                    'selector' => '.brf-tour-holder .brf-tour-holder__buttons',
                ]
            ]
        ];

        $this->controls['button_border'] = [
            'tab'     => 'content',
            'group' => 'style_buttons',
            'label' => esc_html__('Border', 'bricksforge'),
            'type'    => 'border',
            'css' => [
                [
                    'property' => 'border',
                    'selector' => '.shepherd-theme-bricksforge .shepherd-footer button',
                ],
                [
                    'property' => 'border',
                    'selector' => '.brf-tour-holder .brf-tour-holder__buttons',
                ]
            ]
        ];

        // Style: Overlay
        $this->controls['overlay_background'] = [
            'tab'     => 'content',
            'group' => 'style_overlay',
            'label' => esc_html__('Background Color', 'bricksforge'),
            'type'    => 'color',
            'css' => [
                [
                    'property' => 'fill',
                    'selector' => '.shepherd-modal-overlay-container',
                ],
                [
                    'property' => 'fill',
                    'selector' => '.brf-tour-holder__overlay',
                ]
            ]
        ];
    }


    public function render()
    {
        $settings = $this->settings;

        $root_classes[] = 'brf-tour';

        $this->set_attribute('_root', 'class', $root_classes);

        $tour_data = [];
        $styles = [];

        if (isset($settings['activate']) && $settings['activate'] && $settings['steps']) {
            // We collect the tour data
            $tour_data["steps"] = $settings['steps'];
            $tour_data["options"] = [];

            // Options
            $tour_data["options"]["scrollTo"] = isset($settings['scrollTo']) ? $settings['scrollTo'] : false;
            $tour_data["options"]["useModalOverlay"] = isset($settings['useModalOverlay']) ? $settings['useModalOverlay'] : false;
            $tour_data["options"]["trigger"] = isset($settings['trigger']) ? $settings['trigger'] : 'load';
            $tour_data["options"]["triggerOnce"] = isset($settings['triggerOnce']) ? $settings['triggerOnce'] : false;

            // ID
            $tour_data["options"]["id"] = $this->id;

            if (isset($settings['triggerSelector']) && $settings['triggerSelector']) {
                $tour_data["options"]["triggerSelector"] = $settings['triggerSelector'];
            }

            $this->set_attribute('_root', 'data-brf-tour', json_encode($tour_data));
        }

        $hide_in_builder = isset($settings['hide_builder']) && $settings['hide_builder'] ? true : false;

        $output = "<div {$this->render_attributes('_root')}>";

        if ((bricks_is_builder() || bricks_is_builder_call()) && !$hide_in_builder) {
            $output .= '<svg style="position: absolute" class="shepherd-modal-is-visible shepherd-modal-overlay-container"><path d="M2560,832H0V0H2560V832ZM1665.0859375,0.4296875a0,0,0,0,0-0,0V34.5703125a0,0,0,0,0,0,0H1799.7421875a0,0,0,0,0,0-0V0.4296875a0,0,0,0,0-0-0Z"></path></svg>';
        }

        // We render styles and output css variables
        if (!empty($styles)) {
            $output .= "<style>" . implode(' ', $styles) . "</style>";
        }

        if ((bricks_is_builder() || bricks_is_builder_call()) && !$hide_in_builder) {
            $output .= '<div style="position: relative" aria-describedby="Step 1-description" aria-labelledby="Step 1-label" role="dialog" tabindex="0" class="shepherd-has-title shepherd-element shepherd-theme-bricksforge shepherd-enabled" data-shepherd-step-id="Step 1" data-popper-placement="bottom" style="position: static"><div class="shepherd-arrow" data-popper-arrow="" style="left: 192px;"></div> <div class="shepherd-content"><header class="shepherd-header"><h3 id="Step 1-label" class="shepherd-title">Step Preview</h3> </header> <div class="shepherd-text" id="Step 1-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</div> <footer class="shepherd-footer"><button class=" shepherd-button " tabindex="0">Button</button><button class=" shepherd-button " tabindex="0">Button</button></footer></div></div>';
        } else {
            $output .= "<div style='display:none!important' class='brf-tour-holder'><div class='brf-tour-holder__overlay'></div><h3 class='brf-tour-holder__title'></h3><p class='brf-tour-holder__text'></p><a class='bricks-button brf-tour-holder__buttons'></a><a class='bricks-button brf-tour-holder__buttons-hover'></a></div>";
        }

        $output .= "</div>";

        echo $output;
    }
}
