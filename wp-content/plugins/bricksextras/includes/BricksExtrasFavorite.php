<?php

namespace BricksExtras;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Favorite {

	public function init() {

		add_action( 'wp', array( $this, 'x_update_session_cookie' ) );

		/* add attribues on loop element when using favorites loop */
		add_filter( 'bricks/element/render_attributes', [ $this, 'favorite_loop_attributes' ], 10, 3 );

		/* when using favorites button */
		add_action('wp_ajax_x_update_favorite', array( $this, 'x_update_favorite') );

		/* validate favorite IDs for logged out users */
		add_action('wp_ajax_nopriv_x_validate_favorite_ids', array( $this, 'x_validate_favorite_ids') );
		
		/* ajax remove favorites data if cookies disallowed */
		add_action('wp_ajax_nopriv_x_remove_favorite', array( $this, 'x_remove_favorite') );
		
		/* delete session cookie on log out  */
		add_action( 'wp_logout', array( $this, 'x_delete_session_cookies' ) );

		/* set session cookie (and save to user meta if not existing) on log in */
		add_action( 'wp_login', array( $this, 'x_on_user_login' ), 10, 2 );
		
		/* set login cookie only if users logged in */
		add_action( 'template_redirect', array( $this, 'set_logged_in_cookie' ) );

		$publishedSyncing = apply_filters( 'bricksextras/favorites/published_syncing', true );

		if	( $publishedSyncing ) {

			/* when post deleted or trashed, remove from users lists */
			add_action('delete_post', array( $this, 'x_cleanup_user_meta_on_post_change'), 10, 2);
			add_action('wp_trash_post', array( $this, 'x_cleanup_user_meta_on_post_change'), 10, 2);
			
			/* when post no longer published, remove from user's lists */
			add_action('transition_post_status', function($new_status, $old_status, $post) {
				// Only proceed if the old status is 'publish'
				if ($old_status === 'publish') {
					self::x_cleanup_user_meta_on_post_change($post->ID, $post);
				}
			}, 10, 3);

		}

	}

	
	

	/**
	 * Adds favorite item attributes to the element based on query settings.
	 *
	 * @param array $attributes The current attributes for the element.
	 * @param string $key The key of the current element.
	 * @param object $element The element object being processed.
	 * @return array The modified attributes array with additional favorite item data.
	 */
	function favorite_loop_attributes( $attributes, $key, $element ): array {
		
		if( method_exists('\Bricks\Query', 'get_loop_object') && method_exists('\Bricks\Query', 'is_looping') && \Bricks\Query::is_looping() ) {

			$queryObj = \Bricks\Query::get_loop_object();
			
			if( is_object( $queryObj ) && isset( $element->settings['query']['objectType'] ) && 'queryLoopExtras' === $element->settings['query']['objectType'] ) {

				if ( 'favorite' === $element->settings['extrasQuery'] ) {

					$postType = isset( $element->settings['post_type'] ) ? esc_attr( $element->settings['post_type'] ) : 'post';
					$listSlug = isset( $element->settings['listSlug'] ) ? esc_attr( $element->settings['listSlug'] ) : false;

					$favorite_item = !!$listSlug ? $postType . '__' . $listSlug : $postType;

					$attributes['_root']['data-x-favorite-item'] = esc_attr( $favorite_item );
				}

			}

		}
		
		return $attributes;
	
	}


