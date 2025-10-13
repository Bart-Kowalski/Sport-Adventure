<?php

namespace BricksExtras;

use Bricks\Query;
use DateTimeImmutable;
use EDD_recurring_subscriber;
use SureMembers\Inc\Access_Groups;
use TRP_Translate_Press;
use WC_Product;
use WC_Product_Factory;
use WP_Query;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( class_exists( 'BricksExtrasConditions' ) ) {
	return;
}

if ( ! class_exists( 'BricksExtrasHelpers' ) ) {
	require_once 'BricksExtrasHelpers.php';
}

class BricksExtrasConditions {

	static string $prefix = '';

	public static function init( $prefix ): void {

		self::$prefix = $prefix;

		add_filter( 'bricks/conditions/groups', [ __CLASS__, 'condition_group'] );
		add_filter( 'bricks/conditions/options', [ __CLASS__, 'condition_options'] );
		add_filter( 'bricks/conditions/result', [ __CLASS__, 'run_conditions'], 10, 3 );
			
	}
	
	
	public static function condition_group( $groups ) {

		

		// prefix names with x_ 

		/* more general conditions */

		$generalConditionsArray = Helpers::getExtrasConditions('general');
		$generalConditionsActive = false;

		foreach ( $generalConditionsArray as $key => $condition ) {	

			if ( 1 === intval( get_option( self::$prefix . $key, 0 ) ) ) {
				$generalConditionsActive = true;
				break;
			}
		}

		if ( $generalConditionsActive ) {
			$groups[] = [
				'name'  => 'x_extras_conditions',
				'label' => esc_html__( 'Extras', 'bricks' ),
			];
		}


		/* member conditions */

		$memberConditionsArray = Helpers::getExtrasConditions('member');
		$memberConditionsActive = false;

		foreach ( $memberConditionsArray as $key => $condition ) {	
			if ( 1 === intval( get_option( self::$prefix . $key, 0 ) ) ) {
				$memberConditionsActive = true;
				break;
			}
		}

		if ( $memberConditionsActive ) {		
			$groups[] = [
				'name'  => 'x_member_conditions',
				'label' => esc_html__( 'Member Conditions (extras)', 'bricks' ),
			];
		}


		/* WooCommerce conditions */

		$wcConditionsArray = Helpers::getExtrasConditions('wc');
		$wcConditionsActive = false;

		foreach ( $wcConditionsArray as $key => $condition ) {	
			if ( 1 === intval( get_option( self::$prefix . $key, 0 ) ) ) {
				$wcConditionsActive = true;
				break;
			}
		}

		if ( $wcConditionsActive ) {		
			$groups[] = [
				'name'  => 'x_wc_conditions',
				'label' => esc_html__( 'WooCommerce Conditions (extras)', 'bricks' ),
			];
		}
		

		return $groups;
	}

		
	public static function condition_options( $options ) {

		// prefix keys with x_

		/* member conditions */

		/* memberpress */

		if ( get_option( self::$prefix . 'memberpress' ) && class_exists( '\MeprSubscription' ) ) {
			
			$options[] = [
				'key'   => 'x_memberpress_membership', 
				'label' => esc_html__( 'User membership (MemberPress)', 'bricks' ),
				'group' => 'x_member_conditions',
				'compare' => [
					'type'        => 'select',
						'options'     =>  [
							'==' => esc_html__( 'Active', 'bricks' ),
							'!=' => esc_html__( 'Inactive', 'bricks' ),
						],
						'placeholder' => esc_html__( 'Active', 'bricks' ),
					],
				'value'   => [
					'type'        => 'select',
					'options' => Helpers::getAllPostOptions('memberpressproduct'),
					'placeholder' => esc_html__( 'Select membership...', 'bricks' ),
				],
			];

		}

		/* easy digital downloads */

		if ( get_option( self::$prefix . 'easy_digital_downloads' ) && function_exists( 'EDD' ) ) {

			$eddProductOptions = Helpers::getAllPostOptions('download');

			$options[] = [
				'key'   => 'x_edd_product_purchased', 
				'label' => esc_html__( 'User purchased download (EDD)', 'bricks' ),
				'group' => 'x_member_conditions',
				'compare' => [
					'type'        => 'select',
						'options'     =>  [
							'==' => esc_html__( '==', 'bricks' ),
							'!=' => esc_html__( '!=', 'bricks' ),
						],
						'placeholder' => esc_html__( '==', 'bricks' ),
					],
				'value'   => [
					'type'        => 'select',
					'options' =>  $eddProductOptions,
					'placeholder' => esc_html__( 'Select product...', 'bricks' ),
				],
			];

			/* recurring payment in EDD Pro */
			if ( class_exists( '\EDD_Recurring_Subscriber' ) ) {

				$options[] = [
					'key'   => 'x_edd_subscribed', 
					'label' => esc_html__( 'User subscription (EDD)', 'bricks' ),
					'group' => 'x_member_conditions',
					'compare' => [
						'type'        => 'select',
							'options'     =>  [
								'==' => esc_html__( 'Active', 'bricks' ),
								'!=' => esc_html__( 'Inactive', 'bricks' ),
							],
							'placeholder' => esc_html__( 'Active', 'bricks' ),
						],
					'value'   => [
						'type'        => 'select',
						'options' => $eddProductOptions,
						'placeholder' => esc_html__( 'Select product...', 'bricks' ),
					],
				];

			}

		}


		/* wp members */

		if (  class_exists( '\WP_Members_Products' ) && get_option( self::$prefix . 'wp_members' ) ) {
			
			$options[] = [
				'key'   => 'x_wp_members_membership', 
				'label' => esc_html__( 'User membership (WP Members)', 'bricks' ),
				'group' => 'x_member_conditions',
				'compare' => [
					'type'        => 'select',
						'options'     =>  [
							'==' => esc_html__( 'Active', 'bricks' ),
							'!=' => esc_html__( 'Inactive', 'bricks' ),
						],
						'placeholder' => esc_html__( 'Active', 'bricks' ),
					],
				'value'   => [
					'type'        => 'select',
					'options' => Helpers::getAllPostOptions('wpmem_product'),
					'placeholder' => esc_html__( 'Select product...', 'bricks' ),
				],
			];

		}


		/* restrict content */

		if ( get_option( self::$prefix . 'restrict_content' ) && function_exists( 'rcp_get_membership_levels' )  ) {

			$levelOptions = [];

			$levels = rcp_get_membership_levels('active');

			if ( ! empty( $levels ) ) {
				foreach ( $levels as $level ) {
					$levelOptions[$level->id] = $level->name;
				}
			}

			$options[] = [
				'key'   => 'x_rcp_membership_level', 
				'label' => esc_html__( 'Membership level (Restrict Content)', 'bricks' ),
				'group' => 'x_member_conditions',
				'compare' => [
					'type'        => 'select',
						'options'     =>  [
							'==' => esc_html__( 'User belongs to', 'bricks' ),
							'active' => esc_html__( 'User belongs to (status: active)', 'bricks' ),
							'cancelled' => esc_html__( 'User belongs to (status: cancelled)', 'bricks' ),
							'pending' => esc_html__( 'User belongs to (status: pending)', 'bricks' ),
							'expired' => esc_html__( 'User belongs to (status: expired)', 'bricks' ),
							'!=' => esc_html__( 'User does not belong to', 'bricks' ),
						],
						'placeholder' => esc_html__( 'User belongs to', 'bricks' ),
					],
				'value'   => [
					'type'        => 'select',
					'options' => $levelOptions,
					'placeholder' => esc_html__( 'Select membership level..', 'bricks' ),
				],
			];

			$options[] = [
				'key'   => 'x_rcp_has_active_membership', 
				'label' => esc_html__( 'User has active membership (Restrict Content)', 'bricks' ),
				'group' => 'x_member_conditions',
				'compare' => [
					'type'        => 'select',
						'options'     =>  [
							'==' => esc_html__( 'is', 'bricks' ),
						],
						'placeholder' => esc_html__( 'is', 'bricks' ),
					],
				'value'   => [
					'type'        => 'select',
					'options' => [
						'true' => esc_html__( 'True', 'bricks' ),
						'false' => esc_html__( 'False', 'bricks' ),
					],
					'placeholder' => esc_html__( 'True', 'bricks' ),
				],
			];

			$options[] = [
				'key'   => 'x_rcp_has_paid_membership', 
				'label' => esc_html__( 'User has paid membership (Restrict Content)', 'bricks' ),
				'group' => 'x_member_conditions',
				'compare' => [
					'type'        => 'select',
						'options'     =>  [
							'==' => esc_html__( 'is', 'bricks' ),
						],
						'placeholder' => esc_html__( 'is', 'bricks' ),
					],
				'value'   => [
					'type'        => 'select',
					'options' => [
						'true' => esc_html__( 'True', 'bricks' ),
						'false' => esc_html__( 'False', 'bricks' ),
					],
					'placeholder' => esc_html__( 'True', 'bricks' ),
				],
			];

		}

		/* WishList Member */
		if ( get_option( self::$prefix . 'wishlist_member' ) && function_exists( 'wlmapi_get_levels' ) ) {
			$wlmLevels = wlmapi_get_levels();
 
			// Initialize an empty array to store the result
			$wlmResultArray = [];
			
			// Check if the 'levels' key exists in the $wlmLevels array, and it contains the 'level' sub-array
			if ( isset( $wlmLevels['levels'] ) && isset( $wlmLevels['levels']['level'] ) ) {
					// Loop through the 'level' sub-array and extract the id and name values
					foreach ( $wlmLevels['levels']['level'] as $level ) {
							if ( isset( $level['id'] ) && isset( $level['name'] ) ) {
									// Add the id and name as key-value pairs to the $wlmResultArray
									$wlmResultArray[$level['id']] = $level['name'];
							}
					}
			}

			$options[] = [
				'key'   => 'x_wishlist_member', 
				'label' => esc_html__( 'Member Level (WishList Member)', 'bricks' ),
				'group' => 'x_member_conditions',
				'compare' => [
					'type'        => 'select',
					'options'     =>  [
						'==' => esc_html__( 'User belongs to', 'bricks' ),
						'!=' => esc_html__( 'User does not belong to', 'bricks' ),
						'active' => esc_html__( 'User belongs to (active)', 'bricks' ),
						'cancelled' => esc_html__( 'User belongs to (cancelled)', 'bricks' ),
					],
					'placeholder' => esc_html__( 'User belongs to', 'bricks' )
				],
				'value'   => [
					'type' => 'select',
					'options' => $wlmResultArray,
					'placeholder' => esc_html__( 'Select level...', 'bricks' )
				],
			];

		}


		/* Sure Members */
		if ( get_option( self::$prefix . 'sure_members' ) && method_exists( '\SureMembers\Inc\Access_Groups', 'check_if_user_has_access' ) ) {

			$options[] = [
				'key'   => 'x_sure_members_access_groups', 
				'label' => esc_html__( 'Member access group (SureMembers)', 'bricks' ),
				'group' => 'x_member_conditions',
				'compare' => [
					'type'        => 'select',
					'options'     =>  [
						'==' => esc_html__( 'is', 'bricks' ),
						'!=' => esc_html__( 'is not', 'bricks' ),
					],
					'placeholder' => esc_html__( 'is', 'bricks' )
				],
				'value'   => [
					'type' => 'select',
					'options' => Helpers::getAllPostOptions('wsm_access_group'),
					'placeholder' => esc_html__( 'Select access group...', 'bricks' ),
				],
			];

		}

		/* Paid Memberships Pro */
		if ( get_option( self::$prefix . 'pmp_membership_level' ) && class_exists( '\PMPro_Membership_Level' )  ) {

			global $pmpro_levels;

			$bricks_pmpro_levels = [ 'non-members' => esc_html__( 'Non-Members', 'bricks' ) ];

			if ( ! empty( $pmpro_levels ) ) {
				foreach( $pmpro_levels as $pmpro_level ) {
					$bricks_pmpro_levels[$pmpro_level->id] = $pmpro_level->name;
				}
			}

			$options[] = [
				'key'   => 'x_pmp_membership_level',
				'label' => esc_html__( 'Paid Memberships Pro Level', 'bricks' ),
				'group' => 'x_member_conditions',
				'compare' => [
					'type'        => 'select',
						'options'     =>  [
							'==' => esc_html__( '==', 'bricks' ),
							'!=' => esc_html__( '!=', 'bricks' ),
						],
						'placeholder' => esc_html__( '==', 'bricks' ),
					],
				'value'   => [
					'type'        => 'select',
					'options' => $bricks_pmpro_levels,
					'placeholder' => esc_html__( 'Non-Members', 'bricks' ),
				],
			];

		}

		/* Woocommerce Subscriptions */
		if ( get_option( self::$prefix . 'woocommerce_subscriptions' ) && class_exists( '\WC_Subscriptions' )  ) {

			$options[] = [
				'key'   => 'x_woocommerce_subscriptions',
				'label' => esc_html__( 'Has active subscription (Woocommerce Subscriptions)', 'bricks' ),
				'group' => 'x_member_conditions',
				'compare' => [
					'type'        => 'select',
						'options'     =>  [
							'==' => esc_html__( '==', 'bricks' ),
							'!=' => esc_html__( '!=', 'bricks' ),
						],
						'placeholder' => esc_html__( '==', 'bricks' ),
					],
				'value'   => [
					'type'        => 'select',
					'options' => Helpers::getAllPostOptions('product'),
					'placeholder' => esc_html__( 'Select subscription product..', 'bricks' ),
				],
			];

		}


		

		/* general conditions */

		if ( get_option( self::$prefix . 'at_least_1_search_result' ) ) {

			$options[] = [
				'key'   => 'x_at_least_1_search_result', 
				'label' => esc_html__( 'At least one search result', 'bricks' ),
				'group' => 'x_extras_conditions',
				'compare' => [
					'type'        => 'select',
					'options'     =>  [
						'==' => esc_html__( 'is', 'bricks' ),
//						'!=' => esc_html__( 'is not', 'bricks' ),
					],
					'placeholder' => esc_html__( 'is', 'bricks' )
				],
				'value'   => [
					'type'        => 'select',
					'options' => [
						'present' => esc_html__( 'Present', 'bricks' ),
						'not_present' => esc_html__( 'Not present', 'bricks' ),
					],
					'placeholder' => esc_html__( 'Present', 'bricks' )
				],
			];

		}

		if ( get_option( self::$prefix . 'archive_type' ) ) {

			// Archive Type
			$options[] = [
				'key'   => 'x_archive_type', 
				'label' => esc_html__( 'Archive type', 'bricks' ),
				'group' => 'x_extras_conditions',
				'compare' => [
					'type'        => 'select',
					'options'     =>  [
						'==' => esc_html__( 'is', 'bricks' ),
						'!=' => esc_html__( 'is not', 'bricks' ),
					],
					'placeholder' => esc_html__( 'is', 'bricks' )
				],
				'value'   => [
					'type'        => 'select',
					'options' => [
						'posts_page' => esc_html__( 'Posts page (Blog)', 'bricks' ),
						'category' => esc_html__( 'Category', 'bricks' ),
						'tag' => esc_html__( 'Tag', 'bricks' ),
						'taxonomy' => esc_html__( 'Taxonomy', 'bricks' ),
						'search' => esc_html__( 'Search', 'bricks' ),
						'author' => esc_html__( 'Author', 'bricks' ),
						'date' => esc_html__( 'Date', 'bricks' ),
						'wc' => esc_html__( 'Shop/Product Category/Product Tag', 'bricks' ),
					],
					'placeholder' => esc_html__( 'Category', 'bricks' )
				],
			];

		}

		if ( get_option( self::$prefix . 'authored_by_logged_in_user' ) ) {

			// Authored by Logged-In User
			$options[] = [
				'key'   => 'x_authored_by_logged_in_user', 
				'label' => esc_html__( 'Authored by logged-in user', 'bricks' ),
				'group' => 'x_extras_conditions',
				'compare' => [
					'type'        => 'select',
					'options'     =>  [
						'==' => esc_html__( 'is', 'bricks' ),
					],
					'placeholder' => esc_html__( 'is', 'bricks' )
				],
				'value'   => [
					'type'        => 'select',
					'options' => [
						'true' => esc_html__( 'True', 'bricks' ),
						'false' => esc_html__( 'False', 'bricks' ),
					],
					'placeholder' => esc_html__( 'True', 'bricks' )
				]
			];

		}

		if ( get_option( self::$prefix . 'post_ancestor' ) ) {

			// Post Ancestor
			$options[] = [
				'key'   => 'x_post_ancestor', 
				'label' => esc_html__( 'Post ancestor', 'bricks' ),
				'group' => 'x_extras_conditions',
				'compare' => [
					'type'        => 'select',
					'options'     =>  [
						'==' => esc_html__( '==', 'bricks' ),
						'!=' => esc_html__( '!=', 'bricks' ),
					],
					'placeholder' => esc_html__( '==', 'bricks' )
				],
				'value'   => [
					'type'        => 'text',
				],
			];

		}

		if ( get_option( self::$prefix . 'post_type' ) ) {

			// Post Ancestor
			$options[] = [
				'key'   => 'x_post_type', 
				'label' => esc_html__( 'Post type', 'bricks' ),
				'group' => 'x_extras_conditions',
				'compare' => [
					'type'        => 'select',
					'options'     =>  [
						'==' => esc_html__( '==', 'bricks' ),
						'!=' => esc_html__( '!=', 'bricks' ),
					],
					'placeholder' => esc_html__( '!=', 'bricks' )
				],
				'value'   => [
					'type'        => 'text',
				],
			];

		}

		if ( get_option( self::$prefix . 'post_comment_count' ) ) {

			// Post Comment Count
			$options[] = [
				'key'   => 'x_post_comment_count', 
				'label' => esc_html__( 'Post comment count', 'bricks' ),
				'group' => 'x_extras_conditions',
				'compare' => [
					'type'        => 'select',
					'options'     =>  Helpers::getMathsCompareOptions(),
					'placeholder' => esc_html__( '==', 'bricks' )
				],
				'value'   => [
					'type'        => 'text',
					'placeholder' => esc_html__( '0', 'bricks' )
				],
			];

		}

		if ( get_option( self::$prefix . 'post_category' ) ) {

			$post_categories_array = [];

			if ( bricks_is_builder() ) {

				// Post Category
				$post_categories = get_categories( [ 'hide_empty' => false ] );
				
				foreach ( $post_categories as $post_category ) {
					$post_categories_array[ $post_category->term_id ] = $post_category->name;
				}

			}

			$options[] = [
				'key'   => 'x_post_category', 
				'label' => esc_html__( 'Post category', 'bricks' ),
				'group' => 'x_extras_conditions',
				'compare' => [
					'type'        => 'select',
					'options'     =>  [
						'==' => esc_html__( '==', 'bricks' ),
						'!=' => esc_html__( '!=', 'bricks' ),
					],
					'placeholder' => esc_html__( '==', 'bricks' )
				],
				'value'   => [
					'type'        => 'select',
					'options' => $post_categories_array,
					'placeholder' => esc_html__( 'Select category...', 'bricks' )
				],
			];

		}

		if ( get_option( self::$prefix . 'post_tag' ) ) {

			$post_tags_array = [];

			if ( bricks_is_builder() ) {

				// Post Tag
				$post_tags = get_tags( [ 'hide_empty' => false ] );
				
				foreach ( $post_tags as $post_tag ) {
					$post_tags_array[ $post_tag->term_id ] = $post_tag->name;
				}

			}

			$options[] = [
				'key'   => 'x_post_tag', 
				'label' => esc_html__( 'Post tag', 'bricks' ),
				'group' => 'x_extras_conditions',
				'compare' => [
					'type'        => 'select',
					'options'     =>  [
						'==' => esc_html__( '==', 'bricks' ),
					],
					'placeholder' => esc_html__( '==', 'bricks' )
				],
				'value'   => [
					'type'        => 'select',
					'options' => $post_tags_array,
					'placeholder' => esc_html__( 'Select tag...', 'bricks' )
				],
			];

		}

		if ( get_option( self::$prefix . 'is_child' ) ) {

			// Is Child
			$options[] = [
				'key'   => 'x_is_child', 
				'label' => esc_html__( 'Is child', 'bricks' ),
				'group' => 'x_extras_conditions',
				'compare' => [
					'type'        => 'select',
					'options'     =>  [
						'==' => esc_html__( '==', 'bricks' ),
					],
					'placeholder' => esc_html__( '==', 'bricks' )
				],
				'value'   => [
					'type'        => 'select',
					'options' => [
						'true' => esc_html__( 'True', 'bricks' ),
						'false' => esc_html__( 'False', 'bricks' ),
					],
					'placeholder' => esc_html__( 'True', 'bricks' )
				],
			];

		}

		if ( get_option( self::$prefix . 'is_parent' ) ) {

			// Is Parent
			$options[] = [
				'key'   => 'x_is_parent', 
				'label' => esc_html__( 'Is parent', 'bricks' ),
				'group' => 'x_extras_conditions',
				'compare' => [
					'type'        => 'select',
					'options'     =>  [
						'==' => esc_html__( '==', 'bricks' ),
					],
					'placeholder' => esc_html__( '==', 'bricks' )
				],
				'value'   => [
					'type'        => 'select',
					'options' => [
						'true' => esc_html__( 'True', 'bricks' ),
						'false' => esc_html__( 'False', 'bricks' ),
					],
					'placeholder' => esc_html__( 'True', 'bricks' )
				],
			];
		
		}

		if ( get_option( self::$prefix . 'in_favorites_loop' ) ) {

			// Is Parent
			$options[] = [
				'key'   => 'x_in_favorites_loop', 
				'label' => esc_html__( 'In favorites loop', 'bricks' ),
				'group' => 'x_extras_conditions',
				'compare' => [
					'type'        => 'select',
					'options'     =>  [
						'==' => esc_html__( '==', 'bricks' ),
					],
					'placeholder' => esc_html__( '==', 'bricks' )
				],
				'value'   => [
					'type'        => 'select',
					'options' => [
						'true' => esc_html__( 'True', 'bricks' ),
						'false' => esc_html__( 'False', 'bricks' ),
					],
					'placeholder' => esc_html__( 'True', 'bricks' )
				],
			];
		
		}

		

		if ( get_option( self::$prefix . 'language_visitor' ) ) {

			// Language (visitor)
			$options[] = [
				'key'   => 'x_language_visitor', 
				'label' => esc_html__( 'Language (visitor)', 'bricks' ),
				'group' => 'x_extras_conditions',
				'compare' => [
					'type'        => 'select',
					'options'     =>  [
						'==' => esc_html__( '==', 'bricks' ),
						'!=' => esc_html__( '!=', 'bricks' ),
					],
					'placeholder' => esc_html__( '==', 'bricks' )
				],
				'value'   => [
					'type'        => 'text',
					'placeholder' => esc_html__( 'en-US', 'bricks' )
				],
			];

		}

		if ( get_option( self::$prefix . 'category_archive' ) ) {

			$post_categories_array2 = [];

			if ( bricks_is_builder() ) {

				// Category Archive
				$post_categories2 = get_categories( [ 'hide_empty' => false ] );
				
				foreach ( $post_categories2 as $post_category2 ) {
					$post_categories_array2[ $post_category2->term_id ] = $post_category2->name;
				}

			}

			$options[] = [
				'key'   => 'x_category_archive', 
				'label' => esc_html__( 'Category archive', 'bricks' ),
				'group' => 'x_extras_conditions',
				'compare' => [
					'type'        => 'select',
					'options'     =>  [
						'==' => esc_html__( '==', 'bricks' ),
						'!=' => esc_html__( '!=', 'bricks' ),
					],
					'placeholder' => esc_html__( '==', 'bricks' )
				],
				'value'   => [
					'type' => 'select',
					'options' => $post_categories_array2,
					'placeholder' => esc_html__( 'Select category...', 'bricks' )
				],
			];

		}

		if ( get_option( self::$prefix . 'tag_archive' ) ) {

			$post_tags_array2 = [];

			if ( bricks_is_builder() ) {

				// Tag Archive
				$post_tags2 = get_tags( [ 'hide_empty' => false ] );
				
				foreach ( $post_tags2 as $post_tag2 ) {
					$post_tags_array2[ $post_tag2->term_id ] = $post_tag2->name;
				}

			}

			$options[] = [
				'key'   => 'x_tag_archive', 
				'label' => esc_html__( 'Tag archive', 'bricks' ),
				'group' => 'x_extras_conditions',
				'compare' => [
					'type'        => 'select',
					'options'     =>  [
						'==' => esc_html__( '==', 'bricks' ),
						'!=' => esc_html__( '!=', 'bricks' ),
					],
					'placeholder' => esc_html__( '==', 'bricks' )
				],
				'value'   => [
					'type' => 'select',
					'options' => $post_tags_array2,
					'placeholder' => esc_html__( 'Select tag...', 'bricks' )
				],
			];

		}
		
		if ( get_option( self::$prefix . 'post_publish_date' ) ) {
			
			// Post publish date
			$options[] = [
				'key'   => 'x_post_publish_date', 
				'label' => esc_html__( 'Post publish date', 'bricks' ),
				'group' => 'x_extras_conditions',
				'compare' => [
					'type'        => 'select',
					'options'     =>  [
						'==' => esc_html__( 'is', 'bricks' ),
					],
					'placeholder' => esc_html__( 'is', 'bricks' )
				],
				'value'   => [
					'type' => 'select',
					'options' => [
						'past' => esc_html__( 'in the past', 'bricks' ),
						'today' => esc_html__( 'today', 'bricks' ),
					],
					'placeholder' => esc_html__( 'in the past', 'bricks' )
				],
			];
			
		}

		if ( get_option( self::$prefix . 'page_type' ) ) {
			// Normal ones
			$options_normal = [
				'front_page' => esc_html__( 'Front page', 'bricks' ),
				'blog' => esc_html__( 'Posts page (blog)', 'bricks' ),
				'page' => esc_html__( 'Static Page', 'bricks' ),
				'post' => esc_html__( 'Single post', 'bricks' ),
				'singular' => esc_html__( 'Singular', 'bricks' ),
				'single' => esc_html__( 'Single', 'bricks' ),
				'archive' => esc_html__( 'Archive', 'bricks' ),
				'category' => esc_html__( 'Category archive', 'bricks' ),
				'tag' => esc_html__( 'Tag archive', 'bricks' ),
				'search' => esc_html__( 'Search results', 'bricks' ),
				'author' => esc_html__( 'Author archive', 'bricks' ),
				'tax' => esc_html__( 'Taxonomy archive', 'bricks' ),
				'error' => esc_html__( '404', 'bricks' ),
				'date' => esc_html__( 'Date archive', 'bricks' )
			];

			// WooCommerce specific
			$options_wc = [
				'wc' => esc_html__( 'WooCommerce', 'bricks' ),
				'wc_shop' => esc_html__( 'WC Shop', 'bricks' ),
				'wc_product_category' => esc_html__( 'WC Product category', 'bricks' ),
				'wc_product_tag' => esc_html__( 'WC Product tag', 'bricks' ),
				'wc_product' => esc_html__( 'WC Single product', 'bricks' ),
				'wc_cart' => esc_html__( 'WC Cart', 'bricks' ),
				'wc_checkout' => esc_html__( 'WC Checkout', 'bricks' ),
				'wc_account' => esc_html__( 'WC Account', 'bricks' ),
				'wc_endpoint' => esc_html__( 'WC Endpoint', 'bricks' ),
				'wc_order_pay' => esc_html__( 'WC Order pay', 'bricks' ),
				'wc_order_received' => esc_html__( 'WC Order received', 'bricks' ),
				'wc_view_order' => esc_html__( 'WC View order', 'bricks' ),
				'wc_edit_account' => esc_html__( 'WC Edit account', 'bricks' ),
				'wc_edit_address' => esc_html__( 'WC Edit address', 'bricks' ),
				'wc_lost_password' => esc_html__( 'WC Lost password', 'bricks' ),
				'wc_customer_logout' => esc_html__( 'WC Customer logout', 'bricks' ),
				'wc_add_payment_method' => esc_html__( 'WC Add payment method', 'bricks' )
			];

			$optionsArr = class_exists( '\WooCommerce' ) ? array_merge( $options_normal, $options_wc ) : $options_normal;

			// Page type
			$options[] = [
				'key'   => 'x_page_type', 
				'label' => esc_html__( 'Page type', 'bricks' ),
				'group' => 'x_extras_conditions',
				'compare' => [
					'type'        => 'select',
					'options'     =>  [
						'==' => esc_html__( 'is', 'bricks' ),
						'!=' => esc_html__( 'is not', 'bricks' ),
					],
					'placeholder' => esc_html__( 'is', 'bricks' )
				],
				'value'   => [
					'type' => 'select',
					'options' => $optionsArr,
					'placeholder' => esc_html__( 'Page', 'bricks' )
				],
			];

		}

		if ( get_option( self::$prefix . 'wpml_language' ) && defined( 'ICL_SITEPRESS_VERSION' ) ) {

			$languages = apply_filters( 'wpml_active_languages', NULL, 'orderby=name&order=asc' );

			$languages_array = [];

			foreach( $languages as $language ) {
					$languages_array[$language['language_code']] = $language['translated_name'];
			}

			$options[] = [
				'key'   => 'x_wpml_language', 
				'label' => esc_html__( 'WPML language', 'bricks' ),
				'group' => 'x_extras_conditions',
				'compare' => [
					'type'        => 'select',
					'options'     =>  [
						'==' => esc_html__( 'is', 'bricks' ),
						'!=' => esc_html__( 'is not', 'bricks' ),
					],
					'placeholder' => esc_html__( 'is', 'bricks' )
				],
				'value'   => [
					'type' => 'select',
					'options' => $languages_array,
					'placeholder' => $languages_array[apply_filters( 'wpml_default_language', NULL )]
				],
			];

		}

		if ( get_option( self::$prefix . 'page_parent' ) ) {
			
			// array of all Pages of the current Page as ID => Page title
			$parentPages = Helpers::getAllPostOptions( 'page' );

			// Filter the above array to remove posts that do not have children
			$parentPages = array_filter( $parentPages, function( $title, $id ) {
				return count( get_pages( [ 'child_of' => $id ] ) ) !== 0;
			}, ARRAY_FILTER_USE_BOTH );

			$options[] = [
				'key'   => 'x_page_parent', 
				'label' => esc_html__( 'Page parent', 'bricks' ),
				'group' => 'x_extras_conditions',
				'compare' => [
					'type'        => 'select',
						'options'     =>  [
							'==' => esc_html__( 'is', 'bricks' ),
							'!=' => esc_html__( 'is not', 'bricks' ),
						],
						'placeholder' => esc_html__( 'is', 'bricks' ),
					],
				'value'   => [
					'type' => 'select',
					'options' => $parentPages,
					'placeholder' => esc_html__( 'Select Page...', 'bricks' ),
				],
			];

		}

		// Polylang
		if ( get_option( self::$prefix . 'polylang_language' ) && function_exists( 'pll_count_posts' ) ) {

			$polylangArr = array_combine( pll_languages_list(), pll_languages_list( [ 'fields' => 'name' ] ) );

			$options[] = [
				'key'   => 'x_polylang_language', 
				'label' => esc_html__( 'Polylang language', 'bricks' ),
				'group' => 'x_extras_conditions',
				'compare' => [
					'type'        => 'select',
					'options'     =>  [
						'==' => esc_html__( 'is', 'bricks' ),
						'!=' => esc_html__( 'is not', 'bricks' ),
					],
					'placeholder' => esc_html__( 'is', 'bricks' )
				],
				'value'   => [
					'type' => 'select',
					'options' => $polylangArr,
					'placeholder' => pll_default_language( 'name' )
				],
			];

		}

		// TranslatePress
		if ( get_option( self::$prefix . 'translatepress_language' ) && class_exists( '\TRP_Translate_Press' ) ) {

			$trp = TRP_Translate_Press::get_trp_instance();

			$trp_settings = $trp->get_component( 'settings' );

			$language_codes_array = $trp_settings->get_settings()['publish-languages'];

			$language_codes_array = array_combine( $language_codes_array, $language_codes_array );

			$options[] = [
				'key'   => 'x_translatepress_language', 
				'label' => esc_html__( 'TranslatePress language', 'bricks' ),
				'group' => 'x_extras_conditions',
				'compare' => [
					'type'        => 'select',
					'options'     =>  [
						'==' => esc_html__( 'is', 'bricks' ),
						'!=' => esc_html__( 'is not', 'bricks' ),
					],
					'placeholder' => esc_html__( 'is', 'bricks' )
				],
				'value'   => [
					'type' => 'select',
					'options' => $language_codes_array,
					'placeholder' => array_values( $language_codes_array )[0]
				],
			];

		}

		// Published During Last
		if ( get_option( self::$prefix . 'published_during_last' ) ) {
			
			$options[] = [
				'key'   => 'x_published_during_last', 
				'label' => esc_html__( 'Published during the last', 'bricks' ),
				'group' => 'x_extras_conditions',
				'compare' => [
					'type'        => 'select',
						'options'     =>  [
							'==' => esc_html__( '==', 'bricks' ),
							'!=' => esc_html__( '!=', 'bricks' ),
						],
						'placeholder' => esc_html__( '==', 'bricks' ),
					],
				'value'   => [
					'type' => 'select',
					'options' => [
						'day' => esc_html__( '1 day', 'bricks' ),
						'week' => esc_html__( '1 week', 'bricks' ),
						'month' => esc_html__( '1 month', 'bricks' ),
						'year' => esc_html__( '1 year', 'bricks' ),
					],
					'placeholder' => esc_html__( 'Select a period...', 'bricks' ),
				],
			];

		}

		// Loop item number
		if ( get_option( self::$prefix . 'loop_item_number' ) ) {

			$options[] = [
				'key'   => 'x_loop_item_number', 
				'label' => esc_html__( 'Loop item number', 'bricks' ),
				'group' => 'x_extras_conditions',
				'compare' => [
					'type'        => 'select',
					'options'     =>  array_merge( Helpers::getMathsCompareOptions(), [
						'every' => esc_html__( 'every', 'bricks' )
					]),
					'placeholder' => esc_html__( '==', 'bricks' )
				],
				'value'   => [
					'type'        => 'text',
					'placeholder' => esc_html__( '1', 'bricks' )
				],
			];

		}
		
		// Current Month
		if ( get_option( self::$prefix . 'current_month' ) ) {

			$options[] = [
				'key'   => 'x_current_month', 
				'label' => esc_html__( 'Current month', 'bricks' ),
				'group' => 'x_extras_conditions',
				'compare' => [
					'type'        => 'select',
					'options'     =>  [
						'==' => esc_html__( 'is', 'bricks' ),
						'!=' => esc_html__( 'is not', 'bricks' ),
					],
					'placeholder' => esc_html__( 'is', 'bricks' )
				],
				'value'   => [
					'type'        => 'select',
					'options'     => [
						'January' => esc_html__( 'January', 'bricks' ),
						'February' => esc_html__( 'February', 'bricks' ),
						'March' => esc_html__( 'March', 'bricks' ),
						'April' => esc_html__( 'April', 'bricks' ),
						'May' => esc_html__( 'May', 'bricks' ),
						'June' => esc_html__( 'June', 'bricks' ),
						'July' => esc_html__( 'July', 'bricks' ),
						'August' => esc_html__( 'August', 'bricks' ),
						'September' => esc_html__( 'September', 'bricks' ),
						'October' => esc_html__( 'October', 'bricks' ),
						'November' => esc_html__( 'November', 'bricks' ),
						'December' => esc_html__( 'December', 'bricks' )
					],
					'placeholder' => esc_html__( wp_date( 'F' ), 'bricks' )
				],
			];

		}

		// Current Day
		if ( get_option( self::$prefix . 'current_day' ) ) {

			$options[] = [
				'key'   => 'x_current_day', 
				'label' => esc_html__( 'Current day of month', 'bricks' ),
				'group' => 'x_extras_conditions',
				'compare' => [
					'type'        => 'select',
					'options'     =>  [
						'==' => esc_html__( 'is', 'bricks' ),
						'!=' => esc_html__( 'is not', 'bricks' ),
					],
					'placeholder' => esc_html__( 'is', 'bricks' )
				],
				'value'   => [
					'type'        => 'select',
					'options'     => [
						'1' => esc_html__( '1', 'bricks' ),
						'2' => esc_html__( '2', 'bricks' ),
						'3' => esc_html__( '3', 'bricks' ),
						'4' => esc_html__( '4', 'bricks' ),
						'5' => esc_html__( '5', 'bricks' ),
						'6' => esc_html__( '6', 'bricks' ),
						'7' => esc_html__( '7', 'bricks' ),
						'8' => esc_html__( '8', 'bricks' ),
						'9' => esc_html__( '9', 'bricks' ),
						'10' => esc_html__( '10', 'bricks' ),
						'11' => esc_html__( '11', 'bricks' ),
						'12' => esc_html__( '12', 'bricks' ),
						'13' => esc_html__( '13', 'bricks' ),
						'14' => esc_html__( '14', 'bricks' ),
						'15' => esc_html__( '15', 'bricks' ),
						'16' => esc_html__( '16', 'bricks' ),
						'17' => esc_html__( '17', 'bricks' ),
						'18' => esc_html__( '18', 'bricks' ),
						'19' => esc_html__( '19', 'bricks' ),
						'20' => esc_html__( '20', 'bricks' ),
						'21' => esc_html__( '21', 'bricks' ),
						'22' => esc_html__( '22', 'bricks' ),
						'23' => esc_html__( '23', 'bricks' ),
						'24' => esc_html__( '24', 'bricks' ),
						'25' => esc_html__( '25', 'bricks' ),
						'26' => esc_html__( '26', 'bricks' ),
						'27' => esc_html__( '27', 'bricks' ),
						'28' => esc_html__( '28', 'bricks' ),
						'29' => esc_html__( '29', 'bricks' ),
						'30' => esc_html__( '30', 'bricks' ),
						'31' => esc_html__( '31', 'bricks' )
					],
					'placeholder' => esc_html__( wp_date( 'j' ), 'bricks' )
				],
			];

		}
		
		// Current Year
		if ( get_option( self::$prefix . 'current_year' ) ) {

			$options[] = [
				'key'   => 'x_current_year', 
				'label' => esc_html__( 'Current year', 'bricks' ),
				'group' => 'x_extras_conditions',
				'compare' => [
					'type'        => 'select',
					'options'     =>  [
						'==' => esc_html__( 'is', 'bricks' ),
						'!=' => esc_html__( 'is not', 'bricks' ),
					],
					'placeholder' => esc_html__( 'is', 'bricks' )
				],
				'value'   => [
					'type'        => 'text',
					'placeholder' => esc_html__( wp_date( 'Y' ), 'bricks' )
				],
			];

		}

		// Current Taxonomy Term Has Child
		if ( get_option( self::$prefix . 'current_taxonomy_term_has_child' ) ) {

			$options[] = [
				'key'   => 'x_current_taxonomy_term_has_child', 
				'label' => esc_html__( 'Current Taxonomy Term Has Child', 'bricks' ),
				'group' => 'x_extras_conditions',
				'compare' => [
					'type'        => 'select',
					'options'     =>  [
						'==' => esc_html__( '==', 'bricks' ),
					],
					'placeholder' => esc_html__( '==', 'bricks' ),
				],
				'value'   => [
					'type' => 'select',
					'options' => [
						'true' => esc_html__( 'True', 'bricks' ),
						'false' => esc_html__( 'False', 'bricks' ),
					],
					'placeholder' => esc_html__( 'True', 'bricks' ),
				],
			];

		}

		// Current Taxonomy Term Has Parent
		if ( get_option( self::$prefix . 'current_taxonomy_term_has_parent' ) ) {

			$options[] = [
				'key'   => 'x_current_taxonomy_term_has_parent', 
				'label' => esc_html__( 'Current Taxonomy Term Has Parent', 'bricks' ),
				'group' => 'x_extras_conditions',
				'compare' => [
					'type'        => 'select',
					'options'     =>  [
						'==' => esc_html__( '==', 'bricks' ),
					],
					'placeholder' => esc_html__( '==', 'bricks' ),
				],
				'value'   => [
					'type' => 'select',
					'options' => [
						'true' => esc_html__( 'True', 'bricks' ),
						'false' => esc_html__( 'False', 'bricks' ),
					],
					'placeholder' => esc_html__( 'True', 'bricks' ),
				],
			];

		}

		

		// Body classes
		if ( get_option( self::$prefix . 'body_classes' ) ) {

			$options[] = [
				'key'   => 'x_body_classes', 
				'label' => esc_html__( 'Body classes', 'bricks' ),
				'group' => 'x_extras_conditions',
				'compare' => [
					'type'        => 'select',
					'options'     =>  [
						'==' => esc_html__( 'include', 'bricks' ),
						'!=' => esc_html__( 'do not include', 'bricks' ),
					],
					'placeholder' => esc_html__( 'include', 'bricks' ),
				],
				'value'   => [
					'type' => 'text',
					'placeholder' => esc_html__( 'Enter a single class name', 'bricks' ),
				],
			];

		}
		
		// Has custom excerpt
		if ( get_option( self::$prefix . 'has_custom_excerpt' ) ) {

			$options[] = [
				'key'   => 'x_has_custom_excerpt', 
				'label' => esc_html__( 'Has custom excerpt', 'bricks' ),
				'group' => 'x_extras_conditions',
				'compare' => [
					'type'        => 'select',
					'options'     =>  [
						'==' => esc_html__( 'is', 'bricks' ),
					],
					'placeholder' => esc_html__( 'is', 'bricks' ),
				],
				'value'   => [
					'type' => 'select',
					'options' => [
						'true' => esc_html__( 'True', 'bricks' ),
						'false' => esc_html__( 'False', 'bricks' ),
					],
					'placeholder' => esc_html__( 'True', 'bricks' ),
				],
			];

		}
		
		// Has post content
		if ( get_option( self::$prefix . 'has_post_content' ) ) {

			$options[] = [
				'key'   => 'x_has_post_content', 
				'label' => esc_html__( 'Has post content', 'bricks' ),
				'group' => 'x_extras_conditions',
				'compare' => [
					'type'        => 'select',
					'options'     =>  [
						'==' => esc_html__( 'is', 'bricks' ),
					],
					'placeholder' => esc_html__( 'is', 'bricks' ),
				],
				'value'   => [
					'type' => 'select',
					'options' => [
						'true' => esc_html__( 'True', 'bricks' ),
						'false' => esc_html__( 'False', 'bricks' ),
					],
					'placeholder' => esc_html__( 'True', 'bricks' ),
				],
			];

		}
		
		// Has child category
		if ( get_option( self::$prefix . 'has_child_category' ) ) {

			$options[] = [
				'key'   => 'x_has_child_category', 
				'label' => esc_html__( 'Has child category', 'bricks' ),
				'group' => 'x_extras_conditions',
				'compare' => [
					'type'        => 'select',
					'options'     =>  [
						'==' => esc_html__( 'is', 'bricks' ),
					],
					'placeholder' => esc_html__( 'is', 'bricks' ),
				],
				'value'   => [
					'type' => 'select',
					'options' => [
						'true' => esc_html__( 'True', 'bricks' ),
						'false' => esc_html__( 'False', 'bricks' ),
					],
					'placeholder' => esc_html__( 'False', 'bricks' ),
				],
			];

		}
		
		// Date field value
		if ( get_option( self::$prefix . 'date_field_value' ) ) {

			$options[] = [
				'key'   => 'x_date_field_value',
				'label' => esc_html__( 'Date field value', 'bricks' ),
				'group' => 'x_extras_conditions',
				'compare' => [
					'type'        => 'select',
					'options' => [
						'past' => esc_html__( 'is in the past', 'bricks' ),
						'today' => esc_html__( 'is today', 'bricks' ),
						'future' => esc_html__( 'is in the future', 'bricks' ),
					],
					'placeholder' => esc_html__( 'is in the future', 'bricks' ),
				],
				'value'   => [
					'type' => 'text',
					'placeholder' => esc_html__( 'Select custom field', 'bricks' ),
				],
			];

		}
		
		// Datetime field value
		if ( get_option( self::$prefix . 'datetime_field_value' ) ) {

			$options[] = [
				'key'   => 'x_datetime_field_value',
				'label' => esc_html__( 'Datetime field value', 'bricks' ),
				'group' => 'x_extras_conditions',
				'compare' => [
					'type'        => 'select',
					'options' => [
						'past' => esc_html__( 'is in the past', 'bricks' ),
						'future' => esc_html__( 'is in the future', 'bricks' ),
					],
					'placeholder' => esc_html__( 'is in the future', 'bricks' ),
				],
				'value'   => [
					'type' => 'text',
					'placeholder' => esc_html__( 'Select custom field', 'bricks' ),
				],
			];

		}

		// Author has CPT entry
		if ( get_option( self::$prefix . 'author_has_cpt_entry' ) ) {

			$options[] = [
				'key'   => 'x_author_has_cpt_entry',
				'label' => esc_html__( 'Author has CPT entry', 'bricks' ),
				'group' => 'x_extras_conditions',
				'compare' => [
					'type'        => 'select',
					'options' => [
						'==' => esc_html__( '==', 'bricks' ),
						'!=' => esc_html__( '!=', 'bricks' ),
					],
					'placeholder' => esc_html__( '==', 'bricks' ),
				],
				'value'   => [
					'type' => 'select',
					'options' => Helpers::getPostTypes(),
					'placeholder' => esc_html__( 'Select custom post type', 'bricks' ),
				],
			];

		}

		// CPT has at least 1 published entry
		if ( get_option( self::$prefix . 'cpt_has_at_least_1_entry' ) ) {

			$options[] = [
				'key'   => 'x_cpt_has_at_least_1_entry',
				'label' => esc_html__( 'At least 1 entry exists', 'bricks' ),
				'group' => 'x_extras_conditions',
				'compare' => [
					'type'        => 'select',
					'options' => [
						'==' => esc_html__( 'for this CPT', 'bricks' ),
					],
					'placeholder' => esc_html__( 'for this CPT', 'bricks' ),
				],
				'value'   => [
					'type' => 'select',
					'options' => Helpers::getPostTypes(),
					'placeholder' => esc_html__( 'Select custom post type', 'bricks' ),
				],
			];

		}

		/* WooCommerce conditions */

		if ( get_option( self::$prefix . 'cart_empty' ) && class_exists( '\WooCommerce' ) ) {

			// Cart empty
			$options[] = [
				'key'   => 'x_cart_empty', 
				'label' => esc_html__( 'Cart empty', 'bricks' ),
				'group' => 'x_wc_conditions',
				'compare' => [
					'type'        => 'select',
					'options'     =>  [
						'==' => esc_html__( 'is', 'bricks' ),
						'!=' => esc_html__( 'is not', 'bricks' ),
					],
					'placeholder' => esc_html__( 'is', 'bricks' )
				],
				'value'   => [
					'type'        => 'select',
					'options' => [
						'empty' => esc_html__( 'empty', 'bricks' ),
						'not_empty' => esc_html__( 'not empty', 'bricks' ),
					],
					'placeholder' => esc_html__( 'Select an option...', 'bricks' )
				],
			];
					
		}

		// Product in Cart
		if ( get_option( self::$prefix . 'product_in_cart' ) && class_exists( '\WooCommerce' ) ) {
			$options[] = [
				'key'   => 'x_product_in_cart', 
				'label' => esc_html__( 'Product in cart', 'bricks' ),
				'group' => 'x_wc_conditions',
				'compare' => [
					'type'        => 'select',
					'options'     =>  [
						'==' => esc_html__( 'is', 'bricks' ),
						'!=' => esc_html__( 'is not', 'bricks' ),
					],
					'placeholder' => esc_html__( 'is', 'bricks' )
				],
				'value'   => [
					'type'        => 'select',
					'options' => Helpers::getAllPostOptions( 'product' ),
					'placeholder' => esc_html__( 'Select product...', 'bricks' )
				],
			];
		}

		// user purchased product
		if ( get_option( self::$prefix . 'user_purchased_product' ) && class_exists( '\WooCommerce' ) ) {
			$options[] = [
				'key'   => 'x_user_purchased_product', 
				'label' => esc_html__( 'User purchased product', 'bricks' ),
				'group' => 'x_wc_conditions',
				'compare' => [
					'type'        => 'select',
					'options'     =>  [
						'==' => esc_html__( 'has purchased', 'bricks' ),
						'!=' => esc_html__( 'has not purchased', 'bricks' ),
					],
					'placeholder' => esc_html__( 'has purchased', 'bricks' )
				],
				'value'   => [
					'type'        => 'select',
					'options' => Helpers::getAllPostOptions( 'product' ),
					'placeholder' => esc_html__( 'Select product...', 'bricks' )
				],
			];
		}

		// cart total
		if ( get_option( self::$prefix . 'cart_total' ) && class_exists( '\WooCommerce' ) ) {

			$options[] = [
				'key'   => 'x_cart_total', 
				'label' => esc_html__( 'Cart total (' . get_option('woocommerce_currency') . ')', 'bricks' ),
				'group' => 'x_wc_conditions',
				'compare' => [
					'type'        => 'select',
					'options'     =>  Helpers::getMathsCompareOptions(),
					'placeholder' => esc_html__( '==', 'bricks' )
				],
				'value'   => [
					'type'        => 'text',
					'placeholder' => esc_html__( '0', 'bricks' )
				],
			];

		}

		// cart total minus shipping
		if ( get_option( self::$prefix . 'cart_total_minus_shipping' ) && class_exists( '\WooCommerce' ) ) {

			$options[] = [
				'key'   => 'x_cart_total_minus_shipping', 
				'label' => esc_html__( 'Cart total (' . get_option('woocommerce_currency') . ') excl. shipping', 'bricks' ),
				'group' => 'x_wc_conditions',
				'compare' => [
					'type'        => 'select',
					'options'     =>  Helpers::getMathsCompareOptions(),
					'placeholder' => esc_html__( '==', 'bricks' )
				],
				'value'   => [
					'type'        => 'text',
					'placeholder' => esc_html__( '0', 'bricks' )
				],
			];

		}

		// cart weight
		if ( get_option( self::$prefix . 'cart_weight' ) && class_exists( '\WooCommerce' ) ) {

			$options[] = [
				'key'   => 'x_cart_weight', 
				'label' => esc_html__( 'Cart weight (' . get_option('woocommerce_weight_unit') . ')', 'bricks' ),
				'group' => 'x_wc_conditions',
				'compare' => [
					'type'        => 'select',
					'options'     =>  Helpers::getMathsCompareOptions(),
					'placeholder' => esc_html__( '==', 'bricks' )
				],
				'value'   => [
					'type'        => 'text',
					'placeholder' => esc_html__( '0', 'bricks' )
				],
			];

		}

		// product is virtual
		if ( get_option( self::$prefix . 'product_is_virtual' ) && class_exists( '\WooCommerce' ) ) {

			$options[] = [
				'key'   => 'x_product_is_virtual', 
				'label' => esc_html__( 'Product is virtual', 'bricks' ),
				'group' => 'x_wc_conditions',
				'compare' => [
					'type'        => 'select',
					'options'     =>  [
						'==' => esc_html__( '==', 'bricks' ),
					],
					'placeholder' => esc_html__( '==', 'bricks' )
				],
				'value'   => [
					'type'        => 'select',
					'options' => [
						'true' => esc_html__( 'True', 'bricks' ),
						'false' => esc_html__( 'False', 'bricks' ),
					],
					'placeholder' => esc_html__( 'True', 'bricks' )
				],
			];
		
		}

		// product is downloadable
		if ( get_option( self::$prefix . 'product_is_downloadable' ) && class_exists( '\WooCommerce' ) ) {

			$options[] = [
				'key'   => 'x_product_is_downloadable', 
				'label' => esc_html__( 'Product is downloadable', 'bricks' ),
				'group' => 'x_wc_conditions',
				'compare' => [
					'type'        => 'select',
					'options'     =>  [
						'==' => esc_html__( '==', 'bricks' ),
					],
					'placeholder' => esc_html__( '==', 'bricks' )
				],
				'value'   => [
					'type'        => 'select',
					'options' => [
						'true' => esc_html__( 'True', 'bricks' ),
						'false' => esc_html__( 'False', 'bricks' ),
					],
					'placeholder' => esc_html__( 'True', 'bricks' )
				],
			];
		
		}

		// purchased current product
		if ( get_option( self::$prefix . 'user_purchased_current_product' ) && class_exists( '\WooCommerce' ) ) {

			$options[] = [
				'key'   => 'x_user_purchased_current_product', 
				'label' => esc_html__( 'User purchased current product', 'bricks' ),
				'group' => 'x_wc_conditions',
				'compare' => [
					'type'        => 'select',
					'options'     =>  [
						'==' => esc_html__( '==', 'bricks' ),
					],
					'placeholder' => esc_html__( '==', 'bricks' )
				],
				'value'   => [
					'type'        => 'select',
					'options' => [
						'true' => esc_html__( 'True', 'bricks' ),
						'false' => esc_html__( 'False', 'bricks' ),
					],
					'placeholder' => esc_html__( 'True', 'bricks' )
				],
			];
		
		}
		
		// has pending order
		if ( get_option( self::$prefix . 'user_has_pending_order' ) && class_exists( '\WooCommerce' ) ) {

			$options[] = [
				'key'   => 'x_user_has_pending_order', 
				'label' => esc_html__( 'User has pending order', 'bricks' ),
				'group' => 'x_wc_conditions',
				'compare' => [
					'type'        => 'select',
					'options'     =>  [
						'==' => esc_html__( '==', 'bricks' ),
					],
					'placeholder' => esc_html__( '==', 'bricks' )
				],
				'value'   => [
					'type'        => 'select',
					'options' => [
						'true' => esc_html__( 'True', 'bricks' ),
						'false' => esc_html__( 'False', 'bricks' ),
					],
					'placeholder' => esc_html__( 'True', 'bricks' )
				],
			];
		
		}


		// product in stock
		if ( get_option( self::$prefix . 'product_in_stock' ) && class_exists( '\WooCommerce' ) ) {

			$options[] = [
				'key'   => 'x_product_in_stock', 
				'label' => esc_html__( 'Product in stock', 'bricks' ),
				'group' => 'x_wc_conditions',
				'compare' => [
					'type'        => 'select',
					'options'     =>  [
						'==' => esc_html__( '==', 'bricks' ),
					],
					'placeholder' => esc_html__( '==', 'bricks' )
				],
				'value'   => [
					'type'        => 'select',
					'options' => [
						'true' => esc_html__( 'True', 'bricks' ),
						'false' => esc_html__( 'False', 'bricks' ),
					],
					'placeholder' => esc_html__( 'True', 'bricks' )
				],
			];
		
		}

		
		// product weight
		if ( get_option( self::$prefix . 'product_weight' ) && class_exists( '\WooCommerce' ) ) {

			$options[] = [
				'key'   => 'x_product_weight', 
				'label' => esc_html__( 'Product weight (' . get_option('woocommerce_weight_unit') . ')', 'bricks' ),
				'group' => 'x_wc_conditions',
				'compare' => [
					'type'        => 'select',
					'options'     =>  Helpers::getMathsCompareOptions(),
					'placeholder' => esc_html__( '==', 'bricks' )
				],
				'value'   => [
					'type'        => 'text',
					'placeholder' => esc_html__( '0', 'bricks' )
				],
			];
		
		}

		// product rating
		if ( get_option( self::$prefix . 'product_rating' ) && class_exists( '\WooCommerce' ) ) {

			$options[] = [
				'key'   => 'x_product_rating', 
				'label' => esc_html__( 'Product rating', 'bricks' ),
				'group' => 'x_wc_conditions',
				'compare' => [
					'type'        => 'select',
					'options'     =>  Helpers::getMathsCompareOptions(),
					'placeholder' => esc_html__( '==', 'bricks' )
				],
				'value'   => [
					'type'        => 'text',
					'placeholder' => esc_html__( '0', 'bricks' )
				],
			];
		
		}


		if ( get_option( self::$prefix . 'cart_count' ) && class_exists( '\WooCommerce' ) ) {

			// Cart count
			$options[] = [
				'key'   => 'x_cart_count', 
				'label' => esc_html__( 'Cart count (items)', 'bricks' ),
				'group' => 'x_wc_conditions',
				'compare' => [
					'type'        => 'select',
					'options'     =>  Helpers::getMathsCompareOptions(),
					'placeholder' => esc_html__( '==', 'bricks' )
				],
				'value'   => [
					'type'        => 'text',
					'placeholder' => esc_html__( '0', 'bricks' )
				],
			];
					
		}


		if ( get_option( self::$prefix . 'product_type' ) && class_exists( '\WooCommerce' ) ) {

			// product type
			$options[] = [
				'key'   => 'x_product_type', 
				'label' => esc_html__( 'Product type', 'bricks' ),
				'group' => 'x_wc_conditions',
				'compare' => [
					'type'        => 'select',
					'options'     =>  [
						'==' => esc_html__( 'is', 'bricks' ),
						'!=' => esc_html__( 'is not', 'bricks' ),
					],
					'placeholder' => esc_html__( 'is', 'bricks' )
				],
				'value'   => [
					'type'        => 'select',
					'options' => [
						'simple' => esc_html__( 'Simple', 'bricks' ),
						'grouped' => esc_html__( 'Grouped', 'bricks' ),
						'variable' => esc_html__( 'Variable', 'bricks' ),
						'external' => esc_html__( 'External / Affiliate', 'bricks' ),
					],
					'placeholder' => esc_html__( 'Select product type..', 'bricks' )
				],
			];
					
		}

		// product on backorder
		if ( get_option( self::$prefix . 'product_on_backorder' ) && class_exists( '\WooCommerce' ) ) {

			$options[] = [
				'key'   => 'x_product_on_backorder', 
				'label' => esc_html__( 'Product on backorder', 'bricks' ),
				'group' => 'x_wc_conditions',
				'compare' => [
					'type'        => 'select',
					'options'     =>  [
						'==' => esc_html__( '==', 'bricks' ),
					],
					'placeholder' => esc_html__( '==', 'bricks' )
				],
				'value'   => [
					'type'        => 'select',
					'options' => [
						'true' => esc_html__( 'True', 'bricks' ),
						'false' => esc_html__( 'False', 'bricks' ),
					],
					'placeholder' => esc_html__( 'True', 'bricks' )
				],
			];
		
		}


		// product upsells
		if ( get_option( self::$prefix . 'product_upsell_count' ) && class_exists( '\WooCommerce' ) ) {

			$options[] = [
				'key'   => 'x_product_upsell_count', 
				'label' => esc_html__( 'Product upsell count', 'bricks' ),
				'group' => 'x_wc_conditions',
				'compare' => [
					'type'        => 'select',
					'options'     =>  Helpers::getMathsCompareOptions(),
					'placeholder' => esc_html__( '>', 'bricks' )
				],
				'value'   => [
					'type'        => 'text',
					'placeholder' => esc_html__( '0', 'bricks' )
				],
			];

		}

		// product upsells
		if ( get_option( self::$prefix . 'product_crosssells_count' ) && class_exists( '\WooCommerce' ) ) {

			$options[] = [
				'key'   => 'x_product_crosssells_count', 
				'label' => esc_html__( 'Product cross-sells count', 'bricks' ),
				'group' => 'x_wc_conditions',
				'compare' => [
					'type'        => 'select',
					'options'     =>  Helpers::getMathsCompareOptions(),
					'placeholder' => esc_html__( '>', 'bricks' )
				],
				'value'   => [
					'type'        => 'text',
					'placeholder' => esc_html__( '0', 'bricks' )
				],
			];

		}

		// product backorders allowed
		if ( get_option( self::$prefix . 'product_backorders_allowed' ) && class_exists( '\WooCommerce' ) ) {

			$options[] = [
				'key'   => 'x_product_backorders_allowed', 
				'label' => esc_html__( 'Product allows backorders', 'bricks' ),
				'group' => 'x_wc_conditions',
				'compare' => [
					'type'        => 'select',
					'options'     =>  [
						'==' => esc_html__( '==', 'bricks' ),
					],
					'placeholder' => esc_html__( '==', 'bricks' )
				],
				'value'   => [
					'type'        => 'select',
					'options' => [
						'true' => esc_html__( 'True', 'bricks' ),
						'false' => esc_html__( 'False', 'bricks' ),
					],
					'placeholder' => esc_html__( 'True', 'bricks' )
				],
			];
		
		}
		
		// product has category
		if ( get_option( self::$prefix . 'product_has_category' ) && class_exists( '\WooCommerce' ) ) {

			$options[] = [
				'key'   => 'x_product_has_category', 
				'label' => esc_html__( 'Product has category', 'bricks' ),
				'group' => 'x_wc_conditions',
				'compare' => [
					'type'        => 'select',
					'options'     =>  [
						'==' => esc_html__( '==', 'bricks' ),
						'!=' => esc_html__( '!=', 'bricks' ),
					],
					'placeholder' => esc_html__( '==', 'bricks' )
				],
				'value'   => [
					'type' => 'select',
					'options' => Helpers::getProductTerms('product_cat'),
					'placeholder' => esc_html__( 'Select a product category...', 'bricks' )
				],
			];
		
		}
		
		// product has tag
		if ( get_option( self::$prefix . 'product_has_tag' ) && class_exists( '\WooCommerce' ) ) {

			$options[] = [
				'key'   => 'x_product_has_tag', 
				'label' => esc_html__( 'Product has tag', 'bricks' ),
				'group' => 'x_wc_conditions',
				'compare' => [
					'type'        => 'select',
					'options'     =>  [
						'==' => esc_html__( '==', 'bricks' ),
						'!=' => esc_html__( '!=', 'bricks' ),
					],
					'placeholder' => esc_html__( '==', 'bricks' )
				],
				'value'   => [
					'type' => 'select',
					'options' => Helpers::getProductTerms('product_tag'),
					'placeholder' => esc_html__( 'Select a product tag...', 'bricks' )
				],
			];
		
		}
		
		
		// Current Product in Cart
		if ( get_option( self::$prefix . 'current_product_in_cart' ) && class_exists( '\WooCommerce' ) ) {
			$options[] = [
				'key'   => 'x_current_product_in_cart', 
				'label' => esc_html__( 'Current product in cart', 'bricks' ),
				'group' => 'x_wc_conditions',
				'compare' => [
					'type'        => 'select',
					'options'     =>  [
						'==' => esc_html__( '==', 'bricks' ),
					],
					'placeholder' => esc_html__( '==', 'bricks' )
				],
				'value'   => [
					'type'        => 'select',
					'options' => [
						'true' => esc_html__( 'True', 'bricks' ),
						'false' => esc_html__( 'False', 'bricks' ),
					],
					'placeholder' => esc_html__( 'True', 'bricks' )
				],
			];
		}
		
		
		// Selected product in cart has a coupon applied
		if ( get_option( self::$prefix . 'product_in_cart_has_coupon' ) && class_exists( '\WooCommerce' ) ) {
			$options[] = [
				'key'   => 'x_product_in_cart_has_coupon', 
				'label' => esc_html__( 'Product in cart has a coupon applied', 'bricks' ),
				'group' => 'x_wc_conditions',
				'compare' => [
					'type' => 'select',
					'options' => [
						'==' => esc_html__( '==', 'bricks' ),
					],
					'placeholder' => esc_html__( '==', 'bricks' )
				],
				'value' => [
					'type'        => 'select',
					'options' => Helpers::getAllPostOptions( 'product' ),
					'placeholder' => esc_html__( 'Select product...', 'bricks' )
				],
			];
		}
		 

		return $options;
	}


