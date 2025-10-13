<?php
namespace HappyFiles;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Setup {
  public function __construct() {
		add_action( 'init', [$this, 'register_taxonomy'] );
		add_action( 'plugins_loaded', [$this, 'load_plugin_textdomain'] );
		
		add_action( 'admin_enqueue_scripts', [$this, 'has_media_modal'] );
		add_action( 'admin_enqueue_scripts', [$this, 'enqueue_scripts'] );
		add_action( 'wp_enqueue_scripts', [$this, 'enqueue_scripts'] );

		// Admin: Upload new media screen
		add_action( 'admin_enqueue_scripts', [$this, 'admin_new_media_screen'] );
		add_filter( 'pre-upload-ui', [$this, 'upload_ui_media_new'] );

		// Elementor: Enqueue HappyFiles assets
		add_action( 'elementor/editor/after_enqueue_scripts', [$this, 'enqueue_scripts'] );

		// Beaver builder (v2.7+ uses an iframe)
		add_action( 'fl_builder_ui_enqueue_scripts', [ $this, 'just_enqueue' ] );

		// BeTheme (v27+)
		add_action( 'mfn_header_enqueue', [ $this, 'just_enqueue' ] );

		// Cornerstone theme (v6+)
		add_action( 'cornerstone_before_wp_editor', [ $this, 'just_enqueue' ] );

		// Bricks: Disable lazy load in Gutenberg editor for HappyFiles gallery
		add_filter( 'wp_get_attachment_image_attributes', [ $this, 'set_image_attributes' ], 11, 3 );
	}

	/**
	 * Load HappyFiles without performing Data::$load_plugin check
	 * 
	 * Run on other plugin/theme actions like Beaver Builder, Cornerstone, etc.
	 * 
	 * @since 1.8.3
	 */
	public function just_enqueue() {
		Data::$load_plugin = true;
		
		$this->enqueue_scripts();
	}

	/**
	 * Register HappyFiles folders (custom taxonomy term; for post type: attachment)
	 */
	public function register_taxonomy() {
    register_taxonomy(
      HAPPYFILES_TAXONOMY,
      ['attachment'],
      [
				'labels' => [
					'name'               => esc_html__( 'Folder', 'happyfiles' ),
					'singular_name'      => esc_html__( 'Folder', 'happyfiles' ),
					'add_new_item'       => esc_html__( 'Add new folder', 'happyfiles' ),
					'edit_item'          => esc_html__( 'Edit folder', 'happyfiles' ),
					'new_item'           => esc_html__( 'Add new folder', 'happyfiles' ),
					'search_items'       => esc_html__( 'Search folder', 'happyfiles' ),
					'not_found'          => esc_html__( 'Folder not found', 'happyfiles' ),
					'not_found_in_trash' => esc_html__( 'Folder not found in trash', 'happyfiles' ),
				],
        'public'             => false,
        'publicly_queryable' => false,
        'hierarchical'       => true,
        'show_ui'            => true,
        'show_in_menu'       => false,
        'show_in_nav_menus'  => false,
				'show_in_quick_edit' => false,
				'show_in_rest'       => true, // wp.data.select('core').getEntityRecords('taxonomy', 'happyfiles_category', {per_page: -1}) // max 100 items for REST API call
        'show_admin_column'  => false,
        'rewrite'            => false,
        'update_count_callback' => '_update_generic_term_count', // Update term count for attachments
      ]
    );
  }

	/**
	 * Load plugin translation
	 * 
	 * Example: /languages/happyfiles-de_DE.mo
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain( 'happyfiles', false, dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/' );
	}

	/**
	 * Load HappyFiles on any admin page that has 'media-modals' script enqueued
	 * 
	 * Skip post type & plugins admin screen in this check.
	 */
	public function has_media_modal() {
		// Return: No access
		if ( User::$folder_access === 'none' ) {
			return;
		}

		// Pro setting: HappyFiles disabled for files
		$disable_for_files = get_option( 'happyfiles_disable_for_files', false );

		if ( $disable_for_files && ( ! Data::$post_type || Data::$post_type === 'attachment' ) ) {
			Data::$load_plugin = false;

			return;
		}

		global $pagenow;

		/**
		 * WooCommerce 8.2+ HPOS orders screen (https://woo.com/document/high-performance-order-storage/)
		 * 
		 * Return to avoid loading HappyFiles on the Woo order screen by default
		 * As triggered by the 'media-editor' script below.
		 */
		if ( ! empty( $_GET['page'] ) && $_GET['page'] === 'wc-orders' ) {
			return;
		}

		// Media modal script is 'registered' (default 'enqueued' is not enough on custom ACF pages, etc.)
		if ( wp_script_is( 'media-editor', 'registered' ) && $pagenow !== 'edit.php' && $pagenow !== 'plugins.php' ) {
			Data::$load_plugin = true;
		}

		// WordPress Customizer (e.g. Set favicon)
		if ( $pagenow === 'customize.php' ) {
			Data::$load_plugin = true;
		}
	}

