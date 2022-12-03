<?php

/**
 * Plugin Name: Decorator - WooCommerce Email Customizer
 * Plugin URI: 
 * Description: Use native WordPress Customizer to make WooCommerce emails match your brand
 * Version: 1.2.2
 * Author: WebToffee
 * Author URI: https://www.webtoffee.com
 * Requires at least: 4.4
 * Tested up to: 6.0
 * WC tested up to: 6.5.1
 * Text Domain: decorator-woocommerce-email-customizer
 * Domain Path: /languages
 *
 * Copyright 2020 WebToffee (email: info@webtoffee.com)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package Decorator
 * @category Core
 * @author WebToffee
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define Constants
define('RP_DECORATOR_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('RP_DECORATOR_PLUGIN_URL', plugins_url(basename(plugin_dir_path(__FILE__)), basename(__FILE__)));
define( 'WT_WOOMAIL_PATH', realpath( plugin_dir_path( __FILE__ ) ) . DIRECTORY_SEPARATOR );
define('RP_DECORATOR_VERSION', '1.2.2');
define('RP_DECORATOR_SUPPORT_PHP', '5.3');
define('RP_DECORATOR_SUPPORT_WP', '4.4');
define('RP_DECORATOR_SUPPORT_WC', '2.4');

/**
 * Check if WooCommerce is active
 */
if ( !in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) && !array_key_exists( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_site_option( 'active_sitewide_plugins', array() ) ) ) ) { // deactive if woocommerce in not active
        require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        add_action('admin_notices', 'wt_disabled_notice');    
        return;
 }
 function wt_disabled_notice(){
    echo '<div class="error"><p>' . sprintf(__('<strong>Decorator</strong> requires WooCommerce to be active. You can download WooCommerce %s.', 'decorator-woocommerce-email-customizer'), '<a href="https://wordpress.org/plugins/woocommerce">' . __('here', 'decorator-woocommerce-email-customizer') . '</a>') . '</p></div>';
}

