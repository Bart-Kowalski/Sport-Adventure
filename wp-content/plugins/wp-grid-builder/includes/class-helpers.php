<?php
/**
 * Helpers
 *
 * @package   WP Grid Builder
 * @author    Loïc Blascos
 * @copyright 2019-2024 Loïc Blascos
 */

namespace WP_Grid_Builder\Includes;

use WP_Grid_Builder\Includes\Database;
use WP_Grid_Builder\Includes\Settings\Settings;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Helpers methods
 *
 * @class WP_Grid_Builder\Includes\Helpers
 * @since 1.0.0
 */
class Helpers {

	/**
	 * Test if given object is a JSON string or not.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param  mixed $object Given object.
	 * @return bool
	 */
	public static function is_json( $object ) {

		return is_string( $object )
			&& is_array( json_decode( $object, true ) )
			&& json_last_error() === JSON_ERROR_NONE;
	}

	/**
	 * Maybe JSON decode string.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param  mixed $string The json string being decoded.
	 * @param  bool  $assoc  When TRUE, returned objects will be converted into associative arrays.
	 * @return string
	 */
	public static function maybe_json_decode( $string, $assoc = false ) {

		if ( is_array( $string ) ) {

			return array_map(
				function( $val ) {
					return self::maybe_json_decode( $val );
				},
				$string
			);
		}

		return self::is_json( $string ) ? json_decode( $string, $assoc ) : $string;

	}

	/**
	 * Maybe JSON encode string.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param  mixed $obj Content being JSON encoded.
	 * @return mixed
	 */
	public static function maybe_json_encode( $obj ) {

		if ( ! is_array( $obj ) && ! is_object( $obj ) ) {
			return $obj;
		}

		return array_map(
			function( $item ) {

				if ( is_array( $item ) || is_object( $item ) ) {
					return wp_json_encode( $item );
				}

				return $item;

			},
			$obj
		);
	}

	/**
	 * Find partial strings from an array in another array
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param  mixed $haystacks The string to search in.
	 * @param  mixed $needles   The string to search for.
	 * @return boolean
	 */
	public static function strpos_array( $haystacks, $needles ) {

		if ( is_scalar( $haystacks ) && is_scalar( $needles ) ) {
			return strpos( (string) $haystacks, (string) $needles ) !== false;
		}

		$found = false;

		foreach ( (array) $needles as $needle ) {
			foreach ( (array) $haystacks as $haystack ) {

				if ( strpos( $haystack, $needle ) !== false ) {

					$found = true;
					break 2;

				}
			}
		}

		return $found;

	}

	/**
	 * Find a partial string in an array key/value
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param  string $search  The string to search in.
	 * @param  string $replace The replacement value.
	 * @param  array  $subject The array being searched and replaced on.
	 * @return array
	 */
	public static function array_replace( $search, $replace, $subject ) {

		if ( is_string( $subject ) ) {
			return str_replace( $search, $replace, $subject );
		}

		if ( ! is_array( $subject ) ) {
			return $subject;
		}

		$new_subject = [];

		foreach ( $subject as $key => $value ) {
			$new_subject[ str_replace( $search, $replace, $key ) ] = self::array_replace( $search, $replace, $value );
		}

		return $new_subject;

	}

	/**
	 * Sanitize multiple HTML classes in one pass.
	 *
	 * @since 1.0.4 Preg_split string by whitespaces.
	 * @since 1.0.0
	 * @access public
	 *
	 * @param  mixed $classes Classes to be sanitized.
	 * @return string
	 */
	public static function sanitize_html_classes( $classes = '' ) {

		if ( empty( $classes ) ) {
			return '';
		}

		if ( ! is_array( $classes ) ) {
			$classes = preg_split( '/\s+/', $classes );
		}

		$classes = array_map( 'sanitize_html_class', (array) $classes );
		$classes = implode( ' ', $classes );
		$classes = preg_replace( '!\s+!', ' ', $classes );

		return trim( $classes );

	}

