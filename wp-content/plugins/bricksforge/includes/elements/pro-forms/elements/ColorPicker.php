<?php

namespace Bricks;

use \Bricksforge\ProForms\Helper as Helper;

if (!defined('ABSPATH'))
    exit;

class Brf_Pro_Forms_ColorPicker extends \Bricks\Element
{

    public $category = 'bricksforge forms';
    public $name = 'brf-pro-forms-field-color-picker';
    public $icon = 'fa-solid fa-palette';
    public $css_selector = '';
    public $scripts = [];
    public $nestable = false;

    public function get_label()
    {
        return esc_html__("Color Picker", 'bricksforge');
    }

    public function enqueue_scripts()
    {
        wp_enqueue_script('bricksforge-elements');
        wp_enqueue_script('bricksforge-coloris');
        wp_enqueue_style('bricksforge-coloris');
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
        $this->controls = array_merge($this->controls, Helper::get_default_controls('colorPicker'));

        // Color Format
        $this->controls['colorFormat'] = [
            'group'       => 'general',
            'label'       => esc_html__('Color Format', 'bricksforge'),
            'type'        => 'select',
            'default'     => 'hex',
            'options'     => [
                'hex' => esc_html__('hex', 'bricksforge'),
                'rgba' => esc_html__('rgb', 'bricksforge'),
                'hsl' => esc_html__('hsl', 'bricksforge'),
            ],
            'description' => esc_html__('Choose the color format.', 'bricksforge'),
        ];

        // Alpha (Checkbox)
        $this->controls['alpha'] = [
            'group'       => 'general',
            'label'       => esc_html__('Alpha', 'bricksforge'),
            'type'        => 'checkbox',
            'default'     => true,
            'description' => esc_html__('Enable transparency.', 'bricksforge'),
        ];

        // Theme (default, large, polaroid, pill)
        $this->controls['theme'] = [
            'group'       => 'general',
            'label'       => esc_html__('Theme', 'bricksforge'),
            'type'        => 'select',
            'default'     => 'polaroid',
            'options'     => [
                'default' => esc_html__('Default', 'bricksforge'),
                'large' => esc_html__('Large', 'bricksforge'),
                'polaroid' => esc_html__('Polaroid', 'bricksforge'),
                'pill' => esc_html__('Pill', 'bricksforge'),
            ],
            'description' => esc_html__('Choose the theme.', 'bricksforge'),
        ];

        // Theme Mode (Light, Dark, Auto)
        $this->controls['themeMode'] = [
            'group'       => 'general',
            'label'       => esc_html__('Theme Mode', 'bricksforge'),
            'type'        => 'select',
            'default'     => 'auto',
            'options'     => [
                'auto' => esc_html__('Auto', 'bricksforge'),
                'light' => esc_html__('Light', 'bricksforge'),
                'dark' => esc_html__('Dark', 'bricksforge'),
            ],
            'description' => esc_html__('Choose the theme mode.', 'bricksforge'),
        ];

        /**
         * Style Group
         */

        // Color Preview Border
        $this->controls['colorPreviewBorder'] = [
            'group'       => 'style',
            'label'       => esc_html__('Color Preview Border', 'bricksforge'),
            'type'        => 'border',
            'css'         => [
                [
                    'property' => 'border',
                    'selector' => 'button',
                ],
            ],
        ];

        // Color Preview Width
        $this->controls['colorPreviewWidth'] = [
            'group'       => 'style',
            'label'       => esc_html__('Color Preview Width', 'bricksforge'),
            'type'        => 'number',
            'units' => true,
            'default' => 20,
            'css'         => [
                [
                    'property' => 'width',
                    'selector' => 'button',
                ],
            ],
        ];

        // Color Preview Height
        $this->controls['colorPreviewHeight'] = [
            'group'       => 'style',
            'label'       => esc_html__('Color Preview Height', 'bricksforge'),
            'type'        => 'number',
            'default' => 20,
            'units' => true,
            'css'         => [
                [
                    'property' => 'height',
                    'selector' => 'button',
                ],
            ],
        ];

        // Color Preview Top
        $this->controls['colorPreviewTop'] = [
            'group'       => 'style',
            'label'       => esc_html__('Color Preview Top', 'bricksforge'),
            'type'        => 'number',
            'units' => true,
            'css'         => [
                [
                    'property' => 'top',
                    'selector' => 'button',
                ],
            ],
        ];

        // Color Preview Right 
        $this->controls['colorPreviewRight'] = [
            'group'       => 'style',
            'label'       => esc_html__('Color Preview Right', 'bricksforge'),
            'type'        => 'number',
            'default' => 15,
            'units' => true,
            'css'         => [
                [
                    'property' => 'right',
                    'selector' => 'button',
                ],
            ],
        ];

        // Color Preview Bottom
        $this->controls['colorPreviewBottom'] = [
            'group'       => 'style',
            'label'       => esc_html__('Color Preview Bottom', 'bricksforge'),
            'type'        => 'number',
            'default' => 10,
            'units' => true,
            'css'         => [
                [
                    'property' => 'bottom',
                    'selector' => 'button',
                ],
            ],
        ];

        // Color Preview Left
        $this->controls['colorPreviewLeft'] = [
            'group'       => 'style',
            'label'       => esc_html__('Color Preview Left', 'bricksforge'),
            'type'        => 'number',
            'units' => true,
            'css'         => [
                [
                    'property' => 'left',
                    'selector' => 'button',
                ],
            ],
        ];

        $this->controls = array_merge($this->controls, Helper::get_advanced_controls());
    }

