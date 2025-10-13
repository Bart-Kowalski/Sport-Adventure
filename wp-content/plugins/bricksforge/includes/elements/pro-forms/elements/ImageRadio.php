<?php

namespace Bricks;

use \Bricksforge\ProForms\Helper as Helper;
use \Bricksforge\Api\FormsHelper as FormsHelper;

if (!defined('ABSPATH'))
    exit;

class Brf_Pro_Forms_ImageRadio extends \Bricks\Element
{

    public $category = 'bricksforge forms';
    public $name = 'brf-pro-forms-field-image-radio';
    public $icon = 'fa-solid fa-circle-dot';
    public $css_selector = '';
    public $scripts = [];
    public $nestable = false;

    public function get_label()
    {
        return esc_html__("Image Radio", 'bricksforge');
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
        $this->control_groups['checkedStyle'] = [
            'title'    => esc_html__('Checked Style', 'bricksforge'),
            'tab'      => 'content',
        ];
        $this->control_groups['accessibility'] = [
            'title'    => esc_html__('Accessibility', 'bricksforge'),
            'tab'      => 'content',
        ];
    }

    public function set_controls()
    {
        $this->controls['info'] = [
            'type'  => 'info',
            'content' => 'This element should be used as a child of the radio wrapper.'
        ];

        $this->controls = array_merge($this->controls, Helper::get_loop_controls());
        $this->controls = array_merge($this->controls, Helper::get_default_controls('image-radio'));

        $this->controls = array_merge($this->controls, Helper::get_checked_controls());

        // Image
        $this->controls['image1'] = [
            'group' => 'style',
            'label'          => esc_html__('Image', 'bricksforge'),
            'type'           => 'image',
        ];

        // Image Width
        $this->controls['imageWidth'] = [
            'group' => 'style',
            'label'          => esc_html__('Image Width', 'bricksforge'),
            'type'           => 'number',
            'units' => true,
            'css' => [
                [
                    'property' => 'width',
                    'selector' => 'img',
                ],
            ],
        ];

        // Image Filter
        $this->controls['imageFilter'] = [
            'group' => 'style',
            'label'          => esc_html__('Image Filter', 'bricksforge'),
            'type'           => 'filters',
            'inline' => true,
            'css' => [
                [
                    'property' => 'filter',
                    'selector' => 'img[data-image="1"]',
                ],
            ],
        ];

        // Image Transform
        $this->controls['imageTransform'] = [
            'group' => 'style',
            'label'          => esc_html__('Image Transform', 'bricksforge'),
            'type'           => 'transform',
            'css' => [
                [
                    'property' => 'transform',
                    'selector' => 'img[data-image="1"]',
                ],
            ],
        ];

        // Checked Image
        $this->controls['image2'] = [
            'group' => 'checkedStyle',
            'label'          => esc_html__('Checked Image', 'bricksforge'),
            'type'           => 'image',
        ];

        // Checked Image Filter
        $this->controls['checkedImageFilter'] = [
            'group' => 'checkedStyle',
            'label'          => esc_html__('Checked Image Filter', 'bricksforge'),
            'type'           => 'filters',
            'inline' => true,
            'css' => [
                [
                    'property' => 'filter',
                    'selector' => 'img[data-image="2"]',
                ],
            ],
        ];

        // Checked Image Transform
        $this->controls['checkedImageTransform'] = [
            'group' => 'checkedStyle',
            'label'          => esc_html__('Checked Image Transform', 'bricksforge'),
            'type'           => 'transform',
            'css' => [
                [
                    'property' => 'transform',
                    'selector' => 'img[data-image="2"]',
                ],
            ],
        ];

        $this->controls = array_merge($this->controls, Helper::get_accessibility_controls());
        $this->controls = array_merge($this->controls, Helper::get_advanced_controls());
    }