	public static function run_conditions( $result, $condition_key, $condition ) {

		$condition_met = false;

		switch( $condition_key ) {

			case 'x_at_least_1_search_result':
				$condition_met = self::execute_x_at_least_1_search_result( $condition );
				break;

			case 'x_archive_type':
				$condition_met = self::execute_x_archive_type( $condition );
				break;

			case 'x_authored_by_logged_in_user':
				$condition_met = self::execute_x_authored_by_logged_in_user( $condition );
				break;
			
			case 'x_is_archive':
				$condition_met = self::execute_x_is_archive( $condition );
				break;

			case 'x_post_ancestor':
				$condition_met = self::execute_x_post_ancestor( $condition );
				break;

			case 'x_post_type':
				$condition_met = self::execute_x_post_type( $condition );
				break;
	
			case 'x_post_comment_count':
				$condition_met = self::execute_x_post_comment_count( $condition );
				break;

			case 'x_post_category':
				$condition_met = self::execute_x_post_category( $condition );
				break;

			case 'x_post_tag':
				$condition_met = self::execute_x_post_tag( $condition );
				break;

			case 'x_is_homepage':
				$condition_met = self::execute_x_is_homepage( $condition );
				break;
			
			case 'x_is_child':
				$condition_met = self::execute_x_is_child( $condition );
				break;
			
			case 'x_is_parent':
				$condition_met = self::execute_x_is_parent( $condition );
				break;
			case 'x_in_favorites_loop':
				$condition_met = self::execute_x_in_favorites_loop( $condition );
				break;
			case 'x_password_protect':
				$condition_met = self::execute_x_password_protect( $condition );
				break;
			
			case 'x_language_visitor':
				$condition_met = self::execute_x_language_visitor( $condition );
				break;
			
			case 'x_category_archive':
				$condition_met = self::execute_x_category_archive( $condition );
				break;
		
			case 'x_tag_archive':
				$condition_met = self::execute_x_tag_archive( $condition );
				break;

			case 'x_post_publish_date':
				$condition_met = self::execute_x_post_publish_date( $condition );
				break;
			
			case 'x_page_type':
				$condition_met = self::execute_x_page_type( $condition );
				break;
			
			case 'x_wpml_language':
				$condition_met = self::execute_x_wpml_language( $condition );
				break;
			
			case 'x_page_parent':
				$condition_met = self::execute_x_page_parent( $condition );
				break;

			case 'x_polylang_language':
				$condition_met = self::execute_x_polylang_language( $condition );
				break;
			
			case 'x_translatepress_language':
				$condition_met = self::execute_x_translatepress_language( $condition );
				break;
			
			case 'x_published_during_last':
				$condition_met = self::execute_x_published_during_last( $condition );
				break;
			
			case 'x_loop_item_number':
				$condition_met = self::execute_x_loop_item_number( $condition );
				break;
			
			case 'x_current_month':
				$condition_met = self::execute_x_current_month( $condition );
				break;

			case 'x_current_day':
				$condition_met = self::execute_x_current_day( $condition );
				break;
			
			case 'x_current_year':
				$condition_met = self::execute_x_current_year( $condition );
				break;

			case 'x_current_taxonomy_term_has_child':
				$condition_met = self::execute_x_current_taxonomy_term_has_child( $condition );
				break;

			case 'x_current_taxonomy_term_has_parent':
				$condition_met = self::execute_x_current_taxonomy_term_has_parent( $condition );
				break;
				
			case 'x_body_classes':
				$condition_met = self::execute_x_body_classes( $condition );
				break;
			
			case 'x_has_custom_excerpt':
				$condition_met = self::execute_x_has_custom_excerpt( $condition );
				break;
			
			case 'x_has_post_content':
				$condition_met = self::execute_x_has_post_content( $condition );
				break;
			
			case 'x_has_child_category':
				$condition_met = self::execute_x_has_child_category( $condition );
				break;
			
			case 'x_date_field_value':
				$condition_met = self::execute_x_date_field_value( $condition );
				break;
			
			case 'x_datetime_field_value':
				$condition_met = self::execute_x_datetime_field_value( $condition );
				break;

			case 'x_author_has_cpt_entry':
				$condition_met = self::execute_x_author_has_cpt_entry( $condition );
				break;

			case 'x_cpt_has_at_least_1_entry':
				$condition_met = self::execute_x_cpt_has_at_least_1_entry( $condition );
				break;

			/* member conditions */

			case 'x_memberpress_membership':
				$condition_met = self::execute_x_memberpress_membership( $condition );
				break;

			case 'x_edd_product_purchased':
				$condition_met = self::execute_x_edd_product_purchased( $condition );
				break;

			case 'x_edd_subscribed':
				$condition_met = self::execute_x_edd_subscribed( $condition );
				break;

			case 'x_wp_members_membership':
				$condition_met = self::execute_x_wp_members_membership( $condition );
				break;

			case 'x_rcp_membership_level':
				$condition_met = self::execute_x_rcp_membership_level( $condition );
				break;

			case 'x_rcp_has_active_membership':
				$condition_met = self::execute_x_rcp_has_active_membership( $condition );
				break;

			case 'x_rcp_has_paid_membership':
				$condition_met = self::execute_x_rcp_has_paid_membership( $condition );
				break;

//			case 'x_active_member_conditional':
//				$condition_met = self::execute_x_active_member_conditional( $condition );
//				break;
			
			case 'x_wishlist_member':
				$condition_met = self::execute_x_wishlist_member( $condition );
				break;

			case 'x_sure_members_access_groups':
				$condition_met = self::execute_x_sure_members_access_groups( $condition );
				break;

			case 'x_pmp_membership_level':
					$condition_met = self::execute_x_pmp_membership_level( $condition );
					break;

			case 'x_woocommerce_subscriptions':
				$condition_met = self::execute_x_woocommerce_subscriptions( $condition );
				break;

					


			/* WooCommerce conditions */

			case 'x_cart_empty':
				$condition_met = self::execute_x_cart_empty( $condition );
				break;

			case 'x_cart_total':
				$condition_met = self::execute_x_cart_total( $condition );
				break;

			case 'x_cart_total_minus_shipping':
				$condition_met = self::execute_x_cart_total_minus_shipping( $condition );
				break;

			case 'x_cart_weight':
				$condition_met = self::execute_x_cart_weight( $condition );
				break;
			
			case 'x_product_in_cart':
				$condition_met = self::execute_x_product_in_cart( $condition );
				break;

			case 'x_user_purchased_product':
				$condition_met = self::execute_x_user_purchased_product( $condition );
				break;

			case 'x_product_is_virtual':
				$condition_met = self::execute_x_product_is_virtual( $condition );
				break;

			case 'x_product_is_downloadable':
				$condition_met = self::execute_x_product_is_downloadable( $condition );
				break;

			case 'x_user_purchased_current_product':
				$condition_met = self::execute_x_user_purchased_current_product( $condition );
				break;

			case 'x_user_has_pending_order':
				$condition_met = self::execute_x_user_has_pending_order( $condition );
				break;

			case 'x_product_in_stock':
				$condition_met = self::execute_x_product_in_stock( $condition );
				break;

			case 'x_product_weight':
				$condition_met = self::execute_x_product_weight( $condition );
				break;

			case 'x_product_rating':
				$condition_met = self::execute_x_product_rating( $condition );
				break;

			case 'x_cart_count':
				$condition_met = self::execute_x_cart_count( $condition );
				break;

			case 'x_product_type':
				$condition_met = self::execute_x_product_type( $condition );
				break;
			
			case 'x_product_on_backorder':
				$condition_met = self::execute_x_product_on_backorder( $condition );
				break;

			case 'x_product_upsell_count':
				$condition_met = self::execute_x_product_upsell_count( $condition );
				break;

			case 'x_product_crosssells_count':
				$condition_met = self::execute_x_product_crosssells_count( $condition );
				break;

			case 'x_product_backorders_allowed':
				$condition_met = self::execute_x_product_backorders_allowed( $condition );
				break;
			
			case 'x_product_has_category':
				$condition_met = self::execute_x_product_has_category( $condition );
				break;
			
			case 'x_product_has_tag':
				$condition_met = self::execute_x_product_has_tag( $condition );
				break;
				
			case 'x_current_product_in_cart':
				$condition_met = self::execute_x_current_product_in_cart( $condition );
				break;
			
			case 'x_product_in_cart_has_coupon':
				$condition_met = self::execute_x_product_in_cart_has_coupon( $condition );
				break;
		
				
				


			default:
				$condition_met = $result;
				break;
		}

		return $condition_met;

	}


