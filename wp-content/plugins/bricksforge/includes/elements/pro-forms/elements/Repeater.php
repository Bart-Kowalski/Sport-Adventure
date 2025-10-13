<?php

namespace Bricks;

use \Bricksforge\ProForms\Helper as Helper;

if (!defined('ABSPATH'))
    exit;

class Brf_Pro_Forms_Repeater extends \Bricks\Element
{

    public $category = 'bricksforge forms';
    public $name = 'brf-pro-forms-field-repeater';
    public $icon = 'fa-solid fa-repeat';
    public $css_selector = '';
    public $scripts = [];
    public $nestable = true;

    public function get_label()
    {
        return esc_html__("Repeater", 'bricksforge');
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
        $this->control_groups['data'] = [
            'title'    => esc_html__('Data', 'bricksforge'),
            'tab'      => 'content',
        ];
        $this->control_groups['repeaterItem'] = [
            'title'    => esc_html__('Repeater Item', 'bricksforge'),
            'tab'      => 'content',
        ];

        $this->control_groups['buttons'] = [
            'title'    => esc_html__('Buttons', 'bricksforge'),
            'tab'      => 'content',
        ];

        $this->control_groups['conditions'] = [
            'title'    => esc_html__('Conditions', 'bricksforge'),
            'tab'      => 'content',
        ];
    }

