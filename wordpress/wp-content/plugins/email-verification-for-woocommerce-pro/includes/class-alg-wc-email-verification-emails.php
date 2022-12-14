<?php
/**
 * Email Verification for WooCommerce - Emails Class
 *
 * @version 1.9.2
 * @since   1.6.0
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Email_Verification_Emails' ) ) :

class Alg_WC_Email_Verification_Emails {

	/**
	 * Constructor.
	 *
	 * @version 1.8.0
	 * @since   1.6.0
	 */
	function __construct() {
		if ( 'yes' === get_option( 'alg_wc_ev_send_as_separate_email', 'yes' ) ) {
			// Activation email
			$new_user_action = get_option( 'alg_wc_ev_new_user_action', 'user_register' );
			add_action( apply_filters( 'alg_wc_ev_new_user_action', $new_user_action ), array( $this, 'reset_and_mail_activation_link' ), PHP_INT_MAX );
			// Delay WC customer new account email
			if ( 'yes' === get_option( 'alg_wc_ev_delay_wc_email', 'no' ) ) {
				add_action( 'woocommerce_init', array( $this, 'remove_customer_new_account_email' ) );
			}
		} else {
			// Append to WC customer new account email
			add_filter( 'woocommerce_email_additional_content_' . 'customer_new_account', array( $this, 'customer_new_account_reset_and_append_verification_link' ), PHP_INT_MAX, 3 );
		}
	}

	/**
	 * get_verification_url.
	 *
	 * @version 1.8.0
	 * @since   1.8.0
	 */
	function get_verification_url( $user_id, $code = false ) {
		if ( false === $code ) {
			$code = md5( time() );
		}
		return add_query_arg( 'alg_wc_ev_verify_email', base64_encode( json_encode( array( 'id' => $user_id, 'code' => $code ) ) ), wc_get_page_permalink( 'myaccount' ) );
	}

	/**
	 * get_email_content.
	 *
	 * @version 1.9.0
	 * @since   1.8.0
	 * @todo    [dev] (maybe) `$user->user_url`, `$user->user_registered`
	 */
	function get_email_content( $user_id, $code = false ) {
		$content = do_shortcode( apply_filters( 'alg_wc_ev_email_content',
			__( 'Please click the following link to verify your email:<br><br><a href="%verification_url%">%verification_url%</a>', 'emails-verification-for-woocommerce' ) ) );
		$placeholders = ( ( $user = new WP_User( $user_id ) ) && ! is_wp_error( $user ) ? array(
				'%user_first_name%'     => $user->first_name,
				'%user_last_name%'      => $user->last_name,
				'%user_login%'          => $user->user_login,
				'%user_nicename%'       => $user->user_nicename,
				'%user_email%'          => $user->user_email,
				'%user_display_name%'   => $user->display_name,
			) : array() );
		$placeholders['%user_id%']          = $user_id;
		$placeholders['%verification_url%'] = $this->get_verification_url( $user_id, $code );
		return apply_filters( 'alg_wc_ev_email_content_final', str_replace( array_keys( $placeholders ), $placeholders, $content ) );
	}

	/**
	 * update_all_user_meta.
	 *
	 * @version 1.8.0
	 * @since   1.8.0
	 * @todo    [dev] (now) (maybe) run this always and only on `$new_user_action` (i.e. instead of on `$new_user_action` or `woocommerce_email_additional_content_`)
	 */
	function update_all_user_meta( $user_id, $code ) {
		update_user_meta( $user_id, 'alg_wc_ev_is_activated',         '0' );
		update_user_meta( $user_id, 'alg_wc_ev_activation_code',      $code );
		update_user_meta( $user_id, 'alg_wc_ev_activation_code_time', time() );
	}

	/**
	 * customer_new_account_reset_and_append_verification_link.
	 *
	 * @version 1.8.1
	 * @since   1.8.0
	 * @todo    [dev] (recheck) `<p>` and plain?
	 * @todo    [dev] (maybe) try getting new code before generating new one (i.e. `$code = get_user_meta( $user->ID, 'alg_wc_ev_activation_code', true );`)
	 */
	function customer_new_account_reset_and_append_verification_link( $content, $user, $email ) {
		$code = md5( time() );
		$this->update_all_user_meta( $user->ID, $code );
		if ( ! alg_wc_ev()->core->is_user_verified( $user ) ) {
			return str_replace( array( '<br>' ), array( "\n" ), $this->get_email_content( $user->ID, $code ) ) . "\n\n" . $content;
		}
		return $content;
	}

	/**
	 * reset_and_mail_activation_link.
	 *
	 * @version 1.9.2
	 * @since   1.0.0
	 * @todo    [dev] (maybe) add `%site_name%` etc. replaced value in `alg_wc_ev_email_subject`
	 */
	function reset_and_mail_activation_link( $user_id ) {
		if ( $user_id ) {
			// Get data
			$user          = get_userdata( $user_id );
			$code          = md5( time() );
			$email_content = $this->get_email_content( $user_id, $code );
			$email_subject = do_shortcode( apply_filters( 'alg_wc_ev_email_subject', __( 'Please activate your account', 'emails-verification-for-woocommerce' ) ) );
			// Set user meta
			$this->update_all_user_meta( $user_id, $code );
			// Send email
			if ( ! alg_wc_ev()->core->is_user_verified_by_user_id( $user_id ) ) {
				$this->send_mail( $user->user_email, $email_subject, $email_content );
				update_user_meta( $user_id, 'alg_wc_ev_activation_email_sent', time() );
			} else {
				$this->maybe_send_wc_customer_new_account_email( $user_id );
			}
		}
	}

	/**
	 * remove_customer_new_account_email.
	 *
	 * @version 1.6.0
	 * @since   1.2.0
	 */
	function remove_customer_new_account_email() {
		if ( class_exists( 'WC_Emails' ) && method_exists( 'WC_Emails', 'instance' ) ) {
			$wc_emails = WC_Emails::instance();
			remove_action( 'woocommerce_created_customer_notification', array( $wc_emails, 'customer_new_account' ), 10, 3 );
		}
	}

	/**
	 * maybe_send_wc_customer_new_account_email.
	 *
	 * @version 1.8.0
	 * @since   1.6.0
	 * @todo    [dev] (maybe) somehow set 2nd and 3rd params in `customer_new_account()` (i.e. `user_pass` and `password_generated`)
	 */
	function maybe_send_wc_customer_new_account_email( $user_id ) {
		if (
			'yes' === get_option( 'alg_wc_ev_delay_wc_email', 'no' ) &&
			'' == get_user_meta( $user_id, 'alg_wc_ev_customer_new_account_email_sent', true ) &&
			class_exists( 'WC_Emails' ) && method_exists( 'WC_Emails', 'instance' )
		) {
			$wc_emails = WC_Emails::instance();
			$wc_emails->customer_new_account( $user_id );
			update_user_meta( $user_id, 'alg_wc_ev_customer_new_account_email_sent', time() );
		}
	}

	/**
	 * send_mail.
	 *
	 * @version 1.9.2
	 * @since   1.9.2
	 * @see     https://www.php.net/manual/en/function.mail.php
	 * @see     https://github.com/woocommerce/woocommerce/blob/master/includes/wc-core-functions.php
	 * @see     https://developer.wordpress.org/reference/functions/wp_mail/
	 * @todo    [dev] (test) if `$last_error` message for `wc_mail` is logged
	 */
	function send_mail( $to, $subject, $message ) {
		/**
		 * `mail ( string $to , string $subject , string $message [, mixed $additional_headers [, string $additional_parameters ]] ) : bool`
		 * `wc_mail( $to, $subject, $message, $headers = "Content-Type: text/html\r\n", $attachments = '' )`
		 * `wp_mail( string|array $to, string $subject, string $message, string|array $headers = '', string|array $attachments = array() )`
		 */
		$func    = get_option( 'alg_wc_ev_mail_function', 'wc_mail' );
		$message = apply_filters( 'alg_wc_ev_send_mail_message', $message, $func );
		$res     = $func( $to, $subject, $message, "Content-Type: text/html\r\n" );
		if ( ! $res ) {
			$error_message  = __( 'Error sending mail.', 'emails-verification-for-woocommerce' );
			$error_message .= ' ' . sprintf( __( 'Mail function: %s.', 'emails-verification-for-woocommerce' ), $func );
			$last_error     = error_get_last();
			if ( ! empty( $last_error['message'] ) ) {
				$error_message .= ' ' . sprintf( __( 'Last error: %s.', 'emails-verification-for-woocommerce' ), $last_error['message'] );
			}
			alg_wc_ev()->core->add_to_log( $error_message );
		}
	}

}

endif;

return new Alg_WC_Email_Verification_Emails();
