<?php

namespace Bricks;

if (!defined('ABSPATH'))
    exit;

class Brf_Scroll_Video extends \Bricks\Element
{

    public $category = 'bricksforge';
    public $name = 'brf-scroll-video';
    public $icon = 'ti ti-video-clapper';
    public $scripts = ['brfScrollVideoInstance'];
    public $nestable = false;

    public function get_label()
    {
        return esc_html__("Scroll Video", 'bricksforge');
    }

    public function enqueue_scripts()
    {
        wp_enqueue_script('bricksforge-scrolly-video');
        wp_enqueue_script('bricksforge-elements');
    }

    public function set_controls()
    {

        // Video File
        $this->controls['videoFile'] = [
            'tab' => 'content',
            'type' => 'video',
            'label' => 'Video File',
        ];

        // Transition Speed
        $this->controls['transitionSpeed'] = [
            'tab' => 'content',
            'type' => 'number',
            'label' => 'Transition Speed',
            'default' => 8,
            'min' => 0.1,
            'max' => 99,
            'step' => 0.1,
        ];

        // Frame Treshold
        $this->controls['frameThreshold'] = [
            'tab' => 'content',
            'type' => 'number',
            'label' => 'Frame Threshold',
            'default' => 0.1,
            'min' => 0.1,
            'max' => 99,
            'step' => 0.1,
        ];

        // Cover (Checkbox)
        $this->controls['cover'] = [
            'tab' => 'content',
            'type' => 'checkbox',
            'label' => 'Cover',
            'default' => false,
        ];

        // Sticky (Checkbox)
        $this->controls['sticky'] = [
            'tab' => 'content',
            'type' => 'checkbox',
            'label' => 'Sticky',
            'default' => true,
        ];

        // Full (Checkbox)
        $this->controls['full'] = [
            'tab' => 'content',
            'type' => 'checkbox',
            'label' => 'Full (Entire Viewport)',
            'default' => true,
        ];

        // Use Web Codex (Checkbox)
        $this->controls['useWebCodecs'] = [
            'tab' => 'content',
            'type' => 'checkbox',
            'label' => 'Use Web Codecs',
            'default' => true,
        ];

        // Info
        $this->controls['webCodecsInfo'] = [
            'required' => ['useWebCodecs', '=', true],
            'tab' => 'content',
            'type' => 'info',
            'content' => 'For performance reasons, the use of web codecs is disabled in the builder.',
        ];

        // Debug
        $this->controls['debug'] = [
            'tab' => 'content',
            'type' => 'checkbox',
            'label' => 'Debug (Console Logs)',
            'default' => false,
        ];

    }

    public function render()
    {
        $settings = $this->settings;

        $root_classes[] = 'brf-scroll-video';

        $this->set_attribute('_root', 'class', $root_classes);

        if (isset($settings['videoFile'])) {
            $this->set_attribute('_root', 'data-src', $settings['videoFile']['url']);
        }

        if (isset($settings['transitionSpeed'])) {
            $this->set_attribute('_root', 'data-transition-speed', $settings['transitionSpeed']);
        }

        if (isset($settings['frameThreshold'])) {
            $this->set_attribute('_root', 'data-frame-threshold', $settings['frameThreshold']);
        }

        if (isset($settings['cover']) && $settings['cover']) {
            $this->set_attribute('_root', 'data-cover', $settings['cover']);
        }

        if (isset($settings['sticky']) && $settings['sticky']) {
            $this->set_attribute('_root', 'data-sticky', $settings['sticky']);
        } else {
            $this->set_attribute('_root', 'data-sticky', 'false');
        }

        if (isset($settings['full']) && $settings['full']) {
            $this->set_attribute('_root', 'data-full', $settings['full']);
        }

        if (isset($settings['useWebCodecs']) && $settings['useWebCodecs']) {
            $this->set_attribute('_root', 'data-use-web-codecs', $settings['useWebCodecs']);
        }

        if (isset($settings['debug']) && $settings['debug']) {
            $this->set_attribute('_root', 'data-debug', $settings['debug']);
        }

        $output = "<div {$this->render_attributes('_root')}>";
        $output .= "</div>";


        echo $output;
    }

}