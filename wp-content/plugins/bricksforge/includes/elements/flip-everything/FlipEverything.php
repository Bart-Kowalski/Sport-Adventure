<?php

namespace Bricks;

if (!defined('ABSPATH'))
    exit;

class Brf_Flip_Everything extends \Bricks\Element
{

    public $category = 'bricksforge';
    public $name = 'brf-flip';
    public $icon = 'ti ti-home';
    public $css_selector = 'brf-flip';
    public $scripts = ['brfFlipEverything'];
    public $nestable = true;

    public function get_label()
    {
        return esc_html__("Flip Everything", 'bricksforge');
    }

    public function enqueue_scripts()
    {
        wp_enqueue_script('bricksforge-gsap');
        wp_enqueue_script('bricksforge-elements');
    }

    public function set_control_groups()
    {
        $this->control_groups['brf_flip_general_group'] = [
            'title' => esc_html__('General', 'bricksforge'),
            'tab'   => 'content',
        ];
        $this->control_groups['brf_flip_animation_group'] = [
            'title' => esc_html__('Animation', 'bricksforge'),
            'tab'   => 'content',
        ];
        $this->control_groups['brf_flip_developer_group'] = [
            'title' => esc_html__('Developer', 'bricksforge'),
            'tab'   => 'content',
        ];
    }

    public function set_controls()
    {
        $this->controls['width'] = [
            'tab'    => 'content',
            'group'  => 'brf_flip_general_group',
            'label'  => esc_html__('Global Width', 'bricksforge'),
            'type'   => 'number',
            'units'  => true,
            'min'    => 0,
            'step'   => 1,
            'inline' => true,
            'css'    => [
                [
                    'selector' => '.brf-flip-wrapper',
                    'property' => 'width',
                ],
                [
                    'selector' => '.brf-flip-front',
                    'property' => 'width',
                ],
                [
                    'selector' => '.brf-flip-back',
                    'property' => 'width',
                ],
            ],
        ];
        $this->controls['height'] = [
            'tab'    => 'content',
            'group'  => 'brf_flip_general_group',
            'label'  => esc_html__('Global Height', 'bricksforge'),
            'type'   => 'number',
            'units'  => true,
            'min'    => 0,
            'step'   => 1,
            'inline' => true,
            'css'    => [
                [
                    'selector' => '.brf-flip-wrapper',
                    'property' => 'height',
                ],
                [
                    'selector' => '.brf-flip-front',
                    'property' => 'height',
                ],
                [
                    'selector' => '.brf-flip-back',
                    'property' => 'height',
                ],
            ],
        ];
        $this->controls['brf_flip_animation_type'] = [
            'tab'     => 'content',
            'group'   => 'brf_flip_animation_group',
            'label'   => esc_html__('Animation Type', 'bricksforge'),
            'type'    => 'select',
            'options' => [
                'flip'          => 'Flip Vertical',
                'flip-reverse'  => 'Flip Vertical Reverse',
                'flip-horizontal' => 'Flip Horizontal',
                'flip-horizontal-reverse' => 'Flip Horizontal Reverse',
                'fade'          => 'Fade',
                'fadeShrink'    => 'Fade Shrink',
                'fadeUp'        => 'Fade Up',
                'overlay'       => 'Overlay',
                'overlayShrink' => 'Overlay Shrink'
            ],
            'default' => 'flip',
        ];
        $this->controls['brf_flip_animation_trigger'] = [
            'tab'     => 'content',
            'group'   => 'brf_flip_animation_group',
            'label'   => esc_html__('Animation Trigger', 'bricksforge'),
            'type'    => 'select',
            'options' => [
                'hover' => 'Hover',
                'click' => 'Click',
            ],
            'default' => 'hover',
        ];
        $this->controls['brf_flip_animation_duration'] = [
            'tab'     => 'content',
            'group'   => 'brf_flip_animation_group',
            'label'   => esc_html__('Duration (s)', 'bricksforge'),
            'type'    => 'number',
            'default' => 1,
        ];
        $this->controls['brf_flip_animation_delay'] = [
            'tab'     => 'content',
            'group'   => 'brf_flip_animation_group',
            'label'   => esc_html__('Delay (s)', 'bricksforge'),
            'type'    => 'number',
            'default' => 0,
        ];
        $this->controls['brf_flip_dev_onstart'] = [
            'tab'         => 'content',
            'group'       => 'brf_flip_developer_group',
            'label'       => esc_html__('Event: On Start', 'bricksforge'),
            'type'        => 'code',
            'mode'        => 'javascript',
            'description' => 'This JavaScript runs when the element has started to animate. Use Double quotation marks here.'
        ];
        $this->controls['brf_flip_dev_oncomplete'] = [
            'tab'         => 'content',
            'group'       => 'brf_flip_developer_group',
            'label'       => esc_html__('Event: On Complete', 'bricksforge'),
            'type'        => 'code',
            'mode'        => 'javascript',
            'description' => 'This JavaScript runs when the element has reached the flipped state. Use Double quotation marks here.'
        ];
        $this->controls['brf_flip_dev_onreversecomplete'] = [
            'tab'         => 'content',
            'group'       => 'brf_flip_developer_group',
            'label'       => esc_html__('Event: On Reverse Complete', 'bricksforge'),
            'type'        => 'code',
            'mode'        => 'javascript',
            'description' => 'This JavaScript runs when the element has reversed the flipped state. Use Double quotation marks here.'
        ];
    }