	/**
	 * Shorten long numbers
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param integer $number Number to shorten.
	 * @return integer
	 */
	public static function shorten_number_format( $number ) {

		$number = (int) $number;

		if ( $number < 1000 ) {
			return $number;
		} elseif ( $number < 1000000 ) {
			return (int) ( $number / 1000 ) . 'k';
		} elseif ( $number < 1000000000 ) {
			return (int) ( $number / 1000000 ) . 'M';
		}

		return (int) ( $number / 1000000000 ) . 'B';

	}

	/**
	 * Format control options.
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param  array $values Value to format.
	 * @return array
	 */
	public static function format_options( $values = [] ) {

		return array_map(
			function( $value, $label ) {

				return [
					'value' => $value,
					'label' => $label,
				];
			},
			array_keys( $values ),
			$values
		);
	}

	/**
	 * Get roles
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array
	 */
	public static function get_user_roles() {

		static $user_roles;

		if ( isset( $user_roles ) ) {
			return $user_roles;
		}

		$user_roles = wp_roles();
		$user_roles = $user_roles->get_names();
		$user_roles = array_map( 'translate_user_role', $user_roles );

		return $user_roles;

	}

	/**
	 * Query user ids
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array   $query_vars Holds query arguments.
	 * @param integer $number Number of users to query.
	 * @return array
	 */
	public static function get_user_ids( $query_vars, $number ) {

		return (array) (
			new \WP_User_Query(
				array_merge(
					$query_vars,
					[
						'number'      => $number,
						'fields'      => 'ID',
						'count_total' => false,
					]
				)
			)
		)->results;
	}

	/**
	 * Get user capability
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string
	 */
	public static function get_user_capability() {

		return apply_filters( 'wp_grid_builder/user_capability', 'manage_options' );

	}

	/**
	 * Check user capability
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return boolean
	 */
	public static function current_user_can() {

		return current_user_can( self::get_user_capability() );

	}

	/**
	 * Get post types
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array
	 */
	public static function get_post_types() {

		global $wp_post_types;
		static $post_types;

		if ( isset( $post_types ) ) {
			return $post_types;
		}

		$post_types = [];

		if ( empty( $wp_post_types ) ) {
			return $post_types;
		}

		foreach ( $wp_post_types as $post_type ) {

			if ( $post_type->public ) {
				$post_types[ $post_type->name ] = ucfirst( $post_type->label );
			}
		}

		return $post_types;

	}

	/**
	 * Get post ids
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array   $query_vars Holds query arguments.
	 * @param integer $number Number of posts to query.
	 * @return array
	 */
	public static function get_post_ids( $query_vars, $number ) {

		$post_ids = (array) (
			new \WP_Query(
				array_merge(
					$query_vars,
					[
						'paged'                  => 1,
						'posts_per_page'         => $number,
						'update_post_meta_cache' => false,
						'update_post_term_cache' => false,
						'cache_results'          => false,
						'no_found_rows'          => true,
						'fields'                 => 'ids',
					]
				)
			)
		)->posts;

		wp_reset_postdata();

		return $post_ids;

	}

	/**
	 * Get post status
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array
	 */
	public static function get_post_status() {

		global $wp_post_statuses;
		static $post_status;

		if ( isset( $post_status ) ) {
			return $post_status;
		}

		$post_status = [
			'any' => __( 'Any', 'wp-grid-builder' ),
		];

		if ( ! empty( $wp_post_statuses ) ) {

			foreach ( $wp_post_statuses as $status ) {
				$post_status[ $status->name ] = ucfirst( $status->label );
			}
		}

		return $post_status;

	}