	/**
	 * Update the session cookie with the user's favorite post IDs.
	 *
	 * This function checks if the user is logged in, retrieves their favorite 
	 * post IDs from user meta or a transient, and sets cookies accordingly. 
	 * If no favorite data is found, it clears existing favorite cookies.
	 *
	 * @return void This function does not return a value.
	 */
	function x_update_session_cookie() {

		if (!is_user_logged_in()) {
			return; // Exit early if the user is not logged in
		}

		global $x_favorite_data_global; // Declare global variable for favorite data

		if ( is_admin() || \BricksExtras\Helpers::maybePreview() ) {
			return; // Exit if in the admin area
		}
	
		$meta_name = 'x_favorite_ids';
		$user_id = get_current_user_id();
		$transient_key = 'x_favorite_ids_transient_' . get_current_user_id(); // unique key for each user

		$favorite_data = [];
	
		// Check for a transient storing the favorite data
		$favorite_data_transient = get_transient($transient_key);
	
		// Only fetch from user meta and update the transient if no existing transient is found
		if (false === $favorite_data_transient ) {

			// Get user meta
			$existing_favorite_json = get_user_meta($user_id, $meta_name, true);
			
			// If user meta exists, decode it
			if ($existing_favorite_json) {
				$favorite_data = json_decode($existing_favorite_json, true);
	
				if (json_last_error() === JSON_ERROR_NONE && is_array($favorite_data)) {
					// Ensure all IDs are integers
					$favorite_data = \BricksExtras\Helpers::arrayValuesFromMetaAsIntegers($favorite_data);
	
					// Set a 12hr transient for caching
					set_transient($transient_key, wp_json_encode( $favorite_data ), 12 * HOUR_IN_SECONDS);
				} else {
					// Log JSON error if needed (optional)
					$favorite_data = []; // Clear on JSON error
				}
			} else {
				$favorite_data = []; // Default to an empty array if no meta found
			}
		} else {

			if ( \BricksExtras\Helpers::is_json( $favorite_data_transient) ) {
				
				$decoded_data = json_decode($favorite_data_transient, true);

				if (json_last_error() === JSON_ERROR_NONE && is_array($decoded_data)) {
					$favorite_data = $decoded_data;
				}

			} else {
				$favorite_data = [];
			}

		}

		$x_favorite_data_global = $favorite_data; // Set global variable with favorite data
	
		// If there’s valid favorite data, set or update the cookie
		if (!empty($favorite_data)) {
			foreach ($favorite_data as $post_type => $ids) {

				$cookie_name = 'x_favorite_ids__' . $post_type;
				setcookie($cookie_name, wp_json_encode($ids), [
					'path' => COOKIEPATH ? COOKIEPATH : '/',
					'domain' => COOKIE_DOMAIN,
					'secure' => is_ssl(),
					'samesite' => 'Strict',
				]);
				$_COOKIE[$cookie_name] = wp_json_encode($ids); // Update the global $_COOKIE array
			}
		} else {
			// Clear cookie if no favorite data is available
			foreach ($_COOKIE as $name => $value) {
				$name = sanitize_text_field($name);
				if (strpos($name, 'x_favorite_ids__') === 0) {
					setcookie($name, '', time() - 3600, '/');
					unset($_COOKIE[$name]);
				}
			}
		}
	}
	

	
	/**
	 * Removes all our cookies that start with 'x_favorite_ids__'.
	 *
	 * This function is triggered via AJAX when the xDisallowFavorites() runs.
	 *
	 * @return void Sends a JSON success response and terminates the script.
	 */
	function x_remove_favorite() {

		check_ajax_referer('favorite_nonce', 'sec');

		// Loop through all cookies and remove those that start with 'x_favorite_ids__'
		foreach ($_COOKIE as $key => $value) {

			$key = sanitize_text_field($key);

			if (strpos($key, 'x_favorite_') === 0) {
				setcookie($key, '', time() - 3600, '/'); // Expire the cookie
				unset($_COOKIE[$key]); // Remove from $_COOKIE superglobal
			}
		}

		wp_send_json_success(); // Send a success response

		wp_die(); // Terminate script
	}


