<?php

namespace Bricks;

use \Bricksforge\ProForms\Helper as Helper;

if (!defined('ABSPATH'))
    exit;

class Brf_Pro_Forms_Turnstile extends \Bricks\Element
{

    public $category = 'bricksforge forms';
    public $name = 'brf-pro-forms-field-turnstile';
    public $icon = 'fa-solid fa-shield';
    public $css_selector = '';
    public $scripts = [];
    public $nestable = true;
    private $turnstile_key;

    public function get_label()
    {
        return esc_html__("Turnstile", 'bricksforge');
    }

    public function enqueue_scripts()
    {
        wp_enqueue_script('bricksforge-elements');
        wp_enqueue_script('bricksforge-turnstile');
    }

    public function set_control_groups()
    {
        $this->control_groups['general'] = [
            'title'    => esc_html__('General', 'bricksforge'),
            'tab'      => 'content',
        ];
    }

    public function set_controls()
    {
        if ($this->check_for_turnstile_keys()[0] === true) {
            $this->controls['enableTurnstile'] = [
                'tab'      => 'content',
                'group'    => 'general',
                'label'    => esc_html__('Enable Turnstile', 'bricks'),
                'type'     => 'checkbox'
            ];
        } else {
            $this->controls['turnstileInfo'] = [
                'tab'      => 'content',
                'content'  => '<a href="https://www.cloudflare.com/de-de/products/turnstile/" target="_blank">Cloudflare Turnstile</a> ' . esc_html__('API key required! Add key in dashboard under: ', 'bricks') . 'Bricksforge -> Elements -> Pro Forms',
                'type'     => 'info',
            ];
        }

        if (isset($this->controls['enableTurnstile'])) {
            $this->controls['turnstileSeparator'] = [
                'tab'      => 'content',
                'group'    => 'general',
                'label'    => esc_html__('Turnstile', 'bricks'),
                'type'     => 'separator',
                'required' => ['enableTurnstile', '=', true],
            ];

            // Appearance
            $this->controls['turnstileAppearance'] = [
                'tab'      => 'content',
                'group'    => 'general',
                'label'    => esc_html__('Appearance', 'bricks'),
                'type'     => 'select',
                'options'  => [
                    'always' => esc_html__('Always', 'bricks'),
                    'execute'  => esc_html__('Execute', 'bricks'),
                    'interaction-only' => esc_html__('Interaction only', 'bricks'),
                ],
                'default'  => 'always',
                'required' => ['enableTurnstile', '=', true],
            ];

            // Theme
            $this->controls['turnstileTheme'] = [
                'tab'      => 'content',
                'group'    => 'general',
                'label'    => esc_html__('Theme', 'bricks'),
                'type'     => 'select',
                'options'  => [
                    'light' => esc_html__('Light', 'bricks'),
                    'dark'  => esc_html__('Dark', 'bricks'),
                ],
                'default'  => 'light',
                'required' => ['enableTurnstile', '=', true],
            ];

            // Size
            $this->controls['turnstileSize'] = [
                'tab'      => 'content',
                'group'    => 'general',
                'label'    => esc_html__('Size', 'bricks'),
                'type'     => 'select',
                'options'  => [
                    'normal' => esc_html__('Normal', 'bricks'),
                    'compact'  => esc_html__('Compact', 'bricks'),
                ],
                'default'  => 'normal',
                'required' => ['enableTurnstile', '=', true],
            ];

            // Language
            $this->controls['turnstileLanguage'] = [
                'tab'      => 'content',
                'group'    => 'general',
                'label'    => esc_html__('Language', 'bricks'),
                'type'     => 'text',
                'required' => ['enableTurnstile', '=', true],
                'description' => esc_html__('Enter the language code (e.g. "en" or "de"). Auto if empty.', 'bricks'),
            ];

            // Error Message
            $this->controls['turnstileErrorMessage'] = [
                'tab'      => 'content',
                'group'    => 'general',
                'label'    => esc_html__('Custom Error Message', 'bricks'),
                'type'     => 'text',
                'required' => ['enableTurnstile', '=', true],
                'default' => esc_html__('Your submission is being verified. Please wait a moment before submitting again.', 'bricks'),
            ];
        }
    }

    public function check_for_turnstile_keys()
    {
        $turnstile_settings = array_values(array_filter(get_option('brf_activated_elements'), function ($tool) {
            return $tool->id == 5;
        }));

        if (count($turnstile_settings) === 0) {
            return [false];
        }

        $turnstile_settings = $turnstile_settings[0];

        if (!isset($turnstile_settings->settings->useTurnstile) || $turnstile_settings->settings->useTurnstile !== true) {
            return [false];
        }

        if (empty($turnstile_settings->settings->turnstileKey)) {
            return [false];
        }

        $utils = new \Bricksforge\Api\Utils();
        $decrypted_turnstile_key = $utils->decrypt($turnstile_settings->settings->turnstileKey);

        $this->turnstile_key = $decrypted_turnstile_key;

        return [true, $decrypted_turnstile_key];
    }

    public function render()
    {
        $settings = $this->settings;

        /**
         * Wrapper
         */
        $this->remove_attribute('_root', 'class');

        if (isset($settings['turnstileAppearance']) && $settings['turnstileAppearance'] != 'always') {
            $this->set_attribute("_root", 'data-turnstile-hidden', "true");
        }

        $output = '<div ' . $this->render_attributes('_root') . '>';

        if (isset($settings['enableTurnstile']) && $settings['enableTurnstile'] && isset($this->turnstile_key)) {
            $output .= '<div class="cf-turnstile" data-language="' . (isset($settings['turnstileLanguage']) ? $settings['turnstileLanguage'] : 'auto') . '" data-appearance="' . (isset($settings['turnstileAppearance']) ? $settings['turnstileAppearance'] : 'always') . '" data-size="' . (isset($settings['turnstileSize']) ? $settings['turnstileSize'] : 'normal') . '" data-theme="' . (isset($settings['turnstileTheme']) ? $settings['turnstileTheme'] : 'light') . '" data-sitekey="' . (isset($this->turnstile_key) ? $this->turnstile_key : '') . '"></div>';
        }

        $output .= '</div>';

        echo $output;
?>
<?php
    }
}
