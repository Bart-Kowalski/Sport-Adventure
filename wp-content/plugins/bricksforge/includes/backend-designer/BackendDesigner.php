<?php

namespace Bricksforge;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Global Classes Handler
 */
class BackendDesigner
{

    public function __construct()
    {
        $this->init();
    }

    public function init()
    {
        if ($this->activated() === true) {
            $this->run();
        }
    }


    public function activated()
    {
        return get_option('brf_activated_tools') && in_array(9, get_option('brf_activated_tools'));
    }

    public function passed_exception_tests($settings)
    {
        if (!isset($settings->hasExceptions) || !$settings->hasExceptions) {
            return true;
        }

        if (!isset($settings->exceptions) || !is_array($settings->exceptions)) {
            return true;
        }


        // Get Current Url
        $url = is_ssl() ? 'https://' : 'http://';
        $url .= $_SERVER['HTTP_HOST'];
        $url .= $_SERVER['REQUEST_URI'];

        foreach ($settings->exceptions as $exception) {

            if (!isset($exception->value) || empty($exception->value)) {
                continue;
            }

            switch ($exception->type) {
                case 'url_contains':
                    if (strpos($url, $exception->value) !== false) {
                        return false;
                    }
                    break;
                case 'url_is_exactly':
                    if ($url == $exception->value) {
                        return false;
                    }
                    break;
                default:
                    break;
            }
        }

        return true;
    }

    public function run()
    {
        $settings = get_option('brf_backend_designer');

        if (!$settings) {
            return;
        }

        // If not pass the exception tests, return
        if (!$this->passed_exception_tests($settings)) {
            return;
        }

        if (isset($settings->loginSettings) && $settings->loginSettings && $settings->status->loginPage == true) {
            $this->login_settings($settings->loginSettings);
        }

        if (isset($settings->backendSettings) && $settings->backendSettings) {
            $this->backend_settings($settings->backendSettings, $settings->status);
        }

        if (isset($settings->dashboardSettings) && $settings->dashboardSettings && $settings->status->dashboard == true) {
            $this->dashboard_settings($settings->dashboardSettings);
        }

        if (isset($settings->loginSettings->customLogin) && $settings->loginSettings->customLogin && $settings->status->loginPage == true) {
            $custom_login = $settings->loginSettings->customLogin;

            add_action('parse_request', function () use ($custom_login) {
                $this->handler_custom_login($custom_login);
            });
            add_action('login_init', [$this, 'check_custom_login']);
        }
    }

    public function handler_custom_login($custom_login)
    {

        if (!$custom_login || !is_string($custom_login)) {
            return;
        }

        if (strpos($custom_login, '/') === true) {
            return;
        }

        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        if (basename($path) === $custom_login) {
            $nonce = wp_create_nonce('brf-custom-login');
            setcookie('brf_custom_login', $nonce);
            wp_redirect(wp_login_url());
            exit;
        }
    }

    public function check_custom_login()
    {
        $nonce = isset($_COOKIE['brf_custom_login']) ? $_COOKIE['brf_custom_login'] : '';
        $action = isset($_GET['action']) ? $_GET['action'] : '';
        $previous_page_action = '';
        $loggedout = isset($_GET['loggedout']) ? $_GET['loggedout'] : '';

        $previous_page = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : false;

        if ($previous_page) {
            if ($previous_page) {
                // Check for ?action=resetpass in the previous page
                if (strpos($previous_page, '?action=resetpass') !== false) {
                    $previous_page_action = 'resetpass';
                }
            }
        }

        if (!wp_verify_nonce($nonce, 'brf-custom-login') && $action !== 'logout' && $action !== 'confirm_admin_email' && $action !== 'lostpassword' && $action !== 'rp' && $action !== 'resetpass' && $previous_page_action !== 'resetpass' && $loggedout !== 'true') {
            header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
            exit;
        }
    }

    /**
     * Summary of login_settings
     * @param mixed $settings
     * @return void
     */
    public function login_settings($settings)
    {
        add_action('login_enqueue_scripts', function () use ($settings) {
            wp_enqueue_style('brf-custom-login', BRICKSFORGE_ASSETS . '/css/backend-designer/login.css', array(), false, 'screen');
            $output = $this->generate_login_styles($settings);
            wp_add_inline_style('brf-custom-login', $output);
        });
    }

