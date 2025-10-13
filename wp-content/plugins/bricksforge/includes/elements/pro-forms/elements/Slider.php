<?php

namespace Bricks;

use \Bricksforge\ProForms\Helper as Helper;

if (!defined('ABSPATH'))
    exit;

class Brf_Pro_Forms_Slider extends \Bricks\Element
{

    public $category = 'bricksforge forms';
    public $name = 'brf-pro-forms-field-slider';
    public $icon = 'fa-solid fa-sliders';
    public $css_selector = '';
    public $scripts = ['brfProFormsSlider'];
    public $nestable = false;

    public function get_label()
    {
        return esc_html__("Slider", 'bricksforge');
    }

    public function enqueue_scripts()
    {
        wp_enqueue_script('bricksforge-elements');
        wp_enqueue_script('bricksforge-nouislider');
        wp_enqueue_style('bricksforge-nouislider');
    }

    public function set_control_groups()
    {
        $this->control_groups['general'] = [
            'title'    => esc_html__('General', 'bricksforge'),
            'tab'      => 'content',
        ];
        $this->control_groups['sliderSettings'] = [
            'title'    => esc_html__('Slider Settings', 'bricksforge'),
            'tab'      => 'content',
        ];
        $this->control_groups['binding'] = [
            'title'    => esc_html__('Binding', 'bricksforge'),
            'tab'      => 'content',
        ];
        $this->control_groups['style'] = [
            'title'    => esc_html__('Style', 'bricksforge'),
            'tab'      => 'content',
        ];
        $this->control_groups['conditions'] = [
            'title'    => esc_html__('Conditions', 'bricksforge'),
            'tab'      => 'content',
        ];
    }

