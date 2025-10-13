<?php
namespace HappyFiles;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Ajax {
  public function __construct() {
		add_action( 'wp_ajax_happyfiles_get_data', [$this, 'get_data'] );

		// HappyFiles sidebar actions
		add_action( 'wp_ajax_happyfiles_create_folders', [$this, 'create_folders'] );
		add_action( 'wp_ajax_happyfiles_move_item_ids', [$this, 'move_item_ids'] );
		add_action( 'wp_ajax_happyfiles_move_folder', [$this, 'move_folder'] );
		add_action( 'wp_ajax_happyfiles_export_folders', [$this, 'export_folders'] );
		
		add_action( 'wp_ajax_happyfiles_duplicate_folder', [$this, 'duplicate_folder'] );
		add_action( 'wp_ajax_happyfiles_download_folder', [$this, 'download_folder'] );
		add_action( 'wp_ajax_happyfiles_get_downloads', [$this, 'get_downloads'] );
		add_action( 'wp_ajax_happyfiles_delete_download', [$this, 'delete_download'] );

		add_action( 'wp_ajax_happyfiles_set_folder_color', [$this, 'set_folder_color'] );
		add_action( 'wp_ajax_happyfiles_clear_folder_color', [$this, 'clear_folder_color'] );
		add_action( 'wp_ajax_happyfiles_save_color', [$this, 'save_color'] );
		add_action( 'wp_ajax_happyfiles_delete_color', [$this, 'delete_color'] );

		add_action( 'wp_ajax_happyfiles_save_open_id', [$this, 'save_open_id'] );
		add_action( 'wp_ajax_happyfiles_quick_inspect', [$this, 'quick_inspect'] );
		add_action( 'wp_ajax_happyfiles_hide_import_folders_notification', [$this, 'hide_import_folders_notification'] );

		add_action( 'add_attachment', [$this, 'add_attachment'] );
	}

	/**
	 * Verify nonce used in HappyFiles AJAX call
	 * 
	 * Default required folder access level: full
	 *
	 * @return void
	 */
	public static function verify_nonce( $folder_access = 'full' ) {
		if ( ! check_ajax_referer( 'happyfiles-nonce', 'nonce', false ) ) {
			wp_send_json_error( 'verify_nonce: invalid' );
		}

		if ( User::$folder_access !== $folder_access ) {
			wp_send_json_error( 'folder_access: insufficient' );
		}
	}

	/**
	 * Get HappyFiles folder data when opening media modal
	 * 
	 * @since 1.8
	 */
	public function get_data() {
		if ( ! empty( $_POST['postType'] ) ) {
			Data::$post_type = $_POST['postType'];
		}
		
		if ( ! empty( $_POST['taxonomy'] ) ) {
			Data::$taxonomy = $_POST['taxonomy'];
		}

		Data::get_open_id( Data::$taxonomy, Data::$post_type );
		Data::get_folders( Data::$taxonomy, Data::$post_type );

		wp_send_json_success( [
			'postType' => Data::$post_type,
			'taxonomy' => Data::$taxonomy,
			'openId'   => Data::$open_id,
			'folders'  => Data::$folders,
			'colors'   => get_option( HAPPYFILES_FOLDER_COLORS, [] ),
		] );
	}

	/**
	 * Download selected HappyFiles folder + subfolder & files in one ZIP file
	 * 
	 * @since 1.8
	 */
	public function download_folder() {
		Ajax::verify_nonce();

		// Return: Error if ZipArchive PHP extension doesn't exist
		if ( ! class_exists( '\ZipArchive' ) ) {
			wp_send_json_error( [ 'error' => 'Error: ZipArchive PHP extension does not exist.' ] );
		}

		$taxonomy = HAPPYFILES_TAXONOMY;
		$folder_id = $_POST['folderId'];
		$folder_obj = get_term_by( 'ID', $folder_id, $taxonomy );

		// Get temp path (and create directory if it doesn't exist)
		$wp_upload_dir = wp_upload_dir();
		$temp_path = trailingslashit( $wp_upload_dir['basedir'] ) . HAPPYFILES_TEMP_DIR;
		wp_mkdir_p( $temp_path );

		// STEP: Create ZIP file
		$timestamp    = time();
		$zip_filename = "{$folder_obj->name}-{$timestamp}.zip";
		$zip_path     = trailingslashit( $temp_path ) . $zip_filename;

		$zip = new \ZipArchive();

		$zip->open( $zip_path, \ZipArchive::CREATE );

		self::add_files_to_zip_file( $zip, $folder_id, '' );

		$zip->close();

		// TODO: Delete ZIP file afterward download (maybe after next HappyFiles app load?)
		
		wp_send_json_success( [
			'link' => trailingslashit( $wp_upload_dir['baseurl'] ) . trailingslashit( HAPPYFILES_TEMP_DIR ) . $zip_filename,
		] );
	}

