<?php

namespace Bricksforge;

if (!defined('ABSPATH')) {
    exit;
}

use TypeError;

class EmailDesigner
{

    private static $instance;
    private $wc_order_id;

    /**
     * Holds the global variables for the Twig Parser
     * @var array
     */
    public $global_twig_vars = [];

    public static function get_instance()
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    public function __construct()
    {
        $this->init();
    }

    /**
     * Init
     * @return void
     */
    public function init()
    {
        if ($this->activated() === true) {
            add_action('admin_enqueue_scripts', [$this, 'load_dependencies']);
            $this->configure();
        }
    }

    /**
     * Load Dependencies
     * @return void
     */
    public function load_dependencies()
    {
        wp_enqueue_style('bricksforge-quill-snow');
    }

    /**
     * Configures the Email Designer
     * @return void
     */
    public function configure()
    {
        $templates = get_option('brf_email_designer_data') ? get_option('brf_email_designer_data') : false;

        if ($templates === false || !is_array($templates)) {
            return;
        }

        // Preps
        add_filter('wp_mail_content_type', [$this, 'set_content_type']);

        // User
        add_filter('wp_new_user_notification_email', [$this, 'run_wp_new_user_notification_email'], 10, 3);
        add_filter('retrieve_password_notification_email', [$this, 'run_retrieve_password_notification_email'], 10, 1);
        add_filter('new_user_email_content', [$this, 'run_new_user_email_content'], 10, 2);
        add_filter('email_change_email', [$this, 'run_email_change_email'], 10, 3);
        add_filter('password_change_email', [$this, 'run_password_change_email'], 10, 3);
        add_filter('user_request_action_email_content', [$this, 'run_user_request_action_email_content'], 10, 2);
        add_filter('wp_privacy_personal_data_email_content', [$this, 'run_wp_privacy_personal_data_email_content'], 10, 3);
        add_filter('comment_notification_text', [$this, 'run_comment_notification_text'], 10, 2);

        // Admin
        add_filter('wp_new_user_notification_email_admin', [$this, 'run_wp_new_user_notification_email_admin'], 10, 3);
        add_filter('wp_password_change_notification_email', [$this, 'run_wp_password_change_notification_email'], 10, 3);
        add_filter('new_admin_email_content', [$this, 'run_new_admin_email_content'], 10, 2);
        add_filter('user_request_confirmed_email_content', [$this, 'run_user_request_confirmed_email_content'], 10, 2);
        add_filter('comment_moderation_text', [$this, 'run_comment_moderation_text'], 10, 2);

        // Others
        add_filter('wp_mail', [$this, 'finish'], 9999, 1);

        // Test Mail
        add_action('wp_ajax_brf_email_designer_test_mail', [$this, 'send_test_mail']);
        add_action('wp_ajax_nopriv_brf_email_designer_test_mail', [$this, 'send_test_mail']);

        // WooCommerce
        add_filter('woocommerce_email_headers', [$this, 'set_wc_header'], 10, 3);
    }

    /**
     * Finish the Email. This is the last filter that runs before the email is sent.
     * @param array $mail
     * @return array
     */
    public function finish($mail)
    {

        /**
         * Bricks
         */

        $is_bricks = isset($_POST['action']) && ($_POST['action'] == 'bricks_form_submit' || $_POST['action'] == 'bricksforge_form_submit');
        $bricks_post_id;
        $bricks_form_id;
        $bricks_page;
        $bricks_fields = [];

        if ($is_bricks) {
            $bricks_post_id = $_POST['postId'];
            $bricks_form_id = $_POST['formId'];
            $bricks_page = $_POST['referrer'];
            foreach ($_POST as $key => $value) {
                if (strpos($key, 'form-field-') !== false) {
                    $field_name = str_replace('form-field-', '', $key);

                    $value = stripslashes_deep($value);

                    $bricks_fields['brx_' . $field_name] = $value;

                    if (gettype($value) == 'array') {
                        $bricks_fields['brx_' . $field_name] = implode(', ', $value);
                    }
                }
            }

            $bricks_conditions = $this->check_for_bricks_conditions($bricks_form_id);

            $bricks_form_found = false;

            foreach ($bricks_conditions as $bricks_condition) {

                if ($bricks_condition->type == "bricks_form") {
                    $mail = $this->run_bricks_single($mail, $bricks_condition->formId, $bricks_fields, $bricks_post_id);
                    $bricks_form_found = true;
                }
                if ($bricks_condition->type == "bricks_form_confirmation") {
                    $mail = $this->run_bricks_single($mail, $bricks_condition->formId, $bricks_fields, $bricks_post_id, true);
                    $bricks_form_found = true;
                }
            }

            if (!$bricks_form_found) {
                foreach ($bricks_conditions as $bricks_condition) {
                    if ($bricks_condition->type == "all_bricks_forms") {
                        $mail = $this->run_bricks_all($mail, $bricks_form_id, $bricks_fields, $bricks_post_id);
                    }
                    if ($bricks_condition->type == "all_bricks_forms_confirmations") {
                        $mail = $this->run_bricks_all($mail, $bricks_form_id, $bricks_fields, $bricks_post_id, true);
                    }
                }
            }
        }

        /**
         * Wocommerce
         */
        $is_woocommerce = false;

        if (isset($mail['headers'])) {
            $headers = $mail['headers'];

            if (is_array($headers)) {
                $headers = implode("\r\n", $headers);
            }

            if (strpos($headers, 'Brf-Type:') !== false && strpos($headers, 'WooCommerce') !== false) {
                $is_woocommerce = true;
            }
        }

        if ($is_woocommerce) {
            $wc_email_id = $this->get_wc_email_id($mail['headers']);

            if ($wc_email_id !== false) {

                $wc_email_id = trim($wc_email_id);

                switch ($wc_email_id) {
                    case 'new_order':
                        $mail = $this->run_woocommerce_email($mail, 'woocommerce_new_order');
                        break;
                    case 'cancelled_order':
                        $mail = $this->run_woocommerce_email($mail, 'woocommerce_cancelled_order');
                        break;
                    case 'customer_processing_order':
                        $mail = $this->run_woocommerce_email($mail, 'woocommerce_processing_order');
                        break;
                    case 'failed_order':
                        $mail = $this->run_woocommerce_email($mail, 'woocommerce_failed_order');
                        break;
                    case 'customer_on_hold_order':
                        $mail = $this->run_woocommerce_email($mail, 'woocommerce_on_hold_order');
                        break;
                    case 'customer_completed_order':
                        $mail = $this->run_woocommerce_email($mail, 'woocommerce_completed_order');
                        break;
                    case 'customer_refunded_order':
                        $mail = $this->run_woocommerce_email($mail, 'woocommerce_refunded_order');
                        break;
                    case 'customer_partially_refunded_order':
                        $mail = $this->run_woocommerce_email($mail, 'woocommerce_partially_refunded_order');
                        break;
                    case 'customer_invoice':
                        $mail = $this->run_woocommerce_email($mail, 'woocommerce_customer_invoice');
                        break;
                    case 'customer_note':
                        $mail = $this->run_woocommerce_email($mail, 'woocommerce_customer_note');
                        break;
                    case 'customer_reset_password':
                        $mail = $this->run_woocommerce_email($mail, 'woocommerce_customer_reset_password');
                        break;
                    case 'customer_new_account':
                        $mail = $this->run_woocommerce_email($mail, 'woocommerce_customer_new_account');
                        break;
                    default:
                        break;
                }
            }

            $template_key = $this->get_template_key($mail['message']);

            // If no template key is found, we run all WooCommerce emails.
            if (!$template_key) {
                $mail = $this->run_woocommerce_email($mail, 'woocommerce_all');
            }
        }

        /**
         * Last Rechecks
         */

        // The content of our emails starts with: ###TEMPLATE:{$template_key}###.
        $template_key = $this->get_template_key($mail['message']);

        // If the message contains ###BRFTEMPLATEID, the wp_mail wrapper is used
        if (strpos($mail['message'], '###BRFTEMPLATEID:') !== false) {
            $mail = $this->apply_template($template_key, $mail);
        }

        if ($template_key === false) {
            $template_key = "all";
        }

        if ($template_key === "all") {
            $mail = $this->apply_template('all', $mail);
        }

        // Build From (Mail Headers)
        if ($this->get_from($template_key) !== false && is_array($mail['headers'])) {
            $mail['headers'][] = $this->get_from($template_key);
        }

        // Build Reply To (Mail Headers)
        if ($this->get_reply_to($template_key) !== false && is_array($mail['headers'])) {
            $mail['headers'][] = $this->get_reply_to($template_key);
        }

        if (strpos($mail['subject'], '[BRF_TEST]') === false) {
            // Build Subject
            $mail['subject'] = $this->get_subject($template_key, $mail['subject']);

            $mail['subject'] = $this->parse_twig($mail['subject'], $bricks_fields);

            $mail['subject'] = $this->fix_bricks_brackets($mail['subject']);

            // Build Send To
            $mail['to'] = $this->get_send_to($template_key, $mail['to']);
        }

        // Map $mail['headers']  and change the header value
        if (gettype($mail['headers']) == 'array') {
            $mail['headers'] = array_map(function ($header) use ($bricks_fields) {
                $new_header = $this->parse_twig($header, $bricks_fields);
                $new_header = $this->fix_bricks_brackets($new_header);
                return $new_header;
            }, $mail['headers']);
        }

        // Remove the template key from the content
        $mail['message'] = preg_replace('/###(?:BRFTEMPLATE:.*?|.*?)###/', '', $mail['message']);

        // Remove the template id from the content
        $mail['message'] = preg_replace('/###(?:BRFTEMPLATEID:.*?|.*?)###/', '', $mail['message']);

        // Parse Twig
        $mail['message'] = $this->parse_twig($mail['message'], $bricks_fields);

        // Bricks Dynamic Data Fix
        $mail['message'] = $this->fix_bricks_brackets($mail['message']);

        $mail['message'] = html_entity_decode($mail['message']);

        // Filter: bricksforge/email_designer/mail_before_send
        $mail = apply_filters('bricksforge/email_designer/mail_before_send', $mail);

        return $mail;
    }

    public function get_wc_email_id($headers)
    {
        if (is_array($headers)) {
            $headers = implode("\r\n", $headers);
        }

        if (strpos($headers, 'Brf-Email-Id:') !== false) {
            preg_match('/Brf-Email-Id: (.*)/', $headers, $matches);

            if (isset($matches[1])) {
                return $matches[1];
            }
        }

        return false;
    }

    public function set_wc_header($headers, $email_id, $order)
    {
        $headers .= "Brf-Type: WooCommerce" . "\r\n";

        if (isset($email_id) && $email_id !== false) {
            $headers .= "Brf-Email-Id: " . $email_id . "\r\n";
        }

        if (isset($order) && $order !== false && !is_bool($order) && method_exists($order, 'get_id')) {
            $headers .= "Brf-Order-Id: " . $order->get_id() . "\r\n";
        }

        return $headers;
    }

    /**
     * Clean all variables and remove all tags that are not allowed.
     * @param array $vars
     * @return array
     */
    private function clean_vars($vars)
    {
        $allowed_tags = wp_kses_allowed_html('strip');
        $cleaned_vars = [];

        foreach ($vars as $key => $value) {
            if (is_array($value)) {
                $cleaned_values = [];
                foreach ($value as $value2) {
                    $cleaned_values[] = gettype($value2) == 'string' ? wp_kses($value2, $allowed_tags) : $value2;
                }
                $cleaned_vars[$key] = $cleaned_values;
            } else {

                // If string $key contains url, then use esc_url instead of wp_kses
                if (strpos($key, 'url') !== false && $value !== null) {
                    $cleaned_vars[$key] = esc_url_raw($value);
                    continue;
                }

                $cleaned_vars[$key] = gettype($value) == 'string' ? wp_kses($value, $allowed_tags) : $value;
            }
        }

        return $cleaned_vars;
    }

    function preprocess_twig_context(array $context): array
    {
        $processed_context = [];
        foreach ($context as $key => $value) {
            // Replace hyphens with underscores in the key
            $new_key = str_replace('-', '_', $key);

            // Recursively process nested arrays
            if (is_array($value)) {
                $value = $this->preprocess_twig_context($value);
            }

            $processed_context[$new_key] = $value;
        }
        return $processed_context;
    }

    /**
     * Parse Twig
     * @param $content
     * @param $bricks_fields
     * @return string
     */
    public function parse_twig($content, $bricks_fields)
    {

        require_once(__DIR__ . '/../vendor/twig/autoload.php');

        $twig = new \Twig\Environment(new \Twig\Loader\ArrayLoader());

        $content = html_entity_decode($content);

        // In the content, we're searching for {{brx_my-field}} and replace all hyphens with underscores
        $content = preg_replace_callback('/{{brx_([^}]*)}}/', function ($matches) {
            return '{{brx_' . str_replace('-', '_', $matches[1]) . '}}';
        }, $content);

        $context = $this->fetch_twig_variables($bricks_fields);

        $context = $this->preprocess_twig_context($context);

        // Functions
        $twig->addFunction($this->twig_function_get_item_product_image());
        $twig->addFunction($this->twig_function_get_item_formated_price());
        $twig->addFunction($this->twig_function_get_order_meta());
        $twig->addFunction($this->twig_function_get_locale());
        $twig->addFunction($this->twig_function_get_user_meta());

        // Using a wordpress filter to make it possible to add custom functions
        $custom_functions = apply_filters('bricksforge/email_designer/custom_functions', []);

        if (is_array($custom_functions)) {
            foreach ($custom_functions as $function) {
                $twig->addFunction($function);
            }
        }

        try {
            return $twig->createTemplate($content)->render($context);
        } catch (\Twig\Error\SyntaxError $e) {
            error_log('Syntax Error: ' . $e->getMessage());
        } catch (\Twig\Error\RuntimeError $e) {
            error_log('Runtime Error: ' . $e->getMessage());
        }

        return $content;
    }

    private function twig_function_get_item_product_image()
    {
        return new \Twig\TwigFunction('getItemProductImage', function ($product_item) {

            if (!isset($product_item['product_id'])) {
                return '';
            }

            $product = \wc_get_product($product_item['product_id']);
            $image_id = $product->get_image_id();
            $image_url = \wp_get_attachment_url($image_id);

            return $image_url;
        });
    }

    private function twig_function_get_item_formated_price()
    {
        return new \Twig\TwigFunction('getItemPrice', function ($product_item) {

            if (!isset($product_item['product_id'])) {
                return '';
            }

            $product = \wc_get_product($product_item['product_id']);

            $price = $product->get_price_html();

            // For a variable product, $price is now a string with the price range. We need to get the price matching with the variation
            if ($product->is_type('variable')) {
                $variation_id = $product_item['variation_id'];
                $variation = new \WC_Product_Variation($variation_id);
                $price = $variation->get_price_html();
            }

            return $price;
        });
    }