    public function set_controls()
    {

        $this->controls = array_merge($this->controls, Helper::get_default_controls());

        $this->controls['handlers'] = [
            'group' => 'sliderSettings',
            'type' => 'repeater',
            'label' => esc_html__('Handlers', 'bricksforge'),
            'titleProperty' => 'value',
            'fields' => [
                'value' => [
                    'type' => 'number',
                    'label' => esc_html__('Value', 'bricksforge'),
                    'default' => 0,
                ],
                'connect' => [
                    'type' => 'checkbox',
                    'label' => esc_html__('Connect', 'bricksforge'),
                    'default' => true,
                ],
                'binding' => [
                    'type' => 'repeater',
                    'label' => esc_html__('Binding', 'bricksforge'),
                    'titleProperty' => 'fieldId',
                    'fields' => [
                        'fieldId' => [
                            'type' => 'text',
                            'label' => esc_html__('Form Field ID', 'bricksforge'),
                        ],
                    ],
                ],
                'tooltips' => [
                    'type' => 'checkbox',
                    'label' => esc_html__('Show Tooltips', 'bricksforge'),
                    'default' => true,
                ],
            ],
            'default' => [
                [
                    'value' => 25,
                    'connect' => true,
                ],
            ],

        ];

        $this->controls['connectLast'] = [
            'group' => 'sliderSettings',
            'type' => 'checkbox',
            'label' => esc_html__('Connect Last', 'bricksforge'),
            'default' => false,
        ];

        // Use Static Values
        $this->controls['useStaticValues'] = [
            'group' => 'sliderSettings',
            'type' => 'checkbox',
            'label' => esc_html__('Use Static Values', 'bricksforge'),
            'rerender' => true,
        ];

        // Static Values Repeater
        $this->controls['staticValues'] = [
            'group' => 'sliderSettings',
            'type' => 'repeater',
            'label' => esc_html__('Static Values', 'bricksforge'),
            'titleProperty' => 'value',
            'fields' => [
                'value' => [
                    'type' => 'text',
                    'label' => esc_html__('Value', 'bricksforge'),
                    'placeholder' => esc_html__('12MB', 'bricksforge'),
                ],
            ],
            'required' => ["useStaticValues", "=", true],
        ];

        $this->controls['min'] = [
            'group' => 'sliderSettings',
            'type' => 'number',
            'label' => esc_html__('Min', 'bricksforge'),
            'default' => 0,
            'hasDynamicData' => true,
            'required' => ["useStaticValues", "=", false]
        ];

        $this->controls['max'] = [
            'group' => 'sliderSettings',
            'type' => 'number',
            'label' => esc_html__('Max', 'bricksforge'),
            'default' => 100,
            'hasDynamicData' => true,
            'required' => ["useStaticValues", "=", false]
        ];

        // Round to x decimals
        $this->controls['roundTo'] = [
            'group' => 'sliderSettings',
            'type' => 'number',
            'hasDynamicData' => true,
            'label' => esc_html__('Round to x decimals', 'bricksforge'),
            'default' => 0,
        ];

        // Step
        $this->controls['step'] = [
            'group' => 'sliderSettings',
            'type' => 'number',
            'label' => esc_html__('Step', 'bricksforge'),
            'default' => 10,
            'hasDynamicData' => true,
        ];

        // Pips
        $this->controls['pips'] = [
            'group' => 'sliderSettings',
            'type' => 'checkbox',
            'label' => esc_html__('Show Pips', 'bricksforge'),
            'default' => true,
            'rerender' => true,
            'css' => [
                [
                    'property' => 'margin-bottom',
                    'value' => '35px',
                    'selector' => '.slider',
                ],
            ]
        ];

        // Pips Density
        $this->controls['pipsDensity'] = [
            'group' => 'sliderSettings',
            'type' => 'number',
            'label' => esc_html__('Pips Density', 'bricksforge'),
            'default' => 1,
        ];

        // Pips Mode
        $this->controls['pipsMode'] = [
            'group' => 'sliderSettings',
            'type' => 'select',
            'label' => esc_html__('Pips Mode', 'bricksforge'),
            'default' => 'steps',
            'options' => [
                'steps' => esc_html__('Steps', 'bricksforge'),
                'range' => esc_html__('Range', 'bricksforge'),
            ],
        ];

        // Direction (Horizontal / Vertical)
        $this->controls['orientation'] = [
            'group' => 'sliderSettings',
            'type' => 'select',
            'label' => esc_html__('Orientation', 'bricksforge'),
            'default' => 'horizontal',
            'options' => [
                'horizontal' => esc_html__('Horizontal', 'bricksforge'),
                'vertical' => esc_html__('Vertical', 'bricksforge'),
            ],
        ];

        // If orientation, orientationHeight
        $this->controls['height'] = [
            'group' => 'sliderSettings',
            'type' => 'number',
            'label' => esc_html__('Height', 'bricksforge'),
            'units' => true,
            'css' => [
                [
                    'property' => 'height',
                    'selector' => '.slider',
                ],
            ],
        ];

        /**
         * Bindings
         */
        $this->controls['bindToInfo'] = [
            'group' => 'binding',
            'type' => 'info',
            'content' => esc_html__('If you want to bind a single handler to a form field, you can do it via the "Binding" control on the handler itself.', 'bricksforge'),
        ];
        $this->controls['bindTo'] = [
            'group' => 'binding',
            'type' => 'repeater',
            'label' => esc_html__('Bind To Form Field', 'bricksforge'),
            'description' => esc_html__('Bind all handler values (comma separated) to a form field.', 'bricksforge'),
            'titleProperty' => 'id',
            'fields' => [
                'id' => [
                    'type' => 'text',
                    'label' => esc_html__('Form Field ID', 'bricksforge'),
                ],
            ],
        ];

        /**
         * Style
         */
        $this->controls['styleDefaultBarsSeparator'] = [
            'group' => 'style',
            'type' => 'separator',
            'label' => esc_html__('Default Bars', 'bricksforge'),
        ];

        // Default Bar Background Color
        $this->controls['styleDefaultBarsBackgroundColor'] = [
            'group' => 'style',
            'type' => 'color',
            'label' => esc_html__('Background Color', 'bricksforge'),
            'css' => [
                [
                    'property' => 'background-color',
                    'selector' => '.noUi-connects',
                ],
            ],
        ];

        // Default Bar Border
        $this->controls['styleDefaultBarsBorder'] = [
            'group' => 'style',
            'type' => 'border',
            'label' => esc_html__('Border', 'bricksforge'),
            'css' => [
                [
                    'property' => 'border',
                    'selector' => '.slider',
                ],
                [
                    'property' => 'border',
                    'selector' => '.noUi-base',
                ],
                [
                    'property' => 'border',
                    'selector' => '.noUi-connects',
                ],
            ],
        ];

        $this->controls['styleConnectBarsSeparator'] = [
            'group' => 'style',
            'type' => 'separator',
            'label' => esc_html__('Connection Bars', 'bricksforge'),
        ];

        // Background Color
        $this->controls['styleConnectBarsBackgroundColor'] = [
            'group' => 'style',
            'type' => 'color',
            'label' => esc_html__('Background Color', 'bricksforge'),
            'css' => [
                [
                    'property' => 'background-color',
                    'selector' => '.noUi-connect',
                ],
            ],
        ];

        $this->controls['styleHandlerSeparator'] = [
            'group' => 'style',
            'type' => 'separator',
            'label' => esc_html__('Handler', 'bricksforge'),
        ];

        // Reset Style (Checkbox)
        $this->controls['styleHandlerReset'] = [
            'group' => 'style',
            'type' => 'checkbox',
            'label' => esc_html__('Reset Style', 'bricksforge'),
            'css' => [
                [
                    'property' => 'box-shadow',
                    'selector' => '.noUi-handle',
                    'value' => 'none',
                ],
                [
                    'property' => 'display',
                    'value' => 'none',
                    'selector' => '.noUi-handle::before',
                ],
                [
                    'property' => 'display',
                    'value' => 'none',
                    'selector' => '.noUi-handle::after',
                ],
            ],

        ];


        // Background Color
        $this->controls['styleHandlerBackground'] = [
            'group' => 'style',
            'type' => 'background',
            'label' => esc_html__('Background', 'bricksforge'),
            'css' => [
                [
                    'property' => 'background',
                    'selector' => '.noUi-handle',
                ],
            ],
        ];

        // Width
        $this->controls['styleHandlerWidth'] = [
            'group' => 'style',
            'type' => 'number',
            'units' => true,
            'label' => esc_html__('Width', 'bricksforge'),
            'css' => [
                [
                    'property' => 'width',
                    'selector' => '.noUi-handle',
                ],
            ],
        ];

        // Height
        $this->controls['styleHandlerHeight'] = [
            'group' => 'style',
            'type' => 'number',
            'units' => true,
            'label' => esc_html__('Height', 'bricksforge'),
            'css' => [
                [
                    'property' => 'height',
                    'selector' => '.noUi-handle',
                ],
            ],
        ];

        // Border
        $this->controls['styleHandlerBorder'] = [
            'group' => 'style',
            'type' => 'border',
            'label' => esc_html__('Border', 'bricksforge'),
            'css' => [
                [
                    'property' => 'border',
                    'selector' => '.noUi-handle',
                ],
            ],
        ];

        // Box Shadow
        $this->controls['styleHandlerBoxShadow'] = [
            'group' => 'style',
            'type' => 'box-shadow',
            'label' => esc_html__('Box Shadow', 'bricksforge'),
            'css' => [
                [
                    'property' => 'box-shadow',
                    'selector' => '.noUi-handle',
                ],
            ],
        ];

        // Transform
        $this->controls['styleHandlerTransform'] = [
            'group' => 'style',
            'type' => 'transform',
            'label' => esc_html__('Transform', 'bricksforge'),
            'css' => [
                [
                    'property' => 'transform',
                    'selector' => '.noUi-handle',
                ],
            ],
        ];

        // Pips Separator
        $this->controls['stylePipsSeparator'] = [
            'group' => 'style',
            'type' => 'separator',
            'label' => esc_html__('Pips', 'bricksforge'),
        ];

        // Pips Typography
        $this->controls['pipsTypography'] = [
            'group' => 'style',
            'type' => 'typography',
            'label' => esc_html__('Pips Typography', 'bricksforge'),
            'css' => [
                [
                    'property' => 'typography',
                    'selector' => '.noUi-pips .noUi-value:not(.noUi-value-large)',
                ],
            ],
        ];

        // Pips Top Spacing
        $this->controls['stylePipsTopSpacing'] = [
            'group' => 'style',
            'type' => 'number',
            'units' => true,
            'label' => esc_html__('Pips Top Spacing', 'bricksforge'),
            'css' => [
                [
                    'property' => 'top',
                    'selector' => '.noUi-value',
                ],
            ],
        ];

        // Edge Pips Typography
        $this->controls['largePipsTypography'] = [
            'group' => 'style',
            'type' => 'typography',
            'label' => esc_html__('Large Pips Typography', 'bricksforge'),
            'css' => [
                [
                    'property' => 'typography',
                    'selector' => '.noUi-pips .noUi-value.noUi-value-large',
                ],
            ],
        ];

        // Pips Lines Color
        $this->controls['stylePipsLines'] = [
            'group' => 'style',
            'type' => 'color',
            'label' => esc_html__('Pips Lines Color', 'bricksforge'),
            'css' => [
                [
                    'property' => 'background',
                    'selector' => '.noUi-pips .noUi-marker',
                ],
            ],
        ];

        // Pips Lines Width
        $this->controls['stylePipsLinesWidth'] = [
            'group' => 'style',
            'type' => 'number',
            'units' => true,
            'label' => esc_html__('Pips Lines Width', 'bricksforge'),
            'css' => [
                [
                    'property' => 'width',
                    'selector' => '.noUi-pips .noUi-marker',
                ],
            ],
        ];

        // Pips Lines Height
        $this->controls['stylePipsLinesHeight'] = [
            'group' => 'style',
            'type' => 'number',
            'units' => true,
            'label' => esc_html__('Pips Lines Height', 'bricksforge'),
            'css' => [
                [
                    'property' => 'height',
                    'selector' => '.noUi-pips .noUi-marker',
                ],
            ],
        ];

        // Pips Lines Border
        $this->controls['stylePipsLinesBorder'] = [
            'group' => 'style',
            'type' => 'border',
            'label' => esc_html__('Pips Lines Border', 'bricksforge'),
            'css' => [
                [
                    'property' => 'border',
                    'selector' => '.noUi-pips .noUi-marker',
                ],
            ],
        ];

        // Pips Long Lines Color
        $this->controls['stylePipsLongLines'] = [
            'group' => 'style',
            'type' => 'color',
            'label' => esc_html__('Pips Long Lines Color', 'bricksforge'),
            'css' => [
                [
                    'property' => 'background',
                    'selector' => '.noUi-pips .noUi-marker.noUi-marker-horizontal.noUi-marker-large',
                ],
            ],
        ];

        // Pips Long Lines Width
        $this->controls['stylePipsLongLinesWidth'] = [
            'group' => 'style',
            'type' => 'number',
            'units' => true,
            'label' => esc_html__('Pips Long Lines Width', 'bricksforge'),
            'css' => [
                [
                    'property' => 'width',
                    'selector' => '.noUi-pips .noUi-marker.noUi-marker-horizontal.noUi-marker-large',
                ],
            ],
        ];

        // Pips Long Lines Height
        $this->controls['stylePipsLongLinesHeight'] = [
            'group' => 'style',
            'type' => 'number',
            'units' => true,
            'label' => esc_html__('Pips Long Lines Height', 'bricksforge'),
            'css' => [
                [
                    'property' => 'height',
                    'selector' => '.noUi-pips .noUi-marker.noUi-marker-horizontal.noUi-marker-large',
                ],
            ],
        ];

        // Pips Long Lines Border
        $this->controls['stylePipsLongLinesBorder'] = [
            'group' => 'style',
            'type' => 'border',
            'label' => esc_html__('Pips Long Lines Border', 'bricksforge'),
            'css' => [
                [
                    'property' => 'border',
                    'selector' => '.noUi-pips .noUi-marker.noUi-marker-horizontal.noUi-marker-large',
                ],
            ],
        ];

        $this->controls = array_merge($this->controls, Helper::get_condition_controls());
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

        $show_labels = true;
        if (isset($parent_settings) && !empty($parent_settings) && !isset($parent_settings['showLabels'])) {
            $show_labels = false;
        }

        // Single Show Label
        if (isset($settings['showLabel']) && $settings['showLabel']) {
            $show_labels = true;
        }

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
            $this->set_attribute('_root', 'class', $settings['cssClass']);
        }