	/**
	 * Get array of ZIP file names in temporary HappyFiles downloads directory
	 * 
	 * Run on every context menu trigger.
	 * 
	 * @return array
	 * 
	 * @since 1.8
	 */
	public function get_downloads() {
		Ajax::verify_nonce();
		
		$wp_upload_dir = wp_upload_dir();
		$temp_path = trailingslashit( $wp_upload_dir['basedir'] ) . HAPPYFILES_TEMP_DIR;
		$zip_file_names = glob( $temp_path . "/*.zip" );

		wp_send_json_success( [
			'downloads' => $zip_file_names,
		] );
	}

	/**
	 * Delete HappyFiles download ZIP file in temp uploads directory
	 * 
	 * @since 1.8
	 */
	public function delete_download() {
		Ajax::verify_nonce();

		// Get file basename (prevent traversing into different directory)
		$file_name = basename( $_POST['fileName'] );
		$wp_upload_dir = wp_upload_dir();
		$temp_path = trailingslashit( $wp_upload_dir['basedir'] ) . HAPPYFILES_TEMP_DIR;
		$unlinked = false;

		if ( file_exists( $temp_path . '/' . $file_name ) ) {
			$unlinked = unlink( $temp_path . '/' . $file_name );
		}

		wp_send_json_success( [
			'file_name' => $file_name,
			'unlinked'  => $unlinked,
		] );
	}

	/**
	 * Add files to ZIP file and resursively for all sub folders & files
	 * 
	 * @since 1.8
	 */
	public static function add_files_to_zip_file( $zip, $folder_id, $parent_directory ) {
		// Get attachment IDs of selected folder
		$attachment_ids = get_posts( [
			'post_type'      => 'attachment',
			'posts_per_page' => -1,
			'fields'         => 'ids',
			'tax_query'      => [
				[
					'taxonomy'         => HAPPYFILES_TAXONOMY,
					'terms'            => $folder_id,
					'field'            => 'term_id',
					'include_children' => false,
				],
			],
		] );

		// Add files to ZIP files
		foreach ( $attachment_ids as $attachment_id ) {
			$file = get_attached_file( $attachment_id );

			if ( $file ) {
				$zip->addFile( $file, trailingslashit( $parent_directory ) . basename( $file ) );
			}
		}

		// Add sub folders and their files & sub folers, etc. to to ZIP
		self::add_subfolders_to_zip_file( $zip, $folder_id, $parent_directory );
	}

	public static function add_subfolders_to_zip_file( $zip, $folder_id, $parent_directory ) {
		$sub_folders = get_terms( [
      'taxonomy'   => HAPPYFILES_TAXONOMY,
			'hide_empty' => false,
			'parent'     => $folder_id,
		] );

		foreach ( $sub_folders as $sub_folder ) {
			// Create sub folder directory
			$directory = trailingslashit( $parent_directory ) . $sub_folder->name;

			$zip->addEmptyDir( $directory );

			// Add files to sub folder
			self::add_files_to_zip_file( $zip, $sub_folder->term_id, $directory );
		}
	}

