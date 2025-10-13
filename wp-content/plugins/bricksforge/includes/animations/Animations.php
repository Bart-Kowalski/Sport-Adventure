<?php

namespace Bricksforge;

if (!defined('ABSPATH')) {
    exit;
}

$GLOBALS['brf_animator_needed'] = false;
$GLOBALS['brf_animator_needs_scrolltrigger'] = false;
$GLOBALS['brf_animator_needs_motionpath'] = false;

/**
 * Animations Handler
 */
class Animations
{

    public function __construct()
    {
        $this->init();
    }

    public function init()
    {
        if ($this->activated() === true) {
            $this->add_group_and_controls();
            add_filter('bricks/element/set_root_attributes', [$this, 'render_attributes'], 10, 2);
            add_action('wp_footer', [$this, 'load_scripts']);
            add_action('admin_footer', [$this, 'load_scripts']);
        }
    }

    public function render_attributes($attributes, $element)
    {
        if (
            isset($element->settings['brf_animations_active'])
            && $element->settings['brf_animations_active'] == true
        ) {
            global $brf_animator_needed;
            global $brf_animator_needs_scrolltrigger;
            global $brf_animator_needs_motionpath;

            $brf_animator_needed = true;

            if (isset($element->settings['brf_animations_trigger']) && $element->settings['brf_animations_trigger'] == 'viewport') {
                $brf_animator_needs_scrolltrigger = true;
            }

            if (isset($element->settings['brf_animations_method']) && $element->settings['brf_animations_method'] == 'motionPath') {
                $brf_animator_needs_motionpath = true;
            }

            $animation_data = [
                'brf_animations_trigger' => isset($element->settings['brf_animations_trigger']) ? $element->settings['brf_animations_trigger'] : null,
                'brf_animations_method' => isset($element->settings['brf_animations_method']) ? $element->settings['brf_animations_method'] : 'to',
                'brf_animations_object' => isset($element->settings['brf_animations_object']) ? $element->settings['brf_animations_object'] : [],
                'brf_animations_object_fromto' => isset($element->settings['brf_animations_object_fromto']) ? $element->settings['brf_animations_object_fromto'] : null,
                'brf_animations_fromto_duration' => isset($element->settings['brf_animations_fromto_duration']) ? $element->settings['brf_animations_fromto_duration'] : null,
                'brf_animations_fromto_delay' => isset($element->settings['brf_animations_fromto_delay']) ? $element->settings['brf_animations_fromto_delay'] : null,
                'brf_animations_click_target' => isset($element->settings['brf_animations_click_target']) ? $element->settings['brf_animations_click_target'] : null,
                'brf_animations_click_target_custom' => isset($element->settings['brf_animations_click_target_custom']) ? $element->settings['brf_animations_click_target_custom'] : null,
                'brf_animations_scrub' => isset($element->settings['brf_animations_scrub']) ? $element->settings['brf_animations_scrub'] : false,
                'brf_animations_markers' => isset($element->settings['brf_animations_markers']) ? $element->settings['brf_animations_markers'] : false,
                'brf_animations_start' => isset($element->settings['brf_animations_start']) ? $element->settings['brf_animations_start'] : null,
                'brf_animations_end' => isset($element->settings['brf_animations_end']) ? $element->settings['brf_animations_end'] : null,
                'brf_animations_motionpath' => isset($element->settings['brf_animations_motionpath']) ? $element->settings['brf_animations_motionpath'] : null,
                'brf_animations_motionpath_helper' => isset($element->settings['brf_animations_motionpath_helper']) ? $element->settings['brf_animations_motionpath_helper'] : false,
                'brf_animations_motionpath_autorotate' => isset($element->settings['brf_animations_motionpath_autorotate']) ? $element->settings['brf_animations_motionpath_autorotate'] : false,
                'brf_animations_motionpath_autorotate_value' => isset($element->settings['brf_animations_motionpath_autorotate_value']) ? $element->settings['brf_animations_motionpath_autorotate_value'] : null,
                'brf_animations_motionpath_ignore_parent' => isset($element->settings['brf_animations_motionpath_ignore_parent']) ? $element->settings['brf_animations_motionpath_ignore_parent'] : false,
                'brf_animations_motionpath_position_absolute' => isset($element->settings['brf_animations_motionpath_position_absolute']) ? $element->settings['brf_animations_motionpath_position_absolute'] : false,
                'brf_disable_for_breakpoints' => isset($element->settings['brf_disable_for_breakpoints']) ? $element->settings['brf_disable_for_breakpoints'] : null,
                'brf_animations_motionpath_autorotate_direction' => isset($element->settings['brf_animations_motionpath_autorotate_direction']) ? $element->settings['brf_animations_motionpath_autorotate_direction'] : false,
                'brf_animations_motionpath_autorotate_value_scroll_up' => isset($element->settings['brf_animations_motionpath_autorotate_value_scroll_up']) ? $element->settings['brf_animations_motionpath_autorotate_value_scroll_up'] : null,
                'brf_animations_motionpath_autorotate_value_scroll_down' => isset($element->settings['brf_animations_motionpath_autorotate_value_scroll_down']) ? $element->settings['brf_animations_motionpath_autorotate_value_scroll_down'] : null,
                'brf_animations_motionpath_repeat' => isset($element->settings['brf_animations_motionpath_repeat']) ? $element->settings['brf_animations_motionpath_repeat'] : 0,
                'brf_animations_motionpath_yoyo' => isset($element->settings['brf_animations_motionpath_yoyo']) ? $element->settings['brf_animations_motionpath_yoyo'] : false,
            ];

            $attributes['data-brf-animation'] = htmlspecialchars(json_encode($animation_data), ENT_QUOTES, 'UTF-8');
        }

        return $attributes;
    }

