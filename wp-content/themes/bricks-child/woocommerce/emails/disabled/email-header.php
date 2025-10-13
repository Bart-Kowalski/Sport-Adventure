<?php
/**
 * Email Header
 */

if (!defined('ABSPATH')) {
    exit;
}

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?php bloginfo('charset'); ?>" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title><?php echo get_bloginfo('name', 'display'); ?></title>
</head>
<body <?php echo is_rtl() ? 'rightmargin' : 'leftmargin'; ?>="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">
    <div id="wrapper" dir="<?php echo is_rtl() ? 'rtl' : 'ltr'; ?>">
        <div id="template_container">
            <div id="template_header">
                <div id="header_wrapper">
                    <h1><?php echo $email_heading; ?></h1>
                </div>
            </div>
            <div id="template_body">
                <div id="body_content">
                    <div id="body_content_inner"> 