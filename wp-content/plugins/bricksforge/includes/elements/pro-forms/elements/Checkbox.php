<?php

namespace Bricks;

use \Bricksforge\ProForms\Helper as Helper;
use \Bricksforge\Api\FormsHelper as FormsHelper;

if (!defined('ABSPATH'))
    exit;

class Brf_Pro_Forms_Checkbox extends \Bricks\Element
{

    public $category = 'bricksforge forms';
    public $name = 'brf-pro-forms-field-checkbox';
    public $icon = 'fa-solid fa-square-check';
    public $css_selector = '';
    public $scripts = [];
    public $nestable = false;

    public function get_label()
    {
        return esc_html__("Checkbox", 'bricksforge');
    }

    public function enqueue_scripts()
    {
        wp_enqueue_script('bricksforge-elements');
    }

    public function set_control_groups()
    {
        $this->control_groups['general'] = [
            'title'    => esc_html__('General', 'bricksforge'),
            'tab'      => 'content',
        ];
        $this->control_groups['style'] = [
            'title'    => esc_html__('Style', 'bricksforge'),
            'tab'      => 'content',
        ];
        $this->control_groups['checked_style'] = [
            'title'    => esc_html__('Checked Style', 'bricksforge'),
            'tab'      => 'content',
        ];
        $this->control_groups['accessibility'] = [
            'title'    => esc_html__('Accessibility', 'bricksforge'),
            'tab'      => 'content',
        ];
    }

