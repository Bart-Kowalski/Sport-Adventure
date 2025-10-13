<?php

namespace Bricks;

if (!defined('ABSPATH'))
    exit;

class Brf_Table_Of_Contents extends Element
{
    public $category = 'bricksforge';
    public $name = 'brf-toc';
    public $icon = 'ti-list';
    public $nestable = true;

    public function get_label()
    {
        return esc_html__('Table Of Contents', 'bricksforge');
    }

    public function set_control_groups()
    {
        $this->control_groups['brf_toc_general_group'] = [
            'title' => esc_html__('General', 'bricksforge'),
            'tab'   => 'content',
        ];
        $this->control_groups['brf_toc_content_group'] = [
            'title' => esc_html__('Content', 'bricksforge'),
            'tab'   => 'content',
        ];
        $this->control_groups['brf_toc_logic_group'] = [
            'title' => esc_html__('Logic', 'bricksforge'),
            'tab'   => 'content',
        ];
    }

    public function set_controls()
    {
        $this->controls['background'] = [
            'tab'     => 'content',
            'group'   => 'brf_toc_general_group',
            'label'   => esc_html__('Background', 'bricksforge'),
            'type'    => 'color',
            'default' => [
                'hex' => '#EEEEEE',
            ],
            'css'     => [
                [
                    'selector' => '.brf-toc-wrapper',
                    'property' => 'background'
                ]
            ]
        ];
        $this->controls['width'] = [
            'tab'     => 'content',
            'group'   => 'brf_toc_general_group',
            'label'   => esc_html__('Width', 'bricksforge'),
            'type'    => 'number',
            'units'   => true,
            'default' => '100%',
            'css'     => [
                [
                    'selector' => '',
                    'property' => 'width'
                ]
            ]
        ];
        $this->controls['padding'] = [
            'tab'     => 'content',
            'group'   => 'brf_toc_general_group',
            'label'   => esc_html__('Box Padding', 'bricksforge'),
            'type'    => 'dimensions',
            'units'   => true,
            'default' => [
                'top'    => '25px',
                'right'  => '25px',
                'bottom' => '25px',
                'left'   => '25px',
            ],
            'css'     => [
                [
                    'selector' => '.brf-toc-wrapper',
                    'property' => 'padding'
                ]
            ]
        ];
        $this->controls['border'] = [
            'tab'   => 'content',
            'group' => 'brf_toc_general_group',
            'label' => esc_html__('Border', 'bricksforge'),
            'type'  => 'border',
            'units' => true,
            'css'   => [
                [
                    'selector' => '.brf-toc-wrapper',
                    'property' => 'border'
                ]
            ]
        ];

        $this->controls['contentTypography'] = [
            'tab'   => 'content',
            'group' => 'brf_toc_content_group',
            'label' => esc_html__('Content Typography', 'bricksforge'),
            'type'  => 'typography',
            'units' => true,
            'css'   => [
                [
                    'selector' => '.brf-toc-content a',
                    'property' => 'typography'
                ],
                [
                    'selector' => '.brf-toc-content li::marker',
                    'property' => 'typography'
                ],
                [
                    'selector' => '.brf-toc-content-preview a',
                    'property' => 'typography'
                ],
                [
                    'selector' => '.brf-toc-content-preview li::marker',
                    'property' => 'typography'
                ],
            ]
        ];

        $this->controls['contentPadding'] = [
            'tab'   => 'content',
            'group' => 'brf_toc_content_group',
            'label' => esc_html__('Content Padding', 'bricksforge'),
            'type'  => 'dimensions',
            'units' => true,
            'css'   => [
                [
                    'selector' => '.brf-toc-content',
                    'property' => 'padding'
                ],
                [
                    'selector' => '.brf-toc-content-preview',
                    'property' => 'padding'
                ]
            ]
        ];
        $this->controls['contentMargin'] = [
            'tab'   => 'content',
            'group' => 'brf_toc_content_group',
            'label' => esc_html__('Content Margin', 'bricksforge'),
            'type'  => 'dimensions',
            'units' => true,
            'css'   => [
                [
                    'selector' => '.brf-toc-content',
                    'property' => 'margin'
                ],
                [
                    'selector' => '.brf-toc-content-preview',
                    'property' => 'margin'
                ]
            ]
        ];
        $this->controls['resetListPadding'] = [
            'tab'   => 'content',
            'group' => 'brf_toc_content_group',
            'label' => esc_html__('Reset List Padding', 'bricksforge'),
            'type'  => 'checkbox',
            'css'   => [
                [
                    'selector' => '.brf-toc-content > ul',
                    'property' => 'padding',
                    'value'    => 0
                ],
                [
                    'selector' => '.brf-toc-content-preview > ul',
                    'property' => 'padding',
                    'value'    => 0
                ],
                [
                    'selector' => '.brf-toc-content > ol',
                    'property' => 'padding',
                    'value'    => 0
                ],
                [
                    'selector' => '.brf-toc-content-preview > ol',
                    'property' => 'padding',
                    'value'    => 0
                ]
            ]
        ];
        $this->controls['disableListStyle'] = [
            'tab'   => 'content',
            'group' => 'brf_toc_content_group',
            'label' => esc_html__('Disable List Style', 'bricksforge'),
            'type'  => 'checkbox',
            'css'   => [
                [
                    'selector' => '.brf-toc-content li',
                    'property' => 'list-style',
                    'value'    => 'none'
                ],
                [
                    'selector' => '.brf-toc-content-preview li',
                    'property' => 'list-style',
                    'value'    => 'none'
                ]
            ]
        ];
        $this->controls['listType'] = [
            'required' => ['disableListStyle', '=', false],
            'tab'      => 'content',
            'group'    => 'brf_toc_content_group',
            'label'    => esc_html__('List Type', 'bricksforge'),
            'type'     => 'select',
            'default'  => 'ul',
            'options'  => [
                'ol'   => 'Ordered List',
                'ul'   => 'Unordered List',
                'none' => 'none',
            ],
        ];

        $this->controls['limitID'] = [
            'tab'         => 'content',
            'group'       => 'brf_toc_logic_group',
            'label'       => esc_html__('Limit Detection To Container', 'bricksforge'),
            'description' => esc_html__('Set an ID of an element inside #brx-content. (No spaces. No pound (#) sign). If something is entered here, only within this container will be searched for headlines.', 'bricksforge'),
            'type'        => 'text',
        ];
        $this->controls['topLevel'] = [
            'tab'          => 'content',
            'group'        => 'brf_toc_logic_group',
            'label'        => esc_html__('From Heading', 'bricksforge'),
            'description'  => esc_html__('From which level should headlines be recognized?', 'bricksforge'),
            'type'         => 'select',
            'default'      => 2,
            'placerholder' => '#test',
            'options'      => [
                1 => 'H1',
                2 => 'H2',
                3 => 'H3',
                4 => 'H4',
                5 => 'H5',
                6 => 'H6',
            ],
        ];
        $this->controls['depth'] = [
            'tab'         => 'content',
            'group'       => 'brf_toc_logic_group',
            'label'       => esc_html__('Depth', 'bricksforge'),
            'description' => esc_html__('Example: If "From Heading" was set to "H2", the depth "2" would mean: H2, H3.', 'bricksforge'),
            'type'        => 'select',
            'default'     => 5,
            'options'     => [
                1 => '1',
                2 => '2',
                3 => '3',
                4 => '4',
                5 => '5',
                6 => '6',
            ],
        ];
        $this->controls['emptyText'] = [
            'tab'         => 'content',
            'group'       => 'brf_toc_logic_group',
            'label'       => esc_html__('Empty Text', 'bricksforge'),
            'description' => esc_html__('The text which is showing when empty', 'bricksforge'),
            'default'     => 'Nothing to show',
            'type'        => 'text',
        ];
        $this->controls['hideEmpty'] = [
            'tab'         => 'content',
            'group'       => 'brf_toc_logic_group',
            'label'       => esc_html__('Hide If Empty', 'bricksforge'),
            'description' => esc_html__('Hide the TOC box when there are no headlines on the page', 'bricksforge'),
            'type'        => 'checkbox',
        ];
        $this->controls['excluding-info'] = [
            'tab'     => 'content',
            'group'   => 'brf_toc_logic_group',
            'content' => esc_html__('You can exclude certain headings by adding the attribute "brf-toc-exclude" to the heading.', 'bricksforge'),
            'type'    => 'info',
        ];
    }