    private function twig_function_get_order_meta()
    {
        return new \Twig\TwigFunction('getOrderMeta', function ($key) {

            if (!$this->wc_order_id) {
                return '';
            }

            $order = \wc_get_order($this->wc_order_id);

            if (!$order) {
                return '';
            }

            $meta = $order->get_meta($key);

            return $meta;
        });
    }

    private function twig_function_get_locale()
    {
        return new \Twig\TwigFunction('getLocale', function () {
            $current_language = \get_locale();

            // TranslatePress
            if (class_exists('TRP_Translate_Press')) {
                global $TRP_LANGUAGE;
                $current_language = isset($TRP_LANGUAGE) ? $TRP_LANGUAGE : $current_language;
            }
            // WPML
            elseif (defined('ICL_LANGUAGE_CODE')) {
                $wpml_language = apply_filters('wpml_current_language', NULL);
                $current_language = !empty($wpml_language) ? $wpml_language : $current_language;
            }
            // Polylang
            elseif (class_exists('Polylang') && function_exists('pll_current_language')) {
                $current_language = pll_current_language();
            }

            return $current_language;
        });
    }

    private function twig_function_get_user_meta()
    {
        return new \Twig\TwigFunction('getUserMeta', function ($key) {
            $user_id = get_current_user_id();

            if (!$user_id) {
                return '';
            }

            $meta = get_user_meta($user_id, $key, true);

            return $meta;
        });
    }

    /**
     * Fetch Twig Variables
     * @param $bricks_fields
     * @return array
     */
    private function fetch_twig_variables($bricks_fields)
    {
        $vars = [];

        foreach ($bricks_fields as $key => $value) {

            // $key to string
            $key = (string)$key;

            // Replace hyphens with underscores
            $key = str_replace('-', '_', $key);

            $vars[$key] = $value;
        }

        // Include global variables from $this->global_variables
        foreach ($this->global_twig_vars as $key => $value) {
            $vars[$key] = $value;
        }

        $cleaned_vars = $this->clean_vars($vars);

        return $cleaned_vars;
    }

    /**
     * Fix Bricks Brackets
     * @param $content
     * @return mixed
     */
    private function fix_bricks_brackets($content)
    {
        // Bricks renders variables with a {} syntax, but Twig uses {{}}. We need to replace {} regex to output the plain match
        preg_match_all('/{([\wÀ-ÖØ-öø-ÿ\-\s\.\/:\(\)\'@|,]+)}/', $content, $matches);

        // Replace {} with match
        foreach ($matches[0] as $match) {
            $content = str_replace($match, $matches[1][0], $content);
        }

        return $content;
    }

    /**
     * Get Template Key
     * @param $content
     * @return bool|string
     */
    public function get_template_key($content)
    {
        // Check for BRFTEMPLATE:{key}
        $matches = [];
        preg_match('/###BRFTEMPLATE:(.*?)###/', $content, $matches);
        $template_key = isset($matches[1]) ? $matches[1] : false;

        // If no BRFTEMPLATE:{key} found, check for BRFTEMPLATEID:{id}
        if ($template_key === false) {
            preg_match('/###BRFTEMPLATEID:(.*?)###/', $content, $matches);
            $template_key = isset($matches[1]) ? $matches[1] : false;
        }

        return $template_key;
    }

    /**
     * Check for Bricks Conditions
     * @param $bricks_form_id
     * @return array
     */
    public function check_for_bricks_conditions($bricks_form_id)
    {
        $templates = get_option('brf_email_designer_data') ? get_option('brf_email_designer_data') : false;

        $bricks_conditions = [];

        foreach ($templates as $temp) {
            if (isset($temp->conditions) && is_array($temp->conditions)) {
                foreach ($temp->conditions as $condition) {
                    if ($condition->type === "all_bricks_forms") {
                        $bricks_conditions[] = $condition;
                    }

                    if ($condition->type === "all_bricks_forms_confirmations") {
                        $bricks_conditions[] = $condition;
                    }

                    if ($condition->type == "bricks_form" && $condition->formId == $bricks_form_id) {
                        $bricks_conditions[] = $condition;
                    }

                    if ($condition->type == "bricks_form_confirmation" && $condition->formId == $bricks_form_id) {
                        $bricks_conditions[] = $condition;
                    }
                }
            }
        }

        return $bricks_conditions;
    }

    /**
     * Get Email Subject
     * @param $template_key
     * @param $default_subject
     * @return mixed
     */
    public function get_subject($template_key, $default_subject)
    {
        $template = $this->get_template($template_key);

        if (!isset($template) || $template === false) {
            return $default_subject;
        }

        if (!isset($template->canvas)) {
            return $default_subject;
        }

        if ($template->canvas->emailSubject) {
            return $template->canvas->emailSubject;
        }

        return $default_subject;
    }

    /**
     * Get Email Send To
     * @param $template_key
     * @param $default_to
     * @return mixed
     */
    public function get_send_to($template_key, $default_to)
    {

        $template = $this->get_template($template_key);

        if (!isset($template) || $template === false) {
            return $default_to;
        }

        if (!isset($template->canvas)) {
            return $default_to;
        }

        if ($template->canvas->emailSendTo) {
            return $template->canvas->emailSendTo;
        }

        return $default_to;
    }

    /**
     * Get Email From Name
     * @param $template_key
     * @param $default_from_name
     * @return mixed
     */
    public function get_from($template_key)
    {

        $template = $this->get_template($template_key);

        if (!isset($template) || $template === false) {
            return false;
        }

        if (!isset($template->canvas)) {
            return false;
        }

        $fromName = isset($template->canvas->emailFromName) ? $template->canvas->emailFromName : false;
        $fromEmail = isset($template->canvas->emailFromEmail) ? $template->canvas->emailFromEmail : false;

        // Format: From: Name <email>
        if ($fromName && $fromEmail) {
            return 'From: ' . $fromName . '<' . $fromEmail . '>';
        }

        return false;
    }

    public function get_reply_to($template_key)
    {

        $template = $this->get_template($template_key);

        if (!isset($template) || $template === false) {
            return false;
        }

        if (!isset($template->canvas)) {
            return false;
        }

        $replyToName = isset($template->canvas->emailReplyToName) ? $template->canvas->emailReplyToName : false;
        $replyToEmail = isset($template->canvas->emailReplyToEmail) ? $template->canvas->emailReplyToEmail : false;

        // Format: From: Name <email>
        if ($replyToName && $replyToEmail) {
            return 'Reply-To: ' . $replyToName . '<' . $replyToEmail . '>';
        }

        return false;
    }

    /**
     * Set Email Content Type
     * @param $content_type
     * @return string
     */
    public function set_content_type($content_type)
    {
        return 'text/html';
    }

    /**
     * Run Template: all
     * @param $args
     * @return mixed
     */
    public function run_all($args)
    {
        return $this->apply_template('all', $args);
    }

    public function run_woocommerce_email($args, $template_key, $testmode = false)
    {
        $args = $this->cleanup_woocommerce_email_content($args, $template_key);
        return $this->apply_template($template_key, $args, $testmode);
    }

    public function cleanup_woocommerce_email_content($email, $template_key = 'woocommerce_all')
    {
        if (!function_exists('file_get_html')) {
            require_once BRICKSFORGE_VENDOR . '/simple_html_dom.php';
        }

        $template = $this->get_template($template_key);

        if ($template === null) {
            return $email;
        }

        $settings = (object)$template->canvas;

        $hide_addresses = isset($settings->woocommerce->hideAddresses) ? $settings->woocommerce->hideAddresses : false;
        $hide_order_table = isset($settings->woocommerce->hideOrderTable) ? $settings->woocommerce->hideOrderTable : false;

        // Load the email content into the HTML parser
        $html = str_get_html($email['message']);

        $templateBody = $html->find('#template_body', 0);

        // Define the allowed style properties (only padding and margin properties are allowed)
        $allowedProperties = ['padding-top', 'padding-bottom', 'margin-top', 'margin-bottom', 'margin'];

        // Remove inline styles and specific attributes from all elements
        foreach ($html->find('*[style]') as $element) {
            // Parse the existing inline style to keep only allowed properties
            $existingStyle = $element->getAttribute('style');
            $parsedStyle = $this->parse_wc_inline_styles($existingStyle, $allowedProperties);

            $element->removeAttribute('bgcolor');
            $element->removeAttribute('align');

            // Set the parsed style as the new inline style
            $element->setAttribute('style', $parsedStyle);
        }

        // Set some styles
        $bodyContentInner = $html->find('#body_content_inner', 0);
        $bodyContentInner->setAttribute('style', "font-size: {$settings->fontSize}px;");

        // Also for parent td
        $bodyContentInnerParent = $bodyContentInner->parent();
        $bodyContentInnerParent->setAttribute('style', 'padding: 0;');

        // Give the parent table of .order_item (which is a tr) the class .brf-woocommerce-table
        $orderItem = $html->find('.order_item', 0);
        if ($orderItem) {
            $orderItemTable = $orderItem->parent()->parent(); // Go up to table from tr
            $orderItemTable->class = $orderItemTable->class ? $orderItemTable->class . ' brf-woocommerce-table brf-woocommerce-main-table' : 'brf-woocommerce-table brf-woocommerce-main-table';
        }

        // If an element with the class .woocommerce-order-downloads__title exists, go to the parent div and give the first table the class .brf-woocommerce-downloads-table
        $downloadsTitle = $html->find('.woocommerce-order-downloads__title', 0);

        if ($downloadsTitle) {
            $downloadsTable = $downloadsTitle->parent()->find('table', 0);
            if ($downloadsTable) {
                $downloadsTable->class = $downloadsTable->class ? $downloadsTable->class . ' brf-woocommerce-downloads-table brf-woocommerce-table' : 'brf-woocommerce-downloads-table brf-woocommerce-table';
            }
        }

        // Hide Addresses if needed
        if ($hide_addresses === true) {
            // Remove table with ID #addresses
            $addressesTable = $html->find('#addresses', 0);
            if ($addressesTable) {
                $addressesTable->outertext = '';
            }
        }

        // Hide Order Table if needed
        if ($hide_order_table === true) {
            // Remove table with Class .brf-woocommerce-table
            $orderItemsTable = $html->find('.brf-woocommerce-table', 0);
            if ($orderItemsTable) {
                $orderItemsTable->outertext = '';
            }
        }

        // Get the cleaned HTML content
        $cleanedHtml = $templateBody->innertext;

        // Update the email message with the cleaned HTML content
        $email['message'] = $cleanedHtml;

        // Clear the HTML parser object to free up memory
        $html->clear();

        // Add the class .brf-woocommerce to the first table element
        $email['message'] = preg_replace('/<table/', '<table class="brf-woocommerce-content"', $email['message'], 1);

        return $email;
    }


    private function parse_wc_inline_styles($style, $allowedProperties)
    {
        // Split the inline style properties
        $properties = explode(';', $style);
        $parsedProperties = [];

        foreach ($properties as $property) {
            $property = trim($property);
            if (empty($property)) {
                continue;
            }

            // Extract the property name and value
            list($name, $value) = explode(':', $property, 2);

            // Only keep the allowed properties
            $lowercaseName = strtolower(trim($name));
            if (in_array($lowercaseName, $allowedProperties)) {
                $parsedProperties[] = "$name:$value";
            }
        }

        // Join the parsed properties back into a string
        $parsedStyle = implode(';', $parsedProperties);

        return $parsedStyle;
    }

    /**
     * Run Template: all_bricks_forms
     * @param $args
     * @return mixed
     */
    public function run_bricks_all($mail, $form_id, $bricks_fields, $post_id, $confirmation = false)
    {
        $is_confirmation = false;

        // If the mail header contains X-Confirmation-Email, we know it's a confirmation email
        if (isset($mail['headers'])) {
            $headers = $mail['headers'];

            if (is_array($headers)) {
                $headers = implode("\r\n", $headers);
            }

            if (strpos($headers, 'X-Confirmation-Email:') !== false) {
                $is_confirmation = true;
            }
        }

        // Deprecated
        if (strpos($mail['message'], '[brf_confirmation]') !== false) {
            $is_confirmation = true;
            $mail['message'] = str_replace('[brf_confirmation]', '', $mail['message']);
        }

        if ($is_confirmation) {
            return $this->apply_template('all_bricks_forms_confirmations', $mail);
        }

        return $this->apply_template('all_bricks_forms', $mail);
    }

    /**
     * Run Template: bricks_form
     * @param $args
     * @return mixed
     */
    public function run_bricks_single($mail, $form_id, $bricks_fields, $post_id, $confirmation = false)
    {
        $mail['bricks_form_id'] = $form_id;
        $is_confirmation = false;

        // If the mail header contains X-Confirmation-Email, we know it's a confirmation email
        if (isset($mail['headers'])) {
            $headers = $mail['headers'];

            if (is_array($headers)) {
                $headers = implode("\r\n", $headers);
            }

            if (strpos($headers, 'X-Confirmation-Email:') !== false) {
                $is_confirmation = true;
            }
        }

        // Legacy
        if (strpos($mail['message'], '[brf_confirmation]') !== false) {
            $is_confirmation = true;
            $mail['message'] = str_replace('[brf_confirmation]', '', $mail['message']);
        }

        if ($is_confirmation) {
            return $this->apply_template('bricks_form_confirmation', $mail);
        }

        return $this->apply_template('bricks_form', $mail);
    }

    /**
     * Run Template: wp_new_user_notification_email
     * @param $args
     * @return mixed
     */
    public function run_wp_new_user_notification_email($email, $user, $blogname)
    {
        $email['user'] = $user;
        return $this->apply_template('wp_new_user_notification_email', $email);
    }

    /**
     * Run Template: retrieve_password_notification_email
     * @param $args
     * @return mixed
     */
    public function run_retrieve_password_notification_email($email)
    {
        return $this->apply_template('retrieve_password_notification_email', $email);
    }

    /**
     * Run Template: wp_new_user_notification_email_admin
     * @param $args
     * @return mixed
     */
    public function run_wp_new_user_notification_email_admin($email, $user, $blogname)
    {
        $email['user'] = $user;
        return $this->apply_template('wp_new_user_notification_email_admin', $email);
    }

    /**
     * Run Template: new_user_email_content
     * @param $args
     * @return mixed
     */
    public function run_new_user_email_content($email_text, $new_user_email)
    {
        $email = ['message' => $email_text, 'to' => null, 'newemail' => $new_user_email['newemail'], 'hash' => $new_user_email['hash']];
        $result = $this->apply_template('new_user_email_content', $email);
        return $result['message'];
    }

    /**
     * Run Template: email_change_email
     * @param $args
     * @return mixed
     */
    public function run_email_change_email($email, $user, $userdata)
    {
        $email['user'] = $user;

        // New Email
        $new_email = $userdata['user_email'];

        if (isset($new_email)) {
            $email['newemail'] = $new_email;
        }

        return $this->apply_template('email_change_email', $email);
    }

