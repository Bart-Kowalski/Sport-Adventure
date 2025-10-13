<?php
namespace Advanced_Themer_Bricks;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class AT__Grid_Builder{

    private static function generate_grid_css($classes){
        $imploded_classes = implode(',', $classes);
        $custom_css = '';
        $custom_css .= $imploded_classes;
        $custom_css .= '{display:grid;gap:var(--grid-layout-gap);grid-template-columns: repeat(auto-fit, minmax(min(100%, var(--grid-item--min-width)), 1fr));}@media screen and (min-width: 781px){';
        $custom_css .= $imploded_classes;
        $custom_css .= '{--gap-count: calc(var(--grid-column-count) - 1);--total-gap-width: calc(var(--gap-count) * var(--grid-layout-gap));--grid-item--max-width: calc((100% - var(--total-gap-width)) / var(--grid-column-count));grid-template-columns: repeat(auto-fill, minmax(max(var(--grid-item--min-width), var(--grid-item--max-width)), 1fr));}}';
        return $custom_css;
    }

    public static function grid_builder_classes() {

        $custom_css = '';
        $at_options = get_option('bricks_advanced_themer_builder_settings', []);
        
        if(AT__Helpers::is_array($at_options, 'grid_utility_classes')){
                                    
            $grid_classes = $at_options['grid_utility_classes'];
            if(empty($grid_classes)) return '';

            $classes = [];
            foreach($grid_classes as $item){
                $classes[] = 'body .' . $item['name'];
            }

            $custom_css .= self::generate_grid_css($classes);

        // ACF backwards compatibility
        } else {
            if ( have_rows( 'field_63b59j871b209' , 'bricks-advanced-themer' ) ) :
                while ( have_rows( 'field_63b59j871b209' , 'bricks-advanced-themer' ) ) : the_row();
    
                    if ( have_rows( 'field_63b48c6f1b20a', 'bricks-advanced-themer' ) ) :
    
                        $classes = [];
    
                        while ( have_rows( 'field_63b48c6f1b20a', 'bricks-advanced-themer' ) ) :
                            the_row();
                            $classes[] = 'body .' . get_sub_field('field_63b48c6f1b20b', 'bricks-advanced-themer' );
                        endwhile;

                        $custom_css .= self::generate_grid_css($classes);
    
                    endif;
    
                endwhile;
            endif;
        }

        return $custom_css;

    }
}