	/**
	 * Duplicate folders (and their subfolders)
	 * 
	 * Append ({new_term_id}) as new folder name on same level needs to be unique.
	 * 
	 * @since 1.8
	 */
	public function duplicate_folder() {
		Ajax::verify_nonce();

		$taxonomy = $_POST['taxonomy'];
		$post_type = $_POST['postType'];
		$folders = Data::get_folders( $taxonomy, $post_type );
		$folder = json_decode( stripslashes( $_POST['folder'] ), true );
		$original_folder_id = $folder['term_id'];
		
		$new_folder = false;
		$new_folder_position = 0;

		foreach ( $folders as $folder_obj ) {
			// Increase folder position (custom taxonomy term with same 'parent')
			if ( $folder_obj->parent === $folder['parent'] && $original_folder_id > 0 ) {
				$new_folder_position += 1;

				// Update position of folder after newly created duplicate
				if ( $new_folder ) {
					update_term_meta( $folder_obj->term_id, HAPPYFILES_POSITION, $new_folder_position );
				}
			}

			// Create duplicate of folder with same 'term_id'
			if ( $folder_obj->term_id === $original_folder_id ) {
				$term = get_term_by( 'ID', $folder_obj->term_id, $taxonomy );
				
				// Append last 'term_id' + 1 to folder name (as 'name' of term with same 'parent' must be unique)
				$folder_name  = $term ? $term->name : $folder_obj->name;

				// Append suffix as folder anem on same level needs to be unique
				$folder_name .= ' [' . esc_html__( 'Duplicate', 'happyfiles' ) . ']';
				
				$new_folder = wp_insert_term( 
					$folder_name, 
					$taxonomy, 
					['parent' => $folder_obj->parent] 
				);

				if ( $new_folder && ! is_wp_error( $new_folder ) ) {
					// Update term name (now that we got the new term ID)
					// wp_update_term( $new_folder['term_id'], $taxonomy, ['name' => $folder_name . " ({$new_folder['term_id']})"] );

					add_term_meta( $new_folder['term_id'], HAPPYFILES_POSITION, $new_folder_position );
				}
			}
		}

		if ( $new_folder ) {
			self::duplicate_subfolders( $taxonomy, $folders, $original_folder_id, $new_folder['term_id'] );
		}

		// Get latest data of all folders
		$folders = Data::get_folders( $taxonomy, $post_type );

		wp_send_json_success( [
			'new_folder' => $new_folder,
			'folder'     => $folder,
			'folders'    => $folders,
		] );
	}

	// STEP: Recursively duplicate subfolders
	public static function duplicate_subfolders( $taxonomy, $folders, $original_folder_id, $new_folder_id ) {
		$sub_folders = array_filter( $folders, function( $folder_obj ) use ( $original_folder_id ) {
			return $folder_obj->parent == $original_folder_id;
		});

		// Order sub folders by 'position'
		usort( $sub_folders, function( $a, $b ) {
			return $a->position > $b->position ? 1 : 0;
		} );

		foreach ( $sub_folders as $index => $sub_folder ) {
			$term = get_term_by( 'ID', $sub_folder->term_id, $taxonomy );

			// Duplicate subfolder
			$new_subfolder = wp_insert_term( 
				$term->name, 
				$taxonomy, 
				['parent' => $new_folder_id] 
			);

			if ( $new_subfolder ) {
				// Set new term position
				$updated = update_term_meta( $new_subfolder['term_id'], HAPPYFILES_POSITION, $index );

				self::duplicate_subfolders( $taxonomy, $folders, $sub_folder->term_id, $new_subfolder['term_id'] );
			}
		}
	}

	/**
	 * Save color in db
	 * 
	 * @since 1.8
	 */
	public function save_color() {
		self::verify_nonce();

		$colors = get_option( HAPPYFILES_FOLDER_COLORS, [] );
		$id = $_POST['id'];
		$value = $_POST['value'];

		// Save new value for existing color
		if ( $id ) {
			foreach ( $colors as $index => $color ) {
				if ( ! empty( $color['id'] ) && $color['id'] === $id ) {
					$colors[$index]['value'] = $value;
				}
			}
		}

		// Save new color (generate unique 6-character color ID)
		else {
			$id = substr( sha1( md5( uniqid( rand(), true ) ) ), 0, 6 );

			$colors[] = [
				'value' => $value,
				'id'    => $id,
			];
		}

		update_option( HAPPYFILES_FOLDER_COLORS, $colors );

		wp_send_json_success( [
			'post'   => $_POST,
			'colors' => $colors,
		] );
	}

	/**
	 * Delete color in db
	 * 
	 * @since 1.8
	 */
	public function delete_color() {
		self::verify_nonce();

		$colors = get_option( HAPPYFILES_FOLDER_COLORS, [] );
		$color_id = $_POST['colorId'];

		foreach ( $colors as $index => $color ) {
			if ( ! empty( $color['id'] ) && $color['id'] === $color_id ) {
				unset( $colors[$index] );
				$colors = array_values( $colors );
			}
		}

		update_option( HAPPYFILES_FOLDER_COLORS, $colors );

		wp_send_json_success( [
			'post'   => $_POST,
			'colors' => $colors,
		] );
	}