	/**
	 * Populate data when HappyFiles sidebar is mounted
	 * 
	 * To avoid running Data:.get_folders() when media modal has not been opened.
	 */
	public function enqueue_scripts() {
		if ( ! Data::$load_plugin ) {
			return;
		}

		global $pagenow;

		// Ensure window.wp.media exists for page builders
		wp_enqueue_media();

		wp_enqueue_style( 'happyfiles', HAPPYFILES_ASSETS_URL . '/css/happyfiles.min.css', [], filemtime( HAPPYFILES_ASSETS_PATH .'/css/happyfiles.min.css' ) );
		wp_enqueue_script( 'happyfiles', HAPPYFILES_ASSETS_URL . '/js/happyfiles.min.js', ['jquery'], filemtime( HAPPYFILES_ASSETS_PATH .'/js/happyfiles.min.js' ), true );
		
		$happyfiles_data = [
			'nonce'             => wp_create_nonce( 'happyfiles-nonce' ),
			'pagenow'           => $pagenow,
			'debug'             => isset( $_GET['debug'] ),
			'postType'          => Data::$post_type,
			'taxonomy'          => Data::$taxonomy,
			'folders'           => Data::$folders,
			'colors'            => Data::$colors,
			'openId'            => Data::$open_id,
			'filesizes'         => Settings::$filesizes,
			'folderNameInTitle' => Settings::$folder_name_in_title,
			'access'            => User::$folder_access,
			'svgSupport'        => User::$svg_support,
			'ajaxUrl'           => admin_url( 'admin-ajax.php' ),
			'assetsUrl'         => HAPPYFILES_ASSETS_URL,
			'i18n'              => [
				'infoCreateFolder'   => esc_html__( 'Click the "+" button above to create your first folder. Then start dragging items into it. To remove an item from a folder drag it into the "Uncategorized" folder.', 'happyfiles' ),
				'infoNoSearchResults'=> esc_html__( 'No folders found that match your search criteria. Please try another phrase or clear your search. Search is case-sensitive.', 'happyfiles' ),
				'attachmentIds'      => esc_html__( 'Attachment IDs', 'happyfiles' ),
				'items'              => esc_html__( 'Items', 'happyfiles' ),
				'folder'             => esc_html__( 'Folder', 'happyfiles' ),
				'folders'            => esc_html__( 'Folders', 'happyfiles' ),
				'select'             => esc_html__( 'Select', 'happyfiles' ),
				'create'             => esc_html__( 'Create', 'happyfiles' ),
				'cancel'             => esc_html__( 'Cancel', 'happyfiles' ),
				'rename'             => esc_html__( 'Rename', 'happyfiles' ),
				'delete'             => esc_html__( 'Delete', 'happyfiles' ),
				'move'               => esc_html__( 'Move', 'happyfiles' ),
				'search'             => esc_html__( 'Search', 'happyfiles' ),
				'uploaded'           => esc_html__( 'Uploaded', 'happyfiles' ),
				'sort'               => esc_html__( 'Sort', 'happyfiles' ),
				'expand'             => esc_html__( 'Expand', 'happyfiles' ),
				'collapse'           => esc_html__( 'Collapse', 'happyfiles' ),
				'details'            => esc_html__( 'Details', 'happyfiles' ),
				'duplicate'          => esc_html__( 'Duplicate', 'happyfiles' ),
				'clear'              => esc_html__( 'Clear', 'happyfiles' ),
				'subFolders'         => esc_html__( 'Subfolders', 'happyfiles' ),
				'foldersHelp'        => esc_html__( 'Hold down CTRL/CMD to select multiple folders.', 'happyfiles' ),

				'deleteFolders'      => esc_html__( 'Delete folders', 'happyfiles' ),
				'deleteSubfolders'   => esc_html__( 'Delete folders & subfolders', 'happyfiles' ),
				'deleteFoldersInfo'  => esc_html__( 'Please confirm how you\'d like to delete the HappyFiles folders listed below. All items inside those folders are safe & won\'t be deleted!', 'happyfiles' ),
				'foldersDeletedInfo' => esc_html__( 'The selected folders have been deleted.', 'happyfiles' ),
				'foldersDeletedInfo2'=> esc_html__( 'The selected folder no longer exists. We recommend selecting another folder.', 'happyfiles' ),

				'download'           => esc_html__( 'Download', 'happyfiles' ),

				// Folder colors
				'edit'               => esc_html__( 'Edit', 'happyfiles' ),
				'save'               => esc_html__( 'Save', 'happyfiles' ),
				'color'              => esc_html__( 'Color', 'happyfiles' ),

				// Shortcode generator
				'clickToCopy'        => esc_html__( 'Click to copy', 'happyfiles' ),
				'galleryShortcode'   => esc_html__( 'Gallery shortcode', 'happyfiles' ),
				'default'            => esc_html__( 'Default', 'happyfiles' ),
				'columns'            => esc_html__( 'Columns', 'happyfiles' ),
				'maxItems'           => esc_html__( 'Max items', 'happyfiles' ),
				'height'             => esc_html__( 'Height', 'happyfiles' ),
				'spacing'            => esc_html__( 'Spacing', 'happyfiles' ),
				'orderBy'            => esc_html__( 'Order by', 'happyfiles' ),
				'order'              => esc_html__( 'Order', 'happyfiles' ),
				'imageSize'          => esc_html__( 'Image size', 'happyfiles' ),
				'linkTo'             => esc_html__( 'Link to', 'happyfiles' ),
				'noCrop'             => esc_html__( 'No crop', 'happyfiles' ),
				'includeSubfolder'   => esc_html__( 'Include subfolder', 'happyfiles' ),
				'caption'            => esc_html__( 'Caption', 'happyfiles' ),
				'lightbox'           => esc_html__( 'Lightbox', 'happyfiles' ),
				'lightboxCaption'    => esc_html__( 'Lightbox', 'happyfiles' ) . ': ' . esc_html__( 'Caption', 'happyfiles' ),
				'lightboxFullscreen' => esc_html__( 'Lightbox', 'happyfiles' ) . ': ' . esc_html__( 'Fullscreen', 'happyfiles' ),
				'lightboxThumbnails' => esc_html__( 'Lightbox', 'happyfiles' ) . ': ' . esc_html__( 'Thumbnails', 'happyfiles' ),
				'lightboxZoom'       => esc_html__( 'Lightbox', 'happyfiles' ) . ': ' . esc_html__( 'Zoom', 'happyfiles' ),

				// Order by
				'date'     => esc_html__( 'Date', 'happyfiles' ),
				'author'   => esc_html__( 'Author', 'happyfiles' ),
				'name'     => esc_html__( 'Name', 'happyfiles' ),
				'title'    => esc_html__( 'Title', 'happyfiles' ),
				'type'     => esc_html__( 'Type', 'happyfiles' ),
				'modified' => esc_html__( 'Modified', 'happyfiles' ),
				'random'   => esc_html__( 'Random', 'happyfiles' ),
				'none'     => esc_html__( 'None', 'happyfiles' ),
				'filesize' => esc_html__( 'File size', 'happyfiles' ),

				// Order
				'descending' => esc_html__( 'Descending', 'happyfiles' ),
				'ascending' => esc_html__( 'Ascending', 'happyfiles' ),

				// Link to
				'attachment' => esc_html__( 'Attachment', 'happyfiles' ),
				'media'      => esc_html__( 'Media', 'happyfiles' ),
			],
		];

		// Filter HappyFiles data (filter plugins, etc.)
		$happyfiles_data = apply_filters( 'happyfiles/data', $happyfiles_data );
	
		wp_localize_script( 'happyfiles', 'HappyFiles', $happyfiles_data );
	}

