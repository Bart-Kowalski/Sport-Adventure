<?php

namespace Bricks;

use \Bricksforge\ProForms\Helper as Helper;

if (!defined('ABSPATH'))
    exit;

class Brf_Pro_Forms_ConditionalWrapper extends \Bricks\Element
{

    public $category = 'bricksforge forms';
    public $name = 'brf-pro-forms-field-conditional-wrapper';
    public $icon = 'fa-solid fa-lightbulb';
    public $css_selector = '';
    public $scripts = [];
    public $nestable = true;

    public function get_label()
    {
        return esc_html__("Conditional Wrapper", 'bricksforge');
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

        $this->control_groups['conditions'] = [
            'title'    => esc_html__('Conditions', 'bricksforge'),
            'tab'      => 'content',
        ];
    }

    public function set_controls()
    {
        // Tag name
        $this->controls['tag_name'] = [
            'label'          => esc_html__('Tag Name', 'bricksforge'),
            'group'          => 'general',
            'type'           => 'text',
            'default'        => 'div',
        ];

        // Submit Form Data even if conditional wrapper is hidden
        $this->controls['submit_form_data_even_if_hidden'] = [
            'label'          => esc_html__('Always send form data', 'bricksforge'),
            'description'    => esc_html__('If enabled, the form data will be sent even if the conditional wrapper is hidden.', 'bricksforge'),
            'group'          => 'general',
            'type'           => 'checkbox',
            'default'        => false,
        ];

        $this->controls = array_merge($this->controls, Helper::get_condition_controls());
    }

    public function render()
    {
        $settings = $this->settings;
        $tag_name = isset($settings['tag_name']) ? $settings['tag_name'] : 'div';

        $submit_form_data_even_if_hidden = isset($settings['submit_form_data_even_if_hidden']) ? $settings['submit_form_data_even_if_hidden'] : false;

        if ($submit_form_data_even_if_hidden) {
            $this->set_attribute('_root', 'data-brf-always-send-form-data', 'true');
        }

        // Conditions
        if (isset($settings['hasConditions']) && isset($settings['conditions']) && $settings['conditions']) {
            $this->set_attribute('_root', 'data-brf-conditions', json_encode($settings['conditions']));
        }
        if (isset($settings['conditionsRelation']) && $settings['conditionsRelation']) {
            $this->set_attribute('_root', 'data-brf-conditions-relation', $settings['conditionsRelation']);
        }

        $output = '<' . $tag_name . ' ' . $this->render_attributes('_root') . '>';

        $output .= Frontend::render_children($this);

        $output .= '</' . $tag_name . '>';

        echo $output;
?>
<?php
    }
}
