<?php

namespace BricksExtras;

use Bricks\Database;
use Bricks\Query;
use Bricks\Templates;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Helpers {

	/*
     Get most relevant setting from template settings or overriding page settings currently active
    */
	public static function getCurrentTemplateSetting( $key, $default = false) {

		//$headerSetting = Templates::get_templates_by_type( 'header' ) ? \Bricks\Helpers::get_template_setting( $key, Templates::get_templates_by_type( 'header' )[0] ) : false;
		$headerSetting = Database::$active_templates[ 'header' ] ? \Bricks\Helpers::get_template_setting( $key, Database::$active_templates[ 'header' ] ) : false;
		$contentSetting = Database::$active_templates[ 'content' ] ? \Bricks\Helpers::get_template_setting( $key, Database::$active_templates[ 'content' ] ) : false;
		$pageSetting = Database::$page_settings[$key] ?? false;

        if ( !!$pageSetting ) {
			return $pageSetting;
		} elseif ( !!$contentSetting ) {
			return $contentSetting;
		} elseif ( !!$headerSetting ) {
			return $headerSetting;
		} else {
			return $default;
		}
		
	}


	/* 
	 Get looping parent query Id by level - https://itchycode.com/bricks-builder-useful-functions-and-tips/
	*/
	public static function get_bricks_looping_parent_query_id_by_level( $level = 1 ) {
		
		global $bricks_loop_query;
	
		if ( empty( $bricks_loop_query ) || $level < 1 ) {
			return false;
		}
	
		$current_query_id = Query::is_any_looping();
		
		if ( !$current_query_id ) { 
			return false;
		}
		
		if ( !isset( $bricks_loop_query[ $current_query_id ] ) ) {
			return false;
		}
	
		$query_ids = array_reverse( array_keys( $bricks_loop_query ) );
	
		if ( !isset( $query_ids[ $level ] )) {
			return false;
		}
	
		if ( $bricks_loop_query[ $query_ids[ $level ] ]->is_looping ) {
			return $query_ids[ $level ];
		}
	
		return false;
	}


	/* 
	 True if we are viewing inside builder
	*/
	public static function maybePreview(): bool {

		$builder = isset($_GET["bricks"]) && strstr(sanitize_text_field($_GET["bricks"]), 'run');
		$referrer = isset($_SERVER['HTTP_REFERER']) && strstr(sanitize_text_field($_SERVER['HTTP_REFERER']), 'brickspreview');

		return ( $builder || $referrer );
	}


	/* 
	 Create CSS settings
	*/
	public static function doCSSRules($property, array $selectors, $general = false): array {

		$output = [];

		foreach( $selectors as $selector ) {

			if ( !$general ) {
				$selector = '.wsf-form ' . $selector;
			}

			$output[] = [
				'property' => $property,
				'selector' => $selector, 
			];
		}

		return $output;
	}

	/* 
	 Return true if elements CSS already added in <head>
	*/
	public static function elementCSSAdded($name) {

		global $bricksExtrasElementCSSAdded;
		return $bricksExtrasElementCSSAdded[$name] ?? true;

	}

	/* 
	 Maybe enqueue CSS
	*/
	public static function maybeAddElementCSS($name,$stylesheet,$handle): void {

		if ( Helpers::maybePreview() && Helpers::elementCSSAdded($name) ) {
			wp_enqueue_style( $handle, BRICKSEXTRAS_URL . 'components/assets/css/' . $stylesheet . '.css', [], '' );
		}

	}



	/* 
	 Get elements on page
	*/
	public static function getElementsOnPage() {

		$templateTypes = [
			'header',
			'content',
			'footer'
		];

		if ( ! method_exists( '\Bricks\Database', 'get_template_data' ) || 
			 ! method_exists( '\Bricks\Database', 'get_setting' ) || 
			 ! method_exists( '\Bricks\Assets', 'minify_css' ) || 
			 ! method_exists( '\Bricks\Query', 'get_query_object_type' )  ||
			 ! method_exists( '\Bricks\Helpers', 'get_template_setting' ) ) {
			return;
		}

		$elementsOnPageArray = [];

		foreach ($templateTypes as $templateType) {

			$templateData = Database::get_template_data( $templateType );

			if ( !!$templateData ) {

				foreach ($templateData as $templateElements) {
					$elementsOnPageArray[] = $templateElements;
				}

			}

		}

        $templateIDs = [];

		/* find elements inside templates, post content, shortcodes */

		foreach ($elementsOnPageArray as $elementOnPage) {

			if ( isset( $elementOnPage['settings']['template'] ) ) {
				$templateIDs[] = ! empty( $elementOnPage['settings']['template'] ) ? intval( $elementOnPage['settings']['template'] ) : 0;
			}

			if ( isset( $elementOnPage['settings']['offcanvas_template'] ) ) {
				$templateIDs[] = ! empty( $elementOnPage['settings']['offcanvas_template'] ) ? intval( $elementOnPage['settings']['offcanvas_template'] ) : 0;
			}

			if ( isset( $elementOnPage['settings']['modal_template'] ) ) {
				$templateIDs[] = ! empty( $elementOnPage['settings']['modal_template'] ) ? intval( $elementOnPage['settings']['modal_template'] ) : 0;
			}

			if ( isset( $elementOnPage['settings']['templateId'] ) ) {
				$templateIDs[] = ! empty( $elementOnPage['settings']['templateId'] ) ? intval( $elementOnPage['settings']['templateId'] ) : 0;
			}

			if ( isset( $elementOnPage['settings']['shortcode'] ) ) {
				$templateIDs[] = strstr( $elementOnPage['settings']['shortcode'], '[bricks_template') ? (int) filter_var($elementOnPage['settings']['shortcode'], FILTER_SANITIZE_NUMBER_INT) : 0;
			}

			if ( isset( $elementOnPage['settings']['dataSource'] ) ) {

				if ( 'bricks' === $elementOnPage['settings']['dataSource'] ) {

					$post_id = get_the_ID();

					if ( ! empty( $post_id ) ) {
						$templateIDs[] = $post_id;
					}

				}

			}

		}

		foreach ($templateIDs as $templateID) {
			
			$templateElements = get_post_meta( $templateID, BRICKS_DB_PAGE_CONTENT, true );

			if ( !empty( $templateElements ) ) {
				foreach ($templateElements as $templateElement) {
					$elementsOnPageArray[] = $templateElement;
				}
			}
			
		}

		return $elementsOnPageArray;

	}


	public static function getAllPostOptions($postType = 'post'): array {

			if ( !bricks_is_builder() ) {
				return [];
			}

			$args = array(
				'post_type' => $postType,
				'posts_per_page' => -1,
				'orderby' => 'title',
				'order' => 'ASC'
			);

			$posts = get_posts( $args );

			$postsOptions = [];

			if ( ! empty( $posts ) ) {
				foreach ( $posts as $post ) {
					$postsOptions[$post->ID] = $post->post_title;
				}
			}

			wp_reset_postdata();

			return $postsOptions;

	}

    // Function to get public CPTs
    public static function getPostTypes( $builtIn = false ): array {
        $postTypes = get_post_types(
            array(
                'public'   => true,
                '_builtin' => $builtIn,
            ),
            'objects'
        );
        unset( $postTypes['bricks_template'] );

        $postTypesArr = [];
        foreach ( $postTypes as $postType ) {
            $postTypesArr[$postType->name] = $postType->label;
        }

        return $postTypesArr;
        // https://d.pr/i/VGYKqF
    }


	 // Function to get product terms
	 public static function getProductTerms($taxonomy): array {

		if ( !bricks_is_builder() ) {
			return [];
		}

		$product_terms = get_terms( 
			[ 
				'taxonomy' => $taxonomy,
			 	'hide_empty' => true 
			] 
		);

		$product_terms_array = [];

		foreach ( $product_terms as $product_term ) {
			$product_terms_array[ $product_term->term_id ] = $product_term->name;
		}

        return $product_terms_array;
    }

	public static function getMathsCompareOptions(): array {

		return [
			'==' => esc_html__( '==', 'bricks' ),
			'!=' => esc_html__( '!=', 'bricks' ),
			'>=' => esc_html__( '>=', 'bricks' ),
			'<=' => esc_html__( '<=', 'bricks' ),
			'>' => esc_html__( '>', 'bricks' ),
			'<' => esc_html__( '<', 'bricks' ),
		];

	}


	public static function getMathsCompare( $amount, $value, $compare, $float = true ) {

		switch ( $compare ) {
			case '==':
				return $float ? $amount === floatval( $value ) : $amount === intval( $value );
				break;
			
			case '>':
				return $float ? $amount > floatval( $value ) : $amount > intval( $value );
				break;
			
			case '>=':
				return $float ? $amount >= floatval( $value ) : $amount >= intval( $value );
				break;
			
			case '<':
				return $float ? $amount < floatval( $value ) : $amount < intval( $value );
				break;
			
			case '<=':
				return $float ? $amount <= floatval( $value ) : $amount <= intval( $value );
				break;
			
			case '!=':
				return $float ? $amount !== floatval( $value ) : $amount !== intval( $value );
				break;
		}

	}

	public static function timeToSeconds(string $time): int {

      if ('' !== $time) {
		if ( str_contains($time, ':') ) {
			$arr = explode(':', $time);
			if (count($arr) === 3) {
				return $arr[0] * 3600 + $arr[1] * 60 + $arr[2];
			}
			return $arr[0] * 60 + $arr[1];
		} else {
			return intval($time);
		}
      } else {
        return 0;
      }
	  
    }

	public static function breadcrumb_item( $url, $content, $maybeLink = true, $maybeSchema = true ) {

		$linkSchema = $maybeSchema ? " itemprop='item'><span itemtype='". $url ."' itemprop='name'" : '';

		if ( $maybeLink ) {
			$output = "<a href='" . $url . "' " . $linkSchema . "><span>" . $content . "</span></a>";
		} else {
			$output = "<span " . $linkSchema . "><span>" . $content . "</span></span>";
		}

		return $output;

	}

	public static function getExtrasMiscOptions(): array {

		$options = [];

			$options = [
				'interactions' =>[
					'title' => 'Element Interactions',
					'docs_slug' => 'interactions',
					'description' => 'Triggers & Actions for BricksExtras elements',
				],
				'query_loop_extras' =>[
					'title' => 'Query Loop Extras',
					'file_name' => 'x-query-loop-extras',
					'description' => 'Adjacent Posts, Related Posts, WP Menu, Favorites, Gallery',
					'docs_slug' => 'query-loop-extras',
					'stylesheet' => false,
					'element' => false,
				],
				'x_ray' =>[
					'title' => 'X-Ray Mode',
					'docs_slug' => 'x-ray-mode',
				],
				
			];

		return $options;

	}


	public static function getExtrasConditions( $conditiontype ): array {

		$conditions = [];

		if ('general' === $conditiontype) {

			$conditions = [
				'archive_type' => [
					'title' => 'Archive type',
					'docs_slug' => 'conditions/#archive-type'
				],
				'at_least_1_search_result' => [
					'title' => 'At least one search result',
					'docs_slug' => 'conditions/#at-least-one-search-result'
				],
				'author_has_cpt_entry' => [
					'title' => 'Author has CPT entry',
					'docs_slug' => 'conditions/#author-has-cpt-entry'
				],
                'authored_by_logged_in_user' => [
					'title' => 'Authored by logged in user',
					'docs_slug' => 'conditions/#authored-by-loggedin-user'
				],
				'body_classes' => [
					'title' => 'Body classes',
					'docs_slug' => 'conditions/#body-classes'
				],
				'category_archive' => [
					'title' => 'Category archive',
					'docs_slug' => 'conditions/#category-archive'
				],
				'current_day' => [
					'title' => 'Current day (of month)',
					'docs_slug' => 'conditions/#current-day-of-month'
				],
				'current_month' => [
					'title' => 'Current month',
					'docs_slug' => 'conditions/#current-month'
				],
				'current_year' => [
					'title' => 'Current year',
					'docs_slug' => 'conditions/#current-year'
				],
				'current_taxonomy_term_has_child' => [
					'title' => 'Current taxonomy term has child',
					'docs_slug' => 'conditions/#current-taxonomy-term-has-child'
				],
				'current_taxonomy_term_has_parent' => [
					'title' => 'Current taxonomy term has parent',
					'docs_slug' => 'conditions/#current-taxonomy-term-has-parent'
				],
        		'cpt_has_at_least_1_entry' => [
					'title' => 'CPT has at least 1 published entry',
					'docs_slug' => 'conditions/#cpt-has-at-least-1-published-entry'
				],
				'date_field_value' => [
					'title' => 'Date field value',
					'docs_slug' => 'conditions/#date-field-value'
				],
				'datetime_field_value' => [
					'title' => 'Datetime field value',
					'docs_slug' => 'conditions/#datetime-field-value'
				],
				'has_child_category' => [
					'title' => 'Has child category',
					'docs_slug' => 'conditions/#has-child-category'
				],
				'has_custom_excerpt' => [
					'title' => 'Has custom excerpt',
					'docs_slug' => 'conditions/#has-custom-excerpt'
				],
				'has_post_content' => [
					'title' => 'Has post content',
					'docs_slug' => 'conditions/#has-post-content'
				],
				'is_child' => [
					'title' => 'Is child',
					'docs_slug' => 'conditions/#is-child'
				],
				'is_parent' => [
					'title' => 'Is parent',
					'docs_slug' => 'conditions/#is-parent'
				],
				'in_favorites_loop' => [
					'title' => 'In favorites loop',
					'docs_slug' => 'conditions/#in-favorites-loop'
				],
				'polylang_language' => [
					'title' => 'Language (Polylang)',
					'docs_slug' => 'conditions/#language-polylang'
				],
				'translatepress_language' => [
					'title' => 'Language (TranslatePress)',
					'docs_slug' => 'conditions/#language-translatepress'
				],
				'language_visitor' => [
					'title' => 'Language (visitor)',
					'docs_slug' => 'conditions/#language-visitor'
				],
				'wpml_language' => [
					'title' => 'Language (WPML)',
					'docs_slug' => 'conditions/#language-wpml'
				],
				'loop_item_number' => [
					'title' => 'Loop item number',
					'docs_slug' => 'conditions/#loop-item-number'
				],
				'page_type' => [
					'title' => 'Page type',
					'docs_slug' => 'conditions/#page-type'
				],
				'post_category' => [
					'title' => 'Post category',
					'docs_slug' => 'conditions/#post-category'
				],
				'post_ancestor' => [
					'title' => 'Post ancestor',
					'docs_slug' => 'conditions/#post-ancestor'
				],
				'post_comment_count' => [
					'title' => 'Post comment count',
					'docs_slug' => 'conditions/#post-comment-count'
				],
				'page_parent' => [
					'title' => 'Page parent',
					'docs_slug' => 'conditions/#page-parent'
				],
				'post_publish_date' => [
					'title' => 'Post publish date',
					'docs_slug' => 'conditions/#post-publish-date'
				],
				'post_tag' => [
					'title' => 'Post tag',
					'docs_slug' => 'conditions/#post-tag'
				],
				'post_type' => [
					'title' => 'Post type',
					'docs_slug' => 'conditions/#post-type'
				],
				'published_during_last' => [
					'title' => 'Published during the last',
					'docs_slug' => 'conditions/#published-during-the-last'
				],
				'tag_archive' => [
					'title' => 'Tag archive',
					'docs_slug' => 'conditions/#tag-archive'
				],
				

				
			];

		} 
		
		elseif ( 'member' === $conditiontype ) {

			$conditions = [
				'easy_digital_downloads' => [
					'title' => 'Easy Digital Downloads',
					'docs_slug' => 'easy-digital-downloads'
				],
				'memberpress' => [
					'title' => 'MemberPress',
					'docs_slug' => 'memberpress'
				],
				'pmp_membership_level' => [
					'title' => 'Paid Memberships Pro',
					'docs_slug' => 'paid-memberships-pro'
				],
				'restrict_content' => [
					'title' => 'Restrict Content',
					'docs_slug' => 'restrict-content'
				],
				'sure_members' => [
					'title' => 'SureMembers',
					'docs_slug' => 'suremembers'
				],
				'wishlist_member' => [
					'title' => 'WishList Member',
					'docs_slug' => 'wishlist-member'
				],
				'woocommerce_subscriptions' => [
					'title' => 'WooCommerce Subscriptions',
					'docs_slug' => 'woocommerce-subscriptions'
				],
				'wp_member' => [
					'title' => 'WP Members',
					'docs_slug' => 'wp-members'
				]
				
			];

		}
		
		elseif ( 'wc' === $conditiontype ) {

			$conditions = [
				'cart_count' => [
					'title' => 'Cart count (items)',
					'docs_slug' => 'woocommerce-conditions/#cart-count'
				],
				'cart_empty' => [
					'title' => 'Cart empty',
					'docs_slug' => 'woocommerce-conditions/#cart-empty'
				],
				'cart_total' => [
					'title' => 'Cart total (cost)',
					'docs_slug' => 'woocommerce-conditions/#cart-total'
				],
				'cart_total_minus_shipping' => [
					'title' => 'Cart total (Excluding shipping)',
					'docs_slug' => 'woocommerce-conditions/#cart-total'
				],
				'cart_weight' => [
					'title' => 'Cart weight',
					'docs_slug' => 'woocommerce-conditions/#cart-weight'
				],
				'current_product_in_cart' => [
					'title' => 'Current product in cart',
					'docs_slug' => 'woocommerce-conditions/#current-product-in-cart'
				],
				'product_backorders_allowed' => [
					'title' => 'Product allows backorders',
					'docs_slug' => 'woocommerce-conditions/#product-allows-backorders'
				],
				'product_has_category' => [
					'title' => 'Product has category',
					'docs_slug' => 'woocommerce-conditions/#product-has-category'
				],
				'product_has_tag' => [
					'title' => 'Product has tag',
					'docs_slug' => 'woocommerce-conditions/#product-has-tag'
				],
				'product_crosssells_count' => [
					'title' => 'Product cross-sells count',
					'docs_slug' => 'woocommerce-conditions/#product-cross-sells-count'
				],
				'product_in_cart' => [
					'title' => 'Product in cart',
					'docs_slug' => 'woocommerce-conditions/#product-in-cart'
				],
				'product_in_cart_has_coupon' => [
					'title' => 'Product in cart has a coupon applied',
					'docs_slug' => 'woocommerce-conditions/#product-in-cart-has-a-coupon-applied'
				],
				'product_in_stock' => [
					'title' => 'Product in stock',
					'docs_slug' => 'woocommerce-conditions/#product-in-stock'
				],
				'product_on_backorder' => [
					'title' => 'Product on backorder',
					'docs_slug' => 'woocommerce-conditions/#product-on-backorder'
				],
				'product_is_downloadable' => [
					'title' => 'Product is downloadable',
					'docs_slug' => 'woocommerce-conditions/#product-is-downloadable'
				],
				'product_is_virtual' => [
					'title' => 'Product is virtual',
					'docs_slug' => 'woocommerce-conditions/#product-is-virtual'
				],
				'product_rating' => [
					'title' => 'Product rating',
					'docs_slug' => 'woocommerce-conditions/#product-rating'
				],
				'product_type' => [
					'title' => 'Product type',
					'docs_slug' => 'woocommerce-conditions/#product-type'
				],
				'product_upsell_count' => [
					'title' => 'Product upsell count',
					'docs_slug' => 'woocommerce-conditions/#product-upsell-count'
				],
				'product_weight' => [
					'title' => 'Product weight',
					'docs_slug' => 'woocommerce-conditions/#product-weight'
				],
				'user_has_pending_order' => [
					'title' => 'User has pending order',
					'docs_slug' => 'woocommerce-conditions/#user-has-pending-order'
				],
				'user_purchased_current_product' => [
					'title' => 'User purchased current product',
					'docs_slug' => 'woocommerce-conditions/#user-purchased-current-product'
				],
				
				'user_purchased_product' => [
					'title' => 'User purchased product',
					'docs_slug' => 'woocommerce-conditions/#user-purchased-product'
				],
				
				
				
			];

		}

		return $conditions;

	}

	/* get video id from Youtube/vimeo */
	
	public static function get_video_id($url) {
		$parts = parse_url($url);
		if(isset($parts['query'])){
			parse_str($parts['query'], $qs);
			if(isset($qs['v'])){
				return $qs['v'];
			}else if(isset($qs['vi'])){
				return $qs['vi'];
			}
		}
		if(isset($parts['path'])){
			$path = explode('/', trim($parts['path'], '/'));
			return $path[count($path)-1];
		}
		return false;
	}


	/* 
	 Maybe minify JS (filter if needs to be disabled)
	*/
	public static function maybeMinifyScripts($script_name) {

		$minifyScripts = true;

		/* allow users to disable */
		$minifyScripts = apply_filters( 'bricksextras/minify', $minifyScripts, $script_name, 10, 2 );

		return $minifyScripts ? $script_name. '.min' : $script_name;
	}



	/*
	  Get favorite IDs
	*/
	public static function get_favorite_ids_array($list = false) {

		$key = 'x_favorite_ids';
		$cookie_prefix = 'x_favorite_ids__';
		$transient_key = sanitize_text_field( 'x_favorite_ids_transient_' . get_current_user_id() ); // unique key for each user
		$id_array = [];
	
		// Check if user is logged in
		if (is_user_logged_in()) {

			global $x_favorite_data_global;

			if ( $x_favorite_data_global && is_array( $x_favorite_data_global ) ) {
				$id_array = $x_favorite_data_global;
			} else {

				// Try to get data from transient
				$id_array_transient = get_transient( $transient_key );

				if ( $id_array_transient !== false ) {

					if ( \BricksExtras\Helpers::is_json($id_array_transient) && '' !== $id_array_transient ) {
						$decode_transient = json_decode($id_array_transient, true);
						if (json_last_error() === JSON_ERROR_NONE && is_array($decode_transient)) {
							$id_array = $decode_transient;
						}
					} else {
						$id_array = [];
					}
				
					if (json_last_error() !== JSON_ERROR_NONE) {

						// Transient not found, fetch from user meta
						$user_meta = get_user_meta(get_current_user_id(), $key, true);
						
						if ( $user_meta && \BricksExtras\Helpers::is_json($user_meta) ) {
							$id_array = json_decode($user_meta, true);
							if (json_last_error() === JSON_ERROR_NONE && is_array($id_array)) {
								$id_array = self::arrayValuesFromMetaAsIntegers($id_array);
							} else {
								$id_array = []; // fallback if JSON error occurs
							}
						} else {
							$favorite_data = self::getFavoriteDataFromCookie($cookie_prefix);
							$id_array = is_array($favorite_data) ? $favorite_data : [];
						}

						// Set a 12hr transient for caching
						set_transient($transient_key, wp_json_encode( $id_array ), 12 * HOUR_IN_SECONDS);

					}

				} 

			}
	
		} else {

			// Use cookie for guests
			$favorite_data = self::getFavoriteDataFromCookie($cookie_prefix);
			$id_array = is_array($favorite_data) ? $favorite_data : [];
		}
	
		// Retrieve only the post_type data if specified
		$output = (!$list || !empty($id_array[$list])) ? ($id_array[$list] ?? []) : [];
	
		return is_array($output) ? $output : [];
	}
	



	/* Get favorite data from all relevant cookies */
	public static function getFavoriteDataFromCookie($cookie_prefix): array {

		$favorite_data = [];

		// Iterate through all cookies to find those that start with the prefix
		foreach ($_COOKIE as $cookie_name => $cookie_value) {

			$cookie_name = sanitize_text_field($cookie_name);

			if (strpos($cookie_name, $cookie_prefix) === 0) {

				// Remove prefix from cookie name for use as key
				$key_name = substr($cookie_name, strlen($cookie_prefix));

				$cookieValue = wp_unslash($cookie_value);

				if ( \BricksExtras\Helpers::is_json($cookieValue) ) {

					$data = json_decode($cookieValue, true);

					// Check if the JSON is valid & sanitize IDs as integers
					if (json_last_error() === JSON_ERROR_NONE && is_array($data)) {
						$favorite_data[$key_name] = self::arrayValuesAsIntegers($data);
					}

				}
			}
		}

		return $favorite_data;
	}


	/* Ensure IDs in nested arrays are integers */
	public static function arrayValuesFromMetaAsIntegers($array): array {

		if (!is_array($array)) {
			return [];
		}

		foreach ($array as $key => $value) {

			$key = sanitize_text_field($key);

			if (is_array($value)) {
				// Use the arrayValuesAsIntegers function to process nested arrays
				$array[$key] = self::arrayValuesAsIntegers($value);
			} else {
				// If not an array, make it an empty array
				$array[$key] = [];
			}
		}

		return $array;
	}


	/* Ensure all items in a flat array are integers */
	public static function arrayValuesAsIntegers($array): array {

		// If it's not an array, return an empty array
		if (!is_array($array)) {
			return [];
		}
	
		// Filter the array to keep only integer values
		return array_filter(array_map('intval', $array), 'is_int');
	}

	// Helper function to validate if a string is JSON
	public static function is_json($string) {
		json_decode($string);
		return (json_last_error() === JSON_ERROR_NONE);
	}
	

}
