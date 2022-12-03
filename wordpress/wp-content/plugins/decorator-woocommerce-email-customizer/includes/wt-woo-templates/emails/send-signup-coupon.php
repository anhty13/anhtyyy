<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<?php do_action('woocommerce_email_header', $email_heading, $email); ?>
<?php do_action( 'wt_decorator_email_body_content', $coupon, $sent_to_admin, $plain_text, $email ); ?>

<p><?php $coupon_data  = Wt_Smart_Coupon_Public::get_coupon_meta_data($coupon); ?></p> 
<p><?php echo Wt_Smart_Coupon_Public::get_coupon_html($coupon, $coupon_data, 'email_coupon'); ?></p>

<?php do_action( 'woocommerce_email_footer', $email ); ?>