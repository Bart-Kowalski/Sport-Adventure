<?php
namespace HappyFiles;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class HappyFiles_Gallery extends \Bricks\Element {
	public $category     = 'media';
  public $name         = 'happyfiles-gallery';
  public $icon         = 'ti-gallery';
  public $css_selector = '';
  public $scripts      = [];

	public function get_label() {
		return 'HappyFiles - ' . esc_html__( 'Gallery', 'happyfiles' );
	}

	public function set_controls() {
		$folder_options = [];

		if ( bricks_is_builder() ) {
			$folders = Helpers::get_folders_ordered( HAPPYFILES_TAXONOMY, 'attachment' );

			foreach ( $folders as $folder ) {
				if ( $folder->id > 0 ) {
					$folder_options[$folder->id] = $folder->name;
				}
			}
		}

		$this->controls['attachmentIds'] = [
			'label'          => esc_html__( 'Images', 'happyfiles' ),
			'tab'            => 'content',
			'type'           => 'image-gallery',
			'hasDynamicData' => false,
			'unsplash'       => false,
		];

		$this->controls['ids'] = [
			'tab'      => 'content',
			'label'    => esc_html__( 'Folders', 'happyfiles' ),
			'type'     => 'select',
			'options'  => $folder_options,
			'multiple' => true,
			'placeholder' => esc_html__( 'None', 'happyfiles' ),
		];

		$this->controls['columns'] = [
			'tab'         => 'content',
			'label'       => esc_html__( 'Columns', 'happyfiles' ),
			'type'        => 'number',
			'min'         => 1,
			'css'         => [
				// No crop
				[
					'selector' => 'ul',
					'property' => 'column-count',
				],
				// Crop
				[
					'selector' => 'ul',
					'property' => 'grid-template-columns',
					'value'    => 'repeat(%s, 1fr)',
				],
			],
			'placeholder' => 3,
			'rerender'    => true,
		];

		$this->controls['max'] = [
			'tab'         => 'content',
			'label'       => esc_html__( 'Max items', 'happyfiles' ),
			'type'        => 'number',
			'min'         => 1,
			'placeholder' => -1,
		];

		$this->controls['height'] = [
			'tab'         => 'content',
			'label'       => esc_html__( 'Height', 'happyfiles' ),
			'type'        => 'number',
			'units'       => true,
			'css'         => [
				[
					'selector' => 'li',
					'property' => 'height',
				],
			],
			'placeholder' => '300px',
		];

		$this->controls['spacing'] = [
			'tab'         => 'content',
			'label'       => esc_html__( 'Spacing', 'happyfiles' ),
			'type'        => 'number',
			'units'       => true,
			'css'         => [
				[
					'selector' => 'ul',
					'property' => 'gap',
				],
			],
			'placeholder' => '20px',
		];

		$this->controls['orderBy'] = [
			'tab'         => 'content',
			'label'       => esc_html__( 'Order by', 'happyfiles' ),
			'type'        => 'select',
			'options'     => [
				'none'      => __( 'None' ),
				'ID'        => __( 'ID' ),
				'author'    => __( 'Author' ),
				'title'     => __( 'Title' ),
				'name'      => __( 'Name' ),
				'type'      => __( 'Type' ),
				'date'      => __( 'Date' ),
				'modified'  => __( 'Modified' ),
				'parent'    => __( 'Parent' ),
				'rand'      => __( 'Random' ),
				'menu_order'=> __( 'Menu order' ),
			],
			'inline'      => true,
			'placeholder' => __( 'Date' ),
		];

		$this->controls['order'] = [
			'tab'         => 'content',
			'label'       => esc_html__( 'Order', 'happyfiles' ),
			'type'        => 'select',
			'options'     => [
				'ASC'  => __( 'Ascending' ),
				'DESC' => __( 'Descending' ),
			],
			'inline'      => true,
			'placeholder' => __( 'Descending' ),
		];

		$this->controls['imageSize'] = [
			'tab'         => 'content',
			'label'       => esc_html__( 'Image size', 'happyfiles' ),
			'type'        => 'select',
			'options'     => [
				'thumbnail' => esc_html__( 'Thumbnail', 'happyfiles' ),
				'medium'    => esc_html__( 'Medium', 'happyfiles' ),
				'large'     => esc_html__( 'Large', 'happyfiles' ),
				'full'      => esc_html__( 'Full size', 'happyfiles' ),
			],
			'inline'      => true,
			'placeholder' => esc_html__( 'Medium', 'happyfiles' ),
		];

		$this->controls['linkTo'] = [
			'tab'         => 'content',
			'label'       => esc_html__( 'Link to', 'happyfiles' ),
			'type'        => 'select',
			'options'     => [
				'attachment' => esc_html__( 'Attachment page', 'happyfiles' ),
				'media'      => esc_html__( 'Media file', 'happyfiles' ),
				'none'       => esc_html__( 'None', 'happyfiles' ),
			],
			'inline'      => true,
			'placeholder' => esc_html__( 'None', 'happyfiles' ),
		];

		$this->controls['noCrop'] = [
			'tab'   => 'content',
			'label' => esc_html__( 'No crop', 'happyfiles' ),
			'type'  => 'checkbox',
		];

		$this->controls['includeChildren'] = [
			'tab'   => 'content',
			'label' => esc_html__( 'Include subfolder', 'happyfiles' ),
			'type'  => 'checkbox',
		];

		$this->controls['caption'] = [
			'tab'   => 'content',
			'label' => esc_html__( 'Caption', 'happyfiles' ),
			'type'  => 'checkbox',
		];

		$this->controls['lightbox'] = [
			'tab'   => 'content',
			'label' => esc_html__( 'Lightbox', 'happyfiles' ),
			'type'  => 'checkbox',
		];

		$this->controls['lightboxCaption'] = [
			'tab'      => 'content',
			'label'    => esc_html__( 'Lightbox', 'happyfiles' ) . ': ' . esc_html__( 'Caption', 'happyfiles' ),
			'type'     => 'checkbox',
			'required' => ['lightbox', '!=', ''],
		];

		$this->controls['lightboxFullscreen'] = [
			'tab'      => 'content',
			'label'    => esc_html__( 'Lightbox', 'happyfiles' ) . ': ' . esc_html__( 'Fullscreen', 'happyfiles' ),
			'type'     => 'checkbox',
			'required' => ['lightbox', '!=', ''],
		];

		$this->controls['lightboxThumbnails'] = [
			'tab'      => 'content',
			'label'    => esc_html__( 'Lightbox', 'happyfiles' ) . ': ' . esc_html__( 'Thumbnails', 'happyfiles' ),
			'type'     => 'checkbox',
			'required' => ['lightbox', '!=', ''],
		];

		$this->controls['lightboxZoom'] = [
			'tab'      => 'content',
			'label'    => esc_html__( 'Lightbox', 'happyfiles' ) . ': ' . esc_html__( 'Zoom', 'happyfiles' ),
			'type'     => 'checkbox',
			'required' => ['lightbox', '!=', ''],
		];
	}

	public function render() {
		$settings = $this->settings;
		
		if ( empty( $settings['attachmentIds'] ) && empty( $settings['ids'] ) ) {
			return $this->render_element_placeholder( ['title' => esc_html__( 'No folder selected.', 'happyfiles' )] );
		}
			
		$attachments = ! empty( $settings['attachmentIds']['images'] ) ? $settings['attachmentIds']['images'] : false;

		// Example: [happyfiles_gallery attachmentIds="100,101,102"]
		if ( is_array( $attachments ) ) {
			$settings['attachmentIds'] = implode( ',',  wp_list_pluck( $attachments, 'id' ) );
		}
		
		// Remove setting
		else {
			unset( $settings['attachmentIds'] );
		}

		// Don't generate inline CSS (only needed when using shortcode directly)
		$settings['generateInlineCss'] = false;

		echo "<div {$this->render_attributes( '_root' )}>" . Shortcode::render_block( $settings ) . '</div>';
	}
}