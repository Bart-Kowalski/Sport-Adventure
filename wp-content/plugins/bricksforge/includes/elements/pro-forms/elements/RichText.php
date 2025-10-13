<?php

namespace Bricks;

use \Bricksforge\ProForms\Helper as Helper;

if (!defined('ABSPATH'))
    exit;

class Brf_Pro_Forms_RichText extends \Bricks\Element
{

    public $category = 'bricksforge forms';
    public $name = 'brf-pro-forms-field-richtext';
    public $icon = 'fa-solid fa-underline';
    public $css_selector = '';
    public $scripts = [];
    public $nestable = false;

    public function get_label()
    {
        return esc_html__("Rich Text", 'bricksforge');
    }

    public function enqueue_scripts()
    {
        wp_enqueue_script('bricksforge-elements');

        $rich_style = isset($this->settings['quillStyle']) ? $this->settings['quillStyle'] : 'snow';

        switch ($rich_style) {
            case 'snow':
                wp_enqueue_script('bricksforge-quill');
                wp_enqueue_style('bricksforge-quill-snow');
                break;
            case 'bubble':
                wp_enqueue_script('bricksforge-quill');
                wp_enqueue_style('bricksforge-quill-bubble');
                break;
            case 'wordpress':
                wp_enqueue_style('editor-buttons');
                wp_enqueue_script('tinymce', includes_url('js/tinymce/tinymce.min.js'), array(), false, true);
                break;
        }
    }