    public function render()
    {
        $settings = $this->settings;
        $parent_settings = Helper::get_nestable_parent_settings($this->element) ? Helper::get_nestable_parent_settings($this->element) : [];

        $id = $this->id ? $this->id : false;

        if (isset($settings['id']) && $settings['id']) {
            $id = $settings['id'];
        }

        $show_labels = true;
        if (isset($parent_settings) && !empty($parent_settings) && !isset($parent_settings['showLabels'])) {
            $show_labels = false;
        }

        // Single Show Label
        if (isset($settings['showLabel']) && $settings['showLabel']) {
            $show_labels = true;
        }

        $random_id = Helpers::generate_random_id(false);
        $label = isset($settings['label']) ? $settings['label'] : false;
        $placeholder = isset($settings['placeholder']) ? bricks_render_dynamic_data($settings['placeholder']) : false;
        $value = isset($settings['value']) ? bricks_render_dynamic_data($settings['value']) : '';
        $required = isset($settings['required']) ? $settings['required'] : false;

        if (!$id && bricks_is_builder()) {
            return $this->render_element_placeholder(
                [
                    'title' => esc_html__('You have to set an ID for your element.', 'bricksforge'),
                ]
            );
        }

        /**
         * Wrapper
         */
        $this->set_attribute('_root', 'class', 'pro-forms-builder-field');
        $this->set_attribute('_root', 'class', 'form-group');
        $this->set_attribute('_root', 'data-element-id', $this->id);

        // Post Context
        if (isset($settings['postContext'])) {
            $this->set_attribute('_root', 'data-context', bricks_render_dynamic_data($settings['postContext']));
        }

        if ($id !== $this->id) {
            $this->set_attribute('_root', 'data-custom-id', $id);
        }

        // Custom Css Class
        if (isset($settings['cssClass']) && $settings['cssClass']) {
            $this->set_attribute('field', 'class', $settings['cssClass']);
        }

        /**
         * Field
         */
        $this->set_attribute('field', 'type', 'text');
        $this->set_attribute('field', 'id', 'form-field-' . $random_id);
        $this->set_attribute('field', 'name', 'form-field-' . $id);
        $this->set_attribute('field', 'spellcheck', 'false');
        $this->set_attribute('field', 'data-label', $label);

        if (!bricks_is_builder() && !bricks_is_builder_call()) {
            $this->set_attribute('field', 'data-coloris');
        }

        // Value
        if ($value) {
            $this->set_attribute('field', 'value', $value);
        }

        $picker_options = [];

        // Color Format
        $picker_options['format'] = isset($settings['colorFormat']) ? $settings['colorFormat'] : 'hex';

        // Alpha
        $picker_options['alpha'] = isset($settings['alpha']) ? $settings['alpha'] : false;

        // Theme
        $picker_options['theme'] = isset($settings['theme']) ? $settings['theme'] : 'polaroid';

        // Theme Mode
        $picker_options['themeMode'] = isset($settings['themeMode']) ? $settings['themeMode'] : 'auto';

        // Default Color
        $picker_options['defaultColor'] = isset($settings['value']) ? $settings['value'] : '';

        $this->set_attribute('field', 'data-options', json_encode($picker_options));

?>
        <div <?php echo $this->render_attributes('_root'); ?>>
            <?php if ($label && $show_labels) : ?>
                <label <?php echo $this->render_attributes('label'); ?> for="form-field-<?php echo $random_id; ?>">
                    <?php echo $label; ?>
                </label>
            <?php endif; ?>
            <?php if (!bricks_is_builder() && !bricks_is_builder_call()) { ?>
                <input <?php echo $this->render_attributes('field'); ?>>
            <?php } else { ?>
                <div class="clr-field">
                    <button type="button" aria-labelledby="clr-open-label"></button>
                    <input <?php echo $this->render_attributes('field'); ?>>
                </div>
            <?php } ?>
        </div>
<?php
    }
}
