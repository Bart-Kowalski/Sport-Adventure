<?php
namespace Advanced_Themer_Bricks;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class AT__Conversion{
    private static function set_option_as_converted($key) {
        $option = get_option('bricks_advanced_themer_builder_settings', []);
    
    
        if (!isset($option['converted']) || !is_array($option['converted'])) {
            $option['converted'] = [];
        }
    
        $option['converted'][$key] = 1;
    
        update_option('bricks_advanced_themer_builder_settings', $option);
    }

    private static function has_entry_with_name($array, $name) {
        if(AT__Helpers::is_array($array)){
            foreach ($array as $entry) {
                if ($entry['name'] === $name) {
                    return $entry;
                }
            }
        }
        return false;
    }

    /**
     * Main function to convert grid utility classes.
     */
    public static function convert_grid_utility_classes_function() {
        $global_classes = get_option('bricks_global_classes', []);
        $global_classes_cats = get_option('bricks_global_classes_categories', []);

        if (have_rows('field_63b59j871b209', 'bricks-advanced-themer')) :
            while (have_rows('field_63b59j871b209', 'bricks-advanced-themer')) : the_row();

                if (have_rows('field_63b48c6f1b20a', 'bricks-advanced-themer')) :
                    // Ensure category is added if not already present
                    self::ensure_grid_utility_category_exists($global_classes_cats);

                    while (have_rows('field_63b48c6f1b20a', 'bricks-advanced-themer')) :
                        the_row();

                        // Prepare the grid utility data
                        $item = [
                            "name"  => get_sub_field('field_63b48c6f1b20b', 'bricks-advanced-themer'),
                            "gap"   => get_sub_field('field_63b48d7e1b20e', 'bricks-advanced-themer'),
                            "cols"  => get_sub_field('field_63b48c6f1b20c', 'bricks-advanced-themer'),
                            "width" => get_sub_field('field_63b48c6f1b20d', 'bricks-advanced-themer'),
                        ];

                        // Check and update the class or create a new one
                        self::update_or_create_grid_class($global_classes, $item);

                    endwhile;
                endif;

            endwhile;
        endif;

        update_option('bricks_global_classes', $global_classes);
    }

    public static function convert_grid_utility_classes() {
        if ( (defined('BRICKS_ADVANCED_THEMER_GRID_UTILITY_CLASSES_CONVERTED') && BRICKS_ADVANCED_THEMER_GRID_UTILITY_CLASSES_CONVERTED === true) || !AT__Helpers::is_grids_tab_activated()) {
            return;
        }

        self::convert_grid_utility_classes_function();

        self::set_option_as_converted('grid_utility_classes_3_2');
    }

    /**
     * Ensures that the grid utility classes category exists in the global classes categories.
     *
     * @param array &$global_classes_cats The global classes categories.
     */
    private static function ensure_grid_utility_category_exists(&$global_classes_cats) {
        if (!in_array("brxc_grid_utility_classes", array_column($global_classes_cats, 'id'))) {
            $global_classes_cats[] = [
                "id"   => "brxc_grid_utility_classes",
                "name" => "Grid Utility Classes",
            ];
            update_option('bricks_global_classes_categories', $global_classes_cats);
        }
    }

    /**
     * Updates the global classes array by either updating an existing class or creating a new one.
     *
     * @param array &$global_classes The global classes array.
     * @param array $item The grid utility item.
     */
    private static function update_or_create_grid_class(&$global_classes, $item) {
        $id_prefix = 'brxc_grid_';
        $classFound = false;
        $classRemoved = false;
    
        foreach ($global_classes as $index => &$class) {
            // Remove any class without a 'name' property
            if (!is_array($class) || !isset($class['name'], $class['id'])) {
                unset($global_classes[$index]);
                $classRemoved = true;
                continue;
            }
    
            if (
                $class['name'] === $item['name'] &&
                str_starts_with($class['id'], $id_prefix)
            ) {
                $class['category'] = 'brxc_grid_utility_classes';
                $class['gridUtility'] = true;
                $class['settings'] = self::generate_grid_class_settings($item);
                $classFound = true;
                break;
            }
        }
    
        // Reindex if any class has been removed
        if ($classRemoved) {
            $global_classes = array_values($global_classes);
        }
    
        // Create a new class if none was found
        if (!$classFound) {
            $new_id = isset($item['name']) ? $id_prefix . $item['name'] : $id_prefix . AT__Helpers::generate_unique_string(6);
            $global_classes[] = [
                'id' => $new_id,
                'name' => $item['name'],
                'category' => 'brxc_grid_utility_classes',
                'gridUtility' => true,
                'settings' => self::generate_grid_class_settings($item)
            ];
        }
    }
    

