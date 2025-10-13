<?php

namespace BricksExtras;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( class_exists( 'BricksExtrasQueryLoop' ) ) {
	return;
}

class BricksExtrasQueryLoop {

	static string $prefix = '';

	public function init( $prefix ) {

		self::$prefix    = $prefix;

		add_action( 'init', [ $this, 'add_queryLoopExtras_controls' ], 40 );
		add_filter( 'bricks/setup/control_options', [ $this, 'setup_queryLoopExtras_controls' ]);
		add_filter( 'bricks/query/run', [ $this, 'run_queryLoopExtras' ], 10, 2 );
		add_filter( 'bricks/query/loop_object', [ $this, 'extras_setup_post_data' ], 10, 3);

	}


	function add_queryLoopExtras_controls() {

		$elements = [ 
			'container', 
			'block', 
			'div', 
			'xdynamictable'
		];
	
		foreach ( $elements as $element ) {
			add_filter( "bricks/elements/{$element}/controls", [ $this, 'queryLoopExtras_controls' ], 40 );
		}

	
	}



	public function queryLoopExtras_controls( $controls ) {

		$taxonomies = \Bricks\Setup::$control_options['taxonomies'];
	 	 unset( $taxonomies['Wp_template_part'] );

		  $extrasQueryOptions = [
			'adjacent'  => esc_html__( 'Adjacent Posts' ),
			'gallery'	=> esc_html__( 'Gallery' ),
			'related'  => esc_html__( 'Related Posts' ),
			'wpmenu' => esc_html__( 'WP Menu' ),
		];

		if ( get_option( self::$prefix . 'favorite') ) {
			$extrasQueryOptions['favorite'] = 'Favorites';
		}

		$newControls['extrasQuery'] = [
			'tab'         => 'content',
			'label'       => esc_html__( 'Query Type', 'bricks' ),
			'type'        => 'select',
			'inline'      => true,
			'options'     => $extrasQueryOptions,
			'placeholder' => esc_html__( 'Select...', 'bricks' ),
			'required'    => array(
				[ 'query.objectType', '=', 'queryLoopExtras' ],
				[ 'hasLoop', '!=', false ]
			),
			'rerender'    => true,
			'multiple'    => false,
		];
	
	
	  /* adjacentPost */
	
	  $newControls['adjacentPost'] = [
			'tab'         => 'content',
			'label'       => esc_html__( 'Adjacent Post', 'bricks' ),
			'type'        => 'select',
			'inline'      => true,
			'options'     => [
				'prev'  => esc_html__( 'Previous', 'bricks' ),
				'next'  => esc_html__( 'Next', 'bricks' ),
			],
			'placeholder' => esc_html__( 'Previous', 'bricks' ),
			'required' => [
				[ 'extrasQuery', '=', 'adjacent'],
				[ 'query.objectType', '=', 'queryLoopExtras' ],
				[ 'hasLoop', '!=', false ]
			],
			'rerender'    => true,
			'multiple'    => false,
		];

		$newControls['adjacentPostSameTerm'] = [
			'tab'      => 'content',
			'label'    => esc_html__( 'Post should be in the same taxonomy term', 'bricks' ),
			'type'     => 'checkbox',
			'rerender'  => true,
			'required' => [
				[ 'extrasQuery', '=', 'adjacent'],
				[ 'query.objectType', '=', 'queryLoopExtras' ],
				[ 'hasLoop', '!=', false ]
			]
		  ];

		  $newControls['adjacentTaxonomy'] = [
			'tab'      => 'content',
			'label'    => esc_html__( 'Taxonomy', 'bricks' ),
			'type'        => 'select',
			'options'     => $taxonomies,
			'multiple'    => false,
			'description' => esc_html__( 'Taxonomy adjacent posts must have in common.', 'bricks' ),
			'placeholder' => [
				'category',
			],
			'required' => [
				[ 'extrasQuery', '=', 'adjacent'],
				[ 'query.objectType', '=', 'queryLoopExtras' ],
				[ 'hasLoop', '!=', false ],
				['adjacentPostSameTerm', '=', true]
			]
		  ];

		 $newControls['adjacentPostExcludedTerms'] = [
			'tab'      => 'content',
			'label'    => esc_html__( 'Excluded Term IDs', 'bricks' ),
			'type'     => 'text',
			'required' => [
				[ 'extrasQuery', '=', 'adjacent'],
				[ 'query.objectType', '=', 'queryLoopExtras' ],
				[ 'hasLoop', '!=', false ],
				['adjacentPostSameTerm', '=', true]
			]
		  ];
	
	
	
	  /* related posts */
	
	  $newControls['post_type'] = [
		'tab'         => 'content',
		'label'       => esc_html__( 'Post Type', 'bricks' ),
		'type'        => 'select',
		'options'     => bricks_is_builder() ? \Bricks\Helpers::get_registered_post_types() : [],
		'clearable'   => true,
		'inline'      => true,
		'searchable'  => true,
		'placeholder' => esc_html__( 'Default', 'bricks' ),
		'required' => [
		  [ 'extrasQuery', '=', ['related','favorite']],
		  [ 'query.objectType', '=', 'queryLoopExtras' ],
				[ 'hasLoop', '!=', false ]
		],
	  ];
	
	  $newControls['count'] = [
		'tab'         => 'content',
		'label'       => esc_html__( 'Max. related posts', 'bricks' ),
		'type'        => 'number',
		'min'         => 1,
		'max'         => 4,
		'placeholder' => 3,
		'required' => [
		  [ 'extrasQuery', '=', 'related'],
		  [ 'query.objectType', '=', 'queryLoopExtras' ],
				[ 'hasLoop', '!=', false ]
		],
	  ];
	
	  $newControls['order'] = [
		'tab'         => 'content',
		'label'       => esc_html__( 'Order', 'bricks' ),
		'type'        => 'select',
		'options'     => [
		  'ASC' => esc_html__( 'Ascending', 'bricks' ),
		  'DESC' => esc_html__( 'Descending', 'bricks' ),
		],
		'inline'      => true,
		'placeholder' => esc_html__( 'Descending', 'bricks' ),
		'required' => [
		  [ 'extrasQuery', '=', 'related'],
		  [ 'query.objectType', '=', 'queryLoopExtras' ],
				[ 'hasLoop', '!=', false ]
		],
	  ];
	
	  $newControls['orderby'] = [
		'tab'         => 'content',
		'label'       => esc_html__( 'Order by', 'bricks' ),
		'type'        => 'select',
		'options'     => \Bricks\Setup::$control_options['queryOrderBy'],
		'inline'      => true,
		'placeholder' => esc_html__( 'Random', 'bricks' ),
		'required' => [
		  [ 'extrasQuery', '=', 'related'],
		  [ 'query.objectType', '=', 'queryLoopExtras' ],
		  [ 'hasLoop', '!=', false ]
		],
	  ];
	
	  $newControls['taxonomies'] = [
		'tab'         => 'content',
		'label'       => esc_html__( 'Common taxonomies', 'bricks' ),
		'type'        => 'select',
		'options'     => $taxonomies,
		'multiple'    => true,
		'default'     => [
		  'category',
		  'post_tag'
		],
		'required' => [
		  [ 'extrasQuery', '=', 'related'],
		  [ 'query.objectType', '=', 'queryLoopExtras' ],
		  [ 'hasLoop', '!=', false ]
		],
	  ];
	
	
	  /* wpmenu */

	    $nav_menus = [];

		if ( bricks_is_builder() ) {
			foreach ( wp_get_nav_menus() as $menu ) {
				$nav_menus[ $menu->term_id ] = $menu->name;
			}
		}

		$newControls['menuSource'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Menu source', 'bricks' ),
			'type' => 'select',
			'options' => [
			  'dropdown' => esc_html__( 'Select menu', 'bricks' ),
			  'dynamic' => esc_html__( 'Dynamic data', 'bricks' ),
			],
			'inline'      => true,
			'clearable' => false,
			'placeholder' => esc_html__( 'Select menu', 'bricks' ),
			'required' => [
				[ 'extrasQuery', '=', 'wpmenu'],
				[ 'query.objectType', '=', 'queryLoopExtras' ],
				[ 'hasLoop', '!=', false ]
			],
		  ];
	
		  $newControls['x_menu_id'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Menu name, menu slug or menu ID', 'bricks' ),
			'type' => 'text',
			//'inline' => true,
			'placeholder' => esc_html__( '', 'bricks' ),
			'description' => sprintf( '<a href="' . admin_url( 'nav-menus.php' ) . '" target="_blank">' . esc_html__( 'Manage my menus in WordPress.', 'bricks' ) . '</a>' ),
			'required' => [
				[ 'extrasQuery', '=', 'wpmenu'],
				[ 'query.objectType', '=', 'queryLoopExtras' ],
				[ 'menuSource', '=', 'dynamic' ],
				[ 'hasLoop', '!=', false ]
			],
		  ];

		$newControls['menu'] = [
			'tab'         => 'content',
			'label'       => esc_html__( 'Select Menu..', 'bricks' ),
			'type'        => 'select',
			'options'     => $nav_menus,
			'placeholder' => esc_html__( 'Select nav menu', 'bricks' ),
			'description' => sprintf( '<a href="' . admin_url( 'nav-menus.php' ) . '" target="_blank">' . esc_html__( 'Manage my menus in WordPress.', 'bricks' ) . '</a>' ),
			'required' => [
				[ 'extrasQuery', '=', 'wpmenu'],
				[ 'query.objectType', '=', 'queryLoopExtras' ],
				[ 'menuSource', '!=', 'dynamic' ],
				[ 'hasLoop', '!=', false ]
			],
		];

		/* favorites */

		$newControls['x_favorites_orderby'] = [
			'tab'         => 'content',
			'label'       => esc_html__( 'Order by', 'bricks' ),
			'type'        => 'select',
			'inline'      => true,
			'options'     => [
				'post__in'       => esc_html( 'As added to list', 'bricks' ),
				'none'           => esc_html( 'None', 'bricks' ),
				'ID'             => esc_html( 'ID', 'bricks' ),
				'author'         => esc_html( 'Author', 'bricks' ),
				'title'          => esc_html( 'Title', 'bricks' ),
				'date'           => esc_html( 'Published date', 'bricks' ),
				'modified'       => esc_html( 'Modified date', 'bricks' ),
				'rand'           => esc_html( 'Random', 'bricks' ),
				'meta_value'     => esc_html( 'Meta value', 'bricks' ),
				'meta_value_num' => esc_html( 'Meta numeric value', 'bricks' ),
			],
			'placeholder' => esc_html__( 'Added to list', 'bricks' ),
			'required' => [
				[ 'extrasQuery', '=', 'favorite'],
				[ 'query.objectType', '=', 'queryLoopExtras' ],
				[ 'hasLoop', '!=', false ]
			],
		];

		$newControls['x_favorites_meta_key'] = [
			'type'           => 'text',
			'label'          => esc_html__( 'Meta Key', 'bricks' ),
			'hasDynamicData' => false,
			'inline'      => true,
			'required' => [
				[ 'extrasQuery', '=', 'favorite'],
				[ 'query.objectType', '=', 'queryLoopExtras' ],
				[ 'hasLoop', '!=', false ],
				[ 'optionSource', '=', [ 'meta_value','meta_value_num' ] ],
			],
			'required'       => [
				[ 'x_favorites_orderby', '=', [ 'meta_value','meta_value_num' ] ],
			],
		];

		$newControls['x_favorites_order'] = [
			'tab'         => 'content',
			'label'       => esc_html__( 'Order', 'bricks' ),
			'type'        => 'select',
			'inline'      => true,
			'options'     => [
				'ASC'  => esc_html__( 'Ascending', 'bricks' ),
				'DESC' => esc_html__( 'Descending', 'bricks' ),
			],
			'placeholder' => esc_html__( 'Ascending', 'bricks' ),
			'required' => [
				[ 'extrasQuery', '=', 'favorite'],
				[ 'query.objectType', '=', 'queryLoopExtras' ],
				[ 'hasLoop', '!=', false ],
			],
		];

		$newControls['x_newList_separator'] = [
			'tab' => 'content',
			'type' => 'separator',
			'required' => [
				[ 'extrasQuery', '=', 'favorite'],
				[ 'query.objectType', '=', 'queryLoopExtras' ],
				[ 'hasLoop', '!=', false ]
			],
		];

		$newControls['newList'] = [
			'tab' => 'content',
			'type' => 'checkbox',
      		'label' => esc_html__( 'Custom List', 'extras' ),
			'required' => [
				[ 'extrasQuery', '=', 'favorite'],
				[ 'query.objectType', '=', 'queryLoopExtras' ],
				[ 'hasLoop', '!=', false ]
			],
		];

		$newControls['listSlug'] = [
			'tab'   => 'content',
			//'group' => 'button',
			'label' => esc_html__( 'List Indentifier', 'extras' ),
			'type'  => 'text',
			'inline' => true,
			//'small' => true,
			'placeholder' => esc_html__(''),
			'required' => [
				[ 'extrasQuery', '=', 'favorite'],
				[ 'query.objectType', '=', 'queryLoopExtras' ],
				[ 'hasLoop', '!=', false ],
				[ 'newList', '=', true ]
			],
		  ];


		  /* gallery */

		  $newControls['x_gallery_data'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Gallery', 'bricks' ),
			'type' => 'text',
			'inline' => true,
			'placeholder' => esc_html__( 'Dynamic Data', 'bricks' ),
			'required' => [
				[ 'extrasQuery', '=', 'gallery'],
				[ 'query.objectType', '=', 'queryLoopExtras' ],
				[ 'hasLoop', '!=', false ]
			],
		  ];

		  $newControls['x_gallery_orderby'] = [
			'tab'         => 'content',
			'label'       => esc_html__( 'Order by', 'bricks' ),
			'type'        => 'select',
			'inline'      => true,
			'options'     => [
				'post__in'       => esc_html( 'Default', 'bricks' ),
				'date'           => esc_html( 'Published date', 'bricks' ),
				'modified'       => esc_html( 'Modified date', 'bricks' ),
				'rand'           => esc_html( 'Random', 'bricks' ),
			],
			'placeholder' => esc_html__( 'Default', 'bricks' ),
			'required' => [
				[ 'extrasQuery', '=', 'gallery'],
				[ 'query.objectType', '=', 'queryLoopExtras' ],
				[ 'hasLoop', '!=', false ]
			],
		];

		$newControls['x_gallery_offset'] = [
			'tab'         => 'content',
			'label'       => esc_html__( 'Offset', 'bricks' ),
			'type'        => 'number',
			'units'       => false,
			'inline'      => true,
			'placeholder' => '0',
			'required' => [
				[ 'extrasQuery', '=', 'gallery'],
				[ 'query.objectType', '=', 'queryLoopExtras' ],
				[ 'hasLoop', '!=', false ]
			],
		];

		$newControls['x_gallery_max'] = [
			'tab'         => 'content',
			'label'       => esc_html__( 'Max no. of images', 'bricks' ),
			'type'        => 'number',
			'units'       => false,
			'inline'      => true,
			'required' => [
				[ 'extrasQuery', '=', 'gallery'],
				[ 'query.objectType', '=', 'queryLoopExtras' ],
				[ 'hasLoop', '!=', false ]
			],
		];

	
		$query_key_index = absint( array_search( 'query', array_keys( $controls ) ) );
		$new_controls    = array_slice( $controls, 0, $query_key_index + 1, true ) + $newControls + array_slice( $controls, $query_key_index + 1, null, true );
	
		return $new_controls;
	
	}
	


