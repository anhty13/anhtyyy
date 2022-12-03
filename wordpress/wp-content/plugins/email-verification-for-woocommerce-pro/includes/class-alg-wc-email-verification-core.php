<?php
/**
 * Email Verification for WooCommerce - Core Class
 *
 * @version 1.9.2
 * @since   1.0.0
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Email_Verification_Core' ) ) :

class Alg_WC_Email_Verification_Core {

	/**
	 * Constructor.
	 *
	 * @version 1.9.2
	 * @since   1.0.0
	 * @todo    [dev] (next) (maybe) `[alg_wc_ev_translate]` to description in readme.txt
	 */
	function __construct() {
		// Functions
		require_once( 'alg-wc-email-verification-functions.php' );
		// Verification actions
		add_action( 'init', array( $this, 'verify' ),   PHP_INT_MAX );
		add_action( 'init', array( $this, 'activate' ), PHP_INT_MAX );
		add_action( 'init', array( $this, 'resend' ),   PHP_INT_MAX );
		// Prevent login
		require_once( 'class-alg-wc-email-verification-logouts.php' );
		// Emails
		$this->emails = require_once( 'class-alg-wc-email-verification-emails.php' );
		// Messages
		$this->messages = require_once( 'class-alg-wc-email-verification-messages.php' );
		// Shortcodes
		add_shortcode( 'alg_wc_ev_translate', array( $this, 'language_shortcode' ) );
		// Admin stuff
		require_once( 'class-alg-wc-email-verification-admin.php' );
		// Core loaded
		do_action( 'alg_wc_ev_core_loaded', $this );
	}

	/**
	 * language_in.
	 *
	 * @version 1.7.0
	 * @since   1.7.0
	 */
	function language_in( $needle, $haystack ) {
		return in_array( strtolower( $needle ), array_map( 'trim', explode( ',', strtolower( $haystack ) ) ) );
	}

	/**
	 * get_language.
	 *
	 * @version 1.7.0
	 * @since   1.7.0
	 * @todo    [dev] (next) (maybe) email: add `lang` param to the `alg_wc_ev_user_id`
	 * @todo    [dev] (next) (maybe) email: use `locale` ("Language") field from user profile
	 * @todo    [dev] (next) (maybe) email: `billing_country`?
	 * @todo    [dev] (next) (maybe) email: `shipping_country` fallback?
	 * @todo    [dev] (next) (maybe) email: TLD fallback?
	 */
	function get_language() {
		return ( defined( 'ICL_LANGUAGE_CODE' ) ? ICL_LANGUAGE_CODE : false );
	}

	/**
	 * language_shortcode.
	 *
	 * @version 1.7.0
	 * @since   1.7.0
	 */
	function language_shortcode( $atts, $content = '' ) {
		$language = $this->get_language();
		// E.g.: `[alg_wc_ev_translate lang="EN,DE" lang_text="Text for EN & DE" not_lang_text="Text for other languages"]`
		if ( isset( $atts['lang_text'] ) && isset( $atts['not_lang_text'] ) && ! empty( $atts['lang'] ) ) {
			return ( ! $language || ! $this->language_in( $language, $atts['lang'] ) ) ?
				$atts['not_lang_text'] : $atts['lang_text'];
		}
		// E.g.: `[alg_wc_ev_translate lang="EN,DE"]Text for EN & DE[/alg_wc_ev_translate][alg_wc_ev_translate not_lang="EN,DE"]Text for other languages[/alg_wc_ev_translate]`
		return (
			( ! empty( $atts['lang'] )     && ( ! $language || ! $this->language_in( $language, $atts['lang'] ) ) ) ||
			( ! empty( $atts['not_lang'] ) &&     $language &&   $this->language_in( $language, $atts['not_lang'] ) )
		) ? '' : $content;
	}

	/**
	 * add_to_log.
	 *
	 * @version 1.6.0
	 * @since   1.6.0
	 */
	function add_to_log( $message ) {
		if ( function_exists( 'wc_get_logger' ) && ( $log = wc_get_logger() ) ) {
			$log->log( 'info', $message, array( 'source' => 'alg-wc-ev' ) );
		}
	}

	/**
	 * is_user_verified_by_user_id.
	 *
	 * @version 1.6.0
	 * @since   1.5.0
	 */
	function is_user_verified_by_user_id( $user_id = false, $is_guest_verified = false ) {
		if ( false === $user_id ) {
			$user_id = get_current_user_id();
		}
		if ( 0 == $user_id ) {
			return $is_guest_verified;
		}
		$user = new WP_User( $user_id );
		return $this->is_user_verified( $user, $is_guest_verified );
	}

	/**
	 * is_user_verified.
	 *
	 * @version 1.8.0
	 * @since   1.1.0
	 */
	function is_user_verified( $user, $is_guest_verified = false ) {
		if ( ! $user || is_wp_error( $user ) || 0 == $user->ID || empty( $user->roles ) ) {
			return $is_guest_verified;
		}
		if ( apply_filters( 'alg_wc_ev_is_user_verified', false, $user->ID ) ) {
			return true;
		}
		$do_verify_already_registered = ( 'yes' === get_option( 'alg_wc_ev_verify_already_registered', 'no' ) );
		$is_user_email_activated      = get_user_meta( $user->ID, 'alg_wc_ev_is_activated', true );
		if (
			( ( $do_verify_already_registered && ! $is_user_email_activated ) || ( ! $do_verify_already_registered && '0' === $is_user_email_activated ) ) &&
			! $this->is_user_role_skipped( $user )
		) {
			return false;
		}
		return true;
	}

	/**
	 * is_user_role_skipped.
	 *
	 * @version 1.6.0
	 * @since   1.6.0
	 * @todo    [dev] (next) maybe always include `administrator` (i.e. even if `$skip_user_roles` is empty)?
	 * @todo    [dev] simplify to `( array )`?
	 */
	function is_user_role_skipped( $user ) {
		if ( isset( $user->roles ) && ! empty( $user->roles ) ) {
			$userdata_roles  = $user->roles;
			$skip_user_roles = get_option( 'alg_wc_ev_skip_user_roles', array( 'administrator' ) );
			$userdata_roles  = ( ! is_array( $userdata_roles )  ? array( $userdata_roles )  : $userdata_roles );
			$skip_user_roles = ( ! is_array( $skip_user_roles ) ? array( $skip_user_roles ) : $skip_user_roles );
			$intersect       = array_intersect( $userdata_roles, $skip_user_roles );
			if ( ! empty( $intersect ) ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * verify.
	 *
	 * @version 1.9.1
	 * @since   1.6.0
	 */
	function verify() {
		if ( isset( $_GET['alg_wc_ev_verify_email'] ) ) {
			$data    = json_decode( base64_decode( $_GET['alg_wc_ev_verify_email'] ), true );
			$user_id = $data['id'];
			$code    = get_user_meta( $user_id, 'alg_wc_ev_activation_code', true );
			if ( '' !== $code && $code === $data['code'] ) {
				if ( apply_filters( 'alg_wc_ev_verify_email', true, $user_id ) ) {
					update_user_meta( $user_id, 'alg_wc_ev_is_activated', '1' );
					wc_add_notice( $this->messages->get_success_message() );
					$this->emails->maybe_send_wc_customer_new_account_email( $user_id );
					do_action( 'alg_wc_ev_user_account_activated', $user_id );
					if ( 'no' != ( $redirect = get_option( 'alg_wc_ev_redirect_to_my_account_on_success', 'yes' ) ) ) {
						wp_set_current_user( $user_id );
						wp_set_auth_cookie( $user_id );
						switch ( $redirect ) {
							case 'home':
								$redirect_url = get_home_url();
								break;
							case 'shop':
								$redirect_url = wc_get_page_permalink( 'shop' );
								break;
							case 'custom':
								$redirect_url = get_option( 'alg_wc_ev_redirect_on_success_url', '' );
								break;
							default: // 'yes'
								$redirect_url = wc_get_page_permalink( 'myaccount' );
						}
						wp_redirect( $redirect_url );
						exit;
					}
				} else {
					do_action( 'alg_wc_ev_verify_email_error', $user_id );
				}
			} else {
				wc_add_notice( $this->messages->get_failed_message( $user_id ), 'error' );
			}
		}
	}

	/**
	 * activate.
	 *
	 * @version 1.6.0
	 * @since   1.6.0
	 * @todo    [dev] (now) (maybe) custom `wc_add_notice()`
	 * @todo    [dev] (maybe) rename `alg_wc_ev_activate_account_message`
	 */
	function activate() {
		if ( isset( $_GET['alg_wc_ev_activate_account_message'] ) ) {
			wc_add_notice( $this->messages->get_activation_message( intval( $_GET['alg_wc_ev_activate_account_message'] ) ) );
		}
	}

	/**
	 * resend.
	 *
	 * @version 1.6.0
	 * @since   1.6.0
	 * @todo    [dev] (maybe) rename `alg_wc_ev_user_id`
	 */
	function resend() {
		if ( isset( $_GET['alg_wc_ev_user_id'] ) ) {
			$this->emails->reset_and_mail_activation_link( $_GET['alg_wc_ev_user_id'] );
			wc_add_notice( $this->messages->get_resend_message() );
		}
	}

}

endif;

return new Alg_WC_Email_Verification_Core();