    /**
     * Generates the settings array for a grid utility class.
     *
     * @param array $item The grid utility item.
     * @return array The generated settings.
     */
    private static function generate_grid_class_settings($item) {
        return [
            "gridUtilityGap" => $item['gap'],
            "gridUtilityCols" => $item['cols'],
            "gridUtilityWidth" => $item['width'],
            "_display" => "grid",
            "_gridGap" => "var(--grid-layout-gap)",
            "_cssCustom" => "/* SCOPED VARIABLES */\n.{$item['name']} {\n\t--grid-column-count: {$item['cols']};\n\t--grid-item--min-width: {$item['width']}px;\n\t--grid-layout-gap: {$item['gap']};\n\tgrid-template-columns: repeat(auto-fit, minmax(min(100%, var(--grid-item--min-width)), 1fr))\n}\n\n/* RESPONSIVE CODE */\n@media screen and (min-width: 781px){\n\tbody .{$item['name']} {\n\t\t--gap-count: calc(var(--grid-column-count) - 1);\n\t\t--total-gap-width: calc(var(--gap-count) * var(--grid-layout-gap));\n\t\t--grid-item--max-width: calc((100% - var(--total-gap-width)) / var(--grid-column-count));\n\t\tgrid-template-columns: repeat(auto-fill, minmax(max(var(--grid-item--min-width), var(--grid-item--max-width)), 1fr));\n\t}\n}"
        ];
    }
  
    public static function convert_clamp_settings() {
        global $brxc_acf_fields;
        
        if (defined('BRICKS_ADVANCED_THEMER_CLAMP_SETTINGS_CONVERTED') && BRICKS_ADVANCED_THEMER_CLAMP_SETTINGS_CONVERTED) {
            return;
        }
    
        $categories = get_option('bricks_global_variables_categories', []);
        $variables = get_option('bricks_global_variables', []);
    
        function item_exists($array, $id) {
            if(AT__Helpers::is_array($array)) {
                foreach ($array as $item) {
                    if (isset($item['id']) && $item['id'] === $id) {
                        return true;
                    }
                }
            }
            return false;
        }
    
        if (!item_exists($categories, 'at_clamp-settings')) {
            $categories[] = [
                'id' => 'at_clamp-settings',
                'name' => 'AT - Clamp Settings',
            ];
        }
    
        $new_variables = [
            [
                'id' => 'at_min-viewport',
                'name' => 'min-viewport',
                'value' => $brxc_acf_fields['min_vw'] ?? '360',
                'category' => 'at_clamp-settings'
            ],
            [
                'id' => 'at_max-viewport',
                'name' => 'max-viewport',
                'value' => $brxc_acf_fields['max_vw'] ?? '1600',
                'category' => 'at_clamp-settings'
            ],
            [
                'id' => 'at_base-font',
                'name' => 'base-font',
                'value' => $brxc_acf_fields['base_font'] ?? '10',
                'category' => 'at_clamp-settings'
            ],
            [
                'id' => 'at_clamp-unit',
                'name' => 'clamp-unit',
                'value' => '1' . ($brxc_acf_fields['clamp_unit'] ?? 'vw'),
                'category' => 'at_clamp-settings'
            ]
        ];
    
        foreach ($new_variables as $variable) {
            if (!item_exists($variables, $variable['id'])) {
                $variables[] = $variable;
            }
        }
    
        update_option('bricks_global_variables_categories', $categories);
        update_option('bricks_global_variables', $variables);
    
        self::set_option_as_converted('clamp_settings');
    }    