    public function set_controls()
    {
        $this->controls['info'] = [
            'type'  => 'info',
            'content' => 'This element should be used as a child of the checkbox wrapper.'
        ];

        $this->controls = array_merge($this->controls, Helper::get_loop_controls());
        $this->controls = array_merge($this->controls, Helper::get_default_controls('checkbox'));

        $this->controls = array_merge($this->controls, Helper::get_checked_controls());

        $this->controls['customStyle'] = [
            'tab'   => 'content',
            'group' => 'style',
            'label' => esc_html__('Custom Style', 'bricks'),
            'type'  => 'checkbox',
            'default' => false,
            'rerender' => true,
            'description' => esc_html__('If checked, the checkboxes will be styled with CSS instead of the browser default.', 'bricks'),
            'css' => [
                [
                    'property' => 'appearance',
                    'value' => 'none',
                    'selector' => 'input',
                ],
                [
                    'property' => '-webkit-appearance',
                    'value' => 'none',
                    'selector' => 'input',
                ],
            ],
        ];

        $this->controls['checkboxWidth'] = [
            'tab'   => 'content',
            'group' => 'style',
            'label' => esc_html__('Width', 'bricks'),
            'type'  => 'number',
            'units' => true,
            'unit'  => 'px',
            'min'   => 0,
            'max'   => 100,
            'css'   => [
                [
                    'property' => 'width',
                    'selector' => 'input',
                    'important' => 'true'
                ],
            ],
            'required' => [['customStyle', '=', true]],
        ];

        $this->controls['checkboxHeight'] = [
            'tab'   => 'content',
            'group' => 'style',
            'label' => esc_html__('Height', 'bricks'),
            'type'  => 'number',
            'unit' => 'px',
            'units' => true,
            'css'   => [
                [
                    'property' => 'height',
                    'selector' => '&[data-custom-style="true"] input[type="checkbox"]',
                ],
            ],
            'required' => [['customStyle', '=', true]],
        ];

        $this->controls['checkboxBackground'] = [
            'tab'   => 'content',
            'group' => 'style',
            'label' => esc_html__('Background', 'bricks'),
            'type'  => 'background',
            'css'   => [
                [
                    'property' => 'background',
                    'selector' => '&[data-custom-style="true"] input[type="checkbox"]',
                    'important' => 'true'
                ],
            ],
            'required' => [['customStyle', '=', true]],
        ];

        $this->controls['checkboxBorder'] = [
            'tab'   => 'content',
            'group' => 'style',
            'label' => esc_html__('Border', 'bricks'),
            'type'  => 'border',
            'css'   => [
                [
                    'property' => 'border',
                    'selector' => '&[data-custom-style="true"] input[type="checkbox"]',
                    'important' => 'true'
                ],
            ],
            'required' => [['customStyle', '=', true]],
        ];

        $this->controls['checkboxBoxShadow'] = [
            'tab'   => 'content',
            'group' => 'style',
            'label' => esc_html__('Box shadow', 'bricks'),
            'type'  => 'box-shadow',
            'css'   => [
                [
                    'property' => 'box-shadow',
                    'selector' => '&[data-custom-style="true"] input[type="checkbox"]',
                    'important' => 'true'
                ],
            ],
            'required' => [['customStyle', '=', true]],
        ];

        $this->controls['checkboxPadding'] = [
            'tab'   => 'content',
            'group' => 'style',
            'label' => esc_html__('Padding', 'bricks'),
            'type'  => 'spacing',
            'css'   => [
                [
                    'property' => 'padding',
                    'selector' => '&[data-custom-style="true"] input[type="checkbox"]',
                    'important' => 'true'
                ],
            ],
            'required' => [['customStyle', '=', true]],
        ];

        // Label Typography
        $this->controls['checkboxLabelTypography'] = [
            'tab'   => 'content',
            'group' => 'style',
            'label' => esc_html__('Label Typography', 'bricks'),
            'type'  => 'typography',
            'css'   => [
                [
                    'property' => 'typography',
                    'selector' => '&[data-custom-style="true"] label',
                    'important' => 'true'
                ],
            ],
            'required' => [['customStyle', '=', true]],
        ];

        // Checked

        $this->controls['checkboxCheckedBackground'] = [
            'tab'   => 'content',
            'group' => 'checked_style',
            'label' => esc_html__('Checked Background', 'bricks'),
            'type'  => 'background',
            'css'   => [
                [
                    'property' => 'background-color',
                    'selector' => '&[data-custom-style="true"] input[type="checkbox"]:checked',
                    'important' => 'true'
                ],
            ],
            'required' => [['customStyle', '=', true]],
        ];

        $this->controls['checkboxCheckedBorder'] = [
            'tab'   => 'content',
            'group' => 'checked_style',
            'label' => esc_html__('Checked Border', 'bricks'),
            'type'  => 'border',
            'css'   => [
                [
                    'property' => 'border',
                    'selector' => '&[data-custom-style="true"] input[type="checkbox"]:checked',
                    'important' => 'true'
                ],
            ],
            'required' => [['customStyle', '=', true]],
        ];

        $this->controls['checkboxCheckedBoxShadow'] = [
            'tab'   => 'content',
            'group' => 'checked_style',
            'label' => esc_html__('Checked Box shadow', 'bricks'),
            'type'  => 'box-shadow',
            'css'   => [
                [
                    'property' => 'box-shadow',
                    'selector' => '&[data-custom-style="true"] input[type="checkbox"]:checked',
                    'important' => 'true'
                ],
            ],
            'required' => [['customStyle', '=', true]],
        ];

        // Checked Transform
        $this->controls['checkboxCheckedTransform'] = [
            'tab'   => 'content',
            'group' => 'checked_style',
            'label' => esc_html__('Checked Transform', 'bricks'),
            'type'  => 'transform',
            'css'   => [
                [
                    'property' => 'transform',
                    'selector' => '&[data-custom-style="true"] input[type="checkbox"]:checked',
                    'important' => 'true'
                ],
            ],
            'required' => [['customStyle', '=', true]],
        ];

        // Checked Label Typography
        $this->controls['checkboxCheckedLabelTypography'] = [
            'tab'   => 'content',
            'group' => 'checked_style',
            'label' => esc_html__('Checked Label Typography', 'bricks'),
            'type'  => 'typography',
            'css'   => [
                [
                    'property' => 'typography',
                    'selector' => '&[data-custom-style="true"] input[type="checkbox"]:checked + label',
                    'important' => 'true'
                ],
            ],
            'required' => [['customStyle', '=', true]],
        ];

        // If custom style is not enabled, we show an info on the checked group
        $this->controls['checkedStyleInfo'] = [
            'group' => 'checked_style',
            'type'  => 'info',
            'content' => esc_html__('Custom style must be enabled to customize the checked state.', 'bricks'),
            'required' => [['customStyle', '=', false]],
        ];

        $this->controls = array_merge($this->controls, Helper::get_accessibility_controls());
        $this->controls = array_merge($this->controls, Helper::get_advanced_controls());
    }

