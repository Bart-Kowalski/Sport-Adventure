<?php

	class WS_Form_API_Sidebar extends WS_Form_API {

		public function __construct() {

			// Call parent on WS_Form_API
			parent::__construct();
		}

		// API - POST - Sidebar width
		public function api_sidebar_width($parameters) {

			// Get width
			$sidebar_width = absint(WS_Form_Common::get_query_var_nonce('sidebar_width', '', $parameters));

			// Check width
			if($sidebar_width < WS_FORM_SIDEBAR_WIDTH_MIN) {

				$sidebar_width = WS_FORM_SIDEBAR_WIDTH_MIN;
			}
			if($sidebar_width > WS_FORM_SIDEBAR_WIDTH_MAX) {

				$sidebar_width = WS_FORM_SIDEBAR_WIDTH_MAX;
			}

			// Set option
			WS_Form_Common::option_set('sidebar_width', $sidebar_width);

			return array(

				'error' => false,
				'sidebar_width' => $sidebar_width
			);
		}
	}
