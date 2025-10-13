<?php

namespace Bricks\Integrations\Dynamic_Data\Providers;

if (!defined('ABSPATH')) {
    exit;
}

class Provider_Bricksforge extends Base
{
    public $title;
    public $description;
    public $id;

    public function __construct()
    {
        $this->title = __('Bricksforge', 'bricksforge');
        $this->description = __('Bricksforge Dynamic Data', 'bricksforge');
        $this->id = 'bricksforge';
    }

    public static function load_me()
    {
        return true;
    }

    public function register_tags()
    {
        $tags = $this->get_tags_config();

        foreach ($tags as $key => $tag) {
            $this->tags[$key] = [
                'name'     => '{' . $key . '}',
                'label'    => $tag['label'],
                'group'    => $tag['group'],
                'provider' => $this->id,
            ];
        }
    }

    public function get_tags_config()
    {
        $tags = [];

        $tags['brf_form_calculation'] = [
            'name' => 'brf_form_calculation',
            'label' => __('Form Calculation - add id after :', 'bricksforge'),
            'group' => __('Bricksforge', 'bricksforge'),
            'provider' => $this->id,
        ];

        $tags['brf_form_live_value'] = [
            'name' => 'brf_form_live_value',
            'label' => __('Form Live Value - add field id after :', 'bricksforge'),
            'group' => __('Bricksforge', 'bricksforge'),
            'provider' => $this->id,
        ];

        if (class_exists('WooCommerce')) {
            $tags['brf_form_wc_variation_price'] = [
                'name' => 'brf_form_wc_variation_price',
                'label' => __('Form WC Variation Price - add id after :', 'bricksforge'),
                'group' => __('Bricksforge', 'bricksforge'),
                'provider' => $this->id,
            ];
        }

        $tags['brf_post_title'] = [
            'name' => 'brf_post_title',
            'label' => __('Post Title - add post_id after :', 'bricksforge'),
            'group' => __('Bricksforge', 'bricksforge'),
            'provider' => $this->id,
        ];

        $tags['brf_post_content'] = [
            'name' => 'brf_post_content',
            'label' => __('Post Content - add post_id after :', 'bricksforge'),
            'group' => __('Bricksforge', 'bricksforge'),
            'provider' => $this->id,
        ];

        $tags['brf_post_status'] = [
            'name' => 'brf_post_status',
            'label' => __('Post Status - add post_id after :', 'bricksforge'),
            'group' => __('Bricksforge', 'bricksforge'),
            'provider' => $this->id,
        ];

        $tags['brf_post_excerpt'] = [
            'name' => 'brf_post_excerpt',
            'label' => __('Post Excerpt - add post_id after :', 'bricksforge'),
            'group' => __('Bricksforge', 'bricksforge'),
            'provider' => $this->id,
        ];

        $tags['brf_post_date'] = [
            'name' => 'brf_post_date',
            'label' => __('Post Date - add post_id after :', 'bricksforge'),
            'group' => __('Bricksforge', 'bricksforge'),
            'provider' => $this->id,
        ];

        $tags['brf_post_thumbnail_url'] = [
            'name' => 'brf_post_thumbnail',
            'label' => __('Post Thumbnail Url - add post_id after :', 'bricksforge'),
            'group' => __('Bricksforge', 'bricksforge'),
            'provider' => $this->id,
        ];

        $tags['brf_post_meta'] = [
            'name' => 'brf_post_meta',
            'label' => __('Post Meta - use :meta_name:post_id', 'bricksforge'),
            'group' => __('Bricksforge', 'bricksforge'),
            'provider' => $this->id,
        ];

        $tags['brf_acf_field'] = [
            'name' => 'brf_acf_field',
            'label' => __('ACF Field - use :field_name:post_id', 'bricksforge'),
            'group' => __('Bricksforge', 'bricksforge'),
            'provider' => $this->id,
        ];

        $tags['brf_parent_loop_post_id'] = [
            'name' => 'brf_parent_loop_post_id',
            'label' => __('Parent Loop Post ID', 'bricksforge'),
            'group' => __('Bricksforge', 'bricksforge'),
            'provider' => $this->id,
        ];

        $tags['brf_loop_index'] = [
            'name' => 'brf_loop_index',
            'label' => __('Loop Index', 'bricksforge'),
            'group' => __('Bricksforge', 'bricksforge'),
            'provider' => $this->id,
        ];

        return $tags;
    }

