<?php

#[AllowDynamicProperties]
class WS_Form_Action_WooCommerce extends WS_Form_Action {

	public $id = 'woocommerce';
	public $label;
	public $configured = false;

	public $product    = false;
	public $product_id = false;

	public $meta_data = false;
	public $meta_key_config;
	public $meta_keys;
	public $use_form_settings = false;

	public $product_form_cache = false;

	public $form_id = false;
	public $form    = false;

	public $settings_source                  = 'form';

	public $catalog_add_to_cart_text         = '';
	public $price_disable                    = '';
	public $price_prefix                     = '';
	public $price_suffix                     = '';
	public $product_price_variation_hide     = '';
	public $product_add_to_cart_hide         = '';
	public $product_quantity_hide            = '';
	public $product_form_validate            = '';
	public $product_clear_hidden_meta_values = '';
	public $cart_edit                        = '';
	public $cart_price_plugin_allow_negative = '';
	public $actions_fire                     = '';

	public $form_catalog_add_to_cart_text         = '';
	public $form_price_disable                    = '';
	public $form_price_prefix                     = '';
	public $form_price_suffix                     = '';
	public $form_product_price_variation_hide     = '';
	public $form_product_add_to_cart_hide         = '';
	public $form_product_quantity_hide            = '';
	public $form_product_form_validate            = '';
	public $form_product_clear_hidden_meta_values = '';
	public $form_cart_edit                        = '';
	public $form_cart_price_plugin_allow_negative = '';
	public $form_actions_fire                     = '';

	public $order_id                   = '';
	public $order_number               = '';
	public $order_url                  = '';
	public $order_status               = '';
	public $order_sub_total            = '';
	public $order_sub_total_price      = '';
	public $order_discount_total       = '';
	public $order_discount_total_price = '';
	public $order_shipping_total       = '';
	public $order_shipping_total_price = '';
	public $order_tax_total            = '';
	public $order_tax_total_price      = '';
	public $order_total                = '';
	public $order_total_price          = '';

	public $order_user_id         = '';
	public $order_user_first_name = '';
	public $order_user_last_name  = '';
	public $order_user_email      = '';

	public $order_billing_first_name = '';
	public $order_billing_last_name  = '';
	public $order_billing_company    = '';
	public $order_billing_address_1  = '';
	public $order_billing_address_2  = '';
	public $order_billing_city       = '';
	public $order_billing_state      = '';
	public $order_billing_postcode   = '';
	public $order_billing_country    = '';
	public $order_billing_email      = '';
	public $order_billing_phone      = '';

	public $order_shipping_first_name = '';
	public $order_shipping_last_name  = '';
	public $order_shipping_company    = '';
	public $order_shipping_address_1  = '';
	public $order_shipping_address_2  = '';
	public $order_shipping_city       = '';
	public $order_shipping_state      = '';
	public $order_shipping_postcode   = '';
	public $order_shipping_country    = '';
	public $order_shipping_phone      = '';

	public $localize_array = array();

	// Constants - License
	const WS_FORM_LICENSE_ITEM_ID = 5533;
	const WS_FORM_LICENSE_NAME    = 'WooCommerce WS Form PRO Product Add-Ons';
	const WS_FORM_LICENSE_VERSION = WS_FORM_WOOCOMMERCE_VERSION;
	const WS_FORM_LICENSE_AUTHOR  = 'WS Form';

	// Constants -  Meta
	const META_KEY_PRODUCT                  = '_wsform_woocommerce_meta_data';
	const META_KEY_CART_ITEM_WS_FORM_SUBMIT = 'wsf_submit';
	const META_KEY_CART_ITEM_DATA           = 'wsf_item_data';
	const META_KEY_ORDER_WS_FORM_SUBMIT     = self::META_KEY_CART_ITEM_WS_FORM_SUBMIT;
	const META_KEY_ORDER_FORM_ID            = '_wsf_form_id';
	const META_KEY_ORDER_SUBMIT_ID          = '_wsf_submit_id';
	const META_KEY_SUBMIT_ORDER_STATUS      = 'woocommerce_order_status';
	const META_KEY_SUBMIT_ORDER_ID          = 'woocommerce_order_id';

	// Constants - 
	const QUERY_ARG_CART_ITEM_KEY    = 'wsf_cart_item_key';
	const QUERY_ARG_PRODUCT_ID       = 'wsf-woocommerce-product-id';
	const SKIN_DEFAULT               = '';
	const MAX_PRODUCT_SEARCH_RESULTS = 10;
	const PRODUCT_FORM_CACHE_OPTION  = WS_FORM_IDENTIFIER . '_wc_pfc';

	// Legacy meta data
	const META_KEY_ORDER_FORM_ID_LEGACY        = 'wsf_form_id';
	const META_KEY_ORDER_SUBMIT_ID_LEGACY      = 'wsf_submit_id';
	const META_KEY_ORDER_DATA_LEGACY           = 'wsf_order_data';
	const META_KEY_ORDER_DATA_FORM_ID_LEGACY   = 'form_id';
	const META_KEY_ORDER_DATA_SUBMIT_ID_LEGACY = 'submit_id';

	public function __construct() {

		// Set label
		$this->label = __( 'WooCommerce', 'ws-form-woocommerce' );

		// Set meta key config
		$this->meta_key_config = self::config_meta_keys(array());

		// Set up meta keys
		foreach ( $this->meta_key_config as $meta_key => $meta_key_config ) {

			$woocommerce_meta = ( isset($meta_key_config['wc']) ? $meta_key_config['wc'] : false );
			if ( $woocommerce_meta ) {
				$meta_key_config['meta_key'] = $meta_key;
				$this->meta_keys[]           = $meta_key_config;
			}
		}

		// Filters
		add_filter( 'plugin_action_links_' . WS_FORM_WOOCOMMERCE_PLUGIN_BASENAME, array( $this, 'plugin_action_links' ), 10, 1 );
		add_filter( 'wsf_config_options', array( $this, 'config_options' ), 10, 1 );
		add_filter( 'wsf_config_field_types', array( $this, 'config_field_types' ), 10, 2 );
		add_filter( 'wsf_config_meta_keys', array( $this, 'config_meta_keys' ), 10, 2 );
		add_filter( 'wsf_config_frameworks', array( $this, 'config_frameworks' ), 10, 1 );
		add_filter( 'wsf_settings_static', array( $this, 'settings_static' ), 10, 2 );
		add_filter( 'wsf_config_settings_form_admin', array( $this, 'config_settings_form_admin_action' ), 10, 1 );

		// Actions
		add_action( 'wsf_nag', array( $this, 'nag' ) );
		add_action( 'wsf_form_edit_nav_left', array( $this, 'nav_left' ), 10, 1 );
		add_action( 'wsf_form_submit_nav_left', array( $this, 'nav_left' ), 10, 1 );

		// Updates
		add_action( 'init', array( $this, 'update_1_1_0' ) );
		add_action( 'init', array( $this, 'update_1_1_49' ) );

		// Initialize
		self::load_config_plugin();
		if ( $this->configured ) {
			self::init();
		}
	}

	// Nag
	public function nag() {

		// Load plugin level configuration
		self::load_config_plugin();

		if ( ! $this->configured ) {

			WS_Form_Common::admin_message_push( 

				/* translators: %1$s: WS Form PRO add-on name, %2$s: WS Form PRO presentable name */
				sprintf( __( 'The %1$s add-on for %2$s requires the WooCommerce plugin to be installed and activated.', 'ws-form-woocommerce' ), $this->label, WS_FORM_NAME_PRESENTABLE )

			, 'notice-warning', false );
		}
	}

	// Initialize
	public function init() {

		// WooCommerce admin actions
		add_action( 'woocommerce_product_data_tabs', array( $this, 'product_data_tabs' ), 10, 1 );
		add_action( 'woocommerce_product_data_panels', array( $this, 'product_data_panels' ), 10, 1 );
		add_action( 'woocommerce_process_product_meta', array( $this, 'process_product_meta' ), 10, 2 );

		// WooCommerce shop loop items
		add_action( 'woocommerce_after_shop_loop_item', array( $this, 'after_shop_loop_item' ), 5 );

		// WooCommerce product actions/filters
		add_action( 'woocommerce_before_add_to_cart_button', array( $this, 'before_add_to_cart_button' ), 10, 0 );
		add_filter( 'add_to_cart_text', array( $this, 'add_to_cart_text' ), 1000 );
		add_filter( 'woocommerce_product_add_to_cart_text', array( $this, 'add_to_cart_text' ), 100 );
		add_filter( 'woocommerce_add_to_cart_url', array( $this, 'add_to_cart_url' ), 10, 2 );
		add_filter( 'woocommerce_product_add_to_cart_url', array( $this, 'add_to_cart_url' ), 10, 2 );
		add_filter( 'woocommerce_product_supports', array( $this, 'product_supports' ), 10, 3 );
		add_filter( 'woocommerce_price_html', array( $this, 'price_html' ), 1000, 2 );
		add_filter( 'woocommerce_get_price_html', array( $this, 'price_html' ), 1000, 2 );
		add_action( 'woocommerce_update_product', array( $this, 'update_product' ), 10, 1 );
		add_action( 'deleted_post', array( $this, 'deleted_post' ), 10, 1 );
		add_action( 'delete_term', array( $this, 'delete_term' ), 10, 3 );

		// WooCommerce cart actions/filters
		add_filter( 'woocommerce_add_cart_item_data', array( $this, 'add_cart_item_data' ), 10, 3 );
		add_filter( 'woocommerce_cart_id', array( $this, 'cart_id' ), 1000, 5 );
		add_filter( 'woocommerce_get_cart_item_from_session', array( $this, 'get_cart_item_from_session' ), 10, 2 );
		add_filter( 'woocommerce_get_item_data', array( $this, 'get_item_data' ), 10, 2 );
		add_filter( 'woocommerce_add_cart_item', array( $this, 'cart_item_calculate' ), 10, 1 );

		// WooCommerce order again
		add_filter( 'woocommerce_order_again_cart_item_data', array( $this, 'order_again_cart_item_data' ), 10, 3 );

		// WooCommerce cart edit
		add_filter( 'wsf_populate', array( $this, 'populate' ), 10, 1 );
		add_filter( 'woocommerce_cart_item_permalink', array( $this, 'cart_item_permalink' ), 100, 3 );
		add_filter( 'woocommerce_add_to_cart', array( $this, 'add_to_cart' ), 100, 6 );

		// WooCommerce checkout
		add_action( 'woocommerce_checkout_create_order_line_item', array( $this, 'checkout_create_order_line_item' ), 10, 4 );
		add_action( 'woocommerce_checkout_order_created', array( $this, 'checkout_order_created' ), 10, 1 );

		// WooCommerce order status changed
		add_action( 'woocommerce_order_status_changed', array( $this, 'order_status_changed' ), 10, 3 );

		// WooCommerce order item
		add_filter( 'woocommerce_hidden_order_itemmeta', array( $this, 'hidden_order_itemmeta' ), 10, 2 );

		// WooCommerce order admin
		add_action( 'woocommerce_after_order_itemmeta', array( $this, 'after_order_itemmeta' ), 10, 3 );
		add_action( 'woocommerce_after_order_notes', array( $this, 'after_order_notes' ), 10, 0 );
		add_action( 'woocommerce_admin_order_data_after_order_details', array( $this, 'woocommerce_admin_order_data_after_order_details' ), 10, 1 );

		// Theme switching
		add_action( 'switch_theme', array( $this, 'switch_theme' ), 10, 0 );

		// WS Form
		add_filter( 'wsf_config_ecommerce', array( $this, 'config_ecommerce' ), 10, 1 );
		add_action( 'wsf_form_add_hidden', array( $this, 'form_add_hidden' ), 10, 1 );
		add_filter( 'wsf_get_locations_post', array( $this, 'get_locations_post' ), 10, 3 );
		add_filter( 'wsf_template_form_config_files', array( $this, 'template_config_files' ), 10, 1 );
		add_filter( 'wsf_template_svg_buttons', array( $this, 'template_svg_buttons' ), 10, 1 );
		add_filter( 'wsf_template_svg_price_span', array( $this, 'template_svg_price_span' ), 10, 1 );
		add_filter( 'wsf_action_settings', array( $this, 'action_settings' ), 10, 2 );
		add_filter( 'wsf_action_post_do', array( $this, 'action_post_do' ), 10, 6 );
		add_action( 'wsf_form_publish', array( $this, 'form_publish' ), 10, 1 );
		add_action( 'wsf_form_trash', array( $this, 'form_change' ), 10, 1 );
		add_action( 'wsf_form_delete', array( $this, 'form_change' ), 10, 1 );
		add_action( 'wsf_form_restore', array( $this, 'form_change' ), 10, 1 );
		add_filter( 'wsf_config_parse_variables', array( $this, 'config_parse_variables' ), 10, 1 );
		add_filter( 'wsf_parse_variables', array( $this, 'parse_variables' ), 10, 5 );
		add_filter( 'wsf_data_source_get_column_mapping', array( $this, 'data_source_get_column_mapping' ), 10, 3 );

		// Enqueue scripts / styles
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_styles' ), 10, 1 );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ), 10, 1 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		// Footer
		add_action( 'wp_footer', array( $this, 'wp_footer' ), 10000 );

		// Activation
		register_activation_hook( __FILE__, array( $this, 'activation' ) );

		// API
		add_action( 'rest_api_init', array( $this, 'rest_api_init' ), 10, 0 );

		// Media button
		add_filter( 'wsf_render_media_button', array( $this, 'render_media_button' ), 10, 2 );
	}

	// Data source column mapping
	public function data_source_get_column_mapping( $meta_keys, $meta_value, $meta_key_config ) {

		// Get meta key for label mapping
		$meta_key_label = isset( $meta_key_config[ 'meta_key_label' ] ) ? $meta_key_config[ 'meta_key_label' ] : false;

		// Check mapping
		if ( false === $meta_key_label ) {
			return $meta_keys;
		}

		// Build WooCommerce meta key for mapping
		$meta_key_wc = str_replace( '_label', '_wc', $meta_key_label );

		// Set meta key
		if ( isset( $meta_keys[ $meta_key_label ] ) ) {
			$meta_keys[ $meta_key_wc ] = $meta_keys[ $meta_key_label ];
		}

		return $meta_keys;
	}

	// Config parse variables
	public function config_parse_variables( $parse_variables ) {

		$parse_variables['woocommerce'] = array(

			'label' => __( 'WooCommerce', 'ws-form-woocommerce' ),

			'variables'   => array(

				// Order
				'woocommerce_order_id' => array( 'label' => __( 'Order ID', 'ws-form-woocommerce' ), 'value' => $this->order_id, 'description' => __( 'Returns the WooCommerce order ID.', 'ws-form-woocommerce' ) ),
				'woocommerce_order_number' => array( 'label' => __( 'Order Number', 'ws-form-woocommerce' ), 'value' => $this->order_number, 'description' => __( 'Returns the WooCommerce order number (For display).', 'ws-form-woocommerce' ) ),
				'woocommerce_order_url' => array( 'label' => __( 'Order URL', 'ws-form-woocommerce' ), 'value' => $this->order_url, 'description' => __( 'Returns the WooCommerce order URL. Do not use this URL in any communications to the person placing the order.', 'ws-form-woocommerce' ) ),
				'woocommerce_order_status' => array( 'label' => __( 'Order Status', 'ws-form-woocommerce' ), 'value' => $this->order_status, 'description' => __( 'Returns the WooCommerce order status.', 'ws-form-woocommerce' ) ),
				'woocommerce_order_sub_total' => array( 'label' => __( 'Order Sub Total (Without Currency)', 'ws-form-woocommerce' ), 'value' => $this->order_sub_total, 'description' => __( 'Returns the WooCommerce order sub total (Without currency).', 'ws-form-woocommerce' ) ),
				'woocommerce_order_sub_total_price' => array( 'label' => __( 'Order Sub Total (With Currency)', 'ws-form-woocommerce' ), 'value' => $this->order_sub_total_price, 'description' => __( 'Returns the WooCommerce order sub total (With currency).', 'ws-form-woocommerce' ) ),
				'woocommerce_order_discount_total' => array( 'label' => __( 'Order Discount Total (Without Currency)', 'ws-form-woocommerce' ), 'value' => $this->order_discount_total, 'description' => __( 'Returns the WooCommerce order discount total (Without currency).', 'ws-form-woocommerce' ) ),
				'woocommerce_order_discount_total_price' => array( 'label' => __( 'Order Discount Total (With Currency)', 'ws-form-woocommerce' ), 'value' => $this->order_discount_total_price, 'description' => __( 'Returns the WooCommerce order discount total (With currency).', 'ws-form-woocommerce' ) ),
				'woocommerce_order_shipping_total' => array( 'label' => __( 'Order Shipping Total (Without Currency)', 'ws-form-woocommerce' ), 'value' => $this->order_shipping_total, 'description' => __( 'Returns the WooCommerce order shipping total (Without currency).', 'ws-form-woocommerce' ) ),
				'woocommerce_order_shipping_total_price' => array( 'label' => __( 'Order Shipping Total (With Currency)', 'ws-form-woocommerce' ), 'value' => $this->order_shipping_total_price, 'description' => __( 'Returns the WooCommerce order shipping total (With currency).', 'ws-form-woocommerce' ) ),
				'woocommerce_order_tax_total' => array( 'label' => __( 'Order Tax Total (Without Currency)', 'ws-form-woocommerce' ), 'value' => $this->order_tax_total, 'description' => __( 'Returns the WooCommerce order tax total (Without currency).', 'ws-form-woocommerce' ) ),
				'woocommerce_order_tax_total_price' => array( 'label' => __( 'Order Tax Total (With Currency)', 'ws-form-woocommerce' ), 'value' => $this->order_tax_total_price, 'description' => __( 'Returns the WooCommerce order tax total (With currency).', 'ws-form-woocommerce' ) ),
				'woocommerce_order_total' => array( 'label' => __( 'Order Total (Without Currency)', 'ws-form-woocommerce' ), 'value' => $this->order_total, 'description' => __( 'Returns the WooCommerce order total (Without currency).', 'ws-form-woocommerce' ) ),
				'woocommerce_order_total_price' => array( 'label' => __( 'Order Total (With Currency)', 'ws-form-woocommerce' ), 'value' => $this->order_total_price, 'description' => __( 'Returns the WooCommerce order total (With currency).', 'ws-form-woocommerce' ) ),

				// User
				'woocommerce_order_user_id' => array( 'label' => __( 'Order User ID', 'ws-form-woocommerce' ), 'value' => $this->order_user_id, 'description' => __( 'Returns the WooCommerce order user ID.', 'ws-form-woocommerce' ) ),
				'woocommerce_order_user_first_name' => array( 'label' => __( 'Order User First Name', 'ws-form-woocommerce' ), 'value' => $this->order_user_first_name, 'description' => __( 'Returns the WooCommerce order user first name.', 'ws-form-woocommerce' ) ),
				'woocommerce_order_user_last_name' => array( 'label' => __( 'Order User Last Name', 'ws-form-woocommerce' ), 'value' => $this->order_user_last_name, 'description' => __( 'Returns the WooCommerce order user last name.', 'ws-form-woocommerce' ) ),
				'woocommerce_order_user_email' => array( 'label' => __( 'Order User Email', 'ws-form-woocommerce' ), 'value' => $this->order_user_email, 'description' => __( 'Returns the WooCommerce order user email.', 'ws-form-woocommerce' ) ),

				// Billing
				'woocommerce_order_billing_first_name' => array( 'label' => __( 'Order Billing First Name', 'ws-form-woocommerce' ), 'value' => $this->order_billing_first_name, 'description' => __( 'Returns the WooCommerce order billing first name.', 'ws-form-woocommerce' ) ),
				'woocommerce_order_billing_last_name' => array( 'label' => __( 'Order Billing Last Name', 'ws-form-woocommerce' ), 'value' => $this->order_billing_last_name, 'description' => __( 'Returns the WooCommerce order billing last name.', 'ws-form-woocommerce' ) ),
				'woocommerce_order_billing_company' => array( 'label' => __( 'Order Billing Company', 'ws-form-woocommerce' ), 'value' => $this->order_billing_company, 'description' => __( 'Returns the WooCommerce order billing company.', 'ws-form-woocommerce' ) ),
				'woocommerce_order_billing_address_1' => array( 'label' => __( 'Order Billing Address Line 1', 'ws-form-woocommerce' ), 'value' => $this->order_billing_address_1, 'description' => __( 'Returns the WooCommerce order billing address line 1.', 'ws-form-woocommerce' ) ),
				'woocommerce_order_billing_address_2' => array( 'label' => __( 'Order Billing Address Line 2', 'ws-form-woocommerce' ), 'value' => $this->order_billing_address_2, 'description' => __( 'Returns the WooCommerce order billing address line 2.', 'ws-form-woocommerce' ) ),
				'woocommerce_order_billing_city' => array( 'label' => __( 'Order Billing City', 'ws-form-woocommerce' ), 'value' => $this->order_billing_city, 'description' => __( 'Returns the WooCommerce order billing city.', 'ws-form-woocommerce' ) ),
				'woocommerce_order_billing_state' => array( 'label' => __( 'Order Billing State', 'ws-form-woocommerce' ), 'value' => $this->order_billing_state, 'description' => __( 'Returns the WooCommerce order billing state.', 'ws-form-woocommerce' ) ),
				'woocommerce_order_billing_postcode' => array( 'label' => __( 'Order Billing Zip', 'ws-form-woocommerce' ), 'value' => $this->order_billing_postcode, 'description' => __( 'Returns the WooCommerce order billing zip.', 'ws-form-woocommerce' ) ),
				'woocommerce_order_billing_country' => array( 'label' => __( 'Order Billing Country', 'ws-form-woocommerce' ), 'value' => $this->order_billing_country, 'description' => __( 'Returns the WooCommerce order billing country.', 'ws-form-woocommerce' ) ),
				'woocommerce_order_billing_email' => array( 'label' => __( 'Order Billing Email', 'ws-form-woocommerce' ), 'value' => $this->order_billing_email, 'description' => __( 'Returns the WooCommerce order billing email.', 'ws-form-woocommerce' ) ),
				'woocommerce_order_billing_phone' => array( 'label' => __( 'Order Billing Phone', 'ws-form-woocommerce' ), 'value' => $this->order_billing_phone, 'description' => __( 'Returns the WooCommerce order billing phone.', 'ws-form-woocommerce' ) ),

				// Shipping
				'woocommerce_order_shipping_first_name' => array( 'label' => __( 'Order Shipping First Name', 'ws-form-woocommerce' ), 'value' => $this->order_shipping_first_name, 'description' => __( 'Returns the WooCommerce order shipping first name.', 'ws-form-woocommerce' ) ),
				'woocommerce_order_shipping_last_name' => array( 'label' => __( 'Order Shipping Last Name', 'ws-form-woocommerce' ), 'value' => $this->order_shipping_last_name, 'description' => __( 'Returns the WooCommerce order shipping last name.', 'ws-form-woocommerce' ) ),
				'woocommerce_order_shipping_company' => array( 'label' => __( 'Order Shipping Company', 'ws-form-woocommerce' ), 'value' => $this->order_shipping_company, 'description' => __( 'Returns the WooCommerce order shipping company.', 'ws-form-woocommerce' ) ),
				'woocommerce_order_shipping_address_1' => array( 'label' => __( 'Order Shipping Address Line 1', 'ws-form-woocommerce' ), 'value' => $this->order_shipping_address_1, 'description' => __( 'Returns the WooCommerce order shipping address line 1.', 'ws-form-woocommerce' ) ),
				'woocommerce_order_shipping_address_2' => array( 'label' => __( 'Order Shipping Address Line 2', 'ws-form-woocommerce' ), 'value' => $this->order_shipping_address_2, 'description' => __( 'Returns the WooCommerce order shipping address line 2.', 'ws-form-woocommerce' ) ),
				'woocommerce_order_shipping_city' => array( 'label' => __( 'Order Shipping City', 'ws-form-woocommerce' ), 'value' => $this->order_shipping_city, 'description' => __( 'Returns the WooCommerce order shipping city.', 'ws-form-woocommerce' ) ),
				'woocommerce_order_shipping_state' => array( 'label' => __( 'Order Shipping State', 'ws-form-woocommerce' ), 'value' => $this->order_shipping_state, 'description' => __( 'Returns the WooCommerce order shipping state.', 'ws-form-woocommerce' ) ),
				'woocommerce_order_shipping_postcode' => array( 'label' => __( 'Order Shipping Zip', 'ws-form-woocommerce' ), 'value' => $this->order_shipping_postcode, 'description' => __( 'Returns the WooCommerce order shipping zip.', 'ws-form-woocommerce' ) ),
				'woocommerce_order_shipping_country' => array( 'label' => __( 'Order Shipping Country', 'ws-form-woocommerce' ), 'value' => $this->order_shipping_country, 'description' => __( 'Returns the WooCommerce order shipping country.', 'ws-form-woocommerce' ) ),
				'woocommerce_order_shipping_phone' => array( 'label' => __( 'Order Shipping Phone', 'ws-form-woocommerce' ), 'value' => $this->order_shipping_phone, 'description' => __( 'Returns the WooCommerce order shipping phone.', 'ws-form-woocommerce' ) ),
			),
		);

		return $parse_variables;
	}

