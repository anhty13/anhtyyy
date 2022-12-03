<?php
/**
 * Email Verification for WooCommerce - Emails Section Settings
 *
 * @version 1.9.3
 * @since   1.3.0
 * @author  WPFactory
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Email_Verification_Settings_Emails' ) ) :

class Alg_WC_Email_Verification_Settings_Emails extends Alg_WC_Email_Verification_Settings_Section {

	/**
	 * Constructor.
	 *
	 * @version 1.3.0
	 * @since   1.3.0
	 */
	function __construct() {
		$this->id   = 'emails';
		$this->desc = __( 'Emails', 'emails-verification-for-woocommerce' );
		parent::__construct();
	}

	/**
	 * get_settings.
	 *
	 * @version 1.9.3
	 * @since   1.3.0
	 */
	function get_settings() {
		return array(
			array(
				'title'    => __( 'Email Options', 'emails-verification-for-woocommerce' ),
				'type'     => 'title',
				'id'       => 'alg_wc_ev_email_options',
				'desc'     => $this->pro_msg( '<strong>', 'You will need %s plugin to change email settings.', '</strong>' ),
			),
			array(
				'title'    => __( 'Email subject', 'emails-verification-for-woocommerce' ),
				'type'     => 'textarea',
				'id'       => 'alg_wc_ev_email_subject',
				'default'  => __( 'Please activate your account', 'emails-verification-for-woocommerce' ),
				'css'      => 'width:100%;',
				'alg_wc_ev_raw' => true,
				'custom_attributes' => apply_filters( 'alg_wc_ev_settings', array( 'readonly' => 'readonly' ) ),
			),
			array(
				'title'    => __( 'Email content', 'emails-verification-for-woocommerce' ),
				'desc'     => sprintf( __( 'Placeholders: %s', 'emails-verification-for-woocommerce' ), '<code>' . implode( '</code>, <code>', array(
						'%verification_url%',
						'%user_id%',
						'%user_first_name%',
						'%user_last_name%',
						'%user_login%',
						'%user_nicename%',
						'%user_email%',
						'%user_display_name%',
					) ) . '</code>' ),
				'type'     => 'textarea',
				'id'       => 'alg_wc_ev_email_content',
				'default'  => __( 'Please click the following link to verify your email:<br><br><a href="%verification_url%">%verification_url%</a>', 'emails-verification-for-woocommerce' ),
				'css'      => 'width:100%;height:150px;',
				'alg_wc_ev_raw' => true,
				'custom_attributes' => apply_filters( 'alg_wc_ev_settings', array( 'readonly' => 'readonly' ) ),
			),
			array(
				'title'    => __( 'Email template', 'emails-verification-for-woocommerce' ),
				'desc_tip' => __( 'Possible values: Plain, WooCommerce.', 'emails-verification-for-woocommerce' ),
				'id'       => 'alg_wc_ev_email_template',
				'type'     => 'select',
				'class'    => 'chosen_select',
				'default'  => 'plain',
				'options'  => array(
					'plain' => __( 'Plain', 'emails-verification-for-woocommerce' ),
					'wc'    => __( 'WooCommerce', 'emails-verification-for-woocommerce' ),
				),
				'custom_attributes' => apply_filters( 'alg_wc_ev_settings', array( 'disabled' => 'disabled' ) ),
			),
			array(
				'desc'     => __( 'If "WooCommerce" is selected as "Email template", set email heading here.', 'emails-verification-for-woocommerce' ),
				'id'       => 'alg_wc_ev_email_template_wc_heading',
				'type'     => 'textarea',
				'default'  => __( 'Activate your account', 'emails-verification-for-woocommerce' ),
				'css'      => 'width:100%;',
				'alg_wc_ev_raw' => true,
				'custom_attributes' => apply_filters( 'alg_wc_ev_settings', array( 'readonly' => 'readonly' ) ),
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_wc_ev_email_options',
			),
		);
	}

}

endif;

return new Alg_WC_Email_Verification_Settings_Emails();
