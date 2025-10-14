<?php
namespace Advanced_Themer_Bricks;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class AT__Class_Importer{

    public static function extract_selectors_from_css( $file ) {

        $file = AT__Helpers::read_file_contents($file);
    
        if (!$file){
    
            return;
        }
    
        // Remove comments from the file
        $file = preg_replace('!/\*.*?\*/!s', '', $file);
    
        // Remove media query declarations but keep the content
        $file = preg_replace('/@media[^{]*\{/i', '', $file);
    
        // Close remaining brackets from media queries
        $file = preg_replace('/\s*}\s*(?=})/', '', $file);
    
        $pattern_one = '/(?<=\{)(.*?)(?=\})/s';
    
        $pattern_two = '/[\.][\w|-]*/';
    
        $stripped = preg_replace($pattern_one, '', $file);
    
        $selectors = array();
    
        $matches = preg_match_all($pattern_two, $stripped, $selectors);
    
        return array_unique($selectors[0]);
    
    }

    public static function enqueue_uploaded_css() {
        if (have_rows('field_63b59j871b209', 'bricks-advanced-themer')) :
            while (have_rows('field_63b59j871b209', 'bricks-advanced-themer')) : the_row();
                if (have_rows('field_63b4bd5c16ac1', 'bricks-advanced-themer')) :
                    while (have_rows('field_63b4bd5c16ac1', 'bricks-advanced-themer')) :
                        the_row();
    
                        $class_importer_label = get_sub_field('field_63b4bd5c16ac3', 'bricks-advanced-themer');
                        $class_importer_enqueue = get_sub_field('field_6406649wff5c12', 'bricks-advanced-themer');
    
                        if (isset($class_importer_enqueue) && $class_importer_enqueue === false) return;
    
                        $class_importer_version = get_sub_field('field_63b4bd5c16ac4', 'bricks-advanced-themer');
                        !AT__Helpers::is_value($class_importer_version) ? $class_importer_version = microtime(true) : '';
    
                        $priority = get_sub_field('field_6f8v4s1x4a5ff', 'bricks-advanced-themer');
                        $position_temp = get_sub_field('field_6f5o9q1d14dd1', 'bricks-advanced-themer');
                        (isset($position_temp) && $position_temp === 'head') ? $position = 'wp_enqueue_scripts' : $position = 'get_footer';
                        $class_importer_file = get_sub_field('field_63b4bdf216ac7', 'bricks-advanced-themer');
    
                        if (
                            (function_exists('bricks_is_builder') && bricks_is_builder() && !bricks_is_builder_iframe()) ||
                            !isset($class_importer_label, $priority, $position, $class_importer_file, $class_importer_version)
                        ) {
                            return;
                        }
                        // Use the variables inside the add_action function
                        add_action($position, function () use ($class_importer_label, $class_importer_file, $class_importer_version) {
                            wp_enqueue_style('brxc-' . $class_importer_label, $class_importer_file, array(), $class_importer_version, 'all');
                        }, $priority);
                    endwhile;
                endif;
            endwhile;
        endif;
    }
}