	/* functions for each condition */
	
	public static function execute_x_at_least_1_search_result( $condition ): bool {

		// If user value is empty, we set it to 'present' as default
		$user_value = $condition['value'] ?? 'present';

		// Get the number of search results
		global $wp_query;
		$search_results_count = $wp_query->found_posts;

				return $user_value === 'present' ? $search_results_count > 0 : $search_results_count === 0;

	}

	public static function execute_x_archive_type( $condition ): bool {

		// If compare is empty, we set it to '==' as default
		// If user value is empty, we set it to 'category' as default
		$compare    = $condition['compare'] ?? '==';
		$user_value = $condition['value'] ?? 'category';

		// Return true or false depending on the chosen value 
		if ( $compare == '==' ) {
			switch ( $user_value ) {
				case 'posts_page':
					return is_home();
					break;

				case 'category':
					return is_category();
					break;

				case 'tag':
					return is_tag();
					break;

				case 'taxonomy':
					return is_tax();
					break;

				case 'search':
					return is_search();
					break;

				case 'author':
					return is_author();
					break;

				case 'date':
					return is_date();
					break;

				case 'wc':
					return is_shop() || is_product_taxonomy();
					break;

				default:
						return is_category();
			}
		} else {
			switch ( $user_value ) {
				case 'posts_page':
					return ! is_home();
					break;

				case 'category':
					return ! is_category();
					break;

				case 'tag':
					return ! is_tag();
					break;

				case 'taxonomy':
					return ! is_tax();
					break;

				case 'search':
					return ! is_search();
					break;

				case 'author':
					return ! is_author();
					break;

				case 'date':
					return ! is_date();
					break;

				case 'wc':
					return !((is_shop() || is_product_taxonomy()));
					break;

				default:
						return ! is_category();
			}
		}

	}

