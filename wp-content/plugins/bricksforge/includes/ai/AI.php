<?php

namespace Bricksforge;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Maintenance Handler
 */
class AI
{

    public function __construct()
    {
        $this->init();
    }

    public function init()
    {
        if ($this->activated() === false) {
            return;
        }
    }

    public function activated()
    {
        return get_option('brf_activated_tools') && in_array(14, get_option('brf_activated_tools'));
    }

    public function run($prompt)
    {
        if ($this->activated() === false) {
            return;
        }

        try {
            return $this->run_task($prompt);
        } catch (\Exception $e) {
            error_log($e->getMessage());
        }
    }

    public function run_task($prompt)
    {
        $api_key = null;

        $ai_settings = array_values(array_filter(get_option('brf_tool_settings'), function ($tool) {
            return $tool->id == 14;
        }));

        if (count($ai_settings) === 0) {
            if (!$api_key) {
                wp_send_json_error([
                    'message' => 'No API key provided.',
                    'code' => 'no_api_key',
                    'status' => 400
                ]);
            }
        }

        $ai_settings = $ai_settings[0];

        if (isset($ai_settings->settings->apiKey)) {
            $utils = new \Bricksforge\Api\Utils();
            $api_key = $utils->decrypt($ai_settings->settings->apiKey);
        }

        if (!$api_key) {
            wp_send_json_error([
                'message' => 'No API key provided.',
                'code' => 'no_api_key',
                'status' => 400
            ]);
        }

        $animation_controls = ['x', 'y', 'scale', 'scaleX', 'scaleY', 'rotate', 'rotateX', 'rotateY', 'rotateZ', 'skew', 'skewX', 'skewY', 'opacity', 'fillOpacity', 'strokeOpacity', 'color', 'backgroundColor', 'fill', 'stroke', 'borderRadius', 'borderTopLeftRadius', 'borderTopRightRadius', 'borderBottomRightRadius', 'borderBottomLeftRadius', 'padding', 'paddingTop', 'paddingRight', 'paddingBottom', 'paddingLeft', 'margin', 'marginTop', 'marginRight', 'marginBottom', 'marginLeft', 'width', 'minWidth', 'height', 'minHeight', 'filter', 'drawSVG'];
        $animation_properties = [
            'method' => [
                'type' => 'string',
                'description' => 'The gsap method to use.',
                'enum' => ['from', 'to', 'fromTo'],
            ],
            'selector' => [
                'type' => 'string',
                'description' => 'The selector of the element to animate. The prompt MUST include a very clear selector which starts with . or # (for example .card or .card-wrapper). If no clear selector is provided, choose: ".your-selector"',
            ],
            'duration' => [
                'type' => 'number',
                'description' => 'The duration of the animation in seconds.',
            ],
            'delay' => [
                'type' => 'number',
                'description' => 'The delay of the animation in seconds.',
            ],
            'ease' => [
                'type' => 'string',
                'description' => 'The ease of the animation.',
            ],
            'data' => [
                'type' => 'string',
                'description' => 'The data to animate, for example: {x: 200, y: 50, repeat: -1}. Wrap the data with {}.  This MUST be a stringified JSON object.',
            ],
            'data2' => [
                'type' => 'string',
                'description' => 'The second data for fromTo animations, for example: {x: 200, y: 50, repeat: -1}. Wrap the data with {}.  This MUST be a stringified JSON object.',
            ],
            'yoyo' => [
                'type' => 'boolean',
                'description' => 'The yoyo of the animation.',
            ],
            'position' => [
                'type' => 'string',
                'description' => 'The position of the animation. In most cases this will be ">".',
                'enum' => ['>', '<', 'custom']
            ],
            'customPosition' => [
                'type' => 'string',
                'description' => 'The custom position of the animation.',
            ],
            'splitText' => [
                'type' => 'boolean',
                'description' => 'The splitText of the animation. If this is true, you MUST provide a splitTextType. Also, you MUST provide a stagger and staggerValue.',
            ],
            'splitTextType' => [
                'type' => 'string',
                'description' => 'The splitTextType of the animation.',
                'enum' => ['chars', 'words', 'lines']
            ],
            'stagger' => [
                'type' => 'boolean',
                'description' => 'The stagger of the animation.',
            ],
            'staggerValue' => [
                'type' => 'number',
                'description' => 'The staggerValue of the animation.',
            ],
            'repeat' => [
                'type' => 'number',
                'description' => 'The repeat of the animation.',
            ],
        ];

        $body = array(
            'model' => 'gpt-4o-mini',
            'messages' => [
                [
                    'role' => 'user',
                    'content' => $prompt
                ]
            ],
            'functions' => [
                [
                    'name' => 'create_gsap_animation_objects',
                    'description' => 'Creates GSAP animation objects from the provided data. The data property MUST be a stringified JSON object. For colors, always use HEX format.',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => [
                            "animationObjects" => [
                                "type" => "array",
                                "items" => [
                                    "type" => "object",
                                    "properties" => $animation_properties,
                                    "required" => [
                                        "method",
                                        "duration",
                                        "delay",
                                        "data",
                                        "position",
                                    ]
                                ]
                            ],
                        ],
                        'required' => [
                            'animationObjects'
                        ]
                    ]
                ]
            ],
            'max_tokens' => 1000,
            'temperature' => 0.2,
            'function_call' => [
                'name' => 'create_gsap_animation_objects',
            ]
        );

        $args = array(
            'method' => 'POST',
            'timeout' => 20,
            'headers' => array(
                'Authorization' => 'Bearer ' . $api_key, // Add the decrypted API key in the Authorization header
                'Content-Type' => 'application/json',
            ),
            'body' => json_encode($body), // The data you want to send to the ChatGPT API
        );

        $response = wp_remote_post('https://api.openai.com/v1/chat/completions', $args); // Send the request to the ChatGPT API

        if (is_wp_error($response)) {
            $error_message = $response->get_error_message();
            return $error_message;
        }

        $response_body = json_decode(wp_remote_retrieve_body($response), true); // Get the response body from the ChatGPT API

        return $response_body;
    }
}
