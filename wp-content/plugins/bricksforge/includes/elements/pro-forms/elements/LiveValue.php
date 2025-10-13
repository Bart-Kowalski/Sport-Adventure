<?php

namespace Bricks;

use \Bricksforge\ProForms\Helper as Helper;

if (!defined('ABSPATH'))
    exit;

class Brf_Pro_Forms_LiveValue extends \Bricks\Element
{

    public $category = 'bricksforge forms';
    public $name = 'brf-pro-forms-field-live-value';
    public $icon = 'fa-solid fa-feather';
    public $css_selector = '';
    public $scripts = [];

    public function get_label()
    {
        return esc_html__("Live Value", 'bricksforge');
    }

    public function enqueue_scripts()
    {
        wp_enqueue_script('bricksforge-elements');
    }

    public function set_controls()
    {
        // Connection
        $this->controls['connection'] = [
            'label'          => esc_html__('Connection (Form Field ID)', 'bricksforge'),
            'type'           => 'text',
        ];

        // Tag name
        $this->controls['tag_name'] = [
            'label'          => esc_html__('Tag Name', 'bricksforge'),
            'type'           => 'text',
            'default'        => 'span',
        ];

        // Fallback
        $this->controls['fallback'] = [
            'label'          => esc_html__('Fallback', 'bricksforge'),
            'type'           => 'text',
        ];

        // Prefix
        $this->controls['prefix'] = [
            'label'          => esc_html__('Prefix', 'bricksforge'),
            'type'           => 'text',
        ];

        // Suffix
        $this->controls['suffix'] = [
            'label'          => esc_html__('Suffix', 'bricksforge'),
            'type'           => 'text',
        ];
    }

    public function render()
    {
        $settings = $this->settings;
        $connection = isset($settings['connection']) ? $settings['connection'] : null;
        $tag_name = isset($settings['tag_name']) ? $settings['tag_name'] : 'span';
        $fallback = isset($settings['fallback']) ? $settings['fallback'] : null;
        $prefix = isset($settings['prefix']) ? $settings['prefix'] : null;
        $suffix = isset($settings['suffix']) ? $settings['suffix'] : null;

        $this->set_attribute("_root", 'class', ['brf-live-value']);

        if (isset($connection)) {
            $this->set_attribute("_root", 'data-connection', $connection);
        }

        if (isset($fallback)) {
            $this->set_attribute("_root", 'data-fallback', $fallback);
        }

        if (isset($prefix)) {
            $this->set_attribute("_root", 'data-prefix', $prefix);
        }

        if (isset($suffix)) {
            $this->set_attribute("_root", 'data-suffix', $suffix);
        }

        $output = '<' . $tag_name . ' ' . $this->render_attributes('_root') . '>';

        if (isset($prefix) && !empty($prefix)) {
            $output .= $prefix;
        }

        if (bricks_is_builder() || bricks_is_rest_call()) {
            if (isset($connection)) {
                $output .= '[Live Value for Field: ' . $connection . ']';
            } else {
                $output .= '[Live Value: No connection set]';
            }
        }

        if (isset($fallback)) {
            $output .= $fallback;
        }

        if (isset($suffix) && !empty($suffix)) {
            $output .= $suffix;
        }

        $output .= '</' . $tag_name . '>';

        echo $output;

?>
<?php
    }
}
