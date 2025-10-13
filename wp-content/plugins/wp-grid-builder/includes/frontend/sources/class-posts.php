<?php
/**
 * Query Posts
 *
 * @package   WP Grid Builder
 * @author    Loïc Blascos
 * @copyright 2019-2024 Loïc Blascos
 */

namespace WP_Grid_Builder\Includes\FrontEnd\Sources;

use WP_Grid_Builder\Includes\Helpers;
use WP_Grid_Builder\Includes\First_Media;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Post class.
 *
 * @class WP_Grid_Builder\FrontEnd\Sources\Posts
 * @since 1.0.0
 */
class Posts extends Base {

	/**
	 * Holds settings instance
	 *
	 * @since 1.0.0
	 * @access protected
	 *
	 * @var array
	 */
	protected $settings;

	/**
	 * Holds attachment instance
	 *
	 * @since 1.0.0
	 * @access protected
	 *
	 * @var array
	 */
	protected $attachment;

	/**
	 * WP_Query args
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @var array
	 */
	protected $query_args;

	/**
	 * Hold queried taxonomy terms
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @var array
	 */
	private $term_taxonomies = [];

	/**
	 * WP_Query
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @var object
	 */
	public $query;

	/**
	 * Holds queried posts
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @var array
	 */
	public $posts = [];

	/**
	 * Holds post object
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @var object
	 */
	public $post = [];

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $settings Holds grid settings.
	 */
	public function __construct( $settings ) {

		$this->settings   = $settings;
		$this->attachment = new Attachment( $settings );

	}

	/**
	 * Parse posts query args
	 *
	 * @since 1.4.0
	 * @access public
	 */
	public function parse_query() {

		if ( $this->settings->is_main_query ) {
			$this->main_query();
		} else {

			$this->build_query();
			$this->run_query();

		}
	}

	/**
	 * Retrieves posts based on query variables
	 *
	 * @since 1.4.0
	 * @access public
	 *
	 * @return array Queried objects
	 */
	public function get_results() {

		if ( ! $this->query || ! $this->query->have_posts() ) {
			return [];
		}

		$this->do_loop();
		$this->get_attachments();

		return apply_filters( 'wp_grid_builder/grid/the_objects', $this->posts );

	}