    public function render()
    {
        $element = $this->element;
        $settings = $this->settings;
        $parent_settings = Helper::get_nestable_parent_settings($element) ? Helper::get_nestable_parent_settings($element) : false;
        $field_wrapper = Helper::get_parent("brf-pro-forms-field-checkbox-wrapper", $element);

        $id = $this->id ? $this->id : false;

        if ($field_wrapper) {
            $id = isset($field_wrapper['settings']['id']) ? $field_wrapper['settings']['id'] : $field_wrapper['id'];
        }

        $random_id = Helpers::generate_random_id(false);

        $output   = '';
        $query_output = '';

        // Bricks Query Loop
        if (isset($settings['hasLoop'])) {
            // Hold the global element settings to add back 'hasLoop' after the query->render (@since 1.8)
            $global_element = Helpers::get_global_element($element);

            // STEP: Query
            add_filter('bricks/posts/query_vars', [$this, 'maybe_set_preview_query'], 10, 3);

            $query = new \Bricks\Query($element);

            remove_filter('bricks/posts/query_vars', [$this, 'maybe_set_preview_query'], 10, 3);

            // Prevent endless loop
            unset($element['settings']['hasLoop']);

            // Prevent endless loop for global element (@since 1.8)
            if (!empty($global_element['global'])) {
                // Find the global element and unset 'hasLoop'
                Database::$global_data['elements'] = array_map(function ($global_element) use ($element) {
                    if (!empty($element['global']) && $element['global'] === $global_element['global']) {
                        unset($global_element['settings']['hasLoop']);
                    }
                    return $global_element;
                }, Database::$global_data['elements']);
            }

            // STEP: Render loop
            $output = $query->render('Bricks\Frontend::render_element', compact('element'));

            echo $output;

            // Prevent endless loop for global element (@since 1.8)
            if (!empty($global_element['global'])) {
                // Add back global element 'hasLoop' setting after execute render_element
                Database::$global_data['elements'] = array_map(function ($global_element) use ($element) {
                    if (!empty($element['global']) && $element['global'] === $global_element['global']) {
                        $global_element['settings']['hasLoop'] = true;
                    }
                    return $global_element;
                }, Database::$global_data['elements']);
            }

            // STEP: Infinite scroll
            $this->render_query_loop_trail($query);

            // Destroy Query to explicitly remove it from global store
            $query->destroy();

            unset($query);

            return;
        }

        $label = isset($settings['label']) ? $settings['label'] : false;
        $value = isset($settings['value']) ? bricks_render_dynamic_data($settings['value']) : null;
        $required = isset($settings['required']) ? $settings['required'] : false;

        if (!$id && bricks_is_builder()) {
            return $this->render_element_placeholder(
                [
                    'title' => esc_html__('You have to set an ID for your element.', 'bricksforge'),
                ]
            );
        }

        /**
         * Wrapper
         */
        $this->set_attribute('_root', 'data-element-id', $this->id);

        // Custom Css Class
        if (isset($settings['cssClass']) && $settings['cssClass']) {
            $this->set_attribute('field', 'class', $settings['cssClass']);
        }

        // If has custom style, we add a data attribute to the wrapper
        if (isset($settings['customStyle']) && $settings['customStyle']) {
            $this->set_attribute('_root', 'data-custom-style', 'true');
        }

        /**
         * Field
         */
        $this->set_attribute('field', 'id', 'form-field-' . $id . '-' . $random_id);
        $this->set_attribute('field', 'name', 'form-field-' . $id . '[]');

        // We remove html tags from the label
        $clean_label = strip_tags(bricks_render_dynamic_data($label));
        $this->set_attribute('field', 'data-label', $clean_label);

        if (isset($value)) {
            $this->set_attribute('field', 'value', $value);
        }
        if ($required) {
            $this->set_attribute('field', 'required');
        }

        // Calculation Value
        if (isset($settings['calculationValue']) && $settings['calculationValue']) {
            $this->set_attribute('field', 'data-calculation-value', $settings['calculationValue']);
        }

        $checked = FormsHelper::handle_conditionally_selected($settings, $value);

        if (isset($settings['checked']) && $settings['checked']) {
            $checked = 'checked';
        }

        $output .= "<li " . $this->render_attributes("_root") . ">";
        $output .= "<input type='checkbox' " . $this->render_attributes("field") . " " . $checked . " aria-label='" . $label . "' role='checkbox' aria-checked='" . ($checked ? 'true' : 'false') . "' />";
        $output .= "<label for='form-field-" . $id . '-' . $random_id . "'>" . $label . "</label>";
        $output .= "</li>";

        echo $output;
?>
        
<?php
    }
}
