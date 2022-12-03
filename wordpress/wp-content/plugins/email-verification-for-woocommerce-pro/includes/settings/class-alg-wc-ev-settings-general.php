<?php
/**
 * Email Verification for WooCommerce - General Section Settings
 *
 * @version 1.9.1
 * @since   1.0.0
 * @author  WPFactory
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Email_Verification_Settings_General' ) ) :

class Alg_WC_Email_Verification_Settings_General extends Alg_WC_Email_Verification_Settings_Section {

	/**
	 * Constructor.
	 *
	 * @version 1.1.1
	 * @since   1.0.0
	 */
	function __construct() {
		$this->id   = '';
		$this->desc = __( 'General', 'emails-verification-for-woocommerce' );
		parent::__construct();
	}

	/**
	 * get_user_roles_options.
	 *
	 * @version 1.3.0
	 * @since   1.0.0
	 */
	function get_user_roles_options() {
		global $wp_roles;
		$roles = apply_filters( 'editable_roles', ( isset( $wp_roles ) && is_object( $wp_roles ) ? $wp_roles->roles : array() ) );
		return wp_list_pluck( $roles, 'name' );
	}

	/**
	 * get_settings.
	 *
	 * @version 1.9.1
	 * @since   1.0.0
	 * @todo    [next] Logout unverified users on every page: better description
	 * @todo    [next] (maybe) `alg_wc_ev_delay_wc_email`: default to `yes`?
	 * @todo    `alg_wc_ev_expiration_time`: remove v1.7.0 note
	 * @todo    (maybe) more subsections?
	 * @todo    (maybe) `show_if_checked`
	 */
	function get_settings() {
		return array(
			array(
				'title'    => __( 'General Options', 'emails-verification-for-woocommerce' ),
				'type'     => 'title',
				'id'       => 'alg_wc_ev_general_options',
			),
			array(
				'title'    => __( 'Send as a separate email', 'emails-verification-for-woocommerce' ),
				'desc'     => __( 'Enable', 'emails-verification-for-woocommerce' ),
				'desc_tip' => __( 'Select if you want to send verification as a separate email, or append it to the standard WooCommerce "Customer new account" email.', 'emails-verification-for-woocommerce' ),
				'type'     => 'checkbox',
				'id'       => 'alg_wc_ev_send_as_separate_email',
				'default'  => 'yes',
			),
			array(
				'title'    => __( 'Skip email verification for user roles', 'emails-verification-for-woocommerce' ),
				'type'     => 'multiselect',
				'options'  => $this->get_user_roles_options(),
				'id'       => 'alg_wc_ev_skip_user_roles',
				'default'  => array( 'administrator' ),
				'class'    => 'chosen_select',
			),
			array(
				'title'    => __( 'Enable email verification for already registered users', 'emails-verification-for-woocommerce' ),
				'desc'     => __( 'Enable', 'emails-verification-for-woocommerce' ),
				'desc_tip' => __( 'If enabled, all your current users will have to verify their emails when logging to your site.', 'emails-verification-for-woocommerce' ),
				'type'     => 'checkbox',
				'id'       => 'alg_wc_ev_verify_already_registered',
				'default'  => 'no',
			),
			array(
				'title'    => __( 'Redirect on success', 'emails-verification-for-woocommerce' ),
				'desc_tip' => __( 'Redirects customers to the selected page after successful verification.', 'emails-verification-for-woocommerce' ),
				'type'     => 'select',
				'class'    => 'chosen_select',
				'id'       => 'alg_wc_ev_redirect_to_my_account_on_success', // mislabeled, should be `alg_wc_ev_redirect_on_success`
				'default'  => 'yes',
				'options'  => array(
					'no'     => __( 'Do not redirect', 'emails-verification-for-woocommerce' ),
					'yes'    => __( 'Redirect to "My account" page', 'emails-verification-for-woocommerce' ),
					'shop'   => __( 'Redirect to "Shop" page', 'emails-verification-for-woocommerce' ),
					'home'   => __( 'Redirect to home page', 'emails-verification-for-woocommerce' ),
					'custom' => __( 'Redirect to custom URL', 'emails-verification-for-woocommerce' ),
				),
			),
			array(
				'desc'     => __( 'Custom redirect URL', 'emails-verification-for-woocommerce' ),
				'desc_tip' => sprintf( __( '"%s" must be selected for the "%s" option above.', 'emails-verification-for-woocommerce' ),
						__( 'Redirect to custom URL', 'emails-verification-for-woocommerce' ), __( 'Redirect on success', 'emails-verification-for-woocommerce' ) ),
				'type'     => 'text',
				'id'       => 'alg_wc_ev_redirect_on_success_url',
				'default'  => '',
				'css'      => 'width:100%;',
				'alg_wc_ev_raw' => true,
			),
			array(
				'title'    => __( 'Delay standard WooCommerce customer new account email', 'emails-verification-for-woocommerce' ),
				'desc_tip' => __( 'Delays standard WooCommerce "Customer new account" email until after successful verification.', 'emails-verification-for-woocommerce' ) . ' ' .
					$this->separate_email_option_msg(),
				'desc'     => __( 'Delay', 'emails-verification-for-woocommerce' ),
				'type'     => 'checkbox',
				'id'       => 'alg_wc_ev_delay_wc_email',
				'default'  => 'no',
			),
			array(
				'title'    => __( 'Prevent automatic user login after register', 'emails-verification-for-woocommerce' ),
				'desc'     => __( 'Enable', 'emails-verification-for-woocommerce' ),
				'desc_tip' => __( 'Prevents automatic user login after registration on "My Account" page.', 'emails-verification-for-woocommerce' ),
				'type'     => 'checkbox',
				'id'       => 'alg_wc_ev_prevent_login_after_register',
				'default'  => 'yes',
			),
			array(
				'desc'     => __( 'Redirect', 'emails-verification-for-woocommerce' ),
				'type'     => 'select',
				'class'    => 'chosen_select',
				'id'       => 'alg_wc_ev_prevent_login_after_register_redirect',
				'default'  => 'no',
				'options'  => array(
					'no'     => __( 'No redirect', 'emails-verification-for-woocommerce' ),
					'yes'    => __( 'Force redirect to the "My Account" page', 'emails-verification-for-woocommerce' ),
					'custom' => __( 'Custom redirect', 'emails-verification-for-woocommerce' ),
				),
			),
			array(
				'desc'     => __( 'Custom redirect URL', 'emails-verification-for-woocommerce' ),
				'desc_tip' => sprintf( __( '"%s" must be selected for the "%s" option above.', 'emails-verification-for-woocommerce' ),
						__( 'Custom redirect', 'emails-verification-for-woocommerce' ), __( 'Redirect', 'emails-verification-for-woocommerce' ) ) . ' ' .
					__( 'Must be a local URL.', 'emails-verification-for-woocommerce' ),
				'type'     => 'text',
				'id'       => 'alg_wc_ev_prevent_login_after_register_redirect_url',
				'default'  => '',
				'css'      => 'width:100%;',
				'alg_wc_ev_raw' => true,
			),
			array(
				'title'    => __( 'Prevent automatic user login after checkout', 'emails-verification-for-woocommerce' ),
				'desc'     => __( 'Enable', 'emails-verification-for-woocommerce' ),
				'desc_tip' => __( 'Prevents automatic user login after registration during checkout.', 'emails-verification-for-woocommerce' ) . '<br>' .
					sprintf( __( 'If this option is not working correctly on your site, please try changing the value for the %s option in %s.', 'emails-verification-for-woocommerce' ),
						"'" . __( 'Action for "Prevent automatic user login after checkout"', 'emails-verification-for-woocommerce' ) . "'",
						'<a href="' . admin_url( 'admin.php?page=wc-settings&tab=alg_wc_ev&section=advanced' ) . '">' .
							__( 'WooCommerce > Settings > Email Verification > Advanced', 'emails-verification-for-woocommerce' ) . '</a>'
					),
				'type'     => 'checkbox',
				'id'       => 'alg_wc_ev_prevent_login_after_checkout',
				'default'  => 'yes',
				'checkboxgroup' => 'start',
			),
			array(
				'desc'     => __( 'Block "Thank you" page', 'emails-verification-for-woocommerce' ),
				'desc_tip' => __( 'Blocks "Thank you" (i.e. "Order received") page access for non-verified users. Users will be redirected to the "My account" page.', 'emails-verification-for-woocommerce' ) .
					$this->pro_msg(),
				'type'     => 'checkbox',
				'id'       => 'alg_wc_ev_prevent_login_after_checkout_block_thankyou',
				'default'  => 'no',
				'checkboxgroup' => '',
				'custom_attributes' => apply_filters( 'alg_wc_ev_settings', array( 'disabled' => 'disabled' ) ),
			),
			array(
				'desc'     => __( 'Block customer order emails', 'emails-verification-for-woocommerce' ),
				'desc_tip' => sprintf( __( 'Blocks standard WooCommerce customer order emails ("%s", "%s", "%s") for all non-verified users (including guests).', 'emails-verification-for-woocommerce' ),
						__( 'Order on-hold', 'woocommerce' ), __( 'Processing order', 'woocommerce' ), __( 'Completed order', 'woocommerce' ) ) .
					$this->pro_msg(),
				'type'     => 'checkbox',
				'id'       => 'alg_wc_ev_block_customer_order_emails',
				'default'  => 'no',
				'checkboxgroup' => 'end',
				'custom_attributes' => apply_filters( 'alg_wc_ev_settings', array( 'disabled' => 'disabled' ) ),
			),
			array(
				'title'    => __( 'Logout unverified users on "My Account" page', 'emails-verification-for-woocommerce' ),
				'desc'     => __( 'Enable', 'emails-verification-for-woocommerce' ),
				'desc_tip' => __( 'Will check if logged user is verified on "My Account" page.', 'emails-verification-for-woocommerce' ),
				'type'     => 'checkbox',
				'id'       => 'alg_wc_ev_prevent_login_myaccount',
				'default'  => 'no',
			),
			array(
				'title'    => __( 'Logout unverified users on every page', 'emails-verification-for-woocommerce' ),
				'desc'     => __( 'Enable', 'emails-verification-for-woocommerce' ),
				'desc_tip' => __( 'Will check if logged user is verified on every page of your site.', 'emails-verification-for-woocommerce' ),
				'type'     => 'checkbox',
				'id'       => 'alg_wc_ev_prevent_login_always',
				'default'  => 'no',
				'checkboxgroup' => 'start',
			),
			array(
				'desc'     => __( 'Redirect', 'emails-verification-for-woocommerce' ),
				'desc_tip' => __( 'Redirect to the activate account notice after logout.', 'emails-verification-for-woocommerce' ),
				'type'     => 'checkbox',
				'id'       => 'alg_wc_ev_prevent_login_always_redirect',
				'default'  => 'yes',
				'checkboxgroup' => 'end',
			),
			array(
				'title'    => __( 'Block checkout process for unverified users', 'emails-verification-for-woocommerce' ),
				'desc'     => __( 'Enable', 'emails-verification-for-woocommerce' ),
				'desc_tip' => __( 'Blocks checkout process for unverified users (including guests).', 'emails-verification-for-woocommerce' ) .
					$this->pro_msg(),
				'type'     => 'checkbox',
				'id'       => 'alg_wc_ev_block_checkout_process',
				'default'  => 'no',
				'custom_attributes' => apply_filters( 'alg_wc_ev_settings', array( 'disabled' => 'disabled' ) ),
			),
			array(
				'desc'     => __( 'Notice', 'emails-verification-for-woocommerce' ),
				'type'     => 'textarea',
				'id'       => 'alg_wc_ev_block_checkout_process_notice',
				'default'  => __( 'You need to log in and verify your email to place an order.', 'emails-verification-for-woocommerce' ),
				'css'      => 'width:100%;',
				'alg_wc_ev_raw' => true,
				'custom_attributes' => apply_filters( 'alg_wc_ev_settings', array( 'readonly' => 'readonly' ) ),
			),
			array(
				'title'    => __( 'Block guests from adding products to the cart', 'emails-verification-for-woocommerce' ),
				'desc'     => __( 'Enable', 'emails-verification-for-woocommerce' ),
				'desc_tip' => __( 'Blocks guests from adding any products to the cart.', 'emails-verification-for-woocommerce' ) .
					$this->pro_msg(),
				'type'     => 'checkbox',
				'id'       => 'alg_wc_ev_block_guest_add_to_cart',
				'default'  => 'no',
				'custom_attributes' => apply_filters( 'alg_wc_ev_settings', array( 'disabled' => 'disabled' ) ),
			),
			array(
				'desc'     => __( 'Notice', 'emails-verification-for-woocommerce' ) . '<br>' .
					$this->available_placeholders_desc( array( '%myaccount_url%' ) ),
				'type'     => 'textarea',
				'id'       => 'alg_wc_ev_block_guest_add_to_cart_notice',
				'default'  => __( 'You need to <a href="%myaccount_url%" target="_blank">register</a> and verify your email before adding products to the cart.', 'emails-verification-for-woocommerce' ),
				'css'      => 'width:100%;',
				'alg_wc_ev_raw' => true,
				'custom_attributes' => apply_filters( 'alg_wc_ev_settings', array( 'readonly' => 'readonly' ) ),
			),
			array(
				'title'    => __( 'Expire activation link', 'emails-verification-for-woocommerce' ),
				'desc_tip' => __( 'Ignored if set to zero.', 'emails-verification-for-woocommerce' ) . ' ' .
					__( 'Please note that all activation codes generated before installing the plugin v1.7.0 will be automatically expired.', 'emails-verification-for-woocommerce' ),
				'desc'     => __( 'Expiration time in seconds', 'emails-verification-for-woocommerce' ) .
					$this->pro_msg(),
				'type'     => 'number',
				'id'       => 'alg_wc_ev_expiration_time',
				'default'  => 0,
				'custom_attributes' => apply_filters( 'alg_wc_ev_settings', array( 'readonly' => 'readonly' ), 'min', array( 0 ) ),
			),
			array(
				'desc'     => __( 'Notice', 'emails-verification-for-woocommerce' ) . '<br>' .
					$this->available_placeholders_desc( array( '%resend_verification_url%' ) ),
				'desc_tip' => __( 'Notice will appear when user will try to verify his email by clicking the email activation link.', 'emails-verification-for-woocommerce' ),
				'type'     => 'textarea',
				'id'       => 'alg_wc_ev_activation_code_expired_message',
				'default'  => __( 'Link has expired. You can resend the email with verification link by clicking <a href="%resend_verification_url%">here</a>.', 'emails-verification-for-woocommerce' ),
				'css'      => 'width:100%;',
				'alg_wc_ev_raw' => true,
				'custom_attributes' => apply_filters( 'alg_wc_ev_settings', array( 'readonly' => 'readonly' ) ),
			),
			array(
				'title'    => __( 'Blacklist emails', 'emails-verification-for-woocommerce' ),
				'desc_tip' => __( 'Ignored if empty.', 'emails-verification-for-woocommerce' ),
				'desc'     => sprintf( __( 'Separate emails with a comma and/or with a new line. You can also use wildcard (%s) here, for example: %s', 'emails-verification-for-woocommerce' ),
					'<code>*</code>', '<code>*@example.com,email@example.net</code>' ) .
					$this->pro_msg(),
				'type'     => 'textarea',
				'css'      => 'width:100%;height:100px;',
				'id'       => 'alg_wc_ev_email_blacklist',
				'default'  => '',
				'custom_attributes' => apply_filters( 'alg_wc_ev_settings', array( 'readonly' => 'readonly' ) ),
			),
			array(
				'desc'     => __( 'Notice', 'emails-verification-for-woocommerce' ),
				'desc_tip' => __( 'Notice will appear when user will try to verify his email by clicking the email activation link.', 'emails-verification-for-woocommerce' ),
				'type'     => 'textarea',
				'id'       => 'alg_wc_ev_blacklisted_message',
				'default'  => __( 'Your email is blacklisted.', 'emails-verification-for-woocommerce' ),
				'css'      => 'width:100%;',
				'alg_wc_ev_raw' => true,
				'custom_attributes' => apply_filters( 'alg_wc_ev_settings', array( 'readonly' => 'readonly' ) ),
			),
			array(
				'title'    => __( 'Accept verification from "WooCommerce - Social Login" plugin', 'emails-verification-for-woocommerce' ),
				'desc_tip' => __( 'Check this if you want to automatically accept verification made by "WooCommerce - Social Login" plugin.', 'emails-verification-for-woocommerce' ) .
					$this->pro_msg(),
				'desc'     => __( 'Enable', 'emails-verification-for-woocommerce' ),
				'type'     => 'checkbox',
				'id'       => 'alg_wc_ev_accept_social_login',
				'default'  => 'no',
				'custom_attributes' => apply_filters( 'alg_wc_ev_settings', array( 'disabled' => 'disabled' ) ),
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_wc_ev_general_options',
			),
		);
	}

}

endif;

return new Alg_WC_Email_Verification_Settings_General();
