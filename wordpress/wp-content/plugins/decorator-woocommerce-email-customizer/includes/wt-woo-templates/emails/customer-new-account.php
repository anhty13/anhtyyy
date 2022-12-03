<?php
/**
 * Customer new account email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-new-account.php.
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
 * @version     3.7.0
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
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

    }
</style>
    <?php

do_action( 'woocommerce_email_header', $email_heading, $email );
$wt_custom_style = RP_Decorator_Customizer::$wt_template_type;

if (empty($wt_custom_style)) {
    $wt_custom_style = RP_Decorator_Customizer::wt_get_current_template();
}
$button_check    = get_option( 'rp_decorator_customer_new_account_btn_switch' );//RP_Decorator_Customizer::opt( 'customer_new_account_btn_switch' );
$account_section = true;//RP_Decorator_Customizer::opt( 'customer_new_account_account_section' );
if(isset($_POST['customized']) && !empty($_POST['customized'])){   
    $data = json_decode(wp_unslash($_POST['customized']), true);
    if(array_key_exists('rp_decorator_customer_new_account_btn_switch' , $data)){
        if($button_check != $data['rp_decorator_customer_new_account_btn_switch'] ){            
            $button_check = $data['rp_decorator_customer_new_account_btn_switch'];
        }
    }
}

do_action( 'wt_decorator_email_body_content_text', $email ); ?>

<?php if ( 'yes' === get_option( 'woocommerce_registration_generate_password' ) && $password_generated ) : ?>

	<?php if ($set_password_url) { /** $set_password_url was introduced in WooCommerce 6.0 */ ?>
		<p><a href="<?php echo esc_attr( $set_password_url ); ?>"><?php printf( esc_html__( 'Click here to set your new password.', 'decorator-woocommerce-email-customizer' ) ); ?></a></p>
	<?php } else { ?>
		<p><?php printf( __( 'Your password has been automatically generated: %s', 'decorator-woocommerce-email-customizer' ), '<strong>' . esc_html( $user_pass ) . '</strong>' ); ?></p>
	<?php } ?>

<?php
endif;
if ( true == $account_section ) {
	if ( true == $button_check ) {
		echo '<p>' . esc_html__( 'You can access your account area to view your orders and change your password.', 'decorator-woocommerce-email-customizer' ) . '</p>';
		echo '<p class="btn-container"><a href="' . esc_url( wc_get_page_permalink( 'myaccount' ) ) . '" class="wt_template_button">' . esc_html__( 'View Account', 'decorator-woocommerce-email-customizer' ) . '</a></p>';
	} else {
	?>
	<p><?php printf( __( 'You can access your account area to view your orders and change your password here: %s.', 'decorator-woocommerce-email-customizer' ), make_clickable( esc_url( wc_get_page_permalink( 'myaccount' ) ) ) ); ?></p>
	<?php
	}
}
/**
 * Show user-defined additonal content - this is set in each email's settings.
 */
if ( isset( $additional_content ) && ! empty( $additional_content ) ) {
	echo wp_kses_post( wpautop( wptexturize( $additional_content ) ) );
}

do_action( 'woocommerce_email_footer', $email );