    public function render()
    {
        $element = $this->element;
        $settings = $this->settings;
        $parent_settings = Helper::get_nestable_parent_settings($element) ? Helper::get_nestable_parent_settings($element) : false;
        $field_wrapper = Helper::get_parent("brf-pro-forms-field-radio-wrapper", $element);

        $id = $this->id ? $this->id : false;

        if ($field_wrapper) {
            $id = isset($field_wrapper['settings']['id']) ? $field_wrapper['settings']['id'] : $field_wrapper['id'];
        }

        $random_id = Helpers::generate_random_id(false);

        $output   = '';
        $query_output = '';

        // Bricks Query Loop
        if (isset($settings['hasLoop'])) {
            // Hold the global element settings to add back 'hasLoop' after the query->render (@since 1.8)
            $global_element = Helpers::get_global_element($element);

            $query = new \Bricks\Query($element);

            // Prevent endless loop
            unset($element['settings']['hasLoop']);

            // Prevent endless loop for global element (@since 1.8)
            if (!empty($global_element['global'])) {
                // Find the global element and unset 'hasLoop'
                Database::$global_data['elements'] = array_map(function ($global_element) use ($element) {
                    if (!empty($element['global']) && $element['global'] === $global_element['global']) {
                        unset($global_element['settings']['hasLoop']);
                    }
                    return $global_element;
                }, Database::$global_data['elements']);
            }

            // STEP: Render loop
            $output = $query->render('Bricks\Frontend::render_element', compact('element'));

            echo $output;

            // Prevent endless loop for global element (@since 1.8)
            if (!empty($global_element['global'])) {
                // Add back global element 'hasLoop' setting after execute render_element
                Database::$global_data['elements'] = array_map(function ($global_element) use ($element) {
                    if (!empty($element['global']) && $element['global'] === $global_element['global']) {
                        $global_element['settings']['hasLoop'] = true;
                    }
                    return $global_element;
                }, Database::$global_data['elements']);
            }

            // STEP: Infinite scroll
            $this->render_query_loop_trail($query);

            // Destroy Query to explicitly remove it from global store
            $query->destroy();

            unset($query);

            return;
        }

        $label = isset($settings['label']) ? $settings['label'] : false;
        $value = isset($settings['value']) ? bricks_render_dynamic_data($settings['value']) : null;
        $required = isset($settings['required']) ? $settings['required'] : false;

        $image1 = isset($settings['image1']['url']) ? $settings['image1']['url'] : false;
        $image2 = isset($settings['image2']['url']) ? $settings['image2']['url'] : false;

        // If images are null, check for dynamic data
        if (!$image1) {
            if (isset($settings['image1']['useDynamicData'])) {
                $image1 = $this->render_dynamic_data_tag($settings['image1']['useDynamicData'], 'image', ['size' => $image['size']])[0];

                if (is_numeric($image1)) {
                    $image1 = wp_get_attachment_image_src($image1, $image['size'])[0];
                }
            }
        }

        if (!$image2) {
            if (isset($settings['image2']['useDynamicData'])) {
                $image2 = $this->render_dynamic_data_tag($settings['image2']['useDynamicData'], 'image', ['size' => $image['size']])[0];

                if (is_numeric($image2)) {
                    $image2 = wp_get_attachment_image_src($image2, $image['size'])[0];
                }
            }
        }

        // We need both. If one is not set, return.
        if (!$image1 || !$image2) {
            return;
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
        $this->set_attribute('_root', 'data-element-id', $this->id);
        $this->set_attribute('_root', 'class', 'image-radio');

        // Custom Css Class
        if (isset($settings['cssClass']) && $settings['cssClass']) {
            $this->set_attribute('field', 'class', $settings['cssClass']);
        }

        /**
         * Field
         */
        $this->set_attribute('field', 'id', 'form-field-' . $id . '-' . $random_id);
        $this->set_attribute('field', 'name', 'form-field-' . $id . '[]');

        // We remove html tags from the label
        $clean_label = strip_tags(bricks_render_dynamic_data($label));
        $this->set_attribute('field', 'data-label', $clean_label);

        if (isset($value)) {
            $this->set_attribute('field', 'value', $value);
        }
        if ($required) {
            $this->set_attribute('field', 'required', $required);
        }

        // Calculation Value
        if (isset($settings['calculationValue']) && $settings['calculationValue']) {
            $this->set_attribute('field', 'data-calculation-value', $settings['calculationValue']);
        }

        // Next step trigger
        if (isset($settings['nextStepTrigger']) && $settings['nextStepTrigger']) {
            $this->set_attribute('field', 'data-next-step-trigger', $settings['nextStepTrigger']);
        }

        $checked = FormsHelper::handle_conditionally_selected($settings, $value);

        if (isset($settings['checked']) && $settings['checked']) {
            $checked = 'checked';
        }

        $output .= "<li " . $this->render_attributes("_root") . ">";

        $output .= "<input hidden type='radio' " . $this->render_attributes("field") . " " . $checked . " aria-label='" . $label . "' role='radio' aria-checked='" . ($checked ? 'true' : 'false') . "' />";
        $output .= "<label for='form-field-" . $id . '-' . $random_id . "'>";

        $output .= "<img data-image='1' src='" . $image1 . "' alt='" . $label . "' />";
        $output .= "<img data-image='2' src='" . $image2 . "' alt='" . $label . "' />";

        $output .= "</label>";
        $output .= "</li>";

        echo $output;
?>

    <?php
    }

    public static function render_builder()
    { ?>
        <script type="text/x-template" id="tmpl-bricks-element-brf-pro-forms-field-image-radio">
            <component :is="tag">
            <li class="image-radio">
                <input type="radio">
                <label>
                    <img data-image="1" :src="settings.image1.url" alt="">
                    <img data-image="2" :src="settings.image2.url" alt="">
                </label>
            </li>
            </component>
        </script>
<?php
    }
}