	public static function execute_x_author_has_cpt_entry( $condition ): bool {

		// If compare is empty, we set it to '==' as default
		// If user value is empty, we set it to '' as default
		$compare    = $condition['compare'] ?? '==';
		$user_value = $condition['value'] ?? '';

		if ( $user_value === '' ) {
				return true;
		}

		$posts_count = count_user_posts( get_current_user_id(), $user_value );

		// Return true or false depending on the chosen value 
		return $compare === '==' ? $posts_count > 0 : $posts_count == 0;

	}

	public static function execute_x_cpt_has_at_least_1_entry( $condition ): bool {

		// If compare is empty, we set it to '==' as default
		// If user value is empty, we set it to '' as default
		$compare    = $condition['compare'] ?? '==';
		$user_value = $condition['value'] ?? '';

				if ( $user_value === '' ) {
						return true;
				}

				return wp_count_posts( $user_value )->publish > 0;

	}

	public static function execute_x_authored_by_logged_in_user( $condition ): bool {

		// If user value is empty, we set it to 'true' as default
		$value = $condition['value'] ?? 'true';

		global $post;

		if (!$post) {
			return false;
		}

		switch ( $value ) {
			case 'false':
					if ( is_user_logged_in() ) {
							return get_the_author_meta( 'ID', $post->post_author ) !== wp_get_current_user()->ID;
					} else {
							return false;
					}
					break;

			case 'true':
				default:
					if ( is_user_logged_in() ) {
						return get_the_author_meta( 'ID', $post->post_author ) === wp_get_current_user()->ID;
					} else {
						return false;
					}
		}

	}

