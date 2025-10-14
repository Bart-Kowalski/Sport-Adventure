<?php
namespace Advanced_Themer_Bricks;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class AT__Admin{

    public static function check_theme_version() {
        $theme = wp_get_theme();
        if(!$theme) return;
    
        $parent = $theme->parent();
        $current_theme = ($parent) ? $parent : $theme;
    
        $current_version = $current_theme->get('Version');
        $target_version = '1.9.8';
    
        if (version_compare($current_version, $target_version, '<')) {
            ?>
            <div class="notice notice-error is-dismissible">
                <p>Your current theme version is lower than 1.9.8 and may not be compatible with your current version of Advanced Themer. Please update your theme to the latest version to avoid conflicts.</p>
            </div>
            <?php
        }
    }

    public static function add_plugin_links($links) {
        if( AT__Helpers::return_user_role_check() === true ) {
            // Setting Page
            $url_settings = esc_url( add_query_arg(
                'page',
                'bricks-advanced-themer',
                get_admin_url() . 'admin.php'
            ) );
            $settings_link = "<a href='$url_settings'>" . __( 'Theme Settings' ) . '</a>';
            array_push(
                $links,
                $settings_link
            );

            // License
            $url_license = esc_url( add_query_arg(
                'page',
                'at-license',
                get_admin_url() . 'admin.php'
            ) );
            $license_link = "<a href='$url_license'>" . __( 'License' ) . '</a>';
            array_push(
                $links,
                $license_link
            );
            
            // Support
            $support_link = "<a href='mailto:hello@advancedthemer.com'>" . __( 'Support' ) . '</a>';
            array_push(
                $links,
                $support_link
            );
        }

        return $links;

    }

    public static function add_admin_bar_menu( $admin_bar ) {

        global $brxc_acf_fields;

        if (!AT__Helpers::return_user_role_check() === true  ||  !AT__Helpers::in_array('admin-bar', $brxc_acf_fields, 'theme_settings_tabs') ) {

            return;
        };

        $args = array (
                'id'        => 'brxc-advanced-themer-admin-bar',
                'title'     => 'Advanced Themer',
        );
    
        $admin_bar->add_node( $args );

        $args = array (
            'id'        => 'brxc-theme-settings-admin-bar',
            'title'     => 'Theme Settings',
            'href'      => \get_admin_url() . 'admin.php?page=bricks-advanced-themer',
            'parent'    => 'brxc-advanced-themer-admin-bar'
        );

        $admin_bar->add_node( $args );

        $args = array (
            'id'        => 'brxc-license-admin-bar',
            'title'     => 'Manage your License',
            'href'      => \get_admin_url() . 'admin.php?page=at-license',
            'parent'    => 'brxc-advanced-themer-admin-bar'
        );

        $admin_bar->add_node( $args );
    }

    public static function remove_theme_settings_from_bricks_menu( $menu_order ) {
        if (AT__Helpers::return_user_role_check() === false){
            remove_submenu_page('bricks', 'bricks-advanced-themer');

        }

    }