	public function admin_new_media_screen() {
		global $pagenow;
		
		if ( $pagenow === 'media-new.php' ) {
			wp_enqueue_script( 'happyfiles-media-new', HAPPYFILES_ASSETS_URL . '/js/media-new.min.js', ['jquery'], filemtime( HAPPYFILES_ASSETS_PATH .'/js/media-new.min.js' ), true );
		}
	}

	/**
   * Add folder dropdown to media-new.php in WP admin
   */
  public function upload_ui_media_new() {
    if ( is_admin() && get_current_screen() && get_current_screen()->base === 'media' && in_array( User::$folder_access, ['upload', 'full'] ) ) {
      $tax_slug = Data::$taxonomy;
      $tax_obj = get_taxonomy( $tax_slug );

      echo '<div id="happyfiles-folder-upload-wrapper">';
      echo '<p>' . esc_html__( 'Optional: Assign a folder to your uploaded file(s):', 'happyfiles' ) . '</p>';

      echo '<p>';

      wp_dropdown_categories( [
        'id'               => 'happyfiles_category_add_new', // Has to be different from AJAX generated 'happyfiles_category' filter
        'show_option_none' => esc_html__( 'Uncategorized', 'happyfiles' ),
        'taxonomy'         => $tax_slug,
        'name'             => $tax_obj->name,
        'hierarchical'     => true,
				'hide_empty'       => false,
      ] );

      echo '</p>';
      echo '</div>';
    }
  }

	/**
	 * Bricks: Disable lazy load in Gutenberg editor for HappyFiles gallery
	 */
	function set_image_attributes( $attr, $attachment, $size ) {
		if ( ! empty( $_GET['attributes']['categories'] ) && ! empty( $attr['data-src'] ) ) {
			$attr['src'] = $attr['data-src'];
		}

		return $attr;
	}
}