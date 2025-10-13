<?php

namespace Bricks;

use \Bricksforge\ProForms\Helper as Helper;
use \Bricksforge\Api\FormsHelper as FormsHelper;

if (!defined('ABSPATH'))
    exit;

class Brf_Pro_Forms_Option extends \Bricks\Element
{

    public $category = 'bricksforge forms';
    public $name = 'brf-pro-forms-field-option';
    public $icon = 'fa-solid fa-rectangle-list';
    public $css_selector = '';
    public $scripts = [];
    public $nestable = true;

    public function get_label()
    {
        return esc_html__("Option", 'bricksforge');
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
    }

    public function set_controls()
    {
        $this->controls = array_merge($this->controls, Helper::get_loop_controls());


        $this->controls['label'] = [
            'group' => 'general',
            'label'          => esc_html__('Label', 'bricksforge'),
            'type'           => 'text',
            'inline'         => true,
            'spellcheck'     => false,
            'hasDynamicData' => true,
        ];
        $this->controls['value'] = [
            'group' => 'general',
            'label'          => esc_html__('Value', 'bricksforge'),
            'type'           => 'text',
            'inline'         => true,
            'spellcheck'     => false,
            'hasDynamicData' => true,
        ];
        $this->controls['calculationValue'] = [
            'group' => 'general',
            'label'          => esc_html__('Calculation Value', 'bricksforge'),
            'type'           => 'number',
            'inline'         => true,
            'hasDynamicData' => true,
            'description'    => esc_html__('A numeric value that will be used for calculation fields.', 'bricksforge'),
        ];

        $this->controls = array_merge($this->controls, Helper::get_selected_controls());
    }

    public function render()
    {
        $element = $this->element;
        $settings = $this->settings;
        $field_wrapper = Helper::get_parent("brf-pro-forms-field-select", $element);
        $value = isset($settings['value']) ? bricks_render_dynamic_data($settings['value']) : null;
        $parent_value = isset($field_wrapper['settings']['value']) ? bricks_render_dynamic_data($field_wrapper['settings']['value']) : false;
        $label = isset($settings['label']) ? $settings['label'] : null;
        $output   = '';

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

        // If nothing is set, we stop here
        if (!isset($label) && !isset($value)) {
            return;
        }

        // If no value is set, use the label
        if (!isset($value)) {
            $value = $label;
        }

        // If no label is set, use the value
        if (!isset($label)) {
            $label = $value;
        }

        /**
         * Wrapper
         */
        $this->remove_attribute('_root', 'class');
        $this->set_attribute('_root', 'data-label', $label);

        // Calculation Value
        if (isset($settings['calculationValue']) && $settings['calculationValue']) {
            $this->set_attribute('_root', 'data-calculation-value', $settings['calculationValue']);
        }

        // If $parent_value is set, this is the initial value (selected)
        if (isset($parent_value) && (trim($parent_value) == bricks_render_dynamic_data(trim($value)))) {
            $this->set_attribute('_root', 'selected', 'selected');
        }

        // Populate
        $needs_selected = FormsHelper::handle_conditionally_selected($settings, $value, "selected");

        if ($needs_selected) {
            $this->set_attribute('_root', 'selected', 'selected');
        } else {
            // This is removing every attribute for some reason. We temporarily remove it.
            //$this->remove_attribute('_root', 'selected');
        }

        $this->set_attribute('_root', 'value', $value);

        $output .= '<option ' . $this->render_attributes('_root') . '>';
        $output .= $label;
        $output .= '</option>';

        echo $output;
?>
<?php
    }
}