    // Register Scripts
    public static function register_scripts(){
        wp_register_script( 'sass-at', \BRICKS_ADVANCED_THEMER_URL . 'assets/js/sass.js', ['sass-worker-at'], filemtime( \BRICKS_ADVANCED_THEMER_PATH . '/assets/js/sass.js' ) );
        wp_register_script( 'sass-worker-at', \BRICKS_ADVANCED_THEMER_URL . 'assets/js/sass.worker.js', [], '1.0.1' );
        
        // Styles
        wp_register_style( 'bricks-advanced-themer', \BRICKS_ADVANCED_THEMER_URL . 'assets/css/bricks-advanced-themer.css', [], filemtime( \BRICKS_ADVANCED_THEMER_PATH . '/assets/css/bricks-advanced-themer.css' ) );
        wp_register_style( 'bricks-advanced-themer-builder', \BRICKS_ADVANCED_THEMER_URL . 'assets/css/bricks-advanced-themer-builder.css', [], filemtime( \BRICKS_ADVANCED_THEMER_PATH . '/assets/css/bricks-advanced-themer-builder.css' ) );
        wp_register_style( 'bricks-advanced-themer-backend', \BRICKS_ADVANCED_THEMER_URL . 'assets/css/bricks-advanced-themer-backend.css', [], \BRICKS_ADVANCED_THEMER_VERSION );
        wp_enqueue_style( 'bricks-advanced-themer' );
        wp_register_style( 'alwan', \BRICKS_ADVANCED_THEMER_URL . 'assets/css/alwan.min.css', [], filemtime( \BRICKS_ADVANCED_THEMER_PATH . '/assets/css/alwan.min.css' ) );
        wp_register_style( 'brxc-darkmode-toggle', \BRICKS_ADVANCED_THEMER_URL . 'assets/css/darkmode-toggle.css', [], filemtime( \BRICKS_ADVANCED_THEMER_PATH . '/assets/css/darkmode-toggle.css' ) );
        wp_register_style( 'brxc-darkmode-btn', \BRICKS_ADVANCED_THEMER_URL . 'assets/css/darkmode-btn.css', [], filemtime( \BRICKS_ADVANCED_THEMER_PATH . '/assets/css/darkmode-btn.css' ) );
        wp_register_style( 'brxc-darkmode-btn-nestable', \BRICKS_ADVANCED_THEMER_URL . 'assets/css/darkmode-btn-nestable.css', [], filemtime( \BRICKS_ADVANCED_THEMER_PATH . '/assets/css/darkmode-btn-nestable.css' ) );
        wp_register_style( 'brxc-darkmode-toggle-nestable', \BRICKS_ADVANCED_THEMER_URL . 'assets/css/darkmode-toggle-nestable.css', [], filemtime( \BRICKS_ADVANCED_THEMER_PATH . '/assets/css/darkmode-toggle-nestable.css' ) );
        wp_register_style( 'monokai', \BRICKS_ADVANCED_THEMER_URL . 'assets/css/monokai.min.css', [], filemtime( \BRICKS_ADVANCED_THEMER_PATH . '/assets/css/monokai.min.css' ) );
        wp_register_style( 'brxc-builder-new-codemirror', \BRICKS_ADVANCED_THEMER_URL . 'assets/js/codemirror/lib/codemirror.css', [], filemtime( \BRICKS_ADVANCED_THEMER_PATH . 'assets/js/codemirror/lib/codemirror.css' ) );
        wp_register_style( 'bricks-strict-editor-view', \BRICKS_ADVANCED_THEMER_URL . 'assets/css/bricks-strict-editor-view.css', [], filemtime( \BRICKS_ADVANCED_THEMER_PATH . '/assets/css/bricks-strict-editor-view.css' ) );
        wp_register_style( 'brxc-page-transition', \BRICKS_ADVANCED_THEMER_URL . 'assets/css/page-transition.css', [], filemtime( \BRICKS_ADVANCED_THEMER_PATH . '/assets/css/page-transition.css' ) );
        
        // Scripts
        wp_register_script( 'alwan', \BRICKS_ADVANCED_THEMER_URL . 'assets/js/alwan.min.js', [], filemtime( \BRICKS_ADVANCED_THEMER_PATH . '/assets/js/alwan.min.js' ) );
        wp_register_script( 'brxc-builder', \BRICKS_ADVANCED_THEMER_URL . 'assets/js/builder.js', ['sortable'], filemtime( \BRICKS_ADVANCED_THEMER_PATH . '/assets/js/builder.js' ) );
        wp_register_script( 'beautifer-css', \BRICKS_ADVANCED_THEMER_URL . 'assets/js/beautifer-css.js', [], filemtime( \BRICKS_ADVANCED_THEMER_PATH . '/assets/js/beautifer-css.js' ) );
        wp_register_script( 'brxc-builder-new-codemirror', \BRICKS_ADVANCED_THEMER_URL . 'assets/js/codemirror/lib/codemirror.js', [], filemtime( \BRICKS_ADVANCED_THEMER_PATH . 'assets/js/codemirror/lib/codemirror.js' ) );
        wp_register_script( 'brxc-darkmode-local-storage', \BRICKS_ADVANCED_THEMER_URL . 'assets/js/darkmode-local-storage.js', [], filemtime( \BRICKS_ADVANCED_THEMER_PATH . '/assets/js/darkmode-local-storage.js'), false  );
        wp_register_script( 'brxc-darkmode', \BRICKS_ADVANCED_THEMER_URL . 'assets/js/darkmode.js', [], filemtime( \BRICKS_ADVANCED_THEMER_PATH . '/assets/js/darkmode.js'), false  );
        wp_register_script( 'sortable', \BRICKS_ADVANCED_THEMER_URL . 'assets/js/Sortable.min.js', [], filemtime( \BRICKS_ADVANCED_THEMER_PATH . '/assets/js/Sortable.min.js' ) );
        wp_register_script( 'contrast', \BRICKS_ADVANCED_THEMER_URL . 'assets/js/contrast.js', [], filemtime( \BRICKS_ADVANCED_THEMER_PATH . '/assets/js/contrast.js' ) );
        wp_register_script( 'chroma', \BRICKS_ADVANCED_THEMER_URL . 'assets/js/chroma.min.js', [], filemtime( \BRICKS_ADVANCED_THEMER_PATH . '/assets/js/chroma.min.js' ) );
        wp_register_script( 'highlight', \BRICKS_ADVANCED_THEMER_URL . 'assets/js/highlight.min.js', [], filemtime( \BRICKS_ADVANCED_THEMER_PATH . '/assets/js/highlight.min.js' ) );
        wp_register_script( 'bricks-strict-editor-view', \BRICKS_ADVANCED_THEMER_URL . 'assets/js/bricks-strict-editor-view.js', [], filemtime( \BRICKS_ADVANCED_THEMER_PATH . '/assets/js/bricks-strict-editor-view.js' ) );
        wp_register_script( 'brxc-scroll-timeline', \BRICKS_ADVANCED_THEMER_URL . 'assets/js/scroll-timeline.js', [], filemtime( \BRICKS_ADVANCED_THEMER_PATH . '/assets/js/scroll-timeline.js' ) );
        
    }