	/**
	 * Get attachment
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function get_attachments() {

		if ( empty( $this->attachment->ids ) ) {
			return;
		}

		$this->attachment->query( $this->posts );

	}

	/**
	 * Run main WP query
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function main_query() {

		global $wp_query;

		if ( wpgb_doing_ajax() && ! empty( $this->settings->main_query ) ) {

			// Add language to prevent issue when querying asynchronously.
			$this->settings->main_query['lang'] = $this->settings->lang;
			// Turns off SQL_CALC_FOUND_ROWS even when limits are present.
			$this->settings->main_query['no_found_rows'] = true;
			// Add WP Grid Builder to query args.
			$this->settings->main_query['wp_grid_builder'] = $this->settings->id;

			$this->query = new \WP_Query( $this->settings->main_query );

		} elseif ( is_main_query() && ! is_admin() ) {
			$this->query = $wp_query;
		}

	}

	/**
	 * Build custom query
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function build_query() {

		$this->settings->order_clauses = $this->settings->post_order;

		$this->set_post_type();
		$this->set_post_status();
		$this->set_posts_per_page();
		$this->set_offset();
		$this->set_author__in();
		$this->set_post__in();
		$this->set_post__not_in();
		$this->set_sticky_posts();
		$this->orderby_clauses();
		$this->meta_query_clauses();
		$this->tax_query_clauses();
		$this->set_attachments();
		$this->set_mime_types();

	}

	/**
	 * Run custom query
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function run_query() {

		// Add language to prevent issue when querying asynchronously.
		$this->query_args['lang'] = $this->settings->lang;
		// Turns off SQL_CALC_FOUND_ROWS even when limits are present.
		$this->query_args['no_found_rows'] = true;
		// Add WP Grid Builder to query args.
		$this->query_args['wp_grid_builder'] = $this->settings->id;
		// Filter the query args.
		$this->query_args = apply_filters( 'wp_grid_builder/grid/query_args', $this->query_args, $this->settings->id );
		// Run the query.
		$this->query = new \WP_Query( $this->query_args );

	}

	/**
	 * Set post_type parameter
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function set_post_type() {

		$post_type = $this->settings->post_type;
		$post_type = ! empty( $post_type ) ? $post_type : 'any';

		$this->query_args['post_type'] = (array) $post_type;

	}

	/**
	 * Set post_status parameter
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function set_post_status() {

		$this->query_args['post_status'] = (array) $this->settings->post_status;

	}

	/**
	 * Set posts_per_page parameter
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function set_posts_per_page() {

		$this->query_args['posts_per_page'] = (int) $this->settings->posts_per_page;

	}

	/**
	 * Set offset parameter
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function set_offset() {

		$this->query_args['offset'] = (int) $this->settings->offset;

	}

	/**
	 * Set author__in parameter
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function set_author__in() {

		$this->query_args['author__in'] = (array) $this->settings->author__in;

	}

	/**
	 * Set post__in parameter
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function set_post__in() {

		$this->query_args['post__in'] = (array) $this->settings->post__in;

	}

	/**
	 * Set post__not_in parameter
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function set_post__not_in() {

		$this->query_args['post__not_in'] = (array) $this->settings->post__not_in;

	}

	/**
	 * Set post__not_in parameter
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function set_sticky_posts() {

		$this->query_args['ignore_sticky_posts'] = (bool) $this->settings->ignore_sticky_posts;

	}

	/**
	 * Set attachments ids in post__in or post__not_in
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function set_attachments() {

		// If no attachment post type selected.
		if ( ! in_array( 'attachment', $this->query_args['post_type'], true ) ) {
			return;
		}

		$attachment_ids = (array) $this->settings->attachment_ids;

		// Add "inherit" status to retrieve attachments.
		array_push( $this->query_args['post_status'], 'publish', 'inherit' );

		if ( empty( $attachment_ids ) ) {
			return;
		}

		$attachment_ids = array_filter( $attachment_ids );

		// Merge post__in if not empty.
		if ( ! empty( $this->query_args['post__in'] ) ) {

			$this->query_args['post__in'] = array_merge(
				$this->query_args['post__in'],
				$attachment_ids
			);

			return;

		}

		// If several post types set.
		if ( count( $this->query_args['post_type'] ) > 1 ) {

			$this->query_args['post__not_in'] = array_merge(
				$this->query_args['post__not_in'],
				array_diff(
					apply_filters( 'wp_grid_builder/all_attachment_ids', [] ),
					$attachment_ids
				)
			);

			return;

		}

		if ( ! isset( $this->query_args['orderby'] ) ) {
			$this->query_args['orderby'] = [];
		}

		if (
			is_array( $this->query_args['orderby'] ) &&
			! isset( $this->query_args['orderby']['post__in'] )
		) {
			$this->query_args['orderby']['post__in'] = 'ASC';
		} elseif ( is_string( $this->query_args['orderby'] ) ) {
			$this->query_args['orderby'] .= ' post__in';
		}

		$this->query_args['post__in'] = $attachment_ids;

	}


	/**
	 * Set post mime types
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function set_mime_types() {

		// If no attachment post type selected or media selected.
		if ( ! in_array( 'attachment', $this->query_args['post_type'], true )
			|| ! empty( $this->settings->attachment_ids )
			|| count( $this->query_args['post_type'] ) > 1 ) {
			return;
		}

		$this->query_args['post_mime_type'] = (array) $this->settings->post_mime_type;

	}

	/**
	 * Custom query loop
	 *
	 * @since 1.2.0 Parse attachment ids after the object is filtered.
	 * @since 1.0.0
	 * @access public
	 */
	public function do_loop() {

		while ( $this->query->have_posts() ) {

			$this->query->the_post();

			$this->get_post();
			$this->get_metadata();
			$this->get_permalink();
			$this->get_post_format();
			$this->get_post_author();
			$this->get_post_media();
			$this->get_product_data();

			$this->posts[] = apply_filters( 'wp_grid_builder/grid/the_object', $this->post );
			$this->attachment->parse_attachment_ids( $this->post );

		}

		wp_reset_postdata();

	}

	/**
	 * Build item array
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function get_post() {

		global $post;

		$this->post                = clone $post;
		$this->post->object_type   = 'post';
		$this->post->post_sticky   = is_sticky();
		$this->post->post_status   = get_post_status();
		$this->post->post_date     = get_the_date( 'U' );
		$this->post->post_modified = get_the_modified_date( 'U' );
		$this->post->post_title    = get_the_title();
		$this->post->post_content  = get_the_content( null, false, $post->ID );

	}

	/**
	 * Get post permalink
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function get_permalink() {

		$this->post->permalink = $this->post->metadata[ WPGB_SLUG ]['permalink'];

		if ( empty( $this->post->permalink ) ) {
			$this->post->permalink = get_the_permalink( $this->post->ID );
		}
	}

	/**
	 * Get post format
	 *
	 * @since 1.1.8 Preserve unsupported post formats.
	 * @since 1.0.0
	 * @access public
	 */
	public function get_post_format() {

		$post_format = $this->post->metadata[ WPGB_SLUG ]['post_format'];
		$post_format = empty( $post_format ) ? get_post_format() : $post_format;
		$post_format = empty( $post_format ) ? 'standard' : $post_format;

		$supported_formats = [ 'gallery', 'audio', 'video' ];
		$format_supported = in_array( $post_format, $supported_formats, true );
		$format_allowed = in_array( $post_format, $this->settings->post_formats, true );

		// We keep not supported formats (to assign cards to them) and we exclude support for not allowed formats.
		$this->post->post_format = ! $format_supported || $format_allowed ? $post_format : 'standard';

	}