if (!class_exists('RP_Decorator')) {

/**
 * Main plugin class
 *
 * @package Decorator
 * @author WebToffee
 */
class RP_Decorator
{
    // Properties
    private static $admin_capability = null;

    // Singleton instance
    private static $instance = false;

    /**
     * Singleton control
     */
    public static function get_instance()
    {
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
    public function __construct()
    {
        // Use our templates instead of woocommerce.
        add_filter( 'woocommerce_locate_template', array( $this, 'wt_localize_templates' ), 9999, 3 );
        // Load translation
        load_textdomain('decorator-woocommerce-email-customizer', WP_LANG_DIR . '/decorator-woocommerce-email-customizer/rp_decorator-' . apply_filters('plugin_locale', get_locale(), 'decorator-woocommerce-email-customizer') . '.mo');
        load_plugin_textdomain('decorator-woocommerce-email-customizer', false, dirname(plugin_basename(__FILE__)) . '/languages/');

        // Execute other code when all plugins are loaded
        add_action('plugins_loaded', array($this, 'on_plugins_loaded'), 1);
        add_action('init', array($this, 'wt_change_style_storage_structure'), 1);
    }

    /**
     * Code executed when all plugins are loaded
     *
     * @access public
     * @return void
     */
    public function on_plugins_loaded()
    {
        // Check environment
        if (!RP_Decorator::check_environment()) {
            return;
        }

        // Includes
        foreach (glob(RP_DECORATOR_PLUGIN_PATH . 'includes/classes/*.class.php') as $filename) {
            include $filename;
        }
        include RP_DECORATOR_PLUGIN_PATH . 'includes/rp-decorator-uninstall-feedback.php';
        require_once RP_DECORATOR_PLUGIN_PATH . 'includes/classes/components/wt-custom-checkbox-design.php'; 
        require_once RP_DECORATOR_PLUGIN_PATH . 'includes/classes/components/wt-custom-social-icon-repeater.php'; 
        require_once RP_DECORATOR_PLUGIN_PATH . 'includes/classes/components/wt-custom-range-design.php'; 
        require_once RP_DECORATOR_PLUGIN_PATH . 'includes/classes/components/wt-custom-div-shortcodes-design.php'; 
        require_once RP_DECORATOR_PLUGIN_PATH . 'includes/classes/components/wt-custom-label-design.php'; 
        // Plugins page links
        add_filter('plugin_action_links_' . plugin_basename(__FILE__), array($this, 'plugins_page_links'));
    }

    /**
     * Check if environment meets requirements
     *
     * @access public
     * @return bool
     */
    public static function check_environment()
    {
        $is_ok = true;

        // Check PHP version
        if (!RP_Decorator::php_version_gte(RP_DECORATOR_SUPPORT_PHP)) {
            add_action('admin_notices', array('RP_Decorator', 'php_version_notice'));
        }

        // Check WordPress version
        if (!RP_Decorator::wp_version_gte(RP_DECORATOR_SUPPORT_WP)) {
            add_action('admin_notices', array('RP_Decorator', 'wp_version_notice'));
            $is_ok = false;
        }

        // Check if WooCommerce is enabled
        if (!class_exists('WooCommerce')) {
            add_action('admin_notices', array('RP_Decorator', 'wc_disabled_notice'));
            $is_ok = false;
        }
        else if (!RP_Decorator::wc_version_gte(RP_DECORATOR_SUPPORT_WC)) {
            add_action('admin_notices', array('RP_Decorator', 'wc_version_notice'));
            $is_ok = false;
        }

        return $is_ok;
    }

    /**
     * Check PHP version
     *
     * @access public
     * @param string $version
     * @return bool
     */
    public static function php_version_gte($version)
    {
        return version_compare(PHP_VERSION, $version, '>=');
    }

    /**
     * Check WordPress version
     *
     * @access public
     * @param string $version
     * @return bool
     */
    public static function wp_version_gte($version)
    {
        $wp_version = get_bloginfo('version');

        // Treat release candidate strings
        $wp_version = preg_replace('/-RC.+/i', '', $wp_version);

        if ($wp_version) {
            return version_compare($wp_version, $version, '>=');
        }

        return false;
    }

    /**
     * Check WooCommerce version
     *
     * @access public
     * @param string $version
     * @return bool
     */
    public static function wc_version_gte($version)
    {
        if (defined('WC_VERSION') && WC_VERSION) {
            return version_compare(WC_VERSION, $version, '>=');
        }
        else if (defined('WOOCOMMERCE_VERSION') && WOOCOMMERCE_VERSION) {
            return version_compare(WOOCOMMERCE_VERSION, $version, '>=');
        }
        else {
            return false;
        }
    }

    /**
     * Display PHP version notice
     *
     * @access public
     * @return void
     */
    public static function php_version_notice()
    {
        echo '<div class="error"><p>' . sprintf(__('<strong>Decorator</strong> requires PHP %s or later. Please update PHP on your server to use this plugin.', 'decorator-woocommerce-email-customizer'), RP_DECORATOR_SUPPORT_PHP) . '</p></div>';
    }

    /**
     * Display WP version notice
     *
     * @access public
     * @return void
     */
    public static function wp_version_notice()
    {
        echo '<div class="error"><p>' . sprintf(__('<strong>Decorator</strong> requires WordPress version %s or later. Please update WordPress to use this plugin.', 'decorator-woocommerce-email-customizer'), RP_DECORATOR_SUPPORT_WP) . '</p></div>';
    }

    /**
     * Display WC disabled notice
     *
     * @access public
     * @return void
     */
    public static function wc_disabled_notice()
    {
        echo '<div class="error"><p>' . sprintf(__('<strong>Decorator</strong> requires WooCommerce to be active. You can download WooCommerce %s.', 'decorator-woocommerce-email-customizer'), '<a href="https://wordpress.org/plugins/woocommerce">' . __('here', 'decorator-woocommerce-email-customizer') . '</a>') . '</p></div>';
    }

    /**
     * Display WC version notice
     *
     * @access public
     * @return void
     */
    public static function wc_version_notice()
    {
        echo '<div class="error"><p>' . sprintf(__('<strong>Decorator</strong> requires WooCommerce version %s or later. Please update WooCommerce to use this plugin.', 'decorator-woocommerce-email-customizer'), RP_DECORATOR_SUPPORT_WC) . '</p></div>';
    }

    /**
     * Plugins page links
     *
     * @access public
     * @param array $links
     * @return array
     */
    public function plugins_page_links($links)
    {

	    $plugin_links = array( '<a href="' . RP_Decorator_Customizer::get_customizer_url() . '"  target="_blank">' . __( 'Customise Email', 'decorator-woocommerce-email-customizer' ) . '</a>',
                '<a href="https://www.webtoffee.com/decorator-woocommerce-email-customizer-plugin-user-guide/"  target="_blank">' . __( 'Docs', 'decorator-woocommerce-email-customizer' ) . '</a>',
                '<a href="https://wordpress.org/support/plugin/decorator-woocommerce-email-customizer/"  target="_blank">' . __( 'Support', 'decorator-woocommerce-email-customizer' ) . '</a>' );
        
        if (array_key_exists('deactivate', $links)) {
            $links['deactivate'] = str_replace('<a', '<a class="decorator-deactivate-link"', $links['deactivate']);
        }
	     return array_merge($plugin_links, $links);
    }

    /**
     * Check if current user has administrative capability
     *
     * @access public
     * @return bool
     */
    public static function is_admin()
    {
        return current_user_can(RP_Decorator::get_admin_capability());
    }

    /**
     * Get admininistrative capability
     *
     * @access public
     * @return string
     */
    public static function get_admin_capability()
    {
        // Get capability
        if (self::$admin_capability === null) {
            self::$admin_capability = apply_filters('rp_decorator_capability', 'manage_woocommerce');
        }

        // Return capability
        return self::$admin_capability;
    }

    /**
     * Check if current request is own Customizer request
     *
     * @access public
     * @return bool
     */
    public static function is_own_customizer_request()
    {
        return isset($_REQUEST['rp-decorator-customize']) && $_REQUEST['rp-decorator-customize'] === '1';
    }

    /**
     * Check if current request is preview request
     *
     * @access public
     * @return bool
     */
    public static function is_own_preview_request()
    {
        return isset($_REQUEST['rp-decorator-preview']) && $_REQUEST['rp-decorator-preview'] === '1';
    }
    
    
    /**
     * Filter in custom email templates with priority to child themes
     *
     * @param string $template the email template file.
     * @param string $template_name name of email template.
     * @param string $template_path path to email template.
     * @access public
     * @return string
     */
    public function wt_localize_templates( $template, $template_name, $template_path ) {
            // Make sure we are working with an email template.
            if ( ! in_array( 'emails', explode( '/', $template_name ) ) && ! in_array( 'email', explode( '/', $template_name ) ) ) {
                    return $template;
            }
            
            if( in_array( 'email', explode( '/', $template_name ) )){
                $template_name = str_replace('email', 'emails', $template_name);
            }
            // clone template.
            $_template = $template;

            // Get the woocommerce template path if empty.
            if ( ! $template_path ) {
                    global $woocommerce;
                    $template_path = $woocommerce->template_url;
            }
                wp_enqueue_style( 'font-awesome', RP_DECORATOR_PLUGIN_URL . '/assets/css/customizer-repeater/font-awesome.min.css', array(), RP_DECORATOR_VERSION );

		wp_enqueue_script( 'customizer-repeater-fontawesome-iconpicker', RP_DECORATOR_PLUGIN_URL . '/assets/js/customizer-repeater/fontawesome-iconpicker.min.js', array( 'jquery' ), RP_DECORATOR_VERSION, true );

		wp_enqueue_style( 'customizer-repeater-fontawesome-iconpicker-script', RP_DECORATOR_PLUGIN_URL . '/assets/css/customizer-repeater/fontawesome-iconpicker.min.css', array(), RP_DECORATOR_VERSION );
	
            // Get our template path.
            $plugin_path = WT_WOOMAIL_PATH . 'includes/wt-woo-templates/';

            // Look within passed path within the theme - this is priority.
            $template = locate_template( array( $template_path . $template_name, $template_name ) );

            // If theme isn't trying to override get the template from this plugin, if it exists.
            if ( ! $template && file_exists( $plugin_path . $template_name ) ) {
                    $template = $plugin_path . $template_name;
            }

            // else if we still don't have a template use default.
            if ( ! $template ) {
                    $template = $_template;
            }
            // Return template.
            return $template;

    }
    
     /**
     * wt_change_style_storage_structure
     *
     * @access public
     *
     */
     public function wt_change_style_storage_structure() {

            if ( version_compare( get_option( 'wt_decorator_current_version', '1.0.0' ), RP_DECORATOR_VERSION, '<' ) ) {
                $wc_emails = WC_Emails::instance();
                $emails = $wc_emails->get_emails();
                $wt_custom_draft_data = array();
                $wt_custom_data = array();
                global $wpdb;
                $stored = (array) get_option('rp_decorator', array());
                if (isset($stored) && !empty($stored)) {
                    foreach ($emails as $email_key => $email_value) {
                        if (isset($email_value->id) && !empty($email_value->id)) {
                            $wt_custom_data[$email_value->id] = $stored;
                        }
                    }
                    update_option('wt_decorator_custom_styles', $wt_custom_data);
                    update_option('rp_decorator', array());
                }else{
                    update_option('wt_decorator_custom_styles', '');
                }
                
                $drafted_data = $wpdb->get_results('SELECT MAX(id) FROM ' . $wpdb->prefix . "posts WHERE post_status = 'draft' and post_type = 'customize_changeset' and post_content LIKE '%rp_decorator%' ", ARRAY_A);
                if (isset($drafted_data[0]['MAX(id)']) && !empty($drafted_data[0]['MAX(id)'])) {
                    $data = get_post_field('post_content', $drafted_data[0]['MAX(id)']);
                    if (isset($data) && !empty($data)) {
                        $data = json_decode($data, TRUE);

                        foreach ($data as $data_key => $data_value) {
                            if (preg_match('/rp_decorator\[(.*?)\]/', $data_key, $match)) {
                                $key = $match[1];
                                $custom_data[$key] = $data_value['value'];
                            }
                        }
                        if(isset($stored) && !empty($stored)){
                            foreach ($stored as $s_key => $s_value) {
                                if(!array_key_exists($s_key, $custom_data)){
                                    $custom_data[$s_key] = $s_value;
                                }
                            }
                        }
                        foreach ($emails as $email_key => $email_value) {
                            if (isset($email_value->id) && !empty($email_value->id)) {
                                $wt_custom_draft_data[$email_value->id] = $custom_data;
                            }
                        }
                        update_option('wt_decorator_custom_styles_in_draft', $wt_custom_draft_data);
                        wp_delete_post($drafted_data[0]['MAX(id)']);
                    }
                }
                
                update_option('wt_decorator_current_version', RP_DECORATOR_VERSION);
            }
        }

    }

RP_Decorator::get_instance();

}