    public function get_tag_value($tag, $post, $args, $context)
    {

        $value = '';

        switch ($tag) {
            case 'brf_form_calculation':
                if (empty($args)) {
                    break;
                }

                $calculation_id = $args[0];
                $operator = null;
                $post_calc_value = null;
                $operator2 = null;
                $post_calc_value2 = null;

                // args[1] can contain the operator. args[2] can contain the post calculation value
                if (isset($args[1])) {
                    $operator = $args[1];
                }

                if (isset($args[2])) {
                    $post_calc_value = $args[2];
                }

                if (isset($args[3])) {
                    $operator2 = $args[3];
                }

                if (isset($args[4])) {
                    $post_calc_value2 = $args[4];
                }

                $value = $this->get_form_calculation_value($calculation_id, $operator, $post_calc_value, $operator2, $post_calc_value2);


                break;
            case 'brf_form_live_value':
                if (empty($args)) {
                    break;
                }

                $field_id = $args[0];
                $fallback = isset($args[1]) ? $args[1] : '';




                $value = "<span class='brf-live-value' data-connection='" . $field_id . "' data-fallback='" . $fallback . "'></span>";

                break;
            case 'brf_form_wc_variation_price':
                if (empty($args)) {
                    break;
                }

                $form_id = $args[0];

                $value = $this->get_form_wc_variation_value($form_id);

                break;
            case 'brf_post_title':
                if (empty($args)) {
                    $value = get_the_title();
                    break;
                }

                $post_id = $args[0];

                $value = get_the_title($post_id);

                break;

            case 'brf_post_content':
                if (empty($args)) {
                    $value = get_the_content();
                    break;
                }

                $post_id = $args[0];

                $value = get_the_content(null, false, $post_id);

                break;

            case 'brf_post_status':
                if (empty($args)) {
                    $value = get_post_status();
                    break;
                }

                $post_id = $args[0];

                $value = get_post_status($post_id);

                break;

            case 'brf_post_excerpt':
                if (empty($args)) {
                    $value = get_the_excerpt();
                    break;
                }

                $post_id = $args[0];

                $value = get_the_excerpt($post_id);

                break;

            case 'brf_post_date':
                if (empty($args)) {
                    $value = get_the_date();
                    break;
                }

                $post_id = $args[0];

                $value = get_the_date(null, $post_id);

                break;

            case 'brf_post_thumbnail_url':
                if (empty($args)) {
                    $value = get_the_post_thumbnail_url();
                    break;
                }

                $post_id = $args[0];

                $value = get_the_post_thumbnail_url($post_id);

                break;

            case 'brf_post_meta':
                if (empty($args)) {
                    break;
                }

                $meta_key = $args[0];
                $post_id = null;

                if (isset($args[1])) {
                    $post_id = $args[1];
                    $post_id = absint($post_id);
                }

                $value = get_post_meta($post_id, $meta_key, true);

                break;

            case 'brf_acf_field':
                if (empty($args)) {
                    break;
                }

                $meta_key = $args[0];
                $post_id = null;

                if (isset($args[1])) {
                    $post_id = $args[1];
                    $post_id = absint($post_id);
                }

                $value = get_field($meta_key, $post_id);

                break;

            case 'brf_parent_loop_post_id':
                $level = 1;

                if (!empty($args) && is_numeric($args[0])) {
                    $level = $args[0];
                }

                $query_id = $this->get_bricks_looping_parent_query_id_by_level($level);

                if (!$query_id) {
                    break;
                }

                $query_object = \Bricks\Query::get_loop_object($query_id);

                if (!$query_object) {
                    break;
                }

                $post_id = $query_object->ID;

                if (!$post_id) {
                    break;
                }

                $value = $post_id;

                break;
            case 'brf_loop_index':
                $offset = 0;

                if (!empty($args) && is_numeric($args[0])) {
                    $offset = intval($args[0]);
                }

                $index = intval(\Bricks\Query::get_loop_index()) + $offset;

                if (is_nan($index)) {
                    break;
                }

                $value = $index;

                break;
        }

        return $value;
    }