    private function generate_login_styles($settings)
    {
        $css_rules = '';

        $css_rules .= 'body.login input:focus { box-shadow: none; border-color: ' . $settings->formsBorderColor . '; } ';

        $css_rules .= 'body.login .dashicons-visibility:before { color: ' . $settings->linkColor . '; } ';

        if (isset($settings->backgroundColor) && $settings->backgroundColor) {
            $css_rules .= 'body.login { background-color: ' . $settings->backgroundColor . '; } ';
        }

        if (isset($settings->backgroundColor2) && $settings->backgroundColor2) {
            $css_rules .= 'body.login { background: linear-gradient(to bottom, ' . $settings->backgroundColor . ' 0%, ' . $settings->backgroundColor2 . ' 100%)!important; } ';
        }

        if (isset($settings->backgroundImage) && $settings->backgroundImage) {
            $css_rules .= 'body.login { background-image: url(' . $settings->backgroundImage . ')!important; background-size: cover!important; } ';
        }

        if (isset($settings->backgroundImageOverlay) && $settings->backgroundImageOverlay) {
            $css_rules .= 'body.login::before { background-color: ' . $settings->backgroundImageOverlay . '!important; content: ""; position: fixed; left: 0; top: 0; right: 0; bottom: 0; z-index: -1; } ';
        }

        if (isset($settings->boxColor) && $settings->boxColor) {
            $css_rules .= 'body.login form, .login #login_error, .login .message, .login .success { background-color: ' . $settings->boxColor . '; border-color: ' . $settings->boxColor . '; } ';
        }

        if (isset($settings->textColor) && $settings->textColor) {
            $css_rules .= 'body.login { color: ' . $settings->textColor . '; } ';
        }

        if (isset($settings->linkColor) && $settings->linkColor) {
            $css_rules .= 'body.login a { color: ' . $settings->linkColor . '!important; } ';
        }

        if (isset($settings->linkHoverColor) && $settings->linkHoverColor) {
            $css_rules .= 'body.login a:hover { color: ' . $settings->linkHoverColor . '!important; } ';
        }

        if (isset($settings->backgroundColor) && $settings->backgroundColor) {
            $css_rules .= 'body.login { background-color: ' . $settings->backgroundColor . '; } ';
        }

        if (isset($settings->backgroundColor2) && $settings->backgroundColor2) {
            $css_rules .= 'body.login { background: linear-gradient(to bottom, ' . $settings->backgroundColor . ' 0%, ' . $settings->backgroundColor2 . ' 100%)!important; } ';
        }

        if (isset($settings->backgroundImage) && $settings->backgroundImage) {
            $css_rules .= 'body.login { background-image: url(' . $settings->backgroundImage . ')!important; background-size: cover!important; } ';
        }

        if (isset($settings->backgroundImageOverlay) && $settings->backgroundImageOverlay) {
            $css_rules .= 'body.login::before { background-color: ' . $settings->backgroundImageOverlay . '!important; content: ""; position: fixed; left: 0; top: 0; right: 0; bottom: 0; z-index: -1; } ';
        }

        if (isset($settings->boxColor) && $settings->boxColor) {
            $css_rules .= 'body.login form, .login #login_error, .login .message, .login .success { background-color: ' . $settings->boxColor . '; border-color: ' . $settings->boxColor . '; } ';
        }

        if (isset($settings->textColor) && $settings->textColor) {
            $css_rules .= 'body.login { color: ' . $settings->textColor . '; } ';
        }

        if (isset($settings->linkColor) && $settings->linkColor) {
            $css_rules .= 'body.login a { color: ' . $settings->linkColor . '!important; } ';
        }

        if (isset($settings->linkHoverColor) && $settings->linkHoverColor) {
            $css_rules .= 'body.login a:hover { color: ' . $settings->linkHoverColor . '!important; } ';
        }

        if (isset($settings->buttonColor) && $settings->buttonColor) {
            $css_rules .= 'body.login input[type=submit] { background-color: ' . $settings->buttonColor . '; border-color: ' . $settings->buttonColor . '; } ';
        }

        if (isset($settings->buttonHoverColor) && $settings->buttonHoverColor) {
            $css_rules .= 'body.login input[type=submit]:hover, body.login input[type=submit]:active, body.login input[type=submit]:focus { background-color: ' . $settings->buttonHoverColor . '; border-color: ' . $settings->buttonHoverColor . '; outline: none; box-shadow: none; } ';
        }

        if (isset($settings->buttonTextColor) && $settings->buttonTextColor) {
            $css_rules .= 'body.login input[type=submit] { color: ' . $settings->buttonTextColor . '!important; } ';
        }

        if (isset($settings->formsBackgroundColor) && $settings->formsBackgroundColor) {
            $css_rules .= 'body.login input[type=text], body.login input[type=password], select, body.login form input[type=checkbox] { background-color: ' . $settings->formsBackgroundColor . '!important; } ';
        }

        if (isset($settings->formsBorderColor) && $settings->formsBorderColor) {
            $css_rules .= 'body.login input[type=text], body.login input[type=password], select, body.login form input[type=checkbox] { border-color: ' . $settings->formsBorderColor . '!important; } ';
        }

        if (isset($settings->formsTextColor) && $settings->formsTextColor) {
            $css_rules .= 'body.login input[type=text], body.login input[type=password], select, body.login form input[type=checkbox] { color: ' . $settings->formsTextColor . '!important; } ';
        }

        if (isset($settings->formsFontSize) && $settings->formsFontSize) {
            $css_rules .= 'body.login input[type=text], body.login input[type=password] { font-size: ' . $settings->formsFontSize . '!important; } ';
        }

        /**
         * Forms Padding
         */
        if (isset($settings->formsPadding) && $settings->formsPadding) {
            $css_rules .= 'body.login input[type=text], body.login input[type=password] { padding: ' . $settings->formsPadding . '!important; }';
        }

        /**
         * Change Logo
         */
        if (isset($settings->logo) && $settings->logo) {
            $css_rules .= 'body.login h1 a { background-image: url(' . $settings->logo . ')!important; background-size: contain!important; width: 100%!important; }';
        }

        /**
         * Logo Height
         */
        if (isset($settings->logoHeight) && $settings->logoHeight) {
            $css_rules .= 'body.login h1 a { height: ' . $settings->logoHeight . '!important; }';
        }

        /**
         * Logo Negative Margin Top
         */
        if (isset($settings->negativeMarginTop) && $settings->negativeMarginTop) {
            $css_rules .= 'body.login h1 a { margin-top: -' . $settings->negativeMarginTop . '!important; }';
        }

        /**
         * Remove Logo Link
         */
        if (isset($settings->removeLogoLink) && $settings->removeLogoLink) {
            $css_rules .= 'body.login h1 a { pointer-events: none!important; }';
        }

        /**
         * Box Width
         */
        if (isset($settings->boxWidth) && $settings->boxWidth) {
            $css_rules .= 'body.login #login { width: ' . $settings->boxWidth . ' }';
        }

        /**
         * Box Padding
         */
        if (isset($settings->boxPadding) && $settings->boxPadding) {
            $css_rules .= 'body.login #loginform { padding: ' . $settings->boxPadding . ' }';
        }

        /**
         * Box Border Radius
         */
        if (isset($settings->boxBorderRadius) && $settings->boxBorderRadius) {
            $css_rules .= 'body.login #loginform { border-radius: ' . $settings->boxBorderRadius . '!important }';
        }

        /**
         * Glassmorphism Effect
         */
        if (isset($settings->glassmorphism) && $settings->glassmorphism && isset($settings->boxColor) && $settings->boxColor) {
            $boxColorWithOpacity = $this->modifyColorOpacity($settings->boxColor, '20');
            $borderColorWithOpacity = $this->modifyColorOpacity($settings->boxColor, '30');
            $css_rules .= 'body.login #loginform, body.login form, .login #login_error, body.login .message { background: ' . $boxColorWithOpacity . '; border-radius: 16px; box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1); backdrop-filter: blur(5px); -webkit-backdrop-filter: blur(5px); border: 1px solid ' . $borderColorWithOpacity . ';}';
        }

        /**
         * Remove Logo
         */
        if (isset($settings->removeLogo) && $settings->removeLogo) {
            $css_rules .= 'body.login h1 a { display: none!important; }';
        }

        /**
         * Center Box
         */
        if (isset($settings->centerBox) && $settings->centerBox) {
            $css_rules .= 'body.login { display: flex; justify-content: center; align-items: center; flex-direction: column; } #loginform {margin: 0!important} #login {padding: 0}';
        }

        /**
         * Remove Language Switcher
         */
        if (isset($settings->removeLanguageSwitcher) && $settings->removeLanguageSwitcher) {
            $css_rules .= 'body.login .language-switcher { display: none!important; }';
        }

        /**
         * Remove Back To Website
         */
        if (isset($settings->removeBackToWebsite) && $settings->removeBackToWebsite) {
            $css_rules .= 'body.login #backtoblog { display: none!important; }';
        }

        /**
         * Remove Password Reset
         */
        if (isset($settings->removePasswordReset) && $settings->removePasswordReset) {
            $css_rules .= 'body.login #nav { display: none!important; }';
        }

        /**
         * Remove Remember Me
         */
        if (isset($settings->removeRememberMe) && $settings->removeRememberMe) {
            $css_rules .= 'body.login .forgetmenot { display: none!important; }';
        }

        /**
         * Output
         */

        // Regular expression to match CSS variables
        $css_rules = $this->wrap_css_vars($css_rules);

        return $css_rules;
    }

