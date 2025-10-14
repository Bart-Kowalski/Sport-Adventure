<?php

	class WS_Form_Config_Option extends WS_Form_Config {

		// Configuration - Options
		public static function get_options($process_options = true) {

			// File upload checks
			$upload_checks = WS_Form_Common::uploads_check();
			$max_upload_size = $upload_checks['max_upload_size'];
			$max_uploads = $upload_checks['max_uploads'];

			$options = array(

				// Basic
				'basic'		=> array(

					'label'		=>	__('Basic', 'ws-form'),
					'groups'	=>	array(

						'preview'	=>	array(

							'heading'		=>	__('Preview', 'ws-form'),
							'fields'	=>	array(

								'helper_live_preview'	=>	array(

									'label'		=>	__('Live', 'ws-form'),
									'type'		=>	'checkbox',
									'help'		=>	sprintf('%s <a href="%s" target="_blank">%s</a>', __('Update the form preview window automatically.', 'ws-form'), WS_Form_Common::get_plugin_website_url('/knowledgebase/previewing-forms/'), __('Learn more', 'ws-form')),
									'admin'		=>	true,
									'default'	=>	true,
								),

								'preview_template'	=> array(

									'label'				=>	__('Template', 'ws-form'),
									'type'				=>	'select',
									'help'				=>	__('Page template used for previewing forms.', 'ws-form'),
									'options'			=>	array(),	// Populated below
									'default'			=>	''
								)
							)
						),

						'debug'	=>	array(

							'heading'		=>	__('Debug', 'ws-form'),
							'fields'	=>	array(
								'helper_debug'	=> array(

									'label'		=>	__('Debug Console', 'ws-form'),
									'type'		=>	'select',
									'help'		=>	sprintf('%s <a href="%s" target="_blank">%s</a>', __('Choose when to show the debug console.', 'ws-form'), WS_Form_Common::get_plugin_website_url('/knowledgebase/debug-console/'), __('Learn more', 'ws-form')),
									'default'	=>	'',
									'options'	=>	array(

										'off'				=>	array('text' => __('Off', 'ws-form')),
										'administrator'		=>	array('text' => __('Administrators only', 'ws-form')),
										'on'				=>	array('text' => __('Show always', 'ws-form'))
									),
									'mode'	=>	array(

										'basic'		=>	'off',
										'advanced'	=>	'administrator'
									)
								)
							)
						),

						'layout_editor'	=>	array(

							'heading'	=>	__('Layout Editor', 'ws-form'),
							'fields'	=>	array(

								'mode'	=> array(

									'label'		=>	__('Mode', 'ws-form'),
									'type'		=>	'select',
									'help'		=>	__('Advanced mode allows variables and calculations to be used in field settings.', 'ws-form'),
									'default'	=>	'basic',
									'admin'		=>	true,
									'options'	=>	array(

										'basic'		=>	array('text' => __('Basic', 'ws-form')),
										'advanced'	=>	array('text' => __('Advanced', 'ws-form'))
									)
								),

								'helper_columns'	=>	array(

									'label'		=>	__('Column Guidelines', 'ws-form'),
									'type'		=>	'select',
									'help'		=>	__('Show column guidelines when editing forms?', 'ws-form'),
									'options'	=>	array(

										'off'		=>	array('text' => __('Off', 'ws-form')),
										'resize'	=>	array('text' => __('On resize', 'ws-form')),
										'on'		=>	array('text' => __('Always on', 'ws-form')),
									),
									'default'	=>	'resize',
									'admin'		=>	true
								),

								'publish_auto'	=>	array(

									'label'		=>	__('Auto Publish', 'ws-form'),
									'type'		=>	'checkbox',
									'help'		=>	sprintf(

										'%s <a href="%s" target="_blank">%s</a>',
										__('If checked, changes made to your form will be automatically published.', 'ws-form'),
										WS_Form_Common::get_plugin_website_url('/knowledgebase/publishing-forms/'),
										__('Learn more', 'ws-form')
									),
									'default'	=>	false
								),

								'helper_breakpoint_width'	=>	array(

									'label'		=>	__('Breakpoint Widths', 'ws-form'),
									'type'		=>	'checkbox',
									'help'		=>	__('Resize the width of the form to the selected breakpoint.', 'ws-form'),
									'default'	=>	true,
									'admin'		=>	true
								),

								'helper_compatibility' => array(

									'label'		=>	__('HTML Compatibility Helpers', 'ws-form'),
									'type'		=>	'checkbox',
									'help'		=>	__('Show HTML compatibility helper links (Data from', 'ws-form') . ' <a href="' . WS_FORM_COMPATIBILITY_URL . '" target="_blank">' . WS_FORM_COMPATIBILITY_NAME . '</a>).',
									'default'	=>	false,
									'admin'		=>	true,
									'mode'		=>	array(

										'basic'		=>	false,
										'advanced'	=>	true
									),
								),

								'helper_icon_tooltip' => array(

									'label'		=>	__('Icon Tooltips', 'ws-form'),
									'type'		=>	'checkbox',
									'help'		=>	__('Show icon tooltips.', 'ws-form'),
									'default'	=>	true,
									'admin'		=>	true
								),

								'helper_field_help' => array(

									'label'		=>	__('Sidebar Help Text', 'ws-form'),
									'type'		=>	'checkbox',
									'help'		=>	__('Show help text in sidebar.', 'ws-form'),
									'default'	=>	true,
									'admin'		=>	true
								),

								'helper_section_id'	=> array(

									'label'		=>	__('Section IDs', 'ws-form'),
									'type'		=>	'checkbox',
									'help'		=>	__('Show IDs on sections.', 'ws-form'),
									'default'	=>	true,
									'admin'		=>	true,
									'mode'		=>	array(

										'basic'		=>	false,
										'advanced'	=>	true
									),
								),

								'helper_field_id'	=> array(

									'label'		=>	__('Field IDs', 'ws-form'),
									'type'		=>	'checkbox',
									'help'		=>	__('Show IDs on fields. Useful for #field(nnn) variables.', 'ws-form'),
									'default'	=>	true,
									'admin'		=>	true
								),

								'helper_select2_on_mousedown'	=> array(

									'label'		=>	__('Searchable Sidebar Dropdowns', 'ws-form'),
									'type'		=>	'checkbox',
									'help'		=>	sprintf(

										'%s<br><em>%s</em>',

										__('If enabled, dropdown settings in the sidebar with 20 or more options will become searchable.', 'ws-form'),

										__('Experimental', 'ws-form')
									),
									'default'	=>	false,
									'admin'		=>	true
								)
							)
						),

						'statistics'	=>	array(

							'heading'	=>	__('Statistics', 'ws-form'),
							'fields'	=>	array(

								'disable_form_stats'			=>	array(

									'label'		=>	__('Disable', 'ws-form'),
									'type'		=>	'checkbox',
									'default'	=>	false,
									'help'		=>	sprintf(

										'%s <a href="%s" target="_blank">%s</a>',
										sprintf(

											/* translators: %s: WS Form */
											__('If checked, %s will stop gathering statistical data about forms.', 'ws-form'),

											WS_FORM_NAME_GENERIC

										),
										WS_Form_Common::get_plugin_website_url('/knowledgebase/statistics/'),
										__('Learn more', 'ws-form')
									)
								),

								'admin_form_stats'			=>	array(

									'label'		=>	__('Include Admin Traffic', 'ws-form'),
									'type'		=>	'checkbox',
									'default'	=>	false,
									'help'		=>	__('Check this to include traffic from administrators in form statistics.', 'ws-form')
								),

								'add_view_method'	=>	array(

									'label'		=>	__('Method', 'ws-form'),
									'type'		=>	'select',
									'help'		=>	sprintf('%s <a href="%s" target="_blank">%s</a>', sprintf(

										/* translators: %s: WS Form */
										__('Select how %s should gather form statistics.', 'ws-form'),

										WS_FORM_NAME_GENERIC

									), WS_Form_Common::get_plugin_website_url('/knowledgebase/global-settings/'), __('Learn more', 'ws-form')),
									'default'	=>	'',
									'options'	=>	array()
								)
							)
						),
						'admin'	=>	array(

							'heading'	=>	__('Administration', 'ws-form'),
							'fields'	=>	array(

								'disable_count_submit_unread'	=>	array(

									'label'		=>	__('Disable Unread Submission Bubbles', 'ws-form'),
									'type'		=>	'checkbox',
									'default'	=>	false
								),

								'disable_toolbar_menu'			=>	array(

									'label'		=>	__('Disable Toolbar Menu', 'ws-form'),
									'type'		=>	'checkbox',
									'default'	=>	false,
									'help'		=>	sprintf(

										/* translators: %s: WS Form */
										__('If checked, the %s toolbar menu will not be shown.', 'ws-form'),

										WS_FORM_NAME_GENERIC
									)
								),

								'disable_translation'			=>	array(

									'label'		=>	__('Disable Translation', 'ws-form'),
									'type'		=>	'checkbox',
									'default'	=>	false
								)
							)
						)
					)
				),

				// Advanced
				'advanced'	=> array(

					'label'		=>	__('Advanced', 'ws-form'),
					'groups'	=>	array(

						'performance'	=>	array(

							'heading'		=>	__('Performance', 'ws-form'),
							'fields'	=>	array(

								'enqueue_dynamic'	=>	array(

									'label'		=>	__('Dynamic Enqueuing', 'ws-form'),
									'type'		=>	'checkbox',
									'help'		=>	__('Should WS Form dynamically enqueue CSS and JavaScript components? (Recommended)', 'ws-form'),
									'default'	=>	true
								),
							),
						),

						'javascript'	=>	array(

							'heading'	=>	__('JavaScript', 'ws-form'),
							'fields'	=>	array(

								'js_defer'	=>	array(

									'label'		=>	__('Defer', 'ws-form'),
									'type'		=>	'checkbox',
									'help'		=>	__('If checked, scripts will be executed after the document has been parsed.', 'ws-form'),
									'default'	=>	''
								),

								'jquery_footer'	=>	array(

									'label'		=>	__('Enqueue in Footer', 'ws-form'),
									'type'		=>	'checkbox',
									'help'		=>	__('If checked, scripts will be enqueued in the footer.', 'ws-form'),
									'default'	=>	''
								),
								'jquery_source'	=>	array(

									'label'		=>	__('Source', 'ws-form'),
									'type'		=>	'select',
									'help'		=>	__('Where should external libraries load from? Use \'Local\' if you are using optimization plugins.', 'ws-form'),
									'default'	=>	'local',
									'public'	=>	true,
									'options'	=>	array(

										'local'		=>	array('text' => __('Local (Recommended)', 'ws-form')),
										'cdn'		=>	array('text' => __('CDN', 'ws-form'))
									)
								),

								'ui_datepicker'	=>	array(

									'label'		=>	__('Date/Time Picker', 'ws-form'),
									'type'		=>	'select',
									'help'		=>	__('When should date fields use a Date/Time Picker component?', 'ws-form'),
									'default'	=>	'on',
									'public'	=>	true,
									'options'	=>	array(

										'on'		=>	array('text' => __('Always (Recommended)', 'ws-form')),
										'native'	=>	array('text' => __('If native not available', 'ws-form')),
										'off'		=>	array('text' => __('Never', 'ws-form'))
									)
								),

								'ui_color'	=>	array(

									'label'		=>	__('Color Picker', 'ws-form'),
									'type'		=>	'select',
									'help'		=>	__('When should color fields use a Color picker component?', 'ws-form'),
									'default'	=>	'on',
									'public'	=>	true,
									'options'	=>	array(

										'on'		=>	array('text' => __('Always (Recommended)', 'ws-form')),
										'native'	=>	array('text' => __('If native not available', 'ws-form')),
										'off'		=>	array('text' => __('Never', 'ws-form'))
									)
								),
							)
						),
						'upload'	=>	array(

							'heading'	=>	__('File Uploads', 'ws-form'),
							'fields'	=>	array(

								'max_upload_size'	=>	array(

									'label'		=>	__('Maximum File Size (Bytes)', 'ws-form'),
									'type'		=>	'number',
									'default'	=>	'#max_upload_size',
									'minimum'	=>	0,
									'maximum'	=>	'#max_upload_size',
									'button'	=>	'wsf-max-upload-size',
									'help'		=>	sprintf(

										/* translators: %u: Server maximum filesize */
										__('Server maximum filesize (bytes): %u', 'ws-form'),
										$max_upload_size
									)
								),

								'max_uploads'	=>	array(

									'label'		=>	__('Maximum Files', 'ws-form'),
									'type'		=>	'number',
									'default'	=>	'#max_uploads',
									'minimum'	=>	0,
									'maximum'	=>	'#max_uploads',
									'button'	=>	'wsf-max-uploads',
									'help'		=>	sprintf(

										/* translators: %u: Server maximum files */
										__('Server maximum files: %u', 'ws-form'),
										$max_uploads
									)
								)
							)
						),
						'cookie'	=>	array(

							'heading'	=>	__('Cookies', 'ws-form'),
							'fields'	=>	array(

								'cookie_timeout'	=>	array(

									'label'		=>	__('Cookie Timeout (Seconds)', 'ws-form'),
									'type'		=>	'number',
									'help'		=>	__('Duration in seconds cookies are valid for.', 'ws-form'),
									'default'	=>	60 * 60 * 24 * 28,	// 28 day
									'public'	=>	true
								),

								'cookie_prefix'	=>	array(

									'label'		=>	__('Cookie Prefix', 'ws-form'),
									'type'		=>	'text',
									'help'		=>	__('We recommend leaving this value as it is.', 'ws-form'),
									'default'	=>	WS_FORM_IDENTIFIER,
									'public'	=>	true
								),

								'cookie_hash'	=>	array(

									'label'		=>	__('Enable Save Cookie', 'ws-form'),
									'type'		=>	'checkbox',
									'help'		=>	__('If checked a cookie will be set when a form save button is clicked to later recall the form content.', 'ws-form'),
									'default'	=>	true,
									'public'	=>	true
								)
							)
						),

						'google'	=>	array(

							'heading'	=>	__('Google', 'ws-form'),
							'fields'	=>	array(

								'api_key_google_map'	=>	array(

									'label'		=>	__('API Key', 'ws-form'),
									'type'		=>	'text',
									'default'	=>	'',
									'help'		=>	sprintf('%s <a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank">%s</a>', __('Need an API key?', 'ws-form'), __('Learn more', 'ws-form')),
									'admin'		=>	true,
									'public'	=>	true
								),

								'google_maps_js_api_version'	=>	array(

									'label'		=>	__('API Version', 'ws-form'),
									'type'		=>	'select',
									'options'	=>	array(

										'' => array('text' => __('Places API (Legacy)', 'ws-form')),
										'2' => array('text' => __('Places API (New)', 'ws-form')),
									),
									'help'		=>	__('For Google accounts registered after March 1st, 2025, choose Places API (New).', 'ws-form'),
									'default'	=>	'',
									'public'	=>	true
								)
							)
						),

						'geo'	=>	array(

							'heading'	=>	__('Geolocation Lookup by IP', 'ws-form'),
							'fields'	=>	array(

								'ip_lookup_method' => array(

									'label'		=>	__('Service', 'ws-form'),
									'type'		=>	'select',
									'options'	=>	array(

										'' => array('text' => __('geoplugin.com', 'ws-form')),
										'ipapi' => array('text' => __('ip-api.com', 'ws-form')),
										'ipapico' => array('text' => __('ipapi.co (Recommended)', 'ws-form')),
										'ipinfo' => array('text' => __('ipinfo.io', 'ws-form'))
									),
									'default'	=>	'ipapico'
								),

								'ip_lookup_geoplugin_key' => array(

									'label'		=>	__('geoplugin.com API Key', 'ws-form'),
									'type'		=>	'text',
									'default'	=>	'',
									'help'		=>	sprintf(

										'%s <a href="https://www.geoplugin.com" target="_blank">%s</a>',

										__('If you are using the commercial version of geoplugin.com, please enter your API key. Used for server-side tracking only.', 'ws-form'),
										__('Learn more', 'ws-form')
									)
								),

								'ip_lookup_ipapi_key' => array(

									'label'		=>	__('ip-api.com API Key', 'ws-form'),
									'type'		=>	'text',
									'default'	=>	'',
									'help'		=>	sprintf(

										'%s <a href="https://ip-api.com" target="_blank">%s</a>',

										__('If you are using the commercial version of ip-api.com, please enter your API key. Used for server-side tracking only.', 'ws-form'),
										__('Learn more', 'ws-form')
									)
								),

								'ip_lookup_ipapico_key' => array(

									'label'		=>	__('ipapi.co API Key', 'ws-form'),
									'type'		=>	'text',
									'default'	=>	'',
									'help'		=>	sprintf(

										'%s <a href="https://ipapi.co" target="_blank">%s</a>',

										__('If you are using the commercial version of ipapi.co, please enter your API key. Used for server-side tracking only.', 'ws-form'),
										__('Learn more', 'ws-form')
									)
								),

								'ip_lookup_ipinfo_key' => array(

									'label'		=>	__('ipinfo.io API Key', 'ws-form'),
									'type'		=>	'text',
									'default'	=>	'',
									'help'		=>	sprintf(

										'%s <a href="https://ipinfo.io" target="_blank">%s</a>',

										__('If you are using the commercial version of ipinfo.io, please enter your API key. Used for server-side tracking only.', 'ws-form'),
										__('Learn more', 'ws-form')
									)
								)
							)
						),

						'tracking'	=>	array(

							'heading'	=>	__('Tracking Links', 'ws-form'),
							'fields'	=>	array(


								'ip_lookup_url_mask' => array(

									'label'		=>	__('URL Mask - IP Lookup', 'ws-form'),
									'type'		=>	'text',
									'default'	=>	'https://whatismyipaddress.com/ip/#value',
									'admin'		=>	true,
									'help'		=>	__('#value will be replaced with the tracking IP address.', 'ws-form')
								),

								'latlon_lookup_url_mask' => array(

									'label'		=>	__('URL Mask - Lat/Lon Lookup', 'ws-form'),
									'type'		=>	'text',
									'default'	=>	'https://www.google.com/maps/search/?api=1&query=#value',
									'admin'		=>	true,
									'help'		=>	__('#value will be replaced with latitude,longitude.', 'ws-form')
								)
							)
						),

						'submit'	=>	array(

							'heading'	=>	__('Submissions', 'ws-form'),
							'fields'	=>	array(

								'submit_edit_in_preview'		=>	array(

									'label'		=>	__('Enable Edit in Preview', 'ws-form'),
									'type'		=>	'checkbox',
									'default'	=>	false,
									'help'		=>	__("If checked 'Edit in Preview' will be enabled on submissions. This allows submissions to be edited in form preview mode.<br /><strong>Important:</strong> Actions will run again if the form is resubmitted, resaved or reprocessed with conditional logic.", 'ws-form')
								)
							)
						)
					)
				),

				// Styling
				'styling'	=> array(

					'label'		=>	__('Styling', 'ws-form'),
					'groups'	=>	array(

						'markup'	=>	array(

							'heading'		=>	__('Markup', 'ws-form'),
							'fields'	=>	array(

								'framework'	=> array(

									'label'			=>	__('Framework', 'ws-form'),
									'type'			=>	'select',
									'help'			=>	__('Framework used for rendering the front-end HTML.', 'ws-form'),
									'options'		=>	array(),	// Populated below
									'default'		=>	WS_FORM_DEFAULT_FRAMEWORK,
									'button'		=>	'wsf-framework-detect',
									'admin'			=>	true,
									'public'		=>	true,
									'data_change'	=>	'reload'
								),

								'css_layout'	=>	array(

									'label'		=>	__('Layout CSS', 'ws-form'),
									'type'		=>	'checkbox',
									'help'		=>	__('Should the layout CSS be rendered?', 'ws-form'),
									'default'	=>	true,
									'public'	=>	true,
									'condition'	=>	array('framework' => 'ws-form')
								),

								(WS_Form_Common::styler_enabled() ? 'css_style' : 'css_skin')	=>	array(

									'label'		=>	(WS_Form_Common::styler_enabled() ? __('Style CSS', 'ws-form') : __('Skin CSS', 'ws-form')),
									'type'		=>	'checkbox',
									'help'		=>	sprintf(

										'%s <a href="%s">%s</a>',
										__('Should the style CSS be rendered?', 'ws-form'),
										WS_Form_Common::styler_enabled() ? WS_Form_Common::get_admin_url('ws-form-style') : admin_url('customize.php?return=%2Fwp-admin%2Fadmin.php%3Fpage%3Dws-form-settings%26tab%3Dappearance'),
										WS_Form_Common::styler_enabled() ? __('View styles', 'ws-form') : __('Customize', 'ws-form'),
									),
									'default'	=>	true,
									'public'	=>	true,
									'condition'	=>	array('framework' => 'ws-form')
								),

								'framework_column_count'	=> array(

									'label'		=>	__('Column Count', 'ws-form'),
									'type'		=>	'select_number',
									'default'	=>	12,
									'minimum'	=>	1,
									'maximum'	=>	24,
									'admin'		=>	true,
									'public'	=>	true,
									'absint'	=>	true,
									'help'		=>	__('We recommend leaving this setting at 12.', 'ws-form')
								),
							),
						),

						'scheme'	=>	array(

							'heading'		=>	__('Scheme', 'ws-form'),
							'fields'	=>	array(

								'scheme'	=> array(

									'label'			=>	__('Color Scheme', 'ws-form'),
									'type'			=>	'select',
									'help'			=>	__('Is your website scheme light or dark?', 'ws-form'),
									'options'		=>	array(
										'light' => array('text' => __('Light', 'ws-form')),
										'dark' => array('text' => __('Dark', 'ws-form')),
									),
									'default'		=>	'light',
									'public'		=>	true,
									'condition'	=>	array('framework' => 'ws-form'),
								),

								'scheme_auto'	=> array(

									'label'			=>	__('Auto Color Scheme', 'ws-form'),
									'type'			=>	'checkbox',
									'help'			=>	__('Should WS Form detect the preferred color scheme?', 'ws-form'),
									'default'		=>	'on',
									'public'		=>	true,
									'condition'	=>	array('framework' => 'ws-form'),
								),
							),
						),
						'performance'	=>	array(

							'heading'		=>	__('Performance', 'ws-form'),
							'fields'	=>	array(

								'css_compile'	=>	array(

									'label'		=>	__('Compile CSS', 'ws-form'),
									'type'		=>	'checkbox',
									'help'		=>	__('Should CSS be precompiled? (Recommended)', 'ws-form'),
									'default'	=>	true,
									'condition'	=>	array('framework' => 'ws-form')
								),

								'css_inline'	=>	array(

									'label'		=>	__('Inline CSS', 'ws-form'),
									'type'		=>	'checkbox',
									'help'		=>	__('Should CSS be rendered inline? (Recommended)', 'ws-form'),
									'default'	=>	true,
									'condition'	=>	array('framework' => 'ws-form')
								),

								'css_cache_duration'	=>	array(

									'label'		=>	__('CSS Cache Duration', 'ws-form'),
									'type'		=>	'number',
									'help'		=>	__('Expires header duration in seconds for CSS.', 'ws-form'),
									'default'	=>	WS_FORM_CSS_CACHE_DURATION_DEFAULT,
									'public'	=>	true,
									'condition'	=>	array('framework' => 'ws-form')
								),
							)
						),
					),
				),

				// E-Commerce
				'ecommerce'	=> array(

					'label'		=>	__('E-Commerce', 'ws-form'),
					'groups'	=>	array(

						'price'	=>	array(

							'heading'	=>	__('Prices', 'ws-form'),
							'fields'	=>	array(

								'currency'	=> array(

									'label'		=>	__('Currency', 'ws-form'),
									'type'		=>	'select',
									'default'	=>	WS_Form_Common::get_currency_default(),
									'options'	=>	array(),
									'admin'		=>	true,
									'public'	=>	true
								),

								'currency_position'	=> array(

									'label'		=>	__('Currency Position', 'ws-form'),
									'type'		=>	'select',
									'default'	=>	'left',
									'options'	=>	array(
										'left'			=>	array('text' => __('Left', 'ws-form')),
										'right'			=>	array('text' => __('Right', 'ws-form')),
										'left_space'	=>	array('text' => __('Left with space', 'ws-form')),
										'right_space'	=>	array('text' => __('Right with space', 'ws-form'))
									),
									'admin'		=>	true,
									'public'	=>	true
								),

								'price_thousand_separator'	=> array(

									'label'		=>	__('Thousand Separator', 'ws-form'),
									'type'		=>	'text',
									'default'	=>	',',
									'admin'		=>	true,
									'public'	=>	true
								),

								'price_decimal_separator'	=> array(

									'label'		=>	__('Decimal Separator', 'ws-form'),
									'type'		=>	'text',
									'default'	=>	'.',
									'admin'		=>	true,
									'public'	=>	true
								),

								'price_decimals'	=> array(

									'label'		=>	__('Number Of Decimals', 'ws-form'),
									'type'		=>	'number',
									'default'	=>	'2',
									'admin'		=>	true,
									'public'	=>	true
								)
							)
						),

						'submission'	=>	array(

							'heading'	=>	__('Submissions', 'ws-form'),
							'fields'	=>	array(

								'submit_edit_ecommerce'	=>	array(

									'label'		=>	__('Allow Price Field Edits', 'ws-form'),
									'type'		=>	'checkbox',
									'help'		=>	__('If checked, prices can be edited in submissions. Note that changes to prices will not recalculate values in the rest of the submission.', 'ws-form'),
									'default'	=>	'',
									'admin'		=>	true
								)
							)
						)
					)
				),
				// System
				'system'	=> array(

					'label'		=>	__('System', 'ws-form'),
					'fields'	=>	array(

						'system' => array(

							'label'		=>	__('System Report', 'ws-form'),
							'type'		=>	'static'
						),

						'setup'	=> array(

							'type'		=>	'hidden',
							'default'	=>	false
						)
					)
				),
				// License
				'license'	=> array(

					'label'		=>	__('License', 'ws-form'),
					'fields'	=>	array(

						'version'	=>	array(

							'label'		=>	__('Version', 'ws-form'),
							'type'		=>	'static'
						),

						'license_key'	=>	array(

							'label'		=>	__('License Key', 'ws-form'),
							'type'		=>	'license',

							'help'		=>	sprintf('%s <a href="%s" target="_blank">%s</a><br><em>', 

								esc_html(sprintf(

									/* translators: %1$s: Presentable name (e.g. WS Form PRO) */
									__('Enter your %1$s license key here. If you have a Freelance or Agency license, enter your %1$s key.', 'ws-form'),
									WS_FORM_NAME_PRESENTABLE
								)),

								esc_url(WS_Form_Common::get_plugin_website_url('/knowledgebase/licensing/')),
								esc_html(__('Learn more', 'ws-form'))
							),
							'button'	=>	'wsf-license'
						)
					)
				),
				// Data
				'data'	=> array(

					'label'		=>	__('Data', 'ws-form'),
					'groups'	=>	array(

						'form'	=>	array(

							'heading'	=>	__('Forms', 'ws-form'),
							'fields'	=>	array(

								'form_stat_reset' => array(

									'label'		=>	__('Reset Statistics', 'ws-form'),
									'type'		=>	'select',
									'save'		=>	false,
									'button'	=>	'wsf-form-stat-reset'
								)
							)
						),

						'encryption'	=>	array(

							'heading'	=>	__('Encryption', 'ws-form'),
							'fields'	=>	array(

								'encryption_enabled' => array(

									'label'		=>	__('Enable Data Encryption', 'ws-form'),
									'type'		=>	'checkbox',
									'default'	=>	false,
									'help'		=>	sprintf(

										'<a href="%s" target="_blank">%s</a>',
										esc_url(WS_Form_Common::get_plugin_website_url('/knowledgebase/data-encryption/')),
										esc_html(__('Learn more', 'ws-form'))
									)
								),

								'encryption_status' => array(

									'label'		=>	__('Encryption Status', 'ws-form'),
									'type'		=>	'static'
								)
							)
						),
						'uninstall'	=>	array(

							'heading'	=>	__('Uninstall', 'ws-form'),
							'fields'	=>	array(

								'uninstall_options' => array(

									'label'		=>	__('Delete Plugin Settings on Uninstall', 'ws-form'),
									'type'		=>	'checkbox',
									'default'	=>	false,
									'help'		=>	sprintf(

										'<p><strong style="color: #bb0000;">%s:</strong> %s</p>',
										esc_html(__('Caution', 'ws-form')),
										esc_html(__('If you enable this setting and uninstall the plugin this data cannot be recovered.', 'ws-form'))
									)
								),

								'uninstall_database' => array(

									'label'		=>	__('Delete Database Tables on Uninstall', 'ws-form'),
									'type'		=>	'checkbox',
									'default'	=>	false,
									'help'		=>	sprintf(

										'<p><strong style="color: #bb0000;">%s:</strong> %s</p>',
										esc_html(__('Caution', 'ws-form')),
										esc_html(__('If you enable this setting and uninstall the plugin this data cannot be recovered.', 'ws-form'))
									)
								)
							)
						)
					)
				),

				// Spam Protection
				'spam_protection'	=> array(

					'label'		=>	__('Spam Protection', 'ws-form'),
					'groups'	=>	array(

						'recaptcha'	=>	array(

							'heading'	=> 'reCAPTCHA',
							'fields'	=>	array(

								'recaptcha_site_key' => array(

									'label'		=>	__('Site Key', 'ws-form'),
									'type'		=>	'key',
									'help'		=>	sprintf(
										'%s <a href="%s" target="_blank">%s</a>',
										esc_html(sprintf(

											/* translators: %s: Brand name */
											__('%s site key.', 'ws-form'),
											'reCAPTCHA'
										)),
										esc_url(WS_Form_Common::get_plugin_website_url('/knowledgebase/recaptcha/')),
										esc_html(__('Learn more', 'ws-form'))
									),
									'default'		=>	'',
									'admin'			=>	true,
									'public'		=>	true
								),

								'recaptcha_secret_key' => array(

									'label'		=>	__('Secret Key', 'ws-form'),
									'type'		=>	'key',
									'help'		=>	sprintf(
										'%s <a href="%s" target="_blank">%s</a>',
										esc_html(sprintf(

											/* translators: %s: Brand name */
											__('%s secret key.', 'ws-form'),
											'reCAPTCHA'
										)),
										esc_url(WS_Form_Common::get_plugin_website_url('/knowledgebase/recaptcha/')),
										esc_html(__('Learn more', 'ws-form'))
									),
									'default'		=>	'',
									'admin'			=>	true
								),

								// reCAPTCHA - Default type
								'recaptcha_recaptcha_type' => array(

									'label'						=>	__('Default reCAPTCHA Type', 'ws-form'),
									'type'						=>	'select',
									'help'						=>	__('Select the default type used for new reCAPTCHA fields.', 'ws-form'),
									'options'					=>	array(

										'v2_default' => array('text' => __('Version 2 - Default', 'ws-form')),
										'v2_invisible' => array('text' => __('Version 2 - Invisible', 'ws-form')),
										'v3_default' => array('text' => __('Version 3', 'ws-form')),
									),
									'default'					=>	'v2_default'
								)
							)
						),

						'hcaptcha'	=>	array(

							'heading'	=>	'hCaptcha',
							'fields'	=>	array(

								'hcaptcha_site_key' => array(

									'label'		=>	__('Site Key', 'ws-form'),
									'type'		=>	'key',
									'help'		=>	sprintf(
										'%s <a href="%s" target="_blank">%s</a>',
										esc_html(sprintf(

											/* translators: %s: Brand name */
											__('%s site key.', 'ws-form'),
											'hCaptcha'
										)),
										esc_url(WS_Form_Common::get_plugin_website_url('/knowledgebase/hcaptcha/')),
										esc_html(__('Learn more', 'ws-form'))
									),
									'default'		=>	'',
									'admin'			=>	true,
									'public'		=>	true
								),

								'hcaptcha_secret_key' => array(

									'label'		=>	__('Secret Key', 'ws-form'),
									'type'		=>	'key',
									'help'		=>	sprintf(
										'%s <a href="%s" target="_blank">%s</a>',
										esc_html(sprintf(

											/* translators: %s: Brand name */
											__('%s secret key.', 'ws-form'),
											'hCaptcha'
										)),
										esc_url(WS_Form_Common::get_plugin_website_url('/knowledgebase/hcaptcha/')),
										esc_html(__('Learn more', 'ws-form'))
									),
									'default'		=>	'',
									'admin'			=>	true
								)
							)
						),

						'turnstile'	=>	array(

							'heading'	=>	'Turnstile',
							'fields'	=>	array(

								'turnstile_site_key' => array(

									'label'		=>	__('Site Key', 'ws-form'),
									'type'		=>	'key',
									'help'		=>	sprintf(
										'%s <a href="%s" target="_blank">%s</a>',
										esc_html(sprintf(

											/* translators: %s: Brand name */
											__('%s site key.', 'ws-form'),
											'Turnstile'
										)),
										esc_url(WS_Form_Common::get_plugin_website_url('/knowledgebase/turnstile/')),
										esc_html(__('Learn more', 'ws-form'))
									),
									'default'		=>	'',
									'admin'			=>	true,
									'public'		=>	true
								),

								'turnstile_secret_key' => array(

									'label'		=>	__('Secret Key', 'ws-form'),
									'type'		=>	'key',
									'help'		=>	sprintf(
										'%s <a href="%s" target="_blank">%s</a>',
										esc_html(sprintf(

											/* translators: %s: Brand name */
											__('%s secret key.', 'ws-form'),
											'Turnstile'
										)),
										esc_url(WS_Form_Common::get_plugin_website_url('/knowledgebase/turnstile/')),
										esc_html(__('Learn more', 'ws-form'))
									),
									'default'		=>	'',
									'admin'			=>	true
								)
							)
						),

						'nonce'	=>	array(

							'heading'	=>	__('NONCE', 'ws-form'),
							'fields'	=>	array(

								'security_nonce'	=>	array(

									'label'		=>	__('Enable NONCE', 'ws-form'),
									'type'		=>	'checkbox',
									'help'		=>	sprintf(

										'%s <a href="https://wsform.com/knowledgebase/using-nonces-to-protect-against-spam/" target="_blank">%s</a><br />%s',

										__('Add a NONCE to all form submissions.', 'ws-form'),
										__('Learn more', 'ws-form'),
										__('If enabled we recommend keeping overall page caching to less than 10 hours.<br />NONCEs are always used on forms if a user is logged in.', 'ws-form')
									),
									'default'	=>	''
								)
							)
						)
					)
				),
				// Reporting
				'report'	=> array(

					'label'		=>	__('Reporting', 'ws-form'),
					'groups'	=>	array(

						'report_form_statistics'	=>	array(

							'heading'	=>	__('Form Statistics Email', 'ws-form'),
							'fields'	=>	array(

								'report_form_statistics_enable' => array(

									'label'		=>	__('Enable', 'ws-form'),
									'type'		=>	'checkbox',
									'default'	=>	false
								),

								'report_form_statistics_form_published' => array(

									'label'		=>	__('Published Forms', 'ws-form'),
									'type'		=>	'checkbox',
									'default'	=>	true,
									'help'		=>	__('Only include statistics from published forms.', 'ws-form')
								),

								'report_form_statistics_frequency' => array(

									'label'			=>	__('Frequency', 'ws-form'),
									'type'			=>	'select',
									'options'		=>	array(

										'daily'		=>	array('text' => __('Daily', 'ws-form')),
										'weekly'	=>	array('text' => __('Weekly', 'ws-form')),
										'monthly'	=>	array('text' => __('Monthly', 'ws-form')),
									),
									'default'		=>	'weekly',
									'help'			=>	__('How often should the report be emailed?', 'ws-form'),
									'data_change'	=>	'reload'
								),

								'report_form_statistics_day_of_week' => array(

									'label'			=>	__('Day to Send', 'ws-form'),
									'type'			=>	'select',
									'options'		=>	array(

										'0'	=>	array('text' => __('Monday', 'ws-form')),
										'1'	=>	array('text' => __('Tuesday', 'ws-form')),
										'2'	=>	array('text' => __('Wednesday', 'ws-form')),
										'3'	=>	array('text' => __('Thursday', 'ws-form')),
										'4'	=>	array('text' => __('Friday', 'ws-form')),
										'5'	=>	array('text' => __('Saturday', 'ws-form')),
										'6'	=>	array('text' => __('Sunday', 'ws-form'))
									),
									'default'		=>	'0',
									'help'			=>	__('What day of the week should the weekly report be sent?', 'ws-form'),
									'condition'		=>	array('report_form_statistics_frequency' => 'weekly')
								),

								'report_form_statistics_email_to' => array(

									'label'			=>	__('Email To', 'ws-form'),
									'type'			=>	'text',
									'placeholder'	=>	get_bloginfo('admin_email'),
									'default'		=>	'',
									'help'			=>	__('Separate multiple email addresses with spaces.', 'ws-form'),
									'button'		=>	'wsf-report-form-statistics-test'
								),

								'report_form_statistics_email_subject' => array(

									'label'			=>	__('Email Subject', 'ws-form'),
									'type'			=>	'text',
									'placeholder'	=>	__('WS Form - Form Statistics', 'ws-form'),
									'default'		=>	''
								)
							)
						),

						'report_submit_error'	=>	array(

							'heading'	=>	__('Form Submission Error Email', 'ws-form'),
							'fields'	=>	array(

								'report_submit_error_enable' => array(

									'label'		=>	__('Enable', 'ws-form'),
									'type'		=>	'checkbox',
									'default'	=>	false,
									'help'		=>	__('If enabled, WS Form will send an email if an error occurs when processing a form submission.', 'ws-form')
								),

								'report_submit_error_frequency' => array(

									'label'			=>	__('Frequency', 'ws-form'),
									'type'			=>	'select',
									'options'		=>	array(

										'all'		=>	array('text' => __('Real-time', 'ws-form')),
										'minute'	=>	array('text' => __('Once per minute', 'ws-form')),
										'hour'		=>	array('text' => __('Once per hour', 'ws-form')),
										'day'		=>	array('text' => __('Once per day', 'ws-form'))
									),
									'default'		=>	'minute',
									'help'			=>	__('How often should errors be emailed?', 'ws-form')
								),

								'report_submit_error_email_to' => array(

									'label'			=>	__('Email To', 'ws-form'),
									'type'			=>	'text',
									'placeholder'	=>	get_bloginfo('admin_email'),
									'default'		=>	'',
									'help'			=>	__('Separate multiple email addresses with spaces.', 'ws-form'),
									'button'		=>	'wsf-submit-error-test'
								),

								'report_submit_error_email_subject' => array(

									'label'			=>	__('Email Subject', 'ws-form'),
									'type'			=>	'text',
									'placeholder'	=>	__('WS Form - Form Submission Error', 'ws-form'),
									'default'		=>	''
								)
							)
						)
					)
				),
				'variable' => array(

					'label'		=>	__('Variables', 'ws-form'),

					'groups'	=>	array(

						'variable_email_logo'	=>	array(

							'heading'		=>	__('Variable: #email_logo', 'ws-form'),

							'fields'	=>	array(

								'action_email_logo'	=>	array(

									'label'		=>	__('Image', 'ws-form'),
									'type'		=>	'image',
									'button'	=>	'wsf-image',
									'help'		=>	__('Use #email_logo in your template to add this logo.', 'ws-form')
								),

								'action_email_logo_size'	=>	array(

									'label'		=>	__('Size', 'ws-form'),
									'type'		=>	'image_size',
									'default'	=>	'full',
									'help'		=>	__('Recommended max dimensions: 400 x 200 pixels.', 'ws-form')
								)
							)
						),

						'variable_email_submission'	=>	array(

							'heading'		=>	'Variable: #email_submission',

							'fields'	=>	array(

								'action_email_group_labels'	=> array(

									'label'		=>	__('Tab Labels', 'ws-form'),
									'type'		=>	'select',
									'default'	=>	'auto',
									'options'	=>	array(

										'auto'				=>	array('text' => __('Auto', 'ws-form')),
										'true'				=>	array('text' => __('Yes', 'ws-form')),
										'false'				=>	array('text' => __('No', 'ws-form'))
									),
									'help'		=>	__("Auto - Only shown if any fields are not empty and the 'Show Label' setting is enabled.<br />Yes - Only shown if the 'Show Label' setting is enabled for that tab.<br />No - Never shown.", 'ws-form')
								),

								'action_email_section_labels'	=> array(

									'label'		=>	__('Section Labels', 'ws-form'),
									'type'		=>	'select',
									'default'	=>	'auto',
									'options'	=>	array(

										'auto'				=>	array('text' => __('Auto', 'ws-form')),
										'true'				=>	array('text' => __('Yes', 'ws-form')),
										'false'				=>	array('text' => __('No', 'ws-form'))
									),
									'help'		=>	__("Auto - Only shown if any fields are not empty and the 'Show Label' setting is enabled.<br />Yes - Only shown if the 'Show Label' setting is enabled.<br />No - Never shown.", 'ws-form')
								),

								'action_email_field_labels'	=> array(

									'label'		=>	__('Field Labels', 'ws-form'),
									'type'		=>	'select',
									'default'	=>	'auto',
									'options'	=>	array(

										'auto'				=>	array('text' => __("Auto", 'ws-form')),
										'true'				=>	array('text' => __('Yes', 'ws-form')),
										'false'				=>	array('text' => __('No', 'ws-form'))
									),
									'help'		=>	__("Auto - Only shown if the 'Show Label' setting is enabled.<br />Yes - Always shown.<br />No - Never shown.", 'ws-form')
								),

								'action_email_static_fields'	=>	array(

									'label'		=>	__('Static Fields', 'ws-form'),
									'type'		=>	'checkbox',
									'default'	=>	true,
									'help'		=>	__('Show static fields such as text and HTML, if not excluded at a field level.', 'ws-form')
								),

								'action_email_exclude_empty'	=>	array(

									'label'		=>	__('Exclude Empty Fields', 'ws-form'),
									'type'		=>	'checkbox',
									'default'	=>	true,
									'help'		=>	__('Exclude empty fields.', 'ws-form')
								)
							)
						),

						'variable_field'	=>	array(

							'heading'		=>	'Variable: #field',

							'fields'	=>	array(

								'action_email_embed_images'	=>	array(

									'label'		=>	__('Show File Preview', 'ws-form'),
									'type'		=>	'checkbox',
									'default'	=>	true,
									'help'		=>	__('If checked, file and signature previews will be shown. Compatible with the WS Form (Private), WS Form (Public) and Media Library file handlers.', 'ws-form')
								),

								'action_email_embed_image_description'	=>	array(

									'label'		=>	__('Show File Name and Size', 'ws-form'),
									'type'		=>	'checkbox',
									'default'	=>	true,
									'help'		=>	__('If checked, file and signature file names and sizes will be shown. Compatible with the WS Form (Private), WS Form (Public) and Media Library file handlers.', 'ws-form')
								),

								'action_email_embed_image_link'	=>	array(

									'label'		=>	__('Link to Files', 'ws-form'),
									'type'		=>	'checkbox',
									'default'	=>	false,
									'help'		=>	__('If checked, file and signature files will have links added to them. The Send Email action has a separate setting for this. Compatible with the WS Form (Private), WS Form (Public) and Media Library file handlers.', 'ws-form')
								)
							)
						)
					)
				)
			);

			// Don't run the rest of this function to improve client side performance
			if(!$process_options) {

				// Apply filter
				$options = apply_filters('wsf_config_options', $options);

				return $options;
			}

			// Frameworks
			$frameworks = self::get_frameworks(false);
			foreach($frameworks['types'] as $key => $framework) {

				$name = $framework['name'];
				$options['styling']['groups']['markup']['fields']['framework']['options'][$key] = array('text' => $name);
			}

			// Templates
			$options['basic']['groups']['preview']['fields']['preview_template']['options'][''] = array('text' => __('Automatic', 'ws-form'));

			// Custom page templates
			$page_templates = array();
			$templates_path = get_template_directory();
			$templates = wp_get_theme()->get_page_templates();
			$templates['page.php'] = 'Page';
			$templates['singular.php'] = 'Singular';
			$templates['index.php'] = 'Index';
			$templates['front-page.php'] = 'Front Page';
			$templates['single-post.php'] = 'Single Post';
			$templates['single.php'] = 'Single';
			$templates['home.php'] = 'Home';

			foreach($templates as $template_file => $template_title) {

				// Build template path
				$template_file_full = $templates_path . '/' . $template_file;

				// Skip files that don't exist
				if(!file_exists($template_file_full)) { continue; }

				$page_templates[$template_file] = $template_title . ' (' . $template_file . ')';
			}

			asort($page_templates);

			foreach($page_templates as $template_file => $template_title) {

				$options['basic']['groups']['preview']['fields']['preview_template']['options'][$template_file] = array('text' => $template_title);
			}

			// Fallback
			$options['basic']['groups']['preview']['fields']['preview_template']['options']['fallback'] = array('text' => __('Blank Page', 'ws-form'));

			// Currencies
			$currencies = self::get_currencies();
			foreach($currencies as $code => $currency) {

				$options['ecommerce']['groups']['price']['fields']['currency']['options'][$code] = array('text' => $currency['n'] . ' (' . $currency['s'] . ')');
			}

			// Forms
			$options['data']['groups']['form']['fields']['form_stat_reset']['options'][''] = array('text' => __('Select...', 'ws-form'));

			$ws_form_form = new WS_Form_Form();
			$forms = $ws_form_form->db_read_all('', "NOT (status = 'trash')", 'label ASC', '', '', false);

			if($forms) {

				foreach($forms as $form) {

					if($form['count_stat_view'] > 0) {

						$options['data']['groups']['form']['fields']['form_stat_reset']['options'][$form['id']] = array('text' => esc_html(

							sprintf(

								'%s (%s: %u)',
								$form['label'],
								__('ID', 'ws-form'),
								$form['id']
							)
						));
					}
				}
			}

			// Add view method
			$options['basic']['groups']['statistics']['fields']['add_view_method']['options'][''] = array('text' => __('AJAX', 'ws-form'));

			// Check to see if PHP script is working
			$ws_form_form_stat = new WS_Form_Form_Stat();
			$add_view_php_valid = $ws_form_form_stat->add_view_php_valid();

			$options['basic']['groups']['statistics']['fields']['add_view_method']['options']['php'] = array('text' => sprintf('%s%s', __('AJAX Low Resource', 'ws-form'), ($add_view_php_valid['error'] ? sprintf(' (%s: %s)', __('Error', 'ws-form'), $add_view_php_valid['error_message']) : '')), 'disabled' => $add_view_php_valid['error']);

			$options['basic']['groups']['statistics']['fields']['add_view_method']['options']['server'] = array('text' => __('Server Side', 'ws-form'));
			// MCP server
			if(WS_Form_Common::mcp_adapter_enabled(false)) {

				$options['ai'] = array(

					'label'		=>	__('AI', 'ws-form'),
					'fields'	=>	array(

						'mcp_adapter'	=>	array(

							'label'		=>	__('MCP Server', 'ws-form'),
							'type'		=>	'checkbox',

							'help'		=>	sprintf('%s <a href="%s" target="_blank">%s</a><br><em>%s</em>', 

								esc_html(sprintf(

									/* translators: %s: Presentable name (e.g. WS Form PRO) */
									__('Enable the %s MCP (Model Context Protocol) server. Requires the WordPress Abilities API and MCP adapter.', 'ws-form'),
									WS_FORM_NAME_PRESENTABLE
								)),

								esc_url(WS_Form_Common::get_plugin_website_url('/knowledgebase/mcp-server/')),
								esc_html(__('Learn more', 'ws-form')),

								__('Experimental', 'ws-form')
							)
						),

						'mcp_adapter_url'	=>	array(

							'label'		=>	__('MCP Server URL', 'ws-form'),
							'type'		=>	'static'
						)						
					)
				);		
			}

			// Apply filter
			$options = apply_filters('wsf_config_options', $options);

			return $options;
		}
	}