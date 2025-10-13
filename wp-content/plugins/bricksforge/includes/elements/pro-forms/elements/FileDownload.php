<?php

namespace Bricks;

use \Bricksforge\ProForms\Helper as Helper;

if (!defined('ABSPATH'))
    exit;

class Brf_Pro_Forms_FileDownload extends \Bricks\Element
{

    public $category = 'bricksforge forms';
    public $name = 'brf-pro-forms-field-file-download';
    public $icon = 'fa-solid fa-file';
    public $css_selector = '';
    public $scripts = [];
    public $nestable = true;

    public function get_label()
    {
        return esc_html__("Download Info", 'bricksforge');
    }

    public function enqueue_scripts()
    {
        wp_enqueue_script('bricksforge-elements');
    }

    public function set_control_groups()
    {
    }

    public function set_controls()
    {
        $this->controls['info'] = [
            'type'           => 'info',
            'content'        => esc_html__('This element is used to show download information and a download button after a form submission.', 'bricksforge'),
        ];

        // Trigger
        $this->controls['trigger'] = [
            'type'           => 'select',
            'label'          => esc_html__('Trigger', 'bricksforge'),
            'default'        => 'automatic',
            'options'        => [
                'automatic' => esc_html__('Automatic', 'bricksforge'),
                'manual'    => esc_html__('Manual', 'bricksforge'),
            ],
            'description'    => esc_html__('By default, the download info will be loaded automatically, for example after proceeding certain actions like "Create PDF". But you can also set it to "Manual" and include a static download URL.', 'bricksforge'),
        ];

        // Download URL
        $this->controls['download_url'] = [
            'type'           => 'text',
            'label'          => esc_html__('Download URL', 'bricksforge'),
            'default'        => '',
            'required'      => ["trigger", "=", "manual"],
        ];
    }

    public function get_nestable_children()
    {
        return [
            [
                'name'     => 'button',
                'label'    => esc_html__('Button', 'bricksforge'),
                'settings' => [
                    'text' => esc_html__('Download Button', 'bricksforge'),
                    'style' => 'primary',
                    'size' => 'sm',
                    '_cssClasses' => 'file-download-btn',
                    "link" => [
                        "type" => "external",
                        "newTab" => true
                    ]
                ],
            ]
        ];
    }

    public function render()
    {
        $settings = $this->settings;
        $parent_settings = Helper::get_nestable_parent_settings($this->element) ? Helper::get_nestable_parent_settings($this->element) : [];

        /**
         * Wrapper
         */
        $this->set_attribute("_root", 'class', 'brf-pro-forms-field-file-download');

        if (!bricks_is_builder() && !bricks_is_builder_call()) {
            $this->set_attribute("_root", 'class', 'brf-pending');
        }

        $output = '<div ' . $this->render_attributes('_root') . '>';

        $output .= Frontend::render_children($this);

        $output .= '</div>';

        echo $output;
?>
    <?php
    }

    public static function render_builder()
    { ?>
        <script type="text/x-template" id="tmpl-bricks-element-brf-pro-forms-field-file-download">
            <component :is="tag">
            <div class="brf-pro-forms-field-file-download">
                <bricks-element-children :element="element"/>
            </div>
            </component>
        </script>
<?php
    }
}