	// Parse variables
	public function parse_variables( $parse_variables, $parse_string, $form, $submit, $content_type ) {

		// WooCommerce
		if ( function_exists( 'wc_get_order_statuses' ) && strpos($parse_string, 'woocommerce_order_status' ) !== false ) {

			$order_statuses                              = wc_get_order_statuses();
			$parse_variables['woocommerce_order_status'] = isset($order_statuses['wc-' . $this->order_status]) ? $order_statuses['wc-' . $this->order_status] : __( 'Unknown', 'ws-form-woocommerce' );
		}

		return $parse_variables;
	}

	// Disable 'Add WS Form' on products (to avoid customer confusion)
	public function render_media_button( $render_media_button, $post_type ) {

		$render_media_button = $render_media_button && ( 'product' !== $post_type );

		return $render_media_button;
	}

	// Nav - Left
	public function nav_left( $form_id = 0 ) {

		$product_id = absint( WS_Form_Common::get_query_var('product_id', 0) );

		if ( 0 === $product_id ) {

			$product_id = self::get_form_product_id( $form_id );
		}

		if ( 0 < $product_id ) {
			?>
<a class="wsf-button wsf-button-small" href="<?php echo esc_attr(admin_url(sprintf('post.php?post=%u&action=edit&wsf_wc_tab=wsf_woocommerce_tab', $product_id))); ?>"><?php WS_Form_Common::render_icon_16_svg('woo' ); ?> <?php esc_html_e('Product', 'ws-form-woocommerce' ); ?></a>
			<?php
		}
	}

	// Templates - Config files
	public function template_config_files( $config_files ) {

		$config_files[] = WS_FORM_WOOCOMMERCE_PLUGIN_DIR_PATH . 'includes/templates/form/config.json';

		return $config_files;
	}

	// Templates - SVG buttons
	public function template_svg_buttons( $field_type_buttons ) {

		$color_default          = WS_Form_Common::option_get( 'skin_color_default' );
		$color_default_inverted = WS_Form_Common::option_get( 'skin_color_default_inverted' );
		$color_default_lighter  = WS_Form_Common::option_get( 'skin_color_default_lighter' );
		$color_primary          = WS_Form_Common::option_get( 'skin_color_primary' );

		$field_type_buttons['wc_add_to_cart'] = array( 'fill' => $color_primary, 'color' => $color_default_inverted );
		$field_type_buttons['wc_clear']       = array( 'fill' => $color_default_lighter, 'color' => $color_default );

		return $field_type_buttons;
	}

	// Template - SVG price span
	public function template_svg_price_span( $field_type_price_span ) {

		$field_type_price_span['wc_subtotal'] = array();
		$field_type_price_span['wc_cart']     = array();
		$field_type_price_span['wc_total']    = array();
		$field_type_price_span['wc_ci_total'] = array();

		return $field_type_price_span;
	}

	// Form add - Hidden fields
	public function form_add_hidden() {

		$product_id = absint( WS_Form_Common::get_query_var( self::QUERY_ARG_PRODUCT_ID ) );
		if ( 0 == $product_id ) {
			return;
		}

		?>
		<input type="hidden" name="wsf-woocommerce-product-id" value="<?php echo esc_attr($product_id); ?>" />
		<?php
	}

	// Get locations
	public function get_locations_post( $form_id_array, $post, $form_id ) {

		if ( 'product' != get_post_type( $post->ID ) ) {
			return $form_id_array;
		}

		$product_id = $post->ID;

		$form_id = self::get_product_form_id( $product_id );

		if ( ! empty( $form_id ) ) {
			$form_id_array[] = $form_id;
		}

		return $form_id_array;
	}

	// Get form ID by product ID
	public function get_product_form_id( $product_id ) {

		if ( false === $this->product_form_cache ) {
			$this->product_form_cache = get_option(self::PRODUCT_FORM_CACHE_OPTION, false);
		}
		if ( ( false === $this->product_form_cache ) || ! isset($this->product_form_cache[$product_id]) ) {
			return false;
		}

		$matches = $this->product_form_cache[$product_id];

		if ( 0 == count( $matches ) ) {
			return false;
		}

		$match_first = array_shift( $matches );

		return $match_first['form_id'];
	}

	// Get product ID by form ID
	public function get_form_product_id( $form_id ) {

		if ( false === $this->product_form_cache ) {
			$this->product_form_cache = get_option(self::PRODUCT_FORM_CACHE_OPTION, false);
		}
		if ( ( false === $this->product_form_cache ) ) {
			return 0;
		}

		$product_id = 0;

		$form_id = strval( $form_id );

		foreach ( $this->product_form_cache as $assignments ) {

			foreach ( $assignments as $assignment ) {

				if (
					! isset( $assignment['form_id'] ) ||
					! isset( $assignment['product_id'] ) ||
					( $assignment['form_id'] !== $form_id )
				) {
					continue;
				}

				// Form is assigned to more than one product, so we can't link to a product for this form
				if ( 0 !== $product_id ) {
					return 0;
				}

				$product_id = $assignment['product_id'];
			}
		}

		return $product_id;
	}

	// Enqueue styles (Admin)
	public function admin_enqueue_styles( $hook ) {

		switch ( $hook ) {

			case 'post.php':
			case 'post-new.php':
				// CSS - Admin
				wp_enqueue_style( 'ws-form-admin', WS_FORM_WOOCOMMERCE_PLUGIN_DIR_URL . 'css/admin.css', array(), WS_FORM_VERSION, 'all' );

				// CSS - Template
				wp_enqueue_style( 'ws-form-template', WS_FORM_PLUGIN_DIR_URL . 'admin/css/ws-form-admin-template.css', array(), WS_FORM_VERSION, 'all' );

				break;

			case 'admin_page_ws-form-edit':
				// WooCommerce CSS - Select2
				if ( ! wp_style_is('select2', 'enqueued' ) && defined( 'WC_VERSION' ) ) {

					wp_enqueue_style('select2', WC()->plugin_url() . '/assets/css/select2.css', array(), WC_VERSION, 'all' );
				}

				break;
		}
	}

	// Enqueue scripts (Admin)
	public function admin_enqueue_scripts( $hook ) {

		switch ( $hook ) {

			case 'admin_page_ws-form-edit':
				$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

				// WooCommerce JS - Select2
				if ( ! wp_script_is('select2', 'enqueued' ) && defined( 'WC_VERSION' ) ) {

					wp_register_script( 'select2', WC()->plugin_url() . '/assets/js/select2/select2.full' . $suffix . '.js', array( 'jquery' ), WC_VERSION, false );
					wp_enqueue_script( 'select2' );
				}

				break;
		}
	}

	// Enqueue styles (Public)
	public function enqueue_styles() {

		// CSS - Public
		wp_enqueue_style( 'ws-form-' . $this->id . '-public', WS_FORM_WOOCOMMERCE_PLUGIN_DIR_URL . 'css/public.css', array(), self::WS_FORM_LICENSE_VERSION, 'all' );

		// CSS - Skin
		$skin = WS_Form_Common::option_get( 'action_' . $this->id . '_skin', '' );
		if ( '' != $skin ) {

			$skin_options = self::skin_options();

			if ( isset( $skin_options[$skin] ) ) {

				$dependencies =  array( 'ws-form-' . $this->id . '-public' );
				if ( isset( $skin_options[$skin]['dependencies'] ) ) {
					$dependencies = array_merge( $dependencies, $skin_options[$skin]['dependencies'] );
				}

				wp_enqueue_style( 'ws-form-' . $this->id . '-public-skin', WS_FORM_WOOCOMMERCE_PLUGIN_DIR_URL . 'css/skins/' . $skin . '.css', $dependencies, self::WS_FORM_LICENSE_VERSION, 'all' );
			}
		}
	}

	// Enqueue scripts
	public function enqueue_scripts() {

		// Enqueue in footer?
		$jquery_footer = WS_Form_Common::option_get( 'jquery_footer', '' );

		// Min suffix?
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		// WooCommerce JS - Accounting
		if ( ! wp_script_is('accounting', 'enqueued' )) {

			wp_register_script( 'accounting', WC()->plugin_url() . '/assets/js/accounting/accounting' . $suffix . '.js', array( 'jquery' ), self::WS_FORM_LICENSE_VERSION, $jquery_footer );
			wp_localize_script( 'accounting', 'accounting_params', array( 'mon_decimal_point' => wc_get_price_decimal_separator() ) );
			wp_enqueue_script( 'accounting' );
		}

		// Global public JS
		$handle = 'wsform-' . $this->id . '-public-js';
		wp_register_script( $handle, WS_FORM_WOOCOMMERCE_PLUGIN_DIR_URL . 'js/public.js', array( 'jquery', 'accounting' ), self::WS_FORM_LICENSE_VERSION, $jquery_footer );
		wp_enqueue_script( $handle );
	}

	// Build REST API endpoints
	public function rest_api_init() {

		// API routes
		register_rest_route(
			WS_FORM_RESTFUL_NAMESPACE,
			'/action/' . $this->id . '/product/(?P<product_id>[0-9]+)/',
			array(
				'methods' => 'GET',
				'callback' => array( $this, 'api_get_product' ),
				'permission_callback' => function ( $parameters ) {
					return ( WS_Form_Common::get_query_var_nonce( 'product_id', false, $parameters ) !== false );
				},
			)
		);
		register_rest_route(
			WS_FORM_RESTFUL_NAMESPACE,
			'/action/' . $this->id . '/product/(?P<product_id>[0-9]+)/template/(?P<template_id>[a-zA-Z0-9-]+)/',
			array(
				'methods' => 'POST',
				'callback' => array( $this, 'api_post_template' ),
				'permission_callback' => function () {
					return WS_Form_Common::can_user( 'create_form' );
				},
			)
		);
		register_rest_route(
			WS_FORM_RESTFUL_NAMESPACE,
			'/action/' . $this->id . '/product/(?P<product_id>[0-9]+)/form/(?P<form_id>[0-9-]+)/',
			array(
				'methods' => 'POST',
				'callback' => array( $this, 'api_form_assign' ),
				'permission_callback' => function () {
					return WS_Form_Common::can_user( 'edit_form' );
				},
			)
		);
		register_rest_route(
			WS_FORM_RESTFUL_NAMESPACE,
			'/action/' . $this->id . '/product/(?P<product_id>[0-9]+)/remove/',
			array(
				'methods' => 'POST',
				'callback' => array( $this, 'api_remove' ),
				'permission_callback' => function () {
					return WS_Form_Common::can_user( 'edit_form' );
				},
			)
		);

		register_rest_route(
			WS_FORM_RESTFUL_NAMESPACE,
			'/select2/wc_category_search/',
			array(
				'methods' => 'GET',
				'callback' => array( $this, 'api_category_search' ),
				'permission_callback' => function () {
					return WS_Form_Common::can_user( 'edit_form' );
				},
			)
		);
		register_rest_route(
			WS_FORM_RESTFUL_NAMESPACE,
			'/select2/wc_category_cache/',
			array(
				'methods' => 'POST',
				'callback' => array( $this, 'api_category_cache' ),
				'permission_callback' => function () {
					return WS_Form_Common::can_user( 'edit_form' );
				},
			)
		);

		register_rest_route(
			WS_FORM_RESTFUL_NAMESPACE,
			'/select2/wc_tag_search/',
			array(
				'methods' => 'GET',
				'callback' => array( $this, 'api_tag_search' ),
				'permission_callback' => function () {
					return WS_Form_Common::can_user( 'edit_form' );
				},
			)
		);
		register_rest_route(
			WS_FORM_RESTFUL_NAMESPACE,
			'/select2/wc_tag_cache/',
			array(
				'methods' => 'POST',
				'callback' => array( $this, 'api_tag_cache' ),
				'permission_callback' => function () {
					return WS_Form_Common::can_user( 'edit_form' );
				},
			)
		);

		register_rest_route(
			WS_FORM_RESTFUL_NAMESPACE,
			'/select2/wc_product_search/',
			array(
				'methods' => 'GET',
				'callback' => array( $this, 'api_product_search' ),
				'permission_callback' => function () {
					return WS_Form_Common::can_user( 'edit_form' );
				},
			)
		);
		register_rest_route(
			WS_FORM_RESTFUL_NAMESPACE,
			'/select2/wc_product_cache/',
			array(
				'methods' => 'POST',
				'callback' => array( $this, 'api_product_cache' ),
				'permission_callback' => function () {
					return WS_Form_Common::can_user( 'edit_form' );
				},
			)
		);
	}

	// API endpoint - Search tags
	public function api_category_search( $parameters ) {

		return self::term_search( $parameters, 'product_cat' );
	}

	// API endpoint - Search tags
	public function api_tag_search( $parameters ) {

		return self::term_search( $parameters, 'product_tag' );
	}

	// Term search
	public function term_search( $parameters, $taxonomy ) {

		global $wpdb;

		$term = WS_Form_Common::get_query_var_nonce( 'term', '', $parameters );
		$type = WS_Form_Common::get_query_var_nonce( '_type', '', $parameters );

		$results = array();

		$terms = $wpdb->get_results( sprintf( 'SELECT DISTINCT t.term_id, t.name FROM %1$sterms AS t LEFT JOIN %1$stermmeta AS tm ON ( t.term_id = tm.term_id AND tm.meta_key=\'order\' ) INNER JOIN %1$sterm_taxonomy AS tt ON t.term_id = tt.term_id WHERE tt.taxonomy IN (\'%2$s\' ) AND ((t.name LIKE \'%3$s%%\' ) OR (t.slug LIKE \'%3$s%%\' )) AND (( tm.meta_key = \'order\' OR tm.meta_key IS NULL )) ORDER BY tm.meta_value+0 ASC, t.name ASC', $wpdb->prefix, esc_sql($taxonomy), esc_sql($term) ) );

		foreach ( $terms as $term ) {

			$results[] = array( 'id' => $term->term_id, 'text' => sprintf('%s (ID: %u)', $term->name, $term->term_id) );
		}

		return array( 'results' => $results );
	}

	// API endpoint - Search products
	public function api_product_search( $parameters ) {

		$term = WS_Form_Common::get_query_var_nonce( 'term', '', $parameters );
		$type = WS_Form_Common::get_query_var_nonce( '_type', '', $parameters );

		$results = array();

		$args          = array(

			'post_type' => 'product',
			'posts_per_page' => self::MAX_PRODUCT_SEARCH_RESULTS,
			's' => esc_sql( $term ),
		);
		$product_query = new WP_Query( $args );
		$products      = $product_query->posts;

		foreach ( $products as $product ) {

			$results[] = array( 'id' => $product->ID, 'text' => sprintf('%s (ID: %u)', $product->post_title, $product->ID) );
		}

		return array( 'results' => $results );
	}

	// API endpoint - Cache categories
	public function api_category_cache( $parameters ) {

		return self::term_cache( $parameters, 'product_cat' );
	}

	// API endpoint - Cache tags
	public function api_tag_cache( $parameters ) {

		return self::term_cache( $parameters, 'product_tag' );
	}

	// Term cache
	public function term_cache( $parameters, $taxonomy ) {

		$return_array = array();

		$term_ids = WS_Form_Common::get_query_var_nonce('ids', '', $parameters);
		foreach ( $term_ids as $term_id ) {

			$term_id = absint($term_id);

			$term = get_term($term_id);
			if (false === $term) {
				continue;
			}

			$return_array[$term_id] = sprintf( '%s (ID: %u)', $term->name, $term->term_id );
		}

		return $return_array;
	}

	// API endpoint - Cache products (Used for initial load of select2)
	public function api_product_cache( $parameters ) {

		$return_array = array();

		$product_ids = WS_Form_Common::get_query_var_nonce('ids', '', $parameters);

		foreach ( $product_ids as $product_id ) {

			$product_id = absint($product_id);

			$product_title = get_the_title($product_id);

			if ( ! empty( $product_title ) ) {

				$return_array[$product_id] = $product_title;
			}
		}

		return $return_array;
	}

	// API endpoint - Get product localize array
	public function api_get_product( $parameters ) {

		// Get product ID
		$this->product_id = WS_Form_Common::get_query_var_nonce( 'product_id', false, $parameters );
		if ( empty( $this->product_id ) ) {
			return false;
		}

		// Get product
		$this->product = wc_get_product( $this->product_id );
		if ( empty( $this->product) ) {
			return false;
		}

		// Read product meta data
		if ( ! self::read_product_meta_data() ) {
			return false;
		}

		// Get localization array
		$woocommerce_product_localize_array = self::woocommerce_product_localize_array( $this->product_id );

		// Process response
		return $woocommerce_product_localize_array;
	}

	// API endpoint - Create form from template
	public function api_post_template( $parameters ) {

		// Get product ID
		$product_id = absint( WS_Form_Common::get_query_var_nonce( 'product_id', false, $parameters ) );
		if ( 0 == $product_id ) {

			return self::api_error( __( 'Invalid product ID', 'ws-form-woocommerce' ) );
		}

		// Get template ID
		$template_id = WS_Form_Common::get_query_var_nonce( 'template_id', false, $parameters);
		if ( empty( $template_id ) ) {

			return self::api_error( __( 'Invalid template ID', 'ws-form-woocommerce' ) );
		}

		// Create form from template
		$ws_form_form = new WS_Form_Form();

		if ( 'blank' == $template_id ) {

			$ws_form_form->db_create();

		} else {

			$ws_form_form->db_create_from_template( $template_id );
		}

		$form_id = $ws_form_form->id;
		if ( 0 == $form_id ) {

			return self::api_error( __( 'Error creating form from template', 'ws-form-woocommerce' ) );
		}

		// Assign form to product ID
		self::form_assign_product( $form_id, $product_id, 'include' );
		self::form_unassign_product( $form_id, $product_id, 'exclude' );

		// Publish the form
		$ws_form_form->db_publish();

		// Set meta data on product
		$this->product_id             = $product_id;
		$wsform_woocommerce_meta_data = self::product_set_meta_data();

		// Update product form cache
		self::build_product_form_cache( $product_id );

		return array( 'error' => false, 'form_id' => $form_id, 'form_list' => self::get_form_list( $product_id ), 'meta_data' => $wsform_woocommerce_meta_data );
	}

	// Set form ID of product
	public function product_set_meta_data() {

		// Set form ID of product
		if ( ! self::product_set( $this->product_id ) ) {
			return;
		}

		// Delete product meta data
		self::delete_meta_data( $this->product );

		// Set meta data to defaults
		$wsform_woocommerce_meta_data = array();
		foreach ( $this->meta_keys as $meta_key ) {

			$name = $meta_key['name'];
			$id   = 'wsf-woocommerce-' . $meta_key['id'];

			$value = isset( $meta_key['default_template'] ) ? $meta_key['default_template'] : '';
			if ( '' == $value ) {

				$value = isset( $meta_key['default'] ) ? $meta_key['default'] : '';
			}

			$wsform_woocommerce_meta_data[$name] = $value;
		}

		// Update meta data
		$this->product->update_meta_data( self::META_KEY_PRODUCT, $wsform_woocommerce_meta_data );
		$this->product->save_meta_data();

		// Return meta data (This is used to correctly configure the client side)
		return $wsform_woocommerce_meta_data;
	}

