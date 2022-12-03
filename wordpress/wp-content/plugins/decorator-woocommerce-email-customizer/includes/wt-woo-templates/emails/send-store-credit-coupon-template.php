<?php


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<?php //do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

    <?php
    
    // translators: placeholder is the name of the site
    $credit_amount = 0;
    $coupon_html = array();
    $currency_symbol = get_woocommerce_currency_symbol();


    $coupons = isset($credit_email_args['coupon_id']) && !empty($credit_email_args['coupon_id']) ? $credit_email_args['coupon_id'] :'';
    $coupons = maybe_unserialize( $coupons  );
    $from = isset( $credit_email_args['from_name'] )? $credit_email_args['from_name'] : '';
    if( '' == $from ) {
        $from = get_bloginfo('name');
    }
    $template = isset( $credit_email_args['template'] ) ?  $credit_email_args['template'] : 'general' ;

    $store_credit_templates = Wt_Smart_Coupon_Customisable_Gift_Card::get_template_image($template);
    
    /* custom caption */
    $caption=(isset($credit_email_args['caption']) ?  $credit_email_args['caption'] : Wt_Smart_Coupon_Store_Credit::get_gift_card_caption($template));

    $coupon_message = (isset($credit_email_args['message']) ?  $credit_email_args['message'] : Wt_Smart_Coupon_Store_Credit::get_gift_card_message($template));

    $top_background = $store_credit_templates['top_bg_color'];
    $bottom_background = $store_credit_templates['bottom_bg_color'];

    if( is_array( $coupons )) {
        $coupons = $coupons[0];
    }
    $coupon_obj = new WC_Coupon( $coupons );
    $coupon_title = $coupon_obj->get_code();
    $credit_amount = $coupon_obj->get_amount();

    if( !$coupon_title ) {
        $coupon_title = 'XXX-XXX-XXX';
    }
    ?>
    <div class="wt_store_credit_email_wrapper">
        <div class="store_credit_preview" >
            <div class="wt_gift_coupon_preview_caption <?php echo $template; ?>" style="background-color:<?php  echo $top_background ;?>">
                <?php echo esc_html($caption); ?>
            </div>
            <div class="wt_gift_coupon_preview_image">
                <?php echo '<img src="'.$store_credit_templates['image_url'].'" alt="'.$template.'" />'; ?>
            </div>
            <div class="wt_coupon-code-block">
                <div class="coupon-code">
                    <?php echo $coupon_title; ?>
                </div>
                <div class="coupon_price"> 
                    <?php
                    
                    $currentcy_positon = get_option('woocommerce_currency_pos');
                    if( $currentcy_positon  == 'left' ) {
                        ?>
                        <?php echo $currency_symbol; ?><span><?php echo $credit_amount; ?></span> 
                        <?php 
                    } else {
                        ?>
                            <span><?php echo $credit_amount; ?></span> <?php echo $currency_symbol; ?> 
                        <?php  
                    }
                    ?>
                </div>
            </div>

            <div class="coupon-message-block <?php echo $template; ?>"  style="background-color:<?php  echo $bottom_background ;?>">
                <div class="coupon-message"><?php echo $coupon_message; ?></div>
                <?php if($from) { ?>
                    <div class="coupon-from"><?php _e('FROM:','wt-smart-coupons-for-woocommerce-pro'); ?> <span><?php echo $from; ?></span> </div>
                <?php } ?>
            </div>
        </div>
    </div>


    
<?php //do_action( 'woocommerce_email_footer', $email ); ?>
