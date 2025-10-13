<?php

namespace Bricksforge;

if (!defined('ABSPATH')) {
    exit;
}

class DynamicData
{

    public function __construct()
    {
        $this->init();
    }

    public function init()
    {
        if ($this->activated() === true) {
            $this->handle();
        }
    }

    public function handle()
    {
        add_action('init', [$this, 'init_providers'], 10);
        add_filter('bricks/dynamic_data/register_providers', [$this, 'register_providers'], 10, 1);
    }

    public function register_providers($providers)
    {
        if (!class_exists('\Bricks\Integrations\Dynamic_Data\Providers\Base')) {
            return;
        }

        $providers[] = 'bricksforge';
        return $providers;
    }

    public function init_providers()
    {
        if (!class_exists('\Bricks\Integrations\Dynamic_Data\Providers\Base')) {
            return;
        }

        require_once BRICKSFORGE_INCLUDES . '/dynamic-data/providers/Provider_Bricksforge.php';
        new \Bricks\Integrations\Dynamic_Data\Providers\Provider_Bricksforge();
    }

    public function activated()
    {
        $options = get_option('brf_activated_tools') ? get_option('brf_activated_tools') : false;

        if ($options && in_array(12, $options)) {
            return true;
        }

        return false;
    }
}
