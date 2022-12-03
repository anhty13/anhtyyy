<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Customizer Settings
 *
 * @class RP_Decorator_Settings
 * @package Decorator
 * @author WebToffee
 */
if (!class_exists('RP_Decorator_Settings')) {

    class RP_Decorator_Settings {

        private static $panels = null;
        private static $sections = null;
        private static $settings = null;
        private static $default_values = null;
        private static $order_ids = null;
        private static $custom_settings_for_textedit = null;
        // Font family mapping
        public static $font_family_mapping = array(
            'arial' => 'Arial, Helvetica, sans-serif',
            'arial_black' => '"Arial Black", Gadget, sans-serif',
            'courier' => '"Courier New", Courier, monospace',
            'georgia' => 'Georgia, serif',
            'helvetica' => '"Helvetica Neue", Helvetica, Roboto, Arial, sans-serif',
            'impact' => 'Impact, Charcoal, sans-serif',
            'lucida' => '"Lucida Sans Unicode", "Lucida Grande", sans-serif',
            'palatino' => '"Palatino Linotype", "Book Antiqua", Palatino, serif',
            'tahoma' => 'Tahoma, Geneva, sans-serif',
            'times' => '"Times New Roman", Times, serif',
            'verdana' => 'Verdana, Geneva, sans-serif',
        );
        
        /**
         * Get panels
         *
         * @access public
         * @return array
         */
        public static function get_panels() {
            // Define panels
            if (self::$panels === null) {
                self::$panels = array(
                    // Header
                    'header' => array(
                        'title' => __('Header', 'decorator-woocommerce-email-customizer'),
                        'priority' => 20,
                    ),
                    // Footer
                    'footer' => array(
                        'title' => __('Footer', 'decorator-woocommerce-email-customizer'),
                        'priority' => 30,
                    ),
                    // Content
                    'content' => array(
                        'title' => __('Content', 'decorator-woocommerce-email-customizer'),
                        'priority' => 40,
                    ),
                    // Other
                    'other' => array(
                        'title' => __('Other', 'decorator-woocommerce-email-customizer'),
                        'priority' => 50,
                    ),
                );
            }

            // Return panels
            return self::$panels;
        }

        /**
         * Get sections
         *
         * @access public
         * @return array
         */
        public static function get_sections() {
            // Define sections
            if (self::$sections === null) {
                self::$sections = array(
                    // Text Editor.
                    'text_editor' => array(
                        'title' => __('Email text', 'decorator-woocommerce-email-customizer'),
                        'priority' => 5,
                    ),
                    // Container
                    'container' => array(
                        'title' => __('Container', 'decorator-woocommerce-email-customizer'),
                        'priority' => 10,
                    ),
                    // Header Style
                    'header_style' => array(
                        'title' => __('Header style', 'decorator-woocommerce-email-customizer'),
                        'panel' => 'header',
                        'priority' => 20,
                    ),
                    // Header Image
                    'header_image' => array(
                        'title' => __('Header image', 'decorator-woocommerce-email-customizer'),
                        'panel' => 'header',
                        'priority' => 20,
                    ),
                    // Heading
                    'heading' => array(
                        'title' => __('Heading and subtitle', 'decorator-woocommerce-email-customizer'),
                        'panel' => 'header',
                        'priority' => 30,
                    ),
                    // Footer Style
                    'footer_style' => array(
                        'title' => __('Footer style', 'decorator-woocommerce-email-customizer'),
                        'panel' => 'footer',
                        'priority' => 40,
                    ),
                    // Footer Content
                    'footer_content' => array(
                        'title' => __('Footer content', 'decorator-woocommerce-email-customizer'),
                        'panel' => 'footer',
                        'priority' => 50,
                    ),
                    // Footer social.
                    'social_links' => array(
                        'title' => __('Social links', 'decorator-woocommerce-email-customizer'),
                        'panel' => 'footer',
                        'priority' => 50,
                    ),
                    // Content Container
                    'content_container' => array(
                        'title' => __('Content container', 'decorator-woocommerce-email-customizer'),
                        'panel' => 'content',
                        'priority' => 10,
                    ),
                    // Text Style
                    'text_style' => array(
                        'title' => __('Text style', 'decorator-woocommerce-email-customizer'),
                        'panel' => 'content',
                        'priority' => 10,
                    ),
                    // Heading 1
                    'h1' => array(
                        'title' => __('Heading 1', 'decorator-woocommerce-email-customizer'),
                        'panel' => 'other',
                        'priority' => 20,
                    ),
                    // Heading 2
                    'h2' => array(
                        'title' => __('Heading 2', 'decorator-woocommerce-email-customizer'),
                        'panel' => 'other',
                        'priority' => 30,
                    ),
                    // Heading 3
                    'h3' => array(
                        'title' => __('Heading 3', 'decorator-woocommerce-email-customizer'),
                        'panel' => 'other',
                        'priority' => 40,
                    ),
                    // Heading 4
                    'h4' => array(
                        'title' => __('Heading 4', 'decorator-woocommerce-email-customizer'),
                        'panel' => 'other',
                        'priority' => 50,
                    ),
                    // Heading 5
                    'h5' => array(
                        'title' => __('Heading 5', 'decorator-woocommerce-email-customizer'),
                        'panel' => 'other',
                        'priority' => 60,
                    ),
                    // Heading 6
                    'h6' => array(
                        'title' => __('Heading 6', 'decorator-woocommerce-email-customizer'),
                        'panel' => 'other',
                        'priority' => 70,
                    ),
                    // Custom Styles
                    'custom_styles' => array(
                        'title' => __('Custom styles', 'decorator-woocommerce-email-customizer'),
                        'panel' => 'other',
                        'priority' => 100,
                    ),
                    // Items Table
                    'items_table' => array(
                        'title' => __('Order items', 'decorator-woocommerce-email-customizer'),
                        'panel' => 'content',
                        'priority' => 10,
                    ),
                    // Items Table
                    'address_table' => array(
                        'title' => __('Order addresses', 'decorator-woocommerce-email-customizer'),
                        'panel' => 'content',
                        'priority' => 10,
                    ),
                    'button_styles' => array(
                        'title' => __('Button styles', 'decorator-woocommerce-email-customizer'),
                        'panel' => 'content',
                        'priority' => 10,
                    ),
                );
            }

            // Return sections
            return self::$sections;
        }

        /**
         * Get settings
         *
         * @access public
         * @return array
         */
        public static function get_settings() {
            // Define settings
            if (self::$settings === null) {
                self::$settings = array(
                    // Preview Order Id.
                    'preview_order_id' => array(
                        'section' => 'container',
                        'type' => 'select',
                        'priority' => 1,
                        'choices' => self::get_order_ids(),
                        'default' => self::get_default_value('preview_order_id'),
                        'transport' => 'refresh',
                    ),
                    // Email Type.
                    'email_type' => array(
                        'section' => 'container',
                        'type' => 'select',
                        'priority' => 2,
                        'choices' => RP_Decorator_Preview::get_email_types(),
                        'default' => self::get_default_value('email_type'),
                        //'transport' => 'refresh',
                    ),
                    // Background color
                    'background_color' => array(
                        'title' => __('Background color', 'decorator-woocommerce-email-customizer'),
                        'section' => 'container',
                        'control_type' => 'color',
                        'default' => RP_Decorator_Settings::get_default_value('background_color'),
                        'live_method' => 'css',
                        'selectors' => array(
                            'body' => array('background-color'),
                            '#wrapper' => array('background-color'),
                        ),
                    ),
                    // Email padding
                    'email_padding' => array(
                        'title' => __('Padding', 'decorator-woocommerce-email-customizer'),
                        'control_type' => 'range_value',
                        'section' => 'container',
                        'default' => RP_Decorator_Settings::get_default_value('email_padding'),
                        'live_method' => 'css',
                        'selectors' => array(
                            '#wrapper' => array('padding'),
                        ),
                        'input_attrs' => array(
                            'step' => 1,
                            'min' => 0,
                            'max' => 250,
                            'suffix' => 'px',
                        ),
                    ),
                    // Email width
                    'email_width' => array(
                        'title' => __('Container width', 'decorator-woocommerce-email-customizer'),
                        'control_type' => 'range_value',
                        'section' => 'container',
                        'default' => RP_Decorator_Settings::get_default_value('email_width'),
                        'live_method' => 'css',
                        'selectors' => array(
                            '#template_container' => array('width'),
                            '#template_header' => array('width'),
                            '#template_body' => array('width'),
                            '#template_footer' => array('width'),
                            '#wt_wrapper_img_table' => array('width'),
                            '#template_header_image' => array('width'),
                            'body #wt_wrapper_table' => array('width'),
                        ),
                        'input_attrs' => array(
                            'step' => 1,
                            'min' => 350,
                            'max' => 1500,
                            'suffix' => 'px',
                        ),
                    ),
                    // Email background color
                    'email_background_color' => array(
                        'title' => __('Background color', 'decorator-woocommerce-email-customizer'),
                        'section' => 'content_container',
                        'control_type' => 'color',
                        'default' => RP_Decorator_Settings::get_default_value('email_background_color'),
                        'live_method' => 'css',
                        'selectors' => array(
                            '#template_container' => array('background-color'),
                            '#body_content' => array('background-color'),
                        ),
                    ),
                    // Content padding
                    'content_padding_left' => array(
                        'title' => __('Left padding', 'decorator-woocommerce-email-customizer'),
                        'control_type' => 'range_value',
                        'section' => 'content_container',
                        'default' => RP_Decorator_Settings::get_default_value('content_padding_left'),
                        'live_method' => 'css',
                        'selectors' => array(
                            '#body_content > table > tbody > tr > td' => array('padding-left'),
                            '#body_content > table > tr > td' => array('padding-left'),
                        ),
                        'input_attrs' => array(
                            'step' => 1,
                            'min' => 0,
                            'max' => 150,
                            'suffix' => 'px',
                        ),
                    ),
                    // Content padding
                    'content_padding_right' => array(
                        'title' => __('Right padding', 'decorator-woocommerce-email-customizer'),
                        'control_type' => 'range_value',
                        'section' => 'content_container',
                        'default' => RP_Decorator_Settings::get_default_value('content_padding_right'),
                        'live_method' => 'css',
                        'selectors' => array(
                            '#body_content > table > tbody > tr > td' => array('padding-right'),
                            '#body_content > table > tr > td' => array('padding-right'),
                        ),
                        'input_attrs' => array(
                            'step' => 1,
                            'min' => 0,
                            'max' => 150,
                            'suffix' => 'px',
                        ),
                    ),
                    // Content padding
                    'content_padding_top' => array(
                        'title' => __('Top padding', 'decorator-woocommerce-email-customizer'),
                        'control_type' => 'range_value',
                        'section' => 'content_container',
                        'default' => RP_Decorator_Settings::get_default_value('content_padding_top'),
                        'live_method' => 'css',
                        'selectors' => array(
                            '#body_content > table > tbody > tr > td' => array('padding-top'),
                            '#body_content > table > tr > td' => array('padding-top'),
                        ),
                        'input_attrs' => array(
                            'step' => 1,
                            'min' => 0,
                            'max' => 150,
                            'suffix' => 'px',
                        ),
                    ),
                    // Content padding
                    'content_padding_bottom' => array(
                        'title' => __('Bottom padding', 'decorator-woocommerce-email-customizer'),
                        'control_type' => 'range_value',
                        'section' => 'content_container',
                        'default' => RP_Decorator_Settings::get_default_value('content_padding_bottom'),
                        'live_method' => 'css',
                        'selectors' => array(
                            '#body_content > table > tbody > tr > td' => array('padding-bottom'),
                            '#body_content > table > tr > td' => array('padding-bottom'),
                        ),
                        'input_attrs' => array(
                            'step' => 1,
                            'min' => 0,
                            'max' => 150,
                            'suffix' => 'px',
                        ),
                    ),
                    // Font size
                    'font_size' => array(
                        'title' => __('Font size', 'decorator-woocommerce-email-customizer'),
                        'control_type' => 'range_value',
                        'section' => 'text_style',
                        'default' => RP_Decorator_Settings::get_default_value('font_size'),
                        'live_method' => 'css',
                        'selectors' => array(
                            '#body_content_inner' => array('font-size'),
                            'img' => array('font-size'),
                        ),
                        'input_attrs' => array(
                            'step' => 1,
                            'min' => 8,
                            'max' => 30,
                            'suffix' => 'px',
                        ),
                    ),
                    // Font family
                    'font_family' => array(
                        'title' => __('Font family', 'decorator-woocommerce-email-customizer'),
                        'section' => 'text_style',
                        'default' => RP_Decorator_Settings::get_default_value('font_family'),
                        'live_method' => 'css',
                        'type' => 'select',
                        'choices' => RP_Decorator_Settings::get_font_families(),
                        'selectors' => array(
                            '#body_content_inner' => array('font-family'),
                            '.td' => array('font-family'),
                            '.text' => array('font-family'),
                            //'.address' => array('font-family'),
                            '#body_content_inner h2' => array('font-family'),
                        ),
                    ),
                    // Text color
                    'text_color' => array(
                        'title' => __('Text color', 'decorator-woocommerce-email-customizer'),
                        'section' => 'text_style',
                        'control_type' => 'color',
                        'default' => RP_Decorator_Settings::get_default_value('text_color'),
                        'live_method' => 'css',
                        'selectors' => array(
                            '#body_content_inner' => array('color'),
                            '.td' => array('color'),
                            '.text' => array('color'),
                            //'.address' => array('color'),
//                            '#body_content_inner h2' => array('color'),
                        ),
                    ),
                    // Header Text align
                    'header_show' => array(
                        'title' => __('Header visibility', 'decorator-woocommerce-email-customizer'),
                        'section' => 'header_style',
                        'default' => self::get_default_value('header_show'),
                        'live_method' => 'css',
                        'control_type' => 'toggleswitch',
                        'selectors' => array(
                            '#wt_header_wrapper' => array('height','width','overflow'),
                        ),
                    ),
                    // Link color
                    'link_color' => array(
                        'title' => __('Link color', 'decorator-woocommerce-email-customizer'),
                        'section' => 'text_style',
                        'control_type' => 'color',
                        'default' => RP_Decorator_Settings::get_default_value('link_color'),
                        'live_method' => 'css',
                        'selectors' => array(
                            '#body_content_inner a' => array('color'),
                            '#body_content_inner .link' => array('color'),
                        ),
                    ),
                    // Header background color
                    'header_background_color' => array(
                        'title' => __('Background color', 'decorator-woocommerce-email-customizer'),
                        'section' => 'header_style',
                        'control_type' => 'color',
                        'default' => RP_Decorator_Settings::get_default_value('header_background_color'),
                        'live_method' => 'css',
                        'selectors' => array(
                            '#template_header' => array('background-color'),
                        ),
                    ),
                    // Header Text align
                    'header_text_align' => array(
                        'title' => __('Text align', 'decorator-woocommerce-email-customizer'),
                        'section' => 'header_style',
                        'default' => RP_Decorator_Settings::get_default_value('header_text_align'),
                        'live_method' => 'css',
                        'type' => 'select',
                        'choices' => RP_Decorator_Settings::get_text_aligns(),
                        'selectors' => array(
                            '#header_wrapper > h1' => array('text-align'),
                        ),
                    ),

                    // Header Padding top/bottom
                    'header_padding_top_bottom' => array(
                        'title' => __('Padding top/bottom', 'decorator-woocommerce-email-customizer'),
                        'control_type' => 'range_value',
                        'section' => 'header_style',
                        'default' => RP_Decorator_Settings::get_default_value('header_padding_top_bottom'),
                        'live_method' => 'css',
                        'selectors' => array(
                            '#header_wrapper' => array('padding-top', 'padding-bottom'),
                        ),
                        'input_attrs' => array(
                            'step' => 1,
                            'min' => 0,
                            'max' => 150,
                            'suffix' => 'px',
                        ),
                    ),
                    // Header Padding left/right
                    'header_padding_left_right' => array(
                        'title' => __('Padding left/right', 'decorator-woocommerce-email-customizer'),
                        'control_type' => 'range_value',
                        'section' => 'header_style',
                        'default' => RP_Decorator_Settings::get_default_value('header_padding_left_right'),
                        'live_method' => 'css',
                        'selectors' => array(
                            '#header_wrapper' => array('padding-left', 'padding-right'),
                        ),
                        'input_attrs' => array(
                            'step' => 1,
                            'min' => 0,
                            'max' => 150,
                            'suffix' => 'px',
                        ),
                    ),
                    // Header image
                    'header_image' => array(
                        'title' => __('Header image', 'decorator-woocommerce-email-customizer'),
                        'control_type' => 'image',
                        'section' => 'header_image',
                        'default' => RP_Decorator_Settings::get_default_value('header_image'),
                        'original' => '',
                        'live_method' => 'replace',
                        'selectors' => array(
                            '#template_header_image'
                        ),
                    ),
                    // Border radius
                    'border_radius' => array(
                        'title' => __('Border radius', 'decorator-woocommerce-email-customizer'),
                        'control_type' => 'range_value',
                        'section' => 'container',
                        'default' => RP_Decorator_Settings::get_default_value('border_radius'),
                        'live_method' => 'css',
                        'selectors' => array(
                            'body #wt_wrapper_table' => array('border-radius'),
                            '#template_container' => array('border-top-left-radius','border-top-right-radius'),
                            'body #template_header' => array('border-top-left-radius','border-top-right-radius'),
                            'body #template_footer' => array('border-bottom-left-radius','border-bottom-right-radius')
                        ),
                        'input_attrs' => array(
                            'step' => 1,
                            'min' => 0,
                            'max' => 50,
                            'suffix' => 'px',
                        ),
                    ),
                    // Shadow
                    'shadow' => array(
                        'title' => __('Shadow', 'decorator-woocommerce-email-customizer'),
                        'control_type' => 'range_value',
                        'section' => 'container',
                        'default' => RP_Decorator_Settings::get_default_value('shadow'),
                        'live_method' => 'css',
                        'selectors' => array(
                            'body #wt_wrapper_table' => array('box-shadow'),
                        ),
                        'input_attrs' => array(
                            'step' => 1,
                            'min' => 0,
                            'max' => 20,
                            'suffix' => 'px',
                        ),
                    ),
                    // Border Color.
                    'border_color' => array(
                        'title' => __('Border color', 'decorator-woocommerce-email-customizer'),
                        'section' => 'container',
                        'control_type' => 'color',
                        'default' => self::get_default_value('border_color'),
                        'live_method' => 'css',
                        'selectors' => array(
                            'body #wt_wrapper_table' => array('border-color'),
                        ),
                    ),
                      // Border Color.
                     // Items table Border width
                    'container_border_width' => array(
                        'title' => __('Container border width', 'decorator-woocommerce-email-customizer'),
                        'control_type' => 'range_value',
                        'section' => 'container',
                        'default' => RP_Decorator_Settings::get_default_value('container_border_width'),
                        'live_method' => 'css',
                        'selectors' => array(
                            'body #wt_wrapper_table' => array('border-width'),
                        ),
                        'input_attrs' => array(
                            'step' => 1,
                            'min' => 0,
                            'max' => 10,
                            'suffix' => 'px',
                        ),
                    ),
                      'heading_settings' => array(
                        'title' => __('Heading settings', 'decorator-woocommerce-email-customizer'),
                        'section' => 'heading',
                        'control_type' => 'labels',
                    ),
                    // Heading Font size
                    'heading_font_size' => array(
                        'title' => __('Font size', 'decorator-woocommerce-email-customizer'),
                        'control_type' => 'range_value',
                        'section' => 'heading',
                        'default' => RP_Decorator_Settings::get_default_value('heading_font_size'),
                        'live_method' => 'css',
                        'selectors' => array(
                            '#template_header h1' => array('font-size'),
                        ),
                        'input_attrs' => array(
                            'step' => 1,
                            'min' => 10,
                            'max' => 75,
                            'suffix' => 'px',
                        ),
                    ),
                    // Heading Font family
                    'heading_font_family' => array(
                        'title' => __('Font family', 'decorator-woocommerce-email-customizer'),
                        'section' => 'heading',
                        'default' => RP_Decorator_Settings::get_default_value('heading_font_family'),
                        'live_method' => 'css',
                        'type' => 'select',
                        'choices' => RP_Decorator_Settings::get_font_families(),
                        'selectors' => array(
                            '#template_header h1' => array('font-family'),
                        ),
                    ),
                    // Heading Font weight
                    'heading_font_weight' => array(
                        'title' => __('Font weight', 'decorator-woocommerce-email-customizer'),
                        'control_type' => 'range_value',
                        'section' => 'heading',
                        'default' => RP_Decorator_Settings::get_default_value('heading_font_weight'),
                        'live_method' => 'css',
                        'selectors' => array(
                            '#template_header' => array('font-weight'),
                            '#template_header h1' => array('font-weight'),
                        ),
                        'input_attrs' => array(
                            'step' => 100,
                            'min' => 100,
                            'max' => 900,
                        ),
                    ),
                    // Heading Color
                    'heading_color' => array(
                        'title' => __('Text color', 'decorator-woocommerce-email-customizer'),
                        'section' => 'heading',
                        'control_type' => 'color',
                        'default' => RP_Decorator_Settings::get_default_value('heading_color'),
                        'live_method' => 'css',
                        'selectors' => array(
                            '#template_header' => array('color'),
                            '#template_header h1' => array('color'),
                        ),
                    ),
                    'heading_font_style' => array(
                        'title' => __('Font style', 'decorator-woocommerce-email-customizer'),
                        'section' => 'heading',
                        'default' => self::get_default_value('heading_font_style'),
                        'live_method' => 'css',
                        'type' => 'select',
                        'choices' => array(
                            'normal' => __('Normal', 'decorator-woocommerce-email-customizer'),
                            'italic' => __('Italic', 'decorator-woocommerce-email-customizer'),
                        ),
                        'selectors' => array(
                            '#template_header h1' => array('font-style'),
                        ),
                    ),
                    'heading_text_decoration' => array(
                        'title' => __('Text decoration', 'decorator-woocommerce-email-customizer'),
                        'section' => 'heading',
                        'default' => self::get_default_value('heading_text_decoration'),
                        'live_method' => 'css',
                        'type' => 'select',
                        'choices' => array(
                            'normal' => __('Normal', 'decorator-woocommerce-email-customizer'),
                            'underline' => __('Underline', 'decorator-woocommerce-email-customizer'),
                            'overline' => __('Overline', 'decorator-woocommerce-email-customizer'),
                            'line-through' => __('Line-through', 'decorator-woocommerce-email-customizer'),
                            'underline overline' => __('Underline overline', 'decorator-woocommerce-email-customizer'),
                        ),
                        'selectors' => array(
                            '#template_header h1' => array('text-decoration'),
                        ),
                    ),
                    'heading_line_height' => array(
                        'title' => __('Line height', 'decorator-woocommerce-email-customizer'),
                        'control_type' => 'range_value',
                        'section' => 'heading',
                        'default' => self::get_default_value('heading_line_height'),
                        'live_method' => 'css',
                        'selectors' => array(
                            '#template_header h1' => array('line-height'),
                        ),
                        'input_attrs' => array(
                            'step' => 1,
                            'min' => 1,
                            'max' => 50,
                            'suffix' => 'px',
                        ),
                    ),
                    // Header Text align
                    'footer_show' => array(
                        'title' => __('Footer visibility', 'decorator-woocommerce-email-customizer'),
                        'section' => 'footer_style',
                        'default' => self::get_default_value('footer_show'),
                        'live_method' => 'css',
                        'control_type' => 'toggleswitch',
                        'selectors' => array(
                            '#wt_wrapper_table #wt_template_footers' => array('height','width','overflow'),
                        ),
                    ),
                    
                    // Background color
                    'footer_background_color' => array(
                        'title' => __('Background color', 'decorator-woocommerce-email-customizer'),
                        'section' => 'footer_style',
                        'control_type' => 'color',
                        'default' => RP_Decorator_Settings::get_default_value('footer_background_color'),
                        'live_method' => 'css',
                        'selectors' => array(
                            '#template_footer' => array('background-color'),
                        ),
                    ),
                    // Footer Top/Bottom Padding
                    'footer_top_bottom_padding' => array(
                        'title' => __('Top/Bottom padding', 'decorator-woocommerce-email-customizer'),
                        'control_type' => 'range_value',
                        'section' => 'footer_style',
                        'default' => RP_Decorator_Settings::get_default_value('footer_top_bottom_padding'),
                        'live_method' => 'css',
                        'selectors' => array(
                            '#template_footer #credits' => array('padding-top', 'padding-bottom'),
                        ),
                        'input_attrs' => array(
                            'step' => 1,
                            'min' => 0,
                            'max' => 150,
                            'suffix' => 'px',
                        ),
                    ),
                    // Footer left and right Padding
                    'footer_left_right_padding' => array(
                        'title' => __('Left/Right padding', 'decorator-woocommerce-email-customizer'),
                        'control_type' => 'range_value',
                        'section' => 'footer_style',
                        'default' => RP_Decorator_Settings::get_default_value('footer_left_right_padding'),
                        'live_method' => 'css',
                        'selectors' => array(
                            '#template_footer #credit' => array('padding-left', 'padding-right'),
                        ),
                        'input_attrs' => array(
                            'step' => 1,
                            'min' => 0,
                            'max' => 150,
                            'suffix' => 'px',
                        ),
                    ),
                    // Footer Text align
                    'footer_text_align' => array(
                        'title' => __('Text align', 'decorator-woocommerce-email-customizer'),
                        'section' => 'footer_style',
                        'default' => RP_Decorator_Settings::get_default_value('footer_text_align'),
                        'live_method' => 'css',
                        'type' => 'select',
                        'choices' => RP_Decorator_Settings::get_text_aligns(),
                        'selectors' => array(
                            '#template_footer #credit' => array('text-align'),
                        ),
                    ),
                    // Footer Font size
                    'footer_font_size' => array(
                        'title' => __('Font size', 'decorator-woocommerce-email-customizer'),
                        'control_type' => 'range_value',
                        'section' => 'footer_style',
                        'default' => RP_Decorator_Settings::get_default_value('footer_font_size'),
                        'live_method' => 'css',
                        'selectors' => array(
                            '#template_footer #credit' => array('font-size'),
                        ),
                        'input_attrs' => array(
                            'step' => 1,
                            'min' => 8,
                            'max' => 30,
                            'suffix' => 'px',
                        ),
                    ),
                    // Footer Font family
                    'footer_font_family' => array(
                        'title' => __('Font family', 'decorator-woocommerce-email-customizer'),
                        'section' => 'footer_style',
                        'default' => RP_Decorator_Settings::get_default_value('footer_font_family'),
                        'live_method' => 'css',
                        'type' => 'select',
                        'choices' => RP_Decorator_Settings::get_font_families(),
                        'selectors' => array(
                            '#template_footer #credit' => array('font-family'),
                        ),
                    ),
                    // Footer Font weight
                    'footer_font_weight' => array(
                        'title' => __('Font weight', 'decorator-woocommerce-email-customizer'),
                        'control_type' => 'range_value',
                        'section' => 'footer_style',
                        'default' => RP_Decorator_Settings::get_default_value('footer_font_weight'),
                        'live_method' => 'css',
                        'selectors' => array(
                            '#template_footer #credit' => array('font-weight'),
                        ),
                        'input_attrs' => array(
                            'step' => 100,
                            'min' => 100,
                            'max' => 900,
                        ),
                    ),
                    // Footer Color
                    'footer_color' => array(
                        'title' => __('Text color', 'decorator-woocommerce-email-customizer'),
                        'section' => 'footer_style',
                        'control_type' => 'color',
                        'default' => RP_Decorator_Settings::get_default_value('footer_color'),
                        'live_method' => 'css',
                        'selectors' => array(
                            '#template_footer #credit' => array('color'),
                        ),
                    ),
                    // Link color
                    'footer_link_color' => array(
                        'title' => __('Link color', 'decorator-woocommerce-email-customizer'),
                        'section' => 'footer_style',
                        'control_type' => 'color',
                        'default' => RP_Decorator_Settings::get_default_value('footer_link_color'),
                        'live_method' => 'css',
                        'selectors' => array(
                            '#credit a' => array('color'),
                            '#credit .link' => array('color'),
                        ),
                    ),

                    // Footer Content Footer text
                    'footer_content_text' => array(
                        'title' => __('Footer text', 'decorator-woocommerce-email-customizer'),
                        'type' => 'textarea',
                        'section' => 'footer_content',
                        'default' => RP_Decorator_Settings::get_default_value('footer_content_text'),
                        'original' => '',
                        'live_method' => 'replace',
                        'selectors' => array(
                            '#template_footer #credit'
                        ),
                    ),
                    // H1 Font size
                    'h1_font_size' => array(
                        'title' => __('Font size', 'decorator-woocommerce-email-customizer'),
                        'control_type' => 'range_value',
                        'section' => 'h1',
                        'default' => RP_Decorator_Settings::get_default_value('h1_font_size'),
                        'live_method' => 'css',
                        'selectors' => array(
                            '#template_body h1' => array('font-size'),
                        ),
                        'input_attrs' => array(
                            'step' => 1,
                            'min' => 10,
                            'max' => 50,
                            'suffix' => 'px',
                        ),
                    ),
                    // H1 Font family
                    'h1_font_family' => array(
                        'title' => __('Font family', 'decorator-woocommerce-email-customizer'),
                        'section' => 'h1',
                        'default' => RP_Decorator_Settings::get_default_value('h1_font_family'),
                        'live_method' => 'css',
                        'type' => 'select',
                        'choices' => RP_Decorator_Settings::get_font_families(),
                        'selectors' => array(
                            '#template_body h1' => array('font-family'),
                        ),
                    ),
                    // H1 Font weight
                    'h1_font_weight' => array(
                        'title' => __('Font weight', 'decorator-woocommerce-email-customizer'),
                        'control_type' => 'range_value',
                        'section' => 'h1',
                        'default' => RP_Decorator_Settings::get_default_value('h1_font_weight'),
                        'live_method' => 'css',
                        'selectors' => array(
                            '#template_body h1' => array('font-weight'),
                        ),
                        'input_attrs' => array(
                            'step' => 100,
                            'min' => 100,
                            'max' => 900,
                        ),
                    ),
                    // H1 Color
                    'h1_color' => array(
                        'title' => __('Text color', 'decorator-woocommerce-email-customizer'),
                        'section' => 'h1',
                        'control_type' => 'color',
                        'default' => RP_Decorator_Settings::get_default_value('h1_color'),
                        'live_method' => 'css',
                        'selectors' => array(
                            '#template_body h1' => array('color'),
                        ),
                    ),
                    // H1 Separator
                    'h1_separator_style' => array(
                        'title' => __('Separator style', 'decorator-woocommerce-email-customizer'),
                        'section' => 'h1',
                        'default' => RP_Decorator_Settings::get_default_value('h1_separator_style'),
                        'live_method' => 'css',
                        'type' => 'select',
                        'choices' => RP_Decorator_Settings::get_border_styles(),
                        'selectors' => array(
                            '#template_body h1' => array('border-bottom-style'),
                        ),
                    ),
                    // H1 Separator width
                    'h1_separator_width' => array(
                        'title' => __('Separator width', 'decorator-woocommerce-email-customizer'),
                        'control_type' => 'range_value',
                        'section' => 'h1',
                        'default' => RP_Decorator_Settings::get_default_value('h1_separator_width'),
                        'live_method' => 'css',
                        'selectors' => array(
                            '#template_body h1' => array('border-bottom-width'),
                        ),
                        'input_attrs' => array(
                            'step' => 1,
                            'min' => 0,
                            'max' => 50,
                            'suffix' => 'px',
                        ),
                    ),
                    // H1 Separator color
                    'h1_separator_color' => array(
                        'title' => __('Separator color', 'decorator-woocommerce-email-customizer'),
                        'section' => 'h1',
                        'control_type' => 'color',
                        'default' => RP_Decorator_Settings::get_default_value('h1_separator_color'),
                        'live_method' => 'css',
                        'selectors' => array(
                            '#template_body h1' => array('border-bottom-color'),
                        ),
                    ),
                    // H2 Font size
                    'h2_font_size' => array(
                        'title' => __('Font size', 'decorator-woocommerce-email-customizer'),
                        'control_type' => 'range_value',
                        'section' => 'h2',
                        'default' => RP_Decorator_Settings::get_default_value('h2_font_size'),
                        'live_method' => 'css',
                        'selectors' => array(
                            '#template_body h2' => array('font-size'),
                        ),
                        'input_attrs' => array(
                            'step' => 1,
                            'min' => 10,
                            'max' => 50,
                            'suffix' => 'px',
                        ),
                    ),
                    // H2 Font family
                    'h2_font_family' => array(
                        'title' => __('Font family', 'decorator-woocommerce-email-customizer'),
                        'section' => 'h2',
                        'default' => RP_Decorator_Settings::get_default_value('h2_font_family'),
                        'live_method' => 'css',
                        'type' => 'select',
                        'choices' => RP_Decorator_Settings::get_font_families(),
                        'selectors' => array(
                            '#template_body h2' => array('font-family'),
                        ),
                    ),
                    // H2 Font weight
                    'h2_font_weight' => array(
                        'title' => __('Font weight', 'decorator-woocommerce-email-customizer'),
                        'control_type' => 'range_value',
                        'section' => 'h2',
                        'default' => RP_Decorator_Settings::get_default_value('h2_font_weight'),
                        'live_method' => 'css',
                        'selectors' => array(
                            '#template_body h2' => array('font-weight'),
                        ),
                        'input_attrs' => array(
                            'step' => 100,
                            'min' => 100,
                            'max' => 900,
                        ),
                    ),
                    // H2 Color
                    'h2_color' => array(
                        'title' => __('Text color', 'decorator-woocommerce-email-customizer'),
                        'section' => 'h2',
                        'control_type' => 'color',
                        'default' => RP_Decorator_Settings::get_default_value('h2_color'),
                        'live_method' => 'css',
                        'selectors' => array(
                            '#template_body h2' => array('color'),
                        ),
                    ),
                    // H2 Separator
                    'h2_separator_style' => array(
                        'title' => __('Separator style', 'decorator-woocommerce-email-customizer'),
                        'section' => 'h2',
                        'default' => RP_Decorator_Settings::get_default_value('h2_separator_style'),
                        'live_method' => 'css',
                        'type' => 'select',
                        'choices' => RP_Decorator_Settings::get_border_styles(),
                        'selectors' => array(
                            '#template_body h2' => array('border-bottom-style'),
                        ),
                    ),
                    // H2 Separator width
                    'h2_separator_width' => array(
                        'title' => __('Separator width', 'decorator-woocommerce-email-customizer'),
                        'control_type' => 'range_value',
                        'section' => 'h2',
                        'default' => RP_Decorator_Settings::get_default_value('h2_separator_width'),
                        'live_method' => 'css',
                        'selectors' => array(
                            '#template_body h2' => array('border-bottom-width'),
                        ),
                        'input_attrs' => array(
                            'step' => 1,
                            'min' => 0,
                            'max' => 50,
                            'suffix' => 'px',
                        ),
                    ),
                    // H2 Separator color
                    'h2_separator_color' => array(
                        'title' => __('Separator color', 'decorator-woocommerce-email-customizer'),
                        'section' => 'h2',
                        'control_type' => 'color',
                        'default' => RP_Decorator_Settings::get_default_value('h2_separator_color'),
                        'live_method' => 'css',
                        'selectors' => array(
                            '#template_body h2' => array('border-bottom-color'),
                        ),
                    ),
                    // H3 Font size
                    'h3_font_size' => array(
                        'title' => __('Font size', 'decorator-woocommerce-email-customizer'),
                        'control_type' => 'range_value',
                        'section' => 'h3',
                        'default' => RP_Decorator_Settings::get_default_value('h3_font_size'),
                        'live_method' => 'css',
                        'selectors' => array(
                            '#template_body h3' => array('font-size'),
                        ),
                        'input_attrs' => array(
                            'step' => 1,
                            'min' => 10,
                            'max' => 50,
                            'suffix' => 'px',
                        ),
                    ),
                    // H3 Font family
                    'h3_font_family' => array(
                        'title' => __('Font family', 'decorator-woocommerce-email-customizer'),
                        'section' => 'h3',
                        'default' => RP_Decorator_Settings::get_default_value('h3_font_family'),
                        'live_method' => 'css',
                        'type' => 'select',
                        'choices' => RP_Decorator_Settings::get_font_families(),
                        'selectors' => array(
                            '#template_body h3' => array('font-family'),
                        ),
                    ),
                    // H3 Font weight
                    'h3_font_weight' => array(
                        'title' => __('Font weight', 'decorator-woocommerce-email-customizer'),
                        'control_type' => 'range_value',
                        'section' => 'h3',
                        'default' => RP_Decorator_Settings::get_default_value('h3_font_weight'),
                        'live_method' => 'css',
                        'selectors' => array(
                            '#template_body h3' => array('font-weight'),
                        ),
                        'input_attrs' => array(
                            'step' => 100,
                            'min' => 100,
                            'max' => 900,
                        ),
                    ),
                    // H3 Color
                    'h3_color' => array(
                        'title' => __('Text color', 'decorator-woocommerce-email-customizer'),
                        'section' => 'h3',
                        'control_type' => 'color',
                        'default' => RP_Decorator_Settings::get_default_value('h3_color'),
                        'live_method' => 'css',
                        'selectors' => array(
                            '#template_body h3' => array('color'),
                        ),
                    ),
                    // H3 Separator
                    'h3_separator_style' => array(
                        'title' => __('Separator style', 'decorator-woocommerce-email-customizer'),
                        'section' => 'h3',
                        'default' => RP_Decorator_Settings::get_default_value('h3_separator_style'),
                        'live_method' => 'css',
                        'type' => 'select',
                        'choices' => RP_Decorator_Settings::get_border_styles(),
                        'selectors' => array(
                            '#template_body h3' => array('border-bottom-style'),
                        ),
                    ),
                    // H3 Separator width
                    'h3_separator_width' => array(
                        'title' => __('Separator width', 'decorator-woocommerce-email-customizer'),
                        'control_type' => 'range_value',
                        'section' => 'h3',
                        'default' => RP_Decorator_Settings::get_default_value('h3_separator_width'),
                        'live_method' => 'css',
                        'selectors' => array(
                            '#template_body h3' => array('border-bottom-width'),
                        ),
                        'input_attrs' => array(
                            'step' => 1,
                            'min' => 0,
                            'max' => 50,
                            'suffix' => 'px',
                        ),
                    ),
                    // H3 Separator color
                    'h3_separator_color' => array(
                        'title' => __('Separator color', 'decorator-woocommerce-email-customizer'),
                        'section' => 'h3',
                        'control_type' => 'color',
                        'default' => RP_Decorator_Settings::get_default_value('h3_separator_color'),
                        'live_method' => 'css',
                        'selectors' => array(
                            '#template_body h3' => array('border-bottom-color'),
                        ),
                    ),
                    // H4 Font size
                    'h4_font_size' => array(
                        'title' => __('Font size', 'decorator-woocommerce-email-customizer'),
                        'control_type' => 'range_value',
                        'section' => 'h4',
                        'default' => RP_Decorator_Settings::get_default_value('h4_font_size'),
                        'live_method' => 'css',
                        'selectors' => array(
                            '#template_body h4' => array('font-size'),
                        ),
                        'input_attrs' => array(
                            'step' => 1,
                            'min' => 10,
                            'max' => 50,
                            'suffix' => 'px',
                        ),
                    ),
                    // H4 Font family
                    'h4_font_family' => array(
                        'title' => __('Font family', 'decorator-woocommerce-email-customizer'),
                        'section' => 'h4',
                        'default' => RP_Decorator_Settings::get_default_value('h4_font_family'),
                        'live_method' => 'css',
                        'type' => 'select',
                        'choices' => RP_Decorator_Settings::get_font_families(),
                        'selectors' => array(
                            '#template_body h4' => array('font-family'),
                        ),
                    ),
                    // H4 Font weight
                    'h4_font_weight' => array(
                        'title' => __('Font weight', 'decorator-woocommerce-email-customizer'),
                        'control_type' => 'range_value',
                        'section' => 'h4',
                        'default' => RP_Decorator_Settings::get_default_value('h4_font_weight'),
                        'live_method' => 'css',
                        'selectors' => array(
                            '#template_body h4' => array('font-weight'),
                        ),
                        'input_attrs' => array(
                            'step' => 100,
                            'min' => 100,
                            'max' => 900,
                        ),
                    ),
                    // H4 Color
                    'h4_color' => array(
                        'title' => __('Text color', 'decorator-woocommerce-email-customizer'),
                        'section' => 'h4',
                        'control_type' => 'color',
                        'default' => RP_Decorator_Settings::get_default_value('h4_color'),
                        'live_method' => 'css',
                        'selectors' => array(
                            '#template_body h4' => array('color'),
                        ),
                    ),
                    // H4 Separator
                    'h4_separator_style' => array(
                        'title' => __('Separator style', 'decorator-woocommerce-email-customizer'),
                        'section' => 'h4',
                        'default' => RP_Decorator_Settings::get_default_value('h4_separator_style'),
                        'live_method' => 'css',
                        'type' => 'select',
                        'choices' => RP_Decorator_Settings::get_border_styles(),
                        'selectors' => array(
                            '#template_body h4' => array('border-bottom-style'),
                        ),
                    ),
                    // H4 Separator width
                    'h4_separator_width' => array(
                        'title' => __('Separator width', 'decorator-woocommerce-email-customizer'),
                        'control_type' => 'range_value',
                        'section' => 'h4',
                        'default' => RP_Decorator_Settings::get_default_value('h4_separator_width'),
                        'live_method' => 'css',
                        'selectors' => array(
                            '#template_body h4' => array('border-bottom-width'),
                        ),
                        'input_attrs' => array(
                            'step' => 1,
                            'min' => 0,
                            'max' => 50,
                            'suffix' => 'px',
                        ),
                    ),
                    // H4 Separator color
                    'h4_separator_color' => array(
                        'title' => __('Separator color', 'decorator-woocommerce-email-customizer'),
                        'section' => 'h4',
                        'control_type' => 'color',
                        'default' => RP_Decorator_Settings::get_default_value('h4_separator_color'),
                        'live_method' => 'css',
                        'selectors' => array(
                            '#template_body h4' => array('border-bottom-color'),
                        ),
                    ),
                    // H5 Font size
                    'h5_font_size' => array(
                        'title' => __('Font size', 'decorator-woocommerce-email-customizer'),
                        'control_type' => 'range_value',
                        'section' => 'h5',
                        'default' => RP_Decorator_Settings::get_default_value('h5_font_size'),
                        'live_method' => 'css',
                        'selectors' => array(
                            '#template_body h5' => array('font-size'),
                        ),
                        'input_attrs' => array(
                            'step' => 1,
                            'min' => 10,
                            'max' => 50,
                            'suffix' => 'px',
                        ),
                    ),
                    // H5 Font family
                    'h5_font_family' => array(
                        'title' => __('Font family', 'decorator-woocommerce-email-customizer'),
                        'section' => 'h5',
                        'default' => RP_Decorator_Settings::get_default_value('h5_font_family'),
                        'live_method' => 'css',
                        'type' => 'select',
                        'choices' => RP_Decorator_Settings::get_font_families(),
                        'selectors' => array(
                            '#template_body h5' => array('font-family'),
                        ),
                    ),
                    // H5 Font weight
                    'h5_font_weight' => array(
                        'title' => __('Font weight', 'decorator-woocommerce-email-customizer'),
                        'control_type' => 'range_value',
                        'section' => 'h5',
                        'default' => RP_Decorator_Settings::get_default_value('h5_font_weight'),
                        'live_method' => 'css',
                        'selectors' => array(
                            '#template_body h5' => array('font-weight'),
                        ),
                        'input_attrs' => array(
                            'step' => 100,
                            'min' => 100,
                            'max' => 900,
                        ),
                    ),
                    // H5 Color
                    'h5_color' => array(
                        'title' => __('Text color', 'decorator-woocommerce-email-customizer'),
                        'section' => 'h5',
                        'control_type' => 'color',
                        'default' => RP_Decorator_Settings::get_default_value('h5_color'),
                        'live_method' => 'css',
                        'selectors' => array(
                            '#template_body h5' => array('color'),
                        ),
                    ),
                    // H5 Separator
                    'h5_separator_style' => array(
                        'title' => __('Separator style', 'decorator-woocommerce-email-customizer'),
                        'section' => 'h5',
                        'default' => RP_Decorator_Settings::get_default_value('h5_separator_style'),
                        'live_method' => 'css',
                        'type' => 'select',
                        'choices' => RP_Decorator_Settings::get_border_styles(),
                        'selectors' => array(
                            '#template_body h5' => array('border-bottom-style'),
                        ),
                    ),
                    // H5 Separator width
                    'h5_separator_width' => array(
                        'title' => __('Separator width', 'decorator-woocommerce-email-customizer'),
                        'control_type' => 'range_value',
                        'section' => 'h5',
                        'default' => RP_Decorator_Settings::get_default_value('h5_separator_width'),
                        'live_method' => 'css',
                        'selectors' => array(
                            '#template_body h5' => array('border-bottom-width'),
                        ),
                        'input_attrs' => array(
                            'step' => 1,
                            'min' => 0,
                            'max' => 50,
                            'suffix' => 'px',
                        ),
                    ),
                    // H5 Separator color
                    'h5_separator_color' => array(
                        'title' => __('Separator color', 'decorator-woocommerce-email-customizer'),
                        'section' => 'h5',
                        'control_type' => 'color',
                        'default' => RP_Decorator_Settings::get_default_value('h5_separator_color'),
                        'live_method' => 'css',
                        'selectors' => array(
                            '#template_body h5' => array('border-bottom-color'),
                        ),
                    ),
                    // H6 Font size
                    'h6_font_size' => array(
                        'title' => __('Font size', 'decorator-woocommerce-email-customizer'),
                        'control_type' => 'range_value',
                        'section' => 'h6',
                        'default' => RP_Decorator_Settings::get_default_value('h6_font_size'),
                        'live_method' => 'css',
                        'selectors' => array(
                            '#template_body h6' => array('font-size'),
                        ),
                        'input_attrs' => array(
                            'step' => 1,
                            'min' => 10,
                            'max' => 50,
                            'suffix' => 'px',
                        ),
                    ),
                    // H6 Font family
                    'h6_font_family' => array(
                        'title' => __('Font family', 'decorator-woocommerce-email-customizer'),
                        'section' => 'h6',
                        'default' => RP_Decorator_Settings::get_default_value('h6_font_family'),
                        'live_method' => 'css',
                        'type' => 'select',
                        'choices' => RP_Decorator_Settings::get_font_families(),
                        'selectors' => array(
                            '#template_body h6' => array('font-family'),
                        ),
                    ),
                    // H6 Font weight
                    'h6_font_weight' => array(
                        'title' => __('Font weight', 'decorator-woocommerce-email-customizer'),
                        'control_type' => 'range_value',
                        'section' => 'h6',
                        'default' => RP_Decorator_Settings::get_default_value('h6_font_weight'),
                        'live_method' => 'css',
                        'selectors' => array(
                            '#template_body h6' => array('font-weight'),
                        ),
                        'input_attrs' => array(
                            'step' => 100,
                            'min' => 100,
                            'max' => 900,
                        ),
                    ),
                    // H6 Color
                    'h6_color' => array(
                        'title' => __('Text color', 'decorator-woocommerce-email-customizer'),
                        'section' => 'h6',
                        'control_type' => 'color',
                        'default' => RP_Decorator_Settings::get_default_value('h6_color'),
                        'live_method' => 'css',
                        'selectors' => array(
                            '#template_body h6' => array('color'),
                        ),
                    ),
                    // H6 Separator
                    'h6_separator_style' => array(
                        'title' => __('Separator style', 'decorator-woocommerce-email-customizer'),
                        'section' => 'h6',
                        'default' => RP_Decorator_Settings::get_default_value('h6_separator_style'),
                        'live_method' => 'css',
                        'type' => 'select',
                        'choices' => RP_Decorator_Settings::get_border_styles(),
                        'selectors' => array(
                            '#template_body h6' => array('border-bottom-style'),
                        ),
                    ),
                    // H6 Separator width
                    'h6_separator_width' => array(
                        'title' => __('Separator width', 'decorator-woocommerce-email-customizer'),
                        'control_type' => 'range_value',
                        'section' => 'h6',
                        'default' => RP_Decorator_Settings::get_default_value('h6_separator_width'),
                        'live_method' => 'css',
                        'selectors' => array(
                            '#template_body h6' => array('border-bottom-width'),
                        ),
                        'input_attrs' => array(
                            'step' => 1,
                            'min' => 0,
                            'max' => 50,
                            'suffix' => 'px',
                        ),
                    ),
                    // H6 Separator color
                    'h6_separator_color' => array(
                        'title' => __('Separator color', 'decorator-woocommerce-email-customizer'),
                        'section' => 'h6',
                        'control_type' => 'color',
                        'default' => RP_Decorator_Settings::get_default_value('h6_separator_color'),
                        'live_method' => 'css',
                        'selectors' => array(
                            '#template_body h6' => array('border-bottom-color'),
                        ),
                    ),
                    // Custom CSS
                    'custom_css' => array(
                        'title' => __('Custom CSS', 'decorator-woocommerce-email-customizer'),
                        'section' => 'custom_styles',
                        'default' => RP_Decorator_Settings::get_default_value('custom_css'),
                        'type' => 'textarea',
                        'live_method' => 'replace',
                        'original' => '',
                        'selectors' => array(
                            'style#rp_decorator_custom_css'
                        ),
                    ),
                    'order_items_show' => array(
                         'title' => __('Item table visibility', 'decorator-woocommerce-email-customizer'),
                         'section' => 'items_table',
                         'default' => self::get_default_value('order_items_show'),
                         'live_method' => 'css',
                         'control_type' => 'toggleswitch',
                         'selectors' => array(
                             '#wt_order_items_table' => array('height','width','overflow'),
                         ),
                     ),
                    
                    // Order item image.
                    'order_items_image' => array(
                        'title' => __('Product image option', 'decorator-woocommerce-email-customizer'),
                        'section' => 'items_table',
                        'default' => self::get_default_value('order_items_image'),
                        'transport' => 'refresh',
                        'type' => 'select',
                        'choices' => array(
                            'normal' => __('Do not show', 'decorator-woocommerce-email-customizer'),
                            'show' => __('Show', 'decorator-woocommerce-email-customizer'),
                        ),
                    ),
                    // Order item image.
                    'order_items_sku' => array(
                        'title' => __('Product SKU', 'decorator-woocommerce-email-customizer'),
                        'section' => 'items_table',
                        'default' => self::get_default_value('order_items_sku'),
                        'transport' => 'refresh',
                        'type' => 'select',
                        'choices' => array(
                            'normal' => __('Do not show', 'decorator-woocommerce-email-customizer'),
                            'show' => __('Show', 'decorator-woocommerce-email-customizer'),
                        ),
                    ),
                    // Order item image size.
                    'order_items_image_size' => array(
                        'title' => __('Product image size', 'decorator-woocommerce-email-customizer'),
                        'section' => 'items_table',
                        'default' => self::get_default_value('order_items_image_size'),
                        'transport' => 'refresh',
                        'type' => 'select',
                        'choices' => array(
                            '40x40' => __('40x40', 'decorator-woocommerce-email-customizer'),
                            '50x50' => __('50x50', 'decorator-woocommerce-email-customizer'),
                            '100x50' => __('100x50', 'decorator-woocommerce-email-customizer'),
                            '100x100' => __('100x100', 'decorator-woocommerce-email-customizer'),
                            '150x150' => __('150x150', 'decorator-woocommerce-email-customizer'),
                            'woocommerce_thumbnail' => __('Woocommerce Thumbnail', 'decorator-woocommerce-email-customizer'),
                        ),
                    ),
                    // Items table Background color
                    'items_table_background_color' => array(
                        'title' => __('Background color', 'decorator-woocommerce-email-customizer'),
                        'section' => 'items_table',
                        'control_type' => 'color',
                        'default' => RP_Decorator_Settings::get_default_value('items_table_background_color'),
                        'live_method' => 'css',
                        'selectors' => array(
                            '#body_content_inner > #wt_order_items_table > div> table.td' => array('background-color'),
                            '#body_content_inner > div > table.td' => array('background-color'),
                        ),
                    ),
                    // Items table Padding
                    'items_table_padding' => array(
                        'title' => __('Padding', 'decorator-woocommerce-email-customizer'),
                        'control_type' => 'range_value',
                        'section' => 'items_table',
                        'default' => RP_Decorator_Settings::get_default_value('items_table_padding'),
                        'live_method' => 'css',
                        'selectors' => array(
                            '#wt_order_items_table > table.td th' => array('padding'),
                            '#wt_order_items_table > div > table.td th' => array('padding'),
                            '#wt_order_items_table > table.td td' => array('padding'),
                            '#wt_order_items_table > div > table.td td' => array('padding'),
                        //'#body_content_inner > div#rp_wcec_email_content > table.td td' => array('padding'), - removed wcec_email_content div from all item table settings - woocommerce-email-center plugin compatiblity issues.			
                        ),
                        'input_attrs' => array(
                            'step' => 1,
                            'min' => 0,
                            'max' => 50,
                            'suffix' => 'px',
                        ),
                    ),
                    // Items table Border width
                    'items_table_border_width' => array(
                        'title' => __('Border width', 'decorator-woocommerce-email-customizer'),
                        'control_type' => 'range_value',
                        'section' => 'items_table',
                        'default' => RP_Decorator_Settings::get_default_value('items_table_border_width'),
                        'live_method' => 'css',
                        'selectors' => array(
                            '#wt_order_items_table > table.td' => array('border-width'),
                            '#wt_order_items_table > div > table.td' => array('border-width'),
                            '#wt_order_items_table > table.td .td' => array('border-width'),
                            '#wt_order_items_table > div > table.td .td' => array('border-width'),
                            '.rp_decorator_order_refund_line .td' => array('border-width'),
                            '#wt_order_items_table > div > table.td > tbody > tr:last-child > td' => array('border-bottom-width'),
                        ),
                        'input_attrs' => array(
                            'step' => 1,
                            'min' => 0,
                            'max' => 10,
                            'suffix' => 'px',
                        ),
                    ),
                    // Items table Border color
                    'items_table_border_color' => array(
                        'title' => __('Border color', 'decorator-woocommerce-email-customizer'),
                        'section' => 'items_table',
                        'control_type' => 'color',
                        'default' => RP_Decorator_Settings::get_default_value('items_table_border_color'),
                        'live_method' => 'css',
                        'selectors' => array(
                            '#wt_order_items_table > table.td' => array('border-color'),
                            '#wt_order_items_table > div > table.td' => array('border-color'),
                            '#wt_order_items_table > table.td td.td' => array('border-color'),
                            '#wt_order_items_table > div > table.td td.td' => array('border-color'),
                            '#wt_order_items_table > table.td .td' => array('border-color'),
                            '#wt_order_items_table > div > table.td .td' => array('border-color'),
                        ),
                    ),
                    // Items table Totals separator width
                   /* 'items_table_separator_width' => array(
                        'title' => __('Totals separator width', 'decorator-woocommerce-email-customizer'),
                        'control_type' => 'range_value',
                        'section' => 'items_table',
                        'default' => RP_Decorator_Settings::get_default_value('items_table_separator_width'),
                        'live_method' => 'css',
                        'selectors' => array(
                            '#body_content_inner > table.td > tbody > tr > td' => array('border-bottom-width'),
                            '#body_content_inner > div > table.td > tbody > tr:last-child > td' => array('border-bottom-width'),
                        ),
                        'input_attrs' => array(
                            'step' => 1,
                            'min' => 0,
                            'max' => 20,
                            'suffix' => 'px',
                        ),
                    ),
                    // Items table Totals separator color
                    'items_table_separator_color' => array(
                        'title' => __('Totals separator color', 'decorator-woocommerce-email-customizer'),
                        'section' => 'items_table',
                        'control_type' => 'color',
                        'default' => RP_Decorator_Settings::get_default_value('items_table_separator_color'),
                        'live_method' => 'css',
                        'selectors' => array(
                            '#body_content_inner > table.td > tbody > tr > td' => array('border-bottom-color'),
                            '#body_content_inner > div > table.td > tbody > tr:last-child > td' => array('border-bottom-color'),
                        ),
                    ),*/
                    // Image Align.
                    'header_image_align' => array(
                        'title' => __('Align', 'decorator-woocommerce-email-customizer'),
                        'section' => 'header_image',
                        'default' => self::get_default_value('header_image_align'),
                        'live_method' => 'css',
                        'type' => 'select',
                        'choices' => RP_Decorator_Settings::get_text_aligns(),
                        'selectors' => array(
                            '#template_header_image' => array('text-align'),
                        ),
                    ),
                    'header_image_placement' => array(
                        'title' => __('Header image placement', 'decorator-woocommerce-email-customizer'),
                        'section' => 'header_image',
                        'default' => self::get_default_value('header_image_placement'),
                        'transport' => 'refresh',
                        'type' => 'select',
                        'choices' => array(
                            'inside' => __('Inside', 'decorator-woocommerce-email-customizer'),
                            'outside' => __('Outside', 'decorator-woocommerce-email-customizer'),
                        ),
                    ),
                    // Image Maxwidth
                    'header_image_maxwidth' => array(
                        'title' => __('Max width', 'decorator-woocommerce-email-customizer'),
                        'section' => 'header_image',
                        'default' => self::get_default_value('header_image_maxwidth'),
                        'live_method' => 'css',
                        'control_type' => 'range_value',
                        'selectors' => array(
                            '#template_header_image p img' => array('width'),
                            '#template_header_image p a img' => array('width'),
                            '#template_header_image a' => array('width'),
                            '#template_header_image' => array('width'),
                        ),
                        'input_attrs' => array(
                            'step' => 1,
                            'min' => 10,
                            'max' => 1200,
                            'suffix' => 'px',
                        ),
                    ),
                     // Image Maxwidth
                    'header_image_maxheight' => array(
                        'title' => __('Max height', 'decorator-woocommerce-email-customizer'),
                        'section' => 'header_image',
                        'default' => self::get_default_value('header_image_maxheight'),
                        'live_method' => 'css',
                        'control_type' => 'range_value',
                        'selectors' => array(
                            '#template_header_image p img' => array('height'),
                            '#template_header_image p a img' => array('height'),
                        ),
                        'input_attrs' => array(
                            'step' => 1,
                            'min' => 10,
                            'max' => 600,
                            'suffix' => 'px',
                        ),
                    ),
                    'header_image_background_color' => array(
                        'title' => __('Background color', 'decorator-woocommerce-email-customizer'),
                        'section' => 'header_image',
                        'control_type' => 'color',
                        'default' => self::get_default_value('header_image_background_color'),
                        'live_method' => 'css',
                        'selectors' => array(
                            '#template_header_image' => array('background-color'),
                        ),
                    ),
                    // Header Padding top/bottom.
                    'header_image_padding_top_bottom' => array(
                        'title' => __('Padding top/bottom', 'decorator-woocommerce-email-customizer'),
                        'control_type' => 'range_value',
                        'section' => 'header_image',
                        'default' => self::get_default_value('header_image_padding_top_bottom'),
                        'live_method' => 'css',
                        'selectors' => array(
                            '#template_header_image p img' => array('padding-top', 'padding-bottom'),
                            '#template_header_image p a img' => array('padding-top', 'padding-bottom'),
                        ),
                        'input_attrs' => array(
                            'step' => 1,
                            'min' => 0,
                            'max' => 150,
                            'suffix' => 'px',
                        ),
                    ),
                    'subtitle_settings' => array(
                        'title' => __('Subtitle Settings', 'decorator-woocommerce-email-customizer'),
                        'section' => 'heading',
                        'control_type' => 'labels',
                    ),
                    // Subtitle placement
                    'subtitle_placement' => array(
                        'title' => __('Subtitle position', 'decorator-woocommerce-email-customizer'),
                        'section' => 'heading',
                        'default' => self::get_default_value('subtitle_placement'),
                        'transport' => 'refresh',
                        'type' => 'select',
                        'choices' => array(
                            'below' => __('Below heading', 'decorator-woocommerce-email-customizer'),
                            'above' => __('Above heading', 'decorator-woocommerce-email-customizer'),
                        ),
                    ),

                    // Subtitle Text align
                    'subtitle_text_align' => array(
                        'title' => __('Text align', 'decorator-woocommerce-email-customizer'),
                        'section' => 'heading',
                        'default' => RP_Decorator_Settings::get_default_value('subtitle_text_align'),
                        'live_method' => 'css',
                        'type' => 'select',
                        'choices' => RP_Decorator_Settings::get_text_aligns(),
                        'selectors' => array(
                            '#template_header .subtitle' => array('text-align'),
                        ),
                    ),
                    // Subtitle Font size
                    'subtitle_font_size' => array(
                        'title' => __('Font size', 'decorator-woocommerce-email-customizer'),
                        'control_type' => 'range_value',
                        'section' => 'heading',
                        'default' => self::get_default_value('subtitle_font_size'),
                        'live_method' => 'css',
                        'selectors' => array(
                            '#template_header .subtitle' => array('font-size'),
                        ),
                        'input_attrs' => array(
                            'step' => 1,
                            'min' => 10,
                            'max' => 75,
                            'suffix' => 'px',
                        ),
                    ),
                    // Subtitle Line Height
                    'subtitle_line_height' => array(
                        'title' => __('Line height', 'decorator-woocommerce-email-customizer'),
                        'control_type' => 'range_value',
                        'section' => 'heading',
                        'default' => self::get_default_value('subtitle_line_height'),
                        'live_method' => 'css',
                        'selectors' => array(
                            '#template_header .subtitle' => array('line-height'),
                        ),
                        'input_attrs' => array(
                            'step' => 1,
                            'min' => 1,
                            'max' => 50,
                            'suffix' => 'px',
                        ),
                    ),
                    // Subtitle Font family.
                    'subtitle_font_family' => array(
                        'title' => __('Font family', 'decorator-woocommerce-email-customizer'),
                        'section' => 'heading',
                        'default' => self::get_default_value('subtitle_font_family'),
                        'live_method' => 'css',
                        'type' => 'select',
                        'choices' => self::get_font_families(),
                        'selectors' => array(
                            '#template_header .subtitle' => array('font-family'),
                        ),
                    ),
                    // Subtitle Font style
                    'subtitle_font_style' => array(
                        'title' => __('Font style', 'decorator-woocommerce-email-customizer'),
                        'section' => 'heading',
                        'default' => self::get_default_value('subtitle_font_style'),
                        'live_method' => 'css',
                        'type' => 'select',
                        'choices' => array(
                            'normal' => __('Normal', 'decorator-woocommerce-email-customizer'),
                            'italic' => __('Italic', 'decorator-woocommerce-email-customizer'),
                        ),
                        'selectors' => array(
                            '#template_header .subtitle' => array('font-style'),
                        ),
                    ),
                    // Subtitle Font weight
                    'subtitle_font_weight' => array(
                        'title' => __('Font weight', 'decorator-woocommerce-email-customizer'),
                        'control_type' => 'range_value',
                        'section' => 'heading',
                        'default' => self::get_default_value('subtitle_font_weight'),
                        'live_method' => 'css',
                        'selectors' => array(
                            '#template_header .subtitle' => array('font-weight'),
                        ),
                        'input_attrs' => array(
                            'step' => 100,
                            'min' => 100,
                            'max' => 900,
                        ),
                    ),
                    // Subtitle Color
                    'subtitle_color' => array(
                        'title' => __('Text color', 'decorator-woocommerce-email-customizer'),
                        'section' => 'heading',
                        'control_type' => 'color',
                        'default' => self::get_default_value('subtitle_color'),
                        'live_method' => 'css',
                        'selectors' => array(
                            '#template_header .subtitle' => array('color'),
                        ),
                    ),
                    
                    // Header Text align
                    'billing_address_show' => array(
                         'title' => __('Billing address visibility', 'decorator-woocommerce-email-customizer'),
                         'section' => 'address_table',
                         'default' => self::get_default_value('billing_address_show'),
                         'live_method' => 'css',
                         'control_type' => 'toggleswitch',
                         'selectors' => array(
                             '#wt_billing_address_wrap' => array('height','width','overflow'),
                         ),
                     ),
                    
                    // Header Text align
                    'shipping_address_show' => array(
                         'title' => __('Shipping address visibility', 'decorator-woocommerce-email-customizer'),
                         'section' => 'address_table',
                         'default' => self::get_default_value('shipping_address_show'),
                         'live_method' => 'css',
                         'control_type' => 'toggleswitch',
                         'selectors' => array(
                             '#wt_shipping_address_wrap' => array('height','width','overflow'),
                         ),
                     ),
                    
                    // addresses Background color
                    'address_box_background_color' => array(
                        'title' => __('Address box background color', 'decorator-woocommerce-email-customizer'),
                        'section' => 'address_table',
                        'control_type' => 'color',
                        'default' => self::get_default_value('address_box_background_color'),
                        'live_method' => 'css',
                        'selectors' => array(
                            '#body_content_inner .address' => array('background-color'),
                        ),
                    ),
                    // addresses color
                    'address_box_text_color' => array(
                        'title' => __('Address box text color', 'decorator-woocommerce-email-customizer'),
                        'section' => 'address_table',
                        'control_type' => 'color',
                        'default' => self::get_default_value('address_box_text_color'),
                        'live_method' => 'css',
                        'selectors' => array(
                            '#body_content_inner .address' => array('color'),
                        ),
                    ),
                    // addresses Padding
                    'address_box_padding_left_right' => array(
                        'title' => __('Address box left/right padding', 'decorator-woocommerce-email-customizer'),
                        'control_type' => 'range_value',
                        'section' => 'address_table',
                        'default' => self::get_default_value('address_box_padding'),
                        'live_method' => 'css',
                        'selectors' => array(
                            '#body_content_inner .address' => array('padding-left', 'padding-right'),
                        ),
                        'input_attrs' => array(
                            'step' => 1,
                            'min' => 0,
                            'max' => 100,
                            'suffix' => 'px',
                        ),
                    ),
                    // addresses Padding
                    'address_box_padding_top_bottom' => array(
                        'title' => __('Address box top/bottom padding', 'decorator-woocommerce-email-customizer'),
                        'control_type' => 'range_value',
                        'section' => 'address_table',
                        'default' => self::get_default_value('address_box_padding'),
                        'live_method' => 'css',
                        'selectors' => array(
                            '#body_content_inner .address' => array('padding-top', 'padding-bottom'),
                        ),
                        'input_attrs' => array(
                            'step' => 1,
                            'min' => 0,
                            'max' => 100,
                            'suffix' => 'px',
                        ),
                    ),
                    // addresses text align
                    'address_box_text_align' => array(
                        'title' => __('Address box text align', 'decorator-woocommerce-email-customizer'),
                        'section' => 'address_table',
                        'default' => self::get_default_value('address_box_text_align'),
                        'live_method' => 'css',
                        'type' => 'select',
                        'choices' => self::get_text_aligns(),
                        'selectors' => array(
                            '#body_content_inner .address' => array('text-align'),
                        ),
                    ),
                    // addresses Border width
                    'address_box_border_width' => array(
                        'title' => __('Address box border width', 'decorator-woocommerce-email-customizer'),
                        'control_type' => 'range_value',
                        'section' => 'address_table',
                        'default' => self::get_default_value('address_box_border_width'),
                        'live_method' => 'css',
                        'selectors' => array(
                            '#body_content_inner .address' => array('border-width'),
                        ),
                        'input_attrs' => array(
                            'step' => 1,
                            'min' => 0,
                            'max' => 10,
                            'suffix' => 'px',
                        ),
                    ),
                    // addresses Border color
                    'address_box_border_color' => array(
                        'title' => __('Address box border color', 'decorator-woocommerce-email-customizer'),
                        'section' => 'address_table',
                        'control_type' => 'color',
                        'default' => self::get_default_value('address_box_border_color'),
                        'live_method' => 'css',
                        'selectors' => array(
                            '#body_content_inner .address' => array('border-color'),
                        ),
                    ),
                    // h2 style
                    'address_box_border_style' => array(
                        'title' => __('Address box border style', 'decorator-woocommerce-email-customizer'),
                        'section' => 'address_table',
                        'default' => self::get_default_value('address_box_border_style'),
                        'live_method' => 'css',
                        'type' => 'select',
                        'choices' => array(
                            'solid' => __('Solid', 'decorator-woocommerce-email-customizer'),
                            'double' => __('Double', 'decorator-woocommerce-email-customizer'),
                            'groove' => __('Groove', 'decorator-woocommerce-email-customizer'),
                            'dotted' => __('Dotted', 'decorator-woocommerce-email-customizer'),
                            'dashed' => __('Dashed', 'decorator-woocommerce-email-customizer'),
                            'ridge' => __('Ridge', 'decorator-woocommerce-email-customizer'),
                        ),
                        'selectors' => array(
                            '#body_content_inner .address' => array('border-style'),
                        ),
                    ),
                    // Button Color.
                    'button_color' => array(
                        'title' => __('Button text color', 'decorator-woocommerce-email-customizer'),
                        'section' => 'button_styles',
                        'default' => self::get_default_value('button_color'),
                        'live_method' => 'css',
                        'selectors' => array(
                            'a.wt_template_button' => array('color'),
                            'a.button' => array('color'),
                        ),
                        'control_type' => 'color',
                    ),
                    // Button Text Size.
                    'button_size' => array(
                        'title' => __('Button font size', 'decorator-woocommerce-email-customizer'),
                        'control_type' => 'range_value',
                        'section' => 'button_styles',
                        'default' => self::get_default_value('button_size'),
                        'live_method' => 'css',
                        'selectors' => array(
                            'a.wt_template_button' => array('font-size'),
                            'a.button' => array('font-size'),
                        ),
                        'input_attrs' => array(
                            'step' => 1,
                            'min' => 8,
                            'max' => 30,
                            'suffix' => 'px',
                        ),
                    ),
                    // Button Text align
                    'button_text_align' => array(
                        'title' => __('Button align', 'decorator-woocommerce-email-customizer'),
                        'section' => 'button_styles',
                        'default' => self::get_default_value('button_text_align'),
                        'live_method' => 'css',
                        'type' => 'select',
                        'choices' => self::get_text_aligns(),
                        'selectors' => array(
                            '#body_content_inner .btn-container' => array('text-align'),
                        ),
                    ),
                    // Button Font Family
                    'button_font_family' => array(
                        'title' => __('Button font family', 'decorator-woocommerce-email-customizer'),
                        'section' => 'button_styles',
                        'default' => self::get_default_value('button_font_family'),
                        'live_method' => 'css',
                        'type' => 'select',
                        'choices' => self::get_font_families(),
                        'selectors' => array(
                            'a.wt_template_button' => array('font-family'),
                            'a.button' => array('font-family'),
                        ),
                    ),
                    // Button Font weight.
                    'button_font_weight' => array(
                        'title' => __('Button font weight', 'decorator-woocommerce-email-customizer'),
                        'control_type' => 'range_value',
                        'section' => 'button_styles',
                        'default' => self::get_default_value('button_font_weight'),
                        'live_method' => 'css',
                        'selectors' => array(
                            'a.wt_template_button' => array('font-weight'),
                            'a.button' => array('font-weight'),
                        ),
                        'input_attrs' => array(
                            'step' => 100,
                            'min' => 100,
                            'max' => 900,
                        ),
                    ),
                    // Button Background Color.
                    'button_bg_color' => array(
                        'title' => __('Button background color', 'decorator-woocommerce-email-customizer'),
                        'section' => 'button_styles',
                        'default' => self::get_default_value('button_bg_color'),
                        'live_method' => 'css',
                        'selectors' => array(
                            'a.wt_template_button' => array('background'),
                            'a.button' => array('background'),
                        ),
                        'control_type' => 'color',
                    ),
                    // Button Top and bottom Padding.
                    'button_top_bottom_padding' => array(
                        'title' => __('Top and bottom padding', 'decorator-woocommerce-email-customizer'),
                        'control_type' => 'range_value',
                        'section' => 'button_styles',
                        'default' => self::get_default_value('button_top_bottom_padding'),
                        'live_method' => 'css',
                        'selectors' => array(
                            'a.wt_template_button' => array('padding-top', 'padding-bottom'),
                            'a.button' => array('padding-top', 'padding-bottom'),
                        ),
                        'input_attrs' => array(
                            'step' => 1,
                            'min' => 0,
                            'max' => 150,
                            'suffix' => 'px',
                        ),
                    ),
                    // Button Left and Right Padding.
                    'button_left_right_padding' => array(
                        'title' => __('Left and right padding', 'decorator-woocommerce-email-customizer'),
                        'control_type' => 'range_value',
                        'section' => 'button_styles',
                        'default' => self::get_default_value('button_left_right_padding'),
                        'live_method' => 'css',
                        'selectors' => array(
                            'a.wt_template_button' => array('padding-left', 'padding-right'),
                            'a.button' => array('padding-left', 'padding-right'),
                        ),
                        'input_attrs' => array(
                            'step' => 1,
                            'min' => 0,
                            'max' => 150,
                            'suffix' => 'px',
                        ),
                    ),
                    // Button Border Width.
                    'button_border_width' => array(
                        'title' => __('Button border width', 'decorator-woocommerce-email-customizer'),
                        'control_type' => 'range_value',
                        'section' => 'button_styles',
                        'default' => self::get_default_value('button_border_width'),
                        'live_method' => 'css',
                        'selectors' => array(
                            'a.wt_template_button' => array('border-width'),
                            'a.button' => array('border-width'),
                        ),
                        'input_attrs' => array(
                            'step' => 1,
                            'min' => 0,
                            'max' => 10,
                            'suffix' => 'px',
                        ),
                    ),
                    // Border radius
                    'button_border_radius' => array(
                        'title' => __('Border radius', 'decorator-woocommerce-email-customizer'),
                        'control_type' => 'range_value',
                        'section' => 'button_styles',
                        'default' => self::get_default_value('button_border_radius'),
                        'live_method' => 'css',
                        'selectors' => array(
                            'a.wt_template_button' => array('border-radius'),
                            'a.button' => array('border-radius'),
                        ),
                        'input_attrs' => array(
                            'step' => 1,
                            'min' => 0,
                            'max' => 100,
                            'suffix' => 'px',
                        ),
                    ),
                    // Button Bordercolor
                    'button_border_color' => array(
                        'title' => __('Button border color', 'decorator-woocommerce-email-customizer'),
                        'section' => 'button_styles',
                        'control_type' => 'color',
                        'default' => self::get_default_value('button_border_color'),
                        'live_method' => 'css',
                        'selectors' => array(
                            'a.wt_template_button' => array('border-color'),
                            'a.button' => array('border-color'),
                        ),
                    ),
                    // Footer Social Title Color
//                    'social_links_icon_color' => array(
//                        'title' => __('Icon color', 'decorator-woocommerce-email-customizer'),
//                        'section' => 'social_links',
//                        'default' => self::get_default_value('social_links_icon_color'),
//                        'live_method' => 'css',
//                        'type' => 'select',
//                        'choices' => array(
//                            'black' => __('Black', 'decorator-woocommerce-email-customizer'),
//                            'white' => __('White', 'decorator-woocommerce-email-customizer'),
//                            'gray' => __('Gray', 'decorator-woocommerce-email-customizer'),
//                        ),
//                        'selectors' => array(
//                            '#template_footer .wt-social-link-icon' => array('color'),
//                        ),
//                        //'control_type' => 'color',
//                    ),
                    // Footer Social Title Color
                    'social_links_title_color' => array(
                        'title' => __('Title color', 'decorator-woocommerce-email-customizer'),
                        'section' => 'social_links',
                        'default' => self::get_default_value('social_links_title_color'),
                        'live_method' => 'css',
                        'selectors' => array(
                            '#template_footer .wt-social-link-title' => array('color'),
                        ),
                        'control_type' => 'color',
                    ),

                    // Footer Social Title Font size
                    'social_links_title_size' => array(
                        'title' => __('Title font size', 'decorator-woocommerce-email-customizer'),
                        'control_type' => 'range_value',
                        'section' => 'social_links',
                        'default' => self::get_default_value('social_links_title_size'),
                        'live_method' => 'css',
                        'selectors' => array(
                            '#template_footer .wt-social-link-title' => array('font-size'),
                        ),
                        'input_attrs' => array(
                            'step' => 1,
                            'min' => 8,
                            'max' => 30,
                            'suffix' => 'px',
                        ),
                    ),
                    // Footer Social Title Font family
                    'social_links_title_font_family' => array(
                        'title' => __('Title font family', 'decorator-woocommerce-email-customizer'),
                        'section' => 'social_links',
                        'default' => self::get_default_value('social_links_title_font_family'),
                        'live_method' => 'css',
                        'type' => 'select',
                        'choices' => self::get_font_families(),
                        'selectors' => array(
                            '#template_footer a.wt-footer-social-links' => array('font-family'),
                        ),
                    ),
                    // Footer Social Title Font weight
                    'social_links_title_font_weight' => array(
                        'title' => __('Title font weight', 'decorator-woocommerce-email-customizer'),
                        'control_type' => 'range_value',
                        'section' => 'social_links',
                        'default' => self::get_default_value('social_links_title_font_weight'),
                        'live_method' => 'css',
                        'selectors' => array(
                            '#template_footer .wt-social-link-title' => array('font-weight'),
                        ),
                        'input_attrs' => array(
                            'step' => 100,
                            'min' => 100,
                            'max' => 900,
                        ),
                    ),
                    // Footer Text align
                    'social_links_align' => array(
                        'title' => __('Icon align', 'decorator-woocommerce-email-customizer'),
                        'section' => 'social_links',
                        'default' => RP_Decorator_Settings::get_default_value('social_links_align'),
                        'live_method' => 'css',
                        'type' => 'select',
                        'choices' => RP_Decorator_Settings::get_text_aligns(),
                        'selectors' => array(
                            '#template_footer #wt_social_footer' => array('margin'),
                        ),
                    ),
                    // Footer Social Top Padding
                    'social_links_top_padding' => array(
                        'title' => __('Top padding', 'decorator-woocommerce-email-customizer'),
                        'control_type' => 'range_value',
                        'section' => 'social_links',
                        'default' => self::get_default_value('social_links_top_padding'),
                        'live_method' => 'css',
                        'selectors' => array(
                            '#template_footer #wt_social_footer td' => array('padding-top'),
                        ),
                        'input_attrs' => array(
                            'step' => 1,
                            'min' => 0,
                            'max' => 150,
                            'suffix' => 'px',
                        ),
                    ),
                    // Footer Social Bottom Padding
                    'social_links_bottom_padding' => array(
                        'title' => __('Bottom padding', 'decorator-woocommerce-email-customizer'),
                        'control_type' => 'range_value',
                        'section' => 'social_links',
                        'default' => self::get_default_value('social_links_bottom_padding'),
                        'live_method' => 'css',
                        'selectors' => array(
                            '#template_footer #wt_social_footer td' => array('padding-bottom'),
                        ),
                        'input_attrs' => array(
                            'step' => 1,
                            'min' => 0,
                            'max' => 150,
                            'suffix' => 'px',
                        ),
                    ),
                    // Footer Social Bottom Padding
                    'social_links_left_padding' => array(
                        'title' => __('Left padding', 'decorator-woocommerce-email-customizer'),
                        'control_type' => 'range_value',
                        'section' => 'social_links',
                        'default' => self::get_default_value('social_links_left_padding'),
                        'live_method' => 'css',
                        'selectors' => array(
                            '#template_footer #wt_social_footer td' => array('padding-left'),
                        ),
                        'input_attrs' => array(
                            'step' => 1,
                            'min' => 0,
                            'max' => 50,
                            'suffix' => 'px',
                        ),
                    ),
                    // Footer Social Bottom Padding
                    'social_links_right_padding' => array(
                        'title' => __('Right padding', 'decorator-woocommerce-email-customizer'),
                        'control_type' => 'range_value',
                        'section' => 'social_links',
                        'default' => self::get_default_value('social_links_right_padding'),
                        'live_method' => 'css',
                        'selectors' => array(
                            '#template_footer #wt_social_footer td' => array('padding-right'),
                        ),
                        'input_attrs' => array(
                            'step' => 1,
                            'min' => 0,
                            'max' => 50,
                            'suffix' => 'px',
                        ),
                    ),
                );
            }

            // Return settings
            return self::$settings;
        }

        /**
         * Get default values
         *
         * @access public
         * @return array
         */
        public static function get_default_values() {
            $wt_custom_style = RP_Decorator_Customizer::$wt_template_type;
            if (empty($wt_custom_style)) {
                $wt_custom_style = RP_Decorator_Customizer::wt_get_current_template();
            }
            $stored_value = RP_Decorator_Customizer::get_stored_value('preview_order_id', 'mockup', $wt_custom_style, '');
            // Define default values
            if (self::$default_values === null) {
                self::$default_values = array(
                    'email_type' => get_option('wt_decorator_last_selected_template') && array_key_exists(get_option('wt_decorator_last_selected_template'), RP_Decorator_Preview::get_email_types()) ? get_option('wt_decorator_last_selected_template') : 'new_order',
                    'preview_order_id' => $stored_value ? $stored_value: 'mockup',
                    'background_color' => '#f5f5f5',
                    'email_background_color' => '#fdfdfd',
                    'header_background_color' => get_option('woocommerce_email_base_color') ? get_option('woocommerce_email_base_color') : '#96588a',
                    'header_text_align' => is_rtl() ? 'right' : 'left',
                    'subtitle_text_align' => is_rtl() ? 'right' : 'left',
                    'header_padding_top_bottom' => '36',
                    'header_padding_left_right' => '48',
                    'text_color' => '#737373',
                    'header_show' => 'true',
                    'order_items_show' => 'true', 
                    'billing_address_show' => 'true',
                    'shipping_address_show' => 'true',
                    'custom_css'=> '',
                    'footer_show' => 'true', 
                    'font_family' => 'helvetica',
                    'font_size' => '14',
                    'link_color' => '#557da1',
                    'email_padding' => '70',
                    'content_padding_right' => '48',
                    'content_padding_left' => '48',
                    'content_padding_top' => '48',
                    'content_padding_bottom' => '48',
                    'email_width' => '600',
                    'border_radius' => '3',
                    'shadow' => '4',
                    'heading_font_size' => '30',
                    'heading_font_family' => 'helvetica',
                    'heading_color' => '#ffffff',
                    'heading_font_weight' => '300',
                    'heading_font_style' => 'normal',
                    'heading_text_decoration' => 'normal',
                    'heading_line_height' => '1',
                    'footer_top_bottom_padding' => '24',
                    'footer_left_right_padding' => '0',
                    'footer_text_align' => 'center',
                    'social_links_align'  => 'center',
                    'footer_font_size' => '12',
                    'footer_font_family' => 'helvetica',
                    'footer_color' => '#8a8a8a',
                    'footer_link_color' => get_option('woocommerce_email_base_color') ? get_option('woocommerce_email_base_color') : '#557da1',
                    'footer_border_color' => '#f5f5f5',
                    'footer_font_weight' => '400',
                    'h1_font_size' => '24',
                    'h1_font_family' => 'helvetica',
                    'h1_color' => '#557da1',
                    'h1_font_weight' => '700',
                    'h1_separator_style' => 'none',
                    'h1_separator_width' => '1',
                    'h1_separator_color' => '#e4e4e4',
                    'h2_font_size' => '18',
                    'h2_font_family' => 'helvetica',
                    'h2_color' => get_option('woocommerce_email_base_color') ? get_option('woocommerce_email_base_color') : '#557da1',
                    'h2_font_weight' => '700',
                    'h2_separator_style' => 'none',
                    'h2_separator_width' => '1',
                    'h2_separator_color' => get_option('woocommerce_email_base_color') ? get_option('woocommerce_email_base_color') : '#e4e4e4',
                    'h3_font_size' => '16',
                    'h3_font_family' => 'helvetica',
                    'h3_color' => '#557da1',
                    'h3_font_weight' => '700',
                    'h3_separator_style' => 'none',
                    'h3_separator_width' => '1',
                    'h3_separator_color' => '#e4e4e4',
                    'h4_font_size' => '14',
                    'h4_font_family' => 'helvetica',
                    'h4_color' => '#557da1',
                    'h4_font_weight' => '700',
                    'h4_separator_style' => 'none',
                    'h4_separator_width' => '1',
                    'h4_separator_color' => '#e4e4e4',
                    'h5_font_size' => '12',
                    'h5_font_family' => 'helvetica',
                    'h5_color' => '#557da1',
                    'h5_font_weight' => '700',
                    'h5_separator_style' => 'none',
                    'h5_separator_width' => '1',
                    'h5_separator_color' => '#e4e4e4',
                    'h6_font_size' => '10',
                    'h6_font_family' => 'helvetica',
                    'h6_color' => '#557da1',
                    'h6_font_weight' => '700',
                    'h6_separator_style' => 'none',
                    'h6_separator_width' => '1',
                    'h6_separator_color' => '#e4e4e4',
                    'items_table_border_width' => '1',
                    'container_border_width'  => '0',
                    'items_table_border_color' => '#e4e4e4',
                    'items_table_separator_width' => '4',
                    'items_table_separator_color' => '#e4e4e4',
                    'items_table_background_color' => '',
                    'items_table_padding' => '12',
                    'footer_content_text' => get_option('woocommerce_email_footer_text', ''),
                    'footer_background_color' => '#efefef',
                    'border_color' => '#dedede',
                    'header_image_maxwidth' => '300',
                    'header_image_maxheight' => 'auto',
                    'header_image_padding_top_bottom' => '0',
                    'header_image_background_color' => 'transparent',
                    'header_image_align' => 'center',
                    'header_image_link' => true,
                    'subtitle_placement' => 'below',
                    'header_image_placement' => 'outside',
                    'subtitle_font_size' => '18',
                    'footer_bottom_padding' =>'24',
                    'subtitle_line_height' => '1',
                    'subtitle_font_family' => 'helvetica',
                    'subtitle_font_style' => 'normal',
                    'subtitle_color' => '#ffffff',
                    'subtitle_font_weight' => '300',
                    'address_box_padding_top_bottom' => '12',
                    'address_box_padding_left_right' => '12',
                    'address_box_border_width' => '1',
                    'address_box_border_color' => '#e5e5e5',
                    'address_box_border_style' => 'solid',
                    'address_box_background_color' => '',
                    'address_box_text_color' => '#8f8f8f',
                    'address_box_text_align' => 'left',
                    'button_border_width' => '1',
                    'button_border_radius' => '4',
                    'button_border_color' => '#dedede',
                    'button_font_family' => 'helvetica',
                    'button_color' => '#ffffff',
                    'button_font_weight' => '600',
                    'button_text_align' => is_rtl() ? 'right' : 'left',
                    'button_left_right_padding' => '8',
                    'button_top_bottom_padding' => '10',
                    'button_size' => '16',
                    'button_bg_color' => '#96588a',
                    'social_links_enable' => 'normal',
                    'social_links_title_color' => '#000000',
                    'social_links_icon_color' => 'default',
                    'social_links_title_font_family' => 'helvetica',
                    'social_links_title_size' => '15',
                    'social_links_title_font_weight' => '400',
                    'social_links_top_padding' => '0',
                    'social_links_bottom_padding' => '10',
                    'social_links_left_padding' => '10',
                    'social_links_right_padding' => '0',
                    'woocommerce_waitlist_mailout_body' => __('Hi There,', 'decorator-woocommerce-email-customizer'),
                    'woocommerce_waitlist_mailout_heading' => sprintf(__('%s is now back in stock at %s', 'decorator-woocommerce-email-customizer'),'{product_title}','{site_title}'),
                    'woocommerce_waitlist_mailout_subject' => __('A product you are waiting for is back in stock', 'decorator-woocommerce-email-customizer'),
                    'new_renewal_order_heading' => __('New customer order', 'decorator-woocommerce-email-customizer'),
                    'new_renewal_order_subject' => sprintf(__('[%s] New customer order (%s) - %s', 'decorator-woocommerce-email-customizer'),'{site_title}','{order_number}','{order_date}'),
                    'new_renewal_order_body' => sprintf(__('You have received a subscription renewal order from %s. Their order is as follows:', 'decorator-woocommerce-email-customizer'),'{customer_full_name}'),
                    'customer_processing_renewal_order_heading' => __('Thank you for your order', 'decorator-woocommerce-email-customizer'),
                    'customer_processing_renewal_order_subject' => sprintf(__('Your %s order receipt from %s', 'decorator-woocommerce-email-customizer'),'{site_title}','{order_date}'),
                    'customer_processing_renewal_order_body' => __('Your subscription renewal order has been received and is now being processed. Your order details are shown below for your reference:', 'decorator-woocommerce-email-customizer'),
                    'customer_completed_renewal_order_heading' => __('Your order is complete', 'decorator-woocommerce-email-customizer'),
                    'customer_completed_renewal_order_subject' =>sprintf(__('Your %s order from %s is complete', 'decorator-woocommerce-email-customizer'),'{site_title}','{order_date}'),
                    'customer_completed_renewal_order_body' => sprintf(__('Hi there. Your subscription renewal order with %s has been completed. Your order details are shown below for your reference:', 'decorator-woocommerce-email-customizer'),'{site_title}'),
                    'customer_completed_switch_order_heading' => __('Your order is complete', 'decorator-woocommerce-email-customizer'),
                    'customer_completed_switch_order_subject' => sprintf(__('Your %s order from %s is complete', 'decorator-woocommerce-email-customizer'),'{site_title}','{order_date}'),
                    'customer_completed_switch_order_body' => sprintf(__('Hi there. You have successfully changed your subscription items on %s. Your new order and subscription details are shown below for your reference:', 'decorator-woocommerce-email-customizer'),'{site_title}'),
                    'customer_renewal_invoice_heading' => sprintf(__('Invoice for order %s', 'decorator-woocommerce-email-customizer'),'{order_number}'),
                    'customer_renewal_invoice_subject' => sprintf(__('Invoice for order %s', 'decorator-woocommerce-email-customizer'),'{order_number}'),
                    'customer_renewal_invoice_body' => sprintf(__('An invoice has been created for you to renew your subscription with %s. To pay for this invoice please use the following link: %s', 'decorator-woocommerce-email-customizer'),'{site_title}','{invoice_pay_link}'),
                    'customer_renewal_invoice_btn_switch' => false,
                    'customer_renewal_invoice_body_failed' =>sprintf(__('The automatic payment to renew your subscription with %s has failed. To reactivate the subscription, please login and pay for the renewal from your account page: %s', 'decorator-woocommerce-email-customizer'),'{site_title}','{invoice_pay_link}'),
                    'cancelled_subscription_heading' => __('Subscription Cancelled', 'decorator-woocommerce-email-customizer'),
                    'cancelled_subscription_subject' => sprintf(__('[%s] Subscription Cancelled', 'decorator-woocommerce-email-customizer'),'{site_title}'),
                    'cancelled_subscription_body' => sprintf(__('A subscription belonging to %s has been cancelled. Their subscription\'s details are as follows:', 'decorator-woocommerce-email-customizer'),'{customer_full_name}'),
                    'customer_payment_retry_heading' => sprintf(__('Automatic payment failed for order %s', 'decorator-woocommerce-email-customizer'),'{order_number}'),
                    'customer_payment_retry_subject' => sprintf(__('Automatic payment failed for %s, we will retry %s', 'decorator-woocommerce-email-customizer'),'{order_number}','{retry_time}'),
                    'customer_payment_retry_body' => '',
                    'customer_payment_retry_override' => false,
                    'customer_payment_retry_btn_switch' => false,
                    'admin_payment_retry_heading' => __('Automatic renewal payment failed', 'decorator-woocommerce-email-customizer'),
                    'admin_payment_retry_subject' => sprintf(__('[%s] Automatic payment failed for %s, retry scheduled to run %s', 'decorator-woocommerce-email-customizer'),'{site_title}','{order_number}','{retry_time}'),
                    'admin_payment_retry_body' => '',
                    'admin_payment_retry_override' => false,
                    'billing_address_subtitle' => __('Billing address', 'decorator-woocommerce-email-customizer'),
                    'shipping_address_subtitle' => __('Shipping address', 'decorator-woocommerce-email-customizer'),
                    'new_order_heading' => __('New order', 'decorator-woocommerce-email-customizer'),
                    'cancelled_order_heading' => __('Cancelled order', 'decorator-woocommerce-email-customizer'),
                    'customer_processing_order_heading' => __('Thank you for your order', 'decorator-woocommerce-email-customizer'),
                    'new_order_additional_content' => __('Congratulations on the sale!', 'decorator-woocommerce-email-customizer'),
                    'customer_processing_order_additional_content' => sprintf(__('Thanks for using %s!', 'decorator-woocommerce-email-customizer'),'{site_address}'),
                    'customer_completed_order_additional_content' => __('Thanks for shopping with us.', 'decorator-woocommerce-email-customizer'),
                    'customer_refunded_order_additional_content' => __('We hope to see you again soon.', 'decorator-woocommerce-email-customizer'),
                    'customer_on_hold_order_additional_content' => __('We look forward to fulfilling your order soon.', 'decorator-woocommerce-email-customizer'),
                    'customer_new_account_additional_content' => __('We look forward to seeing you soon.', 'decorator-woocommerce-email-customizer'),
                    'customer_reset_password_additional_content' => __('Thanks for reading.', 'decorator-woocommerce-email-customizer'),
                    'customer_completed_order_heading' => __('Your order is complete', 'decorator-woocommerce-email-customizer'),
                    'customer_refunded_order_heading_full' => sprintf(__('Order %s details', 'decorator-woocommerce-email-customizer'),'{order_number}'),
                    'customer_refunded_order_heading_partial' => __('Your order has been partially refunded', 'decorator-woocommerce-email-customizer'),
                    'customer_on_hold_order_heading' => __('Thank you for your order', 'decorator-woocommerce-email-customizer'),
                    'customer_invoice_heading' => sprintf(__('Invoice for order %s', 'decorator-woocommerce-email-customizer'),'{order_number}'),
                    'customer_invoice_heading_paid' => __('Your order details', 'decorator-woocommerce-email-customizer'),
                    'failed_order_heading' => __('Failed order', 'decorator-woocommerce-email-customizer'),
                    'customer_new_account_heading' => sprintf(__('Welcome to %s', 'decorator-woocommerce-email-customizer'),'{site_title}'),
                    'customer_note_heading' => __('A note has been added to your order', 'decorator-woocommerce-email-customizer'),
                    'customer_reset_password_heading' => __('Password reset instructions', 'decorator-woocommerce-email-customizer'),
                    'customer_reset_password_btn_switch' => false,
                    'body_text_enable_switch' => true,
                    'new_order_subject' => sprintf(__('[%s] New order (%s) - %s', 'decorator-woocommerce-email-customizer'),'{site_title}','{order_number}','{order_date}'),
                    'cancelled_order_subject' => sprintf(__('[%s] Cancelled order (%s)', 'decorator-woocommerce-email-customizer'),'{site_title}','{order_number}'),
                    'customer_processing_order_subject' => sprintf(__('Your %s order receipt from %s', 'decorator-woocommerce-email-customizer'),'{site_title}','{order_date}'),
                    'customer_completed_order_subject' => sprintf(__('Your %s order from %s is complete', 'decorator-woocommerce-email-customizer'),'{site_title}','{order_date}'),
                    'customer_refunded_order_subject_full' => sprintf(__('Your %s order from %s has been refunded', 'decorator-woocommerce-email-customizer'),'{site_title}','{order_date}'),
                    'customer_refunded_order_subject_partial' => sprintf(__('Your %s order from %s has been partially refunded', 'decorator-woocommerce-email-customizer'),'{site_title}','{order_date}'),
                    'customer_on_hold_order_subject' => sprintf(__('Your %s order receipt from %s', 'decorator-woocommerce-email-customizer'),'{site_title}','{order_date}'),
                    'customer_invoice_subject' => sprintf(__('Invoice for order %s', 'decorator-woocommerce-email-customizer'),'{order_number}'),
                    'customer_invoice_subject_paid' => sprintf(__('Your %s order from %s', 'decorator-woocommerce-email-customizer'),'{site_title}','{order_date}'),
                    'failed_order_subject' => sprintf(__('[%s] Failed order (%s)', 'decorator-woocommerce-email-customizer'),'{site_title}','{order_number}'),
                    'customer_new_account_subject' => sprintf(__('Your account on %s', 'decorator-woocommerce-email-customizer'),'{site_title}'),
                    'customer_note_subject' => sprintf(__('Note added to your %s order from %s', 'decorator-woocommerce-email-customizer'),'{site_title}','{order_date}'),
                    'customer_reset_password_subject' => sprintf(__('Password reset for %s', 'decorator-woocommerce-email-customizer'),'{site_title}'),
                    'new_order_body' => sprintf(__('You’ve received the following order from %s:', 'decorator-woocommerce-email-customizer'),'{customer_full_name}'),
                    'cancelled_order_body' => sprintf(__('Notification to let you know &mdash; order %s belonging to %s has been cancelled:', 'decorator-woocommerce-email-customizer'),'#{order_number}','{customer_full_name}'),
                    'customer_processing_order_body' => sprintf(__('Hi %s,
                        
Just to let you know &mdash; we\'ve received your order %s, and it is now being processed:', 'decorator-woocommerce-email-customizer'),'{customer_first_name}','#{order_number}'),
                    'customer_completed_order_body' => sprintf(__('Hi %s,
                        
We have finished processing your order.', 'decorator-woocommerce-email-customizer'),'{customer_first_name}'),
                    'customer_refunded_order_switch' => true,
                    'customer_refunded_order_body_full' => sprintf(__('Hi %s,
                        
Your order on %s has been refunded. There are more details below for your reference:', 'decorator-woocommerce-email-customizer'),'{customer_first_name}','{site_title}'),
                    'customer_refunded_order_body_partial' => sprintf(__('Hi %s,
                        
Your order on %s has been partially refunded. There are more details below for your reference:', 'decorator-woocommerce-email-customizer'),'{customer_first_name}','{site_title}'),
                    'customer_on_hold_order_body' =>  sprintf(__('Hi %s,
                        
Thanks for your order. It’s on-hold until we confirm that payment has been received. In the meantime, here’s a reminder of what you ordered:', 'decorator-woocommerce-email-customizer'),'{customer_first_name}'),
                    'customer_invoice_switch' => true,
                    'customer_invoice_btn_switch' => false,
                    'customer_invoice_body' => sprintf(__('Hi %s,
                        
An order has been created for you on %s. Your invoice is below, with a link to make payment when you’re ready: %s ', 'decorator-woocommerce-email-customizer'),'{customer_first_name}','{site_title}','{invoice_pay_link}'),
                    'customer_invoice_body_paid' => sprintf(__('Hi %s,
                        
An order has been created for you on %s. Your invoice is below:', 'decorator-woocommerce-email-customizer'),'{customer_first_name}','{site_title}'),
                    'expired_subscription_body' => sprintf(__('A subscription belonging to %s has expired. Their subscription\'s details are as follows:', 'decorator-woocommerce-email-customizer'),'{customer_full_name}'),
                    'order_items_image' => 'normal',
                    'order_items_image_size' => '100x50',
                    'order_items_sku' => 'normal',
                    'failed_order_body' => sprintf(__('Payment for order %s from %s has failed. The order was as follows:', 'decorator-woocommerce-email-customizer'),'#{order_number}','{customer_full_name}'),
                    'customer_new_account_btn_switch' => false,
                    'customer_new_account_account_section' => true,
                    'customer_new_account_body' => sprintf(__('Hi %s,
                        
Thanks for creating an account on %s. Your username is %s', 'decorator-woocommerce-email-customizer'),'{customer_first_name}','{site_title}','{customer_username}'),
                    'customer_note_body' => sprintf(__('Hi %s,
                        
The following note has been added to your order:', 'decorator-woocommerce-email-customizer'),'{customer_first_name}'),
                    'customer_reset_password_body' => sprintf(__('Hi %s,
                            
                            Someone has requested a new password for the following account on %s:

                                                                                        Username: %s

                                                                                        If you didn\'t make this request, just ignore this email. If you\'d like to proceed:', 'decorator-woocommerce-email-customizer'
                    ),'{customer_first_name}','{site_title}','{customer_username}'),
                    'wt_smart_coupon_abandonment_coupon_email_body' => __(
                            'Hi there,
                                
                                                                                        Did you forget something? You\'ve left behind some products in your cart. Grab them before they go out of stock at an additional discount of ${coupon_amount}. Hurry up!!

                                                                                        If this was a mistake, just ignore this email and nothing will happen.

                                                                                        To reset your password, visit the following address:', 'decorator-woocommerce-email-customizer'
                    ),
                    'wt_smart_coupon_gift_body' => __(
                            'Hi there,

Congratulations! You\'ve got a coupon! To redeem your discount, use following coupon code during checkout.', 'decorator-woocommerce-email-customizer'
                    ),
                    'wt_smart_coupon_signup_coupon_email_body' => sprintf(__(
                            'Hi there,

Thanks for signing up with us.! We would like to welcome you to our %s with a gift.

Use the following coupon code during your next purchase to avail the discount.', 'decorator-woocommerce-email-customizer'
                    ),'{blog_url}'),
                    'wt_smart_coupon_body' => sprintf(__(
                            'Hi there,

Congratulations! You\'ve got a coupon! To redeem your discount use coupon code %s during checkout.

You\'ve got a coupon!', 'decorator-woocommerce-email-customizer'
                    ),'{coupon_code}'),
                    'wt_smart_coupon_heading' => __('You\'ve got a coupon!', 'decorator-woocommerce-email-customizer'),
                    'wt_smart_coupon_signup_coupon_email_heading' => __('Welcome aboard! You\'ve got a gift!', 'decorator-woocommerce-email-customizer'),
                    'customer_new_account_activation_heading' => sprintf(__('Account activation %s', 'decorator-woocommerce-email-customizer'),'{site_title}'),
                    'customer_paid_for_order_heading' => __('Payment received', 'decorator-woocommerce-email-customizer'),
                    'customer_revocation_heading' => __('Your revocation', 'decorator-woocommerce-email-customizer'),
                    'customer_revocation_body' => __('By sending you this email we confirm receiving your withdrawal. Please review your data. ', 'decorator-woocommerce-email-customizer'),
                    'suspended_subscription_body' => sprintf(__('A subscription belonging to %s has been suspended by the user. Their subscription\'s details are as follows: ', 'decorator-woocommerce-email-customizer'),'{customer_full_name}'),
                    'suspended_subscription_heading' => __('Subscription Suspended', 'decorator-woocommerce-email-customizer'),
                    'wt_smart_coupon_abandonment_coupon_email_heading' => __('Your favourites are runnung out of stock!', 'decorator-woocommerce-email-customizer'),
                    'wt_smart_coupon_gift_heading' => __('You\'ve got a gift!', 'decorator-woocommerce-email-customizer'),
                    'expired_subscription_heading' => __('Subscription Expired', 'decorator-woocommerce-email-customizer'),
                    'WC_Memberships_User_Membership_Ended_Email_heading' => sprintf(__('Renew your %s', 'decorator-woocommerce-email-customizer'),'{membership_plan}'),
                    'WC_Memberships_User_Membership_Ended_Email_subject' => sprintf(__('Your %s membership has expired', 'decorator-woocommerce-email-customizer'),'{site_title}'),
                    'WC_Memberships_User_Membership_Activated_Email_heading' => sprintf(__('You can now access %s', 'decorator-woocommerce-email-customizer'),'{membership_plan}'),
                    'WC_Memberships_User_Membership_Activated_Email_subject' => sprintf(__('Your %s membership is now active!', 'decorator-woocommerce-email-customizer'),'{site_title}'),
                    'WC_Memberships_User_Membership_Ending_Soon_Email_heading' => sprintf(__('An update about your %s', 'decorator-woocommerce-email-customizer'),'{membership_plan}'),
                    'WC_Memberships_User_Membership_Ending_Soon_Email_subject' => sprintf(__('Your %s membership ends soon!', 'decorator-woocommerce-email-customizer'),'{site_title}'),
                    'WC_Memberships_User_Membership_Note_Email_heading' => __('A note has been added about your membership', 'decorator-woocommerce-email-customizer'),
                    'WC_Memberships_User_Membership_Note_Email_subject' => sprintf(__('Note added to your %s membership', 'decorator-woocommerce-email-customizer'),'{site_title}'),
                    'WC_Memberships_User_Membership_Renewal_Reminder_Email_heading' => sprintf(__('You can renew your %s', 'decorator-woocommerce-email-customizer'),'{membership_plan}'),
                    'WC_Memberships_User_Membership_Renewal_Reminder_Email_subject' => sprintf(__('Renew your %s membership!', 'decorator-woocommerce-email-customizer'),'{site_title}'),
                    'customer_delivered_order_heading' => __('Thanks for shopping with us', 'decorator-woocommerce-email-customizer'),
                );
            }

            // Return default values
            return self::$default_values;
        }

        /**
         * Get default values
         *
         * @access public
         * @param string $key
         * @return string
         */
        public static function get_default_value($key) {
            $default_values = RP_Decorator_Settings::get_default_values();
            if(strstr($key, 'image_link_btn_switch')){
                $key_data = explode('#', $key);
                $key = $key_data[0];
                     // Get all stored values
                if($key){
                    $stored_values = (array) get_option('wt_decorator_custom_styles', array());
                    if (isset($stored_values[$key]) && !empty($stored_values[$key])) {
                        $stored = $stored_values[$key];
                    } 

                    $drafted_values = get_option('wt_decorator_custom_styles_in_draft', array());
                    $scheduled_values = get_option('wt_decorator_custom_styles_scheduled', array());
                    if (isset($drafted_values[$key]) && !empty($drafted_values[$key])) {
                        $stored = $drafted_values[$key];
                    } elseif (isset($scheduled_values[$key]) && !empty($scheduled_values[$key])) {
                        $stored = $scheduled_values[$key];
                    }

                    // Check if value exists in stored values array
                    if (!empty($stored) && isset($stored['image_link'])) {                
                        return $stored['image_link'];
                    }
                }
            }else{
                $set = FALSE;
                if($key){
                    $wt_custom_style = RP_Decorator_Customizer::$wt_template_type;
                    if (empty($wt_custom_style)) {

                        $wt_custom_style = RP_Decorator_Customizer::wt_get_current_template();
                    }
                    $stored_values = (array) get_option('wt_decorator_custom_styles', array());
                    if (isset($stored_values[$wt_custom_style][$key]) && !empty($stored_values[$wt_custom_style][$key])) {
                        $stored = $stored_values[$wt_custom_style][$key];
                        $set = TRUE;
                    } 
                      
                    $drafted_values = get_option('wt_decorator_custom_styles_in_draft', array());
                    $scheduled_values = get_option('wt_decorator_custom_styles_scheduled', array());
                    if (isset($drafted_values[$wt_custom_style][$key]) && !empty($drafted_values[$wt_custom_style][$key])) {
                        $stored = $drafted_values[$wt_custom_style][$key];
                        $set = TRUE;
                    } elseif (isset($scheduled_values[$wt_custom_style][$key]) && !empty($scheduled_values[$wt_custom_style][$key])) {
                        $stored = $scheduled_values[$wt_custom_style][$key];
                        $set = TRUE;
                    }
                     if((($key == 'header_show' && $set == TRUE) || ($key == 'footer_show' && $set == TRUE) || ($key == 'billing_address_show' && $set == TRUE) || ($key == 'shipping_address_show' && $set == TRUE) || ($key == 'order_items_show' && $set == TRUE)) && empty($stored)){
                         return FALSE;
                     }
                    // Check if value exists in stored values array
                    if (!empty($stored) && isset($stored)) {                
                        return $stored;
                    }
                }
            }

            // Check if such key exists and return default value
            return isset($default_values[$key]) ? $default_values[$key] : '';
        }

        /**
         * Get border styles
         *
         * @access public
         * @return array
         */
        public static function get_border_styles() {
            return array(
                'none' => __('none', 'decorator-woocommerce-email-customizer'),
                'hidden' => __('hidden', 'decorator-woocommerce-email-customizer'),
                'dotted' => __('dotted', 'decorator-woocommerce-email-customizer'),
                'dashed' => __('dashed', 'decorator-woocommerce-email-customizer'),
                'solid' => __('solid', 'decorator-woocommerce-email-customizer'),
                'double' => __('double', 'decorator-woocommerce-email-customizer'),
                'groove' => __('groove', 'decorator-woocommerce-email-customizer'),
                'ridge' => __('ridge', 'decorator-woocommerce-email-customizer'),
                'inset' => __('inset', 'decorator-woocommerce-email-customizer'),
                'outset' => __('outset', 'decorator-woocommerce-email-customizer'),
            );
        }

        /**
         * Get text align options
         *
         * @access public
         * @return array
         */
        public static function get_text_aligns() {
            return array(
                'left' => __('left', 'decorator-woocommerce-email-customizer'),
                'center' => __('center', 'decorator-woocommerce-email-customizer'),
                'right' => __('right', 'decorator-woocommerce-email-customizer'),
                'justify' => __('justify', 'decorator-woocommerce-email-customizer'),
            );
        }

        /**
         * Get font families
         *
         * @access public
         * @return array
         */
        public static function get_font_families() {
            return array(
                'arial' => __('Arial', 'decorator-woocommerce-email-customizer'),
                'arial_black' => __('Arial black', 'decorator-woocommerce-email-customizer'),
                'courier' => __('Courier new', 'decorator-woocommerce-email-customizer'),
                'georgia' => __('Georgia', 'decorator-woocommerce-email-customizer'),
                'helvetica' => __('Helvetica', 'decorator-woocommerce-email-customizer'),
                'impact' => __('Impact', 'decorator-woocommerce-email-customizer'),
                'lucida' => __('Lucida', 'decorator-woocommerce-email-customizer'),
                'palatino' => __('Palatino', 'decorator-woocommerce-email-customizer'),
                'tahoma' => __('Tahoma', 'decorator-woocommerce-email-customizer'),
                'times' => __('Times new roman', 'decorator-woocommerce-email-customizer'),
                'verdana' => __('Verdana', 'decorator-woocommerce-email-customizer'),
            );
        }

        /**
         * Get Order Ids
         *
         * @access public
         * @return array
         */
        public static function get_order_ids() {

            if (is_null(self::$order_ids)) {
                $order_array = array();
                $order_array['mockup'] = __('Mockup order', 'decorator-woocommerce-email-customizer');
                $orders = new WP_Query(
                        array(
                    'post_type' => 'shop_order',
                    'post_status' => array_keys(wc_get_order_statuses()),
                    'posts_per_page' => 10,
                    'order' => 'ASC'
                        )
                );
                if ($orders->posts) {
                    foreach ($orders->posts as $order) {
                        // Get order object.
                        $order_object = new WC_Order($order->ID);
                        $order_array[$order_object->get_id()] = $order_object->get_id() . ' - ' . $order_object->get_billing_first_name() . ' ' . $order_object->get_billing_last_name();
                    }
                }
                self::$order_ids = $order_array;
            }
            return self::$order_ids;
        }

        /**
         * Get woocommerce settings that the plugin will allow editing of
         *
         * @access public
         * @return array
         */
        public static function wt_get_custom_text_edit_settings($wt_custom_style = null) {

            if (is_null(self::$custom_settings_for_textedit)) {
                $email_text = array();
                foreach (RP_Decorator_Preview::get_email_types() as $key => $value) {

                    $email_text['social_links_enable'] = array(
                        'title' => __('Set social links', 'decorator-woocommerce-email-customizer'),
                        'section' => 'social_links',
                        'priority' => 1,
                        'transport' => 'refresh',
                        'default' => self::get_default_value('social_links_enable'),
                        'transport' => 'refresh',
                        'type' => 'select',
                        'choices' => array(
                            'normal' => __('Do not show', 'decorator-woocommerce-email-customizer'),
                            'above' => __('Show above footer text', 'decorator-woocommerce-email-customizer'),
                            'bellow' => __('Show below footer text', 'decorator-woocommerce-email-customizer'),
                        ),
                    );

                    // Email preview switch.
                    $email_text['wt_decorator_' . $key . '_image_link_btn_switch'] = array(
                        'title' => __('Add link to the website on header image', 'decorator-woocommerce-email-customizer'),
                        'control_type' => 'toggleswitch',
                        'section' => 'header_image',
                        'transport' => 'refresh',
                        'default' => self::get_default_value($key.'#image_link_btn_switch'),
                        'original' => '',
                    );


                    // Footer social repeater
                    $email_text['footer_social_repeater'] = array(
                        'title' => __('Add social links to footer', 'decorator-woocommerce-email-customizer'),
                        'control_type' => 'repeater',
                        'priority' => 2,
                        'transport' => 'refresh',
                        'section' => 'social_links',
                        'default' => self::get_default_value('social_options'),
                        'customizer_repeater_image_control' => true,
                        'customizer_repeater_icon_control' => true,
                        'customizer_repeater_icon_color' => true,
                        'customizer_repeater_title_control' => true,
                        'customizer_repeater_link_control' => true,
                        'santitize_callback' => 'customizer_repeater_sanitize',
                    );
                       $email_text['social_links_icon_color'] = array(
                        'title' => __('Icon color', 'decorator-woocommerce-email-customizer'),
                        'section' => 'social_links',
                        'priority' => 3,
                        'transport' => 'refresh',
                        'default' => self::get_default_value('social_links_icon_color'),
                        'transport' => 'refresh',
                        'type' => 'select',
                        'choices' => array(
                            'default' => __('Default', 'decorator-woocommerce-email-customizer'),
                            'black' => __('Black', 'decorator-woocommerce-email-customizer'),
                            'white' => __('White', 'decorator-woocommerce-email-customizer'),
                            'gray' => __('Gray', 'decorator-woocommerce-email-customizer'),
                        ),
                    );


                    // Email recipients Text.
                    if ('cancelled_order' == $key || 'new_order' == $key || 'failed_order' == $key) {
                        $email_text['woocommerce_' . $key . '_settings[recipient]'] = array(
                            'title' => __('Recipient(s)', 'decorator-woocommerce-email-customizer'),
                            'type' => 'text',
                            'section' => 'text_editor',
                            'priority' => 5,
                            'default' => get_option('admin_email'),
                            'wt_arg' => $key,
                            'description' => __('Enter recipients (comma separated) for this email.', 'decorator-woocommerce-email-customizer'),
                        );
                    }
                    $email_text['shortcodes'] = array(
                        'title' => __('Shortcodes', 'decorator-woocommerce-email-customizer'),
                        'section' => 'text_editor',
                        'control_type' => 'Shortcode',
                        'priority' => 50,
                        'description' => '{customer_username},{customer_email},{customer_first_name}, {customer_last_name}, {customer_full_name}, {customer_company}, {order_date}, {order_number}, {site_title}',
                    );

                    if ('customer_refunded_order' == $key) {
                        // Email Subject.
                        $email_text['woocommerce_' . $key . '_settings[subject_full]'] = array(
                            'title' => __('Full refund subject', 'decorator-woocommerce-email-customizer'),
                            'type' => 'text',
                            'section' => 'text_editor',
                            'priority' => 5,
                            'default' => '',
                            'wt_arg' => $key,
                            'input_attrs' => array(
                                'placeholder' => self::get_default_value($key . '_subject_full'),
                            ),
                        );
                        // Email Subject.
                        $email_text['woocommerce_' . $key . '_settings[subject_partial]'] = array(
                            'title' => __('Partial refund subject', 'decorator-woocommerce-email-customizer'),
                            'type' => 'text',
                            'section' => 'text_editor',
                            'priority' => 5,
                            'default' => '',
                            'wt_arg' => $key,
                            'input_attrs' => array(
                                'placeholder' => self::get_default_value($key . '_subject_partial'),
                            ),
                        );
                        // Email Header Text.
                        $email_text['woocommerce_' . $key . '_settings[heading_full]'] = array(
                            'title' => __('Full refund heading text', 'decorator-woocommerce-email-customizer'),
                            'type' => 'text',
                            'section' => 'text_editor',
                            'priority' => 5,
                            'default' => '',
                            'wt_arg' => $key,
                            'input_attrs' => array(
                                'placeholder' => self::get_default_value($key . '_heading_full'),
                            ),
                            'live_method' => 'replace',
                            'selectors' => array(
                                '#header_wrapper h1',
                            ),
                        );
                        // Email Header Text.
                        $email_text['woocommerce_' . $key . '_settings[heading_partial]'] = array(
                            'title' => __('Partial refund heading text', 'decorator-woocommerce-email-customizer'),
                            'type' => 'text',
                            'section' => 'text_editor',
                            'priority' => 5,
                            'default' => '',
                            'wt_arg' => $key,
                            'input_attrs' => array(
                                'placeholder' => self::get_default_value($key . '_heading_partial'),
                            ),
                        );
                        if (version_compare(WC_VERSION, '3.7', '>')) {
                            $email_text['woocommerce_' . $key . '_settings[additional_content]'] = array(
                                'title' => __('Additional content', 'decorator-woocommerce-email-customizer'),
                                'type' => 'textarea',
                                'section' => 'text_editor',
                                'priority' => 20,
                                'default' => self::get_default_value($key . '_additional_content'),
                                'wt_arg' => $key,
                                'input_attrs' => array(
                                    'placeholder' => self::get_default_value($key . '_additional_content'),
                                ),
                                'transport' => 'refresh',
                            );
                        }
                    } else {

                        // Email Subject.
                        $email_text['woocommerce_' . $key . '_settings[subject]'] = array(
                            'title' => __('Subject text', 'decorator-woocommerce-email-customizer'),
                            'type' => 'text',
                            'section' => 'text_editor',
                            'priority' => 5,
                            'default' => '',
                            'wt_arg' => $key,
                            'input_attrs' => array(
                                'placeholder' => self::get_default_value($key . '_subject'),
                            ),
                        );
                        if ('customer_invoice' == $key) {
                            $email_text['woocommerce_' . $key . '_settings[subject_paid]'] = array(
                                'title' => __('Subject (paid) text', 'decorator-woocommerce-email-customizer'),
                                'type' => 'text',
                                'section' => 'text_editor',
                                'priority' => 5,
                                'default' => '',
                                'wt_arg' => $key,
                                'input_attrs' => array(
                                    'placeholder' => self::get_default_value($key . '_subject_paid'),
                                ),
                            );
                        }
                        // Email Header Text.
                        $email_text['woocommerce_' . $key . '_settings[heading]'] = array(
                            'title' => __('Heading text', 'decorator-woocommerce-email-customizer'),
                            'type' => 'text',
                            'section' => 'text_editor',
                            'priority' => 5,
                            'default' => '',
                            'wt_arg' => $key,
                            'input_attrs' => array(
                                'placeholder' => self::get_default_value($key . '_heading'),
                            ),
                            'live_method' => 'replace',
                            'transport' => 'refresh',
                            'selectors' => array('#header_wrapper h1'),
                        );
                        if ('customer_invoice' == $key) {
                            $email_text['woocommerce_' . $key . '_settings[heading_paid]'] = array(
                                'title' => __('Heading (paid) text', 'decorator-woocommerce-email-customizer'),
                                'type' => 'text',
                                'section' => 'text_editor',
                                'priority' => 5,
                                'default' => '',
                                'wt_arg' => $key,
                                'input_attrs' => array(
                                    'placeholder' => self::get_default_value($key . '_heading_paid'),
                                ),
                                'transport' => 'refresh',
                            );
                        }
                        if (version_compare(WC_VERSION, '3.7', '>')) {
                            $email_text['woocommerce_' . $key . '_settings[additional_content]'] = array(
                                'title' => __('Additional content', 'decorator-woocommerce-email-customizer'),
                                'type' => 'textarea',
                                'section' => 'text_editor',
                                'priority' => 20,
                                'default' => self::get_default_value($key . '_additional_content'),
                                'wt_arg' => $key,
                                'input_attrs' => array(
                                    'placeholder' => self::get_default_value($key . '_additional_content'),
                                ),
                                'transport' => 'refresh',
                            );
                        }
                    }
                    $email_text['rp_decorator_' . $key . '_subtitle'] = array(
                        'title' => __('Subtitle text', 'decorator-woocommerce-email-customizer'),
                        'type' => 'text',
                        'section' => 'text_editor',
                        'default' => '',
                        'original' => '',
                        'transport' => 'refresh',
                        'live_method' => 'replace',
                        'selectors' => array(
                            '#header_wrapper .subtitle',
                        ),
                    );
                    $email_text['rp_decorator_' . $key . '_billing_address_subtitle'] = array(
                        'title' => __('Billing address title', 'decorator-woocommerce-email-customizer'),
                        'type' => 'text',
                        'section' => 'address_table',
                        'default' => '',
                        'original' => '',
                        'transport' => 'refresh',
                        'live_method' => 'replace',
                        'default' => self::get_default_value('billing_address_subtitle'),
                        'selectors' => array(
                            '#wt_addresses_wrapper #wt_billing_address',
                        ),
                    );
                    
                        $email_text['rp_decorator_' . $key . '_shipping_address_subtitle'] = array(
                        'title' => __('Shipping address title', 'decorator-woocommerce-email-customizer'),
                        'type' => 'text',
                        'section' => 'address_table',
                        'default' => '',
                        'original' => '',
                        'transport' => 'refresh',
                        'live_method' => 'replace',
                        'default' => self::get_default_value('shipping_address_subtitle'),
                        'selectors' => array(
                            '#wt_shipping_addresses_wrapper #wt_shipping_address',
                        ),
                    );
                        
                    if ('customer_new_account' == $key) {
                        $email_text['rp_decorator_' . $key . '_btn_switch'] = array(
                            'title' => __('Switch user account link to button', 'decorator-woocommerce-email-customizer'),
                            'control_type' => 'toggleswitch',
                            'section' => 'text_editor',
                            'transport' => 'refresh',
                            'default' => self::get_default_value($key . '_btn_switch'),
                            'original' => '',
                            'priority' => 10,
                        );
                    }
                    if ('customer_refunded_order' == $key) {
                        $email_text['rp_decorator_' . $key . '_body_text_enable_switch'] = array(
                            'title' => __('Body text visibility', 'decorator-woocommerce-email-customizer'),
                            'control_type' => 'toggleswitch',
                            'section' => 'text_editor',
                            'transport' => 'refresh',
                            'default' => self::get_default_value( 'body_text_enable_switch'),
                            'original' => '',
                        );
                        // Email Body Text
                        $email_text['rp_decorator_' . $key . '_body_full'] = array(
                            'title' => __('Body full refund text', 'decorator-woocommerce-email-customizer'),
                            'type' => 'textarea',
                            'section' => 'text_editor',
                            'default' => self::get_default_value($key . '_body_full'),
                            'original' => '',
                            'transport' => 'refresh',
                        );
                        // Email Body Text
                        $email_text['rp_decorator_' . $key . '_body_partial'] = array(
                            'title' => __('Body partial refund text', 'decorator-woocommerce-email-customizer'),
                            'type' => 'textarea',
                            'section' => 'text_editor',
                            'default' => self::get_default_value($key . '_body_partial'),
                            'original' => '',
                            'transport' => 'refresh',
                        );
                    } else if ('customer_invoice' == $key) {
                        // Email preview switch
                        $email_text['rp_decorator_' . $key . '_btn_switch'] = array(
                            'title' => __('Make "pay for this order" a button', 'decorator-woocommerce-email-customizer'),
                            'control_type' => 'toggleswitch',
                            'section' => 'text_editor',
                            'transport' => 'refresh',
                            'default' => self::get_default_value($key . '_btn_switch'),
                            'original' => '',
                        );
                        $email_text['rp_decorator_' . $key . '_body_text_enable_switch'] = array(
                            'title' => __('Body text visibility', 'decorator-woocommerce-email-customizer'),
                            'control_type' => 'toggleswitch',
                            'section' => 'text_editor',
                            'transport' => 'refresh',
                            'default' => self::get_default_value( 'body_text_enable_switch'),
                            'original' => '',
                        );
                        // Email Body Text
                        $email_text['rp_decorator_' . $key . '_body_paid'] = array(
                            'title' => __('Body invoice paid text', 'decorator-woocommerce-email-customizer'),
                            'type' => 'textarea',
                            'section' => 'text_editor',
                            'transport' => 'refresh',
                            'default' => self::get_default_value($key . '_body_paid'),
                            'original' => '',
                        );
                        // Email Body Text
                        $email_text['rp_decorator_' . $key . '_body'] = array(
                            'title' => __('Body invoice pending payment text', 'decorator-woocommerce-email-customizer'),
                            'type' => 'textarea',
                            'section' => 'text_editor',
                            'default' => self::get_default_value($key . '_body'),
                            'original' => '',
                        );
                    } else if ('customer_renewal_invoice' == $key) {
                        // Email Body Text
                        $email_text['rp_decorator_' . $key . '_body_failed'] = array(
                            'title' => __('Body invoice failed text', 'decorator-woocommerce-email-customizer'),
                            'type' => 'textarea',
                            'section' => 'text_editor',
                            'default' => self::get_default_value($key . '_body_failed'),
                            'original' => '',
                            'transport' => 'refresh',
                        );
                        $email_text['rp_decorator_' . $key . '_body_text_enable_switch'] = array(
                            'title' => __('Body invoice pending payment text', 'decorator-woocommerce-email-customizer'),
                            'control_type' => 'toggleswitch',
                            'section' => 'text_editor',
                            'transport' => 'refresh',
                            'default' => self::get_default_value( 'body_text_enable_switch'),
                            'original' => '',
                        );
                        // Email Body Text
                        $email_text['rp_decorator_' . $key . '_body'] = array(
                            'title' => __('', 'decorator-woocommerce-email-customizer'),
                            'type' => 'textarea',
                            'section' => 'text_editor',
                            'default' => self::get_default_value($key . '_body'),
                            'original' => '',
                        );
                    } else if ('customer_reset_password' == $key) {
                        // Email preview switch.
                        $email_text['rp_decorator_' . $key . '_btn_switch'] = array(
                            'title' => __('Make "reset your password" a button', 'decorator-woocommerce-email-customizer'),
                            'control_type' => 'toggleswitch',
                            'section' => 'text_editor',
                            'transport' => 'refresh',
                            'default' => self::get_default_value($key . '_btn_switch'),
                            'original' => '',
                        );
                        
                        $email_text['rp_decorator_' . $key . '_body_text_enable_switch'] = array(
                            'title' => __('Body text', 'decorator-woocommerce-email-customizer'),
                            'control_type' => 'toggleswitch',
                            'section' => 'text_editor',
                            'transport' => 'refresh',
                            'default' => self::get_default_value( 'body_text_enable_switch'),
                            'original' => '',
                        );
                        // Email Body Text
                        $email_text['rp_decorator_' . $key . '_body'] = array(
                            'title' => __('', 'decorator-woocommerce-email-customizer'),
                            'type' => 'textarea',
                            'section' => 'text_editor',
                            'default' => self::get_default_value($key . '_body'),
                            'original' => '',
                        );
                    } else {
                        if (array_key_exists($key, RP_Decorator_Preview::get_body_compactible_email_types())) {
                            // Email preview switch.
                            $email_text['rp_decorator_' . $key . '_body_text_enable_switch'] = array(
                                'title' => __('Body text', 'decorator-woocommerce-email-customizer'),
                                'control_type' => 'toggleswitch',
                                'section' => 'text_editor',
                                'transport' => 'refresh',
                                'default' => self::get_default_value( 'body_text_enable_switch'),
                                'original' => '',
                            );
                            // Email Body Text.
                            $email_text['rp_decorator_' . $key . '_body'] = array(
                                'title' => __('', 'decorator-woocommerce-email-customizer'),
                                'type' => 'textarea',
                                'section' => 'text_editor',
                                'default' => self::get_default_value($key . '_body'),
                                'original' => '',
                                'transport' => 'refresh',
                            );
                        }
                    }
                }
                self::$custom_settings_for_textedit = $email_text;
            }
            return self::$custom_settings_for_textedit;
        }
        
    }

}
