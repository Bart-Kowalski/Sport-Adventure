<?php

namespace Bricks;

use \Bricksforge\ProForms\Helper as Helper;

if (!defined('ABSPATH'))
    exit;

class Brf_Pro_Forms_Signature extends \Bricks\Element
{

    public $category = 'bricksforge forms';
    public $name = 'brf-pro-forms-field-signature';
    public $icon = 'fa-solid fa-signature';
    public $css_selector = '';
    public $scripts = [];
    public $nestable = false;

    public function get_label()
    {
        return esc_html__("Signature", 'bricksforge');
    }

    public function enqueue_scripts()
    {
        wp_enqueue_script('bricksforge-elements');
        wp_enqueue_script('bricksforge-signature-pad');
    }

    public function set_control_groups()
    {
        $this->control_groups['general'] = [
            'title'    => esc_html__('General', 'bricksforge'),
            'tab'      => 'content',
        ];
        $this->control_groups['canvas'] = [
            'title'    => esc_html__('Canvas', 'bricksforge'),
            'tab'      => 'content',
        ];
        $this->control_groups['actions'] = [
            'title'    => esc_html__('Actions', 'bricksforge'),
            'tab'      => 'content',
        ];
        $this->control_groups['conditions'] = [
            'title'    => esc_html__('Conditions', 'bricksforge'),
            'tab'      => 'content',
        ];
        $this->control_groups['validation'] = [
            'title'    => esc_html__('Validation', 'bricksforge'),
            'tab'      => 'content',
        ];
    }

