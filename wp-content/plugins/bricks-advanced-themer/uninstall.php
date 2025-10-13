<?php
    if (!defined('ABSPATH')) { die();
}
$remove_data = get_option('bricks-advanced-themer__brxc_remove_data_uninstall');
if(isset($remove_data) && $remove_data == 1){
    global $wpdb;

    $all_post_ids = get_posts(array(
        'posts_per_page' => -1,
        'post_type'      => 'brxc_color_palette'
    ));

    if (isset($all_post_ids) && is_array($all_post_ids)) {
        foreach ($all_post_ids as $post) {
            wp_delete_post($post->ID, true);
        }
    }

    // Delete postmeta data associated with 'brxc_color_palette'
    $result_postmeta = $wpdb->query("DELETE FROM $wpdb->postmeta WHERE post_id IN (SELECT ID FROM $wpdb->posts WHERE post_type = 'brxc_color_palette')");

    // Delete options from wp_options table with 'bricks-advanced-themer' in option_name
    $sql = "SELECT option_name FROM $wpdb->options  WHERE option_name LIKE '%bricks%advanced%themer%'";
    $result = $wpdb->get_results($sql, 'ARRAY_A');   

    if($result && is_array($result)) {
        foreach($result as $row) {
            delete_option($row['option_name']);
        }
    }
    delete_option('advanced_themer_color_palette_converted');

    // Classes

    $global_classes = get_option('bricks_global_classes');

    if (isset($global_classes) && is_array($global_classes)) {
        foreach ($global_classes as $index => $global_class) {
            if (isset($global_class['id']) && strpos($global_class['id'], 'brxc_') !== false) {
                unset($global_classes[$index]);
            }
        }

        $global_classes = array_values($global_classes);

        update_option('bricks_global_classes', $global_classes);
    }

    // Variables

    $themes = get_option('bricks_theme_styles');

    if (isset($themes) && is_array($themes)) {
        foreach ($themes as $index => $theme) {
            if (isset($theme['settings']) && isset($theme['settings']['general']) && isset($theme['settings']['general']['_cssVariables'])) {
                unset($themes[$index]['settings']['general']['_cssVariables']);
            }
        }

        update_option('bricks_theme_styles', $themes);
    }

    // Colors
    $palettes = get_option('bricks_color_palette');

    if (isset($palettes) && is_array($palettes)) {
        foreach ($palettes as $index1 => $palette) {
            $colors = $palette['colors'];
            if (isset($colors) && is_array($colors)) {
                foreach ($colors as $index2 => $color) {
                    if (isset($color['rawValue'])) {
                        unset($palettes[$index1]['colors'][$index2]);
                    }
                }
                if(empty($palettes[$index1]['colors'])){
                    unset($palettes[$index1]);
                }
            }
        }

        $palettes = array_values($palettes);
        
        update_option('bricks_color_palette', $palettes);
    }

    // Options
    delete_option('bricks-advanced-themer_frontend_styles');
    delete_option('brxc_license_key');
    delete_option('brxc_license_status');
    delete_option('brxc_license_date_created');
}