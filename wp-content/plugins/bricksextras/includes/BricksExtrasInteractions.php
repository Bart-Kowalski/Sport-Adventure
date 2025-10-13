<?php

namespace BricksExtras;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( class_exists( 'BricksExtrasInteractions' ) ) {
	return;
}

class BricksExtrasInteractions {

	static $prefix = '';

	public static function init( $prefix ) {

		self::$prefix    = $prefix;
		$controlsData = \Bricks\Interactions::get_controls_data();
		\Bricks\Interactions::$control_options = $controlsData;

		
		/* triggers */

		$triggers = [
			'copy_to_clipboard' => [
				'label' => 'Copy to Clipboard',
				'triggers' => [
					'x_copy:copied' => esc_html__( 'Copy success', 'bricks' ),
					'x_copy:failed' => esc_html__( 'Copy failed', 'bricks' ),
					'x_copy:reset' => esc_html__( 'Button reset', 'bricks' ),
					'x_copy:empty' => esc_html__( 'Copy returned empty', 'bricks' ),
				]
			],
			'dynamic_lightbox' => [
				'label' => 'Dynamic Lightbox',
				'triggers' => [
					'x_lightbox:open' => esc_html__( 'Lightbox opened', 'bricks' ),
					'x_lightbox:close' => esc_html__( 'Lightbox closed', 'bricks' ),
				]
			],
			'favorite' => [
				'label' => 'Favorites',
				'triggers' => [
					'x_favorite:added' => esc_html__( 'Item added', 'bricks' ),
					'x_favorite:removed' 	=> esc_html__( 'Item removed', 'bricks' ),
					'x_favorite:cleared' => esc_html__( 'All items cleared', 'bricks' ),
					'x_favorite:count-reached_{count}' => esc_html__( 'List count reaches X', 'bricks' ),
					'x_favorite:maximum-reached' => esc_html__( 'List count full (over maximum)', 'bricks' ),
				]
			],
			'fluent_form' => [
				'label' => 'Fluent Form',
				'triggers' => [
					'fluentform_submission_success' => esc_html__( 'Submission success', 'bricks' ),
					'fluentform_submission_failed' 	=> esc_html__( 'Submission failed', 'bricks' ),
					'fluentform_reset' => esc_html__( 'Form reset', 'bricks' ),
				]
			],
			'header_row' => [
				'label' => 'Header Extras',
				'triggers' => [
					'x_sticky_header:active' => esc_html__( 'Header sticky active', 'bricks' ),
					'x_sticky_header:inactive' => esc_html__( 'Header sticky inactive', 'bricks' ),
					'x_hide_header:active' => esc_html__( 'Header hidden active', 'bricks' ),
					'x_hide_header:inactive' => esc_html__( 'Header hidden inactive', 'bricks' ),
				]
			],
			'image_hotspots' => [
				'label' => 'Image Hotspots',
				'triggers' => [
					'x_image_hotspot:selected_{index}' => esc_html__( 'Image hotspot selected', 'bricks' ),
				]
			],
			'notification_bar' => [
				'label' => 'Header Notification Bar',
				'triggers' => [
					'x_notification:show' => esc_html__( 'Notification Open', 'bricks' ),
					'x_notification:close' => esc_html__( 'Notification Dismissed', 'bricks' ),
				]
			],
			'lottie'=> [
				'label' => 'Lottie',
				'triggers' => [
					'x_lottie:complete' => esc_html__( 'Animation end', 'bricks' ),
				]
			],
			/*
			'media_player' => [
				'label' => 'Media Player',
				'triggers' => [
					'xmediaplayer-started' => esc_html__( 'Started', 'bricks' ),
					'xmediaplayer-ended' => esc_html__( 'Ended', 'bricks' ),
					'xmediaplayer-time-update' => esc_html__( 'Player time reaches..', 'bricks' ),
					'xmediaplayer-watch-time' => esc_html__( 'User watch time reaches..', 'bricks' ),
					'xmediaplayer-pause' => esc_html__( 'Paused', 'bricks' ),
				]
			],
			'media_playlist' => [
				'label' => 'Media Playlist',
				'triggers' => [
					'x_media_playlist:active' => esc_html__( 'Active', 'bricks' ),
					'x_media_playlist:inactive' => esc_html__( 'Inactive', 'bricks' ),
					'x_media_playlist:playing' => esc_html__( 'Playing', 'bricks' ),
					'x_media_playlist:paused' => esc_html__( 'Paused', 'bricks' ),
				]
			],
			*/
			'pro_modal_nestable' => [
				'label' => 'Modal',
				'triggers' => [
					'x_modal:open' => esc_html__( 'Modal opened', 'bricks' ),
					'x_modal:close' => esc_html__( 'Modal closed', 'bricks' ),
				]
			],
			
			
			'pro_alert' => [
				'label' => 'Pro Alert',
				'triggers' => [
					'x_alert:show' => esc_html__( 'Alert shown', 'bricks' ),
					'x_alert:close' => esc_html__( 'Alert closed', 'bricks' ),
				]
			],
			'countdown' => [
				'label' => 'Pro Countdown',
				'triggers' => [
					'x_countdown:ended' => esc_html__( 'Countdown End', 'bricks' ),
				]
			],
			
			'offcanvas_nestable' => [
				'label' => 'Pro Offcanvas',
				'triggers' => [
					'x_offcanvas:open' => esc_html__( 'Offcanvas opened', 'bricks' ),
					'x_offcanvas:close' => esc_html__( 'Offcanvas closed', 'bricks' ),
				]
			],
			'popover' => [
				'label' => 'Popover',
				'triggers' => [
					'x_popover:show' => esc_html__( 'Popover opened', 'bricks' ),
					'x_popover:hide' => esc_html__( 'Popover closed', 'bricks' ),
				]
			],
			'pro_accordion' => [
				'label' => 'Pro Accordion',
				'triggers' => [
					'x_accordion:expand' => esc_html__( 'Accordion expand', 'bricks' ),
					'x_accordion:expand_{index}' => esc_html__( 'Specific item expand', 'bricks' ),
				]
			],
			'pro_slider' => [
				'label' => 'Pro Slider',
				'triggers' => [
					'x_slider:active-slide' => esc_html__( 'Slider moved to specific slide', 'bricks' ),
					'x_slider:moved' => esc_html__( 'Slider moved', 'bricks' ),
					'x_slider:move' => esc_html__( 'Slider move', 'bricks' ),
				]
			],
			
			'pro_tabs' => [
				'label' => 'Pro Tabs',
				'triggers' => [
					//'x_tabs:switch' => esc_html__( 'New tab selected', 'bricks' ),
					'x_tabs:accordion' => esc_html__( 'Switched to accordion', 'bricks' ),
					'x_tabs:tabs' => esc_html__( 'Switched to tabs', 'bricks' ),
					'x_tabs_accordion:expand' => esc_html__( 'Switch to new item', 'bricks' ),
					'x_tabs_accordion:collapse' => esc_html__( 'Accordion collapse', 'bricks' ),
					'x_tabs_accordion:expand_{index}' => esc_html__( 'Switch to specific item', 'bricks' ),
				]
			],
			'read_more_less' => [
				'label' => 'Read More / Less',
				'triggers' => [
					'x_readmore:collapse' => esc_html__( 'Collapsing', 'bricks' ),
					'x_readmore:expand' => esc_html__( 'Expanding', 'bricks' ),
					'x_readmore:collapsed' => esc_html__( 'Collapsed', 'bricks' ),
					'x_readmore:expanded' => esc_html__( 'Expanded', 'bricks' ),
				]
			],
			'slide_menu' => [
				'label' => 'Slide Menu',
				'triggers' => [
					'x_slide_menu:expand' => esc_html__( 'Menu expanded', 'bricks' ),
					'x_slide_menu:collapse' => esc_html__( 'Menu collapse', 'bricks' ),
				]
			],
			'table_of_contents' => [
				'label' => 'Table of Contents',
				'triggers' => [
					'x_toc:link-clicked' => esc_html__( 'Heading link clicked', 'bricks' ),
				]
			],
			'toggle_switch' => [
				'label' => 'Toggle Switch',
				'triggers' => [
					'x_toggle_switch:change' => esc_html__( 'Switched', 'bricks' ),
					'x_toggle_switch:toggled_{label number}' => esc_html__( 'Switched to', 'bricks' ),
				]
			],
			
			'ws_forms' => [
				'label' => 'WS Forms',
				'triggers' => [
					'wsf-submit-success' => esc_html__( 'Submission success', 'bricks' ),
					'wsf-submit-error' => esc_html__( 'Submission error', 'bricks' ),
					'wsf-reset-complete' => esc_html__( 'Form reset', 'bricks' ),
					'wsf-validate-fail' => esc_html__( 'Validate fail', 'bricks' ),
				]
			],
				
			
		];


		foreach( $triggers as $element => $trigger ) {
			BricksExtrasInteractions::addElementInteractions($element, $trigger['label'], $trigger['triggers']);
		}

		

		/* extras options */

		$bricksExtrasOptions = [];

		/*

		$bricksExtrasOptions['xMediaPlayerTimeMinutes'] = [
			'label' 		=> esc_html__( 'Minutes', 'bricks' ),
			'type' 			=> 'number',
			'unit' 			=> false,
			'required' 		=> [ 'trigger', '=', [
				'xmediaplayer-time-update',
				'xmediaplayer-watch-time',
				] 
			]
		];

		$bricksExtrasOptions['xMediaPlayerTime'] = [
			'label' 		=> esc_html__( 'Seconds', 'bricks' ),
			'type' 			=> 'number',
			'unit' 			=> false,
			'required' 		=> [ 'trigger', '=', [
				'xmediaplayer-time-update',
				'xmediaplayer-watch-time',
				] 
			]
		];

		*/


		$bricksExtrasOptions['xFavoriteCountReachedCount'] = [
			'label' 		=> esc_html__( 'Count', 'bricks' ),
			'type' 			=> 'number',
			'unit' 			=> false,
			'required' 		=> [ 'trigger', '=', [
				'x_favorite:count-reached_{count}'
				] 
			]
		];


		$bricksExtrasOptions['xInteractionsDelay'] = [
			'label' 		=> esc_html__( 'Trigger Delay (ms)', 'bricks' ),
			'type' 			=> 'number',
			'unit' 			=> false,
			'placeholder' 	=> 0,
			'required' 		=> [ 'trigger', '=', [
				//'xmediaplayer-started',
				//'xmediaplayer-ended',
				//'xmediaplayer-pause',
				//'xmediaplayer-time-update',
				//'xmediaplayer-watch-time',
				'x_modal:open',
				'x_modal:close',
				'x_alert:show',
				'x_alert:close',
				'x_lightbox:open',
				'x_lightbox:close',
				'x_offcanvas:open',
				'x_offcanvas:close',
				'fluentform_submission_success',
				'fluentform_submission_failed',
				'fluentform_reset',
				'x_favorite:maximum-reached',
				'x_favorite:cleared',
				'x_favorite:removed',
				'x_favorite:added',
				'x_favorite:count-reached_{count}',
				'wsf-submit-success',
				'wsf-submit-error',
				'wsf-reset-complete',
				'wsf-validate-fail',
				'x_sticky_header:active',
				'x_sticky_header:inactive',
				'x_hide_header:active',
				'x_hide_header:inactive',
				'x_toggle_switch:change',
				'x_toggle_switch:toggled_{label number}',
				'x_copy:copied',
				'x_copy:reset',
				'x_copy:failed',
				'x_copy:empty',
				'x_notification:show',
				'x_notification:close',
				'x_countdown:ended',
				'x_tabs:accordion',
				'x_tabs:tabs',
				'x_tabs:switch',
				'x_tabs_accordion:collapse',
				'x_tabs_accordion:expand',
				'x_tabs_accordion:expand_{index}',
				'x_toc:link-clicked',
				'x_slide_menu:expand',
				'x_slide_menu:collapse',
				'x_readmore:collapse',
				'x_readmore:expand',
				'x_readmore:collapsed',
				'x_readmore:expanded',
				//'x_tabs:tab_'
				'x_slider:active-slide',
				'x_slider:move',
				'x_slider:moved',
				'x_accordion:expand_{index}',
				'x_accordion:expand',
				'x_image_hotspot:selected_{index}',
				'x_popover:show',
				'x_popover:hide'
				] 
			]
		];

		$bricksExtrasOptions['xToggleSwitchLabelNumber'] = [
			'label' 		=> esc_html__( 'Label Number', 'bricks' ),
			'type' 			=> 'number',
			'unit' 			=> false,
			'required' 		=> [ 'trigger', '=', [
				'x_toggle_switch:toggled_{label number}'
				] 
			]
		];

		$bricksExtrasOptions['xSliderNumber'] = [
			'label' 		=> esc_html__( 'Slide Index', 'bricks' ),
			'type' 			=> 'number',
			'unit' 			=> false,
			'required' 		=> [ 'trigger', '=', [
				'x_slider:active-slide'
				] 
			]
		];

		$bricksExtrasOptions['xAccordionItemIndex'] = [
			'label' 		=> esc_html__( 'Accordion Item Index', 'bricks' ),
			'type' 			=> 'number',
			'unit' 			=> false,
			'required' 		=> [ 'trigger', '=', [
				'x_accordion:expand_{index}',
				] 
			]
		];

		$bricksExtrasOptions['xAccordionTabsItemIndex'] = [
			'label' 		=> esc_html__( 'Item Index', 'bricks' ),
			'type' 			=> 'number',
			'unit' 			=> false,
			'required' 		=> [ 'trigger', '=', [
				'x_tabs_accordion:expand_{index}'
				] 
			]
		];

		$bricksExtrasOptions['xImageHotspotsIndex'] = [
			'label' 		=> esc_html__( 'Hotspot Index', 'bricks' ),
			'type' 			=> 'number',
			'unit' 			=> false,
			'required' 		=> [ 'trigger', '=', [
				'x_image_hotspot:selected_{index}'
				] 
			]
		];

		
		


		$bricksFields = \Bricks\Interactions::$control_options['fields'];
		$pos = array_search("action", array_keys($bricksFields));

        \Bricks\Interactions::$control_options['fields'] = array_slice($bricksFields, 0, $pos, true) + $bricksExtrasOptions +  array_slice($bricksFields, $pos, null, true);

	}


	/* 
	 Add interactions for each element if activated
	*/
	public static function addElementInteractions($element, $label, $triggers) {

		if ( get_option( self::$prefix . $element) ) {

			\Bricks\Interactions::$control_options['fields']['trigger']['options'][$element . 'GroupTitle'] = esc_html__( $label . ' (Extras)', 'bricks' );

			foreach( $triggers as $key => $event ) {
				\Bricks\Interactions::$control_options['fields']['trigger']['options'][$key] = $event;
			}

		}

	}

}