	public static function execute_x_post_comment_count( $condition ): bool {

		// If compare is empty, we set it to '==' as default
		// If user value is empty, we set it to 0 as default
		$compare    = $condition['compare'] ?? '==';
		$value = $condition['value'] ?? 0;

		// get comments count for the current post

		global $post;

		if (!$post) {
			return false;
		}

		$comments_count = get_comments_number( $post->ID );

		return Helpers::getMathsCompare($comments_count, intval( $value ), $compare, false);

	}

	public static function execute_x_is_archive( $condition ): bool {

		// If user value is empty, we set it to '' as default
		$value = $condition['value'] ?? 'false';

		return $value === 'true' ? is_archive() : ! is_archive();

	}

	public static function execute_x_post_ancestor( $condition ): bool {

		// If user value is empty, we set it to '' as default
		$compare = $condition['compare'] ?? '==';
		$value = $condition['value'] ?? false;

		if (!$value) { 
			return false;
		}
		
		$post_ancestors = get_post_ancestors( get_the_ID() );

		$value = bricks_render_dynamic_data( $value );

		$valueArray = explode(",", $value);

		$has_ancestor = false;

		foreach( $valueArray as $value ) {

			if ( in_array( intval($value), $post_ancestors, true ) ) {
				$has_ancestor = true;
			}
		}

		return $compare === '==' ? $has_ancestor : !$has_ancestor;

	}

