<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class X_Social_Share extends \Bricks\Element {

  // Element properties
  public $category     = 'extras';
	public $name         = 'xsocialshare';
	public $icon         = 'ti-facebook';
	public $css_selector = '';

  
  public function get_label() {
	return esc_html__( 'Social Share', 'extras' );
  }
  public function set_control_groups() {

	$this->control_groups['shareLinks'] = [
		'title' => esc_html__( 'Manage Share buttons', 'extras' ),
		'tab' => 'content',
	];

	$this->control_groups['styling'] = [
		'title' => esc_html__( 'Button Styles', 'extras' ),
		'tab' => 'content',
	];

	$this->control_groups['iconStyling'] = [
		'title' => esc_html__( 'Icon Styles', 'extras' ),
		'tab' => 'content',
	];

  }

  public function set_controls() {

    $this->controls['items'] = [
			'tab'           => 'content',
			'label'         => esc_html__( 'Services', 'bricks' ),
			'titleProperty' => 'service',
			'group'			=> 'shareLinks',
			'type'          => 'repeater',
			'selector'      => 'li',
			'fields'        => [
				'service'    => [
					'label'     => esc_html__( 'Service', 'bricks' ),
					'type'      => 'select',
					'clearable' => false,
					'options'   => [
						'facebook'  => 'Facebook',
						'twitter'   => 'Twitter',
						'linkedin'  => 'LinkedIn',
						'whatsapp'  => 'WhatsApp',
						'pinterest' => 'Pinterest',
						'telegram'  => 'Telegram',
						'xing' 		=> 'Xing',
						'line'		=> 'Line',
						'vkontakte' => 'VKontakte',
						'mastodon' => 'Mastodon',
						'email'     => esc_html__( 'Email', 'bricks' ),
						'print'     => esc_html__( 'Print', 'bricks' ),
						'copy'     => esc_html__( 'Copy URL', 'bricks' ),
						'custom' => esc_html__( 'Custom', 'bricks' )
					],
				],
				'label'   => [
					'label' => esc_html__( 'Custom label', 'bricks' ),
					'type'  => 'text',
				],
				'copiedLabel'   => [
					'label' => esc_html__( 'Copied label', 'bricks' ),
					'type'  => 'text',
					'required' => ['service', '=', 'copy']
				],
				'ariaLabel'   => [
					'label' => esc_html__( 'Aria-label', 'bricks' ),
					'type'  => 'text',
				],
				'customURL'   => [
					'label' => esc_html__( 'Custom URL', 'bricks' ),
					'type'  => 'text',
					'required' => ['service', '=', 'custom']
				],
				'customURLDynamic'   => [
					'label' => esc_html__( 'Dynamic part of URL', 'bricks' ),
					'type'  => 'text',
					'required' => ['service', '=', 'custom'],
					'info' => esc_html__( '( Use dynamic tags, will URL-encode automatically) ', 'bricks' )
				],
				'emailSubject'   => [
					'label' => esc_html__( 'Subject', 'bricks' ),
					'type'  => 'text',
					'required' => ['service', '=', 'email'],
					'placeholder' => '{post_title}'
				],
				'emailBody'   => [
					'label' => esc_html__( 'Body', 'bricks' ),
					'type'  => 'textarea',
					'required' => ['service', '=', 'email'],
					'placeholder' => '{post_url}'
				],
				'printSelector'   => [
					'label' => esc_html__( 'Selector of content area to print', 'bricks' ),
					'type'  => 'text',
					'required' => ['service', '=', 'print'],
					//'info' => esc_html__( '( Use dynamic tags, will URL-encode automatically) ', 'bricks' )
				],
				'printColorAdjust'   => [
					'label' => esc_html__( 'Print color adjust', 'bricks' ),
					'inline' => true,
					'type'  => 'select',
					'options'   => [
						'exact'  => 'Exact',
						'economy'   => 'Economy',
					],
					'required' => ['service', '=', 'print'],
				],
				'removeShareButtons'   => [
					'label' => esc_html__( 'Remove share buttons from print', 'bricks' ),
					'inline' => true,
					'type'  => 'checkbox',
					'required' => ['service', '=', 'print'],
				],
				'icon'       => [
					'label' => esc_html__( 'Icon', 'bricks' ),
					'type'  => 'icon',
				],
				'display'      => [
					'type'  => 'select',
					'label' => esc_html__( 'Display', 'bricks' ),
					'inline' => true,
					'options'   => [
						'icon' => 'Icon',
						'text' => 'Text',
						'both' => 'Icon & Text',
					],
				],
				'background' => [
					'type'  => 'color',
					'label' => esc_html__( 'Background', 'bricks' ),
					'css'   => [
						[
							'property' => 'background-color',
							'selector' => 'a',
						],
					],
				],
				'color'      => [
					'type'  => 'color',
					'label' => esc_html__( 'Color', 'bricks' ),
					'css'   => [
						[
							'property' => 'color',
							'selector' => 'a',
						],
					],
				],
			],
			'default'       => [
				[ 'service' => 'facebook' ],
				[ 'service' => 'twitter' ],
				[ 'service' => 'linkedin' ],
				[ 'service' => 'pinterest' ],
				[ 'service' => 'email' ],
			],
		];

		

		$this->controls['overallDisplay'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Display', 'bricks' ),
			'type' => 'select',
			'options' => [
			  'text' => esc_html__( 'Text', 'bricks' ),
			  'icon' => esc_html__( 'Icon', 'bricks' ),
			  'both' => esc_html__( 'Text & Icon', 'bricks' ),
			],
			'inline'      => true,
			'clearable' => false,
			'placeholder' => esc_html__( 'Text & Icon', 'bricks' ),
			'info' => 'This can be also changed individually for each link'
		  ];

		$this->controls['direction'] = [
			'tab'    => 'content',
			'label'  => esc_html__( 'Flex direction', 'bricks' ),
			'type'   => 'direction',
			'css'    => [
				[
					'property' => 'flex-direction',
					'selector' => '',
				],
			],
			'inline' => true,
		];

		$this->controls['listGap'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Gap', 'bricks' ),
			'inline' => true,
			'type' => 'number',
			'units' => true,
			'placeholder' => '4px',
			'hasDynamicData' => false,
			'css'    => [
				[
					'property' => 'gap',
					'selector' => '',
				],
			],
		  ];  


		  $this->controls['firstSep'] = [
			'tab'     => 'content',
			'type'  => 'separator',
		];

		$this->controls['brandColors'] = [
			'tab'     => 'content',
			'label'   => esc_html__( 'Use brand colors', 'bricks' ),
			'type'  => 'select',
			'inline' => true,
			'placeholder' => esc_html__( 'Disable', 'bricks' ),
			'options' => [
				'true' => esc_html__( 'Enable', 'bricks' ),
				'false' => esc_html__( 'Disable', 'bricks' ),
			]
		];

		$this->controls['openPopup'] = [
			'tab'   => 'content',
			'placeholder' => esc_html__( 'Window popup', 'bricks' ),
			'label' => esc_html__( "Behaviour", 'bricks' ),
			'type'  => 'select',
			'inline' => true,
			'options' => [
				'true' => esc_html__( 'Window popup', 'bricks' ),
				'false' => esc_html__( 'Open in new tab', 'bricks' ),
				'same' => esc_html__( 'Open in same tab', 'bricks' ),
			]
		];


		$this->controls['addTooltips'] = [
			'tab'   => 'content',
			'placeholder' => esc_html__( 'Disable', 'bricks' ),
			'label' => esc_html__( "Use Tooltips", 'bricks' ),
			'type'  => 'select',
			'inline' => true,
			'options' => [
				'true' => esc_html__( 'Enable', 'bricks' ),
				'false' => esc_html__( 'Disable', 'bricks' ),
			]
		];

		$this->controls['shareURL'] = [
			'tab'   => 'content',
			'placeholder' => esc_html__( 'Current', 'bricks' ),
			'label' => esc_html__( "URL to Share", 'bricks' ),
			'type'  => 'select',
			'inline' => true,
			'options' => [
				'current' => esc_html__( 'Current', 'bricks' ),
				'home' => esc_html__( 'Home URL', 'bricks' ),
				'custom' => esc_html__( 'Custom URL', 'bricks' ),
			]
		];

		$this->controls['customURL'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Custom URL', 'bricks' ),
			'inline' => true,
			'type' => 'text',
			'hasDynamicData' => true,
			'required' => ['shareURL', '=', 'custom']
		  ];  


		$this->controls['customText'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Text', 'bricks' ),
			'inline' => true,
			'type' => 'text',
			'hasDynamicData' => true,
			'required' => ['shareURL', '=', 'custom'],
			'placeholder' => esc_html__( '{post_title}', 'bricks' ),
		  ];  


		/* link styles */

		$linkSelector = '.x-social-share_link';

		$this->controls['linkTypography'] = [
			'tab'    => 'content',
			'type'   => 'typography',
			'label'  => esc_html__( 'Typography', 'extras' ),
			'css'    => [
				[
					'property' => 'font',
					'selector' => $linkSelector,
				],
			],
			'group'	=> 'styling'
		];

		$this->controls['linkBackgroundColor'] = [
			'tab'    => 'content',
			'type'   => 'color',
			'label'  => esc_html__( 'Background color', 'extras' ),
			'css'    => [
				[
					'property' => 'background-color',
					'selector' => $linkSelector,
				],
			],
			'group'	=> 'styling'
		];

		$this->controls['labelBackgroundColor'] = [
			'tab'    => 'content',
			'type'   => 'color',
			'label'  => esc_html__( 'Background color (label)', 'extras' ),
			'css'    => [
				[
					'property' => 'background-color',
					'selector' => '.x-social-share_label',
				],
			],
			'group'	=> 'styling'
		];

		$this->controls['iconBackgroundColor'] = [
			'tab'    => 'content',
			'type'   => 'color',
			'label'  => esc_html__( 'Background color (icon)', 'extras' ),
			'css'    => [
				[
					'property' => 'background-color',
					'selector' => '.x-social-share_icon',
				],
			],
			'group'	=> 'styling'
		];

		$this->controls['linkBorder'] = [
			'tab'    => 'content',
			'type'   => 'border',
			'label'  => esc_html__( 'Border', 'extras' ),
			'css'    => [
				[
					'property' => 'border',
					'selector' => $linkSelector,
				],
			],
			'group'	=> 'styling'
		];

		$this->controls['linkBoxShadow'] = [
			'tab'    => 'content',
			'label'  => esc_html__( 'Box Shadow', 'extras' ),
			'type'   => 'box-shadow',
			'css'    => [
				[
					'property' => 'box-shadow',
					'selector' => $linkSelector,
				],
			],
			'group'	=> 'styling'
		];

		$this->controls['linkPadding'] = [
			'tab'   => 'content',
			'label' => esc_html__( 'Padding', 'extras' ),
			'type'  => 'dimensions',
			'css'   => [
				[
					'property' => 'padding',
					'selector' => '.x-social-share_label',
				],
			],
			'group'	=> 'styling'
		];



		/* icon styles */

		$iconSelector = '.x-social-share_icon';

		$this->controls['iconTypography'] = [
			'tab'    => 'content',
			'type'   => 'typography',
			'label'  => esc_html__( 'Typography', 'extras' ),
			'css'    => [
				[
					'property' => 'font',
					'selector' => $iconSelector,
				],
			],
			'group'	=> 'iconStyling'
		];

		$this->controls['iconBorder'] = [
			'tab'    => 'content',
			'type'   => 'border',
			'label'  => esc_html__( 'Border', 'extras' ),
			'css'    => [
				[
					'property' => 'border',
					'selector' => $iconSelector,
				],
			],
			'group'	=> 'iconStyling'
		];

		$this->controls['iconBoxShadow'] = [
			'tab'    => 'content',
			'label'  => esc_html__( 'Box Shadow', 'extras' ),
			'type'   => 'box-shadow',
			'css'    => [
				[
					'property' => 'box-shadow',
					'selector' => $iconSelector,
				],
			],
			'group'	=> 'iconStyling'
		];

		$this->controls['iconMinHeight'] = [
			'tab'   => 'content',
			'label' => esc_html__( 'Min-height', 'extras' ),
			'type'  => 'number',
			'units'  => true,
			'css'   => [
				[
					'property' => 'min-height',
					'selector' => $iconSelector,
				],
			],
			'group'	=> 'iconStyling'
		];

		$this->controls['iconPadding'] = [
			'tab'   => 'content',
			'label' => esc_html__( 'Padding', 'extras' ),
			'type'  => 'dimensions',
			'css'   => [
				[
					'property' => 'padding',
					'selector' => $iconSelector,
				],
			],
			'group'	=> 'iconStyling'
		];

  }

  // Methods: Frontend-specific
  public function enqueue_scripts() {
	if (! \BricksExtras\Helpers::elementCSSAdded($this->name) ) {
		wp_enqueue_style( 'x-social-share', BRICKSEXTRAS_URL . 'components/assets/css/socialshare.css', [], '' );
	}
  }
  
  public function render() {

    $items = ! empty( $this->settings['items'] ) ? $this->settings['items'] : false;
    $rel_attribute = ! empty( $settings['linkRel'] ) ? trim( $settings['linkRel'] ) : 'nofollow noopener';
	$overallDisplay = isset( $this->settings['overallDisplay'] ) ? $this->settings['overallDisplay'] : 'both';
	$openPopup = isset( $this->settings['openPopup'] ) ? $this->settings['openPopup'] : 'true';

	$customURL = isset( $this->settings['customURL'] ) ? $this->settings['customURL'] : '';
	$customText = isset( $this->settings['customText'] ) ? $this->settings['customText'] : false;
	$shareURL = isset( $this->settings['shareURL'] ) ? $this->settings['shareURL'] : 'current';

	$addTooltips = isset( $this->settings['addTooltips'] ) ? 'true' === $this->settings['addTooltips'] : false;

	$config = [];

	/* get element ID of the most deep looped element */
	if ( method_exists('\Bricks\Query','is_any_looping') ) {

		$query_id = \Bricks\Query::is_any_looping();

		if ( $query_id ) {
			$config += [ 'isLooping' => \Bricks\Query::get_query_element_id( $query_id ) ];
		}

	}

    if ( ! $items ) {
			return $this->render_element_placeholder(
				[
					'title' => esc_html__( 'No sharing option selected.', 'bricks' ),
				]
			);
		}

   		 global $post;

		$post = get_post( $this->post_id );

		$image = rawurlencode( html_entity_decode( wp_get_attachment_url( get_post_thumbnail_id() ), ENT_COMPAT, 'UTF-8' ) );
		$title = rawurlencode( html_entity_decode( get_the_title(), ENT_COMPAT, 'UTF-8' ) );

		if ('current' === $shareURL) {
			
			/* for archive pages outside of query loops */
			if ( is_archive() && ! \Bricks\Query::is_any_looping() ) {

				if (isset($_SERVER['HTTPS']) &&
				(sanitize_text_field($_SERVER['HTTPS']) == 'on' || sanitize_text_field($_SERVER['HTTPS']) == 1) ||
					isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
					sanitize_text_field($_SERVER['HTTP_X_FORWARDED_PROTO']) == 'https') {
					$protocol = 'https://';
				} else {
					$protocol = 'http://';
				}

				$host = isset( $_SERVER['HTTP_HOST'] ) ? sanitize_text_field($_SERVER['HTTP_HOST']) : '';
				$requestURI = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field($_SERVER['REQUEST_URI']) : '';

				$settingsURL  = $protocol . $host . $requestURI;

			} else {
				$settingsURL  = get_the_permalink();
			}

		} elseif ('home' === $shareURL) {
			$settingsURL = get_home_url();
		} else {
			$settingsURL = $this->render_dynamic_data($customURL);
			if ($customText) {
				$title = esc_html( $this->render_dynamic_data( $customText ) );
			} 
		}

		$url  = rawurlencode( html_entity_decode( $settingsURL, ENT_COMPAT, 'UTF-8' ) );

		$this->set_attribute( "_root", 'data-x-popup', esc_attr( $openPopup ) );	

		$this->set_attribute( "_root", 'data-x-social', wp_json_encode( $config ) );	

    echo "<ul {$this->render_attributes( '_root' )}>";

		foreach ( $items as $index => $item ) {

			if ( empty( $item['service'] ) ) {
				continue;
			}

			$icon = ! empty( $item['icon'] ) ? self::render_icon( $item['icon'] ) : false;

			$display = isset( $item['display'] ) ? $item['display'] : $overallDisplay;

			$label = isset( $item['label'] ) ? esc_attr__( $item['label'] ) : false;
			$ariaLabel = isset( $item['ariaLabel'] ) ? esc_attr__( $item['ariaLabel'] ) : false;

			$printSelector = isset( $item['printSelector'] ) ? esc_attr__( $item['printSelector'] ) : false;
			$printColorAdjust = isset( $item['printColorAdjust'] ) ? 'exact' === $item['printColorAdjust'] : false;
			$removeShareButtons = isset( $item['removeShareButtons'] );

			$copiedLabel = isset( $item['copiedLabel'] ) ? esc_attr__( $item['copiedLabel'] ) : false;

			
			$customURL = ! empty( $item['customURL'] ) ? esc_url( $item['customURL'] ) : '';
			$customURLDynamicSetting = ! empty( $item['customURLDynamic'] ) ? $item['customURLDynamic'] : '';

			$emailSubject = ! empty( $item['emailSubject'] ) ? $item['emailSubject'] : false;
			$emailBody = ! empty( $item['emailBody'] ) ? $item['emailBody'] : false;


			$dynamicURLArray = preg_split('/{|}/', $customURLDynamicSetting);

			$urlArray = [];

			foreach ($dynamicURLArray as $value) {

				$value = $this->render_dynamic_data_tag( $value );

				/* if dynamic data, encode it */
				if(strpos($value, '{') === false) {
					$value = rawurlencode( html_entity_decode( $value, ENT_COMPAT, 'UTF-8' ) );
				} 
				
				/* if not remove the braces */
				else {
					$value = trim($value, '{}');
				}

				array_push( $urlArray, $value );

			}

			$customURLDynamic = implode($urlArray);

			$data = false;

			$facebookIcon = method_exists( '\Bricks\Helpers', 'get_file_contents' ) ? \Bricks\Helpers::get_file_contents( BRICKS_URL_ASSETS . 'svg/frontend/facebook.svg' ) : \Bricks\Helpers::file_get_contents( BRICKS_PATH_ASSETS . 'svg/frontend/facebook.svg' );
			$twitterIcon = '<svg viewBox="0 0 24 24" aria-hidden="true" class="r-4qtqp9 r-yyyyoo r-dnmrzs r-bnwqim r-1plcrui r-lrvibr r-lrsllp r-1nao33i r-16y2uox r-8kz0gk"><g><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"></path></g></svg>';
			$linkedinIcon = method_exists( '\Bricks\Helpers', 'get_file_contents' ) ? \Bricks\Helpers::get_file_contents( BRICKS_URL_ASSETS . 'svg/frontend/linkedin.svg' ) : \Bricks\Helpers::file_get_contents( BRICKS_PATH_ASSETS . 'svg/frontend/linkedin.svg' );
			$whatsappIcon = method_exists( '\Bricks\Helpers', 'get_file_contents' ) ? \Bricks\Helpers::get_file_contents( BRICKS_URL_ASSETS . 'svg/frontend/whatsapp.svg' ) : \Bricks\Helpers::file_get_contents( BRICKS_PATH_ASSETS . 'svg/frontend/whatsapp.svg' );
			$pinterestIcon = method_exists( '\Bricks\Helpers', 'get_file_contents' ) ? \Bricks\Helpers::get_file_contents( BRICKS_URL_ASSETS . 'svg/frontend/pinterest.svg' ) : \Bricks\Helpers::file_get_contents( BRICKS_PATH_ASSETS . 'svg/frontend/pinterest.svg' );
			$telegramIcon = method_exists( '\Bricks\Helpers', 'get_file_contents' ) ? \Bricks\Helpers::get_file_contents( BRICKS_URL_ASSETS . 'svg/frontend/telegram.svg' ) : \Bricks\Helpers::file_get_contents( BRICKS_PATH_ASSETS . 'svg/frontend/telegram.svg' );
			$vkontakteIcon = method_exists( '\Bricks\Helpers', 'get_file_contents' ) ? \Bricks\Helpers::get_file_contents( BRICKS_URL_ASSETS . 'svg/frontend/vkontakte.svg' ) : \Bricks\Helpers::file_get_contents( BRICKS_PATH_ASSETS . 'svg/frontend/vkontakte.svg' );
			$emailIcon = method_exists( '\Bricks\Helpers', 'get_file_contents' ) ? \Bricks\Helpers::get_file_contents( BRICKS_URL_ASSETS . 'svg/frontend/email.svg' ) : \Bricks\Helpers::file_get_contents( BRICKS_PATH_ASSETS . 'svg/frontend/email.svg' );



			switch ( $item['service'] ) {
				case 'facebook':
					$data = [
						'url'     => wp_is_mobile() ? 'https://m.facebook.com/sharer.php?u=' . $url : "http://www.facebook.com/sharer.php?u=$url&amp;picture=$image&amp;title=$title",
						'tooltip' => sprintf( esc_html__( 'Share on %s', 'bricks' ), 'Facebook' ),
						'class'   => 'facebook',
						'label'   => $label ? $label : 'Facebook',
						'ariaLabel'   => $ariaLabel ? $ariaLabel : esc_attr__( 'Share on ') . 'Facebook',
						'icon'    => $icon ? $icon : $facebookIcon,
					];
					break;

				case 'twitter':
					$data = [
						'url'     => "https://twitter.com/share?text=$title&amp;url=$url",
						'tooltip' => sprintf( esc_html__( 'Share on %s', 'bricks' ), 'X' ),
						'class'   => 'twitter',
						'label'   => $label ? $label : 'X',
						'ariaLabel'   => $ariaLabel ? $ariaLabel : esc_attr__( 'Share on ') . 'X',
						'icon'    => $icon ? $icon : $twitterIcon,
					];
					break;

				case 'linkedin':
					$data = [
						'url'     => "https://www.linkedin.com/shareArticle?mini=true&amp;url=$url&amp;title=$title",
						'tooltip' => sprintf( esc_html__( 'Share on %s', 'bricks' ), 'LinkedIn' ),
						'class'   => 'linkedin',
						'label'   =>  $label ? $label : 'Linkedin',
						'ariaLabel'   => $ariaLabel ? $ariaLabel : esc_attr__( 'Share on ') . 'linkedin',
						//'icon'    => $icon ? $icon : \Bricks\Helpers::get_file_contents( BRICKS_URL_ASSETS . 'svg/frontend/linkedin.svg' ),
						'icon'    => $icon ? $icon : $linkedinIcon
					];
					break;

				case 'whatsapp':
					$text = get_the_excerpt( $post );

					$data = [
						'url'     => "https://api.whatsapp.com/send?text=*{$title}&nbsp;{$text}&nbsp;{$url}",
						'tooltip' => sprintf( esc_html__( 'Share on %s', 'bricks' ), 'WhatsApp' ),
						'class'   => 'whatsapp',
						'label'   => $label ? $label : 'WhatsApp',
						'ariaLabel'   => $ariaLabel ? $ariaLabel : esc_attr__( 'Share on ') . 'whatsapp',
						//'icon'    => $icon ? $icon : \Bricks\Helpers::get_file_contents( BRICKS_URL_ASSETS . 'svg/frontend/whatsapp.svg' ),
						'icon'    => $icon ? $icon : $whatsappIcon,
					];
					break;

				case 'pinterest':
					$data = [
						'url'     => "http://pinterest.com/pin/create/button/?url=$url&amp;media=$image",
						'tooltip' => sprintf( esc_html__( 'Share on %s', 'bricks' ), 'Pinterest' ),
						'class'   => 'pinterest',
						'label'   => $label ? $label : 'Pinterest',
						'ariaLabel'   => $ariaLabel ? $ariaLabel : esc_attr__( 'Share on ') . 'pinterest',
						//'icon'    => $icon ? $icon : \Bricks\Helpers::get_file_contents( BRICKS_URL_ASSETS . 'svg/frontend/pinterest.svg' ),
						'icon'    => $icon ? $icon : $pinterestIcon,
					];
					break;

				case 'telegram':
					$data = [
						'url'     => "https://t.me/share/url?url={$url}&text={$title}",
						'tooltip' => sprintf( esc_html__( 'Share on %s', 'bricks' ), 'Telegram' ),
						'class'   => 'telegram',
						'label'   => $label ? $label : 'Telegram',
						'ariaLabel'   => $ariaLabel ? $ariaLabel : esc_attr__( 'Share on ') . 'telegram',
						//'icon'    => \Bricks\Helpers::get_file_contents( BRICKS_URL_ASSETS . 'svg/frontend/telegram.svg' ),
						'icon'    => $icon ? $icon : $telegramIcon,
					];
					break;

				case 'vkontakte':
					$data = [
						'url'     => "https://vk.com/share.php?url={$url}&title={$title}&image=$image",
						'tooltip' => sprintf( esc_html__( 'Share on %s', 'bricks' ), 'VKontakte' ),
						'class'   => 'vkontakte',
						'label'   => $label ? $label : 'Vkontakte',
						'ariaLabel'   => $ariaLabel ? $ariaLabel : esc_attr__( 'Share on ') . 'vkontakte',
						//'icon'    => $icon ? $icon : \Bricks\Helpers::get_file_contents( BRICKS_URL_ASSETS . 'svg/frontend/vkontakte.svg' ),
						'icon'    => $icon ? $icon : $vkontakteIcon,
					];
					break;

				case 'xing':
					$data = [
						'url'     => "https://www.xing.com/spi/shares/new?url={$url}",
						'tooltip' => sprintf( esc_html__( 'Share on %s', 'bricks' ), 'Xing' ),
						'class'   => 'xing',
						'label'   => $label ? $label : 'Xing',
						'ariaLabel'   => $ariaLabel ? $ariaLabel : esc_attr__( 'Share on ') . 'xing',
						'icon'    => $icon ? $icon : '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><!--! Font Awesome Pro 6.2.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M162.7 210c-1.8 3.3-25.2 44.4-70.1 123.5-4.9 8.3-10.8 12.5-17.7 12.5H9.8c-7.7 0-12.1-7.5-8.5-14.4l69-121.3c.2 0 .2-.1 0-.3l-43.9-75.6c-4.3-7.8.3-14.1 8.5-14.1H100c7.3 0 13.3 4.1 18 12.2l44.7 77.5zM382.6 46.1l-144 253v.3L330.2 466c3.9 7.1.2 14.1-8.5 14.1h-65.2c-7.6 0-13.6-4-18-12.2l-92.4-168.5c3.3-5.8 51.5-90.8 144.8-255.2 4.6-8.1 10.4-12.2 17.5-12.2h65.7c8 0 12.3 6.7 8.5 14.1z"/></svg>',
					];
					break;

				case 'line':
					$data = [
						'url'     => "https://social-plugins.line.me/lineit/share?url={$url}",
						'tooltip' => sprintf( esc_html__( 'Share on %s', 'bricks' ), 'line' ),
						'class'   => 'line',
						'label'   => $label ? $label : 'Line',
						'ariaLabel'   => $ariaLabel ? $ariaLabel : esc_attr__( 'Share on ') . 'Line',
						'icon'    => $icon ? $icon : '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M272.1 204.2v71.1c0 1.8-1.4 3.2-3.2 3.2h-11.4c-1.1 0-2.1-.6-2.6-1.3l-32.6-44v42.2c0 1.8-1.4 3.2-3.2 3.2h-11.4c-1.8 0-3.2-1.4-3.2-3.2v-71.1c0-1.8 1.4-3.2 3.2-3.2H219c1 0 2.1.5 2.6 1.4l32.6 44v-42.2c0-1.8 1.4-3.2 3.2-3.2h11.4c1.8-.1 3.3 1.4 3.3 3.1zm-82-3.2h-11.4c-1.8 0-3.2 1.4-3.2 3.2v71.1c0 1.8 1.4 3.2 3.2 3.2h11.4c1.8 0 3.2-1.4 3.2-3.2v-71.1c0-1.7-1.4-3.2-3.2-3.2zm-27.5 59.6h-31.1v-56.4c0-1.8-1.4-3.2-3.2-3.2h-11.4c-1.8 0-3.2 1.4-3.2 3.2v71.1c0 .9.3 1.6.9 2.2.6.5 1.3.9 2.2.9h45.7c1.8 0 3.2-1.4 3.2-3.2v-11.4c0-1.7-1.4-3.2-3.1-3.2zM332.1 201h-45.7c-1.7 0-3.2 1.4-3.2 3.2v71.1c0 1.7 1.4 3.2 3.2 3.2h45.7c1.8 0 3.2-1.4 3.2-3.2v-11.4c0-1.8-1.4-3.2-3.2-3.2H301v-12h31.1c1.8 0 3.2-1.4 3.2-3.2V234c0-1.8-1.4-3.2-3.2-3.2H301v-12h31.1c1.8 0 3.2-1.4 3.2-3.2v-11.4c-.1-1.7-1.5-3.2-3.2-3.2zM448 113.7V399c-.1 44.8-36.8 81.1-81.7 81H81c-44.8-.1-81.1-36.9-81-81.7V113c.1-44.8 36.9-81.1 81.7-81H367c44.8.1 81.1 36.8 81 81.7zm-61.6 122.6c0-73-73.2-132.4-163.1-132.4-89.9 0-163.1 59.4-163.1 132.4 0 65.4 58 120.2 136.4 130.6 19.1 4.1 16.9 11.1 12.6 36.8-.7 4.1-3.3 16.1 14.1 8.8 17.4-7.3 93.9-55.3 128.2-94.7 23.6-26 34.9-52.3 34.9-81.5z"/></svg>',
					];
					break;

				case 'mastodon':
					$data = [
						'url'     => "#",
						'tooltip' => sprintf( esc_html__( 'Share on %s', 'bricks' ), 'Mastodon' ),
						'data-src' => "$title&amp;url=$url",
						'class'   => 'mastodon',
						'label'   => $label ? $label : 'Mastodon',
						'ariaLabel'   => $ariaLabel ? $ariaLabel : esc_attr__( 'Share on ') . 'Mastodon',
						'icon'    => $icon ? $icon : '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--! Font Awesome Pro 6.3.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M433 179.11c0-97.2-63.71-125.7-63.71-125.7-62.52-28.7-228.56-28.4-290.48 0 0 0-63.72 28.5-63.72 125.7 0 115.7-6.6 259.4 105.63 289.1 40.51 10.7 75.32 13 103.33 11.4 50.81-2.8 79.32-18.1 79.32-18.1l-1.7-36.9s-36.31 11.4-77.12 10.1c-40.41-1.4-83-4.4-89.63-54a102.54 102.54 0 0 1-.9-13.9c85.63 20.9 158.65 9.1 178.75 6.7 56.12-6.7 105-41.3 111.23-72.9 9.8-49.8 9-121.5 9-121.5zm-75.12 125.2h-46.63v-114.2c0-49.7-64-51.6-64 6.9v62.5h-46.33V197c0-58.5-64-56.6-64-6.9v114.2H90.19c0-122.1-5.2-147.9 18.41-175 25.9-28.9 79.82-30.8 103.83 6.1l11.6 19.5 11.6-19.5c24.11-37.1 78.12-34.8 103.83-6.1 23.71 27.3 18.4 53 18.4 175z"/></svg>',
					];
					break;


				case 'email':

					$subjectText = $emailSubject ? esc_attr( bricks_render_dynamic_data( $emailSubject ) ) : $title;
					$bodyText = $emailBody ? esc_attr( bricks_render_dynamic_data( $emailBody ) ) : $url;

					$data = [
						'url'     => "mailto:?subject=$subjectText&amp;body=$bodyText",
						'tooltip' => esc_html__( 'Share via email', 'bricks' ),
						'class'   => 'email',
						'label'   => $label ? $label : 'Email',
						'ariaLabel'   => $ariaLabel ? $ariaLabel : 'Email',
						//'icon'    => $icon ? $icon : \Bricks\Helpers::get_file_contents( BRICKS_URL_ASSETS . 'svg/frontend/email.svg' ),
						'icon'    => $icon ? $icon : $emailIcon,
					];
					break;
					
				case 'print':
					$data = [
						'url'     => '#',
						'tooltip' => $label ? $label : 'Print',
						'class'   => 'print',
						'label'   => $label ? $label : 'Print',
						'ariaLabel'   => $ariaLabel ? $ariaLabel : 'Print',
						'icon'    => $icon ? $icon : '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M128 0C92.7 0 64 28.7 64 64v96h64V64H354.7L384 93.3V160h64V93.3c0-17-6.7-33.3-18.7-45.3L400 18.7C388 6.7 371.7 0 354.7 0H128zM384 352v32 64H128V384 368 352H384zm64 32h32c17.7 0 32-14.3 32-32V256c0-35.3-28.7-64-64-64H64c-35.3 0-64 28.7-64 64v96c0 17.7 14.3 32 32 32H64v64c0 35.3 28.7 64 64 64H384c35.3 0 64-28.7 64-64V384zm-16-88c-13.3 0-24-10.7-24-24s10.7-24 24-24s24 10.7 24 24s-10.7 24-24 24z"/></svg>',
					];
					break;

				case 'copy':
					$data = [
						'url'     => $settingsURL,
						'tooltip' => $label ? $label : esc_html__( 'Copy URL', 'bricks' ),
						'class'   => 'copy',
						'label'   => $label ? $label : esc_html__( 'Copy URL', 'bricks' ),
						'ariaLabel'   => $ariaLabel ? $ariaLabel : 'Copy URL',
						'copiedLabel' => $copiedLabel ? $copiedLabel : false,
						'icon'    => $icon ? $icon : '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M326.612 185.391c59.747 59.809 58.927 155.698.36 214.59-.11.12-.24.25-.36.37l-67.2 67.2c-59.27 59.27-155.699 59.262-214.96 0-59.27-59.26-59.27-155.7 0-214.96l37.106-37.106c9.84-9.84 26.786-3.3 27.294 10.606.648 17.722 3.826 35.527 9.69 52.721 1.986 5.822.567 12.262-3.783 16.612l-13.087 13.087c-28.026 28.026-28.905 73.66-1.155 101.96 28.024 28.579 74.086 28.749 102.325.51l67.2-67.19c28.191-28.191 28.073-73.757 0-101.83-3.701-3.694-7.429-6.564-10.341-8.569a16.037 16.037 0 0 1-6.947-12.606c-.396-10.567 3.348-21.456 11.698-29.806l21.054-21.055c5.521-5.521 14.182-6.199 20.584-1.731a152.482 152.482 0 0 1 20.522 17.197zM467.547 44.449c-59.261-59.262-155.69-59.27-214.96 0l-67.2 67.2c-.12.12-.25.25-.36.37-58.566 58.892-59.387 154.781.36 214.59a152.454 152.454 0 0 0 20.521 17.196c6.402 4.468 15.064 3.789 20.584-1.731l21.054-21.055c8.35-8.35 12.094-19.239 11.698-29.806a16.037 16.037 0 0 0-6.947-12.606c-2.912-2.005-6.64-4.875-10.341-8.569-28.073-28.073-28.191-73.639 0-101.83l67.2-67.19c28.239-28.239 74.3-28.069 102.325.51 27.75 28.3 26.872 73.934-1.155 101.96l-13.087 13.087c-4.35 4.35-5.769 10.79-3.783 16.612 5.864 17.194 9.042 34.999 9.69 52.721.509 13.906 17.454 20.446 27.294 10.606l37.106-37.106c59.271-59.259 59.271-155.699.001-214.959z"/></svg>',
					];
					break;

					
				case 'custom':
					$data = [
						'url'     => $customURL . $customURLDynamic,
						'tooltip' => esc_html__( $label ? 'Share via ' . $label : 'Share', 'bricks' ),
						'class'   => 'custom',
						'label'   => $label ? $label : 'Custom',
						'ariaLabel'   => $ariaLabel ? $ariaLabel : esc_attr__( 'Share'),
						'icon'    => $icon ? $icon : '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M352 224H305.5c-45 0-81.5 36.5-81.5 81.5c0 22.3 10.3 34.3 19.2 40.5c6.8 4.7 12.8 12 12.8 20.3c0 9.8-8 17.8-17.8 17.8h-2.5c-2.4 0-4.8-.4-7.1-1.4C210.8 374.8 128 333.4 128 240c0-79.5 64.5-144 144-144h80V34.7C352 15.5 367.5 0 386.7 0c8.6 0 16.8 3.2 23.2 8.9L548.1 133.3c7.6 6.8 11.9 16.5 11.9 26.7s-4.3 19.9-11.9 26.7l-139 125.1c-5.9 5.3-13.5 8.2-21.4 8.2H384c-17.7 0-32-14.3-32-32V224zM80 96c-8.8 0-16 7.2-16 16V432c0 8.8 7.2 16 16 16H400c8.8 0 16-7.2 16-16V384c0-17.7 14.3-32 32-32s32 14.3 32 32v48c0 44.2-35.8 80-80 80H80c-44.2 0-80-35.8-80-80V112C0 67.8 35.8 32 80 32h48c17.7 0 32 14.3 32 32s-14.3 32-32 32H80z"/></svg>'
					];
					break;
			}

			if ( $data ) {

				$toolTipData = esc_attr( $data['tooltip'] );

				echo $addTooltips ? "<li class='x-social-share_item' data-balloon=\"{$toolTipData}\" data-balloon-pos=\"top\">" : "<li class='x-social-share_item'>";

				$this->set_attribute( "link-{$index}", 'class', [
					'x-social-share_link',
					$data['class'],
					] );

				if ( isset( $this->settings['brandColors'] ) ) {
					if ( 'true' === $this->settings['brandColors'] ) {
						$this->set_attribute( "link-{$index}", 'class', 'x-social-share_brand-colors' );
					}
				}
        
				if ( 'print' !== $data['class'] && 'copy' !== $data['class'] ) {
					$this->set_attribute( "link-{$index}", 'href', esc_url($data['url']) );
					$this->set_attribute( "link-{$index}", 'rel', esc_attr($rel_attribute) );

					if ('same' !== $openPopup) {
						$this->set_attribute( "link-{$index}", 'target', '_blank' );
					}
				}
				
				if ( 'copy' === $data['class'] ) {
					$this->set_attribute( "link-{$index}", 'data-copy-url', esc_url($data['url']) );
				}

				if ( isset( $data['copiedLabel'] ) ) {
					$this->set_attribute( "link-{$index}", 'data-copied-label', esc_attr( $data['copiedLabel'] ) );
				}

				if ( isset( $data['ariaLabel'] ) ) {
					$this->set_attribute( "link-{$index}", 'aria-label', esc_attr( $data['ariaLabel'] ) );
				} 

				if ( isset( $data['data-src'] ) ) {
					$this->set_attribute( "link-{$index}", 'data-src', esc_attr( $data['data-src'] ) );
				} 

				

				if ( 'print' === $data['class'] ) {
					//$this->set_attribute( "link-{$index}", 'onclick', 'window.print();return false;' );
					if ( $printSelector ) {
						$this->set_attribute( "link-{$index}", 'data-print-selector', $printSelector );
					}
					if ( $printColorAdjust ) {
						$this->set_attribute( "link-{$index}", 'data-print-exact', $printColorAdjust );
					}

					if ( $removeShareButtons ) {
						$this->set_attribute( "link-{$index}", 'data-x-hide-print', 'true' );
					}
				}

				if ( 'print' === $data['class'] || 'copy' === $data['class'] ) {
					echo "<button {$this->render_attributes( "link-{$index}" )}>";
				} else {
					echo "<a {$this->render_attributes( "link-{$index}" )}>";
				}

				echo "text" !== $display ? "<span class='x-social-share_icon'>" . self::render_svg( $data['icon'] ) . "</span>" : "";
				echo "icon" !== $display ? "<span class='x-social-share_label'>" . esc_attr($data['label']) . "</span>" : "";

				if ( 'print' === $data['class'] || 'copy' === $data['class'] ) {
					echo "</button>";
				} else {
					echo "</a>";
				}

				echo '</li>';
			}
		}

		echo '</ul>';


		//if ( $openPopup ) {
		wp_enqueue_script( 'x-social-share', BRICKSEXTRAS_URL . 'components/assets/js/' . \BricksExtras\Helpers::maybeMinifyScripts('socialshare') . '.js', '', '1.0.4', true );
		//}
    
  }

}