    public function set_controls()
    {
        // Info
        $this->controls['infoStyle'] = [
            'label'          => esc_html__('Important', 'bricksforge'),
            'description'    => esc_html__('Styles inside repeater children needs to be added using global classes. ID Level styles will not work.', 'bricksforge'),
            'type'           => 'info',
        ];

        // ID
        $this->controls['id'] = [
            'group' => 'general',
            'label'          => esc_html__('Custom ID', 'bricksforge'),
            'description'    => esc_html__('Add a custom ID to the repeater', 'bricksforge'),
            'type'           => 'text',
            'inline'         => true,
            'spellcheck'     => false,
            'hasDynamicData' => true,
            'default' => \Bricks\Helpers::generate_random_id(false)
        ];

        // Label
        $this->controls['label'] = [
            'group' => 'general',
            'label'          => esc_html__('Label', 'bricksforge'),
            'type'           => 'text',
            'inline'         => true,
            'spellcheck'     => false,
            'hasDynamicData' => true,
            'default'        => esc_html__('Label', 'bricksforge'),
        ];

        // Hide Label
        $this->controls['showLabel'] = [
            'group' => 'general',
            'label'          => esc_html__('Show Label', 'bricksforge'),
            'type'           => 'checkbox',
            'default'        => true,
        ];

        // Tag name
        $this->controls['tag_name'] = [
            'label'          => esc_html__('Tag Name', 'bricksforge'),
            'group'          => 'general',
            'type'           => 'text',
            'default'        => 'div',
        ];

        // Group: Repeater Item

        // Padding
        $this->controls['padding'] = [
            'label'          => esc_html__('Padding', 'bricksforge'),
            'group'          => 'repeaterItem',
            'type'           => 'dimensions',
            'css'      => [
                [
                    'selector' => '.brf-repeater-item',
                    'property' => 'padding',
                ]
            ]
        ];

        // Margin
        $this->controls['margin'] = [
            'label'          => esc_html__('Margin', 'bricksforge'),
            'group'          => 'repeaterItem',
            'type'           => 'dimensions',
            'css'      => [
                [
                    'selector' => '.brf-repeater-item',
                    'property' => 'margin',
                ]
            ]
        ];

        // Background Color
        $this->controls['background_color'] = [
            'label'          => esc_html__('Background Color', 'bricksforge'),
            'group'          => 'repeaterItem',
            'type'           => 'color',
            'css'      => [
                [
                    'selector' => '.brf-repeater-item',
                    'property' => 'background-color',
                ]
            ]
        ];

        // Info
        $this->controls['info'] = [
            'label'          => esc_html__('Information', 'bricksforge'),
            'description'    => esc_html__('The Form Field IDs of your Repeater Childs must match the names of the fields in your ACF/Metabox/ACPT/JetEngine Repeater.', 'bricksforge'),
            'type'           => 'info',
            'group'          => 'data',
        ];

        // Populate Data from
        $this->controls['dataSource'] = [
            'group' => 'data',
            'label'          => esc_html__('Data Source', 'bricksforge'),
            'type'           => 'select',
            'options'        => [
                'acf' => esc_html__('ACF Repeater', 'bricksforge'),
                'metabox' => esc_html__('Metabox Repeater', 'bricksforge'),
                'jetengine' => esc_html__('JetEngine Repeater', 'bricksforge'),
                'acpt' => esc_html__('ACPT Repeater', 'bricksforge'),
            ],
        ];

        // Post/User ID
        $this->controls['postId'] = [
            'group' => 'data',
            'label'          => esc_html__('Post ID', 'bricksforge'),
            'type'           => 'text',
        ];

        // If is ACPT, we need also the Box Name
        $this->controls['boxName'] = [
            'group' => 'data',
            'label'          => esc_html__('Box Name', 'bricksforge'),
            'type'           => 'text',
            'required' => [
                ['dataSource', '=', 'acpt']
            ]
        ];

        // Field Name
        $this->controls['fieldName'] = [
            'group' => 'data',
            'label'          => esc_html__('Field Name', 'bricksforge'),
            'type'           => 'text',
        ];

        // Buttons Group

        // Plus Button Color
        $this->controls['plusButtonColor'] = [
            'label'          => esc_html__('Plus Button Color', 'bricksforge'),
            'group'          => 'buttons',
            'type'           => 'color',
            'css'      => [
                [
                    'selector' => 'button.brf-repeater-add-item svg',
                    'property' => 'fill',
                ]
            ]
        ];

        // Minus Button Color
        $this->controls['minusButtonColor'] = [
            'label'          => esc_html__('Minus Button Color', 'bricksforge'),
            'group'          => 'buttons',
            'type'           => 'color',
            'css'      => [
                [
                    'selector' => 'button.brf-repeater-remove-item svg',
                    'property' => 'fill',
                ]
            ]
        ];

        // Buttons Size
        $this->controls['buttonsSize'] = [
            'label'          => esc_html__('Size', 'bricksforge'),
            'group'          => 'buttons',
            'type'           => 'number',
            'units' => true,
            'css'      => [
                [
                    'selector' => 'button.brf-repeater-add-item, button.brf-repeater-remove-item',
                    'property' => 'font-size',
                ],
            ]
        ];

        $this->controls = array_merge($this->controls, Helper::get_condition_controls());
    }

    public function get_nestable_children()
    {
        return [
            [
                'name'     => 'brf-pro-forms-field-text',
                'label'    => esc_html__('Text', 'bricksforge'),
            ]
        ];
    }