	public static function execute_x_post_type( $condition ): bool {

		// If user value is empty, we set it to '' as default
		$compare = $condition['compare'] ?? '==';
		$value = $condition['value'] ?? false;

		if (!$value) { 
			return false;
		}
		
		$is_post_type = $value === get_post_type();

		return $compare === '==' ? $is_post_type : !$is_post_type;

	}

	public static function execute_x_post_category( $condition ): bool {

		// If compare is empty, we set it to '==' as default
		// If user value is empty, we set it to '' as default
		$compare    = $condition['compare'] ?? '==';
		$value = $condition['value'] ?? '';

		// Array of current post category IDs
		global $post;

		if (!$post) {
			return false;
		}
		$post_categories = get_the_category( $post->ID );
		$post_categories_array = [];
		foreach ( $post_categories as $post_category ) {
			$post_categories_array[] = $post_category->term_id;
		}
		
		// Return true or false depending on the chosen value
		return $compare === '==' ? in_array( $value, $post_categories_array ) : ! in_array( $value, $post_categories_array );

	}

	public static function execute_x_post_tag( $condition ): bool {

		// If compare is empty, we set it to '==' as default
		// If user value is empty, we set it to '' as default
		$compare = $condition['compare'] ?? '==';
		$value = $condition['value'] ?? '';

		// Array of current post tag IDs
		global $post;

		if (!$post) {
			return false;
		}
		$post_tags = get_the_tags( $post->ID );
		$post_tags_array = [];

		if ( !is_array( $post_tags ) ) {
			return false;
		}

		foreach ( $post_tags as $post_tag ) {
			$post_tags_array[] = $post_tag->term_id;
		}
		
		// Return true or false depending on the chosen value
		return $compare === '==' ? in_array( $value, $post_tags_array ) : ! in_array( $value, $post_tags_array );

	}

	public static function execute_x_is_homepage( $condition ): bool {

		// If compare is empty, we set it to '==' as default
		// If user value is empty, we set it to '' as default
		$value = $condition['value'] ?? 'false';

		return $value === 'true' ? is_front_page() : ! is_front_page();

	}
	
	public static function execute_x_is_child( $condition ): bool {

		// If user value is empty, we set it to 'false' as default
		$value = $condition['value'] ?? 'false';

		global $post;

		if (!$post) {
			return false;
		}

		return $value === 'true' ? $post->post_parent > 0 : $post->post_parent === 0;

	}
	
	public static function execute_x_is_parent( $condition ): bool {

		// If user value is empty, we set it to 'true' as default
		$value = $condition['value'] ?? 'true';

		global $post;

		if (!$post) {
			return false;
		}

		$children = get_pages( array( 'child_of' => $post->ID, 'post_type' => $post->post_type ) );

		if ( !is_array( $children ) ) {
			return $value === 'false';
		}

		return $value === 'true' ? count( $children ) > 0 : count( $children ) === 0;

	}

	public static function execute_x_in_favorites_loop( $condition ): bool {

		// If user value is empty, we set it to 'true' as default
		$value = $condition['value'] ?? 'true';

		global $post;

		if (!$post) {
			return false;
		}

		$condition_met = ( isset( $post->in_favorites_loop ) && $post->in_favorites_loop === true );

		return $value === 'true' ? $condition_met : !$condition_met;

	}

	

	public static function execute_x_password_protect( $condition ): bool {

		// If user value is empty, we set it to 'false' as default
		$value = $condition['value'] ?? 'false';

		// Return true or false depending on the chosen value 
		global $post;

		if (!$post) {
			return false;
		}
	
		return 'true' === $value ? post_password_required( $post ) : ! post_password_required( $post );

	}
	
	public static function execute_x_language_visitor( $condition ): bool {

		// If compare is empty, we set it to '=' as default
		// If user value is empty, we set it to '' as default
		$compare = $condition['compare'] ?? '==';
		$value = $condition['value'] ?? 'en-US';

		// Return true or false depending on the chosen value 
		// Get the languages set in the browser
		$languages = isset( $_SERVER['HTTP_ACCEPT_LANGUAGE'] ) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : false;

		if (false === $languages) {
			return true;
		}

		// Get the position of "," in languages
		$pos = strpos( $languages, ',' );

		// Get the first language before the first occurrence of comma
		$language = substr( $languages, 0, $pos );
	
		return $compare === '==' ? $language === $value : $language !== $value;

	}
	
	public static function execute_x_category_archive( $condition ): bool {

		// If compare is empty, we set it to '=' as default
		// If user value is empty, we set it to '' as default
		$compare = $condition['compare'] ?? '==';
		$value = $condition['value'] ?? '1';

		// Return true or false depending on the chosen value 
		return $compare === '==' ? is_category( $value ) : ! is_category( $value );

	}
	
	public static function execute_x_tag_archive( $condition ): bool {

		// If compare is empty, we set it to '=' as default
		// If user value is empty, we set it to '' as default
		$compare = $condition['compare'] ?? '==';
		$value = $condition['value'] ?? '';

		// if $value is empty, return true
		if ( $value === '' ) {
			return true;
		}

		// Return true or false depending on the chosen value 
		return $compare === '==' ? is_tag( $value ) : ! is_tag( $value );

	}
	
	public static function execute_x_post_publish_date( $condition ): bool {

		// If user value is empty, we set it to 'past' as default
		$value = $condition['value'] ?? 'past';

		$publishDate = get_post_datetime( get_the_ID() )->format( 'Y-m-d' );
		$currentDate = wp_date( 'Y-m-d' );

		return $value === 'past' ? strtotime( $publishDate ) < strtotime( $currentDate ) : strtotime( $publishDate ) === strtotime( $currentDate );

	}
	
	public static function execute_x_page_type( $condition ): bool {

		// If compare is empty, we set it to '==' as default
		// If user value is empty, we set it to '' as default
		$compare = $condition['compare'] ?? '==';
		$value = $condition['value'] ?? 'page';

		switch ( $value ) {

						case 'post':
				return $compare === '==' ? is_singular( 'post') : ! is_singular( 'post' );
				break;

						case 'front_page':
				return $compare === '==' ? is_front_page() : ! is_front_page();
				break;

						case 'blog':
				return $compare === '==' ? is_home() : ! is_home();
				break;

						case 'error':
				return $compare === '==' ? is_404() : ! is_404();
				break;

						case 'search':
				return $compare === '==' ? is_search() : ! is_search();
				break;

						case 'singular':
				return $compare === '==' ? is_singular() : ! is_singular();
				break;

						case 'single':
				return $compare === '==' ? is_single() : ! is_single();
				break;

						case 'archive':
				return $compare === '==' ? is_archive() : ! is_archive();
				break;

						case 'category':
				return $compare === '==' ? is_category() : ! is_category();
				break;

						case 'tag':
				return $compare === '==' ? is_tag() : ! is_tag();
				break;

						case 'author':
				return $compare === '==' ? is_author() : ! is_author();
				break;

						case 'date':
				return $compare === '==' ? is_date() : ! is_date();
				break;

						case 'tax':
				return $compare === '==' ? is_tax() : ! is_tax();
				break;

						case 'wc':
				if ( class_exists( '\WooCommerce' ) ) {
					return $compare === '==' ? is_woocommerce() : ! is_woocommerce();
				} else {
					return false;
				}

				break;

						case 'wc_shop':
				if ( class_exists( '\WooCommerce' ) ) {
					return $compare === '==' ? is_shop() : ! is_shop();
				} else {
					return false;
				}
				break;

						case 'wc_product':
				if ( class_exists( '\WooCommerce' ) ) {
					return $compare === '==' ? is_product() : ! is_product();
				} else {
					return false;
				}
				break;

						case 'wc_product_category':
				if ( class_exists( '\WooCommerce' ) ) {
					return $compare === '==' ? is_product_category() : ! is_product_category();
				} else {
					return false;
				}
				break;

						case 'wc_product_tag':
				if ( class_exists( '\WooCommerce' ) ) {
					return $compare === '==' ? is_product_tag() : ! is_product_tag();
				} else {
					return false;
				}
				break;

						case 'wc_cart':
				if ( class_exists( '\WooCommerce' ) ) {
					return $compare === '==' ? is_cart() : ! is_cart();
				} else {
					return false;
				}
				break;

						case 'wc_checkout':
				if ( class_exists( '\WooCommerce' ) ) {
					return $compare === '==' ? is_checkout() : ! is_checkout();
				} else {
					return false;
				}
				break;

						case 'wc_account':
				if ( class_exists( '\WooCommerce' ) ) {
					return $compare === '==' ? is_account_page() : ! is_account_page();
				} else {
					return false;
				}
				break;

						case 'wc_endpoint':
				if ( class_exists( '\WooCommerce' ) ) {
					return $compare === '==' ? is_wc_endpoint_url() : ! is_wc_endpoint_url();
				} else {
					return false;
				}
				break;

						case 'wc_order_pay':
				if ( class_exists( '\WooCommerce' ) ) {
					return $compare === '==' ? is_wc_endpoint_url( 'order-pay' ) : ! is_wc_endpoint_url( 'order-pay' );
				} else {
					return false;
				}
				break;

						case 'wc_order_received':
				if ( class_exists( '\WooCommerce' ) ) {
					return $compare === '==' ? is_wc_endpoint_url( 'order-received' ) : ! is_wc_endpoint_url( 'order-received' );
				} else {
					return false;
				}
				break;

						case 'wc_view_order':
				if ( class_exists( '\WooCommerce' ) ) {
					return $compare === '==' ? is_wc_endpoint_url( 'view-order' ) : ! is_wc_endpoint_url( 'view-order' );
				} else {
					return false;
				}
				break;

						case 'wc_edit_account':
				if ( class_exists( '\WooCommerce' ) ) {
					return $compare === '==' ? is_wc_endpoint_url( 'edit-account' ) : ! is_wc_endpoint_url( 'edit-account' );
				} else {
					return false;
				}
				break;

						case 'wc_edit_address':
				if ( class_exists( '\WooCommerce' ) ) {
					return $compare === '==' ? is_wc_endpoint_url( 'edit-address' ) : ! is_wc_endpoint_url( 'edit-address' );
				} else {
					return false;
				}
				break;

						case 'wc_lost_password':
				if ( class_exists( '\WooCommerce' ) ) {
					return $compare === '==' ? is_wc_endpoint_url( 'lost-password' ) : ! is_wc_endpoint_url( 'lost-password' );
				} else {
					return false;
				}
				break;

						case 'wc_customer_logout':
				if ( class_exists( '\WooCommerce' ) ) {
					return $compare === '==' ? is_wc_endpoint_url( 'customer-logout' ) : ! is_wc_endpoint_url( 'customer-logout' );
				} else {
					return false;
				}
				break;

						case 'wc_add_payment_method':
				if ( class_exists( '\WooCommerce' ) ) {
					return $compare === '==' ? is_wc_endpoint_url( 'add-payment-method' ) : ! is_wc_endpoint_url( 'add-payment-method' );
				} else {
					return false;
				}
				break;

						case 'page':
						default:
								return $compare === '==' ? is_page() : ! is_page();

				}

	}

