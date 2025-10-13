<?php

namespace Bricks;

use \Bricksforge\ProForms\Helper as Helper;

if (!defined('ABSPATH'))
    exit;

class Brf_Pro_Forms_SummaryButton extends \Bricks\Element
{

    public $category = 'bricksforge forms';
    public $name = 'brf-pro-forms-field-summary-button';
    public $icon = 'fa-solid fa-file-invoice';
    public $css_selector = '';
    public $scripts = [];
    public $nestable = false;

    public function get_label()
    {
        return esc_html__("Summary Button", 'bricksforge');
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
            'default'        => esc_html__('Label', 'bricksforge'),
            'default'   => 'Summary'
        ];

        $this->controls = array_merge($this->controls, Helper::get_advanced_controls());
        $this->controls = array_merge($this->controls, Helper::get_button_style_controls());
    }

    public function render()
    {
        $settings = $this->settings;
        $parent_settings = Helper::get_nestable_parent_settings($this->element) ? Helper::get_nestable_parent_settings($this->element) : [];

        /**
         * Wrapper
         */
        $this->set_attribute('button', 'class', ['bricks-button', 'summary', 'step-progress']);


        if (isset($settings['cssClass']) && !empty($settings['cssClass'])) {
            $this->set_attribute('button', 'class', $settings['cssClass']);
        }

        $output = '<div ' . $this->render_attributes('_root') . '>';

        $output .= '<button ' . $this->render_attributes('button') . ' type="button">';
        $output .= esc_html(isset($settings['label']) && $settings['label'] ? $settings['label'] : 'Summary', 'bricksforge');
        $output .= '</button>';

        $output .= '</div>';

        echo $output;
?>
<?php
    }
}
