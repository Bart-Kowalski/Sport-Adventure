<?php

// Define the namespace for the Parallax class
namespace Bricksforge;

use \Bricksforge\Helper\ElementsHelper as ElementsHelper;

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Parallax class definition
class Parallax
{
    /**
     * Breakpoints
     *
     * @var array
     */
    protected $breakpoints = [];

    /**
     * Constructor
     */
    public function __construct()
    {
        // Initialize the class
        $this->init();
    }

    /**
     * Check if the tool is activated
     *
     * @return boolean
     */
    private function activated()
    {
        // Returns true if the tool is activated
        return get_option('brf_activated_tools') && in_array(15, get_option('brf_activated_tools'));
    }

    /**
     * Enqueue scripts
     *
     * @return void
     */
    public function enqueue_scripts()
    {
        if (!class_exists('\Bricks\Database')) {
            return;
        }

        $post_id = get_the_ID();

        $data = ElementsHelper::$page_data_string;
        $load = false;

        // Strops bricksforge-parallax-enable
        $load = strpos($data, 'bricksforge-parallax-enable') !== false;

        if (!$load) {
            return;
        }

        // Enqueue the 'bricksforge-rellax' script
        wp_enqueue_script('bricksforge-rellax');

        // Prepare the script to initialize the parallax effect
        $script = '
        document.addEventListener("DOMContentLoaded", function() {
            var rellaxElements = document.querySelectorAll("[data-rellax-speed]");
            rellaxElements.forEach(function(element) {
                var breakpoint = element.getAttribute("data-rellax-breakpoint");
                if (breakpoint && window.innerWidth <= breakpoint) {
                    return;
                }

                new Rellax(element);
            });
         });
        ';

        // Add the inline script
        wp_add_inline_script('bricksforge-rellax', $script);
    }

    /** 
     * Initialize the class
     * 
     * @return void
     */
    public function init()
    {
        // If the tool is not activated, return
        if (!$this->activated()) {
            return;
        }

        // Add the 'enqueue_scripts' method to the 'wp_enqueue_scripts' action
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));

        // Add groups and controls to the builder
        $this->add_group_and_controls();

        // Add the 'render_attributes' method to the 'bricks/element/render_attributes' filter
        add_filter('bricks/element/render_attributes', [$this, 'render_attributes'], 10, 3);
    }

    /**
     * Add control groups and controls
     *
     * @return void
     */
    public function add_group_and_controls()
    {
        // If \Bricks\Elements is not defined, return
        if (!class_exists('\Bricks\Elements')) {
            return;
        }

        if (!bricks_is_builder()) {
            return;
        }

        // Get the elements and their names
        $elements = \Bricks\Elements::$elements;
        $names = array_keys($elements);

        // For each element, add control groups and controls
        foreach ($names as $name) {
            add_filter("bricks/elements/{$name}/control_groups", [$this, 'add_control_groups']);
            add_filter("bricks/elements/{$name}/controls", [$this, 'add_controls']);
        }
    }

    /**
     * Add control groups
     *
     * @param array $control_groups
     * @return array
     */
    public function add_control_groups($control_groups)
    {
        // Add the 'bricksforge-parallax-group' control group
        $control_groups['bricksforge-parallax-group'] = [
            'tab' => 'style',
            'title' => __('Parallax', 'bricksforge'),
        ];

        // Return the control groups
        return $control_groups;
    }

    /**
     * Add controls
     *
     * @param array $controls
     * @return array
     */
    public function add_controls($controls)
    {
        // If breakpoints are empty, get the breakpoints
        if (empty($this->breakpoints)) {
            $this->breakpoints = $this->get_breakpoints();
        }

        // Add the 'bricksforge-parallax-enable' control
        $controls['bricksforge-parallax-enable'] = [
            'tab' => 'style',
            'group' => 'bricksforge-parallax-group',
            'label' => __('Enable', 'bricksforge'),
            'type' => 'checkbox',
        ];
        // Add the 'bricksforge-parallax-speed' control
        $controls['bricksforge-parallax-speed'] = [
            'tab' => 'style',
            'group' => 'bricksforge-parallax-group',
            'label' => __('Speed', 'bricksforge'),
            'type' => 'number',
            'description' => __('The speed of the parallax effect. A negative value will make the element move slower than regular scrolling. A positive value will make the element move faster.', 'bricksforge'),
            'required' => [
                'bricksforge-parallax-enable',
                '=',
                true,
            ],
        ];

        // Add the 'bricksforge-parallax-from-breakpoint' control
        $controls['bricksforge-parallax-from-breakpoint'] = [
            'tab' => 'style',
            'group' => 'bricksforge-parallax-group',
            'label' => __('From breakpoint', 'bricksforge'),
            'type' => 'select',
            'options' => $this->breakpoints,
            'required' => [
                'bricksforge-parallax-enable',
                '=',
                true,
            ],
        ];

        // Return the controls
        return $controls;
    }

    /**
     * Get breakpoints
     *
     * @return array
     */
    public function get_breakpoints()
    {
        // If \Bricks\Breakpoints is not defined, return
        if (!class_exists('\Bricks\Breakpoints')) {
            return [];
        }

        // Get the breakpoints
        $breakpoints = \Bricks\Breakpoints::get_breakpoints();

        // Transform the breakpoints array to the desired structure
        $transformed_breakpoints = [];
        foreach ($breakpoints as $breakpoint) {
            $transformed_breakpoints[$breakpoint['width']] = $breakpoint['label'];
        }

        // Return the transformed breakpoints
        return (array)$transformed_breakpoints;
    }

    /**
     * Render attributes
     *
     * @param array $attributes
     * @param string $key
     * @param object $element
     * @return array
     */
    public function render_attributes($attributes, $key, $element)
    {
        // If the parallax effect is enabled and the speed is set, add the 'data-rellax-speed' attribute
        if (isset($element->settings["bricksforge-parallax-enable"]) && $element->settings["bricksforge-parallax-enable"] == true && isset($element->settings["bricksforge-parallax-speed"])) {
            $attributes[$key]['data-rellax-speed'] = $element->settings["bricksforge-parallax-speed"];

            // If the 'bricksforge-parallax-from-breakpoint' setting is set, add the 'data-rellax-breakpoint' attribute
            if (isset($element->settings["bricksforge-parallax-from-breakpoint"])) {
                $attributes[$key]['data-rellax-breakpoint'] = $element->settings["bricksforge-parallax-from-breakpoint"];
            }
        }

        // Return the attributes
        return $attributes;
    }
}
