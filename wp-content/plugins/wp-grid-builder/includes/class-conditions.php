<?php
/**
 * Conditions
 *
 * @package   WP Grid Builder
 * @author    Loïc Blascos
 * @copyright 2019-2024 Loïc Blascos
 */

namespace WP_Grid_Builder\Includes;

use WP_Grid_Builder\Includes\Helpers;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handle conditions
 *
 * @class WP_Grid_Builder\Includes\Conditions
 * @since 2.0.0
 */
final class Conditions {

	/**
	 * Constructor
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function __construct() {

		add_filter( 'wp_grid_builder/do_conditions', [ $this, 'do_conditions' ], 10, 3 );
		add_filter( 'wp_grid_builder/condition/value', [ $this, 'post_conditions' ], 0, 4 );
		add_filter( 'wp_grid_builder/condition/value', [ $this, 'term_conditions' ], 0, 4 );
		add_filter( 'wp_grid_builder/condition/value', [ $this, 'user_conditions' ], 0, 4 );
		add_filter( 'wp_grid_builder/condition/value', [ $this, 'current_user_conditions' ], 0, 2 );
		add_filter( 'wp_grid_builder/condition/value', [ $this, 'content_conditions' ], 0, 4 );
		add_filter( 'wp_grid_builder/condition/value', [ $this, 'facet_conditions' ], 0, 4 );
		add_filter( 'wp_grid_builder/condition/value', [ $this, 'date_conditions' ], 0, 2 );
		add_filter( 'wp_grid_builder/condition/value', [ $this, 'url_conditions' ], 0, 2 );
		add_filter( 'wp_grid_builder/condition/value', [ $this, 'device_conditions' ], 0, 2 );

	}

	/**
	 * Do conditions
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param boolean $render     whether or not to render.
	 * @param array   $conditions Holds conditions.
	 * @param string  $context    Condition context.
	 * @return string
	 */
	public function do_conditions( $render, $conditions = [], $context = '' ) {

		if ( empty( $conditions ) ) {
			return true;
		}

		// Return true by default.
		$render = true;

		// OR clauses.
		foreach ( $conditions as $clauses ) {

			if ( ! is_array( $clauses ) ) {
				continue;
			}

			// Reset render on each OR clause.
			$render = true;

			// AND clauses.
			foreach ( $clauses as $clause ) {

				// Bypass subsequent AND clauses if any preceding AND clause is not satisfied.
				if ( false === $render ) {
					continue;
				}

				// Ignore invalid clause.
				if ( ! is_array( $clause ) ) {
					continue;
				}

				// Ignore empty clause.
				if (
					! isset( $clause['value'] ) || '' === $clause['value'] ||
					( is_array( $clause['value'] ) && empty( $clause['value'] ) ) ||
					empty( $clause['compare'] ) || empty( $clause['key'] )
				) {
					continue;
				}

				$value  = apply_filters( 'wp_grid_builder/condition/value', '', $clause['key'], $clause, $context );
				$render = $this->compare( $value, apply_filters( 'wp_grid_builder/dynamic_data', $clause['value'], $context ), $clause['compare'] );
				$render = apply_filters( 'wp_grid_builder/do_condition', $render, $clause['key'], $clause, $context );

			}

			// Bypass subsequent OR clauses if all previous AND clauses are satisfied.
			if ( true === $render ) {
				break;
			}
		}

		return $render;

	}

	/**
	 * Compare values from condition clause
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param boolean $value      Condition value.
	 * @param array   $to_compare Value to compare with.
	 * @param string  $operator   Operator used to compare with.
	 * @return boolean Comparison result.
	 */
	public function compare( $value, $to_compare, $operator ) {

		switch ( $operator ) {
			case '==':
				return $value == $to_compare; // phpcs:ignore Universal.Operators.StrictComparisons.LooseEqual
			case '!=':
				return $value != $to_compare; // phpcs:ignore Universal.Operators.StrictComparisons.LooseNotEqual
			case '>=':
				return $value >= $to_compare;
			case '<=':
				return $value <= $to_compare;
			case '>':
				return $value > $to_compare;
			case '<':
				return $value < $to_compare;
			case 'IN':
				return count( array_intersect( (array) $value, (array) $to_compare ) ) > 0;
			case 'NOT IN':
				return count( array_intersect( (array) $value, (array) $to_compare ) ) === 0;
			case 'CONTAINS':
				return Helpers::strpos_array( $value, $to_compare );
			case 'NOT CONTAINS':
				return ! Helpers::strpos_array( $value, $to_compare );
		}

		return false;

	}

