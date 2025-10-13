<?php

namespace Bricks;

use \Bricksforge\ProForms\Helper as Helper;
use Bricksforge\Api\FormsHelper as FormsHelper;

if (!defined('ABSPATH'))
    exit;

class Brf_Pro_Forms_SubmitButton extends \Bricks\Element
{
    private $forms_helper;

    public $category = 'bricksforge forms';
    public $name = 'brf-pro-forms-field-submit-button';
    public $icon = 'fa-solid fa-share';
    public $css_selector = '';
    public $scripts = [];
    public $nestable = false;

    public function get_label()
    {
        return esc_html__("Submit Button", 'bricksforge');
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
        $this->control_groups['style'] = [
            'title'    => esc_html__('Style', 'bricksforge'),
            'tab'      => 'content',
        ];
    }

    public function set_controls()
    {
        $this->controls['label'] = [
            'group' => 'general',
            'label'          => esc_html__('Label', 'bricksforge'),
            'type'           => 'text',
            'inline'         => true,
            'spellcheck'     => false,
            'hasDynamicData' => true,
            'default'        => esc_html__('Label', 'bricksforge'),
            'default'   => 'Submit'
        ];

        // Icon
        $this->controls['submitButtonIcon'] = [
            'group' => 'general',
            'label'          => esc_html__('Icon', 'bricksforge'),
            'type'           => 'icon',
            'inline'         => true,
            'hasDynamicData' => true,
            'default'        => '',
        ];

        // Icon Position (Left, Right)
        $this->controls['submitButtonIconPosition'] = [
            'group' => 'general',
            'label'          => esc_html__('Icon Position', 'bricksforge'),
            'type'           => 'select',
            'inline'         => true,
            'hasDynamicData' => true,
            'default'        => 'left',
            'options'        => [
                'left'  => esc_html__('Left', 'bricksforge'),
                'right' => esc_html__('Right', 'bricksforge'),
            ],
        ];

        $this->controls['submitButtonSize'] = [
            'tab'     => 'content',
            'group'   => 'general',
            'label'   => esc_html__('Size', 'bricks'),
            'type'    => 'select',
            'inline'  => true,
            'options' => $this->control_options['buttonSizes'],
        ];

        $this->controls['submitButtonStyle'] = [
            'tab'         => 'content',
            'group'       => 'general',
            'label'       => esc_html__('Style', 'bricks'),
            'type'        => 'select',
            'inline'      => true,
            'options'     => $this->control_options['styles'],
            'default'     => 'primary',
            'placeholder' => esc_html__('Custom', 'bricks'),
        ];

        $this->controls['loadingIcon'] = [
            'group' => 'general',
            'label'          => esc_html__('Loading Icon', 'bricksforge'),
            'type'           => 'icon',
        ];

        // Loading Icon Transform
        $this->controls['loadingIconTransform'] = [
            'group' => 'style',
            'label'          => esc_html__('Loading Icon Transform', 'bricksforge'),
            'type'           => 'transform',
            'default'        => '',
            'css' => [
                [
                    'property' => 'transform',
                    'selector' => '.loading i',
                ],
                [
                    'property' => 'transform',
                    'selector' => '.loading svg',
                ],
            ],
        ];

        // Loading Icon Color
        $this->controls['loadingIconColor'] = [
            'group' => 'style',
            'label'          => esc_html__('Loading Icon Color', 'bricksforge'),
            'type'           => 'color',
            'default'        => '',
            'css' => [
                [
                    'property' => 'color',
                    'selector' => '.loading i',
                ],
                [
                    'property' => 'fill',
                    'selector' => '.loading svg',
                ],
            ],
        ];

        $this->controls = array_merge($this->controls, Helper::get_advanced_controls());
        $this->controls = array_merge($this->controls, Helper::get_button_style_controls());
    }

    public function render()
    {
        $this->forms_helper = new FormsHelper();

        $settings = $this->settings;
        $parent_settings = Helper::get_nestable_parent_settings($this->element) ? Helper::get_nestable_parent_settings($this->element) : [];

        $submit_button_classes = [];
        $submit_button_icon_position = isset($settings['submitButtonIconPosition']) ? $settings['submitButtonIconPosition'] : 'left';
        $submit_button_icon = isset($settings['submitButtonIcon']) ? \Bricks\Element::render_icon($settings['submitButtonIcon']) : false;

        $loading_svg;

        if (version_compare(BRICKS_VERSION, '1.8.1', '<')) {
            $loading_svg = Helpers::get_file_contents(BRICKS_PATH_ASSETS . 'svg/frontend/reload.svg');
        } else {
            $loading_svg = Helpers::file_get_contents(BRICKS_PATH_ASSETS . 'svg/frontend/reload.svg');
        }

        // If has a custom loading icon, we use this
        if (!empty($settings['loadingIcon'])) {
            $loading_svg = self::render_icon($settings['loadingIcon']);
        }

        if (!empty($parent_settings['submitButtonIcon'])) {
            $submit_button_classes[] = "icon-$submit_button_icon_position";
        }

        // Attributes
        $this->set_attribute('_root', 'class', ['form-group', 'submit-button-wrapper']);

        // If submitButtonConditionAction is "disabled", add the "disabled" attribute to the submit button
        if (!empty($parent_settings['submitButtonConditionAction']) && $parent_settings['submitButtonConditionAction'] === 'disabled') {
            $this->set_attribute('_root', 'disabled');
        }

        $submit_button_classes[] = 'bricks-button';

        if (!empty($settings['submitButtonStyle'])) {
            $submit_button_classes[] = "bricks-background-{$settings['submitButtonStyle']}";
        }

        if (!empty($settings['submitButtonSize'])) {
            $submit_button_classes[] = $settings['submitButtonSize'];
        }

        if (isset($parent_settings['submitButtonCircle'])) {
            $submit_button_classes[] = 'circle';
        }

        if (!empty($parent_settings['submitButtonIcon'])) {
            $submit_button_classes[] = "icon-$submit_button_icon_position";
        }

        if (isset($settings['cssClass']) && !empty($settings['cssClass'])) {
            $this->set_attribute('submit-button', 'class', $settings['cssClass']);
        }

        $this->set_attribute('submit-button', 'class', $submit_button_classes);

        $output = '<div ' . $this->render_attributes('_root') . '>';
        $output .= '<button ' . $this->render_attributes('submit-button') . ' type="submit">';

        if ($submit_button_icon_position === 'left' && $submit_button_icon) {
            $output .= $submit_button_icon;
        }

        if (!isset($parent_settings['submitButtonIcon']) || (isset($parent_settings['submitButtonIcon']) && isset($settings['label']))) {
            $this->set_attribute('submitButtonText', 'class', 'text');

            $submit_button_text = isset($settings['label']) && $settings['label'] ? $settings['label'] : '';

            $output .= "<span {$this->render_attributes('submitButtonText')}>$submit_button_text</span>";
        }

        $output .= '<span class="loading">' . $loading_svg . '</span>';

        if ($submit_button_icon_position === 'right' && $submit_button_icon) {
            $output .= $submit_button_icon;
        }

        $output .= '</button>';
        $output .= '</div>';

        echo $output;
?>
    <?php
    }
}
