<?php

if (!defined('ABSPATH')) {
    exit;
}

add_filter('bricks/assets/generate_css_from_element', 'bricksforge_filter_generate_css_from_element', 10, 3);
function bricksforge_filter_generate_css_from_element($element_name, $current_element, $css_type)
{

    $additional_fields = [
        'brf-pro-forms-field-checkbox',
        'brf-pro-forms-field-card-checkbox',
        'brf-pro-forms-field-image-checkbox',
        'brf-pro-forms-field-radio',
        'brf-pro-forms-field-card-radio',
        'brf-pro-forms-field-image-radio',
        'brf-pro-forms-field-option',
    ];

    $element_name = array_merge($element_name, $additional_fields);

    return $element_name;
}

add_filter('bricks/frontend/render_data', 'bricksforge_filter_render_data', 10, 3);
function bricksforge_filter_render_data($content, $post, $area)
{
    return $content;
}
