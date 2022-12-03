<?php
/**
 * Email Addresses
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-addresses.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates\Emails
 * @version 5.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$wt_custom_style = RP_Decorator_Customizer::$wt_template_type;
$wt_template_object = RP_Decorator_Customizer::$wt_template_object;

if (empty($wt_custom_style)) {
    $wt_custom_style = RP_Decorator_Customizer::wt_get_current_template();
}
$key = 'rp_decorator_' . $wt_custom_style . '_billing_address_subtitle';

$billing_address_subtitle = get_option('rp_decorator_' . $wt_custom_style . '_billing_address_subtitle');
if (!empty($billing_address_subtitle)) {
    $billing_address_subtitle = RP_Decorator_Customizer::wt_subtitle_shortcode_replace($billing_address_subtitle, $wt_template_object);
}else{
    $billing_address_subtitle = __( 'Billing address', 'decorator-woocommerce-email-customizer' );
}
$shipping_address_subtitle = get_option('rp_decorator_' . $wt_custom_style . '_shipping_address_subtitle');
if (!empty($shipping_address_subtitle)) {
    $shipping_address_subtitle = RP_Decorator_Customizer::wt_subtitle_shortcode_replace($shipping_address_subtitle, $wt_template_object);
}else{
    $shipping_address_subtitle = __( 'Shipping address', 'decorator-woocommerce-email-customizer' );
}
$text_align = is_rtl() ? 'right' : 'left';
$address    = $order->get_formatted_billing_address();
$shipping   = $order->get_formatted_shipping_address();

?><table id="addresses" cellspacing="0" cellpadding="0" style="width: 100%; vertical-align: top; margin-bottom: 40px; padding:0;" border="0">
	<tr>
            <td id="wt_addresses_wrapper" style="text-align:<?php echo esc_attr( $text_align ); ?>; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; border:0; padding:0;" valign="top">
                <div id="wt_billing_address_wrap">
                        <h2 id="wt_billing_address"><?php echo wp_kses_post($billing_address_subtitle); ?></h2>

			<address class="address">
				<?php echo wp_kses_post( $address ? $address : esc_html__( 'N/A', 'decorator-woocommerce-email-customizer' ) ); ?>
				<?php if ( $order->get_billing_phone() ) : ?>
					<br/><?php echo wc_make_phone_clickable( $order->get_billing_phone() ); ?>
				<?php endif; ?>
				<?php if ( $order->get_billing_email() ) :?>
					<br/><a href="mailto:<?php echo esc_attr( $order->get_billing_email() ); ?>"><?php echo esc_html( $order->get_billing_email() ); ?></a>
				<?php endif; ?>
			</address>
                </div>
		</td>
		<?php if ( ! wc_ship_to_billing_address_only() && $order->needs_shipping_address() && $shipping ) : ?>
                     <?php if ( defined('WC_VERSION') && (WC_VERSION >= 5.6) ) : ?>
			<td id="wt_shipping_addresses_wrapper" style="text-align:<?php echo esc_attr( $text_align ); ?>; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; padding:0;" valign="top">
                            <div id="wt_shipping_address_wrap">
                                <h2 id="wt_shipping_address"><?php echo wp_kses_post($shipping_address_subtitle); ?></h2>

				<address class="address">
					<?php echo wp_kses_post( $shipping ); ?>
					<?php if ( $order->get_shipping_phone() ) : ?>
						<br /><?php echo wc_make_phone_clickable( $order->get_shipping_phone() ); ?>
					<?php endif; ?>
				</address>
                            </div>   
			</td>
                        <?php endif; ?>
		<?php endif; ?>
	</tr>
</table>
