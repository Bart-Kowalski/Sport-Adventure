<?php

namespace BricksExtras;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists("\Bricks\Integrations\Dynamic_Data\Providers\Base") ) {
	return;
}

use BricksExtras\Helpers;

class Provider_Extras extends \Bricks\Integrations\Dynamic_Data\Providers\Base {

	public function register_tags() {
		$tags = $this->get_tags_config();

		foreach ( $tags as $key => $tag ) {
			
			$this->tags[ $key ] = [
				'name'     => '{' . $key . '}',
				'label'    => $tag['label'],
				'group'    => $tag['group'],
				'provider' => $this->name
			];

			if ( ! empty( $tag['render'] ) ) {
				$this->tags[ $key ]['render'] = $tag['render'];
			}
		}
	}

	public function get_tags_config() {

		$extras_group = esc_html__( 'Extras', 'bricks' );

		$tags = [

			'x_post_reading_time' => [
				'label' => esc_html__( 'Post reading time [extras]', 'bricks' ),
				'group' => esc_html__( 'post', 'bricks' )
			],
			'x_post_terms_list' => [
				'label' => esc_html__( 'Post terms list [extras]', 'bricks' ),
				'group' => esc_html__( 'post', 'bricks' )
			],
			'x_url_parameter' => [
				'label' => esc_html__( 'URL parameter [extras]', 'bricks' ),
				'group' => $extras_group
			],
			'x_est_year_current_year' => [
				'label' => esc_html__( 'Est. year - current year [extras]', 'bricks' ),
				'group' => $extras_group
			],
			'x_loop_index' => [
				'label' => esc_html__( 'Loop index [extras]', 'bricks' ),
				'group' => $extras_group
			],
			'x_parent_loop_index' => [
				'label' => esc_html__( 'Parent loop index [extras]', 'bricks' ),
				'group' => $extras_group
			],

			/* wp menu */
			'x_menu_item_label' => [
				'label' => esc_html__( 'Menu Item Label [extras]', 'bricks' ),
				'group' => $extras_group
			],
			'x_menu_item_url' => [
				'label' => esc_html__( 'Menu Item URL [extras]', 'bricks' ),
				'group' => $extras_group
			],
			'x_menu_item_description' => [		
				'label' => esc_html__( 'Menu Item Description [extras]', 'bricks' ),
				'group' => $extras_group
			],
			'x_menu_item_classes' => [		
				'label' => esc_html__( 'Menu Item Classes [extras]', 'bricks' ),
				'group' => $extras_group
			],

			/* favorites */
			'x_favorite_ids' => [		
				'label' => esc_html__( 'Favorite IDs [extras]', 'bricks' ),
				'group' => $extras_group
			],
			'x_favorite_count' => [		
				'label' => esc_html__( 'Favorite Count (Display) [extras]', 'bricks' ),
				'group' => $extras_group
			],
			'x_favorite_count_number' => [		
				'label' => esc_html__( 'Favorite Count (Number) [extras]', 'bricks' ),
				'group' => $extras_group
			],

			/* attachments */
			'x_attachment_alt_text' => [		
				'label' => esc_html__( 'Attachment Alt Text [extras]', 'bricks' ),
				'group' => $extras_group
			],
			'x_attachment_title' => [		
				'label' => esc_html__( 'Attachment Title [extras]', 'bricks' ),
				'group' => $extras_group
			],	
			'x_attachment_url' => [		
				'label' => esc_html__( 'Attachment File URL [extras]', 'bricks' ),
				'group' => $extras_group
			],
			'x_attachment_description' => [		
				'label' => esc_html__( 'Attachment Description [extras]', 'bricks' ),
				'group' => $extras_group
			],
			'x_attachment_caption' => [		
				'label' => esc_html__( 'Attachment Caption [extras]', 'bricks' ),
				'group' => $extras_group
			],

		];

		return $tags;
	}