    /**
     * Run Template: password_change_email
     * @param $args
     * @return mixed
     */
    public function run_password_change_email($email, $user, $userdata)
    {
        $email['user'] = $user;
        return $this->apply_template('password_change_email', $email);
    }

    /**
     * Run Template: user_request_action_email_content
     * @param $args
     * @return mixed
     */
    public function run_user_request_action_email_content($email_text, $request_data)
    {

        $email = ['message' => $email_text, 'to' => null, 'confirm_url' => $request_data['confirm_url'], 'request_type' => $request_data['request']->action_name];
        $result = $this->apply_template('user_request_action_email_content', $email);
        return $result['message'];
    }

    /**
     * Run Template: wp_privacy_personal_data_email_content
     * @param $args
     * @return mixed
     */
    public function run_wp_privacy_personal_data_email_content($email_text, $request_id, $email_data)
    {
        $email = ['message' => $email_text, 'to' => null, 'expiration' => $email_data['expiration'], 'export_file_url' => $email_data['export_file_url']];
        $result = $this->apply_template('wp_privacy_personal_data_email_content', $email);
        return $result['message'];
    }

    /**
     * Run Template: wp_password_change_notification_email
     * @param $args
     * @return mixed
     */
    public function run_wp_password_change_notification_email($email,  $user, $blogname)
    {
        $email['user'] = $user;
        return $this->apply_template('wp_password_change_notification_email', $email);
    }

    /**
     * Run Template: new_admin_email_content
     * @param $args
     * @return mixed
     */
    public function run_new_admin_email_content($email_text, $new_admin_email)
    {
        $email = ['message' => $email_text, 'to' => null, 'newadminemail' => $new_admin_email['newemail'], 'hash' => $new_admin_email['hash']];
        $result = $this->apply_template('new_admin_email_content', $email);
        return $result['message'];
    }

    /**
     * Run Template: user_request_confirmed_email_content
     * @param $args
     * @return mixed
     */
    public function run_user_request_confirmed_email_content($email_text, $email_data)
    {
        $email = ['message' => $email_text, 'to' => $email_data['user_email'], 'request_type' => $email_data['request']->action_name, 'manage_url' => $email_data['manage_url']];
        $result = $this->apply_template('user_request_confirmed_email_content', $email);
        return $result['message'];
    }

    /**
     * Run Template: comment_moderation_text
     * @param $args
     * @return mixed
     */
    public function run_comment_moderation_text($notify_message, $comment_id)
    {
        $comment = get_comment($comment_id);
        $post = get_post($comment->comment_post_ID);

        $comment_author = $comment->comment_author;
        $comment_author_ip = $comment->comment_author_IP;
        $comment_author_domain = @gethostbyaddr($comment_author_ip);
        $comment_author_email = $comment->comment_author_email;
        $comment_author_url = $comment->comment_author_url;
        $comment_content = $comment->comment_content;

        $approve_comment_url = add_query_arg(array(
            'action' => 'approve',
            'c' => $comment_id
        ), get_edit_comment_link($comment_id));

        $trash_comment_url = add_query_arg(array(
            'action' => 'trash',
            'c' => $comment_id
        ), get_edit_comment_link($comment_id));

        $spam_comment_url = add_query_arg(array(
            'action' => 'spam',
            'c' => $comment_id
        ), get_edit_comment_link($comment_id));

        $delete_comment_url = add_query_arg(array(
            'action' => 'delete',
            'c' => $comment_id
        ), get_edit_comment_link($comment_id));


        $comment_count = get_comments_number($comment->comment_post_ID);
        $moderation_panel_url = admin_url('comment.php');
        $post_title = $post->post_title;

        $admin_url = admin_url('comment.php');

        $email =
            [
                'message' => $notify_message,
                'to' => null,
                'comment_author' => $comment_author,
                'comment_author_ip' => $comment_author_ip,
                'comment_author_domain' => $comment_author_domain,
                'comment_author_email' => $comment_author_email,
                'comment_author_url' => $comment_author_url,
                'comment_content' => $comment_content,
                'approve_comment_url' => esc_url($admin_url . '' . $approve_comment_url),
                'trash_comment_url' => esc_url($admin_url . '' . $trash_comment_url),
                'spam_comment_url' => esc_url($admin_url . '' . $spam_comment_url),
                'delete_comment_url' => esc_url($admin_url . '' . $delete_comment_url),
                'comment_count' => $comment_count,
                'moderation_panel_url' => $moderation_panel_url,
                'post_title' => $post_title
            ];
        $result = $this->apply_template('comment_moderation_text', $email);
        return $result['message'];
    }

    /**
     * Run Template: comment_notification_text
     * @param $args
     * @return mixed
     */
    public function run_comment_notification_text($notify_message, $comment_id)
    {
        $comment = get_comment($comment_id);
        $post = get_post($comment->comment_post_ID);

        $comment_author = $comment->comment_author;
        $comment_author_ip = $comment->comment_author_IP;
        $comment_author_domain = @gethostbyaddr($comment_author_ip);
        $comment_author_email = $comment->comment_author_email;
        $comment_author_url = $comment->comment_author_url;
        $comment_content = $comment->comment_content;

        $approve_comment_url = add_query_arg(array(
            'action' => 'approve',
            'c' => $comment_id
        ), get_edit_comment_link($comment_id));

        $trash_comment_url = add_query_arg(array(
            'action' => 'trash',
            'c' => $comment_id
        ), get_edit_comment_link($comment_id));

        $spam_comment_url = add_query_arg(array(
            'action' => 'spam',
            'c' => $comment_id
        ), get_edit_comment_link($comment_id));

        $delete_comment_url = add_query_arg(array(
            'action' => 'delete',
            'c' => $comment_id
        ), get_edit_comment_link($comment_id));

        $comment_count = get_comments_number($comment->comment_post_ID);
        $moderation_panel_url = admin_url('comment.php');
        $post_title = $post->post_title;

        $admin_url = admin_url('comment.php');

        $email =
            [
                'message' => $notify_message,
                'to' => null,
                'comment_author' => $comment_author,
                'comment_author_ip' => $comment_author_ip,
                'comment_author_domain' => $comment_author_domain,
                'comment_author_email' => $comment_author_email,
                'comment_author_url' => $comment_author_url,
                'comment_content' => $comment_content,
                'approve_comment_url' => esc_url($admin_url . '' . $approve_comment_url),
                'trash_comment_url' => esc_url($admin_url . '' . $trash_comment_url),
                'spam_comment_url' => esc_url($admin_url . '' . $spam_comment_url),
                'delete_comment_url' => esc_url($admin_url . '' . $delete_comment_url),
                'comment_count' => $comment_count,
                'moderation_panel_url' => $moderation_panel_url,
                'post_title' => $post_title
            ];

        $result = $this->apply_template('comment_notification_text', $email);
        return $result['message'];
    }

    /**
     * Apply the template
     * @param $template_key
     * @param $email
     * @param bool $test_mode
     * @return mixed
     */
    private function apply_template($template_key, $email, $test_mode = false)
    {
        $initial_message = $email['message'];
        $order_id = 0; // WooCommerce

        $template = $this->get_template($template_key, $email);

        if ($template === null) {
            return $email;
        }

        $settings = $template->canvas;

        if ($settings === false) {
            return $email;
        }

        if (strpos($initial_message, 'data-brf-email') !== false) {
            return $email;
        }

        // $initial_message = wpautop($initial_message, true);

        // If there is a header Brf-Order-Id (header is string), save it into a variable. But only if WooCommerce is active.
        if (class_exists('WooCommerce')) {

            // If $email['headers'] is an array, we need to implode it
            if (is_array($email['headers'])) {
                $email['headers'] = implode("\r\n", $email['headers']);
            }

            $regex = '/Brf-Order-Id: ([0-9]+)/';
            preg_match($regex, $email['headers'], $matches);
            if (isset($matches[1])) {
                $order_id = $matches[1];
                $this->wc_order_id = $order_id;
            }
        }

        if (isset($email['headers'])) {
            if (!is_array($email['headers'])) {
                // Convert headers string to array if necessary
                $email['headers'] = explode("\r\n", $email['headers']);
            }

            // Filter out any existing 'Content-Type' headers (case-insensitive)
            $email['headers'] = array_filter($email['headers'], function ($header) {
                return stripos($header, 'Content-Type:') !== 0;
            });

            // Append the new 'Content-Type' header
            $email['headers'][] = 'Content-Type: text/html; charset=UTF-8';
        } else {
            // If no headers are set, initialize the headers array with the 'Content-Type' header
            $email['headers'] = ['Content-Type: text/html; charset=UTF-8'];
        }

        $email['message'] = $this->build($initial_message, $settings, $template_key);

        $extra_vars = [];
        if (isset($email['newemail'])) $extra_vars['newemail'] = $email['newemail'];
        if (isset($email['newadminemail'])) $extra_vars['newadminemail'] = $email['newadminemail'];
        if (isset($email['hash'])) $extra_vars['hash'] = $email['hash'];
        if (isset($email['confirm_url'])) $extra_vars['confirm_url'] = $email['confirm_url'];
        if (isset($email['request_type'])) $extra_vars['request_type'] = $email['request_type'];
        if (isset($email['expiration'])) $extra_vars['expiration'] = $email['expiration'];
        if (isset($email['export_file_url'])) $extra_vars['export_file_url'] = $email['export_file_url'];
        if (isset($email['user'])) $extra_vars['user'] = $email['user'];
        if (isset($email['manage_url'])) $extra_vars['manage_url'] = $email['manage_url'];
        if (isset($email['comment_author'])) $extra_vars['comment_author'] = $email['comment_author'];
        if (isset($email['comment_author_ip'])) $extra_vars['comment_author_ip'] = $email['comment_author_ip'];
        if (isset($email['comment_author_domain'])) $extra_vars['comment_author_domain'] = $email['comment_author_domain'];
        if (isset($email['comment_author_email'])) $extra_vars['comment_author_email'] = $email['comment_author_email'];
        if (isset($email['comment_author_url'])) $extra_vars['comment_author_url'] = $email['comment_author_url'];
        if (isset($email['comment_content'])) $extra_vars['comment_content'] = $email['comment_content'];
        if (isset($email['approve_comment_url'])) $extra_vars['approve_comment_url'] = $email['approve_comment_url'];
        if (isset($email['trash_comment_url'])) $extra_vars['trash_comment_url'] = $email['trash_comment_url'];
        if (isset($email['spam_comment_url'])) $extra_vars['spam_comment_url'] = $email['spam_comment_url'];
        if (isset($email['delete_comment_url'])) $extra_vars['delete_comment_url'] = $email['delete_comment_url'];
        if (isset($email['comment_count'])) $extra_vars['comment_count'] = $email['comment_count'];
        if (isset($email['moderation_panel_url'])) $extra_vars['moderation_panel_url'] = $email['moderation_panel_url'];
        if (isset($email['post_title'])) $extra_vars['post_title'] = $email['post_title'];

        if (class_exists('WooCommerce')) {
            $extra_vars['order_id'] = $order_id;
        }

        if ($test_mode === false) {
            $email['message'] = $this->handle_vars($email['message'], $email['to'], $extra_vars);
        } else {
            $email['message'] = $this->handle_vars_testmode($email['message'], $email['to'], $extra_vars);
        }

        return $email;
    }

