<?php

namespace Bricksforge;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Global Classes Handler
 */
class PageTransitions
{
    private $settings = [];

    public function __construct()
    {
        $this->init();
    }

    public function init()
    {
        if ($this->activated() === true) {
            $this->settings = get_option('brf_page_transitions') ? get_option('brf_page_transitions') : [];

            add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
            add_action('wp_enqueue_scripts', [$this, 'add_custom_styles']);
            add_action('wp_footer', [$this, 'load_main_script'], 999);
            $this->add_attributes();
        }
    }

    public function enqueue_scripts()
    {
        wp_enqueue_script('bricksforge-swup');
        wp_enqueue_script('bricksforge-swup-head-plugin');
        wp_enqueue_script('bricksforge-swup-body-class-plugin');
        wp_enqueue_script('bricksforge-swup-accessibility-plugin');
        wp_enqueue_script('bricksforge-swup-morph-plugin');

        if (isset($this->settings->animationType) && $this->settings->animationType === 'gsap') {
            wp_enqueue_script('bricksforge-gsap');
        }

        if (isset($this->settings->animationType) && $this->settings->animationType === 'gsap') {
            wp_enqueue_script('bricksforge-swup-js-plugin');
        }
    }

    private function add_attributes()
    {
        add_filter('bricks/content/attributes', function ($attributes) {
            $attributes['data-post-id'] = get_the_ID();
            $attributes['class'] = 'brf-page-transition';

            return $attributes;
        });

        if ((isset($this->settings->animationType) && $this->settings->animationType === 'css') || !isset($this->settings->animationType)) {
            add_filter('bricks/content/attributes', function ($attributes) {
                $attributes['data-transition-type'] = "css";

                return $attributes;
            });
        }
    }

    public function add_custom_styles()
    {
        if (!isset($this->settings->animationType) || $this->settings->animationType !== 'css') {
            return;
        }

        $css = isset($this->settings->customCssCode) && !empty($this->settings->customCssCode) ? $this->settings->customCssCode : false;

        if (!$css) {
            return;
        }

        // Add custom CSS to the page
        wp_add_inline_style('bricksforge-style', $css);
    }

    public function load_main_script()
    {
        // Load the main script
        echo '<script src="' . BRICKSFORGE_ASSETS . '/js/bricksforge_transitions.js?ver=' . filemtime(BRICKSFORGE_PATH . '/assets/js/bricksforge_transitions.js') . '' . '" id="bricksforge-transitions-js"></script>';

        // Add the settings to an additoinal script
        echo '<script id="bricksforge-transitions-settings-js">var BRFTRANSITIONS = ' . json_encode($this->settings) . ';</script>';
    }

    public function activated()
    {
        return get_option('brf_activated_tools') && in_array(16, get_option('brf_activated_tools'));
    }
}
