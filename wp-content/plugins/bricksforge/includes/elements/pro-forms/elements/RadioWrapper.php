<?php

namespace Bricks;

use \Bricksforge\ProForms\Helper as Helper;

if (!defined('ABSPATH'))
    exit;

class Brf_Pro_Forms_RadioWrapper extends \Bricks\Element
{

    public $category = 'bricksforge forms';
    public $name = 'brf-pro-forms-field-radio-wrapper';
    public $icon = 'fa-solid fa-circle-dot';
    public $css_selector = '';
    public $scripts = [];
    public $nestable = true;

    public function get_label()
    {
        return esc_html__("Radio Wrapper", 'bricksforge');
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

        $this->control_groups['options'] = [
            'title'    => esc_html__('Options', 'bricksforge'),
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
        $this->controls = array_merge($this->controls, Helper::get_default_controls('radio_wrapper'));

        // Flex Direction
        $this->controls['radioFlexDirection'] = [
            'group' => 'general',
            'label'          => esc_html__('Flex Direction', 'bricksforge'),
            'type'           => 'direction',
            'css'        => [
                [
                    'property' => 'flex-direction',
                    'selector' => '.options-wrapper',
                    'important' => true,
                ],
                [
                    'property' => 'display',
                    'value' => 'flex',
                    'selector' => '.options-wrapper',
                    'important' => true,
                ],
            ],
        ];

        // Align Items
        $this->controls['radioAlignItems'] = [
            'group' => 'general',
            'label'          => esc_html__('Align Items', 'bricksforge'),
            'type'           => 'align-items',
            'css'        => [
                [
                    'property' => 'align-items',
                    'selector' => '> .options-wrapper',
                    'important' => true,
                ],
                [
                    'property' => 'display',
                    'value' => 'flex',
                    'selector' => '> .options-wrapper',
                    'important' => true,
                ],
            ],
        ];

        // Column Gap
        $this->controls['radioColumnGap'] = [
            'group' => 'general',
            'label'          => esc_html__('Column Gap', 'bricksforge'),
            'type'           => 'number',
            'units' => true,
            'css' => [
                [
                    'property' => 'column-gap',
                    'selector' => '.options-wrapper',
                    'important' => true,
                ],
                [
                    'property' => 'display',
                    'value' => 'flex',
                    'selector' => '.options-wrapper',
                ],
            ],
        ];

        // Row Gap
        $this->controls['radioRowGap'] = [
            'group' => 'general',
            'label'          => esc_html__('Row Gap', 'bricksforge'),
            'type'           => 'number',
            'units' => true,
            'css' => [
                [
                    'property' => 'row-gap',
                    'selector' => '.options-wrapper',
                    'important' => true,
                ],
                [
                    'property' => 'display',
                    'value' => 'flex',
                    'selector' => '.options-wrapper',
                ],
            ],
        ];

        $this->controls = array_merge($this->controls, Helper::get_data_source_controls());
        $this->controls = array_merge($this->controls, Helper::get_condition_controls());
        $this->controls = array_merge($this->controls, Helper::get_advanced_controls());
        $this->controls = array_merge($this->controls, Helper::get_validation_controls());
    }

    public function get_nestable_children()
    {
        return [
            [
                'name'     => 'brf-pro-forms-field-radio',
                'label'    => esc_html__('Radio', 'bricksforge'),
            ]
        ];
    }

    public function render()
    {
        $settings = $this->settings;
        $parent_settings = Helper::get_nestable_parent_settings($this->element) ? Helper::get_nestable_parent_settings($this->element) : [];

        $id = $this->id ? $this->id : false;

        if (isset($settings['id']) && $settings['id']) {
            $id = $settings['id'];
        }

        $random_id = Helpers::generate_random_id(false);
        $required = isset($settings['customRequired']) ? $settings['customRequired'] : false;

        $show_labels = true;
        if (isset($parent_settings) && !empty($parent_settings) && !isset($parent_settings['showLabels'])) {
            $show_labels = false;
        }

        // Single Show Label
        if (isset($settings['showLabel']) && $settings['showLabel']) {
            $show_labels = true;
        }

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

        if ($id !== $this->id) {
            $this->set_attribute('_root', 'data-custom-id', $id);
        }

        // Custom Css Class
        if (isset($settings['cssClass']) && $settings['cssClass']) {
            $this->set_attribute('_root', 'class', $settings['cssClass']);
        }

        // Required && Required Count
        if ($required) {
            $this->set_attribute('_root', 'data-is-required', 'true');
        }

        /**
         * Parent Attributes
         */
        if (isset($parent_settings['radioCustomStyle']) && $parent_settings['radioCustomStyle']) {
            $this->set_attribute("_root", 'data-radio-custom');
        }
        $this->set_attribute('_root', 'data-field-type', 'radio');

        if (isset($parent_settings['radioCard']) && $parent_settings['radioCard']) {
            $this->set_attribute("_root", 'data-radio-card');
        }

        // Child LI
        $this->set_attribute('li', 'class', 'brxe-brf-pro-forms-field-radio');

        // Child Input
        if ($id !== $this->id) {
            $this->set_attribute('field', 'data-custom-id', $id);
        }

        $this->set_attribute('field', 'name', 'form-field-' . $id . '[]');

        // Aria Label
        if (isset($settings['label']) && $settings['label']) {
            $this->set_attribute('field', 'aria-label', $settings['label']);
        }

        // Role
        $this->set_attribute('field', 'role', 'radio');

        // Aria Checked
        $this->set_attribute('field', 'aria-checked', 'false');


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

        // Validation
        $validation = isset($settings['validation']) ? $settings['validation'] : false;
        if ($validation) {
            $this->set_attribute('_root', 'data-validation', json_encode($validation));

            if (isset($settings['enableLiveValidation']) && $settings['enableLiveValidation'] == true) {
                $this->set_attribute('_root', 'data-live-validation', 'true');
            }

            if (isset($settings['showValidationMessage']) && $settings['showValidationMessage'] == true) {
                $this->set_attribute('_root', 'data-show-validation-message', 'true');
            }

            if (isset($settings['showMessageBelowField']) && $settings['showMessageBelowField'] == true) {
                $this->set_attribute('_root', 'data-show-message-below-field', 'true');
            }
        }

        // Post Context
        if (isset($settings['postContext'])) {
            $this->set_attribute('_root', 'data-context', bricks_render_dynamic_data($settings['postContext']));
        }

?>
        <div <?php echo $this->render_attributes('_root'); ?>>
            <?php if (!empty($settings['label']) && $show_labels) : ?>
                <label <?php echo $this->render_attributes('label'); ?>>
                    <?php echo esc_html($settings['label']); ?>
                </label>
            <?php endif; ?>

            <ul class="options-wrapper">
                <?php
                $options = Helper::parse_options($settings);

                foreach ($options as $option) : ?>
                    <?php
                    $random_id = Helpers::generate_random_id(false);
                    $checked = false;

                    if (isset($option['active']) && $option['active']) {
                        $checked = true;
                    }

                    ?>
                    <li <?php echo $this->render_attributes('li'); ?>>
                        <input id='<?php echo "form-field-{$id}-{$random_id}" ?>' <?php echo $this->render_attributes('field'); ?> type="radio" value="<?php echo esc_attr($option['value']); ?>" <?php echo $checked ? 'checked' : ''; ?>>
                        <label for='<?php echo "form-field-{$id}-{$random_id}" ?>' <?php echo $this->render_attributes('label'); ?>><?php echo esc_html($option['label']); ?></label>
                    </li>
                <?php endforeach; ?>

                <?php echo Frontend::render_children($this); ?>
            </ul>
        </div>
<?php
    }
}