    /**
     * Handle all available variables
     * @param $email_content
     * @param null $user_email
     * @param array $extra_vars
     * @return mixed
     */
    public function handle_vars($email_content, $user_email = null, $extra_vars = [])
    {
        $user = wp_get_current_user();

        if (isset($user_email) && gettype($user_email) === 'string') {
            $user = get_user_by('email', $user_email);
        }

        if (isset($extra_vars['user'])) {
            $user = $extra_vars['user'];
        }

        if ($user && !$user_email) {
            $user_email = $user->user_email;
        }

        // If the email content not contains action=rp&key=, we know it's not a password reset email. So we can generate a new password reset key for the variables.
        $key = false;

        if (strpos($email_content, 'action=rp&key=') === false) {
            $key = get_password_reset_key($user);
        }

        $user_login = isset($user) && $user ? $user->user_login : null;

        $user_ip = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'];

        $new_email_hash = isset($extra_vars['hash']) ? $extra_vars['hash'] : null;
        $new_email_url = network_site_url("wp-admin/profile.php?newuseremail=$new_email_hash", "login");

        $new_admin_email_hash = isset($extra_vars['hash']) ? $extra_vars['hash'] : null;
        $new_admin_email_url = network_site_url("wp-admin/options.php?adminhash=$new_admin_email_hash", "login");

        $confirm_url = isset($extra_vars['confirm_url']) ? $extra_vars['confirm_url'] : null;
        $request_type = isset($extra_vars['request_type']) ? $extra_vars['request_type'] : null;
        $expiration = isset($extra_vars['expiration']) ? $extra_vars['expiration'] : null;
        $export_file_url = isset($extra_vars['export_file_url']) ? $extra_vars['export_file_url'] : null;

        $manage_url = isset($extra_vars['manage_url']) ? $extra_vars['manage_url'] : null;

        if ($expiration) {
            $expiration = date_i18n(get_option('date_format'), $expiration);
        }

        $reset_password_url = "";

        if ($key && is_string($key) && !is_wp_error($key)) {
            $reset_password_url = $user_login ? network_site_url("wp-login.php?action=rp&key=" . $key . "&login=" . rawurlencode($user_login), 'login') : '';
        }

        // For WooCommerce, we need to get the custom endpoint URL for building the reset password URL
        if (class_exists('WooCommerce')) {
            $wc_lostpassword_url = wc_lostpassword_url();

            if ($wc_lostpassword_url) {
                $reset_password_url = add_query_arg(array(
                    'action' => 'rp',
                    'key' => $key,
                    'login' => rawurlencode($user_login),
                ), $wc_lostpassword_url);
            }
        }

        // Consider Bricks Custom Auth Pages
        if (class_exists('\Bricks\Database')) {
            $bricks_reset_password_page = \Bricks\Database::get_setting('reset_password_page') ? \Bricks\Database::get_setting('reset_password_page') : false;

            if ($bricks_reset_password_page) {
                $reset_password_url = add_query_arg(array(
                    'action' => 'rp',
                    'key' => $key,
                    'login' => rawurlencode($user_login),
                ), get_permalink($bricks_reset_password_page));
            }
        }

        if ($reset_password_url) {
            $reset_password_url = html_entity_decode($reset_password_url);
        }

        $allowed_vars = [
            "site_title" => get_bloginfo('name'),
            "site_url" => get_bloginfo('url'),
            "site_description" => get_bloginfo('description'),
            'admin_email' => get_option('admin_email'),
            'home_url' => home_url(),
            'login_url' => wp_login_url(),
            'current_year' => date('Y'),

            // User Object Data
            'user' => isset($user) ? $user : '',
            'user_name' => $user ? $user->display_name : '',
            'user_email' => $user_email ? $user_email : '',
            'user_first_name' => $user ? $user->first_name : '',
            'user_last_name' => $user ? $user->last_name : '',
            'reset_password_url' => $reset_password_url,
            'new_password_url' => $reset_password_url,
            'user_ip' => $user_ip,
            'new_user_email' => isset($extra_vars['newemail']) ? $extra_vars['newemail'] : '',
            'new_user_email_url' => $new_email_url,
            'confirm_url' => $confirm_url,
            'request_type' => $request_type,
            'expiration' => $expiration,
            'export_file_url' => $export_file_url,


            'new_admin_email' => isset($extra_vars['newadminemail']) ? $extra_vars['newadminemail'] : '',
            'new_admin_email_url' => $new_admin_email_url,
            'manage_url' => $manage_url,
            'comment_author' => isset($extra_vars['comment_author']) ? $extra_vars['comment_author'] : '',
            'comment_author_ip' => isset($extra_vars['comment_author_ip']) ? $extra_vars['comment_author_ip'] : '',
            'comment_author_domain' => isset($extra_vars['comment_author_domain']) ? $extra_vars['comment_author_domain'] : '',
            'comment_author_email' => isset($extra_vars['comment_author_email']) ? $extra_vars['comment_author_email'] : '',
            'comment_author_url' => isset($extra_vars['comment_author_url']) ? $extra_vars['comment_author_url'] : '',
            'comment_content' => isset($extra_vars['comment_content']) ? $extra_vars['comment_content'] : '',
            'approve_comment_url' => isset($extra_vars['approve_comment_url']) ? $extra_vars['approve_comment_url'] : '',
            'trash_comment_url' => isset($extra_vars['trash_comment_url']) ? $extra_vars['trash_comment_url'] : '',
            'spam_comment_url' => isset($extra_vars['spam_comment_url']) ? $extra_vars['spam_comment_url'] : '',
            'delete_comment_url' => isset($extra_vars['delete_comment_url']) ? $extra_vars['delete_comment_url'] : '',
            'comment_count' => isset($extra_vars['comment_count']) ? $extra_vars['comment_count'] : '',
            'moderation_panel_url' => isset($extra_vars['moderation_panel_url']) ? $extra_vars['moderation_panel_url'] : '',
            'post_title' => isset($extra_vars['post_title']) ? $extra_vars['post_title'] : '',
        ];

        if (class_exists('WooCommerce')) {
            $order;
            if ($extra_vars['order_id'] && $extra_vars['order_id'] != 0) {
                $order = wc_get_order($extra_vars['order_id']);
                $order_user_info = get_userdata($order->get_user_id());

                if (isset($order) && is_object($order)) {

                    // Save the original locale
                    $original_locale = determine_locale();

                    // Set the locale to the site's default locale
                    switch_to_locale(get_locale());

                    $timestamp = $order->get_date_created()->getTimestamp();

                    $order_fees_total = 0;
                    $order_fees_total_formatted = '0';

                    if (method_exists($order, 'get_fees')) {
                        $fees = $order->get_fees();
                        if (is_array($fees)) {
                            foreach ($fees as $fee) {
                                $order_fees_total += $fee->get_total();
                            }
                        }

                        $order_fees_total = wc_format_decimal($order_fees_total);
                        $order_fees_total_formatted = wc_price($order_fees_total);
                    }

                    $wc_vars = [
                        'wc_order' => (object)$order->get_data(),
                        'wc_order_id' => $order->get_id(),
                        'wc_order_number' => $order->get_order_number(),
                        'wc_order_currency' => $order->get_currency(),
                        'wc_order_currency_symbol' => get_woocommerce_currency_symbol($order->get_currency()),
                        'wc_order_date' => date_i18n(get_option('date_format'), $timestamp),
                        'wc_order_date_hour' => $order->get_date_created()->date('H'),
                        'wc_order_date_minute' => $order->get_date_created()->date('i'),
                        'wc_order_date_second' => $order->get_date_created()->date('s'),
                        'wc_order_status' => $order->get_status(),
                        'wc_order_total' => $order->get_total(),
                        'wc_order_total_formatted' => wc_price($order->get_total()),
                        'wc_order_subtotal' => $order->get_subtotal(),
                        'wc_order_subtotal_formatted' => wc_price($order->get_subtotal()),
                        'wc_order_tax' => $order->get_total_tax(),
                        'wc_order_tax_formatted' => wc_price($order->get_total_tax()),
                        'wc_order_discount' => $order->get_total_discount(),
                        'wc_order_discount_formatted' => wc_price($order->get_total_discount()),
                        'wc_customer_user_id' => $order->get_user_id(),
                        'wc_customer_userdata' => isset($order_user_info->data) ? $order_user_info->data : null,
                        'wc_billing_email' => $order->get_billing_email(),
                        'wc_billing_first_name' => $order->get_billing_first_name(),
                        'wc_billing_last_name' => $order->get_billing_last_name(),
                        'wc_billing_company' => $order->get_billing_company(),
                        'wc_billing_address_1' => $order->get_billing_address_1(),
                        'wc_billing_address_2' => $order->get_billing_address_2(),
                        'wc_billing_city' => $order->get_billing_city(),
                        'wc_billing_state' => $order->get_billing_state(),
                        'wc_billing_postcode' => $order->get_billing_postcode(),
                        'wc_billing_country' => $order->get_billing_country(),
                        'wc_billing_phone' => $order->get_billing_phone(),
                        'wc_billing_payment_method' => $order->get_payment_method(),
                        'wc_shipping_first_name' => $order->get_shipping_first_name(),
                        'wc_shipping_last_name' => $order->get_shipping_last_name(),
                        'wc_shipping_company' => $order->get_shipping_company(),
                        'wc_shipping_address_1' => $order->get_shipping_address_1(),
                        'wc_shipping_address_2' => $order->get_shipping_address_2(),
                        'wc_shipping_city' => $order->get_shipping_city(),
                        'wc_shipping_state' => $order->get_shipping_state(),
                        'wc_shipping_postcode' => $order->get_shipping_postcode(),
                        'wc_shipping_country' => $order->get_shipping_country(),
                        'wc_shipping_phone' => $order->get_shipping_phone(),
                        'wc_shipping_method' => $order->get_shipping_method(),
                        'wc_customer_note' => $order->get_customer_note(),
                        'wc_shipping_cost' => $order->get_shipping_total(),
                        'wc_shipping_tax' => $order->get_shipping_tax(),
                        'wc_shipping_cost_formatted' => $order->get_shipping_to_display(),
                        'wc_order_refunded_amount' => $order->get_total_refunded(),
                        'wc_order_refunded_amount_formatted' => wc_price($order->get_total_refunded()),
                        'wc_order_fees_total' => $order_fees_total,
                        'wc_order_fees_total_formatted' => $order_fees_total_formatted,
                    ];

                    // Get Items
                    $items = $order->get_items();

                    $wc_vars['wc_order_items'] = $items;
                    $wc_vars['wc_order_items_count'] = count($items);
                }


                // If is Customer Note, we need to get the data manually via order ID
                $is_customer_note = isset($_POST['action']) && $_POST['action'] === 'woocommerce_add_order_note' ? true : false;

                if ($is_customer_note) {
                    $wc_vars['wc_customer_note'] = $_POST['note'];
                }

                $allowed_vars = array_merge($allowed_vars, $wc_vars);
            }
        }

        // WP Filter bricksforge/email_designer/allowed_vars
        $allowed_vars = apply_filters('bricksforge/email_designer/allowed_vars', $allowed_vars);

        // Add these vars to $this->global_twig_vars

        foreach ($allowed_vars as $key => $value) {
            $this->global_twig_vars[$key] = $value;
        }

        /* preg_match_all('/\{\{\s*([\w\s]+?)\s*\}\}/', $email_content, $matches);

        if (isset($matches[1]) && is_array($matches[1])) {
            foreach ($matches[1] as $match) {
                if (isset($allowed_vars[$match])) {
                    $this->global_twig_vars[$match] = $allowed_vars[$match];
                }
            }
        } */

        return $email_content;
    }

    /**
     * Handle variables for testmode
     * @param $email_content
     * @param null $user_email
     * @param array $extra_vars
     * @return mixed
     */
    public function handle_vars_testmode($email_content, $user_email = null, $extra_vars = [])
    {

        $test_user_object = new \stdClass();
        $test_user_object->display_name = 'John Doe';
        $test_user_object->first_name = 'John';
        $test_user_object->last_name = 'Doe';
        $test_user_object->email = 'john@doe.com';
        $test_user_object->user_login = 'john';
        $test_user_object->roles = ['administrator'];
        $test_user_object->user_registered = '2021-01-01 00:00:00';
        $test_user_object->user_status = 0;
        $test_user_object->user_url = 'https://www.example.com';
        $test_user_object->user_registered = '2021-01-01 00:00:00';
        $test_user_object->user_status = 0;
        $test_user_object->user_url = 'https://www.example.com';


        $allowed_vars = [
            "site_title" => get_bloginfo('name'),
            "site_url" => get_bloginfo('url'),
            "site_description" => get_bloginfo('description'),
            'admin_email' => get_option('admin_email'),
            'home_url' => home_url(),
            'login_url' => wp_login_url(),
            'current_year' => date('Y'),
            'user' => $test_user_object,
            'user_name' => $test_user_object->display_name,
            'user_email' => $test_user_object->email,
            'user_first_name' => $test_user_object->first_name,
            'user_last_name' => $test_user_object->last_name,
            'reset_password_url' => 'https://www.example.com/wp-login.php?action=rp&key=owejfowiejfiowejoijwefi',
            'new_password_url' => 'https://www.example.com/wp-login.php?action=rp&key=owejfowiejfiowejoijwefi',
            'user_ip' => '192.158.1.38',
            'new_user_email' => 'john@doe.com',
            'new_user_email_url' => 'https://www.example.com/wp-login.php?action=rp&key=owejfowiejfiowejoijwefi',
            'confirm_url' => 'https://www.example.com/wp-login.php?action=rp&key=owejfowiejfiowejoijwefi',
            'request_type' => 'Export Data',
            'expiration' => '2028-01-01',
            'export_file_url' => 'https://www.example.com/path-to-file.zip',
            'new_admin_email' => 'john@doe.com',
            'new_admin_email_url' => 'https://www.example.com/path',
            'manage_url' => 'https://www.example.com/path',
            'comment_author' => 'John Doe',
            'comment_author_ip' => '192.158.1.38',
            'comment_author_domain' => 'example.com',
            'comment_author_email' => 'john@doe.com',
            'comment_author_url' => 'https://www.example.com',
            'comment_content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam euismod, nisl eget aliquam ultricies, nunc nisl aliquet nunc, quis aliquam nis',
            'approve_comment_url' => 'https://www.example.com/approve-path',
            'trash_comment_url' => 'https://www.example.com/trash-path',
            'spam_comment_url' => 'https://www.example.com/spam-path',
            'delete_comment_url' => 'https://www.example.com/delete-path',
            'comment_count' => '5',
            'moderation_panel_url' => 'https://www.example.com/moderation-path',
            'post_title' => 'I am a Post Title'
        ];

        if (class_exists('WooCommerce')) {
            $order;

            // Check if there is a wc order. If yes, get the last one
            $orders = wc_get_orders([
                'limit' => 1,
                'orderby' => 'date',
                'order' => 'DESC'
            ]);

            if (isset($orders[0])) {
                $order = $orders[0];

                // Save the original locale
                $original_locale = determine_locale();

                // Set the locale to the site's default locale
                switch_to_locale(get_locale());

                $timestamp = $order->get_date_created()->getTimestamp();

                $order_fees_total = 0;
                $order_fees_total_formatted = '0';

                if (method_exists($order, 'get_fees')) {
                    $fees = $order->get_fees();
                    if (is_array($fees)) {
                        foreach ($fees as $fee) {
                            $order_fees_total += $fee->get_total();
                        }
                    }

                    $order_fees_total = wc_format_decimal($order_fees_total);
                    $order_fees_total_formatted = wc_price($order_fees_total);
                }

                $wc_vars = [
                    'wc_order' => (object)$order->get_data(),
                    'wc_order_id' => $order->get_id(),
                    'wc_order_number' => $order->get_order_number(),
                    'wc_order_currency' => $order->get_currency(),
                    'wc_order_currency_symbol' => get_woocommerce_currency_symbol($order->get_currency()),
                    'wc_order_date' => date_i18n(get_option('date_format'), $timestamp),
                    'wc_order_date_hour' => $order->get_date_created()->date('H'),
                    'wc_order_date_minute' => $order->get_date_created()->date('i'),
                    'wc_order_date_second' => $order->get_date_created()->date('s'),
                    'wc_order_status' => $order->get_status(),
                    'wc_order_total' => $order->get_total(),
                    'wc_order_total_formatted' => wc_price($order->get_total()),
                    'wc_order_subtotal' => $order->get_subtotal(),
                    'wc_order_subtotal_formatted' => wc_price($order->get_subtotal()),
                    'wc_order_tax' => $order->get_total_tax(),
                    'wc_order_tax_formatted' => wc_price($order->get_total_tax()),
                    'wc_order_discount' => $order->get_total_discount(),
                    'wc_order_discount_formatted' => wc_price($order->get_total_discount()),
                    'wc_customer_user_id' => $order->get_user_id(),
                    'wc_billing_email' => $order->get_billing_email(),
                    'wc_billing_first_name' => $order->get_billing_first_name(),
                    'wc_billing_last_name' => $order->get_billing_last_name(),
                    'wc_billing_company' => $order->get_billing_company(),
                    'wc_billing_address_1' => $order->get_billing_address_1(),
                    'wc_billing_address_2' => $order->get_billing_address_2(),
                    'wc_billing_city' => $order->get_billing_city(),
                    'wc_billing_state' => $order->get_billing_state(),
                    'wc_billing_postcode' => $order->get_billing_postcode(),
                    'wc_billing_country' => $order->get_billing_country(),
                    'wc_billing_phone' => $order->get_billing_phone(),
                    'wc_billing_payment_method' => $order->get_payment_method(),
                    'wc_shipping_first_name' => $order->get_shipping_first_name(),
                    'wc_shipping_last_name' => $order->get_shipping_last_name(),
                    'wc_shipping_company' => $order->get_shipping_company(),
                    'wc_shipping_address_1' => $order->get_shipping_address_1(),
                    'wc_shipping_address_2' => $order->get_shipping_address_2(),
                    'wc_shipping_city' => $order->get_shipping_city(),
                    'wc_shipping_state' => $order->get_shipping_state(),
                    'wc_shipping_postcode' => $order->get_shipping_postcode(),
                    'wc_shipping_country' => $order->get_shipping_country(),
                    'wc_shipping_phone' => $order->get_shipping_phone(),
                    'wc_shipping_method' => $order->get_shipping_method(),
                    'wc_customer_note' => $order->get_customer_note(),
                    'wc_shipping_cost' => $order->get_shipping_total(),
                    'wc_shipping_tax' => $order->get_shipping_tax(),
                    'wc_shipping_cost_formatted' => $order->get_shipping_to_display(),
                    'wc_order_refunded_amount' => $order->get_total_refunded(),
                    'wc_order_refunded_amount_formatted' => wc_price($order->get_total_refunded()),
                    'wc_order_fees_total' => $order_fees_total,
                    'wc_order_fees_total_formatted' => $order_fees_total_formatted,
                ];

                switch_to_locale($original_locale);

                // Get Items
                $items = $order->get_items();

                $wc_vars['wc_order_items'] = $items;
                $wc_vars['wc_order_items_count'] = count($items);

                // If is Customer Note, we need to get the data manually via order ID
                $is_customer_note = isset($_POST['action']) && $_POST['action'] === 'woocommerce_add_order_note' ? true : false;

                if ($is_customer_note) {
                    $wc_vars['wc_customer_note'] = $_POST['note'];
                }

                $allowed_vars = array_merge($allowed_vars, $wc_vars);
            }
        }

        // WP Filter bricksforge/email_designer/allowed_vars
        $allowed_vars = apply_filters('bricksforge/email_designer/allowed_vars', $allowed_vars);

        // add to global twig vars as above
        foreach ($allowed_vars as $key => $value) {
            $this->global_twig_vars[$key] = $value;
        }

        return $email_content;
    }