    public function get_nestable_children()
    {
        return [
            [
                'name'     => 'heading',
                'label'    => esc_html__('Headline', 'bricksforge'),
                'settings' => [
                    'text'        => 'Table Of Contents',
                    'tag'         => 'h2',
                    '_typography' => [
                        'color' => [
                            'hex' => '#141414',
                        ]
                    ],
                    '_attributes' => [
                        [
                            'name'  => 'brf-toc-exclude',
                            'value' => true
                        ]
                    ]
                ],
            ]
        ];
    }

    public function enqueue_scripts()
    {
        wp_enqueue_script('bricksforge-elements');
    }

    public function render()
    {
        require_once(__DIR__ . '/vendor/autoload.php');

        $settings = $this->settings;
        $list_type = isset($settings['listType']) ? $settings['listType'] : 'ul';

        $root_classes[] = 'brf-toc';
        $this->set_attribute('_root', 'class', $root_classes);

        // Render preview in canvas
        echo "<div {$this->render_attributes('_root')}>";
        echo '<div id="brf-toc" class="brf-toc-wrapper">';

        echo Frontend::render_children($this);

        if (bricks_is_builder() || bricks_is_rest_call()) {
            echo '<div class="brf-toc-content-preview">';
            echo $list_type == 'ul' ? '<ul>' : '<ol>';
            echo $list_type == 'ul' ? '<li> <a>I am a heading</a> <ul> <li> <a>I am a heading</a> <ul> <li> <a>I am a heading</a> </li> </ul> </li> </ul> </li> <li> <a>I am a heading</a> <ul> <li> <a>I am a heading</a> </li> </ul> </li>' : '<li> <a>I am a heading</a> <ol> <li> <a>I am a heading</a> <ol> <li> <a>I am a heading</a> </li> </ol> </li> </ol> </li> <li> <a>I am a heading</a> <ol> <li> <a>I am a heading</a> </li> </ol> </li>';
            echo $list_type == 'ul' ? '</ul>' : '</ol>';
            echo '</div>';
        }

        echo '</div>';
        echo '</div>';

        add_filter('bricks/frontend/render_data', [$this, 'fix_markup'], 10, 3);
    }