    public static function convert_settings_to_logical_properties(array $obj): array {
        $keyTransformations = [
            '_margin' => [
                'newKey' => '_marginLogical',
                'map' => ['top' => 'block-start', 'bottom' => 'block-end', 'left' => 'inline-start', 'right' => 'inline-end']
            ],
            '_padding' => [
                'newKey' => '_paddingLogical',
                'map' => ['top' => 'block-start', 'bottom' => 'block-end', 'left' => 'inline-start', 'right' => 'inline-end']
            ],
            '_border' => [
                'width' => [
                    'newKey' => '_borderWidthLogical',
                    'map' => ['top' => 'block-start-width', 'bottom' => 'block-end-width', 'left' => 'inline-start-width', 'right' => 'inline-end-width']
                ],
                'radius' => [
                    'newKey' => '_borderRadiusLogical',
                    'map' => ['top' => 'start-start-radius', 'bottom' => 'end-start-radius', 'left' => 'end-end-radius', 'right' => 'start-end-radius']
                ],
                'style' => ['newKey' => '_borderStyle'],
                'color' => ['newKey' => '_borderColor']
            ],
            '_top' => ['newKey' => '_insetLogical', 'subKey' => 'block-start'],
            '_bottom' => ['newKey' => '_insetLogical', 'subKey' => 'block-end'],
            '_left' => ['newKey' => '_insetLogical', 'subKey' => 'inline-start'],
            '_right' => ['newKey' => '_insetLogical', 'subKey' => 'inline-end'],
            '_width' => ['newKey' => '_inlineSize'],
            '_widthMin' => ['newKey' => '_inlineSizeMin'],
            '_widthMax' => ['newKey' => '_inlineSizeMax'],
            '_height' => ['newKey' => '_blockSize'],
            '_heightMin' => ['newKey' => '_blockSizeMin'],
            '_heightMax' => ['newKey' => '_blockSizeMax']
        ];
    
        $transformedObj = [];
    
        foreach ($obj as $key => $value) {
            $parts = explode(':', $key, 2);
            $baseKey = $parts[0];
            $suffix = isset($parts[1]) ? ":{$parts[1]}" : '';
    
            if (isset($keyTransformations[$baseKey])) {
                $transform = $keyTransformations[$baseKey];
                if (isset($transform['newKey'])) {
                    if (isset($transform['map']) && is_array($value)) {
                        $newValue = [];
                        foreach ($value as $subKey => $val) {
                            $newSubKey = $transform['map'][$subKey] ?? $subKey;
                            $newValue[$newSubKey] = $val;
                        }
                        $transformedObj[$transform['newKey'] . $suffix] = $newValue;
                    } elseif (isset($transform['subKey'])) {
                        if (!isset($transformedObj[$transform['newKey'] . $suffix])) {
                            $transformedObj[$transform['newKey'] . $suffix] = [];
                        }
                        $transformedObj[$transform['newKey'] . $suffix][$transform['subKey']] = $value;
                    } else {
                        $transformedObj[$transform['newKey'] . $suffix] = $value;
                    }
                } else {
                    foreach ($value as $subKey => $subValue) {
                        if (isset($transform[$subKey]['newKey'])) {
                            $subTransform = $transform[$subKey];
                            if (isset($subTransform['map']) && is_array($subValue)) {
                                $newValue = [];
                                foreach ($subValue as $innerKey => $innerVal) {
                                    $newInnerKey = $subTransform['map'][$innerKey] ?? $innerKey;
                                    $newValue[$newInnerKey] = $innerVal;
                                }
                                $transformedObj[$subTransform['newKey'] . $suffix] = $newValue;
                            } else {
                                $transformedObj[$subTransform['newKey'] . $suffix] = $subValue;
                            }
                        } else {
                            if (!isset($transformedObj[$key . $suffix])) {
                                $transformedObj[$key . $suffix] = [];
                            }
                            $transformedObj[$key . $suffix][$subKey] = $subValue;
                        }
                    }
                }
            } else {
                $transformedObj[$key] = $value;
            }
        }
    
        return $transformedObj;
    }

