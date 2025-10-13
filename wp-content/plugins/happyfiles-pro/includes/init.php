<?php
namespace HappyFiles;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Init {
	public $actions;
	public $ajax;
	public $data;
	public $import;
	public $query;
	public $settings;
	public $setup;
	public $shortcode;
	public $user;

  public static $instance = null;

  public function __construct() {
		require_once HAPPYFILES_PATH . 'includes/helpers.php';
		require_once HAPPYFILES_PATH . 'includes/import.php';
		require_once HAPPYFILES_PATH . 'includes/actions.php';
		require_once HAPPYFILES_PATH . 'includes/ajax.php';
		require_once HAPPYFILES_PATH . 'includes/user.php';
		
		require_once HAPPYFILES_PATH . 'includes/setup.php';
		require_once HAPPYFILES_PATH . 'includes/data.php';

		require_once HAPPYFILES_PATH . 'includes/query.php';
		require_once HAPPYFILES_PATH . 'includes/settings.php';
		require_once HAPPYFILES_PATH . 'includes/shortcode.php';

		$this->actions = new Actions();
		$this->ajax = new Ajax();
		$this->data = new Data();
		$this->import = new Import();
		$this->query = new Query();
		$this->settings = new Settings();
		$this->setup = new Setup();
		$this->shortcode = new Shortcode();
		$this->user = new User();
  }

  public static function run() {
    if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Init ) ) {
      self::$instance = new self();
    }

    return self::$instance;
  }
}

Init::run();
