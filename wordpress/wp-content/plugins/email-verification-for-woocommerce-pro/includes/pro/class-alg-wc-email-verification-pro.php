<?php
/**
 * Email Verification for WooCommerce - Pro Class
 *
 * @version 1.9.2
 * @since   1.1.0
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Email_Verification_Pro' ) ) :

class Alg_WC_Email_Verification_Pro {

	/**
	 * Constructor.
	 *
	 * @version 1.9.2
	 * @since   1.1.0
	 * @todo    [dev] (next) Block thank you (`maybe_redirect_to_myaccount`?): Cancelled order (also block login)
	 * @todo    [feature] (maybe) email verification + order statuses?
	 * @todo    [feature] (maybe) emails whitelist
	 */
	function __construct() {
		add_filter( 'alg_wc_ev_settings',                          array( $this, 'settings' ), 10, 3 );
		add_filter( 'alg_wc_ev_core_loaded',                       array( $this, 'core_loaded' ) );
		add_filter( 'alg_wc_ev_email_content',                     array( $this, 'email_content' ) );
		add_filter( 'alg_wc_ev_email_content_final',               array( $this, 'maybe_wrap_in_wrap_in_wc_email_template' ) );
		add_filter( 'alg_wc_ev_email_subject',                     array( $this, 'email_subject' ) );
		add_action( 'alg_wc_ev_user_account_activated',            array( $this, 'maybe_send_admin_email' ) );
		add_action( 'alg_wc_ev_after_thankyou_logout',             array( $this, 'maybe_redirect_to_myaccount_action' ) );
		add_filter( 'alg_wc_ev_redirect_after_checkout',           array( $this, 'maybe_redirect_to_myaccount_filter' ), 10, 2 );
		add_filter( 'alg_wc_ev_is_user_verified',                  array( $this, 'is_user_verified_social_login' ), 10, 2 );
		add_filter( 'alg_wc_ev_verify_email',                      array( $this, 'validate_blacklisted_emails' ), 10, 2 );
		add_filter( 'alg_wc_ev_verify_email_error',                array( $this, 'output_blacklisted_email_notice' ) );
		add_filter( 'alg_wc_ev_verify_email',                      array( $this, 'validate_activation_code_time' ), 10, 2 );
		add_filter( 'alg_wc_ev_verify_email_error',                array( $this, 'output_activation_code_expired_notice' ) );
		add_filter( 'alg_wc_ev_delete_unverified_users_loop_args', array( $this, 'add_activation_code_time_meta_query' ), 10, 3 );
		add_filter( 'alg_wc_ev_send_mail_message',                 array( $this, 'maybe_add_wc_email_style' ), 10, 2 );
		add_filter( 'woocommerce_checkout_process',                array( $this, 'maybe_block_unverified_checkout_process' ), PHP_INT_MAX );
	}

	/**
	 * core_loaded.
	 *
	 * @version 1.7.0
	 * @since   1.5.0
	 */
	function core_loaded( $core ) {
		$this->core = $core;
		// Prevent login: After checkout: Block WC customer order emails
		if ( 'yes' === get_option( 'alg_wc_ev_prevent_login_after_checkout', 'yes' ) ) {
			if ( 'yes' === get_option( 'alg_wc_ev_block_customer_order_emails', 'no' ) ) {
				foreach ( array( 'customer_on_hold_order', 'customer_processing_order', 'customer_completed_order' ) as $email_id ) {
					add_filter( 'woocommerce_email_enabled_' . $email_id, array( $this, 'block_customer_order_emails' ), PHP_INT_MAX, 3 );
				}
			}
		}
		// Block guests from adding products to the cart
		if ( 'yes' === get_option( 'alg_wc_ev_block_guest_add_to_cart', 'no' ) ) {
			add_filter( 'woocommerce_add_to_cart_validation', array( $this, 'block_guest_add_to_cart_validation' ), PHP_INT_MAX, 3 );
			add_action( 'woocommerce_init',                   array( $this, 'block_guest_add_to_cart_ajax_error' ), PHP_INT_MAX );
		}
	}

	/**
	 * maybe_block_unverified_checkout_process.
	 *
	 * @version 1.8.0
	 * @since   1.8.0
	 * @todo    [dev] (now) (maybe) `alg_wc_ev_block_checkout_process_notice`: better default value
	 * @todo    [dev] (maybe) `alg_wc_ev_block_checkout_process_notice`: add placeholders (e.g. `%login_url%`)
	 */
	function maybe_block_unverified_checkout_process() {
		if ( 'yes' === get_option( 'alg_wc_ev_block_checkout_process', 'no' ) ) {
			if ( ! $this->core->is_user_verified( wp_get_current_user() ) ) {
				$message = do_shortcode( get_option( 'alg_wc_ev_block_checkout_process_notice',
					__( 'You need to log in and verify your email to place an order.', 'emails-verification-for-woocommerce' ) ) );
				wc_add_notice( $message, 'error' );
			}
		}
	}

	/**
	 * add_activation_code_time_meta_query.
	 *
	 * @version 1.7.0
	 * @since   1.7.0
	 */
	function add_activation_code_time_meta_query( $args, $current_user_id, $is_cron ) {
		if ( 0 != ( $expiration_time = get_option( 'alg_wc_ev_expiration_time', 0 ) ) ) {
			$meta_query = array(
				'key'     => 'alg_wc_ev_activation_code_time',
				'value'   => ( time() - $expiration_time ),
				'compare' => '<',
			);
			if ( isset( $args['meta_query']['relation'] ) ) {
				$_meta_query = $args['meta_query'];
				$args['meta_query'] = array( $_meta_query );
			}
			$args['meta_query']['relation'] = 'AND';
			$args['meta_query'][] = $meta_query;
		}
		return $args;
	}

	/**
	 * output_activation_code_expired_notice.
	 *
	 * @version 1.7.0
	 * @since   1.7.0
	 */
	function output_activation_code_expired_notice( $user_id ) {
		if ( ! $this->validate_activation_code_time( true, $user_id ) ) {
			wc_add_notice( $this->get_activation_code_expired_message( $user_id ), 'error' );
		}
	}

	/**
	 * get_activation_code_expired_message.
	 *
	 * @version 1.8.0
	 * @since   1.7.0
	 */
	function get_activation_code_expired_message( $user_id ) {
		$notice = do_shortcode( get_option( 'alg_wc_ev_activation_code_expired_message',
			__( 'Link has expired. You can resend the email with verification link by clicking <a href="%resend_verification_url%">here</a>.', 'emails-verification-for-woocommerce' ) ) );
		return str_replace( '%resend_verification_url%', $this->core->messages->get_resend_verification_url( $user_id ), $notice );
	}

	/**
	 * validate_activation_code_time.
	 *
	 * @version 1.7.0
	 * @since   1.7.0
	 */
	function validate_activation_code_time( $is_valid, $user_id ) {
		if ( $is_valid && $user_id && 0 != ( $expiration_time = get_option( 'alg_wc_ev_expiration_time', 0 ) ) ) {
			$activation_code_time = get_user_meta( $user_id, 'alg_wc_ev_activation_code_time', true );
			if ( ! $activation_code_time ) {
				$activation_code_time = 0;
			}
			if ( ( time() - $activation_code_time ) > $expiration_time ) {
				return false;
			}
		}
		return $is_valid;
	}

	/**
	 * output_blacklisted_email_notice.
	 *
	 * @version 1.6.0
	 * @since   1.6.0
	 */
	function output_blacklisted_email_notice( $user_id ) {
		if ( ! $this->validate_blacklisted_emails( true, $user_id ) ) {
			wc_add_notice( $this->get_blacklisted_message(), 'error' );
		}
	}

	/**
	 * get_blacklisted_message.
	 *
	 * @version 1.6.0
	 * @since   1.6.0
	 */
	function get_blacklisted_message() {
		return do_shortcode( get_option( 'alg_wc_ev_blacklisted_message', __( 'Your email is blacklisted.', 'emails-verification-for-woocommerce' ) ) );
	}

	/**
	 * wildcard_match.
	 *
	 * @version 1.6.0
	 * @since   1.6.0
	 */
	function wildcard_match( $pattern, $subject ) {
		$pattern = strtr( $pattern, array(
			'*' => '.*?', // 0 or more (lazy) - asterisk (*)
			'?' => '.',   // 1 character - question mark (?)
		) );
		return preg_match( "/$pattern/", $subject );
	}

	/**
	 * validate_blacklisted_emails.
	 *
	 * @version 1.8.0
	 * @since   1.6.0
	 * @todo    [dev] (maybe) check for this earlier, i.e. not on verification link click
	 */
	function validate_blacklisted_emails( $is_valid, $user_id ) {
		if ( $is_valid && $user_id && '' != ( $blacklist = get_option( 'alg_wc_ev_email_blacklist', '' ) ) ) {
			$user      = new WP_User( $user_id );
			$blacklist = str_replace( PHP_EOL, ',', $blacklist );
			$blacklist = array_map( 'trim', explode( ',', $blacklist ) );
			foreach ( $blacklist as $email ) {
				if ( $email === $user->user_email || $this->wildcard_match( $email, $user->user_email ) ) {
					return false;
				}
			}
		}
		return $is_valid;
	}

	/**
	 * is_user_verified_social_login.
	 *
	 * @version 1.6.0
	 * @since   1.6.0
	 * @todo    [dev] (next) "YITH WooCommerce Social Login"
	 */
	function is_user_verified_social_login( $is_user_verified, $user_id ) {
		if ( 'yes' === get_option( 'alg_wc_ev_accept_social_login', 'no' ) ) {
			if ( defined( 'WOO_SLG_USER_META_PREFIX' ) ) {
				$wooslg_by_social_login = get_user_meta( $user_id, WOO_SLG_USER_META_PREFIX . 'by_social_login', true );
				if ( 'true' === $wooslg_by_social_login ) {
					return true;
				}
			}
		}
		return $is_user_verified;
	}

	/**
	 * maybe_redirect_to_myaccount_filter.
	 *
	 * @version 1.5.0
	 * @since   1.5.0
	 */
	function maybe_redirect_to_myaccount_filter( $redirect_to, $user_id ) {
		return ( 'yes' === get_option( 'alg_wc_ev_prevent_login_after_checkout_block_thankyou', 'no' ) ? wc_get_page_permalink( 'myaccount' ) : $redirect_to );
	}

	/**
	 * maybe_redirect_to_myaccount_action.
	 *
	 * @version 1.5.0
	 * @since   1.5.0
	 */
	function maybe_redirect_to_myaccount_action( $user_id ) {
		if ( 'yes' === get_option( 'alg_wc_ev_prevent_login_after_checkout_block_thankyou', 'no' ) ) {
			wp_safe_redirect( add_query_arg( 'alg_wc_ev_activate_account_message', $user_id, wc_get_page_permalink( 'myaccount' ) ) );
			exit;
		}
	}

	/**
	 * block_customer_order_emails.
	 *
	 * @version 1.5.0
	 * @since   1.5.0
	 * @todo    [dev] (maybe) optional `guest`
	 * @todo    [dev] (maybe) delay (i.e. not block)
	 */
	function block_customer_order_emails( $is_enabled, $order, $email ) {
		return ( is_a( $order, 'WC_Order' ) && ( $user_id = $order->get_customer_id() ) && $this->core->is_user_verified_by_user_id( $user_id ) ? $is_enabled : false );
	}

	/**
	 * get_block_guest_add_to_cart_notice.
	 *
	 * @version 1.7.0
	 * @since   1.5.0
	 */
	function get_block_guest_add_to_cart_notice() {
		$notice = do_shortcode( get_option( 'alg_wc_ev_block_guest_add_to_cart_notice',
			__( 'You need to <a href="%myaccount_url%" target="_blank">register</a> and verify your email before adding products to the cart.', 'emails-verification-for-woocommerce' ) ) );
		$placeholders = array(
			'%myaccount_url%' => wc_get_page_permalink( 'myaccount' ),
		);
		return str_replace( array_keys( $placeholders ), $placeholders, $notice );
	}

	/**
	 * block_guest_add_to_cart_validation.
	 *
	 * @version 1.5.0
	 * @since   1.5.0
	 */
	function block_guest_add_to_cart_validation( $passed, $product_id, $quantity ) {
		if ( ! is_user_logged_in() ) {
			if ( ! wp_doing_ajax() ) {
				wc_add_notice( $this->get_block_guest_add_to_cart_notice(), 'error' );
			} else {
				add_filter( 'woocommerce_cart_redirect_after_error', array( $this, 'block_guest_add_to_cart_ajax_redirect' ), PHP_INT_MAX, 2 );
			}
			return false;
		}
		return $passed;
	}

	/**
	 * block_guest_add_to_cart_ajax_redirect.
	 *
	 * @version 1.5.0
	 * @since   1.5.0
	 */
	function block_guest_add_to_cart_ajax_redirect( $url, $product_id ) {
		return add_query_arg( 'alg_wc_ev_guest', true, $url );
	}

	/**
	 * block_guest_add_to_cart_ajax_error.
	 *
	 * @version 1.5.0
	 * @since   1.5.0
	 */
	function block_guest_add_to_cart_ajax_error() {
		if ( isset( $_GET['alg_wc_ev_guest'] ) ) {
			wc_add_notice( $this->get_block_guest_add_to_cart_notice(), 'error' );
		}
	}

	/**
	 * maybe_wrap_in_wrap_in_wc_email_template.
	 *
	 * @version 1.5.0
	 * @since   1.5.0
	 */
	function maybe_wrap_in_wrap_in_wc_email_template( $email_content ) {
		if ( 'wc' === get_option( 'alg_wc_ev_email_template', 'plain' ) ) {
			$email_content = $this->wrap_in_wc_email_template( $email_content,
				do_shortcode( get_option( 'alg_wc_ev_email_template_wc_heading', __( 'Activate your account', 'emails-verification-for-woocommerce' ) ) ) );
		}
		return $email_content;
	}

	/**
	 * wrap_in_wc_email_template.
	 *
	 * @version 1.9.0
	 * @since   1.0.0
	 */
	function wrap_in_wc_email_template( $content, $email_heading = '' ) {
		$header = $this->get_wc_email_part( 'header', $email_heading );
		$footer = $this->get_wc_email_part( 'footer' );
		if ( class_exists( 'WC_Emails' ) && method_exists( 'WC_Emails', 'instance' ) && method_exists( 'WC_Emails', 'replace_placeholders' ) ) {
			$emails = WC_Emails::instance();
			$footer = $emails->replace_placeholders( $footer );
		} else {
			$footer = $this->replace_placeholders( $footer );
		}
		return $header . $content . $footer;
	}

	/**
	 * Replace placeholder text in strings.
	 *
	 * @version 1.9.0
	 * @since   1.9.0
	 * @param   string $string Email footer text.
	 * @return  string         Email footer text with any replacements done.
	 * @see     /woocommerce/includes/class-wc-emails.php
	 */
	function replace_placeholders( $string ) {
		$domain = wp_parse_url( home_url(), PHP_URL_HOST );

		return str_replace(
			array(
				'{site_title}',
				'{site_address}',
				'{site_url}',
				'{woocommerce}',
				'{WooCommerce}',
			),
			array(
				wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES ),
				$domain,
				$domain,
				'<a href="https://woocommerce.com">WooCommerce</a>',
				'<a href="https://woocommerce.com">WooCommerce</a>',
			),
			$string
		);
	}

	/**
	 * get_wc_email_part.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function get_wc_email_part( $part, $email_heading = '' ) {
		ob_start();
		switch ( $part ) {
			case 'header':
				wc_get_template( 'emails/email-header.php', array( 'email_heading' => $email_heading ) );
				break;
			case 'footer':
				wc_get_template( 'emails/email-footer.php' );
				break;
		}
		return ob_get_clean();
	}

	/**
	 * maybe_add_wc_email_style.
	 *
	 * @version 1.9.2
	 * @since   1.9.2
	 * @todo    [dev] (test) if it's required in case of `mail` function
	 * @todo    [dev] (maybe) add style only if are sure that it's not plain text message
	 */
	function maybe_add_wc_email_style( $message, $func ) {
		if ( in_array( $func, array( 'mail', 'wp_mail' ) ) ) {
			$email   = new WC_Email();
			$message = $email->style_inline( $message );
		}
		return $message;
	}

	/**
	 * maybe_send_admin_email.
	 *
	 * @version 1.9.2
	 * @since   1.5.0
	 * @todo    [dev] (next) optional WC template
	 */
	function maybe_send_admin_email( $user_id ) {
		if (
			'yes' === get_option( 'alg_wc_ev_admin_email', 'no' ) &&
			'' == get_user_meta( $user_id, 'alg_wc_ev_admin_email_sent', true )
		) {
			$recipient    = get_option( 'alg_wc_ev_admin_email_recipient', '' );
			if ( '' === $recipient ) {
				$recipient = get_bloginfo( 'admin_email' );
			}
			$subject      = get_option( 'alg_wc_ev_admin_email_subject', __( 'User email has been verified', 'emails-verification-for-woocommerce' ) );
			$heading      = get_option( 'alg_wc_ev_admin_email_heading', __( 'User account has been activated', 'emails-verification-for-woocommerce' ) );
			$user         = new WP_User( $user_id );
			$placeholders = array(
				'%user_id%'                => $user_id,
				'%user_login%'             => $user->user_login,
				'%user_nicename%'          => $user->user_nicename,
				'%user_email%'             => $user->user_email,
				'%user_url%'               => $user->user_url,
				'%user_registered%'        => $user->user_registered,
				'%user_display_name%'      => $user->display_name,
				'%user_roles%'             => implode( ', ', $user->roles ),
				'%user_first_name%'        => $user->first_name,
				'%user_last_name%'         => $user->last_name,
				'%admin_user_profile_url%' => admin_url( 'user-edit.php?user_id=' . $user_id ),
			);
			$content      = wpautop( str_replace( array_keys( $placeholders ), $placeholders, get_option( 'alg_wc_ev_admin_email_content',
				sprintf( __( 'User %s has just verified his email (%s).', 'emails-verification-for-woocommerce' ),
					'<a href="%admin_user_profile_url%">%user_login%</a>', '%user_email%' ) ) ) );
			$content      = $this->wrap_in_wc_email_template( $content, $heading );
			$this->core->emails->send_mail( $recipient, $subject, $content );
			update_user_meta( $user_id, 'alg_wc_ev_admin_email_sent', time() );
		}
	}

	/**
	 * email_subject.
	 *
	 * @version 1.1.1
	 * @since   1.1.0
	 */
	function email_subject( $subject ) {
		return get_option( 'alg_wc_ev_email_subject', __( 'Please activate your account', 'emails-verification-for-woocommerce' ) );
	}

	/**
	 * email_content.
	 *
	 * @version 1.1.1
	 * @since   1.1.0
	 */
	function email_content( $content ) {
		return get_option( 'alg_wc_ev_email_content',
			__( 'Please click the following link to verify your email:<br><br><a href="%verification_url%">%verification_url%</a>', 'emails-verification-for-woocommerce' ) );
	}

	/**
	 * settings.
	 *
	 * @version 1.7.0
	 * @since   1.1.0
	 */
	function settings( $value, $type = '', $args = array() ) {
		if ( 'min' === $type ) {
			return array( 'min' => $args[0] );
		}
		return '';
	}

}

endif;

return new Alg_WC_Email_Verification_Pro();
