<?php

namespace Bricksforge\Helper;

if (!defined('ABSPATH'))
    exit;

class ElementsHelper
{

    public static $page_data = [];
    public static $page_data_string = "";
    public static $post_id = 0;

    // Constructor
    public function __construct()
    {
        add_action('wp', [$this, 'init'], 10);
    }

    public function init()
    {
        // If $page_data is already set, we don't need to do anything
        if (isset(self::$page_data) && !empty(self::$page_data)) {
            return;
        }

        self::$post_id = get_the_ID();
        self::$page_data = $this->get_page_data();
        self::$page_data_string = json_encode(self::$page_data);
    }

    public function get_page_data()
    {
        $post_id = self::$post_id;

        $data = isset(\Bricks\Database::$page_data['content']) ? \Bricks\Database::$page_data['content'] : \Bricks\Database::get_data($post_id);
        $bricks_data = \Bricks\Helpers::get_bricks_data($post_id, 'content');
        $template_data = \Bricks\Database::get_template_data('content');

        $active_popups = \Bricks\Database::$active_templates['popup'];
        $active_header = \Bricks\Database::$active_templates['header'];
        $active_footer = \Bricks\Database::$active_templates['footer'];
        $active_section = \Bricks\Database::$active_templates['section'];

        if (!isset($data) || !is_array($data)) {
            $data = [];
        }

        if (!isset($bricks_data) || !is_array($bricks_data)) {
            $bricks_data = [];
        }

        if (!isset($template_data) || !is_array($template_data)) {
            $template_data = [];
        }

        // In $data, get all elements with name = "template". Then, get the settings['template'] value. This is the template ID
        $templates = array_filter($data, function ($element) {
            return $element['name'] === 'template';
        });

        // Re-index
        if (!empty($templates)) {
            $templates = array_values($templates);

            foreach ($templates as $template) {
                if (isset($template['settings']) && isset($template['settings']['template'])) {
                    $template_id = $template['settings']['template'];
                    $template_data = \Bricks\Database::get_data($template_id);
                    if (is_array($template_data)) {
                        $data = array_merge($data, $template_data);
                    }
                }
            }
        }

        $bricks_templates = array_filter($bricks_data, function ($element) {
            return $element['name'] === 'template';
        });

        // Re-index
        if (!empty($bricks_templates)) {
            $bricks_templates = array_values($bricks_templates);

            foreach ($bricks_templates as $template) {
                if (isset($template['settings']) && isset($template['settings']['template'])) {
                    $template_id = $template['settings']['template'];
                    $template_data = \Bricks\Database::get_data($template_id);
                    if (is_array($template_data)) {
                        $bricks_data = array_merge($bricks_data, $template_data);
                    }
                }
            }
        }

        if (isset($template_data) && is_array($template_data)) {
            $data = array_merge($data, $template_data);
        }

        if (is_array($active_popups) && !empty($active_popups)) {
            foreach ($active_popups as $popup_id) {
                $popup_data = \Bricks\Database::get_data($popup_id);
                if (is_array($popup_data)) {
                    $data = array_merge($data, $popup_data);
                }
            }
        }

        if (isset($active_section) && is_array($active_section) && !empty($active_section)) {
            foreach ($active_section as $section_id) {
                $section_data = \Bricks\Database::get_data($section_id);
                if (is_array($section_data)) {
                    $data = array_merge($data, $section_data);
                }
            }
        }

        if (isset($active_header) && $active_header) {
            $header_data = \Bricks\Database::get_template_data('header');
            if (is_array($header_data)) {
                $data = array_merge($data, $header_data);
            }
        }

        if (isset($active_footer) && $active_footer) {
            $footer_data = \Bricks\Database::get_template_data('footer');
            if (is_array($footer_data)) {
                $data = array_merge($data, $footer_data);
            }
        }

        if (!is_array($data)) {
            // No data found. We stop here.
            return;
        }

        // Remove duplicates
        $data = array_map("unserialize", array_unique(array_map("serialize", $data)));

        return $data;
    }

    public static function exists_in_page($element_name)
    {
        if (!isset($element_name) || empty($element_name)) {
            return false;
        }

        if (isset(self::$page_data_string) && !empty(self::$page_data_string)) {
            return strpos(self::$page_data_string, $element_name) !== false;
        }

        return false;
    }

    public static function get_elements_by_name($element_name)
    {
        if (!isset($element_name) || empty($element_name)) {
            return;
        }

        $elements = array_filter(self::$page_data, function ($element) use ($element_name) {
            return $element['name'] === $element_name;
        });

        return $elements;
    }

    public static function get_element_by_id($element_id)
    {
        if (!isset($element_id) || empty($element_id)) {
            return;
        }

        $elements = array_filter(self::$page_data, function ($element) use ($element_id) {
            return $element['id'] === $element_id;
        });

        if (isset($elements) && !empty($elements)) {
            $elements = array_values($elements);
            return $elements[0];
        }

        return;
    }
}