    public function fix_markup($content, $post, $area)
    {
        // @since 0.9.5
        if ($area == 'header' || $area == 'popup' || $area == 'footer') {
            return $content;
        }

        $settings = $this->settings;
        $hide_empty = isset($settings['hideEmpty']) ? $settings['hideEmpty'] : false;
        $empty_text = isset($settings['emptyText']) ? $settings['emptyText'] : 'Nothing to show';

        if (!isset($settings) || !$settings) {
            return $content;
        }

        if (!class_exists('\TOC\MarkupFixer') || !class_exists('\TOC\TocGenerator')) {
            return $content;
        }

        $list_type = isset($settings['listType']) ? $settings['listType'] : 'ul';
        $top_level = isset($settings['topLevel']) ? $settings['topLevel'] : 2;
        $depth = isset($settings['depth']) ? $settings['depth'] : 5;
        $limitId = isset($settings['limitID']) ? $settings['limitID'] : null;

        $markup_fixer = new \TOC\MarkupFixer();
        $toc_generator = new \TOC\TocGenerator();

        $final_content = $markup_fixer->fix($content, $top_level, $depth);

        $doc = new \DOMDocument();

        libxml_use_internal_errors(true);
        $doc->loadHTML('<?xml encoding="utf-8" ?>' . $final_content);
        libxml_use_internal_errors(false);

        if ($limitId !== null) {
            $doc = $doc->saveHTML($doc->getElementById($limitId));
        } else {
            $doc = $doc->saveHTML();
        }

        $toc_output = $list_type == 'ul' ? $toc_generator->getHtmlMenu($doc, $top_level, $depth, null, false) : $toc_generator->getOrderedHtmlMenu($doc, $top_level, $depth, null);

        if ($toc_output) {
            $final_content .= "<div class='brf-toc-content' style='display: none'>" . $toc_output . "</div>";
        } else {
            if ($hide_empty === true) {
                $final_content .= "<div class='brf-toc-content' data-status='hidden' style='display: none'>" . $empty_text . "</div>";
            } else {
                $final_content .= "<div class='brf-toc-content' style='display: none'>" . $empty_text . "</div>";
            }
        }

        return $final_content;
    }
}
