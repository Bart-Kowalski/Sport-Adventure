<?php

namespace Bricks;

use \Bricksforge\ProForms\Helper as Helper;

if (!defined('ABSPATH'))
    exit;

class Brf_Pro_Forms_Step extends \Bricks\Element
{

    public $category = 'bricksforge forms';
    public $name = 'brf-pro-forms-field-step';
    public $icon = 'fa-solid fa-list-check';
    public $css_selector = '';
    public $scripts = [];
    public $nestable = false;
    private $turnstile_key;

    public function get_label()
    {
        return esc_html__("Step", 'bricksforge');
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
        $this->controls['label'] = [
            'group' => 'general',
            'label'          => esc_html__('Label', 'bricksforge'),
            'type'           => 'text',
            'inline'         => true,
            'spellcheck'     => false,
            'hasDynamicData' => true,
            'default'   => 'Step'
        ];

        $this->controls = array_merge($this->controls, Helper::get_condition_controls());


        // Conditional Method (Prevent Navigation, Hide Step)
        $this->controls['conditionalMethod'] = [
            'group' => 'conditions',
            'label' => esc_html__('Method', 'bricksforge'),
            'type' => 'select',
            'options' => [
                'prevent-navigation' => esc_html__('Prevent Navigation', 'bricksforge'),
                'hide-step' => esc_html__('Hide Step', 'bricksforge'),
            ],
            'default' => 'hide-step',
            'required' => [['hasConditions', '=', true]],
        ];

        // Extend conditions with Error Message
        $this->controls['errorMessage'] = [
            'group' => 'conditions',
            'label' => esc_html__('Error Message', 'bricksforge'),
            'description' => esc_html__('This message will be displayed if the conditions are not met and the navigation to the next step is not allowed.', 'bricksforge'),
            'type' => 'text',
            'required' => [['hasConditions', '=', true], ['conditionalMethod', '=', 'prevent-navigation']],
        ];


        $this->controls = array_merge($this->controls, Helper::get_advanced_controls());
    }

    public function render()
    {
        $settings = $this->settings;
        $parent_settings = Helper::get_nestable_parent_settings($this->element) ? Helper::get_nestable_parent_settings($this->element) : [];

        /**
         * Wrapper
         */
        $this->set_attribute("_root", 'class', ['step']);
        $this->set_attribute("_root", 'aria-label', isset($settings['label']) ? $settings['label'] : '');

        if (bricks_is_builder() || bricks_is_rest_call()) {
            $this->set_attribute("_root", 'data-step-builder');
        }

        // Conditions
        if (isset($settings['hasConditions']) && isset($settings['conditions']) && $settings['conditions']) {
            $this->set_attribute('_root', 'data-brf-conditions', json_encode($settings['conditions']));
        }
        if (isset($settings['conditionsRelation']) && $settings['conditionsRelation']) {
            $this->set_attribute('_root', 'data-brf-conditions-relation', $settings['conditionsRelation']);
        }
        if (isset($settings['conditionalMethod']) && $settings['conditionalMethod']) {
            $this->set_attribute('_root', 'data-brf-conditional-method', $settings['conditionalMethod']);
        }
        if (isset($settings['errorMessage']) && $settings['errorMessage']) {
            $this->set_attribute('_root', 'data-brf-error-message', $settings['errorMessage']);
        }

        $output = '<div ' . $this->render_attributes('_root') . '>';

        if (bricks_is_builder() || bricks_is_rest_call()) {
            $output .= 'Step: ' . $settings['label'];
        }

        $output .= '</div>';

        echo $output;
?>
<?php
    }
}
