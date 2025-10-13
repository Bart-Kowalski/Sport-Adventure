<?php

namespace Bricks;

use \Bricksforge\ProForms\Helper as Helper;

if (!defined('ABSPATH'))
    exit;

class Brf_Pro_Forms_Select extends \Bricks\Element
{

    public $category = 'bricksforge forms';
    public $name = 'brf-pro-forms-field-select';
    public $icon = 'fa-solid fa-rectangle-list';
    public $css_selector = '';
    public $scripts = ["brfProForms"];
    public $nestable = true;

    public function get_label()
    {
        return esc_html__("Select", 'bricksforge');
    }

    public function enqueue_scripts()
    {
        wp_enqueue_script('bricksforge-elements');

        if ((bricks_is_builder()) || (isset($this->settings['useChoices']) && $this->settings['useChoices'] == true)) {
            wp_enqueue_script('bricksforge-choices');
            wp_enqueue_style('bricksforge-choices');
        }
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
        $this->control_groups['style'] = [
            'title'    => esc_html__('Style', 'bricksforge'),
            'tab'      => 'content',
        ];
        $this->control_groups['modernSelect'] = [
            'title'    => esc_html__('Modern Select', 'bricksforge'),
            'tab'      => 'content',
            'required' => ["useChoices", "=", true],
        ];
    }

    public function set_controls()
    {
        $this->controls['useChoices'] = [
            'group' => 'general',
            'label'          => esc_html__('Use Modern Style', 'bricksforge'),
            'type'           => 'checkbox',
        ];

        $this->controls = array_merge($this->controls, Helper::get_default_controls('select'));

        // Multiple
        $this->controls['multiple'] = [
            'group' => 'general',
            'label'          => esc_html__('Multiple', 'bricksforge'),
            'type'           => 'checkbox',
            'inline'         => true,
        ];

        $this->controls = array_merge($this->controls, Helper::get_data_source_controls());

        // Placeholder
        $this->controls['placeholder'] = [
            'group' => 'general',
            'label'          => esc_html__('Placeholder', 'bricksforge'),
            'type'           => 'text',
            'inline'         => true,
            'spellcheck'     => false,
            'hasDynamicData' => true,
        ];

        $this->controls = array_merge($this->controls, Helper::get_condition_controls());
        $this->controls = array_merge($this->controls, Helper::get_advanced_controls());
        $this->controls = array_merge($this->controls, Helper::get_validation_controls());

        // Item Select Text
        $this->controls['itemSelectText'] = [
            'group' => 'modernSelect',
            'label'          => esc_html__('Item Select Text', 'bricksforge'),
            'type'           => 'text',
            'placeholder'    => esc_html__('Press to select', 'bricksforge'),
            'required' => ["useChoices", "=", true],
        ];

        // No Results Text
        $this->controls['noResultsText'] = [
            'group' => 'modernSelect',
            'label'          => esc_html__('No Results Text', 'bricksforge'),
            'type'           => 'text',
            'placeholder'    => esc_html__('No results found', 'bricksforge'),
            'required' => ["useChoices", "=", true],
        ];

        // Searchable
        $this->controls['searchable'] = [
            'group' => 'modernSelect',
            'label'          => esc_html__('Searchable', 'bricksforge'),
            'type'           => 'checkbox',
            'required' => ["useChoices", "=", true],
        ];

        // Active Background
        $this->controls['activeBackground'] = [
            'group' => 'modernSelect',
            'label'          => esc_html__('Hover Background', 'bricksforge'),
            'type'           => 'background',
            'css' => [
                [
                    'property' => 'background',
                    'selector' => '.choices .is-highlighted',
                ],
            ],
            'required' => ["useChoices", "=", true],
        ];

        // Active Typography
        $this->controls['activeTypography'] = [
            'group' => 'modernSelect',
            'label'          => esc_html__('Hover Typography', 'bricksforge'),
            'type'           => 'typography',
            'css' => [
                [
                    'property' => 'font',
                    'selector' => '.choices .is-highlighted',
                ],
            ],
            'required' => ["useChoices", "=", true],

        ];

        // Dropdown Border Color
        $this->controls['dropdownBorderColor'] = [
            'group' => 'modernSelect',
            'label'          => esc_html__('Dropdown Border Color', 'bricksforge'),
            'type'           => 'color',
            'css' => [
                [
                    'property' => 'border-color',
                    'selector' => '.choices__list--dropdown',
                ],
            ],
            'required' => ["useChoices", "=", true],
        ];

        // Choices Background
        $this->controls['choicesBackground'] = [
            'group' => 'modernSelect',
            'label'          => esc_html__('Choices Background', 'bricksforge'),
            'type'           => 'background',
            'css' => [
                [
                    'property' => 'background',
                    'selector' => '[data-type="select-multiple"] .choices__item[data-item] ',
                ],
            ],
            'required' => [["useChoices", "=", true], ["multiple", "=", true]],
        ];

        // Choices Typography
        $this->controls['choicesTypography'] = [
            'group' => 'modernSelect',
            'label'          => esc_html__('Choices Typography', 'bricksforge'),
            'type'           => 'typography',
            'css' => [
                [
                    'property' => 'font',
                    'selector' => '[data-type="select-multiple"] .choices__item[data-item]',
                ],
                [
                    'property' => 'color',
                    'selector' => '[data-type="select-multiple"] .choices__button',
                ]
            ],
            'required' => [["useChoices", "=", true], ["multiple", "=", true]],
        ];

        // Choices Border
        $this->controls['choicesBorder'] = [
            'group' => 'modernSelect',
            'label'          => esc_html__('Choices Border', 'bricksforge'),
            'type'           => 'border',
            'css' => [
                [
                    'property' => 'border',
                    'selector' => '[data-type="select-multiple"] .choices__item[data-item] ',
                ],
            ],
            'required' => [["useChoices", "=", true], ["multiple", "=", true]],
        ];

        // Choices Box Shadow
        $this->controls['choicesBoxShadow'] = [
            'group' => 'modernSelect',
            'label'          => esc_html__('Choices Box Shadow', 'bricksforge'),
            'type'           => 'box-shadow',
            'css' => [
                [
                    'property' => 'box-shadow',
                    'selector' => '[data-type="select-multiple"] .choices__item[data-item] ',
                ],
            ],
            'required' => [["useChoices", "=", true], ["multiple", "=", true]],
        ];

        // Choices Close Button (Filter Control)
        $this->controls['choicesCloseButton'] = [
            'group' => 'modernSelect',
            'label'          => esc_html__('Choices Close Button', 'bricksforge'),
            'type'           => 'filters',
            'inline'         => true,
            'css' => [
                [
                    'property' => 'filter',
                    'selector' => '[data-type="select-multiple"] .choices__button',
                ],
            ],
            'required' => [["useChoices", "=", true], ["multiple", "=", true]],
        ];
    }

