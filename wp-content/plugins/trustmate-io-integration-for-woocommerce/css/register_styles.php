<?php

add_action('init', 'register_styles');

function register_styles() {
    wp_register_style( 'config_form_style', plugins_url('/css/config_form.css', __DIR__));
    wp_register_style( 'widgets_style', plugins_url('/css/widgets.css', __DIR__));
    wp_register_style( 'dialog_style', plugins_url('/components/dialogs/dialog.css', __DIR__));
    wp_register_style( 'switch_style', plugins_url('/components/switch/switch.css', __DIR__));
}

?>