        /**
         * Field
         */
        $this->set_attribute('field', 'type', 'hidden');
        $this->set_attribute('field', 'id', 'form-field-' . $random_id);
        $this->set_attribute('field', 'name', 'form-field-' . $id);
        $this->set_attribute('field', 'data-label', $label);

        if ($value) {
            $this->set_attribute('field', 'value', $value);
        }
        if ($required) {
            $this->set_attribute('field', 'required', $required);
        }

        // Slider
        $this->set_attribute('slider', 'class', ['slider']);

        $roundTo = isset($settings['roundTo']) ? intval($settings['roundTo']) : 0;

        $this->set_attribute('slider', 'data-round-to', $roundTo);

        $start = isset($settings['handlers']) ? array_map(function ($item) {
            return isset($item['value']) ? intval($item['value']) : 0;
        }, $settings['handlers']) : [20];

        $connect = isset($settings['handlers']) ? array_map(function ($item) {
            return isset($item['connect']) ? filter_var($item['connect'], FILTER_VALIDATE_BOOLEAN) : false;
        }, $settings['handlers']) : [true];
        $connect[] = isset($settings['connectLast']) ? filter_var($settings['connectLast'], FILTER_VALIDATE_BOOLEAN) : false;

        $single_bindings = isset($settings['handlers']) ? array_merge(...array_map(function ($item) {
            return isset($item['binding']) ? $item['binding'] : [null];
        }, $settings['handlers'])) : [];