	/**
	 * Validates and processes favorite IDs stored in cookies.
	 *
	 * This function is triggered via AJAX and checks the cookies for any favorite IDs
	 * that start with x_favorite_ids__. It decodes the JSON data from the cookies,
	 * validates the post types, and aggregates the unique favorite IDs. If the user
	 * is not logged in, it sets a cookie to store the favorite time and updates the
	 * favorite data with an expiration.
	 *
	 * @return void Sends a JSON success response and terminates the script.
	 */
	function x_validate_favorite_ids() {

		check_ajax_referer('favorite_nonce', 'sec');
	
		if (!is_user_logged_in()) {

			// Prefix for favorite ID cookies
			$cookie_prefix = 'x_favorite_ids__';
			$favorite_data = [];
		
			// Loop through all cookies
			foreach ($_COOKIE as $key => $value) {

				$key = sanitize_text_field($key);

				if (strpos($key, $cookie_prefix) === 0) { // Check if the cookie name starts with the prefix
					$cookie_value = wp_unslash($value);

					if (\BricksExtras\Helpers::is_json($cookie_value)) {

						$decoded_data = json_decode($cookie_value, true);

						// Check if the JSON is valid
						if (json_last_error() === JSON_ERROR_NONE && is_array($decoded_data)) {

							// Extract the base post type from the cookie key
							$listParts = explode('__', $key);
							$base_post_type = $listParts[1]; // Use the second part as the base post type

							// Check if this is a valid post type
							if (post_type_exists($base_post_type)) {
								// Initialize the array for the full key if it doesn't exist
								if (!isset($favorite_data[$key])) {
									$favorite_data[$key] = []; // Use the full key here
								}
								// Merge the IDs into the respective key
								$favorite_data[$key] = array_unique(array_merge($favorite_data[$key], $decoded_data));
							} else {
								//error_log("Invalid post type: $base_post_type");
							}
						} else {
							//error_log("JSON Error: " . json_last_error_msg());
						}
					}
				}
			}

			$expire_days = 7; // Default expiration
			$expire_days = apply_filters('bricksextras/favorite/expiry', $expire_days);
			$expires = time() + (floatval($expire_days) * 24 * 60 * 60);
	
			// Pass the entire array to updateCookieForCount
			$favorite_data = self::updateCookieForCount($favorite_data, $expires, false);
	
			// Check if the 'x_favorite_allow' cookie is set and has the value 'true'
			if ( isset( $_COOKIE['x_favorite_allow'] ) && $_COOKIE['x_favorite_allow'] === 'true' ) {
				/* reset the time */
				setcookie('x_favorite_time', time(), [
					'expires' => $expires,
					'path' => COOKIEPATH ? COOKIEPATH : '/',
					'domain' => COOKIE_DOMAIN,
					'secure' => is_ssl(),
					'samesite' => 'Strict',
				]);
			}
		}
	
		wp_send_json_success();
		wp_die();
	}
	
	

