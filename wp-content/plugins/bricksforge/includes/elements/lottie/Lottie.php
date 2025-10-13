<?php

namespace Bricks;

if (!defined('ABSPATH'))
    exit;

class Brf_Lottie extends \Bricks\Element
{

    public $category = 'bricksforge';
    public $name = 'brf-lottie';
    public $icon = 'ti ti-layout-tab-window';
    public $css_selector = 'brf-lottie';
    public $scripts = [];
    public $nestable = false;

    public function __construct($element = null)
    {
        parent::__construct($element);

        add_filter('upload_mimes', function ($mimes) {
            $mimes['json'] = 'application/json';
            return $mimes;
        });
    }

    public function get_label()
    {
        return esc_html__("Lottie", 'bricksforge');
    }

    public function enqueue_scripts()
    {
        wp_enqueue_script('bricksforge-lottie');
    }

    public function set_control_groups()
    {
    }

    public function set_controls()
    {
        // Source (File, URL)
        $this->controls['source'] = [
            'tab'     => 'content',
            'label'   => esc_html__('Source', 'bricksforge'),
            'type'    => 'select',
            'default' => 'file',
            'options' => [
                'file' => esc_html__('File', 'bricksforge'),
                'url'  => esc_html__('URL', 'bricksforge'),
            ],
        ];

        // File
        $this->controls['file'] = [
            'tab'     => 'content',
            'label'   => esc_html__('JSON File', 'bricksforge'),
            'type'    => 'file',
            'required' => ['source', '=', 'file'],
        ];

        // URL
        $this->controls['url'] = [
            'tab'     => 'content',
            'label'   => esc_html__('URL', 'bricksforge'),
            'type'    => 'text',
            'required' => ['source', '=', 'url'],
        ];

        // Width
        $this->controls['width'] = [
            'tab'     => 'content',
            'label'   => esc_html__('Width', 'bricksforge'),
            'type'    => 'number',
            'default' => '400px',
            'css'     => [
                [
                    'selector' => 'lottie-player',
                    'property' => 'width',
                ],
            ],
        ];

        // Height
        $this->controls['height'] = [
            'tab'     => 'content',
            'label'   => esc_html__('Height', 'bricksforge'),
            'type'    => 'number',
            'default' => '400px',
            'css'     => [
                [
                    'selector' => 'lottie-player',
                    'property' => 'height',
                ],
            ],
        ];

        // Autoplay
        $this->controls['autoplay'] = [
            'tab'     => 'content',
            'label'   => esc_html__('Autoplay', 'bricksforge'),
            'type'    => 'checkbox',
            'default' => true,
        ];

        // Loop
        $this->controls['loop'] = [
            'tab'     => 'content',
            'label'   => esc_html__('Loop', 'bricksforge'),
            'type'    => 'checkbox',
            'default' => true,
        ];

        // Speed
        $this->controls['speed'] = [
            'tab'     => 'content',
            'label'   => esc_html__('Speed', 'bricksforge'),
            'type'    => 'number',
            'default' => 1,
        ];

        // Mode (Normal, Bounce)
        $this->controls['mode'] = [
            'tab'     => 'content',
            'label'   => esc_html__('Mode', 'bricksforge'),
            'type'    => 'select',
            'default' => 'normal',
            'options' => [
                'normal' => esc_html__('Normal', 'bricksforge'),
                'bounce' => esc_html__('Bounce', 'bricksforge'),
            ],
        ];

        // Direction (Normal, Reverse)
        $this->controls['direction'] = [
            'tab'     => 'content',
            'label'   => esc_html__('Direction', 'bricksforge'),
            'type'    => 'select',
            'default' => 'normal',
            'options' => [
                'normal'  => esc_html__('Normal', 'bricksforge'),
                'reverse' => esc_html__('Reverse', 'bricksforge'),
            ],
        ];

        // Play On Hover
        $this->controls['playOnHover'] = [
            'tab'     => 'content',
            'label'   => esc_html__('Play On Hover', 'bricksforge'),
            'type'    => 'checkbox',
            'default' => false,
        ];

        // Controls
        $this->controls['controls'] = [
            'tab'     => 'content',
            'label'   => esc_html__('Show Controls', 'bricksforge'),
            'type'    => 'checkbox',
            'default' => false,
        ];

        // Background
        $this->controls['background'] = [
            'tab'     => 'content',
            'label'   => esc_html__('Background', 'bricksforge'),
            'type'    => 'color',
            'default' => 'transparent',
            'css'     => [
                [
                    'selector' => 'lottie-player',
                    'property' => 'background-color',
                ],
            ],
        ];
    }

    public function render()
    {
        $settings = $this->settings;
        $source = isset($settings['source']) ? $settings['source'] : 'file';
        $file = isset($settings['file']) ? $settings['file'] : false;
        $url = isset($settings['url']) ? $settings['url'] : false;
        $autoplay = isset($settings['autoplay']) ? $settings['autoplay'] : false;
        $loop = isset($settings['loop']) ? $settings['loop'] : false;
        $controls = isset($settings['controls']) ? $settings['controls'] : false;
        $speed = isset($settings['speed']) ? $settings['speed'] : 1;
        $mode = isset($settings['mode']) ? $settings['mode'] : 'normal';
        $direction = isset($settings['direction']) ? $settings['direction'] : 'normal';
        $play_on_hover = isset($settings['playOnHover']) ? $settings['playOnHover'] : false;

        if (!$file && !$url) {
            return $this->render_element_placeholder(
                [
                    'title' => esc_html__('No Lottie File added', 'bricks'),
                ]
            );
        }

        $root_classes[] = 'brf-lottie';
        $this->set_attribute('_root', 'class', $root_classes);

        /**
         *  Lottie Player Attributes
         */

        if ($autoplay) {
            $this->set_attribute('_player', 'autoplay');
        }

        if ($loop) {
            $this->set_attribute('_player', 'loop');
        }

        if ($controls) {
            $this->set_attribute('_player', 'controls');
        }

        if ($play_on_hover) {
            $this->set_attribute('_player', 'hover');
        }

        $this->set_attribute('_player', 'src', $source === 'file' ? $file['url'] : $url);

        $this->set_attribute('_player', 'speed', $speed);
        $this->set_attribute('_player', 'mode', $mode);
        $this->set_attribute('_player', 'direction', $direction);

        $output = "<div {$this->render_attributes('_root')}>";
        $output .= "<lottie-player {$this->render_attributes('_player')}></lottie-player>";
        $output .= "</div>";


        echo $output;
    }
}
