<?php

if (!defined('ABSPATH')) { die();
}

/*--------------------------------------
CONST
--------------------------------------*/

$brxc_options = get_option('bricks_advanced_themer_builder_settings');

function brxc_is_option_enabled($option_key, $options) {
    return isset($options['converted'][$option_key]) && $options['converted'][$option_key] === 1;
}

define('BRICKS_ADVANCED_THEMER_CLAMP_SETTINGS_CONVERTED', brxc_is_option_enabled('clamp_settings', $brxc_options));
define('BRICKS_ADVANCED_THEMER_GRID_UTILITY_CLASSES_CONVERTED', brxc_is_option_enabled('grid_utility_classes_3_2', $brxc_options));
define('BRICKS_ADVANCED_THEMER_GLOBAL_COLORS_PREFIX_CONVERTED', brxc_is_option_enabled('global_colors_prefix', $brxc_options));