	/**
	 * Get Taxonomies
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array
	 */
	public static function get_taxonomies() {

		global $wp_taxonomies;
		static $taxonomies;

		if ( isset( $taxonomies ) ) {
			return $taxonomies;
		}

		$taxonomies = [];

		foreach ( (array) $wp_taxonomies as $taxonomy => $args ) {

			$matched = true;

			if ( ! empty( $post_types ) && is_array( $post_types ) ) {
				$matched = array_intersect( $args->object_type, $post_types );
			}

			if (
				empty( $matched ) ||
				(
					// Exception for WooCommerce taxonomy.
					'product_visibility' !== $taxonomy &&
					! $args->publicly_queryable &&
					! $args->show_tagcloud &&
					! $args->show_ui &&
					! $args->public
				)
			) {
				continue;
			}

			// Fallback to taxonomy name if empty label.
			$taxonomies[ $taxonomy ] = ucfirst( $args->label ?: $taxonomy );

		}

		return $taxonomies;

	}

	/**
	 * Get Taxonomies list
	 *
	 * @since 1.4.2
	 * @access public
	 *
	 * @return array
	 */
	public static function get_taxonomies_list() {

		static $taxonomies;

		if ( isset( $taxonomies ) ) {
			return $taxonomies;
		}

		$taxonomies = self::get_taxonomies();
		$duplicates = array_diff_key( $taxonomies, array_unique( $taxonomies ) );

		foreach ( $duplicates as $taxonomy => $label ) {
			$taxonomies[ $taxonomy ] .= ' (' . $taxonomy . ')';
		}

		return $taxonomies;

	}

	/**
	 * Query term ids
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array   $query_vars Holds query arguments.
	 * @param integer $number Number of terms to query.
	 * @return array
	 */
	public static function get_term_ids( $query_vars, $number ) {

		return (array) (
			new \WP_Term_Query(
				array_merge(
					$query_vars,
					[
						'number' => $number,
						'fields' => 'ids',
					]
				)
			)
		)->terms;
	}

	/**
	 * Get term ancestor IDs
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param string $taxonomy Taxonomy name.
	 * @return array
	 */
	public static function get_ancestors( $taxonomy = 'category' ) {

		static $depths;

		if ( isset( $depths[ $taxonomy ] ) ) {
			return $depths[ $taxonomy ];
		}

		$parents = [];
		$output  = [];
		$terms   = (array) (
			new \WP_Term_Query(
				[
					'taxonomy' => $taxonomy,
					'fields'   => 'id=>parent',
				]
			)
		)->terms;

		foreach ( $terms as $term_id => $parent ) {
			$parents[ $term_id ] = $parent;
		}

		foreach ( $terms as $term_id => $parent ) {

			if ( 1 > $parent ) {

				$output[ $term_id ] = [];
				continue;

			}

			$output[ $term_id ] = [ $parent ];

			while ( 0 < $parent ) {

				$parent = $parents[ $parent ];

				if ( 1 > $parent ) {
					break;
				}

				$output[ $term_id ][] = $parent;

			}
		}

		$depths[ $taxonomy ] = $output;

		return $output;

	}

	/**
	 * Delete facet from index table.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $slug Facet slug.
	 */
	public static function delete_index( $slug ) {

		global $wpdb;

		$wpdb->delete(
			$wpdb->prefix . 'wpgb_index',
			[ 'slug' => $slug ],
			[ '%s' ]
		);
	}

	/**
	 * Get indexable/filterable facets.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param  array $ids IDs of facets.
	 * @return array
	 */
	public static function get_indexable_facets( $ids = -1 ) {

		$facet_ids = -1 === (int) $ids ? '' : (array) $ids;
		$defaults  = apply_filters( 'wp_grid_builder/defaults/facet', [] );
		$facets    = Database::query_results(
			[
				'select'  => 'id, slug, type, source, settings',
				'from'    => 'facets',
				'orderby' => 'date DESC',
				'id'      => $facet_ids,
			]
		);

		$facets = array_filter(
			(array) $facets,
			function( $facet ) {

				$can_filter = 'selection' !== $facet['type'] && 'search' !== $facet['type'];
				return ! empty( $facet['source'] ) && $can_filter;

			}
		);

		return array_map(
			function( $facet ) use ( $defaults ) {

				// We cast the facet ID.
				$facet['id'] = (int) $facet['id'];

				$settings = json_decode( $facet['settings'], true );
				$settings = wp_parse_args( $settings, $defaults );

				// Remove settings before merge.
				unset( $facet['settings'] );
				// Add facet normalized settings.
				$settings = array_merge( $settings, $facet );

				return $settings;

			},
			$facets
		);
	}

