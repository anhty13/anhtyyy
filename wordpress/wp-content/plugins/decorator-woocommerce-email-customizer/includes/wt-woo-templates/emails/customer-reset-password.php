<?php
/**
 * Customer Reset Password email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-reset-password.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates/Emails
 * @version 4.0.0
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>
<style>  

    .wt_template_button{
        text-decoration: none;
         border-style: solid;
         font-weight: 600;
         background: #96588a;
         border-color: #dedede;
         color: #ffffff;
         font-size: 16px;
         padding-top: 10px;
         padding-bottom: 10px;
         padding-left: 8px;
         padding-right: 8px;
         border-width: 1px;
         border-radius: 4px;

    }</style>
<?php
$wt_custom_style = RP_Decorator_Customizer::$wt_template_type;

if (empty($wt_custom_style)) {
    $wt_custom_style = RP_Decorator_Customizer::wt_get_current_template();
}
$button_check = get_option('rp_decorator_customer_reset_password_btn_switch');
if (isset($_POST['customized']) && !empty($_POST['customized'])) {
    $data = json_decode(wp_unslash($_POST['customized']), true);
    if (array_key_exists('rp_decorator_customer_reset_password_btn_switch', $data)) {
        if ($button_check != $data['rp_decorator_customer_reset_password_btn_switch']) {
            $button_check = $data['rp_decorator_customer_reset_password_btn_switch'];
        }
    }
}

do_action('woocommerce_email_header', $email_heading, $email);

do_action('wt_decorator_email_body_content_text', $email);

if (true == $button_check) {
    echo '<p class="btn-container"><a href="' . esc_url(add_query_arg(array('key' => $reset_key, 'id' => $user_id), wc_get_endpoint_url('lost-password', '', wc_get_page_permalink('myaccount')))) . '" class="wt_template_button">' . esc_html__('Reset Password', 'decorator-woocommerce-email-customizer') . '</a></p>';
} else {
    ?>
    <p>
        <a class="link" href="<?php echo esc_url(add_query_arg(array('key' => $reset_key, 'id' => $user_id), wc_get_endpoint_url('lost-password', '', wc_get_page_permalink('myaccount')))); ?>">
    <?php esc_html_e('Click here to reset your password', 'decorator-woocommerce-email-customizer'); ?></a>
    </p>
            <?php
        }
        ?>
<p></p>
<?php
/**
 * Show user-defined additonal content - this is set in each email's settings.
 */
if ($additional_content) {
    echo wp_kses_post(wpautop(wptexturize($additional_content)));
}

do_action('woocommerce_email_footer', $email);
?>