	/**
	 * Set folder color
	 * 
	 * @since 1.8
	 */
	public function set_folder_color() {
		self::verify_nonce();

		$folder_id = ! empty( $_POST['folderId'] ) ? intval( $_POST['folderId'] ) : 0;
		$value = ! empty( $_POST['value'] ) ? $_POST['value'] : '';

		// Update folder color in term meta
		$updated = update_term_meta( $folder_id, 'happyfiles_folder_color', $value );

		wp_send_json_success( [
			'post'      => $_POST, 
			'folder_id' => $folder_id, 
			'value'     => $value, 
			'updated'   => $updated,
		] );
	}

	/**
	 * Clear folder color
	 * 
	 * @since 1.8
	 */
	public function clear_folder_color() {
		self::verify_nonce();

		$term_id = $_POST['folderId'];
		$cleared = delete_term_meta( $term_id, 'happyfiles_folder_color' );

		wp_send_json_success( [
			'cleared' => $cleared,
			'post'    => $_POST,
		] );
	}

	/**
   * Create HappyFiles folders (custom taxonomy terms)
   *
   * @return array
   */
	public function create_folders() {
		self::verify_nonce();

		$taxonomy = $_POST['taxonomy'];
		$post_type = $_POST['postType'];
		$folder_names = ! empty( $_POST['folderNames'] ) ? $_POST['folderNames'] : [];
		$parent_id = ! empty( $_POST['parentId'] ) ? intval( $_POST['parentId'] ) : 0;

		// Check if parent term exists
		if ( $parent_id ) {
			$parent_id = term_exists( $parent_id, $taxonomy ) ? $parent_id : 0;
		}

		foreach ( $folder_names as $folder_name ) {
			$new_folder = wp_insert_term( 
				esc_attr( trim( $folder_name ) ), 
				$taxonomy, 
				['parent' => $parent_id] 
			);

			if ( is_wp_error( $new_folder ) ) {
				wp_send_json_error( ['error' => $new_folder->get_error_message() ] );
			}
		}

		$folders = Data::get_folders( $taxonomy, $post_type );

		// Return new folders to add them to toolbar filter dropdown (grid view)
		$new_folders = array_slice( array_reverse( $folders ), 0, count( $folder_names ) );

    wp_send_json_success( [
			'taxonomy'     => $taxonomy,
      'folders'      => $folders,
			'newFolders'   => $new_folders,
			'parentId '    => $parent_id,
    ] );
  }

	/**
   * Move HappyFiles folder (via DnD)
   *
   * @return array
   */
	public function move_item_ids() {
		self::verify_nonce();

		$taxonomy = $_POST['taxonomy'];
		$post_type = $_POST['postType'];
		$folder_id = ! empty( $_POST['folderId'] ) ? intval( $_POST['folderId'] ) : 0;
		$item_ids = ! empty( $_POST['itemIds'] ) ? $_POST['itemIds'] : [];
		$open_id = ! empty( $_POST['openId'] ) ? intval( $_POST['openId'] ) : 0;

		foreach ( $item_ids as $item_id ) {
			$folder_ids = wp_get_object_terms( $item_id, $taxonomy, ['fields' => 'ids'] );

			// Multiple folders per item
			if ( Settings::$multiple_folders ) {
				// Add to folder
				if ( $folder_id > 0 ) {
					$folder_ids[] = $folder_id;
				}

				// Remove from folder
				else {
					// Remove from all folders
					if ( Settings::$remove_from_all_folders ) {
						$folder_ids = [];
					} 
					
					// Remove from open folder
					else {
						$index = array_search( $open_id, $folder_ids );

						if ( is_int( $index ) ) {
							unset( $folder_ids[$index] );
						}
					}
				}
			} 
			
			// One folder per item (default)
			else {
				$folder_ids = $folder_id > 0 ? [$folder_id] : [];
			}

			// Delete terms
      if ( ! count( $folder_ids ) ) {
        wp_delete_object_term_relationships( $item_id, $taxonomy );
      }

			$reponse = wp_set_object_terms( $item_id, $folder_ids, $taxonomy, false );	

			if ( is_wp_error( $reponse ) ) {
        wp_send_json_error( ['error' => $reponse->get_error_message() ] );
      }
		}

		$folders = Data::get_folders( $taxonomy, $post_type );

		// Update term count
    foreach ( $folders as $folder ) {
      wp_update_term_count_now( [$folder->term_id], $taxonomy );
		}

    wp_send_json_success( [
			'taxonomy' => $taxonomy,
			'itemIds'  => $item_ids,
      'folders'  => $folders,
    ] );
	}