	/**
	 * Get author data
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function get_post_author() {

		$author = get_the_author_meta( 'ID' );
		$avatar = get_avatar_data( $author );

		$this->post->post_author = [
			'ID'           => $author,
			'display_name' => get_the_author_meta( 'display_name' ),
			'posts_url'    => get_author_posts_url( $author ),
			'avatar'       => $avatar,
		];
	}

	/**
	 * Get media content according to post format
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function get_post_media() {

		$source = $this->get_media();
		$format = $this->post->post_format;

		// If no alternative format available, get default attachement format and source.
		if ( empty( $source ) && 'attachment' === $this->post->post_type ) {

			$format = $this->get_attachment_format();
			$source = $this->get_attachment_url();

		}

		// Get thumbnail whatever the post format.
		$this->get_thumbnail();

		if ( 'standard' === $format ) {
			return;
		}

		// Try to fetch first media (audio, video & gallery) if missng.
		if ( empty( $source ) && $this->settings->first_media ) {
			$source = ( new First_Media( $this->post ) )->get( $format );
		}

		// If not content found set post to standard format.
		if ( empty( $source ) || ! is_array( $source ) ) {
			return;
		}

		$source['format'] = $format;
		$this->post->post_media = $source;

	}

	/**
	 * Get thumbnail ID or data
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function get_thumbnail() {

		// Get alternative attachment ID (from metadata).
		$thumb = (int) $this->post->metadata[ WPGB_SLUG ]['attachment_id'];

		// If image attachment directly get image.
		if ( $thumb < 1 && 'attachment' === $this->post->post_type && wp_attachment_is_image( $this->post->ID ) ) {

			$this->post->post_thumbnail = $this->attachment->get_attachment( $this->post );
			return;

		}

		// Get thumbnail id.
		if ( $thumb < 1 ) {
			$thumb = get_post_thumbnail_id();
		}

		// Try to get first image in post content if thumb missing.
		if ( empty( $thumb ) && $this->settings->first_media && 'attachment' !== $this->post->post_type ) {
			$thumb = ( new First_Media( $this->post ) )->get();
		}

		if ( empty( $thumb ) ) {
			return;
		}

		$this->post->post_thumbnail = $thumb;

	}

	/**
	 * Get attachment media format (audi or video)
	 *
	 * @since 1.1.8
	 * @access public
	 *
	 * @return string
	 */
	public function get_attachment_format() {

		$format  = wp_attachment_is( 'video' ) ? 'video' : 'standard';
		$format  = wp_attachment_is( 'audio' ) ? 'audio' : $format;
		$allowed = in_array( $format, $this->settings->post_formats, true );

		// We exclude support for not allowed formats.
		$this->post->post_format = $allowed ? $format : 'standard';

		return $format;

	}

	/**
	 * Get attachment url for audio or video format
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array
	 */
	public function get_attachment_url() {

		if (
			'video' !== $this->post->post_format &&
			'audio' !== $this->post->post_format
		) {
			return [];
		}

		$file_url = wp_get_attachment_url( $this->post->ID );

		if ( empty( $file_url ) ) {
			return [];
		}

		return [
			'type'    => 'hosted',
			'sources' => (array) $file_url,
		];
	}

	/**
	 * Get product data
	 *
	 * @since 1.2.0 Add support for WooCommerce product_variation post type.
	 * @since 1.1.5 Add first product gallery in attachment.
	 * @since 1.0.0
	 * @access public
	 */
	public function get_product_data() {

		if (
			(
				'product' === $this->post->post_type ||
				'product_variation' === $this->post->post_type
			) &&
			class_exists( 'WooCommerce' )
		) {

			$this->post->product = ( new Woo() )->post;
			$this->attachment->ids[] = $this->post->product->first_gallery_image;

		}

		if ( 'download' === $this->post->post_type && class_exists( 'Easy_Digital_Downloads' ) ) {
			$this->post->product = ( new EDD( $this->post->ID ) )->post;
		}
	}
}