    public function set_controls()
    {
        $this->controls = array_merge($this->controls, Helper::get_default_controls('signature'));

        // Upload Location (Media Library, Custom)
        $this->controls['uploadLocation'] = [
            'group' => 'general',
            'label'          => esc_html__('Upload Location', 'bricksforge'),
            'type'           => 'select',
            'default'        => 'mediaLibrary',
            'options'        => [
                'mediaLibrary' => esc_html__('Media Library', 'bricksforge'),
                'custom' => esc_html__('Custom', 'bricksforge'),
            ],
            'description'    => esc_html__('"Custom" will not create any data records in the media library. This is safer, but will not work with the "File Upload" meta fields of providers like ACF or Metabox, which require an attachment ID instead of a file URL.', 'bricksforge'),
        ];

        // If Custom, Upload Directory (Relative to wp-content/uploads)
        $this->controls['uploadDirectory'] = [
            'group' => 'general',
            'label'          => esc_html__('Upload Directory', 'bricksforge'),
            'type'           => 'text',
            'default'        => 'bricksforge',
            'placeholder'    => 'my-location',
            'description'    => esc_html__('The directory where the uploaded files will be stored. Relative to wp-content/uploads. Example: my-location. This will create a folder wp-content/uploads/my-location', 'bricksforge'),
            'required'       => [
                ['uploadLocation', '=', 'custom']
            ]
        ];

        // Canvas Info
        $this->controls['info'] = [
            'group' => 'canvas',
            'type'           => 'info',
            'content'        => esc_html__('CSS Variables are not supported here. Please use regular CSS values.', 'bricksforge'),
        ];

        // Canvas Height
        $this->controls['canvasHeight'] = [
            'group' => 'canvas',
            'label'          => esc_html__('Canvas Height', 'bricksforge'),
            'type'           => 'number',
            'units' => true,
            'default' => 200,
            'description'    => esc_html__('The height of the canvas. Default: 200', 'bricksforge'),
            'css' => [
                [
                    'selector' => '.brf-signature__pad',
                    'property' => 'height',
                ]
            ],
        ];

        // Dot Size
        $this->controls['dotSize'] = [
            'group' => 'canvas',
            'label'          => esc_html__('Dot Size', 'bricksforge'),
            'type'           => 'number',
            'description'    => esc_html__('The radius of a single dot', 'bricksforge'),
        ];

        // Min Width
        $this->controls['minWidth'] = [
            'group' => 'canvas',
            'label'          => esc_html__('Min Width', 'bricksforge'),
            'type'           => 'number',
            'description'    => esc_html__('The minimum width of a line. Default: 0.5', 'bricksforge'),
        ];

        // Max Width
        $this->controls['maxWidth'] = [
            'group' => 'canvas',
            'label'          => esc_html__('Max Width', 'bricksforge'),
            'type'           => 'number',
            'description'    => esc_html__('The maximum width of a line. Default: 2.5', 'bricksforge'),
        ];

        // Background Color
        $this->controls['backgroundColor'] = [
            'group' => 'canvas',
            'label'          => esc_html__('Background Color', 'bricksforge'),
            'type'           => 'color',
            'description'    => esc_html__('The color of the background', 'bricksforge'),
            'css' => [
                [
                    'selector' => '.brf-signature__pad',
                    'property' => 'background-color'
                ]
            ]
        ];

        // Border
        $this->controls['border'] = [
            'group' => 'canvas',
            'label'          => esc_html__('Border', 'bricksforge'),
            'type'           => 'border',
            'description'    => esc_html__('The border of the canvas', 'bricksforge'),
            'css' => [
                [
                    'selector' => '.brf-signature__pad',
                    'property' => 'border'
                ]
            ]
        ];

        // Pen Color
        $this->controls['penColor'] = [
            'group' => 'canvas',
            'label'          => esc_html__('Pen Color', 'bricksforge'),
            'type'           => 'color',
            'description'    => esc_html__('The color of the pen', 'bricksforge'),
        ];

        // throttle
        $this->controls['throttle'] = [
            'group' => 'canvas',
            'label'          => esc_html__('Throttle', 'bricksforge'),
            'type'           => 'number',
            'description'    => esc_html__('The time interval between each point. Default: 16', 'bricksforge'),
        ];

        // Actions Wrapper Flex Direction
        $this->controls['actionsWrapperFlexDirection'] = [
            'group' => 'actions',
            'label'          => esc_html__('Actions Wrapper Flex Direction', 'bricksforge'),
            'type'           => 'direction',
            'description'    => esc_html__('The flex direction of the actions wrapper', 'bricksforge'),
            'css' => [
                [
                    'selector' => '.brf-signature__actions',
                    'property' => 'flex-direction'
                ]
            ]
        ];

        // Actions Wrapper Justify Content
        $this->controls['actionsWrapperJustifyContent'] = [
            'group' => 'actions',
            'label'          => esc_html__('Actions Wrapper Justify Content', 'bricksforge'),
            'type'           => 'justify-content',
            'description'    => esc_html__('The justify content of the actions wrapper', 'bricksforge'),
            'css' => [
                [
                    'selector' => '.brf-signature__actions',
                    'property' => 'justify-content'
                ]
            ]
        ];

        // Actions Wrapper Margin
        $this->controls['actionsWrapperMargin'] = [
            'group' => 'actions',
            'label'          => esc_html__('Actions Wrapper Margin', 'bricksforge'),
            'type'           => 'dimensions',
            'description'    => esc_html__('The margin of the actions wrapper', 'bricksforge'),
            'css' => [
                [
                    'selector' => '.brf-signature__actions',
                    'property' => 'margin'
                ]
            ]
        ];

        // Clear Button
        $this->controls['clearButton'] = [
            'group' => 'actions',
            'label'          => esc_html__('Show Clear Button', 'bricksforge'),
            'type'           => 'checkbox',
            'default'        => false,
            'description'    => esc_html__('Show or hide the clear button', 'bricksforge'),
        ];

        // Clear Button Text
        $this->controls['clearButtonText'] = [
            'group' => 'actions',
            'label'          => esc_html__('Clear Button Text', 'bricksforge'),
            'type'           => 'text',
            'default'        => esc_html__('Clear', 'bricksforge'),
            'description'    => esc_html__('The text of the clear button', 'bricksforge'),
            'required'       => [
                ['clearButton', '=', true]
            ]
        ];

        // Clear Button Typography
        $this->controls['clearButtonTypography'] = [
            'group' => 'actions',
            'label'          => esc_html__('Clear Button Typography', 'bricksforge'),
            'type'           => 'typography',
            'description'    => esc_html__('The typography of the clear button', 'bricksforge'),
            'css' => [
                [
                    'selector' => '.brf-signature__clear',
                    'property' => 'typography'
                ]
            ],
            'required'       => [
                ['clearButton', '=', true]
            ]
        ];

        // Clear Button Color
        $this->controls['clearButtonColor'] = [
            'group' => 'actions',
            'label'          => esc_html__('Clear Button Color', 'bricksforge'),
            'type'           => 'color',
            'description'    => esc_html__('The color of the clear button', 'bricksforge'),
            'css' => [
                [
                    'selector' => '.brf-signature__clear',
                    'property' => 'color'
                ]
            ],
            'required'       => [
                ['clearButton', '=', true]
            ]
        ];

        // Clear Button Background Color
        $this->controls['clearButtonBackgroundColor'] = [
            'group' => 'actions',
            'label'          => esc_html__('Clear Button Background Color', 'bricksforge'),
            'type'           => 'color',
            'description'    => esc_html__('The background color of the clear button', 'bricksforge'),
            'css' => [
                [
                    'selector' => '.brf-signature__clear',
                    'property' => 'background-color'
                ]
            ],
            'required'       => [
                ['clearButton', '=', true]
            ]
        ];

        // Clear Button Border
        $this->controls['clearButtonBorder'] = [
            'group' => 'actions',
            'label'          => esc_html__('Clear Button Border', 'bricksforge'),
            'type'           => 'border',
            'description'    => esc_html__('The border of the clear button', 'bricksforge'),
            'css' => [
                [
                    'selector' => '.brf-signature__clear',
                    'property' => 'border'
                ]
            ],
            'required'       => [
                ['clearButton', '=', true]
            ]
        ];

        // Clear Button Padding
        $this->controls['clearButtonPadding'] = [
            'group' => 'actions',
            'label'          => esc_html__('Clear Button Padding', 'bricksforge'),
            'type'           => 'dimensions',
            'description'    => esc_html__('The padding of the clear button', 'bricksforge'),
            'css' => [
                [
                    'selector' => '.brf-signature__clear',
                    'property' => 'padding'
                ]
            ],
            'required'       => [
                ['clearButton', '=', true]
            ]
        ];

        // Clear Button Margin
        $this->controls['clearButtonMargin'] = [
            'group' => 'actions',
            'label'          => esc_html__('Clear Button Margin', 'bricksforge'),
            'type'           => 'dimensions',
            'description'    => esc_html__('The margin of the clear button', 'bricksforge'),
            'css' => [
                [
                    'selector' => '.brf-signature__clear',
                    'property' => 'margin'
                ]
            ],
            'required'       => [
                ['clearButton', '=', true]
            ]
        ];

        $this->controls = array_merge($this->controls, Helper::get_condition_controls());
        $this->controls = array_merge($this->controls, Helper::get_advanced_controls());
        $this->controls = array_merge($this->controls, Helper::get_validation_controls());
    }

