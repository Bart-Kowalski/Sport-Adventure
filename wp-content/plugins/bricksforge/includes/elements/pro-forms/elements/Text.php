<?php

namespace Bricks;

use \Bricksforge\ProForms\Helper as Helper;

if (!defined('ABSPATH'))
    exit;

class Brf_Pro_Forms_Text extends \Bricks\Element
{

    public $category = 'bricksforge forms';
    public $name = 'brf-pro-forms-field-text';
    public $icon = 'fa-solid fa-font';
    public $css_selector = '';
    public $scripts = [];
    public $nestable = false;

    public function get_label()
    {
        return esc_html__("Text", 'bricksforge');
    }

    public function enqueue_scripts()
    {
        wp_enqueue_script('bricksforge-elements');

        if (isset($this->settings['enableInputMasking']) && $this->settings['enableInputMasking']) {
            wp_enqueue_script('bricksforge-imask');
        }
    }

    public function set_control_groups()
    {
        $this->control_groups['general'] = [
            'title'    => esc_html__('General', 'bricksforge'),
            'tab'      => 'content',
        ];
        $this->control_groups['inputMasking'] = [
            'title'    => esc_html__('Input Masking', 'bricksforge'),
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
        $this->control_groups['style'] = [
            'title'    => esc_html__('Style', 'bricksforge'),
            'tab'      => 'content',
        ];
    }

    public function set_controls()
    {

        $this->controls = array_merge($this->controls, Helper::get_default_controls('text'));

        // Placeholder
        $this->controls['placeholder'] = [
            'group' => 'general',
            'label'          => esc_html__('Placeholder', 'bricksforge'),
            'type'           => 'text',
            'inline'         => true,
            'spellcheck'     => false,
            'hasDynamicData' => true,
        ];

        // Autocomplete
        $this->controls['autocomplete'] = [
            'group' => 'general',
            'label' => esc_html__('Autocomplete', 'bricksforge'),
            'type'  => 'select',
            'default' => 'off',
            'options' => Helper::get_autocomplete_options(),
            'description' => esc_html__('If checked, you allow the browser to autocomplete the value.', 'bricksforge'),
        ];

        // Strip HTML
        $this->controls['stripHTML'] = [
            'group' => 'general',
            'label' => esc_html__('Strip HTML', 'bricksforge'),
            'type'  => 'checkbox',
            'default' => false,
            'description' => esc_html__('If checked, all HTML tags will be stripped from the output. By default, not dangerous tags are allowed.', 'bricksforge'),
        ];

        // Max Length
        $this->controls['maxlength'] = [
            'group' => 'general',
            'label'          => esc_html__('Max Length', 'bricksforge'),
            'type'           => 'number',
            'inline'         => true,
            'spellcheck'     => false,
            'hasDynamicData' => true,
            'default'        => '',
        ];

        $this->controls = array_merge($this->controls, Helper::get_condition_controls());
        $this->controls = array_merge($this->controls, Helper::get_advanced_controls());
        $this->controls = array_merge($this->controls, Helper::get_validation_controls());
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

        $label = isset($settings['label']) ? $settings['label'] : false;
        $show_labels = true;

        if (isset($parent_settings) && !empty($parent_settings) && !isset($parent_settings['showLabels'])) {
            $show_labels = false;
        }

        // Single Show Label
        if (isset($settings['showLabel']) && $settings['showLabel']) {
            $show_labels = true;
        }

        $placeholder = isset($settings['placeholder']) ? bricks_render_dynamic_data($settings['placeholder']) : false;
        $autocomplete = isset($settings['autocomplete']) ? $settings['autocomplete'] : 'off';
        $value = isset($settings['value']) ? bricks_render_dynamic_data($settings['value']) : '';
        $stripHTML = isset($settings['stripHTML']) ? $settings['stripHTML'] : false;
        $maxlength = isset($settings['maxlength']) ? $settings['maxlength'] : '';
        $required = isset($settings['required']) ? $settings['required'] : false;
        $pattern = isset($settings['pattern']) ? $settings['pattern'] : '';

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

        $disabled = isset($settings['disabled']) ? $settings['disabled'] : false;
        if ($disabled) {
            $this->set_attribute('field', 'disabled', 'disabled');
        }

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

        // Input Masking
        if (isset($settings['enableInputMasking']) && $settings['enableInputMasking']) {
            $input_mask_options = [];

            // inputMaskingMethod
            if (isset($settings['inputMaskingMethod']) && $settings['inputMaskingMethod']) {
                $input_mask_options['method'] = $settings['inputMaskingMethod'];
            }

            // maskPattern
            if (isset($settings['maskPattern']) && $settings['maskPattern']) {
                $input_mask_options['maskPattern'] = $settings['maskPattern'];
            }

            // maskDefinitions
            if (isset($settings['maskDefinitions']) && $settings['maskDefinitions']) {
                $input_mask_options['definitions'] = $settings['maskDefinitions'];
            }

            // lazyMasking
            if (isset($settings['lazyMasking']) && $settings['lazyMasking']) {
                $input_mask_options['lazy'] = true;
            }

            $this->set_attribute('field', 'data-input-mask', json_encode($input_mask_options));
        }

        if ($placeholder) {
            $this->set_attribute('field', 'placeholder', $placeholder);
        }
        if ($autocomplete) {
            $this->set_attribute('field', 'autocomplete', $autocomplete);
        }
        if ($value) {
            $this->set_attribute('field', 'value', $value);
        }
        if ($maxlength) {
            $this->set_attribute('field', 'maxlength', $maxlength);
        }
        if ($required) {
            $this->set_attribute('field', 'required', $required);
        }

        if ($pattern) {
            $this->set_attribute('field', 'pattern', $pattern);
        }

        // Conditions
        if (isset($settings['hasConditions']) && isset($settings['conditions']) && $settings['conditions']) {
            $this->set_attribute('_root', 'data-brf-conditions', json_encode($settings['conditions']));
        }
        if (isset($settings['conditionsRelation']) && $settings['conditionsRelation']) {
            $this->set_attribute('_root', 'data-brf-conditions-relation', $settings['conditionsRelation']);
        }

        // Icons
        if (isset($settings['icon'])) {
            $this->set_attribute("field-icons", 'class', 'input-icon-wrapper');
            $this->set_attribute("field-icons", 'class', isset($parent_settings['iconPosition']) && $parent_settings['iconPosition'] == 'row' ? 'icon-left' : 'icon-right');

            if (isset($parent_settings['iconInset']) && $parent_settings['iconInset'] == true) {
                $this->set_attribute("field-icons", 'class', 'icon-inset');
            }

            if (isset($parent_settings['iconFocusInput']) && $parent_settings['iconFocusInput'] == true) {
                $this->set_attribute("field-icons", 'data-focus', 'true');
            }
        }

        // Required Asterisk
        if (isset($parent_settings['requiredAsterisk']) && $parent_settings['requiredAsterisk'] == true && $required) {
            $this->set_attribute("label", 'class', 'required');
        }

?>
        <div <?php echo $this->render_attributes('_root'); ?>>
            <?php if ($label && $show_labels) : ?>
                <label <?php echo $this->render_attributes('label'); ?> for="form-field-<?php echo $random_id; ?>">
                    <?php echo $label; ?>
                </label>
            <?php endif; ?>
            <?php if (isset($settings['icon'])) { ?>
                <div <?php echo $this->render_attributes("field-icons"); ?>>
                    <span class="input-icon"><?php echo $this->render_icon($settings['icon']) ?></span>
                    <input <?php echo $this->render_attributes('field'); ?>>
                </div>
            <?php } else { ?>
                <input <?php echo $this->render_attributes('field'); ?>>
            <?php } ?>
        </div>
<?php
    }
}
