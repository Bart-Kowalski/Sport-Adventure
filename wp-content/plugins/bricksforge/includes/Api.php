<?php

namespace Bricksforge;

if (!defined('ABSPATH')) {
    exit;
}

use WP_REST_Controller;

/**
 * REST_API Handler
 */
class Api extends WP_REST_Controller
{

    /**
     * [__construct description]
     */
    public function __construct()
    {
        $this->includes();

        add_action('rest_api_init', [$this, 'register_routes']);
    }

    /**
     * Include the controller classes
     *
     * @return void
     */
    private function includes()
    {
        if (!class_exists(__NAMESPACE__ . '\Api\Bricksforge')) {
            require_once __DIR__ . '/api/Bricksforge.php';
        }
        if (!class_exists(__NAMESPACE__ . '\Api\Helper')) {
            require_once __DIR__ . '/api/Helper.php';
        }
        if (!class_exists(__NAMESPACE__ . '\Api\Utils')) {
            require_once __DIR__ . '/api/Utils.php';
        }
    }

    /**
     * Register the API routes
     *
     * @return void
     */
    public function register_routes()
    {
        (new Api\Bricksforge())->register_routes();
    }
}