	/**
	 * Post conditions
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param string $return    Returned value.
	 * @param string $key       key condition.
	 * @param array  $condition Condition arguments.
	 * @param string $context   Condition context.
	 * @return mixed
	 */
	public function post_conditions( $return, $key, $condition, $context ) {

		if ( 'card' !== $context ) {
			return $return;
		}

		if ( false === strpos( $key, 'post_' ) ) {
			return $return;
		}

		switch ( $key ) {
			case 'post_id':
				return wpgb_get_the_id();
			case 'post_parent':
				return wpgb_get_post_parent_id();
			case 'post_name':
				return wpgb_get_the_name();
			case 'post_title':
				return wpgb_get_the_title();
			case 'post_author':
				return wpgb_get_the_author_meta( 'ID' );
			case 'post_permalink':
				return wpgb_get_the_permalink();
			case 'post_date':
				return wpgb_get_the_date( 'Y-m-d' );
			case 'post_modified_date':
				return wpgb_get_the_modified_date( 'Y-m-d' );
			case 'post_content':
				return wpgb_get_the_content();
			case 'post_excerpt':
				return wpgb_get_the_excerpt( -1, '' );
			case 'post_status':
				return wpgb_get_post_status();
			case 'post_type':
				return wpgb_get_post_type();
			case 'post_format':
				return wpgb_get_post_format();
			case 'post_thumbnail':
				return wpgb_has_post_thumbnail();
			case 'post_comments_number':
				return wpgb_get_comments_number();
			case 'post_metadata':
				return wpgb_format_metadata( [], wpgb_get_metadata( trim( $condition['field'] ?? '' ) ) );
			case 'post_term':
				return (array) wpgb_get_the_terms(
					[
						'include' => $condition['value'],
						'fields'  => 'ids',
					]
				);
		}

		return $return;

	}

	/**
	 * Term conditions
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param string $return    Returned value.
	 * @param string $key       key condition.
	 * @param array  $condition Condition arguments.
	 * @param string $context   Condition context.
	 * @return mixed
	 */
	public function term_conditions( $return, $key, $condition, $context ) {

		if ( 'card' !== $context ) {
			return $return;
		}

		if ( false === strpos( $key, 'term_' ) ) {
			return $return;
		}

		switch ( $key ) {
			case 'term_id':
				return wpgb_get_the_term_id();
			case 'term_slug':
				return wpgb_get_term_slug();
			case 'term_name':
				return wpgb_get_term_name();
			case 'term_taxonomy':
				return wpgb_get_term_taxonomy();
			case 'term_parent':
				return wpgb_get_term_parent();
			case 'term_description':
				return wpgb_get_term_description( -1, '' );
			case 'term_count':
				return wpgb_get_term_count();
			case 'term_metadata':
				return wpgb_format_metadata( [], wpgb_get_metadata( trim( $condition['field'] ?? '' ) ) );
		}

		return $return;

	}

	/**
	 * User conditions
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param string $return    Returned value.
	 * @param string $key       key condition.
	 * @param array  $condition Condition arguments.
	 * @param string $context   Condition context.
	 * @return mixed
	 */
	public function user_conditions( $return, $key, $condition, $context ) {

		if ( 'card' !== $context ) {
			return $return;
		}

		if ( false === strpos( $key, 'user_' ) ) {
			return $return;
		}

		switch ( $key ) {
			case 'user_id':
				return wpgb_get_the_user_id();
			case 'user_login':
				return wpgb_get_user_login();
			case 'user_display_name':
				return wpgb_get_user_display_name();
			case 'user_first_name':
				return wpgb_get_user_first_name();
			case 'user_last_name':
				return wpgb_get_user_last_name();
			case 'user_nickname':
				return wpgb_get_user_nickname();
			case 'user_description':
				return wpgb_get_user_description();
			case 'user_email':
				return wpgb_get_user_email();
			case 'user_url':
				return wpgb_get_user_url();
			case 'user_roles':
				return wpgb_get_user_roles();
			case 'user_post_count':
				return wpgb_get_user_post_count();
			case 'user_metadata':
				return wpgb_format_metadata( [], wpgb_get_metadata( trim( $condition['field'] ?? '' ) ) );
		}

		return $return;

	}

	/**
	 * Current user condtions
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param string $return Returned value.
	 * @param string $key    key condition.
	 * @return mixed
	 */
	public function current_user_conditions( $return, $key ) {

		if ( 0 !== strpos( $key, 'current_user_' ) ) {
			return $return;
		}

		if ( 'current_user_logged_in' === $key ) {
			return is_user_logged_in();
		}

		$current_user = wp_get_current_user();

		if ( ! isset( $current_user->ID ) ) {
			return '';
		}

		switch ( $key ) {
			case 'current_user_id':
				return $current_user->ID;
			case 'current_user_login':
				return $current_user->user_login;
			case 'current_user_registered':
				return date_i18n( 'Y-m-d', strtotime( $current_user->user_registered ) );
			case 'current_user_display_name':
				return $current_user->display_name;
			case 'current_user_first_name':
				return $current_user->user_firstname;
			case 'current_user_last_name':
				return $current_user->user_lastname;
			case 'current_user_email':
				return $current_user->user_email;
			case 'current_user_url':
				return $current_user->user_url;
			case 'current_user_roles':
				return $current_user->roles;
		}

		return $return;

	}