    private function modifyColorOpacity($color, $opacity)
    {
        $opacity = $opacity / 100;

        if (strpos($color, 'rgba') !== false) {
            // RGBA Color
            $pattern = "/rgba\((\d+,\s*\d+,\s*\d+),\s*(\d(\.\d+)?)\)/";
            $replacement = "rgba($1, $opacity)";
            return preg_replace($pattern, $replacement, $color);
        } elseif (strpos($color, 'rgb') !== false) {
            // RGB Color, convert to RGBA
            $pattern = "/rgb\((\d+,\s*\d+,\s*\d+)\)/";
            $replacement = "rgba($1, $opacity)";
            return preg_replace($pattern, $replacement, $color);
        } elseif (strpos($color, 'hsla') !== false) {
            // HSLA Color
            $pattern = "/hsla\((\d+,\s*\d+%,\s*\d+%),\s*(\d(\.\d+)?)\)/";
            $replacement = "hsla($1, $opacity)";
            return preg_replace($pattern, $replacement, $color);
        } elseif (strpos($color, 'hsl') !== false) {
            // HSL Color, convert to HSLA
            $pattern = "/hsl\((\d+,\s*\d+%,\s*\d+%)\)/";
            $replacement = "hsla($1, $opacity)";
            return preg_replace($pattern, $replacement, $color);
        } else {
            // Assume HEX color
            return substr_replace($color, $opacity, -2, 0);
        }
    }