	/**
	 * Updates the user's favorite items based on AJAX requests (logged in users only)
	 *
	 * This function processes an AJAX request to add or remove a favorite item.
	 * It checks for user authentication, sanitizes input data, and updates the
	 * user meta and cookies accordingly. It also handles maximum favorite limits
	 * and clears favorite data if necessary.
	 *
	 * @return void Sends a JSON success or error response and terminates the script.
	 */
	function x_update_favorite() {
		
		check_ajax_referer('favorite_nonce', 'sec');
	
		if (is_user_logged_in() && isset($_POST['post_id'], $_POST['post_type'], $_POST['cookie'], $_POST['type'], $_POST['listMaximum'])) {

			// Sanitize all POST variables
			$post_id = intval($_POST['post_id']);
			$post_type = sanitize_text_field($_POST['post_type']);
			$type = sanitize_text_field($_POST['type']);
			$cookie_name = sanitize_text_field($_POST['cookie']);
			$listMaximum = intval($_POST['listMaximum']);

			$meta_name = 'x_favorite_ids';

			$maximumReached = false;
			$setMaximum = ($listMaximum >= 1); // Determine if a maximum limit is set
	
			$user_id = get_current_user_id();
	
			// Retrieve existing favorite data from user meta
			$existing_favorite_json = get_user_meta($user_id, $meta_name, true);
			$favorite_data = json_decode($existing_favorite_json, true);
	
			if (json_last_error() !== JSON_ERROR_NONE || !is_array($favorite_data)) {
				$favorite_data = array(); // Initialize empty array if JSON is invalid
			} else {
				$favorite_data = \BricksExtras\Helpers::arrayValuesFromMetaAsIntegers($favorite_data);
			}
	
			// Initialize post type array if not set
			if (!isset($favorite_data[$post_type])) {
				$favorite_data[$post_type] = array();
			}
	
			if ($type === 'clear') {
				// Clear the data
				if (isset($favorite_data[$post_type])) {
					$favorite_data[$post_type] = array(); // Reset the array if it exists
				}
			} else {

				// Check if the post ID already exists in the array
				if (in_array($post_id, $favorite_data[$post_type], true) || 'remove' === $type) {
					// Remove the post ID from favorites
					$favorite_data[$post_type] = array_values(array_diff($favorite_data[$post_type], [$post_id]));
				} else {
					// Check if we can add the post ID
					if (!$setMaximum || (count($favorite_data[$post_type]) < $listMaximum)) {
						$favorite_data[$post_type][] = $post_id; // Add post ID to favorites
					} else {
						$maximumReached = true; // Mark that the maximum has been reached
					}
				}
			}
	
			// Save the data after change
			$new_meta_value = wp_json_encode($favorite_data);

			// Save the data after change
			$new_cookie_value = wp_json_encode($favorite_data[$post_type]);

			$transient_key = 'x_favorite_ids_transient_' . get_current_user_id(); // unique key for each user

			// Update user meta
			update_user_meta($user_id, $meta_name, $new_meta_value);
			// Set a 12hr transient for caching
			set_transient($transient_key, wp_json_encode( $favorite_data ), 12 * HOUR_IN_SECONDS);
	
			// Update the session cookie
			setcookie($cookie_name, $new_cookie_value, [
				'path' => COOKIEPATH ? COOKIEPATH : '/',
				'domain' => COOKIE_DOMAIN,
				'secure' => is_ssl(),
				'samesite' => 'Strict',
			]);

			$_COOKIE[$cookie_name] = $new_cookie_value;
	
			// Send the success response
			wp_send_json_success(array(
				'data' => $favorite_data,
				'message' => $maximumReached,
			));
		} else {
			wp_send_json_error(array('message' => 'Invalid input data'));
		}
	
		wp_die();
	}
	

	/**
	 * Deletes session cookies related to user's favorite items upon logout.
	 *
	 * This function clears all cookies that start with the prefix 'x_favorite_ids__'.
	 * It sets each cookie's expiration time to the past to effectively delete them.
	 *
	 * @return void
	 */
	function x_delete_session_cookies() {

		// Clear all cookies starting with 'x_favorite_ids__'
		foreach ($_COOKIE as $name => $value) {

			$name = sanitize_text_field($name);

			if (strpos($name, 'x_favorite_') === 0) {
				setcookie($name, '', time() - 3600, COOKIEPATH ? COOKIEPATH : '/', COOKIE_DOMAIN);
				unset($_COOKIE[$name]); // Optional: remove from global $_COOKIE array
			}
		}
	}


