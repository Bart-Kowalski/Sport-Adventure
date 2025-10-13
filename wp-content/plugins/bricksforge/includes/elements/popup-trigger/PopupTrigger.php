<?php

namespace Bricks;

if (!defined('ABSPATH'))
    exit;

class Brf_Popup_Trigger extends \Bricks\Element
{

    public $category = 'bricksforge';
    public $name = 'brf-popup-trigger';
    public $icon = 'ti ti-layout-tab-window';
    public $css_selector = 'brf-popup-trigger';
    public $scripts = [];
    public $nestable = true;

    public function get_label()
    {
        return esc_html__("Popup Trigger", 'bricksforge');
    }

    public function enqueue_scripts()
    {
        wp_enqueue_script('bricksforge-popups');
    }

    public function set_control_groups()
    {
    }

    public function set_controls()
    {
        $this->controls['info'] = [
            'tab'     => 'content',
            'content' => esc_html__('Add children to this Nestable element. These will serve as triggers.', 'bricksforge'),
            'type'    => 'info',
        ];
        $this->controls['type'] = [
            'tab'     => 'content',
            'type'    => 'select',
            'label'   => 'Type',
            'options' => [
                'open'  => 'Open Popup',
                'close' => 'Close Popup',
            ],
            'default' => 'open'
        ];
        $this->controls['popup'] = [
            'required'    => [['type', '=', 'open']],
            'tab'         => 'content',
            'type'        => 'select',
            'label'       => 'Popup',
            'options'     => $this->get_popups(),
            'placeholder' => 'Choose Popup'
        ];
    }

    public function get_popups()
    {
        $output = [];
        $popups = get_option('brf_popups');

        if ($popups && count($popups) > 0) {
            foreach ($popups as $popup) {
                if (isset($popup->active) && $popup->active == true) {
                    $output[$popup->id] = isset($popup->name) ? $popup->name : "Unknown Popup";
                }
            }
        }

        return $output;
    }

    public function render()
    {
        $settings = $this->settings;

        $root_classes[] = 'brf-popup-trigger';
        $root_classes[] = isset($settings['type']) && $settings['type'] == 'open' ? 'brf-popup-open' : 'brf-popup-close';

        $this->set_attribute('_root', 'class', $root_classes);
        $this->set_attribute('_root', 'data-popup', isset($settings['popup']) ? $settings['popup'] : '');

        $output = "<div {$this->render_attributes('_root')} style='cursor: pointer'>";
        $output .= Frontend::render_children($this);
        $output .= "</div>";


        echo $output;
    }
}