	/**
	 * Content conditions
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param string $return    Returned value.
	 * @param string $key       key condition.
	 * @param array  $condition Condition arguments.
	 * @param string $context   Context.
	 * @return integer|string
	 */
	public function content_conditions( $return, $key, $condition, $context ) {

		if ( ! in_array( $context, [ 'card', 'facet' ], true ) ) {
			return $return;
		}

		if ( false === strpos( $key, 'content_' ) ) {
			return $return;
		}

		switch ( $key ) {
			case 'content_id':
				if ( 'card' === $context ) {
					return wpgb_get_grid_settings( 'id' );
				}

				$facets = wpgb_get_facets_instance();

				if ( ! empty( $facets->settings['id'] ) ) {
					return $facets->settings['id'];
				}

				return '';
			case 'content_state':
				return wpgb_is_content_filtered();
		}

		return $return;

	}

	/**
	 * Facet conditions
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param string $return    Returned value.
	 * @param string $key       key condition.
	 * @param array  $condition Condition arguments.
	 * @param string $context   Context.
	 * @return mixed
	 */
	public function facet_conditions( $return, $key, $condition, $context ) {

		if ( ! in_array( $context, [ 'card', 'facet' ], true ) ) {
			return $return;
		}

		if ( false === strpos( $key, 'facet_' ) ) {
			return $return;
		}

		if ( ! empty( $condition['field'] ) ) {
			$facet = current( wpgb_get_facet_instances( $condition['field'] ) );
		}

		switch ( $key ) {
			case 'facet_value':
				return ! isset( $facet['slug'] ) ?: wpgb_get_selected_facet_values( $facet['slug'] );
			case 'facet_state':
				return ! isset( $facet['slug'] ) ?: count( wpgb_get_selected_facet_values( $facet['slug'] ) ) > 0;
			case 'facet_result_count':
				return wpgb_get_found_objects();
		}

		return $return;

	}

	/**
	 * Date conditions
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param string $return Returned value.
	 * @param string $key    key condition.
	 * @return string
	 */
	public function date_conditions( $return, $key ) {

		if ( false === strpos( $key, 'date_' ) ) {
			return $return;
		}

		switch ( $key ) {
			case 'date_weekday':
				return date_i18n( 'w' );
			case 'date_date':
				return date_i18n( 'Y-m-d' );
			case 'date_time':
				return date_i18n( 'H:i' );
			case 'date_datetime':
				return date_i18n( 'Y-m-d H:i' );
		}

		return $return;

	}

	/**
	 * Url conditions
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param string $return Returned value.
	 * @param string $key    key condition.
	 * @return string
	 */
	public function url_conditions( $return, $key ) {

		if ( false === strpos( $key, 'url_' ) ) {
			return $return;
		}

		switch ( $key ) {
			case 'url_current':
				if ( wpgb_doing_ajax() || wp_doing_ajax() ) {
					return wp_get_raw_referer();
				} else {
					$url_parts = wp_parse_url( home_url() );

					if ( isset( $url_parts['scheme'], $url_parts['host'] ) ) {
						return $url_parts['scheme'] . '://' . $url_parts['host'] . add_query_arg( null, null );
					} else {
						return get_pagenum_link();
					}
				}
			case 'url_referer':
				return wp_get_raw_referer();
		}

		return $return;

	}

	/**
	 * Device conditions
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param string $return Returned value.
	 * @param string $key    key condition.
	 * @return string
	 */
	public function device_conditions( $return, $key ) {

		if ( false === strpos( $key, 'device_' ) ) {
			return $return;
		}

		if ( empty( $_SERVER['HTTP_USER_AGENT'] ) ) {
			return $return;
		}

		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$agent = wp_unslash( $_SERVER['HTTP_USER_AGENT'] );

		switch ( $key ) {
			case 'device_browser':
				$browsers = [
					'opr',
					'opera',
					'edg',
					'edge',
					'chrome',
					'firefox',
					'safari',
					'msie',
					'trident',
				];

				foreach ( $browsers as $browser ) {
					if ( stripos( $agent, $browser ) !== false ) {
						return str_replace( [ 'opr', 'edg', 'trident' ], [ 'opera', 'edge', 'msie' ], $browser );
					}
				}

				return '';
			case 'device_os':
				$systems = [
					'win',
					'mac',
					'linux',
					'ubuntu',
					'iphone',
					'ipad',
					'ipod',
					'android',
					'blackberry',
					'webos',
				];

				foreach ( $systems as $system ) {
					if ( stripos( $agent, $system ) !== false ) {
						return $system;
					}
				}

				return '';

		}

		return $return;

	}
}