    /**
     * Add settings for the backend
     *
     * @param array $settings
     * @return void
     */
    public function backend_settings($settings, $status)
    {

        // Add a <style> tag to the head with some styles in a wordpress hook
        add_action('admin_head', function () use ($settings, $status) {

            $styles = '';

            $settings = (object) $settings;

            /**
             * Navigation Styles
             */
            if (isset($status->navigation) && $status->navigation == true) {
                $styles .= "
                    #adminmenu a:focus, #adminmenu a:hover, .folded #adminmenu .wp-submenu-head:hover { box-shadow: none !important; }
                    #adminmenu, #adminmenu .wp-submenu, #adminmenuback, #adminmenuwrap { background-color: {$settings->navigationBackground} !important; }
                    #adminmenu > li .wp-menu-name, div.wp-menu-image:before { color: {$settings->navigationText} !important; }
                    #adminmenu .wp-submenu { background-color: {$settings->navigationBackground2} !important; }
                    #adminmenu .wp-submenu li a, #adminmenu .wp-submenu li a:focus, #adminmenu .wp-submenu li a:hover { color: {$settings->navigationText2} !important; }
                    #adminmenu li.menu-top:hover, #adminmenu .wp-submenu li:hover, a.wp-menu-open, #collapse-menu:hover { background-color: {$settings->navigationBackgroundHover} !important; color: {$settings->navigationTextHover} !important; }
                    #collapse-menu:hover button, #collapse-button:focus { color: {$settings->navigationTextHover} !important; }
                    #adminmenu a.wp-menu-open .wp-menu-name, #adminmenu a.wp-menu-open div.wp-menu-image:before { color: {$settings->navigationTextHover} !important; }
                    #adminmenu li.opensub>a.menu-top { background: transparent!important; }
                    #adminmenu li.menu-top:hover .wp-menu-name, #adminmenu li.opensub>a.menu-top, #adminmenu li>a.menu-top:focus, #adminmenu li.menu-top:hover div.wp-menu-image:before, #adminmenu > li .wp-has-current-submenu .wp-menu-name, #adminmenu .wp-submenu li:hover a { color: {$settings->navigationTextHover} !important; border-color: {$settings->navigationTextHover} !important; }
                    .wp-submenu { border: 0!important; }
                ";

                if (isset($settings->adminBarBackground)) {
                    $styles .= "#wpadminbar { background: {$settings->adminBarBackground}; }";
                    $styles .= "#wpadminbar .menupop .ab-sub-wrapper, #wpadminbar .shortlink-input { background: {$settings->adminBarBackground}; }";
                    $styles .= "#wpadminbar .ab-top-menu>li.hover>.ab-item, #wpadminbar.nojq .quicklinks .ab-top-menu>li>.ab-item:focus, #wpadminbar:not(.mobile) .ab-top-menu>li:hover>.ab-item, #wpadminbar:not(.mobile) .ab-top-menu>li>.ab-item:focus { background: {$settings->adminBarBackground}; }";
                }

                if (isset($settings->adminBarTextColor)) {
                    $styles .= "#wpadminbar .ab-empty-item, #wpadminbar a.ab-item, #wpadminbar>#wp-toolbar span.ab-label, #wpadminbar>#wp-toolbar span.noticon { color: {$settings->adminBarTextColor}; }";
                    $styles .= "#wpadminbar a.ab-item::before, #wpadminbar a.ab-item span::before, #wpadminbar .quicklinks .menupop ul li a, #wpadminbar .quicklinks .menupop ul li a strong, #wpadminbar .quicklinks .menupop.hover ul li a, #wpadminbar.nojs .quicklinks .menupop:hover ul li a { color: {$settings->adminBarTextColor}!important; }";
                }

                if (isset($settings->adminBarHoverBackground)) {
                    $styles .= "#wpadminbar ul > li:hover, #wpadminbar .shortlink-input { background: {$settings->adminBarHoverBackground}; }";
                    $styles .= "#wpadminbar .ab-top-menu>li.hover>.ab-item, #wpadminbar.nojq .quicklinks .ab-top-menu>li>.ab-item:focus, #wpadminbar:not(.mobile) .ab-top-menu>li:hover>.ab-item, #wpadminbar:not(.mobile) .ab-top-menu>li>.ab-item:focus { background: {$settings->adminBarHoverBackground}; }";
                }

                // adminBarHoverText
                if (isset($settings->adminBarHoverText)) {
                    // Add hover text color
                    $styles .= "#wpadminbar ul > li:hover .ab-sub-wrapper li:hover *, #wpadminbar .ab-top-menu li:hover div, #wpadminbar .ab-top-menu li:hover a, #wpadminbar .ab-top-menu li:hover span.ab-label, #wpadminbar a.ab-item:hover::before, #wpadminbar a.ab-item:hover span::before { color: {$settings->adminBarHoverText}!important; }";
                }
            }

            // Stop here if the current url contains: page=bricksforge
            if (strpos($_SERVER['REQUEST_URI'], 'page=bricksforge') !== false) {
                // Return Navigation Styles
                $this->inject_styles($styles);
                return;
            }

            // Stop if url contains: plugin-editor.php or theme-editor.php
            /* if (strpos($_SERVER['REQUEST_URI'], 'plugin-editor.php') !== false || strpos($_SERVER['REQUEST_URI'], 'theme-editor.php') !== false) {
                return;
            } */

            // Stop if url contains: widgets.php
            if (strpos($_SERVER['REQUEST_URI'], 'widgets.php') !== false) {
                return;
            }

            // Stop if is edit mode
            if (strpos($_SERVER['REQUEST_URI'], 'action=edit') !== false && strpos($_SERVER['REQUEST_URI'], 'post.php') !== false) {
                return;
            }

            /**
             * Page Styles
             */
            if (isset($status->pages) && $status->pages == true) {
                $styles .= "
                    .block-editor-inserter__block-list {
                        background: {$settings->pagesContentBackground} !important;
                    }
                    .block-editor-inserter__block-list * {
                        color: {$settings->pagesTextColor} !important;
                    }
                    .edit-post-header {
                        background: {$settings->pagesContentBackground} !important;
                        margin-bottom: -1px;
                    }
                    .community-events ul {
                        background: {$settings->pagesContentBackground};
                    }
                    .js #dashboard_quick_press .drafts {
                        border-color: {$settings->pagesContentBackgroundVariant};
                    }
                    #dashboard-widgets .postbox-container .empty-container {
                        outline-color: {$settings->pagesContentBackgroundVariant};
                    }
                    .edit-post-header * {
                        color: {$settings->pagesTextColor} !important;
                    }
                    #wpcontent a, button.button-link {
                        color: {$settings->pagesLinkColor};
                    }
                    #wpfooter a {
                        color: {$settings->pagesLinkColor};
                    }
                    #wpcontent table.plugins.wp-list-table .row-actions a {
                        color: {$settings->pagesLinkColor}!important;
                    }
                    #wpcontent table.plugins.wp-list-table .row-actions span.delete a {
                        color: #b32d2e!important;
                    }
                    .components-notice {
                        background: {$settings->pagesContentBackground} !important;
                        color: {$settings->pagesTextColor} !important;
                    }
                    .edit-post-fullscreen-mode-close.components-button {
                        height: 59px;
                    }
                    .components-popover__content {
                        background: {$settings->pagesContentBackground} !important;
                    }
                    .components-popover__content * {
                        color: {$settings->pagesTextColor} !important;
                    }
                    body {
                        background-color: {$settings->pagesBodyBackground} !important;
                        color: {$settings->pagesTextColor} !important;
                    }
                    .wp-core-ui .attachment .filename, tr, th, li {
                        color: {$settings->pagesTextColor} !important;
                    }
                    h1,h2,h3,h4,h5,h6, p, td, th, span, label, legend, .media-menu-item {
                        color: {$settings->pagesTextColor} !important;
                    }
                    #wpcontent a:not(.page-title-action), #wpcontent button.button-link {
                        color: {$settings->pagesLinkColor};
                    }
                    table {
                        border-color: {$settings->pagesContentBackground} !important;
                    }
                    .interface-interface-skeleton__sidebar {
                        background: transparent!important;
                    }
                    input[type=checkbox]:checked::before {
                        filter: grayscale(1)
                    }
                    #screen-meta, .revisions-diff, .revisions-meta, .attachments-browser .media-toolbar, .wp-core-ui
                    .attachment-preview, .wp-core-ui .attachment .filename, .welcome-panel .welcome-panel-column-container, .postbox,
                    #dashboard-widgets .postbox-container .empty-container, th, .nav-tab, .health-check-header, #contextual-help-back,
                    .contextual-help-tabs .active, .theme-about, .theme-actions, .nav-menus-php #post-body,
                    #nav-menu-footer, .bulk-select-button, #nav-menu-header, .accordion-section-title, .accordion-section-content,
                    .tabs-panel, ul.add-menu-item-tabs li.tabs, .notification-dialog, .CodeMirror, .bricks-admin-wrapper.getting-started
                    .bricks-admin-inner, .bricks-admin-wrapper.getting-started .box-wrapper:after, .bricks-admin-wrapper.getting-started
                    .badge, .manage-menus, .edit-post-header, .components-panel__body, .block-editor-block-inspector__no-blocks,
                    .components-panel__header, .media-modal-content, .attachments-wrapper, .media-sidebar, .media-frame-content,
                    .components-popover__content, .plugin-card, #plugin-information-content, #plugin-information .fyi,
                    #TB_window.plugin-details-modal, .health-check-accordion-trigger:focus, .privacy-settings-accordion-trigger:focus {
                        background-color: {$settings->pagesContentBackground} !important;
                        border-color: {$settings->pagesContentBackground} !important;
                    }
                    .components-button.editor-post-last-revision__title:active, .components-button.editor-post-last-revision__title:hover,
                    .plugin-card-bottom, .popular-tags, #plugin-information-footer, #plugin-information-tabs, .color-option.selected,
                    .color-option:hover, .card, .importer-item, .health-check-accordion-trigger, .privacy-settings-accordion-trigger,
                    .health-check-accordion-heading, .health-check-accordion, .health-check-accordion-panel,
                    .privacy-settings-accordion-panel, .health-check-header, .privacy-settings-header, #wpadminbar {
                        background-color: {$settings->pagesContentBackgroundVariant} !important;
                        border-color: {$settings->pagesContentBackgroundVariant} !important;
                    }
                    .health-check-accordion-trigger:focus, .privacy-settings-accordion-trigger:focus {
                        outline: none;
                    }
                    .filter-links li>a {
                        border-color: {$settings->pagesContentBackgroundVariant} !important;
                    }
                    .filter-links li>a.current {
                        border-color: {$settings->pagesLinkColor} !important;
                    }
                    #plugin-information .fyi strong {
                        color: {$settings->pagesTextColor} !important;
                    }
                    .menu-item-handle, .menu-item-settings {
                        background-color: {$settings->pagesContentBackgroundVariant} !important;
                        border-color: {$settings->pagesContentBackgroundVariant} !important;
                    }
                    #plugin-information-tabs a.current {
                        background-color: {$settings->pagesContentBackgroundVariant} !important;
                        border-color: {$settings->pagesContentBackgroundVariant} !important;
                        color: {$settings->pagesTextColor} !important;
                    }
                    td, th, tr, ul, section, .postbox-header, .empty-container, .activity-block, p, li, .accordion-container, .menu-edit,
                    .menu-settings, #nav-menu-footer, .menu-item-settings, .bricks-admin-wrapper.getting-started .box-wrapper {
                        border-color: {$settings->pagesDividersColor} !important;
                        box-shadow: none!important;
                    }
                    #bricks-settings .separator {
                        background-color: {$settings->pagesDividersColor} !important;
                    }
                    #activity-widget #the-comment-list .comment-item {
                        background-color: {$settings->pagesContentBackground} !important;
                    }
                    .nav-tab-active {
                        background-color: {$settings->pagesContentBackgroundVariant} !important;
                    }
                    p.info {
                        background-color: {$settings->pagesContentBackgroundVariant} !important;
                    }
                    .bricks-admin-wrapper.sidebars .bricks-admin-inner {
                        background-color: {$settings->pagesContentBackground} !important;
                        border-color: {$settings->pagesContentBackground} !important;
                    }
                    .bricks-admin-wrapper.sidebars .new-sidebar-wrapper form {
                        background-color: {$settings->pagesContentBackgroundVariant} !important;
                        border-color: {$settings->pagesContentBackgroundVariant} !important;
                    }
                    .notice, div.error, div.updated {
                        background-color: {$settings->pagesContentBackgroundVariant} !important;
                        border-color: {$settings->pagesContentBackgroundVariant} !important;
                    }
                    .bricks-admin-wrapper.license form, .wp-filter {
                        background-color: {$settings->pagesContentBackground} !important;
                        border-color: {$settings->pagesContentBackground} !important;
                    }
                    .bricks-admin-inner {
                        border-color: {$settings->pagesContentBackground} !important;
                    }
                    .wp-core-ui .button.disabled, .edit-attachment-frame .attachment-info {
                        background-color: {$settings->pagesContentBackgroundVariant} !important;
                        border-color: {$settings->pagesContentBackgroundVariant} !important;
                        color: {$settings->pagesTextColor} !important;
                    }
                    .edit-attachment-frame .attachment-info .details, .edit-attachment-frame .attachment-info .settings,
                    .edit-attachment-frame .attachment-info .filename {
                        color: {$settings->pagesTextColor} !important;
                    }
                    .view-switch a.current:before {
                        color: {$settings->pagesLinkColor} !important;
                    }
                    .edit-media-header button {
                        border: 0!important;
                    }
                    .edit-media-header button:hover {
                        background: {$settings->pagesContentBackgroundVariant} !important;
                        border-color: {$settings->pagesContentBackgroundVariant} !important;
                        color: {$settings->pagesTextColor} !important;
                    }
                    .media-upload-form .media-item {
                        background: {$settings->pagesContentBackground} !important;
                    }
                    .nav-tab-wrapper, .wrap h2.nav-tab-wrapper, h1.nav-tab-wrapper {
                        border-color: {$settings->pagesContentBackgroundVariant} !important;
                    }
                    .wp-person a:focus .gravatar, a:focus, a:focus .media-icon img, a:focus .plugin-icon {
                        box-shadow: none!important;
                        outline: none!important;
                    }
                ";
            }

            /**
             * Button Styles
             */
            if (isset($status->buttons) && $status->buttons == true) {
                $styles .= "
                    .wp-core-ui .button, button.is-tertiary, button.components-button, .media-router .active, .media-router .media-menu-item.active:last-child {
                        background-color: {$settings->defaultButtonBackground} !important;
                        color: {$settings->defaultButtonText} !important;
                        border-color: {$settings->defaultButtonBackground} !important;
                    }

                    .wp-core-ui .button.button-primary, .wrap .page-title-action, button.is-primary {
                        background-color: {$settings->primaryButtonBackground} !important;
                        color: {$settings->primaryButtonText} !important;
                        border-color: {$settings->primaryButtonBackground} !important;
                    }
                    .bricks-admin-wrapper.sidebars .registered-sidebars-wrapper button[type=submit] {
                        color: {$settings->primaryButtonBackground} !important;
                    }
                ";

                $styles .= "
                    .wp-core-ui .button, button.is-tertiary, .media-router .active, .media-router .media-menu-item.active:last-child {
                        background-color: {$settings->defaultButtonBackground} !important;
                        color: {$settings->defaultButtonText} !important;
                        border-color: {$settings->defaultButtonBackground} !important;
                    }
                    .button.button-primary, .wrap .page-title-action, button.is-primary {
                        background-color: {$settings->primaryButtonBackground} !important;
                        color: {$settings->primaryButtonText} !important;
                        border-color: {$settings->primaryButtonBackground} !important;
                    }
                    .components-button:focus:not(:disabled), .components-button:hover:not(:disabled) {
                        box-shadow: 0 0 0 1px {$settings->primaryButtonBackground} !important;
                    }
                ";
            }

            /**
             * Forms Styles
             */
            if (isset($status->forms) && $status->forms == true) {
                $styles .= "
                    input, textarea, select {
                        background-color: {$settings->formsBackground} !important;
                        color: {$settings->formsText} !important;
                        box-shadow: 0 0 0 1px {$settings->formsBorder} !important;
                        border-color: {$settings->formsBorder} !important;
                    }

                    table.form-table th {
                        padding-left: 25px;
                    }

                    input[type=radio]:checked::before {
                        background-color: {$settings->formsAccent} !important;
                    }

                    .plugin-details-modal #TB_closeWindowButton {
                        color: {$settings->formsAccent} !important;
                    }
                    .wp-core-ui .attachment.details .check, .wp-core-ui .attachment.selected .check:focus, .wp-core-ui .media-frame.mode-grid .attachment.selected .check {
                        background-color: {$settings->formsAccent} !important;
                        border-color: {$settings->formsAccent} !important;
                        outline: none!important;
                        box-shadow: none!important;
                    }
                    #bricks-settings input[type=checkbox]:checked {
                        background-color: {$settings->formsAccent} !important;
                    }
                ";
            }


            /**
             * Tables Styles
             */
            if (isset($status->tables) && $status->tables == true) {
                $styles .= "
                    .wp-list-table tr, .wp-list-table th, .wp-list-table td {
                        background-color: {$settings->tablesEvenColor} !important;
                    }

                    table.striped tr {
                        background-color: {$settings->tablesEvenColor} !important;
                    }

                    .striped>tbody>:nth-child(odd), .striped>tbody>:nth-child(odd) th, .striped>tbody>:nth-child(odd) td, ul.striped>:nth-child(odd), ul.striped>:nth-child(odd) th, ul.striped>:nth-child(odd) td {
                        background-color: {$settings->tablesOddColor} !important;
                    }

                    .wp-list-table tr, .wp-list-table th, .wp-list-table td {
                        background-color: {$settings->tablesEvenColor} !important;
                    }

                    .wp-list-table tr.active, .wp-list-table tr.active th, .wp-list-table tr.active td {
                        background-color: {$settings->tablesOddColor} !important;
                    }

                    #bricks-settings td {
                        background-color: {$settings->tablesEvenColor} !important;
                    }
                    #wpcontent table.plugins tr, #wpcontent table.plugins tr > th {
                        border-left-color: {$settings->pagesLinkColor}!important;
                    }
                ";
            }

            /** Inject Styles */
            $this->inject_styles($styles);
        }, 10);


        $settings = (object)$settings;

        /**
         * Change Footer Text
         */
        if (isset($settings->footerText) && $settings->footerText != '' && $status->footer == true) {
            add_filter('admin_footer_text', function () use ($settings) {
                return $settings->footerText;
            });
        }

        /** 
         * Change WordPress Version Text
         */
        if (isset($settings->versionText) && $settings->versionText != '' && $status->footer == true) {
            add_filter('update_footer', function () use ($settings) {
                return $settings->versionText;
            }, 11);
        }

        /**
         * Change WP Admin Bar Logo
         */
        if (isset($settings->adminBarLogo) && $settings->adminBarLogo != '' && $status->adminBar == true) {
            add_action('admin_bar_menu', function ($wp_admin_bar) use ($settings) {
                $wp_admin_bar->remove_node('wp-logo');

                $logo_height = isset($settings->adminBarLogoHeight) && $settings->adminBarLogoHeight != '' ? $settings->adminBarLogoHeight : '30px';

                $wp_admin_bar->add_node([
                    'id' => 'brf-custom-logo',
                    'title' => '<img src="' . $settings->adminBarLogo . '" alt="Logo" style="max-height: 100%; height: ' . $logo_height . '" />',
                    'href' => home_url(),
                ]);
            }, 11);
        }

        /**
         * Replace Howdy in Admin Bar
         */
        if (isset($settings->adminBarNewHowdy) && $settings->adminBarNewHowdy != '' && $status->adminBar == true) {
            add_filter('admin_bar_menu', function ($wp_admin_bar) use ($settings) {
                $my_account = $wp_admin_bar->get_node('my-account');
                $newtitle = str_replace('Howdy,', $settings->adminBarNewHowdy, $my_account->title);
                $wp_admin_bar->add_node([
                    'id' => 'my-account',
                    'title' => $newtitle,
                ]);
            }, 9999);
        }

        /**
         * Remove all Admin Bar links except logo and user
         */
        if (isset($settings->clearAdminBar) && $settings->clearAdminBar == '1' && $status->adminBar == true) {
            add_action('admin_bar_menu', function ($wp_admin_bar) {
                $wp_admin_bar->remove_node('site-name');
                $wp_admin_bar->remove_node('comments');
                $wp_admin_bar->remove_node('new-content');
                $wp_admin_bar->remove_node('customize');
                $wp_admin_bar->remove_node('updates');
                $wp_admin_bar->remove_node('search');
                $wp_admin_bar->remove_node('wp-logo-external');
                $wp_admin_bar->remove_node('edit');
                $wp_admin_bar->remove_node('view');
                $wp_admin_bar->remove_node('preview');
                $wp_admin_bar->remove_node('trash');
                $wp_admin_bar->remove_node('archive');
                $wp_admin_bar->remove_node('untrash');
                $wp_admin_bar->remove_node('delete');
                $wp_admin_bar->remove_node('spam');
                $wp_admin_bar->remove_node('unspam');
                $wp_admin_bar->remove_node('duplicate');
                $wp_admin_bar->remove_node('edit-as-new-draft');
                $wp_admin_bar->remove_node('revisions');
                $wp_admin_bar->remove_node('view-revisions');
                $wp_admin_bar->remove_node('next-post');
                $wp_admin_bar->remove_node('previous-post');
                $wp_admin_bar->remove_node('next-page');
                $wp_admin_bar->remove_node('previous-page');
                $wp_admin_bar->remove_node('next-media');
                $wp_admin_bar->remove_node('previous-media');
                $wp_admin_bar->remove_node('next-image');
                $wp_admin_bar->remove_node('previous-image');
                $wp_admin_bar->remove_node('next-user');
                $wp_admin_bar->remove_node('previous-user');
                $wp_admin_bar->remove_node('next-comment');
                $wp_admin_bar->remove_node('previous-comment');
            }, 999);
        }
    }

    public function inject_styles($styles)
    {
        if ($styles != '') {
            $styles = preg_replace('/\s+/', ' ', $styles);
            $styles = trim($styles);

            $styles = $this->wrap_css_vars($styles);

            echo "
                <style type='text/css' id='brf-admin-styles'>{$styles}</style>
            ";
        }
    }

    public function dashboard_settings($settings)
    {
        if (isset($settings->useTemplate) && $settings->useTemplate) {
            add_action('admin_head', function () use ($settings) {
                if (get_current_screen()->id != 'dashboard') {
                    return;
                }

                wp_enqueue_script('bricksforge-custom-dashboard-script', BRICKSFORGE_ASSETS . '/js/backend-designer/dashboard.js', [], false, true);
                wp_localize_script('bricksforge-custom-dashboard-script', 'dashboardSettings', ['linkHandling' => $settings->linkHandling ?? '']);

                wp_enqueue_style('bricksforge-custom-dashboard-style', BRICKSFORGE_ASSETS . '/css/backend-designer/dashboard.css');
            });

            if (is_user_logged_in() && isset($_GET['backend']) && $_GET['backend'] == 'true') {
                add_filter('body_class', function ($classes) {
                    $classes[] = 'brf-backend-view';
                    return $classes;
                });

                add_filter('show_admin_bar', function ($show) {
                    return false;
                });
            }

            add_action('wp_dashboard_setup', function () use ($settings) {
                $this->remove_default_dashboard_widgets();

                $url = add_query_arg('backend', 'true', get_permalink($settings->template));

                wp_add_dashboard_widget(
                    'brf_custom_dashboard',
                    'Welcome',
                    function () use ($url) {
                        echo '<iframe src="' . $url . '" width="100%" height="100%"></iframe>';
                    }
                );
            }, 999);
        }
    }

    public function wrap_css_vars($css_rules)
    {
        $cssVarRegex = '/(--[a-zA-Z0-9-_]+)/';

        return preg_replace_callback($cssVarRegex, function ($matches) {
            return 'var(' . $matches[0] . ')';
        }, $css_rules);
    }

    private function remove_default_dashboard_widgets()
    {
        global $wp_meta_boxes;

        $widget_ids = [
            'dashboard_activity',
            'dashboard_right_now',
            'dashboard_recent_comments',
            'dashboard_incoming_links',
            'dashboard_plugins',
            'dashboard_primary',
            'dashboard_secondary',
            'dashboard_quick_press',
            'dashboard_recent_drafts',
            'dashboard_site_health',
            'welcome-panel-column-container',
        ];

        foreach ($widget_ids as $widget_id) {
            foreach (['normal', 'side'] as $context) {
                foreach (['core'] as $priority) {
                    unset($wp_meta_boxes['dashboard'][$context][$priority][$widget_id]);
                }
            }
        }

        remove_action('welcome_panel', 'wp_welcome_panel');
    }
}