	// API endpoint - Assign existing form
	public function api_form_assign( $parameters ) {

		// Get product ID
		$product_id = absint( WS_Form_Common::get_query_var_nonce( 'product_id', false, $parameters ) );
		if ( 0 == $product_id ) {

			return self::api_error( __( 'Invalid product ID', 'ws-form-woocommerce' ) );
		}

		// Get form ID
		$form_id = absint(WS_Form_Common::get_query_var_nonce( 'form_id', false, $parameters));
		if ( empty( $form_id ) ) {

			return self::api_error( __( 'Invalid for ID', 'ws-form-woocommerce' ) );
		}

		// Create form from template
		$ws_form_form     = new WS_Form_Form();
		$ws_form_form->id = $form_id;

		// Assign form to product ID
		self::form_assign_product($form_id, $product_id, 'include' );
		self::form_unassign_product($form_id, $product_id, 'exclude' );

		// Publish the form
		$ws_form_form->db_publish();

		// Set meta data on product
		$this->product_id             = $product_id;
		$wsform_woocommerce_meta_data = self::product_set_meta_data();

		// Update product form cache
		self::build_product_form_cache( $product_id );

		return array( 'error' => false, 'form_id' => $form_id, 'form_list' => self::get_form_list( $product_id ), 'meta_data' => $wsform_woocommerce_meta_data );
	}

	// API endpoint - Remove product assignment from form
	public function api_remove( $parameters ) {

		// Get product ID
		$product_id = absint( WS_Form_Common::get_query_var_nonce( 'product_id', false, $parameters ) );
		if ( 0 == $product_id ) {

			return self::api_error(__( 'Invalid product ID', 'ws-form-woocommerce' ));
		}

		// Read published form data
		$ws_form_form = new WS_Form_Form();
		$forms        = $ws_form_form->db_read_all('', "status = 'publish'");
		if (is_array($forms)) {

			foreach ( $forms as $form ) {

				$publish_form = self::form_unassign_product($form['id'], $product_id, 'include' );
				self::form_unassign_product($form['id'], $product_id, 'exclude' );

				// Publish form
				if ($publish_form) {

					$ws_form_form->id = $form['id'];
					$ws_form_form->db_publish();
				}
			}
		}

		// Update product form cache
		self::build_product_form_cache( $product_id );

		return array( 'error' => false );
	}

	// API endpoint - Error
	public function api_error( $error_message ) {

		return array( 'error' => true, 'error_message' => $error_message );
	}

	// WS Form - Config E-Commerce
	public function config_ecommerce( $ecommerce ) {

		// Cart price types
		$ecommerce['cart_price_types']['woocommerce_price_regular']         = array( 'label' => __( 'WooCommerce Regular Price', 'ws-form-woocommerce' ), 'sum' => false, 'priority' => 500 );
		$ecommerce['cart_price_types']['woocommerce_price']                 = array( 'label' => __( 'WooCommerce Price', 'ws-form-woocommerce' ), 'sum' => false, 'priority' => 510 );
		$ecommerce['cart_price_types']['woocommerce_price_total']           = array( 'label' => __( 'WooCommerce Total', 'ws-form-woocommerce' ), 'sum' => false, 'priority' => 520 );
		$ecommerce['cart_price_types']['woocommerce_price_cart_item_total'] = array( 'label' => __( 'WooCommerce Cart Item Total', 'ws-form-woocommerce' ), 'sum' => false, 'priority' => 530 );

		// Meta keys
		$post_type_object = get_post_type_object( 'shop_order' );
		if ( $post_type_object->_edit_link ) {

			$mask_url = str_replace ( '%d', '#value', admin_url( $post_type_object->_edit_link) ) . '&action=edit';
			$mask     = sprintf ( '<a href="%s">##value</a>', $mask_url );
			$ecommerce['meta_keys'][self::META_KEY_SUBMIT_ORDER_ID] = array( 'label' => __( 'WooCommerce Order ID', 'ws-form-woocommerce' ), 'mask' => $mask, 'priority' => 1200 );
		}

		return $ecommerce;
	}

	// Get product ID
	public function product_id_get( $method = 'queried_object' ) {

		switch ($method) {

			case 'queried_object':
				$queried_object = get_queried_object();
				$post_id        = ( ! is_null( $queried_object ) && isset( $queried_object->ID ) ) ? $queried_object->ID : false;
				break;

			case 'post':
				global $post;
				$post_id = ( $post && isset( $post->ID ) ) ? $post->ID : false;
				break;

			case 'root':
				$post_root = WS_Form_Common::get_post_root();
				$post_id   = $post_root ? $post_root->ID : false;
				break;
		}

		// Check post ID
		if (
			empty( $post_id ) ||
			( 'product' !== get_post_type( $post_id ) )
		) {
			
			switch ($method) {

				case 'queried_object':
					$post_id = self::product_id_get( 'post' );
					break;

				case 'post':
					$post_id = self::product_id_get( 'root' );
					break;

				default:
					return false;
			}
		}

		return $post_id;
	}

	// Set product (Object)
	public function product_set( $product_id = false ) {

		if ( false === $product_id ) {

			$this->product_id = self::product_id_get();

		} else {

			$this->product_id = $product_id;
		}

		if (
			empty( $this->product_id ) ||
			( 'product' !== get_post_type( $this->product_id ) )
		) {

			$this->product_id = false;

			return false;
		}

		// Read product
		$this->product = wc_get_product( $this->product_id );
		if ( empty( $this->product) ) {

			return false;
		}

		// Product is good
		return $this->product;
	}

	// WooCommerce - Read product data
	public function read_product_meta_data( $check_form_id = true ) {

		// Get product meta data
		$this->meta_data = $this->product->get_meta( self::META_KEY_PRODUCT, true );

		// Read meta data
		foreach ( $this->meta_keys as $meta_key_config ) {

			$name = $meta_key_config['name'];
			if (in_array($name, array( 'form_id' ))) {
				continue;
			}
			$meta_value = self::get_product_meta_data_value( $this->meta_data, $name );
			if ( false === $meta_value ) {
				$meta_value = isset( $meta_key_config['default'] ) ? $meta_key_config['default'] : '';
			}
			$this->{$name} = $meta_value;
		}

		// Get form ID
		$this->form_id = self::get_product_form_id( $this->product_id );

		// Check form_id
		if ( empty( $this->form_id ) ) {

			if ( $check_form_id ) {
				return false;
			}

		} elseif ( 'form' === $this->settings_source ) {

			// Read form
			$ws_form_form     = new WS_Form_Form();
			$ws_form_form->id = $this->form_id;

			try {

				$form_array = $ws_form_form->db_read_published();

			} catch ( Exception $e ) {

				return false;
			}

			$form = json_decode(json_encode($form_array));

			// Read meta data
			foreach ( $this->meta_keys as $meta_key_config ) {

				$name     = $meta_key_config['name'];
				$meta_key = $meta_key_config['meta_key'];
				if ( in_array( $name, array( 'form_id', 'settings_source' )) ) {
					continue;
				}
				$meta_value = WS_Form_Common::get_object_meta_value( $form, $meta_key );
				if ( false === $meta_value ) {
					$meta_value = isset( $meta_key_config['default'] ) ? $meta_key_config['default'] : '';
				}
				$this->{'form_' . $name} = $meta_value;
			}

				$this->use_form_settings = true;

		} else {

			$this->use_form_settings = false;
		}

		return true;
	}

	// WooCommerce - Before add to cart button() {
	public function before_add_to_cart_button() {

		// Read product ID
		$product_id = self::product_id_get();
		if ( empty ( $product_id ) ) {
			return;
		}

		// Get form ID
		$form_id = self::get_product_form_id( $product_id );
		if ( empty ( $form_id ) ) {
			return;
		}

		// Build shortcode
		$shortcode = sprintf( '[%s id="%u" element="div"]', WS_FORM_SHORTCODE, $form_id );

		// Do and echo shortcode
		echo do_shortcode( $shortcode );

		// Check for AJAX request
		if ( wp_doing_ajax() ) {

			// Initialize form if AJAX request executes JavaScript
			echo "<script type=\"text/javascript\">if ( typeof( wsf_form_init ) === 'function' ) { wsf_form_init(); }</script>";
		}

		// Add to localize array
		self::woocommerce_product_localize_array( $product_id );
	}

	// Localize array - WooCommerce product
	public function woocommerce_product_localize_array( $lookup_product_id ) {

		// Check if already set
		if ( isset( $this->localize_array[ $lookup_product_id ] ) ) {
			return $this->localize_array;
		}

		// For one time AJAX requests, set product ID
		$this->localize_array['product_id_last'] = $lookup_product_id;

		// Use $lookup_product_id to set product
		if ( ! self::product_set( $lookup_product_id ) ) {
			return $this->localize_array;
		}

		// Read product meta data
		self::read_product_meta_data();

		// Get product lookups
		$lookup_price         = array( $lookup_product_id => $this->product->get_price() );
		$lookup_price_regular = array( $lookup_product_id => $this->product->get_regular_price() );

		/**
		 * Function for `woocommerce_quantity_input_min` filter-hook.
		 * 
		 * @since  1.0.0
		 *
		 * @param  $min_purchase_quantity 
		 * @param  $product 
		 *
		 * @return $min_purchase_quantity
		 */
		$lookup_quantity_min  = array( $lookup_product_id => apply_filters( 'woocommerce_quantity_input_min', $this->product->get_min_purchase_quantity(), $this->product) );

		/**
		 * Function for `woocommerce_quantity_input_max` filter-hook.
		 * 
		 * @since  1.0.0
		 *
		 * @param  $max_purchase_quantity 
		 * @param  $product 
		 *
		 * @return $max_purchase_quantity
		 */
		$lookup_quantity_max  = array( $lookup_product_id => apply_filters( 'woocommerce_quantity_input_max', $this->product->get_max_purchase_quantity(), $this->product) );

		// Get variation lookups
		if ( $this->product->has_child() ) {

			foreach ( $this->product->get_children() as $variation_id ) {

				$variation = wc_get_product( $variation_id );

				$lookup_price[$variation_id]         = $variation->get_price();
				$lookup_price_regular[$variation_id] = $variation->get_price();

				/**
				 * Function for `woocommerce_quantity_input_min` filter-hook.
				 * 
				 * @since  1.0.0
				 *
				 * @param  $min_purchase_quantity 
				 * @param  $product 
				 *
				 * @return $min_purchase_quantity
				 */
				$lookup_quantity_min[$variation_id]  = apply_filters( 'woocommerce_quantity_input_min', $this->product->get_min_purchase_quantity(), $variation);

				/**
				 * Function for `woocommerce_quantity_input_min` filter-hook.
				 * 
				 * @since  1.0.0
				 *
				 * @param  $min_purchase_quantity 
				 * @param  $product 
				 *
				 * @return $min_purchase_quantity
				 */
				$lookup_quantity_max[$variation_id]  = apply_filters( 'woocommerce_quantity_input_max', $this->product->get_max_purchase_quantity(), $variation);
			}
		}

		// Get accounting lookups
		if ( ! isset( $this->localize_array['account'] ) ) {

			$account = array(

				'lookup_price'          => $lookup_price,
				'lookup_price_regular'  => $lookup_price_regular,
				'lookup_quantity_min'   => $lookup_quantity_min,
				'lookup_quantity_max'   => $lookup_quantity_max,
				'suffix'                => $this->product->get_price_suffix(),
				'symbol'                => get_woocommerce_currency_symbol(),
				'decimal'               => esc_attr( wc_get_price_decimal_separator() ),
				'thousand'              => esc_attr( wc_get_price_thousand_separator() ),
				'precision'             => wc_get_price_decimals(),
				'format'                => esc_attr( str_replace (array( '%1$s', '%2$s' ), array( '%s', '%v' ), get_woocommerce_price_format() ) ),
			);

			$this->localize_array['account'] = $account;
		}

		// Build localize
		$localize_array = array(

			'product_id'        => $this->product_id,
			'cart_item_key'     => self::get_query_arg_cart_item_key(),
			'style_woocommerce' => WS_Form_Common::option_get( 'action_' . $this->id . '_style_woocommerce', '' ),
		);

		// Determine which WooCommerce fields exist
		$woocommerce_field_localize = array(

			'wc_subtotal'    => 'product_price_variation_hide',
			'wc_add_to_cart' => 'product_add_to_cart_hide',
			'wc_quantity'    => 'product_quantity_hide',
		);

		// Get form fields
		if ( $this->form_id ) {

			// Read form meta
			$ws_form_form     = new WS_Form_Form();
			$ws_form_form->id = $this->form_id;

			try {

				$form_array = $ws_form_form->db_read_published();
				$form       = json_decode(json_encode($form_array));
				$fields     = ( false === $form ) ? array() : WS_Form_Common::get_fields_from_form($form);

			} catch ( Exception $e ) {

				$fields = array();
			}

		} else {

			$fields = array();
		}

		// Get field_types to search for
		$field_types = array_keys($woocommerce_field_localize);

		// Get all WooCommerce fields that, if included, will result in the WooCommerce component being hidden
		$field_types_found = array();
		foreach ( $fields as $field ) {

			if (
				isset( $field->type ) &&
				in_array( $field->type, $field_types ) &&
				! isset( $field_types_found[ $field->type ] )
			) {

				$field_types_found[ $field->type ] = true;
			}
		}

		foreach ( $woocommerce_field_localize as $field_type => $localize_key ) {

			switch ( $this->use_form_settings ? $this->{ 'form_' . $localize_key } : $this->{ $localize_key } ) {

				// Auto
				case '':
					$localize_array[ $localize_key ] = isset( $field_types_found[ $field_type ] );
					break;

				case 'yes': // WooCommerce uses 'yes'
				case 'on':  // WS Form uses 'on' (checkbox default value)
					$localize_array[ $localize_key ] = true;
					break;

				default:
					$localize_array[ $localize_key ] = false;
					break;

			}
		}

		// Add meta keys
		foreach ( $this->meta_keys as $meta_key ) {

			if ( ! isset( $meta_key['localize'] ) ) {

				continue;
			}
			if ( $meta_key['localize'] ) {

				$localize_array[ $meta_key['name'] ] = $this->use_form_settings ? $this->{ 'form_' . $meta_key['name'] } : $this->{ $meta_key['name'] };
			}
		}

		// Add to localize array
		$this->localize_array[$lookup_product_id] = $localize_array;

		return $this->localize_array;
	}

	// WordPress footer
	public function wp_footer() {

		if ( 0 === count( $this->localize_array ) ) {
			return;
		}

		?>
<script type='text/javascript'>
/* <![CDATA[ */
var ws_form_settings_woocommerce_product = <?php echo json_encode($this->localize_array); ?>;
/* ]]> */
</script>
		<?php
	}

	// WooCommerce - Product data tabs
	public function product_data_tabs( $product_data_tabs ) {

		$product_data_tabs['wsf_woocommerce'] = array(

			'label'  => __( 'WS Form', 'ws-form-woocommerce' ),
			'target' => 'wsf_woocommerce_data',
		);

		return $product_data_tabs;
	}

	// Product updated
	public function update_product( $product_id ) {

		self::build_product_form_cache( $product_id );
	}

	// Product deleted
	public function deleted_post( $product_id ) {

		if ('product' !== get_post_type($product_id)) {
			return;
		}

		// Read published form data
		$ws_form_form = new WS_Form_Form();
		$forms        = $ws_form_form->db_read_all('', "status = 'publish'");
		if (is_array($forms)) {

			foreach ( $forms as $form ) {

				self::form_unassign_product($form['id'], $product_id, 'include' );
				self::form_unassign_product($form['id'], $product_id, 'exclude' );
			}
		}

		// Update product form cache
		self::build_product_form_cache( $product_id );
	}

	// Form deleted
	public function form_change( $form_id ) {

		// Update product form cache
		self::build_product_form_cache();
	}

	public function form_assign_product( $form_id, $product_id, $type ) {

		$ws_form_meta            = new WS_Form_Meta();
		$ws_form_meta->object    = 'form';
		$ws_form_meta->parent_id = $form_id;

		// Get existing mapping
		$mapping_found = false;
		$mapping       = $ws_form_meta->db_read(sprintf('wc_form_assign_product_%s_mapping', $type));

		if ( is_object($mapping) ) {
			$mapping = (array) $mapping; // Legacy support
		}

		if ( is_array($mapping) && ( count($mapping) > 0 ) ) {

			// Check to see if mapping already exists
			foreach ( $mapping as $key => $map ) {

				$map = (object) $map; // Legacy support

				if ( ! isset( $map->wc_form_assign_product ) || ( '' == $map->wc_form_assign_product ) ) {
					continue;
				}
				$map_product_id = absint($map->wc_form_assign_product);
				if ( 0 == $map_product_id ) {
					unset($mapping[$key]);
				}
				if ($map_product_id == $product_id) {
					$mapping_found = true;
				}
			}
		}

		if ( ! is_array( $mapping ) ) {

			$mapping = array();
		}

		// If mapping does not exist, add it
		if ( ! $mapping_found ) {

			$mapping[] = array( 'wc_form_assign_product' => strval($product_id) );
		}

		// Update mapping meta
		$update_array = array( sprintf('wc_form_assign_product_%s_mapping', $type) => $mapping );

		// Form assigment should be filtered if currently set to off
		$form_assign = $ws_form_meta->db_read(sprintf('wc_form_assign', $type));
		if ('' == $form_assign) {

			$update_array['wc_form_assign'] = 'filter';
		}

		$ws_form_meta->db_update_from_array($update_array);
	}

	// Unassign form from product
	public function form_unassign_product( $form_id, $product_id, $type ) {

		$ws_form_meta            = new WS_Form_Meta();
		$ws_form_meta->object    = 'form';
		$ws_form_meta->parent_id = $form_id;

		// Get existing form mapping
		$mapping = $ws_form_meta->db_read(sprintf('wc_form_assign_product_%s_mapping', $type));

		if ( is_object($mapping) ) {
			$mapping = (array) $mapping; // Legacy support
		}

		$mapping_updated = false;

		if ( is_array($mapping) && ( count($mapping) > 0 ) ) {

			foreach ( $mapping as $key => $map ) {

				$map = (object) $map; // Legacy support

				if ( ! isset( $map->wc_form_assign_product ) ) {
					continue;
				}
				$map_product_id = absint($map->wc_form_assign_product);
				if (( $map_product_id == $product_id ) || ( 0 == $map_product_id )) {
					unset($mapping[$key]);
					$mapping_updated = true;
				}
			}
		}

		// If mapping updated, write new meta data
		if ($mapping_updated) {

			$ws_form_meta->db_update_from_array(array( sprintf('wc_form_assign_product_%s_mapping', $type) => $mapping ));
		}

		return $mapping_updated;
	}

	// Settings to add to all actions
	public function action_settings( $action_settings, $action ) {

		// Check action settings
		if (
			! is_object($action_settings) ||
			! is_array($action_settings->fieldsets)
		) {
			return $action_settings;
		}

		// Check to see if this action is bypassed for WooCommerce orders
		$woocommerce_bypass = isset($action->woocommerce_bypass) ? $action->woocommerce_bypass : false;
		if ( $woocommerce_bypass ) {
			return $action_settings;
		}

		// Get action ID
		$action_id = $action->id;

		// Add meta_keys
		reset( $action_settings->fieldsets );
		$action_settings_fieldsets_key_first = key( $action_settings->fieldsets );
		$action_settings->fieldsets[ $action_settings_fieldsets_key_first ]['meta_keys'][] = 'wc_order_statuses_action_post';

		return $action_settings;
	}

	// Term deleted
	public function delete_term( $term_id, $tt_id, $taxonomy ) {

		// Check taxonomy
		if ( ! in_array( $taxonomy, array( 'product_cat', 'product_tag' ) ) ) {
			return;
		}

		// Get type
		switch ($taxonomy) {

			case 'product_cat':
				$type = 'category';
				break;

			case 'product_tag':
				$type = 'tag';
				break;
		}

		// Form meta object
		$ws_form_meta         = new WS_Form_Meta();
		$ws_form_meta->object = 'form';

		// Read published form data
		$ws_form_form = new WS_Form_Form();
		$forms        = $ws_form_form->db_read_all('', "status = 'publish'");

		if ( is_array( $forms ) ) {

			foreach ( $forms as $form ) {

				// Get existing form mapping
				$ws_form_meta->parent_id = $form['id'];
				$mapping                 = $ws_form_meta->db_read(sprintf('wc_form_assign_product_%s_mapping', $type));

				// Get assign key
				$assign_key = sprintf('wc_form_assign_product_%s', $type);

				$mapping_updated = false;

				if ( ! empty($mapping) && ( count($mapping) > 0 ) ) {

					foreach ( $mapping as $key => $map ) {

						$map = (object) $map; // Legacy support

						if ( ! isset( $map->{$assign_key} ) || ( '' == $map->{$assign_key} ) ) {
							continue;
						}
						$map_term_id = absint($map->{$assign_key});
						if ( ( $map_term_id == $term_id ) || ( 0 == $map_term_id ) ) {
							unset($mapping[$key]);
							$mapping_updated = true;
						}
					}
				}

				// If mapping updated, write new meta data
				if ($mapping_updated) {

					$ws_form_meta->db_update_from_array(array( sprintf('wc_form_assign_product_%s_mapping', $type) => $mapping ));

					if (count($mapping) == 0) {

						$ws_form_meta->db_update_from_array(array( sprintf('wc_form_assign_product_%s_filter', $type) => '' ));
					}
				}
			}
		}

		// Update product form cache
		self::build_product_form_cache();
	}

	// Form published
	public function form_publish( $form ) {

		self::build_product_form_cache();
	}

