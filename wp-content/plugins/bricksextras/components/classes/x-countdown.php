<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class X_Countdown extends \Bricks\Element {
	public $category     = 'extras';
	public $name         = 'xcountdown';
	public $icon         = 'ti-time';
	public $css_selector = '.x-countdown_item';
	public $scripts      = [ 'xCountdown' ];

	public function get_label() {
		return esc_html__( 'Pro Countdown', 'bricks' );
	}

	public function get_keywords() {
		return [ 'evergreen', 'countdown', 'timer' ];
	}

	public function enqueue_scripts() {
	    wp_enqueue_script( 'x-countdown', BRICKSEXTRAS_URL . 'components/assets/js/' . \BricksExtras\Helpers::maybeMinifyScripts('countdown') . '.js', '', '1.0.2' );
		if (! \BricksExtras\Helpers::elementCSSAdded($this->name) ) {
			wp_enqueue_style( 'x-countdown', BRICKSEXTRAS_URL . 'components/assets/css/countdown.css', [], '' );
		}
	}
	public function set_control_groups() {

		$this->control_groups['itemsGroup'] = [
				'title' => esc_html__( 'Items', 'bricks' ),
				'tab' => 'content',
		];

		$this->control_groups['seperatorsGroup'] = [
			'title' => esc_html__( 'Seperators', 'bricks' ),
			'tab' => 'content',
		];

	}

	public function set_controls() {

        $this->controls['mode'] = [
			'label'       => esc_html__( 'Countdown mode', 'bricks' ),
			'type'        => 'select',
			'options'     => [
				'fixed' => esc_html__( 'Fixed end time', 'bricks' ),
				'evergreen' => esc_html__( 'Evergreen', 'bricks' ),
			],
			'placeholder' => esc_html__( 'Evergreen', 'bricks' ),
			'inline'      => true,
			'rerender'    => true,
		];

		$now = current_time( 'mysql' ); 
        $time_default = date( 'Y-m-d H:i:s', strtotime( $now ) + 7200 );

		$this->controls['date'] = [
			'label' => esc_html__( 'End date/time', 'bricks' ),
			'type'  => 'text',
			'required' => ['mode', '=', 'fixed'],
			'inline'      => true,
			'default' => $time_default
		];
		
		// Start with UTC offsets from UTC-12:00 to UTC+14:00
		$timezones = [
			'UTC-12:00' => 'UTC-12:00',
			'UTC-11:00' => 'UTC-11:00',
			'UTC-10:00' => 'UTC-10:00',
			'UTC-09:30' => 'UTC-09:30',
			'UTC-09:00' => 'UTC-09:00',
			'UTC-08:00' => 'UTC-08:00',
			'UTC-07:00' => 'UTC-07:00',
			'UTC-06:00' => 'UTC-06:00',
			'UTC-05:00' => 'UTC-05:00',
			'UTC-04:30' => 'UTC-04:30',
			'UTC-04:00' => 'UTC-04:00',
			'UTC-03:30' => 'UTC-03:30',
			'UTC-03:00' => 'UTC-03:00',
			'UTC-02:00' => 'UTC-02:00',
			'UTC-01:00' => 'UTC-01:00',
			'UTC+00:00' => 'UTC+00:00',
			'UTC+01:00' => 'UTC+01:00',
			'UTC+02:00' => 'UTC+02:00',
			'UTC+03:00' => 'UTC+03:00',
			'UTC+03:30' => 'UTC+03:30',
			'UTC+04:00' => 'UTC+04:00',
			'UTC+04:30' => 'UTC+04:30',
			'UTC+05:00' => 'UTC+05:00',
			'UTC+05:30' => 'UTC+05:30',
			'UTC+05:45' => 'UTC+05:45',
			'UTC+06:00' => 'UTC+06:00',
			'UTC+06:30' => 'UTC+06:30',
			'UTC+07:00' => 'UTC+07:00',
			'UTC+08:00' => 'UTC+08:00',
			'UTC+08:45' => 'UTC+08:45',
			'UTC+09:00' => 'UTC+09:00',
			'UTC+09:30' => 'UTC+09:30',
			'UTC+10:00' => 'UTC+10:00',
			'UTC+10:30' => 'UTC+10:30',
			'UTC+11:00' => 'UTC+11:00',
			'UTC+12:00' => 'UTC+12:00',
			'UTC+12:45' => 'UTC+12:45',
			'UTC+13:00' => 'UTC+13:00',
			'UTC+14:00' => 'UTC+14:00',
		];

		// Add timezone regions (e.g., America/New_York)
		$timezone_strings = timezone_identifiers_list();
		foreach ($timezone_strings as $zone) {
			$timezones[$zone] = $zone;
		}

		 $timezone_default = wp_timezone()->getName();

		$this->controls['timezone'] = [
			'tab'         => 'content',
			'required' => ['mode', '=', 'fixed'],
			'label'       => esc_html__( 'Time zone', 'bricks' ),
			'type'        => 'select',
			'searchable' => true,
			'options'     => $timezones,
			'inline'      => true,
			'placeholder' => $timezone_default
		];

		$this->controls['actionSeparator'] = [
			'type'  => 'separator',
		];

        $this->controls['action'] = [
			'label'       => esc_html__( 'Action when countdown ends', 'bricks' ),
			'type'        => 'select',
			'options'     => [
				'countdown' => esc_html__( 'Countdown', 'bricks' ),
				'hide'      => esc_html__( 'Hide', 'bricks' ),
				'text'      => esc_html__( 'Custom text', 'bricks' ),
				'redirect' => esc_html__( 'Redirect', 'bricks' ),
				'sync'      => esc_html__( 'Auto-close Modal/Notification', 'bricks' ),
				'countUp'   => esc_html__( 'Count Up', 'bricks' ),
				'none'   => esc_html__( 'None', 'bricks' ),
			],
			'placeholder' => esc_html__( 'Countdown', 'bricks' ),
			'inline'      => true,
			'rerender'    => true,
		];

		$this->controls['redirectURL'] = [
			'label'    => esc_html__( 'Redirect URL', 'bricks' ),
			'type'     => 'text',
			'required' => [ 'action', '=', 'redirect' ],
			'inline'      => true,
		];

		$this->controls['preventRedirect'] = [
			'label'    => esc_html__( 'Prevent redirect for admins', 'bricks' ),
			'type'     => 'select',
			'options'     => [
				'enable'  => esc_html__( 'Enable', 'bricks' ),
				'disable' => esc_html__( 'Disable', 'bricks' ),
			],
			'required' => [ 'action', '=', 'redirect' ],
			'placeholder' => esc_html__( 'Enable', 'bricks' ),
			'inline'      => true,
		];


        $this->controls['actionText'] = [
			'label'    => esc_html__( 'Date Reached', 'bricks' ) . ': ' . esc_html__( 'Custom text', 'bricks' ),
			'type'     => 'editor',
			'required' => [ 'action', '=', 'text' ],
			'rerender' => true,
		];
		
        $this->controls['fieldsSeparator'] = [
			'label' => esc_html__( 'Fields', 'bricks' ),
			'type'  => 'separator',
		];

		$this->controls['fields'] = [
			'required' => ['mode', '!=', 'fixed'],
			'type'          => 'repeater',
			'titleProperty' => 'format',
			'placeholder'   => esc_html__( 'Field', 'bricks' ),
			'fields'        => [
                'type' => [
					'label'  => esc_html__( 'Type', 'bricks' ),
					'type'   => 'select',
                    'options' => [
                        'days' => esc_html__( 'Days', 'bricks' ),
                        'hours' => esc_html__( 'Hours', 'bricks' ),
                        'minutes' => esc_html__( 'Minutes', 'bricks' ),
                        'seconds' => esc_html__( 'Seconds', 'bricks' )
                    ],
					'inline' => true,
				],
                'value' => [
					'label'  => esc_html__( 'Value', 'bricks' ),
					'type'   => 'text',
					'inline' => true,
				],
				'format' => [
					'label'       => esc_html__( 'Format', 'bricks' ),
					'type'        => 'text',
					'placeholder' => '%D',
					'inline'      => true,
					'info'        => '%D, %H, %M, %S (' . esc_html__( 'Lowercase removes leading zeros', 'bricks' ) . ')',
				],
			],
			'default'       => [
				[ 'format' => esc_attr__( '%D days' ),
                  'type' => 'days',
				  'value' => 3
                ],
				[ 'format' => esc_attr__( '%H hours'),
                'type' => 'hours' 
               ],
				[ 'format' => esc_attr__( '%M minutes'),
                'type' => 'minutes' 
               ],
				[ 'format' => esc_attr__( '%S seconds'),
                'type' => 'seconds'
               ],
			],
			'rerender'      => true,
		];

		$this->controls['fieldsFixed'] = [
			'required' => ['mode', '=', 'fixed'],
			'type'          => 'repeater',
			'titleProperty' => 'format',
			'placeholder'   => esc_html__( 'Field', 'bricks' ),
			'fields'        => [
                'type' => [
					'label'  => esc_html__( 'Type', 'bricks' ),
					'type'   => 'select',
                    'options' => [
                        'days' => esc_attr__( 'Days', 'bricks' ),
                        'hours' => esc_attr__( 'Hours', 'bricks' ),
                        'minutes' => esc_attr__( 'Minutes', 'bricks' ),
                        'seconds' => esc_attr__( 'Seconds', 'bricks' )
                    ],
					'inline' => true,
				],
				'format' => [
					'label'       => esc_attr__( 'Format', 'bricks' ),
					'type'        => 'text',
					'placeholder' => '%D',
					'inline'      => true,
					'info'        => '%D, %H, %M, %S (' . esc_html__( 'Lowercase removes leading zeros', 'bricks' ) . ')',
				],
			],
			'default'       => [
				[ 'format' => esc_attr__('%D days'),
                  'type' => 'days',
				  'value' => 3
                ],
				[ 'format' => esc_attr__('%H hours'),
                'type' => 'hours' 
               ],
				[ 'format' => esc_attr__('%M minutes'),
                'type' => 'minutes' 
               ],
				[ 'format' => esc_attr__('%S seconds'),
                'type' => 'seconds'
               ],
			],
			'rerender'      => true,
		];

        

        $this->controls['actionEndSeparator'] = [
			'type'  => 'separator',
		];

		$this->controls['flexDirectionFields'] = [
			'label'  => esc_html__( 'Direction', 'bricks' ),
			'type'   => 'direction',
			'css'    => [
				[
					'property' => 'flex-direction',
					'selector' => '',
				],
			],
			'inline' => true,
		];

		// items

		
		$this->controls['flexDirection'] = [
			'label'  => esc_html__( 'Direction', 'bricks' ),
			'type'   => 'direction',
			'css'    => [
				[
					'property' => 'flex-direction',
					'selector' => '.x-countdown_format',
				],
			],
			'inline' => true,
			'group' => 'itemsGroup',
			'placeholder' => 'column',
		];

		  $this->controls['itemGap'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Item gap', 'bricks' ),
			'inline' => true,
			'type' => 'number',
			'units' => true,
			'placeholder' => '5px',
			'css'    => [
				[
					'property' => 'gap',
					'selector' => '',
				],
			],
			'group' => 'itemsGroup'
		  ];  


		  $this->controls['itemBackgroundColor'] = [
			'tab'    => 'content',
			'type'   => 'color',
			'label'  => esc_html__( 'Background', 'extras' ),
			'css'    => [
				[
					'property' => 'background-color',
					'selector' => '.x-countdown_item',
				],
			],
			'group' => 'itemsGroup'
		];
	
		$this->controls['itemBorder'] = [
			'tab'    => 'content',
			'type'   => 'border',
			'label'  => esc_html__( 'Border', 'extras' ),
			'css'    => [
				[
					'property' => 'border',
					'selector' => '.x-countdown_item',
				],
			],
			'group' => 'itemsGroup'
		];
	
		$this->controls['itemBoxShadow'] = [
			'tab'    => 'content',
			'label'  => esc_html__( 'Box Shadow', 'extras' ),
			'type'   => 'box-shadow',
			'css'    => [
				[
					'property' => 'box-shadow',
					'selector' => '.x-countdown_item',
				],
			],
			'group' => 'itemsGroup'
		];

		$this->controls['itemPadding'] = [
			'tab'   => 'content',
			'label' => esc_html__( 'Padding', 'extras' ),
			'type'  => 'dimensions',
			'css'   => [
			  [
				'property' => 'padding',
				'selector' => '.x-countdown_item',
			  ],
			],
			'group' => 'itemsGroup'
		  ];


		  $this->controls['itemWidth'] = [
			'tab'   => 'content',
			'label' => esc_html__( 'Width', 'extras' ),
			'type' => 'number',
			'units' => true,
			'css'   => [
			  [
				'property' => 'width',
				'selector' => '.x-countdown_item',
			  ],
			],
			'group' => 'itemsGroup'
		  ];

		  


		unset( $this->controls['_typography'] );

		$this->controls['typography'] = [
			'tab'   => 'style',
			'group' => '_typography',
			'label' => esc_html__( 'Typography', 'bricks' ),
			'type'  => 'typography',
			'css'   => [
				[
					'property' => 'font',
				],
			],
		];

		$this->controls['numberTypography'] = [
			'tab'   => 'style',
			'group' => '_typography',
			'label' => esc_html__( 'Typography', 'bricks' ) . ' (' . esc_html__( 'Number', 'bricks' ) . ')',
			'type'  => 'typography',
			'css'   => [
				[
					'property' => 'font',
					'selector' => '.x-countdown_number',
				],
			],
		];


		$this->controls['maybeSeperator'] = [
			'tab'   => 'style',
			'group' => 'seperatorsGroup',
			'label' => esc_html__( 'Show seperators', 'bricks' ),
			'type'  => 'select',
			'inline'	=> true,
			'options'   => [
				'disable' => esc_html__( 'Disable', 'bricks' ),
				'enable' => esc_html__( 'Enable', 'bricks' ),
			],
			'placeholder' => esc_html__( 'Enable', 'bricks' ),
		];

		$this->controls['seperator'] = [
			'tab'   => 'style',
			'group' => 'seperatorsGroup',
			'label' => esc_html__( 'Seperator', 'bricks' ),
			'type'  => 'text',
			'inline'	=> true,
			'placeholder' => ':',
			'required' => ['maybeSeperator', '!=', 'disable'],
		];

		$this->controls['seperatorTypography'] = [
			'tab'    => 'content',
			'type'   => 'typography',
			'label'  => esc_html__( 'Typography', 'extras' ),
			'css'    => [
				[
					'property' => 'font',
					'selector' => '.x-countdown_seperator',
				],
			],
			'group' => 'seperatorsGroup',
		];

	}

	public function render() {

		$settings = $this->settings;

		$timezone_setting = !empty($settings['timezone']) ? esc_attr($settings['timezone']) : false;

		if (!$timezone_setting) {

			$default_timezone_settings = wp_timezone()->getName();

			// Check if  wp_timezone is a named timezone
			if ( in_array($default_timezone_settings, timezone_identifiers_list())) {

				$timezone_object = new DateTimeZone($default_timezone_settings);
				$datetime = new DateTime('now', $timezone_object);
				$timezone = "UTC" . $datetime->format('P');

			} else {
				$timezone = "UTC" . $default_timezone_settings;
			}

		} else {

			// Check if is a named timezone
			if ( in_array($timezone_setting, timezone_identifiers_list())) {

				$timezone_object = new DateTimeZone($timezone_setting);
				$datetime = new DateTime('now', $timezone_object);
				$timezone = "UTC" . $datetime->format('P');

			} else {
				$timezone = $timezone_setting;
			}
		}

        $mode        = isset( $settings['mode'] ) ? esc_attr($settings['mode']) : 'evergreen';
		$date        = ! empty( $settings['date'] ) ? $settings['date'] : false;
		$fields      = ! empty( $settings['fields'] ) ? $settings['fields'] : false;
		$fieldsFixed = ! empty( $settings['fieldsFixed'] ) ? $settings['fieldsFixed'] : false;
		$action      = ! empty( $settings['action'] ) ? esc_attr( $settings['action'] ) : 'countdown';
		$action_text = ! empty( $settings['actionText'] ) ? wp_kses_post( $settings['actionText'] ) : '';
		$maybeSeperator = isset( $settings['maybeSeperator'] ) ? 'enable' === $settings['maybeSeperator'] : true;
		$seperator = isset( $settings['seperator'] ) ? $settings['seperator'] : ':';

		$redirectURL = isset( $settings['redirectURL'] ) ? $settings['redirectURL'] : false;

		if ( ! $fields ) {
			return $this->render_element_placeholder( [ 'title' => esc_html__( 'No date/fields set.', 'bricks' ) ] );
		}

		$config = [
			'timezone' => $timezone,
			'mode' => $mode,
            'fields'     => 'evergreen' === $mode ? $fields : $fieldsFixed,
            'action'     => $action,
            'actionText' => $action_text,
        ];

		if (!!$date) {

			$date = $this->render_dynamic_data($date);

			try {
				$date = new DateTimeImmutable( $date );
				$date = $date->format('Y-m-d H:i:s');
			}
			catch (exception $e) { }

			$config['date']  = $date;

		}

        if ( $maybeSeperator ) {
			$config['seperator']  = $seperator;
		} 

		if ( !!$redirectURL && 'redirect' === $action ) {
			$config['redirectURL']  = $redirectURL;
			$config['preventRedirect']  = isset( $settings['preventRedirect'] ) ? $settings['preventRedirect'] : 'enable';
		}

		$outputConfig = $this->render_dynamic_data( wp_json_encode($config), 'text' );

		$this->set_attribute( '_root', 'role', 'timer');
		$this->set_attribute( '_root', 'aria-atomic', 'true');
		$this->set_attribute( '_root', 'data-x-countdown', $outputConfig);

        $loopIndex = false;

		if ( method_exists('\Bricks\Query','is_any_looping') ) {

			$query_id = \Bricks\Query::is_any_looping();
	
			if ( $query_id ) {
				
				if ( BricksExtras\Helpers::get_bricks_looping_parent_query_id_by_level(2) ) {
					$loopIndex = \Bricks\Query::get_query_for_element_id( \Bricks\Query::get_query_element_id( BricksExtras\Helpers::get_bricks_looping_parent_query_id_by_level(2) ) )->loop_index . '_' . \Bricks\Query::get_query_for_element_id( \Bricks\Query::get_query_element_id( BricksExtras\Helpers::get_bricks_looping_parent_query_id_by_level(1) ) )->loop_index . '_' . \Bricks\Query::get_loop_index();
				} else {
					if ( BricksExtras\Helpers::get_bricks_looping_parent_query_id_by_level(1) ) {
						$loopIndex = \Bricks\Query::get_query_for_element_id( \Bricks\Query::get_query_element_id( BricksExtras\Helpers::get_bricks_looping_parent_query_id_by_level(1) ) )->loop_index . '_' . \Bricks\Query::get_loop_index();
					} else {
						$loopIndex = \Bricks\Query::get_loop_index();
					}
				}			
	
				$this->set_attribute( '_root', 'data-x-id', $this->id . '_' . $loopIndex );
				
			} else {
				$this->set_attribute( '_root', 'data-x-id', $this->id );
			}
	
		} 

		echo "<div {$this->render_attributes( '_root' )}></div>";

		
	}
}
