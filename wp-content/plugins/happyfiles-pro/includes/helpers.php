<?php
namespace HappyFiles;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Helpers {
	/**
   * Sanitize all $_GET, $_POST, $_REQUEST, $_FILE input data before processing
   *
   * @param $data (array|string)
	 * 
   * @return mixed
   */
  public static function sanitize_data( $data ) {
		// Sanitize string
		if ( is_string( $data ) ) {
			$data = sanitize_text_field( $data );
		}

		// Sanitize each element individually
		else if ( is_array( $data ) ) {
			foreach ( $data as $key => &$value ) {
				if ( is_array( $value ) ) {
					$value = sanitize_data( $value );
				} 
				
				else {
					$value = sanitize_text_field( $value );
				}
			}
		}

		return $data;
	}

	public static function get_taxonomy_by( $type, $data ) {
		// Return taxonomy name for files if not in wp-admin (e.g. page builder frontend editing)
		if ( ! is_admin() || ! $data ) {
			return HAPPYFILES_TAXONOMY;
		}

		switch ( $type ) {
			case 'ID':
				$taxonomy_obj = get_term( $data );

				return is_object( $taxonomy_obj ) ? $taxonomy_obj->taxonomy : HAPPYFILES_TAXONOMY;
			break;

			case 'post_type':
				return $data === 'attachment' ? HAPPYFILES_TAXONOMY : 'hf_cat_' . $data; // Max. tax length of 32 characters
			break;

			default:
				return HAPPYFILES_TAXONOMY;
			break;
		}
	}

	/**
	 * Get flat list of folders ordered by position & parent
	 * 
	 * @see Gallery block folders select dropdown
	 * 
	 * @param string $taxonomy The taxonomy slug.
	 * @param string $post_type The post type (attachment, page, etc.).
	 * @param string $taxonomy Term name level-prefix & count-suffix.
	 * @param boolean $get_all_folders Don't get inside Gutenberg shortcode.php to avoid unnecessary WP_Query (@since 1.8)
	 */
	public static function get_folders_ordered( $taxonomy = '', $post_type = '', $fixes = false, $get_all_folders = true ) {
		$folders = Data::get_folders( $taxonomy, $post_type, $get_all_folders );

		if ( $fixes ) {
			$folders = self::get_folders_by_parent( $folders, 0 );
		}

		return $folders;
	}

	/**
	 * Recursive helper function get generate ordered flat list in get_folders_ordered() function above
	 */
	public static function get_folders_by_parent( $folders, $parent_id = 0 ) {
		$folders_ordered = [];

		foreach ( $folders as $folder ) {
			if ( $folder->parent === $parent_id ) {
				$folder->name = str_repeat( '-', $folder->level ) . ' ' . $folder->name . ' (' . $folder->count . ')';

				$folders_ordered[] = $folder;
				
				$folders_ordered = array_merge( $folders_ordered, self::get_folders_by_parent( $folders, $folder->term_id ) );
			}
		}

		return $folders_ordered;
	}
}