    /**
     * Get template by type
     * @param $type
     * @param $mail
     * @return null
     */
    public function get_template($type, $mail = null)
    {
        $templates = get_option('brf_email_designer_data') ? get_option('brf_email_designer_data') : false;
        $template = null;

        foreach ($templates as $temp) {

            // In some situations the first argument is the ID. For example when using the wp_mail wrapper.
            if ($temp->id === $type) {
                return $temp;
            }

            if (isset($temp->conditions) && is_array($temp->conditions)) {
                foreach ($temp->conditions as $condition) {

                    if ($condition->type === $type) {

                        if ($condition->type === 'bricks_form_confirmation' || $condition->type === 'bricks_form') {
                            // We need to check if the form ID is the same as the one in the condition
                            $condition_form_id = isset($condition->formId) ? $condition->formId : null;
                            $mail_form_id = isset($mail['bricks_form_id']) ? $mail['bricks_form_id'] : null;

                            if (!isset($mail_form_id)) {
                                $mail_form_id = isset($_POST['formId']) ? $_POST['formId'] : null;
                            }

                            if ($condition_form_id === null || $mail_form_id === null || $condition->type !== $type) {
                                continue;
                            }

                            // The $template is the one with the same form ID as the one in the condition
                            $template = $condition_form_id === $mail_form_id ? $temp : null;

                            if ($template !== null) {
                                return $template;
                            }
                        }

                        $template = $temp;
                        break;
                    }
                }
            }
        }

        if ($template === null) {
            return null;
        }

        return $template;
    }

    /**
     * Build the email template
     * @param $initial_message
     * @param $settings
     * @param $template_key
     * @return string
     */
    public function build($initial_message, $settings, $template_key)
    {

        $background_color = isset($settings->backgroundColor) ? $this->render_color($settings->backgroundColor) : '';

        $content_background_color = isset($settings->contentBackgroundColor) ? $this->render_color($settings->contentBackgroundColor) : '';
        $text_color = isset($settings->textColor) ? $this->render_color($settings->textColor) : '';
        $content_width = isset($settings->contentWidth) ? (int)$settings->contentWidth . 'px' : 600 . 'px';
        $content_width_plain = isset($settings->contentWidth) ? (int)$settings->contentWidth : 600;
        $font_size = isset($settings->fontSize) ? (int)$settings->fontSize . 'px' : 16 . 'px';
        $content_padding_top = isset($settings->contentPaddingTop) ? (int)$settings->contentPaddingTop . 'px' : 20 . 'px';
        $content_padding_bottom = isset($settings->contentPaddingBottom) ? (int)$settings->contentPaddingBottom . 'px' : 20 . 'px';

        $wordpress_lang = get_locale();

        $content = "###BRFTEMPLATE:{$template_key}###";
        $content .= "<!DOCTYPE html>";
        $content .= "<html lang='{$wordpress_lang}' style='height: 100%;'>";
        $content .= "<head>";
        $content .= "<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />";
        $content .= $this->get_global_styles($settings, $template_key);
        $content .= $this->add_outlook_styles();
        $content .= "</head>";
        $content .= "<body style='height: 100%; margin: 0; padding: 0; background: {$background_color}; font-family: Arial, sans-serif'>";
        $content .= $this->get_custom_css($settings);
        $content .= "<table data-brf-email width='100%' height='100%' cellpadding='0' cellspacing='0' role='presentation' style='background: {$background_color};'>";
        $content .= "<tr><td valign='top'>";
        $content .= "<table class='email-content-wrapper' bgcolor='{$this->render_color($content_background_color)}' width='{$content_width_plain}' align='center' cellpadding='0' cellspacing='0' role='presentation' style='overflow: hidden; background: {$content_background_color}; color: {$text_color}; font-size: {$font_size}; box-sizing:border-box; height: 100%; margin: 0 auto; width: {$content_width}; padding-top: {$content_padding_top}; padding-bottom: {$content_padding_top};'><tr><td valign='top'>";

        // For each $settings->elements, add the element to the message
        if (isset($settings->elements) && is_array($settings->elements)) {
            foreach ($settings->elements as $element) {

                if (isset($element->parent) && $element->parent !== null) {
                    continue;
                }

                $content .= $this->render_individual_element($element, $settings, $initial_message);
            }
        }

        $content .= "</td></tr></table>";
        $content .= "</td></tr>";
        $content .= "</table>";
        $content .= "</body>";
        $content .= "</html>";

        return $content;
    }

    /**
     * Get Global Styles
     * @param $settings
     * @return string
     */
    public function get_global_styles($settings, $template_key)
    {
        $heading_font_family = isset($settings->headingFontFamily) ? $settings->headingFontFamily : 'Arial, sans-serif';
        $heading_font_weight = isset($settings->headingFontWeight) ? $settings->headingFontWeight : 400;
        $text_font_family = isset($settings->textFontFamily) ? $settings->textFontFamily : 'Arial, sans-serif';
        $button_font_family = isset($settings->buttonFontFamily) ? $settings->buttonFontFamily : 'Arial, sans-serif';
        $link_color = isset($settings->linkColor) ? $this->render_color($settings->linkColor) : '';
        $link_hover_color = isset($settings->linkHoverColor) ? $this->render_color($settings->linkHoverColor) : '';

        $font_size = isset($settings->fontSize) ? (int)$settings->fontSize . 'px' : '';
        $text_color = isset($settings->textColor) ? $this->render_color($settings->textColor) : '';
        $font_size_h1 = isset($settings->fontSizeH1) ? (int)$settings->fontSizeH1 . 'px' : '';
        $font_size_h2 = isset($settings->fontSizeH2) ? (int)$settings->fontSizeH2 . 'px' : '';
        $font_size_h3 = isset($settings->fontSizeH3) ? (int)$settings->fontSizeH3 . 'px' : '';
        $heading_text_color = isset($settings->headingTextColor) ? $this->render_color($settings->headingTextColor) : '';

        $woocommerce_table_border_style = isset($settings->woocommerce->tableBorderStyle) ? $settings->woocommerce->tableBorderStyle : '';
        $woocommerce_table_border_color = isset($settings->woocommerce->tableBorderColor) ? $this->render_color($settings->woocommerce->tableBorderColor) : '';
        $woocommerce_table_heading_color = isset($settings->woocommerce->tableHeadingColor) ? $this->render_color($settings->woocommerce->tableHeadingColor) : '';
        $woocommerce_table_heading_background_color = isset($settings->woocommerce->tableHeadingBackgroundColor) ? $this->render_color($settings->woocommerce->tableHeadingBackgroundColor) : '';
        $woocommerce_table_text_color = isset($settings->woocommerce->tableTextColor) ? $this->render_color($settings->woocommerce->tableTextColor) : '';
        $woocommerce_table_border_width_top = isset($settings->woocommerce->tableBorderTop) ? (int)$settings->woocommerce->tableBorderTop . 'px' : '';
        $woocommerce_table_border_width_bottom = isset($settings->woocommerce->tableBorderBottom) ? (int)$settings->woocommerce->tableBorderBottom . 'px' : '';
        $woocommerce_table_border_width_left = isset($settings->woocommerce->tableBorderLeft) ? (int)$settings->woocommerce->tableBorderLeft . 'px' : '';
        $woocommerce_table_border_width_right = isset($settings->woocommerce->tableBorderRight) ? (int)$settings->woocommerce->tableBorderRight . 'px' : '';
        $woocommerce_table_background_color = isset($settings->woocommerce->tableBackgroundColor) ? $this->render_color($settings->woocommerce->tableBackgroundColor) : '';
        $woocommerce_table_padding = isset($settings->woocommerce->tablePadding) ? (int)$settings->woocommerce->tablePadding . 'px' : 0 . 'px';
        $woocommerce_table_inner_padding = isset($settings->woocommerce->tableInnerPadding) ? (int)$settings->woocommerce->tableInnerPadding . 'px' : 0 . 'px';
        $woocommerce_remove_border_on_parent_table = isset($settings->woocommerce->removeBorderOnParentTable) ? $settings->woocommerce->removeBorderOnParentTable : false;
        $woocommerce_table_first_column_width = isset($settings->woocommerce->tableFirstColumnWidth) ? (int)$settings->woocommerce->tableFirstColumnWidth . '%' : '55%';
        $woocommerce_table_second_column_width = isset($settings->woocommerce->tableSecondColumnWidth) ? (int)$settings->woocommerce->tableSecondColumnWidth . '%' : '25%';
        $woocommerce_table_third_column_width = isset($settings->woocommerce->tableThirdColumnWidth) ? (int)$settings->woocommerce->tableThirdColumnWidth . '%' : '20%';

        // Responsive
        $responsive_content_padding_top = isset($settings->responsive->contentPaddingTop) ? (int)$settings->responsive->contentPaddingTop . 'px' : $settings->contentPaddingTop . 'px';
        $responsive_content_padding_bottom = isset($settings->responsive->contentPaddingBottom) ? (int)$settings->responsive->contentPaddingBottom . 'px' : $settings->contentPaddingBottom . 'px';
        $responsive_content_padding_left = isset($settings->responsive->contentPaddingLeft) ? (int)$settings->responsive->contentPaddingLeft . 'px' : $settings->contentPaddingLeft . 'px';
        $responsive_content_padding_right = isset($settings->responsive->contentPaddingRight) ? (int)$settings->responsive->contentPaddingRight . 'px' : $settings->contentPaddingRight . 'px';
        $responsive_column_alignment = isset($settings->responsive->columnAlign) ? $settings->responsive->columnAlign : 'left';
        $responsive_column_gap = isset($settings->responsive->columnGap) ? (int)$settings->responsive->columnGap . 'px' : 5 . 'px';
        $responsive_font_size = isset($settings->responsive->fontSize) ? (int)$settings->responsive->fontSize . 'px' : $font_size . 'px';
        $responsive_font_size_h1 = isset($settings->responsive->fontSizeH1) ? (int)$settings->responsive->fontSizeH1 . 'px' : $font_size_h1 . 'px';
        $responsive_font_size_h2 = isset($settings->responsive->fontSizeH2) ? (int)$settings->responsive->fontSizeH2 . 'px' : $font_size_h2 . 'px';
        $responsive_font_size_h3 = isset($settings->responsive->fontSizeH3) ? (int)$settings->responsive->fontSizeH3 . 'px' : $font_size_h3 . 'px';

        $responsive_woocommerce_font_size = isset($settings->responsive->woocommerce->tableFontSize) ? (int)$settings->responsive->woocommerce->tableFontSize . 'px' : $font_size . 'px';
        $responsive_woocmmerce_first_column_width = isset($settings->responsive->woocommerce->tableFirstColumnWidth) ? (int)$settings->responsive->woocommerce->tableFirstColumnWidth . '%' : '55%';
        $responsive_woocmmerce_second_column_width = isset($settings->responsive->woocommerce->tableSecondColumnWidth) ? (int)$settings->responsive->woocommerce->tableSecondColumnWidth . '%' : '25%';
        $responsive_woocmmerce_third_column_width = isset($settings->responsive->woocommerce->tableThirdColumnWidth) ? (int)$settings->responsive->woocommerce->tableThirdColumnWidth . '%' : '20%';

        $styles = [];

        $styles[] = "* { box-sizing: border-box; }";
        $styles[] = "body { margin: 0; padding: 0; font-family: {$text_font_family}; }";
        $styles[] = ".email-content-wrapper a { color: {$link_color}; text-decoration: none; }";
        $styles[] = "p {margin: 0; padding: 0; margin-bottom: 0; font-family: {$text_font_family}; font-size: {$font_size}; }";
        $styles[] = "h1,h2,h3,h4,h5,h6 {padding: 0; margin: 0; font-family: {$heading_font_family}; font-weight: {$heading_font_weight}; color: {$heading_text_color};}";
        $styles[] = "img {max-width: 100%;}";
        $styles[] = ".email-content-wrapper h1 {font-size: {$font_size_h1};}";
        $styles[] = ".email-content-wrapper h2 {font-size: {$font_size_h2};}";
        $styles[] = ".email-content-wrapper h3 {font-size: {$font_size_h3};}";
        $styles[] = ".email-content-wrapper a:hover { color: {$link_hover_color}; }";
        $styles[] = ".button-default { font-family: {$button_font_family}; }";
        $styles[] = ".button-default:hover { background-color: {$this->render_color($settings->buttonBackgroundHoverColor)}!important; color: {$this->render_color($settings->buttonTextHoverColor)}!important; }";
        $styles[] = "table { table-layout: fixed; } p {hyphens: auto; word-break: break-word;}";
        $styles[] = ".column-child td, .column-child table {padding-left: 0!important; padding-right: 0!important;}";

        // WooCommerce
        if (strpos($template_key, 'woocommerce') !== false) {
            $styles[] = ".brf-woocommerce-table, .brf-woocommerce-table th, .brf-woocommerce-table td, .brf-woocommerce-table thead, .brf-woocommerce-table tfoot {border-color: {$woocommerce_table_border_color}; border-style: {$woocommerce_table_border_style}; text-align:left}";
            $styles[] = ".brf-woocommerce-table th {color: {$woocommerce_table_heading_color}; background-color: {$woocommerce_table_heading_background_color};}";
            $styles[] = ".brf-woocommerce-content ul {list-style: none; padding-left: 0; margin-left: 0;}";
            $styles[] = ".brf-woocommerce-table th, .brf-woocommerce-table td {padding: {$woocommerce_table_inner_padding};}";
            $styles[] = ".brf-woocommerce-table th:first-child {width: {$woocommerce_table_first_column_width};}";
            $styles[] = ".brf-woocommerce-table th:nth-child(2) {width: {$woocommerce_table_second_column_width};}";
            $styles[] = ".brf-woocommerce-table th:nth-child(3) {width: {$woocommerce_table_third_column_width};}";

            // Table Text Color
            if ($woocommerce_table_text_color) {
                $styles[] = ".brf-woocommerce-table {color: {$woocommerce_table_text_color};}";
            }

            // Table Background
            if ($woocommerce_table_background_color) {
                $styles[] = ".brf-woocommerce-table {background-color: {$woocommerce_table_background_color};}";
            }

            // Table Padding
            if ($woocommerce_table_padding) {
                $styles[] = ".brf-woocommerce-table {padding: {$woocommerce_table_padding};}";
            }

            // Table Border
            if ($woocommerce_table_border_width_bottom && $woocommerce_table_border_width_bottom > 0) {
                $styles[] = ".brf-woocommerce-table tfoot tr:last-child td, .brf-woocommerce-table tfoot tr:last-child th {border-bottom-width: 0;}";
            }

            if ($woocommerce_table_border_width_top) {
                $styles[] = ".brf-woocommerce-table th, .brf-woocommerce-table td {border-top-width: {$woocommerce_table_border_width_top};}";
            }

            if ($woocommerce_table_border_width_bottom) {
                $styles[] = ".brf-woocommerce-table th, .brf-woocommerce-table td {border-bottom-width: {$woocommerce_table_border_width_bottom};}";
            }

            if ($woocommerce_table_border_width_left) {
                $styles[] = ".brf-woocommerce-table th, .brf-woocommerce-table td {border-left-width: {$woocommerce_table_border_width_left};}";
            }

            if ($woocommerce_table_border_width_right) {
                $styles[] = ".brf-woocommerce-table th, .brf-woocommerce-table td {border-right-width: {$woocommerce_table_border_width_right};}";
            }

            // Remove Border on Parent Table
            if ($woocommerce_remove_border_on_parent_table) {
                $styles[] = ".brf-woocommerce-table {border: none!important;}";
            }
        }

        // Media Query. If the browser width is less than $settings->contentWidth, then the content width to 100%
        $styles[] = "@media only screen and (max-width: {$settings->contentWidth}px) { 
            .email-content-wrapper { 
                width: 100% !important; 
                padding-top: {$responsive_content_padding_top}!important;
                padding-bottom: {$responsive_content_padding_bottom}!important;
            }  
            .email-content-wrapper img.img-fullwidth { 
                max-width: 100%!important; 
            }
            .column-wrapper-full { 
                width: 100%!important; 
                margin-right: inherit!important 
            }
            .column-child {
                display: block!important;
                width: 100%!important;
                padding-bottom: {$responsive_column_gap}!important;
            }
            .column-child * {
                text-align: {$responsive_column_alignment}!important;
            }
            p {
                width: 100% !important;
            }
            .email-content-wrapper h1 {font-size: {$responsive_font_size_h1}!important;}
            .email-content-wrapper h2 {font-size: {$responsive_font_size_h2}!important;}
            .email-content-wrapper h3 {font-size: {$responsive_font_size_h3}!important;}
            .email-content-wrapper, .email-content-wrapper p {font-size: {$responsive_font_size}!important;}
        }";

