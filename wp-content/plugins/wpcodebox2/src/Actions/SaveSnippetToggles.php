<?php


namespace Wpcb2\Actions;


use Wpcb2\FunctionalityPlugin\Manager;
use Wpcb2\FunctionalityPlugin\PluginsFolderNotWritableException;

class SaveSnippetToggles
{
    public function execute()
    {
        $data = file_get_contents("php://input");
        $data = json_decode($data, true);

        if(isset($data['toggles'])) {
            add_option('wpcb_toggles');
            update_option('wpcb_toggles', $data['toggles'], false);
        }


        die;
    }
}
