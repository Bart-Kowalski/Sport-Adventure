<?php
/**
 * WP REST API Posts route
 *
 * @package   WP Grid Builder
 * @author    Loïc Blascos
 * @copyright 2019-2024 Loïc Blascos
 */

namespace WP_Grid_Builder\includes\Routes;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handle posts route
 *
 * @class WP_Grid_Builder\Includes\Routes\Posts
 * @since 2.0.0
 */
final class Posts extends Base {

	/**
	 * Register custom route
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function register_routes() {

		$this->register(
			'posts',
			[
				'methods'  => \WP_REST_Server::READABLE,
				'callback' => [ $this, 'get_posts' ],
				'args'     => [
					'include' => [
						'type'              => 'array',
						'default'           => [],
						'sanitize_callback' => 'wp_parse_id_list',
					],
					'search'  => [
						'type'              => 'string',
						'default'           => '',
						'sanitize_callback' => 'sanitize_text_field',
					],
					'lang'    => [
						'type'              => 'string',
						'default'           => '',
						'sanitize_callback' => 'sanitize_text_field',
					],
				],
			]
		);
	}

	/**
	 * Query posts
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_REST_Response
	 */
	public function get_posts( $request ) {

		$params = $request->get_params();

		return $this->ensure_response(
			$this->query(
				[
					'lang'                   => $params['lang'],
					'posts_per_page'         => count( $params['include'] ) ?: 20,
					'post_type'              => get_post_types( [ 'public' => true ] ),
					'post_status'            => 'any',
					'post__in'               => $params['include'],
					's'                      => $params['search'],
					'search_columns'         => [ 'post_title' ],
					'orderby'                => 'relevance',
					'update_post_meta_cache' => false,
					'update_post_term_cache' => false,
					'no_found_rows'          => true,
				]
			)
		);
	}

	/**
	 * Query posts
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param array $query_args Holds WP_Query arguments.
	 * @return array
	 */
	public function query( $query_args ) {

		$posts = [];
		$query = new \WP_Query( $query_args );

		if ( $query->have_posts() ) {

			while ( $query->have_posts() ) {

				global $post;

				$query->the_post();

				if ( ! isset( $posts[ $post->post_type ] ) ) {

					$posts[ $post->post_type ] = [
						'label'   => get_post_type_object( $post->post_type )->labels->name,
						'options' => [],
					];
				}

				$posts[ $post->post_type ]['options'][] = [
					'value' => $post->ID,
					'label' => sprintf(
						/* translators: %s: option name, %d: option count */
						__( '%1$s (#%2$d)', 'wp-grid-builder' ),
						html_entity_decode( wp_strip_all_tags( get_the_title() ?: $post->post_name ), ENT_QUOTES, get_bloginfo( 'charset' ) ),
						$post->ID
					),
				];
			}

			wp_reset_postdata();

		}

		return $posts;

	}
}
