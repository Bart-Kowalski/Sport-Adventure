<?php

namespace Bricks;

if (!defined('ABSPATH'))
    exit;

class BrfOption extends \Bricks\Element
{

    public $category = 'bricksforge';
    public $name = 'brf-option';
    public $icon = 'ti ti-server';
    public $scripts = [];
    public $nestable = false;

    public function get_label()
    {
        return esc_html__("Option", 'bricksforge');
    }


    public function set_controls()
    {
        $this->controls['info'] = [
            'tab'     => 'content',
            'content' => esc_html__('Define a fallback if needed. This text will be displayed if there is no entry in the database. Important: The output does not work with arrays.', 'bricksforge'),
            'type'    => 'info',
        ];
        $this->controls['name'] = [
            'tab'   => 'content',
            'type'  => 'text',
            'label' => 'Option Name',
        ];
        $this->controls['fallback'] = [
            'tab'   => 'content',
            'type'  => 'text',
            'label' => 'Fallback',
        ];
    }

    public function option_value($name, $fallback)
    {
        $output = get_option($name, $fallback);

        // If is array, we join the values
        if (is_array($output)) {
            $output = implode(', ', $output);
        }

        return $output;
    }


    public function render()
    {
        $settings = $this->settings;
        $name = isset($settings['name']) ? $settings['name'] : '';
        $fallback = isset($settings['fallback']) ? $settings['fallback'] : '';

        if (!$name) {
            return $this->render_element_placeholder(
                [
                    'title'      => esc_html__('No Option Added', 'bricks'),
                    'icon-class' => 'ti-server',
                ]
            );
        }

        $root_classes[] = 'brf-option';

        $this->set_attribute('_root', 'class', $root_classes);

        $output = "<div {$this->render_attributes('_root')}>";

        $output .= $this->option_value($name, $fallback);

        $output .= "</div>";


        echo $output;
    }
}