	// Build product to form cache
	public function build_product_form_cache( $product_id_filter = false ) {

		// Read product to form cache
		$product_form_cache = ( false === $product_id_filter ) ? array() : get_option(self::PRODUCT_FORM_CACHE_OPTION, array());
		if ( !is_array( $product_form_cache ) ) {
			$product_form_cache = array();
		}

		// Build product IDs
		if ( false === $product_id_filter ) {

			// All products
			global $wpdb;
			$product_ids = $wpdb->get_col( "SELECT ID FROM {$wpdb->prefix}posts WHERE post_type = 'product' AND NOT post_status = 'trash';" );

		} else {

			// Single product
			if ( isset( $product_form_cache[$product_id_filter] ) ) {

				unset( $product_form_cache[$product_id_filter] );
			}

			// Use single product ID
			$product_ids = array( $product_id_filter );
		}

		// Build term ID to product ID lookup
		$term_id_product_id_lookup = array();

		foreach ( $product_ids as $product_id ) {

			$term_ids = wp_get_post_terms( $product_id, array( 'product_cat', 'product_tag' ), array( 'fields' => 'ids' ) );
			foreach ( $term_ids as $term_id ) {

				if ( ! isset($term_id_product_id_lookup[$term_id]) ) {

					$term_id_product_id_lookup[$term_id] = array();
				}
				if ( ! in_array($product_id, $term_id_product_id_lookup[$term_id]) ) {

					$term_id_product_id_lookup[$term_id][] = $product_id;
				}
			}
		}

		// Read published form data
		$ws_form_form = new WS_Form_Form();
		$where        = "status = 'publish' AND published_checksum != ''";
		$forms        = $ws_form_form->db_read_all('', $where, '', '', '', false, true, 'published' );
		if ( is_array( $forms ) ) {

			foreach ( $forms as $form ) {

				$form_published = $form['published'];
				$form           = json_decode($form_published);

				// Check assignment
				$wc_form_assign = WS_Form_Common::get_object_meta_value($form, 'wc_form_assign' );

				// No assignment, skip
				if ('' == $wc_form_assign) { 
					continue;
				}

				// All product
				if ('all' == $wc_form_assign) {

					foreach ( $product_ids as $product_id ) {

						$product_form_cache[$product_id][$form->id] = array( 'form_id' => $form->id, 'match' => 'all', 'priority' => 25 );
					}

					continue;
				}

				// Include
				self::build_product_form_cache_term_process('product_cat', 'include', $form, $term_id_product_id_lookup, $product_form_cache);
				self::build_product_form_cache_term_process('product_tag', 'include', $form, $term_id_product_id_lookup, $product_form_cache);
				self::build_product_form_cache_product_process('include', $form, $product_form_cache, $product_id_filter);

				// Exclude
				self::build_product_form_cache_term_process('product_cat', 'exclude', $form, $term_id_product_id_lookup, $product_form_cache);
				self::build_product_form_cache_term_process('product_tag', 'exclude', $form, $term_id_product_id_lookup, $product_form_cache);
				self::build_product_form_cache_product_process('exclude', $form, $product_form_cache, $product_id_filter);  
			}
		}

		// Prioritize list according to priority
		if ( false === $product_id_filter ) {

			// All products
			foreach ( $product_form_cache as $key => $product ) {

				$priority = array_column($product_form_cache[$key], 'priority' );
				array_multisort($priority, SORT_DESC, $product_form_cache[$key]);
			}

		} elseif ( isset( $product_form_cache[$product_id_filter] ) ) {

			$priority = array_column($product_form_cache[$product_id_filter], 'priority' );
			array_multisort($priority, SORT_DESC, $product_form_cache[$product_id_filter]);
		}

		// Update option
		update_option(self::PRODUCT_FORM_CACHE_OPTION, $product_form_cache, 'no' );

		return $product_form_cache;
	}

	// Process terms for form cache
	public function build_product_form_cache_term_process( $taxonomy, $type, $form, $term_id_product_id_lookup, &$product_form_cache ) {

		// Product terms
		$mapping = WS_Form_Common::get_object_meta_value($form, sprintf('wc_form_assign_%s_%s_mapping', $taxonomy, $type));

		if ( is_object ($mapping) ) {
			$mapping = (array) $mapping; // Legacy support
		}

		if ( is_array($mapping) && ( count($mapping) > 0 ) ) {

			$product_ids = array();

			foreach ( $mapping as $map ) {

				$map = (object) $map; // Legacy support

				$term_id = isset($map->{sprintf('wc_form_assign_%s', $taxonomy)}) ? absint($map->{sprintf('wc_form_assign_%s', $taxonomy)}) : 0;
				if (0 == $term_id) {
					continue;
				}

				if (isset($term_id_product_id_lookup[$term_id])) {

					$product_ids = $term_id_product_id_lookup[$term_id];

					switch ($type) {

						case 'include':
							foreach ( $product_ids as $product_id ) {

								if ( ! isset($product_form_cache[$product_id]) ) {
									$product_form_cache[$product_id] = array();
								}
								$product_form_cache[$product_id][$form->id] = array( 'form_id' => $form->id, 'match' => $taxonomy, 'taxonomy' => $taxonomy, 'term_id' => $term_id, 'priority' => ( ( 'product_cat' == $taxonomy )  ? 75 : 50 ) );
							}
							break;

						case 'exclude':
							foreach ( $product_ids as $product_id ) {

								if (isset($product_form_cache[$product_id]) && isset($product_form_cache[$product_id][$form->id])) {

									unset($product_form_cache[$product_id][$form->id]);
								}
							}
							break;
					}
				}
			}
		}
	}

	// Process product for form cache
	public function build_product_form_cache_product_process( $type, $form, &$product_form_cache, $product_id_filter ) {

		$mapping = WS_Form_Common::get_object_meta_value($form, sprintf('wc_form_assign_product_%s_mapping', $type));

		if ( is_object( $mapping ) ) {
			$mapping = (array) $mapping; // Legacy support
		}

		if ( is_array($mapping) && ( count($mapping) > 0 ) ) {

			$product_ids = array();

			foreach ( $mapping as $map ) {

				$map = (object) $map; // Legacy support

				if ( ! isset($map->wc_form_assign_product) || ( '' == $map->wc_form_assign_product ) ) {
					continue;
				}
				$product_id = absint($map->wc_form_assign_product);
				if ( 
					( 0 == $product_id ) ||
					( ( false !== $product_id_filter ) && ( $product_id !== $product_id_filter ) ) ||
					( in_array ( get_post_status( $product_id ), array( false, 'trash' ) ) )    // Check if product exists / trashed
				) {
					continue;
				}
				$product_ids[] = $product_id;
			}

			switch ($type) {

				case 'include':
					foreach ( $product_ids as $product_id ) {

						if ( ! isset( $product_form_cache[$product_id] ) ) {
							$product_form_cache[$product_id] = array();
						}
						$product_form_cache[$product_id][$form->id] = array( 'form_id' => $form->id, 'match' => 'product', 'product_id' => $product_id, 'priority' => 100 );
					}

					break;

				case 'exclude':
					foreach ( $product_ids as $product_id ) {

						if ( isset( $product_form_cache[$product_id] ) && isset( $product_form_cache[$product_id][$form->id] ) ) {

							unset( $product_form_cache[$product_id][$form->id] );
						}
					}

					break;
			}
		}
	}

	// Get form list
	public function get_form_list( $product_id ) {

		if ( false === $product_id ) {
			return false;
		}

		$product_form_cache = get_option( self::PRODUCT_FORM_CACHE_OPTION, false );
		if ( false === $product_form_cache ) {
			$product_form_cache = self::build_product_form_cache( $product_id );
		}

		if ( ! isset( $product_form_cache[$product_id] ) ) {
			return array();
		}

		$matches = $product_form_cache[$product_id];

		$form_list = array();

		$ws_form_form = new WS_Form_Form();

		foreach ( $matches as $match ) {

			$form_id = $match['form_id'];

			$ws_form_form->id = $form_id;
			try {

				$form = $ws_form_form->db_read_published();

			} catch ( Exception $e ) {

				continue;
			}

			if ( false === $form ) {
				continue;
			}

			// Build match string
			$match_description = __( 'Unknown', 'ws-form-woocommerce' );
			switch ( $match['match'] ) {

				case 'product_cat':
					$term = get_term($match['term_id'], $match['match']);
					if ( $term ) {

						/* translators: %s: Term name that matched the product form assignment */
						$match_description = sprintf(__( 'Matched product category: %s.', 'ws-form-woocommerce' ), $term->name);

					} else {

						/* translators: %s: Term name that matched the product form assignment */
						$match_description = sprintf(__( 'Matched product category.', 'ws-form-woocommerce' ));
					}
					break;

				case 'product_tag':
					$term = get_term($match['term_id'], $match['match']);
					if ( $term ) {

						/* translators: %s: Term name that matched the product form assignment */
						$match_description = sprintf('Matched product tag: %s.', $term->name);

					} else {

						/* translators: %s: Term name that matched the product form assignment */
						$match_description = sprintf(__( 'Matched product tag.', 'ws-form-woocommerce' ));
					}
					break;

				case 'product':
					$match_description = 'Matched product.';
					break;

				case 'all':
					$match_description = 'All products.';
					break;
			}

			// Add form to list
			$form_list[] = array(

				'id'                => $form_id,
				'label'             => is_array($form) ? $form['label'] : $form->label, // Legacy support
				'svg'               => $ws_form_form->get_svg(),
				'match'             => $match['match'],
				'match_description' => $match_description,
				'button_label'      => __( 'Edit Settings', 'ws-form-woocommerce' ),
				'priority'          => $match['priority'],
			);
		}

		return $form_list;
	}

	// WooCommerce - Product data panels
	public function product_data_panels( $product_data_tabs ) {

		// Check product
		if ( ! self::product_set() ) {
			return;
		}

		$ws_form_form = new WS_Form_Form();

		// Get form IDs
		$form_list = self::get_form_list( $this->product_id );

		// Is assigned?
		$form_is_assigned = ( count($form_list) > 0 );

		// Get all forms
		$forms     = $ws_form_form->db_read_all( '', "NOT (status = 'trash' ) AND NOT (published_checksum = '' )", 'label ASC', '', '', false );
		$has_forms = ( $forms && count($forms) > 0 );

		// Read product meta data (Do not check form_id)
		self::read_product_meta_data( false );

		// Check to see if a tab should be opened
		$tab = ( WS_Form_Common::get_query_var( 'wsf_wc_tab', false ) == 'wsf_woocommerce_tab' );
		?>

<script type="text/javascript">

var wsf_woocommerce_form_list = <?php echo json_encode($form_list); ?>;

</script>

<div id="wsf_woocommerce_data" class="panel woocommerce_options_panel wc-metaboxes-wrapper hidden">
		<?php

		// Show WS Form tab
		if ( $tab ) {
			?>
<script type="text/javascript">

var wsf_woocommerce_form_list = <?php echo json_encode($form_list); ?>;

(function($){

	$( document.body ).on( 'wc-init-tabbed-panels', function() {

		setTimeout(function() { $('#woocommerce-product-data ul.wc-tabs > li.wsf_woocommerce_tab a' ).click(); }, 250);
	});

})(jQuery);

</script>
			<?php
		}
		?>
<!-- Form assigned -->
		<?php
			echo '<div id="wsf_woocommerce_panel_form_assigned" class="wsf_woocommerce_panel' . ( !$form_is_assigned ? ' wsf_woocommerce_panel_hidden' : '' ) . '">';
		?>
<div id="wsf_woocommerce_panel_form_assigned_header">
<div class="wsf-grid">
	<div id="wsf_woocommerce_assigned_preview" class="wsf-tile">
		<div data-wsf-woocommerce-form-edit data-wsf-woocommerce-svg class="wsf-template"></div>
	</div>
	<div id="wsf_woocommerce_assigned_options" class="wsf-tile">
		<p>This product is customized using form:<br /><a data-wsf-woocommerce-form-edit data-wsf-woocommerce-form-label></a></p>
		<ul class="wsf-list-inline">
			<li><button class="button button-primary" type="button" data-wsf-woocommerce-form-edit>Edit Form</button></li>
			<li wsf_woocommerce_panel_form_assigned_single><button class="button" type="button" data-wsf-woocommerce-remove>Remove Form</button></li>
		</ul>
		<p wsf_woocommerce_panel_form_assigned_single>Need to assign this form to more products?<br /><a data-wsf-woocommerce-form-edit-woocommerce>Edit Assignment Settings</a></p>
	</div>
</div>
</div>
<div id="wsf_woocommerce_panel_form_assigned_multiple">
<h4><span class="wsf-woocommerce-form-assigned-warning"><?php WS_Form_Common::render_icon_16_svg('warning' ); ?></span> <?php esc_html_e( 'Multiple Assignments', 'ws-form-woocommerce' ); ?></h4>

<p><?php printf('<strong>%s</strong> %s', esc_html('This product is assigned to more than one form.', 'ws-form-woocommerce' ), esc_html('You should assign products to a single form. The forms that contain assignment criteria matching this product are shown below. The first form in the list is used to customize this product.', 'ws-form-woocommerce' )); ?></p>

<table class="wp-list-table widefat fixed striped">

<thead>
	<tr>
		<th id="wsf-woocommerce-form-assign-th-id"><?php esc_html_e('ID', 'ws-form-woocommerce' ); ?></th>
		<th><?php esc_html_e('Form', 'ws-form-woocommerce' ); ?></th>
		<th><?php esc_html_e('Match', 'ws-form-woocommerce' ); ?></th>
		<th id="wsf-woocommerce-form-assign-th-action"><?php esc_html_e('Action', 'ws-form-woocommerce' ); ?></th>
	</tr>
</thead>

<tbody>
</tbody>

</table>

</div>

<div class="options_group">
<h4><?php esc_html_e( 'Configuration', 'ws-form-woocommerce' ); ?></h4>
		<?php
		// WooCommerce settings source
		self::woocommerce_render_meta( 'wc_settings_source' );
		?>
</div>

<div id="wsf_woocommerce_panel_settings_source_product">

<div class="options_group">
<h4><?php esc_html_e( 'Catalog', 'ws-form-woocommerce' ); ?></h4>
		<?php

		// Add to cart text
		self::woocommerce_render_meta( 'wc_catalog_add_to_cart_text' );
		?>
</div>

<div class="options_group">
<h4><?php esc_html_e( 'Product', 'ws-form-woocommerce' ); ?></h4>
		<?php
		// Remove WooCommerce price
		self::woocommerce_render_meta( 'wc_price_disable' );

		// Before WooCommerce price
		self::woocommerce_render_meta( 'wc_price_prefix' );

		// After WooCommerce price
		self::woocommerce_render_meta( 'wc_price_suffix' );

		// Variation - Hide
		self::woocommerce_render_meta( 'wc_product_price_variation_hide' );

		// Quantity - Hide
		self::woocommerce_render_meta( 'wc_product_quantity_hide' );

		// Add to cart - Hide
		self::woocommerce_render_meta( 'wc_product_add_to_cart_hide' );

		// Form validation
		self::woocommerce_render_meta( 'wc_product_form_validate' );

		// Clear hidden fields
		self::woocommerce_render_meta( 'wc_product_clear_hidden_meta_values' );
		?>
</div>

<div class="options_group">
<h4><?php esc_html_e( 'Cart', 'ws-form-woocommerce' ); ?></h4>
		<?php
		// Allow negative form calculations
		self::woocommerce_render_meta( 'wc_cart_edit' );

		// Allow negative form calculations
		self::woocommerce_render_meta( 'wc_cart_price_plugin_allow_negative' );
		?>
</div>

<div class="options_group">
<h4><?php esc_html_e( 'WS Form', 'ws-form-woocommerce' ); ?></h4>
		<?php
		// Fire actions
		self::woocommerce_render_meta( 'wc_actions_fire' );
		?>
</div>

</div>

</div>
<!-- /Form assigned -->

<!-- Form new -->
		<?php
		echo '<div id="wsf_woocommerce_panel_form_add" class="wsf_woocommerce_panel' . ( $form_is_assigned ? ' wsf_woocommerce_panel_hidden' : '' ) . '">';
		?>
<p>To customize this product, choose a template or <a data-wsf-woocommerce-panel-show="form_existing">choose an existing form</a>.</p>
		<?php

		// List templates
		$ws_form_template    = new WS_Form_Template();
		$template_categories = $ws_form_template->read_config(array( WS_FORM_WOOCOMMERCE_PLUGIN_DIR_PATH . 'includes/templates/form/config.json' ));
		$template_category   = $template_categories[0];
		?>
<ul class="wsf-templates">
		<?php
		$ws_form_template->template_category_render($template_category, 'button button-primary' );
		?>
</ul>

</div>
<!-- /Form new -->

<!-- Form existing -->
<div id="wsf_woocommerce_panel_form_existing" class="wsf_woocommerce_panel wsf_woocommerce_panel_hidden">

<p>To customize this product, choose a published form or <a data-wsf-woocommerce-panel-show="form_add">create a new form</a>.</p>
		<?php

		// Form ID
		$options = array( '' => __( 'Select...', 'ws-form-woocommerce' ) );
		foreach ( $forms as $form ) {

			$options[$form['id']] = $form['label'] . ' (ID: ' . $form['id'] . ' )';
		}
		woocommerce_wp_select( array(

			'id'            => 'wsf-woocommerce-form-id-select',
			'label'         => __( 'Form', 'ws-form-woocommerce' ),
			'value'         => $this->form_id,
			'options'       => $options,
			'description'   => '&nbsp;',
		) );
		?>
</div>
<!-- /Form existing -->

<!-- Form new - Loader -->
<div id="wsf_woocommerce_panel_loader" class="wsf_woocommerce_panel wsf_woocommerce_panel_hidden">
<div id="wsf-loader" class="wsf-loader-on"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 91.3 91.1"><circle fill="#a3a3a3" cx="45.7" cy="45.7" r="45.7"/><circle fill="#fff" cx="45.7" cy="24.4" r="12.5"/></svg></div>
</div>
<!-- /Form new - Loader -->
		<?php
		// Plugin JS
		$handle = 'wsform-' . $this->id . '-admin-js';

		wp_register_script( $handle, WS_FORM_WOOCOMMERCE_PLUGIN_DIR_URL . 'js/admin.js', array( 'jquery' ), self::WS_FORM_LICENSE_VERSION, true);

		// Build localize
		$localize_array = array(

			'x_wp_nonce'           => wp_create_nonce('wp_rest' ),
			'wsf_nonce_field_name' => WS_FORM_POST_NONCE_FIELD_NAME,
			'wsf_nonce'            => wp_create_nonce(WS_FORM_POST_NONCE_ACTION_NAME),

			'id'                   => $this->id,
			'edit_url'             => WS_Form_Common::get_admin_url( 'ws-form-edit' ) . '&product_id=' . $this->product_id . '&id=',
			'edit_url_woocommerce' => WS_Form_Common::get_admin_url( 'ws-form-edit' ) . '&product_id=' . $this->product_id . '&sidebar=form&tab=woocommerce&id=',
			'ajax_url'             => WS_Form_Common::get_api_path(),
			'meta_keys'            => $this->meta_keys,
		);

		wp_localize_script( $handle, 'ws_form_settings_woocommerce_product', $localize_array );
		wp_enqueue_script( $handle );
		?>
<!-- Hidden fields -->
<input type="hidden" id="wsf-woocommerce-form-id" value="<?php esc_attr_e($this->form_id); ?>" />
		<?php
		wp_nonce_field(WS_FORM_POST_NONCE_ACTION_NAME, WS_FORM_POST_NONCE_FIELD_NAME);
		?>
<!-- /Hidden fields -->

</div>
		<?php
	}

	// WooCommerce - Render meta key
	public function woocommerce_render_meta( $meta_key ) {

		if ( ! isset($this->meta_key_config[ $meta_key ]) ) {
			return false;
		}

		$meta_key_config = $this->meta_key_config[ $meta_key ];

		$id    = $meta_key_config[ 'id' ];
		$label = isset( $meta_key_config[ 'label_woo' ] ) ? $meta_key_config[ 'label_woo' ] : $meta_key_config[ 'label' ];
		$name  = $meta_key_config[ 'name' ];
		$value = $this->{ $name };
		$type  = $meta_key_config[ 'type' ];
		$help  = isset( $meta_key_config[ 'help' ] ) ? $meta_key_config [ 'help' ] : false;

		// Base args
		$args = array(

			'id'    => 'wsf-woocommerce-' . $id,
			'name'    => 'wsf-woocommerce-' . $id,
			'label' => $label,
			'value' => $value,
		);

		if (false !== $help) {
			$args[ 'description' ] = $help;
		}

		switch ( $type ) {

			case 'text':
				woocommerce_wp_text_input( $args );
				break;

			case 'checkbox':
				woocommerce_wp_checkbox( $args );
				break;

			case 'radio':
				$options             = $meta_key_config[ 'options' ];
				$options_woocommerce = array();
				foreach ( $options as $key => $option ) {
					$options_woocommerce[ $option[ 'value' ] ] = $option[ 'text' ];
				}
				$args[ 'options' ] = $options_woocommerce;
				woocommerce_wp_radio( $args );
				break;

			case 'select':
				$options             = $meta_key_config[ 'options' ];
				$options_woocommerce = array();
				foreach ( $options as $option ) {
					$options_woocommerce[ $option[ 'value' ] ] = $option[ 'text' ];
				}
				$args[ 'options' ] = $options_woocommerce;
				woocommerce_wp_select( $args );
				break;
		}
	}

	// WooCommerce - Process product meta
	public function process_product_meta( $post_id, $post ) {

		// Read meta data
		$wsform_woocommerce_meta_data = array();
		foreach ( $this->meta_keys as $meta_key ) {

			$name                                = $meta_key['name'];
			$id                                  = 'wsf-woocommerce-' . $meta_key['id'];
			$this->{$name}                       = WS_Form_Common::get_query_var_nonce( $id );
			$wsform_woocommerce_meta_data[$name] = $this->{$name};
		}

		// Update meta data
		if ( ! self::product_set( $post_id ) ) {
			return;
		}

		$this->product->update_meta_data(self::META_KEY_PRODUCT, $wsform_woocommerce_meta_data);
		$this->product->save_meta_data();
	}