	/**
	 * Main function to render the tag value for WordPress provider
	 *
	 * @param [type] $tag
	 * @param [type] $post
	 * @param [type] $args
	 * @param [type] $context
	 * @return void
	 */
	public function get_tag_value( $tag, $post, $args, $context ) {
		
		$post_id = isset( $post->ID ) ? $post->ID : '';

		// STEP: Check for filter args
		$filters = $this->get_filters_from_args( $args );

		// STEP: Get the value
		$value = '';

		$render = isset( $this->tags[ $tag ]['render'] ) ? $this->tags[ $tag ]['render'] : $tag;

		switch ( $render ) {

			/* est reading time */
			case 'x_post_reading_time':
				if ( ! empty( $filters['meta_key'] ) ) {
					
					$meta_keys = explode( ",", $filters['meta_key'] ); 
					
					$text_after_singular = isset( $meta_keys[0] ) ? $meta_keys[0] : 'minute';
					$text_after_plural = isset( $meta_keys[1] ) ? $meta_keys[1] : 'minutes';
					$wpm = isset( $meta_keys[2] ) ? $meta_keys[2] : 225;

				} else {
					$text_after_singular = 'minute';
					$text_after_plural = 'minutes';
					$wpm = 225;
				}	

				if ( null != $post ) {
					
					$content = get_post_field( 'post_content', $post->ID );

					$wordArray = preg_split('/\s+/', strip_tags( $content ) );

					$word_count = is_array( $wordArray ) ? count( $wordArray ) : 0;
					
					$readingtime = ceil( $word_count / $wpm );
					if ($readingtime == 1) {
						$timer = " ". $text_after_singular;
					} else {
						$timer = " ". $text_after_plural;
					}
					
					$value = $readingtime . $timer;

				}
				
			break;


			/* post terms list */
			case 'x_post_terms_list':

				if ( ! empty( $filters['meta_key'] ) ) {
					$taxonomy = isset( $filters['meta_key'] ) ? sanitize_text_field( $filters['meta_key'] ) : '';
				} else {
					$taxonomy =  'category';
				}

				$value = is_string( get_the_term_list( $post->ID, $taxonomy, '', ', ' ) ) ? strip_tags( get_the_term_list( $post->ID, $taxonomy, '', ', ' ) ) : '';
				
			break;	


			/* URL Parameter */
			case 'x_url_parameter':

				if ( ! empty( $filters['meta_key'] ) ) {
					$variable = isset( $filters['meta_key'] ) ? sanitize_text_field( $filters['meta_key'] ) : '';
				} else {
					$variable =  's';
				}

				$value = isset( $_GET[ $variable ] ) ? sanitize_text_field( $_GET[ $variable ] ) : null;

				
			break;	

			/* URL Parameter */
			case 'x_est_year_current_year':

				if ( ! empty( $filters['num_words'] ) ) {
					$est_year = isset( $filters['num_words'] ) ? $filters['num_words'] : false;
					if (false !== $est_year) {
						$value = $filters['num_words'] == date( 'Y' ) ? date( 'Y' ) : sanitize_text_field( $filters['num_words'] ) . ' - ' . date( 'Y' );
					}
				} else {
					$value = date( 'Y' );
				}

				
			break;	

			/* Loop index */
			case 'x_loop_index':

				if ( method_exists( '\Bricks\Query', 'get_query_object' ) ) {

					$query_object = \Bricks\Query::get_query_object();

					if( $query_object ) {

						$offset = isset( $filters['num_words'] ) ? sanitize_text_field( $filters['num_words'] ) : 0;
						$value = intval( $query_object::get_loop_index() ) + $offset;
					}

				}
				
			break;	

			/* Parent Loop index */
			case 'x_parent_loop_index':

				if ( method_exists( '\Bricks\Query', 'get_query_for_element_id' ) ) {

					$level = isset( $filters['num_words'] ) ? sanitize_text_field( $filters['num_words'] ) : 1;

					$value = \BricksExtras\Helpers::get_bricks_looping_parent_query_id_by_level($level) ? \Bricks\Query::get_query_for_element_id( \Bricks\Query::get_query_element_id( \BricksExtras\Helpers::get_bricks_looping_parent_query_id_by_level($level) ) )->loop_index : '';

				}
				
			break;	

			/* wp menu */
			case 'x_menu_item_label':

				if ( method_exists( '\Bricks\Query', 'is_any_looping' ) ) {

					$looping_query_id = \Bricks\Query::is_any_looping();

					if ( $looping_query_id ) {

						$loopObject = \Bricks\Query::get_loop_object( $looping_query_id ) ;

						if ( $loopObject && is_array( $loopObject ) ) {
							$value = esc_html( $loopObject['title'] );
						}

					}

				}
				
			break;	

			case 'x_menu_item_url':

				if ( method_exists( '\Bricks\Query', 'is_any_looping' ) ) {

					$looping_query_id = \Bricks\Query::is_any_looping();

					if ( $looping_query_id ) {

						$loopObject = \Bricks\Query::get_loop_object( $looping_query_id ) ;

						if ( $loopObject && is_array( $loopObject ) ) {
							$value = esc_url( $loopObject['url'] );
						}

					}

				}
				
			break;	

			case 'x_menu_item_description':

				if ( method_exists( '\Bricks\Query', 'is_any_looping' ) ) {

					$looping_query_id = \Bricks\Query::is_any_looping();

					if ( $looping_query_id ) {

						$loopObject = \Bricks\Query::get_loop_object( $looping_query_id ) ;

						if ( $loopObject && is_array( $loopObject ) ) {
							$value = esc_html( $loopObject['description'] );
						}

					}

				}
				
			break;

			case 'x_menu_item_classes':

				if ( method_exists( '\Bricks\Query', 'is_any_looping' ) ) {

					$looping_query_id = \Bricks\Query::is_any_looping();

					if ( $looping_query_id ) {

						$loopObject = \Bricks\Query::get_loop_object( $looping_query_id ) ;

						if ( $loopObject && is_array( $loopObject ) && is_array( $loopObject['classes'] ) ) {
							$as_string = implode(' ', $loopObject['classes'] );
							$value = '' !== $as_string ? esc_attr( $as_string ) : '';
						}

					}

				}
				
			break;

			case 'x_favorite_count':

				$list = isset( $filters['meta_key'] ) ? sanitize_text_field( $filters['meta_key'] ) : 'post';

				$array = Helpers::get_favorite_ids_array( $list );

				if ( empty( $array ) ) {
					$count = '';
				} else {
					$count = count( $array );
				}

				$value = '<span data-x-favorite-count="' . esc_attr( $list ) . '">' . intval( $count ) . '</span>';
				
				
			break;

			case 'x_favorite_count_number':

				$list = isset( $filters['meta_key'] ) ? sanitize_text_field( $filters['meta_key'] ) : 'post';

				$array = Helpers::get_favorite_ids_array( $list );

				if ( empty( $array ) ) {
					$count = 0;
				} else {
					$count = count( $array );
				}

				$value = intval( $count );
				
				
			break;

			case 'x_favorite_ids':

				$list = isset( $filters['meta_key'] ) ? sanitize_text_field( $filters['meta_key'] ) : 'post';

				$array = Helpers::get_favorite_ids_array( $list );

				if ( empty( $array ) ) {
					$value = '[0]';
				} else {
					$string = implode(', ', $array);
					$value = esc_html( '[' . $string . ']' );
				}
				
				
			break;


			case 'x_attachment_alt_text':

				$alt = trim( strip_tags( get_post_meta( \Bricks\Query::get_loop_object_id(), '_wp_attachment_image_alt', true ) ) );
				$value = esc_attr( $alt );

			break;

			case 'x_attachment_title':

				$title = get_the_title( \Bricks\Query::get_loop_object_id() );
				$value = wp_kses_post( $title );

			break;

			case 'x_attachment_url':

				$url = wp_get_attachment_url( get_the_ID() );
				$value = $url ? esc_url( $url ) : '';

			break;

			case 'x_attachment_description':

				$description = get_post( \Bricks\Query::get_loop_object_id() )->post_content;
				$value = wp_kses_post( $description );

			break;

			case 'x_attachment_caption':

				$caption = wp_get_attachment_caption( \Bricks\Query::get_loop_object_id() );
				$value = wp_kses_post( $caption );

			break;


			
		}

		// STEP: Apply context (text, link, image, media)
		$value = $this->format_value_for_context( $value, $tag, $post_id, $filters, $context );

		return $value;
	}

}