    public function load_scripts()
    {
        global $brf_animator_needed;
        global $brf_animator_needs_scrolltrigger;
        global $brf_animator_needs_motionpath;

        if (!$brf_animator_needed) {
            return;
        }

        wp_enqueue_script('bricksforge-animator');
        wp_enqueue_script('bricksforge-gsap');

        if ($brf_animator_needs_scrolltrigger) {
            wp_enqueue_script('bricksforge-gsap-scrolltrigger');
        }

        if ($brf_animator_needs_motionpath) {
            wp_enqueue_script('bricksforge-gsap-motionpath');
            wp_enqueue_script('bricksforge-gsap-motionpath-helper');
        }
    }

    public function activated()
    {
        return get_option('brf_activated_tools') && in_array(1, get_option('brf_activated_tools'));
    }

    public function add_group_and_controls()
    {
        if (bricks_is_frontend()) {
            return;
        }

        $elements = \Bricks\Elements::$elements;

        if (empty($elements)) {
            return;
        }

        $names = array_keys($elements);

        foreach ($names as $name) {
            add_filter("bricks/elements/{$name}/control_groups", [$this, 'add_control_group'], 10);
            add_filter("bricks/elements/{$name}/controls", [$this, 'add_controls'], 10);
        }
    }

    public function add_control_group($control_groups)
    {
        $control_groups['brf_animations'] = [
            'tab'   => 'style',
            'title' => esc_html__('Animations', 'bricksforge'),
        ];

        return $control_groups;
    }