	public function add_cart_item_data( $cart_item_data, $product_id, $variation_id, $ws_form_submit = false ) {

		// If submit object is false
		if ( false === $ws_form_submit ) {
			// Check a form ID is present
			$form_id = absint( WS_Form_Common::get_query_var_nonce( 'wsf_form_id', 0 ) );
			if ( 0 === $form_id ) {
				return $cart_item_data;
			}
		}

		// If data is already set, just return it
		if (
			isset($cart_item_data[self::META_KEY_CART_ITEM_WS_FORM_SUBMIT]) &&
			isset($cart_item_data[self::META_KEY_CART_ITEM_DATA])
		) {

			return $cart_item_data;
		}

		// Use product_id to set product
		if ( ! self::product_set( $product_id ) ) {
			return $cart_item_data;
		}

		// Read product meta data
		if ( ! self::read_product_meta_data() ) {
			return $cart_item_data;
		}

		try {

			// If submit object is false
			if ( false === $ws_form_submit ) {
				// Create ws_form_submit
				$ws_form_submit = new WS_Form_Submit();

				// Setup from post
				$ws_form_submit->setup_from_post();
			}

			// Clear hidden meta values
			if (
				( $this->use_form_settings ? $this->form_product_clear_hidden_meta_values : $this->product_clear_hidden_meta_values ) &&
				method_exists( $ws_form_submit, 'clear_hidden_meta_values' )
			) {

				$ws_form_submit->clear_hidden_meta_values();
			}

		} catch ( Exception $e ) {
			WS_Form_Common::throw_error( $e->getMessage() );
		}

		// Build item data
		$item_data = array();

		// Get fields in single dimension array
		$fields = WS_Form_Common::get_fields_from_form( $ws_form_submit->form_object );

		// Get field types
		$field_types = WS_Form_Config::get_field_types_flat( true );

		// Build submit meta cache
		$submit_meta_cache = array();
		foreach ( $ws_form_submit->meta as $meta_data ) {

			if ( ! is_array( $meta_data ) || ! isset( $meta_data['id'] ) ) {
				continue;
			}

			// Ignore repeatable fields (we'll use fallback)
			$repeatable_index = ( isset( $meta_data['repeatable_index'] ) ? $meta_data['repeatable_index'] : false );
			if ( $repeatable_index ) {
				continue;
			}

			$submit_meta_cache[$meta_data['id']] = $meta_data;
		}

		// Exclude fields that are not saved or excluded from WooCommerce cart (wc_exclude_cart)
		$fields_process = array();
		foreach ( $fields as $key => $field ) {

			$field_type = $field->type;

			if ( ! isset( $field_types[$field_type] ) ) {
				continue;
			}

			// Check wc_exclude_cart
			$wc_exclude_cart = WS_Form_Common::get_object_meta_value( $field, 'wc_exclude_cart', false );
			if ( $wc_exclude_cart ) {
				continue;
			}

			$field_type_config = $field_types[$field_type];

			$submit_save = isset( $field_type_config['submit_save'] ) ? $field_type_config['submit_save'] : false;

			if ( $submit_save ) {

				$fields_process[$key] = $field;
			}
		}

		foreach ( $fields_process as $field ) {

			// Get key
			$key = $field->label;

			// Get value
			$value       = '';
			$submit_meta = isset( $submit_meta_cache[$field->id] ) ? $submit_meta_cache[$field->id] : false;
			if ( false === $submit_meta ) {
				continue;
			}

			// Process according to type
			switch ( $submit_meta['type'] ) {

				// Choice
				case 'select':
				case 'radio':
				case 'checkbox':
				case 'price_select':
				case 'price_radio':
				case 'price_checkbox':
					$value = WS_Form_Common::parse_variables_meta_value($ws_form_submit->form_object, $submit_meta, 'text/html', true, 'wc', 0, ', ' );
					if ( empty( $value ) ) {
						break;
					}
					$value = is_array( $value ) ? implode( ', ', $value ) : $value;
					break;

				// File
				case 'file':
				case 'signature':
					$files = $submit_meta['value'];

					if ( empty( $files) ) {
						break;
					}

					$value_array = array();

					foreach ( $files as $file ) {

						$file_name     = $file['name'];
						$file_size     = WS_Form_Common::get_file_size( $file['size'] );
						$value_array[] = $file_name . ( ( 'file' == $submit_meta['type'] ) ? ( ' (' . $file_size . ' )' ) : '' );
					}

					$value = implode( ', ', $value_array );
					break;

				// Google Map
				case 'googlemap':
					$value = $submit_meta['value'];

					if (
						is_array($value) &&
						isset($value['lat']) &&
						isset($value['lng'])
					) {

						$value = sprintf('%.7f, %.7f', $value['lat'], $value['lng']);

					} else {

						$value = '';
					}

					break;

				default:
					/**
					 * Function for `wsf_woocommerce_submit_meta_data` filter-hook.
					 * 
					 * @since  1.0.0
					 *
					 * @param  $submit_meta
					 *
					 * @return $submit_meta
					 */
					$submit_meta = apply_filters( 'wsf_woocommerce_submit_meta_data', $submit_meta );
					$value       = $submit_meta['value'];
					break;
			}

			// Skip empty submit meta
			if (
				! is_string( $value ) ||
				( '' === $value )
			) {
				continue;
			}

			// Add to item data
			$item_data[] = array(

				'key'     => esc_html($field->label),
				'value'   => esc_html($value),
				'display' => '',
			);
		}

		// Remove protected data
		$ws_form_submit->db_remove_meta_protected();

		// Compact (Stop WooCommerce session filling up with trash)
		$ws_form_submit->db_compact();

		// Save ws_form_submit to cart item
		$cart_item_data[self::META_KEY_CART_ITEM_WS_FORM_SUBMIT] = $ws_form_submit;

		// Save item data
		$cart_item_data[self::META_KEY_CART_ITEM_DATA] = $item_data;

		return $cart_item_data;
	}

	public function cart_id( $cart_id, $product_id, $variation_id, $variation, $cart_item_data ) {

		// Check if sold individually checked
		$product_data = wc_get_product( $variation_id ? $variation_id : $product_id );

		if ( $product_data->is_sold_individually() ) {
			// Return cart ID that represents the root product so behavior matches WooCommerce behavior
			if (
				! is_array( $cart_item_data ) ||
				! isset( $cart_item_data['wsf_submit'] ) ||
				! isset( $cart_item_data['wsf_item_data'] )
			) {
				return $cart_id;
			}

			// Remove WS Form meta data
			if ( isset( $cart_item_data['wsf_submit'] ) ) {
				unset($cart_item_data['wsf_submit']);
			}
			if ( isset( $cart_item_data['wsf_item_data'] ) ) {
				unset($cart_item_data['wsf_item_data']);
			}

			// Generate cart ID using WC_Cart generate_cart_id method (Initial conditions will prevent loop)
			$cart_id = WC()->cart->generate_cart_id( $product_id, $variation_id, $variation, $cart_item_data);
		}

		return $cart_id;
	}

	public function get_cart_item_from_session( $cart_item, $values ) {

		// Check for item data
		if ( isset( $values[self::META_KEY_CART_ITEM_DATA] ) ) {
			$cart_item[self::META_KEY_CART_ITEM_DATA] = $values[self::META_KEY_CART_ITEM_DATA];
		}

		// Check for WS Form submit
		if ( isset( $values[self::META_KEY_CART_ITEM_WS_FORM_SUBMIT] ) ) {
			$cart_item[self::META_KEY_CART_ITEM_WS_FORM_SUBMIT] = $values[self::META_KEY_CART_ITEM_WS_FORM_SUBMIT];
			$cart_item = self::cart_item_calculate($cart_item);
		}

		return $cart_item;
	}

	public function order_again_cart_item_data( $data, $item, $order ) {

		// Get submit ID
		$submit_id = $item->get_meta( self::META_KEY_ORDER_SUBMIT_ID );
		if ( empty ( $submit_id ) ) {
			return $data;
		}

		// Check for subscription renewal
		if ( is_array( $data ) && isset( $data['subscription_renewal'] ) ) {
			return $data;
		}

		// Get ws_form_submit
		try {

			$ws_form_submit     = new WS_Form_Submit();
			$ws_form_submit->id = $submit_id;
			$ws_form_submit->db_read( true, true, true );
			$ws_form_submit->db_form_object_read();

		} catch ( Exception $e) {

			return $data;
		}

		// Get product ID
		$product_id = $item->get_product_id();

		// Get variation ID
		$variation_id = $item->get_variation_id();

		// Process meta data
		$cart_item_data = self::add_cart_item_data( array(), $product_id, $variation_id, $ws_form_submit );

		return $cart_item_data;
	}

	public function cart_item_calculate( $cart_item ) {

		if ( ! isset( $cart_item[self::META_KEY_CART_ITEM_WS_FORM_SUBMIT] ) ) {
			return $cart_item;
		}

		// Get ws_form_submit
		$ws_form_submit = $cart_item[self::META_KEY_CART_ITEM_WS_FORM_SUBMIT];

		// Get cart item price
		$cart_item_price = $cart_item['data']->get_price( 'edit' );

		// Get root product ID
		$root_product_id = $cart_item['product_id'];

		// Use root_product_id to set product
		if ( ! self::product_set( $root_product_id ) ) {
			return $cart_item;
		}

		// Read product meta data
		if ( ! self::read_product_meta_data() ) {
			return $cart_item;
		}

		// Get plugin price if set
		$price_plugin = WS_Form_Common::get_number( self::get_submit_meta_value( $ws_form_submit, 'ecommerce_cart_total', 0) );

		// Check for negative amounts
		if ( ! ( $this->use_form_settings ? $this->form_cart_price_plugin_allow_negative : $this->cart_price_plugin_allow_negative ) && ( 0 > $price_plugin ) ) {

			throw new Exception('Illegal WS Form cart amount. Enable negative cart amounts.' );
		}

		// Calculate new price
		$cart_item_price_new = $cart_item_price + $price_plugin;

		// Set new price
		$cart_item['data']->set_price( $cart_item_price_new );

		// Share addons_price_before_calc with other plugins (e.g. Dynamic Pricing)
		$cart_item['addons_price_before_calc'] = $cart_item_price;

		// Add form price to $cart_item for third party plugins
		$cart_item['wsform_total'] = $price_plugin;

		return $cart_item;
	}

	public function cart_item_permalink( $permalink, $cart_item, $cart_item_key ) {

		// Get product ID
		$product_id = $cart_item[ 'product_id' ];

		// Get permalink (in case another plugin clears this)
		if ( empty ( $permalink ) ) {

			$permalink_product_id = ( isset( $cart_item['variation_id'] ) && !empty( $cart_item['variation_id']) ) ? $cart_item['variation_id'] : $cart_item['product_id'];
			$permalink            = get_permalink( $permalink_product_id );

			if ( false === $permalink ) {
				return '';
			}
		}

		// Get product
		$product = wc_get_product( $product_id );
		if ( empty ( $product ) ) {
			return $permalink;
		}

		// Get product meta data
		$meta_data = $product->get_meta( self::META_KEY_PRODUCT, true );
		if ( empty ( $meta_data ) ) {
			return $permalink;
		}

		// Get form ID
		$form_id = self::get_product_form_id( $product_id );
		if ( empty( $form_id ) ) {
			return $permalink;
		}

		// Get settings source
		$settings_source = self::get_product_meta_data_value( $meta_data, 'settings_source' );

		if ( 'form' == $settings_source ) {

			// Read cart edit setting from form settings
			$ws_form_form     = new WS_Form_Form();
			$ws_form_form->id = $form_id;

			try {

				$form_array = $ws_form_form->db_read_published();

			} catch ( Exception $e) {

				return $permalink;
			}

			$form      = json_decode(json_encode($form_array));
			$cart_edit = WS_Form_Common::get_object_meta_value( $form, 'wc_cart_edit' );

		} else {

			// Read cart edit setting from product settings
			$cart_edit = self::get_product_meta_data_value( $meta_data, 'cart_edit' );
		}

		if ( $cart_edit ) {
			$permalink = add_query_arg( array( self::QUERY_ARG_CART_ITEM_KEY => $cart_item_key ), $permalink );
		}

		return $permalink;
	}


	public function populate( $populate_array ) {

		// Get cart item key
		$cart_item_key = self::get_query_arg_cart_item_key();
		if ( false === $cart_item_key ) {
			return $populate_array;
		}

		// Get cart item
		$cart_item = WC()->cart->get_cart_item( $cart_item_key );
		if ( empty( $cart_item) ) {
			return $populate_array;
		}

		// Get product ID
		$product_id = $cart_item['product_id'];

		// Use product_id to set product
		if ( ! self::product_set( $product_id ) ) {
			return $populate_array;
		}

		// Read product meta data
		if ( ! self::read_product_meta_data() ) {
			return $populate_array;
		}

		$cart_edit = ( $this->use_form_settings ? $this->form_cart_edit : ( isset( $this->cart_edit ) ? $this->cart_edit : false ) );
		if ( ! $cart_edit ) {
			return $populate_array;
		}

		// Get ws_form_submit
		if ( ! isset( $cart_item[self::META_KEY_CART_ITEM_WS_FORM_SUBMIT] ) ) {
			return $populate_array;
		}
		$ws_form_submit = $cart_item[self::META_KEY_CART_ITEM_WS_FORM_SUBMIT];

		$return_array = array();

		// Get form fields
		foreach ( $ws_form_submit->meta as $meta_key => $meta_value ) {

			if ( ! is_array( $meta_value ) ) {
				continue;
			}
			if ( ! isset( $meta_value['id'] ) ) {
				continue;
			}

			$field_id                = $meta_value['id'];
			$value                   = $meta_value['value'];
			$repeatable_index_string = '';

			if (
				isset( $meta_value['repeatable_index'] ) &&
				!empty( $meta_value['repeatable_index'] )
			) {
				$repeatable_index_string = sprintf('_%u', absint( $meta_value['repeatable_index'] ) );
			}

			$return_array[WS_FORM_FIELD_PREFIX . $field_id . $repeatable_index_string] = $value;
		}

		// Check for section_repeatable
		$section_repeatable = array();
		if (
			isset( $ws_form_submit->section_repeatable ) && 
			!empty( $ws_form_submit->section_repeatable ) &&
			is_serialized( $ws_form_submit->section_repeatable )
		) {
			$section_repeatable = unserialize( $ws_form_submit->section_repeatable );
		}

		$section_repeatable = is_array( $section_repeatable ) ? $section_repeatable : array();

		return array(
			'action_label' => $this->label,
			'data' => $return_array,
			'section_repeatable' => $section_repeatable,
		);
	}

	public function add_to_cart( $cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data ) {

		// Use product_id to set product
		if ( ! self::product_set( $product_id ) ) {
			return;
		}

		// Read product meta data
		if ( ! self::read_product_meta_data() ) {
			return;
		}

		$cart_edit = ( $this->use_form_settings ? $this->form_cart_edit : ( isset( $this->cart_edit ) ? $this->cart_edit : false ) );
		if ( ! $cart_edit ) {
			return;
		}

		// Remove cart item
		$cart_item_key = WS_Form_Common::get_query_var( self::QUERY_ARG_CART_ITEM_KEY );

		if ( false !== $cart_item_key ) {
			WC()->cart->remove_cart_item( $cart_item_key );
		}
	}

	public function get_query_arg_cart_item_key() {

		$cart_item_key = false;

		if ( isset( $_GET ) && isset( $_GET[self::QUERY_ARG_CART_ITEM_KEY] ) ) { // phpcs:ignore

			$cart_item_key = sanitize_text_field( $_GET[self::QUERY_ARG_CART_ITEM_KEY] ); // phpcs:ignore
		}

		if ( empty( $cart_item_key ) ) {

			return false;
		}

		return $cart_item_key;
	}

	public function get_item_data( $item_data, $cart_item ) {

		// Run through item data and remove wsf_submit object if it exists
		foreach ( $item_data as $key => $item ) {

			if ( ! is_array( $item ) || ! isset( $item['key'] ) ) {
				continue;
			}

			if ( self::META_KEY_CART_ITEM_WS_FORM_SUBMIT === $item['key'] ) {
				unset( $item_data[$key] );
				break;
			}
		}

		// If cart item data not found, return item data, nothing to do
		if ( empty( $cart_item[self::META_KEY_CART_ITEM_DATA] ) ) {
			return $item_data;
		}

		// Get cart item meta data
		$ws_form_item_data = $cart_item[self::META_KEY_CART_ITEM_DATA];

		// Add cart item meta data to item data
		$item_data = array_merge( $item_data, $ws_form_item_data);

		return $item_data;
	}

	public function checkout_create_order_line_item( $item, $cart_item_key, $values, $order ) {

		// If this item is not related to WS Form, skip it
		if ( empty( $values[self::META_KEY_CART_ITEM_DATA] ) ) {
			return;
		}

		// Add field meta data
		$ws_form_item_data = $values[self::META_KEY_CART_ITEM_DATA];
		foreach ( $ws_form_item_data as $item_data ) {

			$item->add_meta_data( $item_data['key'], $item_data['value'] );
		}

		// Get submit object
		if ( empty( $values[self::META_KEY_CART_ITEM_WS_FORM_SUBMIT] ) ) {
			return;
		}
		$ws_form_submit = $values[self::META_KEY_CART_ITEM_WS_FORM_SUBMIT];

		/// Add submit to order item
		$item->add_meta_data( self::META_KEY_ORDER_WS_FORM_SUBMIT, $ws_form_submit );

		return $item;
	}

	public function checkout_order_created( $order ) {

		// Get order ID
		$order_id = $order->get_id();

		// Get order status
		$order_status = $order->get_status();

		// Get order items
		$order_items = $order->get_items();

		// Run through the order items
		foreach ( $order_items as $item_key => $item ) {

			// Process item
			$item = self::checkout_order_item_process( $order_id, $order_status, $item );
			if ( false === $item ) {
				continue;
			}
		}

		// Run initial order actions
		self::run_order_actions( $order, $order_status );
	}

	// Checkout 
	public function checkout_order_item_process( $order_id, $order_status, $item ) {

		// Get submit
		$ws_form_submit = $item->get_meta( self::META_KEY_ORDER_WS_FORM_SUBMIT );
		if (
			! is_object($ws_form_submit) ||
			! isset($ws_form_submit->id) ||
			! isset($ws_form_submit->form_id) ||
			! isset($ws_form_submit->meta)
		) {
			return false;
		}

		// Use product_id to set product
		if ( ! self::product_set( $item->get_product_id() ) ) {
			return false;
		}

		// Read product meta data
		if ( ! self::read_product_meta_data() ) {
			return false;
		}

		// Should actions be fired?
		$actions_fire = $this->use_form_settings ? $this->form_actions_fire : $this->actions_fire;

		// Action firing?
		if ( ! $actions_fire ) {
			return false;
		}

		// Read form object
		$ws_form_form     = new WS_Form_Form();
		$ws_form_form->id = $ws_form_submit->form_id;
		try {

			$form_object = $ws_form_form->db_read_published();

		} catch ( Exception $e ) {

			return false;
		}

		// Check if server side actions exist
		$server_side_action_count = 0;
		$form_actions             = WS_Form_Action::get_form_actions( $form_object );

		foreach ( $form_actions as $form_action ) {

			// Get action ID
			if ( ! isset( $form_action['id'] ) ) {
				continue;
			}
			$action_id = $form_action['id'];

			// Get action
			if ( ! isset( WS_Form_Action::$actions[$action_id] ) ) {
				continue;
			}
			$action = WS_Form_Action::$actions[$action_id];

			// WooCommerce bypass?
			$woocommerce_bypass = isset( $action->woocommerce_bypass ) ? $action->woocommerce_bypass : false;
			if ( ( 'database' !== $action_id ) && $woocommerce_bypass ) {
				continue;
			}

			++$server_side_action_count;
		}

		if ( $server_side_action_count > 0 ) {

			// Add order status to submit
			$ws_form_submit->meta[ self::META_KEY_SUBMIT_ORDER_ID ] = $order_id;

			// Add order status to submit
			$ws_form_submit->meta[ self::META_KEY_SUBMIT_ORDER_STATUS ] = $order_status;

			// Create submission (We always create a submission record)
			/**
			 * Function for `wsf_actions_post` action-hook.
			 * 
			 * @since  1.0.0
			 *
			 * @param  $form_object 
			 * @param  $submit_object 
			 * @param  $complete_action 
			 * @param  $action_id_filter 
			 * @param  $database_only
			 * @param  $process_file_fields
			 *
			 * @return void
			 */
			do_action( 'wsf_actions_post', $form_object, $ws_form_submit, false, false, true, false );
		}

		// Remove ws_form_submit meta
		$item->delete_meta_data( self::META_KEY_ORDER_WS_FORM_SUBMIT );

		// Add item meta
		$item->add_meta_data( self::META_KEY_ORDER_SUBMIT_ID, $ws_form_submit->id );
		$item->add_meta_data( self::META_KEY_ORDER_FORM_ID, $ws_form_submit->form_id );

		// Save order item
		$item->save();

		return $item;
	}
	
	// Order status changed
	public function order_status_changed( $order_id, $old_status, $new_status ) {

		// Get the order
		$order = wc_get_order( $order_id );
		if ( false === $order ) {
			return;
		}

		self::run_order_actions( $order, $new_status );
	}