    public static function convert_settings_to_directional_properties(array $obj): array {
        $reverseTransformations = [
            '_marginLogical' => [
                'newKey' => '_margin',
                'map' => ['block-start' => 'top', 'block-end' => 'bottom', 'inline-start' => 'left', 'inline-end' => 'right']
            ],
            '_paddingLogical' => [
                'newKey' => '_padding',
                'map' => ['block-start' => 'top', 'block-end' => 'bottom', 'inline-start' => 'left', 'inline-end' => 'right']
            ],
            '_borderWidthLogical' => [
                'newKey' => '_border',
                'map' => ['block-start-width' => 'top', 'block-end-width' => 'bottom', 'inline-start-width' => 'left', 'inline-end-width' => 'right'],
                'subKey' => 'width'
            ],
            '_borderRadiusLogical' => [
                'newKey' => '_border',
                'map' => ['start-start-radius' => 'top', 'end-start-radius' => 'bottom', 'end-end-radius' => 'left', 'start-end-radius' => 'right'],
                'subKey' => 'radius'
            ],
            '_borderStyle' => ['newKey' => '_border', 'subKey' => 'style'],
            '_borderColor' => ['newKey' => '_border', 'subKey' => 'color'],
            '_insetLogical' => [
                'newKey' => null, // handled by subKey mapping
                'map' => ['block-start' => '_top', 'block-end' => '_bottom', 'inline-start' => '_left', 'inline-end' => '_right']
            ],
            '_inlineSize'    => ['newKey' => '_width'],
            '_inlineSizeMin' => ['newKey' => '_widthMin'],
            '_inlineSizeMax' => ['newKey' => '_widthMax'],
            '_blockSize'     => ['newKey' => '_height'],
            '_blockSizeMin'  => ['newKey' => '_heightMin'],
            '_blockSizeMax'  => ['newKey' => '_heightMax']
        ];

        $transformedObj = [];

        foreach ($obj as $key => $value) {
            $parts = explode(':', $key, 2);
            $baseKey = $parts[0];
            $suffix = isset($parts[1]) ? ":{$parts[1]}" : '';

            if (isset($reverseTransformations[$baseKey])) {
                $transform = $reverseTransformations[$baseKey];

                // margin/padding/border width/radius maps
                if (isset($transform['map']) && is_array($value)) {
                    if ($baseKey === '_insetLogical') {
                        // special case: inset maps directly to top/bottom/left/right
                        foreach ($value as $subKey => $val) {
                            $dirKey = $transform['map'][$subKey] ?? null;
                            if ($dirKey) {
                                $transformedObj[$dirKey . $suffix] = $val;
                            }
                        }
                    } elseif (isset($transform['subKey'])) {
                        $newArr = [];
                        foreach ($value as $subKey => $val) {
                            $dirKey = $transform['map'][$subKey] ?? $subKey;
                            $newArr[$dirKey] = $val;
                        }
                        $newKey = $transform['newKey'] . $suffix;
                        if (!isset($transformedObj[$newKey])) {
                            $transformedObj[$newKey] = [];
                        }
                        $transformedObj[$newKey][$transform['subKey']] = $newArr;
                    } else {
                        $newArr = [];
                        foreach ($value as $subKey => $val) {
                            $dirKey = $transform['map'][$subKey] ?? $subKey;
                            $newArr[$dirKey] = $val;
                        }
                        $transformedObj[$transform['newKey'] . $suffix] = $newArr;
                    }
                }
                // simple key with optional subKey (style, color, size, etc.)
                elseif (isset($transform['subKey'])) {
                    $newKey = $transform['newKey'] . $suffix;
                    if (!isset($transformedObj[$newKey])) {
                        $transformedObj[$newKey] = [];
                    }
                    $transformedObj[$newKey][$transform['subKey']] = $value;
                } else {
                    $newKey = $transform['newKey'] . $suffix;
                    $transformedObj[$newKey] = $value;
                }
            } else {
                // no transformation → keep as is
                $transformedObj[$key] = $value;
            }
        }

        return $transformedObj;
    }

    public static function convert_settings_to_hide_remove_properties(array $obj): array {
        $keyTransformations = [
            'unrenderFrontend' => '_hideElementFrontend',
            'hideElement'      => '_hideElementBuilder',
        ];
    
        $transformedObj = [];
    
        foreach ($obj as $key => $value) {
            $newKey = $keyTransformations[$key] ?? $key;
            $transformedObj[$newKey] = $value;
        }
    
        return $transformedObj;
    }


    public static function convert_global_colors_prefix() {
        if ( (defined('BRICKS_ADVANCED_THEMER_GLOBAL_COLORS_PREFIX_CONVERTED') && BRICKS_ADVANCED_THEMER_GLOBAL_COLORS_PREFIX_CONVERTED === true)) return;
        
        global $brxc_acf_fields;
        $global_colors = get_option('bricks_color_palette', []);
        $old_prefix = !empty($brxc_acf_fields['color_prefix']) ? $brxc_acf_fields['color_prefix'] . '-' : '';
        
        if(AT__Helpers::is_array($global_colors)) {
            foreach($global_colors as &$color){
                if(isset($color["at_framework"]) && $color["at_framework"] === true){
                    $color["prefix"] = "at-";
                } else {
                    $color["prefix"] = $old_prefix;
                }
            }
        }
    
        update_option( 'bricks_color_palette', $global_colors);
        self::set_option_as_converted('global_colors_prefix');
    }  
}
