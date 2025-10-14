<?php

	class WS_Form_API_Block extends WS_Form_API {

		public function __construct() {

			// Call parent on WS_Form_API
			parent::__construct();
		}

		// API - Get forms
		public function api_get_forms($parameters) {

            $ws_form_form = new WS_Form_Form();
            $forms = $ws_form_form->db_read_all('', 'NOT status="trash"', 'label ASC, id ASC', '', '', false);

            return array_map(function ($form) {

                return array(

                    'id' => $form['id'],
                    'label' => sprintf(

                    	'%s (ID: %u)',

                    	esc_html($form['label']),
                    	$form['id']
                    )
                );

            }, $forms);
		}
	}
