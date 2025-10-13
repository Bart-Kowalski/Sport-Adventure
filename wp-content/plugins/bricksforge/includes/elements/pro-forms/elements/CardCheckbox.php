<?php

namespace Bricks;

use \Bricksforge\ProForms\Helper as Helper;
use \Bricksforge\Api\FormsHelper as FormsHelper;

if (!defined('ABSPATH'))
    exit;

class Brf_Pro_Forms_CardCheckbox extends \Bricks\Element
{

    public $category = 'bricksforge forms';
    public $name = 'brf-pro-forms-field-card-checkbox';
    public $icon = 'fa-solid fa-square-check';
    public $css_selector = '';
    public $scripts = [];
    public $nestable = true;

    public function get_label()
    {
        return esc_html__("Card Checkbox", 'bricksforge');
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
        $this->control_groups['checkedStyle'] = [
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
        $this->controls = array_merge($this->controls, Helper::get_default_controls('card-checkbox'));

        $this->controls = array_merge($this->controls, Helper::get_checked_controls());

        // Card Width
        $this->controls['cardWidth'] = [
            'group' => 'style',
            'label'          => esc_html__('Card Width', 'bricksforge'),
            'type'           => 'number',
            'units' => true,
            'css' => [
                [
                    'selector' => 'label',
                    'property' => 'width'
                ]
            ],
            'default' => '100%'

        ];

        // Card Padding
        $this->controls['cardPadding'] = [
            'group' => 'style',
            'label'          => esc_html__('Card Padding', 'bricksforge'),
            'type'           => 'dimensions',
            'css' => [
                [
                    'selector' => 'label',
                    'property' => 'padding',
                ]
            ],
            'default' => [
                'top' => '15px',
                'right' => '15px',
                'bottom' => '15px',
                'left' => '15px',
            ]
        ];

        // Card Margin
        $this->controls['cardMargin'] = [
            'group' => 'style',
            'label'          => esc_html__('Card Margin', 'bricksforge'),
            'type'           => 'dimensions',
            'css' => [
                [
                    'selector' => 'label',
                    'property' => 'margin',
                ]
            ]
        ];

        // Card Background
        $this->controls['cardBackground'] = [
            'group' => 'style',
            'label'          => esc_html__('Card Background', 'bricksforge'),
            'type'           => 'background',
            'css' => [
                [
                    'selector' => 'label',
                    'property' => 'background',
                ]
            ],
            'default' => [
                'color' => [
                    'hex' => '#eee',
                ]
            ]
        ];

        // Card Border
        $this->controls['cardBorder'] = [
            'group' => 'style',
            'label'          => esc_html__('Card Border', 'bricksforge'),
            'type'           => 'border',
            'css' => [
                [
                    'selector' => 'label',
                    'property' => 'border',
                ]
            ]
        ];

        // Card Box Shadow
        $this->controls['cardBoxShadow'] = [
            'group' => 'style',
            'label'          => esc_html__('Card Box Shadow', 'bricksforge'),
            'type'           => 'box-shadow',
            'css' => [
                [
                    'selector' => 'label',
                    'property' => 'box-shadow',
                ]
            ]
        ];

        // Card Filter
        $this->controls['cardFilter'] = [
            'group' => 'style',
            'label'          => esc_html__('Card Filter', 'bricksforge'),
            'type'           => 'filters',
            'inline'         => true,
            'css' => [
                [
                    'selector' => 'label',
                    'property' => 'filter',
                ]
            ]
        ];

        // Card Transform
        $this->controls['cardTransform'] = [
            'group' => 'style',
            'label'          => esc_html__('Card Transform', 'bricksforge'),
            'type'           => 'transform',
            'css' => [
                [
                    'selector' => 'label',
                    'property' => 'transform',
                ]
            ]
        ];

        /**
         * Checked
         */

        // Checked Card Padding
        $this->controls['checkedCardPadding'] = [
            'group' => 'checkedStyle',
            'label'          => esc_html__('Checked Card Padding', 'bricksforge'),
            'type'           => 'dimensions',
            'css' => [
                [
                    'selector' => 'input:checked + label',
                    'property' => 'padding',
                    'important' => 'true'
                ]
            ]
        ];

        // Checked Card Margin
        $this->controls['checkedCardMargin'] = [
            'group' => 'checkedStyle',
            'label'          => esc_html__('Checked Card Margin', 'bricksforge'),
            'type'           => 'dimensions',
            'css' => [
                [
                    'selector' => 'input:checked + label',
                    'property' => 'margin',
                    'important' => 'true'
                ]
            ]
        ];

        // Checked Card Background
        $this->controls['checkedCardBackground'] = [
            'group' => 'checkedStyle',
            'label'          => esc_html__('Checked Card Background', 'bricksforge'),
            'type'           => 'background',
            'css' => [
                [
                    'selector' => 'input:checked + label',
                    'property' => 'background',
                    'important' => 'true'
                ]
            ],
            'default' => [
                'color' => [
                    'hex' => '#ccc',
                ]
            ]
        ];

        // Checked Card Border
        $this->controls['checkedCardBorder'] = [
            'group' => 'checkedStyle',
            'label'          => esc_html__('Checked Card Border', 'bricksforge'),
            'type'           => 'border',
            'css' => [
                [
                    'selector' => 'input:checked + label',
                    'property' => 'border',
                    'important' => 'true'
                ]
            ]
        ];

        // Checked Card Box Shadow
        $this->controls['checkedCardBoxShadow'] = [
            'group' => 'checkedStyle',
            'label'          => esc_html__('Checked Card Box Shadow', 'bricksforge'),
            'type'           => 'box-shadow',
            'css' => [
                [
                    'selector' => 'input:checked + label',
                    'property' => 'box-shadow',
                    'important' => 'true'
                ]
            ]
        ];

        // Checked Card Filter
        $this->controls['checkedCardFilter'] = [
            'group' => 'checkedStyle',
            'label'          => esc_html__('Checked Card Filter', 'bricksforge'),
            'type'           => 'filters',
            'inline'         => true,
            'css' => [
                [
                    'selector' => 'input:checked + label',
                    'property' => 'filter',
                    'important' => 'true'
                ]
            ]
        ];

        // Checked Card Transform
        $this->controls['checkedCardTransform'] = [
            'group' => 'checkedStyle',
            'label'          => esc_html__('Checked Card Transform', 'bricksforge'),
            'type'           => 'transform',
            'css' => [
                [
                    'selector' => 'input:checked + label',
                    'property' => 'transform',
                    'important' => 'true'
                ]
            ]
        ];

        $this->controls = array_merge($this->controls, Helper::get_accessibility_controls());
        $this->controls = array_merge($this->controls, Helper::get_advanced_controls());
    }

    public function get_nestable_children()
    {
        return [
            [
                'name'     => 'heading',
                'label'    => esc_html__('Heading', 'bricksforge'),
                'settings' => [
                    '_text' => "Child",
                    'text' => "Child",
                    'tag' => 'h4'
                ]
            ],
        ];
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

            $query = new \Bricks\Query($element);

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
        $this->set_attribute('_root', 'class', 'card-checkbox');

        // Custom Css Class
        if (isset($settings['cssClass']) && $settings['cssClass']) {
            $this->set_attribute('field', 'class', $settings['cssClass']);
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
            $this->set_attribute('field', 'required', $required);
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
        $output .= "<input hidden type='checkbox' " . $this->render_attributes("field") . " " . $checked . " aria-label='" . $label . "' role='checkbox' aria-checked='" . ($checked ? 'true' : 'false') . "' />";
        $output .= "<label for='form-field-" . $id . '-' . $random_id . "'>" . Frontend::render_children($this) . "</label>";
        $output .= "</li>";

        echo $output;
?>

    <?php
    }

    public static function render_builder()
    { ?>
        <script type="text/x-template" id="tmpl-bricks-element-brf-pro-forms-field-card-checkbox">
            <component :is="tag">
                <li class="card-checkbox" :class="settings.cssClass">
                    <input hidden type="checkbox" :required="settings.required" :data-label="settings.label" role="checkbox" aria-checked="false">
                    <label>
                        <bricks-element-children :element="element"/>
                    </label>
                </li>
            </component>
        </script>
<?php
    }
}