	public static function execute_x_wpml_language( $condition ): bool {

		if ( ! defined( 'ICL_SITEPRESS_VERSION' ) ) {
			return true;
		}

		$current_language = apply_filters( 'wpml_current_language', null );

		// If compare is empty, we set it to '==' as default
		// If user value is empty, we set it to '' as default
		$compare = $condition['compare'] ?? '==';
		$value = $condition['value'] ?? apply_filters('wpml_default_language', NULL );

		// Return true or false depending on the chosen value
		return $compare === '==' ? $value === $current_language : $value !== $current_language;

	}
	
	public static function execute_x_page_parent( $condition ): bool {

		// If compare is empty, we set it to '==' as default
		// If user value is empty, we set it to '' as default
		$compare = $condition['compare'] ?? '==';
		$value = $condition['value'] ?? '';

		if ( ! $value ) {
			return true;
		}
		
		// check if current Page is a child of $value
		global $post;

		if (!$post) {
			return false;
		}

		return $compare === '==' ? $post->post_parent === intval($value) : $post->post_parent !== intval($value);

	}

	public static function execute_x_polylang_language( $condition ): bool {

		if ( ! function_exists( 'pll_count_posts' ) ) {
			return true;
		}

		$current_language = pll_current_language();

		// If compare is empty, we set it to '==' as default
		// If user value is empty, we set it to '' as default
		$compare = $condition['compare'] ?? '==';
		$value = $condition['value'] ?? pll_default_language();

		// Return true or false depending on the chosen value
		return $compare === '==' ? $value === $current_language : $value !== $current_language;

	}
	
	public static function execute_x_translatepress_language( $condition ): bool {

		if ( ! class_exists( '\TRP_Translate_Press' ) ) {
			return true;
		}

		$trp = TRP_Translate_Press::get_trp_instance();

		$trp_settings = $trp->get_component( 'settings' );

		$language_codes_array = $trp_settings->get_settings()['publish-languages'];

		$current_language = get_locale();

		// If compare is empty, we set it to '==' as default
		// If user value is empty, we set it to the default language as default
		$compare = $condition['compare'] ?? '==';
		$value = $condition['value'] ?? $language_codes_array[0];

		// Return true or false depending on the chosen value
		return $compare === '==' ? $value === $current_language : $value !== $current_language;

	}
	
	public static function execute_x_published_during_last( $condition ): bool {

		// If compare is empty, we set it to '==' as default
		// If user value is empty, we set it to '' as default
		$compare = $condition['compare'] ?? '==';
		$value = $condition['value'] ?? '';

		if ( ! $value ) {
			return true;
		}

		$args = [
			'date_query' => [
				[
					'after' => '1 ' . $value . ' ago',
				],
			],
			'post_type' => 'any',
			'posts_per_page' => -1,
		];
	
		$dateQuery = new WP_Query( $args );

		$present = in_array( get_the_ID(), wp_list_pluck( $dateQuery->posts, 'ID' ) );

		return $compare === '==' ? $present : ! $present;

	}

	public static function execute_x_loop_item_number( $condition ): bool {

		// If compare is empty, we set it to '==' as default
		// If user value is empty, we set it to 1 as default
		$compare = $condition['compare'] ?? '==';
		$value = $condition['value'] ?? 1;

		if ( method_exists( '\Bricks\Query', 'get_query_object' ) ) {
			$query_object = Query::get_query_object();
	
			if ( $query_object ) {
				$loop_item_number = intval( $query_object::get_loop_index() ) + 1;
			}
		} else {
			$loop_item_number = 0;
		}
	
		$loop_item_number = $loop_item_number ?? 1;

		return $compare === 'every' ? $loop_item_number % intval( $value ) === 0 : Helpers::getMathsCompare( $loop_item_number, intval( $value ), $compare, false );

	}
	
	public static function execute_x_current_month( $condition ): bool {

		$currentMonth = wp_date( 'F' );

		// If compare is empty, we set it to '==' as default
		// If user value is empty, we set it to the current month name as default
		$compare = $condition['compare'] ?? '==';
		$value = $condition['value'] ?? $currentMonth;

		return $compare === '==' ? $currentMonth === $value : $currentMonth !== $value;

	}

	public static function execute_x_current_day( $condition ): bool {

		$currentDay = wp_date( 'j' );

		// If compare is empty, we set it to '==' as default
		// If user value is empty, we set it to current day of the week as default
		$compare = $condition['compare'] ?? '==';
		$value = $condition['value'] ?? $currentDay;

		return $compare === '==' ? $currentDay === $value : $currentDay !== $value;

	}
	
	public static function execute_x_current_year( $condition ): bool {

		// If compare is empty, we set it to '==' as default
		// If user value is empty, we set it to the current year as default
		$compare = $condition['compare'] ?? '==';



		$value = $condition['value'] ?? $currentYear;

		return $compare === '==' ? $currentYear === $value : $currentYear !== $value;

	}

	public static function execute_x_current_taxonomy_term_has_child( $condition ): bool {

		// If compare is empty, we set it to '==' as default
		// If user value is empty, we set it to the current year as default
		$compare = $condition['compare'] ?? '==';
		$value = $condition['value'] ?? 'true';

		// Get the current term
		$current_term = get_queried_object();

		$hasChild = false;

		// Check if it's a term object and has children
		if ( is_a($current_term, 'WP_Term') && $current_term->term_id) {

			$child_terms = get_term_children($current_term->term_id, $current_term->taxonomy);

			if ( is_array( $child_terms ) ) {
				$hasChild = !empty( $child_terms );
			}
		}

		return $value === 'true' ? $hasChild : ! $hasChild;

	}


	public static function execute_x_current_taxonomy_term_has_parent( $condition ): bool {

		// If compare is empty, we set it to '==' as default
		// If user value is empty, we set it to the current year as default
		$compare = $condition['compare'] ?? '==';
		$value = $condition['value'] ?? 'true';

		// Get the current term
		$current_term = get_queried_object();

		$hasParent = false;

		// Check if it's a term object and has parent
		if ( is_a($current_term, 'WP_Term') && $current_term->term_id) {

			$parent_terms = get_ancestors($current_term->term_id, $current_term->taxonomy);

			if ( is_array( $parent_terms ) ) {
				$hasParent = !empty( $parent_terms );
			}
		}

		return $value === 'true' ? $hasParent : ! $hasParent;

	}

	
	
	public static function execute_x_body_classes( $condition ): bool {

		// If compare is empty, we set it to '==' as default
		// If user value is empty, we set it to '' as default
		$compare = $condition['compare'] ?? '==';
		$value = $condition['value'] ?? '';

		if ( ! $value ) {
			return true;
		}

		$bodyClasses = get_body_class();

		return $compare === '==' ? in_array( $value, $bodyClasses ) : ! in_array( $value, $bodyClasses );

	}
	
	public static function execute_x_has_custom_excerpt( $condition ): bool {

		// If user value is empty, we set it to 'true' as default
		$value = $condition['value'] ?? 'true';

		$hasExcerpt = has_excerpt( get_the_ID() );

		return $value === 'true' ? $hasExcerpt : ! $hasExcerpt;

	}
	
	public static function execute_x_has_post_content( $condition ): bool {

		// If user value is empty, we set it to 'true' as default
		$value = $condition['value'] ?? 'true';

		global $post;

		if (!$post) {
			return false;
		}

		$contentLength = strlen( $post->post_content );

		return $value === 'true' ? $contentLength : ! $contentLength;

	}
	
	public static function execute_x_has_child_category( $condition ): bool {

		// If user value is empty, we set it to 'false' as default
		$value = $condition['value'] ?? 'false';

		if ( $value === 'false' && ! is_category() ) {
			return true;
		}

		$cat = get_queried_object();

		$hasChildCategory = false;

		if ( $cat->category_parent === 0 ) { // if the current category is a top-level category
			$children = get_categories( [
				'child_of' => $cat->term_id,
				'hide_empty' => false,
			] );

			$hasChildCategory = count( $children ) > 0;
		}

		return $value === 'true' ? $hasChildCategory : ! $hasChildCategory;

	}
	
	public static function execute_x_date_field_value( $condition ): bool {

		// If compare is empty, we set it to 'future' as default
		// If user value is empty, we set it to '' as default
		$compare = $condition['compare'] ?? 'future';
		$value = $condition['value'] ?? '';

		if ( ! $value ) {
			return true;
		}

		$value = str_replace( '}', ':Y-m-d}', $value );
		
		$value = bricks_render_dynamic_data( $value );
		// 31/08/2023 → 2023-08-31

		$value = new DateTimeImmutable( $value, wp_timezone() );
		$value = $value->format( 'Y-m-d' );
		
		$today = current_datetime(); // retrieves the current time as an object using the site's timezone
		$today = $today->format( 'Y-m-d' );

		switch ( $compare ) {

						case 'today':
				return $value === $today;
				break;

						case 'past':
				return $value < $today;
				break;

						case 'future':
						default:
								return $value > $today;
				}

	}
	
	public static function execute_x_datetime_field_value( $condition ): bool {

		// If compare is empty, we set it to 'future' as default
		// If user value is empty, we set it to '' as default
		$compare = $condition['compare'] ?? 'future';
		$value = $condition['value'] ?? '';

		if ( ! $value ) {
			return true;
		}

		$value = str_replace( '}', ':Y-m-d H:i:s}', $value );
		
		$value = bricks_render_dynamic_data( $value );

		$value = new DateTimeImmutable( $value, wp_timezone() );
		$value = $value->format( 'U' );
		
		$now = time(); // retrieves the current Unix timestamp

		switch ( $compare ) {
			case 'future':
				return $value > $now;
				break;

			case 'past':
				return $value < $now;
				break;

						default:
								return $value > $now;
				}


		}




	/* membership conditions */

	public static function execute_x_memberpress_membership( $condition ): bool {

		$compare    = $condition['compare'] ?? '==';
		$user_value = $condition['value'] ?? false;

		/* abort if no value */
		if (!$user_value || ! class_exists( '\MeprSubscription' )) {
			return true;
		}

		$current_user_membership = current_user_can("mepr-active","membership: $user_value");

		return  '==' === $compare ? $current_user_membership : !$current_user_membership;
		

	}

	public static function execute_x_edd_product_purchased( $condition ): bool {

		$compare    = $condition['compare'] ?? '==';
		$user_value = $condition['value'] ?? false;

		if (!$user_value || !function_exists( 'EDD' ) ) {
			return true;
		}

		$current_user = get_current_user_id();
		$value = intval($user_value);

		return  '==' === $compare ? edd_has_user_purchased($current_user, $value) : !edd_has_user_purchased($current_user, $value);

	}

	public static function execute_x_edd_subscribed( $condition ) {

		if ( !class_exists( '\EDD_Recurring_Subscriber' ) ) {
			return true;
		}

		$compare    = $condition['compare'] ?? '==';
		$user_value = $condition['value'] ?? false;

		if (!$user_value) {
			return true;
		}

		$subscriber = new EDD_recurring_subscriber( get_current_user_id(), true );
		$value = intval($user_value);

		return  '==' === $compare ? $subscriber->has_active_product_subscription( $value ) : !$subscriber->has_active_product_subscription( $value );

	}

	public static function execute_x_wp_members_membership( $condition ): bool {

		if ( !class_exists( '\WP_Members_Products' ) ) {
			return true;
		}

		$compare    = $condition['compare'] ?? '==';
		$user_value = isset( $condition['value'] ) ? intval( $condition['value'] ) : false;

		if (!$user_value) {
			return true;
		}

		
		$product_slug = get_post_field( 'post_name', $user_value ); 

		return  '==' === $compare ? wpmem_user_has_access( $product_slug ) : !wpmem_user_has_access( $product_slug );

	}

	public static function execute_x_rcp_membership_level( $condition ): bool {

		if ( !function_exists( 'rcp_get_membership_levels' ) ) {
			return true;
		}

		$compare    = $condition['compare'] ?? '==';
		$user_value = isset( $condition['value'] ) ? intval( $condition['value'] ) : false;

		if (!$user_value) {
			return true;
		}



		if ( '==' === $compare ) {
			$condition_met = in_array( $user_value, rcp_get_customer_membership_level_ids() );
		} elseif ( '!=' === $compare ) {
			$condition_met = ! in_array( $user_value, rcp_get_customer_membership_level_ids() );
		} else {

			$customer = rcp_get_customer_by_user_id( get_current_user_id() );
	
			if ($customer) {

				$args = [
					'customer_id' => $customer->get_id(),
					'object_id' => $user_value,
					'status' => $compare
				];
				
				$matching_memberships = rcp_get_memberships($args);

				$condition_met = !empty( $matching_memberships );

			} else {
				$condition_met = false;
			}
		}

		return $condition_met;

	}

	public static function execute_x_rcp_has_active_membership( $condition ): bool {

		if ( !function_exists( 'rcp_user_has_active_membership' ) ) {
			return true;
		}

		$user_value = $condition['value'] ?? 'true';

		return $user_value === 'true' ? rcp_user_has_active_membership() : ! rcp_user_has_active_membership();

	}

	public static function execute_x_rcp_has_paid_membership( $condition ): bool {

		if ( !function_exists( 'rcp_user_has_paid_membership' ) ) {
			return true;
		}

		$user_value = $condition['value'] ?? 'true';

		return $user_value === 'true' ? rcp_user_has_paid_membership() : ! rcp_user_has_paid_membership();

	}
	
	public static function execute_x_wishlist_member( $condition ): bool {

		$compare    = $condition['compare'] ?? '==';
		$user_value = $condition['value'] ?? false;

		/* abort if no value */
		if ( ! $user_value || !function_exists( 'wlmapi_get_levels' ) || !function_exists('wlmapi_get_level_member_data') ) {
			return true;
		}

		$current_user_membership = wlmapi_is_user_a_member( intval( $user_value ), get_current_user_id() );

		switch( $compare ) {

			case '==':
				$condition_met = $current_user_membership;
				break;

			case '!=':
				$condition_met = !$current_user_membership;
				break;

			case 'active':

				if ( $current_user_membership ) {
					$membershipStatus = wlmapi_get_level_member_data( intval( $user_value ), get_current_user_id() )['member']['level']->Status[0];
					$condition_met = 'Active' === $membershipStatus;
				} else {
					$condition_met = false;
				}
				
				break;

			case 'cancelled':
				
				if ( $current_user_membership ) {
					$membershipStatus = wlmapi_get_level_member_data( intval( $user_value ), get_current_user_id() )['member']['level']->Status[0];
					$condition_met = 'Cancelled' === $membershipStatus;
				} else {
					$condition_met = false;
				}

				break;

		}

		return $condition_met;

	}