    public static function enqueue_theme_styles(){
        $variables = AT__Frontend::generate_theme_variables();
        if($variables === '') return;

        $custom_css = '/* Theme Variables from Advanced Themer */
:root, .brxc-scoped-variables{';
        $custom_css .= $variables;
        $custom_css .= '}';
        echo '<style>' . PHP_EOL;
        echo wp_strip_all_tags(trim($custom_css) ) . PHP_EOL;
        echo '</style>';
    }
    public static function enqueue_theme_variables(){
        if(!AT__Helpers::is_theme_variables_tab_activated() 
        || !function_exists('bricks_is_builder') 
        || (bricks_is_builder() && \Bricks\Capabilities::current_user_has_full_access() === true)) {
            return;
        }
        
        global $brxc_acf_fields;
        $position = $brxc_acf_fields['theme_var_position'] ?? 'head';
        $priority = $brxc_acf_fields['theme_var_priority'] ?? 10;

        add_action('wp_' . $position, 'Advanced_Themer_Bricks\AT__Admin::enqueue_theme_styles', $priority);
    }

    public static function admin_enqueue_scripts($hook_suffix){

        wp_enqueue_style( 'bricks-advanced-themer-backend' );
        
    }

    public static function load_variables_on_backend() {

        $gutenberg_colors_frontend_css = AT__Frontend::generate_css_for_frontend();
        
        if(!$gutenberg_colors_frontend_css){
            return;
        }

        wp_add_inline_style( 'bricks-advanced-themer-backend', $gutenberg_colors_frontend_css );
    }

    public static function enqueue_builder_scripts_strict_editor() {
        if (!class_exists('Bricks\Capabilities') || !function_exists('bricks_is_builder') || !bricks_is_builder() || \Bricks\Capabilities::current_user_has_full_access()) {
            return;
        }

        global $brxc_acf_fields;

        wp_enqueue_style( 'bricks-advanced-themer' );
        

        // Return if the Strict Editor View isn't enabled
        if( !AT__Helpers::is_strict_editor_view_category_activated()) {
            return;
        }

        wp_enqueue_style( 'bricks-strict-editor-view' );

        $custom_css = '';

        // Custom CSS Tweak
        if(AT__Helpers::in_array('custom-css',  $brxc_acf_fields, 'strict_editor_view_tweaks') && AT__Helpers::is_value($brxc_acf_fields, 'strict_editor_view_custom_css') ) {
            $custom_css .= $brxc_acf_fields['strict_editor_view_custom_css'];
        }
        

        if(AT__Helpers::is_value($brxc_acf_fields, 'change_accent_color') ){
            $custom_css .= 'html body{--builder-color-accent:';
            $custom_css .= $brxc_acf_fields['change_accent_color'];
            $custom_css .= '}#bricks-toolbar .logo{background-color:';
            $custom_css .= $brxc_acf_fields['change_accent_color'];
            $custom_css .= '}';
        }
        if(AT__Helpers::is_array($brxc_acf_fields, 'disable_toolbar_icons')){
            $toolbar_items = [
                ['logo','#bricks-toolbar li.logo'],
                ['pages','#bricks-toolbar li.pages'],
                ['command-palette','#bricks-toolbar li.command-palette'],
                ['breakpoints','#bricks-toolbar li.breakpoint '],
                ['dimensions','#bricks-toolbar li.preview-dimension'],
                ['undo-redo','#bricks-toolbar li.undo, #bricks-toolbar li.redo'],
                ['edit','#bricks-toolbar li.wordpress'],
                ['new-tab','#bricks-toolbar li.new-tab'],
                ['preview','#bricks-toolbar li.preview']
            ];
            $temp_css = [];
            foreach ($toolbar_items as $item){
                if(AT__Helpers::in_array($item[0], $brxc_acf_fields, 'disable_toolbar_icons')){
                    $temp_css[] = $item[1];
                }
            }

            $custom_css .= implode(",", $temp_css) . '{display: none !important;}';
            
            wp_enqueue_script( 'bricks-strict-editor-view' );
            wp_add_inline_style('bricks-strict-editor-view', wp_strip_all_tags($custom_css), 'after');


            // Logo
            $image_url = isset($brxc_acf_fields['change_logo_img']) && !empty($brxc_acf_fields['change_logo_img']) ? wp_get_attachment_url($brxc_acf_fields['change_logo_img']) : '';
            $options = [
                'change_logo' => $image_url,
                'builderTweaks' =>  $brxc_acf_fields['strict_editor_view_tweaks']
            ];

            wp_localize_script( 'bricks-builder', 'brxcStrictOptions', $options );
        }
    }

    public static function enqueue_builder_scripts() {

        if (!class_exists('Bricks\Capabilities') || !function_exists('bricks_is_builder') || !bricks_is_builder()) {
            return;
        }

        wp_enqueue_style( 'bricks-advanced-themer' );
        
        if( \Bricks\Capabilities::current_user_has_full_access() !== true) {
            return;
        }

        global $brxc_acf_fields;
        wp_enqueue_script( 'contrast' );
        wp_enqueue_style( 'bricks-advanced-themer-builder' );

        $custom_css = '';
        if(AT__Helpers::is_builder_tweaks_category_activated()){
            if(AT__Helpers::is_array($brxc_acf_fields, 'element_features')){
                if(in_array('diable-pin-on-elements', $brxc_acf_fields['element_features'])){

                    $custom_css .= 'body .bricks-panel #bricks-panel-elements :not([data-tab="components"]) .bricks-panel-actions-icon.pin{display: none !important;}';
                }
        
                if(in_array('increase-field-size', $brxc_acf_fields['element_features'])){
        
                    $custom_css .= '.bricks-panel-controls .control-inline>[data-control=number],.bricks-panel-controls .control-inline>div{flex-basis: 50%!important;max-width:unset;width:unset;flex: unset;}small>div, .bricks-panel-controls .control-small>label{flex: unset!important;}';
        
                }
                if(in_array('class-icons-reveal-on-hover', $brxc_acf_fields['element_features'])){
        
                    $custom_css .= '.active-selector>.actions-wrapper{display:none!important;}.active-selector:hover>.actions-wrapper,.active-selector:focus>.actions-wrapper{display:flex!important;}';
                }
            }
            
            if(isset($brxc_acf_fields['tab_icons_offset']) ){
    
                $custom_css .= '#bricks-panel .brxce-panel-shortcut__container{top:'. esc_attr($brxc_acf_fields['tab_icons_offset']) .'px;}';
            }
        }

        wp_add_inline_style('bricks-advanced-themer-builder', $custom_css, 'after');

        if( !function_exists('bricks_is_builder_iframe') || bricks_is_builder_iframe() ) return;
        
        // SASS
        $is_sass_active = AT__Helpers::is_value($brxc_acf_fields, 'superpowercss-enable-sass')  || AT__Helpers::is_value($brxc_acf_fields, 'advanced_css_enable_sass');
        if( $is_sass_active ){
            wp_enqueue_script('sass-worker-at');
            wp_enqueue_script('sass-at');
            wp_add_inline_script('sass-at', "Sass.setWorkerUrl('" . esc_url(\BRICKS_ADVANCED_THEMER_URL . 'assets/js/sass.worker.js?ver=1.0.0') . "');");
        }

        wp_enqueue_script('alwan');
        wp_enqueue_style('alwan');
        wp_enqueue_script( 'chroma' );
        wp_enqueue_script('brxc-builder');
        wp_enqueue_script('beautifer-css');
        wp_enqueue_script( 'brxc-builder-new-codemirror');
        wp_enqueue_script( 'brxc-builder-new-codemirror-mode-css', \BRICKS_ADVANCED_THEMER_URL . 'assets/js/codemirror/mode/css/css.js', [], filemtime( \BRICKS_ADVANCED_THEMER_PATH . 'assets/js/codemirror/mode/css/css.js' ) );
        wp_enqueue_script( 'brxc-builder-new-codemirror-mode-javascript', \BRICKS_ADVANCED_THEMER_URL . 'assets/js/codemirror/mode/javascript/javascript.js', [], filemtime( \BRICKS_ADVANCED_THEMER_PATH . 'assets/js/codemirror/mode/javascript/javascript.js' ) );
        wp_enqueue_script( 'brxc-builder-new-codemirror-mode-xml', \BRICKS_ADVANCED_THEMER_URL . 'assets/js/codemirror/mode/xml/xml.js', [], filemtime( \BRICKS_ADVANCED_THEMER_PATH . 'assets/js/codemirror/mode/xml/xml.js' ) );
        wp_enqueue_script( 'brxc-builder-new-codemirror-mode-htmlmixed', \BRICKS_ADVANCED_THEMER_URL . 'assets/js/codemirror/mode/htmlmixed/htmlmixed.js', [], filemtime( \BRICKS_ADVANCED_THEMER_PATH . 'assets/js/codemirror/mode/htmlmixed/htmlmixed.js' ) );
        wp_enqueue_script( 'brxc-builder-new-codemirror-mode-sass', \BRICKS_ADVANCED_THEMER_URL . 'assets/js/codemirror/mode/sass/sass.js', [], filemtime( \BRICKS_ADVANCED_THEMER_PATH . 'assets/js/codemirror/mode/sass/sass.js' ) );
        wp_enqueue_script( 'brxc-builder-new-codemirror-addon-dialog', \BRICKS_ADVANCED_THEMER_URL . 'assets/js/codemirror/addon/dialog/dialog.js', [], filemtime( \BRICKS_ADVANCED_THEMER_PATH . 'assets/js/codemirror/addon/dialog/dialog.js' ) );
        wp_enqueue_style( 'brxc-builder-new-codemirror-addon-dialog', \BRICKS_ADVANCED_THEMER_URL . 'assets/js/codemirror/addon/dialog/dialog.css', [], filemtime( \BRICKS_ADVANCED_THEMER_PATH . 'assets/js/codemirror/addon/dialog/dialog.css' ) );
        wp_enqueue_script( 'brxc-builder-new-codemirror-addon-placeholder', \BRICKS_ADVANCED_THEMER_URL . 'assets/js/codemirror/addon/display/placeholder.js', [], filemtime( \BRICKS_ADVANCED_THEMER_PATH . 'assets/js/codemirror/addon/display/placeholder.js' ) );
        wp_enqueue_script( 'brxc-builder-new-codemirror-addon-closeBrackets', \BRICKS_ADVANCED_THEMER_URL . 'assets/js/codemirror/addon/edit/closebrackets.js', [], filemtime( \BRICKS_ADVANCED_THEMER_PATH . 'assets/js/codemirror/addon/edit/closebrackets.js' ) );
        wp_enqueue_script( 'brxc-builder-new-codemirror-addon-closeTag', \BRICKS_ADVANCED_THEMER_URL . 'assets/js/codemirror/addon/edit/closetag.js', [], filemtime( \BRICKS_ADVANCED_THEMER_PATH . 'assets/js/codemirror/addon/edit/closetag.js' ) );
        wp_enqueue_script( 'brxc-builder-new-codemirror-addon-matchBrackets', \BRICKS_ADVANCED_THEMER_URL . 'assets/js/codemirror/addon/edit/matchbrackets.js', [], filemtime( \BRICKS_ADVANCED_THEMER_PATH . 'assets/js/codemirror/addon/edit/matchbrackets.js' ) );
        wp_enqueue_script( 'brxc-builder-new-codemirror-addon-fold-xml', \BRICKS_ADVANCED_THEMER_URL . 'assets/js/codemirror/addon/fold/xml-fold.js', [], filemtime( \BRICKS_ADVANCED_THEMER_PATH . 'assets/js/codemirror/addon/fold/xml-fold.js' ) );
        wp_enqueue_script( 'brxc-builder-new-codemirror-addon-matchTags', \BRICKS_ADVANCED_THEMER_URL . 'assets/js/codemirror/addon/edit/matchtags.js', [], filemtime( \BRICKS_ADVANCED_THEMER_PATH . 'assets/js/codemirror/addon/edit/matchtags.js' ) );
        wp_enqueue_style( 'brxc-builder-new-codemirror-addon-hint', \BRICKS_ADVANCED_THEMER_URL . 'assets/js/codemirror/addon/hint/show-hint.css', [], filemtime( \BRICKS_ADVANCED_THEMER_PATH . 'assets/js/codemirror/addon/hint/show-hint.css' ) );
        wp_enqueue_script( 'brxc-builder-new-codemirror-addon-hint', \BRICKS_ADVANCED_THEMER_URL . 'assets/js/codemirror/addon/hint/show-hint.js', [], filemtime( \BRICKS_ADVANCED_THEMER_PATH . 'assets/js/codemirror/addon/hint/show-hint.js' ) );
        wp_enqueue_script( 'brxc-builder-new-codemirror-addon-css-hint', \BRICKS_ADVANCED_THEMER_URL . 'assets/js/codemirror/addon/hint/css-hint.js', [], filemtime( \BRICKS_ADVANCED_THEMER_PATH . 'assets/js/codemirror/addon/hint/css-hint.js' ) );
        wp_enqueue_script( 'brxc-builder-new-codemirror-addon-xml-hint', \BRICKS_ADVANCED_THEMER_URL . 'assets/js/codemirror/addon/hint/xml-hint.js', [], filemtime( \BRICKS_ADVANCED_THEMER_PATH . 'assets/js/codemirror/addon/hint/xml-hint.js' ) );
        wp_enqueue_script( 'brxc-builder-new-codemirror-addon-html-hint', \BRICKS_ADVANCED_THEMER_URL . 'assets/js/codemirror/addon/hint/html-hint.js', [], filemtime( \BRICKS_ADVANCED_THEMER_PATH . 'assets/js/codemirror/addon/hint/html-hint.js' ) );
        wp_enqueue_script( 'brxc-builder-new-codemirror-addon-search', \BRICKS_ADVANCED_THEMER_URL . 'assets/js/codemirror/addon/search/search.js', [], filemtime( \BRICKS_ADVANCED_THEMER_PATH . 'assets/js/codemirror/addon/search/search.js' ) );
        wp_enqueue_script( 'brxc-builder-new-codemirror-addon-searchcursor', \BRICKS_ADVANCED_THEMER_URL . 'assets/js/codemirror/addon/search/searchcursor.js', [], filemtime( \BRICKS_ADVANCED_THEMER_PATH . 'assets/js/codemirror/addon/search/searchcursor.js' ) );
        wp_enqueue_script( 'brxc-builder-new-codemirror-addon-comment', \BRICKS_ADVANCED_THEMER_URL . 'assets/js/codemirror/addon/comment/comment.js', [], filemtime( \BRICKS_ADVANCED_THEMER_PATH . 'assets/js/codemirror/addon/comment/comment.js' ) );
        wp_enqueue_script( 'brxc-builder-emmet-codemirror', \BRICKS_ADVANCED_THEMER_URL . 'assets/js/emmet.js', [], filemtime( \BRICKS_ADVANCED_THEMER_PATH . 'assets/js/emmet.js' ) );
        wp_localize_script( 'brxc-builder', 'openai_ajax_req', array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'nonce' => wp_create_nonce( 'openai_ajax_nonce' ) ) );
    }
}