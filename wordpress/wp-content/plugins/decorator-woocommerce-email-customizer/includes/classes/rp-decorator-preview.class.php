<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Preview for WordPress Customizer
 *
 * @class RP_Decorator_Preview
 * @package Decorator
 * @author WebToffee
 */
if (!class_exists('RP_Decorator_Preview')) {

    class RP_Decorator_Preview {

        // WooCommerce email classes
        public static $email_classes = array(
            'WC_Email_New_Order' => 'processing',
            'WC_Email_Cancelled_Order' => 'cancelled',
            'WC_Email_Failed_Order' => 'failed',
            'WC_Email_Customer_On_Hold_Order' => 'on-hold',
            'WC_Email_Customer_Processing_Order' => 'processing',
            'WC_Email_Customer_Completed_Order' => 'completed',
            'WC_Email_Customer_Refunded_Order' => 'refunded',
            'WC_Email_Customer_Invoice' => 'processing',
            'WC_Email_Customer_Note' => 'processing',
            'WC_Email_Customer_Reset_Password' => null,
            'WC_Email_Customer_New_Account' => null,
        );
        // Singleton instance
        private static $instance = false;
        private static $email_types = null;
        public static $wt_product_title = null;
        public static $current_member = null;
        public static $wt_supported_email_class_names = null;
        public static $wt_supported_email_order_status = null;

        /**
         * Singleton control
         */
        public static function get_instance() {
            if (!self::$instance) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        /**
         * Class constructor
         *
         * @access public
         * @return void
         */
        public function __construct() {
            // Set up preview
            add_action('customize_controls_head', array($this, 'customize_controls_head'), 99999);
            add_filter('customize_save_response', array($this, 'wt_customize_save_response'), 9999, 2);
            add_filter('customize_changeset_save_data', array($this, 'wt_customize_changeset_save_data'), 9999, 2);
            add_action('init', array($this, 'wt_scheduled_action'));
            add_action('parse_request', array($this, 'set_up_preview'));
            self::$wt_supported_email_class_names = self::wt_supported_email_classes();
            self::$wt_supported_email_order_status = self::wt_supported_email_type_status();
            add_action('admin_menu', array($this, 'admin_menu'));
        }

        /**
         * Set up admin menu
         * @access public
         * @return void
         */
        public function admin_menu() {
            $page = add_submenu_page('woocommerce', __('Decorator', 'decorator-woocommerce-email-customizer'), __('Decorator', 'decorator-woocommerce-email-customizer'), apply_filters('woocommerce_decorator_role', 'manage_woocommerce'), 'decorator-woocommerce-email-customizer', array($this, 'output'));
        }

        /**
         * Admin Screen output
         */
        public function output() {
            wp_redirect(RP_Decorator_Customizer::get_customizer_url());
        }

        /**
         * Set up preview
         *
         * @access public
         * @return void
         */
        public function set_up_preview() {
            // Make sure this is own preview request
            if (!RP_Decorator::is_own_preview_request()) {
                return;
            }

            //make sure this user has the administrative capability
            if (!RP_Decorator::is_admin()) {
                return;
            }

            // Load main view
            include RP_DECORATOR_PLUGIN_PATH . 'includes/views/preview.php';

            // Do not load any further elements
            exit;
        }

        /**
         * Print preview email
         *
         * @access public
         * @return void
         */
        public static function print_preview_email($send_email = false, $email_addresses = null, $email_type = null, $order_id = null) {
            $content = self::get_preview_email($send_email, $email_addresses, $email_type, $order_id);

            if (false == $content) {
                echo __('An error occurred trying to load this email type. Make sure this email type is enabled or please try another type.', 'decorator-woocommerce-email-customizer');
            } elseif (!empty($content)) {
                // Print email content
                echo $content;
                // Print live preview scripts in footer
                add_action('wp_footer', array('RP_Decorator_Preview', 'print_live_preview_scripts'), 99);
            }
        }

        /**
         * Get WooCommerce order for preview
         *
         * @access public
         * @param string $order_status
         * @return object
         */
        public static function get_wc_order_for_preview($order_status = null, $order_id = null) {

            if (!empty($order_id) && 'mockup' != $order_id) {
                return wc_get_order($order_id);
            } else {

                if (RP_Decorator::wc_version_gte('2.7') && 'mockup' == $order_id) {

                    // Instantiate order object
                    $order = new WC_Order();

                    // Other order properties
                    $order->set_props(array(
                        'id' => 1,
                        'status' => ($order_status === null ? 'processing' : $order_status),
                        'billing_first_name' => 'Sherlock',
                        'billing_last_name' => 'Holmes',
                        'billing_company' => 'Detectives Ltd.',
                        'billing_address_1' => '221B Baker Street',
                        'billing_city' => 'London',
                        'billing_postcode' => 'NW1 6XE',
                        'billing_country' => 'GB',
                        'billing_email' => 'sherlock@holmes.co.uk',
                        'billing_phone' => '02079304832',
                        'date_created' => date('Y-m-d H:i:s'),
                        'total' => 24.90,
                    ));

                    // Item #1
                    $order_item = new WC_Order_Item_Product();
                    $order_item->set_props(array(
                        'name' => 'A Study in Scarlet',
                        'subtotal' => '9.95',
                    ));
                    $order->add_item($order_item);

                    // Item #2
                    $order_item = new WC_Order_Item_Product();
                    $order_item->set_props(array(
                        'name' => 'The Hound of the Baskervilles',
                        'subtotal' => '14.95',
                    ));
                    $order->add_item($order_item);

                    // Return mockup order
                    return $order;
                } else {

                    // Include mockup order class
                    if (!class_exists('RP_Decorator_Mockup_Order')) {
                        include RP_DECORATOR_PLUGIN_PATH . 'includes/classes/lazy/rp-decorator-mockup-order.class.php';
                    }

                    // Instantiate order object
                    $order = new RP_Decorator_Mockup_Order();

                    // Set order status
                    if ($order_status) {
                        $order->status = $order_status;
                    }

                    // Return mockup order
                    return $order;
                }
            }
        }

        /**
         * Print live preview scripts
         *
         * @access public
         * @return void
         */
        public static function print_live_preview_scripts() {
            // Open container
            $scripts = '<script type="text/javascript">jQuery(document).ready(function() {';

            // Font family mapping
            $scripts .= 'var font_family_mapping = ' . json_encode(RP_Decorator_Settings::$font_family_mapping) . ';';

            // Function to handle special cases
            $scripts .= "function prepare(value, key, selector, property) {
            if (key === 'border_radius' && selector === '#template_header') {
                value = value.replace('!important', '').trim();
                value = value + ' ' + value + ' 0 0 !important';
            }
            else if (key === 'email_padding' && selector === '#wrapper') {
                value = value + ' 0 ' + value + ' 0';
            }
            else if (key === 'footer_padding' && selector === '#template_footer #credit') {
                value = '0 ' + value + ' ' + value + ' ' + value;
            }
            else if (key === 'header_image_maxwidth' && selector === '#template_header_image') {
               var width = document.querySelector('#wt_wrapper_table').offsetWidth;
               value = value.replace('!important', '').trim();
               value = value.replace('px', '').trim();
               if(value < width){
               value = width+'px' ;
               }
            }
            else if (key === 'footer_show' || key === 'order_items_show' || key === 'header_show' || key === 'billing_address_show' || key === 'shipping_address_show') {
                if(value == 'true' || value == true || value == 1){
                    value = 'auto';
                }else{
                    if(selector == '#wt_header_wrapper'){
                        if(property == 'overflow'){
                          value = 'hidden;' ;
                        }else{
                         value = '0px !important;' ;
                        }
                    }
                    if(selector == '#wt_shipping_address_wrap'){
                        if(property == 'overflow'){
                          value = 'hidden;' ;
                        }else{
                         value = '0px !important;' ;
                        }
                    }
                     if(selector == '#wt_billing_address_wrap'){
                        if(property == 'overflow'){
                          value = 'hidden;' ;
                        }else{
                         value = '0px !important;' ;
                        }
                    }
                     if(selector == '#wt_order_items_table'){
                        if(property == 'overflow'){
                          value = 'hidden;' ;
                        }else{
                         value = '0px !important;' ;
                        }
                    }
                    if(selector == '#wt_wrapper_table #wt_template_footers'){
                        if(property == 'overflow'){
                          value = 'hidden;' ;
                        }else{
                         value = '0px !important;' ;
                        }
                    }
                }
            }
            else if (key === 'footer_content_text' && value !== '') {
                value = '<p>' + value + '</p>';
            }
            else if (key === 'border_radius' && selector === 'body #template_header') {
               value = value.replace('!important', '').trim();
               value = value.replace('px', '').trim();
               if(value > 5){
               value = parseInt(value) - 10;
               }
               value = value + 'px !important';
            }else if (key === 'shadow') {
                value = '0 ' + (value > 0 ? 1 : 0) + 'px ' + (value * 4) + 'px ' + value + 'px rgba(0,0,0,0.1) !important';
            }
            else if (key.match(/font_family$/)) {
                value = typeof font_family_mapping[value] !== 'undefined' ? font_family_mapping[value] : value;
            }
            else if (key === 'header_image' && value!='') {
                value = '<p style=\"margin-top:0;margin-bottom:0;\"><img src=\"' + value + '\" style=\"border: none; display: inline; vertical-align: top; font-weight: bold; height: auto; line-height: 100%; outline: none; text-decoration: none; width:300px; text-transform: capitalize;\" /></p>';
            }
             else if (key === 'social_links_align' && selector === '#template_footer #wt_social_footer') {
                value = value.replace('!important', '').trim();
                 if(value == 'center'){
                     value = '0 auto 0 auto !important';
                 }else if (value == 'right') {
                    value = '0 0 0 auto !important';
                } else {
                    value = '0 auto 0 0 !important';
                }

            }
           
            return value;
        }";

            // Get CSS suffixes
            $scripts .= 'var suffixes = ' . json_encode(RP_Decorator_Customizer::get_css_suffix()) . ';';

            // Iterate over settings
            foreach (RP_Decorator_Settings::get_settings() as $setting_key => $setting) {

                // No live method
                if (!isset($setting['live_method'])) {
                    continue;
                }

                // Iterate over selectors
                if (in_array($setting['live_method'], array('css', 'property')) && !empty($setting['selectors'])) {
                    foreach ($setting['selectors'] as $selector => $properties) {

                        // Iterate over properties
                        foreach ($properties as $property) {

                            // CSS value change
                            if (!isset($setting['live_method']) || $setting['live_method'] === 'css') {
                                $scripts .= "wp.customize('rp_decorator[$setting_key]', function(value) {
                                value.bind(function(newval) {
                                    newval = newval + (typeof suffixes['$setting_key'] !== 'undefined' ? suffixes['$setting_key'] : '');
                                    newval = prepare(newval, '$setting_key', '$selector', '$property');
                                    jQuery('$selector').css('$property', '').attr('style', function(i, s) { return (s||'') + '$property: ' + newval + ';' });
                                });
                            });";
                            }

                            // DOM object property
                            if ($setting['live_method'] === 'property') {
                                $scripts .= "wp.customize('rp_decorator[$setting_key]', function(value) {
                                value.bind(function(newval) {
                                    newval = newval + (typeof suffixes['$setting_key'] !== 'undefined' ? suffixes['$setting_key'] : '');
                                    newval = prepare(newval, '$setting_key', '$selector', '$property');
                                    jQuery('$selector').prop('$property', newval);
                                });
                            });";
                            }
                        }
                    }
                }

                // HTML Replace
                if ($setting['live_method'] === 'replace' && !empty($setting['selectors'])) {
                    foreach ($setting['selectors'] as $selector) {
                        $original = json_encode($setting['original']);
                        $scripts .= "wp.customize('rp_decorator[$setting_key]', function(value) {
                        value.bind(function(newval) {
                            newval = (newval !== '' ? newval : $original);
                            newval = prepare(newval, '$setting_key', '$selector', '$property');
                            jQuery('$selector').html(newval);
                        });
                    });";
                    }
                }
            }

            // Go through woo settings
            foreach (RP_Decorator_Settings::wt_get_custom_text_edit_settings() as $setting_key => $setting) {

                // No live method
                if (!isset($setting['live_method'])) {
                    continue;
                }

                // Iterate over selectors
                if (in_array($setting['live_method'], array('css', 'property')) && !empty($setting['selectors'])) {
                    foreach ($setting['selectors'] as $selector => $properties) {

                        // Iterate over properties
                        foreach ($properties as $property) {

                            // CSS value change.
                            if (!isset($setting['live_method']) || $setting['live_method'] === 'css') {
                                $scripts .= "wp.customize('$setting_key', function(value) {
                                                value.bind(function(newval) {
                                                newval = newval + (typeof suffixes['$setting_key'] !== 'undefined' ? suffixes['$setting_key'] : '');
                                                newval = prepare(newval, '$setting_key', '$selector', '$property');
                                                jQuery('$selector').css('$property', '').attr('style', function(i, s) { return (s||'') + '$property: ' + newval + ';' });
                                                });
                                                });";
                            }

                            // DOM object property.
                            if ('property' === $setting['live_method']) {
                                $scripts .= "wp.customize('$setting_key', function(value) {
                                                value.bind(function(newval) {
                                                newval = newval + (typeof suffixes['$setting_key'] !== 'undefined' ? suffixes['$setting_key'] : '');
                                                newval = prepare(newval, '$setting_key', '$selector', '$property');
                                                jQuery('$selector').prop('$property', newval);
                                                });
                                                });";
                            }
                        }
                    }
                }
                // HTML Replace.
                if ('replace' === $setting['live_method'] && !empty($setting['selectors'])) {
                    foreach ($setting['selectors'] as $selector) {
                        $original = ( isset($setting['input_attrs']) && isset($setting['input_attrs']['placeholder']) && !empty($setting['input_attrs']['placeholder']) ? json_encode($setting['input_attrs']['placeholder']) : '"Placeholder Text"' );
                        $scripts .= "wp.customize('$setting_key', function(value) {
                                value.bind(function(newval) {
                                newval = (newval !== '' ? newval : $original);

                                newval = prepare(newval, '$setting_key', '$selector', '');
                                jQuery('$selector').html(newval);
                                });
                                });";
                    }
                }
            }

            // Close container and return
            echo $scripts . '});</script>';
        }

        /**
         * Add an header section in template edit screen
         *
         * @access public
         * 
         * @return page
         */
        public function customize_controls_head() {
            $email_type = self::get_email_types();
            include RP_DECORATOR_PLUGIN_PATH . 'includes/views/wt_custom_header.php';
        }

        /**
         * Get Email Types
         *
         * @access public
         * @return array
         */
        public static function get_email_types() {

            if (is_null(self::$email_types)) {
                $types = array(
                    'new_order' => __('New order', 'decorator-woocommerce-email-customizer'),
                    'cancelled_order' => __('Cancelled order', 'decorator-woocommerce-email-customizer'),
                    'customer_processing_order' => __('Customer processing order', 'decorator-woocommerce-email-customizer'),
                    'customer_completed_order' => __('Customer completed order', 'decorator-woocommerce-email-customizer'),
                    'customer_refunded_order' => __('Customer refunded order', 'decorator-woocommerce-email-customizer'),
                    'customer_on_hold_order' => __('Customer on hold order', 'decorator-woocommerce-email-customizer'),
                    'customer_invoice' => __('Customer invoice', 'decorator-woocommerce-email-customizer'),
                    'failed_order' => __('Failed order', 'decorator-woocommerce-email-customizer'),
                    'customer_new_account' => __('Customer new account', 'decorator-woocommerce-email-customizer'),
                    'customer_note' => __('Customer note', 'decorator-woocommerce-email-customizer'),
                    'customer_reset_password' => __('Customer reset password', 'decorator-woocommerce-email-customizer'),
                );
                if ( defined('WT_SMARTCOUPON_INSTALLED_VERSION') && WT_SMARTCOUPON_INSTALLED_VERSION ==  'PREMIUM' ) {
                        $types = array_merge( $types, array(
                                'wt_smart_coupon_gift'                 => __( 'Smart coupon gift', 'decorator-woocommerce-email-customizer' ),
                                'wt_smart_coupon_abandonment_coupon_email' => __( 'Smart coupon abandonment coupon email', 'decorator-woocommerce-email-customizer' ),
                                'wt_smart_coupon_store_credit'  => __( 'Smart coupon store credit', 'decorator-woocommerce-email-customizer' ),
                                'wt_smart_coupon_signup_coupon_email'   => __( 'Smart coupon signup coupon email', 'decorator-woocommerce-email-customizer' ),
                                'wt_smart_coupon'          => __( 'Smart coupon', 'decorator-woocommerce-email-customizer' ),
                        ) );
                }
                if (class_exists('WC_Subscriptions')) {
                    $types = array_merge($types, array(
                        'new_renewal_order' => __('New renewal order', 'decorator-woocommerce-email-customizer'),
                        'customer_processing_renewal_order' => __('Customer processing renewal order', 'decorator-woocommerce-email-customizer'),
                        'customer_completed_renewal_order' => __('Customer completed renewal order', 'decorator-woocommerce-email-customizer'),
                        'customer_completed_switch_order' => __('Customer completed switch order', 'decorator-woocommerce-email-customizer'),
                        'customer_renewal_invoice' => __('Customer renewal invoice', 'decorator-woocommerce-email-customizer'),
                        'cancelled_subscription' => __('Cancelled subscription', 'decorator-woocommerce-email-customizer'),
                    ));
                }
                if (class_exists('WC_Memberships')) {
                    $types = array_merge($types, array(
                        'WC_Memberships_User_Membership_Note_Email' => __('User membership note', 'decorator-woocommerce-email-customizer'),
                        'WC_Memberships_User_Membership_Ending_Soon_Email' => __('User membership ending soon', 'decorator-woocommerce-email-customizer'),
                        'WC_Memberships_User_Membership_Ended_Email' => __('User membership ended', 'decorator-woocommerce-email-customizer'),
                        'WC_Memberships_User_Membership_Renewal_Reminder_Email' => __('User membership renewal reminder', 'decorator-woocommerce-email-customizer'),
                        'WC_Memberships_User_Membership_Activated_Email' => __('User membership activated', 'decorator-woocommerce-email-customizer'),
                    ));
                }
                if (class_exists('WCMp')) {
                    $types = array_merge($types, array(
                        'vendor_new_account' => __('New vendor account', 'decorator-woocommerce-email-customizer'),
                        'admin_new_vendor' => __('Admin new vendor account', 'decorator-woocommerce-email-customizer'),
                        'approved_vendor_new_account' => __('Approved vendor account', 'decorator-woocommerce-email-customizer'),
                        'rejected_vendor_new_account' => __('Rejected vendor account', 'decorator-woocommerce-email-customizer'),
                        'vendor_new_order' => __('Vendor new order', 'decorator-woocommerce-email-customizer'),
                        'notify_shipped' => __('Notify as shipped.', 'decorator-woocommerce-email-customizer'),
                        'admin_new_vendor_product' => __('New vendor product', 'decorator-woocommerce-email-customizer'),
                        'admin_added_new_product_to_vendor' => __('New vendor product by admin', 'decorator-woocommerce-email-customizer'),
                        'vendor_commissions_transaction' => __('Transactions (for vendor)', 'decorator-woocommerce-email-customizer'),
                        'vendor_direct_bank' => __('Commission paid (for vendor) by BAC', 'decorator-woocommerce-email-customizer'),
                        'admin_widthdrawal_request' => __('Withdrawal request to admin from vendor by BAC', 'decorator-woocommerce-email-customizer'),
                        'vendor_orders_stats_report' => __('Vendor orders stats report', 'decorator-woocommerce-email-customizer'),
                        'vendor_contact_widget_email' => __('Vendor contact email', 'decorator-woocommerce-email-customizer'),
                    ));
                }
                if (class_exists('WooCommerce_Germanized')) {
                    $types = array_merge($types, array(
                        'customer_ekomi' => __('eKomi review reminder', 'decorator-woocommerce-email-customizer'),
                        'customer_new_account_activation' => __('New account activation', 'decorator-woocommerce-email-customizer'),
                        'customer_paid_for_order' => __('Paid for order', 'decorator-woocommerce-email-customizer'),
                        'customer_revocation' => __('Revocation', 'decorator-woocommerce-email-customizer'),
                        'customer_trusted_shops' => __('Trusted shops review reminder', 'decorator-woocommerce-email-customizer'),
                    ));
                }
                if (class_exists('WooCommerce_Waitlist_Plugin')) {
                    $types = array_merge($types, array(
                        'woocommerce_waitlist_mailout' => __('Waitlist mailout', 'decorator-woocommerce-email-customizer'),
                    ));
                }
                if (class_exists('WC_Stripe')) {
                    $types = array_merge($types, array(
                        'failed_renewal_authentication' => __('Failed subscription renewal SCA authentication', 'decorator-woocommerce-email-customizer'),
                        'failed_preorder_sca_authentication' => __('Pre-order payment action needed', 'decorator-woocommerce-email-customizer'),
                    ));
                }
                if (class_exists('WC_Stripe') && class_exists('WC_Subscriptions')) {
                    $types = array_merge($types, array(
                        'failed_authentication_requested' => __('Payment authentication requested email', 'decorator-woocommerce-email-customizer'),
                    ));
                }

                self::$email_types = apply_filters('wt_decorator_email_template_types', $types);
            }

            return self::$email_types;
        }

        /**
         * plugin supported template for body content editing
         *
         * @access public
         * @param null
         * @return array
         */
        public static function get_body_compactible_email_types() {

            $types = array(
                'new_order' => __('New Order', 'decorator-woocommerce-email-customizer'),
                'cancelled_order' => __('Cancelled Order', 'decorator-woocommerce-email-customizer'),
                'customer_processing_order' => __('Customer Processing Order', 'decorator-woocommerce-email-customizer'),
                'customer_completed_order' => __('Customer Completed Order', 'decorator-woocommerce-email-customizer'),
                'customer_refunded_order' => __('Customer Refunded Order', 'decorator-woocommerce-email-customizer'),
                'customer_on_hold_order' => __('Customer On Hold Order', 'decorator-woocommerce-email-customizer'),
                'customer_invoice' => __('Customer Invoice', 'decorator-woocommerce-email-customizer'),
                'failed_order' => __('Failed Order', 'decorator-woocommerce-email-customizer'),
                'customer_new_account' => __('Customer New Account', 'decorator-woocommerce-email-customizer'),
                'customer_note' => __('Customer Note', 'decorator-woocommerce-email-customizer'),
                'customer_reset_password' => __('Customer Reset Password', 'decorator-woocommerce-email-customizer'),
            );
            if (class_exists('WC_Subscriptions')) {
                $types = array_merge($types, array(
                    'new_renewal_order' => __('New Renewal Order', 'decorator-woocommerce-email-customizer'),
                    'customer_processing_renewal_order' => __('Customer Processing Renewal Order', 'decorator-woocommerce-email-customizer'),
                    'customer_completed_renewal_order' => __('Customer Completed Renewal Order', 'decorator-woocommerce-email-customizer'),
                    'customer_completed_switch_order' => __('Customer Completed Switch Order', 'decorator-woocommerce-email-customizer'),
                    'customer_renewal_invoice' => __('Customer Renewal Invoice', 'decorator-woocommerce-email-customizer'),
                    'cancelled_subscription' => __('Cancelled Subscription', 'decorator-woocommerce-email-customizer'),
                    'expired_subscription' => __('Expired Subscription', 'decorator-woocommerce-email-customizer'),
                    'suspended_subscription' => __('Suspended Subscription', 'decorator-woocommerce-email-customizer'),
                ));
            }

            if ( defined('WT_SMARTCOUPON_INSTALLED_VERSION') && WT_SMARTCOUPON_INSTALLED_VERSION ==  'PREMIUM' ) {
                $types = array_merge($types, array(
                    'wt_smart_coupon_gift' => __('Smart Coupon Gift', 'decorator-woocommerce-email-customizer'),
                    'wt_smart_coupon_abandonment_coupon_email' => __('Smart Coupon Abandonment Coupon Email', 'decorator-woocommerce-email-customizer'),
                    'wt_smart_coupon_signup_coupon_email'   => __( 'Smart Coupon Signup Coupon Email', 'decorator-woocommerce-email-customizer' ),
                    'wt_smart_coupon'          => __( 'Smart Coupon', 'decorator-woocommerce-email-customizer' ),
                ));
            }


            return $types;
        }

        /**
         * Get the email content
         *
         */
        public static function get_preview_email($send_email = false, $email_addresses = null, $email_type = null, $order_id = null) {
            // Load WooCommerce emails.
            $wc_emails = WC_Emails::instance();
            $emails = $wc_emails->get_emails();
            $email_template = isset($email_type) && !empty($email_type) ? $email_type : RP_Decorator_Customizer::opt('email_type');
            $preview_id = isset($order_id) && !empty($order_id) ? $order_id : RP_Decorator_Customizer::opt('preview_order_id');
            if (strlen($email_template) > 29 && 'cartflows_ca_email_templates_' === substr($email_template, 29)) {
                $email_template = 'cartflows_ca_email_templates';
            }
            $email_type = self::get_email_class_name($email_template);
            if (false === $email_type) {
                return false;
            }
            $order_status = self::get_email_order_status($email_template);

            if ('customer_invoice' == $email_template) {
                $invoice_paid = RP_Decorator_Customizer::opt('customer_invoice_switch');
                if (!$invoice_paid) {
                    $order_status = 'pending';
                }
            }

            if ('customer_refunded_order' == $email_template) {
                $partial_preview = RP_Decorator_Customizer::opt('customer_refunded_order_switch');
                if (!$partial_preview) {
                    $partial_status = true;
                } else {
                    $partial_status = false;
                }
            }
            // Reference email.
            if (isset($emails[$email_type]) && is_object($emails[$email_type])) {
                $email = $emails[$email_type];
            };

            // Get an order
            $order = self::get_wc_order_for_preview($order_status, $preview_id);
            if (is_object($order)) {
                // Get user ID from order, if guest get current user ID.
                if (0 === ( $user_id = (int) get_post_meta($order->get_id(), '_customer_user', true) )) {
                    $user_id = get_current_user_id();
                }
            } else {
                $user_id = get_current_user_id();
            }
            // Get user object
            $user = get_user_by('id', $user_id);
            self::$wt_product_title = 'Product Title Example';
            if ('woocommerce_waitlist_mailout' == $email_template) {

                $product_id = -1;
                if (is_object($order)) {
                    $items = $order->get_items();
                    foreach ($items as $item) {
                        $product_id = $item['product_id'];
                        if (null !== get_post($product_id)) {
                            break;
                        }
                    }
                }

                if (null === get_post($product_id)) {

                    $args = array(
                        'posts_per_page' => 1,
                        'orderby' => 'date',
                        'post_type' => 'product',
                        'post_status' => 'publish',
                    );
                    $products_array = get_posts($args);

                    if (isset($products_array[0]->ID)) {
                        $product_id = $products_array[0]->ID;
                    }
                }
            }
            if (class_exists('Wt_Smart_Coupon')) {
                $args = array(
                    'posts_per_page' => -1,
                    'orderby' => 'title',
                    'order' => 'asc',
                    'post_type' => 'shop_coupon',
                );
                $coupon = 'wt_smart_coupon';
                $coupons = get_posts($args);
                if (!empty($coupons)) {
                    $coupon_id = $coupons[0]->ID;
                }
                if(!empty($coupon_id)){
                    $coupon = new WC_Coupon($coupon_id);
                }
            }


            if (isset($email)) {
                WC()->payment_gateways();
                WC()->shipping();
                switch ($email_template) {
                    case 'new_order':
                    case 'cancelled_order':
                    case 'customer_processing_order':
                    case 'customer_completed_order':
                    case 'customer_on_hold_order':
                    case 'failed_renewal_authentication':
                    case 'failed_preorder_sca_authentication':
                    case 'failed_order':
                        $email->object = $order;
                        $email = self::wt_header_shortcode_replace($email);
                        break;
                    case 'customer_invoice':
                        $email->object = $order;
                        if (is_object($order)) {
                            $email->invoice = ( function_exists('wc_gzdp_get_order_last_invoice') ? wc_gzdp_get_order_last_invoice($order) : null );
                        }
                        $email = self::wt_header_shortcode_replace($email);
                        break;
                    case 'customer_refunded_order':
                        $email->object = $order;
                        $email = self::wt_header_shortcode_replace($email);
                        break;
                    case 'customer_new_account':
                        $email->object = $user;
                        $email->user_pass = '{user_pass}';
                        $email->user_login = stripslashes($email->object->user_login);
                        $email->user_email = stripslashes($email->object->user_email);
                        $email->recipient = $email->user_email;
                        $email->password_generated = true;
                        $email = self::wt_header_shortcode_replace($email);
                        break;
                    case 'customer_note':
                        $email->object = $order;
                        $email->customer_note = __('Hello! This is an example note', 'decorator-woocommerce-email-customizer');
                        $email = self::wt_header_shortcode_replace($email);
                        break;
                    case 'customer_reset_password':
                        $email->object = $user;
                        $email->user_id = $user_id;
                        $email->user_login = $user->user_login;
                        $email->user_email = stripslashes($email->object->user_email);
                        $email->reset_key = '{{reset-key}}';
                        $email->recipient = stripslashes($email->object->user_email);
                        $email = self::wt_header_shortcode_replace($email);
                        break;
                    case 'wt_smart_coupon_store_credit':
                        $email->coupon_id = isset($coupon_id) && $coupon_id ? $coupon_id : '';
                        $email = self::wt_header_shortcode_replace($email);
                        break;
                    case 'wt_smart_coupon_gift':
                        $email->object = $coupon ? $coupon : '';
                        $email = self::wt_header_shortcode_replace($email);
                        break;
                    case 'wt_smart_coupon':
                        $email->coupon_id = isset($coupon_id) && $coupon_id ? $coupon_id : '';
                        $email->object = $coupon ? $coupon : '';
                        $email = self::wt_header_shortcode_replace($email);
                        break;
                    case 'wt_smart_coupon_signup_coupon_email':
                    case 'wt_smart_coupon_abandonment_coupon_email':
                        $email->object = $coupon ? $coupon : '';
                        $email = self::wt_header_shortcode_replace($email);
                        break;
                    //WooCommerce Subscriptions
                    case 'new_renewal_order':
                    case 'new_switch_order':
                    case 'admin_payment_retry':
                    case 'customer_processing_renewal_order':
                    case 'customer_completed_renewal_order':
                    case 'customer_renewal_invoice':
                        $email->object = $order;
                        $email = self::wt_header_shortcode_replace($email);

                        break;
                    case 'customer_completed_switch_order':
                        $email->object = $order;
                        $email = self::wt_header_shortcode_replace($email);
                        $subscriptions = false;
                        if (!empty($preview_id) && 'mockup' != $preview_id) {
                            if (function_exists('wcs_get_subscriptions_for_switch_order')) {
                                $subscriptions = wcs_get_subscriptions_for_switch_order($preview_id);
                            }
                        }
                        if ($subscriptions) {
                            $email->subscriptions = $subscriptions;
                        } else {
                            $email->subscriptions = array();
                        }
                        $email = self::wt_header_shortcode_replace($email);
                        break;
                    case 'expired_subscription':
                    case 'suspended_subscription':
                    case 'cancelled_subscription':
                        $subscription = false;
                        if (!empty($preview_id) && 'mockup' != $preview_id) {
                            if (function_exists('wcs_get_subscriptions_for_order')) {
                                $subscriptions_ids = wcs_get_subscriptions_for_order($preview_id);
                                if ($subscriptions_ids) {
                                    foreach ($subscriptions_ids as $subscription_id => $subscription_obj) {
                                        if ($subscription_obj->get_parent_id() == $preview_id) {
                                            $subscription = $subscription_obj;
                                            break; // Stop the loop).
                                        }
                                    }
                                }
                            }
                        }
                        if ($subscription) {
                            $email->object = $subscription;
                        } else {
                            $email->object = 'subscription';
                        }
                        $email = self::wt_header_shortcode_replace($email);
                        break;

                    //WooCommerce Membership.
                    case 'WC_Memberships_User_Membership_Note_Email':
                    case 'WC_Memberships_User_Membership_Ending_Soon_Email':
                    case 'WC_Memberships_User_Membership_Ended_Email':
                    case 'WC_Memberships_User_Membership_Renewal_Reminder_Email':
                    case 'WC_Memberships_User_Membership_Activated_Email':
                        if (function_exists('wc_memberships_get_user_membership')) {
                            $memberships = wc_memberships_get_user_active_memberships($user_id);

                            if (!empty($memberships)) {
                                $user_membership = $memberships[0];
                                self::$current_member = $user_membership;
                                $email->object = $user_membership;
                                $email_id = strtolower($email_template);
                                $email_body = $email->object->get_plan()->get_email_content($email_template);
                                $member_body = (string) apply_filters("{$email_id}_email_body", $email->format_string($email_body), $email->object);

                                if (empty($member_body) || !is_string($member_body) || '' === trim($member_body)) {
                                    $member_body = $email->get_default_body();
                                }

                                // convert relative URLs to absolute for links href and images src attributes
                                $domain = get_home_url();
                                $replace = array();
                                $replace['/href="(?!https?:\/\/)(?!data:)(?!#)/'] = 'href="' . $domain;
                                $replace['/src="(?!https?:\/\/)(?!data:)(?!#)/'] = 'src="' . $domain;

                                $member_body = preg_replace(array_keys($replace), array_values($replace), $member_body);

                                $membership_plan = $user_membership->get_plan();

                                // get member data
                                $member = get_user_by('id', $user_membership->get_user_id());
                                $member_name = !empty($member->display_name) ? $member->display_name : '';
                                $member_first_name = !empty($member->first_name) ? $member->first_name : $member_name;
                                $member_last_name = !empty($member->last_name) ? $member->last_name : '';
                                $member_full_name = $member_first_name && $member_last_name ? $member_first_name . ' ' . $member->last_name : $member_name;

                                // membership expiry date
                                $expiration_date_timestamp = $user_membership->get_local_end_date('timestamp');

                                // placeholders
                                $email_merge_tags = array(
                                    'member_name' => $member_name,
                                    'member_first_name' => $member_first_name,
                                    'member_last_name' => $member_last_name,
                                    'member_full_name' => $member_full_name,
                                    'membership_plan' => $membership_plan ? $membership_plan->get_name() : '',
                                    'membership_expiration_date' => date_i18n(wc_date_format(), $expiration_date_timestamp),
                                    'membership_expiry_time_diff' => human_time_diff(current_time('timestamp', true), $expiration_date_timestamp),
                                    'membership_view_url' => esc_url($user_membership->get_view_membership_url()),
                                    'membership_renewal_url' => esc_url($user_membership->get_renew_membership_url()),
                                );
                                foreach ($email_merge_tags as $find => $replace) {
                                    $email->find[$find] = '{' . $find . '}';
                                    $email->replace[$find] = $replace;
                                    $member_body = str_replace('{' . $find . '}', $replace, $member_body);
                                }
                            } else {
                                $email->object = 'member';
                            }
                        } else {
                            $email->object = false;
                        }
                        break;
                    //WC MarketPlace
                    case 'vendor_new_order':
                        if (is_object($order)) {
                            $order_id = $order->get_id();
                            if (function_exists('get_vendor_from_an_order')) {
                                if (1 === $order_id) {
                                    $email->object = 'vendor';
                                } else {
                                    $vendors = get_vendor_from_an_order($order_id);

                                    if ($vendors) {
                                        $vendor = $vendors[0];

                                        $vendor_obj = get_wcmp_vendor_by_term($vendor);
                                        $vendor_email = $vendor_obj->user_data->user_email;
                                        $vendor_id = $vendor_obj->id;

                                        if ($order_id && $vendor_email) {
                                            $email->object = $email->order = $order;
                                            $email->find[] = '{order_date}';
                                            $email->replace[] = wc_format_datetime($email->object->get_date_created());

                                            $email->find[] = '{order_number}';
                                            $email->replace[] = $email->object->get_order_number();
                                            $email->vendor_email = $vendor_email;
                                            $email->vendor_id = $vendor_id;
                                            $email->recipient = $vendor_email;
                                        }
                                    } else {
                                        $email->object = 'vendor';
                                    }
                                }
                            } else {
                                $email->object = false;
                            }
                        } else {
                            $email->object = false;
                        }
                        break;
                    //WooCommerce Wait-list Plugin
                    case 'woocommerce_waitlist_mailout':
                        $email->object = wc_get_product($product_id);
                        $email->find[] = '{product_title}';
                        $email->replace[] = $email->object->get_title();
                        self::$wt_product_title = $email->object->get_title();
                        break;

                    case 'failed_preorder_sca_authentication' :
                        $email->object = $order;
                        $email->find['order-number'] = '{order_number}';
                        $email->replace['order-number'] = $email->object->get_order_number();
                        break;
                    case 'failed_renewal_authentication' :
                        $email->object = $order;
                        $email->find['order-number'] = '{order_number}';
                        $email->replace['order-number'] = $email->object->get_order_number();
                        break;

                    case 'failed_authentication_requested':
                        $email->object = $order;
                        $email->find['order-date'] = '{order_date}';
                        $email->find['order-number'] = '{order_number}';
                        if (is_object($order)) {
                            $email->replace['order-date'] = wc_format_datetime($email->object->get_date_created());
                            $email->replace['order-number'] = $email->object->get_order_number();
                            // Other properties
                            $email->recipient = $email->object->get_billing_email();
                        }
                        if (!empty($preview_id) && 'mockup' != $preview_id) {
                            if (class_exists('WCS_Retry_Manager') && WCS_Retry_Manager::is_retry_enabled()) {
                                $retry = WCS_Retry_Manager::store()->get_last_retry_for_order($preview_id);
                                if (!empty($retry) && is_object($retry)) {
                                    $email->retry = $retry;
                                    $email->find['retry_time'] = '{retry_time}';
                                    $email->replace['retry_time'] = wcs_get_human_time_diff($email->retry->get_time());
                                } else {
                                    $email->object = 'retry';
                                }
                            } else {
                                $email->object = 'retry';
                            }
                        } else {
                            $email->object = 'retry';
                        }
                        break;
                    //Woo Advanced Shipment Tracking
                    case 'customer_delivered_order':
                        $email->object = $order;
                        if (is_object($order)) {
                            $email->find['order-date'] = '{order_date}';
                            $email->find['order-number'] = '{order_number}';
                            $email->replace['order-date'] = wc_format_datetime($email->object->get_date_created());
                            $email->replace['order-number'] = $email->object->get_order_number();
                            // Other properties
                            $email->recipient = $email->object->get_billing_email();
                        }
                        break;
                    //default here.
                    default:
                        $email->object = $order;
                        $email = apply_filters('wt_decorator_preview_email_object', $email);
                        break;
                }

                if (true == $send_email && !empty($email_addresses)) {
                    
                    $email->setup_locale();

                    if ($email_addresses) {
                        $content = $email->send($email_addresses, $email->get_subject(), $email->get_content(), $email->get_headers(), $email->get_attachments());
                    }
                    $email->restore_locale();
                    return $content;
                } else {
                    if ('wt_smart_coupon' == $email->object && ($email_template !== 'wt_smart_coupon_store_credit')) {
                        $content = '<div style="padding: 35px 40px; background-color: white;">' . __('Please create atleast one coupon to preview this email type.', 'decorator-woocommerce-email-customizer') . '</div>';
                    }else if (false == $email->object && ($email_template !== 'wt_smart_coupon_store_credit')) {
                        $content = '<div style="padding: 35px 40px; background-color: white;">' . __('This email type can not be previewed please try a different order or email type.', 'decorator-woocommerce-email-customizer') . '</div>';
                    } else if ('subscription' == $email->object) {
                        $content = '<div style="padding: 35px 40px; background-color: white;">' . __('This email type requires that an order containing a subscription be selected as the preview order.', 'decorator-woocommerce-email-customizer') . '</div>';
                    } else if ('retry' == $email->object) {
                        $content = '<div style="padding: 35px 40px; background-color: white;">' . __('To generate a preview of this email type you must choose an order containing a subscription which has also failed to auto renew as the preview order in the settings.', 'decorator-woocommerce-email-customizer') . '</div>';
                    } else if ('vendor' == $email->object) {
                        $content = '<div style="padding: 35px 40px; background-color: white;">' . __('This email type requires that an order containing a vendor purchase be selected as the preview order.', 'decorator-woocommerce-email-customizer') . '</div>';
                    } else if ('member' == $email->object) {
                        $content = '<div style="padding: 35px 40px; background-color: white;">' . __('This email type requires that an order containing a user who has an active membership be selected as the preview order.', 'decorator-woocommerce-email-customizer') . '</div>';
                    } else if ('WC_Memberships_User_Membership_Ending_Soon_Email' === $email_template || 'WC_Memberships_User_Membership_Renewal_Reminder_Email' === $email_template || 'WC_Memberships_User_Membership_Activated_Email' === $email_template || 'WC_Memberships_User_Membership_Ended_Email' === $email_template) {
                        $args = array(
                            'user_membership' => $email->object,
                            'email' => $email,
                            'email_heading' => $email->get_heading(),
                            'email_body' => $member_body,
                            'sent_to_admin' => false,
                        );
                        ob_start();

                        wc_get_template($email->template_html, $args);

                        $content = ob_get_clean();
                        $content = $email->style_inline($content);
                        $content = apply_filters('woocommerce_mail_content', $content);

                        if ('plain' === $email->email_type) {
                            $content = '<div style="padding: 35px 40px; background-color: white;">' . str_replace("\n", '<br/>', $content) . '</div>';
                        }
                    } else {
                        // Get email content and apply styles.
                        $content = $email->get_content();
                        $content = $email->style_inline($content);
                        $content = apply_filters('woocommerce_mail_content', $content);

                        if ('plain' === $email->email_type) {
                            $content = '<div style="padding: 35px 40px; background-color: white;">' . str_replace("\n", '<br/>', $content) . '</div>';
                        }
                    }
                }
            } else {

                $content = false;
            }

            return $content;
        }

        /**
         * Replace short codes in header
         *
         * @access public
         * @param email 
         * @return object
         */
        public static function wt_header_shortcode_replace($object) {
            $object->find['site_address'] = '{site_address}';
            $object->find['site_url'] = '{site_url}';
            $object->find['site_title'] = '{site_title}';
            $object->replace['site_address'] = wp_parse_url(home_url(), PHP_URL_HOST);
            $object->replace['site-url'] = wp_parse_url(home_url(), PHP_URL_HOST);
            $object->replace['site-title'] = get_bloginfo('name', 'display');
            if (is_a($object->object, 'WP_User')) {
                $user_first_name = get_user_meta($object->object->ID, 'billing_first_name', true);
                if (empty($user_first_name)) {
                    $user_first_name = $object->object->display_name;
                }

                $user_last_name = get_user_meta($object->object->ID, 'billing_last_name', true);
                if (empty($user_last_name)) {
                    $user_last_name = $object->object->display_name;
                }

                $user_full_name = get_user_meta($object->object->ID, 'formatted_billing_full_name', true);
                if (empty($user_full_name)) {
                    $user_full_name = $object->object->display_name;
                }
                $object->find['customer_first_name'] = '{customer_first_name}';
                $object->replace['customer_first_name'] = $user_first_name;
                $object->find['customer_full_name'] = '{customer_full_name}';
                $object->replace['customer_full_name'] = $user_full_name;
                $object->find['customer_last_name'] = '{customer_last_name}';
                $object->replace['customer_last_name'] = $user_last_name;
                $object->find['customer_username'] = '{customer_username}';
                $object->replace['customer_username'] = $object->user_login;
                $object->find['customer_email'] = '{customer_email}';
                $object->replace['customer_email'] = $object->object->user_email;
            } elseif (is_a($object->object, 'WC_Order')) {

                if (0 === ( $user_id = (int) get_post_meta($object->object->get_id(), '_customer_user', true) )) {
                    $user_id = 'guest';
                }
                $object->find['order_date'] = '{order_date}';
                $object->replace['order_date'] = wc_format_datetime($object->object->get_date_created());
                $object->find['order_number'] = '{order_number}';
                $object->replace['order_number'] = $object->object->get_order_number();
                $object->find['customer_first_name'] = '{customer_first_name}';
                $object->replace['customer_first_name'] = $object->object->get_billing_first_name();
                $object->find['customer_last_name'] = '{customer_last_name}';
                $object->replace['customer_last_name'] = $object->object->get_billing_last_name();
                $object->find['customer_full_name'] = '{customer_full_name}';
                $object->replace['customer_full_name'] = $object->object->get_formatted_billing_full_name();
                $object->find['customer_company'] = '{customer_company}';
                $object->replace['customer_company'] = $object->object->get_billing_company();
                $object->find['customer_email'] = '{customer_email}';
                $object->replace['customer_email'] = $object->object->get_billing_email();
            }

            return $object;
        }

        /**
         * Get the email class name
         *
         * @param string $email_template the email template slug.
         */
        public static function get_email_class_name($email_template) {
            $class_names = apply_filters('wt_decorator_email_type_class_name_array', self::$wt_supported_email_class_names);
            if (isset($class_names[$email_template])) {
                return $class_names[$email_template];
            } else {
                return false;
            }
        }

        /**
         * Get the email order status
         *
         * @param string $email_template the template string name.
         */
        public static function get_email_order_status($email_template) {
            $order_status = apply_filters('wt_decorator_email_template_type_order_status_array', self::$wt_supported_email_order_status);
            if (isset($order_status[$email_template])) {
                return $order_status[$email_template];
            } else {
                return 'processing';
            }
        }

        /**
         * save data
         *
         * @param $data, $filter_context
         */
        public static function wt_customize_changeset_save_data($data, $filter_context) {
            $reset_link =FALSE;
            $wt_custom_style = RP_Decorator_Customizer::$wt_template_type;
            if (empty($wt_custom_style)) {

                $wt_custom_style = RP_Decorator_Customizer::wt_get_current_template();
            }
            
            if (isset($_POST['customized']) && !empty($_POST['customized'])) {
                $enable_data = json_decode(wp_unslash($_POST['customized']), true);
                if (isset($enable_data['rp_decorator_'.$wt_custom_style.'_body_text_enable_switch']) ) {
                    $enable_value = !empty($enable_data['rp_decorator_'.$wt_custom_style.'_body_text_enable_switch']) ? $enable_data['rp_decorator_'.$wt_custom_style.'_body_text_enable_switch'] : '';
                    update_option( 'rp_decorator_'.$wt_custom_style.'_body_text_enable_switch', $enable_value);
                }
                
            }
            
            if(empty($data['wt_decorator_' . $wt_custom_style . '_image_link_btn_switch']['value'])){
                $reset_link = TRUE;
            }
            if (($filter_context['status'] == 'draft' ) && !empty($data)) {
                $custom_data = array();
                $wt_custom_data = array();
                $wt_custom_data = (array) get_option('wt_decorator_custom_styles_in_draft', array());
                $wt_custom_data_scheduled = (array) get_option('wt_decorator_custom_styles_scheduled', array());
                $wt_stored = (array) get_option('wt_decorator_custom_styles', array());

                foreach ($data as $data_key => $data_value) {
                    if (preg_match('/rp_decorator\[(.*?)\]/', $data_key, $match)) {
                        $key = $match[1];
                        $custom_data[$key] = $data_value['value'];
                    } else {
                        if (strstr($data_key, '[')) {
                            $content_details = explode('[', $data_key);
                            update_option($content_details[0], array(rtrim($content_details[1], ']') => $data_value['value']));
                        } else {
                            update_option($data_key, $data_value['value']);
                        }
                    }
                }
                if ($custom_data) {
                    $email_tp = isset($custom_data['email_type']) && !empty($custom_data['email_type']) ? $custom_data['email_type'] : $wt_custom_style;
                    $key = $email_tp;
                    if (array_key_exists($key, $wt_custom_data_scheduled)) {
                        foreach ($custom_data as $c_key => $c_value) {
                            if ($c_key == 'email_type' && empty($c_value)) {
                                $c_value = 'new_order';
                            }
                            $wt_custom_data_scheduled[$key][$c_key] = $c_value;
                        }
                        $wt_custom_data[$key] = $wt_custom_data_scheduled[$key];
                        unset($wt_custom_data_scheduled[$key]);
                        update_option('wt_decorator_custom_styles_scheduled', $wt_custom_data_scheduled);
                    } elseif (array_key_exists($key, $wt_custom_data)) {
                        foreach ($custom_data as $c_key => $c_value) {
                            if ($c_key == 'email_type' && empty($c_value)) {
                                $c_value = 'new_order';
                            }
                            $wt_custom_data[$key][$c_key] = $c_value;
                        }
                    } elseif (array_key_exists($key, $wt_stored)) {
                        foreach ($custom_data as $c_key => $c_value) {
                            $wt_stored[$key][$c_key] = $c_value;
                        }

                        $wt_custom_data[$key] = $wt_stored[$key];
                    } else {
                        if (!isset($custom_data['email_type']) && empty($custom_data['email_type'])) {
                            $custom_data['email_type'] = 'new_order';
                        }
                        $wt_custom_data[$key] = $custom_data;
                    }
                    if($reset_link){
                        if(array_key_exists('image_link', $wt_custom_data[$wt_custom_style])){
                          unset($wt_custom_data[$wt_custom_style]['image_link']);
                        }
                    }
                    update_option('wt_decorator_custom_styles_in_draft', $wt_custom_data);
                }
            } elseif ($filter_context['status'] == 'future' && !empty($data)) {
                $wt_stored = (array) get_option('wt_decorator_custom_styles', array());
                $wt_custom_data_draft = (array) get_option('wt_decorator_custom_styles_in_draft', array());
                $wt_custom_data_scheduled = (array) get_option('wt_decorator_custom_styles_scheduled', array());

                $custom_data = array();
                $wt_custom_data = array();
                foreach ($data as $data_key => $data_value) {
                    if (preg_match('/rp_decorator\[(.*?)\]/', $data_key, $match)) {
                        $key = $match[1];
                        $custom_data[$key] = $data_value['value'];
                    }
                }

                if ($custom_data) {
                    $email_tp = isset($custom_data['email_type']) && !empty($custom_data['email_type']) ? $custom_data['email_type'] : $wt_custom_style;
                    $key = $email_tp;
                    if (array_key_exists($key, $wt_custom_data_draft) && !array_key_exists($key, $wt_custom_data_scheduled)) {
                        foreach ($custom_data as $c_key => $c_value) {
                            if ($c_key == 'email_type' && empty($c_value)) {
                                $c_value = 'new_order';
                            }
                            $wt_custom_data_draft[$key][$c_key] = $c_value;
                        }
                        $wt_custom_data_scheduled[$key] = $wt_custom_data_draft[$key];
                        $wt_custom_data_scheduled[$key]['date_gmt'] = $filter_context['date_gmt'];
                        unset($wt_custom_data_draft[$key]);
                        update_option('wt_decorator_custom_styles_in_draft', $wt_custom_data_draft);
                    } elseif (array_key_exists($key, $wt_custom_data_scheduled)) {
                        foreach ($custom_data as $c_key => $c_value) {
                            if ($c_key == 'email_type' && empty($c_value)) {
                                $c_value = 'new_order';
                            }
                            $wt_custom_data_scheduled[$key][$c_key] = $c_value;
                        }
                        $wt_custom_data_scheduled[$key]['date_gmt'] = $filter_context['date_gmt'];
                    } elseif (array_key_exists($key, $wt_stored)) {
                        foreach ($custom_data as $c_key => $c_value) {
                            $wt_stored[$key][$c_key] = $c_value;
                        }
                        $wt_custom_data[$key] = $wt_stored[$key];
                        $wt_custom_data_scheduled[$key] = $wt_custom_data[$key];
                        $wt_custom_data_scheduled[$key]['date_gmt'] = $filter_context['date_gmt'];
                    } else {
                        $wt_custom_data_scheduled[$key] = $custom_data;
                        $wt_custom_data_scheduled[$key]['date_gmt'] = $filter_context['date_gmt'];
                    }
                    update_option('wt_decorator_custom_styles_scheduled', $wt_custom_data_scheduled);
                }
            } 
            return $data;
        }

        /**
         * save response
         *
         * @param $data, $filter_context
         * @return  $data
         */
        public static function wt_customize_save_response($data, $filter_context) {
                $reset_link =FALSE;
                $wt_stored = (array) get_option('wt_decorator_custom_styles', array());
                $wt_custom_draft_data = (array) get_option('wt_decorator_custom_styles_in_draft', array());
                $stored = (array) get_option('rp_decorator', array());
                $wt_custom_data_scheduled = (array) get_option('wt_decorator_custom_styles_scheduled', array());
                $current_temp = RP_Decorator_Customizer::wt_get_current_template();
                if(empty($data['wt_decorator_' . $current_temp . '_image_link_btn_switch']['value'])){
                 $reset_link = TRUE;
                }
                if ($stored) {
                    if (array_key_exists('email_type', $stored) || $current_temp !== 'new_order') {
                        $key = $stored['email_type'] ? $stored['email_type'] : $current_temp;
                        foreach ($stored as $st_key => $st_value) {
                            $wt_stored[$key][$st_key] = $st_value;
                        }
                        if (isset($wt_custom_draft_data[$key]) && !empty($wt_custom_draft_data[$key])) {
                            foreach ($wt_custom_draft_data[$key] as $cu_key => $cu_value) {
                                $wt_stored[$key][$cu_key] = $cu_value;
                            }
                            unset($wt_custom_draft_data[$key]);
                        } elseif (isset($wt_custom_data_scheduled[$key]) && !empty($wt_custom_data_scheduled[$key])) {
                            foreach ($wt_custom_data_scheduled[$key] as $cu_key => $cu_value) {
                                $wt_stored[$key][$cu_key] = $cu_value;
                            }
                            unset($wt_custom_data_scheduled[$key]);
                        }
                    } else {
                        foreach ($stored as $st_key => $st_value) {
                            $wt_stored['new_order'][$st_key] = $st_value;
                        }
                        if (isset($wt_custom_draft_data['new_order']) && !empty($wt_custom_draft_data['new_order'])) {
                            foreach ($wt_custom_draft_data['new_order'] as $cu_key => $cu_value) {
                                $wt_stored[$key][$cu_key] = $cu_value;
                            }
                            unset($wt_custom_draft_data['new_order']);
                        }
                        if (isset($wt_custom_data_scheduled['new_order']) && !empty($wt_custom_data_scheduled['new_order'])) {
                            foreach ($wt_custom_data_scheduled['new_order'] as $cu_key => $cu_value) {
                                $wt_stored['new_order'][$cu_key] = $cu_value;
                            }
                            unset($wt_custom_data_scheduled['new_order']);
                        }
                    }
                     if($reset_link){
                        if(array_key_exists('image_link', $wt_stored[$current_temp])){
                          unset($wt_stored[$current_temp]['image_link']);
                        }
                    }
                    update_option('wt_decorator_custom_styles', $wt_stored);
                    update_option('wt_decorator_custom_styles_in_draft', $wt_custom_draft_data);
                    update_option('wt_decorator_custom_styles_scheduled', $wt_custom_data_scheduled);
                }
                update_option('rp_decorator', array());
            return $data;
        }

        /**
         * Setup schedule
         * @access public
         * @return void
         */
        public function wt_scheduled_action() {

            $wt_custom_data_scheduled = (array) get_option('wt_decorator_custom_styles_scheduled', array());
            $wt_stored = (array) get_option('wt_decorator_custom_styles', array());
            if (!empty($wt_custom_data_scheduled)) {
                $current_timestamp = strtotime(gmdate("Y-m-d H:i:s"));
                foreach ($wt_custom_data_scheduled as $sc_key => $sc_value) {
                    if (isset($sc_value['date_gmt']) && !empty($sc_value['date_gmt'])) {
                        $scheduled_timestamp = strtotime($sc_value['date_gmt']);
                        if ($current_timestamp >= $scheduled_timestamp) {
                            $wt_stored[$sc_key] = $wt_custom_data_scheduled[$sc_key];
                            unset($wt_custom_data_scheduled[$sc_key]);
                            update_option('wt_decorator_custom_styles_scheduled', $wt_custom_data_scheduled);
                            update_option('wt_decorator_custom_styles', $wt_stored);
                        }
                    }
                }
            }
        }

        /**
         * wt_supported_email_classes
         * @access public
         * @return arrray
         */
        public function wt_supported_email_classes() {
            $classes = array(
                'new_order' => 'WC_Email_New_Order',
                'cancelled_order' => 'WC_Email_Cancelled_Order',
                'customer_processing_order' => 'WC_Email_Customer_Processing_Order',
                'customer_completed_order' => 'WC_Email_Customer_Completed_Order',
                'customer_refunded_order' => 'WC_Email_Customer_Refunded_Order',
                'customer_on_hold_order' => 'WC_Email_Customer_On_Hold_Order',
                'customer_invoice' => 'WC_Email_Customer_Invoice',
                'failed_order' => 'WC_Email_Failed_Order',
                'customer_new_account' => 'WC_Email_Customer_New_Account',
                'customer_note' => 'WC_Email_Customer_Note',
                'customer_reset_password' => 'WC_Email_Customer_Reset_Password',
                'wt_smart_coupon_gift' => 'WT_smart_Coupon_Gift',
                'wt_smart_coupon_abandonment_coupon_email' => 'WT_smart_Coupon_Abandonment_Coupon_Email',
                'wt_smart_coupon_signup_coupon_email' => 'WT_smart_Coupon_Signup_Coupon_Email',
                'wt_smart_coupon_store_credit' => 'WT_smart_Coupon_Store_Credit_Email',
                'wt_smart_coupon' => 'WT_smart_Coupon_Email',
                // WooCommerce Subscriptions Plugin.
                'new_renewal_order' => 'WCS_Email_New_Renewal_Order',
                'customer_processing_renewal_order' => 'WCS_Email_Processing_Renewal_Order',
                'customer_completed_renewal_order' => 'WCS_Email_Completed_Renewal_Order',
                'customer_completed_switch_order' => 'WCS_Email_Completed_Switch_Order',
                'customer_renewal_invoice' => 'WCS_Email_Customer_Renewal_Invoice',
                'customer_payment_retry' => 'WCS_Email_Customer_Payment_Retry',
                'admin_payment_retry' => 'WCS_Email_Payment_Retry',
                'cancelled_subscription' => 'WCS_Email_Cancelled_Subscription',
                // Woocommerce Memberships.
                'WC_Memberships_User_Membership_Note_Email' => 'WC_Memberships_User_Membership_Note_Email',
                'WC_Memberships_User_Membership_Ending_Soon_Email' => 'WC_Memberships_User_Membership_Ending_Soon_Email',
                'WC_Memberships_User_Membership_Ended_Email' => 'WC_Memberships_User_Membership_Ended_Email',
                'WC_Memberships_User_Membership_Renewal_Reminder_Email' => 'WC_Memberships_User_Membership_Renewal_Reminder_Email',
                'WC_Memberships_User_Membership_Activated_Email' => 'WC_Memberships_User_Membership_Activated_Email',
                // Waitlist Plugin.
                'woocommerce_waitlist_mailout' => 'Pie_WCWL_Waitlist_Mailout',
                // WC Marketplace.
                'vendor_new_account' => 'WC_Email_Vendor_New_Account',
                'admin_new_vendor' => 'WC_Email_Admin_New_Vendor_Account',
                'approved_vendor_new_account' => 'WC_Email_Approved_New_Vendor_Account',
                'rejected_vendor_new_account' => 'WC_Email_Rejected_New_Vendor_Account',
                'vendor_new_order' => 'WC_Email_Vendor_New_Order',
                'notify_shipped' => 'WC_Email_Notify_Shipped',
                'admin_new_vendor_product' => 'WC_Email_Vendor_New_Product_Added',
                'admin_added_new_product_to_vendor' => 'WC_Email_Admin_Added_New_Product_to_Vendor',
                'vendor_commissions_transaction' => 'WC_Email_Vendor_Commission_Transactions',
                'vendor_direct_bank' => 'WC_Email_Vendor_Direct_Bank',
                'admin_widthdrawal_request' => 'WC_Email_Admin_Widthdrawal_Request',
                'vendor_orders_stats_report' => 'WC_Email_Vendor_Orders_Stats_Report',
                'vendor_contact_widget_email' => 'WC_Email_Vendor_Contact_Widget',
                // Germanized Emails.
                'customer_ekomi' => 'WC_GZD_Email_Customer_Ekomi',
                'customer_new_account_activation' => 'WC_GZD_Email_Customer_New_Account_Activation',
                'customer_paid_for_order' => 'WC_GZD_Email_Customer_Paid_For_Order',
                'customer_revocation' => 'WC_GZD_Email_Customer_Revocation',
                'customer_sepa_direct_debit_mandate' => 'WC_GZD_Email_Customer_SEPA_Direct_Debit_Mandate',
                'customer_trusted_shops' => 'WC_GZD_Email_Customer_Trusted_Shops',
                // stripe Emails
                'failed_preorder_sca_authentication' => 'WC_Stripe_Email_Failed_Preorder_Authentication',
                'failed_renewal_authentication' => 'WC_Stripe_Email_Failed_Renewal_Authentication',
                'failed_authentication_requested' => 'WC_Stripe_Email_Failed_Authentication_Retry',
                'cartflows_ca_email_templates' => 'KWED_Cartflows_CA_Email',
            );
            $classes = apply_filters('wt_decorator_supported_email_classes', $classes);
            return $classes;
        }

        /**
         * wt_supported_email_type_status
         * @access public
         * @return array
         */
        public function wt_supported_email_type_status() {
            $statuses = array(
                'new_order' => 'processing',
                'cancelled_order' => 'cancelled',
                'customer_processing_order' => 'processing',
                'customer_completed_order' => 'completed',
                'customer_refunded_order' => 'refunded',
                'customer_on_hold_order' => 'on-hold',
                'customer_invoice' => 'processing',
                'failed_order' => 'failed',
                'customer_new_account' => null,
                'customer_note' => 'processing',
                'customer_reset_password' => null,
                // WooCommerce Subscriptions Plugin.
                'new_renewal_order' => 'processing',
                'customer_processing_renewal_order' => 'processing',
                'customer_completed_renewal_order' => 'completed',
                'customer_completed_switch_order' => 'completed',
                'customer_renewal_invoice' => 'failed',
                'cancelled_subscription' => 'cancelled',
                // Woocommerce Memberships.
                'WC_Memberships_User_Membership_Note_Email' => 'completed',
                'WC_Memberships_User_Membership_Ending_Soon_Email' => 'completed',
                'WC_Memberships_User_Membership_Ended_Email' => 'on-hold',
                'WC_Memberships_User_Membership_Renewal_Reminder_Email' => 'completed',
                'WC_Memberships_User_Membership_Activated_Email' => 'completed',
                // WC Marketplace
                'vendor_new_account' => null,
                'admin_new_vendor' => null,
                'approved_vendor_new_account' => null,
                'rejected_vendor_new_account' => null,
                'vendor_new_order' => 'processing',
                'notify_shipped' => 'completed',
                'admin_new_vendor_product' => null,
                'admin_added_new_product_to_vendor' => null,
                'vendor_commissions_transaction' => null,
                'vendor_direct_bank' => null,
                'admin_widthdrawal_request' => null,
                'vendor_orders_stats_report' => null,
                'vendor_contact_widget_email' => null,
                // Woo Advanced Shipment Tracking
                'customer_delivered_order' => 'completed',
                // Germanized Emails.
                'customer_ekomi' => 'completed',
                'customer_new_account_activation' => null,
                'customer_paid_for_order' => 'completed',
                'customer_revocation' => null,
                'customer_sepa_direct_debit_mandate' => 'completed',
                'customer_trusted_shops' => 'completed',
                // Stripe
                'failed_preorder_sca_authentication' => 'failed',
                'failed_renewal_authentication' => 'failed',
                'failed_authentication_requested' => 'failed',
            );
            $statuses = apply_filters('wt_decorator_wt_supported_email_type_status', $statuses);
            return $statuses;
        }

    }

    RP_Decorator_Preview::get_instance();
}
