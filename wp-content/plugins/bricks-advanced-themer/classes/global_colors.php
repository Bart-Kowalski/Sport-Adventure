<?php
namespace Advanced_Themer_Bricks;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class AT__Global_Colors{

    public static function force_default_color_scheme(){
        global $brxc_acf_fields;

        if( !AT__Helpers::is_global_colors_category_activated() || !isset($brxc_acf_fields['force_default_color_scheme'])) return;

        wp_add_inline_script('brxc-darkmode-local-storage', "const BRXC_FORCE_DEFAULT_SCHEME_COLOR = '" . $brxc_acf_fields['force_default_color_scheme'] . "';", 'before');

    }

    private static function add_prefix($name, $is_framework, $palette_prefix){;

        if(!$palette_prefix || $palette_prefix === '' || $palette_prefix === 0 || substr($name, 0, strlen($palette_prefix)) === $palette_prefix || $is_framework) return $name;

        return $palette_prefix . $name;
    }

    public static function is_framework($id){
        $prefix = 'acss';
        if(substr($id, 0, strlen($prefix)) === $prefix){
            return true;
        }

        return false;

    }
    public static function load_converted_colors_variables_on_frontend(){

        $palette_arr = get_option('bricks_color_palette', [] );

        if( !isset($palette_arr) || !$palette_arr || !is_array($palette_arr) || empty($palette_arr) ) {
            return false;
        }

        $light = '';
        $dark = ''; 
        $property = '';

        foreach ($palette_arr as &$palette) {

            // Override palette settings
            $palette = apply_filters('at/color_palettes/override_palette_settings', $palette);

            if (isset($palette['status']) && $palette['status'] === 'disabled'){
                continue;
            }

            $palette_prefix = $palette['prefix'] ?? false; 


            if (isset($palette['colors']) && $palette['colors'] && is_array($palette['colors'])) {
                foreach ($palette['colors'] as &$color) {
                    $is_framework = self::is_framework($color['id']);
                    $name = preg_replace('/[^a-zA-Z0-9_-]+/', '-', strtolower(trim($color['name'])));
  
                    if ( isset($color['raw'], $color['rawValue'], $color['rawValue']['light']) && !isset($color['isVariableOnly']) ) {
                        
                        // @property
                        if (isset($color['colorProperty']) && $color['colorProperty'] === true) {
                            $tempproperty = "@property --" . esc_attr(self::add_prefix($name, $is_framework, $palette_prefix)) . "{syntax: '&lt;color&gt;';initial-value: " . esc_attr($color['rawValue']['light']) . ";inherits: true;}";
                            $temp = wp_specialchars_decode($tempproperty);
                            $property .= $temp;
                        }

                        // Root Color
                        $light .=  '--' . esc_attr(self::add_prefix($name, $is_framework, $palette_prefix)) . ':' .  esc_attr($color['rawValue']['light']) . ';';
                        // HSL Values
                        if (isset($color['shadeChildren']) && preg_match('/hsla\(([^,]+),([^,]+%),([^,]+%)/', $color['rawValue']['light'], $matches)) {
                            $light .=  '--' . esc_attr(self::add_prefix($name, $is_framework, $palette_prefix)) . '-h:' .  esc_attr($matches[1]) . ';';
                            $light .=  '--' . esc_attr(self::add_prefix($name, $is_framework, $palette_prefix)) . '-s:' .  esc_attr($matches[2]) . ';';
                            $light .=  '--' . esc_attr(self::add_prefix($name, $is_framework, $palette_prefix)) . '-l:' .  esc_attr($matches[3]) . ';';
                        }

                    }
                    if ( isset($color['raw']) && isset($color['rawValue']) && isset($color['rawValue']['dark']) ) {
                        $dark .=  '--' . esc_attr(self::add_prefix($name, $is_framework, $palette_prefix)) . ':' .  esc_attr($color['rawValue']['dark']) . ';';

                        // HSL Values
                        if (isset($color['shadeChildren']) && preg_match('/hsla\(([^,]+),([^,]+%),([^,]+%)/', $color['rawValue']['dark'], $matches)) {
                            $dark .=  '--' . esc_attr(self::add_prefix($name, $is_framework, $palette_prefix)) . '-h:' .  esc_attr($matches[1]) . ';';
                            $dark .=  '--' . esc_attr(self::add_prefix($name, $is_framework, $palette_prefix)) . '-s:' .  esc_attr($matches[2]) . ';';
                            $dark .=  '--' . esc_attr(self::add_prefix($name, $is_framework, $palette_prefix)) . '-l:' .  esc_attr($matches[3]) . ';';
                        }
                        
                    }
                }
            }
        }

        return [$light, $dark, $property];
    }

    public static function theme_support_load_gutenberg_colors() {


        global $brxc_acf_fields;

        if ( !AT__Helpers::is_value($brxc_acf_fields, 'replace_gutenberg_palettes') ){
            return;
        }

        $palette_arr = get_option('bricks_color_palette', [] );

        if( !AT__Helpers::is_array($palette_arr) ) {
            return;
        }

        $gutenberg_colors = [];

        foreach ($palette_arr as $palette) {

            // Override palette settings
            $palette = apply_filters('at/color_palettes/override_palette_settings', $palette);

            if (isset($palette['status']) && $palette['status'] === 'disabled'){
                continue;
            }

            $palette_prefix = $palette['prefix'] ?? false; 

            if (AT__Helpers::is_array($palette, 'colors')) {
                foreach ($palette['colors'] as $color) {
                    $final_color = '';
                    $is_framework = self::is_framework($color['id']);
                    $name = preg_replace('/[^a-zA-Z0-9_-]+/', '-', strtolower(trim($color['name'])));

                    foreach(['hex', 'rgb','hsl','raw'] as $format){
                        if( isset($color[$format] )){
                            $final_color = $color[$format];
                        }
                    }
                    if (isset($color['rawValue']) && isset($color['rawValue']['light']) ){
                        $final_color = 'var(--' . esc_attr(self::add_prefix($name, $is_framework, $palette_prefix)) . ')';
                    }
                    $color_arr = array(
                        'name' => self::add_prefix($name, $is_framework, $palette_prefix),
                        'slug' => $name,
                        'color' => $final_color,
                    );
                    $gutenberg_colors[] = $color_arr;
                }
            }
        }

        add_theme_support('editor-color-palette', $gutenberg_colors);

    }
}