    public function render()
    {
        $settings = $this->settings;
        $parent_settings = Helper::get_nestable_parent_settings($this->element) ? Helper::get_nestable_parent_settings($this->element) : [];
        $random_id = Helpers::generate_random_id(false);
        $id = $this->id ? $this->id : false;

        if (isset($settings['id']) && $settings['id']) {
            $id = $settings['id'];
        }
        $label = isset($settings['label']) ? $settings['label'] : false;
        $required = isset($settings['required']) ? $settings['required'] : false;

        $show_labels = true;
        if (isset($parent_settings) && !empty($parent_settings) && !isset($parent_settings['showLabels'])) {
            $show_labels = false;
        }

        // Single Show Label
        if (isset($settings['showLabel']) && $settings['showLabel']) {
            $show_labels = true;
        }

        $value = isset($settings['value']) ? bricks_render_dynamic_data($settings['value']) : '';

        $canvas_height = isset($settings['canvasHeight']) ? $settings['canvasHeight'] : 200;
        $show_clear_button = isset($settings['clearButton']) ? $settings['clearButton'] : false;
        $clear_button_text = isset($settings['clearButtonText']) ? $settings['clearButtonText'] : esc_html__('Clear', 'bricksforge');

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

        if (isset($settings['cssClass']) && !empty($settings['cssClass'])) {
            $this->set_attribute('_root', 'class', $settings['cssClass']);
        }

        // Conditions
        if (isset($settings['hasConditions']) && isset($settings['conditions']) && $settings['conditions']) {
            $this->set_attribute('_root', 'data-brf-conditions', json_encode($settings['conditions']));
        }
        if (isset($settings['conditionsRelation']) && $settings['conditionsRelation']) {
            $this->set_attribute('_root', 'data-brf-conditions-relation', $settings['conditionsRelation']);
        }

        // Required Asterisk
        if (isset($parent_settings['requiredAsterisk']) && $parent_settings['requiredAsterisk'] == true && $required) {
            $this->set_attribute("label", 'class', 'required');
        }

        /**
         * Field
         */
        $this->set_attribute('field', 'type', 'text');
        $this->set_attribute('field', 'id', 'form-field-' . $id);
        $this->set_attribute('field', 'name', 'form-field-' . $id);
        $this->set_attribute('field', 'data-label', $label);

        if ($required) {
            $this->set_attribute('field', 'required', $required);
        }

        // Settings
        $signature_settings = [
            "dotSize" => isset($settings["dotSize"]) ? floatval($settings["dotSize"]) : 1,
            "minWidth" => isset($settings["minWidth"]) ? floatval($settings["minWidth"]) : 0.5,
            "maxWidth" => isset($settings["maxWidth"]) ? floatval($settings["maxWidth"]) : 2.5,
            "backgroundColor" => isset($settings["backgroundColor"]) ? Assets::generate_css_color($settings["backgroundColor"]) : "#fff",
            "penColor" => isset($settings["penColor"]) ? Assets::generate_css_color($settings["penColor"]) : "#000",
            "throttle" => isset($settings["throttle"]) ? intval($settings["throttle"]) : 16,
        ];

        // Validation
        $validation = isset($settings['validation']) ? $settings['validation'] : false;

        if ($validation) {
            $this->set_attribute('field', 'data-validation', json_encode($validation));

            if (isset($settings['enableLiveValidation']) && $settings['enableLiveValidation'] == true) {
                $this->set_attribute('field', 'data-live-validation', 'true');
            }

            if (isset($settings['showValidationMessage']) && $settings['showValidationMessage'] == true) {
                $this->set_attribute('field', 'data-show-validation-message', 'true');
            }

            if (isset($settings['showMessageBelowField']) && $settings['showMessageBelowField'] == true) {
                $this->set_attribute('field', 'data-show-message-below-field', 'true');
            }
        }

?>
        <div <?php echo $this->render_attributes('_root'); ?>>
            <?php if ($label && $show_labels) : ?>
                <label <?php echo $this->render_attributes('label'); ?> for="form-field-<?php echo $random_id; ?>">
                    <?php echo $label; ?>
                </label>
            <?php endif; ?>
            <canvas data-settings='<?php echo json_encode($signature_settings) ?>' id="brf-signature-pad-<?php echo $random_id; ?>" class="brf-signature__pad" width="400" height="<?php echo $canvas_height; ?>"></canvas>
            <div class="brf-signature__actions">
                <?php if ($show_clear_button) : ?>
                    <button onclick="BrfProForms.clearSignature(event)" class="brf-signature__clear" type="button"><?php esc_html_e("{$clear_button_text}", 'bricksforge'); ?></button>
                <?php endif; ?>
            </div>
            <input type="hidden" <?php echo $this->render_attributes('field'); ?>>
        </div>
        <?php
        ?>
<?php
    }
}