	/**
	 * Get list of image sizes
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array
	 */
	public static function get_image_sizes() {

		static $image_sizes;

		if ( isset( $image_sizes ) ) {
			return $image_sizes;
		}

		$image_sizes = [ 'full', 'thumbnail', 'medium', 'medium_large', 'large' ];
		$image_sizes = array_combine( $image_sizes, $image_sizes );
		$image_sizes = array_merge( $image_sizes, wp_get_additional_image_sizes() );

		foreach ( $image_sizes as $key => $args ) {

			if ( ! isset( $args['width'], $args['height'] ) ) {

				$args = [
					'width'  => get_option( $args . '_size_w' ),
					'height' => get_option( $args . '_size_h' ),
				];
			}

			unset( $args['crop'] );
			$size = array_filter( $args );
			$size = join( ' x ', $size );

			$image_sizes[ $key ] = $key . ( $size ? ' (' . $size . ')' : '' );

		}

		return $image_sizes;

	}

	/**
	 * Get file contents
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param  string $file File to read.
	 * @return string
	 */
	public static function file_get_contents( $file ) {

		// To make sur we get content from plugin file.
		$file = wp_normalize_path( WPGB_PATH . $file );

		if ( ! file_exists( $file ) ) {
			return '';
		}

		// Some shared hosting disable file access.
		if ( function_exists( 'ini_get' ) && ini_get( 'allow_url_fopen' ) ) {
			// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
			return file_get_contents( $file );
		}

		// Fallback to include.
		// This helper is only used with .json from the plugin folder.
		// There is nothing to execute from these files.
		ob_start();
		require $file;
		return ob_get_clean();

	}

	/**
	 * Delete transient
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $name Transient partial name.
	 */
	public static function delete_transient( $name = '' ) {

		global $wpdb;

		$wpdb->query(
			$wpdb->prepare(
				"DELETE FROM {$wpdb->options}
				WHERE option_name LIKE %s
				OR option_name LIKE %s",
				$wpdb->esc_like( '_site_transient_wpgb_' . $name ) . '%',
				$wpdb->esc_like( '_transient_wpgb_' . $name ) . '%'
			)
		);
	}

	/**
	 * Get template part
	 *
	 * @since 1.2.1 Support of require_once
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string  $template      Template name.
	 * @param mixed   $wpgb_template Var to pass in the template.
	 * @param boolean $require_once  Whether to require_once or require.
	 */
	public static function get_template( $template = '', $wpgb_template = '', $require_once = false ) {

		if ( empty( $template ) ) {
			return;
		}

		$folder     = 'wp-grid-builder';
		$template   = '/templates/' . ltrim( $template . '.php', '/' );
		$child_dir  = trailingslashit( get_stylesheet_directory() );
		$parent_dir = trailingslashit( get_template_directory() );
		$plugin_dir = trailingslashit( WPGB_PATH . 'includes/frontend/' );

		if ( file_exists( $child_dir . $folder . $template ) ) {
			// Child theme.
			$located = $child_dir . $folder . $template;
		} elseif ( file_exists( $parent_dir . $folder . $template ) ) {
			// Parent theme.
			$located = $parent_dir . $folder . $template;
		} else {
			// Native Plugin template.
			$located = $plugin_dir . $template;
		}

		$located = wp_normalize_path( $located );

		if ( ! is_file( $located ) ) {
			return;
		}

		if ( $require_once ) {
			require_once $located;
		} else {
			require $located;
		}
	}