	public static function execute_x_sure_members_access_groups( $condition ): bool {

		$compare    = $condition['compare'] ?? '==';
		$user_value = isset( $condition['value'] ) ? intval( $condition['value'] ) : false;

		/* abort if no value */
		if ( ! $user_value || !method_exists( '\SureMembers\Inc\Access_Groups', 'check_if_user_has_access' ) ) {
			return true;
		}

		$check_user_has_access = Access_Groups::check_if_user_has_access( (array)$user_value );

		return '==' === $compare ? $check_user_has_access : ! $check_user_has_access;

	}

	public static function execute_x_pmp_membership_level( $condition ): bool {

		// if Paid Memberships Pro is not active, render the element
		if ( ! class_exists( '\PMPro_Membership_Level' ) ) {
			return true;
		}

		$compare    = $condition['compare'] ?? '==';
		$user_value = $condition['value'] ?? 'non-members';

		$hasMembershipLevel = pmpro_hasMembershipLevel( $user_value );

		// Notes: https://d.pr/i/cHnYUV

		// $isNonMember = ! is_user_logged_in() || ( is_user_logged_in() && ! pmpro_hasMembershipLevel() );
		$isNonMember = ! pmpro_hasMembershipLevel();

		if ( $user_value === 'non-members' ) {
			return '==' === $compare ? $isNonMember : ! $isNonMember;
		} else {
			return '==' === $compare ? $hasMembershipLevel : ! $hasMembershipLevel;
		}

	}

	public static function execute_x_woocommerce_subscriptions( $condition ): bool {

		$compare = $condition['compare'] ?? '==';
		$value = isset( $condition['value'] ) ? intval( $condition['value'] ) : false;

		if ( ! $value || !class_exists( '\WC_Subscriptions' ) || !class_exists( '\WooCommerce' ) ) {
			return true;
		}

		$has_active_subscription = wcs_user_has_subscription( '', $value, 'active' );

		return '==' === $compare ? $has_active_subscription : ! $has_active_subscription;

		

	}


	


	/* WooCommerce conditions */

	public static function execute_x_cart_empty( $condition ): bool {

		// If user value is empty, we set it to '' as default
		$value = $condition['value'] ?? '';

		if ( ! $value || !class_exists( '\WooCommerce' ) ) {
			return true;
		}

		$isEmpty = WC()->cart->is_empty();

		return $value === 'empty' ? $isEmpty : ! $isEmpty;

	}
	
	public static function execute_x_product_in_cart( $condition ): bool {

		// If compare is empty, we set it to '==' as default
		// If user value is empty, we set it to '' as default
		$compare = $condition['compare'] ?? '==';
		$value = $condition['value'] ?? '';

		if ( ! $value || !class_exists( '\WooCommerce' ) ) {
			return true;
		}
		
		$product = wc_get_product( $value );
		
		if (!$product) {
			return true;
		}
		
		$product_type = $product->get_type();

		if ('variable' == $product_type ) {
			$in_cart = in_array( $value, array_column( WC()->cart->get_cart(), 'product_id' ) );
		}

		else {
			$in_cart = WC()->cart->find_product_in_cart( WC()->cart->generate_cart_id( $value ) );
		}

		

		return $compare === '==' ? $in_cart : ! $in_cart;

	}

	public static function execute_x_user_purchased_product( $condition ): bool {

		$compare = $condition['compare'] ?? '==';
		$value = $condition['value'] ?? false;

		if ( ! $value || !class_exists( '\WooCommerce' ) ) {
			return true;
		}

		$has_purchased = wc_customer_bought_product('', get_current_user_id(), $value);

		return $compare === '==' ? $has_purchased : ! $has_purchased;

	}

	public static function execute_x_cart_total( $condition ): bool {

		$compare = $condition['compare'] ?? '==';
		$value = $condition['value'] ?? 0;

		if ( !class_exists( '\WooCommerce' ) ) {
			return true;
		}

		$cart_total = WC()->cart->get_total( 'edit' );

		return Helpers::getMathsCompare($cart_total, floatval( $value ), $compare);

	}

	public static function execute_x_cart_total_minus_shipping( $condition ): bool {

		$compare = $condition['compare'] ?? '==';
		$value = $condition['value'] ?? 0;

		if ( !class_exists( '\WooCommerce' ) ) {
			return true;
		}

		$cart_total = WC()->cart->get_total( 'edit' ) - WC()->cart->get_shipping_total( 'edit' );

		return Helpers::getMathsCompare($cart_total, floatval( $value ), $compare);

	}

	public static function execute_x_cart_weight( $condition ): bool {

		$compare    = $condition['compare'] ?? '==';
		$value = $condition['value'] ?? 0;

		if ( !class_exists( '\WooCommerce' ) ) {
			return true;
		}

		$cart_weight = WC()->cart->cart_contents_weight;

		return Helpers::getMathsCompare($cart_weight, floatval( $value ), $compare);

	}

	public static function execute_x_product_is_virtual( $condition ): bool {

		$value = $condition['value'] ?? 'true';

		if ( !class_exists( '\WooCommerce' ) ) {
			return true;
		}
	
		if ( 'product' !== get_post_type( get_the_ID() ) ) {
			return false;
		}

		$product = new WC_Product( get_the_ID() );
		
		$product_is_virtual = $product->is_virtual();

		return $value === 'true' ? $product_is_virtual : ! $product_is_virtual;

	}

	public static function execute_x_product_is_downloadable( $condition ): bool {

		$value = $condition['value'] ?? 'true';

		if ( !class_exists( '\WooCommerce' ) ) {
			return true;
		}

		if ( 'product' !== get_post_type( get_the_ID() ) ) {
			return false;
		}
	
		$product = new WC_Product( get_the_ID() );
		
		$product_is_downloadable = $product->is_downloadable();

		return $value === 'true' ? $product_is_downloadable : ! $product_is_downloadable;

	}

	public static function execute_x_user_purchased_current_product( $condition ): bool {

		$value = $condition['value'] ?? 'true';

		if ( !class_exists( '\WooCommerce' ) ) {
			return true;
		}

		if ( 'product' !== get_post_type( get_the_ID() ) ) {
			return false;
		}
	
		$product = new WC_Product( get_the_ID() );
			$product_id = $product->get_id();
			$current_user = wp_get_current_user();
		
		$customer_bought_product = wc_customer_bought_product( $current_user->user_email, $current_user->ID, $product_id );

		return $value === 'true' ? $customer_bought_product : ! $customer_bought_product;

	}

	public static function execute_x_user_has_pending_order( $condition ): bool {

		$value = $condition['value'] ?? 'true';

		if ( !class_exists( '\WooCommerce' ) ) {
			return true;
		}

		$user = wp_get_current_user();
	
		if ( ! empty( $user ) ) {

			$customer_orders = get_posts( [
					'numberposts' => -1,
					'meta_key'    => '_customer_user',
					'meta_value'  => $user->ID,
					'post_type'   => 'shop_order',
					'post_status' => 'wc-pending'
			] );
			
			$has_pending = count( $customer_orders ) > 0;

		} else {
			$has_pending = false;
		}

		return $value === 'true' ? $has_pending : ! $has_pending;

	}

	public static function execute_x_product_in_stock( $condition ): bool {

		$value = $condition['value'] ?? 'true';

		if ( !class_exists( '\WooCommerce' ) ) {
			return true;
		}

		if ( 'product' !== get_post_type( get_the_ID() ) ) {
			return false;
		}
	
		$product = new WC_Product( get_the_ID() );
		
		$product_in_stock = $product->is_in_stock();

		return $value === 'true' ? $product_in_stock : ! $product_in_stock;

	}

	public static function execute_x_product_weight( $condition ): bool {

		$compare    = $condition['compare'] ?? '==';
		$value = $condition['value'] ?? 0;

		if ( !class_exists( '\WooCommerce' ) ) {
			return true;
		}

		if ( 'product' !== get_post_type( get_the_ID() ) ) {
			return false;
		}

		$product = new WC_Product( get_the_ID() );
		
		$product_weight = $product->get_weight();

		return Helpers::getMathsCompare($product_weight, floatval( $value ), $compare);


	}

	public static function execute_x_product_rating( $condition ): bool {

		$compare    = $condition['compare'] ?? '==';
		$value = $condition['value'] ?? 0;

		if ( !class_exists( '\WooCommerce' ) ) {
			return true;
		}

		if ( 'product' !== get_post_type( get_the_ID() ) ) {
			return false;
		}

		$product = new WC_Product( get_the_ID() );
		
		$product_rating = $product->get_average_rating();

		return Helpers::getMathsCompare( floatval($product_rating), floatval( $value ), $compare);


	}

	public static function execute_x_cart_count( $condition ): bool {

		$compare    = $condition['compare'] ?? '==';
		$value = $condition['value'] ?? 0;

		if ( !class_exists( '\WooCommerce' ) ) {
			return true;
		}

		$cart_count = WC()->cart->get_cart_contents_count();

		

		return Helpers::getMathsCompare($cart_count, intval( $value ), $compare, false);

	}

	public static function execute_x_product_type( $condition ): bool {

		$compare    = $condition['compare'] ?? '==';
		$value = $condition['value'] ?? false;

		if ( ! $value || !class_exists( '\WooCommerce' ) ) {
			return true;
		}

		if ( 'product' !== get_post_type( get_the_ID() ) ) {
			return false;
		}
		
		$product_type = WC_Product_Factory::get_product_type( get_the_ID() );

		return  '==' === $compare ? $value === $product_type : $value !== $product_type;

	}

	public static function execute_x_product_on_backorder( $condition ): bool {

		$value = $condition['value'] ?? 'true';

		if ( ! class_exists( '\WooCommerce' ) ) {
			return true;
		}

		if ( 'product' !== get_post_type( get_the_ID() ) ) {
			return false;
		}
	
		$product = new WC_Product( get_the_ID() );
		
		$product_on_backorder = $product->is_on_backorder();

		return $value === 'true' ? $product_on_backorder : ! $product_on_backorder;

	}


	public static function execute_x_product_upsell_count( $condition ): bool {

		$compare  = $condition['compare'] ?? '>';
		$value = $condition['value'] ?? 0;

		if ( ! class_exists( '\WooCommerce' ) ) {
			return true;
		}

		if ( 'product' !== get_post_type( get_the_ID() ) ) {
			return false;
		}
		
		$product = new WC_Product( get_the_ID() );
		$product_upsell_count = count( $product->get_upsell_ids() );

		return Helpers::getMathsCompare($product_upsell_count, intval( $value ), $compare, false);

	}


	public static function execute_x_product_crosssells_count( $condition ): bool {

		$compare  = $condition['compare'] ?? '>';
		$value = $condition['value'] ?? 0;

		if ( ! class_exists( '\WooCommerce' ) ) {
			return true;
		}

		if ( 'product' !== get_post_type( get_the_ID() ) ) {
			return false;
		}
		
		$product = new WC_Product( get_the_ID() );

		$product_crosssells_count = count( $product->get_cross_sell_ids() );

		return Helpers::getMathsCompare($product_crosssells_count, intval( $value ), $compare, false);

	}


	public static function execute_x_product_backorders_allowed( $condition ): bool {

		$value = $condition['value'] ?? 'true';

		if ( ! class_exists( '\WooCommerce' ) ) {
			return true;
		}

		if ( 'product' !== get_post_type( get_the_ID() ) ) {
			return false;
		}
	
		$product = new WC_Product( get_the_ID() );
		
		$product_allows_backorder = $product->backorders_allowed();

		return $value === 'true' ? $product_allows_backorder : ! $product_allows_backorder;

	}
	
	
	public static function execute_x_product_has_category( $condition ): bool {

		$compare = $condition['compare'] ?? '==';
		$value = $condition['value'] ?? '';

		if ( ! $value || ! class_exists( '\WooCommerce' ) ) {
			return true;
		}

		$has_term = has_term( $value, 'product_cat' );

		return $compare === '==' ? $has_term : ! $has_term;

	}
	
	public static function execute_x_product_has_tag( $condition ): bool {

		$compare = $condition['compare'] ?? '==';
		$value = $condition['value'] ?? '';

		if ( ! $value || ! class_exists( '\WooCommerce' ) ) {
			return true;
		}

		$has_term = has_term( $value, 'product_tag' );

		return $compare === '==' ? $has_term : ! $has_term;

	}


	public static function execute_x_current_product_in_cart( $condition ): bool {

		$compare = $condition['compare'] ?? '==';
		$value = $condition['value'] ?? 'true';

		if ( ! class_exists( '\WooCommerce' ) ) {
			return true;
		}

		if ( 'product' !== get_post_type( get_the_ID() ) ) {
			return false;
		}
	
		$product = new WC_Product( get_the_ID() );

		$product_type = $product->get_type();

		if ('variable' == $product_type ) {
			$in_cart = in_array( get_the_ID(), array_column( WC()->cart->get_cart(), 'product_id' ) );
		}

		else {
			$in_cart = WC()->cart->find_product_in_cart( WC()->cart->generate_cart_id( get_the_ID() ) );
		}

		return $value === 'true' ? $in_cart : ! $in_cart;

	}

	public static function execute_x_product_in_cart_has_coupon( $condition ): bool {
		$compare = $condition['compare'] ?? '==';
		$product_id = isset( $condition['value'] ) ? intval( $condition['value'] ) : false;

		// abort if no value or WooCommerce is not active
		if ( ! $product_id || ! class_exists( '\WooCommerce' ) ) {
			return false;
		}

		// Get WooCommerce cart instance
		$cart = WC()->cart;

		// Check if cart exists and has coupons
		if ( empty( $cart ) || empty( $cart->get_applied_coupons() ) ) {
			return false;
		}
	
		// Get cart items
		$cart_items = $cart->get_cart();
	
		// Loop through cart items to find our product
		foreach ( $cart_items as $cart_item ) {
			if ( $cart_item['product_id'] === $product_id ) {
				// Get applied coupons
				$applied_coupons = $cart->get_applied_coupons();
	
				foreach ( $applied_coupons as $coupon_code ) {
					$coupon = new \WC_Coupon( $coupon_code );
	
					// Check if coupon is valid for this product
					$product_ids = $coupon->get_product_ids();
					$excluded_product_ids = $coupon->get_excluded_product_ids();
	
					// If coupon has no specific products, it applies to all
					if ( empty( $product_ids ) && ! in_array( $product_id, $excluded_product_ids, true ) ) {
						return true;
					}
	
					// If product is specifically included
					if ( in_array( $product_id, $product_ids, true ) ) {
						return true;
					}
				}
			}
		}
	
		return false;

	}
	
	
	

}