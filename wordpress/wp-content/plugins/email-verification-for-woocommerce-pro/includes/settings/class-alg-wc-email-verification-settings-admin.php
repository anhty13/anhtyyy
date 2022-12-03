<?php
/**
 * Email Verification for WooCommerce - Admin Section Settings
 *
 * @version 1.8.0
 * @since   1.3.0
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Email_Verification_Settings_Admin' ) ) :

class Alg_WC_Email_Verification_Settings_Admin extends Alg_WC_Email_Verification_Settings_Section {

	/**
	 * Constructor.
	 *
	 * @version 1.3.0
	 * @since   1.3.0
	 */
	function __construct() {
		$this->id   = 'admin';
		$this->desc = __( 'Admin', 'emails-verification-for-woocommerce' );
		parent::__construct();
	}

	/**
	 * get_settings.
	 *
	 * @version 1.8.0
	 * @since   1.3.0
	 * @todo    [dev] (next) Delete users (automatically): better description
	 * @todo    [dev] (next) Email: better description(s) and default value(s)
	 * @todo    [dev] (next) Email: heading: placeholders
	 * @todo    [dev] (maybe) set `alg_wc_ev_admin_manual` default to `yes`
	 */
	function get_settings() {
		return array(
			array(
				'title'    => __( 'Admin Options', 'emails-verification-for-woocommerce' ),
				'type'     => 'title',
				'id'       => 'alg_wc_ev_admin_options',
			),
			array(
				'title'    => __( 'Add column', 'emails-verification-for-woocommerce' ),
				'desc_tip' => __( 'Adds "Verified" column to the admin "Users" list.', 'emails-verification-for-woocommerce' ),
				'desc'     => __( 'Enable', 'emails-verification-for-woocommerce' ),
				'type'     => 'checkbox',
				'id'       => 'alg_wc_ev_admin_column',
				'default'  => 'yes',
				'checkboxgroup' => 'start',
			),
			array(
				'desc_tip' => __( 'Adds links for manual email verification, unverification and email resend by admin.', 'emails-verification-for-woocommerce' ) . ' ' .
					__( '"Verified" column must be enabled.', 'emails-verification-for-woocommerce' ),
				'desc'     => __( 'Add actions', 'emails-verification-for-woocommerce' ),
				'type'     => 'checkbox',
				'id'       => 'alg_wc_ev_admin_manual',
				'default'  => 'no',
				'checkboxgroup' => 'end',
			),
			array(
				'title'    => __( 'Email', 'emails-verification-for-woocommerce' ),
				'desc_tip' => __( 'Sends email to the admin when new user verifies his email.', 'emails-verification-for-woocommerce' ) .
					$this->pro_msg(),
				'desc'     => __( 'Enable', 'emails-verification-for-woocommerce' ),
				'type'     => 'checkbox',
				'id'       => 'alg_wc_ev_admin_email',
				'default'  => 'no',
				'custom_attributes' => apply_filters( 'alg_wc_ev_settings', array( 'disabled' => 'disabled' ) ),
			),
			array(
				'desc'     => __( 'Email recipient', 'emails-verification-for-woocommerce' ),
				'desc_tip' => sprintf( __( 'Leave empty to send to %s.', 'emails-verification-for-woocommerce' ), get_bloginfo( 'admin_email' ) ),
				'type'     => 'text',
				'id'       => 'alg_wc_ev_admin_email_recipient',
				'default'  => '',
				'css'      => 'width:100%;',
			),
			array(
				'desc'     => __( 'Email subject', 'emails-verification-for-woocommerce' ),
				'type'     => 'text',
				'id'       => 'alg_wc_ev_admin_email_subject',
				'default'  => __( 'User email has been verified', 'emails-verification-for-woocommerce' ),
				'css'      => 'width:100%;',
			),
			array(
				'desc'     => __( 'Email heading', 'emails-verification-for-woocommerce' ),
				'type'     => 'textarea',
				'id'       => 'alg_wc_ev_admin_email_heading',
				'default'  => __( 'User account has been activated', 'emails-verification-for-woocommerce' ),
				'css'      => 'width:100%;',
				'alg_wc_ev_raw' => true,
			),
			array(
				'desc'     => __( 'Email content', 'emails-verification-for-woocommerce' ) . '<br>' .
					$this->available_placeholders_desc( array(
						'%user_id%',
						'%user_login%',
						'%user_nicename%',
						'%user_email%',
						'%user_url%',
						'%user_registered%',
						'%user_display_name%',
						'%user_roles%',
						'%user_first_name%',
						'%user_last_name%',
						'%admin_user_profile_url%',
					) ),
				'type'     => 'textarea',
				'id'       => 'alg_wc_ev_admin_email_content',
				'default'  => sprintf( __( 'User %s has just verified his email (%s).', 'emails-verification-for-woocommerce' ),
					'<a href="%admin_user_profile_url%">%user_login%</a>', '%user_email%' ),
				'css'      => 'width:100%;height:100px;',
				'alg_wc_ev_raw' => true,
			),
			array(
				'title'    => __( 'Delete users', 'emails-verification-for-woocommerce' ),
				'desc'     => '<strong>' . __( 'Delete', 'emails-verification-for-woocommerce' ) . '</strong>',
				'desc_tip' => __( 'Deletes unverified users from the database.', 'emails-verification-for-woocommerce' ) . ' ' .
					sprintf( __( 'Deleted users list will be affected by "%s", "%s" and "%s" options in "%s" settings section.', 'emails-verification-for-woocommerce' ),
						__( 'Skip email verification for user roles', 'emails-verification-for-woocommerce' ),
						__( 'Enable email verification for already registered users', 'emails-verification-for-woocommerce' ),
						__( 'Expire activation link', 'emails-verification-for-woocommerce' ),
						__( 'General', 'emails-verification-for-woocommerce' ) ) . ' ' .
					__( 'The tool will never delete the current user.', 'emails-verification-for-woocommerce' ) . ' ' .
					__( 'Check the box and save changes to run the tool.', 'emails-verification-for-woocommerce' ) . ' ' .
					'<span style="font-weight: bold; color: red;">' . __( 'There is no undo for this action!', 'emails-verification-for-woocommerce' ) . '</span>',
				'type'     => 'checkbox',
				'id'       => 'alg_wc_ev_delete_users',
				'default'  => 'no',
			),
			array(
				'title'    => __( 'Delete users automatically', 'emails-verification-for-woocommerce' ),
				'desc'     => '<strong>' . __( 'Enable', 'emails-verification-for-woocommerce' ) . '</strong>',
				'desc_tip' => __( 'Deletes unverified users from the database automatically once per week.', 'emails-verification-for-woocommerce' ),
				'type'     => 'checkbox',
				'id'       => 'alg_wc_ev_delete_users_cron',
				'default'  => 'no',
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_wc_ev_admin_options',
			),
		);
	}

}

endif;

return new Alg_WC_Email_Verification_Settings_Admin();