    public function get_nestable_children()
    {
        return [
            [
                'name'     => 'brf-pro-forms-field-option',
                'label'    => esc_html__('Option', 'bricksforge'),
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
        $value = isset($settings['value']) ? bricks_render_dynamic_data($settings['value']) : null;
        $required = isset($settings['required']) ? $settings['required'] : false;

        $options = Helper::parse_options($settings);

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
        $this->set_attribute('_root', 'data-label', $label);

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
        $this->set_attribute('field', 'id', 'form-field-' . $random_id);

        $disabled = isset($settings['disabled']) ? $settings['disabled'] : false;
        if ($disabled) {
            $this->set_attribute('field', 'disabled', 'disabled');
        }

        if (isset($this->settings['multiple']) && $this->settings['multiple'] == true) {
            $this->set_attribute('field', 'name', 'form-field-' . $id . '[]');
        } else {
            $this->set_attribute('field', 'name', 'form-field-' . $id);
        }

        $this->set_attribute('field', 'spellcheck', 'false');
        $this->set_attribute('field', 'data-label', $label);

        if (isset($this->settings['useChoices']) && $this->settings['useChoices'] == true) {
            $this->set_attribute('field', 'data-use-choices', 'true');


            if (isset($this->settings['itemSelectText']) && $this->settings['itemSelectText']) {
                $this->set_attribute('field', 'data-item-select-text', $this->settings['itemSelectText']);
            }

            if (isset($this->settings['noResultsText']) && $this->settings['noResultsText']) {
                $this->set_attribute('field', 'data-no-results-text', $this->settings['noResultsText']);
            }

            if (isset($this->settings['searchable']) && $this->settings['searchable'] == true) {
                $this->set_attribute('field', 'data-searchable', 'true');
            }
        }

        if (isset($value)) {
            $this->set_attribute('field', 'value', $value);
        }

        if ($required) {
            $this->set_attribute('field', 'required', $required);
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

        // Multiple
        if (isset($settings['multiple']) && $settings['multiple'] == true) {
            $this->set_attribute('field', 'multiple', 'multiple');
        }

        // Placeholder
        if ($placeholder) {
            $this->set_attribute('field', 'data-placeholder', $placeholder);
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


?>
        <div <?php echo $this->render_attributes('_root'); ?>>
            <?php if ($label && $show_labels) : ?>
                <label <?php echo $this->render_attributes('label'); ?> for="form-field-<?php echo $random_id; ?>"><?php echo $label; ?></label>
            <?php endif; ?>
            <?php if (isset($settings['icon'])) { ?>
                <div <?php echo $this->render_attributes("field-icons"); ?>>
                    <span class="input-icon"><?php echo $this->render_icon($settings['icon']) ?></span>
                    <select value="sleeping" <?php echo $this->render_attributes('field'); ?>>
                        <?php if (isset($settings['placeholder'])) : ?>
                            <option data-placeholder value="" disabled selected hidden><?php echo $settings['placeholder']; ?></option>
                        <?php endif; ?>

                        <?php foreach ($options as $option) : ?>
                            <?php
                            $selected = false;
                            if (isset($option['active']) && $option['active']) {
                                $selected = true;
                            }
                            if (isset($settings['value']) && $option['value'] == $settings['value']) {
                                $selected = true;
                            }
                            ?>
                            <option value="<?php echo $option['value']; ?>" data-label="<?php echo $option['label']; ?>" <?php echo $selected ? 'selected' : ''; ?>><?php echo $option['label']; ?></option>
                        <?php endforeach; ?>

                        <?php echo Frontend::render_children($this); ?>
                    </select>
                </div>
            <?php } else { ?>
                <select <?php echo $this->render_attributes('field'); ?>>
                    <?php if (isset($settings['placeholder'])) : ?>
                        <option data-placeholder value="" selected><?php echo $settings['placeholder']; ?></option>
                    <?php endif; ?>

                    <?php foreach ($options as $option) : ?>
                        <?php
                        $selected = false;
                        if (isset($option['active']) && $option['active']) {
                            $selected = true;
                        }
                        if (isset($settings['value']) && $option['value'] == $settings['value']) {
                            $selected = true;
                        }
                        ?>
                        <option data-label="<?php echo $option['label']; ?>" value="<?php echo $option['value']; ?>" <?php echo $selected ? 'selected' : ''; ?>><?php echo $option['label']; ?></option>
                    <?php endforeach; ?>

                    <?php echo Frontend::render_children($this); ?>
                </select>
            <?php } ?>
        </div>
<?php
    }
}
