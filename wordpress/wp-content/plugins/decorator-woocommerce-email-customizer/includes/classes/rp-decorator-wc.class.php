<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Integration with WooCommerce
 *
 * @class RP_Decorator_WC
 * @package Decorator
 * @author WebToffee
 */
if (!class_exists('RP_Decorator_WC')) {

    class RP_Decorator_WC {

        // Properties
        private static $overwrite_options = null;
        // Settings to overwrite
        public static $default_setting_replacement = array(
            'woocommerce_email_header_image' => 'header_image',
            'woocommerce_email_footer_text' => 'footer_content_text',
            'woocommerce_email_base_color' => null,
            'woocommerce_email_background_color' => 'background_color',
            'woocommerce_email_body_background_color' => 'email_background_color',
            'woocommerce_email_text_color' => 'text_color',
        );
        // Singleton instance
        private static $instance = false;

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
            // Hook in main email template body to customized text.
            add_action('wt_decorator_email_body_content', array($this, 'wt_alter_order_object_email_body'), 999, 4);
            // Hook in main text areas for customized emails.
            add_action('wt_decorator_email_body_content_text', array($this, 'wt_alter_customer_object_email_body'), 999, 1);
            // add line item image
            add_filter('woocommerce_email_order_items_args', array($this, 'wt_add_lineitem_image'), 999);
            // Replace default WooCommerce email style settings with Open Decorator button
            add_filter('woocommerce_email_settings', array($this, 'replace_woocommerce_email_settings'));
            add_action('woocommerce_admin_field_rp_decorator_open_customizer_button', array($this, 'print_open_customizer_button'));
            //RP_Decorator_WC::remove_decorator_draft();
            foreach (RP_Decorator_WC::$default_setting_replacement as $option => $replacement) {
                add_filter('pre_option_' . $option, array($this, 'overwrite_default_email_settings'), 9999, 2);
            }
        }

        /**
         * Check if WooCommerce settings need to be overwritten and custom styles applied
         * This is true when plugin is active and at least one custom option is stored in the database
         *
         * @access public
         * @return bool
         */
        public static function overwrite_options() {
            // Check if any settings were saved
            if (self::$overwrite_options === null) {
                self::$overwrite_options = FALSE;
                //$option = get_option('wt_decorator_custom_styles', array());
                $wt_custom_data_draft = (array) get_option('wt_decorator_custom_styles_in_draft', array());
                $wt_custom_data_scheduled = (array) get_option('wt_decorator_custom_styles_scheduled', array());
                $wt_stored = (array) get_option('wt_decorator_custom_styles', array());

                if (!empty($wt_stored)) {
                    self::$overwrite_options = $wt_stored;
                } elseif (!empty($wt_custom_data_draft)) {
                    self::$overwrite_options = $wt_custom_data_draft;
                } elseif (!empty($wt_custom_data_scheduled)) {
                    self::$overwrite_options = $wt_custom_data_scheduled;
                }
            }

            // Return result
            return self::$overwrite_options;
        }

        /**
         * remove decorator drafted posts
         *
         * @access public
         * @return bool
         */
        public static function remove_decorator_draft() {
            global $wpdb;
            $d_status = false;
            $drafted_data = $wpdb->get_results('SELECT id,post_content,post_status FROM ' . $wpdb->prefix . "posts WHERE post_type = 'customize_changeset'", ARRAY_A);
            foreach ($drafted_data as $key => $drafted_template) {
                $did = $drafted_template['id'];
                $drafted_content = $drafted_template['post_content'];
                $d_status = false;
                $draft_status = array('draft', 'auto-draft', 'future');
                if (isset($drafted_content) && !empty($drafted_content) && strstr($drafted_content, 'rp_decorator')) {
                    $p_status = $drafted_template['post_status'];
                    wp_delete_post($did);
                    $d_status = $did;
                }
            }


            return $d_status;
        }

        /**
         * Overwrite default email settings
         *
         * @access public
         * @param mixed $value
         * @param string $option
         * @return mixed
         */
        public function overwrite_default_email_settings($value, $option) {
            // Check if we know what the replacement is
            if (isset(RP_Decorator_WC::$default_setting_replacement[$option])) {

                $wt_custom_style = RP_Decorator_Customizer::$wt_template_type;
                if (empty($wt_custom_style)) {
                    $wt_custom_style = RP_Decorator_Customizer::wt_get_current_template();
                }
                // Get value from a set of custom values and return it
                return RP_Decorator_Customizer::get_stored_value(RP_Decorator_WC::$default_setting_replacement[$option], $value, $wt_custom_style);
            }

            // Return original value
            return $value;
        }

        /**
         * Replace default WooCommerce email style settings with Open Decorator button
         *
         * @access public
         * @param array $settings
         * @return array
         */
        public function replace_woocommerce_email_settings($settings) {
            // Define options that need to be replaced
            $replace = array_merge(array_keys(RP_Decorator_WC::$default_setting_replacement), array('email_template_options'));

            // Iterate over settings and unset those that are available in Customizer
            foreach ($settings as $setting_key => $setting) {
                if (isset($setting['id']) && in_array($setting['id'], $replace, true)) {
                    unset($settings[$setting_key]);
                }
            }

            // Open section
            $settings[] = array(
                'id' => 'rp_decorator',
                'type' => 'title',
                'title' => __('Decorator', 'decorator-woocommerce-email-customizer'),
            );

            // Add Open Decorator button
            $settings[] = array(
                'id' => 'rp_decorator_open_customizer_button',
                'type' => 'rp_decorator_open_customizer_button',
            );

            // Close section
            $settings[] = array(
                'id' => 'rp_decorator',
                'type' => 'sectionend',
            );

            // Return remaining settings
            return $settings;
        }

        /**
         * Print Open Decorator button
         *
         * @access public
         * @param array $options
         * @return void
         */
        public function print_open_customizer_button($options) {
            ?><tr valign="top">
                <th scope="row" class="titledesc" style="width:230px;">
            <?php _e('Customize WooCommerce Emails', 'decorator-woocommerce-email-customizer'); ?>
                </th>
                <td class="forminp forminp-<?php echo sanitize_title($options['type']); ?>">
                    <a href="<?php echo RP_Decorator_Customizer::get_customizer_url(); ?>">
                        <button type="button" class="button button-secondary" value="<?php _e('Open Decorator', 'decorator-woocommerce-email-customizer'); ?>">
            <?php _e('Open Decorator', 'decorator-woocommerce-email-customizer'); ?>
                        </button>
                    </a>
                    <p class="description"><?php printf(__('Make WooCommerce emails match your brand. <a href="%s">Decorator</a> plugin by <a href="%s">WebToffee</a>.', 'decorator-woocommerce-email-customizer'), 'https://wordpress.org/plugins/decorator-woocommerce-email-customizer', 'https://www.webtoffee.com'); ?></p>
                </td>
            </tr><?php
        }

        /**
         * Get WooCommerce email settings page URL
         *
         * @access public
         * @return string
         */
        public static function get_email_settings_page_url() {
            return admin_url('admin.php?page=wc-settings&tab=email');
        }

        /**
         * Hook in main text areas for customized emails
         *
         * @param  object  $order   the order object.
         * @param  boolean $sent_to_admin if sent to admin.
         * @param  boolean $plain_text if plan text.
         * @param  object  $email the Email object.
         * @return void
         */
        public function wt_alter_order_object_email_body($order, $sent_to_admin, $plain_text, $email) {

            // Get Email ID.
            $key = $email->id;
            $body_text_enable = get_option('rp_decorator_' . $key . '_body_text_enable_switch');
            if ('customer_refunded_order' == $key) {
                $body_text = get_option('rp_decorator_' . $key . '_body_full') ? get_option('rp_decorator_' . $key . '_body_full') : RP_Decorator_Settings::get_default_value('customer_refunded_order_body_full');
            } elseif ('customer_partially_refunded_order' == $key) {
                $body_text = get_option('customer_' . $key . '_body_partial') ? get_option('customer_' . $key . '_body_partial') : RP_Decorator_Settings::get_default_value('customer_refunded_order_body_partial');
            } elseif ('customer_invoice' == $key) {
                if ($order->has_status('pending')) {
                    $body_text = get_option('rp_decorator_' . $key . '_body') ?  get_option('rp_decorator_' . $key . '_body') : RP_Decorator_Settings::get_default_value('customer_invoice_body');
                    $btn_switch = get_option('rp_decorator_' . $key . '_btn_switch');
                    if (true == $btn_switch) {
                        $pay_link = '<p class="btn-container"><a class="wt_template_button" href="' . esc_url($order->get_checkout_payment_url()) . '">' . esc_html__('Pay for this order', 'decorator-woocommerce-email-customizer') . '</a></p>';
                    } else {
                        $pay_link = '<a href="' . esc_url($order->get_checkout_payment_url()) . '">' . esc_html__('Pay for this order', 'decorator-woocommerce-email-customizer') . '</a>';
                    }
                    $body_text = str_replace('{invoice_pay_link}', $pay_link, $body_text);
                } else {
                    $body_text = get_option('rp_decorator_' . $key . '_body_paid') ? get_option('rp_decorator_' . $key . '_body_paid') : RP_Decorator_Settings::get_default_value('customer_invoice_body_paid');
                }
            } elseif ('customer_renewal_invoice' == $key) {
                if ($order->has_status('pending')) {
                    $body_text = get_option('rp_decorator_' . $key . '_body') ? get_option('rp_decorator_' . $key . '_body') : RP_Decorator_Settings::get_default_value('customer_renewal_invoice_body');
                    $btn_switch = get_option('rp_decorator_' . $key . '_btn_switch');
                    if (true == $btn_switch) {
                        $pay_link = '<p class="btn-container"><a class="btn" href="' . esc_url($order->get_checkout_payment_url()) . '">' . esc_html__('Pay Now &raquo;', 'decorator-woocommerce-email-customizer') . '</a></p>';
                    } else {
                        $pay_link = '<a href="' . esc_url($order->get_checkout_payment_url()) . '">' . esc_html__('Pay Now &raquo;', 'decorator-woocommerce-email-customizer') . '</a>';
                    }
                    $body_text = str_replace('{invoice_pay_link}', $pay_link, $body_text);
                } else {
                    $body_text = get_option('rp_decorator_' . $key . '_body_failed');
                    $btn_switch = get_option('rp_decorator_' . $key . '_btn_switch');
                    if (true == $btn_switch) {
                        $pay_link = '<p class="btn-container"><a class="btn" href="' . esc_url($order->get_checkout_payment_url()) . '">' . esc_html__('Pay Now &raquo;', 'decorator-woocommerce-email-customizer') . '</a></p>';
                    } else {
                        $pay_link = '<a href="' . esc_url($order->get_checkout_payment_url()) . '">' . esc_html__('Pay Now &raquo;', 'decorator-woocommerce-email-customizer') . '</a>';
                    }
                    $body_text = str_replace('{invoice_pay_link}', $pay_link, $body_text);
                }
            } elseif (strstr($key, 'wt_smart_coupon')) {
                $body_text = get_option('rp_decorator_' . $key . '_body')?  get_option('rp_decorator_' . $key . '_body') : RP_Decorator_Settings::get_default_value('wt_smart_coupon_body');;
            } else {
                $body_text = get_option('rp_decorator_' . $key . '_body') ?  get_option('rp_decorator_' . $key . '_body') : RP_Decorator_Settings::get_default_value($key.'_body');;               
            }
            
            if(!$body_text_enable){
                    $body_text = '';
            }
            $body_text = str_replace('{site_title}', get_bloginfo('name', 'display'), $body_text);
            $body_text = str_replace('{site_address}', wp_parse_url(home_url(), PHP_URL_HOST), $body_text);
            $body_text = str_replace('{site_url}', wp_parse_url(home_url(), PHP_URL_HOST), $body_text);
            $body_text = str_replace('{blog_url}', get_bloginfo('url'), $body_text);

            if ($order && (($order instanceof WC_Order) || ($order instanceof WC_Subscription))) {
                if (0 === ( $user_id = (int) get_post_meta($order->get_id(), '_customer_user', true) )) {
                    $user_id = 'guest';
                }
                // Check for placeholders.
                $body_text = str_replace('{order_date}', wc_format_datetime($order->get_date_created()), $body_text);
                $body_text = str_replace('{order_number}', $order->get_order_number(), $body_text);
                $body_text = str_replace('{customer_first_name}', $order->get_billing_first_name(), $body_text);
                $body_text = str_replace('{customer_last_name}', $order->get_billing_last_name(), $body_text);
                $body_text = str_replace('{customer_full_name}', $order->get_formatted_billing_full_name(), $body_text);
                $body_text = str_replace('{customer_company}', $order->get_billing_company(), $body_text);
                $body_text = str_replace('{customer_email}', $order->get_billing_email(), $body_text);
                $body_text = str_replace('{customer_username}', self::get_username_from_id($user_id), $body_text);
                   $coupons = get_post_meta($order->get_id(), 'wt_coupons', true);
                    $coupons = maybe_unserialize($coupons);
                    if(!empty($coupons))
                    {
                        foreach($coupons as $coupon_id)
                        {		
                            $coupon_title = get_the_title( $coupon_id );
                            $coupon = new WC_Coupon( $coupon_title );
                            
                        }
                    }
                if(!empty($coupon) && strstr($body_text, '{coupon_code}')){
                    $body_text = str_replace('{coupon_code}', $coupon->get_code(), $body_text);
                }
                
            } else if ($order) {
                if(strstr($body_text, '{coupon_amount}')){
                $body_text = str_replace('{coupon_amount}', $order->get_amount(), $body_text);
                $body_text = str_replace('{coupon_code}', $order->get_amount(), $body_text);
                }
                 if(strstr($body_text, '{coupon_code}')){
                $body_text = str_replace('{coupon_code}', $order->get_code(), $body_text);
                }
            }

            $body_text = apply_filters('wt_woomail_order_body_text', $body_text, $order, $sent_to_admin, $plain_text, $email);

            // auto wrap text.
            $body_text = wpautop($body_text);

            echo wp_kses_post($body_text);
        }

        /**
         * Get username from user id.
         *
         * @param string $id the user id.
         * @access public
         * @return string
         */
        public static function get_username_from_id($id) {
            if (empty($id) || 'guest' === $id) {
                return __('Guest', 'decorator-woocommerce-email-customizer');
            }
            $user = get_user_by('id', $id);
            if (is_object($user)) {
                $username = $user->user_login;
            } else {
                $username = __('Guest', 'decorator-woocommerce-email-customizer');
            }
            return $username;
        }

        /**
         * Hook in main text areas for customized emails.
         *
         * @param object $email the email object.
         * @access public
         * @return void
         */
        public function wt_alter_customer_object_email_body($email) {

            // Get Email ID.
            $key = $email->id;

            $body_text = get_option('rp_decorator_' . $key . '_body') ? get_option('rp_decorator_' . $key . '_body') : RP_Decorator_Settings::get_default_value($key.'_body');;;
            // Check for placeholders.
            $body_text = str_replace('{site_title}', get_bloginfo('name', 'display'), $body_text);
            $body_text = str_replace('{site_address}', wp_parse_url(home_url(), PHP_URL_HOST), $body_text);
            $body_text = str_replace('{site_url}', wp_parse_url(home_url(), PHP_URL_HOST), $body_text);
            if (is_a($email->object, 'WP_User')) {

                $first_name = get_user_meta($email->object->ID, 'billing_first_name', true);
                if (empty($first_name)) {
                    $first_name = get_user_meta($email->object->ID, 'first_name', true);
                    if (empty($first_name)) {
                        // Fall back to user display name.
                        $first_name = $email->object->display_name;
                    }
                }

                $last_name = get_user_meta($email->object->ID, 'billing_last_name', true);
                if (empty($last_name)) {
                    $last_name = get_user_meta($email->object->ID, 'last_name', true);
                    if (empty($last_name)) {
                        // Fall back to user display name.
                        $last_name = $email->object->display_name;
                    }
                }

                $full_name = get_user_meta($email->object->ID, 'formatted_billing_full_name', true);
                if (empty($full_name)) {
                    // Fall back to user display name.
                    $full_name = $email->object->display_name;
                }
                $body_text = str_replace('{customer_first_name}', $first_name, $body_text);
                $body_text = str_replace('{customer_last_name}', $last_name, $body_text);
                $body_text = str_replace('{customer_full_name}', $full_name, $body_text);
                $body_text = str_replace('{customer_username}', $email->user_login, $body_text);
                $body_text = str_replace('{customer_email}', $email->object->user_email, $body_text);
            } elseif (is_a($email->object, 'WC_Product')) {
                $body_text = str_replace('{product_title}', $email->object->get_title(), $body_text);
                $body_text = str_replace('{product_link}', $email->object->get_permalink(), $body_text);
            }

            $body_text = apply_filters('wt_woomail_no_order_body_text', $body_text, $email);

            // auto wrap text.
            $body_text = wpautop($body_text);

            echo wp_kses_post($body_text);
        }

        /**
         * wt_add_lineitem_image.
         *
         * @param array $args contain order details.
         */
        public function wt_add_lineitem_image($args) {
            $line_item_image = RP_Decorator_Customizer::opt('order_items_image');
            $line_item_sku = RP_Decorator_Customizer::opt('order_items_sku');
            if (isset($_POST['customized']) && !empty($_POST['customized'])) {
                $data = json_decode(wp_unslash($_POST['customized']), true);
                if (array_key_exists('rp_decorator[order_items_image]', $data)) {
                    if ($line_item_image != $data['rp_decorator[order_items_image]']) {
                        $line_item_image = $data['rp_decorator[order_items_image]'];
                    }
                }
                if (array_key_exists('rp_decorator[order_items_sku]', $data)) {
                    if ($line_item_sku != $data['rp_decorator[order_items_sku]']) {
                        $line_item_sku = $data['rp_decorator[order_items_sku]'];
                    }
                }
            }
            $args['show_sku'] = FALSE;
            if ('show' === $line_item_sku) {
                $args['show_sku'] = true;
            }
            $line_item_image_size = RP_Decorator_Customizer::opt('order_items_image_size');
            if ('show' === $line_item_image) {
                $args['show_image'] = true;
                switch ($line_item_image_size) {
                    case 'woocommerce_thumbnail':
                        $args['image_size'] = 'woocommerce_thumbnail';
                        break;
                    case '50x50':
                        $args['image_size'] = array(50, 50);
                        break;
                    case '40x40':
                        $args['image_size'] = array(40, 40);
                        break;
                    case '100x100':
                        $args['image_size'] = array(100, 100);
                        break;
                    case '150x150':
                        $args['image_size'] = array(150, 150);
                        break;
                    default:
                        $args['image_size'] = array(100, 50);
                        break;
                }
            }
            return $args;
        }

    }

    RP_Decorator_WC::get_instance();
}
