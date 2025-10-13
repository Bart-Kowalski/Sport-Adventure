<?php


namespace Wpcb2\Actions;


class GetSettings
{
    public function execute()
    {

        $api_key = get_option('wpcb_settings_api_key', '');
        echo json_encode([
            'apiKey' => $api_key,
            'showInTools' => get_option('wpcb_show_in_tools', false),
            'darkMode' => get_option('wpcb_dark_mode', true) ? true : false,
            ]);
        die;
    }
}