    public function add_controls($controls)
    {
        $controls['brf_animations_active'] = [
            'tab'   => 'style',
            'group' => 'brf_animations',
            'label' => esc_html__('Use Animations', 'bricksforge'),
            'type'  => 'checkbox',
        ];
        $controls['brf_animations_trigger'] = [
            'required'    => ['brf_animations_active', '=', true],
            'tab'         => 'style',
            'group'       => 'brf_animations',
            'label'       => esc_html__('Trigger', 'bricksforge'),
            'type'        => 'select',
            'options'     => [
                'viewport'   => 'Enter Viewport',
                'click'      => 'Click',
                'mouseenter' => 'Hover'
            ],
            'inline'      => true,
            'placeholder' => esc_html__('Select Trigger', 'bricksforge'),
        ];
        $controls['brf_animations_click_target'] = [
            'required'    => [['brf_animations_active', '=', true], ['brf_animations_trigger', '!=', 'viewport']],
            'tab'         => 'style',
            'group'       => 'brf_animations',
            'label'       => esc_html__('Target', 'bricksforge'),
            'type'        => 'select',
            'options'     => [
                'this'   => 'This Element',
                'custom' => 'Other Element',
            ],
            'inline'      => true,
            'placeholder' => esc_html__('Target', 'bricksforge'),
        ];
        $controls['brf_animations_click_target_custom'] = [
            'required'    => [['brf_animations_click_target', '=', 'custom'], ['brf_animations_trigger', '!=', 'viewport']],
            'tab'         => 'style',
            'group'       => 'brf_animations',
            'label'       => esc_html__('Custom Selector', 'bricksforge'),
            'type'        => 'text',
            'inline'      => true,
            'placeholder' => esc_html__('.example', 'bricksforge'),
        ];
        $controls['brf_animations_method'] = [
            'required'    => ['brf_animations_active', '=', true],
            'tab'         => 'style',
            'group'       => 'brf_animations',
            'label'       => esc_html__('Method', 'bricksforge'),
            'type'        => 'select',
            'options'     => [
                'from'       => 'From',
                'to'         => 'To',
                'fromTo'     => 'From To',
                'motionPath' => 'Motion Path'
            ],
            'inline'      => true,
            'placeholder' => esc_html__('Select Method', 'bricksforge'),
        ];
        $controls['brf_animations_motionpath'] = [
            'required'    => [['brf_animations_method', '=', 'motionPath'], ['brf_animations_active', '=', true]],
            'tab'         => 'style',
            'group'       => 'brf_animations',
            'label'       => esc_html__('SVG Path', 'bricksforge'),
            'description' => esc_html__('The motion path along which to animate the target as string, for example: "M9,100c0,0,18-41,49-65"', 'bricksforge'),
            'type'        => 'text',
            'placeholder' => 'SVG Path'
        ];
        $controls['brf_animations_motionpath_helper'] = [
            'required'    => [['brf_animations_method', '=', 'motionPath'], ['brf_animations_active', '=', true]],
            'tab'         => 'style',
            'group'       => 'brf_animations',
            'label'       => esc_html__('Use Motion Path Helper', 'bricksforge'),
            'description' => esc_html__('This helper lets you interactively edit a motion path directly in the browser by dragging its anchors and control points', 'bricksforge'),
            'type'        => 'checkbox',
        ];
        $controls['brf_animations_object'] = [
            'required'    => [['brf_animations_active', '=', true], ['brf_animations_method', '!=', 'fromTo'], ['brf_animations_method', '!=', 'motionPath']],
            'tab'         => 'style',
            'group'       => 'brf_animations',
            'label'       => esc_html__('Animation', 'bricksforge'),
            'type'        => 'repeater',
            'description' => 'Multiple repeater fields are created as a timeline. So you can play several animations one after the other. (Currently only supported for Click & Hover trigger)',
            'placeholder' => esc_html__('Animation', 'bricksforge'),
            'fields'      => [
                'brf_animations_object_transform'     => [
                    'label'  => esc_html__('Transform', 'bricksforge'),
                    'type'   => 'transform',
                    'inline' => true,
                ],
                'brf_animations_object_filters'       => [
                    'label'  => esc_html__('Filters', 'bricksforge'),
                    'type'   => 'filters',
                    'inline' => true,
                ],
                'brf_animations_object_custom'        => [
                    'tab'   => 'style',
                    'group' => 'brf_animations',
                    'label' => esc_html__('Custom', 'bricksforge'),
                    'type'  => 'checkbox'
                ],
                'brf_animations_object_custom_object' => [
                    "required"    => ["brf_animations_object_custom", "=", true],
                    'label'       => esc_html__('Javascript Object', 'bricksforge'),
                    'type'        => 'code',
                    'clearable'   => false,
                    'inline'      => false,
                    'description' => 'An object with animation keys and values. You can use CSS properties here. Use double quotes for your keys. Example: { "opacity": 0, "color": "#FFFFFF" }',
                    'placeholder' => '{ "opacity": 0, "color": #FFFFFF }',
                    'mode'        => 'javascript',
                    'default'     => '{}'
                ],
                'brf_animations_object_duration'      => [
                    'label'  => esc_html__('Duration (s)', 'bricksforge'),
                    'type'   => 'number',
                    'inline' => true,
                ],
                'brf_animations_object_delay'         => [
                    'label'  => esc_html__('Delay (s)', 'bricksforge'),
                    'type'   => 'number',
                    'inline' => true,
                ],
            ],
        ];
        $controls['typeInfo'] = [
            'required' => [['brf_animations_active', '=', true], ['brf_animations_method', '=', 'fromTo']],
            'tab'      => 'style',
            'group'    => 'brf_animations',
            'content'  => esc_html__('Create two repeater fields. The first one is your "From" animation. The second is your "To" animation.', 'bricksforge'),
            'type'     => 'info',
        ];
        $controls['brf_animations_object_fromto'] = [
            'required'    => [['brf_animations_active', '=', true], ['brf_animations_method', '=', 'fromTo']],
            'tab'         => 'style',
            'group'       => 'brf_animations',
            'label'       => esc_html__('From To Animation', 'bricksforge'),
            'type'        => 'repeater',
            'placeholder' => esc_html__('Animation', 'bricksforge'),
            'fields'      => [
                'brf_animations_object_fromto_transform'     => [
                    'label'  => esc_html__('Transform', 'bricksforge'),
                    'type'   => 'transform',
                    'inline' => true,
                ],
                'brf_animations_object_fromto_filters'       => [
                    'label'  => esc_html__('Filters', 'bricksforge'),
                    'type'   => 'filters',
                    'inline' => true,
                ],
                'brf_animations_object_fromto_custom'        => [
                    'tab'   => 'style',
                    'group' => 'brf_animations',
                    'label' => esc_html__('Custom', 'bricksforge'),
                    'type'  => 'checkbox'
                ],
                'brf_animations_object_fromto_custom_object' => [
                    "required"    => ["brf_animations_object_fromto_custom", "=", true],
                    'label'       => esc_html__('Javascript Object', 'bricksforge'),
                    'type'        => 'code',
                    'clearable'   => false,
                    'inline'      => false,
                    'description' => 'An object with animation keys and values. You can use CSS properties here. Use double quotes for your keys. Example: { "opacity": 0, "color": "#FFFFFF" }',
                    'placeholder' => '{ "opacity": 0, "color": #FFFFFF }',
                    'mode'        => 'javascript',
                ],
            ],
        ];
        $controls['brf_animations_fromto_duration'] = [
            'required'    => [['brf_animations_active', '=', true], ['brf_animations_method', '!=', 'from'], ['brf_animations_method', '!=', 'to']],
            'tab'         => 'style',
            'group'       => 'brf_animations',
            'label'       => esc_html__('Duration (s)', 'bricksforge'),
            'type'        => 'number',
            'placeholder' => 1
        ];
        $controls['brf_animations_fromto_delay'] = [
            'required'    => [['brf_animations_active', '=', true], ['brf_animations_method', '!=', 'from'], ['brf_animations_method', '!=', 'to']],
            'tab'         => 'style',
            'group'       => 'brf_animations',
            'label'       => esc_html__('Delay (s)', 'bricksforge'),
            'type'        => 'number',
            'placeholder' => 0
        ];

        $controls['brf_animations_motionpath_repeat'] = [
            'required'    => [['brf_animations_active', '=', true], ['brf_animations_trigger', '=', 'viewport'], ['brf_animations_method', '=', 'motionPath']],
            'tab'         => 'style',
            'group'       => 'brf_animations',
            'label'       => esc_html__('Repeat', 'bricksforge'),
            'type'        => 'number',
            'placeholder' => 0
        ];

        $controls['brf_animations_motionpath_yoyo'] = [
            'required' => [['brf_animations_active', '=', true], ['brf_animations_method', '=', 'motionPath']],
            'tab'      => 'style',
            'group'    => 'brf_animations',
            'label'    => esc_html__('Yoyo', 'bricksforge'),
            'type'     => 'checkbox',
        ];

        $controls['brf_animations_motionpath_autorotate'] = [
            'required' => [['brf_animations_active', '=', true], ['brf_animations_method', '=', 'motionPath']],
            'tab'      => 'style',
            'group'    => 'brf_animations',
            'label'    => esc_html__('Auto Rotate', 'bricksforge'),
            'type'     => 'checkbox',
        ];
        // Auto Rotate Value
        $controls['brf_animations_motionpath_autorotate_value'] = [
            'required'    => [['brf_animations_active', '=', true], ['brf_animations_method', '=', 'motionPath'], ['brf_animations_motionpath_autorotate', '=', true]],
            'tab'         => 'style',
            'group'       => 'brf_animations',
            'label'       => esc_html__('Auto Rotate Value in Degrees', 'bricksforge'),
            'description' => 'Leave empty for default value',
            'type'        => 'number',
            'placeholder' => 0
        ];
        // Update rotation on scroll direction change
        $controls['brf_animations_motionpath_autorotate_direction'] = [
            'required'    => [['brf_animations_active', '=', true], ['brf_animations_method', '=', 'motionPath'], ['brf_animations_motionpath_autorotate', '=', true]],
            'tab'         => 'style',
            'group'       => 'brf_animations',
            'label'       => esc_html__('Update Rotation on Scroll Direction Change', 'bricksforge'),
            'type'        => 'checkbox',
        ];

        // Value Scroll Up
        $controls['brf_animations_motionpath_autorotate_value_scroll_up'] = [
            'required'    => [['brf_animations_active', '=', true], ['brf_animations_method', '=', 'motionPath'], ['brf_animations_motionpath_autorotate', '=', true], ['brf_animations_motionpath_autorotate_direction', '=', true]],
            'tab'         => 'style',
            'group'       => 'brf_animations',
            'label'       => esc_html__('Value Scroll Up', 'bricksforge'),
            'type'        => 'number',
            'placeholder' => 0
        ];

        // Value Scroll Down
        $controls['brf_animations_motionpath_autorotate_value_scroll_down'] = [
            'required'    => [['brf_animations_active', '=', true], ['brf_animations_method', '=', 'motionPath'], ['brf_animations_motionpath_autorotate', '=', true], ['brf_animations_motionpath_autorotate_direction', '=', true]],
            'tab'         => 'style',
            'group'       => 'brf_animations',
            'label'       => esc_html__('Value Scroll Down', 'bricksforge'),
            'type'        => 'number',
            'placeholder' => 0
        ];

        $controls['brf_animations_motionpath_ignore_parent'] = [
            'required' => [['brf_animations_active', '=', true], ['brf_animations_method', '=', 'motionPath']],
            'tab'      => 'style',
            'group'    => 'brf_animations',
            'label'    => esc_html__('Ignore Parent Dimensions', 'bricksforge'),
            'type'     => 'checkbox',
        ];
        $controls['brf_animations_motionpath_position_absolute'] = [
            'required' => [['brf_animations_active', '=', true], ['brf_animations_method', '=', 'motionPath']],
            'tab'      => 'style',
            'group'    => 'brf_animations',
            'label'    => esc_html__('Set Position to Absolute', 'bricksforge'),
            'type'     => 'checkbox',
        ];
        $controls['brf_animations_start'] = [
            'required'    => [['brf_animations_active', '=', true], ['brf_animations_trigger', '=', 'viewport']],
            'tab'         => 'style',
            'group'       => 'brf_animations',
            'label'       => esc_html__('Start Position', 'bricksforge'),
            'type'        => 'text',
            'placeholder' => 'top bottom',
            'description' => 'top bottom = The animation starts when the top of the element hits the bottom of the viewport'
        ];
        $controls['brf_animations_end'] = [
            'required'    => [['brf_animations_active', '=', true], ['brf_animations_trigger', '=', 'viewport']],
            'tab'         => 'style',
            'group'       => 'brf_animations',
            'label'       => esc_html__('End Position', 'bricksforge'),
            'type'        => 'text',
            'placeholder' => 'bottom top',
            'description' => 'bottom top = The animation ends when the bottom of the element hits the top of the viewport'
        ];
        $controls['brf_animations_scrub'] = [
            'required'    => [['brf_animations_active', '=', true], ['brf_animations_trigger', '=', 'viewport']],
            'tab'         => 'style',
            'group'       => 'brf_animations',
            'label'       => esc_html__('Scrub', 'bricksforge'),
            'type'        => 'checkbox',
            'placeholder' => false
        ];
        $controls['brf_animations_markers'] = [
            'required'    => [['brf_animations_active', '=', true], ['brf_animations_trigger', '=', 'viewport']],
            'tab'         => 'style',
            'group'       => 'brf_animations',
            'label'       => esc_html__('Show Markers (for development)', 'bricksforge'),
            'type'        => 'checkbox',
            'placeholder' => false
        ];
        $controls['brf_disable_for_breakpoints'] = [
            'required'    => [['brf_animations_active', '=', true]],
            'tab'         => 'style',
            'group'       => 'brf_animations',
            'label'       => esc_html__('Disable from Breakpoint', 'bricksforge'),
            'type'        => 'select',
            'options'     => $this->get_breakpoint_options(),
            'multiple'    => false,
            'placeholder' => false
        ];
        $controls['apply'] = [
            'group'  => 'brf_animations',
            'type'   => 'apply',
            'reload' => true,
            'label'  => esc_html__('Render Changes', 'bricksforge'),
        ];

        return $controls;
    }

    function get_breakpoint_options()
    {
        $breakpoints = \Bricks\Breakpoints::$breakpoints;
        $array = [];
        foreach ($breakpoints as $bp) {
            $array[$bp['width']] = $bp['label'];
        }

        return $array;
    }
}