	/** 
	 * Move folder
	 * 
	 * - Update parent
	 * - Update folder positions (of all sibling folders)
	 */
	public function move_folder() {
		Ajax::verify_nonce();

		$post_type = ! empty( $_POST['postType'] ) ? $_POST['postType'] : false;
		$taxonomy = ! empty( $_POST['taxonomy'] ) ? $_POST['taxonomy'] : false;
		$parent_id = ! empty( $_POST['parentId'] ) ? $_POST['parentId'] : 0;
		$folder_ids = isset( $_POST['folderIds'] ) ? $_POST['folderIds'] : [];

		foreach ( $folder_ids as $index => $folder_id ) {
			$parent_set = wp_update_term( $folder_id, $taxonomy, ['parent' => $parent_id] );
			$position_updated = update_term_meta( $folder_id, HAPPYFILES_POSITION, $index );

			if ( is_wp_error( $position_updated ) ) {
				wp_send_json_error( ['error' => $position_updated->term_meta_updated() ] );
			}
		}

		wp_send_json_success( [
			'folders'    => Data::get_folders( $taxonomy, $post_type ),
			'parent_id'  => $parent_id,
			'folder_ids' => $folder_ids,
			'post_type'  => $post_type,
			'taxonomy'   => $taxonomy,
		] );
	}

	/**
	 * Export HappyFiles folders by passed 'postTypes'
	 * 
	 * @since 1.7
	 */
	public function export_folders() {
		Ajax::verify_nonce();

		$post_types = $_POST['postTypes'];
		$folders = [];

		foreach ( $post_types as $post_type ) {
			$taxonomy = Data::get_taxonomy( $post_type );
			$folders = array_merge( $folders, Data::get_folders( $taxonomy, $post_type ) );
		}
		
		wp_send_json_success( $folders );
	}

	/**
	 * Save open folder ID as user metadata
	 * 
	 * Key:   Taxonomy slug
	 * Value: Taxonomy term ID
	 */
	public function save_open_id() {
		$user_id = get_current_user_id();

		$user_open_ids = get_user_meta( $user_id, 'happyfiles_open_ids', true );

		if ( ! $user_open_ids ) {
			$user_open_ids = [];
		}

		$taxonomy = ! empty( $_POST['taxonomy'] ) ? $_POST['taxonomy'] : false;
		$folder_id = ! empty( $_POST['folderId'] ) ? intval( $_POST['folderId'] ) : false;

		if ( ! $taxonomy ) {
			wp_send_json_error( ['message' => 'Error: No taxonomy provided (save_open_id)'] );
		}

		if ( ! $folder_id ) {
			wp_send_json_error( ['message' => 'Error: No folder_id provided (save_open_id)'] );
		}

		$user_open_ids[$taxonomy] = $folder_id;

		$updated = update_user_meta( $user_id, 'happyfiles_open_ids', $user_open_ids );

		wp_send_json_success( [
			'update_user_meta' => $updated,
			'user_open_ids'    => $user_open_ids,
		] );
	}

	/**
   * Set folder for uploaded attachment
   *
   * @see Mixins.js: 'BeforeUpload'
   */
  public function add_attachment( $post_id ) {
    $folder_id = isset( $_REQUEST['hfOpenId'] ) ? intval( Helpers::sanitize_data( $_REQUEST['hfOpenId'] ) ) : 0;

    if ( is_numeric( $folder_id ) && $folder_id > 0 ) {
      wp_set_object_terms( $post_id, $folder_id, Data::$taxonomy, false );
    }
  }

	/**
	 * Quick inspect item: Get HappyFiles folders for passed post ID
	 */
	public function quick_inspect() {
		$taxonomy = ! empty( $_POST['taxonomy'] ) ? $_POST['taxonomy'] : HAPPYFILES_TAXONOMY; 
		$post_id = ! empty( $_POST['id'] ) ? intval( $_POST['id'] ) : 0;
		$folders = wp_get_object_terms( $post_id, $taxonomy );

		wp_send_json_success( [
			'taxonomy' => $taxonomy,
			'folders'  => $folders,
		] );
	}

	/**
	 * Admin: Dismiss folder import notification
	 */
	public function hide_import_folders_notification() {
		$updated = update_option( 'happyfiles_hide_import_folders_notification', true );
	
		wp_send_json_success( ['updated' => $updated] );
	}
}