	/**
	 * Handles user login and syncs favorite items from cookies to user meta.
	 *
	 * @param string $user_login The username of the user logging in.
	 * @param WP_User $user The WP_User object for the logging-in user.
	 * @return void
	 */
	function x_on_user_login($user_login, $user) {

		$user_id = $user->ID;
		$meta_name = 'x_favorite_ids';
		$favorite_data = [];
		
		// Retrieve existing favorite data from user meta
		$existing_favorite_json = get_user_meta($user_id, $meta_name, true);

		delete_transient('x_favorite_ids_transient_' . $user_id);
		wp_cache_delete('x_favorite_ids_transient_' . $user_id, 'transient');
		
		// Check if user meta already exists
		if (empty($existing_favorite_json)) {
			// Iterating through cookies to preserve post_type keys in $favorite_data
			foreach ($_COOKIE as $cookie_name => $cookie_value) {

				$cookie_name = sanitize_text_field( $cookie_name );

				if (strpos($cookie_name, 'x_favorite_ids__') === 0) {

					if (\BricksExtras\Helpers::is_json($cookie_value)) {

						// Decode cookie data
						$cookie_data = json_decode($cookie_value, true);

						// Check if the JSON is valid
						if (json_last_error() === JSON_ERROR_NONE && is_array($cookie_data)) {
							// Extract post type from the cookie name (e.g., x_favorite_ids__post becomes post)
							$post_type_key = str_replace('x_favorite_ids__', '', $cookie_name);
		
							// Ensure post type key exists in favorite_data, and merge IDs if already present
							if (!isset($favorite_data[$post_type_key])) {
								$favorite_data[$post_type_key] = $cookie_data;
							} else {
								$favorite_data[$post_type_key] = array_merge($favorite_data[$post_type_key], $cookie_data);
							}
						}
					}
				}
			}
	
			// Validate and filter the collected favorite data
			if (!empty($favorite_data)) {

				$publishedSyncing = apply_filters('bricksextras/favorites/published_syncing', true);

				if ($publishedSyncing) {
					$post_ids_to_check = [];
					$posts_to_filter = [];

					$registered_post_types = get_post_types(['public' => true], 'names');

					if (($key = array_search('bricks_template', $registered_post_types)) !== false) {
						unset($registered_post_types[$key]);
					}

					// Collect post IDs across post types
					foreach ($favorite_data as $post_type_key => $post_ids) {
						if (in_array($post_type_key, $registered_post_types, true)) {
							$posts_to_filter[$post_type_key] = $post_ids;
							$post_ids_to_check = array_merge($post_ids_to_check, $post_ids);
						}
					}
		
					// Get only published posts
					$published_posts = get_posts([
						'post_type' => ['any'],
						'post__in' => $post_ids_to_check,
						'post_status' => ['publish', 'inherit'],
						'numberposts' => -1,
						'fields' => 'ids'
					]);
		
					// Make published post IDs a simple array
					$published_post_ids = array_values($published_posts);
		
					// Filter favorite data based on published posts
					foreach ($posts_to_filter as $post_type_key => $post_ids) {
						// Use in_array to check if the post ID exists in published_post_ids
						$filtered_ids = array_filter($post_ids, function ($post_id) use ($published_post_ids) {
							return in_array($post_id, $published_post_ids, true); // Check existence correctly
						});
						
						// Update favorite_data with filtered IDs
						if (!empty($filtered_ids)) {
							$favorite_data[$post_type_key] = array_values($filtered_ids);
						} else {
							unset($favorite_data[$post_type_key]); // Remove empty keys
						}
					}
				}
	
				// Update user meta if there's valid favorite data
				if (!empty($favorite_data)) {
					$new_meta_value = wp_json_encode($favorite_data);
					update_user_meta($user_id, $meta_name, $new_meta_value);
				}
			}
		}
	
		// Handle existing user meta
		if (!empty($existing_favorite_json)) {
			$favorite_data = json_decode($existing_favorite_json, true);
	
			if (json_last_error() !== JSON_ERROR_NONE) {
				$favorite_data = false;
			}
		}
	
		// Set session cookie based on favorite data
		if ($favorite_data && is_array($favorite_data)) {
	
			// Clear existing cookies
			foreach ($_COOKIE as $cookie_name => $value) {

				$cookie_name = sanitize_text_field( $cookie_name );

				if (strpos($cookie_name, 'x_favorite_ids__') === 0) {
					setcookie($cookie_name, '', time() - 3600, COOKIEPATH, COOKIE_DOMAIN);
					unset($_COOKIE[$cookie_name]); // Optional: remove from global $_COOKIE array
				}
			}
	
			// Set the new cookies
			foreach ($favorite_data as $post_type => $post_ids) {
				$cookie_name = "x_favorite_ids__$post_type";
				$new_cookie_value = wp_json_encode($post_ids);
				setcookie($cookie_name, $new_cookie_value, [
					'path' => COOKIEPATH ? COOKIEPATH : '/',
					'domain' => COOKIE_DOMAIN,
					'secure' => is_ssl(),
					'samesite' => 'Strict'
				]);
			}
	
			// make the new cookie available in the current request
			foreach ($favorite_data as $post_type => $post_ids) {
				$_COOKIE["x_favorite_ids__$post_type"] = wp_json_encode($post_ids);
			}
		}
	}
	
	
	

