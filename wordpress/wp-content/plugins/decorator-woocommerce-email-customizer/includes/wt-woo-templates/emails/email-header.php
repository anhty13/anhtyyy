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
 * @version 4.0.0
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
$wt_custom_style = RP_Decorator_Customizer::$wt_template_type;
$wt_template_object = RP_Decorator_Customizer::$wt_template_object;

if (empty($wt_custom_style)) {
    $wt_custom_style = RP_Decorator_Customizer::wt_get_current_template();
}
$key = 'rp_decorator_' . $wt_custom_style . '_subtitle';

$email_subtitle = get_option('rp_decorator_' . $wt_custom_style . '_subtitle');
$image_link_btn = get_option('wt_decorator_' . $wt_custom_style . '_image_link_btn_switch');
if (!empty($email_subtitle)) {
    $email_subtitle = RP_Decorator_Customizer::wt_subtitle_shortcode_replace($email_subtitle, $wt_template_object);
}
$subtitle_placement = RP_Decorator_Customizer::opt('subtitle_placement');

$header_placement = RP_Decorator_Customizer::opt( 'header_image_placement' );
if ( empty( $header_placement ) ) {
	$header_placement = 'outside';
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=<?php bloginfo('charset'); ?>" />
        <title><?php echo get_bloginfo('name', 'display'); ?></title>
    </head>
    <body <?php echo is_rtl() ? 'rightmargin' : 'leftmargin'; ?>="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">
        <div id="wrapper" dir="<?php echo is_rtl() ? 'rtl' : 'ltr'; ?>">
            <?php if ( 'outside' == $header_placement ) { ?>
            <table id="wt_wrapper_img_table" border="0" cellpadding="0" cellspacing="0" style="margin-left:auto; margin-right: auto;">
                <tr>
                    <td align="center" valign="top">
                        <div id="template_header_image">
                            <?php
                            if ($img = get_option('woocommerce_email_header_image')) {
                                echo '<p style="margin-top:0;margin-bottom: 0;">';
                                if (isset($image_link_btn) && $image_link_btn) {
                                    echo '<a href="' . esc_url(get_home_url()) . '" target="_blank" style="display:inline-block;width: 300px;text-decoration: none;">';
                                }
                                echo '<img src="' . esc_url($img) . '" alt="' . get_bloginfo('name', 'display') . '" />';
                                if (isset($image_link_btn) && $image_link_btn) {
                                    echo '</a>';
                                }
                                echo '</p>';
                            }
                            ?>
                        </div>
                    </td>
                </tr>
            </table>
            <?php } ?>
            <table id="wt_wrapper_table" border="0" cellpadding="0" cellspacing="0" height="100%" style="border: 0px solid;margin-left: auto;margin-right: auto;border-color: #dedede">
                <?php if ( 'inside' == $header_placement ) { ?>  
                <tr>
                    <td align="center" valign="top">
                        <div id="template_header_image">
                            <?php
                            if ($img = get_option('woocommerce_email_header_image')) {
                                echo '<p style="margin-top:0;margin-bottom: 0;">';
                                if (isset($image_link_btn) && $image_link_btn) {
                                    echo '<a href="' . esc_url(get_home_url()) . '" target="_blank" style="display:inline-block;width: 300px;text-decoration: none;">';
                                }
                                echo '<img src="' . esc_url($img) . '" alt="' . get_bloginfo('name', 'display') . '" />';
                                if (isset($image_link_btn) && $image_link_btn) {
                                    echo '</a>';
                                }
                                echo '</p>';
                            }
                            ?>
                        </div>
                    </td>
                </tr>
                 <?php } ?>
                <tr>
                    <td align="center" valign="top">
                        <table border="0" cellpadding="0" cellspacing="0" width="600" id="template_container" style="border: none;box-shadow: none !important">
                            <tr>
                                <td align="center" valign="top">
                                    <div id="wt_header_wrapper">
                                        <!-- Header -->
                                        <table border="0" cellpadding="0" cellspacing="0" width="100%" id="template_header">
                                            <tr>
                                                <td id="header_wrapper">
                                                    <?php if ('above' === $subtitle_placement && !empty($email_subtitle)) { ?>
                                                        <div class="subtitle" style="padding-bottom: 10px;margin-left: 0px;padding-right: 0px"><?php echo wp_kses_post($email_subtitle); ?></div>
                                                    <?php } ?>
                                                    <h1><?php echo $email_heading; ?></h1>
                                                    <?php if ('below' === $subtitle_placement && !empty($email_subtitle)) { ?>
                                                        <div class="subtitle" style="padding-top: 10px;padding-left: 0px;padding-right: 0px"><?php echo wp_kses_post($email_subtitle); ?></div>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                        </table>
                                    <!-- End Header -->
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td align="center" valign="top">
                                    <!-- Body -->
                                    <table border="0" cellpadding="0" cellspacing="0" width="600" id="template_body">
                                        <tr>
                                            <td valign="top" id="body_content" >
                                                <!-- Content -->
                                                <table border="0" cellpadding="20" cellspacing="0" width="100%">
                                                    <tr>
                                                        <td valign="top">
                                                            <div id="body_content_inner">