    public function get_nestable_children()
    {
        return [
            [
                'name'     => 'block',
                'label'    => esc_html__('Front', 'bricksforge'),
                'settings' => [
                    '_background'     => [
                        'color' => [
                            'hex' => '#141414',
                        ],
                    ],
                    '_padding'        => [
                        'top'    => '45',
                        'right'  => '45',
                        'bottom' => '46',
                        'left'   => '45',
                    ],
                    '_border'         => [
                        'radius' => [
                            'top'    => '5',
                            'right'  => '5',
                            'bottom' => '5',
                            'left'   => '5',
                        ],
                    ],
                    '_alignItems'     => 'center',
                    '_justifyContent' => 'space-around',
                    '_hidden'         => [
                        '_cssClasses' => 'brf-flip-box brf-flip-front',
                    ],
                ],
                'children' => [
                    [
                        'name'     => 'icon',
                        'label'    => esc_html__('Icon', 'bricksforge'),
                        'settings' => [
                            'icon'      => [
                                'library' => 'ionicons',
                                'icon'    => 'ion-ios-leaf',
                            ],
                            '_margin'   => [
                                'bottom' => '49',
                                'top'    => '29',
                            ],
                            'iconColor' => [
                                'hex' => '#ffeb3b',
                            ],
                            'iconSize'  => '45px'
                        ],
                    ],
                    [
                        'name'     => 'div',
                        'label'    => esc_html__('Text', 'bricksforge'),
                        'children' => [
                            [
                                'name'     => 'heading',
                                'label'    => 'Heading',
                                'settings' => [
                                    'text'        => 'I am a heading',
                                    '_typography' => [
                                        'color'      => [
                                            'hex' => '#f5f5f5',
                                        ],
                                        'text-align' => 'center',
                                    ],
                                    '_margin'     => [
                                        'bottom' => '7',
                                    ],
                                    'tag'         => 'h4',
                                ],
                            ],
                            [
                                'name'     => 'text',
                                'label'    => 'Body',
                                'settings' => [
                                    'text'        => '<p>Here goes your text ... Select any part of your text to access the formatting toolbar.</p>',
                                    '_typography' => [
                                        'color'      => [
                                            'hex' => '#f5f5f5',
                                        ],
                                        'text-align' => 'center',
                                    ],
                                    '_margin'     => [
                                        'bottom' => '26',
                                    ],
                                ],
                            ]
                        ]
                    ],
                ]
            ],
            [
                'name'     => 'block',
                'label'    => esc_html__('Back', 'bricksforge'),
                'settings' => [
                    '_background'     => [
                        'color' => [
                            'hex' => '#212121',
                        ],
                    ],
                    '_padding'        => [
                        'top'    => '45',
                        'right'  => '45',
                        'bottom' => '46',
                        'left'   => '45',
                    ],
                    '_border'         => [
                        'radius' => [
                            'top'    => '5',
                            'right'  => '5',
                            'bottom' => '5',
                            'left'   => '5',
                        ],
                    ],
                    '_alignItems'     => 'center',
                    '_justifyContent' => 'space-around',
                    '_hidden'         => [
                        '_cssClasses' => 'brf-flip-box brf-flip-back',
                    ],
                ],
                'children' => [
                    [
                        'name'     => 'icon',
                        'label'    => esc_html__('Icon', 'bricksforge'),
                        'settings' => [
                            'icon'      => [
                                'library' => 'ionicons',
                                'icon'    => 'ion-ios-leaf',
                            ],
                            '_margin'   => [
                                'bottom' => '49',
                                'top'    => '29',
                            ],
                            'iconColor' => [
                                'hex' => '#ffeb3b',
                            ],
                            'iconSize'  => '45px'
                        ],
                    ],
                    [
                        'name'     => 'div',
                        'label'    => esc_html__('Text', 'bricksforge'),
                        'children' => [
                            [
                                'name'     => 'heading',
                                'label'    => 'Heading',
                                'settings' => [
                                    'text'        => 'I am a heading',
                                    '_typography' => [
                                        'color'      => [
                                            'hex' => '#f5f5f5',
                                        ],
                                        'text-align' => 'center',
                                    ],
                                    '_margin'     => [
                                        'bottom' => '7',
                                    ],
                                    'tag'         => 'h4',
                                ],
                            ],
                            [
                                'name'     => 'text',
                                'label'    => 'Body',
                                'settings' => [
                                    'text'        => '<p>Here goes your text ... Select any part of your text to access the formatting toolbar.</p>',
                                    '_typography' => [
                                        'color'      => [
                                            'hex' => '#f5f5f5',
                                        ],
                                        'text-align' => 'center',
                                    ],
                                    '_margin'     => [
                                        'bottom' => '26',
                                    ],
                                ],
                            ]
                        ]
                    ],
                ]
            ]
        ];
    }

