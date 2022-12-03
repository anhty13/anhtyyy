<?php
/**
 * Customer payment retry email
 *
 * @author  Prospress
 * @package WooCommerce_Subscriptions/Templates/Emails
 * @version 2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

do_action( 'woocommerce_email_header', $email_heading, $email );

$override_content = RP_Decorator_Customizer::opt( 'customer_payment_retry_override' );

if ( true == $override_content ) {

	do_action( 'wt_decorator_email_body_content', $order, $sent_to_admin, $plain_text, $email );


} else {
	$button_check = RP_Decorator_Customizer::opt( 'customer_payment_retry_btn_switch' );
	?>
	<p>
		<?php
		// translators: %1$s: name of the blog, %2$s: lowercase human time diff in the form returned by wcs_get_human_time_diff(), e.g. 'in 12 hours'
		echo wp_kses( sprintf( _x( 'The automatic payment to renew your subscription with %1$s has failed. We will retry the payment %2$s.', 'In customer renewal invoice email', 'decorator-woocommerce-email-customizer' ), esc_html( get_bloginfo( 'name' ) ), wcs_get_human_time_diff( $retry->get_time() ) ), array( 'a' => array( 'href' => true ) ) );
		?>
	</p>
	<?php
		if ( true == $button_check ) {
			echo '<p>' . esc_html__( 'To reactivate the subscription now, you can also login and pay for the renewal from your account page:', 'decorator-woocommerce-email-customizer' ) . '</p>';
			echo '<p class="btn-container"><a href="' . esc_url( $order->get_checkout_payment_url() ) . '" class="btn">' . esc_html__( 'Pay Now &raquo;', 'decorator-woocommerce-email-customizer' ) . '</a></p>';
		} else {
	?>
		<p>
			<?php
			// translators: %1$s %2$s: link markup to checkout payment url, note: no full stop due to url at the end
			echo wp_kses( sprintf( _x( 'To reactivate the subscription now, you can also login and pay for the renewal from your account page: %1$sPay Now &raquo;%2$s', 'In customer renewal invoice email', 'decorator-woocommerce-email-customizer' ), '<a href="' . esc_url( $order->get_checkout_payment_url() ) . '">', '</a>' ), array( 'a' => array( 'href' => true ) ) );
			?>
		</p>
		<?php
	}
}

do_action( 'woocommerce_subscriptions_email_order_details', $order, $sent_to_admin, $plain_text, $email );

/**
 * Show user-defined additonal content - this is set in each email's settings.
 */
if ( isset( $additional_content ) && ! empty( $additional_content ) ) {
	echo wp_kses_post( wpautop( wptexturize( $additional_content ) ) );
}

do_action( 'woocommerce_email_footer', $email );