	/**
	 * Return list of oembed providers
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array
	 */
	public static function get_embed_providers() {

		// phpcs:disable WordPress.Arrays.MultipleStatementAlignment.DoubleArrowNotAligned, WordPress.Arrays.MultipleStatementAlignment.LongIndexSpaceBeforeDoubleArrow
		return [
			'#https?://?(?:www\.|m\.)?youtube\.com/?(?:watch\?v=|embed/|live/|shorts/)?([\w\-_]+)+#i'              => 'youtube',
			'#https?://youtu\.be/?([\w\-_]+)+#i'                                                                   => 'youtube',
			'#https?://?player.vimeo\.com/video/?([\w\-_]+)+#i'                                                    => 'vimeo',
			'#https?://?(?:www\.)?vimeo\.com/?([\w\-_]+)+#i'                                                       => 'vimeo',
			'#https?://?(?:.+)?(?:wistia\.com|wistia\.net|wi\.st)/?(?:embed/)?(?:iframe|playlists)/?([\w\-_]+)+#i' => 'wistia',
		];
		// phpcs:enable WordPress.Arrays.MultipleStatementAlignment.DoubleArrowNotAligned, WordPress.Arrays.MultipleStatementAlignment.LongIndexSpaceBeforeDoubleArrow
	}

	/**
	 * Retrieve oembed data.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $provider  Provider type (youtube, vimeo, wistia).
	 * @param string $video_id  Video id to retrieve oembed data.
	 * @return object|false
	 */
	public static function get_oembed_data( $provider, $video_id ) {

		$providers = [
			'youtube' => 'https://www.youtube.com/watch?v=%s',
			'vimeo'   => 'https://vimeo.com/%d',
			'wistia'  => 'https://fast.wistia.com/embed/iframe/%s',
		];

		if ( ! isset( $providers[ $provider ] ) ) {
			return false;
		}

		if ( ! class_exists( 'WP_oEmbed' ) ) {
			include ABSPATH . WPINC . '/class-oembed.php';
		}

		$url   = sprintf( $providers[ $provider ], $video_id );
		$embed = _wp_oembed_get_object();
		$embed = $embed->get_data( $url );

		if ( empty( $embed ) ) {
			return false;
		}

		return $embed;

	}

	/**
	 * Sanitize facet value (for query string value in URL)
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $str String to sanitize.
	 * @return string|float
	 */
	public static function sanitize_facet_value( $str ) {

		if ( is_numeric( $str ) && ! is_int( $str ) ) {
			return (float) $str + 0;
		}

		$str = remove_accents( $str );
		// phpcs:ignore WordPress.WP.AlternativeFunctions.strip_tags_strip_tags
		$str = strip_tags( $str );
		// Convert nbsp, ndash, mdash and its entities to hyphens.
		$str = str_replace( [ '%c2%a0', '%e2%80%93', '%e2%80%94' ], '-', $str );
		$str = str_replace( [ '&nbsp;', '&#160;', '&ndash;', '&#8211;', '&mdash;', '&#8212;' ], '-', $str );
		// kill entities.
		$str = preg_replace( '/&.+?;/', '', $str );
		$str = preg_replace( '/\s+/', '-', $str );
		$str = preg_replace( '|-+|', '-', $str );
		$str = str_replace( [ ',', '.' ], '-', $str );
		$str = strtolower( $str );

		// Facet_value column accept 191 chars
		// Url is also limited in length (2,083 chars).
		if ( 80 < strlen( $str || '' ) ) {
			$str = md5( $str );
		}

		return $str;

	}

	/**
	 * Split string into words (to use in SQL LIKE clauses)
	 *
	 * @since 1.3.0
	 * @access public
	 *
	 * @param string $string Holds string to split.
	 * @return array
	 */
	public static function split_into_words( $string = '' ) {

		$terms = [];

		if ( ! preg_match_all( '/".*?("|$)|((?<=[\t ",+])|^)[^\t ",+]+/', $string, $matches ) ) {
			return [ $string ];
		}

		foreach ( $matches[0] as $term ) {

			// We remove all types of quote to prevent missing match.
			$term = str_replace( [ "'", '"', '＂', '‘', '’', '`' ], ' ', $term );
			$term = trim( $term );

			if ( empty( $term ) ) {
				continue;
			}

			$terms = array_merge( $terms, preg_split( '/\s+/', $term ) );

		}

		return $terms ?: [ $string ];

	}
}