    public function render()
    {
        $settings = $this->settings;

        $output = "<div {$this->render_attributes('_root')}>";

        $type = isset($settings['brf_flip_animation_type']) ? $settings['brf_flip_animation_type'] : 'flip';
        $trigger = isset($settings['brf_flip_animation_trigger']) ? $settings['brf_flip_animation_trigger'] : 'hover';
        $duration = isset($settings['brf_flip_animation_duration']) ? $settings['brf_flip_animation_duration'] : 1;
        $delay = isset($settings['brf_flip_animation_delay']) ? $settings['brf_flip_animation_delay'] : 0;
        $on_complete = isset($settings['brf_flip_dev_oncomplete']) ? $settings['brf_flip_dev_oncomplete'] : false;
        $on_start = isset($settings['brf_flip_dev_onstart']) ? $settings['brf_flip_dev_onstart'] : false;
        $on_reversecomplete = isset($settings['brf_flip_dev_onreversecomplete']) ? $settings['brf_flip_dev_onreversecomplete'] : false;

        $output .= "<div class='brf-flip-wrapper' data-type='$type' data-trigger='$trigger' data-duration='$duration' data-delay='$delay' data-oncomplete='$on_complete' data-onstart='$on_start' data-onreversecomplete='$on_reversecomplete'>";
        $output .= Frontend::render_children($this);
        $output .= "</div>";

        $output .= '</div>';

        echo $output;
    }
}
