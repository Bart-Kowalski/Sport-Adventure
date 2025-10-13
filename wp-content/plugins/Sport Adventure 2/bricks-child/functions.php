<?php
/**
 * Theme functions
 */

add_action( 'wp_enqueue_scripts', function() {
    // Enqueue style.css of child theme
    wp_enqueue_style( 'bricks-child-theme', get_stylesheet_directory_uri() . '/style.css', [ 'bricks-frontend' ], filemtime( get_stylesheet_directory() . '/style.css' ) );
}, 20 );