    public function render()
    {
        $settings = $this->settings;
        $parent_settings = Helper::get_nestable_parent_settings($this->element) ? Helper::get_nestable_parent_settings($this->element) : [];
        $tag_name = isset($settings['tag_name']) ? $settings['tag_name'] : 'div';

        // ID
        $id = $this->id ? $this->id : false;

        if (isset($settings['id']) && $settings['id']) {
            $id = $settings['id'];
        }

        // Labels
        $show_labels = true;
        if (isset($parent_settings) && !empty($parent_settings) && !isset($parent_settings['showLabels'])) {
            $show_labels = false;
        }

        // Single Show Label
        if (isset($settings['showLabel']) && $settings['showLabel']) {
            $show_labels = true;
        }

        // Conditions
        if (isset($settings['hasConditions']) && isset($settings['conditions']) && $settings['conditions']) {
            $this->set_attribute('_root', 'data-brf-conditions', json_encode($settings['conditions']));
        }
        if (isset($settings['conditionsRelation']) && $settings['conditionsRelation']) {
            $this->set_attribute('_root', 'data-brf-conditions-relation', $settings['conditionsRelation']);
        }

        /**
         * Wrapper
         */
        $this->set_attribute('_root', 'class', 'brf-repeater-wrapper');
        $this->set_attribute('_root', 'class', 'pro-forms-builder-field');
        $this->set_attribute('_root', 'class', 'form-group');
        $this->set_attribute('_root', 'data-element-id', $this->id);

        if (isset($settings['label'])) {
            $this->set_attribute('_root', 'data-label', esc_html($settings['label']));
        }

        // Data Source
        if (isset($settings['dataSource']) && $settings['dataSource'] && isset($settings['fieldName']) && $settings['fieldName']) {
            $data = [];

            $post_id = isset($settings['postId']) ? bricks_render_dynamic_data($settings['postId']) : get_the_ID();
            $box_name = isset($settings['boxName']) ? bricks_render_dynamic_data($settings['boxName']) : '';
            $field_name = isset($settings['fieldName']) ? bricks_render_dynamic_data($settings['fieldName']) : '';

            if ($post_id) {
                $post_id = intval($post_id);
            }

            switch ($settings['dataSource']) {
                case 'acf':
                    if (!function_exists('get_field')) {
                        return;
                    }

                    $data = get_field($field_name, $post_id);
                    break;
                case 'metabox':
                    if (!function_exists('rwmb_meta')) {
                        return;
                    }

                    $data = rwmb_meta($field_name, [], $post_id);
                    break;
                case 'jetengine':
                    if (!function_exists('jet_engine')) {
                        return;
                    }

                    $data = get_post_meta($post_id, $field_name, true);

                    // We have item-0, item-1 object keys. We convert it to a simple array.
                    if (is_array($data) && !empty($data)) {
                        $data = array_values($data);
                    }

                    break;
                case 'acpt':
                    if (!function_exists('get_acpt_field')) {
                        return;
                    }

                    $data = get_acpt_field([
                        "post_id" => $post_id,
                        "box_name" => $box_name,
                        "field_name" => $field_name
                    ]);

                    break;
            }

            if ($data) {
                $this->set_attribute('_root', 'data-brf-repeater-data', json_encode($data));
            }
        }

        if ($id !== $this->id) {
            $this->set_attribute('_root', 'data-custom-id', $id);
        }

        $output = '<' . $tag_name . ' ' . $this->render_attributes('_root') . '>';

        if (!empty($settings['label']) && $show_labels) {
            $output .= '<label ' . $this->render_attributes('label') . '>' . esc_html($settings['label']) . '</label>';
        }

        $output .= '<div class="brf-repeater-items">';

        $output .= '<div class="brf-repeater-item">';

        $output .= '<div class="brf-repeater-item__content">';
        $output .= Frontend::render_children($this);
        $output .= '</div>';

        $output .= '<div class="brf-repeater-item__actions">';
        $output .= '<button type="button" onclick="BrfProForms.removeRepeaterField(event)" class="brf-repeater-remove-item"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                            <path d="M432 256c0 17.7-14.3 32-32 32L48 288c-17.7 0-32-14.3-32-32s14.3-32 32-32l352 0c17.7 0 32 14.3 32 32z" />
                        </svg></button>';
        $output .= '<button type="button" onclick="BrfProForms.addRepeaterField(event)" class="brf-repeater-add-item"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                            <path d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32V224H48c-17.7 0-32 14.3-32 32s14.3 32 32 32H192V432c0 17.7 14.3 32 32 32s32-14.3 32-32V288H400c17.7 0 32-14.3 32-32s-14.3-32-32-32H256V80z" />
                        </svg></button>';
        $output .= '</div>';

        $output .= '</div>';

        $output .= '</div>';



        $output .= '</' . $tag_name . '>';
        echo $output; ?>
<?php
    }
}
