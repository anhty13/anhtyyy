<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$coupon_data  = Wt_Smart_Coupon_Public::get_coupon_meta_data($coupon);
?>

<?php do_action( 'woocommerce_email_header', $email_heading, $email); ?>

<?php do_action( 'wt_decorator_email_body_content', $coupon, $sent_to_admin, $plain_text, $email ); ?>

<div style="height:150px;">
	<?php 
	$coupon_data  = Wt_Smart_Coupon_Public::get_coupon_meta_data($coupon);
	echo Wt_Smart_Coupon_Public::get_coupon_html($coupon, $coupon_data, 'email_coupon'); 
	?>		
</div>

<p>
	<?php
		$style='background:#0085ba; border:none; color:#fff; text-decoration:none; padding:10px; text-align:center;';
  		$style=apply_filters('wt_sc_alter_abandonment_email_button_style', $style, $coupon);
  	?>
	<a style="<?php echo esc_attr($style);?>" href="<?php echo esc_attr(wc_get_cart_url());?>"><?php  _e('Go to your cart', 'wt-smart-coupons-for-woocommerce-pro'); ?></a>
</p>

<?php do_action('woocommerce_email_footer', $email); ?>