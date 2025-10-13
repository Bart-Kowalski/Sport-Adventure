<?php

namespace Bricks;

use \Bricksforge\ProForms\Helper as Helper;

if (!defined('ABSPATH'))
    exit;

class Brf_Pro_Forms_Rating extends \Bricks\Element
{

    public $category = 'bricksforge forms';
    public $name = 'brf-pro-forms-field-rating';
    public $icon = 'fa-solid fa-star';
    public $css_selector = '';
    public $scripts = ["brfProFormsRating"];
    public $nestable = false;

    public function get_label()
    {
        return esc_html__("Rating", 'bricksforge');
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
        $this->control_groups['config'] = [
            'title'    => esc_html__('Config', 'bricksforge'),
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
        $this->controls = array_merge($this->controls, Helper::get_default_controls('rating'));

        // Max Rating
        $this->controls['maxRating'] = [
            'group' => 'config',
            'label' => esc_html__('Max Rating', 'bricksforge'),
            'type'  => 'number',
            'default'   => 5,
            'min'   => 1,
            'max'   => 9999,
            'hasDynamicData' => true,
        ];

        // Default Rating
        $this->controls['defaultRating'] = [
            'group' => 'config',
            'label' => esc_html__('Default Rating', 'bricksforge'),
            'type'  => 'number',
            'default' => 3,
            'min'   => 0,
            'max'   => 9999,
            'hasDynamicData' => true,
        ];

        // Rating Icon
        $this->controls['ratingIcon'] = [
            'group' => 'style',
            'label' => esc_html__('Rating Icon', 'bricksforge'),
            'type'  => 'icon',
            'default' => [
                'icon' => 'fa-solid fa-star',
                'style' => 'solid',
            ],
        ];

        // Icon Size
        $this->controls['iconSize'] = [
            'group' => 'style',
            'label' => esc_html__('Icon Size', 'bricksforge'),
            'type'  => 'number',
            'default' => 24,
            'units' => true,
            'min'   => 1,
            'max'   => 9999,
            'css' => [
                [
                    'selector' => '.brf-pro-forms-rating-icon',
                    'property' => 'font-size',
                ],
                [
                    'selector' => '.brf-pro-forms-rating-icon svg',
                    'property' => 'width',
                ],
                [
                    'selector' => '.brf-pro-forms-rating-icon svg',
                    'property' => 'height',
                    'value' => 'auto'
                ],
            ],
        ];

        // Icon Gap
        $this->controls['iconGap'] = [
            'group' => 'style',
            'label' => esc_html__('Icon Gap', 'bricksforge'),
            'type'  => 'number',
            'default' => 5,
            'units' => true,
            'min'   => 0,
            'max'   => 9999,
            'css' => [
                [
                    'selector' => '.brf-pro-forms-rating-icons',
                    'property' => 'gap',
                ],
            ],
        ];

        // Icon Color Normal
        $this->controls['iconColorNormal'] = [
            'group' => 'style',
            'label' => esc_html__('Icon Color Normal', 'bricksforge'),
            'type'  => 'color',
            'css' => [
                [
                    'selector' => '.brf-pro-forms-rating-icon',
                    'property' => 'color',
                ],
            ],
        ];

        // Icon Active Color
        $this->controls['iconColorHover'] = [
            'group' => 'style',
            'label' => esc_html__('Icon Active Hover', 'bricksforge'),
            'type'  => 'color',
            'default' => [
                "raw" => "var(--bricks-color-primary)"
            ],
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

        $value = isset($settings['value']) ? bricks_render_dynamic_data($settings['value']) : '';
        $rating_icon = isset($settings['ratingIcon']) ? $settings['ratingIcon'] : 'fa-solid fa-star';
        $max_rating = isset($settings['maxRating']) ? bricks_render_dynamic_data($settings['maxRating']) : 5;
        $default_rating = isset($settings['defaultRating']) ? bricks_render_dynamic_data($settings['defaultRating']) : 0;
        $icon_size = isset($settings['iconSize']) ? $settings['iconSize'] : 24;
        $icon_color_normal = isset($settings['iconColorNormal']) ? $settings['iconColorNormal'] : '#000';
        $icon_color_hover = isset($settings['iconColorHover']) ? $settings['iconColorHover'] : '#000';
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

        if ($id !== $this->id) {
            $this->set_attribute('_root', 'data-custom-id', $id);
        }

        // Custom Css Class
        if (isset($settings['cssClass']) && $settings['cssClass']) {
            $this->set_attribute('field', 'class', $settings['cssClass']);
        }

        $rating_settings = [
            'maxRating' => $max_rating,
            'defaultRating' => $default_rating,
            'ratingIcon' => $rating_icon,
            'iconSize' => $icon_size,
            'iconColorNormal' => $icon_color_normal,
            'iconColorHover' => $icon_color_hover,
        ];

        $this->set_attribute('field', 'data-settings', json_encode($rating_settings));

        /**
         * Field
         */
        $this->set_attribute('field', 'type', 'number');
        $this->set_attribute('field', 'id', 'form-field-' . $random_id);
        $this->set_attribute('field', 'name', 'form-field-' . $id);
        $this->set_attribute('field', 'data-label', $label);

        if ($required) {
            $this->set_attribute('field', 'required', 'required');
        }

        if ($value) {
            $this->set_attribute('field', 'value', $value);
            $this->set_attribute('field', 'data-original-content', $value);
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



?>
        <div <?php echo $this->render_attributes('_root'); ?>>
            <?php if ($label && $show_labels) : ?>
                <label <?php echo $this->render_attributes('label'); ?> for="form-field-<?php echo $random_id; ?>">
                    <?php echo $label; ?>
                </label>
            <?php endif; ?>
            <!-- Our icons with an hidden input -->
            <div class="brf-pro-forms-rating-icons">
                <?php for ($i = 1; $i <= $max_rating; $i++) : ?>
                    <span class="brf-pro-forms-rating-icon <?php echo ($value && $i <= $value) ? 'active' : ''; ?>" data-value="<?php echo $i; ?>"><?php echo $this->render_icon($rating_icon); ?></span>
                <?php endfor; ?>
            </div>
            <input type="hidden" <?php echo $this->render_attributes('field'); ?> />
        </div>
<?php
    }
}