	// Update order meta - Run WS Form actions
	public function run_order_actions( $order, $order_status ) {

		// Get order ID
		$order_id = $order->get_id();

		// Get order items
		$order_items = $order->get_items();

		// Run through the order items
		foreach ( $order_items as $item_key => $item ) {

			// Check if an old submit object is still attached to the order item
			$ws_form_submit = $item->get_meta( self::META_KEY_ORDER_WS_FORM_SUBMIT );
			if ( is_object( $ws_form_submit ) ) {

				// Process item
				$item = self::checkout_order_item_process( $order_id, $order_status, $item );
				if ( false === $item ) {
					continue;
				}
			}

			// Get submit ID
			$submit_id = $item->get_meta( self::META_KEY_ORDER_SUBMIT_ID );

			// Get form ID
			$form_id = $item->get_meta( self::META_KEY_ORDER_FORM_ID );

			// Legacy support
			$order_meta = $item->get_meta( self::META_KEY_ORDER_DATA_LEGACY );

			if ( is_object( $order_meta ) ) {

				if ( empty( $submit_id ) ) {

					$submit_id = isset( $order_meta->{self::META_KEY_ORDER_SUBMIT_ID_LEGACY} ) ? $order_meta->{self::META_KEY_ORDER_SUBMIT_ID_LEGACY} : ( isset( $order_meta->{self::META_KEY_ORDER_DATA_SUBMIT_ID_LEGACY} ) ? $order_meta->{self::META_KEY_ORDER_DATA_SUBMIT_ID_LEGACY} : false );
				}

				if ( empty( $form_id ) ) {

					$form_id = isset( $order_meta->{self::META_KEY_ORDER_FORM_ID_LEGACY} ) ? $order_meta->{self::META_KEY_ORDER_FORM_ID_LEGACY} : ( isset( $order_meta->{self::META_KEY_ORDER_DATA_FORM_ID_LEGACY} ) ? $order_meta->{self::META_KEY_ORDER_DATA_FORM_ID_LEGACY} : false );
				}
			}

			// Check form ID
			if ( empty( $form_id) ) {
				continue;
			}

			// Check submit ID
			if ( empty( $submit_id) ) {
				continue;
			}

			// Use product_id to set product
			if ( ! self::product_set( $item->get_product_id() ) ) {
				continue;
			}

			// Read product meta data
			if ( ! self::read_product_meta_data() ) {
				continue;
			}

			// Should actions be fired?
			$actions_fire = $this->use_form_settings ? $this->form_actions_fire : $this->actions_fire;

			// Action firing?
			if ( ! $actions_fire ) {
				continue;
			}

			// Read form object
			$ws_form_form     = new WS_Form_Form();
			$ws_form_form->id = $form_id ;
			try {

				$form_object = $ws_form_form->db_read_published();

			} catch ( Exception $e ) {

				continue;
			}

			// Check if server side actions exist
			$server_side_action_count = 0;
			$form_actions             = WS_Form_Action::get_form_actions( $form_object );

			foreach ( $form_actions as $form_action ) {

				// Get action ID
				if ( ! isset( $form_action['id'] ) ) {
					continue;
				}
				$action_id = $form_action['id'];

				// Get action
				if ( ! isset( WS_Form_Action::$actions[$action_id] ) ) {
					continue;
				}
				$action = WS_Form_Action::$actions[$action_id];

				// WooCommerce bypass?
				$woocommerce_bypass = isset( $action->woocommerce_bypass ) ? $action->woocommerce_bypass : false;
				if ( ( 'database' !== $action_id ) && $woocommerce_bypass ) {
					continue;
				}

				++$server_side_action_count;
			}
			if ( 0 === $server_side_action_count ) {
				continue;
			}

			// Read submit
			$ws_form_submit     = new WS_Form_Submit();
			$ws_form_submit->id = absint( $submit_id );
			try {

				$ws_form_submit->db_read( true, false, true );

			} catch ( Exception $e ) {

				continue;
			}

			// Parse variable values - Order
			$this->order_id = $order_id;

			$this->order_number = $order->get_order_number();

			$this->order_status = $order_status;

			$this->order_sub_total       = $order->get_subtotal();
			$this->order_sub_total_price = WS_Form_Common::get_price($this->order_sub_total);

			$this->order_discount_total       = $order->get_total_discount();
			$this->order_discount_total_price = WS_Form_Common::get_price($this->order_discount_total);

			$this->order_shipping_total       = $order->get_total_shipping();
			$this->order_shipping_total_price = WS_Form_Common::get_price($this->order_shipping_total);

			$this->order_tax_total       = $order->get_total_tax();
			$this->order_tax_total_price = WS_Form_Common::get_price($this->order_tax_total);

			$this->order_total       = $order->get_total();
			$this->order_total_price = WS_Form_Common::get_price($this->order_total);

			$post_type_object = get_post_type_object( 'shop_order' );
			if ( $post_type_object->_edit_link ) {

				$this->order_url = str_replace ( '%d', $order_id, admin_url( $post_type_object->_edit_link) ) . '&action=edit';
			}

			// Parse variable values - User
			$user = $order->get_user();

			if ( $user ) {

				// User
				$this->order_user_id         = isset($user->ID) ? $user->ID : '';
				$this->order_user_first_name = isset($user->first_name) ? $user->first_name : '';
				$this->order_user_last_name  = isset($user->last_name) ? $user->last_name : '';
				$this->order_user_email      = isset($user->email) ? $user->email : '';
			}

			// Billing
			$this->order_billing_first_name = $order->get_billing_first_name();
			$this->order_billing_last_name  = $order->get_billing_last_name();
			$this->order_billing_company    = $order->get_billing_company();
			$this->order_billing_address_1  = $order->get_billing_address_1();
			$this->order_billing_address_2  = $order->get_billing_address_2();
			$this->order_billing_city       = $order->get_billing_city();
			$this->order_billing_state      = $order->get_billing_state();
			$this->order_billing_postcode   = $order->get_billing_postcode();
			$this->order_billing_country    = $order->get_billing_country();
			$this->order_billing_email      = $order->get_billing_email();
			$this->order_billing_phone      = $order->get_billing_phone();

			// Shipping
			$this->order_shipping_first_name = $order->get_shipping_first_name();
			$this->order_shipping_last_name  = $order->get_shipping_last_name();
			$this->order_shipping_company    = $order->get_shipping_company();
			$this->order_shipping_address_1  = $order->get_shipping_address_1();
			$this->order_shipping_address_2  = $order->get_shipping_address_2();
			$this->order_shipping_city       = $order->get_shipping_city();
			$this->order_shipping_state      = $order->get_shipping_state();
			$this->order_shipping_postcode   = $order->get_shipping_postcode();
			$this->order_shipping_country    = $order->get_shipping_country();
			$this->order_shipping_phone      = $order->get_shipping_phone();

			// Add order status to submit
			$ws_form_submit->meta[self::META_KEY_SUBMIT_ORDER_STATUS] = $order_status;

			// Fire actions
			/**
			 * Function for `wsf_actions_post` action-hook.
			 * 
			 * @since  1.0.0
			 *
			 * @param  $form_object 
			 * @param  $submit_object 
			 * @param  $complete_action 
			 * @param  $action_id_filter 
			 * @param  $database_only
			 * @param  $process_file_fields
			 *
			 * @return void
			 */

			do_action( 'wsf_actions_post', $form_object, $ws_form_submit );
		}
	}

	// Should action run?
	public function action_post_do( $actions_post_do, $form, $submit, $action_id_filter, $database_only, $config ) {

		// If initial database write, then enable
		if ( $database_only ) { 
			return $actions_post_do;
		}

		// Always run database action if priority is 200
		if (
			isset( $config['id'] ) &&
			( 'database' == $config['id'] ) &&
			isset( $config['priority'] ) &&
			( 200 == $config['priority'] )
		) {
			return $actions_post_do;
		}

		// Get order status
		if (
			! isset( $submit->meta ) ||
			! isset( $submit->meta[self::META_KEY_SUBMIT_ORDER_STATUS] )
		) {
			return $actions_post_do;
		}
		$submit_order_status = $submit->meta[self::META_KEY_SUBMIT_ORDER_STATUS];

		// Check action order statuses
		if (
			! isset( $config['meta'] ) ||
			! isset( $config['meta']['wc_order_statuses_action_post'] ) ||
			! is_array($config['meta']['wc_order_statuses_action_post'] )
		) {
			// Legacy support
			$wc_order_statuses_action_post = array(
				
				array( 'wc_order_status' => 'processing' ),
				array( 'wc_order_status' => 'completed' ),
			);

		} else {
			
			// Get order statuses to fire on
			$wc_order_statuses_action_post = $config['meta']['wc_order_statuses_action_post'];
		}

		// Run through action order statuses
		foreach ( $wc_order_statuses_action_post as $wc_order_status_action_post ) {

			if (
				! is_array( $wc_order_status_action_post ) ||
				! isset( $wc_order_status_action_post['wc_order_status'] )
			) {
				continue;
			}

			$order_status = $wc_order_status_action_post['wc_order_status'];
			if ( $order_status == $submit_order_status ) {
				return $actions_post_do;
			}
		}

		return false;
	}

	// Add hidden order item meta
	public function hidden_order_itemmeta( $meta_keys ) {

		$meta_keys[] = self::META_KEY_ORDER_SUBMIT_ID;
		$meta_keys[] = self::META_KEY_ORDER_FORM_ID;

		return $meta_keys;
	}

	// Admin order item meta data
	public function after_order_itemmeta( $item_id, $item, $product ) {

		if ( ! is_object( $item ) ) {
			return;
		}

		// Get submit ID
		$submit_id = $item->get_meta( self::META_KEY_ORDER_SUBMIT_ID );

		// Get form ID
		$form_id = $item->get_meta( self::META_KEY_ORDER_FORM_ID );

		// Legacy support
		$order_meta = $item->get_meta( self::META_KEY_ORDER_DATA_LEGACY );

		if ( is_object( $order_meta ) ) {

			if ( empty( $submit_id ) ) {

				$submit_id = isset( $order_meta->{self::META_KEY_ORDER_SUBMIT_ID_LEGACY} ) ? $order_meta->{self::META_KEY_ORDER_SUBMIT_ID_LEGACY} : ( isset( $order_meta->{self::META_KEY_ORDER_DATA_SUBMIT_ID_LEGACY} ) ? $order_meta->{self::META_KEY_ORDER_DATA_SUBMIT_ID_LEGACY} : false );
			}

			if ( empty( $form_id ) ) {

				$form_id = isset( $order_meta->{self::META_KEY_ORDER_FORM_ID_LEGACY} ) ? $order_meta->{self::META_KEY_ORDER_FORM_ID_LEGACY} : ( isset( $order_meta->{self::META_KEY_ORDER_DATA_FORM_ID_LEGACY} ) ? $order_meta->{self::META_KEY_ORDER_DATA_FORM_ID_LEGACY} : false );
			}
		}

		// Check form ID
		if ( empty( $form_id ) ) {
			return;
		}

		$ws_form_form     = new WS_Form_Form();
		$ws_form_form->id = $form_id;

		// Read form data
		try {

			$form_object = $ws_form_form->db_read_published();

		} catch ( Exception $e ) {

			$form_object = false;
		}

		echo '<table cellspacing="0" class="display_meta"><tbody>';

		// Build new formatted meta - Form
		$form_link = is_object( $form_object ) && WS_Form_Common::can_user( 'edit_form' );
		if ( $form_link ) {

			printf( '<tr><th>Form:</th><td><a href="%s">%s →</a> (ID: %u)</td></tr>', esc_url( WS_Form_Common::get_admin_url( 'ws-form-edit', $form_id) ), esc_html( $form_object->label ), esc_html( $form_id ) );

		} else {

			printf( '<tr><th>Form:</th><td>%u</td></tr>', esc_html( $form_id ) );
		}

		// Get submit ID
		$submit_link = $form_link && ! empty( $submit_id ) && WS_Form_Common::can_user( 'read_submission' );

		if ( $submit_link ) {

			// Read submit
			$ws_form_submit     = new WS_Form_Submit();
			$ws_form_submit->id = absint( $submit_id );
			try {

				$ws_form_submit->db_read( true, false, true );

			} catch ( Exception $e ) {

				$submit_link = false;
			}
		}

		if ( ! empty( $submit_id ) ) {

			if ( $submit_link ) {

				printf( '<tr><th>Submission:</th><td><a href="%s">%u →</a></td></tr>', esc_url( WS_Form_Common::get_admin_url( 'ws-form-submit', $form_id) . '#' . $submit_id ), esc_html( $submit_id ) );

			} else {

				printf( '<tr><th>Submission:</th><td>%u</td></tr>', esc_html( $submit_id ) );
			}
		}

		echo '</tbody></table>';
	}

	// Admin order data after order details
	public function woocommerce_admin_order_data_after_order_details( $order ) {

		// Add WS Form NONCE
		wp_nonce_field(WS_FORM_POST_NONCE_ACTION_NAME, WS_FORM_POST_NONCE_FIELD_NAME);
	}

	// After order notes
	public function after_order_notes() {

		// Add WS Form NONCE
		wp_nonce_field(WS_FORM_POST_NONCE_ACTION_NAME, WS_FORM_POST_NONCE_FIELD_NAME);
	}

	// Add to cart text
	public function add_to_cart_text( $text ) {

		global $product;

		// Check product
		if ( ! is_object( $product ) ) {
			return $text;
		}

		// Get product ID
		$product_id = $product->get_id();

		// Get product
		$product = wc_get_product( $product_id );
		if ( empty ( $product ) ) {
			return $text;
		}

		// Get product meta data
		$meta_data       = $product->get_meta( self::META_KEY_PRODUCT, true );
		$settings_source = self::get_product_meta_data_value( $meta_data, 'settings_source' );

		// Get add to cart text
		if ( 'form' === $settings_source ) {

			// Get form ID
			$form_id = self::get_product_form_id( $product_id );
			if ( empty( $form_id ) ) {
				return $text;
			}

			// Read form meta
			$ws_form_form     = new WS_Form_Form();
			$ws_form_form->id = $form_id;

			try {

				$form_array = $ws_form_form->db_read_published();

			} catch ( Exception $e ) {

				return $text;
			}

			$form                     = json_decode(json_encode($form_array));
			$catalog_add_to_cart_text = WS_Form_Common::get_object_meta_value( $form, 'wc_catalog_add_to_cart_text' );

		} else {

			// Read product meta
			$catalog_add_to_cart_text = self::get_product_meta_data_value( $meta_data, 'catalog_add_to_cart_text' );
		}

		return ( empty($catalog_add_to_cart_text) ) ? $text : $catalog_add_to_cart_text;
	}

	public function after_shop_loop_item() {

		global $post;

		$this->product = false;

		// Check post object and ID property exist
		if ( ! $post || ! isset( $post->ID ) ) {
			return;
		}

		// Set product to current post ID
		if ( ! self::product_set( $post->ID ) ) {
			return;
		}

		// If single product, don't set
		if ( is_single( $this->product_id ) ) {
			return;
		}

		// Do not reprocess forms
		if ( isset( $this->form_config_array[ $this->product_id] ) ) {
			return;
		}

		// Read product meta data
		if ( ! self::read_product_meta_data() ) {
			return;
		}

		// Build shortcode
		$form_html = WS_Form_Common::option_get( 'action_' . $this->id . '_preload_form_json', '' ) ? '' : ' form_html="false"';
		$shortcode = sprintf( '[%s id="%u"%s]', WS_FORM_SHORTCODE, $this->form_id, $form_html );

		// Process shortcode (Do not echo it, we just want to queue up the form data in the footer)
		do_shortcode( $shortcode );

		// Add to lookup
		$this->form_config_array[ $this->product_id] = true;
	}

	public function add_to_cart_url( $url, $product ) {

		// Check product
		if ( empty( $product ) ) {
			return $url;
		}

		// Check for quick view
		if ( isset( $_GET['wc-api'] ) && ( 'WC_Quick_View' === $_GET['wc-api'] ) ) { // phpcs:ignore
			return $url;
		}

		// Get product ID
		$product_id = $product->get_id();

		// Check product ID
		if ( empty( $product_id ) ) {
			return $url;
		}

		// Get form ID on product
		$form_id = self::get_product_form_id( $product_id );

		// Check form ID
		if ( empty( $form_id ) ) {
			return $url;
		}
		
		// Return filtered permalink
		/**
		 * Function for `addons_add_to_cart_url` filter-hook.
		 * 
		 * @since  1.0.0
		 *
		 * @param  $url
		 *
		 * @return $url
		 */
		return apply_filters( 'addons_add_to_cart_url', get_permalink( $product_id ) );
	}

	public function price_html( $html, $product ) {

		if ( is_admin() ) {
			return $html;
		}

		// Get product ID
		$product_id        = $product->get_id();
		$product_parent_id = $product->get_parent_id();
		if ( $product_parent_id > 0 ) {
			$product_id = $product_parent_id;
		}

		// Get product meta data
		$meta_data = $product->get_meta( self::META_KEY_PRODUCT, true );
		if ( empty ( $meta_data ) ) {
			return $html;
		}

		// Get form ID
		$form_id = self::get_product_form_id( $product_id );
		if ( empty( $form_id ) ) {
			return $html;
		}

		// Get settings source
		$settings_source = self::get_product_meta_data_value( $meta_data, 'settings_source' );

		if ('form' == $settings_source) {

			// Read cart edit setting from form settings
			$ws_form_form     = new WS_Form_Form();
			$ws_form_form->id = $form_id;

			try {

				$form_array = $ws_form_form->db_read_published();

			} catch ( Exception $e ) {

				return $html;
			}

			$form = json_decode(json_encode($form_array));

			$price_prefix  = WS_Form_Common::get_object_meta_value( $form, 'wc_price_prefix' );
			$price_disable = WS_Form_Common::get_object_meta_value( $form, 'wc_price_disable' );
			$price_suffix  = WS_Form_Common::get_object_meta_value( $form, 'wc_price_suffix' );

		} else {

			// Read cart edit setting from product settings
			$price_prefix  = self::get_product_meta_data_value( $meta_data, 'price_prefix' );
			$price_disable = self::get_product_meta_data_value( $meta_data, 'price_disable' );
			$price_suffix  = self::get_product_meta_data_value( $meta_data, 'price_suffix' );
		}

		$html = (

			( !empty( $price_prefix ) ? '<span class="woocommerce-price-prefix">' . $price_prefix . '</span>' : '' ) .

			( !$price_disable ? $html : '' ) .

			( !empty( $price_suffix ) ? '<span class="woocommerce-price-suffix">' . $price_suffix . '</span>' : '' )
		);

		return $html;
	}

	public function product_supports( $supports, $feature, $product ) {

		// Set product
		if ( ! self::product_set( $product->get_id() ) ) {
			return $supports;
		}

		// Read product meta data
		if ( ! self::read_product_meta_data() ) {
			return $supports;
		}

		// Ensure feature is not ajax add to cart
		if ( 'ajax_add_to_cart' !== $feature ) {
			return $supports;
		}

		return false;
	}

	// Get product meta data value
	public function get_product_meta_data_value( $meta_data, $key, $default = false ) {

		if ( ! is_array( $meta_data) ) {
			return $default;
		}
		return isset( $meta_data[$key] ) ? $meta_data[$key] : $default;
	}

	// Get submit meta value
	public function get_submit_meta_value( $ws_form_submit, $meta_key, $default = false ) {

		if ( ! isset( $ws_form_submit->meta) ) {
			return $default;
		}
		return isset( $ws_form_submit->meta[$meta_key] ) ? $ws_form_submit->meta[$meta_key] : $default;
	}

	// Delete meta data
	public function delete_meta_data() {

		$this->product->delete_meta_data(self::META_KEY_PRODUCT);
		$this->product->save_meta_data();
	}

	// Get license item ID
	public function get_license_item_id() {

		return self::WS_FORM_LICENSE_ITEM_ID;
	}

	// Load config at plugin level
	public function load_config_plugin() {

		$this->configured = true;

		// Check if WooCommerce is active
		if ( ! defined( 'WC_VERSION' ) ) {
			$this->configured = false;
		}

		// Check WooCommerce version
		if ( defined( 'WC_VERSION' ) && version_compare( WC_VERSION, '3.0', '<' ) ) {
			$this->configured = false;
		}

		return $this->configured;
	}

