<?php

namespace Bricks;

use \Bricksforge\ProForms\Helper as Helper;

if (!defined('ABSPATH'))
    exit;

class Brf_Pro_Forms_Hidden extends \Bricks\Element
{

    public $category = 'bricksforge forms';
    public $name = 'brf-pro-forms-field-hidden';
    public $icon = 'fa-solid fa-user-ninja';
    public $css_selector = '';
    public $scripts = [];
    public $nestable = false;

    public function get_label()
    {
        return esc_html__("Hidden", 'bricksforge');
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
    }

    public function set_controls()
    {
        $this->controls = array_merge($this->controls, Helper::get_default_controls('hidden'));

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

        $random_id = Helpers::generate_random_id(false);
        $label = isset($settings['label']) ? $settings['label'] : false;
        $placeholder = isset($settings['placeholder']) ? bricks_render_dynamic_data($settings['placeholder']) : false;
        $autocomplete = isset($settings['autocomplete']) ? $settings['autocomplete'] : 'off';
        $value = isset($settings['value']) ? bricks_render_dynamic_data($settings['value']) : '';
        $required = isset($settings['required']) ? $settings['required'] : false;
        $maxlength = isset($settings['maxlength']) ? $settings['maxlength'] : '';

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
        $this->set_attribute('field', 'type', bricks_is_builder() || bricks_is_rest_call() ? 'text' : 'hidden');
        $this->set_attribute('field', 'id', 'form-field-' . $random_id);
        $this->set_attribute('field', 'name', 'form-field-' . $id);
        $this->set_attribute('field', 'spellcheck', 'false');
        $this->set_attribute('field', 'data-label', $label);

        if ($value) {
            $this->set_attribute('field', 'value', $value);
        }

?>
        <div <?php echo $this->render_attributes('_root'); ?>>
            <input <?php echo $this->render_attributes('field'); ?>>
        </div>
<?php
    }
}
