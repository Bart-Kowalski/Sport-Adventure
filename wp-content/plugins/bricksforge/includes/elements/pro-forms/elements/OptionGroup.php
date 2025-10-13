<?php

namespace Bricks;

use \Bricksforge\ProForms\Helper as Helper;

if (!defined('ABSPATH'))
    exit;

class Brf_Pro_Forms_OptionGroup extends \Bricks\Element
{

    public $category = 'bricksforge forms';
    public $name = 'brf-pro-forms-field-option-group';
    public $icon = 'fa-solid fa-layer-group';
    public $css_selector = '';
    public $scripts = [];
    public $nestable = true;

    public function get_label()
    {
        return esc_html__("Option Group", 'bricksforge');
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

        $this->controls['label'] = [
            'group' => 'general',
            'label'          => esc_html__('Label', 'bricksforge'),
            'type'           => 'text',
            'inline'         => true,
            'spellcheck'     => false,
            'hasDynamicData' => true,
            'default'        => 'Label'
        ];
    }

    public function render()
    {
        $settings = $this->settings;
        $label = isset($settings['label']) ? $settings['label'] : '';

        // Create optgroup.
        $output = '<optgroup label="' . $label . '">';
        $output .= Frontend::render_children($this);
        $output .= '</optgroup>';

        echo $output;
?>
<?php
    }
}
