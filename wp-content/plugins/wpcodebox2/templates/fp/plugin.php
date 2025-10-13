<?php
/**
 * Plugin Name:     {PLUGIN_NAME}
 * Description:     {PLUGIN_DESCRIPTION}
 * Version:         {PLUGIN_VERSION}
 *
 */

if(!defined('ABSPATH')) {
    die;
}

include_once __DIR__ . '/WFPCore/Preconditions.php';
include_once __DIR__ . '/WFPCore/WordPressContext.php';

$preconditions = new \WFPCore\Preconditions();


if($preconditions->is_wpcb_request() || $preconditions->safe_mode()) {
	return;
}
$preconditions->output_autoreload();


// Snippets will go before this line, do not edit
