<?php
/**
 * Email Header
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-header.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates\Emails
 * @version 7.4.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Get styles but remove any CSS already wrapped in style tags
$css = apply_filters('woocommerce_email_styles', '');
$css = preg_replace('/<style[^>]*>.*?<\/style>/s', '', $css);
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=<?php bloginfo('charset'); ?>" />
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <meta name="color-scheme" content="light">
        <meta name="supported-color-schemes" content="light">
        <?php if (!empty($css)) : ?>
        <style type="text/css">
            <?php echo strip_tags($css); ?>
        </style>
        <?php endif; ?>
    </head>
    <body <?php echo is_rtl() ? 'rightmargin' : 'leftmargin'; ?>="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">
        <div id="wrapper" dir="<?php echo is_rtl() ? 'rtl' : 'ltr'; ?>">
            <div id="template_container">
                <div id="template_header">
                    <div id="header_wrapper">
                        <h1><?php echo esc_html($email_heading); ?></h1>
                    </div>
                </div>
                <div id="template_body">
                    <div id="body_content">
                        <div id="body_content_inner"> 