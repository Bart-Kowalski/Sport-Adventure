<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Angie_WS_Form {

	public function __construct() {

		add_action( 'rest_api_init', [ $this, 'register_rest_routes' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
	}

	public function register_rest_routes() {

		// Get abilities
		$abilities = WS_Form_Config::get_abilities();

		// Register rest routes
		foreach($abilities as $ability_name => $ability) {

			register_rest_route( 

				WS_FORM_RESTFUL_NAMESPACE,
				sprintf('/angie/%s/', self::ability_name_to_angie_id($ability_name)),
				array(
					'methods' => 'POST',
					'callback' => $ability['execute_callback'],
					'permission_callback' => $ability['permission_callback'],
				)
			);
		}

		// Register endpoint for retrieving abilities, public data but we'll only return data if user is logged in (i.e. can see Angie)
		register_rest_route(

			WS_FORM_RESTFUL_NAMESPACE,
			'/angie/abilities/',
			array(
				'methods' => 'POST',
				'callback' => array($this, 'api_abilities'),
				'permission_callback' => 'is_user_logged_in'
			)
		);
	}

	public function api_abilities() {

		// Get abilities
		$abilities = WS_Form_Config::get_abilities();

		$abilities_return = array();

		foreach($abilities as $ability_name => $ability) {

			$abilities_return[] = array(

				'type' => $ability['type'],
				'name' => self::ability_name_to_angie_id($ability_name),
				'label' => $ability['label'],
				'description' => $ability['description'],
				'input_schema' => $ability['input_schema'],
				'output_schema' => $ability['output_schema'],
			);
		}

		return $abilities_return;
	}

	public function enqueue_scripts() {

		wp_enqueue_script_module(
			'angie-ws-form-mcp-server',
			plugin_dir_url( __FILE__ ) . 'angie-ws-form-mcp-server.mjs',
			[],
			WS_FORM_VERSION,
			true
		);
	}

	// Required because WordPress ability names throw this error in Angie:
	// Invalid 'tools[0].function.name': string does not match pattern. Expected a string that matches the pattern '^[a-zA-Z0-9_-]+$'.
	public function ability_name_to_angie_id($ability_name) {

		// Remove namespace prefix if present
		if (strpos($ability_name, WS_FORM_ABILITY_API_NAMESPACE) === 0) {

			$ability_name = substr($ability_name, strlen(WS_FORM_ABILITY_API_NAMESPACE));
		}

		// Remove any characters not matching the regex
		$ability_name = preg_replace('/[^a-zA-Z0-9_-]/', '', $ability_name);

		return $ability_name;
	}
}

new Angie_WS_Form();
