<?php
namespace Advanced_Themer_Bricks;
if (!defined('ABSPATH')) { die();
}

/*--------------------------------------
Variables
--------------------------------------*/

// ID & Classes
$overlay_id = 'brxcCustomComponentsOverlay';
$prefix = 'brxcCustomComponents';
$prefix_id = 'brxc-custom-components';
// Heading
$modal_heading_title = 'Nested Elements Library';
//for loops
$i = 0;

if (!AT__Helpers::is_builder_tweaks_category_activated()){
    $theme_settings = \get_admin_url() . 'admin.php?page=bricks-advanced-themer';
    $error_title = "Feature not enabled";
    $error_desc = "It seems like this feature hasn't been enabled inside the theme settings. Click on the button below and make sure that the Extras settings are enabled inside <strong class='accent'>Global Settings > General > Customize the functions included in Advanced Themer</strong>.";
    include \BRICKS_ADVANCED_THEMER_PATH . '/inc/builderPanels/_default_error.php';
} else {
    $elements = [
        [
            'id' => 'at_hero',
            'label' => 'Hero',
            'icon' => 'ti-id-badge',
            'category' => 'default',
            'default' => true,
            'globalClasses' => [],
            'elements' => [
                [
                    'id' => 'rcoswe',
                    'name' => 'container',
                    'parent' => 0,
                    'children' => ['zixxvh', 'xsieis'],
                    'settings' => [],
                    'label' => 'Hero'
                ],
                [
                    'id' => 'zixxvh',
                    'name' => 'block',
                    'parent' => 'rcoswe',
                    'children' => ['nftjqm', 'iraahi', 'qgtqts', 'cnpfmx'],
                    'settings' => [],
                    'label' => 'Content'
                ],
                [
                    'id' => 'nftjqm',
                    'name' => 'heading',
                    'parent' => 'zixxvh',
                    'children' => [],
                    'settings' => ['text' => 'I am a heading', 'tag' => 'h1']
                ],
                [
                    'id' => 'iraahi',
                    'name' => 'text-basic',
                    'parent' => 'zixxvh',
                    'children' => [],
                    'settings' => ['text' => 'PRETITLE', 'tag' => 'span', '_order' => '-1'],
                    'label' => 'Pretitle'
                ],
                [
                    'id' => 'qgtqts',
                    'name' => 'text-basic',
                    'parent' => 'zixxvh',
                    'children' => [],
                    'settings' => [
                        'tag' => 'p',
                        'text' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero uctus non, massa. Sed cursus ante dapibus diam. Sed nisi. Nulla quis sem at nibh elementum imperdiet.'
                    ],
                    'label' => 'Description'
                ],
                [
                    'id' => 'cnpfmx',
                    'name' => 'button',
                    'parent' => 'zixxvh',
                    'children' => [],
                    'settings' => [
                        'text' => 'Click me',
                        'style' => 'primary',
                        'tag' => 'a'
                    ],
                    'label' => 'CTA'
                ],
                [
                    'id' => 'xsieis',
                    'name' => 'div',
                    'parent' => 'rcoswe',
                    'children' => ['nxedzr'],
                    'settings' => [],
                    'label' => 'Image Wrapper'
                ],
                [
                    'id' => 'nxedzr',
                    'name' => 'image',
                    'parent' => 'xsieis',
                    'children' => [],
                    'settings' => [
                        'image' => [
                            'url' => '/wp-content/plugins/bricks-advanced-themer/assets/img/placeholder-image.png',
                            'external' => true,
                            'filename' => 'placeholder-image.png'
                        ]
                    ]
                ]
            ]
        ],
        [
            "id" => "at_card",
            "label" => "Card",
            "icon" => "ti-image",
            "category" => "default",
            "default" => true,
            "globalClasses" => [],
            "elements" => [
                [
                    "id" => "ichhns",
                    "name" => "block",
                    "parent" => 0,
                    "children" => ["ppzpaz", "defbdv"],
                    "settings" => [
                        "tag" => "li"
                    ],
                    "label" => "Card"
                ],
                [
                    "id" => "ppzpaz",
                    "name" => "div",
                    "parent" => "ichhns",
                    "children" => ["isalnn", "tbdfyl", "xgxowb"],
                    "settings" => [],
                    "label" => "Content Wrapper"
                ],
                [
                    "id" => "isalnn",
                    "name" => "heading",
                    "parent" => "ppzpaz",
                    "children" => [],
                    "settings" => [
                        "text" => "Card Title"
                    ],
                    "label" => "Title"
                ],
                [
                    "id" => "tbdfyl",
                    "name" => "text-basic",
                    "parent" => "ppzpaz",
                    "children" => [],
                    "settings" => [
                        "text" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero uctus non, massa. Sed cursus ante dapibus diam. Sed nisi.",
                        "tag" => "p"
                    ],
                    "label" => "Description"
                ],
                [
                    "id" => "xgxowb",
                    "name" => "button",
                    "parent" => "ppzpaz",
                    "children" => [],
                    "settings" => [
                        "text" => "Read more",
                        "tag" => "a"
                    ],
                    "label" => "CTA"
                ],
                [
                    "id" => "defbdv",
                    "name" => "div",
                    "parent" => "ichhns",
                    "children" => ["qyufpf"],
                    "settings" => [
                        "_order" => "-1"
                    ],
                    "label" => "Image Wrapper"
                ],
                [
                    "id" => "qyufpf",
                    "name" => "image",
                    "parent" => "defbdv",
                    "children" => [],
                    "settings" => [
                        "tag" => "figure",
                        "image" => [
                            "url" => "/wp-content/plugins/bricks-advanced-themer/assets/img/placeholder-image.png",
                            "external" => true,
                            "filename" => "placeholder-image.png"
                        ]
                    ]
                ]
            ]
        ],
        [
            "id" => "at_ordered_list",
            "label" => "Ordered List",
            "icon" => "ti-list-ol",
            "category" => "default",
            "default" => true,
            "globalClasses" => [],
            "elements" => [
                [
                    "id" => "xoitiu",
                    "name" => "div",
                    "parent" => 0,
                    "children" => ["mbovja", "xshmtq", "rssmfm"],
                    "settings" => [
                        "tag" => "ol"
                    ],
                    "label" => "List"
                ],
                [
                    "id" => "mbovja",
                    "name" => "div",
                    "parent" => "xoitiu",
                    "children" => ["qkvcqe"],
                    "settings" => [
                        "tag" => "li",
                        "label" => "Item"
                    ]
                ],
                [
                    "id" => "qkvcqe",
                    "name" => "text-basic",
                    "parent" => "mbovja",
                    "children" => [],
                    "settings" => [
                        "text" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit.",
                        "tag" => "span"
                    ],
                    "label" => "Text"
                ],
                [
                    "id" => "xshmtq",
                    "name" => "div",
                    "parent" => "xoitiu",
                    "children" => ["rjnoef"],
                    "settings" => [
                        "tag" => "li",
                        "label" => "Item"
                    ]
                ],
                [
                    "id" => "rjnoef",
                    "name" => "text-basic",
                    "parent" => "xshmtq",
                    "children" => [],
                    "settings" => [
                        "text" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit.",
                        "tag" => "span"
                    ],
                    "label" => "Text"
                ],
                [
                    "id" => "rssmfm",
                    "name" => "div",
                    "parent" => "xoitiu",
                    "children" => ["ncpxft"],
                    "settings" => [
                        "tag" => "li",
                        "label" => "Item"
                    ]
                ],
                [
                    "id" => "ncpxft",
                    "name" => "text-basic",
                    "parent" => "rssmfm",
                    "children" => [],
                    "settings" => [
                        "text" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit.",
                        "tag" => "span"
                    ],
                    "label" => "Text"
                ]
            ]
        ],
        [
            "id" => "at_icon_list",
            "label" => "Icon List",
            "icon" => "ti-list",
            "category" => "default",
            "default" => true,
            "globalClasses" => [],
            "elements" => [
                [
                    "id" => "xoitiu",
                    "name" => "div",
                    "parent" => 0,
                    "children" => ["mbovja", "xshmtq", "rssmfm"],
                    "settings" => [
                        "tag" => "ul",
                        "_padding" => ["left" => "0"],
                        "_cssCustom" => "#brxe-xoitiu {\n\tlist-style: none;\n}"
                    ],
                    "label" => "List"
                ],
                [
                    "id" => "mbovja",
                    "name" => "div",
                    "parent" => "xoitiu",
                    "children" => ["vzbkul", "qkvcqe"],
                    "settings" => [
                        "tag" => "li",
                        "_display" => "flex",
                        "_alignItems" => "center"
                    ],
                    "label" => "Item"
                ],
                [
                    "id" => "vzbkul",
                    "name" => "icon",
                    "parent" => "mbovja",
                    "children" => [],
                    "settings" => [
                        "icon" => [
                            "library" => "themify",
                            "icon" => "ti-check"
                        ]
                    ]
                ],
                [
                    "id" => "qkvcqe",
                    "name" => "text-basic",
                    "parent" => "mbovja",
                    "children" => [],
                    "settings" => [
                        "text" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit.",
                        "tag" => "span"
                    ],
                    "label" => "Text"
                ],
                [
                    "id" => "xshmtq",
                    "name" => "div",
                    "parent" => "xoitiu",
                    "children" => ["wzjwud", "rjnoef"],
                    "settings" => [
                        "tag" => "li",
                        "_display" => "flex",
                        "_alignItems" => "center"
                    ],
                    "label" => "Item"
                ],
                [
                    "id" => "wzjwud",
                    "name" => "icon",
                    "parent" => "xshmtq",
                    "children" => [],
                    "settings" => [
                        "icon" => [
                            "library" => "themify",
                            "icon" => "ti-check"
                        ]
                    ]
                ],
                [
                    "id" => "rjnoef",
                    "name" => "text-basic",
                    "parent" => "xshmtq",
                    "children" => [],
                    "settings" => [
                        "text" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit.",
                        "tag" => "span"
                    ],
                    "label" => "Text"
                ],
                [
                    "id" => "rssmfm",
                    "name" => "div",
                    "parent" => "xoitiu",
                    "children" => ["ucwcqp", "ncpxft"],
                    "settings" => [
                        "tag" => "li",
                        "_display" => "flex",
                        "_alignItems" => "center"
                    ],
                    "label" => "Item"
                ],
                [
                    "id" => "ucwcqp",
                    "name" => "icon",
                    "parent" => "rssmfm",
                    "children" => [],
                    "settings" => [
                        "icon" => [
                            "library" => "themify",
                            "icon" => "ti-check"
                        ]
                    ]
                ],
                [
                    "id" => "ncpxft",
                    "name" => "text-basic",
                    "parent" => "rssmfm",
                    "children" => [],
                    "settings" => [
                        "text" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit.",
                        "tag" => "span"
                    ],
                    "label" => "Text"
                ]
            ]
        ],
        [
            "id" => "at_table",
            "label" => "Table",
            "icon" => "ti-widgetized",
            "category" => "default",
            "default" => true,
            "globalClasses" => [],
            "elements" => [
                [
                    "id" => "ulnxgn",
                    "name" => "div",
                    "parent" => 0,
                    "children" => ["nptpxz", "eqzrzt"],
                    "settings" => [
                        "tag" => "custom",
                        "customTag" => "table",
                        "_cssCustom" => "#brxe-ulnxgn *:where(th, td) {\n\toutline: 1px solid;\n  padding: 10px;\n}"
                    ],
                    "label" => "Table"
                ],
                [
                    "id" => "nptpxz",
                    "name" => "div",
                    "parent" => "ulnxgn",
                    "children" => ["ykowbv"],
                    "settings" => [
                        "tag" => "custom",
                        "customTag" => "thead"
                    ],
                    "label" => "Head"
                ],
                [
                    "id" => "ykowbv",
                    "name" => "div",
                    "parent" => "nptpxz",
                    "children" => ["uowiuv", "tbfwfu", "ndpput"],
                    "settings" => [
                        "tag" => "custom",
                        "customTag" => "tr"
                    ],
                    "label" => "Tr"
                ],
                [
                    "id" => "uowiuv",
                    "name" => "div",
                    "parent" => "ykowbv",
                    "children" => ["amofsu"],
                    "settings" => [
                        "tag" => "custom",
                        "customTag" => "th"
                    ],
                    "label" => "Th"
                ],
                [
                    "id" => "amofsu",
                    "name" => "text-basic",
                    "parent" => "uowiuv",
                    "children" => [],
                    "settings" => [
                        "text" => "Heading 1",
                        "tag" => "span"
                    ],
                    "label" => "Heading"
                ],
                [
                    "id" => "tbfwfu",
                    "name" => "div",
                    "parent" => "ykowbv",
                    "children" => ["xypiew"],
                    "settings" => [
                        "tag" => "custom",
                        "customTag" => "th"
                    ],
                    "label" => "Th"
                ],
                [
                    "id" => "xypiew",
                    "name" => "text-basic",
                    "parent" => "tbfwfu",
                    "children" => [],
                    "settings" => [
                        "text" => "Heading 2",
                        "tag" => "span"
                    ],
                    "label" => "Heading"
                ],
                [
                    "id" => "ndpput",
                    "name" => "div",
                    "parent" => "ykowbv",
                    "children" => ["xufoeq"],
                    "settings" => [
                        "tag" => "custom",
                        "customTag" => "th"
                    ],
                    "label" => "Th"
                ],
                [
                    "id" => "xufoeq",
                    "name" => "text-basic",
                    "parent" => "ndpput",
                    "children" => [],
                    "settings" => [
                        "text" => "Heading 3",
                        "tag" => "span"
                    ],
                    "label" => "Heading"
                ],
                [
                    "id" => "eqzrzt",
                    "name" => "div",
                    "parent" => "ulnxgn",
                    "children" => ["ijpuev"],
                    "settings" => [
                        "tag" => "custom",
                        "customTag" => "tbody"
                    ],
                    "label" => "Body"
                ],
                [
                    "id" => "ijpuev",
                    "name" => "div",
                    "parent" => "eqzrzt",
                    "children" => ["kznrsy", "lkzzuk", "grgzkg"],
                    "settings" => [
                        "tag" => "custom",
                        "customTag" => "tr"
                    ],
                    "label" => "Tr"
                ],
                [
                    "id" => "kznrsy",
                    "name" => "div",
                    "parent" => "ijpuev",
                    "children" => ["gjlpug"],
                    "settings" => [
                        "tag" => "custom",
                        "customTag" => "td"
                    ],
                    "label" => "Td"
                ],
                [
                    "id" => "gjlpug",
                    "name" => "text-basic",
                    "parent" => "kznrsy",
                    "children" => [],
                    "settings" => [
                        "tag" => "span",
                        "text" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit."
                    ],
                    "label" => "Content"
                ],
                [
                    "id" => "lkzzuk",
                    "name" => "div",
                    "parent" => "ijpuev",
                    "children" => ["tzvqwa"],
                    "settings" => [
                        "tag" => "custom",
                        "customTag" => "td"
                    ],
                    "label" => "Td"
                ],
                [
                    "id" => "tzvqwa",
                    "name" => "text-basic",
                    "parent" => "lkzzuk",
                    "children" => [],
                    "settings" => [
                        "text" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit.",
                        "tag" => "span"
                    ],
                    "label" => "Content"
                ],
                [
                    "id" => "grgzkg",
                    "name" => "div",
                    "parent" => "ijpuev",
                    "children" => ["ocjqvg"],
                    "settings" => [
                        "tag" => "custom",
                        "customTag" => "td"
                    ],
                    "label" => "Td"
                ],
                [
                    "id" => "ocjqvg",
                    "name" => "text-basic",
                    "parent" => "grgzkg",
                    "children" => [],
                    "settings" => [
                        "text" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit.",
                        "tag" => "span"
                    ],
                    "label" => "Content"
                ]
            ]
        ],
        [
            "id" => "at_justified_gallery_img",
            "label" => "Justified Image Gallery",
            "icon" => "ti-gallery",
            "category" => "default",
            "default" => true,
            "globalClasses" => [],
            "elements" => [
                [
                    "id" => "ktagni",
                    "name" => "block",
                    "parent" => 0,
                    "children" => ["ritcws", "laayuv", "idmoky", "vgueex", "yrjjgp", "vduddj"],
                    "settings" => [
                        "_cssCustom" => "#brxe-ktagni {\n  list-style: none;\n}\n\n#brxe-ktagni::after {\n  content: '';\n  flex-grow: 999;\n}\n\n#brxe-ktagni li{\n  flex: 1 1 auto;\n\theight: 200px;\n}\n\n#brxe-ktagni li img{\n\tobject-fit: cover;\n  width: 100%;\n  height: 100%;\n  vertical-align: middle;\n}",
                        "tag" => "ul",
                        "_flexWrap" => "wrap",
                        "_direction" => "row",
                        "_columnGap" => "10",
                        "_rowGap" => "10",
                        "_padding" => [
                            "left" => "0"
                        ]
                    ],
                    "label" => "Justified Image Gallery"
                ],
                [
                    "id" => "ritcws",
                    "name" => "div",
                    "parent" => "ktagni",
                    "children" => ["gampzo"],
                    "settings" => [
                        "tag" => "li"
                    ],
                    "label" => "Image Wrapper"
                ],
                [
                    "id" => "gampzo",
                    "name" => "image",
                    "parent" => "ritcws",
                    "children" => [],
                    "settings" => [
                        "image" => [
                            "url" => "/wp-content/plugins/bricks-advanced-themer/assets/img/placeholder-image.png",
                            "external" => true,
                            "filename" => "placeholder-image.png"
                        ]
                    ]
                ],
                [
                    "id" => "laayuv",
                    "name" => "div",
                    "parent" => "ktagni",
                    "children" => ["fuabbo"],
                    "settings" => [
                        "tag" => "li"
                    ],
                    "label" => "Image Wrapper"
                ],
                [
                    "id" => "fuabbo",
                    "name" => "image",
                    "parent" => "laayuv",
                    "children" => [],
                    "settings" => [
                        "image" => [
                            "url" => "/wp-content/plugins/bricks-advanced-themer/assets/img/placeholder-image.png",
                            "external" => true,
                            "filename" => "placeholder-image.png"
                        ]
                    ]
                ],
                [
                    "id" => "idmoky",
                    "name" => "div",
                    "parent" => "ktagni",
                    "children" => ["xsinls"],
                    "settings" => [
                        "tag" => "li"
                    ],
                    "label" => "Image Wrapper"
                ],
                [
                    "id" => "xsinls",
                    "name" => "image",
                    "parent" => "idmoky",
                    "children" => [],
                    "settings" => [
                        "image" => [
                            "url" => "/wp-content/plugins/bricks-advanced-themer/assets/img/placeholder-image.png",
                            "external" => true,
                            "filename" => "placeholder-image.png"
                        ]
                    ]
                ],
                [
                    "id" => "vgueex",
                    "name" => "div",
                    "parent" => "ktagni",
                    "children" => ["xrgstp"],
                    "settings" => [
                        "tag" => "li"
                    ],
                    "label" => "Image Wrapper"
                ],
                [
                    "id" => "xrgstp",
                    "name" => "image",
                    "parent" => "vgueex",
                    "children" => [],
                    "settings" => [
                        "image" => [
                            "url" => "/wp-content/plugins/bricks-advanced-themer/assets/img/placeholder-image.png",
                            "external" => true,
                            "filename" => "placeholder-image.png"
                        ]
                    ]
                ],
                [
                    "id" => "yrjjgp",
                    "name" => "div",
                    "parent" => "ktagni",
                    "children" => ["uzffwg"],
                    "settings" => [
                        "tag" => "li"
                    ],
                    "label" => "Image Wrapper"
                ],
                [
                    "id" => "uzffwg",
                    "name" => "image",
                    "parent" => "yrjjgp",
                    "children" => [],
                    "settings" => [
                        "image" => [
                            "url" => "/wp-content/plugins/bricks-advanced-themer/assets/img/placeholder-image.png",
                            "external" => true,
                            "filename" => "placeholder-image.png"
                        ]
                    ]
                ],
                [
                    "id" => "vduddj",
                    "name" => "div",
                    "parent" => "ktagni",
                    "children" => ["yvdyii"],
                    "settings" => [
                        "tag" => "li"
                    ],
                    "label" => "Image Wrapper"
                ],
                [
                    "id" => "yvdyii",
                    "name" => "image",
                    "parent" => "vduddj",
                    "children" => [],
                    "settings" => [
                        "image" => [
                            "url" => "/wp-content/plugins/bricks-advanced-themer/assets/img/placeholder-image.png",
                            "external" => true,
                            "filename" => "placeholder-image.png"
                        ]
                    ]
                ]
            ]
        ],
        [
            "id" => "at_masonry_gallery_img",
            "label" => "Masonry Image Gallery",
            "icon" => "ti-gallery",
            "category" => "default",
            "default" => true,
            "globalClasses" => [],
            "elements" => [
                [
                    "id" => "ktagni",
                    "name" => "block",
                    "parent" => 0,
                    "children" => ["ritcws", "laayuv", "idmoky", "vgueex", "yrjjgp", "vduddj", "divihk"],
                    "settings" => [
                        "_cssCustom" => "#brxe-ktagni {\n  --columns: 4;\n  --gap: 10px;\n}\n\n#brxe-ktagni {\n  column-count: var(--columns);\n  gap: var(--gap);\n  list-style: none;\n}\n\n#brxe-ktagni li{\n  margin-block-end: var(--gap);\n  break-inside: avoid;\n}",
                        "tag" => "ul",
                        "_padding" => ["left" => "0"],
                        "_display" => "block"
                    ],
                    "label" => "Masonry Image Gallery"
                ],
                [
                    "id" => "ritcws",
                    "name" => "div",
                    "parent" => "ktagni",
                    "children" => ["gampzo"],
                    "settings" => ["tag" => "li"],
                    "label" => "Image Wrapper"
                ],
                [
                    "id" => "gampzo",
                    "name" => "image",
                    "parent" => "ritcws",
                    "children" => [],
                    "settings" => [
                        "image" => [
                            "url" => "/wp-content/plugins/bricks-advanced-themer/assets/img/placeholder-image.png",
                            "external" => true,
                            "filename" => "placeholder-image.png"
                        ]
                    ]
                ],
                [
                    "id" => "laayuv",
                    "name" => "div",
                    "parent" => "ktagni",
                    "children" => ["fuabbo"],
                    "settings" => ["tag" => "li"],
                    "label" => "Image Wrapper"
                ],
                [
                    "id" => "fuabbo",
                    "name" => "image",
                    "parent" => "laayuv",
                    "children" => [],
                    "settings" => [
                        "image" => [
                            "url" => "/wp-content/plugins/bricks-advanced-themer/assets/img/placeholder-image.png",
                            "external" => true,
                            "filename" => "placeholder-image.png"
                        ]
                    ]
                ],
                [
                    "id" => "idmoky",
                    "name" => "div",
                    "parent" => "ktagni",
                    "children" => ["xsinls"],
                    "settings" => ["tag" => "li"],
                    "label" => "Image Wrapper"
                ],
                [
                    "id" => "xsinls",
                    "name" => "image",
                    "parent" => "idmoky",
                    "children" => [],
                    "settings" => [
                        "image" => [
                            "url" => "/wp-content/plugins/bricks-advanced-themer/assets/img/placeholder-image.png",
                            "external" => true,
                            "filename" => "placeholder-image.png"
                        ]
                    ]
                ],
                [
                    "id" => "vgueex",
                    "name" => "div",
                    "parent" => "ktagni",
                    "children" => ["xrgstp"],
                    "settings" => ["tag" => "li"],
                    "label" => "Image Wrapper"
                ],
                [
                    "id" => "xrgstp",
                    "name" => "image",
                    "parent" => "vgueex",
                    "children" => [],
                    "settings" => [
                        "image" => [
                            "url" => "/wp-content/plugins/bricks-advanced-themer/assets/img/placeholder-image.png",
                            "external" => true,
                            "filename" => "placeholder-image.png"
                        ]
                    ]
                ],
                [
                    "id" => "yrjjgp",
                    "name" => "div",
                    "parent" => "ktagni",
                    "children" => ["uzffwg"],
                    "settings" => ["tag" => "li"],
                    "label" => "Image Wrapper"
                ],
                [
                    "id" => "uzffwg",
                    "name" => "image",
                    "parent" => "yrjjgp",
                    "children" => [],
                    "settings" => [
                        "image" => [
                            "url" => "/wp-content/plugins/bricks-advanced-themer/assets/img/placeholder-image.png",
                            "external" => true,
                            "filename" => "placeholder-image.png"
                        ]
                    ]
                ],
                [
                    "id" => "vduddj",
                    "name" => "div",
                    "parent" => "ktagni",
                    "children" => ["yvdyii"],
                    "settings" => ["tag" => "li"],
                    "label" => "Image Wrapper"
                ],
                [
                    "id" => "yvdyii",
                    "name" => "image",
                    "parent" => "vduddj",
                    "children" => [],
                    "settings" => [
                        "image" => [
                            "url" => "/wp-content/plugins/bricks-advanced-themer/assets/img/placeholder-image.png",
                            "external" => true,
                            "filename" => "placeholder-image.png"
                        ]
                    ]
                ],
                [
                    "id" => "divihk",
                    "name" => "div",
                    "parent" => "ktagni",
                    "children" => ["ffxspl"],
                    "settings" => ["tag" => "li"],
                    "label" => "Image Wrapper"
                ],
                [
                    "id" => "ffxspl",
                    "name" => "image",
                    "parent" => "divihk",
                    "children" => [],
                    "settings" => [
                        "image" => [
                            "url" => "/wp-content/plugins/bricks-advanced-themer/assets/img/placeholder-image.png",
                            "external" => true,
                            "filename" => "placeholder-image.png"
                        ]
                    ]
                ]
            ]
        ]
    ];

    // UNDOCUMENTED
    $default_elements = apply_filters( 'at/nested_elements/default_elements', $elements );
    wp_localize_script( 'brxc-builder', 'brxcNestedElementsDefault', $default_elements );
    ?>
    <div id="<?php echo esc_attr($overlay_id);?>" class="brxc-overlay__wrapper" style="opacity:0" data-input-target="" onmousedown="ADMINBRXC.closeModal(event, this, '#<?php echo esc_attr($overlay_id);?>');" >
        <div class="brxc-overlay__inner brxc-large">
            <div class="brxc-overlay__close-btn" onClick="ADMINBRXC.closeModal(event, event.target, '#<?php echo esc_attr($overlay_id);?>')">
                <i class="bricks-svg ti-close"></i>
            </div>
            <div class="brxc-overlay__inner-wrapper">
                <div class="brxc-overlay__header">
                    <h3 class="brxc-overlay__header-title"><?php echo esc_attr($modal_heading_title);?></h3>

                    <div class="brxc-overlay__resize-icons">
                        <i class="fa-solid fa-window-maximize" onclick="ADMINBRXC.maximizeModal(this, '#<?php echo esc_attr($overlay_id);?>');"></i>
                    </div>
                </div>
                <div class="brxc-overlay__error-message-wrapper"></div>
                <div class="brxc-overlay__container no-radius">
                    <div class="brxc-overlay__pannels-wrapper">
                        <div class="brxc-overlay__pannel brxc-overlay__pannel-1">
                            <div id="brxcCustomComponentsCanvas">
                                <div id="brxcCustomComponentsCats" class="brxc-manager__left-menu"></div>
                                <div id="brxcCustomComponentsContainer">
                                    <div id="brxcCustomComponentsSearch" class="brxc-overlay__search-box">
                                        <input type="text" class="iso-search" name="dynamic-data-search" placeholder="Type here to filter your custom components" oninput="ADMINBRXC.customComponentStates.search = this.value;ADMINBRXC.customComponentsMountElements();">
                                        <div class="iso-search-icon">
                                            <i class="bricks-svg ti-search"></i>
                                        </div>
                                        <div class="iso-reset" data-balloon="Reset" data-balloon-pos="left" onclick="ADMINBRXC.customComponentStates.search = '';ADMINBRXC.customComponentsMountElements();this.previousElementSibling.previousElementSibling.value = '';">
                                            <i class="bricks-svg fas fa-undo"></i>
                                        </div>
                                    </div>
                                    <div id="brxcCustomComponentsGrid">
                                        <div id="brxcCustomComponentsElements"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <div class="brxc-overlay__footer">
                <div class="brxc-overlay__footer-wrapper">
                    <a class="brxc-overlay__action-btn primary" style="margin-left: auto;" onClick="ADMINBRXC.saveCustomComponentsOptions();"><span>Save to Database</span></a>
                </div>
            </div>
            </div>
        </div>
    </div>
<?php }

$output = ob_get_clean();
$output = preg_replace('/>\s+</s', '><', $output);
$brxc_modals['custom_components'] = [
    "id" => $overlay_id,
    "html" => trim($output)
];