        // Reset index
        $this->set_attribute('slider', 'data-single-bindings', json_encode($single_bindings));

        $tooltips = isset($settings['handlers']) ? array_map(function ($item) {
            return isset($item['tooltips']) ? filter_var($item['tooltips'], FILTER_VALIDATE_BOOLEAN) : false;
        }, $settings['handlers']) : [false];

        $min = isset($settings['min']) ? intval($settings['min']) : 0;
        $max = isset($settings['max']) ? intval($settings['max']) : 100;
        $orientation = isset($settings['orientation']) ? $settings['orientation'] : 'horizontal';
        $step = isset($settings['step']) ? intval($settings['step']) : 1;
        $pips = isset($settings['pips']) ? filter_var($settings['pips'], FILTER_VALIDATE_BOOLEAN) : false;
        $pips_density = isset($settings['pipsDensity']) ? intval($settings['pipsDensity']) : 100;
        $pips_mode = isset($settings['pipsMode']) ? $settings['pipsMode'] : 'steps';

        //  if useStaticValues is true, we need to build the range from the static values
        if (isset($settings['useStaticValues']) && $settings['useStaticValues']) {
            $static_values = isset($settings['staticValues']) ? array_map(function ($item) {
                return isset($item['value']) ? $item['value'] : "0";
            }, $settings['staticValues']) : ["0", "100"];

            $min = 0;
            $max = count($static_values) - 1;
            $start = isset($settings['handlers']) ? array_map(function ($item) {
                return isset($item['value']) ? $item['value'] : "0";
            }, $settings['handlers']) : ["20"];

            $this->set_attribute('slider', 'data-static-values', json_encode($static_values));
        }


        $slider_settings = [
            'start' => $start,
            'range' => [
                'min' => $min,
                'max' => $max,
            ],
            'connect' => $connect,
            'tooltips' => $tooltips,
            'orientation' => $orientation,
            'step' => $step,
        ];

        if ($pips) {
            $slider_settings['pips'] = [
                'mode' => $pips_mode,
                'density' => $pips_density,
            ];
        }

        $this->set_attribute('slider', 'data-settings', json_encode($slider_settings));

        if (isset($settings['bindTo']) && $settings['bindTo']) {
            $this->set_attribute('slider', 'data-bindings', json_encode($settings['bindTo']));
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
                <label <?php echo $this->render_attributes('label'); ?> for="form-field-<?php echo $random_id; ?>"><?php echo $label; ?></label>
            <?php endif; ?>
            <input <?php echo $this->render_attributes('field'); ?>>
            <div <?php echo $this->render_attributes('slider'); ?>></div>
        </div>
<?php
    }
}
