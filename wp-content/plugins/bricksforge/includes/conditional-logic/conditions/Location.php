<?php

namespace Bricksforge\ConditionalLogic\Group;

if (!defined('ABSPATH')) {
    exit;
}

class Location
{

    public static function build_options()
    {
        $options = [];

        // Browser Language
        $options[] = [
            'key'   => 'bricksforge_location_browser_language',
            'label' => esc_html__('Browser Language', 'bricksforge'),
            'group' => 'group_bricksforge_location',
            'compare' => [
                'type'        => 'select',
                'options'     =>  [
                    '==' => esc_html__('Is', 'bricksforge'),
                    '!=' => esc_html__('Is not', 'bricksforge'),
                ],
                'placeholder' => esc_html__('Is', 'bricksforge'),
            ],
            'value'   => [
                'label' => esc_html__('Language Code', 'bricksforge'),
                'type'        => 'text',
                'placeholder' => esc_html__('en', 'bricksforge'),
            ],
        ];

        // Website Language
        $options[] = [
            'key'   => 'bricksforge_location_current_language',
            'label' => esc_html__('Website Language', 'bricksforge'),
            'group' => 'group_bricksforge_location',
            'compare' => [
                'type'        => 'select',
                'options'     =>  [
                    '==' => esc_html__('Is', 'bricksforge'),
                    '!=' => esc_html__('Is not', 'bricksforge'),
                ],
                'placeholder' => esc_html__('Is', 'bricksforge'),
            ],
            'value'   => [
                'label' => esc_html__('Language Code', 'bricksforge'),
                'type'        => 'text',
                'placeholder' => esc_html__('en', 'bricksforge'),
            ],
        ];

        // Country
        $options[] = [
            'key'   => 'bricksforge_location_country',
            'label' => esc_html__('Country (Geolocation)', 'bricksforge'),
            'group' => 'group_bricksforge_location',
            'compare' => [
                'type'        => 'select',
                'options'     =>  [
                    '==' => esc_html__('Is', 'bricksforge'),
                    '!=' => esc_html__('Is not', 'bricksforge'),
                ],
                'placeholder' => esc_html__('Is', 'bricksforge'),
            ],
            'value'   => [
                'label' => esc_html__('Country Code', 'bricksforge'),
                'type'        => 'text',
                'placeholder' => esc_html__('en', 'bricksforge'),
            ],
        ];

        return $options;
    }

    public static function result($condition)
    {
        $result = true;

        $compare = isset($condition['compare']) ? $condition['compare'] : '==';
        $user_value = isset($condition['value']) ? \bricks_render_dynamic_data($condition['value']) : '';

        switch ($condition['key']) {
            case 'bricksforge_location_browser_language':
                $user_value = strtolower($user_value);
                $browser_language = strtolower(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2));

                if ($compare === '==') {
                    $result = $browser_language === $user_value;
                } else {
                    $result = $browser_language !== $user_value;
                }
                break;
            case 'bricksforge_location_country':
                $user_value = strtolower($user_value);
                $country_code = self::get_country_code();

                if ($compare === '==') {
                    $result = $country_code === $user_value;
                } else {
                    $result = $country_code !== $user_value;
                }
                break;
            case 'bricksforge_location_current_language':
                $user_value = strtolower($user_value);

                // Default to WordPress locale
                $current_language = \get_locale();

                // If format en_US, extract en
                if (strpos($current_language, '_') !== false) {
                    $current_language = substr($current_language, 0, strpos($current_language, '_'));
                }

                // TranslatePress
                if (class_exists('TRP_Translate_Press')) {
                    global $TRP_LANGUAGE;
                    $current_language = isset($TRP_LANGUAGE) ? $TRP_LANGUAGE : $current_language;
                }
                // WPML
                elseif (defined('ICL_LANGUAGE_CODE')) {
                    $wpml_language = apply_filters('wpml_current_language', NULL);
                    $current_language = !empty($wpml_language) ? $wpml_language : $current_language;
                }
                // Polylang
                elseif (class_exists('Polylang') && function_exists('pll_current_language')) {
                    $current_language = pll_current_language();
                }

                // Perform comparison
                $current_language = strtolower($current_language);

                if ($compare === '==') {
                    $result = $current_language === $user_value;
                } else {
                    $result = $current_language !== $user_value;
                }
                break;
            default:
                break;
        }

        return $result;
    }

    public static function get_country_code($ip = NULL, $deep_detect = TRUE)
    {
        if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
            $ip = $_SERVER["REMOTE_ADDR"];
            if ($deep_detect) {
                if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
                    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
                    $ip = $_SERVER['HTTP_CLIENT_IP'];
            }
        }

        if (filter_var($ip, FILTER_VALIDATE_IP)) {
            //$ip = str_replace('.', '', $ip);

            $ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));

            if (@strlen(trim($ipdat->geoplugin_countryCode)) == 2) {
                return strtolower($ipdat->geoplugin_countryCode);
            }
        }

        return 'unknown';
    }
}
