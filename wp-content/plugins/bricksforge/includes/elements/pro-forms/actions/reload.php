<?php

namespace Bricksforge\ProForms\Actions;

class Reload
{
    public $name = "reload";
    /**
     * User login
     *
     * @since 1.0
     */
    public function run($form)
    {
        $form->set_result(
            [
                'action'          => $this->name,
                'type'            => 'reload'
            ]
        );
    }
}
