<?php
namespace Advanced_Themer_Bricks;
if (!defined('ABSPATH')) { die();
}

/*--------------------------------------
Variables
--------------------------------------*/

// ID & Classes
$overlay_id = 'brxcBoxShadowUIOverlay';
$prefix = "box-shadow-ui";
$prefix_id = 'brxcGBoxShadowUI';
$prefix_class = 'brxc-box-shadow-ui';
// Heading
$modal_heading_title = 'Box-shadow Generator';

ob_start();

if (!AT__Helpers::is_builder_tweaks_category_activated()){
    $theme_settings = \get_admin_url() . 'admin.php?page=bricks-advanced-themer';
    $error_title = "Feature not enabled";
    $error_desc = "It seems like this feature hasn't been enabled inside the theme settings. Click on the button below and make sure that the <strong class='accent'>Builder Tweaks</strong> settings are enabled inside <strong class='accent'>Global Settings > General > Customize the functions included in Advanced Themer</strong>.";
    include \BRICKS_ADVANCED_THEMER_PATH . '/inc/builderPanels/_default_error.php';
} else {


$objs = [
    [
        [
            "rgb" => "0,0,0",
            "alpha" => 0.45,
            "x" => 0,
            "y" => -25,
            "blur" => 20,
            "spread" => -20,
        ],
    ],
    [
        [
            "rgb" => "0,0,0",
            "alpha" => 0.45,
            "x" => 25,
            "y" => 0,
            "blur" => 20,
            "spread" => -20,
        ],
    ],
    [
        [
            "rgb" => "0,0,0",
            "alpha" => 0.45,
            "x" => 0,
            "y" => 25,
            "blur" => 20,
            "spread" => -20,
        ],
    ],
    [
        [
            "rgb" => "0,0,0",
            "alpha" => 0.45,
            "x" => -25,
            "y" => 0,
            "blur" => 20,
            "spread" => -20,
        ],
    ],
    [
        [
            "rgb" => "0,0,0",
            "alpha" => 0.45,
            "x" => 0,
            "y" => -25,
            "blur" => 20,
            "spread" => -20,
        ],
        [
            "rgb" => "0,0,0",
            "alpha" => 0.45,
            "x" => 25,
            "y" => 0,
            "blur" => 20,
            "spread" => -20,
        ],
    ],
    [
        [
            "rgb" => "0,0,0",
            "alpha" => 0.45,
            "x" => 0,
            "y" => -25,
            "blur" => 20,
            "spread" => -20,
        ],
        [
            "rgb" => "0,0,0",
            "alpha" => 0.45,
            "x" => 0,
            "y" => 25,
            "blur" => 20,
            "spread" => -20,
        ],
    ],
    [
        [
            "rgb" => "0,0,0",
            "alpha" => 0.45,
            "x" => 0,
            "y" => -25,
            "blur" => 20,
            "spread" => -20,
        ],
        [
            "rgb" => "0,0,0",
            "alpha" => 0.45,
            "x" => -25,
            "y" => 0,
            "blur" => 20,
            "spread" => -20,
        ],
    ],
    [
        [
            "rgb" => "0,0,0",
            "alpha" => 0.45,
            "x" => 0,
            "y" => 25,
            "blur" => 20,
            "spread" => -20,
        ],
        [
            "rgb" => "0,0,0",
            "alpha" => 0.45,
            "x" => 25,
            "y" => 0,
            "blur" => 20,
            "spread" => -20,
        ],
    ],
    [
        [
            "rgb" => "0,0,0",
            "alpha" => 0.45,
            "x" => 0,
            "y" => 25,
            "blur" => 20,
            "spread" => -20,
        ],
        [
            "rgb" => "0,0,0",
            "alpha" => 0.45,
            "x" => -25,
            "y" => 0,
            "blur" => 20,
            "spread" => -20,
        ],
    ],
    [
        [
            "rgb" => "0,0,0",
            "alpha" => 0.45,
            "x" => 25,
            "y" => 0,
            "blur" => 20,
            "spread" => -20,
        ],
        [
            "rgb" => "0,0,0",
            "alpha" => 0.45,
            "x" => -25,
            "y" => 0,
            "blur" => 20,
            "spread" => -20,
        ],
    ],
    [
        [
            "rgb" => "0,0,0",
            "alpha" => 0.45,
            "x" => 0,
            "y" => -25,
            "blur" => 20,
            "spread" => -20,
        ],
        [
            "rgb" => "0,0,0",
            "alpha" => 0.45,
            "x" => 0,
            "y" => 25,
            "blur" => 20,
            "spread" => -20,
        ],
        [
            "rgb" => "0,0,0",
            "alpha" => 0.45,
            "x" => 25,
            "y" => 0,
            "blur" => 20,
            "spread" => -20,
        ],
    ],
    [
        [
            "rgb" => "0,0,0",
            "alpha" => 0.45,
            "x" => 0,
            "y" => -25,
            "blur" => 20,
            "spread" => -20,
        ],
        [
            "rgb" => "0,0,0",
            "alpha" => 0.45,
            "x" => 0,
            "y" => 25,
            "blur" => 20,
            "spread" => -20,
        ],
        [
            "rgb" => "0,0,0",
            "alpha" => 0.45,
            "x" => -25,
            "y" => 0,
            "blur" => 20,
            "spread" => -20,
        ],
    ],
    [
        [
            "rgb" => "0,0,0",
            "alpha" => 0.45,
            "x" => 0,
            "y" => -25,
            "blur" => 20,
            "spread" => -20,
        ],
        [
            "rgb" => "0,0,0",
            "alpha" => 0.45,
            "x" => 25,
            "y" => 0,
            "blur" => 20,
            "spread" => -20,
        ],
        [
            "rgb" => "0,0,0",
            "alpha" => 0.45,
            "x" => 0,
            "y" => 25,
            "blur" => 20,
            "spread" => -20,
        ],
        [
            "rgb" => "0,0,0",
            "alpha" => 0.45,
            "x" => -25,
            "y" => 0,
            "blur" => 20,
            "spread" => -20,
        ],
    ],
    [
        [
            "rgb" => "0,0,0",
            "alpha" => 0.12,
            "x" => 0,
            "y" => 1,
            "blur" => 3,
            "spread" => 0,
        ],
        [
            "rgb" => "0,0,0",
            "alpha" => 0.24,
            "x" => 0,
            "y" => 1,
            "blur" => 2,
            "spread" => 0,
        ],
    ],
    [
        [
            "rgb" => "0,0,0",
            "alpha" => 0.16,
            "x" => 0,
            "y" => 3,
            "blur" => 6,
            "spread" => 0,
        ],
        [
            "rgb" => "0,0,0",
            "alpha" => 0.23,
            "x" => 0,
            "y" => 3,
            "blur" => 6,
            "spread" => 0,
        ],
    ],
    [
        [
            "rgb" => "0,0,0",
            "alpha" => 0.19,
            "x" => 0,
            "y" => 10,
            "blur" => 20,
            "spread" => 0,
        ],
        [
            "rgb" => "0,0,0",
            "alpha" => 0.23,
            "x" => 0,
            "y" => 6,
            "blur" => 6,
            "spread" => 0,
        ],
    ],
    [
        [
            "rgb" => "0,0,0",
            "alpha" => 0.25,
            "x" => 0,
            "y" => 14,
            "blur" => 28,
            "spread" => 0,
        ],
        [
            "rgb" => "0,0,0",
            "alpha" => 0.22,
            "x" => 0,
            "y" => 10,
            "blur" => 10,
            "spread" => 0,
        ],
    ],
    [
        [
            "rgb" => "0,0,0",
            "alpha" => 0.3,
            "x" => 0,
            "y" => 19,
            "blur" => 38,
            "spread" => 0,
        ],
        [
            "rgb" => "0,0,0",
            "alpha" => 0.22,
            "x" => 0,
            "y" => 15,
            "blur" => 12,
            "spread" => 0,
        ],
    ],
    [
        [
            "rgb" => "0,0,0",
            "alpha" => 0.4,
            "x" => 0,
            "y" => 29,
            "blur" => 52,
            "spread" => 0,
        ],
        [
            "rgb" => "0,0,0",
            "alpha" => 0.2,
            "x" => 0,
            "y" => 25,
            "blur" => 16,
            "spread" => 0,
        ],
    ],
    [
        [
            "rgb" => "0,0,0",
            "alpha" => 0.5,
            "x" => 0,
            "y" => 45,
            "blur" => 65,
            "spread" => 0,
        ],
        [
            "rgb" => "0,0,0",
            "alpha" => 0.16,
            "x" => 0,
            "y" => 35,
            "blur" => 22,
            "spread" => 0,
        ],
    ],
    [
        [
            "rgb" => "0,0,0",
            "alpha" => 0.6,
            "x" => 0,
            "y" => 60,
            "blur" => 80,
            "spread" => 0,
        ],
        [
            "rgb" => "0,0,0",
            "alpha" => 0.14,
            "x" => 0,
            "y" => 45,
            "blur" => 26,
            "spread" => 0,
        ],
    ],
    [
        [
            "rgb" => "0,0,0",
            "alpha" => 0.12,
            "x" => 0,
            "y" => 1,
            "blur" => 1,
            "spread" => 0,
        ],
        [
            "rgb" => "0,0,0",
            "alpha" => 0.12,
            "x" => 0,
            "y" => 2,
            "blur" => 2,
            "spread" => 0,
        ],
    ],
    [
        [
            "rgb" => "0,0,0",
            "alpha" => 0.12,
            "x" => 0,
            "y" => 1,
            "blur" => 1,
            "spread" => 0,
        ],
        [
            "rgb" => "0,0,0",
            "alpha" => 0.12,
            "x" => 0,
            "y" => 2,
            "blur" => 2,
            "spread" => 0,
        ],
        [
            "rgb" => "0,0,0",
            "alpha" => 0.12,
            "x" => 0,
            "y" => 4,
            "blur" => 4,
            "spread" => 0,
        ],
        [
            "rgb" => "0,0,0",
            "alpha" => 0.12,
            "x" => 0,
            "y" => 8,
            "blur" => 8,
            "spread" => 0,
        ],
        [
            "rgb" => "0,0,0",
            "alpha" => 0.12,
            "x" => 0,
            "y" => 16,
            "blur" => 16,
            "spread" => 0,
        ],
    ],
    [
        [
            "rgb" => "0,0,0",
            "alpha" => 0.15,
            "x" => 0,
            "y" => 1,
            "blur" => 1,
            "spread" => 0,
        ],
        [
            "rgb" => "0,0,0",
            "alpha" => 0.15,
            "x" => 0,
            "y" => 2,
            "blur" => 2,
            "spread" => 0,
        ],
        [
            "rgb" => "0,0,0",
            "alpha" => 0.15,
            "x" => 0,
            "y" => 4,
            "blur" => 4,
            "spread" => 0,
        ],
        [
            "rgb" => "0,0,0",
            "alpha" => 0.15,
            "x" => 0,
            "y" => 8,
            "blur" => 8,
            "spread" => 0,
        ],
    ],
    [
        [
            "rgb" => "0,0,0",
            "alpha" => 0.11,
            "x" => 0,
            "y" => 1,
            "blur" => 1,
            "spread" => 0,
        ],
        [
            "rgb" => "0,0,0",
            "alpha" => 0.11,
            "x" => 0,
            "y" => 2,
            "blur" => 2,
            "spread" => 0,
        ],
        [
            "rgb" => "0,0,0",
            "alpha" => 0.11,
            "x" => 0,
            "y" => 4,
            "blur" => 4,
            "spread" => 0,
        ],
        [
            "rgb" => "0,0,0",
            "alpha" => 0.11,
            "x" => 0,
            "y" => 8,
            "blur" => 8,
            "spread" => 0,
        ],
        [
            "rgb" => "0,0,0",
            "alpha" => 0.11,
            "x" => 0,
            "y" => 16,
            "blur" => 16,
            "spread" => 0,
        ],
        [
            "rgb" => "0,0,0",
            "alpha" => 0.11,
            "x" => 0,
            "y" => 32,
            "blur" => 32,
            "spread" => 0,
        ],
    ],
    [
        [
            "rgb" => "0,0,0",
            "alpha" => 0.08,
            "x" => 0,
            "y" => 1,
            "blur" => 1,
            "spread" => 0,
        ],
        [
            "rgb" => "0,0,0",
            "alpha" => 0.12,
            "x" => 0,
            "y" => 2,
            "blur" => 2,
            "spread" => 0,
        ],
        [
            "rgb" => "0,0,0",
            "alpha" => 0.16,
            "x" => 0,
            "y" => 4,
            "blur" => 4,
            "spread" => 0,
        ],
        [
            "rgb" => "0,0,0",
            "alpha" => 0.2,
            "x" => 0,
            "y" => 8,
            "blur" => 8,
            "spread" => 0,
        ],
    ],
    [
        [
            "rgb" => "0,0,0",
            "alpha" => 0.07,
            "x" => 0,
            "y" => 1,
            "blur" => 2,
            "spread" => 0,
        ],
        [
            "rgb" => "0,0,0",
            "alpha" => 0.07,
            "x" => 0,
            "y" => 2,
            "blur" => 4,
            "spread" => 0,
        ],
        [
            "rgb" => "0,0,0",
            "alpha" => 0.07,
            "x" => 0,
            "y" => 4,
            "blur" => 8,
            "spread" => 0,
        ],
        [
            "rgb" => "0,0,0",
            "alpha" => 0.07,
            "x" => 0,
            "y" => 8,
            "blur" => 16,
            "spread" => 0,
        ],
        [
            "rgb" => "0,0,0",
            "alpha" => 0.07,
            "x" => 0,
            "y" => 16,
            "blur" => 32,
            "spread" => 0,
        ],
        [
            "rgb" => "0,0,0",
            "alpha" => 0.07,
            "x" => 0,
            "y" => 32,
            "blur" => 64,
            "spread" => 0,
        ],
    ],
    [
        [
            "rgb" => "0,0,0",
            "alpha" => 0.11,
            "x" => 0,
            "y" => 1,
            "blur" => 1,
            "spread" => 0,
        ],
        [
            "rgb" => "0,0,0",
            "alpha" => 0.11,
            "x" => 0,
            "y" => 2,
            "blur" => 2,
            "spread" => 0,
        ],
        [
            "rgb" => "0,0,0",
            "alpha" => 0.11,
            "x" => 0,
            "y" => 4,
            "blur" => 4,
            "spread" => 0,
        ],
        [
            "rgb" => "0,0,0",
            "alpha" => 0.11,
            "x" => 0,
            "y" => 6,
            "blur" => 8,
            "spread" => 0,
        ],
        [
            "rgb" => "0,0,0",
            "alpha" => 0.11,
            "x" => 0,
            "y" => 8,
            "blur" => 16,
            "spread" => 0,
        ],
    ],
    [
        [
            "rgb" => "0,0,0",
            "alpha" => 0.09,
            "x" => 0,
            "y" => 2,
            "blur" => 1,
            "spread" => 0,
        ],
        [
            "rgb" => "0,0,0",
            "alpha" => 0.09,
            "x" => 0,
            "y" => 4,
            "blur" => 2,
            "spread" => 0,
        ],
        [
            "rgb" => "0,0,0",
            "alpha" => 0.09,
            "x" => 0,
            "y" => 8,
            "blur" => 4,
            "spread" => 0,
        ],
        [
            "rgb" => "0,0,0",
            "alpha" => 0.09,
            "x" => 0,
            "y" => 16,
            "blur" => 8,
            "spread" => 0,
        ],
        [
            "rgb" => "0,0,0",
            "alpha" => 0.09,
            "x" => 0,
            "y" => 32,
            "blur" => 16,
            "spread" => 0,
        ],
    ],
    [
        [
            "rgb" => "0,0,0",
            "alpha" => 0.05,
            "x" => 0,
            "y" => 1,
            "blur" => 2,
            "spread" => 0,
        ],
    ],
    [
        [
            "rgb" => "0,0,0",
            "alpha" => 0.1,
            "x" => 0,
            "y" => 1,
            "blur" => 3,
            "spread" => 0,
        ],
        [
            "rgb" => "0,0,0",
            "alpha" => 0.1,
            "x" => 0,
            "y" => 1,
            "blur" => 2,
            "spread" => -1,
        ],
    ],
    [
        [
            "rgb" => "0,0,0",
            "alpha" => 0.1,
            "x" => 0,
            "y" => 10,
            "blur" => 15,
            "spread" => -3,
        ],
        [
            "rgb" => "0,0,0",
            "alpha" => 0.1,
            "x" => 0,
            "y" => 4,
            "blur" => 6,
            "spread" => -4,
        ],
    ],
    [
        [
            "rgb" => "0,0,0",
            "alpha" => 0.1,
            "x" => 0,
            "y" => 20,
            "blur" => 25,
            "spread" => -5,
        ],
        [
            "rgb" => "0,0,0",
            "alpha" => 0.1,
            "x" => 0,
            "y" => 8,
            "blur" => 10,
            "spread" => -6,
        ],
    ],
    [
        [
            "rgb" => "0,0,0",
            "alpha" => 0.25,
            "x" => 0,
            "y" => 25,
            "blur" => 50,
            "spread" => -12,
        ],
    ],
    [
        [
            "rgb" => "0,0,0",
            "alpha" => 0.13,
            "x" => 0,
            "y" => 2,
            "blur" => 4,
            "spread" => 0,
        ],
        [
            "rgb" => "0,0,0",
            "alpha" => 0.11,
            "x" => 0,
            "y" => 1,
            "blur" => 1,
            "spread" => 0,
        ],
    ],
    [
        [
            "rgb" => "0,0,0",
            "alpha" => 0.13,
            "x" => 0,
            "y" => 3,
            "blur" => 7,
            "spread" => 0,
        ],
        [
            "rgb" => "0,0,0",
            "alpha" => 0.11,
            "x" => 0,
            "y" => 1,
            "blur" => 2,
            "spread" => 0,
        ],
    ],
    [
        [
            "rgb" => "0,0,0",
            "alpha" => 0.13,
            "x" => 0,
            "y" => 7,
            "blur" => 15,
            "spread" => 0,
        ],
        [
            "rgb" => "0,0,0",
            "alpha" => 0.11,
            "x" => 0,
            "y" => 1,
            "blur" => 4,
            "spread" => 0,
        ],
    ],
    [
        [
            "rgb" => "0,0,0",
            "alpha" => 0.22,
            "x" => 0,
            "y" => 26,
            "blur" => 58,
            "spread" => 0,
        ],
        [
            "rgb" => "0,0,0",
            "alpha" => 0.18,
            "x" => 0,
            "y" => 5,
            "blur" => 14,
            "spread" => 0,
        ],
    ],
    [
        [
            "rgb" => "0,0,0",
            "alpha" => 0.075,
            "x" => 0,
            "y" => 2,
            "blur" => 4,
            "spread" => 0,
        ],
    ],
    [
        [
            "rgb" => "0,0,0",
            "alpha" => 0.15,
            "x" => 0,
            "y" => 8,
            "blur" => 16,
            "spread" => 0,
        ],
    ],
    [
        [
            "rgb" => "0,0,0",
            "alpha" => 0.176,
            "x" => 0,
            "y" => 16,
            "blur" => 48,
            "spread" => 0,
        ],
    ],
    [
        [
            "rgb" => "27,31,36",
            "alpha" => 0.04,
            "x" => 0,
            "y" => 1,
            "blur" => 0,
            "spread" => 0,
        ],
    ],
    [
        [
            "rgb" => "140,149,159",
            "alpha" => 0.15,
            "x" => 0,
            "y" => 3,
            "blur" => 6,
            "spread" => 0,
        ],
    ],
    [
        [
            "rgb" => "140,149,159",
            "alpha" => 0.2,
            "x" => 0,
            "y" => 8,
            "blur" => 24,
            "spread" => 0,
        ],
    ],
    [
        [
            "rgb" => "140,149,159",
            "alpha" => 0.3,
            "x" => 0,
            "y" => 12,
            "blur" => 28,
            "spread" => 0,
        ],
    ],
    [
        [
            "rgb" => "150,170,180",
            "alpha" => 0.5,
            "x" => 0,
            "y" => 7,
            "blur" => 30,
            "spread" => -10,
        ],
    ],
    [
        [
            "rgb" => "0,0,0",
            "alpha" => 0.15,
            "x" => 2,
            "y" => 2,
            "blur" => 2,
            "spread" => 0,
        ],
    ],
    [
        [
            "rgb" => "0,0,0",
            "alpha" => 0.2,
            "x" => 0,
            "y" => 2,
            "blur" => 4,
            "spread" => 0,
        ],
    ],
    [
        [
            "rgb" => "0,0,0",
            "alpha" => 0.2,
            "x" => 0,
            "y" => 20,
            "blur" => 50,
            "spread" => -10,
        ],
    ],
    [
        [
            "rgb" => "0,0,0",
            "alpha" => 0.15,
            "x" => 0,
            "y" => 80,
            "blur" => 50,
            "spread" => -30,
        ],
    ],
    [
        [
            "rgb" => "0,0,0",
            "alpha" => 0.15,
            "x" => 0,
            "y" => 25,
            "blur" => 80,
            "spread" => 0,
        ],
    ],
    [
        [
            "rgb" => "0,0,0",
            "alpha" => 0.5,
            "x" => 0,
            "y" => 25,
            "blur" => 80,
            "spread" => 0,
        ],
    ],
    [
        [
            "rgb" => "0,0,0",
            "alpha" => 0.2,
            "x" => 0,
            "y" => 15,
            "blur" => 20,
            "spread" => 0,
        ],
    ],
    [
        [
            "rgb" => "0,0,0",
            "alpha" => 0.05,
            "x" => 0,
            "y" => 0,
            "blur" => 0,
            "spread" => 5,
        ],
    ],
    [
        [
            "rgb" => "0,0,0",
            "alpha" => 1,
            "x" => 8,
            "y" => 8,
            "blur" => 0,
            "spread" => 0,
        ],
    ],
    [
        [
            "rgb" => "33,33,33",
            "alpha" => 1,
            "x" => -10,
            "y" => 10,
            "blur" => 0,
            "spread" => 0,
        ],
        [
            "rgb" => "33,33,33",
            "alpha" => 0.7,
            "x" => -20,
            "y" => 20,
            "blur" => 0,
            "spread" => 0,
        ],
        [
            "rgb" => "33,33,33",
            "alpha" => 0.4,
            "x" => -30,
            "y" => 30,
            "blur" => 0,
            "spread" => 0,
        ],
        [
            "rgb" => "33,33,33",
            "alpha" => 0.1,
            "x" => -40,
            "y" => 40,
            "blur" => 0,
            "spread" => 0,
        ],
    ],
    [
        [
            "rgb" => "0,0,0",
            "alpha" => 0.45,
            "x" => 0,
            "y" => 0,
            "blur" => 40,
            "spread" => 0,
        ],
    ],
    [
        [
            "rgb" => "221,221,221",
            "alpha" => 1,
            "x" => 0,
            "y" => 10,
            "blur" => 1,
            "spread" => 0,
        ],
        [
            "rgb" => "204,204,204",
            "alpha" => 1,
            "x" => 0,
            "y" => 10,
            "blur" => 20,
            "spread" => 0,
        ],
    ],
    [
        [
            "rgb" => "9,30,66",
            "alpha" => 0.25,
            "x" => 0,
            "y" => 1,
            "blur" => 1,
            "spread" => 0,
        ],
        [
            "rgb" => "9,30,66",
            "alpha" => 0.13,
            "x" => 0,
            "y" => 0,
            "blur" => 1,
            "spread" => 1,
        ],
    ],
    [
        [
            "rgb" => "0,0,0",
            "alpha" => 0.16,
            "x" => 0,
            "y" => 1,
            "blur" => 4,
            "spread" => 0,
        ],
        [
            "rgb" => "51,51,51",
            "alpha" => 1,
            "x" => 0,
            "y" => 0,
            "blur" => 0,
            "spread" => 3,
        ],
    ],
    [
        [
            "rgb" => "0,0,0",
            "alpha" => 0.02,
            "x" => 0,
            "y" => 1,
            "blur" => 3,
            "spread" => 0,
        ],
        [
            "rgb" => "27,31,35",
            "alpha" => 0.15,
            "x" => 0,
            "y" => 0,
            "blur" => 0,
            "spread" => 1,
        ],
    ],
    [
        [
            "rgb" => "9,30,66",
            "alpha" => 0.25,
            "x" => 0,
            "y" => 4,
            "blur" => 8,
            "spread" => -2,
        ],
        [
            "rgb" => "9,30,66",
            "alpha" => 0.08,
            "x" => 0,
            "y" => 0,
            "blur" => 0,
            "spread" => 1,
        ],
    ],
    [
        [
            "rgb" => "0,0,0",
            "alpha" => 0.22,
            "x" => 0,
            "y" => 1,
            "blur" => 0,
            "spread" => 0,
        ],
        [
            "rgb" => "0,0,0",
            "alpha" => 0.22,
            "x" => 1,
            "y" => 0,
            "blur" => 0,
            "spread" => 0,
        ],
        [
            "rgb" => "0,0,0",
            "alpha" => 0.22,
            "x" => 1,
            "y" => 2,
            "blur" => 0,
            "spread" => 0,
        ],
        [
            "rgb" => "0,0,0",
            "alpha" => 0.22,
            "x" => 2,
            "y" => 1,
            "blur" => 0,
            "spread" => 0,
        ],
        [
            "rgb" => "0,0,0",
            "alpha" => 0.22,
            "x" => 2,
            "y" => 3,
            "blur" => 0,
            "spread" => 0,
        ],
        [
            "rgb" => "0,0,0",
            "alpha" => 0.22,
            "x" => 3,
            "y" => 2,
            "blur" => 0,
            "spread" => 0,
        ],
        [
            "rgb" => "0,0,0",
            "alpha" => 0.22,
            "x" => 3,
            "y" => 4,
            "blur" => 0,
            "spread" => 0,
        ],
        [
            "rgb" => "0,0,0",
            "alpha" => 0.22,
            "x" => 4,
            "y" => 3,
            "blur" => 0,
            "spread" => 0,
        ],
        [
            "rgb" => "0,0,0",
            "alpha" => 0.22,
            "x" => 4,
            "y" => 5,
            "blur" => 0,
            "spread" => 0,
        ],
        [
            "rgb" => "0,0,0",
            "alpha" => 0.22,
            "x" => 5,
            "y" => 4,
            "blur" => 0,
            "spread" => 0,
        ],
        [
            "rgb" => "0,0,0",
            "alpha" => 0.22,
            "x" => 5,
            "y" => 6,
            "blur" => 0,
            "spread" => 0,
        ],
        [
            "rgb" => "0,0,0",
            "alpha" => 0.22,
            "x" => 6,
            "y" => 5,
            "blur" => 0,
            "spread" => 0,
        ],
        [
            "rgb" => "0,0,0",
            "alpha" => 0.22,
            "x" => 6,
            "y" => 7,
            "blur" => 0,
            "spread" => 0,
        ],
        [
            "rgb" => "0,0,0",
            "alpha" => 0.22,
            "x" => 7,
            "y" => 6,
            "blur" => 0,
            "spread" => 0,
        ],
        [
            "rgb" => "0,0,0",
            "alpha" => 0.22,
            "x" => 7,
            "y" => 8,
            "blur" => 0,
            "spread" => 0,
        ],
        [
            "rgb" => "0,0,0",
            "alpha" => 0.22,
            "x" => 8,
            "y" => 7,
            "blur" => 0,
            "spread" => 0,
        ],
    ],
    [
        [
            "rgb" => "0,0,0",
            "alpha" => 0.24,
            "x" => 0,
            "y" => 3,
            "blur" => 8,
            "spread" => 0,
        ],
    ],
    [
        [
            "rgb" => "13,38,76",
            "alpha" => 0.19,
            "x" => 0,
            "y" => 9,
            "blur" => 20,
            "spread" => 0,
        ],
    ],
    [
        [
            "rgb" => "0,0,0",
            "alpha" => 0.04,
            "x" => 0,
            "y" => 3,
            "blur" => 5,
            "spread" => 0,
        ],
    ],
    [
        [
            "rgb" => "148,0,211",
            "alpha" => 1,
            "x" => 0,
            "y" => 0,
            "blur" => 0,
            "spread" => 3,
        ],
        [
            "rgb" => "75,0,130",
            "alpha" => 1,
            "x" => 0,
            "y" => 0,
            "blur" => 0,
            "spread" => 6,
        ],
        [
            "rgb" => "0,0,255",
            "alpha" => 1,
            "x" => 0,
            "y" => 0,
            "blur" => 0,
            "spread" => 9,
        ],
        [
            "rgb" => "0,255,0",
            "alpha" => 1,
            "x" => 0,
            "y" => 0,
            "blur" => 0,
            "spread" => 12,
        ],
        [
            "rgb" => "255,255,0",
            "alpha" => 1,
            "x" => 0,
            "y" => 0,
            "blur" => 0,
            "spread" => 15,
        ],
        [
            "rgb" => "255,127,0",
            "alpha" => 1,
            "x" => 0,
            "y" => 0,
            "blur" => 0,
            "spread" => 18,
        ],
        [
            "rgb" => "255,0,0",
            "alpha" => 1,
            "x" => 0,
            "y" => 0,
            "blur" => 0,
            "spread" => 21,
        ],
    ],
    [
        [
            "rgb" => "148,0,211",
            "alpha" => 1,
            "x" => 0,
            "y" => 0,
            "blur" => 0,
            "spread" => 2,
        ],
        [
            "rgb" => "255,255,255",
            "alpha" => 1,
            "x" => 15,
            "y" => -15,
            "blur" => 0,
            "spread" => -2,
        ],
        [
            "rgb" => "75,0,130",
            "alpha" => 1,
            "x" => 15,
            "y" => -15,
            "blur" => 0,
            "spread" => 0,
        ],
        [
            "rgb" => "255,255,255",
            "alpha" => 1,
            "x" => 30,
            "y" => -30,
            "blur" => 0,
            "spread" => -2,
        ],
        [
            "rgb" => "0,0,255",
            "alpha" => 1,
            "x" => 30,
            "y" => -30,
            "blur" => 0,
            "spread" => 0,
        ],
        [
            "rgb" => "255,255,255",
            "alpha" => 1,
            "x" => 45,
            "y" => -45,
            "blur" => 0,
            "spread" => -2,
        ],
        [
            "rgb" => "0,255,0",
            "alpha" => 1,
            "x" => 45,
            "y" => -45,
            "blur" => 0,
            "spread" => 0,
        ],
        [
            "rgb" => "255,255,255",
            "alpha" => 1,
            "x" => 60,
            "y" => -60,
            "blur" => 0,
            "spread" => -2,
        ],
        [
            "rgb" => "255,255,0",
            "alpha" => 1,
            "x" => 60,
            "y" => -60,
            "blur" => 0,
            "spread" => 0,
        ],
        [
            "rgb" => "255,255,255",
            "alpha" => 1,
            "x" => 75,
            "y" => -75,
            "blur" => 0,
            "spread" => -2,
        ],
        [
            "rgb" => "255,127,0",
            "alpha" => 1,
            "x" => 75,
            "y" => -75,
            "blur" => 0,
            "spread" => 0,
        ],
        [
            "rgb" => "255,255,255",
            "alpha" => 1,
            "x" => 90,
            "y" => -90,
            "blur" => 0,
            "spread" => -2,
        ],
        [
            "rgb" => "255,0,0",
            "alpha" => 1,
            "x" => 90,
            "y" => -90,
            "blur" => 0,
            "spread" => 0,
        ],
    ],
    [
        [
            "rgb" => "65,117,5",
            "alpha" => 1,
            "x" => 0,
            "y" => 5,
            "blur" => 0,
            "spread" => 0,
        ],
        [
            "rgb" => "65,117,5",
            "alpha" => 0.6,
            "x" => 0,
            "y" => 10,
            "blur" => 0,
            "spread" => 0,
        ],
        [
            "rgb" => "65,117,5",
            "alpha" => 0.4,
            "x" => 0,
            "y" => 15,
            "blur" => 0,
            "spread" => 0,
        ],
        [
            "rgb" => "65,117,5",
            "alpha" => 0.2,
            "x" => 0,
            "y" => 20,
            "blur" => 0,
            "spread" => 0,
        ],
        [
            "rgb" => "65,117,5",
            "alpha" => 0.1,
            "x" => 0,
            "y" => 25,
            "blur" => 0,
            "spread" => 0,
        ],
    ],
    [
        [
            "rgb" => "65,117,5",
            "alpha" => 1,
            "x" => 5,
            "y" => 5,
            "blur" => 0,
            "spread" => 0,
        ],
        [
            "rgb" => "65,117,5",
            "alpha" => 0.6,
            "x" => 10,
            "y" => 10,
            "blur" => 0,
            "spread" => 0,
        ],
        [
            "rgb" => "65,117,5",
            "alpha" => 0.4,
            "x" => 15,
            "y" => 15,
            "blur" => 0,
            "spread" => 0,
        ],
        [
            "rgb" => "65,117,5",
            "alpha" => 0.2,
            "x" => 20,
            "y" => 20,
            "blur" => 0,
            "spread" => 0,
        ],
        [
            "rgb" => "65,117,5",
            "alpha" => 0.1,
            "x" => 25,
            "y" => 25,
            "blur" => 0,
            "spread" => 0,
        ],
    ],
    [
        [
            "rgb" => "65,117,5",
            "alpha" => 1,
            "x" => -5,
            "y" => 5,
            "blur" => 0,
            "spread" => 0,
        ],
        [
            "rgb" => "65,117,5",
            "alpha" => 0.6,
            "x" => -10,
            "y" => 10,
            "blur" => 0,
            "spread" => 0,
        ],
        [
            "rgb" => "65,117,5",
            "alpha" => 0.4,
            "x" => -15,
            "y" => 15,
            "blur" => 0,
            "spread" => 0,
        ],
        [
            "rgb" => "65,117,5",
            "alpha" => 0.2,
            "x" => -20,
            "y" => 20,
            "blur" => 0,
            "spread" => 0,
        ],
        [
            "rgb" => "65,117,5",
            "alpha" => 0.1,
            "x" => -25,
            "y" => 25,
            "blur" => 0,
            "spread" => 0,
        ],
    ],
    [
        [
            "rgb" => "218,102,123",
            "alpha" => 1,
            "x" => 0,
            "y" => 0,
            "blur" => 0,
            "spread" => 2,
        ],
        [
            "rgb" => "218,102,123",
            "alpha" => 1,
            "x" => 8,
            "y" => 8,
            "blur" => 0,
            "spread" => 0,
        ],
    ],
    [
        [
            "rgb" => "255,149,5",
            "alpha" => 0.1,
            "x" => 0,
            "y" => 9,
            "blur" => 30,
            "spread" => 0,
        ],
    ],
    [
        [
            "rgb" => "255,149,5",
            "alpha" => 0.3,
            "x" => 0,
            "y" => 9,
            "blur" => 30,
            "spread" => 0,
        ],
    ],
];

$shadows = apply_filters( 'at/box_shadow/presets', $objs );
$examples = AT__Helpers::format_shadow_strings($shadows);

?>
<div id="<?php echo esc_attr($overlay_id);?>" class="brxc-overlay__wrapper" style="opacity:0" data-input-target="" onmousedown="ADMINBRXC.closeModal(event, this, '#<?php echo esc_attr($overlay_id);?>');" >
    <div class="brxc-overlay__inner brxc-large">
        <div class="brxc-overlay__close-btn" onClick="ADMINBRXC.closeModal(event, event.target, '#<?php echo esc_attr($overlay_id);?>')">
            <i class="bricks-svg ti-close"></i>
        </div>
        <div class="brxc-overlay__inner-wrapper">
            <div class="brxc-overlay__header">
                <h3 class="brxc-overlay__header-title"><?php echo esc_attr($modal_heading_title);?></h3>
            </div>
            <div class="brxc-overlay__error-message-wrapper"></div>
            <div class="brxc-overlay__container no-radius">
                <div class="brxc-overlay__panel-switcher-wrapper">
                    <input type="radio" id="<?php echo esc_attr($prefix);?>-generator" name="<?php echo esc_attr($prefix);?>-switch" class="brxc-input__radio" data-transform="0" onClick="ADMINBRXC.bsStates.activeWindow = 'generator';ADMINBRXC.movePanel(document.querySelector('#<?php echo esc_attr($overlay_id);?> .brxc-overlay__pannels-wrapper'),this.dataset.transform);" checked>
                    <label for="<?php echo esc_attr($prefix);?>-generator" class="brxc-input__label">Box-Shadow Generator</label>
                    <input type="radio" id="<?php echo esc_attr($prefix);?>-presets" name="<?php echo esc_attr($prefix);?>-switch" class="brxc-input__radio" data-transform="calc(-100% - 80px)" onClick="ADMINBRXC.bsStates.activeWindow = 'presets';ADMINBRXC.movePanel(document.querySelector('#<?php echo esc_attr($overlay_id);?> .brxc-overlay__pannels-wrapper'),this.dataset.transform);">
                    <label for="<?php echo esc_attr($prefix);?>-presets" class="brxc-input__label">Presets</label>
                </div>
                <div class="brxc-overlay__pannels-wrapper">
                    <div class="brxc-overlay__pannel brxc-overlay__pannel-1" style="padding: 0px;">
                        <div id="boxShadowUI__container">
                            <div id="boxShadowUI__main">
                                <div id="boxShadowUI__box"></div>
                            </div>
                            <div id="boxShadowUI__global">
                                <div id="boxSettings"></div>
                                <div id="boxShadowSettings"></div>
                                <div id="boxShadowUI__child"></div>
                            </div>
                        </div>
                    </div>
                    <div class="brxc-overlay__pannel brxc-overlay__pannel-2" style="padding: 0px;">
                        <div class="boxShadowdUI__main-container">
                            <?php
                            $index = 1;
                            foreach($examples as $example){
                                ?>
                                    <div class="brxc-box-item">
                                        <div class="brxc-box" style="box-shadow: <?php echo $example?>;"></div>
                                        <div class="brxc-box-btn-wrapper">
                                            <a class="" data-obj='<?php echo json_encode($shadows[$index - 1]);?>' onclick="ADMINBRXC.bsImportLayersFromPresets(this)">Import Layers</a>
                                            <a class="" data-value='<?php echo $example;?>' onClick="ADMINBRXC.bsApplyPresets(this);ADMINBRXC.closeModal(event, this, '#<?php echo esc_attr($overlay_id);?>');">Quick Apply</a>
                                        </div>
                                    </div>
                                <?php
                                $index++;
                            }
0                        ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="brxc-overlay__footer">
                <div class="brxc-overlay__footer-wrapper">
                    <a class="brxc-overlay__action-btn danger" onclick="ADMINBRXC.bsRemoveSettings();ADMINBRXC.closeModal(event, this, '#<?php echo esc_attr($overlay_id);?>');"><span>Remove Settings</span></a>
                    <a class="brxc-overlay__action-btn primary" style="margin-left: auto;" onClick="ADMINBRXC.bsApply();ADMINBRXC.closeModal(event, this, '#<?php echo esc_attr($overlay_id);?>');"><span>Apply Box-shadow</span></a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php }

$output = ob_get_clean();
$output = preg_replace('/>\s+</s', '><', $output);
$brxc_modals['box_shadow_generator'] = [
    "id" => $overlay_id,
    "html" => trim($output)
];