	/**
 	* Set a cookie to indicate whether a user is logged in or not.
	*
	* @param void
	* @return void
	*/
	function set_logged_in_cookie() {

		if ( is_user_logged_in() ) {
			setcookie( 'x_logged_in_user', '1', 0, COOKIEPATH ? COOKIEPATH : '/', COOKIE_DOMAIN, is_ssl(), false );		
		} else {
			
			if ( isset( $_COOKIE['x_logged_in_user'] ) ) {
				setcookie( 'x_logged_in_user', '', time() - 3600, COOKIEPATH ? COOKIEPATH : '/', COOKIE_DOMAIN, is_ssl(), false );
			}
		}

	}


	/**
	 * Cleans up user meta data when a post is deleted or trashed.
	 *
	 * This function checks all users who have favorite posts recorded in user meta,
	 * and removes the deleted or trashed post ID from their favorites.
	 *
	 * @param int $post_id The ID of the post being changed (deleted or trashed).
	 * @param WP_Post $post The post object of the post being changed.
	 *
	 * @return void This function does not return a value. It updates user meta directly.
	 */
	static function x_cleanup_user_meta_on_post_change($post_id, $post) {

		global $wpdb;
		$meta_name = 'x_favorite_ids';

		$post_type = get_post_type($post_id); // Get post type of the deleted post

		$excluded_post_types = array('revision', 'nav_menu_item', 'custom_css', 'oembed_cache', 'user_request', 'wp_block', 'bricks_template');

		$excluded_post_types = apply_filters('bricksextras/favorites/excluded_post_types', $excluded_post_types);

		 // If the post type is in the excluded list, return early
		 if (in_array($post_type, $excluded_post_types)) {
			return;
		}
	
		// Only get users who have the `x_favorite_ids` meta key set
		$user_ids = $wpdb->get_col($wpdb->prepare(
			"SELECT user_id FROM {$wpdb->usermeta} WHERE meta_key = %s",
			$meta_name
		));
	
		if (empty($user_ids)) {
			return;
		}
	
		foreach ($user_ids as $user_id) {
			// Get user meta
			$existing_favorite_json = get_user_meta($user_id, $meta_name, true);
	
			if (!$existing_favorite_json || empty($existing_favorite_json)) {
				continue;
			}
	
			// Decode the JSON data
			$favorite_data = json_decode($existing_favorite_json, true);
	
			if (json_last_error() !== JSON_ERROR_NONE || !is_array($favorite_data)) {
				continue;
			}
	
			$data_changed = false;
	
			// Loop through each post type or custom key
			foreach ($favorite_data as $post_type_key => &$post_ids) {
				
				// Check if the key matches the post type, directly or with a `__` suffix
				if ($post_type_key === $post_type || strpos($post_type_key, "{$post_type}__") === 0) {
					
					// Check if the post ID exists in the array and remove it
					if (($key = array_search($post_id, $post_ids, true)) !== false) {
						unset($post_ids[$key]);
						$post_ids = array_values($post_ids); // Re-index array
						$data_changed = true;
					}
	
					// Remove empty keys
					if (empty($post_ids)) {
						unset($favorite_data[$post_type_key]);
					}
				}
			}
			unset($post_ids); // Clear reference
	
			// Update meta only if changes were made
			if ($data_changed) {

				$new_json_value = wp_json_encode($favorite_data);
	
				if ($new_json_value !== $existing_favorite_json) {
					update_user_meta($user_id, $meta_name, $new_json_value);
					delete_transient('x_favorite_ids_transient_' . $user_id);
				}
			}
		}
	}
	

	
	/**
	 * Update cookies for the count of favorite posts based on their publish status.
	 *
	 * @param array $favorite_data An associative array of favorite post IDs categorized by post type.
	 * @param int $expires The expiration time for the cookies.
	 * @param string|false $listName (Optional) The specific list name to filter favorites by.
	 * @return array The filtered favorite data, containing only published post IDs.
	 */
	function updateCookieForCount($favorite_data, $expires, $listName = false): array {

		$filtered_favorite_data = [];
	
		// Check if a specific list is known
		if ($listName) {
			// Extract the base post type from the list name
			$base_post_type = str_replace('x_favorite_ids__', '', $listName);
	
			// Check if the specific post type exists in the favorite data
			if (isset($favorite_data[$listName])) {
				$post_ids_to_check = $favorite_data[$listName];
	
				// Get only published posts for this specific post type
				$published_posts = get_posts([
					'post_type' => $base_post_type,
					'post__in' => $post_ids_to_check,
					'post_status' => ['publish', 'inherit'],
					'numberposts' => -1,
					'fields' => 'ids'
				]);
	
				// Update the filtered favorite data with any published post IDs
				if (!empty($published_posts)) {
					$filtered_favorite_data[$listName] = array_values(array_intersect($post_ids_to_check, $published_posts));
				} else {
					$filtered_favorite_data[$listName] = [];
				}
			}
		} else { // Check all lists if no specific list is provided


			// Loop through each key in the favorite data
			foreach ($favorite_data as $post_type_key => $post_ids) {
				// Check if this key corresponds to a post type
				$listParts = explode('__', $post_type_key);
				$base_post_type = $listParts[1]; // Get base post type
	
				// Only check against published posts if the post type exists
				if (post_type_exists($base_post_type)) {
					// Get only published posts for this post type
					$published_posts = get_posts([
						'post_type' => $base_post_type,
						'post__in' => $post_ids,
						'post_status' => ['publish', 'inherit'],
						'numberposts' => -1,
						'fields' => 'ids'
					]);
	
					// Update the filtered favorite data with only published post IDs
					if (!empty($published_posts)) {
						$filtered_favorite_data[$post_type_key] = array_values(array_intersect($post_ids, $published_posts));
					} else {
						$filtered_favorite_data[$post_type_key] = [];
					}
				}
			}
		}
	
		// Set cookies based on filtered favorite data
		foreach ($filtered_favorite_data as $post_type => $ids) {

			// If there are valid IDs for this post type
			if (!empty($ids)) {

				$new_cookie_value = wp_json_encode(array_values(array_unique($ids)));
	
				// Clear existing cookie
				setcookie("$post_type", '', time() - 3600, COOKIEPATH, COOKIE_DOMAIN);
				// Set the new cookie for this post type
				setcookie("$post_type", $new_cookie_value, [
					'expires' => $expires,
					'path' => COOKIEPATH ? COOKIEPATH : '/',
					'domain' => COOKIE_DOMAIN,
					'secure' => is_ssl(),
					'samesite' => 'Strict',
				]);
	
				// Make cookie available in the current request
				$_COOKIE["$post_type"] = $new_cookie_value;
			} else {
				// Clear the cookie if there are no valid IDs
				setcookie("$post_type", '', time() - 3600, COOKIEPATH, COOKIE_DOMAIN);
			}
		}
	
		// Check if the 'x_favorite_allow' cookie is set and has the value 'true'
		if ( isset( $_COOKIE['x_favorite_allow'] ) && $_COOKIE['x_favorite_allow'] === 'true' ) {

			/* reset the time */
			setcookie('x_favorite_time', time(), [
				'expires' => $expires,
				'path' => COOKIEPATH ? COOKIEPATH : '/',
				'domain' => COOKIE_DOMAIN,
				'secure' => is_ssl(),
				'samesite' => 'Strict',
			]);
		}
	
		return $filtered_favorite_data; // Return the updated data for further use if needed
	}
	
	


}