	function setup_queryLoopExtras_controls( $control_options ) {

		$control_options['queryTypes']['queryLoopExtras'] = esc_html__( 'Extras', 'bricks' );
		return $control_options;
	
	}


	public function run_queryLoopExtras( $results, $query_obj ) {

		if ( $query_obj->object_type !== 'queryLoopExtras' ) {
			return $results;
		}
	
		$settings = $query_obj->settings;
	
		if ( ! $settings['hasLoop'] ) {
			return [];
		}
	
		$extrasQuery = isset( $settings['extrasQuery'] ) ? $settings['extrasQuery'] : false;
	
	  if ('adjacent' === $extrasQuery) {
	
		/* adjacent posts */
	
		$adjacentPost = isset( $settings['adjacentPost'] ) ? $settings['adjacentPost'] : 'previous';
		$adjacentPostSameTerm = isset( $settings['adjacentPostSameTerm'] );
		$excludedTerms = isset( $settings['adjacentPostExcludedTerms'] ) ? $settings['adjacentPostExcludedTerms'] : '';
		$adjacentTaxonomy = isset( $settings['adjacentTaxonomy'] ) ? $settings['adjacentTaxonomy'] : 'category';
	
		if ( 'prev' === $adjacentPost && !empty( get_previous_post($adjacentPostSameTerm, $excludedTerms, $adjacentTaxonomy) ) ) {
		  return [ get_previous_post($adjacentPostSameTerm, $excludedTerms, $adjacentTaxonomy) ];
		}
	
		if ( 'next' === $adjacentPost && !empty( get_next_post($adjacentPostSameTerm, $excludedTerms, $adjacentTaxonomy) ) ) {
		  return [ get_next_post($adjacentPostSameTerm, $excludedTerms, $adjacentTaxonomy) ];
		}
	
	  } elseif ( 'related' === $extrasQuery ) {
	
		/* related posts */
	
		global $post;
	
		$post_id = $post->ID;
	
			$args = [
				'posts_per_page' => isset( $settings['count'] ) ? intval( $settings['count'] ) : 3,
				'post__not_in'   => [ $post_id ],
				'no_found_rows'  => true, // No pagination
				'orderby'        => isset( $settings['orderby'] ) ? $settings['orderby'] : 'rand',
				'order'          => isset( $settings['order'] ) ? $settings['order'] : 'DESC',
			];
	
			if ( ! empty( $settings['post_type'] ) ) {
				$args['post_type'] = $settings['post_type'];
			}
	
			$taxonomies = ! empty( $settings['taxonomies'] ) ? $settings['taxonomies'] : [];
	
			foreach ( $taxonomies as $taxonomy ) {
				$terms_ids = wp_get_post_terms(
					$post_id,
					$taxonomy,
					[ 'fields' => 'ids' ]
				);
	
				if ( ! empty( $terms_ids ) ) {
					$args['tax_query'][] = [
						'taxonomy' => $taxonomy,
						'field'    => 'term_id',
						'terms'    => $terms_ids,
					];
				}
			}
	
			if ( count( $taxonomies ) > 1 && isset( $args['tax_query'] ) ) {
				$args['tax_query']['relation'] = 'OR';
			}
	
		$args['post_status'] = 'publish';
	
		$posts_query = new \WP_Query( $args );
	
		return $posts_query->posts;
	
	  } elseif ( 'wpmenu' === $extrasQuery ) {

		$menuSource = isset( $settings['menuSource'] ) ? $settings['menuSource'] : 'dropdown';
		$menu = isset( $settings['menu'] ) ? intval( $settings['menu'] ) : false;
		$menu_id = isset( $settings['x_menu_id'] ) ? bricks_render_dynamic_data( $settings['x_menu_id'] ) : false;

		$menuID = 'dropdown' === $menuSource ? $menu : $menu_id;

		return ! $menuID ? [] : $this->x_nav_menu_query( $query_obj, $menuID );


	  } elseif ( 'favorite' === $extrasQuery ) {

		$post_type = ! empty( $settings['post_type'] ) ? $settings['post_type'] : 'post';

		$favoritesOrderBy = isset( $settings['x_favorites_orderby'] ) ? $settings['x_favorites_orderby'] : 'post__in';
		$favoritesOrder = isset( $settings['x_favorites_order'] ) ? $settings['x_favorites_order'] : 'ASC';

		$args = [	
			'post_type' => $post_type,
			'post_status' => ['publish', 'inherit'],
			'posts_per_page' => -1,
			'orderby' => $favoritesOrderBy,
			'order' => $favoritesOrder,
			'cache_results' => false,
			'meta_key' => isset( $settings['x_favorites_meta_key'] ) ? $settings['x_favorites_meta_key'] : '',
		];

		
		
		if ('attachment' === $post_type) {
			$args['post_mime_type'] = 'image';
			$args['post_status'] = 'inherit';
		}

		$listSlug = ! empty( $settings['listSlug'] ) ? $settings['listSlug'] : false;

		if (!!$listSlug && isset( $settings['newList'] ) ) {

			$listSlug = strtolower($listSlug);                     // Convert to lowercase
			$listSlug = preg_replace('/\s+/', '_', $listSlug);      // Replace spaces with underscores
			$suffix = preg_replace('/[^a-z0-9_]/', '', $listSlug);    // Remove characters that are not letters, numbers, or underscores


			$post__in = Helpers::get_favorite_ids_array( $post_type . '__' . $suffix );
		} else {
			$post__in = Helpers::get_favorite_ids_array( $post_type );
		}

		$post__in_value = is_array( $post__in ) && !empty( $post__in ) ? $post__in : [0];

		if ( 'post__in' === $favoritesOrderBy && 'DESC' === $favoritesOrder ) {
			$post__in_value = array_reverse( $post__in_value );
		}

		$args['post__in'] = $post__in_value;

		$posts_query = new \WP_Query( $args );

		$key = 'x_favorite_ids';

		// Add in_favorites_loop flag for posts in favorites loop, for 'in favorites loop' condition
		foreach ( $posts_query->posts as $key => $post ) {
			$posts_query->posts[ $key ]->in_favorites_loop = true;
		}
	
		return is_array( $posts_query->posts ) ? $posts_query->posts : [];
		
	  } elseif ( 'gallery' === $extrasQuery ) {

		$gallery_data = ! empty( $settings['x_gallery_data'] ) ? $settings['x_gallery_data'] : false;

		if (!$gallery_data){
			return [];
		}

		$gallery_images = \Bricks\Integrations\Dynamic_Data\Providers::render_tag($gallery_data, get_the_ID(), 'image' );

		$x_gallery_orderby = isset( $settings['x_gallery_orderby'] ) ? $settings['x_gallery_orderby'] : 'post__in';
		$x_gallery_offset = isset( $settings['x_gallery_offset'] ) ? intval( $settings['x_gallery_offset'] ) : 0;
		$x_gallery_max = isset( $settings['x_gallery_max'] ) ? intval( $settings['x_gallery_max'] ) : 999;

		if (!is_array($gallery_images) || empty($gallery_images)) {
			$post__in = [0];
		} else {
			$post__in = $gallery_images;
		}

		$args = [
			'post_type' => 'attachment',
			'post_status' => 'inherit',
			'posts_per_page' => $x_gallery_max, 
			'no_found_rows' => true,
			'post__in' => $post__in,
			'orderby' => $x_gallery_orderby,
			'offset' => $x_gallery_offset,
		];

		$posts_query = new \WP_Query( $args );

		return is_array( $posts_query->posts ) ? $posts_query->posts : [];

	}
	  
	  else {
		return [];
	  }
	
	
	}


	function extras_setup_post_data( $loop_object, $loop_key, $query_obj ) {
    
		if ( $query_obj->object_type !== 'queryLoopExtras' ) {
			return $loop_object;
		}
	
		 global $post;
		 $post = get_post( $loop_object );
		 setup_postdata( $post );
		
		 return $loop_object;
	
	}


	function x_nav_menu_query( $query_obj, $menu_id ) {

		$menu_items = wp_get_nav_menu_items( $menu_id );

		$menu_objects = [];

		if ( !$menu_items ) {
			return $menu_objects;
		}

		foreach ( $menu_items as $item ) {

			$menu_objects[] = [
				'title' => $item->title,
				'url' => $item->url,
				'description' => $item->description,
				'classes' => $item->classes,
			];
			
		}

		return $menu_objects;
	}
		

}
