<?php
namespace Bricks;

if (!defined('ABSPATH'))
    exit;

class Brf_Before_And_After extends Element
{
    public $category = 'bricksforge';
    public $name = 'brf-before-and-after';
    public $icon = 'ti-image';
    public $css_selector = '.brf-before-and-after';
    public $scripts = ['brfBeforeAndAfter'];
    public $nestable = true;

    public function get_label()
    {
        return esc_html__('Before And After', 'bricksforge');
    }

    public function set_controls()
    {
        $this->controls['width'] = [
            'tab'     => 'content',
            'label'   => esc_html__('Width', 'bricksforge'),
            'type'    => 'number',
            'units'   => true,
            'min'     => 0,
            'step'    => 1,
            'inline'  => true,
            'css'     => [
                [
                    'selector' => '.brf-ba-wrapper',
                    'property' => 'width',
                ],
                [
                    'selector' => '.brf-original',
                    'property' => 'width',
                ],
            ],
            'default' => '800px',
        ];
        $this->controls['height'] = [
            'tab'     => 'content',
            'label'   => esc_html__('Height', 'bricksforge'),
            'type'    => 'number',
            'units'   => true,
            'inline'  => true,
            'default' => '500px',
            'css'     => [
                [
                    'selector' => '.brf-ba-wrapper',
                    'property' => 'height',
                ],
                [
                    'selector' => '.brf-original',
                    'property' => 'height',
                ],
            ],
        ];
        $this->controls['keepImageRatio'] = [
            'tab'     => 'content',
            'label'   => esc_html__('Keep Image Ratio', 'bricksforge'),
            'type'    => 'checkbox',
            'inline'  => true,
            'small'   => true,
            'default' => false,
            'css'     => [
                [
                    'selector' => '.brf-original',
                    'property' => 'height',
                    'value'    => 'auto!important'
                ],
            ],
        ];
        $this->controls['color'] = [
            'tab'    => 'content',
            'label'  => esc_html__('Drag Layer Color', 'bricksforge'),
            'type'   => 'color',
            'inline' => true,
            'css'    => [
                [
                    'selector' => '.brf-ba-handle::after',
                    'property' => 'background-color',
                ],
            ],

        ];
        $this->controls['svg'] = [
            'tab'   => 'content',
            'label' => esc_html__('Drag Layer SVG', 'bricksforge'),
            'type'  => 'svg',
        ];
        $this->controls['brf-render'] = [
            'tab'    => 'content',
            'type'   => 'apply',
            'reload' => false,
            'label'  => esc_html__('Render', 'bricksforge'),
        ];
    }

    public function enqueue_scripts()
    {
        wp_enqueue_style('brf-before-and-after', BRICKSFORGE_ELEMENTS_ROOT_PATH . '/before-and-after/css/style.css');
        wp_enqueue_script('bricksforge-elements');
    }

    public function get_nestable_children()
    {
        return [
            [
                "name"     => 'image',
                "label"    => 'Before',
                'settings' => [
                    '_hidden'    => [
                        '_cssClasses' => 'brf-original',
                    ],
                    '_objectFit' => 'cover',
                    'caption'    => 'none',
                    'image'      => [
                        'filename' => 'placeholder-grey.jpg',
                        'size'     => 'full',
                        'full'     => BRICKSFORGE_ASSETS . '/img/placeholder-grey.jpg',
                        'url'      => BRICKSFORGE_ASSETS . '/img/placeholder-grey.jpg',
                    ],
                ],
            ],
            [
                'name'     => 'div',
                'label'    => 'Div',
                'settings' => [
                    '_hidden' => [
                        '_cssClasses' => 'brf-ba-resize',
                    ],
                    'width'   => '100%'
                ],
                'children' => [
                    [
                        "name"     => 'image',
                        "label"    => 'After',
                        'settings' => [
                            '_hidden'    => [
                                '_cssClasses' => 'brf-original',
                            ],
                            '_objectFit' => 'cover',
                            'caption'    => 'none',
                            'image'      => [
                                'filename' => 'placeholder.jpg',
                                'size'     => 'full',
                                'full'     => BRICKSFORGE_ASSETS . '/img/placeholder.jpg',
                                'url'      => BRICKSFORGE_ASSETS . '/img/placeholder.jpg',
                            ],
                        ],
                    ]
                ]
            ]
        ];
    }

    public function render()
    {
        $settings = $this->settings;
        $id = "brf-ba-" . uniqid();
        $color = isset($settings['color']) ? $settings['color']['hex'] : '#ffd64f';
        $svg = isset($settings['svg']) ? $settings['svg'] : false;
        $width = isset($settings['width']) ? $settings['width'] : 800;
        $height = isset($settings['height']) ? $settings['height'] : 500;
        $keep_image_ratio = isset($settings['keepImageRatio']) ? $settings['keepImageRatio'] : 0;

        $root_classes[] = 'brf-before-and-after';
        $this->set_attribute('_root', 'class', $root_classes);

        $output = "<div {$this->render_attributes('_root')}>";
        $output .= "<style>";
        if ($svg !== false) {
            $output .= "#$id .brf-ba-handle:after {content: ''; display:block!important;background:url(" . $svg['url'] . ") center / contain no-repeat!important; border: none!important; opacity: 1!important;box-shadow: none!important;}";
        }
        $output .= "</style>";
        $output .= "<div class='brf-ba-wrapper' id=" . $id . " data-keep-image-ratio=" . $keep_image_ratio . ">";
        $output .= Frontend::render_children($this);
        $output .= '<span class="brf-ba-handle"></span>';
        $output .= '</div>';
        $output .= '</div>';

        echo $output;
    }
}