    public function get_form_calculation_value($calculation_id, $operator = null, $post_calc_value = null, $operator2 = null, $post_calc_value2 = null)
    {

        if (isset($operator) && (!isset($post_calc_value) || !is_numeric($post_calc_value))) {
            return "";
        }

        $output = "";
        $output .= "<span class='brf-form-calculation-value' data-calculation-id=" . $calculation_id;
        $output .= $operator ? ' data-calculation-operator=' . $operator . ' data-calculation-value=' . $this->sanitize_post_calc_value($post_calc_value) : '';
        $output .= $operator2 ? ' data-calculation-operator2=' . $operator2 . ' data-calculation-value2=' . $this->sanitize_post_calc_value($post_calc_value2) : '';
        $output .= ">";
        $output .= 0;
        $output .= "</span>";

        return $output;
    }

    private function sanitize_post_calc_value($post_calc_value)
    {
        // Ensure the input only contains allowed characters
        if (preg_match('#^[\d+\-*/\s().]+$#', $post_calc_value)) {
            return $post_calc_value;
        } else {
            // Return an empty string or a default value if the input is not valid
            return '';
        }
    }

    // Recheck: To we need this here?
    public function handle_post_calculation($args, $value, $operator, $post_calc_value)
    {
        $final_value = $value;


        // If there is no second and third arg, return the value
        if (!isset($args[1]) || !isset($args[2])) {
            return $final_value;
        }

        // If the second arg is not a valid operator, return the value
        if (!in_array($args[1], ['add', 'subtract', 'multiply', 'divide'])) {
            return $final_value;
        }

        // args[1] can contain the operator. args[2] can contain the post calculation value
        if (isset($args[1])) {
            $operator = $args[1];
        }

        if (isset($args[2])) {
            $post_calc_value = $args[2];
        }

        switch ($operator) {
            case 'add':
                $final_value = $value + $post_calc_value;
                break;
            case 'subtract':
                $final_value = $value - $post_calc_value;
                break;
            case 'multiply':
                $final_value = $value * $post_calc_value;
                break;
            case 'divide':
                $final_value = $value / $post_calc_value;
                break;
        }

        return $final_value;
    }

    public function get_form_wc_variation_value($form_id)
    {
        // If Woocommerce is not active, return empty string
        if (!class_exists('WooCommerce')) {
            return '';
        }

        return "<span class='brf-form-wc-variation-price' data-form-id=" . $form_id . "></span>";
    }

    // Thanks, Itchy! :)
    // Reference: https://itchycode.com/bricks-builder-useful-functions-and-tips/
    // Get the parent post ID of the current loop
    public function get_bricks_looping_parent_query_id_by_level($level = 1)
    {
        global $bricks_loop_query;

        if (empty($bricks_loop_query) || $level < 1) {
            return false;
        }

        $current_query_id = \Bricks\Query::is_any_looping();

        if (!$current_query_id) {
            return false;
        }

        if (!isset($bricks_loop_query[$current_query_id])) {
            return false;
        }

        $query_ids = array_reverse(array_keys($bricks_loop_query));

        if (!isset($query_ids[$level])) {
            return false;
        }

        if ($bricks_loop_query[$query_ids[$level]]->is_looping) {
            return $query_ids[$level];
        }

        return false;
    }
}