	// Plug-in field types for this action
	public function config_field_types( $field_types ) {

		// Add exclude from cart meta_key to all field types
		foreach ( $field_types as $group_key => $group ) {

			$types = $group['types'];

			foreach ( $types as $field_type_key => $field_type ) {

				if ( 
					! isset( $field_type['fieldsets'] ) ||
					! isset( $field_type['fieldsets']['basic'] ) ||
					! isset( $field_type['fieldsets']['basic']['fieldsets'] )
				) {
					continue;
				}

				$fieldsets = $field_type['fieldsets']['basic']['fieldsets'];

				foreach ( $fieldsets as $fieldset_key => $fieldset ) {

					if ( ! isset( $fieldset['meta_keys'] ) ) {
						continue;
					}

					if ( in_array( 'exclude_email', $fieldset['meta_keys'] ) ) {

						$field_types[$group_key]['types'][$field_type_key]['fieldsets']['basic']['fieldsets'][$fieldset_key]['meta_keys'][] = 'wc_exclude_cart';
					}
					if ( in_array( 'exclude_email_on', $fieldset['meta_keys'] ) ) {

						$field_types[$group_key]['types'][$field_type_key]['fieldsets']['basic']['fieldsets'][$fieldset_key]['meta_keys'][] = 'wc_exclude_cart_on';
					}
				}
			}
		}

		// Get key index ecommerce field group
		$field_type_ecommerce_key = array_search( 'ecommerce', array_keys( $field_types) );
		if ( false === $field_type_ecommerce_key ) {

			return $field_types;
		}

		// Build new field type
		$field_type_woocommerce = array(

			'label' => __( 'WooCommerce', 'ws-form-woocommerce' ),
			'types' => array(

				'wc_subtotal'           => $field_types['content']['types']['html'],
				'wc_cart'               => $field_types['content']['types']['html'],
				'wc_total'              => $field_types['content']['types']['html'],
				'wc_quantity'           => $field_types['ecommerce']['types']['quantity'],
				'wc_ci_total'           => $field_types['content']['types']['html'],
				'wc_add_to_cart'        => $field_types['buttons']['types']['submit'],
				'wc_clear'              => $field_types['buttons']['types']['reset'],
			),
		);

		// Inject at the correct position
		++$field_type_ecommerce_key; // After group
		$field_types = array_slice( $field_types, 0, $field_type_ecommerce_key, true) + array( $this->id => $field_type_woocommerce ) + array_slice( $field_types, $field_type_ecommerce_key, count( $field_types) - 1, true);

		// Configure WooCommerce price field
		$wc_subtotal                  = &$field_types[$this->id]['types']['wc_subtotal'];
		$wc_subtotal['label']         = __( 'Subtotal', 'ws-form-woocommerce' );
		$wc_subtotal['label_default'] = __( 'Subtotal', 'ws-form-woocommerce' );
		$wc_subtotal['icon']          = WS_Form_Config::get_icon_16_svg( 'calculator' );
		$wc_subtotal['keyword']       = 'money currency ecommerce e-commerce';
		$wc_subtotal_meta_keys        = &$wc_subtotal['fieldsets']['basic']['meta_keys'];
		$html_field_key               = array_search( 'html_editor', $wc_subtotal_meta_keys );
		if ( false !== $html_field_key ) {
			$wc_subtotal_meta_keys[$html_field_key] = 'wc_subtotal_html_editor';
		}
		$wc_subtotal_meta_keys   = &$wc_subtotal['fieldsets']['advanced']['fieldsets'][1]['meta_keys'];
		$class_field_wrapper_key = array_search( 'class_field_wrapper', $wc_subtotal_meta_keys );
		if ( false !== $class_field_wrapper_key ) {
			$wc_subtotal_meta_keys[$class_field_wrapper_key] = 'wc_price_class_field_wrapper';
		}

		// Configure WS Form cart total field
		$wc_cart                  = &$field_types[$this->id]['types']['wc_cart'];
		$wc_cart['label']         = __( 'Options', 'ws-form-woocommerce' );
		$wc_cart['label_default'] = __( 'Options', 'ws-form-woocommerce' );
		$wc_cart['icon']          = WS_Form_Config::get_icon_16_svg( 'calculator' );
		$wc_cart['keyword']       = 'money currency ecommerce e-commerce';
		$wc_cart_meta_keys        = &$wc_cart['fieldsets']['basic']['meta_keys'];
		$html_field_key           = array_search( 'html_editor', $wc_cart_meta_keys );
		if ( false !== $html_field_key ) {
			$wc_cart_meta_keys[$html_field_key] = 'wc_cart_html_editor';
		}
		$wc_cart_meta_keys       = &$wc_cart['fieldsets']['advanced']['fieldsets'][1]['meta_keys'];
		$class_field_wrapper_key = array_search( 'class_field_wrapper', $wc_cart_meta_keys );
		if ( false !== $class_field_wrapper_key ) {
			$wc_cart_meta_keys[$class_field_wrapper_key] = 'wc_price_class_field_wrapper';
		}

		// Configure total field
		$wc_total                  = &$field_types[$this->id]['types']['wc_total'];
		$wc_total['label']         = __( 'Total', 'ws-form-woocommerce' );
		$wc_total['label_default'] = __( 'Total', 'ws-form-woocommerce' );
		$wc_total['icon']          = WS_Form_Config::get_icon_16_svg( 'calculator' );
		$wc_total['keyword']       = 'money currency ecommerce e-commerce';
		$wc_total_meta_keys        = &$wc_total['fieldsets']['basic']['meta_keys'];
		$html_field_key            = array_search( 'html_editor', $wc_total_meta_keys );
		if ( false !== $html_field_key ) {
			$wc_total_meta_keys[$html_field_key] = 'wc_total_html_editor';
		}
		$wc_total_meta_keys      = &$wc_total['fieldsets']['advanced']['fieldsets'][1]['meta_keys'];
		$class_field_wrapper_key = array_search( 'class_field_wrapper', $wc_total_meta_keys );
		if ( false !== $class_field_wrapper_key ) {
			$wc_total_meta_keys[$class_field_wrapper_key] = 'wc_price_class_field_wrapper';
		}

		// Configure quantity field
		$wc_quantity                            = &$field_types[$this->id]['types']['wc_quantity'];
		$wc_quantity['label']                   = __( 'Quantity', 'ws-form-woocommerce' );
		$wc_quantity['label_default']           = __( 'Quantity', 'ws-form-woocommerce' );
		$wc_quantity['mask_field_attributes'][] = 'wc_quantity';
		$wc_quantity['multiple']                = false;
		$wc_quantity['icon']                    = WS_Form_Config::get_icon_16_svg( 'quantity' );
		$wc_quantity['keyword']                 = 'number digit ecommerce e-commerce';
		$wc_quantity['submit_save']             = false;
		$wc_quantity['submit_edit']             = false;
		$wc_quantity['progress']                = false;
		unset( $wc_quantity['mask_field_attributes']['ecommerce_field_id'] );

		$wc_quantity_meta_keys = &$wc_quantity['fieldsets']['basic']['meta_keys'];
		$class_field_key       = array_search( 'label_render', $wc_quantity_meta_keys );
		if ( false !== $class_field_key ) {
			$wc_quantity_meta_keys[$class_field_key] = 'label_render_off';
		}
		$ecommerce_field_id_key = array_search( 'ecommerce_field_id', $wc_quantity_meta_keys );
		if ( false !== $ecommerce_field_id_key ) {
			unset( $wc_quantity_meta_keys[$ecommerce_field_id_key] );
		}

		// Configure cart item total field
		$wc_ci_total                  = &$field_types[$this->id]['types']['wc_ci_total'];
		$wc_ci_total['label']         = __( 'Cart Item Total', 'ws-form-woocommerce' );
		$wc_ci_total['label_default'] = __( 'Cart Item Total', 'ws-form-woocommerce' );
		$wc_ci_total['icon']          = WS_Form_Config::get_icon_16_svg( 'calculator' );
		$wc_ci_total['keyword']       = 'money currency ecommerce e-commerce';
		$wc_ci_total_meta_keys        = &$wc_ci_total['fieldsets']['basic']['meta_keys'];
		$html_field_key               = array_search( 'html_editor', $wc_ci_total_meta_keys );
		if ( false !== $html_field_key ) {
			$wc_ci_total_meta_keys[$html_field_key] = 'wc_ci_total_html_editor';
		}
		$wc_ci_total_meta_keys   = &$wc_ci_total['fieldsets']['advanced']['fieldsets'][1]['meta_keys'];
		$class_field_wrapper_key = array_search( 'class_field_wrapper', $wc_ci_total_meta_keys );
		if ( false !== $class_field_wrapper_key ) {
			$wc_ci_total_meta_keys[$class_field_wrapper_key] = 'wc_price_class_field_wrapper';
		}

		// Configure add to cart field
		$wc_submit                  = &$field_types[$this->id]['types']['wc_add_to_cart'];
		$wc_submit['label']         = __( 'Add To Cart', 'ws-form-woocommerce' );
		$wc_submit['label_default'] = __( 'Add To Cart', 'ws-form-woocommerce' );
		$wc_submit['icon']          = self::get_icon_woo();
		$wc_submit['keyword']       = 'button money currency ecommerce e-commerce';

		// Configure clear field
		$wc_clear                  = &$field_types[$this->id]['types']['wc_clear'];
		$wc_clear['label']         = __( 'Clear', 'ws-form-woocommerce' );
		$wc_clear['label_default'] = __( 'Clear', 'ws-form-woocommerce' );
		$wc_clear['icon']          = self::get_icon_woo();
		$wc_clear['keyword']       = 'button money currency ecommerce e-commerce';
		$wc_clear_meta_keys        = &$wc_clear['fieldsets']['advanced']['fieldsets'][0]['meta_keys'];
		$class_field_key           = array_search( 'class_field', $wc_clear_meta_keys );
		if ( false !== $class_field_key ) {
			$wc_clear_meta_keys[$class_field_key] = 'wc_clear_class_field';
		}

		// Add WooCommerce cart mapping
		$field_types['choice']['types']['select']['fieldsets']['options']['fieldsets'][0]['meta_keys'][] = 'select_field_wc';
		$field_types['choice']['types']['select']['mask_row_lookups'][]                                  = 'select_field_wc';

		$field_types['choice']['types']['checkbox']['fieldsets']['checkboxes']['fieldsets'][0]['meta_keys'][] = 'checkbox_field_wc';
		$field_types['choice']['types']['checkbox']['mask_row_lookups'][]                                     = 'checkbox_field_wc';

		$field_types['choice']['types']['radio']['fieldsets']['radios']['fieldsets'][0]['meta_keys'][] = 'radio_field_wc';
		$field_types['choice']['types']['radio']['mask_row_lookups'][]                                 = 'radio_field_wc';

		$field_types['ecommerce']['types']['price_select']['fieldsets']['options']['fieldsets'][0]['meta_keys'][] = 'select_price_field_wc';
		$field_types['ecommerce']['types']['price_select']['mask_row_lookups'][]                                  = 'select_price_field_wc';

		$field_types['ecommerce']['types']['price_checkbox']['fieldsets']['checkboxes']['fieldsets'][0]['meta_keys'][] = 'checkbox_price_field_wc';
		$field_types['ecommerce']['types']['price_checkbox']['mask_row_lookups'][]                                     = 'checkbox_price_field_wc';

		$field_types['ecommerce']['types']['price_radio']['fieldsets']['radios']['fieldsets'][0]['meta_keys'][] = 'radio_price_field_wc';
		$field_types['ecommerce']['types']['price_radio']['mask_row_lookups'][]                                 = 'radio_price_field_wc';

		return $field_types;
	}

	// Plugin meta keys for this action
	public function config_meta_keys( $meta_keys ) {

		$meta_keys['wc_quantity'] = array(

			'mask' => 'data-wsf-wc-quantity',
		);

		$meta_keys['wc_clear_class_field'] = array(

			'label'                     => __( 'Field CSS Classes', 'ws-form-woocommerce' ),
			'mask'                      => '#value',
			'mask_disregard_on_empty'   => true,
			'type'                      => 'text',
			'default'                   => 'reset_variations',
			'help'                      => __( 'Separate multiple classes by a space.', 'ws-form-woocommerce' ),
			'key'                       => 'class_field',
		);

		$meta_keys['wc_price_class_field_wrapper'] = array(

			'label'                     => __( 'Wrapper CSS Classes', 'ws-form-woocommerce' ),
			'mask'                      => '#value',
			'mask_disregard_on_empty'   => true,
			'type'                      => 'text',
			'default'                   => 'wsf-woocommerce-price',
			'help'                      => __( 'Separate multiple classes by a space.', 'ws-form-woocommerce' ),
			'key'                       => 'class_field_wrapper',
		);

		$meta_keys['wc_exclude_cart'] = array(

			'label'                     => __( 'Exclude From WooCommerce Cart and Orders', 'ws-form-woocommerce' ),
			'type'                      => 'checkbox',
			'default'                   => '',
			'help'                      => __( 'If checked, this field will not appear in WooCommerce carts, orders or confirmation emails.', 'ws-form-woocommerce' ),
		);

		$meta_keys['wc_exclude_cart_on'] = array(

			'label'                     => __( 'Exclude From WooCommerce Cart and Orders', 'ws-form-woocommerce' ),
			'type'                      => 'checkbox',
			'default'                   => 'on',
			'help'                      => __( 'If checked, this field will not appear in WooCommerce carts, orders or confirmation emails.', 'ws-form-woocommerce' ),
			'key'                       => 'wc_exclude_cart',
		);

		$meta_keys['wc_subtotal_html_editor'] = array(

			'label'                     => __( 'HTML', 'ws-form-woocommerce' ),
			'mask'                      => '#value',
			'mask_disregard_on_empty'   => true,
			'type'                      => 'html_editor',
			'default'                   => '<span class="woocommerce-Price-amount amount">Subtotal: <span class="woocommerce-Price-currencySymbol">#ecommerce_currency_symbol</span>#ecommerce_cart_woocommerce_price_span</span>',
			'select_list'               => true,
			'key'                       => 'html_editor',
		);

		$meta_keys['wc_cart_html_editor'] = array(

			'label'                     => __( 'HTML', 'ws-form-woocommerce' ),
			'mask'                      => '#value',
			'mask_disregard_on_empty'   => true,
			'type'                      => 'html_editor',
			'default'                   => '<span class="woocommerce-Price-amount amount">Options: <span class="woocommerce-Price-currencySymbol">#ecommerce_currency_symbol</span>#ecommerce_cart_total_span</span>',
			'select_list'               => true,
			'key'                       => 'html_editor',
		);

		$meta_keys['wc_total_html_editor'] = array(

			'label'                     => __( 'HTML', 'ws-form-woocommerce' ),
			'mask'                      => '#value',
			'mask_disregard_on_empty'   => true,
			'type'                      => 'html_editor',
			'default'                   => '<span class="woocommerce-Price-amount amount">Total: <span class="woocommerce-Price-currencySymbol">#ecommerce_currency_symbol</span>#ecommerce_cart_woocommerce_price_total_span</span>',
			'select_list'               => true,
			'key'                       => 'html_editor',
		);

		$meta_keys['wc_ci_total_html_editor'] = array(

			'label'                     => __( 'HTML', 'ws-form-woocommerce' ),
			'mask'                      => '#value',
			'mask_disregard_on_empty'   => true,
			'type'                      => 'html_editor',
			'default'                   => '<span class="woocommerce-Price-amount amount">Cart Item Total: <span class="woocommerce-Price-currencySymbol">#ecommerce_currency_symbol</span>#ecommerce_cart_woocommerce_price_cart_item_total_span</span>',
			'select_list'               => true,
			'key'                       => 'html_editor',
		);

		// Form settings - Woo tab
		$meta_keys['wc_form_assign_intro'] = array(

			'type'                     => 'html',
			'html'                     =>   sprintf('<div class="wsf-helper">%s</div>', __( 'You can use this form to customize WooCommerce products. Select which products to assign this form to.', 'ws-form-woocommerce' ) ),
		);

		$meta_keys['wc_form_assign'] = array(

			'label'   => __( 'Assign To', 'ws-form-woocommerce' ),
			'type'    => 'select',
			'options' => array(

				array( 'value' => '', 'text' => __( 'No Products', 'ws-form-woocommerce' ) ),
				array( 'value' => 'all', 'text' => __( 'All Products', 'ws-form-woocommerce' ) ),
				array( 'value' => 'filter', 'text' => __( 'Filtered Products', 'ws-form-woocommerce' ) ),
			),
			'default' => '',
		);

		// Product category mapping
		$meta_keys['wc_form_assign_product_cat_include_mapping'] = array(

			'label'     => __( 'Included Categories', 'ws-form-woocommerce' ),
			'type'      => 'repeater',
			'default'   => '',
			'meta_keys' => array(

				'wc_form_assign_product_cat',
			),
			'condition' => array(

				array(

					'logic'      => '==',
					'meta_key'   => 'wc_form_assign',
					'meta_value' => 'filter',
				),
			),
		);

		$meta_keys['wc_form_assign_product_cat_exclude_mapping'] = array(

			'label'     => __( 'Excluded Categories', 'ws-form-woocommerce' ),
			'type'      => 'repeater',
			'default'   => '',
			'meta_keys' => array(

				'wc_form_assign_product_cat',
			),
			'condition' => array(

				array(

					'logic'      => '==',
					'meta_key'   => 'wc_form_assign',
					'meta_value' => 'filter',
				),
			),
		);

		$meta_keys['wc_form_assign_product_cat'] = array(

			'label'                     => __( 'Category', 'ws-form-woocommerce' ),
			'type'                      => 'select_ajax',
			'select_ajax_method_search' => 'wc_category_search',
			'select_ajax_method_cache'  => 'wc_category_cache',
			'select_ajax_placeholder'   => __( 'Search categories...', 'ws-form-woocommerce' ),
		);

		// Product tag mapping
		$meta_keys['wc_form_assign_product_tag_include_mapping'] = array(

			'label'     => __( 'Included Tags', 'ws-form-woocommerce' ),
			'type'      => 'repeater',
			'default'   => '',
			'meta_keys' => array(

				'wc_form_assign_product_tag',
			),
			'condition' => array(

				array(

					'logic'      => '==',
					'meta_key'   => 'wc_form_assign',
					'meta_value' => 'filter',
				),
			),
		);

		$meta_keys['wc_form_assign_product_tag_exclude_mapping'] = array(

			'label'     => __( 'Excluded Tags', 'ws-form-woocommerce' ),
			'type'      => 'repeater',
			'default'   => '',
			'meta_keys' => array(

				'wc_form_assign_product_tag',
			),
			'condition' => array(

				array(

					'logic'      => '==',
					'meta_key'   => 'wc_form_assign',
					'meta_value' => 'filter',
				),
			),
		);

		$meta_keys['wc_form_assign_product_tag'] = array(

			'label'                     => __( 'Tag', 'ws-form-woocommerce' ),
			'type'                      => 'select_ajax',
			'select_ajax_method_search' => 'wc_tag_search',
			'select_ajax_method_cache'  => 'wc_tag_cache',
			'select_ajax_placeholder'   => __( 'Search tags...', 'ws-form-woocommerce' ),
		);

		// Product filtering
		$meta_keys['wc_form_assign_product_include_mapping'] = array(

			'label'     => __( 'Included Products', 'ws-form-woocommerce' ),
			'type'      => 'repeater',
			'default'   => '',
			'meta_keys' => array(

				'wc_form_assign_product',
			),
			'condition' => array(

				array(

					'logic'      => '==',
					'meta_key'   => 'wc_form_assign',
					'meta_value' => 'filter',
				),
			),
		);

		$meta_keys['wc_form_assign_product_exclude_mapping'] = array(

			'label'     => __( 'Excluded Products', 'ws-form-woocommerce' ),
			'type'      => 'repeater',
			'default'   => '',
			'meta_keys' => array(

				'wc_form_assign_product',
			),
			'condition' => array(

				array(

					'logic'      => '==',
					'meta_key'   => 'wc_form_assign',
					'meta_value' => 'filter',
				),
			),
		);

		$meta_keys['wc_form_assign_product'] = array(

			'label'                     => __( 'Product', 'ws-form-woocommerce' ),
			'type'                      => 'select_ajax',
			'select_ajax_method_search' => 'wc_product_search',
			'select_ajax_method_cache'  => 'wc_product_cache',
			'select_ajax_placeholder'   => __( 'Search products...', 'ws-form-woocommerce' ),
		);

		// WS Form - Form ID
		$meta_keys['wc_form_id'] = array(

			'name'    => 'form_id',
			'id'      => 'form-id',
			'wc'      => true,
		);

		// WS Form - Source settings
		$meta_keys['wc_settings_source'] = array(

			'label'   => __( 'Source', 'ws-form-woocommerce' ),
			'type'    => 'radio',
			'options' => array(

				array( 'value' => 'form', 'text' => __( 'Form Settings', 'ws-form-woocommerce' ) ),
				array( 'value' => '', 'text' => __( 'Custom Settings', 'ws-form-woocommerce' ) ),
			),
			'name'    => 'settings_source',
			'id'      => 'settings-source',
			'default' => 'form',
			'wc'      => true,
			'help'    => '<a data-wsf-woocommerce-form-edit-woocommerce data-wsf-woocommerce-form-edit-settings>' . __( 'Edit Form Settings', 'ws-form-woocommerce' ) . '</a>',
		);

		// WooCommerce - Catalog
		$meta_keys['wc_catalog_add_to_cart_text'] = array(

			'label'   => __( 'Button Label', 'ws-form-woocommerce' ),
			'type'    => 'text',
			'default' => __( 'Select options', 'ws-form-woocommerce' ),
			'help'    => __( 'Button label shown on catalog pages.', 'ws-form-woocommerce' ),
			'name'    => 'catalog_add_to_cart_text',
			'id'      => 'catalog-add-to-cart-text',
			'wc'      => true,
		);

		// WooCommerce - Price
		$meta_keys['wc_price_disable'] = array(

			'label'   => __( 'Hide Price', 'ws-form-woocommerce' ),
			'type'    => 'checkbox',
			'default' => '',
			'name'    => 'price_disable',
			'id'      => 'price-disable',
			'help'    => __( 'Hide WooCommerce price.', 'ws-form-woocommerce' ),
			'wc'      => true,
		);

		$meta_keys['wc_price_prefix'] = array(

			'label'   => __( 'Price Prefix', 'ws-form-woocommerce' ),
			'type'    => 'text',
			'default' => '',
			'name'    => 'price_prefix',
			'id'      => 'price-prefix',
			'wc'      => true,
		);

		$meta_keys['wc_price_suffix'] = array(

			'label'   => __( 'Price Suffix', 'ws-form-woocommerce' ),
			'type'    => 'text',
			'default' => '',
			'name'    => 'price_suffix',
			'id'      => 'price-suffix',
			'wc'      => true,
		);

		// WooCommerce - Product
		$meta_keys['wc_product_price_variation_hide'] = array(

			'label'   => __( 'Hide Variation Price', 'ws-form-woocommerce' ),
			'type'    => 'select',
			'options' => array(

				array( 'value' => '', 'text' => __( 'Auto', 'ws-form-woocommerce' ) ),
				array( 'value' => 'yes', 'text' => __( 'Hide', 'ws-form-woocommerce' ) ),
				array( 'value' => 'no', 'text' => __( 'Show', 'ws-form-woocommerce' ) ),
			),
			'default'  => '',
			'name'     => 'product_price_variation_hide',
			'id'       => 'product-price-variation-hide',
			'wc'       => true,
		);

		$meta_keys['wc_product_add_to_cart_hide'] = array(

			'label'   => __( 'Hide Add To Cart Button', 'ws-form-woocommerce' ),
			'type'    => 'select',
			'options' => array(

				array( 'value' => '', 'text' => __( 'Auto', 'ws-form-woocommerce' ) ),
				array( 'value' => 'yes', 'text' => __( 'Hide', 'ws-form-woocommerce' ) ),
				array( 'value' => 'no', 'text' => __( 'Show', 'ws-form-woocommerce' ) ),
			),
			'default' => '',
			'name'    => 'product_add_to_cart_hide',
			'id'      => 'product-add-to-cart-hide',
			'wc'      => true,
		);

		$meta_keys['wc_product_quantity_hide'] = array(

			'label'   => __( 'Hide Quantity', 'ws-form-woocommerce' ),
			'type'    => 'select',
			'options' => array(

				array( 'value' => '', 'text' => __( 'Auto', 'ws-form-woocommerce' ) ),
				array( 'value' => 'yes', 'text' => __( 'Hide', 'ws-form-woocommerce' ) ),
				array( 'value' => 'no', 'text' => __( 'Show', 'ws-form-woocommerce' ) ),
			),
			'default' => '',
			'name'    => 'product_quantity_hide',
			'id'      => 'product-quantity-hide',
			'wc'      => true,
		);

		$meta_keys['wc_product_form_validate'] = array(

			'label'   => __( 'If Form Not Valid', 'ws-form-woocommerce' ),
			'type'    => 'select',
			'options' => array(

				array( 'value' => '', 'text' => __( 'Disable Add To Cart', 'ws-form-woocommerce' ) ),
				array( 'value' => 'yes', 'text' => __( 'Show Invalid Feedback', 'ws-form-woocommerce' ) ),
			),
			'default' => '',
			'name'    => 'product_form_validate',
			'id'      => 'product-form-validate',
			'wc'      => true,
			'localize'=> true,
		);

		$meta_keys['wc_product_clear_hidden_meta_values'] = array(

			'label'   => __( 'Clear Hidden Fields', 'ws-form-woocommerce' ),
			'type'    => 'checkbox',
			'name'    => 'product_clear_hidden_meta_values',
			'id'      => 'product-clear-hidden-meta-values',
			'default' => 'on',
			'default_template' => 'on',
			'help'    => __( 'Enabling this will clear fields that were hidden when the form was submitted.', 'ws-form-woocommerce' ),
			'wc'      => true,
		);

		// WooCommerce - Cart
		$meta_keys['wc_cart_edit'] = array(

			'label'   => __( 'Editable', 'ws-form-woocommerce' ),
			'type'    => 'checkbox',
			'name'    => 'cart_edit',
			'id'      => 'cart-edit',
			'default' => '',
			'help'    => __( 'Allow form to be edited after being added to cart.', 'ws-form-woocommerce' ),
			'wc'      => true,
		);

		$meta_keys['wc_cart_price_plugin_allow_negative'] = array(

			'label'   => __( 'Allow Negative', 'ws-form-woocommerce' ),
			'type'    => 'checkbox',
			'name'    => 'cart_price_plugin_allow_negative',
			'id'      => 'cart-price-plugin-allow-negative',
			'default' => '',
			'help'    => __( 'Allow negative form amounts to be processed.', 'ws-form-woocommerce' ),
			'wc'      => true,
		);

		// WS Form - Actions
		$meta_keys['wc_actions_fire'] = array(

			'label'   => __( 'Run WS Form Actions', 'ws-form-woocommerce' ),
			'type'    => 'checkbox',
			'name'    => 'actions_fire',
			'id'      => 'actions-fire',
			'default' => 'on',
			'help'    => __( 'If checked, WS Form will run actions when the order status changes and matches the order status configured on each action.', 'ws-form-woocommerce' ),
			'wc'             => true,
		);

		// Column mapping
		$meta_keys['select_field_wc'] = array(

			'label'     => __( 'WooCommerce Cart', 'ws-form-woocommerce' ),
			'type'      => 'data_grid_field',
			'data_grid' => 'data_grid_select',
			'default'   =>  0,
			'help'      => __( 'Choose which column is saved to the cart and orders.' ),
		);

		$meta_keys['checkbox_field_wc'] = array(

			'label'     => __( 'WooCommerce Cart', 'ws-form-woocommerce' ),
			'type'      => 'data_grid_field',
			'data_grid' => 'data_grid_checkbox',
			'default'   =>  0,
			'help'      => __( 'Choose which column is saved to the cart and orders.' ),
		);

		$meta_keys['radio_field_wc'] = array(

			'label'     => __( 'WooCommerce Cart', 'ws-form-woocommerce' ),
			'type'      => 'data_grid_field',
			'data_grid' => 'data_grid_radio',
			'default'   =>  0,
			'help'      => __( 'Choose which column is saved to the cart and orders.' ),
		);

		$meta_keys['select_price_field_wc'] = array(

			'label'     => __( 'WooCommerce Cart', 'ws-form-woocommerce' ),
			'type'      => 'data_grid_field',
			'data_grid' => 'data_grid_select_price',
			'default'   =>  0,
			'help'      => __( 'Choose which column is saved to the cart and orders.' ),
		);

		$meta_keys['checkbox_price_field_wc'] = array(

			'label'     => __( 'WooCommerce Cart', 'ws-form-woocommerce' ),
			'type'      => 'data_grid_field',
			'data_grid' => 'data_grid_checkbox_price',
			'default'   =>  0,
			'help'      => __( 'Choose which column is saved to the cart and orders.' ),
		);

		$meta_keys['radio_price_field_wc'] = array(

			'label'     => __( 'WooCommerce Cart', 'ws-form-woocommerce' ),
			'type'      => 'data_grid_field',
			'data_grid' => 'data_grid_radio_price',
			'default'   =>  0,
			'help'      => __( 'Choose which column is saved to the cart and orders.' ),
		);

		// Order status
		$meta_keys['wc_order_statuses_action_post'] = array(

			'label'     => __( 'Run on WooCommerce Order Statuses', 'ws-form-woocommerce' ),
			'type'      => 'repeater',
			'meta_keys' => array(

				'wc_order_status',
			),
			'meta_keys_unique'          => array(

				'wc_order_status',
			),
			'help'      => __( 'If this form is assigned to a product, choose which WooCommerce order statuses this action will run on.', 'ws-form-woocommerce' ),
			'default'   => array(

				array(

					'wc_order_status' => 'processing',
				),

				array(

					'wc_order_status' => 'completed',
				),
			),
		);

		// Order statuses
		$meta_keys['wc_order_status'] = array(

			'label'                    => __( 'Order Status', 'ws-form-woocommerce' ),
			'type'                     => 'select',
			'options'                => array(
				array(
					'value' => '',
					'text' => __( 'Select', 'ws-form-woocommerce' ),
				),
			),
		);

		// Populate order statuses
		if ( function_exists( 'wc_get_order_statuses' ) ) {

			$order_statuses = wc_get_order_statuses();
			foreach ( $order_statuses as $order_status_id => $order_status ) {

				$meta_keys['wc_order_status']['options'][] = array( 'value' => str_replace( 'wc-', '', $order_status_id ), 'text' => $order_status );
			}
		}

		return $meta_keys;
	}