    public function set_control_groups()
    {
        $this->control_groups['general'] = [
            'title'    => esc_html__('General', 'bricksforge'),
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

        // Placeholder
        $this->controls['placeholder'] = [
            'group' => 'general',
            'label'          => esc_html__('Placeholder', 'bricksforge'),
            'type'           => 'text',
            'inline'         => true,
            'spellcheck'     => false,
            'hasDynamicData' => true,
        ];

        $this->controls['quillStyle'] = [
            'group' => 'general',
            'label' => esc_html__('Style', 'bricks'),
            'type'  => 'select',
            'options' => [
                'snow' => esc_html__('Flat Toolbar', 'bricks'),
                'bubble' => esc_html__('Tooltip Based', 'bricks'),
                'wordpress' => esc_html__('WordPress', 'bricks'),
            ],
            'default' => 'snow',
            'description' => esc_html__('Default: Flat Toolbar', 'bricks'),
        ];

        $this->controls['quillFormats'] = [
            'group' => 'general',
            'label' => esc_html__('Formats', 'bricks'),
            'type'  => 'select',
            'options' => Helper::get_quill_formats(),
            'multiple' => true,
            'description' => esc_html__('Default: Headings, Bold, Italic, Underline, Link', 'bricks'),
            'required' => [['quillStyle', '!=', 'wordpress']],
        ];

        $this->controls['mceFormatsInfo'] = [
            'group' => 'general',
            'label' => esc_html__('Info', 'bricks'),
            'type'  => 'info',
            'required' => [['quillStyle', '=', 'wordpress']],
            'content' => esc_html__('A list of toolbar buttons you can use can be found here:', 'bricks') . ' <a target="_blank" href="https://www.tiny.cloud/docs/tinymce/6/available-toolbar-buttons/">TinyMCE Docs</a>',

        ];

        $this->controls['mceFormats'] = [
            'group' => 'general',
            'label' => esc_html__('Formats', 'bricks'),
            'type'  => 'text',
            'description' => esc_html__('Example: undo redo | formatselect | bold italic ', 'bricks'),
            'required' => [['quillStyle', '=', 'wordpress']],
        ];


        $this->controls['quillUseBricksColors'] = [
            'group' => 'general',
            'label' => esc_html__('Use Bricks Colors', 'bricks'),
            'type'  => 'checkbox',
            'default' => true,
            'description' => esc_html__('If checked, the editor will use the colors defined in a Bricks Color Palette ', 'bricks'),
            'required' => [['quillStyle', '!=', 'wordpress']],
        ];

        $this->controls['quillBricksColorPalette'] = [
            'group' => 'general',
            'label' => esc_html__('Color Palette', 'bricks'),
            'type'  => 'select',
            'options' => Helper::get_color_palettes(),
            'default' => 'default',
            'description' => esc_html__('Choose your Color Palette', 'bricks'),
            'required' => [['quillUseBricksColors', '=', true], ['quillStyle', '!=', 'wordpress']],
        ];


        // Rich Text
        $this->controls['quillInitialHeight'] = [
            'group' => 'general',
            'label'    => esc_html__('Min Height', 'bricks'),
            'type'     => 'number',
            'units'    => true,
            'css'      => [
                [
                    'property' => 'min-height',
                    'selector' => '.brf-rich-text-container',
                ],
                [
                    'property' => 'min-height',
                    'selector' => '.mce-panel iframe',
                ]
            ],
            'description' => esc_html__('Default: 120px', 'bricks'),
        ];

        $this->controls['quillReadOnly'] = [
            'group' => 'general',
            'label' => esc_html__('Read Only', 'bricks'),
            'type'  => 'checkbox',
            'default' => false,
            'description' => esc_html__('If checked, the editor will be read only.', 'bricks'),
            'required' => [['quillStyle', '!=', 'wordpress']],
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

        $placeholder = isset($settings['placeholder']) ? bricks_render_dynamic_data($settings['placeholder']) : false;
        $value = isset($settings['value']) ? bricks_render_dynamic_data($settings['value']) : '';

        $required = isset($settings['required']) ? $settings['required'] : false;

        if (!$id && bricks_is_builder()) {
            return $this->render_element_placeholder(
                [
                    'title' => esc_html__('You have to set an ID for your element.', 'bricksforge'),
                ]
            );
        }

        $value = bricks_render_dynamic_data($value);

        // Sanitize the value
        $value = wp_kses_post($value);

        // Convert " to '
        $value = str_replace('"', "'", $value);

        // We remove all html comments
        $value = preg_replace('/<!--(.|\s)*?-->/', '', $value);

        // Remove <p><br></p>
        $value = str_replace("<p><br></p>", "", $value);

        /**
         * Wrapper
         */
        $this->set_attribute('_root', 'class', 'pro-forms-builder-field');
        $this->set_attribute('_root', 'class', 'form-group');
        $this->set_attribute('_root', 'class', 'brf-field-rich-text');
        $this->set_attribute('_root', 'data-element-id', $this->id);

        // Post Context
        if (isset($settings['postContext'])) {
            $this->set_attribute('_root', 'data-context', bricks_render_dynamic_data($settings['postContext']));
        }

        // Store the quill style in a data attribute
        if (isset($settings['quillStyle']) && !empty($settings['quillStyle'])) {
            $this->set_attribute("_root", 'data-theme', $settings['quillStyle']);
        }

        // Store the placeholder in a data attribute
        if (isset($settings['placeholder']) && !empty($settings['placeholder'])) {
            $this->set_attribute("_root", 'data-placeholder', $settings['placeholder']);
        }

        // Store the read only state (quillReadOnly as checkbox) in a data attribute
        if (isset($settings['quillReadOnly']) && !empty($settings['quillReadOnly'])) {
            $this->set_attribute("_root", 'data-readonly', "true");
        }

        // Store the formats in a data attribute (quillFormats). Store it comma separated
        if (isset($settings['quillFormats']) && !empty($settings['quillFormats'])) {
            $this->set_attribute("_root", 'data-formats', implode(',', $settings['quillFormats']));
        }

        // Store the formats in a data attribute (mceFormats). Store it comma separated
        if (isset($settings['quillStyle']) && $settings['quillStyle'] == 'wordpress' && isset($settings['mceFormats']) && !empty($settings['mceFormats'])) {
            $this->set_attribute("_root", 'data-formats-mce', $settings['mceFormats']);
        }

        // If the field quillUseBricksColors is set and quillColorPalette is set, store the colors in a data attribute
        if (isset($settings['quillUseBricksColors']) && !empty($settings['quillUseBricksColors']) && isset($settings['quillBricksColorPalette']) && !empty($settings['quillBricksColorPalette'])) {
            $palette = get_option(BRICKS_DB_COLOR_PALETTE, []);

            // Get the palette with the key "name" of the field quillBricksColorPalette
            $palette = array_filter($palette, function ($item) use ($settings) {
                return $item['name'] === $settings['quillBricksColorPalette'];
            });

            if (empty($palette)) {
                $palette = \Bricks\Builder::default_color_palette();
            }

            // If the palette is found, store the colors in a data attribute
            if (!empty($palette)) {

                $colors_hex = array_reduce($palette, function ($result, $palette) {
                    $hex_values = array_map(function ($color) {
                        if (isset($color['hex'])) {
                            return $color['hex'];
                        }
                    }, $palette['colors']);
                    return array_merge($result, $hex_values);
                }, []);

                $colors_string = implode(',', $colors_hex);

                $this->set_attribute("_root", 'data-colors', $colors_string);
            }
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

        if ($placeholder) {
            $this->set_attribute('field', 'placeholder', $placeholder);
        }

        if ($value) {
            $this->set_attribute('field', 'value', $value);
        }

        if ($required) {
            $this->set_attribute('field', 'required', $required);
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

        $rich_style = isset($settings['quillStyle']) ? $settings['quillStyle'] : 'snow';

?>
        <div <?php echo $this->render_attributes('_root'); ?>>
            <?php if ($label && $show_labels) : ?>
                <label <?php echo $this->render_attributes('label'); ?> for="form-field-<?php echo $random_id; ?>"><?php echo $label; ?></label>
            <?php endif; ?>

            <?php if ($rich_style == 'wordpress') { ?>
                <textarea class="brf-rich-text-container" <?php echo $this->render_attributes("field"); ?>><?php echo $value ?></textarea>
            <?php } else { ?>
                <div class="brf-rich-text-container">
                    <?php echo $value ?>
                </div>
            <?php } ?>
            <input data-type="rich-text" type="hidden" value="<?php echo $value ?>" <?php echo $this->render_attributes("field"); ?>>
        </div>
<?php
    }
}
