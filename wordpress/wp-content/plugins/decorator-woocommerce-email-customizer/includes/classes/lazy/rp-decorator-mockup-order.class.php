<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Mockup WooCommerce Order class
 *
 * @class RP_Decorator_Mockup_Order
 * @package Decorator
 * @author WebToffee
 */
if (!class_exists('RP_Decorator_Mockup_Order')) {

class RP_Decorator_Mockup_Order
{

    /**
     * Constructor class
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        $this->id                   = 1;
        $this->billing_first_name   = 'Sherlock';
        $this->billing_last_name    = 'Holmes';
        $this->billing_company      = 'Detectives Ltd.';
        $this->billing_address_1    = '221B Baker Street';
        $this->billing_address_2    = '';
        $this->billing_city         = 'London';
        $this->billing_state        = '';
        $this->billing_postcode     = 'NW1 6XE';
        $this->billing_country      = 'GB';
        $this->billing_email        = 'sherlock@holmes.co.uk';
        $this->billing_phone        = '02079304832';
        $this->order_date           = date('Y-m-d H:i:s');
        $this->customer_note        = '';
        $this->order_total          = 29.90;
        $this->status               = 'processing';
    }

    public function get_order_number()
    {
        return apply_filters('woocommerce_order_number', $this->id, $this);
    }

    public function get_formatted_billing_full_name()
    {
        return sprintf(_x('%1$s %2$s', 'full name', 'woocommerce'), $this->billing_first_name, $this->billing_last_name);
    }

    public function get_status()
    {
        return $this->status;
    }

    public function has_status()
    {
        return false;
    }

    public function get_checkout_payment_url()
    {
        return RP_Decorator_WC::get_email_settings_page_url();
    }

    public function is_download_permitted()
    {
        if ($this->status === 'completed') {
            return true;
        }
        else if ($this->status === 'processing' && get_option('woocommerce_downloads_grant_access_after_payment') === 'yes') {

        }
        else {
            return false;
        }
    }

    public function needs_shipping_address()
    {
        return true;
    }

    public function get_formatted_billing_address()
    {
        return WC()->countries->get_formatted_address(array(
            'first_name'    => $this->billing_first_name,
            'last_name'     => $this->billing_last_name,
            'company'       => $this->billing_company,
            'address_1'     => $this->billing_address_1,
            'address_2'     => $this->billing_address_2,
            'city'          => $this->billing_city,
            'state'         => $this->billing_state,
            'postcode'      => $this->billing_postcode,
            'country'       => $this->billing_country
        ));
    }

    public function get_formatted_shipping_address()
    {
        return WC()->countries->get_formatted_address(array(
            'first_name'    => $this->billing_first_name,
            'last_name'     => $this->billing_last_name,
            'company'       => $this->billing_company,
            'address_1'     => $this->billing_address_1,
            'address_2'     => $this->billing_address_2,
            'city'          => $this->billing_city,
            'state'         => $this->billing_state,
            'postcode'      => $this->billing_postcode,
            'country'       => $this->billing_country
        ));
    }

    public function email_order_items_table($show_download_links = false, $show_sku = false, $show_purchase_note = false, $show_image = false, $image_size = array(32, 32), $plain_text = false)
    {
        ob_start();

        wc_get_template('emails/email-order-items.php', array(
            'order'                 => $this,
            'items'                 => $this->get_items(),
            'show_download_links'   => $show_download_links,
            'show_sku'              => $show_sku,
            'show_purchase_note'    => $show_purchase_note,
            'show_image'            => $show_image,
            'image_size'            => $image_size,
            'plain_text'            => false,
        ));

        return ob_get_clean();
    }

    public function get_items()
    {
        return array(
            '2' => array(
                'id'                => 2,
                'name'              => 'A Study in Scarlet',
                'type'              => 'line_item',
                'qty'               => 1,
                'item_meta'         => '',
                'item_meta_array'   => array(),
                'line_subtotal'     => 9.95,
                'line_subtotal_tax' => 0,
            ),
            '3' => array(
                'id'                => 3,
                'name'              => 'The Hound of the Baskervilles',
                'type'              => 'line_item',
                'qty'               => 1,
                'item_meta'         => '',
                'item_meta_array'   => array(),
                'line_subtotal'     => 14.95,
                'line_subtotal_tax' => 0,
            ),
        );
    }

    public function get_formatted_line_subtotal($item)
    {
        return wc_price($this->get_line_subtotal($item), array('currency' => get_woocommerce_currency()));
    }

    public function get_item_downloads($item)
    {
        return array();
    }

    public function display_item_downloads($item) {
    }

    public function get_order_item_totals()
    {
        $total_rows = array();

        $total_rows['cart_subtotal'] = array(
            'label' => __('Subtotal:', 'woocommerce'),
            'value' => wc_price(24.90, array('currency' => get_woocommerce_currency())),
        );

        $total_rows['shipping'] = array(
            'label' => __('Shipping:', 'woocommerce'),
            'value' => wc_price(5.00, array('currency' => get_woocommerce_currency())) . sprintf(__('&nbsp;<small>%svia %s</small>', 'woocommerce' ), '', 'DHL'),
        );

        $total_rows['payment_method'] = array(
            'label' => __('Payment Method:', 'woocommerce'),
            'value' => 'PayPal',
        );

        $total_rows['order_total'] = array(
            'label' => __('Total:', 'woocommerce'),
            'value' => wc_price($this->get_total(), array('currency' => get_woocommerce_currency())),
        );

        return $total_rows;
    }

    public function get_product_from_item($item)
    {
        // Include mockup product class
        if (!class_exists('RP_Decorator_Mockup_Product')) {
            include RP_DECORATOR_PLUGIN_PATH . 'includes/classes/lazy/rp-decorator-mockup-product.class.php';
        }

        // Instantiate and return product object
        return new RP_Decorator_Mockup_Product(($item['id'] + 2));
    }

    public function get_line_subtotal($item, $inc_tax = false, $round = true)
    {
        if ($inc_tax) {
            $price = $item['line_subtotal'] + $item['line_subtotal_tax'];
        }
        else {
            $price = $item['line_subtotal'];
        }

        return $round ? round($price, wc_get_price_decimals()) : $price;
    }

    public function get_order_currency()
    {
        return get_woocommerce_currency();
    }

    public function get_total()
    {
        return (double) $this->order_total;
    }

    public function get_view_order_url()
    {
        return wc_get_page_permalink('myaccount');
    }


}
}