	// Woo menu in Form Settings
	public function config_settings_form_admin_action( $config_settings_form_admin ) {

		$config_settings_form_admin['sidebars']['form']['meta']['fieldsets'][$this->id] = array(

			'label'   => __( 'Woo', 'ws-form-woocommerce' ),

			'fieldsets'     => array(

				array(

					'label'     => __( 'WooCommerce Product Customization', 'ws-form-woocommerce' ),
					'meta_keys' => array( 'wc_form_assign_intro', 'wc_form_assign', 'wc_form_assign_product_cat_include_mapping', 'wc_form_assign_product_tag_include_mapping', 'wc_form_assign_product_include_mapping', 'wc_form_assign_product_cat_exclude_mapping', 'wc_form_assign_product_tag_exclude_mapping', 'wc_form_assign_product_exclude_mapping' ),
				),

				array(

					'label'     => __( 'Catalog', 'ws-form-woocommerce' ),
					'meta_keys' => array( 'wc_catalog_add_to_cart_text' ),
				),

				array(

					'label'     => __( 'Product', 'ws-form-woocommerce' ),
					'meta_keys' => array( 'wc_price_disable', 'wc_price_prefix', 'wc_price_suffix', 'wc_product_price_variation_hide', 'wc_product_add_to_cart_hide', 'wc_product_quantity_hide', 'wc_product_form_validate', 'wc_product_clear_hidden_meta_values' ),
				),

				array(

					'label'     => __( 'Cart', 'ws-form-woocommerce' ),
					'meta_keys' => array( 'wc_cart_edit', 'wc_cart_price_plugin_allow_negative' ),
				),

				array(

					'label'     => __( 'WS Form', 'ws-form-woocommerce' ),
					'meta_keys' => array( 'wc_actions_fire' ),
				),
			),
		);

		return $config_settings_form_admin;
	}

			// Config - Frameworks
	public function config_frameworks( $frameworks ) {

		// Add to Cart

		// WS Form
		$frameworks['types']['ws-form']['fields']['public']['field_types']['wc_add_to_cart']['class_field']             = array( 'wsf-button', 'wsf-button-primary' );
		$frameworks['types']['ws-form']['fields']['public']['field_types']['wc_add_to_cart']['class_field_full_button'] = array( 'wsf-button-full' );

		// Bootstrap
		$frameworks['types']['bootstrap3']['fields']['public']['field_types']['wc_add_to_cart']['class_field']              = array( 'btn', 'btn-primary' );
		$frameworks['types']['bootstrap3']['fields']['public']['field_types']['wc_add_to_cart']['class_field_full_button']  = array( 'btn-block' );
		$frameworks['types']['bootstrap4']['fields']['public']['field_types']['wc_add_to_cart']['class_field']              = array( 'btn', 'btn-primary' );
		$frameworks['types']['bootstrap4']['fields']['public']['field_types']['wc_add_to_cart']['class_field_full_button']  = array( 'btn-block' );
		$frameworks['types']['bootstrap41']['fields']['public']['field_types']['wc_add_to_cart']['class_field']             = array( 'btn', 'btn-primary' );
		$frameworks['types']['bootstrap41']['fields']['public']['field_types']['wc_add_to_cart']['class_field_full_button'] = array( 'btn-block' );

		// Foundation
		$frameworks['types']['foundation5']['fields']['public']['field_types']['wc_add_to_cart']['mask_field_label']         = '#label';
		$frameworks['types']['foundation5']['fields']['public']['field_types']['wc_add_to_cart']['class_field']              = array( 'button', 'primary' );
		$frameworks['types']['foundation5']['fields']['public']['field_types']['wc_add_to_cart']['class_field_full_button']  = array( 'expand' );
		$frameworks['types']['foundation6']['fields']['public']['field_types']['wc_add_to_cart']['mask_field_label']         = '#label';
		$frameworks['types']['foundation6']['fields']['public']['field_types']['wc_add_to_cart']['class_field']              = array( 'button', 'primary' );
		$frameworks['types']['foundation6']['fields']['public']['field_types']['wc_add_to_cart']['class_field_full_button']  = array( 'expanded' );
		$frameworks['types']['foundation64']['fields']['public']['field_types']['wc_add_to_cart']['mask_field_label']        = '#label';
		$frameworks['types']['foundation64']['fields']['public']['field_types']['wc_add_to_cart']['class_field']             = array( 'button', 'primary' );
		$frameworks['types']['foundation64']['fields']['public']['field_types']['wc_add_to_cart']['class_field_full_button'] = array( 'expanded' );

		// Clear

		// WS Form
		$frameworks['types']['ws-form']['fields']['public']['field_types']['wc_clear']['class_field']             = array( 'wsf-button' );
		$frameworks['types']['ws-form']['fields']['public']['field_types']['wc_clear']['class_field_full_button'] = array( 'wsf-button-full' );

		// Bootstrap
		$frameworks['types']['bootstrap3']['fields']['public']['field_types']['wc_clear']['class_field']              = array( 'btn', 'btn-default' );
		$frameworks['types']['bootstrap3']['fields']['public']['field_types']['wc_clear']['class_field_full_button']  = array( 'btn-block' );
		$frameworks['types']['bootstrap4']['fields']['public']['field_types']['wc_clear']['class_field']              = array( 'btn', 'btn-secondary' );
		$frameworks['types']['bootstrap4']['fields']['public']['field_types']['wc_clear']['class_field_full_button']  = array( 'btn-block' );
		$frameworks['types']['bootstrap41']['fields']['public']['field_types']['wc_clear']['class_field']             = array( 'btn', 'btn-secondary' );
		$frameworks['types']['bootstrap41']['fields']['public']['field_types']['wc_clear']['class_field_full_button'] = array( 'btn-block' );

		// Foundation
		$frameworks['types']['foundation5']['fields']['public']['field_types']['wc_clear']['mask_field_label']         = '#label';
		$frameworks['types']['foundation5']['fields']['public']['field_types']['wc_clear']['class_field']              = array( 'button', 'secondary' );
		$frameworks['types']['foundation5']['fields']['public']['field_types']['wc_clear']['class_field_full_button']  = array( 'expand' );
		$frameworks['types']['foundation6']['fields']['public']['field_types']['wc_clear']['mask_field_label']         = '#label';
		$frameworks['types']['foundation6']['fields']['public']['field_types']['wc_clear']['class_field']              = array( 'button', 'secondary' );
		$frameworks['types']['foundation6']['fields']['public']['field_types']['wc_clear']['class_field_full_button']  = array( 'expanded' );
		$frameworks['types']['foundation64']['fields']['public']['field_types']['wc_clear']['mask_field_label']        = '#label';
		$frameworks['types']['foundation64']['fields']['public']['field_types']['wc_clear']['class_field']             = array( 'button', 'secondary' );
		$frameworks['types']['foundation64']['fields']['public']['field_types']['wc_clear']['class_field_full_button'] = array( 'expanded' );

		return $frameworks;
	}

	// Plugin action link
	public function plugin_action_links( $links ) {

		// Settings
		array_unshift( $links, sprintf( '<a href="%s">%s</a>', WS_Form_Common::get_admin_url( 'ws-form-settings', false, 'tab=action_' . $this->id), __( 'Settings', 'ws-form-woocommerce' ) ) );

		return $links;
	}

	// Plug-in options for this action
	public function config_options( $options ) {

		$options['action_' . $this->id] = array(

			'label'     => $this->label,
			'fields'   => array(

				'action_' . $this->id . '_license_version' => array(

					'label'   => __( 'Add-on Version', 'ws-form-woocommerce' ),
					'type'    => 'static',
				),

				'action_' . $this->id . '_skin' => array(

					'label'   => __( 'Skin', 'ws-form-woocommerce' ),
					'type'    => 'select',
					'help'    => __( 'Select CSS skin to apply to form on product.', 'ws-form-woocommerce' ),
					'options' => self::skin_options(),
					'default' => self::skin_detect(),
				),

				'action_' . $this->id . '_style_woocommerce' => array(

					'label'   => __( 'Style WooCommerce Variations', 'ws-form-woocommerce' ),
					'type'    => 'checkbox',
					'help'    => __( 'When checked, WS Form styles will also be applied to product variation selectors.', 'ws-form-woocommerce' ),
					'default' => 'on',
				),

				'action_' . $this->id . '_preload_form_json' => array(

					'label'   => __( 'Preload Form Data', 'ws-form-woocommerce' ),
					'type'    => 'checkbox',
					'help'    => __( 'If checked, this improves form loading speed for modal extensions such as Quick View.', 'ws-form-woocommerce' ),
					'default'  => '',
				),
			),
		);

		return $options;
	}

	// Theme switch
	public function switch_theme() {

		// Detect skin and set it in options
		WS_Form_Common::option_set( 'action_' . $this->id . '_skin', self::skin_detect() );
	}

	// Detect skin
	public function skin_detect() {

		$skins    = self::skin_options();
		$template = get_template();

		foreach ( $skins as $id => $skin ) {

			$skin_template = isset( $skin['template'] ) ? $skin['template'] : false;
			if ( $skin_template === $template ) {
				return $id;
			}
		}

		return self::SKIN_DEFAULT;
	}

	// Skins
	public function skin_options() {

		$options = array(

			''             => array( 'text' => __( 'None', 'ws-form-woocommerce' ) ),
			'storefront'   => array( 'text' => __( 'Storefront', 'ws-form-woocommerce' ), 'dependencies' => array( 'storefront-woocommerce-style' ), 'template' => 'storefront' ),
		);

		/**
		 * Function for `wsf_woocommerce_skin_options` filter-hook.
		 * 
		 * @since  1.0.0
		 *
		 * @param  $options
		 *
		 * @return $options
		 */
		$options =  apply_filters( 'wsf_woocommerce_skin_options', $options );

		return $options;
	}

	// Settings - Static
	public function settings_static( $value, $field ) {

		switch ( $field ) {

			case 'action_' . $this->id . '_license_version':
				$value = self::WS_FORM_LICENSE_VERSION;
				break;
		}

		return $value;
	}

	// Icon
	public function get_icon_woo() {

		return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"><path d="M2 0h12c.6 0 1 .2 1.4.6.4.3.6.8.6 1.3v10.2c0 .5-.2 1-.6 1.4-.4.4-.9.6-1.4.6H9.5V16l-2-1.9H2c-.6 0-1-.2-1.4-.6-.4-.4-.6-.8-.6-1.4V1.9C0 1.4.2.9.6.5 1 .2 1.4 0 2 0zm5.6 4.7c0-.2 0-.3-.1-.3-.1-.1-.2-.1-.3-.1-.2 0-.4.2-.5.5-.1.3-.2.7-.3 1-.2.4-.2.7-.3 1.1v.6c-.1-.3-.2-.5-.2-.7-.1-.3-.1-.5-.2-.6 0-.1-.1-.3-.2-.6 0-.3-.1-.4-.3-.4-.1 0-.2.1-.4.4-.2.3-.4.5-.5.9-.2.3-.3.6-.4.9-.2.2-.2.4-.3.4v-.1-.1c-.1-.5-.2-1-.2-1.5-.1-.5-.1-1-.2-1.4 0-.1-.1-.2-.2-.2s-.2-.1-.2-.1c-.2 0-.3.1-.4.2 0 .1-.1.3-.1.4 0 .1.1.4.2 1s.2 1.1.3 1.6c0 .1.1.4.2 1s.3.9.5.9c.1 0 .3-.1.5-.4.1-.1.3-.4.4-.7.2-.3.3-.6.4-.9l.2-.4s.1.2.1.5l.3.9c.2.2.4.5.6.8.2.3.4.4.5.4.1 0 .2 0 .3-.1 0-.1.1-.2.1-.3V8.1c0-.3 0-.6.1-1s.2-.8.2-1.1c.1-.3.2-.6.2-.9l.2-.4zm2.6.7c-.2-.4-.6-.6-1-.6s-.8.1-1 .4c-.3.3-.5.8-.6 1.3V7.8c0 .1.1.3.2.5.2.4.4.5.7.6 0 .1.1.1.2.1h.2c.6 0 1-.3 1.3-.9.3-.6.4-1.1.4-1.5-.1-.4-.2-.8-.4-1.2zm-.6 1.3c0 .1-.1.4-.2.8s-.3.6-.5.6-.3-.1-.4-.4c-.1-.4-.1-.5-.1-.6 0-.1.1-.3.2-.7.1-.4.3-.6.6-.6.2 0 .3.1.4.4v.5zm3.9-1.3c-.2-.4-.6-.6-1-.6s-.8.1-1 .4c-.3.3-.5.8-.6 1.3 0 .3-.1.5-.1.7 0 .2 0 .4.1.6 0 .1.1.3.2.5s.3.4.6.5c.1 0 .1 0 .2.1h.2c.6 0 1-.3 1.3-.9.3-.6.4-1.1.4-1.5 0-.3-.1-.7-.3-1.1zm-.8 2.1c-.1.4-.3.6-.6.6-.2 0-.3-.1-.4-.4 0-.3-.1-.5-.1-.5 0-.1 0-.3.1-.7.1-.4.3-.6.6-.6.2 0 .3.1.4.4 0 .2.1.4.1.4.1.1 0 .4-.1.8z"/><path fill="none" d="M174.6 238.5h-8.5 8.5z"/></svg>';
	}

	// Activate
	public function update_1_1_0() {

		// Check if update has already run
		$already_updated = WS_Form_Common::option_get( 'action_' . $this->id . '_update_1_1_0' );
		if ( $already_updated ) {
			return true;
		}

		// User capability check
		if ( ! WS_Form_Common::can_user( 'read_form' ) ) {
			return false;
		}

		global $wpdb;

		// Version 1.1.0 update
		$products = $wpdb->get_results( sprintf( "SELECT meta_id, post_id, meta_value FROM %spostmeta WHERE meta_key = '%s';", $wpdb->prefix, esc_sql( self::META_KEY_PRODUCT ) ) );
		if (is_array($products)) {

			foreach ( $products as $product ) {

				try {

					// Read meta ID
					$meta_id = $product->meta_id;

					// Read product ID
					$product_id = $product->post_id;

					// Read meta data
					$meta_data_serialized = $product->meta_value;
					if (empty($meta_data_serialized) || !@unserialize($meta_data_serialized)) {
						continue;
					}
					$meta_data = unserialize($meta_data_serialized);

					// Read form ID
					if ( ! isset($meta_data['form_id']) ) {
						continue;
					}
					$form_id = absint($meta_data['form_id']);
					if ( 0 == $form_id ) {
						continue;
					}

					// Assign product to form
					self::form_assign_product( $form_id, $product_id, 'include' );

					// Publish form
					$ws_form_form     = new WS_Form_Form();
					$ws_form_form->id = $form_id;
					$ws_form_form->db_publish();

					// Remove form_id from meta data
					unset($meta_data['form_id']);

					// Set settings_source
					$meta_data['settings_source'] = '';

					// Update postmeta
					update_post_meta($product_id, self::META_KEY_PRODUCT, $meta_data);

				} catch ( Exception $e) {

					continue;
				}
			}
		}

		// Build product form cache
		self::build_product_form_cache();

		// Set option so this doesn't run again
		WS_Form_Common::option_set( 'action_' . $this->id . '_update_1_1_0', true );
	}

	public function update_1_1_49() {

		// Check if update has already run
		$already_updated = WS_Form_Common::option_get( 'action_' . $this->id . '_update_1_1_49' );
		if ( $already_updated ) {
			return true;
		}

		// User capability check
		if ( ! WS_Form_Common::can_user( 'read_form' ) ) {
			return false;
		}

		global $wpdb;

		// Meta key updates
		$meta_key_updates = array(

			self::META_KEY_ORDER_FORM_ID_LEGACY        => self::META_KEY_ORDER_FORM_ID,
			self::META_KEY_ORDER_SUBMIT_ID_LEGACY      => self::META_KEY_ORDER_SUBMIT_ID,
		);

		foreach ( $meta_key_updates as $meta_key_old => $meta_key_new ) {

			$wpdb->query( sprintf( "UPDATE %swoocommerce_order_itemmeta SET meta_key = '%s' WHERE meta_key = '%s';", $wpdb->prefix, esc_sql( $meta_key_new ), esc_sql( $meta_key_old ) ) );
		}

		// Set option so this doesn't run again
		WS_Form_Common::option_set( 'action_' . $this->id . '_update_1_1_49', true );
	}
}