        // Woocommerce
        if (strpos($template_key, 'woocommerce') !== false) {
            $styles[] = "@media only screen and (max-width: {$settings->contentWidth}px) { 
                table.brf-woocommerce-table { 
                    font-size: {$responsive_woocommerce_font_size}!important; 
                }
                table.brf-woocommerce-table th:first-child { 
                    width: {$responsive_woocmmerce_first_column_width}!important; 
                }
                table.brf-woocommerce-table th:nth-child(2) { 
                    width: {$responsive_woocmmerce_second_column_width}!important; 
                }
                table.brf-woocommerce-table th:nth-child(3) { 
                    width: {$responsive_woocmmerce_third_column_width}!important; 
                }
            }";
        }

        return '<style>' . implode(' ', $styles) . '</style>';
    }

    public function add_outlook_styles()
    {
        return '<!--[if (gte mso 9)|(IE)]>
        <style type="text/css">
        table {
        border-collapse: collapse;
        border-spacing: 0;
        mso-table-lspace: 0pt !important;
        mso-table-rspace: 0pt !important; }
        </style>
        <![endif]-->';
    }

    /**
     * Get Custom User Css
     * @param $settings
     * @return string
     */
    public function get_custom_css($settings)
    {
        $custom_css = isset($settings->customCss) ? $settings->customCss : false;

        if ($custom_css) {
            // Sanitize the CSS code
            $sanitized_css = $this->sanitize_css($custom_css);

            return "<style>{$sanitized_css}</style>";
        }

        return '';
    }

    /**
     * Sanitize CSS code
     * @param string $css
     * @return string
     */
    function sanitize_css($css)
    {
        // Remove any JavaScript code or HTML tags
        $sanitized_css = preg_replace('/(javascript:|<\s*script|<\s*\/\s*script|<\s*|\s*>)|\s*expression\s*\(/i', '', $css);

        return $sanitized_css;
    }

    /**
     * Get Children Elements for Column Type
     * @param $parent_id
     * @param $elements
     * @param null $parent_index
     * @return array
     */
    public function get_children_elements($parent_id, $elements, $parent_index = null)
    {
        $children = [];

        foreach ($elements as $element) {
            if (isset($element->parent) && $element->parent === $parent_id) {
                if ($parent_index === null || (isset($element->parentIndex) && $element->parentIndex == $parent_index)) {
                    $children[] = $element;
                }
            }
        }

        // Order the children by parentIndex

        usort($children, function ($a, $b) {
            return $a->index - $b->index;
        });

        return $children;
    }

    /**
     * Get Parent Element for Column types
     * @param $parent_id
     * @return bool
     */
    public function get_parent($parent_id)
    {
        $templates = get_option('brf_email_designer_data');

        if (!$templates) {
            return false;
        }

        foreach ($templates as $template) {
            if ($template->canvas->elements) {
                foreach ($template->canvas->elements as $element) {
                    if ($element->id === $parent_id) {
                        return $element;
                    }
                }
            }
        }

        return false;
    }

    /**
     * Render Element
     * @param $element
     * @param $settings
     * @param $initial_message
     * @return string
     */
    public function render_element($element, $settings, $initial_message)
    {
        return $this->render_individual_element($element, $settings, $initial_message);
    }

    /**
     * Sanitize HTML
     * @param $html
     * @return string
     */
    public function sanitize_html($html)
    {

        // Temporary add display to safe_style_css
        add_filter('safe_style_css', function ($styles) {
            $styles[] = 'display';
            return $styles;
        });

        if (!$html) {
            return '';
        }

        $allowed_html = wp_kses_allowed_html('post');

        $clean_html = wp_kses($html, $allowed_html);

        // Remove display from safe_style_css
        remove_filter('safe_style_css', function ($styles) {
            $styles = array_diff($styles, ['display']);
            return $styles;
        });

        return $clean_html;
    }

    /**
     * Render Individual Element
     * @param $element
     * @param $settings
     * @param $initial_message
     * @return string
     */
    public function render_individual_element($element, $settings, $initial_message)
    {
        $content_padding_left = isset($settings->contentPaddingLeft) ? (int)$settings->contentPaddingLeft . 'px' : 20 . 'px';
        $content_padding_right = isset($settings->contentPaddingRight) ? (int)$settings->contentPaddingRight . 'px' : 20 . 'px';
        $content_padding_top = isset($settings->contentPaddingTop) ? (int)$settings->contentPaddingTop . 'px' : 20 . 'px';
        $content_padding_bottom = isset($settings->contentPaddingBottom) ? (int)$settings->contentPaddingBottom . 'px' : 20 . 'px';

        $args_message = "";

        switch ($element->type) {
            case 'content':
                $args_message .= "<table cellpadding='0' cellspacing='0' role='presentation' {$this->get_styles('content',$element,$settings)}><tr><td valign='top'  align='{$element->settings->textAlign->value}'>";
                $args_message .= $initial_message;
                $args_message .= "</td></tr></table>";
                break;
            case 'h1':
                $args_message .= "<table cellpadding='0' cellspacing='0' role='presentation' style='width: 100%; padding-top: {$this->get_setting("spacingTop",$element, 'px')}; padding-bottom: {$this->get_setting("spacingBottom",$element, 'px')}; padding-left: {$content_padding_left}; padding-right: {$content_padding_right}';><tr><td valign='top'  align='{$element->settings->textAlign->value}'>";
                $args_message .= "<h1 {$this->get_styles('h1',$element,$settings)}>";
                $args_message .= bricks_render_dynamic_data($element->settings->text->value);
                $args_message .= "</h1>";
                $args_message .= "</td></tr></table>";
                break;
            case 'h2':
                $args_message .= "<table cellpadding='0' cellspacing='0' role='presentation' style='width: 100%; padding-top: {$this->get_setting("spacingTop",$element, 'px')}; padding-bottom: {$this->get_setting("spacingBottom",$element, 'px')}; padding-left: {$content_padding_left}; padding-right: {$content_padding_right}';><tr><td valign='top'  align='{$element->settings->textAlign->value}'>";
                $args_message .= "<h2 {$this->get_styles('h2',$element,$settings)}>";
                $args_message .= bricks_render_dynamic_data($element->settings->text->value);
                $args_message .= "</h2>";
                $args_message .= "</td></tr></table>";
                break;
            case 'h3':
                $args_message .= "<table cellpadding='0' cellspacing='0' role='presentation' style='width: 100%; padding-top: {$this->get_setting("spacingTop",$element, 'px')}; padding-bottom: {$this->get_setting("spacingBottom",$element, 'px')}; padding-left: {$content_padding_left}; padding-right: {$content_padding_right}';><tr><td valign='top'  align='{$element->settings->textAlign->value}'>";
                $args_message .= "<h3 {$this->get_styles('h3',$element,$settings)}>";
                $args_message .= bricks_render_dynamic_data($element->settings->text->value);
                $args_message .= "</h3>";
                $args_message .= "</td></tr></table>";
                break;
            case 'text':
                $args_message .= "<table cellpadding='0' cellspacing='0' role='presentation' style='width: 100%;'><tr><td valign='top'>";
                $args_message .= "<table cellpadding='0' cellspacing='0' role='presentation' {$this->get_styles('text',$element,$settings)}>";
                $args_message .= "<tr><td valign='top' align='{$element->settings->textAlign->value}'>";
                $args_message .= bricks_render_dynamic_data($element->settings->text->value);
                $args_message .= "</td></tr>";
                $args_message .= "</table>";
                $args_message .= "</td></tr></table>";
                break;
            case 'spacing':
                $args_message .= "<table cellpadding='0' cellspacing='0' role='presentation' {$this->get_styles('spacing',$element,$settings)}><tr><td valign='top'>";
                $args_message .= "</td></tr></table>";
                break;
            case 'dynamicData':
                $post_id = isset($element->settings->postId->value) ? $element->settings->postId->value : get_the_ID();

                $args_message .= "<table cellpadding='0' cellspacing='0' role='presentation' style='width: 100%;'><tr><td valign='top' align='{$element->settings->textAlign->value}'>";
                $args_message .= "<p {$this->get_styles('dynamicData',$element,$settings)}>";
                $args_message .= bricks_render_dynamic_data($element->settings->text->value, $post_id);
                $args_message .= "</p>";
                $args_message .= "</td></tr></table>";
                break;
            case 'html':
                $args_message .= "<table cellpadding='0' cellspacing='0' role='presentation' {$this->get_styles('html',$element,$settings)}><tr><td valign='top'>";
                $args_message .= $this->sanitize_html($element->settings->html->value);
                $args_message .= "</td></tr></table>";
                break;
            case 'image':
                $image_url = isset($element->settings->image->value) && !empty($element->settings->image->value) ? $element->settings->image->value : 'https://placehold.co/600x600';

                // If image is set to fullwith and we need to show a placeholder, we want it smaller (600x200)
                if (isset($element->settings->fullWidth) && $element->settings->fullWidth->value && $image_url === 'https://placehold.co/600x600') {
                    $image_url = 'https://placehold.co/600x200';
                }

                if (isset($element->settings->imageUrl->value) && !empty($element->settings->imageUrl->value)) {
                    $image_url = $element->settings->imageUrl->value;
                }

                $image_link = isset($element->settings->link->value) ? $element->settings->link->value : '';
                $is_fullwidth = isset($element->settings->fullWidth) && $element->settings->fullWidth->value;
                $image_text_align = isset($element->settings->textAlign->value) ? $element->settings->textAlign->value : 'center';

                if ($image_link !== '') {

                    // Outlook
                    $args_message .= "<!--[if mso]>";
                    $args_message .= "<table cellpadding='0' cellspacing='0' role='presentation' style='width: 100%;'><tr><td valign='top' align='{$image_text_align}' {$this->get_styles('imageContainerOutlook',$element,$settings)}>";
                    $args_message .= "<a href='{$image_link}' {$this->get_styles('imageWrapper',$element,$settings)}" . ($is_fullwidth ? " class='img-fullwidth'" : "") . ">";
                    $args_message .= "<img alt='Image' width='{$this->get_setting('width',$element)}' src='{$image_url}' {$this->get_styles('image',$element,$settings)}"  . ($is_fullwidth ? " class='img-fullwidth'" : "") . ">";
                    $args_message .= "</a>";
                    $args_message .= "</td></tr></table>";
                    $args_message .= "<![endif]-->";

                    // Default
                    $args_message .= "<!--[if !mso]><!-->";
                    $args_message .= "<table cellpadding='0' cellspacing='0' role='presentation' style='width: 100%;'><tr><td valign='top' align='{$image_text_align}' {$this->get_styles('imageContainer',$element,$settings)}>";
                    $args_message .= "<a href='{$image_link}' {$this->get_styles('imageWrapper',$element,$settings)}" . ($is_fullwidth ? " class='img-fullwidth'" : "") . ">";
                    $args_message .= "<img alt='Image' width='{$this->get_setting('width',$element)}' src='{$image_url}' {$this->get_styles('image',$element,$settings)}"  . ($is_fullwidth ? " class='img-fullwidth'" : "") . ">";
                    $args_message .= "</a>";
                    $args_message .= "</td></tr></table>";
                    $args_message .= "<!--<![endif]-->";
                } else {

                    // Outlook
                    $args_message .= "<!--[if mso]>";
                    $args_message .= "<table cellpadding='0' cellspacing='0' role='presentation' style='width: 100%;'><tr><td valign='top' align='{$image_text_align}' {$this->get_styles('imageContainerOutlook',$element,$settings)}>";
                    $args_message .= "<img alt='Image' width='{$this->get_setting('width',$element)}' src='{$image_url}' {$this->get_styles('image',$element,$settings)}"  . ($is_fullwidth ? " class='img-fullwidth'" : "") . ">";
                    $args_message .= "</td></tr></table>";
                    $args_message .= "<![endif]-->";

                    // Default
                    $args_message .= "<!--[if !mso]><!-->";
                    $args_message .= "<table cellpadding='0' cellspacing='0' role='presentation' style='width: 100%;'><tr><td valign='top' align='{$image_text_align}' {$this->get_styles('imageContainer',$element,$settings)}>";
                    $args_message .= "<img alt='Image' width='{$this->get_setting('width',$element)}' src='{$image_url}' {$this->get_styles('image',$element,$settings)}"  . ($is_fullwidth ? " class='img-fullwidth'" : "") . ">";
                    $args_message .= "</td></tr></table>";
                    $args_message .= "<!--<![endif]-->";
                }

                break;
            case 'button':
                $button_text = isset($element->settings->text->value) ? $element->settings->text->value : '';
                $button_link = isset($element->settings->link->value) ? $element->settings->link->value : '';
                $text_align = isset($element->settings->textAlign->value) ? $element->settings->textAlign->value : 'center';
                $button_bgcolor = $this->render_color($settings->buttonBackgroundColor);
                $button_textcolor = $this->render_color($settings->buttonTextColor);
                $button_padding_top = isset($element->settings->spacingTop->value) ? $element->settings->spacingTop->value . 'px' : '0px';
                $button_padding_bottom = isset($element->settings->spacingBottom->value) ? $element->settings->spacingBottom->value . 'px' : '0px';

                $args_message .= "<table cellpadding='0' cellspacing='0' role='presentation' style='width: 100%'><tr><td valign='top' style='text-align: {$text_align}; padding: {$button_padding_top} {$content_padding_right} {$button_padding_bottom} {$content_padding_left};'>";
                $args_message .= "<!--[if mso]>";
                $args_message .= "<v:roundrect xmlns:v='urn:schemas-microsoft-com:vml' xmlns:w='urn:schemas-microsoft-com:office:word' href='{$button_link}' style='height:55px;v-text-anchor:middle;width:600px; mso-wrap-style:none; mso-fit-shape-to-text: true;' arcsize='10%' strokecolor='{$button_bgcolor}' fillcolor='{$button_bgcolor}'>";
                $args_message .= "<w:anchorlock/>";
                $args_message .= "<center style='color:{$button_textcolor};font-family:sans-serif;font-size:13px;mso-wrap-style:none;'>{$button_text}</center>";
                $args_message .= "</v:roundrect>";
                $args_message .= "<![endif]-->";
                $args_message .= "<!--[if !mso]><!-->";
                $args_message .= "<a class='button-default' href='{$button_link}' {$this->get_styles('button',$element,$settings)}>";
                $args_message .= $button_text;
                $args_message .= "</a>";
                $args_message .= "<!--<![endif]-->";
                $args_message .= "</td></tr></table>";
                break;

            case 'columns':
                $column_count = isset($element->settings->count) ? $element->settings->count->value : 1;
                $width_ratio = isset($element->settings->widthRatio) ? $element->settings->widthRatio->value : "50:50";
                $width_ratios = explode(":", $width_ratio);
                $column_widths = array();

                for ($i = 0; $i < $column_count; $i++) {
                    if (isset($width_ratios[$i])) {
                        $column_widths[$i] = $width_ratios[$i] . "%";
                    } else {
                        $column_widths[$i] = "50%";
                    }
                }

                $gap = isset($element->settings->gap) ? $element->settings->gap->value . 'px' : 0 . 'px';

                $args_message .= "<table class='" . ($element->settings->fullWidthWrapper->value == true ? "column-wrapper-full" : "column-wrapper") . "' width='100%' cellpadding='0' cellspacing='0' role='presentation' " . $this->get_styles('columns', $element, $settings) . ">";
                $args_message .= "<tr " . $this->get_styles('columnsInner', $element, $settings) . ">";

                $column_valign = isset($element->settings->verticalAlignment) ? $element->settings->verticalAlignment->value : 'top';

                for ($i = 0; $i < $column_count; $i++) {

                    if ($i == $column_count - 1) {
                        $gap = 0;
                    }

                    $column_padding_left = isset($element->settings->padding->value) && $element->settings->padding->value == 0 ? (int)$settings->contentPaddingLeft . 'px' : $element->settings->padding->value . 'px';
                    $column_padding_right = isset($element->settings->padding->value) && $element->settings->padding->value == 0 ? (int)$settings->contentPaddingRight . 'px' : $element->settings->padding->value . 'px';

                    // If is last column 
                    if ($i == $column_count - 1) {
                        // Add padding right as well
                        $args_message .= "<td class='column-child' valign='{$column_valign}' style='width: {$column_widths[$i]}; padding-right: {$column_padding_right}; padding-left: {$column_padding_left}; padding-top: {$element->settings->padding->value}px; padding-bottom: {$element->settings->padding->value}px;'>";
                    } else {
                        // Otherwise, we add gap here
                        $args_message .= "<td class='column-child' valign='{$column_valign}' style='width: {$column_widths[$i]}; padding-right: {$gap}; padding-left: {$column_padding_left}; padding-top: {$element->settings->padding->value}px; padding-bottom: {$element->settings->padding->value}px;'>";
                    }

                    $column_children_elements = $this->get_children_elements($element->id, $settings->elements, $i + 1);

                    foreach ($column_children_elements as $column_child_element) {
                        $column_content = $this->render_individual_element($column_child_element, $settings, $initial_message);
                        $args_message .= $column_content;
                    }

                    $args_message .= "</td>";
                }

                $args_message .= "</tr>";
                $args_message .= "</table>";
                break;
            case 'wcTable':
                $args_message .= "<table cellpadding='0' cellspacing='0' role='presentation' {$this->get_styles('wcTable',$element,$settings)}><tr><td valign='top'>";
                $args_message .= $this->get_wc_table($initial_message);
                $args_message .= "</td></tr></table>";
                break;
            case 'if':
                $args_message .= "{% if {$element->settings->condition->value} %}";
                break;
            case 'elseif':
                $args_message .= "{% elseif {$element->settings->condition->value} %}";
                break;
            case 'else':
                $args_message .= "{% else %}";
                break;
            case 'endif':
                $args_message .= "{% endif %}";
                break;
            case 'for':
                $args_message .= "{% for {$element->settings->rule->value} %}";
                break;
            case 'endfor':
                $args_message .= "{% endfor %}";
                break;
        }

        return $args_message;
    }

    /**
     * Get WooCommerce Table
     * @param $email_content
     * @return string
     */
    public function get_wc_table($email_content)
    {
        if (!function_exists('file_get_html')) {
            require_once BRICKSFORGE_VENDOR . '/simple_html_dom.php';
        }

        $html = str_get_html($email_content);

        $table = $html->find('table.brf-woocommerce-main-table', 0);
        $downloads_table = $html->find('table.brf-woocommerce-downloads-table', 0);

        $table = $table ? $table->outertext : '';
        $downloads_table = $downloads_table ? $downloads_table->outertext : '';

        // If there is a downloads table, include it before the main table
        $table = $downloads_table . $table;

        return $table;
    }

    /**
     * Get Single Styles for element. (Included to style attribute)
     * @param $element_type
     * @param $element
     * @param $settings
     * @return string
     */
    public function get_styles($element_type, $element, $settings)
    {

        $content_padding_left = isset($settings->contentPaddingLeft) ? (int)$settings->contentPaddingLeft . 'px' : 20 . 'px';
        $content_padding_right = isset($settings->contentPaddingRight) ? (int)$settings->contentPaddingRight . 'px' : 20 . 'px';

        $is_column_child = isset($element->parent) && $element->parent !== null;
        $parent = null;

        if ($is_column_child) {
            $parent = $this->get_parent($element->parent);
        }

        $custom_element_css = $this->get_setting("customCss", $element);

        $styles = [];

        switch ($element_type) {
            case 'content':
                $styles[] = "padding-top: {$this->get_setting("spacingTop",$element, 'px')}";
                $styles[] = "padding-bottom: {$this->get_setting("spacingBottom",$element, 'px')}";
                $styles[] = "padding-left: {$content_padding_left}";
                $styles[] = "padding-right: {$content_padding_right}";
                $styles[] = "text-align: {$this->get_setting("textAlign",$element)}";
                $styles[] = "font-size: {$settings->fontSize}px";
                $styles[] = "color: {$this->render_color($settings->textColor)}";
                $styles[] = "line-height: {$settings->lineHeightText}";
                $styles[] = "width: 100%";

                // If has Custom CSS, we include it
                if ($custom_element_css) {
                    $custom_css = str_replace(['{', '}'], '', $custom_element_css);
                    $styles[] = $custom_css;
                }

                break;
            case 'h1':
                $styles[] = "margin-block-end: 0";
                $styles[] = "font-size: {$settings->fontSizeH1}px";
                $styles[] = "color: {$this->render_color($settings->headingTextColor)}";
                $styles[] = "text-align: {$this->get_setting("textAlign",$element)}";
                $styles[] = "line-height: {$settings->lineHeightHeadings}";

                if ($is_column_child && $parent) {
                    $styles[] = "color: {$this->render_color($this->get_setting("headingColor",$parent))}!important";
                }

                // If has Custom CSS, we include it
                if ($custom_element_css) {
                    $custom_css = str_replace(['{', '}'], '', $custom_element_css);
                    $styles[] = $custom_css;
                }

                break;
            case 'h2':
                $styles[] = "font-size: {$settings->fontSizeH2}px";
                $styles[] = "color: {$this->render_color($settings->headingTextColor)}";
                $styles[] = "text-align: {$this->get_setting("textAlign",$element)}";
                $styles[] = "line-height: {$settings->lineHeightHeadings}";

                if ($is_column_child && $parent) {
                    $styles[] = "color: {$this->render_color($this->get_setting("headingColor",$parent))}!important";
                }

                // If has Custom CSS, we include it
                if ($custom_element_css) {
                    $custom_css = str_replace(['{', '}'], '', $custom_element_css);
                    $styles[] = $custom_css;
                }

                break;
            case 'h3':
                $styles[] = "font-size: {$settings->fontSizeH3}px";
                $styles[] = "color: {$this->render_color($settings->headingTextColor)}";
                $styles[] = "text-align: {$this->get_setting("textAlign",$element)}";
                $styles[] = "line-height: {$settings->lineHeightHeadings}";

                if ($is_column_child && $parent) {
                    $styles[] = "color: {$this->render_color($this->get_setting("headingColor",$parent))}!important";
                }

                // If has Custom CSS, we include it
                if ($custom_element_css) {
                    $custom_css = str_replace(['{', '}'], '', $custom_element_css);
                    $styles[] = $custom_css;
                }

                break;
            case 'text':
                $styles[] = "font-size: {$settings->fontSize}px";
                $styles[] = "color: {$this->render_color($settings->textColor)}";
                $styles[] = "padding-top: {$this->get_setting("spacingTop",$element, 'px')}";
                $styles[] = "padding-bottom: {$this->get_setting("spacingBottom",$element, 'px')}";
                $styles[] = "padding-left: {$content_padding_left}";
                $styles[] = "padding-right: {$content_padding_right}";
                $styles[] = "text-align: {$this->get_setting("textAlign",$element)}";
                $styles[] = "line-height: {$settings->lineHeightText}";
                $styles[] = "width: 100%";

                if ($is_column_child && $parent) {
                    $styles[] = "color: {$this->render_color($this->get_setting("textColor",$parent))}!important";
                }

                // If has Custom CSS, we include it
                if ($custom_element_css) {
                    $custom_css = str_replace(['{', '}'], '', $custom_element_css);
                    $styles[] = $custom_css;
                }

                break;
            case 'spacing':
                $styles[] = "height: {$this->get_setting("height",$element, 'px')}";
                $styles[] = "width: 100%";
                $styles[] = "padding-left: {$content_padding_left}";
                $styles[] = "padding-right: {$content_padding_right}";

                // If has Custom CSS, we include it
                if ($custom_element_css) {
                    $custom_css = str_replace(['{', '}'], '', $custom_element_css);
                    $styles[] = $custom_css;
                }

                break;
            case 'dynamicData':
                $styles[] = "padding-top: {$this->get_setting("spacingTop",$element, 'px')}";
                $styles[] = "padding-bottom: {$this->get_setting("spacingBottom",$element, 'px')}";
                $styles[] = "padding-left: {$content_padding_left}";
                $styles[] = "padding-right: {$content_padding_right}";
                $styles[] = "text-align: {$this->get_setting("textAlign",$element)}";

                // If has Custom CSS, we include it
                if ($custom_element_css) {
                    $custom_css = str_replace(['{', '}'], '', $custom_element_css);
                    $styles[] = $custom_css;
                }

                break;
            case 'html':
                $styles[] = "padding-top: {$this->get_setting("spacingTop",$element, 'px')}";
                $styles[] = "padding-bottom: {$this->get_setting("spacingBottom",$element, 'px')}";
                $styles[] = "padding-left: {$content_padding_left}";
                $styles[] = "padding-right: {$content_padding_right}";
                $styles[] = "width: 100%";

                // If has Custom CSS, we include it
                if ($custom_element_css) {
                    $custom_css = str_replace(['{', '}'], '', $custom_element_css);
                    $styles[] = $custom_css;
                }

                break;
            case 'imageContainer':
                $styles[] = "padding-top: {$this->get_setting("spacingTop",$element, 'px')}";
                $styles[] = "padding-bottom: {$this->get_setting("spacingBottom",$element, 'px')}";
                $styles[] = "padding-left: {$content_padding_left}";
                $styles[] = "padding-right: {$content_padding_right}";
                $styles[] = "line-height: 0";

                if ($this->get_setting("fullWidth", $element) == true) {
                    $styles[] = "padding-left: 0!important";
                    $styles[] = "padding-right: 0!important";
                }
                break;
            case 'imageContainerOutlook':
                $styles[] = "padding-top: {$this->get_setting("spacingTop",$element, 'px')}";
                $styles[] = "padding-bottom: {$this->get_setting("spacingBottom",$element, 'px')}";
                $styles[] = "padding-left: {$content_padding_left}";
                $styles[] = "padding-right: {$content_padding_right}";

                if ($this->get_setting("fullWidth", $element) == true) {
                    $styles[] = "padding-left: 0!important";
                    $styles[] = "padding-right: 0!important";
                }
                break;
            case 'imageWrapper':
                $styles[] = "text-align: {$this->get_setting("textAlign",$element)}";
                $styles[] = "display: block";

                break;
            case 'image':
                $styles[] = "width: {$this->get_setting("width",$element, 'px')}";

                $styles[] = "max-width: 100%";
                $styles[] = "box-sizing: border-box";

                if ($this->get_setting("fullWidth", $element) == true) {
                    $styles[] = "width: {$settings->contentWidth}px";
                    $styles[] = "max-width: {$settings->contentWidth}px";
                }

                // If has Custom CSS, we include it
                if ($custom_element_css) {
                    $custom_css = str_replace(['{', '}'], '', $custom_element_css);
                    $styles[] = $custom_css;
                }

                break;
            case 'button':
                $styles[] = "text-align: {$this->get_setting("textAlign",$element)}";
                $styles[] = "background-color: {$this->render_color($settings->buttonBackgroundColor)}";
                $styles[] = "color: {$this->render_color($settings->buttonTextColor)}";
                $styles[] = "border-radius: {$settings->buttonBorderRadius}px";
                $styles[] = "padding: {$settings->buttonPadding}";
                $styles[] = "text-decoration: none";
                $styles[] = "display: inline-block";
                $styles[] = "font-weight: {$settings->buttonFontWeight}";

                // If has Custom CSS, we include it
                if ($custom_element_css) {
                    $custom_css = str_replace(['{', '}'], '', $custom_element_css);
                    $styles[] = $custom_css;
                }

                break;
            case 'columns':
                $styles[] = "margin-top: {$this->get_setting("spacingTop",$element, 'px')}";
                $styles[] = "margin-bottom: {$this->get_setting("spacingBottom",$element, 'px')}";
                $styles[] = "padding-left: {$content_padding_left}";
                $styles[] = "padding-right: {$content_padding_right}";


                if ($this->get_setting("fullWidthWrapper", $element) == true) {
                    $styles[] = "width: {$settings->contentWidth}px";
                    $styles[] = "padding-left: 0!important";
                    $styles[] = "padding-right: 0!important";
                }

                // If has Custom CSS, we include it
                if ($custom_element_css) {
                    $custom_css = str_replace(['{', '}'], '', $custom_element_css);
                    $styles[] = $custom_css;
                }

                break;
            case 'columnsInner':
                $styles[] = "background-color: {$this->render_color($this->get_setting("backgroundColor",$element))}";
                break;
            case 'wcTable':
                $styles[] = "padding-top: {$this->get_setting("spacingTop",$element, 'px')}";
                $styles[] = "padding-bottom: {$this->get_setting("spacingBottom",$element, 'px')}";
                $styles[] = "padding-left: {$content_padding_left}";
                $styles[] = "padding-right: {$content_padding_right}";

                // If has Custom CSS, we include it
                if ($custom_element_css) {
                    $custom_css = str_replace(['{', '}'], '', $custom_element_css);
                    $styles[] = $custom_css;
                }

                break;
        }

        return "style='" . implode(";", $styles) . "'";
    }

    public function render_color($color)
    {
        // Store original color
        $original_color = $color;

        // Remove any leading '#'
        $color = ltrim($color, '#');

        // Check if color is 8-digit hex
        if (strlen($color) == 8) {
            // Remove the last two characters (alpha channel)
            $color = substr($color, 0, 6);
        }

        // Check if color is RGB or RGBA
        elseif (strpos($color, 'rgb') !== false) {
            // Remove 'rgb(' or 'rgba(', and ')' then convert to array
            $color = str_replace(array('rgb(', 'rgba(', ')'), '', $color);
            $parts = explode(',', $color);

            // Convert each part to hex
            for ($i = 0; $i < 3; $i++) { // Change loop iteration from 4 to 3
                // Convert to decimal and then to hex
                $parts[$i] = dechex(intval(trim($parts[$i])));

                // Ensure each part is two characters
                if (strlen($parts[$i]) == 1) {
                    $parts[$i] = '0' . $parts[$i];
                }
            }

            // Keep only the first three elements of the array (the RGB values)
            $parts = array_slice($parts, 0, 3);

            // Merge parts into one 6-digit hex color (excluding alpha)
            $color = implode('', $parts);
        }

        // Ensure color is 6 digits
        if (strlen($color) != 6 && strlen($color) != 8) {
            // If color format doesn't match, return the original color
            return $original_color;
        }

        // If is plain white, for example #000000, return 000001 for compatibility with Outlook
        if ($color == '000000') {
            $color = '000001';
        }

        // We do the same for white
        if ($color == 'ffffff') {
            $color = 'fffffe';
        }

        return '#' . $color;
    }

    /**
     * Get Specific Element Setting
     * @param $setting
     * @param $element
     * @param bool $unit
     * @return bool|string
     */
    public function get_setting($setting, $element, $unit = false)
    {
        if (!isset($element->settings)) {
            return false;
        }

        foreach ($element->settings as $key => $value) {
            if ($key === $setting) {
                $output = $value->value;

                if ($unit) {
                    $output .= $unit;
                }

                return $output;
            }
        }
    }

    /**
     * Send a test email
     * @return void
     */
    public function send_test_mail()
    {

        $template_key = $_POST['templateKey'];
        $is_woocommerce = false;

        if (!isset($template_key) || empty($template_key)) {
            wp_die();
        }

        if (strpos($template_key, 'woocommerce') !== false) {
            $is_woocommerce = true;
        }

        $headers = [
            'Content-Type: text/html; charset=UTF-8',
        ];

        if ($is_woocommerce) {
            $headers[] = 'Brf-Type: WooCommerce';
        }

        $email = [
            'subject' => '',
            'message' => $this->get_default_message($template_key),
            'headers' => $headers,
            'to' => $_POST['to'],
            'from' => get_option('admin_email')
        ];

        if ($is_woocommerce) {
            $email = $this->run_woocommerce_email($email, $template_key, true);
        } else {
            $email = $this->apply_template($template_key, $email, true);
        }

        $email['to'] = $_POST['to'];
        $email['subject'] = '[BRF_TEST] ' . $this->get_subject($template_key, 'Test Email');

        wp_mail($email['to'], $email['subject'], $email['message'], $email['headers']);

        wp_die();
    }

    /**
     * Get the default message for a template
     * @param $template_key
     * @return string
     */
    public function get_default_message($template_key)
    {
        $default_message = "This is a placeholder for the text content of an email. The actual content for this part will be dynamically generated and inserted from your WordPress website.";

        if (strpos($template_key, 'woocommerce') !== false) {
            $default_message = $this->get_woocommerce_default_message();
        }

        return $default_message;
    }

    public function get_woocommerce_default_message()
    {
        return '<!DOCTYPE html> <html lang="en-US"> <head> <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> <meta content="width=device-width, initial-scale=1.0" name="viewport"> <title>My Great Blog</title> <style type="text/css">@media screen and (max-width: 600px){#header_wrapper{padding: 27px 36px !important; font-size: 24px;}#body_content table > tbody > tr > td{padding: 10px !important;}#body_content_inner{font-size: 10px !important;}}</style> </head> <body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0" style="background-color: #f7f7f7; padding: 0; text-align: center;" bgcolor="#f7f7f7"> <table width="100%" id="outer_wrapper" style="background-color: #f7f7f7;" bgcolor="#f7f7f7"><tr> <td><!-- Deliberately empty to support consistent sizing and layout across multiple email clients. --></td> <td width="600"> <div id="wrapper" dir="ltr" style="margin: 0 auto; padding: 70px 0; width: 100%; max-width: 600px; -webkit-text-size-adjust: none;" width="100%"> <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%"> <tr> <td align="center" valign="top"> <div id="template_header_image"> </div> <table border="0" cellpadding="0" cellspacing="0" width="100%" id="template_container" style="background-color: #fff; border: 1px solid #dedede; box-shadow: 0 1px 4px rgba(0,0,0,.1); border-radius: 3px;" bgcolor="#fff"> <tr> <td align="center" valign="top"> <!-- Header --> <table border="0" cellpadding="0" cellspacing="0" width="100%" id="template_header" style=\'background-color: #7f54b3; color: #fff; border-bottom: 0; font-weight: bold; line-height: 100%; vertical-align: middle; font-family: "Helvetica Neue",Helvetica,Roboto,Arial,sans-serif; border-radius: 3px 3px 0 0;\' bgcolor="#7f54b3"><tr> <td id="header_wrapper" style="padding: 36px 48px; display: block;"> <h1 style=\'font-family: "Helvetica Neue",Helvetica,Roboto,Arial,sans-serif; font-size: 30px; font-weight: 300; line-height: 150%; margin: 0; text-align: left; text-shadow: 0 1px 0 #9976c2; color: #fff; background-color: inherit;\' bgcolor="inherit">New Order: #3851</h1> </td> </tr></table> <!-- End Header --> </td> </tr> <tr> <td align="center" valign="top"> <!-- Body --> <table border="0" cellpadding="0" cellspacing="0" width="100%" id="template_body"><tr> <td valign="top" id="body_content" style="background-color: #fff;" bgcolor="#fff"> <!-- Content --> <table border="0" cellpadding="20" cellspacing="0" width="100%"><tr> <td valign="top" style="padding: 48px 48px 32px;"> <div id="body_content_inner" style=\'color: #636363; font-family: "Helvetica Neue",Helvetica,Roboto,Arial,sans-serif; font-size: 14px; line-height: 150%; text-align: left;\' align="left"> <p style="margin: 0 0 16px;">You’ve received the following order from John Doe:</p> <h2 style=\'color: #7f54b3; display: block; font-family: "Helvetica Neue",Helvetica,Roboto,Arial,sans-serif; font-size: 18px; font-weight: bold; line-height: 130%; margin: 0 0 18px; text-align: left;\'> <a class="link" href="https://bricksforge.local/wp-admin/post.php?post=3851&amp;action=edit" style="font-weight: normal; text-decoration: underline; color: #7f54b3;">[Order #3851]</a> (May 22, 2023)</h2> <div style="margin-bottom: 40px;"> <table class="td" cellspacing="0" cellpadding="6" border="1" style="color: #636363; border: 1px solid #e5e5e5; vertical-align: middle; width: 100%; font-family: \'Helvetica Neue\', Helvetica, Roboto, Arial, sans-serif;" width="100%"> <thead><tr> <th class="td" scope="col" style="color: #636363; border: 1px solid #e5e5e5; vertical-align: middle; padding: 12px; text-align: left;" align="left">Product</th> <th class="td" scope="col" style="color: #636363; border: 1px solid #e5e5e5; vertical-align: middle; padding: 12px; text-align: left;" align="left">Quantity</th> <th class="td" scope="col" style="color: #636363; border: 1px solid #e5e5e5; vertical-align: middle; padding: 12px; text-align: left;" align="left">Price</th> </tr></thead> <tbody><tr class="order_item"> <td class="td" style="color: #636363; border: 1px solid #e5e5e5; padding: 12px; text-align: left; vertical-align: middle; font-family: \'Helvetica Neue\', Helvetica, Roboto, Arial, sans-serif; word-wrap: break-word;" align="left"> Test Product		</td> <td class="td" style="color: #636363; border: 1px solid #e5e5e5; padding: 12px; text-align: left; vertical-align: middle; font-family: \'Helvetica Neue\', Helvetica, Roboto, Arial, sans-serif;" align="left"> 1		</td> <td class="td" style="color: #636363; border: 1px solid #e5e5e5; padding: 12px; text-align: left; vertical-align: middle; font-family: \'Helvetica Neue\', Helvetica, Roboto, Arial, sans-serif;" align="left"> <span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">$</span>0.00</span>		</td> </tr></tbody> <tfoot> <tr> <th class="td" scope="row" colspan="2" style="color: #636363; border: 1px solid #e5e5e5; vertical-align: middle; padding: 12px; text-align: left; border-top-width: 4px;" align="left">Subtotal:</th> <td class="td" style="color: #636363; border: 1px solid #e5e5e5; vertical-align: middle; padding: 12px; text-align: left; border-top-width: 4px;" align="left"><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">$</span>0.00</span></td> </tr> <tr> <th class="td" scope="row" colspan="2" style="color: #636363; border: 1px solid #e5e5e5; vertical-align: middle; padding: 12px; text-align: left;" align="left">Total:</th> <td class="td" style="color: #636363; border: 1px solid #e5e5e5; vertical-align: middle; padding: 12px; text-align: left;" align="left"><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">$</span>0.00</span></td> </tr> </tfoot> </table> </div> <table id="addresses" cellspacing="0" cellpadding="0" border="0" style="width: 100%; vertical-align: top; margin-bottom: 40px; padding: 0;" width="100%"><tr> <td valign="top" width="50%" style="text-align: left; font-family: \'Helvetica Neue\', Helvetica, Roboto, Arial, sans-serif; border: 0; padding: 0;" align="left"> <h2 style=\'color: #7f54b3; display: block; font-family: "Helvetica Neue",Helvetica,Roboto,Arial,sans-serif; font-size: 18px; font-weight: bold; line-height: 130%; margin: 0 0 18px; text-align: left;\'>Billing address</h2> <address class="address" style="padding: 12px; color: #636363; border: 1px solid #e5e5e5;"> John Doe<br>California													<br>john@doe.com							</address> </td> </tr></table> <p style="margin: 0 0 16px;">Congratulations on the sale.</p> Process your orders on the go. <a href="https://woocommerce.com/mobile?blog_id=0&amp;utm_campaign=deeplinks_promote_app&amp;utm_medium=email&amp;utm_source=bricksforge.local&amp;utm_term=0" style="color: #7f54b3; font-weight: normal; text-decoration: underline;">Get the app</a>.																		</div> </td> </tr></table> <!-- End Content --> </td> </tr></table> <!-- End Body --> </td> </tr> </table> </td> </tr> <tr> <td align="center" valign="top"> <!-- Footer --> <table border="0" cellpadding="10" cellspacing="0" width="100%" id="template_footer"><tr> <td valign="top" style="padding: 0; border-radius: 6px;"> <table border="0" cellpadding="10" cellspacing="0" width="100%"><tr> <td colspan="2" valign="middle" id="credit" style=\'border-radius: 6px; border: 0; color: #8a8a8a; font-family: "Helvetica Neue",Helvetica,Roboto,Arial,sans-serif; font-size: 12px; line-height: 150%; text-align: center; padding: 24px 0;\' align="center"> <p style="margin: 0 0 16px;">My Great Blog — Built with <a href="https://woocommerce.com" style="color: #7f54b3; font-weight: normal; text-decoration: underline;">WooCommerce</a></p> </td> </tr></table> </td> </tr></table> <!-- End Footer --> </td> </tr> </table> </div> </td> <td><!-- Deliberately empty to support consistent sizing and layout across multiple email clients. --></td> </tr></table> </body> </html>';
    }

    /**
     * Check if this extension is activated
     * @return bool
     */
    public function activated()
    {
        $options = get_option('brf_activated_tools') ? get_option('brf_activated_tools') : false;

        if ($options && in_array(13, $options)) {
            return true;
        }

        return false;
    }
}
