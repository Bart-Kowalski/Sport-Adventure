<?php

namespace Bricks;

use \Bricksforge\ProForms\Helper as Helper;

if (!defined('ABSPATH'))
    exit;

class Brf_Pro_Forms_Honeypot extends \Bricks\Element
{

    public $category = 'bricksforge forms';
    public $name = 'brf-pro-forms-field-honeypot';
    public $icon = 'fa-solid fa-user-ninja';
    public $css_selector = '';
    public $scripts = [];
    public $nestable = false;

    public function get_label()
    {
        return esc_html__("Honeypot", 'bricksforge');
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
        // Honeypot Info
        $this->controls['honeypotInfo'] = [
            'type' => 'info',
            'content' => esc_html__('This field is hidden from users and is used to prevent spam. Just place it somewhere in your form.', 'bricksforge'),
        ];
    }

    public function render()
    {
        $settings = $this->settings;

        $id = $this->id ? $this->id : false;

        /**
         * Wrapper
         */
        $this->set_attribute('_root', 'class', ['pro-forms-builder-field', 'brf-visually-hidden']);
        $this->set_attribute('_root', 'class', 'form-group');

        /**
         * Field
         */
        $this->set_attribute('field', 'type', 'radio');
        $this->set_attribute('field', 'name', 'form-field-guardian42');
        $this->set_attribute('field', 'id', 'form-field-guardian42');
        $this->set_attribute('field', 'value', '1');

?>
        <div <?php echo $this->render_attributes('_root'); ?>>
            <label for="form-field-guardian42" aria-hidden="true">
                Guardian
                <input <?php echo $this->render_attributes('field'); ?> style="display: none">
            </label>
        </div>
<?php
    }
}
