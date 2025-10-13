<?php
namespace HappyFiles;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Query {
  public function __construct() {
		add_action( 'pre_get_posts', [$this, 'filter_list_view'] );
		add_filter( 'ajax_query_attachments_args', [$this, 'filter_grid_view'] );
	}

	/**
	 * List View: Set open folder ID on page load
	 */
	public function filter_list_view( $query ) {
		if ( ! Data::$load_plugin ) {
			return $query;
		}

		if ( ! $query->is_main_query() ) {
			return $query;
		}

		$post_type = $query->get( 'post_type' );
		$taxonomy  = Helpers::get_taxonomy_by( 'post_type', $post_type );
		$open_id   = 0;

		if ( ! $taxonomy ) {
			return $query;
		}

		unset( $query->query_vars[$taxonomy] );

		// Specific folder requested
		if ( ! empty( $_GET[$taxonomy] ) ) {
			$open_id = intval( $_GET[$taxonomy] );
		}

		// Get open ID (usermeta) or default ID (HappyFiles setting)
		else if ( ! isset( $_GET[$taxonomy] ) ) {
			$open_id = Data::get_open_id( $taxonomy, $post_type ); 
		}

		// Return: No folder OR 'All items' ID (-2) selected
		if ( ! $open_id || $open_id == -2 ) {
			return $query;
		}

		// Return: HappyFiles folder ID no longer exists
		if ( $open_id > 0 && ! term_exists( $open_id, $taxonomy ) ) {
			return $query;
		}

		$tax_query = $query->get( 'tax_query' );

		if ( ! is_array( $tax_query ) ) {
			$tax_query = [];
		}

		// Uncategorized items
		if ( $open_id == -1 ) {
			$tax_query[] = [
				'taxonomy' => $taxonomy,
				'operator' => 'NOT EXISTS',
			];

			$query->set( 'tax_query', $tax_query );
		}

		// HappyFiles taxonomy term
		else {
			$tax_query[] = [
				'taxonomy' 			   => $taxonomy,
				'field' 			     => 'term_id',
				'terms' 			     => $open_id,
				'include_children' => false,
			];

			$query->set( 'tax_query', $tax_query );
		}
	}

	/**
	 * Grid view
	 * 
	 * Filter runs before 'init' so Data::$load_plugin check doesn't work here.
	 */
	public function filter_grid_view( $query = [] ) {
		// Return: WordPres Gallery request (don't order by file size)
		if ( isset( $query['post__in'] ) ) {
			return $query;
		}
		
		if ( User::$folder_access === 'none' ) {
			return $query;
		}

		// Return: Opening modal on post overview admin screen
		// @since 1.8: Modal on post overview screen has no HF sidebar, so don't tweak query
		if ( is_admin() && strpos( wp_get_referer(), 'edit.php' ) !== false ) {
			return $query;
		}

		Data::populate();

		$taxonomy = Data::$taxonomy;
		$open_id = Data::$open_id;

		/**
		 * On page load: Default open folder
		 * 
		 * Determine by 'ignore' param, which HF sends with every folder switch, but not initially.
		 */
		if ( ! isset( $_REQUEST['query']['ignore'] ) && Data::$default_id ) {
			$open_id = Data::$default_id;
		}
		
		$folder_id = ! empty( $query[$taxonomy] ) ? intval( $query[$taxonomy] ) : intval( $open_id );

		// All items
		if ( ! $folder_id || $folder_id < -1 ) {
			return $query;
		}

    // Subsequent grid view AJAX calls
		if ( isset( $query['tax_query'] ) && is_array( $query['tax_query'] ) ) {
			$query['tax_query']['relation'] = 'AND';
		} else {
			$query['tax_query'] = ['relation' => 'AND'];
		}

		// Uncategorized
		if ( $folder_id == -1 ) {
			$query['tax_query'][] = [
				'taxonomy' => $taxonomy,
				'operator' => 'NOT EXISTS',
			];
		}

		// Open ID
		else {
			$query['tax_query'][] = [
				'taxonomy'         => $taxonomy,
				'terms'            => $folder_id,
				'field'            => 'id',
				'include_children' => false,
			];
		}

		// $query['post_mime_type'] = 'image/gif'; // TODO NEXT Query by mime type (GIF, etc.)

		unset( $query[$taxonomy] );

		return $query;
  }
}